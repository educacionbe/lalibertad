<?php

namespace JchOptimize;

/**
 * Class HTML_Optimize
 */

/**
 * Compress HTML
 *
 * This is a heavy regex-based removal of whitespace, unnecessary comments and
 * tokens. IE conditional comments are preserved. There are also options to have
 * STYLE and SCRIPT blocks compressed by callback functions.
 *
 * This  class was modified from the original class Minify_HTML that was
 * written by Stephen Clay <steve@mrclay.org>
 *
 * @author Samuel Marshall<sdmarshall73@gmail.com>
 */
class HTML_Optimize
{

        /**
         * "Minify" an HTML page
         *
         * @param string $html
         *
         * @param array $options
         *
         * 'cssMinifier' : (optional) callback function to process content of STYLE
         * elements.
         *
         * 'jsMinifier' : (optional) callback function to process content of SCRIPT
         * elements. Note: the type attribute is ignored.
         *
         * 'xhtml' : (optional boolean) should content be treated as XHTML1.0? If
         * unset, minify will sniff for an XHTML doctype.
         *
         * @return string
         */
        public static function minify($html, $options = array())
        {
                $min = new HTML_Optimize($html, $options);
                return $min->process();
        }

        /**
         * Create a minifier object
         *
         * @param string $html
         *
         * @param array $options
         *
         * 'cssMinifier' : (optional) callback function to process content of STYLE
         * elements.
         *
         * 'jsMinifier' : (optional) callback function to process content of SCRIPT
         * elements. Note: the type attribute is ignored.
         *
         * 'xhtml' : (optional boolean) should content be treated as XHTML1.0? If
         * unset, minify will sniff for an XHTML doctype.
         *
         * @return null
         */
        public function __construct($html, $options = array())
        {
                $this->_html = $html;

                if (isset($options['xhtml']))
                {
                        $this->_isXhtml = (bool) $options['xhtml'];
                }
                if (isset($options['html5']))
                {
                        $this->_isHtml5 = (bool) $options['html5'];
                }
                if (isset($options['cssMinifier']))
                {
                        $this->_cssMinifier = $options['cssMinifier'];
                }
                if (isset($options['jsMinifier']))
                {
                        $this->_jsMinifier = $options['jsMinifier'];
                }
        }

        /**
         * Minify the markeup given in the constructor
         *
         * @return string
         */
        public function process()
        {
                JCH_DEBUG ? \JchPlatformProfiler::mark('beforeProcess plgSystem (JCH Optimize)') : null;

                if ($this->_isXhtml === null)
                {
                        $this->_isXhtml = (preg_match('#^\s*+<!DOCTYPE[^X]++XHTML#i', $this->_html));
                }

                if ($this->_isHtml5 === null)
                {
                        $this->_isHtml5 = (preg_match('#^\s*+<!DOCTYPE html>#i', $this->_html));
                }

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterTestHtml plgSystem (JCH Optimize)') : null;
                //Replace comments (not containing IE conditional comments)
                $this->_html = preg_replace(
                        '#(?>(?:<(?!!))?[^<]*+(?:<(?:script|style)\b[^>]*+>(?><?[^<]*+)*?<\/(?:script|style)>|<!--\[(?>\]?[^\]]*+)*?\]--!?>|'
                        . '<!DOCTYPE[^>]++>)?)*?\K(?:<!--(?>-?[^-\[]*+)*?--!?>|[^<]*+\K$)#i', '', $this->_html);

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterReplaceComments plgSystem (JCH Optimize)') : null;
                //Reduce whitespace outside all elements to one space
                $this->_html = preg_replace('#(?>>?[^>]*+>(?>[^>]*+\S[^>]*+>)*?)*?\K(?:(?<=>)\s++(?=<)|[^<]*+$\K)#', ' ', $this->_html); 

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterReduceWs plgSystem (JCH Optimize)') : null;
                //block/undisplayed elements
                $b = 'address|article|aside|audio|body|blockquote|canvas|dd|div|dl'
                        . '|fieldset|figcaption|figure|footer|form|h[1-6]|head|header|hgroup|html|noscript|ol|output|p'
                        . '|pre|section|style|table|title|tfoot|ul|video';

                //self closing block/undisplayed elements
                $b2 = 'base|meta|link|hr';

                //inline elements
                $i = 'b|big|i|small|tt'
                        . '|abbr|acronym|cite|code|dfn|em|kbd|strong|samp|var'
                        . '|a|bdo|br|map|object|q|script|span|sub|sup'
                        . '|button|label|select|textarea';
                
                //self closing inline elements
                $i2 = 'img|input';

                // remove ws around block elements preserving space around inline elements
               $this->_html = preg_replace(
                        "#(?>\s*+(?:<(?:(?>$i)\b[^>]*+>|(?:/(?>$i)\b>|(?>$i2)\b[^>]*+>)\s*+)|<[^>]*+>)|[^<]++)*?\K"
                        . "(?:\s++(?=<(?>$b|$b2)\b)|(?:</(?>$b)\b>|<(?>$b2)\b[^>]*+>)\K\s++(?!<(?>$i|$i2)\b)|$)#i",
                        '', $this->_html 
                ); 

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveWsAroundBlocks plgSystem (JCH Optimize)') : null;
                //Minify scripts

               $this->_html = preg_replace_callback(
                        '#(?><?[^<]*+)*?\K(?:(<(script|style)\b[^>]*+>)((?>(?:<(?!/\g{2}))?[^<]++)+?)(<\/\g{2}>)|$)#i', array($this, '_minifyCB'),
                        $this->_html
                ); 

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterMinifyScripts plgSystem (JCH Optimize)') : null;
                //elements to escape
                $e = 'pre|script|style|textarea';
                
                //Regex for escape elements
                $p  = "<pre\b[^>]*+>(?><?[^<]*+)*?</pre>";
                $sc = "<script\b[^>]*+>(?><?[^<]*+)*?</script>";
                $st = "<style\b[^>]*+>(?><?[^<]*+)*?</style>";
                $t  = "<textarea\b[^>]*+>(?><?[^<]*+)*?</textarea>";

                //Replace runs of whitespace inside elements with single space escaping pre, textarea, scripts and style elements
                $this->_html = preg_replace(
                        "#(?>[^<]*+(?:$p|$sc|$st|$t|<[^>]++>[^<]*+))*?(?:(?:<(?!$e)[^>]*+>)?(?>\s?[^\s<]*+)*?\K\s{2,}|\K$)#i", ' ', $this->_html
                );

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterReplaceRunsOfWs plgSystem (JCH Optimize)') : null;
                //Remove additional ws around attributes
              $this->_html = preg_replace(
                        '#(?><?[^<]*+)*?(?:<[a-z0-9]++\K\s++|\G[^\>=]++=(?(?=\s*+["\'])\s*+["\'][^"\']*+["\']|[^\s]++)\K\s++|$\K)#i', ' ',
                        $this->_html
                ); 

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveWsAroundAttributes plgSystem (JCH Optimize)') : null;

                if ($this->_isHtml5)
                {
                        $ns1 = '"[^"\'`=<>\s]*+(?:[\'`=<>\s]|(?<=\\\\)")(?>(?:(?<=\\\\)")?[^"]*+)*?(?<!\\\\)"';
                        $ns2 = "'[^'\"`=<>\s]*+(?:[\"`=<>\s]|(?<=\\\\)')(?>(?:(?<=\\\\)')?[^']*+)*?(?<!\\\\)'";

                        //Remove quotes from selected attributes
                        $this->_html = preg_replace(
                                "#(?:(?=[^>]*+>)|<[a-z0-9]++ )"
                                . "(?>[=]?[^=>]*+(?:=(?:$ns1|$ns2)|>(?><?[^<]*+)*?(?:<[a-z0-9]++ |$))?)*?"
                                . "(?:=\K([\"'])([^\"'`=<>\s]++)\g{1}[ ]?|\K$)#i", '$2 ', $this->_html
                        );
                }  

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveQuotes plgSystem (JCH Optimize)') : null;
                //remove redundant attributes
                $this->_html = preg_replace(
                        '#(?><?[^<]*+)*?<(?:(?:script|style|link)|/html>)(?>[ ]?[^ >]*+)*?\K(?: type=["\']?text/(?:javascript|css)|[^<]*+\K$)#i', '',
                        $this->_html
                ); 

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveAttributes plgSystem (JCH Optimize)') : null;
                //Remove last whitespace in open tag
                $this->_html = preg_replace(
                        '#(?><?[^<]*+)*?(?:<[a-z0-9]++(?>\s*+[^\s>]++)*?\K(?:\s++(?=>)|(?<=["\'])\s++(?=/>))|$\K)#i', '', $this->_html
                );  

//                JCH_DEBUG ? \JchPlatformProfiler::mark('afterRemoveLastWs plgSystem (JCH Optimize)') : null;

                return trim($this->_html);
        }

        protected $_isXhtml         = null;
        protected $_isHtml5         = null;
        protected $_replacementHash = null;
        protected $_placeholders    = array();
        protected $_cssMinifier     = null;
        protected $_jsMinifier      = null;
        protected $_html            = '';

        protected function _minifyCB($m)
        {
                if ($m[0] == '')
                {
                        return $m[0];
                }

                $openTag  = $m[1];
                $content  = $m[3];
                $closeTag = $m[4];

                $type = strcasecmp($m[2], 'script') == 0 ? 'js' : 'css';

                if ($this->{'_' . $type . 'Minifier'})
                {
                        // remove HTML comments (and preceding "//" if present) and CDATA
                        $content = self::cleanScript($content);

                        // minify
                        $content = $this->_callMinifier($this->{'_' . $type . 'Minifier'}, $content);


                        return $this->_needsCdata($content) ? "{$openTag}/*<![CDATA[*/{$content}/*]]>*/{$closeTag}" : "{$openTag}{$content}{$closeTag}";
                }
                else
                {
                        return $m[0];
                }
        }

        protected function _needsCdata($str)
        {
                return ($this->_isXhtml && preg_match('#(?:[<&]|\-\-|\]\]>)#', $str));
        }

        protected function _callMinifier($aFunc, $content)
        {
                $class  = $aFunc[0];
                $method = $aFunc[1];

                return $class::$method($content);
        }

        public static function cleanScript($content)
        {
                return preg_replace(
                        '#(?://\s*+)?<!(?:--(?>[<>]?[^<>\n]*+)*?(?:(?=</script)|\n|(?:(?<=--)|(?<=--!))>)'
                        . '|\[CDATA\[)?|(?://\s*+)?(?:--!?|\]\](?:--!?)?)>#i', '', $content
                );
        }

}
