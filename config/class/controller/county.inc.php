<?php
include_once("security_audit_log.inc.php");
class County {
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
	
	function County(){
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
			
		$sql = "SELECT county_mas.* ".$this->join_field_str." FROM county_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo "<pre>";print_r($sql);exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT county_mas.* ".$this->join_field_str." FROM county_mas".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql = "DELETE county_mas ".$this->join_field_str." FROM county_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->Execute($sql);

		$this->SALObj->type = 2;
		$this->SALObj->module_name = "country";
		$this->SALObj->audit_log_entry();

		return $rs_db;
	}*/

	function delete_records($id){
		global $sqlObj;
		
		$sql_del = "DELETE FROM county_mas WHERE county_mas.\"iCountyId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "country";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_del;
	}
	
	function add_records(){
		//echo "<pre>";print_r($_POST);exit;
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO county_mas( \"vCounty\")VALUES (".gen_allow_null_char($this->insert_arr['vCounty']).")";
			$sqlObj->Execute($sql);		
			$rs_db = $sqlObj-> Insert_ID();
			//echo "<pre>";print_r($rs_db);exit;
			

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "country";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/
		
			return $rs_db;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE county_mas SET \"vCounty\"=".gen_allow_null_char($this->update_arr['vCounty'])." WHERE \"iCountyId\" = ".$this->update_arr['iCountyId'];

			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();


			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 1;
			$this->SALObj->module_name = "country";
			$this->SALObj->action = "Update";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/

			return $rs_up;
		}
	}

	function action_records(){
		
		global $sqlObj;
		if($this->ids){
			if($this->action=="Active"){
				$sql = "UPDATE county_mas set \"iStatus\" = '1' WHERE \"iCountyId\" IN (".$this->ids.")";
			}
			if($this->action=="Inactive"){
				$sql = "UPDATE county_mas set \"iStatus\" = '0' WHERE \"iCountyId\" IN (".$this->ids.")";
			}
			$sqlObj->Execute($sql);
			$rs_db =$sqlObj->Affected_Rows();
			
		}

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 1;
		$this->SALObj->module_name = "country";
		$this->SALObj->action = $this->action;
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_db;
	}
}
?>