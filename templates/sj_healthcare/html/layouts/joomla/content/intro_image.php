<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
// includes placehold
$yt_temp = JFactory::getApplication()->getTemplate();
include (JPATH_BASE . '/templates/'.$yt_temp.'/includes/placehold.php');

// Create a shortcut for params.
$params  = $displayData->params;

global $leadingFlag;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$templateParams = JFactory::getApplication()->getTemplate(true)->params;

?>
<?php $images = json_decode($displayData->images);?>

<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
    <?php
	
	// Begin:  The way to resize your image.
	$templateParams = JFactory::getApplication()->getTemplate(true)->params;
	YTTemplateUtils::getImageResizerHelper(array(
		'background' => $templateParams->get('thumbnail_background', '#000'), 
		'thumbnail_mode' => $templateParams->get('thumbnail_mode', 'fit')
		)
	);
	$imgW = (isset($leadingFlag) && $leadingFlag)?$templateParams->get('leading_width', '300'):$templateParams->get('intro_width', '200');
	$imgH = (isset($leadingFlag) && $leadingFlag)?$templateParams->get('leading_height', '300'):$templateParams->get('intro_height', '200');
	$imgsrc = YTTemplateUtils::resize($images->image_intro, $imgW, $imgH);
	
	//Create placeholder items images
	$src = $images->image_intro;
	if (file_exists(JPATH_BASE . '/' . $src)) {								
		$thumb_img = '<img src="'.$imgsrc.'" alt="'.$images->image_intro_alt.'" />';
		$full_img =  JURI::base().'/'.htmlspecialchars($images->image_intro);
	} else if ($is_placehold) {					
		$thumb_img = yt_placehold($placehold_size['listing']);
		$full_img  = 'http://placehold.it/'.$placehold_size['article'].'/969696';
	}	
	?>
	<figure class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image" >
		<a  class="listingimg" data-rel="prettyPhoto"   title="<?php echo htmlspecialchars($images->image_intro_alt); ?>" href="<?php echo $full_img; ?>"  >
			<?php echo $thumb_img; ?>
		</a>
    </figure>
<?php endif; ?>
