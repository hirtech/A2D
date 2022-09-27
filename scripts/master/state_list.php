<?php

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("State", 'List');
$access_group_var_delete = per_hasModuleAccess("State", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("State", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("State", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("State", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "state.inc.php");
//county.inc.php <= use this

$page = $_REQUEST['page'];
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '7');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
$State_Obj = new State();
$iStateId = $_POST['iStateId'];
//echo $mode;exit;
if($mode == "List"){
 
    //echo "<pre>";print_r($_REQUEST);exit();
    $where_arr = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($Keyword != ""){
        if($vOptions == "iStatus"){
            if(strtolower($Keyword) == "active"){
                $where_arr[] = "\"iStatus\" = '1'";
            }
            else if(strtolower($Keyword) == "inactive"){
                $where_arr[] = "\"iStatus\" = '0'";
            }
        }
        else if($vOptions == "iDefault"){
            if(strtolower($Keyword) == "yes"){
                $where_arr[] = "state_mas.\"iDefault\" = '1'";
            }
            else if(strtolower($Keyword) == "no"){
                $where_arr[] = "state_mas.\"iDefault\" = '0'";
            }
        }else{
            $where_arr[] = '"'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }

    //echo "<pre>";print_r($where_arr);exit();
    switch ($display_order) {
        case "0" : 
            $sortname = "state_mas.\"iStateId\"";
            break;
        case "1":
            $sortname = "state_mas.\"vState\"";
            break;
        case "2":
            $sortname = "state_mas.\"vStateCode\"";
            break;
        default:
            $sortname = 'state_mas."vState"';
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $State_Obj->join_field = $join_fieds_arr;
    $State_Obj->join = $join_arr;
    $State_Obj->where = $where_arr;
    $State_Obj->param['order_by'] = $sortname . " " . $dir;
    $State_Obj->param['limit'] = $limit;
    $State_Obj->setClause();
    $State_Obj->debug_query = false;
    $rs_type = $State_Obj->recordset_list();
    
    // Paging Total Records
    $total = $State_Obj->recordset_total();
    // Paging Total Records


    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_type[$i]['iStateId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_type[$i]['iStateId'].');"><i class="fa fa-trash"></i></a>';
            }
            $default_val = 'No';
            $class = 'style=color:#F40D09;';
            if($rs_type[$i]['iDefault'] == 1)
            {
                $default_val = 'Yes';
                $class = 'style=color:#215404;font-weight:bold;';
            }
            $entry[] = array(
                           // "checkbox" =>'<input type="checkbox" class="list" value="'.$rs_type[$i]['iCountyId'].'"/><input type="hidden" id="stype_id_'.$rs_type[$i]['iCountyId'].'" value="'.$rs_type[$i]['iCountyId'].'">',
                            
                           "checkbox" =>$rs_type[$i]['iStateId'].'<input type="hidden" id="state_id_'.$rs_type[$i]['iStateId'].'" value="'.$rs_type[$i]['iStateId'].'">',
                           "vState" =>gen_strip_slash($rs_type[$i]['vState']).'<input type="hidden" id="state_name_'.$rs_type[$i]['iStateId'].'" value="'.$rs_type[$i]['vState'].'">',
                            "vStateCode" =>gen_strip_slash($rs_type[$i]['vStateCode']).'<input type="hidden" id="state_code_'.$rs_type[$i]['iStateId'].'" value="'.$rs_type[$i]['vStateCode'].'">',
                           "actions" => ($action == "")?"---":$action
                        );
            
            
        }
        
    }
    $jsonData['aaData'] = $entry;
    //echo "<pre>";print_r($jsonData);exit();
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}
else if($mode == "Delete"){
    $result = array();
    $iStateId = $_POST['iStateId'];
    
    $rs_tot = $State_Obj->delete_records($iStateId);
    if($rs_tot){
        $result['msg'] = MSG_DELETE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error']= 1 ;

    }
    //$jsonData = array('total'=>$rs_tot);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
   // echo json_encode($jsonData);
   echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode == "Update"){
    $result =array();
    //echo "<pre>";print_r($_REQUEST);exit();
    $update_array = array("iStateId"=>$_POST['iStateId'], 
    "vState"=>$_POST['vState'],
    "vStateCode"=>$_POST['vStateCode'],
    );
  //echo "<pre>";print_r($update_array);exit();
    
    $State_Obj->update_arr = $update_array;
    $rs_db = $State_Obj->update_records();
    if(isset($rs_db)){
        $result['msg'] = MSG_UPDATE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;

    }
   // $jsonData = array('total'=>$rs_db);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    //echo json_encode($jsonData);
    //echo "<pre>";print_r($result);exit();
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode == "Add"){
    $result = array();
    $insert_arr = array(
        "vState"     => $_POST['vState'],
        "vStateCode"     => $_POST['vStateCode'],
    );
    //echo "<pre>";print_r($insert_arr);exit;
    //gen_writeDataInTmpFile($contact_arr);

    $State_Obj->insert_arr = $insert_arr;
    $State_Obj->setClause();
    //echo "<pre>";print_r($State_Obj);exit;
    $iStateId = $State_Obj->add_records();
     if(isset($iStateId)){
        $result['iStateId'] = $iStateId;
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
        
        $result['iStateId'] = $iStateId;
         $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    //echo json_encode($jsonData);
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode== "Excel"){
    $where_arr = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($Keyword != ""){
        if($vOptions == "iStatus"){
            if(strtolower($Keyword) == "active"){
                $where_arr[] = "\"iStatus\" = '1'";
            }
            else if(strtolower($Keyword) == "inactive"){
                $where_arr[] = "\"iStatus\" = '0'";
            }
        }
        else{
            $where_arr[] = '"'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }
    
    $join_fieds_arr = array();
    $join_arr = array();
    $State_Obj->join_field = $join_fieds_arr;
    $State_Obj->join = $join_arr;
    $State_Obj->where = $where_arr;
    $State_Obj->param['order_by'] = "state_mas.\"iStateId\"";
    $State_Obj->param['limit'] = "";
    $State_Obj->setClause();
    $State_Obj->debug_query = false;
    $rs_export = $State_Obj->recordset_list();
    $cnt_export = count($rs_export);

    
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
       // // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();
        $file_name = "state_".time().".xlsx";

        if($cnt_export >0) {

            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'State')
                     ->setCellValue('C1', 'State Code');
        
            for($e=0; $e<$cnt_export; $e++) {
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iStateId'])
                ->setCellValue('B'.($e+2), $rs_export[$e]['vState'])
                ->setCellValue('C'.($e+2), $rs_export[$e]['vStateCode']);
             }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('State');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
         $result_arr  = array();
       //  $objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
       //  //$objWriter->save('php://output');
        
         //save in file 
        $objWriter->save($temp_gallery.$file_name);
        $result_arr['isError'] = 0;
        $result_arr['file_path'] = base64_encode($temp_gallery.$file_name);
        $result_arr['file_url'] = base64_encode($temp_gallery_url.$file_name);
    # -------------------------------------

       echo json_encode($result_arr);
       exit;
}
//echo "<pre>";print_r($rs_type);exit;
$module_name = "State List";
$module_title = "State";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("rs_type",$rs_type);
//$smarty->assign("rs_county",$rs_county);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);
// $smarty->assign("iAGroupId", $_GET['iAGroupId']);
//$smarty->assign("rs_tlist", $rs_tlist);