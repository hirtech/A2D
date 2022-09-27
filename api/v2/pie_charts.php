<?php
include_once($controller_path . "pie_chart.inc.php");

$PieChartObj = new PieChart();

if($request_type == "pie_chart_default_Yaxes"){

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    $PieChartObj->join_field = $join_fieds_arr;
    $PieChartObj->join = $join_arr;
    $PieChartObj->where = $where_arr;
    $PieChartObj->param['order_by'] = '"vDisplayY"';
    $PieChartObj->setClause();
    $rs_default_yaxes = $PieChartObj->recordset_list();
    //echo "<pre>";print_r($rs_default_yaxes);exit;
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
}else if($request_type == "get_pie_chart_default_Xaxes"){

    $vDisplayY = $RES_PARA['vDisplayY'];

	$where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $PieChartObj->join_field = $join_fieds_arr;
    $where_arr[] = "\"vDisplayY\" = '" . $vDisplayY . "'";
    $PieChartObj->join = $join_arr;
    $PieChartObj->where = $where_arr;
    $PieChartObj->param['order_by'] = '"vDisplayX"';
    $PieChartObj->setClause();
    $rs_Xaxes = $PieChartObj->recordset_list();
    
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
}else if($request_type == "get_pie_chart_details_from_axes") {
    $vDisplayY = $RES_PARA['vDisplayY'];
    $vDisplayX = $RES_PARA['vDisplayX'];

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    if($vDisplayY != '') {
        $where_arr[] = "\"vDisplayY\" = '" . $vDisplayY . "'";
    }

    if($vDisplayX != '') {
        $where_arr[] = "\"vDisplayX\" = '" . $vDisplayX . "'";
    }

    $PieChartObj->join_field = $join_fieds_arr;
    $PieChartObj->join = $join_arr;
    $PieChartObj->where = $where_arr;
    $PieChartObj->param['order_by'] = '"vDisplayY"';
    $PieChartObj->setClause();
    $rs = $PieChartObj->recordset_list();
    
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $rs);    
}else if($request_type == "create_pie_charts") {
    $vDisplayY = $RES_PARA['vDisplayY'];
    $vDisplayX = $RES_PARA['vDisplayX'];
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

    $PieChartObj->join_field = $join_fieds_arr;
    $PieChartObj->join = $join_arr;
    $PieChartObj->where = $where_arr;
    $PieChartObj->param['order_by'] = '"vDisplayX"';
    $PieChartObj->setClause();
    $rs = $PieChartObj->recordset_list();
    $chart_arr = array();
    if(!empty($rs)) {
        $iPCTId = $rs[0]['iPCTId'];
        $vChartType = $rs[0]['vChartType'];
        $vDisplayY = $rs[0]['vDisplayY'];
        $vDisplayX = $rs[0]['vDisplayX'];
        $vFieldY = $rs[0]['vFieldY'];
        $vFieldX = $rs[0]['vFieldX'];
        $vTableA = $rs[0]['vTableA'];
        $vTableB = $rs[0]['vTableB'];
        $vJoinFieldAB = $rs[0]['vJoinFieldAB'];
        $vTableC = $rs[0]['vTableC'];
        $vJoinFieldBC = $rs[0]['vJoinFieldBC'];
		$vTableD = $rs[0]['vTableD'];
        $vJoinFieldCD = $rs[0]['vJoinFieldCD'];
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
        }else if($vTableA != '' && $vTableB != '' && $vTableC != '' && $vTableD == ''){
            $sql = 'SELECT '.$vFieldY.' as "vFieldY",'.$vFieldX.' as "vFieldX" FROM '.$vTableA.' a join '.$vTableB.' b on a.'.$vJoinFieldAB.'= b.'.$vJoinFieldAB.' join '.$vTableC.' c on b.'.$vJoinFieldBC.'= c.'.$vJoinFieldBC.$where_clause.' GROUP BY '.$vFieldX;
            $rs = $sqlObj->GetAll($sql);
        }else if($vTableA != '' && $vTableB != '' && $vTableC != '' && $vTableD != ''){
            $sql = 'SELECT '.$vFieldY.' as "vFieldY",'.$vFieldX.' as "vFieldX" FROM '.$vTableA.' a join '.$vTableB.' b on a.'.$vJoinFieldAB.'= b.'.$vJoinFieldAB.' join '.$vTableC.' c on b.'.$vJoinFieldBC.'= c.'.$vJoinFieldBC.' join '.$vTableD.' d on c.'.$vJoinFieldCD.'= d.'.$vJoinFieldCD.$where_clause.' GROUP BY '.$vFieldX;
            $rs = $sqlObj->GetAll($sql);
        } 
        
        $ni = count($rs);
        if($ni > 0) {
            for($i = 0; $i<$ni; $i++) {
                $chart_arr[$i]['vFieldX'] = $rs[$i]['vFieldX'];
                $chart_arr[$i]['vFieldY'] = $rs[$i]['vFieldY'];
            }
        }        
    }
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $chart_arr);
}else {
    $r = HTTPStatus(400);
    $code = 1001;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 400, "Message" => $message);
}
?>