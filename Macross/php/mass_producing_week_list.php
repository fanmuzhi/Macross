<?php
/* ===========================================================
 		mass_producing_week_list.php

		Http Get: 

		Retrun JSON:	[[1231,1231],[1232,1232]]

=========================================================== */

header("Content-type: text/json");

require_once 'lib/database.php';
require_once 'lib/log.php';
require_once 'lib/database_api.php';

	$a = array(	'PartType'=>'11600200',
				'TestItem'=>'idacvalue'
				);
	$a = $_GET;	
	
	if(isset($a['PartType']))
	{
		$PartType = $a['PartType'];
	}
	
	if(isset($a['TestStation']))
	{
		$TestStation = $a['TestStation'];
	}
	else $TestStation = null;
	if(isset($a['TestItem']))
	{
		$TestItem = $a['TestItem'];
		$TestStation = null;
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
	

	$array_for_return=array();
	$myDBAPI = new MyDataBaseAPI();
	
	$temp_array=$myDBAPI->get_mass_pruducing_week_list($PartType,$TestStation);
	
	
	
	foreach($temp_array as $weeknumber)
	{
		array_push($array_for_return, $weeknumber);
		
		//array_push($array_for_return, "{ text: "."Item".$i.", value: ".$partnumber." }");
	
	}
	
	echo json_encode($array_for_return);							

?>