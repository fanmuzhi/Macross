<?php
class Valid
{
	//
	// Constant
	//
	private $part_type_pattern		=	'/^[01]([0][0-9]|[1][0-9])([0][0-9][0-9])[0][01]$/';
	private $serial_number_pattern	=	'/^[01]([0][0-9]|[1][0-9])([0][0-9][0-9])[0][01][0][1-9]([0][1-9]|[1][0-5])([0-4][0-9]|[5][0-3])[0-9][a-zA-Z0-9][a-zA-Z0-9][a-zA-Z0-9][1-8]$/';
	private $test_station_pattern	=	'/^[T][PM][T]|[I][D][D]$/';
	
	private $test_item_pattern		=	array('iddvalue','idacvalue','rawcountaverage','rawcountnoise','iddsleep1','idddeepsleep');
	private $error_code_pattern		=	array(0,10,11,12,21,31,32,41,51,52,53,54,55,61,62,63,64,65,81,91,92);
	
	private $myDBAPI;
	
	
	function __construct()
	{
		$this->myDBAPI = new MyDataBaseAPI();
		
//		if($this->IsValid($unchecked))
//		{
//			$this->name 		= $unchecked;
//		}
	}
	
	function __destruct()
	{
		$this->name = null;
	}
	
	private function Preg_IsValid($patter,$value)
	{
		try
		{
			if(preg_match($patter, $value))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	private function InArray_isValid($value,$array_pattern)
	{
		try
		{
			if(in_array($value,$array_pattern))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	public function serial_number_isValid($serial_number)
	{
		try
		{
//			echo "xxx".$serial_number."xxx"."\n";
			if($this->Preg_IsValid($this->serial_number_pattern,"$serial_number"))
			{
				return true;
			}
			else
			{
//				echo " Serial Number is Not Valid!"."\n";
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	public function part_type_isValid($part_type)
	{
		try
		{
			if($this->Preg_IsValid($this->part_type_pattern,$part_type))
			{
				$part_number_array=$this->myDBAPI->get_partnumber_list();
				if(in_array($part_type, $part_number_array))
				{
					return true;
				}
				else
				{
//					echo " PartType is Not Valid!"."\n";
					return false;
				}
			}
			else
			{
				return false;
			}	
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	
	public function test_station_isValid($test_station)
	{
		try
		{
			if($this->Preg_IsValid($this->test_station_pattern,$test_station))
			{
				return true;
			}
			else
			{
//				echo " TestStation is Not Valid!"."\n";
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	public function test_item_isValid($test_item)
	{
		try
		{
			if($this->InArray_isValid($test_item,$this->test_item_pattern))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	public function error_code_isValid($error_code)
	{
		try
		{
			if($this->InArray_isValid((int)$error_code,$this->error_code_pattern))
			{
				return true;
			}
			else
			{
//				echo " ErrorCode is Not Valid!"."\n";
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	public function test_log_isValid($obj)
	{
		try
		{
			$SN_Valid = $this->serial_number_isValid($obj->Serial_Number);
			$TS_Valid = $this->test_station_isValid(substr($obj->Test_Station,0,3));
			$EC_Valid = $this->error_code_isValid($obj->Error_Code);
			if($SN_Valid and $TS_Valid and $EC_Valid)
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	public function sample_quantity_isValid($sample_quantity)
	{
		try
		{
			if(is_numeric($sample_quantity) and $sample_quantity <=20000 and $sample_quantity >=5000)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->error_message=$ex->getMessage();
			return false;
		}
	}
	
	
	
	
}




?>
