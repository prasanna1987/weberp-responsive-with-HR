<?php
/* This script defines the general ledger code for bank accounts and specifies that bank transactions be created for these accounts for the purposes of reconciliation. */

include('includes/session.php');
$Title = _('Bank Accounts');// Screen identificator.
$ViewTopic= 'GeneralLedger';// Filename's id in ManualContents.php's TOC.
$BookMark = 'BankAccounts';// Anchor's id in the manual's html document.
include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
	_('Bank Accounts Maintenance') . '</h1></a></div>';// Page title.

echo '<div class="text-info">' . _('Set Show on Invoices to Account Default  or System Default to print Account details on Invoices (only one account should be set to System Default)') . '.</div><br />';

if (isset($_GET['SelectedBankAccount'])) {
	$SelectedBankAccount=$_GET['SelectedBankAccount'];
} elseif (isset($_POST['SelectedBankAccount'])) {
	$SelectedBankAccount=$_POST['SelectedBankAccount'];
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;

	$sql="SELECT count(accountcode)
			FROM bankaccounts WHERE accountcode='".$_POST['AccountCode']."'";
	$result=DB_query($sql);
	$myrow=DB_fetch_row($result);

	if ($myrow[0]!=0 and !isset($SelectedBankAccount)) {
		$InputError = 1;
		echo prnMsg( _('IFSC code already exists in the system'),'error');
		$Errors[$i] = 'AccountCode';
		$i++;
	}
	if (mb_strlen($_POST['BankAccountName']) >50) {
		$InputError = 1;
		echo prnMsg(_('The bank account name must be fifty characters or less'),'error');
		$Errors[$i] = 'AccountName';
		$i++;
	}
	if ( trim($_POST['BankAccountName']) == '' ) {
		$InputError = 1;
		echo prnMsg(_('The bank account name can not be empty.'),'error');
		$Errors[$i] = 'AccountName';
		$i++;
	}
	if ( trim($_POST['BankAccountNumber']) == '' ) {
		$InputError = 1;
		echo prnMsg(_('The bank account number can not be empty.'),'error');
		$Errors[$i] = 'AccountNumber';
		$i++;
	}
	if (mb_strlen($_POST['BankAccountNumber']) >50) {
		$InputError = 1;
		echo prnMsg(_('The bank account number must be fifty characters or less'),'error');
		$Errors[$i] = 'AccountNumber';
		$i++;
	}
	if (mb_strlen($_POST['BankAddress']) >250) {
		$InputError = 1;
		echo prnMsg(_('The bank address must be two fifty characters or less'),'error');
		$Errors[$i] = 'BankAddress';
		$i++;
	}

	if (isset($SelectedBankAccount) AND $InputError !=1) {

		/*Check if there are already transactions against this account - cant allow change currency if there are*/

		$sql = "SELECT banktransid FROM banktrans WHERE bankact='" . $SelectedBankAccount . "'";
		$BankTransResult = DB_query($sql);
		if (DB_num_rows($BankTransResult)>0) {
			$sql = "UPDATE bankaccounts SET bankaccountname='" . $_POST['BankAccountName'] . "',
											bankaccountcode='" . $_POST['BankAccountCode'] . "',
											bankaccountnumber='" . $_POST['BankAccountNumber'] . "',
											bankaddress='" . $_POST['BankAddress'] . "',
											invoice ='" . $_POST['DefAccount'] . "',
											importformat='" . $_POST['ImportFormat'] . "'
										WHERE accountcode = '" . $SelectedBankAccount . "'";
			echo prnMsg(_('Note that it is not possible to change the currency of the account once there are transactions against it'),'warn');
	echo '<br />';
		} else {
			$sql = "UPDATE bankaccounts SET bankaccountname='" . $_POST['BankAccountName'] . "',
											bankaccountcode='" . $_POST['BankAccountCode'] . "',
											bankaccountnumber='" . $_POST['BankAccountNumber'] . "',
											bankaddress='" . $_POST['BankAddress'] . "',
											currcode ='" . $_POST['CurrCode'] . "',
											invoice ='" . $_POST['DefAccount'] . "',
											importformat='" . $_POST['ImportFormat'] . "'
										WHERE accountcode = '" . $SelectedBankAccount . "'";
		}

		$msg = _('The bank account details have been updated');
	} elseif ($InputError !=1) {

	/*Selectedbank account is null cos no item selected on first time round so must be adding a    record must be submitting new entries in the new bank account form */

		$sql = "INSERT INTO bankaccounts (accountcode,
										bankaccountname,
										bankaccountcode,
										bankaccountnumber,
										bankaddress,
										currcode,
										invoice,
										importformat
									) VALUES ('" . $_POST['AccountCode'] . "',
										'" . $_POST['BankAccountName'] . "',
										'" . $_POST['BankAccountCode'] . "',
										'" . $_POST['BankAccountNumber'] . "',
										'" . $_POST['BankAddress'] . "',
										'" . $_POST['CurrCode'] . "',
										'" . $_POST['DefAccount'] . "',
										'" . $_POST['ImportFormat'] . "' )";
		$msg = _('The new bank account has been entered');
	}

	//run the SQL from either of the above possibilites
	if( $InputError !=1 ) {
		$ErrMsg = _('The bank account could not be inserted or modified because');
		$DbgMsg = _('The SQL used to insert/modify the bank account details was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);

		echo prnMsg($msg,'success');
		echo '<br />';
		unset($_POST['AccountCode']);
		unset($_POST['BankAccountName']);
		unset($_POST['BankAccountCode']);
		unset($_POST['BankAccountNumber']);
		unset($_POST['BankAddress']);
		unset($_POST['CurrCode']);
		unset($_POST['DefAccount']);
		unset($SelectedBankAccount);
	}


} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'BankTrans'

	$sql= "SELECT COUNT(bankact) AS accounts FROM banktrans WHERE banktrans.bankact='" . $SelectedBankAccount . "'";
	$result = DB_query($sql);
	$myrow = DB_fetch_array($result);
	if ($myrow['accounts']>0) {
		$CancelDelete = 1;
		echo prnMsg(_('Cannot delete this bank account because transactions have been created using this account'),'warn');
		echo '<br /> ' . _('There are') . ' ' . $myrow['accounts'] . ' ' . _('transactions with this IFSC code');

	}
	if (!$CancelDelete) {
		$sql="DELETE FROM bankaccounts WHERE accountcode='" . $SelectedBankAccount . "'";
		$result = DB_query($sql);
		echo prnMsg(_('Bank account deleted'),'success');
	} //end if Delete bank account

	unset($_GET['delete']);
	unset($SelectedBankAccount);
}

/* Always show the list of accounts */
if (!isset($SelectedBankAccount)) {
	$sql = "SELECT bankaccounts.accountcode,
					bankaccounts.bankaccountcode,
					chartmaster.accountname,
					bankaccountname,
					bankaccountnumber,
					bankaddress,
					currcode,
					invoice,
					importformat
			FROM bankaccounts INNER JOIN chartmaster
			ON bankaccounts.accountcode = chartmaster.accountcode";

	$ErrMsg = _('The bank accounts set up could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the bank account details was') . '<br />' . $sql;
	$result = DB_query($sql,$ErrMsg,$DbgMsg);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead><tr>
				<th>' . _('GL Account') . '</th>
				<th>' . _('Account Name') . '</th>
				<th>' . _('IFSC Code') . '</th>
				<th>' . _('Account Number') . '</th>
				<th>' . _('Address') . '</th>
				<th>' . _('Currency') . '</th>
				<th>' . _('Show on Invoices') . '</th>
				<th colspan="2">' . _('Actions') . '</th>
			</tr></thead>';

	while ($myrow = DB_fetch_array($result)) {
		if ($myrow['invoice']==0) {
			$DefaultBankAccount=_('No');
		} elseif ($myrow['invoice']==1) {
			$DefaultBankAccount=_('Fall Back Default');
		} elseif ($myrow['invoice']==2) {
			$DefaultBankAccount=_('Currency Default');
		}

		switch ($myrow['importformat']) {
			case 'MT940-ING':
				$ImportFormat = 'ING MT940';
				break;
			case 'MT940-SCB':
				$ImportFormat = 'SCB MT940';
				break;
			default:
				$ImportFormat ='';
		}

		printf('<tr class="striped_row">
				<td>%s<br />%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td><a href="%s?SelectedBankAccount=%s" class="btn btn-info">' . _('Edit') . '</a></td>
				<td><a href="%s?SelectedBankAccount=%s&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this bank account?') . '\');">' . _('Delete') . '</a></td>
			</tr>',
			$myrow['accountcode'],
			$myrow['accountname'],
			$myrow['bankaccountname'],
			$myrow['bankaccountcode'],
			$myrow['bankaccountnumber'],
			$myrow['bankaddress'],
			$ImportFormat,
			$myrow['currcode'],
			$DefaultBankAccount,
			htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'),
			$myrow['accountcode'],
			htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'),
			$myrow['accountcode']);

	}
	//END WHILE LIST LOOP


	echo '</table></div></div></div><br />';
}

if (isset($SelectedBankAccount)) {
	echo '<br />';
	echo '<div class="row"><div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-info">' . _('Show All Bank Accounts Defined') . '</a></p></div></div>';
	echo '<br />';
}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($SelectedBankAccount) AND !isset($_GET['delete'])) {
	//editing an existing bank account  - not deleting

	$sql = "SELECT accountcode,
					bankaccountname,
					bankaccountcode,
					bankaccountnumber,
					bankaddress,
					currcode,
					invoice
			FROM bankaccounts
			WHERE bankaccounts.accountcode='" . $SelectedBankAccount . "'";

	$result = DB_query($sql);
	$myrow = DB_fetch_array($result);

	$_POST['AccountCode'] = $myrow['accountcode'];
	$_POST['BankAccountName']  = $myrow['bankaccountname'];
	$_POST['BankAccountCode']  = $myrow['bankaccountcode'];
	$_POST['BankAccountNumber'] = $myrow['bankaccountnumber'];
	$_POST['BankAddress'] = $myrow['bankaddress'];
	$_POST['CurrCode'] = $myrow['currcode'];
	$_POST['DefAccount'] = $myrow['invoice'];

	echo '<input type="hidden" name="SelectedBankAccount" value="' . $SelectedBankAccount . '" />';
	echo '<input type="hidden" name="AccountCode" value="' . $_POST['AccountCode'] . '" />';
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Bank Account GL Code') . '</label>
				' . $_POST['AccountCode'] . '</div>
			</div>';
} else { //end of if $Selectedbank account only do the else when a new record is being entered
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Bank Account GL Code') . '</label>
				<select tabindex="1" ' . (in_array('AccountCode',$Errors) ?  'class="selecterror"' : '' ) .' name="AccountCode" autofocus="autofocus" class="form-control">';

	$sql = "SELECT accountcode,
					accountname
			FROM chartmaster LEFT JOIN accountgroups
			ON chartmaster.group_ = accountgroups.groupname
			WHERE accountgroups.pandl = 0
			ORDER BY accountcode";

	$result = DB_query($sql);
	while ($myrow = DB_fetch_array($result)) {
		if (isset($_POST['AccountCode']) and $myrow['accountcode']==$_POST['AccountCode']) {
			echo '<option selected="selected" value="'.$myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
		} else {
			echo '<option value="'.$myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
		}

	} //end while loop

	echo '</select></div></div>';
}

// Check if details exist, if not set some defaults
if (!isset($_POST['BankAccountName'])) {
	$_POST['BankAccountName']='';
}
if (!isset($_POST['BankAccountNumber'])) {
	$_POST['BankAccountNumber']='';
}
if (!isset($_POST['BankAccountCode'])) {
        $_POST['BankAccountCode']='';
}
if (!isset($_POST['BankAddress'])) {
	$_POST['BankAddress']='';
}
if (!isset($_POST['ImportFormat'])) {
	$_POST['ImportFormat']='';
}
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Bank Account Name') . ' </label>
		<input tabindex="2" ' . (in_array('AccountName',$Errors) ?  'class="inputerror"' : '' ) .' type="text" required="required" name="BankAccountName" value="' . $_POST['BankAccountName'] . '" size="40" maxlength="50" class="form-control" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('IFSC Code') . ' </label>
		<input tabindex="3" ' . (in_array('AccountCode',$Errors) ?  'class="inputerror"' : '' ) .' class="form-control" type="text" name="BankAccountCode" value="' . $_POST['BankAccountCode'] . '" size="40" maxlength="50" /></div>
	</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Bank Account Number') . ' </label>
		<input tabindex="3" ' . (in_array('AccountNumber',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="BankAccountNumber" value="' . $_POST['BankAccountNumber'] . '" size="40" maxlength="50" class="form-control" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Bank Address') . ' </label>
		<input tabindex="4" ' . (in_array('BankAddress',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="form-control" name="BankAddress" value="' . $_POST['BankAddress'] . '" size="40" maxlength="250" /></div>
	</div>';
	
	echo '<input type="hidden" name="ImportFormat" value="" />';
	//echo '<div class="col-xs-4">
//<div class="form-group"> <label class="col-md-8 control-label">' . _('Import File Format') . ' </label>
//		<select tabindex="5" name="ImportFormat" class="form-control">
//			<option ' . ($_POST['ImportFormat']=='' ? 'selected="selected"' : '') . ' value="">' . _('N/A') . '</option>
//			<option ' . ($_POST['ImportFormat']=='MT940-SCB' ? 'selected="selected"' : '') . ' value="MT940-SCB">' . _('MT940 - Siam Comercial Bank Thailand') . '</option>
//			<option ' . ($_POST['ImportFormat']=='MT940-ING' ? 'selected="selected"' : '') . ' value="MT940-ING">' . _('MT940 - ING Bank Netherlands') . '</option>
//			<option ' . ($_POST['ImportFormat']=='GIFTS' ? 'selected="selected"' : '') . ' value="GIFTS">' . _('GIFTS - Bank of New Zealand') . '</option>
//			</select>
//		</div>
//	</div>';
	
	echo '
	
	
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Currency Of Account') . ' </label>
		<select tabindex="6" name="CurrCode" class="form-control">';

if (!isset($_POST['CurrCode']) or $_POST['CurrCode']==''){
	$_POST['CurrCode'] = $_SESSION['CompanyRecord']['currencydefault'];
}
$result = DB_query("SELECT currabrev,
							currency
					FROM currencies");

while ($myrow = DB_fetch_array($result)) {
	if ($myrow['currabrev']==$_POST['CurrCode']) {
		echo '<option selected="selected" value="'.$myrow['currabrev'] . '">' . $myrow['currabrev'] . '</option>';
	} else {
		echo '<option value="'.$myrow['currabrev'] . '">' . $myrow['currabrev'] . '</option>';
	}
} //end while loop

echo '</select></div>';
echo '</div></div>';

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Show on Invoices') . ' </label>
		<select tabindex="8" name="DefAccount" class="form-control">';

if (!isset($_POST['DefAccount']) OR $_POST['DefAccount']==''){
	$_POST['DefAccount'] = $_SESSION['CompanyRecord']['currencydefault'];
}

if (isset($SelectedBankAccount)) {
	$result = DB_query("SELECT invoice FROM bankaccounts where accountcode = '" . $SelectedBankAccount . "'" );
	while ($myrow = DB_fetch_array($result)) {
		if ($myrow['invoice']== 1) {
			echo '<option selected="selected" value="1">' . _('System Default') . '</option>
					<option value="2">' . _('Account Default') . '</option>
					<option value="0">' . _('No') . '</option>';
		} elseif ($myrow['invoice']== 2) {
			echo '<option value="0">' . _('No') . '</option>
					<option selected="selected" value="2">' . _('Account Default') . '</option>
					<option value="1">' . _('System Default') . '</option>';
		} else {
			echo '<option selected="selected" value="0">' . _('No') . '</option>
					<option  value="2">' . _('Account Default') . '</option>
					<option value="1">' . _('System Default') . '</option>';
		}
	}//end while loop
} else {
	echo '<option value="1">' . _('System Default') . '</option>
			<option  value="2">' . _('Account Default') . '</option>
			<option value="0">' . _('No') . '</option>';
}

echo '</select></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <br /><input tabindex="9" type="submit" class="btn btn-info" name="submit" value="'. _('Enter Information') .'" /></div>
		</div>
		</div>
		</form>';
include('includes/footer.php');
?>
