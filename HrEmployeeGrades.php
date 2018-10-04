<?php

/* $Id: HrEmploymentGrades.php 7772 2018-04-07 09:30:06Z bagenda $ */

include('includes/session.php');

$Title = _('Employee Grades');

include('includes/header.php');

if (isset($_POST['SelectedGrading'])){
	$SelectedGrading = mb_strtoupper($_POST['SelectedGrading']);
} elseif (isset($_GET['SelectedGrading'])){
	$SelectedGrading = mb_strtoupper($_GET['SelectedGrading']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Employee Grades ') . '</h1></a></div>';


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (mb_strlen($_POST['GradingName']) >100) {
		$InputError = 1;
		prnMsg(_('The Grade  Name description must be 100 characters or less long'),'error');
		$Errors[$i] = 'GradingName';
		$i++;
	}

	if (mb_strlen($_POST['GradingName'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Grading Name  must contain at least one character'),'error');
		$Errors[$i] = 'GradingName';
		$i++;
	}

	if (mb_strlen($_POST['GradingPriority'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Grading Priority  must contain at least one character'),'error');
		$Errors[$i] = 'GradingPriority';
		$i++;
	}

	$checksql = "SELECT count(*)
		     FROM hremployeegradings
		     WHERE grading_name = '" . $_POST['GradingName'] . "'";
	$checkresult=DB_query($checksql);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0 and !isset($SelectedGrading)) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You already have a Grading Name').' '.$_POST['GradingName'],'error');
		$Errors[$i] = 'GradingName';
		$i++;
	}

	if (isset($SelectedGrading) AND $InputError !=1) {

		$sql = "UPDATE hremployeegradings
			SET grading_name = '" . $_POST['GradingName'] . "',
priority= '" . $_POST['GradingPriority'] . "',
grading_description= '" . $_POST['GradingDescription'] . "',
grading_status= '" . $_POST['Status'] . "'

			WHERE employee_grading_id = '" .$SelectedGrading."'";

		$msg = _('The Grading Name') . ' ' . $_POST['GradingName'] . ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the Name is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM hremployeegradings
			     WHERE grading_name = '" . $_POST['GradingName'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Grading Name') . ' ' . $_POST['GradingName'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO hremployeegradings
						(grading_name,
						priority,grading_description,grading_status)
					VALUES ('" . $_POST['GradingName'] . "',
'" . $_POST['GradingPriority'] . "',
'" . $_POST['GradingDescription'] . "',
'" . $_POST['Status'] . "'
)";


			$msg = _('Grading Name') . ' ' . $_POST["GradingName"] .  ' ' . _('has been created');
			$checkSql = "SELECT count(employee_grading_id)
			     FROM hremployeegradings";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql);


	// Fetch the default price list.
		$DefaultGradingName = $_SESSION['DefaultGradingName'];

	// Does it exist
		$checkSql = "SELECT count(*)
			     FROM hremployeegradings
			     WHERE employee_grading_id = '" . $DefaultGradingName . "'"
					 ;
		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

	// If it doesnt then update config with newly created one.
		if ($checkrow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='" . $_POST['employee_grading_id'] . "'
					WHERE confname='DefaultGradingName'";
			$result = DB_query($sql);
			$_SESSION['DefaultGradingName'] = $_POST['employee_grading_id'];
		}
		echo '<br />';
		prnMsg($msg,'success');

		unset($SelectedGrading);
		unset($_POST['employee_grading_id']);
		unset($_POST['GradingName']);
		unset($_POST['GradingPriority']);
		unset($_POST['GradingDescription']);
		unset($_POST['Status']);
	}

} elseif ( isset($_GET['delete']) ) {

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'employees'


	$sql= "SELECT COUNT(*)
	       FROM hremployees
	       WHERE employee_grade_id='".$SelectedGrading."'";

	$ErrMsg = _('The number of employees using this Grade Name could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg(_('Cannot delete this Grade because Employee   have been created using this Grade') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('Employees using this Grade'),'error');

	} else  {
			$result = DB_query("SELECT grading_name FROM hremployeegradings WHERE employee_grading_id='".$SelectedGrading."'");
			if (DB_Num_Rows($result)>0){
				$GradingRow = DB_fetch_array($result);
				$GradingName = $GradingRow['grading_name '];

				$sql="DELETE FROM hremployeegradings WHERE employee_grading_id='".$SelectedGrading."'";
				$ErrMsg = _('The Grade record could not be deleted because');
				$result = DB_query($sql,$ErrMsg);
				echo '<br />';
				prnMsg(_('Grade Name') . ' ' . $GradingName  . ' ' . _('has been deleted') ,'success');
			}
			unset ($SelectedGrading);
			unset($_GET['delete']);

		}
	} //end if Grade used in Employees  set up


if (!isset($SelectedGrading)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedGrading will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of employee grades will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT employee_grading_id, grading_name,priority,grading_description,grading_status FROM hremployeegradings";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<thead><tr>
	<th class="ascending">' . _('Grading id') . '</th>
 <th class="ascending">' . _('Grading Name') . '</th>
 <th class="ascending">' . _('Priority') . '</th>
 <th class="ascending">' . _('Description') . '</th>
<th class="ascending">' . _('Status') . '</th>
<th colspan="2">' . _('Actions') . '</th>
		</tr></thead>';

		$k=0; //row colour counter
		while ($myrow = DB_fetch_array($result)) {
			if ($k==1){
				echo '<tr class="EvenTableRows">';
				$k=0;
			} else {
				echo '<tr class="OddTableRows">';
				$k++;
			}

printf('<td>%s</td>
		<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
		<td><a href="%sSelectedGrading=%s" class="btn btn-info">' . _('Edit') . '</a></td>
		<td><a href="%sSelectedGrading=%s&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this Grading Name?') . '");\'>' . _('Delete') . '</a></td>
		</tr>',
		$myrow['employee_grading_id'],
		$myrow['grading_name'],
		$myrow['priority'],
		$myrow['grading_description'],
		($myrow['grading_status'] == 1) ? 'Active' : 'Inactive',
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['employee_grading_id'],
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['employee_grading_id']);
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}

//end of ifs and buts!
if (isset($SelectedGrading)) {

	echo '<div class="row" align="center"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Grading Defined') . '</a></div><br />';
}
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		';

	// The user wish to EDIT an existing name
	if ( isset($SelectedGrading) AND $SelectedGrading!='' ) {

		$sql = "SELECT employee_grading_id,
			       grading_name,priority, grading_description,grading_status
		        FROM hremployeegradings
		        WHERE employee_grading_id='".$SelectedGrading."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['employee_grading_id'] = $myrow['employee_grading_id'];
		$_POST['GradingName']  = $myrow['grading_name'];
		$_POST['GradingPriority']  = $myrow['priority'];
		$_POST['GradingDescription']  = $myrow['grading_description'];
$_POST['Status']  = $myrow['grading_status'];
		echo '<input type="hidden" name="SelectedGrading" value="' . $SelectedGrading . '" />
			<input type="hidden" name="employee_grading_id" value="' . $_POST['employee_grading_id'] . '" />
			';

		// We dont allow the user to change an existing Name code

		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('GRADING ID') . '</label> ' . $_POST['employee_grading_id'] . '</div>
			</div></div>';
	} else 	{
		// This is a new Name so the user may volunteer a Name code
		echo '';
	}

	if (!isset($_POST['GradingName'])) {
		$_POST['GradingName']='';
	}
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Grading Name') . '</label>
			<input type="text" name="GradingName" class="form-control"  required="required" title="' . _('The Grading Name is required') . '" value="' . $_POST['GradingName'] . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Priority ') . '</label>
				<input type="text" name="GradingPriority" class="form-control"  required="required" title="' . _('The  Priority is required') . '" value="' . $_POST['GradingPriority'] . '" /></div>
			</div>

			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Description ') . '</label>
				
<textarea name="GradingDescription" class="form-control"  title="' . _('The  Description is required') . '">' . $_POST['GradingDescription'] . '</textarea>
					</div>
				</div>
				</div>

			<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Status') .
			  '</label><div class="checkbox">
			 <input type="radio"';
				if (! isset($SelectedGrading)) {
			   echo ' checked';}
			  if (isset($_POST['Status']) and $_POST['Status']==1) {
			    echo ' checked';}
			echo'
			   name="Status" value="1"> Active
</div>
		<div class="checkbox">	  <input';
			  if (isset($_POST['Status']) and $_POST['Status']==0) {
			    echo ' checked';
			  }
			echo'

			  type="radio" name="Status" value="0"> Inactive
			  </div></div>
</div>

		<div class="col-xs-6">
<div class="form-group"><br />
			<input type="submit" class="btn btn-success" name="submit" value="' . _('Accept') . '" />
		</div>
	</div>
	</div><br />
	</form>';

} // end if user wish to delete


include('includes/footer.php');
?>
