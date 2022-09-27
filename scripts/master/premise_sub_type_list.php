<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise Sub Type", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise Sub Type", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise Sub Type", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise Sub Type", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise Sub Type", 'Edit', 'N');
# ----------- Access Rule Condition -----------


include_once($controller_path . "premise_sub_type.inc.php");
include_once($controller_path . "premise_type.inc.php");


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
$Site_TypeObj = new SiteType();
$SiteSubTypeObj = new SiteSubType();
$iSSTypeId = $_POST['iSSTypeId'];

/*Get Premise type */
$st_where_arr[] = " site_type_mas.\"iStatus\"='1' ";
$join_fieds_arr = array();
$join_arr = array();
$Site_TypeObj->join_field = $join_fieds_arr;
$Site_TypeObj->join = $join_arr;
$Site_TypeObj->where = $st_where_arr;
$Site_TypeObj->param['order_by'] = " site_type_mas.\"vTypeName\" ";
$Site_TypeObj->setClause();
$Site_TypeObj->debug_query = false;
$rs_stlist = $Site_TypeObj->recordset_list();


if($mode == "List"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $where_arr = array();
    if ($query){
        $where_arr[] = $qtype." LIKE '".addslashes($query)."%'";
    }
    
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($Keyword != ""){
        if($vOptions == "iStatus"){
            if(strtolower($Keyword) == "active"){
                $where_arr[] = "site_sub_type_mas.\"iStatus\" = '1'";
            }
            else if(strtolower($Keyword) == "inactive"){
                $where_arr[] = "site_sub_type_mas.\"iStatus\" = '0'";
            }
        }else if($vOptions == "vTypeName"){

            $where_arr[] = 'site_type_mas."vTypeName" ILIKE \''.$Keyword.'%\'';
        }
        else{
            $where_arr[] = 'site_sub_type_mas."'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }
        
    

    if($_REQUEST['iStatus'] != ""){
        if($_REQUEST['iStatus'] != "-1")
            $where_arr[] = "site_sub_type_mas.\"iStatus\"='".$_REQUEST['iStatus']."'";
    }
     switch ($display_order) {
        case "0":
            $sortname = "site_sub_type_mas.\"iSSTypeId\"";
            break;
        case "1":
            $sortname = 'site_sub_type_mas."vSubTypeName"';
            break;
        case "2":
            $sortname = "site_type_mas.\"vTypeName\"";
            break;
        case "3":
            $sortname = "site_sub_type_mas.\"iStatus\"";
            break;
        default:
            $sortname = 'site_sub_type_mas."vSubTypeName"';
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";
    //$limit = "LIMIT 1 OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = "site_type_mas.\"vTypeName\"";
    $join_arr  = array();
    $join_arr[] = "LEFT JOIN site_type_mas ON site_sub_type_mas.\"iSTypeId\" = site_type_mas.\"iSTypeId\"";
    $SiteSubTypeObj->join_field = $join_fieds_arr;
    $SiteSubTypeObj->join = $join_arr;
    $SiteSubTypeObj->where = $where_arr;
    $SiteSubTypeObj->param['order_by'] = $sortname . " " . $dir;
    $SiteSubTypeObj->param['limit'] = $limit;
    $SiteSubTypeObj->setClause();
    $rs_type = $SiteSubTypeObj->recordset_list();
    //echo "<pre>";print_r($rs_type);exit();
    // Paging Total Records
    $total = $SiteSubTypeObj->recordset_total();

    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $ni = count($rs_type);
    //print_r($rs_type);
    //echo $ni;exit();
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_type[$i]['iSSTypeId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_type[$i]['iSSTypeId'].');"><i class="fa fa-trash"></i></a>';
            }
            

            $entry[] = array(
                            //"checkbox" => '<input type="checkbox" class="list" value="'.$rs_type[$i]['iSSTypeId'].'"/><input type="hidden" id="sstype_id_'.$rs_type[$i]['iSSTypeId'].'" value="'.$rs_type[$i]['iSSTypeId'].'">',
                            "checkbox" => $rs_type[$i]['iSSTypeId'].'<input type="hidden" id="sstype_id_'.$rs_type[$i]['iSSTypeId'].'" value="'.$rs_type[$i]['iSSTypeId'].'">',
                            "vSubTypeName" =>gen_strip_slash($rs_type[$i]['vSubTypeName']).'<input type="hidden" id="sstype_name_'.$rs_type[$i]['iSSTypeId'].'" value="'.$rs_type[$i]['vSubTypeName'].'">',
                            "vTypeName" => gen_strip_slash($rs_type[$i]['vTypeName']).'<input type="hidden" id="stype_id_'.$rs_type[$i]['iSSTypeId'].'" value="'.$rs_type[$i]['iSTypeId'].'">',
                            "iStatus" => '<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_type[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_type[$i]['iStatus'])].'">&nbsp;</span><input type="hidden" id="stype_status_'.$rs_type[$i]['iSSTypeId'].'" value="'.gen_status($rs_type[$i]['iStatus']).'">',
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
        $iSSTypeId = $_POST['iSSTypeId'];
    
    $rs_tot = $SiteSubTypeObj->delete_records($iSSTypeId);
    
    if($rs_tot){
        $result['tot'] = $rs_tot;
        $result['msg'] = MSG_DELETE;
        $result['error']= 0 ;
    }else{
         $result['tot'] = $rs_tot;
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
    $update_array = array("iSSTypeId"=>$_POST['iSSTypeId'], 
        "iSTypeId"      =>$_POST['iSTypeId'],
        "vSubTypeName"  =>$_POST['vSubTypeName'],
        "iStatus"=>(isset($_POST['iStatus']))?$_POST['iStatus']:"0"

    );
    
    $SiteSubTypeObj->update_arr = $update_array;
    $rs_db = $SiteSubTypeObj->update_records();
    
    if(isset($rs_db)){
        $result['msg'] = MSG_UPDATE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;

    }
   //$jsonData = array('total'=>$rs_db);
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
        "iSTypeId"      => $_POST['iSTypeId'],
        "vSubTypeName"  => $_POST['vSubTypeName'],
        "iStatus"       => isset($_POST['iStatus'])?$_POST['iStatus']:"0"
    );
    
    $SiteSubTypeObj->insert_arr = $insert_arr;
    $SiteSubTypeObj->setClause();
    $iSSTypeId = $SiteSubTypeObj->add_records();
    
   
     if(isset($iSSTypeId)){
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;

    }
   // $jsonData = array('iSSTypeId'=>$iSSTypeId);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    //echo json_encode($jsonData);
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode == "Active" || $mode == "Inactive"){
    $iSSTypeId = $_POST['iSSTypeId'];
    
    $SiteSubTypeObj->ids = $iSSTypeId;
    $SiteSubTypeObj->action = $mode;
    $SiteSubTypeObj->setClause();
    $rs_tot = $SiteSubTypeObj->action_records();

    $jsonData = array('total'=>$rs_tot);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------   
}
else if($mode== "Excel"){
    $where_arr = array();
    if ($query){
        $where_arr[] = $qtype." LIKE '".addslashes($query)."%'";
    }
    
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($Keyword != ""){
        if($vOptions == "iStatus"){
            if(strtolower($Keyword) == "active"){
                $where_arr[] = "site_sub_type_mas.\"iStatus\" = '1'";
            }
            else if(strtolower($Keyword) == "inactive"){
                $where_arr[] = "site_sub_type_mas.\"iStatus\" = '0'";
            }
        }else if($vOptions == "vTypeName"){

            $where_arr[] = 'site_type_mas."vTypeName" ILIKE \''.$Keyword.'%\'';
        }
        else{
            $where_arr[] = 'site_sub_type_mas."'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }
    
    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = "site_type_mas.\"vTypeName\"";
    $join_arr[] = "LEFT JOIN site_type_mas ON site_sub_type_mas.\"iSTypeId\" = site_type_mas.\"iSTypeId\"";
    $SiteSubTypeObj->join_field = $join_fieds_arr;
    $SiteSubTypeObj->join = $join_arr;
    $SiteSubTypeObj->where = $where_arr;
    $SiteSubTypeObj->param['order_by'] = 'site_sub_type_mas."iSSTypeId"';
    $SiteSubTypeObj->param['limit'] = "";
    $SiteSubTypeObj->setClause();
    $SiteSubTypeObj->debug_query = false;
    $rs_export = $SiteSubTypeObj->recordset_list();
    $cnt_export = count($rs_export);

    
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
       // // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();
        $file_name = "premise_sub_type_".time().".xlsx";

        if($cnt_export >0) {

            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'Sub Type')
                     ->setCellValue('C1', 'Premise Type')
                     ->setCellValue('D1', 'Status');
        
            for($e=0; $e<$cnt_export; $e++) {
                $status =gen_status($rs_export[$e]['iStatus']);
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iSSTypeId'])
                ->setCellValue('B'.($e+2), $rs_export[$e]['vSubTypeName'])
                ->setCellValue('C'.($e+2), $rs_export[$e]['vTypeName'])
                ->setCellValue('D'.($e+2), $status);
             }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Premise Sub Type');

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

$module_name = "Premise Sub Type List";
$module_title = "Premise Sub Type";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("rs_sitetype", $rs_stlist);
$smarty->assign("access_group_var_add",$access_group_var_add);
?>