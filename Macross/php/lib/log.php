<?php
class Log
{
	
	public static $log_file='../log/log.txt';
	
	
	public static function info($msg)
	{
		self::writelog("INFO: ", $msg);
	}
	
	public static function warning($msg)
	{
		self::writelog("Warning: ", $msg);
	}	
	
	public static function error($msg)
	{
		self::writelog("Error: ", $msg);
	}

	private static function writelog($type, $msg)
	{
		// Getting the time of the error
		$time = @date('[D M, d g:i:s A] ');
	
		// Accessing the log file
		if(is_writable(dirname(__FILE__).'/'.self::$log_file))
		{
			// Getting the contents of the log file
			$contents = file_get_contents(dirname(__FILE__).'/'.self::$log_file);
	
			// Adding our message at the end of the list
			$contents .= "{$time}{$type}{$msg}"."\n";
	
			// Putting the contents back into the log file
			file_put_contents(dirname(__FILE__).'/'.self::$log_file, $contents);
		}
	}

}
?>