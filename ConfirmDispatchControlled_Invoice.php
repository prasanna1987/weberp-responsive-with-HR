<?php


include('includes/DefineCartClass.php');
include('includes/DefineSerialItems.php');
include('includes/session.php');
$Title = _('Specify Dispatched Controlled Items');

/* Session started in header.php for password checking and authorisation level check */
include('includes/header.php');


if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other order entry sessions on the same machine  */
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (isset($_GET['LineNo'])){
        $LineNo = (int)$_GET['LineNo'];
} elseif (isset($_POST['LineNo'])){
        $LineNo = (int)$_POST['LineNo'];
} else {
	echo '<div class="row"><div class="col-xs-4">
			<a href="' . $RootPath . '/ConfirmDispatch_Invoice.php" class="btn btn-info">' .  _('Select a line item to invoice') . '</a></div></div>
		
			<br />';
	echo prnMsg( _('This page can only be opened if a line item on a sales order to be invoiced has been selected') . '. ' . _('Please do that first'),'error');

	include('includes/footer.php');
	exit;
}

if (!isset($_SESSION['Items'.$identifier]) OR !isset($_SESSION['ProcessingOrder'])) {
	/* This page can only be called with a sales order number to invoice */
	echo '<div class="row">
	<div class="col-xs-4">
			<a href="' . $RootPath . '/SelectSalesOrder.php" class="btn btn-info">' .  _('Select a sales order to invoice') . '</a></div></div>
			<br />';
	echo prnMsg( _('This page can only be opened if a sales order and line item has been selected Please do that first'),'error');
	
	include('includes/footer.php');
	exit;
}


/*Save some typing by referring to the line item class object in short form */
$LineItem = &$_SESSION['Items'.$identifier]->LineItems[$LineNo];


//Make sure this item is really controlled
if ( $LineItem->Controlled != 1 ){
	echo '<div class="row"><div class="col-xs-4"><a href="' . $RootPath . '/ConfirmDispatch_Invoice.php" class="btn btn-default">' .  _('Back to the Sales Order'). '</a></div></div>';
	echo '<br />';
	echo prnMsg( _('The line item must be defined as controlled to require input of the batch numbers or serial numbers being sold'),'error');
	include('includes/footer.php');
	exit;
}

/********************************************
  Get the page going....
********************************************/
echo '<div class="row">';

echo '<div class="col-xs-4"><a href="'. $RootPath. '/ConfirmDispatch_Invoice.php?identifier=' . $identifier . '" class="btn btn-info">' .  _('Back to') . '' . _('Invoice'). '</a></div></div>';

echo '<h4 class="text-danger">' .  _('You can enter up to').' '. locale_number_format($LineItem->Quantity-$LineItem->QtyInv, $LineItem->DecimalPlaces). ' '. _('Controlled items of:').' ' . $LineItem->StockID  . ' - ' . $LineItem->ItemDescription . ' '. _('---For order No:').' ' . $_SESSION['Items'.$identifier]->OrderNo . ' '. _('---To:'). ' ' . $_SESSION['Items'.$identifier]->CustomerName . '</h4><br />
';

/** vars needed by InputSerialItem : **/
$StockID = $LineItem->StockID;
$RecvQty = $LineItem->Quantity-$LineItem->QtyInv;
$ItemMustExist = true;  /*Can only invoice valid batches/serial numbered items that exist */
$LocationOut = $_SESSION['Items'.$identifier]->Location;

if ($_SESSION['RequirePickingNote'] == 1) {
	$OrderstoPick = $_SESSION['Items'.$identifier]->OrderNo;
} else {
	unset($OrderstoPick);
}

$InOutModifier=1;
$ShowExisting=true;

include ('includes/InputSerialItems.php');

/*TotalQuantity set inside this include file from the sum of the bundles
of the item selected for dispatch */
$_SESSION['Items'.$identifier]->LineItems[$LineNo]->QtyDispatched = $TotalQuantity;

include('includes/footer.php');
exit;
?>
