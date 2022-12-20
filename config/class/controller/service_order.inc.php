<?php
include_once("security_audit_log.inc.php");
class ServiceOrder {
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
	
	function ServiceOrder(){
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

		$sql = "SELECT service_order.* ".$this->join_field_str." FROM service_order".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT DISTINCT service_order.* ".$this->join_field_str." FROM service_order".$this->join_clause.$this->where_clause.$this->group_by_clause;

		$rs_db = $sqlObj->Execute($sql);
		
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
			
		return $count;
		
	}

	function add_records(){ //Service Request

		global $sqlObj, $admin_panel_session_suffix, $function_path;

		$sql = "INSERT INTO service_order(\"vMasterMSA\", \"vServiceOrder\", \"iCarrierID\",\"vSalesRepName\", \"vSalesRepEmail\", \"iPremiseId\", \"iConnectionTypeId\", \"iService1\", \"iSOStatus\", \"iCStatus\", \"iSStatus\", \"tComments\", \"iUserCreatedBy\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['vMasterMSA']).", ".gen_allow_null_char($this->insert_arr['vServiceOrder']).", ".gen_allow_null_int($this->insert_arr['iCarrierID']).", ".gen_allow_null_char($this->insert_arr['vSalesRepName']).", ".gen_allow_null_char($this->insert_arr['vSalesRepEmail']).", ".gen_allow_null_int($this->insert_arr['iPremiseId']).", ".gen_allow_null_int($this->insert_arr['iConnectionTypeId']).", ".gen_allow_null_int($this->insert_arr['iService1']).", ".gen_allow_null_int($this->insert_arr['iSOStatus']).", ".gen_allow_null_int($this->insert_arr['iCStatus']).", ".gen_allow_null_int($this->insert_arr['iSStatus']).", ".gen_allow_null_char($this->insert_arr['tComments']).", ".gen_allow_null_int($this->insert_arr['iUserCreatedBy']).",  ".gen_allow_null_char(date_getSystemDateTime()).")";
		//echo $sql;exit;
		$sqlObj->Execute($sql);		
		$iServiceOrderId = $sqlObj->Insert_ID();
		return $iServiceOrderId;
	}
	
	function update_records(){
		global $sqlObj, $admin_panel_session_suffix, $function_path;

		if($this->update_arr){
			$rs_db = "UPDATE service_order SET 
			\"vMasterMSA\" = ".gen_allow_null_char($this->update_arr['vMasterMSA']).", 
			\"vServiceOrder\" = ".gen_allow_null_char($this->update_arr['vServiceOrder']).", 
			\"iCarrierID\" = ".gen_allow_null_int($this->update_arr['iCarrierID']).", 
			\"vSalesRepName\" = ".gen_allow_null_char($this->update_arr['vSalesRepName']).", 
			\"vSalesRepEmail\" = ".gen_allow_null_char($this->update_arr['vSalesRepEmail']).", 
			\"iPremiseId\" = ".gen_allow_null_int($this->update_arr['iPremiseId']).", 
			\"iConnectionTypeId\" = ".gen_allow_null_int($this->update_arr['iConnectionTypeId']).", 
			\"iService1\" = ".gen_allow_null_int($this->update_arr['iService1']).", 
			\"iSOStatus\" = ".gen_allow_null_int($this->update_arr['iSOStatus']).", 
			\"iCStatus\" = ".gen_allow_null_int($this->update_arr['iCStatus']).", 
			\"iSStatus\" = ".gen_allow_null_int($this->update_arr['iSStatus']).", 
			\"tComments\" = ".gen_allow_null_char($this->update_arr['tComments']).", 
			\"iUserModifiedBy\" = ".gen_allow_null_int($this->update_arr['iUserModifiedBy']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iServiceOrderId\" = ".$this->update_arr['iServiceOrderId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			return $rs_up;
		}
	}

	function delete_records($iServiceOrderId){
		global $sqlObj;

        $sql_del = "DELETE FROM service_order WHERE service_order.\"iServiceOrderId\" IN (".$iServiceOrderId.")";
		$rs_del = $sqlObj->Execute($sql_del);

		return $rs_del;
	}

	function recordset_glance_data($where_clause1 = "",$where_clause2 ="") {	
		global $sqlObj;
		if($where_clause1 != ""){
			$where_clause1 = " WHERE ".$where_clause1 ;	
		}

		if($where_clause2 != ""){
			$where_clause2 = " WHERE ".$where_clause2 ;	
		}

		$sql_glance =  "select (SELECT count(\"iServiceOrderId\")  from  service_order ".$where_clause1." ) as socount1, ( SELECT  count(\"iServiceOrderId\") from  service_order ".$where_clause2." ) as socount2 ";
		//echo $sql_glance;exit();
		$rs_db = $sqlObj->GetAll($sql_glance);
			
		return $rs_db;
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