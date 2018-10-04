<?php


/*The supplier transaction uses the SuppTrans class to hold the information about the invoice or credit note
the SuppTrans class contains an array of GRNs objects - containing details of GRNs for invoicing/crediting and also
an array of GLCodes objects - only used if the AP - GL link is effective */

include('includes/DefineSuppTransClass.php');

/* Session started in header.php for password checking and authorisation level check */
include('includes/session.php');
$Title = _('Supplier Transaction General Ledger Analysis');
$ViewTopic = 'AccountsPayable';
$BookMark = 'SuppTransGLAnalysis';
include('includes/header.php');

if (!isset($_SESSION['SuppTrans'])){
	echo  prnMsg(_('To enter a supplier invoice or credit note the supplier must first be selected from the supplier selection screen') . ', ' . _('then the link to enter a supplier invoice or supplier credit note must be clicked on'),'info');
	echo '<div class="row" align="center"><a href="' . $RootPath . '/SelectSupplier.php" class="btn btn-info">' . _('Select A Supplier') . '</a></div><br />';
	include('includes/footer.php');
	exit;
	/*It all stops here if there aint no supplier selected and transaction initiated ie $_SESSION['SuppTrans'] started off*/
}

/*If the user hit the Add to transaction button then process this first before showing  all GL codes on the transaction otherwise it wouldnt show the latest addition*/

if (isset($_POST['AddGLCodeToTrans'])
	AND $_POST['AddGLCodeToTrans'] == _('Enter GL Line')){

	$InputError = False;
	if ($_POST['GLCode'] == ''){
		$_POST['GLCode'] = $_POST['AcctSelection'];
	}

	if ($_POST['GLCode'] == ''){
		echo  prnMsg( _('You must select a general ledger code from the list below') ,'warn');
		$InputError = True;
	}

	$sql = "SELECT accountcode,
			accountname
		FROM chartmaster
		WHERE accountcode='" . $_POST['GLCode'] . "'";
	$result = DB_query($sql);
	if (DB_num_rows($result) == 0 and $_POST['GLCode'] != ''){
		echo prnMsg(_('The account code entered is not a valid code') . '. ' . _('This line cannot be added to the transaction') . '.<br />' . _('You can use the selection box to select the account you want'),'error');
		$InputError = True;
	} else if ($_POST['GLCode'] != '') {
		$myrow = DB_fetch_row($result);
		$GLActName = $myrow[1];
		if (!is_numeric(filter_number_format($_POST['Amount']))){
			echo prnMsg( _('The amount entered is not numeric') . '. ' . _('This line cannot be added to the transaction'),'error');
			$InputError = True;
		} elseif ($_POST['JobRef'] != ''){
			$sql = "SELECT contractref FROM contracts WHERE contractref='" . $_POST['JobRef'] . "'";
			$result = DB_query($sql);
			if (DB_num_rows($result) == 0){
				echo prnMsg( _('The contract reference entered is not a valid contract, this line cannot be added to the transaction'),'error');
				$InputError = True;
			}
		}
	}

	if ($InputError == False){

		$_SESSION['SuppTrans']->Add_GLCodes_To_Trans($_POST['GLCode'],
													$GLActName,
													filter_number_format($_POST['Amount']),
													$_POST['Narrative'],
													$_POST['Tag']);
		unset($_POST['GLCode']);
		unset($_POST['Amount']);
		unset($_POST['JobRef']);
		unset($_POST['Narrative']);
		unset($_POST['AcctSelection']);
		unset($_POST['Tag']);
	}
}

if (isset($_GET['Delete'])){
	$_SESSION['SuppTrans']->Remove_GLCodes_From_Trans($_GET['Delete']);
}

if (isset($_GET['Edit'])){
	$_POST['GLCode'] = $_SESSION['SuppTrans']->GLCodes[$_GET['Edit']]->GLCode;
	$_POST['AcctSelection']= $_SESSION['SuppTrans']->GLCodes[$_GET['Edit']]->GLCode;
	$_POST['Amount'] = $_SESSION['SuppTrans']->GLCodes[$_GET['Edit']]->Amount;
	$_POST['JobRef'] = $_SESSION['SuppTrans']->GLCodes[$_GET['Edit']]->JobRef;
	$_POST['Narrative'] = $_SESSION['SuppTrans']->GLCodes[$_GET['Edit']]->Narrative;
	$_POST['Tag'] = $_SESSION['SuppTrans']->GLCodes[$_GET['Edit']]->Tag;
	$_SESSION['SuppTrans']->Remove_GLCodes_From_Trans($_GET['Edit']);
}

/*Show all the selected GLCodes so far from the SESSION['SuppInv']->GLCodes array */
if ($_SESSION['SuppTrans']->InvoiceOrCredit == 'Invoice'){
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('General Ledger Analysis of Invoice From') . ' ' . $_SESSION['SuppTrans']->SupplierName;
} else {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('General Ledger Analysis of Credit Note From') . ' ' . $_SESSION['SuppTrans']->SupplierName;
}
echo '</h1></a></div>
	<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
	<thead>
		<tr>
					<th>' . _('Account') . '</th>
					<th>' . _('Name') . '</th>
					<th>' . _('Amount') . '<br />(' . $_SESSION['SuppTrans']->CurrCode . ')</th>
					<th>' . _('Narrative') . '</th>
					<th>' . _('Tag') . '</th>
					<th colspan="2">Action</th>
		</tr>
	</thead>
	<tbody>';

$TotalGLValue=0;

foreach ( $_SESSION['SuppTrans']->GLCodes AS $EnteredGLCode){

	echo '<tr>
			<td class="text">' . $EnteredGLCode->GLCode . '</td>
			<td class="text">' . $EnteredGLCode->GLActName . '</td>
			<td class="number">' . locale_number_format($EnteredGLCode->Amount,$_SESSION['SuppTrans']->CurrDecimalPlaces) . '</td>
			<td class="text">' . $EnteredGLCode->Narrative . '</td>
			<td class="text">' . $EnteredGLCode->Tag  . ' - ' . $EnteredGLCode->TagName . '</td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Edit=' . $EnteredGLCode->Counter . '" class="btn btn-info">' . _('Edit') . '</a></td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $EnteredGLCode->Counter . '" class="btn btn-danger">' . _('Delete') . '</a></td>
		</tr>';

	$TotalGLValue += $EnteredGLCode->Amount;
}

echo '</tbody>
	
		<tr>
		<td colspan="2" class="number">' . _('Total') . ':</td>
		<td class="number">' . locale_number_format($TotalGLValue,$_SESSION['SuppTrans']->CurrDecimalPlaces) . '</td>
		<td colspan="4">&nbsp;</td>
	</tr>
	
	</table></div></div></div><br />';

if ($_SESSION['SuppTrans']->InvoiceOrCredit == 'Invoice'){
	echo '<br />
		<div class="row">
		<div class="col-xs-4">
			<a href="' . $RootPath . '/SupplierInvoice.php" class="btn btn-default">' . _('Back to Invoice Entry') . '</a>
		</div></div><br />';
} else {
	echo '<div class="row">
		<div class="col-xs-4">
			<a href="' . $RootPath . '/SupplierCredit.php" class="btn btn-default">' . _('Back to Credit Note Entry') . '</a>
		</div></div><br />';
}

/*Set up a form to allow input of new GL entries */
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '
	<div class="row">';
if (!isset($_POST['GLCode'])) {
	$_POST['GLCode']='';
}

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select Tag') . '</label>
		<select name="Tag" class="form-control">';

$SQL = "SELECT tagref,
			tagdescription
		FROM tags
		ORDER BY tagref";

$result=DB_query($SQL);
echo '<option value="0"></option>';
while ($myrow=DB_fetch_array($result)){
	if (isset($_POST['Tag']) AND $_POST['Tag']==$myrow['tagref']){
		echo '<option selected="selected" value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	}
}
echo '</select></div>
	</div>';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Account Code') . '</label>
		<input type="text" data-type="no-illegal-chars" title="'._('The input must be alpha-numeric characters').'" placeholder="'._('less than 20 alpha-numeric characters').'" name="GLCode" class="form-control" size="21" maxlength="20" value="' .  $_POST['GLCode'] . '" />
		<input type="hidden" name="JobRef" value="" /></div>
	</div>';
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Account Selection') . '(' . _('If you know the code enter it above') . '
		' . _('otherwise select the account from the list') . ')</label>
	<select name="AcctSelection" class="form-control">';

$sql = "SELECT chartmaster.accountcode,
			   chartmaster.accountname
		FROM chartmaster
		INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canupd=1
		ORDER BY chartmaster.accountcode";

$result = DB_query($sql);
echo '<option value=""></option>';
while ($myrow = DB_fetch_array($result)) {
	if ($myrow['accountcode'] == $_POST['AcctSelection']) {
		echo '<option selected="selected" value="';
	} else {
		echo '<option value="';
	}
	echo $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
}

echo '</select>
	</div>
	</div></div>';
if (!isset($_POST['Amount'])) {
	$_POST['Amount']=0;
}
echo '<div class="row">
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('Amount'), ' (', $_SESSION['SuppTrans']->CurrCode, ')</label>
	<input type="text" class="form-control" required="required" pattern="(?!^[-]?0[.,]0*$).{1,11}" title="'._('The amount must be numeric and cannot be zero').'" name="Amount" size="12" placeholder="'._('No zero numeric').'" maxlength="11" value="' .  locale_number_format($_POST['Amount'],$_SESSION['SuppTrans']->CurrDecimalPlaces) . '" /></div>
	</div>';

if (!isset($_POST['Narrative'])) {
	$_POST['Narrative']='';
}
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Narrative') . '</label>
	<textarea name="Narrative" class="form-control">' .  $_POST['Narrative'] . '</textarea></div>
	</div>
	';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label"><br /></label>
		<input type="submit" class="btn btn-success" name="AddGLCodeToTrans" value="' . _('Enter GL Line') . '" />
	</div></div>';

echo '</div><br />
      </form>';
include('includes/footer.php');
?>
