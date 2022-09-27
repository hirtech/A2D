<?php
include_once("security_audit_log.inc.php");
class SR {
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
	
	function SR(){
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

		$sql = "SELECT sr_details.* ".$this->join_field_str." FROM sr_details".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT DISTINCT sr_details.* ".$this->join_field_str." FROM sr_details".$this->join_clause.$this->where_clause.$this->group_by_clause;

		$rs_db = $sqlObj->Execute($sql);
		
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
			
		return $count;
		
	}

	function add_records(){ //Service Request

		global $sqlObj, $admin_panel_session_suffix, $function_path;

		$iLoginUserId = $_SESSION["sess_iUserId".$admin_panel_session_suffix];

		$sql = "INSERT INTO sr_details(\"iCId\", \"vAddress1\", \"vAddress2\", \"vStreet\",\"vCrossStreet\", \"iZipcode\", \"iStateId\", \"iCountyId\", \"iCityId\", \"iZoneId\", \"vLatitude\", \"vLongitude\", \"bMosquitoService\", \"bCarcassService\", \"iUserId\", \"bInspectPermission\", \"bAccessPermission\", \"bPets\", \"tProblems\", \"tInternalNotes\", \"tRequestorNotes\", \"iStatus\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['iCId']).", ".gen_allow_null_char($this->insert_arr['vAddress1']).", ".gen_allow_null_char($this->insert_arr['vAddress2']).", ".gen_allow_null_char($this->insert_arr['vStreet']).", ".gen_allow_null_char($this->insert_arr['vCrossStreet']).", ".gen_allow_null_char($this->insert_arr['iZipcode']).", ".gen_allow_null_char($this->insert_arr['iStateId']).", ".gen_allow_null_char($this->insert_arr['iCountyId']).", ".gen_allow_null_char($this->insert_arr['iCityId']).", ".gen_allow_null_char($this->insert_arr['iZoneId']).", ".gen_allow_null_char($this->insert_arr['vLatitude']).", ".gen_allow_null_char($this->insert_arr['vLongitude']).", ".gen_allow_null_char($this->insert_arr['bMosquitoService']).", ".gen_allow_null_char($this->insert_arr['bCarcassService']).", ".gen_allow_null_char($this->insert_arr['iUserId']).", ".gen_allow_null_char($this->insert_arr['bInspectPermission']).", ".gen_allow_null_char($this->insert_arr['bAccessPermission']).", ".gen_allow_null_char($this->insert_arr['bPets']).", ".gen_allow_null_char($this->insert_arr['tProblems']).", ".gen_allow_null_char($this->insert_arr['tInternalNotes']).", ".gen_allow_null_char($this->insert_arr['tRequestorNotes']).", ".gen_allow_null_char($this->insert_arr['iStatus']).",".gen_allow_null_char(date_getSystemDateTime()).")";
		//echo $sql;exit;
		$sqlObj->Execute($sql);		
			
		$iSRId = $sqlObj->Insert_ID();
		if($iSRId) {
			if($this->insert_arr['iCId'] > 0) {
				include_once($function_path."mail.inc.php");

				$mailed = sendSystemMail("User", "SRContact", $iSRId, $reminder_flag = 0);
			
				$mail = 0;
				if($mailed){
					$mail = 1;
				}
			}
			if($this->insert_arr['iStatus']!="") {
				$iLoginUserId = $this->insert_arr['iLoginUserId'];
				$sql = "INSERT INTO sr_status_history(\"iSRId\", \"iStatus\", \"dAddedDate\", \"iLoginUserId\") VALUES (".gen_allow_null_int($iSRId).", ".$this->insert_arr['iStatus'].",  ".gen_allow_null_char(date_getSystemDateTime()).", ".gen_allow_null_int($iLoginUserId).")";
				$sqlObj->Execute($sql);		
			}
		}

		return $iSRId;
	}
	
	function update_records(){
		global $sqlObj, $admin_panel_session_suffix, $function_path;

		if($this->update_arr){

			$rs_db = "UPDATE sr_details SET 
			\"iCId\"=".gen_allow_null_char($this->update_arr['iCId']).", 
			\"vAddress1\"=".gen_allow_null_char($this->update_arr['vAddress1']).", 
			\"vAddress2\"=".gen_allow_null_char($this->update_arr['vAddress2']).", 
			\"vStreet\"=".gen_allow_null_char($this->update_arr['vStreet']).", 
			\"vCrossStreet\"=".gen_allow_null_char($this->update_arr['vCrossStreet']).", 
			\"iZipcode\"=".gen_allow_null_char($this->update_arr['iZipcode']).", 
			\"iStateId\"=".gen_allow_null_char($this->update_arr['iStateId']).", 
			\"iCountyId\"=".gen_allow_null_char($this->update_arr['iCountyId']).", 
			\"iCityId\"=".gen_allow_null_char($this->update_arr['iCityId']).", 
			\"iZoneId\"=".gen_allow_null_char($this->update_arr['iZoneId']).", 
			\"vLatitude\"=".gen_allow_null_char($this->update_arr['vLatitude']).", 
			\"vLongitude\"=".gen_allow_null_char($this->update_arr['vLongitude']).", 
			\"bMosquitoService\"=".gen_allow_null_char($this->update_arr['bMosquitoService']).", 
			\"bCarcassService\"=".gen_allow_null_char($this->update_arr['bCarcassService']).", 
			\"iUserId\"=".gen_allow_null_char($this->update_arr['iUserId']).", 
			\"bInspectPermission\"=".gen_allow_null_char($this->update_arr['bInspectPermission']).", 
			\"bAccessPermission\"=".gen_allow_null_char($this->update_arr['bAccessPermission']).", 
			\"bPets\"=".gen_allow_null_char($this->update_arr['bPets']).", 
			\"tProblems\"=".gen_allow_null_char($this->update_arr['tProblems']).", 
			\"tInternalNotes\"=".gen_allow_null_char($this->update_arr['tInternalNotes']).", 
			\"tRequestorNotes\"=".gen_allow_null_char($this->update_arr['tRequestorNotes']).", 
			\"iStatus\"=".gen_allow_null_char($this->update_arr['iStatus']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." 
			WHERE \"iSRId\" = ".$this->update_arr['iSRId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			$iSRId = $this->update_arr['iSRId'];
			
			if($rs_up) {

				$iOldStatus = $this->update_arr['iOldStatus'];
				$iStatus = $this->update_arr['iStatus'];
				$iLoginUserId = $this->update_arr['iLoginUserId'];
				if($iOldStatus != $iStatus){
					$sql = "INSERT INTO sr_status_history(\"iSRId\", \"iStatus\", \"dAddedDate\", \"iLoginUserId\") VALUES (".gen_allow_null_int($iSRId).", ".$iStatus.",  ".gen_allow_null_char(date_getSystemDateTime()).", ".gen_allow_null_int($iLoginUserId).")";
					$sqlObj->Execute($sql);	
					if($iStatus == 4) { // Completed
						include_once($function_path."mail.inc.php");

						$mailed = sendSystemMail("User", "SRComplete", $iSRId);
					
						$mail = 0;
						if($mailed){
							$mail = 1;
						}
					}

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

	function delete_records($iSRId){
		global $sqlObj;

        $sql_del = "DELETE FROM sr_details WHERE sr_details.\"iSRId\" IN (".$iSRId.")";
		$rs_del = $sqlObj->Execute($sql_del);

        $sql = "DELETE FROM sr_status_history WHERE \"iSRId\" IN (" . $iSRId . ")";
        $sqlObj->Execute($sql);

		return $rs_del;
	}

	function recordset_glance_data($where_clause1 = "",$where_clause2 ="")
	{	global $sqlObj;
		if($where_clause1 != ""){
			$where_clause1 = " WHERE ".$where_clause1 ;	
		}

		if($where_clause2 != ""){
			$where_clause2 = " WHERE ".$where_clause2 ;	
		}

		$sql_glance =  "select (SELECT count(\"iSRId\")  from  sr_details ".$where_clause1." ) as srcount1 , ( SELECT  count(\"iSRId\")  from  sr_details  ".$where_clause2." ) as srcount2 ";
		//echo $sql_glance;exit();
		$rs_db = $sqlObj->GetAll($sql_glance);
			
		return $rs_db;
	}
}
?>