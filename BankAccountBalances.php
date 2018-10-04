<?php
/* Shows bank accounts authorised for with balances */

include('includes/session.php');
$Title = _('List of bank account balances');
$ViewTopic = 'GeneralLedger';
$BookMark = 'BankAccountBalances';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
	_('Bank Account Balances'), '</h1></a></div>',// Page title.
	'<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<tr>
			<th>', _('Bank Account'), '</th>
			<th>', _('Account Name'), '</th>
			<th>', _('Balance in transaction currency'), '</th>
			<th>', _('Balance in system currency'), '</th>
		</tr>';

$SQL = "SELECT DISTINCT bankaccounts.accountcode,
						currcode,
						bankaccountname
			FROM bankaccounts
			INNER JOIN bankaccountusers
			ON bankaccounts.accountcode=bankaccountusers.accountcode
			AND userid='" . $_SESSION['UserID'] . "'";
$Result = DB_query($SQL);

if (DB_num_rows($Result) == 0) {
	echo _('There are no bank accounts defined that you have authority to see');
} else {
	while ($MyBankRow = DB_fetch_array($Result)) {
		$CurrBalanceSQL = "SELECT SUM(amount) AS balance FROM banktrans WHERE bankact='" . $MyBankRow['accountcode'] . "'";
		$CurrBalanceResult = DB_query($CurrBalanceSQL);
		$CurrBalanceRow = DB_fetch_array($CurrBalanceResult);

		$FuncBalanceSQL = "SELECT SUM(amount) AS balance FROM gltrans WHERE account='" . $MyBankRow['accountcode'] . "'";
		$FuncBalanceResult = DB_query($FuncBalanceSQL);
		$FuncBalanceRow = DB_fetch_array($FuncBalanceResult);

		$DecimalPlacesSQL = "SELECT decimalplaces FROM currencies WHERE currabrev='" . $MyBankRow['currcode'] . "'";
		$DecimalPlacesResult = DB_query($DecimalPlacesSQL);
		$DecimalPlacesRow = DB_fetch_array($DecimalPlacesResult);

		echo '<tr>
				<td>', $MyBankRow['accountcode'], '</td>
				<td>', $MyBankRow['bankaccountname'], '</td>
				<td>', locale_number_format($CurrBalanceRow['balance'], $DecimalPlacesRow['decimalplaces']), ' ', $MyBankRow['currcode'], '</td>
				<td>', locale_number_format($FuncBalanceRow['balance'], $_SESSION['CompanyRecord']['decimalplaces']), ' ', $_SESSION['CompanyRecord']['currencydefault'], '</td>
			</tr>';
	}

	echo '</table></div></div></div>';
}
include('includes/footer.php');
?>