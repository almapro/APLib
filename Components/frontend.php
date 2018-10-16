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
	* FrontEnd - Front-End requests & responses management class
	*/
	class FrontEnd Extends \APLib\Container
	{

		/**
		 * @var  array  $data  an array to contain received JSON data
		 */
		private static $data     =  array();

		/**
		 * @var  array  $items  an array containing costume FrontEnd commands
		 */
		protected static $items  =  array();

		/**
		 * Initiate FrontEnd
		 *
		 * @return  void
		 */
		public static function init()
		{
			static::$data  =  \APLib\Request\HTTP::data();
		}

		/**
		 * Run FrontEnd
		 *
		 * @return  void
		 */
		public static function run()
		{
			// DEBUG: Deprecated
			if(\APLib\Config::get('FrontEnd') != true)
				die(
					json_encode(
						array(
							'command'    =>  'disable',
							'reason'     =>  'Front-End is disabled',
							'placement'  =>  array(
								'from'   =>  'bottom',
								'align'  =>  'left'
							)
						)
					)
				);
			$response  =  array();
			switch(static::$data['command'])
			{
				// DEBUG: Deprecated
				case 'update':
					$response  =  array(
						'command'   =>  'update',
						'selector'  =>  static::$data['selector'],
						'html'      =>  static::$data['html']
					);
					break;
				// DEBUG: Deprecated
				case 'disabled':
					$response  =  array(
						'command'    =>  'enable',
						'placement'  =>  array(
							'from'   =>  'bottom',
							'align'  =>  'left'
						)
					);
					break;
				default:
					foreach(static::$items as $item)
					{
						if(static::$data['command']  ==  $item['command'])
						{
							if(is_array($item['response']))
							{
								$response  =  $item['response'];
							}
							else
							{
								try
								{
									$response  =  $item['response']();
								}
								catch(\Exception $e)
								{
									\APLib\Logger::Error($e);
									$response  =  array(
										'command'    =>  'alert',
										'title'      =>  'There was an error',
										'message'    =>  $e->getMessage(),
										'type'       =>  'error',
										'placement'  =>  array(
											'from'   =>  'bottom',
											'align'  =>  'left'
										)
									);
								}
							}
							break;
						}
					}
					break;
			}
			if(\APLib\Config::get('verbose')  ==  true) $response['verbose']  =  true;
			else $response['verbose']  =  false;
			// DEBUG: Deprecated
			if(\APLib\Config::get('interval')  !=  null) $response['interval']  =  \APLib\Config::get('interval');
			die(json_encode($response));
		}
	}
?>
