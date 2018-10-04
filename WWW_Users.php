<?php
/* Entry of users and security settings of users */

if(isset($_POST['UserID']) AND isset($_POST['ID'])) {
	if($_POST['UserID'] == $_POST['ID']) {
		$_POST['Language'] = $_POST['UserLanguage'];
	}
}

include('includes/session.php');
$Title = _('Users Maintenance');
$ViewTopic = 'GettingStarted';
$BookMark = 'UserMaintenance';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
	$Title, '</h1></a></div>';// Page title.


$ModuleList = array(
	_('Sales'),
	_('Receivables'),
	_('Purchases'),
	_('Payables'),
	_('Inventory'),
	_('HRMS'),
	_('Projects'),
	_('Manufacturing'),
	_('General Ledger'),
	_('Asset Manager'),
	_('Petty Cash'),
	_('Setup')
	
);
$ModuleListLabel = array(
	_('Display Sales module'),
	_('Display Receivables module'),
	_('Display Purchases module'),
	_('Display Payables module'),
	_('Display Inventory module'),
	_('Display HRMS module'),
	_('Display Project module'),
	_('Display Manufacturing module'),
	_('Display General Ledger module'),
	_('Display Asset Manager module'),
	_('Display Petty Cash module'),
	_('Display Setup module')
	
);
$PDFLanguages = array(
	_('Latin Western Languages - Times'),
	_('Eastern European Russian Japanese Korean Hebrew Arabic Thai'),
	_('Chinese'),
	_('Free Serif')
);

include('includes/SQL_CommonFunctions.inc');

// Make an array of the security roles
$sql = "SELECT secroleid,
				secrolename
		FROM securityroles
		ORDER BY secrolename";

$Sec_Result = DB_query($sql);
$SecurityRoles = array();
// Now load it into an a ray using Key/Value pairs
while( $Sec_row = DB_fetch_row($Sec_Result) ) {
	$SecurityRoles[$Sec_row[0]] = $Sec_row[1];
}
DB_free_result($Sec_Result);

if(isset($_GET['SelectedUser'])) {
	$SelectedUser = $_GET['SelectedUser'];
} elseif(isset($_POST['SelectedUser'])) {
	$SelectedUser = $_POST['SelectedUser'];
}

if(isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if(mb_strlen($_POST['UserID'])<4) {
		$InputError = 1;
		echo prnMsg(_('The user ID entered must be at least 4 characters long'), 'error');
	} elseif(ContainsIlLegalCharacters($_POST['UserID'])) {
		$InputError = 1;
		echo prnMsg(_('User names cannot contain any of the following characters') . " - ' &amp; + \" \\ " . _('or a space'), 'error');
	} elseif(mb_strlen($_POST['Password'])<5) {
		if(!$SelectedUser) {
			$InputError = 1;
			echo prnMsg(_('The password entered must be at least 5 characters long'), 'error');
		}
	} elseif(mb_strstr($_POST['Password'],$_POST['UserID'])!= False) {
		$InputError = 1;
		echo prnMsg(_('The password cannot contain the user id'), 'error');
	} elseif((mb_strlen($_POST['Cust'])>0)
				AND (mb_strlen($_POST['BranchCode'])==0)) {
		$InputError = 1;
		echo prnMsg(_('If you enter a Customer Code you must also enter a Branch Code valid for this Customer'), 'error');
	} elseif($AllowDemoMode AND $_POST['UserID'] == 'admin') {
		echo prnMsg(_('The demonstration user called demo cannot be modified.'), 'error');
		$InputError = 1;
	}

	if(!isset($SelectedUser)) {
		/* check to ensure the user id is not already entered */
		$Result = DB_query("SELECT userid FROM www_users WHERE userid='" . $_POST['UserID'] . "'");
		if(DB_num_rows($Result)==1) {
			$InputError =1;
			echo prnMsg(_('The user ID') . ' ' . $_POST['UserID'] . ' ' . _('already exists and cannot be used again'), 'error');
		}
	}

	if((mb_strlen($_POST['BranchCode'])>0) AND ($InputError !=1)) {
		// check that the entered branch is valid for the customer code
		$sql = "SELECT custbranch.debtorno
				FROM custbranch
				WHERE custbranch.debtorno='" . $_POST['Cust'] . "'
				AND custbranch.branchcode='" . $_POST['BranchCode'] . "'";

		$ErrMsg = _('The check on validity of the customer code and branch failed because');
		$DbgMsg = _('The SQL that was used to check the customer code and branch was');
		$Result = DB_query($sql, $ErrMsg, $DbgMsg);

		if(DB_num_rows($Result)==0) {
			echo prnMsg(_('The entered Branch Code is not valid for the entered Customer Code'), 'error');
			$InputError = 1;
		}
	}

	/* Make a comma separated list of modules allowed ready to update the database*/
	$i=0;
	$ModulesAllowed = '';
	while($i < count($ModuleList)) {
		$FormVbl = 'Module_' . $i;
		$ModulesAllowed .= $_POST[($FormVbl)] . ',';
		$i++;
	}
	$_POST['ModulesAllowed']= $ModulesAllowed;

	if(isset($SelectedUser) AND $InputError !=1) {

/*SelectedUser could also exist if submit had not been clicked this code would not run in this case cos submit is false of course see the delete code below*/

		if(!isset($_POST['Cust']) OR $_POST['Cust']==NULL OR $_POST['Cust']=='') {
			$_POST['Cust']='';
			$_POST['BranchCode']='';
		}
		$UpdatePassword = '';
		if($_POST['Password'] != '') {
			$UpdatePassword = "password='" . CryptPass($_POST['Password']) . "',";
		}

		$sql = "UPDATE www_users SET realname='" . $_POST['RealName'] . "',
						customerid='" . $_POST['Cust'] ."',
						phone='" . $_POST['Phone'] ."',
						email='" . $_POST['Email'] ."',
						" . $UpdatePassword . "
						branchcode='" . $_POST['BranchCode'] . "',
						supplierid='" . $_POST['SupplierID'] . "',
						salesman='" . $_POST['Salesman'] . "',
						pagesize='" . $_POST['PageSize'] . "',
						fullaccess='" . $_POST['Access'] . "',
						cancreatetender='" . $_POST['CanCreateTender'] . "',
						theme='" . $_POST['Theme'] . "',
						language ='" . $_POST['UserLanguage'] . "',
						defaultlocation='" . $_POST['DefaultLocation'] ."',
						modulesallowed='" . $ModulesAllowed . "',
						showdashboard='" . $_POST['ShowDashboard'] . "',
						showpagehelp='" . $_POST['ShowPageHelp'] . "',
						showfieldhelp='" . $_POST['ShowFieldHelp'] . "',
						blocked='" . $_POST['Blocked'] . "',
						pdflanguage='" . $_POST['PDFLanguage'] . "',
						department='" . $_POST['Department'] . "'
					WHERE userid = '". $SelectedUser . "'";
		echo prnMsg(_('The selected user record has been updated'), 'success');
		$_SESSION['ShowPageHelp'] = $_POST['ShowPageHelp'];
		$_SESSION['ShowFieldHelp'] = $_POST['ShowFieldHelp'];

	} elseif($InputError !=1) {

		$sql = "INSERT INTO www_users (
					userid,
					realname,
					customerid,
					branchcode,
					supplierid,
					salesman,
					password,
					phone,
					email,
					pagesize,
					fullaccess,
					cancreatetender,
					defaultlocation,
					modulesallowed,
					showdashboard,
					showpagehelp,
					showfieldhelp,
					displayrecordsmax,
					theme,
					language,
					pdflanguage,
					department)
				VALUES ('" . $_POST['UserID'] . "',
					'" . $_POST['RealName'] ."',
					'" . $_POST['Cust'] ."',
					'" . $_POST['BranchCode'] ."',
					'" . $_POST['SupplierID'] ."',
					'" . $_POST['Salesman'] . "',
					'" . CryptPass($_POST['Password']) ."',
					'" . $_POST['Phone'] . "',
					'" . $_POST['Email'] ."',
					'" . $_POST['PageSize'] ."',
					'" . $_POST['Access'] . "',
					'" . $_POST['CanCreateTender'] . "',
					'" . $_POST['DefaultLocation'] ."',
					'" . $ModulesAllowed . "',
					'" . $_POST['ShowDashboard'] . "',
					'" . $_POST['ShowPageHelp'] . "',
					'" . $_POST['ShowFieldHelp'] . "',
					'" . $_SESSION['DefaultDisplayRecordsMax'] . "',
					'" . $_POST['Theme'] . "',
					'". $_POST['UserLanguage'] ."',
					'" . $_POST['PDFLanguage'] . "',
					'" . $_POST['Department'] . "')";
		echo prnMsg(_('A new user record has been inserted'), 'success');

		$LocationSql = "INSERT INTO locationusers (loccode,
													userid,
													canview,
													canupd
												) VALUES (
													'" . $_POST['DefaultLocation'] . "',
													'" . $_POST['UserID'] . "',
													1,
													1
												)";
		$ErrMsg = _('The default user locations could not be processed because');
		$DbgMsg = _('The SQL that was used to create the user locations and failed was');
		$Result = DB_query($LocationSql, $ErrMsg, $DbgMsg);
		echo prnMsg(_('User has been authorized to use and update only his / her default location'), 'success');

		$GLAccountsSql = "INSERT INTO glaccountusers (userid, accountcode, canview, canupd)
						 SELECT '" . $_POST['UserID'] . "', chartmaster.accountcode,1,1
						 FROM chartmaster;	";

		$ErrMsg = _('The default user GL Accounts could not be processed because');
		$DbgMsg = _('The SQL that was used to create the user GL Accounts and failed was');
		$Result = DB_query($GLAccountsSql, $ErrMsg, $DbgMsg);
		echo prnMsg(_('User has been authorized to use and update all GL accounts'), 'success');
	}

	if($InputError!=1) {
		//run the SQL from either of the above possibilites
		$ErrMsg = _('The user alterations could not be processed because');
		$DbgMsg = _('The SQL that was used to update the user and failed was');
		$Result = DB_query($sql, $ErrMsg, $DbgMsg);

		unset($_POST['UserID']);
		unset($_POST['RealName']);
		unset($_POST['Cust']);
		unset($_POST['BranchCode']);
		unset($_POST['SupplierID']);
		unset($_POST['Salesman']);
		unset($_POST['Phone']);
		unset($_POST['Email']);
		unset($_POST['Password']);
		unset($_POST['PageSize']);
		unset($_POST['Access']);
		unset($_POST['CanCreateTender']);
		unset($_POST['DefaultLocation']);
		unset($_POST['ModulesAllowed']);
		unset($_POST['ShowDashboard']);
		unset($_POST['ShowPageHelp']);
		unset($_POST['ShowFieldHelp']);
		unset($_POST['Blocked']);
		unset($_POST['Theme']);
		unset($_POST['UserLanguage']);
		unset($_POST['PDFLanguage']);
		unset($_POST['Department']);
		unset($SelectedUser);
	}

} elseif(isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button


	if($AllowDemoMode AND $SelectedUser == 'admin') {
		echo prnMsg(_('The demonstration user called demo cannot be deleted'), 'error');
	} else {
		$sql = "SELECT userid FROM audittrail where userid='" . $SelectedUser ."'";
		$Result = DB_query($sql);
		if(DB_num_rows($Result)!=0) {
			echo prnMsg(_('Cannot delete user as entries already exist in the audit trail'), 'warn');
		} else {
			$sql = "DELETE FROM locationusers WHERE userid='" . $SelectedUser . "'";
			$ErrMsg = _('The Location - User could not be deleted because');
			$Result = DB_query($sql, $ErrMsg);

			$sql = "DELETE FROM glaccountusers WHERE userid='" . $SelectedUser . "'";
			$ErrMsg = _('The GL Account - User could not be deleted because');
			$Result = DB_query($sql, $ErrMsg);

			$sql = "DELETE FROM bankaccountusers WHERE userid='" . $SelectedUser . "'";
			$ErrMsg = _('The Bank Accounts - User could not be deleted because');
			$Result = DB_query($sql, $ErrMsg);

			$sql = "DELETE FROM www_users WHERE userid='" . $SelectedUser . "'";
			$ErrMsg = _('The User could not be deleted because');
			$Result = DB_query($sql, $ErrMsg);
			echo prnMsg(_('User Deleted'),'info');
		}
		unset($SelectedUser);
	}

}

if(!isset($SelectedUser)) {

/* If its the first time the page has been displayed with no parameters then none of the above are true and the list of Users will be displayed with links to delete or edit each. These will call the same page again and allow update/input or deletion of the records*/

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
				<th>', _('User Login'), '</th>
				<th>', _('Full Name'), '</th>
				<th>', _('Telephone'), '</th>
				<th>', _('Email'), '</th>
				<th>', _('Customer Code'), '</th>
				<th>', _('Branch Code'), '</th>
				<th>', _('Supplier Code'), '</th>
				<th>', _('Salesperson'), '</th>
				<th>', _('Last Visit'), '</th>
				<th>', _('Security Role'), '</th>
				<th class="noprint" colspan="2">&nbsp;</th>
			</tr>
		</thead>
		<tbody>';

	$Sql = "SELECT userid,
					realname,
					phone,
					email,
					customerid,
					branchcode,
					supplierid,
					salesman,
					lastvisitdate,
					fullaccess,
					cancreatetender,
					pagesize,
					theme,
					language
				FROM www_users";
	$Result = DB_query($Sql);

	while ($MyRow = DB_fetch_array($Result)) {
		if($MyRow[8] == '') {
			$LastVisitDate = _('No login record');
		} else {
			$LastVisitDate = ConvertSQLDate($MyRow[8]);
		}
		/*The SecurityHeadings array is defined in config.php */
		echo '<tr class="striped_row">
				<td>', $MyRow['userid'], '</td>
				<td>', $MyRow['realname'], '</td>
				<td>', $MyRow['phone'], ' </td>
				<td>', $MyRow['email'], '</td>
				<td>', $MyRow['customerid'], '</td>
				<td>', $MyRow['branchcode'], '</td>
				<td>', $MyRow['supplierid'], '</td>
				<td>', $MyRow['salesman'], '</td>
				<td class="centre">', $LastVisitDate, '</td>
				<td>', $SecurityRoles[($MyRow['fullaccess'])], '</td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?', '&amp;SelectedUser=', $MyRow['userid'], '" class="btn btn-info">', _('Edit'), '</a></td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?', '&amp;SelectedUser=', $MyRow['userid'], '&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'', _('Are you sure you wish to delete this user?'), '\');">', _('Delete'), '</a></td>
			</tr>';
	}// END foreach($Result as $MyRow).
	echo '</tbody></table></div></div></div>
		<br />';
} //end of ifs and buts!


if(isset($SelectedUser)) {
	echo '<div class="row"><div class="col-xs-4">
<a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Back to Users') . '</a></div></div><br />';
}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if(isset($SelectedUser)) {
	//editing an existing User

	$sql = "SELECT
				userid,
				realname,
				phone,
				email,
				customerid,
				password,
				branchcode,
				supplierid,
				salesman,
				pagesize,
				fullaccess,
				cancreatetender,
				defaultlocation,
				modulesallowed,
				showdashboard,
				showpagehelp,
				showfieldhelp,
				blocked,
				theme,
				language,
				pdflanguage,
				department
			FROM www_users
			WHERE userid='" . $SelectedUser . "'";

	$Result = DB_query($sql);
	$myrow = DB_fetch_array($Result);

	$_POST['UserID'] = $myrow['userid'];
	$_POST['RealName'] = $myrow['realname'];
	$_POST['Phone'] = $myrow['phone'];
	$_POST['Email'] = $myrow['email'];
	$_POST['Cust']	= $myrow['customerid'];
	$_POST['BranchCode'] = $myrow['branchcode'];
	$_POST['SupplierID'] = $myrow['supplierid'];
	$_POST['Salesman'] = $myrow['salesman'];
	$_POST['PageSize'] = $myrow['pagesize'];
	$_POST['Access'] = $myrow['fullaccess'];
	$_POST['CanCreateTender'] = $myrow['cancreatetender'];
	$_POST['DefaultLocation'] = $myrow['defaultlocation'];
	$_POST['ModulesAllowed'] = $myrow['modulesallowed'];
	$_POST['ShowDashboard'] = $myrow['showdashboard'];
	$_POST['ShowPageHelp'] = $myrow['showpagehelp'];
	$_POST['ShowFieldHelp'] = $myrow['showfieldhelp'];
	$_POST['Blocked'] = $myrow['blocked'];
	$_POST['Theme'] = $myrow['theme'];
	$_POST['UserLanguage'] = $myrow['language'];
	$_POST['PDFLanguage'] = $myrow['pdflanguage'];
	$_POST['Department'] = $myrow['department'];

	echo '<input type="hidden" name="SelectedUser" value="' . $SelectedUser . '" />';
	echo '<input type="hidden" name="UserID" value="' . $_POST['UserID'] . '" />';
	echo '<input type="hidden" name="ModulesAllowed" value="' . $_POST['ModulesAllowed'] . '" />';

	echo '<br /><div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">
' . _('User Code') . '</label>
				' . $_POST['UserID'] . '</div>
			</div>';

} else { //end of if $SelectedUser only do the else when a new record is being entered

	echo '<br /><div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('User Login') . '</label>
				<input pattern="(?!^([aA]{1}[dD]{1}[mM]{1}[iI]{1}[nN]{1})$)[^?+.&\\>< ]{4,}" type="text" class="form-control" required="required" name="UserID" size="22" maxlength="20" placeholder="'._('At least 4 characters').'" title="'._('Please input not less than 4 characters and canot be admin or contains illegal characters').'" /></div>
			</div>';

	/*set the default modules to show to all
	this had trapped a few people previously*/
	$i=0;
	if(!isset($_POST['ModulesAllowed'])) {
		$_POST['ModulesAllowed']='';
	}
	foreach($ModuleList as $ModuleName) {
		if($i>0) {
			$_POST['ModulesAllowed'] .=',';
		}
		$_POST['ModulesAllowed'] .= '1';
		$i++;
	}
	$_POST['ShowDashboard'] = 0;
	$_POST['ShowPageHelp'] = 1;
	$_POST['ShowFieldHelp'] = 1;
}

if(!isset($_POST['Password'])) {
	$_POST['Password']='';
}
if(!isset($_POST['RealName'])) {
	$_POST['RealName']='';
}
if(!isset($_POST['Phone'])) {
	$_POST['Phone']='';
}
if(!isset($_POST['Email'])) {
	$_POST['Email']='';
}
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Password') . '</label>
		<input type="password" pattern=".{5,}" name="Password" ' . (!isset($SelectedUser) ? 'required="required"' : '') . ' size="22" maxlength="20" value="' . $_POST['Password'] . '" placeholder="'._('At least 5 characters').'" class="form-control" title="'._('Passwords must be 5 characters or more and cannot same as the users id. A mix of upper and lower case and some non-alphanumeric characters are recommended.').'" /></div></div>';
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Full Name') . '</label>
		<input type="text" class="form-control" name="RealName" ' . (isset($SelectedUser) ? 'autofocus="autofocus"' : '') . ' required="required" value="' . $_POST['RealName'] . '" size="36" maxlength="35" /></div></div></div>';
echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Telephone No') . '</label>
		<input type="tel" class="form-control" name="Phone" pattern="[0-9+()\s-]*" value="' . $_POST['Phone'] . '" size="32" maxlength="30" /></div></div>';
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Email Address') .'</label>
		<input type="email" class="form-control" name="Email" placeholder="' . _('e.g. user@domain.com') . '" required="required" value="' . $_POST['Email'] .'" size="32" maxlength="55" title="'._('A valid email address is required').'" /></div></div>';
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Security Role') . '</label>
		<select name="Access" class="form-control">';

foreach($SecurityRoles as $SecKey => $SecVal) {
	if(isset($_POST['Access']) and $SecKey == $_POST['Access']) {
		echo '<option selected="selected" value="' . $SecKey . '">' . $SecVal . '</option>';
	} else {
		echo '<option value="' . $SecKey . '">' . $SecVal . '</option>';
	}
}
echo '</select>';
echo '<input type="hidden" name="ID" value="'.$_SESSION['UserID'].'" /></div></div></div>';

echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('User Can Create Tenders') . '</label>
		<select name="CanCreateTender" class="form-control">';

if($_POST['CanCreateTender']==0) {
	echo '<option selected="selected" value="0">' . _('No') . '</option>';
	echo '<option value="1">' . _('Yes') . '</option>';
} else {
 	echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
	echo '<option value="0">' . _('No') . '</option>';
}
echo '</select></div></div>';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Default Location') . '</label>
		<select name="DefaultLocation" class="form-control">';

$sql = "SELECT loccode, locationname FROM locations";
$Result = DB_query($sql);

while($myrow=DB_fetch_array($Result)) {
	if(isset($_POST['DefaultLocation']) AND $myrow['loccode'] == $_POST['DefaultLocation']) {
		echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	} else {
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
}

echo '</select></div></div>';

if(!isset($_POST['Cust'])) {
	$_POST['Cust']='';
}
if(!isset($_POST['BranchCode'])) {
	$_POST['BranchCode']='';
}
if(!isset($_POST['SupplierID'])) {
	$_POST['SupplierID']='';
}
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Code') . '</label>
		<input type="text" class="form-control" name="Cust" data-type="no-ilLegal-chars" title="' . _('If this user login is to be associated with a customer account, enter the customer account code') . '" size="10" maxlength="10" value="' . $_POST['Cust'] . '" /></div></div></div>';

echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Branch Code') . '</label>
		<input type="text" class="form-control" name="BranchCode" data-type="no-ilLegal-chars" title="' . _('If this user login is to be associated with a customer account a valid branch for the customer account must be entered.') . '" size="10" maxlength="10" value="' . $_POST['BranchCode'] .'" /></div></div>';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Supplier Code') . '</label>
		<input type="text" class="form-control" name="SupplierID" data-type="no-ilLegal-chars" size="10" maxlength="10" value="' . $_POST['SupplierID'] .'" /></div></div>';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Restrict to Sales Person') . '</label>
		<select name="Salesman" class="form-control">';

$sql = "SELECT salesmancode, salesmanname FROM salesman WHERE current = 1 ORDER BY salesmanname";
$Result = DB_query($sql);
if((isset($_POST['Salesman']) AND $_POST['Salesman']=='') OR !isset($_POST['Salesman'])) {
	echo '<option selected="selected" value="">' . _('Not a salesperson only login') . '</option>';
} else {
	echo '<option value="">' . _('Not a salesperson only login') . '</option>';
}
while($myrow=DB_fetch_array($Result)) {

	if(isset($_POST['Salesman']) AND $myrow['salesmancode'] == $_POST['Salesman']) {
		echo '<option selected="selected" value="' . $myrow['salesmancode'] . '">' . $myrow['salesmanname'] . '</option>';
	} else {
		echo '<option value="' . $myrow['salesmancode'] . '">' . $myrow['salesmanname'] . '</option>';
	}

}

echo '</select></div></div></div>';

echo '
<input type="hidden" name="PageSize" value="A4" />
<input type="hidden" name="Theme" value="fluid" />
<input type="hidden" name="UserLanguage" value="en_IN.utf8" />

';



echo '<div class="row">';

/*Make an array out of the comma separated list of modules allowed*/
$ModulesAllowed = explode(',',$_POST['ModulesAllowed']);
$i = 0;
foreach($ModuleList as $ModuleName) {
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">', $ModuleListLabel[$i], ':</label>
			<select id="Module_', $i, '" name="Module_', $i, '" class="form-control">';
	if($ModulesAllowed[$i] == 0) {
		echo '<option selected="selected" value="0">', _('No'), '</option>',
			 '<option value="1">', _('Yes'), '</option>';
	} else {
		echo '<option value="0">', _('No'), '</option>',
	 		 '<option selected="selected" value="1">', _('Yes'), '</option>';
	}
	echo '</select></div>
		</div>';
	$i++;
}// END foreach($ModuleList as $ModuleName).

// Turn off/on dashboard:
echo '</div><div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">', _('Display dashboard'), ':</label>
		<select id="ShowDashboard" name="ShowDashboard" class="form-control">';
if($_POST['ShowDashboard']==0) {
	echo '<option selected="selected" value="0">', _('No'), '</option>',
		 '<option value="1">', _('Yes'), '</option>';
} else {
	echo '<option value="0">', _('No'), '</option>',
 		 '<option selected="selected" value="1">', _('Yes'), '</option>';
}
echo '</select>',
		(!isset($_SESSION['ShowFieldHelp']) || $_SESSION['ShowFieldHelp'] ? _('Show dashboard page after login') : ''), // If the parameter $_SESSION['ShowFieldHelp'] is not set OR is TRUE, shows this field help text.
		'</div></div>';
// Turn off/on page help:
echo '
<input type="hidden" value="0" name="ShowPageHelp" />
<input type="hidden" value="0" name="ShowFieldHelp" />
<input type="hidden" value="0" name="PDFLanguage" />
';
// Turn off/on field help:

/* Allowed Department for Internal Requests */

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Allowed Department for Internal Requests') . '</label>';

$sql = "SELECT departmentid,
			description
		FROM departments
		ORDER BY description";

$Result=DB_query($sql);
echo '<select name="Department" class="form-control">';
if((isset($_POST['Department']) AND $_POST['Department']=='0') OR !isset($_POST['Department'])) {
	echo '<option selected="selected" value="0">' . _('Any Internal Department') . '</option>';
} else {
	echo '<option value="">' . _('Any Internal Department') . '</option>';
}
while($myrow=DB_fetch_array($Result)) {
	if(isset($_POST['Department']) AND $myrow['departmentid'] == $_POST['Department']) {
		echo '<option selected="selected" value="' . $myrow['departmentid'] . '">' . $myrow['description'] . '</option>';
	} else {
		echo '<option value="' . $myrow['departmentid'] . '">' . $myrow['description'] . '</option>';
	}
}
echo '</select></div></div>';

/* Account status */

echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Account Status') . '</label>
		<select required="required" name="Blocked" class="form-control">';
if($_POST['Blocked']==0) {
	echo '<option selected="selected" value="0">' . _('Open') . '</option>';
	echo '<option value="1">' . _('Blocked') . '</option>';
} else {
 	echo '<option selected="selected" value="1">' . _('Blocked') . '</option>';
	echo '<option value="0">' . _('Open') . '</option>';
}
echo '</select></div></div></div>';

echo '<div class="row" align="center">
	<div>
		<input type="submit" class="btn btn-success" name="submit" value="' . _('Enter Information') . '" />
	</div>
 </div><br />

	</form>';

include('includes/footer.php');
?>
