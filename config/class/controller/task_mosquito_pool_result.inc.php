<?php
include_once("security_audit_log.inc.php");
class TaskMosquitoPoolResult {

	var $join_field = array();
	var $join = array();
	var $where = array();
	var $param = array();
	var $ids = 0;
	var $action;
	var $insert_arr = array();
	var $update_arr = array();
	var $join_field_str="";
	var $where_clause="";
	var $join_clause="";
	var $order_by_clause="";
	var $group_by_clause="";
	var $limit_clause = "";
	var $debug_query = false;
	
	function TaskMosquitoPoolResult() {
		$this->SALObj = new Security_audit_log();
	}
	
	function setClause() {			
		//Join Fields for select query	
		if(is_array($this->join_field) && count($this->join_field) > 0){
				$this->join_field_str = ", ".implode(", ", $this->join_field);
		}
		// Join clause
		if(is_array($this->join) && count($this->join) > 0){
			$this->join_clause = " ".implode(" ", $this->join);	
		}
		// Where clause
		if(is_array($this->where) && count($this->where) > 0){
			$this->where_clause = " WHERE ".implode(" AND ", $this->where);	
		}
		
		if(is_array($this->param) && count($this->param) > 0){		
			// Order by clause
			if(!empty($this->param['order_by']))
				$this->order_by_clause = " ORDER BY ".$this->param['order_by'];
				
			// Group by clause
			if(!empty($param['group_by']))
				$this->group_by_clause = " GROUP BY ".$this->param['group_by'];
			
			// Limit clause
			if(!empty($this->param['limit'])){
				if(intval($this->param['limit']) > 0){
					//$this->limit_clause = " LIMIT 0, ".intval($this->param['limit']);
					$this->limit_clause = " LIMIT ".intval($this->param['limit'])." OFFSET 0";
				}
				else if(strstr($this->param['limit'], "LIMIT")){
					$this->limit_clause = " ".$this->param['limit'];
				}
				else{
					$this->limit_clause = " LIMIT ".$this->param['limit'];
				}		
			}
			else{
				$this->limit_clause="";
			}
		}
	}

	function recordset_list()
	{
		global $sqlObj;
			
		$sql = "SELECT task_mosquito_pool_result.* ".$this->join_field_str." FROM task_mosquito_pool_result".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		// echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT task_mosquito_pool_result.* ".$this->join_field_str." FROM task_mosquito_pool_result".$this->join_clause.$this->where_clause.$this->group_by_clause;
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();

		return $count;
		
	}
	function add_records(){
		global $sqlObj,$admin_panel_session_suffix;
		if($this->insert_arr){
			$sql = "INSERT INTO task_mosquito_pool_result(\"iTMPId\", \"iAMId\", \"iTMMId\", \"iValue\",\"iResultId\",\"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['iTMPId']).",".gen_allow_null_char($this->insert_arr['iAMId']).",".gen_allow_null_char($this->insert_arr['iTMMId']).",".gen_allow_null_int($this->insert_arr['iValue']).",".gen_allow_null_char($this->insert_arr['iResultId']).",".gen_allow_null_char(date_getSystemDateTime()).")";
			 $sqlObj->Execute($sql);		
			 $iTMPRId = $sqlObj->Insert_ID();
			 return $iTMPRId;
		}
	}

	function update_records(){
		global $sqlObj;
		if($this->update_arr){
			$rs_db = "UPDATE task_mosquito_pool_result SET 
			\"iTMPId\"=".gen_allow_null_char($this->update_arr['iTMPId']).", 
			\"iAMId\"=".gen_allow_null_char($this->update_arr['iAMId']).", 
			\"iTMMId\"=".gen_allow_null_char($this->update_arr['iTMMId']).", 
			\"iValue\"=".gen_allow_null_int($this->update_arr['iValue']).", 
			\"iResultId\"=".gen_allow_null_char($this->update_arr['iResultId']).",
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." 
			WHERE \"iTMPRId\" = ".$this->update_arr['iTMPRId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			
			return $rs_up;
		}
	}

	function delete_records($id){
		global $sqlObj;
		
		$sql_del = "DELETE FROM task_mosquito_pool_result WHERE task_mosquito_pool_result.\"iTMPRId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);
		return $rs_del;
	}

	


	function clear_variable(){
		$this->join_field = array();
		$this->join = array();
		$this->where = array();
		$this->param = array();
		$this->ids = 0;
		$this->action="";
		$this->insert_arr = array();
		$this->update_arr = array();
		$this->join_field_str="";
		$this->where_clause="";
		$this->join_clause="";
		$this->order_by_clause="";
		$this->group_by_clause="";
		$this->limit_clause = "";
	}

	function recordset_glance_data($where_clause1 = "",$where_clause2 ="")
	{	global $sqlObj;
		
		$sql_glance =  "select (SELECT count(\"iTMPRId\") from task_mosquito_pool_result  WHERE \"iResultId\" ='3' And ".$where_clause1." ) as postivepoolcount1 , ( SELECT count(\"iTMPRId\") from task_mosquito_pool_result  WHERE \"iResultId\" ='3' And  ".$where_clause2." ) as postivepoolcount2 ";
		//echo $sql_glance;exit();
		$rs_db = $sqlObj->GetAll($sql_glance);
		//echo "<pre>";print_r($rs_db);exit();	
		return $rs_db;
	}
}

?>