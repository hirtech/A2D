<?php

include_once($controller_path . "network.inc.php");
include_once($function_path."image.inc.php");

$NetworkObj = new Network();

##Search Arary
$where_arr = array();
if($request_type == "network_list"){
	$where_arr = array();

   if(!empty($RES_PARA)){

        $iNetworkId         = $RES_PARA['iNetworkId'];
        $vName              = $RES_PARA['vName'];
        $iStatus              = $RES_PARA['iStatus'];
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
    }
    
    if ($iNetworkId != "") {
        $where_arr[] = '"iNetworkId"='.$iNetworkId ;
    }

    if ($vName != "") {
        $where_arr[] = "\"vName\" ILIKE '" . $vName . "%'";
    }

    if($iStatus != ""){
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "\"iStatus\" = '0'";
        }
    } 

    switch ($display_order) {
        case "0":
            $sortname = "\"iNetworkId\"";
            break;
        case "1":
            $sortname = "\"vName\"";
            break;
        case "2":
            $sortname = "\"iStatus\"";
            break;
        default:
            $sortname = "\"iNetworkId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $NetworkObj->join_field = $join_fieds_arr;
    $NetworkObj->join = $join_arr;
    $NetworkObj->where = $where_arr;
    if ($order_by != "") {
        $NetworkObj->param['order_by'] = $order_by;
    }else {
        $NetworkObj->param['order_by'] = $sortname . " " . $dir;
    }
    $NetworkObj->param['limit'] = $limit;
    $NetworkObj->setClause();
    $NetworkObj->debug_query = false;
    $rs_list = $NetworkObj->recordset_list();

    // Paging Total Records
    $total_record = $NetworkObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_list);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
	
        	$data[] = array(
                "iNetworkId" => $rs_list[$i]['iNetworkId'],
                "vName" => gen_strip_slash($rs_list[$i]['vName']),
                "iStatus" => $rs_list[$i]['iStatus']       
            );
        }
    }
    
   $result = array('data' => $data , 'total_record' => $total_record);

   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result);  
   
}else if($request_type == "network_delete"){

    $iNetworkId = $RES_PARA['iNetworkId'];
    $county_id = $RES_PARA['county_id'];

    //get data
    $NetworkObj->clear_variable();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr  = array();
    $where_arr[] = '"iNetworkId" = '.$iNetworkId.'';
    $NetworkObj->join_field = $join_fieds_arr;
    $NetworkObj->join = $join_arr;
    $NetworkObj->where = $where_arr;
    $NetworkObj->param['limit'] = ' LIMIT 1 ';
    $NetworkObj->setClause();
    $rs_data = $NetworkObj->recordset_list();

    //delete records
    $rs_tot = $NetworkObj->delete_records($iNetworkId);
    
    if($rs_tot){

        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "result" => $iNetworkId);
        //unlink(delete) file
        $filepath = $network_path."/";
        if($rs_data[0]['vFile'] != "" && file_exists($filepath.$rs_data[0]['vFile']) ){
             @unlink($filepath.$rs_data[0]['vFile']);
        }
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }    
}
else if($request_type == "network_add"){
    //echo "<pre>";print_r($FILES_PARA);exit;
    if($FILES_PARA["vFile"]['name'] != ""){
        //$filepath = create_image_folder($sess_iCountySaasId,$network_path);
        $filepath = $network_path;
        $file_arr = img_fileUpload("vFile", $filepath, '', $valid_ext = array('kml','kmz'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
   }
   //echo $file_name;exit;
   if($file_name != ""){
        $insert_arr = array(
            "vName"     => $RES_PARA['vName'],
            "vFile"     => $file_name,
            "iStatus"   => $RES_PARA['iStatus'],
        );

        $NetworkObj->insert_arr = $insert_arr;
        $NetworkObj->setClause();
        $iNetworkId = $NetworkObj->add_records();

        if($iNetworkId){
            $response_data = array("Code" => 200, "Message" => MSG_ADD, "iNetworkId" => $iNetworkId);
        }
        else{
            $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
        }
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => $file_msg);
    }
}
else if($request_type == "network_edit"){

    if($RES_PARA['iNetworkId'] > 0 || $RES_PARA != ""){
        $update_arr = array(
            "iNetworkId"     => $RES_PARA['iNetworkId'],
            "vName"     => $RES_PARA['vName'],
            "iStatus"   => $RES_PARA['iStatus'],
        );

        $NetworkObj->update_arr = $update_arr;
        $NetworkObj->setClause();
        $rs = $NetworkObj->update_records();

        if($rs){
            $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iNetworkId" => $RES_PARA['iNetworkId']);
        }
        else{
            $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
        }
    }else{
         $response_data = array("Code" => 500 , "Message" => "iNetworkId is missing");
    }
} else if($request_type == "network_map_data"){
   
    $iNetworkId = $RES_PARA['iNetworkId']; 
    $country_id = $RES_PARA['country_id']; 

    $jsonData =array();
    $NetworkObj->clear_variable();
    

    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr  = array();

    $where_arr[] = '"iNetworkId" = '.$iNetworkId.'';

    $NetworkObj->join_field = $join_fieds_arr;
    $NetworkObj->join = $join_arr;
    $NetworkObj->where = $where_arr;
    $NetworkObj->param['limit'] = 0;
    $NetworkObj->setClause();
    $rs_data = $NetworkObj->recordset_list();
    
    $ki = count($rs_data);
   
    if($ki > 0){
        for($k=0;$k<$ki;$k++){
            $filepath = $network_path."/";
            $file_url = "";
            if(file_exists($filepath.$rs_data[0]['vFile'])){
                $file_path = $network_path."/".$rs_data[0]['vFile'];
                $file_url = $network_url."/".$rs_data[0]['vFile'];
            }
            $rs_data[$k]['vFilePath'] = $file_url;
        }
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200 , "Message" => $message , "result" => $rs_data);
    }else{
        $code = 2104;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 500 , "Message" => $message , "result" => array());
    }
}else if($request_type == "network_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $iStatus = $RES_PARA['iStatus'];
    if($iStatus != ''){
        $where_arr[] = "network.\"iStatus\"='".$iStatus."'";
    }
    $NetworkObj->where = $where_arr;
    $NetworkObj->param['order_by'] = "network.\"vName\"";
    $NetworkObj->setClause();
    $rs_network = $NetworkObj->recordset_list();
    if($rs_network){
        $response_data = array("Code" => 200, "result" => $rs_network, "total_record" => count($rs_network));
    }else{
        $response_data = array("Code" => 500);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>