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

if (version_compare(PHP_VERSION, '5.3.0', '<'))
{
        require_once dirname(__FILE__) . '/compat.php';

        class JFormFieldAutoorder extends JFormFieldCompat
        {
                public $type = 'autoorder';
                
                protected function getInput()
                {
                        
                }

        }

}
else
{
        include_once dirname(__FILE__) . '/auto.php';

        class JFormFieldAutoorder extends JFormFieldAuto
        {

                protected $type = 'autoorder';

                protected function getButtons()
                {
                        if (JFactory::getApplication()->input->get('jchtask') == 'orderplugins')
                        {
                                $this->orderPlugins();
                        }
                        elseif (JFactory::getApplication()->input->get('jchtask') == 'cleancache')
                        {
                                $this->cleanCache();
                        }

                        $aButton              = array();
                        $aButton[0]['link']   = JURI::getInstance()->toString() . '&amp;jchtask=orderplugins';
                        $aButton[0]['icon']   = 'fa-sort-numeric-asc';
                        $aButton[0]['color']  = '#278EB1';
                        $aButton[0]['text']   = 'Order Plugin';
                        $aButton[0]['script'] = '';
                        $aButton[0]['class']  = 'enabled';

                        $aButton[1]['link']   = JURI::getInstance()->toString() . '&amp;jchtask=cleancache';
                        $aButton[1]['icon']   = 'fa-times-circle';
                        $aButton[1]['color']  = '#C0110A';
                        $aButton[1]['text']   = 'Clean Cache';
                        $aButton[1]['script'] = '';
                        $aButton[1]['class']  = 'enabled';

                        return $aButton;
                }

        }

}