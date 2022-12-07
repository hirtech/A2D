<?php
//echo "<pre>";print_r($_REQUEST);exit;

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Mosquito Count", 'List');
$access_group_var_delete = per_hasModuleAccess("Mosquito Count", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Mosquito Count", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Mosquito Count", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Mosquito Count", 'Edit', 'N');
# ----------- Access Rule Condition -----------

# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '0');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------


if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    
    if ($iPremiseId != "") {
        $arr_param['iPremiseId'] = $iPremiseId;
    }
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    //echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."trap_mosquito_count_list.json";
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
    //echo "<pre>";print_r(json_encode($result_arr['result']));exit();

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_data = $result_arr['result']['data'];
	$ni = count($rs_data);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $action = '';

            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'lab_task/task_mosquito_count&mode=list&iTTId='.$rs_data[$i]['iTTId'].'"><i class="fa fa-edit"></i></a>';
            }

            $entry[] = array(
                "iTTId" => $rs_data[$i]['iTTId'],
                "vName" => $rs_data[$i]['vName'],
                "vAddress" => $rs_data[$i]['vAddress'],
                "dTrapPlaced" => date_getDateTimeDDMMYYYY($rs_data[$i]['dTrapPlaced']),
                "dTrapCollected" => date_getDateTimeDDMMYYYY($rs_data[$i]['dTrapCollected']),
                "vTrapName" => $rs_data[$i]['vTrapName'],
                "tNotes" => $rs_data[$i]['tNotes'],
                "malecount" => $rs_data[$i]['malecount'],
                "femalecount" =>$rs_data[$i]['femalecount'],
                "totalcount" => $rs_data[$i]['totalcount'],
                "actions" => ($action!="")?$action:"---"
            );
        }
    }

    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
 
}



$module_name = "Mosquito Count List";
$module_title = "Mosquito Count";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);


?>