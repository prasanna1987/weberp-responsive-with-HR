<?php
/*Input Serial Items - used for inputing serial numbers or batch/roll/bundle references
for controlled items - used in:
- ConfirmDispatchControlledInvoice.php
- GoodsReceivedControlled.php
- StockAdjustments.php
- StockTransfers.php
- CreditItemsControlled.php

*/

//we start with a batch or serial no header and need to display something for verification...
global $tableheader;
global $LineItem;
//$LineNo = initPvar('LineNo', $LineNo);
if (isset($_GET['LineNo'])){
	$LineNo = $_GET['LineNo'];
} elseif (isset($_POST['LineNo'])){
	$LineNo = $_POST['LineNo'];
}


echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
echo $tableheader;

$TotalQuantity = 0; /*Variable to accumulate total quantity received */
$RowCounter =0;

/*Display the batches already entered with quantities if not serialised */
foreach ($LineItem->SerialItems as $Bundle){

	$RowCounter++;
	//only show 1st 10 lines
	if ($RowCounter < 10){

		echo '<tr class="striped_row">
				<td>' . $Bundle->BundleRef . '</td>';

		if ($LineItem->Serialised==0){
			echo '<td class="number">' . locale_number_format($Bundle->BundleQty, $LineItem->DecimalPlaces) . '</td>';
		}
	}

	$TotalQuantity += $Bundle->BundleQty;
}


/*Display the totals and rule off before allowing new entries */
if ($LineItem->Serialised==1){
	echo '<tr>
			<td class="number"><b>' .   _('Total Quantity'). ': ' . locale_number_format($TotalQuantity,$LineItem->DecimalPlaces) . '</b></td>
		</tr>
		';
} else {
	echo '<tr>
			<td class="number"><strong>' .  _('Total Quantity'). ':</strong></td>
			<td class="number"><strong>' . locale_number_format($TotalQuantity,$LineItem->DecimalPlaces) . '</strong></td>
		</tr>
		';
}

echo '</table></div></div></div><br />';

//DISPLAY FILE INFO
// do some inits & error checks...
$ShowFileInfo = false;
if (!isset($_SESSION['CurImportFile']) ){
		$_SESSION['CurImportFile'] = '';
		$LineItem->SerialItemsValid=false;
}
if ((isset($_FILES['ImportFile']) AND $_FILES['ImportFile']['name'] == '') AND $_SESSION['CurImportFile'] == ''){
	$msg = _('Please Choose a file and then click Set Entry Type to upload a file for import');
	echo prnMsg($msg, 'info');
	$LineItem->SerialItemsValid=false;
	echo '</td></tr></table>';
	include('includes/footer.php');
	exit();
}
if ((isset($_FILES['ImportFile']) AND $_FILES['ImportFile']['error'] != '') AND !isset($_SESSION['CurImportFile'])){
		echo prnMsg('There was a problem with the uploaded file') . '. ' . _('We received').':<br />' .
				 _('Name').':'.$_FILES['ImportFile']['name'] . '<br />' .
				 _('Size').':'.locale_number_format($_FILES['ImportFile']['size']/1024,2).'kb<br />' .
				 _('Type').':'.$_FILES['ImportFile']['type'] . '<br />';
		echo '<br />' . _('Error was').' '.$_FILES['ImportFile']['error'];
		$LineItem->SerialItemsValid=false;
		echo '</td></tr></table>';
		include('includes/footer.php');
		exit();
} elseif ((isset($_FILES['ImportFile']) AND $_FILES['ImportFile']['name']!='')){
	//User has uploaded importfile. reset items, then just 'get hold' of it for later.

	$LineItem->SerialItems=array();
	$LineItem->SerialItemsValid=false;
	$_SESSION['CurImportFile']['Processed']=false;
	$_SESSION['CurImportFile'] = $_FILES['ImportFile'];
	$_SESSION['CurImportFile']['tmp_name'] = $_SERVER['DOCUMENT_ROOT'].$RootPath.$PathPrefix . '/' . $_SESSION['reports_dir'] . '/'.$LineItem->StockID.'_'.$LineNo.'_'.uniqid(4);
	if (!move_uploaded_file($_FILES['ImportFile']['tmp_name'],$_SESSION['CurImportFile']['tmp_name'])){
		echo prnMsg(_('Error moving temporary file') . '. ' . _('Please check your configuration'),'error' );
		$LineItem->SerialItemsValid=false;
		echo '</td></tr></table>';
		include('includes/footer.php');
		exit;
	}
	$_SESSION['CurImportFile']['Processed']=false;
	if ($_FILES['ImportFile']['name']!=''){
		echo prnMsg( _('Successfully received'), 'success');
		$ShowFileInfo = true;
	}
} elseif (isset($_SESSION['CurImportFile']) and $_SESSION['CurImportFile']['Processed'] ) {
	//file exists, some action performed...
	$ShowFileInfo = true;
} elseif ($LineItem->SerialItemsValid and $_SESSION['CurImportFile']['Processed']){
	$ShowFileInfo = true;
}
if ($ShowFileInfo){
	/********************************************
	  Display file info for visual verification
	********************************************/
	$File=$_SESSION['CurImportFile']['tmp_name'];
	$TotalLines = 0;
	$Handle = fopen($File, 'r');
	while(!feof($Handle)){
		$Line = fgets($Handle);
		$TotalLines++;
	}
	fclose($Handle);
	if ($Line=='') {
		$TotalLines--;
	}

	echo '<br /><div class="row gutter30">
<div class="col-xs-7">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';
	echo '<tr>
			<td>' . _('Name').':</td>
			<td>' . $_SESSION['CurImportFile']['name'] . '</td>
		</tr>
		<tr>
			<td>' .  _('Size') .':</td>
			<td>' . locale_number_format($_SESSION['CurImportFile']['size']/1024,4) . 'kb</td>
		</tr>
		<tr>
			<td>' .  _('Type') .':</td>
			<td>' . $_SESSION['CurImportFile']['type'] . '</td>
		</tr>
		<tr>
			<td>' .  _('TempName') .':</td>
			<td>' . basename($_SESSION['CurImportFile']['tmp_name']) . '</td>
		</tr>
		<tr>
			<td>' .  _('Status') .':</td>
			<td>' . ($invalid_imports==0?getMsg(_('Valid'),'success'):getMsg(_('Invalid'),'error')) . '</td>
		</tr>
	</table></div></div></div>';
	$filename = $_SESSION['CurImportFile']['tmp_name'];
}

if ($invalid_imports>0 AND !$_SESSION['CurImportFile']['Processed']){
		// IF all items are not valid, show the raw first 10 lines of the file. maybe it will help.

	echo '<br /><div class="row"><form method="post"><div class="col-xs-4">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<input type="submit" name="ValidateFile" class="btn btn-info" value="' . _('Validate File') . '" />
			<input type="hidden" name="LineNo" value="' . $LineNo . '" />
			<input type="hidden" name="StockID" value="' . $StockID . '" />
			<input type="hidden" name="EntryType" value="FILE" /></div>
			</form>
			<p><strong>' .  _('1st 10 Lines of File'). '....</strong></p></div>
			<hr width="15%">
		<pre>';

	echo $contents;

	echo '</pre>';

} else {
	echo '<br /><div class="row"><form method="post"><div class="col-xs-4">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<button type="submit" class="btn btn-info" name="ValidateFile">' . _('Update Batches') . '</button>
			<input type="hidden" name="LineNo" value="' . $LineNo . '" />
			<input type="hidden" name="InvalidImports" value="' . $invalid_imports . '" />
			<input type="hidden" name="StockID" value="' . $StockID . '" />
			<input type="hidden" name="EntryType" value="FILE" /></div><br />';
		//Otherwise we have all valid records. show the first (100)  for visual verification.
	echo '<br /><div class="row gutter30">
<div class="col-xs-12">
<div class="block">
<div class="block-title"><h3>' . _('Below are the 1st 100 records as parsed') . '</h3></div>
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<tr>
				<th class="header" colspan="2"></th>
			</tr>
			<thead><tr>
				<th>' . _('Batch Number') . '</th>
				<th>' . _('Quantity') . '</th>
			</tr></thead>';
	$i=0;
	foreach($LineItem->SerialItems as $SItem){
		echo '<tr>
				<td>' . $SItem->BundleRef . '</td>
				<td class="number">' . locale_number_format($SItem->BundleQty, $LineItem->DecimalPlaces) . '</td>
			</tr>';
		$i++;
		if ($i == 100) {
			break;
		}
	}
	echo '</table></div></div></div></div></form></div>';
}

?>
