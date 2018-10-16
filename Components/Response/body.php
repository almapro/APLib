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
	* Body - A class to control body
	*/
	class Body Extends \APLib\Container
	{

		/**
		 * @var  array  $items  an array to contian elements
		 */
		protected static $items  =  array();

		/**
		 * Show Body
		 *
		 * @return  void
		 */
		function show()
		{
			?>
	<body<?php echo (\APLib\Config::get('direction') !== null) ? " dir='".\APLib\Config::get('direction')."'" : ''; ?>>
<?php if(sizeof(\APLib\Response\Body\CSS::items()) > 0)
{ ?>
		<style type="text/css">
<?php foreach(\APLib\Response\Body\CSS::items() as $attribute){
	echo "			$attribute\r\n";
} ?>
		</style>
<?php }
	foreach(static::items() as $item)
	{
		echo $item."\r\n";
	}
	if(sizeof(\APLib\Response\Body\JavaScript::items()) > 0 || sizeof(\APLib\Response\FrontEnd::items()) > 0)
	{
		if(sizeof(\APLib\Response\FrontEnd::items()) > 0 && !\APLib\Config::get('Optimize')) \APLib\Response\FrontEnd::init(); ?>
		<script type="text/javascript">
			window.onload = function(){
<?php foreach(\APLib\Response\FrontEnd::items() as $item)
		{
			echo "APLib.Commands.set('{$item['command']}', {$item['callback']});";
		}
		foreach(\APLib\Response\Body\JavaScript::items() as $item)
		{
			echo "				$item\r\n";
		} ?>
			}
		</script>
<?php }?>
	</body>
<?php
		}
	}
?>
