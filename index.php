<?php

$PageSecurity=0;

include('includes/session.php');
$Title=_('Main Menu');
include('includes/header.php');


/*The module link codes are hard coded in a switch statement below to determine the options to show for each tab */
//include('includes/MainMenuLinksArray.php');

if (isset($SupplierLogin) AND $SupplierLogin==1){
	echo '<table class="table_index">
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SupplierTenders.php?TenderType=1">' . _('View or Amend outstanding offers') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SupplierTenders.php?TenderType=2">' . _('Create a new offer') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SupplierTenders.php?TenderType=3">' . _('View any open tenders without an offer') . '</a></p>
			</td>
			</tr>
		</table>';
	include('includes/footer.php');
	exit;
} elseif (isset($CustomerLogin) AND $CustomerLogin==1){
	echo '<table class="table_index">
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/CustomerInquiry.php?CustomerID=' . $_SESSION['CustomerID'] . '">' . _('Account Status') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SelectOrderItems.php?NewOrder=Yes">' . _('Place An Order') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SelectCompletedOrder.php?SelectedCustomer=' . $_SESSION['CustomerID'] . '">' . _('Order Status') . '</a></p>
			</td>
			</tr>
		</table>';

	include('includes/footer.php');
	exit;
}

if (isset($_GET['Application'])){ /*This is sent by this page (to itself) when the user clicks on a tab */
	$_SESSION['Module'] = $_GET['Application'];
}

// BEGIN MainMenuDiv ===========================================================
// Option 1:


?>
