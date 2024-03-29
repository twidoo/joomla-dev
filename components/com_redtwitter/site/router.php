<?php
/**
 * @version    1.0.0
 * @package    Com_Redtwitter
 * @author     Ronni K. G. Christiansen<email@redweb.dk> - http://www.redcomponent.com
 * @copyright  Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * Developed by email@recomponent.com - redCOMPONENT.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * @param   array  $query  A named array
 *
 * @return  array
 */
function redtwitterBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['task']))
	{
		$segments[] = $query['task'];
		unset($query['task']);
	}

	if (isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	}

	return $segments;
}

/**
 * @param    array    A named array
 * @param    array
 *
 * Formats:
 *
 * index.php?/redtwitter/task/id/Itemid
 *
 * index.php?/redtwitter/id/Itemid
 */
function redtwitterParseRoute($segments)
{
	$vars = array();

	// View is always the first element of the array
	$count = count($segments);

	if ($count)
	{
		$count--;
		$segment = array_shift($segments);

		if (is_numeric($segment))
		{
			$vars['id'] = $segment;
		}
		else
		{
			$vars['task'] = $segment;
		}
	}

	if ($count)
	{
		$count--;
		$segment = array_shift($segments);

		if (is_numeric($segment))
		{
			$vars['id'] = $segment;
		}
	}

	return $vars;
}