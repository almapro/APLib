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
	* DB - Database class
	*/
	class DB
	{

		/**
		 * @var  object  $db  database connection holder variable
		 */
		private static $db  =  null;

		/**
		 * Initiate DB
		 *
		 * @param   string  $host  Datebase host
		 * @param   string  $name  Datebase name
		 * @param   string  $user  Datebase user
		 * @param   string  $pass  Datebase pass
		 *
		 * @return  void
		 */
		public static function init($host, $name, $user, $pass)
		{
			if(static::$db  !=  NULL) return;
			mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
			try
			{
				static::$db  =  new \mysqli($host, $user, $pass, $name);
				static::$db->query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'");
			}
			catch(\Exception $e)
			{
				\APLib\Logger::Error($e);
			}
		}

		public static function __callStatic($method, $args)
		{
			try
			{
				if(method_exists(get_class(), $method))
				{
					return call_user_func_array(array(get_class(), $method), $args);
				}
				elseif(method_exists(static::$db, $method))
				{
					return call_user_func_array(array(static::$db, $method), $args);
				}
				else
				{
					throw new \Exception("Call to a non-existing method ({$method})", 1);
				}
			}
			catch(\Exception $e)
			{
				\APLib\Logger::Error($e);
			}
		}
	}
?>
