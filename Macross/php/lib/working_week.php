<?php
require_once 'database.php';
//require_once 'database_api.php';
class WorkingWeek
{
	//
	// Constant
	//
	private $week_number_pattern = '/^[1][1-4]([0][0-9]|[1-4][0-9]|[5][0-3])$/';
	
	//
	// Property
	//
	
	
	//-------------------------------------------------------------------------------------------------------//
	
	//
	// Function: Get Start Date and End Date from WW number
	//
	public function get_date($week_number)
	{
		if($this->IsValidWeek($week_number))
		{
			$yy=substr($week_number,0,2);
			$ww=substr($week_number,2);
			
			$start_date = @date("Y-m-d H:i:s",$this->getTimeFromWeek($yy, $ww, 0));
			$end_date   = @date("Y-m-d H:i:s",$this->getTimeFromWeek($yy, $ww, 1));
			
			return array($start_date, $end_date);
			
		}
		else
		{
			return -1;
		}
	}
	
	//
	// Function: Get WW array from Start Date to End Date
	//
//	public function get_week_list($start_time, $end_time)
//	{
//		$week_numbers=array();
//		
//		$start_date = @date($start_time);
//		$end_date = @date($end_time);
//
//		//TODO bug in the condition
//		//for($j=$start_date; @strtotime($j)<=@strtotime($end_date); $j = @date("Y-m-d",@strtotime("$j + 1 week")))
//
//		for($current_ww=$this->getWeekFromTime($start_date); 
//			$current_ww<=$this->getWeekFromTime($end_date); 
//			$current_ww=$this->getNextWeek($current_ww))
//		{
//			$week = $current_ww;
//			
//			if($this->IsValidWeek($week))
//			{
//				array_push($week_numbers,$week);
//			}
//		}
//		
//		//** the array is not empty **//
//		if(count($week_numbers)!=0)
//		{
//			return $week_numbers;
//		}
//		else
//		{
//			return -1;
//		}
//	}

	public function get_week_list($start_time, $end_time)
	{
		$week_numbers=array();
		$sql_array =array (	'SET @i = -1;',
					 		'SET @sql = REPEAT(" select 1 union all",-DATEDIFF(\''.$start_time.'\',\''.$end_time.'\')+1);',
							'SET @sql = LEFT(@sql,LENGTH(@sql)-LENGTH(" union all"));',
							'SET @sql = CONCAT("select distinct right(yearweek(date_add(\''.$start_time.'\',interval @i:=@i+1 day),3),4) as week from (",@sql,") as tmp");',
							'PREPARE stmt FROM @sql;'
							);
		$myDBAPI = new MyDatabase; 
		foreach ($sql_array as $sql0)
		{
//			echo $sql0."\n";
			$myDBAPI->execute_none_query($sql0);
		}
		$sql = 'EXECUTE stmt';
		$week_array = $myDBAPI->execute_reader($sql);
//		print_r($week_array);
		foreach ($week_array as $sub_array)
		{
//			echo $week;
			array_push($week_numbers,$sub_array['week']);
		}
		
		//TODO bug in the condition
		//for($j=$start_date; @strtotime($j)<=@strtotime($end_date); $j = @date("Y-m-d",@strtotime("$j + 1 week")))

		
		
		//** the array is not empty **//
		if(count($week_numbers)!=0)
		{
			return $week_numbers;
		}
		else
		{
			return -1;
		}
	}
	
	//
	// Function: get the number of how many weeks ago
	//
	public function get_weeks_ago($week_number)
	{
		if($this->IsValidWeek($week_number))
		{

			$yy=substr($week_number,0,2);
			$ww=substr($week_number,2);
			
			$today=@date("Y-m-d");
			$start_date=@date("Y-m-d", $this->getTimeFromWeek($yy, $ww, 0));

			return count($this->get_week_list($start_date, $today))-1;
		}
		else
		{
			return -1;
		}
	}
	
	//
	// Function: get previous week
	//
	Public function get_previous_week($current_date)
	{
		$week_number=$this->getWeekFromTime($current_date);
	
		if($this->IsValidWeek($week_number))
		{
			$yy=substr($week_number,0,2);
			$ww=substr($week_number,2);
	
			$start_date = @date("Y-m-d", $this->getTimeFromWeek($yy, $ww, 0));
	
			$end_date = @date("Y-m-d",@strtotime("$start_date - 1 week"));
	
	
			$next_week=$this->getWeekFromTime($end_date);
				
			if($this->IsValidWeek($next_week))
			{
				return $end_date;
			}
			else
			{
				return -1;
			}
	
		}
		else
		{
			return -1;
		}
	}
	
	
	//
	// Function: get next week
	//
	Public function get_next_week($current_date)
	{
		$week_number=$this->getWeekFromTime($current_date);

		if($this->IsValidWeek($week_number))
		{
			$yy=substr($week_number,0,2);
			$ww=substr($week_number,2);
		
			$start_date = @date("Y-m-d", $this->getTimeFromWeek($yy, $ww, 0));
		
			$end_date = @date("Y-m-d",@strtotime("$start_date + 1 week"));
		
		
			$next_week=$this->getWeekFromTime($end_date);
			
			if($this->IsValidWeek($next_week))
			{
				return $end_date;
			}
			else
			{
				return -1;
			}

		}
		else
		{
			return -1;
		}
	}
	
	
	//-------------------------------------------------------------------------------------------------------//	
	
	//
	// Function: private function to detect the week number is valid
	//
	private function IsValidWeek($week_number)
	{
	
		//** step1 match the pattern, from start year 2011-01-01 **//
		if(preg_match($this->week_number_pattern, $week_number))
		{
			//** step2 the date is not big than today**//
			$today=@date("Y-m-d");
			$current_week=$this->getWeekFromTime($today);
			
			if($week_number<=$current_week)
			{
				return true;	
			}
			else
			{
				return false;
			}	
		}
		else
		{
			return false;
		}
	}
	
	//
	// Function: private function to get date time from week number
	// print getTimeFromWeek("11","33",1);
	//
	private function getTimeFromWeek($year,$week,$dir)
	{
		$wday = 4-@date('w',@mktime(0,0,0,1,4,$year))+1;
		return @strtotime(sprintf("+%d weeks",$week-($dir?0:1)),@mktime(0,0,0,1,$wday,$year))-($dir?1:0);
	}
	
	//
	// Function: private function to get week number from date time
	// print getWeekFromTime("2011-12-02");
	// print getWeekFromTime("20111202");
	//
	private function getWeekFromTime($time)
	{
		$date = @strtotime($time);

		$getYY = @date('o',$date);
		$getWW = @date('W',$date);
		
		$getYY=substr($getYY, -2);
		return $YYWWforStore = $getYY.$getWW;
	}
	
	//
	// Function: get next week
	//
	private function getNextWeek($week_number)
	{
		$yy=substr($week_number,0,2);
		$ww=substr($week_number,2);
	
		$start_date = @date("Y-m-d", $this->getTimeFromWeek($yy, $ww, 0));
	
		$end_date = @date("Y-m-d",@strtotime("$start_date + 1 week"));
	
	
		return $this->getWeekFromTime($end_date);
	}
	
}

?>