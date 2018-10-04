<?php


include('includes/session.php');
$Title = _('Where Used Inquiry');
include('includes/header.php');

if (isset($_GET['StockID'])){
	$StockID = trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	$StockID = trim(mb_strtoupper($_POST['StockID']));
}

echo '
	
	<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '
	</h1></a></div>
	<p align="left"><a href="' . $RootPath . '/SelectProduct.php" class="btn btn-default">' . _('Back to Items') . '</a></p>';
if (isset($StockID)){
	$result = DB_query("SELECT description,
								units,
								mbflag
						FROM stockmaster
						WHERE stockid='".$StockID."'");
	$myrow = DB_fetch_row($result);
	if (DB_num_rows($result)==0){
		echo prnMsg(_('The item code entered') . ' - ' . $StockID . ' ' . _('is not set up as an item in the system') . '. ' . _('Re-enter a valid item code or select from the Select Item link above'),'error');
		include('includes/footer.php');
		exit;
	}
	echo '<br />
		<div class="row"><h3>' . $StockID . ' - ' . $myrow[0] . '  (' . _('in units of') . ' ' . $myrow[1] . ')</h3><br /></div>';
}
echo '<div class="row gutter30">
<div class="col-xs-12"><div class="row">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($StockID)) {
	echo '
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">'._('Enter an Item Code') . '</label> <input type="text" required="required" data-type="no-illegal-chars" class="form-control" title="'._('Illegal characters and blank is not allowed').'" name="StockID" autofocus="autofocus" size="21" maxlength="20" value="' . $StockID . '" placeholder="'._('No illegal characters allowed').'" /></div></div>';
} else {
	echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">'._('Enter an Item Code') . '</label>
 <input type="text" required="required" class="form-control" data-type="no-illegal-chars"  title="'._('Illegal characters and blank is not allowed').'" name="StockID" autofocus="autofocus" size="21" maxlength="20" placeholder="'._('No illegal characters allowed').'" /></div></div>';
}

echo '<div class="col-xs-4">
<div class="form-group"><br /> <input type="submit" class="btn btn-success" name="ShowWhereUsed" value="' . _('Show') . '" />
		
	</div></div></div>';

if (isset($StockID)) {

	$SQL = "SELECT bom.*,
				stockmaster.description,
				stockmaster.discontinued
			FROM bom INNER JOIN stockmaster
			ON bom.parent = stockmaster.stockid
			INNER JOIN locationusers ON locationusers.loccode=bom.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			WHERE component='" . $StockID . "'
                AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                AND bom.effectiveto > '" . date('Y-m-d') . "'
			ORDER BY stockmaster.discontinued, bom.parent";

	$ErrMsg = _('The parents for the selected part could not be retrieved because');;
	$result = DB_query($SQL,$ErrMsg);
	if (DB_num_rows($result)==0){
		echo prnMsg(_('The selected item') . ' ' . $StockID . ' ' . _('is not used as a component of any other parts'),'error');
	} else {

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
				<tr>
					<th>' . _('Used By') . '</th>
					<th>' . _('Status') . '</th>
					<th>' . _('Work Centre') . '</th>
					<th>' . _('Location') . '</th>
					<th>' . _('Quantity Required') . '</th>
					<th>' . _('Effective After') . '</th>
					<th>' . _('Effective To') . '</th>
				</tr>
			</thead>
			<tbody>';

		while ($myrow=DB_fetch_array($result)) {

			if ($myrow['discontinued'] == 1){
				$Status = _('In-active');
			}else{
				$Status = _('Active');
			}
			echo '<tr class="striped_row">
					<td><a target="_blank" class="btn btn-info" href="' . $RootPath . '/BOMInquiry.php?StockID=' . $myrow['parent'] . '" alt="' . _('Show Bill Of Material') . '">' . $myrow['parent']. ' - ' . $myrow['description']. '</a></td>
					<td>' . $Status. '</td>
					<td>' . $myrow['workcentreadded']. '</td>
					<td>' . $myrow['loccode']. '</td>
					<td>' . locale_number_format($myrow['quantity'],'Variable') . '</td>
					<td>' . ConvertSQLDate($myrow['effectiveafter']) . '</td>
					<td>' . ConvertSQLDate($myrow['effectiveto']) . '</td>
                </tr>';

			//end of page full new headings if
		}
		echo '</tbody></table></div></div></div>';
	}
} // StockID is set
echo '</form></div></div>';
include('includes/footer.php');
?>
