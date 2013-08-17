<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 * @license  GNU Lesser General Public License v3
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * TransportException thrown when an error occurs in the Transport subsystem.
 *
 * @package    Swift
 * @subpackage Transport
 * @author     Chris Corbyn
 */

defined('_JEXEC') or die;

class Swift_TransportException extends Swift_IoException
{
    /**
     * Create a new TransportException with $message.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
