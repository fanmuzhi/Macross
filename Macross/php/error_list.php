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

	$a = array(	'PartType' =>'01200500',
				'TestStation'=>'IDD',
				'StartDate' => '2012-01-01',
				'EndWeek'=> '2013-05-10'
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
	
	if(isset($a['StartWeek']))
	{
		$StartWeek = $a['StartWeek'];
	}
	else $StartWeek = null;
	if(isset($a['EndWeek']))
	{
		$EndWeek = $a['EndWeek'];
	}
	else $EndWeek = null;
	if(isset($a['StartDate']))
	{
		$StartDate = $a['StartDate'];
	}
	else $StartDate = null;
	if(isset($a['EndDate']))
	{
		$EndDate = $a['EndDate'];
	}
	else $EndDate = null;
	
	
	$array_for_return=array();
	$myDBAPI = new MyDataBaseAPI();
	
	$temp_array=$myDBAPI->get_errorlist($PartType,$TestStation,$StartWeek,$EndWeek,$StartDate,$EndDate);
	
	
	
	foreach($temp_array as $errorcode)
	{
		array_push($array_for_return, $errorcode);
	}
	
	echo json_encode($array_for_return);							

?>