<?php
include_once("security_audit_log.inc.php");
class SiteAttribute {
	var $iSAttributeId;
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
	
	function SiteAttribute() {
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
			
		$sql = "SELECT site_attribute_mas.* ".$join_field_str." FROM site_attribute_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//file_put_contents($site_path."logs/a.txt", $sql);
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT site_attribute_mas.\"iSAttributeId\" ".$this->join_field_str." FROM site_attribute_mas".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql_del = "DELETE FROM site_attribute_mas WHERE site_attribute_mas.\"iSAttributeId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "Premise Attribute";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_del;
	}
	

	function action_records(){		
		global $sqlObj;

		if($this->ids){								
			if($this->action=="Active"){
				$sql = "UPDATE site_attribute_mas set \"iStatus\" = '1',\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())."   WHERE \"iSAttributeId\" IN (".$this->ids.")";
			}
			if($this->action=="Inactive"){
				$sql = "UPDATE site_attribute_mas set \"iStatus\" = '0',\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())."   WHERE \"iSAttributeId\" IN (".$this->ids.")";
			}
			$sqlObj->Execute($sql);
			$rs_db =$sqlObj->Affected_Rows();											
		}

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 1;
		$this->SALObj->module_name = "Premise Attribute";
		$this->SALObj->action = $this->action;
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_db;
	}

	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO site_attribute_mas(\"vAttribute\",\"iStatus\", \"dAddedDate\")VALUES (".gen_allow_null_char($this->insert_arr['vAttribute']).",".gen_allow_null_char($this->insert_arr['iStatus']).",".gen_allow_null_char(date_getSystemDateTime()).")";

			$sqlObj->Execute($sql);		
			$rs_db = $sqlObj-> Insert_ID();

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "Premise Attribute";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/

			return $rs_db;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;

		if($this->update_arr){
			$rs_db = "UPDATE site_attribute_mas SET \"vAttribute\"=".gen_allow_null_char($this->update_arr['vAttribute'])." ,\"iStatus\"=".gen_allow_null_char($this->update_arr['iStatus']).",\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iSAttributeId\" = ".$this->update_arr['iSAttributeId'];
			
			//file_put_contents($site_path."a.txt", $rs_db);
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 1;
			$this->SALObj->module_name = "Premise Attribute";
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