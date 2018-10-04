<?php

/* $Id: HrEmployees.php 7751 2018-04-13 16:34:26Z raymond $ */
/*	Add and Edit Employee*/

include('includes/session.php');
$Title = _('Generate Payroll for Paygroups');

$ViewTopic = 'GeneratePayroll';
$BookMark = 'Payroll';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/CountriesArray.php');



echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
echo '<div class="row" align="center"><a href="' . $RootPath . '/HrSelectEmployee.php" class="btn btn-info">' . _('Search For Employee') . '</a></div><br />';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
    
      <input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
      echo '<div class="row"><div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">';
    if (isset($SelectedPayroll)) {
      echo _('For the Payroll Period') . ': ' . $SelectedPayroll . ' ' . _('and') . ' <input type="hidden" name="$SelectedEmployee" value="' . $SelectedEmployee . '" />';
    }
    echo ''. _('Payroll Group') . '</label>
	<select required="required" id="select-payrollgroup" name="PayrollGroupID" class="form-control"> 
	<option value="">select payroll group</option>';
    $sql = "SELECT payrollgroup_id, payrollgroup_name,frequency_name FROM hrpayrollgroups JOIN hrpaymentfrequency ON hrpayrollgroups.payment_frequency=hrpaymentfrequency.paymentfrequency_id";
    $resultPayrollGroups = DB_query($sql);
    while ($myrow=DB_fetch_array($resultPayrollGroups)){
          if ($myrow['payrollgroup_id']==$_POST['PayrollGroupID']){
            echo '<option data-id="'.$myrow['frequency_name'].'" selected="selected" value="'. $myrow['payrollgroup_id'] . '">' . $myrow['payrollgroup_name'] . '</option>';
          } else {
            echo '<option data-id="'.$myrow['frequency_name'].'" value="'. $myrow['payrollgroup_id'] . '">' . $myrow['payrollgroup_name']. '</option>';
          }
          $payroll_group=$myrow['payrollgroup_id'];
      }
      if (!isset($_POST['PayrollGroupID'])) {
        $_POST['PayrollGroupID']=$payroll_group;
      }
      echo '</select> </div></div>
	  <div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">'._('Payment Period') . '</label> <input type="text" name="PaymentPeriod" required="required" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" maxlength="10" size="10"  /></div></div>';
      echo '<div class="col-xs-4">
<div class="form-group"> <br />
<input type="submit" name="GeneratePayroll" class="btn btn-warning" value="' . _('Generate ') . '" onclick="return confirm(\'', _('Are you sure you wish to generate payroll?'), '\');" />
       </div>
	   </div>
        </div>
        <br />
       
        </form>';
if(isset($GeneratedPayroll))
{
	//summary of payroll and bulk action buttons

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>Payroll Group'.$_POST['PayrollGroupID'].'</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead>
					<tr><th>Pay Period</th><th>Payment Frequency</th><th>Pay Period</th><th>Payslips Generated</th></tr></thead>
					<tr><td>'.$_POST['PaymentPeriod'].'</td><td>'.$PaymentFrequency.'</td><td>'.$PayslipEmployees.' of '.$EmployeesInPaygroup.' Approved: '.$ApprovedPayslips.' Pending: '.$PendingPayslips.' </td></tr>
			</table></div></div></div></div><br />';
}
echo '
		<script>
			$( document ).ready(function() {
				$("#datepicker").datepicker();
					$("#select-payrollgroup").change(function(){
							var payroll_period = $("option:selected", this).attr("data-id");

							if(payroll_period.indexOf("Monthly") !== -1)
							{
								$( "#datepicker" ).datepicker( "option", "dateFormat", "MM yy" );
							}

					});

					$("#BulkOption").change(function() {
						 var option = $(this).val();
						 if(option == "approve_selected")
						 {
								 $("input[type=checkbox]").each(function() {
									 var current_checkbox = $(this).clone();
									 current_checkbox.css("display","none");
		 						 		$("#bulkActionsForm").append(current_checkbox);

									});
						}

						});


			});

		</script>

';
if(isset($_POST['GeneratePayroll'])) {
			$InputError = 0;
			if (trim($_POST['PayrollGroupID']) == '') {
				$InputError = 1;
				prnMsg(_('please select Payroll group'),'error');
				$Errors[$i] = 'PayrollGroupID';
				$i++;
			}
			if($InputError == 0)
			{
			$GeneratedPayroll = $_POST['GeneratePayroll'];

			$PayrollGroupID = $_POST['PayrollGroupID'];
			// get payrooll group Details
			$sql = "SELECT hrpayrollgroups.*, frequency_name FROM hrpayrollgroups JOIN hrpaymentfrequency ON hrpayrollgroups.payment_frequency=hrpaymentfrequency.paymentfrequency_id WHERE payrollgroup_id ='".$PayrollGroupID."'";
			$result = DB_query($sql);
			$result_row = DB_fetch_array($result);
			$PayrollGroupName = $result_row['payrollgroup_name'];
			$PayrollGroupFrequency = $result_row['frequency_name'];
			$PayrollGroupLOP = $result_row['enable_lop'];
			$PayrollGroupLOPValue = $result_row['lop_value'];
			$payroll_currency = $result_row['currency'];

			$PayrollGroupEmployees = 0;
			$PayslipEmployees = 0;
			$ApprovedPayslips = 0;
			$PendingPayslips = 0;
			$PaidPayslips = 0;
			$sql = DB_query("SELECT count(employee_id) as no_of_employees FROM hremployeesalarystructures WHERE payrollgroup_id='".$PayrollGroupID."'");
			$result = DB_fetch_array($sql);
			$PayrollGroupEmployees = $result['no_of_employees'];
			/* check if payperiod already generated*/
			$chosenPaymentPeriod = $_POST['PaymentPeriod'];
			$StartDate = date("Y-m-01", strtotime($chosenPaymentPeriod));
			$EndDate = date("Y-m-t", strtotime($chosenPaymentPeriod));
			$checksql = DB_query("SELECT daterange_id FROM hrpayslipdateranges WHERE payrollgroup_id='".$PayrollGroupID."'
							AND start_date ='".$StartDate."' AND end_date='".$EndDate."'");
			if (DB_num_rows($checksql) > 0)
			{
					//payslips already generated
					$MyRow = DB_fetch_array($checksql);
					$PaymentPeriodID = $MyRow['daterange_id'];
					$sql = "SELECT hremployeepayslips.*, first_name,middle_name,last_name,employee_department FROM hremployeepayslips JOIN hremployees on hremployeepayslips.employee_id = hremployees.empid
					WHERE payslip_date_range_id ='".$PaymentPeriodID."'";

					$result = DB_query($sql);

					$payroll_array = array();

					while($myrow = DB_fetch_array($result))
					{

						$employee_array = array();
						$employee_name = $myrow['first_name']." ".$myrow['middle_name']." ".$myrow['last_name'];
						$department_id = $myrow['employee_department'];
						$payslip_id = $myrow['payslip_id'];
						$gross_salary = $myrow['gross_pay'];
						$net_pay = $myrow['net_pay'];
						$employee_id = $myrow['employee_id'];
						$loan_amount_to_pay = $myrow['loan_deduction_amount'];
						$lop_amount = $myrow['lop_amount'];
						$payslip_status = $myrow['payslip_status'];
						switch($payslip_status)
						{
							case "pending" :
									$PendingPayslips++;
									break;
							case "approved":
									$ApprovedPayslips++;
									break;
							case "paid":
									$PaidPayslips++;
									break;
						}
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
						//get other earnings.
						$sql2 = DB_query("SELECT sum(amount) as other_earnings FROM hrpayslipextradetails WHERE entry_type = 1 AND payslip_id='".$payslip_id."'");
						$earning_result = DB_fetch_array($sql2);
						$other_earnings = $earning_result['other_earnings'];

						//get other deductions
						$sql2 = DB_query("SELECT sum(amount) as other_deductions FROM hrpayslipextradetails WHERE entry_type = 0 AND payslip_id='".$payslip_id."'");
						$deduction_result = DB_fetch_array($sql2);
						$extra_deductions = $deduction_result['other_deductions'];

						$other_deductions = $lop_amount + $loan_amount_to_pay + $extra_deductions;
						$total_earnings = array_sum(array_column($earnings_array,'amount')) + $other_earnings;
						$total_deductions = array_sum(array_column($deductions_array,'amount')) + $other_deductions;

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
						$payroll_array[] = $employee_array;
					}
			}else {
				// insert into payslips.
				//start transaction
				//$result = DB_Txn_Begin();
				$sql = "INSERT INTO hrpayslipdateranges(start_date,end_date,payrollgroup_id)
				VALUES(
					'" . $StartDate. "',
					'" . $EndDate. "',
					'" . $PayrollGroupID. "'
				)";
				$result = DB_query($sql);
				$PaymentPeriodID = DB_Last_Insert_ID($db,'hrpayslipdateranges','daterange_id');
				//calculations
				$sql ="SELECT hremployees.*,salary_structure_id,gross_pay,net_pay
						FROM hremployees JOIN hremployeesalarystructures on hremployees.empid = hremployeesalarystructures.employee_id
						WHERE hremployees.status = 1 AND hremployeesalarystructures.payrollgroup_id ='" . $PayrollGroupID . "'";
				$result = DB_query($sql);
				$payroll_array = array();
				$i=0;
				$PendingPayslips = 0;
				while($myrow = DB_fetch_array($result)){
						$employee_array = array();
						$employee_id = $myrow['empid'];
						$employee_name = $myrow['first_name']." ".$myrow['middle_name']." ".$myrow['last_name'];
						$gross_salary = $myrow['gross_pay'];
						$salary_structure_id = $myrow['salary_structure_id'];
						$department_id = $myrow['employee_department'];
						$earnings_array = array();
						$deductions_array = array();
						$sql2 = "SELECT hremployeesalarystructure_components.* ,payroll_category_code,payroll_category_type from hremployeesalarystructure_components JOIN hrpayrollcategories ON hremployeesalarystructure_components.payroll_category_id = hrpayrollcategories.payroll_category_id
										WHERE salary_structure_id ='".$salary_structure_id."'";
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
						$i++;
						$PendingPayslips++;

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


				}


				//$result = DB_Txn_Commit();

			}
			if (DB_error_no() ==0) {
				$PayslipEmployees = count($payroll_array);
				$TotalNetPay = array_sum(array_column($payroll_array, 'employee_net_pay'));
			
				echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>Payroll Group - '.$PayrollGroupName.'</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead>
								<tr><th>Pay period</th><th>Payment frequency</th><th>Payslips generated</th><th>Total Net Salary</th><th>Bulk actions</th></tr></thead>
								<tr><td><strong>'.$_POST['PaymentPeriod'].'</strong></td><td><strong>'.$PayrollGroupFrequency.'</strong></td><td>'.$PayslipEmployees.' of '.$PayrollGroupEmployees.' |  Pending: '.$PendingPayslips.' Approved: '.$ApprovedPayslips. ' Paid : '.$PaidPayslips.'</td>
									<td><strong>'.$payroll_currency.' '.number_format($TotalNetPay,2).'</strong></td>
									<td><form id="bulkActionsForm" method="post">  <input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
											<select id="BulkOption" name="BulkOption" class="form-control">
													<option>Select Bulk action</option>
													<option value="approve_all">Approve All</option>
													<option value="approve_selected">Approve Selected</option>
													<option value="pay_all">Pay </option>

												</select>
												<input type="hidden" name="PaymentPeriodID" value="'.$PaymentPeriodID.'"/>
												<input type="hidden" name="PayrollGroupID" value="'.$PayrollGroupID.'"/>
												<input type="hidden" name="TotalNetPay" value="'.$TotalNetPay.'"/>
												<button >Go</button>
											</form>
									</td>
								</tr>
						</table></div></div></div></div><br />';
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
								 WHERE payroll_group_id='".$_POST['PayrollGroupID']."'";
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
			}


			echo '</table></div></div></div><br />';
		}
	}
	if(isset($_POST['BulkOption']))
	{

		$PaymentPeriodID = $_POST['PaymentPeriodID'];
		$PayrollGroupID = $_POST['PayrollGroupID'];
		//$TotalPayrollPay = $_POST['TotalNetPay'];
		$sql_net_pay = DB_query("SELECT sum(net_pay) as net_salaries FROM hremployeepayslips WHERE payslip_date_range_id='".$PaymentPeriodID."' AND payslip_status='approved'");
		$net_pay_row = DB_fetch_array($sql_net_pay);
		$TotalPayrollPay = $net_pay_row['net_salaries'];
		$sql_date_range = DB_query("SELECT start_date, end_date FROM hrpayslipdateranges WHERE daterange_id='".$PaymentPeriodID."'");
		$date_range_row = DB_fetch_array($sql_date_range);
		$start_date = $date_range_row['start_date'];
		$MonthOfPayment = date('F Y',strtotime($start_date));
		$DayOfPayment = date('d-m-Y',strtotime($start_date));

		if($_POST['BulkOption'] == "approve_selected")
		{
			$selected_payslips = $_POST['selected_payslips'];
			if(count($selected_payslips) == 0)
			{
				prnMsg( _('NONE SELECTED  :  you did not select any payslips to approve'), 'error');
				echo '<br />';
			}
			else if(count($selected_payslips) > 0)
			{
				//print_r($selected_payslips);
				$payslip_ids = implode (", ", $selected_payslips);
				$sql = "UPDATE hremployeepayslips
						SET payslip_status='approved',
								approver_id='".$_SESSION['UserID']."'
							WHERE payslip_id in(" . $payslip_ids . ") AND payslip_status ='pending' ";

					$ErrMsg = _('The payroll could not be updated because');
					$DbgMsg = _('The SQL that was used to update the payroll and failed was');
					$result = DB_query($sql,$ErrMsg,$DbgMsg);

					$no_of_updates = DB_affected_rows($result);

					prnMsg( _('Number of approvals : ') . ' ' . $no_of_updates . ' ' . _(' have been made'), 'success');
					echo '<br />';
			}
		}
		else if($_POST['BulkOption'] == "approve_all")
		{
			$sql = "UPDATE hremployeepayslips
					SET payslip_status='approved',
							approver_id='".$_SESSION['UserID']."'
						WHERE payslip_date_range_id='" . $PaymentPeriodID . "' AND payslip_status ='pending' ";

				$ErrMsg = _('The payroll could not be updated because');
				$DbgMsg = _('The SQL that was used to update the payroll and failed was');
				$result = DB_query($sql,$ErrMsg,$DbgMsg);

				$no_of_updates = DB_affected_rows($result);

				prnMsg( _('Number of approvals : ') . ' ' . $no_of_updates . ' ' . _(' have been made'), 'success');
				echo '<br />';
		}
		else if($_POST['BulkOption'] == "pay_all")
		{
			//check for general ledger permissions
			if(!in_array('10',$_SESSION['AllowedPageSecurityTokens']))
			{
				prnMsg( _('Unauthorized Access  :  you do not have access to this functionality'), 'error');
				echo '<br />';
			}
			else{
				//check if salaries have already been paid
				$checkSql = "SELECT count(*) FROM hremployeepayslips WHERE payslip_date_range_id='" . $PaymentPeriodID . "' AND payslip_status ='pending' ";
				$checkresult=DB_query($checksql);
				$checkrow=DB_fetch_row($checkresult);
				if ($checkrow[0]>0 )
				{
					prnMsg( _('Already Paid  :  the payroll has already been paid'), 'error');
					echo '<br />';
					exit(1);
				}
				// user has general ledger permissions, check bank account for balance
				$sql = DB_query("SELECT bank_account_to_use,gl_posting_account,currency FROM hrpayrollgroups WHERE payrollgroup_id = '".$PayrollGroupID."'");
				$result = DB_fetch_array($sql);
				$payroll_bank_account = $result['bank_account_to_use'];
				$gl_posting_account = $result['gl_posting_account'];
				$payroll_currency = $result['currency'];
				if($payroll_bank_account == NULL)
				{

					prnMsg( _('No bank account configured  :  Please configure a bank account for this payroll group or set default account for paying salaries'), 'error');
					echo '<br />';
					exit(1);
				}
				$sql = "SELECT sum(amount) as bank_balance FROM banktrans WHERE bankact ='".$payroll_bank_account."'";
				$ErrMsg = _('The bank account for payroll could not be retrieved');
				$DbgMsg = _('The SQL that was used to check bank account and failed was');
				$result = DB_query($sql,$ErrMsg,$DbgMsg);

				$myrow = DB_fetch_array($result);
				$bank_balance = $myrow['bank_balance'];
				if($bank_balance < $TotalPayrollPay)
				{
					prnMsg( _('Low Bank Balance  :  Please reconcile your bank account no: '.$payroll_bank_account.' for paying this payroll, it appears you may not have enough money to pay in this account'), 'error');
					echo '<br />';
				}
				else if($bank_balance > $TotalPayrollPay)
				{
						//change status to paid for payroll selected.
						//confirm posting accounts
						$sql_company_details = DB_query("SELECT currencydefault, payrollact FROM companies");
						$result_company_details = DB_fetch_array($sql_company_details);
						$default_currency = $result_company_details['currencydefault'];
						if($payroll_currency == NULL)
						{
							$payroll_currency = $default_currency;
						}

						$default_payroll_gl_account = $result_company_details['payrollact'];
						if($gl_posting_account == null)
						{
							$gl_posting_account = $default_payroll_gl_account;
						}


						$DatePaid = Date($_SESSION['DefaultDateFormat']);
						$Narrative = "Salaries for ".$MonthOfPayment."";
						$PeriodNo = GetPeriod($DatePaid,$db);
						$Cheque = 0;
						$Tag = 0;
						$PaymentType ="Direct Credit";
						$ExchangeRate = 1;
						//begin transactions
						$result = DB_Txn_Begin();
						$TransNo = GetNextTransNo( 1, $db);
						$TransType = 1;

						//1. First DO gl entry for payroll liabilities accounts
						$SQL = "INSERT INTO gltrans (
									type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount,
									chequeno,
									tag
								) VALUES (
									1,'" .
									$TransNo . "','" .
									FormatDateForSQL($DatePaid) . "','" .
									$PeriodNo . "','" .
									$gl_posting_account . "','" .
									$Narrative . "','" .
									$TotalPayrollPay . "','".
									$Cheque ."','" .
									$Tag .
								"')";
						$ErrMsg = _('Cannot insert a GL entry for the payment using the SQL');
						$result = DB_query($SQL,$ErrMsg,_('The SQL that failed was'),true);
						$gl_transaction_id = DB_Last_Insert_ID($db,'gltrans','counterindex');

						//2.  do GL transaction for the bank account - credit
						$SQL = "INSERT INTO gltrans (
									type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount
								) VALUES ('" .
									$TransType . "','" .
									$TransNo . "','" .
									FormatDateForSQL($DatePaid) . "','" .
									$PeriodNo . "','" .
									$payroll_bank_account . "','" .
									$Narrative . "','" .
									-$TotalPayrollPay .
								"')";
						$ErrMsg = _('Cannot insert a GL transaction for the bank account credit because');
						$DbgMsg = _('Cannot insert a GL transaction for the bank account credit using the SQL');
						$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
						EnsureGLEntriesBalance($TransType,$TransNo,$db);

						//3. do Bank transaction.
						$SQL = "INSERT INTO banktrans (
									transno,
									type,
									bankact,
									ref,
									exrate,
									functionalexrate,
									transdate,
									banktranstype,
									amount,
									currcode
								) VALUES ('" .
									$TransNo . "','" .
									$TransType . "','" .
									$payroll_bank_account . "','" .
									'@'._('Bank Withdraw : '). $Narrative . "','" .
									$ExchangeRate . "','" .
									$ExchangeRate . "','" .
									FormatDateForSQL($DatePaid) . "','" .
									$PaymentType . "','" .
									-$TotalNetPay . "','" .
									$payroll_currency .
								"')";
						$ErrMsg = _('Cannot insert a bank transaction because');
						$DbgMsg = _('Cannot insert a bank transaction using the SQL');
						$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

						/*******/


						// change status to paid
						$sql = "UPDATE hremployeepayslips
								SET payslip_status='paid',
										finance_transaction_id='".$gl_transaction_id."'
									WHERE payslip_date_range_id='" . $PaymentPeriodID . "' AND payslip_status ='approved' ";

							$ErrMsg = _('The payroll could not be updated because');
							$DbgMsg = _('The SQL that was used to update the payroll and failed was');
							$result = DB_query($sql,$ErrMsg,$DbgMsg);

						// update loan status for those employees that have loan to pay
						$sql = "SELECT employee_id,loan_deduction_amount FROM hremployeepayslips WHERE payslip_date_range_id='" . $PaymentPeriodID . "' AND payslip_status ='paid' AND loan_deduction_amount > 0";
						$ErrMsg = _('The payslips with loans to pay above 0 could not be retrieved because');
						$DbgMsg = _('The SQL that was used  and failed was');
						$result = DB_query($sql,$ErrMsg,$DbgMsg);
						while($myrow =DB_fetch_array($result))
						{
								$loan_status = 0;
								$loan_amount_paid = $myrow['loan_deduction_amount'];
								$employee_id = $myrow['employee_id'];
								$loan_sql = DB_query("SELECT loan_id,loan_amount FROM hremployeeloans WHERE employee_id='".$employee_id."'");
								$loan_result = DB_fetch_array($loan_sql);
								$loan_id = $loan_result['loan_id'];
								$total_loan_amount = $loan_result['loan_amount'];

								//update loan payments table
								$loan_payment_sql = "INSERT INTO hremployeeloanpayments(loan_id,amount_paid)
																		VALUES(
																			'".$loan_id."',
																			'".$loan_amount_paid."'
																		)";
								$ErrMsg = _('Cannot insert a loan payment because');
								$DbgMsg = _('Cannot insert a loan payment with the SQL');
								$loan_payment_result = DB_query($loan_payment_sql,$ErrMsg,$DbgMsg,true);

								$check_finished_loan_sql = DB_query("SELECT sum(amount_paid) as total_paid FROM hremployeeloanpayments WHERE loan_id='".$loan_id."'");
								$check_finished_loan_row = DB_fetch_array($check_finished_loan_sql);
								$total_loan_amount_paid = $check_finished_loan_row['total_paid'];

								if($total_loan_amount_paid >= $total_loan_amount)
								{
									// update status of loan to paid in EmployeeLoans
									$loan_status_sql = DB_query("UPDATE hremployeeloans
																			SET loan_status='1' WHERE loan_id='".$loan_id."'");
								}

						}

						DB_Txn_Commit();

						prnMsg(_('Payment for salieres with Gl transacton id:') . ' ' . $gl_transaction_id . ' ' . _('has been successfully entered'),'success');

				}


			}
		}
	}
include('includes/footer.php');
?>
