<?php
include_once("security_audit_log.inc.php");
class Site {
	var $iPremiseId;
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
	
	function Site(){
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
			if(!empty($this->param['group_by']))
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
		$sql = "SELECT s.* " . $this->join_field_str . " FROM \"premise_mas\" s" . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		//echo $sql;exit();
		//file_put_contents($site_path."logs/a.txt", $sql);
		## Function to write query in temp file.
		//gen_writeDataInTmpFile($sql);
		
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}

	function recordset_list_for_zone() {
		global $sqlObj;
		
		$sql = "SELECT s.* " . $this->join_field_str . " FROM \"premise_mas\" s" . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		//file_put_contents($site_path."logs/a.txt", $sql);
		## Function to write query in temp file.
		//gen_writeDataInTmpFile($sql);
		
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}

	function recordset_total() {
		global $sqlObj;
			
		$sql = "SELECT s.* " . $this->join_field_str . " FROM premise_mas s" . $this->join_clause . $this->where_clause . $this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false) $count = 0;
		else $count = $rs_db->RecordCount();
		//return count($rs_db);
		return $count;
	}

	function delete_records(){
		//echo "<pre>";print_r($_POST);exit;
		global $sqlObj;
		$sql = "DELETE FROM premise_mas" . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		//echo $sql;exit;
		$sqlObj->Execute($sql);
		$rs_del =$sqlObj->Affected_Rows();

        $sql = "DELETE FROM site_attribute WHERE \"iPremiseId\" IN (" . $_POST['iPremiseId'] . ")";
        $sqlObj->Execute($sql);

        $sql = "DELETE FROM site_contact WHERE \"iPremiseId\" IN (" . $_POST['iPremiseId'] . ")";
        $sqlObj->Execute($sql);

		/*-------------- Log Entry -------------*/
		$this->SALObj->type = 2;
		$this->SALObj->module_name = "Site";
		$this->SALObj->audit_log_entry();
		/*-------------- Log Entry -------------*/

		return $rs_del;
	}
	
	function delete_single_record($iPremiseId) {
        global $sqlObj;
       
        $sql = "DELETE FROM premise_mas WHERE \"iPremiseId\" = ".$iPremiseId;
        $rs_del = $sqlObj->Execute($sql);

        $sql = "DELETE FROM site_attribute WHERE \"iPremiseId\" = ".$iPremiseId;
        $sqlObj->Execute($sql);

        $sql = "DELETE FROM site_contact WHERE \"iPremiseId\" = ".$iPremiseId;
        $sqlObj->Execute($sql);

		return $rs_del;

    }

	function add_records() {
		global $sqlObj, $admin_panel_session_suffix;
		
		if($this->insert_arr) {
			//echo "<pre>";print_r($this->insert_arr);exit();
			$iGeometryType 		= $this->insert_arr['iGeometryType'];
			$vLatitude 			= $this->insert_arr['vLatitude'];
			$vLongitude 		= $this->insert_arr['vLongitude'];
			$vNewLatitude 		= $this->insert_arr['vNewLatitude'];
			$vNewLongitude 		= $this->insert_arr['vNewLongitude'];

			$vPointLatLong = gen_allow_null_char('');

			if($this->insert_arr['pointlatlong'] != ""){
				$search_arr  = ["POINT(", ")"];
				$replace_arr  = ["", ""];
				$new_polygonstr = str_replace($search_arr, $replace_arr, $this->insert_arr['pointlatlong']);
				$point_arr = explode(" ", $new_polygonstr);
				$vLongitude = $point_arr[0];
				$vLatitude = $point_arr[1];
			}

			if($vNewLatitude != '' && $vNewLongitude != ''){
				$vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT('.$vNewLongitude.' '.$vNewLatitude .')\', 4326)';
			}else if($vLatitude != '' && $vLongitude != ''){
				$vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT('.$vLongitude.' '.$vLatitude.')\', 4326)';
			} 

			$iStatus = $this->insert_arr['iStatus'];
			if($this->insert_arr['iZoneId'] > 0){
				$sql_zone = "SELECT \"iNetworkId\" FROM zone WHERE \"iZoneId\" = '".$this->insert_arr['iZoneId']."' AND \"iNetworkId\" > 0 LIMIT 1";
				$rs_zone = $sqlObj->GetAll($sql_zone);
				if(!empty($rs_zone)) {
					$iStatus = 2; // Near-Net
				}
			}

			$sql_ins = 'INSERT INTO premise_mas ("vName",  "iSTypeId", "iSSTypeId",  "vAddress1", "vAddress2", "vStreet", "vCrossStreet", "iZipcode", "iGeometryType", "iZoneId", "vLatitude", "vLongitude", "vNewLatitude", "vNewLongitude", "vPointLatLong", "dAddedDate", "iStatus", "vLoginUserName", "iStateId", "iCountyId", "iCityId") VALUES ('.gen_allow_null_char($this->insert_arr['vName']).', '.gen_allow_null_int($this->insert_arr['iSTypeId']).', '.gen_allow_null_int($this->insert_arr['iSSTypeId']).', '.gen_allow_null_char($this->insert_arr['vAddress1']).', '.gen_allow_null_char($this->insert_arr['vAddress2']).', '.gen_allow_null_char($this->insert_arr['vStreet']).', '.gen_allow_null_char($this->insert_arr['vCrossStreet']).', '.gen_allow_null_int($this->insert_arr['iZipcode']).', '.gen_allow_null_int($this->insert_arr['iGeometryType']).', '.gen_allow_null_int($this->insert_arr['iZoneId']).', '.gen_allow_null_char($vLatitude).', '.gen_allow_null_char($vLongitude).', '.gen_allow_null_char($vNewLatitude).', '.gen_allow_null_char($vNewLongitude).', '.$vPointLatLong.', '.gen_allow_null_char(date_getSystemDateTime()).', '.gen_allow_null_int($iStatus).', '.gen_allow_null_char($this->insert_arr['vLoginUserName']).', '.gen_allow_null_char($this->insert_arr['iStateId']).', '.gen_allow_null_char($this->insert_arr['iCountyId']).', '.gen_allow_null_char($this->insert_arr['iCityId']).')';
		    //echo $sql_ins;exit();
			$sqlObj->Execute($sql_ins);
			$iPremiseId = $sqlObj->Insert_ID();
			//echo $iPremiseId;exit();
	 		if($iPremiseId){
	 			$iSAttributeIds = $this->insert_arr['iSAttributeId'];
	 			if(count($iSAttributeIds) >0){
	 				$attr_array = array();
	 				for($i=0;$i<count($iSAttributeIds);$i++){
	 					$attr_array[] = "(".$iPremiseId.",".gen_allow_null_int($iSAttributeIds[$i]).",".gen_allow_null_char($this->insert_arr['vLoginUserName']).', '.gen_allow_null_char(date_getSystemDateTime()).', '.gen_allow_null_char(date_getSystemDateTime()).")";
	 				}
	 				if(count($attr_array) > 0){
	 					$sql_site_attr = 'INSERT INTO site_attribute ("iPremiseId","iSAttributeId","vLoginUserName","dAddedDate","dModifiedDate") VALUES '.implode(",", $attr_array).'';
	 					$sqlObj->Execute($sql_site_attr);
	 				}
	 			}

	 		}

			return $iPremiseId;
		}
	}

	function update_records() {
		global $sqlObj, $admin_panel_session_suffix;
		
		if($this->update_arr) {
			//echo "<pre>";print_r($this->update_arr);exit();
			$iGeometryType 		= $this->update_arr['iGeometryType'];
			$vLatitude 			= $this->update_arr['vLatitude'];
			$vLongitude 		= $this->update_arr['vLongitude'];
			$vNewLatitude 		= $this->update_arr['vNewLatitude'];
			$vNewLongitude 		= $this->update_arr['vNewLongitude'];

			$vPointLatLong = gen_allow_null_char('');

			if($this->update_arr['pointlatlong'] != ""){
				$search_arr  = ["POINT(", ")"];
				$replace_arr  = ["", ""];
				$new_polygonstr = str_replace($search_arr, $replace_arr, $this->update_arr['pointlatlong']);
				$point_arr = explode(" ", $new_polygonstr);
				$vLongitude = $point_arr[0];
				$vLatitude = $point_arr[1];
			}

			if($vNewLatitude != '' && $vNewLongitude != ''){
				$vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT('.$this->update_arr['vNewLongitude'].' '.$this->update_arr['vNewLatitude'] .')\', 4326)';
			}else if($vLatitude != '' && $vLongitude != ''){
				$vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT('.$vLongitude.' '.$vLatitude .')\', 4326)';
			}

			$iStatus = $this->update_arr['iStatus'];
			/*if($this->update_arr['iZoneId'] > 0){
				$sql_zone = "SELECT \"iNetworkId\" FROM zone WHERE \"iZoneId\" = '".$this->update_arr['iZoneId']."' AND \"iNetworkId\" > 0 LIMIT 1";
				$rs_zone = $sqlObj->GetAll($sql_zone);
				if(!empty($rs_zone)) {
					$iStatus = 2; // Near-Net
				}
			}*/

			$sql_updt = 'UPDATE premise_mas set "vName" = '.gen_allow_null_char($this->update_arr['vName']).', "iSTypeId" = '.gen_allow_null_int($this->update_arr['iSTypeId']).', "iSSTypeId" = '.gen_allow_null_int($this->update_arr['iSSTypeId']).', "vAddress1" = '.gen_allow_null_char($this->update_arr['vAddress1']).', "vAddress2" = '.gen_allow_null_char($this->update_arr['vAddress2']).', "vStreet" = '.gen_allow_null_char($this->update_arr['vStreet']).', "vCrossStreet" = '.gen_allow_null_char($this->update_arr['vCrossStreet']).', "iZipcode" = '.gen_allow_null_int($this->update_arr['iZipcode']).', "iGeometryType" = '.gen_allow_null_int($this->update_arr['iGeometryType']).', "iZoneId" = '.gen_allow_null_int($this->update_arr['iZoneId']).', "vLatitude" = '.gen_allow_null_char($vLatitude).', "vLongitude" = '.gen_allow_null_char($vLongitude).', "vNewLatitude" = '.gen_allow_null_char($vNewLatitude).', "vNewLongitude" = '.gen_allow_null_char($vNewLongitude).', "vPointLatLong" = '.$vPointLatLong.', "dModifiedDate" = '.gen_allow_null_char(date_getSystemDateTime()).', "iStatus" = '.gen_allow_null_int($iStatus).', "iStateId" = '.gen_allow_null_char($this->update_arr['iStateId']).', "iCountyId" = '.gen_allow_null_char($this->update_arr['iCountyId']).', "iCityId" = '.gen_allow_null_char($this->update_arr['iCityId']).' WHERE "iPremiseId" = '.$this->update_arr['iPremiseId'].'';
			//echo $sql_updt;exit();
			$rs_up = $sqlObj->Execute($sql_updt);

			//echo $iPremiseId;exit();
			$iPremiseId = $this->update_arr['iPremiseId'];
	 		if($rs_up){
	 			$iSAttributeIds = isset($this->update_arr['iSAttributeId'])?$this->update_arr['iSAttributeId']:array();
	 			//site contact
	 			$sql_con = 'SELECT "iSAttributeId" From site_attribute WHERE "iPremiseId" = '.gen_allow_null_int($iPremiseId);
	 			$rs_sa = $sqlObj->GetAll($sql_con);
	 			$sa_id = $sa_del = array();

	 			if(!empty($rs_sa)){
	 				$sa_id = array_column($rs_sa, 'iSAttributeId');
	 				$sa_del = array_diff($sa_id,$iSAttributeIds);
	 			}
	 			if(!empty($sa_del)){
	 				$sql_del = 'DELETE FROM site_attribute WHERE "iPremiseId" = '.gen_allow_null_int($iPremiseId) .' and "iSAttributeId" IN ('.implode(",",$sa_del).')';
					$sqlObj->Execute($sql_del);

 				}
 				$cnt_Sa=count($iSAttributeIds);
	 			if($cnt_Sa >0){
	 				$attr_array =array();
	 				for($i=0;$i<$cnt_Sa;$i++){
	 					if(!in_array($iSAttributeIds[$i],$sa_id)){
	 						$attr_array[] = "(".$iPremiseId.",".gen_allow_null_int($iSAttributeIds[$i]).",".gen_allow_null_char($this->update_arr['vLoginUserName']).",".gen_allow_null_char(date_getSystemDateTime()).",".gen_allow_null_char(date_getSystemDateTime()).")";
	 					}
	 				}
	 				if(count($attr_array) > 0){
	 					$sql_site_attr = 'INSERT INTO site_attribute ("iPremiseId","iSAttributeId","vLoginUserName","dAddedDate","dModifiedDate") VALUES '.implode(",", $attr_array).'';
	 					$sqlObj->Execute($sql_site_attr);
	 				}
	 			}

	 			//site contact
	 			$sql_con = 'SELECT "iCId" From site_contact WHERE "iPremiseId" = '.gen_allow_null_int($iPremiseId);
	 			$rs_con = $sqlObj->GetAll($sql_con);

	 			$con_id = $con_del = array();
				$iCId_arr = isset($this->update_arr['iCId'])?$this->update_arr['iCId']:array();

	 			if(!empty($rs_con)){
	 				$con_id = array_column($rs_con, 'iCId');
	 				$con_del = array_diff($con_id,$iCId_arr);
	 			}

 				if(!empty($con_del)){
	 				$sql_del = 'DELETE FROM site_contact WHERE "iPremiseId" = '.gen_allow_null_int($iPremiseId) .' and "iCId" IN ('.implode(",",$con_del).')';
					$sqlObj->Execute($sql_del);

 				}
				
				$ci = count($iCId_arr);
				if($ci > 0){
					$cont_arr  = array();
					for($c=0;$c<$ci;$c++){
						if(!in_array($iCId_arr[$c],$con_id)){
							$cont_arr[] = '('.gen_allow_null_int($iPremiseId).', '.gen_allow_null_int($iCId_arr[$c]).', '.gen_allow_null_char($this->update_arr['vLoginUserName']).','.gen_allow_null_char(date_getSystemDateTime()).','.gen_allow_null_char(date_getSystemDateTime()).') ';
						}
					}
					if(count($cont_arr) > 0){
	 					$sql_sc = 'INSERT INTO site_contact ("iPremiseId", "iCId", "vLoginUserName","dAddedDate","dModifiedDate") VALUES '.implode(",", $cont_arr).'';
	 					$sqlObj->Execute($sql_sc);
	 				}

				}
	 		}

			return $rs_up;
		}
	}

	function edit_batch_records() {
		global $sqlObj, $admin_panel_session_suffix;

		$query_arr = array();
		$query_str = "";
		if($this->update_arr) {
			if($this->update_arr['iSTypeId'] != ''){
				$query_arr[] = '"iSTypeId" = '.gen_allow_null_int($this->update_arr['iSTypeId']);
			}
			if($this->update_arr['iSSTypeId'] != ''){
				$query_arr[] = '"iSSTypeId" = '.gen_allow_null_int($this->update_arr['iSSTypeId']);
			}
			if($this->update_arr['iStatus'] != ''){
				$query_arr[] = '"iStatus" = '.gen_allow_null_int($this->update_arr['iStatus']);
			}
			if(!empty($query_arr)){
				$query_str = implode(",", $query_arr);
				$sql_updt = 'UPDATE premise_mas set '.$query_str.' WHERE "iPremiseId" IN ('.$this->update_arr['iPremiseId'].')';
				//echo $sql_updt;exit();
				$rs_up = $sqlObj->Execute($sql_updt);
				return $rs_up;
			}
			else return "";
		}
	}

	function site_attribute_list(){
		global $sqlObj;
		$sql = "SELECT site_attribute.* " . $this->join_field_str . " FROM \"site_attribute\" " . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	function site_contact_list(){
		global $sqlObj;
		$sql = "SELECT site_contact.* " . $this->join_field_str . " FROM \"site_contact\" " . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}
	
	## Function will retun zipcode id according its state, country and city id. If not exist will insert it and will send newly inserted id...
	private function getZipcodeId($vZipcode, $iStateId, $iCountyId, $iCityId) {
		global $sqlObj;
		$sql = 'SELECT "iZipcode" FROM "zipcode_mas" WHERE "vZipcode"=\''.$vZipcode.'\' AND "iStateId"=\''.$iStateId.'\' AND "iCountyId"=\''.$iCountyId.'\' AND "iCityId"=\''.$iCityId.'\' LIMIT 1';
		$rs = $sqlObj->GetAll($sql);
		$cnt_c = count($rs);
		if($cnt_c > 0) return $rs[0]['iZipcode'];
		else {
			$sql_in = 'INSERT INTO "zipcode_mas" ("vZipcode", "iStateId", "iCountyId", "iCityId") VALUES ('.gen_allow_null_char($vZipcode).', '.gen_allow_null_int($iStateId).', '.gen_allow_null_int($iCountyId).', '.gen_allow_null_int($iCityId).')';
			$sqlObj->Execute($sql_in);
			return $sqlObj->Insert_ID();
		}
	}
	
	public function getContactPhoneNumbers($iCId) {
		global $sqlObj;
		$sql = "SELECT * FROM contact_phone WHERE \"iCId\"=".$iCId;
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
	
	function add_document() {
		global $sqlObj, $admin_panel_session_suffix;
		if($this->insert_arr) {
			$sql = 'INSERT INTO site_documents ("iPremiseId", "vTitle", "vFile", "dAddedDate", "vLoginUserName") VALUES ('.gen_allow_null_char($this->insert_arr['iPremiseId']).', '.gen_allow_null_char($this->insert_arr['vTitle']).', '.gen_allow_null_char($this->insert_arr['vFile']).', '.gen_allow_null_char(date_getSystemDateTime()).', '.gen_allow_null_char($this->insert_arr['vLoginUserName']).')';
			//echo $sql;exit();
			$sqlObj->Execute($sql);
			$iSDId = $sqlObj->Insert_ID();
			return $iSDId;			
		}
	}

	function get_site_document_list() {
		global $sqlObj;
		
		$sql = 'SELECT * ' . $this->join_field_str . ' FROM site_documents' . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
		return $sqlObj->GetAll($sql);
	}

	function delete_site_document(){
		global $sqlObj;
		
		if($this->ids){
			$sql = 'DELETE FROM site_documents WHERE "iSDId" = '.$this->ids;
			$sqlObj->Execute($sql);
		}
		
		$rs_db =$sqlObj->Affected_Rows();
		return $rs_db; 
	}

	function add_multiple_site_records(){
		
		global $sqlObj, $GOOGLE_GEOCODE_API_KEY, $admin_panel_session_suffix;
		//echo "<pre>";print_r($this->insert_arr);exit;
		$search_arr = array("(", ")");
		$replace_arr = array("", "");

		$latlong = str_replace($search_arr, $replace_arr, $this->insert_arr['latlong']);
		$iSTypeId = $this->insert_arr['iSTypeId'];
		$iSSTypeId = $this->insert_arr['iSSTypeId'];
		$vLoginUserName = $this->insert_arr['vLoginUserName'];
		//echo $latlong;exit;
		if($latlong){
			$latlong_arr = explode("##", $latlong);
			$ni = count($latlong_arr);
			//echo $ni;exit;
			//echo "<pre>";print_r($latlong_arr);exit;
			$site_arr = array();
			if($ni > 0){
				for ($i=0; $i < $ni; $i++) { 
					$arr = explode(", ", $latlong_arr[$i]);
					$vLatitude = trim($arr[0]);
					$vLongitude = trim($arr[1]);
					if($vLatitude != "" && $vLongitude != ""){
						$url = "https://maps.googleapis.com/maps/api/geocode/json?key=$GOOGLE_GEOCODE_API_KEY&latlng=".$vLatitude.",".$vLongitude."&sensor=true";
						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL,$url);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
						curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
						$data = curl_exec($ch);
						curl_close($ch);
						$jsondata = json_decode($data,true);
						//echo "<pre>";print_r($data);exit;
						$address_arr = array();
						if(is_array($jsondata) && $jsondata['status'] == "OK"){					
							foreach($jsondata['results']['0']['address_components'] as $element){
								$address_arr[$element['types'][0]]['short_name'] = $element['short_name'];
								$address_arr[$element['types'][0]]['long_name'] = $element['long_name'];
							}
						}
						
						$vAddress1 = "";
						$vStreet = "";
						$vCrossStreet = "";
						$vCity = "";
						$vCounty = "";
						$vStateCode = "";
						$vState = "";
						$vCountry = "";
						$vZipcode = 0;
						//echo "<pre>";print_r($address_arr);exit;
						
						if(count($address_arr) > 0){
							
							$sql_state = 'SELECT "iStateId" FROM state_mas WHERE "vStateCode" = '.gen_allow_null_char($address_arr['administrative_area_level_1']['short_name']);
							$rs_state = $sqlObj->GetAll($sql_state);
							
							$sql="SELECT fun_getZoneIdFromZoneBoundary(ST_GeometryFromText('POINT(".$vLongitude." ".$vLatitude.")', 4326)::geometry) as iZoneId;";
							$rs_zone=$sqlObj->GetAll($sql);
							
							$vAddress1 = $address_arr['street_number']['short_name'];
							$vStreet = $address_arr['route']['long_name'];
							$vCrossStreet = $address_arr['neighborhood']['long_name'];
							$iStateId = $rs_state[0]['iStateId'];
							$izoneid = $rs_zone[0]['izoneid'];
							$vZipcode = $address_arr['postal_code']['short_name'];
							$vCity = $address_arr['locality']['long_name'];
							$vCounty = trim(str_replace("County", "", $address_arr['administrative_area_level_2']['long_name']));
							$vStateCode = $address_arr['administrative_area_level_1']['short_name'];
							$vState = $address_arr['administrative_area_level_1']['long_name'];
							$vCountry = $address_arr['country']['long_name'];
							$vCountryCode = $address_arr['country']['short_name'];

							$sql_county = 'SELECT "iCountyId" FROM county_mas WHERE "vCounty" = '.gen_allow_null_char($vCounty);
							$rs_county = $sqlObj->GetAll($sql_county);
							if($rs_county)
								$iCountyId = $rs_county[0]['iCountyId'];
							
							$sql_city = 'SELECT "iCityId" FROM city_mas WHERE "vCity" = '.gen_allow_null_char($vCity);
							$rs_city = $sqlObj->GetAll($sql_city);
							if($rs_city)
								$iCityId = $rs_city[0]['iCityId'];

							if($vZipcode != "" && $iStateId != "" && $iCountyId != "" && $iCityId != "") {
								$iZipcode = $this->getZipcodeId($vZipcode, $iStateId, $iCountyId, $iCityId);
							}	

							$vName = $vAddress1." ".$vStreet;	
							$iGeometryType = 1;
							$iStatus = 1;
							
							$sql_ins = 'INSERT INTO premise_mas ("vName",  "iSTypeId", "iSSTypeId",  "vAddress1", "vAddress2", "vStreet", "vCrossStreet", "iZipcode", "iGeometryType", "iZoneId", "vLatitude", "vLongitude", "vPointLatLong", "dAddedDate",  "vLoginUserName", "iStatus","iStateId", "iCountyId", "iCityId") VALUES ('.gen_allow_null_char($vName).', '.gen_allow_null_int($iSTypeId).', '.gen_allow_null_int($iSSTypeId).', '.gen_allow_null_char($vAddress1).', '.gen_allow_null_char($vAddress2).', '.gen_allow_null_char($vStreet).', '.gen_allow_null_char($vCrossStreet).', '.gen_allow_null_int($iZipcode).', '.gen_allow_null_int($iGeometryType).', '.gen_allow_null_int($iZoneId).', '.gen_allow_null_char($vLatitude).', '.gen_allow_null_char($vLongitude).', ST_GEOMFROMTEXT(\'POINT('.$vLongitude.' '.$vLatitude .')\', 4326), '.gen_allow_null_char(date_getSystemDateTime()).', '.gen_allow_null_char($vLoginUserName).', '.gen_allow_null_int($iStatus).', '.gen_allow_null_int($iStateId).', '.gen_allow_null_int($iCountyId).', '.gen_allow_null_int($iCityId).')';
						    //echo $sql_ins."\n";
							$rs_site = $sqlObj->Execute($sql_ins);
							if($rs_site){
								$site_arr[] = $sqlObj->Insert_ID();
					 			$iSAttributeIds = $this->insert_arr['iSAttributeId'];
					 			if(count($iSAttributeIds) >0){
					 				$attr_array = array();
					 				for($ai=0;$ai<count($iSAttributeIds);$ai++){
					 					$attr_array[] = "(".$iPremiseId.",".gen_allow_null_int($iSAttributeIds[$ai]).",".gen_allow_null_char($vLoginUserName).', '.gen_allow_null_char(date_getSystemDateTime()).', '.gen_allow_null_char(date_getSystemDateTime()).")";
					 				}
					 				if(count($attr_array) > 0){
					 					$sql_site_attr = 'INSERT INTO site_attribute ("iPremiseId","iSAttributeId","vLoginUserName","dAddedDate","dModifiedDate") VALUES '.implode(",", $attr_array).'';
					 					//echo $sql_site_attr."\n";
					 					$sqlObj->Execute($sql_site_attr);
					 				}
					 			}

								
							}
						}
					}
				}	

			}
		}

		return $site_arr;
	}

	function site_history_list($iPremiseId = 0) {
		//echo "111";exit();
		global $sqlObj;
		$arr = array();

		// Awareness
		$sql = 'SELECT awareness.*, \'Awareness\' AS "Type"' . $this->join_field_str . " FROM awareness " . $this->join_clause .' WHERE "iPremiseId" = '.$iPremiseId.' '.$this->group_by_clause . 'order by "dDate" desc'. $this->limit_clause;
		//echo $sql;
		$rs_st = $sqlObj->GetAll($sql);
		$ni = count($rs_st);
		if($ni > 0){
			for($i=0;$i<$ni;$i++){
				if($rs_st[$i]['dDate'] != ""){
					$arr[strtotime($rs_st[$i]['dDate'])][] = $rs_st[$i];
				}
			}
		}

		// Fiberinquiry Details
		$sql = 'SELECT fiberinquiry_details	.*, \'FiberInquiry\' AS "Type"' . $this->join_field_str . " FROM fiberinquiry_details " . $this->join_clause.' WHERE "iMatchingPremiseId" = '.$iPremiseId.' '.$this->group_by_clause .'order by "dAddedDate" desc'. $this->limit_clause;
		//echo $sql;exit;
		$rs_st = $sqlObj->GetAll($sql);
		$ni = count($rs_st);
		if($ni > 0){
			for($i=0;$i<$ni;$i++){
				if($rs_st[$i]['dAddedDate'] !=  "")
					$arr[strtotime($rs_st[$i]['dAddedDate'])][] = $rs_st[$i];
			}
		}

		// Service Order
		$sql = 'SELECT service_order.*, \'ServiceOrder\' AS "Type"' . $this->join_field_str . " FROM service_order " . $this->join_clause.' WHERE "iPremiseId" = '.$iPremiseId.' '.$this->group_by_clause .'order by "dAddedDate" desc'. $this->limit_clause;
		$rs_st = $sqlObj->GetAll($sql);

		$ni = count($rs_st);
		if($ni > 0){
			for($i=0;$i<$ni;$i++){
				if($rs_st[$i]['dAddedDate'] !=  "")
					$arr[strtotime($rs_st[$i]['dAddedDate'])][] = $rs_st[$i];
			}
		}

		// Work Order
		$sql = 'SELECT workorder.*, \'WorkOrder\' AS "Type"' . $this->join_field_str . " FROM workorder " . $this->join_clause.' WHERE "iPremiseId" = '.$iPremiseId.' '.$this->group_by_clause .'order by "dAddedDate" desc'. $this->limit_clause;
		$rs_st = $sqlObj->GetAll($sql);

		$ni = count($rs_st);
		if($ni > 0){
			for($i=0;$i<$ni;$i++){
				if($rs_st[$i]['dAddedDate'] !=  "")
					$arr[strtotime($rs_st[$i]['dAddedDate'])][] = $rs_st[$i];
			}
		}

		// Trouble Ticket
		$sql = 'SELECT trouble_ticket_premise.*, \'TroubleTicket\' AS "Type" FROM trouble_ticket_premise LEFT JOIN trouble_ticket tt on trouble_ticket_premise."iTroubleTicketId" = tt."iTroubleTicketId" WHERE "iPremiseId" = '.$iPremiseId.' '.$this->group_by_clause .'order by tt."dAddedDate" desc'. $this->limit_clause;
		$rs_st = $sqlObj->GetAll($sql);

		$ni = count($rs_st);
		if($ni > 0){
			for($i=0;$i<$ni;$i++){
				if($rs_st[$i]['dAddedDate'] !=  "")
					$arr[strtotime($rs_st[$i]['dAddedDate'])][] = $rs_st[$i];
			}
		}

		// Maintainance Ticket
		$sql = 'SELECT maintenance_ticket_premise.*, \'MaintainanceTicket\' AS "Type" FROM maintenance_ticket_premise LEFT JOIN maintenance_ticket mt on maintenance_ticket_premise."iMaintenanceTicketId" = mt."iMaintenanceTicketId" WHERE "iPremiseId" = '.$iPremiseId.' '.$this->group_by_clause .'order by mt."dAddedDate" desc'. $this->limit_clause;
		$rs_st = $sqlObj->GetAll($sql);

		$ni = count($rs_st);
		if($ni > 0){
			for($i=0;$i<$ni;$i++){
				if($rs_st[$i]['dAddedDate'] !=  "")
					$arr[strtotime($rs_st[$i]['dAddedDate'])][] = $rs_st[$i];
			}
		}

		krsort($arr);
		//echo "<pre>";print_r($arr);exit;
		$operation_arr = array();

		$ai = count($arr);
		if($ai > 0){
			foreach($arr as $key=>$val_arr){
				if(is_array($val_arr)){
					$vi = count($val_arr);
					if($vi > 0){
						for($v=0;$v<$vi;$v++){
							$operation_arr[] = $val_arr[$v];
						}
					}
				}
			}
		}
		
		return $operation_arr;
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

	function add_site_contact() {
		global $sqlObj;
		if($this->insert_arr) {
			$sql = 'INSERT INTO site_contact ("iPremiseId", "iCId", "vLoginUserName","dAddedDate") VALUES ('.gen_allow_null_char($this->insert_arr['iPremiseId']).', '.gen_allow_null_char($this->insert_arr['iCId']).', '.gen_allow_null_char($this->insert_arr['vLoginUserName']).', '.gen_allow_null_char(date_getSystemDateTime()).')';
			
			$sqlObj->Execute($sql);
			$iSCId = $sqlObj->Insert_ID();
			return $iSCId;			
		}
	}

	function delete_site_contact(){
		global $sqlObj;
		
		if($this->ids){
			$sql = 'DELETE FROM site_contact WHERE "iSCId" = '.$this->ids;
			$sqlObj->Execute($sql);
		}
		
		$rs_db =$sqlObj->Affected_Rows();
		return $rs_db; 
	}

	function update_delete_site(){
		global $sqlObj;
		
		if($this->ids){
			$sql = 'UPDATE premise_mas SET "dModifiedDate" = '.gen_allow_null_char(date_getSystemDateTime()).', "dDeletedDate" = '.gen_allow_null_char(date_getSystemDateTime()).', "iStatus" =  2  WHERE "iPremiseId" = '.$this->ids.'';
			$rs_up =$sqlObj->Execute($sql);
		}
		
		return $rs_up;
	}
}
?>