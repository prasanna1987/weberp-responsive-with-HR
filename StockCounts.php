<?php

include('includes/session.php');

$Title = _('Stock Check Sheets Entry');

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
echo '<form name="EnterCountsForm" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';



if (!isset($_POST['Action']) AND !isset($_GET['Action'])) {
	$_GET['Action'] = 'Enter';
}
if (isset($_POST['Action'])) {
	$_GET['Action'] = $_POST['Action'];
}

if ($_GET['Action']!='View' AND $_GET['Action']!='Enter'){
	$_GET['Action'] = 'Enter';
}

echo '<div class="row" align="center">';
if ($_GET['Action']=='View'){
	echo '<a href="' . $RootPath . '/StockCounts.php?&amp;Action=Enter" class="btn btn-info">' . _('Resuming Entering Counts') . '</a> ';
} else {
	echo '<a href="' . $RootPath . '/StockCounts.php?&amp;Action=View" class="btn btn-info">' . _('View Entered Counts') . '</a>';
}
echo '</div><br />';
if ($_GET['Action'] == 'Enter'){

	if (isset($_POST['EnterCounts'])){

		$Added=0;
		$Counter = isset($_POST['RowCount'])?$_POST['RowCount'] : 10; // Arbitrary number of 10 hard coded as default as originally used - should there be a setting?
			for ($i=1;$i<=$Counter;$i++){
			$InputError =False; //always assume the best to start with

			$Quantity = 'Qty_' . $i;
			$BarCode = 'BarCode_' . $i;
			$StockID = 'StockID_' . $i;
			$Reference = 'Ref_' . $i;

			if (strlen($_POST[$BarCode])>0){
				$sql = "SELECT stockmaster.stockid
								FROM stockmaster
								WHERE stockmaster.barcode='". $_POST[$BarCode] ."'";

				$ErrMsg = _('Could not determine if the part being ordered was a kitset or not because');
				$DbgMsg = _('The sql that was used to determine if the part being ordered was a kitset or not was ');
				$KitResult = DB_query($sql,$ErrMsg,$DbgMsg);
				$myrow=DB_fetch_array($KitResult);

				$_POST[$StockID] = strtoupper($myrow['stockid']);
			}

			if (mb_strlen($_POST[$StockID])>0){
				if (!is_numeric($_POST[$Quantity])){
					$InputError=True;
				}
			$SQL = "SELECT stockid FROM stockcheckfreeze WHERE stockid='" . $_POST[$StockID] . "'";
				$result = DB_query($SQL);
				if (DB_num_rows($result)==0){
					echo prnMsg( _('The stock code entered on line') . ' ' . $i . ' ' . _('is not a part code that has been added to the stock check file') . ' - ' . _('the code entered was') . ' ' . $_POST[$StockID] . '. ' . _('This line will have to be re-entered'),'warn');
					$InputError = True;
				}

				if ($InputError==False){
					$Added++;
					$sql = "INSERT INTO stockcounts (stockid,
									loccode,
									qtycounted,
									reference)
								VALUES ('" . $_POST[$StockID] . "',
									'" . $_POST['Location'] . "',
									'" . $_POST[$Quantity] . "',
									'" . $_POST[$Reference] . "')";

					$ErrMsg = _('The stock count line number') . ' ' . $i . ' ' . _('could not be entered because');
					$EnterResult = DB_query($sql,$ErrMsg);
				}
			}
		} // end of loop
		echo prnMsg($Added . _(' Stock Counts Entered'), 'success');
		unset($_POST['EnterCounts']);
	} // end of if enter counts button hit

	$CatsResult = DB_query("SELECT DISTINCT stockcategory.categoryid,
								categorydescription
						FROM stockcategory INNER JOIN stockmaster
							ON stockcategory.categoryid=stockmaster.categoryid
							INNER JOIN stockcheckfreeze
							ON stockmaster.stockid=stockcheckfreeze.stockid");

	if (DB_num_rows($CatsResult) ==0) {
		echo prnMsg(_('The stock check sheets must be run first to create the stock check. Only once these are created can the stock counts be entered. Currently there is no stock check to enter counts for'),'error');
		echo '<div class="row" align="center"><a href="' . $RootPath . '/StockCheck.php" class="btn btn-success">' . _('Create New Stock Check') . '</a></div><br />';
	} else {
		echo '<div class="row">
		<div class="col-xs-4"><div class="form-group" style="margin-top:27px;"> <label class="col-md-12 control-label">' ._('Location') . '</label>
		<select name="Location" class="form-control">';
		$sql = "SELECT locations.loccode, locationname FROM locations
				INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
		$result = DB_query($sql);

		while ($myrow=DB_fetch_array($result)){

			if (isset($_POST['Location']) AND $myrow['loccode']==$_POST['Location']){
				echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			} else {
				echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			}
		}
		echo '</select></div></div>
		<div class="col-xs-4">
		<div class="form-group"><label class="col-md-12 control-label">
		<input type="submit" class="btn btn-info" name="EnterByCat" value="' . _('Enter By Category') . '" /></label>
		<select name="StkCat" class="form-control" onChange="ReloadForm(EnterCountsForm.EnterByCat)" >';

		echo '<option value="">' . _('Not Yet Selected') . '</option>';

		while ($myrow=DB_fetch_array($CatsResult)){
			if ($_POST['StkCat']==$myrow['categoryid']) {
				echo '<option selected="selected" value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
			} else {
				echo '<option value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
			}
		}
		echo '</select></div></div></div><br />';

		if (isset($_POST['EnterByCat'])){

			$StkCatResult = DB_query("SELECT categorydescription FROM stockcategory WHERE categoryid='" . $_POST['StkCat'] . "'");
			$StkCatRow = DB_fetch_row($StkCatResult);

			echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Entering Counts For Stock Category') . ': ' . $StkCatRow[0] . '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">

				<thead>
				<tr>
					<th>' . _('Stock Code') . '</th>
					<th>' . _('Description') . '</th>
					<th>' . _('Quantity') . '</th>
					<th>' . _('Reference') . '</th>
				</tr></thead>';
			$StkItemsResult = DB_query("SELECT stockcheckfreeze.stockid,
												description
										FROM stockcheckfreeze INNER JOIN stockmaster
										ON stockcheckfreeze.stockid=stockmaster.stockid
										WHERE categoryid='" . $_POST['StkCat'] . "' AND loccode = '" . $_POST['Location'] . "'
										ORDER BY stockcheckfreeze.stockid");

			$RowCount=1;
			while ($StkRow = DB_fetch_array($StkItemsResult)) {
				echo '<tr>
						<td><input type="hidden" name="StockID_' . $RowCount . '" value="' . $StkRow['stockid'] . '" />' . $StkRow['stockid'] . '</td>
						<td>' . $StkRow['description'] . '</td>
						<td><input type="text" class="form-control" name="Qty_' . $RowCount . '" maxlength="10" size="10" /></td>
						<td><input type="text" class="form-control" name="Ref_' . $RowCount . '" maxlength="20" size="20" /></td>
					</tr>';
				$RowCount++;
			}

		} else {
			echo '<br /><div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Entering Counts For Stock Category') . ': ' . $StkCatRow[0] . '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered"><tr>
					<th>' . _('Bar Code') . '</th>
					<th>' . _('Stock Code') . '</th>
					<th>' . _('Quantity') . '</th>
					<th>' . _('Reference') . '</th>
				</tr>';

			for ($RowCount=1;$RowCount<=10;$RowCount++){

				echo '<tr>
						<td><input type="text" class="form-control" name="BarCode_' . $RowCount . '" maxlength="20" size="20" /></td>
						<td><input type="text" class="form-control" name="StockID_' . $RowCount . '" maxlength="20" size="20" /></td>
						<td><input type="text" class="form-control" name="Qty_' . $RowCount . '" maxlength="10" size="10" /></td>
						<td><input type="text" class="form-control" name="Ref_' . $RowCount . '" maxlength="20" size="20" /></td>
					</tr>';

			}
		}

		echo '</table></div></div></div></div>
				<br />
				<div class="row" align="center">
				
					<input type="hidden" name="RowCount" value="' .$RowCount . '" />
					<input type="submit" class="btn btn-success" name="EnterCounts" value="' . _('Enter Above Counts') . '" />
				</div><br />';
	} // there is a stock check to enter counts for
//END OF action=ENTER
} elseif ($_GET['Action']=='View'){

	if (isset($_POST['DEL']) AND is_array($_POST['DEL']) ){
		foreach ($_POST['DEL'] as $id=>$val){
			if ($val == 'on'){
				$sql = "DELETE FROM stockcounts WHERE id='".$id."'";
				$ErrMsg = _('Failed to delete StockCount ID #').' '.$i;
				$EnterResult = DB_query($sql,$ErrMsg);
				echo prnMsg( _('Deleted Id #') . ' ' . $id, 'success');
			}
		}
	}

	//START OF action=VIEW
	$SQL = "select stockcounts.*,
					canupd from stockcounts
					INNER JOIN locationusers ON locationusers.loccode=stockcounts.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1";
	$result = DB_query($SQL);
	echo '<input type="hidden" name="Action" value="View" />';
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<thead>
	<tr>
			<th>' . _('Stock Code') . '</th>
			<th>' . _('Location') . '</th>
			<th>' . _('Qty Counted') . '</th>
			<th>' . _('Reference') . '</th>
			<th>' . _('Delete?') . '</th></tr></thead>';
	while ($myrow=DB_fetch_array($result)){
		echo '<tr>
			<td>'.$myrow['stockid'].'</td>
			<td>'.$myrow['loccode'].'</td>
			<td>'.$myrow['qtycounted'].'</td>
			<td>'.$myrow['reference'].'</td>
			<td>';
		if ($myrow['canupd']==1) {
			echo '<input type="checkbox" name="DEL[' . $myrow['id'] . ']" maxlength="20" size="20" />';

		}
		echo '</td></tr>';

	}
	echo '</table></div></div></div>
	<br /><div class="row" align="center">

	<input type="submit" class="btn btn-success" name="SubmitChanges" value="' . _('Submit') . '" /></div><br />
';

//END OF action=VIEW
}

echo '
      </form>';
include('includes/footer.php');
?>