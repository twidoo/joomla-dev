<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ngoc Nha
 * Date: 4/4/13
 * Time: 11:03 PM
 * To change this template use File | Settings | File Templates.
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('legacy.view.legacy');

class HelloWorldViewHelloWorld extends JViewLegacy
{
    protected $item;

	// Overwriting JView display method
	function display($tpl = null)
	{
		// Assign data to the view
		//$this->msg = $this->get('Msg');

		// Assign data to the view
		$this->item = $this->get('Item');

		if(count($errors = $this->get('Errors')))
		{
			JLog::add(implode('<br/>', $errors), JLog::WARNING, 'jerror');
			return false;
		}

		// Display the view
		parent::display($tpl);
	}
}

?>