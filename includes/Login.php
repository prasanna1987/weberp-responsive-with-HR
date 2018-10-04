<?php
// Display demo user name and password within login form if $AllowDemoMode is true

//include ('LanguageSetup.php');
if ((isset($AllowDemoMode)) AND ($AllowDemoMode == True) AND (!isset($demo_text))) {
	$demo_text = _('Login as user') .': <i>' . _('admin') . '</i><br />' ._('with password') . ': <i>' . _('nERP') . '</i>' .
		'<br /><a href="../">' . _('Return') . '</a>';// This line is to add a return link.
} elseif (!isset($demo_text)) {
	$demo_text = '';
}
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
 <meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
	<title>nERP Login</title>
	<link rel="stylesheet" href="css/bootstrap.css">

        
        <link rel="stylesheet" href="css/plugins.css">

       
        <link rel="stylesheet" href="css/main.css">

       
        <link rel="stylesheet" href="css/themes.css">
        <script src="js/vendor/modernizr-respond.min.js"></script>
</head>
<body>
<?php

if (get_magic_quotes_gpc()){
	echo '<p style="background:white">';
	echo _('Your webserver is configured to enable Magic Quotes. This may cause problems if you use punctuation (such as quotes) when doing data entry. You should contact your webmaster to disable Magic Quotes');
	echo '</p>';
}

?>

<!-- Header -->
        <header class="navbar navbar-default navbar-fixed-top">
          
            <a href="#" class="navbar-brand">
                <img src="img/logo.jpg" alt="nERP">
                <span>nERP</span>
            </a>
            <!-- END Header Brand -->
        </header>
        <!-- END Header -->
	
	
     <div id="login-container">
     <div id="page-content" class="block remove-margin">
     <div class="block-header">
                    <div class="header-section">
                        <h1 class="text-center">Login</h1>
                    </div>
                </div>
	<form action="Dashboard.php" method="post" id="form-login" class="form-horizontal">
    
	<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID']; ?>" />
	<div class="form-group">
    	<div class="col-xs-12">
<?php

	    if (isset($CompanyList) AND is_array($CompanyList)) {
            foreach ($CompanyList as $key => $CompanyEntry){
                if ($DefaultDatabase == $CompanyEntry['database']) {
                    $CompanyNameField = "$key";
                    $DefaultCompany = $CompanyEntry['company'];
                }
            }
	        if ($AllowCompanySelectionBox === 'Hide'){
			    // do not show input or selection box
			    echo '<input type="hidden" name="CompanyNameField"  value="' .  $CompanyNameField . '" />';
		    } elseif ($AllowCompanySelectionBox === 'ShowInputBox'){
			    // show input box
			    echo  '<input type="text" class="form-control input-lg" name="DefaultCompany" autofocus="autofocus" required="required" value="' .  htmlspecialchars($DefaultCompany ,ENT_QUOTES,'UTF-8') . '" disabled="disabled"/>';//use disabled input for display consistency
		        echo '<input type="hidden" name="CompanyNameField"  value="' .  $CompanyNameField . '" />';
		    } else {
                // Show selection box ($AllowCompanySelectionBox == 'ShowSelectionBox')
                
                echo '<select name="CompanyNameField" class="form-control input-lg">';
                foreach ($CompanyList as $key => $CompanyEntry){
                    if (is_dir('companies/' . $CompanyEntry['database']) ){
                        if ($CompanyEntry['database'] == $DefaultDatabase) {
                            echo '<option selected="selected" label="'.htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8').'" value="'.$key.'">' . htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8') . '</option>';
                        } else {
                            echo '<option label="'.htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8').'" value="'.$key.'">' . htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8') . '</option>';
                        }
                    }
                }
                echo '</select>';
            }
	    }
	      else { //provision for backward compat - remove when we have a reliable upgrade for config.php
            if ($AllowCompanySelectionBox === 'Hide'){
			    // do not show input or selection box
			    echo '<input type="hidden" name="CompanyNameField"  value="' . $DefaultCompany . '" />';
		    } else if ($AllowCompanySelectionBox === 'ShowInputBox'){
			    // show input box
			    echo '<input type="text" name="CompanyNameField" class="form-control input-lg"  autofocus="autofocus" required="required" value="' . $DefaultCompany . '" />';
		    } else {
      			// Show selection box ($AllowCompanySelectionBox == 'ShowSelectionBox')
    			
	    		echo '<select name="CompanyNameField" class="form-control input-lg">';
	    		$Companies = scandir('companies/', 0);
			    foreach ($Companies as $CompanyEntry){
                    if (is_dir('companies/' . $CompanyEntry) AND $CompanyEntry != '..' AND $CompanyEntry != '' AND $CompanyEntry!='.svn' AND $CompanyEntry!='.'){
                        if ($CompanyEntry==$DefaultDatabase) {
                            echo '<option selected="selected" label="'.$CompanyEntry.'" value="'.$CompanyEntry.'">' . $CompanyEntry . '</option>';
                        } else {
                            echo '<option label="'.$CompanyEntry.'" value="'.$CompanyEntry.'">' . $CompanyEntry . '</option>';
                        }
                    }
    	        }
    	         echo '</select>';
            }
        } //end provision for backward compat
?>

	
	</div></div>
	<div class="form-group">
   <div class="col-xs-12">
	<input type="text" name="UserNameEntryField" class="form-control input-lg" required="required" autofocus="autofocus" maxlength="20" placeholder="<?php echo _('User name'); ?>" />
    </div>
    </div>
    
	<div class="form-group">
   <div class="col-xs-12">
	<input type="password" class="form-control input-lg" required="required" name="Password" placeholder="<?php echo _('Password'); ?>" />
    </div>
    </div>
	<div id="demo_text">
<?php

	if (isset($demo_text)){
		echo $demo_text;
	}
?>

	</div>
    <div class="form-group">
   		<div class="col-xs-12" align="center">
	<input type="submit" class="btn btn-success btn-lg" value="<?php echo _('Login'); ?>" name="SubmitUser" />
	</div>
        </div>
        
	</form>
	</div>
    </div>
    </div>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>!window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-1.11.1.min.js"%3E%3C/script%3E'));</script>

        <!-- Bootstrap.js, Jquery plugins and custom Javascript code -->
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Javascript code only for this page -->
        <script>
            $(function () {
                /* Save buttons (remember me and terms) and hidden checkboxes in variables */
                var checkR = $('#login-remember'),
                    checkT = $('#register-terms'),
                    btnR = $('#btn-remember'),
                    btnT = $('#btn-terms');

                // Add the 'active' class to button if their checkbox has the property 'checked'
                if (checkR.prop('checked'))
                    btnR.addClass('active');
                if (checkT.prop('checked'))
                    btnT.addClass('active');

                // Toggle 'checked' property of hidden checkboxes when buttons are clicked
                btnR.on('click', function () {
                    checkR.prop('checked', !checkR.prop('checked'));
                });
                btnT.on('click', function () {
                    checkT.prop('checked', !checkT.prop('checked'));
                });

                /* Login & Register show-hide */
                var formLogin = $('#form-login'),
                    formRegister = $('#form-register');

                $('#link-login').click(function () {
                    formLogin.slideUp(250);
                    formRegister.slideDown(250);
                });
                $('#link-register').click(function () {
                    formRegister.slideUp(250);
                    formLogin.slideDown(250);
                });
            });
        </script> 
</body>
</html>