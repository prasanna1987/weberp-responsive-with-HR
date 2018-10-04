<?php


include('includes/session.php');
$Title = _('Daily Sales Inquiry');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Daily Sales') . '';
echo '</h1></a></div>
	';

echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_POST['MonthToShow'])){
	$_POST['MonthToShow'] = GetPeriod(Date($_SESSION['DefaultDateFormat']));
	$Result = DB_query("SELECT lastdate_in_period FROM periods WHERE periodno='" . $_POST['MonthToShow'] . "'");
	$myrow = DB_fetch_array($Result);
	$EndDateSQL = $myrow['lastdate_in_period'];
}

echo '<div class="row">
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Select Month') . '</label>
		<select tabindex="1" name="MonthToShow" class="form-control">';

$PeriodsResult = DB_query("SELECT periodno, lastdate_in_period FROM periods");

while ($PeriodRow = DB_fetch_array($PeriodsResult)){
	if ($_POST['MonthToShow']==$PeriodRow['periodno']) {
		echo '<option selected="selected" value="' . $PeriodRow['periodno'] . '">' . MonthAndYearFromSQLDate($PeriodRow['lastdate_in_period']) . '</option>';
		$EndDateSQL = $PeriodRow['lastdate_in_period'];
	} else {
		echo '<option value="' . $PeriodRow['periodno'] . '">' . MonthAndYearFromSQLDate($PeriodRow['lastdate_in_period']) . '</option>';
	}
}
echo '</select></div></div>

	
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Salesperson') . '</label>';

if($_SESSION['SalesmanLogin'] != '') {
	echo ' - ';
	echo $_SESSION['UsersRealName'];
	
}else{
	echo '<select tabindex="2" name="Salesperson" class="form-control">';

	$SalespeopleResult = DB_query("SELECT salesmancode, salesmanname FROM salesman");
	if (!isset($_POST['Salesperson'])){
		$_POST['Salesperson'] = 'All';
		echo '<option selected="selected" value="All">' . _('All') . '</option>';
} else {
	echo '<option value="All">' . _('All') . '</option>';
}
while ($SalespersonRow = DB_fetch_array($SalespeopleResult)){

	if ($_POST['Salesperson']==$SalespersonRow['salesmancode']) {
		echo '<option selected="selected" value="' . $SalespersonRow['salesmancode'] . '">' . $SalespersonRow['salesmanname'] . '</option>';
	} else {
		echo '<option value="' . $SalespersonRow['salesmancode'] . '">' . $SalespersonRow['salesmanname'] . '</option>';
	}
}
echo '</select></div></div>';
}
echo '
	
		<div class="col-xs-4">
        <div class="form-group">
		<input tabindex="4" type="submit" name="ShowResults" class="btn btn-info" value="' . _('Show') . '" />
	</div>
    </div>
	</div>
	</form>
	 </div>
	</div>
	<br />';
/*Now get and display the sales data returned */
if (mb_strpos($EndDateSQL,'/')) {
	$Date_Array = explode('/',$EndDateSQL);
} elseif (mb_strpos ($EndDateSQL,'-')) {
	$Date_Array = explode('-',$EndDateSQL);
} elseif (mb_strpos ($EndDateSQL,'.')) {
	$Date_Array = explode('.',$EndDateSQL);
}

if (mb_strlen($Date_Array[2])>4) {
	$Date_Array[2]= mb_substr($Date_Array[2],0,2);
}

$StartDateSQL =  date('Y-m-d', mktime(0,0,0, (int)$Date_Array[1],1,(int)$Date_Array[0]));

$sql = "SELECT 	trandate,
				SUM(price*(1-discountpercent)* (-qty)) as salesvalue,
				SUM(CASE WHEN mbflag='A' THEN 0 ELSE (standardcost * -qty) END) as cost
			FROM stockmoves
			INNER JOIN stockmaster
			ON stockmoves.stockid=stockmaster.stockid
			INNER JOIN custbranch
			ON stockmoves.debtorno=custbranch.debtorno
				AND stockmoves.branchcode=custbranch.branchcode
			WHERE (stockmoves.type=10 or stockmoves.type=11)
			AND trandate>='" . $StartDateSQL . "'
			AND trandate<='" . $EndDateSQL . "'";

if ($_SESSION['SalesmanLogin'] != '') {
	$SQL .= " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
}elseif ($_POST['Salesperson']!='All') {
	$sql .= " AND custbranch.salesman='" . $_POST['Salesperson'] . "'";
}

$sql .= " GROUP BY stockmoves.trandate ORDER BY stockmoves.trandate";
$ErrMsg = _('The sales data could not be retrieved because') . ' - ' . DB_error_msg();
$SalesResult = DB_query($sql,$ErrMsg);

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
			<table id="general-table" class="table table-bordered">
	<tr>
		<th style="width: 14%">' . _('Sunday') . '</th>
		<th style="width: 14%">' . _('Monday') . '</th>
		<th style="width: 14%">' . _('Tuesday') . '</th>
		<th style="width: 14%">' . _('Wednesday') . '</th>
		<th style="width: 14%">' . _('Thursday') . '</th>
		<th style="width: 14%">' . _('Friday') . '</th>
		<th style="width: 14%">' . _('Saturday') . '</th>
	</tr>';

$CumulativeTotalSales = 0;
$CumulativeTotalCost = 0;
$BilledDays = 0;
$DaySalesArray = array();
while ($DaySalesRow=DB_fetch_array($SalesResult)) {

	if ($DaySalesRow['salesvalue'] > 0) {
		$DaySalesArray[DayOfMonthFromSQLDate($DaySalesRow['trandate'])]['Sales'] = $DaySalesRow['salesvalue'];
	} else {
		$DaySalesArray[DayOfMonthFromSQLDate($DaySalesRow['trandate'])]['Sales'] = 0;
	}
	if ($DaySalesRow['salesvalue'] > 0 ) {
		$DaySalesArray[DayOfMonthFromSQLDate($DaySalesRow['trandate'])]['GPPercent'] = ($DaySalesRow['salesvalue']-$DaySalesRow['cost'])/$DaySalesRow['salesvalue'];
	} else {
		$DaySalesArray[DayOfMonthFromSQLDate($DaySalesRow['trandate'])]['GPPercent'] = 0;
	}
	$BilledDays++;
	$CumulativeTotalSales += $DaySalesRow['salesvalue'];
	$CumulativeTotalCost += $DaySalesRow['cost'];
}
//end of while loop
echo '<tr>';
$ColumnCounter = DayOfWeekFromSQLDate($StartDateSQL);
for ($i=0;$i<$ColumnCounter;$i++){
	echo '<td></td>';
}
$DayNumber = 1;
/*Set up day number headings*/
for ($i=$ColumnCounter;$i<=6;$i++){
	   echo '<th>' . $DayNumber . '</th>';
	   $DayNumber++;
}
echo '</tr><tr>';
for ($i=0;$i<$ColumnCounter;$i++){
	echo '<td></td>';
}

$LastDayOfMonth = DayOfMonthFromSQLDate($EndDateSQL);
for ($i=1;$i<=$LastDayOfMonth;$i++){
		$ColumnCounter++;
		if(isset($DaySalesArray[$i])) {
			echo '<td class="number" style="outline: 1px solid gray;">' . locale_number_format($DaySalesArray[$i]['Sales'],0) . '<br />' .  locale_number_format($DaySalesArray[$i]['GPPercent']*100,1) . '%</td>';
		} else {
			echo '<td class="number" style="outline: 1px solid gray;">' . locale_number_format(0,0) . '<br />' .  locale_number_format(0,1) . '%</td>';
		}
		if ($ColumnCounter==7){
			echo '</tr><tr>';
						for ($j=1;$j<=7;$j++){
								   echo '<th>' . $DayNumber. '</th>';
							$DayNumber++;
							if($DayNumber>$LastDayOfMonth){
								   break;
							}
						}
						echo '</tr><tr>';
			$ColumnCounter=0;
		}


}
if ($ColumnCounter!=0) {
	echo '</tr><tr>';
}

if ($CumulativeTotalSales !=0){
	$AverageGPPercent = ($CumulativeTotalSales - $CumulativeTotalCost)*100/$CumulativeTotalSales;
	$AverageDailySales = $CumulativeTotalSales/$BilledDays;
} else {
	$AverageGPPercent = 0;
	$AverageDailySales = 0;
}

echo '<th>' . _('Total Sales for month') . '</th><td> ' . locale_number_format($CumulativeTotalSales,0) . '</td><th> ' . _('Gross Profit') . '</th><td> ' . locale_number_format($AverageGPPercent,1) . '% </td><th>' . _('Average Daily Sales') . '</th><td> ' . locale_number_format($AverageDailySales,0) . '</td></tr>';

echo '</table></div></div></div>';

include('includes/footer.php');
?>
