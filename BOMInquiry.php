<?php


include('includes/session.php');
$Title = _('Costed Bill Of Material');
include('includes/header.php');

if (isset($_GET['StockID'])){
	$StockID =trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	$StockID =trim(mb_strtoupper($_POST['StockID']));
}

if (!isset($_POST['StockID'])) {
	echo '<div class="row"><p class="text-info">
			<strong>'. _('Select only a manufactured part') . ' (' . _('or Assembly or Kit part') . ') ' . _('to view the costed bill of materials') . '
			</strong></p>
		</div>
		<div class="row gutter30">
<div class="col-xs-12">
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
       
	   		<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Description-part or full') . '</label>
			<input tabindex="1" class="form-control" type="text" autofocus="autofocus" name="Keywords" size="20" maxlength="25" /></div></div>
			
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Stock Code-part or full') . ' </label>
			<input tabindex="2" class="form-control" type="text" name="StockCode" size="15" maxlength="20" /></div>
		</div>
		
		<div class="col-xs-4">
<div class="form-group"> <br />
			<input tabindex="3" class="btn btn-info" type="submit" name="Search" value="' . _('Search Now') . '" />
		</div>
		</div>
		</div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
}

if (isset($_POST['Search'])){
	// Work around to auto select
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		$_POST['StockCode']='%';
	}
	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		echo prnMsg( _('Stock description keywords have been used in preference to the Stock code extract entered'), 'info' );
	}
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		echo prnMsg( _('At least one stock description keyword or an extract of a stock code must be entered for the search'), 'info' );
	} else {
		if (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$sql = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.mbflag,
							SUM(locstock.quantity) as totalonhand
					FROM stockmaster INNER JOIN locstock
					ON stockmaster.stockid = locstock.stockid
					WHERE stockmaster.description " . LIKE . "'" . $SearchString . "'
					AND (stockmaster.mbflag='M'
						OR stockmaster.mbflag='K'
						OR stockmaster.mbflag='A'
						OR stockmaster.mbflag='G')
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.units,
						stockmaster.mbflag
					ORDER BY stockmaster.stockid";

		} elseif (mb_strlen($_POST['StockCode'])>0){
			$sql = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.mbflag,
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
						stockmaster.mbflag
					ORDER BY stockmaster.stockid";

		}

		$ErrMsg = _('The SQL to find the parts selected failed with the message');
		$result = DB_query($sql,$ErrMsg);

	} //one of keywords or StockCode was more than a zero length string
} //end of if search

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
		if ($myrow['mbflag']=='A' OR $myrow['mbflag']=='K'){
			$StockOnHand = 'N/A';
		} else {
			$StockOnHand = locale_number_format($myrow['totalonhand'],2);
		}
		$tabindex=$j+4;
		printf('<tr class="striped_row">
				<td><input tabindex="' .$tabindex . '" type="submit" name="StockID" value="%s" class="btn btn-warning" /></td>
		        <td>%s</td>
				<td>%s</td>
				<td>%s</td>
				</tr>',
				$myrow['stockid'],
				$myrow['description'],
				$StockOnHand,
				$myrow['units'] );
		$j++;
//end of page full new headings if
	}
//end of while loop

	echo '</table></div></div></div>';
}
if (!isset($_POST['StockID'])) {
    echo '
          </form></div></div>';
}

if (isset($StockID) and $StockID!=""){

	$result = DB_query("SELECT description,
								units,
								labourcost,
								overheadcost
						FROM stockmaster
						WHERE stockid='" . $StockID  . "'");
	$myrow = DB_fetch_array($result);
	$ParentLabourCost = $myrow['labourcost'];
	$ParentOverheadCost = $myrow['overheadcost'];

	$sql = "SELECT bom.parent,
					bom.component,
					stockmaster.description,
					stockmaster.decimalplaces,
					stockmaster.materialcost+ stockmaster.labourcost+stockmaster.overheadcost as standardcost,
					bom.quantity,
					bom.quantity * (stockmaster.materialcost+ stockmaster.labourcost+ stockmaster.overheadcost) AS componentcost
			FROM bom INNER JOIN stockmaster
			ON bom.component = stockmaster.stockid
			WHERE bom.parent = '" . $StockID . "'
            AND bom.effectiveafter <= '" . date('Y-m-d') . "'
            AND bom.effectiveto > '" . date('Y-m-d') . "'";

	$ErrMsg = _('The bill of material could not be retrieved because');
	$BOMResult = DB_query ($sql,$ErrMsg);

	if (DB_num_rows($BOMResult)==0){
		echo prnMsg(_('The bill of material for this part is not set up') . ' - ' . _('there are no components defined for it'),'warn');
	} else {
		echo '<br /><p align="right"><a href="' . $RootPath . '/menu_data.php?Application=manuf" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title.'
				</h1></a></div>
				<br />';

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h2>' . $myrow[0] . ' : ' . _('per') . ' ' . $myrow[1] . '</h2></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		
		$TableHeader = '<tr>
							<th>' . _('Component') . '</th>
							<th>' . _('Description') . '</th>
							<th>' . _('Quantity') . '</th>
							<th>' . _('Unit Cost') . '</th>
							<th>' . _('Total Cost') . '</th>
						</tr>';
		echo $TableHeader;

		$j = 1;

		$TotalCost = 0;

		while ($myrow=DB_fetch_array($BOMResult)) {

			$ComponentLink = '<a href="' . $RootPath . '/SelectProduct.php?StockID=' . $myrow['component'] . '" class="btn btn-warning">' . $myrow['component'] . '</a>';

			/* Component Code  Description  Quantity Std Cost  Total Cost */
			printf('<tr class="striped_row">
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					</tr>',
					$ComponentLink,
					$myrow['description'],
					locale_number_format($myrow['quantity'],$myrow['decimalplaces']),
					locale_number_format($myrow['standardcost'],$_SESSION['CompanyRecord']['decimalplaces'] + 2),
					locale_number_format($myrow['componentcost'],$_SESSION['CompanyRecord']['decimalplaces'] + 2));

			$TotalCost += $myrow['componentcost'];

			$j++;
		}

		$TotalCost += $ParentLabourCost;
		echo '<tr>
			<td colspan="4" class="number"><b>' . _('Labour Cost') . '</b></td>
			<td><b>' . locale_number_format($ParentLabourCost,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></td></tr>';
		$TotalCost += $ParentOverheadCost;
		echo '<tr><td colspan="4" class="number"><b>' . _('Overhead Cost') . '</b></td>
			<td><b>' . locale_number_format($ParentOverheadCost,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></td></tr>';

		echo '<tr>
				<td colspan="4" class="number"><b>' . _('Total Cost') . '</b></td>
				<td><b>' . locale_number_format($TotalCost,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></td>
			</tr>';

		echo '</table></div></div></div></div>';
	}
} else { //no stock item entered
	//prnMsg(_('Enter a stock item code above') . ', ' . _('to view the costed bill of material for'),'info');
}

include('includes/footer.php');
?>