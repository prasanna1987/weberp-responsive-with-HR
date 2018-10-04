<?php
/* Defines the various centres of work within a manufacturing company. Also the overhead and labour rates applicable to the work centre and its standard capacity */

include('includes/session.php');
$Title = _('Work Centres');
$ViewTopic = 'Manufacturing';
$BookMark = 'WorkCentres';
include('includes/header.php');

if (isset($_POST['SelectedWC'])){
	$SelectedWC =$_POST['SelectedWC'];
} elseif (isset($_GET['SelectedWC'])){
	$SelectedWC =$_GET['SelectedWC'];
}

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if (mb_strlen($_POST['Code']) < 2) {
		$InputError = 1;
		echo prnMsg(_('The Work Centre code must be at least 2 characters long'),'error');
	}
	if (mb_strlen($_POST['Description'])<3) {
		$InputError = 1;
		echo prnMsg(_('The Work Centre description must be at least 3 characters long'),'error');
	}
	if (mb_strstr($_POST['Code'],' ') OR ContainsIllegalCharacters($_POST['Code']) ) {
		$InputError = 1;
		echo prnMsg(_('The work centre code cannot contain any of the following characters') . " - ' &amp; + \" \\ " . _('or a space'),'error');
	}

	if (isset($SelectedWC) AND $InputError !=1) {

		/*SelectedWC could also exist if submit had not been clicked this code
		would not run in this case cos submit is false of course  see the
		delete code below*/

		$sql = "UPDATE workcentres SET location = '" . $_POST['Location'] . "',
						description = '" . $_POST['Description'] . "',
						overheadrecoveryact ='" . $_POST['OverheadRecoveryAct'] . "',
						overheadperhour = '" . $_POST['OverheadPerHour'] . "'
				WHERE code = '" . $SelectedWC . "'";
		$msg = _('The work centre record has been updated');
	} elseif ($InputError !=1) {

	/*Selected work centre is null cos no item selected on first time round so must be adding a	record must be submitting new entries in the new work centre form */

		$sql = "INSERT INTO workcentres (code,
										location,
										description,
										overheadrecoveryact,
										overheadperhour)
					VALUES ('" . $_POST['Code'] . "',
						'" . $_POST['Location'] . "',
						'" . $_POST['Description'] . "',
						'" . $_POST['OverheadRecoveryAct'] . "',
						'" . $_POST['OverheadPerHour'] . "'
						)";
		$msg = _('The new work centre has been added to the database');
	}
	//run the SQL from either of the above possibilites

	if ($InputError !=1){
		$result = DB_query($sql,_('The update/addition of the work centre failed because'));
		echo prnMsg($msg,'success');
		unset ($_POST['Location']);
		unset ($_POST['Description']);
		unset ($_POST['Code']);
		unset ($_POST['OverheadRecoveryAct']);
		unset ($_POST['OverheadPerHour']);
		unset ($SelectedWC);
	}

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'BOM'

	$sql= "SELECT COUNT(*) FROM bom WHERE bom.workcentreadded='" . $SelectedWC . "'";
	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		echo prnMsg(_('Cannot delete this work centre because bills of material have been created requiring components to be added at this work center') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' ._('BOM items referring to this work centre code'),'warn');
	}  else {
		$sql= "SELECT COUNT(*) FROM contractbom WHERE contractbom.workcentreadded='" . $SelectedWC . "'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			echo prnMsg(_('Cannot delete this work centre because contract bills of material have been created having components added at this work center') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('Contract BOM items referring to this work centre code'),'warn');
		} else {
			$sql="DELETE FROM workcentres WHERE code='" . $SelectedWC . "'";
			$result = DB_query($sql);
			echo prnMsg(_('The selected work centre record has been deleted'),'succes');
		} // end of Contract BOM test
	} // end of BOM test
}

if (!isset($SelectedWC)) {

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedWC will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of work centres will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '
		</h1></a></div>';

	$sql = "SELECT workcentres.code,
				workcentres.description,
				locations.locationname,
				workcentres.overheadrecoveryact,
				workcentres.overheadperhour
			FROM workcentres,
				locations
			INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			WHERE workcentres.location = locations.loccode";

	$result = DB_query($sql);
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
				<th>', _('WC Code'), '</th>
				<th>', _('Description'), '</th>
				<th>', _('Location'), '</th>
				<th>', _('Overhead GL Account'), '</th>
				<th>', _('Overhead Per Hour'), '</th>
				<th colspan="2">', _('Actions'), '</th>
			</tr>
		</thead>
		<tbody>';

	while ($myrow = DB_fetch_array($result)) {

		printf('<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td><a href="%s&amp;SelectedWC=%s" class="btn btn-info">' . _('Edit') . '</a></td>
					<td><a href="%s&amp;SelectedWC=%s&amp;delete=yes" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this work centre?') . '\');">' . _('Delete')  . '</a></td>
				</tr>',
				$myrow['code'],
				$myrow['description'],
				$myrow['locationname'],
				$myrow['overheadrecoveryact'],
				$myrow['overheadperhour'],
				htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
				$myrow['code'],
				htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
				$myrow['code']);
	}

	//END WHILE LIST LOOP
	echo '</tbody></table></div></div></div>';
}

//end of ifs and buts!

if (isset($SelectedWC)) {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ',// Icon title.
		$Title, '</h1></a></div>';// Page title.
	echo '<div class="row">
<div class="col-xs-4">
<a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Back to Work Centres') . '</a></div></div><br />';
}

echo '<br />
	<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($SelectedWC)) {
	//editing an existing work centre

	$sql = "SELECT code,
					location,
					description,
					overheadrecoveryact,
					overheadperhour
			FROM workcentres
			INNER JOIN locationusers ON locationusers.loccode=workcentres.location AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
			WHERE code='" . $SelectedWC . "'";

	$result = DB_query($sql);
	$myrow = DB_fetch_array($result);

	$_POST['Code'] = $myrow['code'];
	$_POST['Location'] = $myrow['location'];
	$_POST['Description'] = $myrow['description'];
	$_POST['OverheadRecoveryAct']  = $myrow['overheadrecoveryact'];
	$_POST['OverheadPerHour']  = $myrow['overheadperhour'];

	echo '<input type="hidden" name="SelectedWC" value="' . $SelectedWC . '" />
		<input type="hidden" name="Code" value="' . $_POST['Code'] . '" />
		<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' ._('Work Centre Code') . '</label>
				' . $_POST['Code'] . '</div>
			</div>';

} else { //end of if $SelectedWC only do the else when a new record is being entered
	if (!isset($_POST['Code'])) {
		$_POST['Code'] = '';
	}
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Work Centre Code') . '</label>
				<input type="text" name="Code" pattern="[^&+-]{2,}" required="required" autofocus="autofocus" title="'._('The code should be at least 2 characters and no illegal characters allowed').'"  size="6" maxlength="5" value="' . $_POST['Code'] . '" placeholder="'._('More than 2 legal characters').'" class="form-control" /></div>
			</div>';
}

$SQL = "SELECT locationname,
				locations.loccode
		FROM locations
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
$result = DB_query($SQL);

if (!isset($_POST['Description'])) {
	$_POST['Description'] = '';
}
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Work Centre Description') . '</label>
	<input type="text" pattern="[^&+-]{3,}" required="required" title="'._('The Work Center should be more than 3 characters and no illegal characters allowed').'" class="form-control" name="Description" ' . (isset($SelectedWC)? 'autofocus="autofocus"': '') . ' size="21" maxlength="20" value="' . $_POST['Description'] . '" placeholder="'._('More than 3 legal characters').'" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Location') . '</label>
		<select name="Location" class="form-control">';

while ($myrow = DB_fetch_array($result)) {
	if (isset($_POST['Location']) and $myrow['loccode']==$_POST['Location']) {
		echo '<option selected="selected" value="';
	} else {
		echo '<option value="';
	}
	echo $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';

} //end while loop

DB_free_result($result);


echo '</select></div>
	</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Overhead Recovery GL Account') . '</label>
		<select name="OverheadRecoveryAct" class="form-control">';

//SQL to poulate account selection boxes
$SQL = "SELECT accountcode,
				accountname
		FROM chartmaster INNER JOIN accountgroups
			ON chartmaster.group_=accountgroups.groupname
		WHERE accountgroups.pandl!=0
		ORDER BY accountcode";

$result = DB_query($SQL);

while ($myrow = DB_fetch_array($result)) {
	if (isset($_POST['OverheadRecoveryAct']) and $myrow['accountcode']==$_POST['OverheadRecoveryAct']) {
		echo '<option selected="selected" value="';
	} else {
		echo '<option value="';
	}
	echo $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';

} //end while loop
DB_free_result($result);

if (!isset($_POST['OverheadPerHour'])) {
	$_POST['OverheadPerHour']=0;
}

echo '</select></div></div>';
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Overhead Per Hour') . '</label>
		<input type="text" class="form-control" name="OverheadPerHour" size="6" title="'._('The input must be numeric').'" maxlength="6" value="'.$_POST['OverheadPerHour'].'" />';

echo '</div>
	</div>
	
	<div class="col-xs-4">
<div class="form-group"><br />
		<input type="submit" class="btn btn-success" name="submit" value="' . _('Enter Information') . '" />
	</div>
	</div>
	</div>
      </form>';
include('includes/footer.php');
?>
