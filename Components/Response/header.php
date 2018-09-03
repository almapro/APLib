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
	class Header Extends \APLib\Container
	{

		/**
		 * @var  array  $items  an array to contain custome header tags
		 */
		protected static $items  =  array();

    /**
		 * Add an item to contined items
		 *
		 * @param   string  $item  item to add
		 *
		 * @return  void
		 */
    public static function add($item)
		{
			array_push(static::$items, $item);
		}

    /**
		 * Remove an item from the contained items
		 *
		 * @param   string/null  $item  item to remove
		 *
		 * @return  void
		 */
		public static function remove($item  =  null)
		{
			if($item  ==  null)
			{
				array_pop(static::$items);
			}
			else
			{
				for($i=0; $i < sizeof(static::$items); $i++)
				{
					if(static::$items[$i]  ==  $item)
					{
						array_splice(static::$items, $i, 1);
						break;
					}
				}
			}
		}

		/**
		 * Initiate Header
		 *
		 * @return  void
		 */
		public static function init()
		{
			foreach(glob(APLibPath."../css/*.css") as $css)
			{
				\APLib\Response\Header\Link::add(\APLib\Extras::NormalizePath(APLibHTML.'../css/'.basename($css)));
			}
			\APLib\Response\Header\Script::add(\APLib\Extras::NormalizePath(APLibHTML.'../js/raphael.min.js'));
			\APLib\Response\Header\Script::add(\APLib\Extras::NormalizePath(APLibHTML.'../js/jquery.min.js'));
			foreach(array_reverse(glob(APLibPath."../js/*.js")) as $js)
			{
				if(basename($js)  !=  'jquery.min.js' && basename($js)  !=  'raphael.min.js')
					\APLib\Response\Header\Script::add(\APLib\Extras::NormalizePath(APLibHTML.'../js/'.basename($js)));
			}
			\APLib\Response\Header\Meta::add(
				array(
					array(
						'charset',
						'UTF-8'
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
<?php
	echo "		<title>".\APLib\Config::get('title')."</title>\r\n";
	foreach(\APLib\Response\Header\Meta::items() as $item){
		echo "		{$item}\r\n";
	}
	foreach(\APLib\Response\Header\Link::items() as $item)
	{
		echo "		{$item}\r\n";
	}
	foreach(\APLib\Response\Header\Script::items() as $item)
	{
		echo "		{$item}\r\n";
	}
	foreach(\APLib\Response\Header::items() as $item){
		echo "		{$item}\r\n";
	} ?>
	</head>
<?php
		}
	}
?>
