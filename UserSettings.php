	<?php
/* Allows the user to change system wide defaults for the theme - appearance, the number of records to show in searches and the language to display messages in */
include('includes/session.php');
$Title = _('User Settings');
$ViewTopic = 'GettingStarted';
$BookMark = 'UserSettings';
include('includes/header.php');

echo '<div class="block-header">
                            <a href="" class="header-title-link"><h1>
                               ',// Icon title.
	_('User Settings'), ' 
                            </h1></a>
                        </a>
                    </div>';// Page title.
	

$PDFLanguages = array(
	_('Latin Western Languages - Times'),
	_('Eastern European Russian Japanese Korean Hebrew Arabic Thai'),
	_('Chinese'),
	_('Free Serif')
);

if(isset($_POST['Modify'])) {
	// no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if($_POST['DisplayRecordsMax'] <= 0) {
		$InputError = 1;
		echo  prnMsg(_('The Maximum Number of Records on Display entered must not be negative') . '. ' . _('0 will default to system setting'),'error') ,'<a href="javascript:void(0)" class="alert-link"></a>!
                                </div>';
		//prnMsg(_('The Maximum Number of Records on Display entered must not be negative') . '. ' . _('0 will default to system setting'),'error');
	}

	//!!!for the demo only - enable this check so password is not changed
	if($AllowDemoMode AND $_POST['Password'] != '') {
		$InputError = 1;
		echo   prnMsg(_('Cannot change password in the demo or others would be locked out!'),'warn');
	}

 	$UpdatePassword = 'N';

	if($_POST['PasswordCheck'] != '') {
		if(mb_strlen($_POST['Password']) < 5) {
			$InputError = 1;
			echo  
			prnMsg(_('The password entered must be at least 5 characters long'),'error');
		} elseif(mb_strstr($_POST['Password'],$_SESSION['UserID'])!= False) {
			$InputError = 1;
			echo  prnMsg(_('The password cannot contain the user id'), 'error');
		}
		if($_POST['Password'] != $_POST['PasswordCheck']) {
			$InputError = 1;
			echo  prnMsg(_('The password and password confirmation fields entered do not match'), 'error'),'</p>';
		} else {
			$UpdatePassword = 'Y';
		}
	}


	if($InputError != 1) {
		// no errors
		if($UpdatePassword != 'Y') {
			$sql = "UPDATE www_users
					SET displayrecordsmax='" . $_POST['DisplayRecordsMax'] . "',
						theme='" . $_POST['Theme'] . "',
						language='" . $_POST['Language'] . "',
						email='" . $_POST['email'] . "',
						showpagehelp='" . $_POST['ShowPageHelp'] . "',
						showfieldhelp='" . $_POST['ShowFieldHelp'] . "',
						pdflanguage='" . $_POST['PDFLanguage'] . "'
					WHERE userid = '" . $_SESSION['UserID'] . "'";
			$ErrMsg = _('The user alterations could not be processed because');
			$DbgMsg = _('The SQL that was used to update the user and failed was');
			$Result = DB_query($sql, $ErrMsg, $DbgMsg);
			
			echo  
                                    prnMsg( _('The user settings have been updated') . '. ' . _('Be sure to remember your password for the next time you login'),'success');
			
		} else {
			$sql = "UPDATE www_users
					SET displayrecordsmax='" . $_POST['DisplayRecordsMax'] . "',
						theme='" . $_POST['Theme'] . "',
						language='" . $_POST['Language'] . "',
						email='" . $_POST['email'] ."',
						showpagehelp='" . $_POST['ShowPageHelp'] . "',
						showfieldhelp='" . $_POST['ShowFieldHelp'] . "',
						pdflanguage='" . $_POST['PDFLanguage'] . "',
						password='" . CryptPass($_POST['Password']) . "'
					WHERE userid = '" . $_SESSION['UserID'] . "'";
			$ErrMsg = _('The user alterations could not be processed because');
			$DbgMsg = _('The SQL that was used to update the user and failed was');
			$Result = DB_query($sql, $ErrMsg, $DbgMsg);
			echo  
                                    prnMsg(_('The user settings have been updated'),'success');
			
		}
		// Update the session variables to reflect user changes on-the-fly:
		$_SESSION['DisplayRecordsMax'] = $_POST['DisplayRecordsMax'];
		$_SESSION['Theme'] = trim($_POST['Theme']); /*already set by session.php but for completeness */
		$Theme = $_SESSION['Theme'];
		$_SESSION['Language'] = trim($_POST['Language']);
		$_SESSION['ShowPageHelp'] = $_POST['ShowPageHelp'];
		$_SESSION['ShowFieldHelp'] = $_POST['ShowFieldHelp'];
		$_SESSION['PDFLanguage'] = $_POST['PDFLanguage'];
		include('includes/LanguageSetup.php');// After last changes in LanguageSetup.php, is it required to update?
	}
}
echo '
<div class="row gutter30">
        <div class="col-xs-12">';
echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


echo '<div class="row"><div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('User ID'), '</label>
			<input type="text" name="fname" class="form-control" value="', $_SESSION['UserID'], '" readonly="readonly" /></div>
</div>

			<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('User Name'), '</label>
			<input type="text" name="fname" class="form-control" value="', $_SESSION['UsersRealName'], '" readonly="readonly" /></div>
</div>
			
		<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('Maximum No of Records to Display'), '</label>
			<input class="form-control" maxlength="3" name="DisplayRecordsMax" required="required" size="3" title="', _('The input must be positive integer'), '" type="text" value="', $_SESSION['DisplayRecordsMax'], '" /></div>
		</div></div>';

// Select language:
//echo '<div class="col-xs-4">
//        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('Language'), '</label>
//	<select name="Language" class="form-control" >';
//if(!isset($_POST['Language'])) {
//	$_POST['Language'] = $_SESSION['Language'];
//}
//foreach($LanguagesArray as $LanguageEntry => $LanguageName) {
//	echo '<option ';
//	if(isset($_POST['Language']) AND $_POST['Language'] == $LanguageEntry) {
//		echo 'selected="selected" ';
//	}
//	echo 'value="', $LanguageEntry, '">', $LanguageName['LanguageName'], '</option>';
//}
//echo '</select></div>
//	</div>';

// Select theme:
echo '<input type="hidden" name="Language" value="en_IN.utf8" />
<input type="hidden" name="Theme" value="'.$ThemeName.'" />';

if(!isset($_POST['PasswordCheck'])) {
	$_POST['PasswordCheck']='';
}
if(!isset($_POST['Password'])) {
	$_POST['Password']='';
}
echo '<div class="row">
	
	<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-12 control-label" for="example-select">', _('New Password'), '(Leave blank, if not changing)</label>
		<input name="Password" pattern="(?!^', $_SESSION['UserID'], '$).{5,}" placeholder="', _('More than 5 characters'), '" size="20" title="', _('Must be more than 5 characters and cannot be as same as userid'), '" type="password" class="form-control" value="', $_POST['Password'], '" /></div>
	</div>
	
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label" for="example-select">', _('Confirm Password'), '</label>
		<input name="PasswordCheck" pattern="(?!^', $_SESSION['UserID'], '$).{5,}" placeholder="', _('More than 5 characters'), '" size="20" title="', _('Must be more than 5 characters and cannot be as same as userid'), '" class="form-control" type="password" value="', $_POST['PasswordCheck'], '" /></div>
	</div>
	
	
	<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('Email'), '</label>';

$sql = "SELECT
			email,
			showpagehelp,
			showfieldhelp
		from www_users WHERE userid = '" . $_SESSION['UserID'] . "'";
$Result = DB_query($sql);
$myrow = DB_fetch_array($Result);

if(!isset($_POST['email'])) {
	$_POST['email'] = $myrow['email'];
}
$_POST['ShowPageHelp'] = $myrow['showpagehelp'];
$_POST['ShowFieldHelp'] = $myrow['showfieldhelp'];

echo '<input name="email" size="40" type="email" class="form-control" value="', $_POST['email'], '" /></div>
	
	<input type="hidden" name="ShowPageHelp" value="0" />
	<input type="hidden" name="ShowFieldHelp" value="0" />
	<input type="hidden" name="PDFLanguage" value="0" /></div></div>
	';

// Turn off/on page help:
//echo '
//<div class="col-xs-4">
//        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('Display page help'), '</label>
//		<select id="ShowPageHelp" name="ShowPageHelp" class="form-control">';
//if($_POST['ShowPageHelp']==0) {
//	echo '<option selected="selected" value="0">', _('No'), '</option>',
//		 '<option value="1">', _('Yes'), '</option>';
//} else {
//	echo '<option value="0">', _('No'), '</option>',
// 		 '<option selected="selected" value="1">', _('Yes'), '</option>';
//}
//echo '</select>',
//		(!isset($_SESSION['ShowFieldHelp']) || $_SESSION['ShowFieldHelp'] ? _('Show page help when available') : ''), // If the parameter $_SESSION['ShowFieldHelp'] is not set OR is TRUE, shows this field help text.
//		'</div>
//	</div>';
//// Turn off/on field help:
//echo '<div class="col-xs-4">
//        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('Display field help'), '</label><select id="ShowFieldHelp" name="ShowFieldHelp" class="form-control">';
//if($_POST['ShowFieldHelp']==0) {
//	echo '<option selected="selected" value="0">', _('No'), '</option>',
//		 '<option value="1">', _('Yes'), '</option>';
//} else {
//	echo '<option value="0">', _('No'), '</option>',
// 		 '<option selected="selected" value="1">', _('Yes'), '</option>';
//}
//echo '</select>',
//		(!isset($_SESSION['ShowFieldHelp']) || $_SESSION['ShowFieldHelp'] ? _('Show field help when available') : ''), // If the parameter $_SESSION['ShowFieldHelp'] is not set OR is TRUE, shows this field help text.
//		'</div>
//	</div>';
//// PDF Language Support:
//if(!isset($_POST['PDFLanguage'])) {
//	$_POST['PDFLanguage']=$_SESSION['PDFLanguage'];
//}
//echo '<div class="col-xs-4">
//        <div class="form-group has-error"> <label class="col-md-8 control-label" for="example-select">', _('PDF Language Support'), ': </label>
//		<select name="PDFLanguage" class="form-control">';
//for($i=0; $i<count($PDFLanguages); $i++) {
//	if($_POST['PDFLanguage'] == $i) {
//		echo '<option selected="selected" value="', $i, '">', $PDFLanguages[$i], '</option>';
//	} else {
//		echo '<option value="', $i, '">', $PDFLanguages[$i], '</option>';
//	}
//}
//echo '</select></div>
//	</div>
	
	
	echo '<div class="row" align="center">
        <input name="Modify" type="submit" class="btn btn-success" value="', _('Update'), '" /></div>
 <br />
	</form></div></div>';

include('includes/footer.php');
?>
