<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
defined('_JEXEC') or die;

if($yt->getParam('useSpecialPositions')==1){?>
<div id="yt_special_pos" class="row hidden-xs hidden-sm">
	<?php
	if($doc->countModules('notice')){ ?>
    <div id="yt_notice" class="col-sm-12">
        <div class="yt-notice clearfix">
			<jdoc:include type="modules" name="notice" style="special" />
        </div>
    </div>
    <?php
	} ?>
    <?php
	if($doc->countModules('sticky_left')){ ?>
	<div id="yt_sticky_left" class="col-sm-3" >
        <div class="yt-sticky sticky-left clearfix">
			<jdoc:include type="modules" name="sticky_left" style="special" />
        </div>
    </div>
    <?php 
	} ?>
    <?php
	if($doc->countModules('sticky_right')){ ?>
    <div id="yt_sticky_right" class="col-sm-3"  >
        <div class="yt-sticky sticky-right clearfix">
			<jdoc:include type="modules" name="sticky_right" style="special" />
        </div>
    </div>
    <?php
	} ?>
    <?php
	if($doc->countModules('tool_bottom')){ ?>
    <div id="yt_tool_bottom" class="col-sm-3" >
        <div class="yt-tool-bottom tool_bottom clearfix">
			<jdoc:include type="modules" name="tool_bottom" style="special" title="1" />
        </div>
    </div>
    <?php
	} ?>
	<script type="text/javascript">
		function useSP(){
			jQuery(document).ready(function($){
				var width = $(window).width()+17; //alert(width);
				var events = '<?php echo $yt->getParam("eventsSpecialPostion", "click")?>';
				if(width>767){
					<?php if($doc->countModules('notice')){ ?>
						YTScript.slidePositionNotice('#yt_notice .yt-notice', 'activeNotice');
					<?php } ?>
					<?php if($doc->countModules('sticky_left')){ ?>
						YTScript.slidePositions('#yt_sticky_left .yt-sticky', 'left', events);
					<?php } ?>
					<?php if($doc->countModules('sticky_right')){ ?>
						YTScript.slidePositions('#yt_sticky_right .yt-sticky', 'right', events);
					<?php } ?>
					<?php if($doc->countModules('tool_bottom')){ ?>
						YTScript.slidePositionBottom('#yt_tool_bottom .yt-tool-bottom', events);
					<?php } ?>
				}
			});
			<?php if($doc->countModules('notice')){ ?>
				jQuery(window).load(function($){
					var width = jQuery(window).width()+17;
					if(width>767){
						YTScript.checkActiveNotice('#yt_notice .yt-notice', 'activeNotice', <?php echo $yt->getParam('activeNotice', 0) ?>);
					}
				});
			<?php } ?>
		}

		useSP();
		/*
		jQuery(document).ready(function($){
		$(".sticky-right .btn-special").tooltip({
                  'selector': '',
                  'placement': 'left'
            });
		$(".sticky-left .btn-special").tooltip({
					  'selector': '',
					  'placement': 'right'
				});
		});
		
		 jQuery(window).resize(function(){ 
	    	if (jQuery.data(window, 'use-special-position'))
	      		clearTimeout(jQuery.data(window, 'use-special-position'));
				
	    	jQuery.data(window, 'use-special-position', 
	      		setTimeout(function(){
	        		useSP();
	      		}, 200)
	    	)
	  	}) */
	</script>

</div>
<?php } ?>