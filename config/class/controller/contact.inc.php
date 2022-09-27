<?php
include_once("security_audit_log.inc.php");
class Contact {
	var $iContactId;
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
	
	function Contact(){
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

		$sql = "SELECT contact_mas.* " . $this->join_field_str . " FROM contact_mas " . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		//echo $sql;exit();
		$rs_db = $sqlObj->GetAll($sql);

		//$this->debug_query($sql);
		return $rs_db;
		
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT contact_mas.* ".$this->join_field_str." FROM contact_mas ".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
		
	}
	function delete_records($iCId){
		global $sqlObj;

		$sql='UPDATE contact_mas SET "iDelete"=1 WHERE "iCId"=' . gen_allow_null_int($iCId);
		$sqlObj->Execute($sql);
		//gen_writeDataInTmpFile($sql);
		$rs_del =$sqlObj->Affected_Rows();

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "contact";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_del;
	}
	
	function add_records() {
		global $sqlObj;
		if($this->insert_arr) {
			$vPhone			= $this->setPhoneNumberValue($this->insert_arr['vPhone']);
			$sql_contact = "INSERT INTO contact_mas (\"vSalutation\", \"vFirstName\", \"vLastName\", \"vCompany\", \"vPosition\", \"vEmail\", \"vPhone\",\"tNotes\",  \"iStatus\", \"iDelete\",\"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['vSalutation']).", ".gen_allow_null_char($this->insert_arr['vFirstName']).", ".gen_allow_null_char($this->insert_arr['vLastName']).", ".gen_allow_null_char($this->insert_arr['vCompany']).", ".gen_allow_null_char($this->insert_arr['vPosition']).", ".gen_allow_null_char($this->insert_arr['vEmail']).", ".gen_allow_null_char($vPhone).",".gen_allow_null_char($this->insert_arr['tNotes']).", ".gen_allow_null_int($this->insert_arr['iStatus']).",0,".gen_allow_null_char(date_getSystemDateTime()).")";
			
			$sqlObj->Execute($sql_contact);
			$iCId = $sqlObj->Insert_ID();

			
			
			return $iCId;
		}
	}
	function update_records(){
		global $sqlObj;

		if($this->update_arr){			
			$vPhone	= $this->setPhoneNumberValue($this->update_arr['vPhone']);

			$sql_contact = "UPDATE contact_mas SET  \"vSalutation\"=".gen_allow_null_char($this->update_arr['vSalutation']).", \"vFirstName\"=".gen_allow_null_char($this->update_arr['vFirstName']).", \"vLastName\"=".gen_allow_null_char($this->update_arr['vLastName']).", \"vCompany\"=".gen_allow_null_char($this->update_arr['vCompany']).", \"vPosition\"=".gen_allow_null_char($this->update_arr['vPosition']).", \"vEmail\"=".gen_allow_null_char($this->update_arr['vEmail']).", \"vPhone\" =".gen_allow_null_char($vPhone)."  ,\"tNotes\"=".gen_allow_null_char($this->update_arr['tNotes']).", \"iStatus\" =".gen_allow_null_int($this->update_arr['iStatus']).", \"dModifiedDate\"= ".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iCId\"=".gen_allow_null_int($this->update_arr['iCId']);
			
			$rs_up = $sqlObj->Execute($sql_contact);
			return $rs_up;
		}
	}
	
	private function setPhoneNumberValue($arr=array(), $sep_sign=" ") {
		if(count($arr)) {
			$vPhone="";
			for($a=0, $na=count($arr); $a<$na; $a++) {
				if(trim($arr[$a])!="")
					$vPhone .= $arr[$a] . $sep_sign;
			}
			if(trim($vPhone)!="") {
				return substr($vPhone, 0, -strlen($sep_sign));
			}
			else return "";
		}
		else return "";
	}

	public function getPhoneValueArr($vPhone, $sep_sign=" ") {
		return explode($sep_sign, $vPhone);
	}
	

	function contact_clear_variable(){
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
	function contact_site_details_list()
	{
	
		global $sqlObj;

		$sql = "SELECT site_contact.\"iSCId\"" . $this->join_field_str . " FROM site_contact " . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		//echo $sql;exit();
		$rs_db = $sqlObj->GetAll($sql);

		//$this->debug_query($sql);
		return $rs_db;
	}
}
?>