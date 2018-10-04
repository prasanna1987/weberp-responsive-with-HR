<?php


include('includes/session.php');
$Title = _('Supplier Transactions Inquiry');
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' . ' ' . $Title . '
	</h1></a></div>';

echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Type') . '</label>
			<select name="TransType" class="form-control">';

$sql = "SELECT typeid,
				typename
		FROM systypes
		WHERE typeid >= 20
		AND typeid <= 23";

$resultTypes = DB_query($sql);

echo '<option value="All">' ._('All') . '</option>';
while ($myrow=DB_fetch_array($resultTypes)){
	if (isset($_POST['TransType'])){
		if ($myrow['typeid'] == $_POST['TransType']){
		     echo '<option selected="selected" value="' . $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
		} else {
		     echo '<option value="' . $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
		}
	} else {
		     echo '<option value="' . $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
	}
}
echo '</select></div></div>';

if (!isset($_POST['FromDate'])){
	$_POST['FromDate']=Date($_SESSION['DefaultDateFormat'], mktime(0,0,0,Date('m'),1,Date('Y')));
}
if (!isset($_POST['ToDate'])){
	$_POST['ToDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['SupplierNo'])) {
	$_POST['SupplierNo'] = '';
}
echo '
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('From') . '</label>
		<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="FromDate" maxlength="10" size="11" value="' . $_POST['FromDate'] . '" /></div></div>
		
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('To') . '</label>
		<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="ToDate" maxlength="10" size="11" value="' . $_POST['ToDate'] . '" /></div></div>
		</div>
		
		<div class="row">
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Supplier No') . '</label>
		<input type="text" name="SupplierNo" class="form-control" size="11" maxlength="10" value="' . $_POST['SupplierNo'] . '" />
		</div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <br />
		<input type="submit" class="btn btn-success" name="ShowResults" value="' . _('Show') . '" />
	</div>
	
    </div>
	</div>
	</form>
	</div>
	</div>
	';

if (isset($_POST['ShowResults']) && $_POST['TransType'] != ''){
   $SQL_FromDate = FormatDateForSQL($_POST['FromDate']);
   $SQL_ToDate = FormatDateForSQL($_POST['ToDate']);
   $sql = "SELECT type,
				transno,
		   		trandate,
				duedate,
				supplierno,
				suppname,
				suppreference,
				transtext,
				supptrans.rate,
				diffonexch,
				alloc,
				ovamount+ovgst as totalamt,
				currcode,
				typename,
				decimalplaces AS currdecimalplaces
			FROM supptrans
			INNER JOIN suppliers ON supptrans.supplierno=suppliers.supplierid
			INNER JOIN systypes ON supptrans.type = systypes.typeid
			INNER JOIN currencies ON suppliers.currcode=currencies.currabrev
			WHERE ";

   $sql = $sql . "trandate >='" . $SQL_FromDate . "' AND trandate <= '" . $SQL_ToDate . "'";
	if  ($_POST['TransType']!='All')  {
		$sql .= " AND type = " . $_POST['TransType'];
	}
	$sql .=  " ORDER BY id";

   $TransResult = DB_query($sql);
   $ErrMsg = _('The supplier transactions for the selected criteria could not be retrieved because') . ' - ' . DB_error_msg();
   $DbgMsg =  _('The SQL that failed was');

   echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

   $tableheader = '<thead><tr>
					<th>' . _('Type') . '</th>
					<th>' . _('Number') . '</th>
					<th>' . _('Supp Ref') . '</th>
					<th>' . _('Date') . '</th>
					<th>' . _('Supplier') . '</th>
					<th>' . _('Comments') . '</th>
					<th>' . _('Due Date') . '</th>
					<th>' . _('Ex Rate') . '</th>
					<th>' . _('Amount') . '</th>
					<th>' . _('Currency') . '</th>
				</tr></thead>';
	echo $tableheader;

	$RowCounter = 1;

	while ($myrow=DB_fetch_array($TransResult)) {

		printf ('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td>%s</td>
				</tr>',
				$myrow['typename'],
				$myrow['transno'],
				$myrow['suppreference'],
				ConvertSQLDate($myrow['trandate']),
				$myrow['supplierno'] . ' - ' . $myrow['suppname'],
				$myrow['transtext'],
				ConvertSQLDate($myrow['duedate']),
				locale_number_format($myrow['rate'],'Variable'),
				locale_number_format($myrow['totalamt'],$myrow['currdecimalplaces']),
				$myrow['currcode']);


		$GLTransResult = DB_query("SELECT account,
										accountname,
										narrative,
										amount
									FROM gltrans INNER JOIN chartmaster
									ON gltrans.account=chartmaster.accountcode
									WHERE type='" . $myrow['type'] . "'
									AND typeno='" . $myrow['transno'] . "'",
									_('Could not retrieve the GL transactions for this AP transaction'));

		if (DB_num_rows($GLTransResult)==0){
			echo '<tr>
					<td colspan="10">' . _('There are no GL transactions created for the above AP transaction') . '</td>
				</tr>';
		} else {
			echo '<tr>
					<td colspan="2"></td>
					<td colspan="8">
						<div class="table-responsive">
<table id="general-table" class="table table-bordered">
';
			echo '<tr>
					<th colspan="2"><b>' . _('GL Account') . '</b></th>
					<th><b>' . _('Local Amount') . '</b></th>
					<th><b>' . _('Narrative') . '</b></th>
				</tr>';
			$CheckGLTransBalance =0;
			while ($GLTransRow = DB_fetch_array($GLTransResult)){

				printf('<tr>
						<td>%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td>%s</td>
						</tr>',
						$GLTransRow['account'],
						$GLTransRow['accountname'],
						locale_number_format($GLTransRow['amount'],$_SESSION['CompanyRecord']['decimalplaces']),
						$GLTransRow['narrative']);

				$CheckGLTransBalance += $GLTransRow['amount'];
			}
			if (round($CheckGLTransBalance,5)!= 0){
				echo '<tr>
						<td colspan="4" style="background-color:red"><b>' . _('The GL transactions for this AP transaction are out of balance by') .  ' ' . $CheckGLTransBalance . '</b></td>
					</tr>';
			}
			echo '</table></div></td></tr>';
		}

		$RowCounter++;
		If ($RowCounter == 12){
			$RowCounter=1;
			echo $tableheader;
		}
	//end of page full new headings if
	}
	//end of while loop

 echo '</table></div></div></div>';
}
include('includes/footer.php');
?>
