<?php


include('includes/session.php');
$Title = _('Freight Costs Maintenance');
include('includes/header.php');
include('includes/CountriesArray.php');

if (isset($_GET['LocationFrom'])){
	$LocationFrom = $_GET['LocationFrom'];
} elseif (isset($_POST['LocationFrom'])){
	$LocationFrom = $_POST['LocationFrom'];
}
if (isset($_GET['ShipperID'])){
	$ShipperID = $_GET['ShipperID'];
} elseif (isset($_POST['ShipperID'])){
	$ShipperID = $_POST['ShipperID'];
}
if (isset($_GET['SelectedFreightCost'])){
	$SelectedFreightCost = $_GET['SelectedFreightCost'];
} elseif (isset($_POST['SelectedFreightCost'])){
	$SelectedFreightCost = $_POST['SelectedFreightCost'];
}

if (!isset($LocationFrom) OR !isset($ShipperID)) {
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
  
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	$sql = "SELECT shippername, shipper_id FROM shippers";
	$ShipperResults = DB_query($sql);

	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select a Shipper to set up costs for') . '</label>
			<select name="ShipperID" class="form-control">';

	while ($myrow = DB_fetch_array($ShipperResults)){
		echo '<option value="' . $myrow['shipper_id'] . '">' . $myrow['shippername'] . '</option>';
	}
	echo '</select></div></div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Select') . ' ' . _('ship from location') . '</label>
				<select name="LocationFrom" class="form-control">';

	$sql = "SELECT locations.loccode,
					locationname
			FROM locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
	$LocationResults = DB_query($sql);

	while ($myrow = DB_fetch_array($LocationResults)){
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}

	echo '</select></div></div>
			<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" value="' . _('Accept') . '" class="btn btn-success name="Accept" /></div>
            </div>
			</div>
			</form></div></div>';

} else {

	$sql = "SELECT shippername FROM shippers WHERE shipper_id = '".$ShipperID."'";
	$ShipperResults = DB_query($sql);
	$myrow = DB_fetch_row($ShipperResults);
	$ShipperName = $myrow[0];
	$sql = "SELECT locationname FROM locations WHERE loccode = '".$LocationFrom."'";
	$LocationResults = DB_query($sql);
	$myrow = DB_fetch_row($LocationResults);
	$LocationName = $myrow[0];

	if (isset($ShipperID)){
		$Title .= ' ' . _('For') . ' ' . $ShipperName;
	}
	if (isset($LocationFrom)){
		$Title .= ' ' . _('From') . ' ' . $LocationName;
	}

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

}

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	//first off validate inputs sensible

	if (trim($_POST['DestinationCountry']) == '' ) {
		$_POST['DestinationCountry'] = $CountriesArray[$_SESSION['CountryOfOperation']];
	}
	if (trim($_POST['CubRate']) == '' ) {
		$_POST['CubRate'] = 0;
	}
	if (trim($_POST['KGRate']) == '' ) {
		$_POST['KGRate'] = 0;
	}
	if (trim($_POST['MAXKGs']) == '' ) {
		$_POST['MAXKGs'] = 0;
	}
	if (trim($_POST['MAXCub']) == '' ) {
		$_POST['MAXCub'] = 0;
	}
	if (trim($_POST['FixedPrice']) == '' ){
		$_POST['FixedPrice'] = 0;
	}
	if (trim($_POST['MinimumChg']) == '' ) {
		$_POST['MinimumChg'] = 0;
	}

	if (!is_double((double) $_POST['CubRate']) OR !is_double((double) $_POST['KGRate']) OR !is_double((double) $_POST['MAXKGs']) OR !is_double((double) $_POST['MAXCub']) OR !is_double((double) $_POST['FixedPrice']) OR !is_double((double) $_POST['MinimumChg'])) {
		$InputError=1;
		echo prnMsg(_('The entries for Cubic Rate, KG Rate, Maximum Weight, Maximum Volume, Fixed Price and Minimum charge must be numeric'),'warn');
	}

	if (isset($SelectedFreightCost) AND $InputError !=1) {

		$sql = "UPDATE freightcosts
				SET	locationfrom='".$LocationFrom."',
					destinationcountry='" . $_POST['DestinationCountry'] . "',
					destination='" . $_POST['Destination'] . "',
					shipperid='" . $ShipperID . "',
					cubrate='" . $_POST['CubRate'] . "',
					kgrate ='" . $_POST['KGRate'] . "',
					maxkgs ='" . $_POST['MAXKGs'] . "',
					maxcub= '" . $_POST['MAXCub'] . "',
					fixedprice = '" . $_POST['FixedPrice'] . "',
					minimumchg= '" . $_POST['MinimumChg'] . "'
			WHERE shipcostfromid='" . $SelectedFreightCost . "'";

		$msg = _('Freight cost record updated');

	} elseif ($InputError !=1) {

	/*Selected freight cost is null cos no item selected on first time round so must be adding a record must be submitting new entries */

		$sql = "INSERT INTO freightcosts (locationfrom,
											destinationcountry,
											destination,
											shipperid,
											cubrate,
											kgrate,
											maxkgs,
											maxcub,
											fixedprice,
											minimumchg)
										VALUES (
											'".$LocationFrom."',
											'" . $_POST['DestinationCountry'] . "',
											'" . $_POST['Destination'] . "',
											'" . $ShipperID . "',
											'" . $_POST['CubRate'] . "',
											'" . $_POST['KGRate'] . "',
											'" . $_POST['MAXKGs'] . "',
											'" . $_POST['MAXCub'] . "',
											'" . $_POST['FixedPrice'] ."',
											'" . $_POST['MinimumChg'] . "'
										)";

		$msg = _('Freight cost record inserted');

	}
	//run the SQL from either of the above possibilites

	$ErrMsg = _('The freight cost record could not be updated because');
	$result = DB_query($sql,$ErrMsg);

	echo prnMsg($msg,'success');

	unset($SelectedFreightCost);
	unset($_POST['CubRate']);
	unset($_POST['KGRate']);
	unset($_POST['MAXKGs']);
	unset($_POST['MAXCub']);
	unset($_POST['FixedPrice']);
	unset($_POST['MinimumChg']);

} elseif (isset($_GET['delete'])) {

	$sql = "DELETE FROM freightcosts WHERE shipcostfromid='" . $SelectedFreightCost . "'";
	$result = DB_query($sql);
	echo prnMsg( _('Freight cost record deleted'),'success');
	unset ($SelectedFreightCost);
	unset($_GET['delete']);
}

if (!isset($SelectedFreightCost) AND isset($LocationFrom) AND isset($ShipperID)){

	$sql = "SELECT shipcostfromid,
					destinationcountry,
					destination,
					cubrate,
					kgrate,
					maxkgs,
					maxcub,
					fixedprice,
					minimumchg
				FROM freightcosts
				WHERE freightcosts.locationfrom = '".$LocationFrom. "'
				AND freightcosts.shipperid = '" . $ShipperID . "'
				ORDER BY destinationcountry,
						destination,
						maxkgs,
						maxcub";

	$result = DB_query($sql);

	echo '
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
';
	$TableHeader = '
	<thead>
	<tr>
					<th>' . _('Country') . '</th>
					<th>' . _('Destination') . '</th>
					<th>' . _('Cubic Rate') . '</th>
					<th>' . _('KG Rate') . '</th>
					<th>' . _('MAX Weight') . '</th>
					<th>' . _('MAX Volume') . '</th>
					<th>' . _('Fixed Price') . '</th>
					<th>' . _('Minimum Charge') . '</th>
					<th colspan="2">' . _('Actions') . '</th>
					</tr></thead>';

	echo $TableHeader;

	$PageFullCounter=0;

	while ($myrow = DB_fetch_row($result)) {
		$PageFullCounter++;
		if ($PageFullCounter==15){
				$PageFullCounter=0;
				echo $TableHeader;

		}

		printf('<tr class="striped_row">
			<td>%s</td>
			<td>%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td><a href="%s&amp;SelectedFreightCost=%s&amp;LocationFrom=%s&amp;ShipperID=%s" class="btn btn-info">' . _('Edit') . '</a></td>
			<td><a href="%s&amp;SelectedFreightCost=%s&amp;LocationFrom=%s&amp;ShipperID=%s&amp;delete=yes" onclick="return confirm(\'' . _('Are you sure you wish to delete this freight cost') . '\');" class="btn btn-danger">' . _('Delete') . '</a></td></tr>',
			$myrow[1],
			$myrow[2],
			locale_number_format($myrow[3],$_SESSION['CompanyRecord']['decimalplaces']),
			locale_number_format($myrow[4],$_SESSION['CompanyRecord']['decimalplaces']),
			locale_number_format($myrow[5],2),
			locale_number_format($myrow[6],3),
			locale_number_format($myrow[7],$_SESSION['CompanyRecord']['decimalplaces']),
			locale_number_format($myrow[8],$_SESSION['CompanyRecord']['decimalplaces']),
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
			$myrow[0],
			$LocationFrom,
			$ShipperID,
			htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
			$myrow[0],
			$LocationFrom,
			$ShipperID);

	}

	//END WHILE LIST LOOP
	echo '</table></div></div></div>';
}

//end of ifs and buts!

if (isset($SelectedFreightCost)) {
	echo '<div class="row"><div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?LocationFrom=' . $LocationFrom . '&amp;ShipperID=' . $ShipperID . '" class="btn btn-info">' . _('Show all freight costs for') . ' ' . $ShipperName  . ' ' . _('from') . ' ' . $LocationName . '</a></div></div><br />';
}

if (isset($LocationFrom) AND isset($ShipperID)) {
echo '<div class="page-header"></div><br />
<div class="row gutter30">
<div class="col-xs-12">';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
   
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($SelectedFreightCost)) {
		//editing an existing freight cost item

		$sql = "SELECT locationfrom,
					destinationcountry,
					destination,
					shipperid,
					cubrate,
					kgrate,
					maxkgs,
					maxcub,
					fixedprice,
					minimumchg
				FROM freightcosts
				WHERE shipcostfromid='" . $SelectedFreightCost ."'";

		$result = DB_query($sql);
		$myrow = DB_fetch_array($result);

		$LocationFrom  = $myrow['locationfrom'];
		$_POST['DestinationCountry']	= $myrow['destinationcountry'];
		$_POST['Destination']	= $myrow['destination'];
		$ShipperID  = $myrow['shipperid'];
		$_POST['CubRate']  = $myrow['cubrate'];
		$_POST['KGRate'] = $myrow['kgrate'];
		$_POST['MAXKGs'] = $myrow['maxkgs'];
		$_POST['MAXCub'] = $myrow['maxcub'];
		$_POST['FixedPrice'] = $myrow['fixedprice'];
		$_POST['MinimumChg'] = $myrow['minimumchg'];

		echo '<input type="hidden" name="SelectedFreightCost" value="' . $SelectedFreightCost . '" />';

	} else {
		$_POST['FixedPrice'] = 0;
		$_POST['MinimumChg'] = 0;
	}

	echo '<input type="hidden" name="LocationFrom" value="' . $LocationFrom . '" />';
	echo '<input type="hidden" name="ShipperID" value="' . $ShipperID . '" />';

	if (!isset($_POST['DestinationCountry'])) {$_POST['DestinationCountry']=$CountriesArray[$_SESSION['CountryOfOperation']];}
	if (!isset($_POST['Destination'])) {$_POST['Destination']='';}
	if (!isset($_POST['CubRate'])) {$_POST['CubRate']='';}
	if (!isset($_POST['KGRate'])) {$_POST['KGRate']='';}
	if (!isset($_POST['MAXKGs'])) {$_POST['MAXKGs']='';}
	if (!isset($_POST['MAXCub'])) {$_POST['MAXCub']='';}

	
	echo '<h3 class="page-header"><strong>' . _('For Deliveries From') . ' ' . $LocationName . ' ' . _('using') . ' ' .
		$ShipperName . '</strong></h3><br />
';

	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Destination Country') . '</label>
			<select name="DestinationCountry" class="form-control">';
	foreach ($CountriesArray as $CountryEntry => $CountryName){
		if (isset($_POST['DestinationCountry']) AND (strtoupper($_POST['DestinationCountry']) == strtoupper($CountryName))){
			echo '<option selected="selected" value="' . $CountryName . '">' . $CountryName  . '</option>';
		} else {
			echo '<option value="' . $CountryName . '">' . $CountryName  . '</option>';
		}
	}
	echo '</select></div>
		</div>';

	echo'<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Destination Zone') . '</label>
		<input type="text" maxlength="20" size="20" class="form-control" name="Destination" value="' . $_POST['Destination'] . '" /></div></div>';
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Rate per Cubic Metre') . '</label>
		<input type="text" name="CubRate" class="form-control" size="6" maxlength="5" value="' . $_POST['CubRate'] . '" /></div></div></div>';
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Rate Per KG') . '</label>
		<input type="text" name="KGRate" class="form-control" size="6" maxlength="5" value="' . $_POST['KGRate'] . '" /></div></div>';
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Maximum Weight Per Package (KGs)') . '</label>
		<input type="text" name="MAXKGs" class="form-control" size="8" maxlength="7" value="' . $_POST['MAXKGs'] . '" /></div></div>';
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Maximum Volume Per Package') . '</label>
		<input type="text" name="MAXCub" class="form-control" size="8" maxlength="7" value="' . $_POST['MAXCub'] . '" /></div></div></div>';
	echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Fixed Price (zero if rate per KG or Cubic is applicable)') . '</label>
		<input type="text" name="FixedPrice" class="form-control" size="11" maxlength="10" value="' . $_POST['FixedPrice'] . '" /></div></div>';
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Minimum Charge (0 is N/A)') . '</label>
		<td><input type="text" name="MinimumChg" class="form-control" size="11" maxlength="10" value="' . $_POST['MinimumChg'] . '" /></div></div>';

	

	echo '<div class="col-xs-4">
<div class="form-group"><br /><input type="submit" name="submit" class="btn btn-info" value="' . _('Enter Information') . '" /></div>';
    echo '</div></div>';
	echo '</form></div></div>';

} //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>