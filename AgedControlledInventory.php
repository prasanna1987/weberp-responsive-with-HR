<?php


include('includes/session.php');
$PricesSecurity = 12;//don't show pricing info unless security token 12 available to user

$Today =  time();
$Title = _('Aged Controlled Inventory') . ' ' . _('as-of') . ' ' . Date(($_SESSION['DefaultDateFormat']), $Today);

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>', $Title, '</h1></a>
	</div>';

$sql = "SELECT stockserialitems.stockid,
				stockmaster.description,
				stockserialitems.serialno,
				stockserialitems.quantity,
				stockmoves.trandate,
				stockmaster.units,
				stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS cost,
				createdate,
				decimalplaces
			FROM stockserialitems
			LEFT JOIN stockserialmoves
				ON stockserialitems.serialno=stockserialmoves.serialno
			LEFT JOIN stockmoves
				ON stockserialmoves.stockmoveno=stockmoves.stkmoveno
			INNER JOIN stockmaster
				ON stockmaster.stockid = stockserialitems.stockid
			INNER JOIN locationusers
				ON locationusers.loccode=stockserialitems.loccode
				AND locationusers.userid='" .  $_SESSION['UserID'] . "'
				AND locationusers.canview=1
			WHERE quantity > 0
			ORDER BY createdate, quantity";

$ErrMsg =  _('The stock held could not be retrieved because');
$LocStockResult = DB_query($sql, $ErrMsg);
$NumRows = DB_num_rows($LocStockResult);

$TotalQty=0;
$TotalVal=0;

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
		<tr>
			<th>', _('Stock ID'), '</th>
			<th>', _('Description'), '</th>
			<th>', _('Batch'), '</th>
			<th>', _('Quantity Remaining'), '</th>
			<th>', _('Units'), '</th>
			<th>', _('Inventory Value'), '</th>
			<th>', _('Date'), '</th>
			<th>', _('Days Old'), '</th>
			</tr>
		</thead>
		<tbody>';

while ($LocQtyRow=DB_fetch_array($LocStockResult)) {

	$DaysOld = floor(($Today - strtotime($LocQtyRow['createdate']))/(60*60*24));
	$TotalQty += $LocQtyRow['quantity'];
	$DispVal =  '-----------';

	if (in_array($PricesSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PricesSecurity)) {
		$DispVal = locale_number_format(($LocQtyRow['quantity']*$LocQtyRow['cost']),$LocQtyRow['decimalplaces']);
		$TotalVal += ($LocQtyRow['quantity'] * $LocQtyRow['cost']);
	}

	printf('<tr class="striped_row">
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td class="number">%s</td>
			<td>%s</td>
			<td class="number">%s</td>
			<td>%s</td>
			<td class="number">%s</td>
		</tr>',
			mb_strtoupper($LocQtyRow['stockid']),
			$LocQtyRow['description'],
			$LocQtyRow['serialno'],
			locale_number_format($LocQtyRow['quantity'],$LocQtyRow['decimalplaces']),
			$LocQtyRow['units'],
			$DispVal,
			ConvertSQLDate($LocQtyRow['createdate']),
			$DaysOld
		);
} //while

echo '</tbody>
		<tfoot>
			<tr class="striped_row">
				<td colspan="3"><strong>', _('Total'), '</strong></td>
				<td class="number"><b>', locale_number_format($TotalQty,2), '</b></td>
				<td class="number"><b>', locale_number_format($TotalVal,2), '</b></td>
      <td colspan="2"></td>
			</tr>
		</tfoot>
	</table></div></div></div>';

include('includes/footer.php');
?>