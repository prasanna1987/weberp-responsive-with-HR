<?php
/* $Id: HrSelectEmployee.php 7751 2018-04-13 16:34:26Z raymond $*/
/* Search for employees  */

include('includes/session.php');
$Title = _('My Leaves');
$ViewTopic = 'HumanResource';
$BookMark = 'HumanResource';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();
$i=1;

					$sqluser="SELECT
				   	user_id ,
				 		empid
				 FROM hremployees
				 WHERE user_id ='".$_SESSION['UserID']."'
				  ";
				   $userfetch=DB_query($sqluser);

					if (DB_Num_Rows($userfetch)>0){


				 	while($userrow = DB_fetch_array($userfetch))
				 	{
				 			$base_sql =	"SELECT employee_leave_id,
				 			empid,
				 					leaveemployee_id,
				 					leave_type_id ,
				 				 is_half ,
				 				 leave_start_date,
				 				 leave_end_date,
				 				 leave_approved,
				 		first_name,middle_name,last_name,employee_id,leavetype_name,leavetype_leavecount,
				 		employee_department
				 			  FROM hremployeeleaves
				 		JOIN hremployees on hremployeeleaves.leaveemployee_id = hremployees.empid
				 		JOIN hremployeeleavetypes on hremployeeleaves.leave_type_id = hremployeeleavetypes.hrleavetype_id
            WHERE leaveemployee_id='".$userrow['empid']."'";
				 	}


    $Result = DB_query($base_sql);

if (DB_Num_Rows($Result)>0){



echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
    <thead><tr>
    <th class="ascending">' . _('Leave id') . '</th>
    <th class="ascending">' . _('Employee Id ') . '</th>
   <th class="ascending">' . _('Employee ') . '</th>
   <th class="ascending">', _('Department'), '</th>
   <th class="ascending">' . _('Leave Name') . '</th>
   <th class="ascending">' . _('Leave Count') . '</th>
   <th class="ascending">' . _('Remaining Days') . '</th>
    <th class="ascending">' . _('Leave Days') . '</th>
   <th class="ascending">' . _('Start Date') . '</th>
   <th class="ascending">' . _('End Date') . '</th>
   <th class="ascending">' . _('Duration') . '</th>
   <th class="ascending">' . _('Approved') . '</th>
    </tr></thead>';




    	$k = 1;// Row colour counter.
    	while ($MyRow = DB_fetch_array($Result)) {
    		if($k == 1) {
    			echo '<tr class="OddTableRows">';
    			$k = 0;
    		} else {
    			echo '<tr class="EvenTableRows">';
    			$k = 1;
    		}

    		echo
'<td class="text">'. $MyRow['employee_leave_id']. '</td>
				<td class="text">'. $MyRow['employee_id']. '</td>
    				<td class="text">'. $MyRow['first_name'].' '.$MyRow['middle_name'].' '.$MyRow['last_name']. '</td>
    				<td class="text">'. $deparmentDetails['description']. ' </td>
    				<td class="text">'. $MyRow['leavetype_name']. '</td>
    				<td class="text">'. $MyRow['leavetype_leavecount']. '</td>';

						$startmonth ='-1-1';
						$endmonth ='-12-31';
						$date_string = date('Y',strtotime($MyRow['leave_start_date']));
							$startyear = $date_string.$startmonth;
							$endyear = $date_string.$endmonth;


						$sqlleavecount ="SELECT
						                leave_end_date,
						                leave_start_date
						                FROM hremployeeleaves
														WHERE leave_type_id  = '" . $MyRow['leave_type_id'] . "'
														AND leaveemployee_id='".$MyRow['empid']."'
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

						$datetime12 = date_create($MyRow['leave_end_date']);
						$datetime22 = date_create($MyRow['leave_start_date']);
						$interval2 = date_diff($datetime12, $datetime22);

						if($interval2->format('%a')==0)
						{
						$leavedays=1;
						}else
						{
						$leavedays =$interval2->format('%a')+1;

						}


    			echo'
         	<td class="text">'.($MyRow['leavetype_leavecount']-$nodays). '</td>
	<td class="text">'.$leavedays. '</td>
					<td class="text">'. $MyRow['leave_start_date']. '</td>
    				<td class="centre">'. $MyRow['leave_end_date']. '</td>
    				<td class="text">'. $MyRow['is_half']. '</td>
    				<td class="text">'. (($MyRow['leave_approved'] =='1') ? 'YES':'NO').'</td>
    				<td class="noprint">';
						if($MyRow['leave_approved']==1)
						{
						echo'<a href="HrLeaveApplications.php?SelectedName='. $MyRow['employee_leave_id']. '" class="btn btn-info">'. _('View'). '</a>';
						}else {
							echo'<a href="HrLeaveApplications.php?SelectedName='. $MyRow['employee_leave_id']. '" class="btn btn-info">'. _('Edit'). '</a>';
						}
						echo'</td>
    				<td class="noprint">';
						if($MyRow['leave_approved']==0)
						{
						echo'<a href="HrLeaveApplications.php?SelectedName='. $MyRow['employee_leave_id']. '&amp;delete=1" onclick="return confirm(\'', _('Are you sure you wish to delete this employee?'), '\');" class="btn btn-danger">'. _('Delete'). '</a>';
						}
echo'</tr>';
    	}// END foreach($Result as $MyRow).
    	echo '</table></div></div></div>
    		<br />';

      }else{
        $InputError = 1;
        prnMsg(_(' You Do not have any Leave yet'),'success');
        $Errors[$i] = 'LeaveTypeName';
        $i++;

      }



}
        else {
          $InputError = 1;
          prnMsg(_('You are not an employee of this company'),'error');
          $Errors[$i] = 'LeaveTypeName';
          $i++;
        }



include('includes/footer.php');
?>
