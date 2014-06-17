<?php

	require_once dirname(__FILE__).'/lib/database.php';
	require_once dirname(__FILE__).'/lib/database_api.php';
	require_once dirname(__FILE__).'/lib/capability_index.php';
	require_once dirname(__FILE__).'/lib/result.php';
	require_once dirname(__FILE__).'/lib/part_number.php';
	require_once dirname(__FILE__).'/lib/valid.php';
	
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	$stime = microtime(true);
	
	
	$a = array(	'PartType'=>'01200500',
				'TestItem'=>'idddeepsleep',
				'WeekNumber'=>'1302', 
				'IndexNumber'=>'');

	$a	=	$_GET;	
	
	if(isset($a['PartType']))
	{
		$PartType			=	$a['PartType'];
	}
	else $PartType =NULL;
//	$TestStation		=	'TMT';
	
	if(isset($a['TestItem']))
	{
		$TestItem			=	$a['TestItem'];
		switch($TestItem) 
			{
				case "idacvalue" : 
					$TestStation = 'TMT';
					break;
				case "rawcountaverage":
					$TestStation = 'TMT';
					break;
				case "rawcountnoise":
					$TestStation = 'TMT';
					break;
				case "iddvalue":
					$TestStation = 'TPT';
					break;
				case "iddsleep1":
					$TestStation = 'IDD';
					break;
				case "idddeepsleep":
					$TestStation = 'IDD';
					break;
			}
	}
	else 
	{
		$TestItem =NULL;
		$TestStation =NULL;
	}
	
	if(isset($a['WeekNumber']))
	{
		$WeekNumber		=	$a['WeekNumber'];
	}
	else $WeekNumber =NULL;
	
	if(isset($a['IndexNumber']))
	{
		$IndexNumber		=	$a['IndexNumber'];
	}
	else $IndexNumber =NULL;

	$valid = new Valid();
	if($valid->part_type_isValid($PartType))
	{
		if($valid->test_station_isValid($TestStation))
		{
			if($valid->test_item_isValid($TestItem))
			{
					$calculate = new capbility_index();
					$calculation = $calculate->calculate_CPK_By_Week($PartType,$TestStation,$TestItem,$IndexNumber,$WeekNumber);
					
					if($calculation)
					{
						$frequency = array();
						foreach ($calculation[frequency] as $key=>$val)
						{
							$a = array($key*1,$val);
							array_push($frequency,$a);
						}
						$calculation[frequency]	=	$frequency;
						$response	=	$calculation;
						$error= 0;
					}
					
					else
					{
						$error=0x50;
						$response=$calculate->error_message;
					}
			}
			else
			{
				$error=0x30;
				$response="invalid Test Item";
			}
		}
		else
		{
			$error=0x20;
			$response="invalid Test Station";
		}
	}
	else
	{
		$error=0x10;
		$response="invalid Part Number";
	}
	$result	=	new result();
	
	$result->Name='OneWeekStats';
	$result->PartType=$PartType;
	$result->TestItem=$TestItem;
	$result->TestStation=$TestStation;
	$result->error=$error;
	$result->Response=$response;
	$etime	=	 microtime(true);
	$elapsed_time=$etime-$stime;
	$result->ElapsedTime=round($elapsed_time,2)."s";
	
	echo json_encode($result);
	
?>
