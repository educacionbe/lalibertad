<?php
/*
 * ------------------------------------------------------------------------
 * Yt FrameWork for Joomla 3.0
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2012 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;
if(!defined('YT_FRAMEWORK')){
	throw new Exception(JText::_('INSTALL_YT_PLUGIN'));
}
if(!defined('J_TEMPLATEDIR')){
	define('J_TEMPLATEDIR', JPATH_SITE.J_SEPARATOR.'templates'.J_SEPARATOR.$this->template);
}
include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'lib'.J_SEPARATOR.'template.php');

global $yt;
$params_cookie = array();
$yt = new YtTemplate($this, $params_cookie);

// Check RTL or LTF direction
$doc = JFactory::getDocument();
$direction = $doc->direction;
$dir = ($direction == 'rtl') ? ' dir="rtl"' : '';

$app   = JFactory::getApplication();
$doc   = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$print = JRequest::getCmd('print');

?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="head" />
    <?php include_once (J_TEMPLATEDIR.'/includes/head.php');
		// Include CSS 
		$yt->ytStyleSheet('templates/protostar/css/template.css');
		
		// Include jQuery & bootstrap's javascript 
		$doc->addScript($yt->templateurl().'asset/bootstrap/js/bootstrap.min.js');
		$doc->addScript($yt->templateurl().'js/touchswipe.min.js');
	?>
	<?php if($print == 1) : ?> 
		<meta name="viewport" content="width=device-width, target-densitydpi=160dpi, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<?php endif; ?>
</head>
<body class="contentpane<?php echo ($direction == 'rtl')?' rtl':''; ?>">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>




