<?php
include_once($controller_path . "awareness.inc.php");

$AwarenessObj = new Awareness();
if($request_type == "awareness_add"){
    //echo "<pre>";print_r($RES_PARA);exit;
    $insert_arr = array(
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iFiberInquiryId"       => $RES_PARA['iFiberInquiryId'],
        "dDate"                 => $RES_PARA['dDate'],
        "dStartDate"            => $RES_PARA['dStartDate'],
        "dEndDate"              => $RES_PARA['dEndDate'],
        "iTaskTypeId"           => $RES_PARA['iTaskTypeId'],
        "tNotes"                => $RES_PARA['tNotes'],
        "iLoginUserId"          => $RES_PARA['iLoginUserId'],
        "iTechnicianId"         => $RES_PARA['iTechnicianId'],
    );

    $AwarenessObj->insert_arr = $insert_arr;
    $AwarenessObj->setClause();
    $iAId = $AwarenessObj->add_records();

    if($iAId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iAId" => $iAId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>