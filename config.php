<?php

// User configurable variables
//---------------------------------------------------

// Default language to use for the login screen and the setup of new users.
$DefaultLanguage = 'en_IN.utf8';

// Default theme to use for the login screen and the setup of new users.
$DefaultTheme = 'xenos';

// Whether to display the demo login and password or not on the login screen
$AllowDemoMode = FALSE;

// Connection information for the database
// $host is the computer ip address or name where the database is located
// assuming that the webserver is also the sql server
$host = 'localhost';

// assuming that the web server is also the sql server
$DBType = 'mysqli';
//assuming that the web server is also the sql server
$DBUser = 'weberp';
$DBPassword = 'weberp';
// The timezone of the business - this allows the possibility of having;
date_default_timezone_set('Asia/Kolkata');
putenv('TZ=Asia/Kolkata');
$AllowCompanySelectionBox = 'ShowSelectionBox';
//The system administrator name use the user input mail;
$SysAdminEmail = '';
$DefaultDatabase = 'weberp';
$SessionLifeTime = 3600;
$MaximumExecutionTime = 120;
$DefaultClock = 12;
$RootPath = dirname(htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'));
if (isset($DirectoryLevelsDeep)){
   for ($i=0;$i<$DirectoryLevelsDeep;$i++){
		$RootPath = mb_substr($RootPath,0, strrpos($RootPath,'/'));
	}
}
if ($RootPath == '/' OR $RootPath == '\\') {
	$RootPath = '';
}
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//Installed companies 
$CompanyList[0] = array('database'=>'weberp' ,'company'=>'weberp' );
//$CompanyList[1] = array('database'=>'weberpdemo' ,'company'=>'weberp Demo Company' );
//$CompanyList[] = array('database'=>'weberp' ,'company'=>'weberp');
//End Installed companies-do not change this line
/* Make sure there is nothing - not even spaces after this last ?> */
?>