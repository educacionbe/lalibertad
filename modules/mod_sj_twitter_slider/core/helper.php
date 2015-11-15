<?php
/**
 * @package SJ Twitter Slider
 * @version 1.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die ();

abstract class SjTwitterSliderHelper
{
	public static function getList(&$params)
	{
		$list = array();
		if (!class_exists('TwitterOAuth')) {
			require_once dirname(__FILE__) . '/twitteroauth.php';
		}
		$consumerKey = trim($params->get('consumekey'));
		$consumerSecret = trim($params->get('consumersecret'));
		$oAuthToken = trim($params->get('access_token'));
		$oAuthSecret = trim($params->get('access_token_secret'));
		$screenName = $params->get('screenname');
		$notweets = $params->get('count', 6);
		if (!$consumerKey || !$consumerSecret || !$screenName) return;
		$_time = time();
		$Tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);
		$tweets = $Tweet->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $screenName . "&count=" . $notweets);
		$items = json_decode($tweets);
		if (empty($items) || isset($items->errors)) return;

		foreach ($items as $item) {
			$text = preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a target="_blank" href="$1">$1</a>', $item->text);
			$text = preg_replace('/@(\w+)/', '<a target="_blank" href="http://twitter.com/$1">@$1</a>', $text);
			$text = preg_replace('/(?:^|\s)+#(\w+)/', ' <a target="_blank" href="http://search.twitter.com/search?q=%23$1">#$1</a>', $text);
			$item->_text = $text;
			$item->_full_name = $item->user->name;
			$item->_username = '@' . $item->user->screen_name;
			$item->_twitter_link = 'http://www.twitter.com/' . $item->user->screen_name;
			$item->_image = $item->user->profile_image_url;

			$list[] = $item;
		}
		return $list;
	}
}


