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
	* Cache - Cache management class
	*/
	class Cache
	{

		/**
		 * @var  object  $FilesCache  phpFastCache instance holder
		 */
		private static $FilesCache  =  null;

		/**
		 * Initiate the cache
		 *
		 * @return  void
		 */
		public static function init()
		{
      if(static::$FilesCache  !=  null) return;
			\Phpfastcache\CacheManager::setDefaultConfig(new \Phpfastcache\Config\ConfigurationOption([
        'path' => __DIR__.'/../cache'
      ]));
      static::$FilesCache  =  \Phpfastcache\CacheManager::getInstance('files');
		}

		public static function __callStatic($method, $args)
		{
			try
			{
				if(method_exists(get_class(), $method))
				{
					return call_user_func_array(array(get_class(), $method), $args);
				}
				elseif(method_exists(static::$FilesCache, $method))
				{
					return call_user_func_array(array(static::$FilesCache, $method), $args);
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
