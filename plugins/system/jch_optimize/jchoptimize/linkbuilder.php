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
defined('_JCH_EXEC') or die('Restricted access');

class JchOptimizeLinkBuilderBase
{

        /**
         * 
         * @return string
         */
        protected function getAsyncAttribute()
        {
                return '';
        }

}

/**
 * 
 * 
 */
class JchOptimizeLinkBuilder extends JchOptimizelinkBuilderBase
{

        /** @var JchOptimizeParser Object       Parser object */
        public $oParser;

        /** @var string         Document line end */
        protected $sLnEnd;

        /** @var string         Document tab */
        protected $sTab;

        /** @var string cache id * */
        protected $params;

        /**
         * Constructor
         * 
         * @param JchOptimizeParser Object  $oParser
         */
        public function __construct($oParser = null)
        {
                $this->oParser = $oParser;
                $this->params  = $this->oParser->params;
                $this->sLnEnd  = $this->oParser->sLnEnd;
                $this->sTab    = $this->oParser->sTab;
        }

        /**
         * Prepare links for the combined files and insert them in the processed HTML
         * 
         */
        public function insertJchLinks()
        {
                $aLinks = $this->oParser->getReplacedFiles();
                $params = $this->params;

                if ($this->params->get('htaccess', 2) == 2)
                {
                        JchOptimizeHelper::checkModRewriteEnabled($this->params);
                }

                if ($params->get('javascript', 1))
                {
                        $sLink = $this->params->get('bottom_js', '0') == '2' ? '</title>' . $this->sLnEnd . $this->sTab : '';
                        $sLink .= '<script type="text/javascript" src="URL"';
                        $sLink .= $params->get('defer_js', 0) ?
                                ($this->isXhtml() ? ' defer="defer"' : ' defer' ) :
                                '';
                        $sLink .= $this->getAsyncAttribute();
                        $sLink .= '></script>';

                        $sNewJsLink = ($params->get('bottom_js') == '1') ? $this->sTab . $sLink . $this->sLnEnd . '</body>' : $sLink;

                        if (!empty($aLinks['js']))
                        {
                                $this->processLink('js', $sNewJsLink);
                        }
                }

                if ($this->oParser->enableCssCompression())
                {
                        $sNewCssLink = $this->params->get('bottom_js', '0') ? '</title>' . $this->sLnEnd . $this->sTab : '';
                        $sNewCssLink .= '<link rel="stylesheet" type="text/css" ';
                        $sNewCssLink .= 'href="URL"/>';

                        if (!empty($aLinks['css']))
                        {
                                $this->processLink('css', $sNewCssLink);
                        }
                }
        }

        /**
         * Use generated id to cache aggregated file
         *
         * @param string $sType           css or js
         * @param string $sLink           Url for aggregated file
         */
        protected function processLink($sType, $sLink)
        {
                //JCH_DEBUG ? JchPlatformProfiler::mark('beforeProcessLink plgSystem (JCH Optimize)') : null;

                $aLinks = $this->oParser->getReplacedFiles();
                $sId    = $this->getCacheId($aLinks[$sType], $sType);
                $aArgs  = array($aLinks[$sType], $sType, $this->oParser);

                $oCombiner = new JchOptimizeCombiner($this->oParser);
                $aFunction = array(&$oCombiner, 'getContents');

                $bCached = $this->loadCache($aFunction, $aArgs, $sId);

                if ($bCached === FALSE)
                {
                        throw new Exception(JchPlatformUtility::translate('Error creating cache file'));
                }

                $iTime = (int) $this->params->get('lifetime', '30');
                $sUrl  = $this->buildUrl($sId, $sType, $iTime, $this->isGZ());


                if ($sType == 'css' && $this->params->get('pro_optimizeCssDelivery', '0'))
                {
                        $sCriticalCss = '<style type="text/css">' . $this->sLnEnd .
                                $bCached['criticalcss'] . $this->sLnEnd .
                                '</style>' . $this->sLnEnd .
                                '</head>';

                        $sHeadHtml = str_replace('</head>', $sCriticalCss, $this->oParser->getHeadHtml());
                        $this->oParser->setSearchArea($sHeadHtml, 'head');

                        $sUrl = str_replace('JCHI', '0', $sUrl);

                        $this->loadCssAsync($sUrl);
                }
                else
                {
                        $sNewLink = str_replace('URL', $sUrl, $sLink);
                        $this->replaceLink($sNewLink, $sType);
                }

                //JCH_DEBUG ? JchPlatformProfiler::mark('afterProcessLink plgSystem (JCH Optimize)') : null;
        }

        /**
         * 
         * @param type $aUrlArrays
         * @return type
         */
        private function getCacheId($aUrlArrays, $sType)
        {
                $aIdArray = array();

                foreach ($aUrlArrays as $aUrlArray)
                {
                        foreach ($aUrlArray as $aUrl)
                        {
                                $aIdArray[] = $aUrl['id'];
                        }
                }

                $sHtmlHash = ($sType == 'css') ? $this->oParser->getHtmlHash() : '';

                return md5(serialize($aIdArray) . $sHtmlHash);
        }

        /**
         * Returns url of aggregated file
         *
         * @param string $sFile		Aggregated file name
         * @param string $sType		css or js
         * @param mixed $bGz		True (or 1) if gzip set and enabled
         * @param number $sTime		Expire header time
         * @return string			Url of aggregated file
         */
        protected function buildUrl($sId, $sType, $iTime, $bGz = FALSE)
        {
                $sPath = JchPlatformPaths::assetPath();

                if ($this->params->get('htaccess', 0) == 1)
                {
                        $sUrl = JchOptimizeHelper::cookieLessDomain($this->params) . $sPath . JchPlatformPaths::rewriteBase()
                                . ($bGz ? 'gz/' : 'nz/') . $iTime . '/JCHI/' . $sId . '.' . $sType;
                }
                else
                {
                        $oUri = clone JchPlatformUri::getInstance();

                        $oUri->setPath($sPath . '2/jscss.php');

                        $aVar         = array();
                        $aVar['f']    = $sId;
                        $aVar['type'] = $sType;
                        $aVar['gz']   = $bGz ? 'gz' : 'nz';
                        $aVar['d']    = $iTime;
                        $aVar['i']    = 'JCHI';

                        $oUri->setQuery($aVar);

                        $sUrl = htmlentities($oUri->toString(array('path', 'query')));
                }

                return ($sUrl);
        }

        /**
         * Insert url of aggregated file in html
         *
         * @param string $sNewLink   Url of aggregated file
         */
        protected function replaceLink($sNewLink, $sType)
        {
                $sSearchArea = $this->oParser->getHeadHtml();
                $sSearchArea .= $this->oParser->getBodyHtml();


                if ($sType == 'js' && $this->params->get('bottom_js', '0') == '1')
                {
                        $sNewLink             = str_replace('JCHI', '0', $sNewLink);
                        $sSearchArea          = str_replace('</body>', $sNewLink, $sSearchArea);
                        $this->oParser->sHtml = str_replace('</body>', $sNewLink, $this->oParser->sHtml);
                }
                elseif ($this->params->get('bottom_js', '0'))
                {
                        $sNewLink    = str_replace('JCHI', '0', $sNewLink);
                        $sSearchArea = str_replace('</title>', $sNewLink, $sSearchArea);
                }
                else
                {
                        $sSearchArea = preg_replace_callback('#<JCH_' . strtoupper($sType) . '([^>]++)>#',
                                                                                   function($aM) use ($sNewLink)
                        {
                                return str_replace('JCHI', $aM[1], $sNewLink);
                        }, $sSearchArea);
                }

                if (!$this->params->get('pro_searchBody', '0') && !$this->params->get('pro_cookielessdomain', ''))
                {
                        $this->oParser->setSearchArea($sSearchArea, 'head');
                }
                else
                {
                        $this->oParser->setSearchArea(preg_replace($this->oParser->getBodyRegex(), '', $sSearchArea), 'head');
                        $this->oParser->setSearchArea(preg_replace($this->oParser->getHeadRegex(), '', $sSearchArea), 'body');
                }
        }

        /**
         * Create and cache aggregated file if it doesn't exists, file will have
         * lifetime set in global configurations.
         *
         * @param array $aFunction    Name of function used to aggregate files
         * @param array $aArgs        Arguments used by function above
         * @param string $sId         Generated id to identify cached file
         * @return boolean           True on success
         */
        public function loadCache($aFunction, $aArgs, $sId)
        {
                JCH_DEBUG ? JchPlatformProfiler::mark('beforeLoadCache plgSystem (JCH Optimize)') : null;

                $iLifeTime = (int) $this->params->get('lifetime', '30') * 24 * 60 * 60;
                $bCached   = JchPlatformCache::getCallbackCache($sId, $iLifeTime, $aFunction, $aArgs);

                JCH_DEBUG ? JchPlatformProfiler::mark('afterLoadCache plgSystem (JCH Optimize)') : null;

                return $bCached;
        }

        /**
         * Check if gzip is set or enabled
         *
         * @return boolean   True if gzip parameter set and server is enabled
         */
        public function isGZ()
        {
                return ($this->params->get('gzip', 0) && extension_loaded('zlib') && !ini_get('zlib.output_compression')
                        && (ini_get('output_handler') != 'ob_gzhandler'));
        }

        /**
         * Determine if document is of XHTML doctype
         * 
         * @return boolean
         */
        protected function isXhtml()
        {
                if (preg_match('#^<!DOCTYPE(?=[^>]+XHTML)#i', trim($this->oParser->sHtml)) === 1)
                {
                        return true;
                }
                else
                {
                        return false;
                }
        }

        
}
