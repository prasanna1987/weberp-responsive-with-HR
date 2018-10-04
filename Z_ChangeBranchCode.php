<?php
/* This script is an utility to change a customer branch code. */

include ('includes/session.php');
$Title = _('UTILITY PAGE To Changes A Customer Branch Code In All Tables');// Screen identificator.
$ViewTopic = 'SpecialUtilities'; // Filename's id in ManualContents.php's TOC.
$BookMark = 'Z_ChangeBranchCode'; // Anchor's id in the manual's html document.
include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
	_('Change A Customer Branch Code') . '</h1></a></div>';// Page title.

if (isset($_POST['ProcessCustomerChange'])){

/*First check the customer code exists */
	$result=DB_query("SELECT debtorno,
							branchcode
						FROM custbranch
						WHERE debtorno='" . $_POST['DebtorNo'] . "'
						AND branchcode='" . $_POST['OldBranchCode'] . "'");
	if (DB_num_rows($result)==0){
		echo prnMsg (_('The customer branch code') . ': ' . $_POST['DebtorNo'] . ' - ' . $_POST['OldBranchCode'] . ' ' . _('does not currently exist as a customer branch code in the system'),'error');
		include('includes/footer.php');
		exit;
	}

	if ($_POST['NewBranchCode']==''){
		echo prnMsg(_('The new customer branch code to change the old code to must be entered as well'),'error');
		include('includes/footer.php');
		exit;
	}
	if (ContainsIllegalCharacters($_POST['NewBranchCode']) OR mb_strstr($_POST['NewBranchCode'],' ')){
		echo prnMsg(_('The new customer branch code cannot contain') . ' - & . ' . _('or a space'),'error');
		include('includes/footer.php');
		exit;
	}


/*Now check that the new code doesn't already exist */
	$result=DB_query("SELECT debtorno FROM custbranch WHERE debtorno='" . $_POST['DebtorNo'] . "' AND branchcode ='" . $_POST['NewBranchCode'] . "'");
	if (DB_num_rows($result)!=0){
		echo prnMsg(_('The replacement customer branch code') . ': ' . $_POST['NewBranchCode'] . ' ' . _('already exists as a branch code for the same customer') . ' - ' . _('a unique branch code must be entered for the new code'),'error');
		include('includes/footer.php');
		exit;
	}


	$result = DB_Txn_Begin();

	echo prnMsg(_('Inserting the new customer branches master record'),'info');
	$sql = "INSERT INTO custbranch (`branchcode`,
					`debtorno`,
					`brname`,
					`braddress1`,
					`braddress2`,
					`braddress3`,
					`braddress4`,
					`braddress5`,
					`braddress6`,
					`estdeliverydays`,
					`area`,
					`salesman`,
					`fwddate`,
					`phoneno`,
					`faxno`,
					`contactname`,
					`email`,
					`defaultlocation`,
					`taxgroupid`,
					`disabletrans`,
					`brpostaddr1`,
					`brpostaddr2`,
					`brpostaddr3`,
					`brpostaddr4`,
					`brpostaddr5`,
					`brpostaddr6`,
					`defaultshipvia`,
					`custbranchcode`)
			SELECT '" . $_POST['NewBranchCode'] . "',
					`debtorno`,
					`brname`,
					`braddress1`,
					`braddress2`,
					`braddress3`,
					`braddress4`,
					`braddress5`,
					`braddress6`,
					`estdeliverydays`,
					`area`,
					`salesman`,
					`fwddate`,
					`phoneno`,
					`faxno`,
					`contactname`,
					`email`,
					`defaultlocation`,
					`taxgroupid`,
					`disabletrans`,
					`brpostaddr1`,
					`brpostaddr2`,
					`brpostaddr3`,
					`brpostaddr4`,
					`brpostaddr5`,
					`brpostaddr6`,
					`defaultshipvia`,
					`custbranchcode`
			FROM custbranch
			WHERE debtorno='" . $_POST['DebtorNo'] . "'
			AND branchcode='" . $_POST['OldBranchCode'] . "'";
	$DbgMsg = _('The SQL that failed was');
	$ErrMsg = _('The SQL to insert the new customer branch master record failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg (_('Changing customer transaction records'),'info');
	$sql = "UPDATE debtortrans SET
					branchcode='" . $_POST['NewBranchCode'] . "'
					WHERE debtorno='" . $_POST['DebtorNo'] . "'
					AND branchcode='" . $_POST['OldBranchCode'] . "'";

	$ErrMsg = _('The SQL to update debtor transaction records failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Changing sales analysis records'),'info');
	$sql = "UPDATE salesanalysis
					SET custbranch='" . $_POST['NewBranchCode'] . "'
					WHERE cust='" . $_POST['DebtorNo'] . "'
					AND custbranch='" . $_POST['OldBranchCode'] . "'";

	$ErrMsg = _('The SQL to update Sales Analysis records failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);


	echo prnMsg(_('Changing order delivery differences records'),'info');
	$sql = "UPDATE orderdeliverydifferenceslog
					SET branch='" . $_POST['NewBranchCode'] . "'
					WHERE debtorno='" . $_POST['DebtorNo'] . "'
					AND branch='" . $_POST['OldBranchCode'] . "'";

	$ErrMsg = _('The SQL to update order delivery differences records failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);


	echo prnMsg (_('Changing pricing records'),'info');
	$sql = "UPDATE prices
				SET branchcode='" . $_POST['NewBranchCode'] . "'
				WHERE debtorno='" . $_POST['DebtorNo'] . "'
				AND branchcode='" . $_POST['OldBranchCode'] . "'";
	$ErrMsg = _('The SQL to update the pricing records failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);


	echo prnMsg(_('Changing sales orders records'),'info');
	$sql = "UPDATE salesorders
					SET branchcode='" . $_POST['NewBranchCode'] . "'
					WHERE debtorno='" . $_POST['DebtorNo'] . "'
					AND branchcode='" . $_POST['OldBranchCode'] . "'";
	$ErrMsg = _('The SQL to update the sales order header records failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);


	echo prnMsg(_('Changing stock movement records'),'info');
	$sql = "UPDATE stockmoves
					SET branchcode='" . $_POST['NewBranchCode'] . "'
					WHERE debtorno='" . $_POST['DebtorNo'] . "'
					AND branchcode='" . $_POST['OldBranchCode'] . "'";
	$ErrMsg = _('The SQL to update the stock movement records failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Changing user default customer records'),'info');
	$sql = "UPDATE www_users
					SET branchcode='" . $_POST['NewBranchCode'] . "'
					WHERE customerid='" . $_POST['DebtorNo'] . "'
					AND branchcode='" . $_POST['OldBranchCode'] . "'";;

	$ErrMsg = _('The SQL to update the user records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Changing the customer branch code in contract header records'),'info');
	$sql = "UPDATE contracts
					SET branchcode='" . $_POST['NewBranchCode'] . "'
					WHERE debtorno='" . $_POST['DebtorNo'] . "'
					AND branchcode='" . $_POST['OldBranchCode'] . "'";
	$ErrMsg = _('The SQL to update contract header records failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	$result = DB_Txn_Commit();

	$result = DB_IgnoreForeignKeys();
	echo prnMsg(_('Deleting the old customer branch record'),'info');
	$sql = "DELETE FROM custbranch
					WHERE debtorno='" . $_POST['DebtorNo'] . "'
					AND branchcode='" . $_POST['OldBranchCode'] . "'";

	$ErrMsg = _('The SQL to delete the old customer branch record failed because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true,true);
	$result = DB_ReinstateForeignKeys();

}

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<br />
	<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Code') . '</label>
			<input type="text" class="form-control" name="DebtorNo" size="20" maxlength="20" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Existing Branch Code') . '</label>
			<input type="text" class="form-control" name="OldBranchCode" size="20" maxlength="20" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('New Branch Code') . '</label>
			<input type="text" class="form-control" name="NewBranchCode" size="20" maxlength="20" /></div>
		</div>
	</div>';

echo '<div class="row"><div class="col-xs-4">
<input type="submit" name="ProcessCustomerChange" class="btn btn-success" value="' . _('Process') . '" />';

echo '</div></div><br />

      </form>';

include('includes/footer.php');
?>
