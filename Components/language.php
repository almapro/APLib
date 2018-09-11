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
   *
   */
  class Language Extends \APLib\Container
  {

    /**
     * @var  array  $items  an array to contain phrases
     */
    protected static $items  =  array();

    /**
     * @var  array  $langs  an array to contain languages
     */
    private static $langs  =  array();

    /**
     * Set languages and their codes
     *
     * @param   array  $langs  an array of languages & codes
     *
     * @return  void
     */
    public static function set($langs)
    {
      static::$langs  =  $langs;
    }

		/**
     * Get all languages
     *
     * @return  array
     */
    public static function langs()
    {
      return static::$langs;
    }

		/**
     * Get a language's name
     *
     * @param   string  $code  language's codes
     *
     * @return  string/null
     */
    public static function name($code)
    {
		foreach(static::$langs as $lang)
		{
			if($lang['code']  ==  $code) return $lang['name'];
		}
		return null;
    }

    /**
     * Get a phrase from a specific language
     *
     * @param   string       $placeholder  the phrase's placeholder
     * @param   null/string  $lang         language code [Default: FIRST LANGUAGE]
     *
     * @return  array/null
     */
    public static function get($placeholder, $lang  =  null)
    {
    	if(sizeof(static::$langs) == 0) return null;
		if($lang == null || sizeof(static::$langs) == 1)
		{
			$lang  =  static::$langs[0];
		}
		else
		{
			for($i=0; $i < sizeof(static::$langs); $i++)
			{
				if(static::$langs[$i]['code'] == $lang)
				{
					$lang  =  static::$langs[$i];
					break;
				}
	  		}
		}
		foreach(static::$items as $phrase)
		{
    		if(isset($phrase['placeholder']) && $phrase['placeholder'] == $placeholder && $phrase['lang'] == $lang['code']) return $phrase;
		}
    }

	/**
	 * Parse a string with all found phrases in a specific language
	 *
	 * @param   string       $data  a string to parse
	 * @param   null/string  $lang  language code [Default: FIRST LANGUAGE]
	 *
	 * @return  string
	 */
	public static function parse($data, $lang  =  null)
	{
		if(sizeof(static::$langs) == 0) return $data;
		if($lang == null || sizeof(static::$langs) == 1)
		{
			$lang  =  static::$langs[0];
		}
		else
		{
			for($i=0; $i < sizeof(static::$langs); $i++)
			{
				if(static::$langs[$i]['code'] == $lang)
				{
					$lang  =  static::$langs[$i];
					break;
				}
			}
		}
		foreach(static::$items as $phrase)
		{
			if(strpos($data, $phrase['placeholder']) >= 0)
			{
				if(isset($phrase['placeholder']) && $phrase['lang'] == $lang['code']) $data  =  str_replace($phrase['placeholder'], $phrase['value'], $data);
			}
		}
		return $data;
	}
  }
?>
