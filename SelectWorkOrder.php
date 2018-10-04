<?php


include('includes/session.php');
$Title = _('Search Work Orders');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">
	<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


if (isset($_GET['WO'])) {
	$SelectedWO = $_GET['WO'];
} elseif (isset($_POST['WO'])){
	$SelectedWO = $_POST['WO'];
} else {
	unset($SelectedWO);
}

if (isset($_GET['SelectedStockItem'])) {
	$SelectedStockItem = $_GET['SelectedStockItem'];
} elseif (isset($_POST['SelectedStockItem'])){
	$SelectedStockItem = $_POST['SelectedStockItem'];
} else {
	unset($SelectedStockItem);
}


if (isset($_POST['ResetPart'])){
	 unset($SelectedStockItem);
}

if (isset($SelectedWO) AND $SelectedWO!='') {
	$SelectedWO = trim($SelectedWO);
	if (!is_numeric($SelectedWO)){
		  echo prnMsg(_('The work order number entered MUST be numeric'),'warn');
		  unset ($SelectedWO);
		  include('includes/footer.php');
		  exit;
	} else {
		echo '<p class="text-info">'._('Work Order Number') . ' - <strong>' . $SelectedWO .'</strong></p>';
	}
}

if (isset($_POST['SearchParts'])){

	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		echo '<p class="text-info">'._('Stock description keywords have been used in preference to the Stock code extract entered').'</p>';
	}
	if ($_POST['Keywords']) {
		//insert wildcard characters in spaces
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.decimalplaces,
						SUM(locstock.quantity) AS qoh,
						stockmaster.units
					FROM stockmaster,
						locstock
					WHERE stockmaster.stockid=locstock.stockid
					AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					AND stockmaster.categoryid='" . $_POST['StockCat']. "'
					AND stockmaster.mbflag='M'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.decimalplaces,
						stockmaster.units
					ORDER BY stockmaster.stockid";

	 } elseif (isset($_POST['StockCode'])){
		$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.decimalplaces,
						sum(locstock.quantity) as qoh,
						stockmaster.units
					FROM stockmaster,
						locstock
					WHERE stockmaster.stockid=locstock.stockid
					AND stockmaster.stockid " . LIKE . " '%" . $_POST['StockCode'] . "%'
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					AND stockmaster.mbflag='M'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.decimalplaces,
						stockmaster.units
					ORDER BY stockmaster.stockid";

	 } elseif (!isset($_POST['StockCode']) AND !isset($_POST['Keywords'])) {
		$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.decimalplaces,
						sum(locstock.quantity) as qoh,
						stockmaster.units
					FROM stockmaster,
						locstock
					WHERE stockmaster.stockid=locstock.stockid
					AND stockmaster.categoryid='" . $_POST['StockCat'] ."'
					AND stockmaster.mbflag='M'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.decimalplaces,
						stockmaster.units
					ORDER BY stockmaster.stockid";
	 }

	$ErrMsg =  _('No items were returned by the SQL because');
	$DbgMsg = _('The SQL used to retrieve the searched parts was');
	$StockItemsResult = DB_query($SQL,$ErrMsg,$DbgMsg);
}

if (isset($_POST['StockID'])){
	$StockID = trim(mb_strtoupper($_POST['StockID']));
} elseif (isset($_GET['StockID'])){
	$StockID = trim(mb_strtoupper($_GET['StockID']));
}

if (!isset($StockID)) {

	 /* Not appropriate really to restrict search by date since may miss older
	 ouststanding orders
	$OrdersAfterDate = Date('d/m/Y',Mktime(0,0,0,Date('m')-2,Date('d'),Date('Y')));
	 */

	if (!isset($SelectedWO) or ($SelectedWO=='')){
		
		if (isset($SelectedStockItem)) {
			echo '<h4>'._('For the item') . ': <strong>' . $SelectedStockItem . '</strong> <input type="hidden" name="SelectedStockItem" value="' . $SelectedStockItem . '" /> </h4>';
		}
		echo '<div class="row">
<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">';
		echo _('Work Order number') . '</label>
		 <input type="text" class="form-control" name="WO" autofocus="autofocus" maxlength="8" size="9" /></div></div> 
		 <div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Processing at') . '</label><select name="StockLocation" class="form-control"> ';

		$sql = "SELECT locations.loccode, locationname FROM locations
				INNER JOIN locationusers
					ON locationusers.loccode=locations.loccode
					AND locationusers.userid='" .  $_SESSION['UserID'] . "'
					AND locationusers.canview=1
				WHERE locations.usedforwo = 1";

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

		echo '</select> </div></div><div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label"><br />
</label>
			<select name="ClosedOrOpen" class="form-control">';

		if (isset($_GET['ClosedOrOpen']) AND $_GET['ClosedOrOpen']=='Closed_Only'){
			$_POST['ClosedOrOpen']='Closed_Only';
		}

		if (isset($_POST['ClosedOrOpen']) AND $_POST['ClosedOrOpen']=='Closed_Only'){
			echo '<option selected="selected" value="Closed_Only">' . _('Closed Work Orders Only') . '</option>';
			echo '<option value="Open_Only">' . _('Open Work Orders Only')  . '</option>';
		} else {
			echo '<option value="Closed_Only">' . _('Closed Work Orders Only')  . '</option>';
			echo '<option selected="selected" value="Open_Only">' . _('Open Work Orders Only')  . '</option>';
		}

		echo '</select> </div></div></div>
		<div class="row">
		<div class="col-xs-3">
<div class="form-group"> 
			<input type="submit" class="btn btn-success" name="SearchOrders" value="' . _('Search') . '" />
			</div></div>
			<div class="col-xs-3">
<div class="form-group"> <a href="' . $RootPath . '/WorkOrderEntry.php" class="btn btn-info">' . _('New Work Order') . '</a></div>
			</div>
			</div>
			<br />';
	}

	$SQL="SELECT categoryid,
			categorydescription
			FROM stockcategory
			ORDER BY categorydescription";

	$result1 = DB_query($SQL);

	echo '<h4><strong>' . _('To search for work orders for a specific item use the item selection facilities below') . '</strong></h4><br />
<div class="row">
			<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select a stock category') . '</label>
	  			<select name="StockCat" class="form-control">';

	while ($myrow1 = DB_fetch_array($result1)) {
		echo '<option value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
	}

	  echo '</select></div></div>
	  		<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Description-part or ful') . '</label>
	  		<input type="text" class="form-control" name="Keywords" size="20" maxlength="25" /></div>
		</div>
	  	<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock ID-part or full') . '</label>
	  		<input type="text" class="form-control" name="StockCode" size="15" maxlength="18" /></div>
	  	</div></div>
	  ';
	echo '<div class="row"><div class="col-xs-3">
<div class="form-group"><input type="submit" name="SearchParts" class="btn btn-success" value="' . _('Search') . '" /></div></div>
<div class="col-xs-3">
<div class="form-group"> 
        <input type="submit" class="btn btn-info" name="ResetPart" value="' . _('Show All') . '" /></div></div></div>';

	if (isset($StockItemsResult)) {

		echo '
			<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
			<tr>
				<th>' . _('Code') . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('On Hand') . '</th>
				<th>' . _('Units') . '</th>
				</tr>
			</thead>
			<tbody>';

		while ($myrow=DB_fetch_array($StockItemsResult)) {

			printf('<tr class="striped_row">
					<td><input type="submit" name="SelectedStockItem" value="%s" class="btn btn-info" /></td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					</tr>',
					$myrow['stockid'],
					$myrow['description'],
					locale_number_format($myrow['qoh'],$myrow['decimalplaces']),
					$myrow['units']);

		}//end of while loop
		echo '</tbody></table></div></div></div>';
	}
	//end if stock search results to show
	  else {

	  	if (!isset($_POST['StockLocation'])) {
	  		$_POST['StockLocation'] = '';
	  	}

		//figure out the SQL required from the inputs available
		if (isset($_POST['ClosedOrOpen']) and $_POST['ClosedOrOpen']=='Open_Only'){
			$ClosedOrOpen = 0;
		} else {
			$ClosedOrOpen = 1;
		}
		if (isset($SelectedWO) AND $SelectedWO !='') {
				$SQL = "SELECT workorders.wo,
								woitems.stockid,
								stockmaster.description,
								stockmaster.decimalplaces,
								woitems.qtyreqd,
								woitems.qtyrecd,
								workorders.requiredby,
								workorders.startdate,
								workorders.reference,
								workorders.loccode
						FROM workorders
						INNER JOIN woitems ON workorders.wo=woitems.wo
						INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
						INNER JOIN locationusers ON locationusers.loccode=workorders.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
						WHERE workorders.closed='" . $ClosedOrOpen . "'
						AND workorders.wo='". $SelectedWO ."'
						ORDER BY workorders.wo,
								woitems.stockid";
		} else {
			  /* $DateAfterCriteria = FormatDateforSQL($OrdersAfterDate); */

				if (isset($SelectedStockItem)) {
					$SQL = "SELECT workorders.wo,
									woitems.stockid,
									stockmaster.description,
									stockmaster.decimalplaces,
									woitems.qtyreqd,
									woitems.qtyrecd,
									workorders.requiredby,
									workorders.startdate,
									workorders.reference,
									workorders.loccode
							FROM workorders
							INNER JOIN woitems ON workorders.wo=woitems.wo
							INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
							INNER JOIN locationusers ON locationusers.loccode=workorders.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
							WHERE workorders.closed='" . $ClosedOrOpen . "'
							AND woitems.stockid='". $SelectedStockItem ."'
							AND workorders.loccode='" . $_POST['StockLocation'] . "'
							ORDER BY workorders.wo,
								 woitems.stockid";
				} else {
					$SQL = "SELECT workorders.wo,
									woitems.stockid,
									stockmaster.description,
									stockmaster.decimalplaces,
									woitems.qtyreqd,
									woitems.qtyrecd,
									workorders.requiredby,
									workorders.startdate,
									workorders.reference,
									workorders.loccode
							FROM workorders
							INNER JOIN woitems ON workorders.wo=woitems.wo
							INNER JOIN locationusers ON locationusers.loccode=workorders.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
							INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
							WHERE workorders.closed='" . $ClosedOrOpen . "'
							AND workorders.loccode='" . $_POST['StockLocation'] . "'
							ORDER BY workorders.wo,
									 woitems.stockid";
				}
		} //end not order number selected

		$ErrMsg = _('No works orders were returned by the SQL because');
		$WorkOrdersResult = DB_query($SQL,$ErrMsg);

		/*show a table of the orders returned by the SQL */
		if (DB_num_rows($WorkOrdersResult)>0) {
			echo '<br />
				<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
				<thead>
				<tr>
					<th>' . _('Modify') . '</th>
					<th colspan="5">' . _('Actions') . '</th>
					<th>' . _('Location') . '</th>
					<th>' . _('Item') . '</th>
					<th>' . _('Required') . '</th>
					<th>' . _('Received') . '</th>
					<th>' . _('Pending') . '</th>
					<th>' . _('Start Date')  . '</th>
					<th>' . _('Required Date') . '</th>
					</tr>
				</thead>
				<tbody>';

		while ($myrow=DB_fetch_array($WorkOrdersResult)) {

			$ModifyPage = $RootPath . '/WorkOrderEntry.php?WO=' . $myrow['wo'];
			$Status_WO = $RootPath . '/WorkOrderStatus.php?WO=' .$myrow['wo'] . '&amp;StockID=' . urlencode($myrow['stockid']);
			$Receive_WO = $RootPath . '/WorkOrderReceive.php?WO=' .$myrow['wo'] . '&amp;StockID=' . urlencode($myrow['stockid']);
			$Issue_WO = $RootPath . '/WorkOrderIssue.php?WO=' .$myrow['wo'] . '&amp;StockID=' . urlencode($myrow['stockid']);
			$Costing_WO =$RootPath . '/WorkOrderCosting.php?WO=' .$myrow['wo'];
			$Printing_WO =$RootPath . '/PDFWOPrint.php?WO=' .$myrow['wo'] . '&amp;StockID=' . urlencode($myrow['stockid']);

			$FormatedRequiredByDate = ConvertSQLDate($myrow['requiredby']);
			$FormatedStartDate = ConvertSQLDate($myrow['startdate']);


			printf('<tr class="striped_row">
					<td><a href="%s" class="btn btn-info">%s</a></td>
					<td><a href="%s" class="btn btn-info">' . _('Status') . '</a></td>
					<td><a href="%s" class="btn btn-info">' . _('Issue To') . '</a></td>
					<td><a href="%s" class="btn btn-info">' . _('Receive') . '</a></td>
					<td><a href="%s" class="btn btn-info">' . _('Costing') . '</a></td>
					<td><a href="%s" class="btn btn-warning">' . _('Print') . '</a></td>
					<td>%s</td>
					<td>%s - %s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					</tr>',
					$ModifyPage,
					$myrow['wo'].' : '.$myrow['reference'] . '',
					$Status_WO,
					$Issue_WO,
					$Receive_WO,
					$Costing_WO,
					$Printing_WO,
					$myrow['loccode'],
					urlencode($myrow['stockid']),
					$myrow['description'],
					locale_number_format($myrow['qtyreqd'],$myrow['decimalplaces']),
					locale_number_format($myrow['qtyrecd'],$myrow['decimalplaces']),
					locale_number_format($myrow['qtyreqd']-$myrow['qtyrecd'],$myrow['decimalplaces']),
					$FormatedStartDate,
					$FormatedRequiredByDate);
		}
		//end of while loop

			echo '</tbody></table></div></div></div>';
      }
	}

	echo '
          </form></div></div>';
}

include('includes/footer.php');
?>
