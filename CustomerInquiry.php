<?php
/* Shows the customers account transactions with balances outstanding, links available to drill down to invoice/credit note or email invoices/credit notes. */

include('includes/session.php');
$Title = _('Customer Inquiry');// Screen identification.
$ViewTopic = 'ARInquiries';// Filename's id in ManualContents.php's TOC.
$BookMark = 'CustomerInquiry';// Anchor's id in the manual's html document.
include('includes/header.php');

// always figure out the SQL required from the inputs available

if (!isset($_GET['CustomerID']) and !isset($_SESSION['CustomerID'])) {
	echo prnMsg(_('Select a customer to show enquiry'), 'info');
	echo '<br /><div class="row">
<div class="col-xs-4"><a href="', $RootPath, '/SelectCustomer.php" class="btn btn-info">', _('Select a Customer to Inquire On'), '</a></div></div><br />';
	include('includes/footer.php');
	exit;
} else {
	if (isset($_GET['CustomerID'])) {
		$_SESSION['CustomerID'] = stripslashes($_GET['CustomerID']);
	}
	$CustomerID = $_SESSION['CustomerID'];
}
//Check if the users have proper authority
if ($_SESSION['SalesmanLogin'] != '') {
	$ViewAllowed = false;
	$sql = "SELECT salesman FROM custbranch WHERE debtorno = '" . $CustomerID . "'";
	$ErrMsg = _('Failed to retrieve sales data');
	$result = DB_query($sql,$ErrMsg);
	if(DB_num_rows($result)>0) {
		while($myrow = DB_fetch_array($result)) {
			if ($_SESSION['SalesmanLogin'] == $myrow['salesman']){
				$ViewAllowed = true;
			}
		}
	} else {
		echo prnMsg(_('There is no salesman data set for this customer'),'error');
		include('includes/footer.php');
		exit;
	}
	if (!$ViewAllowed){
		echo prnMsg(_('You have no acess to view this customer'),'error');
		include('includes/footer.php');
		exit;
	}
}


if (isset($_GET['Status'])) {
	if (is_numeric($_GET['Status'])) {
		$_POST['Status'] = $_GET['Status'];
	}
} elseif (isset($_POST['Status'])) {
	if($_POST['Status'] == '' or $_POST['Status'] == 1 or $_POST['Status'] == 0) {
		$Status = $_POST['Status'];
	} else {
		echo prnMsg(_('The balance status should be all or zero balance or not zero balance'), 'error');
		exit;
	}
} else {
	$_POST['Status'] = '';
}

if (!isset($_POST['TransAfterDate'])) {
	$_POST['TransAfterDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, Date('m') - $_SESSION['NumberOfMonthMustBeShown'], Date('d'), Date('Y')));
}

$SQL = "SELECT debtorsmaster.name,
		currencies.currency,
		currencies.decimalplaces,
		paymentterms.terms,
		debtorsmaster.creditlimit,
		holdreasons.dissallowinvoices,
		holdreasons.reasondescription,
		SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) AS balance,
		SUM(CASE WHEN (paymentterms.daysbeforedue > 0) THEN
			CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) >= paymentterms.daysbeforedue
			THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
		ELSE
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate),paymentterms.dayinfollowingmonth)) >= 0 THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
		END) AS due,
		SUM(CASE WHEN (paymentterms.daysbeforedue > 0) THEN
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue
			AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ")
			THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
		ELSE
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate),paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays1'] . "
			THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount
			- debtortrans.alloc ELSE 0 END
		END) AS overdue1,
		SUM(CASE WHEN (paymentterms.daysbeforedue > 0) THEN
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue
			AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ") THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
		ELSE
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate),paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays2'] . " THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
		END) AS overdue2
		FROM debtorsmaster,
	 			paymentterms,
	 			holdreasons,
	 			currencies,
	 			debtortrans
		WHERE  debtorsmaster.paymentterms = paymentterms.termsindicator
	 		AND debtorsmaster.currcode = currencies.currabrev
	 		AND debtorsmaster.holdreason = holdreasons.reasoncode
	 		AND debtorsmaster.debtorno = '" . $CustomerID . "'
	 		AND debtorsmaster.debtorno = debtortrans.debtorno
			GROUP BY debtorsmaster.name,
			currencies.currency,
			paymentterms.terms,
			paymentterms.daysbeforedue,
			paymentterms.dayinfollowingmonth,
			debtorsmaster.creditlimit,
			holdreasons.dissallowinvoices,
			holdreasons.reasondescription";

$ErrMsg = _('The customer details could not be retrieved by the SQL because');
$CustomerResult = DB_query($SQL, $ErrMsg);

if (DB_num_rows($CustomerResult) == 0) {

	/*Because there is no balance - so just retrieve the header information about the customer - the choice is do one query to get the balance and transactions for those customers who have a balance and two queries for those who don't have a balance OR always do two queries - I opted for the former */

	$NIL_BALANCE = True;

	$SQL = "SELECT debtorsmaster.name,
					debtorsmaster.currcode,
					currencies.currency,
					currencies.decimalplaces,
					paymentterms.terms,
					debtorsmaster.creditlimit,
					holdreasons.dissallowinvoices,
					holdreasons.reasondescription
			FROM debtorsmaster INNER JOIN paymentterms
			ON debtorsmaster.paymentterms = paymentterms.termsindicator
			INNER JOIN currencies
			ON debtorsmaster.currcode = currencies.currabrev
			INNER JOIN holdreasons
			ON debtorsmaster.holdreason = holdreasons.reasoncode
			WHERE debtorsmaster.debtorno = '" . $CustomerID . "'";

	$ErrMsg = _('The customer details could not be retrieved by the SQL because');
	$CustomerResult = DB_query($SQL, $ErrMsg);

} else {
	$NIL_BALANCE = False;
}

$CustomerRecord = DB_fetch_array($CustomerResult);

if ($NIL_BALANCE == True) {
	$CustomerRecord['balance'] = 0;
	$CustomerRecord['due'] = 0;
	$CustomerRecord['overdue1'] = 0;
	$CustomerRecord['overdue2'] = 0;
}



echo '<div class="block-header"><a href="" class="header-title-link"><h1>', _('Customer'), ': ', stripslashes($CustomerID), ' - ', $CustomerRecord['name'], '</h1></a></div>';
	
	echo '<p align="left">
		<a href="', $RootPath, '/SelectCustomer.php" class="btn btn-default">', _('Back to Customers'), '</a></p><br />';
	

if ($CustomerRecord['dissallowinvoices'] != 0) {
	echo '<br /><div class="text-danger"><strong>', _('ACCOUNT ON HOLD'), '</strong></div><br />';
}

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<tr>
		<th>', _('Total Balance'), '</th>
		<th>', _('Current'), '</th>
		<th>', _('Now Due'), '</th>
		<th>', $_SESSION['PastDueDays1'], '-', $_SESSION['PastDueDays2'], ' ' . _('Days Overdue'), '</th>
		<th>', _('Over'), ' ', $_SESSION['PastDueDays2'], ' ', _('Days Overdue'), '</th>
	</tr>';

echo '<tr>
		<td class="number">', locale_number_format($CustomerRecord['balance'], $CustomerRecord['decimalplaces']), '</td>
		<td class="number">', locale_number_format(($CustomerRecord['balance'] - $CustomerRecord['due']), $CustomerRecord['decimalplaces']), '</td>
		<td class="number">', locale_number_format(($CustomerRecord['due'] - $CustomerRecord['overdue1']), $CustomerRecord['decimalplaces']), '</td>
		<td class="number">', locale_number_format(($CustomerRecord['overdue1'] - $CustomerRecord['overdue2']), $CustomerRecord['decimalplaces']), '</td>
		<td class="number">', locale_number_format($CustomerRecord['overdue2'], $CustomerRecord['decimalplaces']), '</td>
	</tr>
</table></div></div></div>';

echo '<br />
<form onSubmit="return VerifyForm(this);" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post" class="noPrint">
		<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error">
<label class="col-md-12 control-label">'._('Show all transactions after'), '</label>
<input type="text" required="required" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="TransAfterDate" value="', $_POST['TransAfterDate'], '" maxlength="10" size="11" />
</div>
</div>
';

echo '<div class="col-xs-4">
<div class="form-group">
<label class="col-md-12 control-label"><br /></label>
<select name="Status" class="form-control">';
if ($_POST['Status'] == '') {
	echo '<option value="" selected="selected">', _('All'), '</option>';
	echo '<option value="1">', _('Invoices not fully allocated'), '</option>';
	echo '<option value="0">', _('Invoices fully allocated'), '</option>';
} else {
	if ($_POST['Status'] == 0) {
		echo '<option value="">', _('All'), '</option>';
		echo '<option value="1">', _('Invoices not fully allocated'), '</option>';
		echo '<option selected="selected" value="0">', _('Invoices fully allocated'), '</option>';
	} elseif ($_POST['Status'] == 1) {
		echo '<option value="" selected="selected">', _('All'), '</option>';
		echo '<option selected="selected" value="1">', _('Invoices not fully allocated'), '</option>';
		echo '<option value="0">', _('Invoices fully allocated'), '</option>';
	}
}

echo '</select></div></div>';
echo '<div class="col-xs-4">
<div class="form-group"><br />
<input class="noprint btn btn-info" name="Refresh Inquiry" type="submit" value="', _('Refresh Inquiry'), '" />
</div></div>
</div><br />
	</form>';

$DateAfterCriteria = FormatDateForSQL($_POST['TransAfterDate']);

$SQL = "SELECT systypes.typename,
				debtortrans.id,
				debtortrans.type,
				debtortrans.transno,
				debtortrans.branchcode,
				debtortrans.trandate,
				debtortrans.reference,
				debtortrans.invtext,
				debtortrans.order_,
				salesorders.customerref,
				debtortrans.rate,
				(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount) AS totalamount,
				debtortrans.alloc AS allocated
			FROM debtortrans
			INNER JOIN systypes
				ON debtortrans.type = systypes.typeid
			LEFT JOIN salesorders
				ON salesorders.orderno=debtortrans.order_
			WHERE debtortrans.debtorno = '" . $CustomerID . "'
				AND debtortrans.trandate >= '" . $DateAfterCriteria . "'
				ORDER BY debtortrans.trandate,
					debtortrans.id";

$ErrMsg = _('No transactions were returned by the SQL because');
$TransResult = DB_query($SQL, $ErrMsg);

if (DB_num_rows($TransResult) == 0) {
	echo '<p class="text-info">', _('There are no transactions to display since'), ' ', $_POST['TransAfterDate'], '</p><br />';
	include('includes/footer.php');
	exit;
}

/* Show a table of the invoices returned by the SQL. */

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead>
	<tr>
		<th class="ascending">', _('Type'), '</th>
		<th class="ascending">', _('Number'), '</th>
		<th class="ascending">', _('Date'), '</th>
		<th>', _('Customer Branch'), '</th>
		<th class="ascending">', _('Reference'), '</th>
		<th>', _('Comments'), '</th>
		<th>', _('Order'), '</th>
		<th>', _('Total Receipts'), '</th>
		<th>', _('Allocated'), '</th>
		<th>', _('Balance'), '</th>
		<th colspan="4" align="center">', _('Action'), '</th>
		
	</tr>
	</thead><tbody>';

while ($MyRow = DB_fetch_array($TransResult)) {

	$FormatedTranDate = ConvertSQLDate($MyRow['trandate']);

	if ($_SESSION['InvoicePortraitFormat'] == 1) { //Invoice/credits in portrait
		$PrintCustomerTransactionScript = 'PrintCustTransPortrait.php';
	} else { //produce pdfs in landscape
		$PrintCustomerTransactionScript = 'PrintCustTrans.php';
	}

	/* if the user is allowed to create credits for invoices */
	if (in_array($_SESSION['PageSecurityArray']['Credit_Invoice.php'], $_SESSION['AllowedPageSecurityTokens']) and $MyRow['type'] == 10) {
		if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1 and in_array($_SESSION['PageSecurityArray']['GLTransInquiry.php'], $_SESSION['AllowedPageSecurityTokens'])) {
			/* Show transactions where:
			 * - Is invoice
			 * - User can raise credits
			 * - User can view GL transactions
			 */
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td><a href="' . $RootPath . '/CustWhereAlloc.php?TransType=' . $MyRow['type'] . '&TransNo=' . $MyRow['transno'] . '" target="_blank" class="btn btn-info">' . $MyRow['transno'] . '</a></td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td>', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">
						<a href="', $RootPath, '/Credit_Invoice.php?InvoiceNumber=', $MyRow['transno'], '" class="btn btn-success" title="', _('Click to credit the invoice'), '">
							',
							_('Credit'), '
						</a>
					</td>
					
					<td class="noprint">
						<a href="', $RootPath, '/', $PrintCustomerTransactionScript, '?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice&amp;PrintPDF=True" title="', _('Click for PDF'), '" class="btn btn-warning">
							',
							_('PDF'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/EmailCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice" title="', _('Click to email the invoice'), '" class="btn btn-info">
							 ', _('Email'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/GLTransInquiry.php?TypeID=', $MyRow['type'], '&amp;TransNo=', $MyRow['transno'], '" title="', _('Click to view the GL entries'), '" class="btn btn-info">
							 ',
							_('GL Entries'), '
						</a>
					</td>
				</tr>';
		} else {
			/* Show transactions where:
			 * - Is invoice
			 * - User can raise credits
			 * - User cannot view GL transactions
			 */
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td><a href="' . $RootPath . '/CustWhereAlloc.php?TransType=' . $MyRow['type'] . '&TransNo=' . $MyRow['transno'] . '" class="btn btn-info">' . $MyRow['transno'] . '</a></td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td>', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">
						<a href="', $RootPath, '/Credit_Invoice.php?InvoiceNumber=', $MyRow['transno'], '" title="', _('Click to credit the invoice'), '" class="btn btn-info">
							',
							_('Credit'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/PrintCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice" title="', _('Click to preview the invoice'), '" class="btn btn-warning">
							 ',
							_('HTML'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/', $PrintCustomerTransactionScript, '?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice&amp;PrintPDF=True" title="', _('Click for PDF'), '" class="btn btn-warning">
							',
							_('PDF'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/EmailCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice" title="', _('Click to email the invoice'), '" class="btn btn-warning">
							', _('Email'), '
						</a>
					</td>
					<td class="noprint">&nbsp;</td>
				</tr>';

		}

	} elseif ($MyRow['type'] == 10) {
		/* Show transactions where:
		 * - Is invoice
		 * - User cannot raise credits
		 * - User cannot view GL transactions
		 */
		echo '<tr class="striped_row">
				<td>', _($MyRow['typename']), '</td>
				<td>', $MyRow['transno'], '</td>
				<td>', ConvertSQLDate($MyRow['trandate']), '</td>
				<td>', $MyRow['branchcode'], '</td>
				<td>', $MyRow['reference'], '</td>
				<td>', $MyRow['invtext'], '</td>
				<td>', $MyRow['order_'], '</td>
				<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
				<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
				<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
				<td class="noprint">&nbsp;</td>
				<td class="noprint">
					<a href="', $RootPath, '/PrintCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice" title="', _('Click to preview the invoice'), '" class="btn btn-info">
						',
						_('HTML'), '
					</a>
				</td>
				<td class="noprint">
					<a href="', $RootPath, '/', $PrintCustomerTransactionScript, '?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice&amp;PrintPDF=True" title="', _('Click for PDF'), '" class="btn btn-warning">
						',
						_('PDF'), '
					</a>
				</td>
				<td class="noprint">
					<a href="', $RootPath, '/EmailCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Invoice" title="', _('Click to email the invoice'), '" class="btn btn-warning">
						', _('Email'), '
					</a>
				</td>
				<td class="noprint">&nbsp;</td>
			</tr>';

	} elseif ($MyRow['type'] == 11) {
		/* Show transactions where:
		 * - Is credit note
		 * - User can view GL transactions
		 */
		if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1 and in_array($_SESSION['PageSecurityArray']['GLTransInquiry.php'], $_SESSION['AllowedPageSecurityTokens'])) {
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td><a href="' . $RootPath . '/CustWhereAlloc.php?TransType=' . $MyRow['type'] . '&TransNo=' . $MyRow['transno'] . '" class="btn btn-info">' . $MyRow['transno'] . '</a></td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td style="width:200px">', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">
						<a href="', $RootPath, '/PrintCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Credit" title="', _('Click to preview the credit note'), '" class="btn btn-info">
							 ',
							_('HTML'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/', $PrintCustomerTransactionScript, '?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Credit&amp;PrintPDF=True" title="', _('Click for PDF'), '" class="btn btn-warning">
							 ',
							_('PDF'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/EmailCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Credit" class="btn btn-warning">', _('Email'), '
							
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/CustomerAllocations.php?AllocTrans=', $MyRow['id'], '" title="', _('Click to allocate funds'), '" class="btn btn-info">
							',
							_('Allocation'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/GLTransInquiry.php?TypeID=', $MyRow['type'], '&amp;TransNo=', $MyRow['transno'], '" title="', _('Click to view the GL entries'), '" class="btn btn-info">
							',
							_('GL Entries'), '
						</a>
					</td>
				</tr>';

		} else {
			/* Show transactions where:
			* - Is credit note
			* - User cannot view GL transactions
			*/
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td><a href="' . $RootPath . '/CustWhereAlloc.php?TransType=' . $MyRow['type'] . '&TransNo=' . $MyRow['transno'] . '" class="btn btn-info">' . $MyRow['transno'] . '</a></td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td>', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">
						<a href="', $RootPath, '/PrintCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Credit" title="', _('Click to preview the credit note'), '" class="btn btn-info">
							',
							_('HTML'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/', $PrintCustomerTransactionScript, '?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Credit&amp;PrintPDF=True" title="', _('Click for PDF'), '" class="btn btn-warning">
							',
							_('PDF'), '
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/EmailCustTrans.php?FromTransNo=', $MyRow['transno'], '&amp;InvOrCredit=Credit" class="btn btn-warning">', _('Email'), '
							
						</a>
					</td>
					<td class="noprint">
						<a href="', $RootPath, '/CustomerAllocations.php?AllocTrans=', $MyRow['id'], '" title="', _('Click to allocate funds'), '" class="btn btn-info">
							',
							_('Allocation'), '
						</a>
					</td>
					<td class="noprint">&nbsp;</td>
				</tr>';

		}
	} elseif ($MyRow['type'] == 12 and $MyRow['totalamount'] < 0) {
		/* Show transactions where:
		 * - Is receipt
		 * - User can view GL transactions
		 */
		if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1 and in_array($_SESSION['PageSecurityArray']['GLTransInquiry.php'], $_SESSION['AllowedPageSecurityTokens'])) {
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td><a href="' . $RootPath . '/CustWhereAlloc.php?TransType=' . $MyRow['type'] . '&TransNo=' . $MyRow['transno'] . '" class="btn btn-info">' . $MyRow['transno'] . '</a></td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td>', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">
						<a href="', $RootPath, '/CustomerAllocations.php?AllocTrans=', $MyRow['id'], '" title="', _('Click to allocate funds'), '" class="btn btn-info">
							 ',
							_('Allocation'), '
						</a>
					</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">
						<a href="', $RootPath, '/GLTransInquiry.php?TypeID=', $MyRow['type'], '&amp;TransNo=', $MyRow['transno'], '" title="', _('Click to view the GL entries'), '" class="btn btn-info">
							',
							_('GL Entries'), '
						</a>
					</td>
				</tr>';

		} else { //no permission for GLTrans Inquiries
		/* Show transactions where:
		 * - Is credit note
		 * - User cannot view GL transactions
		 */
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td>', $MyRow['transno'], '</td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td>', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">
						<a href="', $RootPath, '/CustomerAllocations.php?AllocTrans=', $MyRow['id'], '" title="', _('Click to allocate funds'), '" class="btn btn-info" class="btn btn-info">
							 ',
							_('Allocation'), '
						</a>
					</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
				</tr>';

		}
	} elseif ($MyRow['type'] == 12 and $MyRow['totalamount'] > 0) {
		if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1 and in_array($_SESSION['PageSecurityArray']['GLTransInquiry.php'], $_SESSION['AllowedPageSecurityTokens'])) {
			/* Show transactions where:
			* - Is a negative receipt
			* - User can view GL transactions
			*/
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td>', $MyRow['transno'], '</td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td>', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">
						<a href="', $RootPath, '/GLTransInquiry.php?TypeID=', $MyRow['type'], '&amp;TransNo=', $MyRow['transno'], '" title="', _('Click to view the GL entries'), '" class="btn btn-info">
							',
							_('GL Entries'), '
						</a>
					</td>
				</tr>';

		} else {
			/* Show transactions where:
			* - Is a negative receipt
			* - User cannot view GL transactions
			*/
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td>', $MyRow['transno'], '</td>
					<td>', ConvertSQLDate($MyRow['trandate']), ' </td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td>', $MyRow['invtext'],  '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
				</tr>';
		}
	} else {
		if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1 and in_array($_SESSION['PageSecurityArray']['GLTransInquiry.php'], $_SESSION['AllowedPageSecurityTokens'])) {
			/* Show transactions where:
			* - Is a misc transaction
			* - User can view GL transactions
			*/
			echo '<tr class="striped_row">
					<td>', _($MyRow['typename']), '</td>
					<td>', $MyRow['transno'], '</td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td style="width:200px">', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">
						<a href="', $RootPath, '/GLTransInquiry.php?TypeID=', $MyRow['type'], '&amp;TransNo=', $MyRow['transno'], '" title="', _('Click to view the GL entries'), '" class="btn btn-info">
							 ',
							_('GL Entries'), '
						</a>
					</td>
				</tr>';

		} else {
			/* Show transactions where:
			* - Is a misc transaction
			* - User cannot view GL transactions
			*/
			echo '<tr class="striped_row">
					<td>', $MyRow['typename'], '</td>
					<td>', $MyRow['transno'], '</td>
					<td>', ConvertSQLDate($MyRow['trandate']), '</td>
					<td>', $MyRow['branchcode'], '</td>
					<td>', $MyRow['reference'], '</td>
					<td style="width:200px">', $MyRow['invtext'], '</td>
					<td>', $MyRow['order_'], '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="number">', locale_number_format($MyRow['totalamount'] - $MyRow['allocated'], $CustomerRecord['decimalplaces']), '</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
					<td class="noprint">&nbsp;</td>
				</tr>';
		}
	}

}
//end of while loop

echo '</tbody></table></div></div></div>';
include('includes/footer.php');
?>
