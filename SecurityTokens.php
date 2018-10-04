<?php
/* Administration of security tokens */

include('includes/session.php');
$Title = _('Maintain Security Tokens');
$ViewTopic = 'SecuritySchema';
$BookMark = 'SecurityTokens';// Pending ?
include('includes/header.php');

// Merge gets into posts:
if(isset($_GET['Action'])) {
	$_POST['Action'] = $_GET['Action'];
}
if(isset($_GET['TokenId'])) {
	$_POST['TokenId'] = $_GET['TokenId'];
}
if(isset($_GET['TokenDescription'])) {
	$_POST['TokenDescription'] = $_GET['TokenDescription'];
}

// Validate the data sent:
$InputError = 0;
if($_POST['Action']=='insert' OR $_POST['Action']=='update') {
	if(!is_numeric($_POST['TokenId'])) {
		echo prnMsg(_('The token ID is expected to be a number. Please enter a number for the token ID'), 'error');
		$InputError = 1;
	}
	if(mb_strlen($_POST['TokenId']) == 0) {
		echo prnMsg(_('A token ID must be entered'), 'error');
		$InputError = 1;
	}
	if(mb_strlen($_POST['TokenDescription']) == 0) {
		echo prnMsg(_('A token description must be entered'), 'error');
		$InputError = 1;
	}
}

// Execute the requested action:
switch($_POST['Action']) {
    case 'cancel':
		unset($_POST['Action']);
		unset($_POST['TokenId']);
		unset($_POST['TokenDescription']);
		break;
    case 'delete':
		$Result = DB_query("SELECT script FROM scripts WHERE pagesecurity='" . $_POST['TokenId'] . "'");
		if(DB_num_rows($Result) > 0) {
			$List = '';
			while($ScriptRow = DB_fetch_array($Result)) {
					$List .= ' ' . $ScriptRow['script'];
				}
			echo prnMsg(_('This security token is currently used by the following scripts and cannot be deleted') . ':' . $List, 'error');
		} else {
			$Result = DB_query("DELETE FROM securitytokens WHERE tokenid='" . $_POST['TokenId'] . "'");
			if($Result) {echo prnMsg(_('The security token was deleted successfully'), 'success');}
		}
		unset($_POST['Action']);
		unset($_POST['TokenId']);
		unset($_POST['TokenDescription']);
		break;
    case 'edit':
		$Result = DB_query("SELECT tokenid, tokenname FROM securitytokens WHERE tokenid='" . $_POST['TokenId'] . "'");
		$MyRow = DB_fetch_array($Result);
		// Keeps $_POST['Action']=edit, and sets $_POST['TokenId'] and $_POST['TokenDescription'].
		$_POST['TokenId'] = $MyRow['tokenid'];
		$_POST['TokenDescription'] = $MyRow['tokenname'];
		break;
    case 'insert':
		$Result = DB_query("SELECT tokenid FROM securitytokens WHERE tokenid='" . $_POST['TokenId'] . "'");
		if(DB_num_rows($Result) != 0) {
			echo prnMsg( _('This token ID has already been used. Please use a new one') , 'warn');
			$InputError = 1;
		}
		if($InputError == 0) {
			$Result = DB_query("INSERT INTO securitytokens values('" . $_POST['TokenId'] . "', '" . $_POST['TokenDescription'] . "')");
			if($Result) {echo prnMsg(_('The security token was inserted successfully'), 'success');}
			unset($_POST['Action']);
			unset($_POST['TokenId']);
			unset($_POST['TokenDescription']);
		}
		break;
    case 'update':
		if($InputError == 0) {
			$Result = DB_query("UPDATE securitytokens SET tokenname='" . $_POST['TokenDescription'] . "' WHERE tokenid='" . $_POST['TokenId'] . "'");
			if($Result) {echo prnMsg(_('The security token was updated successfully'), 'success');}
			unset($_POST['Action']);
			unset($_POST['TokenId']);
			unset($_POST['TokenDescription']);
		}
		break;
    default:// Unknown requested action.
		unset($_POST['Action']);
		unset($_POST['TokenId']);
		unset($_POST['TokenDescription']);
}// END switch($_POST['Action']).

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
	$Title, '</h1></a></div>', // Page title.
// Security Token Data table:
	'<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<thead>
		<tr>
			<th>', _('Token ID'), '</th>
			<th>', _('Description'), '</th>
			<th colspan="2">', _('Actions'), '</th>
			
		</tr>
	</thead><tbody>';
$Result = DB_query("SELECT tokenid, tokenname FROM securitytokens ORDER BY tokenid");
while($MyRow = DB_fetch_array($Result)) {
	echo '<tr>
			<td class="number">', $MyRow['tokenid'], '</td>
			<td class="text">', htmlspecialchars($MyRow['tokenname'], ENT_QUOTES, 'UTF-8'), '</td>
			<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Action=edit&amp;TokenId=', $MyRow['tokenid'], '" class="btn btn-info">', _('Edit'), '</a></td>
			<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Action=delete&amp;TokenId=', $MyRow['tokenid'], '" class="btn btn-danger" onclick="return confirm(\'', _('Are you sure you wish to delete this security token?'), '\');">', _('Delete'), '</a></td>
		</tr>';
}
echo '</tbody></table>
</div></div></div>
	<br />
	<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" id="form" method="post">
	<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />
	<div class="block">
	';
// Edit or New Security Token form table:
if(isset($_POST['Action']) and $_POST['Action']=='edit') {
	echo '<div class="block-title"><h3>' .('Edit Security Token'), '</h3></div>',
		
		'<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Token ID'), '</label>
			', $_POST['TokenId'], '<input name="TokenId" type="hidden" value="';
} else {
	echo '<div class="block-title"><h3>'.		('New Security Token'), '</h3>',
		'</div>
		
		<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Token ID'), '</label>
			<input autofocus="autofocus" class="form-control" id="TokenId" maxlength="4" name="TokenId" required="required" size="6" type="text" value="';
}
echo			$_POST['TokenId'], '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Description'), '</label>
			<input id="TokenDescription" class="form-control" maxlength="60" name="TokenDescription" required="required" size="50" title="', _('The security token description should describe which functions this token allows a user/role to access'), '" type="text" value="', $_POST['TokenDescription'], '" /></div>
		</div>
	</div>';
	
if(isset($_POST['Action']) and $_POST['Action']=='edit') {
echo '
<div class="row">
				<div class="col-xs-4">
<button name="Action" type="submit" value="update" class="btn btn-info">', _('Enter Information'), '</button></div>
				
				<div align="right">
<button onclick="window.location=\'menu_data.php?Application=system\'" class="btn btn-default" type="button">', _('Back to Menu'), '</button></div>
			</div><br />
 ';
            
            }
            
 else
 {
echo '<div class="row">
				<div class="col-xs-4">
				<button name="Action" type="submit" class="btn btn-success" value="insert">', _('Enter Information'), '</button></div>
				<div align="right"><button onclick="window.location=\'menu_data.php?Application=system\'" type="button" class="btn btn-default">', _('Back to Menu'), '</button></div>
			</div> <br />
';
            }     	
	
	
	echo '</div></form>';

include('includes/footer.php');
?>
