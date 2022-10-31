<?php
include_once("security_audit_log.inc.php");
class EquipmentModel {
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
	
	function EquipmentModel(){
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

		$sql = "SELECT equipment_model.* ".$this->join_field_str." FROM equipment_model".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT DISTINCT equipment_model.* ".$this->join_field_str." FROM equipment_model".$this->join_clause.$this->where_clause.$this->group_by_clause;

		$rs_db = $sqlObj->Execute($sql);
		
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
			
		return $count;
		
	}

	function add_records(){ //Service Request

		global $sqlObj, $admin_panel_session_suffix, $function_path;
		
		$sql = "INSERT INTO equipment_model(\"vModelName\", \"vModelNumber\", \"vPartNumber\", \"tDescription\", \"iUnitQuantity\", \"rUnitCost\", \"iEquipmentTypeId\", \"iEquipmentManufacturerId\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['vModelName']).", ".gen_allow_null_char($this->insert_arr['vModelNumber']).", ".gen_allow_null_char($this->insert_arr['vPartNumber']).", ".gen_allow_null_char($this->insert_arr['tDescription']).", ".gen_allow_null_int($this->insert_arr['iUnitQuantity']).", ".gen_allow_null_char($this->insert_arr['rUnitCost']).", ".gen_allow_null_int($this->insert_arr['iEquipmentTypeId']).", ".gen_allow_null_int($this->insert_arr['iEquipmentManufacturerId']).",  ".gen_allow_null_char(date_getSystemDateTime()).")";
		//echo $sql;exit;
		$sqlObj->Execute($sql);		
		$iEquipmentModelId = $sqlObj->Insert_ID();
		return $iEquipmentModelId;
	}
	
	function update_records(){
		global $sqlObj, $admin_panel_session_suffix, $function_path;

		if($this->update_arr){
			$rs_db = "UPDATE equipment_model SET  
			\"vModelName\" = ".gen_allow_null_char($this->update_arr['vModelName']).", 
			\"vModelNumber\" = ".gen_allow_null_char($this->update_arr['vModelNumber']).", 
			\"vPartNumber\" = ".gen_allow_null_char($this->update_arr['vPartNumber']).", 
			\"tDescription\" = ".gen_allow_null_char($this->update_arr['tDescription']).", 
			\"iUnitQuantity\" = ".gen_allow_null_int($this->update_arr['iUnitQuantity']).", 
			\"rUnitCost\" = ".gen_allow_null_char($this->update_arr['rUnitCost']).", 
			\"iEquipmentTypeId\" = ".gen_allow_null_char($this->update_arr['iEquipmentTypeId']).", 
			\"iEquipmentManufacturerId\" = ".gen_allow_null_char($this->update_arr['iEquipmentManufacturerId']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iEquipmentModelId\" = ".$this->update_arr['iEquipmentModelId'];
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

	function delete_records($iEquipmentModelId){
		global $sqlObj;

        $sql_del = "DELETE FROM equipment_model WHERE equipment_model.\"iEquipmentModelId\" IN (".$iEquipmentModelId.")";
		$rs_del = $sqlObj->Execute($sql_del);

		return $rs_del;
	}
}
?>