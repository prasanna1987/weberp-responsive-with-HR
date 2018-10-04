<?php

include ('includes/session.php');
$Title = _('Produce Stock Quantities CSV');
include ('includes/header.php');

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . $Title. '</h1></a></div>';

function stripcomma($str) { //because we're using comma as a delimiter
	return str_replace(',', '', $str);
}



$ErrMsg = _('The SQL to get the stock quantities failed with the message');

$sql = "SELECT stockid, SUM(quantity) FROM locstock
			INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			GROUP BY stockid HAVING SUM(quantity)<>0";
$result = DB_query($sql, $ErrMsg);

if (!file_exists($_SESSION['reports_dir'])){
	$Result = mkdir('./' . $_SESSION['reports_dir']);
}

$filename = $_SESSION['reports_dir'] . '/StockQties.csv';

$fp = fopen($filename,'w');

if ($fp==FALSE){

	echo prnMsg(_('Could not open or create the file under') . ' ' . $_SESSION['reports_dir'] . '/StockQties.csv','error');
	include('includes/footer.php');
	exit;
}

While ($myrow = DB_fetch_row($result)){
	$line = stripcomma($myrow[0]) . ', ' . stripcomma($myrow[1]);
	fputs($fp,"\xEF\xBB\xBF" . $line . "\n");
}

fclose($fp);

echo '<br /><p align="center"><a href="' . $RootPath . '/' . $_SESSION['reports_dir'] . '/StockQties.csv " class="btn btn-warning">' . _('Download') . '</a></p>';

include('includes/footer.php');

?>
