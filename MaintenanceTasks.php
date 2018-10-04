<?php


include('includes/session.php');

$Title = _('Fixed Asset Maintenance Tasks');

$ViewTopic = 'FixedAssets';
$BookMark = 'AssetMaintenance';

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';


if (isset($_POST['Submit'])) {
	if (!is_numeric(filter_number_format($_POST['FrequencyDays'])) OR filter_number_format($_POST['FrequencyDays']) < 0){
		echo prnMsg(_('The remind before a task falls due is expected to be a postive'),'error');
	} else {
		$sql="INSERT INTO fixedassettasks (assetid,
											taskdescription,
											frequencydays,
											userresponsible,
											manager,
											lastcompleted)
						VALUES( '" . $_POST['AssetID'] . "',
								'" . $_POST['TaskDescription'] . "',
								'" . filter_number_format($_POST['FrequencyDays']) . "',
								'" . $_POST['UserResponsible'] . "',
								'" . $_POST['Manager'] . "',
								'" . Date('Y-m-d') . "' )";
		$ErrMsg = _('The authentication details cannot be inserted because');
		$Result=DB_query($sql,$ErrMsg);
		unset($_POST['AssetID']);
		unset($_POST['TaskDescription']);
		unset($_POST['FrequencyDays']);
		unset($_POST['Manager']);
		unset($_POST['UserResponsible']);
	}
}

if (isset($_POST['Update'])) {
	if (!is_numeric(filter_number_format($_POST['FrequencyDays'])) OR filter_number_format($_POST['FrequencyDays']) < 0){
		echo prnMsg(_('The remind before a task falls due is expected to be a postive'),'error');
	} else {
		$sql="UPDATE fixedassettasks SET
				assetid = '" . $_POST['AssetID'] . "',
				taskdescription='".$_POST['TaskDescription'] ."',
				frequencydays='" . filter_number_format($_POST['FrequencyDays'])."',
				userresponsible='" . $_POST['UserResponsible'] . "',
				manager='" . $_POST['Manager'] . "'
				WHERE taskid='".$_POST['TaskID']."'";

		$ErrMsg = _('The task details cannot be updated because');
		$Result=DB_query($sql,$ErrMsg);
		unset($_POST['AssetID']);
		unset($_POST['TaskDescription']);
		unset($_POST['FrequencyDays']);
		unset($_POST['Manager']);
		unset($_POST['UserResponsible']);
	}
}

if (isset($_GET['Delete'])) {
	$sql="DELETE FROM fixedassettasks
		WHERE taskid='".$_GET['TaskID']."'";

	$ErrMsg = _('The maintenance task cannot be deleted because');
	$Result=DB_query($sql,$ErrMsg);
}

$sql="SELECT taskid,
				fixedassettasks.assetid,
				description,
				taskdescription,
				frequencydays,
				lastcompleted,
				userresponsible,
				realname,
				manager
		FROM fixedassettasks
		INNER JOIN fixedassets
		ON fixedassettasks.assetid=fixedassets.assetid
		INNER JOIN www_users
		ON fixedassettasks.userresponsible=www_users.userid";

$ErrMsg = _('The maintenance task details cannot be retrieved because');
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
		<th>' . _('Responsibility') . '</th>
		<th>' . _('Manager') . '</th>
		<th colspan="2">' . _('Actions') . '</th>
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
			<td>' . $myrow['realname'] . '</td>
			<td>' . $ManagerName . '</td>
			<td><a href="'.$RootPath.'/MaintenanceTasks.php?Edit=Yes&amp;TaskID=' . $myrow['taskid'] .'" class="btn  btn-info">' . _('Edit') . '</a></td>
			<td><a href="'.$RootPath.'/MaintenanceTasks.php?Delete=Yes&amp;TaskID=' . $myrow['taskid'] .'" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this maintenance task?') . '\');">' . _('Delete') . '</a></td>
		</tr>';
}

echo '</table></div></div></div>';


echo '<br /><form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post" id="form1">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


if (isset($_GET['Edit'])) {
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Task ID') . '</label>
                       ' . $_GET['TaskID'] . '</div>
		</div></div>';
	echo '<input type="hidden" name="TaskID" value="'.$_GET['TaskID'].'" />';
	$sql="SELECT assetid,
				taskdescription,
				frequencydays,
				lastcompleted,
				userresponsible,
				manager
			FROM fixedassettasks
			WHERE taskid='".$_GET['TaskID']."'";
	$ErrMsg = _('The maintenance task details cannot be retrieved because');
	$result=DB_query($sql,$ErrMsg);
	$myrow=DB_fetch_array($result);
	$_POST['TaskDescription'] = $myrow['taskdescription'];
	$_POST['FrequencyDays'] = $myrow['frequencydays'];
	$_POST['UserResponsible'] = $myrow['userresponsible'];
	$_POST['Manager'] = $myrow['manager'];
	$_POST['AssetID'] = $myrow['assetid'];
}

if (!isset($_POST['TaskDescription'])){
	$_POST['TaskDescription']='';
}
if (!isset($_POST['FrequencyDays'])){
	$_POST['FrequencyDays']='';
}
if (!isset($_POST['UserResponsible'])){
	 $_POST['UserResponsible']= '';
}
if (!isset($_POST['Manager'])){
	$_POST['Manager']='';
}
if (!isset($_POST['AssetID'])){
	$_POST['AssetID']='';
}

echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Asset').'</label>
		<select required="required" name="AssetID" class="form-control">';
$AssetSQL="SELECT assetid, description FROM fixedassets";
$AssetResult=DB_query($AssetSQL);
while ($myrow=DB_fetch_array($AssetResult)) {
	if ($myrow['assetid']==$_POST['AssetID']) {
		echo '<option selected="selected" value="'.$myrow['assetid'].'">' . $myrow['assetid'] . ' - ' . $myrow['description']  . '</option>';
	} else {
		echo '<option value="'.$myrow['assetid'].'">' . $myrow['assetid'] . ' - ' . $myrow['description']  . '</option>';
	}
}
echo '</select></div>
	</div>';

echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Task Description').'</label>
		<textarea name="TaskDescription" class="form-control" required="required">' . $_POST['TaskDescription'] . '</textarea></div>
	</div>';

echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Remind before (Days)').'</label>
		<input type="text" class="form-control" required="required" name="FrequencyDays" size="5" maxlength="5" value="' . $_POST['FrequencyDays'] . '" /></div>
	</div></div>';

echo '<div class="row">
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Responsibility') . '</label>
		<select required="required" name="UserResponsible" class="form-control">';
$UserSQL="SELECT userid FROM www_users";
$UserResult=DB_query($UserSQL);
while ($myrow=DB_fetch_array($UserResult)) {
	if ($myrow['userid']==$_POST['UserResponsible']) {
		echo '<option selected="selected" value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
	} else {
		echo '<option value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
	}
}
echo '</select></div>
	</div>';

echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Manager').'</label>
		<select required="required" name="Manager" class="form-control">';
if ($_POST['Manager']==''){
	echo '<option selected="selected" value="">' . _('No Manager') . '</option>';
} else {
	echo '<option value="">' . _('No Manager') . '</option>';
}
$ManagerSQL="SELECT userid FROM www_users";
$ManagerResult=DB_query($UserSQL);
while ($myrow=DB_fetch_array($ManagerResult)) {
	if ($myrow['userid']==$_POST['Manager']) {
		echo '<option selected="selected" value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
	} else {
		echo '<option value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
	}
}
echo '</select></div>
	</div>
	';

if (isset($_GET['Edit'])) {
	echo '<div class="col-xs-4">
<div class="form-group"> <br />
				<input type="submit" class="btn btn-info" name="Update" value="'._('Update').'" />
			</div></div>';
} else {
	echo '
		<div class="col-xs-4">
<div class="form-group"> <br />
			<input type="submit" class="btn btn-success" name="Submit" value="'._('Submit').'" />
		</div></div>';
}
echo '</div>
        </form>';
include('includes/footer.php');
?>