<?php
/* This script displays the bank reconciliation for a selected bank account. */

include('includes/session.php');
$Title = _('Bank Reconciliation');;// Screen identificator.
$ViewTopic= 'GeneralLedger';// Filename's id in ManualContents.php's TOC.
$BookMark = 'BankAccounts';// Anchor's id in the manual's html document.
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
	_('Bank Reconciliation') . '</h1></a></div>';// Page title.
echo '<div class="row gutter30">
<div class="col-xs-12">';	
echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($_GET['Account'])) {
	$_POST['BankAccount']=$_GET['Account'];
	$_POST['ShowRec']=true;
}

if (isset($_POST['BankStatementBalance'])){
	$_POST['BankStatementBalance'] = filter_number_format($_POST['BankStatementBalance']);
}

if (isset($_POST['PostExchangeDifference']) AND is_numeric(filter_number_format($_POST['DoExchangeDifference']))){

	if (!is_numeric($_POST['BankStatementBalance'])){
		echo prnMsg(_('The entry in the bank statement balance is not numeric. The balance on the bank statement should be entered. The exchange difference has not been calculated and no general ledger journal has been created'),'warn');
		echo '<br />' . $_POST['BankStatementBalance'];
	} else {

		/* Now need to get the currency of the account and the current table ex rate */
		$SQL = "SELECT rate,
						bankaccountname,
						decimalplaces AS currdecimalplaces
				FROM bankaccounts INNER JOIN currencies
				ON bankaccounts.currcode=currencies.currabrev
				WHERE bankaccounts.accountcode = '" . $_POST['BankAccount']."'";
				

		$ErrMsg = _('Could not retrieve the exchange rate for the selected bank account');
		$CurrencyResult = DB_query($SQL);
		$CurrencyRow =  DB_fetch_array($CurrencyResult);

		$CalculatedBalance = filter_number_format($_POST['DoExchangeDifference']);

		$ExchangeDifference = ($CalculatedBalance - filter_number_format($_POST['BankStatementBalance']))/$CurrencyRow['rate'];
//echo "Difference".$ExchangeDifference;
		include ('includes/SQL_CommonFunctions.inc');
		$ExDiffTransNo = GetNextTransNo(36);
		/*Post the exchange difference to the last day of the month prior to current date*/
		$PostingDate = Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, Date('m'), 0,Date('Y')));
		$PeriodNo = GetPeriod($PostingDate);
		$result = DB_Txn_Begin();

//yet to code the journal

		$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
								  VALUES (36,
									'" . $ExDiffTransNo . "',
									'" . FormatDateForSQL($PostingDate) . "',
									'" . $PeriodNo . "',
									'" . $_SESSION['CompanyRecord']['exchangediffact'] . "',
									'" . $CurrencyRow['bankaccountname'] . ' ' . _('reconciliation on') . " " .
										Date($_SESSION['DefaultDateFormat']) . "','" . $ExchangeDifference . "')";
										

		$ErrMsg = _('Cannot insert a GL entry for the exchange difference because');
		$DbgMsg = _('The SQL that failed to insert the exchange difference GL entry was');
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
								  VALUES (36,
									'" . $ExDiffTransNo . "',
									'" . FormatDateForSQL($PostingDate) . "',
									'" . $PeriodNo . "',
									'" . $_POST['BankAccount'] . "',
									'" . $CurrencyRow['bankaccountname'] . ' ' . _('reconciliation on') . ' ' . Date($_SESSION['DefaultDateFormat']) . "',
									'" . (-$ExchangeDifference) . "')";

		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		$result = DB_Txn_Commit();
		echo prnMsg(_('Exchange difference of') . ' ' . locale_number_format($ExchangeDifference,$_SESSION['CompanyRecord']['decimalplaces']) . ' ' . _('has been posted'),'success');
	} //end if the bank statement balance was numeric
}



$SQL = "SELECT bankaccounts.accountcode,
				bankaccounts.bankaccountname
		FROM bankaccounts, bankaccountusers
		WHERE bankaccounts.accountcode=bankaccountusers.accountcode
			AND bankaccountusers.userid = '" . $_SESSION['UserID'] ."'
		ORDER BY bankaccounts.bankaccountname";

$ErrMsg = _('The bank accounts could not be retrieved by the SQL because');
$DbgMsg = _('The SQL used to retrieve the bank accounts was');
$AccountsResults = DB_query($SQL,$ErrMsg,$DbgMsg);

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Bank Account') . '</label>
		<select tabindex="1" name="BankAccount" class="form-control">';

if (DB_num_rows($AccountsResults)==0){
	echo '</select></div>
			</div>
			
			<p class="text-info">' . _('Bank Accounts have not yet been defined') . '. ' . _('You must first') . '<a href="' . $RootPath . '/BankAccounts.php">' . _('define the bank accounts') . '</a>' . ' ' . _('and general ledger accounts to be affected') . '.</p>';
	include('includes/footer.php');
	exit;
} else {
	
	while ($myrow=DB_fetch_array($AccountsResults)){
		
		
		/*list the bank account names */
		if (isset($_POST['BankAccount']) and $_POST['BankAccount']==$myrow['accountcode']){
			echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . '</option>';
		} else {
			echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . '</option>';
		}
	}
	echo '</select></div>
		</div>';
		
}

/*Now do the posting while the user is thinking about the bank account to select */

include ('includes/GLPostings.inc');

echo '<div class="col-xs-4">
<div class="form-group"><br />
		<input type="submit" class="btn btn-info" tabindex="2" name="ShowRec" value="' . _('Show bank reconciliation statement') . '" />
	</div></div></div>
	<br />';


if (isset($_POST['ShowRec']) OR isset($_POST['DoExchangeDifference'])){

/*Get the balance of the bank account concerned */

	$PeriodNo = GetPeriod(date($_SESSION['DefaultDateFormat']));

	$SQL = "SELECT bfwd+actual AS balance
			FROM chartdetails
			WHERE period='" . $PeriodNo . "'
			AND accountcode='" . $_POST['BankAccount']."'";

	$ErrMsg = _('The bank account balance could not be returned by the SQL because');
	$BalanceResult = DB_query($SQL,$ErrMsg);

	$myrow = DB_fetch_row($BalanceResult);
	$Balance = $myrow[0];

	/* Now need to get the currency of the account and the current table ex rate */
	$SQL = "SELECT rate,
					bankaccounts.currcode,
					bankaccounts.bankaccountname,
					currencies.decimalplaces AS currdecimalplaces
			FROM bankaccounts INNER JOIN currencies
			ON bankaccounts.currcode=currencies.currabrev
			WHERE bankaccounts.accountcode = '" . $_POST['BankAccount']."'";
	$ErrMsg = _('Could not retrieve the currency and exchange rate for the selected bank account');
	$CurrencyResult = DB_query($SQL);
	$CurrencyRow =  DB_fetch_array($CurrencyResult);


	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<tr class="striped_row">
				<td colspan="5"><strong>' . $CurrencyRow['bankaccountname'] . ' ' . _('Balance as on') . ' ' . Date($_SESSION['DefaultDateFormat']);

	if ($_SESSION['CompanyRecord']['currencydefault']!=$CurrencyRow['currcode']){
		echo  ' (' . $CurrencyRow['currcode'] . ' @ ' . $CurrencyRow['rate'] .')';
	}
	echo '</strong></td>
			<td valign="bottom" class="number"><strong>' . locale_number_format($Balance*$CurrencyRow['rate'],$CurrencyRow['currdecimalplaces']) . '</strong></td></tr>';

	$SQL = "SELECT amount/exrate AS amt,
					amountcleared,
					(amount/exrate)-amountcleared as outstanding,
					ref,
					transdate,
					systypes.typename,
					transno
				FROM banktrans,
					systypes
				WHERE banktrans.type = systypes.typeid
				AND banktrans.bankact='" . $_POST['BankAccount'] . "'
				AND amount < 0
				AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	echo '<tr><td><br /></td></tr>'; /*Bang in a blank line */

	$ErrMsg = _('The unpresented cheques could not be retrieved by the SQL because');
	$UPChequesResult = DB_query($SQL, $ErrMsg);

	echo '<tr>
			<td colspan="6"><strong>' . _('Unpresented cheques') . ':</strong></td>
		</tr>';

	$TableHeader = '<tr>
						<th>' . _('Date') . '</th>
						<th>' . _('Type') . '</th>
						<th>' . _('Number') . '</th>
						<th>' . _('Reference') . '</th>
						<th>' . _('Original Amount') . '</th>
						<th>' . _('Outstanding') . '</th>
					</tr>';

	echo $TableHeader;

	$j = 1;
	$TotalUnpresentedCheques =0;

	while ($myrow=DB_fetch_array($UPChequesResult)) {
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				</tr>',
				ConvertSQLDate($myrow['transdate']),
				$myrow['typename'],
				$myrow['transno'],
				$myrow['ref'],
				locale_number_format($myrow['amt'],$CurrencyRow['currdecimalplaces']),
				locale_number_format($myrow['outstanding'],$CurrencyRow['currdecimalplaces']));

		$TotalUnpresentedCheques +=$myrow['outstanding'];

		$j++;
		If ($j == 18){
			$j=1;
			echo $TableHeader;
		}
	}
	//end of while loop

	echo '<tr>
             <td><br /></td>
          </tr>
			<tr class="striped_row">
				<td colspan="5">' . _('Total of all unpresented cheques') . '</td>
				<td class="number">' . locale_number_format($TotalUnpresentedCheques,$CurrencyRow['currdecimalplaces']) . '</td>
			</tr>';

	$SQL = "SELECT amount/exrate AS amt,
				amountcleared,
				(amount/exrate)-amountcleared AS outstanding,
				ref,
				transdate,
				systypes.typename,
				transno
			FROM banktrans INNER JOIN systypes
			ON banktrans.type = systypes.typeid
			WHERE banktrans.bankact='" . $_POST['BankAccount'] . "'
			AND amount > 0
			AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	echo '<tr><td><br /></td></tr>'; /*Bang in a blank line */

	$ErrMsg = _('The uncleared deposits could not be retrieved by the SQL because');

	$UPChequesResult = DB_query($SQL,$ErrMsg);

	echo '<tr><td colspan="6"><strong>' . _('Uncleared cheques ') . ':</strong></td></tr>';

	$TableHeader = '<tr>
						<th>' . _('Date') . '</th>
						<th>' . _('Type') . '</th>
						<th>' . _('Number') . '</th>
						<th>' . _('Reference') . '</th>
						<th>' . _('Original Amount') . '</th>
						<th>' . _('Outstanding') . '</th>
					</tr>';

	echo  $TableHeader;

	$j = 1;
	$TotalUnclearedDeposits =0;

	while ($myrow=DB_fetch_array($UPChequesResult)) {
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				</tr>',
				ConvertSQLDate($myrow['transdate']),
				$myrow['typename'],
				$myrow['transno'],
				$myrow['ref'],
				locale_number_format($myrow['amt'],$CurrencyRow['currdecimalplaces']),
				locale_number_format($myrow['outstanding'],$CurrencyRow['currdecimalplaces']) );

		$TotalUnclearedDeposits +=$myrow['outstanding'];

		$j++;
		if ($j == 18){
			$j=1;
			echo $TableHeader;
		}
	}
	//end of while loop
	echo '<tr>
            <td><br /></td>
		</tr>
		<tr class="striped_row">
			<td colspan="5">' . _('Total of all uncleared cheques') . '</td>
			<td class="number">' . locale_number_format($TotalUnclearedDeposits,$CurrencyRow['currdecimalplaces']) . '</td>
		</tr>';
	$FXStatementBalance = ($Balance*$CurrencyRow['rate'] - $TotalUnpresentedCheques -$TotalUnclearedDeposits);
	echo '<tr>
            <td><br /></td>
		</tr>
		<tr class="striped_row">
			<td colspan="5"><strong>' . _('Bank statement balance should be') . ' (' . $CurrencyRow['currcode'] . ')</strong></td>
			<td class="number">' . locale_number_format($FXStatementBalance,$CurrencyRow['currdecimalplaces']) . '</td>
		</tr>';

	if (isset($_POST['DoExchangeDifference'])){
		echo '<input type="hidden" name="DoExchangeDifference" value="' . $FXStatementBalance . '" />';
		if (!isset($_POST['BankStatementBalance'])){
			$_POST['BankStatementBalance'] =0;
		}
		echo '<tr>
				<td colspan="5"><strong>' . _('Enter the actual bank statement balance') . ' (' . $CurrencyRow['currcode'] . ')</strong></td>
				<td class="number"><input type="text" name="BankStatementBalance" class="form-control" autofocus="autofocus" required="required" maxlength="15" size="15" value="' . locale_number_format($_POST['BankStatementBalance'],$CurrencyRow['currdecimalplaces']) . '" /></td>
			</tr>
			<tr>
				<td colspan="6" align="center"><input type="submit" class="btn btn-info" name="PostExchangeDifference" value="' . _('Calculate and Post Exchange Difference') . '" onclick="return confirm(\'' . _('This will create a general ledger journal to write off the exchange difference in the current balance of the account. It is important that the exchange rate above reflects the current value of the bank account currency') . ' - ' . _('Are You Sure?') . '\');" /></td>
			</tr>';
	}

	if ($_SESSION['CompanyRecord']['currencydefault']!=$CurrencyRow['currcode'] AND !isset($_POST['DoExchangeDifference'])){

		echo '<tr>
				<td colspan="7"><hr /></td>
			</tr>
			<tr>
				<td colspan="7">' . _('It is normal for foreign currency accounts to have exchange differences that need to be reflected as the exchange rate varies. This reconciliation is prepared using the exchange rate set up in nERP. If you wish to create a journal to reflect the exchange difference based on the current exchange rate to correct the reconciliation to the actual bank statement balance click below.') . '</td>
			</tr>
			<tr>
				<td colspan="7" align="center"><input type="submit" class="btn btn-info" name="DoExchangeDifference" value="' . _('Calculate and Post Exchange Difference') . '" /></td>
			</tr>';
	}
	echo '</table></div></div></div>';
}


//if (isset($_POST['BankAccount'])) {
//	echo '<div class="row">
//			<div class="col-xs-4">
//			<a tabindex="4" href="' . $RootPath . '/BankMatching.php?Type=Payments&amp;Account='.$_POST['BankAccount'].'" class="btn btn-info">' . _('Match off cleared payments') . '</a></div>
//			</p>
//			<div class="col-xs-4">
//			<a tabindex="5" href="' . $RootPath . '/BankMatching.php?Type=Receipts&amp;Account='.$_POST['BankAccount'].'" class="btn btn-info">' . _('Match off cleared deposits') . '</a></div>
//		</div><br />
//';
//} else {
//	echo '<div class="row">
//			<div class="col-xs-4">
//			<a tabindex="4" href="' . $RootPath . '/BankMatching.php?Type=Payments" class="btn btn-info">' . _('Match off cleared payments') . '</a>
//			</div>
//			<div class="col-xs-4">
//			<a tabindex="5" href="' . $RootPath . '/BankMatching.php?Type=Receipts" class="btn btn-info">' . _('Match off cleared deposits') . '</a>
//		</div></div><br />
//';
//}

echo '</form>';
echo '</div></div>';
include('includes/footer.php');
?>
