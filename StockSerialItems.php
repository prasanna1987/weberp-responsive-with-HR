<?php

include('includes/session.php');
$Title = _('Stock Of Controlled Items');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . $Title. '</h1>
	</a></div>';

if (isset($_GET['StockID'])){
	if (ContainsIllegalCharacters ($_GET['StockID'])){
		echo prnMsg(_('The stock code sent to this page appears to be invalid'),'error');
		include('includes/footer.php');
		exit;
	}
	$StockID = trim(mb_strtoupper($_GET['StockID']));
} else {
	echo prnMsg( _('This page must be called with parameters specifying the item to show the serial references and quantities') . '. ' . _('It cannot be displayed without the proper parameters being passed'),'error');
	include('includes/footer.php');
	exit;
}

$result = DB_query("SELECT description,
							units,
							mbflag,
							decimalplaces,
							serialised,
							controlled,
							perishable
						FROM stockmaster
						WHERE stockid='".$StockID."'",
						_('Could not retrieve the requested item because'));

$myrow = DB_fetch_array($result);

$Description = $myrow['description'];
$UOM = $myrow['units'];
$DecimalPlaces = $myrow['decimalplaces'];
$Serialised = $myrow['serialised'];
$Controlled = $myrow['controlled'];
$Perishable = $myrow['perishable'];

if ($myrow['mbflag']=='K' OR $myrow['mbflag']=='A' OR $myrow['mbflag']=='D'){

	echo prnMsg(_('This item is either a kitset or assembly or a dummy part and cannot have a stock holding') . '. ' . _('This page cannot be displayed') . '. ' . _('Only serialised or controlled items can be displayed in this page'),'error');
	include('includes/footer.php');
	exit;
}

$result = DB_query("SELECT locationname
						FROM locations
						INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
						WHERE locations.loccode='" . $_GET['Location'] . "'",
						_('Could not retrieve the stock location of the item because'),
						_('The SQL used to lookup the location was'));

$myrow = DB_fetch_row($result);

$sql = "SELECT serialno,
				quantity,
				expirationdate
			FROM stockserialitems
			INNER JOIN locationusers ON locationusers.loccode=stockserialitems.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			WHERE stockserialitems.loccode='" . $_GET['Location'] . "'
			AND stockid = '" . $StockID . "'
			AND quantity <>0";


$ErrMsg = _('The serial numbers/batches held cannot be retrieved because');
$LocStockResult = DB_query($sql, $ErrMsg);

if ($Serialised==1){
	$tTitle = "Serialised items in ".$myrow[0].' -'. $StockID .'(' . _('In units of') . ' ' . $UOM . ') ';
} else {
	$tTitle="Controlled items in ".$myrow[0].' -'. $StockID .'(' . _('In units of') . ' ' . $UOM . ') ';
}

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>'.$tTitle.'</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

if ($Serialised == 1 and $Perishable==0){
	$tableheader = '<thead><tr>
						<th>' . _('Serial Number') . '</th>
						<th></th>
						<th>' . _('Serial Number') . '</th>
						<th></th>
						<th>' . _('Serial Number') . '</th>
					</tr></thead>';
} else if ($Serialised == 1 and $Perishable==1){
	$tableheader = '<thead><tr>
			<th>' . _('Serial Number') . '</th>
			<th>' . _('Expiry Date') . '</th>
			<th>' . _('Serial Number') . '</th>
			<th>' . _('Expiry Date') . '</th>
			<th>' . _('Serial Number') . '</th>
			<th>' . _('Expiry Date') . '</th>
			</tr></thead>';
} else if ($Serialised == 0 and $Perishable==0){
	$tableheader = '<thead><tr>
						<th>' . _('Batch/Bundle Ref') . '</th>
						<th>' . _('Quantity On Hand') . '</th>
						<th></th>
						<th>' . _('Batch/Bundle Ref') . '</th>
						<th>' . _('Quantity On Hand') . '</th>
						<th></th>
						<th>' . _('Batch/Bundle Ref') . '</th>
						<th>' . _('Quantity On Hand') . '</th>
					</tr></thead>';
} else if ($Serialised == 0 and $Perishable==1){
	$tableheader = '<thead><tr>
						<th>' . _('Batch/Bundle Ref') . '</th>
						<th>' . _('Quantity On Hand') . '</th>
						<th>' . _('Expiry Date') . '</th>
						<th></th>
						<th>' . _('Batch/Bundle Ref') . '</th>
						<th>' . _('Quantity On Hand') . '</th>
						<th>' . _('Expiry Date') . '</th>
						<th></th>
			   			<th>' . _('Batch/Bundle Ref') . '</th>
						<th>' . _('Quantity On Hand') . '</th>
						<th>' . _('Expiry Date') . '</th>
			   		</tr></thead>';
}
echo $tableheader;
$TotalQuantity =0;
$j = 1;
$Col =0;

while ($myrow=DB_fetch_array($LocStockResult)) {

	echo '<tr class="striped_row">';

	$TotalQuantity += $myrow['quantity'];

	if ($Serialised == 1 and $Perishable==0){
		echo '<td>' . $myrow['serialno'] . '</td>';
		echo '<th></th>';
	} else if ($Serialised == 1 and $Perishable==1) {
		echo '<td>' . $myrow['serialno'] . '</td>
				<td>' . ConvertSQLDate($myrow['expirationdate']). '</td>';
	} else if ($Serialised == 0 and $Perishable==0) {
		echo '<td>' . $myrow['serialno'] . '</td>
			<td class="number">' . locale_number_format($myrow['quantity'],$DecimalPlaces) . '</td>';
		echo '<th></th>';
	} else if ($Serialised == 0 and $Perishable==1){
		echo '<td>' . $myrow['serialno'] . '</td>
			<td class="number">' . locale_number_format($myrow['quantity'],$DecimalPlaces). '</td>
			<td>' . ConvertSQLDate($myrow['expirationdate']). '</td>
			<th></th>';
	}
	$j++;
	If ($j == 36){
		$j=1;
		echo $tableheader;
	}
//end of page full new headings if
	$Col++;
	if ($Col==3){
		echo '</tr>';
		$Col=0;
	}
}
//end of while loop
echo '</table></div></div></div></div><br />';
echo '<div class="row"><h5><strong>' . _('Total quantity') . ': ' . locale_number_format($TotalQuantity, $DecimalPlaces) . '</strong></h5></div><br />';

echo '</form>';
include('includes/footer.php');
?>
