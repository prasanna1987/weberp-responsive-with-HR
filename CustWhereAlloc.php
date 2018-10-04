<?php
/* Shows to which invoices a receipt was allocated to */

include('includes/session.php');
$Title = _('Customer Allocation Inquiry');
$ViewTopic = 'ARInquiries';
$BookMark = 'WhereAllocated';
include('includes/header.php');

if(isset($_GET['TransNo']) AND isset($_GET['TransType'])) {
	$_POST['TransNo'] = (int)$_GET['TransNo'];
	$_POST['TransType'] = (int)$_GET['TransType'];
	$_POST['ShowResults'] = true;
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', $Title. '</h1></a></div>',// Page title.
'<div class="row gutter30">
<div class="col-xs-12">
<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	
	<div class="row">
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Transaaction Type') . '</label>
		<select tabindex="1" name="TransType" class="form-control"> ';

if(!isset($_POST['TransType'])) {
	$_POST['TransType']='10';
}
if($_POST['TransType']==10) {
	 echo '<option selected="selected" value="10">' . _('Invoice') . '</option>
			<option value="12">' . _('Receipt') . '</option>
			<option value="11">' . _('Credit Note') . '</option>';
} elseif($_POST['TransType'] == 12) {
	echo '<option selected="selected" value="12">' . _('Receipt') . '</option>
			<option value="10">' . _('Invoice') . '</option>
			<option value="11">' . _('Credit Note') . '</option>';
} elseif($_POST['TransType'] == 11) {
	echo '<option selected="selected" value="11">' . _('Credit Note') . '</option>
		<option value="10">' . _('Invoice') . '</option>
		<option value="12">' . _('Receipt') . '</option>';
}

echo '</select></div></div>';

if(!isset($_POST['TransNo'])) {$_POST['TransNo']='';}
echo '<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Transaction Number').'</label>
		<input tabindex="2" type="text" class="form-control" name="TransNo"  required="required" maxlength="10" size="10" value="'. $_POST['TransNo'] . '" /></div>
	</div>
	
	
	<div class="col-xs-4">
        <div class="form-group"><br />
		<input tabindex="3" type="submit" class="btn btn-success" name="ShowResults" value="' . _('Show') . '" />
	</div>
	</div>
	</div>
	';

if(isset($_POST['ShowResults']) AND  $_POST['TransNo']=='') {
	echo '<br />';
	echo prnMsg(_('The transaction number to be queried must be entered first'),'warn');
}

if(isset($_POST['ShowResults']) AND $_POST['TransNo']!='') {

/*First off get the DebtorTransID of the transaction (invoice normally) selected */
	$sql = "SELECT debtortrans.id,
				ovamount+ovgst AS totamt,
				currencies.decimalplaces AS currdecimalplaces,
				debtorsmaster.currcode,
				debtortrans.rate
			FROM debtortrans INNER JOIN debtorsmaster
			ON debtortrans.debtorno=debtorsmaster.debtorno
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE type='" . $_POST['TransType'] . "'
			AND transno = '" . $_POST['TransNo']."'";

	if($_SESSION['SalesmanLogin'] != '') {
			$sql .= " AND debtortrans.salesperson='" . $_SESSION['SalesmanLogin'] . "'";
	}
	$result = DB_query($sql );
	$GrandTotal = 0;
	$Rows = DB_num_rows($result);
	if($Rows>=1) {
		while($myrow = DB_fetch_array($result)) {
		$GrandTotal +=$myrow['totamt'];
		$Rate = $myrow['rate'];
		$AllocToID = $myrow['id'];
		$CurrCode = $myrow['currcode'];
		$CurrDecimalPlaces = $myrow['currdecimalplaces'];
		$sql = "SELECT type,
					transno,
					trandate,
					debtortrans.debtorno,
					reference,
					debtortrans.rate,
					ovamount+ovgst+ovfreight+ovdiscount as totalamt,
					custallocns.amt
				FROM debtortrans
				INNER JOIN custallocns ";
		if($_POST['TransType']==12 OR $_POST['TransType'] == 11) {

			$TitleInfo = ($_POST['TransType'] == 12)?_('Receipt'):_('Credit Note');
			if($myrow['totamt']<0) {
				$sql .= "ON debtortrans.id = custallocns.transid_allocto
					WHERE custallocns.transid_allocfrom = '" . $AllocToID . "'";
			} else {
				$sql .= "ON debtortrans.id = custallocns.transid_allocfrom
					WHERE custallocns.transid_allocto = '" . $AllocToID . "'";
		
			}

		} else {
			$TitleInfo = _('invoice');
			$sql .= "ON debtortrans.id = custallocns.transid_allocfrom
				WHERE custallocns.transid_allocto = '" . $AllocToID . "'";
		}
		$sql .= " ORDER BY transno ";

		$ErrMsg = _('The customer transactions for the selected criteria could not be retrieved because');
		$TransResult = DB_query($sql, $ErrMsg);

		if(DB_num_rows($TransResult)==0) {

			if($myrow['totamt']<0 AND ($_POST['TransType']==12 OR $_POST['TransType'] == 11)) {
					echo prnMsg(_('This transaction was a receipt of funds and there can be no allocations of receipts or credits to a receipt. This inquiry is meant to be used to see how a payment which is entered as a negative receipt is settled against credit notes or receipts'),'info');
			} else {
				echo prnMsg(_('There are no allocations made against this transaction'),'info');
			}
		} else {
			$Printer = true;
			echo '<br />
				<div id="Report">
				<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h2>' . _('Allocations made against') . ' ' . $TitleInfo . ' ' . _('number') . ' ' . $_POST['TransNo'] . '<br />' .
						_('Transaction Total').': '. locale_number_format($myrow['totamt'],$CurrDecimalPlaces) . ' ' . $CurrCode . '</h2></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">

				<thead>
				';

			$TableHeader = '<tr>
					<th>' . _('Date') . '</th>
					<th>' . _('Type') . '</th>
					<th>' . _('Number') . '</th>
					<th>' . _('Reference') . '</th>
					<th>' . _('Ex Rate') . '</th>
					<th>' . _('Amount') . '</th>
					<th>' . _('Allocated') . '</th>
				</tr>';
			echo $TableHeader,
				'</thead>
				<tbody>';

			$RowCounter = 1;
			$AllocsTotal = 0;

			while($myrow=DB_fetch_array($TransResult)) {

				if($myrow['type']==11) {
					$TransType = _('Credit Note');
				} elseif($myrow['type'] == 10) {
					$TransType = _('Invoice');
				} else {
					$TransType = _('Receipt');
				}
				echo '<tr class="striped_row">
						<td>', ConvertSQLDate($myrow['trandate']), '</td>
						<td>' . $TransType . '</td>
						<td>' . $myrow['transno'] . '</td>
						<td>' . $myrow['reference'] . '</td>
						<td>' . $myrow['rate'] . '</td>
						<td>' . locale_number_format($myrow['totalamt'], $CurrDecimalPlaces) . '</td>
						<td>' . locale_number_format($myrow['amt'], $CurrDecimalPlaces) . '</td>
					</tr>';

				$RowCounter++;
				if($RowCounter == 12) {
					$RowCounter=1;
					echo $TableHeader;
				}
				//end of page full new headings if
				$AllocsTotal += $myrow['amt'];
			}
			//end of while loop
			echo '<tr>
					<td colspan="6"><strong>'. _('Total allocated') . '</strong></td>
					<td>' . locale_number_format($AllocsTotal,$CurrDecimalPlaces) . '</td>
				</tr>

</tbody></table>
</div></div></div></div>

			</div>';
		} // end if there are allocations against the transaction
	} //got the ID of the transaction to find allocations for
} //end of while loop;
if ($Rows>1) {
	echo '<div class="row"><strong>' . _('Transaction Total'). ' ' .locale_number_format($GrandTotal,$CurrDecimalPlaces) . '</strong></div>';
}
if ($_POST['TransType']== 12) {
	//retrieve transaction to see if there are any transaction fee,
	$sql = "SELECT account,
						amount
					FROM gltrans LEFT JOIN bankaccounts ON account=accountcode
					WHERE type=12 AND typeno='".$_POST['TransNo']."' AND account !='". $_SESSION['CompanyRecord']['debtorsact'] ."' AND accountcode IS NULL";
	$ErrMsg = _('Failed to retrieve charge data');
	$result = DB_query($sql,$ErrMsg);
	if (DB_num_rows($result)>0) {
		while ($myrow = DB_fetch_array($result)){
			echo '<div class="row">
							<strong>'._('GL Account') .' ' . $myrow['account'] . '</strong> '. _('Amount') . locale_number_format($myrow['amount'],$CurrDecimalPlaces).'<br/> '. _('To local currency'). ' ' . locale_number_format($myrow['amount']*$Rate,$CurrDecimalPlaces).' ' . _('at rate') . ' ' . $Rate .
				
					'</div>';
					$GrandTotal += $myrow['amount'] * $Rate;
		}
		echo '<div class="row">
					<strong>' . _('Grand Total') . '</strong>' . ' ' . locale_number_format($GrandTotal,$CurrDecimalPlaces).'
		</div>';
	}
}
}

echo '</form>';
echo '</div></div>';
if(isset($Printer)) {
	echo '<div class="row noprint">
	<div class="col-xs-4">
			<button onclick="javascript:window.print()" type="button" class="btn btn-warning"> ', _('Print'), '</button>', // "Print" button.
		'</div></div><br />
';// "Print This" button.
}
include('includes/footer.php');
?>
