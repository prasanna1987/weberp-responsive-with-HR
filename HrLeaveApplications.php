<?php

/* $Id: HrPayrollCategories.php 7772 2018-04-07 09:30:06Z bagenda $ */

include('includes/session.php');
include('includes/SQL_CommonFunctions.inc');
$Title = _('Employees Leaves');

include('includes/header.php');



// BEGIN: Employee  array.
$Employeelist = array();
$Query = "SELECT 	empid, employee_id,first_name,middle_name,last_name FROM hremployees ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$Employeelist[$Row['empid']] = $Row['employee_id'].' - '.$Row['first_name'].' '.$Row['middle_name'].' '.$Row['last_name'];
}

	// BEGIN: Leave Types  array.
$LeaveTypes = array();
$Query = "SELECT 	hrleavetype_id, leavetype_name FROM hremployeeleavetypes WHERE leavetype_status =1 ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$LeaveTypes[$Row['hrleavetype_id']] = $Row['leavetype_name'];
}



if (isset($_POST['SelectedName'])){
	$SelectedName = mb_strtoupper($_POST['SelectedName']);
} elseif (isset($_GET['SelectedName'])){
	$SelectedName = mb_strtoupper($_GET['SelectedName']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Employees Leaves ') . '</h1></a></div>';

$sqluser="SELECT
	user_id ,
	empid
FROM hremployees
WHERE user_id ='".$_SESSION['UserID']."'
";
 $userfetch=DB_query($sqluser);

if (in_array('21',$_SESSION['AllowedPageSecurityTokens']))
{

echo '<div class="row" align="center"><a href="' . $RootPath . '/HrSelectLeave.php" class="btn btn-info">' . _('Search For  Leaves') . '</a></div><br />';

}


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (mb_strlen($_POST['EmployeeId']) ==0) {
		$InputError = 1;
		prnMsg(_('Select Employee'),'error');
		$Errors[$i] = 'EmployeeId';
		$i++;
	}

	if (mb_strlen($_POST['LeaveType'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('Select Leave Type'),'error');
		$Errors[$i] = 'LeaveType';
		$i++;
	}
$startmonth ='-01-01';
$endmonth ='-12-31';
$dateyear=DateTime::createFromFormat($_SESSION['DefaultDateFormat'],$_POST['StartDate']);
$date_string = $dateyear->format('Y');

	$startyear = $date_string.$startmonth;
	$endyear = $date_string.$endmonth;
$sqlleavecount ="SELECT
                leave_end_date,
                leave_start_date
                FROM hremployeeleaves
								WHERE leave_type_id  = '" . $_POST['LeaveType'] . "'
								AND leaveemployee_id='".$_POST['EmployeeId']."'
AND leave_approved='1'
AND leave_start_date BETWEEN '".$startyear."' AND '".$endyear."'
								";

$leavefetch=DB_query($sqlleavecount);
$nodays =0;

if (DB_Num_Rows($leavefetch)>0){

	while($leaverow = DB_fetch_array($leavefetch))
	{

		$datetime1 = date_create($leaverow['leave_end_date']);
		$datetime2 = date_create($leaverow['leave_start_date']);
		$interval = date_diff($datetime1, $datetime2);
	 $interval->format('%a');
	 if($interval->format('%a')==0)
	 {
	$olddays=1;
	}else
	{
		$olddays =$interval->format('%a');
	}
	 $nodays =$nodays+$olddays;
	}

}else{

}

$newenddate = DateTime::createFromFormat($_SESSION['DefaultDateFormat'],$_POST['EndDate']);
$newstartdate = DateTime::createFromFormat($_SESSION['DefaultDateFormat'],$_POST['StartDate']);

if($newenddate->format('Y-m-d') < $newstartdate->format('Y-m-d')){
	$InputError = 1;
	echo '<br />';
	prnMsg(_('End Date should be higher than Start Date'),'error');
	$Errors[$i] = 'EndDate';
	$i++;

}

$newleavestartdate=date_create($newstartdate->format('Y-m-d'));
$newleaveenddate=date_create($newenddate->format('Y-m-d'));

$newinterval = date_diff($newleaveenddate, $newleavestartdate);
	if($newinterval->format('%a')==0){
		$newstartday=1;
	}else {
		$newstartday=$newinterval->format('%a')+1;
	}
$newdays=$newstartday+$nodays;


if($_POST['LeaveDuration']=="single" OR $_POST['LeaveDuration']=="half"){
if($_POST['StartDate']!=$_POST['EndDate']){
$InputError = 1;
echo '<br />';
prnMsg(_('Single and Half Day only allows one Day, Make End Date same as Start Day'),'error');
$Errors[$i] = 'EndDate';
$i++;
}

}

	$checksql = "SELECT leavetype_leavecount,leavetype_name
		     FROM hremployeeleavetypes
		     WHERE hrleavetype_id  = '" . $_POST['LeaveType'] . "'";
	$checkresult=DB_query($checksql);
	$checkrow=DB_fetch_array($checkresult);
	prnMsg(_($checkrow['leavetype_name'].' Applied Days  '.$newstartday.' , Remaining days ['.($checkrow['leavetype_leavecount']- $nodays).'] for this Year.'),'info');

	if ($checkrow['leavetype_leavecount'] < $newdays) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_(' You will exceed your '.$checkrow['leavetype_name'].' - Leave Count Days [ '.$checkrow['leavetype_leavecount'].'] for this Year.'),'error');
		$Errors[$i] = 'LeaveType';
		$i++;
	}



	if (isset($SelectedName) AND $InputError !=1) {

		$queryemp ="SELECT manager_id FROM hremployees WHERE empid='". $_POST['EmployeeId']."'";
			$resultemp = DB_query($queryemp);
			$EmployeeManager = DB_fetch_array($resultemp);
			$ManagerId = $EmployeeManager['manager_id'];

			$queryuser ="SELECT user_id FROM hremployees WHERE empid='". $ManagerId."'";
				$resultuser = DB_query($queryuser);
				$ManagerUser = DB_fetch_array($resultuser);
				$UserId = $ManagerUser['user_id'];


			if($_POST['Approved']==1){

				if((in_array('21',$_SESSION['AllowedPageSecurityTokens'])) ){

				}else{
					$InputError = 1;
					echo '<br />';
					prnMsg(_('Your not Authorised to Approve Leave'),'error');
					$Errors[$i] = 'LeaveType';
					$i++;

				}


			}

		$sql = "UPDATE hremployeeleaves
			SET leaveemployee_id = '" . $_POST['EmployeeId'] . "',
			leave_type_id = '" .$_POST['LeaveType']. "',
			is_half = '" . $_POST['LeaveDuration'] . "',
leave_start_date= '" . $newstartdate->format('Y-m-d'). "',
leave_end_date= '" . $newenddate->format('Y-m-d'). "',
leave_reason= '" . $_POST['Reason']. "',
leave_approved = '" . $_POST['Approved']. "',
leave_viewed_by_manager= '" . $_POST['ViewManager']. "',
leave_manager_remark = '" . $_POST['Remarks']. "',
leave_approving_manager = '" .$ManagerId. "'


			WHERE employee_leave_id = '" .$SelectedName."'";

		$msg = _('Leave for Employee') . ' ' . $_POST['EmployeeId']. ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the Name is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM hremployeeleaves
			     WHERE leave_type_id=0";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('Leave ') . ' ' . $_POST['category_name'] . _(' already exist.'),'error');
		} else {


			$query ="SELECT manager_id FROM hremployees WHERE empid='". $_POST['EmployeeId']."'";
				$result2 = DB_query($query);
				$EmployeeManager = DB_fetch_array($result2);
				$ManagerId = $EmployeeManager['manager_id'];
			// Add new record on submit

			if($_POST['Approved']==1){

				if(in_array('21',$_SESSION['AllowedPageSecurityTokens'])) {

				}else{
					$InputError = 1;
					echo '<br />';
					prnMsg(_('Your not Authorised to Approve  Leave'),'error');
					$Errors[$i] = 'LeaveType';
					$i++;

				}


			}


			$sql = "INSERT INTO hremployeeleaves
						(
leaveemployee_id ,
 leave_type_id ,
is_half ,
leave_start_date,
leave_end_date ,
 	leave_reason ,
	leave_approved,
	leave_viewed_by_manager,
	leave_manager_remark ,
	leave_approving_manager
)
					VALUES ('" . $_POST['EmployeeId'] . "',
'" . $_POST['LeaveType'] . "',
'" . $_POST['LeaveDuration']. "',
'" . $newstartdate->format('Y-m-d'). "',
'" . $newenddate->format('Y-m-d'). "',
'" . $_POST['Reason'] . "',
'" . $_POST['Approved']. "',
'" . $_POST['ViewManager']. "',
'" . $_POST['Remarks'] . "',
'" .$ManagerId. "'

)";


			$msg = _('Employee') . ' ' . $_POST["EmployeeId"] .  ' ' . _('Leave Application has been Created');
			// $checkSql = "SELECT count(employee_leave_id)
			//      FROM hremployeeleaves";
			// $result = DB_query($checkSql);
			// $row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql);
if($_POST['Approved']==1){

	$begin = new DateTime( date('Y-m-d',strtotime($_POST['StartDate'])) );
	$end = new DateTime( date('Y-m-d',strtotime($_POST['EndDate'])));
	$end = $end->modify( '+1 day' );

	$period = new DatePeriod($begin, new DateInterval('P1D'),$end);
	foreach ($period as $key => $value) {
$sqlleave ="INSERT INTO hremployeeattendanceregister
			(employee_attendance_id,
			absent_date,
			leave_type_id
		)
		VALUES ('" . $_POST['EmployeeId'] . "',
'" .$value->format('Y-m-d'). "',
'" . $_POST['LeaveType'] . "'
)";

$result1 = DB_query($sqlleave);
	}


}


	// Fetch the default Category list.
		$DefaultId = $_SESSION['Defaultid'];

	// Does it exist
		$checkSql = "SELECT count(*)
			     FROM hremployeeleaves
			     WHERE employee_leave_id = '" . $DefaultId . "'"
					 ;
		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

	// If it doesnt then update config with newly created one.
		if ($checkrow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='" . $_POST['employee_leave_id'] . "'
					WHERE confname='Defaultid'";
			$result = DB_query($sql);
			$_SESSION['Defaultid'] = $_POST['employee_leave_id'];
		}
		echo '<br />';
		prnMsg($msg,'success');

		unset($SelectedName);
		unset($_POST['employee_leave_id']);
		unset($_POST['LeaveType']);
		unset($_POST['EmployeeId']);
		unset($_POST['LeaveDuration']);
		unset($_POST['StartDate']);
		unset($_POST['EndDate']);
	unset($_POST['Reason']);
	unset($_POST['Approved']);
	unset($_POST['ViewManager']);
unset($_POST['Remarks']);
unset($_POST['ApprovingManager']);

	}

} elseif ( isset($_GET['delete']) ) {

	$sql= "SELECT COUNT(*)
				 FROM hremployeeleaves
				 WHERE employee_leave_id='".$SelectedName."'
				  AND leave_approved =1";

	$ErrMsg = _('The number of Records using this Leave  could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg(_('Cannot delete this Leave Record because It has already been approved') . '<br />' ,'error');

	}

	 else {
			$result = DB_query("SELECT employee_leave_id FROM hremployeeleaves WHERE employee_leave_id='".$SelectedName."'");
			if (DB_Num_Rows($result)>0){
				$NameRow = DB_fetch_array($result);
				$LeaveId = $NameRow['category_name'];

				$sql="DELETE FROM hremployeeleaves WHERE employee_leave_id='".$SelectedName."'";
				$ErrMsg = _('The Leave record could not be deleted because');
				$result = DB_query($sql,$ErrMsg);
				echo '<br />';
				prnMsg(_('Leave Record') . ' ' . $LeaveId  . ' ' . _('has been deleted') ,'success');
			}
			unset ($SelectedName);
			unset($_GET['delete']);

	} //end if Positions used in Employees set up
}

if (!isset($SelectedName)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedPayrollCategory will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of sales types will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/
$sqluser="SELECT
	user_id ,
	empid
FROM hremployees
WHERE user_id ='".$_SESSION['UserID']."'
";
 $userfetch=DB_query($sqluser);

if (DB_Num_Rows($userfetch)>0 AND !in_array('22',$_SESSION['AllowedPageSecurityTokens'])){

	while($userrow = DB_fetch_array($userfetch))
 {
	$sql = "SELECT employee_leave_id,
			leaveemployee_id
			leave_type_id ,
		 is_half ,
		 leave_start_date,
		 leave_end_date,
		 leave_approved,
first_name,middle_name,last_name,employee_id,leavetype_name,leavetype_leavecount
	  FROM hremployeeleaves
JOIN hremployees on hremployeeleaves.leaveemployee_id = hremployees.empid
JOIN hremployeeleavetypes on hremployeeleaves.leave_type_id = hremployeeleavetypes.hrleavetype_id
WHERE leave_approved=0
AND   manager_id='". $userrow['empid']."'";

	$result2 = DB_query($sql);

if (DB_Num_Rows($result2)>0){
$result = DB_query($sql);
}else{

	$sql = "SELECT employee_leave_id,
			leaveemployee_id,
			leave_type_id ,
		 is_half ,
		 leave_start_date,
		 leave_end_date,
		 leave_approved,
	first_name,middle_name,last_name,employee_id,leavetype_name,leavetype_leavecount
		FROM hremployeeleaves
	JOIN hremployees on hremployeeleaves.leaveemployee_id = hremployees.empid
	JOIN hremployeeleavetypes on hremployeeleaves.leave_type_id = hremployeeleavetypes.hrleavetype_id
	WHERE leave_approved=0
	AND   leaveemployee_id='". $userrow['empid']."'";

	$result = DB_query($sql);
}

}
}elseif(in_array('22',$_SESSION['AllowedPageSecurityTokens'])){
	$sql = "SELECT employee_leave_id,empid,
			leaveemployee_id,
			leave_type_id ,
		 is_half ,
		 leave_start_date,
		 leave_end_date,
		 leave_approved,
	first_name,middle_name,last_name,employee_id,leavetype_name,leavetype_leavecount
		FROM hremployeeleaves
	JOIN hremployees on hremployeeleaves.leaveemployee_id = hremployees.empid
	JOIN hremployeeleavetypes on hremployeeleaves.leave_type_id = hremployeeleavetypes.hrleavetype_id
	WHERE leave_approved=0";
	$result = DB_query($sql);

}

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead>';
	echo '<tr>
	<th class="ascending">' . _('Leave id') . '</th>
	<th class="ascending">' . _('Employee Id ') . '</th>
 <th class="ascending">' . _('Employee ') . '</th>
 <th class="ascending">' . _('Leave Name') . '</th>
 <th class="ascending">' . _('Leave Count') . '</th>
  <th class="ascending">' . _('Remaining Days') . '</th>
	 <th class="ascending">' . _('Leave Days') . '</th>
 <th class="ascending">' . _('Start Date') . '</th>
 <th class="ascending">' . _('End Date') . '</th>
 <th class="ascending">' . _('Duration') . '</th>
 <th class="ascending">' . _('Approved') . '</th>

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



echo'<td>'.$myrow['employee_leave_id'].'</td>
		<td>'.$myrow['employee_id'].'</td>
<td>'.$myrow['first_name'].' '.$myrow['middle_name'].' '.$myrow['last_name'].'</td>
<td>'.$myrow['leavetype_name'].'</td>
<td>'.$myrow['leavetype_leavecount'].'</td>';


$startmonth ='-1-1';
$endmonth ='-12-31';
$date_string = date('Y',strtotime($myrow['leave_start_date']));
	$startyear = $date_string.$startmonth;
	$endyear = $date_string.$endmonth;




$sqlleavecount ="SELECT
								leave_end_date,
								leave_start_date
								FROM hremployeeleaves
								WHERE leave_type_id  = '" . $myrow['leave_type_id'] . "'
								AND leaveemployee_id='".$myrow['empid']."'
								AND  	leave_approved ='1'
								AND leave_start_date  BETWEEN '".$startyear."' AND '".$endyear."'";

$leavefetch=DB_query($sqlleavecount);
$nodays =0;
while($leaverow = DB_fetch_array($leavefetch))
{

	$datetime1 = date_create($leaverow['leave_end_date']);
	$datetime2 = date_create($leaverow['leave_start_date']);
	$interval = date_diff($datetime1, $datetime2);

 if($interval->format('%a')==0)
 {
$olddays=1;
}else
{
	$olddays =$interval->format('%a')+1;

}
 $nodays =$nodays+$olddays;
}
$datetime12 = date_create($myrow['leave_end_date']);
$datetime22 = date_create($myrow['leave_start_date']);
$interval2 = date_diff($datetime12, $datetime22);

if($interval2->format('%a')==0)
{
$leavedays=1;
}else
{
$leavedays =$interval2->format('%a')+1;

}


echo'
<td>'.($myrow['leavetype_leavecount']-$nodays).'</td>
<td>'.$leavedays.'</td>
<td>'.$myrow['leave_start_date'].'</td>
<td>'.$myrow['leave_end_date'].'</td>
<td>'.$myrow['is_half'].'</td>
<td>'._(($myrow['leave_approved'] == 1) ? 'YES' : 'NO').'</td>

		<td>';
if($myrow['leave_approved']==1)
{
echo'<a href="HrLeaveApplications.php?SelectedName='.$myrow['employee_leave_id'].'" class="btn btn-info">' . _('View') . '</a>';
}else {
	echo'<a href="HrLeaveApplications.php?SelectedName='.$myrow['employee_leave_id'].'" class="btn btn-info">' . _('Edit') . '</a>';
}
echo'</td>
		<td>';

		if($myrow['leave_approved']==0)
		{
		echo'<a href="HrLeaveApplications.php?SelectedName='.$myrow['employee_leave_id'].'&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this Category Name?') . '");\'>' . _('Delete') . '</a>';
		}
		echo'</td>
		</tr>';

	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}

//end of ifs and buts!
if (isset($SelectedName)) {

	echo '<div class="row" align="center"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Leaves Defined') . '</a></div><br />';
}
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		';

	// The user wish to EDIT an existing name
	if ( isset($SelectedName) AND $SelectedName!='' ) {

		$sql = "SELECT *
	FROM hremployeeleaves

		        WHERE employee_leave_id='".$SelectedName."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['employee_leave_id'] = $myrow['employee_leave_id'];
		$_POST['EmployeeId']  = $myrow['leaveemployee_id'];
		$_POST['LeaveType']  = $myrow['leave_type_id'];
		$_POST['LeaveDuration']  = $myrow['is_half'];
		$_POST['StartDate']  = $myrow['leave_start_date'];
$_POST['EndDate']  = $myrow['leave_end_date'];
$_POST['Reason']  = $myrow['leave_reason'];
$_POST['Approved']  = $myrow['leave_approved'];
$_POST['ViewManager']  = $myrow['leave_viewed_by_manager'];
$_POST['Remarks']  = $myrow['leave_manager_remark'];
$_POST['ApprovingManager']  = $myrow['leave_approving_manager'];

		echo '<input type="hidden" name="SelectedName" value="' . $SelectedName . '" />
			<input type="hidden" name="employee_leave_id" value="' . $_POST['employee_leave_id'] . '" />
			<div class="row">';

		// We dont allow the user to change an existing Name code

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Leave ID') . ': ' . $_POST['employee_leave_id'] . '</div>
					</div></div>';
	} else 	{
		// This is a new Name so the user may volunteer a Name code
		echo '';
	}
echo '<div class="row">';
	if (!isset($_POST['EmployeeId'])) {
		$_POST['EmployeeId']='';
	}

	$sqluser="SELECT
		user_id ,
		empid,employee_id,first_name,middle_name,last_name
	FROM hremployees
	WHERE user_id ='".$_SESSION['UserID']."'
	";
	 $userfetch=DB_query($sqluser);

	if (!in_array('22',$_SESSION['AllowedPageSecurityTokens']))
	{
	while($userrow = DB_fetch_array($userfetch))
	{
		$sqlmanager="SELECT empid, employee_id,first_name,middle_name,last_name
			FROM hremployees
	  WHERE manager_id='".$userrow['empid']."'";

		$magagerfetch=DB_query($sqlmanager);
	if (DB_Num_Rows($magagerfetch)>0){

		echo '
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Employee') .
			'</label>
			<select id="EmployeeId" name="EmployeeId" class="form-control">';
				echo'<option value="">Select an Employee </option>';
	while ($managerrow = DB_fetch_array($magagerfetch)) {

			echo '<option';
			if (isset($_POST['EmployeeId']) and $_POST['EmployeeId']==$managerrow['empid']) {
				echo ' selected="selected"';
			}
			echo ' value="' . $managerrow['empid']. '">'.$managerrow['employee_id'].' - ' .$managerrow['first_name'].' ' .$managerrow['middle_name'].' '.$managerrow['last_name'].'</option>';
		}
		echo '</select> </div>
					</div>';

}else{

	echo'<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Employee</label>
<input type="text" value="'.$userrow['employee_id'].' - ' .$userrow['first_name'].' ' .$userrow['middle_name'].' '.$userrow['last_name'].'" name="Employee" size="50" class="form-control">
<input type="hidden" value="'.$userrow['empid'].'" name="EmployeeId">

	</div>
					</div>';
}

	}

	}elseif(in_array('22',$_SESSION['AllowedPageSecurityTokens']))
	{
		echo '
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Employee') .
			'</label>
			<select id="EmployeeId" name="EmployeeId" class="form-control">';
				echo'<option value="">Select an Employee </option>';
		foreach ($Employeelist as $EmpID => $Row) {

			echo '<option';
			if (isset($_POST['EmployeeId']) and $_POST['EmployeeId']==$EmpID) {
				echo ' selected="selected"';
			}
			echo ' value="' . $EmpID . '">' . $Row . '</option>';
		}
		echo '</select> </div>
					</div></div>';
	}else{


	}

echo'	<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Leave Type') .
		'</label>
		<select id="LeaveType" name="LeaveType" class="form-control">';
			echo'<option value="">Select a Leave Type</option>';
	foreach ($LeaveTypes as $LeaveTypeId => $Row) {

		echo '<option';
		if (isset($_POST['LeaveType']) and $_POST['LeaveType']==$LeaveTypeId) {
			echo ' selected="selected"';
		}
		echo ' value="' . $LeaveTypeId . '">' . $Row . '</option>';
	}
	echo '</select> </div>
					</div>

			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Leave duration') .
				'</label><div class="checkbox"><input type="radio"';
				if (!isset($SelectedName)) {
				 echo ' checked';}
				if (isset($_POST['LeaveDuration']) and $_POST['LeaveDuration']=="single") {
					echo ' checked';}
			echo'
				 name="LeaveDuration" value="single"> Single day
</div>
<div class="checkbox">
				<input';
				if (isset($_POST['LeaveDuration']) and $_POST['LeaveDuration']=="multiple") {
					echo ' checked';
				}
			echo'

				type="radio" name="LeaveDuration" value="multiple"> Multiple days
</div>
<div class="checkbox">
				<input';
				if (isset($_POST['LeaveDuration']) and $_POST['LeaveDuration']=="half") {
					echo ' checked';
				}
			echo'

				type="radio" name="LeaveDuration" value="half"> Half day
				</div>
				</div>
					</div>
	<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Start Date') . '</label>';

if (isset($SelectedName)) {
	$StartDate= $_POST['StartDate'];
	$EndDate= $_POST['EndDate'];
}else{
$StartDate=date('Y-m-d');
	$EndDate=date('Y-m-d');
}

			echo

			'
			<input type="text" name="StartDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" required="required" title="' . _('Start Date ') . '" value="' .ConvertSQLDate($StartDate). '" /></div>
					</div>
</div>
		<div class="row">
				<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('End Date') . '</label>';


				echo
				'
				<input type="text" name="EndDate"  class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" required="required" title="' . _('End Date') . '" value="' .ConvertSQLDate($EndDate). '" /></div>
					</div>

			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Reason') . '</label>
				
<textarea name="Reason" class="form-control">' . $_POST['Reason'] .' '. $UserId. '</textarea></div>
					</div>';
$queryemp1 ="SELECT manager_id FROM hremployees WHERE empid='". $_POST['EmployeeId']."'";
	$resultemp1 = DB_query($queryemp1);
	$EmployeeManager1 = DB_fetch_array($resultemp1);
	$ManagerId = $EmployeeManager['manager_id'];

$queryemp1 ="SELECT manager_id FROM hremployees WHERE empid='". $_POST['EmployeeId']."'";
	$resultemp1 = DB_query($queryemp1);
	$EmployeeManager1 = DB_fetch_array($resultemp1);
	$ManagerId1= $EmployeeManager1['manager_id'];

	$queryuser1 ="SELECT user_id FROM hremployees WHERE empid='". $ManagerId1."'";
		$resultuser1 = DB_query($queryuser1);
		$ManagerUser1 = DB_fetch_array($resultuser1);
		$UserId1 = $ManagerUser1['user_id'];


if(in_array('21',$_SESSION['AllowedPageSecurityTokens'])){


echo'	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Approved') .
					'</label><div class="checkbox"><input type="radio"';

					if (isset($_POST['Approved']) and $_POST['Approved']==1) {
						echo ' checked';}
				echo'
					 name="Approved" value="1"> YES
</div>
<div class="checkbox">
					<input';
					if (! isset($SelectedName)) {
					 echo ' checked';}
					if (isset($_POST['Approved']) and $_POST['Approved']==0) {
						echo ' checked';
					}
				echo'

					type="radio" name="Approved" value="0">NO
					</div>
					</div>
					</div>
					</div>

					<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Viewed By Manager') .
						'</label><div class="checkbox"><input type="radio"';

						if (isset($_POST['ViewManager']) and $_POST['ViewManager']==1) {
							echo ' checked';}
					echo'
						 name="ViewManager" value="1"> YES
</div>
<div class="checkbox">
						<input';
						if (! isset($SelectedName)) {
						 echo ' checked';}
						if (isset($_POST['ViewManager']) and $_POST['ViewManager']==0) {
							echo ' checked';
						}
					echo'

						type="radio" name="ViewManager" value="0"> NO
						</div>
					</div>
</div>
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Managers Remarks') . '</label>
					
				<textarea name="Remarks" class="form-control">' . $_POST['Remarks'] . '</textarea>
				</div>
					</div>';
			}else{
	if (!isset($SelectedName)) {
echo'<input type="hidden" name="Approved"   value="0" />
<input type="hidden" name="ViewManager"   value="0" />
<textarea name="Remarks"  hidden ></textarea>
';
}else{
	echo'<input type="hidden" name="Approved"   value="'.$_POST['Approved'].'" />
	<input type="hidden" name="ViewManager"   value="'.$_POST['ViewManager'].'" />
	<textarea name="Remarks"  hidden >' . $_POST['Remarks'] . '</textarea>
	';

}


			}

	if (isset($SelectedName)) {
						echo'<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Approving Manager') . '</label>
						<input type="text" name="ApprovingManager" class="form-control"  value="' .$Employeelist[$_POST['ApprovingManager']]  . '" size="40" /></div>
					</div>';
				}
		echo'</div>
		<br />
		<div class="row" align="center">';
if (isset($SelectedName)) {
		if($_POST['Approved']==0)
		{
		echo'<input type="submit" class="btn btn-success" name="submit" value="' . _('Accept') . '" /></a>';
		}
	}else{
echo'<input type="submit" class="btn btn-success" name="submit" value="' . _('Accept') . '" />';
	}

		echo'</div>
	<br />
	</form>';

} // end if user wish to delete
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
include('includes/footer.php');
?>
