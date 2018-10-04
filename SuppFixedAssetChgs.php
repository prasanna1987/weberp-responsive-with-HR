<?php

/*The supplier transaction uses the SuppTrans class to hold the information about the invoice
the SuppTrans class contains an array of Asset objects called Assets - containing details of all asset additions on a supplier invoice
Asset additions are posted to the debit of fixed asset category cost account if the creditors GL link is on */

include('includes/DefineSuppTransClass.php');

/* Session started here for password checking and authorisation level check */
include('includes/session.php');
$Title = _('Fixed Asset Charges or Credits');
//$ViewTopic = 'FixedAssets';
//$BookMark = 'AssetInvoices';
include('includes/header.php');


if (!isset($_SESSION['SuppTrans'])){
	echo   prnMsg(_('Fixed asset additions or credits are entered against supplier invoices or credit notes respectively') . '. ' . _('To enter supplier transactions the supplier must first be selected from the supplier selection screen') . ', ' . _('then the link to enter a supplier invoice or credit note must be clicked on'),'info');
	echo '<div class="row" align="center"><a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-info">' . _('Select A Supplier') . '</a></div><br />';
	exit;
	/*It all stops here if there aint no supplier selected and invoice/credit initiated ie $_SESSION['SuppTrans'] started off*/
}


if (isset($_POST['AddAssetToInvoice'])){

	$InputError = False;
	if ($_POST['AssetID'] == ''){
		if ($_POST['AssetSelection']==''){
			$InputError = True;
			echo prnMsg(_('A valid asset must be either selected from the list or entered'),'error');
		} else {
			$_POST['AssetID'] = $_POST['AssetSelection'];
		}
	} else {
		$result = DB_query("SELECT assetid FROM fixedassets WHERE assetid='" . $_POST['AssetID'] . "'");
		if (DB_num_rows($result)==0) {
			echo prnMsg(_('The asset ID entered manually is not a valid fixed asset. If you do not know the asset reference, select it from the list'),'error');
			$InputError = True;
			unset($_POST['AssetID']);
		}
	}

	if (!is_numeric(filter_number_format($_POST['Amount']))){
		echo prnMsg(_('The amount entered is not numeric. This fixed asset cannot be added to the invoice'),'error');
		$InputError = True;
		unset($_POST['Amount']);
	}

	if ($InputError == False){
		$_SESSION['SuppTrans']->Add_Asset_To_Trans($_POST['AssetID'],
													filter_number_format($_POST['Amount']));
		unset($_POST['AssetID']);
		unset($_POST['Amount']);
	}
}

if (isset($_GET['Delete'])){

	$_SESSION['SuppTrans']->Remove_Asset_From_Trans($_GET['Delete']);
}

/*Show all the selected ShiptRefs so far from the SESSION['SuppInv']->Shipts array */
if ($_SESSION['SuppTrans']->InvoiceOrCredit=='Invoice'){
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' .  _('Fixed Assets on Invoice') . '<br /><small> ';
} else {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Fixed Asset credits on Credit Note') . '<br /><small> ';
}
echo $_SESSION['SuppTrans']->SuppReference . ' ' ._('From') . ' ' . $_SESSION['SuppTrans']->SupplierName;
echo '</small></h1></a></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<thead>
		<tr>
					<th>' . _('Asset ID') . '</th>
					<th>' . _('Description') . '</th>
					<th>' . _('Amount') . '</th>
		</tr>
	</thead>
	<tbody>';

$TotalAssetValue = 0;

foreach ($_SESSION['SuppTrans']->Assets as $EnteredAsset){

	echo '<tr><td>' . $EnteredAsset->AssetID . '</td>
		<td>' . $EnteredAsset->Description . '</td>
		<td class="number">' . locale_number_format($EnteredAsset->Amount,$_SESSION['SuppTrans']->CurrDecimalPlaces). '</td>
		<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $EnteredAsset->Counter . '" class="btn btn-danger">' . _('Delete') . '</a></td></tr>';

	$TotalAssetValue +=  $EnteredAsset->Amount;

}

echo '</tbody>

		<tr>
	<td colspan="2"><strong><h4>' . _('Total') . ':</h4></strong></td>
	<td colspan="2"><h4><strong>' . locale_number_format($TotalAssetValue,$_SESSION['SuppTrans']->CurrDecimalPlaces) . '</strong></h4></td>
		</tr>
	</table></div></div></div><br />';

if ($_SESSION['SuppTrans']->InvoiceOrCredit == 'Invoice'){
	echo '<div class="row">
	<div class="col-xs-4">
			<a href="' . $RootPath . '/SupplierInvoice.php" class="btn btn-default">' . _('Back to Invoice Entry') . '</a>
			</div></div><br />';
} else {
	echo '<div class="row">
	<div class="col-xs-4">
			<a href="' . $RootPath . '/SupplierCredit.php" class="btn btn-default">' . _('Back to Credit Note Entry') . '</a>
			</div></div><br />';
}

/*Set up a form to allow input of new Shipment charges */
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post" />';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_POST['AssetID'])) {
	$_POST['AssetID']='';
}

echo   prnMsg(_('If you know the code enter it in the Asset ID input box, otherwise select the asset from the list below. Only  assets with no cost will show in the list'),'info');

echo '<br /><div class="row">';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">', _('Enter Asset ID'), '</label>
		<input class="form-control" maxlength="6" name="AssetID" pattern="[^-]{1,5}" placeholder="', _('Positive integer'), '" size="7" title="', _('The Asset ID should be positive integer'), '" type="text" value="',  $_POST['AssetID'], '" /> <a href="FixedAssetItems.php" target="_blank" class="btn btn-info">', _('New Fixed Asset'), '</a></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">', _('Select from list'), '</label>
		<select name="AssetSelection" class="form-control">';

$sql = "SELECT assetid,
			description
		FROM fixedassets
		WHERE cost=0
		ORDER BY assetid DESC";

$result = DB_query($sql);

while ($myrow = DB_fetch_array($result)) {
	if (isset($_POST['AssetSelection']) AND $myrow['AssetID']==$_POST['AssetSelection']) {
		echo '<option selected="selected" value="';
	} else {
		echo '<option value="';
	}
	echo $myrow['assetid'] . '">' . $myrow['assetid'] . ' - ' . $myrow['description']  . '</option>';
}

echo '</select></div>
	</div>';

if (!isset($_POST['Amount'])) {
	$_POST['Amount']=0;
}
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Amount') . '</label>
		<input type="text" class="form-control" pattern="(?!^-?0[,.]0*$).{1,11}" title="'._('The amount must be numeric and cannot be zero').'" name="Amount" size="12" maxlength="11" value="' .  locale_number_format($_POST['Amount'],$_SESSION['SuppTrans']->CurrDecimalPlaces) . '" /></div>
	</div>';
echo '</div>';

echo '
	<div class="row" align="center">
		<input type="submit" class="btn btn-success" name="AddAssetToInvoice" value="' . _('Enter Fixed Asset') . '" />
	</div><br />';

echo '
      </form>';
include('includes/footer.php');
?>
