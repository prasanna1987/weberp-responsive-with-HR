<?php


include('includes/session.php');
$Title = _('Customer Notes');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['Id'])){
	$Id = (int)$_GET['Id'];
} else if (isset($_POST['Id'])){
	$Id = (int)$_POST['Id'];
}
if (isset($_POST['DebtorNo'])){
	$DebtorNo = $_POST['DebtorNo'];
} elseif (isset($_GET['DebtorNo'])){
	$DebtorNo = $_GET['DebtorNo'];
}
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Notes for Customer') . ': <strong>' . $DebtorNo . '</strong></h1></a></div>';

echo '<div class="row"><div class="col-xs-4">
<a href="' . $RootPath . '/SelectCustomer.php?Select=' . $DebtorNo . '" class="btn btn-default">' . _('Back To Customer') . '</a></div>
	';
	if (isset($Id)) {
	echo '
	      <div class="col-xs-4">
			<a href="'.htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?DebtorNo='.$DebtorNo.'" class="btn btn-info">' . _('Review all notes for this Customer') . '</a></div>
		';
}
echo '</div><br />';

if ( isset($_POST['submit']) ) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;
	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if (!is_long((integer)$_POST['Priority'])) {
		$InputError = 1;
		echo prnMsg( _('The contact priority must be an integer.'), 'error');
	} elseif (mb_strlen($_POST['Note']) >200) {
		$InputError = 1;
		echo prnMsg( _('The contact\'s notes must be two hundred characters or less'), 'error');
	} elseif( trim($_POST['Note']) == '' ) {
		$InputError = 1;
		echo prnMsg( _('The contact\'s notes can not be empty'), 'error');
	}

	if (isset($Id) and $InputError !=1) {

		$sql = "UPDATE custnotes SET note='" . $_POST['Note'] . "',
									date='" . FormatDateForSQL($_POST['NoteDate']) . "',
									href='" . $_POST['Href'] . "',
									priority='" . $_POST['Priority'] . "'
				WHERE debtorno ='".$DebtorNo."'
				AND noteid='".$Id."'";
		$msg = _('Customer Notes') . ' ' . $DebtorNo  . ' ' . _('has been updated');
	} elseif ($InputError !=1) {

		$sql = "INSERT INTO custnotes (debtorno,
										href,
										note,
										date,
										priority)
				VALUES ('" . $DebtorNo. "',
						'" . $_POST['Href'] . "',
						'" . $_POST['Note'] . "',
						'" . FormatDateForSQL($_POST['NoteDate']) . "',
						'" . $_POST['Priority'] . "')";
		$msg = _('The contact notes record has been added');
	}

	if ($InputError !=1) {
		$result = DB_query($sql);
				//echo '<br />' . $sql;

		echo '<br />';
		echo prnMsg($msg, 'success');
		unset($Id);
		unset($_POST['Note']);
		unset($_POST['Noteid']);
		unset($_POST['NoteDate']);
		unset($_POST['Href']);
		unset($_POST['Priority']);
	}
} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'SalesOrders'

	$sql="DELETE FROM custnotes
			WHERE noteid='".$Id."'
			AND debtorno='".$DebtorNo."'";
	$result = DB_query($sql);

	echo '<br />';
	echo prnMsg( _('The contact note record has been deleted'), 'success');
	unset($Id);
	unset($_GET['delete']);
}

if (!isset($Id)) {
	$SQLname="SELECT * FROM debtorsmaster
				WHERE debtorno='".$DebtorNo."'";
	$Result = DB_query($SQLname);
	$row = DB_fetch_array($Result);


	$sql = "SELECT noteid,
					debtorno,
					href,
					note,
					date,
					priority
				FROM custnotes
				WHERE debtorno='".$DebtorNo."'
				ORDER BY date DESC";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<tr>
			<th>' . _('Date') . '</th>
			<th>' . _('Note') . '</th>
			<th>' . _('Reference') . '</th>
			<th>' . _('Priority') . '</th>
		</tr>';

	while ($myrow = DB_fetch_array($result)) {
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td><a href="%sId=%s&DebtorNo=%s" class="btn btn-info">' .  _('Edit').' </td>
				<td><a href="%sId=%s&DebtorNo=%s&delete=1" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this customer note?') . '\');">' .  _('Delete'). '</td>
				</tr>',
				ConvertSQLDate($myrow['date']),
				$myrow['note'],
				$myrow['href'],
				$myrow['priority'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['noteid'],
				$myrow['debtorno'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['noteid'],
				$myrow['debtorno']);

	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}



if (!isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?DebtorNo=' . $DebtorNo . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($Id)) {
		//editing an existing

		$sql = "SELECT noteid,
						debtorno,
						href,
						note,
						date,
						priority
					FROM custnotes
					WHERE noteid='".$Id."'
						AND debtorno='".$DebtorNo."'";

		$result = DB_query($sql);

		$myrow = DB_fetch_array($result);

		$_POST['Noteid'] = $myrow['noteid'];
		$_POST['Note']	= $myrow['note'];
		$_POST['Href']  = $myrow['href'];
		$_POST['NoteDate']  = $myrow['date'];
		$_POST['Priority']  = $myrow['priority'];
		$_POST['debtorno']  = $myrow['debtorno'];
		echo '<input type="hidden" name="Id" value="'. $Id .'" />';
		echo '<input type="hidden" name="Con_ID" value="' . $_POST['Noteid'] . '" />';
		echo '<input type="hidden" name="DebtorNo" value="' . $_POST['debtorno'] . '" />';
		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' .  _('Note ID').'</label>
				' . $_POST['Noteid'] . '</div>
			</div></div>';
	} else {
		
	}

	echo '<div class="row">
<div class="col-xs-3">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Contact Note'). '</label>';
	if (isset($_POST['Note'])) {
		echo '<textarea name="Note" class="form-control" required="required" rows="3" cols="32">' .$_POST['Note'] . '</textarea></div>
			</div>';
	} else {
		echo '<textarea name="Note" class="form-control" required="required" rows="3" cols="32"></textarea></div>
			</div>';
	}
	echo '<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label">' .  _('Reference') . '</label>';
	if (isset($_POST['Href'])) {
		echo '<input type="text" class="form-control" name="Href" value="'.$_POST['Href'].'" size="35" maxlength="100" /></div>
			</div>';
	} else {
		echo '<input type="text" class="form-control" name="Href" size="35" maxlength="100" /></div>
			</div>';
	}
	echo '<div class="col-xs-3">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Date')  . '</label>';
	if (isset($_POST['NoteDate'])) {
		echo '<input type="text" required name="NoteDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" value="'.ConvertSQLDate($_POST['NoteDate']).'" size="11" maxlength="10" /></div>
			</div>';
	} else {
		echo '<input type="text" required name="NoteDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" size="11" maxlength="10" /></div>
			</div>';
	}
	echo '<div class="col-xs-3">
<div class="form-group has-error"> <label class="col-md-12 control-label">' .  _('Priority'). '</label>';
	if (isset($_POST['Priority'])) {
		echo '<input type="text" class="form-control" required="required" name="Priority" class="number" value="' . $_POST['Priority']. '" size="1" maxlength="3" />
			</div></div></div>';
	} else {
		echo '<input type="text" class="form-control" required="required"  name="Priority" value="1"  size="1" maxlength="3"/></div>
			</div></div>';
	}
	echo '<div class="row">
			<div class="col-xs-3">
				<input type="submit" name="submit" value="'._('Enter Information').'" class="btn btn-info" />
			</div>
			</div>
		<br />
		</form>';

} //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>