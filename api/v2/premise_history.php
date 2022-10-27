<?php
include_once ($controller_path . "premise.inc.php");
include_once ($controller_path . "task_awareness.inc.php");
include_once ($controller_path . "fiber_inquiry.inc.php");
include_once ($controller_path . "service_order.inc.php");
include_once ($controller_path . "workorder.inc.php");
# ------------------------------------------------------------
$SiteObj = new Site();
$TaskAwarenessObj = new TaskAwareness();
$FiberInquiryObj = new FiberInquiry();
$ServiceOrderObj = new ServiceOrder();
$WorkOrderObj = new WorkOrder();
if ($request_type == "premise_history") {
    $page_length    = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"";
    $start          = isset($RES_PARA['start'])?trim($RES_PARA['start']):"";
    $iPremiseId     = isset($RES_PARA['iPremiseId'])?trim($RES_PARA['iPremiseId']):"";
    $page_type      = isset($RES_PARA['page_type'])?trim($RES_PARA['page_type']):"";
    $sortname       = 'dDate';
    $sortdir        = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"desc";
    $where_arr      = $join_fieds_arr = $join_arr = $site_history_arr = array();

    if($start != "" && $page_length != ""){
        $SiteObj->param['limit'] = " LIMIT $page_length OFFSET $start";
    }else if($page_length != ""){
        $SiteObj->param['limit'] = " LIMIT $page_length";
    }

    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->param['order_by'] = '"' . $sortname . '" ' . $dir;
    $SiteObj->setClause();
    $rs = $SiteObj->site_history_list($iPremiseId);
    //echo "<pre>";print_r($rs);exit;
    $total = 0;
    $entry = array();
    $ni = count($rs);
    $total_record = $ni;
    if ($ni > 0) {
        $arr = array();
        $sr_arr = array();
        $ind = 0;
        foreach ($rs as $key => $val) {
            if ($val['Type'] == "Awareness"){
                $iAId = $val['iAId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();
                $where_arr[] = 'awareness."iAId"=' . $iAId;
                $join_fieds_arr[] = " e.\"vEngagement\"";
                $join_fieds_arr[] = " s.\"vName\" as  \"vPremiseName\" ";
                $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
                $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
                $join_arr[] = 'LEFT JOIN engagement_mas e on e."iEngagementId" = awareness."iEngagementId"';
                $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = awareness."iPremiseId"';
                $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
                $join_arr[] = 'LEFT JOIN fiberinquiry_details on fiberinquiry_details."iFiberInquiryId" = awareness."iFiberInquiryId"';
                $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';
                
                $TaskAwarenessObj->join_field = $join_fieds_arr;
                $TaskAwarenessObj->join = $join_arr;
                $TaskAwarenessObj->where = $where_arr;
                $TaskAwarenessObj->param['order_by'] = "awareness.\"dDate\" DESC";
                $TaskAwarenessObj->setClause();
                $TaskAwarenessObj->debug_query = false;
                $awareness_arr = $TaskAwarenessObj->recordset_list();
                //echo "<pre>";print_r($awareness_arr);exit;
                if (!empty($awareness_arr)){
                    $ai = count($awareness_arr);
                    for ($a=0;$a<$ai;$a++){
                        $vSiteName = $awareness_arr[$a]['iPremiseId'] . " (" . $awareness_arr[$a]['vPremiseName'] . "; " . $awareness_arr[$a]['vTypeName'] . ")";

                        $site_details = '';
                        $site_details .= 'Premise ' . $awareness_arr[$a]['iPremiseId'] . ($awareness_arr[$a]['vPremiseName'] ? ' (' . $awareness_arr[$a]['vPremiseName'] . ') ' : '') . ($awareness_arr[$a]['vTypeName'] ? $awareness_arr[$a]['vTypeName'] : '');

                        $arr[$ind]['site_details'] = $site_details;
                        $vSummary = '';
                        $vSummary .= 'Awareness #'.$awareness_arr[$a]['iAId'].":".$awareness_arr[$t]['iAId']." ".$awareness_arr[$a]['vEngagement'];
                        $arr[$ind]['dDate'] = ($awareness_arr[$a]['dDate'] ? date("m/d/Y", strtotime($awareness_arr[$a]['dDate'])) : '');
                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iAId'];

                        if($awareness_arr[$a]['dStartDate'] != ''){
                            $awareness_arr[$a]['dStartTime'] = date("H:i", strtotime($awareness_arr[$a]['dStartDate']));
                        }
                        else{
                            $awareness_arr[$a]['dStartTime'] = date("H:i", time());
                        }

                        if($awareness_arr[$a]['dEndDate'] != ''){
                            $awareness_arr[$a]['dEndTime'] = date("H:i", strtotime($awareness_arr[$a]['dEndDate']));
                        }
                        else{
                            $awareness_arr[$a]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s') . " +10 minutes"));
                        }
                        $fiberinquirydisplay = ($awareness_arr[$a]['iFiberInquiryId'] != "") ? $awareness_arr[$a]['iFiberInquiryId'] . " (" . $awareness_arr[$a]['vContactName'] . ")" : "";
                        
                        $arr[$ind]['operation_type_data'] = array(
                            "iAId"                  => $awareness_arr[$a]['iAId'],
                            "iPremiseId"            => $awareness_arr[$a]['iPremiseId'],
                            "vSiteName"             => $vSiteName,
                            "dDate"                 => $awareness_arr[$a]['dDate'],
                            "dStartDate"            => $awareness_arr[$a]['dStartDate'],
                            "dStartTime"            => $awareness_arr[$a]['dStartTime'],
                            "dEndDate"              => $awareness_arr[$a]['dEndDate'],
                            "dEndTime"              => $awareness_arr[$a]['dEndTime'],
                            "iEngagementId"         => $awareness_arr[$a]['iEngagementId'],
                            "vEngagement"           => $awareness_arr[$a]['vEngagement'],
                            "iFiberInquiryId"       => $awareness_arr[$a]['iFiberInquiryId'],
                            "fiberinquirydisplay"   => $fiberinquirydisplay,
                            "iTechnicianId"         => $awareness_arr[$a]['iTechnicianId'],
                            "tNotes"                => $awareness_arr[$a]['tNotes'],
                            
                        );
                    }
                    $ind++;
                }
            }

            if ($val['Type'] == "FiberInquiry"){
                $iFiberInquiryId = $val['iFiberInquiryId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();
                $where_arr[] = 'fiberinquiry_details."iFiberInquiryId"=' . $iFiberInquiryId;
                //$where_arr[] = "sr_details.\"iStatus\" = 4 "; // SR Status = Complete;
                $join_fieds_arr[] = " e.\"vEngagement\"";
                $join_fieds_arr[] = " fiberinquiry_details.\"iMatchingPremiseId\" as \"iPremiseId\" ";
                $join_fieds_arr[] = "concat(contact_mas.\"vFirstName\",' ', contact_mas.\"vLastName\") as \"vContactName\" ";
                $join_fieds_arr[] = " contact_mas.\"vEmail\" as \"vContactEmail\" ";
                $join_fieds_arr[] = " contact_mas.\"vPhone\" as \"vContactPhone\" ";
                $join_arr[] = 'LEFT JOIN engagement_mas e on e."iEngagementId" = fiberinquiry_details."iEngagementId"';
        
                $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';
                        
                $FiberInquiryObj->join_field = $join_fieds_arr;
                $FiberInquiryObj->join = $join_arr;
                $FiberInquiryObj->where = $where_arr;
                $FiberInquiryObj->param['order_by'] = "fiberinquiry_details.\"dAddedDate\" DESC";
                $FiberInquiryObj->setClause();
                $FiberInquiryObj->debug_query = false;
                $fiberinquiry_details_arr = $FiberInquiryObj->recordset_list();
                //echo "<pre>";print_r($fiberinquiry_details_arr);exit;
                if (!empty($fiberinquiry_details_arr)){
                    $fi = count($fiberinquiry_details_arr);
                    for ($f=0;$f<$fi;$f++){
                        $vContactName = $fiberinquiry_details_arr[$f]['vContactName'];
                        $vContactEmail = $fiberinquiry_details_arr[$f]['vContactEmail'];
                        $vContactPhone = $fiberinquiry_details_arr[$f]['vContactPhone'];
                        $vSummary = '';
                        $vContactstr = '';
                        if($vContactEmail != '' && $vContactPhone != ''){
                            $vContactstr = ' ('.$vContactEmail.' / '.$vContactPhone.')';
                        }else if($vContactEmail != '' && $vContactPhone == ''){
                            $vContactstr = ' ('.$vContactEmail.')';
                        }else if($vContactEmail == '' && $vContactPhone != ''){
                            $vContactstr = ' ('.$vContactPhone.')';
                        }
                        $vSummary .= 'Inquiry #'.$fiberinquiry_details_arr[$f]['iFiberInquiryId'].":".$fiberinquiry_details_arr[$t]['iFiberInquiryId']." ".$vContactName.$vContactstr." ".$fiberinquiry_details_arr[$f]['vEngagement'];
                        $arr[$ind]['site_details'] = 'Fiber Inquiry ' . $fiberinquiry_details_arr[$f]['iFiberInquiryId'] . ($vContactName ? " (".$vContactName.")" : '');
                        $arr[$ind]['dDate'] = ($fiberinquiry_details_arr[$f]['dAddedDate'] ? date("m/d/Y", strtotime($fiberinquiry_details_arr[$f]['dAddedDate'])) : '');
                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iFiberInquiryId'];
                    }
                    $ind++;
                }
            }

            if ($val['Type'] == "ServiceOrder"){
                $iServiceOrderId = $val['iServiceOrderId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();
                $where_arr[] = 'service_order."iServiceOrderId"=' . $iServiceOrderId;
                $join_fieds_arr[] = " ct.\"vConnectionTypeName\"";
                $join_fieds_arr[] = " st1.\"vServiceType\" as \"vServiceType1\"";
                $join_fieds_arr[] = " st2.\"vServiceType\" as \"vServiceType2\"";
                $join_fieds_arr[] = " st3.\"vServiceType\" as \"vServiceType3\"";
                $join_arr[] = 'LEFT JOIN connection_type_mas ct on ct."iConnectionTypeId" = service_order."iConnectionTypeId"';
                $join_arr[] = 'LEFT JOIN service_type_mas st1 on st1."iServiceTypeId" = service_order."iService1"';
                $join_arr[] = 'LEFT JOIN service_type_mas st2 on st2."iServiceTypeId" = service_order."iService2"';
                $join_arr[] = 'LEFT JOIN service_type_mas st3 on st3."iServiceTypeId" = service_order."iService3"';
                $ServiceOrderObj->join_field = $join_fieds_arr;
                $ServiceOrderObj->join = $join_arr;
                $ServiceOrderObj->where = $where_arr;
                $ServiceOrderObj->param['order_by'] = "service_order.\"dAddedDate\" DESC";
                $ServiceOrderObj->setClause();
                $ServiceOrderObj->debug_query = false;
                $so_arr = $ServiceOrderObj->recordset_list();
                //echo "<pre>";print_r($so_arr);exit;
                if (!empty($so_arr)){
                    $si = count($so_arr);
                    for ($s=0;$s<$si;$s++){
                        $vConnectionTypeName = $so_arr[$s]['vConnectionTypeName'];
                        $vServiceType1 = $so_arr[$s]['vServiceType1'];
                        $vServiceType2 = $so_arr[$s]['vServiceType2'];
                        $vServiceType3 = $so_arr[$s]['vServiceType3'];
                        $vSummary = '';
                        $vServicestr = '';
                        if($vServiceType1 != ''){
                            $vServicestr = ' | '.$vServiceType1;
                        }else if($vServiceType2 != ''){
                            $vServicestr = ' | '.$vServiceType2;
                        }else if($vServiceType3 != ''){
                            $vServicestr = ' | '.$vServiceType3;
                        }
                        $vSummary .= 'SO #'.$so_arr[$s]['iServiceOrderId'].":".$so_arr[$s]['vServiceOrder']." ".$vConnectionTypeName.$vServicestr;

                        $arr[$ind]['site_details'] = 'Service Order ' . $so_arr[$s]['iServiceOrderId']." ".$so_arr[$s]['vServiceOrder'];
                        $arr[$ind]['dDate'] = ($so_arr[$s]['dAddedDate'] ? date("m/d/Y", strtotime($so_arr[$s]['dAddedDate'])) : '');
                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iServiceOrderId'];
                    }
                    $ind++;
                }
            }

            if ($val['Type'] == "WorkOrder"){
                $iWOId = $val['iWOId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();
                $where_arr[] = 'workorder."iWOId"=' . $iWOId;
                $join_fieds_arr[] = " wt.\"vType\"";
                
                $join_arr[] = 'LEFT JOIN workorder_type_mas wt on workorder."iWOTId" = wt."iWOTId"';
                $WorkOrderObj->join_field = $join_fieds_arr;
                $WorkOrderObj->join = $join_arr;
                $WorkOrderObj->where = $where_arr;
                $WorkOrderObj->param['order_by'] = "workorder.\"dAddedDate\" DESC";
                $WorkOrderObj->setClause();
                $WorkOrderObj->debug_query = false;
                $wo_arr = $WorkOrderObj->recordset_list();
                //echo "<pre>";print_r($wo_arr);exit;
                if (!empty($wo_arr)){
                    $wi = count($wo_arr);
                    for ($w=0;$w<$wi;$w++){

                        $vType = $wo_arr[$w]['vType'];
                        $vSummary = '';
                        
                        $vSummary .= 'WO #'.$wo_arr[$w]['iWOId'].":".$wo_arr[$w]['vWOProject']." | ".$vType;

                        $arr[$ind]['site_details'] = 'Work Order ' . $wo_arr[$w]['iWOId']." ".$wo_arr[$w]['vWOProject']." | ".$vType;
                        $arr[$ind]['dDate'] = ($wo_arr[$w]['dAddedDate'] ? date("m/d/Y", strtotime($wo_arr[$w]['dAddedDate'])) : '');
                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iWOId'];
                    }
                    $ind++;
                }
            }
        }
        //echo "<pre>";print_r($arr);exit();
        $ni = count($arr);
        if ($page_type == "site_info_window") {
            $ni = count($arr);
            if ($ni >= 5){
                $ni = 5;
            }
            $start1 = 0;
            $end1 = $ni;
            $total_record = $ni;
        }else{
            $ni = count($arr);
            if ($ni > $page_length) {
                if ($start != 0){
                    if ($page_length != $start){
                        $start1 = ($start < $page_length) ? ($ni - $page_length - $start) : ($ni - $page_length);
                    }
                    else{
                        $start1 = $page_length;
                    }
                }
                else{
                    $start1 = ($start < $page_length) ? 0 : ($ni - $page_length);
                }

                $end1 = ($ni - $start >= $page_length) ? ($start + $page_length) : $ni;
            }
            else{
                $start1 = 0;
                $end1 = $ni;
            }            
            
            $total_record = $ni;
        }

        if ($ni > 0){
            for ($i = $start1;$i < $end1;$i++){
                $site_history_arr[] = array(
                    "Date" => $arr[$i]['dDate'],
                    "id" => $arr[$i]['id'],
                    "Name" => $arr[$i]['site_details'],
                    "Description" => $arr[$i]['vSummary'],
                    "Type" => $arr[$i]['Type'],
                    "operation_type_data" => $arr[$i]['operation_type_data'],
                );
            }
        }
    }
    //echo "<pre>";print_r($site_history_arr);exit();
    $result = array('data' =>$site_history_arr , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}

?>
