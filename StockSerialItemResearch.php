<?php

include('includes/session.php');
$Title = _('Serial Item Research');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' . $Title. '</h1></a>
	  </div>';


//validate the submission
if (isset($_POST['serialno'])) {
	$SerialNo = trim($_POST['serialno']);
} elseif(isset($_GET['serialno'])) {
	$SerialNo = trim($_GET['serialno']);
} else {
	$SerialNo = '';
}



echo '<div class="row gutter30">
<div class="col-xs-12">
<form id="SerialNoResearch" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .'">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo' <div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">'
._('Serial Number') .'</label>
 <input id="serialno" type="text" class="form-control" name="serialno" size="21" maxlength="20" value="'. $SerialNo . '" /> </div></div>
 <div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" name="submit" class="btn btn-success" value="' . _('Search') . '" />
</div>
</div>
</div>
</form>
</div>
</div>
';

echo '<script  type="text/javascript">
		document.getElementById("serialno").focus();
	</script>';


if ($SerialNo!='') {
	//the point here is to allow a semi fuzzy search, but still keep someone from killing the db server
	if (mb_strstr($SerialNo,'%')){
		while(mb_strstr($SerialNo,'%%'))	{
			$SerialNo = str_replace('%%','%',$SerialNo);
		}
		if (mb_strlen($SerialNo) < 11){
			$SerialNo = str_replace('%','',$SerialNo);
			echo prnMsg('You can not use LIKE with short numbers. It has been removed.','warn');
		}
	}
	$SQL = "SELECT ssi.serialno,
			ssi.stockid, ssi.quantity CurInvQty,
			ssm.moveqty,
			sm.type, st.typename,
			sm.transno, sm.loccode, l.locationname, sm.trandate, sm.debtorno, sm.branchcode, sm.reference, sm.qty TotalMoveQty
			FROM stockserialitems ssi INNER JOIN stockserialmoves ssm
				ON ssi.serialno = ssm.serialno AND ssi.stockid=ssm.stockid
			INNER JOIN stockmoves sm
				ON ssm.stockmoveno = sm.stkmoveno and ssi.loccode=sm.loccode
			INNER JOIN systypes st
				ON sm.type=st.typeid
			INNER JOIN locations l
				on sm.loccode = l.loccode
			INNER JOIN locationusers ON locationusers.loccode=l.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			WHERE ssi.serialno " . LIKE . " '" . $SerialNo . "'
			ORDER BY stkmoveno";

	$result = DB_query($SQL);

	if (DB_num_rows($result) == 0){
		echo prnMsg( _('No History found for Serial Number'). ': <b>' . $SerialNo . '</b>' , 'warn');
	} else {
		echo '<h4>' .  _('Details for Serial Item').': <b>' . $SerialNo . '</b><br />' .  _('Length').'='.mb_strlen($SerialNo) . '</h4>';
		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
		echo '<thead>
		<tr>
				<th>' . _('Stock ID') . '</th>
				<th>' . _('QOH') . '</th>
				<th>' . _('Move Qty') . '</th>
				<th>' . _('Move Type') . '</th>
				<th>' . _('Trans #') . '</th>
				<th>' . _('Location') . '</th>
				<th>' . _('Date') . '</th>
				<th>' . _('Customer No') . '</th>
				<th>' . _('Branch') . '</th>
				<th>' . _('Move Ref') . '</th>
				<th>' . _('Total Move Qty') . '</th>
			</tr></thead>';
		while ($myrow=DB_fetch_row($result)) {
			
			//$tdate= $ConvertSQLDate($myrow['trandate']);
			
			
			printf('<tr>
					<td>%s<br />%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td>%s (%s)</td>
					<td class="number">%s</td>
					<td>%s - %s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					</tr>',
					$myrow[1],
					$myrow[0],
					$myrow[2],
					$myrow[3],
					$myrow[5], 
					$myrow[4],
					$myrow[6],
					$myrow[7], 
					$myrow[8],
					$myrow[9],
					$myrow[10],
					$myrow[11],
					$myrow[12],
					$myrow[13]
				);
		} //END WHILE LIST LOOP
		echo '</table></div></div></div>';
	} // ELSE THERE WHERE ROWS
}//END OF POST IS SET
//echo '</div>';

include('includes/footer.php');
?>