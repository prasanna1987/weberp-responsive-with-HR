<?php


include('includes/session.php');

$Title = _('Multi-Level Bill Of Materials Maintenance');

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

function display_children($Parent, $Level, &$BOMTree) {

	global $i;

	// retrive all children of parent
	$c_result = DB_query("SELECT parent,
						component,
						sequence/pow(10,digitals)
							AS sequence
						FROM bom
						WHERE parent='" . $Parent. "'
						ORDER BY sequence ASC");
	if (DB_num_rows($c_result) > 0) {

		while ($row = DB_fetch_array($c_result)) {
			//echo '<br />Parent: ' . $Parent . ' Level: ' . $Level . ' row[component]: ' . $row['component']  . '<br />';
			if ($Parent != $row['component']) {
				// indent and display the title of this child
				$BOMTree[$i]['Level'] = $Level; 		// Level
				if ($Level > 15) {
					echo prnMsg(_('A maximum of 15 levels of bill of materials only can be displayed'),'error');
					exit;
				}
				$BOMTree[$i]['Parent'] = $Parent;		// Assemble
				$BOMTree[$i]['Component'] = $row['component'];	// Component
				// call this function again to display this
				// child's children
				$i++;
				display_children($row['component'], $Level + 1, $BOMTree);
			} else {
				echo prnMsg(_('The component and the parent is the same'),'error');
				echo $row['component'] . '<br/>';
				include('includes/footer.php');
				exit;
			}
		}
	}
}


function CheckForRecursiveBOM ($UltimateParent, $ComponentToCheck) {

/* returns true ie 1 if the BOM contains the parent part as a component
ie the BOM is recursive otherwise false ie 0 */

	$sql = "SELECT component FROM bom WHERE parent='".$ComponentToCheck."'";
	$ErrMsg = _('An error occurred in retrieving the components of the BOM during the check for recursion');
	$DbgMsg = _('The SQL that was used to retrieve the components of the BOM and that failed in the process was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);

	if (DB_num_rows($result)!=0) {
		while ($myrow=DB_fetch_array($result)){
			if ($myrow['component']==$UltimateParent){
				return 1;
			}
			if (CheckForRecursiveBOM($UltimateParent, $myrow['component'])){
				return 1;
			}
		} //(while loop)
	} //end if $result is true

	return 0;

} //end of function CheckForRecursiveBOM

function DisplayBOMItems($UltimateParent, $Parent, $Component,$Level) {

		global $ParentMBflag;
		$sql = "SELECT bom.sequence,
						bom.digitals,
						bom.component,
						stockcategory.categorydescription,
						stockmaster.description as itemdescription,
						stockmaster.units,
						locations.locationname,
						locations.loccode,
						workcentres.description as workcentrename,
						workcentres.code as workcentrecode,
						bom.quantity,
						bom.effectiveafter,
						bom.effectiveto,
						stockmaster.mbflag,
						bom.autoissue,
						bom.remark,
						stockmaster.controlled,
						locstock.quantity AS qoh,
						stockmaster.decimalplaces
				FROM bom INNER JOIN stockmaster
				ON bom.component=stockmaster.stockid
				INNER JOIN stockcategory
				ON stockcategory.categoryid = stockmaster.categoryid
				INNER JOIN locations ON
				bom.loccode = locations.loccode
				INNER JOIN workcentres
				ON bom.workcentreadded=workcentres.code
				INNER JOIN locstock
				ON bom.loccode=locstock.loccode
				AND bom.component = locstock.stockid
				INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
				WHERE bom.component='".$Component."'
				AND bom.parent = '".$Parent."'";

		$ErrMsg = _('Could not retrieve the BOM components because');
		$DbgMsg = _('The SQL used to retrieve the components was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);

		//echo $TableHeader;
		$RowCounter =0;

		while ($myrow=DB_fetch_array($result)) {

			$Level1 = str_repeat('-&nbsp;',$Level-1).$Level;
			if( $myrow['mbflag']=='B'
				OR $myrow['mbflag']=='K'
				OR $myrow['mbflag']=='D') {

				$DrillText = '%s%s';
				$DrillLink = '<div class="row">' . _('No lower levels') . '</div>';
				$DrillID='';
			} else {
				$DrillText = '<a href="%s&amp;Select=%s" class="btn btn-info">' . _('Drill <br>Down<br/>') . '</a>';
				$DrillLink = htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?';
				$DrillID=$myrow['component'];
			}
			if ($ParentMBflag!='M' AND $ParentMBflag!='G'){
				$AutoIssue = _('N/A');
			} elseif ($myrow['controlled']==0 AND $myrow['autoissue']==1){//autoissue and not controlled
				$AutoIssue = _('Yes');
			} elseif ($myrow['controlled']==1) {
				$AutoIssue = _('No');
			} else {
				$AutoIssue = _('N/A');
			}

			if ($myrow['mbflag']=='D' //dummy orservice
				OR $myrow['mbflag']=='K' //kit-set
				OR $myrow['mbflag']=='A'  // assembly
				OR $myrow['mbflag']=='G') /* ghost */ {

				$QuantityOnHand = _('N/A');
			} else {
				$QuantityOnHand = locale_number_format($myrow['qoh'],$myrow['decimalplaces']);
			}
			$TextIndent= $Level . 'em';
			if (!empty($myrow['remark'])) {
				$myrow['remark'] = ' **' . ' ' . $myrow['remark'];
			}
			$StockID = $myrow['component'];
		if (function_exists('imagecreatefromjpeg')){
			if ($_SESSION['ShowStockidOnImages'] == '0'){
				$StockImgLink = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
								'&amp;StockID='.urlencode($StockID).
								'&amp;text='.
								'&amp;width=100'.
								'&amp;eight=100'.
								'" alt="" />';
			} else {
				$StockImgLink = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
								'&amp;StockID='.urlencode($StockID).
								'&amp;text='. $StockID .
								'&amp;width=100'.
								'&amp;height=100'.
								'" alt="" />';
			}
		} else {
			if( isset($StockID) AND file_exists($_SESSION['part_pics_dir'] . '/' .$StockID.'.jpg') ) {
				$StockImgLink = '<img src="' . $_SESSION['part_pics_dir'] . '/' . $StockID . '.jpg" height="100" width="100" />';
			} else {
				$StockImgLink = _('No Image');
			}
		}

			printf('<td class="number">%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td class="noprint">%s</td>
					<td class="noprint">%s</td>
					<td class="noprint">%s</td>
					<td class="number noprint">%s</td>
					<td class="noprint"><a href="%s&amp;Select=%s&amp;SelectedComponent=%s" class="btn btn-info">' . _('Edit') . '</a></td>
					<td class="noprint">' . $DrillText . '</td>
					 <td class="noprint"><a href="%s&amp;Select=%s&amp;SelectedComponent=%s&amp;delete=1&amp;ReSelect=%s&amp;Location=%s&amp;WorkCentre=%s" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this component from the bill of material?') . '\');">' . _('Delete') . '</a></td>
					 </tr>
					',
					$Level1,
					locale_number_format($myrow['sequence']/pow(10,$myrow['digitals']),'Variable'),
					$myrow['categorydescription'],
					$myrow['component'],
					$myrow['itemdescription'],
					$myrow['locationname'],
					$myrow['workcentrename'],
					locale_number_format($myrow['quantity'],'Variable'),
					$myrow['units'],
					ConvertSQLDate($myrow['effectiveafter']),
					ConvertSQLDate($myrow['effectiveto']),
					$AutoIssue,
					$QuantityOnHand,
					htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
					$Parent,
					$myrow['component'],
					$DrillLink,
					$DrillID,
					htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
					$Parent,
					$myrow['component'],
					$UltimateParent,
					$myrow['loccode'],
					$myrow['workcentrecode'],
					$myrow['remark']
					//$StockImgLink
					);

		} //END WHILE LIST LOOP
} //end of function DisplayBOMItems

//---------------------------------------------------------------------------------

/* SelectedParent could come from a post or a get */
if (isset($_GET['SelectedParent'])){
	$SelectedParent = $_GET['SelectedParent'];
}else if (isset($_POST['SelectedParent'])){
	$SelectedParent = $_POST['SelectedParent'];
}



/* SelectedComponent could also come from a post or a get */
if (isset($_GET['SelectedComponent'])){
	$SelectedComponent = $_GET['SelectedComponent'];
} elseif (isset($_POST['SelectedComponent'])){
	$SelectedComponent = $_POST['SelectedComponent'];
}

/* delete function requires Location to be set */
if (isset($_GET['Location'])){
	$Location = $_GET['Location'];
} elseif (isset($_POST['Location'])){
	$Location = $_POST['Location'];
}

/* delete function requires WorkCentre to be set */
if (isset($_GET['WorkCentre'])){
	$WorkCentre = $_GET['WorkCentre'];
} elseif (isset($_POST['WorkCentre'])){
	$WorkCentre = $_POST['WorkCentre'];
}

if (isset($_GET['Select'])){
	$Select = $_GET['Select'];
} elseif (isset($_POST['Select'])){
	$Select = $_POST['Select'];
}


$msg='';

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();
$InputError = 0;

if (isset($Select)) { //Parent Stock Item selected so display BOM or edit Component
	$SelectedParent = $Select;
	unset($Select);// = NULL;
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

	if (isset($SelectedParent) AND isset($_POST['Submit'])) {

		//editing a component need to do some validation of inputs

		$i = 1;

		if (!Is_Date($_POST['EffectiveAfter'])) {
			$InputError = 1;
			echo prnMsg(_('The effective from date field must be a date in the format') . ' ' .$_SESSION['DefaultDateFormat'],'error');
			$Errors[$i] = 'EffectiveAfter';
			$i++;
		}
		if (!Is_Date($_POST['EffectiveTo'])) {
			$InputError = 1;
			echo prnMsg(_('The effective to date field must be a date in the format')  . ' ' .$_SESSION['DefaultDateFormat'],'error');
			$Errors[$i] = 'EffectiveTo';
			$i++;
		}
		if (!is_numeric(filter_number_format($_POST['Quantity']))) {
			$InputError = 1;
			echo prnMsg(_('The quantity entered must be numeric'),'error');
			$Errors[$i] = 'Quantity';
			$i++;
		}
		/* Comment this out to make substittute material can be recorded in the BOM
		if (filter_number_format($_POST['Quantity'])==0) {
			$InputError = 1;
			prnMsg(_('The quantity entered cannot be zero'),'error');
			$Errors[$i] = 'Quantity';
			$i++;
		}
		 */
		if(!Date1GreaterThanDate2($_POST['EffectiveTo'], $_POST['EffectiveAfter'])){
			$InputError = 1;
			echo prnMsg(_('The effective to date must be a date after the effective from date') . '<br />' . _('The effective to date is') . ' ' . DateDiff($_POST['EffectiveTo'], $_POST['EffectiveAfter'], 'd') . ' ' . _('days before the effective after date') . '! ' . _('No updates have been performed') . '.<br />' . _('Effective after was') . ': ' . $_POST['EffectiveAfter'] . ' ' . _('and effective to was') . ': ' . $_POST['EffectiveTo'],'error');
			$Errors[$i] = 'EffectiveAfter';
			$i++;
			$Errors[$i] = 'EffectiveTo';
			$i++;
		}
		if($_POST['AutoIssue']==1 AND isset($_POST['Component'])){
			$sql = "SELECT controlled FROM stockmaster WHERE stockid='" . $_POST['Component'] . "'";
			$CheckControlledResult = DB_query($sql);
			$CheckControlledRow = DB_fetch_row($CheckControlledResult);
			if ($CheckControlledRow[0]==1){
				echo prnMsg(_('Only non-serialised or non-lot controlled items can be set to auto issue. These items require the lot/serial numbers of items issued to the works orders to be specified so auto issue is not an option. Auto issue has been automatically set to No for this component'),'warn');
				$_POST['AutoIssue']=0;
			}
		}
		if ($_POST['Component'] == $SelectedParent) {
			$InputError = 1;
			echo prnMsg(_('The component selected is the same as the parent which is not allowed'),'error');
			$Errors[$i] = 'Component';
		}

		if (!in_array('EffectiveAfter', $Errors)) {
			$EffectiveAfterSQL = FormatDateForSQL($_POST['EffectiveAfter']);
		}
		if (!in_array('EffectiveTo', $Errors)) {
			$EffectiveToSQL = FormatDateForSQL($_POST['EffectiveTo']);
		}

		if (isset($SelectedParent) AND isset($SelectedComponent) AND $InputError != 1) {
			$Sequence = filter_number_format($_POST['Sequence']);
			$Digitals = GetDigitals($_POST['Sequence']);
			$Sequence = $Sequence * pow(10,$Digitals);
			$sql = "UPDATE bom SET sequence='" . $Sequence . "',
						digitals = '" . $Digitals . "',
						workcentreadded='" . $_POST['WorkCentreAdded'] . "',
						loccode='" . $_POST['LocCode'] . "',
						effectiveafter='" . $EffectiveAfterSQL . "',
						effectiveto='" . $EffectiveToSQL . "',
						quantity= '" . filter_number_format($_POST['Quantity']) . "',
						autoissue='" . $_POST['AutoIssue'] . "',
						remark='" . $_POST['Remark'] . "'
					WHERE bom.parent='" . $SelectedParent . "'
					AND bom.component='" . $SelectedComponent . "'";

			$ErrMsg =  _('Could not update this BOM component because');
			$DbgMsg =  _('The SQL used to update the component was');

			$result = DB_query($sql,$ErrMsg,$DbgMsg);
			$msg = _('Details for') . ' - ' . $SelectedComponent . ' ' . _('have been updated') . '.';
			if ($_SESSION['WeightedAverageCosting'] != 1) {
				UpdateCost($SelectedParent);
			}

		} elseif ($InputError !=1 AND ! isset($SelectedComponent) AND isset($SelectedParent)) {

		/*Selected component is null cos no item selected on first time round so must be adding a record must be Submitting new entries in the new component form */

		//need to check not recursive BOM component of itself!

			if (!CheckForRecursiveBOM ($SelectedParent, $_POST['Component'])) {

				/*Now check to see that the component is not already on the BOM */
				$sql = "SELECT component
						FROM bom
						WHERE parent='".$SelectedParent."'
						AND component='" . $_POST['Component'] . "'
						AND workcentreadded='" . $_POST['WorkCentreAdded'] . "'
						AND loccode='" . $_POST['LocCode'] . "'" ;

				$ErrMsg =  _('An error occurred in checking the component is not already on the BOM');
				$DbgMsg =  _('The SQL that was used to check the component was not already on the BOM and that failed in the process was');

				$result = DB_query($sql,$ErrMsg,$DbgMsg);

				if (DB_num_rows($result)==0) {
					$Sequence = filter_number_format($_POST['Sequence']);
					$Digitals = GetDigitals($_POST['Sequence']);
					$Sequence = $Sequence * pow(10,$Digitals);

					$sql = "INSERT INTO bom (sequence,
									digitals,
											parent,
											component,
											workcentreadded,
											loccode,
											quantity,
											effectiveafter,
											effectiveto,
											autoissue,
											remark)
							VALUES ('" . $Sequence . "',
								'" . $Digitals . "',
								'".$SelectedParent."',
								'" . $_POST['Component'] . "',
								'" . $_POST['WorkCentreAdded'] . "',
								'" . $_POST['LocCode'] . "',
								" . filter_number_format($_POST['Quantity']) . ",
								'" . $EffectiveAfterSQL . "',
								'" . $EffectiveToSQL . "',
								" . $_POST['AutoIssue'] . ",
								'" . $_POST['Remark'] . "')";

					$ErrMsg = _('Could not insert the BOM component because');
					$DbgMsg = _('The SQL used to insert the component was');

					$result = DB_query($sql,$ErrMsg,$DbgMsg);

			if ($_SESSION['WeightedAverageCosting'] != 1) {
					UpdateCost($SelectedParent);
			}
					$msg = _('A new component part') . ' ' . $_POST['Component'] . ' ' . _('has been added to the bill of material for part') . ' - ' . $SelectedParent . '.';

				} else {

				/*The component must already be on the BOM */

					echo prnMsg( _('The component') . ' ' . $_POST['Component'] . ' ' . _('is already recorded as a component of') . ' ' . $SelectedParent . '.' . '<br />' . _('So the quantity of the component required can be modified it is inappropriate for a component to appear more than once in a bill of material'),'error');
					$Errors[$i]='ComponentCode';
				}


			} //end of if its not a recursive BOM

		} //end of if no input errors

		if ($msg != '') {echo prnMsg($msg,'success');}

	} elseif (isset($_GET['delete']) AND isset($SelectedComponent) AND isset($SelectedParent)) {

	//the link to delete a selected record was clicked instead of the Submit button

		$sql="DELETE FROM bom
				WHERE parent='".$SelectedParent."'
				AND component='".$SelectedComponent."'
				AND loccode='".$Location."'
				AND workcentreadded='".$WorkCentre."'";

		$ErrMsg = _('Could not delete this BOM components because');
		$DbgMsg = _('The SQL used to delete the BOM was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);

		$ComponentSQL = "SELECT component
							FROM bom
							WHERE parent='" . $SelectedParent ."'";
		$ComponentResult = DB_query($ComponentSQL);
		$ComponentArray = DB_fetch_row($ComponentResult);

		if ($_SESSION['WeightedAverageCosting'] != 1) {//only update the cost for standard cost
			UpdateCost($SelectedParent);
		}

		echo prnMsg(_('The component part') . ' - ' . $SelectedComponent . ' - ' . _('has been deleted from this BOM'),'success');
		// Now reset to enable New Component Details to display after delete
        unset($_GET['SelectedComponent']);
	} elseif (isset($SelectedParent)
		AND !isset($SelectedComponent)
		AND ! isset($_POST['submit'])) {

	/* It could still be the second time the page has been run and a record has been selected	for modification - SelectedParent will exist because it was sent with the new call. if		its the first time the page has been displayed with no parameters then none of the above		are true and the list of components will be displayed with links to delete or edit each.		These will call the same page again and allow update/input or deletion of the records*/
		//DisplayBOMItems($SelectedParent);

	} //BOM editing/insertion ifs


	if(isset($_GET['ReSelect'])) {
		$SelectedParent = $_GET['ReSelect'];
	}

	//DisplayBOMItems($SelectedParent);
	$sql = "SELECT stockmaster.description,
					stockmaster.mbflag
			FROM stockmaster
			WHERE stockmaster.stockid='" . $SelectedParent . "'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);

	$myrow=DB_fetch_row($result);

	$ParentMBflag = $myrow[1];

	switch ($ParentMBflag){
		case 'A':
			$MBdesc = _('Assembly');
			break;
		case 'B':
			$MBdesc = _('Purchased');
			break;
		case 'M':
			$MBdesc = _('Manufactured');
			break;
		case 'K':
			$MBdesc = _('Kit Set');
			break;
		case 'G':
			$MBdesc = _('Phantom');
			break;
	}

	echo '<br /><div class="row noprint"><div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-default">' . _('Back to BOMs') . '</a></div></div><br />';
	// Display Manufatured Parent Items
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
			FROM bom INNER JOIN locationusers ON locationusers.loccode=bom.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1, stockmaster
			WHERE bom.component='".$SelectedParent."'
			AND stockmaster.stockid=bom.parent
			AND stockmaster.mbflag='M'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	$ix = 0;
	if( DB_num_rows($result) > 0 ) {
     echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	 echo '<tr><td><div class="row">' . _('Manufactured parent items').' : ';
	 while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .
			$myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 } //end while loop
	 echo '</div></td></tr>';
     echo '</table></div></div></div>';
	}
	// Display Assembly Parent Items
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
		FROM bom INNER JOIN stockmaster
		ON bom.parent=stockmaster.stockid
		WHERE bom.component='".$SelectedParent."'
		AND stockmaster.mbflag='A'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	if( DB_num_rows($result) > 0 ) {
        echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		echo '<tr><td><div class="centre">' . _('Assembly parent items').' : ';
	 	$ix = 0;
	 	while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .
			$myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 	} //end while loop
	 	echo '</div></td></tr>';
        echo '</table></div></div></div>';
	}
	// Display Kit Sets
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
			FROM bom INNER JOIN stockmaster
			ON bom.parent=stockmaster.stockid
			INNER JOIN locationusers ON locationusers.loccode=bom.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
			WHERE bom.component='".$SelectedParent."'
			AND stockmaster.mbflag='K'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	if( DB_num_rows($result) > 0 ) {
        echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		echo '<tr><td><div class="row">' . _('Kit sets').' : ';
	 	$ix = 0;
	 	while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .
			$myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 	} //end while loop
	 	echo '</div></td></tr>';
        echo '</table></div></div></div>';
	}
	// Display Phantom/Ghosts
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
			FROM bom INNER JOIN stockmaster
			ON bom.parent=stockmaster.stockid
			WHERE bom.component='".$SelectedParent."'
			AND stockmaster.mbflag='G'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	if( DB_num_rows($result) > 0 ) {
		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
				<tr>
					<td><div class="row">' . _('Phantom').' : ';
	 	$ix = 0;
	 	while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .  $myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 	} //end while loop
	 	echo '</div></td>
				</tr>
			</table></div></div></div>';
	}
		$StockID = $SelectedParent;
		if (function_exists('imagecreatefromjpeg')){
			if ($_SESSION['ShowStockidOnImages'] == '0'){
				$StockImgLink = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
								'&amp;StockID='.urlencode($StockID).
								'&amp;text='.
								'&amp;width=100'.
								'&amp;eight=100'.
								'" alt="" />';
			} else {
				$StockImgLink = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
								'&amp;StockID='.urlencode($StockID).
								'&amp;text='. $StockID .
								'&amp;width=100'.
								'&amp;height=100'.
								'" alt="" />';
			}
		} else {
			if( isset($StockID) AND file_exists($_SESSION['part_pics_dir'] . '/' .$StockID.'.jpg') ) {
				$StockImgLink = '<img src="' . $_SESSION['part_pics_dir'] . '/' . $StockID . '.jpg" height="100" width="100" />';
			} else {
				$StockImgLink = _('No Image');
			}
		}
	echo '<br />
			<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<tr>
			<th colspan="13">' . $SelectedParent .' - ' . $myrow[0] . ' ('. $MBdesc. ') ' . $StockImgLink . '</th>
		</tr>';
	echo '</table></div></div></div>
	<div id="Report"><div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

	$BOMTree = array();
	//BOMTree is a 2 dimensional array with three elements for each item in the array - Level, Parent, Component
	//display children populates the BOM_Tree from the selected parent
	$i =0;
	display_children($SelectedParent, 1, $BOMTree);

	$TableHeader =  '<tr>
						<th>' . _('Level') . '</th>
						<th>' . _('Sequence') . '</th>
						<th>' . _('Category') . '</th>
						<th>' . _('Code') . '</th>
						<th>' . _('Description') . '</th>
						<th>' . _('Location') . '</th>
						<th>' . _('Work Centre') . '</th>
						<th>' . _('Quantity') . '</th>
						<th>' . _('Unit') . '</th>
						<th class="noprint">' . _('Effective From') . '</th>
						<th class="noprint">' . _('Effective To') . '</th>
						<th class="noprint">' . _('Auto Issue ') . '</th>
						<th class="noprint">' . _('On Hand') . '</th>
						<th colspan="3">' . _('Actions') . '</th>
						
											</tr>';
	echo $TableHeader;
	if(count($BOMTree) == 0) {
		echo '<tr class="striped_row">
				<td colspan="8">' . _('No materials found.') . '</td>
			</tr>';
	} else {
		$UltimateParent = $SelectedParent;
		$RowCounter = 1;
		$BOMTree = arrayUnique($BOMTree);
		foreach($BOMTree as $BOMItem){
			$Level = $BOMItem['Level'];
			$Parent = $BOMItem['Parent'];
			$Component = $BOMItem['Component'];

			echo '<tr class="striped_row">';

			DisplayBOMItems($UltimateParent, $Parent, $Component, $Level);
		}
	}
	echo '</table>
		</div>
		</div>
		</div>
				<br />';
    /* We do want to show the new component entry form in any case - it is a lot of work to get back to it otherwise if we need to add */

		echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select=' . $SelectedParent .'">';
       
		echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

		if (isset($_GET['SelectedComponent']) AND $InputError !=1) {
		//editing a selected component from the link to the line item

			$sql = "SELECT sequence,
						digitals,
						bom.loccode,
						effectiveafter,
						effectiveto,
						workcentreadded,
						quantity,
						autoissue,
						remark
					FROM bom
					INNER JOIN locationusers ON locationusers.loccode=bom.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
					WHERE parent='".$SelectedParent."'
					AND component='".$SelectedComponent."'";

			$result = DB_query($sql);
			$myrow = DB_fetch_array($result);

			$_POST['Sequence'] = locale_number_format($myrow['sequence']/pow(10,$myrow['digitals']),'Variable');
			$_POST['LocCode'] = $myrow['loccode'];
			$_POST['EffectiveAfter'] = ConvertSQLDate($myrow['effectiveafter']);
			$_POST['EffectiveTo'] = ConvertSQLDate($myrow['effectiveto']);
			$_POST['WorkCentreAdded']  = $myrow['workcentreadded'];
			$_POST['Quantity'] = locale_number_format($myrow['quantity'],'Variable');
			$_POST['AutoIssue'] = $myrow['autoissue'];
			$_POST['Remark'] = $myrow['remark'];

			//echo prnMsg(_('Edit the details of the selected component in the fields below') . '. <br />' . _('Click on the Enter Information button to update the component details'),'info')
//                 ;
			echo '<br />
					<input type="hidden" name="SelectedParent" value="' . $SelectedParent . '" />';
			echo '<input type="hidden" name="SelectedComponent" value="' . $SelectedComponent . '" />';
			
			echo '
					<div class="block"><div class="block-title"><h3>' .  ('Edit Component Details')  . '</h3></div>
				';
			echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Component') . '</label>
					' . $SelectedComponent . '</div>
					 <input type="hidden" name="Component" value="' . $SelectedComponent . '" />
				</div>';

		} else { //end of if $SelectedComponent
			$_POST['Sequence'] = 0;
			$_POST['Remark'] = '';
			echo '<input type="hidden" name="SelectedParent" value="' . $SelectedParent . '" />';
			/* echo "Enter the details of a new component in the fields below. <br />Click on 'Enter Information' to add the new component, once all fields are completed.";
			*/
			
			echo '<div class="block"><div class="block-title"><h3>' . _('New Component Details')  . '</h3></div>
				';
			echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Component code') . '</label>
					';
			echo '<select ' . (in_array('ComponentCode',$Errors) ?  'class="selecterror"' : '' ) .' tabindex="1" name="Component" class="form-control">';

			if ($ParentMBflag=='A'){ /*Its an assembly */
				$sql = "SELECT stockmaster.stockid,
							stockmaster.description
						FROM stockmaster INNER JOIN stockcategory
							ON stockmaster.categoryid = stockcategory.categoryid
						WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
						OR stockmaster.mbflag !='D')
						AND stockmaster.mbflag !='K'
						AND stockmaster.mbflag !='A'
						AND stockmaster.controlled = 0
						AND stockmaster.stockid != '".$SelectedParent."'
						ORDER BY stockmaster.stockid";

			} else { /*Its either a normal manufac item, phantom, kitset - controlled items ok */
				$sql = "SELECT stockmaster.stockid,
							stockmaster.description
						FROM stockmaster INNER JOIN stockcategory
							ON stockmaster.categoryid = stockcategory.categoryid
						WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
						OR stockmaster.mbflag !='D')
						AND stockmaster.mbflag !='K'
						AND stockmaster.mbflag !='A'
						AND stockmaster.stockid != '".$SelectedParent."'
						ORDER BY stockmaster.stockid";
			}

			$ErrMsg = _('Could not retrieve the list of potential components because');
			$DbgMsg = _('The SQL used to retrieve the list of potential components part was');
			$result = DB_query($sql,$ErrMsg, $DbgMsg);


			while ($myrow = DB_fetch_array($result)) {
				echo '<option value="' .$myrow['stockid'].'">' . str_pad($myrow['stockid'],21, '_', STR_PAD_RIGHT) . $myrow['description'] . '</option>';
			} //end while loop

			echo '</select></div>
				</div>';
		}
		echo '
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Sequence in BOM') . '</label>
               <input type="text" class="form-control" required="required" size="5" name="Sequence" value="' . $_POST['Sequence'] . '" /> ' . _('Number with decimal places is acceptable') . '</div>
            </div>';
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Location') . ' </label>
				<select tabindex="2" name="LocCode" class="form-control">';

		DB_free_result($result);
		$sql = "SELECT locationname,
					locations.loccode
				FROM locations
				INNER JOIN locationusers
					ON locationusers.loccode=locations.loccode
					AND locationusers.userid='" .  $_SESSION['UserID'] . "'
					AND locationusers.canupd=1
				WHERE locations.usedforwo = 1";
		$result = DB_query($sql);

		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['LocCode']) AND $myrow['loccode']==$_POST['LocCode']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';

		} //end while loop

		DB_free_result($result);

		echo '</select></div>
			</div></div>
			<div class="row">
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Add Work Centre') . ' </label>';

		$sql = "SELECT code, description FROM workcentres INNER JOIN locationusers ON locationusers.loccode=workcentres.location AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
		$result = DB_query($sql);

		if (DB_num_rows($result)==0){
			echo prnMsg( _('There are no work centres set up yet') . '. ' . _('Please use the link below to set up work centres') . '.','warn');
			echo '<a href="' . $RootPath . '/WorkCentres.php" class="btn btn-info">' . _('Work Centre Maintenance') . '</a></div></div><br />';
			include('includes/footer.php');
			exit;
		}

		echo '<select tabindex="3" name="WorkCentreAdded" class="form-control">';

		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['WorkCentreAdded']) AND $myrow['code']==$_POST['WorkCentreAdded']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['code'] . '">' . $myrow['description'] . '</option>';
		} //end while loop

		DB_free_result($result);

		echo '</select></div>
				</div>
				<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Quantity') . ' </label>
					<input ' . (in_array('Quantity',$Errors) ?  'class="inputerror"' : '' ) .' tabindex="4" type="text" class="form-control" required="required" name="Quantity" size="10" maxlength="8" title="' . _('Enter the quantity of this item required for the parent item') . '" value="';
		if (isset($_POST['Quantity'])){
			echo $_POST['Quantity'];
		} else {
			echo 1;
		}

		echo '" /></div>
			</div>';

		if (!isset($_POST['EffectiveTo']) OR $_POST['EffectiveTo']=='') {
			$_POST['EffectiveTo'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m'),Date('d'),(Date('y')+20)));
		}
		if (!isset($_POST['EffectiveAfter']) OR $_POST['EffectiveAfter']=='') {
			$_POST['EffectiveAfter'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m'),Date('d')-1,Date('y')));
		}

		echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Effective From') . ' (' . $_SESSION['DefaultDateFormat'] . ')</label>
				<input ' . (in_array('EffectiveAfter',$Errors) ?  '"' : '' ) . ' tabindex="5" type="text" required="required" name="EffectiveAfter" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" size="11" maxlength="10" value="' . $_POST['EffectiveAfter'] .'" /></div>
			</div>
			</div>
			<div class="row">
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Effective To') . ' (' . $_SESSION['DefaultDateFormat'] . ')</label>
				<input  ' . (in_array('EffectiveTo',$Errors) ?  '"' : '' ) . ' tabindex="6" type="text" name="EffectiveTo" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" size="11" maxlength="10" value="' . $_POST['EffectiveTo'] .'" /></div>
			</div>';

		if ($ParentMBflag=='M' OR $ParentMBflag=='G'){
			echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Auto Issue to Work Orders') . '</label>
					
					<select tabindex="7" name="AutoIssue" class="form-control">';

			if (!isset($_POST['AutoIssue'])){
				$_POST['AutoIssue'] = $_SESSION['AutoIssue'];
			}
			if ($_POST['AutoIssue']==0) {
				echo '<option selected="selected" value="0">' . _('No') . '</option>';
				echo '<option value="1">' . _('Yes') . '</option>';
			} else {
				echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
				echo '<option value="0">' . _('No') . '</option>';
			}


			echo '</select></div>
				</div>';
		} else {
			echo '<input type="hidden" name="AutoIssue" value="0" />';
		}

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Remark') . '</label>
			<textarea class="form-control" name="Remark" >' . $_POST['Remark'] . '</textarea></div>
			</div>';

		echo '</div>
			
			<div class="row noprint" align="center">
			
				<input tabindex="8" class="btn btn-success" type="submit" name="Submit" value="' . _('Submit') . '" />
				
				
            </div><br />
			 </div>
			 </div>
			</form>';


	// end of BOM maintenance code - look at the parent selection form if not relevant
// ----------------------------------------------------------------------------------

} elseif (isset($_POST['Search'])){
	// Work around to auto select
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		$_POST['StockCode']='%';
	}
	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		echo prnMsg( _('Stock description has been used instead of the Stock ID'), 'info' );
	}
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		echo prnMsg( _('At least one stock description keyword or part of a stock ID must be entered for the search'), 'info' );
	} else {
		if (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$sql = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					stockmaster.mbflag,
					SUM(locstock.quantity) as totalonhand
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				WHERE stockmaster.description " . LIKE . " '".$SearchString."'
				AND (stockmaster.mbflag='M' OR stockmaster.mbflag='K' OR stockmaster.mbflag='A' OR stockmaster.mbflag='G')
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					stockmaster.mbflag
				ORDER BY stockmaster.stockid";

		} elseif (mb_strlen($_POST['StockCode'])>0){
			$sql = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.mbflag,
					stockmaster.decimalplaces,
					sum(locstock.quantity) as totalonhand
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				WHERE stockmaster.stockid " . LIKE  . "'%" . $_POST['StockCode'] . "%'
				AND (stockmaster.mbflag='M'
					OR stockmaster.mbflag='K'
					OR stockmaster.mbflag='G'
					OR stockmaster.mbflag='A')
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.mbflag,
					stockmaster.decimalplaces
				ORDER BY stockmaster.stockid";

		}

		$ErrMsg = _('The SQL to find the parts selected failed with the message');
		$result = DB_query($sql,$ErrMsg);

	} //one of keywords or StockCode was more than a zero length string
} //end of if search

if (!isset($SelectedParent)) {

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">' .
	'<div class="text-info">' .  _('Select only a manufactured part') . ' (' . _('or Assembly or Kit part') . ') ' . _('to maintain the multi level bill of materials using the options below') .  '</div>' .  '
   
     <br />
     <div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Description') . '' . _('-part or full') . '</label>
		<input tabindex="1" type="text" class="form-control" name="Keywords" size="20" maxlength="25" /></div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock ID') . ' ' . _('-part or full') . '</label>
		<input tabindex="2" class="form-control" type="text" name="StockCode" autofocus="autofocus" size="15" maxlength="18" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"><br /><input tabindex="3" type="submit" class="btn btn-info" name="Search" value="' . _('Search Now') . '" /></div></div></div><br />';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($_POST['Search'])
		AND isset($result)
		AND !isset($SelectedParent)) {

		echo '<br />
			<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		$TableHeader = '<tr>
							<th>' . _('Code') . '</th>
							<th>' . _('Description') . '</th>
							<th>' . _('On Hand') . '</th>
							<th>' . _('Units') . '</th>
						</tr>';

		echo $TableHeader;

		$j = 1;

		while ($myrow=DB_fetch_array($result)) {
			if ($myrow['mbflag']=='A' OR $myrow['mbflag']=='K' OR $myrow['mbflag']=='G'){
				$StockOnHand = _('N/A');
			} else {
				$StockOnHand = locale_number_format($myrow['totalonhand'],$myrow['decimalplaces']);
			}
			$tab = $j+3;
			printf('<tr class="striped_row">
					<td><input tabindex="' . $tab . '" type="submit" name="Select" value="%s" class="btn btn-info" /></td>
					<td>%s</td>
					<td class="number noprint">%s</td>
					<td>%s</td>
					</tr>',
					$myrow['stockid'],
					$myrow['description'],
					$StockOnHand,
					$myrow['units']);

			$j++;
	//end of page full new headings if
		}
	//end of while loop

		echo '</table></div></div></div>';

	}
	//end if results to show


	echo '</form>';

	} //end StockID already selected
// This function created by Dominik Jungowski on PHP developer blog
function arrayUnique($array, $preserveKeys = false)
{
	//Unique Array for return
	$arrayRewrite = array();
	//Array with the md5 hashes
	$arrayHashes = array();
	foreach($array as $key => $item) {
		// Serialize the current element and create a md5 hash
		$hash = md5(serialize($item));
		// If the md5 didn't come up yet, add the element to
		// arrayRewrite, otherwise drop it
		if (!isset($arrayHashes[$hash])) {
			// Save the current element hash
			$arrayHashes[$hash] = $hash;
			//Add element to the unique Array
			if ($preserveKeys) {
				$arrayRewrite[$key] = $item;
			} else {
				$arrayRewrite[] = $item;
			}
		}
	}
	return $arrayRewrite;
}

include('includes/footer.php');
function GetDigitals($Sequence) {
	$SQLNumber = filter_number_format($Sequence);
	return strlen(substr(strrchr($SQLNumber, "."),1));
}

?>
