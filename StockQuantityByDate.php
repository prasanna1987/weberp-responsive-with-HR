<?php
include ('includes/session.php');
$Title = _('Stock On Hand By Date');
include ('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . $Title . '</h1></a></div>
	';
echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="' . htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

$SQL = "SELECT categoryid, categorydescription FROM stockcategory";
$ResultStkLocs = DB_query($SQL);

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Stock Category') . '</label>
		
			<select required="required" name="StockCategory" class="form-control">
				<option value="All">' . _('All') . '</option>';

while ($MyRow = DB_fetch_array($ResultStkLocs)) {
	if (isset($_POST['StockCategory']) and $_POST['StockCategory'] != 'All') {
		if ($MyRow['categoryid'] == $_POST['StockCategory']) {
			echo '<option selected="selected" value="' . $MyRow['categoryid'] . '">' . $MyRow['categorydescription'] . '</option>';
		} else {
			echo '<option value="' . $MyRow['categoryid'] . '">' . $MyRow['categorydescription'] . '</option>';
		}
	} else {
		echo '<option value="' . $MyRow['categoryid'] . '">' . $MyRow['categorydescription'] . '</option>';
	}
}
echo '</select></div></div>';

$SQL = "SELECT locationname,
				locations.loccode
			FROM locations
			INNER JOIN locationusers
				ON locationusers.loccode=locations.loccode
				AND locationusers.userid='" . $_SESSION['UserID'] . "'
				AND locationusers.canview=1";

$ResultStkLocs = DB_query($SQL);

echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Stock Location') . '</label>
	<select required="required" name="StockLocation" class="form-control"> ';

while ($MyRow = DB_fetch_array($ResultStkLocs)) {
	if (isset($_POST['StockLocation']) and $_POST['StockLocation'] != 'All') {
		if ($MyRow['loccode'] == $_POST['StockLocation']) {
			echo '<option selected="selected" value="' . $MyRow['loccode'] . '">' . $MyRow['locationname'] . '</option>';
		} else {
			echo '<option value="' . $MyRow['loccode'] . '">' . $MyRow['locationname'] . '</option>';
		}
	} elseif ($MyRow['loccode'] == $_SESSION['UserStockLocation']) {
		echo '<option selected="selected" value="' . $MyRow['loccode'] . '">' . $MyRow['locationname'] . '</option>';
		$_POST['StockLocation'] = $MyRow['loccode'];
	} else {
		echo '<option value="' . $MyRow['loccode'] . '">' . $MyRow['locationname'] . '</option>';
	}
}
echo '</select></div></div>';

if (!isset($_POST['OnHandDate'])) {
	$_POST['OnHandDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, Date('m'), 0, Date('y')));
}

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('On-Hand On Date') . '</label>
	<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="OnHandDate" size="12" required="required" maxlength="10" value="' . $_POST['OnHandDate'] . '" /></div></div></div>';

if (isset($_POST['ShowZeroStocks'])) {
	$Checked = 'checked="checked"';
} else {
	$Checked = '';
}

echo '<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">', ('Include zero stocks?'), '</label>
		<input type="checkbox" name="ShowZeroStocks" value="" ', $Checked, '  /></div>
	</div>
';

echo '<div class="col-xs-4">
<div class="form-group"> <br />
		<input type="submit" name="ShowStatus" class="btn btn-success" value="' . _('Show') . '" />
		</div></div>
	</div>
	
	</form>
	</div></div>
	';

$TotalQuantity = 0;

if (isset($_POST['ShowStatus']) and is_date($_POST['OnHandDate'])) {
	if ($_POST['StockCategory'] == 'All') {
		$SQL = "SELECT stockid,
						 description,
						 decimalplaces
					 FROM stockmaster
					 WHERE (mbflag='M' OR mbflag='B')";
	} else {
		$SQL = "SELECT stockid,
						description,
						decimalplaces
					 FROM stockmaster
					 WHERE categoryid = '" . $_POST['StockCategory'] . "'
					 AND (mbflag='M' OR mbflag='B')";
	}

	$ErrMsg = _('The stock items in the category selected cannot be retrieved because');
	$DbgMsg = _('The SQL that failed was');

	$StockResult = DB_query($SQL, $ErrMsg, $DbgMsg);

	$SQLOnHandDate = FormatDateForSQL($_POST['OnHandDate']);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead> 
			<tr>
				<th>' . _('Stock ID') . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('Quantity On Hand') . '</th>
				<th>' . _('Controlled') . '</th>
			</tr></thead> ';

	while ($MyRow = DB_fetch_array($StockResult)) {

		if (isset($_POST['ShowZeroStocks'])) {
			$SQL = "SELECT stockid,
							newqoh
						FROM stockmoves
						WHERE stockmoves.trandate <= '" . $SQLOnHandDate . "'
							AND stockid = '" . $MyRow['stockid'] . "'
							AND loccode = '" . $_POST['StockLocation'] . "'
						ORDER BY stkmoveno DESC LIMIT 1";
		} else {
			$SQL = "SELECT stockid,
							newqoh
						FROM stockmoves
						WHERE stockmoves.trandate <= '" . $SQLOnHandDate . "'
							AND stockid = '" . $MyRow['stockid'] . "'
							AND loccode = '" . $_POST['StockLocation'] . "'
							AND newqoh > 0
						ORDER BY stkmoveno DESC LIMIT 1";
		}

		$ErrMsg = _('The stock held as at') . ' ' . $_POST['OnHandDate'] . ' ' . _('could not be retrieved because');

		$LocStockResult = DB_query($SQL, $ErrMsg);

		$NumRows = DB_num_rows($LocStockResult);

		while ($LocQtyRow = DB_fetch_array($LocStockResult)) {

			if ($MyRows['controlled'] == 1) {
				$Controlled = _('Yes');
			} else {
				$Controlled = _('No');
			}

			if ($NumRows == 0) {
				printf('<tr class="striped_row">
						<td><a target="_blank" href="' . $RootPath . '/StockStatus.php?%s" class="btn btn-info">%s</a></td>
						<td>%s</td>
						<td>%s</td></tr>', 'StockID=' . mb_strtoupper($MyRow['stockid']), mb_strtoupper($MyRow['stockid']), $MyRow['description'], 0);
			} else {
				printf('<tr class="striped_row">
					<td><a target="_blank" href="' . $RootPath . '/StockStatus.php?%s" class="btn btn-info">%s</a></td>
					<td>%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td></tr>', 'StockID=' . mb_strtoupper($MyRow['stockid']), mb_strtoupper($MyRow['stockid']), $MyRow['description'], locale_number_format($LocQtyRow['newqoh'], $MyRow['decimalplaces']), $Controlled);

				$TotalQuantity+= $LocQtyRow['newqoh'];
			}
			//end of page full new headings if
			
		}

	} //end of while loop
	echo '<tr>
			<td colspan="4">' . _('<strong>Total Quantity   </strong>') . '' . $TotalQuantity . '</td>
		</tr>
		</table></div></div></div>';
}

include ('includes/footer.php');
?>