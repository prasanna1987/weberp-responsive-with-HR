<?php

include ('includes/session.php');
$Title = _('Select an Asset');

$ViewTopic = 'FixedAssets';
$BookMark = 'AssetSelection';

include ('includes/header.php');

if (isset($_GET['AssetID'])) {
	//The page is called with a AssetID
	$_POST['Select'] = $_GET['AssetID'];
}

if (isset($_GET['NewSearch']) OR isset($_POST['Next']) OR isset($_POST['Previous']) OR isset($_POST['Go'])) {
	unset($AssetID);
	unset($_SESSION['SelectedAsset']);
	unset($_POST['Select']);
}
if (!isset($_POST['PageOffset'])) {
	$_POST['PageOffset'] = 1;
} else {
	if ($_POST['PageOffset'] == 0) {
		$_POST['PageOffset'] = 1;
	}
}
if (isset($_POST['AssetCode'])) {
	$_POST['AssetCode'] = trim(mb_strtoupper($_POST['AssetCode']));
}
// Always show the search facilities
$SQL = "SELECT categoryid,
				categorydescription
			FROM fixedassetcategories
			ORDER BY categorydescription";
$result = DB_query($SQL);
if (DB_num_rows($result) == 0) {
	echo '<p class="text-danger"><strong>' . _('Problem Report') . ':</strong> ' . _('There are no asset categories currently defined please use the link below to set them up');
	echo '</p><br /><div class="row">
<div class="col-xs-4"><a href="' . $RootPath . '/FixedAssetCategories.php" class="btn btn-info">' . _('Define Asset Categories') . '</a></div></div><br />';
	exit;
}
// end of showing search facilities

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>
		<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Asset Category') . '</label>
			<td><select name="AssetCategory" class="form-control">';

if (!isset($_POST['AssetCategory'])) {
	$_POST['AssetCategory'] = 'ALL';
}
if ($_POST['AssetCategory']=='ALL'){
	echo '<option selected="selected" value="ALL">' . _('Any asset category') . '</option>';
} else {
	echo '<option value="ALL">' . _('Any asset category') . '</option>';
}

while ($myrow = DB_fetch_array($result)) {
	if ($myrow['categoryid'] == $_POST['AssetCategory']) {
		echo '<option selected="selected" value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	}
}
echo '</select></div></div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Description-part or full') . '</label>
	';
if (isset($_POST['Keywords'])) {
	echo '<input type="text" class="form-control" name="Keywords" autofocus="autofocus" value="' . $_POST['Keywords'] . '" size="20" maxlength="25" />';
} else {
	echo '<input type="text" class="form-control" name="Keywords" autofocus="autofocus" size="20" maxlength="25" />';
}
echo '</div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Asset Location') . '</label>
		<select name="AssetLocation" class="form-control">';

if (!isset($_POST['AssetLocation'])) {
	$_POST['AssetLocation'] = 'ALL';
}
if ($_POST['AssetLocation']=='ALL'){
	echo '<option selected="selected" value="ALL">' . _('Any asset location') . '</option>';
} else {
	echo '<option value="ALL">' . _('Any asset location') . '</option>';
}
$result = DB_query("SELECT locationid, locationdescription FROM fixedassetlocations");

while ($myrow = DB_fetch_array($result)) {
	if ($myrow['locationid'] == $_POST['AssetLocation']) {
		echo '<option selected="selected" value="' . $myrow['locationid'] . '">' . $myrow['locationdescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow['locationid'] . '">' . $myrow['locationdescription'] . '</option>';
	}
}
echo '</select>';

echo '  </div></div></div>
		<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Asset ID-part or full') . '</label>
		';
if (isset($_POST['AssetCode'])) {
	echo '<input type="text" class="form-control" name="AssetCode" value="' . $_POST['AssetCode'] . '" size="15" maxlength="13" />';
} else {
	echo '<input type="text" name="AssetCode" class="form-control" size="15" maxlength="13" />';
}
echo '</div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <br />
		<input type="submit" class="btn btn-success" name="Search" value="' . _('Search') . '" />
	</div></div></div>
	<br />
   ';

// query for list of record(s)
if(isset($_POST['Go']) OR isset($_POST['Next']) OR isset($_POST['Previous'])) {
	$_POST['Search']='Search';
}
if (isset($_POST['Search']) OR isset($_POST['Go']) OR isset($_POST['Next']) OR isset($_POST['Previous'])) {
	if (!isset($_POST['Go']) AND !isset($_POST['Next']) AND !isset($_POST['Previous'])) {
		// if Search then set to first page
		$_POST['PageOffset'] = 1;
	}
	if ($_POST['Keywords'] AND $_POST['AssetCode']) {
		echo prnMsg( _('Asset description keywords have been used in preference to the asset code extract entered'), 'info' );
	}
	$SQL = "SELECT assetid,
					description,
					datepurchased,
					fixedassetlocations.locationdescription
			FROM fixedassets INNER JOIN fixedassetlocations
			ON fixedassets.assetlocation=fixedassetlocations.locationid ";

	if ($_POST['Keywords']) {
		//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';
		if ($_POST['AssetCategory'] == 'ALL') {
			if ($_POST['AssetLocation']=='ALL'){
				$SQL .= "WHERE description " . LIKE .  "'" . $SearchString . "'
						ORDER BY fixedassets.assetid";
			} else {
				$SQL .= "WHERE fixedassets.assetlocation='" . $_POST['AssetLocation'] . "'
						AND description " . LIKE .  "'" . $SearchString . "'
						ORDER BY fixedassets.assetid";
			}
		} else {
			if ($_POST['AssetLocation']=='ALL'){
				$SQL .= "WHERE description " . LIKE .  "'" . $SearchString . "'
						AND  assetcategoryid='" . $_POST['AssetCategory'] . "'
						ORDER BY fixedassets.assetid";
			} else {
				$SQL .= "WHERE fixedassets.assetlocation='" . $_POST['AssetLocation'] . "'
						AND description " . LIKE .  "'" . $SearchString . "'
						AND  assetcategoryid='" . $_POST['AssetCategory'] . "'
						ORDER BY fixedassets.assetid";
			}
		}
	} elseif (isset($_POST['AssetCode'])) {
		if ($_POST['AssetCategory'] == 'ALL') {
			if ($_POST['AssetLocation']=='ALL'){
				$SQL .= "WHERE fixedassets.assetid " . LIKE . " '%" . $_POST['AssetCode'] . "%'
						ORDER BY fixedassets.assetid";
			} else {
				$SQL .= "WHERE fixedassets.assetlocation='" . $_POST['AssetLocation'] . "'
						AND fixedassets.assetid " . LIKE . " '%" . $_POST['AssetCode'] . "%'
						ORDER BY fixedassets.assetid";
			}
		} else {
			if ($_POST['AssetLocation']=='ALL'){
				$SQL .= "WHERE fixedassets.assetid " . LIKE . " '%" . $_POST['AssetCode'] . "%'
						AND  assetcategoryid='" . $_POST['AssetCategory'] . "'
						ORDER BY fixedassets.assetid";
			} else {
				$SQL .= "WHERE fixedassets.assetlocation='" . $_POST['AssetLocation'] . "'
						AND fixedassets.assetid " . LIKE . " '%" . $_POST['AssetCode'] . "%'
						AND  assetcategoryid='" . $_POST['AssetCategory'] . "'
						ORDER BY fixedassets.assetid";
			}
		}
	} elseif (!isset($_POST['AssetCode']) AND !isset($_POST['Keywords'])) {
		if ($_POST['AssetCategory'] == 'All') {
			if ($_POST['AssetLocation']=='ALL'){
				$SQL .= 'ORDER BY fixedassets.assetid';
			} else {
				$SQL .= "WHERE fixedassets.assetlocation='" . $_POST['AssetLocation'] . "'
						ORDER BY fixedassets.assetid";
			}
		} else {
			if ($_POST['AssetLocation']=='ALL'){
				$SQL .= "WHERE assetcategoryid='" . $_POST['AssetCategory'] . "'
						ORDER BY fixedassets.assetid";
			} else {
				$SQL .= "WHERE assetcategoryid='" . $_POST['AssetCategory'] . "'
						AND fixedassets.assetlocation='" . $_POST['AssetLocation'] . "'
						ORDER BY fixedassets.assetid";
			}
		}
	}

	$ErrMsg = _('No assets were returned by the SQL because');
	$DbgMsg = _('The SQL that returned an error was');
	$SearchResult = DB_query($SQL, $ErrMsg, $DbgMsg);

	if (DB_num_rows($SearchResult) == 0) {
		echo prnMsg(_('No assets were returned by this search please re-enter alternative criteria to try again'), 'info');
	}
	unset($_POST['Search']);
}
/* end query for list of records */
/* display list if there is more than one record */
if (isset($SearchResult) AND !isset($_POST['Select'])) {
	$ListCount = DB_num_rows($SearchResult);
	if ($ListCount > 0) {
		// If the user hit the search button and there is more than one item to show
		$ListPageMax = ceil($ListCount / $_SESSION['DisplayRecordsMax']);
		if (isset($_POST['Next'])) {
			if ($_POST['PageOffset'] < $ListPageMax) {
				$_POST['PageOffset'] ++;
			}
		}
		if (isset($_POST['Previous'])) {
			if ($_POST['PageOffset'] > 1) {
				$_POST['PageOffset']--;
			}
		}
		if ($_POST['PageOffset'] > $ListPageMax) {
			$_POST['PageOffset'] = $ListPageMax;
		}
		if ($ListPageMax > 1) {
			echo '<div class="row"><div class="col-xs-3">' . $_POST['PageOffset'] . ' ' . _('of') . ' ' . $ListPageMax . ' ' . _('pages') . '. ' . _('Go to Page') . ': ';
			echo '<select name="PageOffset" class="form-control">';
			$ListPage = 1;
			while ($ListPage <= $ListPageMax) {
				if ($ListPage == $_POST['PageOffset']) {
					echo '<option value="' . $ListPage . '" selected="selected">' . $ListPage . '</option>';
				} else {
					echo '<option value="' . $ListPage . '">' . $ListPage . '</option>';
				}
				$ListPage++;
			}
			echo '</select></div>
			<div class="col-xs-3">
				<input type="submit" class="btn btn-info" name="Go" value="' . _('Submit') . '" />
				</div>
				<div class="col-xs-3">
				<input type="submit" class="btn btn-default" name="Previous" value="' . _('Previous') . '" />
				</div>
				<div class="col-xs-3">
				<input type="submit" class="btn btn-default" name="Next" value="' . _('Next') . '" />';

			echo '</div></div><br />';
		}
		echo '</form>';

		echo '<form action="FixedAssetItems.php" method="post">';
		
		echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		$tableheader = '<thead>
		<tr>
					<th>' . _('Asset Code') . '</th>
					<th>' . _('Description') . '</th>
					<th>' . _('Asset Location') . '</th>
					<th>' . _('Date Purchased') . '</th>
				</tr></thead>';
		echo $tableheader;
		$j = 1;
		$RowIndex = 0;
		if (DB_num_rows($SearchResult) <> 0) {
			DB_data_seek($SearchResult, ($_POST['PageOffset'] - 1) * $_SESSION['DisplayRecordsMax']);
		}
		while (($myrow = DB_fetch_array($SearchResult)) AND ($RowIndex <> $_SESSION['DisplayRecordsMax'])) {
			echo '<tr class="striped_row">
				<td><input type="submit" class="btn btn-info" name="Select" value="' . $myrow['assetid'] .'" /></td>
				<td>' . $myrow['description'] . '</td>
				<td>' . $myrow['locationdescription'] . '</td>
				<td>' . ConvertSQLDate($myrow['datepurchased']) . '</td>
				</tr>';
			$j++;
			if ($j == 20 AND ($RowIndex + 1 != $_SESSION['DisplayRecordsMax'])) {
				$j = 1;
				echo $tableheader;
			}
			$RowIndex = $RowIndex + 1;
			//end of page full new headings if
		}
		//end of while loop
		echo '</table>';
		echo '</div></div></div>
          </form>';
	} // there were records to list

}
/* end display list if there is more than one record */
include ('includes/footer.php');
?>
