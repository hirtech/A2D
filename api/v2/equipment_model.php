<?php
include_once($controller_path . "equipment_model.inc.php");
$EquipmentModelObj = new EquipmentModel();
if($request_type == "equipment_model_list"){
    $EquipmentModelObj->clear_variable();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iEquipmentModelId      = $RES_PARA['iEquipmentModelId'];
        $vModelName             = $RES_PARA['vModelName'];
        $vModelNumber             = $RES_PARA['vModelNumber'];
        $vPartNumber            = $RES_PARA['vPartNumber'];
        $iUnitQuantity          = $RES_PARA['iUnitQuantity'];
        $rUnitCost              = $RES_PARA['rUnitCost'];
        $vEquipmentType         = $RES_PARA['vEquipmentType'];
        $vEquipmentManufacturer = $RES_PARA['vEquipmentManufacturer'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];
        $order_by               = $RES_PARA['order_by'];
 
    }

    if ($iEquipmentModelId != "") {
        $where_arr[] = 'equipment_model."iEquipmentModelId"='.$iEquipmentModelId ;
    }
    
    if ($vModelName != "") {
        $where_arr[] = "equipment_model.\"vModelName\" ILIKE '%".$vModelName."%'" ;
    }
    
    if ($vModelNumber != "") {
        $where_arr[] = "equipment_model.\"vModelNumber\" ILIKE '%".$vModelNumber."%'" ;
    }
    
    if ($vPartNumber != "") {
        $where_arr[] = "equipment_model.\"vPartNumber\" ILIKE '%".$vPartNumber."%'" ;
    }
    
    if ($iUnitQuantity != "") {
        $where_arr[] = "equipment_model.\"iUnitQuantity\" = '".$iUnitQuantity."'" ;
    }
    
    if ($rUnitCost != "") {
        $where_arr[] = "equipment_model.\"rUnitCost\" = '".$rUnitCost."'" ;
    }

    if ($vEquipmentType != "") {
        $where_arr[] = "et.\"vEquipmentType\" ILIKE '%".$vEquipmentType."%'" ;
    }

    if ($vEquipmentManufacturer != "") {
        $where_arr[] = "em.\"vEquipmentManufacturer\" ILIKE '%".$vEquipmentManufacturer."%'" ;
    }

    switch ($display_order) {
        case "0":
            $sortname = "equipment_model.\"iEquipmentModelId\"";
            break;
        case "1":
            $sortname = "equipment_model.\"vModelName\"";
            break;
         case "2":
            $sortname = "equipment_model.\"vModelNumber\"";
            break;
        case "3":
            $sortname = "equipment_model.\"vPartNumber\"";
            break;
        case "4":
            $sortname = "equipment_model.\"iUnitQuantity\"";
            break;
        case "5":
            $sortname = "equipment_model.\"rUnitCost\"";
            break;
        case "6":
            $sortname = "et.\"vEquipmentType\"";
            break;
        case "7":
            $sortname = "em.\"vEquipmentManufacturer\"";
            break;
        default:
            $sortname = "equipment_model.\"iEquipmentModelId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 'et."vEquipmentType"';
    $join_fieds_arr[] = 'em."vEquipmentManufacturer"';
    $join_arr[] = 'LEFT JOIN equipment_type_mas et on equipment_model."iEquipmentTypeId" = et."iEquipmentTypeId"';
    $join_arr[] = 'LEFT JOIN equipment_manufacturer_mas em on equipment_model."iEquipmentManufacturerId" = em."iEquipmentManufacturerId"';
    $EquipmentModelObj->join_field = $join_fieds_arr;
    $EquipmentModelObj->join = $join_arr;
    $EquipmentModelObj->where = $where_arr;
    $EquipmentModelObj->param['order_by'] = $sortname . " " . $dir;
    $EquipmentModelObj->param['limit'] = $limit;
    $EquipmentModelObj->setClause();
    $EquipmentModelObj->debug_query = false;
    $rs_model = $EquipmentModelObj->recordset_list();

    // Paging Total Records
    $total = $EquipmentModelObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_model);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            
            $data[] = array(
                "iEquipmentModelId"         => $rs_model[$i]['iEquipmentModelId'],
                "vModelName"                => $rs_model[$i]['vModelName'],
                "vModelNumber"              => $rs_model[$i]['vModelNumber'],
                "vPartNumber"               => $rs_model[$i]['vPartNumber'],
                "tDescription"              => $rs_model[$i]['tDescription'],
                "iUnitQuantity"             => $rs_model[$i]['iUnitQuantity'],
                "rUnitCost"                 => $rs_model[$i]['rUnitCost'],
                "iEquipmentTypeId"          => $rs_model[$i]['iEquipmentTypeId'],
                "iEquipmentManufacturerId"  => $rs_model[$i]['iEquipmentManufacturerId'],
                "vEquipmentType"            => $rs_model[$i]['vEquipmentType'],
                "vEquipmentManufacturer"    => $rs_model[$i]['vEquipmentManufacturer'],
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "equipment_model_edit"){
	$EquipmentModelObj->clear_variable();
   	$update_arr = array(
        "iEquipmentModelId"			=> $RES_PARA['iEquipmentModelId'],
        "vModelName"                => $RES_PARA['vModelName'],
        "vModelNumber"              => $RES_PARA['vModelNumber'],
        "vPartNumber"               => $RES_PARA['vPartNumber'],
        "tDescription"              => $RES_PARA['tDescription'],
        "iUnitQuantity"             => $RES_PARA['iUnitQuantity'],
        "rUnitCost"                 => $RES_PARA['rUnitCost'],
        "iEquipmentTypeId"          => $RES_PARA['iEquipmentTypeId'],
        "iEquipmentManufacturerId"  => $RES_PARA['iEquipmentManufacturerId']
    );

   $EquipmentModelObj->update_arr = $update_arr;
   $EquipmentModelObj->setClause();
   $rs_db = $EquipmentModelObj->update_records();
   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iEquipmentModelId" => $RES_PARA['iEquipmentModelId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}else if($request_type == "equipment_model_delete"){
   	$iEquipmentModelId = $RES_PARA['iEquipmentModelId'];
	$EquipmentModelObj->clear_variable();
    $rs_db = $EquipmentModelObj->delete_records($iEquipmentModelId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iEquipmentModelId" => $iEquipmentModelId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "equipment_model_add") {
    $EquipmentModelObj->clear_variable();

    $insert_arr = array(
        "vModelName"                => $RES_PARA['vModelName'],
        "vModelNumber"              => $RES_PARA['vModelNumber'],
        "vPartNumber"			    => $RES_PARA['vPartNumber'],
        "tDescription"              => $RES_PARA['tDescription'],
        "iUnitQuantity"				=> $RES_PARA['iUnitQuantity'],
        "rUnitCost"			        => $RES_PARA['rUnitCost'],
        "iEquipmentTypeId"          => $RES_PARA['iEquipmentTypeId'],
        "iEquipmentManufacturerId"	=> $RES_PARA['iEquipmentManufacturerId']
    );

    $EquipmentModelObj->insert_arr = $insert_arr;
    $EquipmentModelObj->setClause();
    $iEquipmentModelId = $EquipmentModelObj->add_records();
    if($iEquipmentModelId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iEquipmentModelId" => $iEquipmentModelId);
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