<?php
include_once("security_audit_log.inc.php");
class Invoice {

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
	
	function Invoice() {
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

	function recordset_list() {
		global $sqlObj;
			
		$sql = "SELECT invoice.* ".$this->join_field_str." FROM invoice".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		// echo $sql;exit;
		// echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total() {
		global $sqlObj;
			
		$sql = "SELECT invoice.* ".$this->join_field_str." FROM invoice".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
	}
	
	function add_records(){
		global $sqlObj;
		if($this->insert_arr){
			$sql = "INSERT INTO invoice(\"iCustomerId\", \"vPONumber\", \"dInvoiceDate\", \"dPaymentDate\", \"iBillingMonth\", \"iBillingYear\", \"tNotes\", \"iStatus\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['iCustomerId']).", ".gen_allow_null_char($this->insert_arr['vPONumber']).", ".gen_allow_null_char($this->insert_arr['dInvoiceDate']).", ".gen_allow_null_char($this->insert_arr['dPaymentDate']).", ".gen_allow_null_char($this->insert_arr['iBillingMonth']).", ".gen_allow_null_char($this->insert_arr['iBillingYear']).", ".gen_allow_null_char($this->insert_arr['tNotes']).", ".gen_allow_null_char($this->insert_arr['iStatus']).",".gen_allow_null_char(date_getSystemDateTime()).")";
			//echo $sql;exit;
			$sqlObj->Execute($sql);		
			$iInvoiceId = $sqlObj->Insert_ID();
			if($iInvoiceId > 0){
				$sql_ps = "SELECT premise_services.*, to_char(\"dStartDate\", 'MM') as \"iBillingMonth\", to_char(\"dStartDate\", 'YYYY') as \"iBillingYear\" FROM premise_services WHERE \"iCarrierId\" = '".$this->insert_arr['iCustomerId']."' ORDER BY \"iPremiseServiceId\"";
				$rs_ps = $sqlObj->GetAll($sql_ps);
				$ni = count($rs_ps);
				$total_amt = 0;
				if($ni > 0) {
					$ins_arr = [];
					$sql = "INSERT INTO invoice_lines(\"iInvoiceId\", \"iPremiseServiceId\", \"iPremiseId\", \"iServiceTypeId\", \"iPremiseServiceStatus\", \"iNRCVariable\", \"iMRCFixed\", \"dStartDate\", \"iStatus\", \"dAddedDate\") VALUES ";
					$total_nrc = 0;
					$total_mrc = 0;
					for ($i=0; $i<$ni; $i++) {
						$iPremiseServiceId = $rs_ps[$i]['iPremiseServiceId'];
						$iPremiseId = $rs_ps[$i]['iPremiseId'];
						$iServiceTypeId = $rs_ps[$i]['iServiceTypeId'];
						$iPremiseServiceStatus = $rs_ps[$i]['iStatus'];
						
						$iMRCFixed = $rs_ps[$i]['iMRCFixed'];
						$dStartDate = $rs_ps[$i]['dStartDate'];
						$iBillingMonth = $rs_ps[$i]['iBillingMonth'];
						$iBillingYear = $rs_ps[$i]['iBillingYear'];
						$iNRCVariable = 0;
						if($iBillingMonth >= $this->insert_arr['iBillingMonth'] && $iBillingYear >= $this->insert_arr['iBillingYear']){
							$iNRCVariable = $rs_ps[$i]['iNRCVariable'];
						}
						$total_mrc += $iMRCFixed;
						$total_nrc += $iNRCVariable;
						$iStatus = 1; //Active
						$sql .= "(".gen_allow_null_int($iInvoiceId).", ".gen_allow_null_int($iPremiseServiceId).", ".gen_allow_null_int($iPremiseId).", ".gen_allow_null_int($iServiceTypeId).", ".gen_allow_null_int($iPremiseServiceStatus).", ".gen_allow_null_int($iNRCVariable).", ".gen_allow_null_int($iMRCFixed).", ".gen_allow_null_char($dStartDate).", ".gen_allow_null_int($iStatus).", ".gen_allow_null_char(date_getSystemDateTime())."), ";

					}
					//echo $sql;exit;
					$sqlObj->Execute(substr($sql, 0, -2));
				}

				// Update total amount in invoice
				$total_amt = $total_mrc + $total_nrc;
				$sql_updt = "UPDATE invoice set \"rTotalAmount\" = '".$total_amt."' WHERE \"iInvoiceId\" = '".$iInvoiceId."'";
				$rs_updt = $sqlObj->Execute($sql_updt);

				// Insert status in to invoice status
				$sql_status_ins = "INSERT INTO invoice_status(\"iInvoiceId\", \"iStatus\", \"iUserId\", \"dAddedDate\") VALUES (".gen_allow_null_char($iInvoiceId).", ".gen_allow_null_char($this->insert_arr['iStatus']).", ".gen_allow_null_char($this->insert_arr['iLoginUserId']).",".gen_allow_null_char(date_getSystemDateTime()).")";
				$sqlObj->Execute($sql_status_ins);
			}
			return $iInvoiceId;
		}
	}

	function invoice_lines_recordset_list()
	{
		global $sqlObj;
			
		$sql = "SELECT invoice_lines.* ".$this->join_field_str." FROM invoice_lines".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		// echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}

	function change_status($iInvoiceId, $iStatus, $iLoginUserId)
	{
		global $sqlObj;
			
		$sql_updt = "UPDATE invoice set \"iStatus\" = '".$iStatus."' WHERE \"iInvoiceId\" = '".$iInvoiceId."'";
		$rs_updt = $sqlObj->Execute($sql_updt);

		// Insert status in to invoice status
		$sql_status_ins = "INSERT INTO invoice_status(\"iInvoiceId\", \"iStatus\", \"iUserId\", \"dAddedDate\") VALUES (".gen_allow_null_char($iInvoiceId).", ".gen_allow_null_char($iStatus).", ".gen_allow_null_char($iLoginUserId).",".gen_allow_null_char(date_getSystemDateTime()).")";
		$sqlObj->Execute($sql_status_ins);
		return $rs_updt;
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