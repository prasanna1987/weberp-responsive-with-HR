<?php
include('includes/session.php');
$Title = _('Supplier Invoice and GRN inquiry');
include('includes/header.php');
if (isset($_GET['SelectedSupplier'])) {
	$SupplierID= $_GET['SelectedSupplier'];
} elseif (isset($_POST['SelectedSupplier'])){
	$SupplierID = $_POST['SelectedSupplier'];
} else {
	echo prnMsg(_('The page must be called from suppliers selected interface, please click following link to select the supplier'),'error');
	echo '<br /><p align="center"><a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-default">'. _('Back to Supplier') . '</a></p>';
	include('includes/footer.php');
	exit;
}
if (isset($_GET['SupplierName'])) {
	$SupplierName = $_GET['SupplierName'];
} 
if (!isset($_POST['SupplierRef']) OR trim($_POST['SupplierRef'])=='') {
	$_POST['SupplierRef'] = '';
	if (empty($_POST['GRNBatchNo']) AND empty($_POST['InvoiceNo'])) {
		$_POST['GRNBatchNo'] = '';
		$_POST['InvoiceNo'] = '';
	} elseif (!empty($_POST['GRNBatchNo']) AND !empty($_POST['InvoiceNo'])) {
		$_POST['InvoiceNo'] = '';
	}
} elseif (isset($_POST['GRNBatchNo']) OR isset($_POST['InvoiceNo'])) {
	$_POST['GRNBatchNo'] = '';
	$_POST['InvoiceNo'] = '';
}
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Supplier') . ': ' . $SupplierName . '</h1></a></div>';

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<input type="hidden" name="SelectedSupplier" value="' . $SupplierID . '" />';
	
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Delivery Note-part or full') . '</label>
		<input type="text" name="SupplierRef" value="' . $_POST['SupplierRef'] . '" class="form-control" size="20" maxlength="30" ></div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('GRN No') . '</label><input type="text" class="form-control" name="GRNBatchNo" value="' . $_POST['GRNBatchNo'] . '" size="6" maxlength="6" /></div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Invoice No') . '</label><input type="text" class="form-control" name="InvoiceNo" value="' . $_POST['InvoiceNo'] . '" size="11" maxlength="11" /></div>
	
	</div>
	</div>';
echo '<div class="row" align="center">
<div>

		<input type="submit" name="Submit" class="btn btn-success" value="' . _('Submit') . '" />
	</div></div><br />';
if (isset($_POST['Submit'])) {
	$Where = '';
	if (isset($_POST['SupplierRef']) AND trim($_POST['SupplierRef']) != '') {
		$SupplierRef = trim($_POST['SupplierRef']);
		$WhereSupplierRef = " AND grns.supplierref LIKE '%" . $SupplierRef . "%'";
		$Where .= $WhereSupplierRef;
	} elseif (isset($_POST['GRNBatchNo']) AND trim($_POST['GRNBatchNo']) != '') {
		$GRNBatchNo = trim($_POST['GRNBatchNo']);
		$WhereGRN = " AND grnbatch LIKE '%" . $GRNBatchNo . "%'";
		$Where .= $WhereGRN;
	} elseif (isset($_POST['InvoiceNo']) AND (trim($_POST['InvoiceNo']) != '')) {
		$InvoiceNo = trim($_POST['InvoiceNo']);
		$WhereInvoiceNo = " AND suppinv LIKE '%" . $InvoiceNo . "%'";
		$Where .= $WhereInvoiceNo;
	}
	$sql = "SELECT grnbatch, grns.supplierref, suppinv,purchorderdetails.orderno 
		FROM grns INNER JOIN purchorderdetails ON grns.podetailitem=purchorderdetails.podetailitem 
		LEFT JOIN suppinvstogrn ON grns.grnno=suppinvstogrn.grnno 
		WHERE supplierid='" . $SupplierID . "'" . $Where;
	$ErrMsg = _('Failed to retrieve supplier invoice and grn data');
	$result = DB_query($sql,$ErrMsg);
	if (DB_num_rows($result)>0) {
		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
			<tr>
					<th>' . _('Supplier Delivery Note') . '</th>
					<th>' . _('GRN Batch No') . '</th>
					<th>' . _('PO No') . '</th>
					<th>' . _('Invoice No') . '</th>
				</tr>
			</thead>
			<tbody>';

		while ($myrow = DB_fetch_array($result)){
			echo '<tr class="striped_row">
				<td>' . $myrow['supplierref'] . '</td>
				<td><a href="' . $RootPath .'/PDFGrn.php?GRNNo=' . $myrow['grnbatch'] . '&amp;PONo=' . $myrow['orderno'] . '" class="btn btn-warning">' . $myrow['grnbatch']. '</td>
				<td>' . $myrow['orderno'] . '</td>
				<td>' . $myrow['suppinv'] . '</td>
				</tr>';

		}
		echo '</tbody></table></div></div></div>';

	}

}
include('includes/footer.php');
?>
