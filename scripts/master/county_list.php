<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("County", 'List');
$access_group_var_delete = per_hasModuleAccess("County", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("County", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("County", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("County", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "county.inc.php");

//echo "<pre>";print_r($_REQUEST);exit();
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
$County_Obj = new County();
$iCountyId = $_POST['iCountyId'];
//echo $mode;exit;
if($mode == "List"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $where_arr = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($Keyword != ""){
       $where_arr[] = 'county_mas."'.$vOptions."\" ILIKE '".$Keyword."%'";
    }
    //echo "<pre>";print_r($where_arr);exit();
    switch ($display_order) {
        case "0" : 
            $sortname = "county_mas.\"iCountyId\"";
            break;
        case "1":
            $sortname = "county_mas.\"vCounty\"";
            break;
        default:
            $sortname = 'county_mas."vCounty"';
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $County_Obj->join_field = $join_fieds_arr;
    $County_Obj->join = $join_arr;
    $County_Obj->where = $where_arr;
    $County_Obj->param['order_by'] = $sortname . " " . $dir;
    $County_Obj->param['limit'] = $limit;
    $County_Obj->setClause();
    $County_Obj->debug_query = false;
    $rs_type = $County_Obj->recordset_list();
    $smarty->assign("rs_type",$rs_type);
    // Paging Total Records
    $total = $County_Obj->recordset_total();
    // Paging Total Records

    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_type[$i]['iCountyId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_type[$i]['iCountyId'].');"><i class="fa fa-trash"></i></a>';
            }
            
            $entry[] = array(
                           // "checkbox" =>'<input type="checkbox" class="list" value="'.$rs_type[$i]['iCountyId'].'"/><input type="hidden" id="stype_id_'.$rs_type[$i]['iCountyId'].'" value="'.$rs_type[$i]['iCountyId'].'">',
                            
                           "checkbox" =>$rs_type[$i]['iCountyId'].'<input type="hidden" id="county_id_'.$rs_type[$i]['iCountyId'].'" value="'.$rs_type[$i]['iCountyId'].'">',
                           "vCounty" =>gen_strip_slash($rs_type[$i]['vCounty']).'<input type="hidden" id="county_name_'.$rs_type[$i]['iCountyId'].'" value="'.$rs_type[$i]['vCounty'].'">',
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
    $iCountyId = $_POST['iCountyId'];
    
    $rs_tot = $County_Obj->delete_records($iCountyId);
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
    $update_array = array("iCountyId"=>$_POST['iCountyId'], 
    "vCounty"=>$_POST['vCounty']
    );
  //echo "<pre>";print_r($update_array);exit();
    
    $County_Obj->update_arr = $update_array;
    $rs_db = $County_Obj->update_records();
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
        "vCounty"     => $_POST['vCounty']
    );
    //echo "<pre>";print_r($insert_arr);exit;
    //gen_writeDataInTmpFile($contact_arr);

    $County_Obj->insert_arr = $insert_arr;
    $County_Obj->setClause();
    //echo "<pre>";print_r($County_Obj);exit;
    $iCountyId = $County_Obj->add_records();
     if(isset($iCountyId)){
        $result['iCountyId'] = $iCountyId;
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
        
        $result['iCountyId'] = $iCountyId;
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
}else if($mode== "Excel"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $where_arr = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($Keyword != ""){
       $where_arr[] = 'county_mas."'.$vOptions."\" ILIKE '".$Keyword."%'";
    }
   
    
    $join_fieds_arr = array();
    $join_arr = array();
    $County_Obj->join_field = $join_fieds_arr;
    $County_Obj->join = $join_arr;
    $County_Obj->where = $where_arr;
    $County_Obj->param['order_by'] = 'county_mas."iCountyId" ASC';
    $County_Obj->param['limit'] = "";
    $County_Obj->setClause();
    $County_Obj->debug_query = false;
    $rs_export = $County_Obj->recordset_list();
    $cnt_export = count($rs_export);
        
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
       // // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();
        //$file_name = "county_".time().".xls";
        $file_name = "county_".time().".xlsx";

        if($cnt_export >0) {

            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'County');
        
            for($e=0; $e<$cnt_export; $e++) {
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iCountyId'])
                ->setCellValue('B'.($e+2), $rs_export[$e]['vCounty']);
             }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('County');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
        }

    /* $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');*/
         $result_arr  = array();
       //  $objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
$module_name = "County List";
$module_title = "County";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);

$smarty->assign("rs_county",$rs_county);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);
// $smarty->assign("iAGroupId", $_GET['iAGroupId']);
//$smarty->assign("rs_tlist", $rs_tlist);