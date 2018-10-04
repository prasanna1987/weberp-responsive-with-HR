<?php


include('includes/session.php');
$Title = _('Supplier Login Configuration');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include ('includes/LanguagesArray.php');


if (!isset($_SESSION['SupplierID'])){
	echo '
		<br />';
	echo '<div align="center" class="alert alert-info alert-dismissable" id="MessageContainerHead">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 ' , prnMsg(_('A supplier must first be selected before logins can be defined for it') . '<br /><br /><a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-info">' . _('Select A Supplier') . '</a>','info');
	include('includes/footer.php');
	exit;
}

$ModuleList = array(_('Orders'),
					_('Receivables'),
					_('Payables'),
					_('Purchasing'),
					_('Inventory'),
					_('Manufacturing'),
					_('General Ledger'),
					_('Asset Manager'),
					_('Petty Cash'),
					_('Setup'));



echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Supplier') . ' : ' . $_SESSION['SupplierID'] . _(' has been selected') . '</h1></a></div>';
echo '<p align="left"><a href="' . $RootPath . '/SelectSupplier.php?" class="btn btn-default">' . _('Back to Suppliers') . '</a></p>';



if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if (mb_strlen($_POST['UserID'])<4){
		$InputError = 1;
		echo prnMsg(_('The user ID entered must be at least 4 characters long'),'error');
	} elseif (ContainsIllegalCharacters($_POST['UserID'])) {
		$InputError = 1;
		echo prnMsg(_('User names cannot contain any of the following characters') . " - ' & + \" \\ " . _('or a space'),'error');
	} elseif (mb_strlen($_POST['Password'])<5){
			$InputError = 1;
			echo prnMsg(_('The password entered must be at least 5 characters long'),'error');
	} elseif (mb_strstr($_POST['Password'],$_POST['UserID'])!= False){
		$InputError = 1;
		echo prnMsg(_('The password cannot contain the user id'),'error');
	}

	/* Make a comma separated list of modules allowed ready to update the database*/
	$i=0;
	$ModulesAllowed = '';
	while ($i < count($ModuleList)){
		$ModulesAllowed .= ' '. ',';//no any modules allowed for the suppliers
		$i++;
	}


	if ($InputError !=1) {

		$sql = "INSERT INTO www_users (userid,
										realname,
										supplierid,
										password,
										phone,
										email,
										pagesize,
										fullaccess,
										defaultlocation,
										lastvisitdate,
										modulesallowed,
										displayrecordsmax,
										theme,
										language)
						VALUES ('" . $_POST['UserID'] . "',
							'" . $_POST['RealName'] ."',
							'" . $_SESSION['SupplierID'] ."',
							'" . CryptPass($_POST['Password']) ."',
							'" . $_POST['Phone'] . "',
							'" . $_POST['Email'] ."',
							'" . $_POST['PageSize'] ."',
							'" . $_POST['Access'] . "',
							'" . $_POST['DefaultLocation'] ."',
							'" . date($_SESSION['DefaultDateFormat']) ."',
							'" . $ModulesAllowed . "',
							'" . $_SESSION['DefaultDisplayRecordsMax'] . "',
							'" . $_POST['Theme'] . "',
							'". $_POST['UserLanguage'] ."')";
		$ErrMsg = _('The user could not be added because');
		$DbgMsg = _('The SQL that was used to insert the new user and failed was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);
		echo prnMsg( _('A new supplier login has been created'), 'success');
		include('includes/footer.php');
		exit;
	}
}
echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('User Login') . ':</label>
			<input type="text" pattern="[^><+-]{4,20}" title="'._('The user ID must has more than 4 legal characters').'" required="required" placeholder="'._('More than 4 characters').'" name="UserID" class="form-control" size="22" maxlength="20" /></div>
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
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Password') . ':</label>
		<input type="password" pattern=".{5,20}" placeholder="'._('More than 5 characters').'" required="required" title="'._('Password must be more than 5 characters').'" class="form-control"  name="Password" size="22" maxlength="20" value="' . $_POST['Password'] . '" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Full Name') . ':</label>
		<input type="text" class="form-control" pattern=".{0,35}" title="'._('Must be less than 35 characters').'" placeholder="'._('User name').'" name="RealName" value="' . $_POST['RealName'] . '" size="36" maxlength="35" /></div>
	</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Telephone No') . ':</label>
		<input type="tel" pattern="[\s+()-\d]{1,30}" title="'._('The input must be phone number').'" placeholder="'._('number and allowed charactrs').'" name="Phone" class="form-control" value="' . $_POST['Phone'] . '" size="32" maxlength="30" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Email Address') .':</label>
		<input type="email" class="form-control" name="Email" title="'._('The input must be email address').'" placeholder="'._('email address format').'" value="' . $_POST['Email'] .'" size="32" maxlength="55" /></div>
	</div>';





//Make an array of the security roles where only one role is active and is ID 1

//For the security role selection box, we will only show roles that have:
//- Only one entry in securitygroups AND the tokenid of this entry == 9

//First get all available security role ID's'
$RolesResult = DB_query("SELECT secroleid FROM securityroles");
$FoundTheSupplierRole = false;
while ($myroles = DB_fetch_array($RolesResult)){
	//Now look to find the tokens for the role - we just wnat the role that has just one token i.e. token 9
	$TokensResult = DB_query("SELECT tokenid
								FROM securitygroups
								WHERE secroleid = '" . $myroles['secroleid'] ."'");

	while ($mytoken = DB_fetch_row($TokensResult)) {
		if ($mytoken[0]==9){
			echo'<input type="hidden" name="Access" value ="' . $myroles['secroleid'] . '" />';
			$FoundTheSupplierRole = true;
			break;
		}
	}
}

if (!$FoundTheSupplierRole){
    echo '
          </div>
          </form></div></div>';
	echo prnMsg(_('The supplier login role is expected to contain just one token - number 9. There is no such role currently defined - so a supplier login cannot be set up until this role is defined'),'error');
	include('includes/footer.php');
	exit;
}


echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Default Location') . '</label>
	<select name="DefaultLocation" class="form-control">';

$sql = "SELECT locations.loccode, locationname FROM locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
$result = DB_query($sql);

while ($myrow=DB_fetch_array($result)){

	if (isset($_POST['DefaultLocation'])
		AND $myrow['loccode'] == $_POST['DefaultLocation']){

		echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	} else {
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
}
echo '</select></div></div>';
/*echo '<tr><td>' . _('Reports Page Size') .':</td>
	<td><select name="PageSize">';

if(isset($_POST['PageSize']) and $_POST['PageSize']=='A4'){
	echo '<option selected="selected" value="A4">' . _('A4') . '</option>';
} else {
	echo '<option value="A4">' . _('A4') . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='A3'){
	echo '<option selected="selected" value="A3">' . _('A3') . '</option>';
} else {
	echo '<option value="A3">' . _('A3') . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='A3_landscape'){
	echo '<option selected="selected" value="A3_landscape">' . _('A3') . ' ' . _('landscape') . '</option>';
} else {
	echo '<option value="A3_landscape">' . _('A3') . ' ' . _('landscape') . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='letter'){
	echo '<option selected="selected" value="letter">' . _('Letter') . '</option>';
} else {
	echo '<option value="letter">' . _('Letter') . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='letter_landscape'){
	echo '<option selected="selected" value="letter_landscape">' . _('Letter') . ' ' . _('landscape') . '</option>';
} else {
	echo '<option value="letter_landscape">' . _('Letter') . ' ' . _('landscape') . '</option>';
}

if(isset($_POST['PageSize']) and $_POST['PageSize']=='legal'){
	echo '<option selected="selected" value="legal">' . _('Legal') . '</option>';
} else {
	echo '<option value="legal">' . _('Legal') . '</option>';
}
if(isset($_POST['PageSize']) and $_POST['PageSize']=='legal_landscape'){
	echo '<option selected="selected" value="legal_landscape">' . _('Legal') . ' ' . _('landscape') . '</option>';
} else {
	echo '<option value="legal_landscape">' . _('Legal') . ' ' . _('landscape') . '</option>';
}

echo '</select></td></tr>';

echo '<tr>
	<td>' . _('Theme') . ':</td>
	<td><select name="Theme">';

$ThemeDirectory = dir('css/');


while (false != ($ThemeName = $ThemeDirectory->read())){

	if (is_dir('css/' . $ThemeName) AND $ThemeName != '.' AND $ThemeName != '..' AND $ThemeName != '.svn'){

		if (isset($_POST['Theme']) and $_POST['Theme'] == $ThemeName){
			echo '<option selected="selected" value="' . $ThemeName . '">' . $ThemeName . '</option>';
		} else if (!isset($_POST['Theme']) and ($Theme==$ThemeName)) {
			echo '<option selected="selected" value="' . $ThemeName . '">' . $ThemeName . '</option>';
		} else {
			echo '<option value="' . $ThemeName . '">' . $ThemeName . '</option>';
		}
	}
}

echo '</select></td></tr>';


echo '<tr>
	<td>' . _('Language') . ':</td>
	<td><select name="UserLanguage">';

foreach ($LanguagesArray as $LanguageEntry => $LanguageName){
	if (isset($_POST['UserLanguage']) and $_POST['UserLanguage'] == $LanguageEntry){
		echo '<option selected="selected" value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	} elseif (!isset($_POST['UserLanguage']) and $LanguageEntry == $DefaultLanguage) {
		echo '<option selected="selected" value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	} else {
		echo '<option value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	}
}
</select></td>
	</tr>*/
	echo ' 
	<br />
	</div>
	<div class="row" align="center">
	<div>
		<input type="submit" name="submit" class="btn btn-success" value="' . _('Enter Information') . '" />
	</div>
    </div><br />

	</form></div></div>';

echo '<script  type="text/javascript">defaultControl(document.forms[0].UserID);</script>';

include('includes/footer.php');
?>
