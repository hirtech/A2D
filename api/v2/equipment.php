<?php
include_once($controller_path . "equipment.inc.php");
$EquipmentObj = new Equipment();

include_once($function_path."image.inc.php");

if($request_type == "equipment_list"){
    $EquipmentObj->clear_variable();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iEquipmentId		= $RES_PARA['iEquipmentId'];

        $vNetwork           = trim($RES_PARA['vNetwork']);
        $vOStatus           = trim($RES_PARA['vOStatus']);
        $vSModelName        = trim($RES_PARA['vSModelName']);
        $vMaterial          = trim($RES_PARA['vMaterial']);
        $vPType             = trim($RES_PARA['vPType']);
        $vGrounded          = trim($RES_PARA['vGrounded']);
        $vIType             = trim($RES_PARA['vIType']);
        $vLType             = trim($RES_PARA['vLType']);

        $vSerialNumber		= $RES_PARA['vSerialNumber'];
        $vMACAddress		= $RES_PARA['vMACAddress'];
        $vIPAddress			= $RES_PARA['vIPAddress'];
        $vSize				= $RES_PARA['vSize'];
        $vWeight			= $RES_PARA['vWeight'];
		$iSPremiseId        = $RES_PARA['iSPremiseId'];
		$PremiseFilterOpDD  = $RES_PARA['PremiseFilterOpDD'];
		$vPremiseName       = $RES_PARA['vPremiseName'];
        $NameFilterOpDD     = $RES_PARA['NameFilterOpDD'];
        $vName              = $RES_PARA['vName'];
        $CommentFilterOpDD  = $RES_PARA['CommentFilterOpDD'];
        $tComments          = $RES_PARA['tComments'];

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
        $iFieldmapPremiseId = $RES_PARA['iFieldmapPremiseId'];

    }

    if ($iFieldmapPremiseId != "") {
        $where_arr[] = 'equipment."iPremiseId"='.$iFieldmapPremiseId ;
    }

    if ($vNetwork != "") {
        $where_arr[] = "zone.\"iNetworkId\"=".$vNetwork;
    }
    if ($vOStatus != "") {
        $where_arr[] = "equipment.\"iOperationalStatusId\"=".$vOStatus;
    }
    if ($vSModelName != "") {
        $where_arr[] = "equipment.\"iEquipmentModelId\" =".$vSModelName;
    }
    if ($vMaterial != "") {
        $where_arr[] = "equipment.\"iMaterialId\"=".$vMaterial;
    }
    if ($vPType != "") {
        $where_arr[] = "equipment.\"iPowerId\"=".$vPType;
    }
    if ($vGrounded != "") {
        $where_arr[] = "equipment.\"iGrounded\"=".$vGrounded;
    }
    if ($vIType != "") {
        $where_arr[] = "equipment.\"iInstallTypeId\"=".$vIType;
    }
    if ($vLType != "") {
        $where_arr[] = "equipment.\"iLinkTypeId\"=".$vLType;
    }

    if ($vSerialNumber != "") {
        $where_arr[] = "equipment.\"vSerialNumber\" ILIKE '".$vSerialNumber."%'" ;
    }
    if ($vMACAddress != "") {
        $where_arr[] = "equipment.\"vMACAddress\" ILIKE '".$vMACAddress."%'" ;
    }
    if ($vIPAddress != "") {
        $where_arr[] = "equipment.\"vIPAddress\" ILIKE '".$vIPAddress."%'" ;
    }
	if ($vSize != "") {
        $where_arr[] = "equipment.\"vSize\" ILIKE '".$vSize."%'" ;
    }
	if ($vWeight != "") {
        $where_arr[] = "equipment.\"vWeight\" ILIKE '".$vWeight."%'" ;
    }
	if ($iSPremiseId != "") {
        $where_arr[] = 'equipment."iPremiseId"='.$iSPremiseId ;
    }

	if ($vPremiseName != "") {
        if ($PremiseFilterOpDD != "") {
            if ($PremiseFilterOpDD == "Begins") {
                $where_arr[] = 's."vName" ILIKE \''.$vPremiseName.'%\'';
            } else if ($PremiseFilterOpDD == "Ends") {
                $where_arr[] = 's."vName"  ILIKE \'%'.$vPremiseName.'\'';
            } else if ($PremiseFilterOpDD == "Contains") {
                $where_arr[] = 's."vName"  ILIKE \'%'.$vPremiseName.'%\'';
            } else if ($PremiseFilterOpDD == "Exactly") {
                $where_arr[] = 's."vName" = \''.$vPremiseName.'\'';
            }
        } else {
            $where_arr[] = 's."vName" ILIKE \''.$vPremiseName.'%\'';
        }
    }

    if ($vName != "") {
        if ($NameFilterOpDD != "") {
            if ($NameFilterOpDD == "Begins") {
                $where_arr[] = 'equipment."vName" ILIKE \''.$vName.'%\'';
            } else if ($NameFilterOpDD == "Ends") {
                $where_arr[] = 'equipment."vName"  ILIKE \'%'.$vName.'\'';
            } else if ($NameFilterOpDD == "Contains") {
                $where_arr[] = 'equipment."vName"  ILIKE \'%'.$vName.'%\'';
            } else if ($NameFilterOpDD == "Exactly") {
                $where_arr[] = 'equipment."vName" = \''.$vName.'\'';
            }
        } else {
            $where_arr[] = 'equipment."vName" ILIKE \''.$vName.'%\'';
        }
    }

    if ($tComments != "") {
        if ($CommentFilterOpDD != "") {
            if ($CommentFilterOpDD == "Begins") {
                $where_arr[] = 'equipment."tComments" ILIKE \''.$tComments.'%\'';
            } else if ($CommentFilterOpDD == "Ends") {
                $where_arr[] = 'equipment."tComments"  ILIKE \'%'.$tComments.'\'';
            } else if ($CommentFilterOpDD == "Contains") {
                $where_arr[] = 'equipment."tComments"  ILIKE \'%'.$tComments.'%\'';
            } else if ($CommentFilterOpDD == "Exactly") {
                $where_arr[] = 'equipment."tComments" = \''.$tComments.'\'';
            }
        } else {
            $where_arr[] = 'equipment."tComments" ILIKE \''.$tComments.'%\'';
        }
    }

    switch ($display_order) {
        case "0":
            $sortname = "equipment.\"iEquipmentId\"";
            break;
        case "1":
            $sortname = "em.\"vModelName\"";
            break;
         case "2":
            $sortname = "equipment.\"vSerialNumber\"";
            break;
        case "3":
            $sortname = "equipment.\"dPurchaseDate\"";
            break;
        case "4":
            $sortname = "equipment.\"dWarrantyExpiration\"";
            break;
        case "5":
            $sortname = "equipment.\"iPremiseId\"";
            break;
        case "6":
            $sortname = "equipment.\"iPremiseCircuitId\"";
            break;
		case "7":
            $sortname = "os.\"vOperationalStatus\"";
            break;
        default:
            $sortname = "equipment.\"iEquipmentId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 'em."vModelName"';
    $join_fieds_arr[] = 'm."vMaterial"';
    $join_fieds_arr[] = 'p."vPower"';
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
	$join_fieds_arr[] = 'it."vInstallType"';
	$join_fieds_arr[] = 'lt."vLinkType"';
	$join_fieds_arr[] = 'os."vOperationalStatus"';
    $join_fieds_arr[] = 'zone."iNetworkId"';
    $join_fieds_arr[] = 'circuit."vCircuitName"';
    $join_arr[] = 'LEFT JOIN equipment_model em on equipment."iEquipmentModelId" = em."iEquipmentModelId"';
    $join_arr[] = 'LEFT JOIN material_mas m on equipment."iMaterialId" = m."iMaterialId"';
    $join_arr[] = 'LEFT JOIN power_mas p on equipment."iPowerId" = p."iPowerId"';
    $join_arr[] = 'LEFT JOIN premise_mas s on equipment."iPremiseId" = s."iPremiseId"';
    $join_arr[] = 'LEFT JOIN zone on s."iZoneId" = zone."iZoneId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN install_type_mas it on equipment."iInstallTypeId" = it."iInstallTypeId"';
    $join_arr[] = 'LEFT JOIN link_type_mas lt on equipment."iLinkTypeId" = lt."iLinkTypeId"';
    $join_arr[] = 'LEFT JOIN operational_status_mas os on equipment."iOperationalStatusId" = os."iOperationalStatusId"';
    $join_arr[] = 'LEFT JOIN premise_circuit on equipment."iPremiseCircuitId" = premise_circuit."iPremiseCircuitId"';
    $join_arr[] = 'LEFT JOIN circuit on premise_circuit."iCircuitId" = circuit."iCircuitId"';
    $EquipmentObj->join_field = $join_fieds_arr;
    $EquipmentObj->join = $join_arr;
    $EquipmentObj->where = $where_arr;
    $EquipmentObj->param['order_by'] = $sortname . " " . $dir;
    $EquipmentObj->param['limit'] = $limit;
    $EquipmentObj->setClause();
    $EquipmentObj->debug_query = false;
    $rs_equipment = $EquipmentObj->recordset_list();
	//echo "<pre>";print_r($rs_equipment);exit;
    // Paging Total Records
    $total = $EquipmentObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_equipment);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $data[] = array(
                "iEquipmentId"			=> $rs_equipment[$i]['iEquipmentId'],
                "iEquipmentModelId"     => $rs_equipment[$i]['iEquipmentModelId'],
                "vModelName"			=> $rs_equipment[$i]['vModelName'],
                "vSerialNumber"         => $rs_equipment[$i]['vSerialNumber'],
                "vMACAddress"           => $rs_equipment[$i]['vMACAddress'],
                "vIPAddress"            => $rs_equipment[$i]['vIPAddress'],
                "vSize"					=> $rs_equipment[$i]['vSize'],
                "vWeight"               => $rs_equipment[$i]['vWeight'],
                "iMaterialId"           => $rs_equipment[$i]['iMaterialId'],
                "vMaterial"				=> $rs_equipment[$i]['vMaterial'],
                "iPowerId"				=> $rs_equipment[$i]['iPowerId'],
                "vPower"				=> $rs_equipment[$i]['vPower'],
                "iGrounded"				=> $rs_equipment[$i]['iGrounded'],
                "dInstallByDate"		=> $rs_equipment[$i]['dInstallByDate'],
                "dInstalledDate"		=> $rs_equipment[$i]['dInstalledDate'],
                "vPurchaseCost"			=> $rs_equipment[$i]['vPurchaseCost'],
                "dPurchaseDate"			=> $rs_equipment[$i]['dPurchaseDate'],
                "dWarrantyExpiration"   => $rs_equipment[$i]['dWarrantyExpiration'],
                "vWarrantyCost"			=> $rs_equipment[$i]['vWarrantyCost'],
                "iPremiseId"			=> $rs_equipment[$i]['iPremiseId'],
                "vPremiseName"			=> $rs_equipment[$i]['vPremiseName'],
                "vPremiseType"			=> $rs_equipment[$i]['vPremiseType'],
                "iInstallTypeId"		=> $rs_equipment[$i]['iInstallTypeId'],
                "vInstallType"			=> $rs_equipment[$i]['vInstallType'],
                "iPremiseCircuitId"		=> $rs_equipment[$i]['iPremiseCircuitId'],
                "iLinkTypeId"			=> $rs_equipment[$i]['iLinkTypeId'],
                "vLinkType"				=> $rs_equipment[$i]['vLinkType'],
                "dProvisionDate"		=> $rs_equipment[$i]['dProvisionDate'],
                "iOperationalStatusId"  => $rs_equipment[$i]['iOperationalStatusId'],
                "vOperationalStatus"	=> $rs_equipment[$i]['vOperationalStatus'],
                "iNetworkId"            => $rs_equipment[$i]['iNetworkId'],
                "vCircuitName"          => $rs_equipment[$i]['vCircuitName'],
                "vName"                 => $rs_equipment[$i]['vName'],
                "tComments"             => $rs_equipment[$i]['tComments'],
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);
    
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "equipment_edit"){
	$EquipmentObj->clear_variable();
   	$update_arr = array(
        "iEquipmentId"				=> $RES_PARA['iEquipmentId'],
        "iEquipmentModelId"         => $RES_PARA['iEquipmentModelId'],
        "vSerialNumber"             => $RES_PARA['vSerialNumber'],
        "vMACAddress"				=> $RES_PARA['vMACAddress'],
        "vIPAddress"			    => $RES_PARA['vIPAddress'],
        "vSize"						=> $RES_PARA['vSize'],
        "vWeight"					=> $RES_PARA['vWeight'],
        "iMaterialId"			    => $RES_PARA['iMaterialId'],
        "iPowerId"			        => $RES_PARA['iPowerId'],
        "iGrounded"					=> $RES_PARA['iGrounded'],
        "dInstallByDate"			=> $RES_PARA['dInstallByDate'],
        "dInstalledDate"			=> $RES_PARA['dInstalledDate'],
        "vPurchaseCost"				=> $RES_PARA['vPurchaseCost'],
        "dPurchaseDate"				=> $RES_PARA['dPurchaseDate'],
        "dWarrantyExpiration"		=> $RES_PARA['dWarrantyExpiration'],
        "vWarrantyCost"				=> $RES_PARA['vWarrantyCost'],
        "iPremiseId"				=> $RES_PARA['iPremiseId'],
        "iInstallTypeId"			=> $RES_PARA['iInstallTypeId'],
        "iPremiseCircuitId"			=> $RES_PARA['iPremiseCircuitId'],
        "iLinkTypeId"				=> $RES_PARA['iLinkTypeId'],
        "dProvisionDate"			=> $RES_PARA['dProvisionDate'],
        "iOperationalStatusId"		=> $RES_PARA['iOperationalStatusId'],
        "vName"                     => $RES_PARA['vName'],
        "tComments"                 => $RES_PARA['tComments'],
        "vFile"                     => $RES_PARA['vFile'],
    );

   $EquipmentObj->update_arr = $update_arr;
   $EquipmentObj->setClause();
   $rs_db = $EquipmentObj->update_records();
   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iEquipmentId" => $RES_PARA['iEquipmentId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}else if($request_type == "equipment_delete"){
   	$iEquipmentId = $RES_PARA['iEquipmentId'];
	$EquipmentObj->clear_variable();
    $rs_db = $EquipmentObj->delete_records($iEquipmentId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iEquipmentId" => $iEquipmentId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "equipment_add") {
    $EquipmentObj->clear_variable();
    $insert_arr = array(
        "iEquipmentModelId"         => $RES_PARA['iEquipmentModelId'],
        "vSerialNumber"             => $RES_PARA['vSerialNumber'],
        "vMACAddress"				=> $RES_PARA['vMACAddress'],
        "vIPAddress"			    => $RES_PARA['vIPAddress'],
        "vSize"						=> $RES_PARA['vSize'],
        "vWeight"					=> $RES_PARA['vWeight'],
        "iMaterialId"			    => $RES_PARA['iMaterialId'],
        "iPowerId"			        => $RES_PARA['iPowerId'],
        "iGrounded"					=> $RES_PARA['iGrounded'],
        "dInstallByDate"			=> $RES_PARA['dInstallByDate'],
        "dInstalledDate"			=> $RES_PARA['dInstalledDate'],
        "vPurchaseCost"				=> $RES_PARA['vPurchaseCost'],
        "dPurchaseDate"				=> $RES_PARA['dPurchaseDate'],
        "dWarrantyExpiration"		=> $RES_PARA['dWarrantyExpiration'],
        "vWarrantyCost"				=> $RES_PARA['vWarrantyCost'],
        "iPremiseId"				=> $RES_PARA['iPremiseId'],
        "iInstallTypeId"			=> $RES_PARA['iInstallTypeId'],
        "iPremiseCircuitId"			=> $RES_PARA['iPremiseCircuitId'],
        "iLinkTypeId"				=> $RES_PARA['iLinkTypeId'],
        "dProvisionDate"			=> $RES_PARA['dProvisionDate'],
        "iOperationalStatusId"		=> $RES_PARA['iOperationalStatusId'],
        "vName"                     => $RES_PARA['vName'],
        "tComments"                 => $RES_PARA['tComments'],
        "vFile"                     => $RES_PARA['vFile'],
    );

    $EquipmentObj->insert_arr = $insert_arr;
    $EquipmentObj->setClause();
    $iEquipmentId = $EquipmentObj->add_records();
    if($iEquipmentId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iEquipmentId" => $iEquipmentId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "equipment_delete_document"){
    $iEquipmentId = $RES_PARA['iEquipmentId'];
    $table_name = "equipment";
    
    $res = array();
    $res = img_doUnlinkImages("vFile", "iEquipmentId", $table_name, $iEquipmentId, $equipment_path, '', $ext="", $sizes="single");
    
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