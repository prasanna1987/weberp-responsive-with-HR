<?php


/* Session started in session.php for password checking and authorisation level check
config.php is in turn included in session.php*/
include ('includes/session.php');
$Title = _('Top Items Searching');
include ('includes/header.php');
include ('includes/SQL_CommonFunctions.inc');

//check if input already
if (!(isset($_POST['Search']))) {

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Top Sales Order Search') . '
		</h1></a></div>';
		echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	
	//to view store location
	echo '<div class="row">
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Location') . '  </label>
			
			<select name="Location" class="form-control">';
	$sql = "SELECT locations.loccode,
					locationname
			FROM locations
			INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1 ORDER BY locations.locationname";
	$result = DB_query($sql);
	echo '<option value="All">' . _('All') . '</option>';
	while ($myrow = DB_fetch_array($result)) {
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
	echo '</select></div>
		</div>';
	//to view list of customer
	echo '
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Customer Type') . '</label>
			<select name="Customers" class="form-control">';

	$sql = "SELECT typename,
					typeid
			FROM debtortype
			ORDER BY typename";
	$result = DB_query($sql);
	echo '<option value="All">' . _('All') . '</option>';
	while ($myrow = DB_fetch_array($result)) {
		echo '<option value="' . $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
	}
	echo '</select></div>
		</div>';

	// stock category selection
	$SQL="SELECT categoryid,
					categorydescription
			FROM stockcategory
			ORDER BY categorydescription";
	$result1 = DB_query($SQL);

	echo '<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Stock Category') . ' </label>
			<select name="StockCat" class="form-control">';
	if (!isset($_POST['StockCat'])){
		$_POST['StockCat']='All';
	}
	if ($_POST['StockCat']=='All'){
		echo '<option selected="selected" value="All">' . _('All') . '</option>';
	} else {
		echo '<option value="All">' . _('All') . '</option>';
	}
	while ($myrow1 = DB_fetch_array($result1)) {
		if ($myrow1['categoryid']==$_POST['StockCat']){
			echo '<option selected="selected" value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
		} else {
			echo '<option value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
		}
	}
    echo '</select></div>
        </div>
		</div>
		';

	//view order by list to display
	echo '<div class="row">
			<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Order By ') . ' </label>
			
			<select name="Sequence" class="form-control">
				<option value="totalinvoiced">' . _('Volume') . '</option>
				<option value="valuesales">' . _('Value') . '</option>
				</select></div>
		</div>';
	//View number of days
	echo '<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Number Of Days') . ' </label>
			
			<input class="integer form-control" required="required" pattern="(?!^0*$)(\d+)" title="'._('The input must be positive integer').'" tabindex="3" type="text" name="NumberOfDays" size="8" maxlength="8" value="30" /></div>
		 </div>';
	//Stock in days less than
	echo '<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Days of Stock (QOH + QOO) Available') . ' </label>
			<input class="integer form-control" required="required" pattern="(?!^0*$)(\d+)" title="'._('The input must be positive integer').'" tabindex="4" type="text" name="MaxDaysOfStock" size="8" maxlength="8" value="99999" />
			' . ' </div>
		 </div></div>';
	//view number of NumberOfTopItems items
	echo '<div class="row">
			<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Number Of Top Items') . ' </label><input class="integer form-control" required="required" pattern="(?!^0*$)(\d+)" title="'._('The input must be positive integer').'" tabindex="4" type="text" name="NumberOfTopItems" size="8" maxlength="8" value="100" /></div>
		 </div>
		 
	
	<div class="col-xs-4">
        <div class="form-group"> <br />
		<input tabindex="5" class="btn btn-success" type="submit" name="Search" value="' . _('Submit') . '" />
	</div>
    </div>
	</div>
	</form>
	</div>
	</div>
	';
} else {
	// everything below here to view NumberOfTopItems items sale on selected location
	$FromDate = FormatDateForSQL(DateAdd(Date($_SESSION['DefaultDateFormat']),'d', -filter_number_format($_POST['NumberOfDays'])));

	$SQL = "SELECT 	salesorderdetails.stkcode,
					SUM(salesorderdetails.qtyinvoiced) AS totalinvoiced,
					SUM(salesorderdetails.qtyinvoiced * salesorderdetails.unitprice/currencies.rate ) AS valuesales,
					stockmaster.description,
					stockmaster.units,
					stockmaster.mbflag,
					currencies.rate,
					debtorsmaster.currcode,
					fromstkloc,
					stockmaster.decimalplaces
			FROM 	salesorderdetails, salesorders INNER JOIN locationusers ON locationusers.loccode=salesorders.fromstkloc AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1,
			debtorsmaster,stockmaster, currencies
			WHERE 	salesorderdetails.orderno = salesorders.orderno
					AND salesorderdetails.stkcode = stockmaster.stockid
					AND salesorders.debtorno = debtorsmaster.debtorno
					AND debtorsmaster.currcode = currencies.currabrev
					AND salesorderdetails.actualdispatchdate >= '" . $FromDate . "'";

	if ($_POST['Location'] != 'All') {
		$SQL = $SQL . "	AND salesorders.fromstkloc = '" . $_POST['Location'] . "'";
	}

	if ($_POST['Customers'] != 'All') {
		$SQL = $SQL . "	AND debtorsmaster.typeid = '" . $_POST['Customers'] . "'";
	}

	if ($_POST['StockCat'] != 'All') {
		$SQL = $SQL . "	AND stockmaster.categoryid = '" . $_POST['StockCat'] . "'";
	}

	$SQL = $SQL . "	GROUP BY salesorderdetails.stkcode
					ORDER BY `" . $_POST['Sequence'] . "` DESC
					LIMIT " . filter_number_format($_POST['NumberOfTopItems']);

	$result = DB_query($SQL);

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Top Sales Items List') . '</h1></a></div>';
	echo '<form action="PDFTopItems.php"  method="GET">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<input type="hidden" value="' . $_POST['Location'] . '" name="Location" />
		<input type="hidden" value="' . $_POST['Sequence'] . '" name="Sequence" />
		<input type="hidden" value="' . filter_number_format($_POST['NumberOfDays']) . '" name="NumberOfDays" />
		<input type="hidden" value="' . $_POST['Customers'] . '" name="Customers" />
		<input type="hidden" value="' . filter_number_format($_POST['NumberOfTopItems']) . '" name="NumberOfTopItems" />
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
			<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
						<th>' . _('#') . '</th>
						<th class="ascending">' . _('Code') . '</th>
						<th class="ascending">' . _('Description') . '</th>
						<th class="ascending">' . _('Total Invoiced') . '</th>
						<th class="ascending">' . _('Units') . '</th>
						<th class="ascending">' . _('Value Sales') . '</th>
						<th class="ascending">' . _('On Hand') . '</th>
						<th class="ascending">' . _('On Order') . '</th>
						<th class="ascending">' . _('Stock (Days)') . '</th>
			</tr>
		</thead>
		<tbody>';

	$i = 1;
	while ($myrow = DB_fetch_array($result)) {
		$QOH = 0;
		$QOO = 0;
		switch ($myrow['mbflag']) {
			case 'A':
			case 'D':
			case 'K':
				$QOH = _('N/A');
				$QOO = _('N/A');
			break;
			case 'M':
			case 'B':
				$QOHResult = DB_query("SELECT sum(quantity)
								FROM locstock
								INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
								WHERE stockid = '" . DB_escape_string($myrow['stkcode']) . "'");
				$QOHRow = DB_fetch_row($QOHResult);
				$QOH = $QOHRow[0];

				// Get the QOO due to Purchase orders for all locations. Function defined in SQL_CommonFunctions.inc
				$QOO = GetQuantityOnOrderDueToPurchaseOrders($myrow['stkcode'], '');
				// Get the QOO due to Work Orders for all locations. Function defined in SQL_CommonFunctions.inc
				$QOO += GetQuantityOnOrderDueToWorkOrders($myrow['stkcode'], '');
			break;
		}
	        if(is_numeric($QOH) and is_numeric($QOO)){
			$DaysOfStock = ($QOH + $QOO) / ($myrow['totalinvoiced'] / $_POST['NumberOfDays']);
		}elseif(is_numeric($QOH)){
			$DaysOfStock = $QOH/ ($myrow['totalinvoiced'] / $_POST['NumberOfDays']);
		}elseif(is_numeric($QOO)){
			$DaysOfStock = $QOO/ ($myrow['totalinvoiced'] / $_POST['NumberOfDays']);

		}else{
			$DaysOfStock = 0;
		}
		if ($DaysOfStock < $_POST['MaxDaysOfStock']){
			$CodeLink = '<a href="' . $RootPath . '/SelectProduct.php?StockID=' . $myrow['stkcode'] . '" class="btn btn-info">' . $myrow['stkcode'] . '</a>';
			$QOH = is_numeric($QOH)?locale_number_format($QOH,$myrow['decimalplaces']):$QOH;
			$QOO = is_numeric($QOO)?locale_number_format($QOO,$myrow['decimalplaces']):$QOO;
			printf('<tr class="striped_row">
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					</tr>',
					$i,
					$CodeLink,
					$myrow['description'],
					locale_number_format($myrow['totalinvoiced'],$myrow['decimalplaces']), //total invoice here
					$myrow['units'], //unit
					locale_number_format($myrow['valuesales'],$_SESSION['CompanyRecord']['decimalplaces']), //value sales here
					$QOH,  //on hand
					$QOO, //on order
					locale_number_format($DaysOfStock, 0) //days of available stock
					);
		}
		$i++;
	}
	echo '</tbody></table></div></div></div>';
	echo '<br />
			<div class="row" align="center">
		<div>
        <div class="form-group">
				<input type="submit" name="PrintPDF" class="btn btn-warning" value="' . _('Print PDF') . '" />
			</div>
        </div>
		</div>
		</form>';
}
include ('includes/footer.php');
?>
