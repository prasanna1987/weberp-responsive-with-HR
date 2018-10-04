<?php
/**
 * Author: Ashish Shukla <gmail.com!wahjava>
 *
 * Script to duplicate BoMs.
 */

include('includes/session.php');

$Title = _('Copy a BOM to New Item Code');

include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');

if(isset($_POST['Submit'])) {
	$StockID = $_POST['StockID'];
	$NewOrExisting = $_POST['NewOrExisting'];
	$NewStockID = '';
	$InputError = 0; //assume the best

	if($NewOrExisting == 'N') {
		$NewStockID = $_POST['ToStockID'];
		if (mb_strlen($NewStockID)==0 OR $NewStockID==''){
			$InputError = 1;
			echo prnMsg(_('The new Stock ID cannot be blank. Enter a new ID for the Stock to copy the BOM to'),'error');
		}
	} else {
		$NewStockID = $_POST['ExStockID'];
	}
	if ($InputError==0){
		$result = DB_Txn_Begin();

		if($NewOrExisting == 'N') {
	      /* duplicate rows into stockmaster */
			$sql = "INSERT INTO stockmaster( stockid,
									categoryid,
									description,
									longdescription,
									units,
									mbflag,
									actualcost,
									lastcost,
									materialcost,
									labourcost,
									overheadcost,
									lowestlevel,
									discontinued,
									controlled,
									eoq,
									volume,
									grossweight,
									barcode,
									discountcategory,
									taxcatid,
									serialised,
									appendfile,
									perishable,
									digitals,
									nextserialno,
									pansize,
									shrinkfactor,
									netweight )
							SELECT '".$NewStockID."' AS stockid,
									categoryid,
									description,
									longdescription,
									units,
									mbflag,
									actualcost,
									lastcost,
									materialcost,
									labourcost,
									overheadcost,
									lowestlevel,
									discontinued,
									controlled,
									eoq,
									volume,
									grossweight,
									barcode,
									discountcategory,
									taxcatid,
									serialised,
									appendfile,
									perishable,
									digitals,
									nextserialno,
									pansize,
									shrinkfactor,
									netweight
							FROM stockmaster
							WHERE stockid='".$StockID."'";
							
			$result = DB_query($sql);
		} else {
			$sql = "SELECT lastcostupdate,
							actualcost,
							lastcost,
							materialcost,
							labourcost,
							overheadcost,
							lowestlevel
						FROM stockmaster
						WHERE stockid='".$StockID."'";
						
			$result = DB_query($sql);

			$myrow = DB_fetch_row($result);

			$sql = "UPDATE stockmaster set
					lastcostupdate  = '" . $myrow[0] . "',
					actualcost      = " . $myrow[1] . ",
					lastcost        = " . $myrow[2] . ",
					materialcost    = " . $myrow[3] . ",
					labourcost      = " . $myrow[4] . ",
					overheadcost    = " . $myrow[5] . ",
					lowestlevel     = " . $myrow[6] . "
					WHERE stockid='".$NewStockID."'";
					echo $sql;
			$result = DB_query($sql);
		}

		$sql = "INSERT INTO bom
					SELECT '".$NewStockID."' AS parent,
					        sequence,
							component,
							workcentreadded,
							loccode,
							effectiveafter,
							effectiveto,
							quantity,
							autoissue,
							remark,
							digitals
					FROM bom
					WHERE parent='".$StockID."'";
					
		$result = DB_query($sql);

		if($NewOrExisting == 'N') {
			$sql = "INSERT INTO locstock (loccode,
								            stockid,
								            quantity,
								            reorderlevel,
								            bin )
				      SELECT loccode,
							'".$NewStockID."' AS stockid,
							0 AS quantity,
							reorderlevel,
							bin
						FROM locstock
						WHERE stockid='".$StockID."'";

			$result = DB_query($sql);
		}

		$result = DB_Txn_Commit();

		UpdateCost($NewStockID);

		header('Location: BOMs.php?Select='.$NewStockID);
		ob_end_flush();
		
	} //end  if there is no input error
} else {

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	$sql = "SELECT stockid,
					description
				FROM stockmaster
				WHERE stockid IN (SELECT DISTINCT parent FROM bom)
				AND  mbflag IN ('M', 'A', 'K', 'G');";
	$result = DB_query($sql);

	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('From Stock ID') . '</label>';
	echo '<select name="StockID" class="form-control">';
	while($myrow = DB_fetch_row($result)) {
		echo '<option value="'.$myrow[0].'">' . $myrow[0].' -- '.$myrow[1] . '</option>';
	}
	echo '</select></div>
			</div>';
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label"><input type="radio" name="NewOrExisting" value="N" />' . _(' To New Stock ID') . '</label>';
	echo '<input type="text" class="form-control" maxlength="20" autofocus="autofocus" pattern="[a-zA-Z0-9_\-]*" name="ToStockID" title="' . _('Enter a new item code to copy the existing item and its bill of material to. Item codes can contain only alpha-numeric characters, underscore or hyphens.') . '" /></div></div>';

	$sql = "SELECT stockid,
					description
				FROM stockmaster
				WHERE stockid NOT IN (SELECT DISTINCT parent FROM bom)
				AND mbflag IN ('M', 'A', 'K', 'G');";
	$result = DB_query($sql);

	if (DB_num_rows($result) > 0) {
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label"><input type="radio" name="NewOrExisting" checked="checked" value="E" />' . _('To Existing Stock ID') . '</label>';
		echo '<select name="ExStockID" class="form-control">';
		while($myrow = DB_fetch_row($result)) {
			echo '<option value="'.$myrow[0].'">' . $myrow[0].' -- '.$myrow[1] . '</option>';
		}
		echo '</select></div></div>';
	}
	echo '</div>';
	echo '<div class="row"><div class="col-xs-4">
<input type="submit" name="Submit" value="Submit" class="btn btn-info" /></div>
          </div><br />

          </form>';

	include('includes/footer.php');
}
?>
