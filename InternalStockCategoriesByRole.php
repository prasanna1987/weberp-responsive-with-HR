<?php

/* Functionas as described in http://www.netelity.com/forum/showthread.php?tid=171 */

include('includes/session.php');
$Title = _('Internal Stock Categories Requests By Security Role Maintenance ');

include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['SelectedType'])){
	$SelectedType = mb_strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
	$SelectedType = mb_strtoupper($_GET['SelectedType']);
} else {
	$SelectedType='';
}

if (!isset($_GET['delete']) AND (ContainsIllegalCharacters($SelectedType) OR mb_strpos($SelectedType,' ')>0)){
	$InputError = 1;
	echo prnMsg(_('The Selected type cannot contain any of the following characters') . ' " \' - &amp; ' . _('or a space'),'error');
}
if (isset($_POST['SelectedRole'])){
	$SelectedRole = mb_strtoupper($_POST['SelectedRole']);
} elseif (isset($_GET['SelectedRole'])){
	$SelectedRole = mb_strtoupper($_GET['SelectedRole']);
}

if (isset($_POST['Cancel'])) {
	unset($SelectedRole);
	unset($SelectedType);
}

if (isset($_POST['Process'])) {

	if ($_POST['SelectedRole'] == '') {
		echo prnMsg(_('You have not selected a security role to maintain the internal stock categories on'),'error');
		echo '<br />';
		unset($SelectedRole);
		unset($_POST['SelectedRole']);
	}
}

if (isset($_POST['submit'])) {

	$InputError=0;

	if ($_POST['SelectedCategory']=='') {
		$InputError=1;
		echo prnMsg(_('You have not selected a stock category to be added as internal to this security role'),'error');
		echo '<br />';
		unset($SelectedRole);
	}

	if ( $InputError !=1 ) {

		// First check the type is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM internalstockcatrole
			     WHERE secroleid= '" .  $_POST['SelectedRole'] . "'
				 AND categoryid = '" .  $_POST['SelectedCategory'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] >0) {
			$InputError = 1;
			echo prnMsg( _('The Stock Category') . ' ' . $_POST['categoryid'] . ' ' ._('already allowed as internal for this security role'),'error');
		} else {
			// Add new record on submit
			$sql = "INSERT INTO internalstockcatrole (secroleid,
												categoryid)
										VALUES ('" . $_POST['SelectedRole'] . "',
												'" . $_POST['SelectedCategory'] . "')";

			$msg = _('Stock Category:') . ' ' . $_POST['SelectedCategory'].' '._('has been allowed to user role') .' '. $_POST['SelectedRole'] .  ' ' . _('as internal');
			$checkSql = "SELECT count(secroleid)
							FROM securityroles";
			$result = DB_query($checkSql);
			$row = DB_fetch_row($result);
		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql);
		echo prnMsg($msg,'success');
		unset($_POST['SelectedCategory']);
	}

} elseif ( isset($_GET['delete']) ) {
	$sql="DELETE FROM internalstockcatrole
		WHERE secroleid='".$SelectedRole."'
		AND categoryid='".$SelectedType."'";

	$ErrMsg = _('The Stock Category by Role record could not be deleted because');
	$result = DB_query($sql,$ErrMsg);
	echo prnMsg(_('Internal Stock Category').' '. $SelectedType .' '. _('for user role').' '. $SelectedRole .' '. _('has been deleted') ,'success');
	unset($_GET['delete']);
}

if (!isset($SelectedRole)){

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
  
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="row">'; //Main table

	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select User Role') . '</label>
<select name="SelectedRole" class="form-control">';

	$SQL = "SELECT secroleid,
					secrolename
			FROM securityroles";

	$result = DB_query($SQL);
	echo '<option value="">' . _('Not Yet Selected') . '</option>';
	while ($myrow = DB_fetch_array($result)) {
		if (isset($SelectedRole) AND $myrow['secroleid']==$SelectedRole) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $myrow['secroleid'] . '">' . $myrow['secroleid'] . ' - ' . $myrow['secrolename'] . '</option>';

	} //end while loop

	echo '</select></div></div>';

  
    DB_free_result($result);

	echo '<div class="col-xs-4">
<div class="form-group"><br />
<input type="submit" class="btn btn-success" name="Process" value="' . _('Submit') . '" /></div></div>
<div class="col-xs-4">
<div class="form-group"><br />
				</div>';

	echo '</div></div><br />

          </form>';

}

//end of ifs and buts!
if (isset($_POST['process'])OR isset($SelectedRole)) {

	
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
  
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<input type="hidden" name="SelectedRole" value="' . $SelectedRole . '" />';

	$sql = "SELECT internalstockcatrole.categoryid,
					stockcategory.categorydescription
			FROM internalstockcatrole INNER JOIN stockcategory
			ON internalstockcatrole.categoryid=stockcategory.categoryid
			WHERE internalstockcatrole.secroleid='".$SelectedRole."'
			ORDER BY internalstockcatrole.categoryid ASC";

	$result = DB_query($sql);

	echo '<br />
			<div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Internal Stock Categories Allowed to user role') . ' ' .$SelectedRole. '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	
	echo '<thead>
	<tr>
			<th>' . _('Category Code') . '</th>
			<th>' . _('Description') . '</th>
			<th>' . _('Action') . '</th>
		</tr></thead>';

while ($myrow = DB_fetch_array($result)) {

	printf('<tr class="striped_row">
			<td>%s</td>
			<td>%s</td>
			<td><a href="%s?SelectedType=%s&amp;delete=yes&amp;SelectedRole=' . $SelectedRole . '" onclick="return confirm(\'' . _('Are you sure you wish to delete this internal stock category code?') . '\');" class="btn btn-danger">' . _('Delete') . '</a></td>
			</tr>',
			$myrow['categoryid'],
			$myrow['categorydescription'],
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'),
			$myrow['categoryid'],
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'),
			$myrow['categoryid']);
	}
	//END WHILE LIST LOOP
	echo '</table></div></div></div></div>';

	if (! isset($_GET['delete'])) {


		echo '<br /><div class="row">'; //Main table

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select Stock Category Code') . '</label>
<select name="SelectedCategory" class="form-control">';

		$SQL = "SELECT categoryid,
						categorydescription
				FROM stockcategory";

		$result = DB_query($SQL);
		if (!isset($_POST['SelectedCategory'])){
			echo '<option selected="selected" value="">' . _('Not Yet Selected') . '</option>';
		}
		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['SelectedCategory']) AND $myrow['categoryid']==$_POST['SelectedCategory']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['categoryid'] . '">' . $myrow['categoryid'] . ' - ' . $myrow['categorydescription'] . '</option>';

		} //end while loop

		echo '</select></div></div>';

	   
        DB_free_result($result);

		echo '<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" name="submit" class="btn btn-success" value="' . _('Accept') . '" /></div></div>
<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" name="Cancel" class="btn btn-danger" value="' . _('Cancel') . '" /></div></div>';

		echo '</div><br />

              </form>';

	} // end if user wish to delete
}

include('includes/footer.php');
?>