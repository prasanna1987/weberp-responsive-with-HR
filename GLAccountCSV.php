<?php



include ('includes/session.php');
$Title = _('General Ledger Account Report');

$ViewTopic= 'GeneralLedger';
$BookMark = 'GLAccountCSV';

include('includes/header.php');
include('includes/GLPostings.inc');

if (isset($_POST['Period'])){
	$SelectedPeriod = $_POST['Period'];
} elseif (isset($_GET['Period'])){
	$SelectedPeriod = $_GET['Period'];
}

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('General Ledger Account Report') . '<br /><small>';

echo '' . _('Use Ctrl/Shift key to select multiple accounts and periods') . '</small></h1></a></div>';
echo '<div class="row gutter30">
<div class="col-xs-12">';
echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/*Dates in SQL format for the last day of last month*/
$DefaultPeriodDate = Date ('Y-m-d', Mktime(0,0,0,Date('m'),0,Date('Y')));

/*Show a form to allow input of criteria for the report */
echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Accounts') . '</label>
	        <select name="Account[]" size="12" multiple="multiple" class="form-control">';
$sql = "SELECT chartmaster.accountcode,
			   chartmaster.accountname
		FROM chartmaster
		INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		ORDER BY chartmaster.accountcode";
$AccountsResult = DB_query($sql);
$i=0;
while ($myrow=DB_fetch_array($AccountsResult)){
	if(isset($_POST['Account'][$i]) AND $myrow['accountcode'] == $_POST['Account'][$i]){
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
		$i++;
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
	}
}
echo '</select></div></div>';

echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Period range').'</label>
		<select name="Period[]" size="12" multiple="multiple" class="form-control">';
$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno DESC";
$Periods = DB_query($sql);
$id=0;

while ($myrow=DB_fetch_array($Periods)){
	if (isset($SelectedPeriod[$id]) and $myrow['periodno'] == $SelectedPeriod[$id]){
		echo '<option selected="selected" value="' . $myrow['periodno'] . '">' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
		$id++;
	} else {
		echo '<option value="' . $myrow['periodno'] . '">' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
	}
}
echo '</select></div></div>';

//Select the tag
echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Select Tag') . '</label>
<select name="tag" class="form-control">';

$SQL = "SELECT tagref,
	       tagdescription
		FROM tags
		ORDER BY tagref";

$result=DB_query($SQL);
echo '<option value="0">0 - ' . _('All tags') . '</option>';
while ($myrow=DB_fetch_array($result)){
	if (isset($_POST['tag']) and $_POST['tag']==$myrow['tagref']){
	   echo '<option selected="selected" value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	} else {
	   echo '<option value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	}
}
echo '</select></div></div>';
// End select tag

echo '</div>
		<div class="row">
		<div class="col-xs-4">
<div class="form-group">
		<input type="submit" name="MakeCSV" class="btn btn-warning" value="'._('Submit').'" /></div>
    </div></div>
	</form></div></div>';

/* End of the Form  rest of script is what happens if the show button is hit*/

if (isset($_POST['MakeCSV'])){

	if (!isset($SelectedPeriod)){
		echo prnMsg(_('A period or range of periods must be selected from the list box'),'info');
		include('includes/footer.php');
		exit;
	}
	if (!isset($_POST['Account'])){
		echo prnMsg(_('An account or range of accounts must be selected from the list box'),'info');
		include('includes/footer.php');
		exit;
	}

	if (!file_exists($_SESSION['reports_dir'])){
		$Result = mkdir('./' . $_SESSION['reports_dir']);
	}

	$FileName = $_SESSION['reports_dir'] . '/Accounts_Listing_' . Date('Y-m-d') .'.csv';

	$fp = fopen($FileName,'w');

	if ($fp==FALSE){
		echo prnMsg(_('Could not open or create the file under') . ' ' . $FileName,'error');
		include('includes/footer.php');
		exit;
	}

	foreach ($_POST['Account'] as $SelectedAccount){
		/*Is the account a balance sheet or a profit and loss account */
		$SQL = "SELECT chartmaster.accountname,
								accountgroups.pandl
							    FROM accountgroups
							    INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_
							    WHERE chartmaster.accountcode='" . $SelectedAccount . "'";
		$result = DB_query($SQL);
		$AccountDetailRow = DB_fetch_row($result);
		$AccountName = $AccountDetailRow[1];
		if ($AccountDetailRow[1]==1){
			$PandLAccount = True;
		}else{
			$PandLAccount = False; /*its a balance sheet account */
		}

		$FirstPeriodSelected = min($SelectedPeriod);
		$LastPeriodSelected = max($SelectedPeriod);

		if ($_POST['tag']==0) {
	 		$sql= "SELECT type,
				      typename,
				      gltrans.typeno,
				      gltrans.trandate,
				      gltrans.narrative,
          			      gltrans.amount,
				      gltrans.periodno,
				      gltrans.tag
				FROM gltrans, systypes
				WHERE gltrans.account = '" . $SelectedAccount . "'
				AND systypes.typeid=gltrans.type
				AND posted=1
				AND periodno>='" . $FirstPeriodSelected . "'
				AND periodno<='" . $LastPeriodSelected . "'
				ORDER BY periodno, gltrans.trandate, counterindex";

		} else {
	 		$sql= "SELECT gltrans.type,
						gltrans.typename,
						gltrans.typeno,
						gltrans.trandate,
						gltrans.narrative,
						gltrans.amount,
						gltrans.periodno,
						gltrans.tag
					FROM gltrans, systypes
					WHERE gltrans.account = '" . $SelectedAccount . "'
					AND systypes.typeid=gltrans.type
					AND posted=1
					AND periodno>='" . $FirstPeriodSelected . "'
					AND periodno<='" . $LastPeriodSelected . "'
					AND tag='".$_POST['tag']."'
					ORDER BY periodno, gltrans.trandate, counterindex";
		}

		$ErrMsg = _('The transactions for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved because') ;
		$TransResult = DB_query($sql,$ErrMsg);

		fwrite($fp, $SelectedAccount . ' - ' . $AccountName . ' ' . _('for period'). ' ' . $FirstPeriodSelected . ' ' . _('to') . ' ' . $LastPeriodSelected . "\n");
		if ($PandLAccount==True) {
			$RunningTotal = 0;
		} else {
			$sql = "SELECT bfwd,
					actual,
					period
				FROM chartdetails
				WHERE chartdetails.accountcode= '" . $SelectedAccount . "'
				AND chartdetails.period='" . $FirstPeriodSelected . "'";

			$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
			$ChartDetailsResult = DB_query($sql,$ErrMsg);
			$ChartDetailRow = DB_fetch_array($ChartDetailsResult);

			$RunningTotal =$ChartDetailRow['bfwd'];
			if ($RunningTotal < 0 ){
				fwrite($fp,$SelectedAccount . ', '  .$FirstPeriodSelected . ', ' . _('Brought Forward Balance') . ',,,,' . -$RunningTotal . "\n");
			} else {
				fwrite($fp,$SelectedAccount . ', '  .$FirstPeriodSelected . ', ' . _('Brought Forward Balance') . ',,,' . $RunningTotal . "\n");
			}
		}
		$PeriodTotal = 0;
		$PeriodNo = -9999;

		while ($myrow=DB_fetch_array($TransResult)) {

			if ($myrow['periodno']!=$PeriodNo){
				if ($PeriodNo!=-9999){ //ie its not the first time around
					/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/
					$sql = "SELECT bfwd,
									actual,
									period
							FROM chartdetails
							WHERE chartdetails.accountcode= '" . $SelectedAccount . "'
							AND chartdetails.period='" . $PeriodNo . "'";

					$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
					$ChartDetailsResult = DB_query($sql,$ErrMsg);
					$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
					if ($PeriodTotal < 0) {
						fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,,' . -$PeriodTotal. "\n");
					} else {
						fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,' . $PeriodTotal. "\n");
					}
				}
				$PeriodNo = $myrow['periodno'];
				$PeriodTotal = 0;
			}

			$RunningTotal += $myrow['amount'];
			$PeriodTotal += $myrow['amount'];

			$FormatedTranDate = ConvertSQLDate($myrow['trandate']);

			$tagsql="SELECT tagdescription FROM tags WHERE tagref='".$myrow['tag'] . "'";
			$tagresult=DB_query($tagsql);
			$tagrow = DB_fetch_array($tagresult);
			if ($myrow['amount']<0){
				fwrite($fp, $SelectedAccount . ',' . $myrow['periodno'] . ', ' . $myrow['typename'] . ',' . $myrow['typeno'] . ',' . $FormatedTranDate . ',,' . -$myrow['amount'] . ',' . $myrow['narrative'] . ',' . $tagrow['tagdescription']. "\n");
			} else {
				fwrite($fp, $SelectedAccount . ',' . $myrow['periodno'] . ', ' . $myrow['typename'] . ',' . $myrow['typeno'] . ',' . $FormatedTranDate . ',' . $myrow['amount'] . ',,' . $myrow['narrative'] . ',' . $tagrow['tagdescription']. "\n");
			}
		} //end loop around GLtrans
		if ($PeriodTotal <>0){
			if ($PeriodTotal < 0){
				fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,,' . -$PeriodTotal. "\n");
			} else {
				fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,' . $PeriodTotal. "\n");
			}
		}
		if ($PandLAccount==True){
			if ($RunningTotal < 0){
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Total Period Movement') . ',,,,' . -$RunningTotal . "\n");
			} else {
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Total Period Movement') . ',,,' . $RunningTotal . "\n");
			}
		} else { /*its a balance sheet account*/
			if ($RunningTotal < 0){
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Balance C/Fwd') . ',,,,' . -$RunningTotal . "\n");
			} else {
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Balance C/Fwd') . ',,,' . $RunningTotal . "\n");
			}
		}

	} /*end for each SelectedAccount */
	fclose($fp);
	echo '<p align="left"><a href="' .  $FileName . '" class="btn btn-info">' . _('Download') . '</a> ' .  '<p><br />';
} /* end of if CreateCSV button hit */

include('includes/footer.php');
?>