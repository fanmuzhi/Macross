<?php

	require_once dirname(__FILE__).'/lib/database.php';
	require_once dirname(__FILE__).'/lib/database_api.php';
//	require_once dirname(__FILE__).'/lib/capability_index.php';
	require_once dirname(__FILE__).'/lib/result.php';
	require_once dirname(__FILE__).'/lib/part_number.php';
	require_once dirname(__FILE__).'/lib/valid.php';
	
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	$stime = microtime(true);
	$array_for_return=array();
	
	$a = array(	'PartType'=>'01200200',
				'TestItem'=>'idacvalue',
				'WW_start'=>'1201', 
				'WW_end'=>'1312',
				);

	$a	=	$_GET;	
	
	if(isset($a['PartType']))
	{
		$PartType			=	$a['PartType'];
	}
	else $PartType =NULL;
	
	$TestItem = $a['TestItem'];
	
	if(isset($a['WW_start']))
	{
		$WW_start		=	$a['WW_start'];
	}
	else $WW_start =NULL;
	
	if(isset($a['WW_end']))
	{
		$WW_end		=	$a['WW_end'];
	}
	else $WW_end =NULL;

	$valid = new Valid();
	if($valid->part_type_isValid($PartType))
	{
		
		if($valid->test_item_isValid($TestItem))
		{
			$all_weeks_array = array();
			$myDBAPI = new MyDataBaseAPI();
			$temp=$myDBAPI->get_mass_pruducing_week_list('All',null);
			$temp = array_reverse($temp);
			foreach($temp as $key=>$val)
			{
				if($val>=$WW_start and $val<=$WW_end)
				{
					array_push($all_weeks_array, $val);
				}
			}
			$temp_array = array_fill_keys($all_weeks_array, null);
			$result = $myDBAPI->get_weekly_cpk_value($PartType,$TestItem,$WW_start,$WW_end);
			if($result != -1)
			{
				foreach($result as $re)
				{
					if(array_key_exists( $re['WeekNumber']*1 , $temp_array ))
					{
						$temp_array[$re['WeekNumber']*1] = $re['Cpk']*1;
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
				$response=null;
			}
		}
		else
		{
			$error=0x30;
			$response=null;
		}
	}
	else
	{
		$error=0x10;
		$response=null;
	}
	$result	=	new result();
	$result->Name='CpkTrend';
	$result->PartType=$PartType;
	$result->TestItem=$TestItem;
	//$result->TestStation=$TestStation;
	$result->error=$error;
	$result->Response=$response;
	$etime = microtime(true);
	$elapsed_time=$etime-$stime;
	$result->ElapsedTime=round($elapsed_time,2)."s";
	
	echo json_encode($result);
	
?>
