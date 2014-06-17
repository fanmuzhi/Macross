<?php

class PartNumber
{
	//
	// Constant
	//
	private $part_type_pattern = '/^[01]([0][0-9]|[1][0-9])([0][0-9][0-9])[0][01]$/';
	private $myDBAPI;
	//
	// Property
	//
	public $name;
	public $start_date;
	public $end_date;
	
	public $totalTested;
	public $totalFailed;
	public $totalYiled;
	
	//
	// Construction
	//
	function PartNumber($part_number)
	{
		$this->myDBAPI = new MyDataBaseAPI();
		
		if($this->IsValid($part_number))
		{
			$this->name 		= $part_number;
			$this->start_date 	= $this->myDBAPI->get_starttime($part_number);
			$this->end_date   	= $this->myDBAPI->get_endtime($part_number);
		}
	}
		
	//
	// Destruction
	//
	function __destruct()
	{
		$this->name 		= null;
		$this->start_date 	= null;
		$this->end_date   	= null;
		
	}

	//
	// Function: check the part number if valid or not
	//
	private function IsValid($part_number)
	{
		if(preg_match($this->part_type_pattern, $part_number))
		{
			
			$part_number_array=$this->myDBAPI->get_partnumber_list();
			
			if(in_array($part_number, $part_number_array))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	//
	// Function: check error code
	//
	public function IsValid_ErrorCode($ErrorCode)
	{
		$error_code_array=$this->myDBAPI->get_errorcode_list($this->name);
		
		if(count($error_code_array)>0)
		{
			if(in_array($ErrorCode, $error_code_array))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
}


?>