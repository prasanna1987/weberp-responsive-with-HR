<?php
/*	Please note that addTextWrap prints a font-size-height further down than
	addText and other functions.*/

// $PageNumber is initialised in 0 by includes/PDFStarter.php.
$PageNumber ++;// Increments $PageNumber before printing.
if ($PageNumber>1) {// Inserts a page break if it is not the first page.
	$pdf->newPage();
}

// Prints company logo:
$XPos = $Page_Width/2 - 140;
$pdf->addJpegFromFile($_SESSION['LogoFile'], $XPos + 135, 768, 0, 30);

$XPos = $XPos + 130;
// Prints 'Quotation' title:
$pdf->SetFont('arsenalb');
$FontSize = 14;
$pdf->addTextWrap(10, $Page_Height-$Top_Margin-5, $Page_Width+15, 18, _('Quotation'), 'center');

// Prints company info:
$XPos = $Page_Width/2+$Left_Margin-50;
$YPos = 758;
$FontSize = 9;
$pdf->SetFont('arsenalb');
$pdf->addText($XPos, $YPos, $FontSize, $_SESSION['CompanyRecord']['coyname']);
$YPos -= $FontSize;
$FontSize = 9;
$pdf->SetFont('arsenal');
$pdf->addText($XPos, $YPos, $FontSize, $_SESSION['CompanyRecord']['regoffice1']);
$pdf->addText($XPos, $YPos-$FontSize*1, $FontSize, $_SESSION['CompanyRecord']['regoffice2']);
$pdf->addText($XPos, $YPos-$FontSize*2, $FontSize, $_SESSION['CompanyRecord']['regoffice3']);
$pdf->addText($XPos, $YPos-$FontSize*3, $FontSize, $_SESSION['CompanyRecord']['regoffice4']);
$pdf->addText($XPos, $YPos-$FontSize*4, $FontSize, $_SESSION['CompanyRecord']['regoffice5'] .
	' ' . $_SESSION['CompanyRecord']['regoffice6']);
$pdf->addText($XPos, $YPos-$FontSize*5, $FontSize,  _('Ph') . ': ' . $_SESSION['CompanyRecord']['telephone'] .
	' ' . _('Fax'). ': ' . $_SESSION['CompanyRecord']['fax']);
$pdf->addText($XPos, $YPos-$FontSize*6, $FontSize, $_SESSION['CompanyRecord']['email']);

// Prints 'Delivery To' info:
$XPos = 46;
$YPos = 770;
$FontSize = 10;
$pdf->SetFont('arsenalb');
$myrow = array_map(html_entity_decode, $myrow);
$pdf->addText($XPos, $YPos+10,$FontSize, _('Quotation For').':' );
$FontSize = 9;
$pdf->SetFont('arsenal');

$pdf->addText($XPos, $YPos-3,$FontSize, $myrow['name']);
$pdf->addText($XPos, $YPos-15,$FontSize, $myrow['address1']);
$pdf->addText($XPos, $YPos-30,$FontSize, $myrow['address2']);
$pdf->addText($XPos, $YPos-45,$FontSize, $myrow['address3'] . ' ' . $myrow['address4'] . ' ' . $myrow['address5']);



// Prints 'Quotation For' info:
$YPos -= 80;
$FontSize = 10;
$pdf->SetFont('arsenalb');
$pdf->addText($XPos, $YPos,$FontSize, _('Delivery To').':');
$FontSize = 9;
$pdf->SetFont('arsenal');
$pdf->addText($XPos, $YPos- 15,$FontSize, $myrow['deliverto']);
$pdf->addText($XPos, $YPos-30,$FontSize, $myrow['deladd1']);
$pdf->addText($XPos, $YPos-45,$FontSize, $myrow['deladd2']);
$pdf->addText($XPos, $YPos-60,$FontSize, $myrow['deladd3'] . ' ' . $myrow['deladd4'] . ' ' . $myrow['deladd5']);


// Draws a box with round corners around 'Delivery To' info:
$XPos = 50;
$YPos += 25;
//$pdf->RoundRectangle(
//	$XPos-10,// RoundRectangle $XPos.
//	$YPos+60+10,// RoundRectangle $YPos.
//	200+10+10,// RoundRectangle $Width.
//	60+10+10,// RoundRectangle $Height.
//	0,// RoundRectangle $RadiusX.
//	0);// RoundRectangle $RadiusY.

// Draws a box with round corners around around 'Quotation For' info:
$YPos -= 90;
//$pdf->RoundRectangle(
//	$XPos-10,// RoundRectangle $XPos.
//	$YPos+60+10,// RoundRectangle $YPos.
//	200+10+10,// RoundRectangle $Width.
//	60+10+10,// RoundRectangle $Height.
//	0,// RoundRectangle $RadiusX.
//	0);// RoundRectangle $RadiusY.

// Prints quotation info:



$pdf->SetFont('arsenalb');
$pdf->addText($Page_Width-$Right_Margin-100, $Page_Height-$Top_Margin-$FontSize*1+9, $FontSize, _('Number'). ' : ');
$pdf->SetFont('arsenal');
$pdf->addTextWrap($Page_Width-$Right_Margin-200, $Page_Height-$Top_Margin-$FontSize*1, 200, $FontSize, $_GET['QuotationNo'], 'right');

$pdf->SetFont('arsenalb');
$pdf->addText($Page_Width-$Right_Margin-100, $Page_Height-$Top_Margin-$FontSize*2.4+9, $FontSize, _('Date'). ': ');
$pdf->SetFont('arsenal');
$pdf->addTextWrap($Page_Width-$Right_Margin-200, $Page_Height-$Top_Margin-$FontSize*2.4, 200, $FontSize, ConvertSQLDate($myrow['quotedate']), 'right');
$pdf->SetFont('arsenalb');
$pdf->addText($Page_Width-$Right_Margin-100, $Page_Height-$Top_Margin-$FontSize*3.8+9, $FontSize, _('Page').': ');
$pdf->SetFont('arsenal');
$pdf->addTextWrap($Page_Width-$Right_Margin-200, $Page_Height-$Top_Margin-$FontSize*3.8, 200, $FontSize, $PageNumber, 'right');

$pdf->SetFont('arsenalb');
$pdf->addText($Page_Width-$Right_Margin-100, $Page_Height-$Top_Margin-$FontSize*5.8+9, $FontSize, _('Your Ref'). ' : ');
$pdf->SetFont('arsenal');
$pdf->addTextWrap($Page_Width-$Right_Margin-200, $Page_Height-$Top_Margin-$FontSize*5.8, 200, $FontSize, $myrow['customerref'], 'right');

$FontSize=8;

// Prints the currency name:
require_once('includes/CurrenciesArray.php');// To get the currency name from the currency code.
$pdf->addText($Page_Width/2+$Left_Margin+25, $YPos-15, $FontSize,
	_('All amounts stated in') . ' ' . $myrow['currcode'] . ' - ' . $CurrencyName[$myrow['currcode']]);

// Prints table header:
$YPos -= 45;
$XPos = 40;
$FontSize=9;
$pdf->SetFont('arsenalb');
$LeftOvers = $pdf->addTextWrap($Left_Margin-20,$YPos,100,$FontSize, _('Item Code'));
$LeftOvers = $pdf->addTextWrap(80,$YPos,200,$FontSize, _('Description'),'left');
$LeftOvers = $pdf->addTextWrap(160,$YPos,105,$FontSize, _('HSN/SAC'),'right');
$LeftOvers = $pdf->addTextWrap(216,$YPos,105,$FontSize, _('Qty'),'right');
$LeftOvers = $pdf->addTextWrap(275,$YPos,85,$FontSize,_('Price'),'right');
$LeftOvers = $pdf->addTextWrap(330,$YPos,85,$FontSize, _('Disc.%'),'right');
$LeftOvers = $pdf->addTextWrap(370,$YPos,140,$FontSize, _('Tax'),'center');
$LeftOvers = $pdf->addTextWrap(440,$YPos,85,$FontSize, _('Tax Amount'),'right');
$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-90, $YPos, 90, $FontSize, _('Net'),'right');

// Draws a box with round corners around line items:
$pdf->RoundRectangle(
	$Left_Margin-20,// RoundRectangle $XPos.
	$YPos+$FontSize+5,// RoundRectangle $YPos.
	$Page_Width-$Left_Margin-$Right_Margin+30,// RoundRectangle $Width.
	$YPos+$FontSize-$Bottom_Margin,// RoundRectangle $Height.
	0,// RoundRectangle $RadiusX.
	0);// RoundRectangle $RadiusY.

// Line under table headings:
$LineYPos = $YPos - $FontSize -1;
$pdf->line($Page_Width-$Right_Margin+10, $LineYPos, $Left_Margin-20, $LineYPos);

$YPos -= $FontSize;// This is to use addTextWrap's $YPos instead of normal $YPos.
$FontSize=9;
$pdf->SetFont('arsenal');
?>
