<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Treatment Product", 'List');
$access_group_var_delete = per_hasModuleAccess("Treatment Product", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Treatment Product", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Treatment Product", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Treatment Product", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Treatment Product", 'CSV', 'N');
# ----------- Access Rule Condition -----------
# General Variables
# ------------------------------------------------------------
$page = $_REQUEST['page'];
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '0');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
if($mode == "List"){
    $result_arr = array();
    $total =0;
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
    
    $API_URL = $site_api_url."treatment_product_list.json";
    //echo $API_URL. " ".json_encode($arr_param);exit;
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
    //echo "<pre>";print_r($result_arr);exit;
    $total = 0;
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    
    if(!empty($result_arr['result']['data'])){
        $ni = count($result_arr['result']['data']);

        $rs_data = $result_arr['result']['data']; 
        $entry = array();
        $jsonData['iTotalDisplayRecords'] = $result_arr['result']['total_record'];
        $jsonData['iTotalRecords'] = $result_arr['result']['total_record'];
        for($i=0;$i<$ni;$i++){
            $action = '';
            $itpId = $rs_data[$i]['iTPId'];

            if($access_group_var_edit =="1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit"  onclick="addeditTreatmentProdData('.$itpId.',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$itpId.');"><i class="fa fa-trash"></i></a>';
            }
            
             //$pesticide = ($rs_data[$i]['iPesticide'] =='Y')?'Yes':'No';
             $pesticide = $rs_data[$i]['iPesticide'];

             $tragetAppRate =($rs_data[$i]['vTragetAppRate']=="acre")?" Per acre":" Per sqft";

             $status =gen_status($rs_data[$i]['iStatus']);

            $hidden_fields = '<input type="hidden" id="tp_iTPId_'.$itpId.'" value="'.$itpId.'"><input type="hidden" id="tp_vName_'.$itpId.'" value="'.$rs_data[$i]['vName'].'"><input type="hidden" id="tp_vCategory_'.$itpId.'" value="'.$rs_data[$i]['vCategory'].'"><input type="hidden" id="tp_vClass_'.$itpId.'" value="'.$rs_data[$i]['vClass'].'"><input type="hidden" id="tp_iPesticide_'.$itpId.'" value="'.$rs_data[$i]['iPesticide'].'"><input type="hidden" id="tp_vEPARegNo_'.$itpId.'" value="'.$rs_data[$i]['vEPARegNo'].'"><input type="hidden" id="tp_vActiveIngredient_'.$itpId.'" value="'.$rs_data[$i]['vActiveIngredient'].'"><input type="hidden" id="tp_vActiveIngredient2_'.$itpId.'" value="'.$rs_data[$i]['vActiveIngredient2'].'"><input type="hidden" id="tp_vAI_'.$itpId.'" value="'.$rs_data[$i]['vAI'].'"><input type="hidden" id="tp_vAI2_'.$itpId.'" value="'.$rs_data[$i]['vAI2'].'"><input type="hidden" id="tp_iUId_'.$itpId.'" value="'.$rs_data[$i]['iUId'].'"><input type="hidden" id="tp_vAppRate_'.$itpId.'" value="'.$rs_data[$i]['vAppRate'].'"><input type="hidden" id="tp_vTragetAppRate_'.$itpId.'" value="'.$rs_data[$i]['vTragetAppRate'].'"><input type="hidden" id="tp_vMinAppRate_'.$itpId.'" value="'.$rs_data[$i]['vMinAppRate'].'"><input type="hidden" id="tp_vMaxAppRate_'.$itpId.'" value="'.$rs_data[$i]['vMaxAppRate'].'"><input type="hidden" id="tp_iStatus_'.$itpId.'" value="'.$status.'">';

            $entry[] = array(
                "iTPId" => $itpId,
                "vName" => gen_strip_slash($rs_data[$i]['vName']).$hidden_fields,
                "vCategory" => gen_strip_slash($rs_data[$i]['vCategory']),
                "iPesticide" => $pesticide,
                "vClass" => gen_strip_slash($rs_data[$i]['vClass']),
                "vEPARegNo" => gen_strip_slash($rs_data[$i]['vEPARegNo']),
                "iUId" => $rs_data[$i]['vUnit'],
                "vTragetAppRate" =>$rs_data[$i]['vAppRate'].$tragetAppRate,
                "iStatus" => '<span data-toggle="tooltip" data-placement="top" title="'.$status.'" class="badge badge-pill badge-'.$status_color[ $status].'">&nbsp;</span>',
                            
                "actions" => ($action!="")?$action:"---"
       
            );
        }
        
        $jsonData['aaData'] = $entry;
        
    }

    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();

    //echo "<pre>";print_r($_REQUEST);
    # -----------------------------------

}else if($mode == "Add"){
    $result = array();
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        $status = isset($_POST['iStatus'])?$_POST['iStatus']:"0";
        $arr_param = array(
            "iTPId"         	=> $_POST['modal_iTPId'],
            "vName"         	=> $_POST['modal_vName'],
            "vCategory"     	=> $_POST['modal_vCategory'],
            "vClass"        	=> $_POST['modal_vClass'],
            "iPesticide"    	=> $_POST['modal_iPesticide'],
            "vEPARegNo"    		=> $_POST['modal_vEPARegNo'],
            "vActiveIngredient"	=> $_POST['modal_vActiveIngredient'],
            "vActiveIngredient2"=> $_POST['modal_vActiveIngredient2'],
            "vAI"				=> $_POST['modal_vAI'],
            "vAI2"      		=> $_POST['modal_vAI2'],
            "iUId"      		=> $_POST['modal_iUId'],
            "vAppRate"      	=> $_POST['modal_vAppRate'],
            "vTragetAppRate"    => $_POST['modal_vTragetAppRate'],
            "vMinAppRate"       => $_POST['modal_vMinAppRate'],
            "vMaxAppRate"       => $_POST['modal_vMaxAppRate'],
            "iStatus"        => $_POST['modal_iStatus'],
            "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix] 
        );

        $API_URL = $site_api_url."treatment_product_add.json";
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

        $iTPId = curl_exec($ch);
        curl_close($ch);  

        if($iTPId){
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

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}else if($mode == "Delete"){
    $result = array();
    $id = $_POST['id'];
    $arr_param['iTPId'] = $id;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."treatment_product_delete.json";
    ///echo $API_URL." ".json_encode($arr_param);exit;
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

    $rs_tot = curl_exec($ch);

    curl_close($ch);  

    if($rs_tot){    
        $result['msg'] = MSG_DELETE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error']= 1 ;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}else if($mode == "Update"){
    $result = array();
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        $status = isset($_POST['iStatus'])?$_POST['iStatus']:"0";
        $arr_param = array(
            "iTPId"             => $_POST['modal_iTPId'],
            "vName"             => $_POST['modal_vName'],
            "vCategory"         => $_POST['modal_vCategory'],
            "vClass"            => $_POST['modal_vClass'],
            "iPesticide"        => $_POST['modal_iPesticide'],
            "vEPARegNo"         => $_POST['modal_vEPARegNo'],
            "vActiveIngredient" => $_POST['modal_vActiveIngredient'],
            "vActiveIngredient2"=> $_POST['modal_vActiveIngredient2'],
            "vAI"               => $_POST['modal_vAI'],
            "vAI2"              => $_POST['modal_vAI2'],
            "iUId"              => $_POST['modal_iUId'],
            "vAppRate"          => $_POST['modal_vAppRate'],
            "vTragetAppRate"    => $_POST['modal_vTragetAppRate'],
            "vMinAppRate"       => $_POST['modal_vMinAppRate'],
            "vMaxAppRate"       => $_POST['modal_vMaxAppRate'],
            "iStatus"        => $_POST['modal_iStatus'],
            "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix] 
        );

        $API_URL = $site_api_url."treatment_product_edit.json";
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

        $rs = curl_exec($ch); 
        curl_close($ch);  
        if($rs){
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

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}

//Unit array
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."unit_multi_dropdown.json";
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
$unit_arr =$res['result'];
$smarty->assign("unit_arr", $unit_arr);

$module_name = "Treatment Product List";
$module_title = "Treatment Product";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);

$smarty->assign("access_group_var_add", $access_group_var_add);


?>