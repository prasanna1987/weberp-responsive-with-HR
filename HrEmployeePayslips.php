<?php

/* $Id: HrEmployeePayslips.php 7772 2018-04-07 09:30:06Z raymond $ */
/* employee sees his payslips  and generate pdfs for them */

include('includes/session.php');
$Title = _('My Payslips');

$ViewTopic = 'EmployeePayslips';
$BookMark = 'Employees';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/CountriesArray.php');



echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

$employee_sql = DB_query("SELECT hremployees.employee_id, empid, payrollgroup_id FROM hremployees JOIN hremployeesalarystructures on hremployees.empid = hremployeesalarystructures.employee_id WHERE user_id='".$_SESSION['UserID']."'");
$employee_row = DB_fetch_array($employee_sql);
if(DB_num_rows($employee_sql) < 1)
{
  prnMsg( _('You are not an employee of this company : Please contact HR'), 'error');
  echo '<br />';
}
else if(DB_num_rows($employee_sql) > 0)
{
  $EmpID = $employee_row['empid'];
  $employee_number = $employee_row['employee_id'];
  $payroll_group_id = $employee_row['payrollgroup_id'];
  $sql = "SELECT hremployeepayslips.*, first_name,middle_name,last_name,employee_department FROM hremployeepayslips JOIN hremployees on hremployeepayslips.employee_id = hremployees.empid
  WHERE hremployeepayslips.employee_id ='".$EmpID."' AND payslip_status ='paid'";

  $result = DB_query($sql);

  $payroll_array = array();
  $no_of_payslips = 0;
  while($myrow = DB_fetch_array($result))
  {

    $employee_array = array();
    $employee_name = $myrow['first_name']." ".$myrow['middle_name']." ".$myrow['last_name'];
    $department_id = $myrow['employee_department'];
    $payslip_id = $myrow['payslip_id'];
    $gross_salary = $myrow['gross_pay'];
    $net_pay = $myrow['net_pay'];
    $employee_id = $myrow['employee_id'];
    $loan_amount_to_pay = $myrow['loan_amount'];
    $lop_amount = $myrow['lop_amount'];
    $payslip_status = $myrow['payslip_status'];
    $PaymentPeriodID = $myrow['payslip_date_range_id'];

    //calculations
    $sql_salary_structures ="SELECT salary_structure_id,gross_pay,net_pay
        FROM hremployeesalarystructures
        WHERE employee_id ='" . $employee_id . "'";
    $result_salary_structures = DB_query($sql_salary_structures);
    $row = DB_fetch_array($result_salary_structures);
    $salary_structure_id = $row['salary_structure_id'];

    $earnings_array = array();
    $deductions_array = array();
    $sql2 = "SELECT hremployeesalarystructure_components.* ,payroll_category_code,payroll_category_type from hremployeesalarystructure_components JOIN hrpayrollcategories ON hremployeesalarystructure_components.payroll_category_id = hrpayrollcategories.payroll_category_id
            WHERE salary_structure_id ='".$salary_structure_id."'";
    $result_components = DB_query($sql2);
    while ($myrow2 = DB_fetch_array($result_components)) {
      if($myrow2['payroll_category_type'] == 1)
      {
        $earnings_array[] = $myrow2['amount'];
      }
      else if($myrow2['payroll_category_type'] == 0)
      {
        $deductions_array[] = $myrow2['amount'];
      }
    }
    //get other earnings.
    $sql2 = DB_query("SELECT sum(amount) as other_earnings FROM hrpayslipextradetails WHERE entry_type = 1 AND payslip_id='".$payslip_id."'");
    $earning_result = DB_fetch_array($sql2);
    $other_earnings = $earning_result['other_earnings'];

    //get other deductions
    $sql2 = DB_query("SELECT sum(amount) as other_deductions FROM hrpayslipextradetails WHERE entry_type = 0 AND payslip_id='".$payslip_id."'");
    $deduction_result = DB_fetch_array($sql2);
    $extra_deductions = $deduction_result['other_deductions'];

    $other_deductions = $lop_amount + $loan_amount_to_pay + $extra_deductions;
    $total_earnings = array_sum($earnings_array) + $other_earnings;
    $total_deductions = array_sum($deductions_array) + $other_deductions;

    //get pay period
    $sql_date_range = DB_query("SELECT start_date, end_date FROM hrpayslipdateranges WHERE daterange_id='".$PaymentPeriodID."'");
		$date_range_row = DB_fetch_array($sql_date_range);
		$start_date = $date_range_row['start_date'];
		$MonthOfPayment = date('F Y',strtotime($start_date));
		$DayOfPayment = date('d-m-Y',strtotime($start_date));

    //add to payroll Array
    $employee_array['employee_id'] = $employee_id;
    $employee_array['payslip_id'] = $payslip_id;
    $employee_array['employee_name'] = $employee_name;
    $employee_array['employee_department'] = $department_id;
    $employee_array['employee_earnings'] = $earnings_array;
    $employee_array['employee_deductions'] = $deductions_array;
    $employee_array['employee_other_earnings'] = $other_earnings;
    $employee_array['employee_other_deductions'] = $other_deductions;
    $employee_array['employee_total_earnings'] = $total_earnings;
    $employee_array['employee_total_deductions'] = $total_deductions;
    $employee_array['employee_net_pay'] = $net_pay;
    $employee_array['payslip_status'] = $payslip_status;
    $employee_array['payment_period'] = $MonthOfPayment;
    $payroll_array[] = $employee_array;

    $no_of_payslips++;
  }
  if($no_of_payslips == 0)
  {
    prnMsg(_('You Do not have any payslips yet'),'success');
  }
  else if($no_of_payslips > 0)
  {
    echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead><tr>
          <th class="ascending">', _('Period'), '</th>
          <th class="ascending">', _('Status'), '</th>
          <th class="ascending">', _('Employee'), '</th>
          <th class="ascending">', _('Department'), '</th>';
          $sql= "SELECT payroll_category_name,payroll_category_type,payroll_category_code,payroll_category_value,payroll_category_type,additional_condition
                 FROM hrpayroll_groups_payroll_categories JOIN hrpayrollgroups ON hrpayroll_groups_payroll_categories.payroll_group_id = hrpayrollgroups.payrollgroup_id JOIN hrpayrollcategories ON hrpayroll_groups_payroll_categories.payroll_category_id = hrpayrollcategories.payroll_category_id
                 WHERE payroll_group_id='".$payroll_group_id."'";
                 $ErrMsg = _('The payroll groups categories could not be retrieved because');
                $DbgMsg = _('The SQL used to retrieve payroll group categories and failed was');
                $result = DB_query($sql,$ErrMsg,$DbgMsg);

                while ($myrow=DB_fetch_array($result)){
                  $CategoryCodeValue = $myrow['payroll_category_code'];
                  if($myrow['payroll_category_type'] == 1){
                      echo '<th class="ascending">'.$myrow['payroll_category_name'].'</th>';
                    }
                }
                echo '<th class="ascending"> Others</th>';
                echo '<th class="ascending"> Total Earnings</th>';
                DB_data_seek($result, 0 );
                while ($myrow=DB_fetch_array($result)){
                  $CategoryCodeValue = $myrow['payroll_category_code'];
                  if($myrow['payroll_category_type'] == 0){
                      echo '<th class="ascending">'.$myrow['payroll_category_name'].'</th>';
                    }
                }
                echo '<th class="ascending"> Others</th>';
                echo '<th class="ascending"> Total Deductions</th>';

          echo'
          <th class="ascending">', _('Net Salary'), '</th>
          <th class="noprint" colspan="2">&nbsp;</th>
        </tr></thead>';

        foreach($payroll_array as $index => $employee_payslip){
					$sql2 ="SELECT departmentid,description FROM departments WHERE departmentid =".$employee_payslip['employee_department']."";
					$result2 = DB_query($sql2);
					$deparmentDetails = DB_fetch_array($result2);
					echo '<tr id="'.$employee_payslip['payslip_id'].'">
								<td>'.$employee_payslip['payment_period'].'</td>
								<td>'.$employee_payslip['payslip_status'].'</td>
								<td>'.$employee_payslip['employee_name'].'</td>
								<td>'.$deparmentDetails['description'].'</td>';
								foreach($employee_payslip['employee_earnings'] as $earnings)
								{
										echo '<td>'.number_format($earnings,2).'</td>';
								}
					echo'	<td>'.number_format($employee_payslip['employee_other_earnings'],2).'</td>
								<td>'.number_format($employee_payslip['employee_total_earnings'],2).'</td>';
								foreach($employee_payslip['employee_deductions'] as $deductions)
								{
										echo '<td>'.number_format($deductions,2).'</td>';
								}
					echo'	<td>'.number_format($employee_payslip['employee_other_deductions'],2).'</td>
								<td>'.number_format($employee_payslip['employee_total_deductions'],2).'</td>
								<td>'.number_format($employee_payslip['employee_net_pay'],2).'</td>
								<td class="noprint"><a href="HrPrintPayslip.php?PayslipNo='. $employee_payslip['payslip_id']. '">'. _('Print Payslip'). '</a></td>';

					echo '</tr>';
				}
        echo '</table></div></div></div><br />';
  }
}
include('includes/footer.php');
?>
