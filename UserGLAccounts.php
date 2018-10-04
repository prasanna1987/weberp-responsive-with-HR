<?php
/* Maintenance of GL Accounts allowed for a user. */

include('includes/session.php');
$Title = _('User Authorised GL Accounts');
$ViewTopic = 'GeneralLedger';
$BookMark = 'UserGLAccounts';
include('includes/header.php');

if(isset($_POST['SelectedUser']) and $_POST['SelectedUser']<>'') {//If POST not empty:
	$SelectedUser = mb_strtoupper($_POST['SelectedUser']);
} elseif(isset($_GET['SelectedUser']) and $_GET['SelectedUser']<>'') {//If GET not empty:
	$SelectedUser = mb_strtoupper($_GET['SelectedUser']);
} else {// Unset empty SelectedUser:
	unset($_GET['SelectedUser']);
	unset($_POST['SelectedUser']);
	unset($SelectedUser);
}

if(isset($_POST['SelectedGLAccount']) and $_POST['SelectedGLAccount']<>'') {//If POST not empty:
	$SelectedGLAccount = mb_strtoupper($_POST['SelectedGLAccount']);
} elseif(isset($_GET['SelectedGLAccount']) and $_GET['SelectedGLAccount']<>'') {//If GET not empty:
	$SelectedGLAccount = mb_strtoupper($_GET['SelectedGLAccount']);
} else {// Unset empty SelectedGLAccount:
	unset($_GET['SelectedGLAccount']);
	unset($_POST['SelectedGLAccount']);
	unset($SelectedGLAccount);
}

if(isset($_GET['Cancel']) or isset($_POST['Cancel'])) {
	unset($SelectedUser);
	unset($SelectedGLAccount);
}


if(!isset($SelectedUser)) {// If is NOT set a user for GL accounts.
	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ',// Icon title.
		_('User Authorised GL Accounts'), '</h1></a></div>';// Page title.

	/* It could still be the second time the page has been run and a record has been selected for modification - SelectedGLAccount will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then none of the above are true. These will call the same page again and allow update/input or deletion of the records.*/

	if(isset($_POST['Process'])) {
		echo  prnMsg(_('You have not selected any user'), 'error');
	}
	echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
		'<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">', _('Select User'), '</label>
				<select name="SelectedUser" onchange="this.form.submit()" class="form-control">',// Submit when the value of the select is changed.
					'<option value="">', _('Not Yet Selected'), '</option>';
	$Result = DB_query("
		SELECT
			userid,
			realname
		FROM www_users
		ORDER BY userid");
	while ($MyRow = DB_fetch_array($Result)) {
		echo '<option ';
		if(isset($SelectedUser) and $MyRow['userid'] == $SelectedUser) {
			echo 'selected="selected" ';
		}
		echo 'value="', $MyRow['userid'], '">', $MyRow['userid'], ' - ', $MyRow['realname'], '</option>';
	}// End while loop.
	echo '</select></div>
			</div>
		';//Close Select_User table.

	DB_free_result($Result);

	echo	'<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><br /></label>',// Form buttons:
				'<button name="Process" type="submit" class="btn btn-info" value="Submit">', _('Accept'), '</button> </div></div></div><br />'; // "Accept" button.

} else {// If is set a user for GL accounts ($SelectedUser).
	$Result = DB_query("
		SELECT realname
		FROM www_users
		WHERE userid='" . $SelectedUser . "'");
	$MyRow = DB_fetch_array($Result);
	$SelectedUserName = $MyRow['realname'];
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>',// Icon title.
		_('Authorised GL Accounts for'), ' ', $SelectedUserName, '</h1></a></div>';// Page title.

	// BEGIN: Needs $SelectedUser, $SelectedGLAccount:
	if(isset($_POST['submit'])) {
		if(!isset($SelectedGLAccount)) {
			echo  prnMsg(_('You have not selected an GL Account to be authorised for this user'), 'error');
		} else {
			// First check the user is not being duplicated
			$CheckResult = DB_query("
				SELECT count(*)
				FROM glaccountusers
				WHERE accountcode= '" . $SelectedGLAccount . "'
				AND userid = '" . $SelectedUser . "'");
			$CheckRow = DB_fetch_row($CheckResult);
			if($CheckRow[0] > 0) {
				echo  prnMsg(_('The GL Account') . ' ' . $SelectedGLAccount . ' ' . _('is already authorised for this user'), 'error');
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
				$ErrMsg = _('An access permission to a GL account could not be added');
				if(DB_query($SQL, $ErrMsg)) {
					echo  prnMsg(_('An access permission to a GL account was added') . '. ' . _('User') . ': ' . $SelectedUser . '. ' . _('GL Account') . ': ' . $SelectedGLAccount . '.', 'success');
					unset($_GET['SelectedGLAccount']);
					unset($_POST['SelectedGLAccount']);
				}
			}
		}
	} elseif(isset($_GET['delete']) or isset($_POST['delete'])) {
		$SQL = "DELETE FROM glaccountusers
			WHERE accountcode='" . $SelectedGLAccount . "'
			AND userid='" . $SelectedUser . "'";
		$ErrMsg = _('An access permission to a GL account could not be removed');
		if(DB_query($SQL, $ErrMsg)) {
			echo  prnMsg(_('An access permission to a GL account was removed') . '. ' . _('User') . ': ' . $SelectedUser . '. ' . _('GL Account') . ': ' . $SelectedGLAccount . '.', 'success');
			unset($_GET['delete']);
			unset($_POST['delete']);
		}
	} elseif(isset($_GET['ToggleUpdate']) or isset($_POST['ToggleUpdate'])) {// Can update (write) GL accounts flag.
		if(isset($_GET['ToggleUpdate']) and $_GET['ToggleUpdate']<>'') {//If GET not empty.
			$ToggleUpdate = $_GET['ToggleUpdate'];
		} elseif(isset($_POST['ToggleUpdate']) and $_POST['ToggleUpdate']<>'') {//If POST not empty.
			$ToggleUpdate = $_POST['ToggleUpdate'];
		}
		$SQL = "UPDATE glaccountusers
				SET canupd='" . $ToggleUpdate . "'
				WHERE accountcode='" . $SelectedGLAccount . "'
				AND userid='" . $SelectedUser . "'";
		$ErrMsg = _('An access permission to update a GL account could not be modified');
		if(DB_query($SQL, $ErrMsg)) {
			echo  prnMsg(_('An access permission to update a GL account was modified') . '. ' . _('User') . ': ' . $SelectedUser . '. ' . _('GL Account') . ': ' . $SelectedGLAccount . '.', 'success');
			unset($_GET['ToggleUpdate']);
			unset($_POST['ToggleUpdate']);
		}
	}
// END: Needs $SelectedUser, $SelectedGLAccount.

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
		<tr>
			<th class="text">', _('Code'), '</th>
			<th class="text">', _('Name'), '</th>
			<th class="centre">', _('View'), '</th>
			<th class="centre">', _('Update'), '</th>
			<th class="noprint" colspan="2">&nbsp;</th>
		</tr>
		</thead><tbody>';
	$Result = DB_query("
		SELECT
			glaccountusers.accountcode,
			canview,
			canupd,
			chartmaster.accountname
		FROM glaccountusers INNER JOIN chartmaster
		ON glaccountusers.accountcode=chartmaster.accountcode
		WHERE glaccountusers.userid='" . $SelectedUser . "'
		ORDER BY chartmaster.accountcode ASC");
	if(DB_num_rows($Result)>0) {// If the user has access permissions to one or more GL accounts:
		while ($MyRow = DB_fetch_array($Result)) {
			echo '<tr class="striped_row">
				<td class="text">', $MyRow['accountcode'], '</td>
				<td class="text">', $MyRow['accountname'], '</td>
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
					'<td class="noprint"><a href="', $ScriptName, '?SelectedUser=', $SelectedUser, '&amp;SelectedGLAccount=', $MyRow['accountcode'], '&amp;ToggleUpdate=0" onclick="return confirm(\'', _('Are you sure you wish to remove Update for this GL Account?'), '\');" class="btn btn-danger">', _('Remove Update');
			} else {
				echo _('No'), '</td>',
					'<td class="noprint"><a href="', $ScriptName, '?SelectedUser=', $SelectedUser, '&amp;SelectedGLAccount=', $MyRow['accountcode'], '&amp;ToggleUpdate=1" onclick="return confirm(\'', _('Are you sure you wish to add Update for this GL Account?'), '\');" class="btn btn-success">', _('Add Update');
			}
			echo	'</a></td>',
					'<td class="noprint"><a href="', $ScriptName, '?SelectedUser=', $SelectedUser, '&amp;SelectedGLAccount=', $MyRow['accountcode'], '&amp;delete=yes" onclick="return confirm(\'', _('Are you sure you wish to un-authorise this GL Account?'), '\');" class="btn btn-danger">', _('Un-authorise'), '</a></td>',
				'</tr>';
		}// End while list loop.
	} else {// If the user does not have access permissions to GL accounts:
		echo '<tr><td colspan="6">', _('User does not have access permissions to GL accounts'), '</td></tr>';
	}
	echo '</tbody></table></div></div></div>',
		'<br />',
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
		'<input name="SelectedUser" type="hidden" value="', $SelectedUser, '" />',
		'
		<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">';
	$Result = DB_query("
		SELECT
			accountcode,
			accountname
		FROM chartmaster
		WHERE NOT EXISTS (SELECT glaccountusers.accountcode
		FROM glaccountusers
		WHERE glaccountusers.userid='" . $SelectedUser . "'
			AND glaccountusers.accountcode=chartmaster.accountcode)
		ORDER BY accountcode");
	if(DB_num_rows($Result)>0) {// If the user does not have access permissions to one or more GL accounts:
		echo	_('Add access permissions to a GL account'), '</label>
				<select name="SelectedGLAccount" class="form-control">';
		if(!isset($_POST['SelectedGLAccount'])) {
			echo '<option selected="selected" value="">', _('Not Yet Selected'), '</option>';
		}
		while ($MyRow = DB_fetch_array($Result)) {
			if(isset($_POST['SelectedGLAccount']) and $MyRow['accountcode'] == $_POST['SelectedGLAccount']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $MyRow['accountcode'], '">', $MyRow['accountcode'], ' - ', $MyRow['accountname'], '</option>';
		}
		echo	'</select></div></div>
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><br /></label><input type="submit" class="btn btn-success" name="submit" value="Accept" /></div></div>';
	} else {// If the user has access permissions to all GL accounts:
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><br /></label><br />'._('User has access permissions to all GL accounts'),'</div></div>';
	}
	echo		'</div>
			';
	DB_free_result($Result);
	echo '<div class="row">',
		'<div class="col-xs-4">', // Form buttons:
			'<button onclick="javascript:window.print()" type="button" class="btn btn-info">', _('Print'), '</button></div>', // "Print" button.
			'<div class="col-xs-4"><button formaction="UserGLAccounts.php?Cancel" type="submit" class="btn btn-info"> ', _('Select A Different User'), '</button></div>'; // "Select A Different User" button.
}
echo		'<div class="col-xs-4"><button onclick="window.location=\'menu_data.php?Application=GL\'" type="button" class="btn btn-default">', _('Return'), '</button></div>', // "Return" button.
		'</div><br />
	</form>';

include('includes/footer.php');
?>
