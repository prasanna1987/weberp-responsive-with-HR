<?php

$RootPath = dirname(htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'));
echo '<div id="MessageContainerFoot">';
if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 0) {
        $LogFile = fopen($_SESSION['LogPath'] . '/nerp.log', 'a');
    } 
if (isset($Messages) and count($Messages) > 0) {
	foreach ($Messages as $Message) {
		$Prefix = '';
		switch ($Message[1]) {
			case 'error':
				$Class = 'error';
				$Prefix = $Prefix ? $Prefix : _('ERROR') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="alert alert-danger alert-dismissable Message ' . $Class . ' noPrint">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
				<strong>' . $Prefix . '</strong> : ' . $Message[0] . '</div>';
				break;
			case 'warn':
			case 'warning':	 
				$Class = 'warn';
				$Prefix = $Prefix ? $Prefix : _('WARNING') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="alert alert-warning alert-dismissable Message ' . $Class . ' noPrint">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
				
				<strong>' . $Prefix . '</strong> : ' . $Message[0] . '</div>';
				break;
			case 'success':
				$Class = 'success';
				$Prefix = $Prefix ? $Prefix : _('SUCCESS') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="alert alert-success alert-dismissable Message ' . $Class . ' noPrint">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
				<strong>' . $Prefix . '</strong> : ' . $Message[0] . '</div>';
				break;
			case 'info':
			default:
				$Prefix = $Prefix ? $Prefix : _('INFORMATION') . ' ' . _('Message');
				$Class = 'info';
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 2) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="alert alert-info alert-dismissable Message ' . $Class . ' noPrint">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
				<strong>' . $Prefix . '</strong> : ' . $Message[0] . '</div>';
		}
	}
}
//echo ''; // eof MessageContainer div
//echo '</div>'; // BodyWrapDiv
echo '</div>'; // BodyDiv

echo '</div> <footer class="clearfix">';
echo '<div class="pull-right">';

//echo '<div id="FooterLogoDiv">';
	//echo '<img src="'. $RootPath . '/' . $_SESSION['LogoFile'] . '" width="120" alt="nERP" title="nERP ' . _('Copyright') . ' &copy; netelity.com - ' . date('Y') . '" />';
echo 'Created with <i class="fa fa-heart text-danger"></i> by <a href="http://www.netelity.com" target="_blank">Netelity</a>
                    </div>
                    <div class="pull-left">
                        <span id=""></span>  <a href="http://www.netelity.com" target="_blank">nERP</a>
                    </div>';

//echo '<div id="FooterTimeDiv">';
	//echo DisplayDateTime();
//echo '</div>';

//echo '<div id="FooterVersionDiv">';
	//echo 'nERP ' . _('version') . ' ' . $_SESSION['VersionNumber'] . ' ' . _('Copyright') . ' &copy; 2004 - ' . Date('Y'). ' <a target="_blank" href="http://www.netelity.com/nERP/doc/Manual/ManualContributors.html">netelity.com</a>';
echo '</footer>
';

//echo '</div>'; // FooterWrapDiv



echo '<div>
	<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<input type="hidden" name="ScriptName" value="' . htmlspecialchars($ScriptName,ENT_QUOTES,'UTF-8') . '" />
	<input type="hidden" name="Title" value="' . $Title . '" />
	' . $ShowAdd . $ShowDel . '
        
	
		</form>
	</div>
';
echo '</div>
</div>
<a href="javascript:void(0)" id="to-top"><i class="fa fa-angle-up"></i></a>
';
?>

 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>!window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-1.11.1.min.js"%3E%3C/script%3E'));</script>

       <?php
	   echo' 
        <script src="'. $RootPath.'/js/vendor/bootstrap.min.js"></script>
        <script src="'. $RootPath.'/js/plugins.js"></script>
        <script src="'. $RootPath.'/js/main.js"></script>';
		?>
        
        <script>
            $(function () {
                // Set up timeline scrolling functionality
                $('.timeline-con').slimScroll({height: 565, color: '#000000', size: '3px', touchScrollStep: 100, distance: '0'});
                $('.timeline').css('min-height', '565px');

                // Demo status updates and timeline functionality
                var statusUpdate = $('#status-update');
                var statusUpdateVal = '';

                $('#accept-request').click(function () {
                    $(this).replaceWith('<span class="label label-success">Awesome, you became friends!');
                });

                $('#status-update-btn').click(function () {
                    statusUpdateVal = statusUpdate.val();

                    if (statusUpdateVal) {
                        $('.timeline-con').slimScroll({scrollTo: '0px'});

                        $('.timeline').prepend('<li class="animation-pullDown">' +
                            '<div class="timeline-item">' +
                            '<h4 class="timeline-title"><small class="timeline-meta">just now</small><i class="fa fa-file"></i> Status</h4>' +
                            '<div class="timeline-content"><p>' + $('<div />').text(statusUpdateVal).html().substring(0, 200) + '</p><em>Demo functionality</em></div>' +
                            '</div>' +
                            '</li>');

                        statusUpdate.val('').attr('placeholder', 'I hope you like it! :-)');
                    }
                });

                /*
                 * Flot 0.8.3 Jquery plugin is used for charts
                 *
                 * For more examples or getting extra plugins you can check http://www.flotcharts.org/
                 * Plugins included in this template: pie, resize, stack
                 */

                // Get the elements where we will attach the charts
                var chartClassic = $('#chart-classic');

                // Random data for the charts
                var dataEarnings = [[0, 60], [1, 100], [2, 80], [3, 84], [4, 124], [5, 90], [6, 150]];
                var dataSales = [[0, 30], [1, 50], [2, 40], [3, 42], [4, 62], [5, 45], [6, 75]];

                /* Classic Chart */
                $.plot(chartClassic,
                    [
                        {
                            data: dataEarnings,
                            lines: {show: true, fill: true, fillColor: {colors: [{opacity: 0.25}, {opacity: 0.25}]}},
                            points: {show: true, radius: 7}
                        },
                        {
                            data: dataSales,
                            lines: {show: true, fill: true, fillColor: {colors: [{opacity: 0.15}, {opacity: 0.15}]}},
                            points: {show: true, radius: 7}
                        }
                    ],
                    {
                        colors: ['#f39c12', '#2e3030'],
                        legend: {show: false},
                        grid: {borderWidth: 0, hoverable: true, clickable: true},
                        yaxis: {show: false},
                        xaxis: {show: false}
                    }
                );

                // Creating and attaching a tooltip to the classic chart
                var previousPoint = null, ttlabel = null;
                chartClassic.bind('plothover', function (event, pos, item) {

                    if (item) {
                        if (previousPoint !== item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $('#chart-tooltip').remove();
                            var x = item.datapoint[0], y = item.datapoint[1];

                            if (item.seriesIndex === 1) {
                                ttlabel = '<strong>' + y + '</strong> sales';
                            } else {
                                ttlabel = '$ <strong>' + y + '</strong>';
                            }

                            $('<div id="chart-tooltip" class="chart-tooltip">' + ttlabel + '</div>')
                                .css({top: item.pageY - 45, left: item.pageX + 5}).appendTo("body").show();
                        }
                    }
                    else {
                        $('#chart-tooltip').remove();
                        previousPoint = null;
                    }
                });
            });
        </script>
        <?php
echo '</body>
	</html>';
?>
