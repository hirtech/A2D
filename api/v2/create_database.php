<?php 
include_once($controller_path . "county_db_generate.inc.php");
$DBOBJ = new DB_GENERATE();

if($request_type == "create_database"){
	$where_arr = array();
	$iCountySaasId = $RES_PARA['iCountySaasId']; 
	$vCountyName = $RES_PARA['vCountyName']; 
	//exit;
	if($iCountySaasId != '') {
		$where_arr[] = '"iCountySaasId" = '.$iCountySaasId.'';
	}

	$join_fieds_arr = array();
	$join_arr = array();
	$DBOBJ->clear_variable();
    $DBOBJ->join_field = $join_fieds_arr;
    $DBOBJ->join = $join_arr;
    $DBOBJ->where = $where_arr;
    $DBOBJ->setClause();
    $DBOBJ->debug_query = false;
	$res=$DBOBJ->recordset_list();

	$host = $res[0]['vDBHostName'];
	$db = $res[0]['vDBName']; 
	$user = $res[0]['vDBUserName'];
	$pass = $res[0]['vDBPassword'];

	$create_db_res =  $DBOBJ->create_db($host, $db, $user, $pass, $vCountyName);
	$response_data = $create_db_res;
}
?>