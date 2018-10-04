<?php

include('includes/session.php');

If (isset($_POST['PrintPDF'])) {

/*	if ($_POST['CustomerSpecials']=='Customer Special Prices Only') {
		// To do: For special prices, change from portrait to landscape orientation.
	}*/
	include('includes/PDFStarter.php');// Sets $PageNumber, page width, page height, top margin, bottom margin, left margin and right margin.

	$pdf->addInfo('Title', _('Price list by inventory category') );
	$pdf->addInfo('Subject', _('Price List') );

	$FontSize=10;
	$line_height=12;
	

	if ($_POST['Currency'] != "All"){
		$WhereCurrency = " AND prices.currabrev = '" . $_POST['Currency'] ."' ";
	}else{
		$WhereCurrency = "";
	}
	/*Now figure out the inventory data to report for the category range under review */
	if ($_POST['CustomerSpecials']==_('Customer Special Prices Only')) {

		if ($_SESSION['CustomerID']=='') {
			$Title = _('Special price List - No Customer Selected');
			$ViewTopic = 'SalesTypes';// Filename in ManualContents.php's TOC.
			$BookMark = 'PDFPriceList';// Anchor's id in the manual's html document.
			include('includes/header.php');
			echo '<br />';
			echo   prnMsg( _('The customer must first be selected from the select customer link') . '. ' . _('Re-run the price list once the customer has been selected') ),'</div>';
			echo '<br /><p align="right"><a class="noprint btn btn-default" href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Back') . '</a></p>';
			include('includes/footer.php');
			exit;
		}
		if (!Is_Date($_POST['EffectiveDate'])) {
			$Title = _('Special price List - No Customer Selected');
			$ViewTopic = 'SalesTypes';// Filename in ManualContents.php's TOC.
			$BookMark = 'PDFPriceList';// Anchor's id in the manual's html document.
			include('includes/header.php');
			echo prnMsg(_('The effective date must be entered in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
			echo '<br /><p align="right"><a class="noprint btn btn-default" href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Back') . '</a></p>';
			include('includes/footer.php');
			exit;
		}

		$SQL = "SELECT debtorsmaster.name,
				debtorsmaster.salestype
				FROM debtorsmaster
				WHERE debtorno = '" . $_SESSION['CustomerID'] . "'";
		$CustNameResult = DB_query($SQL);
		$CustNameRow = DB_fetch_row($CustNameResult);
		$CustomerName = $CustNameRow[0];
		$SalesType = $CustNameRow[1];
		$SQL = "SELECT prices.typeabbrev,
  						prices.stockid,
  						stockmaster.description,
  						stockmaster.longdescription,
  						prices.currabrev,
  						prices.startdate,
  						prices.enddate,
  						prices.price,
  						stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standardcost,
  						stockmaster.categoryid,
  						stockcategory.categorydescription,
  						prices.debtorno,
  						prices.branchcode,
  						custbranch.brname,
  						currencies.decimalplaces
						FROM stockmaster INNER JOIN	stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
						INNER JOIN prices
						ON stockmaster.stockid=prices.stockid
						INNER JOIN currencies
						ON prices.currabrev=currencies.currabrev
                        LEFT JOIN custbranch
						ON prices.debtorno=custbranch.debtorno
						AND prices.branchcode=custbranch.branchcode
						WHERE prices.typeabbrev = '" . $SalesType . "'
						AND stockmaster.categoryid IN ('". implode("','",$_POST['Categories'])."')
						AND prices.debtorno='" . $_SESSION['CustomerID'] . "'
						AND prices.startdate<='" . FormatDateForSQL($_POST['EffectiveDate']) . "'
						AND (prices.enddate='0000-00-00' OR prices.enddate >'" . FormatDateForSQL($_POST['EffectiveDate']) . "')" .
						$WhereCurrency . "
						ORDER BY prices.currabrev,
							stockcategory.categorydescription,
							stockmaster.stockid,
							prices.startdate";

	} else { /* the sales type list only */

		$SQL = "SELECT sales_type FROM salestypes WHERE typeabbrev='" . $_POST['SalesType'] . "'";
		$SalesTypeResult = DB_query($SQL);
		$SalesTypeRow = DB_fetch_row($SalesTypeResult);
		$SalesTypeName = $SalesTypeRow[0];

		$SQL = "SELECT	prices.typeabbrev,
        				prices.stockid,
        				prices.startdate,
        				prices.enddate,
        				stockmaster.description,
        				stockmaster.longdescription,
        				prices.currabrev,
        				prices.price,
        				stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost as standardcost,
        				stockmaster.categoryid,
        				stockcategory.categorydescription,
        				currencies.decimalplaces
				FROM stockmaster INNER JOIN	stockcategory
	   			     ON stockmaster.categoryid=stockcategory.categoryid
				INNER JOIN prices
    				ON stockmaster.stockid=prices.stockid
				INNER JOIN currencies
					ON prices.currabrev=currencies.currabrev
                WHERE stockmaster.categoryid IN ('". implode("','",$_POST['Categories'])."')
				AND prices.typeabbrev='" . $_POST['SalesType'] . "'
    			AND prices.startdate<='" . FormatDateForSQL($_POST['EffectiveDate']) . "'
    			AND (prices.enddate='0000-00-00' OR prices.enddate>'" . FormatDateForSQL($_POST['EffectiveDate']) . "')" .
				$WhereCurrency . "
    			AND prices.debtorno=''
    			ORDER BY prices.currabrev,
    				stockcategory.categorydescription,
    				stockmaster.stockid,
    				prices.startdate";
	}
	$PricesResult = DB_query($SQL,'','',false,false);

	if (DB_error_no() !=0) {
		$Title = _('Price List') . ' - ' . _('Problem Report....');
		include('includes/header.php');
		echo prnMsg( _('The Price List could not be retrieved by the SQL because'). ' - ' . DB_error_msg(), 'error');
		echo '<br /><p align="right"><a class="noprint btn btn-default" href="' .$RootPath .'/menu_data.php">' .   _('<i class="fa fa-hand-o-left fa-fw"></i> Back to Menu'). '</a></p>';
		if ($debug==1) {
			echo prnMsg(_('For debugging purposes the SQL used was:') . $SQL,'error');
		}
		include('includes/footer.php');
		exit;
	}
	if (DB_num_rows($PricesResult)==0) {
		$Title = _('Print Price List Error');
		include('includes/header.php');
		echo  prnMsg(_('There were no price details to print out for the customer or category specified'),'warn');
		echo '<br /><p align="right"><a class="noprint btn btn-default" href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' .  _('<i class="fa fa-hand-o-left fa-fw"></i> Back') . '</a></p><br />';
		include('includes/footer.php');
		exit;
	}

	PageHeader();

	$CurrCode ='';
	$Category = '';
	$CatTot_Val=0;

	While ($PriceList = DB_fetch_array($PricesResult)) {

		if ($CurrCode != $PriceList['currabrev']) {
			$FontSize = 8;
			if ($YPos < $Bottom_Margin + $FontSize*3) {// If the next line reaches the bottom margin, do PageHeader().
			   PageHeader();
			}
			
			$YPos -= $FontSize;// Jumps additional line before.
			require_once('includes/CurrenciesArray.php');// To get the currency name from the currency code.
			$pdf->SetFont('arsenalb', '', 8);
			$pdf->addText($Left_Margin, $YPos, $FontSize,
				$PriceList['currabrev'] . ' - ' . _($CurrencyName[$PriceList['currabrev']]));
			$CurrCode = $PriceList['currabrev'];
			$YPos -= $FontSize;// End-of-line line-feed.
			$pdf->SetFont('arsenal', '', 10);
		}

		if ($Category != $PriceList['categoryid']) {
			$pdf->SetFont('arsenal', '', 10);
			if ($YPos < $Bottom_Margin + $FontSize*3) {// If the next line reaches the bottom margin, do PageHeader().
			   PageHeader();
			}
			$YPos -= $FontSize;// Jumps additional line before.
			$pdf->addText($Left_Margin, $YPos, $FontSize,
				$PriceList['categoryid'] . ' - ' . $PriceList['categorydescription']);
			$Category = $PriceList['categoryid'];
			$YPos -= $FontSize;// End-of-line line-feed.
		}

		$FontSize = 8;
		$pdf->addText($Left_Margin, $YPos, $FontSize, $PriceList['stockid']);
		$pdf->addText($Left_Margin+80, $YPos, $FontSize, $PriceList['description']);
		$pdf->addText($Left_Margin+280, $YPos, $FontSize, ConvertSQLDate($PriceList['startdate']));
		if ($PriceList['enddate']!='0000-00-00') {
			$DisplayEndDate = ConvertSQLDate($PriceList['enddate']);
		} else {
			$DisplayEndDate = _('No End Date');
		}
		$pdf->addText($Left_Margin+320, $YPos, $FontSize, $DisplayEndDate);

		// Shows gross profit percentage:
		if ($_POST['ShowGPPercentages']=='Yes') {
			$DisplayGPPercent = '-';
			if ($PriceList['price']!=0) {
				$DisplayGPPercent = locale_number_format((($PriceList['price']-$PriceList['standardcost'])*100/$PriceList['price']), 2) . '%';
			}
			$pdf->addTextWrap($Page_Width-$Right_Margin-128, $YPos-$FontSize, 32, $FontSize,
				$DisplayGPPercent, 'right');
		}
		// Displays unit price:
		$pdf->addTextWrap($Page_Width-$Right_Margin-96, $YPos-$FontSize, 96, $FontSize,
			locale_number_format($PriceList['price'],$PriceList['decimalplaces']), 'right');

		if ($_POST['CustomerSpecials']=='Customer Special Prices Only') {
			/*Need to show to which branch the price relates */
			if ($PriceList['branchcode']!='') {
				$pdf->addText($Left_Margin+376, $YPos, $FontSize, $PriceList['brname']);
			} else {
				$pdf->addText($Left_Margin+376, $YPos, $FontSize, _('All'));
			}
			$YPos -= $FontSize;// End-of-line line-feed.

		} elseif ($_POST['CustomerSpecials']=='Full Description') {
			$YPos -= $FontSize;

			// Prints item image:
			$SupportedImgExt = array('png','jpg','jpeg');
			$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $PriceList['stockid'] . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
			$YPosImage = $YPos;// Initializes the image bottom $YPos.
			if (file_exists($imagefile) ) {
				if($YPos-36 < $Bottom_Margin) {// If the image bottom reaches the bottom margin, do PageHeader().
					PageHeader();
				}
				$LeftOvers = $pdf->Image($imagefile,$Left_Margin+3, $Page_Height-$YPos, 36, 36);
				$YPosImage = $YPos-36;// Stores the $YPos of the image bottom (see bottom).
			}
			// Prints stockmaster.longdescription:
			$XPos = $Left_Margin+80;// Takes out this calculation from the loop.
			$Width = $Page_Width-$Right_Margin-$XPos;// Takes out this calculation from the loop.
			$FontSize2 = $FontSize*0.80;// Font size and line height of Full Description section.
			$Split = explode("\r\n", $PriceList['longdescription']);
			foreach ($Split as $LeftOvers) {
				$LeftOvers = stripslashes($LeftOvers);
				while(mb_strlen($LeftOvers)>1) {
					if ($YPos < $Bottom_Margin) {// If the description line reaches the bottom margin, do PageHeader().
						PageHeader();
						$YPosImage = $YPos;// Resets the image bottom $YPos.
					}
					$LeftOvers = $pdf->addTextWrap($XPos, $YPos-$FontSize2, $Width, $FontSize2, $LeftOvers);
					$YPos -= $FontSize2;
				}
			}

			// Assigns to $YPos the lowest $YPos value between the image and the description:
			$YPos = min($YPosImage, $YPos);
			$YPos -= $FontSize;// Jumps additional line after the image and the description.
		} else {
			$YPos -= $FontSize;// End-of-line line-feed.

		}/* Endif full descriptions*/

		if ($YPos < $Bottom_Margin + $line_height) {
		   PageHeader();
		}
	} /*end inventory valn while loop */

	$FontSize = 10;
	$FileName='nERP'. '_' . _('Price_List') . '_' . date('Y-m-d').'.pdf';
	ob_clean();
	$pdf->OutputD($FileName);
	$pdf->__destruct();

} else { /*The option to print PDF was not hit */
	$Title = _('Price Listing');
	$ViewTopic = 'SalesTypes';// Filename in ManualContents.php's TOC.
	$BookMark = 'PDFPriceList';// Anchor's id in the manual's html document.
	include('includes/header.php');
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Print a price list by inventory category') . '</h1></a></div>';

echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
	
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<div class="row">
		<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Select Inventory Categories') . '</label>
		<select autofocus="autofocus" class="form-control" required="required" id="example-multiple-select" minlength="1" size="12" name="Categories[]"multiple="multiple">';
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

	echo '
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('For Sales Type/Price List').'</label>
		<select name="SalesType" class="form-control">';
	$sql = "SELECT sales_type, typeabbrev FROM salestypes";
	$SalesTypesResult=DB_query($sql);

	while ($myrow=DB_fetch_array($SalesTypesResult)) {
		echo '<option value="' . $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
	}
	echo '</select></div></div>';

	echo '<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('For Currency').'</label>
		<select name="Currency" class="form-control">';
	$sql = "SELECT currabrev, currency FROM currencies ORDER BY currency";
	$CurrencyResult=DB_query($sql);
	echo '<option selected="selected" value="All">' . _('All')  . '</option>';
	while ($myrow=DB_fetch_array($CurrencyResult)) {
		echo '<option value="' . $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
	}
	echo '</select></div></div></div>';

	echo '<div class="row">
	<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Show Gross Profit %') . '</label>
			<select name="ShowGPPercentages" class="form-control">
				<option selected="selected" value="No">' .  _('Prices Only') . '</option>
				<option value="Yes">' .  _('Show GP % too') . '</option>
				</select></div>
		</div>
		<div class="col-xs-4">
        <div class="form-group"> <label class="col-md-8 control-label">' . _('Price Listing Type'). '</label>
		<select name="CustomerSpecials" class="form-control">
				<option selected="selected" value="Sales Type Prices">' .  _('Default Sales Type Prices') . '</option>
				<option value="Customer Special Prices Only">' .  _('Customer Special Prices Only') . '</option>
				<option value="Full Description">' .  _('Full Description') . '</option>
				</select></div>
		</div>
		<div class="col-xs-4">
        <div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Effective As At') . '</label>
		<input type="text" required="required" maxlength="10" size="11" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="EffectiveDate" value="' . Date($_SESSION['DefaultDateFormat']) . '" /></div>
		</div>
		</div>
		
		<div class="row">
	    <div class="col-xs-4">
        <div class="form-group">
			<input type="submit" name="PrintPDF" class="btn btn-info" value="'. _('Print PDF'). '" />
		</div>
		</div></div>
	</form>
	</div></div>
	';

	include('includes/footer.php');
} /*end of else not PrintPDF */

function PageHeader () {
	global $pdf;
	global $Page_Width;
	global $Page_Height;
	global $Top_Margin;
	global $Bottom_Margin;
	global $Left_Margin;
	global $Right_Margin;
	global $PageNumber;
	global $YPos;
	global $FontSize;
	global $line_height;
	global $SalesTypeName;
	global $CustomerName;

	$PageNumber ++;// Increments $PageNumber before printing.
	if ($PageNumber>1) {// Inserts a page break if it is not the first page.
		$pdf->newPage();
	}

	$YPos = $Page_Height-$Top_Margin;
	$FontSizeLast = $FontSize;// To preserve the main font size.
	$FontSize = 10;
	$pdf->SetFont('arsenalb', '', 10);
	$pdf->addText($Left_Margin, $YPos+5, $FontSize,
		$_SESSION['CompanyRecord']['coyname']);// Company name.
	$pdf->SetFont('arsenal', '', 10);
	$FontSize = 8;
	$pdf->addTextWrap($Page_Width-$Right_Margin-140, $YPos-$FontSize, 140, $FontSize,
		_('Page'). ' ' . $PageNumber, 'right');// Page number.

$pdf->SetFont('arsenal', '', 9);
	//$FontSize = 9;
	$YPos -= $FontSize;
	
	//Note, this is ok for multilang as this is the value of a Select, text in option is different
	if ($_POST['CustomerSpecials']==_('Customer Special Prices Only')) {
		$pdf->addText($Left_Margin, $YPos, $FontSize, _('Price List') . ': ' . $CustomerName);
	} else {
		$pdf->addText($Left_Margin, $YPos, $FontSize, _('Price List') . ': ' . $SalesTypeName);
	}
	$pdf->SetFont('arsenal', '', 8);
	$pdf->addTextWrap($Page_Width-$Right_Margin-140, $YPos-$FontSize, 140, $FontSize,
		_('Printed') . ': ' . date($_SESSION['DefaultDateFormat']), 'right');// Date printed.

	$YPos -= $FontSize;
	$pdf->addText($Left_Margin, $YPos, $FontSize, _('Effective As At') . ' ' . $_POST['EffectiveDate']);
	$pdf->addTextWrap($Page_Width-$Right_Margin-140, $YPos-$FontSize, 140, $FontSize,
		date('H:i:s'), 'right');// Time printed.

	$YPos -=(2*$line_height);
	// Draws a rectangle to put the headings in:
	$pdf->Rectangle(
		$Left_Margin,// Rectangle $XPos.
		$YPos,// Rectangle $YPos.
		$Page_Width-$Left_Margin-$Right_Margin,// Rectangle $Width.
		$line_height*2);// Rectangle $Height.

	$YPos -= $line_height;
$pdf->SetFont('arsenalb', '', 10);
	//$FontSize = 10;
	/*set up the headings */
	$LeftOvers = $pdf->addTextWrap($Left_Margin, $YPos, 80, $FontSize, _('Item Code'));// 20chr @ 8dpi.
	if($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
		$LeftOvers = $pdf->addTextWrap($Left_Margin, $YPos-$FontSize, 80, $FontSize, $LeftOvers);
	}
	$LeftOvers = $pdf->addTextWrap($Left_Margin+80, $YPos, 200,$FontSize, _('Item Description'));// 50chr @ 8dpi.
	if($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
		$LeftOvers = $pdf->addTextWrap($Left_Margin+80, $YPos-$FontSize, 200, $FontSize, $LeftOvers);
	}
	$LeftOvers = $pdf->addTextWrap($Left_Margin+280, $YPos, 96, $FontSize, _('Effective Date Range'), 'center');// (10+2+12)chr @ 8dpi.
	if($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
		$LeftOvers = $pdf->addTextWrap($Left_Margin+280, $YPos-$FontSize, 96, $FontSize, $LeftOvers, 'center');
	}

	if ($_POST['CustomerSpecials']=='Customer Special Prices Only') {
		$LeftOvers = $pdf->addTextWrap($Left_Margin+376, $YPos, 160, $FontSize, _('Branch'));// 40chr @ 8dpd.
	}

	if ($_POST['ShowGPPercentages']=='Yes') {
		$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-128, $YPos, 32, $FontSize, _('Gross Profit'), 'right');// 8chr @ 8dpi.
		if($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
			$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-128, $YPos-$FontSize, 32, $FontSize, $LeftOvers, 'right');
		}
	}
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-96, $YPos, 96, $FontSize, _('Price') , 'right');// 24chr @ 8dpd.
	$YPos -= $FontSize+8;
$pdf->SetFont('arsenal', '', 10);
	//$FontSize = 10;
	// In some countries it is mandatory to clarify that prices do not include taxes:
	$pdf->addText($Left_Margin, $YPos, $FontSize,
		'* ' . _('Prices excluding tax'));// Warning text.
	$YPos -= $FontSize;// End-of-line line-feed.*/

/*	$YPos -= $FontSize;// Jumps additional line after the table headings.*/
$pdf->SetFont('arsenal', '', 10);
	//$FontSize = $FontSizeLast;// Resets to the main font size.
}
?>
