<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Inventory", 'List');
$access_group_var_delete = per_hasModuleAccess("Inventory", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Inventory", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Inventory", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Inventory", 'Edit', 'N');
# ----------- Access Rule Condition -----------

$page = $_REQUEST['page'];
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------

include_once($controller_path . "inventory_count.inc.php");
$InvCount_Obj = new InventoryCount();
$iICId = $_POST['iICId'];


if($mode == "List"){
	
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }


    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."inventory_count_list.json";
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    //echo $API_URL."<pre>";print_r(json_encode($arr_param));exit();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));

    $response = curl_exec($ch);
    curl_close($ch);  
   
    $result_arr = json_decode($response, true);

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $rs_list = $result_arr['result']['data'];
	$ni = count($rs_list);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $entry[] = array(
                "iICId" => $rs_list[$i]['iICId'],
                "vName" => $rs_list[$i]['vName'],
                "date" => date_getDateTimeDDMMYYYY($rs_list[$i]['date']),
                "lastInvCount"=> $rs_list[$i]['lastInvCount'],
                "estlevel" => $rs_list[$i]['estlevel'],
                "purchInvCount" => $rs_list[$i]['purchInvCount'],
                'usedInvCount' => $rs_list[$i]['usedInvCount'],
                "actions" => '<a title="Detail" class="text-primary" href="'.$site_url.'inventory/inventory_detail&iTPId='.$rs_list[$i]['iTPId'].'">Detail</a>'
            );
        }
    }

    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Add"){
	//echo "<pre>";print_r($_POST);exit();

    //echo "<pre>";print_r($_POST);exit();
     $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
       	$rqty = is_numeric($_POST['rqty'])?$_POST['rqty']:'0';

        $arr_param = array(
            "iTPId"       => $_POST['iTPId'],
            "rQty"        => $rqty,
            "dDate"       => $_POST['dDate'],
            "iUserId"     => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "sessionId"   => $_SESSION["we_api_session_id".$admin_panel_session_suffix]
        );

        $API_URL = $site_api_url."inventory_count_add.json";
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
       // echo $API_URL."<pre>";print_r(json_encode($arr_param));exit();

        $iICId = curl_exec($ch);  
        curl_close($ch);  

        if($iICId){
            $result['msg'] = MSG_ADD;
            $result['error']= 0 ;
        }else{
            $result['msg'] = MSG_ADD_ERROR;
            $result['error']= 1 ;
        }
    }else {
        $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;
    }
     //echo "<pre>";print_r($result);exit(); 

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}else if($mode == "Update"){
    //echo "<pre>";print_r($_POST);exit();

    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        $rqty = is_numeric($_POST['rqty'])?$_POST['rqty']:'0';

        $arr_param = array(
            "iICId"     => $_POST['iICId'],
            "iTPId"     => $_POST['iTPId'],
            "rQty"      => $rqty,
            "dDate"     => $_POST['dDate'],
            "iUserId"   => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "sessionId" => $_SESSION["we_api_session_id".$admin_panel_session_suffix]
        );

        $API_URL = $site_api_url."inventory_count_edit.json";
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
       // echo $API_URL."<pre>";print_r(json_encode($arr_param));exit();

        $iICId = curl_exec($ch);  
        curl_close($ch);

        if($iICId){
            $result['msg'] = MSG_UPDATE;
            $result['error']= 0 ;
        }else{
            $result['msg'] = MSG_UPDATE_ERROR;
            $result['error']= 1 ;
        }
    }else {
        $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;
    }
     //echo "<pre>";print_r($result);exit(); 

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}



//Treatment Product Array
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."treatment_product_dropdown.json";
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
$smarty->assign("treat_prod_arr", $res['result']);

$module_name = "Inventory List";
$module_title = "Inventory";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);

$smarty->assign("dDate", date('Y-m-d'));

?>