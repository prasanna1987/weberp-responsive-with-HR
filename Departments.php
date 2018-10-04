<?php

include('includes/session.php');

$Title = _('Departments');

include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if ( isset($_GET['SelectedDepartmentID']) )
	$SelectedDepartmentID = $_GET['SelectedDepartmentID'];
elseif (isset($_POST['SelectedDepartmentID']))
	$SelectedDepartmentID = $_POST['SelectedDepartmentID'];

if (isset($_POST['Submit'])) {

	//initialise no input errors assumed initially before we test

	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if (ContainsIllegalCharacters($_POST['DepartmentName'])) {
		$InputError = 1;
		echo prnMsg( _('The description of the department must not contain the character') . " '&amp;' " . _('or the character') ." '",'error');
	}
	if (trim($_POST['DepartmentName']) == '') {
		$InputError = 1;
		echo prnMsg( _('The Name of the Department should not be empty'), 'error');
	}

	if (isset($_POST['SelectedDepartmentID'])
		AND $_POST['SelectedDepartmentID']!=''
		AND $InputError !=1) {


		/*SelectedDepartmentID could also exist if submit had not been clicked this code would not run in this case cos submit is false of course  see the delete code below*/
		// Check the name does not clash
		$sql = "SELECT count(*) FROM departments
				WHERE departmentid <> '" . $SelectedDepartmentID ."'
				AND description " . LIKE . " '" . $_POST['DepartmentName'] . "'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if ( $myrow[0] > 0 ) {
			$InputError = 1;
			echo prnMsg( _('This department name already exists.'),'error');
		} else {
			// Get the old name and check that the record still exist neet to be very careful here

			$sql = "SELECT description
					FROM departments
					WHERE departmentid = '" . $SelectedDepartmentID . "'";
			$result = DB_query($sql);
			if ( DB_num_rows($result) != 0 ) {
				// This is probably the safest way there is
				$myrow = DB_fetch_array($result);
				$OldDepartmentName = $myrow['description'];
				$sql = array();
				$sql[] = "UPDATE departments
							SET description='" . $_POST['DepartmentName'] . "',
								authoriser='" . $_POST['Authoriser'] . "'
							WHERE description " . LIKE . " '" . $OldDepartmentName . "'";
			} else {
				$InputError = 1;
				echo prnMsg( _('The department does not exist.'),'error');
			}
		}
		$msg = _('The department has been modified');
	} elseif ($InputError !=1) {
		/*SelectedDepartmentID is null cos no item selected on first time round so must be adding a record*/
		$sql = "SELECT count(*) FROM departments
				WHERE description " . LIKE . " '" . $_POST['DepartmentName'] . "'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if ( $myrow[0] > 0 ) {
			$InputError = 1;
			echo prnMsg( _('There is already a department with the specified name.'),'error');
		} else {
			$sql = "INSERT INTO departments (description,
											 authoriser )
					VALUES ('" . $_POST['DepartmentName'] . "',
							'" . $_POST['Authoriser'] . "')";
		}
		$msg = _('The new department has been created');
	}

	if ($InputError!=1){
		//run the SQL from either of the above possibilites
		if (is_array($sql)) {
			$result = DB_Txn_Begin();
			$ErrMsg = _('The department could not be inserted');
			$DbgMsg = _('The sql that failed was') . ':';
			foreach ($sql as $SQLStatement ) {
				$result = DB_query($SQLStatement, $ErrMsg,$DbgMsg,true);
				if(!$result) {
					$InputError = 1;
					break;
				}
			}
			if ($InputError!=1){
				$result = DB_Txn_Commit();
			} else {
				$result = DB_Txn_Rollback();
			}
		} else {
			$result = DB_query($sql);
		}
		echo prnMsg($msg,'success');
        echo '<br />';
	}
	unset ($SelectedDepartmentID);
	unset ($_POST['SelectedDepartmentID']);
	unset ($_POST['DepartmentName']);

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button


	$sql = "SELECT description
			FROM departments
			WHERE departmentid = '" . $SelectedDepartmentID . "'";
	$result = DB_query($sql);
	if ( DB_num_rows($result) == 0 ) {
		echo prnMsg( _('You cannot delete this Department'),'warn');
	} else {
		$myrow = DB_fetch_row($result);
		$OldDepartmentName = $myrow[0];
		$sql= "SELECT COUNT(*)
				FROM stockrequest INNER JOIN departments
				ON stockrequest.departmentid=departments.departmentid
				WHERE description " . LIKE . " '" . $OldDepartmentName . "'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			echo prnMsg(_('You cannot delete this Department  <br /> There are ' . $myrow[0] . 'items related to this department'),'warn');
			
		} else {
			$sql="DELETE FROM departments WHERE description " . LIKE . "'" . $OldDepartmentName . "'";
			$result = DB_query($sql);
			echo prnMsg( $OldDepartmentName . ' ' . _('The department has been removed') . '!','success');
		}
	} //end if account group used in GL accounts
	unset ($SelectedDepartmentID);
	unset ($_GET['SelectedDepartmentID']);
	unset($_GET['delete']);
	unset ($_POST['SelectedDepartmentID']);
	unset ($_POST['DepartmentID']);
	unset ($_POST['DepartmentName']);
}

 if (!isset($SelectedDepartmentID)) {

	$sql = "SELECT departmentid,
					description,
					authoriser
			FROM departments
			ORDER BY description";

	$ErrMsg = _('There are no departments created');
	$result = DB_query($sql,$ErrMsg);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead><tr>
				<th>' . _('Department Name') . '</th>
				<th>' . _('Authoriser') . '</th>
				<th colspan="2">' . _('Actions') . '</th>
			</tr></thead>';

	while ($myrow = DB_fetch_array($result)) {

		echo '<tr class="striped_row">
				<td>' . $myrow['description'] . '</td>
				<td>' . $myrow['authoriser'] . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?SelectedDepartmentID=' . $myrow['departmentid'] . '" class="btn btn-info">' . _('Edit') . '</a></td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?SelectedDepartmentID=' . $myrow['departmentid'] . '&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this department?') . '\');">'  . _('Delete')  . '</a></td>
			</tr>';

	} //END WHILE LIST LOOP
	echo '</table></div></div></div>';
} //end of ifs and buts!


if (isset($SelectedDepartmentID)) {
	echo '<div class="row">
<div class="col-xs-4">
			<a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-info">' . _('View all Departments') . '</a>
		</div></div><br />';
}


if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') .  '">';

	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($SelectedDepartmentID)) {
		//editing an existing section

		$sql = "SELECT departmentid,
						description,
						authoriser
				FROM departments
				WHERE departmentid='" . $SelectedDepartmentID . "'";

		$result = DB_query($sql);
		if ( DB_num_rows($result) == 0 ) {
			echo prnMsg( _('The selected departemnt could not be found.'),'warn');
			unset($SelectedDepartmentID);
		} else {
			$myrow = DB_fetch_array($result);

			$_POST['DepartmentID'] = $myrow['departmentid'];
			$_POST['DepartmentName']  = $myrow['description'];
			$AuthoriserID			= $myrow['authoriser'];

			echo '<input type="hidden" name="SelectedDepartmentID" value="' . $_POST['DepartmentID'] . '" />';
			echo '<div class="row">';
		}

	}  else {
		$_POST['DepartmentName']='';
		echo '<div class="row">';
	}
	echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Department Name') . '' . '</label>
			<input type="text" class="form-control" name="DepartmentName" size="50" required="required" title="' ._('The department name is required') . '" maxlength="100" value="' . $_POST['DepartmentName'] . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Authoriser') . '</label>
			<select name="Authoriser" class="form-control">';
	$usersql="SELECT userid FROM www_users";
	$userresult=DB_query($usersql);
	while ($myrow=DB_fetch_array($userresult)) {
		if ($myrow['userid']==$AuthoriserID) {
			echo '<option selected="True" value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
		} else {
			echo '<option value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
		}
	}
	echo '</select></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <br />
			<input type="submit" class="btn btn-success" name="Submit" value="' . _('Enter Information') . '" />
		</div>
        </div>
		</div>
		</form>';

} //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>