<?php
/* This script shows the balance sheet for the company as at a specified date. */

/*Through deviousness and cunning, this system allows shows the balance sheets as at the end of any period selected - so first off need to show the input of criteria screen while the user is selecting the period end of the balance date meanwhile the system is posting any unposted transactions */


include ('includes/session.php');
$Title = _('Balance Sheet');// Screen identification.
$ViewTopic = 'GeneralLedger';// Filename's id in ManualContents.php's TOC.
$BookMark = 'BalanceSheet';// Anchor's id in the manual's html document.
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.php'); // This loads the $Sections variable


if (! isset($_POST['BalancePeriodEnd']) or isset($_POST['SelectADifferentPeriod'])){

	/*Show a form to allow input of criteria for TB to show */
	include('includes/header.php');
	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
		_('Balance Sheet') . '</h1></a></div>';// Page title.
//		_('Print Statement of Financial Position') . '</p>';// Page title.

	echo '<div class="row"><p class="text-info">'
		. _('Please Note nERP is an "accrual" based system (not a "cash based" system).  Accrual systems include items when they are invoiced to the customer, and when expenses are owed based on the supplier invoice date.') . '</p></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
	
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Balance Sheet date').'</label>
				<select required="required" name="BalancePeriodEnd" class="form-control">';

	$periodno=GetPeriod(Date($_SESSION['DefaultDateFormat']));
	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='".$periodno . "'";
	$result = DB_query($sql);
	$myrow=DB_fetch_array($result);
	$lastdate_in_period=$myrow[0];

	$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno DESC";
	$Periods = DB_query($sql);

	while ($myrow=DB_fetch_array($Periods)){
		if( $myrow['periodno']== $periodno){
			echo '<option selected="selected" value="' . $myrow['periodno'] . '">' . ConvertSQLDate($lastdate_in_period) . '</option>';
		} else {
			echo '<option value="' . $myrow['periodno'] . '">' . ConvertSQLDate($myrow['lastdate_in_period'])  . '</option>';
		}
	}

	echo '</select></div></div>';

	echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Type').'</label>
			<select required="required" class="form-control" name="Detail" title="' . _('Selecting Summary will show on the totals at the account group level') . '" >
				<option value="Summary">' . _('Summary') . '</option>
				<option selected="selected" value="Detailed">' . _('Detailed') . '</option>
			</select></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Show zero balance accounts?') . '</label>
			<div class="checkbox"><label class="example-checkbox1"><input type="checkbox" title="' . _('Check this box to display all accounts including those accounts with no balance') . '" name="ShowZeroBalances" /></label></div>
			</div>
		</div>
		</div>';

	echo '
			<div class="row">
			<div class="col-xs-4">
<div class="form-group has-error"> 
				<input type="submit" class="btn btn-info" name="ShowBalanceSheet" value="'._('Show').'" />
			</div></div>';
	echo '<div class="col-xs-4">
<div class="form-group has-error"> 
				<input type="submit" name="PrintPDF" class="btn btn-warning" value="'._('Print PDF').'" />
			</div></div>';
	echo '</div>';// div class=?
	echo '</form></div></div>';

	/*Now do the posting while the user is thinking about the period to select */
	include ('includes/GLPostings.inc');

} elseif (isset($_POST['PrintPDF'])) {

	include('includes/PDFStarter.php');
	$pdf->addInfo('Title', _('Balance Sheet') );
	$pdf->addInfo('Subject', _('Balance Sheet') );
	$line_height = 12;
	$PageNumber = 0;
	$FontSize = 10;

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $_POST['BalancePeriodEnd'] . "'";
	$PrdResult = DB_query($sql);
	$myrow = DB_fetch_row($PrdResult);
	$BalanceDate = ConvertSQLDate($myrow[0]);

	/*Calculate B/Fwd retained earnings */

	$SQL = "SELECT Sum(CASE WHEN chartdetails.period='" . $_POST['BalancePeriodEnd'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS accumprofitbfwd,
			Sum(CASE WHEN chartdetails.period='" . ($_POST['BalancePeriodEnd'] - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lyaccumprofitbfwd
		FROM chartmaster INNER JOIN accountgroups
		ON chartmaster.group_ = accountgroups.groupname INNER JOIN chartdetails
		ON chartmaster.accountcode= chartdetails.accountcode
		WHERE accountgroups.pandl=1";

	$AccumProfitResult = DB_query($SQL);
	if (DB_error_no() !=0) {
		$Title = _('Balance Sheet') . ' - ' . _('Problem Report') . '....';
		include('includes/header.php');
		echo prnMsg( _('The accumulated profits brought forward could not be calculated by nERP because') . ' - ' . DB_error_msg() );
		echo '<br /><p align="right"><a href="' . $RootPath . '/menu_data.php?Application=GL" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';
		if ($debug==1){
			echo '<br />' .  $SQL;
		}
		include('includes/footer.php');
		exit;
	}

	$AccumProfitRow = DB_fetch_array($AccumProfitResult); /*should only be one row returned */

	$SQL = "SELECT accountgroups.sectioninaccounts,
			accountgroups.groupname,
			accountgroups.parentgroupname,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['BalancePeriodEnd'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS balancecfwd,
			Sum(CASE WHEN chartdetails.period='" . ($_POST['BalancePeriodEnd'] - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lybalancecfwd
		FROM chartmaster
			INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails	ON chartmaster.accountcode= chartdetails.accountcode
			INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		WHERE accountgroups.pandl=0
		GROUP BY accountgroups.groupname,
			chartdetails.accountcode,
			chartmaster.accountname,
			accountgroups.parentgroupname,
			accountgroups.sequenceintb,
			accountgroups.sectioninaccounts
		ORDER BY accountgroups.sectioninaccounts,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";

	$AccountsResult = DB_query($SQL);

	if (DB_error_no() !=0) {
		$Title = _('Balance Sheet') . ' - ' . _('Problem Report') . '....';
		include('includes/header.php');
		echo prnMsg( _('No general ledger accounts were returned by nERP because') . ' - ' . DB_error_msg() );
		echo '<br /><p align="right"><a href="' . $RootPath . '/menu_data.php?Application=GL" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';
		if ($debug==1){
			echo '<br />' .  $SQL;
		}
		include('includes/footer.php');
		exit;
	}

    $ListCount = DB_num_rows($AccountsResult); // UldisN

	include('includes/PDFBalanceSheetPageHeader.inc');

	$Section='';
	$SectionBalance = 0;
	$SectionBalanceLY = 0;

	$LYCheckTotal = 0;
	$CheckTotal = 0;

	$ActGrp ='';
	$Level =0;
	$ParentGroups = array();
	$ParentGroups[$Level]='';
	$GroupTotal = array(0);
	$LYGroupTotal = array(0);

	while ($myrow=DB_fetch_array($AccountsResult)) {
		$AccountBalance = $myrow['balancecfwd'];
		$LYAccountBalance = $myrow['lybalancecfwd'];

		if ($myrow['accountcode'] == $RetainedEarningsAct){
			$AccountBalance += $AccumProfitRow['accumprofitbfwd'];
			$LYAccountBalance += $AccumProfitRow['lyaccumprofitbfwd'];
		}
		if ($ActGrp !=''){
        		if ($myrow['groupname']!=$ActGrp){
					$FontSize = 8;
					$pdf->setFont('','B');
        			while ($myrow['groupname']!= $ParentGroups[$Level] AND $Level>0) {
        				$YPos -= $line_height;
        				$LeftOvers = $pdf->addTextWrap($Left_Margin+(10 * ($Level+1)),$YPos,200,$FontSize,_('Total') . ' ' . $ParentGroups[$Level]);
        				$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
        				$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
        				$ParentGroups[$Level]='';
        				$GroupTotal[$Level]=0;
        				$LYGroupTotal[$Level]=0;
        				$Level--;
                        if ($YPos < $Bottom_Margin){
                            include('includes/PDFBalanceSheetPageHeader.inc');
                        }
        			}
        			$YPos -= $line_height;
        			$LeftOvers = $pdf->addTextWrap($Left_Margin+(10 * ($Level+1)),$YPos,200,$FontSize,_('Total') . ' ' . $ParentGroups[$Level]);
        			$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
        			$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
        			$ParentGroups[$Level]='';
        			$GroupTotal[$Level]=0;
        			$LYGroupTotal[$Level]=0;
        			$YPos -= $line_height;
                    if ($YPos < $Bottom_Margin){
                        include('includes/PDFBalanceSheetPageHeader.inc');
                    }
        		}
        }

		if ($myrow['sectioninaccounts']!= $Section){

			if ($Section !=''){
				$FontSize = 8;
				$pdf->setFont('','B');
				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$Sections[$Section]);
				$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($SectionBalance,$_SESSION['CompanyRecord']['decimalplaces']),'right');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']),'right');
				$YPos -= (2 * $line_height);
				if ($YPos < $Bottom_Margin){
					include('includes/PDFBalanceSheetPageHeader.inc');
				}
			}
			$SectionBalanceLY = 0;
			$SectionBalance = 0;

			$Section = $myrow['sectioninaccounts'];
			if ($_POST['Detail']=='Detailed'){

				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$Sections[$myrow['sectioninaccounts']]);
				$YPos -= (2 * $line_height);
                if ($YPos < $Bottom_Margin){
                    include('includes/PDFBalanceSheetPageHeader.inc');
                }
			}
		}

		if ($myrow['groupname']!= $ActGrp){
			if ($YPos < $Bottom_Margin + $line_height){
			   include('includes/PDFBalanceSheetPageHeader.inc');
			}
			$FontSize =8;
			$pdf->setFont('','B');
			if ($myrow['parentgroupname']==$ActGrp AND $ActGrp!=''){
				$Level++;
			}
			$ActGrp = $myrow['groupname'];
			$ParentGroups[$Level] = $ActGrp;
			if ($_POST['Detail']=='Detailed'){
				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$myrow['groupname']);
				$YPos -= $line_height;
			}
			$GroupTotal[$Level]=0;
			$LYGroupTotal[$Level]=0;
		}

		$SectionBalanceLY +=	$LYAccountBalance;
		$SectionBalance	  +=	$AccountBalance;

		for ($i=0;$i<=$Level;$i++){
			$LYGroupTotal[$i]  +=	$LYAccountBalance;
			$GroupTotal[$i]	  +=	$AccountBalance;
		}
		$LYCheckTotal 	  +=	$LYAccountBalance;
		$CheckTotal  	  +=	$AccountBalance;


		if ($_POST['Detail']=='Detailed') {
			if (isset($_POST['ShowZeroBalances']) OR (!isset($_POST['ShowZeroBalances']) AND ($AccountBalance <> 0 OR $LYAccountBalance <> 0))){
				$FontSize =8;
      			$pdf->setFont('','');
      			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,50,$FontSize,$myrow['accountcode']);
      			$LeftOvers = $pdf->addTextWrap($Left_Margin+55,$YPos,200,$FontSize,$myrow['accountname']);
      			$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($AccountBalance,$_SESSION['CompanyRecord']['decimalplaces']),'right');
      			$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($LYAccountBalance,$_SESSION['CompanyRecord']['decimalplaces']),'right');
      			$YPos -= $line_height;
			}
		}
		if ($YPos < ($Bottom_Margin)){
			include('includes/PDFBalanceSheetPageHeader.inc');
		}
	}//end of loop

    $FontSize = 8;
	$pdf->setFont('','B');
	while ($Level>0) {
		$YPos -= $line_height;
		$LeftOvers = $pdf->addTextWrap($Left_Margin+(10 * ($Level+1)),$YPos,200,$FontSize,_('Total') . ' ' . $ParentGroups[$Level]);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$ParentGroups[$Level]='';
		$GroupTotal[$Level]=0;
		$LYGroupTotal[$Level]=0;
		$Level--;
	}
	$YPos -= $line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin+(10 * ($Level+1)),$YPos,200,$FontSize,_('Total') . ' ' . $ParentGroups[$Level]);
	$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
	$ParentGroups[$Level]='';
	$GroupTotal[$Level]=0;
	$LYGroupTotal[$Level]=0;
	$YPos -= $line_height;

	if ($SectionBalanceLY+$SectionBalance !=0){
		$FontSize =8;
		$pdf->setFont('','B');
		$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$Sections[$Section]);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($SectionBalance,$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$YPos -= $line_height;
	}

	$YPos -= $line_height;

	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,_('Check Total'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,100,$FontSize,locale_number_format($CheckTotal,$_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos,100,$FontSize,locale_number_format($LYCheckTotal,$_SESSION['CompanyRecord']['decimalplaces']),'right');

	if ($ListCount == 0) {   //UldisN
		$Title = _('Print Balance Sheet Error');
		include('includes/header.php');
		echo prnMsg( _('There were no entries to print out for the selections specified'),'error' );
		echo '<br /><p align="right"><a href="' . $RootPath . '/menu_data.php?Application=GL" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';
		include('includes/footer.php');
		exit;
	} else {
	    $pdf->OutputD($_SESSION['DatabaseName'] . '_GL_Balance_Sheet_' . date('Y-m-d') . '.pdf');
        $pdf->__destruct();
	}
	exit;
} else {
	include('includes/header.php');
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
	
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<input type="hidden" name="BalancePeriodEnd" value="' . $_POST['BalancePeriodEnd'] . '" />';

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $_POST['BalancePeriodEnd'] . "'";
	$PrdResult = DB_query($sql);
	$myrow = DB_fetch_row($PrdResult);
	$BalanceDate = ConvertSQLDate($myrow[0]);

	/*Calculate B/Fwd retained earnings */

	$SQL = "SELECT Sum(CASE WHEN chartdetails.period='" . $_POST['BalancePeriodEnd'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS accumprofitbfwd,
			Sum(CASE WHEN chartdetails.period='" . ($_POST['BalancePeriodEnd'] - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lyaccumprofitbfwd
		FROM chartmaster INNER JOIN accountgroups
		ON chartmaster.group_ = accountgroups.groupname INNER JOIN chartdetails
		ON chartmaster.accountcode= chartdetails.accountcode
		WHERE accountgroups.pandl=1";

	$AccumProfitResult = DB_query($SQL,_('The accumulated profits brought forward could not be calculated by the SQL because'));

	$AccumProfitRow = DB_fetch_array($AccumProfitResult); /*should only be one row returned */

	$SQL = "SELECT accountgroups.sectioninaccounts,
			accountgroups.groupname,
			accountgroups.parentgroupname,
			chartdetails.accountcode,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['BalancePeriodEnd'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS balancecfwd,
			Sum(CASE WHEN chartdetails.period='" . ($_POST['BalancePeriodEnd'] - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lybalancecfwd
		FROM chartmaster
			INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails	ON chartmaster.accountcode= chartdetails.accountcode
			INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		WHERE accountgroups.pandl=0
		GROUP BY accountgroups.groupname,
			chartdetails.accountcode,
			chartmaster.accountname,
			accountgroups.parentgroupname,
			accountgroups.sequenceintb,
			accountgroups.sectioninaccounts
		ORDER BY accountgroups.sectioninaccounts,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";

	$AccountsResult = DB_query($SQL,_('No general ledger accounts were returned by the SQL because'));

	// Page title as IAS1 numerals 10 and 51:
	include_once('includes/CurrenciesArray.php');// Array to retrieve currency name.
	echo '<div id="Report">';// Division to identify the report block.
	echo '<div class="block-header"><a href="" class="header-title-link"><h1> ' .// Icon title.
		_('Balance Sheet') . '<br /><small>' .// Page title, reporting statement.
//		_('Statement of Financial Position') . '<br />' .// Page title, reporting statement.
		stripslashes($_SESSION['CompanyRecord']['coyname']) . ', ' .// Page title, reporting entity.
		_('as at') . ' ' . $BalanceDate . ' , ' .// Page title, reporting period.
		_('All amounts stated in').': '. _($CurrencyName[$_SESSION['CompanyRecord']['currencydefault']]).'</small></h1></a></div>';// Page title, reporting presentation currency and level of rounding used.

	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

	if ($_POST['Detail']=='Detailed'){
		$TableHeader = '<tr>
							<th>' . _('Account') . '</th>
							<th>' . _('Account Name') . '</th>
							<th colspan="2">' . $BalanceDate . '</th>
							<th colspan="2">' . _('Last Year') . '</th>
						</tr>';
	} else { /*summary */
		$TableHeader = '<tr>
							<th colspan="2"></th>
							<th colspan="2">' . $BalanceDate . '</th>
							<th colspan="2">' ._('Last Year') . '</th>
						</tr>';
	}
/* echo '<thead>' . $TableHeader . '<thead><tbody>';// thead used in conjunction with tbody enable scrolling of the table body independently of the header and footer. Also, when printing a large table that spans multiple pages, these elements can enable the table header to be printed at the top of each page. */

	$Section='';
	$SectionBalance = 0;
	$SectionBalanceLY = 0;

	$LYCheckTotal = 0;
	$CheckTotal = 0;

	$ActGrp ='';
	$Level=0;
	$ParentGroups=array();
	$ParentGroups[$Level]='';
	$GroupTotal = array(0);
	$LYGroupTotal = array(0);

	echo $TableHeader;
	$j=0; //row counter

	while ($myrow=DB_fetch_array($AccountsResult)) {
		$AccountBalance = $myrow['balancecfwd'];
		$LYAccountBalance = $myrow['lybalancecfwd'];

		if ($myrow['accountcode'] == $RetainedEarningsAct){
			$AccountBalance += $AccumProfitRow['accumprofitbfwd'];
			$LYAccountBalance += $AccumProfitRow['lyaccumprofitbfwd'];
		}

		if ($myrow['groupname']!= $ActGrp AND $ActGrp != '') {
			if ($myrow['parentgroupname']!=$ActGrp){
				while ($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0){
					if ($_POST['Detail']=='Detailed'){
						echo '<tr>
								<td colspan="2"></td>
      							<td><hr /></td>
								<td></td>
								<td><hr /></td>
								<td></td>
							</tr>';
					}
					printf('<tr>
                              <td colspan="2"><I>%s</I></td>
							  <td class="number">%s</td>
							  <td></td>
							  <td class="number">%s</td>
							</tr>',
							$ParentGroups[$Level],
							locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
							locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces'])
							);
					$GroupTotal[$Level] = 0;
					$LYGroupTotal[$Level] = 0;
					$ParentGroups[$Level]='';
					$Level--;
					$j++;
				}
				if ($_POST['Detail']=='Detailed'){
					echo '<tr>
							<td colspan="2"></td>
							<td><hr /></td>
							<td></td>
							<td><hr /></td>
							<td></td>
						</tr>';
				}

				printf('<tr>
                          <td colspan="2">%s</td>
						  <td class="number">%s</td>
						  <td></td>
						  <td class="number">%s</td>
						</tr>',
						$ParentGroups[$Level],
						locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']) );

				$GroupTotal[$Level] = 0;
				$LYGroupTotal[$Level] = 0;
				$ParentGroups[$Level]='';
				$j++;
			}
		}
		if ($myrow['sectioninaccounts']!= $Section ){

			if ($Section!=''){
				if ($_POST['Detail']=='Detailed'){
					echo '<tr>
							<td colspan="2"></td>
							<td><hr /></td>
							<td></td>
							<td><hr /></td>
							<td></td>
						</tr>';
				} else {
					echo '<tr>
							<td colspan="3"></td>
							<td><hr /></td>
							<td></td>
							<td><hr /></td>
						</tr>';
				}

				printf('<tr>
							<td colspan="3"><h2>%s</h2></td>
							<td class="number">%s</td>
							<td></td>
							<td class="number">%s</td>
						</tr>',
				$Sections[$Section],
				locale_number_format($SectionBalance,$_SESSION['CompanyRecord']['decimalplaces']),
				locale_number_format($SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']));
				$j++;
			}
			$SectionBalanceLY = 0;
			$SectionBalance = 0;
			$Section = $myrow['sectioninaccounts'];


			if ($_POST['Detail']=='Detailed'){
				printf('<tr>
						  <td colspan="6"><h1>%s</h1></td>
						</tr>',
						$Sections[$myrow['sectioninaccounts']]);
			}
		}

		if ($myrow['groupname']!= $ActGrp){

			if ($ActGrp!='' AND $myrow['parentgroupname']==$ActGrp){
				$Level++;
			}

			if ($_POST['Detail']=='Detailed'){
				$ActGrp = $myrow['groupname'];
				printf('<tr>
						  <td colspan="6"><h3>%s</h3></td>
						</tr>',
						$myrow['groupname']);
				echo $TableHeader;
			}
			$GroupTotal[$Level]=0;
			$LYGroupTotal[$Level]=0;
			$ActGrp = $myrow['groupname'];
			$ParentGroups[$Level]=$myrow['groupname'];
			$j++;
		}

		$SectionBalanceLY +=	$LYAccountBalance;
		$SectionBalance	  +=	$AccountBalance;
		for ($i=0;$i<=$Level;$i++){
			$LYGroupTotal[$i] += $LYAccountBalance;
			$GroupTotal[$i] += $AccountBalance;
		}
		$LYCheckTotal	  +=	$LYAccountBalance;
		$CheckTotal  	  +=	$AccountBalance;


		if ($_POST['Detail']=='Detailed'){
			if (isset($_POST['ShowZeroBalances']) OR (!isset($_POST['ShowZeroBalances']) AND (round($AccountBalance,$_SESSION['CompanyRecord']['decimalplaces']) <> 0 OR round($LYAccountBalance,$_SESSION['CompanyRecord']['decimalplaces']) <> 0))){

	  			$ActEnquiryURL = '<a href="' . $RootPath . '/GLAccountInquiry.php?Period=' . $_POST['BalancePeriodEnd'] . '&amp;Account=' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . '</a>';

	  			printf('<tr class="striped_row">
						<td>%s</td>
	  					<td>%s</td>
	  					<td class="number">%s</td>
	  					<td></td>
	  					<td class="number">%s</td>
	  					<td></td>
	  					</tr>',
	  					$ActEnquiryURL,
	  					htmlspecialchars($myrow['accountname'],ENT_QUOTES,'UTF-8',false),
	  					locale_number_format($AccountBalance,$_SESSION['CompanyRecord']['decimalplaces']),
	  					locale_number_format($LYAccountBalance,$_SESSION['CompanyRecord']['decimalplaces']));
	  			$j++;
			}
		}
	}
	//end of loop

	while ($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0){
		if ($_POST['Detail']=='Detailed'){
			echo '<tr>
					<td colspan="2"></td>
					<td><hr /></td>
					<td></td>
					<td><hr /></td>
					<td></td>
				</tr>';
		}
		printf('<tr>
                  <td colspan="2"><I>%s</I></td>
				  <td class="number">%s</td>
				  <td></td>
				  <td class="number">%s</td>
				</tr>',
				$ParentGroups[$Level],
				locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
				locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces'])
				);
		$Level--;
	}
	if ($_POST['Detail']=='Detailed'){
		echo '<tr>
				<td colspan="2"></td>
				<td><hr /></td>
				<td></td>
				<td><hr /></td>
				<td></td>
			</tr>';
	}

	printf('<tr>
              <td colspan="2">%s</td>
		      <td class="number">%s</td>
		      <td></td>
		      <td class="number">%s</td>
		   </tr>',
		$ParentGroups[$Level],
		locale_number_format($GroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
		locale_number_format($LYGroupTotal[$Level],$_SESSION['CompanyRecord']['decimalplaces'])
		);

	if ($_POST['Detail']=='Detailed'){
		echo '<tr>
		<td colspan="2"></td>
		<td><hr /></td>
		<td></td>
		<td><hr /></td>
		<td></td>
		</tr>';
	} else {
		echo '<tr>
		<td colspan="3"></td>
		<td><hr /></td>
		<td></td>
		<td><hr /></td>
		</tr>';
	}

	printf('<tr>
		<td colspan="3"><h2>%s</h2></td>
		<td class="number">%s</td>
		<td></td>
		<td class="number">%s</td>
		</tr>',
		$Sections[$Section],
		locale_number_format($SectionBalance,$_SESSION['CompanyRecord']['decimalplaces']),
		locale_number_format($SectionBalanceLY,$_SESSION['CompanyRecord']['decimalplaces']));

	$Section = $myrow['sectioninaccounts'];

	if (isset($myrow['sectioninaccounts']) and $_POST['Detail']=='Detailed'){
		printf('<tr>
				<td colspan="6"><h1>%s</h1></td>
				</tr>',
				$Sections[$myrow['sectioninaccounts']]);
	}

	echo '<tr>
			<td colspan="3"></td>
      		<td><hr /></td>
			<td></td>
			<td><hr /></td>
		</tr>';

	printf('<tr>
		<td colspan="3"><h2>' . _('Check Total') . '</h2></td>
		<td class="number">%s</td>
		<td></td>
		<td class="number">%s</td>
		</tr>',
		locale_number_format($CheckTotal,$_SESSION['CompanyRecord']['decimalplaces']),
		locale_number_format($LYCheckTotal,$_SESSION['CompanyRecord']['decimalplaces']));

	echo '<tr>
		<td colspan="3"></td>
      	<td><hr /></td>
		<td></td>
		<td><hr /></td>
		</tr>';
/*	echo '</tbody>';// See comment at the begin of the table.*/
	echo '</table>';
	echo '</div></div></div></div>';// div id="Report".
	echo '<br />
		<div class="row noprint"><div class="col-xs-4">', // Form buttons:
			'</div>', // "Print" button.
			'<div class="col-xs-4"><button name="SelectADifferentPeriod" type="submit" value="'. _('Select A Different Period') .'" class="btn btn-info">' . _('Select A Different Balance Date') . '</button></div>'.// "Select A Different Period" button.
			'<div class="col-xs-4"><button onclick="window.location=\'menu_data.php?Application=GL\'" type="button" class="btn btn-default">', _('Return'), '</button>', // "Return" button.
		'</div>';

	echo '</div><br />
';// div class=?
	echo '</form>';
}

include('includes/footer.php');
?>
