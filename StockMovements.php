<?php


include('includes/session.php');
$Title = _('Stock Movements');
/* nERP manual links before header.php */
$ViewTopic= 'Inventory';
$BookMark = 'InventoryMovement';
include('includes/header.php');

if (isset($_GET['StockID'])){
	$StockID = trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	$StockID = trim(mb_strtoupper($_POST['StockID']));
} else {
	$StockID = '';
}

$StockInfo = '';
if ('' != $StockID) {
	$result = DB_query("SELECT description, units FROM stockmaster WHERE stockid='" . $StockID . "'");
	$myrow = DB_fetch_row($result);

	$StockInfo = '<br /><b>' . $StockID . ' - ' . $myrow['0'] . ' : ' . _('in units of') . ' : ' . $myrow[1] . '</b>';
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', $Title,
		$StockInfo,
	'</h1></a></div>';
	echo '<div class="row gutter30">
<div class="col-xs-12">';

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '" method="post">
	
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';

if (!isset($_POST['BeforeDate']) OR !Is_date($_POST['BeforeDate'])){
   $_POST['BeforeDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['AfterDate']) OR !Is_date($_POST['AfterDate'])){
   $_POST['AfterDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,Date('m')-3,Date('d'),Date('y')));
}

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Stock Code'), '</label>
<input type="text" class="form-control" name="StockID" size="21" value="', $StockID, '" required="required" maxlength="20" />';
echo '</div></div>';
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">  ', _('Stock Location'), '</label>
<select required="required" name="StockLocation" class="form-control"> ';

$SQL = "SELECT locations.loccode,
				locationname
		FROM locations
		INNER JOIN locationusers
			ON locationusers.loccode=locations.loccode
				AND locationusers.userid='" .  $_SESSION['UserID'] . "'
				AND locationusers.canview=1
		ORDER BY locationname";

$resultStkLocs = DB_query($SQL);

while ($myrow=DB_fetch_array($resultStkLocs)){
	if (isset($_POST['StockLocation']) AND $_POST['StockLocation']!='All'){
		if ($myrow['loccode'] == $_POST['StockLocation']){
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		} else {
			echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		}
	} elseif ($myrow['loccode']==$_SESSION['UserStockLocation']){
		echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		$_POST['StockLocation']=$myrow['loccode'];
	} else {
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
}

echo '</select></div>
	</div>';
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Date From'), '</label>
			<input type="text" name="AfterDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" size="11" required="required" maxlength="10" value="', $_POST['AfterDate'], '" /></div></div></div>
<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Date To'), '</label>
			<input type="text" name="BeforeDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" size="11" required="required" maxlength="10" value="', $_POST['BeforeDate'], '" /> </div></div>
			<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" class="btn btn-success" name="ShowMoves" value="', _('Show'), '" />
		</div>
	</div></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
';
$SQLBeforeDate = FormatDateForSQL($_POST['BeforeDate']);
$SQLAfterDate = FormatDateForSQL($_POST['AfterDate']);

$SQL = "SELECT stockmoves.stockid,
				systypes.typename,
				stockmoves.stkmoveno,
				stockmoves.type,
				stockmoves.transno,
				stockmoves.trandate,
				stockmoves.userid,
				stockmoves.debtorno,
				stockmoves.branchcode,
				stockmoves.qty,
				stockmoves.reference,
				stockmoves.price,
				stockmoves.discountpercent,
				stockmoves.newqoh,
				stockmaster.decimalplaces,
				stockmaster.controlled,
				stockmaster.serialised
		FROM stockmoves
		INNER JOIN systypes
			ON stockmoves.type=systypes.typeid
		INNER JOIN stockmaster
			ON stockmoves.stockid=stockmaster.stockid
		WHERE  stockmoves.loccode='" . $_POST['StockLocation'] . "'
			AND stockmoves.trandate >= '" . $SQLAfterDate . "'
			AND stockmoves.stockid = '" . $StockID . "'
			AND stockmoves.trandate <= '" . $SQLBeforeDate . "'
			AND hidemovt=0
		ORDER BY stkmoveno DESC";

$ErrMsg = _('The stock movements for the selected criteria could not be retrieved because') . ' - ';
$DbgMsg = _('The SQL that failed was') . ' ';

$MovtsResult = DB_query($SQL, $ErrMsg, $DbgMsg);

if (DB_num_rows($MovtsResult) > 0) {
	$myrow = DB_fetch_array($MovtsResult);

	echo '<thead> 
	<tr>
		<th>' . _('Type') . '</th>
		<th>' . _('Number') . '</th>
		<th>' . _('Date') . '</th>
		<th>' . _('User ID') . '</th>
		<th>' . _('Customer') . '</th>
		<th>' . _('Branch') . '</th>
		<th>' . _('Quantity') . '</th>
		<th>' . _('Reference') . '</th>
		<th>' . _('Price') . '</th>
		<th>' . _('Discount') . '</th>
		<th>' . _('New Qty') . '</th>';
	if ($myrow['controlled'] == 1) {
		echo '<th>', _('Serial No.'), '</th>';
	}
	echo '</tr></thead> ';

	DB_data_seek($MovtsResult, 0);

	while ($myrow = DB_fetch_array($MovtsResult)) {

		$DisplayTranDate = ConvertSQLDate($myrow['trandate']);

		$SerialSQL = "SELECT serialno, moveqty FROM stockserialmoves WHERE stockmoveno='" . $myrow['stkmoveno'] . "'";
		$SerialResult = DB_query($SerialSQL);

		$SerialText = '';
		while ($SerialRow = DB_fetch_array($SerialResult)) {
			if ($myrow['serialised'] == 1) {
				$SerialText .= $SerialRow['serialno'] . '<br />';
			} else {
				$SerialText .= $SerialRow['serialno'] . ' Qty- ' . $SerialRow['moveqty'] . '<br />';
			}
		}

		if ($myrow['type']==10){
			/*its a sales invoice allow link to show invoice it was sold on*/

			echo '<tr class="striped_row">
					<td><a target="_blank" href="', $RootPath, '/PrintCustTrans.php?FromTransNo=', urlencode($myrow['transno']), '&amp;InvOrCredit=Invoice" class="btn btn-info">', $myrow['typename'], '</a></td>
					<td>', $myrow['transno'], '</td>
					<td>', $DisplayTranDate, '</td>
					<td>', $myrow['userid'], '</td>
					<td>', $myrow['debtorno'], '</td>
					<td>', $myrow['branchcode'], '</td>
					<td class="number">', locale_number_format($myrow['qty'], $myrow['decimalplaces']), '</td>
					<td>', $myrow['reference'], '</td>
					<td class="number">', locale_number_format($myrow['price'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>
					<td class="number">', locale_number_format($myrow['discountpercent'] * 100, 2), '%%</td>
					<td class="number">', locale_number_format($myrow['newqoh'], $myrow['decimalplaces']), '</td>';
			if ($myrow['controlled'] == 1) {
				echo '<td>', $SerialText, '</td>';
			}
			echo '</tr>';

		} elseif ($myrow['type']==11){

			echo '<tr class="striped_row">
					<td><a target="_blank" href="', $RootPath, '/PrintCustTrans.php?FromTransNo=', urlencode($myrow['transno']), '&amp;InvOrCredit=Credit" class="btn btn-info">', $myrow['typename'], '</a></td>
					<td>', $myrow['transno'], '</td>
					<td>', $DisplayTranDate, '</td>
					<td>', $myrow['userid'], '</td>
					<td>', $myrow['debtorno'], '</td>
					<td>', $myrow['branchcode'], '</td>
					<td class="number">', locale_number_format($myrow['qty'], $myrow['decimalplaces']), '</td>
					<td>', $myrow['reference'], '</td>
					<td class="number">', locale_number_format($myrow['price'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>
					<td class="number">', locale_number_format($myrow['discountpercent'] * 100, 2), '%%</td>
					<td class="number">', locale_number_format($myrow['newqoh'], $myrow['decimalplaces']), '</td>';
			if ($myrow['controlled'] == 1) {
				echo '<td>', $SerialText, '</td>';
			}
			echo '</tr>';

		} else {

			echo '<tr class="striped_row">
					<td>', $myrow['typename'], '</td>
					<td>', $myrow['transno'], '</td>
					<td>', $DisplayTranDate, '</td>
					<td>', $myrow['userid'], '</td>
					<td>', $myrow['debtorno'], '</td>
					<td>', $myrow['branchcode'], '</td>
					<td class="number">', locale_number_format($myrow['qty'], $myrow['decimalplaces']), '</td>
					<td>', $myrow['reference'], '</td>
					<td class="number">', locale_number_format($myrow['price'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>
					<td class="number">', locale_number_format($myrow['discountpercent'] * 100, 2), '%</td>
					<td class="number">', locale_number_format($myrow['newqoh'], $myrow['decimalplaces']), '</td>';
			if ($myrow['controlled'] == 1) {
				echo '<td>', $SerialText, '</td>';
			}
			echo '</tr>';

		}
	//end of page full new headings if
	}
	//end of while loop
}

echo '</table>
</div>
</div>
</div>
		<div class="row">
		<div class="col-xs-3">
			<a href="', $RootPath, '/StockStatus.php?StockID=', urlencode($StockID), '" class="btn btn-info">', _('Show Stock Status'), '</a>
			</div>
		
		<div class="col-xs-3">	
			<a href="', $RootPath, '/StockUsage.php?StockID=', urlencode($StockID), '&amp;StockLocation=', urlencode($_POST['StockLocation']), '" class="btn btn-info">', _('Show Stock Usage'), '</a>
		</div>	
			<div class="col-xs-3">	<a href="', $RootPath, '/SelectSalesOrder.php?SelectedStockItem=', urlencode($StockID), '&amp;StockLocation=', urlencode($_POST['StockLocation']), '" class="btn btn-info">', _('Search Outstanding Sales Orders'), '</a>
			</div>
			<div class="col-xs-3">	<a href="', $RootPath, '/SelectCompletedOrder.php?SelectedStockItem=', urlencode($StockID), '" class="btn btn-info">', _('Search Completed Sales Orders'), '</a>
			</div></div>
	
	</form></div></div>	<br />	';

include('includes/footer.php');

?>
