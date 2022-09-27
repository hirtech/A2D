<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');
include_once("server.php");
include_once($controller_path . "pwaapi.inc.php");

$mapObj = new PwaApi();

$action = $_GET['action'];

$data = $mapObj->$action($_GET); 

if(count($data)>0){
	ob_start('ob_gzhandler');
    echo json_encode($data);
    die;
} else {
    echo false;
    die;
}

?>