<?php
include_once($controller_path . "cluster_chart.inc.php");

$ClusterChartObj = new ClusterChart();


if($request_type == "cluster_chart_default_Yaxes"){

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    $ClusterChartObj->join_field = $join_fieds_arr;
    $ClusterChartObj->join = $join_arr;
    $ClusterChartObj->where = $where_arr;
    $ClusterChartObj->param['order_by'] = '"vDisplayY"';
    $ClusterChartObj->setClause();
    $rs_default_yaxes = $ClusterChartObj->recordset_list();

    $ni = count($rs_default_yaxes);
    $vDisplayYArr = array();
    if($ni >0) {
        for($i=0; $i<$ni; $i++){
            if(!@in_array($rs_default_yaxes[$i]['vDisplayY'], $vDisplayYArr))
                $vDisplayYArr[] = $rs_default_yaxes[$i]['vDisplayY'];
        }
    }

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $vDisplayYArr);
}else if($request_type == "get_cluster_chart_default_Xaxes"){

    $vDisplayY = $RES_PARA['vDisplayY'];

	$where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ClusterChartObj->join_field = $join_fieds_arr;
    $where_arr[] = "\"vDisplayY\" = '" . $vDisplayY . "'";
    $ClusterChartObj->join = $join_arr;
    $ClusterChartObj->where = $where_arr;
    $ClusterChartObj->param['order_by'] = '"vDisplayX"';
    $ClusterChartObj->setClause();
    $rs_Xaxes = $ClusterChartObj->recordset_list();

    $ni = count($rs_Xaxes);
    $vDisplayXArr = array();
    if($ni >0) {
        for($i=0; $i<$ni; $i++){
            if(!@in_array($rs_Xaxes[$i]['vDisplayX'], $vDisplayXArr))
                $vDisplayXArr[] = $rs_Xaxes[$i]['vDisplayX'];
        }
    }

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $vDisplayXArr);
}else if($request_type == "get_cluster_chart_default_X1axes"){
    $vDisplayY = $RES_PARA['vDisplayY'];
    $vDisplayX = $RES_PARA['vDisplayX'];

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ClusterChartObj->join_field = $join_fieds_arr;
    $where_arr[] = "\"vDisplayY\" = '" . $vDisplayY . "'";
    $where_arr[] = "\"vDisplayX\" = '" . $vDisplayX . "'";
    $ClusterChartObj->join = $join_arr;
    $ClusterChartObj->where = $where_arr;
    $ClusterChartObj->param['order_by'] = '"vDisplayX1"';
    $ClusterChartObj->setClause();
    $rs_Xaxes = $ClusterChartObj->recordset_list();

    $ni = count($rs_Xaxes);
    $vDisplayXArr = array();
    if($ni >0) {
        for($i=0; $i<$ni; $i++){
            if(!@in_array($rs_Xaxes[$i]['vDisplayX1'], $vDisplayXArr))
                $vDisplayXArr[] = $rs_Xaxes[$i]['vDisplayX1'];
        }
    }
    //echo "<pre>";print_r($vDisplayXArr);exit();

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $vDisplayXArr);
}else if($request_type == "create_cluster_charts") {

    $vDisplayY = $RES_PARA['vDisplayY'];
    $vDisplayX = $RES_PARA['vDisplayX'];
    $vDisplayX1 = $RES_PARA['vDisplayX1'];
    $dFromDate = $RES_PARA['dFromDate'];
    $dToDate   = $RES_PARA['dToDate'];

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    if($vDisplayY != '') {
        $where_arr[] = "\"vDisplayY\" = '" . $vDisplayY . "'";
    }

    if($vDisplayX != '') {
        $where_arr[] = "\"vDisplayX\" = '" . $vDisplayX . "'";
    }

    if($vDisplayX1 != '') {
        $where_arr[] = "\"vDisplayX1\" = '" . $vDisplayX1 . "'";
    }

    $ClusterChartObj->join_field = $join_fieds_arr;
    $ClusterChartObj->join = $join_arr;
    $ClusterChartObj->where = $where_arr;
    $ClusterChartObj->param['order_by'] = '"vDisplayX"';
    $ClusterChartObj->setClause();
    $rs = $ClusterChartObj->recordset_list();

    $chart_arr = array();
    $chart_data_arr = array();
    $chart_series_arr = array();

   // echo "<pre>";print_r($rs);exit;
    if(!empty($rs)) {
        $iCCTId = $rs[0]['iCCTId'];
        $vDisplayY = $rs[0]['vDisplayY'];
        $vDisplayX = $rs[0]['vDisplayX'];
        $vDisplayX1 = $rs[0]['vDisplayX1'];
        $vFieldY = $rs[0]['vFieldY'];
        $vFieldX = $rs[0]['vFieldX'];
        $vFieldX1 = $rs[0]['vFieldX1'];
        $vTableA = $rs[0]['vTableA'];
        $vTableB = $rs[0]['vTableB'];
        $vJoinFieldAB = $rs[0]['vJoinFieldAB'];
        $vTableC = $rs[0]['vTableC'];
        $vJoinFieldBC = $rs[0]['vJoinFieldBC'];
        $bFromTo = $rs[0]['bFromTo'];
        $vDateField = $rs[0]['vDateField'];

        $rs = array();
        $where_arr = array();
        if($dFromDate != ''){
            $dFromDate1 = $dFromDate." 00:00:00";
            $where_arr[] = "(".$vDateField." >= '".$dFromDate."' OR ".$vDateField." >= '".$dFromDate1."')";
        }

        if($dToDate != ''){
            $dToDate1 = $dToDate." 23:59:59";
            $where_arr[] = "(".$vDateField." <= '".$dToDate."' OR ".$vDateField." <= '".$dToDate1."')";
        }

        $where_clause = '';
        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        
        if($vTableA != '' && $vTableB != '' && $vTableC == ''){
            $sql = 'SELECT '.$vFieldY.' as "vFieldY",'.$vFieldX.' as "vFieldX" FROM '.$vTableA.' a join '.$vTableB.' b on a.'.$vJoinFieldAB.'= b.'.$vJoinFieldAB.$where_clause.' GROUP BY '.$vFieldX;
          
            $rs = $sqlObj->GetAll($sql);
        }else if($vTableA != '' && $vTableB != '' && $vTableC != ''){

            $sql = 'SELECT '.$vFieldY.' as "vFieldY",'.$vFieldX.' as "vFieldX",'.$vFieldX1.' as "vFieldX1"  FROM '.$vTableA.' a join '.$vTableB.' b on a.'.$vJoinFieldAB.'= b.'.$vJoinFieldAB.' join '.$vTableC.' c on a.'.$vJoinFieldBC.'= c.'.$vJoinFieldBC.$where_clause.' GROUP BY '.$vFieldX.' ,'.$vFieldX1;
            //echo $sql;exit();
            $rs = $sqlObj->GetAll($sql);
        } 
        
        $ni = count($rs);
        
        $c =0;
        $temparrary = array();
        if($ni > 0) {
            for($i = 0; $i<$ni; $i++) {
                if(!in_array($rs[$i]['vFieldX1'],$chart_series_arr)){
                    $chart_series_arr[$c]= $rs[$i]['vFieldX1'];
                    $c++;
                }
            }
            for($i = 0; $i<$ni; $i++) {
                if(in_array($rs[$i]['vFieldX1'],$chart_series_arr)){
                    $temparrary[$rs[$i]['vFieldX']][array_search($rs[$i]['vFieldX1'],$chart_series_arr)] = $rs[$i]['vFieldY'];
                }
            }

            if(count($temparrary) > 0){
                foreach ($temparrary as $key => $value) {
                    $chart_data_tmp = array();
                    if(count($value) > 0){
                         $chart_data_tmp['category']  = $key;
                        foreach ($value as $k1 => $v1) {
                            $chart_data_tmp[$k1] = $v1;
                        }
                        $chart_data_arr[] =$chart_data_tmp;
                    }
                }
            }
            $chart_arr['data'] = $chart_data_arr;
            $chart_arr['series'] = $chart_series_arr;

        }
    }
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $chart_arr);
}else if($request_type == "get_cluster_chart_details_from_axes") {
    
    $vDisplayY = $RES_PARA['vDisplayY'];
    $vDisplayX = $RES_PARA['vDisplayX'];
    $vDisplayX1 = $RES_PARA['vDisplayX1'];

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    if($vDisplayY != '') {
        $where_arr[] = "\"vDisplayY\" = '" . $vDisplayY . "'";
    }

    if($vDisplayX != '') {
        $where_arr[] = "\"vDisplayX\" = '" . $vDisplayX . "'";
    }

    if($vDisplayX1 != '') {
        $where_arr[] = "\"vDisplayX1\" = '" . $vDisplayX1. "'";
    }

    $ClusterChartObj->join_field = $join_fieds_arr;
    $ClusterChartObj->join = $join_arr;
    $ClusterChartObj->where = $where_arr;
    $ClusterChartObj->param['order_by'] = '"vDisplayX1"';
    $ClusterChartObj->setClause();
    $rs = $ClusterChartObj->recordset_list();

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $rs);
    
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400, "Message" => $message);
}
?>