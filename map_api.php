<?php 
include_once("server.php");

include_once($controller_path . "fieldmap.inc.php");

$mapObj = new Fieldmap();

$action = $_POST['action'];
$data = $mapObj->$action($_POST, $site_url); 

if(count($data)>0){
	ob_start('ob_gzhandler');
    echo json_encode($data);
    die;
} else {
    echo false;
    die;
}

?>