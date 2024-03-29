<?php
/**
 * @package    Redsource.Admin
 *
 * @copyright  Copyright (C) 2012 - 2013 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

if (!class_exists('RLoader'))
{
	throw new RuntimeException('Please enable redRAD System plugin!');
}

RLoader::registerPrefix('Redsource', __DIR__);
RLoader::registerPrefix('Redsource', JPATH_LIBRARIES . '/redsource');

$app = JFactory::getApplication();

// Check access.
if (!JFactory::getUser()->authorise('core.manage', 'com_redsource'))
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

	return false;
}

// Instanciate and execute the front controller.
$controller = JControllerLegacy::getInstance('Redsource');
$controller->execute($app->input->get('task'));
$controller->redirect();
