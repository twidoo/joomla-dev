<?php
/**
 * @copyright Copyright (C) 2012 - 2013 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die('Restricted access');

define('FACEBOOK', 1);
define('TWITTER', 2);
define('LINKEDIN', 3);
define('YOUTUBE', 4);
define('DEFAULT_PRODUCT_ORDERING_METHOD', 1);

class JFormFieldprofiletypeid extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'profiletypeid';

	public function getInput()
	{
		$order_data = array(new stdClass, new stdClass, new stdClass, new stdClass);
		$order_data[0]->value = FACEBOOK;
		$order_data[0]->text = JText::_('COM_REDSOCIALSTREAM_FACEBOOK');
		$order_data[1]->value = TWITTER;
		$order_data[1]->text = JText::_('COM_REDSOCIALSTREAM_TWITTER');
		$order_data[2]->value = YOUTUBE;
		$order_data[2]->text = JText::_('COM_REDSOCIALSTREAM_YOUTUBE');
		$order_data[3]->value = LINKEDIN;
		$order_data[3]->text = JText::_('COM_REDSOCIALSTREAM_LINKEDIN');


		$name = $this->name;
		$value = $this->value;
		if (!$value)
		{
			$value = DEFAULT_PRODUCT_ORDERING_METHOD;
		}

		$order_select = JHTML::_('select.genericlist', $order_data, $name, 'class="inputbox"', 'value', 'text', $value, $name);

		return $order_select;
	}
}

