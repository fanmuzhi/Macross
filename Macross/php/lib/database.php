<?php

class MyDatabase
{
	
	//
	// Constant
	//
	private $config_file = '../config/database.xml'; 
	
	private $databaseServer = '172.23.6.12';
	private $databaseName = 'trackpad';
	private $userName = 'qibo';
	private $passWord = '123456';
	
	//
	// Property
	//
	public $db;
	public $db_connected;
	
	//
	// Function: Connect to database
	//
	public function connect_database()
	{
		//$this->db = new PDO("mysql:host=172.23.6.12;dbname=trackpad",'qibo','123456');
		
		
		// if config file exists, read the database config.
		// else use the default.
		// dirname(__FILE__) is the absolute path of this file
		if(file_exists(dirname(__FILE__).'/'.$this->config_file))
		{
			$xml=simplexml_load_file(dirname(__FILE__).'/'.$this->config_file);
				
			$this->databaseServer = $xml->host;
			$this->databaseName = $xml->dbname;
			$this->userName = $xml->user;
			$this->passWord = $xml->pass;
		
		}
	
		
		$dsn_string="mysql:host=".$this->databaseServer.";dbname=".$this->databaseName;
		$this->db = new PDO($dsn_string,$this->userName,$this->passWord );

		$this->db_connected=TRUE;

	}
	
	
	//
	// Function: Disconnect to database
	//
	public function disconnect_database()
	{
		$this->db = null;
		$this->db_connected=FALSE;
	}
	
	
	//
	// Function: Show database version
	//
	public function show_database_version()
	{

		if(!$this->db_connected)
		{
			$this->connect_database();
		}

		$sql_command="SELECT VERSION()";
		$sth=$this->db->query($sql_command);
		$result_array=$sth->fetch(PDO::FETCH_NUM);
		
		if($result_array!=null)
		{
			foreach($result_array as $result)
			{
				$version_number=$result;
			}
	
			//$this->_disconnect_database();
			return "Connected to: IP address:".$this->databaseServer." mySQL version: ".$version_number;
		}
		else 
		{
			return -1;
		}

	}
	

	//
	// Function: Execute Scalar
	//
	public function execute_scalar($sql)
	{
		if(!$this->db_connected)
		{
			$this->connect_database();
		}

		$sth=$this->db->prepare($sql);
		$sth->execute();
		$result_array=$sth->fetch(PDO::FETCH_NUM);

		if($result_array!=null)
		{
			foreach($result_array as $result)
			{
				$scalar=$result;
			}

			//$this->disconnect_database();
			return $scalar;
		}
		else
		{
			return -1;
		}
	}
	
	//
	// Function: Execute None Query
	//
	public function execute_none_query($sql)
	{
		if(!$this->db_connected)
		{
			$this->connect_database();
		}
		
		$sth=$this->db->prepare($sql);
		$sth->execute();
		return $sth->rowCount();

	}
	
	//
	// Function: Execute Reader
	//
	public function execute_reader($sql)
	{
		if(!$this->db_connected)
		{
			$this->connect_database();
		}
		
		$sth=$this->db->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		
		$result_array=$sth->fetchAll();
		
		//print_r($result_array);
		
		if($result_array!=null)
		{
			return $result_array;
		}
		else
		{
			return -1;
		}
	}
	
}



?>