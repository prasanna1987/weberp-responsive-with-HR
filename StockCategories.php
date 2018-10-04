<?php

include('includes/session.php');

$Title = _('Inventory Categories Maintenance');
$ViewTopic= 'Inventory';
$BookMark = 'InventoryCategories';
include('includes/header.php');

// BEGIN: Stock Type Name array.
$StockTypeName = array();
$StockTypeName['D'] = _('Dummy Item - (No Movements)');
$StockTypeName['F'] = _('Finished Goods');
$StockTypeName['L'] = _('Labour');
$StockTypeName['M'] = _('Raw Materials');
asort($StockTypeName);
// END: Stock Type Name array.

// BEGIN: Tax Category Name array.
$TaxCategoryName = array();
$Query = "SELECT taxcatid, taxcatname FROM taxcategories ORDER BY taxcatname";
$Result = DB_query($Query);
while ($Row = DB_fetch_array($Result)) {
	$TaxCategoryName[$Row['taxcatid']] = $Row['taxcatname'];
}
// END: Tax Category Name array.

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_GET['SelectedCategory'])){
	$SelectedCategory = mb_strtoupper($_GET['SelectedCategory']);
} else if (isset($_POST['SelectedCategory'])){
	$SelectedCategory = mb_strtoupper($_POST['SelectedCategory']);
}

if (isset($_GET['DeleteProperty'])){

	$ErrMsg = _('Could not delete the property') . ' ' . $_GET['DeleteProperty'] . ' ' . _('because');
	$sql = "DELETE FROM stockitemproperties WHERE stkcatpropid='" . $_GET['DeleteProperty'] . "'";
	$result = DB_query($sql,$ErrMsg);
	$sql = "DELETE FROM stockcatproperties WHERE stkcatpropid='" . $_GET['DeleteProperty'] . "'";
	$result = DB_query($sql,$ErrMsg);
	echo prnMsg(_('Deleted the property') . ' ' . $_GET['DeleteProperty'],'success');
}

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	$_POST['CategoryID'] = mb_strtoupper($_POST['CategoryID']);

	if (mb_strlen($_POST['CategoryID']) > 6) {
		$InputError = 1;
		echo prnMsg(_('The Inventory Category code must be six characters or less'),'error');
	} elseif (mb_strlen($_POST['CategoryID'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Inventory category code must be at least 1 character but less than six characters'),'error');
	} elseif (mb_strlen($_POST['CategoryDescription']) >20 or mb_strlen($_POST['CategoryDescription'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Sales category description must be twenty characters or less long and cannot be zero'),'error');
	} elseif ($_POST['StockType'] !='D' AND $_POST['StockType'] !='L' AND $_POST['StockType'] !='F' AND $_POST['StockType'] !='M') {
		$InputError = 1;
		echo prnMsg(_('The stock type selected must be one of') . ' "D" - ' . _('Dummy item') . ', "L" - ' . _('Labour stock item') . ', "F" - ' . _('Finished product') . ' ' . _('or') . ' "M" - ' . _('Raw Materials'),'error');
	}
	for ($i=0;$i<=$_POST['PropertyCounter'];$i++){
		if (isset($_POST['PropNumeric' .$i]) and $_POST['PropNumeric' .$i] == true){
			if (!is_numeric(filter_number_format($_POST['PropMinimum' .$i]))){
				$InputError = 1;
				echo prnMsg(_('The minimum value is expected to be a numeric value'),'error');
			}
			if (!is_numeric(filter_number_format($_POST['PropMaximum' .$i]))){
				$InputError = 1;
				echo prnMsg(_('The maximum value is expected to be a numeric value'),'error');
			}
		}
	} //check the properties are sensible

	if (isset($SelectedCategory) AND $InputError !=1) {

		/*SelectedCategory could also exist if submit had not been clicked this code
		would not run in this case cos submit is false of course  see the
		delete code below*/

		$sql = "UPDATE stockcategory SET stocktype = '" . $_POST['StockType'] . "',
									 categorydescription = '" . $_POST['CategoryDescription'] . "',
									 defaulttaxcatid = '" . $_POST['DefaultTaxCatID'] . "',
									 stockact = '" . $_POST['StockAct'] . "',
									 adjglact = '" . $_POST['AdjGLAct'] . "',
									 issueglact = '" . $_POST['IssueGLAct'] . "',
									 purchpricevaract = '" . $_POST['PurchPriceVarAct'] . "',
									 materialuseagevarac = '" . $_POST['MaterialUseageVarAc'] . "',
									 wipact = '" . $_POST['WIPAct'] . "'
									 WHERE
									 categoryid = '" . $SelectedCategory. "'";
		$ErrMsg = _('Could not update the stock category') . $_POST['CategoryDescription'] . _('because');
		$result = DB_query($sql,$ErrMsg);

		if ($_POST['PropertyCounter']==0 and $_POST['PropLabel0']!='') {
			$_POST['PropertyCounter']=0;
		}

		for ($i=0;$i<=$_POST['PropertyCounter'];$i++){

			if (isset($_POST['PropReqSO' .$i]) and $_POST['PropReqSO' .$i] == true){
					$_POST['PropReqSO' .$i] =1;
			} else {
					$_POST['PropReqSO' .$i] =0;
			}
			if (isset($_POST['PropNumeric' .$i]) and $_POST['PropNumeric' .$i] == true){
					$_POST['PropNumeric' .$i] =1;
			} else {
					$_POST['PropNumeric' .$i] =0;
			}
			if (!isset($_POST['PropMinimum' . $i]) or $_POST['PropMinimum' . $i] === ''){
				$_POST['PropMinimum' . $i] = '-999999999';
			}
			if (!isset($_POST['PropMaximum' . $i]) or $_POST['PropMaximum' . $i] === ''){
				$_POST['PropMaximum' . $i] = '999999999';
			}

			if ($_POST['PropID' .$i] =='NewProperty' AND mb_strlen($_POST['PropLabel'.$i])>0){
				$sql = "INSERT INTO stockcatproperties (categoryid,
														label,
														controltype,
														defaultvalue,
														minimumvalue,
														maximumvalue,
														numericvalue,
														reqatsalesorder)
											VALUES ('" . $SelectedCategory . "',
													'" . $_POST['PropLabel' . $i] . "',
													" . $_POST['PropControlType' . $i] . ",
													'" . $_POST['PropDefault' .$i] . "',
													'" . filter_number_format($_POST['PropMinimum' .$i]) . "',
													'" . filter_number_format($_POST['PropMaximum' .$i]) . "',
													'" . $_POST['PropNumeric' .$i] . "',
													" . $_POST['PropReqSO' .$i] . ')';
				$ErrMsg = _('Could not insert a new category property for') . $_POST['PropLabel' . $i];
				$result = DB_query($sql,$ErrMsg);
			} elseif ($_POST['PropID' .$i] !='NewProperty') { //we could be amending existing properties
				$sql = "UPDATE stockcatproperties SET label ='" . $_POST['PropLabel' . $i] . "',
													  controltype = " . $_POST['PropControlType' . $i] . ",
													  defaultvalue = '"	. $_POST['PropDefault' .$i] . "',
													  minimumvalue = '" . filter_number_format($_POST['PropMinimum' .$i]) . "',
													  maximumvalue = '" . filter_number_format($_POST['PropMaximum' .$i]) . "',
													  numericvalue = '" . $_POST['PropNumeric' .$i] . "',
													  reqatsalesorder = " . $_POST['PropReqSO' .$i] . "
												WHERE stkcatpropid =" . $_POST['PropID' .$i];
				$ErrMsg = _('Updated the stock category property for') . ' ' . $_POST['PropLabel' . $i];
				$result = DB_query($sql,$ErrMsg);
			}

		} //end of loop round properties

		echo prnMsg(_('Updated the stock category record for') . ' ' . $_POST['CategoryDescription'],'success');

	} elseif ($InputError !=1) {

	/*Selected category is null cos no item selected on first time round so must be adding a	record must be submitting new entries in the new stock category form */

		$sql = "INSERT INTO stockcategory (categoryid,
											stocktype,
											categorydescription,
											defaulttaxcatid,
											stockact,
											adjglact,
											issueglact,
											purchpricevaract,
											materialuseagevarac,
											wipact)
										VALUES ('" .
											$_POST['CategoryID'] . "','" .
											$_POST['StockType'] . "','" .
											$_POST['CategoryDescription'] . "','" .
											$_POST['DefaultTaxCatID'] . "','" .
											$_POST['StockAct'] . "','" .
											$_POST['AdjGLAct'] . "','" .
											$_POST['IssueGLAct'] . "','" .
											$_POST['PurchPriceVarAct'] . "','" .
											$_POST['MaterialUseageVarAc'] . "','" .
											$_POST['WIPAct'] . "')";
		$ErrMsg = _('Could not insert the new stock category') . $_POST['CategoryDescription'] . _('because');
		$result = DB_query($sql,$ErrMsg);
		echo prnMsg(_('A new stock category record has been added for') . ' ' . $_POST['CategoryDescription'],'success');

	}
	//run the SQL from either of the above possibilites

	unset($_POST['StockType']);
	unset($_POST['CategoryDescription']);
	unset($_POST['StockAct']);
	unset($_POST['AdjGLAct']);
	unset($_POST['IssueGLAct']);
	unset($_POST['PurchPriceVarAct']);
	unset($_POST['MaterialUseageVarAc']);
	unset($_POST['WIPAct']);


} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'StockMaster'

	$sql= "SELECT stockid FROM stockmaster WHERE stockmaster.categoryid='" . $SelectedCategory . "'";
	$result = DB_query($sql);

	if (DB_num_rows($result)>0) {
		echo prnMsg(_('Cannot delete this stock category because stock items have been created using this stock category') .
			'<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('items referring to this stock category code'),'warn');

	} else {
		$sql = "SELECT stkcat FROM salesglpostings WHERE stkcat='" . $SelectedCategory . "'";
		$result = DB_query($sql);

		if (DB_num_rows($result)>0) {
			echo prnMsg(_('Cannot delete this stock category because it is used by the sales') . ' - ' . _('GL posting interface') . '. ' . _('Delete any records in the Sales GL Interface set up using this stock category first'),'warn');
		} else {
			$sql = "SELECT stkcat FROM cogsglpostings WHERE stkcat='" . $SelectedCategory . "'";
			$result = DB_query($sql);

			if (DB_num_rows($result)>0) {
				echo prnMsg(_('Cannot delete this stock category because it is used by the cost of sales') . ' - ' . _('GL posting interface') . '. ' . _('Delete any records in the Cost of Sales GL Interface set up using this stock category first'),'warn');
			} else {
				$sql="DELETE FROM stockcategory WHERE categoryid='" . $SelectedCategory . "'";
				$result = DB_query($sql);
				echo prnMsg(_('The stock category') . ' ' . $SelectedCategory . ' ' . _('has been deleted') . ' !','success');
				unset ($SelectedCategory);
			}
		}
	} //end if stock category used in debtor transactions
}

if (!isset($SelectedCategory)) {

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedCategory will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of stock categorys will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT	categoryid,
					categorydescription,
					stocktype,
					defaulttaxcatid,
					stockact,
					adjglact,
					issueglact,
					purchpricevaract,
					materialuseagevarac,
					wipact
				FROM stockcategory";
	$result = DB_query($sql);

	echo '<br />
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
				<th class="ascending">' . _('Code') . '</th>
				<th class="ascending">' . _('Category Description') . '</th>' . '
				<th class="ascending">' . _('Stock Type') . '</th>' . '
				<th class="ascending">' . _('Tax Category') . '</th>' . '
				<th class="ascending">' . _('Stock GL') . '</th>' . '
				<th class="ascending">' . _('Adjts GL') . '</th>
				<th class="ascending">' . _('Issues GL') . '</th>
				<th class="ascending">' . _('Price Var GL') . '</th>
				<th class="ascending">' . _('Usage Var GL') . '</th>
				<th class="ascending">' . _('WIP GL') . '</th>
				<th colspan="2">' . _('Actions') . '</th>
			</tr>
		</thead>
		<tbody>';

	while ($myrow = DB_fetch_array($result)) {
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td><a href="%sSelectedCategory=%s" class="btn btn-info">' . _('Edit') . '</a></td>
				<td><a href="%sSelectedCategory=%s&amp;delete=yes" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this stock category? Additional checks will be performed before actual deletion to ensure data integrity is not compromised.') . '\');">' . _('Delete') . '</a></td>
			</tr>',
				$myrow['categoryid'],
				$myrow['categorydescription'],
				$StockTypeName[$myrow['stocktype']],
				$TaxCategoryName[$myrow['defaulttaxcatid']],
				$myrow['stockact'],
				$myrow['adjglact'],
				$myrow['issueglact'],
				$myrow['purchpricevaract'],
				$myrow['materialuseagevarac'],
				$myrow['wipact'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['categoryid'],
				htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?',
				$myrow['categoryid']);
	}
	//END WHILE LIST LOOP
	echo '</tbody></table></div></div></div>';
}

//end of ifs and buts!



if (isset($SelectedCategory)) {
	echo '
<div class="row"> <div class="col-xs-4"><label class="col-md-12 control-label"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-default">' . _('Back Stock Categories') . '</a></div></div>
';
}

echo '<div class="sub-header"></div><br />
<form id="CategoryForm" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($SelectedCategory)) {
	//editing an existing stock category
	if (!isset($_POST['UpdateTypes'])) {
		$sql = "SELECT categoryid,
						stocktype,
						categorydescription,
						stockact,
						adjglact,
						issueglact,
						purchpricevaract,
						materialuseagevarac,
						wipact,
						defaulttaxcatid
					FROM stockcategory
					WHERE categoryid='" . $SelectedCategory . "'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['CategoryID'] = $myrow['categoryid'];
		$_POST['StockType']  = $myrow['stocktype'];
		$_POST['CategoryDescription']  = $myrow['categorydescription'];
		$_POST['StockAct']  = $myrow['stockact'];
		$_POST['AdjGLAct']  = $myrow['adjglact'];
		$_POST['IssueGLAct']  = $myrow['issueglact'];
		$_POST['PurchPriceVarAct']  = $myrow['purchpricevaract'];
		$_POST['MaterialUseageVarAc']  = $myrow['materialuseagevarac'];
		$_POST['WIPAct']  = $myrow['wipact'];
		$_POST['DefaultTaxCatID']  = $myrow['defaulttaxcatid'];
	}
	echo '<input type="hidden" name="SelectedCategory" value="' . $SelectedCategory . '" />';
	echo '<input type="hidden" name="CategoryID" value="' . $_POST['CategoryID'] . '" />';
	echo '<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Category Code') . '</label>
				' . $_POST['CategoryID'] . '</div>
			</div>';

} else { //end of if $SelectedCategory only do the else when a new record is being entered
	if (!isset($_POST['CategoryID'])) {
		$_POST['CategoryID'] = '';
	}
	echo '<div class="row">
			<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Category Code') . '</label>
				<td><input type="text" class="form-control" name="CategoryID" required="required" autofocus="autofocus" data-type="no-illegal-chars" title="' . _('Enter up to six alphanumeric characters or underscore as a code for this stock category') . '" size="7" maxlength="6" value="' . $_POST['CategoryID'] . '" /></div></div>';
}

//SQL to poulate account selection boxes
$sql = "SELECT accountcode,
				accountname
			FROM chartmaster
			LEFT JOIN accountgroups
				ON chartmaster.group_=accountgroups.groupname
			WHERE accountgroups.pandl=0
			ORDER BY accountcode";

$BSAccountsResult = DB_query($sql);

$sql = "SELECT accountcode,
				accountname
			FROM chartmaster
			LEFT JOIN accountgroups
				ON chartmaster.group_=accountgroups.groupname
			WHERE accountgroups.pandl=1
			ORDER BY accountcode";

$PnLAccountsResult = DB_query($sql);

// Category Description input.
if (!isset($_POST['CategoryDescription'])) {
	$_POST['CategoryDescription'] = '';
}
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Category Description') .
	'</label>
	<input id="CategoryDescription" maxlength="20" class="form-control" name="CategoryDescription" required="required" size="22" title="' .
	_('A description of the inventory category is required') .
	'" type="text" value="' . $_POST['CategoryDescription'] .
	'" /></div></div>';

// Stock Type input.
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Stock Type') .
	'</label>
	<select id="StockType" name="StockType" onChange="ReloadForm(CategoryForm.UpdateTypes)"  class="form-control">';
foreach ($StockTypeName as $StockTypeId => $Row) {
	echo '<option';
	if (isset($_POST['StockType']) and $_POST['StockType']==$StockTypeId) {
		echo ' selected="selected"';
	}
	echo ' value="' . $StockTypeId . '">' . $Row . '</option>';
}
echo '</select></div></div></div>';

// Default Tax Category input.
if (!isset($_POST['DefaultTaxCatID'])) {
	$_POST['DefaultTaxCatID'] = $_SESSION['DefaultTaxCategory'];
}
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Default Tax Category') .
	'</label>
	<select id="DefaultTaxCatID"  name="DefaultTaxCatID" class="form-control">';
foreach ($TaxCategoryName as $TaxCategoryId => $Row) {
	echo '<option';
	if ($_POST['DefaultTaxCatID'] == $TaxCategoryId) {
		echo ' selected="selected"';
	}
	echo ' value="' . $TaxCategoryId . '">' . $Row . '</option>';
}
echo '</select></div></div>';

// Recovery or Stock GL Code input.
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><input type="submit" name="UpdateTypes" style="visibility:hidden;width:1px" value="Not Seen" />';
if (isset($_POST['StockType']) and $_POST['StockType']=='L') {
	$Result = $PnLAccountsResult;
	echo _('Recovery GL Code');
} else {
	$Result = $BSAccountsResult;
	echo _('Stock GL Code');
}
echo '</label>
<select name="StockAct" class="form-control">';

while ($myrow = DB_fetch_array($Result)){

	if (isset($_POST['StockAct']) and $myrow['accountcode']==$_POST['StockAct']) {
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	}
} //end while loop
DB_data_seek($PnLAccountsResult,0);
DB_data_seek($BSAccountsResult,0);
echo '</select></div></div>';

// WIP GL Code input.
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('WIP GL Code') . '</label>
<select name="WIPAct" class="form-control">';
while ($myrow = DB_fetch_array($BSAccountsResult)) {
	echo '<option';
	if (isset($_POST['WIPAct']) and $myrow['accountcode']==$_POST['WIPAct']) {
		echo ' selected="selected"';
	}
	echo ' value="' . $myrow['accountcode'] . '">' .
		htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) .
		' ('.$myrow['accountcode'].')' . '</option>';
}
echo '</select></div></div></div>';
DB_data_seek($BSAccountsResult,0);

// Stock Adjustments GL Code input.
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Stock Adjustments GL Code') . '</label>
		<select name="AdjGLAct" class="form-control">';

while ($myrow = DB_fetch_array($PnLAccountsResult)) {
	if (isset($_POST['AdjGLAct']) and $myrow['accountcode']==$_POST['AdjGLAct']) {
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	}

} //end while loop
DB_data_seek($PnLAccountsResult,0);
echo '</select></div></div>';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Internal Stock Issues GL Code') . '</label>
		<select name="IssueGLAct" class="form-control">';

while ($myrow = DB_fetch_array($PnLAccountsResult)) {
	if (isset($_POST['IssueGLAct']) and $myrow['accountcode']==$_POST['IssueGLAct']) {
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	}

} //end while loop
DB_data_seek($PnLAccountsResult,0);
echo '</select></div></div>';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Price Variance GL Code') . '</label>
		<select name="PurchPriceVarAct" class="form-control">';

while ($myrow = DB_fetch_array($PnLAccountsResult)) {
	if (isset($_POST['PurchPriceVarAct']) and $myrow['accountcode']==$_POST['PurchPriceVarAct']) {
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	}

} //end while loop
DB_data_seek($PnLAccountsResult,0);

echo '</select></div></div></div>

<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">';
if (isset($_POST['StockType']) and $_POST['StockType']=='L') {
	echo  _('Labour Efficiency Variance GL Code');
} else {
	echo  _('Usage Variance GL Code');
}
echo '</label>
		<select name="MaterialUseageVarAc" class="form-control">';

while ($myrow = DB_fetch_array($PnLAccountsResult)) {
	if (isset($_POST['MaterialUseageVarAc']) and $myrow['accountcode']==$_POST['MaterialUseageVarAc']) {
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . ' ('.$myrow['accountcode'].')' . '</option>';
	}

} //end while loop
DB_free_result($PnLAccountsResult);
echo '</select></div></div>
		</div>';

if (!isset($SelectedCategory)) {
	$SelectedCategory='';
}
if (isset($SelectedCategory)) {
	//editing an existing stock category

	$sql = "SELECT stkcatpropid,
					label,
					controltype,
					defaultvalue,
					numericvalue,
					reqatsalesorder,
					minimumvalue,
					maximumvalue
			   FROM stockcatproperties
			   WHERE categoryid='" . $SelectedCategory . "'
			   ORDER BY stkcatpropid";

	$result = DB_query($sql);

/*		echo '<br />Number of rows returned by the sql = ' . DB_num_rows($result) .
			'<br />The SQL was:<br />' . $sql;
*/
	echo '<br />
			
				';
	$PropertyCounter =0;
	while ($myrow = DB_fetch_array($result)) {
		echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Property Label</label>
<input type="hidden" name="PropID' . $PropertyCounter .'" value="' . $myrow['stkcatpropid'] . '" />';
		echo '<input type="text" class="form-control" name="PropLabel' . $PropertyCounter . '" size="50" maxlength="100" value="' . $myrow['label'] . '" /></div>
		</div>
		
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Control Type</label>
<select name="PropControlType' . $PropertyCounter . '" class="form-control">';
		if ($myrow['controltype']==0){
			echo '<option selected="selected" value="0">' . _('Text Box') . '</option>';
		} else {
			echo '<option value="0">' . _('Text Box') . '</option>';
		}
		if ($myrow['controltype']==1){
			echo '<option selected="selected" value="1">' . _('Select Box') . '</option>';
		} else {
			echo '<option value="1">' . _('Select Box') . '</option>';
		}
		if ($myrow['controltype']==2){
			echo '<option selected="selected" value="2">' . _('Check Box') . '</option>';
		} else {
			echo '<option value="2">' . _('Check Box') . '</option>';
		}
		if ($myrow['controltype']==3){
			echo '<option selected="selected" value="3">' . _('Date Box') . '</option>';
		} else {
			echo '<option value="3">' . _('Date Box') . '</option>';
		}
		echo '</select></div></div>
					<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Default Value</label>
<input type="text" class="form-control" name="PropDefault' . $PropertyCounter . '" value="' . $myrow['defaultvalue'] . '" /></div></div></div>';
echo '<div class="row">';

		if ($myrow['numericvalue']==1){
			echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Numeric Value</label><input type="checkbox" name="PropNumeric' . $PropertyCounter . '" checked="checked" /></div></div>';
		} else {
			echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Numeric Value</label>
<input type="checkbox" name="PropNumeric' . $PropertyCounter . '" /></div></div>';
		}

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Minimum Value</label>
<input type="text" name="PropMinimum' . $PropertyCounter . '" class="form-control" value="' . $myrow['minimumvalue'] . '" /></div></div>

				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Maximum Value</label><input type="text" class="form-control" name="PropMaximum' . $PropertyCounter . '" value="' . $myrow['maximumvalue'] . '" /></div></div></div>
<div class="row">';

		if ($myrow['reqatsalesorder']==1){
			echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Require in SO</label>
<input type="checkbox" name="PropReqSO' . $PropertyCounter .'" checked="True" /></div></div>';
		} else {
			echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Require in SO</label>
<input type="checkbox" name="PropReqSO' . $PropertyCounter .'" /></div></div>';
		}

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?DeleteProperty=' . $myrow['stkcatpropid'] .'&amp;SelectedCategory=' . $SelectedCategory . '" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this property? All properties of this type set up for stock items will also be deleted.') . '\');">' . _('Delete') . '</a></div></div></div>';

		$PropertyCounter++;
	} //end loop around defined properties for this category
	echo '<div class="row">
	<div class="col-xs-4"><div class="form-group">
 <label class="col-md-12 control-label">Property Label</label>
 <input type="hidden" name="PropID' . $PropertyCounter .'" value="NewProperty" />';
	echo '<input type="text" class="form-control" name="PropLabel' . $PropertyCounter . '" size="50" maxlength="100" /></div></div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Control Type</label>
<select name="PropControlType' . $PropertyCounter . '" class="form-control">
				<option selected="selected" value="0">' . _('Text Box') . '</option>
				<option value="1">' . _('Select Box') . '</option>
				<option value="2">' . _('Check Box') . '</option>
				<option value="3">' . _('Date Box') . '</option>
				</select></div></div>
				
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Default Value</label>
<input type="text" class="form-control" name="PropDefault' . $PropertyCounter . '" /></div></div></div>

<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Numeric Value</label>
<input type="checkbox" name="PropNumeric' . $PropertyCounter . '" /></div></div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Minimum Value</label>
<input type="text" class="form-control" name="PropMinimum' . $PropertyCounter . '" /></div></div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Maximum Value</label>
<input type="text" class="form-control" name="PropMaximum' . $PropertyCounter . '" /></div></div></div>
<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Require in SO</label>
<input type="checkbox" name="PropReqSO' . $PropertyCounter .'" /></div></div>';

	echo '<input type="hidden" name="PropertyCounter" value="' . $PropertyCounter . '" />';

} /* end if there is a category selected */

echo '
		<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" class="btn btn-success" name="submit" value="' . _('Enter Information') . '" />
		</div>
    </div>
	</div>
	
	</form>';

include('includes/footer.php');
?>
