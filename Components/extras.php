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
	* Extras - A class that contains extra/common function
	*/
	class Extras
	{

		/**
		 * Generate a random string
		 *
		 * @param   int     $length  how long the random string should be beginning [Default: 25]
 		 * @param   string  $chars   all chars to be included in the string [Default: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789']
 		 *
		 * @return  string
		 */
		public static function RandomString($length  =  25, $chars  =  'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
		{
			$charslength    =  strlen($chars);
			$result         =  '';
			for($i = 0; $i  <  $length; $i++){
				$result      .=  $chars[rand(0, $charslength - 1)];
			}
			return $result;
		}

		/**
		 * Return the normal path. https://edmondscommerce.github.io/php/php-realpath-for-none-existant-paths.html
		 *
		 * @param   string  $path  the path to normalize
		 *
		 * @return  string
		 */
		public static function NormalizePath($path)
		{
			$ret = array_reduce(
				explode('/', $path),
				create_function(
					'$a, $b',
					'
						if($a === 0)
							$a = "/";
						if($b === "" || $b === ".")
							return $a;
						if($b === "..")
							return str_replace("\\\\", "/", dirname($a));
						return preg_replace("/\/+/", "/", "$a/$b");
					'
				),
				0
			);
			$ret = (substr($ret, 2, 1) == ':') ? substr($ret, 1) : $ret;
			return $ret;
		}

		/**
		 * Define HTMLPath constant
		 *
		 * @param   string  $path  the path to set
		 *
		 * @return  void
		 */
		public static function DefineHTML($path)
		{
			$path = str_replace('\\', '/', $path);
			define('HTMLPath', static::NormalizePath(str_replace($_SERVER['DOCUMENT_ROOT'], '', $path))."/");
		}

		/**
		 * Define ath constant
		 *
		 * @param   string  $path  the path to set
		 *
		 * @return  void
		 */
		public static function DefinePATH($path)
		{
			$path = str_replace('\\', '/', $path);
			$path = (substr($path, 2, 1) == ':') ? substr($path, 1) : $path;
			define('Path', static::NormalizePath($path)."/");
		}
	}
?>
