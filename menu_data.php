<?php
$PageSecurity = 0;
include('includes/session.php');
include('includes/header.php');
include('includes/MainMenuLinksArray.php');
$ModuleName = $_GET['Application'];
$_SESSION['Module'] = $ModuleName;
echo '
					<div class="row gutter30">
					<div id="TransactionsDiv" class="col-md-6"><div class="block">
					<ul class="list-unstyled">';

echo '<li>'; //=== SubMenuHeader ===

if ($_SESSION['Module']=='system') {
	$Header='<div class="block-title"><h2>' . _('General Setup Options') . '</h2></div>';
} else {
	$Header='<div class="block-title">
                                    <h2>' . _('Operations') . '</h2>
                                </div>';
}
echo $Header;
echo '</li>'; // SubMenuHeader

//=== SubMenu Items ===
$i=0;
foreach ($MenuItems[$_SESSION['Module']]['Transactions']['Caption'] as $Caption) {
/* Transactions Menu Item */
	$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Transactions']['URL'][$i],1));
	$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
	if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PageSecurity))) {
		echo '<li>
				<a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Transactions']['URL'][$i] .'">' . $Caption . '</a>
			  </li>';
	}
	$i++;
}


echo '</ul></div></div>';
// END TransactionsDiv =========================================================


echo '<div id="InquiriesDiv" class="col-md-6"><div class="block"><ul class="list-unstyled">'; //=== InquiriesDiv ===

echo '<li>';
if ($_SESSION['Module']=='system') {
	$Header='<div class="block-title">
                                    <h2>' . _('Receivables/Payables Setup') . '</h2></div>';
} else {
	$Header=' <div class="block-title"><h2>' . _('Inquiries and Reports') . '</h2></div>';
}
echo $Header;
echo '</li>';


$i=0;
foreach ($MenuItems[$_SESSION['Module']]['Reports']['Caption'] as $Caption) {
/* Transactions Menu Item */
	$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Reports']['URL'][$i],1));
	$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
	if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PageSecurity))) {
		echo '<li>
				<a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Reports']['URL'][$i] .'">' . $Caption . '</a>
			  </li>';
	}
	$i++;
}
//echo GetRptLinks($_SESSION['Module']); //=== GetRptLinks() must be modified!!! ===
echo '</ul></div></div>'; //=== InquiriesDiv ===


echo '</div>



';
//echo ' ';
 // SubMenuDiv ===HJ===

include('includes/footer.php');

function GetRptLinks($GroupID) {
/*
This function retrieves the reports given a certain group id as defined in /reports/admin/defaults.php
in the acssociative array $ReportGroups[]. It will fetch the reports belonging solely to the group
specified to create a list of links for insertion into a table to choose a report. Two table sections will
be generated, one for standard reports and the other for custom reports.
*/
	global $RootPath, $ReportList;
	require_once('/reportwriter/languages/en_US/reports.php');
	require_once('/reportwriter/admin/defaults.php');
	$GroupID=$ReportList[$GroupID];
	$Title= array(_('Custom Reports'), _('Standard Reports and Forms'));

	$sql= "SELECT id,
				reporttype,
				defaultreport,
				groupname,
				reportname
			FROM reports
			ORDER BY groupname,
					reportname";
	$Result=DB_query($sql,'','',false,true);
	$ReportList = array();
	while ($Temp = DB_fetch_array($Result)) {
		$ReportList[] = $Temp;
	}
	$RptLinks = '';
	for ($Def=1; $Def>=0; $Def--) {
        $RptLinks .= '<li>';
        $RptLinks .= '<b>' .  $Title[$Def] . '</b>';
        $RptLinks .= '</li>';
		$NoEntries = true;
		if ($ReportList) { // then there are reports to show, show by grouping
			foreach ($ReportList as $Report) {
				if ($Report['groupname']==$GroupID AND $Report['defaultreport']==$Def) {
                    $RptLinks .= '<li>';
					$RptLinks .= '<a href="' . $RootPath . '/reportwriter/ReportMaker.php?action=go&amp;reportid=' . $Report['id'] . '">' . _($Report['reportname']) . '</a>';
					$RptLinks .= '</li>';
					$NoEntries = false;
				}
			}
			// now fetch the form groups that are a part of this group (List after reports)
			$NoForms = true;
			foreach ($ReportList as $Report) {
				$Group=explode(':',$Report['groupname']); // break into main group and form group array
				if ($NoForms AND $Group[0]==$GroupID AND $Report['reporttype']=='frm' AND $Report['defaultreport']==$Def) {
                    $RptLinks .= '<li>';
					$RptLinks .= '<img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/folders.gif" width="16" height="13" alt="" />';
					$RptLinks .= '<a href="' . $RootPath . '/reportwriter/FormMaker.php?id=' . $Report['groupname'] . '">';
					$RptLinks .= $FormGroups[$Report['groupname']] . '</a>';
					$RptLinks .= '</li>';
					$NoForms = false;
					$NoEntries = false;
				}
			}
		}
		if ($NoEntries) $RptLinks .= '<li>' . _('There are no reports to show!') . '</li>';
	}
	return $RptLinks;
}

?>
