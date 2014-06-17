<?php

class MyDataBaseAPI
{
	//
	// Constant
	//
	private $myDB;

	//
	//	Property
	//
	public $error_message;



	//
	// Constructor
	//
	function MyDataBaseAPI()
	{
		try
		{
			$this->myDB=new MyDatabase();
			
			$this->myDB->connect_database();
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
		}
	}

	//
	// Destructor
	//
	function __destruct()
	{
		try
		{
			$this->myDB->disconnect_database();
		}
		catch (Exception $ex)
		{
			//do nothing
		}
	}

	
	//
	// Function: Get TP Quantity.
	//
	public function get_quantity($PartNumber, $TestStation, $StartWeek ,$EndWeek, $return)
	{
		try
		{
//			$QuantityTotalTested=0;
//			$apc_key_TotalFailed=0;
			
			/*** debug APC ***/
			//$params = array('Project' => "='10200500'", 'TestStation' => "='TMT'", 'WeekNumber' => "=1211");

//			$tmp_params = array('Project' => "='$PartNumber'", 'TestStation' => "='$TestStation'", 'StartWeek' => "=$StartWeek", 'EndWeek' => "=$EndWeek");

//			$apc_key_TotalTested='TotalTested_';
//			$apc_key_TotalFailed='TotalFailed_';
			

//			foreach ($tmp_params as $key=>$value)
//			{
//				$apc_key_TotalTested.=$key.' '.$value.'_';
//				$apc_key_TotalFailed.=$key.' '.$value.'_';
//			}

//			if(apc_exists($apc_key_TotalTested))
//			{
//				$QuantityTotalTested=apc_fetch($apc_key_TotalTested);
//				$QuantityTotalFailed=apc_fetch($apc_key_TotalFailed);
//			}
//			else
//			{
				/*** generate SQL ***/
				$sql=' SELECT `WeekNumber`, `TotalTested` , `TotalFailed` FROM `statistics_dut` WHERE ' .
					 ' `Project` = ' . "'" .$PartNumber . "'".
					 ' AND `TestStation` = ' . "'". $TestStation. "'". 
					 ' AND `WeekNumber` BETWEEN '."'".$StartWeek."'".' AND '."'".$EndWeek. "'";
				$result_array=$this->myDB->execute_reader($sql);
				
				if($result_array!=-1)
				{
//					foreach($result_array as $re)
//					{
//						$result=$re;
//					}
//
//					apc_add($apc_key_TotalTested, $result["TotalTested"]);
//					apc_add($apc_key_TotalFailed, $result["TotalFailed"]);
//
//					$QuantityTotalTested=apc_fetch($apc_key_TotalTested);
//					$QuantityTotalFailed=apc_fetch($apc_key_TotalFailed);
// 					print "Found in MySQL: ".$apc_key."{".$Quantity."}";
// 					print "<br />";
					return $result_array;
				}
				else
				{
					//no record found in database statitics

//					$sql_totalTested= ' SELECT `TotalTested` FROM `statistics_dut` WHERE ' .
//								  ' `Project` = ' . "'" .$PartNumber . "'".
//								  ' AND `TestStation` = ' . "'". $TestStation. "'". 
//								  ' AND `WeekNumber` BETWEEN '."'".$StartWeek."'".' AND '."'".$EndWeek. "';";
//					$sql_totalFailed= ' SELECT `TotalFailed` FROM `statistics_dut` WHERE ' .
//									  ' `Project` = ' . "'" .$PartNumber . "'".
//									  ' AND `TestStation` = ' . "'". $TestStation. "'". 
//									  ' AND `WeekNumber` BETWEEN '."'".$StartWeek."'".' AND '."'".$EndWeek. "';";
//					
//					$QuantityTotalTested=$this->myDB->execute_scalar($sql_totalTested);
//					if($QuantityTotalTested == -1)
//					{
//						$QuantityTotalTested =0;
//					}
//					$QuantityTotalFailed=$this->myDB->execute_scalar($sql_totalFailed);
//					if($QuantityTotalFailed == -1)
//					{
//						$QuantityTotalFailed =0;
//					}
//					
//					apc_add($apc_key_TotalTested, $QuantityTotalTested);
//					apc_add($apc_key_TotalFailed, $QuantityTotalFailed);
					//}
					return $result_array = null;
				}
//			}
			
//			
//			if($return=="TotalTested")
//			{
//				return $QuantityTotalTested;
//			}
//			else
//			{
//				return $QuantityTotalFailed;
//			}

		}
		catch (PDOException $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}

	}

	//
	// Function: Get TP Error Quantity
	//
//	public function get_error_quantity($PartNumber, $TestStation, $WeekNumber, $ErrorCode)
//	{
//		try
//		{
//			$ErrorQuantity=0;
//			
//			$tmp_params = array('statistics_dut.Project' => "='$PartNumber'", 'statistics_dut.TestStation' => "='$TestStation'", 
//					             'statistics_dut.WeekNumber' => "=$WeekNumber", 'statistics_error.ErrorCode'=>"=$ErrorCode");
//			
//			$apc_key='ErrorQuantity_';
//			
//			foreach ($tmp_params as $key=>$value)
//			{
//				$apc_key.=$key.' '.$value.'_';
//			}
//			
//			if(apc_exists($apc_key))
//			{
//				$ErrorQuantity=apc_fetch($apc_key);
//			}
//			else
//			{
//				// generate SQL, inner join for mySQL
//				$sql='SELECT statistics_error.ErrorNumber FROM statistics_dut, statistics_error 
//					  WHERE statistics_error.DutId=statistics_dut.Id AND ';
//				
//				foreach ($tmp_params as $key=>$value)
//				{
//					/*** combine key "PartType" and value "='10200500'" ***/
//					$sql.=$key.$value.' AND ';
//				}
//				
//				/*** remove the last " AND " and plus a ";" ***/
//				$sql=substr($sql, 0, -5).";";
//				
//				//debug
//				//print $sql."\n";
//				
//				$result=$this->myDB->execute_scalar($sql);
//				
//				if($result!=-1)
//				{
//					//exists in statistics_error
//					apc_add($apc_key, $result);
//					$ErrorQuantity=apc_fetch($apc_key);
//					
//				}
//				else
//				{
//					//doesn't exists in statistics_error
//					
//					$sql='SELECT COUNT(*) FROM dut WHERE TestStatus = 0 AND ';
//											
//					$WW=new WorkingWeek();
//					
//					$date_array=$WW->get_date($WeekNumber);
//					$start_date=$date_array[0];
//					$end_date=$date_array[1];
//					
//					$tmp_params = array('PartType' => "='$PartNumber'", 'TestStation' => "='$TestStation'", 'ErrorCode'=>"=$ErrorCode", 
//										'TestTime >=' => "'$start_date'", 'TestTime <=' => "'$end_date'");
//
//					foreach ($tmp_params as $key=>$value)
//					{
//					/*** combine key "PartType" and value "='10200500'" ***/
//						$sql.=$key.$value.' AND ';
//					}
//						
//					/*** remove the last " AND " and plus a ";" ***/
//					$sql=substr($sql, 0, -5).";";
//					
//					//debug
//					//print $sql."\n";
//					
//					$ErrorQuantity=$this->myDB->execute_scalar($sql);
//
//					//insert into the statistics_error
//					$sql_getID="SELECT statistics_dut.Id FROM statistics_dut WHERE Project='".$PartNumber.
//					           "' AND TestStation='".$TestStation."' AND WeekNumber=".$WeekNumber.";";
//					
//					$dutId=$this->myDB->execute_scalar($sql_getID);
//					
//					//$sql_insert='INSERT INTO statistics_error(DutId, ErrorCode, ErrorNumber) VALUES ('.$dutId.', '.$ErrorCode.', '.$ErrorQuantity.');';
//					
//					//debug
//					//print $sql_getID."\n";
//					//print $sql_insert."\n";
//					
//					
//					//$this->myDB->execute_none_query($sql_insert);
//					
//					//apc_add($apc_key, $ErrorQuantity);
//					
//				}
//				
//			}
//			
//			return $ErrorQuantity;
//			
//		}
//		catch (PDOException $e)
//		{
//			$this->error_message=$e->getMessage();
//			return $e->getCode();
//		}
//		
//	}
//	

	public function get_error_quantity($PartNumber, $TestStation, $StartWeek, $EndWeek, $ErrorCode)
	{
		try
		{
			$ErrorQuantity=0;
			
//			$tmp_params = array('statistics_dut.Project' => "='$PartNumber'", 'statistics_dut.TestStation' => "='$TestStation'", 
//					             'statistics_dut.WeekNumber' => "=$WeekNumber", 'statistics_error.ErrorCode'=>"=$ErrorCode");
			
//			$apc_key='ErrorQuantity_';
			
//			foreach ($tmp_params as $key=>$value)
//			{
//				$apc_key.=$key.' '.$value.'_';
//			}
			
//			if(apc_exists($apc_key))
//			{
//				$ErrorQuantity=apc_fetch($apc_key);
//			}
//			else
//			{
				// generate SQL, inner join for mySQL
				$sql= " SELECT `statistics_dut`.`WeekNumber`, `statistics_error`.`ErrorNumber` " .
					  " FROM `statistics_dut`, `statistics_error` ".
					  " WHERE `statistics_error`.`DutId` = `statistics_dut`.`Id` AND ".
					  " `statistics_dut`.`Project` = '".$PartNumber."' AND ".
					  " `statistics_dut`.`TestStation` = '".$TestStation."' AND ".
					  " `statistics_dut`.`WeekNumber` BETWEEN '".$StartWeek."' AND '".$EndWeek."' AND ".
					  " `statistics_error`.`ErrorCode` = '".$ErrorCode."';"
					  ;
				
//				foreach ($tmp_params as $key=>$value)
//				{
//					/*** combine key "PartType" and value "='10200500'" ***/
//					$sql.=$key.$value.' AND ';
//				}
				
				/*** remove the last " AND " and plus a ";" ***/
//				$sql=substr($sql, 0, -5).";";
				
				//debug
				//print $sql."\n";
				
				$result=$this->myDB->execute_reader($sql);
				
				if($result!=-1)
				{
					return $result;
					//exists in statistics_error
//					apc_add($apc_key, $result);
//					$ErrorQuantity=apc_fetch($apc_key);
					
				}
				else
				{
					return $result = null;
//					$result = 0;
//					apc_add($apc_key, $result);
//					$ErrorQuantity=apc_fetch($apc_key);
				}
				
//			}
			
			
			
		}
		catch (PDOException $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
		
	}

	//
	// Function: Get PartNumber list
	//	
	public function get_partnumber_list()
	{
		try
		{
			$Array_PartNumber=array();
			$apc_key="part_number_list";
	
			if(apc_exists($apc_key))
			{
				//** get string from the APC **//
				$part_number_list=apc_fetch($apc_key);
				
				//** convert to array **//
				$Array_PartNumber= explode("|", $part_number_list);
				
//				print "Found in APC: ".$apc_key."{".$part_number_list."}";
// 				print "<br />";
			}
			else
			{
				$sql="SELECT PartType FROM (SELECT DISTINCT PartType FROM dut) temp";
				
				$result_array = $this->myDB->execute_reader($sql);
				
				//print_r($result_array);
				
				if($result_array!=-1)
				{
					foreach($result_array as $result)
					{
						/*** add element into the array***/
						array_push($Array_PartNumber, $result["PartType"]);
						sort($Array_PartNumber);
					}
					
					$part_number_list = implode("|", $Array_PartNumber);
					apc_add($apc_key,$part_number_list);
					
					
// 					print "Found in MySQL: ".$apc_key."{".$part_number_list."}";
// 					print "<br />";
				}
				else
				{
					$Array_PartNumber=null;
				}
	
			}
			
			return $Array_PartNumber;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	
	}
	
	
	//
	// Function: find the first record
	//
	public function get_starttime($PartNumber)
	{
		try
		{
			$apc_key=$PartNumber."_first_record";
			if(apc_exists($apc_key))
			{
				//** get string from the APC **//
				$start_time=apc_fetch($apc_key);
				
// 				print "Found in APC: Start Time: ".$start_time;
// 				print "<br />";
				
			}
			else
			{
				$sql="SELECT MIN(TestTime) FROM dut WHERE PartType='$PartNumber'";
				$result=$this->myDB->execute_scalar($sql);
				

				
				if($result!=-1)
				{
					$start_time=$result;
					apc_add($apc_key,$start_time);
					
// 					print "Found in MySQL: Start Time: ".$start_time;
// 					print "<br />";
				}
				else
				{
					$start_time=null;
				}
			}
			
			return $start_time;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
		
		
	
	}
	
	//
	// Function: find the last record
	//
	public function get_endtime($PartNumber)
	{
		try
		{
			$apc_key=$PartNumber."_last_record";
			if(apc_exists($apc_key))
			{
				//** get string from the APC **//
				$end_time=apc_fetch($apc_key);
				
// 				print "Found in APC: End Time: ".$end_time;
// 				print "<br />";
				
			}
			else
			{
				$sql="SELECT MAX(TestTime) FROM dut WHERE PartType='$PartNumber'";
				$result = $this->myDB->execute_scalar($sql);
				

				
				if($result!=-1)
				{
					$end_time=@date($result);
					apc_add($apc_key,$end_time);
					
// 					print "Found in MySQL: End Time: ".$end_time;
// 					print "<br />";
				}
				else
				{
					$end_time=null;
				}
			}
			
			return $end_time;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	
	}
	
	
	//
	// Function: find the total error number to save time
	//
	public function get_total_error_count($PartNumber, $ErrorCode)
	{
		try
		{
			$apc_key=$PartNumber."_".$ErrorCode."_Total_Number";
			if(apc_exists($apc_key))
			{
				$total_quantity=apc_fetch($apc_key);
			}
			else
			{
				$sql="SELECT Count(*) FROM dut WHERE PartType='$PartNumber' And ErrorCode=$ErrorCode AND TestStatus = 0";
				$result=$this->myDB->execute_scalar($sql);
	
				if($result!=-1)
				{
					$total_quantity=$result;
					apc_add($apc_key,$total_quantity);

				}
				else
				{
					$total_quantity=null;
				}
			}
			
			return $total_quantity;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}

	}
	
	//
	// Function: Get ErrorCode list
	//
	public function get_errorcode_list($PartNumber)
	{
		try
		{
			$Array_ErrorCode=array();
			$apc_key=$PartNumber."_error_code_list";
		
			if(apc_exists($apc_key))
			{
			//** get string from the APC **//
				$error_code_list=apc_fetch($apc_key);
		
				//** convert to array **//
				$Array_ErrorCode= explode("|", $error_code_list);
	
			}
			else
			{
				$sql="SELECT ErrorCode FROM (SELECT DISTINCT ErrorCode FROM dut WHERE dut.PartType='$PartNumber') temp";
			
				$result_array = $this->myDB->execute_reader($sql);
			
				//print_r($result_array);
			
				if($result_array!=-1)
				{
					foreach($result_array as $result)
					{
					/*** add element into the array***/
						array_push($Array_ErrorCode, $result["ErrorCode"]);
					}
						
					$error_code_list = implode("|", $Array_ErrorCode);
					apc_add($apc_key,$error_code_list);
		
				}
				else
				{
					$Array_ErrorCode=null;
				}
		
			}
				
			return $Array_ErrorCode;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	
	}
	
	
	
	
	//
	// Function: Get TP Quantity. (for search using)
	//
	public function get_dut_log($SerialNumber, $PartType, $TestStation, $StartTime, $EndTime, $ErrorCode,$TestStatus , $PageNumber)
	{
		try
		{
//			echo $ErrorCode;
			$Array_Log=array();
			if($SerialNumber != null)
			{
				$sn_condition = "SerialNumber = '$SerialNumber' AND ";
			}
			else $sn_condition = null;
			
			if($PartType != null)
			{
				$pt_condition = "PartType = '$PartType' AND ";
			}
			else $pt_condition = null;
			
			if($TestStation != null)
			{
				$ts_condition = "TestStation = '$TestStation' AND ";
			}
			else $ts_condition = null;
			
			if($StartTime != null)
			{
				$st_condition = "TestTime >= '$StartTime 00:00:00' AND ";
			}
			else $st_condition = null;
			
			if($EndTime != null)
			{
				$et_condition = "TestTime <= '$EndTime 23:59:59' AND ";
			}
			else $et_condition = null;
			
			if($TestStatus == "0")
			{
				$status_condition = "TestStatus = 0 AND ";
			}
			else $status_condition = null;
			
			if($ErrorCode != null)
			{
				$ec_condition = "ErrorCode = '$ErrorCode' AND ";
			}
			else $ec_condition = null;
			
			if($PageNumber != null)
			{
				$start_line = (string)($PageNumber-1)*50;
				$end_line = (string)$PageNumber*50;
			}
			else 
			{
				$start_line = "0";
				$end_line = "50";
			}
			
			$sql_select	=	"SELECT * ";
			$sql_select_count ="SELECT Count(1) " ;
			$sql_from	=	" FROM dut ";
			$sql_where	= 	" WHERE ".substr(
									  $pt_condition.
									  $ts_condition.
									  $sn_condition.
									  $ec_condition.
									  $status_condition.
							    	  $st_condition.
							    	  $et_condition,0,-5);
			$sql_limit	= " LIMIT ".$start_line." , ".$end_line;
			$this->myDB->connect_database();
			$sql_statement_count = $sql_select_count.$sql_from.$sql_where;
			$Count = $this->myDB->execute_Scalar($sql_statement_count);
			
			$sql_statement	=	$sql_select.$sql_from.$sql_where.$sql_limit;
			$result_array = $this->myDB->execute_reader($sql_statement);
			
			if($result_array != -1)
			{
				return $Array_Log = array($result_array,(int)$Count);
			}
			else
			{
				return $Array_Log = null;
			}
		}
		catch (PDOException $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	public function get_errorlist($PartType,$TestStation,$StartWeek,$EndWeek,$StartDate,$EndDate)
	{
		try
		{
			$Array_List=array();
			if($PartType == 'All')
			{
				$pn_condition = null;
			}
			else
			{
				$pn_condition = " AND `statistics_dut`.`Project` = '".$PartType."'";
			}
			
			if($TestStation == 'All')
			{
				$ts_condition = null;
			}
			else
			{
				$ts_condition = " AND `statistics_dut`.`TestStation` = '".$TestStation."' ";
			}
			
			if($StartWeek == null)
			{
				$sw_condition = null;
			}
			else
			{
				$sw_condition = " AND `statistics_dut`.`WeekNumber` >= '".$StartWeek."' ";
			}
			if($EndWeek == null)
			{
				$ew_condition = null;
			}
			else
			{
				$ew_condition = " AND `statistics_dut`.`WeekNumber` <= '".$EndWeek."' ";
			}
			
			if($StartDate == null)
			{
				$sd_condition = null;
			}
			else
			{
				$sd_condition = " AND `statistics_dut`.`WeekNumber` >= RIGHT(YEARWEEK('".$StartDate."',3),4) ";
			}
			if($EndDate == null)
			{
				$ed_condition = null;
			}
			else
			{
				$ed_condition = " AND `statistics_dut`.`WeekNumber` <= RIGHT(YEARWEEK('".$EndDate."',3),4) ";
			}
			
//			
			$sql=	" SELECT DISTINCT `statistics_error`.`ErrorCode` ".
					" FROM `statistics_dut`, `statistics_error` " .
					" WHERE `statistics_error`.`DutId`=`statistics_dut`.`Id` ".
					" AND `statistics_error`.`ErrorNumber` > 0 ".
					$pn_condition.
					$ts_condition.
					$sw_condition.
					$ew_condition.
					$sd_condition.
					$ed_condition
					; 
			
			$result_array = $this->myDB->execute_reader($sql);
			
			//print_r($result_array);
			
			if($result_array!=-1)
			{
				foreach($result_array as $result)
				{
					/*** add element into the array***/
					array_push($Array_List, $result["ErrorCode"]);
				}
			}
			else
			{
				$Array_List = array();
			}
			return $Array_List;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	public function get_mass_pruducing_week_list($PartType,$TestStation)
	{
		try
		{
			$Array_WeekList=array();
			if($PartType == 'All')
			{
				$pn_condition = null;
			}
			else
			{
				$pn_condition = " `statistics_dut`.`Project` = '".$PartType."' AND ";
			}
			
			if($TestStation == null)
			{
				$ts_condition = null;
			}
			else
			{
				$ts_condition = " `statistics_dut`.`TestStation` = '".$TestStation."' AND ";
			}
//			$apc_key="producing_week_list";
//	
//			if(apc_exists($apc_key))
//			{
//				//** get string from the APC **//
//				$part_number_list=apc_fetch($apc_key);
//				
//				//** convert to array **//
//				$Array_PartNumber= explode("|", $part_number_list);
//				
////				print "Found in APC: ".$apc_key."{".$part_number_list."}";
//// 				print "<br />";
//			}
//			else
//			{
				$sql=	" SELECT DISTINCT `statistics_dut`.`WeekNumber` ".
						" FROM `statistics_dut` WHERE ".
						$pn_condition.
						$ts_condition.
						" 1=1 ;"; 
//						" AND `TotalTested`>5000;";
				
				$result_array = $this->myDB->execute_reader($sql);
				
				//print_r($result_array);
				
				if($result_array!=-1)
				{
					foreach($result_array as $result)
					{
						/*** add element into the array***/
						array_push($Array_WeekList, $result["WeekNumber"]);
						rsort($Array_WeekList);
					}
					
//					$part_number_list = implode("|", $Array_PartNumber);
//					apc_add($apc_key,$part_number_list);
					
					
// 					print "Found in MySQL: ".$apc_key."{".$part_number_list."}";
// 					print "<br />";
				}
				else
				{
					$Array_WeekList = array("no weeks");
				}
	
//			}
			
			return $Array_WeekList;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	
	}
	
	public function get_station_list($PartType)
	{
		try
		{
			$Array_StationList=array();
			if($PartType == 'All')
			{
				$pn_condition = null;
			}
			else
			{
				$pn_condition = "WHERE `statistics_dut`.`Project` = '".$PartType."';";
			}
//			
				$sql=	" SELECT DISTINCT `statistics_dut`.`TestStation` ".
						" FROM `statistics_dut` ".
						$pn_condition; 
				
				$result_array = $this->myDB->execute_reader($sql);
				
				//print_r($result_array);
				
				if($result_array!=-1)
				{
					foreach($result_array as $result)
					{
						/*** add element into the array***/
						array_push($Array_StationList, $result["TestStation"]);
						rsort($Array_StationList);
					}
					
//					$part_number_list = implode("|", $Array_PartNumber);
//					apc_add($apc_key,$part_number_list);
					
					
// 					print "Found in MySQL: ".$apc_key."{".$part_number_list."}";
// 					print "<br />";
				}
				else
				{
					$Array_StationList = array("no station");
				}
	
//			}
			
			return $Array_StationList;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	public function db_log_check($xml_obj)
	{
		try
		{
			$serial_number =$xml_obj->Serial_Number ;
			$part_type = substr($serial_number,0,8);
			$test_time = substr($xml_obj->Test_Time,0,19);
			
			$sql_statement="SELECT `Id` FROM dut WHERE `PartType` = '$part_type' AND `SerialNumber` = '$serial_number' AND `TestTime` = '$test_time'";
			$result = $this->myDB->execute_reader($sql_statement);
//			print_r($result);
			if($result == -1)
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
		}
	}
	
	public function table_dut_insert($xml_obj)
	{
		try
		{
			if($xml_obj)
			{
				if(isset($xml_obj->Serial_Number))
				{
					$serial_number	=	$xml_obj->Serial_Number;
					$part_type 		=	substr($serial_number,0,8);
				}
				else 
				{
					$serial_number	=	"null";
					$part_type		=	"null";
				}
				
				if(isset($xml_obj->Test_Station))
				{
					$test_station	=	substr($xml_obj->Test_Station,0,3);
				}
				else $test_station	= 	"null";
				
				if(isset($xml_obj->Error_Code))
				{
					$error_code 	=	$xml_obj->Error_Code;
				}
				else $error_code	=	"null";
				
				if(isset($xml_obj->Test_Time))
				{
					$test_time		=	substr($xml_obj->Test_Time,0,19);
				}
				else $test_time		= 	"null";
				
				if(isset($xml_obj->IDD_Value))
				{
					$idd_value		=	$xml_obj->IDD_Value;
				}
				else $idd_value		= 	"null";
				
				if(isset($xml_obj->Firmware_Revision))
				{
					$fw_version		=	$xml_obj->Firmware_Revision;
				}
				else $fw_version	=	"null";
				
				$id = $this->check_exist_newest_exist($serial_number,$test_station,$test_time);
				if($id != -1)
				{
					$test_status = 1;
					$sql0="insert ignore into `trackpad`.`dut` " .
					  "values (NULL, '$serial_number', '$test_station', '$part_type', $error_code, $idd_value, $fw_version, '$test_time', $test_status)";
//					  .$new_status.");
					$result = $this->myDB->execute_none_query($sql0);
				}
				else
				{
					$test_status = 0;
					$sql0="insert ignore into `trackpad`.`dut` " .
					  "values (NULL, '$serial_number', '$test_station', '$part_type', $error_code, $idd_value, $fw_version, '$test_time', $test_status)";
//					  .$new_status.");
					$result = $this->myDB->execute_none_query($sql0);
					$this->update_status($serial_number,$test_station,$test_time);
				}
				return $result;
			}
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	private function check_exist_newest_exist($serial_number,$test_station,$test_time)
	{
		try
		{
				$sql =	" SELECT Id FROM dut WHERE " .
						" dut.SerialNumber = '" .$serial_number."'".
						" AND dut.TestStation = '".$test_station."'".
						" AND dut.TestTime >= '".$test_time."'".
						" AND TestStatus = 0";
				$Id = $this->myDB->execute_reader($sql);
				return $Id;
				
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
//	private function check_older_log_exist($serial_number,$test_station,$test_time)
//	{
//		try
//		{
//				$sql =	" SELECT Id FROM dut WHERE " .
//						" dut.SerialNumber = " .$serial_number.
//						" AND dut.TestStation = ".$test_station.
//						" AND dut.TestTime < ".$test_time.
//						" AND TestStatus = 0";
//				$Id = $this->myDB->execute_reader($sql);
//				return $Id;
//				
//		}
//		catch (Exception $e)
//		{
//			$this->error_message=$e->getMessage();
//			return $e->getCode();
//		}
//	}
	
	private function update_status($serial_number,$test_station,$test_time)
	{
		try
		{
			$sql =	" UPDATE dut SET ".
					" dut.TestStatus = 1".
					" WHERE SerialNumber = '".$serial_number."'".
					" AND TestStation = '".$test_station."'".
					" AND TestStatus = 0".
					" AND TestTime < '".$test_time."'";
			$this->myDB->execute_none_query($sql);
//			if($result >= 1)
//			{
//				return true;
//			}
//			else
//			{
//				return false;
//			}
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	
	
	public function sub_table_insert($id,$table_name,$obj)
	{
		try
		{
//			echo $test_name =get_class($obj);
//			switch($test_name) 
//			{
//				case "IDAC_Value" : 
//					$table_name	=	"idacvalue";
//					break;
//				case "Global_IDAC_Value" : 
//					$table_name	=	"idacvalue";
//					break;
//				case "Raw_Count_Averages":
//					$table_name	=	"rawcountaverage";
//					break;
//				case "Raw_Count_Noise":
//					$table_name	=	"rawcountnoise";
//					break;
//			}
			
			$combined_value = '';
			foreach($obj as $key=>$val)
			{
				$combined_value	= $combined_value."(NULL, ".$id.",".(int)substr($key,1).",".$val."),";
			}
			$str_id_index_value	=	substr($combined_value,0,-1);
			$sql_statement="insert into `trackpad`.`".$table_name."`" .
						   "values".$str_id_index_value;
			$this->myDB->execute_none_query($sql_statement);	
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	
	public function idd_table_insert($id, $xml_obj)
	{
		try
		{
			$idd_sleep1 = $xml_obj->IDD_Sleep1_Value;
			$idd_deep_sleep = $xml_obj->IDD_Deep_Sleep_Value;
			$sql_statement="insert into `trackpad`.`iddstandby`" .
						   "values (NULL, '".$id."','".$idd_sleep1."','".$idd_deep_sleep."')";
			$result = $this->myDB->execute_none_query($sql_statement);
			return $result;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	public function get_auto_increased_id($database,$table_name)
	{
		try
		{
			$sql_statement="SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$database."'"." AND TABLE_NAME='".$table_name."'";
			$result = $this->myDB->execute_scalar($sql_statement);
			return $result;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}
	
	private function set_stats_sub_table($test_item)
	{
		try 
		{
			switch($test_item) 
			{
				case "idacvalue" : 
					$sub_table = 'statistics_idacvalue';
					break;
				case "rawcountaverage":
					$sub_table = 'statistics_rawcountaverage';
					break;
				case "rawcountnoise":
					$sub_table = 'statistics_rawcountnoise';
					break;
				case "iddvalue":
					$sub_table = 'statistics_iddvalue';
					break;
				case "iddsleep1":
					$sub_table = 'statistics_iddsleep1';
					break;
				case "idddeepsleep":
					$sub_table = 'statistics_idddeepsleep';
					break;
			}
			return $sub_table;
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}

	public function get_weekly_cpk_value($part_number, $test_item, $week_start, $week_end)
	{
		try
		{
			$stats_sub_table = $this->set_stats_sub_table($test_item);
			$sql_statement=	" SELECT `statistics_dut`.`WeekNumber` , `".$stats_sub_table."`.`Cpk`".
							" FROM `statistics_dut`,`".$stats_sub_table."`".
							" WHERE `statistics_dut`.`Id` = `".$stats_sub_table."`.`DutId` " .
							" AND `statistics_dut`.`Project` = '".$part_number."'".
//							" AND `statistics_dut`.`TestStation` = '".$test_station."'".
							" AND `statistics_dut`.`WeekNumber` BETWEEN ".$week_start." AND ".$week_end.";";
			$result = $this->myDB->execute_reader($sql_statement);
			return $result;
		}
		catch (Exception $e)
		{
			$this->error_message=$e->getMessage();
			return $e->getCode();
		}
	}


//	private function test_status_sql_form($serial_number, $test_station, $test_time)
//	{
//		try
//		{
//			$sql =	" SELECT Id FROM dut " .
//					" WHERE SerialNumber = '".$serial_number."'".
//					" AND TestStation = '".$test_station."'".
//					" AND TestTime >=".$test_time."'".
//					" AND TestStatus = 0";
//			$id_list = $this->myDB->execute_reader($sql);
//			if($id_list != -1)
//			{
////				foreach ($id_list as $id)
////				$sql_update =	" UPDATE dut " .
////								" SET TestStatus = 1" .
////								" WHERE Id = ".$id.";";
////				$this->myDB->execute_none_query($sql_update); 
//				return true;
//			}
//			else
//			{
//				return false;
//			}
//		}
//		catch (Exception $e)
//		{
//			$this->error_message=$e->getMessage();
//			return $e->getCode();
//		}
//	}
	
	
	
	
	
//	public function refresh_test_status($serial_number,$test_station,$test_time)
//	{
//		try
//		{
//				$sql_update =	" UPDATE dut " .
//								" SET TestStatus = 1" .
//								" WHERE dut.";
//				$this->myDB->execute_none_query($sql_update);
//				}
//				return true;
//			}
//			else
//			{
//				return false;
//			}
//		}
//		catch (Exception $e)
//		{
//			$this->error_message=$e->getMessage();
//			return $e->getCode();
//		}
//	}
}






?>