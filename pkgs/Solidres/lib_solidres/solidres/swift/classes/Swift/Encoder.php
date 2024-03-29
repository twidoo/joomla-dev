<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 * @license  GNU Lesser General Public License v3
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Interface for all Encoder schemes.
 * @package Swift
 * @subpackage Encoder
 * @author Chris Corbyn
 */

defined('_JEXEC') or die;

interface Swift_Encoder extends Swift_Mime_CharsetObserver
{
    /**
     * Encode a given string to produce an encoded string.
     *
     * @param string  $string
     * @param integer $firstLineOffset if first line needs to be shorter
     * @param integer $maxLineLength   - 0 indicates the default length for this encoding
     *
     * @return string
     */
    public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0);
}
