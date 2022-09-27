<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise Type", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise Type", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise Type", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise Type", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise Type", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "premise_type.inc.php");
include_once($function_path."image.inc.php");

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
$iSTypeId = $_POST['iSTypeId'];

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
        case "0" : 
            $sortname = "site_type_mas.\"iSTypeId\"";
            break;
        case "1":
            $sortname = "site_type_mas.\"vTypeName\"";
            break;
        case "3":
            $sortname = "site_type_mas.\"iStatus\"";
            break;
        default:
            $sortname = 'site_type_mas."vTypeName"';
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $Site_TypeObj->join_field = $join_fieds_arr;
    $Site_TypeObj->join = $join_arr;
    $Site_TypeObj->where = $where_arr;
    $Site_TypeObj->param['order_by'] = $sortname . " " . $dir;
    $Site_TypeObj->param['limit'] = $limit;
    $Site_TypeObj->setClause();
    $Site_TypeObj->debug_query = false;
    $rs_type = $Site_TypeObj->recordset_list();
    $rs_tlist = $Site_TypeObj->recordset_list();
   
    //echo "<pre>";print_r($rs_type);exit();
    // Paging Total Records
    $total = $Site_TypeObj->recordset_total();
    // Paging Total Records


    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_type[$i]['iSTypeId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_type[$i]['iSTypeId'].');"><i class="fa fa-trash"></i></a>';
            }

           

            $site_icon = "";
             $site_icon_image = "";
            if($rs_type[$i]['icon'] !=""  && file_exists($premise_type_icon_path."".$rs_type[$i]['icon'])){
                $site_icon_image = $premise_type_icon_url."".$rs_type[$i]['icon'];
                $site_icon = '<img src="'.$site_icon_image.'" alt="" class="img-fluid rounded-circle">';
            }
           

            $entry[] = array(
                           // "checkbox" =>'<input type="checkbox" class="list" value="'.$rs_type[$i]['iSTypeId'].'"/><input type="hidden" id="stype_id_'.$rs_type[$i]['iSTypeId'].'" value="'.$rs_type[$i]['iSTypeId'].'">',
                           "checkbox" =>$rs_type[$i]['iSTypeId'].'<input type="hidden" id="stype_id_'.$rs_type[$i]['iSTypeId'].'" value="'.$rs_type[$i]['iSTypeId'].'">',
                            "vTypeName" =>gen_strip_slash($rs_type[$i]['vTypeName']).'<input type="hidden" id="stype_name_'.$rs_type[$i]['iSTypeId'].'" value="'.$rs_type[$i]['vTypeName'].'"><input type="hidden" id="stype_icon_'.$rs_type[$i]['iSTypeId'].'" value="'.$rs_type[$i]['icon'].'">',
                            "icon" =>$site_icon.'<input type="hidden" id="stype_icon_url_'.$rs_type[$i]['iSTypeId'].'" value="'.$site_icon_image.'">',
                            "iStatus" => '<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_type[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_type[$i]['iStatus'])].'">&nbsp;</span><input type="hidden" id="stype_status_'.$rs_type[$i]['iSTypeId'].'" value="'.gen_status($rs_type[$i]['iStatus']).'">',
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
    $iSTypeId = $_POST['iSTypeId'];
    
    $rs_tot = $Site_TypeObj->delete_records($iSTypeId);
    if($rs_tot){
        $result['msg'] = MSG_DELETE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error']= 1 ;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
   echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode == "Update"){
    $result =array();
    //echo "<pre>";print_r($_FILES);
    //echo "<pre>";print_r($_REQUEST);exit();

    $file_name = $file_msg ="";
    if($_FILES["icon_url"]['name'] != ""){
        $file_arr = img_fileUpload("icon_url", $premise_type_icon_path, '', $valid_ext = array('jpg', 'jpeg', 'png'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    }else{
         $file_name = $_POST['icon_url_old'];
    }
    

    $update_array = array(
        "iSTypeId"=>$_POST['iSTypeId'], 
        "vTypeName"=>$_POST['vTypeName'],
        "iStatus"=>(isset($_POST['iStatus']))?$_POST['iStatus']:"0",
        "icon" => $file_name
    );
  // echo "<pre>";print_r($update_array);exit();
    
    $Site_TypeObj->update_arr = $update_array;
    $rs_db = $Site_TypeObj->update_records();
    if(isset($rs_db)){
        $result['msg'] = MSG_UPDATE.$file_msg;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_UPDATE_ERROR.$file_msg;
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
    
    $file_name = $file_msg ="";
    if($_FILES["icon_url"]['name'] != ""){
        
        $file_arr = img_fileUpload("icon_url", $premise_type_icon_path, '', $valid_ext = array('jpg', 'jpeg', 'png'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    }

    $insert_arr = array(
        "vTypeName" => $_POST['vTypeName'],
        "iStatus"   => isset($_POST['iStatus'])?$_POST['iStatus']:"0",
        "icon"      => $file_name
    );
    

    $Site_TypeObj->insert_arr = $insert_arr;
    $Site_TypeObj->setClause();
    $iSTypeId = $Site_TypeObj->add_records();
     if(isset($iSTypeId)){
        $result['msg'] = MSG_ADD.$file_msg;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_ADD_ERROR.$file_msg;
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
    
    $join_fieds_arr = array();
    $join_arr = array();
    $Site_TypeObj->join_field = $join_fieds_arr;
    $Site_TypeObj->join = $join_arr;
    $Site_TypeObj->where = $where_arr;
    $Site_TypeObj->param['order_by'] = 'site_type_mas."iSTypeId" ASC';
    $Site_TypeObj->param['limit'] = "";
    $Site_TypeObj->setClause();
    $Site_TypeObj->debug_query = false;
    $rs_export = $Site_TypeObj->recordset_list();
    $cnt_export = count($rs_export);
    
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
       // // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();
        //$file_name = "county_".time().".xls";
        $file_name = "premise_type_".time().".xlsx";

        if($cnt_export >0) {

            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'Premise Type')
                     ->setCellValue('C1', 'Status');
        
            for($e=0; $e<$cnt_export; $e++) {
                $status =gen_status($rs_export[$e]['iStatus']);
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iSTypeId'])
                ->setCellValue('B'.($e+2), $rs_export[$e]['vTypeName'])
                ->setCellValue('C'.($e+2), $status);
             }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Premise Type');

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
$module_name = "Premise Type List";
$module_title = "Premise Type";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("access_group_var_add",$access_group_var_add);

?>