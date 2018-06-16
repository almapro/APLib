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
	 * Bootstrap - A class to create bootstrap-based elements
	 */
	class Bootstrap implements \APLib\Bootstrap\GlyphIcons
	{

		/**
		 * @var  int  $maxElementsPeerRow  how many elements peer row. This is used if elements are not cased
		 */
		public static $maxElementsPeerRow  =  4;

		/**
		 * Create a ScrollSpy javascript for a specific scrollable area
		 *
		 * @param   string  $area    a selector string which points to the scrollable area
		 * @param   string  $target  a selector string which points to the target navbar
		 * @param   int     $offset  offset of which the target's scrollspy begins [Default: 50]
		 *
		 * @return  void
		 */
		public static function scrollspy($area, $target, $offset  =  50)
		{
			\APLib\Response\Body\JavaScript::add("$('{$area}').scrollspy({ target: '{$target}', offset: {$offset} });");
		}

		/**
		 * Create an affix javascript for a specific element
		 *
		 * @param   string  $target        a selector string which points to the target element
		 * @param   int     $offsetTop     offset of which target should be afixed when scrolled from Top [Default: 150]
		 * @param   int     $offsetBottom  offset of which target should be afixed when scrolled from Bottom[Default: 0]
		 *
		 * @return  void
		 */
		public static function affix($target, $offsetTop  =  150, $offsetBottom  =  0)
		{
			$offsetBottom  =  ($offsetBottom  !=  0) ? ', bottom: '.$offsetBottom : '';
			\APLib\Response\Body\JavaScript::add("$('{$target}').affix({ offset: { top: {$offsetTop}{$offsetBottom} }});");
		}
		
		/**
		 * Create a Jumbotron
		 *
		 * @param   string  $text       a text that will take a place in the H1 header
		 * @param   string  $paragraph  a paragraph text to describe the header
		 * @param   int     $padding    how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $id         element's id [Default: NO ID]
		 *
		 * @return  string
		 */
		public static function jumbotron($text, $paragraph, $padding  =  2, $id  =  '')
		{
			$tabs  =  "\r\n".str_repeat('	', $padding);
			$id    =  ($id  !=  '') ? " id='{$id}'" : '';
			ob_start();
			echo "{$tabs}<div class='jumbotron text-center'{$id}>";
			echo "{$tabs}	<h1>{$text}</h1>";
			echo "{$tabs}	<p>{$paragraph}</p>";
			echo "{$tabs}</div>";
			return ob_get_clean();
		}

		/**
		 * Create a link ('a' tag)
		 *
		 * @param   string  $url     a URL to use in the href attribute
		 * @param   string  $text    a text to describe the link
		 * @param   bool    $newTab  whether or not to open the link in a new tab [Default: true]
		 *
		 * @return  string
		 */
		public static function link($url, $text, $newTab  =  true)
		{
			$tab  =  ($newTab) ? " target='_blank'" : '';
			return "<a href='{$url}'{$tab}>{$text}</a>";
		}

		/**
		 * Create keyboard input indicator tag (<kbd>)
		 *
		 * @param   string  $keys  keys string
		 *
		 * @return  string
		 */
		public static function keyboard($keys)
		{
			return "<kbd>{$keys}</kbd>";
		}

		/**
		 * Create a 'mark' tag to highlight text
		 *
		 * @param   string  $text  text to highlight
		 *
		 * @return  string
		 */
		public static function mark($text)
		{
			return "<mark>{$text}</mark>";
		}

		/**
		 * Create an abbreviation or acronym element
		 *
		 * @param   string  $text   text of the abbreviation
		 * @param   string  $title  title of the abbreviation
		 *
		 * @return  string
		 */
		public static function abbreviation($text, $title)
		{
			return "<abbr title='{$title}'>{$text}</abbr>";
		}

		/**
		 * Create a blockquote
		 *
		 * @param   string  $quote           quote text
		 * @param   string  $quoter          who quoted that
		 * @param   int     $padding         how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $id              element's id [Default: NO ID]
		 * @param   bool    $reverseQuote    whether to show the quote on the right or not [Default: false]
		 * @param   bool    $fluidContainer  whether or not the container div should be fluid [Default: true]
		 *
		 * @return  string
		 */
		public static function blockquote($quote, $quoter, $padding  =  2, $id  =  '', $reverseQuote  =  false, $fluidContainer  =  true)
		{
			$tabs     =  "\r\n".str_repeat('	', $padding);
			$id       =  ($id  !=  '') ? " id='{$id}'" : '';
			$fluid    =  ($fluidContainer) ? '-fluid' : '';
			$reverse  =  ($reverseQuote) ? " class='blockquote-reverse'" : '';
			ob_start();
			echo "{$tabs}<div class='container{$fluid}'{$id}>";
			echo "{$tabs}	<blockquote{$reverse}>";
			echo "{$tabs}		<p>{$quote}</p>";
			echo "{$tabs}		<footer>{$quoter}</footer>";
			echo "{$tabs}	</blockquote>";
			echo "{$tabs}</div>";
			return ob_get_clean();
		}

		/**
		 * Create a description list
		 *
		 * @param   string  $title           description list title
		 * @param   string  $text            description list description text
		 * @param   array   $elements        two dimensions arrays containing elements as follows:
		 *                                     [0] = title
		 *                                     [1] = description
		 * @param   int     $padding         how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $id              element's id [Default: NO ID]
		 * @param   bool    $newLineStart    whether or not to start in a new line [Default: false]
		 * @param   bool    $fluidContainer  whether or not the container div should be fluid [Default: true]
		 *
		 * @return  string
		 */
		public static function dl($title, $text, $elements, $padding  =  2, $id  =  '', $newLineStart  =  true,  $fluidContainer  =  true)
		{
			if(!is_array($elements)) return '';
			$tabs      =  "\r\n".str_repeat('	', $padding);
			$id        =  ($id  !=  '') ? " id='{$id}'" : '';
			$fluid     =  ($fluidContainer) ? '-fluid' : '';
			$startPad  =  ($newLineStart) ? $tabs : '';
			try
			{
				ob_start();
				echo "{$startPad}<div class='container{$fluid}'{$id}>";
				echo "{$tabs}	<h1>{$title}</h1>";
				echo "{$tabs}	<p>{$text}</p>";
				echo "{$tabs}	<dl>";
				foreach($elements as $element)
				{
					echo "{$tabs}		<dt>{$element[0]}</dt>";
					echo "{$tabs}		<dd>{$element[1]}</dd>";
				}
				echo "{$tabs}	</dl>";
				echo "{$tabs}</div>";
				return ob_get_clean();
			}
			catch(\Exception $e)
			{
				ob_flush();
				\APLib\Logger::Error($e);
			}
			return '';
		}

		/**
		 * Create 'code' tag element
		 *
		 * @param   string  $code code to use
		 *
		 * @return  string
		 */
		public static function code($code)
		{
			return "<code>{$code}</code>";
		}

		/**
		 * Create 'p' tag element with colored text type
		 *
		 * @param   string  $text  text to use
		 * @param   string  $type  type to use. Use one of bootstrap's text color types
		 *
		 * @return  string
		 */
		public static function text($text, $type)
		{
			return "<p class='text-{$type}'>{$text}</p>";
		}

		/**
		 * Create 'p' tag element with background colored text type
		 *
		 * @param   string  $text  text to use
		 * @param   string  $type  type to use. Use one of bootstrap's background color types
		 *
		 * @return  string
		 */
		public static function backgroundedText($text, $type)
		{
			return "<p class='bg-{$type}'>{$text}</p>";
		}

		/**
		 * Create a 'div' tag with bootstrap's 'well' class
		 *
		 * @param   string  $text  text to write in the 'well'
		 * @param   string  $size  well's size. Use one of bootstrap's well sizes
		 *
		 * @return  string
		 */
		public static function well($text, $size  =  '')
		{
			$sizeClass  =  ($size  ==  '') ? '' : ' '.$size;
			return "<div class='well{$sizeClass}'>{$text}</div>";
		}

		/**
		 * Create an icon span with a sr-only text
		 *
		 * @param   string  $icon     a string containing the GlyphIcons icon [Default: ICON_SEARCH]
		 * @param   string  $srText   a hint to be used by assistive technologies [Default: '']
		 * @param   int     $padding  how many tabs to add at each line's beginning [Default: 2]
		 *
		 * @return  string
		 */
		public static function icon($icon  =  self::ICON_SEARCH, $srText  =  '', $padding  =  0)
		{
			$tabs  =  str_repeat('	', $padding);
			$sr    =  ($srText  ==  '') ? '' : " <span class='sr-only'>{$srText}</span>";
			return "{$tabs}<span class='{$icon}' aria-hidden='true'></span>{$sr}";
		}

		/**
		 * Create a label span
		 *
		 * @param   string  $text             a text to be the label string
		 * @param   int     $padding          how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $extraClasses     optional classes to add to the label
		 *
		 * @return  string
		 */
		public static function label($text, $padding  =  0, $extraClasses  =  '')
		{
			$tabs          =  str_repeat('	', $padding);
			$extraClasses  =  ($extraClasses  !=  '') ? ' '.$extraClasses : '';
			return "{$tabs}<span class='label{$extraClasses}' aria-hidden='true'>{$text}</span>{$sr}";
		}

		/**
		 * Create a badge span
		 *
		 * @param   int     $integer          an integer to write within the badge
		 * @param   int     $padding          how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $extraClasses     optional classes to add to the badge
		 *
		 * @return  string
		 */
		public static function badge($integer, $padding  =  0, $extraClasses  =  '')
		{
			$tabs          =  str_repeat('	', $padding);
			$extraClasses  =  ($extraClasses  !=  '') ? ' '.$extraClasses : '';
			return "{$tabs}<span class='badge{$extraClasses}'>{$integer}</span>";
		}

		/**
		 * Create a table
		 *
		 * @param   array   $columns          an array containing columns' names
		 * @param   array   $row              an array containing rows
		 * @param   int     $padding          how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $id               element's id [Default: NO ID]
		 * @param   string  $extraClasses     optional classes to add to the table
		 * @param   string  $textAlign        text align [Default: center]
		 * @param   bool    $responsiveTable  whether or not table should be responsive [Default: true]
		 * @param   bool    $fluidContainer   whether or not the container div should be fluid [Default: true]
		 *
		 * @return  string
		 */
		public static function table($columns, $rows, $padding  =  2, $id  =  '', $extraClasses  =  '', $textAlign  =  'center', $responsiveTable  =  true, $fluidContainer  =  true)
		{
			if(!is_array($columns)) return '';
			if(!is_array($rows)) return '';
			if(sizeof($columns)  ==  0) return '';
			if(sizeof($rows)     ==  0) return '';
			$tabs          =  "\r\n".str_repeat('	', $padding);
			$id            =  ($id  !=  '') ? " id='{$id}'" : '';
			$fluid         =  ($fluidContainer) ? '-fluid' : '';
			$responsive    =  ($responsiveTable) ? ' table-responsive' : '';
			$extraClasses  =  ($extraClasses == '') ? '' : ' '.$extraClasses;
			switch($textAlign)
			{
				case 'center':
				case 'left':
				case 'right':
					// All good
					break;
				default:
					$textAlign='center';
					break;
			}
			ob_start();
			echo "{$tabs}<div class='container{$fluid}{$responsive}'{$id}>";
			echo "{$tabs}	<table class='table text-{$textAlign}{$extraClasses}'>";
			echo "{$tabs}		<thead>";
			echo "{$tabs}			<tr>";
			foreach($columns as $column)
			{
				echo "{$tabs}				<th class='text-{$textAlign}'>{$column}</th>";
			}
			echo "{$tabs}			</tr>";
			echo "{$tabs}		</thead>";
			echo "{$tabs}		<tbody>";
			foreach($rows as $row)
			{
				echo "{$tabs}			<tr>";
				foreach($row as $data)
				{
					echo "{$tabs}				<td>{$data}</td>";
				}
				echo "{$tabs}			</tr>";
			}
			echo "{$tabs}		</tbody>";
			echo "{$tabs}	</table>";
			echo "{$tabs}</div>";
			return ob_get_clean();
		}

		/**
		 * Create an organized rowed elements
		 *
		 * @param   array  $elements        an array containing elements to organize
		 * @param   int    $padding         how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool   $fluidContainer  whether or not the container div should be fluid [Default: true]
		 *
		 * @return  string
		 */
		public static function row($elements, $padding  =  2, $fluidContainer  =  true)
		{
			if(!is_array($elements)) return '';
			if(sizeof($elements) == 0) return '';
			$tabs   =  "\r\n".str_repeat('	', $padding);
			$fluid  =  ($fluidContainer) ? '-fluid' : '';
			$size   =  12;
			switch(sizeof($elements))
			{
				case 1:
					// All good
					break;
				case 2:
					$size  =  6;
					break;
				case 3:
					$size  =  4;
					break;
				case 4:
					$size  =  3;
					break;
				case 6:
					$size  =  2;
					break;
				case 12:
					$size  =  1;
					break;
				default:
					switch(static::$maxElementsPeerRow)
					{
						case 1:
						case 2:
						case 3:
						case 4:
						case 6:
						case 12:
							// All good
							break;
						default:
							static::$maxElementsPeerRow  =  4;
							break;
					}
					$final_return  =  '';
					$temp_arr      =  array();
					if(sizeof($elements) < static::$maxElementsPeerRow)
					{
						$final_return  .=  static::row(array($elements[0]), $padding, $fluidContainer);
						array_splice($elements, 0, 1);
						$final_return  .=  static::row($elements, $padding, $fluidContainer);
					}
					else
					{
						for($i=0; $i < sizeof($elements); $i++)
						{
							if(sizeof($temp_arr)  ==  static::$maxElementsPeerRow)
							{
								$final_return  .=  static::row($temp_arr,$padding,$fluidContainer);
								$temp_arr       =  array();
							}
							array_push($temp_arr, $elements[$i]);
						}
						if(sizeof($temp_arr) > 0) $final_return  .=  static::row($temp_arr, $padding, $fluidContainer);
					}
					return $final_return;
					break;
			}
			ob_start();
			echo $tabs."<div class='container{$fluid}'>";
			echo $tabs."	<div class='row'>";
			foreach($elements as $element)
			{
				echo $tabs."		<div class='col-xs-{$size} col-sm-{$size} col-md-{$size} col-lg-{$size}'>";
				echo $tabs."			".$element;
				echo $tabs."		</div>";
			}
			echo $tabs."	</div>";
			echo $tabs."</div>";
			return ob_get_clean();
		}

		/**
		 * Create an unfair row columns' sizes
		 *
		 * @param   array  $elements        an array containing elements of each column
		 * @param   array  $sizes           an array containing sizes of columns
		 * @param   int    $padding         how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool   $fluidContainer  whether or not the container div should be fluid [Default: true]
		 *
		 * @return  string
		 */
		public static function unfairRow($elements, $sizes, $padding  =  2, $fluidContainer  =  true)
		{
			if(!is_array($elements) || !is_array($sizes)) return '';
			if(sizeof($elements)  ==  0) return '';
			if(sizeof($elements)  !=  sizeof($sizes)) return '';
			$totalSum  =  0;
			foreach($sizes as $size)
			{
				if(!is_int($size)) return '';
				$totalSum  +=  $size;
			}
			if($totalSum  !=  12) return '';
			$tabs   =  "\r\n".str_repeat('	', $padding);
			$fluid  =  ($fluidContainer) ? '-fluid' : '';
			if(sizeof($elements)  ==  1 || sizeof($elements)  ==  12) return static::row($elements, $padding, $fluidContainer);
			switch(sizeof($elements))
			{
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
				case 11:
					ob_start();
					echo $tabs."<div class='container{$fluid}'>";
					echo $tabs."	<div class='row'>";
					for($i=0; $i < sizeof($elements); $i++)
					{
						echo $tabs."		<div class='col-xs-{$sizes[$i]} col-sm-{$sizes[$i]} col-md-{$sizes[$i]} col-lg-{$sizes[$i]}'>";
						echo $tabs."			{$elements[$i]}";
						echo $tabs."		</div>";
					}
					echo $tabs."	</div>";
					echo $tabs."</div>";
					return ob_get_clean();
					break;
				default:
					switch(static::$maxElementsPeerRow)
					{
						case 1:
						case 2:
						case 3:
						case 4:
						case 6:
						case 12:
							// All good
							break;
						default:
							static::$maxElementsPeerRow  =  4;
							break;
					}
					$final_return  =  '';
					$temp_arr      =  array();
					$temp_sizes    =  array();
					if(sizeof($elements) < static::$maxElementsPeerRow)
					{
						$final_return  .=  static::row(array($elements[0]), $padding, $fluidContainer);
						array_splice($elements, 0, 1);
						array_splice($sizes, 0, 1);
						$final_return  .=  static::unfairRow($elements, $sizes, $padding, $fluidContainer);
					}
					else
					{
						for($i=0; $i < sizeof($elements); $i++)
						{
							if(sizeof($temp_arr)  ==  static::$maxElementsPeerRow)
							{
								$final_return  .=  static::unfairRow($temp_arr, $temp_sizes, $padding, $fluidContainer);
								$temp_arr       =  array();
								$temp_sizes     =  array();
							}
							array_push($temp_arr, $elements[$i]);
							array_push($temp_sizes, $sizes[$i]);
						}
						if(sizeof($temp_arr) > 0) $final_return  .=  static::unfairRow($temp_arr, $temp_sizes, $padding, $fluidContainer);
					}
					return $final_return;
					break;
			}
		}

		/**
		 * create thumbnails div. Note that they're equal in high while normal case is content warp
		 *
		 * @param   array   $elements        an array containing elements to be thumbnailed
		 * @param   int     $padding         how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool    $fluidContainer  whether or not the container div should be fluid [Default: true]
		 *
		 * @return  string
		 */
		public static function thumbnails($elements, $padding  =  2, $fluidContainer  =  true)
		{
			if(!is_array($elements)) return '';
			if(sizeof($elements)  ==  0) return '';
			$tabs   =  "\r\n".str_repeat('	', $padding);
			$fluid  =  ($fluidContainer) ? '-fluid' : '';
			$size   =  12;
			switch(sizeof($elements))
			{
				case 1:
					// All good
					break;
				case 2:
					$size  =  6;
					break;
				case 3:
					$size  =  4;
					break;
				case 4:
					$size  =  3;
					break;
				case 6:
					$size  =  2;
					break;
				case 12:
					$size  =  1;
					break;
				default:
					switch(static::$maxElementsPeerRow)
					{
						case 1:
						case 2:
						case 3:
						case 4:
						case 6:
						case 12:
							// All good
							break;
						default:
							static::$maxElementsPeerRow  =  4;
							break;
					}
					$final_return  =  '';
					$temp_arr      =  array();
					if(sizeof($elements) < static::$maxElementsPeerRow)
					{
						$final_return  .=  static::thumbnails(array($elements[0]), $padding, $fluidContainer);
						array_splice($elements, 0, 1);
						$final_return  .=  static::thumbnails($elements, $padding, $fluidContainer);
					}
					else
					{
						for($i=0; $i < sizeof($elements); $i++)
						{
							if(sizeof($temp_arr)  ==  static::$maxElementsPeerRow)
							{
								$final_return  .=  static::thumbnails($temp_arr, $padding, $fluidContainer);
								$temp_arr       =  array();
							}
							array_push($temp_arr, $elements[$i]);
						}
						if(sizeof($temp_arr) > 0) $final_return  .=  static::thumbnails($temp_arr, $padding, $fluidContainer);
					}
					return $final_return;
					break;
			}
			ob_start();
			echo $tabs."<div class='container{$fluid}'>";
			echo $tabs."	<div class='row equal-height'>";
			foreach($elements as $element)
			{
				$url      =  $element['url'];
				$image    =  $element['image'];
				$alt      =  $element['alt'];
				$caption  =  $element['caption'];
				echo $tabs."		<div class='col-xs-{$size} col-sm-{$size} col-md-{$size} col-lg-{$size}'>";
				echo "{$tabs}			<div class='thumbnail'>";
				echo "{$tabs}				<img src='{$image}' alt='{$alt}'>";
				echo "{$tabs}				<div class='caption'>";
				echo "{$tabs}					<h3>".static::link($url, $alt)."</h3>";
				echo "{$tabs}					<p>{$caption}</p>";
				echo "{$tabs}				</div>";
				echo "{$tabs}			</div>";
				echo $tabs."		</div>";
			}
			echo $tabs."	</div>";
			echo $tabs."</div>";
			return ob_get_clean();
		}

		/**
		 * Create an alert div
		 *
		 * @param   string  $text          a text to show in the alert
		 * @param   string  $link          a URL to use as an alert-link [Default: '']
		 * @param   string  $linkText      a description for the URL (used only if link is provided)
		 * @param   bool    $newTab        whether or not the link should open in a new tab (used only if link is provided) [Default: false]
		 * @param   string  $type          type of the alert. Use one of bootstrap's alert types [Default: 'warning']
		 * @param   bool    $allowDismiss  whether or not the alert div should be dismissable [Default: false]
		 * @param   int     $padding       how many tabs to add at each line's beginning [Default: 2]
		 *
		 * @return  string
		 */
		public static function alert($text, $link  =  '', $linkText  =  '', $newTab  =  false, $type  =  'warning', $allowDismiss  =  false, $padding  =  2)
		{
			$tabs  =  "\r\n".str_repeat('	', $padding);
			switch($type)
			{
				case 'danger':
				case 'success':
				case 'info':
				case 'warning':
					// All good
					break;
				default:
					$type  =  'warning';
					break;
			}
			if($linkText  ==  '') $linkText  =  $link;
			$tab           =  ($newTab) ? " target='_blank'" : '';
			if($link      !=  '') $link  =  " <a class='alert-link' href='{$link}'{$tab}>{$linkText}</a>";
			$dismiss       =  ($allowDismiss) ? "{$tabs}	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" : '';
			ob_start();
			echo "{$tabs}<div class='alert alert-{$type} fade in'>{$dismiss}";
			echo "{$tabs}	{$text}{$link}";
			echo "{$tabs}</div>";
			return ob_get_clean();
		}

		/**
		 * Create drop-menus
		 *
		 * @param   string  $toggleText    a text to show on the toggle button
		 * @param   array   $items         items to add to the drop menu
		 * @param   bool    $downMenu      whether the menu should be down or up
		 * @param   int     $padding       how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool    $newLineStart  whether or not to start in a new line [Default: false]
		 *
		 * @return  string
		 */
		public static function dropmenu($toggleText, $items, $downMenu  =  true, $padding  =  2, $newLineStart  =  false, $type  =  'default',  $extraClasses  =  '')
		{
			if(!is_array($items)) return '';
			$tabs          =  "\r\n".str_repeat('	', $padding);
			$startPad      =  ($newLineStart) ? $tabs : '';
			$menu          =  ($downMenu) ? 'down' : 'up';
			$id            =  \APLib\Extras::RandomString();
			$extraClasses  =  ($extraClasses  !=  '') ? ' '.$extraClasses : '';
			ob_start();
			echo "{$startPad}<div class='drop{$menu}'>";
			echo "{$tabs}	<button class='btn btn-{$type}{$extraClasses} dropdown-toggle' type='button' id='{$id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
			echo "{$tabs}		{$toggleText}";
			echo "{$tabs}		<span class='caret'></span>";
			echo "{$tabs}	</button>";
			echo "{$tabs}	<ul class='dropdown-menu' role='menu' aria-labelledby='{$id}'>";
			foreach($items as $item)
			{
				switch($item[0])
				{
					case 'divider':
						$a  =  "<li role='separator' class='divider'></li>";
						break;

					case 'header':
						$a  =  "<li class='dropdown-header'>{$item[1]}</li>";
						break;

					case 'disabled':
						$a  =  "<li class='disabled'><a href='#'>{$item[1]}</a></li>";
						break;

					default:
						$a  =  "<li><a href='{$item[0]}'>{$item[1]}</a></li>";
						break;
				}
				echo "{$tabs}		{$a}";
			}
			echo "{$tabs}	</ul>";
			echo "{$tabs}</div>";
			\APLib\Response\Body\JavaScript::add("$('.drop{$menu}').on('show.bs.dropdown', function(event){");
			\APLib\Response\Body\JavaScript::add("	var all = $(this).children();");
			\APLib\Response\Body\JavaScript::add("	$(all[1]).css('left', $(all[0]).offset().left);");
			\APLib\Response\Body\JavaScript::add("});");
			return ob_get_clean();
		}

		/**
		 * Create a button group
		 *
		 * @param   array   $buttons       a collection of buttons
		 * @param   int     $padding       how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool    $newLineStart  whether or not to start in a new line [Default: false]
		 * @param   string  $ariaLabel     aria-label text
		 * @param   string  $extraClasses  extra classes to append to the button group
		 *
		 * @return  string
		 */
		public static function buttongroup($buttons, $padding  =  2, $newLineStart  =  false, $ariaLabel  =  '', $extraClasses  =  '', $verticalGroup  =  false, $justifiedGroup  =  false)
		{
			if(!is_array($buttons)) return '';
			$tabs          =  "\r\n".str_repeat('	', $padding);
			$startPad      =  ($newLineStart) ? $tabs : '';
			$extraClasses  =  ($extraClasses  !=  '') ? ' '.$extraClasses : '';
			$vertical      =  ($verticalGroup) ? '-vertical' : '';
			$justified     =  ($justifiedGroup) ? ' btn-group-justified' : '';
			ob_start();
			echo "{$startPad}<div class='btn-group{$vertical}{$justified}{$extraClasses}' role='group' aria-label='{$ariaLabel}'>";
			foreach($buttons as $button)
			{
				if(array_key_exists('items', $button))
				{
					$type          =  (isset($button['type'])) ? $button['type'] : 'default';
					$extraClasses  =  (isset($button['extra classes'])) ? ' '.$button['extra classes'] : '';
					echo "{$tabs}		<div class='btn-group' role='group'>";
					echo "{$tabs}			<button type='button' class='btn btn-{$type}{$extraClasses} dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
					echo "{$tabs}				{$button['label']}";
					echo "{$tabs}				<span class='caret'></span>";
					echo "{$tabs}			</button>";
					echo "{$tabs}			<ul class='dropdown-menu'>";
					foreach($button['items'] as $item)
					{
						switch($item[0])
						{
							case 'divider':
								$a  =  "<li role='separator' class='divider'></li>";
								break;

							case 'header':
								$a  =  "<li class='dropdown-header'>{$item[1]}</li>";
								break;

							case 'disabled':
								$a  =  "<li class='disabled'><a href='#'>{$item[1]}</a></li>";
								break;

							default:
								$a  =  "<li><a href='{$item[0]}'>{$item[1]}</a></li>";
								break;
						}
						echo "{$tabs} 			{$a}";
					}
					echo "{$tabs}			</ul>";
					echo "{$tabs}		</div>";
				}
				else
				{
					$type          =  (isset($button['type'])) ? $button['type'] : 'default';
					$extraClasses  =  (isset($button['extra classes'])) ? ' '.$button['extra classes'] : '';
					$action        =  $button['action'];
					$label         =  $button['label'];
					if($justifiedGroup)
					{
						echo "{$tabs}		<div class='btn-group' role='group'>";
						echo "{$tabs}			<button type='button' class='btn btn-{$type}{$extraClasses}' onclick='{$action}'>{$label}</button>";
						echo "{$tabs}		</div>";
					}
					else
					{
						echo "{$tabs}		<button type='button' class='btn btn-{$type}{$extraClasses}' onclick='{$action}'>{$label}</button>";
					}
				}
			}
			echo "{$tabs}</div>";
			return ob_get_clean();
		}

		/**
		 * Create a button toolbar
		 *
		 * @param   array   $groups        a collection of button groups
		 * @param   int     $padding       how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool    $newLineStart  whether or not to start in a new line [Default: false]
		 * @param   string  $ariaLabel     aria-label text
		 *
		 * @return  string
		 */
		public static function buttontoolbar($groups, $padding  =  2, $newLineStart  =  false, $ariaLabel  =  '')
		{
			if(!is_array($groups)) return '';
			$tabs      =  "\r\n".str_repeat('	', $padding);
			$startPad  =  ($newLineStart) ? $tabs : '';
			ob_start();
			echo "{$startPad}<div class='btn-toolbar' role='toolbar' aria-label='{$ariaLabel}'>";
			foreach($groups as $group)
			{
				if(array_key_exists('buttons', $group))
				{
					$groupLabel    =  (isset($group['label'])) ? $group['label'] : '';
					$extraClasses  =  (isset($group['extra classes'])) ? ' '.$group['extra classes'] : '';
					$vertical      =  (isset($group['vertical'])  &&  $group['vertical']) ? '-vertical' : '';
					$justified     =  (isset($group['justified'])  &&  $group['justified']) ? ' btn-group-justified' : '';
					echo "{$tabs}	<div class='btn-group{$vertical}{$justified}{$extraClasses}' role='group' aria-label='$groupLabel'>";
					foreach($group['buttons'] as $button)
					{
						if(array_key_exists('items', $button))
						{
							$type          =  (isset($button['type'])) ? $button['type'] : 'default';
							$extraClasses  =  (isset($button['extra classes'])) ? ' '.$button['extra classes'] : '';
							echo "{$tabs}		<div class='btn-group' role='group'>";
							echo "{$tabs}			<button type='button' class='btn btn-{$type}{$extraClasses} dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
      				echo "{$tabs}				{$button['label']}";
							echo "{$tabs}				<span class='caret'></span>";
							echo "{$tabs}			</button>";
							echo "{$tabs}			<ul class='dropdown-menu'>";
							foreach($button['items'] as $item)
							{
								switch($item[0])
								{
									case 'divider':
										$a  =  "<li role='separator' class='divider'></li>";
										break;

									case 'header':
										$a  =  "<li class='dropdown-header'>{$item[1]}</li>";
										break;

									case 'disabled':
										$a  =  "<li class='disabled'><a href='#'>{$item[1]}</a></li>";
										break;

									default:
										$a  =  "<li><a href='{$item[0]}'>{$item[1]}</a></li>";
										break;
								}
								echo "{$tabs} 			{$a}";
							}
							echo "{$tabs}			</ul>";
							echo "{$tabs}		</div>";
						}
						else
						{
							$type           =  (isset($button['type'])) ? $button['type'] : 'default';
							$extraClasses   =  (isset($button['extra classes'])) ? ' '.$button['extra classes'] : '';
							$action         =  $button['action'];
							$label          =  $button['label'];
							if($justified  !=  '')
							{
								echo "{$tabs}		<div class='btn-group' role='group'>";
								echo "{$tabs}			<button type='button' class='btn btn-{$type}{$extraClasses}' onclick='{$action}'>{$label}</button>";
								echo "{$tabs}		</div>";
							}
							else
							{
								echo "{$tabs}		<button type='button' class='btn btn-{$type}{$extraClasses}' onclick='{$action}'>{$label}</button>";
							}
						}
					}
					echo "{$tabs}	</div>";
				}
			}
			echo "{$tabs}</div>";
			return ob_get_clean();
		}

		/**
		 * Create an input $group
		 *
		 * @param   array   $input         an array containing: left input addon, right input addon[, input placeholder, input id]
		 * @param   string  $type          input type [Default: text]
		 * @param   int     $padding       how many tabs to add at each line's beginning [Default: 3]
		 * @param   bool    $newLineStart  whether or not to start in a new line [Default: false]
		 * @param   string  $extraClasses  extra classes to append to the input group
		 *
		 * @return  string
		 */
		public static function inputgroup($input, $type  =  'text', $padding  =  3, $newLineStart  =  false, $extraClasses  =  '')
		{
			if(!is_array($input)) return '';
			$tabs           =  "\r\n".str_repeat('	', $padding);
			$startPad       =  ($newLineStart) ? $tabs : '';
			$extraClasses   =  ($extraClasses  !=  '') ? ' '.$extraClasses : '';
			$leftAddon      =  '';
			$rightAddon     =  '';
			if(array_key_exists('left addon', $input))
			{
				if($input['left addon']['type']  ==  'buttons')
				{
					$leftAddon   =  "{$tabs}	<div class='input-group-btn'>";
					foreach($input['left addon']['items'] as $button)
					{
						$leftAddon  .=  "{$tabs}		<button onclick='{$button['action']}' class='btn btn-{$button['input type']}{$button['extra classes']}'>{$button['label']}</button>";
					}
					$leftAddon  .=  "{$tabs}	</div>";
				}
				elseif($input['left addon']['type']  ==  'button')
				{
					$leftAddon   =  "{$tabs}	<span class='input-group-btn'>";
					$leftAddon  .=  "{$tabs}		<button onclick='{$input['left addon']['action']}' class='btn btn-{$input['left addon']['input type']}{$input['left addon']['extra classes']}'>{$input['left addon']['label']}</button>";
					$leftAddon  .=  "{$tabs}	</span>";
				}
				elseif($input['left addon']['type']  ==  'dropmenu')
				{
					if(!array_key_exists('items', $input['left addon']))
					{
						$thrown  =  ob_get_clean();
						return '';
					}
					$leftAddon   =  "{$tabs}	<div class='input-group-btn'>";
					$leftAddon  .=  "{$tabs}		<button class='btn btn-{$input['left addon']['input type']}{$input['left addon']['extra classes']} dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
					$leftAddon  .=  "{$tabs}			{$input['left addon']['label']}";
					$leftAddon  .=  "{$tabs}			<span class='caret'></span>";
					$leftAddon  .=  "{$tabs}		</button>";
					$leftAddon  .=  "{$tabs}		<ul class='dropdown-menu'>";
					foreach($input['left addon']['items'] as $item)
					{
						switch($item[0])
						{
							case 'divider':
								$a  =  "<li role='separator' class='divider'></li>";
								break;

							case 'header':
								$a  =  "<li class='dropdown-header'>{$item[1]}</li>";
								break;

							case 'disabled':
								$a  =  "<li class='disabled'><a href='#'>{$item[1]}</a></li>";
								break;

							default:
								$a  =  "<li><a href='{$item[0]}'>{$item[1]}</a></li>";
								break;
						}
						$leftAddon  .=  "{$tabs}			{$a}";
					}
					$leftAddon    .=  "{$tabs}		</ul>";
					$leftAddon    .=  "{$tabs}	</div>";
				}
				else
				{
					$leftAddon   =  "{$tabs}	<span class='input-group-addon'>";
					$leftAddon  .=  "{$tabs}		<input type='{$input['left addon']['type']}' aria-label='{$input['left addon']['label']}' onchange='{$input['left addon']['action']}'>";
					$leftAddon  .=  "{$tabs}	</span>";
				}
			}
			if(array_key_exists('right addon', $input))
			{
				if($input['right addon']['type']  ==  'buttons')
				{
					$rightAddon   =  "{$tabs}	<div class='input-group-btn'>";
					foreach($input['right addon']['items'] as $button)
					{
						$rightAddon  .=  "{$tabs}		<button onclick='{$button['action']}' class='btn btn-{$button['input type']}{$button['extra classes']}'>{$button['label']}</button>";
					}
					$rightAddon  .=  "{$tabs}	</div>";
				}
				elseif($input['right addon']['type']  ==  'button')
				{
					$rightAddon   =  "{$tabs}	<span class='input-group-btn'>";
					$rightAddon  .=  "{$tabs}		<button onclick='{$input['right addon']['action']}' class='btn btn-{$input['right addon']['input type']}{$input['right addon']['extra classes']}'>{$input['right addon']['label']}</button>";
					$rightAddon  .=  "{$tabs}	</span>";
				}
				elseif($input['right addon']['type']  ==  'dropmenu')
				{
					if(!isset($input['right addon']['items']))
					{
						$thrown  =  ob_get_clean();
						return '';
					}
					$rightAddon   =  "{$tabs}	<div class='input-group-btn'>";
					$rightAddon  .=  "{$tabs}		<button class='btn btn-{$input['right addon']['input type']}{$input['right addon']['extra classes']} dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
					$rightAddon  .=  "{$tabs}			{$input['right addon']['label']}";
					$rightAddon  .=  "{$tabs}			<span class='caret'></span>";
					$rightAddon  .=  "{$tabs}		</button>";
					$rightAddon  .=  "{$tabs}		<ul class='dropdown-menu'>";
					foreach($input['right addon']['items'] as $item)
					{
						switch($item[0])
						{
							case 'divider':
								$a  =  "<li role='separator' class='divider'></li>";
								break;

							case 'header':
								$a  =  "<li class='dropdown-header'>{$item[1]}</li>";
								break;

							case 'disabled':
								$a  =  "<li class='disabled'><a href='#'>{$item[1]}</a></li>";
								break;

							default:
								$a  =  "<li><a href='{$item[0]}'>{$item[1]}</a></li>";
								break;
						}
						$rightAddon  .=  "{$tabs}			{$a}";
					}
					$rightAddon    .=  "{$tabs}		</ul>";
					$rightAddon    .=  "{$tabs}	</div>";
				}
				else
				{
					$rightAddon   =  "{$tabs}	<span class='input-group-addon'>";
					$rightAddon  .=  "{$tabs}		<input type='{$input['right addon']['type']}' aria-label='{$input['right addon']['label']}' onchange='{$input['right addon']['action']}'>";
					$rightAddon  .=  "{$tabs}	</span>";
				}
			}
			$placeHolder    =  (array_key_exists('place holder', $input)) ? " placeholder='{$input['place holder']}'" : '';
			$id             =  (array_key_exists('id', $input)) ? " id='{$input['id']}'" : '';
			switch($type)
			{
				case 'text':
					// All good
					break;

				default:
					$type  =  'text';
					break;
			}
			ob_start();
			echo "{$startPad}<div class='input-group{$extraClasses}'>";
			echo "{$leftAddon}{$tabs}	<input type='{$type}' class='form-control'{$id}{$placeHolder}>{$rightAddon}";
			echo "{$tabs}</div>";
			return ob_get_clean();
		}

		/**
		 * Create navs (Tabs/Pills)
		 *
		 * @param   array   $navs          navs to create
		 * @param   int     $padding       how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool    $tabsNotPills  whether it's tabs or pills [Default: true]
		 * @param   string  $extraClasses  extra classes to append to the main nav
		 *
		 *
		 * @return  string
		 */
		public static function navs($navs, $padding  =  2,  $tabsNotPills  =  true, $extraClasses  =  '')
		{
			if(!is_array($navs)) return '';
			$tabs           =  "\r\n".str_repeat('	', $padding);
			$extraClasses   =  ($extraClasses  !=  '') ? ' '.$extraClasses : '';
			$type           =  ($tabsNotPills) ? 'tabs' : 'pills';
			ob_start();
			echo "{$tabs}<ul class='nav nav-{$type}{$extraClasses}'>";
			$activeChoosen  =  false;
			foreach($navs as $nav)
			{
				if(isset($nav['dropmenu']))
				{
					if(!isset($nav['items']))
					{
						$thrown  =  ob_get_clean();
						return '';
					}
					echo "{$tabs}	<li role='presentation' class='dropdown'>";
					echo "{$tabs}		<a class='dropdown-toggle' data-toggle='dropdown' href='#' role='button' aria-haspopup='true' aria-expanded='false'>";
					echo "{$tabs}			{$nav['label']}";
					echo "{$tabs}			<span class='caret'></span>";
					echo "{$tabs}		</a>";
					echo "{$tabs}		<ul class='dropdown-menu'>";
					foreach($nav['items'] as $item)
					{
						switch($item[0])
						{
							case 'divider':
								$a  =  "<li role='separator' class='divider'></li>";
								break;

							case 'header':
								$a  =  "<li class='dropdown-header'>{$item[1]}</li>";
								break;

							case 'disabled':
								$a  =  "<li class='disabled'><a href='#'>{$item[1]}</a></li>";
								break;

							default:
								$a  =  "<li><a href='{$item[0]}'>{$item[1]}</a></li>";
								break;
						}
						echo "{$tabs}			{$a}";
					}
					echo "{$tabs}		</ul>";
					echo "{$tabs}	</li>";
				}
				else
				{
					$action  =  $nav['action'];
					$active  =  '';
					if(isset($nav['active']) && !$activeChoosen)
					{
						$active         =  " class='active'";
						$activeChoosen  =  true;
					}
					if(isset($nav['disabled']))
					{
						$action  =  '#'.$action;
						$active  =  ($active  !=  '') ? " class='active disabled'" : " class='disabled'";
					}
					echo "{$tabs}	<li role='presentation'{$active}><a href='{$action}'>{$nav['label']}</a></li>";
				}
			}
			echo "{$tabs}</ul>";
			return ob_get_clean();
		}

		/**
		 * Create a navbar
		 *
		 * @param   array   $navs          navs to include in the navbar
		 * @param   array   $brand         an array containing: title/alt & image, action[, id, collapses[, sr text]]
		 * @param   int     $padding       how many tabs to add at each line's beginning [Default: 2]
		 * @param   bool    $reverse       whether or not the navbar should be reversed [Default: false]
		 * @param   string  $extraClasses  extra classes to append to the navbar
		 *
		 * @return  string
		 */
		public static function navbar($navs, $brand, $padding  =  2, $reverse  =  false, $extraClasses  =  '')
		{
			if(!is_array($navs)) return '';
			if(!is_array($brand)) return '';
			$tabs           =  "\r\n".str_repeat('	', $padding);
			$extraClasses   =  ($extraClasses  !=  '') ? ' '.$extraClasses : '';
			$type           =  ($reverse) ? 'reverse' : 'default';
			$id             =  (isset($brand['id'])) ? $brand['id'] : \APLib\Extras::RandomString();
			ob_start();
			echo "{$tabs}<nav class='navbar navbar-{$type}{$extraClasses}' id='{$id}'>";
			echo "{$tabs}	<div class='container-fluid'>";
			$collapse       =  "";
			if(isset($brand['collapses']))
			{
				$collapse     =  "{$tabs}			<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#{$id}Col' aria-expanded='false'>";
				if(isset($brand['sr text'])) $collapse  .=  "{$tabs}				<span class='sr-only'>{$brand['sr text']}</span>";
				for($i=0; $i < 3; $i++) $collapse       .=  "{$tabs}				<span class='icon-bar'></span>";
				$collapse    .=  "{$tabs}			</button>";
			}
			echo "{$tabs}		<div class='navbar-header'>{$collapse}";
			echo "{$tabs}			<a class='navbar-brand' href='{$brand['action']}'>";
			if(isset($brand['title']))
			{
				echo "{$tabs}				{$brand['title']}";
			}
			else
			{
				echo "{$tabs}				<img alt='{$brand['alt']}' src='{$brand['image']}'>";
			}
			echo "{$tabs}			</a>";
			echo "{$tabs}		</div>";
			echo "{$tabs}		<div class='collapse navbar-collapse' id='{$id}Col'>";
			$activeChoosen  =  false;
			foreach($navs as $nav)
			{
				$direction  =  '';
				$direction  =  (isset($nav['left'])) ? ' navbar-left' : '';
				$direction  =  (isset($nav['right']) && $direction  ==  '') ? ' navbar-right' : $direction;
				if(isset($nav['menu']))
				{
					echo "{$tabs}			<ul class='nav navbar-nav{$direction}'>";
					if(isset($nav['dropmenu']))
					{
						if(!isset($nav['items']))
						{
							$thrown  =  ob_get_clean();
							return '';
						}
						echo "{$tabs}				<li role='presentation' class='dropdown'>";
						echo "{$tabs}					<a class='dropdown-toggle' data-toggle='dropdown' href='#' role='button' aria-haspopup='true' aria-expanded='false'>";
						echo "{$tabs}						{$nav['label']}";
						echo "{$tabs}						<span class='caret'></span>";
						echo "{$tabs}					</a>";
						echo "{$tabs}					<ul class='dropdown-menu'>";
						foreach($nav['items'] as $item)
						{
							switch($item[0])
							{
								case 'divider':
									$a  =  "<li role='separator' class='divider'></li>";
									break;

								case 'header':
									$a  =  "<li class='dropdown-header'>{$item[1]}</li>";
									break;

								case 'disabled':
									$a  =  "<li class='disabled'><a href='#'>{$item[1]}</a></li>";
									break;

								default:
									$a  =  "<li><a href='{$item[0]}'>{$item[1]}</a></li>";
									break;
							}
							echo "{$tabs}						{$a}";
						}
						echo "{$tabs}					</ul>";
						echo "{$tabs}				</li>";
					}
					else
					{
						$action  =  $nav['action'];
						$active  =  '';
						if(isset($nav['active']) && !$activeChoosen)
						{
							$active         =  " class='active'";
							$activeChoosen  =  true;
						}
						if(isset($nav['disabled']))
						{
							$action  =  '#'.$action;
							$active  =  ($active !=  '') ? " class='active disabled'" : " class='disabled'";
						}
						echo "{$tabs}				<li role='presentation'{$active}><a href='{$action}'>{$nav['label']}</a></li>";
					}
					echo "{$tabs}			</ul>";
				}
				else
				{
					if(isset($nav['inputs']))
					{
						$method  =  (isset($nav['method'])) ? " method='{$nav['method']}'" : '';
						echo "{$tabs}			<form class='navbar-form{$direction}' {$method}>";
						foreach($nav['inputs'] as $input)
						{
							if(!isset($input['submit']))
							{
								$placeHolder  =  (isset($input['place holder'])) ? " placeholder='{$input['place holder']}'" : '';
								$value        =  (isset($input['value'])) ? $input['value'] : '';
								if(isset($input['name']))
								{
									echo "{$tabs}				<div class='form-group'>";
									echo "{$tabs}					<input type='text' name='{$input['name']}' class='form-control'{$placeHolder} value='{$value}'>";
									echo "{$tabs}				</div>";
								}
							}
							else
							{
								echo "{$tabs}				<button type='submit' class='btn btn-default'>{$input['submit']}</button>";
							}
						}
						echo "{$tabs}			</form>";
					}
					elseif(isset($nav['button']))
					{
						echo "{$tabs}			<button type='button' class='btn btn-default navbar-btn' onclick='{$nav['action']}'>{$nav['label']}</button>";
					}
					elseif(isset($nav['text']))
					{
						$label  =  (isset($nav['link'])) ? $nav['label']." <a href='{$nav['link action']}' class='navbar-link'>{$nav['link label']}</a>" : $nav['label'];
						echo "{$tabs}			<p class='navbar-text{$direction}'>{$label}</p>";
					}
				}
			}
			echo "{$tabs}		</div>";
			echo "{$tabs}	</div>";
			echo "{$tabs}</nav>";
			return ob_get_clean();
		}

		/**
		 * Create a breadcrumb navigational hierarchy
		 *
		 * @param   array  $navs     navigational hierarchy items
		 * @param   int    $padding  how many tabs to add at each line's beginning [Default: 2]
		 *
		 * @return  string
		 */
		public static function breadcrumb($navs, $padding  =  2)
		{
			if(!is_array($navs)) return '';
			$tabs           =  "\r\n".str_repeat('	', $padding);
			ob_start();
			echo "{$tabs}<ol class='breadcrumb'>";
			for($i=0;$i < (sizeof($navs)-1);$i++)
			{
				echo "{$tabs}	<li><a href='{$navs[$i][0]}'>{$navs[$i][1]}</a></li>";
			}
			echo "{$tabs}	<li class='active'>{$navs[(sizeof($navs)-1)][1]}</li>";
			echo "{$tabs}</ol>";
			return ob_get_clean();
		}

		/**
		 * Create a progress bars
		 *
		 * @param   array   $bars     bars to create
		 * @param   int     $padding  how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $id       element's id [Default: NO ID]
		 *
		 */
		public static function progress($bars, $padding  =  2, $id  =  '')
		{
			if(!is_array($bars)) return '';
			$tabs  =  "\r\n".str_repeat('	', $padding);
			$id    =  ($id  !=  '') ? " id='{$id}'" : '';
			ob_start();
			echo "{$tabs}<div class='progress'{$id}>";
			foreach($bars as $bar)
			{
				$extraClasses  =  (isset($bar['extra classes'])) ? ' '.$bar['extra classes'] : '';
				$valuenow      =  (isset($bar['value now'])) ? " aria-valuenow='{$bar['value now']}'" : '';
				$valuemin      =  (isset($bar['value min'])) ? " aria-valuemin='{$bar['value min']}'" : '';
				$valuemax      =  (isset($bar['value max'])) ? " aria-valuemax='{$bar['value max']}'" : '';
				echo "{$tabs}	<div class='progress-bar{$extraClasses}' role='progressbar'{$valuenow}{$valuemin}{$valuemax} style='width: {$bar['width']}%'>";
				if(isset($bar['label'])) echo "{$tabs}		{$bar['label']}";
				echo "{$tabs}	</div>";
			}
			echo "{$tabs}</div>";
			return ob_get_clean();
		}
	}
?>
