<?php
//echo "<pre>";print_R($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Circuit", 'Edit');

} else {
    per_hasModuleAccess("Circuit", 'Add');
}
# ----------- Access Rule Condition -----------
$access_group_var_list = per_hasModuleAccess("Circuit", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Circuit", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Circuit", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Circuit", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Circuit", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "circuit.inc.php");

$CircuitObj = new Circuit();

if($mode == "Update") {
    $iCircuitId = $_REQUEST['iCircuitId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "circuit.\"iCircuitId\"='".$iCircuitId."'";
    $CircuitObj->join_field = $join_fieds_arr;
    $CircuitObj->join = $join_arr;
    $CircuitObj->where = $where_arr;
    $CircuitObj->param['limit'] = "LIMIT 1";
    $CircuitObj->setClause();
    $rs_data = $CircuitObj->recordset_list();
    if(!empty($rs_data)) {
        ## --------------------------------
        # Get Premise Circuit Data from Circuit
        $arr_param = array();
        $arr_param = array(
            "iCircuitId"    => $rs_data[0]['iCircuitId'],
            "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        $API_URL = $site_api_url."get_premise_circuit_from_circuit_id.json";
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
        $response_pc = curl_exec($ch);
        curl_close($ch); 
        $rs_pc = json_decode($response_pc, true); 
        $premise_circuit_arr = $rs_pc['result'];
        $cnt_premise_circuit = count($premise_circuit_arr);
        ## --------------------------------
    }
}


/*************** Circuit Type Dropdown ***************/
$ctype_param = array();
$ctype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ctypeAPI_URL = $site_api_url."circuit_type_dropdown.json";
//echo $ctypeAPI_URL." ".json_encode($ctype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ctypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ctype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_ctype = $res['result'];
$smarty->assign("rs_ctype", $rs_ctype);
//echo"<pre>";print_r($rs_ctype);exit;
/*************** Link Type Dropdown ***************/

/************** Network Dropdown **************/
$network_arr_param = array();
$network_arr_param['iStatus'] = "1";
$network_arr_param['order_by'] = '"vName" asc';
$network_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$NETWORK_API_URL = $site_api_url."network_list.json";
//echo $NETWORK_API_URL." ".json_encode($network_arr_param);exit;
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $NETWORK_API_URL);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch1, CURLOPT_HEADER, FALSE);
curl_setopt($ch1, CURLOPT_POST, TRUE);
curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($network_arr_param));
curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$network_response = curl_exec($ch1);
curl_close($ch1);  
$network_result_arr = json_decode($network_response, true);
$network_arr = $network_result_arr['result']['data'];
//echo "<pre>";print_r($network_arr);exit;
/************** Network Dropdown **************/

$module_name = "Circuit";
$module_title = "Circuit";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_data", $rs_data);
$smarty->assign("cnt_premise_circuit", $cnt_premise_circuit);
$smarty->assign("premise_circuit_arr", $premise_circuit_arr);
$smarty->assign("network_arr", $network_arr);
?>