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

	$a = array(	'PartType'=>'11600200'
				);
	$a = $_GET;	
	
	if(isset($a['PartType']))
	{
		$PartType = $a['PartType'];
	}
	
	

	$array_for_return=array();
	$myDBAPI = new MyDataBaseAPI();
	
	$temp_array=$myDBAPI->get_station_list($PartType);
	
	
	
	foreach($temp_array as $teststation)
	{
		array_push($array_for_return, $teststation);
		
		//array_push($array_for_return, "{ text: "."Item".$i.", value: ".$partnumber." }");
	
	}
	
	echo json_encode($array_for_return);							

?>