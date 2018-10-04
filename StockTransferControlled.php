<?php

include('includes/DefineSerialItems.php');
include('includes/DefineStockTransfers.php');

include('includes/session.php');
$Title = _('Transfer Controlled Items');

/* Session started in session.php for password checking and authorisation level check */

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . $Title . '</h1></a></div>';

if (!isset($_SESSION['Transfer'])) {
	/* This page can only be called when a stock Transfer is pending */
	echo '<div class="row" align="center"><a href="' . $RootPath . '/StockTransfers.php?NewTransfer=Yes" class="btn btn-info">' . _('Enter A Stock Transfer') . '</a></div><br />';
	echo  prnMsg( _('This page can only be opened if a Stock Transfer for a Controlled Item has been initiated'),'error');
	echo '</div>';
	include('includes/footer.php');
	exit;
}


if (isset($_GET['TransferItem'])){
	$TransferItem = $_GET['TransferItem'];
	$_SESSION['TransferItem'] = $_GET['TransferItem'];
} elseif (isset($_SESSION['TransferItem'])){
	$TransferItem = $_SESSION['TransferItem'];
}


/*Save some typing by referring to the line item class object in short form */
if (isset($TransferItem)){ /*we are in a bulk transfer */
	$LineItem = &$_SESSION['Transfer']->TransferItem[$TransferItem];
} else { /*we are in an individual transfer */
	$LineItem = &$_SESSION['Transfer']->TransferItem[0];
}

//Make sure this item is really controlled
if ($LineItem->Controlled != 1 ){
	if (isset($TransferItem)){
		echo '<div class="row" align="center"><a href="' . $RootPath . '/StockLocTransferReceive.php" class="btn btn-info">' . _('Receive A Stock Transfer') . '</a></div><br />';
	} else {
		echo '<div class="row" align="center"><a href="' . $RootPath . '/StockTransfers.php?NewTransfer=Yes" class="btn btn-info">' . _('Enter A Stock Transfer') . '</a></div>';
	}
	echo  prnMsg(_('Notice') . ' - ' . _('The transferred item must be defined as controlled to require input of the batch numbers or serial numbers being transferred'),'error');
	include('includes/footer.php');
	exit;
}



if (isset($TransferItem)){

	echo '<h4 class="text-info">'._('Transfer Items is set equal to') . ' ' . $TransferItem .'</h4>';

	echo '<br /><p align="right">
			<a href="'.$RootPath.'/StockLocTransferReceive.php?StockID='.$LineItem->StockID.'" class="btn btn-default">' . _('Back To Transfer Screen') . '</a></p><br />';
} else {
	echo '<br /><p align="right">
			<a href="'.$RootPath.'/StockTransfers.php?StockID='.$LineItem->StockID. '" class="btn btn-default">' . _('Back To Transfer Screen') . '</a></p><br />';
}

echo '<h4 class="sub-header">
	' .  _('Transfer of controlled item'). ' ' . $LineItem->StockID  . ' - ' . $LineItem->ItemDescription . '</h4>
	<br />';

/** vars needed by InputSerialItem : **/
$LocationOut = $_SESSION['Transfer']->StockLocationFrom;
$ItemMustExist = true;
$StockID = $LineItem->StockID;
$InOutModifier=1;
$ShowExisting = true;
if (isset($TransferItem)){
	$LineNo=$TransferItem;
} else {
	$LineNo=0;
}

include ('includes/InputSerialItems.php');

/*TotalQuantity set inside this include file from the sum of the bundles
of the item selected for adjusting */
$LineItem->Quantity = $TotalQuantity;

/*Also a multi select box for adding bundles to the Transfer without keying */

include('includes/footer.php');
exit;
?>