<?php


include('includes/session.php');

$Title = _('My Maintenance Jobs');

$Title = _('Fixed Assets Maintenance Schedule');

$ViewTopic = 'FixedAssets';
$BookMark = 'AssetMaintenance';

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';


if (isset($_GET['Complete'])) {
	$result = DB_query("UPDATE fixedassettasks SET lastcompleted='" . Date('Y-m-d') . "', is_completed=1 WHERE taskid='" . $_GET['TaskID'] . "'");
}


$sql="SELECT taskid,
				fixedassettasks.assetid,
				description,
				taskdescription,
				frequencydays,
				lastcompleted,
				is_completed,
				ADDDATE(lastcompleted,frequencydays) AS duedate,
				userresponsible,
				realname,
				manager
		FROM fixedassettasks
		INNER JOIN fixedassets
		ON fixedassettasks.assetid=fixedassets.assetid
		INNER JOIN www_users
		ON fixedassettasks.userresponsible=www_users.userid
		WHERE userresponsible='" . $_SESSION['UserID'] . "'
		OR manager = '" . $_SESSION['UserID'] . "'
		ORDER BY ADDDATE(lastcompleted,frequencydays) DESC";

$ErrMsg = _('The maintenance schedule cannot be retrieved because');
$Result=DB_query($sql,$ErrMsg);

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
     <thead>
	 <tr>
		<th>' . _('Task ID') . '</th>
		<th>' . _('Asset') . '</th>
		<th>' . _('Description') . '</th>
		<th>' . _('Last Completed') . '</th>
		<th>' . _('Due By Date') . '</th>
		<th>' . _('Responsibility') . '</th>
		<th>' . _('Manager') . '</th>
		<th>' . _('Action') . '</th>
    </tr></thead>';

while ($myrow=DB_fetch_array($Result)) {

	if ($myrow['manager']!=''){
		$ManagerResult = DB_query("SELECT realname FROM www_users WHERE userid='" . $myrow['manager'] . "'");
		$ManagerRow = DB_fetch_array($ManagerResult);
		$ManagerName = $ManagerRow['realname'];
	} else {
		$ManagerName = _('No Manager Set');
	}

	echo '<tr>
			<td>' . $myrow['taskid'] . '</td>
			<td>' . $myrow['description'] . '</td>
			<td>' . $myrow['taskdescription'] . '</td>
			<td>' . ConvertSQLDate($myrow['lastcompleted']) . '</td>
			<td>' . ConvertSQLDate($myrow['duedate']) . '</td>
			<td>' . $myrow['realname'] . '</td>
			<td>' . $ManagerName . '</td>';
			if($myrow['is_completed']==1) { 
			echo '<td class="text-danger"><strong>Completed</strong></td>';
			}
			else
			if($myrow['is_completed']==0) { 
			
			echo '<td><a href="'.$RootPath.'/MaintenanceUserSchedule.php?Complete=Yes&amp;TaskID=' . $myrow['taskid'] .'" onclick="return confirm(\'' . _('Are you sure you wish to mark this maintenance task as completed?') . '\');" class="btn btn-success">' . _('Mark Completed') . '</a></td> ';
			}
			
		echo '</tr>';
}

echo '</table></div></div></div><br />';

include('includes/footer.php');
?>