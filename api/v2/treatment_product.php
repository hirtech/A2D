<?php
include_once($controller_path . "treatment_product.inc.php");

$TProdObj = new TreatmentProduct();

if($request_type == "treatment_product_add"){

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
}
else if($request_type == "treatment_product_edit"){

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

    $iTPId = $RES_PARA['iTPId'];
    $rs_db = $TProdObj->delete_records($iTPId);

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTPId" => $iTPId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "treatment_product_list"){

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
   
    if ( $iTPId != "") {
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
        case "iTPId":
            $sortname = "treatment_product.\"iTPId\"";
            break;
        case "vName":
            $sortname = "treatment_product.\"vName\"";
            break; 
        case "vCategory":
            $sortname = "treatment_product.\"vCategory\"";
            break;
        case "iPesticide":
            $sortname = "treatment_product.\"iPesticide\"";
            break;
        case "vClass":
            $sortname = "treatment_product.\"vClass\"";
            break;
        case "vUnit":
            $sortname = "unit_mas.\"vUnit\"";
            break;
        case "iStatus":
            $sortname = "treatment_product.\"iStatus\"";
            break;
        default:
            $sortname = "treatment_product.\"iTPId\"";
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
   
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>