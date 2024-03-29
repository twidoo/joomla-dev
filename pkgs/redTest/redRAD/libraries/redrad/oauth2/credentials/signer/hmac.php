<?php
/**
 * @package     RedRAD
 * @subpackage  OAuth2
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * OAuth HMAC-SHA1 Signature Method class for the RedRAD.
 *
 * @package     RedRAD
 * @subpackage  OAuth2
 * @since       1.0
 */
class ROauth2CredentialsSignerHMAC implements ROauth2CredentialsSigner
{
	/**
	 * Calculate and return the OAuth message signature using HMAC-SHA1
	 *
	 * @param   string  $baseString        The OAuth message as a normalized base string.
	 * @param   string  $clientSecret      The OAuth client's secret.
	 * @param   string  $credentialSecret  The OAuth credentials' secret.
	 *
	 * @return  string  The OAuth message signature.
	 *
	 * @since   1.0
	 * @throws  InvalidArgumentException
	 */
	public function sign($baseString, $clientSecret, $credentialSecret)
	{
		// Build the key for hashing the base string.
		$key = $clientSecret . '&' . $credentialSecret;

		// Generate the binary hash of the based string and key.
		$hmac = hash_hmac('sha1', $baseString, $key, true);

		return base64_encode($hmac);
	}
}
