<?php
/**
 * @package Sj K2 Slider
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;

JHtml::stylesheet('modules/'.$module->module.'/assets/css/slider.css');
if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
JHtml::script('modules/'.$module->module.'/assets/js/slider.js');
JHtml::script('modules/'.$module->module.'/assets/js/jquery.cj-swipe.js');

ImageHelper::setDefault($params);
$options = $params->toObject();
$tag_id ='sj_k2_slider_'.rand().time();

$auto_play = (int)$params->get('play',1);
$delay = (int)$params->get('delay',2500);
if($auto_play == 1 || ($auto_play == 1 && $delay <= 0) ){
	$delay = ($delay > 0)?$delay:2500;
}else if($auto_play == 0){
	$delay = 0;
}
$effect = $params->get('effect');	


if(!empty($list)){?>
	<?php if(!empty($options->pretext)) { ?>
		<div class="pre-text"><?php echo $options->pretext; ?></div>
	<?php } ?>
	<div id="<?php echo $tag_id; ?>" class="sj-k2-container-slider" style="<?php if( $options->anchor == "bottom" ){ echo "margin-bottom:40px;"; }?>">
			<?php if(($options->title_slider_display == 1) && $options->slider_title_text !=''){?>
				<div class="page-title"><?php echo $options->slider_title_text;?></div>
			<?php }?>
			
			<?php if($options->anchor =="top"){?>
			<?php if($options->button_display == 1){?>
			<div class="page-button <?php echo $options->anchor;?> <?php echo $options->control;?>">
				<ul class="control-button preload">
					<li class="preview">Prev</li>
					<li class="next">Next</li>
				</ul>		
			</div>
			<?php }}?>
		
		<?php 
		$count_items = count($list);
		
		if($options->deviceclass_sfx1 > $count_items){
			$options->deviceclass_sfx1 = $count_items;
		}
		
		if($options->deviceclass_sfx2 > $count_items){
			$options->deviceclass_sfx2 = $count_items;
		}
		
		if($options->deviceclass_sfx3 > $count_items){
			$options->deviceclass_sfx3 = $count_items;
		}
		
		if($options->deviceclass_sfx4 > $count_items){
			$options->deviceclass_sfx4 = $count_items;
		}
		
		if($options->deviceclass_sfx5 > $count_items){
			$options->deviceclass_sfx5 = $count_items;
		}
		
		$deviceclass_sfx = 'preset01-'.$options->deviceclass_sfx1.' '.'preset02-'.$options->deviceclass_sfx2.' '.'preset03-'.$options->deviceclass_sfx3.' '.'preset04-'.$options->deviceclass_sfx4.' '.'preset05-'.$options->deviceclass_sfx5;
		
		?>
		<div class="slider not-js cols-6 <?php echo $deviceclass_sfx; ?>">
			<div class="vpo-wrap">
				<div class="vp">
					<div class="vpi-wrap">
					<?php foreach($list as $item){?>
						<div class="item">
							<div class="item-wrap">							
							<?php 
								

								$img = SjK2SliderHelper::getK2Image($item, $params);					
								$img = ImageHelper::init($img)->src();							
								
								$img = (strpos($img,'http://') !== false || strpos($img,'https://') !== false)?$img:(JURI::root().$img);
							
								if((is_file($img) && file_exists($img)) || SjK2SliderHelper::isUrl($img)){
									 ?>
									
									<div class="item-img item-height">
										<div class="item-img-info">
											<a href="<?php echo $item->link;?>"  <?php echo  SjK2SliderHelper::parseTarget($options->item_link_target);?>>
												<img alt="<?php echo $item->displaytitle;?>" src="<?php echo $img;?>"/>	
											</a>
										</div>
									</div>
								<?php }?>
									
								<div class="item-info <?php if( $options->theme == "theme2" ){ echo "item-spotlight"; }?> ">
									<div class="item-inner">
									<?php if($item->displaytitle != '') { ?>
										<div class="item-title">
											<a href="<?php echo $item->link;?>" title="<?php echo $item->displaytitle; ?>"  <?php echo  SjK2SliderHelper::parseTarget($options->item_link_target);?>>
												<?php echo $item->displaytitle;?>
											</a>
										</div>
									<?php }?>
										<div class="item-content">
											<?php if($item->displayIntrotext != ''  && SjK2SliderHelper::_trimEncode($item->displayIntrotext) != '') { ?>
												<div class="item-des">
													<?php echo $item->displayIntrotext; ?>							
												</div>
											<?php }
											
											// show tags
				
											if($item->tags !=''){?>
												<div class="tags">
													<?php $i = -1; foreach ($item->tags as $tag): $i++; ?>
													<span class="tag-<?php echo $tag->id.' tag-list'.$i; ?>">
														<a class="label label-info" href="<?php echo $tag->link; ?>" title="<?php echo $tag->name; ?>" target="_blank"><?php echo $tag->name; ?></a>
													</span>
													<?php endforeach; ?>
												 </div>					
											<?php }	
											if((int)$params->get('item_created_display',1)) { ?>
												<div class="item-created">
													<?php echo  JHTML::_('date', $item->created,JText::_('DATE_FORMAT_LC3')) ;?>
												</div>
											<?php }
											if( (int)$options->item_readmore_display && ($options->item_readmore_text !='' )){ ?>
												<div class="item-read">
													<a href="<?php echo $item->link; ?>" title="<?php echo $item->displaytitle; ?>" <?php echo SjK2SliderHelper::parseTarget($options->item_link_target); ?> >							
														<?php echo $options->item_readmore_text; ?>
													</a>
												</div>
											<?php } // readmore display ?>
																
										</div>	
										<?php if( $options->theme == "theme2" ){
											if( $options->item_title_display == 1 || $options->item_desc_display == 1 || $options->item_readmore_display == 1 ){?>
											<div class="item-bg"></div>				
										<?php }}?>		
									</div>
								</div>						
							</div>
						</div>
					<?php }?>
					</div>
				</div>
			</div>
		</div>
		
		<?php if($options->anchor !="top"){?>
			<?php if($options->button_display == 1){?>
			<div class="page-button <?php echo $options->anchor;?> <?php echo $options->control;?>">
				<ul class="control-button preload">
					<li class="preview">Prev</li>
					<li class="next">Next</li>
				</ul>		
			</div>
		<?php }}?>
		
	</div>
	<?php if(!empty($options->posttext)) {  ?>
		<div class="post-text"><?php echo $options->posttext; ?></div>
	<?php } ?>


<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function($){
		;(function(element){
			var $element = $(element);
			var $slider = $('.slider', $element)
			$('.slider', $element).responsiver({
				interval: <?php echo $delay;?>,
				speed: <?php echo $options->duration;?>,
				start: <?php echo $options->start -1;?>,
				step: <?php echo $options->scroll;?>,
				circular: true,
				preload: true,
				fx: '<?php echo $effect; ?>',
				pause: '<?php echo ($options->pause_hover == 'hover')?"hover":"null"; ?>',
				control:{
					prev: '#<?php echo $tag_id;?> .control-button li[class="preview"]',
					next: '#<?php echo $tag_id;?> .control-button li[class="next"]'
				},
				getColumns: function(element){
					var match = $(element).attr('class').match(/cols-(\d+)/);
					if (match[1]){
						var column = parseInt(match[1]);
					} else {
						var column = 1;
					}
					if (!column) column = 1;
					return column;
				}          
			});
			<?php 
				if($options->swipe_enable == 1) {	?>
					$slider.touchSwipeLeft(function(){
						$slider.responsiver('next');
						}
					);
					$slider.touchSwipeRight(function(){
						$slider.responsiver('prev');
						}
					);
				<?php } ?>	
			$('.control-button',$element).removeClass('preload');
		})('#<?php echo $tag_id; ?>');
    });
//]]>
</script>
<?php }else {echo JText::_('HAS_NO_CONTENT');}?>