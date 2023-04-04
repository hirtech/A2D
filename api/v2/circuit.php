<?php
include_once($controller_path . "circuit.inc.php");
include_once($function_path . "image.inc.php");

$CircuitObj = new Circuit();

if($request_type == "circuit_list"){
	$where_arr = array();

    if(!empty($RES_PARA)){

        $vCircuitType       = $RES_PARA['vCircuitType'];
        $vNetwork           = $RES_PARA['vNetwork'];
        /*$vCircuitName       = $RES_PARA['vCircuitName'];
        $vName				= $RES_PARA['vName'];
        $tComments          = $RES_PARA['tComments'];*/

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
    }
    
    if ($vCircuitType != "") {
        $where_arr[] = "ct.\"iCircuitTypeId\"=" . $vCircuitType;
    }

    if ($vNetwork != "") {
        $where_arr[] = "network.\"iNetworkId\"=" . $vNetwork;
    }
	
    /*if ($vCircuitName != "") {
        $where_arr[] = "circuit.\"vCircuitName\" ILIKE '%".$vCircuitName."%' ";
    }
	
    if ($vName != "") {
        $where_arr[] = "circuit.\"vCircuitName\" ILIKE '%".$vName."%' ";
    }
	
    if ($tComments != "") {
        $where_arr[] = "circuit.\"vCircuitName\" ILIKE '%".$tComments."%' ";
    }*/
    
    switch ($display_order) {
        case "0":
            $sortname = "circuit.\"iCircuitId\"";
            break;
        case "1":
            $sortname = "ct.\"vCircuitType\"";
            break;
        case "2":
            $sortname = "network.\"vName\"";
            break;
        case "3":
            $sortname = "circuit.\"vCircuitName\"";
            break;
		case "4":
            $sortname = "circuit.\"vName\"";
            break;
		case "5":
            $sortname = "circuit.\"tComments\"";
            break;
        default:
            $sortname = "circuit.\"iCircuitId\"";
            break;
    } 

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 'network."vName" as "vNetwork"';
    $join_fieds_arr[] = 'network."iNetworkId"';
    $join_fieds_arr[] = 'ct."vCircuitType"';
    $join_arr[] = " LEFT JOIN circuit_type_mas ct ON circuit.\"iCircuitTypeId\" = ct.\"iCircuitTypeId\"";
    $join_arr[] = " LEFT JOIN network ON circuit.\"iNetworkId\" = network.\"iNetworkId\"";
    $CircuitObj->join_field = $join_fieds_arr;
    $CircuitObj->join = $join_arr;
    $CircuitObj->where = $where_arr;
    if ($order_by != "") {
        $CircuitObj->param['order_by'] = $order_by;
    }else {
        $CircuitObj->param['order_by'] = $sortname . " " . $dir;
    }
    $CircuitObj->param['limit'] = $limit;
    $CircuitObj->setClause();
    $CircuitObj->debug_query = false;
    $rs_list = $CircuitObj->recordset_list();

    // Paging Total Records
    $total_record = $CircuitObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_list);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
	        $data[] = array(
                "iCircuitId"        => $rs_list[$i]['iCircuitId'],
                "iCircuitTypeId"    => $rs_list[$i]['iCircuitTypeId'],
                "vCircuitType"      => $rs_list[$i]['vCircuitType'],
                "iNetworkId"        => $rs_list[$i]['iNetworkId'],
                "vNetwork"          => $rs_list[$i]['vNetwork'],
                "vCircuitName"      => $rs_list[$i]['vCircuitName'],
                "vName"				=> $rs_list[$i]['vName'],
                "tComments"			=> $rs_list[$i]['tComments'],
            );
        }
    }
    
   $result = array('data' => $data , 'total_record' => $total_record);

   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result);  
}else if($request_type == "circuit_delete"){
    $iCircuitId = $RES_PARA['iCircuitId'];
    $CircuitObj->clear_variable();
    $rs_db = $CircuitObj->delete_records($iCircuitId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iCircuitId" => $iCircuitId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }    
}else if($request_type == "circuit_add"){
    $insert_arr = array(
        "iCircuitTypeId"    => $RES_PARA['iCircuitTypeId'],
        "iNetworkId"        => $RES_PARA['iNetworkId'],
        "vCircuitName"      => $RES_PARA['vCircuitName'],
        "vName"             => $RES_PARA['vName'],
        "tComments"         => $RES_PARA['tComments'],
        "vFile"             => $RES_PARA['vFile'],
    );
    $CircuitObj->insert_arr = $insert_arr;
    $CircuitObj->setClause();
    $iCircuitId = $CircuitObj->add_records();
    if($iCircuitId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iCircuitId" => $iCircuitId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "circuit_edit"){
    $update_arr = array(
        "iCircuitId"        => $RES_PARA['iCircuitId'],
        "iCircuitTypeId"    => $RES_PARA['iCircuitTypeId'],
        "iNetworkId"        => $RES_PARA['iNetworkId'],
        "vCircuitName"      => $RES_PARA['vCircuitName'],
        "vName"             => $RES_PARA['vName'],
        "tComments"         => $RES_PARA['tComments'],
        "vFile"             => $RES_PARA['vFile'],
    );

    $CircuitObj->update_arr = $update_arr;
    $CircuitObj->setClause();
    $rs = $CircuitObj->update_records();

    if($rs){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iCircuitId" => $RES_PARA['iCircuitId']);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "circuit_delete_document"){
    $iCircuitId = $RES_PARA['iCircuitId'];
	$table_name = "circuit";
	
	$res = array();
	$res = img_doUnlinkImages("vFile", "iCircuitId", $table_name, $iCircuitId, $circuit_path, '', $ext="", $sizes="single");
	
	//echo "<pre>";print_r($res);exit;
    if(!empty($res) && $res[0] > 0){
        $response_data = array("Code" => 200, "Message" => "File Deleted Successfully.", "error" => 0);
    }else{
        $response_data = array("Code" => 500 , "Message" => "ERROR - in file delete.", "error" => 1);
    }    
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>