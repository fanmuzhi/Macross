<?php
require_once 'database.php';
require_once 'log.php';
require_once 'database_api.php';
require_once 'working_week.php';
require_once 'part_number.php';



$myDBAPI = new MyDataBaseAPI();

$result=$myDBAPI->get_error_quantity('01200200', 'TMT', 1149, 63);
print "result: ".$result."<br />";

$result=$myDBAPI->get_error_quantity('01200200', 'TMT', 1150, 63);
print "result: ".$result."<br />";

$result=$myDBAPI->get_error_quantity('01200200', 'TMT', 1151, 63);
print "result: ".$result."<br />";

$result=$myDBAPI->get_error_quantity('01200200', 'TMT', 1152, 63);
print "result: ".$result."<br />";

$result=$myDBAPI->get_error_quantity('01200200', 'TMT', 1201, 63);
print "result: ".$result."<br />";

$result=$myDBAPI->get_error_quantity('01200200', 'TMT', 1202, 63);
print "result: ".$result."<br />";



//print_r(apc_sma_info());

// $myDB=new MyDatabase();

// print $myDB->show_database_version();
// print "\n";

// $params = array('Project' => "='10200500'", 'TestStation' => "='TPT'", 'WeekNumber' => "=1201");

// print $myDB->statistics_query_quantity($params);

//print_r(get_declared_classes());

// print "TEST";
// print "<br />";

//  $pn1=new PartNumber("01200200");

//  if($pn1->name!=null)
//  {
// 	 print $pn1->name;
// 	 print "<br />";
// 	 print "Start Build from: ".$pn1->start_date;
// 	 print "<br />";
// 	 print "To: ".$pn1->end_date;
// 	 print "<br />";
//  }
//  else
//  {
//  	print "Invalid part number.";
//  }


// $WW=new WorkingWeek();
// $week_array=$WW->get_week_list("2001-04-02", "2012-07-30");

// foreach($week_array as $week)
// {
// 	print $week." is ".$WW->get_weeks_ago($week)." weeks ago.";
// 	//print "<br />";
	
// 	print "\n";
	
// }

// $current_date="2011-12-19";

// for($i=0; $i<=100; $i++)
// {
// 	$current_date=$WW->get_next_week($current_date);
	
// 	print $current_date;
// 	print "\n";
// }

// $pn1=new PartNumber('10400600');


// $StartDate='2010-10-01';
// print "Start Date: ".$StartDate."\n"."<br />";

// $EndDate='2013-10-01';
// print "End Date: ".$EndDate."\n"."<br />";

// // check working week
// if($StartDate < $pn1->start_date)
// {
// 	$StartDate=$pn1->start_date;
// }
// if($EndDate > $pn1->end_date)
// {
// 	$EndDate=$pn1->end_date;
// }
// print "Project Start Date: ".$pn1->start_date."\n"."<br />";
// print "Project End Date: ".$pn1->end_date."\n"."<br />";


// print "Start Date: ".$StartDate."\n"."<br />";
// print "End Date: ".$EndDate."\n"."<br />";


//  $date=$WW->get_date("1152");

//  if($date!=-1)
//  {
//  	print "Valid week number 1152";
//  	print "<br />";
//  	print "Start Date: ".$date[0];
//  	print "<br />";
//  	print "End Date: ".$date[1];
//  	print "<br />";

//  }
//  else
//  {
//  	print "Invalid week number 1152";
//  	print "<br />";
//  }


//  $week_array=$WW->get_week_list("2009-12-27", "2012-09-03");

//  if($week_array!=-1)
//  {
//  	$myDBAPI = new MyDataBaseAPI();


//  	foreach($week_array as $week)
//  	{

//  		//print $week;
//  		print $myDBAPI->get_quantity('01200200', 'TMT', $week);
//  		print "<br />";
//  	}
//  }
//  else
//  {
//  	print "the week number is not vaild";
//  	print "<br />";
//  }
?>