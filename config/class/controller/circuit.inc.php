<?php
class Circuit {
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
	
	function Circuit(){
		
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
			
		$sql = "SELECT circuit.* ".$this->join_field_str." FROM circuit".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}

	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT circuit.\"iCircuitId\" ".$this->join_field_str." FROM circuit".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
		
	}
	
	function delete_records($iCircuitId){
		global $sqlObj;
		
        $sql_del = "DELETE FROM circuit WHERE circuit.\"iCircuitId\" IN (".$iCircuitId.")";
        //echo $sql_del;exit;
		$rs_del = $sqlObj->Execute($sql_del);

		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj, $circuit_path, $circuit_url;
		if($this->insert_arr){
			$sql = "INSERT INTO circuit(\"iCircuitTypeId\",\"iNetworkId\", \"vCircuitName\", \"dAddedDate\", \"vName\", \"tComments\", \"vFile\")VALUES ('".$this->insert_arr['iCircuitTypeId']."', '".$this->insert_arr['iNetworkId']."', '".$this->insert_arr['vCircuitName']."', ".gen_allow_null_char(date_getSystemDateTime()).", '".$this->insert_arr['vName']."', '".$this->insert_arr['tComments']."', '".$this->insert_arr['vFile']."')";
			$sqlObj->Execute($sql);
			$iCircuitId = $sqlObj->Insert_ID();
			return $iCircuitId;
		}
	}

	function update_records(){
		global $sqlObj, $circuit_path, $circuit_url;

		if($this->update_arr){
			$rs_db = "UPDATE circuit SET \"iCircuitTypeId\"='".$this->update_arr['iCircuitTypeId']."', \"iNetworkId\"='".$this->update_arr['iNetworkId']."', \"vCircuitName\"='".$this->update_arr['vCircuitName']."',\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime()).", \"vName\"='".$this->update_arr['vName']."', \"tComments\"='".$this->update_arr['tComments']."', \"vFile\"='".$this->update_arr['vFile']."'  WHERE \"iCircuitId\"='".$this->update_arr['iCircuitId']."'";
			$sqlObj->Execute($rs_db);
			$rs_db = $sqlObj->Affected_Rows();
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
