<?php
//require_once "/usr/share/pear/Math/Stats.php";
//require_once "C:/xampp/php/pear/Math/Stats.php";
require_once dirname(__FILE__).'/Stats.php';


class capbility_index
{
	public $error_message;
	
	private $config_folder = '../config/test_limit_config/'; 
	private $myDB;
	
	private $part_type;
	private $test_station;
	private $test_item;
	private $sample_quantity;
	private $index_number= NULL;
	private $upper_limit = NULL;
	private $lower_limit = NULL;
	private $week_number;
	private $digit;
	private $sub_table;
	private $sub_col;
//	private $limit_max;
//	private $limit_min;
	private $basic_stats = array();
	
	public function __construct()
	{
		try
		{
			$this->myDB	=new MyDatabase();
			$this->myDB->connect_database();
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			
		}
		
//		echo "db_connected"."\n";
	}
	
	public function __destruct()
	{
		try
		{
			$this->myDB->disconnect_database();
			$this->part_type = null;
			$this->test_station= null;
			$this->test_item= null;
			$this->sample_quantity= null;
			$this->index_number= null;
			$this->sample_quantity= null;
			$this->upper_limit= null;
			$this->lower_limit= null;
			$this->week_number= null;
			$this->sub_table = null;
			$this->sub_col = null;
//			$this->limit_route=null;
			$this->basic_stats=	null;
			$this->digit = null;
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
		}
	}
	
	private function set_sub_table_name()
	{
		try 
		{
			switch($this->test_item) 
			{
				case "idacvalue" : 
					$this->sub_table = 'idacvalue';
					break;
				case "rawcountaverage":
					$this->sub_table = 'rawcountaverage';
					break;
				case "rawcountnoise":
					$this->sub_table = 'rawcountnoise';
					break;
				case "iddvalue":
					$this->sub_table = 'iddvalue';
					break;
				case "iddsleep1":
					$this->sub_table = 'iddstandby';
					break;
				case "idddeepsleep":
					$this->sub_table = 'iddstandby';
					break;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	private function set_column_name()
	{
		try
		{
			switch($this->test_item) 
			{
				case "idacvalue" : 
					$this->sub_col	=	"IDACValue";
					break;
				case "rawcountaverage":
					$this->sub_col	=	"RawCountAverage";
					break;
				case "rawcountnoise":
					$this->sub_col	=	"RawCountNoise";
					break;
				case "iddvalue":
					$this->sub_col	=	"IDDValue";
					break;
				case "iddsleep1":
					$this->sub_col	=	"IDDSleep1";
					break;
				case "idddeepsleep":
					$this->sub_col	=	"IDDDeepSleep";
					break;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	private function digit_place()
	{
		try
		{
			switch($this->test_item) 
			{
				case "idacvalue" : 
					$this->digit	=	0;
					break;
				case "rawcountaverage":
					$this->digit	=	0;
					break;
				case "rawcountnoise":
					$this->digit	=	0;
					break;
				case "iddvalue":
					$this->digit	=	0;
					break;
				case "iddsleep1":
					$this->digit	=	0;
					break;
				case "idddeepsleep":
					$this->digit	=	1;
					break;
			}
			//echo $this->digit;
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	private function set_basic_variables()
	{
		try 
		{
			$this->set_sub_table_name();
			$this->set_column_name();
			$this->digit_place();
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	private function set_params($part_type,$test_station ,$test_item,$sample_quantity = 5000, $index_number = NULL)
	{
		try
		{
			$this->part_type		=	$part_type;
			$this->test_station		=	$test_station;
			$this->test_item		=	$test_item;
			$this->sample_quantity	=	$sample_quantity;
			$this->index_number		= 	$index_number;
			$this->sample_quantity 	= 	$sample_quantity;
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
		
	}
	
	private function set_params_by_week($part_type, $test_station ,$test_item, $index_number = NULL,$week_number)
	{
		try
		{
			$this->part_type		=	$part_type;
			$this->test_station		=	$test_station;
			$this->test_item		=	$test_item;
			$this->index_number		= 	$index_number;
			$this->week_number		=	$week_number;
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	private function get_boundry()
	{
		try 
		{
			$config_file=dirname(__FILE__).'/'.$this->config_folder.$this->part_type.".xml";
			if(file_exists($config_file))
			{
				$xml=simplexml_load_file($config_file);
				switch($this->test_item) 
				{
					case "idacvalue" : 
						$limit_min = $xml->TMT->IDAC_MIN;
						$limit_max = $xml->TMT->IDAC_MAX;
						break;
					case "rawcountaverage":
						$limit_min = $xml->TMT->RAW_AVG_MIN;
						$limit_max = $xml->TMT->RAW_AVG_MAX;
						break;
					case "rawcountnoise":
						$limit_min = $xml->TMT->RAW_NOISE_MIN;
						$limit_max = $xml->TMT->RAW_NOISE_MAX;
						break;
					case "iddvalue":
						$limit_min = $xml->IDD_MIN;
						$limit_max = $xml->IDD_MAX;
						break;
					case "iddsleep1":
						$limit_min = $xml->IDD->IDD_SLEEP1_MIN;
						$limit_max = $xml->IDD->IDD_SLEEP1_MAX;
						break;
					case "idddeepsleep":
						$limit_min = $xml->IDD->IDD_DEEP_SLEEP_MIN;
						$limit_max = $xml->IDD->IDD_DEEP_SLEEP_MAX;
						break;
				}
				if(isset($limit_min))
					$this->lower_limit	=	(float)$limit_min;
				if(isset($limit_max));
					$this->upper_limit	=	(float)$limit_max;
				
				return true;	
//				else
//				{
//					$this->error_message= "Cannot find the limit item in Config File ".$this->part_type;
//					return false;
//				}
			}
			else
			{
				$this->error_message= "Cannot find the config file of ".$this->part_type;
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}

	private function sample_gather()
	{
		try
		{
			if($this->test_item == 'iddvalue')
			{
				$sql_select	=	" SELECT dut.Id, dut.".$this->sub_col;
				$sql_from	=	" FROM dut ";
				$sql_where	=	" WHERE dut.PartType = ".$this->part_type.
								" AND dut.TestStation = "."'".$this->test_station."'";
				$sql_filter	=	" ORDER BY dut.TestTime DESC ".
								" LIMIT ".$this->sample_quantity;
			}
			else 
			{
				$sql_select	=	" SELECT dut.Id, ".$this->sub_table.".".$this->sub_col;
				$sql_from	=	" FROM dut, ".$this->sub_table;
				$sql_where	=	" WHERE ".
								" dut.Id = ".$this->sub_table.".DUTID" .
								" AND dut.PartType = ".$this->part_type.
								" AND dut.TestStation = "."'".$this->test_station."'";
				$sql_filter	=	" ORDER BY dut.TestTime DESC ".
								" LIMIT ".$this->sample_quantity;
//				echo 'xxx '.$this->index_number;
				if($this->index_number != NULL)
				{
					$sql_where	=	$sql_where." AND ".$this->test_item.".ValueIndex = ".$this->index_number;
				}
			}
			$sql0 = $sql_select. $sql_from. $sql_where." AND dut.TestStatus = 0 ".$sql_filter;
			$result = $this->myDB->execute_reader($sql0);
			if($result != -1)
			{
				$sample = array();
				foreach ($result as $log)
				{
					array_push($sample , round($log[$this->sub_col],$this->digit));
				}
				//print_r($sample);
				return $data = array_slice($sample, 0, $this->sample_quantity);									
			}
			else
			{
				$this->error_message= "Cannot find the fetch result from sql query";
				return false;
			}
		}
		catch(Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	private function sample_gather_by_week()
	{
		try
		{
			if($this->test_item == 'iddvalue')
			{
				$sql_select	=	" SELECT dut.".$this->sub_col;
				$sql_from	=	" FROM dut ";
				$sql_where	=	" WHERE dut.PartType = ".$this->part_type.
								" AND dut.TestStation = "."'".$this->test_station."'".
								" AND RIGHT(YEARWEEK(dut.TestTime,3),4)= ".$this->week_number;
				
			}
			else 
			{
				$sql_select	=	" SELECT ".$this->sub_table.".".$this->sub_col;
				$sql_from	=	" FROM dut, ".$this->sub_table;
				$sql_where	=	" WHERE ".
								" dut.Id = ".$this->sub_table.".DUTID" .
								" AND dut.PartType = ".$this->part_type.
								" AND dut.TestStation = "."'".$this->test_station."'".
								" AND RIGHT(YEARWEEK(dut.TestTime,3),4)= ".$this->week_number;
								
				if($this->index_number != NULL)
				{
					$sql_where	=	$sql_where." AND ".$this->test_item.".ValueIndex = ".$this->index_number;
				}
			}
			$sql0 = $sql_select. $sql_from. $sql_where." AND dut.TestStatus =0 LIMIT 1000000 ";
			$result = $this->myDB->execute_reader($sql0);
			if($result != -1)
			{
				$sample = array();
				foreach ($result as $log)
				{
					array_push($sample , round($log[$this->sub_col],$this->digit));
				}
				return $data = array_slice($sample, 0, $this->sample_quantity);									
			}
			else
			{
				$this->error_message= "Cannot find the fetch result from sql query";
				return false;
			}
		}
		catch(Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}



	private function basic_stats()
	{
		try
		{
			$data	=	$this->sample_gather();
			if($data != false)
			{
				$compute	=	new Math_Stats();
				$compute->setData($data);
				$this->basic_stats = $compute->calcBasic();
//				print_r($this->basic_stats);
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}

	private function basic_stats_by_week()
	{
		try
		{
			$data	=	$this->sample_gather_by_week();
			if($data != false)
			{
				$compute	=	new Math_Stats();
				$compute->setData($data);
				$this->basic_stats = $compute->calcBasic();
//				print_r($this->basic_stats);
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	private function calculate_CP()
	{
		try
		{
			if($this->lower_limit != NULL or $this->upper_limit != NULL)
			{
				if($this->lower_limit != NULL)
				{
					$lower_limit = $this->lower_limit;
				}
				else
				{
					$lower_limit = $this->upper_limit;
				}	
				if($this->upper_limit != NULL)
				{
					$upper_limit = $this->upper_limit;
				}
				else
				{
					$upper_limit = $this->lower_limit;
				}
				return $CP = (abs($upper_limit-$this->basic_stats[mean])+abs($this->basic_stats[mean]-$lower_limit))/(6*($this->basic_stats[stdev]));
			}
			else
			{
				$this->error_message= "either lower limit nor upper limit exists!";
				return false;
			}
		}
		catch (Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}

	private function calculate_CA()
	{
		try
        {
            if($this->upper_limit != NULL and $this->lower_limit !=NULL)
            {
                    $C = ($this->upper_limit+$this->lower_limit)/2;
                    $T = ($this->upper_limit-$this->lower_limit);
                    return $CA = abs(($this->basic_stats[mean]-$C)/($T/2));
            }
            else
            {
                    return false;
            }
        }
		catch (Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	public function calculate_CPK($part_type,$test_station ,$test_item, $sample_quantity = 5000 , $index_number = NULL)
	{
		try
		{
			$this->set_params($part_type, $test_station , $test_item, $sample_quantity , $index_number);
			$tmp_params = array('PartType'		=> "='$part_type'",
								'TestStation'	=> "='$test_station'",
								'TestItem'		=> "='$test_item'",
								'IndexNumber'	=> "='$index_number'",
								'SampleQuantity'=> "='$sample_quantity'");
			$apc_key_CPK='CPK_';
			foreach ($tmp_params as $key=>$value)
			{
				$apc_key_CPK.=$key.' '.$value.'_';
//				echo "\n";
			}
			if(apc_exists($apc_key_CPK))
			{	
				$serialized_result=apc_fetch($apc_key_CPK);
				$unserialized_result	=	unserialize($serialized_result);
				$response	=	$unserialized_result;
//	 			print "Found in APC: ".$apc_key_CPK."{".$unserialized_result."}";
			}	
			else
			{
				$this->set_basic_variables();
				if($this->get_boundry())
				{
					$this->basic_stats();
					$limit_array	=	array("lower_limit"=>$this->lower_limit,
											  "upper_limit"=>$this->upper_limit);
	//				print_r($limit_array);
					$stats	=	array_merge($limit_array,$this->basic_stats);
	//				print_r($stats);
					$CP	=	$this->calculate_CP();
					$CA	=	$this->calculate_CA();
					if ($CA != false)
					{
						$CPK = round($CP*(abs(1-$CA)),2);
						$indices	=	array("CP"=>round($CP,2),"CPK"=>round($CPK,2));
					}
					else
					{
						$indices	=	array ("CP"=>round($CP,2),"CPK"=>round($CP,2));
					}
					$result = array_merge($stats,$indices);
	//				print_r($result);
					$result_serialized	=	serialize($result);
					apc_add($apc_key_CPK,$result_serialized);
					$response=$result;
				}
				else
				{
					$response = false;
				}	
			}		
			return $response;
		}
		catch (Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	public function calculate_CPK_By_Week($part_type, $test_station ,$test_item, $index_number = NULL,$week_number)
	{
		try
		{
			$this->set_params_by_week($part_type, $test_station ,$test_item, $index_number = NULL,$week_number);
			$tmp_params = array('PartType'		=> "='$part_type'",
								'TestStation'	=> "='$test_station'",
								'WeekNumber'	=> "='$week_number'",
								'TestItem'		=> "='$test_item'",
								'IndexNumber'	=> "='$index_number'"
								);
			$apc_key_CPK='CPK_';
			foreach ($tmp_params as $key=>$value)
			{
				$apc_key_CPK.=$key.' '.$value.'_';
//				echo "\n";
			}
			if(apc_exists($apc_key_CPK))
			{	
				$serialized_result=apc_fetch($apc_key_CPK);
				$unserialized_result	=	unserialize($serialized_result);
				$response	=	$unserialized_result;
//	 			print "Found in APC: ".$apc_key_CPK."{".$unserialized_result."}";
			}	
			else
			{
				$this->set_basic_variables();
				if($this->get_boundry())
				{
					$this->basic_stats_by_week();
					$limit_array	=	array("lower_limit"=>$this->lower_limit,
											  "upper_limit"=>$this->upper_limit);
					$stats	=	array_merge($limit_array,$this->basic_stats);
	//				print_r($stats);
					$CP	=	$this->calculate_CP();
					$CA	=	$this->calculate_CA();
					if ($CA != false)
					{
						$CPK = round($CP*(abs(1-$CA)),2);
						$indices	=	array("CP"=>round($CP,2),"CPK"=>round($CPK,2));
					}
					else
					{
						$indices	=	array ("CP"=>round($CP,2),"CPK"=>round($CP,2));
					}
					$result = array_merge($stats,$indices);
	//				print_r($result);
					$result_serialized	=	serialize($result);
					apc_add($apc_key_CPK,$result_serialized);
					$response=$result;
				}
				else
				{
					$response = false;
				}	
			}		
			return $response;
		}
		catch (Expection $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	



}
?>