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

	namespace APLib\Response\Header;

	/**
	* Link - Link Style Sheets class
	*/
	class Link Extends \APLib\Container
	{

		/**
		 * @var  array  $files  an array to contain JavaScript files
		 */
		private static $files  =  array();

		public static function files()
		{
			return static::$files;
		}

		/**
		 * @var  array  $items  an array to contain JavaScript functions
		 */
		protected static $items  =  array();

    /**
		 * Add an item to contined items
		 *
		 * @param   string  $item  item to add
		 *
		 * @return  void
		 */
    public static function add($item, $rel = 'stylesheet')
		{
			if($rel == 'stylesheet') array_push(static::$files, $item);
			array_push(static::$items, "<link rel=\"{$rel}\" href=\"{$item}\" />");
		}

    /**
		 * Remove an item from the contained items
		 *
		 * @param   string/null  $item  item to remove
		 *
		 * @return  void
		 */
		public static function remove($item  =  null, $rel = 'stylesheet')
		{
			if($item  ==  null)
			{
				array_pop(static::$files);
				array_pop(static::$items);
			}
			else
			{
				for($i=0; $i < sizeof(static::$items); $i++)
				{
					if(static::$items[$i]  ==  "<link rel=\"{$rel}\" href=\"{$item}\" />")
					{
						array_splice(static::$files, $i, 1);
						array_splice(static::$items, $i, 1);
						break;
					}
				}
			}
		}
	}
?>
