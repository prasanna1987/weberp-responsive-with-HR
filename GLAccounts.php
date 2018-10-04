<?php
/* Defines the general ledger accounts */
/* To delete, insert, or update an account. */

// BEGIN: Functions division ---------------------------------------------------
function CashFlowsActivityName($Activity) {
	// Converts the cash flow activity number to an activity text.
	switch($Activity) {
		case -1: return '<strong>' . _('Not set up') . '</strong>';
		case 0: return _('No effect on cash flow');
		case 1: return _('Operating activity');
		case 2: return _('Investing activity');
		case 3: return _('Financing activity');
		case 4: return _('Cash or cash equivalent');
		default: return '<strong>' . _('Unknown') . '</strong>';
	}
}
// END: Functions division -----------------------------------------------------

// BEGIN: Procedure division ---------------------------------------------------
include('includes/session.php');
$Title = _('General Ledger Accounts');
$ViewTopic= 'GeneralLedger';
$BookMark = 'GLAccounts';
include('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1> ', // Icon title.
	$Title, '</h1></a></div>';// Page title.

// Merges gets into posts:
if(isset($_GET['CashFlowsActivity'])) {// Select period from.
	$_POST['CashFlowsActivity'] = $_GET['CashFlowsActivity'];
}

if(isset($_POST['SelectedAccount'])) {
	$SelectedAccount = $_POST['SelectedAccount'];
} elseif(isset($_GET['SelectedAccount'])) {
	$SelectedAccount = $_GET['SelectedAccount'];
}

if(isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if(mb_strlen($_POST['AccountName']) >50) {
		$InputError = 1;
		echo prnMsg(_('The account name must be fifty characters or less'), 'warn');
	}

	if(isset($SelectedAccount) AND $InputError != 1) {

		$Sql = "UPDATE
					chartmaster SET accountname='" . $_POST['AccountName'] . "',
					group_='" . $_POST['Group'] . "',
					cashflowsactivity='" . $_POST['CashFlowsActivity'] . "'
				WHERE accountcode ='" . $SelectedAccount . "'";
		$ErrMsg = _('Could not update the account because');
		$Result = DB_query($Sql, $ErrMsg);

		echo prnMsg (_('The general ledger account has been updated'),'success');
	} elseif($InputError != 1) {

		/*SelectedAccount is null cos no item selected on first time round so must be adding a	record must be submitting new entries */

		$Sql = "INSERT INTO chartmaster (
					accountcode,
					accountname,
					group_,
					cashflowsactivity)
				VALUES ('" .
					$_POST['AccountCode'] . "', '" .
					$_POST['AccountName'] . "', '" .
					$_POST['Group'] . "', '" .
					$_POST['CashFlowsActivity'] . "')";
		$ErrMsg = _('Could not add the new account code');
		$Result = DB_query($Sql, $ErrMsg);

		echo prnMsg(_('The new general ledger account has been added'),'success');
	}

	unset($_POST['Group']);
	unset($_POST['AccountCode']);
	unset($_POST['AccountName']);
	unset($_POST['CashFlowsActivity']);
	unset($SelectedAccount);

} elseif(isset($_GET['delete'])) {
	//the link to delete a selected record was clicked instead of the submit button

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'ChartDetails'

	$Sql= "SELECT COUNT(*)
			FROM chartdetails
			WHERE chartdetails.accountcode ='" . $SelectedAccount . "'
			AND chartdetails.actual <>0";
	$Result = DB_query($Sql);
	$MyRow = DB_fetch_row($Result);
	if($MyRow[0] > 0) {
		$CancelDelete = 1;
		echo prnMsg(_('Cannot delete this account because chart details have been created using this account and at least one period has postings to it'), 'warn');
		echo '<br />' . _('There are') . ' ' . $MyRow[0] . ' ' . _('chart details that require this account code');

	} else {
// PREVENT DELETES IF DEPENDENT RECORDS IN 'GLTrans'
		$Sql = "SELECT COUNT(*)
				FROM gltrans
				WHERE gltrans.account ='" . $SelectedAccount . "'";
		$ErrMsg = _('Could not test for existing transactions because');
		$Result = DB_query($Sql, $ErrMsg);

		$MyRow = DB_fetch_row($Result);
		if($MyRow[0] > 0) {
			$CancelDelete = 1;
			echo prnMsg(_('Cannot delete this account because transactions have been created using this account'), 'warn');
			echo '<br />' . _('There are') . ' ' . $MyRow[0] . ' ' . _('transactions that require this account code');

		} else {
			//PREVENT DELETES IF Company default accounts set up to this account
			$Sql = "SELECT COUNT(*) FROM companies
					WHERE debtorsact='" . $SelectedAccount . "'
					OR pytdiscountact='" . $SelectedAccount . "'
					OR creditorsact='" . $SelectedAccount . "'
					OR payrollact='" . $SelectedAccount . "'
					OR grnact='" . $SelectedAccount . "'
					OR exchangediffact='" . $SelectedAccount . "'
					OR purchasesexchangediffact='" . $SelectedAccount . "'
					OR retainedearnings='" . $SelectedAccount . "'";
			$ErrMsg = _('Could not test for default company GL codes because');
			$Result = DB_query($Sql, $ErrMsg);

			$MyRow = DB_fetch_row($Result);
			if($MyRow[0] > 0) {
				$CancelDelete = 1;
				echo prnMsg(_('Cannot delete this account because it is used as one of the company default accounts'), 'warn');

			} else {
				//PREVENT DELETES IF Company default accounts set up to this account
				$Sql = "SELECT COUNT(*) FROM taxauthorities
					WHERE taxglcode='" . $SelectedAccount ."'
					OR purchtaxglaccount ='" . $SelectedAccount ."'";
				$ErrMsg = _('Could not test for tax authority GL codes because');
				$Result = DB_query($Sql, $ErrMsg);

				$MyRow = DB_fetch_row($Result);
				if($MyRow[0] > 0) {
					$CancelDelete = 1;
					echo prnMsg(_('Cannot delete this account because it is used as one of the tax authority accounts'), 'warn');
				} else {
//PREVENT DELETES IF SALES POSTINGS USE THE GL ACCOUNT
					$Sql = "SELECT COUNT(*) FROM salesglpostings
						WHERE salesglcode='" . $SelectedAccount . "'
						OR discountglcode='" . $SelectedAccount . "'";
					$ErrMsg = _('Could not test for existing sales interface GL codes because');
					$Result = DB_query($Sql, $ErrMsg);

					$MyRow = DB_fetch_row($Result);
					if($MyRow[0] > 0) {
						$CancelDelete = 1;
						echo prnMsg(_('Cannot delete this account because it is used by one of the sales GL posting interface records'), 'warn');
					} else {
//PREVENT DELETES IF COGS POSTINGS USE THE GL ACCOUNT
						$Sql = "SELECT COUNT(*)
								FROM cogsglpostings
								WHERE glcode='" . $SelectedAccount . "'";
						$ErrMsg = _('Could not test for existing cost of sales interface codes because');
						$Result = DB_query($Sql, $ErrMsg);

						$MyRow = DB_fetch_row($Result);
						if($MyRow[0]>0) {
							$CancelDelete = 1;
							echo prnMsg(_('Cannot delete this account because it is used by one of the cost of sales GL posting interface records'), 'warn');

						} else {
//PREVENT DELETES IF STOCK POSTINGS USE THE GL ACCOUNT
							$Sql = "SELECT COUNT(*) FROM stockcategory
									WHERE stockact='" . $SelectedAccount . "'
									OR adjglact='" . $SelectedAccount . "'
									OR purchpricevaract='" . $SelectedAccount . "'
									OR materialuseagevarac='" . $SelectedAccount . "'
									OR wipact='" . $SelectedAccount . "'";
							$Errmsg = _('Could not test for existing stock GL codes because');
							$Result = DB_query($Sql,$ErrMsg);

							$MyRow = DB_fetch_row($Result);
							if($MyRow[0]>0) {
								$CancelDelete = 1;
								echo prnMsg(_('Cannot delete this account because it is used by one of the stock GL posting interface records'), 'warn');
							} else {
//PREVENT DELETES IF STOCK POSTINGS USE THE GL ACCOUNT
								$Sql= "SELECT COUNT(*) FROM bankaccounts
								WHERE accountcode='" . $SelectedAccount ."'";
								$ErrMsg = _('Could not test for existing bank account GL codes because');
								$Result = DB_query($Sql,$ErrMsg);

								$MyRow = DB_fetch_row($Result);
								if($MyRow[0]>0) {
									$CancelDelete = 1;
									echo prnMsg(_('Cannot delete this account because it is used by one the defined bank accounts'), 'warn');
								} else {

									$Sql = "DELETE FROM chartdetails WHERE accountcode='" . $SelectedAccount ."'";
									$Result = DB_query($Sql);
									$Sql="DELETE FROM chartmaster WHERE accountcode= '" . $SelectedAccount ."'";
									$Result = DB_query($Sql);
									echo prnMsg(_('Account') . ' ' . $SelectedAccount . ' ' . _('has been deleted'), 'succes');
								}
							}
						}
					}
				}
			}
		}
	}
}

if(!isset($_GET['delete'])) {

	echo '<form method="post" id="GLAccounts" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if(isset($SelectedAccount)) {// Edit an existing account.
		echo '<input type="hidden" name="SelectedAccount" value="' . $SelectedAccount . '" />';
		$Sql = "SELECT accountcode, accountname, group_, cashflowsactivity FROM chartmaster WHERE accountcode='" . $SelectedAccount ."'";
		$Result = DB_query($Sql);
		$MyRow = DB_fetch_array($Result);

		$_POST['AccountCode'] = $MyRow['accountcode'];
		$_POST['AccountName'] = $MyRow['accountname'];
		$_POST['Group'] = $MyRow['group_'];
		$_POST['CashFlowsActivity'] = $MyRow['cashflowsactivity'];
	} else {
		$_POST['AccountCode'] = '';
		$_POST['AccountName'] = '';
	}

	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Account Code'), '</label>
			<input ', (empty($_POST['AccountCode']) ? 'autofocus="autofocus" ' : 'disabled="disabled" '), 'data-type="no-illegal-chars" maxlength="20" name="AccountCode" required="required" size="20" class="form-control" title="', _('Enter up to 20 alpha-numeric characters for the general ledger account code'), '" type="text" value="', $_POST['AccountCode'], '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Account Name') . '</label>
			<input ', (empty($_POST['AccountCode']) ? '' : 'autofocus="autofocus" '), 'maxlength="50" name="AccountName" required="required" size="51" title="' . _('Enter up to 50 alpha-numeric characters for the general ledger account name') . '" class="form-control" type="text" value="', $_POST['AccountName'], '" /></div></div>';

	$Sql = "SELECT groupname FROM accountgroups ORDER BY sequenceintb";
	$Result = DB_query($Sql);

	echo '<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Account Group') . '</label>
			<select required="required" name="Group" class="form-control">';
	while($MyRow = DB_fetch_array($Result)) {
		echo '<option';
		if(isset($_POST['Group']) and $MyRow[0]==$_POST['Group']) {
			echo ' selected="selected"';
		}
		echo ' value="', $MyRow[0], '">', $MyRow[0], '</option>';
	}
	echo '</select></div>
		</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">', _('Cash Flows Activity'), '</label>
			<select id="CashFlowsActivity" name="CashFlowsActivity" required="required" class="form-control">
					<option value="0"', ($_POST['CashFlowsActivity'] == 0 ? ' selected="selected"' : ''), '>', _('No effect on cash flow'), '</option>
					<option value="1"', ($_POST['CashFlowsActivity'] == 1 ? ' selected="selected"' : ''), '>', _('Operating activity'), '</option>
					<option value="2"', ($_POST['CashFlowsActivity'] == 2 ? ' selected="selected"' : ''), '>', _('Investing activity'), '</option>
					<option value="3"', ($_POST['CashFlowsActivity'] == 3 ? ' selected="selected"' : ''), '>', _('Financing activity'), '</option>
					<option value="4"', ($_POST['CashFlowsActivity'] == 4 ? ' selected="selected"' : ''), '>', _('Cash or cash equivalent'), '</option>
				</select>
			</div>
		</div>
		';

	echo '
		<div class="col-xs-4">
<div class="form-group"><br />
			<input type="submit" class="btn btn-info" name="submit" value="'. _('Enter Information') . '" />
		</div></div></div>
		</form>
';

} //end if record deleted no point displaying form to add record


if(!isset($SelectedAccount)) {
/* It could still be the second time the page has been run and a record has been selected for modification - SelectedAccount will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of ChartMaster will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	echo '<br />
		<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
		<thead>
			<tr>
				<th>', _('Account Code'), '</th>
				<th>', _('Account Name'), '</th>
				<th>', _('Account Group'), '</th>
				<th>', _('P/L or B/S'), '</th>
				<th>', _('Cash Flows Activity'), '</th>
				<th colspan="2">', _('Actions'), '</th>
			</tr>
		</thead>
		<tbody>';

	$Sql = "SELECT
				accountcode,
				accountname,
				group_,
				CASE WHEN pandl=0 THEN '" . _('Balance Sheet') . "' ELSE '" . _('Profit/Loss') . "' END AS acttype,
				cashflowsactivity
			FROM chartmaster, accountgroups
			WHERE chartmaster.group_=accountgroups.groupname
			ORDER BY chartmaster.accountcode";
	$ErrMsg = _('The chart accounts could not be retrieved because');
	$Result = DB_query($Sql, $ErrMsg);

	while ($MyRow = DB_fetch_array($Result)) {
		echo '<tr class="striped_row">
				<td class="text">', $MyRow['accountcode'], '</td>
				<td class="text">', htmlspecialchars($MyRow['accountname'], ENT_QUOTES, 'UTF-8'), '</td>
				<td class="text">', $MyRow['group_'], '</td>
				<td class="text">', $MyRow['acttype'], '</td>
				<td class="text">', CashFlowsActivityName($MyRow['cashflowsactivity']), '</td>
				<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?', '&amp;SelectedAccount=', $MyRow['accountcode'], '" class="btn btn-info">', _('Edit'), '</a></td>
				<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?', '&amp;SelectedAccount=', $MyRow['accountcode'], '&amp;delete=1" class="btn btn-danger" onclick="return confirm(\'', _('Are you sure you wish to delete this account? Additional checks will be performed in any event to ensure data integrity is not compromised.'), '\');">', _('Delete'), '</a></td>
			</tr>';
	}// END foreach($Result as $MyRow).

	echo '</tbody></table></div></div></div>';
} //END IF selected ACCOUNT

//end of ifs and buts!



if(isset($SelectedAccount)) {
	echo '<div class="row"><div class="col-xs-4"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Accounts') . '</a></div></div><br />';
}

include('includes/footer.php');
?>
