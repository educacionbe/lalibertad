<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!-- Suport IE8: media query, html5 -->
<?php if($yt->ieversion()==8) { ?>
	<script src="<?php echo $yt->templateurl().'js/modernizr.min.js' ?>" type="text/javascript"></script>
	<script src="<?php echo $yt->templateurl().'js/respond.min.js' ?>" type="text/javascript"></script>
<?php } ?>



<?php
if($yt->getParam('enableGoogleAnalytics')=='1' && $yt->getParam('googleAnalyticsTrackingID')!='' ){ ?>
	<!-- Google Analytics -->
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', '<?php echo $yt->getParam('googleAnalyticsTrackingID')?>', 'auto');
	ga('send', 'pageview');

	</script>
	<!-- End Google Analytics -->

	
<?php
} ?>