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
	* Security - Securing everything from here
	*/
	class Security
	{

		/**
		 * @var  bool  $hack  a variable to indicate a hacking attempt
		 */
		private static $hack  =  false;

		/**
		 * @var  int  $riskLevel  risk level indicator variable
		 */
		private static $riskLevel  =  0;

		/**
		 * @var  array  $params_array  an array to contain params of the request (if any)
		 */
		private static $params_array  =  array();

		/**
		 * @var  string  $user_agent  a variable to contain client's user agent
		 */
		public static $user_agent  =  '';

		/**
		 * Initiate Security
		 *
		 * @return  void
		 */
		public static function init()
		{
			static::$user_agent  =  (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
			if(\APLib\Config::get('Secure params') != null)
			{
				if(\APLib\Request\HTTP::post())
				{
					if(\APLib\Request\HTTP::json())
					{
						static::$params_array  =  \APLib\Request\HTTP::data();
					}
					else
					{
						static::$params_array  =  $_POST;
					}
				}
				else
				{
					static::$params_array  =  $_GET;
				}
				foreach(\APLib\Config::get('Secure params') as $param)
				{
					if(isset(static::$params_array[$param]))
					{
						static::Secure(static::$params_array[$param], true);
					}
				}
				if(static::$hack) \APLib\Config::set('Secure params\' warning', true);
			}
		}

		/**
		 * Return IP's info. URL: https://stackoverflow.com/questions/12553160/getting-visitors-country-from-their-ip
		 *
		 * @param   string/null  $ip           an IP to get info from [Default: null]
		 * @param   bool         $deep_detect  deeply detect IP address if no IP is provided [Default: true]
		 *
		 * @return  array/null
		 */
		public static function lookup($ip  =  null, $deep_detect  =  true)
		 {
			$continents  =  array(
				"AF"  =>  "Africa",
				"AN"  =>  "Antarctica",
				"AS"  =>  "Asia",
				"EU"  =>  "Europe",
				"OC"  =>  "Australia (Oceania)",
				"NA"  =>  "North America",
				"SA"  =>  "South America"
			);
			$info  =  null;
			if(filter_var($ip, FILTER_VALIDATE_IP) === false)
			{
				$ip    =  $_SERVER["REMOTE_ADDR"];
				if($deep_detect)
				{
					if(filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
						$ip  =  $_SERVER['HTTP_X_FORWARDED_FOR'];
					if(filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
						$ip  =  $_SERVER['HTTP_CLIENT_IP'];
				}
			}
			$gi = null;
			if($ip  ==  '::1' || $ip  ==  '127.0.0.1') return null;
			if(strpos($ip, ':') === false)
			{
				if(file_exists('/usr/share/GeoIP/GeoIPCity.dat'))
				{
					require_once \APLib\Config::get('composer loader');
					$gi   = geoip_open("/usr/share/GeoIP/GeoIPCity.dat", GEOIP_STANDARD);
				}
				elseif(file_exists('/usr/local/share/GeoIP/GeoIPCity.dat'))
				{
					require_once \APLib\Config::get('composer loader');
					$gi   = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat", GEOIP_STANDARD);
				}
				if($gi != null)
				{
					global $GEOIP_REGION_NAME;
					$record = GeoIP_record_by_addr($gi, $ip);
					if($record  ==  null) return null;
					$info = array(
						'ip'              =>  $ip,
						'city'            =>  $record->city,
						'state'           =>  $GEOIP_REGION_NAME[$record->country_code][$record->region],
						'country'         =>  $record->country_name,
						'country_code'    =>  $record->country_code,
						'continent'       =>  $continents[strtoupper($record->continent_code)],
						'continent_code'  =>  $record->continent_code,
						'currency'        =>  'Unknown',
						'longitude'       =>  $record->longitude,
						'latitude'        =>  $record->latitude
					);
					geoip_close($gi);
				}
			}
			else
			{
				if(file_exists('/usr/share/GeoIP/GeoIPCityv6.dat'))
				{
					require_once \APLib\Config::get('composer loader');
					$gi = geoip_open("/usr/share/GeoIP/GeoIPCityv6.dat", GEOIP_STANDARD);
				}
				elseif(file_exists('/usr/local/share/GeoIP/GeoIPCityv6.dat'))
				{
					require_once \APLib\Config::get('composer loader');
					$gi   = geoip_open("/usr/local/share/GeoIP/GeoIPCityv6.dat", GEOIP_STANDARD);
				}
				if($gi != null)
				{
					global $GEOIP_REGION_NAME;
					$record = GeoIP_record_by_addr_v6($gi, $ip);
					if($record  ==  null) return null;
					$info = array(
						'ip'              =>  $ip,
						'city'            =>  $record->city,
						'state'           =>  $GEOIP_REGION_NAME[$record->country_code][$record->region],
						'country'         =>  $record->country_name,
						'country_code'    =>  $record->country_code,
						'continent'       =>  $continents[strtoupper($record->continent_code)],
						'continent_code'  =>  $record->continent_code,
						'currency'        =>  'Unknown',
						'longitude'       =>  $record->longitude,
						'latitude'        =>  $record->latitude
					);
					geoip_close($gi);
				}
			}
			if($info != null) return $info;
			try
			{
				$rec = dns_get_record('www.geoplugin.net');
			}
			catch (\Exception $e)
			{
				\APLib\Logger::Error($e);
				$rec = null;
			}
	    if(!$rec) return null;
			if(filter_var($ip, FILTER_VALIDATE_IP))
			{
				$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));
				if(@strlen(trim($ipdat->geoplugin_countryCode)) == 2)
				{
					$info = array(
						'ip'              =>  $ip,
						'city'            =>  @$ipdat->geoplugin_city,
						'state'           =>  @$ipdat->geoplugin_regionName,
						'country'         =>  @$ipdat->geoplugin_countryName,
						'country_code'    =>  @$ipdat->geoplugin_countryCode,
						'continent'       =>  @$continents[strtoupper($ipdat->geoplugin_continentCode)],
						'continent_code'  =>  @$ipdat->geoplugin_continentCode,
						'currency'        =>  @$ipdat->geoplugin_currencySymbol_UTF8,
						'longitude'       =>  @$ipdat->geoplugin_longitude,
						'latitude'        =>  @$ipdat->geoplugin_latitude
					);
				}
			}
			return $info;
		}

		/**
		 * Identify the client's OS, Agent, IP, Country ...etc
		 *
		 * @param   bool  $iplookup  Whether or not to lookup the IP [Default: false]
		 *
		 * @return  array
		 */
		public static function identify($iplookup = false)
		{
			$os_platform  =  "Unknown OS Platform";
			$os_array     =  array(
				'/windows nt 10/i'	     =>  'Windows 10',
				'/windows nt 6.3/i'	     =>  'Windows 8.1',
				'/windows nt 6.2/i'	     =>  'Windows 8',
				'/windows nt 6.1/i'	     =>  'Windows 7',
				'/windows nt 6.0/i'	     =>  'Windows Vista',
				'/windows nt 5.2/i'	     =>  'Windows Server 2003/XP x64',
				'/windows nt 5.1/i'	     =>  'Windows XP',
				'/windows xp/i'		       =>  'Windows XP',
				'/windows nt 5.0/i'	     =>  'Windows 2000',
				'/windows me/i'          =>  'Windows ME',
				'/win98/i'			         =>  'Windows 98',
				'/win95/i'			         =>  'Windows 95',
				'/win16/i'			         =>  'Windows 3.11',
				'/macintosh|mac os x/i'  =>  'Mac OS X',
				'/mac_powerpc/i'         =>  'Mac OS 9',
				'/linux/i'		           =>  'Linux',
				'/ubuntu/i'		        	 =>  'Ubuntu',
				'/iphone/i'		           =>  'iPhone',
				'/ipod/i'			           =>  'iPod',
				'/ipad/i'			           =>  'iPad',
				'/android/i'		         =>  'Android',
				'/blackberry/i'	         =>  'BlackBerry',
				'/webos/i'			         =>  'Mobile'
			);
			foreach ($os_array as $regex => $value)
			{
				if (preg_match($regex, static::$user_agent))
				{
					$os_platform  =  $value;
				}
			}
			$browser        =  "Unknown Browser";
			$browser_array  =  array(
				'/msie/i'       =>  'Internet Explorer',
				'/firefox/i'    =>  'FireFox',
				'/safari/i'	    =>  'Safari',
				'/chrome/i'     =>  'Chrome',
				'/edge/i'	      =>  'Edge',
				'/opera/i'      =>  'Opera',
				'/netscape/i'   =>  'Netscape',
				'/maxthon/i'    =>  'Maxthon',
				'/konqueror/i'  =>  'Konqueror',
				'/mobile/i'     =>  'Handheld Browser'
			);
			foreach ($browser_array as $regex => $value)
			{
				if (preg_match($regex, static::$user_agent))
				{
					$browser  =  $value;
					break;
				}
			}
			$info  =  ($iplookup) ? static::lookup() : null;
			if($info  ==  null)
			{
				$info  =  array(
					'ip'              =>  $_SERVER['REMOTE_ADDR'],
					'city'            =>  'Unknown',
					'state'           =>  'Unknown',
					'country'         =>  'Unknown',
					'country_code'    =>  'Unknown',
					'continent'       =>  'Unknown',
					'continent_code'  =>  'Unknown',
					'currency'        =>  'Unknown',
					'longitude'       =>  'Unknown',
					'latitude'        =>  'Unknown'
				);
			}
			return array(
				'os'              =>  $os_platform,
				'agent'           =>  $browser,
				'ip'              =>  $info['ip'],
				'city'            =>  $info['city'],
				'state'           =>  $info['state'],
				'country'         =>  $info['country'],
				'country_code'    =>  $info['country_code'],
				'continent'       =>  $info['continent'],
				'continent_code'  =>  $info['continent_code'],
				'currency'        =>  $info['currency'],
				'longitude'       =>  $info['longitude'],
				'latitude'        =>  $info['latitude']
			);
		}

		/**
		 * Filter a payload out of XSS attacks
		 *
		 * @param   string  $payload    a payload to filter
		 * @param   bool    $checkOnly  wether to check or secure
		 *
		 * @return  string
		 */
		public static function XSS($payload, $checkOnly  =  false)
		{
			if($payload  ==  "") return "";
			$pregs  =  array(
				// Match any attribute starting with "on" or xmlns
				'#(<[^>]+[\x00-\x20\"\'\/])(on|xmlns)[^>]*>?#iUu',
				// Match javascript:, livescript:, vbscript: and mocha: protocols
				'!((java|live|vb)script|mocha):(\w)*!iUu',
				'#-moz-binding[\x00-\x20]*:#u',
				// Match style attributes
				'#(<[^>]+[\x00-\x20\"\'\/])style=[^>]*>?#iUu',
				// Match unneeded tags
				'#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>?#i'
			);
			foreach($pregs as $preg)
			{
				if(preg_match($preg, $payload) > 0)
				{
					static::$hack  =  true;
					break;
				}
			}
			if($checkOnly) return $payload;
			$payload = addslashes(filter_var($payload, FILTER_SANITIZE_STRING));
			return $payload;
		}

		/**
		 * Filter a payload againts CRLF attacks
		 *
		 * @param   string  $payload    a payload to filter
		 * @param   bool    $checkOnly  wether to check or secure
		 *
		 * @return  string
		 */
		public static function CRLF($payload, $checkOnly  =  false)
		{
			if($payload  ==  "") return "";
			$pregs  =  array(
				'/\r\n|\n|\r/','/(\r?\n){2}/','/(\s*\n){2}/','/\%0d/','/\%0a/'
			);
			foreach($pregs as $preg)
			{
				if(preg_match($preg, $payload) > 0)
				{
					static::$hack  =  true;
					if(!$checkOnly) return preg_split($preg, $payload)[0];
					break;
				}
			}
			return $payload;
		}

		/**
		 * Secure a payload with all filtering functions available
		 *
		 * @param   string  $payload    a payload to filter
		 * @param   bool    $checkOnly  wether to check or secure
		 *
		 * @return  string
		 */
		public static function Secure($payload, $checkOnly  =  false)
		{
			if($payload  ==  "") return "";
			return static::XSS(static::CRLF($payload, $checkOnly), $checkOnly);
		}

		/**
		 * Convert a payload to an HTML special chars
		 *
		 * @param   string  $payload  a payload to convert
		 *
		 * @return  string
		 */
		public static function ToHTML($payload){
			return htmlspecialchars($payload);
		}

		/**
		 * Secure an index file via Security index
		 *
		 * @param   string  $file  path of the index file
		 *
		 * @return  void
		 */
		public static function Index($file)
		{
			$path  =  APLibPath;
			file_put_contents(
				$file,
				"<?php\r\n	require_once '{$path}core.php';\r\n	\APLib\Core::init();\r\n	\APLib\Security::run();\r\n?>"
			);
		}

		/**
		 * Run Security
		 *
		 * @return  void
		 */
		public static function run()
		{
			if(\APLib\Config::get('Secure params\' warning') === true && static::$riskLevel < 2) static::$riskLevel  =  2;
			if(!\APLib\Request\HTTP::post() || !\APLib\Request\HTTP::json())
			{
				echo "<!-- Security is running -->\r\n";
				\APLib\Response\Body::add('		<div class="security"></div>');
			}
			if(static::$hack)
			{
				switch(static::$riskLevel)
				{
					case 5:
						\APLib\Logger::Hack("A high risk hack attempt has been detected!\r\nBlocking....");
						die("<!--
###############################################################################
#                                                                             #
#    #############  #####          #####    #############  ######    ######   #
#    #############  #####          #####   ##############  ######   ######    #
#    #####          #####          #####  ######           ######  ######     #
#    #####          #####          #####  #####            ###### ######      #
#    #############  #####          #####  #####            #############      #
#    #############  #####          #####  #####            #############      #
#    #####          #####          #####  #####            ###### ######      #
#    #####          #####          #####  ######           ######  ######     #
#    #####           ##################    ##############  ######   ######    #
#    #####            ################      #############  ######    ######   #
#                                                                             #
#       #####        #####   ###############    #####          #####          #
#        #####      #####   #################   #####          #####          #
#         #####    #####   #####         #####  #####          #####          #
#          #####  #####    #####         #####  #####          #####          #
#           ##########     #####         #####  #####          #####          #
#            ########      #####         #####  #####          #####          #
#             ######       #####         #####  #####          #####          #
#             ######       #####         #####  #####          #####          #
#             ######       #####         #####  #####          #####          #
#             ######        #################    ##################           #
#             ######         ###############      ################            #
#                                                                             #
###############################################################################
-->");
						break;

					case 4:
						\APLib\Logger::Hack("Risky hack attempt has been detected!\r\nTricking....");
						\APLib\Response\HTTP::status(500);
						break;

					case 3:
						\APLib\Logger::Hack("An obvious hack attempt has been detected!\r\nStopping....");
						\APLib\Response\HTTP::status(403);
						break;

					case 2:
						\APLib\Logger::Hack("Secure params' hack attempt has been detected!\r\nResending....");
						$resend_array  =  array();
						foreach(static::$params_array as $key => $value)
						{
							array_push($resend_array, array($key, $value));
						}
						\APLib\Response\HTTP::resend($resend_array);
						break;

					case 1:
						\APLib\Logger::Hack('A low hack attempt has been detected!');
						break;
				}
			}
		}
	}
?>
