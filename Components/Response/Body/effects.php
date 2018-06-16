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

	namespace APLib\Response\Body;

	/**
	* Effects - A class to add some special crafted elements effects
	*/
	class Effects
	{

		/**
		 * Show a punch of blocks one by one
     *
		 * @param   array  $blocks   an array of blocks to show one by one
 		 * @param   int    $padding  how many tabs to add at each line's beginning [Default: 2]
 		 *
		 * @return  string
		 */
		function BlockByBlock($blocks, $padding  =  2)
		{
      if(!is_array($blocks)) return '';
      $tabs     =  "\r\n".str_repeat('	', $padding);
      $id       =  \APLib\Extras::RandomString(8, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
      $endTags  =  array();
			ob_start();
			for($i=0; $i < sizeof($blocks); $i++){
        $style        =  (isset($blocks[$i]['left']))        ? 'direction: ltr;'   : '';
        $style        =  (isset($blocks[$i]['right']))       ? 'direction: rtl;'   : '';
        $class        =  (isset($blocks[$i]['class']))       ? $blocks[$i]['class'].' noneopacity' : 'noneopacity';
        $style       .=  (isset($blocks[$i]['color']))       ? 'color: '.$blocks[$i]['color'].';'  : '';
        $interval     =  (isset($blocks[$i]['interval']))    ? $blocks[$i]['interval'] : '1000';
        $style       .=  (isset($blocks[$i]['background']))  ? 'background: '.$blocks[$i]['background'].';' : '';
        $currentTags  =  ($i < (sizeof($blocks)-1))          ? ', function(){' : ');';
        $delay        =  (isset($blocks[$i]['delay']))       ? 'setTimeout(function(){ ' : '';
        $fstyle       =  ($style  ==  '')                   ? '' : " style='{$style}'";
        if($i  ==  0){
          if ($i < (sizeof($blocks)-1)) array_push($endTags, str_repeat('	', (sizeof($blocks)-2))."}");
          if($delay  !=  '') array_push($endTags, str_repeat('	', (sizeof($blocks)+1-$i))."})}, {$blocks[$i]['delay']})");
          else array_push($endTags, str_repeat('	', (sizeof($blocks)+1-$i))."})");
        }elseif($i  !=  (sizeof($blocks)-1)){
          if($i < (sizeof($blocks)-1)) array_push($endTags, str_repeat('	', (sizeof($blocks)+1-$i)));
          if($delay  !=  '') array_push($endTags, str_repeat('	', (sizeof($blocks)+1-$i))."})}, {$blocks[$i]['delay']})");
        }else{
          $currentTags  .=  ($delay  !=  '') ? "}, {$blocks[$i]['delay']})" : '';
        }
        echo "{$tabs}<div id='{$id}{$i}' class='{$class}'{$fstyle}>{$blocks[$i]['text']}</div>";
        \APLib\Response\Body\JavaScript::add(str_repeat('	', $i)."{$delay}$('#{$id}{$i}').css('display', 'block').animate({ opacity: '1'},{$interval}{$currentTags}");
      }
      //array_push($endTags, "}");
      for ($i1=(sizeof($endTags)-1); $i1 > 0 ; $i1--) {
        \APLib\Response\Body\JavaScript::add($endTags[$i1]);
      }
      //\APLib\Response\Body\JavaScript::add("}");
			return ob_get_clean();
		}
	}
?>
