<?php

if ($PageNumber > 1) {
	$pdf->newPage();
}

$XPos = $Page_Width / 2 - 140;
$pdf->addJpegFromFile($_SESSION['LogoFile'], $XPos + 135, 720, 0, 30);

$XPos = $XPos + 130;

$FontSize = 14;
$pdf->SetFont('arsenalb');
$pdf->addText($XPos, 780, $FontSize, _('Proforma Invoice'));
$FontSize = 10;
$pdf->SetFont('arsenalb');
$YPos = 720;
$pdf->addText($XPos, $YPos, $FontSize, $_SESSION['CompanyRecord']['coyname']);
$FontSize = 9;
$pdf->SetFont('arsenal');
$pdf->addText($XPos, $YPos - 12, $FontSize, $_SESSION['CompanyRecord']['regoffice1']);
$pdf->addText($XPos, $YPos - 21, $FontSize, $_SESSION['CompanyRecord']['regoffice2']);
$pdf->addText($XPos, $YPos - 30, $FontSize, $_SESSION['CompanyRecord']['regoffice3']);
$pdf->addText($XPos, $YPos - 39, $FontSize, $_SESSION['CompanyRecord']['regoffice4']);
$pdf->addText($XPos, $YPos - 48, $FontSize, _('Ph') . ': ' . $_SESSION['CompanyRecord']['telephone']);
$pdf->addText($XPos, $YPos - 57, $FontSize, _('Fax') . ': ' . $_SESSION['CompanyRecord']['fax']);
$pdf->addText($XPos, $YPos - 66, $FontSize, $_SESSION['CompanyRecord']['email']);


$XPos = 46;
$YPos = 760;

$FontSize = 10;
$pdf->SetFont('arsenalb');
$MyRow = array_map(html_entity_decode, $MyRow);
$pdf->addText($XPos, $YPos+10, $FontSize, _('Bill To') . ':');
$FontSize = 9;
$pdf->SetFont('arsenal');

$pdf->addText($XPos, $YPos - 3, $FontSize, $MyRow['name']);
$pdf->addText($XPos, $YPos - 15, $FontSize, $MyRow['address1']);
$pdf->addText($XPos, $YPos - 30, $FontSize, $MyRow['address2']);
$pdf->addText($XPos, $YPos - 45, $FontSize, $MyRow['address3'] . ' ' . $MyRow['address4'] . ' ' . $MyRow['address5']);



$YPos -= 80;
$FontSize = 10;
$pdf->SetFont('arsenalb');
$pdf->addText($XPos, $YPos, $FontSize, _('Delivery To') . ':');
$FontSize = 9;
$pdf->SetFont('arsenal');
$pdf->addText($XPos, $YPos - 15, $FontSize, $MyRow['deliverto']);
$pdf->addText($XPos, $YPos - 30, $FontSize, $MyRow['deladd1']);
$pdf->addText($XPos, $YPos - 45, $FontSize, $MyRow['deladd2']);
$pdf->addText($XPos, $YPos - 60, $FontSize, $MyRow['deladd3'] . ' ' . $MyRow['deladd4'] . ' ' . $MyRow['deladd5']);


$XPos = 50;
$YPos += 25;
///*draw a nice curved corner box around the delivery details */
///*from the top right */
//$pdf->partEllipse($XPos + 200, $YPos + 60, 0, 90, 0, 0);
///*line to the top left */
//$pdf->line($XPos + 210, $YPos + 67, $XPos-10, $YPos + 67);
///*Dow top left corner */
//$pdf->partEllipse($XPos, $YPos + 60, 90, 180, 0, 0);
///*Do a line to the bottom left corner */
//$pdf->line($XPos - 10, $YPos + 67, $XPos - 10, $YPos-10);
///*Now do the bottom left corner 180 - 270 coming back west*/
//$pdf->partEllipse($XPos, $YPos, 180, 270, 0, 0);
///*Now a line to the bottom right */
//$pdf->line($XPos-10, $YPos - 10, $XPos + 210, $YPos - 10);
///*Now do the bottom right corner */
//$pdf->partEllipse($XPos + 200, $YPos, 270, 360, 0, 0);
///*Finally join up to the top right corner where started */
//$pdf->line($XPos + 210, $YPos-10, $XPos + 210, $YPos + 67);


$YPos -= 90;
///*draw a nice curved corner box around the billing details */
///*from the top right */
//$pdf->partEllipse($XPos + 200, $YPos + 60, 0, 90, 0, 0);
///*line to the top left */
//$pdf->line($XPos + 210, $YPos + 67, $XPos-10, $YPos + 67);
///*Dow top left corner */
//$pdf->partEllipse($XPos, $YPos + 60, 90, 180, 0, 0);
///*Do a line to the bottom left corner */
//$pdf->line($XPos - 10, $YPos + 67, $XPos - 10, $YPos-10);
///*Now do the bottom left corner 180 - 270 coming back west*/
//$pdf->partEllipse($XPos, $YPos, 180, 270, 0, 0);
///*Now a line to the bottom right */
//$pdf->line($XPos-10, $YPos - 10, $XPos + 210, $YPos - 10);
///*Now do the bottom right corner */
//$pdf->partEllipse($XPos + 200, $YPos, 270, 360, 0, 0);
///*Finally join up to the top right corner where started */
//$pdf->line($XPos + 210, $YPos-10, $XPos + 210, $YPos + 67);

$pdf->SetFont('arsenalb');
$pdf->addText($Page_Width - $Right_Margin - 95, $Page_Height - $Top_Margin - $FontSize * 1+9, $FontSize, _('Order Number') . ': ');
$pdf->SetFont('arsenal');
$pdf->addTextWrap($Page_Width - $Right_Margin - 200, $Page_Height - $Top_Margin - $FontSize * 1, 200, $FontSize, $_GET['AcknowledgementNo'], 'right');
$pdf->SetFont('arsenalb');
$pdf->addText($Page_Width - $Right_Margin - 95, $Page_Height - $Top_Margin - $FontSize * 2.4+9, $FontSize, _('Customer P/O') . ': ');
$pdf->SetFont('arsenal');
$pdf->addTextWrap($Page_Width - $Right_Margin - 200, $Page_Height - $Top_Margin - $FontSize * 2.4, 200, $FontSize, $MyRow['customerref'], 'right');
$pdf->SetFont('arsenalb');
$pdf->addText($Page_Width - $Right_Margin - 95, $Page_Height - $Top_Margin - $FontSize * 4+9, $FontSize, _('Date') . ': ' );
$pdf->SetFont('arsenal');
$pdf->addTextWrap($Page_Width - $Right_Margin - 200, $Page_Height - $Top_Margin - $FontSize * 4, 200, $FontSize, ConvertSQLDate($MyRow['orddate']), 'right');

$pdf->addText($Page_Width / 2 - 10, $YPos + 15, $FontSize, _('All amounts stated in') . ' - ' . $MyRow['currcode']);

$YPos -= 45;
$XPos = 40;

$FontSize=9;
$pdf->SetFont('arsenalb');
$LeftOvers = $pdf->addTextWrap($XPos - 20, $YPos, 100, $FontSize, _('Item Code'), 'left');
$LeftOvers = $pdf->addTextWrap(80, $YPos, 200, $FontSize, _('Description'), 'left');
$LeftOvers = $pdf->addTextWrap(190,$YPos,105,$FontSize, _('HSN/SAC'),'left');
$LeftOvers = $pdf->addTextWrap(205, $YPos, 95, $FontSize, _('Ship Date'), 'right');
$LeftOvers = $pdf->addTextWrap(315, $YPos, 85, $FontSize, _('Qty'), 'left');
$LeftOvers = $pdf->addTextWrap(345, $YPos, 85, $FontSize, _('Price'), 'left');
$LeftOvers = $pdf->addTextWrap(395, $YPos, 55, $FontSize, _('Disc.%'),'left');
$LeftOvers = $pdf->addTextWrap(430, $YPos, 95, $FontSize, _('Tax'),'left');
$LeftOvers = $pdf->addTextWrap(480, $YPos, 65, $FontSize, _('Tax Amt'),'left');
$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90, $YPos, 90, $FontSize, _('Net'), 'right');

$pdf->line($XPos-24, $YPos-6,$Page_Width-$Right_Margin+10, $YPos-6);
$FontSize=8;
$pdf->SetFont('arsenal');

/*draw a box with nice round corner for entering line items */
/*90 degree arc at top right of box 0 degrees starts a bottom */
//$pdf->partEllipse($Page_Width - $Right_Margin - 0, $Bottom_Margin + 540, 0, 90, 0, 0);
///*line to the top left */
//$pdf->line($Page_Width - $Right_Margin + 10, $Bottom_Margin + 550, $Left_Margin -10, $Bottom_Margin + 550);
//
///*line under headings to top left */
//$pdf->line($Page_Width - $Right_Margin + 10, $Bottom_Margin + 525, $Left_Margin, $Bottom_Margin + 525);
//
//
///*Dow top left corner */
//$pdf->partEllipse($Left_Margin + 10, $Bottom_Margin + 540, 90, 180, 0, 0);
///*Do a line to the bottom left corner */
//$pdf->line($Left_Margin, $Bottom_Margin + 550, $Left_Margin, $Bottom_Margin + 0);
///*Now do the bottom left corner 180 - 270 coming back west*/
//$pdf->partEllipse($Left_Margin + 10, $Bottom_Margin + 10, 180, 270, 0, 0);
///*Now a line to the bottom right */
//$pdf->line($Left_Margin + 0, $Bottom_Margin, $Page_Width - $Right_Margin - 10, $Bottom_Margin);
///*Now do the bottom right corner */
//$pdf->partEllipse($Page_Width - $Right_Margin - 0, $Bottom_Margin + 10, 270, 360, 0, 0);
///*Finally join up to the top right corner where started */
//$pdf->line($Page_Width - $Right_Margin + 1, $Bottom_Margin + 10, $Page_Width - $Right_Margin + 0, $Bottom_Margin + 550);
//
//$YPos -= $line_height * 2;

$BoxHeight = $Page_Height-252;

// Draws a rounded rectangle around line items:
$pdf->RoundRectangle(
	$Left_Margin-20,// RoundRectangle $XPos.
	$Bottom_Margin+$BoxHeight+10,// RoundRectangle $YPos.
	$Page_Width-$Right_Margin-$Left_Margin+30,// RoundRectangle $Width.
	$BoxHeight+10,// RoundRectangle $Height.
	0,// RoundRectangle $RadiusX.
	0);// RoundRectangle $RadiusY.
$YPos -= 25;
$FontSize = 9;

?>