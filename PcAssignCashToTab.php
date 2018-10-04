<?php

include('includes/session.php');
$Title = _('Assignment of Cash to Petty Cash Tab');
/* nERP manual links before header.php */
$ViewTopic = 'PettyCash';
$BookMark = 'CashAssignment';
include('includes/header.php');
if (isset($_POST['SelectedTabs'])) {
	$SelectedTabs = mb_strtoupper($_POST['SelectedTabs']);
} elseif (isset($_GET['SelectedTabs'])) {
	$SelectedTabs = mb_strtoupper($_GET['SelectedTabs']);
}
if (isset($_POST['SelectedIndex'])) {
	$SelectedIndex = $_POST['SelectedIndex'];
} elseif (isset($_GET['SelectedIndex'])) {
	$SelectedIndex = $_GET['SelectedIndex'];
}
if (isset($_POST['Days'])) {
	$Days = $_POST['Days'];
} elseif (isset($_GET['Days'])) {
	$Days = $_GET['Days'];
}
if (isset($_POST['Cancel'])) {
	unset($SelectedTabs);
	unset($SelectedIndex);
	unset($Days);
	unset($_POST['Amount']);
	unset($_POST['Notes']);
}
if (isset($_POST['Process'])) {
	if ($SelectedTabs == '') {
		echo prnMsg(_('You must first select a petty cash tab to assign cash'), 'error');
		unset($SelectedTabs);
	}
}
if (isset($_POST['Go'])) {
	$InputError = 0;
	if ($Days <= 0) {
		$InputError = 1;
		echo prnMsg(_('The number of days must be a positive number'), 'error');
		$Days = 30;
	}
}
if (isset($_POST['submit'])) {
	//initialise no input errors assumed initially before we test
	$InputError = 0;
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>', ' ', $Title, '
		</h1></a></div>';
	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */
	if ($_POST['Amount'] == 0) {
		$InputError = 1;
		echo prnMsg('<br />' . _('The Amount must be input'), 'error');
	}
	$SQLLimit = "SELECT pctabs.tablimit,
						pctabs.currency,
						currencies.decimalplaces
					FROM pctabs,
						currencies
					WHERE pctabs.currency = currencies.currabrev
						AND pctabs.tabcode='" . $SelectedTabs . "'";
	$ResultLimit = DB_query($SQLLimit);
	$Limit = DB_fetch_array($ResultLimit);
	if (($_POST['CurrentAmount']) > $Limit['tablimit']){
		$InputError = 1;
		echo prnMsg(_('Cash NOT assigned because PC tab current balance is over its cash limit of') . ' ' . locale_number_format($Limit['tablimit'], $Limit['decimalplaces']) . ' ' . $Limit['currency'], 'error');
		echo prnMsg(_('Add expenses before being allowed to assign more cash or ask the administrator to increase the limit'), 'error');
	}
	if ($InputError !=1 and (($_POST['CurrentAmount'] + $_POST['Amount']) > $Limit['tablimit'])) {
		echo prnMsg(_('Cash assigned but PC tab current balance is over its cash limit of') . ' ' . locale_number_format($Limit['tablimit'], $Limit['decimalplaces']) . ' ' . $Limit['currency'], 'warn');
		echo prnMsg(_('Add expenses before being allowed to assign more cash or ask the administrator to increase the limit'), 'warn');
	}
	if ($InputError != 1 and isset($SelectedIndex)) {
		$SQL = "UPDATE pcashdetails
				SET date = '" . FormatDateForSQL($_POST['Date']) . "',
					amount = '" . filter_number_format($_POST['Amount']) . "',
					authorized = '0000-00-00',
					notes = '" . $_POST['Notes'] . "',
				WHERE counterindex = '" . $SelectedIndex . "'";
		$Msg = _('Assignment of cash to PC Tab ') . ' ' . $SelectedTabs . ' ' . _('has been updated');
	} elseif ($InputError != 1) {
		// Add new record on submit
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
					)";
		$Msg = _('Assignment of cash to PC Tab ') . ' ' . $_POST['SelectedTabs'] . ' ' . _('has been created');
	}
	if ($InputError != 1) {
		//run the SQL from either of the above possibilites
		$Result = DB_query($SQL);
		echo prnMsg($Msg, 'success');
		unset($_POST['SelectedExpense']);
		unset($_POST['Amount']);
		unset($_POST['Notes']);
		unset($_POST['SelectedTabs']);
		unset($_POST['Date']);
	}
} elseif (isset($_GET['delete'])) {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>', ' ', $Title, '
		</h1></a></div>';
	$SQL = "DELETE FROM pcashdetails
		WHERE counterindex='" . $SelectedIndex . "'";
	$ErrMsg = _('The assignment of cash record could not be deleted because');
	$Result = DB_query($SQL, $ErrMsg);
	echo prnMsg(_('Assignment of cash to PC Tab ') . ' ' . $SelectedTabs . ' ' . _('has been deleted'), 'success');
	unset($_GET['delete']);
}
if (!isset($SelectedTabs)) {
	/* It could still be the second time the page has been run and a record has been selected for modification - SelectedTabs will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
	then none of the above are true and the list of sales types will be displayed with
	links to delete or edit each. These will call the same page again and allow update/input
	or deletion of the records*/
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>', ' ', $Title, '
		</h1></a></div>';
	echo '<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	$SQL = "SELECT tabcode
			FROM pctabs
			WHERE assigner='" . $_SESSION['UserID'] . "'
			ORDER BY tabcode";
	$Result = DB_query($SQL);
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Assign cash to'), '</label>
				<select name="SelectedTabs" class="form-control">';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['SelectTabs']) and $MyRow['tabcode'] == $_POST['SelectTabs']) {
			echo '<option selected="selected" value="', $MyRow['tabcode'], '">', $MyRow['tabcode'], '</option>';
		} else {
			echo '<option value="', $MyRow['tabcode'], '">', $MyRow['tabcode'], '</option>';
		}
	}
	echo '</select>
			</div>
		</div>';
	
	echo '<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" class="btn btn-success" name="Process" value="', _('Submit'), '" /></div></div>
			<div class="col-xs-4">
<div class="form-group"> <br />
			
		</div></div></div>';
	echo '</form>';
}
//end of ifs and buts!
if (isset($_POST['Process']) or isset($SelectedTabs)) {
	if (!isset($_POST['submit'])) {
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>', ' ', $Title, '
			</h1></a></div>';
	}
	echo '<div class="row">
			<div class="col-xs-4"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" class="btn btn-info">', _('Back to assign'), '</a>
		</div></div>';

	echo '<br /><div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '	<tr>
				<td>' . _('<strong>Petty Cash Tab</strong>') . ':</td>
				<td>' . $SelectedTabs . '</td>
			</tr>';
	echo '</table></div></div></div>';

	if (!isset($_GET['edit']) or isset($_POST['GO'])) {
		if (isset($_POST['Cancel'])) {
			unset($_POST['Amount']);
			unset($_POST['Date']);
			unset($_POST['Notes']);
		}
	if (!isset($Days)) {
			$Days = 30;
		}
		/* Retrieve decimal places to display */
		$SqlDecimalPlaces = "SELECT decimalplaces
					FROM currencies,pctabs
					WHERE currencies.currabrev = pctabs.currency
						AND tabcode='" . $SelectedTabs . "'";
		$Result = DB_query($SqlDecimalPlaces);
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
						AND date >=DATE_SUB(CURDATE(), INTERVAL " . $Days . " DAY)
					ORDER BY date,
							 counterindex ASC";
		$Result = DB_query($SQL);
		echo '<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">';
		echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';

		//Limit expenses history to X days
		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Detail of Tab Movements For Last'), '' . _(' Days') . '</label>
						<input type="hidden" name="SelectedTabs" value="', $SelectedTabs, '" />
						<input type="text" class="form-control" name="Days" value="', $Days, '" required="required" maxlength="3" size="4" /></div></div>
						<div class="col-xs-4">
<div class="form-group"><br />
						<input type="submit" class="btn btn-info" name="Go" value="' . _('Go') . '" /></div>
					</div>
				</div>
			<br />';

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
				<thead>
					<tr>
						<th class="ascending">', _('Date'), '</th>
						<th class="ascending">', _('Expense Code'), '</th>
						<th class="ascending">', _('Amount'), '</th>
						<th>', _('Purpose'), '</th>
						<th>', _('Notes'), '</th>
						<th>', _('Receipt'), '</th>
						<th class="ascending">', _('Date Authorised'), '</th>
						<th colspan="2">', _('Actions'), '</th>
					</tr>
				</thead>
				<tbody>';

		while ($MyRow = DB_fetch_array($Result)) {

			$SQLdes = "SELECT description
					FROM pcexpenses
					WHERE codeexpense='" . $MyRow['codeexpense'] . "'";
			$ResultDes = DB_query($SQLdes);
			$Description = DB_fetch_array($ResultDes);
			if (!isset($Description[0])) {
				$ExpenseCodeDes = 'ASSIGNCASH';
			} else {
					$ExpenseCodeDes = $MyRow['codeexpense'] . ' - ' . $Description[0];
			}

			if ($MyRow['authorized'] == '0000-00-00') {
				$AuthorisedDate = _('Unauthorised');
			} else {
				$AuthorisedDate = ConvertSQLDate($MyRow['authorized']);
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
				$ReceiptText = '<a href="' . $ReceiptPath . '" class="btn btn-info" download="ExpenseReceipt-' . mb_strtolower($SelectedTabs) . '-[' . $MyRow['date'] . ']-[' . $MyRow['counterindex'] . ']">' . _('Download') . '</a>';
			} elseif ($ExpenseCodeDes == 'ASSIGNCASH') {
				$ReceiptText = '';
			} else {
				$ReceiptText = _('No attachment');
			}

			if (($MyRow['authorized'] == '0000-00-00') and ($ExpenseCodeDes == 'ASSIGNCASH')) {
				// only cash assignations NOT authorized can be modified or deleted
				echo '<tr class="striped_row">
					<td>', ConvertSQLDate($MyRow['date']), '</td>
					<td>', $ExpenseCodeDes, '</td>
					<td class="number">', locale_number_format($MyRow['amount'], $CurrDecimalPlaces), '</td>
					<td>', $MyRow['purpose'], '</td>
					<td>', $MyRow['notes'], '</td>
					<td>', $ReceiptText, '</td>
					<td>', $AuthorisedDate, '</td>
					<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?SelectedIndex=', $MyRow['counterindex'], '&amp;SelectedTabs=', $SelectedTabs, '&amp;Days=', $Days, '&amp;edit=yes" class="btn btn-info">', _('Edit'), '</a></td>
					<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?SelectedIndex=', $MyRow['counterindex'], '&amp;SelectedTabs=', $SelectedTabs, '&amp;Days=', $Days, '&amp;delete=yes" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this assigned cash?') . '");\'>' . _('Delete') . '</a></td>
				</tr>';
			} else {
				echo '<tr class="striped_row">
					<td>', ConvertSQLDate($MyRow['date']), '</td>
					<td>', $ExpenseCodeDes, '</td>
					<td class="number">', locale_number_format($MyRow['amount'], $CurrDecimalPlaces), '</td>
					<td>', $MyRow['purpose'], '</td>
					<td>', $MyRow['notes'], '</td>
					<td>', $ReceiptText, '</td>
					<td>', $AuthorisedDate, '</td>
				</tr>';
			}
		}
		//END WHILE LIST LOOP
		$SQLamount = "SELECT sum(amount)
					FROM pcashdetails
					WHERE tabcode='" . $SelectedTabs . "'";
		$ResultAmount = DB_query($SQLamount);
		$Amount = DB_fetch_array($ResultAmount);
		if (!isset($Amount['0'])) {
			$Amount['0'] = 0;
		}
		echo '</tbody>
			
				<tr>
					<td colspan="2" class="number">', _('<strong>Current balance</strong>'), ':</td>
					<td class="number">', locale_number_format($Amount['0'], $CurrDecimalPlaces), '</td>
				</tr>
			';
		echo '</table>
		</div></div></div>
			</form>';
	}
	if (!isset($_GET['delete'])) {
		if (!isset($Amount['0'])) {
			$Amount['0'] = 0;
		}
		echo '<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">';
		echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
		if (isset($_GET['edit'])) {
			/* Retrieve decimal places to display */
			$SqlDecimalPlaces = "SELECT decimalplaces
						FROM currencies,pctabs
						WHERE currencies.currabrev = pctabs.currency
							AND tabcode='" . $SelectedTabs . "'";
			$Result = DB_query($SqlDecimalPlaces);
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
						WHERE counterindex='" . $SelectedIndex . "'";
			$Result = DB_query($SQL);
			$MyRow = DB_fetch_array($Result);
			$_POST['Date'] = ConvertSQLDate($MyRow['date']);
			$_POST['SelectedExpense'] = $MyRow['codeexpense'];
			$_POST['Amount'] = $MyRow['amount'];
			$_POST['Notes'] = $MyRow['notes'];
			echo '<input type="hidden" name="SelectedTabs" value="', $SelectedTabs, '" />';
			echo '<input type="hidden" name="SelectedIndex" value="', $SelectedIndex, '" />';
			echo '<input type="hidden" name="CurrentAmount" value="', $Amount[0], '" />';
			echo '<input type="hidden" name="Days" value="', $Days, '" />';
		}
		/* Ricard: needs revision of this date initialization */
		if (!isset($_POST['Date'])) {
			$_POST['Date'] = Date($_SESSION['DefaultDateFormat']);
		}
		echo '<br /><div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
'; //Main table
		if (isset($_GET['SelectedIndex'])) {
			echo '<tr>
					<th colspan="2"><h3>', _('<strong>Update Cash Assignment</strong>'), '</h3></th>
				</tr>';
		} else {
			echo '<tr>
					<th colspan="2"><h3>', _('<strong>New Cash Assignment</strong>'), '</h3></th>
				</tr>';
		}
		echo '<tr>
				<td>', _('Cash Assignment Date'), ':</td>';
		echo '<td>
				<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="Date" size="11" required="required" maxlength="10" value="', $_POST['Date'], '" />
			</td>
		</tr>';
		if (!isset($_POST['Amount'])) {
			$_POST['Amount'] = 0;
		}
		echo '<tr>
				<td>', _('Amount'), ':</td>
				<td><input type="text" class="form-control" name="Amount" size="12" required="required" maxlength="11" value="', locale_number_format($_POST['Amount'], $CurrDecimalPlaces), '" /></td>
			</tr>';
		if (!isset($_POST['Notes'])) {
			$_POST['Notes'] = '';
		}
		echo '<tr>
				<td>', _('Notes'), ':</td>
				<td><input type="text" name="Notes" class="form-control" size="50" maxlength="49" value="', $_POST['Notes'], '" /></td>
			</tr>';
		echo '</table></div></div></div>'; // close main table
		echo '<input type="hidden" name="CurrentAmount" value="', $Amount['0'], '" />';
		echo '<input type="hidden" name="SelectedTabs" value="', $SelectedTabs, '" />';
		echo '<input type="hidden" name="Days" value="', $Days, '" />';
		echo '<div class="row" align="center"><div>
				<input type="submit" class="btn btn-success" name="submit" value="', _('Submit'), '" /></div>
				<div class="col-xs-4">
				
			</div></div><br />
';
		echo '</form>';
	} // end if user wish to delete
}
include('includes/footer.php');
?>