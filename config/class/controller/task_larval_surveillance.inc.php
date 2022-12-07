<?php
include_once("security_audit_log.inc.php");
class TaskLarvalSurveillance {
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
	
	function TaskLarvalSurveillance(){
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
			
		$sql = "SELECT task_larval_surveillance.* ".$this->join_field_str." FROM task_larval_surveillance".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit();
		$rs_db = $sqlObj->GetAll($sql);
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT task_larval_surveillance.* ".$this->join_field_str." FROM task_larval_surveillance".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql_del = "DELETE FROM task_larval_surveillance WHERE task_larval_surveillance.\"iTLSId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);

		return $rs_del;
	}
	
	function add_records(){
		//echo "<pre>";print_r($_POST);exit;
		global $sqlObj;
		if($this->insert_arr){
			$sql = 'INSERT INTO task_larval_surveillance ("iPremiseId", "iSRId", "iDips", "dDate", "dStartDate", "dEndDate", "iGenus", "iCount", "bEggs", "bInstar1", "bInstar2", "bInstar3", "bInstar4", "bPupae", "bAdult", "iGenus2", "iCount2", "bEggs2", "bInstar12", "bInstar22", "bInstar32", "bInstar42", "bPupae2", "bAdult2", "rAvgLarvel", "tNotes", "dAddedDate","iUserId","iTechnicianId") VALUES ('.gen_allow_null_int($this->insert_arr['iPremiseId']).', '.gen_allow_null_int($this->insert_arr['iSRId']).', '.gen_allow_null_char($this->insert_arr['iDips']).', '.gen_allow_null_char($this->insert_arr['dDate']).', '.gen_allow_null_char($this->insert_arr['dStartDate']).', '.gen_allow_null_char($this->insert_arr['dEndDate']).', '.gen_allow_null_char($this->insert_arr['iGenus']).', '.gen_allow_null_char($this->insert_arr['iCount']).', '.gen_allow_null_char($this->insert_arr['bEggs']).', '.gen_allow_null_char($this->insert_arr['bInstar1']).', '.gen_allow_null_char($this->insert_arr['bInstar2']).', '.gen_allow_null_char($this->insert_arr['bInstar3']).', '.gen_allow_null_char($this->insert_arr['bInstar4']).', '.gen_allow_null_char($this->insert_arr['bPupae']).', '.gen_allow_null_char($this->insert_arr['bAdult']).', '.gen_allow_null_char($this->insert_arr['iGenus2']).', '.gen_allow_null_char($this->insert_arr['iCount2']).', '.gen_allow_null_char($this->insert_arr['bEggs2']).', '.gen_allow_null_char($this->insert_arr['bInstar12']).', '.gen_allow_null_char($this->insert_arr['bInstar22']).', '.gen_allow_null_char($this->insert_arr['bInstar32']).', '.gen_allow_null_char($this->insert_arr['bInstar42']).', '.gen_allow_null_char($this->insert_arr['bPupae2']).', '.gen_allow_null_char($this->insert_arr['bAdult2']).', '.gen_allow_null_char($this->insert_arr['rAvgLarvel']).', '.gen_allow_null_char($this->insert_arr['tNotes']).', '.gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_int($this->insert_arr['iUserId']).','.gen_allow_null_int($this->insert_arr['iTechnicianId']).')';
			//echo $sql;exit;
			//file_put_contents($site_path."a.txt", $sql);//exit;
			$sqlObj->Execute($sql);		
			$iTLSId = $sqlObj->Insert_ID();
			//echo $iTLSId;exit;
			return $iTLSId;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE task_larval_surveillance SET 
			\"iPremiseId\"=".gen_allow_null_char($this->update_arr['iPremiseId']).", 
			\"iSRId\"=".gen_allow_null_char($this->update_arr['iSRId']).", 
			\"iDips\"=".gen_allow_null_char($this->update_arr['iDips']).", 
			\"dDate\"=".gen_allow_null_char($this->update_arr['dDate']).", 
			\"dStartDate\"=".gen_allow_null_char($this->update_arr['dStartDate']).", 
			\"dEndDate\"=".gen_allow_null_char($this->update_arr['dEndDate']).", 
			\"iGenus\"=".gen_allow_null_char($this->update_arr['iGenus']).", 
			\"iCount\"=".gen_allow_null_char($this->update_arr['iCount']).", 
			\"bEggs\"=".gen_allow_null_char($this->update_arr['bEggs']).", 
			\"bInstar1\"=".gen_allow_null_char($this->update_arr['bInstar1']).", 
			\"bInstar2\"=".gen_allow_null_char($this->update_arr['bInstar2']).", 
			\"bInstar3\"=".gen_allow_null_char($this->update_arr['bInstar3']).", 
			\"bInstar4\"=".gen_allow_null_char($this->update_arr['bInstar4']).", 
			\"bPupae\"=".gen_allow_null_char($this->update_arr['bPupae']).", 
			\"bAdult\"=".gen_allow_null_char($this->update_arr['bAdult']).", 
			\"iGenus2\"=".gen_allow_null_char($this->update_arr['iGenus2']).", 
			\"iCount2\"=".gen_allow_null_char($this->update_arr['iCount2']).", 
			\"bEggs2\"=".gen_allow_null_char($this->update_arr['bEggs2']).", 
			\"bInstar12\"=".gen_allow_null_char($this->update_arr['bInstar12']).", 
			\"bInstar22\"=".gen_allow_null_char($this->update_arr['bInstar22']).", 
			\"bInstar32\"=".gen_allow_null_char($this->update_arr['bInstar32']).", 
			\"bInstar42\"=".gen_allow_null_char($this->update_arr['bInstar42']).", 
			\"bPupae2\"=".gen_allow_null_char($this->update_arr['bPupae2']).", 
			\"bAdult2\"=".gen_allow_null_char($this->update_arr['bAdult2']).", 
			\"rAvgLarvel\"=".gen_allow_null_char($this->update_arr['rAvgLarvel']).", 
			\"tNotes\"=".gen_allow_null_char($this->update_arr['tNotes']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime()).",
			\"iUserId\" = ".gen_allow_null_int($this->update_arr['iUserId']).",
			\"iTechnicianId\" = ".gen_allow_null_int($this->update_arr['iTechnicianId'])."
			WHERE \"iTLSId\" = ".$this->update_arr['iTLSId'];
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


	function recordset_glance_data($where_clause1 = "",$where_clause2 ="")
	{	global $sqlObj;
		if($where_clause1 != ""){
			$where_clause1 = " WHERE ".$where_clause1 ;	
		}

		if($where_clause2 != ""){
			$where_clause2 = " WHERE ".$where_clause2 ;	
		}

	
		$sql_glance =  "select (SELECT count(\"iTLSId\") from  task_larval_surveillance ".$where_clause1." ) as larcount1 , ( SELECT  count(\"iTLSId\")  from  task_larval_surveillance  ".$where_clause2." ) as larcount2 ";
		//echo $sql_glance;exit();
		$rs_db = $sqlObj->GetAll($sql_glance);
		//echo "<pre>";print_r($rs_db);exit();	
		return $rs_db;
	}
}
?>