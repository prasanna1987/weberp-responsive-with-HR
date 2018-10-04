<?php
/*The supplier transaction uses the SuppTrans class to hold the information about the invoice
the SuppTrans class contains an array of GRNs objects - containing details of GRNs for invoicing and also
an array of GLCodes objects - only used if the AP - GL link is effective */

include('includes/DefineSuppTransClass.php');
/* Session started in header.php for password checking and authorisation level check */
include('includes/session.php');
$Title = _('Enter Supplier Invoice Against Goods Received');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '
	</h1></a></div>';

$Complete=false;
if (!isset($_SESSION['SuppTrans'])){
	echo   prnMsg(_('To enter a supplier transactions the supplier must first be selected from the supplier selection screen') . ', ' . _('then the link to enter a supplier invoice must be clicked on'),'info');
	echo '<br />
			<div class="row" align="center">
<div><a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-info">' . _('Select A Supplier') . '</a></div></div><br />';
	include('includes/footer.php');
	exit;
	/*It all stops here if there aint no supplier selected and invoice initiated ie $_SESSION['SuppTrans'] started off*/
}

/*If the user hit the Add to Invoice button then process this first before showing  all GRNs on the invoice
otherwise it wouldn't show the latest additions*/
if (isset($_POST['AddPOToTrans']) AND $_POST['AddPOToTrans']!=''){
	foreach($_SESSION['SuppTransTmp']->GRNs as $GRNTmp) { //loop around temp GRNs array
		if ($_POST['AddPOToTrans']==$GRNTmp->PONo) {
			$_SESSION['SuppTrans']->Copy_GRN_To_Trans($GRNTmp); //copy from  temp GRNs array to entered GRNs array
			$_SESSION['SuppTransTmp']->Remove_GRN_From_Trans($GRNTmp->GRNNo); //remove from temp GRNs array
		}
	}
}

if (isset($_POST['AddGRNToTrans'])){ /*adding a GRN to the invoice */
	foreach($_SESSION['SuppTransTmp']->GRNs as $GRNTmp) {
		if (isset($_POST['GRNNo_' . $GRNTmp->GRNNo])) {
			$_POST['GRNNo_' . $GRNTmp->GRNNo] = true;
		} else {
			$_POST['GRNNo_' . $GRNTmp->GRNNo] = false;
		}
		$Selected = $_POST['GRNNo_' . $GRNTmp->GRNNo];
		if ($Selected==True) {
			$_SESSION['SuppTrans']->Copy_GRN_To_Trans($GRNTmp);
			$_SESSION['SuppTransTmp']->Remove_GRN_From_Trans($GRNTmp->GRNNo);
		}
	}
}

if (isset($_POST['ModifyGRN'])){

	for ($i=0;isset($_POST['GRNNo'.$i]);$i++) { //loop through all the possible form variables where a GRNNo is in the POST variable name

		$InputError=False;
		$Hold=False;
		if (filter_number_format($_POST['This_QuantityInv'. $i]) >= ($_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->QtyRecd - $_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->Prev_QuantityInv )){
			$Complete = True;
		} else {
			$Complete = False;
		}

		if (filter_number_format($_POST['This_QuantityInv'+$i])+$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->Prev_QuantityInv-$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->QtyRecd > 0){
			echo  prnMsg(_('The quantity being invoiced is more than the outstanding quantity that was delivered. It is not possible to enter an invoice for a quantity more than was received into stock'),'warn');
			$InputError = True;
		}
		if (!is_numeric(filter_number_format($_POST['ChgPrice' . $i])) AND filter_number_format($_POST['ChgPrice' . $i])<0){
			$InputError = True;
			echo prnMsg(_('The price charged in the suppliers currency is either not numeric or negative') . '. ' . _('The goods received cannot be invoiced at this price'),'error');
		} elseif ($_SESSION['Check_Price_Charged_vs_Order_Price'] == True AND $_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->OrderPrice != 0) {
			if (filter_number_format($_POST['ChgPrice' . $i])/$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->OrderPrice > (1+ ($_SESSION['OverChargeProportion'] / 100))){
				echo  prnMsg(_('The price being invoiced is more than the purchase order price by more than') . ' ' . $_SESSION['OverChargeProportion'] . '%. ' .
				_('The system is set up to prohibit this so will put this invoice on hold until it is authorised'),'warn');
				$Hold=True;
			}
		}

		if ($InputError==False){
			$_SESSION['SuppTrans']->Modify_GRN_To_Trans($_POST['GRNNo'.$i],
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->PODetailItem,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->ItemCode,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->ItemDescription,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->QtyRecd,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->Prev_QuantityInv,
														filter_number_format($_POST['This_QuantityInv' . $i]),
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->OrderPrice,
														filter_number_format($_POST['ChgPrice' . $i]),
														$Complete,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->StdCostUnit,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->ShiptRef,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->JobRef,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->GLCode,
														$Hold,
														$_SESSION['SuppTrans']->GRNs[$_POST['GRNNo'.$i]]->SupplierRef);
		}
	}
}

if (isset($_GET['Delete'])){
	$_SESSION['SuppTransTmp']->Copy_GRN_To_Trans($_SESSION['SuppTrans']->GRNs[$_GET['Delete']]);
	$_SESSION['SuppTrans']->Remove_GRN_From_Trans($_GET['Delete']);
}


/*Show all the selected GRNs so far from the SESSION['SuppTrans']->GRNs array */

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .'" method="post">
	<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
		<tr>
			<th colspan="10"></th>
		</tr>
		<tr>
			<th>' . _('Sequence') . ' #</th>
			<th>' . _("Supplier's Ref") . '</th>
			<th>' . _('Item Code') . '</th>
			<th>' . _('Description') . '</th>
			<th>' . _('Quantity Yet To Inv') . '</th>
			<th>' . _('Quantity Inv') . '</th>
			<th>' . _('Order Price') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
			<th>' . _('Inv Price') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
			<th>' . _('Order Value') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>';

$TotalValueCharged=0;

$i=0;
foreach ($_SESSION['SuppTrans']->GRNs as $EnteredGRN){
	if ($EnteredGRN->ChgPrice > 1) {
		$DisplayPrice = locale_number_format($EnteredGRN->OrderPrice,$_SESSION['SuppTrans']->CurrDecimalPlaces);
	} else {
		$DisplayPrice = locale_number_format($EnteredGRN->OrderPrice,4);
	}

	echo '<tr>
			<td>', $EnteredGRN->GRNNo, '</td>
			<td class="text">', $EnteredGRN->SupplierRef, '</td>
			<td>', $EnteredGRN->ItemCode, '</td>
			<td class="text">', $EnteredGRN->ItemDescription, '</td>
			<td>', locale_number_format($EnteredGRN->QtyRecd - $EnteredGRN->Prev_QuantityInv,'Variable'), '</td>
			<td><input class="form-control" maxlength="10" name="This_QuantityInv', $i, '" size="11" type="text" value="', locale_number_format($EnteredGRN->This_QuantityInv, 'Variable'), '" /></td>
			<td>', $DisplayPrice, '</td>
			<td><input class="form-control" maxlength="10" name="ChgPrice', $i, '" size="11" type="text" value="', locale_number_format($EnteredGRN->ChgPrice, $_SESSION['SuppTrans']->CurrDecimalPlaces), '" /></td>
			<td>', locale_number_format($EnteredGRN->ChgPrice * $EnteredGRN->This_QuantityInv, $_SESSION['SuppTrans']->CurrDecimalPlaces), '</td>
			<td class="text"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Delete=', $EnteredGRN->GRNNo, '" class="btn btn-danger">', _('Delete'), '</a></td>
		</tr>
		<input type="hidden" name="GRNNo' . $i . '" . value="' . $EnteredGRN->GRNNo . '" />';
	$i++;
}

echo '</tbody>
	</table></div></div></div><br />
	<div class="row">
<div class="col-xs-4">
			<input type="submit" class="btn btn-info" name="ModifyGRN" value="' . _('Update') . '" />
		
	
	</div>
	
<div class="col-xs-4">
		<a href="' . $RootPath . '/SupplierInvoice.php" class="btn btn-success">' . _('Submit') . '</a></div>
	</div>
	<br />';


/* Now get all the outstanding GRNs for this supplier from the database*/

$SQL = "SELECT grnbatch,
				grnno,
				purchorderdetails.orderno,
				purchorderdetails.unitprice,
				grns.itemcode,
				grns.deliverydate,
				grns.itemdescription,
				grns.qtyrecd,
				grns.quantityinv,
				grns.stdcostunit,
				grns.supplierref,
				purchorderdetails.glcode,
				purchorderdetails.shiptref,
				purchorderdetails.jobref,
				purchorderdetails.podetailitem,
				purchorderdetails.assetid,
				stockmaster.decimalplaces
		FROM grns INNER JOIN purchorderdetails
			ON  grns.podetailitem=purchorderdetails.podetailitem
		LEFT JOIN stockmaster ON grns.itemcode=stockmaster.stockid
		WHERE grns.supplierid ='" . $_SESSION['SuppTrans']->SupplierID . "'
		AND grns.qtyrecd - grns.quantityinv > 0
		ORDER BY grns.grnno";
$GRNResults = DB_query($SQL);

if (DB_num_rows($GRNResults)==0){
	echo  prnMsg(_('There are no outstanding goods received from') . ' ' . $_SESSION['SuppTrans']->SupplierName . ' ' . _('that have not been invoiced by them') . '<br />' . _('The goods must first be received using the link below to select purchase orders to receive'),'warn');
	echo '<div class="row">
<div class="col-xs-4"><a href="' . $RootPath . '/PO_SelectOSPurchOrder.php?SupplierID=' . $_SESSION['SuppTrans']->SupplierID .'" class="btn btn-info">' . _('Select Purchase Orders to Receive')  . '</a></div></div><br />';
	include('includes/footer.php');
	exit;
}

/*Set up a table to show the GRNs outstanding for selection */
echo '
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset( $_SESSION['SuppTransTmp'])){
	$_SESSION['SuppTransTmp'] = new SuppTrans;
	while ($myrow=DB_fetch_array($GRNResults)){

		$GRNAlreadyOnInvoice = False;

		foreach ($_SESSION['SuppTrans']->GRNs as $EnteredGRN){
			if ($EnteredGRN->GRNNo == $myrow['grnno']) {
				$GRNAlreadyOnInvoice = True;
			}
		}
		if ($myrow['decimalplaces']==''){
			$myrow['decimalplaces']=2;
		}
		if ($GRNAlreadyOnInvoice == False){
			$_SESSION['SuppTransTmp']->Add_GRN_To_Trans($myrow['grnno'],
														$myrow['podetailitem'],
														$myrow['itemcode'],
														$myrow['itemdescription'],
														$myrow['qtyrecd'],
														$myrow['quantityinv'],
														$myrow['qtyrecd'] - $myrow['quantityinv'],
														$myrow['unitprice'],
														$myrow['unitprice'],
														$Complete,
														$myrow['stdcostunit'],
														$myrow['shiptref'],
														$myrow['jobref'],
														$myrow['glcode'],
														$myrow['orderno'],
														$myrow['assetid'],
														0,
														$myrow['decimalplaces'],
														$myrow['grnbatch'],
														$myrow['supplierref']);
		}
	}
}

if (!isset($_GET['Modify'])){
	if (count( $_SESSION['SuppTransTmp']->GRNs)>0){   /*if there are any outstanding GRNs then */
		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Goods Received Yet to be Invoiced From') . ' ' . $_SESSION['SuppTrans']->SupplierName . '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
					<thead>
					<tr>
						<th>' . _('Sequence') . ' #</th>
						<th>' . _('GRN Number') . '</th>
						<th>' . _('Supplier\'s Ref') . '</th>
						<th>' . _('Order') . '</th>
						<th>' . _('Item Code') . '</th>
						<th>' . _('Description') . '</th>
						<th>' . _('Total Qty Received') . '</th>
						<th>' . _('Qty Already Invoiced') . '</th>
						<th>' . _('Qty Yet To Invoice') . '</th>
						<th>' . _('Order Price in') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
						<th>' . _('Line Value in') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
						<th>' . _('Select'), '</th>
					</tr>
					</thead>
					<tbody>';
		$i = 0;
		$POs = array();
		foreach($_SESSION['SuppTransTmp']->GRNs as $GRNTmp) {
			$_SESSION['SuppTransTmp']->GRNs[$GRNTmp->GRNNo]->This_QuantityInv = $GRNTmp->QtyRecd - $GRNTmp->Prev_QuantityInv;
			if (isset($POs[$GRNTmp->PONo]) and $POs[$GRNTmp->PONo] != $GRNTmp->PONo) {
				$POs[$GRNTmp->PONo] = $GRNTmp->PONo;
				echo '<tr>
						<td><input type="submit" name="AddPOToTrans" class="btn btn-success" value="' . $GRNTmp->PONo . '" /></td>
						<td colspan="3">' . _('Add Whole PO to Invoice') . '</td>
							</tr>';
			}
			echo '<tr>
				<td>', $GRNTmp->GRNNo, '</td>
				<td>', $GRNTmp->GRNBatchNo, '</td>
				<td class="text">', $GRNTmp->SupplierRef, '</td>
				<td>', $GRNTmp->PONo, '</td>
				<td>', $GRNTmp->ItemCode, '</td>
				<td class="text">', $GRNTmp->ItemDescription, '</td>
				<td>', locale_number_format($GRNTmp->QtyRecd, $GRNTmp->DecimalPlaces), '</td>
				<td>', locale_number_format($GRNTmp->Prev_QuantityInv, $GRNTmp->DecimalPlaces), '</td>
				<td>', locale_number_format(($GRNTmp->QtyRecd - $GRNTmp->Prev_QuantityInv), $GRNTmp->DecimalPlaces), '</td>
				<td>', locale_number_format($GRNTmp->OrderPrice, $_SESSION['SuppTrans']->CurrDecimalPlaces), '</td>
				<td>', locale_number_format($GRNTmp->OrderPrice * ($GRNTmp->QtyRecd - $GRNTmp->Prev_QuantityInv), $_SESSION['SuppTrans']->CurrDecimalPlaces), '</td>
				<td class="centre"><input';
			if(isset($_POST['SelectAll'])) {
				echo ' checked';
			}
			echo ' name=" GRNNo_', $GRNTmp->GRNNo, '" type="checkbox" /></td>
				</tr>';
		}
		echo '</tbody>
			</table>
			</div></div></div></div>
			<br />
			<div class="row">
			<div class="col-xs-4">
				<input type="submit" class="btn btn-info" name="SelectAll" value="' . _('Select All') . '" />
				</div>
			<div class="col-xs-4">
			<input type="submit" class="btn btn-danger" name="DeSelectAll" value="' . _('Deselect All') . '" />
			</div>
			<div class="col-xs-4">
				<input type="submit" class="btn btn-success" name="AddGRNToTrans" value="' . _('Add to Invoice') . '" />
			</div></div><br />
';
	}
}

echo '
	</form>';
include('includes/footer.php');
?>
