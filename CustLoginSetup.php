<?php


include('includes/session.php');
$Title = _('Customer Login Configuration');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include ('includes/LanguagesArray.php');


if (!isset($_SESSION['CustomerID'])){
	
	echo   prnMsg(_('A customer must first be selected before logins can be defined for it') . '<br /><br /><a href="' . $RootPath . '/SelectCustomer.php" class="btn btn-info">' . _('Select A Customer') . '</a>','info');
	include('includes/footer.php');
	exit;
}


echo '<br /><p align="right"><a href="' . $RootPath . '/SelectCustomer.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> To Customers') . '</a></p><br />';

$sql="SELECT name
		FROM debtorsmaster
		WHERE debtorno='".$_SESSION['CustomerID']."'";

$result=DB_query($sql);
$myrow=DB_fetch_array($result);
$CustomerName=$myrow['name'];

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' . ' ' . _('Customer') . ' : ' . $_SESSION['CustomerID'] . ' - ' . $CustomerName. _(' has been selected') .
	'</h1></a></div>
	';


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if (mb_strlen($_POST['UserID'])<4){
		$InputError = 1;
		echo prnMsg(_('The user ID entered must be at least 4 characters long'),'error');
	} elseif (ContainsIllegalCharacters($_POST['UserID']) OR mb_strstr($_POST['UserID'],' ')) {
		$InputError = 1;
		echo prnMsg(_('User names cannot contain any of the following characters') . " - ' &amp; + \" \\ " . _('or a space'),'error');
	} elseif (mb_strlen($_POST['Password'])<5){
		if (!$SelectedUser){
			$InputError = 1;
			echo prnMsg(_('The password entered must be at least 5 characters long'),'error');
		}
	} elseif (mb_strstr($_POST['Password'],$_POST['UserID'])!= false){
		$InputError = 1;
		echo  prnMsg(_('The password cannot contain the user id'),'error');
	} elseif ((mb_strlen($_POST['Cust'])>0) AND (mb_strlen($_POST['BranchCode'])==0)) {
		$InputError = 1;
		echo prnMsg(_('If you enter a Customer Code you must also enter a Branch Code valid for this Customer'),'error');
	}

	if ((mb_strlen($_POST['BranchCode'])>0) AND ($InputError !=1)) {
		// check that the entered branch is valid for the customer code
		$sql = "SELECT defaultlocation
				FROM custbranch
				WHERE debtorno='" . $_SESSION['CustomerID'] . "'
				AND branchcode='" . $_POST['BranchCode'] . "'";

		$ErrMsg = _('The check on validity of the customer code and branch failed because');
		$DbgMsg = _('The SQL that was used to check the customer code and branch was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);

		if (DB_num_rows($result)==0){
			echo prnMsg(_('The entered Branch Code is not valid for the entered Customer Code'),'error');
			$InputError = 1;
		} else {
			$myrow = DB_fetch_row($result);
			$InventoryLocation = $myrow[0];
	}

	if ($InputError !=1) {

		$sql = "INSERT INTO www_users (userid,
										realname,
										customerid,
										branchcode,
										password,
										phone,
										email,
										pagesize,
										fullaccess,
										defaultlocation,
										modulesallowed,
										displayrecordsmax,
										theme,
										language)
									VALUES ('" . $_POST['UserID'] . "',
											'" . $_POST['RealName'] ."',
											'" . $_SESSION['CustomerID'] ."',
											'" . $_POST['BranchCode'] ."',
											'" . CryptPass($_POST['Password']) ."',
											'" . $_POST['Phone'] . "',
											'" . $_POST['Email'] ."',
											'" . $_POST['PageSize'] ."',
											'7',
											'" . $InventoryLocation ."',
											'1,1,0,0,0,0,0,0',
											'" . $_SESSION['DefaultDisplayRecordsMax'] . "',
											'" . $_POST['Theme'] . "',
											'". $_POST['UserLanguage'] ."')";

			$ErrMsg = _('The user could not be added because');
			$DbgMsg = _('The SQL that was used to insert the new user and failed was');
			$result = DB_query($sql,$ErrMsg,$DbgMsg);
			echo prnMsg( _('A new customer login has been created'), 'success' );
			include('includes/footer.php');
			exit;
		}
	}

}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('User Login') . '</label>
			<input type="text" name="UserID" class="form-control" required="required" ' . (isset($_GET['SelectedUser']) ? '':'autofocus="autofocus"') . 'title="' . _('Enter a userid for this customer login') . '" size="22" maxlength="20" /></div>
		</div>';

if (!isset($_POST['Password'])) {
	$_POST['Password']='';
}
if (!isset($_POST['RealName'])) {
	$_POST['RealName']='';
}
if (!isset($_POST['Phone'])) {
	$_POST['Phone']='';
}
if (!isset($_POST['Email'])) {
	$_POST['Email']='';
}

echo '
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Password') . '</label>
		<input type="password" name="Password" class="form-control" required="required" ' . (isset($_GET['SelectedUser']) ? 'autofocus="autofocus"':'') . ' title="' . _('Enter a password for this customer login') . '" size="22" maxlength="20" value="' . $_POST['Password'] . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Full Name') . '</label>
			<input type="text" class="form-control" name="RealName" value="' . $_POST['RealName'] . '" required="required" title="' . _('Enter the user\'s real name') . '" size="36" maxlength="35" /></div>
		</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Telephone No') . '</label>
			<input type="tel" class="form-control" name="Phone" value="' . $_POST['Phone'] . '" size="32" maxlength="30" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Email Address') .'</label>
			<input type="email" class="form-control" name="Email" value="' . $_POST['Email'] .'" required="required" title="' . _('Enter the user\'s email address') . '" size="32" maxlength="55" /></div>
		</div>
        <div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><input type="hidden" name="Access" value="1" />
			' . _('Branch Code') . '</label>
			<select name="BranchCode" class="form-control">';

$sql = "SELECT branchcode FROM custbranch WHERE debtorno = '" . $_SESSION['CustomerID'] . "'";
$result = DB_query($sql);

while ($myrow=DB_fetch_array($result)){

	//Set the first available branch as default value when nothing is selected
	if (!isset($_POST['BranchCode'])) {
		$_POST['BranchCode']= $myrow['branchcode'];
	}

	if (isset($_POST['BranchCode']) and $myrow['branchcode'] == $_POST['BranchCode']){
		echo '<option selected="selected" value="' . $myrow['branchcode'] . '">' . $myrow['branchcode'] . '</option>';
	} else {
		echo '<option value="' . $myrow['branchcode'] . '">' . $myrow['branchcode'] . '</option>';
	}
}
echo '</select></div></div></div>';
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Reports Page Size') .'</label>
	<select name="PageSize" class="form-control">';

if(isset($_POST['PageSize']) and $_POST['PageSize']=='A4'){
	echo '<option selected="selected" value="A4">' . _('A4')  . '</option>';
} else {
	echo '<option value="A4">' . _('A4') . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='A3'){
	echo '<option selected="selected" value="A3">' . _('A3')  . '</option>';
} else {
	echo '<option value="A3">' . _('A3')  . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='A3_landscape'){
	echo '<option selected="selected" value="A3_landscape">' . _('A3') . ' ' . _('landscape')  . '</option>';
} else {
	echo '<option value="A3_landscape">' . _('A3') . ' ' . _('landscape')  . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='letter'){
	echo '<option selected="selected" value="letter">' . _('Letter')  . '</option>';
} else {
	echo '<option value="letter">' . _('Letter')  . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='letter_landscape'){
	echo '<option selected="selected" value="letter_landscape">' . _('Letter') . ' ' . _('landscape')  . '</option>';
} else {
	echo '<option value="letter_landscape">' . _('Letter') . ' ' . _('landscape')  . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='legal'){
	echo '<option selected="selected" value="legal">' . _('Legal')  . '</option>';
} else {
	echo '<option value="legal">' . _('Legal')  . '</option>';
}
if(isset($_POST['PageSize']) and $_POST['PageSize']=='legal_landscape'){
	echo '<option selected="selected" value="legal_landscape">' . _('Legal') . ' ' . _('landscape')  . '</option>';
} else {
	echo '<option value="legal_landscape">' . _('Legal') . ' ' . _('landscape')  . '</option>';
}

echo '</select></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Theme') . '</label>
		<select name="Theme" class="form-control">';

$ThemeDirectory = dir('css/');

while (false != ($ThemeName = $ThemeDirectory->read())){

	if (is_dir('css/' . $ThemeName) AND $ThemeName != '.' AND $ThemeName != '..' AND $ThemeName != '.svn'){

		if (isset($_POST['Theme']) and $_POST['Theme'] == $ThemeName){
			echo '<option selected="selected" value="' . $ThemeName . '">' . $ThemeName  . '</option>';
		} else if (!isset($_POST['Theme']) and ($Theme==$ThemeName)) {
			echo '<option selected="selected" value="' . $ThemeName . '">' . $ThemeName  . '</option>';
		} else {
			echo '<option value="' . $ThemeName . '">' . $ThemeName  . '</option>';
		}
	}
}

echo '</select></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Language') . '</label>
		<select name="UserLanguage" class="form-control">';

foreach ($LanguagesArray as $LanguageEntry => $LanguageName){
	if (isset($_POST['UserLanguage']) and $_POST['UserLanguage'] == $LanguageEntry){
		echo '<option selected="selected" value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	} elseif (!isset($_POST['UserLanguage']) AND $LanguageEntry == $DefaultLanguage) {
		echo '<option selected="selected" value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	} else {
		echo '<option value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	}
}
echo '</select></div>
	</div>
	</div>
	
	<div class="row">

<div class="col-xs-4">		<input type="submit" name="submit" class="btn btn-info" value="' . _('Enter Information') . '" />
	</div>
    </div><br />

	</form>';

include('includes/footer.php');
?>