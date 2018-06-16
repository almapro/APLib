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
	* Container - A class to be extended into a class which may contain more or less items
	*/
	class Container
	{

		/**
		 * @var  array  $items  items to contain
		 */
		protected static $items = array();

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
		 * Clear items
		 *
		 * @return  void
		 */
		public static function reset()
		{
			static::$items  =  array();
		}

		/**
		 * Return all items
		 *
		 * @return  array
		 */
		public static function items()
		{
			return static::$items;
		}
	}
?>
