<?php
include_once("security_audit_log.inc.php");
class WorkOrder {
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
	
	function WorkOrder(){
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

		$sql = "SELECT workorder.* ".$this->join_field_str." FROM workorder".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT DISTINCT workorder.* ".$this->join_field_str." FROM workorder".$this->join_clause.$this->where_clause.$this->group_by_clause;

		$rs_db = $sqlObj->Execute($sql);
		
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
			
		return $count;
		
	}

	function add_records(){ //Service Request

		global $sqlObj, $admin_panel_session_suffix, $function_path;
		$dClosedDate = '';
		if($this->insert_arr['iWOSId'] == 2) { 
			// WorkOrder Type - 2 | Closed
			$dClosedDate = date_getSystemDateTime();
		}

		$sql = "INSERT INTO workorder(\"iPremiseId\", \"iServiceOrderId\", \"iRequestorId\",\"vWOProject\", \"iWOTId\", \"tDescription\", \"iAssignedToId\", \"iWOSId\", \"dClosedDate\", \"dAddedDate\") VALUES (".gen_allow_null_int($this->insert_arr['iPremiseId']).", ".gen_allow_null_int($this->insert_arr['iServiceOrderId']).", ".gen_allow_null_int($this->insert_arr['iRequestorId']).", ".gen_allow_null_char($this->insert_arr['vWOProject']).", ".gen_allow_null_int($this->insert_arr['iWOTId']).", ".gen_allow_null_char($this->insert_arr['tDescription']).", ".gen_allow_null_int($this->insert_arr['iAssignedToId']).", ".gen_allow_null_int($this->insert_arr['iWOSId']).", ".gen_allow_null_char($dClosedDate).",  ".gen_allow_null_char(date_getSystemDateTime()).")";
		//echo $sql;exit;
		$sqlObj->Execute($sql);		
		$iWOId = $sqlObj->Insert_ID();
		return $iWOId;
	}
	
	function update_records(){
		global $sqlObj, $admin_panel_session_suffix, $function_path;

		if($this->update_arr){
			$dClosedDate = '';
			if($this->update_arr['iWOSId'] == 2) { 
				// WorkOrder Type - 2 | Closed
				$dClosedDate = date_getSystemDateTime();
			}

			$rs_db = "UPDATE workorder SET  
			\"iPremiseId\" = ".gen_allow_null_int($this->update_arr['iPremiseId']).", 
			\"iServiceOrderId\" = ".gen_allow_null_int($this->update_arr['iServiceOrderId']).", 
			\"iRequestorId\" = ".gen_allow_null_int($this->update_arr['iRequestorId']).", 
			\"vWOProject\" = ".gen_allow_null_char($this->update_arr['vWOProject']).", 
			\"iWOTId\" = ".gen_allow_null_int($this->update_arr['iWOTId']).", 
			\"tDescription\" = ".gen_allow_null_char($this->update_arr['tDescription']).", 
			\"iAssignedToId\" = ".gen_allow_null_int($this->update_arr['iAssignedToId']).", 
			\"iWOSId\" = ".gen_allow_null_int($this->update_arr['iWOSId']).", 
			\"dClosedDate\" = ".gen_allow_null_char($dClosedDate).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iWOId\" = ".$this->update_arr['iWOId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			return $rs_up;
		}
	}

	function change_status($ids, $status){
		global $sqlObj;
		
		$sql = "UPDATE workorder set \"iWOSId\" = '".$status."', \"dModifiedDate\" = '".date_getSystemDateTime()."' WHERE workorder.\"iWOId\" IN (".$ids.")";
		//echo  $sql;exit();
		$rs = $sqlObj->Execute($sql);
		return $rs;
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

	function recordset_glance_data($where_clause1 = "",$where_clause2 ="") {	
		global $sqlObj;
		if($where_clause1 != ""){
			$where_clause1 = " WHERE ".$where_clause1 ;	
		}

		if($where_clause2 != ""){
			$where_clause2 = " WHERE ".$where_clause2 ;	
		}

		$sql_glance =  "select (SELECT count(\"iWOId\")  from  workorder ".$where_clause1." ) as wocount1, ( SELECT  count(\"iWOId\") from  workorder ".$where_clause2." ) as wocount2 ";
		//echo $sql_glance;exit();
		$rs_db = $sqlObj->GetAll($sql_glance);
			
		return $rs_db;
	}

	
}
?>