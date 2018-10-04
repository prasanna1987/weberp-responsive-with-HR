<?php
/* Prints an acknowledgement */

include('includes/session.php');
include('includes/SQL_CommonFunctions.inc');
$cm1 = $_SESSION['CompanyRecord']['coyname'];
//Get Out if we have no order number to work with
If (!isset($_GET['AcknowledgementNo']) || $_GET['AcknowledgementNo'] == "") {
	$Title = _('Select Acknowledgement To Print');
	include('includes/header.php');
	prnMsg(_('Select a Acknowledgement to Print before calling this page'), 'error');
	echo '<div class="row">
				<div class="col-xs-4"><a href="' . $RootPath . '/SelectSalesOrder.php?Acknowledgements=Quotes_Only" class="btn btn-info">' . _('Acknowledgements') . '</a></div>
						</div><br />';
	include('includes/footer.php');
	exit();
}

/*retrieve the order details from the database to print */
$ErrMsg = _('There was a problem retrieving the Acknowledgement header details for Order Number') . ' ' . $_GET['AcknowledgementNo'] . ' ' . _('from the database');

$SQL = "SELECT salesorders.customerref,
				salesorders.comments,
				salesorders.orddate,
				salesorders.deliverto,
				salesorders.deladd1,
				salesorders.deladd2,
				salesorders.deladd3,
				salesorders.deladd4,
				salesorders.deladd5,
				salesorders.deladd6,
				salesorders.freightcost,
				debtorsmaster.debtorno,
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
				salesorders.branchcode,
				locations.taxprovinceid,
				locations.locationname,
				currencies.decimalplaces AS currdecimalplaces
			FROM salesorders
			INNER JOIN debtorsmaster
				ON salesorders.debtorno=debtorsmaster.debtorno
			INNER JOIN shippers
				ON salesorders.shipvia=shippers.shipper_id
			INNER JOIN locations
				ON salesorders.fromstkloc=locations.loccode
			INNER JOIN currencies
				ON debtorsmaster.currcode=currencies.currabrev
				AND salesorders.orderno='" . $_GET['AcknowledgementNo'] . "'";

$Result = DB_query($SQL, $ErrMsg);

//If there are no rows, there's a problem.
if (DB_num_rows($Result) == 0) {
	$Title = _('Print Acknowledgement Error');
	include('includes/header.php');
	prnMsg(_('Unable to Locate Acknowledgement Number') . ' : ' . $_GET['AcknowledgementNo'] . ' ', 'error');
	echo '<div class="row">
				<div class="col-xs-4"><a href="' . $RootPath . '/SelectSalesOrder.php?Acknowledgements=Quotes_Only" class="btn btn-info">' . _('Outstanding Acknowledgements') . '</a></div></div>
				<br />
			';
	include('includes/footer.php');
	exit;
} elseif (DB_num_rows($Result) == 1) {
	/*There is only one order header returned - thats good! */
	$MyRow = DB_fetch_array($Result);
}

/*retrieve the order details from the database to print */

/* Then there's an order to print and its not been printed already (or its been flagged for reprinting/ge_Width=807;
)
LETS GO */
$Terms = $_SESSION['TermsAndConditions'];

$PaperSize = 'Letter';

include('includes/PDFStarter.php');
$pdf->addInfo('Title', _('Customer Acknowledgement'));
$pdf->addInfo('Subject', _('Acknowledgement') . ' ' . $_GET['AcknowledgementNo']);
$FontSize = 12;
$PageNumber = 1;
$line_height = $FontSize * 1.25;

/* Now ... Has the order got any line items still outstanding to be invoiced */

$ErrMsg = _('There was a problem retrieving the Acknowledgement line details for Acknowledgement Number') . ' ' . $_GET['AcknowledgementNo'] . ' ' . _('from the database');

$SQL = "SELECT salesorderdetails.stkcode,
		stockmaster.description,
		stockmaster.hsn_code,
		salesorderdetails.quantity,
		salesorderdetails.qtyinvoiced,
		salesorderdetails.discountpercent,
		salesorderdetails.unitprice,
		salesorderdetails.itemdue,
		salesorderdetails.narrative,
		stockmaster.taxcatid,
		salesorderdetails.narrative,
		stockmaster.decimalplaces,
		custitem.cust_part,
		custitem.cust_description
	FROM salesorderdetails
	INNER JOIN stockmaster
		ON salesorderdetails.stkcode=stockmaster.stockid
	LEFT OUTER JOIN custitem
		ON custitem.debtorno='" . $MyRow['debtorno'] . "'
		AND custitem.stockid=stockmaster.stockid
	WHERE salesorderdetails.orderno='" . $_GET['AcknowledgementNo'] . "'";

$Result = DB_query($SQL, $ErrMsg);

$ListCount = 0;

if (DB_num_rows($Result) > 0) {
	/*Yes there are line items to start the ball rolling with a page header */
	include('includes/PDFAckPageHeader.php');

	$AcknowledgementTotal = $MyRow['freightcost'];
	$AcknowledgementTotalEx = 0;
	$TaxTotal = 0;

	while ($MyRow2 = DB_fetch_array($Result)) {

		$ListCount++;

		if ((mb_strlen($MyRow2['narrative']) > 200 AND $YPos - $line_height <= 75) OR (mb_strlen($MyRow2['narrative']) > 1 AND $YPos - $line_height <= 62) OR $YPos - $line_height <= 50) {
			/* We reached the end of the page so finsih off the page and start a newy */
			$PageNumber++;
			include('includes/PDFAckPageHeader.php');

		} //end if need a new page headed up

		$DisplayQty = locale_number_format($MyRow2['quantity'], $MyRow2['decimalplaces']);
		$DisplayPrevDel = locale_number_format($MyRow2['qtyinvoiced'], $MyRow2['decimalplaces']);
		$DisplayDiscount = locale_number_format($MyRow2['discountpercent']*100,2) . '';
		//$DisplayPrice = locale_number_format($MyRow2['unitprice'],$MyRow['currdecimalplaces']);
		$DisplayPrice = locale_number_format($MyRow2['unitprice'], $MyRow['currdecimalplaces']);
		$SubTot = $MyRow2['unitprice'] * $MyRow2['quantity'] * (1 - $MyRow2['discountpercent']);
		$SubTot1 = locale_number_format($SubTot,$MyRow2['decimalplaces']);
		$TaxProv = $MyRow['taxprovinceid'];
		$TaxCat = $MyRow2['taxcatid'];
		$Branch = $MyRow['branchcode'];
		$HSNCode = $MyRow2['hsn_code'];
		$SQL3 = " SELECT taxauthorities.description,taxgrouptaxes.taxauthid
						FROM taxauthorities INNER JOIN taxgrouptaxes ON taxauthorities.taxid=taxgrouptaxes.taxauthid
						INNER JOIN custbranch
							ON taxgrouptaxes.taxgroupid=custbranch.taxgroupid
						WHERE custbranch.branchcode='" . $Branch . "'";
		$Result3 = DB_query($SQL3, $ErrMsg);
		while ($MyRow3 = DB_fetch_array($Result3)) {
			$TaxAuth = $MyRow3['taxauthid'];
			$Tname = $MyRow3['description'];
		}

		$SQL4 = "SELECT taxrate
					FROM taxauthrates
					WHERE dispatchtaxprovince='" . $TaxProv . "'
						AND taxcatid='" . $TaxCat . "'
						AND taxauthority='" . $TaxAuth . "'";
		$Result4 = DB_query($SQL4, $ErrMsg);
		while ($MyRow4 = DB_fetch_array($Result4)) {
			$TaxClass = 100 * $MyRow4['taxrate'];
		}

		$DisplayTaxClass = $TaxClass . "%";
		$TaxAmount = (($SubTot / 100) * (100 + $TaxClass)) - $SubTot;
		$DisplayTaxAmount = locale_number_format($TaxAmount, $MyRow['currdecimalplaces']);

		$LineTotal = $SubTot + $TaxAmount;
		$DisplayTotal = locale_number_format($LineTotal, $MyRow['currdecimalplaces']);

		$FontSize = 8;

		$LeftOvers = $pdf->addText($Left_Margin-20, $YPos+$FontSize, $FontSize, $MyRow2['stkcode']);
	     $line_height = 32;
		$pdf->MultiCell(110, 60, $MyRow2['description'], 0, 'L', 0, 0, '80', '', true);
	
		$LeftOvers = $pdf->addTextWrap(190, $YPos,95,$FontSize,$HSNCode,'left');
		$LeftOvers = $pdf->addTextWrap(210, $YPos, 95, $FontSize, ConvertSQLDate($MyRow2['itemdue']), 'right');
		$LeftOvers = $pdf->addTextWrap(315, $YPos, 85, $FontSize, $DisplayQty, 'left');
		$LeftOvers = $pdf->addTextWrap(345, $YPos, 85, $FontSize, $DisplayPrice, 'left');
		$LeftOvers = $pdf->addTextWrap(395, $YPos, 55, $FontSize, $DisplayDiscount,'left');
		$LeftOvers = $pdf->addTextWrap(430, $YPos, 95, $FontSize, $Tname.' - '.$DisplayTaxClass,'left');
		$LeftOvers = $pdf->addTextWrap(480, $YPos, 65, $FontSize, $DisplayTaxAmount,'left');
		$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90, $YPos, 90, $FontSize, $SubTot1, 'right');

		if ($MyRow2['cust_part'] > '') {
			$YPos -= $line_height;
			$LeftOvers = $pdf->addTextWrap($XPos + 10, $YPos, 300, $FontSize, _('Customer Part') . ': ' . $MyRow2['cust_part'] . ' ' . $MyRow2['cust_description']);
			//$LeftOvers = $pdf->addTextWrap(190,$YPos,186,$FontSize,$MyRow2['cust_description']);
		}

		// Prints salesorderdetails.narrative
		$Split = explode("\r\n", wordwrap($MyRow2['narrative'], 130, "\r\n"));
		foreach ($Split as $TextLine) {
			$YPos -= $line_height; // rchacon's suggestion: $YPos -= $FontSize;
			if ($YPos < ($Bottom_Margin + $line_height)) { // Begins new page
				$PageNumber++;
				include('includes/PDFAckPageHeader.php');
			}
			$LeftOvers = $pdf->addTextWrap($XPos + 1, $YPos, 750, 10, $TextLine);
		}
		$YPos -= $line_height;

		$AcknowledgementTotal += $LineTotal;
		$AcknowledgementTotalEx += $SubTot;
		$TaxTotal += $TaxAmount;

		/*increment a line down for the next line item */
		$YPos -= ($line_height);

	} //end while there are line items to print out
	if ((mb_strlen($MyRow['comments']) > 200 AND $YPos - $line_height <= 75) OR (mb_strlen($MyRow['comments']) > 1 AND $YPos - $line_height <= 62) OR $YPos - $line_height <= 50) {
		/* We reached the end of the page so finsih off the page and start a newy */
		$PageNumber++;
		include('includes/PDFAckPageHeader.php');
	} //end if need a new page headed up
$pdf->SetFont('arsenalb');
	$LeftOvers = $pdf->addTextWrap($XPos, $YPos - 80, 30, 10, _('Notes:'));
	$pdf->SetFont('arsenal');
	$LeftOvers = $pdf->addText($XPos, $YPos - 95, 10, $MyRow['comments']);

	if (mb_strlen($LeftOvers) > 1) {
		$YPos -= 10;
		$LeftOvers = $pdf->addTextWrap($XPos, $YPos, 700, 10, $LeftOvers);
		if (mb_strlen($LeftOvers) > 1) {
			$YPos -= 10;
			$LeftOvers = $pdf->addTextWrap($XPos, $YPos, 700, 10, $LeftOvers);
			if (mb_strlen($LeftOvers) > 1) {
				$YPos -= 10;
				$LeftOvers = $pdf->addTextWrap($XPos, $YPos, 700, 10, $LeftOvers);
				if (mb_strlen($LeftOvers) > 1) {
					$YPos -= 10;
					$LeftOvers = $pdf->addTextWrap($XPos, $YPos, 10, $FontSize, $LeftOvers);
				}
			}
		}
	}
	$YPos -= ($line_height);
	$pdf->SetFont('arsenalb');
	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90 - 655, $YPos, 655, $FontSize, _('Total Excluding Tax'), 'right');
	$pdf->SetFont('arsenal');
	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90, $YPos, 90, $FontSize, locale_number_format($AcknowledgementTotalEx, $MyRow['currdecimalplaces']), 'right');
	$YPos -= 12;
	$pdf->SetFont('arsenalb');
	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90 - 655, $YPos, 655, $FontSize, _('Tax'), 'right');
	$pdf->SetFont('arsenal');
	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90, $YPos, 90, $FontSize, locale_number_format($TaxTotal, $MyRow['currdecimalplaces']), 'right');
	//$YPos -= 12;
//	$pdf->SetFont('arsenalb');
//	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90 - 655, $YPos, 655, $FontSize, _('Freight'), 'right');
//	$pdf->SetFont('arsenal');
//	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90, $YPos, 90, $FontSize, locale_number_format($MyRow['freightcost'], $MyRow['currdecimalplaces']), 'right');
	$YPos -= 12;
	$pdf->SetFont('arsenalb');
	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90 - 655, $YPos, 655, $FontSize, _('Total Including Tax'), 'right');
	$pdf->SetFont('arsenal');
	$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90, $YPos, 90, $FontSize, locale_number_format($AcknowledgementTotal, $MyRow['currdecimalplaces']), 'right');

	//now print T&C
	//$PageNumber++;
	//include ('includes/PDFAckPageHeader.php');
	//$LeftOvers = $pdf->addTextWrap($XPos, $YPos,700,$FontSize, $Terms, 'left');

	//while (mb_strlen($LeftOvers) > 1) {
	//$YPos -= $line_height;
	//check page break here
	//$LeftOvers = $pdf->addTextWrap($XPos, $YPos,700,$FontSize, $LeftOvers, 'left');
	//}
$sqlC = "SELECT companynumber FROM companies WHERE coyname='".$cm1."'";	
$resultC=DB_query($sqlC);
$row = DB_fetch_array($resultC);
$pdf->addTextWrap(0, $Bottom_Margin-7, $Page_Width, 7, 'CIN/PAN : '.$row['companynumber'], 'center');
$html1 = 'This document has been electronically generated by <a href="http://netelity.net/nerp"><b>nERP</b></a> and requires no physical signature or stamp';
$pdf->writeHTML($html1, true, false, true, false, 'C');
}
/*end if there are line details to show on the Acknowledgement*/


if ($ListCount == 0) {
	$Title = _('Print Acknowledgement Error');
	include('includes/header.php');
	echo '<p>' . _('There were no items on the Acknowledgement') . '. ' . _('The Acknowledgement cannot be printed') . '</p><br />
	<div class="row">
				<div class="col-xs-6"><a href="' . $RootPath . '/SelectSalesOrder.php?Acknowledgement=Quotes_only" class="btn btn-info">' . _('Print Another Acknowledgement') . '</a></div>' . '<div class="col-xs-6">' . '<a href="' . $RootPath . '/menu_data.php?Application=Sales" class="btn btn-default">' . _('Back to Menu') . '</a></div><br />';
	include('includes/footer.php');
	exit;
} else {
	$pdf->OutputI($_SESSION['DatabaseName'] . '_Acknowledgement_' . date('Y-m-d') . '.pdf');
	$pdf->__destruct();
}
?>
