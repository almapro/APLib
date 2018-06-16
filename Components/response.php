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
	* Response - A class to control response
	*/
	class Response
	{

		/**
		 * Initiate Response
		 *
		 * @return  void
		 */
		public static function init()
		{
			\APLib\Response\Header::init();
		}

		/**
		 * Run Response
		 *
		 * @return  void
		 */
		public static function run()
		{
			\APLib\Response\Header::show();
			\APLib\Response\Body::show();
			\APLib\Response\Footer::show();
		}
	}
?>
