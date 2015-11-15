<?php
/**
 * @package Sj K2 Extra Slider
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
 
defined('_JEXEC') or die;
	
  if(!empty($list)){
    JHtml::stylesheet('modules/'.$module->module.'/assets/css/style.css');
    JHtml::stylesheet('modules/'.$module->module.'/assets/css/css3.css');
	if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
		JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
		JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
		define('SMART_JQUERY', 1);
	}
	
	JHtml::script('modules/'.$module->module.'/assets/js/jcarousel.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery.mousewheel.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery.touchwipe.1.1.1.js');
	
    
    ImageHelper::setDefault($params);
    $options=$params->toObject();
	$count_item = count($list);
	$item_of_page = $options->items_page;
	$pags = (int)ceil($count_item/$item_of_page);
	$suffix = rand().time();
	$tag_id = 'sjextraslider_'.$suffix;	
	
	$play = $params->get('play', 1);
	if (!$play){
		$interval = 0;
	} else {
		$interval = $params->get('interval', 5000);
	}	
	
	$nb_column1 = ($params->get('nb-column1',6) >= $item_of_page)?$item_of_page:$params->get('nb-column1',6);
	$nb_column2 = ($params->get('nb-column2',4) >= $item_of_page)?$item_of_page:$params->get('nb-column2',4);
	$nb_column3 = ($params->get('nb-column3',2) >= $item_of_page)?$item_of_page:$params->get('nb-column3',2);
	$nb_column4 = ($params->get('nb-column4',1) >= $item_of_page)?$item_of_page:$params->get('nb-column4',1);
	   
	?>
	<?php $class_respl= 'extra-resp01-'.$nb_column1.' extra-resp02-'.$nb_column2.' extra-resp03-'.$nb_column3.' extra-resp04-'.$nb_column4; ?>
	<!--[if lt IE 9]><div id="<?php echo $tag_id;?>" class="sj-extraslider msie lt-ie9 <?php if( $options->effect == 'slide' ){ echo $options->effect;}?>  <?php echo $class_respl; ?>"  data-interval="<?php echo $interval; ?>" data-pause="<?php echo $params->get('pause_hover'); ?>"><![endif]-->
	<!--[if IE 9]><div id="<?php echo $tag_id;?>" class="sj-extraslider msie <?php if( $options->effect == 'slide' ){ echo $options->effect;}?>  <?php echo $class_respl; ?>"  data-interval="<?php echo $interval; ?>" data-pause="<?php echo $params->get('pause_hover'); ?>"><![endif]-->
	<!--[if gt IE 9]><!--><div id="<?php echo $tag_id;?>" class="sj-extraslider <?php if( $options->effect == 'slide' ){ echo $options->effect;}?>  <?php echo $class_respl; ?>"   data-interval="<?php echo $interval; ?>" data-pause="<?php echo $params->get('pause_hover'); ?>"><!--<![endif]-->
		<?php if(!empty($options->pretext)) { ?>
			<div class="pre-text"><?php echo $options->pretext; ?></div>
		<?php } ?> 
        <?php if($options->title_slider_display == 1){?>
            <div class="heading-title"><?php echo $options->title_slider;?></div><!--end heading-title-->
        <?php }?>		    
    	<div class="extraslider-control  <?php if( $options->button_page == 'under' ){echo 'button-type2';}?>">
		    <a class="button-prev" href="<?php echo '#'.$tag_id;?>" data-jslide="prev"></a>
		    <?php if( $options->button_page == 'top' ){?>
		    <ul class="nav-page">
		    <?php $j = 0;$page = 0;
		    	for($i=0; $i<$pags; $i++){ $j ++;
				$active_class = $page == 0 ? " active" : "";$page ++;
		    		//if( $j%$item_of_page == 1 || $item_of_page == 1 ){$page ++;?>
		    		<li class="page">
		    			<a class="button-page <?php if( $page==1 ){echo 'sel';}?>" href="<?php echo '#'.$tag_id;?>" data-jslide="<?php echo $page-1;?>"></a>
		    		</li>
	    		<?php }//}?>
		    </ul>
		    <?php }?>
		    <a class="button-next" href="<?php echo '#'.$tag_id;?>" data-jslide="next"></a>
	    </div>
	    <div class="extraslider-inner">
	    <?php 
		for($i=0; $i<$pags; $i++){ 
			$count = 0; $i = 0; $j = 0;
			foreach ($list as $item){$count ++; $i++; 
				if($j == $item_of_page){
						$j = 0;
					}
				$j++; 
			?>
				<?php  if($count%$item_of_page == 1 || $item_of_page == 1){?>
				<div class="item <?php if($i==1){echo "active";}?>">
					<div class="line">
				<?php }?>
					<div class="item-wrap <?php echo $options->theme;?>">
						<div class="item-wrap-inner">
							<?php $img = K2ExtrasliderHelper::getK2Image($item, $params);
							if($img){
							?>
							<div class="item-image">
								<?php   echo K2ExtrasliderHelper::imageTag($img);?>
							</div>
							<?php } ?>
							<?php if( $options->item_title_display == 1 || $options->item_desc_display == 1 || ( $item->tags != '') || $options->item_readmore_display == 1 ){ ?>
							<div class="item-info">
							<?php if( $options->item_title_display == 1 ){?>
								<div class="item-title">
									<a href="<?php echo $item->link;?>" title="<?php echo $item->title ?>" <?php echo K2ExtrasliderHelper::parseTarget($params->get('item_link_target')); ?>>
										<?php echo K2ExtrasliderHelper::truncate($item->title, $params->get('item_title_max_characs',25));?>
									</a>    			     
								</div>
							<?php }?>
							<?php if( ($options->item_desc_display == 1 && !empty($item->displayIntrotext)) || ($item->tags != '') || $options->item_readmore_display == 1 ){?>
								<div class="item-content">
									<?php if( $options->item_desc_display == 1 ){?>
									<div class="item-description">
										<?php echo $item->displayIntrotext;?>
									</div>
									<?php }?>
									<?php if($item->tags != ''){?>
									<div class="item-tags">
										<div class="tags">
											<?php $hd = -1; foreach ($item->tags as $tag): $hd++; ?>
											<span class="tag-<?php echo $tag->id.' tag-list'.$hd; ?>">
												<a class="label label-info" href="<?php echo $tag->link; ?>" title="<?php echo $tag->name; ?>" target="_blank">
													<?php echo $tag->name; ?>
												</a>
											</span>
											<?php endforeach; ?>
										 </div>
									</div>
									<?php }	?>
									<?php if( $options->item_readmore_display == 1 ){?>
										<div class="item-readmore">
											<a href="<?php echo $item->link;?>" title="<?php echo $item->title ?>" <?php echo K2ExtrasliderHelper::parseTarget($params->get('item_link_target')); ?>>
												<?php echo $options->item_readmore_text;?>
											</a>                                
										</div> 
									<?php }?>                               
								</div>
							<?php } ?>	
							</div>
						<?php }?>
						</div> 
					</div>
					<?php
						$clear = 'clr1';
						if ($j % 2 == 0) $clear .= ' clr2';
						if ($j % 3 == 0) $clear .= ' clr3';
						if ($j % 4 == 0) $clear .= ' clr4';
						if ($j % 5 == 0) $clear .= ' clr5';
						if ($j % 6 == 0) $clear .= ' clr6';
					?>
					<div class="<?php echo $clear; ?>"></div> 					
				<?php if(($count%$item_of_page == 0 || $count== $count_item)){?> 
					</div><!--line-->				
				</div><!--end item--> 
				<?php }?>
			<?php }?>
		<?php } ?>	
	    </div><!--end extraslider-inner -->
	    <?php if( $options->button_page == 'under' ){?>
	    <ul class="nav-page nav-under">
	    <?php $j = 0;$page = 0;
	    	for($i=0; $i<$pags; $i++){$j ++;
			$active_class = $page == 0 ? " active" : "";
			$page ++;
	    		//if( $j%$item_of_page == 1 || $item_of_page == 1 ){$page ++;?>
	    		<li class="page">
	    			<a class="button-page <?php if( $page==1 ){echo 'sel';}?>" href="<?php echo '#'.$tag_id;?>" data-jslide="<?php echo $page-1;?>"></a>
	    		</li>
    		<?php }//}?>
	    </ul>
	    <?php }?>	    
		<?php if(!empty($options->posttext)) {  ?>
			<div class="post-text"><?php echo $options->posttext; ?></div>
		<?php }?>
    </div>
<script>
//<![CDATA[    					
	jQuery(document).ready(function($){
			$('#<?php echo $tag_id; ?>').each(function(){
				var $this = $(this), options = options = !$this.data('modal') && $.extend({}, $this.data());
				$this.jcarousel(options);
				$this.bind('jslide', function(e){
					var index = $(this).find(e.relatedTarget).index();
					// process for nav
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
					$this.touchwipe({
						wipeLeft: function() { 
							$this.jcarousel('next');
							return false;
						},
						wipeRight: function() { 
							$this.jcarousel('prev');
							return false;
						},
						wipeUp: function() { 
							$this.jcarousel('next');
							return false;
						},
						wipeDown: function() {
							$this.jcarousel('prev');
							return false;
						}
					});
				<?php } ?>	
				return ;
			});
	});
//]]>	
</script>
	
<?php }else{ echo JText::_('Has no item to show!');}?>



