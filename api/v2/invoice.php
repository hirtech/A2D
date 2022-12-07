<?php
include_once($controller_path . "invoice.inc.php");

$InvoiceObj = new Invoice();
if($request_type == "invoice_list"){
    $where_arr = array();
    if(!empty($RES_PARA)){
        // echo "<pre>"; print_r($RES_PARA);exit();
        $iInvoiceId         = $RES_PARA['iInvoiceId'];
        $vCompanyName       = $RES_PARA['vCompanyName'];
        $vPoNumber          = $RES_PARA['vPoNumber'];

        $dSInvoiceDate      = $RES_PARA['dSInvoiceDate'];
        $dSPaymentDate      = $RES_PARA['dSPaymentDate'];
        $iSBillingMonth     = $RES_PARA['iSBillingMonth'];
        $iSBillingYear      = $RES_PARA['iSBillingYear'];
        $iSPremiseId        = $RES_PARA['iSPremiseId'];
        $vSPremiseNameDD    = $RES_PARA['vSPremiseNameDD'];
        $vSPremiseName      = trim($RES_PARA['vSPremiseName']);
        $iSServiceType      = $RES_PARA['iSServiceType'];
        $dSStartDate        = $RES_PARA['dSStartDate'];
        $iSStatus           = $RES_PARA['iSStatus'];

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
    }
    if ($iInvoiceId != "") {
        $where_arr[] = "invoice.\"iInvoiceId\"='".$iInvoiceId."'";
    }

    if ($vCompanyName != "") {
        $where_arr[] = "cm.\"vCompanyName\" ILIKE '%".$vCompanyName."%'";
    }

    if ($vPoNumber != "") {
        $where_arr[] = "invoice.\"vPONumber\" ILIKE '%".$vPoNumber."%'";
    }

    if ($iSStatus != "") {
        $where_arr[] = "invoice.\"iStatus\"='".$iSStatus."'";
    }

    if ($dSInvoiceDate != "") {
        $where_arr[] = "invoice.\"dInvoiceDate\"='".$dSInvoiceDate."'";
    }

    if ($dSPaymentDate != "") {
        $where_arr[] = "invoice.\"dPaymentDate\"='".$dSPaymentDate."'";
    }

    if ($iSBillingMonth != "") {
        $where_arr[] = "invoice.\"iBillingMonth\"='".$iSBillingMonth."'";
    }

    if ($iSBillingYear != "") {
        $where_arr[] = "invoice.\"iBillingYear\"='".$iSBillingYear."'";
    }

    //invoice_premise Filters
    $premise_where_arr = [];
    if ($iSPremiseId != "") {
        $premise_where_arr[] = "invoice_lines.\"iPremiseId\"='".$iSPremiseId."'";
    }
    if ($vSPremiseName != "") {
        if ($vSPremiseNameDD != "") {
            if ($vSPremiseNameDD == "Begins") {
                $premise_where_arr[] = 's."vName" ILIKE \''.$vSPremiseName.'%\'';
            } else if ($vSPremiseNameDD == "Ends") {
                $premise_where_arr[] = 's."vName" ILIKE \'%'.$vSPremiseName.'\'';
            } else if ($vSPremiseNameDD == "Contains") {
                $premise_where_arr[] = 's."vName" ILIKE \'%'.$vSPremiseName.'%\'';
            } else if ($vSPremiseNameDD == "Exactly") {
                $premise_where_arr[] = 's."vName" ILIKE \''.$vSPremiseName.'\'';
            }
        } else {
            $premise_where_arr[] = 's."vName" ILIKE \''.$vSPremiseName.'%\'';
        }
    }
    if ($iSServiceType != "") {
        $premise_where_arr[] = "invoice_lines.\"iServiceTypeId\"='".$iSServiceType."'";
    }

    if ($dSStartDate != "") {
        $premise_where_arr[] = "invoice_lines.\"dStartDate\" :: DATE=  '".$dSStartDate."'";
    }
    
    $iInvoiceIdArr = array();
    if(!empty($premise_where_arr)) {
        $premise_join_fieds_arr = array();
        $premise_join_fieds_arr[] = 's."vName"';
        $premise_join_arr = array();
        $premise_join_arr[] = 'LEFT JOIN site_mas s on invoice_lines."iPremiseId" = s."iPremiseId"';
        $InvoiceObj->join_field = $premise_join_fieds_arr;
        $InvoiceObj->join = $premise_join_arr;
        $InvoiceObj->where = $premise_where_arr;
        $InvoiceObj->param['order_by'] = "s.\"vName\" ASC";
        $InvoiceObj->setClause();
        $InvoiceObj->debug_query = false;
        $rs = $InvoiceObj->invoice_lines_recordset_list();
        // echo "<pre>"; print_r($rs);exit();
        $ci =count($rs);
        if($ci > 0) {
            for($c=0; $c<$ci; $c++){
                $iInvoiceIdArr[] = $rs[$c]['iInvoiceId'];
            }
        }
    }
    array_unique($iInvoiceIdArr);
    if(!empty($iInvoiceIdArr)){
        $where_arr[] = "invoice.\"iInvoiceId\" IN (".implode(",", $iInvoiceIdArr).") ";
    }


    switch ($display_order) {
        case "0":
            $sortname = "invoice.\"iInvoiceId\"";
            break;
        case "1":
            $sortname = "cm.\"vCompanyName\"";
            break;
        case "2":
            $sortname = "invoice.\"vPONumber\"";
            break;
        case "3":
            $sortname = "invoice.\"dInvoiceDate\"";
            break;
        case "4":
            $sortname = "invoice.\"dPaymentDate\"";
            break;
        case "5":
            $sortname = "invoice.\"iBillingMonth\"";
            break;
        case "6":
            $sortname = "invoice.\"rTotalAmount\"";
            break;
        case "7":
            $sortname = "invoice.\"iStatus\"";
            break;
        default:
            $sortname = "invoice.\"iInvoiceId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 'cm."vCompanyName"';
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN company_mas cm on cm."iCompanyId" = invoice."iCustomerId"';
    $InvoiceObj->join_field = $join_fieds_arr;
    $InvoiceObj->join = $join_arr;
    $InvoiceObj->where = $where_arr;
    $InvoiceObj->param['order_by'] = $sortname . " " . $dir;
    $InvoiceObj->param['limit'] = $limit;
    $InvoiceObj->setClause();
    $InvoiceObj->debug_query = false;
    $rs_invoice = $InvoiceObj->recordset_list();
    // echo "<pre>"; print_r($rs_invoice);exit();
    // Paging Total Records
    $total_record = $InvoiceObj->recordset_total();
    // Paging Total Records
    
    $data = array();
    $ni = count($rs_invoice);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $data[] = array(
                "iInvoiceId"        => $rs_invoice[$i]['iInvoiceId'],
                "iCustomerId"       => $rs_invoice[$i]['iCustomerId'],
                "vCompanyName"      => $rs_invoice[$i]['vCompanyName'],
                "vPONumber"         => $rs_invoice[$i]['vPONumber'],
                "dInvoiceDate"      => $rs_invoice[$i]['dInvoiceDate'],
                "dPaymentDate"      => $rs_invoice[$i]['dPaymentDate'],
                "iBillingMonth"     => $rs_invoice[$i]['iBillingMonth'],
                "iBillingYear"      => $rs_invoice[$i]['iBillingYear'],
                "rTotalAmount"      => $rs_invoice[$i]['rTotalAmount'],
                "iStatus"           => $rs_invoice[$i]['iStatus']
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "invoice_add"){
    $insert_arr = array(
        "iCustomerId"   => $RES_PARA['iCustomerId'],
        "vPONumber"     => $RES_PARA['vPONumber'],
        "dInvoiceDate"  => $RES_PARA['dInvoiceDate'],
        "dPaymentDate"  => $RES_PARA['dPaymentDate'],
        "iBillingMonth" => $RES_PARA['iBillingMonth'],
        "iBillingYear"  => $RES_PARA['iBillingYear'],
        "tNotes"        => $RES_PARA['tNotes'],
        "iLoginUserId"  => $RES_PARA['iLoginUserId'],
        "iStatus"       => "0", //Draft
    );
    $InvoiceObj->insert_arr = $insert_arr;
    $InvoiceObj->setClause();
    $iInvoiceId = $InvoiceObj->add_records();

    if($iInvoiceId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "error" => 0, "iInvoiceId" => $iInvoiceId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "error" => 1);
    }
}else if($request_type == "get_invoice_data_from_id"){
    $invoice_data = array();
    $iInvoiceId = $RES_PARA['iInvoiceId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
        
    $where_arr[] = 'invoice."iInvoiceId"='.$iInvoiceId ;
    $join_fieds_arr[] = 'c."vCompanyName"';
    $join_arr[] = 'LEFT JOIN company_mas c ON invoice."iCustomerId" = c."iCompanyId"';
    $InvoiceObj->join_field = $join_fieds_arr;
    $InvoiceObj->join = $join_arr;
    $InvoiceObj->where = $where_arr;
    $InvoiceObj->param['order_by'] = 'invoice."iInvoiceId" DESC';
    $InvoiceObj->param['limit'] = '1';
    $InvoiceObj->setClause();
    $InvoiceObj->debug_query = false;
    $rs_invoice = $InvoiceObj->recordset_list();
    if($rs_invoice) {
        $invoice_data = $rs_invoice;
        $InvoiceObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();
        $where_arr[] = 'invoice_lines."iInvoiceId"='.$iInvoiceId ;
        $join_fieds_arr[] = 's."vName" as "vPremiseName"';
        $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
        $join_fieds_arr[] = 'sst."vSubTypeName" as "vPremiseSubType"';
        $join_fieds_arr[] = 'n."vName" as "vNetwork"';
        $join_fieds_arr[] = 'stm."vServiceType"';
        $join_arr[] = 'LEFT JOIN site_mas s ON invoice_lines."iPremiseId" = s."iPremiseId"';
        $join_arr[] = 'LEFT JOIN site_type_mas st ON s."iSTypeId" = st."iSTypeId"';
        $join_arr[] = 'LEFT JOIN site_sub_type_mas sst ON s."iSSTypeId" = sst."iSSTypeId"';
        $join_arr[] = 'LEFT JOIN zone z ON s."iZoneId" = z."iZoneId"';
        $join_arr[] = 'LEFT JOIN network n ON z."iNetworkId" = n."iNetworkId"';
        $join_arr[] = 'LEFT JOIN service_type_mas stm ON invoice_lines."iServiceTypeId" = stm."iServiceTypeId"';
        $InvoiceObj->join_field = $join_fieds_arr;
        $InvoiceObj->join = $join_arr;
        $InvoiceObj->where = $where_arr;
        $InvoiceObj->param['order_by'] = 'invoice_lines."iInvoiceId"';
        $InvoiceObj->setClause();
        $InvoiceObj->debug_query = false;
        $rs_invoice_lines = $InvoiceObj->invoice_lines_recordset_list();
        $invoice_data[0]['invoice_lines'] = $rs_invoice_lines;

    }
    $result = array('data' => $invoice_data);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "invoice_change_status"){
    $iInvoiceId = $RES_PARA['iInvoiceId'];
    $iStatus = $RES_PARA['iStatus'];
    $iLoginUserId = $RES_PARA['iLoginUserId'];
    $rs_db = $InvoiceObj->change_status($iInvoiceId, $iStatus, $iLoginUserId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => "Invoice status changed Successfully", "iInvoiceId" => $iInvoiceId, "error" => 0);
    }else{
        $response_data = array("Code" => 500 , "Message" => "ERROR - in Invoice status.", "error" => 1);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>