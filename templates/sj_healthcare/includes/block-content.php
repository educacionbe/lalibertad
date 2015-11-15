<?php
/*
 * ------------------------------------------------------------------------
 * Yt FrameWork for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2012 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
if ($position['group'] == '') { // Position none group
	echo $yt->renPositionsContentNoGroup($position);	
	
} elseif ( ($position['group'] != 'left') && ($position['group'] != 'main') && ($position['group'] != 'right') ) { 	// Position has group's user created	
	if (!isset($countGSpe)) {
		$countGSpe = 0;
	}
	$countGSpe ++;
	
		
	if($countGSpe == 1) {
		$more_attr = '';
		$more_attr .= (isset($yt_render->arr_GI['maintop']['data-wide']))?' data-wide="'.$yt_render->arr_GI['maintop']['data-wide'].'"':'';
		$more_attr .= (isset($yt_render->arr_GI['maintop']['data-normal']))?' data-normal="'.$yt_render->arr_GI['maintop']['data-normal'].'"':'';
		$more_attr .= (isset($yt_render->arr_GI['maintop']['data-tablet']))?' data-tablet="'.$yt_render->arr_GI['maintop']['data-tablet'].'"':'';
		$more_attr .= (isset($yt_render->arr_GI['maintop']['data-stablet']))?' data-stablet="'.$yt_render->arr_GI['maintop']['data-stablet'].'"':'';
		$more_attr .= (isset($yt_render->arr_GI['maintop']['data-mobile']))?' data-mobile="'.$yt_render->arr_GI['maintop']['data-mobile'].'"':'';	     
		
		echo '<div id="' . $position['group'] . '" class="'.$yt_render->arr_GI['maintop']['class'].'"'.$more_attr.'>';	
		echo $yt->renPositionsGroup($position);	  
		
		if($tagBD['count-'.$position['group']] == 1) {
			$countGSpe = null;
			echo '</div>';	
		}
	} elseif ( $countGSpe == $tagBD['count-'.$position['group']] && $tagBD['count-'.$position['group']] > 1 ) {
	
		echo $yt->renPositionsGroup($position);	  
		
		$countGSpe = null;
		echo '</div>';	
	} else {
		echo $yt->renPositionsGroup($position);	  
	}
	
} elseif ( ($position['group'] == 'left')
	   ||($position['group'] == 'main')
	   ||($position['group'] == 'right') ) { // Position has group's framework fixed	- left, main, right
	   
	if($position['group'] == 'left') {
		$countL ++;
		if($countL == 1) {
			$more_attr = '';
			$more_attr .= (isset($yt_render->arr_GI['left']['data-wide']))?' data-wide="'.$yt_render->arr_GI['left']['data-wide'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['left']['data-normal']))?' data-normal="'.$yt_render->arr_GI['left']['data-normal'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['left']['data-tablet']))?' data-tablet="'.$yt_render->arr_GI['left']['data-tablet'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['left']['data-stablet']))?' data-stablet="'.$yt_render->arr_GI['left']['data-stablet'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['left']['data-mobile']))?' data-mobile="'.$yt_render->arr_GI['left']['data-mobile'].'"':'';	     
			           	
			echo '<aside id="content_left" class="'.$yt_render->arr_GI['left']['class'].'"'.$more_attr.'>';	
			echo $yt->renPositionsGroup($position, 'block-content');
			if($tagBD['count-group-left'] == 1) {
				echo '</aside>';
			}
		} elseif ($tagBD['count-group-left'] == $countL && $tagBD['count-group-left'] > 1) {
			echo $yt->renPositionsGroup($position, 'block-content');
			echo '</aside>';			
		} else {
			echo $yt->renPositionsGroup($position, 'block-content');
		}			
	} elseif ($position['group'] == 'main') {
		$countM++;
	
		if ($countM == 1) {
			$more_attr = '';
			$more_attr .= (isset($yt_render->arr_GI['main']['data-wide']))?' data-wide="'.$yt_render->arr_GI['main']['data-wide'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['main']['data-normal']))?' data-normal="'.$yt_render->arr_GI['main']['data-normal'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['main']['data-tablet']))?' data-tablet="'.$yt_render->arr_GI['main']['data-tablet'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['main']['data-stablet']))?' data-stablet="'.$yt_render->arr_GI['main']['data-stablet'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['main']['data-mobile']))?' data-mobile="'.$yt_render->arr_GI['main']['data-mobile'].'"':'';       	
			echo '<div id="content_main" class="'.$yt_render->arr_GI['main']['class'].'"'.$more_attr.'>' ;
			echo '<div class="content-main-inner ">';			
			echo $yt->renPositionsGroup($position, 'main');
			
			if($tagBD['count-group-main'] == 1 ) {
				echo '	</div>';
				echo '</div>';		
			}
		} elseif ( ($tagBD['count-group-main'] == $countM) && ($tagBD['count-group-main'] > 1) ){ 
			echo $yt->renPositionsGroup($position, 'main');		
			echo ' </div>';
			echo '</div>';		
		} else {
			echo $yt->renPositionsGroup($position, 'main');
		}
	} elseif ($position['group'] == 'right') {
		$countR ++;
		if($countR == 1) {		       
			$more_attr = '';
			$more_attr .= (isset($yt_render->arr_GI['right']['data-wide']))?' data-wide="'.$yt_render->arr_GI['right']['data-wide'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['right']['data-normal']))?' data-normal="'.$yt_render->arr_GI['right']['data-normal'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['right']['data-tablet']))?' data-tablet="'.$yt_render->arr_GI['right']['data-tablet'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['right']['data-stablet']))?' data-stablet="'.$yt_render->arr_GI['right']['data-stablet'].'"':'';
			$more_attr .= (isset($yt_render->arr_GI['right']['data-mobile']))?' data-mobile="'.$yt_render->arr_GI['right']['data-mobile'].'"':'';       	
			echo '<aside id="content_right" class="'.$yt_render->arr_GI['right']['class'].'"'.$more_attr.'>';
			echo $yt->renPositionsGroup($position, 'block-content');
			if($tagBD['count-group-right'] == 1) {
				echo '</aside>';			
			}
		} elseif ($countR == $tagBD['count-group-right'] && $tagBD['count-group-right'] > 1) {
			echo $yt->renPositionsGroup($position, 'block-content');
			echo '</aside>';
		} else {
			echo $yt->renPositionsGroup($position, 'block-content');
		}				
	}
}		
?> 