<?php
include_once("security_audit_log.inc.php");
class TaskTrap {

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
	
	function TaskTrap() {
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
			
		$sql = "SELECT task_trap.* ".$this->join_field_str." FROM task_trap".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		// echo $sql;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT task_trap.* ".$this->join_field_str." FROM task_trap".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
		
	}
	
	function delete_records($id){
		global $sqlObj;
		
		$sql_del = "DELETE FROM task_trap WHERE task_trap.\"iTTId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);
		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj,$admin_panel_session_suffix;
		if($this->insert_arr){

			//$sql = "INSERT INTO task_trap(\"iSiteId\", \"iSRId\", \"dTrapPlaced\", \"dTrapCollected\",\"iTrapTypeId\", \"bMalfunction\", \"tNotes\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['iSiteId']).", ".gen_allow_null_char($this->insert_arr['iSRId']).", ".gen_allow_null_char($this->insert_arr['dTrapPlaced']).", ".gen_allow_null_char($this->insert_arr['dTrapCollected']).", ".gen_allow_null_char($this->insert_arr['iTrapTypeId']).", ".gen_allow_null_char($this->insert_arr['bMalfunction']).", ".gen_allow_null_char($this->insert_arr['tNotes']).",".gen_allow_null_char(date_getSystemDateTime()).")";
			$sql = "INSERT INTO task_trap(\"iSiteId\", \"iSRId\", \"dTrapPlaced\", \"dTrapCollected\",\"iTrapTypeId\", \"bMalfunction\", \"tNotes\", \"dAddedDate\",\"iUserId\",\"iTechnicianId\") VALUES (".gen_allow_null_char($this->insert_arr['iSiteId']).", ".gen_allow_null_char($this->insert_arr['iSRId']).", ".gen_allow_null_char($this->insert_arr['dTrapPlaced']).", ".gen_allow_null_char($this->insert_arr['dTrapCollected']).", ".gen_allow_null_char($this->insert_arr['iTrapTypeId']).", ".gen_allow_null_char($this->insert_arr['bMalfunction']).", ".gen_allow_null_char($this->insert_arr['tNotes']).",".gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_char($this->insert_arr['iUserId']).",".gen_allow_null_int($this->insert_arr['iTechnicianId']).")";


			//echo $sql;exit;
			$sqlObj->Execute($sql);		
			$iTTId = $sqlObj->Insert_ID();
			
			return $iTTId;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE task_trap SET 
			\"iSiteId\"=".gen_allow_null_char($this->update_arr['iSiteId']).", 
			\"iSRId\"=".gen_allow_null_char($this->update_arr['iSRId']).", 
			\"dTrapPlaced\"=".gen_allow_null_char($this->update_arr['dTrapPlaced']).", 
			\"dTrapCollected\"=".gen_allow_null_char($this->update_arr['dTrapCollected']).", 
			\"iTrapTypeId\"=".gen_allow_null_char($this->update_arr['iTrapTypeId']).", 
			\"bMalfunction\"=".gen_allow_null_char($this->update_arr['bMalfunction']).", 
			\"tNotes\"=".gen_allow_null_char($this->update_arr['tNotes']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime()).",
			\"iUserId\" = ".gen_allow_null_int($this->update_arr['iUserId']).",			
			\"iTechnicianId\" = ".gen_allow_null_int($this->update_arr['iTechnicianId'])."
			WHERE \"iTTId\" = ".$this->update_arr['iTTId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			$iTTId = $this->update_arr['iTTId'];

			return $rs_up;
		}
	}

	function update_labwork_records(){
		global $sqlObj;
		if($this->update_arr){
			$sql_db = "UPDATE task_trap SET \"bLabWorkComplete\"=".gen_allow_null_char($this->update_arr['bLabWorkComplete'])." WHERE \"iTTId\" = ".$this->update_arr['iTTId'];
			//echo $sql_db;exit;
			$sqlObj->Execute($sql_db);
			$rs_up = $sqlObj->Affected_Rows();

			return $rs_up;
		}
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
		if($where_clause1 != ""){
			$where_clause1 = " WHERE ".$where_clause1 ;	
		}

		if($where_clause2 != ""){
			$where_clause2 = " WHERE ".$where_clause2 ;	
		}

	
		$sql_glance =  "select (SELECT count(\"iTTId\") from task_trap ".$where_clause1." ) as trapcount1, ( SELECT  count(\"iTTId\")  from  task_trap  ".$where_clause2." ) as trapcount2 ";
		//echo $sql_glance;exit();
		$rs_db = $sqlObj->GetAll($sql_glance);
		//echo "<pre>";print_r($rs_db);exit();	
		return $rs_db;
	}

}