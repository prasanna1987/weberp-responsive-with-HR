<?php


include('includes/session.php');

$Title = _('Discount Categories Maintenance');
/* nERP manual links before header.php */
$ViewTopic= "SalesOrders";
$BookMark = "DiscountMatrix";
include('includes/header.php');
echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_POST['stockID'])) {
	$_POST['StockID']=$_POST['stockID'];
} elseif (isset($_GET['StockID'])) {
	$_POST['StockID']=$_GET['StockID'];
	$_POST['ChooseOption']=1;
	$_POST['SelectChoice']=1;
}

if (isset($_POST['submit']) and !isset($_POST['SubmitCategory'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	$result = DB_query("SELECT stockid
						FROM stockmaster
						WHERE mbflag <>'K'
						AND mbflag<>'D'
						AND stockid='" . mb_strtoupper($_POST['StockID']) . "'");
	if (DB_num_rows($result)==0){
		$InputError = 1;
		echo prnMsg(_('The stock item entered must be set up as either a manufactured or purchased or assembly item'),'warn');
	}

	if ($InputError !=1) {

		$sql = "UPDATE stockmaster SET discountcategory='" . $_POST['DiscountCategory'] . "'
				WHERE stockid='" . mb_strtoupper($_POST['StockID']) . "'";

		$result = DB_query($sql, _('The discount category') . ' ' . $_POST['DiscountCategory'] . ' ' . _('record for') . ' ' . mb_strtoupper($_POST['StockID']) . ' ' . _('could not be updated because'));

		echo prnMsg(_('The stock master has been updated with this discount category'),'success');
		unset($_POST['DiscountCategory']);
		unset($_POST['StockID']);
	}


} elseif (isset($_GET['Delete']) and $_GET['Delete']=='yes') {
/*the link to delete a selected record was clicked instead of the submit button */

	$sql="UPDATE stockmaster SET discountcategory='' WHERE stockid='" . trim(mb_strtoupper($_GET['StockID'])) ."'";
	$result = DB_query($sql);
	echo prnMsg( _('The stock master has been updated to no discount category'),'success');
	echo '<br />';
} elseif (isset($_POST['SubmitCategory'])) {
	$sql = "SELECT stockid FROM stockmaster WHERE categoryid='".$_POST['stockcategory']."'";
	$ErrMsg = _('Failed to retrieve stock category data');
	$result = DB_query($sql,$ErrMsg);
	if(DB_num_rows($result)>0){
		$sql="UPDATE stockmaster
				SET discountcategory='".$_POST['DiscountCategory']."'
				WHERE categoryid='".$_POST['stockcategory']."'";
		$result=DB_query($sql);
	}else{
		echo prnMsg(_('There are no stock defined for this stock category, you must define stock for it first'),'error');
		include('includes/footer.php');
		exit;
	}
}

if (isset($_POST['SelectChoice'])) {
	echo '<form id="update" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
    
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	$sql = "SELECT DISTINCT discountcategory FROM stockmaster WHERE discountcategory <>''";
	$result = DB_query($sql);
	if (DB_num_rows($result) > 0) {
		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' .  _('Discount Category Code') .'</label>';

		echo '<select name="DiscCat" onchange="ReloadForm(update.select)" class="form-control">';

		while ($myrow = DB_fetch_array($result)){
			if ($myrow['discountcategory']==$_POST['DiscCat']){
				echo '<option selected="selected" value="' . $myrow['discountcategory'] . '">' . $myrow['discountcategory']  . '</option>';
			} else {
				echo '<option value="' . $myrow['discountcategory'] . '">' . $myrow['discountcategory'] . '</option>';
			}
		}

		echo '</select></div></div>';
		echo '<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" class="btn btn-info" name="select" value="'._('Select').'" /></div>
			</div>
			</div>
			<br />';
	}
    echo '
          </form>';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';

	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<input type="hidden" name="ChooseOption" value="'.$_POST['ChooseOption'].'" />';
	echo '<input type="hidden" name="SelectChoice" value="'.$_POST['SelectChoice'].'" />';

	if (isset($_POST['ChooseOption']) and $_POST['ChooseOption']==1) {
		echo '<div class="row">
				<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' .  _('Discount Category Code') .'</label>
					';

		if (isset($_POST['DiscCat'])) {
			echo '<input type="text" class="form-control" required="required" name="DiscountCategory" pattern="[0-9a-zA-Z_]*" title="' . _('Enter the discount category up to 2 alpha-numeric characters') . '" maxlength="2" size="2" value="' . $_POST['DiscCat'] .'" /></div></div>
				';
		} else {
			echo '<input type="text" class="form-control" name="DiscountCategory" required="required" name="DiscountCategory" pattern="[0-9a-zA-Z_]*" title="' . _('Enter the discount category up to 2 alpha-numeric characters') . '" maxlength="2" size="2" /></div></div>
				';
		}

		if (!isset($_POST['StockID'])) {
			$_POST['StockID']='';
		}
		if (!isset($_POST['PartID'])) {
			$_POST['PartID']='';
		}
		if (!isset($_POST['PartDesc'])) {
			$_POST['PartDesc']='';
		}
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' .  _('Enter Stock Code') .'</label>
				<input type="text" class="form-control" name="StockID" name="DiscountCategory" pattern="[0-9a-zA-Z_]*" title="' . _('Enter the stock code of the item in this discount category up to 20 alpha-numeric characters') . '"  size="20" maxlength="20" value="' . $_POST['StockID'] . '" /></div></div>
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Partial code') . '</label>
				<input type="text" class="form-control" name="PartID" pattern="[0-9a-zA-Z_]*" title="' . _('Enter a portion of the item code only alpha-numeric characters') . '" size="10" maxlength="10" value="' . $_POST['PartID'] . '" /></div></div></div>
				<div class="row">
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Partial description') . '</label>
				<input type="text" class="form-control" name="PartDesc" size="10" value="' . $_POST['PartDesc'] .'" maxlength="10" /></div>
				</div>
				
				<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" class="btn btn-info" name="search" value="' . _('Search') .'" /></div>
			</div>';

		echo '</div>';

		echo '<br /><div class="row"><div class="col-xs-4"><input type="submit" class="btn btn-info" name="submit" value="'. _('Update Item') .'" /></div></div><br />';

		if (isset($_POST['search'])) {
			if ($_POST['PartID']!='' and $_POST['PartDesc']=='')
				$sql="SELECT stockid, description FROM stockmaster
						WHERE stockid " . LIKE  . " '%".$_POST['PartID']."%'";
			if ($_POST['PartID']=='' and $_POST['PartDesc']!='')
				$sql="SELECT stockid, description FROM stockmaster
						WHERE description " . LIKE  . " '%".$_POST['PartDesc']."%'";
			if ($_POST['PartID']!='' and $_POST['PartDesc']!='')
				$sql="SELECT stockid, description FROM stockmaster
						WHERE stockid " . LIKE  . " '%".$_POST['PartID']."%'
						AND description " . LIKE . " '%".$_POST['PartDesc']."%'";
			$result=DB_query($sql);
			if (!isset($_POST['stockID'])) {
				echo _('Select a part code').':<br />';
				while ($myrow=DB_fetch_array($result)) {
					echo '<input type="submit" name="stockID" value="'.$myrow['stockid'].'" /><br />';
				}
			}
		}
	} else {
		echo '<div class="row">
				<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Assign discount category') . '</label>';
		echo '<input type="text" required="required" name="DiscountCategory" pattern="[0-9a-zA-Z_]*" title="' . _('Enter the discount category up to 2 alpha-numeric characters') . '"  maxlength="2" size="2" /></div></div>';
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('to all items in stock category') . '</label>';
		$sql = "SELECT categoryid,
				categorydescription
				FROM stockcategory";
		$result = DB_query($sql);
		echo '<select name="stockcategory" class="form-control">';
		while ($myrow=DB_fetch_array($result)) {
			echo '<option value="'.$myrow['categoryid'].'">' . $myrow['categorydescription'] . '</option>';
		}
		echo '</select></div></div>';
		echo '<div class="col-xs-4">
<div class="form-group"> <br />
<input type="submit" name="SubmitCategory" class="btn btn-info" value="'. _('Update Items') .'" /></div></div></div>';
	}
	echo '
          </form>';

	if (! isset($_POST['DiscCat'])){ /*set DiscCat to something to show results for first cat defined */

		$sql = "SELECT DISTINCT discountcategory FROM stockmaster WHERE discountcategory <>''";
		$result = DB_query($sql);
		if (DB_num_rows($result)>0){
			DB_data_seek($result,0);
			$myrow = DB_fetch_array($result);
			$_POST['DiscCat'] = $myrow['discountcategory'];
		} else {
			$_POST['DiscCat']='0';
		}
	}

	if ($_POST['DiscCat']!='0'){

		$sql = "SELECT stockmaster.stockid,
			stockmaster.description,
			discountcategory
		FROM stockmaster
		WHERE discountcategory='" . $_POST['DiscCat'] . "'
		ORDER BY stockmaster.stockid";

		$result = DB_query($sql);

		echo '<br /><div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		echo '<tr>
			<th>' .  _('Discount Category')  . '</th>
			<th>' .  _('Item')  . '</th></tr>';

		while ($myrow = DB_fetch_array($result)) {
			$DeleteURL = htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=yes&amp;StockID=' . $myrow['stockid'] . '&amp;DiscountCategory=' . $myrow['discountcategory'];

			printf('<tr class="striped_row">
					<td>%s</td>
					<td>%s - %s</td>
					<td><a href="%s" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this discount category?') . '\');">' .  _('Delete')  . '</a></td>
					</tr>',
					$myrow['discountcategory'],
					$myrow['stockid'],
					$myrow['description'],
					$DeleteURL);

		}

		echo '</table></div></div></div>';

	} else { /* $_POST['DiscCat'] ==0 */

		echo '<br />';
		echo prnMsg( _('There are currently no discount categories defined') . '. ' . _('Enter a two character abbreviation for the discount category and the stock code to which this category will apply to. Discount rules can then be applied to this discount category'),'info');
	}
}

if (!isset($_POST['SelectChoice'])) {
	echo '<form method="post" id="choose" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	
	echo '<br />
<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Add discount category for') . '</label>
			<select name="ChooseOption" onchange="ReloadForm(choose.SelectChoice)" class="form-control">
				<option value="1">' . _('a single stock item') . '</option>
				<option value="2">' . _('a complete stock category') . '</option>
				</select></div>
		</div>
		';
	echo '<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" class="btn btn-info" name="SelectChoice" value="'._('Select').'" /></div>';
    echo '</div>
	</div>
          </form>';
}

include('includes/footer.php');
?>
