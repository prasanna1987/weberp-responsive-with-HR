<?php
/* This script maintains table bankaccountusers (Authorized users to work with a bank account in nERP) */

include('includes/session.php');
$Title = _('Bank Account Users');
$ViewTopic = 'GeneralLedger';
$BookMark = 'BankAccountUsers';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
	_('Maintenance Of Bank Account Authorised Users') . '</h1></a></div>';// Page title.

if (isset($_POST['SelectedUser'])){
	$SelectedUser = mb_strtoupper($_POST['SelectedUser']);
} elseif (isset($_GET['SelectedUser'])){
	$SelectedUser = mb_strtoupper($_GET['SelectedUser']);
} else {
	$SelectedUser='';
}

if (isset($_POST['SelectedBankAccount'])){
	$SelectedBankAccount = mb_strtoupper($_POST['SelectedBankAccount']);
} elseif (isset($_GET['SelectedBankAccount'])){
	$SelectedBankAccount = mb_strtoupper($_GET['SelectedBankAccount']);
}

if (isset($_POST['Cancel'])) {
	unset($SelectedBankAccount);
	unset($SelectedUser);
}

if (isset($_POST['Process'])) {
	if ($_POST['SelectedBankAccount'] == '') {
		echo prnMsg(_('You have not selected any bank account'),'error');
		echo '<br />';
		unset($SelectedBankAccount);
		unset($_POST['SelectedBankAccount']);
	}
}

if (isset($_POST['submit'])) {

	$InputError=0;

	if ($_POST['SelectedUser']=='') {
		$InputError=1;
		echo prnMsg(_('You have not selected an user to be authorised to use this bank account'),'error');
		echo '<br />';
		unset($SelectedBankAccount);
	}

	if ( $InputError !=1 ) {

		// First check the user is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM bankaccountusers
			     WHERE accountcode= '" .  $_POST['SelectedBankAccount'] . "'
				 AND userid = '" .  $_POST['SelectedUser'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] >0) {
			$InputError = 1;
			echo prnMsg( _('The user') . ' ' . $_POST['SelectedUser'] . ' ' ._('already authorised to use this bank account'),'error');
		} else {
			// Add new record on submit
			$sql = "INSERT INTO bankaccountusers (accountcode,
												userid)
										VALUES ('" . $_POST['SelectedBankAccount'] . "',
												'" . $_POST['SelectedUser'] . "')";

			$msg = _('User') . ': ' . $_POST['SelectedUser'].' '._('has been authorised to use') .' '. $_POST['SelectedBankAccount'] .  ' ' . _('bank account');
			$result = DB_query($sql);
			echo prnMsg($msg,'success');
			unset($_POST['SelectedUser']);
		}
	}
} elseif ( isset($_GET['delete']) ) {
	$sql="DELETE FROM bankaccountusers
		WHERE accountcode='".$SelectedBankAccount."'
		AND userid='".$SelectedUser."'";

	$ErrMsg = _('The bank account user record could not be deleted because');
	$result = DB_query($sql,$ErrMsg);
	echo prnMsg(_('User').' '. $SelectedUser .' '. _('has been un-authorised to use').' '. $SelectedBankAccount .' '. _('bank account') ,'success');
	unset($_GET['delete']);
}

if (!isset($SelectedBankAccount)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedUser will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true. These will call the same page again and allow update/input or deletion of the records*/
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
    echo '
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Bank Account') . '</label>
				<select name="SelectedBankAccount" class="form-control">';

	$SQL = "SELECT accountcode,
					bankaccountname
			FROM bankaccounts";

	$result = DB_query($SQL);
	echo '<option value="">' . _('Not Yet Selected') . '</option>';
	while ($myrow = DB_fetch_array($result)) {
		if (isset($SelectedBankAccount) and $myrow['accountcode']==$SelectedBankAccount) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . $myrow['bankaccountname'] . '</option>';

	} //end while loop

	echo '</select></div></div>';

   
    DB_free_result($result);

	echo '<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" name="Process" class="btn btn-success" value="' . _('Submit') . '" /></div></div>
	';

	echo '</div>
          </form>';

}

//end of ifs and buts!
if (isset($_POST['process'])OR isset($SelectedBankAccount)) {
	$SQLName = "SELECT bankaccountname
			FROM bankaccounts
			WHERE accountcode='" .$SelectedBankAccount."'";
	$result = DB_query($SQLName);
	$myrow = DB_fetch_array($result);
	$SelectedBankName = $myrow['bankaccountname'];

	
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<input type="hidden" name="SelectedBankAccount" value="' . $SelectedBankAccount . '" />';

	$sql = "SELECT bankaccountusers.userid,
					www_users.realname
			FROM bankaccountusers INNER JOIN www_users
			ON bankaccountusers.userid=www_users.userid
			WHERE bankaccountusers.accountcode='" . $SelectedBankAccount . "'
			ORDER BY bankaccountusers.userid ASC";

	$result = DB_query($sql);

	echo '<br />
			<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Authorised users for:') . ' ' .$SelectedBankName. '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

	echo '<thead><tr>
			<th>' . _('User Code') . '</th>
			<th>' . _('User Name') . '</th>
			<th>' . _('Action') . '</th>
		</tr></thead>';

while ($myrow = DB_fetch_array($result)) {
	printf('<tr class="striped_row">
			<td>%s</td>
			<td>%s</td>
			<td><a href="%s?SelectedUser=%s&amp;delete=yes&amp;SelectedBankAccount=' . $SelectedBankAccount . '" onclick="return confirm(\'' . _('Are you sure you wish to un-authorise this user?') . '\');" class="btn btn-info">' . _('Un-authorise') . '</a></td>
			</tr>',
			$myrow['userid'],
			$myrow['realname'],
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'),
			$myrow['userid'],
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'),
			$myrow['userid']);
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div></div>';

	if (! isset($_GET['delete'])) {


		echo '<br />'; //Main table

		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select User') . '</label>
				<select name="SelectedUser" class="form-control">';

		$SQL = "SELECT userid,
						realname
				FROM www_users";

		$result = DB_query($SQL);
		if (!isset($_POST['SelectedUser'])){
			echo '<option selected="selected" value="">' . _('Not Yet Selected') . '</option>';
		}
		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['SelectedUser']) AND $myrow['userid']==$_POST['SelectedUser']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['userid'] . '">' . $myrow['userid'] . ' - ' . $myrow['realname'] . '</option>';

		} //end while loop

		echo '</select></div></div>';

	   
        DB_free_result($result);

		echo '<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" name="submit" class="btn btn-success" value="' . _('Authorise') . '" /></div></div>
<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" name="Cancel" class="btn btn-default" value="' . _('Back') . '" /></div></div>';

		echo '</div>
              </form>';

	} // end if user wish to delete
}

include('includes/footer.php');
?>
