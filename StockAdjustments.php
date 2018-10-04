<?php


include('includes/DefineStockAdjustment.php');
include('includes/DefineSerialItems.php');
include('includes/session.php');
$Title = _('Stock Adjustments');

/* nERP manual links before header.php */
$ViewTopic= 'Inventory';
$BookMark = 'InventoryAdjustments';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other adjustment sessions on the same machine  */
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (isset($_GET['NewAdjustment'])){
	unset($_SESSION['Adjustment' . $identifier]);
	$_SESSION['Adjustment' . $identifier] = new StockAdjustment();
}

if (!isset($_SESSION['Adjustment' . $identifier])){
	$_SESSION['Adjustment' . $identifier] = new StockAdjustment();
}

$NewAdjustment = false;

if (isset($_GET['StockID'])){
	$NewAdjustment = true;
	$StockID = trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	if($_POST['StockID'] != $_SESSION['Adjustment' . $identifier]->StockID){
		$NewAdjustment = true;
		$StockID = trim(mb_strtoupper($_POST['StockID']));
	}
}

if ($NewAdjustment==true){

	$_SESSION['Adjustment' . $identifier]->StockID = trim(mb_strtoupper($StockID));
	$result = DB_query("SELECT description,
							controlled,
							serialised,
							decimalplaces,
							perishable,
							materialcost+labourcost+overheadcost AS totalcost,
							units
						FROM stockmaster
						WHERE stockid='" . $_SESSION['Adjustment' . $identifier]->StockID . "'");
	$myrow = DB_fetch_array($result);
	$_SESSION['Adjustment' . $identifier]->ItemDescription = $myrow['description'];
	$_SESSION['Adjustment' . $identifier]->Controlled = $myrow['controlled'];
	$_SESSION['Adjustment' . $identifier]->Serialised = $myrow['serialised'];
	$_SESSION['Adjustment' . $identifier]->DecimalPlaces = $myrow['decimalplaces'];
	$_SESSION['Adjustment' . $identifier]->SerialItems = array();
	if (!isset($_SESSION['Adjustment' . $identifier]->Quantity) OR !is_numeric($_SESSION['Adjustment' . $identifier]->Quantity)){
		$_SESSION['Adjustment' . $identifier]->Quantity=0;
	}

	$_SESSION['Adjustment' . $identifier]->PartUnit = $myrow['units'];
	$_SESSION['Adjustment' . $identifier]->StandardCost = $myrow['totalcost'];
	$DecimalPlaces = $myrow['decimalplaces'];
	DB_free_result($result);


} //end if it's a new adjustment
if (isset($_POST['tag'])){
	$_SESSION['Adjustment' . $identifier]->tag = $_POST['tag'];
}
if (isset($_POST['Narrative'])){
	$_SESSION['Adjustment' . $identifier]->Narrative = $_POST['Narrative'];
}

$sql = "SELECT locations.loccode, locationname FROM locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
$resultStkLocs = DB_query($sql);
$LocationList=array();
while ($myrow=DB_fetch_array($resultStkLocs)){
	$LocationList[$myrow['loccode']]=$myrow['locationname'];
}

if (isset($_POST['StockLocation'])){
	if($_SESSION['Adjustment' . $identifier]->StockLocation != $_POST['StockLocation']){/* User has changed the stock location, so the serial no must be validated again */
		$_SESSION['Adjustment' . $identifier]->SerialItems = array();
	}
	$_SESSION['Adjustment' . $identifier]->StockLocation = $_POST['StockLocation'];
}else{
	if(empty($_SESSION['Adjustment' . $identifier]->StockLocation)){
		if(empty($_SESSION['UserStockLocation'])){
			$_SESSION['Adjustment' . $identifier]->StockLocation=key(reset($LocationList));
		}else{
			$_SESSION['Adjustment' . $identifier]->StockLocation=$_SESSION['UserStockLocation'];
		}
	}
}
if (isset($_POST['Quantity'])){
	if ($_POST['Quantity']=='' OR !is_numeric(filter_number_format($_POST['Quantity']))){
		$_POST['Quantity']=0;
	}
} else {
	$_POST['Quantity']=0;
}
if($_POST['Quantity'] != 0){//To prevent from serilised quantity changing to zero
	$_SESSION['Adjustment' . $identifier]->Quantity = filter_number_format($_POST['Quantity']);
	if(count($_SESSION['Adjustment' . $identifier]->SerialItems) == 0 AND $_SESSION['Adjustment' . $identifier]->Controlled == 1 ){/* There is no quantity available for controlled items */
		$_SESSION['Adjustment' . $identifier]->Quantity = 0;
	}
}
if(isset($_GET['OldIdentifier'])){
	$_SESSION['Adjustment'.$identifier]->StockLocation=$_SESSION['Adjustment'.$_GET['OldIdentifier']]->StockLocation;
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Inventory Adjustment') . '</h1></a></div>';

if (isset($_POST['CheckCode'])) {

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Select Item to Adjust') . '</h1></a></div>';

	if (mb_strlen($_POST['StockText'])>0) {
		$sql="SELECT stockid,
					description
				FROM stockmaster
				WHERE description " . LIKE . " '%" . $_POST['StockText'] ."%'";
	} else {
		$sql="SELECT stockid,
					description
				FROM stockmaster
				WHERE stockid " . LIKE  . " '%" . $_POST['StockCode'] ."%'";
	}
	$ErrMsg=_('The stock information cannot be retrieved because');
	$DbgMsg=_('The SQL to get the stock description was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead><tr>
				<th>' . _('Stock Code') . '</th>
				<th>' . _('Stock Description') . '</th>
				<th>' . _('Action') . '</th>
			</tr></thead>';
	while ($myrow = DB_fetch_row($result)) {
		echo '<tr>
				<td>' . $myrow[0] . '</td>
				<td>' . $myrow[1] . '</td>
				<td><a href="StockAdjustments.php?StockID='.$myrow[0].'&amp;Description='.$myrow[1].'&amp;OldIdentifier='.$identifier.'" class="btn btn-success">' . _('Adjust') . '</a>
			</tr>';
	}
	echo '</table></div></div></div>';
	include('includes/footer.php');
	exit;
}

if (isset($_POST['EnterAdjustment']) AND $_POST['EnterAdjustment']!= ''){

	$InputError = false; /*Start by hoping for the best */
	$result = DB_query("SELECT * FROM stockmaster WHERE stockid='" . $_SESSION['Adjustment' . $identifier]->StockID . "'");
	$myrow = DB_fetch_row($result);
	if (DB_num_rows($result)==0) {
		echo prnMsg( _('The entered item code does not exist'),'error');
		$InputError = true;
	} elseif (!is_numeric($_SESSION['Adjustment' . $identifier]->Quantity)){
		echo prnMsg( _('The quantity entered must be numeric'),'error');
		$InputError = true;
	} elseif(strlen(substr(strrchr($_SESSION['Adjustment'.$identifier]->Quantity, "."), 1))>$_SESSION['Adjustment' . $identifier]->DecimalPlaces){
		echo prnMsg(_('The decimal places input is more than the decimals of this item defined,the defined decimal places is ').' '.$_SESSION['Adjustment' . $identifier]->DecimalPlaces.' '._('and the input decimal places is ').' '.strlen(substr(strrchr($_SESSION['Adjustment'.$identifier]->Quantity, "."), 1)),'error');
		$InputError = true;
	} elseif ($_SESSION['Adjustment' . $identifier]->Quantity==0){
		echo prnMsg( _('The quantity entered cannot be zero') . '. ' . _('There would be no adjustment to make'),'error');
		$InputError = true;
	} elseif ($_SESSION['Adjustment' . $identifier]->Controlled==1 AND count($_SESSION['Adjustment' . $identifier]->SerialItems)==0) {
		echo prnMsg( _('The item entered is a controlled item that requires the detail of the serial numbers or batch references to be adjusted to be entered'),'error');
		$InputError = true;
	}

	if ($_SESSION['ProhibitNegativeStock']==1){
		$SQL = "SELECT quantity FROM locstock
				WHERE stockid='" . $_SESSION['Adjustment' . $identifier]->StockID . "'
				AND loccode='" . $_SESSION['Adjustment' . $identifier]->StockLocation . "'";
		$CheckNegResult=DB_query($SQL);
		$CheckNegRow = DB_fetch_array($CheckNegResult);
		if ($CheckNegRow['quantity']+$_SESSION['Adjustment' . $identifier]->Quantity <0){
			$InputError=true;
			echo prnMsg(_('The system parameters are set to prohibit negative stocks. Processing this stock adjustment would result in negative stock at this location. This adjustment will not be processed.'),'error');
		}
	}

	if (!$InputError) {

/*All inputs must be sensible so make the stock movement records and update the locations stocks */

		$AdjustmentNumber = GetNextTransNo(17);
		$PeriodNo = GetPeriod (Date($_SESSION['DefaultDateFormat']));
		$SQLAdjustmentDate = FormatDateForSQL(Date($_SESSION['DefaultDateFormat']));

		$Result = DB_Txn_Begin();

		// Need to get the current location quantity will need it later for the stock movement
		$SQL="SELECT locstock.quantity
			FROM locstock
			WHERE locstock.stockid='" . $_SESSION['Adjustment' . $identifier]->StockID . "'
			AND loccode= '" . $_SESSION['Adjustment' . $identifier]->StockLocation . "'";
		$Result = DB_query($SQL);
		if (DB_num_rows($Result)==1){
			$LocQtyRow = DB_fetch_row($Result);
			$QtyOnHandPrior = $LocQtyRow[0];
		} else {
			// There must actually be some error this should never happen
			$QtyOnHandPrior = 0;
		}
		$SQL = "INSERT INTO stockmoves (stockid,
										type,
										transno,
										loccode,
										trandate,
										userid,
										prd,
										reference,
										qty,
										newqoh,
										standardcost,
										narrative)
									VALUES ('" . $_SESSION['Adjustment' . $identifier]->StockID . "',
										17,
										'" . $AdjustmentNumber . "',
										'" . $_SESSION['Adjustment' . $identifier]->StockLocation . "',
										'" . $SQLAdjustmentDate . "',
										'" . $_SESSION['UserID'] . "',
										'" . $PeriodNo . "',
										'" . $_SESSION['Adjustment' . $identifier]->Narrative ."',
										'" . $_SESSION['Adjustment' . $identifier]->Quantity . "',
										'" . ($QtyOnHandPrior + $_SESSION['Adjustment' . $identifier]->Quantity) . "',
										'" . $_SESSION['Adjustment' . $identifier]->StandardCost . "',
										'')";

		$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock movement record cannot be inserted because');
		$DbgMsg =  _('The following SQL to insert the stock movement record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

/*Get the ID of the StockMove... */
		$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');

/*Insert the StockSerialMovements and update the StockSerialItems  for controlled items*/

		if ($_SESSION['Adjustment' . $identifier]->Controlled ==1){
			foreach($_SESSION['Adjustment' . $identifier]->SerialItems as $Item){
			/*We need to add or update the StockSerialItem record and
			The StockSerialMoves as well */

				/*First need to check if the serial items already exists or not */
				$SQL = "SELECT COUNT(*)
						FROM stockserialitems
						WHERE stockid='" . $_SESSION['Adjustment' . $identifier]->StockID . "'
						AND loccode='" . $_SESSION['Adjustment' . $identifier]->StockLocation . "'
						AND serialno='" . $Item->BundleRef . "'";
				$ErrMsg = _('Unable to determine if the serial item exists');
				$Result = DB_query($SQL,$ErrMsg);
				$SerialItemExistsRow = DB_fetch_row($Result);

				if ($SerialItemExistsRow[0]==1){

					$SQL = "UPDATE stockserialitems SET quantity= quantity + " . $Item->BundleQty . "
							WHERE stockid='" . $_SESSION['Adjustment' . $identifier]->StockID . "'
							AND loccode='" . $_SESSION['Adjustment' . $identifier]->StockLocation . "'
							AND serialno='" . $Item->BundleRef . "'";

					$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated because');
					$DbgMsg =  _('The following SQL to update the serial stock item record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				} else {
					/*Need to insert a new serial item record */
					$SQL = "INSERT INTO stockserialitems (stockid,
														loccode,
														serialno,
														qualitytext,
														quantity,
														expirationdate)
											VALUES ('" . $_SESSION['Adjustment' . $identifier]->StockID . "',
											'" . $_SESSION['Adjustment' . $identifier]->StockLocation . "',
											'" . $Item->BundleRef . "',
											'',
											'" . $Item->BundleQty . "',
											'" . FormatDateForSQL($Item->ExpiryDate) ."')";

					$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated because');
					$DbgMsg =  _('The following SQL to update the serial stock item record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				}


				/* now insert the serial stock movement */

				$SQL = "INSERT INTO stockserialmoves (stockmoveno,
													stockid,
													serialno,
													moveqty)
										VALUES ('" . $StkMoveNo . "',
											'" . $_SESSION['Adjustment' . $identifier]->StockID . "',
											'" . $Item->BundleRef . "',
											'" . $Item->BundleQty . "')";
				$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
				$DbgMsg =  _('The following SQL to insert the serial stock movement records was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			}/* foreach controlled item in the serialitems array */
		} /*end if the adjustment item is a controlled item */



		$SQL = "UPDATE locstock SET quantity = quantity + " . floatval($_SESSION['Adjustment' . $identifier]->Quantity) . "
				WHERE stockid='" . $_SESSION['Adjustment' . $identifier]->StockID . "'
				AND loccode='" . $_SESSION['Adjustment' . $identifier]->StockLocation . "'";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('The location stock record could not be updated because');
		$DbgMsg = _('The following SQL to update the stock record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $_SESSION['Adjustment' . $identifier]->StandardCost > 0){

			$StockGLCodes = GetStockGLCode($_SESSION['Adjustment' . $identifier]->StockID);

			$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										amount,
										narrative,
										tag)
								VALUES (17,
									'" .$AdjustmentNumber . "',
									'" . $SQLAdjustmentDate . "',
									'" . $PeriodNo . "',
									'" .  $StockGLCodes['adjglact'] . "',
									'" . round($_SESSION['Adjustment' . $identifier]->StandardCost * -($_SESSION['Adjustment' . $identifier]->Quantity), $_SESSION['CompanyRecord']['decimalplaces']) . "',
									'" . $_SESSION['Adjustment' . $identifier]->StockID . " x " . $_SESSION['Adjustment' . $identifier]->Quantity . " @ " .
										$_SESSION['Adjustment' . $identifier]->StandardCost . " " . $_SESSION['Adjustment' . $identifier]->Narrative . "',
									'" . $_SESSION['Adjustment' . $identifier]->tag . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The general ledger transaction entries could not be added because');
			$DbgMsg = _('The following SQL to insert the GL entries was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										amount,
										narrative,
										tag)
								VALUES (17,
									'" .$AdjustmentNumber . "',
									'" . $SQLAdjustmentDate . "',
									'" . $PeriodNo . "',
									'" .  $StockGLCodes['stockact'] . "',
									'" . round($_SESSION['Adjustment' . $identifier]->StandardCost * $_SESSION['Adjustment' . $identifier]->Quantity,$_SESSION['CompanyRecord']['decimalplaces']) . "',
									'" . $_SESSION['Adjustment' . $identifier]->StockID . ' x ' . $_SESSION['Adjustment' . $identifier]->Quantity . ' @ ' . $_SESSION['Adjustment' . $identifier]->StandardCost . ' ' . $_SESSION['Adjustment' . $identifier]->Narrative . "',
									'" . $_SESSION['Adjustment' . $identifier]->tag . "'
									)";

			$Errmsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The general ledger transaction entries could not be added because');
			$DbgMsg = _('The following SQL to insert the GL entries was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg,true);
		}

		EnsureGLEntriesBalance(17, $AdjustmentNumber);

		$Result = DB_Txn_Commit();
		$AdjustReason = $_SESSION['Adjustment' . $identifier]->Narrative?  _('Narrative') . ' ' . $_SESSION['Adjustment' . $identifier]->Narrative:'';
		$ConfirmationText = _('A stock adjustment for'). ' ' . $_SESSION['Adjustment' . $identifier]->StockID . ' -  ' . $_SESSION['Adjustment' . $identifier]->ItemDescription . ' '._('has been created from location').' ' . $_SESSION['Adjustment' . $identifier]->StockLocation .' '. _('for a quantity of') . ' ' . locale_number_format($_SESSION['Adjustment' . $identifier]->Quantity,$_SESSION['Adjustment' . $identifier]->DecimalPlaces) . ' ' . $AdjustReason;
		echo prnMsg( $ConfirmationText,'success');

		if ($_SESSION['InventoryManagerEmail']!=''){
			$ConfirmationText = $ConfirmationText . ' ' . _('by user') . ' ' . $_SESSION['UserID'] . ' ' . _('at') . ' ' . Date('Y-m-d H:i:s');
			$EmailSubject = _('Stock adjustment for'). ' ' . $_SESSION['Adjustment' . $identifier]->StockID;
			if($_SESSION['SmtpSetting']==0){
			      mail($_SESSION['InventoryManagerEmail'],$EmailSubject,$ConfirmationText);
			}else{
				include('includes/htmlMimeMail.php');
				$mail = new htmlMimeMail();
				$mail->setSubject($EmailSubject);
				$mail->setText($ConfirmationText);
				$result = SendmailBySmtp($mail,array($_SESSION['InventoryManagerEmail']));
			}

		}
		$StockID = $_SESSION['Adjustment' . $identifier]->StockID;
		unset ($_SESSION['Adjustment' . $identifier]);
	} /* end if there was no input error */

}/* end if the user hit enter the adjustment */

echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="'. htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier.'" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_SESSION['Adjustment' . $identifier])) {
	$StockID='';
	$Controlled= 0;
	$Quantity = 0;
	$DecimalPlaces =2;
} else {
	$StockID = $_SESSION['Adjustment' . $identifier]->StockID;
	$Controlled = $_SESSION['Adjustment' . $identifier]->Controlled;
	$Quantity = $_SESSION['Adjustment' . $identifier]->Quantity;
	$sql="SELECT materialcost,
				labourcost,
				overheadcost,
				units,
				decimalplaces
			FROM stockmaster
			WHERE stockid='".$StockID."'";

	$result=DB_query($sql);
	$myrow=DB_fetch_array($result);
	$_SESSION['Adjustment' . $identifier]->PartUnit=$myrow['units'];
	$_SESSION['Adjustment' . $identifier]->StandardCost=$myrow['materialcost']+$myrow['labourcost']+$myrow['overheadcost'];
	$DecimalPlaces = $myrow['decimalplaces'];
}
echo '<br />
	<div class="block">
	<div class="block-title">
		<h3>' . _('Adjustment Details') . '</h3></div>
	';
if (!isset($_GET['Description'])) {
	$_GET['Description']='';
}
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' .  _('Stock ID'). '</label>';
if (isset($StockID)) {
	echo '<input type="text" name="StockID" class="form-control" size="21" value="' . $StockID . '" maxlength="20" /></div></div>';
} else {
	echo '<input type="text" name="StockID" class="form-control" size="21" value="" maxlength="20" /></div></div>';
}
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' .  _('Description-part or full'). '</label>
		<input type="text" name="StockText" class="form-control" size="21" value="' . $_GET['Description'] .'" /></div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">'._('Stock ID-part or full'). '</label>
		';
if (isset($StockID)) {
	echo '<input type="text" class="form-control" name="StockCode" size="21" value="' . $StockID .'" maxlength="20" />';
} else {
	echo '<input type="text" class="form-control" name="StockCode" size="21" value="" maxlength="20" />';
}
echo '</div>
</div>
</div>
		<div class="row">
		<div class="col-xs-4">

		<input type="submit" class="btn btn-success" name="CheckCode" value="'._('Search').'" /></div>
	</div>';
if (isset($_SESSION['Adjustment' . $identifier]) AND mb_strlen($_SESSION['Adjustment' . $identifier]->ItemDescription)>1){
	echo '<h3 class="sub-header">' . $_SESSION['Adjustment' . $identifier]->ItemDescription . ' ('._('In Units of').' ' . $_SESSION['Adjustment' . $identifier]->PartUnit . ' ) - ' . _('Unit Cost').' = ' . locale_number_format($_SESSION['Adjustment' . $identifier]->StandardCost,4) . '</h3>
		';
}

echo '<br /><div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">'. _('Adjustment to Stock At Location').'</label>
		<select name="StockLocation" onchange="submit();" class="form-control"> ';
foreach ($LocationList as $Loccode=>$Locationname){
	if (isset($_SESSION['Adjustment'.$identifier]->StockLocation) AND $Loccode == $_SESSION['Adjustment' . $identifier]->StockLocation){
		 echo '<option selected="selected" value="' . $Loccode . '">' . $Locationname . '</option>';
	} else {
		 echo '<option value="' . $Loccode . '">' . $Locationname . '</option>';
	}
}

echo '</select></div></div>';
if (isset($_SESSION['Adjustment' . $identifier]) AND !isset($_SESSION['Adjustment' . $identifier]->Narrative)) {
	$_SESSION['Adjustment' . $identifier]->Narrative = '';
	$Narrative ='';
} elseif(isset($_SESSION['Adjustment'.$identifier]->Narrative)) {
	$Narrative = $_SESSION['Adjustment'.$identifier]->Narrative;
} else {
	$Narrative ='';
}

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' .  _('Comments On Why').'</label>
		<input type="text" class="form-control" name="Narrative" size="32" onchange="submit()" maxlength="100" value="' . $Narrative . '" /></div>
	</div>';

echo '';


if ($Controlled==1){
		if ($_SESSION['Adjustment' . $identifier]->StockLocation == ''){
			$_SESSION['Adjustment' . $identifier]->StockLocation = $_SESSION['UserStockLocation'];
		}
		echo '<div class="col-xs-4"><strong>' . _('Adjustment Quantity ').' </strong>
<input type="hidden" name="Quantity" value="' . $_SESSION['Adjustment' . $identifier]->Quantity . '" />
				<span style="margin-left:50px;">'.locale_number_format($_SESSION['Adjustment' . $identifier]->Quantity,$DecimalPlaces) .' </span>
				<br /><div class="col-xs-3" style="padding-top:9px;"><a href="'.$RootPath.'/StockAdjustmentsControlled.php?AdjType=REMOVE&identifier='.$identifier.'" class="btn btn-danger">' . _('Remove') . '</a></div>
				<div class="col-xs-1" style="padding-top:9px;"><a href="'.$RootPath.'/StockAdjustmentsControlled.php?AdjType=ADD&identifier='.$identifier.'" class="btn btn-success">' . _('Add') . '</a></div></div>';
} else {
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Adjustment Quantity').'</label><input type="text" class="form-control" name="Quantity" size="12" maxlength="12" value="' . locale_number_format($Quantity,$DecimalPlaces) . '" /></div></div>';
}
echo '</div>';
	//Select the tag
echo '<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Tag') . '</label>
		<select name="tag" class="form-control">';

$SQL = "SELECT tagref,
				tagdescription
		FROM tags
		ORDER BY tagref";

$result=DB_query($SQL);
echo '<option value="0">0 - ' . _('None') . '</option>';
while ($myrow=DB_fetch_array($result)){
	if (isset($_SESSION['Adjustment' . $identifier]->tag) AND $_SESSION['Adjustment' . $identifier]->tag==$myrow['tagref']){
		echo '<option selected="selected" value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription']. '</option>';
	}
}
echo '</select></div></div>';
// End select tag

echo '
	<div class="col-xs-4">
<div class="form-group"><br />
	<input type="submit" name="EnterAdjustment" value="'. _('Submit'). '" class="btn btn-success" />
	</div></div></div><br />';

if (!isset($_POST['StockLocation'])) {
	$_POST['StockLocation']='';
}

echo '<div class="row">
		
<div class="col-xs-2">	<a href="'. $RootPath. '/StockStatus.php?StockID='. $StockID . '" class="btn btn-info">' . _('Stock Status') . '</a>';
echo '</div>
<div class="col-xs-2">	<a href="'.$RootPath.'/StockMovements.php?StockID=' . $StockID . '" class="btn btn-info">' . _('Stock Movements') . '</a>';
echo '</div>
<div class="col-xs-2">	<a href="'.$RootPath.'/StockUsage.php?StockID=' . $StockID . '&amp;StockLocation=' . $_POST['StockLocation'] . '" class="btn btn-info">' . _('Stock Usage') . '</a>';
echo '</div>
<div class="col-xs-3">	<a href="'.$RootPath.'/SelectSalesOrder.php?SelectedStockItem='. $StockID .'&amp;StockLocation=' . $_POST['StockLocation'] . '" class="btn btn-info">' .  _('Open Sales Orders') . '</a>';
echo '</div>
<div class="col-xs-3">	<a href="'.$RootPath.'/SelectCompletedOrder.php?SelectedStockItem=' . $StockID .'" class="btn btn-info">' . _('Completed Sales Orders') . '</a>';

echo '</div>
      </div><br /></div>
      </form></div></div>';
include('includes/footer.php');
?>
