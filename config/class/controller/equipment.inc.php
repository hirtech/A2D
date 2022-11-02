<?php
include_once("security_audit_log.inc.php");
class Equipment {
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
	
	function Equipment(){
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

		$sql = "SELECT equipment.* ".$this->join_field_str." FROM equipment".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT DISTINCT equipment.* ".$this->join_field_str." FROM equipment".$this->join_clause.$this->where_clause.$this->group_by_clause;

		$rs_db = $sqlObj->Execute($sql);
		
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
			
		return $count;
		
	}

	function add_records(){ //Service Request

		global $sqlObj, $admin_panel_session_suffix, $function_path;

		$sql = "INSERT INTO equipment(\"iEquipmentModelId\", \"vSerialNumber\", \"vMACAddress\", \"vIPAddress\", \"vSize\", \"vWeight\", \"iMaterialId\", \"iPowerId\", \"iGrounded\", \"dInstallByDate\", \"dInstalledDate\", \"vPurchaseCost\", \"dPurchaseDate\", \"dWarrantyExpiration\", \"vWarrantyCost\", \"iPremiseId\", \"iInstallTypeId\", \"iPrimaryCircuitId\", \"iLinkTypeId\", \"dProvisionDate\", \"iOperationalStatusId\", \"dAddedDate\") VALUES (".gen_allow_null_int($this->insert_arr['iEquipmentModelId']).", ".gen_allow_null_char($this->insert_arr['vSerialNumber']).", ".gen_allow_null_char($this->insert_arr['vMACAddress']).", ".gen_allow_null_char($this->insert_arr['vIPAddress']).", ".gen_allow_null_char($this->insert_arr['vSize']).", ".gen_allow_null_char($this->insert_arr['vWeight']).", ".gen_allow_null_int($this->insert_arr['iMaterialId']).", ".gen_allow_null_int($this->insert_arr['iPowerId']).",
		".gen_allow_null_int($this->insert_arr['iGrounded']).",
		".gen_allow_null_char($this->insert_arr['dInstallByDate']).",
		".gen_allow_null_char($this->insert_arr['dInstalledDate']).",
		".gen_allow_null_char($this->insert_arr['vPurchaseCost']).",
		".gen_allow_null_char($this->insert_arr['dPurchaseDate']).",
		".gen_allow_null_char($this->insert_arr['dWarrantyExpiration']).",
		".gen_allow_null_char($this->insert_arr['vWarrantyCost']).",
		".gen_allow_null_char($this->insert_arr['iPremiseId']).",
		".gen_allow_null_char($this->insert_arr['iInstallTypeId']).",
		".gen_allow_null_char($this->insert_arr['iPrimaryCircuitId']).",
		".gen_allow_null_char($this->insert_arr['iLinkTypeId']).",
		".gen_allow_null_char($this->insert_arr['dProvisionDate']).",
		".gen_allow_null_char($this->insert_arr['iOperationalStatusId']).",
		".gen_allow_null_char(date_getSystemDateTime()).")";
		//echo $sql;exit;
		$sqlObj->Execute($sql);		
		$iEquipmentId = $sqlObj->Insert_ID();
		return $iEquipmentId;
	}
	
	function update_records(){
		global $sqlObj, $admin_panel_session_suffix, $function_path;

		if($this->update_arr){
			$rs_db = "UPDATE equipment SET  
			\"iEquipmentModelId\" = ".gen_allow_null_int($this->update_arr['iEquipmentModelId']).", 
			\"vSerialNumber\" = ".gen_allow_null_char($this->update_arr['vSerialNumber']).", 
			\"vMACAddress\" = ".gen_allow_null_char($this->update_arr['vMACAddress']).", 
			\"vSize\" = ".gen_allow_null_char($this->update_arr['vSize']).", 
			\"vWeight\" = ".gen_allow_null_int($this->update_arr['vWeight']).", 
			\"iMaterialId\" = ".gen_allow_null_int($this->update_arr['iMaterialId']).", 
			\"iPowerId\" = ".gen_allow_null_int($this->update_arr['iPowerId']).", 
			\"iGrounded\" = ".gen_allow_null_int($this->update_arr['iGrounded']).", 
			\"dInstallByDate\" = ".gen_allow_null_char($this->update_arr['dInstallByDate']).", 
			\"dInstalledDate\" = ".gen_allow_null_char($this->update_arr['dInstalledDate']).", 
			\"vPurchaseCost\" = ".gen_allow_null_char($this->update_arr['vPurchaseCost']).", 
			\"dPurchaseDate\" = ".gen_allow_null_char($this->update_arr['dPurchaseDate']).", 
			\"dWarrantyExpiration\" = ".gen_allow_null_char($this->update_arr['dWarrantyExpiration']).", 
			\"vWarrantyCost\" = ".gen_allow_null_char($this->update_arr['vWarrantyCost']).", 
			\"iPremiseId\" = ".gen_allow_null_char($this->update_arr['iPremiseId']).", 
			\"iInstallTypeId\" = ".gen_allow_null_int($this->update_arr['iInstallTypeId']).", 
			\"iPrimaryCircuitId\" = ".gen_allow_null_int($this->update_arr['iPrimaryCircuitId']).", 
			\"iLinkTypeId\" = ".gen_allow_null_int($this->update_arr['iLinkTypeId']).", 
			\"dProvisionDate\" = ".gen_allow_null_char($this->update_arr['dProvisionDate']).", 
			\"iOperationalStatusId\" = ".gen_allow_null_int($this->update_arr['iOperationalStatusId']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iEquipmentId\" = ".$this->update_arr['iEquipmentId'];
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

	function delete_records($iEquipmentId){
		global $sqlObj;

        $sql_del = "DELETE FROM equipment WHERE equipment.\"iEquipmentId\" IN (".$iEquipmentId.")";
		$rs_del = $sqlObj->Execute($sql_del);

		return $rs_del;
	}
}
?>