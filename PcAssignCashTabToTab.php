<?php

include('includes/session.php');
$Title = _('Assignment of Cash from Tab to Tab');
/* nERP manual links before header.php */
$ViewTopic= 'PettyCash';
$BookMark = 'CashAssignment';
include('includes/header.php');

if (isset($_POST['SelectedTabs'])){
	$SelectedTabs = mb_strtoupper($_POST['SelectedTabs']);
} elseif (isset($_GET['SelectedTabs'])){
	$SelectedTabs = mb_strtoupper($_GET['SelectedTabs']);
}

if (isset($_POST['SelectedTabsTo'])){
	$SelectedTabsTo = mb_strtoupper($_POST['SelectedTabsTo']);
}

if (isset($_POST['Days'])){
	$Days = $_POST['Days'];
} elseif (isset($_GET['Days'])){
	$Days = $_GET['Days'];
}

if (isset($_POST['Cancel'])) {
	unset($SelectedTabs);
	unset($Days);
	unset($_POST['Amount']);
	unset($_POST['Notes']);
}

if (isset($_POST['Process'])) {
	if ($SelectedTabs == '') {
		echo prnMsg(_('You must first select a petty cash tab to assign cash'),'error');
		unset($SelectedTabs);
	}
	if ($SelectedTabs == $SelectedTabsTo) {
		echo prnMsg(_('The tab selected FROM should not be the same as the selected TO'),'error');
		unset($SelectedTabs);
		unset($SelectedTabsTo);
		unset($_POST['Process']);
	}
	//to ensure currency is the same
	$CurrSQL = "SELECT currency
				FROM pctabs
				WHERE tabcode IN ('" . $SelectedTabs . "','" . $SelectedTabsTo . "')";
	$CurrResult = DB_query($CurrSQL);
	if (DB_num_rows($CurrResult) > 0) {
		$Currency = '';
		while ($CurrRow = DB_fetch_array($CurrResult)) {
			if ($Currency === '') {
				$Currency = $CurrRow['currency'];
			} elseif ($Currency != $CurrRow['currency']) {
				echo prnMsg (_('The currency of the tab transferred from should be the same as the tab being transferred to'),'error');
				unset($SelectedTabs);
				unset($SelectedTabsTo);
				unset($_POST['Process']);
			}
		}
	}

}

if (isset($_POST['Go'])) {
	$InputError = 0;
	if ($Days <= 0) {
		$InputError = 1;
		echo prnMsg(_('The number of days must be a positive number'),'error');
		$Days = 30;
	}
}

if (isset($_POST['submit'])) {
	//initialise no input errors assumed initially before we test
	$InputError = 0;

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	$i = 1;

	if ($_POST['Amount'] == 0) {
		$InputError = 1;
		echo prnMsg('<br />' . _('The Amount must be input'),'error');
	}

	$SQLLimit = "SELECT tablimit,tabcode
				FROM pctabs
				WHERE tabcode IN ('" . $SelectedTabs . "','" . $SelectedTabsTo . "')";

	$ResultLimit = DB_query($SQLLimit);
	while ($LimitRow = DB_fetch_array($ResultLimit)){
		if ($LimitRow['tabcode'] == $SelectedTabs) {
			if (($_POST['CurrentAmount'] + $_POST['Amount']) > $LimitRow['tablimit']){
				$InputError = 1;
				echo prnMsg(_('The balance after this assignment would be greater than the specified limit for this PC tab') . ' ' . $LimitRow[1],'error');
			}
		}  elseif ($_POST['SelectedTabsToAmt'] - $_POST['Amount'] > $LimitRow['tablimit']) {
				$InputError = 1;
				echo prnMsg(_('The balance after this assignment would be greater than the specified limit for this PC tab') . ' ' . $LimitRow[1],'error');
		}
	}

	if ($InputError != 1) {
		// Add these 2 new records on submit
		$SQL = "INSERT INTO pcashdetails
					(counterindex,
					tabcode,
					date,
					codeexpense,
					amount,
					authorized,
					posted,
					purpose,
					notes)
			VALUES (NULL,
					'" . $_POST['SelectedTabs'] . "',
					'" . FormatDateForSQL($_POST['Date']) . "',
					'ASSIGNCASH',
					'" . filter_number_format($_POST['Amount']) . "',
					'0000-00-00',
					'0',
					NULL,
					'" . $_POST['Notes'] . "'
					),
					(NULL,
					'" . $SelectedTabsTo . "',
					'" . FormatDateForSQL($_POST['Date']) . "',
					'ASSIGNCASH',
					'" . filter_number_format(-$_POST['Amount']) . "',
					'0000-00-00',
					'0',
					NULL,
					'" . $_POST['Notes'] . "'
					)";
		$msg = _('Assignment of cash from PC Tab ') . ' ' . $SelectedTabs .  ' ' . _('to ') . $SelectedTabsTo . ' ' . _('has been created');
	}

	if ( $InputError != 1) {
		//run the SQL from either of the above possibilites
		$Result = DB_query($SQL);
		echo prnMsg($msg,'success');
		unset($_POST['SelectedExpense']);
		unset($_POST['Amount']);
		unset($_POST['Notes']);
		unset($_POST['SelectedTabs']);
		unset($_POST['Date']);
	}

}

if (!isset($SelectedTabs)){

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
    
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	$SQL = "SELECT tabcode
			FROM pctabs
			WHERE assigner = '" . $_SESSION['UserID'] . "'
			ORDER BY tabcode";

	$Result = DB_query($SQL);

    echo '<div class="row">
<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Petty cash Tab to assign cash from') . '</label>
            <select name="SelectedTabs" class="form-control">';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['SelectTabs']) AND $MyRow['tabcode'] == $_POST['SelectTabs']) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $MyRow['tabcode'] . '">' . $MyRow['tabcode'] . '</option>';
	}

	echo '</select></div></div>';
  echo '<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Petty cash Tab to assign cash to') . '</label>
	  <select name="SelectedTabsTo" class="form-control">';
	DB_data_seek($Result,0);
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['SelectTabsTo']) AND $MyRow['tabcode'] == $_POST['SelectTabs']) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $MyRow['tabcode'] . '">' . $MyRow['tabcode'] . '</option>';
	}
	echo '</select></div></div>';
   
    DB_free_result($Result);

	echo '<div class="col-xs-3">
<div class="form-group"><br />
			<input type="submit" class="btn btn-success" name="Process" value="' . _('Submit') . '" /></div></div>
			';
	echo '</div>
          </form>';
}

//end of ifs and buts!
if (isset($_POST['Process']) OR isset($SelectedTabs)) {

	if (!isset($_POST['submit'])) {
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
	}
	echo '<div class="row"><div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Back to Tabs') . '</a></div></div><br />';

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
';
	echo '	<tr>
				<td>' . _('Petty cash tab to assign cash from') . ':</td>
				<td>' . $SelectedTabs . '</td>
			</tr>
			<tr>
				<td>' . _('Petty cash tab to assign cash to') . ':</td>
				<td>' . $SelectedTabsTo . '</td>
			</tr>';
	echo '</table></div></div></div>';

	if (! isset($_GET['edit']) OR isset ($_POST['GO'])){

		if (isset($_POST['Cancel'])) {
			unset($_POST['Amount']);
			unset($_POST['Date']);
			unset($_POST['Notes']);
		}

		if(!isset ($Days)){
			$Days = 30;
		 }

		/* Retrieve decimal places to display */
		$SQLDecimalPlaces = "SELECT decimalplaces
					FROM currencies,pctabs
					WHERE currencies.currabrev = pctabs.currency
						AND tabcode = '" . $SelectedTabs . "'";
		$Result = DB_query($SQLDecimalPlaces);
		$MyRow = DB_fetch_array($Result);
		$CurrDecimalPlaces = $MyRow['decimalplaces'];

		$SQL = "SELECT counterindex,
						tabcode,
						tag,
						date,
						codeexpense,
						amount,
						authorized,
						posted,
						purpose,
						notes
				FROM pcashdetails
				WHERE tabcode='" . $SelectedTabs . "'
				AND date >= DATE_SUB(CURDATE(), INTERVAL " . $Days . " DAY)
				ORDER BY date, counterindex ASC";
		$Result = DB_query($SQL);

		echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
			
				<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

		//Limit expenses history to X days
		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Detail of Tab Movements For Last') .'</label>
						<input type="hidden" name="SelectedTabs" value="' . $SelectedTabs . '" />
						<input type="text" class="form-control" name="Days" value="' . $Days  . '" maxlength="3" size="4" /> ' . _('Days') . '</div></div><div class="col-xs-4">
<div class="form-group"> <br />
						<input type="submit" class="btn btn-info" name="Go" value="' . _('Go') . '" />
					</div>
				</div>
			</div>';

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
				<thead>
					<tr>
						<th class="ascending">' . _('Date') . '</th>
						<th class="ascending">' . _('Expense Code') . '</th>
						<th class="ascending">' . _('Amount') . '</th>
						<th>' . _('Business Purpose') . '</th>
						<th>' . _('Notes') . '</th>
						<th>' . _('Receipt Attachment') . '</th>
						<th class="ascending">' . _('Date Authorised') . '</th>
					</tr>
				</thead>
				<tbody>';

	while ($MyRow = DB_fetch_array($Result)) {

		$SQLDes="SELECT description
					FROM pcexpenses
					WHERE codeexpense='" . $MyRow['codeexpense'] . "'";

		$ResultDes = DB_query($SQLDes);
		$Description = DB_fetch_array($ResultDes);
		if (!isset($Description[0])) {
				$ExpenseCodeDes = 'ASSIGNCASH';
		} else {
				$ExpenseCodeDes = $MyRow['codeexpense'] . ' - ' . $Description[0];
		}

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
				$ReceiptText = '<a href="' . $ReceiptPath . '" download="ExpenseReceipt-' . mb_strtolower($SelectedTabs) . '-[' . $MyRow['date'] . ']-[' . $MyRow['counterindex'] . ']" class="btn btn-info">' . _('Download attachment') . '</a>';
			} elseif ($ExpenseCodeDes == 'ASSIGNCASH') {
				$ReceiptText = '';
			} else {
				$ReceiptText = _('No attachment');
			}

		if ($MyRow['authorized'] == '0000-00-00') {
				$AuthorisedDate = _('Unauthorised');
		} else {
			$AuthorisedDate = ConvertSQLDate($MyRow['authorized']);
		}

		if (($MyRow['authorized'] == '0000-00-00') AND ($Description['0'] == 'ASSIGNCASH')){
			// only cash assignations NOT authorized can be modified or deleted
			echo '<tr class="striped_row">
				<td>' . ConvertSQLDate($MyRow['date']) . '</td>
				<td>', $ExpenseCodeDes, '</td>
				<td class="number">' . locale_number_format($MyRow['amount'],$CurrDecimalPlaces) . '</td>
				<td>' . $MyRow['purpose'] . '</td>
				<td>' . $MyRow['notes'] . '</td>
				<td>' . $ReceiptText . '</td>
				<td>' . $AuthorisedDate . '</td>
				</tr>';
		}else{
			echo '<tr class="striped_row">
				<td>' . ConvertSQLDate($MyRow['date']) . '</td>
				<td>', $ExpenseCodeDes, '</td>
				<td class="number">' . locale_number_format($MyRow['amount'],$CurrDecimalPlaces) . '</td>
				<td>' . $MyRow['purpose'] . '</td>
				<td>' . $MyRow['notes'] . '</td>
				<td>' . $ReceiptText . '</td>
				<td>' . $AuthorisedDate . '</td>
				</tr>';
		}
	}
		//END WHILE LIST LOOP

		$SQLAmount="SELECT sum(amount) as amt,
					tabcode
					FROM pcashdetails
					WHERE tabcode IN ('" . $SelectedTabs . "','" . $SelectedTabsTo . "')
					GROUP BY tabcode";

		$ResultAmount = DB_query($SQLAmount);
		if (DB_num_rows($ResultAmount) > 0) {
			while ($AmountRow = DB_fetch_array($ResultAmount)) {
				if (is_null($AmountRow['amt'])) {
					$AmountRow['amt'] = 0;
				}
				if ($AmountRow['tabcode'] == $SelectedTabs) {
					$SelectedTab = array($AmountRow['amt'],$SelectedTabs);
				} else {
					$SelectedTabsTo = array($AmountRow['amt'],$SelectedTabsTo);
				}
			}
		}
		if (!isset($SelectedTab)) {
			$SelectedTab = array(0,$SelectedTabs);
			$SelectedTabsTo = array(0,$SelectedTabsTo);
		}



		echo '</tbody>
			
				<tr>
					<td colspan="2" class="number"><b>' . _('<strong>Current balance</strong>') . ':</b></td>
					<td>' . locale_number_format($SelectedTab['0'],$CurrDecimalPlaces) . '</td>
				</tr>
			
			<input type="hidden" name="CurrentAmount" value="' . $SelectedTab[0] . '" />
			<input type="hidden" name="SelectedTabs" value="' . $SelectedTab[1] . '" />
			<input type="hidden" name="SelectedTabsTo" value="' . $SelectedTabsTo[1] . '" />
			<input type="hidden" name="SelectedTabsToAmt" value="' . $SelectedTabsTo[0] . '" />';

		echo '</table>';
        echo '</div></div></div>
              </form>';
	}



		echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .'">
			
				<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/* Ricard: needs revision of this date initialization */
		if (!isset($_POST['Date'])) {
			$_POST['Date'] = Date($_SESSION['DefaultDateFormat']);
		}

        echo '<br />
				<div class="block">'; //Main table
            echo '
					<div class="block-title"><h3>' . _('New Cash Assignment') . '</h3></div>
				';
		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Cash Assignment Date') . '</label>
				<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="Date" required="required" autofocus="autofocus" size="11" maxlength="10" value="' . $_POST['Date'] . '" /></div>
			</div>';


		if (!isset($_POST['Amount'])) {
			$_POST['Amount'] = 0;
		}

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Amount') . '</label>
				<input type="text" class="form-control" name="Amount" size="12" maxlength="11" value="' . locale_number_format($_POST['Amount'],$CurrDecimalPlaces) . '" /></div>
			</div>';

		if (!isset($_POST['Notes'])) {
			$_POST['Notes'] = '';
		}

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Notes') . '</label>
				<input type="text" class="form-control" name="Notes" size="50" maxlength="49" value="' . $_POST['Notes'] . '" /></div>
			</div>';

		echo '</div>'; // close main table
		echo '<input type="hidden" name="CurrentAmount" value="' . $SelectedTab['0']. '" />
			<input type="hidden" name="SelectedTabs" value="' . $SelectedTabs . '" />
			<input type="hidden" name="Days" value="' . $Days . '" />
			<input type="hidden" name="SelectedTabsTo" value="' . $SelectedTabsTo[1] . '" />
			<input type="hidden" name="SelectedTabsToAmt" value="' . $SelectedTabsTo[0] . '" />
			
			<div class="row" align="center">
			<div>
				<input type="submit" class="btn btn-success" name="submit" value="' . _('Submit') . '" />
				</div>
				<div class="col-xs-4">
				</div>
			</div><br />
			</div>

		</form>';

}

include('includes/footer.php');
?>