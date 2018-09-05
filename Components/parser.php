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
	* Parser - A class to parse APLib logs
	*/
	class Parser
	{

	    /**
	     * Parse one line of log & return a log entry
	     *
	     * @param   string  $log  log data to parse
	     *
	     * @return  array
	     */
	    public static function line($log)
	    {
			if($log  ==  '' || strpos($log, ' - ') === false) return array();
			$parted   =  explode(' - ', $log);
			$type     =  strtolower($parted[0]);
			$time     =  $parted[1];
			$message  =  rtrim(substr($log, strlen($type.' - '.$time.' - ')));
			$entry    =  array(
				'time'     =>  $time,
				'message'  =>  $message
			);
			switch($type)
			{
			case '[x]':
			  $entry['type']  =  'Error';
			  break;

			case '[+]':
			  $entry['type']  =  'Good';
			  break;

			case '[*]':
			  $entry['type']  =  'Info';
			  break;

			case '[!]':
			  $entry['type']  =  'Warning';
			  break;

			case '[**]':
			  $entry['type']  =  'Access';
			  break;

			case '[!!]':
			  $entry['type']  =  'Hack';
			  break;

			default:
			  $entry['type']  =  'Unknown';
			  break;
			}
			return $entry;
	    }

		/**
		 * Parse a log file and return all log entries
		 *
		 * @param   string  $file  a log file to parse
		 *
		 * @return  array
		 */
		public static function parse($file)
		{
	    	$logs  =  array();
			$sfo   =  new \SplFileObject($file, 'r');
			$sfo->seek(1);
			while($sfo->valid())
			{
				$log  = static::line($sfo->current());
				if(isset($log['type'])) array_push($logs, $log);
				$sfo->seek($sfo->key() + 1);
			}
			return $logs;
		}

		/**
		 * Return only the last log entry of a log file
	     *
	     * @param   string  $file  a log file to get the last log entry from
		 *
		 * @return  array
		 */
		public static function last($file)
		{
			$sfo  =  new \SplFileObject($file, 'r');
			$sfo->seek(PHP_INT_MAX);
			$log  =  '';
			while(strpos($log, ' - ') === false && $sfo->key() > 0)
			{
				$sfo->seek($sfo->key() - 1);
				$log  =  $sfo->current();
			}
			return static::line($log);
		}
	}
?>
