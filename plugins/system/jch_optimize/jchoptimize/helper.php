<?php

use JchOptimize\JS_Optimize;

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
defined('_JCH_EXEC') or die('Restricted access');

class JchOptimizeHelperBase
{

        /**
         * 
         */
        public static function cookieLessDomain($params)
        {
                return '';
        }

}

/**
 * Some helper functions
 * 
 */
class JchOptimizeHelper extends JchOptimizeHelperBase
{

        /**
         * Checks if file (can be external) exists
         * 
         * @param type $sPath
         * @return boolean
         */
        public static function fileExists($sPath)
        {
                //JCH_DEBUG ? JchPlatformProfiler::mark('beforeFileExists - ' . $sPath . ' plgSystem (JCH Optimize)') : null;

                if ((strpos($sPath, 'http') === 0))
                {
                        $sFileHeaders = @get_headers($sPath);

                        return ($sFileHeaders !== FALSE && strpos($sFileHeaders[0], '404') === FALSE);
                }
                else
                {
                        return file_exists($sPath);
                }

                //JCH_DEBUG ? JchPlatformProfiler::mark('afterFileExists - ' . $sPath . ' plgSystem (JCH Optimize)') : null;
        }

        /**
         * Get local path of file from the url if internal
         * If external or php file, the url is returned
         *
         * @param string  $sUrl  Url of file
         * @return string       File path
         */
        public static function getFilePath($sUrl)
        {
                //JCH_DEBUG ? JchPlatformProfiler::mark('beforeGetFilePath - ' . $sUrl . ' plgSystem (JCH Optimize)') : null;

                $sUriBase = JchPlatformUri::base();
                $sUriPath = JchPlatformUri::base(TRUE);

                $oUri = clone JchPlatformUri::getInstance();

                $aUrl = parse_url($sUrl);

                if (JchOptimizeHelper::isInternal($sUrl) && preg_match('#\.(?>css|js|png|gif|jpe?g)$#i', $aUrl['path']))
                {
                        $sUrl = preg_replace(
                                array(
                                '#^' . preg_quote($sUriBase, '#') . '#',
                                '#^' . preg_quote($sUriPath, '#') . '/#',
                                '#\?.*?$#'
                                ), '', $sUrl);

                        //JCH_DEBUG ? JchPlatformProfiler::mark('afterGetFilePath - ' . $sUrl . ' plgSystem (JCH Optimize)') : null;

                        return JchPlatformPaths::absolutePath($sUrl);
                }
                else
                {
                        switch (TRUE)
                        {
                                case preg_match('#://#', $sUrl):

                                        break;

                                case (substr($sUrl, 0, 2) == '//'):

                                        $sUrl = $oUri->toString(array('scheme')) . substr($sUrl, 2);
                                        break;

                                case (substr($sUrl, 0, 1) == '/'):

                                        $sUrl = $oUri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . $sUrl;
                                        break;

                                default:

                                        $sUrl = $sUriBase . $sUrl;
                                        break;
                        }


                        //JCH_DEBUG ? JchPlatformProfiler::mark('afterGetFilePath - ' . $sUrl . ' plgSystem (JCH Optimize)') : null;

                        return html_entity_decode($sUrl);
                }
        }

        /**
         * Gets the name of the current Editor
         * 
         * @staticvar string $sEditor
         * @return string
         */
        public static function getEditorName()
        {
                static $sEditor;

                if (!isset($sEditor))
                {
                        $sEditor = JchPlatformUtility::getEditorName();
                }

                return $sEditor;
        }

        /**
         * Determines if file is internal
         * 
         * @param string $sUrl  Url of file
         * @return boolean
         */
        public static function isInternal($sUrl)
        {
                $oUrl = clone JchPlatformUri::getInstance($sUrl);
                //trying to resolve bug in php with parse_url before 5.4.7
                if (preg_match('#^//([^/]+)(/.*)$#i', $oUrl->getPath(), $aMatches))
                {
                        if (!empty($aMatches))
                        {
                                $oUrl->setHost($aMatches[1]);
                                $oUrl->setPath($aMatches[2]);
                        }
                }

                $sUrlBase = $oUrl->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path'));
                $sUrlHost = $oUrl->toString(array('scheme', 'user', 'pass', 'host', 'port'));

                $sBase = JchPlatformUri::base();

                if (stripos($sUrlBase, $sBase) !== 0 && !empty($sUrlHost))
                {
                        return FALSE;
                }

                return TRUE;
        }

        /**
         * 
         * @staticvar string $sContents
         * @return boolean
         */
        public static function checkModRewriteEnabled($params)
        {
                $oFileRetriever = JchOptimizeFileRetriever::getInstance();

                if (!$oFileRetriever->isHttpAdapterAvailable())
                {
                        $params->set('htaccess', '0');
                }
                else
                {
                        $oUri = JchPlatformUri::getInstance();
                        $sUrl = $oUri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . JchPlatformPaths::assetPath() .
                                JchPlatformPaths::rewriteBase() . 'test_mod_rewrite';

                        $sContents = $oFileRetriever->getFileContents($sUrl);

                        if ($sContents == 'TRUE')
                        {
                                $params->set('htaccess', '1');
                        }
                        else
                        {
                                $params->set('htaccess', '0');
                        }
                }


                JchPlatformPlugin::saveSettings($params);
        }

        /**
         * 
         * @param type $aArray
         * @param type $sString
         * @return boolean
         */
        public static function findExcludes($aArray, $sString, $bScript = FALSE)
        {
                foreach ($aArray as $sValue)
                {
                        if ($bScript)
                        {
                                $sString = JS_Optimize::minify($sString);
                        }

                        if ($sValue && strpos($sString, $sValue) !== FALSE)
                        {
                                return TRUE;
                        }
                }

                return FALSE;
        }

        /**
         * 
         * @return type
         */
        public static function getBaseFolder()
        {
                $sJbase = JchPlatformUri::base(true);

                return (($sJbase == '/') ? $sJbase : $sJbase . '/');
        }

        /**
         * 
         * @param string $search
         * @param string $replace
         * @param string $subject
         * @return type
         */
        public static function strReplace($search, $replace, $subject)
        {
                return str_replace(self::cleanPath($search), $replace, self::cleanPath($subject));
        }

        /**
         * 
         * @param type $str
         * @return type
         */
        public static function cleanPath($str)
        {
                return str_replace(array('\\\\', '\\'), '/', $str);
        }

        /**
         * 
         * @staticvar int $cnt
         * @staticvar int $no
         * @param type $arr
         * @param type $count
         * @param type $optimized
         */
        public static function postStatus($arr, $count = FALSE, $optimized = FALSE)
        {
                if (($arr['total']) == 0)
                {
                        return;
                }

                static $cnt = 0;
                static $no  = 0;

                if ($count)
                {
                        $arr['current'] = ++$cnt;
                }

                $arr['optimize'] = $optimized ? ++$no : $no;

                $json = json_encode($arr);

                JchPlatformUtility::write(JCH_PLUGIN_DIR . 'status.json', $json);
        }

        /**
         * 
         * @param type $url
         * @param array $params
         */
        public static function postAsync($url, $params, array $posts)
        {
                foreach ($posts as $key => &$val)
                {
                        if (is_array($val))
                        {
                                $val = implode(',', $val);
                        }

                        $post_params[] = $key . '=' . urlencode($val);
                }

                $post_string = implode('&', $post_params);

                $parts = parse_url($url);

                if(isset($parts['scheme']) && ($parts['scheme'] == 'https')) 
                {
                        $protocol  = 'ssl://';
                        $default_port = 443;
                }
                else
                {
                        $protocol = '';
                        $default_port = 80;
                }

                $fp = fsockopen($protocol . $parts['host'], isset($parts['port']) ? $parts['port'] : $default_port, $errno, $errstr, 1);

                if (!$fp)
                {
                        JchOptimizeLogger::log($errno . ': ' . $errstr, $params);
                }
                else
                {
                        $out = "POST " . $parts['path'] . '?' . $parts['query'] . " HTTP/1.1\r\n";
                        $out.= "Host: " . $parts['host'] . "\r\n";
                        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
                        $out.= "Content-Length: " . strlen($post_string) . "\r\n";
                        $out.= "Connection: Close\r\n\r\n";

                        if (isset($post_string))
                        {
                                $out.= $post_string;
                        }
JchOptimizeLogger::debug($out, 'out');
                        fwrite($fp, $out);
                        fclose($fp);
                }
        }


}
