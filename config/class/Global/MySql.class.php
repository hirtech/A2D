<?php
#######################################
## Created By Dev 			###
#######################################
// Class_Database
class Global_MySql 
{
	var $CONN="";
	var $server_name = "";
	var $user_name ="";
	var $password ="";
	var $database ="";
	
	## Connect to Server ##	
	function Global_MySql($server="",$user="", $pass="") 
	{
		$this->server_name = $server;
		$this->user_name = $user;
		$this->password = $pass;
		$conn = mysql_connect($server,$user,$pass);
		if(!$conn) {
			$this->error("Connection attempt failed"." Error No:".mysql_errno()."<br>Error Message : ". mysql_error());
		}

		$this->CONN = $conn;
		return true;
	}
	
	## Select Database for table operation ##
	function selectDatabase($dbase)
	{	
		if(empty($this->CONN)) { $this->error("Connection not found"); }
		$this->database = $dbase;
		if(!mysql_select_db($dbase, $this->CONN)) {
			$this->error("Dbase Select failed"." Error No:".mysql_errno()."<br>Error Message : ". mysql_error());
		}
	}
	
	## Get Database List ##
	function getDatabaseList()
	{
		$result_db = @mysql_query("SHOW DATABASES") or die(mysql_error());
		//if(!$result_db); return;
		$data = array();
		while ($row = mysql_fetch_array($result_db))
		{
			$data[] = $row;
		}
		mysql_free_result($result_db);
		return $data;		
	}
		
	## Get Database List ##
	function getTableList($db_name)
	{
		if($db_name=='')
			$db_name = $this->database;
		$result = mysql_query("SHOW TABLES FROM ".$db_name);
		//if(!$result_db); return;
		$data = array();
		while ($row = mysql_fetch_array($result))
		{
			$data = $row;
		}
		mysql_free_result($result);
		return $data;	
		
	}
	
	## Close Server connection ##
	function closeConnection()
	{
		$conn = $this->CONN ;
		$close = mysql_close($conn);
		if(!$close) {
			$this->error("Connection close failed"." Error No:".mysql_errno()."<br>Error Message : ". mysql_error());
		}
		return true;
	}
	
	## Close Server connection
	function error($text)
	{
		echo $text;
		exit;
	}
	
	## Execute Select Query
	## $ret_type = Array (as doble dimention array)
	## $ret_type = Assoc, $ass_field = 'Take the field name to be associated'
	## $ret_type = Comma, eg. Return will be 2,3,5
	## $ret_type = Single (as single dimention array) // Modified by PN as on 5 Apr 2013...
	function select ($sql="", $fetch = "mysql_fetch_assoc", $ret_type='Array', $ass_field='')
	{
		if(empty($sql)) { $this->error("Select Query not found"); }
		if(empty($this->CONN)) { $this->error("Connection not found");}
		$conn = $this->CONN;
		$results = @mysql_query($sql,$conn);
		if(!$results) {
			$this->error("Error in Query : ".$sql."<br>".mysql_errno()." : ". mysql_error());	
		}
		if($fetch=='') $fetch = "mysql_fetch_assoc";
		if($ret_type=='Array'){
			$data = array();
			while ($row = $fetch($results))
			{
				$data[] = $row;
			}
		}else if($ret_type=='Single'){
			$data = array();
			while ($row = mysql_fetch_array($results,MYSQL_NUM))
			{
				$data[] = $row[0];
			}
		}else if($ret_type=='Assoc'){
			$data = array();
			while ($row = $fetch($results))
			{
				$data[$row[$ass_field]] = $row;
			}
		}else if($ret_type=='Comma'){
			$data = '';
			while ($row = mysql_fetch_array($results))
			{
				$data .= $row[0].",";
			}
			$data = substr($data,0,-1);
		}
		else if($ret_type=='AssocMulti'){
			$data = array();
			while ($row = $fetch($results))
			{
				$data[$row[$ass_field]][] = $row;
			}
		}
		mysql_free_result($results);
		return $data;
	}
	
	
	
	## Execute Insert Query
	function insert ($sql="")
	{
		if(empty($sql)) { $this->error("Insert query not found"); }
		/*if(!eregi("^insert",$sql))
		{
			return false;
		}*/
		if(empty($this->CONN))
		{
			$this->error("Connection not found");
		}
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			$this->error("Error in Query : ".$sql."<br>".mysql_errno()." : ". mysql_error());	
		}
		$id = mysql_insert_id();
		If($id)
			return $id;
		else
			return 1;
	}
	
	## Execute Update / Delete / mulitple insert query
	function execute($sql="")
	{
		if(empty($sql)) { $this->error("Query not found"); }
		if(empty($this->CONN))
		{
			$this->error("Connection not found");
		}
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			$this->error("Error in Query : ".$sql."<br>".mysql_errno()." : ". mysql_error());			
		}
		$rows = 0;
		$rows = mysql_affected_rows();
		if($rows==0)	return 1;
		return $rows;
	}
	
	## Insert Record through Array
	## eg. ('product', array('iProductId'=>101, 'vName'=>'Computer', 'iQty'=>3));
	function insertArray($table_name, $fields_value_arr)
	{
		if(!is_array($fields_value_arr) || count($fields_value_arr)==0)
		{
			$this->error("Array is not Specified.");
		}
		$fields = '';
		$values = '';
		foreach($fields_value_arr as $key=>$val)
		{
			$fields .= $key .", ";
			$values .= "'". $val . "', ";
		}
		$fields = substr($fields, 0, -2);
		$values = substr($values, 0, -2);
		$query = "INSERT INTO ". $table_name. "(". $fields . ") VALUES (". $values .")";
		return $this->insert($query);
	}
	
	## Update Record through Array
	## eg. ('product', array('iProductId'=>101, 'vName'=>'Computer', 'iQty'=>3), "eStatus='Active'");
	function updateArray($table_name, $fields_value_arr, $where_clause='')
	{
		if(!is_array($fields_value_arr) || count($fields_value_arr)==0)
		{
			$this->error("Array is not Specified.");
		}
		$query = "UPDATE ". $table_name. " set ";
		$fields = '';
		$values = '';
		foreach($fields_value_arr as $key=>$val)
		{
			$query .= $key. "='". $val . "', ";
		}
		$query = substr($query, 0, -2);
		if($where_clause !=''){
			if(strstr($where_clause, "WHERE")){
				$query .=" ". $where_clause;
			}
			else{
				$query .=" WHERE ". $where_clause;
			}
		}
		return $this->insert($query);
	}
	
	## Get Feilds of Table
	function getTableFields($table)
	{
		$fields = mysql_list_fields($this->database, $table, $this->CONN); 
		$columns = mysql_num_fields($fields); 
		for ($i = 0; $i < $columns; $i++) { 
		   $arr[]= mysql_field_name($fields, $i); 
		}
		return $arr;
	}

	## Get Values of enum Database Field
	function getEnumValues($table, $field)
	{
	   	$sql = "SHOW COLUMNS FROM $table LIKE '$field'";
	   	$results = mysql_query($sql);
	   
	   	if(!$results) 
		{
			$this->error("Error in Query : ".$sql."<br>".mysql_errno()." : ". mysql_error());			
		}
	   
	   	$row = mysql_fetch_assoc($results);
	   	mysql_free_result($results);
	  	return explode("','",
		   preg_replace("/.*\('(.*)'\)/", "\\1", $row["Type"]));
	}

	## Do optize tables
	function optimizeTables(){
		$conn = $this->CONN;
		$alltables = mysql_query("SHOW TABLES",$conn) or die("Error: " . mysql_error());
		$table_arr = array();
		while ($table = mysql_fetch_assoc($alltables))
		{ 
			foreach ($table as $db => $tablename)
			{  
				$query="OPTIMIZE TABLE ".$tablename.";";   
				mysql_query($query,$conn) or die("Query failed: " . mysql_error());
				$results = mysql_query($query,$conn);
				if($results) 
				{
					$table_arr[] = $tablename;
				}
			}
		}
		return $table_arr;
	}
	
	## Backup Database and save at specified location
	function backupDatabase($backup_path, $backup_name_prefix)
	{
		$server = $this->server_name;
		$user = $this->user_name;
		$pass = $this->password;
		$dbase = $this->database;
		$day = date("Y-m-d_H-m-s");
		$file_name = $backup_name_prefix.'_'.$day.'.sql';
  		$backup = $backup_path.$file_name;
		
		$last_line = exec(sprintf('mysqldump --host=%s --user=%s --password=%s %s --quick --lock-tables --add-drop-table > %s', $server, $user, $pass, $dbase, $backup));
		//echo $last_line;exit;
		return $file_name;
	}

	## Restore Database from specified location
	function restoreDatabase($fileName)
	{
		$user = $this->user_name;
		$pass = $this->password;
		$dbase = $this->database;
		@exec("mysql -u$user -p$pass $dbase < $fileName");
		//@exec("mysql -u$USERNAME -p$PASSWORD $DBASE < $fileName");
	}
	function select_query ($sql="", $fetch = "mysql_fetch_assoc", $ret_type='Array', $ass_field='')
	{
		if(empty($sql)) { $this->error("Select Query not found"); }
		if(empty($this->CONN)) { $this->error("Connection not found");}
		$conn = $this->CONN;
		$results = @mysql_query($sql,$conn);
		if(!$results) {
			$this->error("Error in Query : ".$sql."<br>".mysql_errno()." : ". mysql_error());	
		}
		if($fetch=='') $fetch = "mysql_fetch_assoc";
		if($ret_type=='Array'){
			$data = array();
			while ($row = $fetch($results))
			{
				$data[] = $row;
			}
		}
		else if($ret_type=='Single'){
			$data = array();
			while ($row = mysql_fetch_array($results,MYSQL_NUM))
			{
				$data[] = $row[0];
			}
		}
		else if($ret_type=='Assoc'){
			$data = array();
			while ($row = $fetch($results))
			{
				$data[$row[$ass_field]] = $row;
			}
		}else if($ret_type=='Comma'){
			$data = '';
			while ($row = mysql_fetch_array($results))
			{
				$data .= $row[0].",";
			}
			$data = substr($data,0,-1);
		}
		mysql_free_result($results);
		return $data;
	}
}
?>