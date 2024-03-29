<?php
include_once("security_audit_log.inc.php");
class PremiseServices {
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
	
	function PremiseServices(){
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
			
		$sql = "SELECT premise_services.* ".$this->join_field_str." FROM premise_services".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}
	
	function start_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO premise_services( \"iPremiseId\", \"iServiceTypeId\", \"iWOId\", \"iStatus\", \"iServiceOrderId\", \"iCarrierId\", \"iPremiseCircuitId\", \"iUserId\", \"iNRCVariable\", \"iMRCFixed\", \"dStartDate\", \"dAddedDate\", \"isSuspended\")VALUES (".gen_allow_null_char($this->insert_arr['iPremiseId']).", ".gen_allow_null_char($this->insert_arr['iServiceTypeId']).", ".gen_allow_null_char($this->insert_arr['iWOId']).", ".gen_allow_null_char($this->insert_arr['iStatus']).", ".gen_allow_null_char($this->insert_arr['iServiceOrderId']).", ".gen_allow_null_char($this->insert_arr['iCarrierId']).", ".gen_allow_null_char($this->insert_arr['iPremiseCircuitId']).", ".gen_allow_null_char($this->insert_arr['iUserId']).", ".gen_allow_null_char($this->insert_arr['iNRCVariable']).", ".gen_allow_null_char($this->insert_arr['iMRCFixed']).", ".gen_allow_null_char($this->insert_arr['dStartDate']).", ".gen_allow_null_char(date_getSystemDateTime()).", ".gen_allow_null_char($this->insert_arr['isSuspended']).")";
			
			$sqlObj->Execute($sql);		
			$rs_db = $sqlObj-> Insert_ID();

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "Premise Service Start";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/

			return $rs_db;
		}
	}

	function suspend_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO premise_services( \"iPremiseId\", \"iServiceTypeId\", \"iWOId\", \"iStatus\", \"iServiceOrderId\", \"iCarrierId\", \"iPremiseCircuitId\", \"iUserId\", \"dSuspendDate\", \"dAddedDate\")VALUES (".gen_allow_null_char($this->insert_arr['iPremiseId']).", ".gen_allow_null_char($this->insert_arr['iServiceTypeId']).", ".gen_allow_null_char($this->insert_arr['iWOId']).", ".gen_allow_null_char($this->insert_arr['iStatus']).", ".gen_allow_null_char($this->insert_arr['iServiceOrderId']).", ".gen_allow_null_char($this->insert_arr['iCarrierId']).", ".gen_allow_null_char($this->insert_arr['iPremiseCircuitId']).", ".gen_allow_null_char($this->insert_arr['iUserId']).", ".gen_allow_null_char($this->insert_arr['dSuspendDate']).", ".gen_allow_null_char(date_getSystemDateTime()).")";
			
			$sqlObj->Execute($sql);		
			$rs_db = $sqlObj-> Insert_ID();
			if($rs_db){ 
				$iPremiseServiceId = $this->insert_arr['iLastStartedPremiseServiceId'];
				if($iPremiseServiceId > 0) {
					# When new premise service is started suspend the previous service for the same service type.
					$sql = "UPDATE premise_services set \"isSuspended\" = 1 WHERE \"iPremiseServiceId\" = ".$iPremiseServiceId;
					$rs = $sqlObj->Execute($sql);	

					# When premise service is suspended, also change the Service Status as disconnected in service order.
					$sqls = "UPDATE service_order set \"iSStatus\" = 4 WHERE \"iServiceOrderId\" = ".$this->insert_arr['iServiceOrderId'];
					$rss = $sqlObj->Execute($sqls);
				}
			}

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "Premise Service Suspend";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/

			return $rs_db;
		}
	}

	function update_records(){
		global $sqlObj, $site_path;
		if($this->update_arr){
			$rs_db = "UPDATE service_pricing_mas SET  \"iCarrierId\"=".gen_allow_null_char($this->update_arr['iCarrierId']).", \"iNetworkId\"=".gen_allow_null_char($this->update_arr['iNetworkId']).", \"iServiceTypeId\"=".gen_allow_null_char($this->update_arr['iServiceTypeId']).", \"iNRCVariable\"=".gen_allow_null_char($this->update_arr['iNRCVariable']).", \"iMRCFixed\"=".gen_allow_null_char($this->update_arr['iMRCFixed']).", \"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iServicePricingId\" = ".$this->update_arr['iServicePricingId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();

			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 1;
			$this->SALObj->module_name = "Premise Service Start";
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
?>