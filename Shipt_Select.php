<?php


include('includes/session.php');
$Title = _('Search Shipments');
include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_GET['SelectedStockItem'])){
	$SelectedStockItem=$_GET['SelectedStockItem'];
} elseif (isset($_POST['SelectedStockItem'])){
	$SelectedStockItem=$_POST['SelectedStockItem'];
}

if (isset($_GET['ShiptRef'])){
	$ShiptRef=$_GET['ShiptRef'];
} elseif (isset($_POST['ShiptRef'])){
	$ShiptRef=$_POST['ShiptRef'];
}

if (isset($_GET['SelectedSupplier'])){
	$SelectedSupplier=$_GET['SelectedSupplier'];
} elseif (isset($_POST['SelectedSupplier'])){
	$SelectedSupplier=$_POST['SelectedSupplier'];
}

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


If (isset($_POST['ResetPart'])) {
     unset($SelectedStockItem);
}

If (isset($ShiptRef) AND $ShiptRef!='') {
	if (!is_numeric($ShiptRef)){
		  echo '<br />';
		  echo prnMsg( _('The Shipment Number entered MUST be numeric') );
		  unset ($ShiptRef);
	} else {
		echo _('Shipment Number'). ' - '. $ShiptRef;
	}
} else {
	if (isset($SelectedSupplier)) {
		echo '<br />' ._('For supplier'). ': '. $SelectedSupplier . ' ' . _('and'). ' ';
		echo '<input type="hidden" name="SelectedSupplier" value="'. $SelectedSupplier. '" />';
	}
	If (isset($SelectedStockItem)) {
		 echo _('for the part'). ': ' . $SelectedStockItem . '.';
		echo '<input type="hidden" name="SelectedStockItem" value="'. $SelectedStockItem. '" />';
	}
}

if (isset($_POST['SearchParts'])) {

	If ($_POST['Keywords'] AND $_POST['StockCode']) {
		echo '<br />';
		echo prnMsg( _('Stock description keywords have been used in preference to the Stock code extract entered'),'info');
	}
	$SQL = "SELECT stockmaster.stockid,
			description,
			decimalplaces,
			SUM(locstock.quantity) AS qoh,
			units,
			SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qord
		FROM stockmaster INNER JOIN locstock
			ON stockmaster.stockid = locstock.stockid
		INNER JOIN purchorderdetails
			ON stockmaster.stockid=purchorderdetails.itemcode";

	If ($_POST['Keywords']) {
		//insert wildcard characters in spaces
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		$SQL .= " WHERE purchorderdetails.shiptref IS NOT NULL
			AND purchorderdetails.shiptref<>0
			AND stockmaster.description " . LIKE . " '" . $SearchString . "'
			AND categoryid='" . $_POST['StockCat'] . "'";

	 } elseif ($_POST['StockCode']){

		$SQL .= " WHERE purchorderdetails.shiptref IS NOT NULL
			AND purchorderdetails.shiptref<>0
			AND stockmaster.stockid " . LIKE . " '%" . $_POST['StockCode'] . "%'
			AND categoryid='" . $_POST['StockCat'] ."'";

	 } elseif (!$_POST['StockCode'] AND !$_POST['Keywords']) {
		$SQL .= " WHERE purchorderdetails.shiptref IS NOT NULL
			AND purchorderdetails.shiptref<>0
			AND stockmaster.categoryid='" . $_POST['StockCat'] . "'";

	 }
	$SQL .= "  GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.decimalplaces,
						stockmaster.units";

	$ErrMsg = _('No Stock Items were returned from the database because'). ' - '. DB_error_msg();
	$StockItemsResult = DB_query($SQL, $ErrMsg);

}

if (!isset($ShiptRef) or $ShiptRef==""){
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">';
	echo _('Shipment Number'). '</label> <input type="text" class="form-control" name="ShiptRef" maxlength="10" size="10" /> </div></div>'.
		' <div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">'._('Location').' </label><select name="StockLocation" class="form-control"> ';
	$sql = "SELECT loccode, locationname FROM locations";
	$resultStkLocs = DB_query($sql);
	while ($myrow=DB_fetch_array($resultStkLocs)){
		if (isset($_POST['StockLocation'])){
			if ($myrow['loccode'] == $_POST['StockLocation']){
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			} else {
			echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			}
		} elseif ($myrow['loccode']==$_SESSION['UserStockLocation']){
			$_POST['StockLocation'] = $_SESSION['UserStockLocation'];
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname']  . '</option>';
		} else {
			echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname']  . '</option>';
		}
	}

	echo '</select></div></div>';
	echo ' <div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label"></label><select name="OpenOrClosed" class="form-control">';
	if (isset($_POST['OpenOrClosed']) AND $_POST['OpenOrClosed']==1){
		echo '<option selected="selected" value="1">' .  _('Closed Shipments Only')  . '</option>';
		echo '<option value="0">' .  _('Open Shipments Only')  . '</option>';
	} else {
		$_POST['OpenOrClosed']=0;
		echo '<option value="1">' .  _('Closed Shipments Only')  . '</option>';
		echo '<option selected="selected" value="0">' .  _('Open Shipments Only')  . '</option>';
	}
	echo '</select></div></div></div>';

	echo '
			<div class="row">
			<div class="col-xs-4">
				<input type="submit" class="btn btn-success" name="SearchShipments" value="'. _('Search'). '" />
			</div>
			</div>
			<br />';
}

$SQL="SELECT categoryid,
		categorydescription
	FROM stockcategory
	WHERE stocktype<>'D'
	ORDER BY categorydescription";
$result1 = DB_query($SQL);

echo '<h3 class="sub-header">' . _('To search for shipments for a specific part use the part selection facilities below') . '</h3>
	<div class="row">
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock category') . ':
			<select name="StockCat" class="form-control">';

while ($myrow1 = DB_fetch_array($result1)) {
	if (isset($_POST['StockCat']) and $myrow1['categoryid']==$_POST['StockCat']){
		echo '<option selected="selected" value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription']  . '</option>';
	} else {
		echo '<option value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription']  . '</option>';
	}
}
echo '</select></div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Description') . ' ' . _('-part or full') . '</label>
		<input type="text" class="form-control" name="Keywords" size="20" maxlength="25" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label"> ' . _('') . ' ' . _('Stock ID-part or full') . '</label>
		<input type="text" name="StockCode" class="form-control" size="15" maxlength="18" /></div>
	</div>
	</div>
	';

echo '<div class="row">
<div class="col-xs-4">
		<input type="submit" name="SearchParts" class="btn btn-success" value="'._('Search').'" />
		</div>
<div class="col-xs-4">		
		<input type="submit" name="ResetPart" class="btn btn-info" value="'. _('Show All') .'" />
	</div>
	</div>
	<br />';

if (isset($StockItemsResult)) {

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	$TableHeader = '<thead><tr>
						<th>' .  _('Code') . '</th>
						<th>' .  _('Description') . '</th>
						<th>' .  _('On Hand') . '</th>
						<th>' .  _('Outstanding Orders') . '</th>
						<th>' .  _('Units') . '</th>
					</tr></thead>';
	echo $TableHeader;

	$j = 1;

	while ($myrow=DB_fetch_array($StockItemsResult)) {

/*
Code	 Description	On Hand		 Orders Ostdg     Units		 Code	Description 	 On Hand     Orders Ostdg	Units	 */
		printf('<tr class="striped_row">
				<td><input type="submit" name="SelectedStockItem" value="%s" class="btn btn-info" /></td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td>%s</td>
				</tr>',
				$myrow['stockid'],
				$myrow['description'],
				locale_number_format($myrow['qoh'],$myrow['decimalplaces']),
				locale_number_format($myrow['qord'],$myrow['decimalplaces']),
				$myrow['units']);

		$j++;
		If ($j == 15){
			$j=1;
			echo $TableHeader;
		}
//end of page full new headings if
	}
//end of while loop

	echo '</table></div></div></div>';

}
//end if stock search results to show
  else {

	//figure out the SQL required from the inputs available

	if (isset($ShiptRef) AND $ShiptRef !="") {
		$SQL = "SELECT shipments.shiptref,
				vessel,
				voyageref,
				suppliers.suppname,
				shipments.eta,
				shipments.closed
			FROM shipments INNER JOIN suppliers
				ON shipments.supplierid = suppliers.supplierid
			WHERE shipments.shiptref='". $ShiptRef . "'";
	} else {
		$SQL = "SELECT DISTINCT shipments.shiptref, vessel, voyageref, suppliers.suppname, shipments.eta, shipments.closed
			FROM shipments INNER JOIN suppliers
				ON shipments.supplierid = suppliers.supplierid
			INNER JOIN purchorderdetails
				ON purchorderdetails.shiptref=shipments.shiptref
			INNER JOIN purchorders
				ON purchorderdetails.orderno=purchorders.orderno";

		if (isset($SelectedSupplier)) {

			if (isset($SelectedStockItem)) {
					$SQL .= " WHERE purchorderdetails.itemcode='". $SelectedStockItem ."'
						AND shipments.supplierid='" . $SelectedSupplier ."'
						AND purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
						AND shipments.closed='" . $_POST['OpenOrClosed'] . "'";
			} else {
				$SQL .= " WHERE shipments.supplierid='" . $SelectedSupplier ."'
					AND purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
					AND shipments.closed='" . $_POST['OpenOrClosed'] ."'";
			}
		} else { //no supplier selected
			if (isset($SelectedStockItem)) {
				$SQL .= " WHERE purchorderdetails.itemcode='". $SelectedStockItem ."'
					AND purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
					AND shipments.closed='" . $_POST['OpenOrClosed'] . "'";
			} else {
				$SQL .= " WHERE purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
					AND shipments.closed='" . $_POST['OpenOrClosed'] . "'";
			}

		} //end selected supplier
	} //end not order number selected

	$ErrMsg = _('No shipments were returned by the SQL because');
	$ShipmentsResult = DB_query($SQL,$ErrMsg);


	if (DB_num_rows($ShipmentsResult)>0){
		/*show a table of the shipments returned by the SQL */

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		$TableHeader = '<thead><tr>
							<th>' .  _('Shipment'). '</th>
							<th>' .  _('Supplier'). '</th>
							<th>' .  _('Vessel'). '</th>
							<th>' .  _('Voyage'). '</th>
							<th>' .  _('Expected Arrival'). '</th>
							<th colspan="3">' .  _('Actions'). '</th>
						</tr></thead>';

		echo $TableHeader;

		$j = 1;

		while ($myrow=DB_fetch_array($ShipmentsResult)) {

			$URL_Modify_Shipment = $RootPath . '/Shipments.php?SelectedShipment=' . $myrow['shiptref'];
			$URL_View_Shipment = $RootPath . '/ShipmentCosting.php?SelectedShipment=' . $myrow['shiptref'];

			$FormatedETA = ConvertSQLDate($myrow['eta']);
			/* ShiptRef   Supplier  Vessel  Voyage  ETA */

			if ($myrow['closed']==0){

				$URL_Close_Shipment = $URL_View_Shipment . '&amp;Close=Yes';

				printf('<tr class="striped_row">
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="%s" class="btn btn-info">' . _('Costing') . '</a></td>
					<td><a href="%s" class="btn btn-info">' . _('Modify') . '</a></td>
					<td><a href="%s" class="btn btn-danger">' . _('Close') . '</a></td>
					</tr>',
					$myrow['shiptref'],
					$myrow['suppname'],
					$myrow['vessel'],
					$myrow['voyageref'],
					$FormatedETA,
					$URL_View_Shipment,
					$URL_Modify_Shipment,
					$URL_Close_Shipment);

			} else {
				printf('<tr class="striped_row">
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><a href="%s" class="btn btn-info">' . _('Costing') . '</a></td>
						</tr>',
						$myrow['shiptref'],
						$myrow['suppname'],
						$myrow['vessel'],
						$myrow['voyage'],
						$FormatedETA,
						$URL_View_Shipment);
			}
			$j++;
			If ($j == 15){
				$j=1;
				echo $TableHeader;
			}
		//end of page full new headings if
		}
		//end of while loop

		echo '</table></div></div></div>';
	} // end if shipments to show
}

echo '
      </form>';
include('includes/footer.php');
?>
