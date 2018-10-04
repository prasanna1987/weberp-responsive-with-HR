<?php

include('includes/session.php');
$Title = _('Product Specifications Maintenance');
$ViewTopic= 'QualityAssurance';// Filename in ManualContents.php's TOC.
$BookMark = 'QA_ProdSpecs';// Anchor's id in the manual's html document.
include('includes/header.php');

if (isset($_GET['SelectedQATest'])){
	$SelectedQATest =mb_strtoupper($_GET['SelectedQATest']);
} elseif(isset($_POST['SelectedQATest'])){
	$SelectedQATest =mb_strtoupper($_POST['SelectedQATest']);
}
if (isset($_GET['KeyValue'])){
	$KeyValue =mb_strtoupper($_GET['KeyValue']);
} elseif(isset($_POST['KeyValue'])){
	$KeyValue =mb_strtoupper($_POST['KeyValue']);
	
}

if (!isset($_POST['RangeMin']) OR $_POST['RangeMin']=='') {
	$RangeMin = 'NULL';
} else {
	$RangeMin = "'" . $_POST['RangeMin'] . "'";
}
if (!isset($_POST['RangeMax']) OR $_POST['RangeMax']=='') {
	$RangeMax = 'NULL';
} else {
	$RangeMax = "'" . $_POST['RangeMax'] . "'";
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

if (isset($_GET['CopySpec']) OR isset($_POST['CopySpec'])) {
	if (!isset($_POST['CopyTo']) OR $_POST['CopyTo']=='' ) {
		echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
		echo '
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" /><div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">
';
		echo _('Enter The Item, Fixed Asset or Template to Copy this Specification to') . '</label>
		<input type="text" name="CopyTo" size="25" maxlength="25" class="form-control" /></div></div>
		
			<div class="col-xs-4">
<div class="form-group"><br />
				<input type="hidden" name="KeyValue" value="' . $KeyValue . '" />
				<input type="submit" class="btn btn-info" name="CopySpec" value="' . _('Copy') . '" />
			</div></div></div>
			</form>';
		include('includes/footer.php');
		exit;
	} else {
		$sql = "INSERT IGNORE INTO prodspecs
							(keyval,
							testid,
							defaultvalue,
							targetvalue,
							rangemin,
							rangemax,
							showoncert,
							showonspec,
							showontestplan,
							active)
					SELECT '"  . $_POST['CopyTo'] . "',
								testid,
								defaultvalue,
								targetvalue,
								rangemin,
								rangemax,
								showoncert,
								showonspec,
								showontestplan,
								active
					FROM prodspecs WHERE keyval='" .$KeyValue. "'";
			$msg = _('A Product Specification has been copied to') . ' ' . $_POST['CopyTo']  . ' from ' . ' ' . $KeyValue ;
			$ErrMsg = _('The insert of the Product Specification failed because');
			$DbgMsg = _('The SQL that was used and failed was');
			$result = DB_query($sql,$ErrMsg, $DbgMsg);
			echo prnMsg($msg , 'success');
		$KeyValue=$_POST['CopyTo'];
		unset($_GET['CopySpec']);
		unset($_POST['CopySpec']);
	} //else
} //CopySpec

if (!isset($KeyValue) OR $KeyValue=='') {
	//prompt user for Key Value
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '" method="post">
			
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<div class="row">
				<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Enter Specification Name') .'</label>
					<input type="text" class="form-control" name="KeyValue" size="25" maxlength="25" /></div>
				</div>
			<div class="col-xs-4">
<div class="form-group"> <br />
				<input type="submit" class="btn btn-success" name="pickspec" value="' . _('Submit') . '" />
			</div>
			</div>
			</div>
		</form>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '" method="post">
			
				<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
				<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Or Select Existing Specification') .'</label>';

	$SQLSpecSelect="SELECT DISTINCT(keyval),
							description
						FROM prodspecs LEFT OUTER JOIN stockmaster
						ON stockmaster.stockid=prodspecs.keyval";


	$ResultSelection=DB_query($SQLSpecSelect);
	echo '<select name="KeyValue" class="form-control">';

	while ($MyRowSelection=DB_fetch_array($ResultSelection)){
		echo '<option value="' . $MyRowSelection['keyval'] . '">' . $MyRowSelection['keyval'].' - ' .htmlspecialchars($MyRowSelection['description'], ENT_QUOTES,'UTF-8', false)  . '</option>';
	}
	echo 	'</select></div>
			</div>
		<div class="col-xs-4">
<div class="form-group"> <br />
			<input type="submit" class="btn btn-success" name="pickspec" value="' . _('Submit') . '" />
		</div></div>
		</div>
		</form>';


} else 
{
	//show header
	$SQLSpecSelect="SELECT description
						FROM stockmaster
						WHERE stockmaster.stockid='" .$KeyValue. "'";

	$ResultSelection=DB_query($SQLSpecSelect);
	$MyRowSelection=DB_fetch_array($ResultSelection);
	echo '<br/>' . _('Product Specification for') . ' ' . $KeyValue . '-' . $MyRowSelection['description'] . '<br/><br/>';
}
if (isset($_GET['ListTests'])) {
	$sql = "SELECT qatests.testid,
				name,
				method,
				units,
				type,
				numericvalue,
				qatests.defaultvalue
			FROM qatests
			LEFT JOIN prodspecs
			ON prodspecs.testid=qatests.testid
			AND prodspecs.keyval='".$KeyValue."'
			WHERE qatests.active='1'
			AND prodspecs.keyval IS NULL
			ORDER BY name";
	$result = DB_query($sql);
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
				<th>' . _('Add') . '</th>
				<th>' . _('Name') . '</th>
				<th>' . _('Method') . '</th>
				<th>' . _('Units') . '</th>
				<th>' . _('Possible Values') . '</th>
				<th>' . _('Target Value') . '</th>
				<th>' . _('Range Min') . '</th>
				<th>' . _('Range Max') . '</th>
			</tr>
		</thead>
		<tbody>';

	$x=0;
	while ($myrow=DB_fetch_array($result)) {

	$x++;
	$Class='';
	$RangeMin='';
	$RangeMax='';
	if ($myrow['numericvalue'] == 1) {
		$IsNumeric = _('Yes');
		$Class="form-control";
	} else {
		$IsNumeric = _('No');
	}

	switch ($myrow['type']) {
	 	case 0; //textbox
	 		$TypeDisp=_('Text Box');
			$Class="form-control";
	 		break;
	 	case 1; //select box
	 		$TypeDisp=_('Select Box');
			break;
		case 2; //checkbox
			$TypeDisp=_('Check Box');
			break;
		case 3; //datebox
			$TypeDisp=_('Date Box');
			$Class="form-control input-datepicker-close";
			$Format ="data-date-format='mm/dd/yy'";
			break;
		case 4; //range
			$TypeDisp=_('Range');
			$RangeMin='<input  class="form-control" type="text" name="AddRangeMin' .$x.'" />';
			$RangeMax='<input  class="form-control" type="text" name="AddRangeMax' .$x.'" />';
			break;
	} //end switch
		printf('<tr class="striped_row">
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			</tr>',
			'<input type="checkbox" name="AddRow' .$x.'"><input type="hidden" name="AddTestID' .$x.'" value="' .$myrow['testid']. '">',
			$myrow['name'],
			$myrow['method'],
			$myrow['units'],
			$myrow['defaultvalue'],
			'<input type="text" class="form-control" name="AddTargetValue' .$x.'" />',
			$RangeMin,
			$RangeMax);

	} //END WHILE LIST LOOP

	echo '</tbody>
		</table></div></div></div>
			<div class="row" align="center">
				<input type="hidden" name="KeyValue" value="' . $KeyValue . '" />
				<input type="hidden" name="AddTestsCounter" value="' . $x . '" />
				
<input type="submit" name="AddTests" class="btn btn-success" value="' . _('Add') . '" />
		
		</div><br />
		</form>';
	include('includes/footer.php');
	exit;
}  //ListTests
if (isset($_POST['AddTests'])) {
	for ($i=0;$i<=$_POST['AddTestsCounter'];$i++){
		if ($_POST['AddRow' .$i]=='on') {
			if ($_POST['AddRangeMin' .$i]=='') {
				$AddRangeMin="NULL";
			} else {
				$AddRangeMin="'" . $_POST['AddRangeMin' .$i] . "'";
			}
			if ($_POST['AddRangeMax' .$i]=='') {
				$AddRangeMax="NULL";
			} else {
				$AddRangeMax="'" . $_POST['AddRangeMax' .$i] . "'";
			}

			$sql = "INSERT INTO prodspecs
							(keyval,
							testid,
							defaultvalue,
							targetvalue,
							rangemin,
							rangemax,
							showoncert,
							showonspec,
							showontestplan,
							active)
						SELECT '"  . $KeyValue . "',
								testid,
								defaultvalue,
								'"  .  $_POST['AddTargetValue' .$i] . "',
								"  . $AddRangeMin . ",
								"  . $AddRangeMax. ",
								showoncert,
								showonspec,
								showontestplan,
								active
						FROM qatests WHERE testid='" .$_POST['AddTestID' .$i]. "'";
			//echo $sql;
			$msg = _('A Product Specification record has been added for Test ID') . ' ' . $_POST['AddTestID' .$i]  . ' for ' . ' ' . $KeyValue ;
			$ErrMsg = _('The insert of the Product Specification failed because');
			$DbgMsg = _('The SQL that was used and failed was');
			$result = DB_query($sql,$ErrMsg, $DbgMsg);
			echo prnMsg($msg , 'success');
		} //if on
	} //for
} //AddTests

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */
	$i=1;

	//first off validate inputs sensible

	if (isset($SelectedQATest) AND $InputError !=1) {

		/*SelectedQATest could also exist if submit had not been clicked this code would not run in this case cos submit is false of course  see the delete code below*/

		$sql = "UPDATE prodspecs SET defaultvalue='" . $_POST['DefaultValue'] . "',
									targetvalue='" . $_POST['TargetValue'] . "',
									rangemin=" . $RangeMin . ",
									rangemax=" . $RangeMax . ",
									showoncert='" . $_POST['ShowOnCert'] . "',
									showonspec='" . $_POST['ShowOnSpec'] . "',
									showontestplan='" . $_POST['ShowOnTestPlan'] . "',
									active='" . $_POST['Active'] . "'
				WHERE prodspecs.keyval = '".$KeyValue."'
				AND prodspecs.testid = '".$SelectedQATest."'";

		$msg = _('Product Specification record for') . ' ' . $_POST['QATestName']  . ' for ' . ' ' . $KeyValue .  _('has been updated');
		$ErrMsg = _('The update of the Product Specification failed because');
		$DbgMsg = _('The SQL that was used and failed was');
		$result = DB_query($sql,$ErrMsg, $DbgMsg);

		echo prnMsg($msg , 'success');

		unset($SelectedQATest);
		unset($_POST['DefaultValue']);
		unset($_POST['TargetValue']);
		unset($_POST['RangeMax']);
		unset($_POST['RangeMin']);
		unset($_POST['ShowOnCert']);
		unset($_POST['ShowOnSpec']);
		unset($_POST['Active']);
	}
} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS

	$sql= "SELECT COUNT(*) FROM qasamples
			INNER JOIN sampleresults on sampleresults.sampleid=qasamples.sampleid AND sampleresults.testid='". $SelectedQATest."'
			WHERE qasamples.prodspeckey='".$KeyValue."'";
	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		echo prnMsg(_('Cannot delete this Product Specification because there are test results tied to it'),'error');
	} else {
		$sql="DELETE FROM prodspecs WHERE keyval='". $KeyValue."'
									AND testid='". $SelectedQATest."'";
		$ErrMsg = _('The Product Specification could not be deleted because');
		$result = DB_query($sql,$ErrMsg);

		echo prnMsg(_('Product Specification') . ' ' . $SelectedQATest . ' for ' . ' ' . $KeyValue . _('has been deleted from the system'),'success');
		unset ($SelectedQATest);
		unset($delete);
		unset ($_GET['delete']);
	}
}

if (!isset($SelectedQATest)) {

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedQATest will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of QA Test will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT prodspecs.testid,
				name,
				method,
				units,
				type,
				numericvalue,
				prodspecs.defaultvalue,
				prodspecs.targetvalue,
				prodspecs.rangemin,
				prodspecs.rangemax,
				prodspecs.showoncert,
				prodspecs.showonspec,
				prodspecs.showontestplan,
				prodspecs.active
			FROM prodspecs INNER JOIN qatests
			ON qatests.testid=prodspecs.testid
			WHERE prodspecs.keyval='" .$KeyValue."'
			ORDER BY name";
	$result = DB_query($sql);

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
		<tr>
			<th>' . _('Name') . '</th>
			<th>' . _('Method') . '</th>
			<th>' . _('Units') . '</th>
			<th>' . _('Type') . '</th>
			<th>' . _('Possible Values') . '</th>
			<th>' . _('Target Value') . '</th>
			<th>' . _('Range Min') . '</th>
			<th>' . _('Range Max') . '</th>
			<th>' . _('Show on Cert') . '</th>
			<th>' . _('Show on Spec') . '</th>
			<th>' . _('Show on Test Plan') . '</th>
			<th>' . _('Active') . '</th>
			<th colspan="2">' . _('Actions') . '</th>
			</tr>
		</thead>
		<tbody>';

	while ($myrow=DB_fetch_array($result)) {

	if ($myrow['active'] == 1) {
		$ActiveText = _('Yes');
	} else {
		$ActiveText = _('No');
	}
	if ($myrow['numericvalue'] == 1) {
		$IsNumeric = _('Yes');
		$Class="number";
	} else {
		$IsNumeric = _('No');
	}
	if ($myrow['showoncert'] == 1) {
		$ShowOnCertText = _('Yes');
	} else {
		$ShowOnCertText = _('No');
	}
	if ($myrow['showonspec'] == 1) {
		$ShowOnSpecText = _('Yes');
	} else {
		$ShowOnSpecText = _('No');
	}
	if ($myrow['showontestplan'] == 1) {
		$ShowOnTestPlanText = _('Yes');
	} else {
		$ShowOnTestPlanText = _('No');
	}
	switch ($myrow['type']) {
	 	case 0; //textbox
	 		$TypeDisp='Text Box';
	 		break;
	 	case 1; //select box
	 		$TypeDisp='Select Box';
			break;
		case 2; //checkbox
			$TypeDisp='Check Box';
			break;
		case 3; //datebox
			$TypeDisp='Date Box';
			$Class="date";
			break;
		case 4; //range
			$TypeDisp='Range';
			break;
	} //end switch

		printf('<tr class="striped_row">
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td><a href="%sSelectedQATest=%s&amp;KeyValue=%s" class="btn btn-info">' .  _('Edit') . '</a></td>
			<td><a href="%sSelectedQATest=%s&amp;KeyValue=%s&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'' . _('Are you sure you wish to delete this Product Specification ?') . '\');">' . _('Delete') . '</a></td>
			</tr>',
			$myrow['name'],
			$myrow['method'],
			$myrow['units'],
			$TypeDisp,
			$myrow['defaultvalue'],
			$myrow['targetvalue'],
			$myrow['rangemin'],
			$myrow['rangemax'],
			$ShowOnCertText,
			$ShowOnSpecText,
			$ShowOnTestPlanText,
			$ActiveText,
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
			$myrow['testid'],
			$KeyValue,
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
			$myrow['testid'],
			$KeyValue);

	} //END WHILE LIST LOOP
	echo '</tbody></table></div></div></div><br />';
} //end of ifs and buts!

if (isset($SelectedQATest)) {
	echo '<div class="row"><div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?KeyValue=' .$KeyValue .'" class="btn btn-info">' . _('Show All Product Specs') . '</a></div></div>';
}

if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($SelectedQATest)) {
		//editing an existing Prod Spec

		$sql = "SELECT prodspecs.testid,
						name,
						method,
						units,
						type,
						numericvalue,
						prodspecs.defaultvalue,
						prodspecs.targetvalue,
						prodspecs.rangemin,
						prodspecs.rangemax,
						prodspecs.showoncert,
						prodspecs.showonspec,
						prodspecs.showontestplan,
						prodspecs.active
				FROM prodspecs INNER JOIN qatests
				ON qatests.testid=prodspecs.testid
				WHERE prodspecs.keyval='".$KeyValue."'
				AND prodspecs.testid='".$SelectedQATest."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$_POST['SelectedQATest'] = $myrow['testid'];
		$_POST['QATestName'] = $myrow['name'];
		$_POST['Method'] = $myrow['method'];
		$_POST['GroupBy'] = $myrow['groupby'];
		$_POST['Type'] = $myrow['type'];
		$_POST['Units'] = $myrow['units'];
		$_POST['DefaultValue'] = $myrow['defaultvalue'];
		$_POST['NumericValue'] = $myrow['numericvalue'];
		$_POST['TargetValue'] = $myrow['targetvalue'];
		$_POST['RangeMin'] = $myrow['rangemin'];
		$_POST['RangeMax'] = $myrow['rangemax'];
		$_POST['ShowOnCert'] = $myrow['showoncert'];
		$_POST['ShowOnSpec'] = $myrow['showonspec'];
		$_POST['ShowOnTestPlan'] = $myrow['showontestplan'];
		$_POST['Active'] = $myrow['active'];


		echo '<input type="hidden" name="SelectedQATest" value="' . $SelectedQATest . '" />';
		echo '<input type="hidden" name="KeyValue" value="' . $KeyValue . '" />';
		echo '<input type="hidden" name="TestID" value="' . $_POST['SelectedQATest'] . '" />';
		echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Test Name') . '</label>
					' . $_POST['QATestName'] . '</div>
				</div></div>';

		if (!isset($_POST['Active'])) {
			$_POST['Active']=1;
		}
		if (!isset($_POST['ShowOnCert'])) {
			$_POST['ShowOnCert']=1;
		}
		if (!isset($_POST['ShowOnSpec'])) {
			$_POST['ShowOnSpec']=1;
		}
		if ($myrow['numericvalue'] == 1) {
			$IsNumeric = _('Yes');
			$Class="number";
		}
		switch ($myrow['type']) {
			case 0; //textbox
				$TypeDisp='Text Box';
				break;
			case 1; //select box
				$TypeDisp='Select Box';
				break;
			case 2; //checkbox
				$TypeDisp='Check Box';
				break;
			case 3; //datebox
				$TypeDisp='Date Box';
				$Class="date";
				break;
			case 4; //range
				$TypeDisp='Range';
				break;
		} //end switch
		if ($TypeDisp=='Select Box') {
			echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Possible Values') . '</label>
					<input type="text" name="DefaultValue" class="form-control" size="50" maxlength="150" value="' . $_POST['DefaultValue']. '" /></div>
				</div>';
		}
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Target Value') . '</label>
				<input type="text" class="form-control" name="TargetValue" size="15" maxlength="15" value="' . $_POST['TargetValue']. '" />&nbsp;'.$_POST['Units'].'</div>
			</div>';

		if ($TypeDisp=='Range') {
			echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Range Min') . '</label>
					<input class="' . $Class.'" type="text" class="form-control" name="RangeMin" size="10" maxlength="10" value="' . $_POST['RangeMin']. '" /></div>
				</div></div>';
			echo '<div class="row">
					<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Range Max') . '</label>
					<input class="' . $Class.'" type="text" class="form-control" name="RangeMax" size="10" maxlength="10" value="' . $_POST['RangeMax']. '" /></div>
				</div>';
		}
		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Show On Cert?') . '</label>
				<select name="ShowOnCert" class="form-control">';
		if ($_POST['ShowOnCert']==1){
			echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
		} else {
			echo '<option value="1">' . _('Yes') . '</option>';
		}
		if ($_POST['ShowOnCert']==0){
			echo '<option selected="selected" value="0">' . _('No') . '</option>';
		} else {
			echo '<option value="0">' . _('No') . '</option>';
		}
		echo '</select></div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Show On Spec?') . '</label>
				<select name="ShowOnSpec" class="form-control">';
		if ($_POST['ShowOnSpec']==1){
			echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
		} else {
			echo '<option value="1">' . _('Yes') . '</option>';
		}
		if ($_POST['ShowOnSpec']==0){
			echo '<option selected="selected" value="0">' . _('No') . '</option>';
		} else {
			echo '<option value="0">' . _('No') . '</option>';
		}
		echo '</select></div></div>
		</div>
		<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Show On Test Plan?') . '</label>
			<select name="ShowOnTestPlan" class="form-control">';
		if ($_POST['ShowOnTestPlan']==1){
			echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
		} else {
			echo '<option value="1">' . _('Yes') . '</option>';
		}
		if ($_POST['ShowOnTestPlan']==0){
			echo '<option selected="selected" value="0">' . _('No') . '</option>';
		} else {
			echo '<option value="0">' . _('No') . '</option>';
		}
		echo '</select></div></div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Active?') . '</label>
				<select name="Active" class="form-control">';
		if ($_POST['Active']==1){
			echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
		} else {
			echo '<option value="1">' . _('Yes') . '</option>';
		}
		if ($_POST['Active']==0){
			echo '<option selected="selected" value="0">' . _('No') . '</option>';
		} else {
			echo '<option value="0">' . _('No') . '</option>';
		}
		echo '</select></div>
			</div>
			<div class="col-xs-4">
<div class="form-group"><br />
				<input type="submit" class="btn btn-info" name="submit" value="' . _('Enter Information') . '" />
			</div>
			</div>
			</div><br />

			</form>';
	}
	}
	if ((isset($KeyValue)) OR ($KeyValue!='')) {
		
		echo '<div class="row"><div class="col-xs-3"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ListTests=yes&amp;KeyValue=' .$KeyValue .'" class="btn btn-success">' . _('Add Tests') . '</a></div>';
		echo '<div class="col-xs-3"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?CopySpec=yes&amp;KeyValue=' .$KeyValue .'" class="btn btn-info">' . _('Copy This Specification') . '</a></div>';
		echo '<div class="col-xs-3"><a target="_blank" class="btn btn-warning" href="'. $RootPath . '/PDFProdSpec.php?KeyValue=' .$KeyValue .'">' . _('Print Product Specification') . '</a></div>';
		echo '<div class="col-xs-3"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .'" class="btn btn-default">' . _('Back') . '</a></div></div><br />
';
	
} //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>