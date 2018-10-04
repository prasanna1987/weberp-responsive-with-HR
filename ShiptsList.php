<?php


//$PageSecurity = 2;
include ('includes/session.php');
$Title = _('Shipments Open Inquiry');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Open Shipments for').' ' . $_GET['SupplierName']. '.</h1></a></div>';

if (!isset($_GET['SupplierID']) or !isset($_GET['SupplierName'])){
	echo '<br />';
	echo prnMsg( _('This page must be given the supplier code to look for shipments for'), 'error');
	include('includes/footer.php');
	exit;
}

$SQL = "SELECT shiptref,
		vessel,
		eta
	FROM shipments
	WHERE supplierid='" . $_GET['SupplierID'] . "'";
$ErrMsg = _('No shipments were returned from the database because'). ' - '. DB_error_msg();
$ShiptsResult = DB_query($SQL, $ErrMsg);

if (DB_num_rows($ShiptsResult)==0){
       echo prnMsg(_('There are no open shipments currently set up for').' ' . $_GET['SupplierName'],'warn');
	include('includes/footer.php');
       exit;
}
/*show a table of the shipments returned by the SQL */

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
echo '<thead>
<tr>
		<th>' .  _('Reference'). '</th>
		<th>' .  _('Vessel'). '</th>
		<th>' .  _('ETA'). '</th>
		</tr></thead>';

$j = 1;

while ($myrow=DB_fetch_array($ShiptsResult)) {

       echo '<tr class="striped_row">
			<td><a href="'.$RootPath.'/Shipments.php?SelectedShipment='.$myrow['shiptref'].'" class="btn btn-info">' . $myrow['shiptref'] . '</a></td>
       		<td>' . $myrow['vessel'] . '</td>
		<td>' . ConvertSQLDate($myrow['eta']) . '</td>
		</tr>';

}
//end of while loop

echo '</table></div></div></div>';

include('includes/footer.php');

?>