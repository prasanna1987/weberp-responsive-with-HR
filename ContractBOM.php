<?php

include('includes/DefineContractClass.php');

include('includes/session.php');

$Title = _('Contract Bill of Materials');

$identifier=$_GET['identifier'];

/* If a contract header doesn't exist, then go to
 * Contracts.php to create one
 */

if (!isset($_SESSION['Contract'.$identifier])){
	header('Location:' . $RootPath . '/Contracts.php');
	exit;
}

$ViewTopic= 'Contracts';
$BookMark = 'AddToContract';

include('includes/header.php');

if (isset($_POST['UpdateLines']) OR isset($_POST['BackToHeader'])) {
	if($_SESSION['Contract'.$identifier]->Status!=2){ //dont do anything if the customer has committed to the contract
		foreach ($_SESSION['Contract'.$identifier]->ContractBOM as $ContractComponent) {
			if (filter_number_format($_POST['Qty'.$ContractComponent->ComponentID])==0){
				//this is the same as deleting the line - so delete it
				$_SESSION['Contract'.$identifier]->Remove_ContractComponent($ContractComponent->ComponentID);
			} else {
				$_SESSION['Contract'.$identifier]->ContractBOM[$ContractComponent->ComponentID]->Quantity=filter_number_format($_POST['Qty'.$ContractComponent->ComponentID]);
			}
		} // end loop around the items on the contract BOM
	} // end if the contract is not currently committed to by the customer
}// end if the user has hit the update lines or back to header buttons


if (isset($_POST['BackToHeader'])){
	echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/Contracts.php?identifier='.$identifier. '" />';
	echo '<br />';
	echo   prnMsg(_('You should automatically be forwarded to the Contract page. If this does not happen perhaps the browser does not support META Refresh') . '<a href="' . $RootPath . '/Contracts.php?identifier='.$identifier . '">' . _('click here') . '</a> ' . _('to continue'),'info');
	include('includes/footer.php');
	exit;
}

if (isset($_POST['Search'])){  /*ie seach for stock items */

	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		echo   prnMsg(_('Stock description keywords have been used in preference to the Stock ID extract entered'), 'info');
	}

	if ($_POST['Keywords']) {
		//insert wildcard characters in spaces
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if ($_POST['StockCat']=='All'){
			$sql = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE stockmaster.mbflag!='D'
					AND stockmaster.mbflag!='A'
					AND stockmaster.mbflag!='K'
					and stockmaster.discontinued!=1
					AND stockmaster.description " . LIKE . " '$SearchString'
					ORDER BY stockmaster.stockid";
		} else {
			$sql = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE stockmaster.mbflag!='D'
					AND stockmaster.mbflag!='A'
					AND stockmaster.mbflag!='K'
					and stockmaster.discontinued!=1
					AND stockmaster.description " . LIKE . " '$SearchString'
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} elseif ($_POST['StockCode']){

		$_POST['StockCode'] = '%' . $_POST['StockCode'] . '%';

		if ($_POST['StockCat']=='All'){
			$sql = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE stockmaster.mbflag!='D'
					AND stockmaster.mbflag!='A'
					AND stockmaster.mbflag!='K'
					AND stockmaster.discontinued!=1
					AND stockmaster.stockid " . LIKE . " '" . $_POST['StockCode'] . "'
					ORDER BY stockmaster.stockid";
		} else {
			$sql = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE stockmaster.mbflag!='D'
					AND stockmaster.mbflag!='A'
					AND stockmaster.mbflag!='K'
					AND stockmaster.discontinued!=1
					AND stockmaster.stockid " . LIKE . " '" . $_POST['StockCode'] . "'
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} else {
		if ($_POST['StockCat']=='All'){
			$sql = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE stockmaster.mbflag!='D'
					AND stockmaster.mbflag!='A'
					AND stockmaster.mbflag!='K'
					AND stockmaster.discontinued!=1
					ORDER BY stockmaster.stockid";
		} else {
			$sql = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE stockmaster.mbflag!='D'
					AND stockmaster.mbflag!='A'
					AND stockmaster.mbflag!='K'
					AND stockmaster.discontinued!=1
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}
	}

	$ErrMsg = _('There is a problem selecting the part records to display because');
	$DbgMsg = _('The SQL statement that failed was');
	$SearchResult = DB_query($sql,$ErrMsg,$DbgMsg);

	if (DB_num_rows($SearchResult)==0 AND $debug==1){
		echo  prnMsg( _('There are no products to display matching the criteria provided'),'warn');
	}
	if (DB_num_rows($SearchResult)==1){
		$myrow=DB_fetch_array($SearchResult);
		$_GET['NewItem'] = $myrow['stockid'];
		DB_data_seek($SearchResult,0);
	}

} //end of if search


if(isset($_GET['Delete'])){
	if($_SESSION['Contract'.$identifier]->Status!=2){
		$_SESSION['Contract'.$identifier]->Remove_ContractComponent($_GET['Delete']);
	} else {
		echo  prnMsg( _('The contract BOM cannot be altered because the contract has already been converted to an order'),'warn');
	}
}

if (isset($_POST['NewItem'])){ /* NewItem is set from the part selection list as the part code selected */
	for ($i=0;$i < $_POST['CountOfItems'];$i++) {
		$AlreadyOnThisBOM = 0;
		if (filter_number_format($_POST['Qty'.$i])>0){
			if (count($_SESSION['Contract'.$identifier]->ContractBOM)!=0){

				foreach ($_SESSION['Contract'.$identifier]->ContractBOM AS $Component) {

				/* do a loop round the items on the order to see that the item
				is not already on this order */
					if ($Component->StockID == trim($_POST['StockID'.$i])) {
						$AlreadyOnThisBOM = 1;
						echo prnMsg( _('The item') . ' ' . trim($_POST['StockID'.$i]) . ' ' . _('is already in the bill of material for this contract. The system will not allow the same item on the contract more than once. However you can change the quantity required for the item.'),'error');
					}
				} /* end of the foreach loop to look for preexisting items of the same code */
			}

			if ($AlreadyOnThisBOM!=1){

				$sql = "SELECT stockmaster.description,
								stockmaster.stockid,
								stockmaster.units,
								stockmaster.decimalplaces,
								stockmaster.materialcost+labourcost+overheadcost AS unitcost
							FROM stockmaster
							WHERE stockmaster.stockid = '". trim($_POST['StockID'.$i]) . "'";

				$ErrMsg = _('The item details could not be retrieved');
				$DbgMsg = _('The SQL used to retrieve the item details but failed was');
				$result1 = DB_query($sql,$ErrMsg,$DbgMsg);

				if ($myrow = DB_fetch_array($result1)){

					$_SESSION['Contract'.$identifier]->Add_To_ContractBOM (trim($_POST['StockID'.$i]),
																			$myrow['description'],
																			'',
																			filter_number_format($_POST['Qty'.$i]), /* Qty */
																			$myrow['unitcost'],
																			$myrow['units'],
																			$myrow['decimalplaces']);
				} else {
					echo prnMsg (_('The item code') . ' ' . trim($_POST['StockID'.$i]) . ' ' . _('does not exist in the system and therefore cannot be added to the contract BOM'),'error');
					if ($debug==1){
						echo '<br />' . $sql;
					}
					include('includes/footer.php');
					exit;
				}
			} /* end of if not already on the contract BOM */
		} /* the quantity of the item is > 0 */
	}
} /* end of if its a new item */

/* This is where the order as selected should be displayed  reflecting any deletions or insertions*/

echo '<form id="ContractBOMForm" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier. '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


if (count($_SESSION['Contract'.$identifier]->ContractBOM)>0){
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>  '.$_SESSION['Contract'.$identifier]->CustomerName . '
		</h1></a></div>';

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

	if (isset($_SESSION['Contract'.$identifier]->ContractRef)) {
		echo  '<tr>
				<th colspan="7">' . _('Contract Reference') . ': '. $_SESSION['Contract'.$identifier]->ContractRef . '</th>
			</tr>';
	}

	echo '<thead><tr>
			<th>' . _('Item Code') . '</th>
			<th>' . _('Description') . '</th>
			<th>' . _('Quantity') . '</th>
			<th>' . _('UOM')  . '</th>
			<th>' . _('Unit Cost') .  '</th>
			<th>' . _('Sub-total') . '</th>
		</tr></thead>';

	$_SESSION['Contract'.$identifier]->total = 0;

	$TotalCost =0;
	foreach ($_SESSION['Contract'.$identifier]->ContractBOM as $ContractComponent) {

		$LineTotal = $ContractComponent->Quantity * $ContractComponent->ItemCost;

		$DisplayLineTotal = locale_number_format($LineTotal,$_SESSION['CompanyRecord']['decimalplaces']);

		echo '<tr class="striped_row">
				<td>' . $ContractComponent->StockID . '</td>
			  <td>' . $ContractComponent->ItemDescription . '</td>
			  <td><input type="text" class="form-control" required="required" title="' . _('Enter the quantity of this component required to complete the contract') . '" name="Qty' . $ContractComponent->ComponentID . '" size="11" value="' . locale_number_format($ContractComponent->Quantity,$ContractComponent->DecimalPlaces)  . '" /></td>
			  <td>' . $ContractComponent->UOM . '</td>
			  <td class="number">' . locale_number_format($ContractComponent->ItemCost,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			  <td class="number">' . $DisplayLineTotal . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier. '&amp;Delete=' . $ContractComponent->ComponentID . '" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this item from the contract BOM?') . '");\'>' . _('Delete') . '</a></td>
			</tr>';
		$TotalCost += $LineTotal;
	}

	$DisplayTotal = locale_number_format($TotalCost,$_SESSION['CompanyRecord']['decimalplaces']);
	echo '<tr>
			<td colspan="5" class="number">' . _('Total Cost') . '</td>
			<td class="number"><b>' . $DisplayTotal . '</b></td>
		</tr>
		</table></div></div></div>';
	echo '<br />
			<div class="row">
			<div class="col-xs-4">
			<input type="submit" name="UpdateLines" class="btn btn-info" value="' . _('Update Lines') . '" /></div>';
	echo '<div class="col-xs-4">
	<input type="submit" name="BackToHeader" class="btn btn-success" value="' . _('Submit') . '" /></div></div>
	<div class="page-header"></div>';

} /*Only display the contract BOM lines if there are any !! */

if (!isset($_GET['Edit']))  {
	$sql="SELECT categoryid,
				categorydescription
			FROM stockcategory
			WHERE stocktype<>'L'
			AND stocktype<>'D'
			ORDER BY categorydescription";
	$ErrMsg = _('The supplier category details could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the category details but failed was');
	$result1 = DB_query($sql,$ErrMsg,$DbgMsg);
	echo '<br />';
	echo '<div class="row">
<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label"><br /></label>
<select name="StockCat" class="form-control">';

	echo '<option selected="selected" value="All">' . _('All') . '</option>';
	while ($myrow1 = DB_fetch_array($result1)) {
		if (isset($_POST['StockCat']) and $_POST['StockCat']==$myrow1['categoryid']){
			echo '<option selected="selected" value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
		} else {
			echo '<option value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
		}
	}

	unset($_POST['Keywords']);
	unset($_POST['StockCode']);

	if (!isset($_POST['Keywords'])) {
		$_POST['Keywords']='';
	}

	if (!isset($_POST['StockCode'])) {
		$_POST['StockCode']='';
	}

	echo '</select></div></div>
			<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Description-part or full') . '</label>
			<input type="text" class="form-control" autofocus="autofocus" title="' . _('Enter any text that should appear in the item description as the basis of your search') . '" name="Keywords" size="20" maxlength="25" value="' . $_POST['Keywords'] . '" /></div>
		</div>
		<div class="col-xs-3">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Stock ID-Part or full') . '</label>
			<input type="text" class="form-control" title="' . _('Enter any part of an item code to seach for all matching items containing that text in the code') . '" name="StockCode" size="15" maxlength="18" value="' . $_POST['StockCode'] . '" /></div>
		</div>
		<div class="col-xs-3">
<div class="form-group"> <br /><a target="_blank" href="'.$RootPath.'/Stocks.php" class="btn btn-info">' . _('Create a New Stock Item') . '</a></div>
		</div>
		</div>
		
		<div class="row" align="center">
		
			<input type="submit" class="btn btn-success" name="Search" value="' . _('Search') . '" />
		
		</div>
		<br />';

}

if (isset($SearchResult)) {

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

	$TableHeader = '<tr>
						<th>' . _('Code')  . '</th>
						<th>' . _('Description') . '</th>
						<th>' . _('Units') . '</th>
						<th>' . _('Image') . '</th>
						<th>' . _('Quantity') . '</th>
					</tr>';
	echo $TableHeader;

	$i=0;
	while ($myrow=DB_fetch_array($SearchResult)) {

		$SupportedImgExt = array('png','jpg','jpeg');
		$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
		if (extension_loaded('gd') && function_exists('gd_info') && file_exists ($imagefile) ) {
			$ImageSource = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
				'&amp;StockID='.urlencode($myrow['stockid']).
				'&amp;text='.
				'&amp;width=64'.
				'&amp;height=64'.
				'" alt="" />';
		} else if (file_exists ($imagefile)) {
			$ImageSource = '<img src="' . $imagefile . '" height="50" width="50" />';
		} else {
			$ImageSource = _('No Image');
		}

		echo '<tr class="striped_row">
				<td>' . $myrow['stockid'] . '</td>
				<td>' . $myrow['description'] . '</td>
				<td>' . $myrow['units'] . '</td>
				<td>' . $ImageSource . '</td>
				<td class="has-error"><input class="form-control" type="text" title="' . _('Enter the quantity required of this item to complete the contract') . '" required="required" size="6" value="0" name="Qty'.$i.'" />
				<input type="hidden" name="StockID' . $i . '" value="' . $myrow['stockid'] . '" />
				</td>
			</tr>';
		$i++;
		if ($i == $_SESSION['DisplayRecordsMax']){
			break;
		}
#end of page full new headings if
	}

#end of while loop
	echo '</table></div></div></div><br />
			<input type="hidden" name="CountOfItems" value="'. $i . '" />';
	if ($i == $_SESSION['DisplayRecordsMax']){

		echo   prnMsg( _('Only the first') . ' ' . $_SESSION['DisplayRecordsMax'] . ' ' . _('can be displayed') . '. ' . _('Please restrict your search to only the items required'),'info');
	}
	echo '
		<div class="row" align="center">
		
			<input type="submit" class="btn btn-success" name="NewItem" value="' . _('Add to Contract') .'" />
		</div><br />';
}#end if SearchResults to show

echo '
	</form>';
include('includes/footer.php');
?>