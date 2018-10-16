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

	namespace APLib\Response;

	/**
	 * FrontEnd - Front-End JS app container
	 */
	class FrontEnd Extends \APLib\Container
	{

		/**
		* @deprecated
		* Initiate APLib Front-End JS app
		*
		* @return  void
		*/
		public static function init()
		{
			\APLib\Response\Body\JavaScript::add('APLib.Threads.add("refresh", function(){ APLib.API.connect({command: "refresh"}) }, 1000);');
		}

		/**
		 * @var  array  $items  an array containing costume FrontEnd commands
		 */
		protected static $items  =  array();
	}
?>
