<?php

//echo "<pre>";print_R($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Custom Layer", 'Edit');

} else {
    per_hasModuleAccess("Custom Layer", 'Add');
}

include_once($controller_path . "custom_layer.inc.php");

$CustomLayerObj = new CustomLayer();

$country_id = $_SESSION["sess_iCountySaasId" . $admin_panel_session_suffix];

if($mode == "GeoEdit"){
	$iCLId = $_REQUEST['iCLId'];
	$CustomLayerObj->clear_variable();
	$join_fieds_arr = array();
	$join_arr  = array();
	$where_arr  = array();
	$where_arr[] = '"iCLId" = '.$iCLId.'';
	$CustomLayerObj->join_field = $join_fieds_arr;
	$CustomLayerObj->join = $join_arr;
	$CustomLayerObj->where = $where_arr;
	$CustomLayerObj->param['limit'] = ' LIMIT 1 ';
	$CustomLayerObj->setClause();
	$rs_data = $CustomLayerObj->recordset_list();

	$vName = $rs_data[0]['vName'];
	//echo "<pre>";print_R($rs_data);exit;
}else if($mode == "kml_layer") {
	
	$iCLId = $_REQUEST['iCLId'];

	$CustomLayerObj->clear_variable();
	$join_fieds_arr = array();
	$join_arr  = array();
	$where_arr  = array();
	$where_arr[] = '"iCLId" = '.$iCLId.'';
	$CustomLayerObj->join_field = $join_fieds_arr;
	$CustomLayerObj->join = $join_arr;
	$CustomLayerObj->where = $where_arr;
	$CustomLayerObj->param['limit'] = ' LIMIT 1 ';
	$CustomLayerObj->setClause();
	$res_sql = $CustomLayerObj->recordset_list();

    $kml_arr = array();
    $file_name = "";
    $filepath = $custom_layer_path.$country_id."/";
	if(count($res_sql) > 0){
		if(file_exists($filepath.$res_sql[0]['vFile'])){
			$file_name = $custom_layer_url.$country_id."/".$res_sql[0]['vFile'];
			##TRUNCATE custom_layer_temp_data
			$del_mas_sql = "TRUNCATE custom_layer_temp_data RESTART IDENTITY CASCADE";
			$rs_del_mas_sql = $sqlObj->Execute($del_mas_sql);

			$sql = 'ALTER SEQUENCE "public"."custom_layer_temp_data_iCLTmpId_seq" RESTART WITH 1';
			$sqlObj->Execute($sql);
			

			header("Content-Type: text/plain");
			$contents = file_get_contents($filepath.$res_sql[0]['vFile']);	
			//echo $contents;exit;
			$xml_data = explode("<coordinates>", $contents);
			//echo "<pre>";print_r($xml_data);exit;
			$ni = count($xml_data);

			$sql_chk = "SELECT * FROM custom_layer_temp_data WHERE \"iCLId\" = '".$res_sql[0]['iCLId']."'";
			$rs_chk = $sqlObj->GetAll($sql_chk);
			$iTempId = 0;
			if($ni > 0 && count($rs_chk) == 0) {
				for($i=0;$i<$ni;$i++) {
					$vOldStr = $xml_data[$i];
					if (strpos($vOldStr, ', 0') !== false) {
						$vOldStr = str_replace(', 0', ' ', $vOldStr);
					}
					if (strpos($vOldStr, ',0') !== false) {
						$vOldStr = str_replace(',0', ' ', $vOldStr);
					}
					if (strpos($vOldStr, '</coordinates>') !== false) {
						$iTempId++;
					}
					//echo trim($vOldStr)."\n";
					if(count($rs_chk) == 0){
						$sql_ins = "INSERT INTO custom_layer_temp_data(\"iCLId\", \"vOldStr\", \"iTempId\") VALUES('".$res_sql[0]['iCLId']."', '".trim($vOldStr)."', '".$iTempId."')";
						//echo $sql_ins;
						$sqlObj->Execute($sql_ins);
					}
				}
			}//exit;

			## get pShape
			$sql_kml_temp = "SELECT * FROM custom_layer_temp_data WHERE \"iCLId\" = '".$res_sql[0]['iCLId']."' ORDER BY \"iCLTmpId\"";
			$res_sql_temp = $sqlObj->GetAll($sql_kml_temp);
			//echo "<pre>";print_r($res_sql_temp);exit;
			$cnt = count($res_sql_temp);

			$str1 = '';
			$str2 = '';
			$str3 = '';
			
			$kml_arr = array();
			$ind = 0;
			if($cnt > 0) {

				for($i=0; $i<$cnt; $i++){
					$iCLTmpId = $res_sql_temp[$i]['iCLTmpId'];
					$vOldStr = $res_sql_temp[$i]['vOldStr'];
					$vNewStr = $res_sql_temp[$i]['vNewStr'];
					$vStr = '';
					if($vNewStr != '')
						$vStr = $vNewStr;
					else 
						$vStr = $vOldStr;

					$iTempId = $res_sql_temp[$i]['iTempId'];
					$vColorArr = explode("<color>",$vStr);
					$vColorArr1 = explode("</color>",$vColorArr[1]);
					$vColor = $vColorArr1[0];
					$PShape = '';
					if($iTempId > 0){
						$vOldStrArr = explode("</coordinates>",$vStr);
						$vOldStrArr[0] = preg_replace("/\r\n|\r|\n/",' ',$vOldStrArr[0]);
						$vOldStrArr[0] = preg_replace('/\s+/', ' ', $vOldStrArr[0]);
						
						$str1 = str_replace(" ","|||", trim($vOldStrArr[0]));
						//echo $str1;exit;
						$str2 = str_replace(","," ", trim($str1));
						$str3 = str_replace("|||",",", $str2);			
						//echo $str3;exit;
						$PShape .= $str3 . "#";					
						$kml_arr[$ind]['iCLTmpId'] = $res_sql_temp[$i]['iCLTmpId']; 
						$kml_arr[$ind]['iCLId'] = $res_sql_temp[$i]['iCLId']; 
						$kml_arr[$ind]['iTempId'] = $res_sql_temp[$i]['iTempId']; 
						$kml_arr[$ind]['vColor'] = $vColor; 
						$kml_arr[$ind]['vName'] = $res_sql[0]['vName']; 
						$kml_arr[$ind]['pShape1'] = $PShape;
						$ind++;
					}
			    }
			}
		}
	}
	$jsonData = array('kml_arr' =>$kml_arr,'file_src' => $file_name);
	# -----------------------------------
	# Return jSON data.
	# -----------------------------------
	echo json_encode($jsonData);
	hc_exit();
	# -----------------------------------	
}else if($mode == "delete_kml_block") {
	//echo "<pre>";print_r($_REQUEST);exit;
	header("Content-Type: text/plain");
	$iCLTmpId = $_REQUEST['iCLTmpId'];
	$iCLId = $_REQUEST['iCLId'];
	
	$CustomLayerObj->clear_variable();
	$join_fieds_arr = array();
	$join_arr  = array();
	$where_arr  = array();
	$where_arr[] = '"iCLId" = '.$iCLId.'';
	$CustomLayerObj->join_field = $join_fieds_arr;
	$CustomLayerObj->join = $join_arr;
	$CustomLayerObj->where = $where_arr;
	$CustomLayerObj->param['limit'] = ' LIMIT 1 ';
	$CustomLayerObj->setClause();
	$rs_kml =  $CustomLayerObj->recordset_list();

	//echo "<pre>";print_r($rs_kml);exit;

	$sql = "SELECT * FROM custom_layer_temp_data WHERE \"iCLId\" = '".$iCLId."' ORDER BY \"iCLTmpId\"";
	$rs_kml_temp = $sqlObj->GetAll($sql);
	//echo "<pre>";print_r($rs_kml_temp);exit;
	$ni = count($rs_kml_temp);
	$kml = '';
	$flag = 0;
	if($ni > 0){
		for($i=0; $i<$ni; $i++){
			$vOldStr = $rs_kml_temp[$i]['vOldStr'];
			$vNewStr = $rs_kml_temp[$i]['vNewStr'];


			$vStr = '';
			if($vNewStr != '')
				$vStr = $vNewStr;
			else 
				$vStr = $vOldStr;
			## remove latlng from placemark
			$vstr_merge = '';

			//echo $iCLTmpId  . "  ==  ". $rs_kml_temp[$i]['iCLTmpId']."\n";//exit;
			if($_REQUEST['iCLTmpId'] == $rs_kml_temp[$i]['iCLTmpId']) {
				
				$arr = explode("</Placemark>",$vStr);
				$vPrevStr = '';
				if($rs_kml_temp[$i-1]['vNewStr'] != ''){
					$vPrevStr = $rs_kml_temp[$i-1]['vNewStr'];
				}else {
					$vPrevStr = $rs_kml_temp[$i-1]['vOldStr'];
				}

				$vNextStr = '';
				if($rs_kml_temp[$i+1]['vNewStr'] != ''){
					$vNextStr = $rs_kml_temp[$i+1]['vNewStr'];
				}else {
					$vNextStr = $rs_kml_temp[$i+1]['vOldStr'];
				}
				

				if($vPrevStr != ''){
					$arr1 = explode("<Placemark>",$vPrevStr);
					//echo $arr1[1];exit;
					if (strpos(trim($arr1[1]), '<MultiGeometry>') !== false) {

						if (strpos(trim($vPrevStr), '<MultiGeometry>') !== false) {

							if (strpos(trim($vNextStr), '<Placemark>') !== false) {
								$vstr_merge .= $vPrevStr;
							}else{
								//echo "here===\n\n";
								//echo $vNextStr;exit;
								//$vNextStr = str_replace("\n"," ",$vNextStr);
								$vNextStr = str_replace("\t","",$vNextStr);
								$vNextStr = str_replace("\n","",$vNextStr);
								//echo $vNextStr;exit;
								if (strpos(trim($vNextStr), '</Polygon><Polygon>') !== false){
									//echo "here===\n\n";
									$vstr_merge .= $vPrevStr;
								}else {
									$arrrr = explode("<MultiGeometry>",$vPrevStr);
									//echo "1<pre>";print_r($arrrr);exit;
									$vstr_merge .= $arrrr[0];
									$vstr_merge .= trim($arrrr[1]);
								}
								
								
							}
						}else {
							$vstr_merge .= $vPrevStr;
						}
					}
					else {
						//echo "here===\n\n";
						if($arr1[1] != ''){
							if (strpos(trim($vStr), '<MultiGeometry>') !== false) {
								//echo "1here===\n\n";
								$arrrr = explode("<Polygon>",$arr1[1]);
								//echo "<pre>";print_r($arrrr);exit;
								$vstr_merge .= $arr1[0]."<Placemark>";
								$vstr_merge .= trim($arrrr[0]).'<MultiGeometry><Polygon>';
								$vstr_merge .= trim($arrrr[1]);
							}else {
								$vstr_merge .= $arr1[0]."<Placemark>";
								$vstr_merge .= trim($arr1[1]);
							}
						}else {
							$vstr_merge .= $arr1[0];
						}
					}
					if(strpos(trim($vstr_merge), '<innerBoundaryIs>') !== false) {
						if (!strpos(trim($vNextStr), '</innerBoundaryIs>') !== false) {
							//echo $vstr_merge."*****\n\n\n";
							$vstr_merge = str_replace("<innerBoundaryIs>","<outerBoundaryIs>",$vstr_merge);
						}
					}
					//$vstr_merge .= $arr1[0];
				}
				
				//echo "<pre>";print_r($arr1);exit;
				$vNextstr_merge = '';
				if($vNextStr != ''){					
					if(strpos(trim($vStr), '</MultiGeometry>') !== false) {
						$arr1 = explode("</Placemark>",$vNextStr);
						if(strpos(trim($arr1[0]), '</MultiGeometry>') !== false){
							//echo "<pre>";print_r($arr1);exit;
							if($arr1[1] != ''){
								$vNextstr_merge .= $arr1[0]."</Placemark>";;
								$vNextstr_merge .= trim($arr1[1]);
							}else {
								$vNextstr_merge .= $arr1[0];
							}
						}else {
							//echo "<pre>";print_r($arr1);exit;
							//echo $arr1[1];exit;
							if($arr1[1] != ''){
								$vNextstr_merge .= $arr1[0]."</MultiGeometry></Placemark>";
								$vNextstr_merge .= trim($arr[1]);
							}else {
								$vNextstr_merge .= $arr1[0];
							}
							
						}
					}
				}
				
				//$vstr_merge.="<LinearRing><LinearRing>";
				//echo $vstr_merge."========\n\n\n\n";//exit;
				//echo $vNextstr_merge;exit;
				//echo $rs_kml_temp[$i-1]['iCLTmpId'];exit;
				if($vstr_merge != '') {
					$sql_u = "UPDATE custom_layer_temp_data set \"vNewStr\" = '".trim($vstr_merge)."' WHERE \"iCLTmpId\" = '".$rs_kml_temp[$i-1]['iCLTmpId']."'";
					//echo $sql_u."\n";;
					$sqlObj->Execute($sql_u);
					if($vNextstr_merge != ''){
						$sql_u = "UPDATE custom_layer_temp_data set \"vNewStr\" = '".trim($vNextstr_merge)."' WHERE \"iCLTmpId\" = '".$rs_kml_temp[$i+1]['iCLTmpId']."'";
						//echo $sql_u."\n";;
						$sqlObj->Execute($sql_u);	
					}

					$sql_del = "DELETE FROM custom_layer_temp_data where \"iCLTmpId\" = '".$_REQUEST['iCLTmpId']."'";
					//echo $sql_del."\n";;
					$sqlObj->Execute($sql_del);
					$rs_up = $sqlObj->Affected_Rows();
					if($rs_up) {
						$flag = 1;
					}
				}
							
			}
		}
		//echo $vstr_merge;exit;
		
		
	}
	//exit;
	$total = 0;
	if($flag == 1){
		$sql_new = "SELECT * FROM custom_layer_temp_data WHERE \"iCLId\" = '".$iCLId."' ORDER BY \"iCLTmpId\"";
		$rs_new = $sqlObj->GetAll($sql_new);
		//echo "<pre>";print_r($rs_new);exit;
		$cnt = count($rs_new);
		$kml = '';
		if($cnt > 0){
			for($i=0; $i<$cnt; $i++){
				$vOldStr = $rs_new[$i]['vOldStr'];
				$vNewStr = $rs_new[$i]['vNewStr'];
				$vStr = '';
				if($vNewStr != '')
					$vStr = $vNewStr;
				else 
					$vStr = $vOldStr;

				if (strpos($vStr, '<LinearRing>') !== false) {
					$kml .= $vStr.'<coordinates>';
				}else {
					$kml .= $vStr;
				}
				
			}
		}
		//echo $kml;exit;
		//ob_clean();
		$file = time()."_".str_replace(" ", "_", $rs_kml[0]['vName']).".kml";
		file_put_contents($custom_layer_path.$country_id."/".$file, $kml);

		$file_path = $custom_layer_path.$country_id."/".$file;
		$file_url = $custom_layer_url.$country_id."/".$file;

		// *************************** //
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $file_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($curl);
		curl_close($curl);
		$res = 0;
		if (simplexml_load_string($output)) {
		  $res = 1;
		} else {
		  $res = 0;
		}
		// *************************** //
		//echo $res;exit;
		if(file_exists($file_path) && $file != '' && $res == 1)
		{	
			$sql_up_kml = "UPDATE kml_mas set \"vFile\" = '".$file."',\"dModifiedDate\" = '".date_getSystemDateTime()."' WHERE \"iCLId\" = '".$iCLId."'";
			//echo $sql_up_kml."<hr>";
			$sqlObj->Execute($sql_up_kml);
			$total = 1;
		}else {
			$total = 0;
			## first TRUNCATE custom_layer_temp_data
			$del_mas_sql = "TRUNCATE custom_layer_temp_data RESTART IDENTITY";
			$rs_del_mas_sql = $sqlObj->Execute($del_mas_sql);

			//$sql = 'select setval(\'"public"."custom_layer_temp_data_iCLTmpId_seq"\'::regclass, (select MAX("iCLTmpId") FROM "public"."custom_layer_temp_data"))';
			$sql = 'ALTER SEQUENCE "public"."custom_layer_temp_data_iCLTmpId_seq" RESTART WITH 1;';
			$sqlObj->Execute($sql);
		}
	}
	$jsonData = array('total' =>$total,'res'=>$res);
	echo json_encode($jsonData);
	hc_exit();
}
else if($mode == "EditBlocksMap"){
	//echo "<pre>";print_r($_REQUEST);exit;
	header("Content-Type: text/plain");
	$flag = 0;
	$iCLId = $_REQUEST['iCLId'];
	$iCLTmpId = $_REQUEST['iCLTmpId'];
	$pShapeArr = $_REQUEST['pShape1'];
	
	$pShape1 = str_replace("||", " ", $pShapeArr);
	$pShape2 = str_replace("|", ",", $pShape1);
	$pShape3 = substr($pShape2, 1,-1);
	//echo $pShape3;exit;
	$sql_chk = "SELECT * FROM custom_layer_temp_data where \"iCLTmpId\" = '".$iCLTmpId."' AND \"iCLId\" = '".$iCLId."' LIMIT 1";
	$rs_chk = $sqlObj->GetAll($sql_chk);
	//echo "<pre>";print_r($rs_chk);exit;
	$vFinalstr = '';
	$flag = 0;
	if(count($rs_chk) > 0){
		$vOldStr = trim($rs_chk[0]['vOldStr']);
		$vNewStr = trim($rs_chk[0]['vOldStr']);
		$vStr = '';
		if($vNewStr != '')
			$vStr = $vNewStr;
		else
			$vStr = $vOldStr;

		$arr = array();
		$arr = explode("</coordinates>",$vStr);
		//echo $arr[1];exit;
		//echo "<pre>";print_r($arr);exit;
		$vFinalstr = $pShape3;
		if(trim($arr[1]) != '')
			$vFinalstr .= "</coordinates>".trim($arr[1]);
		//echo $vFinalstr;exit;
	}
	//echo "<pre>";print_r($rs_chk);exit;
	if($vFinalstr != ''){
		$sql_up = "UPDATE custom_layer_temp_data set \"vNewStr\" = '".trim($vFinalstr)."' where \"iCLTmpId\" = '".$iCLTmpId."' AND \"iCLId\" = '".$iCLId."' ";
		//echo $sql_up."\n";
		$sqlObj->Execute($sql_up);
		$rs_up = $sqlObj->Affected_Rows();
		if($rs_up) {
			$flag = 1;
		}
	}
	//exit;

	$total = 0;
	if($flag == 1){
		$sql_new = "SELECT clt.*,cl.\"vName\" FROM custom_layer_temp_data clt, custom_layer cl  WHERE clt.\"iCLId\" =  cl.\"iCLId\" AND clt.\"iCLId\" = '".$iCLId."' ORDER BY clt.\"iCLTmpId\"";
		$rs_new = $sqlObj->GetAll($sql_new);
		//echo "<pre>";print_r($rs_new);exit;
		$cnt = count($rs_new);
		$kml = '';
		if($cnt > 0){
			for($i=0; $i<$cnt; $i++){
				$vOldStr = $rs_new[$i]['vOldStr'];
				$vNewStr = $rs_new[$i]['vNewStr'];
				$vStr = '';
				if($vNewStr != '')
					$vStr = $vNewStr;
				else 
					$vStr = $vOldStr;

				if (strpos($vStr, '<LinearRing>') !== false) {
					$kml .= $vStr.'<coordinates>';
				}else {
					$kml .= $vStr;
				}
				
			}
		}
		//echo $kml;exit;
		//ob_clean();
		$file = time()."_".str_replace(" ", "_", $rs_new[0]['vName']).".kml";
		file_put_contents($custom_layer_path.$country_id."/".$file, $kml);

		$file_path = $custom_layer_path.$country_id."/".$file;
		$file_url = $custom_layer_url.$country_id."/".$file;
		//echo $file_url;exit;
		
		if(file_exists($file_path) && $file != '')
		{	
			$sql_up_kml = "UPDATE custom_layer set \"vFile\" = '".$file."',\"dModifiedDate\" = '".date_getSystemDateTime()."' WHERE \"iCLId\" = '".$iCLId."'";
			//echo $sql_up_kml."<hr>";
			$sqlObj->Execute($sql_up_kml);
			$total = 1;
		}
	}
	$jsonData = array('total' =>$total);
	echo json_encode($jsonData);
	hc_exit();
}else if($mode == "AddNewBlocks"){
	//echo "<pre>";print_r($_REQUEST);exit;
	header("Content-Type: text/plain");
	$iCLId = $_REQUEST['iCLId'];
	$pShape = $_REQUEST['pShape'];
	$sql_kml = "SELECT * FROM custom_layer where \"iCLId\" = '".$iCLId."'";
	$rs_kml = $sqlObj->GetAll($sql_kml);

	if (strpos($pShape, 'POLYGON') !== false) {
		$str1 = substr($pShape, 9,-2);
		$str2 = str_replace(",", "|||", $str1);
		$str3 = str_replace(" ", ",", $str2);
		$str4 = str_replace("|||", " ", $str3);
		//echo $str4 . "</Folder></Document></kml>";//exit;
	}
	//echo $pShape3;exit;
	$sql_chk = "SELECT * FROM custom_layer_temp_data where \"iCLId\" = '".$iCLId."' ORDER BY \"iCLTmpId\" DESC LIMIT 2";
	$rs_chk = $sqlObj->GetAll($sql_chk);
	//echo "<pre>";print_r($rs_chk);exit;
	if(count($rs_chk)>0){
		######################################
		$vstr = '';
		$vOldStr = $rs_chk[0]['vOldStr'];
		$vNewStr = $rs_chk[0]['vNewStr'];
		$iCLTmpId = $rs_chk[0]['iCLTmpId'];
		if($vNewStr != '')
			$vstr = $vNewStr;
		else 
			$vstr = $vOldStr;
		######################################

		######################################
		$vstr1 = '';
		$vOldStr1 = $rs_chk[1]['vOldStr'];
		$vNewStr1 = $rs_chk[1]['vNewStr'];
		$iCLTmpId1 = $rs_chk[1]['iCLTmpId'];
		if($vNewStr1 != '')
			$vstr1 = $vNewStr1;
		else 
			$vstr1 = $vOldStr1;
		######################################
		$arr1 = explode("</Placemark>",$vstr1);
		//echo "<pre>";print_r($arr1);exit;

		$arr = explode("</Folder>",$vstr);
		//echo "<pre>";print_r($arr);exit;
		if(trim($arr[0]) != ''){
			/*$vNewStr1 = trim($arr[0])."<Placemark>
			<Style><LineStyle><color>ff0000ff</color></LineStyle><PolyStyle><fill>0</fill></PolyStyle></Style>
      		<Polygon><outerBoundaryIs><LinearRing>";*/
      		$vNewStr1 = trim($arr[0]).trim($arr1[1]);
			//echo $vNewStr1;exit;
			$sql_up = "UPDATE custom_layer_temp_data set \"vNewStr\" = '".$vNewStr1."' where \"iCLId\" = '".$iCLId."' AND \"iCLTmpId\" = '".$iCLTmpId."'";
			//echo $sql_up."\n";//exit;
			$sqlObj->Execute($sql_up);

			$sql_te = "SELECT MAX(\"iTempId\") as \"iTempId\" FROM custom_layer_temp_data where \"iCLId\" = '".$iCLId."' ";
			$rs_te = $sqlObj->GetAll($sql_te);
			$iTempId1 = $rs_te[0]['iTempId'];
			//echo $iTempId1;exit;
			$iNewTempId = $iTempId1 +1;
			$vFinalstr = $str4."</coordinates></LinearRing></outerBoundaryIs></Polygon>
  </Placemark></Folder></Document></kml>";
			$sql_ins = "INSERT INTO custom_layer_temp_data(\"iCLId\", \"vOldStr\", \"iTempId\") VALUES('".$iCLId."', '".trim($vFinalstr)."', '".$iNewTempId."')";
			//echo $sql_ins;exit;
			$sqlObj->Execute($sql_ins);
			$iNewKmlTempId = $sqlObj-> Insert_ID();
		}
		//echo $arr[0];exit;
		//echo "iNewKmlTempId >>>>>>>>>>>> ".$iNewKmlTempId."\n";
		$total = 0;
		if($iNewKmlTempId> 0){
			$sql_new = "SELECT * FROM custom_layer_temp_data WHERE \"iCLId\" = '".$iCLId."' ORDER BY \"iCLTmpId\"";
			$rs_new = $sqlObj->GetAll($sql_new);
			//echo "<pre>";print_r($rs_new);exit;
			$cnt = count($rs_new);
			$kml = '';
			if($cnt > 0){
				for($i=0; $i<$cnt; $i++){
					$vOldStr = $rs_new[$i]['vOldStr'];
					$vNewStr = $rs_new[$i]['vNewStr'];
					$vStr = '';
					if($vNewStr != '')
						$vStr = $vNewStr;
					else 
						$vStr = $vOldStr;

					if (strpos($vStr, '<LinearRing>') !== false) {
						$kml .= $vStr.'<coordinates>';
					}else {
						$kml .= $vStr;
					}
					
				}
			}
			//echo $kml;exit;
			//ob_clean();
			$file = time()."_".str_replace(" ", "_", $rs_kml[0]['vName']).".kml";
			file_put_contents($custom_layer_path.$country_id."/".$file, $kml);

			$file_path = $custom_layer_path.$country_id."/".$file;
			$file_url = $custom_layer_url.$country_id."/".$file;
			//echo $file_url;exit;
			
			if(file_exists($file_path) && $file != '')
			{	
				$sql_up_kml = "UPDATE custom_layer set \"vFile\" = '".$file."',\"dModifiedDate\" = '".date_getSystemDateTime()."' WHERE \"iCLId\" = '".$iCLId."'";
				//echo $sql_up_kml."<hr>";
				$sqlObj->Execute($sql_up_kml);
				$total = 1;
			}
		}
		$jsonData = array('total' =>$total);
		echo json_encode($jsonData);
		hc_exit();
	}
}

$module_name = "Custom Layer Geo Edit [".$vName."]";
$smarty->assign("mode", $mode);
$smarty->assign("module_name", $module_name);
$smarty->assign("iCLId", $_REQUEST['iCLId']);
?>