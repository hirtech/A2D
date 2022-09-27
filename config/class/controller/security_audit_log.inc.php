<?php
class Security_audit_log {
	var $join_field = array();
	var $join = array();
	var $where = array();
	var $param = array();
	var $ids = 0;
	var $insert_arr = array();
	var $update_arr = array();
	var $join_field_str="";
	var $where_clause="";
	var $join_clause="";
	var $order_by_clause="";
	var $group_by_clause="";
	var $limit_clause = "";
	var $notice_alerts = false;
	var $warning_alerts = false;
	var $critical_alerts = false;
	var $module_name = "";
	var $type = "";
	var $action = "";
	var $msg = "";
	
	function Security_audit_log(){
		global $NOTICE_ALERTS, $WARNING_ALERTS, $CRITICAL_ALERTS;
		
		$NOTICE_ALERTS=1;
		$WARNING_ALERTS=1;
		$CRITICAL_ALERTS=1;
		
		if($NOTICE_ALERTS ==  1){
			$this->notice_alerts = true;
		}
		if($WARNING_ALERTS ==  1){
			$this->warning_alerts = true;
		}
		if($CRITICAL_ALERTS ==  1){
			$this->critical_alerts = true;
		}
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
			if(!empty($this->param['group_by']))
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
			
		$sql = "SELECT security_audit_log.* ".$this->join_field_str." FROM security_audit_log".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT security_audit_log.* ".$this->join_field_str." FROM security_audit_log".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
		
	}
	
	function clear_variable(){
		$this->join_field = array();
		$this->join = array();
		$this->where = array();
		$this->param = array();
		$this->ids = 0;
		$this->insert_arr = array();
		$this->update_arr = array();
		$this->join_field_str="";
		$this->where_clause="";
		$this->join_clause="";
		$this->order_by_clause="";
		$this->group_by_clause="";
		$this->limit_clause = "";
	}
	
	function audit_log_entry(){
		global $sqlObj, $admin_panel_session_suffix;
		
		if($this->notice_alerts == true || $this->warning_alerts == true || $this->critical_alerts == true){
			
			if($this->module_name != ""){
				
				if($this->type == 0 && $this->notice_alerts == true){//Notice - Add
					$this->msg = 'User created a new '.ucfirst($this->module_name);
				}
				else if($this->type == 1 && $this->warning_alerts == true){//Warning - Active - Inactive - Edit
					if($this->action == "Active")
						$this->msg = 'User activated a '.ucfirst($this->module_name);
					else if($this->action == "Inactive")
						$this->msg = 'User inactivated a '.ucfirst($this->module_name);
					else if($this->action == "Update")
						$this->msg = 'User modified a '.ucfirst($this->module_name);
				}
				else if($this->type == 2 && $this->critical_alerts == true){//Critical - Delete
					$this->msg = 'User Deleted a '.ucfirst($this->module_name);
				}
				
				$sql_sal = "INSERT INTO security_audit_log(\"iUserId\", \"iType\", \"vMsg\", \"vIp\", \"dDate\") VALUES (".gen_allow_null_int($_SESSION["sess_iUserId".$admin_panel_session_suffix]).", ".gen_allow_null_int($this->type).", ".gen_allow_null_char($this->msg).", ".gen_allow_null_char(getIP()).", ".gen_allow_null_char(date_getSystemDateTime()).")";
				$sqlObj->Execute($sql_sal);
				
			}
		}
	}
	function logged_actions_recordset_list()
	{
		global $sqlObj;
			
		$sql = "SELECT audit.logged_actions.* ".$this->join_field_str." FROM audit.logged_actions".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;

		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	
	function logged_actions_recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT audit.logged_actions.* ".$this->join_field_str." FROM audit.logged_actions".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
	}

	function logged_actions_user_name_recordset_list()
	{
		global $sqlObj;
			
		$sql = 'SELECT audit.logged_actions."user_name" '.$this->join_field_str.' FROM audit.logged_actions'.$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	function logged_actions_table_name_recordset_list()
	{
		global $sqlObj;
			
		$sql = 'SELECT audit.logged_actions."table_name" '.$this->join_field_str.' FROM audit.logged_actions'.$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
}
?>