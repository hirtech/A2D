<?php
include_once($controller_path . "premise_type.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path . "premise_sub_type.inc.php");
include_once($controller_path . "treatment_product.inc.php");
include_once($controller_path . "city.inc.php");
include_once($controller_path . "state.inc.php");
include_once($controller_path . "county.inc.php");
include_once($function_path."image.inc.php");
include_once($function_path."site_general.inc.php");

if($request_type == "city_list"){
    $City_Obj = new City();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $vCity              = trim($RES_PARA['vCity']);
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

    if ($vCity != "") {
         $where_arr[] = 'city_mas."vCity" ILIKE \''.$vCity.'%\'';
    }

    switch ($display_order) {
        case "0" : 
            $sortname = "city_mas.\"iCityId\"";
            break;
        case "1":
            $sortname = "city_mas.\"vCity\"";
            break;
        default:
            $sortname = 'city_mas."vCity"';
            break;
    }

    $join_fieds_arr = array();
    $join_arr = array();
    $City_Obj->join_field = $join_fieds_arr;
    $City_Obj->join = $join_arr;
    $City_Obj->where = $where_arr;
    $City_Obj->param['order_by'] = $sortname . " " . $dir;
    $City_Obj->param['limit'] = $limit;
    $City_Obj->setClause();
    $City_Obj->debug_query = false;
    $rs_type = $City_Obj->recordset_list();
    $total = $City_Obj->recordset_total();
    $data = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $data[] = array(
                "iCityId" => gen_strip_slash($rs_type[$i]['iCityId']),
                "vCity" => gen_strip_slash($rs_type[$i]['vCity']),
            );
        }
    }
    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "city_delete"){
    $iCityId = $RES_PARA['iCityId'];
    $City_Obj = new City();
    $rs_db = $City_Obj->delete_records($iCityId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iCityId" => $iCityId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "city_add"){
    $City_Obj = new City();
    $insert_arr = array(
        "vCity"    => $RES_PARA['vCity'],
    );
    $City_Obj->insert_arr = $insert_arr;
    $City_Obj->setClause();
    $iCityId = $City_Obj->add_records();
    if(isset($iCityId)){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iCityId" => $iCityId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "city_edit"){
    $City_Obj = new City();
    $update_arr = array(
        "iCityId"       => $RES_PARA['iCityId'],
        "vCity"         => $RES_PARA['vCity'],
    );
    //echo "<pre>";print_r($update_arr);exit;
    $City_Obj->update_arr = $update_arr;
    $City_Obj->setClause();
    $iCityId = $City_Obj->update_records();
    if(isset($iCityId)){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iCityId" => $RES_PARA['iCityId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "state_list"){
    $State_Obj = new State();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $vState             = trim($RES_PARA['vState']);
        $vStateCode         = trim($RES_PARA['vStateCode']);
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

    if ($vState != "") {
        $where_arr[] = 'state_mas."vState" ILIKE \''.$vState.'%\'';
    }
    if ($vStateCode != "") {
        $where_arr[] = 'state_mas."vStateCode" ILIKE \''.$vStateCode.'%\'';
    }

    switch ($display_order) {
        case "0" : 
            $sortname = "state_mas.\"iStateId\"";
            break;
        case "1":
            $sortname = "state_mas.\"vState\"";
            break;
        case "2":
            $sortname = "state_mas.\"vStateCode\"";
            break;
        default:
            $sortname = 'state_mas."vState"';
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $State_Obj->join_field = $join_fieds_arr;
    $State_Obj->join = $join_arr;
    $State_Obj->where = $where_arr;
    $State_Obj->param['order_by'] = $sortname . " " . $dir;
    $State_Obj->param['limit'] = $limit;
    $State_Obj->setClause();
    $State_Obj->debug_query = false;
    $rs_type = $State_Obj->recordset_list();
    // Paging Total Records
    $total = $State_Obj->recordset_total();
    $data = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $data[] = array(
                "iStateId"      => gen_strip_slash($rs_type[$i]['iStateId']),
                "vState"        => gen_strip_slash($rs_type[$i]['vState']),
                "vStateCode"    => gen_strip_slash($rs_type[$i]['vStateCode']),
            );
        }
    }
    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "state_add"){
    $State_Obj = new State();
    $insert_arr = array(
        "vState"        => $RES_PARA['vState'],
        "vStateCode"    => $RES_PARA['vStateCode'],
    );
    $State_Obj->insert_arr = $insert_arr;
    $State_Obj->setClause();
    $iStateId = $State_Obj->add_records();
    if(isset($iStateId)){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iStateId" => $iStateId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "state_edit"){
    $State_Obj = new State();
    $update_arr = array(
        "iStateId"       => $RES_PARA['iStateId'],
        "vState"         => $RES_PARA['vState'],
        "vStateCode"     => $RES_PARA['vStateCode']
    );
    //echo "<pre>";print_r($update_arr);exit;
    $State_Obj->update_arr = $update_arr;
    $State_Obj->setClause();
    $iStateId = $State_Obj->update_records();
    if(isset($iStateId)){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iStateId" => $RES_PARA['iStateId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "state_delete"){
    $iStateId = $RES_PARA['iStateId'];
    $State_Obj = new State();
    $rs_db = $State_Obj->delete_records($iStateId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iStateId" => $iStateId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "county_list"){
    $County_Obj = new County();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $vCounty            = trim($RES_PARA['vCounty']);
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

    if ($vCounty != "") {
        $where_arr[] = 'county_mas."vCounty" ILIKE \''.$vCounty.'%\'';
    }

    switch ($display_order) {
        case "0" : 
            $sortname = "county_mas.\"iCountyId\"";
            break;
        case "1":
            $sortname = "county_mas.\"vCounty\"";
            break;
        default:
            $sortname = 'county_mas."vCounty"';
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $County_Obj->join_field = $join_fieds_arr;
    $County_Obj->join = $join_arr;
    $County_Obj->where = $where_arr;
    $County_Obj->param['order_by'] = $sortname . " " . $dir;
    $County_Obj->param['limit'] = $limit;
    $County_Obj->setClause();
    $County_Obj->debug_query = false;
    $rs_type = $County_Obj->recordset_list();
    // Paging Total Records
    $total = $County_Obj->recordset_total();

    $data = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $data[] = array(
                "iCountyId"    => gen_strip_slash($rs_type[$i]['iCountyId']),
                "vCounty"      => gen_strip_slash($rs_type[$i]['vCounty'])
            );
        }
    }
    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "county_add"){
    $County_Obj = new County();
    $insert_arr = array(
        "vCounty"    => $RES_PARA['vCounty'],
    );
    $County_Obj->insert_arr = $insert_arr;
    $County_Obj->setClause();
    $iCountyId = $County_Obj->add_records();
    if(isset($iCountyId)){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iCountyId" => $iCountyId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "county_edit"){
    $County_Obj = new County();
    $update_arr = array(
        "iCountyId"      => $RES_PARA['iCountyId'],
        "vCounty"        => $RES_PARA['vCounty'],
    );
    //echo "<pre>";print_r($update_arr);exit;
    $County_Obj->update_arr = $update_arr;
    $County_Obj->setClause();
    $iCountyId = $County_Obj->update_records();
    if(isset($iCountyId)){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iCountyId" => $RES_PARA['iCountyId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "county_delete"){
    $iCountyId = $RES_PARA['iCountyId'];
    $County_Obj = new County();
    $rs_db = $County_Obj->delete_records($iCountyId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iCountyId" => $iCountyId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "premise_type_list"){
	$SiteTypeObj = new SiteType();
	$where_arr = array();
    if(!empty($RES_PARA)){
        $vTypeName			= trim($RES_PARA['vTypeName']);
        $iStatus			= trim($RES_PARA['iStatus']);
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

	if ($vTypeName != "") {
         $where_arr[] = 'site_type_mas."vTypeName" ILIKE \''.$vTypeName.'%\'';
    }

	if ($iStatus != "") {
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "site_type_mas.\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "site_type_mas.\"iStatus\" = '0'";
        }
    }

	switch ($display_order) {
        case "0" : 
            $sortname = "site_type_mas.\"iSTypeId\"";
            break;
        case "1":
            $sortname = "site_type_mas.\"vTypeName\"";
            break;
        case "3":
            $sortname = "site_type_mas.\"iStatus\"";
            break;
        default:
            $sortname = "site_type_mas.\"iSTypeId\"";
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";
    //echo $sortname . " " . $dir;exit;
    $join_fieds_arr = array();
    $join_arr = array();
    $SiteTypeObj->join_field = $join_fieds_arr;
    $SiteTypeObj->join = $join_arr;
    $SiteTypeObj->where = $where_arr;
    $SiteTypeObj->param['order_by'] = $sortname . " " . $dir;
    $SiteTypeObj->param['limit'] = $limit;
    $SiteTypeObj->setClause();
    $SiteTypeObj->debug_query = false;
    $rs_type = $SiteTypeObj->recordset_list();
	//echo "<pre>";print_r($rs_type);exit();
    // Paging Total Records
    $total = $SiteTypeObj->recordset_total();

	$data = array();
	$ni = count($rs_type);

	if($ni > 0){
		for($i=0;$i<$ni;$i++){
			$data[] = array(
				"iSTypeId" => gen_strip_slash($rs_type[$i]['iSTypeId']),
                "vTypeName" => gen_strip_slash($rs_type[$i]['vTypeName']),
				"icon" => $rs_type[$i]['icon'],
				'iStatus' => $rs_type[$i]['iStatus'],
            );
		}
	}
	$result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "premise_type_delete"){
    $iSTypeId = $RES_PARA['iSTypeId'];
    $SiteTypeObj = new SiteType();
    $rs_db = $SiteTypeObj->delete_records($iSTypeId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iSTypeId" => $iSTypeId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "premise_type_add"){
    $file_name = '';
    //echo $premise_type_icon_path;exit;
    //echo "<pre>";print_r($FILES_PARA);exit;
    /*if($FILES_PARA["icon_url"]['name'] != ""){
        $file_arr = img_fileUpload("icon_url", $premise_type_icon_path, '', $valid_ext = array('jpg', 'jpeg', 'png'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    }*/

    $SiteTypeObj = new SiteType();
    $insert_arr = array(
        "vTypeName" => $RES_PARA['vTypeName'],
        "iStatus"   => $RES_PARA['iStatus'],
        "icon"      => $RES_PARA['icon'],
        //"icon"      => $file_name
    );
    //echo "<pre>";print_r($insert_arr);exit;
    $SiteTypeObj->insert_arr = $insert_arr;
    $SiteTypeObj->setClause();
    $iSTypeId = $SiteTypeObj->add_records();
    if(isset($iSTypeId)){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iSTypeId" => $iSTypeId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "premise_type_edit"){
    $SiteTypeObj = new SiteType();

    /*$file_name = '';
    if($FILES_PARA["icon_url"]['name'] != ""){
        $file_arr = img_fileUpload("icon_url", $premise_type_icon_path, '', $valid_ext = array('jpg', 'jpeg', 'png'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    }
    if($file_name == ''){
        $file_name = $RES_PARA['icon_url_old'];
    }*/

    $update_arr = array(
        "iSTypeId"  => $RES_PARA['iSTypeId'],
        "vTypeName" => $RES_PARA['vTypeName'],
        "iStatus"   => $RES_PARA['iStatus'],
        "icon"      => $RES_PARA['icon'],
        //"icon"      => $file_name
    );
    //echo "<pre>";print_r($update_arr);exit;
    $SiteTypeObj->update_arr = $update_arr;
    $SiteTypeObj->setClause();
    $iSTypeId = $SiteTypeObj->update_records();
    if(isset($iSTypeId)){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iSTypeId" => $RES_PARA['iSTypeId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "premise_sub_type_list"){
    $SiteSubTypeObj = new SiteSubType();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $vSubTypeName       = trim($RES_PARA['vSubTypeName']);
        $vTypeName          = trim($RES_PARA['vTypeName']);
        $iStatus            = trim($RES_PARA['iStatus']);
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

    if ($vTypeName != "") {
        $where_arr[] = 'site_type_mas."vTypeName" ILIKE \''.$vTypeName.'%\'';
    }

    if ($vSubTypeName != "") {
        $where_arr[] = 'site_sub_type_mas."vSubTypeName" ILIKE \''.$vSubTypeName.'%\'';
    }

    if ($iStatus != "") {
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "site_sub_type_mas.\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "site_sub_type_mas.\"iStatus\" = '0'";
        }
    }

    switch ($display_order) {
        case "0":
            $sortname = "site_sub_type_mas.\"iSSTypeId\"";
            break;
        case "1":
            $sortname = 'site_sub_type_mas."vSubTypeName"';
            break;
        case "2":
            $sortname = "site_type_mas.\"vTypeName\"";
            break;
        case "3":
            $sortname = "site_sub_type_mas.\"iStatus\"";
            break;
        default:
            $sortname = 'site_sub_type_mas."vSubTypeName"';
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";
    //echo $sortname . " " . $dir;exit;
    $join_fieds_arr = array();
    $join_fieds_arr[] = "site_type_mas.\"vTypeName\"";
    $join_arr  = array();
    $join_arr[] = "LEFT JOIN site_type_mas ON site_sub_type_mas.\"iSTypeId\" = site_type_mas.\"iSTypeId\"";
    $SiteSubTypeObj->join_field = $join_fieds_arr;
    $SiteSubTypeObj->join = $join_arr;
    $SiteSubTypeObj->where = $where_arr;
    $SiteSubTypeObj->param['order_by'] = $sortname . " " . $dir;
    $SiteSubTypeObj->param['limit'] = $limit;
    $SiteSubTypeObj->setClause();
    $SiteSubTypeObj->debug_query = false;
    $rs_subtype = $SiteSubTypeObj->recordset_list();
    //echo "<pre>";print_r($rs_subtype);exit();
    // Paging Total Records
    $total = $SiteSubTypeObj->recordset_total();

    $data = array();
    $ni = count($rs_subtype);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $data[] = array(
                "iSSTypeId"     => $rs_subtype[$i]['iSSTypeId'],
                "iSTypeId"      => $rs_subtype[$i]['iSTypeId'],
                "vSubTypeName"  => gen_strip_slash($rs_subtype[$i]['vSubTypeName']),
                "vTypeName"     => gen_strip_slash($rs_subtype[$i]['vTypeName']),
                'iStatus'       => $rs_subtype[$i]['iStatus'],
            );
        }
    }
    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "premise_sub_type_delete"){
    $iSSTypeId = $RES_PARA['iSSTypeId'];
    $SiteSubTypeObj = new SiteSubType();
    $rs_db = $SiteSubTypeObj->delete_records($iSSTypeId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iSSTypeId" => $iSSTypeId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "premise_sub_type_add"){
    $SiteSubTypeObj = new SiteSubType();
    $insert_arr = array(
        "iSTypeId"      => $RES_PARA['iSTypeId'],
        "vSubTypeName"  => $RES_PARA['vSubTypeName'],
        "iStatus"       => $RES_PARA['iStatus'],
    );
    //echo "<pre>";print_r($insert_arr);exit;
    $SiteSubTypeObj->insert_arr = $insert_arr;
    $SiteSubTypeObj->setClause();
    $iSSTypeId = $SiteSubTypeObj->add_records();
    if(isset($iSSTypeId)){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iSSTypeId" => $iSSTypeId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "premise_sub_type_edit"){
    $SiteSubTypeObj = new SiteSubType();

    $update_arr = array(
        "iSSTypeId"     => $RES_PARA['iSSTypeId'],
        "iSTypeId"      => $RES_PARA['iSTypeId'],
        "vSubTypeName"  => $RES_PARA['vSubTypeName'],
        "iStatus"       => $RES_PARA['iStatus'],
    );
    //echo "<pre>";print_r($update_arr);exit;
    $SiteSubTypeObj->update_arr = $update_arr;
    $SiteSubTypeObj->setClause();
    $iSSTypeId = $SiteSubTypeObj->update_records();
    if(isset($iSSTypeId)){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iSSTypeId" => $RES_PARA['iSSTypeId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "premise_attribute_list"){
    $SiteAttObj = new SiteAttribute();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $vAttribute         = trim($RES_PARA['vAttribute']);
        $iStatus            = trim($RES_PARA['iStatus']);
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

    if ($vAttribute != "") {
        $where_arr[] = 'site_attribute_mas."vAttribute" ILIKE \''.$vAttribute.'%\'';
    }

    if ($iStatus != "") {
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "site_attribute_mas.\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "site_attribute_mas.\"iStatus\" = '0'";
        }
    }

    switch ($display_order) {
        case "0":
            $sortname = "site_attribute_mas.\"iSAttributeId\"";
            break;
        case "1":
            $sortname = "site_attribute_mas.\"vAttribute\"";
            break;
        case "2":
            $sortname = "site_attribute_mas.\"iStatus\"";
            break;
        default:
            $sortname = 'site_attribute_mas."vAttribute"';
            break;
    }

    $join_fieds_arr = array();
    $join_arr = array();
    $SiteAttObj->join_field = $join_fieds_arr;
    $SiteAttObj->join = $join_arr;
    $SiteAttObj->where = $where_arr;
    $SiteAttObj->param['order_by'] = $sortname . " " . $dir;
    $SiteAttObj->param['limit'] = $limit;
    $SiteAttObj->setClause();
    $SiteAttObj->debug_query = false;
    $rs_type = $SiteAttObj->recordset_list();
    // Paging Total Records
    $total = $SiteAttObj->recordset_total();
    //echo "<pre>";print_r($rs_type);exit;
    $data = array();
    $ni = count($rs_type);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $data[] = array(
                "iSAttributeId"    => $rs_type[$i]['iSAttributeId'],
                "vAttribute"       => gen_strip_slash($rs_type[$i]['vAttribute']),
                'iStatus'          => $rs_type[$i]['iStatus'],
            );
        }
    }
    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "premise_attribute_delete"){
    $iSAttributeId = $RES_PARA['iSAttributeId'];
    $SiteAttObj = new SiteAttribute();
    $rs_db = $SiteAttObj->delete_records($iSAttributeId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iSAttributeId" => $iSAttributeId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "premise_attribute_add"){
    $SiteAttObj = new SiteAttribute();
    $insert_arr = array(
        "vAttribute"    => $RES_PARA['vAttribute'],
        "iStatus"       => $RES_PARA['iStatus'],
    );
    //echo "<pre>";print_r($insert_arr);exit;
    $SiteAttObj->insert_arr = $insert_arr;
    $SiteAttObj->setClause();
    $iSAttributeId = $SiteAttObj->add_records();
    if(isset($iSAttributeId)){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iSAttributeId" => $iSAttributeId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "premise_attribute_edit"){
    $SiteAttObj = new SiteAttribute();
    $update_arr = array(
        "iSAttributeId"     => $RES_PARA['iSAttributeId'],
        "vAttribute"        => $RES_PARA['vAttribute'],
        "iStatus"           => $RES_PARA['iStatus'],
    );
    //echo "<pre>";print_r($update_arr);exit;
    $SiteAttObj->update_arr = $update_arr;
    $SiteAttObj->setClause();
    $iSAttributeId = $SiteAttObj->update_records();
    if(isset($iSAttributeId)){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iSAttributeId" => $RES_PARA['iSAttributeId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "treatment_product_list"){
    $TProdObj = new TreatmentProduct();
    $where_arr = array();

    $page_length   = $RES_PARA['page_length'];
    $start         = $RES_PARA['start'];
    $sEcho         = $RES_PARA['sEcho'];
    $display_order = $RES_PARA['display_order'];
    $dir           = $RES_PARA['dir'];
    $iTPId         = $RES_PARA['iTPId'];
    $vName         = $RES_PARA['vName'];
    $vCategory     = $RES_PARA['vCategory'];
    $vClass        = $RES_PARA['vClass'];
    $iPesticide    = $RES_PARA['iPesticide'];
    $iUId          = $RES_PARA['iUId'];
    $iStatus       = $RES_PARA['iStatus'];
    $access_group_var_edit = $RES_PARA['access_group_var_edit'];
    $access_group_var_delete = $RES_PARA['access_group_var_delete'];

    if(!empty($RES_PARA)){
        $iTPId         = $RES_PARA['iTPId'];
        $vName         = $RES_PARA['vName'];
        $vCategory     = $RES_PARA['vCategory'];
        $vClass        = $RES_PARA['vClass'];
        $iPesticide    = $RES_PARA['iPesticide'];
        $iUId          = $RES_PARA['iUId'];
        $iStatus       = $RES_PARA['iStatus'];
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"10";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }
   
    if ($iTPId != "") {
        $where_arr[] = 'treatment_product."iTPId"='.$iTPId ;
    }
    if ($vName != "") {
        $where_arr[] = "treatment_product.\"vName\" ILIKE '" . $vName . "%'";
    }
    if ($vCategory != "") {
        $where_arr[] = "treatment_product.\"vCategory\" ILIKE '" . $vCategory . "%'";
    }
    if ($vClass != "") {
        $where_arr[] = "treatment_product.\"vClass\" ILIKE '" . $vClass . "%'";
    }
    if ($iPesticide != ""){
        if(strtolower($iPesticide) == "yes"){
            $where_arr[] = "treatment_product.\"iPesticide\" = 'Y'";
        }
        else if(strtolower($iPesticide) == "no"){
            $where_arr[] = "treatment_product.\"iPesticide\" = 'N'";
        } 
    }
    if ($iUId != "") {
        $where_arr[] = "unit_mas.\"vUnit\" ILIKE '" . $iUId . "%'";
    }
    if ($iStatus != ""){
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "treatment_product.\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "treatment_product.\"iStatus\" = '0'";
        }
    }
    switch ($display_order) {
        case "0":
            $sortname = 'treatment_product."iTPId"';
            break;
        case "1":
            $sortname = 'treatment_product."vName"';
            break; 
        case "2":
            $sortname = 'treatment_product."vCategory"';
            break;
        case "3":
            $sortname = 'treatment_product."iPesticide"';
            break;
        case "4":
            $sortname = 'treatment_product."vClass"';
            break;
        case "6":
            $sortname = 'unit_mas."vUnit"';
            break;
        case "8":
            $sortname = 'treatment_product."iStatus"';
            break;
        default:
            $sortname = 'treatment_product."iTPId"';
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";
  
    $join_fieds_arr = array();
    $join_fieds_arr[] = "unit_mas.\"vUnit\"";
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = treatment_product."iUId"';
    $TProdObj->join_field = $join_fieds_arr;
    $TProdObj->join = $join_arr;
    $TProdObj->where = $where_arr;
    $TProdObj->param['order_by'] = $sortname . " " . $dir;
    $TProdObj->param['limit'] = $limit;
    $TProdObj->setClause();
    $TProdObj->debug_query = false;
    $rs_data = $TProdObj->recordset_list();
    // Paging Total Records
    $total_record = $TProdObj->recordset_total();
    // Paging Total Records

    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $data = array();
    $ni = count($rs_data);
    if(!empty($rs_data)){
        for($i=0;$i<$ni;$i++){
            $itpId = $rs_data[$i]['iTPId'];
            $pesticide = ($rs_data[$i]['iPesticide'] =='Y')?'Yes':'No';
            $data[] = array(
                "iTPId" => $itpId,
                "vName" => $rs_data[$i]['vName'],
                "vCategory" => $rs_data[$i]['vCategory'],
                "iPesticide" => $pesticide,
                "vClass" => $rs_data[$i]['vClass'],
                "vEPARegNo" => $rs_data[$i]['vEPARegNo'],
                "vActiveIngredient" => $rs_data[$i]['vActiveIngredient'],
                "vActiveIngredient2" => $rs_data[$i]['vActiveIngredient2'],
                "vAI" => $rs_data[$i]['vAI'],
                "vAI2" => $rs_data[$i]['vAI2'],
                "iUId" => $rs_data[$i]['iUId'],
                "vUnit" => $rs_data[$i]['vUnit'],
                "vAppRate" => $rs_data[$i]['vAppRate'],
                "vTragetAppRate" =>$rs_data[$i]['vTragetAppRate'],
                "vMinAppRate" =>$rs_data[$i]['vMinAppRate'],
                "vMaxAppRate" =>$rs_data[$i]['vMaxAppRate'],
                "iStatus" => $rs_data[$i]['iStatus']       
            );
        }
    }
    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "treatment_product_add"){
    $TProdObj = new TreatmentProduct();
    $insert_arr = array(
        "vName"             => $RES_PARA['vName'],
        "vCategory"         => $RES_PARA['vCategory'],
        "vClass"            => $RES_PARA['vClass'],
        "iPesticide"        => $RES_PARA['iPesticide'],
        "vEPARegNo"         => $RES_PARA['vEPARegNo'],
        "vActiveIngredient" => $RES_PARA['vActiveIngredient'],
        "vActiveIngredient2"=> $RES_PARA['vActiveIngredient2'],
        "vAI"               => $RES_PARA['vAI'],
        "vAI2"              => $RES_PARA['vAI2'],
        "iUId"              => $RES_PARA['iUId'],
        "vAppRate"          => $RES_PARA['vAppRate'],
        "vTragetAppRate"    => $RES_PARA['vTragetAppRate'],
        "vMinAppRate"       => $RES_PARA['vMinAppRate'],
        "vMaxAppRate"       => $RES_PARA['vMaxAppRate'],
        "iStatus"           => $RES_PARA['iStatus']
    );

    $TProdObj->insert_arr = $insert_arr;
    $TProdObj->setClause();
    $rs_db = $TProdObj->add_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "result" => $rs_db);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "treatment_product_edit"){
    $TProdObj = new TreatmentProduct();
    $update_arr = array(
        "iTPId"             => $RES_PARA['iTPId'],
        "vName"             => $RES_PARA['vName'],
        "vCategory"         => $RES_PARA['vCategory'],
        "vClass"            => $RES_PARA['vClass'],
        "iPesticide"        => $RES_PARA['iPesticide'],
        "vEPARegNo"         => $RES_PARA['vEPARegNo'],
        "vActiveIngredient" => $RES_PARA['vActiveIngredient'],
        "vActiveIngredient2"=> $RES_PARA['vActiveIngredient2'],
        "vAI"               => $RES_PARA['vAI'],
        "vAI2"              => $RES_PARA['vAI2'],
        "iUId"              => $RES_PARA['iUId'],
        "vAppRate"          => $RES_PARA['vAppRate'],
        "vTragetAppRate"    => $RES_PARA['vTragetAppRate'],
        "vMinAppRate"       => $RES_PARA['vMinAppRate'],
        "vMaxAppRate"       => $RES_PARA['vMaxAppRate'],
        "iStatus"           => $RES_PARA['iStatus']
    );

    $TProdObj->update_arr = $update_arr;
    $TProdObj->setClause();
    $rs_db = $TProdObj->update_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "result" => $rs_db);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}
else if($request_type == "treatment_product_delete"){
    $TProdObj = new TreatmentProduct();
    $iTPId = $RES_PARA['iTPId'];
    $rs_db = $TProdObj->delete_records($iTPId);

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTPId" => $iTPId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>