<?php

include('includes/session.php');
$Title = _('Tax Categories');
$ViewTopic = 'Tax';// Filename in ManualContents.php's TOC.
$BookMark = 'TaxCategories';// Anchor's id in the manual's html document.
include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' .
		_('Tax Category Maintenance') . '</h1></a></div>';

if( isset($_GET['SelectedTaxCategory']) )
	$SelectedTaxCategory = $_GET['SelectedTaxCategory'];
elseif(isset($_POST['SelectedTaxCategory']))
	$SelectedTaxCategory = $_POST['SelectedTaxCategory'];

if(isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test

	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if(ContainsIllegalCharacters($_POST['TaxCategoryName'])) {
		$InputError = 1;
		echo prnMsg( _('The tax category name cannot contain the character') . " '&amp;' " . _('or the character') ." ' " . _('or a space') ,'error');
	}
	if(trim($_POST['TaxCategoryName']) == '') {
		$InputError = 1;
		echo prnMsg( _('The tax category name may not be empty'), 'error');
	}

	if($_POST['SelectedTaxCategory']!='' AND $InputError !=1) {

		/*SelectedTaxCategory could also exist if submit had not been clicked this code would not run in this case cos submit is false of course  see the delete code below*/
		// Check the name does not clash
		$sql = "SELECT count(*) FROM taxcategories
				WHERE taxcatid <> '" . $SelectedTaxCategory ."'
				AND taxcatname ".LIKE." '" . $_POST['TaxCategoryName'] . "'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if( $myrow[0] > 0 ) {
			$InputError = 1;
			echo prnMsg( _('The tax category cannot be renamed because another with the same name already exists.'),'error');
		} else {
			// Get the old name and check that the record still exists

			$sql = "SELECT taxcatname FROM taxcategories
					WHERE taxcatid = '" . $SelectedTaxCategory . "'";
			$result = DB_query($sql);
			if( DB_num_rows($result) != 0 ) {
				// This is probably the safest way there is
				$myrow = DB_fetch_row($result);
				$OldTaxCategoryName = $myrow[0];
				$sql = "UPDATE taxcategories
						SET taxcatname='" . $_POST['TaxCategoryName'] . "'
						WHERE taxcatname ".LIKE." '".$OldTaxCategoryName."'";
				$ErrMsg = _('The tax category could not be updated');
				$result = DB_query($sql,$ErrMsg);
			} else {
				$InputError = 1;
				echo prnMsg( _('The tax category no longer exists'),'error');
			}
		}
		$msg = _('Tax category name changed');
	} elseif($InputError !=1) {
		/*SelectedTaxCategory is null cos no item selected on first time round so must be adding a record*/
		$sql = "SELECT count(*) FROM taxcategories
				WHERE taxcatname " .LIKE. " '".$_POST['TaxCategoryName'] ."'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if( $myrow[0] > 0 ) {
			$InputError = 1;
			echo prnMsg( _('The tax category cannot be created because another with the same name already exists'),'error');
		} else {
			$result = DB_Txn_Begin();
			$sql = "INSERT INTO taxcategories (
						taxcatname )
					VALUES (
						'" . $_POST['TaxCategoryName'] ."'
						)";
			$ErrMsg = _('The new tax category could not be added');
			$result = DB_query($sql,$ErrMsg,true);

			$LastTaxCatID = DB_Last_Insert_ID('taxcategories','taxcatid');

			$sql = "INSERT INTO taxauthrates (taxauthority,
					dispatchtaxprovince,
					taxcatid)
				SELECT taxauthorities.taxid,
 					taxprovinces.taxprovinceid,
					'" . $LastTaxCatID . "'
				FROM taxauthorities CROSS JOIN taxprovinces";
			$result = DB_query($sql,$ErrMsg,true);

			$result = DB_Txn_Commit();
		}
		$msg = _('New tax category added');
	}

	if($InputError!=1) {
		echo prnMsg($msg,'success');
	}
	unset ($SelectedTaxCategory);
	unset ($_POST['SelectedTaxCategory']);
	unset ($_POST['TaxCategoryName']);

} elseif(isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button
// PREVENT DELETES IF DEPENDENT RECORDS IN 'stockmaster'
	// Get the original name of the tax category the ID is just a secure way to find the tax category
	$sql = "SELECT taxcatname FROM taxcategories
		WHERE taxcatid = '" . $SelectedTaxCategory . "'";
	$result = DB_query($sql);
	if( DB_num_rows($result) == 0 ) {
		// This is probably the safest way there is
		echo prnMsg( _('Cannot delete this tax category because it no longer exists'),'warn');
	} else {
		$myrow = DB_fetch_array($result);
		$TaxCatName = $myrow['taxcatname'];
		$sql= "SELECT COUNT(*) FROM stockmaster WHERE taxcatid = '" . $SelectedTaxCategory . "'";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if($myrow[0]>0) {
			echo prnMsg( _('Cannot delete this tax category because inventory items have been created using this tax category'),'warn');
			echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('inventory items that refer to this tax category');
		} else {
			$sql = "DELETE FROM taxauthrates WHERE taxcatid  = '" . $SelectedTaxCategory . "'";
			$result = DB_query($sql);
			$sql = "DELETE FROM taxcategories WHERE taxcatid = '" . $SelectedTaxCategory . "'";
			$result = DB_query($sql);
			echo prnMsg( $TaxCatName . ' ' . _('tax category and any tax rates set for it have been deleted'),'success');
		}
	} //end if
	unset ($SelectedTaxCategory);
	unset ($_GET['SelectedTaxCategory']);
	unset($_GET['delete']);
	unset ($_POST['SelectedTaxCategory']);
	unset ($_POST['TaxCategoryName']);
}

 if(!isset($SelectedTaxCategory)) {

/* An tax category could be posted when one has been edited and is being updated
  or GOT when selected for modification
  SelectedTaxCategory will exist because it was sent with the page in a GET .
  If its the first time the page has been displayed with no parameters
  then none of the above are true and the list of account groups will be displayed with
  links to delete or edit each. These will call the same page again and allow update/input
  or deletion of the records*/

	$sql = "SELECT taxcatid,
			taxcatname
			FROM taxcategories
			ORDER BY taxcatid";

	$ErrMsg = _('Could not get tax categories because');
	$result = DB_query($sql,$ErrMsg);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">

		<thead>
			<tr>
				<th class="ascending">' . _('Tax Category') . '</th>
				<th colspan="2">' . _('Actions') . '</th>
			</tr>
		</thead>
		<tbody>';

	while($myrow = DB_fetch_row($result)) {
		echo '<tr class="striped_row">';

		if($myrow[1]!='Freight') {
			// Uses gettext() to translate 'Exempt' and 'Handling':
			echo '<td>' . _($myrow[1]) . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?SelectedTaxCategory=' . $myrow[0] . '" class="btn btn-info">' . _('Edit') . '</a></td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?SelectedTaxCategory=' . $myrow[0] . '&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this tax category?') . '\');">' .
					_('Delete')  . '</a></td>';
		} else {
			echo '<td>' . _($myrow[1]) . '</td><td>&nbsp;</td><td>&nbsp;</td>';// Uses gettext() to translate 'Freight'.
		}
		echo '</tr>';
	} //END WHILE LIST LOOP

	echo '</tbody></table></div></div></div>';
} //end of ifs and buts!


if(isset($SelectedTaxCategory)) {
	echo '<div class="row">
	<div class="col-xs-4">
			<a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Back to Tax Categories') . '</a>
		</div></div>';
}

echo '<br />';

if(! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';

	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if(isset($SelectedTaxCategory)) {
		//editing an existing section

		$sql = "SELECT taxcatid,
				taxcatname
				FROM taxcategories
				WHERE taxcatid='" . $SelectedTaxCategory . "'";

		$result = DB_query($sql);
		if( DB_num_rows($result) == 0 ) {
			echo prnMsg( _('Could not retrieve the requested tax category, please try again.'),'warn');
			unset($SelectedTaxCategory);
		} else {
			$myrow = DB_fetch_array($result);

			$_POST['TaxCategoryName']  = $myrow['taxcatname'];

			echo '<input type="hidden" name="SelectedTaxCategory" value="' . $myrow['taxcatid'] . '" />';
			echo '<div class="row">';
		}

	}  else {
		$_POST['TaxCategoryName']='';
		echo '<div class="row">';
	}
	echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Tax Category Name') . ':' . '</label>
			<input pattern="(?!^ +$)[^><+-]+" required="required" placeholder="'._('No more than 30 characters').'" type="text" title="'._('No illegal characters allowed and cannot be blank').'" class="form-control" name="TaxCategoryName" size="30" maxlength="30" value="' . $_POST['TaxCategoryName'] . '" /></div>
		</div>
		';

	echo '<div class="col-xs-4">
<div class="form-group"> <br />

				<input type="submit" class="btn btn-success" name="submit" value="' . _('Enter Information') . '" />
			</div>
        </div>
		 </div><br />

		</form>';

} //end if record deleted no point displaying form to add record

echo '<br />
	<div class="row">
<div class="col-xs-4">		<a href="' . $RootPath . '/TaxAuthorities.php" class="btn btn-info">' . _('Tax Authorities and Rates Maintenance') .  '</a></div>
<div class="col-xs-4">		<a href="' . $RootPath . '/TaxGroups.php" class="btn btn-info">' . _('Tax Group Maintenance') .  '</a></div>
<div class="col-xs-4">		<a href="' . $RootPath . '/TaxProvinces.php" class="btn btn-info">' . _('Dispatch Tax Province Maintenance') .  '</a></div>
	</div><br />
';

include('includes/footer.php');
?>
