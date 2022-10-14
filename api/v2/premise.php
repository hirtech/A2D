<?php
include_once($controller_path . "premise.inc.php");
include_once($controller_path . "fieldmap.inc.php");
include_once($controller_path . "premise_type.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path . "premise_sub_type.inc.php");
include_once($function_path."image.inc.php");
include_once($function_path."site_general.inc.php");

if($request_type == "premise_list"){
	$SiteObj = new Site();
	$where_arr = array();
    if(!empty($RES_PARA)){
        $vName				= trim($RES_PARA['vName']);
        $vTypeName			= trim($RES_PARA['vTypeName']);
        $vSubTypeName		= trim($RES_PARA['vSubTypeName']);
        $iStatus			= trim($RES_PARA['iStatus']);
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];

        $siteId				= $RES_PARA['siteId'];
        $iSTypeId			= $RES_PARA['iSTypeId'];
        $iSSTypeId			= $RES_PARA['iSSTypeId'];
        $iGeometryType		= $RES_PARA['iGeometryType'];
        $siteName			= $RES_PARA['siteName'];
        $SiteFilterOpDD		= $RES_PARA['SiteFilterOpDD'];
        $vAddress			= $RES_PARA['vAddress'];
        $AddressFilterOpDD	= $RES_PARA['AddressFilterOpDD'];
        $vCity				= $RES_PARA['vCity'];
        $CityFilterOpDD		= $RES_PARA['CityFilterOpDD'];
        $vState				= $RES_PARA['vState'];
        $StateFilterOpDD	= $RES_PARA['StateFilterOpDD'];
        $vCountry			= $RES_PARA['vCountry'];
        $CountryFilterOpDD	= $RES_PARA['CountryFilterOpDD'];
        $status				= $RES_PARA['status'];
    }
	
	if ($vName != "") {
        $where_arr[] = "s.\"vName\"='".$vName."'";
    }

	if ($vTypeName != "") {
        $where_arr[] = "st.\"vTypeName\"='".$vTypeName."'";
    }

	if ($vSubTypeName != "") {
        $where_arr[] = "sst.\"vSubTypeName\"='".$vSubTypeName."'";
    }

	if ($iStatus != "") {
        $where_arr[] = "s.\"iStatus\"='".$iStatus."'";
    }

	if ($siteId != "") {
        $where_arr[] = "s.\"iSiteId\"='".$siteId."'";
    }
    if ($iSTypeId != "") {
        $where_arr[] = "s.\"iSTypeId\"='".$iSTypeId."'";
    }

    if ($iSSTypeId != ""){
        $where_arr[] = "s.\"iSSTypeId\"='".$iSSTypeId."'";
    }

    if ($iGeometryType != ""){
        $where_arr[] = "s.\"iGeometryType\"='".$iGeometryType."'";
    }

    if ($siteName != "") {
        if ($SiteFilterOpDD != "") {
            if ($SiteFilterOpDD == "Begins") {
                $where_arr[] = 's."vName" ILIKE \''.trim($siteName).'%\'';
            } else if ($SiteFilterOpDD == "Ends") {
                $where_arr[] = 's."vName" ILIKE \'%'.trim($siteName).'\'';
            } else if ($SiteFilterOpDD == "Contains") {
                $where_arr[] = 's."vName" ILIKE \'%'.trim($siteName).'%\'';
            } else if ($SiteFilterOpDD == "Exactly") {
                $where_arr[] = 's."vName" ILIKE \''.trim($siteName).'\'';
            }
        } else {
            $where_arr[] = 's."vName" ILIKE \''.trim($siteName).'%\'';
        }
    }

    if ($vAddress != "") {
        if ($AddressFilterOpDD != "") {
            if ($AddressFilterOpDD == "Begins") {
                $where_arr[] = "s.\"vAddress1\" ILIKE '".trim($vAddress)."%'";
            } else if ($AddressFilterOpDD == "Ends") {
                $where_arr[] = "s.\"vStreet\" ILIKE '%".trim($vAddress)."'";
            } else if ($AddressFilterOpDD == "Contains") {
                $where_arr[] = "concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '%".trim($vAddress)."%'";
            } else if ($AddressFilterOpDD == "Exactly") {
                $where_arr[] = "concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '".trim($vAddress)."'";
            }
        } else {
            $where_arr[] = "concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '".trim($vAddress)."%'";
        }
    }

    if ($vCity != "") {
        if ($CityFilterOpDD != "") {
            if ($CityFilterOpDD == "Begins") {
                $where_arr[] = 'cm."vCity" ILIKE \''.trim($vCity).'%\'';
            } else if ($CityFilterOpDD == "Ends") {
                $where_arr[] = 'cm."vCity" ILIKE \'%'.trim($vCity).'\'';
            } else if ($CityFilterOpDD == "Contains") {
                $where_arr[] = 'cm."vCity" ILIKE \'%'.trim($vCity).'%\'';
            } else if ($CityFilterOpDD == "Exactly") {
                $where_arr[] = 'cm."vCity" ILIKE \''.trim($vCity).'\'';
            }
        } else {
            $where_arr[] = 'cm."vCity" ILIKE \''.trim($vCity).'%\'';
        }
    }

    if ($vState != "") {
        if ($StateFilterOpDD != "") {
            if ($StateFilterOpDD == "Begins") {
                $where_arr[] = 'sm."vState" ILIKE \''.trim($vState).'%\'';
            } else if ($StateFilterOpDD == "Ends") {
                $where_arr[] = 'sm."vState" ILIKE \'%'.trim($vState).'\'';
            } else if ($StateFilterOpDD == "Contains") {
                $where_arr[] = 'sm."vState" ILIKE \'%'.trim($vState).'%\'';
            } else if ($StateFilterOpDD == "Exactly") {
                $where_arr[] = 'sm."vState" ILIKE \''.trim($vState).'\'';
            }
        } else {
            $where_arr[] = 'sm."vState" ILIKE \''.trim($vState).'%\'';
        }
    }

    if ($vCountry != "") {
        if ($CountryFilterOpDD != "") {
            if ($CountryFilterOpDD == "Begins") {
                $where_arr[] = 'c."vCounty" ILIKE \''.trim($vCountry).'%\'';
            } else if ($CountryFilterOpDD == "Ends") {
                $where_arr[] = 'c."vCounty" ILIKE \'%'.trim($vCountry).'\'';
            } else if ($CountryFilterOpDD == "Contains") {
                $where_arr[] = 'c."vCounty" ILIKE \'%'.trim($vCountry).'%\'';
            } else if ($CountryFilterOpDD == "Exactly") {
                $where_arr[] = 'c."vCounty" ILIKE \''.trim($vCountry).'\'';
            }
        } else {
            $where_arr[] = 'c."vCounty" ILIKE \''.trim($vCountry).'%\'';
        }
    }
    if ($status != "") {
        $where_arr[] = "s.\"iStatus\"='".$status."'";
    }

	switch ($display_order) {
        case "1":
            $sortname = "s.\"iSiteId\"";
            break;
        case "2":
            $sortname = "s.\"vName\"";
            break;
        case "3":
            $sortname = "st.\"vTypeName\"";
            break;
        case "4":
            $sortname = 'sst."vSubTypeName"';
            break;
        case "6":
            $sortname = 'cm."vCity"';
            break;
        case "7":
            $sortname = 'sm."vState"';
            break;
        case "8":
            $sortname = 'z."vZoneName"';
            break;
        case "9":
            $sortname = 'n."vName"';
            break;
        case "10":
            $sortname = 's."iStatus"';
            break;
        case "1":
            $sortname = "s.\"iSiteId\"";
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";
    //echo $sortname . " " . $dir;exit;
    $join_fieds_arr = array();
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'sst."vSubTypeName"';
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'z."vZoneName"';
    $join_fieds_arr[] = 'n."vName" as "vNetwork"';
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN site_sub_type_mas sst on s."iSSTypeId" = sst."iSSTypeId"';
    $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
    $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->param['order_by'] = $sortname . " " . $dir;
    $SiteObj->param['limit'] = $limit;
    $SiteObj->setClause();
    $SiteObj->debug_query = false;
    $rs_site = $SiteObj->recordset_list();
	//echo "<pre>";print_r($rs_site);exit();
    // Paging Total Records
    $total = $SiteObj->recordset_total();

	$data = array();
	$ni = count($rs_site);

	if($ni > 0){
		for($i=0;$i<$ni;$i++){
            $vAddress = $rs_site[$i]['vAddress1'].' '.$rs_site[$i]['vStreet'];
			$data[] = array(
                "iSiteId" => gen_strip_slash($rs_site[$i]['iSiteId']),
				"vName" => gen_strip_slash($rs_site[$i]['vName']),
				"vTypeName" => gen_strip_slash($rs_site[$i]['vTypeName']),
				"vSubTypeName" => gen_strip_slash($rs_site[$i]['vSubTypeName']),
				"vAddress" => $vAddress,
				'vCity' => $rs_site[$i]['vCity'],
				'vState' => $rs_site[$i]['vState'],
				'vZoneName' => $rs_site[$i]['vZoneName'],
				'vNetwork' => $rs_site[$i]['vNetwork'],
				'vCounty' => $rs_site[$i]['vCounty'],
				'iStatus' => $rs_site[$i]['iStatus'],
            );
		}
	}
	$result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "premise_add"){
	$SiteObj = new Site();
	$insert_arr = array(
        "vName"				 => $RES_PARA['vName'],
		"iSTypeId"			 => $RES_PARA['iSTypeId'],
		"iSSTypeId"			 => $RES_PARA['iSSTypeId'],
		"vAddress1"			 => $RES_PARA['vAddress1'],
		"vAddress2"			 => $RES_PARA['vAddress2'],
		"vStreet"			 => $RES_PARA['vStreet'],
		"vCrossStreet"		 => $RES_PARA['vCrossStreet'],
		"iZipcode"			 => $RES_PARA['iZipcode'],
		"iStateId"			 => $RES_PARA['iStateId'],
		"iCountyId"			 => $RES_PARA['iCountyId'],
		"iCityId"			 => $RES_PARA['iCityId'],
		"iZoneId"			 => $RES_PARA['iZoneId'],
		"iGeometryType"		 => $RES_PARA['iGeometryType'],
		"vLatitude"			 => $RES_PARA['vLatitude'],
		"vLongitude"		 => $RES_PARA['vLongitude'],
		"vNewLatitude"		 => $RES_PARA['vNewLatitude'],
		"vNewLongitude"		 => $RES_PARA['vNewLongitude'],
		"iStatus"			 => $RES_PARA['iStatus'],
		"vPolygonLatLong"	 =>$RES_PARA['vPolygonLatLong'],
		"vPolyLineLatLong"	 =>$RES_PARA['vPolyLineLatLong'],
		"iSAttributeId"		 =>$RES_PARA['iSAttributeId'],
		"vLoginUserName"	 =>$RES_PARA['vLoginUserName']
    );
	//echo "<pre>";print_r($insert_arr);exit;
    $SiteObj->insert_arr = $insert_arr;
    $SiteObj->setClause();
    $rs_db = $SiteObj->add_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iSiteId" => $rs_db);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "premise_edit"){
	$SiteObj = new Site();
	$update_arr = array(
        "iSiteId"				 => $RES_PARA['iSiteId'],
        "vName"				 => $RES_PARA['vName'],
		"iSTypeId"			 => $RES_PARA['iSTypeId'],
		"iSSTypeId"			 => $RES_PARA['iSSTypeId'],
		"vAddress1"			 => $RES_PARA['vAddress1'],
		"vAddress2"			 => $RES_PARA['vAddress2'],
		"vStreet"			 => $RES_PARA['vStreet'],
		"vCrossStreet"		 => $RES_PARA['vCrossStreet'],
		"iZipcode"			 => $RES_PARA['iZipcode'],
		"iStateId"			 => $RES_PARA['iStateId'],
		"iCountyId"			 => $RES_PARA['iCountyId'],
		"iCityId"			 => $RES_PARA['iCityId'],
		"iZoneId"			 => $RES_PARA['iZoneId'],
		"iGeometryType"		 => $RES_PARA['iGeometryType'],
		"vLatitude"			 => $RES_PARA['vLatitude'],
		"vLongitude"		 => $RES_PARA['vLongitude'],
		"vNewLatitude"		 => $RES_PARA['vNewLatitude'],
		"vNewLongitude"		 => $RES_PARA['vNewLongitude'],
		"iStatus"			 => $RES_PARA['iStatus'],
		"iCId"				 => $RES_PARA['iCId'],
		"vPolygonLatLong"	 =>$RES_PARA['vPolygonLatLong'],
		"vPolyLineLatLong"	 =>$RES_PARA['vPolyLineLatLong'],
		"iSAttributeId"		 =>$RES_PARA['iSAttributeId'],
		"vLoginUserName"	 =>$RES_PARA['vLoginUserName']
    );
	//echo "<pre>";print_r($update_arr);exit;
    $SiteObj->update_arr = $update_arr;
    $SiteObj->setClause();
    $rs_db = $SiteObj->update_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_ADD);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "premise_delete"){
	$iSiteId = $RES_PARA['iSiteId'];
   	$SiteObj = new Site();
    $rs_db = $SiteObj->delete_single_record($iSiteId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iSiteId" => $iSiteId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "getPremiseContactData"){
	$SiteObj = new Site();
	$iSiteId = isset($RES_PARA['iSiteId'])?trim($RES_PARA['iSiteId']):"0";
	if($iSiteId > 0) {
		$SiteObj->clear_variable();
		$where_arr = array();
		$join_arr = array();
		$join_fieds_arr = array();
		$join_fieds_arr[] = 'concat(contact_mas."vSalutation", \' \',contact_mas."vFirstName", \' \', contact_mas."vLastName") AS "vName"';
		$join_fieds_arr[] =  'contact_mas."vPhone"';
		$join_fieds_arr[] = 'contact_mas."iCId"';
		$join_arr[] = 'INNER JOIN contact_mas ON site_contact."iCId" = contact_mas."iCId"';
		$where_arr[] = 'site_contact."iSiteId"='.$iSiteId;
		$SiteObj->join_field = $join_fieds_arr;
		$SiteObj->join = $join_arr;
		$SiteObj->where = $where_arr;
		$SiteObj->param['order_by'] = '"iSCId" ASC';
		$SiteObj->param['limit'] = 0;
		$SiteObj->setClause();
		$rs_site_contact = $SiteObj->site_contact_list();
		if($rs_site_contact){
			$response_data = array("Code" => 200, "result" => $rs_site_contact);
		}else{
			$response_data = array("Code" => 500);
		}
	}else {
		$r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => "Premise id is not valid");
	}
}else if($request_type == "getPremiseDocumentData"){
	$SiteObj = new Site();
	$iSiteId = isset($RES_PARA['iSiteId'])?trim($RES_PARA['iSiteId']):"0";
	if($iSiteId > 0) {
		$SiteObj->clear_variable();
        $join_fieds_arr = array();
        $join_arr = array();
        $where_arr = array();
        $where_arr[] = '"iSiteId" = '.$iSiteId;
        $SiteObj->join_field = $join_fieds_arr;
        $SiteObj->join = $join_arr;
        $SiteObj->where = $where_arr;
        $SiteObj->param['order_by'] = '"iSDId" DESC';
        $SiteObj->param['limit'] = 0;
        $SiteObj->setClause();
        $rs_site_doc = $SiteObj->get_site_document_list();
		$di = count($rs_site_doc);
		if($di > 0){
            $cnt_exif = 0;
            $iSDId_arr = array();
            for($d=0;$d<$di;$d++){
                if(file_exists($premise_documents_path.$rs_site_doc[$d]['vFile'])){
                    $download_path = $premise_documents_path.$rs_site_doc[$d]['vFile'];
                    $download_url = $premise_documents_url.$rs_site_doc[$d]['vFile'];
                    
                    $file_name_arr = explode('_', $rs_site_doc[$d]['vFile'], 2);
                    
                    $file_url = $site_url.'download.php?vFileName_path='.base64_encode($download_path).'&vFileName_url='.base64_encode($download_url).'&file_name='.base64_encode($file_name_arr[1]);
                    $rs_site_doc[$d]['file_url'] = $file_url;

                    $exif = exif_read_data($download_path, 0, true);
                    $rs_site_doc[$d]['dAddedDate'] =  date_getDateTime($rs_site_doc[$d]['dAddedDate']);

                    $details = exif_read_data($download_path);
                    $sections = explode(',', $details['SectionsFound']);
                    if(count($sections) > 0){
                        foreach($sections as $k=>$v){
                            $sections[$k] = trim($v);
                        }
                    }
                    //echo "<pre>"; print_r($sections);
                    $rs_site_doc[$d]['file_exif_gps'] = 0;

                    if(@in_array('GPS', ($sections))){
                        if($details['GPSLatitude']) {
                            $iSDId_arr[] = $rs_site_doc[$d]['iSDId'];
                            $rs_site_doc[0]['iSDId_arr'] = $iSDId_arr;
                            $cnt_exif++;
                            $rs_site_doc[$d]['file_exif_gps'] = 1;
                        }
                    }
                    $rs_site_doc[0]['cnt_exif'] = $cnt_exif;
                }
            }
			$response_data = array("Code" => 200, "result" => $rs_site_doc);
        }else{
			$response_data = array("Code" => 500);
		}
	}else {
		$r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => "Premise id is not valid");
	}
}else if($request_type == "add_premise_document"){
	$SiteObj = new Site();
	$table_row = '';
	$file_name = '';
	$file_msg = '';
	if($FILES_PARA["vFile"]['name'] != ""){
        $file_arr = img_fileUpload("vFile", $premise_documents_path, '', $valid_ext = array('txt', 'doc', 'docx', 'pdf', 'jpg', 'jpeg', 'png'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
   }
   //echo $file_name;exit;
   if($file_name != ""){
        $insert_arr = array(
            "iSiteId"			=> $RES_PARA['iSiteId'],
            "vTitle"			=> $RES_PARA['vTitle'],
            "vLoginUserName"    => $RES_PARA['vLoginUserName'],
            "vFile"				=> $file_name,
        );
        $SiteObj->insert_arr = $insert_arr;
        $iSDId = $SiteObj->add_document();
		if ($iSDId) {
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr = array();
            $where_arr[] = '"iSDId" = ' . $iSDId;
            $SiteObj->join_field = $join_fieds_arr;
            $SiteObj->join = $join_arr;
            $SiteObj->where = $where_arr;
            $SiteObj->param['limit'] = 'LIMIT 1';
            $SiteObj->setClause();
            $rs_site_doc = $SiteObj->get_site_document_list();

            $SiteObj->clear_variable();
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr = array();
            $where_arr[] = '"iSiteId" = ' . $RES_PARA['iSiteId'];
            $SiteObj->join_field = $join_fieds_arr;
            $SiteObj->join = $join_arr;
            $SiteObj->where = $where_arr;
            $SiteObj->param['limit'] = '';
            $SiteObj->setClause();
            $rs_site_documents = $SiteObj->get_site_document_list();
			//echo "<pre>"; print_R($rs_site_documents); exit;
            $cnt_rs_doc = count($rs_site_documents);
            $map_photo_span = '';
            if ($cnt_rs_doc > 0) {
                $iSDId_arr = array();
                for($d=0; $d<$cnt_rs_doc; $d++){
                    if (file_exists($premise_documents_path.$rs_site_documents[$d]['vFile'])) {
                        $details = exif_read_data($premise_documents_path.$rs_site_documents[$d]['vFile']);
                        $sections = explode(',', $details['SectionsFound']);
                        if(count($sections) > 0){
                        foreach($sections as $k=>$v){
                            $sections[$k] = trim($v);
                            }
                        }
                        if(in_array('GPS', ($sections))){
                            if($details['GPSLatitude'])
                                $iSDId_arr[] = $rs_site_documents[$d]['iSDId'];
                        }
                    }
                }

                if(count($iSDId_arr) > 0){                    
                    $map_photo_span = '<br /><a href="'.$site_url.'map/technician?site_selected=1&iSiteId='.$RES_PARA['iSiteId'].'&iSDId='.implode(',', $iSDId_arr).'" target="_blank"><input type="button" id="map_photos" class="btn btn-primary" value="Map Photos"></a>';
                }
            }

            if (count($rs_site_doc) > 0) {
                if (file_exists($premise_documents_path . $rs_site_doc[0]['vFile'])) {
                    $cnt_exif = 0;
                    $download_path = $premise_documents_path . $rs_site_doc[0]['vFile'];
                    $download_url = $premise_documents_url . $rs_site_doc[0]['vFile'];
                    $file_name_arr = explode('_', $rs_site_doc[0]['vFile'], 2);
                    $exif = exif_read_data($download_path, 0, true);
                    $dAddedDate = date_getDateTime($rs_site_doc[0]['dAddedDate']);
                    $details = exif_read_data($download_path);
                    $sections = explode(',', $details['SectionsFound']);
                    if(count($sections) > 0){
						foreach($sections as $k=>$v){
							$sections[$k] = trim($v);
						}
                    }

                    $rs_site_doc[$d]['file_exif_gps'] = 0;
                    if(in_array('GPS', ($sections))){
                        if($details['GPSLatitude']){
                            $rs_site_doc[$d]['file_exif_gps'] = 1;
                            $cnt_exif++;
                        }
                    }
                    $table_row .= '<tr><td>' . $rs_site_doc[0]['vTitle'] . '<input type="hidden" name="file_exif_gps" id="file_exif_gps_'.$rs_site_doc[$d]['file_exif_gps'].'" value="'.$rs_site_doc[$d]['file_exif_gps'].'"></td><td class="text-center" ><div align="center"><a href="' . $site_url . 'download.php?vFileName_path=' . base64_encode($download_path) . '&vFileName_url=' . base64_encode($download_url) . '&file_name=' . base64_encode($file_name_arr[1]) . '" title="Download">Downlaod</a></div></td><td class="text-center" ><div align="center">' . $dAddedDate . '</div></td><td>' . $rs_site_doc[0]['vLoginUserName'] . '</td><td class="action text-center" align="center"> <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_site_document(this, \'' . $rs_site_doc[0]['iSDId'] . '\',\''.$RES_PARA['iSiteId'].'\');" ><i class="fa fa-trash"></i></a></td></tr>';
                }
            }
			$result['error'] = '0' ;
			$result['file_msg'] = $file_msg;
			$result['table_row'] = $table_row;
			$result['map_photo_span'] = $map_photo_span;
			$response_data = array("Code" => 200, "Message" => MSG_ADD, "result" => $result);
        }else{
			$result['error'] = '1' ;
			$result['file_msg'] = $file_msg;
			$result['table_row'] = $table_row;
			$result['map_photo_span'] = '';
            $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "result" => $result);
        }
    }else{
        $r = HTTPStatus(500);
		$result['error'] = '1' ;
		$result['file_msg'] = $file_msg;
		$result['table_row'] = $table_row;
		$result['map_photo_span'] = '';
        $response_data = array("Code" => 500 , "Message" => $file_msg, "result" => $result);
    }
}else if($request_type == "delete_premise_document"){
   $delete_flag = 0;
   $result = array();
   $map_photo_span  = ""; 
   $iSDId = isset($RES_PARA['iSDId'])?trim($RES_PARA['iSDId']):"0";
   $iSiteId = isset($RES_PARA['iSiteId'])?trim($RES_PARA['iSiteId']):"0";
   $err_msg = array();
   if($iSDId == "" || $iSDId==0){
      $err_msg[] = " Premise document id is not valid";
   }
   if($iSiteId == "" || $iSiteId==0){
      $err_msg[] = " Premise id is not valid";
   }

   if(empty($err_msg)){
       $SiteObj = new Site();
       $join_fieds_arr = array();
       $join_arr = array();
       $where_arr = array();
       $where_arr[] = '"iSDId" = ' . $iSDId;
       $SiteObj->join_field = $join_fieds_arr;
       $SiteObj->join = $join_arr;
       $SiteObj->where = $where_arr;
       $SiteObj->param['limit'] = 'LIMIT 1';
       $SiteObj->setClause();
       $rs_site_doc = $SiteObj->get_site_document_list();

       if (count($rs_site_doc) > 0) {
		   if (file_exists($premise_documents_path . $rs_site_doc[0]['vFile'])) {
			   @unlink($premise_documents_path . $rs_site_doc[0]['vFile']);
		   }

           $SiteObj->ids = $rs_site_doc[0]['iSDId'];
           $rs_delete = $SiteObj->delete_site_document();
           if($rs_delete) {
				$SiteObj->clear_variable();
				$join_fieds_arr = array();
				$join_arr = array();
				$where_arr = array();

				$where_arr[] = '"iSiteId" = ' .$iSiteId;
				$SiteObj->join_field = $join_fieds_arr;
				$SiteObj->join = $join_arr;
				$SiteObj->where = $where_arr;
				$SiteObj->param['limit'] = '';
				$SiteObj->setClause();
				$rs_site_documents = $SiteObj->get_site_document_list();

				$cnt_doc = count($rs_site_documents);
				$map_photo_span = '';
				if ($cnt_doc > 0) {
					$iSDId_arr = array();
					for($d=0; $d<$cnt_doc; $d++){
						if (file_exists($site_documents_path.$rs_site_documents[$d]['vFile'])) {
							$details = exif_read_data($site_documents_path.$rs_site_documents[$d]['vFile']);
							$sections = explode(',', $details['SectionsFound']);
							if(count($sections) > 0){
							foreach($sections as $k=>$v){
								$sections[$k] = trim($v);
								}
							}
							if(in_array('GPS', ($sections))){
								if($details['GPSLatitude'])
									$iSDId_arr[] = $rs_site_documents[$d]['iSDId'];
							}
						}
					}

					if(count($iSDId_arr) > 0){
						$map_photo_span = '<br /><a href="'.$site_url.'map/technician?iSiteId='.$iSiteId.'&iSDId='.implode(',', $iSDId_arr).'" target="_blank"><input type="button" id="map_photos" class="btn btn-primary" value="Map Photos"></a>';
					}
				}
				$result['msg'] = MSG_DELETE;
				$result['error']= 0 ;
				$result['map_photo_span'] =$map_photo_span;
			    $rh = HTTPStatus(200);
			    $code = 2000;
			    $message = api_getMessage($req_ext, constant($code));
			    $response_data = array("Code" => 200, "Message" => MSG_DELETE, "result" => $result);;
         }else{
			$result['msg'] = MSG_DELETE;
			$result['error']= 1 ;
			$result['map_photo_span'] = "";
            $r = HTTPStatus(500);
            $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR, "result" => $result);
         }
      }else{
			$result['msg'] = MSG_DELETE;
			$result['error']= 1 ;
			$result['map_photo_span'] = "";
			$r = HTTPStatus(500);
			$response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR, "result" => $result);
      }
   }else{
	  $result['msg'] = implode("\n",$err_msg);
	  $result['error']= 1 ;
	  $result['map_photo_span'] = "";
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => implode("\n",$err_msg), "result" => $result);
   }
}else if($request_type == "search_premise"){
   $rs_arr  = array();
   $where_arr = array();
   $join_fieds_arr = array();
   $join_arr = array();

   $vSiteName_other = $RES_PARA['siteName'];
     
   $SiteObj = new Site();
   $SiteObj->clear_variable();

   $letters = str_replace("'","",$vSiteName_other);
   $exp_keyword = explode("\\",$letters);
  
   $ext_where_arr =array();
   foreach($exp_keyword as $vl){
     if(trim($vl) != '')
         $ext_where_arr[] = " s.\"vName\" ILIKE '%".trim($vl)."%' ";
   }
   if(count($ext_where_arr) > 0){
     $ext_where = implode(" AND ", $ext_where_arr);
     //$where_arr[] = '(s."vName" ILIKE \'%'.$ext_where.'%\' )';
     $where_arr[] = $ext_where;
   }else{
         $where_arr[] = '(s."vName" ILIKE \'%'.$vSiteName_other.'%\' OR s."iSiteId" = '.intval($vSiteName_other).')';
   }     
    $where_arr[] = 's."iStatus" = 1';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->param['limit'] = "0";
    $SiteObj->param['order_by'] = 's."iSiteId" DESC';
    
    $SiteObj->setClause();
    $rs_site = $SiteObj->recordset_list();
    for ($i = 0; $i < count($rs_site); $i++) {
        $rs_arr[] = array(
         'display' => $rs_site[$i]['iSiteId']." (".$rs_site[$i]['vName']."; ".$rs_site[$i]['vTypeName'].")",
         "iSiteId" => $rs_site[$i]['iSiteId'],
         "vName" => $rs_site[$i]['vName']
        );
    }

    $result = array('data' => $rs_arr);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>