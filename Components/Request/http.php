<?php
	/**
	 * APLib - A PHP library to create your website smooth, easy & secure
	 *
	 * @package   APLib
	 * @version   0.1
	 * @author    ALMA PRO LEADER
	 * @license   MIT License
	 * @copyright 2017-2018
	 * @link      https://github.com/almapro/APLib/
	 */

	namespace APLib\Request;

	/**
	* HTTP - A class to handle HTTP request
	*/
	class HTTP
	{

		/**
		 * Request URL
		 *
		 * @return  string
		 */
		public static function URL()
		{
			return $_SERVER['REQUEST_URI'];
		}

		/**
		 * Get the request hostname
		 *
		 * @return  string
		 */
		public static function host()
		{
			return $_SERVER['HTTP_HOST'];
		}

		/**
		 * Whether or not the request method is POST
		 *
		 * @return  bool
		 */
		public static function post()
		{
			return ($_SERVER['REQUEST_METHOD']  ==  "POST");
		}

		/**
		 * Whether or not the request's CONTENT_TYPE is json
		 *
		 * @return  bool
		 */
		public static function json()
		{
			if(!isset($_SERVER['CONTENT_TYPE'])) return false;
			return (strpos($_SERVER['CONTENT_TYPE'], "application/json") !== false);
		}

		/**
		 * @var  null/array  $data  a variable to hold json data if received
		 */
		private static $data  =  null;

		/**
		 * Return json data received
		 *
		 * @return  array
		 */
		public static function jsonData()
		{
			if(static::$data  !=  null) return static::$data;
			if(static::json()) return json_decode(file_get_contents('php://input'), true);
			return array();
		}
  }
?>
