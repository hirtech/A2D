<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Invoice", 'Edit');
} else {
    per_hasModuleAccess("Invoice", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Invoice", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Invoice", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Invoice", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Invoice", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Invoice", 'Edit', 'N');
# ----------- Access Rule Condition -----------

//Carrier (Company) Dropdown
$carrier_param = array();
$carrier_param['iStatus'] = '1';
$carrier_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$carrierAPI_URL = $site_api_url."company_dropdown.json";
//echo $carrierAPI_URL." ".json_encode($carrier_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $carrierAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($carrier_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_carrier = $res['result'];
$smarty->assign("rs_carrier", $rs_carrier);
//echo "<pre>";print_r($rs_carrier);exit;

$dMonth = date_getMonthDigitWithMonthDropDown($selmonth="",$fieldId="iBillingMonth",$first_options="Select Month", $other_parameter='required');
$dYear = date_getYearDropDown($selday="",$fieldId="iBillingYear",$limitStart="2022",$limitEnd="2030",$first_options="Select Year", $other_parameter='required');

$module_name = "Invoice ";
$module_title = "Invoice";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);
$smarty->assign("dMonth",$dMonth);
$smarty->assign("dYear",$dYear);
?>