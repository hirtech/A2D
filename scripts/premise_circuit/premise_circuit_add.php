<?php
//echo "<pre>";print_R($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Premise Circuit", 'Edit');

} else {
    per_hasModuleAccess("Premise Circuit", 'Add');
}
# ----------- Access Rule Condition -----------
$access_group_var_list = per_hasModuleAccess("Premise Circuit", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Premise Circuit", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise Circuit", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise Circuit", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise Circuit", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "premise_circuit.inc.php");

$PremiseCircuitObj = new PremiseCircuit();
$iPremiseId = $_REQUEST['iPremiseId'];
if($mode == "Update") {
    $iPremiseCircuitId = $_REQUEST['iPremiseCircuitId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();

    $where_arr[] = "premise_circuit.\"iPremiseCircuitId\"='".gen_add_slash($iPremiseCircuitId)."'";
    $join_fieds_arr[] = 'wt."vType" as "vWorkOrderType"';
    $join_fieds_arr[] = 'so."iPremiseId"';
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
    
    $join_arr[] = " LEFT JOIN workorder w ON premise_circuit.\"iWOId\" = w.\"iWOId\"";
    $join_arr[] = " LEFT JOIN workorder_type_mas wt ON w.\"iWOTId\" = wt.\"iWOTId\"";
    $join_arr[] = " LEFT JOIN service_order so ON w.\"iServiceOrderId\" = so.\"iServiceOrderId\"";
    $join_arr[] = " LEFT JOIN premise_mas s ON so.\"iPremiseId\" = s.\"iPremiseId\"";
    $join_arr[] = " LEFT JOIN site_type_mas st ON s.\"iSTypeId\" = st.\"iSTypeId\"";
    $PremiseCircuitObj->join_field = $join_fieds_arr;
    $PremiseCircuitObj->join = $join_arr;
    $PremiseCircuitObj->where = $where_arr;
    $PremiseCircuitObj->param['limit'] = "LIMIT 1";
    $PremiseCircuitObj->setClause();
    $PremiseCircuitObj->debug_query = false;
    $rs_data = $PremiseCircuitObj->recordset_list();
    if(!empty($rs_data)) {
        $vWODisplay = " Workorder ID#".$rs_data[0]['iWOId']." (".$rs_data[0]['vWorkOrderType']."; Premise ID# ".$rs_data[0]['iPremiseId'].";".$rs_data[0]['vPremiseName'].";".$rs_data[0]['vPremiseType'].")";

        $rs_data[0]['vWODisplay'] = $vWODisplay;
        $vPremiseDisplay = $rs_data[0]['iPremiseId']." (".$rs_data[0]['vPremiseName'].";".$rs_data[0]['vPremiseType'].")";
        $rs_data[0]['vPremiseDisplay'] = $vPremiseDisplay;

        if($rs_data[0]['vFile'] != ""){
            if(file_exists($premise_circuit_path.$rs_data[0]['vFile'])){
            
                $download_path = $premise_circuit_path.$rs_data[0]['vFile'];
                $download_url = $premise_circuit_url.$rs_data[0]['vFile'];
                
                $file_url = $site_url.'download.php?vFileName_path='.base64_encode($download_path).'&vFileName_url='.base64_encode($download_url).'&file_name='.base64_encode($rs_data[0]['vFile']);
                $rs_data[0]['file_url'] = $file_url;
            }
        }
    }
    //echo "<pre>";print_r($rs_data);exit;
}else if($mode == "search_workorder"){
    $arr_param = array();
    //echo "<pre>";print_r($_REQUEST);exit;
    $vWorkOrder = trim($_REQUEST['vWorkOrder']);
    $iPremiseId = trim($_REQUEST['iPremiseId']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['vWorkOrder'] = $vWorkOrder;
    $arr_param['iPremiseId'] = $iPremiseId;
    $API_URL = $site_api_url."search_workorder.json";
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
    $result_arr = json_decode($response, true);
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}else if($mode =="search_premise"){
    $arr_param = array();
    $vPremiseName = trim($_REQUEST['vPremiseName']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vPremiseName;
    $API_URL = $site_api_url."search_premise_address.json";
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
    $result_arr = json_decode($response, true);
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}else {
    if($iPremiseId > 0){
        /******** Get Premise Name From Premise Id ********/
        $premise_param = array();
        $premise_param['iPremiseId']    = $iPremiseId;
        $premise_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
        $premiseAPI_URL = $site_api_url."get_premise_name_from_id.json";
        //echo $premiseAPI_URL." ".json_encode($premise_param);exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $premiseAPI_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_param));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/json",
        )); 
        $response_premise = curl_exec($ch);
        curl_close($ch);  
        $res_premise = json_decode($response_premise, true);
        $vPremiseName = $res_premise['result'];
        //echo "<pre>";print_r($vPremiseName);exit;
        $rs_data[0]['iPremiseId'] = $iPremiseId;
        $rs_data[0]['vPremiseDisplay'] = $iPremiseId." (".$vPremiseName.")";
    }
}

/*************** Circuit Dropdown***************/
$circuit_param = array();
$circuit_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$circuitAPI_URL = $site_api_url."circuit_dropdown.json";
//echo $circuitAPI_URL." ".json_encode($circuit_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $circuitAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($circuit_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_circuit = $res['result'];
$smarty->assign("rs_circuit", $rs_circuit);
//echo"<pre>";print_r($rs_circuit);exit;
/*************** Circuit Dropdown ***************/

//Connection Type Dropdown
$cntype_param = array();
$cntype_param['iStatus'] = '1';
$cntype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$cntypeAPI_URL = $site_api_url."connection_type_dropdown.json";
//echo $cntypeAPI_URL." ".json_encode($cntype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cntypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cntype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_cntype = curl_exec($ch);
curl_close($ch);  
$res_cntype = json_decode($response_cntype, true);
$rs_cntype = $res_cntype['result'];
$smarty->assign("rs_cntype", $rs_cntype);
//echo "<pre>";print_r($rs_cntype);exit;

$module_name = "Premise Circuit";
$module_title = "Premise Circuit";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_data", $rs_data);
?>