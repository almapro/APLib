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
	 * Include & initiate Loader class
	 */
	require_once __DIR__.'/loader.php';
	\APLib\Loader::init();

	/**
	 * Core - First class to call to boot the library
	 */
	class Core
	{

		/**
		 * @var  array  $loaded_files  an array to contain realpath of each loaded file, so no file would be loaded twice
		 */
		public static $loaded_files   =  array(__FILE__);

		/**
		 * Initiate the library
		 *
		 * @return  void
		 */
		public static function init()
		{
			\APLib\Config::init();
			\APLib\Config::common();
			if(\APLib\Request\HTTP::post())
			{
				if(\APLib\Request\HTTP::json())
				{
					\APLib\FrontEnd::init();
				}
			}
			\APLib\Cache::init();
			\APLib\Security::init();
			\APLib\Logger::init();
			\APLib\Optimizer::init();
			\APLib\Response::init();
		}

		/**
		 * Run the library
		 *
		 * @return  void
		 */
		public static function run()
		{
			if(\APLib\Request\HTTP::post())
			{
				if(\APLib\Request\HTTP::json())
				{
					\APLib\FrontEnd::run();
				}
			}
			\APLib\Security::run();
			\APLib\Logger::run();
			\APLib\Optimizer::run();
			\APLib\Response::run();
		}
	}
?>
