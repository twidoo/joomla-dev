<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking extension for Joomla
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

defined('_JEXEC') or die;

/**
 * Taxes view class
 *
 * @package     Solidres
 * @subpackage	Tax
 * @since		0.1.0
 */
class SolidresViewTaxes extends JViewLegacy
{
	protected $state;
	protected $items;
	protected $pagination;
	
    function display($tpl = null)
	{
    	$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		if (count($errors = $this->get('Errors')))
        {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		JHtml::stylesheet('com_solidres/assets/main.css', false, true, false);

		$this->addToolbar();
		
		parent::display($tpl);
        
    }

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= SolidresHelper::getActions();

		JToolBarHelper::title(JText::_('SR_MANAGE_TAXES'), 'generic.png');
		if ($canDo->get('core.create'))
        {
			JToolBarHelper::addNew('tax.add','JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit'))
        {
			JToolBarHelper::editList('tax.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state'))
        {
			if ($state->get('filter.state') != 2)
            {
				JToolBarHelper::divider();
				JToolBarHelper::custom('taxes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('taxes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			if ($state->get('filter.state') != -1 )
            {
				JToolBarHelper::divider();
				if ($state->get('filter.state') != 2)
                {
					JToolBarHelper::archiveList('taxes.archive','JTOOLBAR_ARCHIVE');
				}
				else if ($state->get('filter.state') == 2)
                {
					JToolBarHelper::unarchiveList('taxes.publish', 'JTOOLBAR_UNARCHIVE');
				}
			}	
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
        {
			JToolBarHelper::deleteList('', 'taxes.delete','JTOOLBAR_EMPTY_TRASH');
		}
        else if ($canDo->get('core.edit.state'))
        {
			JToolBarHelper::trash('taxes.trash','JTOOLBAR_TRASH');
		}
		if ($canDo->get('core.admin'))
        {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_solidres');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.tax','JTOOLBAR_HELP');
	}
}