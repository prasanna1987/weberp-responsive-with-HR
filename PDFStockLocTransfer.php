<?php

include('includes/session.php');
$Title = _('Stock Location Transfer Docket Error');

include('includes/PDFStarter.php');

if (isset($_POST['TransferNo'])) {
	$_GET['TransferNo']=$_POST['TransferNo'];
}

if (!isset($_GET['TransferNo'])){

	include ('includes/header.php');
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Reprint transfer docket') . '</h1></a></div>';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Transfer docket to reprint') . '</label>
				<input type="text" class="form-control" size="10" name="TransferNo" /></div>
			</div>
		';
	echo '
<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" class="btn btn-warning" name="Print" value="' . _('Print') .'" /></div>
          </div>';
    echo '</div><br />
          </form>';

	echo '<form method="post" action="' . $RootPath . '/PDFShipLabel.php">';

	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<input type="hidden" name="Type" value="Transfer" />';
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Transfer docket to reprint Shipping Labels') . '</label>
				<input type="text" class="form-control" size="10" name="ORD" /></div>
			</div>
		';
	echo '
<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" class="btn btn-warning" name="Print" value="' . _('Print Shipping Labels') .'" /> </div>
          </div>';
    echo '</div><br />
          </form>';

	include ('includes/footer.php');
	exit;
}

$pdf->addInfo('Title', _('Inventory Location Transfer BOL') );
$pdf->addInfo('Subject', _('Inventory Location Transfer BOL') . ' # ' . $_GET['TransferNo']);
$FontSize=10;
$PageNumber=1;
$line_height=30;

$ErrMsg = _('An error occurred retrieving the items on the transfer'). '.' . '<p>' .  _('This page must be called with a location transfer reference number').'.';
$DbgMsg = _('The SQL that failed while retrieving the items on the transfer was');
$sql = "SELECT loctransfers.reference,
			   loctransfers.stockid,
			   stockmaster.description,
			   loctransfers.shipqty,
			   loctransfers.recqty,
			   loctransfers.shipdate,
			   loctransfers.shiploc,
			   locations.locationname as shiplocname,
			   loctransfers.recloc,
			   locationsrec.locationname as reclocname,
			   stockmaster.decimalplaces
		FROM loctransfers
		INNER JOIN stockmaster ON loctransfers.stockid=stockmaster.stockid
		INNER JOIN locations ON loctransfers.shiploc=locations.loccode
		INNER JOIN locations AS locationsrec ON loctransfers.recloc = locationsrec.loccode
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
		INNER JOIN locationusers as locationusersrec ON locationusersrec.loccode=locationsrec.loccode AND locationusersrec.userid='" .  $_SESSION['UserID'] . "' AND locationusersrec.canview=1
		WHERE loctransfers.reference='" . $_GET['TransferNo'] . "'";

$result = DB_query($sql, $ErrMsg, $DbgMsg);

If (DB_num_rows($result)==0){

	include ('includes/header.php');
	prnMsg(_('The transfer reference selected does not appear to be set up') . ' - ' . _('enter the items to be transferred first'),'error');
	include ('includes/footer.php');
	exit;
}

$TransferRow = DB_fetch_array($result);

include ('includes/PDFStockLocTransferHeader.inc');
$line_height=30;
$FontSize=10;

do {

	$LeftOvers = $pdf->addTextWrap($Left_Margin, $YPos, 100, $FontSize, $TransferRow['stockid'], 'left');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100, $YPos, 250, $FontSize, $TransferRow['description'], 'left');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-100-100, $YPos, 100, $FontSize, locale_number_format($TransferRow['shipqty'],$TransferRow['decimalplaces']), 'right');
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-100, $YPos, 100, $FontSize, locale_number_format($TransferRow['recqty'],$TransferRow['decimalplaces']), 'right');

	$pdf->line($Left_Margin, $YPos-2,$Page_Width-$Right_Margin, $YPos-2);

	$YPos -= $line_height;

	if ($YPos < $Bottom_Margin + $line_height) {
		$PageNumber++;
		include('includes/PDFStockLocTransferHeader.inc');
	}

} while ($TransferRow = DB_fetch_array($result));
$pdf->OutputD('nERP' . '_StockLocTrfShipment_' . date('Y-m-d') . '.pdf');
$pdf->__destruct();
?>
