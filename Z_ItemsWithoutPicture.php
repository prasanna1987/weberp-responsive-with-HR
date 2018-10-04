<?php

/* Session started in session.php for password checking and authorisation level check
config.php is in turn included in session.php*/
include ('includes/session.php');
$Title = _('List of Items without picture');
include ('includes/header.php');

$SQL = "SELECT stockmaster.stockid,
			stockmaster.description,
			stockcategory.categorydescription
		FROM stockmaster, stockcategory
		WHERE stockmaster.categoryid = stockcategory.categoryid
			AND stockmaster.discontinued = 0
			AND stockcategory.stocktype != 'D'
		ORDER BY stockcategory.categorydescription, stockmaster.stockid";
$result = DB_query($SQL);
$PrintHeader = TRUE;

if (DB_num_rows($result) != 0){
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . _('Current Items without picture in nERP') . '</h1></a></div>';
	
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	$i = 1;
	$SupportedImgExt = array('png','jpg','jpeg');
	while ($myrow = DB_fetch_array($result)) {
		$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
		if(!file_exists($imagefile) ) {
			if($PrintHeader){
				$TableHeader = '<tr>
								<th>' . '#' . '</th>
								<th>' . _('Category') . '</th>
								<th>' . _('Item Code') . '</th>
								<th>' . _('Description') . '</th>
								</tr>';
				echo $TableHeader;
				$PrintHeader = FALSE;
			}

			$CodeLink = '<a href="' . $RootPath . '/SelectProduct.php?StockID=' . $myrow['stockid'] . '" target="_blank" class="btn btn-info">' . $myrow['stockid'] . '</a>';
			printf('<tr class="striped_row">
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					</tr>',
					$i,
					$myrow['categorydescription'],
					$CodeLink,
					$myrow['description']
					);
			$i++;
		}
	}
	echo '</table>
			</div></div></div>
			</form>';
}

include ('includes/footer.php');

?>