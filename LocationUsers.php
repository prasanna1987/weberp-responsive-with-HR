<?php

include('includes/session.php');
$Title = _('Inventory Location Authorised Users Maintenance');
$ViewTopic = 'Inventory';// Filename in ManualContents.php's TOC.
$BookMark = 'LocationUsers';// Anchor's id in the manual's html document.
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['SelectedUser'])) {
	$SelectedUser = mb_strtoupper($_POST['SelectedUser']);
} elseif (isset($_GET['SelectedUser'])) {
	$SelectedUser = mb_strtoupper($_GET['SelectedUser']);
} else {
	$SelectedUser = '';
}

if (isset($_POST['SelectedLocation'])) {
	$SelectedLocation = mb_strtoupper($_POST['SelectedLocation']);
} elseif (isset($_GET['SelectedLocation'])) {
	$SelectedLocation = mb_strtoupper($_GET['SelectedLocation']);
}

if (isset($_POST['Cancel'])) {
	unset($SelectedLocation);
	unset($SelectedUser);
}

if (isset($_POST['Process'])) {
	if ($_POST['SelectedLocation'] == '') {
		echo prnMsg(_('You have not selected any Location'), 'error');
		echo '<br />';
		unset($SelectedLocation);
		unset($_POST['SelectedLocation']);
	}
}

if (isset($_POST['submit'])) {

	$InputError = 0;

	if ($_POST['SelectedUser'] == '') {
		$InputError = 1;
		echo prnMsg(_('You have not selected an user to be authorised to use this Location'), 'error');
		echo '<br />';
		unset($SelectedLocation);
	}

	if ($InputError != 1) {

		// First check the user is not being duplicated

		$CheckSql = "SELECT count(*)
			     FROM locationusers
			     WHERE loccode= '" . $_POST['SelectedLocation'] . "'
				 AND userid = '" . $_POST['SelectedUser'] . "'";

		$CheckResult = DB_query($CheckSql);
		$CheckRow = DB_fetch_row($CheckResult);

		if ($CheckRow[0] > 0) {
			$InputError = 1;
			echo prnMsg(_('The user') . ' ' . $_POST['SelectedUser'] . ' ' . _('is already authorised to use this location'), 'error');
		} else {
			// Add new record on submit
			$SQL = "INSERT INTO locationusers (loccode,
												userid,
												canview,
												canupd)
										VALUES ('" . $_POST['SelectedLocation'] . "',
												'" . $_POST['SelectedUser'] . "',
												'1',
												'1')";

			$msg = _('User') . ': ' . $_POST['SelectedUser'] . ' ' . _('authority to use the') . ' ' . $_POST['SelectedLocation'] . ' ' . _('location has been changed');
			$Result = DB_query($SQL);
			echo prnMsg($msg, 'success');
			unset($_POST['SelectedUser']);
		}
	}
} elseif (isset($_GET['delete'])) {
	$SQL = "DELETE FROM locationusers
		WHERE loccode='" . $SelectedLocation . "'
		AND userid='" . $SelectedUser . "'";

	$ErrMsg = _('The Location user record could not be deleted because');
	$Result = DB_query($SQL, $ErrMsg);
	echo prnMsg(_('User') . ' ' . $SelectedUser . ' ' . _('has had their authority to use the') . ' ' . $SelectedLocation . ' ' . _('location removed'), 'success');
	unset($_GET['delete']);
} elseif (isset($_GET['ToggleUpdate'])) {
	$SQL = "UPDATE locationusers
			SET canupd='" . $_GET['ToggleUpdate'] . "'
			WHERE loccode='" . $SelectedLocation . "'
			AND userid='" . $SelectedUser . "'";

	$ErrMsg = _('The Location user record could not be deleted because');
	$Result = DB_query($SQL, $ErrMsg);
	echo prnMsg(_('User') . ' ' . $SelectedUser . ' ' . _('has had their authority to update') . ' ' . $SelectedLocation . ' ' . _('location removed'), 'success');
	unset($_GET['ToggleUpdate']);
}

if (!isset($SelectedLocation)) {

	/* It could still be the second time the page has been run and a record has been selected for modification - SelectedUser will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
	then none of the above are true. These will call the same page again and allow update/input or deletion of the records*/
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select Location') . '</label>
				<select name="SelectedLocation" class="form-control">';

	$Result = DB_query("SELECT loccode,
								locationname
						FROM locations");

	echo '<option value="">' . _('Not Yet Selected') . '</option>';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($SelectedLocation) and $MyRow['loccode'] == $SelectedLocation) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $MyRow['loccode'] . '">' . $MyRow['loccode'] . ' - ' . $MyRow['locationname'] . '</option>';

	} //end while loop

	echo '</select></div></div>';

	
	DB_free_result($Result);

	echo '<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" class="btn btn-success" name="Process" value="' . _('Submit') . '" />
			</div></div>
			</div>';

	echo '</form>';

}

//end of ifs and buts!
if (isset($_POST['process']) or isset($SelectedLocation)) {
	$SQLName = "SELECT locationname
			FROM locations
			WHERE loccode='" . $SelectedLocation . "'";
	$Result = DB_query($SQLName);
	$MyRow = DB_fetch_array($Result);
	$SelectedLocationName = $MyRow['locationname'];

	echo '

		<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<input type="hidden" name="SelectedLocation" value="' . $SelectedLocation . '" />';

	$SQL = "SELECT locationusers.userid,
					canview,
					canupd,
					www_users.realname
			FROM locationusers INNER JOIN www_users
			ON locationusers.userid=www_users.userid
			WHERE locationusers.loccode='" . $SelectedLocation . "'
			ORDER BY locationusers.userid ASC";

	$Result = DB_query($SQL);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Authorised users for Location') . ': ' . $SelectedLocationName . '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	
	echo '<thead>
	<tr>
			<th>' . _('User Code') . '</th>
			<th>' . _('User Name') . '</th>
			<th>' . _('View') . '</th>
			<th>' . _('Update') . '</th>
			<th colspan="2">' . _('Actions') . '</th>
		</tr></thead>';

	while ($MyRow = DB_fetch_array($Result)) {

		if ($MyRow['canupd'] == 1) {
			$ToggleText = '<td><a href="%s?SelectedUser=%s&amp;ToggleUpdate=0&amp;SelectedLocation=' . $SelectedLocation . '" onclick="return confirm(\'' . _('Are you sure you wish to remove Update for this user?') . '\');" class="btn btn-danger">' . _('Remove Update') . '</a></td>';
		} else {
			$ToggleText = '<td><a href="%s?SelectedUser=%s&amp;ToggleUpdate=1&amp;SelectedLocation=' . $SelectedLocation . '" onclick="return confirm(\'' . _('Are you sure you wish to add Update for this user?') . '\');" class="btn btn-success">' . _('Add Update') . '</a></td>';
		}

		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>' .
				$ToggleText . '
				<td><a href="%s?SelectedUser=%s&amp;delete=yes&amp;SelectedLocation=' . $SelectedLocation . '" onclick="return confirm(\'' . _('Are you sure you wish to un-authorise this user?') . '\');" class="btn btn-danger">' . _('Un-authorise') . '</a></td>
				</tr>',
				$MyRow['userid'],
				$MyRow['realname'],
				$MyRow['canview'],
				$MyRow['canupd'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'),
				$MyRow['userid'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'),
				$MyRow['userid']);
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div></div>';

	if (!isset($_GET['delete'])) {


		echo '<div class="row">'; //Main table

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select User') . '</label>
				<select name="SelectedUser" class="form-control">';

		$Result = DB_query("SELECT userid,
									realname
							FROM www_users
							WHERE NOT EXISTS (SELECT locationusers.userid
											FROM locationusers
											WHERE locationusers.loccode='" . $SelectedLocation . "'
												AND locationusers.userid=www_users.userid)");

		if (!isset($_POST['SelectedUser'])) {
			echo '<option selected="selected" value="">' . _('Not Yet Selected') . '</option>';
		}
		while ($MyRow = DB_fetch_array($Result)) {
			if (isset($_POST['SelectedUser']) and $MyRow['userid'] == $_POST['SelectedUser']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $MyRow['userid'] . '">' . $MyRow['userid'] . ' - ' . $MyRow['realname'] . '</option>';

		} //end while loop

		echo '</select>
					</div>
				</div>
			'; // close main table
		DB_free_result($Result);

		echo '<div class="col-xs-4">
<div class="form-group"> <br />
				<input type="submit" name="submit" class="btn btn-success" value="' . _('Submit') . '" /></div></div>
				<div class="col-xs-4">
<div class="form-group"><br />
				<input type="submit" name="Cancel" class="btn btn-default" value="' . _('Back to Select Location') . '" />
			</div></div>
			</div>
			
			</form>';

	} // end if user wish to delete
}

include('includes/footer.php');
?>
