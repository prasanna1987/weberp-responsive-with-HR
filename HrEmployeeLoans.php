<?php

/* $Id: HrEmployeeLoans.php 7772 2018-04-07 09:30:06Z raymond $ */

include('includes/session.php');

$Title = _('Employee Loans');
$ViewTopic = 'EmployeeLoans';
$BookMark = 'Payroll';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/CountriesArray.php');


// BEGIN: General Ledger  array.
$GeneralLegderAccount = array();
$Query = "SELECT 	accountcode, accountname FROM chartmaster ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$GeneralLegderAccount[$Row['accountcode']] = $Row['accountname'];
}

// BEGIN: Bank accounts  array.
$BankAccount = array();
$Query = "SELECT 	accountcode, bankaccountname FROM bankaccounts ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$BankAccount[$Row['accountcode']] = $Row['bankaccountname'];
}


// BEGIN: Loan Types  array.
$LoanTypes = array();
$Query = "SELECT 	loan_type_id, loan_type_name FROM hremployeeloantypes ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$LoanTypes[$Row['loan_type_id']] = $Row['loan_type_name'];
}

// BEGIN: Employees  array.
$PayrollCategories = array();
$Query = "SELECT * FROM hrpayrollcategories ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$PayrollCategories[$Row['payroll_category_id']] = $Row['payroll_category_name'];
}


if (isset($_POST['SelectedLoan'])){
	$SelectedLoan = mb_strtoupper($_POST['SelectedLoan']);
} elseif (isset($_GET['SelectedLoan'])){
	$SelectedLoan = mb_strtoupper($_GET['SelectedLoan']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Employee Advance ') . '</h1></a></div>';


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (!is_numeric($_POST['LoanAmount']) or trim($_POST['LoanAmount']) == '') {
		$InputError = 1;
		prnMsg(_('The Loan Amount is not a valid number'),'error');
		$Errors[$i] = 'LoanAmount';
		$i++;
	}
	if (trim($_POST['LoanType'])==''){
		$InputError = 1;
		prnMsg(_('There are no loan types defined. create a loan type for salary loans,'),'error');
		$Errors[$i] = 'LoanType';
		$i++;
	}
	if ($_POST['EmpID'] == '0') {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You have not selected an employee'),'error');
		$Errors[$i] = 'EmpID';
		$i++;
	}
	if ($_POST['GeneralLegder'] == 'NULL') {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You have not entered a GL account'),'error');
		$Errors[$i] = 'GeneralLegder';
		$i++;
	}


	$checksql = "SELECT count(*)
		     FROM hremployeeloans
		     WHERE employee_id = '" . $_POST['EmpID'] . "' AND loan_status='0'";
	$checkresult=DB_query($checksql);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0 and !isset($SelectedLoan)) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('Employee still has an outstanding loan').' '.$_POST['EmpID'],'error');
		$Errors[$i] = 'EmpID';
		$i++;
	}

	if (isset($SelectedLoan) AND $InputError !=1) {

		$sql = "UPDATE hremployeeloans
			SET employee_id= '" . $_POST['EmpID'] . "',
			loan_amount = '" . $_POST['LoanAmount']. "',
			number_of_installments = '" . $_POST['Installments'] . "',
			amount_per_installment= '" . $_POST['InstallmentAmount']. "',
			loan_type= '" . $_POST['LoanType']. "',
			bank_account_to_use= '" . $_POST['BankAccount']. "',
			gl_posting_account= '" . $_POST['GeneralLegder']. "'
			WHERE loan_id  = '" .$SelectedLoan."'";

		$msg = _('The Employee loan') . ' ' . $SelectedLoan. ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the employee has unpaid loan

		$checkSql = "SELECT loan_amount
			     FROM hremployeeloans
			     WHERE employee_id = '" . $_POST['EmpID'] . "' AND loan_status ='0'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_array($checkresult);
		$checkAcceptableLoan = DB_query("SELECT net_pay from hremployeesalarystructures WHERE employee_id='".$_POST['EmpID']."'");
		$checkNet = DB_fetch_array($checkAcceptableLoan);
		if ( DB_num_rows($checkresult) > 0 ) {
			$InputError = 1;
			prnMsg( _('The Employee still has a loan ') . ' ' . $_checkrow['loan_amount'] . _(' to pay.'),'error');
		}
		else if($_POST['InstallmentAmount'] > $checkNet['net_pay']) {
			$InputError = 1;
			prnMsg( _('The Payable Installment cannot be more than the Net Salary of ') . ' ' . $_checkNet['net_pay'] . _(' of the employee.'),'error');
		}else {

			// Add new record on submit

			$sql = "INSERT INTO hremployeeloans
						(employee_id,
							loan_type,
							loan_amount,
							number_of_installments,
							amount_per_installment,
							bank_account_to_use,
							gl_posting_account )
					VALUES ('" . $_POST['EmpID'] . "',
					'" .$_POST['LoanType']. "',
					'" . $_POST['LoanAmount']. "',
					'" . $_POST['Installments'] . "',
					'" . $_POST['InstallmentAmount']. "',
					'" . $_POST['BankAccount'] . "',
					'" . $_POST['GeneralLegder'] . "'
					)";

			$msg = _('Loan Request for Emp ID') . ' ' . $_POST["EmpID"] .  ' ' . _('has been created');

			$checkSql = "SELECT count(loan_id )
			     FROM hremployeeloans";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);


		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
	$result = DB_query($sql);
	if(!isset($SelectedLoan)){
		//this is an insert

		$loan_id = DB_Last_Insert_ID($db,'hremployeeloans','loan_id');
		//check if approve and paying
		if(isset($_POST['Approved']) && $_POST['Approved'] == 1)
		{
			// user has general ledger permissions, check bank account for balance
			$sql = DB_query("SELECT bank_account_to_use,gl_posting_account,loan_amount,hremployees.employee_id FROM hremployeeloans JOIN hremployees ON hremployeeloans.employee_id=hremployees.empid WHERE loan_id = '".$loan_id."'");
			$result = DB_fetch_array($sql);
			$payroll_bank_account = $result['bank_account_to_use'];
			$gl_posting_account = $result['gl_posting_account'];
			$loan_amount = $result['loan_amount'];
			$employee_id = $result['employee_id'];
			if($payroll_bank_account == NULL)
			{

				prnMsg( _('No bank account configured  :  Please configure a bank account for this loan or set default account for paying salaries'), 'error');
				echo '<br />';
				exit(1);
			}
			$sql = "SELECT sum(amount) as bank_balance FROM banktrans WHERE bankact ='".$payroll_bank_account."'";
			$ErrMsg = _('The bank account for payroll could not be retrieved');
			$DbgMsg = _('The SQL that was used to check bank account and failed was');
			$result = DB_query($sql,$ErrMsg,$DbgMsg);

			$myrow = DB_fetch_array($result);
			$bank_balance = $myrow['bank_balance'];
			if($bank_balance < $loan_amount)
			{
				prnMsg( _('Low Bank Balance  :  Please reconcile your bank account for paying this advance, it appears you may not have enough money to pay in this account'), 'error');
				echo '<br />';
			}
			else if($bank_balance > $loan_amount)
			{
					//change status to paid for payroll selected.
					//confirm posting accounts
					$sql_company_details = DB_query("SELECT currencydefault, payrollact FROM companies");
					$result_company_details = DB_fetch_array($sql_company_details);
					$default_currency = $result_company_details['currencydefault'];
					$payroll_currency = $default_currency;
					$default_payroll_gl_account = $result_company_details['payrollact'];
					if($gl_posting_account == null)
					{
						$gl_posting_account = $default_payroll_gl_account;
					}


					$DatePaid = Date($_SESSION['DefaultDateFormat']);
					$Narrative = "Salary Advance for: ".$employee_id."";
					$PeriodNo = GetPeriod($DatePaid,$db);
					$Cheque = 0;
					$Tag = 0;
					$Paymenttype ="Direct Credit";
					$ExchangeRate = 1;
					//begin transactions
					$result = DB_Txn_Begin();
					$TransNo = GetNextTransNo( 1, $db);
					$TransType = 1;

					//insert into gl accounts
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
								$loan_amount . "','".
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
								-$loan_amount .
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
								$TransNo . "',
								1,'" .
								$payroll_bank_account . "','" .
								'@'._('Bank Withdraw : '). $Narrative . "','" .
								$ExchangeRate. "','" .
								$ExchangeRate . "','" .
								FormatDateForSQL($DatePaid) . "','" .
								$Paymenttype . "','" .
								-$loan_amount . "','" .
								$payroll_currency .
							"')";
					$ErrMsg = _('Cannot insert a bank transaction because');
					$DbgMsg = _('Cannot insert a bank transaction with the SQL');
					$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);


					// change status to paid
					$sql = "UPDATE hremployeeloans
									SET is_approved='1',
									approved_by ='".$_SESSION['UserID']."',
									finance_transaction_id='".$gl_transaction_id."'
								WHERE loan_id='" . $loan_id . "' AND loan_status ='0' ";

						$ErrMsg = _('The loan could not be updated because');
						$DbgMsg = _('The SQL that was used to update the loan and failed was');
						$result = DB_query($sql,$ErrMsg,$DbgMsg);

					DB_Txn_Commit();

					prnMsg(_('Advance Payment for employee  with Gl transacton id:') . ' ' . $gl_transaction_id . ' ' . _('has been successfully entered'),'success');

			}
		}


	}else {
		// this is an update

		$loan_id = $SelectedLoan;
		$sql = DB_query("SELECT is_approved,bank_account_to_use,gl_posting_account,loan_amount,hremployees.employee_id FROM hremployeeloans JOIN hremployees ON hremployeeloans.employee_id=hremployees.empid WHERE loan_id = '".$loan_id."'");
		$result = DB_fetch_array($sql);
		//check if approve and paying
		if($_POST['Approved'] = 1 && $result['is_approved'] == '0')
		{
			$payroll_bank_account = $result['bank_account_to_use'];
			$gl_posting_account = $result['gl_posting_account'];
			$loan_amount = $result['loan_amount'];
			$employee_id = $result['employee_id'];
			if($payroll_bank_account == NULL)
			{

				prnMsg( _('No bank account configured  :  Please configure a bank account for this loan or set default account for paying salaries'), 'error');
				echo '<br />';
				exit(1);
			}
			$sql = "SELECT sum(amount) as bank_balance FROM banktrans WHERE bankact ='".$payroll_bank_account."'";
			$ErrMsg = _('The bank account for payroll could not be retrieved');
			$DbgMsg = _('The SQL that was used to check bank account and failed was');
			$result = DB_query($sql,$ErrMsg,$DbgMsg);

			$myrow = DB_fetch_array($result);
			$bank_balance = $myrow['bank_balance'];
			if($bank_balance < $loan_amount)
			{
				prnMsg( _('Low Bank Balance  :  Please reconcile your bank account for paying this advance, it appears you may not have enough money to pay in this account'), 'error');
				echo '<br />';
			}
			else if($bank_balance > $loan_amount)
			{
					//change status to paid for payroll selected.
					//confirm posting accounts
					$sql_company_details = DB_query("SELECT currencydefault, payrollact FROM companies");
					$result_company_details = DB_fetch_array($sql_company_details);
					$default_currency = $result_company_details['currencydefault'];
					$payroll_currency = $default_currency;
					$default_payroll_gl_account = $result_company_details['payrollact'];
					if($gl_posting_account == null)
					{
						$gl_posting_account = $default_payroll_gl_account;
					}


					$DatePaid = Date($_SESSION['DefaultDateFormat']);
					$Narrative = "Salary Advance for: ".$employee_id."";
					$PeriodNo = GetPeriod($DatePaid,$db);
					$Cheque = 0;
					$Tag = 0;
					$Paymenttype ="Direct Credit";
					$ExchangeRate = 1;
					//begin transactions
					$result = DB_Txn_Begin();
					$TransNo = GetNextTransNo( 1, $db);
					$TransType = 1;

					//insert into gl accounts
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
								$loan_amount . "','".
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
								-$loan_amount .
							"')";
					$ErrMsg = _('Cannot insert a GL transaction for the bank account credit because');
					$DbgMsg = _('Cannot insert a GL transaction for the bank account credit using the SQL');
					$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
					EnsureGLEntriesBalance($TransType,$TransNo,$db);

					// 3. do bank transaction
					$PaymentTransNo = GetNextTransNo( 1, $db);
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
								$TransNo . "',
								1,'" .
								$payroll_bank_account . "','" .
								'@'._('Bank Withdraw : '). $Narrative . "','" .
								$ExchangeRate. "','" .
								$ExchangeRate . "','" .
								FormatDateForSQL($DatePaid) . "','" .
								$Paymenttype . "','" .
								-$loan_amount . "','" .
								$payroll_currency .
							"')";
					$ErrMsg = _('Cannot insert a bank transaction because');
					$DbgMsg = _('Cannot insert a bank transaction with the SQL');
					$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);


					// change status to paid
					$sql = "UPDATE hremployeeloans
									SET is_approved='1',
									approved_by ='".$_SESSION['UserID']."',
									finance_transaction_id='".$gl_transaction_id."'
								WHERE loan_id='" . $loan_id . "' AND loan_status ='0' ";

						$ErrMsg = _('The loan could not be updated because');
						$DbgMsg = _('The SQL that was used to update the loan and failed was');
						$result = DB_query($sql,$ErrMsg,$DbgMsg);

					DB_Txn_Commit();

					prnMsg(_('Advance Payment for employee  with Gl transacton id:') . ' ' . $gl_transaction_id . ' ' . _('has been successfully entered'),'success');

			}
		}
	}




	// Fetch the default Category list.
			$DefaultGROUPName = $_SESSION['DEFAULTGROUPName'];

	// Does it exist
		$checkSql = "SELECT count(*)
			     FROM hremployeeloans
			     WHERE loan_id  = '" . $DefaultGROUPName . "'"
					 ;
		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

	// If it doesnt then update config with newly created one.
		if ($checkrow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='" . $_POST['loan_id '] . "'
						WHERE confname='DEFAULTGROUPName'";
			$result = DB_query($sql);
			$_SESSION['DEFAULTGROUPName'] = $_POST['loan_id '];
		}
		echo '<br />';
		prnMsg($msg,'success');

		unset($SelectedName);
		unset($_POST['payroll_category_id']);
		unset($_POST['CategoryName']);
		unset($_POST['CategoryCode']);
		unset($_POST['CategoryValue']);
		unset($_POST['CategoryType']);
		unset($_POST['AdditionalCondition']);
		unset($_POST['BankAccount']);
	unset($_POST['GeneralLegder']);

	}

} elseif ( isset($_GET['delete']) ) {

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'employeeloans'


	$sql= "SELECT COUNT(*)
	       FROM hremployeeloanpayments
	       WHERE loan_id ='".$SelectedLoan."'";

	$ErrMsg = _('The number of loan payments could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg(_('Cannot delete this loan because Payments have been made') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('Payments made'),'error');

	}

	 else {
			$result = DB_query("SELECT loan_id,employee_id,is_approved FROM hremployeeloans WHERE loan_id ='".$SelectedLoan."'");
			if (DB_Num_Rows($result)>0){
				$NameRow = DB_fetch_array($result);
				$CategoryName = $NameRow['loan_id'];
				if($NameRow['is_approved'] == 1)
				{
					prnMsg(_('Cannot delete this loan because it is already approved' ),'error');
					exit();
				}
				else {
					$sql="DELETE FROM hremployeeloans WHERE loan_id ='".$SelectedLoan."'";
					$ErrMsg = _('The loan record could not be deleted because');
					$result = DB_query($sql,$ErrMsg);
					echo '<br />';
					prnMsg(_('Loan') . ' ' . $CategoryName  . ' ' . _('has been deleted') ,'success');

				}

			}
			unset ($SelectedLoan);
			unset($_GET['delete']);

	} //end if Positions used in Employees set up
}

if (!isset($SelectedLoan)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedPayrollCategory will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of sales types will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT hremployeeloans.*,hremployees.employee_id as employee_number,first_name,last_name
	  FROM hremployeeloans JOIN hremployees on hremployeeloans.employee_id=hremployees.empid WHERE loan_status='0'";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<thead><tr>
	<th class="ascending">' . _('Employee') . '</th>
 <th class="ascending">' . _('Loan type') . '</th>
 <th class="ascending">' . _('Date requested') . '</th>
 <th class="ascending">' . _('Loan Amount') . '</th>
 <th class="ascending">' . _('no of salaries to deduct') . '</th>
<th class="ascending">' . _('status') . '</th>
<th class="ascending">' . _('Paid so far') . '</th>

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
//get amout paid so Far.
$sql_paid = DB_query("SELECT sum(amount_paid) as paid_so_far FROM hremployeeloanpayments WHERE loan_id='".$myrow['loan_id']."'");
$sql_paid_row = DB_fetch_array($sql_paid);
$loan_paid_so_far = $sql_paid_row['paid_so_far'];
printf('<td>%s</td>
		<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>

		<td><a href="%sSelectedLoan=%s" class="btn btn-info">' . _('%s') . '</a></td>
		<td><a href="%sSelectedLoan=%s&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this loan ?') . '");\'>' . _('Delete') . '</a></td>
		</tr>',
		$myrow['first_name'].' '.$myrow['last_name'].'('.$myrow['employee_number'].')',
		$LoanTypes[$myrow['loan_type']],
		$myrow['created_at'],
		$myrow['loan_amount'],
		$myrow['number_of_installments'],
		($myrow['is_approved'] == 1) ? 'Approved' : 'Not Approved',
		$loan_paid_so_far,
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?' ,
		($myrow['is_approved'] == 0) ? $myrow['loan_id'] : '#',($myrow['is_approved'] == 0) ? 'Edit' : '',
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
		$myrow['loan_id']);
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}

//end of ifs and buts!
if (isset($SelectedLoan)) {

	echo '<div class="row" align="center"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Loans') . '</a></div><br />';
}
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		';

	// The user wish to EDIT an existing name
	if ( isset($SelectedLoan) AND $SelectedLoan!='' ) {

		$sql = "SELECT *
		        FROM hremployeeloans
		        WHERE loan_id ='".$SelectedLoan."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['loan_id'] = $myrow['loan_id'];
		$_POST['EmpID']  = $myrow['employee_id'];
		$_POST['LoanType']  = $myrow['loan_type'];
		$_POST['LoanAmount']  = $myrow['loan_amount'];
		$_POST['Approved']  = $myrow['is_approved'];
$_POST['InstallmentAmount']  = $myrow['amount_per_installment'];
	$_POST['BankAccount']  = $myrow['bank_account_to_use'];
$_POST['GeneralLegder']  = $myrow['gl_posting_account'];
		echo '<input type="hidden" name="SelectedLoan" value="' . $SelectedLoan . '" />
			<input type="hidden" name="loan_id " value="' . $_POST['loan_id'] . '" />
			<div class="row">';

		// We dont allow the user to change an existing Name code

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Loan ID') . '</label> ' . $SelectedLoan . '</div>
			</div></div>';
	} else 	{
		// This is a new Name so the user may volunteer a Name code
		echo '';
	}

	if (!isset($_POST['GroupName'])) {
		$_POST['GroupName']='';
	}
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Employee') . '</label>
			<select ' . (in_array('EmpID',$Errors) ?  'class="inputerror"' : '' ) .' name="EmpID" class="form-control"><option value="0">Select employee</option>';

	$sql = "SELECT empid,employee_id, first_name, last_name FROM hremployees";
	$ErrMsg = _('The Managers could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve managers and failed was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);

	while ($myrow=DB_fetch_array($result)){
		if ($myrow['empid']==$_POST['EmpID']){
			echo '<option selected="selected" value="'. $myrow['empid'] . '">' . $myrow['first_name'] . ' '.$myrow['last_name'].'('.$myrow['employee_id'].')</option>';
		} else {
			echo '<option value="'. $myrow['empid'] . '">' . $myrow['first_name'] . ' '.$myrow['last_name'].'('.$myrow['employee_id'].')</option>';
		}
		$manager=$myrow['employee_id'];
	}
	echo '</select></div></div>';

		// General Legder  input.
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Loan Type') .
			'</label>
			<select id="PaymentFrequency" name="LoanType" class="form-control">';
		foreach ($LoanTypes as $LoanType => $Row) {

			echo '<option';
			if (isset($_POST['LoanType']) and $_POST['LoanType']==$LoanType) {
				echo ' selected="selected"';
			}
			echo ' value="' . $LoanType . '">' . $Row . '</option>';
		}
		echo '</select> </div></div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Loan Amount') . '</label>
				<input ' . (in_array('LoanAmount',$Errors) ?  'class="inputerror"' : '' ) .' id="LoanAmount" class="form-control" type="text" name="LoanAmount"  required="required" title="' . _('The Loan Amount') . '" value="' . $_POST['LoanAmount'] . '" /></div>
			</div></div>';
			echo '<div class="row">
					<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('No of Salaries to Deduct from') . ': (Installments)</label>
					<select id="Installments" name="Installments" class="form-control">';
					for($i=1;$i<13;$i++)
					{
							echo '<option value="'.$i.'" '._(($i==$_POST['Installments'])? 'selected' : '').'>'.$i.'</option>';
					}
	echo '</select></div>
			</div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Installment Amount') . '</label>
			<input id="InstallmentAmount" class="form-control" readonly type="text" name="InstallmentAmount"  value="' . $_POST['InstallmentAmount'] . '" /></div>
				</div>
			<div class="col-xs-3" style="margin-left:35px;">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Approve and Pay?') .
			  '</label><div class="checkbox"><input type="radio"';

			  if (isset($_POST['Approved']) and $_POST['Approved']==1) {
			    echo ' checked';}
			echo'
			   name="Approved" id ="Approved" value="1"> Yes
</div><div class="checkbox">
			  <input';
				if (!isset($SelectedLoan)) {
				 echo ' checked';}

			  if (isset($_POST['Approved']) and $_POST['Approved']==0) {
			    echo ' checked';
			  }
			echo'

			  type="radio" name="Approved"  id ="Approved" value="0"> Not Now
			  </div></div></div></div>
<script>
		$( document ).ready(function() {
				$("#LoanAmount").change(function(){
						var loan_amount = $(this).val();
						var installments = $("#Installments").val();

						var installment_amount = loan_amount/installments;
						$("#InstallmentAmount").val(installment_amount);

				});
				$("#Installments").change(function(){
					var installments = $(this).val();
					var loan_amount = $("#LoanAmount").val();
					var installment_amount = loan_amount/installments;
					$("#InstallmentAmount").val(installment_amount);
				});
		});
</script>

				';


					// Bank Accounts.
					echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Bank Account') .
						'</label><select id="BankAccount" name="BankAccount" class="form-control">';
					foreach ($BankAccount as $AccountCode => $Row) {

						echo '<option';
						if (isset($_POST['BankAccount']) and $_POST['BankAccount']==$AccountCode) {
							echo ' selected="selected"';
						}
						echo ' value="' . $AccountCode . '">' . $Row . '</option>';
					}
					echo '</select> </div></div>';
					// General Legder  input.
					echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('General Legder Posting Account') .
						'</label>
						<select id="GeneralLegder" name="GeneralLegder" class="form-control">';
							echo'<option value="NULL">NONE </option>';
					foreach ($GeneralLegderAccount as $AccountCode => $Row) {

						echo '<option';
						if (isset($_POST['GeneralLegder']) and $_POST['GeneralLegder']==$AccountCode) {
							echo ' selected="selected"';
						}
						echo ' value="' . $AccountCode . '">' . $Row . '</option>';
					}
					echo '</select> </div></div>

		<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" class="btn btn-success" name="submit" value="' . _('Submit') . '" />
		</div>
	</div>
	</div>
	<br />
	</form>';

} // end if user wish to delete

include('includes/footer.php');
?>
