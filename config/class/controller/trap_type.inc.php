<?php
include_once("security_audit_log.inc.php");
class TrapType {
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
	
	function TrapType(){
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
			
		$sql = "SELECT trap_type_mas.* ".$this->join_field_str." FROM trap_type_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT trap_type_mas.* ".$this->join_field_str." FROM trap_type_mas".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql = "DELETE trap_type_mas ".$this->join_field_str." FROM trap_type_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->Execute($sql);

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "Trap Type";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_db;
	}
	function add_records(){
		global $sqlObj, $admin_panel_session_suffix;
		if($this->insert_arr){
			$sql = "INSERT INTO trap_type_mas (\"vTrapName\", \"iStatus\")VALUES (".gen_allow_null_char($this->insert_arr['vTrapName']).", ".gen_allow_null_char($this->insert_arr['iStatus']).")";
			
			$sqlObj->Execute($sql);		
			$iTrapTypeId = $sqlObj-> Insert_ID();

			return $iTrapTypeId;
		}
	}

	function update_records(){
		global $sqlObj, $site_path, $admin_panel_session_suffix;

		if($this->update_arr){
			$rs_db = "UPDATE trap_type_mas SET \"vTrapName\"=".gen_allow_null_char($this->update_arr['vTrapName']).", \"iStatus\"=".gen_allow_null_char($this->update_arr['iStatus'])." WHERE \"iTrapTypeId\" = ".$this->update_arr['iTrapTypeId'];
			//file_put_contents($site_path."a.txt", $rs_db);
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			
			$iTrapTypeId = $this->update_arr['iTrapTypeId'];

			return $rs_up;
		}
	}
}
?>