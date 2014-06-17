<?php
/* ===========================================================
	total_error_by_station.php
	
	Http Get: 		PartType=10200100&TestStation=TMT&
					StartTime=2011-07-27&EndTime=2012-07-27
					&ErrorCode=All
	
	Retrun JSON:	[[63,52],[91,46],[62,18]]
		
=========================================================== */

header("Content-type: text/json");

require_once 'lib/database.php';
require_once 'lib/log.php';
require_once 'lib/database_api.php';
require_once 'lib/working_week.php';
require_once 'lib/part_number.php';
require_once dirname(__FILE__).'/lib/result.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
$stime = microtime(true);
$PartType 	 = $_GET['PartType'];
$TestStation = $_GET['TestStation'];
$StartWeek = $_GET['StartWeek'];
$EndWeek   = $_GET['EndWeek'];
$ErrorCode 	 = $_GET['ErrorCode'];

$array_for_return=array();
$total_quantity=0;

$pn1=new PartNumber($PartType);

if($pn1->name!=null)
{
//	$WW=new WorkingWeek();
//	 
//	if($StartDate < $pn1->start_date)
//	{
//		$StartDate=$pn1->start_date;
//	}
//	if($EndDate > $pn1->end_date)
//	{
//		$EndDate=$pn1->end_date;
//	}
//	 
//	$week_array=$WW->get_week_list($StartDate, $EndDate);
//	 
//	if($week_array!=-1)
//	{
		if($pn1->IsValid_ErrorCode($ErrorCode))
		{
		
		
			$myDBAPI = new MyDataBaseAPI();
			$tested_array = $myDBAPI->get_error_quantity($PartType, $TestStation, $StartWeek, $EndWeek, $ErrorCode);
			if($tested_array != -1)
			{
				foreach($tested_array as $re)
				{
					$total_quantity = $total_quantity + $re['ErrorNumber'];
				}
				array_push($array_for_return, array($ErrorCode, $total_quantity*1));
				$response = $array_for_return;
				$error= 0;
			}
//			foreach ($week_array as $WeekNumber)
//			{
//				$totalFailed=$myDBAPI->get_quantity($PartType, $TestStation, $WeekNumber, "TotalFailed");
//				if($totalFailed>0)
//				{
//					$total_quantity += $myDBAPI->get_error_quantity($PartType, $TestStation, $WeekNumber, $ErrorCode)*1;
//				}
//			}
			
			
			else
			{
				$error=0x50;
				$response='No Log Found';
			}
		}
		else
		{
			echo "Invalid error code.";
		}
		
//	}
//	else 
//	{
//		echo "Invalid test time.";
//	} 
	 
}
else
{
	echo "Invalid part number.";
}
$result	=	new result();
$result->Name='TotalFailedByErrorCode';
$result->PartType=$PartType;
$result->TestStation=$TestStation;
$result->error=$error;
$result->Response=$response;
$etime	=	 microtime(true);
$elapsed_time=$etime-$stime;
$result->ElapsedTime=round($elapsed_time,2)."s";

echo json_encode($result);
?>