<?php
include_once($controller_path . "premise_type.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path . "premise_sub_type.inc.php");
include_once($function_path."image.inc.php");
include_once($function_path."site_general.inc.php");

if($request_type == "premise_type_list"){
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
        $where_arr[] = "site_type_mas.\"iStatus\"='".$iStatus."'";
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
        $where_arr[] = "site_sub_type_mas.\"iStatus\"='".$iStatus."'";
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
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>