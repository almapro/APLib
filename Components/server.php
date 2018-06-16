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
	* Server - A class to give details about the server
	*/
	class Server
	{

		/**
		 * Get the hostname of the server
		 *
		 * @return  string
		 */
		public static function host()
		{
			return $_SERVER['SERVER_NAME'];
		}

		/**
		 * Whether the server is Windows or not
		 *
		 * @return  bool
		 */
		public static function Windows()
		{
			return (stristr(PHP_OS, "win")) ? true : false;
		}

		/**
		 * Whether the server is Linux or not
		 *
		 * @return  bool
		 */
		public static function Linux()
		{
			return (PHP_OS == "Linux") ? true : false;
		}

		/**
		 * Execute a command on the server and return the output
		 *
		 * @param   string  $cmd  command to execute
		 *
		 * @return  string
		 */
		public static function exe($cmd)
		{
			if(function_exists('shell_exec'))
			{
				return shell_exec($cmd);
			}
			if(function_exists('exec'))
			{
				$lastline  =  exec($cmd, $output, $status);
				if($status !== 0)
				{
					return '';
				}
				return implode("\r\n", $output);
			}
			if(function_exists('popen') && function_exists('fread') && function_exists('pclose'))
			{
				$handle = popen($cmd, 'r');
				$read   = fread($handle, 2096);
				pclose($handle);
				return $read;
			}
			if(function_exists('proc_open') && function_exists('stream_get_contents'))
			{
				$proc = proc_open(
					$cmd,
					array(
						array("pipe","r"),
						array("pipe","w"),
						array("pipe","w")
					),
					$pipes
				);
				return stream_get_contents($pipes[1]);
			}
			if(function_exists('system'))
			{
				ob_start();
				$lastline = system($cmd, $retval);
				echo $lastline;
				return ob_get_clean();
			}
			if(function_exists('passthru'))
			{
				ob_start();
				passthru($cmd);
				return ob_get_clean();
			}
			return '';
		}

		// DEBUG: Fix CPU load
		/**
     * Get the CPU usage of the server
     *
     * @return  string
     */
    public static function CPU()
    {
			return static::getServerLoad();
    }

		private static function _getServerLoadLinuxData()
    {
			$cpu = static::exe("top -b -n1 -p1 | grep '^%Cpu' | awk '{print \$2}'");
			if($cpu != '') return $cpu;
			if(function_exists('sys_getloadavg')) return sys_getloadavg()[0];
      if (is_readable("/proc/stat"))
      {
        $stats = @file_get_contents("/proc/stat");
        if ($stats !== false)
        {
          $stats = preg_replace("/[[:blank:]]+/", " ", $stats);
					$stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
          $stats = explode("\n", $stats);
          foreach ($stats as $statLine)
          {
            $statLineData = explode(" ", trim($statLine));
            if((count($statLineData) >= 5) && ($statLineData[0] == "cpu"))
            {
							$total = 0;
							$idle  = $statLineData[4];
							for($i=1; $i < sizeof($statLineData); $i++)
							{
								$total += $statLineData[$i];
							}
							return (1000*($total-$idle)/$total+5)/10;
            }
          }
        }
      }
      return 'N/A';
    }

    private static function getServerLoad()
    {
      $load = null;
      if(static::Windows())
      {
        $output = static::exe("wmic cpu get loadpercentage /all");
        if($output)
        {
          foreach($output as $line)
          {
            if($line && preg_match("/^[0-9]+\$/", $line))
            {
              $load = $line;
              break;
            }
          }
        }
      }
      else
      {
        $statData1 = static::_getServerLoadLinuxData();
        sleep(1);
        $statData2 = static::_getServerLoadLinuxData();
        if(!is_null($statData1) && !is_null($statData2))
        {
          $statData2[0] -= $statData1[0];
          $statData2[1] -= $statData1[1];
          $statData2[2] -= $statData1[2];
          $statData2[3] -= $statData1[3];
          $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];
					$load = 100 - ($statData2[3] * 100 / $cpuTime);
        }
      }
      return $load;
    }

		/**
		 * Get system's uptime
		 *
		 * @return  string
		 */
		public static function UpTime()
		{
			if(static::Windows())
			{
				//
			}
			else
			{
				$uptime  =  static::exe("uptime | awk -F'( |,|:)+' '{print \$6,\$7\",\",\$8,\"hours,\",\$9,\"minutes.\"}'");
				if($uptime != '')
				{
					if(preg_match("/up (\d+) day[s]?,[ ]+(\d+):(\d+),/", $uptime, $ar_buf))
					{
						$min = $ar_buf[3];
						$hours = $ar_buf[2];
						$days = $ar_buf[1];
						return "{$days} days, {$hours} hours, {$min} minutes";
					}
					elseif(preg_match("/up (\d+) day[s]?,[ ]+(\d+) min,/", $uptime, $ar_buf))
					{
						$min = $ar_buf[2];
						$days = $ar_buf[1];
						return "{$days} days, {$min} minutes";
					}
					elseif(preg_match("/up[ ]+(\d+):(\d+),/", $uptime, $ar_buf))
					{
						$min = $ar_buf[2];
						$hours = $ar_buf[1];
						return "{$hours} hours, {$min} minutes";
					}
					elseif(preg_match("/up[ ]+(\d+) min,/", $uptime, $ar_buf))
					{
						$min = $ar_buf[1];
						return "{$min} minutes";
					}
				}
				else
				{
					if(!is_readable('/proc/uptime')) return 'N/A';
					return static::converttime(round(explode(' ', file_get_contents('/proc/uptime'))[0]));
				}
			}
		}

		private static function converttime($seconds)
		{
		    $dtF = new DateTime("@0");
		    $dtT = new DateTime("@" . $seconds);
		    return $dtF->diff($dtT)->format("%a days, %h hours, %i minutes");
		}

		/**
		 * Get used space
		 *
		 * @return  string
		 */
		public static function DiskSpace()
		{
			if(static::Windows())
			{
				//
			}
			else
			{
				$space = static::exe("df -P / | tail -n +2 | awk '{print \$5}'");
				if($space != '') return trim(str_replace("%", "", $space));
			}
			return 'N/A';
		}

		/**
		 * Get RAM size
		 *
		 * @return  string
		 */
		public static function RAMsize()
		{
			if(static::Windows())
			{
				//
			}
			else
			{
				if(is_readable('/proc/meminfo'))
				{
					$ram = static::exe("cat /proc/meminfo | head -n 1 | awk '{print \$2}'");
					if($ram != '') return ($ram/1024/1024);
				}
			}
			return 'N/A';
		}

		/**
		 * Get free RAM size
		 *
		 * @return  string
		 */
		public static function FreeRAM()
		{
			if(static::Windows())
			{
				//
			}
			else
			{
				if(is_readable('/proc/meminfo'))
				{
					$ram = static::exe("cat /proc/meminfo | head -n 2 | tail -n +2 | awk '{print \$2}'");
					if($ram != '') return ($ram/1024/1024);
				}
			}
			return 'N/A';
		}

		/**
		 * Get used RAM size
		 *
		 * @return  string
		 */
		public static function UsedRAM()
		{
			if(static::Windows())
			{
				//
			}
			else
			{
				$free = static::exe("free");
				if($free != '')
				{
			    $free = (string) trim($free);
			    $free_arr = explode("\n", $free);
			    $mem = explode(" ", $free_arr[1]);
			    $mem = array_filter($mem);
			    $mem = array_merge($mem);
			    $memory_usage = $mem[2] / $mem[1] * 100;
			    return round($memory_usage, 2);
				}
				$total = static::RAMsize();
				$free  = static::FreeRAM();
				if($total != 'N/A' && $free != 'N/A') return ((($total-$free)/$total)*100);
			}
			return 'N/A';
		}
	}
?>
