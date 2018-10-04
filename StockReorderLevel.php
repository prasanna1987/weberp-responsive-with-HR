<?php


include('includes/session.php');
$Title = _('Stock Re-Order Level Maintenance');
include('includes/header.php');

if (isset($_GET['StockID'])){
	$StockID = trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	$StockID = trim(mb_strtoupper($_POST['StockID']));
}else{
	$StockID = '';
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' . $Title. '</h1></a>
	</div>';
	echo '<br /><p align="left"><a href="' . $RootPath . '/SelectProduct.php" class="btn btn-default">' . _('Back to Items') . '</a></p><br />';

$result = DB_query("SELECT description, units FROM stockmaster WHERE stockid='" . $StockID . "'");
$myrow = DB_fetch_row($result);

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

$sql = "SELECT locstock.loccode,
				locations.locationname,
				locstock.quantity,
				locstock.reorderlevel,
				stockmaster.decimalplaces,
				canupd
		FROM locstock INNER JOIN locations
			ON locstock.loccode=locations.loccode
		INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			INNER JOIN stockmaster
			ON locstock.stockid=stockmaster.stockid
		WHERE locstock.stockid = '" . $StockID . "'
		ORDER BY locations.locationname";

$ErrMsg = _('The stock held at each location cannot be retrieved because');
$DbgMsg = _('The SQL that failed was');

$LocStockResult = DB_query($sql, $ErrMsg, $DbgMsg);

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Stock Code') . '</label>
<input  type="text" class="form-control" data-type="no-illegal-chars" title="'._('The stock id should not contains illegal characters and blank or percentage mark is not allowed').'" required="required" name="StockID" size="21" value="' . $StockID . '" maxlength="20" /></div></div>

<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><br /></label>
<input type="submit" class="btn btn-success" name="Show" value="' . _('Show Re-Order Levels') . '" /></div>
		</div>
		</div>
		
		
		<div class="row gutter30">
<div class="col-xs-8">
<div class="block">
<div class="block-title"><h3>' . $StockID . ' - ' . $myrow[0] . ' (' . _('In Units of') . ' ' . $myrow[1] . ')</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		
		<thead><tr>
					<th class="ascending">' . _('Location') . '</th>
					<th class="ascending">' . _('Quantity On Hand') . '</th>
					<th class="ascending">' . _('Re-Order Level') . '</th>
		</tr></thead>
	
	<tbody>';

echo $TableHeader;

while ($myrow=DB_fetch_array($LocStockResult)) {

	if (isset($_POST['UpdateData'])
		AND $_POST['Old_' . $myrow['loccode']]!= filter_number_format($_POST[$myrow['loccode']])
		AND is_numeric(filter_number_format($_POST[$myrow['loccode']]))
		AND filter_number_format($_POST[$myrow['loccode']])>=0){

	   $myrow['reorderlevel'] = filter_number_format($_POST[$myrow['loccode']]);
	   $sql = "UPDATE locstock SET reorderlevel = '" . filter_number_format($_POST[$myrow['loccode']]) . "'
	   		WHERE stockid = '" . $StockID . "'
			AND loccode = '"  . $myrow['loccode'] ."'";
	   $UpdateReorderLevel = DB_query($sql);

	}
	if ($myrow['canupd']==1) {
		$UpdateCode='<input title="'._('Input safety stock quantity').'" type="text" class="form-control" name="%s" maxlength="10" size="10" value="%s" />
			<input type="hidden" name="Old_%s" value="%s" />';
	} else {
		$UpdateCode='<input type="hidden" name="%s">%s<input type="hidden" name="Old_%s" value="%s" />';
	}
	printf('<tr class="striped_row">
			<td>%s</td>
			<td class="number">%s</td>
			<td class="number">' . $UpdateCode . '</td>
			</tr>',
			$myrow['locationname'],
			locale_number_format($myrow['quantity'],$myrow['decimalplaces']),
			$myrow['loccode'],
			$myrow['reorderlevel'],
			$myrow['loccode'],
			$myrow['reorderlevel']);

}
//end of while loop

echo '</tbody></table></div></div></div></div>

	<div class="row" align="center"><div>
		<input type="submit" class="btn btn-info" name="UpdateData" value="' . _('Update') . '" />
		</div></div>
		
		';

echo '<div class="sub-header"></div><br /><div class="row">
<div class="col-xs-3"><a href="' . $RootPath . '/StockMovements.php?StockID=' . $StockID . '" class="btn btn-info">' . _('Show Stock Movements') . '</a></div>';
echo '<div class="col-xs-3"><a href="' . $RootPath . '/StockUsage.php?StockID=' . $StockID . '" class="btn btn-info">' . _('Show Stock Usage') . '</a></div>';
echo '<div class="col-xs-3"><a href="' . $RootPath . '/SelectSalesOrder.php?SelectedStockItem=' . $StockID . '" class="btn btn-info">' . _('Search Outstanding Sales Orders') . '</a></div>';
echo '<div class="col-xs-3"><a href="' . $RootPath . '/SelectCompletedOrder.php?SelectedStockItem=' . $StockID . '" class="btn btn-info">' . _('Search Completed Sales Orders') . '</a></div>';

echo '</div>
   <br />

	</form>';
include('includes/footer.php');
?>
