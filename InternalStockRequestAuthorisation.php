<?php


include('includes/session.php');

$Title = _('Authorise Internal Stock Requests');
$ViewTopic = 'Inventory';
$BookMark = 'AuthoriseRequest';

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['UpdateAll'])) {
	foreach ($_POST as $POSTVariableName => $POSTValue) {
		if (mb_substr($POSTVariableName,0,6)=='status') {
			$RequestNo=mb_substr($POSTVariableName,6);
			$sql="UPDATE stockrequest
					SET authorised='1'
					WHERE dispatchid='" . $RequestNo . "'";
			$result=DB_query($sql);
		}
		if (strpos($POSTVariableName, 'cancel')) {
 			$CancelItems = explode('cancel', $POSTVariableName);
 			$sql = "UPDATE stockrequestitems
 						SET completed=1
 						WHERE dispatchid='" . $CancelItems[0] . "'
 						AND dispatchitemsid='" . $CancelItems[1] . "'";
 			$result = DB_query($sql);
 			$result = DB_query("SELECT stockid FROM stockrequestitems WHERE completed=0 AND dispatchid='" . $CancelItems[0] . "'");
 			if (DB_num_rows($result) ==0){
				$result = DB_query("UPDATE stockrequest
									SET authorised='1'
									WHERE dispatchid='" . $CancelItems[0] . "'");
			}

 		}
	}
}

/* Retrieve the requisition header information
 */
$sql="SELECT stockrequest.dispatchid,
			locations.locationname,
			stockrequest.despatchdate,
			stockrequest.narrative,
			departments.description,
			www_users.realname,
			www_users.email
		FROM stockrequest INNER JOIN departments
			ON stockrequest.departmentid=departments.departmentid
		INNER JOIN locations
			ON stockrequest.loccode=locations.loccode
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
		INNER JOIN www_users
			ON www_users.userid=departments.authoriser
		WHERE stockrequest.authorised=0
		AND stockrequest.closed=0
		AND www_users.userid='".$_SESSION['UserID']."'";
$result=DB_query($sql);

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<thead>
	<tr>
		<th>' . _('Request Number') . '</th>
		<th>' . _('Department') . '</th>
		<th>' . _('Location Of Stock') . '</th>
		<th>' . _('Requested Date') . '</th>
		<th>' . _('Narrative') . '</th>
		<th>' . _('Authorise') . '</th>
	</tr></thead>';

while ($myrow=DB_fetch_array($result)) {

	echo '<tr>
			<td>' . $myrow['dispatchid'] . '</td>
			<td>' . $myrow['description'] . '</td>
			<td>' . $myrow['locationname'] . '</td>
			<td>' . ConvertSQLDate($myrow['despatchdate']) . '</td>
			<td>' . $myrow['narrative'] . '</td>
			<td><input type="checkbox" name="status'.$myrow['dispatchid'].'" /></td>
		</tr>';
	$LinesSQL="SELECT stockrequestitems.dispatchitemsid,
						stockrequestitems.stockid,
						stockrequestitems.decimalplaces,
						stockrequestitems.uom,
						stockmaster.description,
						stockrequestitems.quantity
				FROM stockrequestitems
				INNER JOIN stockmaster
				ON stockmaster.stockid=stockrequestitems.stockid
			WHERE dispatchid='".$myrow['dispatchid'] . "'
			AND completed=0";
	$LineResult=DB_query($LinesSQL);

	echo '<tr>
			<td></td>
			<td colspan="5" align="left">
				<div class="table-responsive">
<table id="general-table" class="table table-bordered">

				<thead>
				<tr>
					<th>' . _('Product') . '</th>
					<th>' . _('Quantity Required') . '</th>
					<th>' . _('Units') . '</th>
					<th>' . _('Cancel Line') . '</th>
				</tr></thead>';

	while ($LineRow=DB_fetch_array($LineResult)) {
		echo '<tr>
				<td>' . $LineRow['description'] . '</td>
				<td class="number">' . locale_number_format($LineRow['quantity'],$LineRow['decimalplaces']) . '</td>
				<td>' . $LineRow['uom'] . '</td>
				<td><input type="checkbox" name="' . $myrow['dispatchid'] . 'cancel' . $LineRow['dispatchitemsid'] . '" /></td
			</tr>';
	} // end while order line detail
	echo '</table></div>
			</td>
		</tr>';
} //end while header loop
echo '</table></div></div></div>';
echo '<br /><div class="row" align="center">
<div>
<input type="submit" name="UpdateAll" class="btn btn-info" value="' . _('Update'). '" /></div>
      </div><br />

      </form>';

include('includes/footer.php');
?>