<?php
include_once("security_audit_log.inc.php");
class Network {

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
	
	function CustomLayer() {
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
			
		$sql = "SELECT network.* ".$this->join_field_str." FROM network".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		$rs_db = $sqlObj->GetAll($sql);
		//echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT network.* ".$this->join_field_str." FROM network".$this->join_clause.$this->where_clause.$this->group_by_clause;
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
		
		$sql_del = "DELETE FROM network WHERE network.\"iNetworkId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);
		return $rs_del;
	}
	
	function add_records(){
		global $sqlObj, $network_path, $network_url;
		if($this->insert_arr){
			//echo"<pre>";print_r($this->insert_arr);exit;
			$sql = "INSERT INTO network(\"vName\", \"vFile\", \"iStatus\", \"dAddedDate\") VALUES (".gen_allow_null_char($this->insert_arr['vName']).", ".gen_allow_null_char($this->insert_arr['vFile']).", ".gen_allow_null_int($this->insert_arr['iStatus']).",".gen_allow_null_char(date_getSystemDateTime()).")";
			//echo $sql;exit;
			$sqlObj->Execute($sql);		
			$iNetworkId = $sqlObj->Insert_ID();
			if($iNetworkId){
				$file_name = $this->insert_arr['vFile'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				if($ext == "kmz") {
					$filename = $network_path.$file_name;
					$extract_folder = $network_path."/temp";
					$newfile = substr($filename, 0, -4).'.zip';
					$rename_file = substr($file_name, 0, -4).'.kml';
					if (copy($filename, $newfile)) {
						$zip = new ZipArchive;
						$res = $zip->open($newfile);
						if ($res === TRUE) {
							$zip->extractTo($extract_folder);
							$zip->close();
							$latest_filename = $network_path."/temp/doc.kml";
							
							$contents = utf8_encode(file_get_contents($latest_filename));
							$data12 = str_replace("°", "", $contents);
							$contents1 = utf8_encode($data12);
							$data1 = str_replace("°", "", $contents1);
							$data = xmlstring2array($data1);
							//echo "<pre>";print_r($data);exit;
						}
					}
				}else {
					$contents = file_get_contents($network_path.$file_name);
					$data= xmlstring2array($contents);
				}
		        //echo "<pre>";print_r($data);exit;
		        $ni = count($data['Document']['Placemark']);
		         if($ni > 0) {
		            $PShape =   trim($data['Document']['Placemark']['Polygon']['outerBoundaryIs']['LinearRing']['coordinates']);
		            $str1 = '';
		            $str2 = '';
		            $str3 = '';
		            $pstr = '';
		            $pstr1 = '';
		            $str4 = '';
		            if(strpos($PShape,',0') !== false){
					    $str1 = str_replace(",0","|||", $PShape);
					    $str2 = str_replace(","," ", $str1);
			            $str3 = str_replace("|||",", ", trim($str2));
			            $str3 = substr(trim($str3), 0, -1);
			            $pstr = str_replace("\n","", $PShape);
			            $pstr1 = str_replace(",0 ","),(", trim($pstr));
			            $str4 = str_replace(" ","", trim($pstr1));
			            $str4 = substr(trim($str4), 0, -2);
					} else {
					    $str1 = str_replace(" ","|||", $PShape);
			            $str2 = str_replace(","," ", $str1);
			            $str3 = str_replace("|||",", ", $str2);
			            $str4 = str_replace(" ","),(", $PShape);
					}
		            // echo $str1."==";
		            // echo $str2."==";
		            // echo $str3;exit; 
		            // echo $pstr."==";
		            // echo $str4;exit;
		            //$str3 = substr($str3, 0, -1);
		            $pShape = '';
		            $pShape .= 'POLYGON((';
		            $pShape .= $str3;
		            $pShape .= '))';

					//echo $str4;exit;
		            $pShape1 = '';
		            $pShape1 .= '((';
		            $pShape1 .= $str4;
		            $pShape1 .= '))';
		            //echo $pShape1;exit;
		            if($str3 != '') {
		               $sql_up = 'UPDATE network SET "PShape"= ST_GeomFromText(\''.$pShape.'\', 4326), "PShape1"= \''.$pShape1.'\' WHERE "iNetworkId"= '.$iNetworkId.'';
		                //echo $sql_up;exit();
		                $sqlObj->Execute($sql_up);
		            }
		        } 
			}
			return $iNetworkId;
		}
	}

	function update_records(){
		global $sqlObj, $network_path, $network_url;
		if($this->update_arr){
			$rs_db = "UPDATE network SET 
			\"vName\"=".gen_allow_null_char($this->update_arr['vName']).", 
			\"iStatus\"=".gen_allow_null_int($this->update_arr['iStatus']).", 
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())."
			WHERE \"iNetworkId\" = ".$this->update_arr['iNetworkId'];
			//echo $rs_db;exit;
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			if($rs_db){
				$file_name = $this->update_arr['vFile'];
				$iNetworkId = $this->update_arr['iNetworkId'];
				if($file_name != ''){
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					if($ext == "kmz") {
						$filename = $network_path.$file_name;
						$extract_folder = $network_path."/temp";
						$newfile = substr($filename, 0, -4).'.zip';
						$rename_file = substr($file_name, 0, -4).'.kml';
						if (copy($filename, $newfile)) {
							$zip = new ZipArchive;
							$res = $zip->open($newfile);
							if ($res === TRUE) {
								$zip->extractTo($extract_folder);
								$zip->close();
								$latest_filename = $network_path."/temp/doc.kml";
								
								$contents = utf8_encode(file_get_contents($latest_filename));
								$data12 = str_replace("°", "", $contents);
								$contents1 = utf8_encode($data12);
								$data1 = str_replace("°", "", $contents1);
								$data = xmlstring2array($data1);
								//echo "<pre>";print_r($data);exit;
							}
						}
					}else {
						$contents = file_get_contents($network_path.$file_name);
						$data= xmlstring2array($contents);
					}
			        //echo "<pre>";print_r($data);exit;
			        $ni = count($data['Document']['Placemark']);
			        if($ni > 0) {
			            $PShape =   trim($data['Document']['Placemark']['Polygon']['outerBoundaryIs']['LinearRing']['coordinates']);
			            $str1 = '';
			            $str2 = '';
			            $str3 = '';
			            $pstr = '';
			            $pstr1 = '';
			            $str4 = '';
			            if(strpos($PShape,',0') !== false){
						    $str1 = str_replace(",0","|||", $PShape);
						    $str2 = str_replace(","," ", $str1);
				            $str3 = str_replace("|||",", ", trim($str2));
				            $str3 = substr(trim($str3), 0, -1);
				            $pstr = str_replace("\n","", $PShape);
				            $pstr1 = str_replace(",0 ","),(", trim($pstr));
				            $str4 = str_replace(" ","", trim($pstr1));
				            $str4 = substr(trim($str4), 0, -2);
						} else {
						    $str1 = str_replace(" ","|||", $PShape);
				            $str2 = str_replace(","," ", $str1);
				            $str3 = str_replace("|||",", ", $str2);
				            $str4 = str_replace(" ","),(", $PShape);
						}
			            // echo $str1."==";
			            // echo $str2."==";
			            // echo $str3;exit; 
			            // echo $pstr."==";
			            // echo $str4;exit;
			            //$str3 = substr($str3, 0, -1);
			            $pShape = '';
			            $pShape .= 'POLYGON((';
			            $pShape .= $str3;
			            $pShape .= '))';

						//echo $str4;exit;
			            $pShape1 = '';
			            $pShape1 .= '((';
			            $pShape1 .= $str4;
			            $pShape1 .= '))';
			            //echo $pShape1;exit;
			            if($str3 != '') {
			                $sql_up = 'UPDATE network SET "PShape"= ST_GeomFromText(\''.$pShape.'\', 4326), "PShape1"= \''.$pShape1.'\' WHERE "iNetworkId"= '.$iNetworkId.'';
			                //echo $sql_up;exit();
			                $sqlObj->Execute($sql_up);
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

}