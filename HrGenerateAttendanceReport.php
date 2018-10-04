<?php
/* $Id: HrSelectEmployee.php 7751 2018-04-13 16:34:26Z raymond $*/
/* Search for employees  */

include('includes/session.php');
$Title = _('Attendance Report');
$ViewTopic = 'HumanResource';
$BookMark = 'Attendance Report';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>
	<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


		// BEGIN: Leave Types  array.
		$LeaveTypes = array();
		$Query = "SELECT 	hrleavetype_id, leavetype_name FROM hremployeeleavetypes WHERE leavetype_status =1 ";
		$Result = DB_query($Query);
		while ($Row = DB_fetch_array($Result)) {
		$LeaveTypes[$Row['hrleavetype_id']] = $Row['leavetype_name'];
		}

    if (isset($_GET['Department'])) {
    	$SelectedDEPT = $_GET['Department'];
    } elseif (isset($_POST['Department'])){
    	$SelectedDEPT = $_POST['Department'];
    } else {
    	unset($SelectedDEPT);
    }

		if (isset($_GET['FromDate'])) {
			$SelectedStartDate = $_GET['FromDate'];
		} elseif (isset($_POST['FromDate'])){
			$SelectedStartDate = $_POST['FromDate'];
		} else {
			unset($SelectedStartDate);
		}

		if (isset($_GET['ToDate'])) {
			$SelectedEndtDate = $_GET['ToDate'];
		} elseif (isset($_POST['ToDate'])){
			$SelectedEndDate = $_POST['ToDate'];
		} else {
			unset($SelectedStartDate);
		}

//    if (!isset($EN) or ($EN=='')){
      echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">';

    echo  _(' Department ') . '</label>
	<select name="Department" class="form-control"> <option value="">search by department</option>';
    $sql = "SELECT departmentid, description FROM departments";
    $resultDepartments = DB_query($sql);
    while ($myrow=DB_fetch_array($resultDepartments)){
  			if (isset($_POST['Department'])){
  				if ($myrow['departmentid'] == $_POST['Department']){
  					 echo '<option selected="selected" value="' . $myrow['departmentid'] . '">' . $myrow['description'] . '</option>';
  				} else {
  					 echo '<option value="' . $myrow['departmentid'] . '">' . $myrow['description'] . '</option>';
  				}
  			} elseif ($myrow['departmentid']==$_SESSION['UserStockLocation']){
  				 echo '<option selected="selected" value="' . $myrow['departmentid'] . '">' . $myrow['description'] . '</option>';
  			} else {
  				 echo '<option value="' . $myrow['departmentid'] . '">' . $myrow['description'] . '</option>';
  			}
  		}

  		echo '</select> </div></div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">'._('From') . '</label>
<input type="text" name="FromDate" required="required"  value="'.$SelectedStartDate.'" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" maxlength="10" size="20"  />&nbsp';
echo '</div></div>
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">'._('To') . '</label>
<input type="text" name="ToDate" required="required" value="'.$SelectedEndDate.'" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" maxlength="10" size="20"  /></div></div></div>';


      echo '<div class="row" align="center"><input type="submit" class="btn btn-warning" name="GenerateReport" value="' . _('Generate') . '" />
  			</div>
  			
  			<br />
				
        </form>

				<script>
$( function() {
	$( "#datepicker" ).datepicker();
	$( "#datepicker1" ).datepicker();
} );
</script>

				'
				;
//    }
    if(isset($_POST['GenerateReport'])) {

    	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
    			<thead><tr>
					<th class="ascending">' . _('Employee') . '</th>
	<th class="ascending">' . _('Total') . '</th>
	<th class="ascending">' . _('Loss of Pay') . '</th>
					';

					foreach ($LeaveTypes as $LeaveTypeId => $Row) {

						echo '<th>'. $Row . '</th>
						';
					}
echo'</tr></thead>';

$sqluser="SELECT
	user_id ,
	empid
FROM hremployees
WHERE user_id ='".$_SESSION['UserID']."'
";
 $userfetch=DB_query($sqluser);

if (DB_Num_Rows($userfetch)>0 AND !in_array('22',$_SESSION['AllowedPageSecurityTokens']))
{
while($userrow = DB_fetch_array($userfetch))
{

					$sql= "SELECT empid,employee_id,first_name,middle_name,last_name,employee_department
					FROM hremployees WHERE employee_department='".$SelectedDEPT."'AND manager_id='".$userrow['empid']."'

					";
					$ErrMsg = _('The employee could not be loaded because');
					$DbgMsg = _('The SQL that was used to get the employees and failed was');
					$result = DB_query($sql,$ErrMsg,$DbgMsg);

					while($myrow = DB_fetch_array($result))
					{
						echo'<tr>';
					echo'	<td>'.$myrow['first_name'].' '.$myrow['middle_name'].' '.$myrow['last_name'].'
					<input type="hidden" name="EmployeeID" value="' . $myrow['empid'] . '" />
						</td>';
						$StartDate = date("Y-m-d", strtotime($_POST['FromDate']));
						$EndDate = date("Y-m-d", strtotime($_POST['ToDate']));

						$sql1="SELECT COUNT(*)
							FROM hremployeeattendanceregister WHERE employee_attendance_id='".$myrow['empid']."'
AND  absent_date  BETWEEN '".$StartDate."' AND '".$EndDate."'";
						$result1 = DB_query($sql1);
						$myrow1 = DB_fetch_row($result1);
							echo '<td>'. $myrow1[0] . '</td>
							<td>0</td>';


						foreach ($LeaveTypes as $LeaveTypeId => $Row) {

					  $sql2="SELECT COUNT(*)
					    FROM hremployeeattendanceregister WHERE employee_attendance_id='".$myrow['empid']."' AND leave_type_id ='".$LeaveTypeId."'
AND  absent_date  BETWEEN '".$StartDate."' AND '".$EndDate."'";
										  $result2 = DB_query($sql2);
	$myrow2 = DB_fetch_row($result2);
							echo '<td>'. $myrow2[0] . '</td>';
						}
						echo'

						';
echo '</tr>';
					}
}
}elseif(in_array('22',$_SESSION['AllowedPageSecurityTokens'])){

	$sql= "SELECT empid,employee_id,first_name,middle_name,last_name,employee_department
	FROM hremployees WHERE employee_department='".$SelectedDEPT."'
	";
	$ErrMsg = _('The employee could not be loaded because');
	$DbgMsg = _('The SQL that was used to get the employees and failed was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);

	while($myrow = DB_fetch_array($result))
	{
		echo'<tr>';
	echo'	<td>'.$myrow['first_name'].' '.$myrow['middle_name'].' '.$myrow['last_name'].'
	<input type="hidden" name="EmployeeID" value="' . $myrow['empid'] . '" />
		</td>';
		$StartDate = date("Y-m-d", strtotime($_POST['FromDate']));
		$EndDate = date("Y-m-d", strtotime($_POST['ToDate']));

		$sql1="SELECT COUNT(*)
			FROM hremployeeattendanceregister WHERE employee_attendance_id='".$myrow['empid']."'
AND  absent_date  BETWEEN '".$StartDate."' AND '".$EndDate."'";
		$result1 = DB_query($sql1);
		$myrow1 = DB_fetch_row($result1);
			echo '<td>'. $myrow1[0] . '</td>
			<td>0</td>';


		foreach ($LeaveTypes as $LeaveTypeId => $Row) {

		$sql2="SELECT COUNT(*)
			FROM hremployeeattendanceregister WHERE employee_attendance_id='".$myrow['empid']."' AND leave_type_id ='".$LeaveTypeId."'
AND  absent_date  BETWEEN '".$StartDate."' AND '".$EndDate."'";
							$result2 = DB_query($sql2);
$myrow2 = DB_fetch_row($result2);
			echo '<td>'. $myrow2[0] . '</td>';
		}
		echo'

		';
echo '</tr>';
	}

}

    	echo '</table></div></div></div><br />';

    }
include('includes/footer.php');
?>
