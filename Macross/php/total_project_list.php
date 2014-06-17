<?php
/* ===========================================================
 		total_project_list.php

		Http Get: 

		Retrun JSON:	[[01200200,01200200],[10200100,10200100]]

=========================================================== */

header("Content-type: text/json");

require_once 'lib/database.php';
require_once 'lib/log.php';
require_once 'lib/database_api.php';

$array_for_return=array();
$myDBAPI = new MyDataBaseAPI();

$temp_array=$myDBAPI->get_partnumber_list();



foreach($temp_array as $partnumber)
{
	array_push($array_for_return, $partnumber);
	
	//array_push($array_for_return, "{ text: "."Item".$i.", value: ".$partnumber." }");

}

echo json_encode($array_for_return);

?>