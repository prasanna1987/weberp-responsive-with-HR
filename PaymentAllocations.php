<?php


/*
	This page is called from SupplierInquiry.php when the 'view payments' button is selected
*/

include('includes/session.php');
$Title = _('Payment Allocations');
$ViewTopic = 'AccountsPayable';
$BookMark = 'PaymentAllocations';
include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');

if (!isset($_GET['SuppID'])){
	echo  prnMsg( _('Supplier ID Number is not Set, can not display result'),'warn');
	include('includes/footer.php');
	exit;
}

if (!isset($_GET['InvID'])){
	echo  prnMsg( _('Invoice Number is not Set, can not display result'),'warn');
	include('includes/footer.php');
	exit;
}
$SuppID = $_GET['SuppID'];
$InvID = $_GET['InvID'];

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Payment Allocation for Supplier') . ': ' . $SuppID . _(' and') . ' ' . _('Invoice') . ': ' . $InvID . '</h1></a></div>';

echo '<div class="text-info">' .
		_('This shows how the payment to the supplier was allocated') . '<a href="SupplierInquiry.php?&amp;SupplierID=' . $SuppID . '">' . _('Back to supplier inquiry') . '</a>
	</div>
	<br />';

$SQL= "SELECT supptrans.supplierno,
				supptrans.suppreference,
				supptrans.trandate,
				supptrans.alloc,
				currencies.decimalplaces AS currdecimalplaces
		FROM supptrans INNER JOIN suppliers
		ON supptrans.supplierno=suppliers.supplierid
		INNER JOIN currencies
		ON suppliers.currcode=currencies.currabrev
		WHERE supptrans.id IN (SELECT suppallocs.transid_allocfrom
								FROM supptrans, suppallocs
								WHERE supptrans.supplierno = '" . $SuppID . "'
								AND supptrans.suppreference = '" . $InvID . "'
								AND supptrans.id = suppallocs.transid_allocto)";


$Result = DB_query($SQL);
if (DB_num_rows($Result) == 0){
	echo  prnMsg(_('There may be a problem retrieving the information. No data is returned'),'warn');
	echo '<br /><p align="right"><a href ="javascript:history.back()" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Back') . '</a></p><br />';
	include('includes/footer.php');
	exit;
}

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
$TableHeader = '<thead><tr>
					<th>' . _('Supplier Number') . '<br />' . _('Reference') . '</th>
					<th>' . _('Payment')  . '<br />' . _('Reference') . '</th>
					<th>' . _('Payment') . '<br />' . _('Date') . '</th>
					<th>' . _('Total Payment') . '<br />' . _('Amount') .	'</th>
				</tr></thead>';

echo $TableHeader;

$j=1;
  while ($myrow = DB_fetch_array($Result)) {

	echo '<tr class="striped_row">
		<td>' . $myrow['supplierno'] . '</td>
		<td>' . $myrow['suppreference'] . '</td>
		<td>' . ConvertSQLDate($myrow['trandate']) . '</td>
		<td class="number">' . locale_number_format($myrow['alloc'],$myrow['currdecimalplaces']) . '</td>
		</tr>';

		$j++;
		if ($j == 18){
			$j=1;
			echo $TableHeader;
		}

}
  echo '</table></div></div></div><br />';

include('includes/footer.php');
?>