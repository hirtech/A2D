<?php
//echo "<pre>";print_r($_REQUEST);exit();

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Inventory", 'List');
$access_group_var_delete = per_hasModuleAccess("Inventory", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Inventory", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Inventory", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Inventory", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "inventory_count.inc.php");
include_once($controller_path . "inventory_purchases.inc.php");
include_once($controller_path . "task_treatment.inc.php");
include_once($controller_path . "treatment_product.inc.php");

$InvCount_Obj = new InventoryCount();
$InvPurch_Obj = new InventoryPurchase();
$TaskTreatmentObj = new TaskTreatment();
$TProdObj = new TreatmentProduct();

$page = $_REQUEST['page'];
$iTPId = $_REQUEST['iTPId']; 
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '7');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------

if($mode == "List"){
    
    $arr_param = array();

    $arr_param['iTPId']=$iTPId ;
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."inventory_purchase_list.json";
 
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
    $arr = $hidden_arr = array();
    $rs_data = $result_arr['result']['data'];

        //echo "<pre>"; print_r($result_arr); exit;
    $tmp_arr =array();
    $ind = 0;
    $tot_purchase = 0;
    $tot_uses = 0;
    $tot_balance = 0;

    if($total > 0){
        for($i=0;$i<$total;$i++){

            $arr[] = array(
                "dDate"=> $rs_data[$i]['dDate'],
                "purchase"=> $rs_data[$i]['purchase'],
                "uses" => $rs_data[$i]['uses'],
                "balance" => $rs_data[$i]['balance'],
                "actions" => $rs_data[$i]['actions'],
            );

            $tot_uses += $rs_data[$i]['uses'];
        }
        //sort array by date in ascending order  
        $key = array_column($arr, 'dDate');
        array_multisort($key, SORT_ASC, $arr);

        $other_data = $result_arr['result']['other_data'];
        $tot_balance = $other_data['tot_balance'];
        $tot_purchase = $other_data['tot_purchase'];
    }

   // echo "<pre>";print_r($result_arr['result']);
    $ni = count($arr);
    //echo $ni;exit();
    if ($ni > 0)
    {
        if ($ni > $page_length)
        {
            if ($start != 0)
            {
                if ($page_length != $start)
                {
                    if($start < $page_length){
                        $start1 = ($ni - $page_length - $start);
                    }
                    else{
                        $start1 = $start;
                    }
                }
                else
                {
                    $start1 = $page_length;
                }
            }
            else
            {
                $start1 = ($start < $page_length) ? 0 : ($ni - $page_length);
            }

            $end1 = ($ni - $start >= $page_length) ? ($start + $page_length) : $ni;
        }
        else
        {
            $start1 = 0;
            $end1 = $ni;
        }

        $total_current_balance = (float)$tot_balance+(float)$tot_purchase-(float)$tot_uses;
        $tmp_arr = array(
            'total_purchase' => $tot_purchase,
            'total_balance' => $total_current_balance ,
            'total_uses' => $tot_uses,
        );

        for ($i = $start1;$i < $end1;$i++)
        {
            //$entry[] =$arr[$i]; 
            $entry[] = array(
                "dDate"=> $arr[$i]['dDate'],
                "purchase"=> $arr[$i]['purchase'],
                "uses" => $arr[$i]['uses'],
                "balance" =>$arr[$i]['balance'],
                "actions" => $arr[$i]['actions'],
                "total_uses" => $tot_uses,
                "total_balance" => $total_current_balance ,
                "total_purchase" => $tot_purchase,
            );
        }

        $total = count($entry);

        //$entry['footer_arr'] = $tmp_arr;
        $jsonData = array(
            'sEcho' => $sEcho,
            'iTotalDisplayRecords' => $ni,
            'iTotalRecords' => $ni
        );

        $jsonData['aaData'] = $entry;
        $jsonData['footerarr'] = $tmp_arr;
    }

    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}
else if($mode == "AddPurchaseData"){

    //echo "<pre>";print_r($_POST);exit();

    $arr_param = array();
    $result =array();
    if (isset($_POST) && count($_POST) > 0) {
        $qty = is_numeric($_POST['rPurQty'])?$_POST['rPurQty']:'0';

        $arr_param = array(
            "iTPId"   => $_POST['iTPId'],
            "rPurchQty"    => $qty,
            "dPurchDate"   => $_POST['dPurchDate'],
            "iUserId" => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "sessionId" => $_SESSION["we_api_session_id".$admin_panel_session_suffix]
        );


        $API_URL = $site_api_url."inventory_purchase_add.json";
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
        //echo $API_URL."<pre>";print_r(json_encode($arr_param));exit();

        $iIPId = curl_exec($ch);  
        curl_close($ch);  

        if($iIPId){
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
}else if($mode == "EditPurchaseData"){

    //echo "<pre>";print_r($_POST);exit();
     $arr_param = array();
     $result = array();
    
    if (isset($_POST) && count($_POST) > 0) {
       $qty = is_numeric($_POST['rPurQty'])?$_POST['rPurQty']:'0';

        $arr_param = array(
            "iIPId" => $_POST['iIPId'],
            "iTPId"   => $_POST['iTPId'],
            "rPurchQty"    => $qty,
            "dPurchDate"   => $_POST['dPurchDate'],
            "iUserId" => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "sessionId" => $_SESSION["we_api_session_id".$admin_panel_session_suffix]
        );

        $API_URL = $site_api_url."inventory_purchase_edit.json";
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

        $iIPId = curl_exec($ch);  
        curl_close($ch);

        if($iIPId){
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




$inv_data = array();
if($iTPId != ""){
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
    $InvCount_Obj->param['order_by'] = 'inventory_count."dDate" desc'  ;
    $InvCount_Obj->param['limit'] = "LIMIT 1";
    $InvCount_Obj->setClause();
    $InvCount_Obj->debug_query = false;
    $rs_invcount = $InvCount_Obj->recordset_list();

    $inv_data=$rs_invcount[0];
}
//echo "<pre>";print_r($inv_data);exit();
$smarty->assign("inv_data",$inv_data);

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

$module_name = "Inventory Detail";
$module_title = "Inventory";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("iTPId",$iTPId);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);


$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));

?>