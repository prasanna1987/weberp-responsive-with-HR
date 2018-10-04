<?php

/* $Id: HrEmploymentCategories.php 7772 2018-04-07 09:30:06Z bagenda $ */

include('includes/session.php');

$Title = _('Employment Job Positions');

include('includes/header.php');

// BEGIN: Employee Category array.
$EmployeeCategoryName = array();
$Query = "SELECT employee_category_id, category_name FROM hremployeecategories WHERE status=1 ORDER BY category_name";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$EmployeeCategoryName[$Row['employee_category_id']] = $Row['category_name'];
}


if (isset($_POST['SelectedPosition'])){
	$SelectedPosition = mb_strtoupper($_POST['SelectedPosition']);
} elseif (isset($_GET['SelectedPosition'])){
	$SelectedPosition = mb_strtoupper($_GET['SelectedPosition']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Employment Job Positions ') . '</h1></a></div>';


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (mb_strlen($_POST['PositionName']) >100) {
		$InputError = 1;
		prnMsg(_('The Position  Name description must be 100 characters or less long'),'error');
		$Errors[$i] = 'PositionName';
		$i++;
	}

	if (mb_strlen($_POST['PositionName'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Position Name description must contain at least one character'),'error');
		$Errors[$i] = 'PositionName';
		$i++;
	}

	if (mb_strlen($_POST['CategoryName'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You Dont Have Employee Category created '),'error');
		$Errors[$i] = 'CategoryName';
		$i++;
	}


	$checksql = "SELECT count(*)
		     FROM hremployeepositions
		     WHERE position_name = '" . $_POST['PositionName'] . "'";
	$checkresult=DB_query($checksql);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0 and !isset($SelectedPosition)) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You already have a Category Name').' '.$_POST['CategoryName'],'error');
		$Errors[$i] = 'CateryName';
		$i++;
	}

	if (isset($SelectedPosition) AND $InputError !=1) {

		$sql = "UPDATE hremployeepositions
			SET position_name = '" . $_POST['PositionName'] . "',
employee_category_id= '" . $_POST['CategoryName'] . "',
position_status= '" . $_POST['Status'] . "'

			WHERE employee_position_id = '" .$SelectedPosition."'";

		$msg = _('The Position Name') . ' ' . $_POST['PositionName']. ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the Name is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM hremployeepositions
			     WHERE position_name = '" . $_POST['PositionName'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Position Name') . ' ' . $_POST['employee_category_id'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit


			$sql = "INSERT INTO hremployeepositions
						(position_name,
						employee_category_id,position_status)
					VALUES ('" . $_POST['PositionName'] . "',
'" . $_POST['CategoryName'] . "',
'" . $_POST['Status'] . "'
)";


			$msg = _('Position Name') . ' ' . $_POST["PositionName"] .  ' ' . _('has been created');
			$checkSql = "SELECT count(employee_position_id)
			     FROM hremployeepositions";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql);


	// Fetch the default price list.
		$DefaultPositionName = $_SESSION['$DefaultPositionName'];

	// Does it exist
		$checkSql = "SELECT count(*)
			     FROM hremployeepositions
			     WHERE employee_position_id = '" . $DefaultPositionName . "'"
					 ;
		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

	// If it doesnt then update config with newly created one.
		if ($checkrow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='" . $_POST['employee_position_id'] . "'
					WHERE confname='DefaultPositionName'";
			$result = DB_query($sql);
			$_SESSION['$DefaultPositionName'] = $_POST['employee_position_id'];
		}
		echo '<br />';
		prnMsg($msg,'success');

		unset($SelectedPosition);
		unset($_POST['employee_position_id']);
		unset($_POST['PositionName']);
		unset($_POST['CategoryName']);
		unset($_POST['Status']);
	}

} elseif ( isset($_GET['delete']) ) {

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'hremployees'
	// Prevent delete if employee_position exist in Employee table

	$sql= "SELECT COUNT(*)
	       FROM hremployees
	       WHERE employee_position='".$SelectedPosition."'";

	$ErrMsg = _('The number of employees using this Employee Position could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg(_('Cannot delete this Position because Employees have been created using this Position') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('transactions using this type'),'error');

	} else {
		$result = DB_query("SELECT position_name FROM hremployeepositions WHERE employee_position_id='".$SelectedPosition."'");
		if (DB_Num_Rows($result)>0){
			$PositionRow = DB_fetch_array($result);
			$PositionName = $PositionRow['position_name'];

			$sql="DELETE FROM hremployeepositions WHERE employee_position_id='".$SelectedPosition."'";
			$ErrMsg = _('The Position record could not be deleted because');
			$result = DB_query($sql,$ErrMsg);
			echo '<br />';
			prnMsg(_('Employee Position') . ' ' . $PositionName  . ' ' . _('has been deleted') ,'success');
		}
		unset ($SelectedPosition);
		unset($_GET['delete']);




	} //end if Position used in employees  set up
}

if (!isset($SelectedPosition)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedPosition will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of sales types will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT employee_position_id, position_name,employee_category_id,position_status FROM hremployeepositions";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<thead><tr>
	<th class="ascending">' . _('Position id') . '</th>
 <th class="ascending">' . _('Position Name') . '</th>
 <th class="ascending">' . _('Category Name') . '</th>
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
		$k=1;
	}

printf('<td>%s</td>
		<td>%s</td>
<td>%s</td>
<td>%s</td>
		<td><a href="%sSelectedPosition=%s" class="btn btn-info">' . _('Edit') . '</a></td>
		<td><a href="%sSelectedPosition=%s&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this Position Name?') . '");\'>' . _('Delete') . '</a></td>
		</tr>',
		$myrow['employee_position_id'],
		$myrow['position_name'],
		$EmployeeCategoryName[$myrow['employee_category_id']],
		($myrow['position_status'] == 1) ? 'Active' : 'Inactive',
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['employee_position_id'],
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['employee_position_id']);
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}

//end of ifs and buts!
if (isset($SelectedPosition)) {

	echo '<div class="row" align="center"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Positions Defined') . '</a></div><br />';
}
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '">
	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		';

	// The user wish to EDIT an existing name
	if ( isset($SelectedPosition) AND $SelectedPosition!='' ) {

		$sql = "SELECT employee_position_id,
			       position_name,employee_category_id,position_status
		        FROM hremployeepositions
		        WHERE employee_position_id='".$SelectedPosition."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['employee_position_id'] = $myrow['employee_position_id'];
		$_POST['PositionName']  = $myrow['position_name'];
		$_POST['CategoryName']  = $myrow['employee_category_id'];
    $_POST['position_status']  = $myrow['position_status'];

		echo '<input type="hidden" name="SelectedPosition" value="' . $SelectedPosition . '" />
			<input type="hidden" name="employee_position_id" value="' . $_POST['employee_position_id'] . '" />
			';

		// We dont allow the user to change an existing Name code

		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('POSITION ID') . '</label> ' . $_POST['employee_position_id'] . '</div>
			</div></div>';
	} else 	{
		// This is a new Name so the user may volunteer a Name code
		echo '';
	}

	if (!isset($_POST['PositionName'])) {
		$_POST['PositionName']='';
	}
	echo '<div class="row">
	<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Position Name') . '</label>
			<input type="text" name="PositionName" class="form-control"  required="required" title="' . _('The Position Name is required') . '" value="' . $_POST['PositionName'] . '" /></div>
		</div>';

    // Employee Category  input.
    echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Employee Category Name') .
    	'</label><select id="CategoryName" name="CategoryName" class="form-control">';
    foreach ($EmployeeCategoryName as $CategoryId => $Row) {
			//echo'<option value=""> </option>';
    	echo '<option';
    	if (isset($_POST['CategoryName']) and $_POST['CategoryName']==$CategoryId) {
    		echo ' selected="selected"';
    	}
    	echo ' value="' . $CategoryId . '">' . $Row . '</option>';
    }
    echo '</select> <a target="_blank" href="'. $RootPath . '/HrEmployeeCategories.php" class="btn btn-info">' . ' ' . _('Add Employee Categories') . '</a></div></div>
<div class="col-xs-3" style="margin-left:35px;">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Status') .
  '</label><div class="checkbox"><input type="radio"';
	if (! isset($SelectedName)) {
	 echo ' checked';}
  if (isset($_POST['position_status']) and $_POST['position_status']==1) {
    echo ' checked';}
echo'
   name="Status" value="1"> Active
</div><div class="checkbox">
  <input';
  if (isset($_POST['position_status']) and $_POST['position_status']==0) {
    echo ' checked';
  }
echo'

  type="radio" name="Status" value="0"> Inactive
  </div></div></div>


		</div>
		
		<div class="row" align="center">
			<input type="submit" name="submit" class="btn btn-success" value="' . _('Accept') . '" />
		</div>
	<br />
	</form>';

} // end if user wish to delete




include('includes/footer.php');
?>
