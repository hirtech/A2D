<?php
include_once($controller_path . "premise_circuit.inc.php");

$PremiseCircuitObj = new PremiseCircuit();

if($request_type == "premise_circuit_list"){
	$where_arr = array();

    if(!empty($RES_PARA)){
        $iPremiseCircuitId      = $RES_PARA['iPremiseCircuitId'];
        $iPremiseId             = $RES_PARA['iPremiseId'];
        $vPremise               = $RES_PARA['vPremise'];
        $iWOId                  = $RES_PARA['iWOId'];
        $vWorkOrderType         = $RES_PARA['vWorkOrderType'];
        $iCircuitId             = $RES_PARA['iCircuitId'];
        $vCircuitName           = $RES_PARA['vCircuitName'];
        $iConnectionTypeId      = $RES_PARA['iConnectionTypeId'];
        $vConnectionTypeName    = $RES_PARA['vConnectionTypeName'];
        $vStatus                = $RES_PARA['vStatus'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];
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

    if ($iConnectionTypeId != "") {
        $where_arr[] = 'premise_circuit."iConnectionTypeId"='.$iConnectionTypeId ;
    }

    if ($vConnectionTypeName != "") {
        $where_arr[] = "connection_type_mas.\"vConnectionTypeName\" ILIKE '" . $vConnectionTypeName . "%'";
    }

    if($vStatus != ''){
        if(strtolower($vStatus) == 'created' || $vStatuss == 'Created'){
            $where_arr[] = 'premise_circuit."iStatus"= 1'; 
        }else if(strtolower($vStatus) == 'in progress' || $vStatuss == 'In Progress'){
            $where_arr[] = 'premise_circuit."iStatus"= 2'; 
        }else if(strtolower($vStatus) == 'delayed' || $vStatuss == 'Delayed'){
            $where_arr[] = 'premise_circuit."iStatus"= 3'; 
        }else if(strtolower($vStatus) == 'connected' || $vStatuss == 'Connected'){
            $where_arr[] = 'premise_circuit."iStatus"= 4'; 
        }else if(strtolower($vStatus) == 'active' || $vStatuss == 'Active'){
            $where_arr[] = 'premise_circuit."iStatus"= 5'; 
        }else if(strtolower($vStatus) == 'suspended' || $vStatuss == 'Suspended'){
            $where_arr[] = 'premise_circuit."iStatus"= 6'; 
        }else if(strtolower($vStatus) == 'trouble' || $vStatuss == 'Trouble'){
            $where_arr[] = 'premise_circuit."iStatus"= 7'; 
        }else if(strtolower($vStatus) == 'disconnected' || $vStatuss == 'Disconnected'){
            $where_arr[] = 'premise_circuit."iStatus"= 8'; 
        }
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
        case "4":
            $sortname = "connection_type_mas.\"vConnectionTypeName\"";
            break;
         case "7":
            $sortname = "premise_circuit.\"iStatus\"";
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
    $join_fieds_arr[] = 'connection_type_mas."vConnectionTypeName"';
    
    $join_arr[] = " LEFT JOIN workorder w ON premise_circuit.\"iWOId\" = w.\"iWOId\"";
    $join_arr[] = " LEFT JOIN workorder_type_mas wt ON w.\"iWOTId\" = wt.\"iWOTId\"";
    $join_arr[] = " LEFT JOIN service_order so ON w.\"iServiceOrderId\" = so.\"iServiceOrderId\"";
    $join_arr[] = " LEFT JOIN premise_mas s ON so.\"iPremiseId\" = s.\"iPremiseId\"";
    $join_arr[] = " LEFT JOIN site_type_mas st ON s.\"iSTypeId\" = st.\"iSTypeId\"";
    $join_arr[] = " LEFT JOIN circuit ON premise_circuit.\"iCircuitId\" = circuit.\"iCircuitId\"";
    $join_arr[] = " LEFT JOIN connection_type_mas ON premise_circuit.\"iConnectionTypeId\" = connection_type_mas.\"iConnectionTypeId\"";
    $PremiseCircuitObj->join_field = $join_fieds_arr;
    $PremiseCircuitObj->join = $join_arr;
    $PremiseCircuitObj->where = $where_arr;
    $PremiseCircuitObj->param['order_by'] = $sortname . " " . $dir;
    $PremiseCircuitObj->param['limit'] = $limit;
    $PremiseCircuitObj->setClause();
    $PremiseCircuitObj->debug_query = false;
    $rs_list = $PremiseCircuitObj->recordset_list();
    // echo "<pre>"; print_r($rs_list);exit();
    // Paging Total Records
    $total_record = $PremiseCircuitObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_list);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $sql_comp = 'SELECT DISTINCT(ps."iServiceOrderId"),ps."iWOId",cm."vCompanyName",stm."vServiceType" FROM premise_circuit pc JOIN premise_services ps ON pc."iPremiseCircuitId"= ps."iPremiseCircuitId" LEFT JOIN company_mas cm ON ps."iCarrierId"= cm."iCompanyId" LEFT JOIN service_type_mas stm ON ps."iServiceTypeId"= stm."iServiceTypeId" WHERE pc."iPremiseCircuitId"='.$rs_list[$i]['iPremiseCircuitId'].' AND ps."dStartDate" IS NOT NULL';
            $rs_comp = $sqlObj->GetAll($sql_comp);
            // echo "<pre>"; print_r($rs_comp);exit();
            $vCarrierServices = "";
            if(count($rs_comp) > 0){
                for($j=0;$j<count($rs_comp);$j++){
                    if($rs_comp[$j]['iServiceOrderId'] > 0 && $rs_comp[$j]['iWOId'] > 0 && $rs_comp[$j]['vCompanyName'] != "" && $rs_comp[$j]['vServiceType'] != ""){
                        $vCarrierServices .= "SO#".$rs_comp[$j]['iServiceOrderId']." | WO#".$rs_comp[$j]['iWOId']." | ".$rs_comp[$j]['vCompanyName']." | ".$rs_comp[$j]['vServiceType']."<br>";
                    }else{
                        $vCarrierServices .= "---";
                    }
                }
            }

            $sql_equipment = 'SELECT e."iEquipmentId", e."vMACAddress",em."vModelName",em."iEquipmentModelId" FROM premise_circuit pc LEFT JOIN equipment e ON pc."iPremiseCircuitId"= e."iPremiseCircuitId" LEFT JOIN equipment_model em ON e."iEquipmentModelId"= em."iEquipmentModelId" WHERE pc."iPremiseCircuitId"='.$rs_list[$i]['iPremiseCircuitId'];
            $rs_equipment = $sqlObj->GetAll($sql_equipment);
            $vEquipment = "";
            if(count($rs_equipment) > 0){
                for($k=0;$k<count($rs_equipment);$k++){
                    if($rs_equipment[$k]['iEquipmentModelId'] > 0 && $rs_equipment[$k]['vMACAddress'] != "" && $rs_equipment[$k]['vModelName'] != ""){

                        $vEqupment_url = $site_url."service_order/equipment_add&mode=Update&iEquipmentId=".$rs_equipment[$k]['iEquipmentId'];
                        $vEquipment .= "<a class='text-primary' href='".$vEqupment_url."' target='_blank'>ID#".$rs_equipment[$k]['iEquipmentId']." | ".$rs_equipment[$k]['vModelName']." | ".$rs_equipment[$k]['vMACAddress']."<br>";
                    }else{
                        $vEquipment .= "---";
                    }
                }
            }

	        $data[] = array(
                "iPremiseCircuitId"     => $rs_list[$i]['iPremiseCircuitId'],
                "iWOId"                 => $rs_list[$i]['iWOId'],
                "vWorkOrderType"        => $rs_list[$i]['vWorkOrderType'],
                "iPremiseId"            => $rs_list[$i]['iPremiseId'],
                "vPremiseName"          => $rs_list[$i]['vPremiseName'],
                "vPremiseType"          => $rs_list[$i]['vPremiseType'],
                "iCircuitId"            => $rs_list[$i]['iCircuitId'],
                "vCircuitName"          => $rs_list[$i]['vCircuitName'],
                "iConnectionTypeId"     => $rs_list[$i]['iConnectionTypeId'],
                "vConnectionTypeName"   => $rs_list[$i]['vConnectionTypeName'],
                "vCarrierServices"      => $vCarrierServices,
                "vEquipment"            => $vEquipment,
                "iStatus"               => $rs_list[$i]['iStatus'],
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
	$sql_wonetwork = "SELECT z.\"iNetworkId\", w.\"iPremiseId\" FROM workorder w JOIN service_order so ON w.\"iServiceOrderId\" = so.\"iServiceOrderId\" JOIN premise_mas s ON so.\"iPremiseId\" = s.\"iPremiseId\" JOIN zone z ON s.\"iZoneId\" = z.\"iZoneId\" WHERE w.\"iWOId\" = '".$RES_PARA['iWOId']."' ORDER BY z.\"iNetworkId\" DESC LIMIT 1 ";
	$rs_wonetwork = $sqlObj->GetAll($sql_wonetwork);
	//echo $sql_wonetwork."<pre>";print_r($rs_wonetwork);
	$iWONetworkId = 0;
    $iPremiseId = 0;
	if(!empty($sql_wonetwork)) {
		$iWONetworkId = $rs_wonetwork[0]['iNetworkId'];
        $iPremiseId = $rs_wonetwork[0]['iPremiseId'];
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
			"iWOId"			    => $RES_PARA['iWOId'],
            "iPremiseId"        => $iPremiseId,
			"iCircuitId"        => $RES_PARA['iCircuitId'],
            "iConnectionTypeId" => $RES_PARA['iConnectionTypeId'],
            "iStatus"           => $RES_PARA['iStatus'],
            "iLoginUserId"      => $RES_PARA['iLoginUserId'],
		);
		$PremiseCircuitObj->insert_arr = $insert_arr;
		$PremiseCircuitObj->setClause();
		$iPremiseCircuitId = $PremiseCircuitObj->add_records();
		if($iPremiseCircuitId){
			$response_data = array("Code" => 200, "Message" => MSG_ADD, "iPremiseCircuitId" => $iPremiseCircuitId, "iPremiseId" => $iPremiseId, "matching_network" => $matching_network);
		}
		else{
			$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "iPremiseId" => $iPremiseId, "matching_network" => $matching_network);
		}
	}else {
		$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "iPremiseId" => $iPremiseId, "matching_network" => $matching_network);
	}
}else if($request_type == "premise_circuit_edit"){
	//The network from the workorder/premise
    $sql_wonetwork = "SELECT z.\"iNetworkId\", w.\"iPremiseId\" FROM workorder w JOIN service_order so ON w.\"iServiceOrderId\" = so.\"iServiceOrderId\" JOIN premise_mas s ON so.\"iPremiseId\" = s.\"iPremiseId\" JOIN zone z ON s.\"iZoneId\" = z.\"iZoneId\" WHERE w.\"iWOId\" = '".$RES_PARA['iWOId']."' ORDER BY z.\"iNetworkId\" DESC LIMIT 1 ";
    $rs_wonetwork = $sqlObj->GetAll($sql_wonetwork);
    //echo $sql_wonetwork."<pre>";print_r($rs_wonetwork);
    $iWONetworkId = 0;
    $iPremiseId = 0;
    if(!empty($sql_wonetwork)) {
        $iWONetworkId = $rs_wonetwork[0]['iNetworkId'];
        $iPremiseId = $rs_wonetwork[0]['iPremiseId'];
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
            "iPremiseId"        => $iPremiseId,
            "iCircuitId"        => $RES_PARA['iCircuitId'],
            "iConnectionTypeId" => $RES_PARA['iConnectionTypeId'],
            "iStatus"           => $RES_PARA['iStatus'],
            "iLoginUserId"      => $RES_PARA['iLoginUserId'],
		);

		$PremiseCircuitObj->update_arr = $update_arr;
		$PremiseCircuitObj->setClause();
		$rs = $PremiseCircuitObj->update_records();
        
		if($rs){
			$response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iPremiseCircuitId" => $RES_PARA['iPremiseCircuitId'], "iPremiseId" => $iPremiseId, "matching_network" => $matching_network);
		}
		else{
			$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "iPremiseId" => $iPremiseId, "matching_network" => $matching_network);
		}
	}else{
		$response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "iPremiseId" => $iPremiseId, "matching_network" => $matching_network);
	}
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>