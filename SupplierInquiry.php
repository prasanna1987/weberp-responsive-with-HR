<?php
/* Inquiry showing invoices, credit notes and payments made to suppliers together with the amounts outstanding. */

include('includes/session.php');
$Title = _('Supplier Inquiry');
$ViewTopic = 'AccountsPayable';// RChacon: Is there any content for Supplier Inquiry?
$BookMark = 'AccountsPayable';
include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');

// always figure out the SQL required from the inputs available

if(!isset($_GET['SupplierID']) AND !isset($_SESSION['SupplierID'])) {
	echo '<div class="row">' . _('To display the enquiry a Supplier must first be selected from the Supplier selection screen') .
		 '<br />
			
				<a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-default">' . _('Select a Supplier to Inquire On') . '</a>
			</div><br />';
	include('includes/footer.php');
	exit;
} else {
	if(isset($_GET['SupplierID'])) {
		$_SESSION['SupplierID'] = $_GET['SupplierID'];
	}
	$SupplierID = $_SESSION['SupplierID'];
}

if(isset($_GET['FromDate'])) {
	$_POST['TransAfterDate']=$_GET['FromDate'];
}
if(!isset($_POST['TransAfterDate']) OR !Is_Date($_POST['TransAfterDate'])) {
	$_POST['TransAfterDate'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m')-12,Date('d'),Date('Y')));
}

$SQL = "SELECT suppliers.suppname,
		suppliers.currcode,
		currencies.currency,
		currencies.decimalplaces AS currdecimalplaces,
		paymentterms.terms,
		SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS balance,
		SUM(CASE WHEN paymentterms.daysbeforedue > 0 THEN
			CASE WHEN (TO_DAYS(Now()) - TO_DAYS(supptrans.trandate)) >= paymentterms.daysbeforedue
			THEN supptrans.ovamount + supptrans.ovgst - supptrans.alloc ELSE 0 END
		ELSE
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(supptrans.trandate),paymentterms.dayinfollowingmonth)) >= 0 THEN supptrans.ovamount + supptrans.ovgst - supptrans.alloc ELSE 0 END
		END) AS due,
		SUM(CASE WHEN paymentterms.daysbeforedue > 0  THEN
			CASE WHEN (TO_DAYS(Now()) - TO_DAYS(supptrans.trandate)) > paymentterms.daysbeforedue
					AND (TO_DAYS(Now()) - TO_DAYS(supptrans.trandate)) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ")
			THEN supptrans.ovamount + supptrans.ovgst - supptrans.alloc ELSE 0 END
		ELSE
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(supptrans.trandate),paymentterms.dayinfollowingmonth)) >= '" . $_SESSION['PastDueDays1'] . "'
			THEN supptrans.ovamount + supptrans.ovgst - supptrans.alloc ELSE 0 END
		END) AS overdue1,
		Sum(CASE WHEN paymentterms.daysbeforedue > 0 THEN
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(supptrans.trandate) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(supptrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ")
			THEN supptrans.ovamount + supptrans.ovgst - supptrans.alloc ELSE 0 END
		ELSE
			CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(supptrans.trandate),paymentterms.dayinfollowingmonth)) >= '" . $_SESSION['PastDueDays2'] . "'
			THEN supptrans.ovamount + supptrans.ovgst - supptrans.alloc ELSE 0 END
		END ) AS overdue2
		FROM suppliers INNER JOIN paymentterms
		ON suppliers.paymentterms = paymentterms.termsindicator
     	INNER JOIN currencies
     	ON suppliers.currcode = currencies.currabrev
     	INNER JOIN supptrans
     	ON suppliers.supplierid = supptrans.supplierno
		WHERE suppliers.supplierid = '" . $SupplierID . "'
		GROUP BY suppliers.suppname,
      			currencies.currency,
      			currencies.decimalplaces,
      			paymentterms.terms,
      			paymentterms.daysbeforedue,
      			paymentterms.dayinfollowingmonth";
$ErrMsg = _('The supplier details could not be retrieved by the SQL because');
$DbgMsg = _('The SQL that failed was');
$SupplierResult = DB_query($SQL, $ErrMsg, $DbgMsg);

if(DB_num_rows($SupplierResult) == 0) {

	/*Because there is no balance - so just retrieve the header information about the Supplier - the choice is do one query to get the balance and transactions for those Suppliers who have a balance and two queries for those who don't have a balance OR always do two queries - I opted for the former */

	$NIL_BALANCE = True;

	$SQL = "SELECT suppliers.suppname,
					suppliers.currcode,
					currencies.currency,
					currencies.decimalplaces AS currdecimalplaces,
					paymentterms.terms
			FROM suppliers INNER JOIN paymentterms
		    ON suppliers.paymentterms = paymentterms.termsindicator
		    INNER JOIN currencies
		    ON suppliers.currcode = currencies.currabrev
			WHERE suppliers.supplierid = '" . $SupplierID . "'";

	$ErrMsg = _('The supplier details could not be retrieved by the SQL because');
	$DbgMsg = _('The SQL that failed was');

	$SupplierResult = DB_query($SQL, $ErrMsg, $DbgMsg);

} else {
	$NIL_BALANCE = False;
}

$SupplierRecord = DB_fetch_array($SupplierResult);

if($NIL_BALANCE == True) {
	$SupplierRecord['balance'] = 0;
	$SupplierRecord['due'] = 0;
	$SupplierRecord['overdue1'] = 0;
	$SupplierRecord['overdue2'] = 0;
}
include('includes/CurrenciesArray.php'); // To get the currency name from the currency code.

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
	_('Supplier'), ': ', $SupplierID, ' - ', $SupplierRecord['suppname'], '<br /><small>',
		_('All amounts stated in'), ': ', $SupplierRecord['currcode'], ' - ', $CurrencyName[$SupplierRecord['currcode']], '<br />',
		_('Terms'), ': ', $SupplierRecord['terms'], '</small></h1></a></div>';// Page title.

if(isset($_GET['HoldType']) AND isset($_GET['HoldTrans'])) {
	if($_GET['HoldStatus'] == _('Hold')) {
		$SQL = "UPDATE supptrans SET hold=1
				WHERE type='" . $_GET['HoldType'] . "'
				AND transno='" . $_GET['HoldTrans'] . "'";
	} elseif($_GET['HoldStatus'] == _('Release')) {
		$SQL = "UPDATE supptrans SET hold=0
				WHERE type='" . $_GET['HoldType'] . "'
				AND transno='" . $_GET['HoldTrans'] . "'";
	}
	$ErrMsg = _('The Supplier Transactions could not be updated because');
	$DbgMsg = _('The SQL that failed was');
	$UpdateResult = DB_query($SQL, $ErrMsg, $DbgMsg);
}

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<thead>
	<tr>
		<th>' . _('Total Balance') . '</th>
		<th>' . _('Current') . '</th>
		<th>' . _('Now Due') . '</th>
		<th>' . $_SESSION['PastDueDays1'] . '-' . $_SESSION['PastDueDays2'] . ' ' . _('Days Overdue') . '</th>
		<th>' . _('Over') . ' ' . $_SESSION['PastDueDays2'] . ' ' . _('Days Overdue') . '</th>
	</tr></thead>';

echo '<tr>
		  <td>' . locale_number_format($SupplierRecord['balance'],$SupplierRecord['currdecimalplaces']) . '</td>
		  <td>' . locale_number_format(($SupplierRecord['balance'] - $SupplierRecord['due']),$SupplierRecord['currdecimalplaces']) . '</td>
		  <td>' . locale_number_format(($SupplierRecord['due']-$SupplierRecord['overdue1']),$SupplierRecord['currdecimalplaces']) . '</td>
		  <td>' . locale_number_format(($SupplierRecord['overdue1']-$SupplierRecord['overdue2']) ,$SupplierRecord['currdecimalplaces']) . '</td>
		  <td>' . locale_number_format($SupplierRecord['overdue2'],$SupplierRecord['currdecimalplaces']) . '</td>
	  </tr>
	</table></div></div></div>';

echo '<br />
	<div class="row gutter30">
<div class="col-xs-12">
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '
        <input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">'. _('Show all transactions after') . '</label> '  . '<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="TransAfterDate" value="' . $_POST['TransAfterDate'] . '" maxlength="10" size="10" /> </div></div>
	 <div class="col-xs-4">
<div class="form-group"> <br />
   <input class="noprint btn btn-success" name="Refresh Inquiry" type="submit" value="' . _('Submit') . '" />
    </div>
	</div>
	</div>
	</form>
	<br />';
echo '</div></div>';
$DateAfterCriteria = FormatDateForSQL($_POST['TransAfterDate']);

$SQL = "SELECT supptrans.id,
			systypes.typename,
			supptrans.type,
			supptrans.transno,
			supptrans.trandate,
			supptrans.suppreference,
			supptrans.rate,
			(supptrans.ovamount + supptrans.ovgst) AS totalamount,
			supptrans.alloc AS allocated,
			supptrans.hold,
			supptrans.settled,
			supptrans.transtext,
			supptrans.supplierno
		FROM supptrans,
			systypes
		WHERE supptrans.type = systypes.typeid
		AND supptrans.supplierno = '" . $SupplierID . "'
		AND supptrans.trandate >= '" . $DateAfterCriteria . "'
		ORDER BY supptrans.trandate";
$ErrMsg = _('No transactions were returned by the SQL because');
$DbgMsg = _('The SQL that failed was');
$TransResult = DB_query($SQL, $ErrMsg, $DbgMsg);

if(DB_num_rows($TransResult) == 0) {
	echo '<br /><div class="row"><p class="text-danger"><strong>' . _('There are no transactions to display since') . ' ' . $_POST['TransAfterDate'];
	echo '</strong></p></div>';
	include('includes/footer.php');
	exit;
}

/*show a table of the transactions returned by the SQL */

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">

	<thead>
	<tr>
		<th class="ascending">' . _('Date') . '</th>
		<th class="ascending">' . _('Type') . '</th>
		<th class="ascending">' . _('Transaction Number') . '</th>
		<th class="ascending">' . _('Reference') . '</th>
		<th class="ascending">' . _('Comments') . '</th>
		<th class="ascending">' . _('Total') . '</th>
		<th class="ascending">' . _('Allocated') . '</th>
		<th class="ascending">' . _('Balance') . '</th>
		<th>' . _('Action') . '</th>
		<th>' . _('View') . '</th>
		>
	</tr>
	</thead>
	<tbody>';

$AuthSQL = "SELECT offhold
			FROM purchorderauth
			WHERE userid='" . $_SESSION['UserID'] . "'
			AND currabrev='" . $SupplierRecord['currcode']."'";
$AuthResult = DB_query($AuthSQL);
$AuthRow = DB_fetch_array($AuthResult);

$j = 1;

while($MyRow = DB_fetch_array($TransResult)) {
	if($MyRow['hold'] == 0 AND $MyRow['settled'] == 0) {
		$HoldValue = _('Hold');
	} elseif($MyRow['settled'] == 1) {
		$HoldValue = '';
	} else {
		$HoldValue = _('Release');
	}

	// Comment: All table-row (tag tr) must have 10 table-datacells (tag td).

	if($MyRow['hold'] == 1) {
		echo '<tr>';
	} else {
		echo '<tr class="striped_row">';
	}

	// Prints first 8 columns that are in common (columns 1-8):
	echo '<td class="centre">', ConvertSQLDate($MyRow['trandate']), '</td>
		<td class="text">', _($MyRow['typename']), '</td>
		<td><a href="', $RootPath, '/SuppWhereAlloc.php?TransType=', $MyRow['type'], '&TransNo=', $MyRow['transno'], '" class="btn btn-info">', $MyRow['transno'], '</a></td>
		<td class="text">', $MyRow['suppreference'], '</td>
		<td class="text">', $MyRow['transtext'], '</td>
		<td>', locale_number_format($MyRow['totalamount'], $SupplierRecord['currdecimalplaces']), '</td>
		<td>', locale_number_format($MyRow['allocated'], $SupplierRecord['currdecimalplaces']), '</td>
		<td>', locale_number_format($MyRow['totalamount']-$MyRow['allocated'], $SupplierRecord['currdecimalplaces']), '</td>';

	// STORE "Link to GL transactions inquiry" column to use in some of the cases (column 10):
	$GLEntriesTD1 = '<td class="noprint"><a href="' . $RootPath . '/GLTransInquiry.php?TypeID=' . $MyRow['type'] . '&amp;TransNo=' . $MyRow['transno'] . '" target="_blank" title="' . _('Click to view the GL entries') . '"class="btn btn-info">' . _('GL Entries') . '</a></td>';

	// Now prints columns 9 and 10:
	if($MyRow['type'] == 20) {// It is a Purchase Invoice (systype = 20).
		if($_SESSION['CompanyRecord']['gllink_creditors'] == True) {// Show a link to GL transactions inquiry:
/*			if($MyRow['totalamount'] - $MyRow['allocated'] == 0) {// The transaction is settled so don't show option to hold:*/
			if($MyRow['totalamount'] == $MyRow['allocated']) {// The transaction is settled so don't show option to hold:
				echo '<td class="noprint"><a href="', $RootPath, '/PaymentAllocations.php?SuppID=', $MyRow['supplierno'], '&amp;InvID=', $MyRow['suppreference'], '" title="', _('Click to view payments'), '" class="btn btn-info">', _('Payments'), '</a></td>';// Payment column (column 9).
			} else {// The transaction is NOT settled so show option to hold:
				if($AuthRow['offhold'] == 0) {
					echo '<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES,'UTF-8'), '?HoldType=', $MyRow['type'], '&amp;HoldTrans=', $MyRow['transno'], '&amp;HoldStatus=', $HoldValue, '&amp;FromDate=', $_POST['TransAfterDate'], '" class="btn btn-info">', $HoldValue, '</a></td>';// Column 9.
				} else {
					if($HoldValue == _('Release')) {
						echo '<td class="noprint">', $HoldValue , '</a></td>';// Column 9.
					} else {
						echo '<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES,'UTF-8'), '?HoldType=', $MyRow['type'], '&amp;HoldTrans=', $MyRow['transno'], '&amp;HoldStatus=', $HoldValue, '&amp;FromDate=', $_POST['TransAfterDate'], '" class="btn btn-info">', $HoldValue, '</a></td>';// Column 9.
					}
				}
			}
			echo $GLEntriesTD1;// Column 10.

		} else {// Do NOT show a link to GL transactions inquiry:
/*			if($MyRow['totalamount'] - $MyRow['allocated'] == 0) {// The transaction is settled so don't show option to hold:*/
			if($MyRow['totalamount'] == $MyRow['allocated']) {// The transaction is settled so don't show option to hold:
				echo '<td class="noprint">&nbsp;</td>',// Column 9.
					'<td class="noprint">&nbsp;</td>';// Column 10.
			} else {// The transaction is NOT settled so show option to hold:
				echo '<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES,'UTF-8'), '/PaymentAllocations.php?SuppID=',
						$MyRow['type'], '&amp;InvID=', $MyRow['transno'], '" class="btn btn-info">', _('View Payments'), '</a></td>',// Column 9.
					'<td class="noprint"><a href="' .htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES,'UTF-8'), '?HoldType=', $_POST['TransAfterDate'], '&amp;HoldTrans=', $HoldValue, '&amp;HoldStatus=' .
						$RootPath, '&amp;FromDate=', $MyRow['supplierno'], '" class="btn btn-info">' . $MyRow['suppreference'], '</a></td>';// Column 10.
			}
		}

	} else {// It is NOT a Purchase Invoice (a credit note or a payment).
		echo '<td class="noprint"><a href="', $RootPath, '/SupplierAllocations.php?AllocTrans=', $MyRow['id'], '" title="', _('Click to allocate funds'), '" class="btn btn-success"> ', _('Allocate'), '</a></td>';// Allocation column (column 9).
		if($_SESSION['CompanyRecord']['gllink_creditors'] == True) {// Show a link to GL transactions inquiry:
			echo $GLEntriesTD1;// Column 10.
		} else {// Do NOT show a link to GL transactions inquiry:
			echo '<td class="noprint">&nbsp;</td>';// Column 10.
		}
	}// END printing columns 9 and 10.
	echo '</tr>';// Close the table row.
}// End of while loop

echo '</tbody></table></div></div></div>';
include('includes/footer.php');
?>