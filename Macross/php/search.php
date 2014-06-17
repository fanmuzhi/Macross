<?php

	header("Content-type: text/json");
	require_once dirname(__FILE__).'/lib/database.php';
	require_once dirname(__FILE__).'/lib/database_api.php';
	require_once dirname(__FILE__).'/lib/result.php';
	require_once dirname(__FILE__).'/lib/valid.php';
	$stime = microtime(true);
	$a = array(	'SerialNumber'=>'',
				'PartType'=>'11600200',
				'TestStation'=>'TMT', 
				'StartTime'=>'2012-01-01', 
				'EndTime'=>'2013-03-31', 
				'PageNumber'=>'1',
				'ErrorCode'=>'61');
	$count = 0;
/************************/				
	$a = $_GET;	
/************************/


	if(isset($a['SerialNumber']))
	{
		$SerialNumber = $a['SerialNumber'];
	}
	else $SerialNumber = null;
	
	if(isset($a['PartType']))
	{
		$PartType = $a['PartType'];
	}
	else $PartType = null;
	
	if(isset($a['TestStation']))
	{
		$TestStation = $a['TestStation'];
	}
	else $TestStation = null;
	
	if(isset($a['StartTime']))
	{
		$StartTime = $a['StartTime'];
	}
	else $StartTime = null;
	
	if(isset($a['EndTime']))
	{
		$EndTime = $a['EndTime'];
	}
	else $EndTime = null;
	
	if(isset($a['ErrorCode']))
	{
		$ErrorCode = $a['ErrorCode'];
	}
	else $ErrorCode = null;
	
	
	$TestStatus = $a['TestStatus'];
	
	
	if(isset($a['PageNumber']))
	{
		$PageNumber = $a['PageNumber'];
	}
	else $PageNumber = "1";
	
	//
	// valid param
	//
	$valid = new Valid();
	if(!$valid->serial_number_isValid($SerialNumber) and $SerialNumber !=null)
	{
		$error=0x60;
		$response="invalid Serial Number";	
	}
	
	elseif(!$valid->part_type_isValid($PartType) and $PartType !=null)
	{
		$error=0x10;
		$response="invalid Part Number";		
	}
	
	elseif(!$valid->test_station_isValid($TestStation) and $TestStation!= null)
	{
		$error=0x20;
		$response="invalid Test Station";
	}
	
	elseif(!$valid->error_code_isValid($ErrorCode) and $ErrorCode != null)
	{
		$error=0x70;
		$response="invalid Error Code";
	}
		
	else
	{
		//search log//
		$myDBAPI = new MyDataBaseAPI();
		$search_result = $myDBAPI->get_dut_log($SerialNumber, $PartType, $TestStation, $StartTime, $EndTime, $ErrorCode ,$TestStatus , $PageNumber);
		if($search_result != null)
		{
			$result_array = array();
			foreach($search_result[0] as $re)
			{
				
				$re = array_values($re);
				array_push($result_array,$re);
			}
//			print_r($search_result[0]);
//			$response	=	$search_result[0];
			$response	=	$result_array;
			$count = $search_result[1];
		}
		else
		{
			$response = array(array(null,null,null,null,null,null,null,null,null));
			$count = "0";
		}
		$error= 0;
	}
	$result	=	new result();
	$result->Name= "SearchDatabase";
	$result->PartType=$PartType;
	$result->TestStation=$TestStation;
	$result->error=$error;
	$result->TotalNumber = $count;
	$result->Response=$response;
	$etime	=	 microtime(true);
	$elapsed_time=$etime-$stime;
	$result->ElapsedTime=round($elapsed_time,2)."s";
	
	echo json_encode($result);
?>

