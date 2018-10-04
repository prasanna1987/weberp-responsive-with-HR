<?php

include('includes/session.php');

$Title = _('Search GL Accounts');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLAccountInquiry';
include('includes/header.php');

$msg='';
unset($Result);

if (isset($_POST['Search'])){

	if (mb_strlen($_POST['Keywords']>0) AND mb_strlen($_POST['GLCode'])>0) {
		$msg=_('Account name keywords have been used in preference to the account code extract entered');
	}
	if ($_POST['Keywords']=='' AND $_POST['GLCode']=='') {
            $SQL = "SELECT chartmaster.accountcode,
                    chartmaster.accountname,
                    chartmaster.group_,
                    CASE WHEN accountgroups.pandl!=0 THEN '" . _('Profit and Loss') . "' ELSE '" . _('Balance Sheet') ."' END AS pl
                    FROM chartmaster,
                        accountgroups,
						glaccountusers
					WHERE glaccountusers.accountcode = chartmaster.accountcode
						AND glaccountusers.userid='" .  $_SESSION['UserID'] . "'
						AND glaccountusers.canview=1
						AND chartmaster.group_=accountgroups.groupname
                    ORDER BY chartmaster.accountcode";
    }
	elseif (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$SQL = "SELECT chartmaster.accountcode,
					chartmaster.accountname,
					chartmaster.group_,
					CASE WHEN accountgroups.pandl!=0
						THEN '" . _('Profit and Loss') . "'
						ELSE '" . _('Balance Sheet') . "' END AS pl
				FROM chartmaster,
					accountgroups,
					glaccountusers
				WHERE glaccountusers.accountcode = chartmaster.accountcode
					AND glaccountusers.userid='" .  $_SESSION['UserID'] . "'
					AND glaccountusers.canview=1
					AND chartmaster.group_ = accountgroups.groupname
					AND accountname " . LIKE  . "'". $SearchString ."'
				ORDER BY accountgroups.sequenceintb,
					chartmaster.accountcode";

		} elseif (mb_strlen($_POST['GLCode'])>0){
			if (!empty($_POST['GLCode'])) {
				echo '<meta http-equiv="refresh" content="0; url=' . $RootPath . '/GLAccountInquiry.php?Account=' . $_POST['GLCode'] . '&Show=Yes">';
				exit;
			}

			$SQL = "SELECT chartmaster.accountcode,
					chartmaster.accountname,
					chartmaster.group_,
					CASE WHEN accountgroups.pandl!=0 THEN '" . _('Profit and Loss') . "' ELSE '" . _('Balance Sheet') ."' END AS pl
					FROM chartmaster,
						accountgroups,
						glaccountusers
				WHERE glaccountusers.accountcode = chartmaster.accountcode
					AND glaccountusers.userid='" .  $_SESSION['UserID'] . "'
					AND glaccountusers.canview=1
					AND chartmaster.group_=accountgroups.groupname
					AND chartmaster.accountcode >= '" . $_POST['GLCode'] . "'
					ORDER BY chartmaster.accountcode";
		}
		if (isset($SQL) and $SQL!=''){
			$Result = DB_query($SQL);
			if (DB_num_rows($Result) == 1) {
				$AccountRow = DB_fetch_row($Result);
				header('location:' . $RootPath . '/GLAccountInquiry.php?Account=' . $AccountRow[0] . '&Show=Yes');
				exit;
			}
		}
} //end of if search

$TargetPeriod = GetPeriod(date($_SESSION['DefaultDateFormat']));

if (!isset($AccountID)) {

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Search for General Ledger Accounts') . '</h1></a></div>
		<div class="row gutter30">
<div class="col-xs-12">

		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '" method="post">
		
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if(mb_strlen($msg)>1){
		prnMsg($msg,'info');
	}

	echo '<div class="row">
<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Account name-part or full') .'</label>
			<input type="text" class="form-control" name="Keywords" size="20" maxlength="25" /></div></div>
			';

	$SQLAccountSelect="SELECT chartmaster.accountcode,
							chartmaster.accountname
						FROM chartmaster
						INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
						ORDER BY chartmaster.accountcode";

	$ResultSelection=DB_query($SQLAccountSelect);
	echo '<div class="col-xs-3">
<div class="form-group"> <label class="col-md-8 control-label"><select name="GLCode" class="form-control">';
	echo '<option value="">' . _('Select an Account Code') . '</option>';
	while ($MyRowSelection=DB_fetch_array($ResultSelection)){
		if (isset($_POST['GLCode']) and $_POST['GLCode']==$MyRowSelection['accountcode']){
			echo '<option selected="selected" value="' . $MyRowSelection['accountcode'] . '">' . $MyRowSelection['accountcode'].' - ' .htmlspecialchars($MyRowSelection['accountname'], ENT_QUOTES,'UTF-8', false) . '</option>';
		} else {
			echo '<option value="' . $MyRowSelection['accountcode'] . '">' . $MyRowSelection['accountcode'].' - ' .htmlspecialchars($MyRowSelection['accountname'], ENT_QUOTES,'UTF-8', false)  . '</option>';
		}
	}
	echo '</select></div>';

	echo '	</div>
		
		<br />';

	echo '<div class="col-xs-3">
<div class="form-group"> <br />
			<input type="submit" class="btn btn-success" name="Search" value="' . _('Search') . '" />
			</div></div>
			</div>';

	if (isset($Result) and DB_num_rows($Result)>0) {

		echo '<br /><div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">';

		$TableHeader = '<tr>
							<th>' . _('Code') . '</th>
							<th>' . _('Account Name') . '</th>
							<th>' . _('Group') . '</th>
							<th>' . _('Account Type') . '</th>
							<th>' . _('Inquiry') . '</th>
							<th>' . _('Edit') . '</th>
						</tr>';

		echo $TableHeader;

		$j = 1;

		while ($MyRow=DB_fetch_array($Result)) {

			printf('<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="%s/GLAccountInquiry.php?Account=%s&amp;Show=Yes&FromPeriod=%s&ToPeriod=%s" class="btn btn-warning">Inquiry</td>
					<td><a href="%s/GLAccounts.php?SelectedAccount=%s" class="btn btn-warning">Edit</a></td>
					</tr>',
					htmlspecialchars($MyRow['accountcode'],ENT_QUOTES,'UTF-8',false),
					htmlspecialchars($MyRow['accountname'],ENT_QUOTES,'UTF-8',false),
					$MyRow['group_'],
					$MyRow['pl'],
					$RootPath,
					$MyRow['accountcode'],
					$TargetPeriod,
					$TargetPeriod,
					$RootPath,
					$Theme,
					$RootPath,
					$MyRow['accountcode'],
					$RootPath,
					$Theme);

			$j++;
			if ($j == 12){
				$j=1;
				echo $TableHeader;

			}
//end of page full new headings if
		}
//end of while loop

		echo '</table></div></div></div>';

	}
//end if results to show

	echo '
          </form></div></div>';

} //end AccountID already selected

include('includes/footer.php');
?>
