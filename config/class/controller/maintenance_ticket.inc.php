<?php
include_once("security_audit_log.inc.php");
class MaintenanceTicket {

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
	
	function MaintenanceTicket() {
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
			
		$sql = "SELECT maintenance_ticket.* ".$this->join_field_str." FROM maintenance_ticket".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		//echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT maintenance_ticket.* ".$this->join_field_str." FROM maintenance_ticket".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql_del = "DELETE FROM maintenance_ticket WHERE maintenance_ticket.\"iMaintenanceTicketId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);
		if($rs_del){
			$sql = "DELETE FROM maintenance_ticket_premise WHERE maintenance_ticket_premise.\"iMaintenanceTicketId\" IN (".$id.")";
			$rs = $sqlObj->Execute($sql);
		}
		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			//echo"<pre>";print_r($this->insert_arr);exit;
			$sql = "INSERT INTO maintenance_ticket(\"iAssignedToId\", \"iServiceOrderId\", \"iSeverity\", \"iStatus\", \"dCompletionDate\", \"tDescription\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['iAssignedToId']).", ".gen_allow_null_char($this->insert_arr['iServiceOrderId']).", ".gen_allow_null_char($this->insert_arr['iSeverity']).", ".gen_allow_null_char($this->insert_arr['iStatus']).", ".gen_allow_null_char($this->insert_arr['dCompletionDate']).", ".gen_allow_null_char($this->insert_arr['tDescription']).",".gen_allow_null_char(date_getSystemDateTime()).")";
			//echo $sql;exit;
			$sqlObj->Execute($sql);		
			$iMaintenanceTicketId = $sqlObj->Insert_ID();
			if($iMaintenanceTicketId > 0){
				$premise_length = $this->insert_arr['premise_length'];
				if($premise_length > 0){
					$sql = "INSERT INTO maintenance_ticket_premise(\"iMaintenanceTicketId\",\"iPremiseId\", \"dMaintenanceStartDate\", \"dResolvedDate\", \"dAddedDate\", \"dModifiedDate\") VALUES ";
					for($i=0; $i<$premise_length; $i++){
						$iPremiseId = $this->insert_arr['iPremiseId'][$i];
						$dMaintenanceStartDate = '';
						$dResolvedDate = '';
						if($this->insert_arr['dMaintenanceStartDate'][$i] != '')
							$dMaintenanceStartDate = $this->insert_arr['dMaintenanceStartDate'][$i]." ".date('H:i:s');
						if($this->insert_arr['dResolvedDate'][$i] != '')
							$dResolvedDate =  $this->insert_arr['dResolvedDate'][$i]." ".date('H:i:s');

						$sql .= "(".gen_allow_null_int($iMaintenanceTicketId).", ".gen_allow_null_int($iPremiseId).", ".gen_allow_null_char($dMaintenanceStartDate).", ".gen_allow_null_char($dResolvedDate).",".gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_char(date_getSystemDateTime())."), ";
					}
					$sqlObj->Execute(substr($sql, 0, -2));
				}
			}
			return $iMaintenanceTicketId;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE maintenance_ticket SET 
			\"iAssignedToId\"=".gen_allow_null_char($this->update_arr['iAssignedToId']).", 
			\"iServiceOrderId\"=".gen_allow_null_char($this->update_arr['iServiceOrderId']).", 
			\"iSeverity\"=".gen_allow_null_char($this->update_arr['iSeverity']).", 
			\"iStatus\"=".gen_allow_null_char($this->update_arr['iStatus']).", 
			\"dCompletionDate\"=".gen_allow_null_char($this->update_arr['dCompletionDate']).", 
			\"tDescription\"=".gen_allow_null_char($this->update_arr['tDescription']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())."
			WHERE \"iMaintenanceTicketId\" = ".$this->update_arr['iMaintenanceTicketId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			if($rs_up) {
				$iMaintenanceTicketId = $this->update_arr['iMaintenanceTicketId'];
				$premise_length = $this->update_arr['premise_length'];

				$sql_del = "DELETE FROM maintenance_ticket_premise WHERE maintenance_ticket_premise.\"iMaintenanceTicketId\" = '".$iMaintenanceTicketId."'";
				$rs_del = $sqlObj->Execute($sql_del);

				if($premise_length > 0){
					$sql = "INSERT INTO maintenance_ticket_premise(\"iMaintenanceTicketId\",\"iPremiseId\", \"dMaintenanceStartDate\", \"dResolvedDate\", \"dAddedDate\", \"dModifiedDate\") VALUES ";
					for($i=0; $i<$premise_length; $i++){
						$iPremiseId = $this->update_arr['iPremiseId'][$i];
						$dMaintenanceStartDate = '';
						$dResolvedDate = '';
						if($this->update_arr['dMaintenanceStartDate'][$i] != '')
							$dMaintenanceStartDate = $this->update_arr['dMaintenanceStartDate'][$i]." ".date('H:i:s');
						if($this->update_arr['dResolvedDate'][$i] != '')
							$dResolvedDate =  $this->update_arr['dResolvedDate'][$i]." ".date('H:i:s');

						$sql .= "(".gen_allow_null_int($iMaintenanceTicketId).", ".gen_allow_null_int($iPremiseId).", ".gen_allow_null_char($dMaintenanceStartDate).", ".gen_allow_null_char($dResolvedDate).",".gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_char(date_getSystemDateTime())."), ";
					}
					//echo $sql;exit;
					$sqlObj->Execute(substr($sql, 0, -2));
				}
			}
			
			return $rs_up;
		}
	}

	function change_status($ids, $status){
		global $sqlObj;
		
		$sql_del = "UPDATE maintenance_ticket set \"iStatus\" = '".$status."' WHERE maintenance_ticket.\"iMaintenanceTicketId\" IN (".$ids.")";
		$rs_del = $sqlObj->Execute($sql_del);
		return $rs_del;
	}
	
	function maintenance_ticket_premise_recordset_list()
	{
		global $sqlObj;
			
		$sql = "SELECT maintenance_ticket_premise.* ".$this->join_field_str." FROM maintenance_ticket_premise".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		//echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}

	function recordset_glance_data($where_clause1 = "",$where_clause2 ="") {	
		global $sqlObj;
		if($where_clause1 != ""){
			$where_clause1 = " WHERE ".$where_clause1 ;	
		}

		if($where_clause2 != ""){
			$where_clause2 = " WHERE ".$where_clause2 ;	
		}

		$sql_glance =  "select (SELECT count(\"iMaintenanceTicketId\")  from  maintenance_ticket ".$where_clause1." ) as mtcount1, ( SELECT  count(\"iMaintenanceTicketId\") from  maintenance_ticket ".$where_clause2." ) as mtcount2 ";
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