<?php
include_once("security_audit_log.inc.php");
class Department {
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
	
	function Department(){
		$this->SALObj = new Security_audit_log();
	}
	function setClause() {
		//Join Fields for select query	
		if(is_array($this->join_field) && count($this->join_field) > 0){
			$this->join_field_str = implode(", ", $this->join_field);	
		}
		// Join clause
		if(is_array($this->join) && count($this->join) > 0){
			$this->join_clause = implode(" ", $this->join);	
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
					$this->limit_clause = " LIMIT 0, ".intval($this->param['limit']);
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
			
		$sql = "SELECT department_mas.* ".$join_field_str." FROM department_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}

	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT department_mas.* ".$this->join_field_str." FROM department_mas".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
		
	}
	
	/*function delete_records(){
		global $sqlObj;
		
		$sql = "DELETE department_mas ".$this->join_field_str." FROM department_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$sqlObj->Execute($sql);
		$rs_db = $sqlObj->Affected_Rows();
		
		return $rs_db;
	}*/

	function delete_records($id){
		global $sqlObj;
		
		$sql_del = "DELETE FROM department_mas WHERE department_mas.\"iDepartmentId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "Department";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_del;
	}
	
	function action_records(){
		
		global $sqlObj;
		if($this->ids){
			if($this->action=="Active"){
				$sql = "UPDATE department_mas set \"iStatus\"='1' WHERE \"iDepartmentId\" IN (".$this->ids.")";
			}
			else if($this->action=="Inactive"){
				$sql = "UPDATE department_mas set \"iStatus\"='0' WHERE \"iDepartmentId\" IN (".$this->ids.")";
			}
			$sqlObj->Execute($sql);
			$rs_db = $sqlObj->Affected_Rows();
		}

		
		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 1;
		$this->SALObj->module_name = "Department";
		$this->SALObj->action = $this->action;
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/
		return $rs_db;
	}
	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO department_mas(\"vDepartment\", \"iShowOnFlightLog\", \"iStatus\")VALUES ('".$this->insert_arr['vDepartment']."', '".$this->insert_arr['iShowOnFlightLog']."', '".$this->insert_arr['iStatus']."')";
			$sqlObj->Execute($sql);		
			$rs_db = $sqlObj-> Insert_ID();

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "Department";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/

			return $rs_db;
		}
	}

	function update_records(){
		global $sqlObj;

		if($this->update_arr){
			$rs_db = "UPDATE department_mas SET \"vDepartment\" = '".$this->update_arr['vDepartment']."', \"iShowOnFlightLog\" = '".$this->update_arr['iShowOnFlightLog']."', \"iStatus\" = '".$this->update_arr['iStatus']."' WHERE \"iDepartmentId\" = '".$this->update_arr['iDepartmentId']."'";			
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 1;
			$this->SALObj->module_name = "Department";
			$this->SALObj->action = "Update";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/
			
			return $rs_up;
		}
	}
}
?>