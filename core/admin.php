<?php
/**
 * Curl class
 *
 * @package PBWoW Extension
 * @copyright (c) 2017 Sajaki
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwowext\core;

use phpbb\db\tools;
use phpbb\version_helper;
use phpbb\file_downloader;

class admin
{
	/**
	 * connects to remote site and gets xml or html using Curl
	 *
	 * @param      $url
	 * @param bool $return_Server_Response_Header
	 * @param bool $loud
	 * @param bool $json
	 * @return array
	 */
	public final function curl($url, $return_Server_Response_Header = false, $loud = true, $json = true)
	{
		global $user;

		$data = array(
			'response'            => '',
			'response_headers'    => '',
			'error'               => '',
		);

		if (function_exists('curl_init'))
		{
			/* Create a CURL handle. */
			if (($curl = curl_init($url)) === false)
			{
				trigger_error($user->lang['CURL_REQUIRED'], E_USER_WARNING);
			}

			// set options
			curl_setopt_array(
				$curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url,
					CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:55.0) Gecko/20100101 Firefox/55.0', //override
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_FOLLOWLOCATION, true,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_VERBOSE => true,
					CURLOPT_HEADER => $return_Server_Response_Header,
					CURLOPT_HEADER => false
				)
			);

			$response = curl_exec($curl);
			$headers = curl_getinfo($curl);

			if ($response === false || $response === '')
			{
				trigger_error(curl_error($curl), E_USER_WARNING);
			}
			else
			{
				$data = array(
					'response'            => $json && $this->isJSON($response) ? json_decode($response, true) : $response,
					'response_headers'  => (array) $headers,
					'error'                => '',
				);
			}

			curl_close($curl);
			return $data;

		}

		//report errors?
		if ($loud == true)
		{
			trigger_error($data['error'], E_USER_WARNING);
		}
		return $data['response'];

	}

	/**
	 * @param $string
	 * @return bool check if is json
	 */
	public function isJSON($string)
	{
		return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}

}
