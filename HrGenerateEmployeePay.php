<?php

/* $Id: HrEmployees.php 7751 2018-04-13 16:34:26Z raymond $ */
/*	Add and Edit Employee*/

include('includes/session.php');
$Title = _('Generate Payroll for Single Employee');

$ViewTopic = 'GeneratePayroll';
$BookMark = 'Payroll';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/CountriesArray.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
   
      <input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
      echo '<div class="row"><div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">';
    if (isset($SelectedPayroll)) {
      echo _('For the Payroll Period') . ': ' . $SelectedPayroll . ' ' . _('and') . ' <input type="hidden" name="$SelectedEmployee" value="' . $SelectedEmployee . '" />';
    }
    echo ''. _('Employee') . '</label>
	<select required="required" id="select-payrollgroup" name="EmpID" class="form-control"> <option value="">select employee</option>';
    $sql = "SELECT empid,employee_id, first_name,middle_name,last_name FROM hremployees where status='1'";
    $resultPayrollGroups = DB_query($sql);
    while ($myrow=DB_fetch_array($resultPayrollGroups)){
          if ($myrow['empid']==$_POST['EmpID']){
            echo '<option data-id="'.$myrow['empid'].'" selected="selected" value="'. $myrow['empid'] . '">' . $myrow['first_name'] .' ' . $myrow['last_name'] .'('.$myrow['employee_id'] .') </option>';
          } else {
            echo '<option data-id="'.$myrow['empid'].'" value="'. $myrow['empid'] . '">'. $myrow['first_name'] .' ' . $myrow['last_name'] .'('.$myrow['employee_id'] .')</option>';
          }
          $payroll_group=$myrow['empid'];
      }

      echo '</select> </div></div>
	  <div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">'._('Payment Period') . '</label>
 <input type="text" name="PaymentPeriod" required="required" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" maxlength="10" size="10"  /></div></div>';
      echo '<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" name="GeneratePayroll" value="' . _('Generate ') . '" class="btn btn-warning" onclick="return confirm(\'', _('Are you sure you wish to generate payroll?'), '\');" />
       </div>
	   </div>
        </div>
        <br />
        
        </form>';
        echo '
        		<script>
        			$( document ).ready(function() {
        				$("#datepicker").datepicker({dateFormat: "MM yy" });

        			});

        		</script>

        ';
if(isset($_POST['GeneratePayroll'])) {
			$InputError = 0;
			if (trim($_POST['EmpID']) == '') {
				$InputError = 1;
				prnMsg(_('please select employee'),'error');
				$Errors[$i] = 'EmpID';
				$i++;
			}
      if (trim($_POST['PaymentPeriod']) == '') {
				$InputError = 1;
				prnMsg(_('please select date of payment'),'error');
				$Errors[$i] = 'EmpID';
				$i++;
			}
      if($InputError == 0)
			{
          $EmpID = $_POST['EmpID'];
          $payroll_array = array();
			    $GeneratedPayroll = $_POST['GeneratePayroll'];
          $sql = DB_query("SELECT empid,payrollgroup_id,employee_department,first_name,last_name,hremployees.employee_id,salary_structure_id,gross_pay,net_pay FROM hremployees JOIN hremployeesalarystructures ON hremployees.empid = hremployeesalarystructures.employee_id WHERE hremployees.empid ='".$EmpID."'");
          $result = DB_fetch_array($sql);
    			$PayrollGroupID = $result['payrollgroup_id'];
          $salaryStructureId = $result['salary_structure_id'];
          $gross_salary = $result['gross_pay'];
          $net_salary = $result['net_pay'];
          $employee_name = $result['first_name'].' '.$result['last_name'];
          $department_id = $result['employee_department'];

          /* check if payperiod already generated*/
    			$chosenPaymentPeriod = $_POST['PaymentPeriod'];
    			$StartDate = date("Y-m-01", strtotime($chosenPaymentPeriod));
    			$EndDate = date("Y-m-t", strtotime($chosenPaymentPeriod));
    			$checksql = DB_query("SELECT daterange_id FROM hrpayslipdateranges WHERE payrollgroup_id='".$PayrollGroupID."'
    							AND start_date ='".$StartDate."' AND end_date='".$EndDate."'");

          if (DB_num_rows($checksql) > 0)
    			{
    					//payslips already generated
              $date_range_row = DB_fetch_array($checksql);
              $date_range_id = $date_range_row['daterange_id'];
              //check if current employee has payslip
              $payslip_sql = DB_query("SELECT employee_id, payslip_id from hremployeepayslips WHERE employee_id='".$EmpID."' AND payslip_date_range_id='".$date_range_id."'");
              if(DB_num_rows($payslip_sql) > 0)
              {
                prnMsg(_('pay already generated for this employee'),'error');
              }
              else {
                //generate new one.
                $employee_array = array();
                $employee_id = $EmpID;
                $salary_structure_id = $salaryStructureId;
                $PaymentPeriodID = $date_range_id;
                $earnings_array = array();
                $deductions_array = array();
                $sql2 = "SELECT hremployeesalarystructure_components.* ,payroll_category_code,payroll_category_type from hremployeesalarystructure_components JOIN hrpayrollcategories ON hremployeesalarystructure_components.payroll_category_id = hrpayrollcategories.payroll_category_id
                        WHERE salary_structure_id ='".$salaryStructureId."'";
                $result_components = DB_query($sql2);
                while ($myrow2 = DB_fetch_array($result_components)) {
                  if($myrow2['payroll_category_type'] == 1)
                  {
                    $payroll_category_amount = $myrow2['amount'];
                    $payroll_category_id = $myrow2['payroll_category_id'];
                    array_push($earnings_array,['amount'=>$payroll_category_amount,'payroll_category_id'=>$payroll_category_id]);

                  }
                  else if($myrow2['payroll_category_type'] == 0)
                  {
                    $payroll_category_amount = $myrow2['amount'];
                    $payroll_category_id = $myrow2['payroll_category_id'];
                    array_push($deductions_array,['amount'=>$payroll_category_amount,'payroll_category_id'=>$payroll_category_id]);

                  }
                }


                //calcuate lop_days and amounts
                //start with default of 0
                $lop_amount = 0;


                // calculate loan/advance Deductions
                $loan_amount_to_pay = 0;
                $sql3 = "SELECT * FROM hremployeeloans WHERE employee_id='".$employee_id."' AND is_approved=1 AND loan_status=0 ";
                $result_loans = DB_query($sql3);
                if (DB_num_rows($result_loans) > 0)
                {
                  $row_loan = DB_fetch_array($result_loans);
                  $loan_amount_to_pay = $row_loan['amount_per_installment'];
                }
                $other_earnings = 0;
                $other_deductions = $lop_amount + $loan_amount_to_pay;
                $total_earnings = array_sum(array_column($earnings_array,'amount')) + $other_earnings;
                $total_deductions = array_sum(array_column($deductions_array,'amount')) + $other_deductions;
                $net_pay = $total_earnings - $total_deductions;
                //add to payroll Array
                $employee_array['employee_id'] = $employee_id;
                $employee_array['employee_name'] = $employee_name;
                $employee_array['employee_department'] = $department_id;
                $employee_array['employee_earnings'] = $earnings_array;
                $employee_array['employee_deductions'] = $deductions_array;
                $employee_array['employee_other_earnings'] = $other_earnings;
                $employee_array['employee_other_deductions'] = $other_deductions;
                $employee_array['employee_total_earnings'] = $total_earnings;
                $employee_array['employee_total_deductions'] = $total_deductions;
                $employee_array['payslip_status'] = 'pending';
                $employee_array['employee_net_pay'] = $net_pay;

                $insert_sql = "INSERT INTO hremployeepayslips (employee_id,gross_salary,lop_amount,loan_deduction_amount,total_earnings,total_deductions,payslip_date_range_id,net_pay)
                    VALUES (
                      '" . $employee_id. "',
                      '" . $gross_salary. "',
                      '" . $lop_amount. "',
                      '" . $loan_amount_to_pay. "',
                      '" . $total_earnings. "',
                      '" . $total_deductions. "',
                      '" . $PaymentPeriodID. "',
                      '" . $net_pay. "')";
                $payslip_insert_result = DB_query($insert_sql);
                $payslip_id = DB_Last_Insert_ID($db,"hremployeepayslips","payslip_id");
                $employee_array['payslip_id'] = $payslip_id;
                $payroll_array[] = $employee_array;


                //insert into `hrpayslipcategorydetails`
                foreach($earnings_array as $earnings)
                {
                  $payroll_category_id = $earnings['payroll_category_id'];
                  $payroll_category_amount = $earnings['amount'];
                  $sql_payslip_categories = DB_query("INSERT INTO hrpayslipcategorydetails(payslip_id,payroll_category_id,amount)
                                            VALUES(
                                              '".$payslip_id."',
                                              '".$payroll_category_id."',
                                              '".$payroll_category_amount."'
                                            )");
                }
                foreach($deductions_array as $deductions)
                {
                  $payroll_category_id = $deductions['payroll_category_id'];
                  $payroll_category_amount = $deductions['amount'];
                  $sql_payslip_categories = DB_query("INSERT INTO hrpayslipcategorydetails(payslip_id,payroll_category_id,amount)
                                            VALUES(
                                              '".$payslip_id."',
                                              '".$payroll_category_id."',
                                              '".$payroll_category_amount."'
                                            )");
                }
                echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead>
            				<tr>
            					<th class="noprint" >&nbsp;</th>
            					<th class="ascending">', _('Status'), '</th>
            					<th class="ascending">', _('Employee ID'), '</th>
            					<th class="ascending">', _('Department'), '</th>';
            					$sql= "SELECT payroll_category_name,payroll_category_type,payroll_category_code,payroll_category_value,payroll_category_type,additional_condition
            								 FROM hrpayroll_groups_payroll_categories JOIN hrpayrollgroups ON hrpayroll_groups_payroll_categories.payroll_group_id = hrpayrollgroups.payrollgroup_id JOIN hrpayrollcategories ON hrpayroll_groups_payroll_categories.payroll_category_id = hrpayrollcategories.payroll_category_id
            								 WHERE payroll_group_id='".$PayrollGroupID."'";
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
            								<td><input name="selected_payslips[]" type="checkbox" value="'.$employee_payslip['payslip_id'].'" /></td>
            								<td>'.$employee_payslip['payslip_status'].'</td>
            								<td>'.$employee_payslip['employee_name'].'</td>
            								<td>'.$deparmentDetails['description'].'</td>';
            								foreach($employee_payslip['employee_earnings'] as $earnings)
            								{
            										echo '<td>'.number_format($earnings['amount'],2).'</td>';
            								}
            					echo'	<td>'.number_format($employee_payslip['employee_other_earnings'],2).'</td>
            								<td>'.number_format($employee_payslip['employee_total_earnings'],2).'</td>';
            								foreach($employee_payslip['employee_deductions'] as $deductions)
            								{
            										echo '<td>'.number_format($deductions['amount'],2).'</td>';
            								}
            					echo'	<td>'.number_format($employee_payslip['employee_other_deductions'],2).'</td>
            								<td>'.number_format($employee_payslip['employee_total_deductions'],2).'</td>
            								<td>'.number_format($employee_payslip['employee_net_pay'],2).'</td>';
            						//	echo	'<td class="noprint"><a href="HrEmployees.php?EmpID='. $employee_payslip['employee_id']. '">'. _('Edit'). '</a></td>';
            								if($employee_payslip['payslip_status'] == "pending")
            								{
            										//echo '<td class="noprint"><a href="HrEmployeesg.php?EmpID='. $MyRow['employee_id']. '&amp;delete=1" onclick="return confirm(\'', _('Are you sure you wish to approve this payslip ?'), '\');">'. _('Approve'). '</a></td>';
            								}
            								else if($employee_payslip['payslip_status'] == "paid")
            								{
            		    					echo '<td class="noprint"><a href="HrPrintPayslip.php?PayslipNo='. $employee_payslip['payslip_id']. '" class="btn btn-warning">'. _('Print Payslip'). '</a></td>';
            								}
            					echo '</tr>';
            				}
                    echo '</table></div></div></div><br />';
              }
          }
          else
          {
            prnMsg(_('please use the link to generate payroll for all employees because no employee has been generated for selected period'),'info');

          }

      }

}
include('includes/footer.php');
?>
