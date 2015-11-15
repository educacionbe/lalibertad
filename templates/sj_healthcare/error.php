<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}

//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>

<html  lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="HandheldFriendly" content="true">
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" href="<?php echo $this->baseurl.'/templates/'.$this->template; ?>/css/error.css" type="text/css" />	
</head>
<body>
	
	
	<div class="wrapall">
		<div class="wrap-inner">
			<div class="contener">
				<a href="index.php" class="link-home">
					<img class="img_404" src="<?php echo JURI::base() . 'templates/' . JFactory::getApplication()->getTemplate();?>/images/404/error.png" alt="" />
					<img class="img-logo" src="<?php echo JURI::base() . 'templates/' . JFactory::getApplication()->getTemplate();?>/images/styling/lightskyblue/logo.png" alt="" />
				</a>
				<div class="message">
					
					<p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?> <?php echo $this->error->getMessage(); ?></p>
					
				</div>
				<div class="buttom-home">
					<a class="btn" href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>">
						<?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?>
					</a>
				</div>
				<span class="please"><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?>.</span>
				<span>
					<?php if ($this->debug) :
						echo $this->renderBacktrace();
					endif; ?>
				</span>
			</div>
		</div>
	</div>
		
</body>
</html>
