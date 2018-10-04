<?php
/* Shows the horizontal analysis of the statement of financial position. */

function RelativeChange($selected_period, $previous_period) {
	// Calculates the relative change between selected and previous periods. Uses percent with locale number format.
	if($previous_period<>0) {
		return locale_number_format(($selected_period-$previous_period)*100/$previous_period, $_SESSION['CompanyRecord']['decimalplaces']) . '%';
	} else {
		return _('N/A');
	}
}

include ('includes/session.php');
$Title = _('Horizontal Analysis of Statement of Financial Position');// Screen identification.
$ViewTopic = 'GeneralLedger';// Filename's id in ManualContents.php's TOC.
$BookMark = 'AnalysisHorizontalPosition';// Anchor's id in the manual's html document.
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.php'); // This loads the $Sections variable

if(! isset($_POST['BalancePeriodEnd']) or isset($_POST['SelectADifferentPeriod'])) {

	/*Show a form to allow input of criteria for TB to show */
	include('includes/header.php');
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>', // Icon title.
		_('Horizontal Analysis of Statement of Financial Position'), '</h1></a></div>';// Page title.

	echo '<div class="row"><p class="text-info">',
		_('Horizontal analysis (also known as trend analysis) is a financial statement analysis technique that shows changes in the amounts of corresponding financial statement items over a period of time. It is a useful tool to evaluate trend situations.'), '<br />',
		_('The statements for two periods are used in horizontal analysis. The earliest period is used as the base period. The items on the later statement are compared with items on the statement of the base period. The changes are shown both in currency (actual change) and percentage (relative change).'), '<br />',
		_('nERP is an "accrual" based system (not a "cash based" system).  Accrual systems include items when they are invoiced to the customer, and when expenses are owed based on the supplier invoice date.'), '</p></div>',
		// Show a form to allow input of criteria for the report to show:
		'<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '">',
		'<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />',
		'<br />',
		'<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Balance Period'), '</label>
				<select required="required" name="BalancePeriodEnd" class="form-control">';
/*				<td><select required="required" name="ToPeriod">';*/

	$periodno=GetPeriod(Date($_SESSION['DefaultDateFormat']));
	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='".$periodno . "'";
	$result = DB_query($sql);
	$myrow=DB_fetch_array($result);
	$lastdate_in_period=$myrow[0];

	$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno DESC";
	$Periods = DB_query($sql);

	while($myrow=DB_fetch_array($Periods)) {
		echo '<option';
		if($myrow['periodno']== $periodno) {
			echo ' selected="selected"';
		}
		echo ' value="', $myrow['periodno'], '">', MonthAndYearFromSQLDate($myrow['lastdate_in_period']), '</option>';
	}
	echo		'</select></div>
			</div>
			<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Report Type'), '</label>
				<select name="Detail" class="form-control" required="required" title="', _('Selecting Summary will show on the totals at the account group level'), '" >
					<option value="Summary">', _('Summary'), '</option>
					<option selected="selected" value="Detailed">', _('All Accounts'), '</option>
					</select></div>
			</div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Including 0 balance accounts?'), '</label>
				<div class="checkbox"><label><input name="ShowZeroBalances" title="', _('Check this box to display all accounts including those accounts with no balance'), '" type="checkbox" /></label></div>
			</div>
		</div></div>
		<br />', // Form buttons:
		'<div class="row noprint">',
			'<div class="col-xs-4">
<button name="ShowBalanceSheet" type="submit" value="', _('Show on Screen (HTML)'), '" class="btn btn-info">', _('Show'), '</button></div> ', // "Show on Screen (HTML)" button.
			'<div class="col-xs-4">
<button onclick="window.location=\'menu_data.php?Application=GL\'" type="button" class="btn btn-warning">', _('Return'), '</button></div>', // "Return" button.
		'</div><br />
';

	// Now do the posting while the user is thinking about the period to select:
	include ('includes/GLPostings.inc');

} else {
	include('includes/header.php');

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $_POST['BalancePeriodEnd'] . "'";
	$PrdResult = DB_query($sql);
	$myrow = DB_fetch_row($PrdResult);
	$BalanceDate = ConvertSQLDate($myrow[0]);

	// Page title as IAS 1, numerals 10 and 51:
	include_once('includes/CurrenciesArray.php');// Array to retrieve currency name.
	echo '<div id="Report">', // Division to identify the report block.
		'<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
		_('Horizontal Analysis of Statement of Financial Position'), '<br /><small>', // Page title, reporting statement.
		stripslashes($_SESSION['CompanyRecord']['coyname']), '<br />', // Page title, reporting entity.
		_('as at'), ' ', $BalanceDate, '<br />', // Page title, reporting period.
		_('All amounts stated in'), ': ', _($CurrencyName[$_SESSION['CompanyRecord']['currencydefault']]), '</small></h1></a></div>';// Page title, reporting presentation currency and level of rounding used.
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
		<tr>';
	if($_POST['Detail']=='Detailed') {// Detailed report:
		echo '<th class="text">', _('Account'), '</th>
			<th class="text">', _('Account Name'), '</th>';
	} else {// Summary report:
		echo '<th class="text" colspan="2">', _('Summary'), '</th>';
	}
	echo	'<th class="number">', _('Current period'), '</th>
			<th class="number">', _('Last period'), '</th>
			<th class="number">', _('Actual change'), '</th>
			<th class="number">', _('Relative change'), '</th>
		</tr>
		</thead>
		
			<tr>
				<td class="text-info" colspan="6">',// Prints an explanation of signs in actual and relative changes:
					'<h5><strong>', _('Notes'), ':</strong></h5>',
					_('Actual change signs: a positive number indicates a source of funds; a negative number indicates an application of funds.'), '<br />',
					_('Relative change signs: a positive number indicates an increase in the amount of that account; a negative number indicates a decrease in the amount of that account.'), '<br />',
				'</td>
			</tr>
		
		<tbody>';// thead and tfoot used in conjunction with tbody enable scrolling of the table body independently of the header and footer. Also, when printing a large table that spans multiple pages, these elements can enable the table header to be printed at the top of each page.

	// Calculate B/Fwd retained earnings:
	$SQL = "SELECT Sum(CASE WHEN chartdetails.period='" . $_POST['BalancePeriodEnd'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS accumprofitbfwd,
			Sum(CASE WHEN chartdetails.period='" . ($_POST['BalancePeriodEnd'] - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS accumprofitbfwdly
		FROM chartmaster INNER JOIN accountgroups
		ON chartmaster.group_ = accountgroups.groupname INNER JOIN chartdetails
		ON chartmaster.accountcode= chartdetails.accountcode
		WHERE accountgroups.pandl=1";

	$AccumProfitResult = DB_query($SQL,_('The accumulated profits brought forward could not be calculated by the SQL because'));

	$AccumProfitRow = DB_fetch_array($AccumProfitResult); /*should only be one row returned */

	$SQL = "SELECT accountgroups.sectioninaccounts,
			accountgroups.groupname,
			accountgroups.parentgroupname,
			chartdetails.accountcode,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['BalancePeriodEnd'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS balancecfwd,
			Sum(CASE WHEN chartdetails.period='" . ($_POST['BalancePeriodEnd'] - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS balancecfwdly
		FROM chartmaster
			INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails	ON chartmaster.accountcode= chartdetails.accountcode
			INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		WHERE accountgroups.pandl=0
		GROUP BY accountgroups.groupname,
			chartdetails.accountcode,
			chartmaster.accountname,
			accountgroups.parentgroupname,
			accountgroups.sequenceintb,
			accountgroups.sectioninaccounts
		ORDER BY accountgroups.sectioninaccounts,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";

	$AccountsResult = DB_query($SQL,_('No general ledger accounts were returned by the SQL because'));

	$CheckTotal=0;
	$CheckTotalLY=0;

	$Section='';
	$SectionBalance= 0;
	$SectionBalanceLY=0;

	$ActGrp='';
	$Level=0;
	$ParentGroups=array();
	$ParentGroups[$Level]='';
	$GroupTotal = array(0);
	$GroupTotalLY = array(0);

	$DrawTotalLine = '<tr>
		<td colspan="2">&nbsp;</td>
		<td><hr /></td>
		<td><hr /></td>
		<td><hr /></td>
		<td><hr /></td>
	</tr>';

	while($myrow=DB_fetch_array($AccountsResult)) {
		$AccountBalance = $myrow['balancecfwd'];
		$AccountBalanceLY = $myrow['balancecfwdly'];

		if($myrow['accountcode'] == $RetainedEarningsAct) {
			$AccountBalance += $AccumProfitRow['accumprofitbfwd'];
			$AccountBalanceLY += $AccumProfitRow['accumprofitbfwdly'];
		}

		if($myrow['groupname']!= $ActGrp AND $ActGrp != '') {
			if($myrow['parentgroupname']!=$ActGrp) {
				while($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
					if($_POST['Detail']=='Detailed') {
						echo $DrawTotalLine;
					}
					echo '<tr>
							<td colspan="2">', $ParentGroups[$Level], '</td>
							<td class="number">', locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td class="number">', locale_number_format($GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td class="number">', locale_number_format(-$GroupTotal[$Level]+$GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td class="number">', RelativeChange(-$GroupTotal[$Level],-$GroupTotalLY[$Level]), '</td>
						</tr>';
					$GroupTotal[$Level]=0;
					$GroupTotalLY[$Level]=0;
					$ParentGroups[$Level]='';
					$Level--;
				}
				if($_POST['Detail']=='Detailed') {
					echo $DrawTotalLine;
				}
				echo '<tr>
						<td class="text" colspan="2">', $ParentGroups[$Level], '</td>
						<td class="number">', locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td class="number">', locale_number_format($GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td class="number">', locale_number_format(-$GroupTotal[$Level]+$GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td class="number">', RelativeChange(-$GroupTotal[$Level],-$GroupTotalLY[$Level]), '</td>
					</tr>';
				$GroupTotal[$Level]=0;
				$GroupTotalLY[$Level]=0;
				$ParentGroups[$Level]='';
			}
		}
		if($myrow['sectioninaccounts'] != $Section ) {
			if($Section!='') {
				echo $DrawTotalLine;
				echo '<tr>
						<td class="text" colspan="2"><h2>', $Sections[$Section], '</h2></td>
						<td class="number"><h2>', locale_number_format($SectionBalance,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
						<td class="number"><h2>', locale_number_format($SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
						<td class="number"><h2>', locale_number_format(-$SectionBalance+$SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
						<td class="number"><h2>', RelativeChange(-$SectionBalance,-$SectionBalanceLY), '</h2></td>
					</tr>';
			}
			$SectionBalance=0;
			$SectionBalanceLY=0;
			$Section = $myrow['sectioninaccounts'];
			if($_POST['Detail']=='Detailed') {
				echo '<tr>
						<td colspan="6"><h2>', $Sections[$myrow['sectioninaccounts']], '</h2></td>
					</tr>';
			}
		}

		if($myrow['groupname'] != $ActGrp) {

			if($ActGrp!='' AND $myrow['parentgroupname']==$ActGrp) {
				$Level++;
			}

			if($_POST['Detail']=='Detailed') {
				$ActGrp = $myrow['groupname'];
				echo '<tr>
						<td colspan="6"><h3>', $myrow['groupname'], '</h3></td>
					</tr>';
			}
			$GroupTotal[$Level]=0;
			$GroupTotalLY[$Level]=0;
			$ActGrp = $myrow['groupname'];
			$ParentGroups[$Level] = $myrow['groupname'];
		}
		$SectionBalance += $AccountBalance;
		$SectionBalanceLY += $AccountBalanceLY;

		for ($i=0;$i<=$Level;$i++) {
			$GroupTotalLY[$i] += $AccountBalanceLY;
			$GroupTotal[$i] += $AccountBalance;
		}
		$CheckTotal += $AccountBalance;
		$CheckTotalLY += $AccountBalanceLY;

		if($_POST['Detail']=='Detailed') {
			if(isset($_POST['ShowZeroBalances']) OR (!isset($_POST['ShowZeroBalances']) AND (round($AccountBalance,$_SESSION['CompanyRecord']['decimalplaces']) <> 0 OR round($AccountBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']) <> 0))) {
				echo '<tr class="striped_row">
						<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?Period=', $_POST['BalancePeriodEnd'], '&amp;Account=', $myrow['accountcode'], '" class="btn btn-info">', $myrow['accountcode'], '</a></td>
						<td class="text">', htmlspecialchars($myrow['accountname'],ENT_QUOTES,'UTF-8',false), '</td>
						<td class="number">', locale_number_format($AccountBalance,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td class="number">', locale_number_format($AccountBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td class="number">', locale_number_format(-$AccountBalance+$AccountBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td class="number">', RelativeChange(-$AccountBalance,-$AccountBalanceLY), '</td>
					</tr>';
			}
		}
	}// End of loop.

	while($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
		if($_POST['Detail']=='Detailed') {
			echo $DrawTotalLine;
		}
		echo '<tr>
				<td colspan="2">', $ParentGroups[$Level], '</td>
				<td class="number">', locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format(-$GroupTotal[$Level]+$GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', RelativeChange(-$GroupTotal[$Level],-$GroupTotalLY[$Level]), '</td>
			</tr>';
		$Level--;
	}
	if($_POST['Detail']=='Detailed') {
		echo $DrawTotalLine;
	}
	echo '<tr>
			<td colspan="2">', $ParentGroups[$Level], '</td>
			<td class="number">', locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
			<td class="number">', locale_number_format($GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
			<td class="number">', locale_number_format(-$GroupTotal[$Level]+$GroupTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
			<td class="number">', RelativeChange(-$GroupTotal[$Level],-$GroupTotalLY[$Level]), '</td>
		</tr>';
	echo $DrawTotalLine;
	echo '<tr>
			<td colspan="2"><h2>', $Sections[$Section], '</h2></td>
			<td class="number"><h2>', locale_number_format($SectionBalance,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td class="number"><h2>', locale_number_format($SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td class="number"><h2>', locale_number_format(-$SectionBalance+$SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td class="number"><h2>', RelativeChange(-$SectionBalance,-$SectionBalanceLY), '</h2></td>
		</tr>';

	$Section = $myrow['sectioninaccounts'];

	if(isset($myrow['sectioninaccounts']) and $_POST['Detail']=='Detailed') {
		echo '<tr>
				<td colspan="6"><h2>', $Sections[$myrow['sectioninaccounts']], '</h2></td>
			</tr>';
	}
	echo $DrawTotalLine;
	echo'<tr>
			<td colspan="2"><h2>', _('Check Total'), '</h2></td>
			<td class="number"><h2>', locale_number_format($CheckTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td class="number"><h2>', locale_number_format($CheckTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td class="number"><h2>', locale_number_format(-$CheckTotal+$CheckTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td class="number"><h2>', RelativeChange(-$CheckTotal,-$CheckTotalLY), '</h2></td>
		</tr>';
	echo $DrawTotalLine;
	echo '</tbody>', // See comment at the begin of the table.
		'</table>
		</div></div></div></div>'; // Close div id="Report".
	echo '<br />',
		'<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '">',
		'<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />',
		'<input type="hidden" name="BalancePeriodEnd" value="', $_POST['BalancePeriodEnd'], '" />',
		'<div class="row noprint">', // Form buttons:
			'<div class="col-xs-4"><button onclick="javascript:window.print()" type="button" class="btn btn-warning">', _('Print'), '</button></div>', // "Print" button.
			'<div class="col-xs-4"><button name="SelectADifferentPeriod" type="submit" class="btn btn-info" value="', _('Select A Different Period'), '">', _('Select A Different Period'), '</button> </div>', // "Select A Different Period" button.
			'<div class="col-xs-4"><button onclick="window.location=\'menu_data.php?Application=GL\'" class="btn btn-default" type="button">', _('Return'), '</button></div>', // "Return" button.
		'</div><br />
';
}
echo '</form>';
include('includes/footer.php');
?>
