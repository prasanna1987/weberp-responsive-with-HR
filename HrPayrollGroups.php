<?php

/* $Id: HrPayrollCategories.php 7772 2018-04-07 09:30:06Z bagenda $ */

include('includes/session.php');

$Title = _('Payroll Groups');

include('includes/header.php');



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


// BEGIN: General Ledger  array.
$PaymentFrequency = array();
$Query = "SELECT 	paymentfrequency_id, frequency_name FROM hrpaymentfrequency ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$PaymentFrequency[$Row['paymentfrequency_id']] = $Row['frequency_name'];
}

// BEGIN: PayrollCategories  array.
$PayrollCategories = array();
$Query = "SELECT * FROM hrpayrollcategories ";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$PayrollCategories[$Row['payroll_category_id']] = $Row['payroll_category_name'];
}


if (isset($_POST['SelectedGroupName'])){
	$SelectedGroupName = mb_strtoupper($_POST['SelectedGroupName']);
} elseif (isset($_GET['SelectedGroupName'])){
	$SelectedGroupName = mb_strtoupper($_GET['SelectedGroupName']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Payroll Groups ') . '</h1></a></div>';


if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (mb_strlen($_POST['GroupName']) >100) {
		$InputError = 1;
		prnMsg(_('The Group  Name  must be 100 characters or less long'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}

	if (mb_strlen($_POST['GroupName'])==0) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('The Group Name  must contain at least one character'),'error');
		$Errors[$i] = 'Group Name';
		$i++;
	}




	$checksql = "SELECT count(*)
		     FROM hrpayrollgroups
		     WHERE payrollgroup_name = '" . $_POST['GroupName'] . "'";
	$checkresult=DB_query($checksql);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0 and !isset($SelectedGroupName)) {
		$InputError = 1;
		echo '<br />';
		prnMsg(_('You already have a Payroll Groups').' '.$_POST['GroupName'],'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}

	if (isset($SelectedGroupName) AND $InputError !=1) {

		$sql = "UPDATE hrpayrollgroups
			SET payrollgroup_name = '" . $_POST['GroupName'] . "',
			payment_frequency = '" . $_POST['PaymentFrequency']. "',
			generation_date = '" . $_POST['GenerationDate'] . "',
			enable_lop= '" . $_POST['EnableLop']. "',
			lop_value= '" . $_POST['LopValue']. "',
			bank_account_to_use= '" . $_POST['BankAccount']. "',
			gl_posting_account= '" . $_POST['GeneralLegder']. "',
			currency= '" . $_POST['Currency']. "'


			WHERE payrollgroup_id = '" .$SelectedGroupName."'";

		$msg = _('The Group Name') . ' ' . $_POST['GroupName']. ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the Name is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM hrpayrollgroups
			     WHERE payrollgroup_name = '" . $_POST['GroupName'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Group Name') . ' ' . $_POST['payrollgroup_name'] . _(' already exists.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO hrpayrollgroups
						(payrollgroup_name,
							payment_frequency,
							generation_date,
enable_lop,lop_value,bank_account_to_use,gl_posting_account,currency )
					VALUES ('" . $_POST['GroupName'] . "',
'" .$_POST['PaymentFrequency']. "',
'" . $_POST['GenerationDate']. "',
'" . $_POST['EnableLop'] . "',
'" . $_POST['LopValue']. "',
'" . $_POST['BankAccount'] . "',
'" . $_POST['GeneralLegder'] . "',
'" . $_POST['Currency'] . "'
)";

			$msg = _('Group Name') . ' ' . $_POST["GroupName"] .  ' ' . _('has been created');
			$checkSql = "SELECT count(payrollgroup_id)
			     FROM hrpayrollgroups";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);


		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
$result = DB_query($sql);
	if(!isset($SelectedGroupName)){

		$payroll_group_id = DB_Last_Insert_ID($db,'hrpayrollgroups','payrollgroup_id');
		$selected_payroll_categories = $_POST['PayrollCategories'];
		$j=1;

		foreach($selected_payroll_categories as $payroll_category)
		{
			$sql = "INSERT INTO hrpayroll_groups_payroll_categories
						(payroll_group_id,
							payroll_category_id,
							sort_order )
					VALUES ('" . $payroll_group_id . "',
					'" . $payroll_category . "',
					'" . $j. "'
					)";
					$j++;
					$result = DB_query($sql);
		}


	}else {
$sql="DELETE FROM hrpayroll_groups_payroll_categories WHERE payroll_group_id='".$SelectedGroupName."'";
$result = DB_query($sql);

		$payroll_group_id = $SelectedGroupName;
		$selected_payroll_categories = $_POST['PayrollCategories'];
		$j=1;

		foreach($selected_payroll_categories as $payroll_category)
		{
			$sql = "INSERT INTO hrpayroll_groups_payroll_categories
						(payroll_group_id,
							payroll_category_id,
							sort_order )
					VALUES ('" . $payroll_group_id . "',
					'" . $payroll_category . "',
					'" . $j. "'
					)";
					$j++;
					$result = DB_query($sql);
		}
		# code...
	}




	// Fetch the default Category list.
			$DefaultGROUPName = $_SESSION['DEFAULTGROUPName'];

	// Does it exist
		$checkSql = "SELECT count(*)
			     FROM hrpayrollgroups
			     WHERE payrollgroup_id = '" . $DefaultGROUPName . "'"
					 ;
		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

	// If it doesnt then update config with newly created one.
		if ($checkrow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='" . $_POST['payrollgroup_id'] . "'
						WHERE confname='DEFAULTGROUPName'";
			$result = DB_query($sql);
			$_SESSION['DEFAULTGROUPName'] = $_POST['payrollgroup_id'];
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
		unset($_POST['Currency']);

	}

} elseif ( isset($_GET['delete']) ) {

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'EMPLOYEE Positions'


	$sql= "SELECT COUNT(*)
	       FROM hremployees
	       WHERE payrollgroup_id='".$SelectedGroupName."'";

	$ErrMsg = _('The number of transactions using this Category Name could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg(_('Cannot delete this Payroll because Employees  have been added to this Payroll') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('Employees on this Payroll'),'error');

	}

	 else {
			$result = DB_query("SELECT payrollgroup_name FROM hrpayrollgroups WHERE payrollgroup_id='".$SelectedGroupName."'");
			if (DB_Num_Rows($result)>0){
				$NameRow = DB_fetch_array($result);
				$CategoryName = $NameRow['payrollgroup_name'];

				$sql="DELETE FROM hrpayrollgroups WHERE payrollgroup_id='".$SelectedGroupName."'";
				$ErrMsg = _('The Payroll group record could not be deleted because');
				$result = DB_query($sql,$ErrMsg);
				echo '<br />';
				prnMsg(_('Payroll Group') . ' ' . $CategoryName  . ' ' . _('has been deleted') ,'success');
			}
			unset ($SelectedGroupName);
			unset($_GET['delete']);

	} //end if Positions used in Employees set up
}

if (!isset($SelectedGroupName)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedPayrollCategory will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of sales types will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT *
	  FROM hrpayrollgroups";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
';
	echo '<thead><tr>
	<th class="ascending">' . _('Payroll Group id') . '</th>
 <th class="ascending">' . _('Payroll Group Name') . '</th>
 <th class="ascending">' . _('Payment Frequency') . '</th>
 <th class="ascending">' . _('Generation Date') . '</th>
 <th class="ascending">' . _('LOP') . '</th>
<th class="ascending">' . _('Payroll Categories') . '</th>
<th class="ascending">' . _('Payroll Currency') . '</th>
<th colspan="2">' . _('Actions') . '</th>
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

echo'<td>'.$myrow['payrollgroup_id'].'</td>
		<td>'.$myrow['payrollgroup_name'].'</td>
<td>'.$PaymentFrequency[$myrow['payment_frequency']].'</td>
<td>'.$myrow['generation_date'].'</td>
<td>'._(($myrow['enable_lop'] == 1) ? 'Yes' : 'NO').'</td>
<td>';
$Query1 = "SELECT payroll_category_code
			 FROM hrpayroll_groups_payroll_categories
			JOIN hrpayrollcategories on hrpayroll_groups_payroll_categories.payroll_category_id = hrpayrollcategories.payroll_category_id
			 WHERE payroll_group_id='".$myrow['payrollgroup_id']."'";
$Result1 = DB_query($Query1);
while ($Row1 = DB_fetch_array($Result1)) {
echo $Row1['payroll_category_code'].',';
}



echo'</td>
<td>'.$myrow['currency'].'</td>

		<td><a href="HrPayrollGroups.php?SelectedGroupName='.$myrow['payrollgroup_id'].'" class="btn btn-info">' . _('Edit') . '</a></td>
		<td><a href="HrPayrollGroups.php?SelectedGroupName='.$myrow['payrollgroup_id'].'&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this Payroll group?') . '");\'>' . _('Delete') . '</a></td>
		</tr>';

	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div><br />';
}

//end of ifs and buts!
if (isset($SelectedGroupName)) {

	echo '<div class="row" align="center"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Groups Defined') . '</a></div><br />';
}
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		';

	// The user wish to EDIT an existing name
	if ( isset($SelectedGroupName) AND $SelectedGroupName!='' ) {

		$sql = "SELECT *
		        FROM hrpayrollgroups
		        WHERE payrollgroup_id='".$SelectedGroupName."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['payrollgroup_id'] = $myrow['payrollgroup_id'];
		$_POST['GroupName']  = $myrow['payrollgroup_name'];
		$_POST['PaymentFrequency']  = $myrow['payment_frequency'];
		$_POST['GenerationDate']  = $myrow['generation_date'];
		$_POST['EnableLop']  = $myrow['enable_lop'];
		$_POST['AdditionalCondition']  = $myrow['additional_condition'];
		$_POST['BankAccount']  = $myrow['bank_account_to_use'];
		$_POST['GeneralLegder']  = $myrow['gl_posting_account'];
		$_POST['Currency'] = $myrow['currency'];
		echo '<input type="hidden" name="SelectedGroupName" value="' . $SelectedGroupName . '" />
			<input type="hidden" name="payrollgroup_id" value="' . $_POST['payroll_group_id'] . '" />
			<div class="row">';

		// We dont allow the user to change an existing Name code

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('GroupName ID') . '</label> ' . $_POST['payrollgroup_id'] . '</div>
			</div></div>';
	} else 	{
		// This is a new Name so the user may volunteer a Name code
		echo '';
	}

	if (!isset($_POST['GroupName'])) {
		$_POST['GroupName']='';
	}
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Pay Group  Name') . '</label>
			<input type="text" name="GroupName" class="form-control"  required="required" title="' . _('The Pay Group Name is required') . '" value="' . $_POST['GroupName'] . '" /></div>
		</div>';

		// General Legder  input.
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Payment Frequency') .
			'</label><select id="PaymentFrequency" name="PaymentFrequency"  class="form-control">';
		foreach ($PaymentFrequency as $Frequency => $Row) {

			echo '<option';
			if (isset($_POST['PaymentFrequency']) and $_POST['PaymentFrequency']==$Frequency) {
				echo ' selected="selected"';
			}
			echo ' value="' . $Frequency . '">' . $Row . '</option>';
		}
		echo '</select> </div></div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Generation Date') . '</label>
				<input type="text" name="GenerationDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy"  required="required" title="' . _('The Generation Date is required') . '" value="' . $_POST['GenerationDate'] . '" /></div>
			</div></div>

			<script>

			$(document).ready(function(){
				$("#LopValue").hide();
			         $(":input[name=EnableLop]:eq(0)").click(function(){
			             $("#LopValue").show(1000);
			          });

			          $(":input[name=EnableLop]:eq(1)").click(function(){
			             $("#LopValue").hide(1000);
			          });

			  });



							</script>




			<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Enable Leave without Pay?') .
			  '</label><div class="checkbox"><input type="radio"';

			  if (isset($_POST['EnableLop']) and $_POST['EnableLop']==1) {
			    echo ' checked';}
			echo'
			   name="EnableLop" id ="EnableLop" value="1"> Yes
</div><div class="checkbox">
			  <input';
				if (! isset($SelectedGroupName)) {
				 echo ' checked';}

			  if (isset($_POST['EnableLop']) and $_POST['EnableLop']==0) {
			    echo ' checked';
			  }
			echo'

			  type="radio" name="EnableLop"  id ="EnableLop" value="0"> No
			  </div></div>
</div>
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Leave without Pay Days') . '</label>
						<input type="text" id="LopValue" name="LopValue" class="form-control"  value="' . $_POST['LopValue'] . '" /></div>
					</div>

				';
				// General Legder  input.

					echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Payroll Categories') .
						'</label>
						<select id="PayrollCategories" name="PayrollCategories[]" multiple="multiple"  class="form-control">';

						$Payrollcats = array();
						$Query = "SELECT payroll_category_id
									 FROM hrpayroll_groups_payroll_categories
									 WHERE payroll_group_id='".$SelectedGroupName."'";
						$Result = DB_query($Query);
						while ($Row = DB_fetch_array($Result)) {

$Payrollcats[$Row['payroll_category_id']] = $Row['payroll_category_id'];
						}
						foreach( $Payrollcats as $Frequency => $Row)
						{
							$category_ids[] = $Row;
						}
					foreach ($PayrollCategories as $CategoryCode => $Row) {

						echo '<option';

						if (isset($SelectedGroupName) and (in_array($CategoryCode, $category_ids))) {
							echo ' selected="selected"';
						}
						echo ' value="' . $CategoryCode . '">' . $Row . '</option>';
					}
					echo '</select> </div></div></div>';

					// Bank Accounts.
					echo '<div class="row">
					<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Bank Account') .
						'</label><select id="BankAccount" name="BankAccount"  class="form-control">';
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
						'</label><select id="GeneralLegder" name="GeneralLegder"  class="form-control">';
							echo'<option value="NULL">NONE </option>';
					foreach ($GeneralLegderAccount as $AccountCode => $Row) {

						echo '<option';
						if (isset($_POST['GeneralLegder']) and $_POST['GeneralLegder']==$AccountCode) {
							echo ' selected="selected"';
						}
						echo ' value="' . $AccountCode . '">' . $Row . '</option>';
					}
					echo '</select> </div></div>';
					echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Currency For Payroll Group') .'</label>
<select name="Currency" class="form-control">';
					$sql = "SELECT currabrev,currency FROM currencies";
					$ErrMsg = _('The currencies could not be retrieved because');
					$DbgMsg = _('The SQL used to retrieve currencies and failed was');
					$result = DB_query($sql,$ErrMsg,$DbgMsg);

					while ($myrow=DB_fetch_array($result)){
						if ($myrow['currabrev']==$_POST['Currency']){
							echo '<option selected="selected" value="'. $myrow['currabrev'] . '">' .$myrow['currabrev'].'</option>';
						} else {
							echo '<option value="'. $myrow['currabrev'] . '">' .$myrow['currabrev'].'</option>';
						}

					}
					echo '</select></div></div>


		</div>
		
		<div class="row" align="center">
			<input type="submit" class="btn btn-success" name="submit" value="' . _('Accept') . '" />
		</div>
	<br />
	</form>';

} // end if user wish to delete

include('includes/footer.php');
?>
