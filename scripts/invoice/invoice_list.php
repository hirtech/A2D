<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Invoice", 'List');
$access_group_var_delete = per_hasModuleAccess("Invoice", 'Delete', 'N');
$access_group_var_add = per_hasModuleAccess("Invoice", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Invoice", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Invoice", 'CSV', 'N');

# ----------- Access Rule Condition -----------
# ------------------------------------------------------------
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
    // echo "<pre>"; print_r($_REQUEST);exit();
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }

    $arr_param['dSInvoiceDate']     = $_POST['dSInvoiceDate'];
    $arr_param['dSPaymentDate']     = $_POST['dSPaymentDate'];
    $arr_param['iSBillingMonth']    = $_POST['iSBillingMonth'];
    $arr_param['iSBillingYear']     = $_POST['iSBillingYear'];
    $arr_param['iSPremiseId']       = trim($_POST['iSPremiseId']);
    $arr_param['vSPremiseNameDD']   = $_POST['vSPremiseNameDD'];
    $arr_param['vSPremiseName']     = trim($_POST['vSPremiseName']);
    $arr_param['iSServiceType']     = trim($_POST['iSServiceType']);
    $arr_param['dSStartDate']       = trim($_POST['dSStartDate']);
    $arr_param['iSStatus']          = $_POST['iSStatus'];

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."invoice_list.json";
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
    // echo "<pre>"; print_r($result_arr);exit();

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_invoice = $result_arr['result']['data'];
    // echo "<pre>"; print_r($rs_invoice);exit();
    $ni = count($rs_invoice);
    
    $vmonthvalue = array("1"=>"Jan","2"=>"Feb", "3"=>"Mar","4"=>"Apr","5"=>"May","6"=>"Jun","7"=>"Jul","8"=>"Aug","9"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dec");
    $vStatus = "";
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            /*if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_invoice[$i]['iInvoiceId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }*/

            if ($access_group_var_CSV == '1') {
                $action .= '<a class="btn btn-outline-info" title="Download Invoice" href="'.$site_url.'invoice/customer_invoice&download=1&iInvoiceId='.$rs_invoice[$i]['iInvoiceId'].'"><i class="fas fa-download"></i></a>';
            }

            $action .= ' <a class="btn btn-outline-info" title="View Invoice" href="'.$site_url.'invoice/customer_invoice&download=0&iInvoiceId='.$rs_invoice[$i]['iInvoiceId'].'"><i class="fas fa-info-circle"></i></a>';

            $action .= ' <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Change status">Status</button>
                <div class="dropdown-menu p-0">
                    <a class="dropdown-item" href="javascript:void(0);" onclick="changeInvoiceStatus('.$rs_invoice[$i]['iInvoiceId'].', 0)">Draft</a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="changeInvoiceStatus('.$rs_invoice[$i]['iInvoiceId'].', 1)">Sent</a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="changeInvoiceStatus('.$rs_invoice[$i]['iInvoiceId'].', 2)">Paid</a>
                </div>
            </div>';

            if($rs_invoice[$i]['iStatus'] == 0){
                $vStatus = '<a class="text-secondary font-weight-bold" href="javascript:void(0)" title="Invoice Status">Draft</a>';
            }else if($rs_invoice[$i]['iStatus'] == 1){
                $vStatus = '<a class="text-info font-weight-bold" href="javascript:void(0)" title="Invoice Status">Sent</a>';
            }else if($rs_invoice[$i]['iStatus'] == 2){
                $vStatus = '<a class="text-success font-weight-bold" href="javascript:void(0)" title="Invoice Status">Paid</a>';
            }

            $entry[] = array(
                "iInvoiceId"        => $rs_invoice[$i]['iInvoiceId'],
                "iCustomerId"       => $rs_invoice[$i]['vCompanyName'],
                "vPONumber"         => $rs_invoice[$i]['vPONumber'],
                "dInvoiceDate"      => date_getDateFMY($rs_invoice[$i]['dInvoiceDate']),
                "dPaymentDate"      => date_getDateFMY($rs_invoice[$i]['dPaymentDate']),
                "BillingMonth"      => $vmonthvalue[$rs_invoice[$i]['iBillingMonth']].', '.$rs_invoice[$i]['iBillingYear'],
                "rTotalAmount"      => gen_make_currency_format($rs_invoice[$i]['rTotalAmount']),
                "iStatus"           => $vStatus,
                "actions"           => ($action != "") ? $action : "---"
            );
        }
    }
    $jsonData['aaData'] = $entry;
    //echo "<pre>";print_r($jsonData);exit();
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Add"){
    $arr_param = array();
    // echo "<pre>";print_r($_POST);exit;
    $arr_param = array(
        "iCustomerId"       => $_POST['iCustomerId'],
        "vPONumber"         => $_POST['vPONumber'],
        "dInvoiceDate"      => $_POST['dInvoiceDate'],
        "dPaymentDate"      => $_POST['dPaymentDate'],
        "iBillingMonth"     => $_POST['iBillingMonth'],
        "iBillingYear"      => $_POST['iBillingYear'],
        "tNotes"            => $_POST['tNotes'],
        "iLoginUserId"      => $_SESSION['sess_iUserId' . $admin_panel_session_suffix],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."invoice_add.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_param);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: multipart/form-data",
    ));
    $response = curl_exec($ch);
    curl_close($ch); 
    $result_arr = json_decode($response, true); 
    // echo "<pre>;";print_r($result_arr);exit;
    if(isset($result_arr['iInvoiceId'])){
        $result['iInvoiceId']           = $result_arr['iInvoiceId'];
        $result['msg']                  = $result_arr['Message'];
        $result['error']                = 0 ;
    }else{
        $result['msg']      = $result_arr['Message'];
        $result['error']    = 1 ;
    }
    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}else if($mode == "change_status"){
    $result     = array();
    $arr_param  = array();
    $iInvoiceId = $_POST['iInvoiceId'];
    $iStatus    = $_POST['iStatus'];
    
    $arr_param['iInvoiceId']    = $iInvoiceId; 
    $arr_param['iStatus']       = $iStatus; 
    $arr_param['iLoginUserId']  = $_SESSION['sess_iUserId' . $admin_panel_session_suffix]; 
    $arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."invoice_change_status.json";
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
    // echo "<pre>;";print_r($result_arr);exit;
    if(isset($result_arr)){
        $result['msg']     = $result_arr['Message'];
        $result['error']   = $result_arr['error'] ;
    }else{
        $result['msg']     = "ERROR - in Invoice status.";
        $result['error']   = 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}

//Service Type Dropdown
$stype_param = array();
$stype_param['iStatus'] = '1';
$stype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$stypeAPI_URL = $site_api_url."service_type_dropdown.json";
//echo $stypeAPI_URL." ".json_encode($stype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $stypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_stype = curl_exec($ch);
curl_close($ch);  
$res_stype = json_decode($response_stype, true);
$rs_stype = $res_stype['result'];
$smarty->assign("rs_stype", $rs_stype);
//echo "<pre>";print_r($rs_stype);exit;

$dSMonth = date_getMonthDigitWithMonthDropDown($selmonth="",$fieldId="iSBillingMonth",$first_options="Select Month", $other_parameter='required');
$dSYear = date_getYearDropDown($selday="",$fieldId="iSBillingYear",$limitStart="2022",$limitEnd="2030",$first_options="Select Year", $other_parameter='required');

$module_name = "Invoice List";
$module_title = "Invoice";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);
$smarty->assign("dSMonth",$dSMonth);
$smarty->assign("dSYear",$dSYear);