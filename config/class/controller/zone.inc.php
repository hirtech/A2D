<?php
class Zone {
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
	
	function Zone(){
		
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
			
		$sql = "SELECT zone.* ".$this->join_field_str." FROM zone".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}

	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT zone.\"iZoneId\" ".$this->join_field_str." FROM zone".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//$rs_db = $sqlObj->GetAll($sql);
		//return count($rs_db);
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();
		return $count;
		
	}
	
	function delete_records(){
		global $sqlObj;
		
		$sql = "DELETE  FROM zone".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit;
		$sqlObj->Execute($sql);
		$rs_db = Affected_Rows();
		
		return $rs_db;
	}
	function action_records(){
		
		global $sqlObj;
		if($this->ids){
			if($this->action=="Active"){
				$sql = "UPDATE zone set \"iStatus\"='1',\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iZoneId\" IN (".$this->ids.")";
			}
			else if($this->action=="Inactive"){
				$sql = "UPDATE zone set \"iStatus\"='0',\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())."   WHERE \"iZoneId\" IN (".$this->ids.")";
			}
			$sqlObj->Execute($sql);
			$rs_db = Affected_Rows();
		}
		return $rs_db;
	}
	function add_records(){
		global $sqlObj, $zone_path, $zone_url;
		if($this->insert_arr){
			$file_name = $this->insert_arr['vFile'];
			$sql = "INSERT INTO zone(\"vZoneName\",\"iNetworkId\", \"vFile\", \"iStatus\", \"dAddedDate\")VALUES ('".$this->insert_arr['vZoneName']."', '".$this->insert_arr['iNetworkId']."', '".$this->insert_arr['vFile']."', '".$this->insert_arr['iStatus']."', ".gen_allow_null_char(date_getSystemDateTime()).")";
			$sqlObj->Execute($sql);
			$iZoneId = $sqlObj->Insert_ID();
			if($iZoneId){
		        $contents = file_get_contents($zone_path.$file_name);   
		        $data= xmlstring2array($contents);
		        //echo "<pre>";print_r($data);exit;
		        $ni = count($data['Document']['Placemark']);
		        if($ni > 0) {
		            $PShape =   trim($data['Document']['Placemark']['Polygon']['outerBoundaryIs']['LinearRing']['coordinates']);
		            $str1 = '';
		            $str2 = '';
		            $str3 = '';
		            $str1 = str_replace(" ","|||", $PShape);
		            $str2 = str_replace(","," ", $str1);
		            $str3 = str_replace("|||",", ", $str2);
		            /*echo $str1."\n";
		            echo $str2."\n";
		            echo $str3;exit;*/
		            //$str3 = substr($str3, 0, -1);
		            $pShape = '';
		            $pShape .= 'POLYGON((';
		            $pShape .= $str3;
		            $pShape .= '))';

		            $str4 = '';
		            $str4 = str_replace(" ","),(", $PShape);
		            $pShape1 = '';
		            $pShape1 .= '((';
		            $pShape1 .= $str4;
		            $pShape1 .= '))';
		            if($str3 != '') {
		                $rs_db = 'UPDATE zone SET "PShape"= ST_GeomFromText(\''.$pShape.'\', 4326), "PShape1"= \''.$pShape1.'\' WHERE "iZoneId"= '.$iZoneId.'';
		                $sqlObj->Execute($rs_db);
		            }
		        } 
			}
			return $iZoneId;
		}
	}

	function update_records(){
		global $sqlObj, $zone_path, $zone_url;

		if($this->update_arr){
			$file_name = $this->update_arr['vFile'];
			$iZoneId = $this->update_arr['iZoneId'];
			$rs_db = "UPDATE zone SET \"vZoneName\"='".$this->update_arr['vZoneName']."', \"iNetworkId\"='".$this->update_arr['iNetworkId'].", \"iStatus\"='".$this->update_arr['iStatus']."',\"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())."  WHERE \"iZoneId\"='".$this->update_arr['iZoneId']."' LIMIT 1";
			$sqlObj->Execute($rs_db);
			$rs_db = $sqlObj->Affected_Rows();
			if($rs_db){
		        $contents = file_get_contents($zone_path.$file_name);   
		        $data= xmlstring2array($contents);
		        //echo "<pre>";print_r($data);exit;
		        $ni = count($data['Document']['Placemark']);
		        if($ni > 0) {
		            $PShape =   trim($data['Document']['Placemark']['Polygon']['outerBoundaryIs']['LinearRing']['coordinates']);
		            $str1 = '';
		            $str2 = '';
		            $str3 = '';
		            $str1 = str_replace(" ","|||", $PShape);
		            $str2 = str_replace(","," ", $str1);
		            $str3 = str_replace("|||",", ", $str2);
		            /*echo $str1."\n";
		            echo $str2."\n";
		            echo $str3;exit;*/
		            //$str3 = substr($str3, 0, -1);
		            $pShape = '';
		            $pShape .= 'POLYGON((';
		            $pShape .= $str3;
		            $pShape .= '))';

		            $str4 = '';
		            $str4 = str_replace(" ","),(", $PShape);
		            $pShape1 = '';
		            $pShape1 .= '((';
		            $pShape1 .= $str4;
		            $pShape1 .= '))';
		            if($str3 != '') {
		                $rs_db = 'UPDATE zone SET "PShape"= ST_GeomFromText(\''.$pShape.'\', 4326), "PShape1"= \''.$pShape1.'\' WHERE "iZoneId"= '.$iZoneId.'';
		                $sqlObj->Execute($rs_db);
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

	function getZoneData()
	{
		global $sqlObj;
			
		$sql = "SELECT zone.\"iZoneId\", zone.\"vZoneName\", zone.\"iStatus\", zone.\"dAddedDate\", zone.\"dModifiedDate\" ".$this->join_field_str." FROM zone".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}

	function getZoneWithCoordinate()
	{
		global $sqlObj;
			
		$sql = "SELECT zone.\"iZoneId\", zone.\"vZoneName\",ST_AsGeoJSON(zone.\"PShape\") as PShape, zone.\"iStatus\", zone.\"dAddedDate\", zone.\"dModifiedDate\" ".$this->join_field_str." FROM zone".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
		
	}
}
?>
