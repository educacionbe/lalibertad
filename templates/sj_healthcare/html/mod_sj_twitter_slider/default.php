<?php
/**
 * @package SJ Twitter Slider
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ( '_JEXEC' ) or die ();

JHtml::stylesheet('modules/'.$module->module.'/assets/css/styles.css');
if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}

if (!defined('SMART_JCAROSEL')) {
	JHtml::script('modules/'.$module->module.'/assets/js/jcarousel.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery.cj-swipe.js');
	define('SMART_JCAROUSEL', 1);
}

$tag_id = 'sj_twitter_slider_'.rand().time();

$play = (int)$params->get('play',1);

$interval = (int)$params->get('interval',4000);
$interval = ( $play ) ? $interval : 0;

$start = (int)$params->get('start',1);
$start = ($start <= 0 || $start >= count($list)) ? 1 : $start - 1 ;

$pause_hover = $params->get('pause_hover','hover');
$pause_hover = ( $pause_hover == 0 ) ? '' : 'hover';

$effect = $params->get('effect','slide');
$effect = ( $effect == 'slide' ) ? 'slide' : '';

if($params->get('pretext') != ''){?>	
<div class="sc-pretext">
	<?php echo $params->get('pretext'); ?>
</div>
<?php }
if(!empty($list)) { ?>

<!--Begin sj-twitter-slider-->
<div id="<?php echo $tag_id; ?>" class="sj-twitter-slider">
	<script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
	<!--Begin ts-wrap-->
	<div class="ts-wrap">
		<?php 
		$screen_label = '';
		if((int)$params->get('display_avatars',1)) { $t = 0;
		foreach($list as $item) { $t++;
			if($t == 1) { $screen_label = $item->_username ; ?>
			<div class="ts-header">
				<a title="<?php echo $item->_full_name; ?>" target="_blank" class="ts-avatar" href="<?php echo $item->_twitter_link; ?>">
					<span class="ts-mask">
						<span class="ts-mask-logo">Open in Twitter</span>
					</span>
					<img src="<?php echo $item->_image; ?>" alt="<?php echo $item->_full_name; ?>" title="<?php echo $item->_full_name; ?>">
				</a>
				<div class="ts-userinfo">
					<h2><?php echo $item->_full_name; ?></h2>
					<a target="_blank" href="<?php echo $item->_twitter_link; ?>">
						<?php echo $item->_username; ?>
					</a>
				</div>
			</div>
			<?php } 
			}
		}
		
		$slider_id = 'ts_slider_wap'.rand().time().$module->id; ?>
		<!--Begin ts-slider-wrap-->
		<div class="ts-slider-wrap <?php echo $effect; ?>" id="<?php echo $slider_id; ?>" 
			data-interval="<?php echo $interval; ?>" data-pause="<?php echo $pause_hover;  ?>"
			>
			<div class="ts-items">
				<?php $j = -1; $_count=0; $nb_rows = 3; 
					foreach($list as  $item) {  $_count ++ ; ?>
					<?php
					   if($_count%$nb_rows == 1) {
					   $j++; $active_cls = ($j == $start) ? 'active':''; 
					?>
					<div class="ts-item item <?php echo $active_cls ; ?>">
					<?php } ?>
						<div class="item-inner">
							<div class="ts-created">
								<a href="https://twitter.com/<?php echo $params->get('screenname')?>/status/<?php echo $item->id_str; ?>" target="_blank" title="<?php echo $item->_full_name; ?>" >
									<?php echo date('j F Y',strtotime($item->created_at)); ?>
								</a>
							</div>
							<div class="ts-text">
								<?php echo $item->_text; ?>
							</div>
							<div class="ts-btn">
								<a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $item->id_str; ?>" target="_blank"  class="reply-tweet">
									<i class="fa fa-share"></i><?php echo JText::_('REPLY_LABEL'); ?>
								</a>
								<a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $item->id_str; ?>" class="retweet">
									<i class="fa fa-retweet"></i><?php echo JText::_('RETWEET_LABEL'); ?>
								</a>
								<a href="https://twitter.com/intent/favorite?tweet_id=<?php echo $item->id_str; ?>" class="favorite-tweet">
									<i class="fa fa-star"></i><?php echo JText::_('FAVORITE_LABEL'); ?>
								</a>
							</div>
						</div>
					<?php 
					if($_count%$nb_rows == 0 || $_count == count($list) ) {
					?>	
					</div>
					<?php 
					}
				} ?>
			</div>
			
			<!--Begin ts-control-->
			<div class="ts-control">
				 <?php
				 if((int)$params->get('display_direction_button',1)) {?>	
				 <a class="ts-ctr-prev ts-ctr" href="#<?php echo $slider_id; ?>" data-jslide="prev">&lsaquo;</a>
				 <?php  } ?>
				 <div class="ts-ctr-pages">
					  <?php $k = -1; $pags = (int)ceil(count($list)/3);
					  for($i=0; $i< $pags; $i++){ $k ++;
						$sel_class = $k == $start  ? " sel" : ""; ?>
						<a class="ts-ctr-page <?php echo $sel_class; ?>" href="#<?php echo $slider_id; ?>" data-jslide="<?php echo $k ;?>">
						pre
						</a>
					  <?php }?>
				</div>
				 <?php
				 if((int)$params->get('display_direction_button',1)) {?>	
				 <a class="ts-ctr-next ts-ctr" href="#<?php echo $slider_id; ?>" data-jslide="next">&rsaquo;</a>
				 <?php  } ?>
			</div>
			<!--End ts-control-->
			
		</div>
		<!--End ts-slider-wrap-->
		
		<?php
		if((int)$params->get('display_follow_button',1)) {	?>
		<!--Begin ts-btn-follow-->
		<div class="ts-btn-follow">
			<a href="https://twitter.com/<?php echo $screen_label; ?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $screen_label; ?></a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
			</script>
		</div>
		<!--End ts-btn-follow-->
		<?php } ?>
	</div>
	<!--End ts-wrap-->
</div>
<!--End sj-twitter-slider-->
<?php 
} else { 
	echo JText::_('Has no content to show!');	
} 
if($params->get('posttext') != ''){?>	
<div class="sc-posttext">
	<?php echo $params->get('posttext'); ?>
</div>
<?php } ?>
<script type="text/javascript">
//<![CDATA[    					
	jQuery(document).ready(function($){
		;(function(element){
			var $element = $(element);
			var $_slider = $('#<?php echo $slider_id; ?>', $element);
				$_slider.each(function(){
				var $this = $(this), options = options = !$this.data('modal') && $.extend({}, $this.data());
				$this.jcarousel(options);
				$this.bind('jslide', function(e){
					var index = $(this).find(e.relatedTarget).index();
					$('[data-jslide]').each(function(){
						var $nav = $(this), $navData = $nav.data(), href, $target = $($nav.attr('data-target') || (href = $nav.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, ''));
						if ( !$target.is($this) ) return;
						if (typeof $navData.jslide == 'number' && $navData.jslide==index){
							$nav.addClass('sel');
						} else {
							$nav.removeClass('sel');
						}
					});
				});
				<?php 
				if($params->get('swipe_enable') == 1) {	?>
					$this.touchSwipeLeft(function(){
						$this.jcarousel('next');
						}
					);
					$this.touchSwipeRight(function(){
						$this.jcarousel('prev');
						}
					);
				<?php } ?>	
				return ;
			});	
			
		})('#<?php echo $tag_id; ?>');
	});
//]]>	
</script>