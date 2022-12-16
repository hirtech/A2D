<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Premise", 'CSV', 'N');
# ----------- Access Rule Condition -----------

# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$page = $_REQUEST['page'];
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '1');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------

$iPremiseId = $_REQUEST['iPremiseId'];
$vName = $_REQUEST['vName'];
if($mode == "History"){
    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;
    $arr_param['iPremiseId']    = $_REQUEST['iPremiseId'];
    $arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    $API_URL = $site_api_url."premise_history.json";
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
    //
    $response = curl_exec($ch);
    curl_close($ch);  
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    //echo "<pre>";print_r($response);exit();
    $jsonData = array(
            'sEcho' => $sEcho,
            'iTotalDisplayRecords' => 0,
            'iTotalRecords' => 0,
            'aaData' =>array()
    );

    if(!empty($result_arr['result'])){
         $data = $result_arr['result']['data'];
         $iTotalRecords = $result_arr['result']['total_record'];
         $entry = array();

         if(!empty($data)){
            foreach($data as $k => $val){
               $entry[] = array(
                     'Date' => $val['Date'],
                     'Name' => $val['Name'],
                     'Description' => $val['Description'],
               );
            }
         }

         $jsonData = array(
            'sEcho' => $sEcho,
            'iTotalDisplayRecords' => $iTotalRecords,
            'iTotalRecords' => $iTotalRecords,
            'aaData' => $entry
        );
    }
    //echo "<pre>";print_r(json_encode($result_arr['result']));exit();
    
    # Return jSON data.
    # -----------------------------------
    //echo $result_arr['result'];
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------

}

/*--------------------------------------------------------*/
$module_name = "Premise History";
$module_title = "Premise History";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("iPremiseId", $iPremiseId);
$smarty->assign("vName", $vName);
$smarty->assign("iPremiseId", $iPremiseId);

?>