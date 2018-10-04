<?php


// MRPCalendar.php
// Maintains the calendar of valid manufacturing dates for MRP

include('includes/session.php');
$Title = _('MRP Calendar');
include('includes/header.php');


if (isset($_POST['ChangeDate'])){
	$ChangeDate =trim(mb_strtoupper($_POST['ChangeDate']));
} elseif (isset($_GET['ChangeDate'])){
	$ChangeDate =trim(mb_strtoupper($_GET['ChangeDate']));
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '
	</h1></a></div>';

if (isset($_POST['submit'])) {
	submit($ChangeDate);
} elseif (isset($_POST['update'])) {
	update($ChangeDate);
} elseif (isset($_POST['ListAll'])) {
	ShowDays();
} else {
	ShowInputForm($ChangeDate);
}

function submit(&$ChangeDate)  //####SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT####
{

	//initialize no input errors
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if (!Is_Date($_POST['FromDate'])) {
		$InputError = 1;
		echo prnMsg(_('Invalid From Date'),'error');
	}

	if (!Is_Date($_POST['ToDate'])) {
		$InputError = 1;
		echo prnMsg(_('Invalid To Date'),'error');

	}

// Use FormatDateForSQL to put the entered dates into right format for sql
// Use ConvertSQLDate to put sql formatted dates into right format for functions such as
// DateDiff and DateAdd
	$FormatFromDate = FormatDateForSQL($_POST['FromDate']);
	$FormatToDate = FormatDateForSQL($_POST['ToDate']);
	$ConvertFromDate = ConvertSQLDate($FormatFromDate);
	$ConvertToDate = ConvertSQLDate($FormatToDate);

	$DateGreater = Date1GreaterThanDate2($_POST['ToDate'],$_POST['FromDate']);
	$DateDiff = DateDiff($ConvertToDate,$ConvertFromDate,'d'); // Date1 minus Date2

	if ($DateDiff < 1) {
		$InputError = 1;
		echo prnMsg(_('To Date Must Be Greater Than From Date'),'error');
	}

	 if ($InputError == 1) {
		ShowInputForm($ChangeDate);
		return;
	 }

	$sql = "DROP TABLE IF EXISTS mrpcalendar";
	$result = DB_query($sql);

	$sql = "CREATE TABLE mrpcalendar (
				calendardate date NOT NULL,
				daynumber int(6) NOT NULL,
				manufacturingflag smallint(6) NOT NULL default '1',
				INDEX (daynumber),
				PRIMARY KEY (calendardate)) DEFAULT CHARSET=utf8";
	$ErrMsg = _('The SQL to create passbom failed with the message');
	$result = DB_query($sql,$ErrMsg);

	$i = 0;

	/* $DaysTextArray used so can get text of day based on the value get from DayOfWeekFromSQLDate of
	 the calendar date. See if that text is in the ExcludeDays array note no gettext here hard coded english days from $_POST*/
	$DaysTextArray = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

	$ExcludeDays = array($_POST['Sunday'],$_POST['Monday'],$_POST['Tuesday'],$_POST['Wednesday'],
						 $_POST['Thursday'],$_POST['Friday'],$_POST['Saturday']);

	$CalDate = $ConvertFromDate;
	for ($i = 0; $i <= $DateDiff; $i++) {
		 $DateAdd = FormatDateForSQL(DateAdd($CalDate,'d',$i));

		 // If the check box for the calendar date's day of week was clicked, set the manufacturing flag to 0
		 $DayOfWeek = DayOfWeekFromSQLDate($DateAdd);
		 $ManuFlag = 1;
		 foreach ($ExcludeDays as $exday) {
			 if ($exday == $DaysTextArray[$DayOfWeek]) {
				 $ManuFlag = 0;
			 }
		 }

		 $sql = "INSERT INTO mrpcalendar (
					calendardate,
					daynumber,
					manufacturingflag)
				 VALUES ('" . $DateAdd . "',
						'1',
						'" . $ManuFlag . "')";
		$result = DB_query($sql,$ErrMsg);
	}

	// Update daynumber. Set it so non-manufacturing days will have the same daynumber as a valid
	// manufacturing day that precedes it. That way can read the table by the non-manufacturing day,
	// subtract the leadtime from the daynumber, and find the valid manufacturing day with that daynumber.
	$DayNumber = 1;
	$sql = "SELECT * FROM mrpcalendar
			ORDER BY calendardate";
	$result = DB_query($sql,$ErrMsg);
	while ($myrow = DB_fetch_array($result)) {
		   if ($myrow['manufacturingflag'] == "1") {
			   $DayNumber++;
		   }
		   $CalDate = $myrow['calendardate'];
		   $sql = "UPDATE mrpcalendar SET daynumber = '" . $DayNumber . "'
					WHERE calendardate = '" . $CalDate . "'";
		   $resultupdate = DB_query($sql,$ErrMsg);
	}
	echo prnMsg(_('The MRP Calendar has been created'),'success');
	ShowInputForm($ChangeDate);

} // End of function submit()


function update(&$ChangeDate)  //####UPDATE_UPDATE_UPDATE_UPDATE_UPDATE_UPDATE_UPDATE_####
{
// Change manufacturing flag for a date. The value "1" means the date is a manufacturing date.
// After change the flag, re-calculate the daynumber for all dates.

	$InputError = 0;
	$CalDate = FormatDateForSQL($ChangeDate);
	$sql="SELECT COUNT(*) FROM mrpcalendar
		  WHERE calendardate='$CalDate'
		  GROUP BY calendardate";
	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	if ($myrow[0] < 1  ||  !Is_Date($ChangeDate))  {
		$InputError = 1;
		echo prnMsg(_('Invalid Exclude Date'),'error');
	}

	 if ($InputError == 1) {
		ShowInputForm($ChangeDate);
		return;
	 }

	$sql="SELECT mrpcalendar.* FROM mrpcalendar WHERE calendardate='$CalDate'";
	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	$newmanufacturingflag = 0;
	if ($myrow[2] == 0) {
		$newmanufacturingflag = 1;
	}
	$sql = "UPDATE mrpcalendar SET manufacturingflag = '".$newmanufacturingflag."'
			WHERE calendardate = '".$CalDate."'";
	$ErrMsg = _('Cannot update the MRP Calendar');
	$resultupdate = DB_query($sql,$ErrMsg);
	echo prnMsg(_('The MRP calendar record for') . ' ' . $ChangeDate  . ' ' . _('has been updated'),'success');
	unset ($ChangeDate);
	ShowInputForm($ChangeDate);

	// Have to update daynumber any time change a date from or to a manufacturing date
	// Update daynumber. Set it so non-manufacturing days will have the same daynumber as a valid
	// manufacturing day that precedes it. That way can read the table by the non-manufacturing day,
	// subtract the leadtime from the daynumber, and find the valid manufacturing day with that daynumber.
	$DayNumber = 1;
	$sql = "SELECT * FROM mrpcalendar ORDER BY calendardate";
	$result = DB_query($sql,$ErrMsg);
	while ($myrow = DB_fetch_array($result)) {
		   if ($myrow['manufacturingflag'] == '1') {
			   $DayNumber++;
		   }
		   $CalDate = $myrow['calendardate'];
		   $sql = "UPDATE mrpcalendar SET daynumber = '" . $DayNumber . "'
					WHERE calendardate = '" . $CalDate . "'";
		   $resultupdate = DB_query($sql,$ErrMsg);
	} // End of while

} // End of function update()


function ShowDays()  {//####LISTALL_LISTALL_LISTALL_LISTALL_LISTALL_LISTALL_LISTALL_####

// List all records in date range
	$FromDate = FormatDateForSQL($_POST['FromDate']);
	$ToDate = FormatDateForSQL($_POST['ToDate']);
	$sql = "SELECT calendardate,
				   daynumber,
				   manufacturingflag,
				   DAYNAME(calendardate) as dayname
			FROM mrpcalendar
			WHERE calendardate >='" . $FromDate . "'
			AND calendardate <='" . $ToDate . "'";

	$ErrMsg = _('The SQL to find the parts selected failed with the message');
	$result = DB_query($sql,$ErrMsg);

	echo '<br />
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead> 
		<tr>
			<th>' . _('Date') . '</th>
			<th>' . _('Day') . '</th>
			<th>' . _('Available for Manufacturing?') . '</th>
		</tr></thead> ';
	$ctr = 0;
	while ($myrow = DB_fetch_array($result)) {
		$flag = _('Yes');
		if ($myrow['manufacturingflag'] == 0) {
			$flag = _('No');
		}
		printf('<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
				</tr>',
				ConvertSQLDate($myrow[0]),
				_($myrow[3]),
				$flag);
	} //END WHILE LIST LOOP

	echo '</table></div></div></div>';
	echo '<br />';
	unset ($ChangeDate);
	ShowInputForm($ChangeDate);

} // End of function ShowDays()


function ShowInputForm(&$ChangeDate)  {//####DISPLAY_DISPLAY_DISPLAY_DISPLAY_DISPLAY_DISPLAY_#####

// Display form fields. This function is called the first time
// the page is called, and is also invoked at the end of all of the other functions.

	if (!isset($_POST['FromDate'])) {
		$_POST['FromDate']=date($_SESSION['DefaultDateFormat']);
		$_POST['ToDate']=date($_SESSION['DefaultDateFormat']);
	}
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
          
			<br />';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	

	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('From Date') . '</label>
			<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="FromDate" required="required" size="11" maxlength="10" value="' . $_POST['FromDate'] . '" /></div></div>
			<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-12 control-label">' . _('To Date') . '</label>
			<input type="text" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="ToDate" required="required" size="11" maxlength="10" value="' . $_POST['ToDate'] . '" /></div>
		</div>
		</div>
		
		<div class="row">
		<h4 class="sub-header">
		' . _('Exclude The Following Days from Manufacturing') . '</h4>
		 <div class="col-xs-4">
		 
         <div class="checkbox">
		 <label for="example-Saturday"> 
			<input type="checkbox"id="bordered-checkbox1" name="Saturday" value="Saturday" /> ' . _('Saturday') . ' </label>
		</div>
		<div class="checkbox">
		 <label for="example-Sunday">
			<input type="checkbox" name="Sunday" value="Sunday" />' . _('Sunday') . '</label>
		</div>
		 <div class="checkbox">
		 <label for="example-Monday">
		 <input type="checkbox" name="Monday" value="Monday" />' . _('Monday') . '</label>
		</div>
		 <div class="checkbox">
		 <label for="example-Tuesday">
		<input type="checkbox" name="Tuesday" value="Tuesday" />' . _('Tuesday') . '</label>
		</div>
		<div class="checkbox">
		 <label for="example-Wednesday">
			<input type="checkbox" name="Wednesday" value="Wednesday" />' . _('Wednesday') . '</label>
		</div>
		 <div class="checkbox">
		 <label for="example-Thursday">
			<input type="checkbox" name="Thursday" value="Thursday" />' . _('Thursday') . '</label>
		</div>
		 <div class="checkbox">
		 <label for="example-Friday">
			<input type="checkbox" name="Friday" value="Friday" />' . _('Friday') . '</label>
		</div>
		</div>
		</div>
		<div class="row">
		 <div class="col-xs-4">
			<input type="submit" name="submit" class="btn btn-success" value="' . _('Create Calendar') . '" /></div>
			 <div class="col-xs-4">
			<input type="submit" name="ListAll" class="btn btn-info" value="' . _('View Calendar') . '" /></div>
		</div>';

	if (!isset($_POST['ChangeDate'])) {
		$_POST['ChangeDate']=date($_SESSION['DefaultDateFormat']);
	}

	echo '<div class="sub-header"></div><br />

		<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Exclude following date from Manufacturing') . '</label>
			<input type="text" name="ChangeDate" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" size="11" maxlength="10" value="' . $_POST['ChangeDate'] . '" /></div></div>
<div class="col-xs-4">
<div class="form-group"><br />
<input type="submit" name="update" class="btn btn-info" value="' . _('Update') . '" /></div>
		</div>
		</div>
		
		</form>';

} // End of function ShowInputForm()

include('includes/footer.php');
?>
