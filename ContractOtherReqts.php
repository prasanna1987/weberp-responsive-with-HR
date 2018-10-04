<?php

include('includes/DefineContractClass.php');

include('includes/session.php');
$Title = _('Contract Other Requirements');

$identifier=$_GET['identifier'];

/* If a contract header doesn't exist, then go to
 * Contracts.php to create one
 */

if (!isset($_SESSION['Contract'.$identifier])){
	header('Location:' . $RootPath . '/Contracts.php');
	exit;
}

$ViewTopic= 'Contracts';
$BookMark = 'AddToContract';

include('includes/header.php');


if (isset($_POST['UpdateLines']) OR isset($_POST['BackToHeader'])) {
	if($_SESSION['Contract'.$identifier]->Status!=2){ //dont do anything if the customer has committed to the contract
		foreach ($_SESSION['Contract'.$identifier]->ContractReqts as $ContractComponentID => $ContractRequirementItem) {

			if (filter_number_format($_POST['Qty'.$ContractComponentID])==0){
				//this is the same as deleting the line - so delete it
				$_SESSION['Contract'.$identifier]->Remove_ContractRequirement($ContractComponentID);
			} else {
				$_SESSION['Contract'.$identifier]->ContractReqts[$ContractComponentID]->Quantity=filter_number_format($_POST['Qty'.$ContractComponentID]);
				$_SESSION['Contract'.$identifier]->ContractReqts[$ContractComponentID]->CostPerUnit=filter_number_format($_POST['CostPerUnit'.$ContractComponentID]);
				$_SESSION['Contract'.$identifier]->ContractReqts[$ContractComponentID]->Requirement=$_POST['Requirement'.$ContractComponentID];
			}
		} // end loop around the items on the contract requirements array
	} // end if the contract is not currently committed to by the customer
}// end if the user has hit the update lines or back to header buttons


if (isset($_POST['BackToHeader'])){
	echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/Contracts.php?identifier='.$identifier. '" />';
	echo '<br />';
	echo   prnMsg(_('You should automatically be forwarded to the Contract page. If this does not happen perhaps the browser does not support META Refresh') .	'<a href="' . $RootPath . '/Contracts.php?identifier='.$identifier . '" class="btn btn-info">' . _('click here') . '</a> ' . _('to continue'),'info');
	include('includes/footer.php');
	exit;
}


if(isset($_GET['Delete'])){
	if($_SESSION['Contract'.$identifier]->Status!=2){
		$_SESSION['Contract'.$identifier]->Remove_ContractRequirement($_GET['Delete']);
	} else {
		echo  prnMsg( _('The other contract requirements cannot be altered because the customer has already placed the order'),'warn');
	}
}
if (isset($_POST['EnterNewRequirement'])){
	$InputError = false;
	if (!is_numeric(filter_number_format($_POST['Quantity']))){
		echo prnMsg(_('The quantity of the new requirement is expected to be numeric'),'error');
		$InputError = true;
	}
	if (!is_numeric(filter_number_format($_POST['CostPerUnit']))){
		echo prnMsg(_('The cost per unit of the new requirement is expected to be numeric'),'error');
		$InputError = true;
	}
	if (!$InputError){
		$_SESSION['Contract'.$identifier]->Add_To_ContractRequirements ($_POST['RequirementDescription'],
																		filter_number_format($_POST['Quantity']),
																		filter_number_format($_POST['CostPerUnit']));
		unset($_POST['RequirementDescription']);
		unset($_POST['Quantity']);
		unset($_POST['CostPerUnit']);
	}
}

/* This is where the other requirement as entered/modified should be displayed reflecting any deletions or insertions*/

echo '<form name="ContractReqtsForm" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier. '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' . _('Contract Other Requirements') . ' - ' . $_SESSION['Contract'.$identifier]->CustomerName . '</h1></a></div>';

if (count($_SESSION['Contract'.$identifier]->ContractReqts)>0){

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
';

	if (isset($_SESSION['Contract'.$identifier]->ContractRef)) {
		echo  '<tr>
				<th colspan="5">' . _('Contract Reference') . ': '. $_SESSION['Contract'.$identifier]->ContractRef . '</th>
			</tr>';
	}

	echo '<thead><tr>
			<th>' . _('Description') . '</th>
			<th>' . _('Quantity') . '</th>
			<th>' . _('Unit Cost') .  '</th>
			<th>' . _('Sub-total') . '</th>
		</tr></thead>';

	$_SESSION['Contract'.$identifier]->total = 0;

	$TotalCost =0;
	foreach ($_SESSION['Contract'.$identifier]->ContractReqts as $ContractReqtID => $ContractComponent) {

		$LineTotal = $ContractComponent->Quantity * $ContractComponent->CostPerUnit;
		$DisplayLineTotal = locale_number_format($LineTotal,$_SESSION['CompanyRecord']['decimalplaces']);

		echo '<tr class="striped_row">
				<td class="has-error"><textarea name="Requirement' . $ContractReqtID . '" class="form-control" required="required" title="' . _('Enter a description of this requirement for the contract') . '" >' . $ContractComponent->Requirement . '</textarea></td>
			  <td class="has-error"><input type="text" class="form-control" required="required" title="' . _('Enter the quantity of this requirement for the contract') . '" name="Qty' . $ContractReqtID . '" size="11" value="' . locale_number_format($ContractComponent->Quantity,'Variable')  . '" /></td>
			  <td class="has-error"><input type="text" class="form-control" name="CostPerUnit' . $ContractReqtID . '" size="11" required="required" value="' . locale_number_format($ContractComponent->CostPerUnit,$_SESSION['CompanyRecord']['decimalplaces']) . '" /></td>
			  <td class="number">' . $DisplayLineTotal . '</td>
			  <td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier. '&amp;Delete=' . $ContractReqtID . '" onclick="return confirm(\'' . _('Are you sure you wish to delete this contract requirement?') . '\');" class="btn btn-danger">' . _('Delete') . '</a></td>
			  </tr>';
		$TotalCost += $LineTotal;
	}

	$DisplayTotal = locale_number_format($TotalCost,$_SESSION['CompanyRecord']['decimalplaces']);
	echo '<tr>
			<td colspan="4" class="number">' . _('Total Other Requirements Cost') . '</td>
			<td class="number"><b>' . $DisplayTotal . '</b></td>
		</tr>
		</table></div></div></div>
		<br />
		<div class="row">
		<div class="col-xs-4">
			<input type="submit" name="UpdateLines" class="btn btn-info" value="' . _('Update') . '" />
			</div>
		<div class="col-xs-4">	
			<input type="submit" name="BackToHeader" class="btn btn-success" value="' . _('Submit') . '" />
		</div></div>';

} /*Only display the contract other requirements lines if there are any !! */

echo '<br />';
/*Now show  form to add new requirements to the contract */
if (!isset($_POST['RequirementDescription'])) {
	$_POST['RequirementDescription']='';
	$_POST['Quantity']=0;
	$_POST['CostPerUnit']=0;
}
echo '<div class="block">
<div class="block-title"><h3>' . _('Enter New Requirements') . '</h3></div>
		
		<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Requirement Description') . '</label>
			<textarea name="RequirementDescription" class="form-control"  minlength="5" title="' . _('Enter a description of this requirement for the contract') . '" >' . $_POST['RequirementDescription'] . '</textarea></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Quantity Required') . '</label>
			<input type="text" class="form-control" name="Quantity" required="required" title="' . _('Enter the quantity of this requirement for the contract') . '" size="10"	maxlength="10" value="' . $_POST['Quantity'] . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Cost Per Unit') . '</label>
			<input type="text" class="form-control" name="CostPerUnit" size="10" required="required" title="' . _('Enter the cost per unit of this requirement') . '" maxlength="10" value="' . $_POST['CostPerUnit'] . '" /></div>
		</div>

		</div>

		
		<div class="row" align="center">
		
			<input type="submit" name="EnterNewRequirement" class="btn btn-success" value="' . _('Submit') . '" />
		</div>
		</div><br />
		

		</form>';

include('includes/footer.php');
?>
