<?php
include_once($controller_path . "equipment.inc.php");
$EquipmentObj = new Equipment();
if($request_type == "equipment_list"){
    $EquipmentObj->clear_variable();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iEquipmentId		= $RES_PARA['iEquipmentId'];
        $vModelName			= $RES_PARA['vModelName'];
        $vSerialNumber		= $RES_PARA['vSerialNumber'];
        $vMACAddress		= $RES_PARA['vMACAddress'];
        $vIPAddress			= $RES_PARA['vIPAddress'];
        $vSize				= $RES_PARA['vSize'];
        $vWeight			= $RES_PARA['vWeight'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];
        $order_by               = $RES_PARA['order_by'];

		$iSEquipmentModelId		= $RES_PARA['iSEquipmentModelId'];
		$iSMaterialId			= $RES_PARA['iSMaterialId'];
		$iSPowerId              = $RES_PARA['iSPowerId'];
		$iSGrounded             = $RES_PARA['iSGrounded'];
		$iSPremiseId            = $RES_PARA['iSPremiseId'];
		$PremiseFilterOpDD      = $RES_PARA['PremiseFilterOpDD'];
		$vPremiseName           = $RES_PARA['vPremiseName'];
		$iSInstallTypeId        = $RES_PARA['iSInstallTypeId'];
		$iSLinkTypeId			= $RES_PARA['iSLinkTypeId'];
		$iSOperationalStatusId	= $RES_PARA['iSOperationalStatusId'];
    }

    if ($iEquipmentId != "") {
        $where_arr[] = 'equipment."iEquipmentId"='.$iEquipmentId ;
    }
    if ($vModelName != "") {
        $where_arr[] = "em.\"vModelName\" ILIKE '%".$vModelName."%'" ;
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
	if ($iSEquipmentModelId != "") {
        $where_arr[] = 'equipment."iEquipmentModelId"='.$iSEquipmentModelId ;
    }
	if ($iSMaterialId != "") {
        $where_arr[] = 'equipment."iMaterialId"='.$iSMaterialId ;
    }
	if ($iSPowerId != "") {
        $where_arr[] = 'equipment."iPowerId"='.$iSPowerId ;
    }
	if ($iSGrounded != "") {
        $where_arr[] = 'equipment."iGrounded"='.$iSGrounded ;
    }
	if ($iSPremiseId != "") {
        $where_arr[] = 'equipment."iPremiseId"='.$iSPremiseId ;
    }

	if ($vPremiseName != "") {
        if ($PremiseFilterOpDD != "") {
            if ($PremiseFilterOpDD == "Begins") {
                $where_arr[] = 'n."vName" ILIKE \''.$vPremiseName.'%\'';
            } else if ($PremiseFilterOpDD == "Ends") {
                $where_arr[] = 'n."vName"  ILIKE \'%'.$vPremiseName.'\'';
            } else if ($PremiseFilterOpDD == "Contains") {
                $where_arr[] = 'n."vName"  ILIKE \'%'.$vPremiseName.'%\'';
            } else if ($PremiseFilterOpDD == "Exactly") {
                $where_arr[] = 'n."vName" = \''.$vPremiseName.'\'';
            }
        } else {
            $where_arr[] = 'n."vName" ILIKE \''.$vPremiseName.'%\'';
        }
    }
	if ($iSInstallTypeId != "") {
        $where_arr[] = 'equipment."iInstallTypeId"='.$iSInstallTypeId ;
    }
	if ($iSLinkTypeId != "") {
        $where_arr[] = 'equipment."iLinkTypeId"='.$iSLinkTypeId ;
    }
	if ($iSOperationalStatusId != "") {
        $where_arr[] = 'equipment."iOperationalStatusId"='.$iSOperationalStatusId ;
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
    $join_arr[] = 'LEFT JOIN equipment_model em on equipment."iEquipmentModelId" = em."iEquipmentModelId"';
    $join_arr[] = 'LEFT JOIN material_mas m on equipment."iMaterialId" = m."iMaterialId"';
    $join_arr[] = 'LEFT JOIN power_mas p on equipment."iPowerId" = p."iPowerId"';
    $join_arr[] = 'LEFT JOIN site_mas s on equipment."iPremiseId" = s."iSiteId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN install_type_mas it on equipment."iInstallTypeId" = it."iInstallTypeId"';
    $join_arr[] = 'LEFT JOIN link_type_mas lt on equipment."iLinkTypeId" = lt."iLinkTypeId"';
    $join_arr[] = 'LEFT JOIN operational_status_mas os on equipment."iOperationalStatusId" = os."iOperationalStatusId"';
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
                "iPrimaryCircuitId"		=> $rs_equipment[$i]['iPrimaryCircuitId'],
                "iLinkTypeId"			=> $rs_equipment[$i]['iLinkTypeId'],
                "vLinkType"				=> $rs_equipment[$i]['vLinkType'],
                "dProvisionDate"		=> $rs_equipment[$i]['dProvisionDate'],
                "iOperationalStatusId"  => $rs_equipment[$i]['iOperationalStatusId'],
                "vOperationalStatus"	=> $rs_equipment[$i]['vOperationalStatus'],
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
        "iPrimaryCircuitId"			=> $RES_PARA['iPrimaryCircuitId'],
        "iLinkTypeId"				=> $RES_PARA['iLinkTypeId'],
        "dProvisionDate"			=> $RES_PARA['dProvisionDate'],
        "iOperationalStatusId"		=> $RES_PARA['iOperationalStatusId']
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
        "iPrimaryCircuitId"			=> $RES_PARA['iPrimaryCircuitId'],
        "iLinkTypeId"				=> $RES_PARA['iLinkTypeId'],
        "dProvisionDate"			=> $RES_PARA['dProvisionDate'],
        "iOperationalStatusId"		=> $RES_PARA['iOperationalStatusId']
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
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}
?>