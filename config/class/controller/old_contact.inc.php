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
		
		/*$sql = 'SELECT contact_mas.*, if (SELECT site_mas.* FROM site_mas WHERE "iCId"=contact_mas."iCId")>0 then 1 else 0 endif as site_contact, if (SELECT service_request.* FROM service_request WHERE "iCId"=contact_mas."iCId")>0 then 1 else 0 endif as sr_contact' . $this->join_field_str . ' FROM contact_mas' . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;*/
		$sql = "SELECT contact_mas.* " . $this->join_field_str . " FROM contact_mas " . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		//gen_writeDataInTmpFile($sql);
		//echo $sql;exit();
		$rs_db = $sqlObj->GetAll($sql);
		//echo "<pre>";print_r($rs_db);exit();
		//echo count($rs_db) ;exit();
		/*if(count($rs_db)) {
			for($a=0, $na=count($rs_db); $a<$na; $a++) {
				$s1 = 'SELECT site_mas.* FROM site_mas WHERE "iCId"='.gen_allow_null_int($rs_db[$a]['iCId']);
				$r1 = $sqlObj->GetAll($s1);
				//gen_writeDataInTmpFile($s1);
				if(count($r1)) $rs_db[$a]['hide_delete']=1;
				else {
					$s2 = 'SELECT service_request.* FROM service_request WHERE "iCId"='.gen_allow_null_int($rs_db[$a]['iCId']);
					$r2 = $sqlObj->GetAll($s2);
					//gen_writeDataInTmpFile($s2);
					if(count($r2)) $rs_db[$a]['hide_delete']=1;
					else $rs_db[$a]['hide_delete']=0;
				}
			}
		}*/
		## Function to write query in temp file.
		//gen_writeDataInTmpFile($sql);

		$this->debug_query($sql);
		return $rs_db;
		
	}
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT contact_mas.* " . $this->join_field_str . " FROM contact_mas " . $this->join_clause . $this->where_clause . $this->group_by_clause;
		$rs_db = $sqlObj->GetAll($sql);
		$this->debug_query($sql);
		return count($rs_db);
		
	}
	function delete_records($iCId){
		global $sqlObj;
		
		## Delete From Contact Phone
		/*$s1 = 'DELETE FROM contact_phone WHERE "iCId"=' . gen_allow_null_int($iCId);
		$r1 = $sqlObj->Execute($s1);
		
		## Delete From Contact Master
		$sql = 'DELETE FROM contact_mas WHERE "iCId"=' . gen_allow_null_int($iCId);
		$sqlObj->Execute($sql);*/

		$sql='UPDATE contact_mas SET "iStatus"=3 WHERE "iCId"=' . gen_allow_null_int($iCId);
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
		$iStatus = 1; ## Active
		if($this->insert_arr) {
			$sql_contact = "INSERT INTO contact_mas (\"vSalutation\", \"vFirstName\", \"vLastName\", \"vCompany\", \"vPosition\", \"vEmail\", \"tNotes\", \"dLastModified\", \"iStatus\") VALUES (".gen_allow_null_char($this->insert_arr['vSalutation']).", ".gen_allow_null_char($this->insert_arr['vFirstName']).", ".gen_allow_null_char($this->insert_arr['vLastName']).", ".gen_allow_null_char($this->insert_arr['vCompany']).", ".gen_allow_null_char($this->insert_arr['vPosition']).", ".gen_allow_null_char($this->insert_arr['vEmail']).", ".gen_allow_null_char($this->insert_arr['tNotes']).", TIMESTAMP ".gen_allow_null_char(date_getSystemDateTime()).", ".gen_allow_null_int($iStatus).")";
			## Function to write query in temp file.
			//gen_writeDataInTmpFile($sql_contact);

			$sqlObj->Execute($sql_contact);
			$iCId = $sqlObj->Insert_ID();
			//$this->debug_query($sql_contact);
			if($iCId) {
				$vPhone			= $this->setPhoneNumberValue($this->insert_arr['vPhone']);
				$vCell			= $this->setPhoneNumberValue($this->insert_arr['vCell']);
				## Insert process for contact_phone table
				if(trim($vPhone) !="") ## Primary
					$ins_arr[] = "(".gen_allow_null_int($iCId).", ".gen_allow_null_char($vPhone).", ".gen_allow_null_char("Primary").")";
				if(trim($vCell) !="")  ## Alternate
					$ins_arr[] = "(".gen_allow_null_int($iCId).", ".gen_allow_null_char($vCell).", ".gen_allow_null_char("Alternate").")";
				if(count($ins_arr)) {
					$sql_con = "INSERT INTO contact_phone (\"iCId\", \"vPhone\", \"vType\") VALUES " . implode(", ", $ins_arr);
					$sqlObj->Execute($sql_con);
				}
				## ---------------------------------------
			}
			
			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 0;
			$this->SALObj->module_name = "contact";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/
			
			return $iCId;
		}
	}
	function update_records(){
		global $sqlObj;
		$ins_arr=array();
		//gen_writeDataInTmpFile($this->update_arr);
		if($this->update_arr){
			$sql_contact = "UPDATE contact_mas SET \"vSalutation\"=".gen_allow_null_char($this->update_arr['vSalutation']).", \"vFirstName\"=".gen_allow_null_char($this->update_arr['vFirstName']).", \"vLastName\"=".gen_allow_null_char($this->update_arr['vLastName']).", \"vCompany\"=".gen_allow_null_char($this->update_arr['vCompany']).", \"vPosition\"=".gen_allow_null_char($this->update_arr['vPosition']).", \"vEmail\"=".gen_allow_null_char($this->update_arr['vEmail']).", \"tNotes\"=".gen_allow_null_char($this->update_arr['tNotes']).", \"dLastModified\"= TIMESTAMP ".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iCId\"=".gen_allow_null_int($this->iContactId);
			$sqlObj->Execute($sql_contact);
			$this->debug_query($sql_contact);
			$rs_up = $sqlObj->Affected_Rows();
			//echo "<pre>";print_r($sql_contact);exit;
			## Function to write query in temp file.
			//gen_writeDataInTmpFile($sql_contact);
			if($rs_up) {
				//Added for Phone and Conrtact No
				//Checking for Records exist or not
				$sql = 'SELECT "iCPhoneId", "vType" FROM contact_phone WHERE "iCId"='.gen_allow_null_int($this->iContactId);
				$rs_c = $sqlObj->GetAll($sql);
				//gen_writeDataInTmpFile($sql);
				$cnt_c = count($rs_c);
				if($cnt_c > 0) {
					//\"vType\"=".gen_allow_null_char("Primary")."
					$primary_typecheck = 0;
					$secondry_typecheck = 0;
					for($p=0; $p<$cnt_c; $p++)
					{
						if($rs_c[$p]['vType'] == "Primary") {		//Checking Primary is Exist or not
							$iCPhoneId = $rs_c[$p]['iCPhoneId'];
							$sql_con = "UPDATE contact_phone SET \"vPhone\"=".gen_allow_null_char($this->setPhoneNumberValue($this->update_arr['vPhone']))." WHERE \"iCPhoneId\"=".gen_allow_null_int($iCPhoneId);
							//gen_writeDataInTmpFile($sql_con);
							$sqlObj->Execute($sql_con);
							$primary_typecheck = 1;
						}else if($rs_c[$p]['vType'] == "Alternate") {		//Checking Secondory is Exist or not
							$iCPhoneId = $rs_c[$p]['iCPhoneId'];
							$sql_con = "UPDATE contact_phone SET \"vPhone\"=".gen_allow_null_char($this->setPhoneNumberValue($this->update_arr['vCell']))." WHERE \"iCPhoneId\"=".gen_allow_null_int($iCPhoneId);
							//gen_writeDataInTmpFile($sql_con);
							$sqlObj->Execute($sql_con);
							$secondry_typecheck = 1;
						}
					}
					if($primary_typecheck == 0 && is_array($this->update_arr['vPhone']) && count($this->update_arr['vPhone'])>0) {
						$ins_arr[] = "(".gen_allow_null_int($this->iContactId).", ".gen_allow_null_char($this->setPhoneNumberValue($this->update_arr['vPhone'])).", ".gen_allow_null_char("Primary").")";
					}

					if($secondry_typecheck == 0 && is_array($this->update_arr['vCell']) && count($this->update_arr['vCell'])>0) {
						$ins_arr[] = "(".gen_allow_null_int($this->iContactId).", ".gen_allow_null_char($this->setPhoneNumberValue($this->update_arr['vCell'])).", ".gen_allow_null_char("Alternate").")";
					}
				} else {
					if(is_array($this->update_arr['vPhone']) && count($this->update_arr['vPhone'])>0) { ## Primary
						$ins_arr[] = "(".gen_allow_null_int($this->iContactId).", ".gen_allow_null_char($this->setPhoneNumberValue($this->update_arr['vPhone'])).", ".gen_allow_null_char("Primary").")";
					}

					if(is_array($this->update_arr['vCell']) && count($this->update_arr['vCell'])>0) { ## Alternate
						$ins_arr[] = "(".gen_allow_null_int($this->iContactId).", ".gen_allow_null_char($this->setPhoneNumberValue($this->update_arr['vCell'])).", ".gen_allow_null_char("Alternate").")";
					}
				}
				//gen_writeDataInTmpFile($ins_arr);
				## Insert process for contact_phone table
				if(count($ins_arr)) {
					$sql_con = "INSERT INTO contact_phone (\"iCId\", \"vPhone\", \"vType\") VALUES " . implode(", ", $ins_arr);
					$sqlObj->Execute($sql_con);
					//gen_writeDataInTmpFile($sql_con);
				}
			}
			/*-------------- Log Entry -------------*/
			$this->SALObj->type = 1;
			$this->SALObj->module_name = "contact";
			$this->SALObj->action = "Update";
			$this->SALObj->audit_log_entry();
			/*-------------- Log Entry -------------*/
			return $rs_up;
		}
	}
	
	public function getContactPhoneNumbers($iCId) {
		global $sqlObj;
		$sql = "SELECT * FROM contact_phone WHERE \"iCId\"=".gen_allow_null_int($iCId);
		## Function to write query in temp file.
		//gen_writeDataInTmpFile($sql);
		return $sqlObj->GetAll($sql);
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
	
	public function getLastSRContactAddress($iCId) {
		global $sqlObj;
		//$s = 'SELECT address_mas.*, zipcode_mas."vZipcode", zipcode_mas."iStateId", zipcode_mas."iCountyId", zipcode_mas."iCityId", zone."vZoneName", zone."vDistrict",zone."vZoneId" FROM address_mas LEFT JOIN zipcode_mas ON address_mas."iZipcode" = zipcode_mas."iZipcode" LEFT JOIN zone ON address_mas."iZoneId" = zone."iZoneId" WHERE address_mas."iAddressId" = (SELECT "iAddressId" FROM service_request WHERE "iCId"=' . gen_allow_null_int($iCId) . ' ORDER BY "iSRId" DESC LIMIT 1)'; //change by bhavik desai
		$s = 'SELECT address_mas.*, zipcode_mas."vZipcode", zipcode_mas."iStateId", zipcode_mas."iCountyId", zipcode_mas."iCityId", zone."vZoneName", zone."vDistrict",zone."vZoneId" FROM site_contact LEFT JOIN site_mas ON site_contact."iSiteId" = site_mas."iSiteId" LEFT JOIN address_mas ON site_mas."iAddressId" = address_mas."iAddressId" LEFT JOIN zipcode_mas ON address_mas."iZipcode" = zipcode_mas."iZipcode" LEFT JOIN zone ON address_mas."iZoneId" = zone."iZoneId" WHERE site_contact."iCId" = '.gen_allow_null_int($iCId).' ORDER BY site_contact."iCId" DESC, site_contact."iSiteId" DESC LIMIT 1';
		$r = $sqlObj->GetAll($s);
		## Function to write query in temp file.
		//gen_writeDataInTmpFile($s);
		return $r;
	}

	function user_clear_variable(){
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
	function user_usernameFromId($iCId)
	{
		global $sqlObj;
			
		$sql = "SELECT concat(\"vFirstName\",' ', \"vLastName\" ) as vName FROM contact_mas WHERE \"iCId\"='".$iCId."' LIMIT 1";
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db[0]['vName'];		
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