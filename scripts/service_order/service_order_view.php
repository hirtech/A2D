<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");

include_once($controller_path . "service_order.inc.php");

$iServiceOrderId = $_REQUEST['iServiceOrderId'];

$ServiceOrderObj = new ServiceOrder();

if($iServiceOrderId  > 0) {

	$arr_param['iServiceOrderId']           = $iServiceOrderId;
    $arr_param['sessionId']                 = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."service_order_list.json";
    // echo $API_URL. " ".json_encode($arr_param);exit;
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
    $result_arr = json_decode($response, true);

    $rs_sorder = $result_arr['result']['data'];
	//echo "<pre>";print_r($rs_sorder);exit();
    if(!empty($rs_sorder)){
		$vSOStatus = "";
		if($rs_sorder[0]['iSOStatus'] == 1){
            $vSOStatus = '<span class="btn btn-warning">Created</span>';
        }else if($rs_sorder[0]['iSOStatus'] == 2){
            $vSOStatus = '<span class="btn btn-primary">In Progress</span>';
        }else if($rs_sorder[0]['iSOStatus'] == 3){
            $vSOStatus = '<span class="btn btn-danger">Delayed</span>';
        }else if($rs_sorder[0]['iSOStatus'] == 4){
            $vSOStatus = '<span class="btn btn-warning">Cancelled</span>';
        }else if($rs_sorder[0]['iSOStatus'] == 5){
            $vSOStatus = '<span class="btn btn-info">Final Review</span>';
        }else if($rs_sorder[0]['iSOStatus'] == 6){
            $vSOStatus = '<span class="btn btn-dark">Carrier Approved</span>';
        }else if($rs_sorder[0]['iSOStatus'] == 7){
            $vSOStatus = '<span class="btn btn-success">Final Approved</span>';
        }

		$vCStatus = "";
		if($rs_sorder[0]['iCStatus'] == 1){
			$vCStatus = '<span class="btn btn-warning">Created</span>';
		}else if($rs_sorder[0]['iCStatus'] == 2){
			$vCStatus = '<span class="btn btn-primary">In-Progress</span>';
		}else if($rs_sorder[0]['iCStatus'] == 3){
			$vCStatus = '<span class="btn btn-danger">Delayed</span>';
		}else if($rs_sorder[0]['iCStatus'] == 4){
			$vCStatus = '<span class="btn btn-success">On-Net</span>';
		}

		$vSStatus = "";
		if($rs_sorder[0]['iSStatus'] == 0){
			$vSStatus = '<span class="btn btn-info">Pending</span>';
		}else if($rs_sorder[0]['iSStatus'] == 1){
			$vSStatus = '<span class="btn btn-success">Active</span>';
		}else if($rs_sorder[0]['iSStatus'] == 2){
			$vSStatus = '<span class="btn btn-warning">Suspended</span>';
		}else if($rs_sorder[0]['iSStatus'] == 3){
			$vSStatus = '<span class="btn btn-dark">Trouble</span>';
		}else if($rs_sorder[0]['iSStatus'] == 4){
			$vSStatus ='<span class="btn btn-danger">Disconnected</span>';
		}

		$rs_sorder[0]['vSOStatus'] = $vSOStatus;
		$rs_sorder[0]['vCStatus'] = $vCStatus;
		$rs_sorder[0]['vSStatus'] = $vSStatus;

        if($rs_sorder[0]['vFile'] != ""){
            if(file_exists($service_order_path.$rs_sorder[0]['vFile'])){
            
                $download_path = $service_order_path.$rs_sorder[0]['vFile'];
                $download_url = $service_order_url.$rs_sorder[0]['vFile'];
                
                $file_url = $site_url.'download.php?vFileName_path='.base64_encode($download_path).'&vFileName_url='.base64_encode($download_url).'&file_name='.base64_encode($rs_sorder[0]['vFile']);
                $rs_sorder[0]['file_url'] = $file_url;
            }
        }
    }
}

$module_name = "Service Order ";
$module_title = "Service Order";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", "View");
$smarty->assign("rs_sorder", $rs_sorder);
$smarty->assign("iServiceOrderId", $iServiceOrderId);
?>