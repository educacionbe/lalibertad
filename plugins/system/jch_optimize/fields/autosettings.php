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

        class JFormFieldAutosettings extends JFormFieldCompat
        {

                public $type = 'autosettings';

                protected function getInput()
                {
                        
                }

        }

}
else
{
        include_once dirname(__FILE__) . '/auto.php';

        class JFormFieldAutosettings extends JFormFieldAuto
        {

                protected $type = 'autosettings';

                protected function getButtons()
                {
                        $aButton = array();

                        $aButton[0]['link']   = '';
                        $aButton[0]['icon']   = 'fa-wrench';
                        $aButton[0]['text']   = 'Minimum';
                        $aButton[0]['color']  = '#FFA319';
                        $aButton[0]['script'] = 'onclick="applyAutoSettings(1, 0); return false;"';
                        $aButton[0]['class']  = 'enabled';

                        $aButton[1]['link']   = '';
                        $aButton[1]['icon']   = 'fa-cog';
                        $aButton[1]['text']   = 'Intermediate';
                        $aButton[1]['color']  = '#FF32C7';
                        $aButton[1]['script'] = 'onclick="applyAutoSettings(2, 0); return false;"';
                        $aButton[1]['class']  = 'enabled';

                        $aButton[2]['link']   = '';
                        $aButton[2]['icon']   = 'fa-cogs';
                        $aButton[2]['text']   = 'Average';
                        $aButton[2]['color']  = '#CE3813';
                        $aButton[2]['script'] = 'onclick="applyAutoSettings(3, 0); return false;"';
                        $aButton[2]['class']  = 'enabled';

                        $aButton[3]['link']   = '';
                        $aButton[3]['icon']   = 'fa-forward';
                        $aButton[3]['text']   = 'Deluxe';
                        
                          
                          $aButton[3]['color']  = '#CCC';
                          $aButton[3]['script'] = '';
                          $aButton[3]['class']  = 'disabled';
                          

                        $aButton[4]['link']   = '';
                        $aButton[4]['icon']   = 'fa-fast-forward';
                        $aButton[4]['text']   = 'Premium';
                        
                          
                          $aButton[4]['color']  = '#CCC';
                          $aButton[4]['script'] = '';
                          $aButton[4]['class']  = 'disabled';
                          

                        $aButton[5]['link']   = '';
                        $aButton[5]['icon']   = 'fa-dashboard';
                        $aButton[5]['text']   = 'Optimum';
                        
                         
                          $aButton[5]['color']  = '#CCC';
                          $aButton[5]['script'] = '';
                          $aButton[5]['class']  = 'disabled';
                                                  

                        return $aButton;
                }

        }

}