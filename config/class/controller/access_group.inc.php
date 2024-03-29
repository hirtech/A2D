<?php
include_once("security_audit_log.inc.php");
class AccessGroup {
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
	
	function AccessGroup(){
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
		$sql = "SELECT access_group_mas.* ".$join_field_str." FROM access_group_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}

	function recordset_module_roles_list()
	{
		global $sqlObj;
		$sql = "SELECT access_module_role.* ".$join_field_str." FROM access_module_role".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}
	function recordset_module_list()
	{
		global $sqlObj;
		$sql = "SELECT access_module_mas.* ".$join_field_str." FROM access_module_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}

	function recordset_total()
	{
		global $sqlObj;
		$sql = "SELECT access_group_mas.* ".$this->join_field_str." FROM access_group_mas".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
	}
	
	function delete_records(){
		global $sqlObj;
		
		$sql =  "DELETE FROM access_group_mas WHERE access_group_mas.\"iAGroupId\" IN (".$this->ids.")";
		$sqlObj->Execute($sql);
		$rs_db = $sqlObj->Affected_Rows();
		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "Access Group";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/
		return $rs_db;
	}
	
	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO access_group_mas(\"vAccessGroup\", \"iAccessType\", \"iStatus\", \"tDescription\", \"iDefault\")VALUES (".gen_allow_null_char($this->insert_arr['vAccessGroup']).", ".gen_allow_null_char($this->insert_arr['iAccessType']).", ".gen_allow_null_int($this->insert_arr['iStatus']).", ".gen_allow_null_char($this->insert_arr['tDescription']).", ".gen_allow_null_int($this->insert_arr['iDefault']).")";
			$sqlObj->Execute($sql);		
			$rs_db = $sqlObj-> Insert_ID();
			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "Access Group";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/
			return $rs_db;
		}
	}

	function update_records(){
		global $sqlObj;
		if($this->update_arr){
			$rs_db = "UPDATE access_group_mas SET \"vAccessGroup\"=".gen_allow_null_char($this->update_arr['vAccessGroup']).", \"iAccessType\"=".gen_allow_null_int($this->update_arr['iAccessType']).",\"tDescription\"=".gen_allow_null_char($this->update_arr['tDescription']).",\"iStatus\"=".gen_allow_null_int($this->update_arr['iStatus'])."  WHERE \"iAGroupId\" = ".$this->update_arr['iAGroupId'];
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 1;
			$this->SALObj->module_name = "Access Group";
			$this->SALObj->action = "Update";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/
			return $rs_up;
		}
	}

	 function clear_variable() {
        $this->join_field = array();
        $this->join = array();
        $this->where = array();
        $this->param = array();
        $this->ids = 0;
        $this->action = "";
        $this->insert_arr = array();
        $this->update_arr = array();
        $this->join_field_str = "";
        $this->where_clause = "";
        $this->join_clause = "";
        $this->order_by_clause = "";
        $this->group_by_clause = "";
        $this->limit_clause = "";
    }
}
?>