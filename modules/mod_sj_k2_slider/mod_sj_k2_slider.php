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

//check the exist of k2 component on the site?

if(file_exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php') && file_exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php')){

	// Include the helper functions only once
	require_once dirname(__FILE__) . '/core/helper.php';
	$idbase = $params->get('catid');
	$cacheid = md5(serialize(array ($idbase, $module->id)));
	$cacheparams = new stdClass;
	$cacheparams->cachemode = 'id';
	$cacheparams->class = 'SjK2SliderHelper';
	$cacheparams->method = 'getList';
	$cacheparams->methodparams = $params;
	$cacheparams->modeparams = $cacheid;
	$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	require JModuleHelper::getLayoutPath( $module->module, $params->get('layout', 'default'));

}else{
	echo JText::_('PLEASE_INSTALL_K2_COMPONENT_FIRST');
}
