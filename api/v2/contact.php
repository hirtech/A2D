<?php
include_once($controller_path . "contact.inc.php");
include_once($controller_path . "fiber_inquiry.inc.php");

if($request_type == "contact_add"){
	//echo"<pre>qqq";print_r($RES_PARA);exit;

	$insert_arr = array(
        "vFirstName"            => $RES_PARA['vFirstName'],
        "vLastName"             => $RES_PARA['vLastName'],
        "vSalutation"           => $RES_PARA['vSalutation'],
        "vCompany"              => $RES_PARA['vCompany'],
        "vEmail"                => $RES_PARA['vEmail'],
        "vPosition"             => $RES_PARA['vPosition'],
        "vPhone"                => $RES_PARA['vPhone'],
        "tNotes"                => $RES_PARA['tNotes'],
        "iStatus"               => $RES_PARA['iStatus'],
    );

	$ContactObj = new Contact(); 
	$ContactObj->insert_arr = $insert_arr;
	$ContactObj->setClause();
	$iCId = $ContactObj->add_records();

	if($iCId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "result" => $iCId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}

else if($request_type == "contact_edit"){
    $update_arr = array(
        "iCId"          => $RES_PARA['iCId'],
        "vFirstName"    => $RES_PARA['vFirstName'],
        "vLastName"     => $RES_PARA['vLastName'],
        "vSalutation"   => $RES_PARA['vSalutation'],
        "vCompany"      => $RES_PARA['vCompany'],
        "vEmail"        => $RES_PARA['vEmail'],
        "vPosition"     => $RES_PARA['vPosition'],
        "vPhone"        => $RES_PARA['vPhone'],
        "tNotes"        => $RES_PARA['tNotes'],
        "iStatus"       => $RES_PARA['iStatus'],
    );

	$ContactObj = new Contact(); 
    $ContactObj->update_arr = $update_arr;
    $ContactObj->setClause();
    $rs_db = $ContactObj->update_records();

    if($rs_db){
      	$response_data = array("Code" => 200, "Message" => MSG_UPDATE, "result" => $RES_PARA['iCId']);
   	}else{
      	$response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   	}
}

else if($request_type == "contact_delete"){
   //echo "<pre>";print_r($RES_PARA);exit;
    
    $iCId = $RES_PARA['iCId'];
    $ContactObj = new Contact(); 
    $rs_db = $ContactObj->delete_records($iCId);

    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iCId" => $iCId);
    }
    else {
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}
else if($request_type == "contact_list"){
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iCId           = $RES_PARA['iCId'];
        $Name           = $RES_PARA['Name'];
        $Company        = $RES_PARA['Company'];
        $Position       = $RES_PARA['Position'];
        $Phone          = $RES_PARA['Phone'];
        $Email          = $RES_PARA['Email'];
        $iStatus        = $RES_PARA['iStatus'];
        $vSalutation    = $RES_PARA['vSalutation'];
        $vFirstName     = $RES_PARA['vFirstName'];
        $vFirstNameDD   = $RES_PARA['vFirstNameDD'];
        $vLastName      = $RES_PARA['vLastName'];
        $vLastNameDD    = $RES_PARA['vLastNameDD'];
        $vCompany       = $RES_PARA['vCompany'];
        $vCompanyDD     = $RES_PARA['vCompanyDD'];
        $vEmail         = $RES_PARA['vEmail'];
        $vEmailDD       = $RES_PARA['vEmailDD'];
        $vPosition      = $RES_PARA['vPosition'];
        $vPositionDD    = $RES_PARA['vPositionDD'];
        $sEcho          = $RES_PARA['sEcho'];
        $page_length    = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start          = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $display_order  = isset($RES_PARA['display_order']) ? trim($RES_PARA['display_order']) : "";
        $dir            = isset($RES_PARA['dir']) ? trim($RES_PARA['dir']) : "";
    }

    $where_arr[] = '"iDelete" <> 1';
    if ($Name != "") {
        $where_arr[] = "concat(contact_mas.\"vSalutation\", ' ',contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") ILIKE '" . $Name . "%'";
    } 
    if($Phone != "" ) {
       $where_arr[] = 'contact_mas. "vPhone" LIKE \''.$Phone.'%\' ';
    } 

    if( $iCId != "") {
        $where_arr[] = " contact_mas.\"iCId\"  = '". $iCId . "'";
    } 

    if($iStatus != ""){
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "\"iStatus\" = '0'";
        }
    } 

    if($Email != "") {
        $where_arr[] = " contact_mas.\"vEmail\" ILIKE '" . $Email . "%'";
    }

    if($Company != "") {
        $where_arr[] = " contact_mas.\"vCompany\" ILIKE '" . $Company . "%'";
    } 

    if($Position != "") {
        $where_arr[] = " contact_mas.\"vPosition\" ILIKE '" . $Position . "%'";
    }
    

    if($vSalutation !="") {
        $where_arr[] = 'contact_mas."vSalutation" LIKE \''.trim($vSalutation).'\'';
    }

    if($vFirstName !="") {
        if($vFirstNameDD !="") {
            if($vFirstNameDD =="Begins") {
                $where_arr[] = 'contact_mas."vFirstName" LIKE \''.pg_escape_string(trim($vFirstName)).'%\'';
            }
            else if($vFirstNameDD =="Ends") {
                $where_arr[] = 'contact_mas."vFirstName" LIKE \'%'.pg_escape_string(trim($vFirstName)).'\'';
            }
            else if($vFirstNameDD =="Contains") {
                $where_arr[] = 'contact_mas."vFirstName" LIKE \'%'.pg_escape_string(trim($vFirstName)).'%\'';
            }
            else if($vFirstNameD=="Exactly") {
                $where_arr[] = 'contact_mas."vFirstName" = \''.pg_escape_string(trim($vFirstName)).'\'';
            }
        }
        else {
            $where_arr[] = 'contact_mas."vFirstName" LIKE \''.pg_escape_string(trim($vFirstName)).'%\'';
        }
    }

    if($vLastName!="") {
        if($vLastNameDD!="") {
            if($vLastNameDD=="Begins") {
                $where_arr[] = 'contact_mas."vLastName" LIKE \''.pg_escape_string(trim($vLastName)).'%\'';
            }
            else if($vLastNameDD=="Ends") {
                $where_arr[] = 'contact_mas."vLastName" LIKE \'%'.pg_escape_string(trim($vLastName)).'\'';
            }
            else if($vLastNameDD=="Contains") {
                $where_arr[] = 'contact_mas."vLastName" LIKE \'%'.pg_escape_string(trim($vLastName)).'%\'';
            }
            else if($vLastNameDD=="Exactly") {
                $where_arr[] = 'contact_mas."vLastName" = \''.pg_escape_string(trim($vLastName)).'\'';
            }
        }
        else {
            $where_arr[] = 'contact_mas."vLastName" LIKE \''.pg_escape_string(trim($vLastName)).'%\'';
        }
    }

    if ($vCompany != "") {
        if ($vCompanyDD != "") {
            if ($vCompanyDD == "Begins") {
                $where_arr[] = 'contact_mas."vCompany" LIKE \'' . trim($vCompany) . '%\'';
            } else if ($vCompanyDD == "Ends") {
                $where_arr[] = 'contact_mas."vCompany" LIKE \'%' . trim($vCompany) . '\'';
            } else if ($vCompanyDD == "Contains") {
                $where_arr[] = 'contact_mas."vCompany" LIKE \'%' . trim($vCompany) . '%\'';
            } else if ($vCompanyDD == "Exactly") {
                $where_arr[] = 'contact_mas."vCompany" = \'' . trim($vCompany) . '\'';
            }
        } else {
            $where_arr[] = 'contact_mas."vCompany" LIKE \'' . trim($vCompany) . '%\'';
        }
    }

    if ($vEmail != "") {
        if ($vEmail != "") {
            if ($vEmail == "Begins") {
                $where_arr[] = 'contact_mas."vEmail" LIKE \'' . trim($vEmail) . '%\'';
            } else if ($vEmail == "Ends") {
                $where_arr[] = 'contact_mas."vEmail" LIKE \'%' . trim($vEmail) . '\'';
            } else if ($vEmail == "Contains") {
                $where_arr[] = 'contact_mas."vEmail" LIKE \'%' . trim($vEmail) . '%\'';
            } else if ($vEmail == "Exactly") {
                $where_arr[] = 'contact_mas."vEmail" = \'' . trim($vEmail) . '\'';
            }
        } else {
            $where_arr[] = 'contact_mas."vEmail" LIKE \'' . trim($vEmail) . '%\'';
        }
    }

    if ($vPosition != "") {
        if ($vPositionDD != "") {
            if ($_REQUEST['vPositionDD'] == "Begins") {
                $where_arr[] = 'contact_mas."vPosition" LIKE \'' . trim($vPosition) . '%\'';
            } else if ($vPositionDD == "Ends") {
                $where_arr[] = 'contact_mas."vPosition" LIKE \'%' . trim($vPosition) . '\'';
            } else if ($vPositionDD == "Contains") {
                $where_arr[] = 'contact_mas."vPosition" LIKE \'%' . trim($vPosition) . '%\'';
            } else if ($vPositionDD == "Exactly") {
                $where_arr[] = 'contact_mas."vPosition" = \'' . trim($vPosition) . '\'';
            }
        } else {
            $where_arr[] = 'contact_mas."vPosition" LIKE \'' . trim($vPosition) . '%\'';
        }
    }

    switch ($display_order) {
        case "0":
         $sortname = "contact_mas.\"iCId\"";
            break;
        case "1":
            $sortname = "concat(contact_mas.\"vSalutation\",' ',contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\" )";
            break;
        case "2":
            $sortname = "contact_mas.\"vCompany\"";
            break;
        case "3":
            $sortname = "contact_mas.\"vPosition\"";
            break;
        case "5":
            $sortname = "contact_mas.\"vEmail\"";
            break;
        case "6":
            $sortname = "contact_mas.\"iStatus\"";
            break;
        default:
            $sortname = 'contact_mas."iCId"';
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $ContactObj = new Contact();

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
    $total_record = $ContactObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_contact);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $name = gen_strip_slash($rs_contact[$i]['vSalutation']) . " ".gen_strip_slash($rs_contact[$i]['vFirstName']) . " " . gen_strip_slash($rs_contact[$i]['vLastName']);
            
            $data[] = array(
                'iCId' => $rs_contact[$i]['iCId'],
                'name' => $name,
                "vSalutation" => $rs_contact[$i]['vSalutation'],
                "vFirstName" => $rs_contact[$i]['vFirstName'],
                "vLastName" => $rs_contact[$i]['vLastName'],
                "vCompany" => $rs_contact[$i]['vCompany'],
                "vPosition" => $rs_contact[$i]['vPosition'],
                "vPhone" => $rs_contact[$i]['vPhone'],
                "vEmail" => $rs_contact[$i]['vEmail'],
                "tNotes" => $rs_contact[$i]['tNotes'],
                'status' => $rs_contact[$i]['iStatus']
            );
        }
    }   
    
    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "contact_history"){
    $id = $RES_PARA['id'];
    $rs_arr = array();

    //-- for contact detils --//

    $ContactObj = new Contact(); 
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr=array();
    $ContactObj->contact_clear_variable();
    if($id != '') {
        $where_arr[] = 'site_contact."iCId" = '.$id.'';
    }
    $join_arr[] = 'inner JOIN site_mas on site_mas."iSiteId" = site_contact."iSiteId"';
    $join_fieds_arr[] = "site_mas.\"vName\"";
    $join_fieds_arr[] = "site_mas.\"iSiteId\"";
    $ContactObj->join_field = $join_fieds_arr;
    $ContactObj->join = $join_arr;
    $ContactObj->where = $where_arr;
    $ContactObj->param['order_by'] = 'site_mas."iSiteId"';
    $ContactObj->setClause();
    $ContactObj->debug_query = false;
    $rs_contact =  $ContactObj->contact_site_details_list();
    //echo "<pre>";print_r($rs_contact);exit;
    $rs_arr['site_details_list'] = $rs_contact;

    //-- for sr details --//

    $FiberInquiryObj = new FiberInquiry();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr=array();
    $FiberInquiryObj->clear_variable();
    if($id != '') {
        $where_arr[] = "\"iCId\" = ".$id."";
    }
    
    $FiberInquiryObj->join_field = $join_fieds_arr;
    $FiberInquiryObj->join = $join_arr;
    $FiberInquiryObj->where = $where_arr;
    $FiberInquiryObj->param['order_by'] = '"iCId"';
    $FiberInquiryObj->setClause();
    $FiberInquiryObj->debug_query = false;
    $rs_site = $FiberInquiryObj->recordset_list();
    $rs_arr['site_list'] = $rs_site;

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $rs_arr);
}
else if($request_type == "autoSearchContact"){

    $iCId = trim($RES_PARA['iCId']);
    $vContactName = trim($RES_PARA['vContactName']);
    
    $rs_arr = array();
    
    $join_arr = array();
    $where_arr = array();
    $ContactObj = new Contact(); 
    $where_arr[] = '"iDelete" <> 1';
    if (trim($_REQUEST['iCId']) != ""){
        $where_arr[] = 'contact_mas."iCId" NOT IN (' . $iCId . ')';
    }
    $where_arr[] = "(concat(trim(contact_mas.\"vSalutation\"), ' ',trim(contact_mas.\"vFirstName\"), ' ', trim(contact_mas.\"vLastName\"))) ILIKE '%" . $vContactName. "%'  OR  trim(contact_mas.\"vSalutation\") ILIKE'" . $vContactName. "%'  OR  trim(contact_mas.\"vFirstName\") ILIKE'" . $vContactName. "%' OR trim(contact_mas.\"vLastName\") ILIKE'" . $vContactName. "%' OR contact_mas.\"vCompany\" ILIKE '" . $vContactName. "%' OR contact_mas.\"vEmail\" ILIKE '" . $vContactName. "%' OR contact_mas.\"vPhone\" ILIKE '" . $vContactName. "%' ";
    $ContactObj->where = $where_arr;
    $ContactObj->join = $join_arr;
    $ContactObj->param['order_by'] = 'concat(contact_mas."vFirstName", \' \', contact_mas."vLastName") DESC';
    $ContactObj->param['limit'] = 0;
    $ContactObj->setClause();
    $rs_contact = $ContactObj->recordset_list();
    $ni = count($rs_contact);
    for ($i = 0; $i < $ni; $i++) {
        
        $rs_arr[$i]['iCId']  =  $rs_contact[$i]['iCId'];
        $rs_arr[$i]['display']  =  $rs_contact[$i]['vSalutation'] . ' ' . $rs_contact[$i]['vFirstName'] . ' ' . $rs_contact[$i]['vLastName'] . ' [' . $rs_contact[$i]['vEmail'] . (($rs_contact[$i]['vPhone'] != "") ? ' - ' . $rs_contact[$i]['vPhone'] : "") . ']';
        $rs_arr[$i]['name'] = $rs_contact[$i]['vSalutation'] . ' ' .$rs_contact[$i]['vFirstName'] . ' ' . $rs_contact[$i]['vLastName'];
        $rs_arr[$i]['vPhone'] = $rs_contact[$i]['vPhone'];
        $rs_arr[$i]['vFirstName'] = $rs_contact[$i]['vFirstName'];
        $rs_arr[$i]['vLastName'] = $rs_contact[$i]['vLastName'];
        $rs_arr[$i]['vCompany'] = $rs_contact[$i]['vCompany'];
        $rs_arr[$i]['vEmail'] = $rs_contact[$i]['vEmail'];
    }

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $rs_arr);
}else if($request_type == "getContactData"){
    $iCId = trim($RES_PARA['iCId']);

	$ContactObj = new Contact(); 
	$where_arr = array();
    $join_arr = array();
    $join_fieds_arr = array();
	
	if($iCId != '') {
		$where_arr[] = 'contact_mas."iCId" ='.$iCId.'';
	}
    $ContactObj->where = $where_arr;
    $ContactObj->param['order_by'] = '';
    $ContactObj->param['limit'] = " LIMIT 1 ";
    $ContactObj->setClause();
    $rs_contact = $ContactObj->recordset_list();
    if($rs_contact){
        $response_data = array("Code" => 200, "result" => $rs_contact);
    }else{
        $response_data = array("Code" => 500);
    }
} 
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}
?>