<?php


include('includes/session.php');
$Title = _('Purchase Order Authorisation Maintenance');
$ViewTopic = '';
$BookMark = 'PO_AuthorisationLevels';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
	$Title, '</h1></a></div>';// Page title.


/*Note: If CanCreate==0 then this means the user can create orders
 *     Also if OffHold==0 then the user can release purchase invocies
 *     This logic confused me a bit to start with
 */


if (isset($_POST['Submit'])) {
	if (isset($_POST['CanCreate']) AND $_POST['CanCreate']=='on') {
		$CanCreate=0;
	} else {
		$CanCreate=1;
	}
	if (isset($_POST['OffHold']) AND $_POST['OffHold']=='on') {
		$OffHold=0;
	} else {
		$OffHold=1;
	}
	if ($_POST['AuthLevel']=='') {
		$_POST['AuthLevel']=0;
	}
	$sql="SELECT COUNT(*)
		FROM purchorderauth
		WHERE userid='" . $_POST['UserID'] . "'
		AND currabrev='" . $_POST['CurrCode'] . "'";
	$result=DB_query($sql);
	$myrow=DB_fetch_array($result);
	if ($myrow[0]==0) {
		$sql="INSERT INTO purchorderauth ( userid,
						currabrev,
						cancreate,
						offhold,
						authlevel)
					VALUES( '".$_POST['UserID']."',
						'".$_POST['CurrCode']."',
						'".$CanCreate."',
						'".$OffHold."',
						'" . filter_number_format($_POST['AuthLevel'])."')";
	$ErrMsg = _('The authentication details cannot be inserted because');
	$Result=DB_query($sql,$ErrMsg);
	} else {
		echo prnMsg(_('There already exists an entry for this user/currency combination'), 'error');
		echo '<br />';
	}
}

if (isset($_POST['Update'])) {
	if (isset($_POST['CanCreate']) AND $_POST['CanCreate']=='on') {
		$CanCreate=0;
	} else {
		$CanCreate=1;
	}
	if (isset($_POST['OffHold']) AND $_POST['OffHold']=='on') {
		$OffHold=0;
	} else {
		$OffHold=1;
	}
	$sql="UPDATE purchorderauth SET
			cancreate='".$CanCreate."',
			offhold='".$OffHold."',
			authlevel='".filter_number_format($_POST['AuthLevel'])."'
			WHERE userid='".$_POST['UserID']."'
			AND currabrev='".$_POST['CurrCode']."'";

	$ErrMsg = _('The authentication details cannot be updated because');
	$Result=DB_query($sql,$ErrMsg);
}

if (isset($_GET['Delete'])) {
	$sql="DELETE FROM purchorderauth
		WHERE userid='".$_GET['UserID']."'
		AND currabrev='".$_GET['Currency']."'";

	$ErrMsg = _('The authentication details cannot be deleted because');
	$Result=DB_query($sql,$ErrMsg);
}

if (isset($_GET['Edit'])) {
	$sql="SELECT cancreate,
				offhold,
				authlevel
			FROM purchorderauth
			WHERE userid='".$_GET['UserID']."'
			AND currabrev='".$_GET['Currency']."'";
	$ErrMsg = _('The authentication details cannot be retrieved because');
	$result=DB_query($sql,$ErrMsg);
	$myrow=DB_fetch_array($result);
	$UserID=$_GET['UserID'];
	$Currency=$_GET['Currency'];
	$CanCreate=$myrow['CanCreate'];
	$OffHold=$myrow['offhold'];
	$AuthLevel=$myrow['authlevel'];
}

$sql="SELECT purchorderauth.userid,
			www_users.realname,
			currencies.currabrev,
			currencies.currency,
			currencies.decimalplaces,
			purchorderauth.cancreate,
			purchorderauth.offhold,
			purchorderauth.authlevel
	FROM purchorderauth INNER JOIN www_users
		ON purchorderauth.userid=www_users.userid
	INNER JOIN currencies
		ON purchorderauth.currabrev=currencies.currabrev";

$ErrMsg = _('The authentication details cannot be retrieved because');
$Result=DB_query($sql,$ErrMsg);

echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
     <thead>
	 <tr>
		<th>' . _('User ID') . '</th>
		<th>' . _('User Name') . '</th>
		<th>' . _('Currency') . '</th>
		<th>' . _('Create Orders') . '</th>
		<th>' . _('Create') . '<br />' .  _('Invoices') . '</th>
		<th>' . _('Authority Amount') . '</th>
		<th colspan="2">' . _('Actions') . '</th>
    </tr></thead>';

while ($myrow=DB_fetch_array($Result)) {
	if ($myrow['cancreate']==0) {
		$DisplayCanCreate=_('Yes');
	} else {
		$DisplayCanCreate=_('No');
	}
	if ($myrow['offhold']==0) {
		$DisplayOffHold=_('Yes');
	} else {
		$DisplayOffHold=_('No');
	}
	echo '<tr>
			<td>' . $myrow['userid'] . '</td>
			<td>' . $myrow['realname'] . '</td>
			<td>', _($myrow['currency']), '</td>
			<td>' . $DisplayCanCreate . '</td>
			<td>' . $DisplayOffHold . '</td>
			<td class="number">' . locale_number_format($myrow['authlevel'],$myrow['decimalplaces']) . '</td>
			<td><a href="'.$RootPath.'/PO_AuthorisationLevels.php?Edit=Yes&amp;UserID=' . $myrow['userid'] .
	'&amp;Currency='.$myrow['currabrev'].'" class="btn btn-info">' . _('Edit') . '</a></td>
			<td><a href="'.$RootPath.'/PO_AuthorisationLevels.php?Delete=Yes&amp;UserID=' . $myrow['userid'] .
	'&amp;Currency='.$myrow['currabrev'].'" onclick="return confirm(\'' . _('Are you sure you wish to delete this authorisation level?') . '\');" class="btn btn-danger">' . _('Delete') . '</a></td>
		</tr>';
}

echo '</table></div></div></div>';

if (!isset($_GET['Edit'])) {
	$UserID=$_SESSION['UserID'];
	$Currency=$_SESSION['CompanyRecord']['currencydefault'];
	$CanCreate=0;
	$OffHold=0;
	$AuthLevel=0;
}
echo '<div class="sub-header"></div><br /><div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post" id="form1">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


if (isset($_GET['Edit'])) {
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('User ID') . '</label>' . $_GET['UserID'] . '</div></div>';
	echo '<input type="hidden" name="UserID" value="'.$_GET['UserID'].'" />';
} else {
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('User ID') . '</label><select name="UserID" class="form-control">';
	$usersql="SELECT userid FROM www_users";
	$userresult=DB_query($usersql);
	while ($myrow=DB_fetch_array($userresult)) {
		if ($myrow['userid']==$UserID) {
			echo '<option selected="selected" value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
		} else {
			echo '<option value="'.$myrow['userid'].'">' . $myrow['userid'] . '</option>';
		}
	}
	echo '</select></div></div>';
}

if (isset($_GET['Edit'])) {
	$sql="SELECT cancreate,
				offhold,
				authlevel,
				currency,
				decimalplaces
			FROM purchorderauth INNER JOIN currencies
			ON purchorderauth.currabrev=currencies.currabrev
			WHERE userid='".$_GET['UserID']."'
			AND purchorderauth.currabrev='".$_GET['Currency']."'";
	$ErrMsg = _('The authentication details cannot be retrieved because');
	$result=DB_query($sql,$ErrMsg);
	$myrow=DB_fetch_array($result);
	$UserID=$_GET['UserID'];
	$Currency=$_GET['Currency'];
	$CanCreate=$myrow['cancreate'];
	$OffHold=$myrow['offhold'];
	$AuthLevel=$myrow['authlevel'];
	$CurrDecimalPlaces=$myrow['decimalplaces'];

	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Currency') . '</label>
			' . $myrow['currency'] . '</div>
		</div>';
	echo '<input type="hidden" name="CurrCode" value="'.$Currency.'" />';
} else {
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Currency') . '</label>
			<select name="CurrCode" class="form-control">';
	$currencysql="SELECT currabrev,currency FROM currencies";
	$currencyresult=DB_query($currencysql);
	while ($myrow=DB_fetch_array($currencyresult)) {
		if ($myrow['currabrev']==$Currency) {
			echo '<option selected="selected" value="'.$myrow['currabrev'].'">' . $myrow['currency'] . '</option>';
		} else {
			echo '<option value="'.$myrow['currabrev'].'">' . $myrow['currency'] . '</option>';
		}
	}
	echo '</select></div></div>';
}

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Can create orders') . '</label>';
if ($CanCreate==1) {
	echo '<input type="checkbox" name="CanCreate" /></div></div>
		</div>';
} else {
	echo '<input type="checkbox" checked="checked" name="CanCreate" /></div>
		</div></div>';
}

echo '<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Can create invoices') . '</label>';
if ($OffHold==1) {
	echo '<input type="checkbox" name="OffHold" /></div>
		</div>';
} else {
	echo '<input type="checkbox" checked="checked" name="OffHold" /></div>
		</div>';
}

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Can authorise orders up to') . '</label>';
echo '<input type="text" name="AuthLevel" size="11" class="form-control" title="' . _('Enter the amount that this user is premitted to authorise purchase orders up to') . '" value="'  . locale_number_format($AuthLevel,$CurrDecimalPlaces) . '" /></div>
	</div>
	';

if (isset($_GET['Edit'])) {
	echo '<div class="col-xs-4">
<div class="form-group"> <br />
				<input type="submit" class="btn btn-info" name="Update" value="'._('Update Information').'" />
			</div></div></div>';
} else {
	echo '<div class="col-xs-4">
<div class="form-group"> 
			<input type="submit" class="btn btn-success" name="Submit" value="'._('Enter Information').'" />
		</div></div></div>';
}
echo '
        </form></div></div>';
include('includes/footer.php');
?>
