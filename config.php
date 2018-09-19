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

	namespace APLib;

	/**
	 * Config - A class containing all configuration and common calls
	 */
	class Config
	{

		/**
		 * @var  array  $configurations  an array containing all configurations
		 */
		private static $configurations  =  array();

		/**
		 * Set a configuration's value
		 *
		 * @param   string  $name   configuration's name
		 * @param   object  $value  configuration's value
		 *
		 * @return  void
		 */
		public static function set($name, $value)
		{
			static::$configurations[$name]  =  $value;
		}

		/**
		 * Get a configuration's value
		 *
		 * @param   string  $name   configuration's name
		 *
		 * @return  object
		 */
		public static function get($name)
		{
			return (isset(static::$configurations[$name])) ? static::$configurations[$name] : null;
		}

		/**
		 * Initiate Config
		 *
		 * @return  void
		 */
		function init()
		{
			static::set(
				'composer loader',
				__DIR__.'/composed/vendor/autoload.php'
			);
			static::set(
				'DB Host',
				'localhost'
			);
			static::set(
				'DB Name',
				'APLibDB'
			);
			static::set(
				'DB User',
				'root'
			);
			static::set(
				'DB Pass',
				''
			);
			static::set(
				'title',
				'APLib - A PHP Library to create your website smooth, easy & secure'
			);
			static::set(
				'FrontEnd',
				true
			);
			static::set(
				'Cookie Name',
				'APLibCookie'
			);
			static::set(
				'Cookie Timeout',
				(86400 * 3650) // 10 years
			);
			static::set(
				'Secure params',
				array(
					'username',
					'user',
					'password',
					'pass',
					'search',
					'q',
					'id'
				)
			);
		}

		/**
		 * Call common functions
		 *
		 * @return  void
		 */
		public static function common()
		{

			/**
			 * You can start by adding your common calls.
			 */

 			//////////////////////////////////////
 			// Security Check is Required Here //
 			////////////////////////////////////
			header("Access-Control-Max-Age: 3600");
 			header("Access-Control-Allow-Origin: *");
 			header("Access-Control-Allow-Methods: POST, GET");
 			header("Access-Control-Allow-Headers: Content-Type, Authorization, Access-Control-Allow-Headers, X-Requested-With");


			/**
			 * Multi language websites can use common phrases from below
			 */
			\APLib\Language::set(
				array(
					array('name' => 'English', 'code' => 'en'),
					array('name' => 'Español', 'code' => 'es'),
					array('name' => 'العربية', 'code' => 'ar')
				)
			);
			\APLib\Language::add(
				array(
					'placeholder'  =>  '%g%',
					'lang'         =>  'en',
					'value'        =>  'Hello'
				)
			);
			\APLib\Language::add(
				array(
					'placeholder'  =>  '%g%',
					'lang'         =>  'es',
					'value'        =>  'Hola'
				)
			);
			\APLib\Language::add(
				array(
					'placeholder'  =>  '%g%',
					'lang'         =>  'ar',
					'value'        =>  'مرحباً'
				)
			);
			\APLib\Language::add(
				array(
					'placeholder'  =>  '%wb%',
					'lang'         =>  'en',
					'value'        =>  'Welcome back'
				)
			);
			\APLib\Language::add(
				array(
					'placeholder'  =>  '%wb%',
					'lang'         =>  'es',
					'value'        =>  'Dar una buena acogida'
				)
			);
			\APLib\Language::add(
				array(
					'placeholder'  =>  '%wb%',
					'lang'         =>  'ar',
					'value'        =>  'أهلاً مجدداً'
				)
			);
		}
	}
?>
