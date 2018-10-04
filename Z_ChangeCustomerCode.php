<?php
/* This script is an utility to change a customer code. */

include ('includes/session.php');
$Title = _('UTILITY PAGE To Changes A Customer Code In All Tables');// Screen identificator.
$ViewTopic = 'SpecialUtilities'; // Filename's id in ManualContents.php's TOC.
$BookMark = 'Z_ChangeCustomerCode'; // Anchor's id in the manual's html document.
include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
	_('Change A Customer Code') . '</h1></a></div>';// Page title.

if (isset($_POST['ProcessCustomerChange'])){

/*First check the customer code exists */
	$result=DB_query("SELECT debtorno FROM debtorsmaster WHERE debtorno='" . $_POST['OldDebtorNo'] . "'");
	if (DB_num_rows($result)==0){
		echo prnMsg ('<br /><br />' . _('The customer code') . ': ' . $_POST['OldDebtorNo'] . ' ' . _('does not currently exist as a customer code in the system'),'error');
		include('includes/footer.php');
		exit;
	}


	if ($_POST['NewDebtorNo']==''){
		echo prnMsg(_('The new customer code to change the old code to must be entered as well'),'error');
		include('includes/footer.php');
		exit;
	}
/*Now check that the new code doesn't already exist */
	$result=DB_query("SELECT debtorno FROM debtorsmaster WHERE debtorno='" . $_POST['NewDebtorNo'] . "'");
	if (DB_num_rows($result)!=0){
		echo prnMsg(_('The replacement customer code') .': ' . $_POST['NewDebtorNo'] . ' ' . _('already exists as a customer code in the system') . ' - ' . _('a unique customer code must be entered for the new code'),'error');
		include('includes/footer.php');
		exit;
	}

	$result = DB_Txn_Begin();

	echo prnMsg(_('Inserting the new debtors master record'),'info');
	$sql = "INSERT INTO debtorsmaster (`debtorno`,
									`name`,
									`address1`,
									`address2`,
									`address3`,
									`address4`,
									`currcode`,
									`salestype`,
									`clientsince`,
									`holdreason`,
									`paymentterms`,
									`discount`,
									`discountcode`,
									`pymtdiscount`,
									`lastpaid`,
									`lastpaiddate`,
									`creditlimit`,
									`invaddrbranch`,
									`ediinvoices`,
									`ediorders`,
									`edireference`,
									`editransport`,
									`ediaddress`,
									`ediserveruser`,
									`ediserverpwd`,
									`typeid`)
					SELECT '" . $_POST['NewDebtorNo'] . "',
									`name`,
									`address1`,
									`address2`,
									`address3`,
									`address4`,
									`currcode`,
									`salestype`,
									`clientsince`,
									`holdreason`,
									`paymentterms`,
									`discount`,
									`discountcode`,
									`pymtdiscount`,
									`lastpaid`,
									`lastpaiddate`,
									`creditlimit`,
									`invaddrbranch`,
									`ediinvoices`,
									`ediorders`,
									`edireference`,
									`editransport`,
									`ediaddress`,
									`ediserveruser`,
									`ediserverpwd`,
									`typeid`
					FROM debtorsmaster
					WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";

	$DbgMsg =_('The SQL that failed was');
	$ErrMsg = _('The SQL to insert the new debtors master record failed') . ', ' . _('the SQL statement was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Inserting new customer branch records'),'info');
	$sql = "INSERT INTO custbranch ( `branchcode`,
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
							SELECT branchcode,
								'" . $_POST['NewDebtorNo'] . "',
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
								WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to insert new customer branch records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);
	echo prnMsg(_('Changing debtor transaction records'),'info');

	$sql = "UPDATE debtortrans SET debtorNo='" . $_POST['NewDebtorNo'] . "' WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to update debtor transaction records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Changing sales analysis records'),'info');

	$sql = "UPDATE salesanalysis SET cust='" . $_POST['NewDebtorNo'] . "' WHERE cust='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to update Sales Analysis records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg (_('Changing order delivery differences records'),'info');
	$sql = "UPDATE orderdeliverydifferenceslog SET debtorno='" . $_POST['NewDebtorNo'] . "' WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";
	$ErrMsg = _('The SQL to update order delivery differences records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);


	echo prnMsg(_('Changing pricing records'),'info');
	$sql = "UPDATE prices SET debtorno='" . $_POST['NewDebtorNo'] . "' WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to update the pricing records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Changing sales orders records'),'info');
	$sql = "UPDATE salesorders SET debtorno='" . $_POST['NewDebtorNo'] . "' WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to update the sales order header records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg( _('Changing stock movement records'),'info');
	$sql = "UPDATE stockmoves SET debtorno='" . $_POST['NewDebtorNo'] . "' WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";
	$ErrMsg = _('The SQL to update the sales order header records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Changing user default customer records'),'info');
	$sql = "UPDATE www_users SET customerid='" . $_POST['NewDebtorNo'] . "' WHERE customerid='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to update the user records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	echo prnMsg(_('Changing the customer code in contract header records'),'info');
	$sql = "UPDATE contracts SET debtorno='" . $_POST['NewDebtorNo'] . "' WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to update contract header records failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

	$result = DB_IgnoreForeignKeys();

	echo prnMsg(_('Deleting the old customer branch records from the CustBranch table'),'info');
	$sql = "DELETE FROM custbranch WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";
	$ErrMsg = _('The SQL to delete the old CustBranch records for the old debtor record failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);


	echo prnMsg(_('Deleting the customer code from the DebtorsMaster table'),'info');
	$sql = "DELETE FROM debtorsmaster WHERE debtorno='" . $_POST['OldDebtorNo'] . "'";

	$ErrMsg = _('The SQL to delete the old debtor record failed');
	$result = DB_query($sql,$ErrMsg,$DbgMsg,true);


	$result = DB_Txn_Commit();
	$result = DB_ReinstateForeignKeys();

}

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<br />
	<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Existing Debtor Code') . '</label>
		<input type="text" class="form-control" name="OldDebtorNo" size="20" maxlength="20" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"> ' . _('New Debtor Code') . '</label>
		<input type="text" class="form-control" name="NewDebtorNo" size="20" maxlength="20" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"><br />

	<input type="submit" class="btn btn-success" name="ProcessCustomerChange" value="' . _('Process') . '" />
	</div>
	</div>
	</div>
	</form>';

include('includes/footer.php');
?>
