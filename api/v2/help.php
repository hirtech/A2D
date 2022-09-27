<?php 
include_once($controller_path . "help.inc.php");
$HELPObj = new HELP();

if($request_type == "help_headers"){

	$where_arr = array();
	$join_fieds_arr = array();
	$HELPObj->clear_variable();
    $HELPObj->join_field = $join_fieds_arr;
    $HELPObj->join = $join_arr;
    $HELPObj->where = $where_arr;
    $HELPObj->param['order_by'] = '"iDisplayOrder"';
    $HELPObj->setClause();
    $HELPObj->debug_query = false;
    $rs_help['header_list'] =  $HELPObj->recordset_list();
    $where_arr = array();
	$join_fieds_arr = array();
    $join_fieds_arr[] = " (SELECT count(helpslides.\"iHSId\") FROM helpslides WHERE helplist.\"iHLId\" =  helpslides.\"iHLId\") as \"totSlides\" ";
    $HELPObj->join_field = $join_fieds_arr;
    $HELPObj->join = $join_arr;
    $HELPObj->where = $where_arr;
    $HELPObj->param['order_by'] = 'helplist."iDisplayOrder"';
    $HELPObj->setClause();
    $HELPObj->debug_query = false;
    $rs_help['help_list'] =  $HELPObj->help_list();
	
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $rs_help);
}
elseif ($request_type=="help_sliders") {
	$id=str_replace('accordion', '', $RES_PARA['iHLId']); 
	$where_arr = array();
	$join_fieds_arr = array();
	if($id != '') {
		$where_arr[] = '"iHLId" = '.$id.'';
	}
	$HELPObj->clear_variable();
    $HELPObj->join_field = $join_fieds_arr;
    $HELPObj->join = $join_arr;
    $HELPObj->where = $where_arr;
    $HELPObj->param['order_by'] = '"iDisplayOrder"';
    $HELPObj->setClause();
    $HELPObj->debug_query = false;
    $rs_help =  $HELPObj->help_slider_list();
    $hi =count($rs_help);
    if($hi > 0) {
    	for($h=0; $h<$hi; $h++){
    		$rs_help[$h]['vImagePath'] = $help_path.$rs_help[$h]['vImage'];
    		$rs_help[$h]['vImageUrl'] = $help_url.$rs_help[$h]['vImage'];
    	}
    }
	
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $rs_help);
}else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>