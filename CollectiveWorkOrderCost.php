<?php
/* Multiple work orders cost review */

include('includes/session.php');
$Title = _('Search Work Orders');
$ViewTopic = 'GeneralLedger';
$BookMark = 'Z_ChangeGLAccountCode';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
	$Title, '</h1></a></div>';// Page title.

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($_POST['Submit'])) {//users have selected the WO to calculate and submit it
		$WOSelected = '';
		$i = 0;
		foreach ($_POST as $Key=>$Value) {
			if (substr($Key,0,3) == 'WO_'){
				if ($i>0) $WOSelected .=",";
				if($Value == 'on'){
					$WOSelected .= substr($Key,3);
				}
				$i++;
			}
		}
		if (empty($WOSelected)) {
			echo prnMsg(_('No work orders selected'),'error');
		} else {
			//lets do the workorder issued items retrieve
			$sql = "SELECT stockmoves.stockid,
				stockmaster.description,
				stockmaster.decimalplaces,
				trandate,
				qty,
				reference,
				stockmoves.standardcost
				FROM stockmoves INNER JOIN stockmaster
				ON stockmoves.stockid=stockmaster.stockid
				WHERE stockmoves.type=28
				AND reference IN (" . $WOSelected . ")
				ORDER BY reference";
			$ErrMsg = _('Failed to retrieve wo cost data');
		       	$result = DB_query($sql,$ErrMsg);
			if (DB_num_rows($result)>0) {
				echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
					<thead>
						<tr>
							<th>' . _('Item') . '</th>
						<th>' . _('Description') . '</th>
						<th>' . _('Date Issued') . '</th>
						<th>' . _('Issued Qty') . '</th>
						<th>' . _('Issued Cost') . '</th>
						<th>' . _('Work Order') . '</th>
						</tr>
					</thead>
					<tbody>';

				$TotalCost = 0;
				while ($myrow = DB_fetch_array($result)){
					$IssuedQty = - $myrow['qty'];
					$IssuedCost = $IssuedQty * $myrow['standardcost'];
					$TotalCost += $IssuedCost;
					echo '<tr class="striped_row">
						<td>' . $myrow['stockid'] . '</td>
						<td>' . $myrow['description'] . '</td>
						<td>' . $myrow['trandate'] . '</td>
						<td>' . locale_number_format($IssuedQty,$myrow['decimalplaces']) . '</td>
						<td>' . locale_number_format($IssuedCost,2) . '</td>
						<td>' . $myrow['reference'] . '</td>
					       </tr>';
				}
				echo '</tbody>
					<tfoot>
						<tr>
							<td colspan="4"><b>' . _('Total Cost') . '</b></td>
					<td colspan="2"><b>' .locale_number_format($TotalCost,2) . '</b></td>
						</tr>
					</tfoot>
				</table></div></div></div>';
			} else {
				echo prnMsg(_('No data available'),'error');
				include('includes/footer.php');
				exit;
			}
		}//end of the work orders are not empty
		echo '<p align="left"><a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-default">' . _('Select Other Work Orders') . '</a></p>';
		include('includes/footer.php');
		exit;

}


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
		  echo prnMsg(_('The work order number entered must be numeric'),'warn');
		  unset ($SelectedWO);
		  include('includes/footer.php');
		  exit;
	} else {
		echo '<p><strong>'._('Work Order Number') . ' - ' . $SelectedWO.'</strong></p>';
	}
}

if (isset($_POST['SearchParts'])){

	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		echo '<p>'. _('Stock description keywords have been used in preference to the Stock code extract entered').'</p>';
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
		echo '<div class="row">';
		if (isset($SelectedStockItem)) {
			echo '<h4>'._('For the item') . ': ' . $SelectedStockItem . ' ' . _('and') . ' <input type="hidden" name="SelectedStockItem" value="' . $SelectedStockItem . '" /></h4>';
		}
		echo  '</div><div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">'._('Work Order number') . '</label> <input type="text" class="form-control" name="WO" autofocus="autofocus" maxlength="8" size="9" /></div></div>
 <div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Location') . '</label>
<select name="StockLocation" class="form-control"> ';

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

		echo '</select> </div></div>
		<div class="col-xs-4">
<div class="form-group"><label class="col-md-8 control-label">' . _('Work Order Type') . '</label>
			<select name="ClosedOrOpen" class="form-control">';

		if ($_GET['ClosedOrOpen']=='Closed_Only'){
			$_POST['ClosedOrOpen']='Closed_Only';
		}

		if ($_POST['ClosedOrOpen']=='Closed_Only'){
			echo '<option selected="selected" value="Closed_Only">' . _('Closed') . '</option>';
			echo '<option value="Open_Only">' . _('Open')  . '</option>';
			echo '<option value="All">' . _('All') . '</option>';
		} elseif($_POST['ClosedOrOpen'] == 'Open_Only') {
			echo '<option value="Closed_Only">' . _('Closed')  . '</option>';
			echo '<option selected="selected" value="Open_Only">' . _('Open')  . '</option>';
			echo '<option value="All">' . _('All') . '</option>';
		} elseif ($_POST['ClosedOrOpen'] == 'All') {
			echo '<option value="Closed_Only">' . _('Closed')  . '</option>';
			echo '<option value="Open_Only">' . _('Open')  . '</option>';
			echo '<option selected="selected" value="All">' . _('All') . '</option>';
		} else {
			echo '<option value="Closed_Only">' . _('Closed')  . '</option>';
			echo '<option value="Open_Only">' . _('Open')  . '</option>';
			echo '<option selected="selected" value="All">' . _('All') . '</option>';
		}
		if (!isset($_POST['DateFrom'])) {
			$_POST['DateFrom'] = '';
		}
		if (!isset($_POST['DateTo'])) {
			$_POST['DateTo'] = '';
		}

		echo '</select> 
			</div>
			</div>
			</div>
			
			<div class="row"><div class="col-xs-4">
<div class="form-group"><label class="col-md-8 control-label">' . _('Start Date From') . '</label>
<input type="text" name="DateFrom" value="' . $_POST['DateFrom'] . '" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker"
 /></div></div>

	<div class="col-xs-4">
<div class="form-group"><label class="col-md-8 control-label">		' . _('Start Date To') . '</label>
<input type="text" name="DateTo" value="' . $_POST['DateTo'] . '" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker"
 />
			</div>
				</div>
				</div>';
		echo '<div class="row"><div class="col-xs-4">
<div class="form-group">
			<input type="submit" class="btn btn-info" name="SearchOrders" value="' . _('Search') . '" />
			</div></div>
			
			
			</div>
			';
	}

	$SQL="SELECT categoryid,
			categorydescription
			FROM stockcategory
			ORDER BY categorydescription";

	$result1 = DB_query($SQL);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h4>' . _('Search for work orders for a specific item') . '</div>
<div class="row">
			
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select a stock category') . '</label>
	  			<select name="StockCat" class="form-control">';

	while ($myrow1 = DB_fetch_array($result1)) {
		echo '<option value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
	}

	  echo '</select></div></div>
	  		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Description-part or full') . '</label>
	  		<input type="text" class="form-control" name="Keywords" size="20" maxlength="25" /></div>
		</div>
	  	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock ID-part or full') . '</label>
	  		<input type="text" class="form-control" name="StockCode" size="15" maxlength="18" /></div>
	  	</div>
	  </div>';
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group"><input type="submit" name="SearchParts" class="btn btn-info" value="' . _('Search') . '" />
</div></div>
<div class="col-xs-4">
</div></div></div></div></div>';

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
					<td><input type="submit" name="SelectedStockItem" value="%s" class="btn btn-warning" /></td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					</tr>',
					$myrow['stockid'],
					$myrow['description'],
					locale_number_format($myrow['qoh'],$myrow['decimalplaces']),
					$myrow['units']);

		}//end of while loop
		echo '</tbody>
			</table></div></div></div>';
	}
	//end if stock search results to show
	  else {

	  	if (!isset($_POST['StockLocation'])) {
	  		$_POST['StockLocation'] = '';
	  	}

		//figure out the SQL required from the inputs available
		if (isset($_POST['ClosedOrOpen']) and $_POST['ClosedOrOpen']=='Open_Only'){
			$ClosedOrOpen = ' AND workorders.closed=0';
		} elseif(isset($_POST['ClosedOrOpen']) AND $_POST['ClosedOrOpen'] == 'Closed_Only') {
			$ClosedOrOpen = ' AND workorders.closed=1';
		} else {
			$ClosedOrOpen = '';
		}
		//start date and end date
		if (!empty($_POST['DateFrom'])) {
			$StartDateFrom = " AND workorders.startdate>='" . FormatDateForSQL($_POST['DateFrom']) . "'";
		}
		if (!empty($_POST['DateTo'])) {
			$StartDateTo = " AND workorders.startdate<='" . FormatDateForSQL($_POST['DateTo']) . "'";
		}

		if (isset($SelectedWO) AND $SelectedWO !='') {
				$SQL = "SELECT workorders.wo,
								woitems.stockid,
								stockmaster.description,
								stockmaster.decimalplaces,
								woitems.qtyreqd,
								woitems.qtyrecd,
								workorders.requiredby,
								workorders.startdate
						FROM workorders
						INNER JOIN woitems ON workorders.wo=woitems.wo
						INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
						INNER JOIN locationusers ON locationusers.loccode=workorders.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
						WHERE 1 " . $ClosedOrOpen . $StartDateFrom . $StartDateTo . "
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
									workorders.startdate
							FROM workorders
							INNER JOIN woitems ON workorders.wo=woitems.wo
							INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
							INNER JOIN locationusers ON locationusers.loccode=workorders.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
							WHERE 1 " . $ClosedOrOpen . $StartDateFrom . $StartDateTo . "
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
									workorders.startdate
							FROM workorders
							INNER JOIN woitems ON workorders.wo=woitems.wo
							INNER JOIN locationusers ON locationusers.loccode=workorders.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
							INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
							WHERE  1 " . $ClosedOrOpen . $StartDateFrom . $StartDateTo ."
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
				<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post" id="wos">
				<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
				<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
				<thead>
				<tr>
					<th>' . _('Select') . '</th>
					<th>' . _('Modify') . '</th>
					<th>' . _('Status') . '</th>
					<th>' . _('Issue To') . '</th>
					<th>' . _('Receive') . '</th>
					<th>' . _('Costing') . '</th>
					<th>' . _('Print') . '</th>
					<th>' . _('Item') . '</th>
					<th>' . _('Quantity Required') . '</th>
					<th>' . _('Quantity Received') . '</th>
					<th>' . _('Quantity Outstanding') . '</th>
					<th>' . _('Start Date')  . '</th>
					<th>' . _('Required Date') . '</th>
					</tr>
				</thead>
				<tbody>';

		while ($myrow=DB_fetch_array($WorkOrdersResult)) {

			$ModifyPage = $RootPath . '/WorkOrderEntry.php?WO=' . $myrow['wo'];
			$Status_WO = $RootPath . '/WorkOrderStatus.php?WO=' .$myrow['wo'] . '&amp;StockID=' . $myrow['stockid'];
			$Receive_WO = $RootPath . '/WorkOrderReceive.php?WO=' .$myrow['wo'] . '&amp;StockID=' . $myrow['stockid'];
			$Issue_WO = $RootPath . '/WorkOrderIssue.php?WO=' .$myrow['wo'] . '&amp;StockID=' . $myrow['stockid'];
			$Costing_WO =$RootPath . '/WorkOrderCosting.php?WO=' .$myrow['wo'];
			$Printing_WO =$RootPath . '/PDFWOPrint.php?WO=' .$myrow['wo'] . '&amp;StockID=' . $myrow['stockid'];

			$FormatedRequiredByDate = ConvertSQLDate($myrow['requiredby']);
			$FormatedStartDate = ConvertSQLDate($myrow['startdate']);


			printf('<tr class="striped_row">
					<td><input type="checkbox" name="WO_%s" /></td>
					<td><a href="%s" class="btn btn-warning">%s</a></td>
					<td><a href="%s" class="btn btn-warning">' . _('Status') . '</a></td>
					<td><a href="%s" class="btn btn-warning">' . _('Issue To') . '</a></td>
					<td><a href="%s" class="btn btn-warning">' . _('Receive') . '</a></td>
					<td><a href="%s" class="btn btn-warning">' . _('Costing') . '</a></td>
					<td><a href="%s" class="btn btn-warning">' . _('Print W/O') . '</a></td>
					<td>%s - %s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					</tr>',
					$myrow['wo'],
					$ModifyPage,
					$myrow['wo'],
					$Status_WO,
					$Issue_WO,
					$Receive_WO,
					$Costing_WO,
					$Printing_WO,
					$myrow['stockid'],
					$myrow['description'],
					locale_number_format($myrow['qtyreqd'],$myrow['decimalplaces']),
					locale_number_format($myrow['qtyrecd'],$myrow['decimalplaces']),
					locale_number_format($myrow['qtyreqd']-$myrow['qtyrecd'],$myrow['decimalplaces']),
					$FormatedStartDate,
					$FormatedRequiredByDate);
		//end of page full new headings if
		}
		//end of while loop

		echo '</tbody>
			</table></div></div></div>
			<div class="row"><br />
			<div class="col-xs-4">
				<input type="submit" class="btn btn-info" value="' . _('Submit') . '" name="Submit" />
				</div>
				</div><br />

			</form>';
      }
	}

	echo '
          </form>';
}

include('includes/footer.php');
?>
