<?php
/* Maintenance of GL Accounts allowed for a user. */

include('includes/session.php');
$Title = _('GL Account Authorised Users');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLAccountUsers';
include('includes/header.php');

if(isset($_POST['SelectedGLAccount']) and $_POST['SelectedGLAccount']<>'') {//If POST not empty:
	$SelectedGLAccount = mb_strtoupper($_POST['SelectedGLAccount']);
} elseif(isset($_GET['SelectedGLAccount']) and $_GET['SelectedGLAccount']<>'') {//If GET not empty:
	$SelectedGLAccount = mb_strtoupper($_GET['SelectedGLAccount']);
} else {// Unset empty SelectedGLAccount:
	unset($_GET['SelectedGLAccount']);
	unset($_POST['SelectedGLAccount']);
	unset($SelectedGLAccount);
}

if(isset($_POST['SelectedUser']) and $_POST['SelectedUser']<>'') {//If POST not empty:
	$SelectedUser = mb_strtoupper($_POST['SelectedUser']);
} elseif(isset($_GET['SelectedUser']) and $_GET['SelectedGLAccount']<>'') {//If GET not empty:
	$SelectedUser = mb_strtoupper($_GET['SelectedUser']);
} else {// Unset empty SelectedUser:
	unset($_GET['SelectedUser']);
	unset($_POST['SelectedUser']);
	unset($SelectedUser);
}

if(isset($_POST['Cancel']) or isset($_GET['Cancel'] )) {
	unset($SelectedGLAccount);
	unset($SelectedUser);
}


if(!isset($SelectedGLAccount)) {// If is NOT set a GL account for users.

	/* It could still be the second time the page has been run and a record has been selected for modification - SelectedUser will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then none of the above are true. These will call the same page again and allow update/input or deletion of the records*/

	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ',// Icon title.
		_('GL Account Authorised Users'), '</h1></a></div>';// Page title.
	if(isset($_POST['Process'])) {
		echo prnMsg(_('You have not selected any GL Account'), 'error');
	}
	echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
		'<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', '</label>
				<select name="SelectedGLAccount" onchange="this.form.submit()" class="form-control">',// Submit when the value of the select is changed.
					'<option value="">', _('Not Yet Selected'), '</option>';
	$Result = DB_query("
		SELECT
			accountcode,
			accountname
		FROM chartmaster
		ORDER BY accountcode");
	while ($MyRow = DB_fetch_array($Result)) {
		echo '<option ';
		if(isset($SelectedGLAccount) and $MyRow['accountcode'] == $SelectedGLAccount) {
			echo 'selected="selected" ';
		}
		echo 'value="', $MyRow['accountcode'] . '">' . $MyRow['accountcode'] . ' - ' . $MyRow['accountname'] . '</option>';
	}// End while loop.
	echo '</select></div>
			</div>
		';//Close Select_GL_Account table.
	DB_free_result($Result);
	echo	'<div class="col-xs-4">
<div class="form-group"> <br />',// Form buttons:
				'<button name="Process" type="submit" class="btn btn-info" value="Submit">', _('Select a GL account to add athorised users'), '</button></div>
</div>		
				 '; // "Accept" button.

} else {// If is set a GL account for users ($SelectedGLAccount).
	$Result = DB_query("
		SELECT accountname
		FROM chartmaster
		WHERE accountcode='" . $SelectedGLAccount . "'");
	$MyRow = DB_fetch_array($Result);
	$SelectedGLAccountName = $MyRow['accountname'];
	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ',// Icon title.
		_('Authorised Users for'), ' ', $SelectedGLAccountName, '</h1></a></div>';// Page title.

	// BEGIN: Needs $SelectedGLAccount, $SelectedUser.
	if(isset($_POST['submit'])) {
		if(!isset($SelectedUser)) {
			echo prnMsg(_('You have not selected an user to be authorised to use this GL Account'), 'error');
		} else {
			// First check the user is not being duplicated
			$CheckResult = DB_query("
				SELECT count(*)
				FROM glaccountusers
				WHERE accountcode= '" . $SelectedGLAccount . "'
				AND userid = '" . $SelectedUser . "'");
			$CheckRow = DB_fetch_row($CheckResult);

			if($CheckRow[0] > 0) {
				echo prnMsg(_('The user') . ' ' . $SelectedUser . ' ' . _('is already authorised to use this GL Account'), 'error');
			} else {
				// Add new record on submit
				$SQL = "INSERT INTO glaccountusers (
						accountcode,
						userid,
						canview,
						canupd
					) VALUES ('" .
						$SelectedGLAccount . "','" .
						$SelectedUser . "',
						'1',
						'1')";
				$ErrMsg = _('An access permission for a user could not be added');
				if(DB_query($SQL, $ErrMsg)) {
					echo prnMsg(_('An access permission for a user was added') . '. ' . _('GL Account') . ': ' . $SelectedGLAccount . '. ' . _('User') . ': ' . $SelectedUser . '.', 'success');
					unset($_GET['SelectedUser']);
					unset($_POST['SelectedUser']);
				}
			}
		}
	} elseif(isset($_GET['delete'])) {
		$SQL = "DELETE FROM glaccountusers
			WHERE accountcode='" . $SelectedGLAccount . "'
			AND userid='" . $SelectedUser . "'";
		$ErrMsg = _('An access permission for a user could not be removed');
		if(DB_query($SQL, $ErrMsg)) {
			echo prnMsg(_('An access permission for a user was removed') . '. ' . _('GL Account') . ': ' . $SelectedGLAccount . '. ' . _('User') . ': ' . $SelectedUser . '.', 'success');
			unset($_GET['delete']);
			unset($_POST['delete']);
		}
	} elseif(isset($_GET['ToggleUpdate'])) {
		$SQL = "UPDATE glaccountusers
				SET canupd='" . $_GET['ToggleUpdate'] . "'
				WHERE accountcode='" . $SelectedGLAccount . "'
				AND userid='" . $SelectedUser . "'";
		$ErrMsg = _('An access permission to update a GL account could not be modified');
		if(DB_query($SQL, $ErrMsg)) {
			echo prnMsg(_('An access permission to update a GL account was modified') . '. ' . _('GL Account') . ': ' . $SelectedGLAccount . '. ' . _('User') . ': ' . $SelectedUser . '.', 'success');
			unset($_GET['ToggleUpdate']);
			unset($_POST['ToggleUpdate']);
		}
	}
	// END: Needs $SelectedGLAccount, $SelectedUser.

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
		<tr>
			<th class="text">', _('User Code'), '</th>
			<th class="text">', _('User Name'), '</th>
			<th class="centre">', _('View'), '</th>
			<th class="centre">', _('Update'), '</th>
			<th colspan="2">', _('Actions'), '</th>
		</tr>
		</thead><tbody>';
	$Result = DB_query("
		SELECT
			glaccountusers.userid,
			canview,
			canupd,
			www_users.realname
		FROM glaccountusers INNER JOIN www_users
		ON glaccountusers.userid=www_users.userid
		WHERE glaccountusers.accountcode='" . $SelectedGLAccount . "'
		ORDER BY glaccountusers.userid ASC");
	if(DB_num_rows($Result)>0) {// If the GL account has access permissions for one or more users:
		while($MyRow = DB_fetch_array($Result)) {
			echo '<tr class="striped_row">
				<td class="text">', $MyRow['userid'], '</td>
				<td class="text">', $MyRow['realname'], '</td>
				<td class="centre">';
			if($MyRow['canview'] == 1) {
				echo _('Yes');
			} else {
				echo _('No');
			}
			echo '</td>
				<td class="centre">';

			$ScriptName = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8');
			if($MyRow['canupd'] == 1) {
				echo _('Yes'), '</td>',
					'<td class="noprint"><a href="', $ScriptName, '?SelectedGLAccount=', $SelectedGLAccount, '&amp;SelectedUser=', $MyRow['userid'], '&amp;ToggleUpdate=0" class="btn btn-info" onclick="return confirm(\'', _('Are you sure you wish to remove Update for this user?'), '\');">', _('Remove Update');
			} else {
				echo _('No'), '</td>',
					'<td class="noprint"><a href="', $ScriptName, '?SelectedGLAccount=', $SelectedGLAccount, '&amp;SelectedUser=', $MyRow['userid'], '&amp;ToggleUpdate=1" class="btn btn-info" onclick="return confirm(\'', _('Are you sure you wish to add Update for this user?'), '\');">', _('Add Update');
			}
			echo	'</a></td>',
					'<td class="noprint"><a href="', $ScriptName, '?SelectedGLAccount=', $SelectedGLAccount, '&amp;SelectedUser=', $MyRow['userid'], '&amp;delete=yes" class="btn btn-info" onclick="return confirm(\'', _('Are you sure you wish to un-authorise this user?'), '\');">', _('Un-authorise'), '</a></td>',
				'</tr>';
		}// End while list loop.
	} else {// If the GL account does not have access permissions for users:
		echo '<tr><td class="centre" colspan="6">', _('GL account does not have access permissions for users'), '</td></tr>';
	}
	echo '</tbody></table></div></div></div>',
		'<br />',
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
		'<input name="SelectedGLAccount" type="hidden" value="', $SelectedGLAccount, '" />',
		'
		<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">';
	$Result = DB_query("
		SELECT
			userid,
			realname
		FROM www_users
		WHERE NOT EXISTS (SELECT glaccountusers.userid
		FROM glaccountusers
		WHERE glaccountusers.accountcode='" . $SelectedGLAccount . "'
			AND glaccountusers.userid=www_users.userid)
		ORDER BY userid");
	if(DB_num_rows($Result)>0) {// If the GL account does not have access permissions for one or more users:
		echo	_('Select user to add access permissions'), '</label>
				<select name="SelectedUser" class="form-control">';
		if(!isset($_POST['SelectedUser'])) {
			echo '<option selected="selected" value="">', _('Not Yet Selected'), '</option>';
		}
		while ($MyRow = DB_fetch_array($Result)) {
			if(isset($_POST['SelectedUser']) and $MyRow['userid'] == $_POST['SelectedUser']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $MyRow['userid'], '">', $MyRow['userid'], ' - ', $MyRow['realname'], '</option>';
		}
		echo	'</select></div></div>
				<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" class="btn btn-info" name="submit" value="Submit" />';
	} else {// If the GL account has access permissions for all users:
		echo _('This GL account has access permissions for all users');
	}
	echo		'</div>
			</div>
		</div>';
	DB_free_result($Result);
	echo '<br />',
		'<div class="row noprint">', // Form buttons:
			'', // "Print" button.
			'<div class="col-xs-4"><button formaction="GLAccountUsers.php?Cancel" type="submit" class="btn btn-info">', _('Select A Different GL account'), '</button></div>'; // "Select A Different GL account" button.
}
echo		'<div class="col-xs-4"><button onclick="window.location=\'menu_data.php?Application=GL\'" type="button" class="btn btn-default">', _('Return'), '</button></div>', // "Return" button.
		'</div>
	</form>';

include('includes/footer.php');
?>
