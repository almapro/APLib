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
	* FOPO - Free Online PHP Obfuscator: http://www.fopo.com.ar/
	*/
	class FOPO
	{

		/**
     * Obfuscate PHP code using FOPO
     *
     * @param   string  $content  php code to obfuscate (including PHP tags '<?php & ?>')
     * @param   string  $key      a key to obfuscate with [Default: NOTTHING]
     *
     * @return  string/null
     */
    public static function obfuscate($content, $key  =  '')
    {
			try
			{
	      $ch = curl_init('http://www.fopo.com.ar/api/');
	      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	      curl_setopt($ch, CURLOPT_HEADER, 1);
	      $result = curl_exec($ch);
	      preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
	      $cookies = '';
	      foreach($matches[1] as $item)
				{
	        $cookies .= $item.'; ';
	      }
	      curl_setopt($ch, CURLOPT_COOKIE, $cookies);
	      curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
	      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=UTF-8"));
	      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('direction' => 'obfuscate', 'key' => $key, 'input' => $content)));
	      $html = curl_exec($ch);
	      curl_close($ch);
	      return json_decode($html)->output;
			}
			catch(\Exception $e)
			{
				\APLib\Logger::Error($e);
			}
			return null;
    }
	}
?>
