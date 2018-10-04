<?php

include('includes/session.php');
$Title = _('Authorisation of Assigned Cash');
/* nERP manual links before header.php */
$ViewTopic = 'PettyCash';
$BookMark = 'AuthorizeCash';
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
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
	$Days = filter_number_format($_POST['Days']);
} elseif (isset($_GET['Days'])) {
	$Days = filter_number_format($_GET['Days']);
}
if (isset($_POST['Process'])) {
	if ($SelectedTabs == '') {
		echo prnMsg(_('You must first select a petty cash tab to authorise'), 'error');
		unset($SelectedTabs);
	}
}
if (isset($_POST['Go'])) {
	if ($Days <= 0) {
		echo prnMsg(_('The number of days must be a positive number'), 'error');
		$Days = 30;
	}
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1>', _('Authorisation of Assigned Cash '), '
		</h1></a></div>';

if (isset($SelectedTabs)) {
echo '<div class="row gutter30">
<div class="col-xs-8">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
echo '	<tr>
			<td>' . _('Petty Cash Tab') . ':</td>
			<td>' . $SelectedTabs . '</td>
		</tr>';
echo '</table></div></div></div>';
}

if (isset($_POST['Submit']) or isset($_POST['update']) or isset($SelectedTabs) or isset($_POST['GO'])) {
	echo '<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	if (!isset($Days)) {
		$Days = 30;
	}

	//Limit expenses history to X days
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Detail of Tab Movements For Last (Days) '), '</label>
					<input type="hidden" name="SelectedTabs" value="', $SelectedTabs, '" />
					<input type="text" class="form-control" name="Days" value="', $Days, '" maxlength="3" size="4" />', '</div></div>
					<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" class="btn btn-info" name="Go" value="', _('Go'), '" />
				</div>
			</div>
		</div>';
	$SQL = "SELECT pcashdetails.counterindex,
				pcashdetails.tabcode,
				pcashdetails.date,
				pcashdetails.codeexpense,
				pcashdetails.amount,
				pcashdetails.authorized,
				pcashdetails.posted,
				pcashdetails.notes,
				pctabs.glaccountassignment,
				pctabs.glaccountpcash,
				pctabs.usercode,
				pctabs.currency,
				currencies.rate,
				currencies.decimalplaces
			FROM pcashdetails, pctabs, currencies
			WHERE pcashdetails.tabcode = pctabs.tabcode
				AND pctabs.currency = currencies.currabrev
				AND pcashdetails.tabcode = '" . $SelectedTabs . "'
				AND pcashdetails.date >= DATE_SUB(CURDATE(), INTERVAL '" . $Days . "' DAY)
				AND pcashdetails.codeexpense='ASSIGNCASH'
			ORDER BY pcashdetails.date, pcashdetails.counterindex ASC";
	$Result = DB_query($SQL);
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<tr>
				<th>', _('Date'), '</th>
				<th>', _('Expense Code'), '</th>
				<th>', _('Amount'), '</th>
				<th>', _('Notes'), '</th>
				<th>', _('Date Authorised'), '</th>
			</tr>';

	$CurrDecimalPlaces = 2;
	while ($MyRow = DB_fetch_array($Result)) {
		$CurrDecimalPlaces = $MyRow['decimalplaces'];
		//update database if update pressed
		if (isset($_POST['Submit']) and $_POST['Submit'] == _('Update') and isset($_POST[$MyRow['counterindex']]) and $MyRow['posted'] == 0) {
			$PeriodNo = GetPeriod(ConvertSQLDate($MyRow['date']));
			if ($MyRow['rate'] == 1) { // functional currency
				$Amount = $MyRow['amount'];
			} else { // other currencies
				$Amount = $MyRow['amount'] / $MyRow['rate'];
			}
			if ($MyRow['codeexpense'] == 'ASSIGNCASH') {
				$type = 2;
				$AccountFrom = $MyRow['glaccountassignment'];
				$AccountTo = $MyRow['glaccountpcash'];
				$TagTo = 0;
			} else {
				$type = 1;
				$Amount = -$Amount;
				$AccountFrom = $MyRow['glaccountpcash'];
				$SQLAccExp = "SELECT glaccount,
									tag
								FROM pcexpenses
								WHERE codeexpense = '" . $MyRow['codeexpense'] . "'";
				$ResultAccExp = DB_query($SQLAccExp);
				$MyRowAccExp = DB_fetch_array($ResultAccExp);
				$AccountTo = $MyRowAccExp['glaccount'];
				$TagTo = $MyRowAccExp['tag'];
			}
			//get typeno
			$typeno = GetNextTransNo($type);
			//build narrative
			$Narrative = _('PettyCash') . ' - ' . $MyRow['tabcode'] . ' - ' . $MyRow['codeexpense'] . ' - ' . DB_escape_string($MyRow['notes']);
			//insert to gltrans
			DB_Txn_Begin();
			$SQLFrom = "INSERT INTO `gltrans` (`counterindex`,
											`type`,
											`typeno`,
											`chequeno`,
											`trandate`,
											`periodno`,
											`account`,
											`narrative`,
											`amount`,
											`posted`,
											`jobref`,
											`tag`)
									VALUES (NULL,
											'" . $type . "',
											'" . $typeno . "',
											0,
											'" . $MyRow['date'] . "',
											'" . $PeriodNo . "',
											'" . $AccountFrom . "',
											'" . $Narrative . "',
											'" . -$Amount . "',
											0,
											'',
											'" . $TagTo ."')";
			$ResultFrom = DB_Query($SQLFrom, '', '', true);
			$SQLTo = "INSERT INTO `gltrans` (`counterindex`,
										`type`,
										`typeno`,
										`chequeno`,
										`trandate`,
										`periodno`,
										`account`,
										`narrative`,
										`amount`,
										`posted`,
										`jobref`,
										`tag`
									) VALUES (NULL,
										'" . $type . "',
										'" . $typeno . "',
										0,
										'" . $MyRow['date'] . "',
										'" . $PeriodNo . "',
										'" . $AccountTo . "',
										'" . $Narrative . "',
										'" . $Amount . "',
										0,
										'',
										'" . $TagTo ."'
									)";
			$ResultTo = DB_Query($SQLTo, '', '', true);
			if ($MyRow['codeexpense'] == 'ASSIGNCASH') {
				// if it's a cash assignation we need to updated banktrans table as well.
				$ReceiptTransNo = GetNextTransNo(2);
				$SQLBank = "INSERT INTO banktrans (transno,
												type,
												bankact,
												ref,
												exrate,
												functionalexrate,
												transdate,
												banktranstype,
												amount,
												currcode
											) VALUES (
												'" . $ReceiptTransNo . "',
												1,
												'" . $AccountFrom . "',
												'" . $Narrative . "',
												1,
												'" . $MyRow['rate'] . "',
												'" . $MyRow['date'] . "',
												'Cash',
												'" . -$MyRow['amount'] . "',
												'" . $MyRow['currency'] . "'
											)";
				$ErrMsg = _('Cannot insert a bank transaction because');
				$DbgMsg = _('Cannot insert a bank transaction with the SQL');
				$ResultBank = DB_query($SQLBank, $ErrMsg, $DbgMsg, true);
			}
			$SQL = "UPDATE pcashdetails
					SET authorized = CURRENT_DATE,
					posted = 1
					WHERE counterindex = '" . $MyRow['counterindex'] . "'";
			$Resultupdate = DB_query($SQL, '', '', true);
			DB_Txn_Commit();
			if (DB_error_no() == 0) {
				echo prnMsg(_('The cash was successfully authorised and has been posted to the General Ledger'), 'success');
			} else {
				echo prnMsg(_('There was a problem authorising the cash, and the transaction has not been posted'), 'error');
			}
		} else if ($MyRow['posted'] == 1) {
			echo prnMsg(_('This cash has already been authorised, and cannot be posted again'), 'error');
		}

		echo '<tr class="striped_row">
			<td>', ConvertSQLDate($MyRow['date']), '</td>
			<td>', $MyRow['codeexpense'], '</td>
			<td class="number">', locale_number_format($MyRow['amount'], $CurrDecimalPlaces), '</td>
			<td>', $MyRow['notes'], '</td>';
		if (isset($_POST[$MyRow['counterindex']])) {
			echo '<td>' . ConvertSQLDate(Date('Y-m-d'));
		} else {
			//compare against raw SQL format date, then convert for display.
			if (($MyRow['authorized'] != '0000-00-00')) {
				echo '<td>', ConvertSQLDate($MyRow['authorized']);
			} else {
				echo '<td align="right"><input type="checkbox" name="', $MyRow['counterindex'], '" />';
			}
		}
		echo '<input type="hidden" name="SelectedIndex" value="', $MyRow['counterindex'], '" />
			</td>
		</tr>';
	} //end of looping
	$SQLamount = "SELECT sum(amount)
			FROM pcashdetails
			WHERE tabcode='" . $SelectedTabs . "'
				AND codeexpense='ASSIGNCASH'";
	$ResultAmount = DB_query($SQLamount);
	$Amount = DB_fetch_array($ResultAmount);
	if (!isset($Amount['0'])) {
		$Amount['0'] = 0;
	}
	echo '<tr>
			<td colspan="2" class="number">', _('Current balance'), ':</td>
			<td class="number">', locale_number_format($Amount['0'], $CurrDecimalPlaces), '</td>
		</tr>';
	// Do the postings
	include('includes/GLPostings.inc');
	echo '</table></div></div></div>';
	echo '<div class="row" align="center"><div>
			<input type="submit" name="Submit" class="btn btn-info" value="', _('Update'), '" />
		</div></div><br />
	</form>';
} else {
	/*The option to submit was not hit so display form */
	echo '<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	$SQL = "SELECT tabcode
		FROM pctabs
		WHERE authorizer='" . $_SESSION['UserID'] . "'";
	$Result = DB_query($SQL);
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Authorise cash assigned to'), '</label>
				<select required="required" name="SelectedTabs" class="form-control">';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['SelectTabs']) and $MyRow['tabcode'] == $_POST['SelectTabs']) {
			echo '<option selected="selected" value="', $MyRow['tabcode'], '">', $MyRow['tabcode'], '</option>';
		} else {
			echo '<option value="', $MyRow['tabcode'], '">', $MyRow['tabcode'], '</option>';
		}
	} //end while loop get type of tab
	echo '</select>
			</div>
		</div>';
	
	echo '<div class="col-xs-4">
<div class="form-group"> <br />
			<input type="submit" name="Process" value="', _('Submit'), '" class="btn btn-success" /></div></div>
			<div class="col-xs-4">
<div class="form-group"><br />
			
		</div></div><br />
</div>';
	echo '</form>';
}
/*end of else not submit */
include('includes/footer.php');
?>