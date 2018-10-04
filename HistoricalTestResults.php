<?php

include('includes/session.php');
$Title = _('Historical Test Results');
$ViewTopic= 'QualityAssurance';// Filename in ManualContents.php's TOC.
$BookMark = 'QA_HistoricalResults';// Anchor's id in the manual's html document.
include('includes/header.php');

if (isset($_GET['KeyValue'])){
	$KeyValue =mb_strtoupper($_GET['KeyValue']);
} elseif (isset($_POST['KeyValue'])){
	$KeyValue =mb_strtoupper($_POST['KeyValue']);
} else {
	$KeyValue='';
}

if (!isset($_POST['FromDate'])){
	$_POST['FromDate']=Date(($_SESSION['DefaultDateFormat']), Mktime(0, 0, 0, Date('m'), Date('d')-180, Date('Y')));
}
if (!isset($_POST['ToDate'])){
	$_POST['ToDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!Is_Date($_POST['FromDate'])) {
	$InputError = 1;
	echo prnMsg(_('Invalid From Date'),'error');
	$_POST['FromDate']=Date(($_SESSION['DefaultDateFormat']), Mktime(0, 0, 0, Date('m'), Date('d')-180, Date('Y')));
}
if (!Is_Date($_POST['ToDate'])) {
	$InputError = 1;
	echo prnMsg(_('Invalid To Date'),'error');
	$_POST['ToDate'] = Date($_SESSION['DefaultDateFormat']);
}
$FromDate = FormatDateForSQL($_POST['FromDate']);
$ToDate = FormatDateForSQL($_POST['ToDate']);
if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';


//prompt user for Key Value
echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '" method="post">
	
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Show Test Results For') .'</label>';
$SQLSpecSelect="SELECT DISTINCT(prodspeckey),
						description
					FROM qasamples LEFT OUTER JOIN stockmaster
					ON stockmaster.stockid=qasamples.prodspeckey";


$ResultSelection=DB_query($SQLSpecSelect);
echo '<select name="KeyValue" class="form-control">';

while ($MyRowSelection=DB_fetch_array($ResultSelection)){
	if ($MyRowSelection['prodspeckey']==$KeyValue) {
		$Selected='selected="selected" ';
	} else {
		$Selected='';
	}
	echo '<option ' . $Selected . ' value="' . $MyRowSelection['prodspeckey'] . '">' . $MyRowSelection['prodspeckey'].' - ' .htmlspecialchars($MyRowSelection['description'], ENT_QUOTES,'UTF-8', false)  . '</option>';
}
echo '</select></div>';
echo '</div>
	 <div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('From Sample Date') . ' </label>
		<input name="FromDate" maxlength="10" size="11" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" value="' . $_POST['FromDate'] . '" /></div>
	</div>
	<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label"> ' . _('To Sample Date') . '</label>
		<input name="ToDate" maxlength="10" size="11" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" value="' . $_POST['ToDate'] . '" /></div>
	</div>
	</div>
	<div class="row">
	<div class="col-xs-4">
<div class="form-group"> 
	<input type="submit" class="btn btn-info" name="PickSpec" value="' . _('Submit') . '" />
	</div>
	</div>
	</div>
	</form></div></div>';


//show header
$SQLSpecSelect="SELECT description
					FROM stockmaster
					WHERE stockmaster.stockid='" . $KeyValue . "'";

$ResultSelection=DB_query($SQLSpecSelect);
$MyRowSelection=DB_fetch_array($ResultSelection);
$SQLTests="SELECT sampleresults.testid,
					sampledate,
					sampleresults.sampleid,
					lotkey,
					identifier,
					cert,
					isinspec,
					testvalue,
					name
				FROM qasamples
				INNER JOIN sampleresults on sampleresults.sampleid=qasamples.sampleid
				INNER JOIN qatests
				ON qatests.testid=sampleresults.testid
				WHERE qasamples.prodspeckey='" . $KeyValue . "'
				AND sampleresults.showontestplan='1'
				AND sampledate>='" . $FromDate . "'
				AND sampledate <='" . $ToDate . "'";


$TestResult=DB_query($SQLTests);
$TestsArray=array();
$SamplesArray=array();
$AllResultsArray=array();
$TotResults=0;
while ($MyTestRow=DB_fetch_array($TestResult)) {
	$FormattedSampleID=str_pad($MyTestRow['sampleid'],10,'0',STR_PAD_LEFT);
	$testkey=array_search ($MyTestRow['name'] , $TestsArray);
	if ($testkey===false) {
		$TestsArray[$MyTestRow['name']]=$MyTestRow['name'];
	}

	$testkey=array_search ($MyTestRow['sampleid'] , $SamplesArray);
	if ($testkey===false) {
		$SamplesArray[$FormattedSampleID]=$MyTestRow;
		$TotResults++;
	}
	$AllResultsArray[$MyTestRow['name']][$FormattedSampleID]=$MyTestRow;
}

if ($TotResults>0) {
	echo '<h4>' . _('Historical Test Results for') . ' ' . $KeyValue . '-' . $MyRowSelection['description'] . '</h4><br/>';

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<tr>
				<th style="white-space:nowrap;">' . _('Sample ID:') . '<br>' . _('Lot/Serial:') . '<br>' . _('Identifier:') . '<br>' . _('Sample Date:') .'</th>';
	foreach ($SamplesArray as $samplekey => $samplevalue) {
		echo '<th>'. $samplekey . '<br>' . $samplevalue['lotkey'] . '<br>' . $samplevalue['identifier'] . '<br>' . ConvertSQLDate($samplevalue['sampledate']).'</th>';
	}
	echo '</tr>';
	foreach ($TestsArray as $testkey => $testvalue) {
		echo '<tr class="striped_row">
				<td class="select" style="white-space:nowrap;">'.$testvalue.'</td>';
		foreach ($SamplesArray as $samplekey => $samplevalue) {
			if ($AllResultsArray[$testkey][$samplekey]['testvalue']=='' OR !isset($AllResultsArray[$testkey][$samplekey]['testvalue'])) {
				$AllResultsArray[$testkey][$samplekey]['testvalue']='&nbsp;';
			}
			echo '<td>'.$AllResultsArray[$testkey][$samplekey]['testvalue'].'</td>';
		}
		echo '</tr>';
	}
	echo '</tr></table></div></div></div></div>';
}

include('includes/footer.php');
?>