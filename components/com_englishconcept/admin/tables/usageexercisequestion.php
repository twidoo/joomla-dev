<?php
defined('_JEXEC') or die;

class EnglishConceptTableUsageExerciseQuestion extends JTable
{
	function __construct($_db)
	{
		parent::__construct('#__ec_lessons_usages_exercises_questions', 'id', $_db);
	}
}
