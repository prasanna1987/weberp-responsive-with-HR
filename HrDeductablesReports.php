<?php
/* $Id: Tax.php 7751 2017-04-13 16:34:26Z rchacon $*/

include('includes/session.php');

if(isset($_POST['PayrollDeductables']) AND
	isset($_POST['PrintPDF']) AND
	isset($_POST['NoOfPeriods']) AND
	isset($_POST['ToPeriod'])) {


  }
  else if(isset($_POST['ShowHtml']))
  {
    //print report on screen
    $Title =_('Payroll Deductables Report');
    $ViewTopic = 'Payroll';// Filename in ManualContents.php's TOC.
    $BookMark = 'Deductables';// Anchor's id in the manual's html document.
    include('includes/header.php');
    echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Payroll Reporting') . '</h1></a></div>';
    echo '<div class="row" align="center"><a href="'. $RootPath . '/HrDeductablesReports.php" class="btn btn-info">' . _('Back to Selection') . '</a></div><br />';
    if($_POST['DetailOrSummary'] == 'Summary')
    {
      //get the payroll Deductables
      if(!isset($_POST['PayrollDeductables']))
      {
        prnMsg(_('You must select at least one option'),'error');
        exit(1);
      }
      $payroll_categories_array = array();
      foreach($_POST['PayrollDeductables'] as $payroll_category)
      {
          $payroll_categories_array[] = $payroll_category;
      }
      $payroll_category_ids = implode(",",$payroll_categories_array);
      $payroll_categories_array = array();

      //get the $Periods
      $start_date = date('Y-m-d',strtotime($_POST['ToPeriod']));
      $no_of_months = $_POST['NoOfPeriods']. " months";
      $end_date = date('Y-m-d', strtotime($no_of_months, strtotime($start_date)));
      $daterange_ids_array = array();
      $sql = DB_query("SELECT daterange_id,start_date,payrollgroup_id FROM hrpayslipdateranges WHERE (start_date BETWEEN '".$start_date."' AND '".$end_date."') ");

      while($myrow=DB_fetch_array($sql))
      {

        $daterange_ids_array[] = $myrow['daterange_id'];
      }

      $daterange_ids = implode(",",$daterange_ids_array);

      $sql = DB_query("SELECT payslip_id,payslip_date_range_id FROM hremployeepayslips where payslip_status='paid' and payslip_date_range_id in(".$daterange_ids.")");
      $payslip_ids_array = array();
      while($myrow = DB_fetch_array($sql))
      {
        $payslip_ids_array[]= $myrow['payslip_id'];
      }
      $payslip_ids = implode(",",$payslip_ids_array);
      $formated_start_date = date('d-M-Y',strtotime($start_date));
      $formated_end_date = date('d-M-Y',strtotime($end_date));
      $sql = DB_query("SELECT sum(amount) as total_amount,payroll_category_id FROM hrpayslipcategorydetails where payslip_id in(".$payslip_ids.") and payroll_category_id in (".$payroll_category_ids.") group by(payroll_category_id)");
      echo "<h2>Summary Report for Period From: {$formated_start_date} to {$formated_end_date} </h2>";
      echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
            <tr>
              <th>Name</th>
              <th>Total Amount</th>
            </tr>
            ';
      while($myrow=DB_fetch_array($sql))
      {
        $category_name_sql = DB_query("SELECT payroll_category_name from hrpayrollcategories where payroll_category_id='".$myrow['payroll_category_id']."'");
        $category_result  = DB_fetch_array($category_name_sql);
        $category_name = $category_result['payroll_category_name'];
        $total_amount = $myrow['total_amount'];
        echo '<tr>
                <td>'.$category_name.'</td><td>'.number_format($total_amount,2).'
        </tr>';
      }

      echo "</table></div></div></div>";
    }
    else if($_POST['DetailOrSummary'] == 'Detail')
    {
      if(!isset($_POST['PayrollDeductables']))
      {
        prnMsg(_('You must select at least one option'),'error');
        exit(1);
      }
      $payroll_categories = array();
      foreach($_POST['PayrollDeductables'] as $payroll_category)
      {
          $payroll_categories[] = $payroll_category;
      }
      $payroll_category_ids = implode(",",$payroll_categories);
      $sql = DB_query("SELECT payroll_category_id,payroll_category_code,payroll_category_name FROM hrpayrollcategories WHERE payroll_category_id in(".$payroll_category_ids.")");
      $payroll_categories_array = array();
      while($myrow = DB_fetch_array($sql))
      {
          $payroll_category_name = $myrow['payroll_category_name'];
          $payroll_category_id = $myrow['payroll_category_id'];
          array_push($payroll_categories_array,['payroll_category_name'=>$payroll_category_name,'payroll_category_id'=>$payroll_category_id]);

      }
      //get the $Periods
      $start_date = date('Y-m-d',strtotime($_POST['ToPeriod']));
      $no_of_months = $_POST['NoOfPeriods']. " months";
      $end_date = date('Y-m-d', strtotime($no_of_months, strtotime($start_date)));
      $daterange_ids_array = array();
      $sql = DB_query("SELECT daterange_id,start_date,payrollgroup_id FROM hrpayslipdateranges WHERE (start_date BETWEEN '".$start_date."' AND '".$end_date."') ");

      while($myrow=DB_fetch_array($sql))
      {

        $daterange_ids_array[] = $myrow['daterange_id'];
      }

      $daterange_ids = implode(",",$daterange_ids_array);

      $sql = DB_query("SELECT payslip_id,payslip_date_range_id FROM hremployeepayslips where payslip_status='paid' and payslip_date_range_id in(".$daterange_ids.")");
      $payslip_ids_array = array();
      while($myrow = DB_fetch_array($sql))
      {
        $payslip_ids_array[]= $myrow['payslip_id'];
      }

      $payslip_ids = implode(",",$payslip_ids_array);
      $other_payslip_ids_array = array();
      $sql = DB_query("SELECT payslip_id FROM hrpayslipcategorydetails where payroll_category_id in(".$payroll_category_ids.")");
      while($row=DB_fetch_array($sql))
      {
        $other_payslip_ids_array[] = $row['payslip_id'];
      }
      $filtered_payslips_array = array_unique(array_merge($payslip_ids_array,$other_payslip_ids_array));
      $filtered_payslip_ids = implode(",",$filtered_payslips_array);
      //get employee DetailOrSummary
      $sql = "SELECT hremployeepayslips.*, first_name,middle_name,last_name,employee_department FROM hremployeepayslips JOIN hremployees on hremployeepayslips.employee_id = hremployees.empid
      WHERE payslip_id in(".$filtered_payslip_ids.")";

      $result = DB_query($sql);
      while($myrow = DB_fetch_array($result))
      {

        $employee_array = array();
        $employee_name = $myrow['first_name']." ".$myrow['middle_name']." ".$myrow['last_name'];
        $department_id = $myrow['employee_department'];
        $payslip_id = $myrow['payslip_id'];
        $gross_salary = $myrow['gross_pay'];
        $net_pay = $myrow['net_pay'];
        $employee_id = $myrow['employee_id'];
        $date_range_id = $myrow['payslip_date_range_id'];



        $earnings_array = array();
        $deductions_array = array();
        $sql2 = "SELECT hrpayslipcategorydetails.* ,payroll_category_code,payroll_category_name,payroll_category_type from hrpayslipcategorydetails JOIN hrpayrollcategories ON hrpayslipcategorydetails.payroll_category_id = hrpayrollcategories.payroll_category_id
                WHERE payslip_id ='".$payslip_id."'";
        $result_components = DB_query($sql2);
        while ($myrow2 = DB_fetch_array($result_components)) {
          if($myrow2['payroll_category_type'] == 0){
            $payroll_category_amount = $myrow2['amount'];
            $payroll_category_name = $myrow2['payroll_category_name'];
            array_push($deductions_array,['amount'=>$payroll_category_amount,'payroll_category_name'=>$payroll_category_name]);
          }
        }
        $sql3 = DB_query("SELECT start_date FROM hrpayslipdateranges where daterange_id='".$date_range_id."'");
        $result_date_range = DB_fetch_array($sql3);
        $MonthOfPayment = date('M Y',strtotime($result_date_range['start_date']));

        //add to payroll Array
        $employee_array['employee_id'] = $employee_id;
        $employee_array['payslip_id'] = $payslip_id;
        $employee_array['employee_name'] = $employee_name;
        $employee_array['employee_department'] = $department_id;
        $employee_array['employee_deductions'] = $deductions_array;
        $employee_array['payment_month'] = $MonthOfPayment;

        $payroll_array[] = $employee_array;
      }


      $formated_start_date = date('d-M-Y',strtotime($start_date));
      $formated_end_date = date('d-M-Y',strtotime($end_date));
      echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
          <tr>

            <th class="ascending">', _('Period'), '</th>
            <th class="ascending">', _('Employee ID'), '</th>
            <th class="ascending">', _('Department'), '</th>';
            foreach($payroll_categories_array as $deductions)
            {

                  echo '<td>'.$deductions['payroll_category_name'].'</td>';

            }

            echo '<th class="ascending"> Total Amount</th>
          </tr>';

          foreach($payroll_array as $index => $employee_payslip){
            $sql2 ="SELECT departmentid,description FROM departments WHERE departmentid =".$employee_payslip['employee_department']."";
            $result2 = DB_query($sql2);
            $deparmentDetails = DB_fetch_array($result2);
            echo '<tr >
                  <td>'.$employee_payslip['payment_month'].'</td>
                  <td>'.$employee_payslip['employee_name'].'</td>
                  <td>'.$deparmentDetails['description'].'</td>';
                  foreach($employee_payslip['employee_deductions'] as $deductions)
                  {
                      echo '<td>'.number_format($deductions['amount'],2).'</td>';
                  }
                  $total = array_sum(array_column($employee_payslip['employee_deductions'],'amount'));
            echo'	<td>'.number_format($total,2).'</td>
              </tr>';
          }



        echo '</table></div></div></div><br />';

    }
  }
  else{
    /*The option to print PDF or ShowHtml was not hit */

    	$Title =_('Payroll Deductables Reporting');
    	$ViewTopic = 'Payroll';// Filename in ManualContents.php's TOC.
    	$BookMark = 'Deductables';// Anchor's id in the manual's html document.
    	include('includes/header.php');
    	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Payroll Reporting') . '</h1></a></div>';

    	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
    
    	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    	echo '<div class="row">';

    	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Payroll Deductables to Report On') . '</label>
    			<select name="PayrollDeductables[]" class="form-control" multiple >';

    	$result = DB_query("SELECT payroll_category_id,payroll_category_name,payroll_category_code,payroll_category_type FROM hrpayrollcategories");
    	while($myrow = DB_fetch_array($result)) {
        if($myrow['payroll_category_type'] == 0) {
    		echo '<option value="' . $myrow['payroll_category_id'] . '">' . $myrow['payroll_category_name'] . '</option>';
      }
    	}
    	echo '</select></div></div>';
    	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Return Covering') . '</label>
    			<select name="NoOfPeriods" class="form-control">' .
    			'<option selected="selected" value="1">' . _('One Month') . '</option>' .
    			'<option value="2">' . _('2 Months') . '</option>' .
    			'<option value="3">' . _('3 Months') . '</option>' .
    			'<option value="6">' . _('6 Months') . '</option>' .
    			'<option value="12">' . _('12 Months') . '</option>' .
    			'<option value="24">' . _('24 Months') . '</option>' .
    			'<option value="48">' . _('48 Months') . '</option>' .
    			'</select></div>
    		</div>';

    	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Starting From') . '</label>
    			<select name="ToPeriod" class="form-control">';

    	$DefaultPeriod = GetPeriod(Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m'),0,Date('Y'))),$db);

    	$sql = "SELECT daterange_id,start_date,
    			end_date
    		FROM hrpayslipdateranges order by start_date asc";

    	$ErrMsg = _('Could not retrieve the period data because');
    	$Periods = DB_query($sql,$ErrMsg);

    	while($myrow = DB_fetch_array($Periods,$db)) {
    		if($myrow['periodno']==$DefaultPeriod) {
    			echo '<option selected="selected" value="' . $myrow['start_date'] . '">' . $myrow['start_date'] . '</option>';
    		} else {
    			echo '<option value="' . $myrow['start_date'] . '">' . ConvertSQLDate($myrow['start_date']) . '</option>';
    		}
    	}

    	echo '</select></div>
    		</div></div>
    		<div class="row">
    			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Detail Or Summary Only') . '</label>
    			<select name="DetailOrSummary" class="form-control">
    				<option value="Detail">' . _('Detail and Summary') . '</option>
    				<option selected="selected" value="Summary">' . _('Summary Only') . '</option>
    			</select></div>
    		</div>
    		<div class="col-xs-4">
<div class="form-group"> <br />
         
    			<input type="submit" class="btn btn-warning" name="PrintPDF" value="' . _('Print PDF') . '" />
    		</div>
    		</div>
			</div><br />
    		</form>';

    	include('includes/footer.php');
    } /*end of else not PrintPDF */
