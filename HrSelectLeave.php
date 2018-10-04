<?php
/* $Id: HrSelectEmployee.php 7751 2018-04-13 16:34:26Z raymond $*/
/* Search for employees  */

include('includes/session.php');
$Title = _('Search Leaves');
$ViewTopic = 'HumanResource';
$BookMark = 'HumanResource';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>
	<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

    if (isset($_GET['EN'])) {
    	$EN = $_GET['EN'];
    } elseif (isset($_POST['EN'])){
    	$EN = $_POST['EN'];
    } else {
    	unset($EN);
    }

		if (isset($_GET['Approved'])) {
			$Approved = $_GET['Approved'];
		} elseif (isset($_POST['Approved'])){
			$Approved = $_POST['Approved'];
		} else {
			unset($Approved);
		}

    if (isset($_GET['Department'])) {
    	$SelectedDEPT = $_GET['Department'];
    } elseif (isset($_POST['Department'])){
    	$SelectedStockItem = $_POST['Department'];
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
    if (isset($SelectedEmployee)) {
      echo _('For the Employee') . ': ' . $SelectedEmployee . ' ' . _('and') . ' <input type="hidden" name="$SelectedEmployee" value="' . $SelectedEmployee . '" />';
    }
    echo _('Employee number') . '</label>
	 <input type="text" name="EN" autofocus="autofocus" maxlength="8" size="9" class="form-control" /></div></div>
	 <div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"> ' . _('Department') . '</label>
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
		<div class="col-xs-3" style="margin-left:35px;">
<div class="form-group"> <label class="col-md-12 control-label">'._('Approved') . '</label>
<div class="checkbox"> 
		<input type="radio" checked name="Approved" value="1"> Yes</div>
<div class="checkbox">
		<input type="radio" name="Approved" value="0"> No</div>

</div></div></div>
<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">'._('From') . '</label>
<input type="text" name="FromDate" required="required"  value="'.$SelectedStartDate.'" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" maxlength="6" size="10"  /></div></div>
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">'._('To') . '</label>
<input type="text" name="ToDate" required="required" value="'.$SelectedEndDate.'" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" maxlength="6" size="10"  /></div></div></div>';


      echo '<div class="row">
<div class="col-xs-4">
  			<a href="' . $RootPath . '/HrLeaveApplications.php?New=Yes" class="btn btn-success">' . _('New Leave Application') . '</a></div>
			<div class="col-xs-4">
<input type="submit" class="btn btn-info" name="SearchLeave" value="' . _('Search') . '" /></div>
  			</div>
  			
  			<br />
				
        </form>';
//    }
    if(isset($_POST['SearchLeave'])) {

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
$Fromdate=DateTime::createFromFormat($_SESSION['DefaultDateFormat'],$_POST['FromDate']);
$Todate=DateTime::createFromFormat($_SESSION['DefaultDateFormat'],$_POST['ToDate']);
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

				 		 WHERE leave_start_date  BETWEEN '".$Fromdate->format('Y-m-d')."' AND '".$Todate->format('Y-m-d')."'AND
            manager_id='".$userrow['empid']."' AND
						 ";
				 	}
				}elseif (in_array('22',$_SESSION['AllowedPageSecurityTokens']))

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

	 			 WHERE leave_start_date  BETWEEN '".$Fromdate->format('Y-m-d')."' AND '".$Todate->format('Y-m-d')."'AND
	 			 ";
			 }

		  if(isset($EN) && $EN !=""){
    	$Sql = $base_sql." hremployees.employee_id LIKE '%".$EN."%'";

    }
    elseif(isset($_POST['Department']) && $_POST['Department'] != "")
    {
      $Sql = $base_sql." employee_department=".$_POST['Department']."";
    }
    elseif(isset($_POST['Approved']) && $_POST['Approved'] != "")
    {
      $Sql = $base_sql." leave_approved = ".$_POST['Approved']."";
    }

    $Result = DB_query($Sql);

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

						$sqlleavecount ="SELECT
						                leave_end_date,
						                leave_start_date
						                FROM hremployeeleaves
														WHERE leave_type_id  = '" . $MyRow['leave_type_id'] . "'
														AND leaveemployee_id='".$MyRow['empid']."'
														AND  	leave_approved ='1'
														AND leave_start_date  BETWEEN '".$Fromdate->format('Y-m-d')."' AND '".$Todate->format('Y-m-d')."'";

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

    }

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
