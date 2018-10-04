<?php


/*The supplier transaction uses the SuppTrans class to hold the information about the invoice
the SuppTrans class contains an array of Contract objects - containing details of all contract charges
Contract charges are posted to the debit of Work In Progress (based on the account specified in the stock category record of the contract item
This is cleared against the cost of the contract as originally costed - when the contract is closed and any difference is taken to the price variance on the contract */

include('includes/DefineSuppTransClass.php');

/* Session started here for password checking and authorisation level check */
include('includes/session.php');

$Title = _('Contract Charges or Credits');

include('includes/header.php');

if (!isset($_SESSION['SuppTrans'])){
	echo   prnMsg(_('Contract charges or credits are entered against supplier invoices or credit notes respectively. To enter supplier transactions the supplier must first be selected from the supplier selection screen, then the link to enter a supplier invoice or credit note must be clicked on'),'info');
	echo '<div class="row" align="center">
		<a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-info">' . _('Select A Supplier') . '</a></div><br />';
	exit;
	/*It all stops here if there aint no supplier selected and invoice/credit initiated ie $_SESSION['SuppTrans'] started off*/
}

/*If the user hit the Add to transaction button then process this first before showing  all contracts on the invoice otherwise it wouldnt show the latest addition*/

if (isset($_POST['AddContractChgToInvoice'])){

	$InputError = False;
	if ($_POST['ContractRef'] == ''){
		$_POST['ContractRef'] = $_POST['ContractSelection'];
	} else{
		$result = DB_query("SELECT contractref FROM contracts
							WHERE status=2
							AND contractref='" . $_POST['ContractRef'] . "'");
		if (DB_num_rows($result)==0){
			echo prnMsg(_('The contract reference entered does not exist as a customer ordered contract. This contract cannot be charged to'),'error');
			$InputError =true;
		} //end if the contract ref entered is not a valid contract
	}//end if a contract ref was entered manually
	if (!is_numeric(filter_number_format($_POST['Amount']))){
		echo prnMsg(_('The amount entered is not numeric. This contract charge cannot be added to the invoice'),'error');
		$InputError = True;
	}

	if ($InputError == False){
		$_SESSION['SuppTrans']->Add_Contract_To_Trans($_POST['ContractRef'],
														filter_number_format($_POST['Amount']),
														$_POST['Narrative'],
														$_POST['AnticipatedCost']);
		unset($_POST['ContractRef']);
		unset($_POST['Amount']);
		unset($_POST['Narrative']);
	}
}

if (isset($_GET['Delete'])){
	$_SESSION['SuppTrans']->Remove_Contract_From_Trans($_GET['Delete']);
}

/*Show all the selected ContractRefs so far from the SESSION['SuppInv']->Contracts array */
if ($_SESSION['SuppTrans']->InvoiceOrCredit=='Invoice'){
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Contract charges on Invoice') . '<br /><small> ';
} else {
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Contract credits on Credit Note') . '<br /><small> ';
}

echo  $_SESSION['SuppTrans']->SuppReference . ' ' ._('From') . ' ' . $_SESSION['SuppTrans']->SupplierName;

echo '</small></h1></a></div>';

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<thead>
		<tr>
					<th class="ascending">' . _('Contract') . '</th>
					<th class="ascending">' . _('Amount') . '</th>
					<th class="ascending">' . _('Narrative') . '</th>
					<th class="ascending">' . _('Anticipated') . '</th>
		</tr>
	</thead>
	<tbody>';

$TotalContractsValue = 0;

foreach ($_SESSION['SuppTrans']->Contracts as $EnteredContract){

	if  ($EnteredContract->AnticipatedCost==true) {
		$AnticipatedCost = _('Yes');
	} else {
		$AnticipatedCost = _('No');
	}
	echo '<tr>
			<td>' . $EnteredContract->ContractRef . '</td>
			<td class="number">' . locale_number_format($EnteredContract->Amount,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td>' . $EnteredContract->Narrative . '</td>
			<td>' . $AnticipatedCost . '</td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $EnteredContract->Counter . '" class="btn btn-danger">' . _('Delete') . '</a></td>
		</tr>';

	$TotalContractsValue += $EnteredContract->Amount;

}

echo '</tbody>
		<tr>
		<td colspan="3">' . _('Total') . ':</td>
		<td class="2">' . locale_number_format($TotalContractsValue,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
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

/*Set up a form to allow input of new Contract charges */
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_POST['ContractRef'])) {
	$_POST['ContractRef']='';
}
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Contract Reference') . '</label>
	<input type="text" name="ContractRef" class="form-control" size="22" maxlength="20" value="' .  $_POST['ContractRef'] . '" /></div>
		</div>';
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Contract Selection') . ' ' . _('If you know the code enter it above') . '' . _('otherwise select the contract from the list') . '</label>
		<select name="ContractSelection" class="form-control">';

$sql = "SELECT contractref, name
		FROM contracts INNER JOIN debtorsmaster
		ON contracts.debtorno=debtorsmaster.debtorno
		WHERE status=2"; //only show customer ordered contracts not quotes or contracts that are finished with

$result = DB_query($sql);

while ($myrow = DB_fetch_array($result)) {
	if (isset($_POST['ContractSelection']) and $myrow['contractref']==$_POST['ContractSelection']) {
		echo '<option selected="selected" value="';
	} else {
		echo '<option value="';
	}
	echo $myrow['contractref'] . '">' . $myrow['contractref'] . ' - ' . $myrow['name'] ;
}

echo '</select></div></div>';

if (!isset($_POST['Amount'])) {
	$_POST['Amount']=0;
}
if (!isset($_POST['Narrative'])) {
	$_POST['Narrative']='';
}
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Amount') . '</label>
		<input type="text" class="form-control" pattern="(?!^[-]?0[.,]0*$).{1,11}" title="'._('Amount must be numeric').'" placeholder="'._('Non zero amount').'" name="Amount" size="12" maxlength="11" value="' .  locale_number_format($_POST['Amount'],$_SESSION['CompanyRecord']['decimalplaces']) . '" /></div>
	</div></div>';
echo '<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Narrative') . '</label>
		<input type="text" name="Narrative" size="42" class="form-control" maxlength="40" value="' .  $_POST['Narrative'] . '" /></div>
	</div>';
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Anticipated Cost') . '</label>
		';
if (isset($_POST['AnticipatedCost']) AND $_POST['AnticipatedCost']==1){
	echo '<div class="checkbox"><label><input type="checkbox" name="AnticipatedCost" checked /></label></div>';
} else {
	echo '<div class="checkbox"><label><input type="checkbox" name="AnticipatedCost" /></label></div>';
}

echo '</div>
	</div>
	';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><br /></label>
<input type="submit" name="AddContractChgToInvoice" value="' . _('Enter Contract Charge') . '" class="btn btn-success" /></div>';

echo '</div>
</div><br />
      </form>';
include('includes/footer.php');
?>
