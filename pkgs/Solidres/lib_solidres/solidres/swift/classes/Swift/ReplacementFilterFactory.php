<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 * @license  GNU Lesser General Public License v3
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Creates StreamFilters.
 *
 * @package Swift
 * @author  Chris Corbyn
 */

defined('_JEXEC') or die;

interface Swift_ReplacementFilterFactory
{
    /**
     * Create a filter to replace $search with $replace.
     *
     * @param mixed $search
     * @param mixed $replace
     *
     * @return Swift_StreamFilter
     */
    public function createFilter($search, $replace);
}
