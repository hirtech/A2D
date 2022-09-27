<?php
include_once("security_audit_log.inc.php");
class TreatmentProduct {
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
	
	function TreatmentProduct(){
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
		
		$sql = "SELECT treatment_product.* ".$this->join_field_str." FROM treatment_product ".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT treatment_product.* ".$this->join_field_str." FROM treatment_product ".$this->join_clause.$this->where_clause.$this->group_by_clause;
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
	}

	function unit_data()
	{
		global $sqlObj;
		
		$sql = "SELECT unit_mas.* ".$this->join_field_str." FROM unit_mas ".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}

	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = 'INSERT INTO treatment_product ("vName", "vCategory", "iPesticide", "vActiveIngredient", "vActiveIngredient2", "iUId", "vAppRate", "vClass", "vEPARegNo", "vAI", "vAI2", "vTragetAppRate", "vMinAppRate", "vMaxAppRate", "iStatus", "dAddedDate") VALUES ('.gen_allow_null_char($this->insert_arr['vName']).', '.gen_allow_null_char($this->insert_arr['vCategory']).', '.gen_allow_null_char($this->insert_arr['iPesticide']).', '.gen_allow_null_char($this->insert_arr['vActiveIngredient']).', '.gen_allow_null_char($this->insert_arr['vActiveIngredient2']).', '.gen_allow_null_int($this->insert_arr['iUId']).', '.gen_allow_null_char($this->insert_arr['vAppRate']).', '.gen_allow_null_char($this->insert_arr['vClass']).', '.gen_allow_null_char($this->insert_arr['vEPARegNo']).', '.gen_allow_null_char($this->insert_arr['vAI']).', '.gen_allow_null_char($this->insert_arr['vAI2']).', '.gen_allow_null_char($this->insert_arr['vTragetAppRate']).', '.gen_allow_null_char($this->insert_arr['vMinAppRate']).', '.gen_allow_null_char($this->insert_arr['vMaxAppRate']).', '.gen_allow_null_int($this->insert_arr['iStatus']).",".gen_allow_null_char(date_getSystemDateTime()).')';
			//echo $sql;exit();
			$sqlObj->Execute($sql);		
			$iTPId = $sqlObj->Insert_ID();
			
			return $iTPId;
		}
	}

	function delete_records($id){
		global $sqlObj;
		
		$sql = "DELETE FROM treatment_product WHERE treatment_product.\"iTPId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql);

		return $rs_del;
	}

	function update_records(){
		global $sqlObj;
		if($this->update_arr){
			$sql = "UPDATE treatment_product SET \"vName\"=".gen_allow_null_char($this->update_arr['vName']).", \"vCategory\"=".gen_allow_null_char($this->update_arr['vCategory']).", \"iPesticide\"=".gen_allow_null_char($this->update_arr['iPesticide']).", \"vActiveIngredient\"=".gen_allow_null_char($this->update_arr['vActiveIngredient']).", \"vActiveIngredient2\"=".gen_allow_null_char($this->update_arr['vActiveIngredient2']).", \"iUId\"=".gen_allow_null_int($this->update_arr['iUId']).", \"vAppRate\"=".gen_allow_null_char($this->update_arr['vAppRate']).", \"vClass\"=".gen_allow_null_char($this->update_arr['vClass']).", \"vEPARegNo\"=".gen_allow_null_char($this->update_arr['vEPARegNo']).", \"vAI\"=".gen_allow_null_char($this->update_arr['vAI']).", \"vAI2\"=".gen_allow_null_char($this->update_arr['vAI2']).", \"vTragetAppRate\"=".gen_allow_null_char($this->update_arr['vTragetAppRate']).", \"vMinAppRate\"=".gen_allow_null_char($this->update_arr['vMinAppRate']).", \"vMaxAppRate\"=".gen_allow_null_char($this->update_arr['vMaxAppRate']).", \"iStatus\"=".gen_allow_null_int($this->update_arr['iStatus']).", \"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iTPId\" = ".$this->update_arr['iTPId'];
			
			$sqlObj->Execute($sql);
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

?>