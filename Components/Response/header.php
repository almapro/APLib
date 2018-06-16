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
	* Header - A class to control header
	*/
	class Header
	{

		/**
		 * Initiate Header
		 *
		 * @return  void
		 */
		public static function init()
		{
			foreach(glob(__DIR__."/../../../css/*.css") as $css)
			{
				\APLib\Response\Header\Link::add(\APLib\Extras::NormalizePath(LibPATH.'../css/'.basename($css)));
			}
			\APLib\Response\Header\Script::add(\APLib\Extras::NormalizePath(LibPATH.'../js/raphael.min.js'));
			\APLib\Response\Header\Script::add(\APLib\Extras::NormalizePath(LibPATH.'../js/jquery.min.js'));
			foreach(array_reverse(glob(__DIR__."/../../../js/*.js")) as $js)
			{
				if(basename($js)  !=  'jquery.min.js' && basename($js)  !=  'raphael.min.js')
					\APLib\Response\Header\Script::add(\APLib\Extras::NormalizePath(LibPATH.'../js/'.basename($js)));
			}
			\APLib\Response\Header\Meta::add(
				array(
					array(
						'charset',
						'utf-8'
					)
				)
			);
			\APLib\Response\Header\Meta::add(
				array(
					array(
						'http-equiv',
						'X-UA-Compatible'
					),
					array(
						'content',
						'IE=edge'
					)
				)
			);
			\APLib\Response\Header\Meta::add(
				array(
					array(
						'name',
						'viewport'
					),
					array(
						'content',
						'width=device-width,initial-scale=1'
					)
				)
			);
		}

		/**
		 * Show Header
		 *
		 * @return  void
		 */
		public static function show()
		{
			?>
<!DOCTYPE html>
<html lang="en">
	<head>
<?php foreach(\APLib\Response\Header\Meta::items() as $item){
		echo "		{$item}\r\n";
	} ?>
		<title><?php echo \APLib\Config::get('title'); ?></title>
<?php foreach(\APLib\Response\Header\Link::items() as $item){
			echo "		{$item}\r\n";
		} ?>
<?php foreach(\APLib\Response\Header\Script::items() as $item){
			echo "		{$item}\r\n";
		} ?>
	</head>
<?php
		}
	}
?>
