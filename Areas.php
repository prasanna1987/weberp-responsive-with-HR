<?php


include('includes/session.php');

$Title = _('Sales Area Maintenance');
$ViewTopic= 'CreatingNewSystem';
$BookMark = 'Areas';
include('includes/header.php');


if (isset($_GET['SelectedArea'])){
	$SelectedArea = mb_strtoupper($_GET['SelectedArea']);
} elseif (isset($_POST['SelectedArea'])){
	$SelectedArea = mb_strtoupper($_POST['SelectedArea']);
}

if (isset($Errors)) {
	unset($Errors);
}
$Errors = array();

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;
	$i=1;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$_POST['AreaCode'] = mb_strtoupper($_POST['AreaCode']);
	$sql = "SELECT areacode FROM areas WHERE areacode='".$_POST['AreaCode']."'";
	$result = DB_query($sql);
	// mod to handle 3 char area codes
	if (mb_strlen($_POST['AreaCode']) > 3) {
		$InputError = 1;
		echo prnMsg(_('The area code must be three characters or less'),'error');
		$Errors[$i] = 'AreaCode';
		$i++;
	} elseif (DB_num_rows($result)>0 AND !isset($SelectedArea)){
		$InputError = 1;
		echo prnMsg(_('The area code entered already exists'),'error');
		$Errors[$i] = 'AreaCode';
		$i++;
	} elseif (mb_strlen($_POST['AreaDescription']) >25) {
		$InputError = 1;
		echo prnMsg(_('The area description must be twenty five characters or less'),'error');
		$Errors[$i] = 'AreaDescription';
		$i++;
	} elseif ( trim($_POST['AreaCode']) == '' ) {
		$InputError = 1;
		echo prnMsg(_('The area code can not be empty'),'error');
		$Errors[$i] = 'AreaCode';
		$i++;
	} elseif ( trim($_POST['AreaDescription']) == '' ) {
		$InputError = 1;
		echo prnMsg(_('The area description can not be empty'),'error');
		$Errors[$i] = 'AreaDescription';
		$i++;
	}

	if (isset($SelectedArea) AND $InputError !=1) {

		/*SelectedArea could also exist if submit had not been clicked this code would not run in this case cos submit is false of course  see the delete code below*/

		$sql = "UPDATE areas SET areadescription='" . $_POST['AreaDescription'] . "'
								WHERE areacode = '" . $SelectedArea . "'";

		$msg = _('Area code') . ' ' . $SelectedArea  . ' ' . _('has been updated');

	} elseif ($InputError !=1) {

	/*Selectedarea is null cos no item selected on first time round so must be adding a record must be submitting new entries in the new area form */

		$sql = "INSERT INTO areas (areacode,
									areadescription
								) VALUES (
									'" . $_POST['AreaCode'] . "',
									'" . $_POST['AreaDescription'] . "'
								)";

		$SelectedArea = $_POST['AreaCode'];
		$msg = _('New area code') . ' ' . $_POST['AreaCode'] . ' ' . _('has been inserted');
	} else {
		$msg = '';
	}

	//run the SQL from either of the above possibilites
	if ($InputError !=1) {
		$ErrMsg = _('The area could not be added or updated because');
		$DbgMsg = _('The SQL that failed was');
		$result = DB_query($sql, $ErrMsg, $DbgMsg);
		unset($SelectedArea);
		unset($_POST['AreaCode']);
		unset($_POST['AreaDescription']);
		echo prnMsg($msg,'success');
	}

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'DebtorsMaster'

	$sql= "SELECT COUNT(branchcode) AS branches FROM custbranch WHERE custbranch.area='$SelectedArea'";
	$result = DB_query($sql);
	$myrow = DB_fetch_array($result);
	if ($myrow['branches']>0) {
		$CancelDelete = 1;
		echo prnMsg( _('Cannot delete this area because customer branches have been created using this area'),'warn');
               
		echo '<br />' . _('There are') . ' ' . $myrow['branches'] . ' ' . _('branches using this area code'),' </div>';;

	} else {
		$sql= "SELECT COUNT(area) AS records FROM salesanalysis WHERE salesanalysis.area ='$SelectedArea'";
		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);
		if ($myrow['records']>0) {
			$CancelDelete = 1;
			echo prnMsg( _('Cannot delete this area because sales analysis records exist that use this area'),'warn');
			echo '<br />' . _('There are') . ' ' . $myrow['records'] . ' ' . _('sales analysis records referring this area code');
		}
	}

	if ($CancelDelete==0) {
		$sql="DELETE FROM areas WHERE areacode='" . $SelectedArea . "'";
		$result = DB_query($sql);
		echo prnMsg(_('Area Code') . ' ' . $SelectedArea . ' ' . _('has been deleted') .' !','success');
	} //end if Delete area
	unset($SelectedArea);
	unset($_GET['delete']);
}

if (!isset($SelectedArea)) {

	$sql = "SELECT areacode,
					areadescription
				FROM areas";
	$result = DB_query($sql);

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead><tr>
				<th>' . _('Area Code') . '</th>
				<th>' . _('Area Name') . '</th>
				<th colspan="3">' . _('Actions') . '</th>
			</tr></thead>';

	while ($myrow = DB_fetch_array($result)) {
		echo '<tr class="striped_row">
				<td>' . $myrow['areacode'] . '</td>
				<td>' . $myrow['areadescription'] . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?SelectedArea=' . $myrow['areacode'] . '" class="btn btn-info">' . _('Edit') . '</a></td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?SelectedArea=' . $myrow['areacode'] . '&amp;delete=yes" class="btn btn-danger">' . _('Delete') . '</a></td>
				<td><a href="SelectCustomer.php?Area=' . $myrow['areacode'] . '" class="btn btn-info">' . _('View Customers from this Area') . '</a></td>
			</tr>';
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div>';
}

//end of ifs and buts!

if (isset($SelectedArea)) {
	echo '<div class="row"><div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-info">' . _('Review Areas Defined') . '</a></div></div><br />';
}


if (!isset($_GET['delete'])) {
echo '
<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($SelectedArea)) {
		//editing an existing area

		$sql = "SELECT areacode,
						areadescription
					FROM areas
					WHERE areacode='" . $SelectedArea . "'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['AreaCode'] = $myrow['areacode'];
		$_POST['AreaDescription']  = $myrow['areadescription'];

		echo '<input type="hidden" name="SelectedArea" value="' . $SelectedArea . '" />';
		echo '<input type="hidden" name="AreaCode" value="' .$_POST['AreaCode'] . '" />';
		echo '<div class="block">
<div class="block-title"><h3>Edit Area '.$SelectedArea.'</h3></div>
<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Area Code') . '</label>
					' . $_POST['AreaCode'] . '</div>
				</div>';

	} else {
		if (!isset($_POST['AreaCode'])) {
			$_POST['AreaCode'] = '';
		}
		if (!isset($_POST['AreaDescription'])) {
			$_POST['AreaDescription'] = '';
		}
		echo '
		
		<div class="block">
<div class="block-title"><h3>Add Area</h3></div>
		<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Area Code') . '</label>
				<input tabindex="1" ' . (in_array('AreaCode',$Errors) ? 'class="inputerror"' : '' ) .' type="text" class="form-control" name="AreaCode" required="required" autofocus="autofocus" value="' . $_POST['AreaCode'] . '" size="3" maxlength="3" title="' . _('Enter the sales area code - up to 3 characters are allowed') . '" /></div>
			</div>';
	}

	echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Area Name') . '</label>
		<input tabindex="2" ' . (in_array('AreaDescription',$Errors) ?  'class="inputerror"' : '' ) .'  type="text" class="form-control" required="required" name="AreaDescription" value="' . $_POST['AreaDescription'] .'" size="26" maxlength="25" title="' . _('Enter the description of the sales area') . '" /></div>
		</div>';

	echo '<div class="col-xs-4">
<div class="form-group"><br />
					<input tabindex="3" type="submit" class="btn btn-success" name="submit" value="' . _('Submit') .'" />
				</div>
			</div>
		</div>
		</div>
		</form></div></div>';

 } //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>