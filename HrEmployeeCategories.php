<?php

/* $Id: HrEmploymentCategories.php 7772 2018-04-07 09:30:06Z bagenda $ */

include('includes/session.php');

$Title = _('Employment Categories');

include('includes/header.php');

if (isset($_POST['SelectedName'])){
	$SelectedName = mb_strtoupper($_POST['SelectedName']);
} elseif (isset($_GET['SelectedName'])){
	$SelectedName = mb_strtoupper($_GET['SelectedName']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Employee Categories ') . '</h1></a></div>';


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (mb_strlen($_POST['CategoryName']) >100) {
		$InputError = 1;
		prnMsg(_('The Category  Name  must be 100 characters or less long'),'error');
		$Errors[$i] = 'CateryName';
		$i++;
	}

	if (mb_strlen($_POST['CategoryName'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Category Name  must contain at least one character'),'error');
		$Errors[$i] = 'CategoryName';
		$i++;
	}

	if (mb_strlen($_POST['CategoryPrefix'])>3) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Category Prefix  must be 3 characters or less long'),'error');
		$Errors[$i] = 'CategoryPrefix';
		$i++;
	}


	$checksql = "SELECT count(*)
		     FROM hremployeecategories
		     WHERE category_name = '" . $_POST['CategoryName'] . "'";
	$checkresult=DB_query($checksql);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0 and !isset($SelectedName)) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You already have a Category Name').' '.$_POST['CategoryName'],'error');
		$Errors[$i] = 'CateryName';
		$i++;
	}

	if (isset($SelectedName) AND $InputError !=1) {

		$sql = "UPDATE hremployeecategories
			SET category_name = '" . $_POST['CategoryName'] . "',
category_prefix= '" . mb_strtoupper($_POST['CategoryPrefix']). "',
status= '" . mb_strtoupper($_POST['Status']). "'

			WHERE employee_category_id = '" .$SelectedName."'";

		$msg = _('The Category Name') . ' ' . $_POST['CategoryName']. ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the Name is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM hremployeecategories
			     WHERE category_name = '" . $_POST['CategoryName'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Category Name') . ' ' . $_POST['employee_category_id'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO hremployeecategories
						(category_name,
						category_prefix,status)
					VALUES ('" . $_POST['CategoryName'] . "',
'" . mb_strtoupper($_POST['CategoryPrefix']) . "',
'" . $_POST['Status'] . "'
)";


			$msg = _('Category Name') . ' ' . $_POST["CategoryName"] .  ' ' . _('has been created');
			$checkSql = "SELECT count(employee_category_id)
			     FROM hremployeecategories";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql);


	// Fetch the default Category list.
		$DefaultCategoryName = $_SESSION['DefaultCategoryName'];

	// Does it exist
		$checkSql = "SELECT count(*)
			     FROM hremployeecategories
			     WHERE employee_category_id = '" . $DefaultCategoryName . "'"
					 ;
		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

	// If it doesnt then update config with newly created one.
		if ($checkrow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='" . $_POST['employee_category_id'] . "'
					WHERE confname='DefaultCategoryName'";
			$result = DB_query($sql);
			$_SESSION['DefaultCategoryName'] = $_POST['employee_category_id'];
		}
		echo '<br />';
		prnMsg($msg,'success');

		unset($SelectedName);
		unset($_POST['employee_category_id']);
		unset($_POST['CategoryName']);
		unset($_POST['CategoryPrefix']);
		unset($_POST['Status']);
	}

} elseif ( isset($_GET['delete']) ) {

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'EMPLOYEE Positions'


	$sql= "SELECT COUNT(*)
	       FROM hremployeepositions
	       WHERE employee_category_id='".$SelectedName."'";

	$ErrMsg = _('The number of transactions using this Category Name could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg(_('Cannot delete this Category because Employee Positions  have been created using this Category') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('Positions using this Category'),'error');

	}

	 else {
			$result = DB_query("SELECT category_name FROM hremployeecategories WHERE employee_category_id='".$SelectedName."'");
			if (DB_Num_Rows($result)>0){
				$NameRow = DB_fetch_array($result);
				$CategoryName = $NameRow['category_name'];

				$sql="DELETE FROM hremployeecategories WHERE employee_category_id='".$SelectedName."'";
				$ErrMsg = _('The Category record could not be deleted because');
				$result = DB_query($sql,$ErrMsg);
				echo '<br />';
				prnMsg(_('Category Name') . ' ' . $CategoryName  . ' ' . _('has been deleted') ,'success');
			}
			unset ($SelectedName);
			unset($_GET['delete']);

	} //end if Positions used in Employees set up
}

if (!isset($SelectedName)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedPosition will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of sales types will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT employee_category_id, category_name,category_prefix,status FROM hremployeecategories";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<thead><tr>
	<th class="ascending">' . _('Category id') . '</th>
 <th class="ascending">' . _('Category Name') . '</th>
 <th class="ascending">' . _('Category Prefix') . '</th>
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
		<td><a href="%sSelectedName=%s" class="btn btn-info">' . _('Edit') . '</a></td>
		<td><a href="%sSelectedName=%s&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this Category Name?') . '");\'>' . _('Delete') . '</a></td>
		</tr>',
		$myrow['employee_category_id'],
		$myrow['category_name'],
		$myrow['category_prefix'],
		($myrow['status'] == 1) ? 'Active' : 'Inactive',
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['employee_category_id'],
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['employee_category_id']);
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}

//end of ifs and buts!
if (isset($SelectedName)) {

	echo '<div class="row" align="center"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Types Defined') . '</a></div><br />';
}
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		';

	// The user wish to EDIT an existing name
	if ( isset($SelectedName) AND $SelectedName!='' ) {

		$sql = "SELECT employee_category_id,
			       category_name,category_prefix, status
		        FROM hremployeecategories
		        WHERE employee_category_id='".$SelectedName."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['employee_category_id'] = $myrow['employee_category_id'];
		$_POST['CategoryName']  = $myrow['category_name'];
		$_POST['CategoryPrefix']  = $myrow['category_prefix'];
$_POST['status']  = $myrow['status'];
		echo '<input type="hidden" name="SelectedName" value="' . $SelectedName . '" />
			<input type="hidden" name="employee_category_id" value="' . $_POST['employee_category_id'] . '" />
			<div class="row">';

		// We dont allow the user to change an existing Name code

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('CATEGORY ID') . '</label> ' . $_POST['employee_category_id'] . '</div>
			</div></div>';
	} else 	{
		// This is a new Name so the user may volunteer a Name code
		echo '';
	}

	if (!isset($_POST['CategoryName'])) {
		$_POST['CategoryName']='';
	}
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Category Name') . '</label>
			<input type="text" name="CategoryName" class="form-control"  required="required" title="' . _('The Category Name is required') . '" value="' . $_POST['CategoryName'] . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Category Prefix') . '</label>
				<input type="text" name="CategoryPrefix" class="form-control"  required="required" title="' . _('The Category Prefix is required') . '" value="' . $_POST['CategoryPrefix'] . '" /></div>
			</div>
			<div class="col-xs-3" style="margin-left:35px;">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Status') .
			  '</label><div class="checkbox"><input type="radio"';
				if (! isset($SelectedName)) {
			   echo ' checked';}
			  if (isset($_POST['status']) and $_POST['status']==1) {
			    echo ' checked';}
			echo'
			   name="Status" value="1"> Active
</div><div class="checkbox">
			  <input';
			  if (isset($_POST['status']) and $_POST['status']==0) {
			    echo ' checked';
			  }
			echo'

			  type="radio" name="Status" value="0"> Inactive
			  </div></div>
</div></div>

		
		<div class="row" align="center">
			<input type="submit" class="btn btn-success" name="submit" value="' . _('Accept') . '" />
		</div>
	<br />
	</form>';

} // end if user wish to delete

include('includes/footer.php');
?>
