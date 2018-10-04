<?php

include('includes/session.php');
$Title = _('Work Order Status Inquiry');
include('includes/header.php');

if (isset($_GET['WO'])) {
	$SelectedWO = $_GET['WO'];
} elseif (isset($_POST['WO'])){
	$SelectedWO = $_POST['WO'];
} else {
	unset($SelectedWO);
}
if (isset($_GET['StockID'])) {
	$StockID = $_GET['StockID'];
} elseif (isset($_POST['StockID'])){
	$StockID = $_POST['StockID'];
} else {
	unset($StockID);
}


$ErrMsg = _('Could not retrieve the details of the selected work order item');
$WOResult = DB_query("SELECT workorders.loccode,
							 locations.locationname,
							 workorders.requiredby,
							 workorders.startdate,
							 workorders.closed,
							 stockmaster.description,
							 stockmaster.decimalplaces,
							 stockmaster.units,
							 woitems.qtyreqd,
							 woitems.qtyrecd
						FROM workorders INNER JOIN locations
						ON workorders.loccode=locations.loccode
						INNER JOIN woitems
						ON workorders.wo=woitems.wo
						INNER JOIN stockmaster
						ON woitems.stockid=stockmaster.stockid
						INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
						WHERE woitems.stockid='" . $StockID . "'
						AND woitems.wo ='" . $SelectedWO . "'",
						$ErrMsg);

if (DB_num_rows($WOResult)==0){
	echo    prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
	include('includes/footer.php');
	exit;
}
$WORow = DB_fetch_array($WOResult);

echo '<div class="row">
<div class="col-xs-4"><a href="'. $RootPath . '/SelectWorkOrder.php" class="btn btn-default">' . _('Back To Work Orders'). '</a></div>';
echo '<div class="col-xs-4"><a href="'. $RootPath . '/WorkOrderCosting.php?WO=' .  $SelectedWO . '" class="btn btn-default">' . _('Back To Costing'). '</a></div></div><br /><br />';

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title.'</h1></a></div>';

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<tr>
		<td>' . _('Work order Number') . ':</td>
		<td>' . $SelectedWO  . '</td>
		<td>' . _('Item') . ':</td>
		<td>' . $StockID . ' - ' . $WORow['description'] . '</td>
	</tr>
 	<tr>
		<td>' . _('Manufactured at') . ':</td>
		<td>' . $WORow['locationname'] . '</td>
		<td>' . _('Required By') . ':</td>
		<td>' . ConvertSQLDate($WORow['requiredby']) . '</td>
	</tr>
 	<tr>
		<td>' . _('Quantity Ordered') . ':</td>
		<td class="number">' . locale_number_format($WORow['qtyreqd'],$WORow['decimalplaces']) . '</td>
		<td colspan="2">' . $WORow['units'] . '</td>
	</tr>
 	<tr>
		<td>' . _('Already Received') . ':</td>
		<td class="number">' . locale_number_format($WORow['qtyrecd'],$WORow['decimalplaces']) . '</td>
		<td colspan="2">' . $WORow['units'] . '</td>
	</tr>
	<tr>
		<td>' . _('Start Date') . ':</td>
		<td>' . ConvertSQLDate($WORow['startdate']) . '</td>
	</tr>
	</table></div></div></div>
	<br />';

	//set up options for selection of the item to be issued to the WO
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Material Requirements For this Work Order') . '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
			
	echo '<thead>
	<tr>
			<th colspan="2">' . _('Item') . '</th>
			<th>' . _('Qty Required') . '</th>
			<th>' . _('Qty Issued') . '</th>
		</tr>
		</thead>';

	$RequirmentsResult = DB_query("SELECT worequirements.stockid,
										stockmaster.description,
										stockmaster.decimalplaces,
										autoissue,
										qtypu
									FROM worequirements INNER JOIN stockmaster
									ON worequirements.stockid=stockmaster.stockid
									WHERE wo='" . $SelectedWO . "'
									AND worequirements.parentstockid='" . $StockID . "'");
		$IssuedAlreadyResult = DB_query("SELECT stockid,
						SUM(-qty) AS total
					FROM stockmoves
					WHERE stockmoves.type=28
					AND reference='".$SelectedWO."'
					GROUP BY stockid");
	while ($IssuedRow = DB_fetch_array($IssuedAlreadyResult)){
		$IssuedAlreadyRow[$IssuedRow['stockid']] = $IssuedRow['total'];
	}

	while ($RequirementsRow = DB_fetch_array($RequirmentsResult)){
		if ($RequirementsRow['autoissue']==0){
			echo '<tr>
					<td>' . _('Manual Issue') . '</td>
					<td>' . $RequirementsRow['stockid'] . ' - ' . $RequirementsRow['description'] . '</td>';
		} else {
			echo '<tr>
					<td class="notavailable">' . _('Auto Issue') . '</td>
					<td class="notavailable">' .$RequirementsRow['stockid'] . ' - ' . $RequirementsRow['description']  . '</td>';
		}
		if (isset($IssuedAlreadyRow[$RequirementsRow['stockid']])){
			$Issued = $IssuedAlreadyRow[$RequirementsRow['stockid']];
			unset($IssuedAlreadyRow[$RequirementsRow['stockid']]);
		}else{
			$Issued = 0;
		}
		echo '<td class="number">'.locale_number_format($WORow['qtyreqd']*$RequirementsRow['qtypu'],$RequirementsRow['decimalplaces']).'</td>
			<td class="number">'.locale_number_format($Issued,$RequirementsRow['decimalplaces']).'</td></tr>';
	}
	/* Now do any additional issues of items not in the BOM */
	if(isset($IssuedAlreadyRow) AND count($IssuedAlreadyRow)>0){
		$AdditionalStocks = implode("','",array_keys($IssuedAlreadyRow));
		$RequirementsSQL = "SELECT stockid,
						description,
							decimalplaces
				FROM stockmaster WHERE stockid IN ('".$AdditionalStocks."')";
		$RequirementsResult = DB_query($RequirementsSQL);
			$AdditionalStocks = array();
			while($myrow = DB_fetch_array($RequirementsResult)){
				$AdditionalStocks[$myrow['stockid']]['description'] = $myrow['description'];
				$AdditionalStocks[$myrow['stockid']]['decimalplaces'] = $myrow['decimalplaces'];
			}
			foreach ($IssuedAlreadyRow as $StockID=>$Issued) {
			echo '<tr>
				<td>'._('Additional Issue').'</td>
				<td>'.$StockID . ' - '.$AdditionalStocks[$StockID]['description'].'</td>';
				echo '<td class="number">0</td>
					<td class="number">'.locale_number_format($Issued,$AdditionalStocks[$StockID]['decimalplaces']).'</td>
					</tr>';
			}
		}

	echo '</table></div></div></div></div><br />';
	include('includes/footer.php');

?>
