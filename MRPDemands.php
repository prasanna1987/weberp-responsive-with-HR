<?php
include('includes/session.php');
$Title = _('MRP Demands');
include('includes/header.php');

if (isset($_POST['DemandID'])){
	$DemandID =$_POST['DemandID'];
} elseif (isset($_GET['DemandID'])){
	$DemandID =$_GET['DemandID'];
}

if (isset($_POST['StockID'])){
	$StockID =trim(mb_strtoupper($_POST['StockID']));
} elseif (isset($_GET['StockID'])){
	$StockID =trim(mb_strtoupper($_GET['StockID']));
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['Search'])) {
	search($StockID);
} elseif (isset($_POST['submit'])) {
	submit($StockID,$DemandID);
} elseif (isset($_GET['delete'])) {
	delete($DemandID,'',$StockID);
} elseif (isset($_POST['deletesome'])) {
	delete('',$_POST['MRPDemandtype'],$StockID);
} elseif (isset($_GET['listall'])) {
	listall('','');
} elseif (isset($_POST['listsome'])) {
	listall($StockID,$_POST['MRPDemandtype']);
} else {
	display($StockID,$DemandID);
}

function search(&$StockID) { //####SEARCH_SEARCH_SEARCH_SEARCH_SEARCH_SEARCH_SEARCH_#####

// Search by partial part number or description. Display the part number and description from
// the stockmaster so user can select one. If the user clicks on a part number
// MRPDemands.php is called again, and it goes to the display() routine.

	// Work around to auto select
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		$_POST['StockCode']='%';
	}
	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		$msg=_('Stock description keywords have been used in preference to the Stock code extract entered');
	}
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		$msg=_('At least one stock description keyword or an extract of a stock code must be entered for the search');
	} else {
		if (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$sql = "SELECT stockmaster.stockid,
						stockmaster.description
					FROM stockmaster
					WHERE  stockmaster.description " . LIKE . " '" . $SearchString ."'
					ORDER BY stockmaster.stockid";

		} elseif (mb_strlen($_POST['StockCode'])>0){
			$sql = "SELECT stockmaster.stockid,
						stockmaster.description
					FROM stockmaster
					WHERE  stockmaster.stockid " . LIKE  . "'%" . $_POST['StockCode'] . "%'
					ORDER BY stockmaster.stockid";

		}

		$ErrMsg = _('The SQL to find the parts selected failed with the message');
		$result = DB_query($sql,$ErrMsg);

	} //one of keywords or StockCode was more than a zero length string

	// If the SELECT found records, display them
	if (DB_num_rows($result) > 0) {
		echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
		echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		$TableHeader = '<thead> 
		<tr><th>' . _('Code') . '</th>
							<th>' . _('Description') . '</th>
						</tr></thead> ';
		echo $TableHeader;

		$j = 1;

		while ($myrow=DB_fetch_array($result)) {
			$tabindex=$j+4;
			echo '<tr class="striped_row">
				<td><input tabindex="' . $tabindex . '" type="submit" name="StockID" value="' . $myrow['stockid'] .'" class="btn btn-info" /></td>
				<td>' . $myrow['description'] . '</td>
				</tr>';
			$j++;
	}  //end of while loop

	echo '</table>';
    echo '</div></div></div>';
	echo '</form>';

} else {
	echo prnMsg(_('No record found in search'),'error');
	unset ($StockID);
	display($StockID,$DemandID);
}


} // End of function search()


function submit(&$StockID,&$DemandID)  //####SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT####
{
// In this section if hit submit button. Do edit checks. If all checks pass, see if record already
// exists for StockID/Duedate/MRPDemandtype combo; that means do an Update, otherwise, do INSERT.
//initialise no input errors assumed initially before we test
	// echo "<br/>Submit - DemandID = $DemandID<br/>";
	$FormatedDuedate = FormatDateForSQL($_POST['Duedate']);
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if (!is_numeric(filter_number_format($_POST['Quantity']))) {
		$InputError = 1;
		echo prnMsg(_('Quantity must be numeric'),'error');
	}
	if (filter_number_format($_POST['Quantity']) <= 0) {
		$InputError = 1;
		echo prnMsg(_('Quantity must be greater than 0'),'error');
	}
	if (!Is_Date($_POST['Duedate'])) {
		$InputError = 1;
		echo prnMsg(_('Invalid due date'),'error');
	}
	$sql = "SELECT * FROM mrpdemandtypes
			WHERE mrpdemandtype='" . $_POST['MRPDemandtype'] . "'";
	$result = DB_query($sql);

	if (DB_num_rows($result) == 0){
		$InputError = 1;
		echo prnMsg(_('Invalid demand type'),'error');
	}
// Check if valid part number - Had done a Select Count(*), but that returned a 1 in DB_num_rows
// even if there was no record.
	$sql = "SELECT * FROM stockmaster
			WHERE stockid='" . $StockID . "'";
	$result = DB_query($sql);

	if (DB_num_rows($result) == 0){
			$InputError = 1;
			echo prnMsg($StockID . ' ' . _('is not a valid item code'),'error');
			unset ($_POST['StockID']);
			unset($StockID);
	}
// Check if part number/demand type/due date combination already exists
	$sql = "SELECT * FROM mrpdemands
			WHERE stockid='" . $StockID . "'
			AND mrpdemandtype='" . $_POST['MRPDemandtype'] . "'
			AND duedate='" . $FormatedDuedate . "'
			AND demandid <> '" . $DemandID . "'";
	$result = DB_query($sql);

	if (DB_num_rows($result) > 0){
		$InputError = 1;
		echo prnMsg(_('Record already exists for Stock ID/demand type/date'),'error');
	}

	if ($InputError !=1){
		$sql = "SELECT COUNT(*) FROM mrpdemands
				   WHERE demandid='" . $DemandID . "'
				   GROUP BY demandid";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);

		if ($myrow[0]>0) {
			//If $myrow[0] > 0, it means this is an edit, so do an update
			$sql = "UPDATE mrpdemands SET quantity = '" . filter_number_format($_POST['Quantity']) . "',
							mrpdemandtype = '" . trim(mb_strtoupper($_POST['MRPDemandtype'])) . "',
							duedate = '" . $FormatedDuedate . "'
					WHERE demandid = '" . $DemandID . "'";
			$msg = _("The MRP demand record has been updated for").' '.$StockID;
		} else {

	// If $myrow[0] from SELECT count(*) is zero, this is an entry of a new record
			$sql = "INSERT INTO mrpdemands (stockid,
							mrpdemandtype,
							quantity,
							duedate)
						VALUES ('" . $StockID . "',
							'" . trim(mb_strtoupper($_POST['MRPDemandtype'])) . "',
							'" . filter_number_format($_POST['Quantity']) . "',
							'" . $FormatedDuedate . "'
						)";
			$msg = _('A new MRP demand record has been added to the database for') . ' ' . $StockID;
		}


		$result = DB_query($sql,_('The update/addition of the MRP demand record failed because'));
		echo prnMsg($msg,'success');
		echo '<br />';
		unset ($_POST['MRPDemandtype']);
		unset ($_POST['Quantity']);
		unset ($_POST['StockID']);
		unset ($_POST['Duedate']);
		unset ($StockID);
		unset ($DemandID);
	} // End of else where DB_num_rows showed there was a valid stockmaster record

	display($StockID,$DemandID);
} // End of function submit()


function delete($DemandID,$DemandType,$StockID) { //####DELETE_DELETE_DELETE_DELETE_DELETE_DELETE_####

// If wanted to have a Confirm routine before did actually deletion, could check if
// deletion = "yes"; if it did, display link that redirects back to this page
// like this - <a href=" ' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?&delete=confirm&StockID=' . "$StockID" . ' ">
// that sets delete=confirm. If delete=confirm, do actually deletion.
//  This deletes an individual record by DemandID if called from a listall that shows
// edit/delete or deletes all of a particular demand type if press Delete Demand Type button.
	$where = " ";
	if ($DemandType) {
		$where = " WHERE mrpdemandtype ='"  .  $DemandType . "'";
	}
	if ($DemandID) {
		$where = " WHERE demandid ='"  .  $DemandID . "'";
	}
	$sql="DELETE FROM mrpdemands
		   $where";
	$result = DB_query($sql);
	if ($DemandID) {
		echo prnMsg(_('The MRP demand record for') .' '. $StockID .' '. _('has been deleted'),'succes');
	} else {
		echo prnMsg(_('All records for demand type') .' '. $DemandType .' ' . _('have been deleted'),'succes');
	}
	unset ($DemandID);
	unset ($StockID);
	display($stockID,$DemandID);

} // End of function delete()


function listall($part,$DemandType)  {//####LISTALL_LISTALL_LISTALL_LISTALL_LISTALL_LISTALL_LISTALL_####

// List all mrpdemands records, with anchors to Edit or Delete records if hit List All anchor
// Lists some in hit List Selection submit button, and uses part number if it is entered or
// demandtype

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8')  .'" method="post">';
  
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	$where = " ";
	if ($DemandType) {
		$where = " WHERE mrpdemandtype ='"  .  $DemandType . "'";
	}
	if ($part) {
		$where = " WHERE mrpdemands.stockid ='"  .  $part . "'";
	}
	// If part is entered, it overrides demandtype
	$sql = "SELECT mrpdemands.demandid,
				   mrpdemands.stockid,
				   mrpdemands.mrpdemandtype,
				   mrpdemands.quantity,
				   mrpdemands.duedate,
				   stockmaster.description,
				   stockmaster.decimalplaces
			FROM mrpdemands
			LEFT JOIN stockmaster on mrpdemands.stockid = stockmaster.stockid" .
			 $where	. " ORDER BY mrpdemands.stockid, mrpdemands.duedate";

	$ErrMsg = _('The SQL to find the parts selected failed with the message');
	$result = DB_query($sql,$ErrMsg);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead> 
		<tr>
			<th>' . _('Stock ID') . '</th>
			<th>' . _('Description') . '</th>
			<th>' . _('Demand Type') . '</th>
			<th>' . _('Quantity') . '</th>
			<th>' . _('Due Date') . '</th>
			<th colspan="2">' . _('Actions') . '</th>
			</tr></thead> ';
	$ctr = 0;
	while ($myrow = DB_fetch_array($result)) {
		$displaydate = ConvertSQLDate($myrow[4]);
		$ctr++;
		echo '<tr><td>' . $myrow['stockid'] . '</td>
				<td>' . $myrow['description'] . '</td>
				<td>' . $myrow['mrpdemandtype'] . '</td>
				<td>' . locale_number_format($myrow['quantity'],$myrow['decimalplaces']) . '</td>
				<td>' . $displaydate . '</td>
				<td><a href="' .htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .'?DemandID=' . $myrow['demandid'] . '&amp;StockID=' . $myrow['stockid'] . '" class="btn btn-info">' . _('Edit') . '</a></td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?DemandID=' . $myrow['demandid'] . '&amp;StockID=' . $myrow['stockid'].'&amp;delete=yes" onclick="return confirm(\'' . _('Are you sure you wish to delete this demand?') . '\');" class="btn btn-danger">' . _('Delete')  . '</a></td>
				</tr>';
	}

	//END WHILE LIST LOOP
	echo '<tr><td>' . _('<strong>Number of Records</strong>') . '</td>
				<td>' . $ctr . '</td></tr>';
	echo '</table>';
    echo '</div></div></div>';
	echo '</form>';
	unset ($StockID);
	display($StockID,$DemandID);

} // End of function listall()


function display(&$StockID,&$DemandID) { //####DISPLAY_DISPLAY_DISPLAY_DISPLAY_DISPLAY_DISPLAY_#####

// Display Seach fields at top and Entry form below that. This function is called the first time
// the page is called, and is also invoked at the end of all of the other functions.
// echo "<br/>DISPLAY - DemandID = $DemandID<br/>";
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	if (!isset($StockID)) {
		echo'<div class="row">
<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Description') . ' ' . _('-part or full') . '</label>
			<input tabindex="1" type="text" class="form-control" name="Keywords" size="20" maxlength="25" /></div></div>
			<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock ID') . ' ' . _('-part or full') . '</label>
			<input tabindex="2" class="form-control" type="text" name="StockCode" size="15" maxlength="20" /></div></div>
					
<div class="col-xs-3"><div class="form-group"> <br /><input tabindex="3" class="btn btn-success" type="submit" name="Search" value="' . _('Search') .
            '" /></div></div>
			<div class="col-xs-3">
<div class="form-group"> <br /><a href="'. htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?listall=yes" class="btn btn-info">' . _('List All Demands')  . '</a></div></div></div>';
	} else {
		if (isset($DemandID)) {
		//editing an existing MRP demand

			$sql = "SELECT demandid,
					stockid,
					mrpdemandtype,
					quantity,
					duedate
				FROM mrpdemands
				WHERE demandid='" . $DemandID . "'";
			$result = DB_query($sql);
			$myrow = DB_fetch_array($result);

			if (DB_num_rows($result) > 0){
				$_POST['DemandID'] = $myrow['demandid'];
				$_POST['StockID'] = $myrow['stockid'];
				$_POST['MRPDemandtype'] = $myrow['mrpdemandtype'];
				$_POST['Quantity'] = locale_number_format($myrow['quantity'],'Variable');
				$_POST['Duedate']  = ConvertSQLDate($myrow['duedate']);
			}

			echo '<input type="hidden" name="DemandID" value="' . $_POST['DemandID'] . '" />';
			echo '<input type="hidden" name="StockID" value="' . $_POST['StockID'] . '" />';
			echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' ._('Stock ID') . '</label>
						' . $_POST['StockID'] . '</div>
					</div>';

		} else {
			if (!isset($_POST['StockID'])) {
				$_POST['StockID'] = '';
			}
			echo '<div class="row"><div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock ID') . '</label>
						<input type="text" name="StockID" class="form-control" size="21" maxlength="20" value="' . $_POST['StockID'] . '" /></div>
					</div>';
		}


		if (!isset($_POST['Quantity'])) {
			$_POST['Quantity']=0;
		}

		if (!isset($_POST['Duedate'])) {
			$_POST['Duedate']=' ';
		}

		echo '<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Quantity') . '</label>
				<input type="text" name="Quantity" class="form-control" size="6" maxlength="6" value="' . $_POST['Quantity'] . '" /></div>
			</div>
			<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Due Date') . '</label>
				<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="Duedate" size="11" maxlength="10" value="' . $_POST['Duedate'] . '" /></div>
			</div>';
		// Generate selections for Demand Type
		echo '
				<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Demand Type') . '</label>
				<select name="MRPDemandtype" class="form-control">';

		$sql = "SELECT mrpdemandtype,
						description
				FROM mrpdemandtypes";
		$result = DB_query($sql);
		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['MRPDemandtype']) and $myrow['mrpdemandtype']==$_POST['MRPDemandtype']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['mrpdemandtype'] . '">' . $myrow['mrpdemandtype'] . ' - ' .$myrow['description'] . '</option>';
		} //end while loop
		echo '</select></div>
			</div></div>
			<div class="row">
			<div class="col-xs-3">
				<input type="submit" class="btn btn-success" name="submit" value="' . _('Submit') . '" /></div>
				<div class="col-xs-4">
				<input type="submit" name="listsome" class="btn btn-info" value="' . _('List All Demands for Selected Stock ID') . '" /></div>
				<div class="col-xs-4">
				<input type="submit" name="deletesome" class="btn btn-danger" value="' . _('Delete All Demands for selected Demand Type') . '" /></div>';
				
		// If mrpdemand record exists, display option to delete it
		if ((isset($DemandID)) AND (DB_num_rows($result) > 0)) {
			echo '<div></div>';
		}
        echo '</div><br />
';
	}
	echo '
		</form>';

} // End of function display()

include('includes/footer.php');
?>