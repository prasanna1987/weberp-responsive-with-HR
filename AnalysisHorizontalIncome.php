<?php
/* Shows the horizontal analysis of the statement of comprehensive income. */

// BEGIN: Functions division ---------------------------------------------------
function RelativeChange($selected_period, $previous_period) {
	// Calculates the relative change between selected and previous periods. Uses percent with locale number format.
	if($previous_period<>0) {
		return locale_number_format(($selected_period-$previous_period)*100/$previous_period, $_SESSION['CompanyRecord']['decimalplaces']) . '%';
	} else {
		return _('N/A');
	}
}
// END: Functions division -----------------------------------------------------

// BEGIN: Procedure division ---------------------------------------------------
include ('includes/session.php');
$Title = _('Horizontal Analysis of Statement of Comprehensive Income');
$ViewTopic= 'GeneralLedger';
$BookMark = 'AnalysisHorizontalIncome';

include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.php');// This loads the $Sections variable.

if(isset($_POST['FromPeriod']) and ($_POST['FromPeriod'] > $_POST['ToPeriod'])) {
	echo  prnMsg(_('The selected from period is actually after the to period') . '! ' . _('Please reselect the reporting period'),'error');
	$_POST['SelectADifferentPeriod']='Select A Different Period';
}

if ($_POST['Period'] != '') {
	$_POST['FromPeriod'] = ReportPeriod($_POST['Period'], 'From');
	$_POST['ToPeriod'] = ReportPeriod($_POST['Period'], 'To');
}

include('includes/header.php');
if((!isset($_POST['FromPeriod']) AND !isset($_POST['ToPeriod'])) OR isset($_POST['SelectADifferentPeriod'])) {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
		_('Horizontal Analysis of Statement of Comprehensive Income'), '</h1></a></div>';// Page title.

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
<div class="form-group"> <label class="col-md-8 control-label">', _('From Period'), '</label>
				<select name="FromPeriod" class="form-control">';

	if(Date('m') > $_SESSION['YearEnd']) {
		/*Dates in SQL format */
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')));
	} else {
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')-1));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')-1));
	}

	$sql = "SELECT periodno, lastdate_in_period
			FROM periods
			ORDER BY periodno DESC";
	$Periods = DB_query($sql);

	while($myrow=DB_fetch_array($Periods)) {
		if(isset($_POST['FromPeriod']) AND $_POST['FromPeriod']!='') {
			if( $_POST['FromPeriod']== $myrow['periodno']) {
				echo '<option selected="selected" value="' . $myrow['periodno'] . '">' .MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		} else {
			if($myrow['lastdate_in_period']==$DefaultFromDate) {
				echo '<option selected="selected" value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		}
	}

	echo	'</select></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('To Period'), '</label>
			<select required="required" name="ToPeriod" class="form-control">';

	if(!isset($_POST['ToPeriod']) OR $_POST['ToPeriod']=='') {
		$LastDate = date('Y-m-d',mktime(0,0,0,Date('m')+1,0,Date('Y')));
		$sql = "SELECT periodno FROM periods where lastdate_in_period = '" . $LastDate . "'";
		$MaxPrd = DB_query($sql);
		$MaxPrdrow = DB_fetch_row($MaxPrd);
		$DefaultToPeriod = (int) ($MaxPrdrow[0]);
	} else {
		$DefaultToPeriod = $_POST['ToPeriod'];
	}

	$RetResult = DB_data_seek($Periods,0);

	while($myrow=DB_fetch_array($Periods)) {
		echo '<option';
		if($myrow['periodno']==$DefaultToPeriod) {
			echo ' selected="selected"';
		}
		echo ' value="', $myrow['periodno'], '">', MonthAndYearFromSQLDate($myrow['lastdate_in_period']), '</option>';
	}

	echo		'</select></div>
			</div></div>
			<div class="row">
				<div class="col-xs-4">

					<h3 class="text-danger"><strong>', _('OR'), '</strong></h3>
				</div>
			</div>';

	if (!isset($_POST['Period'])) {
		$_POST['Period'] = '';
	}

	echo '<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Select Period'), '</label>
			', ReportPeriodList($_POST['Period'], array('l', 't')), '</div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Report Type'), '</label>
				<select name="Detail" class="form-control" required="required" title="', _('Selecting Summary will show on the totals at the account group level'), '" >
					<option value="Summary">', _('Summary'), '</option>
					<option selected="selected" value="Detailed">', _('All Accounts'), '</option>
					</select></div>
			</div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Include 0 balance accounts?'), '</label>
				<div class="checkbox"><label><input name="ShowZeroBalances" title="', _('Check this box to display all accounts including those accounts with no balance'), '" type="checkbox" /></label></div>
			</div>
		</div></div>
		<br />', // Form buttons:
		'<div class="row noprint">',
			'<div class="col-xs-4"><button name="ShowPL" type="submit" class="btn btn-info" value="', _('Show on Screen (HTML)'), '"> ', _('Show'), '</button></div> ', // "Show on Screen (HTML)" button.
			'<div class="col-xs-4"><button onclick="window.location=\'menu_data.php?Application=GL\'" class="btn btn-default" type="button">', _('Return'), '</button></div>', // "Return" button.
		'</div><br />
';

	// Now do the posting while the user is thinking about the period to select:
	include ('includes/GLPostings.inc');

} else {
	$NumberOfMonths = $_POST['ToPeriod'] - $_POST['FromPeriod'] + 1;
	if($NumberOfMonths >12) {
		
		echo prnMsg(_('A period up to 12 months in duration can be specified') . ' - ' . _('nERP automatically shows a comparative for the same period from the previous year') . ' - ' . _('nERP cannot do this if a period of more than 12 months is specified') . '. ' . _('Please select an alternative period range'),'error');
		include('includes/footer.php');
		exit;
	}

	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $_POST['ToPeriod'] . "'";
	$PrdResult = DB_query($sql);
	$myrow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($myrow[0]);

	// Page title as IAS 1, numerals 10 and 51:
	include_once('includes/CurrenciesArray.php');// Array to retrieve currency name.
	echo '<div id="Report">', // Division to identify the report block.
		'<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
		_('Horizontal Analysis of Statement of Comprehensive Income'), '<br /><small>', // Page title, reporting statement.
		stripslashes($_SESSION['CompanyRecord']['coyname']), '<br />', // Page title, reporting entity.
		_('For'), ' ', $NumberOfMonths, ' ', _('months to'), ' ', $PeriodToDate, '<br />', // Page title, reporting period.
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
					_('Actual change signs: a positive number indicates a variation that increases the net profit; a negative number indicates a variation that decreases the net profit.'), '<br />',
					_('Relative change signs: a positive number indicates an increase in the amount of that account; a negative number indicates a decrease in the amount of that account.'), '<br />',
				'</td>
			</tr>
	
		<tbody>';// thead and tfoot used in conjunction with tbody enable scrolling of the table body independently of the header and footer. Also, when printing a large table that spans multiple pages, these elements can enable the table header to be printed at the top of each page.

	$SQL = "SELECT accountgroups.sectioninaccounts,
					accountgroups.parentgroupname,
					accountgroups.groupname,
					chartdetails.accountcode,
					chartmaster.accountname,
					SUM(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
					SUM(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
					SUM(CASE WHEN chartdetails.period='" . ($_POST['FromPeriod'] - 12) . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwdly,
					SUM(CASE WHEN chartdetails.period='" . ($_POST['ToPeriod']-12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwdly
			FROM chartmaster
				INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
				INNER JOIN chartdetails	ON chartmaster.accountcode= chartdetails.accountcode
				INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
			WHERE accountgroups.pandl=1
			GROUP BY accountgroups.sectioninaccounts,
					accountgroups.parentgroupname,
					accountgroups.groupname,
					chartdetails.accountcode,
					chartmaster.accountname
			ORDER BY accountgroups.sectioninaccounts,
					accountgroups.sequenceintb,
					accountgroups.groupname,
					chartdetails.accountcode";
	$AccountsResult = DB_query($SQL,_('No general ledger accounts were returned by the SQL because'),_('The SQL that failed was'));

	$PeriodTotal=0;
	$PeriodTotalLY=0;

	$Section='';
	$SectionTotal=0;
	$SectionTotalLY=0;

	$ActGrp='';
	$GrpTotal=array(0);
	$GrpTotalLY=array(0);
	$Level=0;
	$ParentGroups=array();
	$ParentGroups[$Level]='';

	$DrawTotalLine = '<tr>
		<td colspan="2">&nbsp;</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>';

	while($myrow=DB_fetch_array($AccountsResult)) {
		if($myrow['groupname']!= $ActGrp) {
			if($myrow['parentgroupname']!=$ActGrp AND $ActGrp!='') {
				while($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
					if($_POST['Detail']=='Detailed') {
						echo $DrawTotalLine;
						$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' *' . _('total');
					} else {
						$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
					}
					echo '<tr>
							<td class="text" colspan="2">', $ActGrpLabel, '</td>
							<td>', locale_number_format(-$GrpTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$GrpTotal[$Level]+$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', RelativeChange(-$GrpTotal[$Level],-$GrpTotalLY[$Level]), '</td>
						</tr>';
					$GrpTotal[$Level]=0;
					$GrpTotalLY[$Level]=0;
					$ParentGroups[$Level]='';
					$Level--;
				}// End while.

				//still need to print out the old group totals

				if($_POST['Detail']=='Detailed') {
					echo $DrawTotalLine;
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' ' . _('total');
				} else {
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
				}

// --->
				if($Section ==1) {// Income
				echo '<tr>
						<td class="text" colspan="2">', $ActGrpLabel, '</td>
						<td>', locale_number_format(-$GrpTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td>', locale_number_format(-$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td>', locale_number_format(-$GrpTotal[$Level]+$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td>', RelativeChange(-$GrpTotal[$Level],-$GrpTotalLY[$Level]), '</td>
					</tr>';
				} else {// Costs
// <---
				echo '<tr>
						<td class="text" colspan="2">', $ActGrpLabel, '</td>
						<td>', locale_number_format(-$GrpTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td>', locale_number_format(-$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td>', locale_number_format(-$GrpTotal[$Level]+$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
						<td>', RelativeChange(-$GrpTotal[$Level],-$GrpTotalLY[$Level]), '</td>
					</tr>';
// --->
				}
// <---
				$GrpTotalLY[$Level]=0;
				$GrpTotal[$Level]=0;
				$ParentGroups[$Level]='';
			}
		}

		if($myrow['sectioninaccounts']!= $Section) {

			if($SectionTotal+$SectionTotalLY !=0) {

				if($Section==1) {// Income.
					echo $DrawTotalLine;
					echo '<tr>
							<td class="text" colspan="2"><h2>', $Sections[$Section], '</h2></td>
							<td><h2>', locale_number_format(-$SectionTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-$SectionTotal+$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', RelativeChange(-$SectionTotal,-$SectionTotalLY), '</h2></td>
						</tr>';
					$GPIncome = $SectionTotal;
					$GPIncomeLY = $SectionTotalLY;
				} else {
					echo '<tr>
							<td class="text" colspan="2"><h2>', $Sections[$Section], '</h2></td>
							<td><h2>', locale_number_format(-$SectionTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-$SectionTotal+$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', RelativeChange(-$SectionTotal,-$SectionTotalLY), '</h2></td>
						</tr>';
				}

				if($Section==2) {// Cost of Sales - need sub total for Gross Profit.
					echo $DrawTotalLine;
					echo '<tr>
							<td class="text" colspan="2"><h2>', _('Gross Profit'), '</h2></td>
							<td><h2>', locale_number_format(-($GPIncome+$SectionTotal),$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-($GPIncomeLY+$SectionTotalLY),$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-($GPIncome+$SectionTotal)+($GPIncomeLY+$SectionTotalLY),$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', RelativeChange(-($GPIncome+$SectionTotal),-($GPIncomeLY+$SectionTotalLY)), '</h2></td>
						</tr>';
				}

				if(($Section!=1) AND ($Section!=2)) {
					echo $DrawTotalLine;
					echo '<tr>
							<td class="text" colspan="2"><h2>', _('Earnings after'), ' ', $Sections[$Section], '</h2></td>
							<td><h2>', locale_number_format(-$PeriodTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-$PeriodTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', locale_number_format(-$PeriodTotal+$PeriodTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
							<td><h2>', RelativeChange(-$PeriodTotal,-$PeriodTotalLY), '</h2></td>
						</tr>';
					echo $DrawTotalLine;
				}
			}

			$Section = $myrow['sectioninaccounts'];
			$SectionTotal=0;
			$SectionTotalLY=0;

			if($_POST['Detail']=='Detailed') {
				echo '<tr>
						<td colspan="6"><h2>', $Sections[$myrow['sectioninaccounts']], '</h2></td>
					</tr>';
			}
		}

		if($myrow['groupname']!= $ActGrp) {
			if($myrow['parentgroupname']==$ActGrp AND $ActGrp !='') {// Adding another level of nesting
				$Level++;
			}
			$ActGrp = $myrow['groupname'];
			$ParentGroups[$Level] = $myrow['groupname'];
			if($_POST['Detail']=='Detailed') {
				echo '<tr>
						<td colspan="6"><h2>', $myrow['groupname'], '</h2></td>
					</tr>';
			}
		}

		// Set totals for account, groups, section and period:
		$AccountTotal = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
		$AccountTotalLY = $myrow['lastprdcfwdly'] - $myrow['firstprdbfwdly'];
		for ($i=0;$i<=$Level;$i++) {
			if(!isset($GrpTotalLY[$i])) {$GrpTotalLY[$i]=0;}
			$GrpTotalLY[$i] += $AccountTotalLY;
			if(!isset($GrpTotal[$i])) {$GrpTotal[$i]=0;}
			$GrpTotal[$i] += $AccountTotal;
		}
		$SectionTotal += $AccountTotal;
		$SectionTotalLY += $AccountTotalLY;
		$PeriodTotal += $AccountTotal;
		$PeriodTotalLY += $AccountTotalLY;

		if($_POST['Detail']=='Detailed') {
			if(isset($_POST['ShowZeroBalances']) OR (!isset($_POST['ShowZeroBalances']) AND ($AccountTotal <> 0 OR $AccountTotalLY <> 0))) {
				echo '<tr class="striped_row">
							<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?FromPeriod=', urlencode($_POST['FromPeriod']), '&amp;ToPeriod=', urlencode($_POST['ToPeriod']), '&amp;Account=', urlencode($myrow['accountcode']), '&amp;Show=Yes" class="btn btn-info">', $myrow['accountcode'], '</a></td>';
// --->
				if($Section ==1) {
					echo '	<td class="text">', htmlspecialchars($myrow['accountname'],ENT_QUOTES,'UTF-8',false), '</td>
							<td>', locale_number_format(-$AccountTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$AccountTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$AccountTotal+$AccountTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', RelativeChange(-$AccountTotal,-$AccountTotalLY), '</td>
						</tr>';
				} else {
// <---
					echo '	<td class="text">', htmlspecialchars($myrow['accountname'],ENT_QUOTES,'UTF-8',false), '</td>
							<td>', locale_number_format(-$AccountTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$AccountTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$AccountTotal+$AccountTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', RelativeChange(-$AccountTotal,-$AccountTotalLY), '</td>
						</tr>';
				}
			}
		}
	}// End of loop.

	if($myrow['groupname']!= $ActGrp) {
		if($myrow['parentgroupname']!=$ActGrp AND $ActGrp!='') {
			while($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
				if($_POST['Detail']=='Detailed') {
					echo $DrawTotalLine;
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' ' . _('total');
				} else {
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
				}
// --->
				if($Section ==1) {// Income.
					echo '<tr>
							<td colspan="2"><h3>', $ActGrpLabel, '</h3></td>
							<td>', locale_number_format(-$GrpTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$GrpTotal[$Level]+$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', RelativeChange(-$GrpTotal[$Level],-$GrpTotalLY[$Level]), '</td>
						</tr>';
				} else {// Costs.
// <---
					echo '<tr>
							<td colspan="2"><h3>', $ActGrpLabel, '</h3></td>
							<td>', locale_number_format(-$GrpTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', locale_number_format(-$GrpTotal[$Level]+$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
							<td>', RelativeChange(-$GrpTotal[$Level],-$GrpTotalLY[$Level]), '</td>
						</tr>';
				}
				$GrpTotal[$Level]=0;
				$GrpTotalLY[$Level]=0;
				$ParentGroups[$Level]='';
				$Level--;
			}// End while.
			//still need to print out the old group totals
			if($_POST['Detail']=='Detailed') {
				echo $DrawTotalLine;
				$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' ' . _('total');
			} else {
				$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
			}
			echo '<tr>
					<td colspan="2"><h3>', $ActGrpLabel, '</h3></td>
					<td>', locale_number_format(-$GrpTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
					<td>', locale_number_format(-$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
					<td>', locale_number_format(-$GrpTotal[$Level]+$GrpTotalLY[$Level],$_SESSION['CompanyRecord']['decimalplaces']), '</td>
					<td>', RelativeChange(-$GrpTotal[$Level],-$GrpTotalLY[$Level]), '</td>
				</tr>';
			$GrpTotal[$Level]=0;
			$GrpTotalLY[$Level]=0;
			$ParentGroups[$Level]='';
		}
	}

	if($myrow['sectioninaccounts']!= $Section) {

		if($Section==1) {// Income.
			echo $DrawTotalLine,
				'<tr>
					<td colspan="2"><h2>', $Sections[$Section], '</h2></td>
					<td><h2>', locale_number_format(-$SectionTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', locale_number_format(-$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', locale_number_format(-$SectionTotal+$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', RelativeChange(-$SectionTotal,-$SectionTotalLY), '</h2></td>
				</tr>';
			$GPIncome = $SectionTotal;
			$GPIncomeLY = $SectionTotalLY;
		} else {
			echo $DrawTotalLine,
				'<tr>
					<td colspan="2"><h2>', $Sections[$Section], '</h2></td>
					<td><h2>', locale_number_format(-$SectionTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', locale_number_format(-$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', locale_number_format(-$SectionTotal+$SectionTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', RelativeChange(-$SectionTotal,-$SectionTotalLY), '</h2></td>
				</tr>';
		}
		if($Section==2) {// Cost of Sales - need sub total for Gross Profit.
			echo $DrawTotalLine,
				'<tr>
					<td colspan="2"><h2>', _('Gross Profit'), '</h2></td>
					<td><h2>', locale_number_format(-($GPIncome+$SectionTotal),$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', locale_number_format(-($GPIncomeLY+$SectionTotalLY),$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', locale_number_format(-($GPIncome+$SectionTotal)+($GPIncomeLY+$SectionTotalLY),$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
					<td><h2>', RelativeChange(-($GPIncome+$SectionTotal),-($GPIncomeLY+$SectionTotalLY)), '</h2></td>
				</tr>';
		}
		$Section = $myrow['sectioninaccounts'];
		$SectionTotal=0;
		$SectionTotalLY=0;

		if($_POST['Detail']=='Detailed' and isset($Sections[$myrow['sectioninaccounts']])) {
			echo '<tr>
					<td colspan="6"><h2>', $Sections[$myrow['sectioninaccounts']], '</h2></td>
				</tr>';
		}
	}

	echo $DrawTotalLine;
	echo '<tr>
			<td colspan="2"><h2>', _('Net Profit'), '</h2></td>
			<td><h2>', locale_number_format(-$PeriodTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td><h2>', locale_number_format(-$PeriodTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td><h2>', locale_number_format(-$PeriodTotal+$PeriodTotalLY,$_SESSION['CompanyRecord']['decimalplaces']), '</h2></td>
			<td><h2>', RelativeChange(-$PeriodTotal,-$PeriodTotalLY), '</h2></td>
		</tr>';
	echo $DrawTotalLine;
	echo '</tbody>', // See comment at the begin of the table.
		'</table></div></div></div>
		</div>';// End div id="Report".
	echo '<br />',
		'<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '">',
		'<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />',
		'<input type="hidden" name="FromPeriod" value="', $_POST['FromPeriod'], '" />',
		'<input type="hidden" name="ToPeriod" value="', $_POST['ToPeriod'], '" />',
		'<div class="row noprint">', // Form buttons:
			'<div class="col-xs-4"><button onclick="javascript:window.print()" type="button" class="btn btn-warning">', _('Print'), '</button></div>', // "Print" button.
			'<div class="col-xs-4"><button name="SelectADifferentPeriod" type="submit" class="btn btn-info" value="', _('Select A Different Period'), '">', _('Select A Different Period'), '</button></div> ', // "Select A Different Period" button.
			'<div class="col-xs-4"><button onclick="window.location=\'menu_data.php?Application=GL\'" class="btn btn-default" type="button">', _('Return'), '</button></div>', // "Return" button.
		'</div><br />
';
}
echo '</form>';
include('includes/footer.php');
?>
