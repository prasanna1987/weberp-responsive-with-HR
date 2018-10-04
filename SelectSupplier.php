<?php
/* Selects a supplier. A supplier is required to be selected before any AP transactions and before any maintenance or inquiry of the supplier */

include('includes/session.php');
$Title = _('Search Suppliers');
$ViewTopic = 'AccountsPayable';
$BookMark = 'SelectSupplier';
include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['SupplierID'])) {
	$_SESSION['SupplierID']=$_GET['SupplierID'];
}
if (isset($_POST['Select'])) { /*User has hit the button selecting a supplier */
	$_SESSION['SupplierID'] = $_POST['Select'];
	unset($_POST['Select']);
	unset($_POST['Keywords']);
	unset($_POST['SupplierCode']);
	unset($_POST['Search']);
	unset($_POST['Go']);
	unset($_POST['Next']);
	unset($_POST['Previous']);
}
// only get geocode information if integration is on, and supplier has been selected
if ($_SESSION['geocode_integration'] == 1 AND isset($_SESSION['SupplierID'])) {
	$sql = "SELECT * FROM geocode_param WHERE 1";
	$ErrMsg = _('An error occurred in retrieving the information');;
	$result = DB_query($sql, $ErrMsg);
	$myrow = DB_fetch_array($result);
	$sql = "SELECT suppliers.supplierid,
				suppliers.lat,
				suppliers.lng
			FROM suppliers
			WHERE suppliers.supplierid = '" . $_SESSION['SupplierID'] . "'
			ORDER BY suppliers.supplierid";
	$ErrMsg = _('An error occurred in retrieving the information');
	$result2 = DB_query($sql, $ErrMsg);
	$myrow2 = DB_fetch_array($result2);
	$lat = $myrow2['lat'];
	$lng = $myrow2['lng'];
	$api_key = $myrow['geocode_key'];
	$center_long = $myrow['center_long'];
	$center_lat = $myrow['center_lat'];
	$map_height = $myrow['map_height'];
	$map_width = $myrow['map_width'];
	$map_host = $myrow['map_host'];
	echo '<script src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=' . $api_key . '"';
	echo ' type="text/javascript"></script>';
	echo ' <script type="text/javascript">';
	echo 'function load() {
		if (GBrowserIsCompatible()) {
			var map = new GMap2(document.getElementById("map"));
			map.addControl(new GSmallMapControl());
			map.addControl(new GMapTypeControl());';
	echo 'map.setCenter(new GLatLng(' . $lat . ', ' . $lng . '), 11);';
	echo 'var marker = new GMarker(new GLatLng(' . $lat . ', ' . $lng . '));';
	echo 'map.addOverlay(marker);
			GEvent.addListener(marker, "click", function() {
			marker.openInfoWindowHtml(WINDOW_HTML);
			});
			marker.openInfoWindowHtml(WINDOW_HTML);
			}
			}
			</script>
			<body onload="load()" onunload="GUnload()" >';
}

if (!isset($_POST['PageOffset'])) {
	$_POST['PageOffset'] = 1;
} else {
	if ($_POST['PageOffset'] == 0) {
		$_POST['PageOffset'] = 1;
	}
}
if (isset($_POST['Search'])
	OR isset($_POST['Go'])
	OR isset($_POST['Next'])
	OR isset($_POST['Previous'])) {

	if (mb_strlen($_POST['Keywords']) > 0 AND mb_strlen($_POST['SupplierCode']) > 0) {
		echo prnMsg( _('Supplier name keywords have been used in preference to the Supplier code extract entered'), 'info' );
	}
	if ($_POST['Keywords'] == '' AND $_POST['SupplierCode'] == '') {
		$SQL = "SELECT supplierid,
					suppname,
					currcode,
					address1,
					address2,
					address3,
					address4,
					telephone,
					email,
					url
				FROM suppliers
				ORDER BY suppname";
	} else {
		if (mb_strlen($_POST['Keywords']) > 0) {
			$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';
			$SQL = "SELECT supplierid,
							suppname,
							currcode,
							address1,
							address2,
							address3,
							address4,
							telephone,
							email,
							url
						FROM suppliers
						WHERE suppname " . LIKE . " '" . $SearchString . "'
						ORDER BY suppname";
		} elseif (mb_strlen($_POST['SupplierCode']) > 0) {
			$_POST['SupplierCode'] = mb_strtoupper($_POST['SupplierCode']);
			$SQL = "SELECT supplierid,
							suppname,
							currcode,
							address1,
							address2,
							address3,
							address4,
							telephone,
							email,
							url
						FROM suppliers
						WHERE supplierid " . LIKE . " '%" . $_POST['SupplierCode'] . "%'
						ORDER BY supplierid";
		}
	} //one of keywords or SupplierCode was more than a zero length string
	$result = DB_query($SQL);
	if (DB_num_rows($result) == 1) {
		$myrow = DB_fetch_row($result);
		$SingleSupplierReturned = $myrow[0];
	}
	if (isset($SingleSupplierReturned)) { /*there was only one supplier returned */
 	   $_SESSION['SupplierID'] = $SingleSupplierReturned;
	   unset($_POST['Keywords']);
	   unset($_POST['SupplierCode']);
	   unset($_POST['Search']);
        } else {
               unset($_SESSION['SupplierID']);
        }
} //end of if search


if (isset($_SESSION['SupplierID'])) {
	// A supplier is selected
	$SupplierName = '';
	$SQL = "SELECT suppliers.suppname
			FROM suppliers
			WHERE suppliers.supplierid ='" . $_SESSION['SupplierID'] . "'";
	$SupplierNameResult = DB_query($SQL);
	if (DB_num_rows($SupplierNameResult) == 1) {
		$myrow = DB_fetch_row($SupplierNameResult);
		$SupplierName = $myrow[0];
	}

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>', // Icon title.
		_('Supplier'), ': ', $_SESSION['SupplierID'], ' - ', $SupplierName, '<br />',// Page title.
		'</h1></a></div>',// Page help text.
		'',
		$TableHead,
			'<div class="row"><div class="col-xs-12">';
			
			
	
	 /* Supplier Transactions */
	echo '<div class="col-md-6">
		<div class="block"><div class="block-title"><h3>' .
					_('Operations') . '</h1></div>';
	echo '<ul class="list-unstyled">
	<li><a href="' . $RootPath . '/PO_Header.php?NewOrder=Yes&amp;SupplierID=' . $_SESSION['SupplierID'] . '">' . _('New Purchase Order') . '</a></li>';
	echo '<li><a href="' . $RootPath . '/SupplierInvoice.php?SupplierID=' . $_SESSION['SupplierID'] . '">' . _('Invoices') . '</a></li>';
	echo '<li><a href="' . $RootPath . '/SupplierCredit.php?New=true&amp;SupplierID=' . $_SESSION['SupplierID'] . '">' . _('Credit Notes') . '</a></li>';
	echo '<li><a href="' . $RootPath . '/Payments.php?SupplierID=' . $_SESSION['SupplierID'] . '">' . _('Payment or Receipt ') . '</a></li>';
	
	echo '<li><a href="' . $RootPath . '/ReverseGRN.php?SupplierID=' . $_SESSION['SupplierID'] . '">' . _('Reverse an open GRN') . '</a></li>
	<li><a href="' . $RootPath . '/Suppliers.php">' . _('Add a New Supplier') . '</a></li>
		<li><a href="' . $RootPath . '/Suppliers.php?SupplierID=' . $_SESSION['SupplierID'] . '">' . _('Modify') . '</a></li>
		<li><a href="' . $RootPath . '/SupplierContacts.php?SupplierID=' . $_SESSION['SupplierID'] . '">' . _('Contacts') . '</a></li>
		
		
		<li><a href="' . $RootPath . '/Shipments.php?NewShipment=Yes">' . _('Shipments') . '</a></li>
		<li><a href="' . $RootPath . '/SuppLoginSetup.php">' . _('Supplier Login') . '</a></li>
	</ul>';
	echo '</div></div>';
	
	echo '<div class="col-md-6">
		<div class="block">
		<div class="block-title"><h3>' .
					_('Inquiries and Reports') . '</h1></div>';
	// Supplier inquiries options:
	echo '<ul class="list-unstyled">
	<li><a href="' . $RootPath . '/SupplierInquiry.php?SupplierID=' . $_SESSION['SupplierID'] . '">' . _('Account Inquiry') . '</a>
		</li>
		<li><a href="' . $RootPath . '/SupplierGRNAndInvoiceInquiry.php?SelectedSupplier=' . $_SESSION['SupplierID'] . '&amp;SupplierName='.urlencode($SupplierName).'">' . _('Delivery Note AND GRN inquiry') . '</a></li>
		';

	echo '<li><a href="' . $RootPath . '/PO_SelectOSPurchOrder.php?SelectedSupplier=' . $_SESSION['SupplierID'] . '">' . _('Open Purchase Orders') . '</a></li>';
	echo '<li><a href="' . $RootPath . '/PO_SelectPurchOrder.php?SelectedSupplier=' . $_SESSION['SupplierID'] . '">' . _('All Purchase Orders') . '</a></li>';
	wikiLink('Supplier', $_SESSION['SupplierID']);
	echo '<li><a href="' . $RootPath . '/ShiptsList.php?SupplierID=' . $_SESSION['SupplierID'] . '&amp;SupplierName=' . urlencode($SupplierName) . '">' . _('Open shipments') .'</a></li>';
	echo '<li><a href="' . $RootPath . '/Shipt_Select.php?SelectedSupplier=' . $_SESSION['SupplierID'] . '">' . _('Modify / Close Shipments') . '</a></li>';
	echo '<li><a href="' . $RootPath . '/SuppPriceList.php?SelectedSupplier=' . $_SESSION['SupplierID'] . '">' . _('Print Price List') . '</a></li></ul>';
	echo '</div></div>';
	/* Supplier Maintenance */
	echo '</div></div>';
		
		
} else {
	// Supplier is not selected yet
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>', // Icon title.
		_('Suppliers'), '</h1></a></div>',// Page title.
		'',
		'
		<div class="row" align="center"><a href="', $RootPath, '/Suppliers.php" class="btn btn-info">', _('Add a New Supplier'), '</a></div>',// Supplier Maintenance options.
		'
<br />

		';
}

// Only display the geocode map if the integration is turned on, and there is a latitude/longitude to display
if (isset($_SESSION['SupplierID']) and $_SESSION['SupplierID'] != '') {
	
	// Extended Info only if selected in Configuration
	if ($_SESSION['Extended_SupplierInfo'] == 1) {
		if ($_SESSION['SupplierID'] != '') {
			$sql = "SELECT suppliers.suppname,
							suppliers.lastpaid,
							suppliers.lastpaiddate,
							suppliersince,
							currencies.decimalplaces AS currdecimalplaces
					FROM suppliers INNER JOIN currencies
					ON suppliers.currcode=currencies.currabrev
					WHERE suppliers.supplierid ='" . $_SESSION['SupplierID'] . "'";
			$ErrMsg = _('An error occurred in retrieving the information');
			$DataResult = DB_query($sql, $ErrMsg);
			$myrow = DB_fetch_array($DataResult);
			// Select some more data about the supplier
			$SQL = "SELECT SUM(ovamount) AS total FROM supptrans WHERE supplierno = '" . $_SESSION['SupplierID'] . "' AND (type = '20' OR type='21')";
			$Total1Result = DB_query($SQL);
			$row = DB_fetch_array($Total1Result);
			echo '<br />';
			echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('History') . '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		
			echo '<tr><td valign="top" class="select">'; /* Supplier Data */
			//echo "Distance to this Supplier: <b>TBA</b><br />";
			if ($myrow['lastpaiddate'] == 0) {
				echo _('No payments yet to this supplier.') . '</td>
					<td valign="top" class="select"></td>
					</tr>';
			} else {
				echo _('Last Payment Received On') . '</td>
					<td valign="top" class="select"> <b>' . ConvertSQLDate($myrow['lastpaiddate']) . '</b></td>
					</tr>';
			}
			echo '<tr>
					<td valign="top" class="select">' . _('Last Paid Amount') . '</td>
					<td valign="top" class="select">  <b>' . locale_number_format($myrow['lastpaid'], $myrow['currdecimalplaces']) . '</b></td></tr>';
			echo '<tr>
					<td valign="top" class="select">' . _('Supplier since') . '</td>
					<td valign="top" class="select"> <b>' . ConvertSQLDate($myrow['suppliersince']) . '</b></td>
					</tr>';
			echo '<tr>
					<td valign="top" class="select">' . _('Total business with this Supplier') . '</td>
					<td valign="top" class="select"> <b>' . locale_number_format($row['total'], $myrow['currdecimalplaces']) . '</b></td>
					</tr>';
			echo '</table></div></div></div></div>';
		}
	}
}
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Supplier Name-part or full') . '</label>
		';
if (isset($_POST['Keywords'])) {
	echo '<input type="text" class="form-control" name="Keywords" value="' . $_POST['Keywords'] . '" size="20" maxlength="25" />';
} else {
	echo '<input type="text" class="form-control" name="Keywords" size="20" maxlength="25" />';
}
echo '</div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Supplier Code-part or full') . '</label>
		';
if (isset($_POST['SupplierCode'])) {
	echo '<input type="text" class="form-control" autofocus="autofocus" name="SupplierCode" value="' . $_POST['SupplierCode'] . '" size="15" maxlength="18" />';
} else {
	echo '<input type="text" class="form-control" autofocus="autofocus" name="SupplierCode" size="15" maxlength="18" />';
}
echo '</div></div>
		<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" class="btn btn-success" name="Search" value="' . _('Search') . '" /></div></div></div>';
//if (isset($result) AND !isset($SingleSupplierReturned)) {
if (isset($_POST['Search'])) {
	$ListCount = DB_num_rows($result);
	$ListPageMax = ceil($ListCount / $_SESSION['DisplayRecordsMax']);
	if (isset($_POST['Next'])) {
		if ($_POST['PageOffset'] < $ListPageMax) {
			$_POST['PageOffset'] = $_POST['PageOffset'] + 1;
		}
	}
	if (isset($_POST['Previous'])) {
		if ($_POST['PageOffset'] > 1) {
			$_POST['PageOffset'] = $_POST['PageOffset'] - 1;
		}
	}
	if ($ListPageMax > 1) {
		echo '<br /><div class="row"><div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . $_POST['PageOffset'] . ' ' . _('of') . ' ' . $ListPageMax . ' ' . _('pages') . '. ' . _('Go to Page') . '</label>';
		echo '<select name="PageOffset" class="form-control">';
		$ListPage = 1;
		while ($ListPage <= $ListPageMax) {
			if ($ListPage == $_POST['PageOffset']) {
				echo '<option value="' . $ListPage . '" selected="selected">' . $ListPage . '</option>';
			} else {
				echo '<option value="' . $ListPage . '">' . $ListPage . '</option>';
			}
			$ListPage++;
		}
		echo '</select></div></div></div>
			<div class="row">
<div class="col-xs-4"> <input type="submit" class="btn btn-default" name="Go" value="' . _('Go') . '" /></div>
<div class="col-xs-4"> <input type="submit" class="btn btn-default" name="Previous" value="' . _('Previous') . '" /></div>
<div class="col-xs-4"> <input type="submit" class="btn btn-default" name="Next" value="' . _('Next') . '" />';
		echo '</div></div><br />';
	}
	echo '<input type="hidden" name="Search" value="' . _('Search Now') . '" />';
	echo '<br />
		
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
	  		<th>' . _('Code') . '</th>
			<th>' . _('Supplier Name') . '</th>
			<th>' . _('Currency') . '</th>
			<th>' . _('Address 1') . '</th>
			<th>' . _('Address 2') . '</th>
			<th>' . _('Address 3') . '</th>
			<th>' . _('Address 4') . '</th>
			<th>' . _('Telephone') . '</th>
			<th>' . _('Email') . '</th>
			<th>' . _('URL') . '</th>
			</tr>
		</thead>
		<tbody>';

	$RowIndex = 0;
	if (DB_num_rows($result) <> 0) {
		DB_data_seek($result, ($_POST['PageOffset'] - 1) * $_SESSION['DisplayRecordsMax']);
	}
	while (($myrow = DB_fetch_array($result)) AND ($RowIndex <> $_SESSION['DisplayRecordsMax'])) {
		echo '<tr class="striped_row">
				<td><input type="submit" name="Select" value="'.$myrow['supplierid'].'" class="btn btn-info" /></td>
				<td>' . $myrow['suppname'] . '</td>
				<td>' . $myrow['currcode'] . '</td>
				<td>' . $myrow['address1'] . '</td>
				<td>' . $myrow['address2'] . '</td>
				<td>' . $myrow['address3'] . '</td>
				<td>' . $myrow['address4'] . '</td>
				<td>' . $myrow['telephone'] . '</td>
				<td><a href="mailto://'.$myrow['email'].'">' . $myrow['email']. '</a></td>
				<td><a href="'.$myrow['url'].'"target="_blank">' . $myrow['url']. '</a></td>
			</tr>';
		$RowIndex = $RowIndex + 1;
		//end of page full new headings if
	}
	//end of while loop
	echo '</tbody></table></div></div></div>';
}
//end if results to show
if (isset($ListPageMax) and $ListPageMax > 1) {
	echo '<br /><div class="row"><div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . $_POST['PageOffset'] . ' ' . _('of') . ' ' . $ListPageMax . ' ' . _('pages') . '. ' . _('Go to Page') . '</label>';
	echo '<select name="PageOffset" class="form-control">';
	$ListPage = 1;
	while ($ListPage <= $ListPageMax) {
		if ($ListPage == $_POST['PageOffset']) {
			echo '<option value="' . $ListPage . '" selected="selected">' . $ListPage . '</option>';
		} else {
			echo '<option value="' . $ListPage . '">' . $ListPage . '</option>';
		}
		$ListPage++;
	}
	echo '</select></div></div></div>
	<div class="row">
<div class="col-xs-4">
		<input type="submit" class="btn btn-default" name="Go" value="' . _('Go') . '" /></div>
<div class="col-xs-4"> <input type="submit" class="btn btn-default" name="Previous" value="' . _('Previous') . '" /></div>
<div class="col-xs-4"> <input type="submit" class="btn btn-default" name="Next" value="' . _('Next') . '" />';
	echo '</div></div><br />';
}
echo '
      </form>';
include ('includes/footer.php');
?>
