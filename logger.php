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
	 * Define log files' paths
	 */
	if(!defined('ELF')) define("ELF", __DIR__."/logs/error.log");
	if(!defined('ALF')) define("ALF", __DIR__."/logs/access.log");
	if(!defined('MLF')) define("MLF", __DIR__."/logs/message.log");

	/**
	 * Logger - A class to log all errors
	 */
	class Logger
	{
		/**
		 * @var  array  $errors  an array to contain errors
		 */
		private static $errors  =  array();

		/**
		 * Initiate Logger
		 *
		 * @return  void
		 */
		public static function init()
		{
			if(!file_exists(__DIR__.'/logs/index.php'))
			{
				if(!file_exists(__DIR__.'/logs/')) mkdir(__DIR__.'/logs/');
				\APLib\Security::Index(__DIR__.'/logs/index.php');
			}
		}

		/**
		 * Run Logger
		 *
		 * @return  void
		 */
		public static function run()
		{
			if(sizeof(static::$errors) > 0)
			{
				//
			}
		}

		/**
		 * Log error data
		 *
		 * @param   object  $log  log data to write to log file
		 *
		 * @return  void
		 */
		public static function Error($log)
		{
			if($log instanceof \Exception)
			{
				array_push(static::$errors, $log);
				$log  =  "Error: ".$log->getMessage().'\r\nFile: '.$log->getFile().'\r\nLine: '.$log->getLine();
			}
			$le  =  \APLib\Parser::last(ELF);
			if(isset($le['message']) && $log  ==  $le['message']) return;
			$o   =  '[X] - '.date("m/j/Y g:i:s A").' - '.$log;
			file_put_contents(ELF, "->->->->->\r\n".$o."\r\n<-<-<-<-<-\r\n", FILE_APPEND | LOCK_EX);
		}

		/**
		 * Log good data
		 *
		 * @param   object  $log  log data to write to log file
		 *
		 * @return  void
		 */
		public static function Good($log)
		{
			$le  =  \APLib\Parser::last(MLF);
			if(isset($le['message']) && $log  ==  $le['message']) return;
			$o  =  '[+] - '.date("m/j/Y g:i:s A").' - '.$log;
			file_put_contents(MLf, "->->->->->\r\n".$o."\r\n<-<-<-<-<-\r\n", FILE_APPEND | LOCK_EX);
		}

		/**
		 * Log info data
		 *
		 * @param   object  $log  log data to write to log file
		 *
		 * @return  void
		 */
		public static function Info($log)
		{
			$le  =  \APLib\Parser::last(ELF);
			if(isset($le['message']) && $log  ==  $le['message']) return;
			$o  =  '[*] - '.date("m/j/Y g:i:s A").' - '.$log;
			file_put_contents(ELF, "->->->->->\r\n".$o."\r\n<-<-<-<-<-\r\n", FILE_APPEND | LOCK_EX);
		}

		/**
		 * Log warning data
		 *
		 * @param   object  $log  log data to write to log file
		 *
		 * @return  void
		 */
		public static function Warning($log)
		{
			$le  =  \APLib\Parser::last(ELF);
			if(isset($le['message']) && $log  ==  $le['message']) return;
			$o  =  '[!] - '.date("m/j/Y g:i:s A").' - '.$log;
			file_put_contents(ELF, "->->->->->\r\n".$o."\r\n<-<-<-<-<-\r\n", FILE_APPEND | LOCK_EX);
		}

		/**
		 * Log access data
		 *
		 * @param   object  $log  log data to write to log file
		 *
		 * @return  void
		 */
		public static function Access($log)
		{
			$le  =  \APLib\Parser::last(ALF);
			if(isset($le['message']) && $log  ==  $le['message']) return;
			$o  =  '[**] - '.date("m/j/Y g:i:s A").' - '.$log;
			file_put_contents(ALF, "->->->->->\r\n".$o."\r\n<-<-<-<-<-\r\n", FILE_APPEND | LOCK_EX);
		}

		/**
		 * Log hack data
		 *
		 * @param   object  $log  log data to write to log file
		 *
		 * @return  void
		 */
		public static function Hack($log)
		{
			$le  =  \APLib\Parser::last(ALF);
			if(isset($le['message']) && $log  ==  $le['message']) return;
			$o  =  '[!!] - '.date("m/j/Y g:i:s A").' - '.$log;
			file_put_contents(ALF, "->->->->->\r\n".$o."\r\n<-<-<-<-<-\r\n", FILE_APPEND | LOCK_EX);
		}
	}
?>
