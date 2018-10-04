<?php

include('includes/session.php');
$Title = _('Customer Transactions Inquiry');
$ViewTopic = 'ARInquiries';
$BookMark = 'ARTransInquiry';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Transaction Inquiry') . '
	<br />';
echo '
	</h1></a></div>';

echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Transaction Type') . '</label>
			<select tabindex="1" name="TransType" class="form-control"> ';

$sql = "SELECT typeid,
				typename
		FROM systypes
		WHERE typeid >= 10
		AND typeid <= 14";

$resultTypes = DB_query($sql);

echo '<option value="All">' . _('All') . '</option>';
while($myrow=DB_fetch_array($resultTypes)) {
	echo '<option';
	if(isset($_POST['TransType'])) {
		if($myrow['typeid'] == $_POST['TransType']) {
		     echo ' selected="selected"' ;
		}
	}
	echo ' value="' . $myrow['typeid'] . '">' . _($myrow['typename']) . '</option>';
}
echo '</select></div></div>';

if (!isset($_POST['FromDate'])){
	$_POST['FromDate']=Date($_SESSION['DefaultDateFormat'], mktime(0,0,0,Date('m'),1,Date('Y')));
}
if (!isset($_POST['ToDate'])){
	$_POST['ToDate'] = Date($_SESSION['DefaultDateFormat']);
}
echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('From Date') . '</label>
	<input class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" maxlength="10" name="FromDate" required="required" size="11" tabindex="2" type="text" value="' . $_POST['FromDate'] . '" /></div></div>
	<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('To Date') . '</label>
	<input class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" maxlength="10" name="ToDate" required="required" size="11" tabindex="3" type="text" value="' . $_POST['ToDate'] . '" /></div>
	</div>
	</div>
	
	<div class="row">
	<div class="col-xs-4">
<div class="form-group">
		<input name="ShowResults" tabindex="4" class="btn btn-info" type="submit" value="' . _('Show') . '" />
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
   $sql = "SELECT transno,
		   		trandate,
				debtortrans.debtorno,
				branchcode,
				reference,
				invtext,
				order_,
				debtortrans.rate,
				ovamount+ovgst+ovfreight+ovdiscount as totalamt,
				currcode,
				typename,
				decimalplaces AS currdecimalplaces
			FROM debtortrans
			INNER JOIN debtorsmaster ON debtortrans.debtorno=debtorsmaster.debtorno
			INNER JOIN currencies ON debtorsmaster.currcode=currencies.currabrev
			INNER JOIN systypes ON debtortrans.type = systypes.typeid
			WHERE ";

   $sql = $sql . "trandate >='" . $SQL_FromDate . "' AND trandate <= '" . $SQL_ToDate . "'";
	if  ($_POST['TransType']!='All')  {
		$sql .= " AND type = '" . $_POST['TransType']."'";
	}
	$sql .=  " ORDER BY id";

   $ErrMsg = _('The customer transactions for the selected criteria could not be retrieved because') . ' - ' . DB_error_msg();
   $DbgMsg =  _('The SQL that failed was');
   $TransResult = DB_query($sql,$ErrMsg,$DbgMsg);

   echo '<br />
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

   $TableHeader = '<tr>
					<th>' . _('Type') . '</th>
					<th>' . _('Number') . '</th>
					<th>' . _('Date') . '</th>
					<th>' . _('Customer') . '</th>
					<th>' . _('Branch') . '</th>
					<th>' . _('Reference') . '</th>
					<th>' . _('Comments') . '</th>
					<th>' . _('Order') . '</th>
					<th>' . _('Ex Rate') . '</th>
					<th>' . _('Amount') . '</th>
					<th>' . _('Currency') . '</th>
				</tr>';
	echo $TableHeader;

	$RowCounter = 1;

	while ($myrow=DB_fetch_array($TransResult)) {

		$format_base = '<tr class="striped_row">
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td style="width:200px">%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td>%s</td>';

		if ($_POST['TransType']==10){ /* invoices */

			printf($format_base .
					'<td><a target="_blank" class="btn btn-info" href="%s/PrintCustTrans.php?FromTransNo=%s&InvOrCredit=Invoice"><img src="%s" title="' . _('Click to preview the invoice') . '" /></a></td>
					</tr>',
					_($myrow['typename']),
					$myrow['transno'],
					ConvertSQLDate($myrow['trandate']),
					$myrow['debtorno'],
					$myrow['branchcode'],
					$myrow['reference'],
					$myrow['invtext'],
					$myrow['order_'],
					locale_number_format($myrow['rate'],6),
					locale_number_format($myrow['totalamt'],$myrow['currdecimalplaces']),
					$myrow['currcode'],
					$RootPath,
					$myrow['transno'],
					$RootPath.'/css/'.$Theme.'/images/preview.png');

		} elseif($_POST['TransType']==11) { /* credit notes */
			printf($format_base .
					'<td><a target="_blank" href="%s/PrintCustTrans.php?FromTransNo=%s&InvOrCredit=Credit" class="btn btn-info">' . _('Click to preview the credit') . '</a></td>
					</tr>',
					_($myrow['typename']),
					$myrow['transno'],
					ConvertSQLDate($myrow['trandate']),
					$myrow['debtorno'],
					$myrow['branchcode'],
					$myrow['reference'],
					$myrow['invtext'],
					$myrow['order_'],
					locale_number_format($myrow['rate'],6),
					locale_number_format($myrow['totalamt'],$myrow['currdecimalplaces']),
					$myrow['currcode'],
					$RootPath,
					$myrow['transno'],
					$RootPath.'/css/'.$Theme.'/images/preview.png');
		} else {  /* otherwise */
			printf($format_base . '</tr>',
					_($myrow['typename']),
					$myrow['transno'],
					ConvertSQLDate($myrow['trandate']),
					$myrow['debtorno'],
					$myrow['branchcode'],
					$myrow['reference'],
					$myrow['invtext'],
					$myrow['order_'],
					locale_number_format($myrow['rate'],6),
					locale_number_format($myrow['totalamt'],$myrow['currdecimalplaces']),
					$myrow['currcode']);
		}

	}
	//end of while loop

 echo '</table>
 </div>
 </div>
 </div>
 ';
}

include('includes/footer.php');

?>
