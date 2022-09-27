<?php

include_once($site_path . "scripts/session_valid.php"); 

# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise Attribute", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise Attribute", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise Attribute", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise Attribute", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise Attribute", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "premise_attribute.inc.php");

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
$SiteAttObj = new SiteAttribute();
$iSAttributeId = $_POST['iSAttributeId'];

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
    else{
            $where_arr[] = '"'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }

    //echo "<pre>";print_r($where_arr);exit();
    switch ($display_order) {
        case "0":
            $sortname = "site_attribute_mas.\"iSAttributeId\"";
            break;
        case "1":
            $sortname = "site_attribute_mas.\"vAttribute\"";
            break;
        case "2":
            $sortname = "site_attribute_mas.\"iStatus\"";
            break;
        default:
            $sortname = 'site_attribute_mas."vAttribute"';
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $SiteAttObj->join_field = $join_fieds_arr;
    $SiteAttObj->join = $join_arr;
    $SiteAttObj->where = $where_arr;
    $SiteAttObj->param['order_by'] = $sortname . " " . $dir;
    $SiteAttObj->param['limit'] = $limit;
    $SiteAttObj->setClause();
    $SiteAttObj->debug_query = false;
    $rs_type = $SiteAttObj->recordset_list();
    $rs_tlist = $SiteAttObj->recordset_list();
   
    // Paging Total Records
    $total = $SiteAttObj->recordset_total();
    // Paging Total Records


    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $delete = '';
            $actions = '';
            if($access_group_var_edit == '1'){
                $actions .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_type[$i]['iSAttributeId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $actions .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_type[$i]['iSAttributeId'].');"><i class="fa fa-trash"></i></a>';
            }

            $entry[] = array(
                   // "checkbox" => '<input type="checkbox" class="list" value="'.$rs_type[$i]['iSAttributeId'].'"/><input type="hidden" id="sattr_id_'.$rs_type[$i]['iSAttributeId'].'" value="'.$rs_type[$i]['iSAttributeId'].'">',
                    "checkbox" => $rs_type[$i]['iSAttributeId'].'<input type="hidden" id="sattr_id_'.$rs_type[$i]['iSAttributeId'].'" value="'.$rs_type[$i]['iSAttributeId'].'">',
                    "vAttribute" => gen_strip_slash($rs_type[$i]['vAttribute']).'<input type="hidden" id="sattr_name_'.$rs_type[$i]['iSAttributeId'].'" value="'.$rs_type[$i]['vAttribute'].'">',
                    "iStatus" => '<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_type[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_type[$i]['iStatus'])].'">&nbsp;</span><input type="hidden" id="sattr_status_'.$rs_type[$i]['iSAttributeId'].'" value="'.gen_status($rs_type[$i]['iStatus']).'">',
                    "actions" =>($actions == "")?"---":$actions
           
                );
            
        }
    }
        $jsonData['aaData'] = $entry;
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}
else if($mode == "Delete"){
    $result = array();
    $iSAttributeId = $_POST['iSAttributeId'];
    
    $rs_tot = $SiteAttObj->delete_records($iSAttributeId);
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
    $update_array = array("iSAttributeId"=>$_POST['iSAttributeId'], 
    "vAttribute"=>$_POST['vAttribute'],
    "iStatus"=>(isset($_POST['iStatus']))?$_POST['iStatus']:"0"
    );
    $SiteAttObj->update_arr = $update_array;
    $rs_db = $SiteAttObj->update_records();
    if(isset($rs_db)){
        $result['msg'] = MSG_UPDATE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode == "Add"){
    $result = array();
    $insert_arr = array(
        "vAttribute"     => $_POST['vAttribute'],
         "iStatus"       => isset($_POST['iStatus'])?$_POST['iStatus']:"0"
    );
    
    //gen_writeDataInTmpFile($contact_arr);

    $SiteAttObj->insert_arr = $insert_arr;
    $SiteAttObj->setClause();
    $iSAttributeId = $SiteAttObj->add_records();
     if(isset($iSAttributeId)){
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
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
    $SiteAttObj->join_field = $join_fieds_arr;
    $SiteAttObj->join = $join_arr;
    $SiteAttObj->where = $where_arr;
    $SiteAttObj->param['order_by'] = "site_attribute_mas.\"iSAttributeId\"";
    $SiteAttObj->param['limit'] = "";
    $SiteAttObj->setClause();
    $SiteAttObj->debug_query = false;
    $rs_export = $SiteAttObj->recordset_list();
    $cnt_export = count($rs_export);

    
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
       // // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();
        $file_name = "premise_attribute_".time().".xlsx";

        if($cnt_export >0) {

            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'Premise Attribute')
                     ->setCellValue('C1', 'Status');
        
            for($e=0; $e<$cnt_export; $e++) {
                $status =gen_status($rs_export[$e]['iStatus']);
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iSAttributeId'])
                ->setCellValue('B'.($e+2), $rs_export[$e]['vAttribute'])
                ->setCellValue('C'.($e+2), $status);
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
            $objPHPExcel->getActiveSheet()->setTitle('Premise Attribute');

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
$module_name = "Premise Attribute List";
$module_title = "Premise Attribute";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("access_group_var_add", $access_group_var_add);

?>