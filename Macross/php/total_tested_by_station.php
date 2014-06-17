<?php
/* ===========================================================
 	total_tested_by_station.php

	Http Get: 		PartType=10200100&TestStation=Both&
					StartTime=2011-07-27&EndTime=2012-07-27

	Retrun JSON:	[['TPT',4.017],['TMT',1.020]]

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

$PartType = $_GET['PartType'];
$TestStation = $_GET['TestStation'];
$StartWeek = $_GET['StartWeek'];
$EndWeek   = $_GET['EndWeek'];

$array_for_return=array();
$total_quantity=0;
$total_quantity_tpt=0;
$total_quantity_tmt=0;


$pn1=new PartNumber($PartType);

if($pn1->name!=null)
{
	
	$myDBAPI = new MyDataBaseAPI();
	
	$tested_array = $myDBAPI->get_quantity($PartType, $TestStation, $StartWeek, $EndWeek, "");
	if($tested_array != null)
	{
		
		foreach($tested_array as $re)
		{
			$total_quantity = $total_quantity + $re['TotalTested'];
		}
		array_push($array_for_return, array($TestStation, $total_quantity/1000));
		$response = $array_for_return;
		$error= 0;
	}

	else
	{
		$error=0x50;
		$response=null;
	}
}
else
{
	$error=0x10;
	$response=null;
}
$result	=	new result();
$result->Name='TotalTestedByStation';
$result->PartType=$PartType;
$result->TestStation=$TestStation;
$result->error=$error;
$result->Response=$response;
$etime	=	 microtime(true);
$elapsed_time=$etime-$stime;
$result->ElapsedTime=round($elapsed_time,2)."s";

echo json_encode($result);
?>