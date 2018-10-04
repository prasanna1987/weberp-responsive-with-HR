<?php


include('includes/session.php');
$Title = _('Maintain General Ledger Tags');

$ViewTopic = 'GeneralLedger';
$BookMark = 'GLTags';

include('includes/header.php');

if (isset($_GET['SelectedTag'])) {
	if($_GET['Action']=='delete'){
		//first off test there are no transactions created with this tag
		$Result = DB_query("SELECT counterindex
							FROM gltrans
							WHERE tag='" . $_GET['SelectedTag'] . "'");
		if (DB_num_rows($Result)>0){
			echo prnMsg(_('This tag cannot be deleted since there are already general ledger transactions created using it.'),'error');
		} else	{
			$Result = DB_query("DELETE FROM tags WHERE tagref='" . $_GET['SelectedTag'] . "'");
			echo prnMsg(_('The selected tag has been deleted'),'success');
		}
		$Description='';
	} else {
		$sql="SELECT tagref,
					tagdescription
				FROM tags
				WHERE tagref='".$_GET['SelectedTag']."'";

		$result= DB_query($sql);
		$myrow = DB_fetch_array($result);
		$ref=$myrow['tagref'];
		$Description = $myrow['tagdescription'];
	}
} else {
	$Description='';
	$_GET['SelectedTag']='';
}

if (isset($_POST['submit'])) {
	$sql = "INSERT INTO tags values(NULL, '" . $_POST['Description'] . "')";
	$result= DB_query($sql);
}

if (isset($_POST['update'])) {
	$sql = "UPDATE tags SET tagdescription='" . $_POST['Description'] . "'
		WHERE tagref='".$_POST['reference']."'";
	$result= DB_query($sql);
}
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '
	</h1></a></div>';

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" id="form">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' .  _('Description') . '</label>
		<input type="text" required="required" autofocus="autofocus" size="30" maxlength="30" class="form-control" name="Description" title="' . _('Enter the description of the general ledger tag up to 30 characters') . '" value="' . $Description . '" /></div></div>
		<input type="hidden" name="reference" value="'.$_GET['SelectedTag'].'" />';

if (isset($_GET['Action']) AND $_GET['Action']=='edit') {
	echo '<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" name="update" value="' . _('Update') . '" class="btn btn-info" /></div></div>';
} else {
	echo '<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" class="btn btn-success" name="submit" value="' . _('Enter Information') . '" /></div></div>';
}

echo '
    </div><br />
	</form>
	<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
<thead>
	<tr>
		<th>' .  _('Tag ID')  . '</th>
		<th>' .  _('Description'). '</th>
		<th colspan="2">' .  _('Actions'). '</th>
	</tr></thead>';

$sql="SELECT tagref,
			tagdescription
		FROM tags
		ORDER BY tagref";

$result= DB_query($sql);

while ($myrow = DB_fetch_array($result)){
	echo '<tr>
			<td>' . $myrow['tagref'] . '</td>
			<td>' . $myrow['tagdescription'] . '</td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?SelectedTag=' . $myrow['tagref'] . '&amp;Action=edit" class="btn btn-info">' . _('Edit') . '</a></td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?SelectedTag=' . $myrow['tagref'] . '&amp;Action=delete" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this GL tag?') . '\');">' . _('Delete') . '</a></td>
		</tr>';
}

echo '</table></div></div></div>';

include('includes/footer.php');

?>
