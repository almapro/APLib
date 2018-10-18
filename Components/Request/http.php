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
			return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  ==  "POST");
		}

		/**
		 * Whether or not the request's CONTENT_TYPE is json
		 *
		 * @return  bool
		 */
		public static function json()
		{
			return (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], "application/json") !== false);
		}

		/**
		 * @var  null/array  $data  a variable to hold json data if received
		 */
		private static $data  =  null;

		/**
		 * Set the $data variable
		 *
		 * @return void
		 */
		public static function setData($data)
		{
			static::$data = $data;
		}

		/**
		 * Return data received
		 *
		 * @return  array
		 */
		public static function data()
		{
			if(static::$data  !=  null) return static::$data;
			if(static::json()) return json_decode(file_get_contents('php://input'), true);
			return array();
		}
  }
?>
