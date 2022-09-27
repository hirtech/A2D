<?php

# ----------- Access Rule Condition -----------
per_hasModuleAccess("Contact", 'List');
$access_group_var_delete = per_hasModuleAccess("Contact", 'Delete', 'N');
$access_group_var_add = per_hasModuleAccess("Contact", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Contact", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Contact", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Contact", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Contact", 'Respond', 'N');
# ----------- Access Rule Condition -----------

include_once($site_path . "scripts/session_valid.php");
include_once($function_path . "mail.inc.php");
include_once($controller_path . "report.inc.php");
include_once($controller_path . "contact.inc.php");


$vLoginUserName = $_SESSION["sess_vUsername".$admin_panel_session_suffix];
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
$ReportObj = new Report();
$ContactObj = new Contact();

if ($mode == "List") {
    $where_arr = array();
    $where_arr[] = '"iStatus"<>3';
    if ($query) {
        $where_arr[] = $qtype . " LIKE '" . addslashes($query) . "%'";
    }

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        if ($vOptions == "Name") {
            $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") ILIKE '" . $Keyword . "%'";
        } 
        else if($vOptions == "vPhone" || $vOptions == "vCell") {
            if($vOptions == "vPhone")
                $extra_cond = ' AND "vType"= \'Primary\'';
            else if($vOptions == "vCell")
                $extra_cond = ' AND "vType"= \'Alternate\'';
            $where_arr[] = 'contact_mas."iCId" IN (SELECT "iCId" FROM contact_phone WHERE "vPhone" ILIKE \''.$Keyword.'%\''.$extra_cond.')';
        } else if( $vOptions == "iCId") {
            $where_arr[] = " contact_mas.\"iCId\"  = '". $Keyword . "'";
        } else {
            $where_arr[] = '"' . $vOptions . "\" ILIKE '" . $Keyword . "%'";
        }
    }

    if($_REQUEST['vOptions']=="vPhone") {
        $where_arr[] = 'contact_mas."iCId" IN (SELECT "iCId" FROM contact_phone WHERE "vPhone" LIKE \''.$_REQUEST['vPrimaryPhone'].'%\' AND "vType"= \'Primary\')';
    }

    if($_REQUEST['vOptions']=="vCell") {
        $where_arr[] = 'contact_mas."iCId" IN (SELECT "iCId" FROM contact_phone WHERE "vPhone" LIKE \''.$_REQUEST['vAlternatePhone'].'%\' AND "vType"= \'Alternate\')';
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

    if ($_REQUEST['vName'] != "") {
        if ($_REQUEST['vNameDD'] != "") {
            if ($_REQUEST['vNameDD'] == "Begins") {
                $where_arr[] = "contact_mas.\"vFirstName\" LIKE '" . trim($_REQUEST["vName"]) . "%'";
            } else if ($_REQUEST['vNameDD'] == "Ends") {
                $where_arr[] = "contact_mas.\"vLastName\" LIKE '%" . trim($_REQUEST["vName"]) . "'";
            } else if ($_REQUEST['vNameDD'] == "Contains") {
                $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") LIKE '%" . trim($_REQUEST["vName"]) . "%'";
            } else if ($_REQUEST['vNameDD'] == "Exactly") {
                $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") = '" . trim($_REQUEST["vName"]) . "'";
            }
        } else {
            $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") LIKE '" . trim($_REQUEST["vName"]) . "%'";
        }
    }
    //echo "<pre>";print_r($where_arr);exit;

    switch ($display_order) {
        case "0":
         $sortname = "contact_mas.\"iCId\"";
            break;
        case "1":
            $sortname = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\" )";
            break;
        case "2":
            $sortname = "contact_mas.\"vCompany\"";
            break;
        case "3":
            $sortname = "contact_mas.\"vPosition\"";
            break;
        case "6":
            $sortname = "contact_mas.\"vEmail\"";
            break;
        default:
            $sortname = 'contact_mas."iCId"';
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $ContactObj->join_field = $join_fieds_arr;
    $ContactObj->join = $join_arr;
    $ContactObj->where = $where_arr;
    $ContactObj->param['order_by'] = $sortname . " " . $dir;
    $ContactObj->param['limit'] = $limit;
    $ContactObj->setClause();
    $ContactObj->debug_query = false;
    $rs_contact = $ContactObj->recordset_list();
   

    // Paging Total Records
    $total = $ContactObj->recordset_total();
    // Paging Total Records

    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $ni = count($rs_contact);
    //echo "<pre>";print_r($rs_contact);exit;
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {

            $vPhone = $vCell = "";
            $iCId = $rs_contact[$i]['iCId'];
            $c_phone_arr = $ContactObj->getContactPhoneNumbers($iCId);
            if(count($c_phone_arr)) {
                if(count($c_phone_arr)==1 && $c_phone_arr[0]['vType']=="Primary") 
                    $vPhone = $c_phone_arr[0]['vPhone'];
                else if(count($c_phone_arr)==1 && $c_phone_arr[0]['vType']=="Alternate") 
                    $vCell = $c_phone_arr[0]['vPhone'];
                else {
                    for($p=0, $np=count($c_phone_arr); $p<$np; $p++) {
                        if($c_phone_arr[$p]['vType']=="Primary")
                            $vPhone = $c_phone_arr[$p]['vPhone'];
                        else if($c_phone_arr[$p]['vType']=="Alternate")
                            $vCell = $c_phone_arr[$p]['vPhone'];
                    }
                }
            }

            $actions=$delete = "";
            if($access_group_var_edit == "1"){
                $actions .= '<a class="btn btn-outline-secondary" title="Edit" href="' . $site_url . 'contact/edit&mode=Update&iCId=' . $rs_contact[$i]['iCId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == 1) {
                $actions .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_contact[$i]['iCId'].');"><i class="fa fa-trash"></i></a>';
            }

			$ContactObj->user_clear_variable();
            $where_arr = array();
            $join_arr = array();
            $join_fieds_arr = array();
            $ContactObj->join_field = $join_fieds;
            $ContactObj->join = $join_arr;
            $ContactObj->where = $where_arr;
            $ContactObj->param['limit'] = 0;
            $ContactObj->setClause();
            
            $entry[] = array(
                'iCId' => gen_strip_slash($rs_contact[$i]['iCId']),
                "checkbox" => $rs_contact[$i]['iCId'],
                "vSalutation" => $rs_contact[$i]['vSalutation'],
                "name" => gen_strip_slash($rs_contact[$i]['vFirstName']) . " " . gen_strip_slash($rs_contact[$i]['vLastName']),
                "vCompany" => $rs_contact[$i]['vCompany'],
                'vPosition' => gen_strip_slash($rs_contact[$i]['vPosition']),
                'vPhone' => gen_strip_slash($vPhone),
                'vCell' => gen_strip_slash($vCell),
                'vEmail' => $rs_contact[$i]['vEmail'],
                'dLastModified' =>  date_getDateTime($rs_contact[$i]['dLastModified']),
                "actions" => ($actions == "")?"---":$actions
            );
        }
    }

    $jsonData['aaData'] = $entry;
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    //echo"<pre>";print_r($jsonData);exit;

    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
} 
else if($_REQUEST['mode'] == "Update")
{  
    $vPhone = array();
    $vCell = array();

    if(trim($_POST['vPrimaryPhone']) != ""){
        $phonenum = str_replace(str_split('()_'), '', $_POST['vPrimaryPhone']);
        $vPhone =  $ContactObj->getPhoneValueArr($phonenum,"-");
    } 
    if(trim($_POST['vAlternatePhone']) != ""){
        $cellnum = str_replace(str_split('()_'), '', $_POST['vAlternatePhone']);
        $vCell =  $ContactObj->getPhoneValueArr($cellnum,"-");
    }

    $iCId = $_POST['iCId'];
    $ContactObj->iContactId = $iCId;

    $update_array = array(
        "iCId" => $_POST['iCId'],
        "vSalutation" => $_POST['vSalutation'],
        "vFirstName" => $_POST['vFirstName'],
        "vLastName" => $_POST['vLastName'],
        "vCompany" => $_POST['vCompany'],
        "vPosition" => addslashes($_POST['vPosition']),
        "vEmail" => addslashes($_POST['vEmail']),
        "vPhone" => $vPhone,
        "vCell"  => $vCell,         
        "tNotes" => addslashes($_POST['tNotes'])
    );
   
    $ContactObj->update_arr = $update_array;
    $rs_db = $ContactObj->update_records();
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
} else if ($mode == "Add") {

    $vPhone = array();
    $vCell = array();

    if(trim($_POST['vPrimaryPhone']) != ""){
        $phonenum = str_replace(str_split('()_'), '', $_POST['vPrimaryPhone']);
        $vPhone =  $ContactObj->getPhoneValueArr($phonenum,"-");
    } 
    if(trim($_POST['vAlternatePhone']) != ""){
        $cellnum = str_replace(str_split('()_'), '', $_POST['vAlternatePhone']);
        $vCell =  $ContactObj->getPhoneValueArr($cellnum,"-");
    }

    $result = array();

    if (count($rs_contact) == 0) {
        $insert_array = array("vFirstName" => addslashes($_POST['vFirstName']),
            "vLastName" => addslashes($_POST['vLastName']),
            "vSalutation" => addslashes($_POST['vSalutation']),
            "vCompany" => addslashes($_POST['vCompany']),
            "vEmail" => addslashes($_POST['vEmail']),
            "vPosition" => $_POST['vPosition'],
            "vPhone"            => $vPhone,     // Primary
            "vCell"             => $vCell,          // Secondary
            "tNotes" => $_POST['tNotes'],
            "iImportId" => $_POST['iImportId']
        );
        $ContactObj->insert_arr = $insert_array;
        $ContactObj->setClause();
        $iContactId = $ContactObj->add_records();
        
        if($iContactId){
            $result['iContactId'] = $iContactId;
            $result['error'] = 0 ;
            $result['msg'] = MSG_ADD;
        }else{
            $result['error'] = 1 ;
            $result['msg'] = MSG_ADD_ERROR;
        }
    }

    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
} else if ($mode == "Delete") {
    $iCId = $_POST['iCId'];
    $result  = array();
    $rs_tot = $ContactObj->delete_records($iCId);

    if($rs_tot){
       $result['msg'] = MSG_DELETE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error']= 1 ;

    }
    //$jsonData = array('total' => $rs_tot);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
} else if($mode== "Excel"){

    $where_arr = array();

    if ($query) {
        $where_arr[] = $qtype . " LIKE '" . addslashes($query) . "%'";
    }

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        if ($vOptions == "Name") {
            $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") ILIKE '" . $Keyword . "%'";
        } 
        else if($vOptions == "vPhone" || $vOptions == "vCell") {
            if($vOptions == "vPhone")
                $extra_cond = ' AND "vType"= \'Primary\'';
            else if($vOptions == "vCell")
                $extra_cond = ' AND "vType"= \'Alternate\'';
            $where_arr[] = 'contact_mas."iCId" IN (SELECT "iCId" FROM contact_phone WHERE "vPhone" ILIKE \''.$Keyword.'%\''.$extra_cond.')';
        } else if( $vOptions == "iCId") {
            $where_arr[] = " contact_mas.\"iCId\"  = '". $Keyword . "'";
        } else {
            $where_arr[] = '"' . $vOptions . "\" ILIKE '" . $Keyword . "%'";
        }

    }

    if ($_REQUEST['iStatus'] != "") {
        if ($_REQUEST['iStatus'] != "-1")
            $where_arr[] = "contact_mas.\"iStatus\"='" . $_REQUEST['iStatus'] . "'";
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

    if ($_REQUEST['vName'] != "") {
        if ($_REQUEST['vNameDD'] != "") {
            if ($_REQUEST['vNameDD'] == "Begins") {
                $where_arr[] = "contact_mas.\"vFirstName\" LIKE '" . trim($_REQUEST["vName"]) . "%'";
            } else if ($_REQUEST['vNameDD'] == "Ends") {
                $where_arr[] = "contact_mas.\"vLastName\" LIKE '%" . trim($_REQUEST["vName"]) . "'";
            } else if ($_REQUEST['vNameDD'] == "Contains") {
                $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") LIKE '%" . trim($_REQUEST["vName"]) . "%'";
            } else if ($_REQUEST['vNameDD'] == "Exactly") {
                $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") = '" . trim($_REQUEST["vName"]) . "'";
            }
        } else {
            $where_arr[] = "concat(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") LIKE '" . trim($_REQUEST["vName"]) . "%'";
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
                     ->setCellValue('F1', 'Alternate Phone')
                     ->setCellValue('G1', 'Email')
                     ->setCellValue('H1', 'Last modified Date');

            for($e=0; $e<$cnt_export; $e++) {

                $ContactObj->user_clear_variable();
                 $vPhone = $vCell = "";
                $iCId = $rs_export[$e]['iCId'];
                $c_phone_arr = $ContactObj->getContactPhoneNumbers($iCId);
                if(count($c_phone_arr)) {
                    if(count($c_phone_arr)==1 && $c_phone_arr[0]['vType']=="Primary") 
                        $vPhone = $c_phone_arr[0]['vPhone'];
                    else if(count($c_phone_arr)==1 && $c_phone_arr[0]['vType']=="Alternate") 
                        $vCell = $c_phone_arr[0]['vPhone'];
                    else {
                        for($p=0, $np=count($c_phone_arr); $p<$np; $p++) {
                            if($c_phone_arr[$p]['vType']=="Primary")
                                $vPhone = $c_phone_arr[$p]['vPhone'];
                            else if($c_phone_arr[$p]['vType']=="Alternate")
                                $vCell = $c_phone_arr[$p]['vPhone'];
                        }
                    }
                }
                 $name = gen_strip_slash($rs_export[$e]['vFirstName']) . ' ' . gen_strip_slash($rs_export[$e]['vLastName']);
                 $dLastAccess = date_getDateTime($rs_export[$e]['dLastAccess']);
           
                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iCId'])
                ->setCellValue('B'.($e+2), $name)
                ->setCellValue('C'.($e+2), $rs_export[$e]['vCompany'])
                ->setCellValue('D'.($e+2), $rs_export[$e]['vPosition'])
                ->setCellValue('E'.($e+2), $vPhone)
                ->setCellValue('F'.($e+2), $vCell)
                ->setCellValue('G'.($e+2), $rs_export[$e]['vEmail'])
                ->setCellValue('H'.($e+2), $rs_export[$e]['dLastModified']);
             }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            

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