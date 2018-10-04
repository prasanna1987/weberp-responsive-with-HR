<?php


include('includes/session.php');

if (isset($_POST['PrintPDF'])
	AND isset($_POST['FromCriteria'])
	AND mb_strlen($_POST['FromCriteria'])>=1
	AND isset($_POST['ToCriteria'])
	AND mb_strlen($_POST['ToCriteria'])>=1){

	include('includes/PDFStarter.php');
	$pdf->addInfo('Title',_('Customer Balance Listing'));
	$pdf->addInfo('Subject',_('Customer Balances'));
	$FontSize=12;
	$PageNumber=0;
	$line_height=12;

	/*Get the date of the last day in the period selected */

	$SQL = "SELECT lastdate_in_period FROM periods WHERE periodno = '" . $_POST['PeriodEnd']."'";
	$PeriodEndResult = DB_query($SQL,_('Could not get the date of the last day in the period selected'));
	$PeriodRow = DB_fetch_row($PeriodEndResult);
	$PeriodEndDate = ConvertSQLDate($PeriodRow[0]);

	  /*Now figure out the aged analysis for the customer range under review */

	$SQL = "SELECT debtorsmaster.debtorno,
					debtorsmaster.name,
		  			currencies.currency,
		  			currencies.decimalplaces,
					SUM((debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc)/debtortrans.rate) AS balance,
					SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) AS fxbalance,
					SUM(CASE WHEN debtortrans.prd > '" . $_POST['PeriodEnd'] . "' THEN
					(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount)/debtortrans.rate ELSE 0 END) AS afterdatetrans,
					SUM(CASE WHEN debtortrans.prd > '" . $_POST['PeriodEnd'] . "'
						AND (debtortrans.type=11 OR debtortrans.type=12) THEN
						debtortrans.diffonexch ELSE 0 END) AS afterdatediffonexch,
					SUM(CASE WHEN debtortrans.prd > '" . $_POST['PeriodEnd'] . "' THEN
					debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount ELSE 0 END
					) AS fxafterdatetrans
			FROM debtorsmaster INNER JOIN currencies
			ON debtorsmaster.currcode = currencies.currabrev
			INNER JOIN debtortrans
			ON debtorsmaster.debtorno = debtortrans.debtorno
			WHERE debtorsmaster.debtorno >= '" . $_POST['FromCriteria'] . "'
			AND debtorsmaster.debtorno <= '" . $_POST['ToCriteria'] . "'
			GROUP BY debtorsmaster.debtorno,
				debtorsmaster.name,
				currencies.currency,
				currencies.decimalplaces";

	$CustomerResult = DB_query($SQL,'','',false,false);

	if (DB_error_no() !=0) {
		$Title = _('Customer Balances') . ' - ' . _('Problem Report');
		include('includes/header.php');
		echo prnMsg(_('The customer details could not be retrieved by nERP because') . DB_error_msg(),'error');
		echo '<br /><p align="right"><a href="' . $RootPath . '/index.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';

		if ($debug==1){
			echo '<br />' . $SQL;
		}
		include('includes/footer.php');
		exit;
	}

	if (DB_num_rows($CustomerResult) == 0) {
		$Title = _('Customer Balances') . ' - ' . _('Problem Report');
		include('includes/header.php');
		echo prnMsg(_('The customer details listing has no data to report'),'warn');
		echo '<br /><p align="right"><a href="' . $RootPath . '/index.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';

		include('includes/footer.php');
		exit;
	}

	include ('includes/PDFDebtorBalsPageHeader.inc');

	$TotBal=0;

	while ($DebtorBalances = DB_fetch_array($CustomerResult)){

		$Balance = $DebtorBalances['balance'] - $DebtorBalances['afterdatetrans'] + $DebtorBalances['afterdatediffonexch'] ;
		$FXBalance = $DebtorBalances['fxbalance'] - $DebtorBalances['fxafterdatetrans'];

		if (abs($Balance)>0.009 OR ABS($FXBalance)>0.009) {

			$DisplayBalance = locale_number_format($DebtorBalances['balance'] - $DebtorBalances['afterdatetrans'],$DebtorBalances['decimalplaces']);
			$DisplayFXBalance = locale_number_format($DebtorBalances['fxbalance'] - $DebtorBalances['fxafterdatetrans'],$DebtorBalances['decimalplaces']);

			$TotBal += $Balance;

			$LeftOvers = $pdf->addTextWrap($Left_Margin+3,$YPos,220-$Left_Margin,$FontSize,$DebtorBalances['debtorno'] .
				' - ' . html_entity_decode($DebtorBalances['name'],ENT_QUOTES,'UTF-8'),'left');
			$LeftOvers = $pdf->addTextWrap(220,$YPos,60,$FontSize,$DisplayBalance,'right');
			$LeftOvers = $pdf->addTextWrap(280,$YPos,60,$FontSize,$DisplayFXBalance,'right');
			$LeftOvers = $pdf->addTextWrap(350,$YPos,100,$FontSize,$DebtorBalances['currency'],'left');


			$YPos -=$line_height;
			if ($YPos < $Bottom_Margin + $line_height){
				include('includes/PDFDebtorBalsPageHeader.inc');
			}
		}
	} /*end customer aged analysis while loop */

	$YPos -=$line_height;
	if ($YPos < $Bottom_Margin + (2*$line_height)){
		$PageNumber++;
		include('includes/PDFDebtorBalsPageHeader.inc');
	}

	$DisplayTotBalance = locale_number_format($TotBal,$_SESSION['CompanyRecord']['decimalplaces']);

	$LeftOvers = $pdf->addTextWrap(50,$YPos,160,$FontSize,_('Total balances'),'left');
	$LeftOvers = $pdf->addTextWrap(220,$YPos,60,$FontSize,$DisplayTotBalance,'right');

	$pdf->OutputD($_SESSION['DatabaseName'] . '_DebtorBals_' . date('Y-m-d').'.pdf');
	$pdf->__destruct();

} else { /*The option to print PDF was not hit */

	$Title=_('Customer Balances');

	$ViewTopic = 'ARReports';
	$BookMark = 'PriorMonthDebtors';

	include('includes/header.php');
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

	if (!isset($_POST['FromCriteria']) OR !isset($_POST['ToCriteria'])) {

	/*if $FromCriteria is not set then show a form to allow input	*/
		echo '<div class="row gutter30">
			<div class="col-xs-12">';

		echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
              ';
        echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

		
		echo '<div class="row">
			<div class="col-xs-4">
			<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('From Customer Code') .'</label>
			<input tabindex="1" type="text" class="form-control" maxlength="10" size="8" name="FromCriteria" required="required" data-type="no-illegal-chars" title="' . _('Enter a portion of the code of first customer to report') . '" value="1" /></div>
			</div>
			<div class="col-xs-4">
			<div class="form-group has-error"><label class="col-md-8 control-label">' . _('To Customer Code') . '</label>
			<input tabindex="2" class="form-control" type="text" maxlength="10" size="8" name="ToCriteria" required="required" data-type="no-illegal-chars" title="' . _('Enter a portion of the code of last customer to report') . '" value="zzzzzz" /></div>
			</div>
			<div class="col-xs-4">
			<div class="form-group"><label class="col-md-8 control-label">' . _('Balances As On') . '</label>
			<select tabindex="3" name="PeriodEnd" class="form-control">';

		$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno DESC";
		$Periods = DB_query($sql,_('Could not retrieve period data because'),_('The SQL that failed to get the period data was'));

		while ($myrow = DB_fetch_array($Periods)){

			echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';

		}
	}

	echo '</select></div>
		</div>
		</div>
		
		<div class="row">
		<div class="col-xs-4">
			<div class="form-group">
			<input tabindex="5" type="submit" class="btn btn-info" name="PrintPDF" value="' . _('Print PDF') . '" />
		</div>
        </div>
		</div>
		</form>
		</div>
		</div>
		';

	include('includes/footer.php');
} /*end of else not PrintPDF */

?>
