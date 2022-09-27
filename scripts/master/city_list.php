<?php

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("City", 'List');
$access_group_var_delete = per_hasModuleAccess("City", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("City", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("City", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("City", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "county.inc.php");
include_once($controller_path . "city.inc.php");
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
$City_Obj = new City();
$County_Obj = new County();
$iCityId = $_POST['iCityId'];
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
        }else{
            $where_arr[] = '"'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }

    //echo "<pre>";print_r($where_arr);exit();
    switch ($display_order) {
        case "0" : 
            $sortname = "city_mas.\"iCityId\"";
            break;
        case "1":
            $sortname = "city_mas.\"vCity\"";
            break;
        default:
            $sortname = 'city_mas."vCity"';
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $City_Obj->join_field = $join_fieds_arr;
    $City_Obj->join = $join_arr;
    $City_Obj->where = $where_arr;
    $City_Obj->param['order_by'] = $sortname . " " . $dir;
    $City_Obj->param['limit'] = $limit;
    $City_Obj->setClause();
    $City_Obj->debug_query = false;
    $rs_type = $City_Obj->recordset_list();
    $smarty->assign("rs_type",$rs_type);
    // Paging Total Records
    $total = $City_Obj->recordset_total();
    // Paging Total Records


    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_type[$i]['iCityId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_type[$i]['iCityId'].');"><i class="fa fa-trash"></i></a>';
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
                            
                           "checkbox" =>$rs_type[$i]['iCityId'].'<input type="hidden" id="city_id_'.$rs_type[$i]['iCityId'].'" value="'.$rs_type[$i]['iCityId'].'">',
                           "vCity" =>gen_strip_slash($rs_type[$i]['vCity']).'<input type="hidden" id="city_name_'.$rs_type[$i]['iCityId'].'" value="'.$rs_type[$i]['vCity'].'">',
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
    $iCityId = $_POST['iCityId'];
    
    $rs_tot = $City_Obj->delete_records($iCityId);
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
    $update_array = array("iCityId"=>$_POST['iCityId'], 
    "vCity"=>$_POST['vCity'],
    );
  //echo "<pre>";print_r($update_array);exit();
    
    $City_Obj->update_arr = $update_array;
    $rs_db = $City_Obj->update_records();
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
        "vCity"     => $_POST['vCity'],
    );
    //echo "<pre>";print_r($insert_arr);exit;
    //gen_writeDataInTmpFile($contact_arr);

    $City_Obj->insert_arr = $insert_arr;
    $City_Obj->setClause();
    //echo "<pre>";print_r($City_Obj);exit;
    $iCityId = $City_Obj->add_records();
     if(isset($iCityId)){
        $result['iCityId'] = $iCityId;
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
        
        $result['iCityId'] = $iCityId;
         $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;

    }
   // $jsonData = array('iCountyId'=>$iCountyId);
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
        }else{
            $where_arr[] = '"'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }
    
    $join_fieds_arr = array();
    $join_arr = array();
    $City_Obj->join_field = $join_fieds_arr;
    $City_Obj->join = $join_arr;
    $City_Obj->where = $where_arr;
    $City_Obj->param['order_by'] = "city_mas.\"iCityId\"";
    $City_Obj->param['limit'] = "";
    $City_Obj->setClause();
    $City_Obj->debug_query = false;
    $rs_export = $City_Obj->recordset_list();
    $cnt_export = count($rs_export);

    
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
       // // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();
        $file_name = "city_".time().".xlsx";

        if($cnt_export >0) {

            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'City');
        
            for($e=0; $e<$cnt_export; $e++) {
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iCityId'])
                ->setCellValue('B'.($e+2), $rs_export[$e]['vCity']);
             }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('City');

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
    
$module_name = "City List";
$module_title = "City";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);
// $smarty->assign("iAGroupId", $_GET['iAGroupId']);
//$smarty->assign("rs_tlist", $rs_tlist);