<?php
include_once($controller_path . "premise_circuit.inc.php");

$PremiseCircuitObj = new PremiseCircuit();

if($request_type == "premise_circuit_list"){
	$where_arr = array();

    if(!empty($RES_PARA)){
        $iPremiseCircuitId  = $RES_PARA['iPremiseCircuitId'];
        $iPremiseId         = $RES_PARA['iPremiseId'];
        $vPremise           = $RES_PARA['vPremise'];
        $iWOId              = $RES_PARA['iWOId'];
        $vWorkOrderType     = $RES_PARA['vWorkOrderType'];
        $iCircuitId         = $RES_PARA['iCircuitId'];
        $vCircuitName       = $RES_PARA['vCircuitName'];

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }
    
    if ($iPremiseCircuitId != "") {
        $where_arr[] = 'premise_circuit."iPremiseCircuitId"='.$iPremiseCircuitId ;
    }

    if ($iPremiseId != "") {
        $where_arr[] = 'so."iPremiseId"='.$iPremiseId ;
    }

    if ($vPremise != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vPremise . "%'";
    }

    if ($iWOId != "") {
        $where_arr[] = 'premise_circuit."iWOId"='.$iWOId ;
    }

    if ($vWorkOrderType != "") {
        $where_arr[] = "wt.\"vType\" ILIKE '" . $vWorkOrderType . "%'";
    }

    if ($iCircuitId != "") {
        $where_arr[] = 'premise_circuit."iCircuitId"='.$iCircuitId ;
    }

    if ($vCircuitName != "") {
        $where_arr[] = "circuit.\"vCircuitName\" ILIKE '" . $vCircuitName . "%'";
    }

    switch ($display_order) {
        case "0":
            $sortname = "premise_circuit.\"iPremiseCircuitId\"";
            break;
        case "1":
            $sortname = "so.\"iPremiseId\"";
            break;
        case "2":
            $sortname = "premise_circuit.\"iWOId\"";
            break;
        case "3":
            $sortname = "circuit.\"vCircuitName\"";
            break;
        default:
            $sortname = "premise_circuit.\"iPremiseCircuitId\"";
            break;
    } 

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 'wt."vType" as "vWorkOrderType"';
    $join_fieds_arr[] = 'so."iPremiseId"';
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
    $join_fieds_arr[] = 'circuit."vCircuitName"';
    
    $join_arr[] = " LEFT JOIN workorder w ON premise_circuit.\"iWOId\" = w.\"iWOId\"";
    $join_arr[] = " LEFT JOIN workorder_type_mas wt ON w.\"iWOTId\" = wt.\"iWOTId\"";
    $join_arr[] = " LEFT JOIN service_order so ON w.\"iServiceOrderId\" = so.\"iServiceOrderId\"";
    $join_arr[] = " LEFT JOIN premise_mas s ON so.\"iPremiseId\" = s.\"iPremiseId\"";
    $join_arr[] = " LEFT JOIN site_type_mas st ON s.\"iSTypeId\" = st.\"iSTypeId\"";
    $join_arr[] = " LEFT JOIN circuit ON premise_circuit.\"iCircuitId\" = circuit.\"iCircuitId\"";
    $PremiseCircuitObj->join_field = $join_fieds_arr;
    $PremiseCircuitObj->join = $join_arr;
    $PremiseCircuitObj->where = $where_arr;
    $PremiseCircuitObj->param['order_by'] = $sortname . " " . $dir;
    $PremiseCircuitObj->param['limit'] = $limit;
    $PremiseCircuitObj->setClause();
    $PremiseCircuitObj->debug_query = false;
    $rs_list = $PremiseCircuitObj->recordset_list();

    // Paging Total Records
    $total_record = $PremiseCircuitObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_list);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){

	        $data[] = array(
                "iPremiseCircuitId"     => $rs_list[$i]['iPremiseCircuitId'],
                "iWOId"                 => $rs_list[$i]['iWOId'],
                "vWorkOrderType"        => $rs_list[$i]['vWorkOrderType'],
                "iPremiseId"            => $rs_list[$i]['iPremiseId'],
                "vPremiseName"          => $rs_list[$i]['vPremiseName'],
                "vPremiseType"          => $rs_list[$i]['vPremiseType'],
                "iCircuitId"            => $rs_list[$i]['iCircuitId'],
                "vCircuitName"          => $rs_list[$i]['vCircuitName'],
            );
        }
    }
    
   $result = array('data' => $data , 'total_record' => $total_record);

   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result);  
}else if($request_type == "premise_circuit_delete"){
    $iPremiseCircuitId = $RES_PARA['iPremiseCircuitId'];
    $PremiseCircuitObj->clear_variable();
    $rs_db = $PremiseCircuitObj->delete_records($iPremiseCircuitId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iPremiseCircuitId" => $iPremiseCircuitId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }    
}else if($request_type == "premise_circuit_add"){
	//The network from the workorder/premise
	$sql_wonetwork = "SELECT z.\"iNetworkId\" FROM workorder w JOIN service_order so ON w.\"iServiceOrderId\" = so.\"iServiceOrderId\" JOIN premise_mas s ON so.\"iPremiseId\" = s.\"iPremiseId\" JOIN zone z ON s.\"iZoneId\" = z.\"iZoneId\" WHERE w.\"iWOId\" = '".$RES_PARA['iWOId']."' ORDER BY z.\"iNetworkId\" DESC LIMIT 1 ";
	$rs_wonetwork = $sqlObj->GetAll($sql_wonetwork);
	//echo $sql_wonetwork."<pre>";print_r($rs_wonetwork);
	$iWONetworkId = 0;
	if(!empty($sql_wonetwork)) {
		$iWONetworkId = $rs_wonetwork[0]['iNetworkId'];
	}

	//The network of the circuit
	$sql_cnetwork = "SELECT c.\"iNetworkId\" FROM circuit c  WHERE c.\"iCircuitId\" = '".$RES_PARA['iCircuitId']."' ORDER BY c.\"iNetworkId\" DESC LIMIT 1 ";
	$rs_cnetwork = $sqlObj->GetAll($sql_cnetwork);
	//echo $sql_cnetwork."<pre>";print_r($rs_cnetwork);;
	$iCNetworkId = 0;
	if(!empty($rs_cnetwork)) {
		$iCNetworkId = $rs_cnetwork[0]['iNetworkId'];
	}
	$matching_network = 0;
	//echo $iWONetworkId ." == ".$iCNetworkId;exit;
	if($iWONetworkId == $iCNetworkId) {
		$matching_network = 1;
		$insert_arr = array(
			"iWOId"				=> $RES_PARA['iWOId'],
			"iCircuitId"		=> $RES_PARA['iCircuitId'],
		);
		$PremiseCircuitObj->insert_arr = $insert_arr;
		$PremiseCircuitObj->setClause();
		$iPremiseCircuitId = $PremiseCircuitObj->add_records();
		if($iPremiseCircuitId){
			$response_data = array("Code" => 200, "Message" => MSG_ADD, "iPremiseCircuitId" => $iPremiseCircuitId, "matching_network" => $matching_network);
		}
		else{
			$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "matching_network" => $matching_network);
		}
	}else {
		$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "matching_network" => $matching_network);
	}
}else if($request_type == "premise_circuit_edit"){
	//The network from the workorder/premise
    $sql_wonetwork = "SELECT z.\"iNetworkId\" FROM workorder w JOIN service_order so ON w.\"iServiceOrderId\" = so.\"iServiceOrderId\" JOIN premise_mas s ON so.\"iPremiseId\" = s.\"iPremiseId\" JOIN zone z ON s.\"iZoneId\" = z.\"iZoneId\" WHERE w.\"iWOId\" = '".$RES_PARA['iWOId']."' ORDER BY z.\"iNetworkId\" DESC LIMIT 1 ";
    $rs_wonetwork = $sqlObj->GetAll($sql_wonetwork);
    //echo $sql_wonetwork."<pre>";print_r($rs_wonetwork);
    $iWONetworkId = 0;
    if(!empty($sql_wonetwork)) {
        $iWONetworkId = $rs_wonetwork[0]['iNetworkId'];
    }

    //The network of the circuit
    $sql_cnetwork = "SELECT c.\"iNetworkId\" FROM circuit c  WHERE c.\"iCircuitId\" = '".$RES_PARA['iCircuitId']."' ORDER BY c.\"iNetworkId\" DESC LIMIT 1 ";
    $rs_cnetwork = $sqlObj->GetAll($sql_cnetwork);
    //echo $sql_cnetwork."<pre>";print_r($rs_cnetwork);;
    $iCNetworkId = 0;
    if(!empty($rs_cnetwork)) {
        $iCNetworkId = $rs_cnetwork[0]['iNetworkId'];
    }
    $matching_network = 0;
    //echo $iWONetworkId ." == ".$iCNetworkId;exit;
    if($iWONetworkId == $iCNetworkId) {
		$matching_network = 1;
		$update_arr = array(
			"iPremiseCircuitId" => $RES_PARA['iPremiseCircuitId'],
            "iWOId"             => $RES_PARA['iWOId'],
            "iCircuitId"        => $RES_PARA['iCircuitId'],
		);

		$PremiseCircuitObj->update_arr = $update_arr;
		$PremiseCircuitObj->setClause();
		$rs = $PremiseCircuitObj->update_records();
        
		if($rs){
			$response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iPremiseCircuitId" => $RES_PARA['iPremiseCircuitId'], "matching_network" => $matching_network);
		}
		else{
			$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "matching_network" => $matching_network);
		}
	}else{
		$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "matching_network" => $matching_network);
	}
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>