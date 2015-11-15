<?php

namespace JchOptimize;

/**
 * Class Minify_CSS
 * @package Minify
 */

/**
 * Compress CSS
 *
 * This is a heavy regex-based removal of whitespace, unnecessary
 * comments and tokens, and some CSS value minimization, where practical.
 * Many steps have been taken to avoid breaking comment-based hacks,
 * including the ie5/mac filter (and its inversion), but expect tricky
 * hacks involving comment tokens in 'content' value strings to break
 * minimization badly. A test suite is available.
 *
 * @package Minify
 * @author Stephen Clay <steve@mrclay.org>
 * @author http://code.google.com/u/1stvamp/ (Issue 64 patch)
 */
class CSS_Optimize
{

        /**
         * Minify a CSS string
         *
         * @param string $css
         *
         * @param array $options (currently ignored)
         *
         * @return string
         */
        public static function process($css, $options = array())
        {
                $obj = new CSS_Optimize($options);
                return $obj->_process($css);
        }

        /**
         * @var array options
         */
        protected $_options = null;

        /**
         * @var bool Are we "in" a hack?
         *
         * I.e. are some browsers targetted until the next comment?
         */
        protected $_inHack = false;

        /**
         * Constructor
         *
         * @param array $options (currently ignored)
         *
         * @return null
         */
        private function __construct($options)
        {
                $this->_options = $options;
        }

        /**
         * Minify a CSS string
         *
         * @param string $css
         *
         * @return string
         */
        protected function _process($css)
        {
                //JCH_DEBUG ? \JchPlatformProfiler::mark('beforeMinifyCss plgSystem (JCH Optimize)') : null;

                // Remove all comments
                $css = preg_replace('#(?>/?[^/]*+)*?\K(?>/\*(?:\*?[^*]*+)*?\*/|$\K)#', '', $css);

                //JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveComments plgSystem (JCH Optimize)') : null;

                // remove ws around , ; : { } 
                $css = preg_replace('#(?>(?:(?<![,;:{}+>~])\s++(?![,;:{}+>~]))?[^\s]*+)*?\K(?:\s++(?=[,;:{}+>~])|(?<=[,;:{}+>~])\s++|$\K)#', '', $css);

                //JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveWsAroundBrackets plgSystem (JCH Optimize)') : null;

                //remove last ; in block
                $css = preg_replace('#(?>(?:;(?!}))?[^;]*+)*?(?:[^;]*+\K;(?=})|$\K)#', '', $css);

                //JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveLastSemicolon plgSystem (JCH Optimize)') : null;

                // remove ws inside urls
                $css = preg_replace('#(?>\(?[^(]*+)*?(?:[^(]*+(?<=\burl)\(\K\s++|\G(?(?=["\'])[\'"][^\'"]++[\'"]|[^\s]++)\K\s++(?=\))|$\K)#',
                                    '', $css);

                //JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveWsAroundUrls plgSystem (JCH Optimize)') : null;

                // minimize hex colors
                $css = preg_replace('/(?>#?[^#]*+)*?(?:(?<!=)#\K([a-f\d])\1([a-f\d])\2([a-f\d])\3|$\K)/i', '$1$2$3', $css);

                //JCH_DEBUG ? \JchPlatformProfiler::mark('afterMinimizeHexColors plgSystem (JCH Optimize)') : null;

                // reduce remaining ws to single space
                $css = preg_replace('#(?>[^\s\'"]*+(?:"(?>(?:\\\\.)?[^\\\\"]*+)+?"|\'(?>(?:\\\\.)?[^\\\\\']*+)+?\')?)*?\K(?:\s++|$)#s', ' ', $css);

                //JCH_DEBUG ? \JchPlatformProfiler::mark('afterReduceWsToSingleSpace plgSystem (JCH Optimize)') : null;
                return trim($css);
        }
}
