<?php


include('includes/session.php');
$Title = _('Page Security Levels');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['Update']) AND $AlloDemoMode!= true) {
	foreach ($_POST as $ScriptName => $PageSecurityValue) {
		if ($ScriptName!='Update' and $ScriptName!='FormID') {
			$ScriptName = mb_substr($ScriptName, 0, mb_strlen($ScriptName)-4).'.php';
			$sql="UPDATE scripts SET pagesecurity='". $PageSecurityValue . "' WHERE script='" . $ScriptName . "'";
			$UpdateResult=DB_query($sql,_('Could not update the page security value for the script because'));
		}
	}
}

$sql="SELECT script,
			pagesecurity,
			description
		FROM scripts";

$result=DB_query($sql);

echo '<br /><form method="post" id="PageSecurity" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<div class="row">';

$TokenSql="SELECT tokenid,
					tokenname
			FROM securitytokens
			ORDER BY tokenname";
$TokenResult=DB_query($TokenSql);

while ($myrow=DB_fetch_array($result)) {
	echo '<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label">' . $myrow['script'] . '</label>
			<select name="' . $myrow['script'] . '" class="form-control">';

	while ($myTokenRow=DB_fetch_array($TokenResult)) {
		if ($myTokenRow['tokenid']==$myrow['pagesecurity']) {
			echo '<option selected="selected" value="' . $myTokenRow['tokenid'] . '">' . $myTokenRow['tokenname'] . '</option>';
		} else {
			echo '<option value="'.$myTokenRow['tokenid'].'">' . $myTokenRow['tokenname'] . '</option>';
		}
	}
	echo '</select></div>
		</div>';
	DB_data_seek($TokenResult, 0);
}

echo '</div>';

echo '<div class="row" align="center">
<div>
		<input type="submit" class="btn btn-success" name="Update" value="'._('Update').'" />
	</div>
	
    </div><br />
	</form>';

include('includes/footer.php');
?>