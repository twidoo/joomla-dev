<?php
defined('_JEXEC') or die;

class EnglishConceptTableSetting extends JTable
{
	function __construct($_db)
	{
		parent::__construct('#__ec_lesson_settings', 'id', $_db);
	}
}
