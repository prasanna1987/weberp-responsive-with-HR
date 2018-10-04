<?php


include('includes/DefineSerialItems.php');
include('includes/DefineStockAdjustment.php');
include('includes/session.php');

$Title = _('Adjusting Controlled Items');
/* Session started in header.php for password checking and authorisation level check */
include('includes/header.php');

if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other stock adjustment sessions on the same machine  */
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (!isset($_SESSION['Adjustment'.$identifier])) {
	/* This page can only be called when a stock adjustment is pending */
	echo '<div class="row"><div class="col-xs-4"><a href="' . $RootPath . '/StockAdjustments.php?NewAdjustment=Yes" class="btn btn-info">' .  _('Enter A Stock Adjustment'). '</a></div></div><br />';
	echo prnMsg( _('This page can only be opened if a stock adjustment for a controlled item has been entered') . '<br />','error');
	echo '</div>';
	include('includes/footer.php');
	exit;
}
if (isset($_SESSION['Adjustment'.$identifier])){
	if (isset($_GET['AdjType']) and $_GET['AdjType']!=''){
		$_SESSION['Adjustment'.$identifier]->AdjustmentType = $_GET['AdjType'];
	}
}

/*Save some typing by referring to the line item class object in short form */
$LineItem = $_SESSION['Adjustment'.$identifier];

//Make sure this item is really controlled
if ( $LineItem->Controlled != 1 ){
	echo '<div class="row"><div class="col-xs-4"><a href="' . $RootPath . '/StockAdjustments.php?NewAdjustment=Yes">' . _('Enter A Stock Adjustment') . '</a></div></div><br />';
	echo prnMsg('<br />' .  _('Notice') . ' - ' . _('The adjusted item must be defined as controlled to require input of the batch numbers or serial numbers being adjusted'),'error');
	include('includes/footer.php');
	exit;
}

/*****  get the page going now... *****/

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' .  _('Adjustment of controlled item:').' ' . $LineItem->StockID  . ' - ' . $LineItem->ItemDescription.'</h1></a></div>' ;
echo '<div class="row" align="left">';

echo '<a href="'.$RootPath.'/StockAdjustments.php?identifier='.$identifier.'" class="btn btn-default">' . _('Back to Adjustment ') . '</a></div><br /><br />';


/** vars needed by InputSerialItem : **/
$LocationOut = $_SESSION['Adjustment'.$identifier]->StockLocation;
$StockID = $LineItem->StockID;
if ($LineItem->AdjustmentType == 'ADD'){
	echo '<h4><strong>' .  _('Adding Items').'...';
	$ItemMustExist = false;
	$InOutModifier = 1;
	$ShowExisting = false;
} elseif  ($LineItem->AdjustmentType == 'REMOVE'){
	echo '' . _('Removing Items').'...';
	$ItemMustExist = true;
	$InOutModifier = -1;
	$ShowExisting = true;
} else {
	echo prnMsg( _('The Adjustment Type needs to be set') . '. ' . _('Please try again'). '.' );
	include('includes/footer.php');
	exit;
}
echo '</strong></h4>';
include ('includes/InputSerialItems.php');

/*TotalQuantity set inside this include file from the sum of the bundles
of the item selected for adjusting */
$_SESSION['Adjustment'.$identifier]->Quantity = $TotalQuantity;

/*Also a multi select box for adding bundles to the adjustment without keying, showing only when keying */
include('includes/footer.php');
?>
