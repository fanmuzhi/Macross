<?php
/* ===========================================================
 	total_yield_by_week.php

	Http Get: 		PartType=10200100&TestStation=TMT&
					StartTime=2011-07-27&EndTime=2012-07-27

	Retrun JSON:	[[1152,0.998],[1201, 0.9992]]

=========================================================== */
header("Content-type: text/json");

require_once 'lib/database.php';
require_once 'lib/log.php';
require_once 'lib/database_api.php';
require_once dirname(__FILE__).'/lib/result.php';
require_once 'lib/working_week.php';
require_once 'lib/part_number.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$stime = microtime(true);
$PartType = $_GET['PartType'];
$TestStation = $_GET['TestStation'];
$StartWeek = $_GET['StartWeek'];
$EndWeek   = $_GET['EndWeek'];

$array_for_return=array();
$array_temp=array();
$week_quantity=0;
$week_failed=0;

$pn1=new PartNumber($PartType);

if($pn1->name!=null)
{
		$all_weeks_array = array();
		$myDBAPI = new MyDataBaseAPI();
		$temp=$myDBAPI->get_mass_pruducing_week_list('All',null);
		$temp = array_reverse($temp);
		foreach($temp as $key=>$val)
		{
			if($val>=$StartWeek and $val<=$EndWeek)
			{
				array_push($all_weeks_array, $val);
			}
		}
		$temp_array = array_fill_keys($all_weeks_array, 0);
		$tested_array = $myDBAPI->get_quantity($PartType, $TestStation, $StartWeek,$EndWeek, "");
		if($tested_array != null)
		{
			foreach($tested_array as $re)
			{
				if(array_key_exists( $re['WeekNumber']*1 , $temp_array ))
				{
					if($re['TotalTested'] !=0)
					{
						$temp_array[$re['WeekNumber']*1] = round(1-($re['TotalFailed']/$re['TotalTested']),4)*100;
					}
					else
					{
						$temp_array[$re['WeekNumber']*1] = 0;
					}
				}
			}
			foreach($temp_array as $key=>$val)
			{
				array_push($array_for_return , array($key,$val));
			}
			$response = $array_for_return;
			$error= 0;
		}
		else
		{
			$error=0x50;
			$response = null;
		}


}
else
{
	$error=0x10;
	echo "Invalid part number.";
}
$result	=	new result();
$result->Name='YieldByWeek';
$result->PartType=$PartType;
$result->TestStation=$TestStation;
$result->error=$error;
$result->Response=$response;
$etime	=	 microtime(true);
$elapsed_time=$etime-$stime;
$result->ElapsedTime=round($elapsed_time,2)."s";

echo json_encode($result);
?>