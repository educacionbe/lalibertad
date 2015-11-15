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


/****************************
*  Google Font & Body Font
****************************/

// Function YT Font
function ytfont($font, $selectors){
	$doc = JFactory::getDocument();
	if (trim($font)!='0' ){
		if (strpos($font, ',') ) {
			$doc->addStyleDeclaration($selectors.'{font-family:'.$font.'}');
		}else{
			$doc->addStyleSheet('http://fonts.googleapis.com/css?family='.$font);
			$font = str_replace("+"," ",(explode(':',$font)));
			$fontweight = isset($font[1]) ? $font[1] : null;;
			$fontitalic = strpos($fontweight, "italic");
			
			if ($fontitalic == true) {
				$fontweight_italic = substr($fontweight,0,$fontitalic );
				$doc->addStyleDeclaration($selectors.'{font-style:italic;font-weight:'.$fontweight_italic.'}');
			}
			if(trim($selectors)!=""){
				$doc->addStyleDeclaration($selectors.'{font-family:'.$font[0].'!important;font-weight:'.$fontweight.'}');
			}
			
		}
	}
}

// Global Font & Menu Font & Heading Font & Other Font
ytfont($bodyFont,$bodySelectors);
ytfont($menuFont,$menuSelectors);
ytfont($headingFont,$headingSelectors);
ytfont($otherFont,$otherSelectors);

// Show Sidebar Menu Desktop
if($yt->getParam('sidebarMenu') ==0){
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		 $(".yt-resmenu").addClass("hidden-lg hidden-md");
	});
</script>
<?php }?>

<script type="text/javascript">
	jQuery(document).ready(function($){
		/* Begin: add class pattern for element */
		var bodybgimage = '<?php echo $yt->getParam('bgimage');?>';
		<?php if($yt->getParam('typelayout') == 'boxed') {?>
			if(bodybgimage){
				$('#bd').addClass(bodybgimage);
			}
		<?php }; ?>
		/* End: add class pattern for element */
	});
</script>

<?php
// Include cpanel
if($showCpanel) {
	include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'cpanel.php');
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		
		<?php if($yt->getParam('typelayout') == 'boxed') {?>
			patternClick('.body-bg .pattern', 'bgimage', Array('#bd'));
		<?php } ?>
		
		var array 				= Array('bgimage');
		var array_blue          = Array('pattern8');
		var array_green 	    = Array('pattern8');
		var array_oranges 	    = Array('pattern8');
		var array_lightskyblue 	    = Array('pattern8');
		var array_pink 	    = Array('pattern8');
		
		//1.Color Blue
		$('.theme-color.blue').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_blue);
			onCPApply();
		});
		
		//2.Color Green
		$('.theme-color.green').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_green);
			onCPApply();
		});
		
		//3.Color Oranges
		$('.theme-color.oranges').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_oranges);
			onCPApply();
		});
		
		//4.Color lightskyblue
		$('.theme-color.lightskyblue').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_lightskyblue);
			onCPApply();
		});
		
		//5.Color pink
		$('.theme-color.pink').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_pink);
			onCPApply();
		});
		
		/* miniColorsCPanel */
		function miniColorsCPanel(elC, elT, selector){
			$(elC).miniColors({
				change: function(hex, rgb) {
					if(typeof(elT)!='string'){
						for(i=0;i<elT.length;i++){
							$(elT[i]).css(selector, hex);
						}
					}else{
						$(elT).css(selector, hex); 
					}
					createCookie(TMPL_NAME+'_'+($(this).attr('name').match(/^ytcpanel_(.*)$/))[1], hex, 365);
				}
			});
		}
		/* Begin: Set click pattern */
		function patternClick(elC, paramCookie, elT){
			$(elC).click(function(){
				oldvalue = $(this).parent().find('.active').html();
				$(elC).removeClass('active');
				$(this).addClass('active');
				value = $(this).html();
				if(elT.length > 0){
					for($i=0; $i < elT.length; $i++){
						$(elT[$i]).removeClass(oldvalue);
						$(elT[$i]).addClass(value);
					}
				}
				if(paramCookie){
					$('input[name$="ytcpanel_'+paramCookie+'"]').attr('value', value);
					createCookie(TMPL_NAME+'_'+paramCookie, value, 365);
				}
			});
		}
		function setCpanelValues(array){
			
			if(array['0']){
				$('.body-backgroud-image .pattern').removeClass('active');
				$('.body-backgroud-image .pattern.'+array['3']).addClass('active');
				$('input[name$="ytcpanel_bgimage"]').attr('value', array['3']);
			}
			
		}
	});
	</script>
	<?php
}


// Show Back To Top
if( $yt->getParam('showBacktotop') ) { ?>
	
	<a id="yt-totop" class="backtotop" href="#"><i class="fa fa-angle-up"></i></a>
    <script type="text/javascript">
        jQuery(".backtotop").addClass("hidden-top");
			jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() === 0) {
				jQuery(".backtotop").addClass("hidden-top")
			} else {
				jQuery(".backtotop").removeClass("hidden-top")
			}
		});

		jQuery('.backtotop').click(function () {
			jQuery('body,html').animate({
					scrollTop:0
				}, 1200);
			return false;
		});
    </script>
<?php
}
?>


<?php 
// Show Scroll Reveal Effect	
if( $yt->getParam('animateScroll')){ ?>
	
<?php }; ?>
