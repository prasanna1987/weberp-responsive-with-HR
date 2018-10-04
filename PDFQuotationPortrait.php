<?php
/*	Please note that addTextWrap prints a font-size-height further down than
	addText and other functions.*/

include('includes/session.php');
include('includes/SQL_CommonFunctions.inc');
$cm1 = $_SESSION['CompanyRecord']['coyname'];
//Get Out if we have no order number to work with
If (!isset($_GET['QuotationNo']) || $_GET['QuotationNo']==""){
        $Title = _('Select Quotation To Print');
        include('includes/header.php');
      
        echo  prnMsg( _('Select a Quotation to Print before calling this page') , 'error');
        echo '<br />
				<div class="row">
<div class="col-xs-4"><a href="'. $RootPath . '/SelectSalesOrder.php?Quotations=Quotes_Only">' . _('Quotations') . '</a></div>
						</div>
				
				<br />';
        include('includes/footer.php');
        exit();
}

/*retrieve the order details from the database to print */
$ErrMsg = _('There was a problem retrieving the quotation header details for Order Number') . ' ' . $_GET['QuotationNo'] . ' ' . _('from the database');

$sql = "SELECT salesorders.customerref,
				salesorders.comments,
				salesorders.orddate,
				salesorders.deliverto,
				salesorders.deladd1,
				salesorders.deladd2,
				salesorders.deladd3,
				salesorders.deladd4,
				salesorders.deladd5,
				salesorders.deladd6,
				debtorsmaster.name,
				debtorsmaster.currcode,
				debtorsmaster.address1,
				debtorsmaster.address2,
				debtorsmaster.address3,
				debtorsmaster.address4,
				debtorsmaster.address5,
				debtorsmaster.address6,
				shippers.shippername,
				salesorders.printedpackingslip,
				salesorders.datepackingslipprinted,
				salesorders.quotedate,
				salesorders.branchcode,
				locations.taxprovinceid,
				locations.locationname,
				currencies.decimalplaces AS currdecimalplaces
			FROM salesorders INNER JOIN debtorsmaster
			ON salesorders.debtorno=debtorsmaster.debtorno
			INNER JOIN shippers
			ON salesorders.shipvia=shippers.shipper_id
			INNER JOIN locations
			ON salesorders.fromstkloc=locations.loccode
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE salesorders.quotation=1
			AND salesorders.orderno='" . $_GET['QuotationNo'] ."'";

$result=DB_query($sql, $ErrMsg);

//If there are no rows, there's a problem.
if (DB_num_rows($result)==0){
	$Title = _('Print Quotation Error');
	include('includes/header.php');
	
	echo  prnMsg( _('Unable to Locate Quotation Number') . ' : ' . $_GET['QuotationNo'] . ' ', 'error');
	echo '<br />
			<div class="row">
<div class="col-xs-4"><a href="'. $RootPath . '/SelectSalesOrder.php?Quotations=Quotes_Only" class="btn btn-info">' . _('Outstanding Quotations') . '</a></div>
</div>
				
			<br />';
	include('includes/footer.php');
	exit;
} elseif (DB_num_rows($result)==1){ /*There is only one order header returned - thats good! */
	$myrow = DB_fetch_array($result);
}

/*retrieve the order details from the database to print */

/* Then there's an order to print and its not been printed already (or its been flagged for reprinting/ge_Width=807;
)
LETS GO */
$PaperSize = 'A4';
include('includes/PDFStarter.php');
/*$PageNumber = 1;// RChacon: PDFStarter.php sets $PageNumber = 0.*/
$pdf->addInfo('Title', _('Customer Quotation') );
$pdf->addInfo('Subject', _('Quotation') . ' ' . $_GET['QuotationNo']);
$FontSize = 12;
$line_height = 12;// Recommended: $line_height = $x * $FontSize.

/* Now ... Has the order got any line items still outstanding to be invoiced */

$ErrMsg = _('There was a problem retrieving the quotation line details for quotation Number') . ' ' .
	$_GET['QuotationNo'] . ' ' . _('from the database');

$sql = "SELECT salesorderdetails.stkcode,
		stockmaster.description,
		stockmaster.hsn_code,
		salesorderdetails.quantity,
		salesorderdetails.qtyinvoiced,
		salesorderdetails.unitprice,
		salesorderdetails.discountpercent,
		stockmaster.taxcatid,
		salesorderdetails.narrative,
		stockmaster.decimalplaces
	FROM salesorderdetails INNER JOIN stockmaster
		ON salesorderdetails.stkcode=stockmaster.stockid
	WHERE salesorderdetails.orderno='" . $_GET['QuotationNo'] . "'";

$result=DB_query($sql, $ErrMsg);

$ListCount = 0;

if (DB_num_rows($result)>0){
	/*Yes there are line items to start the ball rolling with a page header */
	include('includes/PDFQuotationPortraitPageHeader.inc');

	$QuotationTotal = 0;
	$QuotationTotalEx = 0;
	$TaxTotal = 0;

	while ($myrow2=DB_fetch_array($result)){

        $ListCount ++;

		$YPos -= $line_height;// Increment a line down for the next line item.

		if ((mb_strlen($myrow2['narrative']) >200 AND $YPos-$line_height <= 75)
			OR (mb_strlen($myrow2['narrative']) >1 AND $YPos-$line_height <= 62)
			OR $YPos-$line_height <= 50){
		/* We reached the end of the page so finsih off the page and start a newy */
			include('includes/PDFQuotationPortraitPageHeader.inc');
		} //end if need a new page headed up

		$DisplayQty = locale_number_format($myrow2['quantity'],$myrow2['decimalplaces']);
		$DisplayPrevDel = locale_number_format($myrow2['qtyinvoiced'],$myrow2['decimalplaces']);
		$DisplayPrice = locale_number_format($myrow2['unitprice'],$myrow['currdecimalplaces']);
		$DisplayDiscount = locale_number_format($myrow2['discountpercent']*100,2) . '';
		$SubTot =  $myrow2['unitprice']*$myrow2['quantity']*(1-$myrow2['discountpercent']);
		$TaxProv = $myrow['taxprovinceid'];
		$TaxCat = $myrow2['taxcatid'];
		$Branch = $myrow['branchcode'];
		$HSNCode = $myrow2['hsn_code'];
		$sql3 = " SELECT taxauthorities.description, taxgrouptaxes.taxauthid
				FROM taxauthorities INNER JOIN taxgrouptaxes ON taxauthorities.taxid=taxgrouptaxes.taxauthid INNER JOIN custbranch
				ON taxgrouptaxes.taxgroupid=custbranch.taxgroupid
				WHERE custbranch.branchcode='" .$Branch ."'";
		$result3=DB_query($sql3, $ErrMsg);
		while ($myrow3=DB_fetch_array($result3)){
			$TaxAuth = $myrow3['taxauthid'];
			$Tname = $myrow3['description'];
		}

		$sql4 = "SELECT * FROM taxauthrates
				WHERE dispatchtaxprovince='" .$TaxProv ."'
				AND taxcatid='" .$TaxCat ."'
				AND taxauthority='" .$TaxAuth ."'";
		$result4=DB_query($sql4, $ErrMsg);
		while ($myrow4=DB_fetch_array($result4)){
			$TaxClass = 100 * $myrow4['taxrate'];
		}

		$DisplayTaxClass = $TaxClass . '%';
		$TaxAmount =  (($SubTot/100)*(100+$TaxClass))-$SubTot;
		$DisplayTaxAmount = locale_number_format($TaxAmount,$myrow['currdecimalplaces']);

		$LineTotal = $SubTot + $TaxAmount;
		$DisplayTotal = locale_number_format($LineTotal,$myrow['currdecimalplaces']);

		$FontSize = 8;// Font size for the line item.
//$pdf->setCellHeightRatio(2);
		$LeftOvers = $pdf->addText($Left_Margin-20, $YPos+$FontSize, $FontSize, $myrow2['stkcode']);
		//$LeftOvers = $pdf->addTextWrap(80, $YPos, 150, $FontSize, $myrow2['description'], 'left');
		//$pdf->MultiCell(55, 5, '[LEFT] '.$txt, 1, 'L', 1, 0, '', '', true);
	 // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
	 //$pdf->setCellPaddings(2, 4, 6, 8);
	$line_height = 32;
		$pdf->MultiCell(140, 60, $myrow2['description'], 0, 'L', 0, 0, '80', '', true);
		
		$LeftOvers = $pdf->addTextWrap(190, $YPos,95,$FontSize,$HSNCode,'right');
		$LeftOvers = $pdf->addTextWrap(220, $YPos,95,$FontSize,$DisplayQty,'right');
		$LeftOvers = $pdf->addTextWrap(275, $YPos,85,$FontSize,$DisplayPrice,'right');
		if ($DisplayDiscount > 0){
			$LeftOvers = $pdf->addTextWrap(325, $YPos,85,$FontSize,$DisplayDiscount,'right');
		}
		$LeftOvers = $pdf->addTextWrap(380, $YPos,85,$FontSize,$Tname.' - '.$DisplayTaxClass,'right');
		$LeftOvers = $pdf->addTextWrap(455, $YPos,85,$FontSize,$DisplayTaxAmount,'center');// RChacon: To review align to right.**********
		$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90, $YPos, 90, $FontSize, $DisplayTotal,'right');

		// Prints salesorderdetails.narrative:
		$FontSize2 = $FontSize*0.8;// Font size to print salesorderdetails.narrative.
		$Width2 = $Page_Width-$Right_Margin-120;// Width to print salesorderdetails.narrative.
		$LeftOvers = trim($myrow2['narrative']);
		while(mb_strlen($LeftOvers) > 1) {
			$YPos -= $FontSize2;
			if ($YPos < ($Bottom_Margin)) {// Begins new page.
				include('includes/PDFQuotationPortraitPageHeader.inc');
			}
			$LeftOvers = $pdf->addTextWrap(120, $YPos, $Width2, $FontSize2, $LeftOvers);
		}

		$QuotationTotal += $LineTotal;
		$QuotationTotalEx += $SubTot;
		$TaxTotal += $TaxAmount;

	}// Ends while there are line items to print out.

	if ((mb_strlen($myrow['comments']) >200 AND $YPos-$line_height <= 75)
		OR (mb_strlen($myrow['comments']) >1 AND $YPos-$line_height <= 62)
		OR $YPos-$line_height <= 50){
	/* We reached the end of the page so finsih off the page and start a newy */
		include('includes/PDFQuotationPortraitPageHeader.inc');
		
	} //end if need a new page headed up

	$FontSize = 8;
	$YPos -= $line_height;
	$pdf->SetFont('arsenalb');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90-655, $YPos-5, 655, $FontSize, _('Quotation Excluding Tax'),'right');
	$pdf->SetFont('arsenal');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90, $YPos-5, 90, $FontSize, locale_number_format($QuotationTotalEx,$myrow['currdecimalplaces']), 'right');
	$YPos -= $FontSize;
	$pdf->SetFont('arsenalb');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90-655, $YPos-10, 655, $FontSize, _('Total Tax'), 'right');
	$pdf->SetFont('arsenal');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90, $YPos-10, 90, $FontSize, locale_number_format($TaxTotal,$myrow['currdecimalplaces']), 'right');
	$YPos -= $FontSize;
	$pdf->SetFont('arsenalb');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90-655, $YPos-15, 655, $FontSize, _('Quotation Including Tax'),'right');
	$pdf->SetFont('arsenal');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90, $YPos-15, 90, $FontSize, locale_number_format($QuotationTotal,$myrow['currdecimalplaces']), 'right');
	

//$LeftOvers = $pdf->writeHTML('sdfsdfdf fdfd dfdfdf dfd df dfdfdfd', true, false, true, false, 'C');
	// Print salesorders.comments:
	$YPos -= $FontSize*2;
	$pdf->SetFont('arsenalb');
	$FontSize = 9;
	$pdf->addText($XPos, $YPos+$FontSize, $FontSize, _('Notes').':');
	$pdf->SetFont('arsenal', '', 9);
	$FontSize = 9;
	$Width2 = $Page_Width-$Right_Margin-120;// Width to print salesorders.comments.
	$LeftOvers = trim($myrow['comments']);
	while(mb_strlen($LeftOvers) > 1) {
		$YPos -= $FontSize;
		if ($YPos < ($Bottom_Margin)) {// Begins new page.
			include ('includes/PDFQuotationPageHeader.inc');
		}
		$LeftOvers = $pdf->addTextWrap(40, $YPos, $Width2, $FontSize, $LeftOvers);
	}
$sqlC = "SELECT companynumber FROM companies WHERE coyname='".$cm1."'";	
$resultC=DB_query($sqlC);
$row = DB_fetch_array($resultC);
$pdf->addTextWrap(0, $Bottom_Margin-3, $Page_Width, 7, 'CIN/PAN : '.$row['companynumber'], 'center');
$html1 = 'This document has been electronically generated by <a href="http://netelity.net/nerp"><b>nERP</b></a> and requires no physical signature or stamp';
$pdf->writeHTML($html1, true, false, true, false, 'C');
} /*end if there are line details to show on the quotation*/


if ($ListCount == 0){
        $Title = _('Print Quotation Error');
        include('includes/header.php');
        echo '<p class="text-danger">' .  _('There were no items on the quotation') . '. ' . _('The quotation cannot be printed').
                '</p><br /><div class="row">
<div class="col-xs-4">
<a href="' . $RootPath . '/SelectSalesOrder.php?Quotation=Quotes_only" class="btn btn-info">' .  _('Print Another Quotation').
                '</a></div>' . '<div class="col-xs-4">' .  '<a href="' . $RootPath . '/index.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> To Menu') . '</a></div></div><br />';
        include('includes/footer.php');
	exit;
} else {
    $pdf->OutputI('nERP' . '_Quotation_' . $_GET['QuotationNo'] . '_' . date('Y-m-d') . '.pdf');
    $pdf->__destruct();
}
?>
