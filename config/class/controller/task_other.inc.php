<?php
include_once("security_audit_log.inc.php");
class TaskOther {

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
	
	function TaskOther() {
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
			
		$sql = "SELECT task_other.* ".$this->join_field_str." FROM task_other".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		//echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT task_other.* ".$this->join_field_str." FROM task_other".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql_del = "DELETE FROM task_other WHERE task_other.\"iTOId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);
		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			//echo"<pre>";print_r($this->insert_arr);exit;
			$sql = "INSERT INTO task_other(\"iSiteId\", \"iSRId\", \"dDate\", \"dStartDate\",\"dEndDate\", \"iTaskTypeId\", \"tNotes\", \"dAddedDate\",\"iUserId\",\"iTechnicianId\") VALUES (".gen_allow_null_char($this->insert_arr['iSiteId']).", ".gen_allow_null_char($this->insert_arr['iSRId']).", ".gen_allow_null_char($this->insert_arr['dDate']).", ".gen_allow_null_char($this->insert_arr['dStartDate']).", ".gen_allow_null_char($this->insert_arr['dEndDate']).", ".gen_allow_null_char($this->insert_arr['iTaskTypeId']).", ".gen_allow_null_char($this->insert_arr['tNotes']).",".gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_int($this->insert_arr['iUserId']).",".gen_allow_null_int($this->insert_arr['iTechnicianId']).")";
			//echo $sql;exit;
			$sqlObj->Execute($sql);		
			$iTOId = $sqlObj->Insert_ID();

			return $iTOId;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE task_other SET 
			\"iSiteId\"=".gen_allow_null_char($this->update_arr['iSiteId']).", 
			\"iSRId\"=".gen_allow_null_char($this->update_arr['iSRId']).", 
			\"dDate\"=".gen_allow_null_char($this->update_arr['dDate']).", 
			\"dStartDate\"=".gen_allow_null_char($this->update_arr['dStartDate']).", 
			\"dEndDate\"=".gen_allow_null_char($this->update_arr['dEndDate']).", 
			\"iTaskTypeId\"=".gen_allow_null_char($this->update_arr['iTaskTypeId']).", 
			\"tNotes\"=".gen_allow_null_char($this->update_arr['tNotes']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime()).",
			\"iUserId\" = ".gen_allow_null_int($this->update_arr['iUserId']).",
			\"iTechnicianId\" = ".gen_allow_null_int($this->update_arr['iTechnicianId'])."
			WHERE \"iTOId\" = ".$this->update_arr['iTOId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
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

}