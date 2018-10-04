<?php
/* This script is <create a description for script table>. */

include('includes/session.php');
$Title = _('SMTP Server details');// Screen identification.
$ViewTopic = 'CreatingNewSystem';// Filename's id in ManualContents.php's TOC.
$BookMark = 'SMTPServer';// Anchor's id in the manual's html document.
include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
	_('SMTP Server Settings') . '</h1></a></div>';// Page title.
// First check if there are smtp server data or not


if (isset($_POST['submit']) AND $_POST['MailServerSetting']==1) {//If there are already data setup, Update the table
	$sql="UPDATE emailsettings SET
				host='".$_POST['Host']."',
				port='".$_POST['Port']."',
				heloaddress='".$_POST['HeloAddress']."',
				username='".$_POST['UserName']."',
				password='".$_POST['Password']."',
				auth='".$_POST['Auth']."'";

	$ErrMsg = _('The email setting information failed to update');
	$DbgMsg = _('The SQL failed to update is ');
	$result1=DB_query($sql, $ErrMsg, $DbgMsg);
	unset($_POST['MailServerSetting']);
	echo prnMsg(_('The settings for the SMTP server have been successfully updated'), 'success');
	echo '<br />';

}elseif(isset($_POST['submit']) and $_POST['MailServerSetting']==0){//There is no data setup yet
	$sql = "INSERT INTO emailsettings(host,
		 				port,
						heloaddress,
						username,
						password,
						auth)
				VALUES ('".$_POST['Host']."',
						'".$_POST['Port']."',
						'".$_POST['HeloAddress']."',
						'".$_POST['UserName']."',
						'".$_POST['Password']."',
						'".$_POST['Auth']."')";
	$ErrMsg = _('The email settings failed to be inserted');
	$DbgMsg = _('The SQL failed to insert the email information is');
	$result2 = DB_query($sql);
	unset($_POST['MailServerSetting']);
	echo prnMsg(_('The settings for the SMTP server have been sucessfully added'),'success');
	echo '<br/>';
}

  // Check the mail server setting status

		$sql="SELECT id,
				host,
				port,
				heloaddress,
				username,
				password,
				timeout,
				auth
			FROM emailsettings";
		$ErrMsg = _('The email settings information cannot be retrieved');
		$DbgMsg = _('The SQL that failed was');

		$result=DB_query($sql,$ErrMsg,$DbgMsg);
		if(DB_num_rows($result)!=0){
			$MailServerSetting = 1;
			$myrow=DB_fetch_array($result);
		}else{
			DB_free_result($result);
			$MailServerSetting = 0;
			$myrow['host']='';
			$myrow['port']='';
			$myrow['heloaddress']='';
			$myrow['username']='';
			$myrow['password']='';
			$myrow['timeout']=5;
		}


echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
	
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<input type="hidden" name="MailServerSetting" value="' . $MailServerSetting . '" />
	<div class="row">
	<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Server Host Name') . '</label>
		<input type="text" class="form-control" name="Host" required="required" value="' . $myrow['host'] . '" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('SMTP port') . '</label>
		<input type="text" name="Port" required="required" size="4" class="form-control" value="' . $myrow['port'].'" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Helo Command') . '</label>
		<input type="text" class="form-control" name="HeloAddress" value="' . $myrow['heloaddress'] . '" /></div>
	</div></div>
	<div class="col-xs-row">
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Authorisation Required') . '</label>
		<select name="Auth" class="form-control">';
if ($myrow['auth']==1) {
	echo '<option selected="selected" value="1">' . _('True') . '</option>';
	echo '<option value="0">' . _('False') . '</option>';
} else {
	echo '<option value="1">' . _('True') . '</option>';
	echo '<option selected="selected" value="0">' . _('False') . '</option>';
}
echo '</select></div>
	</div>
	<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('User Name') . '</label>
		<input type="text" required="required" class="form-control" name="UserName" size="50" maxlength="50" value="' . $myrow['username']  .'" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Password') . '</label>
		<input type="password" required="required" class="form-control" name="Password" value="' . $myrow['password'] . '" /></div>
	</div>
	</div>
		<div class="col-xs-row">
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Timeout (seconds)') . '</label>
		<input type="text" size="5" name="Timeout" class="form-control" value="' . $myrow['timeout'] . '" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <br />
<input type="submit" name="submit" value="' . _('Enter Information') . '" class="btn btn-success" /></div>
	</div>
	</div><br />

	</form>';

include('includes/footer.php');

?>
