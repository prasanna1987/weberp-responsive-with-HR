<?php

include('includes/session.php');

$Title = _('All Stock Movements By Location');

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>', ' ', $Title, '
	</h1></a></div>';
	
echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
	<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Stock Location'), '</label>
<select required="required" name="StockLocation" class="form-control">';

$SQL = "SELECT locationname,
				locations.loccode
		FROM locations
		INNER JOIN locationusers
			ON locationusers.loccode=locations.loccode
			AND locationusers.userid='" . $_SESSION['UserID'] . "'
			AND locationusers.canview=1
		ORDER BY locationname";

echo '<option selected="selected" value="All">', _('All Locations'), '</option>';

if (!isset($_POST['StockLocation'])) {
	$_POST['StockLocation'] = 'All';
}

$ResultStkLocs = DB_query($SQL);

while ($MyRow = DB_fetch_array($ResultStkLocs)) {
	if (isset($_POST['StockLocation']) and $_POST['StockLocation'] != 'All') {
		if ($MyRow['loccode'] == $_POST['StockLocation']) {
			echo '<option selected="selected" value="', $MyRow['loccode'], '">', $MyRow['locationname'], '</option>';
		} else {
			echo '<option value="', $MyRow['loccode'], '">', $MyRow['locationname'], '</option>';
		}
	} elseif ($MyRow['loccode'] == $_SESSION['UserStockLocation']) {
		echo '<option selected="selected" value="' . $MyRow['loccode'] . '">' . $MyRow['locationname'] . '</option>';
		$_POST['StockLocation']=$MyRow['loccode'];
	} else {
		echo '<option value="', $MyRow['loccode'], '">', $MyRow['locationname'], '</option>';
	}
}

echo '</select></div></div>';

if (!isset($_POST['BeforeDate']) or !Is_date($_POST['BeforeDate'])) {
	$_POST['BeforeDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['AfterDate']) or !Is_date($_POST['AfterDate'])) {
	$_POST['AfterDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, Date('m') - 1, Date('d'), Date('y')));
}
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label"> ', _('Date From'), '</label>
 <input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="AfterDate" size="11" required="required" maxlength="10" value="', $_POST['AfterDate'], '" />',
	'</div>
	 </div>

<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label"> ', _('Date To'), '</label>
 <input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="BeforeDate" size="11" required="required" maxlength="10" value="', $_POST['BeforeDate'], '" /></div></div>',
	'
	 </div>
	<div class="row" align="center">
	<div>
<div class="form-group">  
		<input type="submit" class="btn btn-success" name="ShowMoves" value="', _('Show'), '" />
	</div>
	</div>
	</div>
	';

if ($_POST['StockLocation'] == 'All') {
	$_POST['StockLocation'] = '%%';
}

$SQLBeforeDate = FormatDateForSQL($_POST['BeforeDate']);
$SQLAfterDate = FormatDateForSQL($_POST['AfterDate']);

$SQL = "SELECT stockmoves.stockid,
				stockmoves.stkmoveno,
				systypes.typename,
				stockmoves.type,
				stockmoves.transno,
				stockmoves.trandate,
				stockmoves.debtorno,
				stockmoves.branchcode,
				stockmoves.qty,
				stockmoves.reference,
				stockmoves.price,
				stockmoves.discountpercent,
				stockmoves.newqoh,
				stockmaster.controlled,
				stockmaster.serialised,
				stockmaster.decimalplaces
			FROM stockmoves
			INNER JOIN systypes
				ON stockmoves.type=systypes.typeid
			INNER JOIN stockmaster
				ON stockmoves.stockid=stockmaster.stockid
			WHERE  stockmoves.loccode " . LIKE . " '" . $_POST['StockLocation'] . "'
				AND stockmoves.trandate >= '" . $SQLAfterDate . "'
				AND stockmoves.trandate <= '" . $SQLBeforeDate . "'
				AND hidemovt=0
			ORDER BY stkmoveno DESC";
$ErrMsg = _('The stock movements for the selected criteria could not be retrieved because');
$MovtsResult = DB_query($SQL, $ErrMsg);

if (DB_num_rows($MovtsResult) > 0) {
	echo '<div class="table-responsive">
<table id="general-table" class="table table-bordered">

			<thead> 
			<tr>
				<th>', _('Item Code'), '</th>
				<th>', _('Type'), '</th>
				<th>', _('Trans No'), '</th>
				<th>', _('Date'), '</th>
				<th>', _('Customer'), '</th>
				<th>', _('Quantity in Transaction'), '</th>
				<th>', _('Reference'), '</th>
				<th>', _('Price'), '</th>
				<th>', _('Discount'), '</th>
				<th>', _('QOH'), '</th>
				<th>', _('Serial No.'), '</th>
			</tr></thead> ';

	while ($MyRow = DB_fetch_array($MovtsResult)) {

		$DisplayTranDate = ConvertSQLDate($MyRow['trandate']);

		$SerialSQL = "SELECT serialno, moveqty FROM stockserialmoves WHERE stockmoveno='" . $MyRow['stkmoveno'] . "'";
		$SerialResult = DB_query($SerialSQL);

		$SerialText = '';
		while ($SerialRow = DB_fetch_array($SerialResult)) {
			if ($MyRow['serialised'] == 1) {
				$SerialText .= $SerialRow['serialno'] . '<br />';
			} else {
				$SerialText .= $SerialRow['serialno'] . ' Qty- ' . $SerialRow['moveqty'] . '<br />';
			}
		}

		echo '<tr class="striped_row">
				<td><a target="_blank" href="', $RootPath, '/StockStatus.php?StockID=', mb_strtoupper(urlencode($MyRow['stockid'])), '">', mb_strtoupper($MyRow['stockid']), '</a></td>
				<td>', $MyRow['typename'], '</td>
				<td>', $MyRow['transno'], '</td>
				<td>', $DisplayTranDate, '</td>
				<td>', $MyRow['debtorno'], '</td>
				<td class="number">', locale_number_format($MyRow['qty'], $MyRow['decimalplaces']), '</td>
				<td>', $MyRow['reference'], '</td>
				<td class="number">', locale_number_format($MyRow['price'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($MyRow['discountpercent'] * 100, 2), '%</td>
				<td class="number">', locale_number_format($MyRow['newqoh'], $MyRow['decimalplaces']), '</td>
				<td>', $SerialText, '</td>
			</tr>';
	}
	//end of while loop
	echo '</table></div>';
}
echo '</form></div></div>';

include ('includes/footer.php');

?>
