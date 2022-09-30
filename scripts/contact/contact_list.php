<?php

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Contact", 'List');
$access_group_var_delete = per_hasModuleAccess("Contact", 'Delete', 'N');
$access_group_var_add = per_hasModuleAccess("Contact", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Contact", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Contact", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Contact", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Contact", 'Respond', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "contact.inc.php");


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

$ContactObj = new Contact();


if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    
    if($_REQUEST['vSalutation'] != ""){
     $arr_param['vSalutation'] = $_REQUEST['vSalutation'];
    }
    if($_REQUEST['vFirstName'] != ""){
     $arr_param['vFirstName'] = $_REQUEST['vFirstName'];
    }
    if($_REQUEST['vFirstNameDD'] != ""){
        $arr_param['vFirstNameDD'] = $_REQUEST['vFirstNameDD'];
    }
    if($_REQUEST['vLastName'] != ""){
     $arr_param['vLastName'] = $_REQUEST['vLastName'] ;
    } 
    if($_REQUEST['vLastNameDD'] != ""){
     $arr_param['vLastNameDD'] = $_REQUEST['vLastNameDD'];
    }
    if($_REQUEST['vCompany'] != ""){
        $arr_param['vCompany'] = $_REQUEST['vCompany'] ;
    }
    if($_REQUEST['vCompanyDD'] != ""){
      $arr_param['vCompanyDD'] = $_REQUEST['vCompanyDD'] ;
    }
    if($_REQUEST['vEmail'] != ""){
      $arr_param['vEmail'] = $_REQUEST['vEmail'];
    }
    if($_REQUEST['vEmailDD'] != ""){
        $arr_param['vEmailDD'] = $_REQUEST['vEmailDD'];
    }
    if($_REQUEST['vPosition'] != ""){
     $arr_param['vPosition'] = $_REQUEST['vPosition'];
    }
    if($_REQUEST['vPositionDD'] != ""){
         $arr_param['vPositionDD'] = $_REQUEST['vPositionDD'];
    }

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    //echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."contact_list.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));
    //
    $response = curl_exec($ch);
    curl_close($ch);  
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    //echo "<pre>";print_r(json_encode($result_arr['result']));exit();

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_contact = $result_arr['result']['data'];
    $ni = count($rs_contact);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $actions = $delete = "";
            if($access_group_var_edit == "1"){
                $actions .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_contact[$i]['iCId'].',\'edit\',\'\',\'contact\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == 1) {
                $actions .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_contact[$i]['iCId'].');"><i class="fa fa-trash"></i></a>';
            }

            $actions .= ' <a class="btn btn-outline-warning" title="History" href="javascript:void(0);" onclick="showContactHistory('.$rs_contact[$i]['iCId'].",'".$rs_contact[$i]['vFirstName']."'".",'".$rs_contact[$i]['vLastName']."'".')"><i class="fas fa-history "></i></a>';

            $hidden_fields = '<input type="hidden" id="cnt_id_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['iCId'].'"><input type="hidden" id="cnt_salution_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['vSalutation'].'"><input type="hidden" id="cnt_fname_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['vFirstName'].'"><input type="hidden" id="cnt_lname_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['vLastName'].'"><input type="hidden" id="cnt_company_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['vCompany'].'"><input type="hidden" id="cnt_position_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['vPosition'].'"><input type="hidden" id="cnt_phone_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['vPhone'].'"><input type="hidden" id="cnt_email_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['vEmail'].'"><input type="hidden" id="cnt_notes_'.$rs_contact[$i]['iCId'].'"  value="'.$rs_contact[$i]['tNotes'].'"><input type="hidden" id="cnt_status_'.$rs_contact[$i]['iCId'].'" value="'.$rs_contact[$i]['status'].'">';

            $entry[] = array(
                'checkbox' => gen_strip_slash($rs_contact[$i]['iCId']),
                "name" => $rs_contact[$i]['name'].$hidden_fields,
                "vCompany" => $rs_contact[$i]['vCompany'],
                'vPosition' => gen_strip_slash($rs_contact[$i]['vPosition']),
                'vPhone' => gen_strip_slash($rs_contact[$i]['vPhone']),
                'vEmail' => $rs_contact[$i]['vEmail'],
                'status' =>  '<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_contact[$i]['status']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_contact[$i]['status'])].'">&nbsp;</span>',
                "actions" => ($actions == "") ? "---" : $actions
            );
        }
    }
    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
 
}
else if ($_REQUEST['mode'] == "contact_history") {
    $id=$_REQUEST['contact_history_id'];
    $arr_param = array();
    $arr_param['id']= $id; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."contact_history.json";
    //echo $API_URL."<pre>";print_r(json_encode($arr_param));exit();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));

    $res = curl_exec($ch);
    curl_close($ch);
    //echo "<pre>";print_r($res);exit();   
    $response = json_decode($res, true);
    echo json_encode($response['result']);
    hc_exit();

}else if($_REQUEST['mode'] == "Update")
{  
    $vPhone = array();

    if(trim($_POST['vPrimaryPhone']) != ""){
        $phonenum = str_replace(str_split('()_'), '', $_POST['vPrimaryPhone']);
        $vPhone =  $ContactObj->getPhoneValueArr($phonenum,"-");
    } 

    $arr_param =array();
    $arr_param = array(
        "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "iCId" => $_POST['iCId'],
        "vSalutation" => $_POST['vSalutation'],
        "vFirstName" => $_POST['vFirstName'],
        "vLastName" => $_POST['vLastName'],
        "vCompany" => $_POST['vCompany'],
        "vPosition" => addslashes($_POST['vPosition']),
        "vEmail" => addslashes($_POST['vEmail']),
        "vPhone" => $vPhone,        
        "tNotes" => addslashes($_POST['tNotes']),
        "iStatus" => isset($_POST['iStatus'])?$_POST['iStatus']:"0"
    );
   
     $API_URL = $site_api_url."contact_edit.json";
       //echo "<pre>";print_r($API_URL);exit();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));

    $res = curl_exec($ch);
    curl_close($ch);
    //echo "<pre>";print_r($res);exit();   
    $response = json_decode($res, true);
   //$iContactId= json_decode($response['iContactId']);
   $rs_db = $response['result'];
 
    $result = array();
    if($rs_db){
        $result['error'] = 0 ;
        $result['msg'] = MSG_UPDATE;
    }else{
        $result['error'] = 1 ;
        $result['msg'] = MSG_UPDATE_ERROR;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}
else if ($mode == "Add") {
    $vPhone = array();
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit;
        if(trim($_POST['vPrimaryPhone']) != ""){
            $phonenum = str_replace(str_split('()_'), '', $_POST['vPrimaryPhone']);
            $vPhone =  $ContactObj->getPhoneValueArr($phonenum,"-");
        } 

        $arr_param = array(
            "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "vFirstName"            => $_POST['vFirstName'],
            "vLastName"             => $_POST['vLastName'],
            "vSalutation"           => $_POST['vSalutation'],
            "vCompany"              => $_POST['vCompany'],
            "vEmail"                => $_POST['vEmail'],
            "vPosition"             => $_POST['vPosition'],
            "vPhone"                => $vPhone,
            "tNotes"                => $_POST['tNotes'],
            "iStatus"               => isset($_POST['iStatus'])?$_POST['iStatus']:"0",
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."contact_add.json";
       //echo "<pre>";print_r($API_URL);exit();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/json",
        ));

        $res = curl_exec($ch);
        curl_close($ch);
        //echo "<pre>";print_r($res);exit();   
        $response = json_decode($res, true);
       //$iContactId= json_decode($response['iContactId']);
       $iContactId= $response['result'];
 
        $result = array();
        if($iContactId){
            $result['iContactId'] = $iContactId;
            $result['msg'] = MSG_ADD;
            $result['error']= 0 ;
        }else{
            $result['msg'] = MSG_ADD_ERROR;
            $result['error']= 1 ;
        }
    }else {
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
else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iCId = $_POST['iCId'];
    
    $arr_param['iCId'] = $iCId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."contact_delete.json";
   //echo $API_URL."<pre>";print_r($arr_param);exit();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));

    $rs_tot = curl_exec($ch);
   //echo "<pre>";print_r($rs);exit();  
    curl_close($ch); 
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
else if($mode== "Excel"){
   $ContactObj->contact_clear_variable();
    $where_arr = array();
    $where_arr[] = '"iDelete" <> 1';
    if ($query) {
        $where_arr[] = $qtype . " LIKE '" . addslashes($query) . "%'";
    }

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
     if ($Keyword != "") {
        if ($vOptions == "Name") {
            $where_arr[] = "concat(contact_mas.\"vSalutation\", ' ',contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") ILIKE '" . $Keyword . "%'";
        } 
        else if($vOptions == "vPhone" ) {
           $where_arr[] = 'contact_mas. "vPhone" LIKE \''.$_REQUEST['vPrimaryPhone'].'%\' ';
        } else if( $vOptions == "iCId") {
            $where_arr[] = " contact_mas.\"iCId\"  = '". $Keyword . "'";
        } else if($vOptions == "iStatus"){
            if(strtolower($Keyword) == "active"){
                $where_arr[] = "\"iStatus\" = '1'";
            }
            else if(strtolower($Keyword) == "inactive"){
                $where_arr[] = "\"iStatus\" = '0'";
            }
        } else {
            $where_arr[] = '"' . $vOptions . "\" ILIKE '" . $Keyword . "%'";
        }
    }

    if($_REQUEST['vSalutation']!="") {
        $where_arr[] = 'contact_mas."vSalutation" LIKE \''.trim($_REQUEST['vSalutation']).'\'';
    }


    if($_REQUEST['vFirstName']!="") {
        if($_REQUEST['vFirstNameDD']!="") {
            if($_REQUEST['vFirstNameDD']=="Begins") {
                $where_arr[] = 'contact_mas."vFirstName" LIKE \''.trim($_REQUEST['vFirstName']).'%\'';
            }
            else if($_REQUEST['vFirstNameDD']=="Ends") {
                $where_arr[] = 'contact_mas."vFirstName" LIKE \'%'.trim($_REQUEST['vFirstName']).'\'';
            }
            else if($_REQUEST['vFirstNameDD']=="Contains") {
                $where_arr[] = 'contact_mas."vFirstName" LIKE \'%'.trim($_REQUEST['vFirstName']).'%\'';
            }
            else if($_REQUEST['vFirstNameDD']=="Exactly") {
                $where_arr[] = 'contact_mas."vFirstName" = \''.trim($_REQUEST['vFirstName']).'\'';
            }
        }
        else {
            $where_arr[] = 'contact_mas."vFirstName" LIKE \''.trim($_REQUEST['vFirstName']).'%\'';
        }
    }

    if($_REQUEST['vLastName']!="") {
        if($_REQUEST['vLastNameDD']!="") {
            if($_REQUEST['vLastNameDD']=="Begins") {
                $where_arr[] = 'contact_mas."vLastName" LIKE \''.trim($_REQUEST['vLastName']).'%\'';
            }
            else if($_REQUEST['vLastNameDD']=="Ends") {
                $where_arr[] = 'contact_mas."vLastName" LIKE \'%'.trim($_REQUEST['vLastName']).'\'';
            }
            else if($_REQUEST['vLastNameDD']=="Contains") {
                $where_arr[] = 'contact_mas."vLastName" LIKE \'%'.trim($_REQUEST['vLastName']).'%\'';
            }
            else if($_REQUEST['vLastNameDD']=="Exactly") {
                $where_arr[] = 'contact_mas."vLastName" = \''.trim($_REQUEST['vLastName']).'\'';
            }
        }
        else {
            $where_arr[] = 'contact_mas."vLastName" LIKE \''.trim($_REQUEST['vLastName']).'%\'';
        }
    }


    if ($_REQUEST['vCompany'] != "") {
        if ($_REQUEST['vCompanyDD'] != "") {
            if ($_REQUEST['vCompanyDD'] == "Begins") {
                $where_arr[] = 'contact_mas."vCompany" LIKE \'' . trim($_REQUEST['vCompany']) . '%\'';
            } else if ($_REQUEST['vCompanyDD'] == "Ends") {
                $where_arr[] = 'contact_mas."vCompany" LIKE \'%' . trim($_REQUEST['vCompany']) . '\'';
            } else if ($_REQUEST['vCompanyDD'] == "Contains") {
                $where_arr[] = 'contact_mas."vCompany" LIKE \'%' . trim($_REQUEST['vCompany']) . '%\'';
            } else if ($_REQUEST['vCompanyDD'] == "Exactly") {
                $where_arr[] = 'contact_mas."vCompany" = \'' . trim($_REQUEST['vCompany']) . '\'';
            }
        } else {
            $where_arr[] = 'contact_mas."vCompany" LIKE \'' . trim($_REQUEST['vCompany']) . '%\'';
        }
    }

    if ($_REQUEST['vEmail'] != "") {
        if ($_REQUEST['vEmailDD'] != "") {
            if ($_REQUEST['vEmailDD'] == "Begins") {
                $where_arr[] = 'contact_mas."vEmail" LIKE \'' . trim($_REQUEST['vEmail']) . '%\'';
            } else if ($_REQUEST['vEmailDD'] == "Ends") {
                $where_arr[] = 'contact_mas."vEmail" LIKE \'%' . trim($_REQUEST['vEmail']) . '\'';
            } else if ($_REQUEST['vEmailDD'] == "Contains") {
                $where_arr[] = 'contact_mas."vEmail" LIKE \'%' . trim($_REQUEST['vEmail']) . '%\'';
            } else if ($_REQUEST['vEmailDD'] == "Exactly") {
                $where_arr[] = 'contact_mas."vEmail" = \'' . trim($_REQUEST['vEmail']) . '\'';
            }
        } else {
            $where_arr[] = 'contact_mas."vEmail" LIKE \'' . trim($_REQUEST['vEmail']) . '%\'';
        }
    }
    if ($_REQUEST['vPosition'] != "") {
        if ($_REQUEST['vPositionDD'] != "") {
            if ($_REQUEST['vPositionDD'] == "Begins") {
                $where_arr[] = 'contact_mas."vPosition" LIKE \'' . trim($_REQUEST['vPosition']) . '%\'';
            } else if ($_REQUEST['vPositionDD'] == "Ends") {
                $where_arr[] = 'contact_mas."vPosition" LIKE \'%' . trim($_REQUEST['vPosition']) . '\'';
            } else if ($_REQUEST['vPositionDD'] == "Contains") {
                $where_arr[] = 'contact_mas."vPosition" LIKE \'%' . trim($_REQUEST['vPosition']) . '%\'';
            } else if ($_REQUEST['vPositionDD'] == "Exactly") {
                $where_arr[] = 'contact_mas."vPosition" = \'' . trim($_REQUEST['vPosition']) . '\'';
            }
        } else {
            $where_arr[] = 'contact_mas."vPosition" LIKE \'' . trim($_REQUEST['vPosition']) . '%\'';
        }
    }
    
    $join_fieds_arr = array();
    $join_arr = array();
    $ContactObj->join_field = $join_fieds_arr;
    $ContactObj->join = $join_arr;
    $ContactObj->where = $where_arr;
    $ContactObj->param['order_by'] = "contact_mas.\"iCId\"";
    $ContactObj->param['limit'] = "";
    $ContactObj->setClause();
    $ContactObj->debug_query = false;
    $rs_export = $ContactObj->recordset_list();
    $cnt_export = count($rs_export);
      
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
       // // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();
        $file_name = "contact_".time().".xlsx";

        if($cnt_export >0) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'Name')
                     ->setCellValue('C1', 'Company')
                     ->setCellValue('D1', 'Position')
                     ->setCellValue('E1', 'Primary Phone')
                     ->setCellValue('F1', 'Email')
                     ->setCellValue('G1', 'Status');

            for($e=0; $e<$cnt_export; $e++) {

                $name = gen_strip_slash($rs_export[$e]['vSalutation']) . ' ' . gen_strip_slash($rs_export[$e]['vFirstName']) . ' ' . gen_strip_slash($rs_export[$e]['vLastName']);
                $vPhone = $rs_export[$e]['vPhone'];
                $status =gen_status($rs_export[$e]['iStatus']);
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iCId'])
                ->setCellValue('B'.($e+2), $name)
                ->setCellValue('C'.($e+2), $rs_export[$e]['vCompany'])
                ->setCellValue('D'.($e+2), $rs_export[$e]['vPosition'])
                ->setCellValue('E'.($e+2), $vPhone)
                ->setCellValue('F'.($e+2), $rs_export[$e]['vEmail'])
                ->setCellValue('G'.($e+2), $status);
             }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Contact');

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


$module_name = "Contact List";
$module_title = "Contact";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iAGroupId", $_GET['iAGroupId']);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);

?>