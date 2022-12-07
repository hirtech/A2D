<?php
include_once($controller_path . "inventory_count.inc.php");
include_once($controller_path . "inventory_purchases.inc.php");
include_once($controller_path . "treatment_product.inc.php");
include_once($controller_path . "task_treatment.inc.php");

$InvCount_Obj = new InventoryCount();
$InvPurch_Obj = new InventoryPurchase();
$TaskTreatmentObj = new TaskTreatment();
$TProdObj = new TreatmentProduct();

if($request_type == "inventory_count_list"){
    $where_arr = array();

    if(!empty($RES_PARA)){

        $iICId      = $RES_PARA['iICId'];
        $vName      = $RES_PARA['vName'];
        $rQty       = $RES_PARA['rQty'];

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

    if ($iICId != "") {
        $where_arr[] = 'inventory_count."iICId"='.$iICId ;
    }

    if ($vName != "") {
        $where_arr[] = "treatment_product.\"vName\" ILIKE '" . $vName . "%'";
    }

    $sortname ="";
    switch ($display_order) {
        case "0":
            $sortname = ", treatment_product.\"vName\"". " " . $dir;
            break;
        case "2":
            $sortname = ", inventory_count.\"rQty\"". " " . $dir;
            break;
    }

    $sort = ' inventory_count."iTPId", inventory_count."dDate" desc '.$sortname;
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";
    $InvCount_Obj->clear_variable();

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = "treatment_product.\"vName\"";
    $join_fieds_arr[] = "unit_mas.\"vUnit\"";
    $join_arr[] =' INNER JOIN treatment_product on treatment_product."iTPId" = inventory_count."iTPId" ';
    $join_arr[] =' LEFT JOIN unit_mas on treatment_product."iUId" = unit_mas."iUId" ';
    $InvCount_Obj->join_field = $join_fieds_arr;
    $InvCount_Obj->join = $join_arr;
    $InvCount_Obj->where = $where_arr;
    $InvCount_Obj->param['order_by'] = $sort  ;
    $InvCount_Obj->param['limit'] = $limit;
    $InvCount_Obj->setClause();
    $InvCount_Obj->debug_query = false;
    $rs_list = $InvCount_Obj->invntory_list();

    // Paging Total Records
    $total_record = $InvCount_Obj->inventory_list_total();

    $data = array();
    $ni = count($rs_list);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
         $action = '';
            $id = $rs_list[$i]['iICId'];
            $iTPId = $rs_list[$i]['iTPId'];
            $date = $rs_list[$i]['dDate'];

            //purchased qty total
            $sql_pur = "SELECT sum(\"rPurchQty\") as \"purchTotal\" from inventory_purchases where \"iTPId\" = ".$iTPId." and \"dPurchDate\" >= '".$date."'::date";
            $rs_pur = $sqlObj->GetAll($sql_pur);

            $purchQtyTotal = (isset($rs_pur[0]['purchTotal']))?$rs_pur[0]['purchTotal']:'0';

            //used treatment inventory count
            $sql_tr = "SELECT sum(\"vAmountApplied\"::real) as \"useTreatTotal\" from task_treatment where \"iTPId\" = ".$iTPId." and \"dDate\" >= '".$date."'::date";
            $rs_tr = $sqlObj->GetAll($sql_tr);
        
            $treatQtyTotal = (isset($rs_tr[0]['useTreatTotal']))?$rs_tr[0]['useTreatTotal']:'0';

            $currentQtyTotal = (isset($rs_list[$i]['rQty']))?$rs_list[$i]['rQty']:'0';

            $estlevelTotal =  (float)$currentQtyTotal+(float)$purchQtyTotal-(float)$treatQtyTotal;

            $data[] = array(
                "iICId" => $id,
                "iTPId" => $iTPId,
                "vName" => gen_strip_slash($rs_list[$i]['vName']),
                "date"=> $rs_list[$i]['dDate'],
                "lastInvCount"=> $rs_list[$i]['rQty']." ".$rs_list[$i]['vUnit'],
                "estlevel" => $estlevelTotal." ".$rs_list[$i]['vUnit'],
                "purchInvCount" => $purchQtyTotal." ".$rs_list[$i]['vUnit'],
                'usedInvCount' => $treatQtyTotal.''.$rs_list[$i]['vUnit']
            );
        }
    }
        
    $result = array('data' => $data, 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "inventory_count_add"){

    $insert_arr = array(
        "iTPId"   => $RES_PARA['iTPId'],
        "rQty"    => $RES_PARA['rQty'],
        "dDate"   => $RES_PARA['dDate'],
        "iUserId" => $RES_PARA['iUserId']
    );

    $InvCount_Obj->insert_arr = $insert_arr;
    $InvCount_Obj->setClause();
    $iICId = $InvCount_Obj->add_records();

    if($iICId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iICId" => $iICId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}
else if($request_type == "inventory_count_edit"){

    $update_arr = array(
        "iICId"   => $RES_PARA['iICId'],
        "iTPId"   => $RES_PARA['iTPId'],
        "rQty"    => $RES_PARA['rQty'],
        "dDate"   => $RES_PARA['dDate'],
        "iUserId" => $RES_PARA['iUserId']
    );

    $InvCount_Obj->update_arr = $update_arr;
    $InvCount_Obj->setClause();
    $rs_db = $InvCount_Obj->update_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iICId" => $RES_PARA['iICId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}
else if($request_type == "inventory_purchase_add"){

    $insert_arr = array(
        "iTPId"         => $RES_PARA['iTPId'],
        "rPurchQty"     => $RES_PARA['rPurchQty'],
        "dPurchDate"    => $RES_PARA['dPurchDate'],
        "iUserId"       => $RES_PARA['iUserId']
    );

    $InvPurch_Obj->insert_arr = $insert_arr;
    $InvPurch_Obj->setClause();
    $iIPId = $InvPurch_Obj->add_records();

    if($iIPId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iIPId" => $iIPId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}
else if($request_type == "inventory_purchase_edit"){

    $update_arr = array(
        "iIPId"         => $RES_PARA['iIPId'],
        "iTPId"         => $RES_PARA['iTPId'],
        "rPurchQty"     => $RES_PARA['rPurchQty'],
        "dPurchDate"    => $RES_PARA['dPurchDate'],
        "iUserId"       => $RES_PARA['iUserId']
    );
    
    $InvPurch_Obj->update_arr = $update_arr;
    $InvPurch_Obj->setClause();
    $rs_db = $InvPurch_Obj->update_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iIPId" => $RES_PARA['iIPId']);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}
else if($request_type == "inventory_purchase_list"){

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $tmp_arr =array();
    $ind = 0;
    $tot_purchase = 0;
    $tot_uses = 0;
    $tot_balance = 0;

    if (!empty($RES_PARA))
    {
        $iICId          = $RES_PARA['iICId'];
        $iTPId          = $RES_PARA['iTPId'];
        $page_length    = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start          = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho          = $RES_PARA['sEcho'];
        $display_order  = $RES_PARA['display_order'];
        $dir            = $RES_PARA['dir'];
        $page_type      = $RES_PARA['page_type'];
    }

    $InvCount_Obj->clear_variable();

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = "treatment_product.\"vName\"";
    $join_fieds_arr[] = "unit_mas.\"vUnit\"";
    $join_arr[] =' LEFT JOIN treatment_product on treatment_product."iTPId" = inventory_count."iTPId" ';
    $join_arr[] =' LEFT JOIN unit_mas on unit_mas."iUId" = treatment_product."iUId" ';
    $where_arr[] = 'inventory_count."iTPId" = '.$iTPId;
    $InvCount_Obj->join_field = $join_fieds_arr;
    $InvCount_Obj->join = $join_arr;
    $InvCount_Obj->where = $where_arr;
    $InvCount_Obj->param['order_by'] = 'inventory_count."dDate" desc';
    $InvCount_Obj->param['limit'] = "LIMIT 1";
    $InvCount_Obj->setClause();
    $InvCount_Obj->debug_query = false;
    $rs_invcount = $InvCount_Obj->recordset_list(); 

    $jsonData['aaData'] = array();
    $total = 0;
    $data = array();
    $n = count($rs_invcount);

    if($n > 0){
        $arr = array();
        $ind = 0;

        $date = $rs_invcount[0]['dDate'];
        $iTPId = $rs_invcount[0]['iTPId'];
        $hidden_fields = '<input type="hidden" id="invcount_iTPId_'.$rs_invcount[0]['iICId'].'" value="'.$rs_invcount[0]['iTPId'].'"><input type="hidden" id="invcount_rQty_'.$rs_invcount[0]['iICId'].'" value="'.$rs_invcount[0]['rQty'].'"><input type="hidden" id="invcount_dDate_'.$rs_invcount[0]['iICId'].'" value="'.$rs_invcount[0]['dDate'].'">';
        $data[] = array(
            "dDate"=> date_getDateTimeDDMMYYYY($rs_invcount[0]['dDate']),
            "purchase"=> "",
            "uses" => '',
            "balance" => $rs_invcount[0]['rQty'],
            "actions" => '<a class="text-primary" title="Edit"  href="javascript:void(0);"onclick="addEditInvCountData('.$rs_invcount[0]['iICId'].',\'editInvCount\')">Last Inventory Count</a>'.$hidden_fields
        );

        $tot_balance += $rs_invcount[0]['rQty'];
            
        //get data from inventory purchase
        $InvPurch_Obj->clear_variable();

        $join_fieds_arr = array();
        $join_arr = array();
        $where_arr= array();
        $where_arr[] = ' "iTPId" = '.$rs_invcount[0]['iTPId'];
        $where_arr[] = ' "dPurchDate" >= \''.$date.'\'::date';
        $InvPurch_Obj->join_field = $join_fieds_arr;
        $InvPurch_Obj->join = $join_arr;
        $InvPurch_Obj->where = $where_arr;
        $InvPurch_Obj->setClause();
        $InvPurch_Obj->debug_query = false;
        $rs_invpurchase = $InvPurch_Obj->recordset_list();

        if(count($rs_invpurchase)>0){
            
            for($j=0;$j<count($rs_invpurchase);$j++){
                $hidden_fields = '<input type="hidden" id="ind_rPurQty_'.$rs_invpurchase[$j]['iIPId'].'" value="'.$rs_invpurchase[$j]['rPurchQty'].'"><input type="hidden" id="ind_dPurDate_'.$rs_invpurchase[$j]['iIPId'].'" value="'.$rs_invpurchase[$j]['dPurchDate'].'">';
                $data[] = array(
                    "dDate"=> date_getDateTimeDDMMYYYY($rs_invpurchase[$j]['dPurchDate']),
                    "purchase"=> $rs_invpurchase[$j]['rPurchQty'],
                    "uses" => '',
                    "balance" =>'',
                    "actions" => '<a class="text-primary" title="Edit"  href="javascript:void(0);"onclick="addEditPurchaseData('.$rs_invpurchase[$j]['iIPId'].',\'edit\')">Added Id : '.$rs_invpurchase[$j]['iIPId'].'</a>'.$hidden_fields,
                );

                $tot_purchase += $rs_invpurchase[$j]['rPurchQty'];
            }
        }

        $TaskTreatmentObj->clear_variable();

        //Get treatment data
        $join_fieds_arr = array();
        $join_arr = array();
        $where_arr =array();
        $join_fieds_arr = array();
        $join_fieds_arr[] = 's."vName"';
        $join_fieds_arr[] = 'st."vTypeName"';
        $join_fieds_arr[] = 't."vName" as "vTPName" ';
        $join_fieds_arr[] = 'unit_mas."iParentId"';
        $join_fieds_arr[] = 'unit_mas."vUnit"';
        $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
        $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_treatment."iPremiseId"';
        $join_arr[] = 'LEFT JOIN treatment_product t on t."iTPId" = task_treatment."iTPId"';
        $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = task_treatment."iUId"';
        $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
        $join_arr[] = 'LEFT JOIN sr_details sd on sd."iSRId" = task_treatment."iSRId"';
        $join_arr[] = 'LEFT JOIN contact_mas on  contact_mas."iCId"= sd."iCId"';
        $where_arr[] = " task_treatment.\"iTPId\" = ".$iTPId;
        $where_arr[] = " task_treatment.\"dDate\" >= '".$date."'::date";
        $TaskTreatmentObj->join_field = $join_fieds_arr;
        $TaskTreatmentObj->join = $join_arr;
        $TaskTreatmentObj->where = $where_arr;
        $TaskTreatmentObj->param['order_by'] = " task_treatment.\"dDate\" ";
        $TaskTreatmentObj->setClause();
        $TaskTreatmentObj->debug_query = false;
        $rs_data = $TaskTreatmentObj->recordset_list();
    
        for($i=0;$i<count($rs_data);$i++){
            $TProdObj->clear_variable();

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $join_fieds_arr[] = 'unit_mas."vUnit"';
            $where_arr[] = 'treatment_product."iTPId" = '.$rs_data[$i]['iTPId'].'';
            $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = treatment_product."iUId"';
            $TProdObj->join_field = $join_fieds_arr;
            $TProdObj->join = $join_arr;
            $TProdObj->where = $where_arr;
            $TProdObj->param['limit'] = "LIMIT 1";
            $TProdObj->param['order_by'] = 'treatment_product."iTPId" DESC';
            $TProdObj->setClause();
            $rs_trtproduct = $TProdObj->recordset_list();

            $appRate = (isset($rs_trtproduct[0]['vAppRate']))?$rs_trtproduct[0]['vAppRate']:"";
            $minRate = (isset($rs_trtproduct[0]['vMinAppRate']))?"min ".$rs_trtproduct[0]['vMinAppRate']:"";
            $maxRate = (isset($rs_trtproduct[0]['vMaxAppRate']))?"- max ".$rs_trtproduct[0]['vMaxAppRate']:"";
            $tragetappRate = (isset($rs_trtproduct[0]['vTragetAppRate']))?$rs_trtproduct[0]['vTragetAppRate']:"";
            $unitName = (isset($rs_trtproduct[0]['vUnit']))?$rs_trtproduct[0]['vUnit']:"";

            $vAppRate = $appRate . "(".$minRate.$maxRate.")".$unitName."/".$tragetappRate;

            $vSiteName = $rs_data[$i]['iPremiseId']." (".$rs_data[$i]['vName']."; ".$rs_data[$i]['vTypeName'].")";
            $srdisplay = ($rs_data[$i]['iSRId'] != "")?$rs_data[$i]['iSRId']." (".$rs_data[$i]['vContactName'].")":"";

            if($rs_data[$i]['dStartDate'] != '')
            {
                $rs_data[$i]['dStartTime'] = date("H:i", strtotime($rs_data[$i]['dStartDate']));
            }
            else
            {
                $rs_data[$i]['dStartTime'] = date("H:i", time());
            }

             if($rs_data[$i]['dEndDate'] != '')
            {
                $rs_data[$i]['dEndTime'] = date("H:i", strtotime($rs_data[$i]['dEndDate']));
            }
            else
            {
                $rs_data[$i]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes"));
            }

            $hidden_fields = '<input type="hidden" id="tt_iTreatmentId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iTreatmentId'].'"><input type="hidden" id="tt_vSiteName_'.$rs_data[$i]['iTreatmentId'].'" value="'.$vSiteName.'"><input type="hidden" id="tt_iPremiseId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iPremiseId'].'"><input type="hidden" id="tt_dDate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dDate'].'"><input type="hidden" id="tt_dStartDate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dStartDate'].'"><input type="hidden" id="tt_dStartTime_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dStartTime'].'"><input type="hidden" id="tt_dEndDate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dEndDate'].'"><input type="hidden" id="tt_dEndTime_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dEndTime'].'"><input type="hidden" id="tt_vType_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vType'].'"><input type="hidden" id="tt_iTPId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iTPId'].'"><input type="hidden" id="tt_iTPName_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vTPName'].'"><input type="hidden" id="tt_vAppRate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$vAppRate.'"><input type="hidden" id="tt_vArea_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vArea'].'"><input type="hidden" id="tt_vAreaTreated_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vAreaTreated'].'"><input type="hidden" id="tt_vAmountApplied_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vAmountApplied'].'"><input type="hidden" id="tt_iUId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iUId'].'"><input type="hidden" id="tt_iUParentId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iParentId'].'"><input type="hidden" id="tt_srdisplay_'.$rs_data[$i]['iTreatmentId'].'" value="'.$srdisplay.'"><input type="hidden" id="tt_iSRId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iSRId'].'">';

            $data[] = array(
                "dDate"=> date_getDateTimeDDMMYYYY($rs_data[$i]['dDate']),
                "purchase"=> '',
                "uses" => $rs_data[$i]['vAmountApplied'],
                "balance" =>'',
                "actions" => '<a class="text-primary" title="Edit"  href="javascript:void(0);"onclick="addEditDataTaskTreatment('.$rs_data[$i]['iTreatmentId'].',\'edit\',0)">Treatment Id : '.$rs_data[$i]['iTreatmentId'].'</a>'.$hidden_fields,
            );
        }
        $other_data = array(
            'tot_balance' => $tot_balance,
            'tot_purchase' => $tot_purchase,
            'tot_uses' => $tot_uses,
        );
    }
    
    $result = array('data' => $data, 'other_data' => $other_data, 'total_record' => count($data));

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