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
	* Script - JavaScript Script class
	*/
	class Script Extends \APLib\Container
	{

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
    public static function add($item)
		{
			array_push(static::$items, "<script type=\"text/javascript\" src=\"{$item}\"></script>");
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
					if(static::$items[$i]  ==  "<script type=\"text/javascript\" src=\"{$item}\"></script>")
					{
						array_splice(static::$items, $i, 1);
						break;
					}
				}
			}
		}
	}
?>
