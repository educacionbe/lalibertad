<?php
/**
 * @version		$Id: user.php 1618 2012-09-21 11:23:08Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

// Get user stuff (do not change)
$user = JFactory::getUser();

?>


<!-- Start K2 User Layout -->

<div id="k2Container" class="userView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">

	<?php if($this->params->get('show_page_title') && $this->params->get('page_title')!=$this->user->name): ?>
	<!-- Page title -->
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
	<?php endif; ?>

	<?php if($this->params->get('userFeedIcon',1)): ?>
	<!-- RSS feed icon -->
	<div class="k2FeedIcon">
		<a href="<?php echo $this->feed; ?>" title="<?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>">
			<span><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></span>
		</a>
		<div class="clr"></div>
	</div>
	<?php endif; ?>

	<?php if ($this->params->get('userImage') || $this->params->get('userName') || $this->params->get('userDescription') || $this->params->get('userURL') || $this->params->get('userEmail')): ?>
	<div class="userBlock">
	
		<?php if(isset($this->addLink) && JRequest::getInt('id')==$user->id): ?>
		<!-- Item add link -->
		<span class="userItemAddLink">
			<a class="modal" rel="{handler:'iframe',size:{x:990,y:550}}" href="<?php echo $this->addLink; ?>">
				<?php echo JText::_('K2_POST_A_NEW_ITEM'); ?>
			</a>
		</span>
		<?php endif; ?>
	
		<?php if ($this->params->get('userImage') && !empty($this->user->avatar)): ?>
		<img src="<?php echo $this->user->avatar; ?>" alt="<?php echo $this->user->name; ?>" style="width:<?php echo $this->params->get('userImageWidth'); ?>px; height:auto;" />
		<?php endif; ?>
		
		<?php if ($this->params->get('userName')): ?>
		<h2><?php echo $this->user->name; ?></h2>
		<?php endif; ?>
		
		<?php if ($this->params->get('userDescription') && trim($this->user->profile->description)): ?>
		<div class="userDescription"><?php echo $this->user->profile->description; ?></div>
		<?php endif; ?>
		
		<?php if (($this->params->get('userURL') && isset($this->user->profile->url) && $this->user->profile->url) || $this->params->get('userEmail')): ?>
		<div class="userAdditionalInfo">
			<?php if ($this->params->get('userURL') && isset($this->user->profile->url) && $this->user->profile->url): ?>
			<span class="userURL">
				<?php echo JText::_('K2_WEBSITE_URL'); ?>: <a href="<?php echo $this->user->profile->url; ?>" target="_blank" rel="me"><?php echo $this->user->profile->url; ?></a>
			</span>
			<?php endif; ?>

			<?php if ($this->params->get('userEmail')): ?>
			<span class="userEmail">
				<?php echo JText::_('K2_EMAIL'); ?>: <?php echo JHTML::_('Email.cloak', $this->user->email); ?>
			</span>
			<?php endif; ?>	
		</div>
		<?php endif; ?>

		<div class="clr"></div>
		
		<?php echo $this->user->event->K2UserDisplay; ?>
		
		<div class="clr"></div>
	</div>
	<?php endif; ?>



	<?php if(count($this->items)): ?>
	<!-- Item list -->
	<div class="catItemList">
	<?php   $i=0; 
			foreach ($this->items as $item): ?>
			<?php
				$inumber = 2;
				if($i< $inumber) $i++;
				elseif ($i== $inumber) $i=1;
			?>
		<!-- Start K2 Item Layout -->
		<div class="catItemView <?php  echo'item'.$i;?><?php if(!$item->published || ($item->publish_up != $this->nullDate && $item->publish_up > $this->now) || ($item->publish_down != $this->nullDate && $item->publish_down < $this->now)) echo ' userItemViewUnpublished'; ?><?php //echo ($item->featured) ? ' userItemIsFeatured' : ''; ?>">
		
			<!-- Plugins: BeforeDisplay -->
			<?php echo $item->event->BeforeDisplay; ?>
			
			<!-- K2 Plugins: K2BeforeDisplay -->
			<?php echo $item->event->K2BeforeDisplay; ?>
		
			
		
		  <!-- Plugins: AfterDisplayTitle -->
		  <?php echo $item->event->AfterDisplayTitle; ?>
		  
		  <!-- K2 Plugins: K2AfterDisplayTitle -->
		  <?php echo $item->event->K2AfterDisplayTitle; ?>

		  <div class="catItemBody">
		
			  <!-- Plugins: BeforeDisplayContent -->
			  <?php echo $item->event->BeforeDisplayContent; ?>
			  
			  <!-- K2 Plugins: K2BeforeDisplayContent -->
			  <?php echo $item->event->K2BeforeDisplayContent; ?>		
			  <?php if($this->params->get('userItemImage') && !empty($item->imageGeneric)): ?>
			  <!-- Item Image -->
			  <div class="catItemImageBlock pull-left">
                        
					<div class="catItemImage">
						<img src="<?php echo $item->imageGeneric; ?>" alt="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>" />
						
						<?php //Hover item images ?>
						<div class="image-overlay">
							<div class="hover-links clearfix">
								<a class="hover-zoom" data-rel="prettyPhoto"  href="<?php echo $item->imageXLarge; ?>">
									<i class="icon-resize-full"></i>
								</a>
								<a class="hover-link" href="<?php echo $item->link; ?>" title="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>">
									<i class="icon-link"></i>
								</a>
							</div>
						</div>
					</div>
                   
				  <div class="clr"></div>
			  </div>
			  <?php endif; ?>
			  
                
			  <?php if($this->params->get('userItemIntroText')): ?>
			  <!-- Item introtext -->
			  <div class="catItemBody">
			<div class="catItemHeader">			
				<dl class="article-info">
					<?php if($this->params->get('userItemDateCreated')): ?>
						<!-- Date created -->
						<dd class="catItemDateCreated">
							<i class="icon-calendar"></i>
							<?php echo JHTML::_('date', $item->created , JText::_('K2_DATE_FORMAT_LC')); ?>
						</dd>
					<?php endif; ?>
					
					<?php if($this->params->get('userItemCategory')): ?>
					<!-- Item category name -->
					<dd class="catItemCategory">
						<i class="icon-briefcase"></i>
						<a href="<?php echo $item->category->link; ?>"><?php echo $item->category->name; ?></a>
					</dd>
					<?php endif; ?>			  
				</dl>
			</div>			  
			<?php if($this->params->get('userItemTitle')): ?>
			<!-- Item title -->
			<h3 class="catItemTitle">
					<?php if(isset($item->editLink)): ?>
					<!-- Item edit link -->
					<span class="catItemEditLink">
						<a class="modal" rel="{handler:'iframe',size:{x:990,y:550}}" href="<?php echo $item->editLink; ?>">
							<?php echo JText::_('K2_EDIT_ITEM'); ?>
						</a>
					</span>
					<?php endif; ?>

				<?php if ($this->params->get('userItemTitleLinked') && $item->published): ?>
					<a href="<?php echo $item->link; ?>">
					<?php echo $item->title; ?>
				</a>
				<?php else: ?>
				<?php echo $item->title; ?>
				<?php endif; ?>
				<?php if(!$item->published || ($item->publish_up != $this->nullDate && $item->publish_up > $this->now) || ($item->publish_down != $this->nullDate && $item->publish_down < $this->now)): ?>
				<span>
					<sup>
						<?php echo JText::_('K2_UNPUBLISHED'); ?>
					</sup>
				</span>
				<?php endif; ?>
			</h3>
			<?php endif; ?>
						  
			  	<?php echo $item->introtext; ?>
                 <?php if ($this->params->get('userItemReadMore')): ?>
                <!-- Item "read more..." link -->
                
				<a class="readmore" href="<?php echo $item->link; ?>">
					<?php echo JText::_('K2_READ_MORE'); ?> <i class="ico-arrow-right"></i>
				</a>
                
                <?php endif; ?>
			  </div>
               
			  <?php endif; ?>
		
				<div class="clr"></div>

			  <!-- Plugins: AfterDisplayContent -->
			  <?php echo $item->event->AfterDisplayContent; ?>
			  
			  <!-- K2 Plugins: K2AfterDisplayContent -->
			  <?php echo $item->event->K2AfterDisplayContent; ?>
		
			  <div class="clr"></div>
		  </div>
		
		  <?php if( $this->params->get('userItemTags')): ?>
		  <div class="catItemLinks">


				
		  </div>
		  <?php endif; ?>  

		  <!-- Plugins: AfterDisplay -->
		  <?php echo $item->event->AfterDisplay; ?>
		  
		  <!-- K2 Plugins: K2AfterDisplay -->
		  <?php echo $item->event->K2AfterDisplay; ?>
			
			<div class="clr"></div>
		</div>
		<!-- End K2 Item Layout -->
		
		<?php endforeach; ?>
	</div>

	<!-- Pagination -->
	<?php if(count($this->pagination->getPagesLinks())): ?>
	<div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<div class="clr"></div>
		<?php //echo $this->pagination->getPagesCounter(); ?>
		<?php if($this->params->get('catPaginationResults')) echo $this->pagination->getPagesCounter(); ?>
	</div>
	<?php endif; ?>
	
	<?php endif; ?>

</div>

<!-- End K2 User Layout -->
