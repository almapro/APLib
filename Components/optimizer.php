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
   * Optimizer - A class to optimize performance
   */
  class Optimizer
  {

    /**
     * @var  object  $cacheItem  a variable to hold cache item object
     */
    private static $cacheItem = null;

    /**
     * Initiate Optimizer
     *
     * @return  void
     */
    public static function init()
    {
      //
    }

    /**
     * Run Optimizer
     *
     * @return  void
     */
    public static function run()
    {
      if(\APLib\Config::get('Optimize') && \APLib\Config::get('Optimize name') != null)
        static::$cacheItem = \APLib\Cache::getItem(\APLib\Config::get('Optimize name'));
      if(static::$cacheItem == null) return;
      $booster_js_path  = APLibPath."boost-".\APLib\Config::get('Optimize name')."-js.php";
      $booster_js_html  = APLibHTML."boost-".\APLib\Config::get('Optimize name')."-js.php";
      $booster_css_path = APLibPath."boost-".\APLib\Config::get('Optimize name')."-css.php";
    	$booster_css_html = APLibHTML."boost-".\APLib\Config::get('Optimize name')."-css.php";
      $files            = array();
      $headers          = \APLib\Response\Header::items();
      \APLib\Response\Header::reset();
			foreach(\APLib\Response\Header\Link::files() as $file)
			{
        if(strpos($file, "http://") !== false || strpos($file, "https://") !== false) continue;
        \APLib\Response\Header\Link::remove($file);
        $files['css'][] = $file;
			}
      \APLib\Response\Header::add("<link rel='stylesheet' href=\"".$booster_css_html."\" async />");
			foreach(\APLib\Response\Header\Script::files() as $file)
			{
        if(strpos($file, "http://") !== false || strpos($file, "https://") !== false) continue;
        \APLib\Response\Header\Script::remove($file);
        $files['js'][] = $file;
			}
      \APLib\Response\Header::add("<script type='text/javascript' src=\"".$booster_js_html."\"></script>");
      foreach($headers as $header)
      {
        \APLib\Response\Header::add($header);
      }
      if(!static::$cacheItem->isHit() || static::$cacheItem->isExpired() || static::$cacheItem->isEmpty() || static::$cacheItem->get() != $files)
      {
        static::$cacheItem->set($files);
        \APLib\Cache::save(static::$cacheItem);
      }
      $name             = \APLib\Config::get("Optimize name");
      $booster_content  = "<?php\r\n";
      $booster_content .= "\$name = '{$name}';\r\n";
      $booster_content .= "require 'core.php';\r\n";
      $booster_content .= "\APLib\Core::init();\r\n";
      $booster_content .= "\$item = \APLib\Cache::getItem(\$name);\r\n";
      $booster_content .= "if(\$item->isHit() && !\$item->isExpired() && !\$item->isEmpty()){\r\n";
      $booster_content .= "	header('Content-Type: %TYPE%');\r\n";
      $booster_content .= " header('Cache-Control: max-age=864000');\r\n";
      $booster_content .= " \$modified_since = (isset(\$_SERVER[\"HTTP_IF_MODIFIED_SINCE\"]) ? strtotime(\$_SERVER[\"HTTP_IF_MODIFIED_SINCE\"]) : false );\r\n";
      $booster_content .= " \$etagHeader     = (isset(\$_SERVER[\"HTTP_IF_NONE_MATCH\"]) ? trim(\$_SERVER[\"HTTP_IF_NONE_MATCH\"]) : false );\r\n";
      $booster_content .= " \$last_modified  = 0;\r\n";
      $booster_content .= " \$files          = \$item->get();\r\n";
      $booster_content .= " \$content        = '';\r\n";
      $booster_content .= " foreach(\$files['%EXT%'] as \$file){\r\n";
      $booster_content .= "   \$file_modify_d = filemtime('../'.\$file);\r\n";
      $booster_content .= "   \$last_modified = (\$file_modify_d > \$last_modified) ? \$file_modify_d : \$last_modified;\r\n";
      $booster_content .= "   \$content      .= file_get_contents('../'.\$file);\r\n";
      $booster_content .= " }\r\n";
      $booster_content .= " \$etag           = sprintf( '\"%s-%s-%s\"', \$last_modified, md5(\$content), 'gzip');\r\n";
      $booster_content .= " header(\"Last-Modified: \".gmdate(\"D, d M Y H:i:s\", \$last_modified).\" GMT\");\r\n";
      $booster_content .= " header(\"ETAG: \".\$etag);\r\n";
      $booster_content .= " if((int)\$modified_since === (int)\$last_modified && \$etag === \$etagHeader){\r\n";
      $booster_content .= "   header(\"HTTP/1.1 304 Not Modified\");\r\n";
      $booster_content .= "   die();\r\n";
      $booster_content .= " }\r\n";
      $booster_content .= "	die(\$content);\r\n";
			$booster_content .= "}\r\n";
			$booster_content .= "?>";
      $booster_js_content  = str_replace('%TYPE%', 'application/javascript', $booster_content);
      $booster_js_content  = str_replace('%EXT%', 'js', $booster_js_content);
      $booster_css_content = str_replace('%TYPE%', 'text/css', $booster_content);
      $booster_css_content = str_replace('%EXT%', 'css', $booster_css_content);
			if(file_exists($booster_js_path))
			{
				if(file_get_contents($booster_js_path) == $booster_js_content) goto okJS;
			}
			file_put_contents($booster_js_path, $booster_js_content);
			okJS:
			if(file_exists($booster_css_path))
			{
				if(file_get_contents($booster_css_path) == $booster_css_content) goto okCSS;
			}
			file_put_contents($booster_css_path, $booster_css_content);
			okCSS:
    }
  }
?>
