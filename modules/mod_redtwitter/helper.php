<?php
/**
 * @version    Id: helper.php
 * @package    Com_Redtwitter
 * @author     Ronni K. G. Christiansen<email@redweb.dk> - http://www.redcomponent.com
 * @copyright  Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *             Developed by email@recomponent.com - redCOMPONENT.com
 */
defined('_JEXEC') or die('Restricted access');

JLoader::register('RedtwitterHelper', JPATH_SITE . '/components/com_redtwitter/helpers/redtwitter.php');

/**
 * Class modRedTwitterHelper
 */
abstract class modRedTwitterHelper
{
	/**
	 * @param array $twitterId
	 * @param $orderType
	 * @param $maxItemDisplayed
	 * @return array
	 */
	public static function getTwitterList($twitterId = array(), $orderType = 0, $maxItemDisplayed = 10, $params = array())
	{
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_redtwitter/models', 'redtwitterModelfollowedprofiles');
		$model = JModelLegacy::getInstance('followedprofiles', 'redtwitterModel', array('ignore_request' => true));

		$twitterUserList =& $model->getData($twitterId);

		$twitterDataTimelines = RedtwitterHelper::getAllUserTimeline($twitterUserList, $orderType, $maxItemDisplayed, $params);
		return $twitterDataTimelines;
	}

	/**
	 * @param $time
	 * @return string
	 */
	public static function ago($time)
	{
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("1", "60", "60", "24", "7", "4.35", "12", "10");

		$now = time();

		$difference = $now - strtotime($time);

		for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
		{
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if ($difference != 1)
		{
			$periods[$j] = JText::_('MOD_REDTWITTER_TWITTER_PLURAL_PERIOD' . $j);
		}
		else
		{
			$periods[$j] = JText::_('MOD_REDTWITTER_TWITTER_PERIOD' . $j);
		}

		return "$difference $periods[$j]";
	}
}