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

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
require_once dirname(__FILE__).'/core/helper.php';

$idbase = $params->get('catid');
$cacheid = md5(serialize(array ($idbase, $module->id)));
$cacheparams = new stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = 'K2ExtrasliderHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = $cacheid;
$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));?>

