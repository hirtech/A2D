<?php 

include_once($controller_path . "user.inc.php");
include_once($controller_path . "treatment_product.inc.php");

$UserObj = new User();
$TProdObj = new TreatmentProduct();

if($request_type == "notification"){
	$notification_arr = array();
	$user_dept = array();
	$userid = $RES_PARA['userId'];

	$UserObj->user_clear_variable();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $where_arr[] = '"iUserId" ='.$userid;
    $UserObj->join_field = $join_fieds_arr;
    $UserObj->join = $join_arr;
    $UserObj->where = $where_arr;
    $UserObj->param['limit'] = "0";
    $UserObj->param['order_by'] = '';
    $UserObj->setClause();
    $rs_user_dept = $UserObj->user_department_list();
    $ui = count($rs_user_dept);

    if($ui > 0){
    	for($u=0;$u<$ui;$u++){
    		$user_dept[] = $rs_user_dept[$u]['iDepartmentId'];
    	}
    }


	$sql = "SELECT * FROM fiberinquiry_details order by \"iFiberInquiryId\" ";
	$rs_db = $sqlObj->GetAll($sql);

	if(count($rs_db)>0){
		for($s=0;$s<count($rs_db);$s++){
			 //Fiber Inquiry status draft &  tech not assign- show alluser in office administration dept
			/*if($rs_db[$s]['iStatus'] == "1" && $rs_db[$s]['iUserId'] == ""){
				if(in_array("2", $user_dept)){
					$notification_arr[] = array('type' => 'FiberInquiry' , 'iFiberInquiryId' => $rs_db[$s]['iFiberInquiryId'] , 'title' => "Fiber Inquiry #".$rs_db[$s]['iFiberInquiryId']." - Assign Technician");
				}
			}

			//SR staus draft and tech assign to user- show  the user who assigned sr
			if($rs_db[$s]['iStatus'] == "1" && $rs_db[$s]['iUserId'] == $userid){
				$notification_arr[] = array('type' => 'FiberInquiry' , 'iFiberInquiryId' => $rs_db[$s]['iFiberInquiryId'] , 'title' => "Fiber Inquiry #".$rs_db[$s]['iFiberInquiryId']." - Assigned");
			}*/

			//SR staus is review - show all user whose dept is field ops management
			if($rs_db[$s]['iStatus'] == "3" && in_array("5", $user_dept)){
				$notification_arr[] = array('type' => 'FiberInquiry' , 'iFiberInquiryId' => $rs_db[$s]['iFiberInquiryId'] , 'title' => "Fiber Inquiry #".$rs_db[$s]['iFiberInquiryId']." - for Review");
			}

		}
	}

	$rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => array('notification_arr' => $notification_arr));
}else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400, "Message" => $message);
}
?>