<?php
include_once($controller_path . "access_group.inc.php");
$AccessGroupObj = new AccessGroup();
if($request_type == "access_group_list"){
	$where_arr = array();
    if(!empty($RES_PARA)){
        $vAccessGroup		= trim($RES_PARA['vAccessGroup']);
        $tDescription		= trim($RES_PARA['tDescription']);
        $iStatus			= trim($RES_PARA['iStatus']);

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];

    } 

    if ($vAccessGroup != "") {
        $where_arr[] = "access_group_mas.\"vAccessGroup\"='". $vAccessGroup."'";
    }
    if ($tDescription != "") {
        $where_arr[] = "access_group_mas.\"tDescription\"='".$tDescription."'";
    }
    if ($iStatus != "") {
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "access_group_mas.\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "access_group_mas.\"iStatus\" = '0'";
        }
    }
    
    switch ($display_order) {
        case "0" : 
            $sortname = "access_group_mas.\"iAGroupId\"";
            break;
        case "1":
            $sortname = "access_group_mas.\"vAccessGroup\"";
            break;
        case "4":
            $sortname = "access_group_mas.\"iStatus\"";
            break;
        default:
            $sortname = "access_group_mas.\"iAGroupId\"";
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $AccessGroupObj->join_field = $join_fieds_arr;
    $AccessGroupObj->join = $join_arr;
    $AccessGroupObj->where = $where_arr;
    $AccessGroupObj->param['order_by'] = $sortname . " " . $dir;
    $AccessGroupObj->param['limit'] = $limit;
    $AccessGroupObj->setClause();
    $AccessGroupObj->debug_query = false;
    $rs_data = $AccessGroupObj->recordset_list();
    // Paging Total Records
    $total = $AccessGroupObj->recordset_total();
    $data = array();
	$ni = count($rs_data);

	if($ni > 0){
		for($i=0;$i<$ni;$i++){
			$data[] = array(
                "iAGroupId" 		=> $rs_data[$i]['iAGroupId'],
                "vAccessGroup" 		=> $rs_data[$i]['vAccessGroup'],
                "iStatus" 			=> $rs_data[$i]['iStatus'],
                "iDefault" 			=> $rs_data[$i]['iDefault'],
                "tDescription"      => $rs_data[$i]['tDescription'],
            );
		}
	}
	$result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "access_group_add"){
	$result = array();
	$vAccessGroup 			= $RES_PARA['vAccessGroup'];
	$tDescription 			= $RES_PARA['tDescription'];
    $iDefault               = $RES_PARA['iDefault'];
    $iStatus                = $RES_PARA['iStatus'];
   	$insert_array = array(
        "vAccessGroup" 		=> $vAccessGroup,
        "tDescription" 	    => $tDescription,
        "iStatus" 			=> $iStatus
    );
    $AccessGroupObj->clear_variable();
    $AccessGroupObj->insert_arr = $insert_array;
    $AccessGroupObj->setClause();
    $iAGroupId = $AccessGroupObj->add_records();
    if($iAGroupId){
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iAGroupId" => $iAGroupId);
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "access_group_edit"){
   	$result = array();
    $iAGroupId              = $RES_PARA['iAGroupId'];
    $vAccessGroup           = $RES_PARA['vAccessGroup'];
    $tDescription           = $RES_PARA['tDescription'];
    $iDefault               = $RES_PARA['iDefault'];
    $iStatus                = $RES_PARA['iStatus'];
    $update_arr = array(
        "iAGroupId"         => $iAGroupId,
        "vAccessGroup"      => $vAccessGroup,
        "tDescription"      => $tDescription,
        "iStatus"           => $iStatus
    );
    $AccessGroupObj->clear_variable();
    $AccessGroupObj->update_arr = $update_arr;
    $AccessGroupObj->setClause();
    $rs_db = $AccessGroupObj->update_records();
    if($rs_db){
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iAGroupId" => $iAGroupId);
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "access_group_delete"){
    $iAGroupId = $RES_PARA['iAGroupId'];
    $AccessGroupObj->ids = $iAGroupId;
    $AccessGroupObj->setClause();
    $rs_db = $AccessGroupObj->delete_records();
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iAGroupId" => $iAGroupId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "access_group_dropdown") {
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "\"iStatus\"='".$iStatus."'";
    }
    $AccessGroupObj->where = $where_arr;
    $AccessGroupObj->param['order_by'] = "\"vAccessGroup\"";
    $AccessGroupObj->setClause();
    $rs_access_group = $AccessGroupObj->recordset_list();;
    if($rs_access_group){
        $response_data = array("Code" => 200, "result" => $rs_access_group, "total_record" => count($rs_access_group));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "access_group_manage_role") {
    $iAGroupId      = $RES_PARA['iAGroupId'];
    $eList          = $RES_PARA['eList'];
    $eAdd           = $RES_PARA['eAdd'];
    $eEdit          = $RES_PARA['eEdit'];
    $eDelete        = $RES_PARA['eDelete'];
    $eStatus        = $RES_PARA['eStatus'];
    $eRespond       = $RES_PARA['eRespond'];
    $eCSV           = $RES_PARA['eCSV'];
    $ePDF           = $RES_PARA['ePDF'];
    $eCalsurv       = $RES_PARA['eCalsurv'];

    $result = array();
    $sql_role = "delete from access_module_role where \"iAGroupId\" = '".$iAGroupId."'";
    $db_sql_role = $sqlObj->Execute($sql_role);
    $sql_module = "select \"iAModuleId\", \"vAccessModule\" from access_module_mas ORDER BY \"iAModuleId\", \"iDispOrder\"";
    $db_module=$sqlObj->GetAll($sql_module);
    if(!empty($db_module)){
        $value = array();
        for ($i=0; $i <count($db_module) ; $i++) { 
            $str_list = $str_add = $str_edit = $str_delete = $str_status = $str_csv = $str_pdf =$str_respond =$str_calsurv ="";
            $str_list       = (isset($eList[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_add        = (isset($eAdd[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_edit       = (isset($eEdit[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_delete     = (isset($eDelete[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_status     = (isset($eStatus[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_respond    = (isset($eRespond[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_csv        = (isset($eCSV[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_pdf        = (isset($ePDF[$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_calsurv    =(isset($eCalsurv[$db_module[$i]['iAModuleId']]))?"Y":"N";

            $value[]= "('".$db_module[$i]['iAModuleId']."', '".$iAGroupId."', '".$str_list."', '".$str_add."', '".$str_edit."', '".$str_delete."', '".$str_status."', '".$str_respond."', '".$str_csv."', '".$str_pdf."', '".$str_calsurv."')";
        }
        //echo "<pre>";print_r($value);exit();
        if(count($value) > 0)
        {
            $sql="INSERT INTO access_module_role (\"iAModuleId\", \"iAGroupId\", \"eList\", \"eAdd\", \"eEdit\", \"eDelete\", \"eStatus\", \"eRespond\", \"eCSV\", \"ePDF\", \"eCalsurv\")  VALUES ".implode(",", $value);
            // echo $sql;exit();
            $sqlObj->Execute($sql);
            $db_sql=$sqlObj->Affected_Rows();
            if($db_sql) {
                $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY;
                $result['error']= 0 ;
            }else{
                $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY_ERROR;
                $result['error']= 1 ;
            }
        }else {
            $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY_ERROR;
            $result['error']= 1 ;
        }
    }else{
        $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY_ERROR;
        $result['error']= 1 ;
    }
    $response_data = array("Code" => 200, "result" => $result);           
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>