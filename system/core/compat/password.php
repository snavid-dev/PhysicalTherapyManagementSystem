<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this category notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    CodeIgniter
 * @author    EllisLab Dev Team
 * @copyright    Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright    Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link    https://codeigniter.com
 * @since    Version 3.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PHP ext/standard/password compatibility package
 *
 * @package        CodeIgniter
 * @subpackage    CodeIgniter
 * @category    Compatibility
 * @author        Andrey Andreev
 * @link        https://codeigniter.com/user_guide/
 * @link        http://php.net/password
 */

// ------------------------------------------------------------------------

if (is_php('5.5') OR !defined('CRYPT_BLOWFISH') OR CRYPT_BLOWFISH !== 1 OR defined('HHVM_VERSION')) {
	return;
}

// ------------------------------------------------------------------------

defined('PASSWORD_BCRYPT') OR define('PASSWORD_BCRYPT', 1);
defined('PASSWORD_DEFAULT') OR define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);

// ------------------------------------------------------------------------

if (!function_exists('password_get_info')) {
	/**
	 * password_get_info()
	 *
	 * @link    http://php.net/password_get_info
	 * @param string $hash
	 * @return    array
	 */
	function password_get_info($hash)
	{
		return (strlen($hash) < 60 OR sscanf($hash, '$2y$%d', $hash) !== 1)
			? array('algo' => 0, 'algoName' => 'unknown', 'options' => array())
			: array('algo' => 1, 'algoName' => 'bcrypt', 'options' => array('cost' => $hash));
	}
}

// ------------------------------------------------------------------------

if (!function_exists('password_hash')) {
	/**
	 * password_hash()
	 *
	 * @link    http://php.net/password_hash
	 * @param string $password
	 * @param int $algo
	 * @param array $options
	 * @return    mixed
	 */
	function password_hash($password, $algo, array $options = array())
	{
		static $func_overload;
		isset($func_overload) OR $func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));

		if ($algo !== 1) {
			trigger_error('password_hash(): Unknown hashing algorithm: ' . (int)$algo, E_USER_WARNING);
			return NULL;
		}

		if (isset($options['cost']) && ($options['cost'] < 4 OR $options['cost'] > 31)) {
			trigger_error('password_hash(): Invalid bcrypt cost parameter specified: ' . (int)$options['cost'], E_USER_WARNING);
			return NULL;
		}

		if (isset($options['salt']) && ($saltlen = ($func_overload ? mb_strlen($options['salt'], '8bit') : strlen($options['salt']))) < 22) {
			trigger_error('password_hash(): Provided salt is too short: ' . $saltlen . ' expecting 22', E_USER_WARNING);
			return NULL;
		} elseif (!isset($options['salt'])) {
			if (function_exists('random_bytes')) {
				try {
					$options['salt'] = random_bytes(16);
				} catch (Exception $e) {
					log_message('error', 'compat/password: Error while trying to use random_bytes(): ' . $e->getMessage());
					return FALSE;
				}
			} elseif (defined('MCRYPT_DEV_URANDOM')) {
				$options['salt'] = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
			} elseif (DIRECTORY_SEPARATOR === '/' && (is_readable($dev = '/dev/arandom') OR is_readable($dev = '/dev/urandom'))) {
				if (($fp = fopen($dev, 'rb')) === FALSE) {
					log_message('error', 'compat/password: Unable to open ' . $dev . ' for reading.');
					return FALSE;
				}

				// Try not to waste entropy ...
				is_php('5.4') && stream_set_chunk_size($fp, 16);

				$options['salt'] = '';
				for ($read = 0; $read < 16; $read = ($func_overload) ? mb_strlen($options['salt'], '8bit') : strlen($options['salt'])) {
					if (($read = fread($fp, 16 - $read)) === FALSE) {
						log_message('error', 'compat/password: Error while reading from ' . $dev . '.');
						return FALSE;
					}
					$options['salt'] .= $read;
				}

				fclose($fp);
			} elseif (function_exists('openssl_random_pseudo_bytes')) {
				$is_secure = NULL;
				$options['salt'] = openssl_random_pseudo_bytes(16, $is_