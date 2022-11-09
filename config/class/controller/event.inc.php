<?php
include_once("security_audit_log.inc.php");
class Event {

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
	
	function Event() {
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
			
		$sql = "SELECT event.* ".$this->join_field_str." FROM event".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		//echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT event.* ".$this->join_field_str." FROM event".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql_del = "DELETE FROM event WHERE event.\"iEventId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);
		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			//echo"<pre>";print_r($this->insert_arr);exit;
			$sql = "INSERT INTO event(\"iEventTypeId\", \"iCampaignBy\", \"iStatus\", \"dCompletedDate\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['iEventTypeId']).", ".gen_allow_null_char($this->insert_arr['iCampaignBy']).", ".gen_allow_null_char($this->insert_arr['iStatus']).", ".gen_allow_null_char($this->insert_arr['dCompletedDate']).",".gen_allow_null_char(date_getSystemDateTime()).")";
			//echo $sql;exit;
			$sqlObj->Execute($sql);		
			$iEventId = $sqlObj->Insert_ID();
			if($iEventId > 0){
				$iCampaignCoverageIdArr = [];
				if($this->insert_arr['iCampaignBy'] == 1) {
					$iCampaignCoverageIdArr = $this->insert_arr['iPremiseId'];
				}else if($this->insert_arr['iCampaignBy'] == 2) {
					$iCampaignCoverageIdArr = $this->insert_arr['iZoneId'];
				}else if($this->insert_arr['iCampaignBy'] == 3) {
					$iCampaignCoverageIdArr = $this->insert_arr['iZipcode'];
				}else if($this->insert_arr['iCampaignBy'] == 4) {
					$iCampaignCoverageIdArr = $this->insert_arr['iCityId'];
				}else if($this->insert_arr['iCampaignBy'] == 5) {
					$iCampaignCoverageIdArr = $this->insert_arr['iNetworkId'];
				}
				//print_r($iCampaignCoverageIdArr);exit;
				if(count($iCampaignCoverageIdArr) > 0){
					$ni =count($iCampaignCoverageIdArr);
					$sql = "INSERT INTO event_campaign_coverage(\"iEventId\", \"iCampaignBy\", \"iCampaignCoverageId\", \"dAddedDate\", \"dModifiedDate\") VALUES ";
					for($i=0;$i<$ni;$i++){
						$sql .= "(".gen_allow_null_int($iEventId).", ".gen_allow_null_int($this->insert_arr['iCampaignBy']).", ".gen_allow_null_int($iCampaignCoverageIdArr[$i]).",".gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_char(date_getSystemDateTime())."), ";
					}
					$sqlObj->Execute(substr($sql, 0, -2));
				}
			}
			return $iEventId;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE event SET 
			\"iEventTypeId\"=".gen_allow_null_char($this->update_arr['iEventTypeId']).", 
			\"iCampaignBy\"=".gen_allow_null_char($this->update_arr['iCampaignBy']).", 
			\"iStatus\"=".gen_allow_null_char($this->update_arr['iStatus']).", 
			\"dCompletedDate\"=".gen_allow_null_char($this->update_arr['dCompletedDate']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())."
			WHERE \"iEventId\" = ".$this->update_arr['iEventId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			if($rs_up) {
				$iEventId = $this->update_arr['iEventId'];
				$sql_del = "DELETE FROM event_campaign_coverage WHERE event_campaign_coverage.\"iEventId\" = ".gen_allow_null_int($iEventId)."";
				$rs_del = $sqlObj->Execute($sql_del);

				$iCampaignCoverageIdArr = [];
				if($this->update_arr['iCampaignBy'] == 1) {
					$iCampaignCoverageIdArr = $this->update_arr['iPremiseId'];
				}else if($this->update_arr['iCampaignBy'] == 2) {
					$iCampaignCoverageIdArr = $this->update_arr['iZoneId'];
				}else if($this->update_arr['iCampaignBy'] == 3) {
					$iCampaignCoverageIdArr = $this->update_arr['iZipcode'];
				}else if($this->update_arr['iCampaignBy'] == 4) {
					$iCampaignCoverageIdArr = $this->update_arr['iCityId'];
				}else if($this->update_arr['iCampaignBy'] == 5) {
					$iCampaignCoverageIdArr = $this->update_arr['iNetworkId'];
				}
				//print_r($iCampaignCoverageIdArr);exit;
				if(count($iCampaignCoverageIdArr) > 0){
					$ni =count($iCampaignCoverageIdArr);
					$sql = "INSERT INTO event_campaign_coverage(\"iEventId\", \"iCampaignBy\", \"iCampaignCoverageId\", \"dAddedDate\", \"dModifiedDate\") VALUES ";
					for($i=0;$i<$ni;$i++){
						$sql .= "(".gen_allow_null_int($iEventId).", ".gen_allow_null_int($this->update_arr['iCampaignBy']).", ".gen_allow_null_int($iCampaignCoverageIdArr[$i]).",".gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_char(date_getSystemDateTime())."), ";
					}
					$sqlObj->Execute(substr($sql, 0, -2));
				}
			}
			
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