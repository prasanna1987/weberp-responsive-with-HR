<?php

include('includes/session.php');
$Title = _('Customer Type (Group) Notes');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['Id'])){
	$Id = (int)$_GET['Id'];
} else if (isset($_POST['Id'])){
	$Id = (int)$_POST['Id'];
}
if (isset($_POST['DebtorType'])){
	$DebtorType = $_POST['DebtorType'];
} elseif (isset($_GET['DebtorType'])){
	$DebtorType = $_GET['DebtorType'];
}


if (isset($_POST['submit']) ) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;
	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if (!is_long((integer)$_POST['Priority'])) {
		$InputError = 1;
		echo prnMsg( _('The Contact priority must be an integer.'), 'error');
	} elseif (mb_strlen($_POST['Note']) >200) {
		$InputError = 1;
		echo prnMsg( _('The contacts notes must be two hundred characters or less'), 'error');
	} elseif( trim($_POST['Note']) == '' ) {
		$InputError = 1;
		echo prnMsg( _('The contacts notes can not be empty'), 'error');
	}

	if ($Id and $InputError !=1) {

		$sql = "UPDATE debtortypenotes SET note='" . $_POST['Note'] . "',
											date='" . FormatDateForSQL($_POST['NoteDate']) . "',
											href='" . $_POST['Href'] . "',
											priority='" . $_POST['Priority'] . "'
										WHERE typeid ='".$DebtorType."'
										AND noteid='".$Id."'";
		$msg = _('Customer Group Notes') . ' ' . $DebtorType  . ' ' . _('has been updated');
	} elseif ($InputError !=1) {

		$sql = "INSERT INTO debtortypenotes (typeid,
											href,
											note,
											date,
											priority)
									VALUES ('" . $DebtorType. "',
											'" . $_POST['Href'] . "',
											'" . $_POST['Note'] . "',
											'" . FormatDateForSQL($_POST['NoteDate']) . "',
											'" . $_POST['Priority'] . "')";
		$msg = _('The contact group notes record has been added');
	}

	if ($InputError !=1) {
		$result = DB_query($sql);

		
		echo prnMsg($msg, 'success');
		unset($Id);
		unset($_POST['Note']);
		unset($_POST['NoteID']);
	}
} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'SalesOrders'

	$sql="DELETE FROM debtortypenotes
			WHERE noteid='".$Id."'
			AND typeid='".$DebtorType."'";
	$result = DB_query($sql);

	echo '<br />';
	echo prnMsg( _('The contact group note record has been deleted'), 'success');
	unset($Id);
	unset($_GET['delete']);

}

if (!isset($Id)) {
	$SQLname="SELECT typename from debtortype where typeid='".$DebtorType."'";
	$result = DB_query($SQLname);
	$myrow = DB_fetch_array($result);
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>'  . _('Notes for Customer Type').': ' .$myrow['typename'] . '</h1></a></div>';
	
echo '<div class="row">
	      <div class="col-xs-4"><a href="' . $RootPath . '/SelectCustomer.php?DebtorType='.$DebtorType.'" class="btn btn-default">' . _('Back To Customer') . '</a></div>';

if (isset($Id)) {
	echo '<div class="row">
	      <div class="col-xs-4">
			<a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?DebtorType=' . $DebtorType . '" class="btn btn-info">' . _('Review all notes for this Customer Type')  . '</a></div>
		';
}
echo '</div><br />';	

	$sql = "SELECT noteid,
					typeid,
					href,
					note,
					date,
					priority
				FROM debtortypenotes
				WHERE typeid='".$DebtorType."'
				ORDER BY date DESC";
	$result = DB_query($sql);
			//echo '<br />' . $sql;

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<thead><tr>
			<th>' . _('Date') . '</th>
			<th>' . _('Note') . '</th>
			<th>' . _('Reference') . '</th>
			<th>' . _('Priority') . '</th>
		</tr></thead>';

	while ($myrow = DB_fetch_array($result)) {
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td><a href="%sId=%s&amp;DebtorType=%s" class="btn btn-info">' .  _('Edit') . '</a></td>
				<td><a href="%sId=%s&amp;DebtorType=%s&amp;delete=1" class="btn btn-danger">' .  _('Delete') . '</a></td>
				</tr>',
				$myrow['date'],
				$myrow['note'],
				$myrow['href'],
				$myrow['priority'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['noteid'],
				$myrow['typeid'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['noteid'],
				$myrow['typeid']);

	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}


if (!isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?DebtorType='.$DebtorType.'">';

	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($Id)) {
		//editing an existing

		$sql = "SELECT noteid,
					typeid,
					href,
					note,
					date,
					priority
				FROM debtortypenotes
				WHERE noteid=".$Id."
					AND typeid='".$DebtorType."'";

		$result = DB_query($sql);
				//echo '<br />' . $sql;

		$myrow = DB_fetch_array($result);

		$_POST['NoteID'] = $myrow['noteid'];
		$_POST['Note']	= $myrow['note'];
		$_POST['Href']  = $myrow['href'];
		$_POST['NoteDate']  = $myrow['date'];
		$_POST['Priority']  = $myrow['priority'];
		$_POST['typeid']  = $myrow['typeid'];
		echo '<input type="hidden" name="Id" value="'. $Id .'" />';
		echo '<input type="hidden" name="Con_ID" value="' . $_POST['NoteID'] . '" />';
		echo '<input type="hidden" name="DebtorType" value="' . $_POST['typeid'] . '" />';
		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' .  _('Note ID').'</label>
				' . $_POST['Noteid'] . '</div>
			</div></div>';
	} else {
		
		$_POST['NoteID'] = '';
		$_POST['Note']  = '';
		$_POST['Href']  = '';
		$_POST['NoteDate']  = Date($_SESSION['DefaultDateFormat']);
		$_POST['Priority']  = '1';
		$_POST['typeid']  = '';
	}

	echo '<div class="row">
<div class="col-xs-3">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Contact Group Note').'</label>
			<textarea name="Note" class="form-control" autofocus="autofocus" required="required" rows="3" cols="32">' .  $_POST['Note'] . '</textarea>
			</div></div>
		
<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label">' .  _('Reference').'</label>
			<input type="text" class="form-control" name="Href" value="'. $_POST['Href'].'" size="35" maxlength="100" />
			</div></div>
			
		
<div class="col-xs-3">
<div class="form-group has-error"> <label class="col-md-12 control-label">' .  _('Date').'</label>
			<input type="text" required="required" name="NoteDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" value="'. $_POST['NoteDate']. '" size="11" maxlength="10" />
			</div></div>
			
		
<div class="col-xs-3">
<div class="form-group has-error"> <label class="col-md-12 control-label">' .  _('Priority').'</label>
			<input type="text" class="form-control" name="Priority" value="'. $_POST['Priority'] .'" size="1" maxlength="3" />
			</div></div>
			
		</div>
		
		<div class="row" align="center">

			<input type="submit" name="submit" class="btn btn-success" value="'. _('Submit').'" />
		
        </div><br />

		</form>';

} //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>