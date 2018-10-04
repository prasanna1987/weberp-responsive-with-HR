<?php


include('includes/session.php');
$Title = _('MRP Demand Types');
include('includes/header.php');

//SelectedDT is the Selected MRPDemandType
if (isset($_POST['SelectedDT'])){
	$SelectedDT = trim(mb_strtoupper($_POST['SelectedDT']));
} elseif (isset($_GET['SelectedDT'])){
	$SelectedDT = trim(mb_strtoupper($_GET['SelectedDT']));
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if (trim(mb_strtoupper($_POST['MRPDemandType']) == 'WO') or
	   trim(mb_strtoupper($_POST['MRPDemandType']) == 'SO')) {
		$InputError = 1;
		echo prnMsg(_('The Demand Type is reserved for the system'),'error');
	}

	if (mb_strlen($_POST['MRPDemandType']) < 1) {
		$InputError = 1;
		echo prnMsg(_('The Demand Type code must be at least 1 character long'),'error');
	}
	if (mb_strlen($_POST['Description'])<3) {
		$InputError = 1;
		echo prnMsg(_('The Demand Type description must be at least 3 characters long'),'error');
	}

	if (isset($SelectedDT) AND $InputError !=1) {

		/*SelectedDT could also exist if submit had not been clicked this code
		would not run in this case cos submit is false of course  see the
		delete code below*/

		$sql = "UPDATE mrpdemandtypes SET description = '" . $_POST['Description'] . "'
				WHERE mrpdemandtype = '" . $SelectedDT . "'";
		$msg = _('The demand type record has been updated');
	} elseif ($InputError !=1) {

	//Selected demand type is null cos no item selected on first time round so must be adding a
	//record must be submitting new entries in the new work centre form

		$sql = "INSERT INTO mrpdemandtypes (mrpdemandtype,
						description)
					VALUES ('" . trim(mb_strtoupper($_POST['MRPDemandType'])) . "',
						'" . $_POST['Description'] . "'
						)";
		$msg = _('The new demand type has been added to the database');
	}
	//run the SQL from either of the above possibilites

	if ($InputError !=1){
		$result = DB_query($sql,_('The update/addition of the demand type failed because'));
		echo prnMsg($msg,'success');
		echo '<br />';
		unset ($_POST['Description']);
		unset ($_POST['MRPDemandType']);
		unset ($SelectedDT);
	}

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'MRPDemands'

	$sql= "SELECT COUNT(*) FROM mrpdemands
	         WHERE mrpdemands.mrpdemandtype='" . $SelectedDT . "'
	         GROUP BY mrpdemandtype";
	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		echo prnMsg(_('Cannot delete this demand type because MRP Demand records exist for this type') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' ._('MRP Demands referring to this type'),'warn');
    } else {
			$sql="DELETE FROM mrpdemandtypes WHERE mrpdemandtype='" . $SelectedDT . "'";
			$result = DB_query($sql);
			echo prnMsg(_('The selected demand type record has been deleted'),'success');
			echo '<br />';
	} // end of MRPDemands test
}

if (!isset($SelectedDT) or isset($_GET['delete'])) {

//It could still be the second time the page has been run and a record has been selected
//for modification SelectedDT will exist because it was sent with the new call. If its
//the first time the page has been displayed with no parameters
//then none of the above are true and the list of demand types will be displayed with
//links to delete or edit each. These will call the same page again and allow update/input
//or deletion of the records

	$sql = "SELECT mrpdemandtype,
					description
			FROM mrpdemandtypes";

	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead> 
			<tr><th>' . _('Demand Type') . '</th>
				<th>' . _('Description') . '</th>
				<th colspan="2">' . _('Actions') . '</th>
			</tr></thead>';

	while ($myrow = DB_fetch_row($result)) {

		printf('<tr><td>%s</td>
				<td>%s</td>
				<td><a href="%sSelectedDT=%s" class="btn btn-success">' . _('Edit') . '</a></td>
				<td><a href="%sSelectedDT=%s&amp;delete=yes" class="btn btn-danger">' . _('Delete')  . '</a></td>
				</tr>',
				$myrow[0],
				$myrow[1],
				htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
				$myrow[0], htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
				$myrow[0]);
	}

	//END WHILE LIST LOOP
	echo '</table></div></div></div>';
}

//end of ifs and buts!

if (isset($SelectedDT) and !isset($_GET['delete'])) {
	echo '<div class="row">
<div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show all Demand Types') . '</a></div></div><br />';
}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .'">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($SelectedDT) and !isset($_GET['delete'])) {
	//editing an existing demand type

	$sql = "SELECT mrpdemandtype,
	        description
		FROM mrpdemandtypes
		WHERE mrpdemandtype='" . $SelectedDT . "'";

	$result = DB_query($sql);
	$myrow = DB_fetch_array($result);

	$_POST['MRPDemandType'] = $myrow['mrpdemandtype'];
	$_POST['Description'] = $myrow['description'];

	echo '<input type="hidden" name="SelectedDT" value="' . $SelectedDT . '" />';
	echo '<input type="hidden" name="MRPDemandType" value="' . $_POST['MRPDemandType'] . '" />';
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' ._('Demand Type') . '</label>
				' . $_POST['MRPDemandType'] . '</div>
			</div>';

} else { //end of if $SelectedDT only do the else when a new record is being entered
	if (!isset($_POST['MRPDemandType'])) {
		$_POST['MRPDemandType'] = '';
	}
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Demand Type') . '</label>
				<input type="text" class="form-control" name="MRPDemandType" size="6" maxlength="5" value="' . $_POST['MRPDemandType'] . '" /></div>
			</div>' ;
}

if (!isset($_POST['Description'])) {
	$_POST['Description'] = '';
}

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Description') . '</label>
		<input type="text" class="form-control" name="Description" size="31" maxlength="30" value="' . $_POST['Description'] . '" /></div>
	</div>
	</div>
	
	<div class="row">
	<div class="col-xs-4">
		<input type="submit" class="btn btn-success" name="submit" value="' . _('Enter Information') . '" />
	</div>
    </div><br />

	</form>';

include('includes/footer.php');
?>