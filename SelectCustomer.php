<?php
/* Selection of customer - from where all customer related maintenance, transactions and inquiries start */

include('includes/session.php');
$Title = _('Search Customers');
$ViewTopic = 'AccountsReceivable';
$BookMark = 'SelectCustomer';
include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');

if(isset($_GET['Select'])) {
	$_SESSION['CustomerID'] = $_GET['Select'];
}

if(!isset($_SESSION['CustomerID'])) {// initialise if not already done
	$_SESSION['CustomerID'] = '';
}

if(isset($_GET['Area'])) {
	$_POST['Area'] = $_GET['Area'];
	$_POST['Search'] = 'Search';
	$_POST['Keywords'] = '';
	$_POST['CustCode'] = '';
	$_POST['CustPhone'] = '';
	$_POST['CustAdd'] = '';
	$_POST['CustType'] = '';
}

if(!isset($_SESSION['CustomerType'])) {// initialise if not already done
	$_SESSION['CustomerType'] = '';
}

if(isset($_POST['JustSelectedACustomer'])) {
	if(isset ($_POST['SubmitCustomerSelection'])) {
	foreach ($_POST['SubmitCustomerSelection'] as $CustomerID => $BranchCode)
		$_SESSION['CustomerID'] = $CustomerID;
		$_SESSION['BranchCode'] = $BranchCode;
	} else {
		echo prnMsg(_('Unable to identify the selected customer'), 'error');
	}
}

$msg = '';

if(isset($_POST['Go1']) OR isset($_POST['Go2'])) {
	$_POST['PageOffset'] = (isset($_POST['Go1']) ? $_POST['PageOffset1'] : $_POST['PageOffset2']);
	$_POST['Go'] = '';
}

if(!isset($_POST['PageOffset'])) {
	$_POST['PageOffset'] = 1;
} else {
	if($_POST['PageOffset'] == 0) {
		$_POST['PageOffset'] = 1;
	}
}

if($_SESSION['CustomerID'] != '' AND !isset($_POST['Search']) AND !isset($_POST['CSV'])) {
	// A customer is selected
	if(!isset($_SESSION['BranchCode'])) {
		// !isset($_SESSION['BranchCode'])
		$SQL = "SELECT debtorsmaster.name,
					custbranch.phoneno,
					custbranch.brname
			FROM debtorsmaster INNER JOIN custbranch
			ON debtorsmaster.debtorno=custbranch.debtorno
			WHERE custbranch.debtorno='" . $_SESSION['CustomerID'] . "'";

	} else {
		// isset($_SESSION['BranchCode'])
		$SQL = "SELECT debtorsmaster.name,
					custbranch.phoneno,
					custbranch.brname
			FROM debtorsmaster INNER JOIN custbranch
			ON debtorsmaster.debtorno=custbranch.debtorno
			WHERE custbranch.debtorno='" . $_SESSION['CustomerID'] . "'
			AND custbranch.branchcode='" . $_SESSION['BranchCode'] . "'";
	}
	$ErrMsg = _('The customer name requested cannot be retrieved because');
	$result = DB_query($SQL, $ErrMsg);
	if($myrow = DB_fetch_array($result)) {
		$CustomerName = htmlspecialchars($myrow['name'], ENT_QUOTES, 'UTF-8', false);
		$PhoneNo = $myrow['phoneno'];
		$BranchName = $myrow['brname'];
	}// $myrow = DB_fetch_array($result)
	unset($result);
$TableHead ='
	
';		               
	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ',// Icon title.
		_('Customer'), ': ', $_SESSION['CustomerID'], ' - ', $CustomerName, ' - ', $PhoneNo, '</h1></a></div>',// Page title.
		'',// Page help text.
		'<div class="row gutter30">
<div class="col-md-12"> ',
		$TableHead,
			'
<div class="col-md-6">
	<div class="block">
		                <div class="block-title">
                            <h2>Operations</h2>
                        </div>
	';
	// Customer transactions options:
	echo '<ul class="list-unstyled"><li><a href="', $RootPath, '/SelectSalesOrder.php?SelectedCustomer=', urlencode($_SESSION['CustomerID']), '">' . _('Open Sales Orders') . '</a></li>';
	echo '<li><a title="' . _('This allows the deposits received from the customer to be matched against invoices') . '" href="', $RootPath, '/CustomerAllocations.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '">' . _('Allocations') . '</a></li>';
	if(isset($_SESSION['CustomerID']) AND isset($_SESSION['BranchCode'])) {
	echo '<li><a href="', $RootPath, '/CounterSales.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '&amp;BranchNo=' . $_SESSION['BranchCode'] . '">' . _('New Counter Sale') . '</a></li>';
	}
	//witholding tax links
	$sql_wht="SELECT witholdingtaxexempted
	FROM companies ";
	
	$result_wht = DB_query($sql_wht);
	$row_wht = DB_fetch_array($result_wht);
	if($row_wht['witholdingtaxexempted']==0){
		echo '<li><a href="' . $RootPath . '/CustomerWitholdingTax.php?New=true&amp;DebtorNo=' . $_SESSION['CustomerID'] . '">' . _('TDS') . '</a></li>';
	}
	echo '<li><a href="', $RootPath, '/Customers.php">' . _('Add a New Customer') . '</a></li>';
	echo '<li><a href="', $RootPath, '/Customers.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '">' . _('Modify Details') . '</a></li>';
	echo '<li><a href="', $RootPath, '/CustomerBranches.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '">' . _('Add/Edit/Delete Branches') . '</a></li>';
	echo '<li><a href="', $RootPath, '/SelectProduct.php">' . _('Special Prices') . '</a></li>';
	
	echo '<li><a href="', $RootPath, '/CustLoginSetup.php">' . _('Customer Logins'), '</a></li>';
	echo '<li><a href="', $RootPath, '/AddCustomerContacts.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '">', _('Contacts'), '</a></li>';
	echo '<li><a href="', $RootPath, '/AddCustomerNotes.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '">', _('Notes'), '</a></li>';
	echo '</ul></div></div>
				
			<div class="col-md-6">
			<div class="block">
		                <div class="block-title">
                            <h2>Inquiries and Reports</h2>
                        </div>
			';
	// Customer inquiries options:
	echo '<ul class="list-unstyled"><li><a href="', $RootPath, '/CustomerInquiry.php?CustomerID=', urlencode($_SESSION['CustomerID']), '">' . _('Transaction Inquiries') . '</a></li>';
	echo '<li><a href="', $RootPath, '/SelectCompletedOrder.php?SelectedCustomer=', urlencode($_SESSION['CustomerID']), '">' . _('Order Inquiries') . '</a></li>';
	echo '<li><a href="', $RootPath, '/CustomerAccount.php?CustomerID=', urlencode($_SESSION['CustomerID']), '">' . _('Account statement') . '</a></li>';
	echo '<li><a href="', $RootPath, '/Customers.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '&amp;Modify=No">' . _('View Details') . '</a></li>';
	echo '<li><a href="', $RootPath, '/PrintCustStatements.php?FromCust=', urlencode($_SESSION['CustomerID']), '&amp;ToCust=', urlencode($_SESSION['CustomerID']), '&amp;EmailOrPrint=print&amp;PrintPDF=Yes">' . _('Print Statement') . '</a></li>';
	echo '<li><a title="' . _('One of the customer\'s contacts must have an email address and be flagged as the address to send the customer statement to for this function to work') . '" href="', $RootPath, '/PrintCustStatements.php?FromCust=', urlencode($_SESSION['CustomerID']), '&amp;ToCust=', urlencode($_SESSION['CustomerID']), '&amp;EmailOrPrint=email&amp;PrintPDF=Yes">' . _('Email Statement') . '</a></li>';
	
	echo '<li><a href="', $RootPath, '/CustomerPurchases.php?DebtorNo=', urlencode($_SESSION['CustomerID']), '">' . _('Purchase History') . '</a></li>';
	//wikiLink('Customer', $_SESSION['CustomerID']);
	echo '</ul></div></div>
	
		</div></div>';
} 

else {
	// Customer is not selected yet
	echo '
<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
		_('Customers'), '</h1></a></div><div class="row gutter30">
<div class="col-md-12">',// Page title.
		'',
		$TableHead,
		'',
			'<div class="col-md-6"></div>',// Customer inquiries options.
			'<div class="col-md-6"></div>',// Customer transactions options.
			'';
	if(!isset($_SESSION['SalesmanLogin']) OR $_SESSION['SalesmanLogin'] == '') {
		echo '<div class="col-md-12" align="center"><a href="', $RootPath, '/Customers.php" class="btn btn-info">', _('Add a New Customer'), '</a></div>
';
	}
	echo '',// Item maintenance options.
		'</div></div>';
}

// End search for customers.
if(isset($_SESSION['CustomerID']) AND $_SESSION['CustomerID'] != '') {
	// Extended Customer Info only if selected in Configuration
	if($_SESSION['Extended_CustomerInfo'] == 1) {
		if($_SESSION['CustomerID'] != '') {
			$SQL = "SELECT debtortype.typeid,
							debtortype.typename
						FROM debtorsmaster INNER JOIN debtortype
					ON debtorsmaster.typeid = debtortype.typeid
					WHERE debtorsmaster.debtorno = '" . $_SESSION['CustomerID'] . "'";
			$ErrMsg = _('An error occurred in retrieving the information');
			$result = DB_query($SQL, $ErrMsg);
			$myrow = DB_fetch_array($result);
			$CustomerType = $myrow['typeid'];
			$CustomerTypeName = $myrow['typename'];
			// Customer Data
			echo '<br />';
			// Select some basic data about the Customer
			$SQL = "SELECT debtorsmaster.clientsince,
						(TO_DAYS(date(now())) - TO_DAYS(date(debtorsmaster.clientsince))) as customersincedays,
						(TO_DAYS(date(now())) - TO_DAYS(date(debtorsmaster.lastpaiddate))) as lastpaiddays,
						debtorsmaster.paymentterms,
						debtorsmaster.lastpaid,
						debtorsmaster.lastpaiddate,
						currencies.decimalplaces AS currdecimalplaces
					FROM debtorsmaster INNER JOIN currencies
					ON debtorsmaster.currcode=currencies.currabrev
					WHERE debtorsmaster.debtorno ='" . $_SESSION['CustomerID'] . "'";
			$DataResult = DB_query($SQL);
			$myrow = DB_fetch_array($DataResult);
			// Select some more data about the customer
			$SQL = "SELECT sum(ovamount+ovgst) as total
					FROM debtortrans
					WHERE debtorno = '" . $_SESSION['CustomerID'] . "'
					AND type !=12";
			$Total1Result = DB_query($SQL);
			$row = DB_fetch_array($Total1Result);
			echo '<div class="col-xs-12">
		<div class="block">
		                <div class="block-title">
                            <h2>', _('History'), '</h2>
				</div>
				<div class="table-responsive">
			<table id="general-table" class="table table-bordered">
				<tr>
					<td class="select" valign="top">';
			/* Customer Data */
			if($myrow['lastpaiddate'] == 0) {
				echo _('No receipts from this customer.'), '</td>
					<td class="select">&nbsp;</td>
					<td class="select">&nbsp;</td>
				</tr>';
			} else {
				echo _('Last Payment Received On'), '</td>
					<td class="select"><strong>' . ConvertSQLDate($myrow['lastpaiddate']), '</strong></td>
					<td class="select">', $myrow['lastpaiddays'], ' ', _('days'), '</td>
				</tr>';
			}
			echo '<tr>
					<td class="select">', _('Last Paid Amount (inc tax)'), '</td>
					<td class="select"><strong>', locale_number_format($myrow['lastpaid'], $myrow['currdecimalplaces']), '</strong></td>
					<td class="select">&nbsp;</td>
				</tr>';
			echo '<tr>
					<td class="select">', _('Customer since'), '</td>
					<td class="select"><strong>', ConvertSQLDate($myrow['clientsince']), '</strong></td>
					<td class="select">', $myrow['customersincedays'], ' ', _('days'), '</td>
				</tr>';
			if($row['total'] == 0) {
				echo '<tr>
						<td class="select"><strong>', _('No Business from this Customer.'), '</strong></td>
						<td class="select">&nbsp;</td>
						<td class="select">&nbsp;</td>
					</tr>';
			} else {
				echo '<tr>
						<td class="select">' . _('Total Business from this Customer (inc tax)') . '</td>
						<td class="select"><strong>' . locale_number_format($row['total'], $myrow['currdecimalplaces']) . '</strong></td>
						<td class="select"></td>
						</tr>';
			}
			echo '<tr>
					<td class="select">', _('Customer Type'), '</td>
					<td class="select"><strong>', $CustomerTypeName, '</strong></td>
					<td class="select">&nbsp;</td>
				</tr>';
			echo '</table></div></div></div>';
		}// end if $_SESSION['CustomerID'] != ''

		// Customer Contacts
		$SQL = "SELECT * FROM custcontacts
				WHERE debtorno='" . $_SESSION['CustomerID'] . "'
				ORDER BY contid";
		$result = DB_query($SQL);

		if(DB_num_rows($result) <> 0) {
			echo '<br /><div class="col-xs-12">
		<div class="block">
		                <div class="block-title"><h2>' . ' ' . _('Contacts') . '</h2></div>';
			echo '<div class="table-responsive">
			<table id="general-table" class="table table-bordered">
 					<thead>
						<tr>
							<th class="ascending">' . _('Name') . '</th>
							<th class="ascending">' . _('Role') . '</th>
							<th class="ascending">' . _('Phone Number') . '</th>
							<th class="ascending">' . _('Email') . '</th>
							<th class="text">' . _('Statement') . '</th>
							<th class="text">', _('Notes'), '</th>
							<th colspan="2">', _('Actions'), '</th>
							
						</tr>
					</thead>
					
					<tbody>';

			while ($myrow = DB_fetch_array($result)) {
				echo '<tr class="striped_row">
					<td>' , $myrow[2] , '</td>
					<td>' , $myrow[3] , '</td>
					<td>' , $myrow[4] , '</td>
					<td><a href="mailto:' , $myrow[6] , '">' , $myrow[6] . '</a></td>
					<td>' , ($myrow[7]==0) ? _('No') : _('Yes'), '</td>
					<td>' , $myrow[5] , '</td>
					<td><a href="AddCustomerContacts.php?Id=' , $myrow[0] , '&amp;DebtorNo=' , $myrow[1] , '" class="btn btn-info">' , _('Edit') , '</a></td>
					<td><a href="AddCustomerContacts.php?Id=' , $myrow[0] , '&amp;DebtorNo=' , $myrow[1] , '&amp;delete=1" class="btn btn-danger">' , _('Delete') , '</a></td>
					</tr>';
			}// END WHILE LIST LOOP

			// Customer Branch Contacts if selected
			if(isset ($_SESSION['BranchCode']) AND $_SESSION['BranchCode'] != '') {
				$SQL = "SELECT
							branchcode,
							brname,
							contactname,
							phoneno,
							email
						FROM custbranch
						WHERE debtorno='" . $_SESSION['CustomerID'] . "'
							AND branchcode='" . $_SESSION['BranchCode'] . "'";
				$result2 = DB_query($SQL);
				$BranchContact = DB_fetch_row($result2);

				echo '<tr class="striped_row">
						<td>' . $BranchContact[2] . '</td>
						<td>' . _('Branch Contact') . ' ' . $BranchContact[0] . '</td>
						<td>' . $BranchContact[3] . '</td>
						<td><a href="mailto:' . $BranchContact[4] . '">' . $BranchContact[4] . '</a></td>
						<td colspan="3"></td>
					</tr>';
			}
			echo '</tbody>
			</table></div></div></div>';
		}// end if there are contact rows returned
		else {
			if($_SESSION['CustomerID'] != '') {
				
			}
		}
		// Customer Notes
		$SQL = "SELECT
					noteid,
					debtorno,
					href,
					note,
					date,
					priority
				FROM custnotes
				WHERE debtorno='" . $_SESSION['CustomerID'] . "'
				ORDER BY date DESC";
		$result = DB_query($SQL);
		if(DB_num_rows($result) <> 0) {
			echo '<div class="col-xs-12">
		<div class="block">
		                <div class="block-title"><h2>' . ' ' . _('Notes') . '</h2></div>';
			echo '<div class="table-responsive">
			<table id="general-table" class="table table-bordered">
				<thead>
					<tr>
					<th class="ascending">' . _('Date') . '</th>
					<th>' . _('Note') . '</th>
					<th>' . _('Reference') . '</th>
					<th class="ascending">' . _('Priority') . '</th>
					<th colspan="2">' . _('Actions') . '</th>
	
					</tr>
				</thead>
				<tbody>';

			while ($myrow = DB_fetch_array($result)) {
				echo '<tr class="striped_row">
					<td>' . ConvertSQLDate($myrow['date']) . '</td>
					<td>' . $myrow['note'] . '</td>
					<td>' . $myrow['href'] . '</td>
					<td>' . $myrow['priority'] . '</td>
					<td><a href="AddCustomerNotes.php?Id=' . $myrow['noteid'] . '&amp;DebtorNo=' . $myrow['debtorno'] . '" class="btn btn-info">' . _('Edit') . '</a></td>
					<td><a href="AddCustomerNotes.php?Id=' . $myrow['noteid'] . '&amp;DebtorNo=' . $myrow['debtorno'] . '&amp;delete=1" class="btn btn-danger">' . _('Delete') . '</a></td>
					</tr>';
			}// END WHILE LIST LOOP
			echo '</tbody></table></div></div></div><br />';
		}// end if there are customer notes to display
		else {
			if($_SESSION['CustomerID'] != '') {
				
			}
		}
		// Custome Type Notes
		$SQL = "SELECT * FROM debtortypenotes
				WHERE typeid='" . $CustomerType . "'
				ORDER BY date DESC";
		$result = DB_query($SQL);
		if(DB_num_rows($result) <> 0) {
			echo '<div class="col-xs-6">
		<div class="block">
		                <div class="block-title"><h2>' . ' ' . _('Customer Type (Group) Notes for:' . ' ' . $CustomerTypeName . '') . '</h2></div>';
			echo '<div class="table-responsive">
			<table id="general-table" class="table table-bordered">
				<thead>
					<tr>
				 	<th class="ascending">' . _('Date') . '</th>
					<th>' . _('Note') . '</th>
					<th>' . _('File Link / Reference / URL') . '</th>
					<th class="ascending">' . _('Priority') . '</th>
					<th>' . _('Edit') . '</th>
					<th>' . _('Delete') . '</th>
					<th><a href="AddCustomerTypeNotes.php?DebtorType=' . $CustomerType . '">' . _('Add New Group Note') . '</a></th>
					</tr>
				</thead>
				<tbody>';

			while ($myrow = DB_fetch_array($result)) {
				echo '<tr class="striped_row">
					<td>' . $myrow[4] . '</td>
					<td>' . $myrow[3] . '</td>
					<td>' . $myrow[2] . '</td>
					<td>' . $myrow[5] . '</td>
					<td><a href="AddCustomerTypeNotes.php?Id=' . $myrow[0] . '&amp;DebtorType=' . $myrow[1] . '">' . _('Edit') . '</a></td>
					<td><a href="AddCustomerTypeNotes.php?Id=' . $myrow[0] . '&amp;DebtorType=' . $myrow[1] . '&amp;delete=1">' . _('Delete') . '</a></td>
					</tr>';
			}// END WHILE LIST LOOP
			echo '</tbody></table></div></div></div>';
		}// end if there are customer group notes to display
		else {
			if($_SESSION['CustomerID'] != '') {
				
			}
		}
	}// end if Extended_CustomerInfo is turned on
}// end if isset($_SESSION['CustomerID']) AND $_SESSION['CustomerID'] != ''

if(isset($_SESSION['SalesmanLogin']) AND $_SESSION['SalesmanLogin'] != '') {
	echo prnMsg(_('Your account enables you to see only customers allocated to you'), 'warn', _('Note: Sales-person Login'));
}
// Search for customers:
echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
	'<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	//unset($_SESSION['CustomerID']);
if(mb_strlen($msg) > 1) {
	echo   prnMsg($msg, 'info');
}

echo '<div class="row">';

echo '<br /><div class="col-xs-4">
        <div class="form-group"> 
        <label class="col-md-8 control-label">', _('Name-part or full'), '</label>
		<input type="text" maxlength="25" name="Keywords" title="', _('If there is an entry in this field then customers with the text entered in their name will be returned') , '" class="form-control"  size="20" ',
			( isset($_POST['Keywords']) ? 'value="' . $_POST['Keywords'] . '" ' : '' ), '/></div></div>';

echo '<div class="col-xs-4">
        <div class="form-group"> 
        <label class="col-md-8 control-label">', _('Code-part or full'), '</label>
		<input maxlength="18" class="form-control" name="CustCode" pattern="[\w-]*" size="15" type="text" title="', _('If there is an entry in this field then customers with the text entered in their customer code will be returned') , '" ', (isset($_POST['CustCode']) ? 'value="' . $_POST['CustCode'] . '" ' : '' ), '/></div>
	</div>';

echo '<div class="col-xs-4">
        <div class="form-group"> 
        <label class="col-md-8 control-label">', _('Phone Number-part or full'), '</label>
		<input maxlength="18" class="form-control" name="CustPhone" pattern="[0-9\-\s()+]*" size="15" type="tel" ',
			( isset($_POST['CustPhone']) ? 'value="' . $_POST['CustPhone'] . '" ' : '' ), '/></div></div></div>';

echo '<div class="row"><div class="col-xs-4">
        <div class="form-group"> 
        <label class="col-md-8 control-label">', _('Address-part or full'), '</label>
		<input maxlength="25" name="CustAdd" class="form-control" size="20" type="text" ',
			(isset($_POST['CustAdd']) ? 'value="' . $_POST['CustAdd'] . '" ' : '' ), '/></div>
	</div>';

echo '<div class="col-xs-4">
        <div class="form-group"> 
        <label class="col-md-8 control-label">', _('Choose a Type'), '</label>
		';
if(isset($_POST['CustType'])) {
	// Show Customer Type drop down list
	$result2 = DB_query("SELECT typeid, typename FROM debtortype ORDER BY typename");
	// Error if no customer types setup
	if(DB_num_rows($result2) == 0) {
		$DataError = 1;
		echo '<a href="CustomerTypes.php" target="_parent">' . _('Setup Types') . '</a>';
		echo '<p class="text-danger">' . prnMsg(_('No Customer types defined'), 'error') . '</p></div></div>';
	} else {
		// If OK show select box with option selected
		echo '<select name="CustType" class="form-control">
				<option value="ALL">' . _('Any') . '</option>';
		while ($myrow = DB_fetch_array($result2)) {
			if($_POST['CustType'] == $myrow['typename']) {
				echo '<option selected="selected" value="' . $myrow['typename'] . '">' . $myrow['typename'] . '</option>';
			}// $_POST['CustType'] == $myrow['typename']
			else {
				echo '<option value="' . $myrow['typename'] . '">' . $myrow['typename'] . '</option>';
			}
		}// end while loop
		DB_data_seek($result2, 0);
		echo '</select></div></div>';
	}
} else {// CustType is not set
	// No option selected="selected" yet, so show Customer Type drop down list
	$result2 = DB_query("SELECT typeid, typename FROM debtortype ORDER BY typename");
	// Error if no customer types setup
	if(DB_num_rows($result2) == 0) {
		$DataError = 1;
		echo '<a href="CustomerTypes.php" target="_parent">' . _('Setup Types') . '</a>';
		echo '<p class="text-danger">' . prnMsg(_('No Customer types defined'), 'error') . '</p></div></div>';
	} else {
		// if OK show select box with available options to choose
		echo '<select name="CustType" class="form-control">
				<option value="ALL">' . _('Any') . '</option>';
		while ($myrow = DB_fetch_array($result2)) {
			echo '<option value="' . $myrow['typename'] . '">' . $myrow['typename'] . '</option>';
		}// end while loop
		DB_data_seek($result2, 0);
		echo '</select></div></div>';
	}
}

/* Option to select a sales area */
echo '<div class="col-xs-4">
        <div class="form-group"> 
        <label class="col-md-8 control-label">' . _('Sales Area') . '</label>';
$result2 = DB_query("SELECT areacode, areadescription FROM areas");
// Error if no sales areas setup
if(DB_num_rows($result2) == 0) {
	$DataError = 1;
	echo '<a href="Areas.php" target="_parent">' . _('Setup Areas') . '</a>';
	echo '<p class="text-danger">' . prnMsg(_('No Sales Areas defined'), 'error') . '</p></div></div>';
} else {
	// if OK show select box with available options to choose
	echo '<select name="Area" class="form-control">';
	echo '<option value="ALL">' . _('Any') . '</option>';
	while ($myrow = DB_fetch_array($result2)) {
		if(isset($_POST['Area']) AND $_POST['Area'] == $myrow['areacode']) {
			echo '<option selected="selected" value="' . $myrow['areacode'] . '">' . $myrow['areadescription'] . '</option>';
		} else {
			echo '<option value="' . $myrow['areacode'] . '">' . $myrow['areadescription'] . '</option>';
		}
	}// end while loop
	DB_data_seek($result2, 0);
	echo '</select></div></div></div>';
}

echo '<div class="row" align="center">';
echo '
		<input name="Search" type="submit" class="btn btn-success" value="', _('Search'), '" /></div><br />';

if(isset($_POST['Search']) OR isset($_POST['CSV']) OR isset($_POST['Go']) OR isset($_POST['Next']) OR isset($_POST['Previous'])) {
	unset($_POST['JustSelectedACustomer']);
	if(isset($_POST['Search'])) {
		$_POST['PageOffset'] = 1;
	}

	if(($_POST['Keywords'] == '') AND ($_POST['CustCode'] == '') AND ($_POST['CustPhone'] == '') AND ($_POST['CustType'] == 'ALL') AND ($_POST['Area'] == 'ALL') AND ($_POST['CustAdd'] == '')) {
		// no criteria set then default to all customers
		$SQL = "SELECT debtorsmaster.debtorno,
					debtorsmaster.name,
					debtorsmaster.address1,
					debtorsmaster.address2,
					debtorsmaster.address3,
					debtorsmaster.address4,
					custbranch.branchcode,
					custbranch.brname,
					custbranch.contactname,
					debtortype.typename,
					custbranch.phoneno,
					custbranch.faxno,
					custbranch.email
				FROM debtorsmaster LEFT JOIN custbranch
				ON debtorsmaster.debtorno = custbranch.debtorno
				INNER JOIN debtortype
				ON debtorsmaster.typeid = debtortype.typeid";
	} else {
		$SearchKeywords = mb_strtoupper(trim(str_replace(' ', '%', $_POST['Keywords'])));
		$_POST['CustCode'] = mb_strtoupper(trim($_POST['CustCode']));
		$_POST['CustPhone'] = trim($_POST['CustPhone']);
		$_POST['CustAdd'] = trim($_POST['CustAdd']);
		$SQL = "SELECT debtorsmaster.debtorno,
						debtorsmaster.name,
						debtorsmaster.address1,
						debtorsmaster.address2,
						debtorsmaster.address3,
						debtorsmaster.address4,
						custbranch.branchcode,
						custbranch.brname,
						custbranch.contactname,
						debtortype.typename,
						custbranch.phoneno,
						custbranch.faxno,
						custbranch.email
					FROM debtorsmaster INNER JOIN debtortype
						ON debtorsmaster.typeid = debtortype.typeid
					LEFT JOIN custbranch
						ON debtorsmaster.debtorno = custbranch.debtorno
					WHERE debtorsmaster.name " . LIKE . " '%" . $SearchKeywords . "%'
					AND debtorsmaster.debtorno " . LIKE . " '%" . $_POST['CustCode'] . "%'
					AND (custbranch.phoneno " . LIKE . " '%" . $_POST['CustPhone'] . "%' OR custbranch.phoneno IS NULL)
					AND (debtorsmaster.address1 " . LIKE . " '%" . $_POST['CustAdd'] . "%'
						OR debtorsmaster.address2 " . LIKE . " '%" . $_POST['CustAdd'] . "%'
						OR debtorsmaster.address3 " . LIKE . " '%" . $_POST['CustAdd'] . "%'
						OR debtorsmaster.address4 " . LIKE . " '%" . $_POST['CustAdd'] . "%')";// If there is no custbranch set, the phoneno in custbranch will be null, so we add IS NULL condition otherwise those debtors without custbranches setting will be no searchable and it will make a inconsistence with customer receipt interface.

		if(mb_strlen($_POST['CustType']) > 0 AND $_POST['CustType'] != 'ALL') {
			$SQL .= " AND debtortype.typename = '" . $_POST['CustType'] . "'";
		}

		if(mb_strlen($_POST['Area']) > 0 AND $_POST['Area'] != 'ALL') {
			$SQL .= " AND custbranch.area = '" . $_POST['Area'] . "'";
		}

	}// one of keywords OR custcode OR custphone was more than a zero length string

	if($_SESSION['SalesmanLogin'] != '') {
		$SQL .= " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
	}

	$SQL .= " ORDER BY debtorsmaster.name";
	$ErrMsg = _('The searched customer records requested cannot be retrieved because');

	$result = DB_query($SQL, $ErrMsg);
	if(DB_num_rows($result) == 1) {
		$myrow = DB_fetch_array($result);
		$_SESSION['CustomerID'] = $myrow['debtorno'];
		$_SESSION['BranchCode'] = $myrow['branchcode'];
		unset($result);
		unset($_POST['Search']);
	} elseif(DB_num_rows($result) == 0) {
		echo prnMsg(_('No customer records contain the selected text') . ' - ' . _('please alter your search criteria AND try again'), 'info');
		
	}
	
if(isset($result)) {

	unset($_SESSION['CustomerID']);
	$ListCount = DB_num_rows($result);
	$ListPageMax = ceil($ListCount / $_SESSION['DisplayRecordsMax']);
	
		if(isset($_POST['Next'])) {
			if($_POST['PageOffset'] < $ListPageMax) {
				$_POST['PageOffset'] = $_POST['PageOffset'] + 1;
			}
		}
		if(isset($_POST['Previous'])) {
			if($_POST['PageOffset'] > 1) {
				$_POST['PageOffset'] = $_POST['PageOffset'] - 1;
			}
		}
		echo '<input type="hidden" name="PageOffset" value="' . $_POST['PageOffset'] . '" />';
		if($ListPageMax > 1) {
			echo '<div class="row">';
echo '' . $_POST['PageOffset'] . ' ' . _('of') . ' ' . $ListPageMax . ' ' . _('pages') . '. ' . _('Go to Page') . '</div> ';
			echo '<div class="row">
			<div class="col-xs-3">
            <div class="form-group"><select name="PageOffset1" class="form-control">';
			$ListPage = 1;
			while ($ListPage <= $ListPageMax) {
				if($ListPage == $_POST['PageOffset']) {
					echo '<option value="' . $ListPage . '" selected="selected">' . $ListPage . '</option>';
				} else {
					echo '<option value="' . $ListPage . '">' . $ListPage . '</option>';
				}
				$ListPage++;
			}
			echo '</select></div></div>
			   <div class="col-xs-3">
            <div class="form-group">
				<input type="submit" name="Go1" value="' . _('Go') . ' class="btn btn-info"" /></div></div>
				<div class="col-xs-3">
            <div class="form-group">
				<input type="submit" name="Previous" class="btn btn-info" value="' . _('Previous') . '" /></div></div>
				<div class="col-xs-3">
            <div class="form-group">
				<input type="submit" name="Next" value="' . _('Next') . '" class="btn btn-info" />';
			echo '</div></div></div>';
		}
		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
				<thead>
					<tr>
						<th class="ascending">' . _('Code') . '</th>
						<th class="ascending">' . _('Customer Name') . '</th>
						<th class="ascending">' . _('Branch') . '</th>
						<th class="ascending">' . _('Contact') . '</th>
						<th class="ascending">' . _('Type') . '</th>
						<th class="ascending">' . _('Phone') . '</th>
						<th class="ascending">' . _('Fax') . '</th>
						<th class="ascending">' . _('Email') . '</th>
					</tr>
				</thead>';
		$RowIndex = 0;

	if(DB_num_rows($result) <> 0) {
		
		
			DB_data_seek($result, ($_POST['PageOffset'] - 1) * $_SESSION['DisplayRecordsMax']);
		
		$i = 0;// counter for input controls
		echo '<tbody>';
		while (($myrow = DB_fetch_array($result)) AND ($RowIndex <> $_SESSION['DisplayRecordsMax'])) {
			echo '<tr class="striped_row">
				<td><button type="submit" name="SubmitCustomerSelection[', htmlspecialchars($myrow['debtorno'], ENT_QUOTES, 'UTF-8', false), ']" value="', htmlspecialchars($myrow['branchcode'], ENT_QUOTES, 'UTF-8', false), '"  class="btn btn-info ">', $myrow['debtorno'], ' ', $myrow['branchcode'], '</button></td>
				<td class="text">', htmlspecialchars($myrow['name'], ENT_QUOTES, 'UTF-8', false), '</td>
				<td class="text">', htmlspecialchars($myrow['brname'], ENT_QUOTES, 'UTF-8', false), '</td>
				<td class="text">', $myrow['contactname'], '</td>
				<td class="text">', $myrow['typename'], '</td>
				<td class="text">', $myrow['phoneno'], '</td>
				<td class="text">', $myrow['faxno'], '</td>
				<td><a href="mailto://'.$myrow['email'].'">' . $myrow['email']. '</a></td>
			</tr>';
			$i++;
			$RowIndex++;
			// end of page full new headings if
		}// end loop through customers
		echo '</tbody>';
		echo '</table></div></div></div>';
		echo '<input type="hidden" name="JustSelectedACustomer" value="Yes" />';
	}// end if there are customers to show
}// end if results to show

//}



	if(isset($ListPageMax) AND $ListPageMax > 1) {
		echo '<br /><div class="row">' . $_POST['PageOffset'] . ' ' . _('of') . ' ' . $ListPageMax . ' ' . _('pages') . '. ' . _('Go to Page') . '</div>';
		echo '<div class="row"><div class="col-xs-3">
            <div class="form-group"><select name="PageOffset2" class="form-control">';
		$ListPage = 1;
		while ($ListPage <= $ListPageMax) {
			if($ListPage == $_POST['PageOffset']) {
				echo '<option value="' . $ListPage . '" selected="selected">' . $ListPage . '</option>';
			}// $ListPage == $_POST['PageOffset']
			else {
				echo '<option value="' . $ListPage . '">' . $ListPage . '</option>';
			}
			$ListPage++;
		}// $ListPage <= $ListPageMax
		echo '</select></div></div>
		<div class="col-xs-3">
            <div class="form-group">
			<input type="submit" name="Go2" value="' . _('Go') . '" class="btn btn-success" />
			</div></div>
			<div class="col-xs-3">
            <div class="form-group">
			<input type="submit" name="Previous" value="' . _('Previous') . '" class="btn btn-default" />
			</div></div>
			<div class="col-xs-3">
            <div class="form-group">
			<input type="submit" name="Next" value="' . _('Next') . '" class="btn btn-default" />';
			
		echo '</div></div></div>';
	}// end if results to show


echo '</form>';
	
	
	
}// end of if search



include('includes/footer.php');
?>
