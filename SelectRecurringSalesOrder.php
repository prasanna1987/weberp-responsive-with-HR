<?php

include('includes/session.php');
$Title = _('Search Recurring Sales Orders');
/* nERP manual links before header.php */
$ViewTopic= 'SalesOrders';
$BookMark = 'RecurringSalesOrders';

include('includes/header.php');

echo '
<div class="row gutter30">
        <div class="col-xs-12">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

echo '<div class="row">    
               <div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-12 control-label">' . _('Location') . ' </label>
			' . '<select name="StockLocation" class="form-control">';

$sql = "SELECT locations.loccode, locationname FROM locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1";

$resultStkLocs = DB_query($sql);

while ($myrow=DB_fetch_array($resultStkLocs)){
	if (isset($_POST['StockLocation'])){
		if ($myrow['loccode'] == $_POST['StockLocation']){
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		} else {
			echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		}
	} elseif ($myrow['loccode']==$_SESSION['UserStockLocation']){
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	} else {
			echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
}

echo '</select></div>
	</div>
	';

echo '
               <div class="col-xs-4">
               <div class="form-group"> 
			   <br /><input type="submit" name="SearchRecurringOrders" class="btn btn-success" value="' . _('Search') . '" /></div></div></div>';

if (isset($_POST['SearchRecurringOrders'])){

	$SQL = "SELECT recurringsalesorders.recurrorderno,
				debtorsmaster.name,
				currencies.decimalplaces AS currdecimalplaces,
				custbranch.brname,
				recurringsalesorders.customerref,
				recurringsalesorders.orddate,
				recurringsalesorders.deliverto,
				recurringsalesorders.lastrecurrence,
				recurringsalesorders.stopdate,
				recurringsalesorders.frequency,
SUM(recurrsalesorderdetails.unitprice*recurrsalesorderdetails.quantity*(1-recurrsalesorderdetails.discountpercent)) AS ordervalue
			FROM recurringsalesorders INNER JOIN recurrsalesorderdetails
			ON recurringsalesorders.recurrorderno = recurrsalesorderdetails.recurrorderno
			INNER JOIN debtorsmaster
			ON recurringsalesorders.debtorno = debtorsmaster.debtorno
			INNER JOIN custbranch
			ON debtorsmaster.debtorno = custbranch.debtorno
			AND recurringsalesorders.branchcode = custbranch.branchcode
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE recurringsalesorders.fromstkloc = '". $_POST['StockLocation'] . "'
			GROUP BY recurringsalesorders.recurrorderno,
				debtorsmaster.name,
				currencies.decimalplaces,
				custbranch.brname,
				recurringsalesorders.customerref,
				recurringsalesorders.orddate,
				recurringsalesorders.deliverto,
				recurringsalesorders.lastrecurrence,
				recurringsalesorders.stopdate,
				recurringsalesorders.frequency";

	$ErrMsg = _('No recurring orders were returned by the SQL because');
	$SalesOrdersResult = DB_query($SQL,$ErrMsg);

	/*show a table of the orders returned by the SQL */

	echo '<div class="row">
		<div class="table-responsive">
			<table id="general-table" class="table table-bordered">';

	$tableheader = '<thead>
	<tr>
						<th>' . _('Modify') . '</th>
						<th>' . _('Customer') . '</th>
						<th>' . _('Branch') . '</th>
						<th>' . _('Customer Reference') . '</th>
						<th>' . _('Last Recurrence') . '</th>
						<th>' . _('End Date') . '</th>
						<th>' . _('Recurrence') . '</th>
						<th>' . _('Order Total') . '</th>
					</tr></thead>';

	echo $tableheader;

	$j = 1;

	while ($myrow=DB_fetch_array($SalesOrdersResult)) {

		$ModifyPage = $RootPath . '/RecurringSalesOrders.php?ModifyRecurringSalesOrder=' . $myrow['recurrorderno'];
		$FormatedLastRecurrence = ConvertSQLDate($myrow['lastrecurrence']);
		$FormatedStopDate = ConvertSQLDate($myrow['stopdate']);
		$FormatedOrderValue = locale_number_format($myrow['ordervalue'],$myrow['currdecimalplaces']);

		printf('<tr class="striped_row">
				<td><a href="%s" class="btn btn-info">%s</a></td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				</tr>',
				$ModifyPage,
				$myrow['recurrorderno'],
				$myrow['name'],
				$myrow['brname'],
				$myrow['customerref'],
				$FormatedLastRecurrence,
				$FormatedStopDate,
				$myrow['frequency'],
				$FormatedOrderValue);

		$j++;
		If ($j == 12){
			$j=1;
			echo $tableheader;
		}
	//end of page full new headings if
	}
	//end of while loop

	echo '</table></div></div>';
}
echo '
      </form></div></div>';

include('includes/footer.php');
?>