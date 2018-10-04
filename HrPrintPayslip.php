<?php

/* $Id: HrPrintPayslip.php 7751 2017-04-13 16:34:26Z raymond $*/

include('includes/session.php');

if (isset($_GET['PayslipNo'])) {
	$PayslipNo=$_GET['PayslipNo'];
} else {
	$PayslipNo='';
}

$FormDesign = simplexml_load_file($PathPrefix.'companies/'.$_SESSION['DatabaseName'].'/FormDesigns/Payslip.xml');

// Set the paper size/orintation
$PaperSize = $FormDesign->PaperSize;
$line_height=$FormDesign->LineHeight;
include('includes/PDFStarter.php');
$PageNumber=1;
$pdf->addInfo('Title', _('Payslip') );

if ($PayslipNo == 'Preview'){

}
else{
  $sql = "SELECT hremployees.*,finance_transaction_id,payslip_date_range_id,total_earnings,total_deductions,hremployeepayslips.net_pay,salary_structure_id
      FROM hremployeepayslips INNER JOIN hremployees
      ON hremployeepayslips.employee_id=hremployees.empid JOIN hremployeesalarystructures on hremployees.empid=hremployeesalarystructures.employee_id
      WHERE payslip_id='". $PayslipNo ."'";
  $EmpResult = DB_query($sql,_('Could not get the employee of the selected PAYSLIP'));
  $EmpRow = DB_fetch_array($EmpResult);

  $paygroup_period_id = $EmpRow['payslip_date_range_id'];
  $department_id = $EmpRow['employee_department'];
  $salaryStructureId = $EmpRow['salary_structure_id'];
  $sql_date_range = "SELECT start_date, payrollgroup_id,end_date FROM hrpayslipdateranges WHERE daterange_id='".$paygroup_period_id."'";
  $date_range_result = DB_query($sql_date_range,_('Could not get the date range of the selected PAYSLIP'));
  $date_range_row = DB_fetch_array($date_range_result);
  $start_date = $date_range_row['start_date'];
  $payroll_group_id = $date_range_row['payrollgroup_id'];
  $PayPeriod = date('F Y',strtotime($start_date));

  $department_sql = "SELECT description from departments WHERE departmentid='".$department_id."'";
  $department_result = DB_query($department_sql,_('Could not get the department of the selected PAYSLIP'));
  $department_row = DB_fetch_array($department_result);
  $DepartmentName = $department_row['description'];

  $payroll_group_sql = "SELECT hrpayrollgroups.* , frequency_name,working_days FROM hrpayrollgroups JOIN hrpaymentfrequency on hrpayrollgroups.payment_frequency=hrpaymentfrequency.paymentfrequency_id WHERE payrollgroup_id='".$payroll_group_id."'";
  $payroll_group_result = DB_query($payroll_group_sql,_('Could not get the date range of the selected PAYSLIP'));
  $PayrollGroupRow = DB_fetch_array($payroll_group_result);
  DB_data_seek($EmpResult,0);
  $salary_components_array = array();
  include ('includes/PDFPayslipHeader.inc'); //head up the page
  $sql = "SELECT hremployeesalarystructure_components.* ,payroll_category_code from hremployeesalarystructure_components JOIN hrpayrollcategories ON hremployeesalarystructure_components.payroll_category_id = hrpayrollcategories.payroll_category_id
  				WHERE salary_structure_id =".$salaryStructureId."";
  $result = DB_query($sql);
  while ($row = DB_fetch_array($result)) {
  	$salaryStructureCode = $row['payroll_category_code'];
  	$salary_components_array[$salaryStructureCode] = $row['amount'];
  }
  $sql= "SELECT payroll_category_name,payroll_category_type,payroll_category_code,payroll_category_value,payroll_category_type,additional_condition
         FROM hrpayroll_groups_payroll_categories JOIN hrpayrollgroups ON hrpayroll_groups_payroll_categories.payroll_group_id = hrpayrollgroups.payrollgroup_id JOIN hrpayrollcategories ON hrpayroll_groups_payroll_categories.payroll_category_id = hrpayrollcategories.payroll_category_id
         WHERE payroll_group_id='".$payroll_group_id."'";
         $ErrMsg = _('The payroll groups categories could not be retrieved because');
        $DbgMsg = _('The SQL used to retrieve payroll group categories and failed was');
        $result2 = DB_query($sql,$ErrMsg,$DbgMsg);
        $YPos = $Page_Height - $FormDesign->Data->y;
				$no_of_earnings = 0;
				$no_of_deductions = 0;
        while ($myrow=DB_fetch_array($result2)){

          $CategoryCodeValue = $myrow['payroll_category_code'];

          if($myrow['payroll_category_type'] == 1){
            $pdf->addText($FormDesign->Data->Column1->x, $Page_Height - $YPos, $FormDesign->Data->Column1->FontSize, $myrow['payroll_category_name']);
            $pdf->addText($FormDesign->Data->Column2->x, $Page_Height - $YPos, $FormDesign->Data->Column2->FontSize, $salary_components_array[$CategoryCodeValue]);
						$no_of_earnings++;
						}
          else if($myrow['payroll_category_type'] == 0){
          $pdf->addText($FormDesign->Data->Column3->x, $Page_Height - $YPos, $FormDesign->Data->Column3->FontSize, $myrow['payroll_category_name']);
          $pdf->addText($FormDesign->Data->Column4->x, $Page_Height - $YPos, $FormDesign->Data->Column4->FontSize, $salary_components_array[$CategoryCodeValue]);
						$no_of_deductions++;
					}
					// if($no_of_earnings < $no_of_deductions)
					// {
					// 	//print blank left column
					// 	$pdf->addText($FormDesign->Data->Column1->x, $Page_Height - $YPos, $FormDesign->Data->Column1->FontSize, _(''));
          //   $pdf->addText($FormDesign->Data->Column2->x, $Page_Height - $YPos, $FormDesign->Data->Column2->FontSize, _(''));
					// }
					// else if($no_of_deductions < $no_of_earnings)
					// {
					// 	//print blank left column
					// 	$pdf->addText($FormDesign->Data->Column3->x, $Page_Height - $YPos, $FormDesign->Data->Column3->FontSize, _(''));
          //   $pdf->addText($FormDesign->Data->Column4->x, $Page_Height - $YPos, $FormDesign->Data->Column3->FontSize, _(''));
					// }
					$YPos += $line_height;
        }
        /* Totals row*/
        $pdf->addText($FormDesign->Totals->Column1->x,$Page_Height - $FormDesign->Totals->Column1->y, $FormDesign->Totals->Column1->FontSize, _('Total Earnings') );
        $pdf->addText($FormDesign->Totals->Column2->x,$Page_Height - $FormDesign->Totals->Column2->y, $FormDesign->Totals->Column2->FontSize, $EmpRow['total_earnings'] );
        $pdf->addText($FormDesign->Totals->Column3->x,$Page_Height - $FormDesign->Totals->Column3->y, $FormDesign->Totals->Column3->FontSize, _('Total Deductions') );
        $pdf->addText($FormDesign->Totals->Column4->x,$Page_Height - $FormDesign->Totals->Column4->y, $FormDesign->Totals->Column4->FontSize, $EmpRow['total_deductions'] );

        /* net salary */
        $pdf->addText($FormDesign->NetSalary->x,$Page_Height- $FormDesign->NetSalary->y,$FormDesign->NetSalary->FontSize, _('Net Salary '). ' ' . $EmpRow['net_pay']);
  $pdf->OutputD($_SESSION['DatabaseName'] . '_PAYSLIP_' . $PayslipNo . '_' . date('Y-m-d').'.pdf');
  $pdf->__destruct();
}
