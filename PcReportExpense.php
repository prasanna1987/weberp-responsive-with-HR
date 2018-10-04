<?php

include ('includes/session.php');
$Title = _('Petty Cash Expense Management Report');
/* nERP manual links before header.php */
$ViewTopic = 'PettyCash';
$BookMark = 'PcReportExpense';

include ('includes/SQL_CommonFunctions.inc');
include  ('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">';

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
	
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($_POST['SelectedExpense'])){
	$SelectedExpense = mb_strtoupper($_POST['SelectedExpense']);
} elseif (isset($_GET['SelectedExpense'])){
	$SelectedExpense = mb_strtoupper($_GET['SelectedExpense']);
}

if ((! isset($_POST['FromDate']) AND ! isset($_POST['ToDate'])) OR isset($_POST['SelectDifferentDate'])) {



	if (!isset($_POST['FromDate'])){
		$_POST['FromDate']=Date($_SESSION['DefaultDateFormat'], mktime(0,0,0,Date('m'),1,Date('Y')));
	}

	if (!isset($_POST['ToDate'])){
		$_POST['ToDate'] = Date($_SESSION['DefaultDateFormat']);
	}

	/*Show a form to allow input of criteria for Expenses to show */
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Expense Code') . '</label>
			<select name="SelectedExpense" class="form-control">';

	$SQL = "SELECT DISTINCT(pctabexpenses.codeexpense)
			FROM pctabs, pctabexpenses
			WHERE pctabexpenses.typetabcode = pctabs.typetabcode
				AND ( pctabs.authorizer='" . $_SESSION['UserID'] .
					"' OR pctabs.usercode ='" . $_SESSION['UserID'].
					"' OR pctabs.assigner ='" . $_SESSION['UserID'] . "' )
			ORDER BY pctabexpenses.codeexpense";

	$Result = DB_query($SQL);

	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['SelectedExpense']) and $MyRow['codeexpense']==$_POST['SelectedExpense']) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $MyRow['codeexpense'] . '">' . $MyRow['codeexpense'] . '</option>';

	} //end while loop get type of tab

	DB_free_result($Result);


	echo '</select></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('From Date') . '' . '</label>
			<input tabindex="2" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" type="text" name="FromDate" maxlength="10" size="11" value="' . $_POST['FromDate'] . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('To Date') . '' . '</label>
			<input tabindex="3" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" type="text" name="ToDate" maxlength="10" size="11" value="' . $_POST['ToDate'] . '" /></div>
		</div>
		</div>
		
		<div class="row" align="center">
		<div>
<div class="form-group">
			<input type="submit" name="ShowTB" class="btn btn-success" value="' . _('Show') .'" />
		</div>
		</div>
		</div>
	</form>
	</div>
	</div>
	';

} else {

	$SQL_FromDate = FormatDateForSQL($_POST['FromDate']);
	$SQL_ToDate = FormatDateForSQL($_POST['ToDate']);

	echo '<input type="hidden" name="FromDate" value="' . $_POST['FromDate'] . '" />
			<input type="hidden" name="ToDate" value="' . $_POST['ToDate'] . '" />';
		echo '<br /><div class="row"><div class="col-xs-4"><input type="submit" class="btn btn-info" name="SelectDifferentDate" value="' . _('Back to Search') . '" /></div>';
		echo '<br /><div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

	echo '<tr>
			<td>' . _('Expense Code') . ':</td>
			<td>' . $SelectedExpense . '</td>
			</tr>
		<tr>
			<td>' . _('From') . ':</td>
			<td>' . $_POST['FromDate'] . '</td>
		</tr>
		<tr>
			<td>' . _('To') . ':</td>
			<td>' . $_POST['ToDate'] . '</td>
		</tr>';

	echo '</table></div></div></div>';

	$SQL = "SELECT pcashdetails.counterindex,
					pcashdetails.tabcode,
					pcashdetails.tag,
					pcashdetails.date,
					pcashdetails.codeexpense,
					pcashdetails.amount,
					pcashdetails.authorized,
					pcashdetails.posted,
					pcashdetails.purpose,
					pcashdetails.notes,
					pctabs.currency,
					currencies.decimalplaces
			FROM pcashdetails, pctabs, currencies
			WHERE pcashdetails.tabcode = pctabs.tabcode
				AND pctabs.currency = currencies.currabrev
				AND pcashdetails.codeexpense='".$SelectedExpense."'
				AND pcashdetails.date >='" . $SQL_FromDate . "'
				AND pcashdetails.date <= '" . $SQL_ToDate . "'
				AND (pctabs.authorizer='" . $_SESSION['UserID'] .
					"' OR pctabs.usercode ='" . $_SESSION['UserID'].
					"' OR pctabs.assigner ='" . $_SESSION['UserID'] . "')
			ORDER BY pcashdetails.date, pcashdetails.counterindex ASC";

	$Result = DB_query($SQL,
						_('No Petty Cash movements for this expense code were returned by the SQL because'),
						_('The SQL that failed was:'));

	echo '<br />
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
				<tr>
					<th class="ascending">' . _('Date') . '</th>
					<th class="ascending">' . _('Tab') . '</th>
					<th>' . _('Currency') . '</th>
					<th class="ascending">' . _('Gross Amount') . '</th>
					<th>', _('Tax'), '</th>
					<th>', _('Tax Group'), '</th>
					<th>', _('Tag'), '</th>
					<th>' . _('Purpose') . '</th>
					<th>' . _('Notes') . '</th>
					<th>' . _('Receipt') . '</th>
					<th class="ascending">' . _('Date Authorised') . '</th>
				</tr>
			</thead>
			<tbody>';

	while ($MyRow = DB_fetch_array($Result)) {
		$CurrDecimalPlaces = $MyRow['decimalplaces'];
		$TaxesDescription = '';
		$TaxesTaxAmount = '';
		$TaxSQL = "SELECT counterindex,
							pccashdetail,
							calculationorder,
							description,
							taxauthid,
							purchtaxglaccount,
							taxontax,
							taxrate,
							amount
						FROM pcashdetailtaxes
						WHERE pccashdetail='" . $MyRow['counterindex'] . "'";
		$TaxResult = DB_query($TaxSQL);
		while ($MyTaxRow = DB_fetch_array($TaxResult)) {
			$TaxesDescription .= $MyTaxRow['description'] . '<br />';
			$TaxesTaxAmount .= locale_number_format($MyTaxRow['amount'], $CurrDecimalPlaces) . '<br />';
		}
		$TagSQL = "SELECT tagdescription FROM tags WHERE tagref='" . $MyRow['tag'] . "'";
		$TagResult = DB_query($TagSQL);
		$TagRow = DB_fetch_array($TagResult);
		if ($MyRow['tag'] == 0) {
			$TagRow['tagdescription'] = _('None');
		}
		$TagTo = $MyRow['tag'];
		$TagDescription = $TagTo . ' - ' . $TagRow['tagdescription'];

		//Generate download link for expense receipt, or show text if no receipt file is found.
		$ReceiptSupportedExt = array('png','jpg','jpeg','pdf','doc','docx','xls','xlsx'); //Supported file extensions
		$ReceiptDir = $PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/expenses_receipts/'; //Receipts upload directory
		$ReceiptSQL = "SELECT hashfile,
								extension
								FROM pcreceipts
								WHERE pccashdetail='" . $MyRow['counterindex'] . "'";
		$ReceiptResult = DB_query($ReceiptSQL);
		$ReceiptRow = DB_fetch_array($ReceiptResult);
		if (DB_num_rows($ReceiptResult) > 0) { //If receipt exists in database
			$ReceiptHash = $ReceiptRow['hashfile'];
			$ReceiptExt = $ReceiptRow['extension'];
			$ReceiptFileName = $ReceiptHash . '.' . $ReceiptExt;
			$ReceiptPath = $ReceiptDir . $ReceiptFileName;
			$ReceiptText = '<a href="' . $ReceiptPath . '" download="ExpenseReceipt-' . mb_strtolower($SelectedTabs) . '-[' . $MyRow['date'] . ']-[' . $MyRow['counterindex'] . ']" class="btn btn-info">' . _('Download') . '</a>';
		} else {
			$ReceiptText = _('No attachment');
		}

		if ($MyRow['authorized'] == '0000-00-00') {
			$AuthorisedDate = _('Unauthorised');
		} else {
			$AuthorisedDate = ConvertSQLDate($MyRow['authorized']);
		}

		echo '<tr class="striped_row">
			<td>', ConvertSQLDate($MyRow['date']), '</td>
			<td>', $MyRow['tabcode'], '</td>
			<td>', $MyRow['currency'], '</td>
			<td class="number">', locale_number_format($MyRow['amount'], $CurrDecimalPlaces), '</td>
			<td class="number">', $TaxesTaxAmount, '</td>
			<td>', $TaxesDescription, '</td>
			<td>', $TagDescription, '</td>
			<td>', $MyRow['purpose'], '</td>
			<td>', $MyRow['notes'], '</td>
			<td>', $ReceiptText, '</td>
			<td>', $AuthorisedDate, '</td>
		</tr>';
	} //end of looping

	echo '</tbody>';
	echo '</table></div></div></div>';

    echo '</div>
	
          </form></div></div><br />
';
}
include('includes/footer.php');

?>