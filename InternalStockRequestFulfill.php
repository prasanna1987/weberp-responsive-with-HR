<?php


include('includes/session.php');

$Title = _('Fulfill Stock Requests');
$ViewTopic = 'Inventory';
$BookMark = 'FulfilRequest';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Fulfill Stock Requests') . '</h1></a></div>';

if (isset($_POST['UpdateAll'])) {
	foreach ($_POST as $key => $value) {
		if (mb_strpos($key,'Qty')) {
			$RequestID = mb_substr($key,0, mb_strpos($key,'Qty'));
			$LineID = mb_substr($key,mb_strpos($key,'Qty')+3);
			$Quantity = filter_number_format($_POST[$RequestID.'Qty'.$LineID]);
			$StockID = $_POST[$RequestID.'StockID'.$LineID];
			$Location = $_POST[$RequestID.'Location'.$LineID];
			$Department = $_POST[$RequestID.'Department'.$LineID];
			$Tag = $_POST[$RequestID.'Tag'.$LineID];
			$RequestedQuantity = filter_number_format($_POST[$RequestID.'RequestedQuantity'.$LineID]);
			$Controlled = $_POST[$RequestID.'Controlled'.$LineID];
			$SerialNo = $_POST[$RequestID.'Ser'.$LineID];
			if (isset($_POST[$RequestID.'Completed'.$LineID])) {
				$Completed=True;
			} else {
				$Completed=False;
			}

			$sql="SELECT materialcost, labourcost, overheadcost, decimalplaces FROM stockmaster WHERE stockid='".$StockID."'";
			$result=DB_query($sql);
			$myrow=DB_fetch_array($result);
			$StandardCost=$myrow['materialcost']+$myrow['labourcost']+$myrow['overheadcost'];
			$DecimalPlaces = $myrow['decimalplaces'];

			$Narrative = _('Issue') . ' ' . $Quantity . ' ' . _('of') . ' '. $StockID . ' ' . _('to department') . ' ' . $Department . ' ' . _('from') . ' ' . $Location ;

			$AdjustmentNumber = GetNextTransNo(17);
			$PeriodNo = GetPeriod (Date($_SESSION['DefaultDateFormat']));
			$SQLAdjustmentDate = FormatDateForSQL(Date($_SESSION['DefaultDateFormat']));

			$Result = DB_Txn_Begin();

			// Need to get the current location quantity will need it later for the stock movement
			$SQL="SELECT locstock.quantity
					FROM locstock
					WHERE locstock.stockid='" . $StockID . "'
						AND loccode= '" . $Location . "'";
			$Result = DB_query($SQL);
			if (DB_num_rows($Result)==1){
				$LocQtyRow = DB_fetch_row($Result);
				$QtyOnHandPrior = $LocQtyRow[0];
			} else {
				// There must actually be some error this should never happen
				$QtyOnHandPrior = 0;
			}

			if ($_SESSION['ProhibitNegativeStock']==0 OR ($_SESSION['ProhibitNegativeStock']==1 AND $QtyOnHandPrior >= $Quantity)) {

				$SQL = "INSERT INTO stockmoves (
									stockid,
									type,
									transno,
									loccode,
									trandate,
									userid,
									prd,
									reference,
									qty,
									newqoh)
								VALUES (
									'" . $StockID . "',
									17,
									'" . $AdjustmentNumber . "',
									'" . $Location . "',
									'" . $SQLAdjustmentDate . "',
									'" . $_SESSION['UserID'] . "',
									'" . $PeriodNo . "',
									'" . $Narrative ."',
									'" . -$Quantity . "',
									'" . ($QtyOnHandPrior - $Quantity) . "'
								)";


				$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock movement record cannot be inserted because');
				$DbgMsg =  _('The following SQL to insert the stock movement record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);


				/*Get the ID of the StockMove... */
				$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');


				if ($Controlled == 1) {
					/*We need to add the StockSerialItem record and the StockSerialMoves as well */

					$SQL = "UPDATE stockserialitems	SET quantity= quantity - " . $Quantity . "
							WHERE stockid='" . $StockID . "'
							AND loccode='" . $Location . "'
							AND serialno='" . $SerialNo . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated because');
					$DbgMsg = _('The following SQL to update the serial stock item record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					/* now insert the serial stock movement */

					$SQL = "INSERT INTO stockserialmoves (stockmoveno,
											stockid,
											serialno,
											moveqty)
									VALUES ('" . $StkMoveNo . "',
											'" . $StockID . "',
											'" . $SerialNo . "',
											'" . -$Quantity . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
					$DbgMsg = _('The following SQL to insert the serial stock movement records was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				} /*end if the orderline is a controlled item */


				$SQL="UPDATE stockrequestitems
						SET qtydelivered=qtydelivered+" . $Quantity . "
						WHERE dispatchid='" . $RequestID . "'
							AND dispatchitemsid='" . $LineID . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('The location stock record could not be updated because');
				$DbgMsg = _('The following SQL to update the stock record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg,true);

				$SQL = "UPDATE locstock SET quantity = quantity - '" . $Quantity . "'
									WHERE stockid='" . $StockID . "'
										AND loccode='" . $Location . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('The location stock record could not be updated because');
				$DbgMsg = _('The following SQL to update the stock record was used');

				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $StandardCost > 0){

					$StockGLCodes = GetStockGLCode($StockID);

					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												amount,
												narrative,
												tag)
											VALUES (17,
												'"  .$AdjustmentNumber . "',
												'" . $SQLAdjustmentDate . "',
												'" . $PeriodNo . "',
												'" . $StockGLCodes['issueglact'] . "',
												'" . $StandardCost * ($Quantity) . "',
												'" . $Narrative . "',
												'" . $Tag . "'
											)";

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
												'" . $AdjustmentNumber . "',
												'" . $SQLAdjustmentDate . "',
												'" . $PeriodNo . "',
												'" . $StockGLCodes['stockact'] . "',
												'" . $StandardCost * -$Quantity . "',
												'" . $Narrative . "',
												'" . $Tag . "'
											)";

					$Errmsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The general ledger transaction entries could not be added because');
					$DbgMsg = _('The following SQL to insert the GL entries was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg,true);
				}

				if (($Quantity >= $RequestedQuantity) OR $Completed==True) {
					$SQL="UPDATE stockrequestitems
								SET completed=1
							WHERE dispatchid='".$RequestID."'
								AND dispatchitemsid='".$LineID."'";
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg,true);
				}

				$Result = DB_Txn_Commit();

				$ConfirmationText = _('An internal stock request for'). ' ' . $StockID . ' ' . _('has been fulfilled from location').' ' . $Location .' '. _('for a quantity of') . ' ' . locale_number_format($Quantity, $DecimalPlaces ) ;
				echo prnMsg( $ConfirmationText,'success');

				if ($_SESSION['InventoryManagerEmail']!=''){
					$ConfirmationText = $ConfirmationText . ' ' . _('by user') . ' ' . $_SESSION['UserID'] . ' ' . _('at') . ' ' . Date('Y-m-d H:i:s');
					$EmailSubject = _('Internal Stock Request Fulfillment for'). ' ' . $StockID;
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
			} else {
				$ConfirmationText = _('An internal stock request for'). ' ' . $StockID . ' ' . _('has been fulfilled from location').' ' . $Location .' '. _('for a quantity of') . ' ' . locale_number_format($Quantity, $DecimalPlaces) . ' ' . _('cannot be created as there is insufficient stock and your system is configured to not allow negative stocks');
				echo prnMsg( $ConfirmationText,'warn');
			}

			// Check if request can be closed and close if done.
			if (isset($RequestID)) {
				$SQL="SELECT dispatchid
						FROM stockrequestitems
						WHERE dispatchid='".$RequestID."'
							AND completed=0";
				$Result=DB_query($SQL);
				if (DB_num_rows($Result)==0) {
					$SQL="UPDATE stockrequest
						SET closed=1
					WHERE dispatchid='".$RequestID."'";
					$Result=DB_query($SQL);
				}
			}
		}
	}
}

if (!isset($_POST['Location'])) {
	echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
    
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Location to issue stock from') . '</label>
				<select name="Location" class="form-control">';
	$sql = "SELECT locations.loccode, locationname
			FROM locations
			INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
			WHERE internalrequest = 1
			ORDER BY locationname";
	$resultStkLocs = DB_query($sql);
	while ($myrow=DB_fetch_array($resultStkLocs)){
		if (isset($_SESSION['Adjustment']->StockLocation)){
			if ($myrow['loccode'] == $_SESSION['Adjustment']->StockLocation){
				echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			} else {
				echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			}
		} elseif ($myrow['loccode']==$_SESSION['UserStockLocation']){
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			$_POST['StockLocation']=$myrow['loccode'];
		} else {
		 echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		}
	}
	echo '</select></div></div>';
	
	echo '<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" class="btn btn-info" name="EnterAdjustment" value="'. _('Submit'). '" /></div>';
    echo '</div>
	</div>
          </form></div></div>';
	include('includes/footer.php');
	exit;
}

/* Retrieve the requisition header information
 */
if (isset($_POST['Location'])) {
	$sql="SELECT stockrequest.dispatchid,
			locations.locationname,
			stockrequest.despatchdate,
			stockrequest.narrative,
			departments.description,
			www_users.realname,
			www_users.email
		FROM stockrequest
		LEFT JOIN departments
			ON stockrequest.departmentid=departments.departmentid
		LEFT JOIN locations
			ON stockrequest.loccode=locations.loccode
		LEFT JOIN www_users
			ON www_users.userid=departments.authoriser
	WHERE stockrequest.authorised=1
		AND stockrequest.closed=0
		AND stockrequest.loccode='".$_POST['Location']."'";
	$result=DB_query($sql);

	if (DB_num_rows($result)==0) {
		echo prnMsg( _('There are no outstanding authorised requests for this location'), 'info');
		echo '<br />';
		echo '<div class="row" align="center"><div><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn  btn-default">' . _('Select another location') . '</a></div></div><br>';
		include('includes/footer.php');
		exit;
	}

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
			<tr>
				<th>' . _('Request Number') . '</th>
				<th>' . _('Department') . '</th>
				<th>' . _('Location Of Stock') . '</th>
				<th>' . _('Requested Date') . '</th>
				<th>' . _('Narrative') . '</th>
			</tr></thead>';

	while ($myrow=DB_fetch_array($result)) {

		echo '<tr>
				<td>' . $myrow['dispatchid'] . '</td>
				<td>' . $myrow['description'] . '</td>
				<td>' . $myrow['locationname'] . '</td>
				<td class="centre">' . ConvertSQLDate($myrow['despatchdate']) . '</td>
				<td>' . $myrow['narrative'] . '</td>
			</tr>';
		$LineSQL="SELECT stockrequestitems.dispatchitemsid,
						stockrequestitems.dispatchid,
						stockrequestitems.stockid,
						stockrequestitems.decimalplaces,
						stockrequestitems.uom,
						stockmaster.description,
						stockrequestitems.quantity,
						stockrequestitems.qtydelivered,
						stockmaster.controlled
				FROM stockrequestitems
				LEFT JOIN stockmaster
				ON stockmaster.stockid=stockrequestitems.stockid
			WHERE dispatchid='".$myrow['dispatchid'] . "'
				AND completed=0";
		$LineResult=DB_query($LineSQL);

		echo '<tr>
				<td></td>
				<td colspan="5" align="left">
					<div class="table-responsive">
<table id="general-table" class="table table-bordered">
					<thead>
					<tr>
						<th>' . _('Product') . '</th>
						<th>' . _('Requested') . '</th>
						<th>' . _('Deliver') . '</th>
						<th>' . _('Units') . '</th>
						<th>' . _('Lot/Batch/Serial') . '</th>
						<th>' . _('Completed') . '</th>
						<th>' . _('GL Tag') . '</th>
					</tr></thead>';

		while ($LineRow=DB_fetch_array($LineResult)) {
			echo '<tr>
					<td>' . $LineRow['description'] . '</td>
					<td class="number">' . locale_number_format($LineRow['quantity']-$LineRow['qtydelivered'],$LineRow['decimalplaces']) . '</td>
					<td class="number"><input type="text" class="form-control" name="'. $LineRow['dispatchid'] . 'Qty' . $LineRow['dispatchitemsid'] . '" value="'.locale_number_format($LineRow['quantity']-$LineRow['qtydelivered'],$LineRow['decimalplaces']).'" size="11" maxlength="10" /></td>
					<td>' . $LineRow['uom'] . '</td>';
					if ($LineRow['controlled'] == 1) {
						echo '<td class="number"><input type="text" class="form-control" name="'. $LineRow['dispatchid'] . 'Ser' . $LineRow['dispatchitemsid'] .'" size="21" maxlength="30" /></td>';
					} else {
						echo '<td>' . _('Stock item is not controlled') . '</td>';
					}
					echo '<td class="centre"><input type="checkbox" name="'. $LineRow['dispatchid'] . 'Completed'. $LineRow['dispatchitemsid'] . '" /></td>
					<td><select name="'. $LineRow['dispatchid'] . 'Tag'. $LineRow['dispatchitemsid'] . '" class="form-control">';

			$SQL = "SELECT tagref,
							tagdescription
						FROM tags
						ORDER BY tagref";

			$TagResult=DB_query($SQL);
			echo '<option value=0>0 - None</option>';
			while ($mytagrow=DB_fetch_array($TagResult)){
				if (isset($_SESSION['Adjustment']->tag) and $_SESSION['Adjustment']->tag==$mytagrow['tagref']){
					echo '<option selected="selected" value="' . $mytagrow['tagref'] . '">' . $mytagrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
				} else {
					echo '<option value="' . $mytagrow['tagref'] . '">' . $mytagrow['tagref'].' - ' .$mytagrow['tagdescription'] . '</option>';
				}
			}
			echo '</select></td>';
// End select tag
			echo '</tr>';
			echo '<input type="hidden" class="number" name="'. $LineRow['dispatchid'] . 'StockID'. $LineRow['dispatchitemsid'] . '" value="'.$LineRow['stockid'].'" />';
			echo '<input type="hidden" class="number" name="'. $LineRow['dispatchid'] . 'Location'. $LineRow['dispatchitemsid'] . '" value="'.$_POST['Location'].'" />';
			echo '<input type="hidden" class="number" name="'. $LineRow['dispatchid'] . 'RequestedQuantity'. $LineRow['dispatchitemsid'] . '" value="'.locale_number_format($LineRow['quantity']-$LineRow['qtydelivered'],$LineRow['decimalplaces']).'" />';
			echo '<input type="hidden" class="number" name="'. $LineRow['dispatchid'] . 'Department'. $LineRow['dispatchitemsid'] . '" value="'.$myrow['description'].'" />';
			echo '<input type="hidden" class="number" name="'. $LineRow['dispatchid'] . 'Controlled'. $LineRow['dispatchitemsid'] . '" value="'.$LineRow['controlled'].'" />';
		} // end while order line detail
		echo '</table></div></td></tr>';
	} //end while header loop
	echo '</table></div></div></div>';
	echo '<div class="row" align="center"><div><input type="submit" class="btn btn-info" name="UpdateAll" value="' . _('Update'). '" /></div>
          </div><br />
          </form>';
}

include('includes/footer.php');

?>
