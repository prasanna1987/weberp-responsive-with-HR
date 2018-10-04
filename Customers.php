<?php


include('includes/session.php');
include('includes/CurrenciesArray.php'); // To get the currency name from the currency code.

if (isset($_POST['Edit']) or isset($_GET['Edit']) or isset($_GET['DebtorNo'])) {
	$ViewTopic = 'AccountsReceivable';
	$BookMark = 'AmendCustomer';
} else {
	$ViewTopic = 'AccountsReceivable';
	$BookMark = 'NewCustomer';
}

$Title = _('Customer Maintenance');
/* nERP manual links before header.php */
$ViewTopic= 'AccountsReceivable';
$BookMark = 'NewCustomer';
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/CountriesArray.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>
		' . ' ' . _('Customer Maintenance') . '
	</h1></a></div>';

if (isset($Errors)) {
	unset($Errors);
}
$Errors = array();

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;
	$i=1;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	$_POST['DebtorNo'] = mb_strtoupper($_POST['DebtorNo']);

	$sql="SELECT COUNT(debtorno) FROM debtorsmaster WHERE debtorno='".$_POST['DebtorNo']."'";
	$result=DB_query($sql);
	$myrow=DB_fetch_row($result);
	if ($myrow[0]>0 AND isset($_POST['New'])) {
		$InputError = 1;
		echo prnMsg( _('The customer number already exists in the system'),'error');
		$Errors[$i] = 'DebtorNo';
		$i++;
	}elseif (mb_strlen($_POST['CustName']) > 40 OR mb_strlen($_POST['CustName'])==0) {
		$InputError = 1;
		echo prnMsg( _('The customer name must be entered and must be forty characters or less'),'error');
		$Errors[$i] = 'CustName';
		$i++;
	} elseif ($_SESSION['AutoDebtorNo']==0 AND mb_strlen($_POST['DebtorNo']) ==0) {
		$InputError = 1;
		echo prnMsg( _('The customer code cannot be empty'),'error');
		$Errors[$i] = 'DebtorNo';
		$i++;
	} elseif ($_SESSION['AutoDebtorNo']==0 AND (ContainsIllegalCharacters($_POST['DebtorNo']) OR mb_strpos($_POST['DebtorNo'], ' '))) {
		$InputError = 1;
		echo prnMsg( _('The customer code cannot contain any of the following characters') . " . - ' &amp; + \" " . _('or a space'),'error');
		$Errors[$i] = 'DebtorNo';
		$i++;
	} elseif (mb_strlen($_POST['Address1']) >40) {
		$InputError = 1;
		echo prnMsg( _('The Line 1 of the address must be forty characters or less'),'error');
		$Errors[$i] = 'Address1';
		$i++;
	} elseif (mb_strlen($_POST['Address2']) >40) {
		$InputError = 1;
		echo prnMsg( _('The Line 2 of the address must be forty characters or less'),'error');
		$Errors[$i] = 'Address2';
		$i++;
	} elseif (mb_strlen($_POST['Address3']) >40) {
		$InputError = 1;
		echo prnMsg( _('The Line 3 of the address must be forty characters or less'),'error');
		$Errors[$i] = 'Address3';
		$i++;
	} elseif (mb_strlen($_POST['Address4']) >50) {
		$InputError = 1;
		echo prnMsg( _('The Line 4 of the address must be fifty characters or less'),'error');
		$Errors[$i] = 'Address4';
		$i++;
	} elseif (mb_strlen($_POST['Address5']) >20) {
		$InputError = 1;
		echo prnMsg( _('The Line 5 of the address must be twenty characters or less'),'error');
		$Errors[$i] = 'Address5';
		$i++;
	} elseif (!is_numeric(filter_number_format($_POST['CreditLimit']))) {
		$InputError = 1;
		echo prnMsg( _('The credit limit must be numeric'),'error');
		$Errors[$i] = 'CreditLimit';
		$i++;
	} elseif (!is_numeric(filter_number_format($_POST['PymtDiscount']))) {
		$InputError = 1;
		echo prnMsg( _('The prompt payment discount must be numeric'),'error');
		$Errors[$i] = 'PymtDiscount';
		$i++;
	} elseif (!Is_Date($_POST['ClientSince'])) {
		$InputError = 1;
		echo prnMsg( _('The customer since field must be a date in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
		$Errors[$i] = 'ClientSince';
		$i++;
	} elseif (!is_numeric(filter_number_format($_POST['Discount']))) {
		$InputError = 1;
		echo prnMsg( _('The discount percentage must be numeric'),'error');
		$Errors[$i] = 'Discount';
		$i++;
	} elseif (filter_number_format($_POST['CreditLimit']) <0) {
		$InputError = 1;
		echo prnMsg( _('The credit limit must be a positive number'),'error');
		$Errors[$i] = 'CreditLimit';
		$i++;
	} elseif ((filter_number_format($_POST['PymtDiscount'])> 10) OR (filter_number_format($_POST['PymtDiscount']) <0)) {
		$InputError = 1;
		echo prnMsg( _('The payment discount is expected to be less than 10% and greater than or equal to 0'),'error');
		$Errors[$i] = 'PymtDiscount';
		$i++;
	} elseif ((filter_number_format($_POST['Discount'])> 100) OR (filter_number_format($_POST['Discount']) <0)) {
		$InputError = 1;
		echo prnMsg( _('The discount is expected to be less than 100% and greater than or equal to 0'),'error');
		$Errors[$i] = 'Discount';
		$i++;
	}

	if ($InputError !=1){

		$SQL_ClientSince = FormatDateForSQL($_POST['ClientSince']);

		if (!isset($_POST['New'])) {

			$sql = "SELECT count(id)
					  FROM debtortrans
					where debtorno = '" . $_POST['DebtorNo'] . "'";
			$result = DB_query($sql);
			$myrow = DB_fetch_array($result);

			if ($myrow[0] == 0) {
			  $sql = "UPDATE debtorsmaster SET	name='" . $_POST['CustName'] . "',
												address1='" . $_POST['Address1'] . "',
												address2='" . $_POST['Address2'] . "',
												address3='" . $_POST['Address3'] ."',
												address4='" . $_POST['Address4'] . "',
												address5='" . $_POST['Address5'] . "',
												address6='" . $_POST['Address6'] . "',
												currcode='" . $_POST['CurrCode'] . "',
												clientsince='" . $SQL_ClientSince. "',
												holdreason='" . $_POST['HoldReason'] . "',
												paymentterms='" . $_POST['PaymentTerms'] . "',
												discount='" . filter_number_format($_POST['Discount'])/100 . "',
												discountcode='" . $_POST['DiscountCode'] . "',
												pymtdiscount='" . filter_number_format($_POST['PymtDiscount'])/100 . "',
												creditlimit='" . filter_number_format($_POST['CreditLimit']) . "',
												salestype = '" . $_POST['SalesType'] . "',
												invaddrbranch='" . $_POST['AddrInvBranch'] . "',
												taxref='" . $_POST['TaxRef'] . "',
												customerpoline='" . $_POST['CustomerPOLine'] . "',
												typeid='" . $_POST['typeid'] . "',
												language_id='" . $_POST['LanguageID'] . "'
					  WHERE debtorno = '" . $_POST['DebtorNo'] . "'";
			} else {

			  $CurrSQL = "SELECT currcode
					  		FROM debtorsmaster
							where debtorno = '" . $_POST['DebtorNo'] . "'";
			  $CurrResult = DB_query($CurrSQL);
			  $CurrRow = DB_fetch_array($CurrResult);
			  $OldCurrency = $CurrRow[0];

			  $sql = "UPDATE debtorsmaster SET	name='" . $_POST['CustName'] . "',
												address1='" . $_POST['Address1'] . "',
												address2='" . $_POST['Address2'] . "',
												address3='" . $_POST['Address3'] ."',
												address4='" . $_POST['Address4'] . "',
												address5='" . $_POST['Address5'] . "',
												address6='" . $_POST['Address6'] . "',
												clientsince='" . $SQL_ClientSince . "',
												holdreason='" . $_POST['HoldReason'] . "',
												paymentterms='" . $_POST['PaymentTerms'] . "',
												discount='" . filter_number_format($_POST['Discount'])/100 . "',
												discountcode='" . $_POST['DiscountCode'] . "',
												pymtdiscount='" . filter_number_format($_POST['PymtDiscount'])/100 . "',
												creditlimit='" . filter_number_format($_POST['CreditLimit']) . "',
												salestype = '" . $_POST['SalesType'] . "',
												invaddrbranch='" . $_POST['AddrInvBranch'] . "',
												taxref='" . $_POST['TaxRef'] . "',
												customerpoline='" . $_POST['CustomerPOLine'] . "',
												typeid='" . $_POST['typeid'] . "',
												language_id='" . $_POST['LanguageID'] . "'
						WHERE debtorno = '" . $_POST['DebtorNo'] . "'";

			  if ($OldCurrency != $_POST['CurrCode']) {
			  	echo prnMsg( _('The currency code cannot be updated as there are already transactions for this customer'),'info');
			  }
			}

			$ErrMsg = _('The customer could not be updated because');
			$result = DB_query($sql,$ErrMsg);
			echo prnMsg( _('Customer updated'),'success');
			echo '<br />';

		} else { //it is a new customer
			/* set the DebtorNo if $AutoDebtorNo in config.php has been set to
			something greater 0 */
			if ($_SESSION['AutoDebtorNo'] > 0) {
				/* system assigned, sequential, numeric */
				if ($_SESSION['AutoDebtorNo']== 1) {
					$_POST['DebtorNo'] = GetNextTransNo(500);
				}
			}

			$sql = "INSERT INTO debtorsmaster (
							debtorno,
							name,
							address1,
							address2,
							address3,
							address4,
							address5,
							address6,
							currcode,
							clientsince,
							holdreason,
							paymentterms,
							discount,
							discountcode,
							pymtdiscount,
							creditlimit,
							salestype,
							invaddrbranch,
							taxref,
							customerpoline,
							typeid,
							language_id)
				VALUES ('" . $_POST['DebtorNo'] ."',
						'" . $_POST['CustName'] ."',
						'" . $_POST['Address1'] ."',
						'" . $_POST['Address2'] ."',
						'" . $_POST['Address3'] . "',
						'" . $_POST['Address4'] . "',
						'" . $_POST['Address5'] . "',
						'" . $_POST['Address6'] . "',
						'" . $_POST['CurrCode'] . "',
						'" . $SQL_ClientSince . "',
						'" . $_POST['HoldReason'] . "',
						'" . $_POST['PaymentTerms'] . "',
						'" . filter_number_format($_POST['Discount'])/100 . "',
						'" . $_POST['DiscountCode'] . "',
						'" . filter_number_format($_POST['PymtDiscount'])/100 . "',
						'" . filter_number_format($_POST['CreditLimit']) . "',
						'" . $_POST['SalesType'] . "',
						'" . $_POST['AddrInvBranch'] . "',
						'" . $_POST['TaxRef'] . "',
						'" . $_POST['CustomerPOLine'] . "',
						'" . $_POST['typeid'] . "',
						'" . $_POST['LanguageID'] . "')";

			$ErrMsg = _('This customer could not be added because');
			$result = DB_query($sql,$ErrMsg);

			$BranchCode = mb_substr($_POST['DebtorNo'],0,4);

			echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath .'/CustomerBranches.php?DebtorNo=' . $_POST['DebtorNo'] . '">';

			echo '<div class="row"><p class="text-info">' . _('You should automatically be forwarded to the entry of a new Customer Branch page') .
			'. ' . _('If this does not happen') .' (' . _('if the browser does not support META Refresh') . ') ' .
			'<a href="' . $RootPath . '/CustomerBranches.php?DebtorNo=' . $_POST['DebtorNo']  . '" class="btn btn-info"></a></p></div>';

			include('includes/footer.php');
			exit;
		}
	} else {
		echo prnMsg( _('Validation failed') . '. ' . _('No updates or deletes took place'),'error');
	}

} elseif (isset($_POST['delete'])) {

//the link to delete a selected record was clicked instead of the submit button

	$CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'DebtorTrans'

	$sql= "SELECT COUNT(*) FROM debtortrans WHERE debtorno='" . $_POST['DebtorNo'] . "'";
	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		$CancelDelete = 1;
		echo prnMsg( _('This customer cannot be deleted because there are transactions that refer to it'),'warn');
		echo '<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('transactions against this customer');

	} else {
		$sql= "SELECT COUNT(*) FROM salesorders WHERE debtorno='" . $_POST['DebtorNo'] . "'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			$CancelDelete = 1;
			echo prnMsg( _('Cannot delete the customer record because orders have been created against it'),'warn');
			echo '<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('orders against this customer');
		} else {
			$sql= "SELECT COUNT(*) FROM salesanalysis WHERE cust='" . $_POST['DebtorNo'] . "'";
			$result = DB_query($sql);
			$myrow = DB_fetch_row($result);
			if ($myrow[0]>0) {
				$CancelDelete = 1;
				echo prnMsg( _('Cannot delete this customer record because sales analysis records exist for it'),'warn');
				echo '<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('sales analysis records against this customer');
			} else {

				// Check if there are any users that refer to this CUSTOMER code
				$SQL= "SELECT COUNT(*) FROM www_users WHERE www_users.customerid = '" . $_POST['DebtorNo'] . "'";

				$result = DB_query($SQL);
				$myrow = DB_fetch_row($result);

				if ($myrow[0]>0) {
					echo prnMsg(_('Cannot delete this customer because users exist that refer to it') . '. ' . _('Purge old users first'),'warn');
					echo '<br />' . _('There are') . ' ' . $myrow[0] . ' '._('users referring to this Branch/customer');
				} else {
						// Check if there are any contract that refer to this branch code
					$SQL = "SELECT COUNT(*) FROM contracts WHERE contracts.debtorno = '" . $_POST['DebtorNo'] . "'";

					$result = DB_query($SQL);
					$myrow = DB_fetch_row($result);

					if ($myrow[0]>0) {
						echo prnMsg(_('Cannot delete this customer because contracts have been created that refer to it') . '. ' . _('Purge old contracts first'),'warn');
						echo '<br />' . _('There are') . ' ' . $myrow[0] . ' '._('contracts referring to this customer');
					}
				}
			}
		}

	}
	if ($CancelDelete==0) { //ie not cancelled the delete as a result of above tests
		$SQL="DELETE FROM custbranch WHERE debtorno='" . $_POST['DebtorNo'] . "'";
		$result = DB_query($SQL,$ErrMsg);
		$sql="DELETE FROM custcontacts WHERE debtorno='" . $_POST['DebtorNo'] . "'";
		$result = DB_query($sql);
		$sql="DELETE FROM debtorsmaster WHERE debtorno='" . $_POST['DebtorNo'] . "'";
		$result = DB_query($sql);
		echo prnMsg( _('Customer') . ' ' . $_POST['DebtorNo'] . ' ' . _('has been deleted - together with all the associated branches and contacts'),'success');
		include('includes/footer.php');
		unset($_SESSION['CustomerID']);
		exit;
	} //end if Delete Customer
}

if(isset($_POST['Reset'])){
	unset($_POST['CustName']);
	unset($_POST['Address1']);
	unset($_POST['Address2']);
	unset($_POST['Address3']);
	unset($_POST['Address4']);
	unset($_POST['Address5']);
	unset($_POST['Address6']);
	unset($_POST['HoldReason']);
	unset($_POST['PaymentTerms']);
	unset($_POST['Discount']);
	unset($_POST['DiscountCode']);
	unset($_POST['PymtDiscount']);
	unset($_POST['CreditLimit']);
// Leave Sales Type set so as to faciltate fast customer setup
//	unset($_POST['SalesType']);
	unset($_POST['DebtorNo']);
	unset($_POST['InvAddrBranch']);
	unset($_POST['TaxRef']);
	unset($_POST['CustomerPOLine']);
	unset($_POST['LanguageID']);
// Leave Type ID set so as to faciltate fast customer setup
//	unset($_POST['typeid']);
}

/*DebtorNo could be set from a post or a get when passed as a parameter to this page */

if (isset($_POST['DebtorNo'])){
	$DebtorNo = $_POST['DebtorNo'];
} elseif (isset($_GET['DebtorNo'])){
	$DebtorNo = $_GET['DebtorNo'];
}
if (isset($_POST['ID'])){
	$ID = $_POST['ID'];
} elseif (isset($_GET['ID'])){
	$ID = $_GET['ID'];
} else {
	$ID='';
}
if (isset($_POST['Edit'])){
	$Edit = $_POST['Edit'];
} elseif (isset($_GET['Edit'])){
	$Edit = $_GET['Edit'];
} else {
	$Edit='';
}

if (isset($_POST['Add'])){
	$Add = $_POST['Add'];
} elseif (isset($_GET['Add'])){
	$Add = $_GET['Add'];
}

if(isset($_POST['AddContact']) AND (isset($_POST['AddContact'])!='')){
	echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/AddCustomerContacts.php?DebtorNo=' .$DebtorNo.'">';
}

if (!isset($DebtorNo)) {

/*If the page was called without $_POST['DebtorNo'] passed to page then assume a new customer is to be entered show a form with a Debtor Code field other wise the form showing the fields with the existing entries against the customer will show for editing with only a hidden DebtorNo field*/

/* First check that all the necessary items have been setup */

	$SetupErrors=0; //Count errors
	$sql="SELECT COUNT(typeabbrev)
				FROM salestypes";
	$result=DB_query($sql);
	$myrow=DB_fetch_row($result);
	if ($myrow[0]==0) {
		echo prnMsg( _('In order to create a new customer you must first set up at least one sales type/price list') . '<br />' .
			_('Click').' ' . '<a target="_blank" href="' . $RootPath . '/SalesTypes.php">' . _('here').' ' . '</a>' . _('to set up your price lists'),'warn');
		$SetupErrors += 1;
	}
	$sql="SELECT COUNT(typeid)
				FROM debtortype";
	$result=DB_query($sql);
	$myrow=DB_fetch_row($result);
	if ($myrow[0]==0) {
		echo prnMsg( _('In order to create a new customer you must first set up at least one customer type') . '<br />' .
			_('Click').' ' . '<a target="_blank" href="' . $RootPath . '/CustomerTypes.php">' . _('here').' ' . '</a>' . _('to set up your customer types'),'warn');
		$SetupErrors += 1;
	}

	if ($SetupErrors>0) {
		echo '<br /><div class="row"><a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .'"  class="btn btn-info">' . _('Click here to continue') . '</a></div>';
		include('includes/footer.php');
		exit;
	}
	echo '
<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<input type="hidden" name="New" value="Yes" />';

	$DataError =0;

	
	/* if $AutoDebtorNo in config.php has not been set or if it has been set to a number less than one,
	then provide an input box for the DebtorNo to manually assigned */
	if ($_SESSION['AutoDebtorNo']==0)  {
		echo '<div class="row"><div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Code') . '</label>
			   <input type="text" data-type="no-illegal-chars" tabindex="1"  name="DebtorNo"  required="required" autofocus="autofocus" title ="'._('Up to 10 characters for the customer code. The following characters are prohibited:') . ' \' &quot; + . &amp; \\ &gt; &lt;" placeholder="'._('alpha-numeric').'" size="11" maxlength="10" class="form-control" /></div></div></div>';
	}

	echo '<div class="row">
	<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Name') . '</label>
			<input tabindex="2" type="text" name="CustName" class="form-control" required="required" size="42" maxlength="40" /></div>
		</div>
		<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Address Line 1 (Street)') . '</label>
			<input tabindex="3" class="form-control" type="text" name="Address1" required="required" size="42" maxlength="40" /></div>
		</div>
		
		
		<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 2 (Street)') . '</label>
			<input tabindex="4" type="text" class="form-control" name="Address2" size="42" maxlength="40" /></div>
		</div></div>
		
		<div class="row">
		<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 3 (Suburb/City)') . '</label>
			<input tabindex="5" type="text" name="Address3" class="form-control" size="42" maxlength="40" /></div>
		</div>
		<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 4 (State/Province)') . '</label>
			<input tabindex="6" type="text" class="form-control" name="Address4" size="42" maxlength="40" /></div>
		</div>
		
		
		<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 5 (Postal Code)') . '</label>
			<input tabindex="7" class="form-control" type="text" name="Address5" size="22" maxlength="20" /></div>
		</div></div><div class="row">';

	if (!isset($_POST['Address6'])) {
		 $_POST['Address6'] = $CountriesArray[$_SESSION['CountryOfOperation']];
	}
	echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Country') . '</label>
			<select name="Address6" class="form-control">';
	foreach ($CountriesArray as $CountryEntry => $CountryName){
		if (isset($_POST['Address6']) AND (strtoupper($_POST['Address6']) == strtoupper($CountryName))){
			echo '<option selected="selected" value="' . $CountryName . '">' . $CountryName  . '</option>';
		} else {
			echo '<option value="' . $CountryName . '">' . $CountryName  . '</option>';
		}
	}
	echo '</select></div>
		</div>';

// Show Sales Type drop down list
	$result=DB_query("SELECT typeabbrev, sales_type FROM salestypes ORDER BY sales_type");
	if (DB_num_rows($result)==0){
		$DataError =1;
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . prnMsg(_('No sales types/price lists defined'),'error') . '</label>
			   <a href="SalesTypes.php?" target="_parent">' . _('Setup Types') . '</a></div></div>
			';
	} else {
        echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Sales Type') . '/' . _('Price List') . '</label>
			   <select tabindex="9" name="SalesType" required="required" class="form-control">';

		while ($myrow = DB_fetch_array($result)) {
		   echo '<option value="'. $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
		} //end while loopre
		DB_data_seek($result,0);
        echo '</select></div>
			</div>';
	}

// Show Customer Type drop down list
	$result=DB_query("SELECT typeid, typename FROM debtortype ORDER BY typename");
	if (DB_num_rows($result)==0){
	   $DataError =1;
	   echo '<a href="SalesTypes.php?" target="_parent">' . _('Setup Types') . '</a>';
	   echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . prnMsg(_('No Customer types/price lists defined'),'error') . '</label>
			</div></div></div>';
	} else {
		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Type') . '</label>
				<select tabindex="9" class="form-control" name="typeid" required="required">';

		while ($myrow = DB_fetch_array($result)) {
			echo '<option value="'. $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
		} //end while loop
		DB_data_seek($result,0);
		echo '</select></div>
			</div></div>';
	}

	$DateString = Date($_SESSION['DefaultDateFormat']);
	echo '<div class="row">
	<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Customer Since') . ' (' . $_SESSION['DefaultDateFormat'] . ')</label>
			<input tabindex="10" type="text" class="date form-control" name="ClientSince" value="' . $DateString . '" size="11" maxlength="10" /></div>
		</div>';

	echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Discount Percent') . '</label>
					<input tabindex="11" type="text" class="number form-control" name="Discount" value="0" size="5" maxlength="4" /></div>
				</div>
				
					<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Discount Code') . '</label>
				<input tabindex="12" type="text" class="form-control" name="DiscountCode" size="3" maxlength="2" /></div>
				</div></div>
				
				<div class="row">
				<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Prompt Payment Discount Percent') . '</label>
			<input tabindex="13" type="text" class ="number form-control" name="PymtDiscount" value="0" size="5" maxlength="4" /></div>
				</div>
				<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Credit Limit') . '</label>
				<input tabindex="14" type="text" class="integer form-control" name="CreditLimit" required="required" value="' . locale_number_format($_SESSION['DefaultCreditLimit'],0) . '" size="16" maxlength="14" /></div>
				</div>
				
				
				<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('GSTIN/PAN') . '</label>
				<input tabindex="15" type="text" class="form-control" name="TaxRef" size="22" maxlength="20" /></div>
				</div></div><div class="row">';

	$result=DB_query("SELECT terms, termsindicator FROM paymentterms");
	if (DB_num_rows($result)==0){
		$DataError =1;
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . prnMsg(_('There are no payment terms currently defined - go to the setup tab of the main menu and set at least one up first'),'error') . '</label></div></div>';
	} else {

		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Payment Terms') . '</label>
				<select tabindex="15" class="form-control" name="PaymentTerms" required="required">';

		while ($myrow = DB_fetch_array($result)) {
			echo '<option value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
		} //end while loop
		DB_data_seek($result,0);

		echo '</select></div></div>';
	}
	echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Credit Status') . '</label>
		<select tabindex="16" name="HoldReason" required="required" class="form-control">';

	$result=DB_query("SELECT reasoncode, reasondescription FROM holdreasons");
	if (DB_num_rows($result)==0){
		$DataError =1;
		echo '<p class="text-danger">' . prnMsg(_('There are no credit statuses currently defined - go to the setup tab of the main menu and set at least one up first'),'error') . '</p>
			';
	} else {
		while ($myrow = DB_fetch_array($result)) {
			echo '<option value="'. $myrow['reasoncode'] . '">' . $myrow['reasondescription'] . '</option>';
		} //end while loop
		DB_data_seek($result,0);
		echo '</select></div></div>';
	}

	$result=DB_query("SELECT currency, currabrev FROM currencies");
	if (DB_num_rows($result)==0){
		$DataError =1;
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <p class="text-danger">' . prnMsg(_('There are no currencies currently defined - go to the setup tab of the main menu and set at least one up first'),'error') . '</p>
			</div></div></div>';
	} else {
		if (!isset($_POST['CurrCode'])){
			$CurrResult = DB_query("SELECT currencydefault FROM companies WHERE coycode=1");
			$myrow = DB_fetch_row($CurrResult);
			$_POST['CurrCode'] = $myrow[0];
		}
		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Currency') . '</label>
				<select tabindex="17" name="CurrCode" required="required" class="form-control">';
		while ($myrow = DB_fetch_array($result)) {
			if ($_POST['CurrCode']==$myrow['currabrev']){
				echo '<option selected="selected" value="'. $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
			} else {
				echo '<option value="'. $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
			}
		} //end while loop
		DB_data_seek($result,0);

		echo '</select></div>
			</div></div>';
	}

	echo '
	<input type="hidden" name="LanguageID" value="en_IN.utf8" />';
	//echo '
//	<div class="col-xs-4">
//               <div class="form-group has-error"> 
//			   <label class="col-md-8 control-label">' . _('Language') . '</label>
//			<select name="LanguageID" class="form-control">';
//
//	if (!isset($_POST['LanguageID']) OR $_POST['LanguageID']==''){
//		$_POST['LanguageID']=$_SESSION['Language'];
//	}
//
//	foreach ($LanguagesArray as $LanguageCode => $LanguageName){
//		if ($_POST['LanguageID'] == $LanguageCode){
//			echo '<option selected="selected" value="' . $LanguageCode . '">' . $LanguageName['LanguageName']  . '</option>';
//		} else {
//			echo '<option value="' . $LanguageCode . '">' . $LanguageName['LanguageName']  . '</option>';
//		}
//	}
//	echo '</select></div>
//			</div>';

	echo '<div class="row">
	<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer PO Line on SO') . '</label>
			<select tabindex="18" class="form-control" name="CustomerPOLine" required="required">
				<option selected="selected" value="0">' . _('No') . '</option>
				<option value="1">' . _('Yes') . '</option>
				</select>
			</div>
		</div>
		
		
		<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Invoice Address') . '</label>
			<select tabindex="19" name="AddrInvBranch" required="required" class="form-control">
				<option selected="selected" value="0">' . _('To HO') . '</option>
				<option value="1">' . _('To Branch') . '</option>
				</select>
			</div>
		</div>
		
		
		';
	if ($DataError ==0){
		echo '
			<div class="col-xs-4">
               <div class="form-group"> <br />
				<input tabindex="20" type="submit" class="btn btn-success" name="submit" value="' . _('Submit') . '" /></div>
				
			</div>	
			</div>	
				
			
			';

	}
  
	echo '</form>
	</div>
	</div>
	<br />

	
	';

} else {

//DebtorNo exists - either passed when calling the form or from the form itself

	echo '
			<div class="row gutter30">
			<div class="col-xs-12">';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	

	if (!isset($_POST['New'])) {
		$sql = "SELECT debtorno,
						name,
						address1,
						address2,
						address3,
						address4,
						address5,
						address6,
						currcode,
						salestype,
						clientsince,
						holdreason,
						paymentterms,
						discount,
						discountcode,
						pymtdiscount,
						creditlimit,
						invaddrbranch,
						taxref,
						customerpoline,
						typeid,
						language_id
				FROM debtorsmaster
				WHERE debtorno = '" . $DebtorNo . "'";

		$ErrMsg = _('The customer details could not be retrieved because');
		$result = DB_query($sql,$ErrMsg);

		$myrow = DB_fetch_array($result);
		/* if $AutoDebtorNo in config.php has not been set or if it has been set to a number less than one,
		then display the DebtorNo */
		if ($_SESSION['AutoDebtorNo']== 0 )  {
			echo '<div class="row"><div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">
               
			   ' . _('Customer Code') . ' </label> 
					' . $DebtorNo. '
				</div></div></div>';
		}
		$_POST['CustName'] = $myrow['name'];
		$_POST['Address1']  = $myrow['address1'];
		$_POST['Address2']  = $myrow['address2'];
		$_POST['Address3']  = $myrow['address3'];
		$_POST['Address4']  = $myrow['address4'];
		$_POST['Address5']  = $myrow['address5'];
		$_POST['Address6']  = $myrow['address6'];
		$_POST['SalesType'] = $myrow['salestype'];
		$_POST['CurrCode']  = $myrow['currcode'];
		$_POST['ClientSince'] = ConvertSQLDate($myrow['clientsince']);
		$_POST['HoldReason']  = $myrow['holdreason'];
		$_POST['PaymentTerms']  = $myrow['paymentterms'];
		$_POST['Discount']  = locale_number_format($myrow['discount'] * 100,2);
		$_POST['DiscountCode']  = $myrow['discountcode'];
		$_POST['PymtDiscount']  = locale_number_format($myrow['pymtdiscount'] * 100,2);
		$_POST['CreditLimit']	= locale_number_format($myrow['creditlimit'],0);
		$_POST['InvAddrBranch'] = $myrow['invaddrbranch'];
		$_POST['TaxRef'] = $myrow['taxref'];
		$_POST['CustomerPOLine'] = $myrow['customerpoline'];
		$_POST['typeid'] = $myrow['typeid'];
		$_POST['LanguageID'] = $myrow['language_id'];

		echo '<input type="hidden" name="DebtorNo" value="' . $DebtorNo . '" />';
       

	} else {
	// its a new customer being added
		echo '<input type="hidden" name="New" value="Yes" />';
        echo '';

		/* if $AutoDebtorNo in config.php has not been set or if it has been set to a number less than one,
		then provide an input box for the DebtorNo to manually assigned */
		if ($_SESSION['AutoDebtorNo']== 0 )  {
			echo '<div class="row"><div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Code') . '</label>
				<input ' . (in_array('DebtorNo',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="DebtorNo" required="required" data-type="no-illegal-chars" title="' . _('The customer code can be up to 10 alpha-numeric characters long or underscore') . '" value="' . $DebtorNo . '" size="12" maxlength="10" class="form-control" /></div></div></div>';
		}
	}
	if (isset($_GET['Modify'])) {
		echo '<div class="row">
		<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Customer Name') . '</label>
				' . $_POST['CustName'] . '</div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 1 (Street)') . '</label>
			' . $_POST['Address1'] . '</div>
			</div>
			
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 2 (Street)') . ':</label>
			' . $_POST['Address2'] . '</div>
			</div></div>
			
			<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 3 (Suburb/City)') . '</label>
				' . $_POST['Address3'] . '</div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 4 (State/Province)') . '</label>
				' . $_POST['Address4'] . '</div>
			</div>
			
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 5 (Postal Code)') . '</label>
			' . $_POST['Address5'] . '</div>
			</div></div>
			
			<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Country') . '</label>
			' . $_POST['Address6'] . '</div>
			</div>';
	} else {
		echo '<div class="row">
		<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Name') . '</label>
				<input ' . (in_array('CustName',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="CustName" class="form-control" required="required" autofocus="autofocus" value="' . $_POST['CustName'] . '" size="42" maxlength="40" /></div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 1 (Street)') . '</label>
				<input ' . (in_array('Address1',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="Address1" class="form-control" required="required" size="42" maxlength="40" value="' . $_POST['Address1'] . '" /></div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 2 (Street)') . '</label>
			  <input ' . (in_array('Address2',$Errors) ?  'class="inputerror" ' : '' ) .' type="text" class="form-control" name="Address2" size="42" maxlength="40" value="' . $_POST['Address2'] . '" /></div>
			</div>
			</div>
			<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 3 (Suburb/City)') . ':</label>
			   <input ' . (in_array('Address3',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="Address3" class="form-control" size="42" maxlength="40" value="' . $_POST['Address3'] . '" /></div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 4 (State/Province)') . '</label>
			   <input ' . (in_array('Address4',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="form-control" name="Address4" size="42" maxlength="40" value="' . $_POST['Address4'] . '" /></div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Address Line 5 (Postal Code)') . '</label>
				<input ' . (in_array('Address5',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="form-control" name="Address5" size="42" maxlength="40" value="' . $_POST['Address5'] . '" /></div>
			</div></div>';
		echo '<div class="row">
				<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Country') . '</label>
			<select name="Address6" class="form-control">';
		foreach ($CountriesArray as $CountryEntry => $CountryName){
			if (isset($_POST['Address6']) AND (strtoupper($_POST['Address6']) == strtoupper($CountryName))){
				echo '<option selected="selected" value="' . $CountryName . '">' . $CountryName  . '</option>';
			}elseif (!isset($_POST['Address6']) AND $CountryName == "") {
				echo '<option selected="selected" value="' . $CountryName . '">' . $CountryName  . '</option>';
			} else {
				echo '<option value="' . $CountryName . '">' . $CountryName  . '</option>';
			}
		}
		echo '</select></div>
			</div>';

	}
// Select sales types for drop down list
	if (isset($_GET['Modify'])) {
		$result=DB_query("SELECT sales_type FROM salestypes WHERE typeabbrev='".$_POST['SalesType']."'");
		$myrow=DB_fetch_array($result);
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Sales Type') . ':</label>
				' . $myrow['sales_type'] . '</div></div>';
	} else {
		$result=DB_query("SELECT typeabbrev, sales_type FROM salestypes");
		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Sales Type') . '/' . _('Price List') . '</label>
				<select name="SalesType" required="required" class="form-control">';
		while ($myrow = DB_fetch_array($result)) {
			if ($_POST['SalesType']==$myrow['typeabbrev']){
				echo '<option selected="selected" value="' . $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
			} else {
				echo '<option value="'. $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
			}
		} //end while loop
		DB_data_seek($result,0);
        echo '</select></div></div>';
	}

// Select Customer types for drop down list for SELECT/UPDATE
	if (isset($_GET['Modify'])) {
		$result=DB_query("SELECT typename FROM debtortype WHERE typeid='".$_POST['typeid']."'");
		$myrow=DB_fetch_array($result);
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Customer Type') . '</label>
				' . $myrow['typename'] . '</div>
			</div></div>';
	} else {
		$result=DB_query("SELECT typeid, typename FROM debtortype ORDER BY typename");
		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Type') . '</label>
				<select name="typeid" required="required" class="form-control">';
		while ($myrow = DB_fetch_array($result)) {
				if ($_POST['typeid']==$myrow['typeid']){
					echo '<option selected="selected" value="' . $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
				} else {
					echo '<option value="'. $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
				}
		} //end while loop
		DB_data_seek($result,0);
	}

	if (isset($_GET['Modify'])) {
		echo '</select></div></div></div>
		
		<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Customer Since') . ' (' . $_SESSION['DefaultDateFormat'] . '):</label>
				' . $_POST['ClientSince'] . '</div></div>';

		

		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Discount Percent') . ':</label>
				' . $_POST['Discount'] . '</div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Discount Code') . ':</label>
				' . $_POST['DiscountCode'] . '</div>
			</div>
			</div>
			<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Prompt Payment Discount Percent') . ':</label>
				' . $_POST['PymtDiscount'] . '</div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Credit Limit') . ':</label>
				' . $_POST['CreditLimit'] . '</div>
			</div>
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('GSTIN/PAN') . ':</label>
				' . $_POST['TaxRef'] . '</div>
			</div></div>';
	} else {
		echo '</select></div></div></div>
		
			<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Customer Since') . ' (' . $_SESSION['DefaultDateFormat'] . ')</label>
				<input ' . (in_array('ClientSince',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="date form-control" name="ClientSince" size="11" maxlength="10" value="' . $_POST['ClientSince'] . '" /></div>
			</div>
			';

		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Discount Percent') . '</label>
			<input type="text" name="Discount" class="number form-control" size="5" maxlength="4" value="' . $_POST['Discount'] . '" /></div>
			</div>
			
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Discount Code') . '</label>
				<input ' . (in_array('DiscountCode',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="form-control" name="DiscountCode" size="3" maxlength="2" value="' . $_POST['DiscountCode'] . '" /></div>
			</div></div>
			
			<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Prompt Payment Discount Percent') . '</label>
				<input ' . (in_array('PymtDiscount',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="number form-control" name="PymtDiscount" size="5" maxlength="4" value="' . $_POST['PymtDiscount'] . '" /></div>
			</div>
			
				<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Credit Limit') . '</label>
			  <input ' . (in_array('CreditLimit',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="integer form-control" name="CreditLimit" required="required" size="16" maxlength="14" value="' . $_POST['CreditLimit'] . '" /></div>
			</div>
			
			<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('GSTIN/PAN') . '</label>
				<input type="text" name="TaxRef" size="22" maxlength="20" class="form-control"  value="' . $_POST['TaxRef'] . '" /></div>
			</div></div>';
	}
echo '<div class="row">';
	if (isset($_GET['Modify'])) {
		$result=DB_query("SELECT terms FROM paymentterms WHERE termsindicator='".$_POST['PaymentTerms']."'");
		$myrow=DB_fetch_array($result);
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Payment Terms') . '</label>
				' . $myrow['terms'] . '</div>
			</div>';
	} else {
		$result=DB_query("SELECT terms, termsindicator FROM paymentterms");
		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Payment Terms') . '</label>
			   <select name="PaymentTerms" required="required" class="form-control">';
		while ($myrow = DB_fetch_array($result)) {
			if ($_POST['PaymentTerms']==$myrow['termsindicator']){
				echo '<option selected="selected" value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
			} else {
				echo '<option value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
			}
		} //end while loop
		DB_data_seek($result,0);
		echo '</select></div>
			</div>';
	}

	if (isset($_GET['Modify'])) {
		$result=DB_query("SELECT reasondescription FROM holdreasons WHERE reasoncode='".$_POST['HoldReason']."'");
		$myrow=DB_fetch_array($result);
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Credit Status') . '</label>
				' . $myrow['reasondescription'] . '</div>
			</div>';
	} else {
		$result=DB_query("SELECT reasoncode, reasondescription FROM holdreasons");
		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Credit Status') . '</label>
				<select name="HoldReason" required="required" class="form-control">';
		while ($myrow = DB_fetch_array($result)) {
			if ($_POST['HoldReason']==$myrow['reasoncode']){
				echo '<option selected="selected" value="'. $myrow['reasoncode'] . '">' . $myrow['reasondescription'] . '</option>';
			} else {
				echo '<option value="'. $myrow['reasoncode'] . '">' . $myrow['reasondescription'] . '</option>';
			}
		} //end while loop
		DB_data_seek($result,0);
		echo '</select></div>
			</div>';
	}

	if (isset($_GET['Modify'])) {
		echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Customer Currency') . '</label>
				' . $CurrencyName[$_POST['CurrCode']] . '</div></div></div>';
	} else {
		$result=DB_query("SELECT currency, currabrev FROM currencies");
		echo '<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">' . _('Customer Currency') . '</label>
				<select name="CurrCode" required="required" class="form-control">';
		while ($myrow = DB_fetch_array($result)) {
			echo '<option';
			if ($_POST['CurrCode']==$myrow['currabrev']){
				echo ' selected="selected"';
			}
			echo ' value="'. $myrow['currabrev'] . '">' . $CurrencyName[$myrow['currabrev']] . '</option>';
		} //end while loop
		DB_data_seek($result,0);
		echo '</select></div>
			</div></div>';
	}

    if (!isset($_POST['LanguageID']) OR $_POST['LanguageID']==''){
        $_POST['LanguageID']=$_SESSION['Language'];
    }
	echo '<input type="hidden" name="LanguageID" value="en_IN.utf8" />';
	
//echo '';
//	if (isset($_GET['Modify'])) {
//        echo '<div class="col-xs-4">
//               <div class="form-group"> 
//			   <label class="col-md-8 control-label">' . _('Language') . '</label>';
//        foreach ($LanguagesArray as $LanguageCode => $LanguageName){
//            if ($_POST['LanguageID'] == $LanguageCode){
//                echo '' . $LanguageName['LanguageName'];
//            }
//        }
//        echo '</div>
//        </div>';
//	} else {
//        echo '<div class="col-xs-4">
//               <div class="form-group has-error"> 
//			   <label class="col-md-8 control-label">' . _('Language') . '</label>
//               <select name="LanguageID" required="required" class="form-control">';
//        foreach ($LanguagesArray as $LanguageCode => $LanguageName){
//            if ($_POST['LanguageID'] == $LanguageCode){
//                echo '<option selected="selected" value="' . $LanguageCode . '">' . $LanguageName['LanguageName']  . '</option>';
//            } else {
//                echo '<option value="' . $LanguageCode . '">' . $LanguageName['LanguageName']  . '</option>';
//            }
//        }
//        echo '</select></div>
//        </div>';
//    }
	echo '<div class="row"><div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Require Customer PO Line on SO') . '</label>';
	if (isset($_GET['Modify'])) {
		if ($_POST['CustomerPOLine']==0){
			echo '' . _('No') . '';
		} else {
			echo '' . _('Yes') . '';
		}
	} else {
		echo '<select name="CustomerPOLine" class="form-control">';
		if ($_POST['CustomerPOLine']==0){
			echo '<option selected="selected" value="0">' . _('No') . '</option>';
			echo '<option value="1">' . _('Yes') . '</option>';
		} else {
			echo '<option value="0">' . _('No') . '</option>';
			echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
		}
		echo '</select></div></div>';
	}
	

	if (isset($_GET['Modify'])) {
		if ($_POST['InvAddrBranch']==0){
			echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Invoice Address') . '</label>
					' . _('To HO') . '</div>
				</div></div>';
		} else {
			echo '<div class="row"><div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Invoice Address') . '</label>
					' . _('To Branch') . '</div>
				</div></div>';
		}
	} else {
		echo '
		<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">' . _('Invoice Address') . '</label>
			<select name="AddrInvBranch" class="form-control">';
		if ($_POST['InvAddrBranch']==0){
			echo '<option selected="selected" value="0">' . _('To HO') . '</option>';
			echo '<option value="1">' . _('To Branch') . '</option>';
		} else {
			echo '<option value="0">' . _('To HO') . '</option>';
			echo '<option selected="selected" value="1">' . _('To Branch') . '</option>';
		}
	}

	echo '</select></div>
		</div></div>
		';

	if (isset($_GET['delete'])) { //User hit delete link on customer contacts
		/*Process this first before showing remaining contacts */
		$resultupcc = DB_query("DELETE FROM custcontacts
								WHERE debtorno='".$DebtorNo."'
								AND contid='".$ID."'");
		echo prnMsg(_('Contact Deleted'),'success');
	}

  //	$sql = "SELECT contid,
//					debtorno,
//					contactname,
//					role,
//					phoneno,
//					notes,
//					email
//			FROM custcontacts
//			WHERE debtorno='".$DebtorNo."'
//			ORDER BY contid";
//	$result = DB_query($sql);
//
//	echo '<div class="table-responsive">
//			<table id="general-table" class="table table-bordered">';
//	if (isset($_GET['Modify'])) {
//		echo '<tr>
//				<th>' . _('Name') . '</th>
//				<th>' . _('Role') . '</th>
//				<th>' . _('Phone Number') . '</th>
//				<th>' . _('Email') . '</th>
//				<th>' . _('Notes') . '</th>
//			</tr>';
//	} else {
//		echo '<tr>
//				<th>' . _('Name') . '</th>
//				<th>' . _('Role') . '</th>
//				<th>' . _('Phone Number') . '</th>
//				<th>' . _('Email') . '</th>
//				<th>' . _('Notes') . '</th>
//				<th colspan="2">' . _('Actions') . '</th>
//				
//			</tr>';
//	}
//
//	while ($myrow = DB_fetch_array($result)) {
//
//		if (isset($_GET['Modify'])) {
//			printf('<tr class="striped_row">
//					<td>%s</td>
//					<td>%s</td>
//					<td>%s</td>
//					<td><a href="mailto:%s">%s</a></td>
//					<td>%s</td>
//					</tr>',
//					$myrow['contactname'],
//					$myrow['role'],
//					$myrow['phoneno'],
//					$myrow['email'],
//					$myrow['email'],
//					$myrow['notes']);
//		} else {
//			printf('<tr class="striped_row">
//					<td>%s</td>
//					<td>%s</td>
//					<td>%s</td>
//					<td><a href="mailto:%s">%s</a></td>
//					<td>%s</td>
//					<td><a href="AddCustomerContacts.php?Id=%s&amp;DebtorNo=%s" class="btn btn-warning">' .  _('Edit'). '</a></td>
//					<td><a href="%sID=%s&amp;DebtorNo=%s&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this customer contact?') . '\');">' .  _('Delete'). '</a></td>
//					</tr>',
//					$myrow['contactname'],
//					$myrow['role'],
//					$myrow['phoneno'],
//					$myrow['email'],
//					$myrow['email'],
//					$myrow['notes'],
//					$myrow['contid'],
//					$myrow['debtorno'],
//					htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
//					$myrow['contid'],
//					$myrow['debtorno']);
//		}
//	}//END WHILE LIST LOOP
//	echo '</table></div>';

	//echo'</td></tr></table>';

	if (isset($_POST['New']) AND $_POST['New']) {
		echo '<div class="row" align="center">
		
               
				<input type="submit" name="submit" value="' . _('Submit') . '" class="btn btn-success" /></div>
				
				</div><br />';
	} elseif (!isset($_GET['Modify'])){
		echo '<br />
			<div class="row">
			<div class="col-xs-4"></div>
		<div class="col-xs-4">
               
				<input type="submit" name="submit" value="' . _('Update Customer') . '" class="btn btn-success" />
				</div>
				<div class="col-xs-4">
              
				<input type="submit" name="delete" value="' . _('Delete Customer') . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');" class="btn btn-danger" />
			</div></div><br />';
	}

	echo '
          </form>
		  </div>
		  </div>
		 
		 
		  ';
} // end of main ifs
//echo '</div>';
//echo '</div>';
include('includes/footer.php');
?>
