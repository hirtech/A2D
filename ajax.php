<?php

include_once("server.php");

$mode = $_REQUEST['mode'];
$userid =  $_SESSION["sess_iUserId".$admin_panel_session_suffix];
$iAGroupId =  $_SESSION["sess_iAGroupId".$admin_panel_session_suffix];

if($mode == "get_top_notification"){
    //get notification
    $arr_param = array();
    $arr_param['userId'] = $userid;
    $arr_param['iAGroupId'] = $iAGroupId;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."notification.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
    )); 
    $response = curl_exec($ch);

    curl_close($ch);  
    $res= json_decode($response, true);
    $rs_notification = array();
    if(!empty($res['result']['notification_arr'])){
        foreach($res['result']['notification_arr'] as $k => $v){
            $class_type = str_replace(' ', '', strtolower($v['type']));

            $icon = "icon-grid";
            $color = "text-dark";
            $link = "javascript:void(0)";
            //echo $class_type;exit;
            if($class_type == 'fiberinquiry'){
                $icon = isset($notification_class_arr["'".$class_type."'"]['icon'])?$notification_class_arr["'".$class_type."'"]['icon']:$notification_class_arr["FiberInquiry"]['icon'];
                $color = isset($notification_class_arr[$class_type]['color'])?$notification_class_arr[$class_type]['color']:$notification_class_arr["FiberInquiry"]['color'];

                $link = $site_url."fiber_inquiry/edit&mode=Update&iFiberInquiryId=".$v['iFiberInquiryId'];
            }else if($class_type == 'workorder'){
                $icon = isset($notification_class_arr["'".$class_type."'"]['icon'])?$notification_class_arr["'".$class_type."'"]['icon']:$notification_class_arr["Workorder"]['icon'];
                $color = isset($notification_class_arr[$class_type]['color'])?$notification_class_arr[$class_type]['color']:$notification_class_arr["Workorder"]['color'];

                $link = $site_url."service_order/workorder_add&mode=Update&iWOId=".$v['iWOId'];
            }else if($class_type == 'troubleticket'){
                $icon = isset($notification_class_arr["'".$class_type."'"]['icon'])?$notification_class_arr["'".$class_type."'"]['icon']:$notification_class_arr["TroubleTicket"]['icon'];
                $color = isset($notification_class_arr[$class_type]['color'])?$notification_class_arr[$class_type]['color']:$notification_class_arr["TroubleTicket"]['color'];
                
                $link = $site_url."trouble_ticket/trouble_ticket_edit&mode=Update&iTroubleTicketId=6".$v['iWOId'];
            }else if($class_type == 'maintenanceticket'){
                $icon = isset($notification_class_arr["'".$class_type."'"]['icon'])?$notification_class_arr["'".$class_type."'"]['icon']:$notification_class_arr["MaintenanceTicket"]['icon'];
                $color = isset($notification_class_arr[$class_type]['color'])?$notification_class_arr[$class_type]['color']:$notification_class_arr["MaintenanceTicket"]['color'];
                
                $link = $site_url."maintenance_ticket/maintenance_ticket_edit&mode=Update&iMaintenanceTicketId=".$v['iWOId'];
            }

            $rs_notification[] = "<a class='dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0' href='".$link."'>
                    <div class='media'>
                    <i class='d-flex mr-3 ".$icon." ".$color."'></i>
                        <div class='media-body'>
                            <h6 class='mb-0 ".$color."'>".$v['title']."</h6>
                        </div>
                    </div>
                </a>";
        }
    }
    echo json_encode($rs_notification);
    hc_exit();
}else if($mode == "addCustomizePanelData"){
    include_once($controller_path . "user.inc.php");
    $UserObj = new User();

    $data = array(
        'iUserId' => $userid,
        'color' => $_REQUEST['color'],
        'layout' => $_REQUEST['layout'],
        'style' => $_REQUEST['style'],
        'bCompactMenu' => ($_REQUEST['compact']=="compact")?'TRUE':'FALSE',
        'bsmallMenu' => ($_REQUEST['compact']=="small-meni-icon")?'TRUE':'FALSE'
    );

    $result  = array();
    $rs_tot = $UserObj->add_Customize_Panel_Data($data);

    if($rs_tot){
        $result['msg'] = 'Customize Panel Updated Successfully.';
        $result['error']= 0 ;
    }else{
        $result['msg'] = 'ERROR - in update Customize Panel' ;
        $result['error']= 1 ;

    }
    //$jsonData = array('total' => $rs_tot);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
}
?>