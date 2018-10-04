<?php
/* Shows a graph of GL account transactions */
/* By Paul Becker */

include ('includes/session.php');
include ('includes/phplot/phplot.php');
$Title = _('GL Account Graph');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLAccountGraph';
include ('includes/header.php');

$SelectADifferentPeriod = '';

if (isset($_POST['Account'])) {
	$SelectedAccount = $_POST['Account'];
} elseif (isset($_GET['Account'])) {
	$SelectedAccount = $_GET['Account'];
}

if ($_POST['Period'] != '') {
	$_POST['FromPeriod'] = ReportPeriod($_POST['Period'], 'From');
	$_POST['ToPeriod'] = ReportPeriod($_POST['Period'], 'To');
}

if (isset($_POST['FromPeriod']) and isset($_POST['ToPeriod'])) {

	if ($_POST['FromPeriod'] > $_POST['ToPeriod']) {
		echo prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'), 'error');
		$SelectADifferentPeriod = _('Select A Different Period');
	}

}

if ((!isset($_POST['FromPeriod']) or !isset($_POST['ToPeriod'])) or $SelectADifferentPeriod == _('Select A Different Period')) {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '
		</h1></a></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form method="post" action="' . htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	

	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select GL Account') . '</label>
				<select name="Account" class="form-control">';

	$SQL = "SELECT chartmaster.accountcode,
				bankaccounts.accountcode AS bankact,
				bankaccounts.currcode,
				chartmaster.accountname
			FROM chartmaster LEFT JOIN bankaccounts
			ON chartmaster.accountcode=bankaccounts.accountcode
			INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" . $_SESSION['UserID'] . "' AND glaccountusers.canview=1
			ORDER BY chartmaster.accountcode";
	$Account = DB_query($SQL);
	while ($MyRow = DB_fetch_array($Account)) {
		if ($MyRow['accountcode'] == $SelectedAccount) {
			if (!is_null($MyRow['bankact'])) {
				$BankAccount = true;
			}
			echo '<option selected="selected" value="' . $MyRow['accountcode'] . '">' . $MyRow['accountcode'] . ' ' . htmlspecialchars($MyRow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
		} else {
			echo '<option value="' . $MyRow['accountcode'] . '">' . $MyRow['accountcode'] . ' ' . htmlspecialchars($MyRow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
		}
	}
	echo '</select>
			</div>
		</div>';

	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Graph Type') . '</label>
			<select name="GraphType" class="form-control">
					<option value="bars">' . _('Bar Graph') . '</option>
					<option value="stackedbars">' . _('Stacked Bar Graph') . '</option>
					<option value="lines">' . _('Line Graph') . '</option>
					<option value="linepoints">' . _('Line Point Graph') . '</option>
					<option value="area">' . _('Area Graph') . '</option>
					<option value="points">' . _('Points Graph') . '</option>
					<option value="pie">' . _('Pie Graph') . '</option>
					<option value="thinbarline">' . _('Thin Bar Line Graph') . '</option>
					<option value="squared">' . _('Squared Graph') . '</option>
					<option value="stackedarea">' . _('Stacked Area Graph') . '</option>
				</select>
			</div>
		</div>';

	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Invert Graph'), '</label>
			<input type="checkbox" name="InvertGraph" /></div>
		</div></div>';

	echo '<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Period From') . '</label>
			<select name="FromPeriod" class="form-control">';

	if (Date('m') > $_SESSION['YearEnd']) {
		/*Dates in SQL format */
		$DefaultFromDate = Date('Y-m-d', Mktime(0, 0, 0, $_SESSION['YearEnd'] + 2, 0, Date('Y')));
	} else {
		$DefaultFromDate = Date('Y-m-d', Mktime(0, 0, 0, $_SESSION['YearEnd'] + 2, 0, Date('Y') - 1));
	}
	$SQL = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno";
	$Periods = DB_query($SQL);

	while ($MyRow = DB_fetch_array($Periods)) {
		if (isset($_POST['FromPeriod']) and $_POST['FromPeriod'] != '') {
			if ($_POST['FromPeriod'] == $MyRow['periodno']) {
				echo '<option selected="selected" value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			}
		} else {
			if ($MyRow['lastdate_in_period'] == $DefaultFromDate) {
				echo '<option selected="selected" value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			}
		}
	}

	echo '</select>
			</div>
		</div>';
	if (!isset($_POST['ToPeriod']) or $_POST['ToPeriod'] == '') {
		$DefaultToPeriod = GetPeriod(DateAdd(ConvertSQLDate($DefaultFromDate), 'm', 11));
	} else {
		$DefaultToPeriod = $_POST['ToPeriod'];
	}

	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Period To') . '</label>
			<select name="ToPeriod" class="form-control">';

	$RetResult = DB_data_seek($Periods, 0);

	while ($MyRow = DB_fetch_array($Periods)) {

		if ($MyRow['periodno'] == $DefaultToPeriod) {
			echo '<option selected="selected" value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
		} else {
			echo '<option value ="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
		}
	}
	echo '</select>
			</div>
		</div>';

	if (!isset($_POST['Period'])) {
		$_POST['Period'] = '';
	}



	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Predefined') . '</label>
			<strong>' . ReportPeriodList($_POST['Period']) . '</strong></div>
		</div>';

	echo '</div>
			<div class="row">
			<div class="col-xs-4">
<div class="form-group">
				<input type="submit" class="btn btn-info" name="ShowGraph" value="' . _('Show Account Graph') . '" />
			</div>
			</div>
			</div>
        </form>
		</div>
		</div>
		';
	include ('includes/footer.php');
} else {

	$Graph = new PHPlot(950, 450);
	$SelectClause = '';
	$WhereClause = '';
	$GraphTitle = '';

	$GraphTitle = _('GL Account Graph - Actual vs. Budget') . "\n\r";
	//$GraphTitle .= $SelectedAccount . " - " . $SelectedAccountName . "\n\r";
	$SQL = "SELECT YEAR(`lastdate_in_period`) AS year, MONTHNAME(`lastdate_in_period`) AS month
			  FROM `periods`
			 WHERE `periodno`='" . $_POST['FromPeriod'] . "' OR periodno='" . $_POST['ToPeriod'] . "'";

	$Result = DB_query($SQL);

	$FromPeriod = DB_fetch_array($Result);
	$Starting = $FromPeriod['month'] . ' ' . $FromPeriod['year'];

	$ToPeriod = DB_fetch_array($Result);
	$Ending = $ToPeriod['month'] . ' ' . $ToPeriod['year'];

	$GraphTitle.= ' ' . _('From Period') . ' ' . $Starting . ' ' . _('to') . ' ' . $Ending . "\n\r";

	$WhereClause = "WHERE " . $WhereClause . " periods.periodno>='" . $_POST['FromPeriod'] . "' AND periods.periodno <= '" . $_POST['ToPeriod'] . "'";

	$SQL = "SELECT periods.periodno,
				periods.lastdate_in_period,
				chartmaster.group_ AS group_,
				chartdetails.budget AS budget,
				(CASE WHEN chartdetails.actual=0 THEN 0 ELSE chartdetails.actual END) AS actual
		FROM periods
		INNER JOIN chartdetails ON periods.periodno=chartdetails.period
		INNER JOIN chartmaster ON chartdetails.accountcode=chartmaster.accountcode " . $WhereClause . "
		AND chartdetails.accountcode = '" . $SelectedAccount . "'
		GROUP BY periods.periodno,
			periods.lastdate_in_period
		ORDER BY periods.periodno";

	$Graph->SetTitle($GraphTitle);
	$Graph->SetTitleColor('blue');
	$Graph->SetOutputFile('companies/' . $_SESSION['DatabaseName'] . '/reports/glaccountgraph.png');
	$Graph->SetXTitle(_('Month'));

	$Graph->SetXTickPos('none');
	$Graph->SetXTickLabelPos('none');
	$Graph->SetXLabelAngle(90);
	$Graph->SetBackgroundColor('white');
	$Graph->SetTitleColor('blue');
	$Graph->SetFileFormat('png');
	$Graph->SetPlotType($_POST['GraphType']);
	$Graph->SetIsInline('1');
	$Graph->SetShading(5);
	$Graph->SetDrawYGrid(true);
	$Graph->SetDataType('text-data');
	$Graph->SetNumberFormat($DecimalPoint, $ThousandsSeparator);
	$Graph->SetPrecisionY($_SESSION['CompanyRecord']['decimalplaces']);

	$SalesResult = DB_query($SQL);
	if (DB_error_no() != 0) {

		echo  prnMsg(_('The GL Account graph data for the selected criteria could not be retrieved because') . ' - ' . DB_error_msg(), 'error');
		include ('includes/footer.php');
		exit;
	}
	if (DB_num_rows($SalesResult) == 0) {
		echo   prnMsg(_('There is no GL Account data for the criteria entered to graph'), 'info');
		include ('includes/footer.php');
		exit;
	}

	$GraphArray = array();
	$i = 0;
	while ($MyRow = DB_fetch_array($SalesResult)) {
		if (!isset($_POST['InvertGraph'])) {
			$GraphArray[$i] = array(MonthAndYearFromSQLDate($MyRow['lastdate_in_period']), $MyRow['actual'], $MyRow['budget']);
		} else {
			$GraphArray[$i] = array(MonthAndYearFromSQLDate($MyRow['lastdate_in_period']), -$MyRow['actual'], $MyRow['budget']);
		}
		++$i;
	}

	$Graph->SetDataValues($GraphArray);
	$Graph->SetDataColors(array('grey', 'wheat'), //Data Colors
	array('black') //Border Colors
	);
	$Graph->SetLegend(array(_('Actual'), _('Budget')));
	$Graph->SetYDataLabelPos('plotin');

	//Draw it
	$Graph->DrawGraph();
	echo '<div class="row">
	<div class="col-xs-4">
			<img src="companies/' . $_SESSION['DatabaseName'] . '/reports/glaccountgraph.png" alt="Sales Report Graph" />
			</div>
		  </div>';

	echo '<div class="row"><div class="col-xs-4">
			<a href="', basename(__FILE__), '" class="btn btn-info">', _('Select Different Criteria'), '</a>
		</div></div><br />
';
	include ('includes/footer.php');
}
?>
