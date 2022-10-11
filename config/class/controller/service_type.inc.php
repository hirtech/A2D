<?php
include_once("security_audit_log.inc.php");
class ServiceType {
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
	
	function ServiceType(){
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
		
		$sql = "SELECT service_type_mas.* ".$this->join_field_str." FROM service_type_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT service_type_mas.* ".$this->join_field_str." FROM service_type_mas".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql_del = "DELETE FROM service_type_mas WHERE service_type_mas.\"iServiceTypeId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "Service Type";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO service_type_mas( \"vServiceType\", \"iStatus\", \"dAddedDate\")VALUES (".gen_allow_null_char($this->insert_arr['vServiceType']).", ".$this->insert_arr['iStatus'].", ".gen_allow_null_char(date_getSystemDateTime()).")";
			
			$sqlObj->Execute($sql);		
			$rs_db = $sqlObj-> Insert_ID();

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "Service Type";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/

			return $rs_db;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE service_type_mas SET  \"vServiceType\"=".gen_allow_null_char($this->update_arr['vServiceType']).", \"iStatus\"=".gen_allow_null_char($this->update_arr['iStatus']).", \"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iServiceTypeId\" = ".$this->update_arr['iServiceTypeId'];
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 1;
			$this->SALObj->module_name = "Service Type";
			$this->SALObj->action = "Update";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/

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
?>