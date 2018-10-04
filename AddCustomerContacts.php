<?php
/* Adds customer contacts */

include('includes/session.php');
$Title = _('Customer Contacts');
$ViewTopic = 'AccountsReceivable';
$BookMark = 'AddCustomerContacts';
include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['Id'])){
	$Id = (int)$_GET['Id'];
} else if (isset($_POST['Id'])){
	$Id = (int)$_POST['Id'];
}
if (isset($_POST['DebtorNo'])){
	$DebtorNo = $_POST['DebtorNo'];
} elseif (isset($_GET['DebtorNo'])){
	$DebtorNo = $_GET['DebtorNo'];
}

$Result = DB_query($SQLname);
$row = DB_fetch_array($Result);
if (!isset($_GET['Id'])) {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Contacts for Customer') . ': <strong>' . $DebtorNo . '</strong></h1></a></div>';
} else {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Edit contact for'). ': <strong>' . $DebtorNo . '</strong></h1></a></div>';
}
echo '<div class="row"><div class="col-xs-4"><a class="noprint btn btn-default" href="' . $RootPath . '/SelectCustomer.php?Select=' . $DebtorNo . '">' . _('Back To Customers') . '</a></div>';
if (isset($Id)) {
	echo '<div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?DebtorNo='.$DebtorNo .'" class="btn btn-info">' . _('Review all contacts for this Customer') . '</a></div>';
}
echo '</div><br />';
$SQLname="SELECT name FROM debtorsmaster WHERE debtorno='" . $DebtorNo . "'";
if ( isset($_POST['submit']) ) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;
	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if (isset($_POST['Con_ID']) AND !is_long((integer)$_POST['Con_ID'])) {
		$InputError = 1;
		echo prnMsg( _('The Contact ID must be an integer.'), 'error');
	} elseif (mb_strlen($_POST['ContactName']) >40) {
		$InputError = 1;
		echo prnMsg( _('The contact name must be forty characters or less'), 'error');
	} elseif( trim($_POST['ContactName']) == '' ) {
		$InputError = 1;
		echo prnMsg( _('The contact name can not be empty'), 'error');
	} elseif (!IsEmailAddress($_POST['ContactEmail']) AND mb_strlen($_POST['ContactEmail'])>0){
		$InputError = 1;
		echo prnMsg( _('The contact email address is not valid'), 'error');
	}

	if (isset($Id) AND ($Id AND $InputError !=1)) {
		$sql = "UPDATE custcontacts SET contactname='" . $_POST['ContactName'] . "',
										role='" . $_POST['ContactRole'] . "',
										phoneno='" . $_POST['ContactPhone'] . "',
										notes='" . $_POST['ContactNotes'] . "',
										email='" . $_POST['ContactEmail'] . "',
										statement='" . $_POST['StatementAddress'] . "'
					WHERE debtorno ='".$DebtorNo."'
					AND contid='".$Id."'";
		$msg = _('Customer Contacts') . ' ' . $DebtorNo . ' ' . _('has been updated');
	} elseif ($InputError !=1) {

		$sql = "INSERT INTO custcontacts (debtorno,
										contactname,
										role,
										phoneno,
										notes,
										email,
										statement)
				VALUES ('" . $DebtorNo. "',
						'" . $_POST['ContactName'] . "',
						'" . $_POST['ContactRole'] . "',
						'" . $_POST['ContactPhone'] . "',
						'" . $_POST['ContactNotes'] . "',
						'" . $_POST['ContactEmail'] . "',
						'" . $_POST['StatementAddress'] . "')";
		$msg = _('The contact record has been added');
	}

	if ($InputError !=1) {
		$result = DB_query($sql);
				//echo '<br />' . $sql;

		echo '<br />';
		echo prnMsg($msg, 'success');
		echo '<br />';
		unset($Id);
		unset($_POST['ContactName']);
		unset($_POST['ContactRole']);
		unset($_POST['ContactPhone']);
		unset($_POST['ContactNotes']);
		unset($_POST['ContactEmail']);
		unset($_POST['Con_ID']);
	}
} elseif (isset($_GET['delete']) AND $_GET['delete']) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'SalesOrders'

	$sql="DELETE FROM custcontacts
			WHERE contid='" . $Id . "'
			AND debtorno='" . $DebtorNo . "'";
	$result = DB_query($sql);

	echo '<br />';
	echo prnMsg( _('The contact record has been deleted'), 'success');
	unset($Id);
	unset($_GET['delete']);

}

if (!isset($Id)) {

	$sql = "SELECT contid,
					debtorno,
					contactname,
					role,
					phoneno,
					statement,
					notes,
					email
			FROM custcontacts
			WHERE debtorno='".$DebtorNo."'
			ORDER BY contid";
	$result = DB_query($sql);
			//echo '<br />' . $sql;

	echo '
<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
			<table id="general-table" class="table table-bordered">';
	echo '<tr>
			<th class="text">', _('Name'), '</th>
			<th class="text">', _('Role'), '</th>
			<th class="text">', _('Phone'), '</th>
			<th class="text">', _('Email'), '</th>
			<th class="text">', _('Statement'), '</th>
			<th class="text">', _('Notes'), '</th>
			<th class="noprint" colspan="2">&nbsp;</th>
		</tr>';

	while ($myrow = DB_fetch_array($result)) {
		printf('<tr class="striped_row">
				<td class="text">%s</td>
				<td class="text">%s</td>
				<td class="text">%s</td>
				<td class="text"><a href="mailto:%s">%s</a></td>
				<td class="text">%s</td>
				<td class="text">%s</td>
				<td class="noprint"><a href="%sId=%s&amp;DebtorNo=%s" class="btn btn-warning">' . _('Edit') . '</a></td>
				<td class="noprint"><a href="%sId=%s&amp;DebtorNo=%s&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this contact?') . '\');">' . _('Delete'). '</a></td>
				</tr>',
				$myrow['contactname'],
				$myrow['role'],
				$myrow['phoneno'],
				$myrow['email'],
				$myrow['email'],
				($myrow['statement']==0) ? _('No') : _('Yes'),
				$myrow['notes'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['contid'],
				$myrow['debtorno'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['contid'],
				$myrow['debtorno']);

	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div>';
}


if (!isset($_GET['delete'])) {
echo '
<div class="row gutter30">
        <div class="col-xs-12">
		<div class="block">
		';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?DebtorNo='.$DebtorNo.'">',
		
		'<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($Id)) {// Edit Customer Contact Details.
		$sql = "SELECT contid,
						debtorno,
						contactname,
						role,
						phoneno,
						notes,
						email
					FROM custcontacts
					WHERE contid='".$Id."'
						AND debtorno='".$DebtorNo."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['Con_ID'] = $myrow['contid'];
		$_POST['ContactName'] = $myrow['contactname'];
		$_POST['ContactRole'] = $myrow['role'];
		$_POST['ContactPhone']  = $myrow['phoneno'];
		$_POST['ContactEmail'] = $myrow['email'];
		$_POST['ContactNotes'] = $myrow['notes'];
		$_POST['DebtorNo'] = $myrow['debtorno'];
		echo '<input type="hidden" name="Id" value="'. $Id .'" />',
			'<input type="hidden" name="Con_ID" value="' . $_POST['Con_ID'] . '" />',
			'<input type="hidden" name="DebtorNo" value="' . $_POST['DebtorNo'] . '" />',
			'
				
		                <div class="block-title">
                            <h2>' . _('Edit Customer Contact Details').'</h2>
                        </div>
					<div class="row"><h4>', _('Contact Code'), ': <span class="text-success">
					<strong>' . $_POST['Con_ID']. '</strong>
				</span></h4></div>
					';
	} else {// New Customer Contact Details.
		echo ' <div class="block-title"> <h2>', _('New Customer Contact Details'), '</h2>
                        </div>
			
		';
	}
	// Contact name:
	echo '<div class="row">
	<div class="col-xs-4">
               <div class="form-group has-error"> 
			   <label class="col-md-8 control-label">', _('Contact Name'), '</label>
			<input maxlength="40" name="ContactName" class="form-control" required="required" size="35" type="text" ';
				if( isset($_POST['ContactName']) ) {
					echo 'autofocus="autofocus" value="', $_POST['ContactName'], '" ';
				}
				echo '/></div>
		</div>';
	// Role:
	echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">', _('Role'), '</label>
			<input maxlength="40" name="ContactRole" class="form-control" size="35" type="text" ';
				if( isset($_POST['ContactRole']) ) {
					echo 'value="', $_POST['ContactRole'], '" ';
				}
				echo '/></div>
		</div>';
	// Phone:
	echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">', _('Phone'), '</label>
			<input maxlength="40" name="ContactPhone" class="form-control" size="35" type="tel" ';
				if( isset($_POST['ContactPhone']) ) {
					echo 'value="', $_POST['ContactPhone'], '" ';
				}
				echo '/></div>
		</div></div>';
	// Email:
	echo ' <div class="row">
	<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">', _('Email'), '</label>
			<input maxlength="55" name="ContactEmail" class="form-control" size="55" type="email" ';
				if( isset($_POST['ContactEmail']) ) {
					echo 'value="', $_POST['ContactEmail'], '" ';
				}
				echo '/></div>
		</div>';
	echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">', _('Send Statement'), '</label>
			<select name="StatementAddress" class="form-control" title="' , _('This flag identifies the contact as one who should receive an email cusstomer statement') , '" >';
				if( !isset($_POST['StatementAddress']) ) {
					echo '<option selected="selected" value="0">', _('No') , '</option>
							<option value="1">', _('Yes') , '</option>';
				} else {
					if ($_POST['StatementAddress']==0) {
						echo '<option selected="selected" value="0">', _('No') , '</option>
								<option value="1">', _('Yes') , '</option>';
					} else {
						echo '<option value="0">', _('No') , '</option>
								<option selected="selected" value="1">', _('Yes') , '</option>';
					}
				}
				echo '</select></div>
		</div>';
	// Notes:
	echo '<div class="col-xs-4">
               <div class="form-group"> 
			   <label class="col-md-8 control-label">', _('Notes'), '</label>
			<textarea cols="40" name="ContactNotes" class="form-control">',
				( isset($_POST['ContactNotes']) ? $_POST['ContactNotes'] : '' ),
				'</textarea></div>
		</div></div>',

		'<div class="row">
			<div class="col-xs-4">
               <div class="form-group"> 
			   
				<input name="submit" type="submit" class="btn btn-success" value="', _('Enter Information'), '" />
			</div>
		</div>
		</div>
		
		
		</form>
		</div>
		</div>
		</div>
		
		';

} //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>