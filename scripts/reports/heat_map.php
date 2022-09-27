<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");

# ----------- Access Rule Condition -----------
per_hasModuleAccess("Heat Map", 'List');
# ----------- Access Rule Condition -----------

# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
# ------------------------------------------------------------

# ------------------  Create Map  ------------------

 if($mode == "create_heat_map"){
	$arr_param = array();
	if (isset($_POST) && count($_POST) > 0) {
		$vLayer = $_POST['vLayer'];
		$dFromDate = $_POST['dFromDate'];
		$dToDate = $_POST['dToDate'];

		$arr_param = array(
			"vLayer"    => $vLayer,
			"dFromDate" => $dFromDate,
			"dToDate"   => $dToDate,
    		'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
		);

		$API_URL = $site_api_url."create_heat_map.json";
		//echo "<pre>";print_r($API_URL);exit();
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

		echo json_encode($result_arr['result']);
		hc_exit();
	}
}   
# ------------------ Create Map  ------------------

$module_name = "Heat Map";
$module_title = "Heat Map";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
?>