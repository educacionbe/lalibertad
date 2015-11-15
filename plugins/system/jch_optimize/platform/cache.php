<?php

/**
 * JCH Optimize - Joomla! plugin to aggregate and minify external resources for
 * optmized downloads
 *
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2014 Samuel Marshall
 * @license GNU/GPLv3, See LICENSE file
 *
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
defined('_JEXEC') or die('Restricted access');

class JchPlatformCache implements JchInterfaceCache
{

        private static $callbackCache = null;
        private static $outputCache   = null;

        /**
         * 
         * @param type $id
         * @param type $lifetime
         * @return type
         */
        public static function getCache($id, $lifetime)
        {
                $oCache = static::_getCacheObject('output', $lifetime);
                $aCache = $oCache->get($id);
                
                if($aCache === FALSE)
                {
                        return FALSE;
                }
                
                return $aCache['result'];
        }


        /**
         * 
         * @param type $id
         * @param type $lifetime
         * @param type $function
         * @param type $args
         * @return type
         */
        public static function getCallbackCache($id, $lifetime, $function, $args)
        {
                $oCache = static::_getCacheObject('callback', $lifetime);

                return $oCache->get($function, $args, $id);
        }

        /**
         * 
         * @param type $type
         * @return type
         */
        private static function _getCacheObject($type, $lifetime)
        {
                if (static::${$type . 'Cache'} !== null)
                {
                        return static::${$type . 'Cache'};
                }


                $aOptions = array(
                        'defaultgroup' => 'plg_jch_optimize',
                        'checkTime'    => TRUE,
                        'caching'      => TRUE,
                        'application'  => 'site',
                        'language'     => 'en-GB',
                        'lifetime'     => $lifetime
                );

                static::${$type . 'Cache'} = JCache::getInstance($type, $aOptions);

                return static::${$type . 'Cache'};
        }

}
