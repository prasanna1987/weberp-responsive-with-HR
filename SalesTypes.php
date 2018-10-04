<?php

include('includes/session.php');
$Title = _('Sales Types') . ' / ' . _('Price List Maintenance');
include('includes/header.php');

if (isset($_POST['SelectedType'])){
	$SelectedType = mb_strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
	$SelectedType = mb_strtoupper($_GET['SelectedType']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;

	if (mb_strlen($_POST['TypeAbbrev']) > 2) {
		$InputError = 1;
		echo prnMsg(_('The sales type (price list) code must be two characters or less'),'error');
		$Errors[$i] = 'SalesType';
		$i++;
	} elseif ($_POST['TypeAbbrev']=='' OR $_POST['TypeAbbrev']==' ' OR $_POST['TypeAbbrev']=='  ') {
		$InputError = 1;
		echo prnMsg( _('The sales type (price list) code cannot be an empty string or spaces'),'error');
		$Errors[$i] = 'SalesType';
		$i++;
	} elseif( trim($_POST['Sales_Type'])==''){
		$InputError = 1;
		echo prnMsg (_('The sales type (price list) description cannot be empty'),'error');
		$Errors[$i] = 'SalesType';
		$i++;
	} elseif (mb_strlen($_POST['Sales_Type']) >40) {
		$InputError = 1;
		echo prnMsg(_('The sales type (price list) description must be forty characters or less'),'error');
		$Errors[$i] = 'SalesType';
		$i++;
	} elseif ($_POST['TypeAbbrev']=='AN'){
		$InputError = 1;
		echo prnMsg (_('The sales type code cannot be AN since this is a system defined abbreviation for any sales type in general ledger interface lookups'),'error');
		$Errors[$i] = 'SalesType';
		$i++;
	}

	if (isset($SelectedType) AND $InputError !=1) {

		$sql = "UPDATE salestypes
			SET sales_type = '" . $_POST['Sales_Type'] . "'
			WHERE typeabbrev = '".$SelectedType."'";

		$msg = _('The customer/sales/pricelist type') . ' ' . $SelectedType . ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the type is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM salestypes
			     WHERE typeabbrev = '" . $_POST['TypeAbbrev'] . "'";

		$CheckResult = DB_query($checkSql);
		$CheckRow = DB_fetch_row($CheckResult);

		if ( $CheckRow[0] > 0 ) {
			$InputError = 1;
			echo prnMsg( _('The customer/sales/pricelist type ') . $_POST['TypeAbbrev'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO salestypes (typeabbrev,
											sales_type)
							VALUES ('" . str_replace(' ', '', $_POST['TypeAbbrev']) . "',
									'" . $_POST['Sales_Type'] . "')";

			$msg = _('Customer/sales/pricelist type') . ' ' . $_POST['Sales_Type'] .  ' ' . _('has been created');
			$checkSql = "SELECT count(typeabbrev)
						FROM salestypes";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql);

	// Check the default price list exists
		$checkSql = "SELECT count(*)
			     FROM salestypes
			     WHERE typeabbrev = '" . $_SESSION['DefaultPriceList'] . "'";
		$CheckResult = DB_query($checkSql);
		$CheckRow = DB_fetch_row($CheckResult);

	// If it doesnt then update config with newly created one.
		if ($CheckRow[0] == 0) {
			$sql = "UPDATE config
					SET confvalue='".$_POST['TypeAbbrev']."'
					WHERE confname='DefaultPriceList'";
			$result = DB_query($sql);
			$_SESSION['DefaultPriceList'] = $_POST['TypeAbbrev'];
		}

		echo prnMsg($msg,'success');

		unset($SelectedType);
		unset($_POST['TypeAbbrev']);
		unset($_POST['Sales_Type']);
	}

} elseif ( isset($_GET['delete']) ) {

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'DebtorTrans'
	// Prevent delete if saletype exist in customer transactions

	$sql= "SELECT COUNT(*)
	       FROM debtortrans
	       WHERE debtortrans.tpe='".$SelectedType."'";

	$ErrMsg = _('The number of transactions using this customer/sales/pricelist type could not be retrieved');
	$result = DB_query($sql,$ErrMsg);

	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		echo prnMsg(_('Cannot delete this sale type because customer transactions have been created using this sales type') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('transactions using this sales type code'),'error');

	} else {

		$sql = "SELECT COUNT(*) FROM debtorsmaster WHERE salestype='".$SelectedType."'";

		$ErrMsg = _('The number of transactions using this Sales Type record could not be retrieved because');
		$result = DB_query($sql,$ErrMsg);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			echo prnMsg (_('Cannot delete this sale type because customers are currently set up to use this sales type') . '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('customers with this sales type code'))
                 ;
		} else {

			$sql="DELETE FROM salestypes WHERE typeabbrev='" . $SelectedType . "'";
			$ErrMsg = _('The Sales Type record could not be deleted because');
			$result = DB_query($sql,$ErrMsg);
			echo prnMsg(_('Sales type') . ' / ' . _('price list') . ' ' . $SelectedType  . ' ' . _('has been deleted') ,'success');

			$sql ="DELETE FROM prices WHERE prices.typeabbrev='" . $SelectedType . "'";
			$ErrMsg =  _('The Sales Type prices could not be deleted because');
			$result = DB_query($sql,$ErrMsg);

			echo prnMsg(' ...  ' . _('and any prices for this sales type / price list were also deleted'),'success');
			unset ($SelectedType);
			unset($_GET['delete']);

		}
	} //end if sales type used in debtor transactions or in customers set up
}


if(isset($_POST['Cancel'])){
	unset($SelectedType);
	unset($_POST['TypeAbbrev']);
	unset($_POST['Sales_Type']);
}

if (!isset($SelectedType)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedType will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of sales types will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT typeabbrev,sales_type FROM salestypes ORDER BY typeabbrev";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">

		<thead>
		<tr>
				<th class="ascending">' . _('Type Code') . '</th>
				<th class="ascending">' . _('Type Name') . '</th>
				<th colspan="2">' . _('Actions') . '</th>
			</tr>
		</thead>
		<tbody>';

while ($myrow = DB_fetch_row($result)) {

	printf('<tr class="striped_row">
		<td>%s</td>
		<td>%s</td>
		<td><a href="%sSelectedType=%s" class="btn btn-info">' . _('Edit') . '</a></td>
		<td><a href="%sSelectedType=%s&amp;delete=yes" onclick="return confirm(\'' . _('Are you sure you wish to delete this price list and all the prices it may have set up?') . '\');" class="btn btn-danger">' . _('Delete') . '</a></td>
		</tr>',
		$myrow[0],
		$myrow[1],
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?', $myrow[0],
		htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?', $myrow[0]);
	}
	//END WHILE LIST LOOP
	echo '</tbody></table></div></div></div>';
}

//end of ifs and buts!
if (isset($SelectedType)) {

	
}
if (! isset($_GET['delete'])) {
echo '<div class="row gutter30">
<div class="col-xs-12"><div class="block">
<div class="block-title"><h2>' . _('Sales Type/Price List Setup') . '</h2></div>
';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" >
	
<div class="row">	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<br />';


	// The user wish to EDIT an existing type
	if ( isset($SelectedType) AND $SelectedType!='' ) {

		$sql = "SELECT typeabbrev,
			       sales_type
		        FROM salestypes
		        WHERE typeabbrev='" . $SelectedType . "'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['TypeAbbrev'] = $myrow['typeabbrev'];
		$_POST['Sales_Type']  = $myrow['sales_type'];

		echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '" />
			<input type="hidden" name="TypeAbbrev" value="' . $_POST['TypeAbbrev'] . '" />
			
			
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Type Code') . '</label>
				' . $_POST['TypeAbbrev'] . '</div>
			</div>';

	} else 	{

		// This is a new type so the user may volunteer a type code

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Type Code') . '</label>
					<input type="text" ' . (in_array('SalesType',$Errors) ? 'class="inputerror"' : '' ) .' size="3" class="form-control" maxlength="2" name="TypeAbbrev" /></div>
				</div>';
	}

	if (!isset($_POST['Sales_Type'])) {
		$_POST['Sales_Type']='';
	}
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Sales Type Name') . '</label>
			<input type="text" class="form-control" name="Sales_Type" value="' . $_POST['Sales_Type'] . '" /></div>
		</div>
		</div>'; // close main table

	echo '<div class="row" align="center"><div>
<input type="submit" name="submit" class="btn btn-success" value="' . _('Submit') . '" /></div>

			</div><br />
          </form></div></div></div>
';

} // end if user wish to delete

include('includes/footer.php');
?>