<?php
include('includes/session.php');
$Title = _('Purchases from Suppliers');
$ViewTopic = 'PurchaseOrdering';
$BookMark = 'PurchasesReport';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>  ', // Icon title.
	$Title, '</h1></a></div>';// Page title.

// Merges gets into posts:
if(isset($_GET['PeriodFrom'])) {// Select period from.
	$_POST['PeriodFrom'] = $_GET['PeriodFrom'];
}
if(isset($_GET['PeriodTo'])) {// Select period to.
	$_POST['PeriodTo'] = $_GET['PeriodTo'];
}
if(isset($_GET['ShowDetails'])) {// Show the budget for the period.
	$_POST['ShowDetails'] = $_GET['ShowDetails'];
}

// Validates the data submitted in the form:
if(isset($_POST['PeriodFrom']) AND isset($_POST['PeriodTo'])) {
	if(Date1GreaterThanDate2($_POST['PeriodFrom'], $_POST['PeriodTo'])) {
		// The beginning is after the end.
		unset($_POST['PeriodFrom']);
		unset($_POST['PeriodTo']);
		echo prnMsg(_('The beginning of the period should be before or equal to the end of the period. Please reselect the reporting period.'), 'error');
	}
}

// Main code:
if(isset($_POST['PeriodFrom']) AND isset($_POST['PeriodTo']) AND $_POST['Action']!='New') {// If all parameters are set and valid, generates the report:
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>';
	$TableFoot =
			'</tr>
		</thead><tfoot>
			<tr>
				<td colspan="9">' .
					_('<strong>Notes</strong>') . '<br />' .
					_('Original amounts in the supplier\'s currency. GL amounts in the default currency.') .
				'</td>
			</tr>
		</tfoot><tbody>';// Common table code.
	$TotalGlAmount = 0;
	$TotalGlTax = 0;
	$PeriodFrom = FormatDateForSQL($_POST['PeriodFrom']);
	$PeriodTo = FormatDateForSQL($_POST['PeriodTo']);
	if($_POST['ShowDetails']) {// Parameters: PeriodFrom, PeriodTo, ShowDetails=on.
		echo		'<th>', _('Date'), '</th>
					<th>', _('Invoice #'), '</th>
					<th>', _('Reference'), '</th>
					<th>', _('Original Overall Amount'), '</th>
					<th>', _('Original Overall Taxes'), '</th>
					<th>', _('Original Overall Total'), '</th>
					<th>', _('GL Overall Amount'), '</th>
					<th>', _('GL Overall Taxes'), '</th>
					<th>', _('GL Overall Total'), '</th>', $TableFoot;
		$SupplierId = '';
		$SupplierOvAmount = 0;
		$SupplierOvTax = 0;
		$SupplierGlAmount = 0;
		$SupplierGlTax = 0;
		$Sql = "SELECT
					supptrans.supplierno,
					suppliers.suppname,
					suppliers.currcode,
					supptrans.trandate,
					supptrans.suppreference,
					supptrans.transno,
					supptrans.ovamount,
					supptrans.ovgst,
					supptrans.rate
				FROM supptrans
					INNER JOIN suppliers ON supptrans.supplierno=suppliers.supplierid
				WHERE supptrans.trandate>='" . $PeriodFrom . "'
					AND supptrans.trandate<='" . $PeriodTo . "'
					AND supptrans.`type`=20
				ORDER BY supptrans.supplierno, supptrans.trandate";
		$Result = DB_query($Sql);
		include('includes/CurrenciesArray.php'); // To get the currency name from the currency code.
		foreach($Result as $MyRow) {
			if($MyRow['supplierno'] != $SupplierId) {// If different, prints supplier totals:
				if($SupplierId != '') {// If NOT the first line.
					echo '<tr>',
							'<td colspan="3">&nbsp;</td>',
							'<td>', locale_number_format($SupplierOvAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td>', locale_number_format($SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td>', locale_number_format($SupplierOvAmount+$SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td>', locale_number_format($SupplierGlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td>', locale_number_format($SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td>', locale_number_format($SupplierGlAmount+$SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
						'</tr>';
				}
				echo '<tr><td colspan="9">&nbsp;</td></tr>';
				echo '<tr><td class="text" colspan="9">', $MyRow['supplierno'], ' - ', $MyRow['suppname'], ' - ', $MyRow['currcode'], ' ', $CurrencyName[$MyRow['currcode']], '</td></tr>';
				$TotalGlAmount += $SupplierGlAmount;
				$TotalGlTax += $SupplierGlTax;
				$SupplierId = $MyRow['supplierno'];
				$SupplierOvAmount = 0;
				$SupplierOvTax = 0;
				$SupplierGlAmount = 0;
				$SupplierGlTax = 0;
			}

			$GlAmount = $MyRow['ovamount']/$MyRow['rate'];
			$GlTax = $MyRow['ovgst']/$MyRow['rate'];
			echo '<tr class="striped_row">
					<td >', $MyRow['trandate'], '</td>',
					'<td>', $MyRow['transno'], '</td>',
					'<td class="text">', $MyRow['suppreference'], '</td>',
					'<td>', locale_number_format($MyRow['ovamount'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td>', locale_number_format($MyRow['ovgst'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td><a href="', $RootPath, '/SuppWhereAlloc.php?TransType=20&TransNo=', $MyRow['transno'], '&amp;ScriptFrom=PurchasesReport" target="_blank" title="', _('Click to view where allocated'), '">', locale_number_format($MyRow['ovamount']+$MyRow['ovgst'], $_SESSION['CompanyRecord']['decimalplaces']), '</a></td>',
					'<td>', locale_number_format($GlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td>',	locale_number_format($GlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td><a href="', $RootPath, '/GLTransInquiry.php?TypeID=20&amp;TransNo=', $MyRow['transno'], '&amp;ScriptFrom=PurchasesReport" target="_blank" title="', _('Click to view the GL entries'), '">', locale_number_format($GlAmount+$GlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</a></td>', // RChacon: Should be "Click to view the General Ledger transaction" instead?
				'</tr>';
			$SupplierOvAmount += $MyRow['ovamount'];
			$SupplierOvTax += $MyRow['ovgst'];
			$SupplierGlAmount += $GlAmount;
			$SupplierGlTax += $GlTax;
		}

		// Prints last supplier total:
		echo '<tr>',
				'<td colspan="3">&nbsp;</td>',
				'<td>', locale_number_format($SupplierOvAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td>', locale_number_format($SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td>', locale_number_format($SupplierOvAmount+$SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td>', locale_number_format($SupplierGlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td>', locale_number_format($SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td>', locale_number_format($SupplierGlAmount+$SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
			'</tr>',
			'<tr><td colspan="9">&nbsp;</td></tr>';

		$TotalGlAmount += $SupplierGlAmount;
		$TotalGlTax += $SupplierGlTax;

	} else {// Parameters: PeriodFrom, PeriodTo, ShowDetails=off.
		// RChacon: Needs to update the table_sort function to use in this table.
		echo		'<th>', _('Supplier Code'), '</th>
					<th>', _('Supplier Name'), '</th>
					<th>', _('Supplier\'s Currency'), '</th>
					<th>', _('Original Overall Amount'), '</th>
					<th>', _('Original Overall Taxes'), '</th>
					<th>', _('Original Overall Total'), '</th>
					<th>', _('GL Overall Amount'), '</th>
					<th>', _('GL Overall Taxes'), '</th>
					<th>', _('GL Overall Total'), '</th>', $TableFoot;
		$Sql = "SELECT
					supptrans.supplierno,
					suppliers.suppname,
					suppliers.currcode,
					SUM(supptrans.ovamount) AS SupplierOvAmount,
					SUM(supptrans.ovgst) AS SupplierOvTax,
					SUM(supptrans.ovamount/supptrans.rate) AS SupplierGlAmount,
					SUM(supptrans.ovgst/supptrans.rate) AS SupplierGlTax
				FROM supptrans
					INNER JOIN suppliers ON supptrans.supplierno=suppliers.supplierid
				WHERE supptrans.trandate>='" . $PeriodFrom . "'
					AND supptrans.trandate<='" . $PeriodTo . "'
					AND supptrans.`type`=20
				GROUP BY
					supptrans.supplierno
				ORDER BY supptrans.supplierno, supptrans.trandate";
		$Result = DB_query($Sql);
		foreach($Result as $MyRow) {
			echo '<tr class="striped_row">
					<td class="text"><a href="', $RootPath, '/SupplierInquiry.php?SupplierID=', $MyRow['supplierno'], '">', $MyRow['supplierno'], '</a></td>',
					'<td class="text">', $MyRow['suppname'], '</td>',
					'<td class="text">', $MyRow['currcode'], '</td>',
					'<td>', locale_number_format($MyRow['SupplierOvAmount'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td>', locale_number_format($MyRow['SupplierOvTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td>', locale_number_format($MyRow['SupplierOvAmount']+$MyRow['SupplierOvTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td>', locale_number_format($MyRow['SupplierGlAmount'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td>', locale_number_format($MyRow['SupplierGlTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td>', locale_number_format($MyRow['SupplierGlAmount']+$MyRow['SupplierGlTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'</tr>';
			$TotalGlAmount += $MyRow['SupplierGlAmount'];
			$TotalGlTax += $MyRow['SupplierGlTax'];
		}
	}
	echo	'<tr>
				<td class="text" colspan="6">&nbsp;</td>
				<td>', locale_number_format($TotalGlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td>', locale_number_format($TotalGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td>', locale_number_format($TotalGlAmount+$TotalGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
			</tr>',// Prints all suppliers total.
		'</tbody></table></div></div></div>
		<br />
		
		<form action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '" method="post">
		<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />
		<input name="PeriodFrom" type="hidden" value="', $_POST['PeriodFrom'], '" />
		<input name="PeriodTo" type="hidden" value="', $_POST['PeriodTo'], '" />
		<input name="ShowDetails" type="hidden" value="', $_POST['ShowDetails'], '" />
		<div class="row noprint">', // Form buttons:
		
			'<div class="col-xs-2">
<div class="form-group">',
			'<button name="Action" type="submit" value="New" class="btn btn-success"> ', _('Back to Search'), '</button>', // "New Report" button.
			'</div></div>',
			'<div class="col-xs-2">
<div class="form-group">',
			'<button onclick="window.location=\'menu_data.php?Application=PO\'" class="btn btn-default" type="button">', _('Back to Menu'), '</button>', // "Return" button.
			'</div></div>',
		'</div>';

} else {
	// Shows a form to allow input of criteria for the report to generate:
	echo '<br />',
		'
		
		<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
		
		// Content of the body of the input table:
			// Select period from:
			'<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('From'), '</label>';
	if(!isset($_POST['PeriodFrom'])) {
		$_POST['PeriodFrom'] = date($_SESSION['DefaultDateFormat'], strtotime("-1 year", time()));// One year before current date.
	}
	echo 		'<input class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" id="PeriodFrom" maxlength="10" name="PeriodFrom" required="required" size="11" type="text" value="', $_POST['PeriodFrom'], '" />',
					(!isset($_SESSION['ShowFieldHelp']) || $_SESSION['ShowFieldHelp'] ? _('Select the beginning of the reporting period') : ''), // If it is not set the $_SESSION['ShowFieldHelp'] parameter OR it is TRUE, shows the page help text.
		 		'</div></div>',
			// Select period to:
			'<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('To'), '</label>';
	if(!isset($_POST['PeriodTo'])) {
		$_POST['PeriodTo'] = date($_SESSION['DefaultDateFormat']);
	}
	echo 		'<input class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" id="PeriodTo" maxlength="10" name="PeriodTo" required="required" size="11" type="text" value="', $_POST['PeriodTo'], '" />',
					(!isset($_SESSION['ShowFieldHelp']) || $_SESSION['ShowFieldHelp'] ? _('Select the end of the reporting period') : ''), // If it is not set the $_SESSION['ShowFieldHelp'] parameter OR it is TRUE, shows the page help text.
		 		'</div>
			</div>',
			// Show the budget for the period:
			'<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Show details?'), '</label>
<div class="checkbox"><label>
			 	<input', (isset($_POST['ShowDetails']) ? ' checked="checked"' : ''), ' name="ShowDetails" type="checkbox">', // "Checked" if ShowDetails is set AND it is TRUE.
			 		(!isset($_SESSION['ShowFieldHelp']) || $_SESSION['ShowFieldHelp'] ? _('Check this box to show purchase invoices') : ''), // If it is not set the $_SESSION['ShowFieldHelp'] parameter OR it is TRUE, shows the page help text.
		 		'</label></div></div>
			</div></div>',
			'<div class="row">
			<div class="col-xs-4">
<div class="form-group">
<button name="Action" type="submit" class="btn btn-success" value="', _('Submit'), '"> ', _('Submit'), '</button>
</div></div>
<div class="col-xs-4">
<div class="form-group">
', // "Submit" button.
						'<button onclick="window.location=\'menu_data.php?Application=PO\'" class="btn btn-default" type="button">', _('Back to Menu'), '</button></div></div>', // "Return" button.
					'</div>',			
		 '';

}
echo	'</form>';
include('includes/footer.php');
// END: Procedure division -----------------------------------------------------
?>
