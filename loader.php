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
	 * Include external php libraries
	 */
	require_once __DIR__.'/composed/vendor/autoload.php';

	/**
	 * Include Container class
	 */
	require_once __DIR__.'/Components/container.php';

	/**
	 * Loader - A class to load any classes or interfaces needed
	 */
	class Loader Extends \APLib\Container
	{

    /**
		 * @var  array  $items  an array to contain paths to load from
		 */
		protected static $items  =  array();

		/**
		 * @var  array  $loaded_files  an array to contain realpath of each loaded file, so no file would be loaded twice
		 */
		private static $loaded_files   =  array();

		/**
		 * Initiate the loader
		 *
		 * @return  void
		 */
		public static function init()
		{
			static::add(
				array(
					'path'       =>  '/',
					'namespace'  =>  'APLib'
				)
			);
			static::add(
				array(
					'path'       =>  '/Components/',
					'namespace'  =>  'APLib'
				)
			);
			static::add(
				array(
					'path'       =>  '/Themes/',
					'namespace'  =>  'APLib'
				)
			);
			static::add(
				array(
					'path'       =>  '/Views/',
					'namespace'  =>  'APLib'
				)
			);
			static::add(
				array(
					'path'       =>  '/Interfaces/',
					'namespace'  =>  'APLib'
				)
			);
			static::add(
				array(
					'path'       =>  '/../'
				)
			);
			$libPath = str_replace('\\', '/', __DIR__);
			define('APLibPath', $libPath."/");
			define('APLibHTML', str_replace($_SERVER['DOCUMENT_ROOT'], '', $libPath)."/");
      spl_autoload_register(function($class)
    	{
				$libPath = str_replace('\\', '/', __DIR__);
    		try
    		{
    			static::$loaded_files  =  array();
    			foreach (get_included_files() as $file)
    			{
    				array_push(static::$loaded_files, realpath($file));
    			}
					$namespace    =  null;
					$path_subs    =  null;
					$class_name   =  $class;
					if(strpos($class, '\\') > 0)
					{
						$path_subs   =  explode('/', str_replace('\\', '/', $class_name));
						$namespace   =  $path_subs[0];
						$class_name  =  end($path_subs);
						array_splice($path_subs, 0, 1);
						array_pop($path_subs);
					}
					$class_name    =  strtolower($class_name);
    			foreach(static::items() as $item)
					{
						if($namespace  !=  null && isset($item['namespace']))
						{
							if($namespace  !=  $item['namespace']) continue;
							if($path_subs  !=  null &&  sizeof($path_subs) > 0)
							{
								$search_sub   =  $libPath.$item['path'];
								foreach($path_subs as $path_sub)
								{
									if($path_sub  ==  '') continue;
									$search_sub   .=  $path_sub.'/';
									$search_path   =  \APLib\Extras::NormalizePath($search_sub.$class_name.'.php');
									$search_path   =  (substr($search_path, 1, 1) == ':') ? str_replace('/', '\\', $search_path) : $search_path;
									if(in_array($search_path, static::$loaded_files)) continue;
									if(file_exists($search_path))
									{
										include $search_path;
										return true;
									}
								}
							}
							$search_path    =  $libPath.$item['path'].$class_name.".php";
							if(in_array(realpath($search_path), static::$loaded_files)) continue;
							if(file_exists($search_path))
							{
								include $search_path;
								return true;
							}
						}
						else
						{
							if($path_subs  !=  null &&  sizeof($path_subs) > 0)
							{
								$search_sub   =  __DIR__.$item['path'];
								foreach($path_subs as $path_sub)
								{
									if($path_sub  ==  '') continue;
									$search_sub   .=  $path_sub.'/';
									$search_path   =  $search_sub.$class_name.'.php';
									if(in_array(realpath($search_path), static::$loaded_files)) continue;
									if(file_exists($search_path))
									{
										include $search_path;
										return true;
									}
								}
							}
							$search_path    =  $libPath.$item['path'].$class_name.".php";
							if(in_array(realpath($search_path), static::$loaded_files)) continue;
							if(file_exists($search_path))
							{
								include $search_path;
								return true;
							}
						}
					}
    			throw new \Exception("Class/Interface ({$class}) was not found!");
    		}
    		catch(\Exception $e)
    		{
    			\APLib\Logger::Error($e);
    		}
    	});
		}

		/**
		 * Load all php files in this directory
		 *
		 * @param   string  $dir  a directory to load from
		 *
		 * @return  void
		 */
		public static function load($dir)
		{
			static::$loaded_files  =  array();
			foreach (get_included_files() as $file)
			{
				array_push(static::$loaded_files, realpath($file));
			}
			foreach(glob($dir.'*.php') as $file)
			{
				if(in_array(realpath($file), static::$loaded_files)) continue;
				include $file;
			}
		}
	}
?>
