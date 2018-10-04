<?php

include('includes/session.php');
$Title=_('Reprint a GRN');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (!isset($_POST['PONumber'])) {
	$_POST['PONumber']='';
}

echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Purchase Order Number') . '</label>
			' . '<input type="text" name="PONumber" class="form-control" size="7" value="'.$_POST['PONumber'].'" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"><br />
<input type="submit" class="btn btn-success" name="Show" value="' . _('Show') . '" /></div>
		</div>
	</div>
   
    
	</form></div></div>';

if (isset($_POST['Show'])) {
	if ($_POST['PONumber']=='') {
		echo '<br />';
		echo prnMsg( _('You must enter a purchase order number in the box above'), 'warn');
		include('includes/footer.php');
		exit;
	}
	$sql="SELECT count(orderno)
				FROM purchorders
				WHERE orderno='" . $_POST['PONumber'] ."'";
	$result=DB_query($sql);
	$myrow=DB_fetch_row($result);
	if ($myrow[0]==0) {
		echo '<br />';
		echo prnMsg( _('This purchase order does not exist on the system. Please try again.'), 'warn');
		include('includes/footer.php');
		exit;
	}
	$sql="SELECT grnbatch,
				grns.grnno,
				grns.podetailitem,
				grns.itemcode,
				grns.itemdescription,
				grns.deliverydate,
				grns.qtyrecd,
				suppinvstogrn.suppinv,
				suppliers.suppname,
				stockmaster.decimalplaces
			FROM grns INNER JOIN suppliers
			ON grns.supplierid=suppliers.supplierid
			LEFT JOIN suppinvstogrn ON grns.grnno=suppinvstogrn.grnno
			INNER JOIN purchorderdetails
			ON grns.podetailitem=purchorderdetails.podetailitem
			INNER JOIN purchorders on purchorders.orderno=purchorderdetails.orderno
			INNER JOIN locationusers ON locationusers.loccode=purchorders.intostocklocation AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			LEFT JOIN stockmaster
			ON grns.itemcode=stockmaster.stockid
			WHERE purchorderdetails.orderno='" . $_POST['PONumber'] ."'";
	$result=DB_query($sql);
	if (DB_num_rows($result)==0) {
		echo '<br />';
		echo prnMsg( _('There are no GRNs for this purchase order that can be reprinted.'), 'warn');
		include('includes/footer.php');
		exit;
	}

	echo '<br />
			<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h2>' . _('GRNs for Purchase Order No') .' ' . $_POST['PONumber'] . '</h2></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			
			<thead>
			<tr>
				<th>' . _('Supplier') . '</th>
				<th>' . _('PO Order line') . '</th>
				<th>' . _('GRN Number') . '</th>
				<th>' . _('Item Code') . '</th>
				<th>' . _('Item Description') . '</th>
				<th>' . _('Delivery Date') . '</th>
				<th>' . _('Quantity Received') . '</th>
				<th>' . _('Invoice No') . '</th>
				<th colspan="2">' . _('Actions') . '</th>
			</tr></thead>';

	while ($myrow=DB_fetch_array($result)) {
		echo '<tr class="striped_row">
			<td>' . $myrow['suppname'] . '</td>
			<td class="number">' . $myrow['podetailitem'] . '</td>
			<td class="number">' . $myrow['grnbatch'] . '</td>
			<td>' . $myrow['itemcode'] . '</td>
			<td>' . $myrow['itemdescription'] . '</td>
			<td>' . $myrow['deliverydate'] . '</td>
			<td>' . locale_number_format($myrow['qtyrecd'], $myrow['decimalplaces']) . '</td>
			<td>' . $myrow['suppinv'] . '</td>
			<td><a href="PDFGrn.php?GRNNo=' . $myrow['grnbatch'] .'&PONo=' . $_POST['PONumber'] . '" class="btn btn-warning">' . _('Reprint GRN ') . '</a></td>
			<td><a href="PDFQALabel.php?GRNNo=' . $myrow['grnbatch'] .'&PONo=' . $_POST['PONumber'] . '" class="btn  btn-warning">' . _('Reprint Labels') . '</a></td>
		</tr>';
	}
	echo '</table></div></div></div></div>';
}

include('includes/footer.php');

?>
