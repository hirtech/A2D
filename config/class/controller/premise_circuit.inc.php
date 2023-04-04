<?php
class PremiseCircuit {
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
	
	function PremiseCircuit(){
		
	}
	function setClause() {
		//Join Fields for select query	
		if(is_array($this->join_field) && count($this->join_field) > 0){
			$this->join_field_str = " , ".implode(", ", $this->join_field);
		}
		// Join clause
		if(is_array($this->join) && count($this->join) > 0){
			$this->join_clause = implode(" ", $this->join);	
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
			
		$sql = "SELECT premise_circuit.* ".$this->join_field_str." FROM premise_circuit".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}

	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT premise_circuit.\"iPremiseCircuitId\" ".$this->join_field_str." FROM premise_circuit".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
		
	}
	
	function delete_records($iPremiseCircuitId){
		global $sqlObj;
		
        $sql_del = "DELETE FROM premise_circuit WHERE premise_circuit.\"iPremiseCircuitId\" IN (".$iPremiseCircuitId.")";
        //echo $sql_del;exit;
		$rs_del = $sqlObj->Execute($sql_del);

		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj, $premise_circuit_path, $premise_circuit_url;
		if($this->insert_arr){
			$sql = "INSERT INTO premise_circuit(\"iWOId\",\"iPremiseId\", \"iCircuitId\", \"dAddedDate\", \"iConnectionTypeId\", \"iStatus\", \"vName\", \"tComments\", \"vFile\")VALUES ('".$this->insert_arr['iWOId']."','".$this->insert_arr['iPremiseId']."', '".$this->insert_arr['iCircuitId']."', ".gen_allow_null_char(date_getSystemDateTime()).", '".$this->insert_arr['iConnectionTypeId']."', '".$this->insert_arr['iStatus']."', '".$this->insert_arr['vName']."', '".$this->insert_arr['tComments']."', '".$this->insert_arr['vFile']."')";
			//echo $sql;exit;
			$sqlObj->Execute($sql);
			$iPremiseCircuitId = $sqlObj->Insert_ID();
			if($iPremiseCircuitId) {
				// Insert status in to invoice status
				$sql_status_ins = "INSERT INTO premise_circuit_status(\"iPremiseCircuitId\", \"iStatus\", \"iUserId\", \"dAddedDate\") VALUES (".gen_allow_null_char($iPremiseCircuitId).", ".gen_allow_null_char($this->insert_arr['iStatus']).", ".gen_allow_null_char($this->insert_arr['iLoginUserId']).",".gen_allow_null_char(date_getSystemDateTime()).")";
				$sqlObj->Execute($sql_status_ins);

				//Change the premise-status to "On-Net" when premise-circuit status is "Connected" or "Active"
				if($this->insert_arr['iPremiseId'] > 0 && ($this->insert_arr['iStatus'] == 4 || $this->insert_arr['iStatus'] == 5)) {
					$sql_premise = "UPDATE premise_mas SET \"iStatus\" = 1 WHERE \"iPremiseId\" = '".$this->insert_arr['iPremiseId']."'";
					$sqlObj->Execute($sql_premise);
				}
			}
			return $iPremiseCircuitId;
		}
	}

	function update_records(){
		global $sqlObj, $premise_circuit_path, $premise_circuit_url;

		if($this->update_arr){
			$rs_db = "UPDATE premise_circuit SET \"iWOId\"='".$this->update_arr['iWOId']."',\"iPremiseId\"='".$this->update_arr['iPremiseId']."', \"iCircuitId\"='".$this->update_arr['iCircuitId']."',\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime()).",\"iConnectionTypeId\" = ".gen_allow_null_char($this->update_arr['iConnectionTypeId']).", \"iStatus\" = ".gen_allow_null_char($this->update_arr['iStatus']).", \"vName\" = ".gen_allow_null_char($this->update_arr['vName']).", \"tComments\" = ".gen_allow_null_char($this->update_arr['tComments']).", \"vFile\" = ".gen_allow_null_char($this->update_arr['vFile'])." WHERE \"iPremiseCircuitId\"='".$this->update_arr['iPremiseCircuitId']."'";
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_db = $sqlObj->Affected_Rows();
			if($rs_db) {
				// Insert status in to invoice status
				$sql_status_ins = "INSERT INTO premise_circuit_status(\"iPremiseCircuitId\", \"iStatus\", \"iUserId\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->update_arr['iPremiseCircuitId']).", ".gen_allow_null_char($this->update_arr['iStatus']).", ".gen_allow_null_char($this->update_arr['iLoginUserId']).",".gen_allow_null_char(date_getSystemDateTime()).")";
				$sqlObj->Execute($sql_status_ins);

				//Change the premise-status to "On-Net" when premise-circuit status is "Connected" or "Active"
				if($this->update_arr['iPremiseId'] > 0 && ($this->update_arr['iStatus'] == 4 || $this->update_arr['iStatus'] == 5)) {
					$sql_premise = "UPDATE premise_mas SET \"iStatus\" = 1 WHERE \"iPremiseId\" = '".$this->update_arr['iPremiseId']."'";
					$sqlObj->Execute($sql_premise);
				}
			}
			return $rs_db;
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
