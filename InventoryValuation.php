<?php


include('includes/session.php');
if (isset($_POST['PrintPDF']) OR isset($_POST['CSV'])){

/*Now figure out the inventory data to report for the category range under review */
	if ($_POST['Location']=='All'){
		$SQL = "SELECT stockmaster.categoryid,
					stockcategory.categorydescription,
					stockmaster.stockid,
					stockmaster.description,
					stockmaster.decimalplaces,
					SUM(locstock.quantity) AS qtyonhand,
					stockmaster.units,
					stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost AS unitcost,
					SUM(locstock.quantity) *(stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost) AS itemtotal
				FROM stockmaster,
					stockcategory,
					locstock
				INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE stockmaster.stockid=locstock.stockid
				AND stockmaster.categoryid=stockcategory.categoryid
				GROUP BY stockmaster.categoryid,
					stockcategory.categorydescription,
					unitcost,
					stockmaster.units,
					stockmaster.decimalplaces,
					stockmaster.materialcost,
					stockmaster.labourcost,
					stockmaster.overheadcost,
					stockmaster.stockid,
					stockmaster.description
				HAVING SUM(locstock.quantity)!=0
				AND stockmaster.categoryid IN ('". implode("','",$_POST['Categories'])."')
				ORDER BY stockcategory.categorydescription,
					stockmaster.stockid";
	} else {
		$SQL = "SELECT stockmaster.categoryid,
					stockcategory.categorydescription,
					stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					locstock.quantity AS qtyonhand,
					stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost AS unitcost,
					locstock.quantity *(stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost) AS itemtotal
				FROM stockmaster,
					stockcategory,
					locstock
				INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE stockmaster.stockid=locstock.stockid
				AND stockmaster.categoryid=stockcategory.categoryid
				AND locstock.quantity!=0
				AND stockmaster.categoryid IN ('". implode("','",$_POST['Categories'])."')
				AND locstock.loccode = '" . $_POST['Location'] . "'
				ORDER BY stockcategory.categorydescription,
					stockmaster.stockid";
	}
	$InventoryResult = DB_query($SQL,'','',false,true);

	if (DB_error_no() !=0) {
	  $Title = _('Inventory Valuation') . ' - ' . _('Problem Report');
	  include('includes/header.php');
	   echo prnMsg( _('The inventory valuation could not be retrieved by nERP because') . ' '  . DB_error_msg(),'error');
	  echo '<br /><p align="right"><a href="' . $RootPath . '/index.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';

	   if ($debug==1){
		  echo '<br />' . $SQL;
	   }
	   include('includes/footer.php');
	   exit;
	}
}

if (isset($_POST['PrintPDF'])){

	include('includes/PDFStarter.php');

	$pdf->addInfo('Title',_('Inventory Valuation Report'));
	$pdf->addInfo('Subject',_('Inventory Valuation'));
	$FontSize=9;
	$PageNumber=1;
	$line_height=12;



	if (DB_num_rows($InventoryResult)==0){
		$Title = _('Print Inventory Valuation Error');
		include('includes/header.php');
		echo prnMsg(_('There were no items with any value to print out for the location specified'),'info');
		echo '<br /><p align="right"><a href="' . $RootPath . '/index.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';

		include('includes/footer.php');
		exit;
	}

	include ('includes/PDFInventoryValnPageHeader.inc');

	$Tot_Val=0;
	$Category = '';
	$CatTot_Val=0;
	$CatTot_Qty=0;

	while ($InventoryValn = DB_fetch_array($InventoryResult)){

		if ($Category!=$InventoryValn['categoryid']){
			$FontSize=10;
			if ($Category!=''){ /*Then it's NOT the first time round */

				/* need to print the total of previous category */
				if ($_POST['DetailedReport']=='Yes'){
					$YPos -= (2*$line_height);
					if ($YPos < $Bottom_Margin + (3*$line_height)){
		 				  include('includes/PDFInventoryValnPageHeader.inc');
					}
					$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,260-$Left_Margin,$FontSize,_('Total for') . ' ' . $Category . ' - ' . $CategoryName);
				}

				$DisplayCatTotVal = locale_number_format($CatTot_Val,$_SESSION['CompanyRecord']['decimalplaces']);
				$DisplayCatTotQty = locale_number_format($CatTot_Qty,2);
				$LeftOvers = $pdf->addTextWrap(480,$YPos,80,$FontSize,$DisplayCatTotVal, 'right');
				$LeftOvers = $pdf->addTextWrap(360,$YPos,60,$FontSize,$DisplayCatTotQty, 'right');
				$YPos -=$line_height;

				If ($_POST['DetailedReport']=='Yes'){
				/*draw a line under the CATEGORY TOTAL*/
					$pdf->line($Left_Margin, $YPos+$line_height-2,$Page_Width-$Right_Margin, $YPos+$line_height-2);
					$YPos -=(2*$line_height);
				}
				$CatTot_Val=0;
				$CatTot_Qty=0;
			}
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,260-$Left_Margin,$FontSize,$InventoryValn['categoryid'] . ' - ' . $InventoryValn['categorydescription']);
			$Category = $InventoryValn['categoryid'];
			$CategoryName = $InventoryValn['categorydescription'];
		}

		if ($_POST['DetailedReport']=='Yes'){
			$YPos -=$line_height;
			$FontSize=8;

			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,100,$FontSize,$InventoryValn['stockid']);
			$LeftOvers = $pdf->addTextWrap(170,$YPos,220,$FontSize,$InventoryValn['description']);
			$DisplayUnitCost = locale_number_format($InventoryValn['unitcost'],$_SESSION['CompanyRecord']['decimalplaces']);
			$DisplayQtyOnHand = locale_number_format($InventoryValn['qtyonhand'],$InventoryValn['decimalplaces']);
			$DisplayItemTotal = locale_number_format($InventoryValn['itemtotal'],$_SESSION['CompanyRecord']['decimalplaces']);

			$LeftOvers = $pdf->addTextWrap(360,$YPos,60,$FontSize,$DisplayQtyOnHand,'right');
			$LeftOvers = $pdf->addTextWrap(423,$YPos,15,$FontSize,$InventoryValn['units'],'left');
			$LeftOvers = $pdf->addTextWrap(438,$YPos,60,$FontSize,$DisplayUnitCost, 'right');

			$LeftOvers = $pdf->addTextWrap(500,$YPos,60,$FontSize,$DisplayItemTotal, 'right');
		}
		$Tot_Val += $InventoryValn['itemtotal'];
		$CatTot_Val += $InventoryValn['itemtotal'];
		$CatTot_Qty += $InventoryValn['qtyonhand'];

		if ($YPos < $Bottom_Margin + $line_height){
		   include('includes/PDFInventoryValnPageHeader.inc');
		}

	} /*end inventory valn while loop */

	$FontSize =10;
/*Print out the category totals */
	if ($_POST['DetailedReport']=='Yes'){
		$YPos -= (2*$line_height);
		$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200-$Left_Margin,$FontSize, _('Total for') . ' ' . $Category . ' - ' . $CategoryName, 'left');
	}
	$DisplayCatTotVal = locale_number_format($CatTot_Val,$_SESSION['CompanyRecord']['decimalplaces']);

	$LeftOvers = $pdf->addTextWrap(480,$YPos,80,$FontSize,$DisplayCatTotVal, 'right');
	$DisplayCatTotQty = locale_number_format($CatTot_Qty,2);
	$LeftOvers = $pdf->addTextWrap(360,$YPos,60,$FontSize,$DisplayCatTotQty, 'right');

	if ($_POST['DetailedReport']=='Yes'){
		/*draw a line under the CATEGORY TOTAL*/
		$YPos -= ($line_height);
		$pdf->line($Left_Margin, $YPos+$line_height-2,$Page_Width-$Right_Margin, $YPos+$line_height-2);
	}

	$YPos -= (2*$line_height);

	if ($YPos < $Bottom_Margin + $line_height){
		   include('includes/PDFInventoryValnPageHeader.inc');
	}
/*Print out the grand totals */
	$LeftOvers = $pdf->addTextWrap(80,$YPos,260-$Left_Margin,$FontSize,_('Grand Total Value'), 'right');
	$DisplayTotalVal = locale_number_format($Tot_Val,$_SESSION['CompanyRecord']['decimalplaces']);
	$LeftOvers = $pdf->addTextWrap(500,$YPos,60,$FontSize,$DisplayTotalVal, 'right');

	$pdf->OutputD($_SESSION['DatabaseName'] . '_Inventory_Valuation_' . Date('Y-m-d') . '.pdf');
	$pdf->__destruct();

} elseif (isset($_POST['CSV'])) {

	$CSVListing = _('Category ID') .','. _('Category Description') .','. _('Stock ID') .','. _('Description') .','. _('Decimal Places') .','. _('Qty On Hand') .','. _('Units') .','. _('Unit Cost') .','. _('Total') . "\n";
	while ($InventoryValn = DB_fetch_row($InventoryResult)) {
		$CSVListing .= '"';
		$CSVListing .= implode('","', $InventoryValn) . '"' . "\n";
	}
	header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header("Content-disposition: attachment; filename=InventoryValuation_Categories_" .  $_POST['FromCriteria']  . '-' .  $_POST['ToCriteria']  .'.csv');
    header("Pragma: public");
    header("Expires: 0");
    echo "\xEF\xBB\xBF"; // UTF-8 BOM
	echo $CSVListing;
	exit;

} else { /*The option to print PDF nor to create the CSV was not hit */

	$Title=_('Inventory Valuation Reporting');
	include('includes/header.php');

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '
		</h1></a></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
		  
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Stock Categories') . '</label>
				<select autofocus="autofocus" required="required" minlength="1" size="12" name="Categories[]" multiple="multiple" class="form-control">';
	$SQL = 'SELECT categoryid, categorydescription
			FROM stockcategory
			ORDER BY categorydescription';
	$CatResult = DB_query($SQL);
	while ($MyRow = DB_fetch_array($CatResult)) {
		if (isset($_POST['Categories']) AND in_array($MyRow['categoryid'], $_POST['Categories'])) {
			echo '<option selected="selected" value="' . $MyRow['categoryid'] . '">' . $MyRow['categorydescription'] .'</option>';
		} else {
			echo '<option value="' . $MyRow['categoryid'] . '">' . $MyRow['categorydescription'] . '</option>';
		}
	}
	echo '</select>
			</div>
		</div>';

	echo '<div class="col-xs-4">
<div class="form-group"><label class="col-md-8 control-label"> ' . _('Stock Location') . '</label>
			<select name="Location" class="form-control">';

	$sql = "SELECT locations.loccode,
					locationname
			FROM locations
			INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			ORDER BY locationname";

	$LocnResult=DB_query($sql);

	echo '<option value="All">' . _('All Locations') . '</option>';

	while ($myrow=DB_fetch_array($LocnResult)){
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
	echo '</select></div>
		</div>';

	echo '<div class="col-xs-4">
<div class="form-group"><label class="col-md-8 control-label">' . _('Report Type') . '</label>
			<select name="DetailedReport" class="form-control">
				<option selected="selected" value="No">' . _('Summary Report') . '</option>
				<option value="Yes">' . _('Detailed Report') . '</option>
				</select></div>
		</div>
		</div>
		
		<div class="row">
		<div class="col-xs-4">
<div class="form-group">
			<input type="submit" class="btn btn-warning" name="PrintPDF" value="' . _('Print PDF') . '" />
			</div></div>
			<div class="col-xs-4">
<div class="form-group">
			<input type="submit" name="CSV" class="btn btn-warning" value="' . _('Export to CSV') . '" />
		</div>';
	echo '</div>
	</div>
		  </form>
		  </div>
		  </div>
		  ';

	include('includes/footer.php');

} /*end of else not PrintPDF */
?>
