<?php
/* $Id: HrSelectEmployee.php 7751 2018-04-13 16:34:26Z raymond $*/
/* Search for employees  */

include('includes/session.php');
$Title = _('Search Employees');
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
    if (isset($_GET['Department'])) {
    	$SelectedDEPT = $_GET['Department'];
    } elseif (isset($_POST['Department'])){
    	$SelectedStockItem = $_POST['Department'];
    } else {
    	unset($SelectedDEPT);
    }
//    if (!isset($EN) or ($EN=='')){
      echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">';
    if (isset($SelectedEmployee)) {
      echo _('For the Employee') . ': ' . $SelectedEmployee . ' ' . _('and') . ' <input type="hidden" name="$SelectedEmployee" value="' . $SelectedEmployee . '" />';
    }
    echo _('Employee number') . '</label> <input type="text" name="EN" autofocus="autofocus" class="form-control" maxlength="8" size="9" /></div></div>
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
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">'._('Employee name') . '</label>
<input type="text" name="Ename" class="form-control"  maxlength="8" size="9" /></div></div></div>';
      echo '<div class="row">
	 
			<div class="col-xs-4">
			<a href="' . $RootPath . '/HrEmployees.php?New=Yes" class="btn btn-success">' . _('New Employee') . '</a></div>
  			
			 <div class="col-xs-4">
	  <input type="submit" name="SearchEmployee" class="btn btn-info" value="' . _('Search') . '" />
  			</div>
  			</div>
  			<br />
				
        </form>';
//    }
    if(isset($_POST['SearchEmployee'])) {

    	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
    			<thead><tr>
    				<th>', _('Employee ID'), '</th>
    				<th>', _('Full Name'), '</th>
            		<th>', _('Department'), '</th>
    				<th>', _('Telephone'), '</th>
    				<th>', _('Joining Date'), '</th>
    				<th>', _('Gender'), '</th>
    				<th>', _('Date of Birth'), '</th>
    				<th>', _('Nationality'), '</th>
    				<th>', _('Address'), '</th>
    				<th>', _('Marital Status'), '</th>
						<th>', _('Gross Salary'), '</th>
						<th>', _('Net Salary'), '</th>
    				<th class="noprint" colspan="2">Action</th>
    			</tr></thead>';
			$base_sql =	"SELECT hremployees.employee_id,hremployees.empid,
    					first_name,
    					middle_name,
              last_name,
    					mobile_phone,
    					marital_status,
    					date_of_birth,
              joining_date,
    					home_address,
    					gender,
    					status,
    					user_id,
              nationality,
    					employee_department,
							gross_pay,
							net_pay
    				FROM hremployees JOIN hremployeesalarystructures ON hremployees.empid = hremployeesalarystructures.employee_id WHERE";
      if(isset($EN) && $EN !=""){
    	$Sql = $base_sql." hremployees.employee_id LIKE '%".$EN."%'";

    }
    elseif(isset($_POST['Department']) && $_POST['Department'] != "")
    {
      $Sql = $base_sql." employee_department=".$_POST['Department']."";
    }
    elseif(isset($_POST['Ename']) && $_POST['Ename'] != "")
    {
      $Sql = $base_sql." first_name LIKE '%".$_POST['Ename']."%'";
    }

    $Result = DB_query($Sql);

    	$k = 1;// Row colour counter.
    	while ($MyRow = DB_fetch_array($Result)) {
    		if($k == 1) {
    			echo '<tr>';
    			$k = 0;
    		} else {
    			echo '<tr>';
    			$k = 1;
    		}
					$sql2 ="SELECT departmentid,description FROM departments WHERE departmentid =".$MyRow['employee_department']."";
					$result2 = DB_query($sql2);
					$deparmentDetails = DB_fetch_array($result2);
    		/*The SecurityHeadings array is defined in config.php */
    		echo	'<td>'. $MyRow['employee_id']. '</td>
    				<td>'. $MyRow['first_name'].' '.$MyRow['middle_name'].' '.$MyRow['last_name']. '</td>
    				<td>'. $deparmentDetails['description']. ' </td>
    				<td>'. $MyRow['mobile_phone']. '</td>
    				<td>'. $MyRow['joining_date']. '</td>
    				<td>'. $MyRow['gender']. '</td>
    				<td class="centre">'. $MyRow['date_of_birth']. '</td>
    				<td>'. $MyRow['nationality']. '</td>
    				<td>'. $MyRow['home_address']. '</td>
    				<td>'. $MyRow['marital_status']. '</td>
						<td>'. number_format($MyRow['gross_pay'],2). '</td>
						<td>'. number_format($MyRow['net_pay'],2). '</td>
    				<td class="noprint"><a href="HrEmployees.php?EmpID='. $MyRow['employee_id']. '" class="btn btn-info">'. _('Edit'). '</a></td>
    				<td class="noprint"><a href="HrEmployees.php?EmpID='. $MyRow['employee_id']. '&amp;delete=1" onclick="return confirm(\'', _('Are you sure you wish to delete this employee?'), '\');" class="btn btn-danger">'. _('Delete'). '</a></td>
    			</tr>';
    	}// END foreach($Result as $MyRow).
    	echo '</table></div></div></div>
    		<br />';
    }
include('includes/footer.php');
?>
