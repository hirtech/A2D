<?php
class Login_History {
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
	
	function Login_History(){
		
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
					$this->limit_clause = " LIMIT 0, ".intval($this->param['limit']);
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
			
		$sql = "SELECT login_logs_mas.* ".$this->join_field_str." FROM login_logs_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		$this->debug_query($sql);
		return $rs_db;
		
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT login_logs_mas.* ".$this->join_field_str." FROM login_logs_mas".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		$this->debug_query($sql);
		return $count;
		
	}
	function user_details_records()
	{
		global $sqlObj;
			
		$sql = "SELECT login_logs_mas.* ".$this->join_field_str." FROM login_logs_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->select($sql);
		$this->debug_query($sql);
		return $rs_db;
		
	}
	function delete_records(){
		global $sqlObj;
		
		$sql = "DELETE login_logs_mas ".$this->join_field_str." FROM login_logs_mas".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$sqlObj->Execute($sql);
		$rs_del =$sqlObj->Affected_Rows();
		$this->debug_query($sql);

		return $rs_del;
	}
	function debug_query($sql){
		global $site_path;
		if($this->debug_query == true){
			
$str = '<?
	/*=================== Query ======================*/
	'.$sql.'
	/*=================== Query ======================*/
?>';
			file_put_contents($site_path."debug/".basename($_SERVER['SCRIPT_FILENAME']), $str);
			
		}
	}
}
?>