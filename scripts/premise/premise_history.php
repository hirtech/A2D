<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Premise", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Premise", 'CSV', 'N');
// $access_group_var_Respond = per_hasModuleAccess("Premise", 'Respond', 'N');
# ----------- Access Rule Condition -----------


include_once($controller_path . "premise.inc.php");

$page = $_REQUEST['page'];
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '1');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
$SiteObj = new Site();

$iSiteId = $_REQUEST['iSiteId'];
$vName = $_REQUEST['vName'];


if($mode == "History"){
    
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    /*switch ($display_order)
    {
        case "0":
            $sortname = 'dDate';
        break;            
        default:
            $sortname = 'dDate';
        break;
    }*/

    $arr_param['iSiteId'] = $_REQUEST['iSiteId'];
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    
    $API_URL = $site_url."get_premise_history.json";
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


    $result_arr = json_decode($response, true);
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
///$vName = $_REQUEST['vName'];

$module_name = "Premise History";
$module_title = "Premise History";
$smarty->assign("module_name", $module_name);
$smarty->assign("iSiteId", $iSiteId);
$smarty->assign("vName", $vName);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iSiteId", $iSiteId);

?>