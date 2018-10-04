<?php

include('includes/DefineSuppTransClass.php');

/* Session started here for password checking and authorisation level check */
include('includes/session.php');

$Title = _('Shipment Charges or Credits');

include('includes/header.php');



if (!isset($_SESSION['SuppTrans'])){
	echo   prnMsg(_('Shipment charges or credits are entered against supplier invoices or credit notes respectively') . '. ' . _('To enter supplier transactions the supplier must first be selected from the supplier selection screen') . ', ' . _('then the link to enter a supplier invoice or credit note must be clicked on'),'info');
	echo '<div class="row" align="center"><a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-info">' . _('Select A Supplier') . '</a></div><br />';
	exit;
	/*It all stops here if there aint no supplier selected and invoice/credit initiated ie $_SESSION['SuppTrans'] started off*/
}

/*If the user hit the Add to transaction button then process this first before showing  all GL codes on the invoice otherwise it wouldnt show the latest addition*/

if (isset($_POST['AddShiptChgToInvoice'])){

	$InputError = False;
	if ($_POST['ShiptRef'] == ''){
		if ($_POST['ShiptSelection']==''){
			echo prnMsg(_('Shipment charges must reference a shipment. It appears that no shipment has been entered'),'error');
			$InputError = True;
		} else {
			$_POST['ShiptRef'] = $_POST['ShiptSelection'];
		}
	} else {
		$result = DB_query("SELECT shiptref FROM shipments WHERE shiptref='". $_POST['ShiptRef'] . "'");
		if (DB_num_rows($result)==0) {
			echo prnMsg(_('The shipment entered manually is not a valid shipment reference. If you do not know the shipment reference, select it from the list'),'error');
			$InputError = True;
		}
	}

	if (!is_numeric(filter_number_format($_POST['Amount']))){
		echo prnMsg(_('The amount entered is not numeric') . '. ' . _('This shipment charge cannot be added to the invoice'),'error');
		$InputError = True;
	}

	if ($InputError == False){
		$_SESSION['SuppTrans']->Add_Shipt_To_Trans($_POST['ShiptRef'],
													filter_number_format($_POST['Amount']));
		unset($_POST['ShiptRef']);
		unset($_POST['Amount']);
	}
}

if (isset($_GET['Delete'])){

	$_SESSION['SuppTrans']->Remove_Shipt_From_Trans($_GET['Delete']);
}

/*Show all the selected ShiptRefs so far from the SESSION['SuppInv']->Shipts array */
if ($_SESSION['SuppTrans']->InvoiceOrCredit=='Invoice'){
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' .  _('Shipment charges on Invoice') . '<br /><small> ';
} else {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Shipment credits on Credit Note') . ' <br /><small>';
}
echo $_SESSION['SuppTrans']->SuppReference . ' ' ._('From') . ' ' . $_SESSION['SuppTrans']->SupplierName;
echo '</small></h1></a></div>';


if ($_SESSION['SuppTrans']->InvoiceOrCredit == 'Invoice'){
	echo '<div class="row" align="center">
<a href="' . $RootPath . '/SupplierInvoice.php" class="btn btn-info">' . _('Back to Invoice Entry') . '</a></div><br />';
} else {
	echo '<div class="row" align="center">
<a href="' . $RootPath . '/SupplierCredit.php" class="btn btn-info">' . _('Back to Credit Note Entry') . '</a></div><br />';
}



echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
$TableHeader = '<thead><tr>
		<th>' . _('Shipment') . '</th>
		<th>' . _('Amount') . '</th>
		<th>' . _('Action') . '</th>
		</tr></thead>';
echo $TableHeader;

$TotalShiptValue = 0;

foreach ($_SESSION['SuppTrans']->Shipts as $EnteredShiptRef){

	echo '<tr><td>' . $EnteredShiptRef->ShiptRef . '</td>
		<td class="number">' . locale_number_format($EnteredShiptRef->Amount,2) . '</td>
		<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $EnteredShiptRef->Counter . '" class="btn btn-danger">' . _('Delete') . '</a></td></tr>';

	$TotalShiptValue = $TotalShiptValue + $EnteredShiptRef->Amount;

}

echo '<tr>
	<td class="number">' . _('Total') . ':</td>
	<td class="number">' . locale_number_format($TotalShiptValue,2) . '</td>
</tr>
</table></div></div></div><br />';

/*Set up a form to allow input of new Shipment charges */
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_POST['ShiptRef'])) {
	$_POST['ShiptRef']='';
}
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Shipment Reference') . '</label>
		<input class="form-control" pattern="[1-9][\d]{0,10}" title="'._('The shiment Ref should be positive integer').'" placeholder="'._('positive integer').'" name="ShiptRef" size="12" maxlength="11" value="' .  $_POST['ShiptRef'] . '" /></div>
	</div>';
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select Shipment ') . '
			</label>
		<select name="ShiptSelection" class="form-control">';

$sql = "SELECT shiptref,
				vessel,
				eta,
				suppname
			FROM shipments INNER JOIN suppliers
				ON shipments.supplierid=suppliers.supplierid
			WHERE closed='0'";

$result = DB_query($sql);

while ($myrow = DB_fetch_array($result)) {
	if (isset($_POST['ShiptSelection']) and $myrow['shiptref']==$_POST['ShiptSelection']) {
		echo '<option selected="selected" value="';
	} else {
		echo '<option value="';
	}
	echo $myrow['shiptref'] . '">' . $myrow['shiptref'] . ' - ' . $myrow['vessel'] . ' ' . _('ETA') . ' ' . ConvertSQLDate($myrow['eta']) . ' ' . _('from') . ' ' . $myrow['suppname']  . '</option>';
}

echo '</select></div>
	</div>';

if (!isset($_POST['Amount'])) {
	$_POST['Amount']=0;
}
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Amount') . '</label>
	<input type="text"  class="form-control" required="required" title="'._('The input must be non zero number').'" placeholder="'._('Non zero number').'" name="Amount" size="12" maxlength="11" value="' .  locale_number_format($_POST['Amount'],$_SESSION['SuppTrans']->CurrDecimalPlaces) . '" /></div>
	</div>
	</div>';

echo '
	<div class="row" align="center">
		<input type="submit" class="btn btn-success" name="AddShiptChgToInvoice" value="' . _('Enter Shipment Charge') . '" />
	</div>
   <br />
	</form>';

include('includes/footer.php');
?>
