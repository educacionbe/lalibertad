<?php

/**
 * JCH Optimize - Joomla! plugin to aggregate and minify external resources for
 * optmized downloads
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall
 * @license GNU/GPLv3, See LICENSE file
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die;

require_once dirname(__FILE__) .'/compat.php';

abstract class JFormFieldAuto extends JFormFieldCompat
{

        protected $bResources = FALSE;
        
        /**
         * 
         * @return string
         */
        protected function getInput()
        {
                //JCH_DEBUG ? JchPlatformProfiler::mark('beforeGetInput - ' . $this->type . ' plgSystem (JCH Optimize)') : null;
                
                $aButton = $this->getButtons();
                $sField  = '<div class="container-icons clearfix">';

                foreach ($aButton as $sButton)
                {
                $sField .= <<<JFIELD
<div class="icon {$sButton['class']} ">
        <a href="{$sButton['link']}"  {$sButton['script']}  >
                <div style="text-align: center;">
                        <i class="fa {$sButton['icon']} fa-3x" style="margin: 7px 0; color: {$sButton['color']}"></i>
                </div>
                <span >{$sButton['text']}</span><br>
                <span class="pro-only"><em>(Pro Only)</em></span>
        </a>
</div>
JFIELD;
                }

                $sField .= '</div>';
                
               // JCH_DEBUG ? JchPlatformProfiler::mark('beforeGetInput - ' . $this->type . ' plgSystem (JCH Optimize)') : null;
                
                return $sField;
        }

        /**
         * 
         */
        protected function cleanCache()
        {

                $oConf = JFactory::getConfig();

                $aOptions = array(
                        'defaultgroup' => '',
                        'storage'      => $oConf->get('cache_handler', ''),
                        'caching'      => true,
                        'cachebase'    => JPATH_SITE . '/cache'
                );

                $oCache = JCache::getInstance('', $aOptions);

                $oController = new JControllerLegacy();

                if ($oCache->clean('plg_jch_optimize') === FALSE || $oCache->clean('page') === FALSE)
                {
                        $oController->setMessage(JText::_('JCH_CACHECLEAN_FAILED'), 'error');
                }

                $aOptions['cachebase'] = JPATH_ADMINISTRATOR . '/cache';

                $oAdminCache = JCache::getInstance('', $aOptions);

                if ($oAdminCache->clean('plg_jch_optimize') === FALSE)
                {
                        $oController->setMessage(JText::_('JCH_CACHECLEAN_FAILED'), 'error');
                }
                else
                {
                        $oController->setMessage(JText::_('JCH_CACHECLEAN_SUCCESS'));
                }

                $this->display($oController);
        }

        /**
         * 
         * @return type
         */
        protected function getPlugins()
        {
                $oDb    = JFactory::getDbo();
                $oQuery = $oDb->getQuery(TRUE);
                $oQuery->select($oDb->quoteName(array('extension_id', 'ordering', 'element')))
                        ->from($oDb->quoteName('#__extensions'))
                        ->where(array(
                                $oDb->quoteName('type') . ' = ' . $oDb->quote('plugin'),
                                $oDb->quoteName('folder') . ' = ' . $oDb->quote('system')
                                ), 'AND');

                $oDb->setQuery($oQuery);

                return $oDb->loadAssocList('element');
        }

        /**
         * 
         * @return type
         */
        protected function orderPlugins()
        {
                $aOrder = array('jscsscontrol', 'jqueryeasy', 'jch_optimize', 'plugin_googlemap3', 'cdnforjoomla', 'bigshotgoogleanalytics', 'GoogleAnalytics', 'jat3', 'cache', 'jSGCache', 'jotcache', 'vmcache_last');

                $aPlugins = $this->getPlugins();

                $aLowerPlugins = array_values(array_filter($aOrder,
                                                           function($aVal) use ($aPlugins)
                        {
                                return (array_key_exists($aVal, $aPlugins));
                        }));

                $iNoPlugins      = count($aPlugins);
                $iNoLowerPlugins = count($aLowerPlugins);
                $iBaseOrder      = $iNoPlugins - $iNoLowerPlugins;

                $cid   = array();
                $order = array();

                foreach ($aPlugins as $key => $value)
                {
                        if (in_array($key, $aLowerPlugins))
                        {
                                $value['ordering'] = $iBaseOrder + 1 + array_search($key, $aLowerPlugins);
                        }
                        elseif ($value['ordering'] >= $iBaseOrder)
                        {
                                $value['ordering'] = $iBaseOrder - 1;
                        }

                        $cid[]   = $value['extension_id'];
                        $order[] = $value['ordering'];
                }

                JArrayHelper::toInteger($cid);
                JArrayHelper::toInteger($order);

                $aOrder          = array();
                $aOrder['cid']   = $cid;
                $aOrder['order'] = $order;

                $oController = new JControllerLegacy;

                $oController->addModelPath(JPATH_ADMINISTRATOR . '/components/com_plugins/models', 'PluginsModel');
                $oPluginModel = $oController->getModel('Plugin', 'PluginsModel');

                if ($oPluginModel->saveorder($aOrder['cid'], $aOrder['order']) === FALSE)
                {
                        $oController->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $oPluginModel->getError()), 'error');
                }
                else
                {
                        $oController->setMessage(JText::_('JLIB_APPLICATION_SUCCESS_ORDERING_SAVED'));
                }

                $this->display($oController);
        }

        /**
         * 
         * @param type $oController
         */
        protected function display($oController)
        {
                $oUri = clone JUri::getInstance();
                $oUri->delVar('jchtask');
                $oUri->delVar('jchdir');
                $oUri->delVar('status');
                $oUri->delVar('msg');
                $oUri->delVar('dir');
                $oUri->delVar('cnt');
                $oController->setRedirect($oUri->toString());
                $oController->redirect();
        }
        
        abstract protected function getButtons();
}
