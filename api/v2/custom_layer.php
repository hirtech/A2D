<?php

include_once($controller_path . "custom_layer.inc.php");
include_once($function_path."image.inc.php");

$CustomLayerObj = new CustomLayer();

##Search Arary
$where_arr = array();

if($request_type == "custom_layer_list"){
	$where_arr = array();

   if(!empty($RES_PARA)){

        $iCLId              = $RES_PARA['iCLId'];
        $vName              = $RES_PARA['vName'];
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }
    
    if ($iCLId != "") {
        $where_arr[] = '"iCLId"='.$iCLId ;
    }

    if ($vName != "") {
        $where_arr[] = "\"vName\" ILIKE '" . $vName . "%'";
    }

    switch ($display_order) {
        case "0":
            $sortname = "\"iCLId\"";
            break;
        case "1":
            $sortname = "\"vName\"";
            break;
        case "2":
            $sortname = "\"iStatus\"";
            break;
        case "0":
            $sortname = "\"iCLId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $CustomLayerObj->join_field = $join_fieds_arr;
    $CustomLayerObj->join = $join_arr;
    $CustomLayerObj->where = $where_arr;
    $CustomLayerObj->param['order_by'] = $sortname . " " . $dir;
    $CustomLayerObj->param['limit'] = $limit;
    $CustomLayerObj->setClause();
    $CustomLayerObj->debug_query = false;
    $rs_list = $CustomLayerObj->recordset_list();

    // Paging Total Records
    $total_record = $CustomLayerObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_list);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
	
        	$data[] = array(
                "iCLId" => $rs_list[$i]['iCLId'],
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
   
}else if($request_type == "custom_layer_delete"){

    $iCLId = $RES_PARA['iCLId'];
    $county_id = $RES_PARA['county_id'];

    //get data
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

    //delete records
    $rs_tot = $CustomLayerObj->delete_records($iCLId);
    
    if($rs_tot){

        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "result" => $iCLId);
        //unlink(delete) file
        $filepath = $custom_layer_path.$county_id."/";
        if($rs_data[0]['vFile'] != "" && file_exists($filepath.$rs_data[0]['vFile']) ){
             @unlink($filepath.$rs_data[0]['vFile']);
        }
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }    
}
else if($request_type == "custom_layer_add"){
    if($FILES_PARA["vFile"]['name'] != ""){
        $filepath = create_image_folder($sess_iCountySaasId,$custom_layer_path);
        $file_arr = img_fileUpload("vFile", $filepath, '', $valid_ext = array('kml','kmz'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
   }

   if($file_name != ""){
        $insert_arr = array(
            "vName"     => $RES_PARA['vName'],
            "vFile"     => $file_name,
            "iStatus"   => $RES_PARA['iStatus'],
        );

        $CustomLayerObj->insert_arr = $insert_arr;
        $CustomLayerObj->setClause();
        $iCLId = $CustomLayerObj->add_records();

        if($iCLId){
            $response_data = array("Code" => 200, "Message" => MSG_ADD, "iCLId" => $iCLId);
        }
        else{
            $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
        }
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => $file_msg);
    }
}
else if($request_type == "custom_layer_edit"){

    if($RES_PARA['iCLId'] > 0 || $RES_PARA != ""){
        $update_arr = array(
            "iCLId"     => $RES_PARA['iCLId'],
            "vName"     => $RES_PARA['vName'],
            "iStatus"   => $RES_PARA['iStatus'],
        );

        $CustomLayerObj->update_arr = $update_arr;
        $CustomLayerObj->setClause();
        $rs = $CustomLayerObj->update_records();

        if($rs){
            $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iCLId" => $RES_PARA['iCLId']);
        }
        else{
            $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
        }
    }else{
         $response_data = array("Code" => 500 , "Message" => "iCLId is missing");
    }
} else if($request_type == "custom_layer_map_data"){
   
    $iCLId = $RES_PARA['iCLId']; 
    $country_id = $RES_PARA['country_id']; 

    $jsonData =array();
    $CustomLayerObj->clear_variable();
    

    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr  = array();

    $where_arr[] = '"iCLId" = '.$iCLId.'';

    $CustomLayerObj->join_field = $join_fieds_arr;
    $CustomLayerObj->join = $join_arr;
    $CustomLayerObj->where = $where_arr;
    $CustomLayerObj->param['limit'] = 0;
    $CustomLayerObj->setClause();
    $rs_data = $CustomLayerObj->recordset_list();
    
    $ki = count($rs_data);
   
    if($ki > 0){
        for($k=0;$k<$ki;$k++){
            $filepath = $custom_layer_path.$country_id."/";
            $file_url = "";
            if(file_exists($filepath.$rs_data[0]['vFile'])){
                $file_path = $custom_layer_path.$country_id."/".$rs_data[0]['vFile'];
                $file_url = $custom_layer_url.$country_id."/".$rs_data[0]['vFile'];
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
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>