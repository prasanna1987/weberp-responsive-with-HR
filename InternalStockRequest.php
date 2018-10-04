<?php


include('includes/DefineStockRequestClass.php');

include('includes/session.php');
$Title = _('Create an Internal Stock Request');
$ViewTopic = 'Inventory';
$BookMark = 'CreateRequest';
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['New'])) {
	unset($_SESSION['Transfer']);
	$_SESSION['Request'] = new StockRequest();
}

if (isset($_POST['Update'])) {
	$InputError=0;
	if ($_POST['Department']=='') {
		echo prnMsg( _('You must select a Department for the request'), 'error');
		$InputError=1;
	}
	if ($_POST['Location']=='') {
		echo prnMsg( _('You must select a Location to request the items from'), 'error');
		$InputError=1;
	}
	if ($InputError==0) {
		$_SESSION['Request']->Department=$_POST['Department'];
		$_SESSION['Request']->Location=$_POST['Location'];
		$_SESSION['Request']->DispatchDate=$_POST['DispatchDate'];
		$_SESSION['Request']->Narrative=$_POST['Narrative'];
	}
}

if (isset($_POST['Edit'])) {
	$_SESSION['Request']->LineItems[$_POST['LineNumber']]->Quantity=$_POST['Quantity'];
}

if (isset($_GET['Delete'])) {
	unset($_SESSION['Request']->LineItems[$_GET['Delete']]);
	echo '<br />';
	echo prnMsg( _('The line was successfully deleted'), 'success');
	echo '<br />';
}

foreach ($_POST as $key => $value) {
	if (mb_strstr($key,'StockID')) {
		$Index=mb_substr($key, 7);
		if (filter_number_format($_POST['Quantity'.$Index])>0) {
			$StockID=$value;
			$ItemDescription=$_POST['ItemDescription'.$Index];
			$DecimalPlaces=$_POST['DecimalPlaces'.$Index];
			$NewItem_array[$StockID] = filter_number_format($_POST['Quantity'.$Index]);
			$_POST['Units'.$StockID]=$_POST['Units'.$Index];
			$_SESSION['Request']->AddLine($StockID, $ItemDescription, $NewItem_array[$StockID], $_POST['Units'.$StockID], $DecimalPlaces);
		}
	}
}

if (isset($_POST['Submit']) AND (!empty($_SESSION['Request']->LineItems))) {

	DB_Txn_Begin();
	$InputError=0;
	if ($_SESSION['Request']->Department=='') {
		echo prnMsg( _('You must select a Department for the request'), 'error');
		$InputError=1;
	}
	if ($_SESSION['Request']->Location=='') {
		echo prnMsg( _('You must select a Location to request the items from'), 'error');
		$InputError=1;
	}
	if ($InputError==0) {
		$RequestNo = GetNextTransNo(38);
		$HeaderSQL="INSERT INTO stockrequest (dispatchid,
											loccode,
											departmentid,
											despatchdate,
											narrative,
											initiator)
										VALUES(
											'" . $RequestNo . "',
											'" . $_SESSION['Request']->Location . "',
											'" . $_SESSION['Request']->Department . "',
											'" . FormatDateForSQL($_SESSION['Request']->DispatchDate) . "',
											'" . $_SESSION['Request']->Narrative . "',
											'" . $_SESSION['UserID'] . "')";
		$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The request header record could not be inserted because');
		$DbgMsg = _('The following SQL to insert the request header record was used');
		$Result = DB_query($HeaderSQL,$ErrMsg,$DbgMsg,true);

		foreach ($_SESSION['Request']->LineItems as $LineItems) {
			$LineSQL="INSERT INTO stockrequestitems (dispatchitemsid,
													dispatchid,
													stockid,
													quantity,
													decimalplaces,
													uom)
												VALUES(
													'".$LineItems->LineNumber."',
													'".$RequestNo."',
													'".$LineItems->StockID."',
													'".$LineItems->Quantity."',
													'".$LineItems->DecimalPlaces."',
													'".$LineItems->UOM."')";
			$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The request line record could not be inserted because');
			$DbgMsg = _('The following SQL to insert the request header record was used');
			$Result = DB_query($LineSQL,$ErrMsg,$DbgMsg,true);
		}

		$EmailSQL="SELECT email
					FROM www_users, departments
					WHERE departments.authoriser = www_users.userid
						AND departments.departmentid = '" . $_SESSION['Request']->Department ."'";
		$EmailResult = DB_query($EmailSQL);
		if ($myEmail=DB_fetch_array($EmailResult)){
			$ConfirmationText = _('An internal stock request has been created and is waiting for your authoritation');
			$EmailSubject = _('Internal Stock Request needs your authoritation');
			 if($_SESSION['SmtpSetting']==0){
			       mail($myEmail['email'],$EmailSubject,$ConfirmationText);
			}else{
				include('includes/htmlMimeMail.php');
				$mail = new htmlMimeMail();
				$mail->setSubject($EmailSubject);
				$mail->setText($ConfirmationText);
				$result = SendmailBySmtp($mail,array($myEmail['email']));
			}
		}
	}
	DB_Txn_Commit();
	echo prnMsg( _('The internal stock request has been entered and now needs to be authorised'), 'success');
	echo '<br /><div class="row"><div class="col-xs-4"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?New=Yes" class="btn btn-info">', _('Create another request'), '</a></div></div>';
	include('includes/footer.php');
	unset($_SESSION['Request']);
	exit;
} elseif(isset($_POST['Submit'])) {
	echo prnMsg(_('There are no items added to this request'),'error');
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1>', ' ', $Title, '</h1></a></div>';

if (isset($_GET['Edit'])) {
	
	echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">';
   
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>', _('Edit the Request Line'), '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
';
	echo '<tr>
			<td>', _('Line number'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->LineNumber, '</td>
		</tr>
		<tr>
			<td>', _('Stock Code'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->StockID, '</td>
		</tr>
		<tr>
			<td>', _('Item Description'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->ItemDescription, '</td>
		</tr>
		<tr>
			<td>', _('Unit of Measure'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->UOM, '</td>
		</tr>
		<tr>
			<td>', _('Quantity Requested'), '</td>
			<td><input type="text" class="form-control" name="Quantity" value="', locale_number_format($_SESSION['Request']->LineItems[$_GET['Edit']]->Quantity, $_SESSION['Request']->LineItems[$_GET['Edit']]->DecimalPlaces), '" /></td>
		</tr>';
	echo '<input type="hidden" name="LineNumber" value="', $_SESSION['Request']->LineItems[$_GET['Edit']]->LineNumber, '" />';
	echo '</table></div></div></div></div>
		<br />';
	echo '<div class="row">
	<div class="col-xs-4">
			<input type="submit" class="btn btn-info" name="Edit" value="', _('Update Line'), '" />
		</div>
        </div>
		</form>';
	include('includes/footer.php');
	exit;
}
echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">
	
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
	<div class="block">
	<div class="block-title"><h3>', _('Internal Stock Request Details'), '</h3></div>
		
	<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Department') . '</label>';
if($_SESSION['AllowedDepartment'] == 0){
	// any internal department allowed
	$sql="SELECT departmentid,
				description
			FROM departments
			ORDER BY description";
}else{
	// just 1 internal department allowed
	$sql="SELECT departmentid,
				description
			FROM departments
			WHERE departmentid = '". $_SESSION['AllowedDepartment'] ."'
			ORDER BY description";
}
$result=DB_query($sql);
echo '<select name="Department" class="form-control">';
while ($myrow=DB_fetch_array($result)){
	if (isset($_SESSION['Request']->Department) AND $_SESSION['Request']->Department==$myrow['departmentid']){
		echo '<option selected value="', $myrow['departmentid'], '">', htmlspecialchars($myrow['description'], ENT_QUOTES,'UTF-8'), '</option>';
	} else {
		echo '<option value="', $myrow['departmentid'], '">', htmlspecialchars($myrow['description'], ENT_QUOTES,'UTF-8'), '</option>';
	}
}
echo '</select></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock Location') . '</label>';
$sql="SELECT locations.loccode,
			locationname
		FROM locations
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
		WHERE internalrequest = 1
		ORDER BY locationname";

$result=DB_query($sql);
echo '<select name="Location" class="form-control">
		<option value="">', _('Select a Location'), '</option>';
while ($myrow=DB_fetch_array($result)){
	if (isset($_SESSION['Request']->Location) AND $_SESSION['Request']->Location==$myrow['loccode']){
		echo '<option selected value="', $myrow['loccode'], '">', $myrow['loccode'], ' - ', htmlspecialchars($myrow['locationname'], ENT_QUOTES,'UTF-8'), '</option>';
	} else {
		echo '<option value="', $myrow['loccode'], '">', $myrow['loccode'], ' - ', htmlspecialchars($myrow['locationname'], ENT_QUOTES,'UTF-8'),  '</option>';
	}
}
echo '</select></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Date when required'), '</label>
		<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="DispatchDate" maxlength="10" size="11" value="', $_SESSION['Request']->DispatchDate, '" /></div>
	</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">',  _('Narrative'), '</label>
	<textarea name="Narrative" class="form-control">', $_SESSION['Request']->Narrative, '</textarea></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"><br />
		<input type="submit" class="btn btn-success" name="Update" value="',  _('Submit'), '" />
	</div>
    </div>
	</div><br />
	</div>
	</form></div></div>';

if (!isset($_SESSION['Request']->Location)) {
	include('includes/footer.php');
	exit;
}

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">
	
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
	
	<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>', _('Details of Items Requested'), '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<thead>
	
	<tr>
		<th>',  _('Line Number'), '</th>
		<th>',  _('Item Code'), '</th>
		<th>',  _('Item Description'), '</th>
		<th>',  _('Quantity Required'), '</th>
		<th>',  _('UOM'), '</th>
		</tr>
	</thead>
	<tbody>';

if (isset($_SESSION['Request']->LineItems)) {
	foreach ($_SESSION['Request']->LineItems as $LineItems) {
		echo '<tr class="striped_row">
				<td>', $LineItems->LineNumber, '</td>
				<td>', $LineItems->StockID, '</td>
				<td>', $LineItems->ItemDescription, '</td>
				<td class="number">', locale_number_format($LineItems->Quantity, $LineItems->DecimalPlaces), '</td>
				<td>', $LineItems->UOM, '</td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Edit=', urlencode($LineItems->LineNumber), '" class="btn btn-info">', _('Edit'), '</a></td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Delete=', urlencode($LineItems->LineNumber), '" class="btn btn-danger">', _('Delete'), '</a></td>
			</tr>';
	}
}

echo '</tbody>

	</table></div></div></div></div>
	
	<div class="row" align="center">
	<input type="submit" class="btn btn-info" name="Submit" value="', _('Submit'), '" />
	</div>
   <br />
    </form>';

echo '<h2 class="page-header">', ' ', _('Search for Inventory Items'),
	'</h2>
	<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">
	
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';

$SQL = "SELECT stockcategory.categoryid,
				stockcategory.categorydescription
		FROM stockcategory
		INNER JOIN internalstockcatrole
			ON stockcategory.categoryid = internalstockcatrole.categoryid
		WHERE internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
			ORDER BY stockcategory.categorydescription";

$result1 = DB_query($SQL);
if (DB_num_rows($result1) == 0) {
	echo '<p class="text-danger">', _('Problem Report'), ':<br />', _('There are no stock categories currently defined please use the link below to set them up'), '</p>
		<br />
		<div class="row" align="center">
<a href="', $RootPath, '/StockCategories.php" class="btn btn-info">', _('Define Stock Categories'), '</a></div>';
	exit;
}

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('In Stock Category') . '</label>
<select name="StockCat" class="form-control">';

if (!isset($_POST['StockCat'])) {
	$_POST['StockCat'] = 'All';
}

if ($_POST['StockCat'] == 'All') {
	echo '<option selected value="All">' . _('All Authorized') . '</option>';
} else {
	echo '<option value="All">' . _('All Authorized') . '</option>';
}

while ($myrow1 = DB_fetch_array($result1)) {
	if ($myrow1['categoryid'] == $_POST['StockCat']) {
		echo '<option selected value="',  $MyRow1['categoryid'],  '">',  $MyRow1['categorydescription'],  '</option>';
	} else {
		echo '<option value="',  $MyRow1['categoryid'],  '">',  $MyRow1['categorydescription'],  '</option>';
	}
}

echo '</select></div></div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Enter partial'), ' ', _('Description'), '</label>';

if (isset($_POST['Keywords'])) {
	echo '<input type="text" class="form-control" name="Keywords" value="',  $_POST['Keywords'],  '" size="20" maxlength="25" /></div></div>';
} else {
	echo '<input type="text" class="form-control" name="Keywords" size="20" maxlength="25" /></div></div>';
}

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', _('Enter partial'), ' ', _('Stock Code'), '</label>';

if (isset($_POST['StockCode'])) {
	echo '<input type="text" class="form-control" autofocus="autofocus" name="StockCode" value="',  $_POST['StockCode'],  '" size="15" maxlength="18" /></div></div>';
} else {
	echo '<input type="text" class="form-control" name="StockCode" size="15" maxlength="18" /></div></div>';
}

echo '
	</div>
	
	<div class="row" align="center">

		<input type="submit" class="btn btn-info" name="Search" value="', _('Search Now'), '" />
	
	</div><br />
	</form>';

if (isset($_POST['Search']) or isset($_POST['Next']) or isset($_POST['Previous'])){

	if ($_POST['Keywords']!='' AND $_POST['StockCode']=='') {
		echo prnMsg ( _('Order Item description has been used in search'), 'warn' );
	} elseif ($_POST['StockCode']!='' AND $_POST['Keywords']=='') {
		echo prnMsg ( _('Stock Code has been used in search'), 'warn' );
	} elseif ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		echo prnMsg ( _('Stock Category has been used in search'), 'warn' );
	}

	if (isset($_POST['Keywords']) AND mb_strlen($_POST['Keywords'])>0) {
		//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if ($_POST['StockCat']=='All'){
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.description " . LIKE . " '" . $SearchString . "'
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} elseif (mb_strlen($_POST['StockCode'])>0){

		$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
		$SearchString = '%' . $_POST['StockCode'] . '%';

		if ($_POST['StockCat']=='All'){
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} else {
		if ($_POST['StockCat']=='All'){
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}
	}

	if (isset($_POST['Next'])) {
		$Offset = $_POST['NextList'];
	}
	if (isset($_POST['Previous'])) {
		$Offset = $_POST['PreviousList'];
	}
	if (!isset($Offset) or $Offset<0) {
		$Offset=0;
	}
	$SQL = $SQL . ' LIMIT ' . $_SESSION['DisplayRecordsMax'] . ' OFFSET ' . ($_SESSION['DisplayRecordsMax']*$Offset);

	$ErrMsg = _('There is a problem selecting the part records to display because');
	$DbgMsg = _('The SQL used to get the part selection was');
	$SearchResult = DB_query($SQL,$ErrMsg, $DbgMsg);

	if (DB_num_rows($SearchResult)==0 ){
		echo prnMsg (_('There are no products available meeting the criteria specified'),'info');
	}
	if (DB_num_rows($SearchResult)<$_SESSION['DisplayRecordsMax']){
		$Offset=0;
	}

} //end of if search

if (isset($SearchResult)) {
	$j = 1;
	echo '<br />
		<p class="text-info"><strong>', _('Select an item by entering the quantity required.  Click Order when ready.'), '</strong></p>
		
		<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post" id="orderform">
		
		<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
		<div class="col-xs-12"><div class="table-responsive">
<table class="table">
		<tr>
			<td align="left">
					<input type="hidden" name="PreviousList" value="', ($Offset - 1), '" />
					<input tabindex="', ($j+8), '" type="submit" class="btn btn-default" name="Previous" value="', _('Previous'), '" /></td>
				<td align="center">
				<input type="hidden" name="order_items" value="1" />
					<input tabindex="', ($j+9), '" type="submit" class="btn btn-info" value="', _('Add to Requisition'), '" /></td>
			<td align="right">
					<input type="hidden" name="NextList" value="', ($Offset + 1), '" />
					<input tabindex="', ($j+10), '" type="submit" class="btn btn-default" name="Next" value="', _('Next'), '" /></td>
			</tr>
			</table>
			</div></div>
			<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
				<th class="ascending">', _('Code'), '</th>
				<th class="ascending">', _('Description'), '</th>
				<th>', _('Units'), '</th>
				<th class="ascending">', _('On Hand'), '</th>
				<th class="ascending">', _('On Demand'), '</th>
				<th class="ascending">', _('On Order'), '</th>
				<th class="ascending">', _('Available'), '</th>
				<th class="ascending">', _('Quantity'), '</th>
			</tr>
		</thead>
		<tbody>';

	$ImageSource = _('No Image');

	$i=0;
	while ($myrow=DB_fetch_array($SearchResult)) {
		if ($myrow['decimalplaces']=='') {
			/* This REALLY seems to be a redundant (unnecessary) re-query?
			 * The default on stockmaster is 0, so an empty string should never
			 * be true, as decimalplaces is in all queries from lines 382-482.
			 */
			$DecimalPlacesSQL="SELECT decimalplaces
								FROM stockmaster
								WHERE stockid='" .$myrow['stockid'] . "'";
			$DecimalPlacesResult = DB_query($DecimalPlacesSQL);
			$DecimalPlacesRow = DB_fetch_array($DecimalPlacesResult);
			$DecimalPlaces = $DecimalPlacesRow['decimalplaces'];
		} else {
			$DecimalPlaces=$myrow['decimalplaces'];
		}

		$QOHSQL = "SELECT sum(locstock.quantity) AS qoh
							   FROM locstock
					WHERE locstock.stockid='" .$MyRow['stockid'] . "'
						AND loccode = '" . $_SESSION['Request']->Location . "'";
		$QOHResult =  DB_query($QOHSQL);
		$QOHRow = DB_fetch_array($QOHResult);
		$QOH = $QOHRow['qoh'];

		// Find the quantity on outstanding sales orders
		$sql = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
				FROM salesorderdetails
				INNER JOIN salesorders
				 ON salesorders.orderno = salesorderdetails.orderno
				 WHERE salesorders.fromstkloc='" . $_SESSION['Request']->Location . "'
				 AND salesorderdetails.completed=0
				 AND salesorders.quotation=0
				 AND salesorderdetails.stkcode='" . $myrow['stockid'] . "'";
		$ErrMsg = _('The demand for this product from') . ' ' . $_SESSION['Request']->Location . ' ' . _('cannot be retrieved because');
		$DemandResult = DB_query($sql,$ErrMsg);

		$DemandRow = DB_fetch_row($DemandResult);
		if ($DemandRow[0] != null){
			$DemandQty =  $DemandRow[0];
		} else {
		  $DemandQty = 0;
		}

		$PurchQty = GetQuantityOnOrderDueToPurchaseOrders($MyRow['stockid'], '');
		$WoQty = GetQuantityOnOrderDueToWorkOrders($MyRow['stockid'], '');

		$OnOrder = $PurchQty + $WoQty;
		$Available = $QOH - $DemandQty + $OnOrder;

		echo '<tr class="striped_row">
				<td>', $myrow['stockid'], '</td>
				<td>', $myrow['description'], '</td>
				<td>', $myrow['stockunits'], '</td>
				<td class="number">', locale_number_format($QOH,$DecimalPlaces), '</td>
				<td class="number">', locale_number_format($DemandQty,$DecimalPlaces), '</td>
				<td class="number">', locale_number_format($OnOrder, $DecimalPlaces), '</td>
				<td class="number">', locale_number_format($Available,$DecimalPlaces), '</td>
				<td><input class="form-control" ', ($i==0 ? 'autofocus="autofocus"':''), ' tabindex="', ($j+7), '" type="text" size="6" name="Quantity', $i, '" value="0" />
				<input type="hidden" name="StockID', $i, '" value="', $myrow['stockid'], '" />
				</td>
			</tr>
			<input type="hidden" name="DecimalPlaces', $i, '" value="', $myrow['decimalplaces'], '" />
			<input type="hidden" name="ItemDescription', $i, '" value="', $myrow['description'], '" />
			<input type="hidden" name="Units', $i, '" value="', $myrow['stockunits'],  '" />';
		$i++;
	}
#end of while loop
	echo '</tbody></table>
       </div></div></div>
		<div class="col-xs-12"><div class="table-responsive">
<table class="table">
				<td align="left"><input type="hidden" name="PreviousList" value="', ($Offset - 1), '" />
					<input tabindex="', ($j+7), '" type="submit" class="btn btn-default" name="Previous" value="', _('Previous'), '" /></td>
			<td align="center"><input type="hidden" name="order_items" value="1" />
					<input tabindex="', ($j+8), '" type="submit" class="btn btn-success" value="', _('Add to Requisition'), '" /></td>
				<td align="right"><input type="hidden" name="NextList" value="', ($Offset + 1), '" />
					<input tabindex="', ($j+9), '" type="submit" class="btn btn-default" name="Next" value="', _('Next'), '" /></td>
			</tr>
		</table>
		</div></div>
       </form><br /><br /><br /><br />';
}#end if SearchResults to show

//*********************************************************************************************************
include('includes/footer.php');
?>
