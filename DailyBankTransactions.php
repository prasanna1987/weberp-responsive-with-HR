<?php
/* Allows you to view all bank transactions for a selected date range, and the inquiry can be filtered by matched or unmatched transactions, or all transactions can be chosen. */

include('includes/session.php');
$Title = _('Daily Bank Transactions');// Screen identification.
$ViewTopic = 'GeneralLedger';// Filename's id in ManualContents.php's TOC.
$BookMark = 'DailyBankTransactions';// Anchor's id in the manual's html document.
include('includes/header.php');

if (!isset($_POST['Show'])) {
	$SQL = "SELECT 	bankaccountname,
					bankaccounts.accountcode,
					bankaccounts.currcode
			FROM bankaccounts,
				chartmaster,
				bankaccountusers
			WHERE bankaccounts.accountcode=chartmaster.accountcode
				AND bankaccounts.accountcode=bankaccountusers.accountcode
			AND bankaccountusers.userid = '" . $_SESSION['UserID'] ."'";

	$ErrMsg = _('The bank accounts could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the bank accounts was');
	$AccountsResults = DB_query($SQL,$ErrMsg,$DbgMsg);

	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
		_('Bank Transactions Inquiry') . '</h1></a></div>';// Page title.

echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Bank Account') . '</label>
			<select name="BankAccount" class="form-control">';

	if (DB_num_rows($AccountsResults)==0){
		echo '</select></div>
				</div>';
		echo prnMsg( _('Bank Accounts have not yet been defined. You must first') . ' <a href="' . $RootPath . '/BankAccounts.php">' . _('define the bank accounts') . '</a> ' . _('and general ledger accounts to be affected'),'warn');
		include('includes/footer.php');
		exit;
	} else {
		while ($myrow=DB_fetch_array($AccountsResults)){
		/*list the bank account names */
			if (!isset($_POST['BankAccount']) AND $myrow['currcode']==$_SESSION['CompanyRecord']['currencydefault']){
				$_POST['BankAccount']=$myrow['accountcode'];
			}
			if ($_POST['BankAccount']==$myrow['accountcode']){
				echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . ' - ' . $myrow['currcode'] . '</option>';
			} else {
				echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . ' - ' . $myrow['currcode'] . '</option>';
			}
		}
		echo '</select></div></div>';
	}
	echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Date From') . '</label>
			<input type="text" name="FromTransDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" required="required" maxlength="10" size="11" onchange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' .
				date($_SESSION['DefaultDateFormat']) . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Date To') . '</label>
			<input type="text" name="ToTransDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" required="required" maxlength="10" size="11" onchange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' . date($_SESSION['DefaultDateFormat']) . '" /></div>
		</div></div>
		<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Show Transactions') . '</label>
			<select name="ShowType" class="form-control">
				<option value="All">' . _('All') . '</option>
				<option value="Unmatched">' . _('Unmatched') . '</option>
				<option value="Matched">' . _('Matched') . '</option>
			</select></div>
			</div>
		
		<div class="col-xs-4">
<div class="form-group"> <br />
			<input type="submit" class="btn btn-info" name="Show" value="' . _('Show'). '" />
		</div>
        </div>
		</div>
		</form></div></div>';
} else {
	$SQL = "SELECT 	bankaccountname,
					bankaccounts.currcode,
					currencies.decimalplaces
			FROM bankaccounts
			INNER JOIN currencies
				ON bankaccounts.currcode = currencies.currabrev
			WHERE bankaccounts.accountcode='" . $_POST['BankAccount'] . "'";
	$BankResult = DB_query($SQL,_('Could not retrieve the bank account details'));


	$sql="SELECT (SELECT sum(banktrans.amount) FROM banktrans
				WHERE transdate < '" . FormatDateForSQL($_POST['FromTransDate']) . "'
				AND bankact='" . $_POST['BankAccount'] ."') AS prebalance,
					banktrans.currcode,
					banktrans.amount,
					banktrans.amountcleared,
					banktrans.functionalexrate,
					banktrans.exrate,
					banktrans.banktranstype,
					banktrans.transdate,
					banktrans.transno,
					banktrans.ref,
					bankaccounts.bankaccountname,
					systypes.typename,
					systypes.typeid
				FROM banktrans
				INNER JOIN bankaccounts
				ON banktrans.bankact=bankaccounts.accountcode
				INNER JOIN systypes
				ON banktrans.type=systypes.typeid
				WHERE bankact='".$_POST['BankAccount']."'
					AND transdate>='" . FormatDateForSQL($_POST['FromTransDate']) . "'
					AND transdate<='" . FormatDateForSQL($_POST['ToTransDate']) . "'
				ORDER BY banktrans.transdate ASC, banktrans.banktransid ASC";
	$result = DB_query($sql);

	$BankDetailRow = DB_fetch_array($BankResult);
	if (DB_num_rows($result)==0) {
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>' .// Icon title.
		_('Bank Transactions Inquiry') . '</h1></a></div>';// Page title.
		echo prnMsg(_('There are no transactions for this account in the date range selected'), 'error');

		$sql = "SELECT sum(banktrans.amount) FROM banktrans WHERE bankact='" . $_POST['BankAccount'] . "'";
		$ErrMsg = _('Failed to retrive balance data');
		$balresult = DB_query($sql,$ErrMsg);
		if (DB_num_rows($balresult)>0) {
			$Balance = DB_fetch_row($balresult);
			$Balance = $Balance[0];
			if (ABS($Balance)>0.001){
				echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('The Bank Account Balance Is in') . '  ' . $BankDetailRow['currcode'] . ' ' . locale_number_format($Balance,$BankDetailRow['decimalplaces']) . '</h1></a></div>';
			}
		}

	} else {
		echo '<div id="Report">';// Division to identify the report block.
		echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
			_('Account Transactions For').'<br />'.$BankDetailRow['bankaccountname'].'<br /><small>'.
			_('Between').' '.$_POST['FromTransDate'] . ' ' . _('and') . ' ' . $_POST['ToTransDate'] . '</h1></a></div>';// Page title.*/
		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
				<tr>
					<th>' . ('Date') . '</th>
					<th>' . _('Transaction type') . '</th>
					<th>' . _('Transaction Number') . '</th>
					<th>' . _('Transaction Mode') . '</th>
					<th>' . _('Reference') . '</th>
					<th>' . _('Amount in orginal currency') . '</th>
					<th>' . _('Balance in') . ' ' . $BankDetailRow['currcode'] . '</th>
					<th>' . _('Running Total').' '.$BankDetailRow['currcode'] . '</th>
					<th>' . _('Amount in').' '.$_SESSION['CompanyRecord']['currencydefault'] . '</th>
					<th>' . _('Running Total').' '.$_SESSION['CompanyRecord']['currencydefault'] . '</th>
					<th>' . _('Cleared?') . '</th>
					<th>' . _('GL Narrative') . '</th>
				</tr>
			</thead><tbody>';

		$AccountCurrTotal=0;
		$LocalCurrTotal =0;
		$Balance = 0;
		$j = 0;
		while ($myrow = DB_fetch_array($result)){

			if ($j == 0) {
				if (ABS($myrow['prebalance'])>0.0001) {
					$Balance += $myrow['prebalance'];
					echo '<tr class="striped_row">
							<td colspan="6" style="font-weight:bold">' . _('Previous Balance') . '</td>
						<td class="number">' . locale_number_format($myrow['prebalance'],$BankDetailRow['decimalplaces']) . '</td>
							<td colspan="5"></td>
						</tr>';
					$j++;

				}
			}

			//check the GL narrative
			if ($myrow['type'] == 2) {
				$myrow['typeid'] = 1;
				$myrow['transno'] = substr($myrow['ref'],1,strpos($myrow['ref'],' ')-1);
			}
			$sql = "SELECT narrative FROM gltrans WHERE type='" . $myrow['typeid'] . "' AND typeno='" . $myrow['transno'] . "'";
			$ErrMsg = _('Failed to retrieve gl narrative');
			$glresult = DB_query($sql,$ErrMsg);
			if (DB_num_rows($glresult)>0) {
				$GLNarrative = DB_fetch_array($glresult);
				$GLNarrative = $GLNarrative[0];
			} else {
				$GLNarrative = 'NA';
			}
			$Balance += $myrow['amount']/$myrow['exrate'];
			$AccountCurrTotal += $myrow['amount']/$myrow['exrate'];
			$LocalCurrTotal += $myrow['amount']/$myrow['functionalexrate']/$myrow['exrate'];

			if ($myrow['amount']==$myrow['amountcleared']) {
				$Matched=_('Yes');
			} else {
				$Matched=_('No');
			}

			echo '<tr class="striped_row">
					<td class="centre">' .  ConvertSQLDate($myrow['transdate']) . '</td>
					<td>' . _($myrow['typename']) . '</td>
					<td class="number"><a href="' . $RootPath . '/GLTransInquiry.php?TypeID=' . $myrow['typeid'] . '&amp;TransNo=' . $myrow['transno'] . '" class="btn btn-info">' . $myrow['transno'] . '</a></td>
					<td>' . $myrow['banktranstype'] . '</td>
					<td>' . $myrow['ref'] . '</td>
					<td class="number">' . locale_number_format($myrow['amount'],$BankDetailRow['decimalplaces']) . ' ' . $myrow['currcode'] . '</td>
					<td class="number">' . locale_number_format($Balance,$BankDetailRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($AccountCurrTotal,$BankDetailRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($myrow['amount']/$myrow['functionalexrate']/$myrow['exrate'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($LocalCurrTotal,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
					<td class="number">' . $Matched . '</td>
					<td>' . $GLNarrative . '</td>
				</tr>';
		}

		echo '<tr class="striped_row">
				<td colspan="6" style="font-weight:bold;">' . _('Account Balance') . '</td>
				<td class="number" style="font-weight:bold;">' .locale_number_format($Balance,$BankDetailRow['decimalplaces']) . '</td>
				<td colspan="5"></td>
			</tr>
		</tbody></table>';
		echo '</div></div></div></div>';// div id="Report".
	} //end if no bank trans in the range to show
echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			
			<div class="row">
<div class="col-xs-4">
<div class="form-group">', // Form buttons:
				'<button onclick="javascript:window.print()" class="btn btn-info" type="button">', _('Print'), '</button></div></div>', // "Print" button.
				'<div class="col-xs-4">
<div class="form-group"><button name="SelectADifferentPeriod" class="btn btn-info" type="submit" value="'. _('Select A Different Period') .'">' .
					_('Select Another Date') . '</button></div></div>'.// "Select A Different Period" button.
				'<div class="col-xs-4">
<div class="form-group"><button onclick="window.location=\'menu_data.php?Application=GL\'" class="btn btn-default" type="button"> ', _('Return'), '</button></div></div>', // "Return" button.
			'</div>
		</form></div></div>';
}
include('includes/footer.php');
?>
