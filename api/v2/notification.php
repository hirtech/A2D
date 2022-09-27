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


	$sql = "SELECT  *  FROM sr_details order by \"iSRId\" ";
	$rs_db = $sqlObj->GetAll($sql);

	if(count($rs_db)>0){
		for($s=0;$s<count($rs_db);$s++){
			 //SR status draft &  tech not assign- show alluser in office administration dept
			if($rs_db[$s]['iStatus'] == "1" && $rs_db[$s]['iUserId'] == ""){
				if(in_array("2", $user_dept)){
					$notification_arr[] = array('type' => 'SR' , 'iSRId' => $rs_db[$s]['iSRId'] , 'title' => "Service Request #".$rs_db[$s]['iSRId']." - Assign Technician");
				}
			}

			//SR staus draft and tech assign to user- show  the user who assigned sr
			if($rs_db[$s]['iStatus'] == "1" && $rs_db[$s]['iUserId'] == $userid){
				$notification_arr[] = array('type' => 'SR' , 'iSRId' => $rs_db[$s]['iSRId'] , 'title' => "Service Request #".$rs_db[$s]['iSRId']." - Assigned");
			}

			//SR staus is review - show all user whose dept is field ops management
			if($rs_db[$s]['iStatus'] == "3" && in_array("5", $user_dept)){
				$notification_arr[] = array('type' => 'SR' , 'iSRId' => $rs_db[$s]['iSRId'] , 'title' => "Service Request #".$rs_db[$s]['iSRId']." - for Review");
			}

		}
	}

	//treatment app rate is outside the limits -show user who dept is field management and who performed treatment
 	$sql = 'SELECT tt."iTreatmentId",tt."vArea",tt."vAreaTreated" ,tt."vAmountApplied",tt."iUId" as "treatmentUId",tt."iUserId",tp."iUId" as "treatmentprodUId",tp."vTragetAppRate" as "treatmentprodTragetAppRate",tp."vMinAppRate",tp."vMaxAppRate"  FROM task_treatment tt left join treatment_product tp on tt."iTPId"=tp."iTPId" order by tt."iTreatmentId"' ;
	$rs_treatment = $sqlObj->GetAll($sql);

	if(count($rs_treatment)){
			$unit_stdUnitfactor_arr = array();

			/* unit stdUnitfactor  array unit id wise*/
			$where_arr = array();
			$join_fieds_arr = array();
			$join_arr = array();
			$TProdObj->join_field = $join_fieds_arr;
			$TProdObj->join = $join_arr;
			$TProdObj->where = $where_arr;
			$TProdObj->param['order_by'] = "";
			$TProdObj->param['limit'] = "";
			$TProdObj->setClause();
			$TProdObj->debug_query = false;
			$rs_unit = $TProdObj->unit_data();
			$ui = count($rs_unit);

			for($u=0;$u<$ui;$u++){
				$unit_stdUnitfactor_arr[$rs_unit[$u]['iUId']] = $rs_unit[$u]['rStdUnitFactor'];
			}

		for($t=0;$t<count($rs_treatment);$t++){
			$rAmount = 0;
			$rAmount1 = 0;
			$rArea = 0; 
			$rAmountapplied = $rs_treatment[$t]['vAmountApplied']; 
		    $treatAreaTreated = trim($rs_treatment[$t]['vAreaTreated']);
			$treatprodAreaTreated = trim($rs_treatment[$t]['treatmentprodTragetAppRate']);
			$vMinAppRate = $rs_treatment[$t]['vMinAppRate'];
            $vMaxAppRate = $rs_treatment[$t]['vMaxAppRate'];
            $tprodStdUnitFactor = $unit_stdUnitfactor_arr[$rs_treatment[$t]['treatmentprodUId']];
            $treatmentStdUnitFactor = $unit_stdUnitfactor_arr[$rs_treatment[$t]['treatmentUId']];
            $treatArea =$rs_treatment[$t]['vArea'];

                /*if($rs_treatment[$t]['vAreaTreated'] == 'sqft'){
                	//convert into acre
                    $rArea = ($rs_treatment[$t]['vArea']/43560);
                }
                else {
                    $rArea= $rs_treatment[$t]['vArea'];
                }
                
                $rAmount = $rAmountapplied / $rArea;*/
                //Check  treatment uid & treatment product uid
            if($rs_treatment[$t]['treatmentUId'] != $rs_treatment[$t]['treatmentprodUId'] ) {
                $rAmount1 = ($rAmountapplied*$treatmentStdUnitFactor) /  $tprodStdUnitFactor;
            }else {
                $rAmount1 = $rAmountapplied;
            }
			//Check  treatment area & treatment product area
            if($treatAreaTreated != $treatprodAreaTreated){
            	if($treatAreaTreated == 'sqft'){
               		$rAreaTreated1 = ($treatArea/43560);
                }
                else {
                    $rAreaTreated1 = $treatArea*43560;
                }
            }
            else {
                $rAreaTreated1 = $treatArea;
            }
                
            $rAmount = $rAmount1 / $rAreaTreated1;

            if(in_array("5", $user_dept) || $rs_treatment[$t]['iUserId'] == $userid ){
            	if(isset($rAmount) && isset($vMinAppRate)  && isset($vMaxAppRate)){
					if(($rAmount < $vMinAppRate) || ($rAmount > $vMaxAppRate) ){
	                    $notification_arr[] = array('type' => 'Treatment' , 'iTreatmentId' => $rs_db[$s]['iTreatmentId'] , 'title' =>  "Treatment #".$rs_treatment[$t]['iTreatmentId']."-Abnormality Detected ".$rAmount);
					}
				}
			}
		}
	}
	


	//trap is placed,but not collention date entered - show the user who placed the trap
	$sql = "SELECT task_trap.\"iTTId\"  FROM task_trap where task_trap.\"dTrapCollected\" IS NULL and task_trap.\"iUserId\" = ". $userid." order by task_trap.\"iTTId\"" ;
	$rs_trap = $sqlObj->GetAll($sql);
		
	if(count($rs_trap)){
		for($t=0;$t<count($rs_trap);$t++){
			$notification_arr[] = array('type' => 'Trap' , 'iTTId' => $rs_db[$s]['iTTId'] , 'title' =>  "Trap #".$rs_trap[$t]['iTTId']." for Collection");
		}
	}

	//show  all user of department - lab technican an lab management
	if(in_array("3", $user_dept) || in_array("4", $user_dept) ){
		$today_date = date_getSystemDate();

		//trap Collected but  lab work not completed onmosqito count
		$sql = 'select task_trap."iTTId",task_trap."bLabWorkComplete",task_trap."dTrapCollected"  from task_trap  where task_trap."dTrapCollected" < \''.$today_date.'\' and task_trap."bLabWorkComplete" <> \'1\' group by task_trap."iTTId" ';

		$rs_pool = $sqlObj->GetAll($sql);
		
		if(count($rs_pool)){
			for($t=0;$t<count($rs_pool);$t++){
				$notification_arr[] = array('type' => 'Mosquito Count' , 'iTTId' => $rs_db[$s]['iTTId'] , 'title' =>  "Mosquito Count from Trap #".$rs_pool[$t]['iTTId']);
		    }
		}
	
		//Pool Collected but pool lab work not completed
		$sql = 'select count(task_mosquito_pool_result."iTMPId") as total_pool,task_mosquito_pool."iCountMosqperpool",task_mosquito_pool."iTMPId",task_mosquito_pool."bLabWorkComplete",task_mosquito_pool."iTTId"  from task_mosquito_pool_result right join task_mosquito_pool on task_mosquito_pool_result."iTMPId" = task_mosquito_pool."iTMPId" where  task_mosquito_pool."bLabWorkComplete" <> \'1\' group by task_mosquito_pool."iTMPId" ' ;
		$rs_pool = $sqlObj->GetAll($sql);

		if(count($rs_pool)){
			for($t=0;$t<count($rs_pool);$t++){
				if($rs_pool[$t]['total_pool'] > 0){
					$notification_arr[] = array('type' => 'Pool' , 'iTTId' => $rs_db[$s]['iTTId'] , 'iTMPId' => $rs_db[$s]['iTMPId'] , 'title' =>  "(Count".$rs_pool[$t]['iCountMosqperpool'].") Pools for Disease Testing");
			    }
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