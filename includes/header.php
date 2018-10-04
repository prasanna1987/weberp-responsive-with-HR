<?php
	if (!isset($RootPath)){
		$RootPath = dirname(htmlspecialchars($_SERVER['PHP_SELF']));
		if ($RootPath == '/' OR $RootPath == "\\") {
			$RootPath = '';
		}
	}

	$ViewTopic = isset($ViewTopic) ? '?ViewTopic=' . $ViewTopic : '';
	$BookMark = isset($BookMark) ? '#' . $BookMark : '';

	if(isset($Title) && $Title == _('Copy a BOM to New Item Code')){//solve the cannot modify header information in CopyBOM.php scripts
		ob_start();
	}

	echo '<!DOCTYPE html>';
	?>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<?php 

	echo '<head>
	<meta name="robots" content="noindex, nofollow">
			<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
			<title>', $Title, '</title>
			<link rel="icon" href="', $RootPath, '/favicon.ico" />
			
			<link href="', $RootPath, '/css/menu.css" rel="stylesheet" type="text/css" />
			<link href="', $RootPath, '/css/print.css" rel="stylesheet" type="text/css" media="print" />
			<link href="', $RootPath, '/css/', $_SESSION['Theme'], '/default.css" rel="stylesheet" type="text/css" media="screen"/>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<script defer="defer" src="', $RootPath, '/javascripts/MiscFunctions.js"></script>
			
			<script defer="defer" src="', $RootPath, '/javascripts/MiscFunctions.js"></script>
			<script>
				localStorage.setItem("DateFormat", "', $_SESSION['DefaultDateFormat'], '");
				localStorage.setItem("Theme", "', $_SESSION['Theme'], '");
			</script>';
			

	// If it is set the $_SESSION['ShowPageHelp'] parameter AND it is FALSE, hides the page help text:
	if(isset($_SESSION['ShowPageHelp']) AND !$_SESSION['ShowPageHelp']) {
		echo '<style>
				.page_help_text, div.page_help_text {
					display:none;
				}
			</style>';
	}

	echo '</head>';
	
		echo '<body class="header-fixed-top">';
		include('menu.php');
			echo
			'<input type="hidden" name="Lang" id="Lang" value="', $Lang, '" />';
			echo '<div id="sidebar-right">',
				'<div class="sidebar-content">
				 <div class="user-info">
				 <div class="user-details">', stripslashes($_SESSION['UsersRealName']), '</div></div>';
				 
		echo ' <div class="sidebar-right-scroll">
		<ul class="sidebar-nav">
		<li><a href="', $RootPath, '/UserSettings.php"><i class="fa fa-pencil-square"></i>', _('Profile'), '</a></li>
		<li><a href="', $RootPath, '/Logout.php" onclick="return confirm(\'', _('Are you sure you wish to logout?'), '\');"><i class="fa fa-power-off"></i>', _('Logout'), '</a></li>
		</ul>
		</div>
		</div>
		</div>
		
		<div id="page-container">
		<header class="navbar navbar-default navbar-fixed-top">
		 <ul class="nav header-nav pull-right">
		 
		 <li class="dropdown"> <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-search" style="font-size:18px;"></i>
                        </a>
		<ul class="dropdown-menu dropdown-custom pull-right">
		';

		if (isset($_POST['AddToMenu'])) {
			if (!isset($_SESSION['Favourites'][$_POST['ScriptName']])) {
				$_SESSION['Favourites'][$_POST['ScriptName']] = $_POST['Title'];
			}
		}

		if (isset($_POST['DelFromMenu'])) {
			unset($_SESSION['Favourites'][$_POST['ScriptName']]);
		}

		if (isset($_SESSION['Favourites']) AND count($_SESSION['Favourites'])>0) {
			echo '<ul>';
			foreach ($_SESSION['Favourites'] as $url=>$ttl) {
				echo '<li><a href="', $url, '">', _($ttl), '<a></li>';
			}
			echo '</ul>';
		}

		echo '</li>'; //take off inline formatting, use CSS instead ===HJ===
		if (count($_SESSION['AllowedPageSecurityTokens'])>1){
			echo '<li><a href="', $RootPath, '/Dashboard.php">', _('Dashboard'), '</a></li>';
			echo '<li><a href="', $RootPath, '/SelectCustomer.php">', _('Customers'), '</a></li>';
			echo '<li><a href="', $RootPath, '/SelectProduct.php">', _('Items'), '</a></li>';
			echo '<li><a href="', $RootPath, '/SelectSupplier.php">', _('Suppliers'), '</a></li>';
			echo '<li><a href="', $RootPath, '/ManualContents.php', $ViewTopic, $BookMark, '" rel="external" accesskey="8">', _('Manual'), '</a></li>';
		}				
		echo '
		
		</ul></li>
		
		 <li>
                        <a href="javascript:void(0)" id="sidebar-right-toggle">
                             <i class="fa fa-user" style="font-size:18px;"></i>
                        </a>
       
				
                  <a href="', $RootPath, '/Dashboard.php" class="navbar-brand">  
					<img alt="', stripslashes($_SESSION['CompanyRecord']['coyname']), '" src="', $RootPath, '/companies/', $_SESSION['DatabaseName'], '/logo.jpg" title="', stripslashes($_SESSION['CompanyRecord']['coyname']), '" height="40px" />
                    
                </a>
               
            </header>';
			echo '<div id="fx-container" class="fx-opacity">',
				'<div id="page-content" class="block">
				
				',
					''; //===HJ===
					
					
					
// END TransactionsDiv =========================================================





//echo '<div id="MaintenanceDiv" class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><ul class="list-unstyled">'; //=== MaintenanceDive ===

//echo '<li>';
//if ($_SESSION['Module']=='system') {
//	$Header='<img src="' . $RootPath . '/css/' . $Theme . '/images/inventory.png" title="' . _('Inventory Setup') . '" alt="' . _('Inventory Setup') . '" /><b>' . _('Inventory Setup') . '</b>';
//} else {
//	$Header='<b>' .  _('Maintenance') . '</b>';
//
//}
//echo $Header;
//echo '</li>';
//
//$i=0;
//foreach ($MenuItems[$_SESSION['Module']]['Maintenance']['Caption'] as $Caption) {
///* Transactions Menu Item */
//	$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Maintenance']['URL'][$i],1));
//	$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
//	if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PageSecurity))) {
//		echo '<li>
//				<p>&bull; <a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Maintenance']['URL'][$i] .'">' . $Caption . '</a></p>
//			  </li>';
//	}
//	$i++;
//}
//echo '</ul></div>'; // MaintenanceDive ===HJ===
//echo '</div>';

//echo ' ';
 // SubMenuDiv ===HJ===

//include('footer.php');



		
?>
