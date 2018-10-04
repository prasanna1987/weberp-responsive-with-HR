<?php


include ('includes/session.php');

$Title = _('Periods Inquiry');

include('includes/header.php');

$SQL = "SELECT periodno ,
		lastdate_in_period
		FROM periods
		ORDER BY periodno";

$ErrMsg =  _('No periods were returned by the SQL because');
$PeriodsResult = DB_query($SQL,$ErrMsg);

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' '
		. $Title . '</h1></a></div>';

/*show a table of the orders returned by the SQL */

$NumberOfPeriods = DB_num_rows($PeriodsResult);
$PeriodsInTable = round($NumberOfPeriods/3,0);

$TableHeader = '<thead><tr><th>' . _('Period Number') . '</th>
					<th>' . _('Last Day') . '</th>
				</tr></thead>';

echo '
<div class="table-responsive">
<table id="general-table" class="table table-bordered"><tr>';

for ($i=0;$i<3;$i++) {
	echo '<td valign="top">';
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo $TableHeader;
	$j=0;

	while ($myrow=DB_fetch_array($PeriodsResult)){
		echo '<tr class="striped_row">
				<td>' . $myrow['periodno'] . '</td>
			  <td>' . ConvertSQLDate($myrow['lastdate_in_period']) . '</td>
			</tr>';
		$j++;
		if ($j==$PeriodsInTable){
			break;
		}
	}
	echo '</table></div></div></div>';
	echo '</td>';
}

echo '</tr></table></div><br />';
//end of while loop

include('includes/footer.php');
?>
