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
   * FancyBox - A class to create fancyBox elements
   */
  class FancyBox
  {

		/**
		 * Create a thumbnail
		 *
		 * @param   string  $url       a URL to use of type is not iframe, ajax or inline
		 * @param   string  $caption   a paragraph text to describe the image
		 * @param   int     $padding   how many tabs to add at each line's beginning [Default: 2]
		 * @param   string  $type      type of content [Default: images]
		 * @param   string  $width     width of the content [Default: 0]
		 * @param   string  $height    height of the content [Default: 0]
		 * @param   string  $srcset    data-srcset value [Default: '']
		 * @param   string  $src       source of data if type is inline, ajax or iframe [Default: '']
		 * @param   string  $fancybox  fancybox value [Default: '']
		 *
		 * @return  string
		 */
    public static function thumbnail($url, $caption  =  '', $padding  =  2, $type  =  'images', $width  =  0, $height  =  0, $srcset  =  '', $src  =  '', $fancybox  =  '')
    {
      $tabs        =  "\r\n".str_repeat('	', $padding);
      $centerData  =  $caption;
      switch($type)
      {
        case 'inline':
        case 'ajax':
        case 'iframe':
          $url     =  'javascript:;';
          $srcset  =  '';
          $src     =  ($src  !=  '') ? " data-src='{$src}'" : '';
          break;
        case 'images':
          $srcset      =  ($srcset  !=  '') ? " data-srcset='{$srcset}'" : '';
          $src         =  '';
          $centerData  =  "<img src='{$url}'>";
          break;
      }
      $caption   =  ($caption  !=  '') ? " data-caption='{$caption}'":'';
      $width     =  ($width    !=  0) ? " data-width='{$width}'" : '';
      $height    =  ($height   !=  0) ? " data-height='{$height}'" : '';
      $fancybox  =  ($fancybox   !=  '') ? " data-fancybox='{$fancybox}'" : ' data-fancybox';
			ob_start();
      echo "{$tabs}<a{$fancybox} data-type='{$type}'{$caption}{$width}{$height}{$srcset}{$src} href='{$url}'>";
      echo "{$tabs} {$centerData}";
      echo "{$tabs}</a>";
      return ob_get_clean();
    }

    /**
     * Create a group of thumbnail
     *
     * @param   array   $thumbs    an array containing thumbnails
     * @param   string  $fancybox  fancybox value
		 * @param   int     $padding   how many tabs to add at each line's beginning [Default: 2]
     *
     * @return  string
     */
    public static function group($thumbs, $fancybox, $padding  =  2)
    {
      if(!is_array($thumbs) || sizeof($thumbs)  ==  0) return '';
      ob_start();
      foreach($thumbs as $thumbnail)
      {
        $url      =  $thumbnail['url'];
        $caption  =  (isset($thumbnail['caption'])) ? $thumbnail['caption'] : '';
        $type     =  (isset($thumbnail['type']))    ? $thumbnail['type']    : 'images';
        $width    =  (isset($thumbnail['width']))   ? $thumbnail['width']   : 0;
        $height   =  (isset($thumbnail['height']))  ? $thumbnail['height']  : 0;
        $srcset   =  (isset($thumbnail['srcset']))  ? $thumbnail['srcset']  : '';
        $src      =  (isset($thumbnail['src']))     ? $thumbnail['src']     : '';
        echo static::thumbnail($url, $caption, $padding, $type, $width, $height, $srcset, $src, $fancybox);
      }
      return ob_get_clean();
    }
  }

?>
