<?php

/* $Id: HrEmploymentCategories.php 7772 2018-04-07 09:30:06Z bagenda $ */
include('includes/session.php');

$Title = _('Employment Leave Types');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
 //$_POST['ValidFrom'] = date($_SESSION['DefaultDateFormat']);
if (isset($_POST['SelectedName'])){
	$SelectedName = mb_strtoupper($_POST['SelectedName']);
} elseif (isset($_GET['SelectedName'])){
	$SelectedName = mb_strtoupper($_GET['SelectedName']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Employee Leave Types ') . '</h1></a></div>';


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (mb_strlen($_POST['LeaveTypeName']) >100) {
		$InputError = 1;
		prnMsg(_('The Leave  Name  must be 100 characters or less long'),'error');
		$Errors[$i] = 'LeaveTypeName';
		$i++;
	}

	if (mb_strlen($_POST['LeaveTypeName'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Leave Name  must contain at least one character'),'error');
		$Errors[$i] = 'LeaveTypeName';
		$i++;
	}

	if (mb_strlen($_POST['LeaveCount'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Leave Count  must contain at least one character'),'error');
		$Errors[$i] = 'LeaveCount';
		$i++;
	}


	if (mb_strlen($_POST['LeaveTypeCode'])>3) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Leave Code  must be 3 characters or less long'),'error');
		$Errors[$i] = 'LeaveTypeCode';
		$i++;
	}

$validfrom=DateTime::createFromFormat($_SESSION['DefaultDateFormat'],$_POST['ValidFrom']);
	$checksql = "SELECT count(*)
		     FROM hremployeeleavetypes
		     WHERE leavetype_name  = '" . $_POST['LeaveTypeName'] . "'";
	$checkresult=DB_query($checksql);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0 and !isset($SelectedName)) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You already have a Leave Name').' '.$_POST['LeaveTypeName'],'error');
			$Errors[$i] = 'LeaveTypeName';
		$i++;
	}

	if (isset($SelectedName) AND $InputError !=1) {

		$sql = "UPDATE hremployeeleavetypes
			SET leavetype_name = '" . $_POST['LeaveTypeName'] . "',
leavetype_code= '" . mb_strtoupper($_POST['LeaveTypeCode']). "',
leavetype_leavecount= '" . $_POST['LeaveCount']. "',
leavetype_status= '" . $_POST['Status']. "',
carry_forward = '" .$_POST['LeaveBalance']. "',
 	lop_enabled = '" . $_POST['Salarydeduction']. "',
	max_carry_forward_leaves = '" . $_POST['ForwardLeaves']. "',
		reset_date  = '" .$validfrom->format('Y-m-d'). "'

			WHERE hrleavetype_id = '" .$SelectedName."'";

		$msg = _('The Leave Name') . ' ' . $_POST['LeaveTypeName']. ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the Name is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM hremployeeleavetypes
			     WHERE leavetype_name  = '" . $_POST['LeaveTypeName'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Leave Name') . ' ' . $_POST['LeaveTypeName'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO hremployeeleavetypes
						(leavetype_name,
						leavetype_code,leavetype_leavecount,leavetype_status,
						carry_forward,lop_enabled,max_carry_forward_leaves,reset_date
					)
					VALUES ('" . $_POST['LeaveTypeName'] . "',
'" . mb_strtoupper($_POST['LeaveTypeCode']) . "',
'" . $_POST['LeaveCount'] . "',
'" . $_POST['Status'] . "',
'" . $_POST['LeaveBalance'] . "',
'" . $_POST['Salarydeduction'] . "',
'" . $_POST['ForwardLeaves'] . "',
'" .$validfrom->format('Y-m-d'). "'
)";


			$msg = _('Leave Name') . ' ' . $_POST["LeaveTypeName"] .  ' ' . _('has been created');
			$checkSql = "SELECT count(hrleavetype_id)
			     FROM hremployeeleavetypes";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql);


	// Fetch the default Group list.
		$DefaultTypeName = $_SESSION['DefaultTypeName'];

	// Does it exist
		$checkSql = "SELECT count(*)
			     FROM hremployeeleavetypes
			     WHERE hrleavetype_id = '" . $DefaultTypeName . "'"
					 ;
		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

	// If it doesnt then update config with newly created one.
		if ($checkrow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='" . $_POST['hrleavetype_id'] . "'
					WHERE confname='$DefaultTypeName'";
			$result = DB_query($sql);
			$_SESSION['DefaultTypeName'] = $_POST['hrleavetype_id'];
		}
		echo '<br />';
		prnMsg($msg,'success');

		unset($SelectedName);
		unset($_POST['hrleavetype_id']);
		unset($_POST['LeaveTypeName']);
		unset($_POST['LeaveTypeCode']);
		unset($_POST['LeaveCount']);
		unset($_POST['LeaveBalance']);
		unset($_POST['Salarydeduction']);
		unset($_POST['ForwardLeaves']);
		unset($_POST['ValidFrom']);
		unset($_POST['Status']);
	}

} elseif ( isset($_GET['delete']) ) {

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'Leave Table'


	$sql= "SELECT COUNT(*)
	       FROM hremployeeleaves
	       WHERE leave_type_id ='".$SelectedName."'";

	$ErrMsg = _('The number of Leave Records using this Leave Type could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg(_('Cannot delete this Leave Type because Employee Leaves  have been created using this Type') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('Leave Records using this Type'),'error');

	}

	 else {
			$result = DB_query("SELECT leavetype_name FROM hremployeeleavetypes WHERE hrleavetype_id='".$SelectedName."'");
			if (DB_Num_Rows($result)>0){
				$NameRow = DB_fetch_array($result);
				$TypeName = $NameRow['leavetype_name'];

				$sql="DELETE FROM hremployeeleavetypes WHERE hrleavetype_id='".$SelectedName."'";
				$ErrMsg = _('The Category record could not be deleted because');
				$result = DB_query($sql,$ErrMsg);
				echo '<br />';
				prnMsg(_('Leave Type') . ' ' . $TypeName  . ' ' . _('has been deleted') ,'success');
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

	$sql = "SELECT * FROM hremployeeleavetypes";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead>';
	echo '<tr>
	<th class="ascending">' . _('Leave Type id') . '</th>
 <th class="ascending">' . _('Leave Type Name') . '</th>
 <th class="ascending">' . _('Leave Type Code') . '</th>
  <th class="ascending">' . _('Leave Count') . '</th>
	  <th class="ascending">' . _('Valid From') . '</th>
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
<td>%s</td>
		<td><a href="%sSelectedName=%s" class="btn btn-info">' . _('Edit') . '</a></td>
		<td><a href="%sSelectedName=%s&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this Category Name?') . '");\'>' . _('Delete') . '</a></td>
		</tr>',
		$myrow['hrleavetype_id'],
		$myrow['leavetype_name'],
		$myrow['leavetype_code'],
		$myrow['leavetype_leavecount'],
		$myrow['reset_date'],
		($myrow['leavetype_status'] == 1) ? 'Active' : 'Inactive',
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['hrleavetype_id'],
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['hrleavetype_id']);
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

		$sql = "SELECT *
		        FROM hremployeeleavetypes
		        WHERE hrleavetype_id='".$SelectedName."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['hrleavetype_id'] = $myrow['hrleavetype_id'];
		$_POST['LeaveTypeName']  = $myrow['leavetype_name'];
		$_POST['LeaveTypeCode']  = $myrow['leavetype_code'];
		$_POST['LeaveCount']  = $myrow['leavetype_leavecount'];
		$_POST['LeaveBalance'] = $myrow['carry_forward'];
		$_POST['Salarydeduction']  = $myrow['lop_enabled'];
		$_POST['ForwardLeaves']  = $myrow['max_carry_forward_leaves'];
		$_POST['ValidFrom']  = $myrow['reset_date'];

$_POST['Status']  = $myrow['leavetype_status'];
		echo '<input type="hidden" name="SelectedName" value="' . $SelectedName . '" />
			<input type="hidden" name="hrleavetype_id" value="' . $_POST['hrleavetype_id'] . '" />
			<div class="row">';

		// We dont allow the user to change an existing Name code

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Leave Type ID') . '</label> ' . $_POST['hrleavetype_id'] . '</div>
			</div></div>';
	} else 	{
		// This is a new Name so the user may volunteer a Name code
		echo '';
	}

	if (!isset($_POST['LeaveTypeName'])) {
		$_POST['LeaveTypeName']='';
	}
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Leave Name') . '</label>
			<input type="text" name="LeaveTypeName" class="form-control"  required="required" title="' . _('The Leave Type Name is required') . '" value="' . $_POST['LeaveTypeName'] . '" /></div>
							</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Leave  Code') . '</label>
				<input type="text" name="LeaveTypeCode" class="form-control"  required="required" title="' . _('The Leave Type Code is required') . '" value="' . $_POST['LeaveTypeCode'] . '" /></div>
							</div>
			<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Leave Count') . '</label>
					<input type="text" name="LeaveCount"  class="form-control" required="required" title="' . _('The Leave Count is required') . '" value="' . $_POST['LeaveCount'] . '" /></div>
							</div></div>
			<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Status') .
			  '</label><div class="checkbox">
			 <input type="radio"';
				if (! isset($SelectedName)) {
			   echo ' checked';}
			  if (isset($_POST['Status']) and $_POST['Status']==1) {
			    echo ' checked';}
			echo'
			   name="Status" value="1"> Active
</div>
<div class="checkbox">
			  <input';
			  if (isset($_POST['Status']) and $_POST['Status']==0) {
			    echo ' checked';
			  }
			echo'

			  type="radio" name="Status" value="0"> Inactive
			  </div>
			 </div>
							</div>
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Employee leave balance') .
					'</label><div class="checkbox"><input type="radio"';
					if (! isset($SelectedName)) {
					 echo ' checked';}
					if (isset($_POST['LeaveBalance']) and $_POST['LeaveBalance']==1) {
						echo ' checked';}
				echo'
					 name="LeaveBalance" value="1"> Allow leave carry forward
</div>
<div class="checkbox">
					<input';
					if (isset($_POST['LeaveBalance']) and $_POST['LeaveBalance']==0) {
						echo ' checked';
					}
				echo'
type="radio" name="LeaveBalance" value="0"> Discard leave balance
					</div></div>
							</div>

					<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Additional leaves') .'</label>
						<div class="checkbox"><input type="radio"';
						if (! isset($SelectedName)) {
						 echo ' checked';}
						if (isset($_POST['Salarydeduction']) and $_POST['Salarydeduction']==1) {
							echo ' checked';}
					echo'
						 name="Salarydeduction" value="1"> Leave Without Pay (LWP)?</div>
						 <div class="checkbox">
						<input';
						if (isset($_POST['Salarydeduction']) and $_POST['Salarydeduction']==0) {
							echo ' checked';
						}
					echo'
					type="radio" name="Salarydeduction" value="0"> No salary deduction
						</div>
							</div>
							</div>
							</div>
						<div class="row">
								<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Max Carry Forward Leaves ') . '</label>
								<input type="text" name="ForwardLeaves"  class="form-control"  title="' . _('The Leave Count is required') . '" value="' . $_POST['ForwardLeaves'] . '" /></div>
							</div>
						<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Valid From') . '</label>';
								if (isset($SelectedName)) {
									$valid= $_POST['ValidFrom'];
								}else{
								$valid=date('Y-m-d');

								}

								echo
								'
								<input type="text" name="ValidFrom"  required="required" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" title="' . _('The Valid From is required') . '" value="'.ConvertSQLDate($valid).'" /></div>
							</div>




		<div class="col-xs-4">
<div class="form-group"> <br />
			<input type="submit" class="btn btn-success" name="submit" value="' . _('Accept') . '" />
		</div>
	</div>
	</div><br />
	</form>';

	echo "<script>
					$( document ).ready(function() {
							//create date.
							//get format.
							var date_format = '".$_SESSION['DefaultDateFormat']."';
							var new_date_format = date_format.replace('Y', 'yy');
							$('.datepicker').datepicker({
									changeMonth: true,
									changeYear: true,
									showButtonPanel: true,
									dateFormat: new_date_format
							});
					});

			</script>";

} // end if user wish to delete

include('includes/footer.php');
?>
