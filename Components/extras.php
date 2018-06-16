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
			return array_reduce(
				explode('/', $path),
				create_function(
					'$a, $b',
					'
						if($a === 0)
							$a = "/";
						if($b === "" || $b === ".")
							return $a;
						if($b === "..")
							return dirname($a);
						return preg_replace("/\/+/", "/", "$a/$b");
					'
				),
				0
			);
		}
	}
?>
