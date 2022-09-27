<?php

//set_error_handler("var_dump");
function sendSystemMail($vSection, $vType, $iId, $reminder_flag) {
    global $sqlObj, $site_url, $admin_url, $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE, $EMAIL_ADMIN, $MAIL_FOOTER, $SITE_NAME, $SITE_TITLE, $SUPPORT_PHONE;
    $sql = "select * from email_format where \"vType\"='$vType' and \"vSection\"='$vSection' and \"iStatus\"='1'";

    $mail_db = $sqlObj->GetAll($sql);

    if (count($mail_db) == 0)
        return;
    $vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE . "> ";
    $vSubject = $mail_db[0]['vSubject'];
    $eMIME = $mail_db[0]['eMIME'];
    $vBody = $mail_db[0]['vBody'];
    $vCC = $mail_db[0]['vCC'];
    if ($vSection == 'User') {
        if ($vType == 'Registration') {
            $sql = "select \"vFirstName\", \"vLastName\", \"vUsername\",\"vPassword\",\"vEmail\",\"sSalt\" from user_mas where \"iUserId\"='" . $iId . "' limit 1";
            $db_sql = $sqlObj->GetAll($sql);
            $Name = $db_sql[0]['vFirstName'] . " " . $db_sql[0]['vLastName'];

            $Username = $db_sql[0]['vUsername'];
            $Password = decrypt_password($db_sql);
            //$Password = decrypt($db_sql[0]['vPassword']);
            $vEmail = $db_sql[0]['vEmail'];
            $to = $vEmail;
            $arr_search = array('#Name#', '#EMAIL_FROM_NAME#', '#Username#', '#Password#', '#site_url#', '#MAIL_FOOTER#', '#EMAIL_CUST_SERVICE#');
            $arr_replace = array($Name, $EMAIL_FROM_NAME, $Username, $Password, $site_url, $MAIL_FOOTER, $EMAIL_CUST_SERVICE);
        } else if ($vType == 'ForgotPassword' || $vType == 'ResetPassword') {
            //:28-7-16 for for task score of "good" or better using some kind of password detection & auto reset password
            $sql = "select \"vFirstName\", \"vLastName\", \"vUsername\", \"vPassword\", \"vEmail\",\"sSalt\" from user_mas where \"iUserId\"='" . $iId . "' limit 1";
            $db_sql = $sqlObj->GetAll($sql);
            $Name = $db_sql[0]['vFirstName'] . " " . $db_sql[0]['vLastName'];

            $Username = $db_sql[0]['vUsername'];
            //$Password = decrypt($db_sql[0]['vPassword']);
            $Password = decrypt_password($db_sql);
            $vEmail = $db_sql[0]['vEmail'];
            $to = $vEmail;
            $arr_search = array('#Name#', '#EMAIL_FROM_NAME#', '#Username#', '#Password#', '#Link#', '#site_url#', '#MAIL_FOOTER#', '#EMAIL_CUST_SERVICE#');
            $arr_replace = array($Name, $EMAIL_FROM_NAME, $Username, $Password, $site_url . "?token=" .urlencode(encrypt($Username)), $site_url, $MAIL_FOOTER, $EMAIL_CUST_SERVICE);
               
        } else if ($vType == 'OtpVerification') {
            
            $sql = "select \"vFirstName\", \"vLastName\", \"vUsername\", \"vPassword\", \"vEmail\",\"sSalt\",\"OTP\" from user_mas where \"iUserId\"='" . $iId . "' limit 1";
            $db_sql = $sqlObj->GetAll($sql);
            $Name = $db_sql[0]['vFirstName'] . " " . $db_sql[0]['vLastName'];
            $Username = $db_sql[0]['vUsername'];
            //$Password = decrypt($db_sql[0]['vPassword']);
            $Password = decrypt_password($db_sql);
            $vEmail = $db_sql[0]['vEmail'];
            $otp = $db_sql[0]['OTP'];
            $to = $vEmail;
            $arr_search = array('#Name#', '#EMAIL_FROM_NAME#', '#OTP#', '#Link#', '#site_url#', '#MAIL_FOOTER#', '#EMAIL_CUST_SERVICE#');
            $arr_replace = array($Name, $EMAIL_FROM_NAME, $otp, $site_url . "?otp=" . urlencode(encrypt($Username)) . "&autologin=true", $site_url, $MAIL_FOOTER, $EMAIL_CUST_SERVICE);
        } else if ($vType == "SRContact") {

            global $class_path;

            include_once($controller_path."sr.inc.php");

            $vSubject = "Your Service Request #<".$iId.">";

            $SRObj = new SR();
            $SRObj->clear_variable();

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            //echo $iId;exit;
            $where_arr[] = 'sr_details."iSRId" = ' . $iId;

            $join_fieds_arr[] = 'concat(contact_mas."vFirstName", \' \', contact_mas."vLastName") as "vName"';
            $join_fieds_arr[] = 'contact_mas."vEmail"';
            $join_fieds_arr[] = 'concat(sr_details."vAddress1", \' \', sr_details."vStreet", \', \', city_mas."vCity", \', \', county_mas."vCounty", \' County, \', zipcode_mas."vZipcode") as "vAddress"';

            $join_arr[] = 'LEFT JOIN zipcode_mas ON sr_details."iZipcode" = zipcode_mas."iZipcode"';
            $join_arr[] = 'LEFT JOIN city_mas ON sr_details."iCityId" = city_mas."iCityId"';
            $join_arr[] = 'LEFT JOIN county_mas ON sr_details."iCountyId" = county_mas."iCountyId"';
            $join_arr[] = 'LEFT JOIN contact_mas ON sr_details."iCId" = contact_mas."iCId"';

            $SRObj->join_field = $join_fieds_arr;
            $SRObj->join = $join_arr;
            $SRObj->where = $where_arr;
            $SRObj->param['limit'] = 'LIMIT 1';
            $SRObj->setClause();
            $rs_sr = $SRObj->recordset_list();
            //echo "<pre>";print_r($rs_sr);exit;

            if ($rs_sr) {

                $si = count($rs_sr);

                if ($si > 0) {

                    $Name = $rs_sr[0]['vName'];
                    $vEmail = $rs_sr[0]['vEmail'];
                    $vAddress = $rs_sr[0]['vAddress'];
                    $to = $vEmail;

                    $arr_search = array('#SR#', '#ADDRESS#', '#DATE#', '#NAME#', '#EMAIL_CUST_SERVICE#', '#MAIL_FOOTER#', '#SUPPORT_PHONE#');
                    $arr_replace = array($rs_sr[0]['iSRId'], $vAddress, date_display_report_date(date_getSystemDate()), $Name, $EMAIL_CUST_SERVICE, nl2br($MAIL_FOOTER), $SUPPORT_PHONE);
                }
            }
        } else if ($vType == "SRComplete") {

            global $class_path;

            include_once($controller_path."sr.inc.php");


            $SRObj = new SR();
            $SRObj->clear_variable();

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            //echo $iId;exit;
            $where_arr[] = 'sr_details."iSRId" = ' . $iId;

            $join_fieds_arr[] = 'concat(contact_mas."vFirstName", \' \', contact_mas."vLastName") as "vName"';
            $join_fieds_arr[] = 'contact_mas."vEmail"';
            $join_fieds_arr[] = 'concat(sr_details."vAddress1", \' \', sr_details."vStreet", \', \', city_mas."vCity", \', \', county_mas."vCounty", \' County, \', zipcode_mas."vZipcode") as "vAddress"';

            $join_arr[] = 'LEFT JOIN zipcode_mas ON sr_details."iZipcode" = zipcode_mas."iZipcode"';
            $join_arr[] = 'LEFT JOIN city_mas ON sr_details."iCityId" = city_mas."iCityId"';
            $join_arr[] = 'LEFT JOIN county_mas ON sr_details."iCountyId" = county_mas."iCountyId"';
            $join_arr[] = 'LEFT JOIN contact_mas ON sr_details."iCId" = contact_mas."iCId"';

            $SRObj->join_field = $join_fieds_arr;
            $SRObj->join = $join_arr;
            $SRObj->where = $where_arr;
            $SRObj->param['limit'] = 'LIMIT 1';
            $SRObj->setClause();
            $rs_sr = $SRObj->recordset_list();
            //echo "<pre>";print_r($rs_sr);exit;

            if ($rs_sr) {

                $si = count($rs_sr);

                if ($si > 0) {

                    $Name = $rs_sr[0]['vName'];
                    $vEmail = $rs_sr[0]['vEmail'];
                    $vAddress = $rs_sr[0]['vAddress'];
                    $tRequestorNotes = $rs_sr[0]['tRequestorNotes'];
                    $to = $vEmail;

                    $arr_search = array('#SR#', '#ADDRESS#', '#DATE#', '#NAME#', '#EMAIL_CUST_SERVICE#', '#MAIL_FOOTER#', '#SUPPORT_PHONE#', '#REQUESTOR_NOTES#');
                    $arr_replace = array($rs_sr[0]['iSRId'], $vAddress, date_display_report_date(date_getSystemDate()), $Name, $EMAIL_CUST_SERVICE, nl2br($MAIL_FOOTER), $SUPPORT_PHONE, $tRequestorNotes);
                }
            }
        }

    }

    $reminder_subject = '';
    if($reminder_flag == 1)
        $reminder_subject = 'Reminder: ';        

    $vSubject = $reminder_subject.str_replace($arr_search, $arr_replace, $vSubject);
    //echo $vSubject;exit;
    $vBody = str_replace($arr_search, $arr_replace, $vBody);
    //echo nl2br($vBody);exit;

    if ($eMIME == 'html')
        $format = 'text/html';
    else
        $format = 'text/plain';
    /*if ($to != '')
        return mailme($to, $vSubject, $vBody, $vFrom, $format, $vCC, $bcc = ""); */
    if ($to != '')
        return sendSMTPMail($to, $vSubject, $vBody, $format ,$vCC , $bcc="");
}

function sendSystemMailWithAttachment($vSection, $vType, $iId, $file_path) {

    global $sqlObj, $site_url, $admin_url, $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE, $EMAIL_ADMIN, $MAIL_FOOTER, $SITE_NAME, $SITE_TITLE;
 
    $sql = "select * from email_format where \"vType\"='$vType' and \"vSection\"='$vSection' and \"iStatus\"='1'";
    $mail_db = $sqlObj->GetAll($sql);
    //echo "<pre>";print_r($mail_db);exit;

    if (count($mail_db) == 0){
        return;
    }
    $vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE . "> ";
    $vSubject = $mail_db[0]['vSubject'];
    $eMIME = $mail_db[0]['eMIME'];
    $vBody = $mail_db[0]['vBody'];
    $vCC = $mail_db[0]['vCC'];
    if ($vSection == 'User') {
        if ($vType == 'BillingInvoice') {

            $sql = "select invoice.\"iInvoiceId\", invoice.\"iCustomerId\", invoice.\"vReferenceNo\", invoice.\"dFromDate\", invoice.\"dToDate\", concat(customer_mas.\"vFirstName\", ' ', customer_mas.\"vLastName\") as \"vName\", customer_mas.\"vEmail\" from invoice LEFT JOIN customer_mas ON invoice.\"iCustomerId\"=customer_mas.\"iCustomerId\" where invoice.\"iInvoiceId\"='" . $iId . "' limit 1";
            $db_sql = $sqlObj->GetAll($sql);
            //echo "<pre>";print_r($db_sql);exit;

            $Name = $db_sql[0]['vName'];
            $dFromDate = date('M Y', strtotime($db_sql[0]['dFromDate']));
            $dToDate = date('M Y', strtotime($db_sql[0]['dToDate']));
            $vEmail = $db_sql[0]['vEmail'];
            $INVOICENO = $db_sql[0]['vReferenceNo'];
            $DATE_RANGE = "the Month of " . $dFromDate;
            $file_name = explode("/", $file_path);
            $INVOICE_PDF_NAME = end($file_name);

            $to = $vEmail;
            $arr_search = array('#Name#', '#EMAIL_FROM_NAME#', '#Username#', '#Password#', '#site_url#', '#MAIL_FOOTER#', '#EMAIL_CUST_SERVICE#', '#INVOICENO#', '#DATE_RANGE#', '#INVOICE_PDF_NAME#');
            $arr_replace = array($Name, $EMAIL_FROM_NAME, $Username, $Password, $site_url, $MAIL_FOOTER, $EMAIL_CUST_SERVICE, $INVOICENO, $DATE_RANGE, $INVOICE_PDF_NAME);
        }
        else if($vType == 'SRAssigned')
        {
            global $class_path;

            include_once($class_path."Application/service_request.inc.php");
            include_once($class_path."Application/user.inc.php");

            $SRObj = new SR();
            $UserObj = new User();

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();

            $where_arr[] = 'service_request."iSRId"=' . $iId;

            $join_fieds_arr[] = 'concat(user_mas."vFirstName", \' \', user_mas."vLastName") as "vName"';
            $join_fieds_arr[] = 'user_mas."vEmail"';

            $join_arr[] = ' INNER JOIN user_mas ON service_request."iAssignedToUserId" = user_mas."iUserId"';

            $SRObj->join_field = $join_fieds_arr;
            $SRObj->join = $join_arr;
            $SRObj->where = $where_arr;
            $SRObj->param['limit'] = "LIMIT 1";
            $SRObj->setClause();
            $rs_sr = $SRObj->recordset_list();

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr  = array();

            $where_arr[] = 'user_preference."iMailNewSRAssigned" = 1';

            $join_fieds_arr[] = 'user_mas."vEmail"';
            $join_arr[] = 'LEFT JOIN user_mas ON user_preference."iUserId" = user_mas."iUserId"';

            $UserObj->join_field = $join_fieds_arr;
            $UserObj->join = $join_arr;
            $UserObj->where = $where_arr;
            $UserObj->param['limit'] = 0;
            $UserObj->setClause();
            $rs_sr_assigned_user = $UserObj->user_preference_list();
            $cnt_sr_assigned_user = count($rs_sr_assigned_user);
            $user_email_arr = array();
            if($cnt_sr_assigned_user > 0)
            {                    
                for ($i=0; $i < $cnt_sr_assigned_user; $i++)
                { 
                    $user_email_arr[] = $rs_sr_assigned_user[$i]['vEmail'];
                }
            }
            $user_pref_user_email = '';
            if(count($user_email_arr) > 0)
                $user_pref_user_email = ", ".implode(', ', $user_email_arr);

            $Name = $rs_sr[0]['vName'];
            $iSRId = $rs_sr[0]['iSRId'];
            $vEmail = $rs_sr[0]['vEmail'];
           
            $to = $vEmail;
            $vCC = 'sterling@lcmcd.org, kbaker@lcmcd.org'.$user_pref_user_email;
            //$to = $vEmail.', sterling@lcmcd.org, kbaker@lcmcd.org';
            //$to = 'pallavi.makadia@horizoncore.com';

            $url1 = '<a href="' . $site_url . 'sr/service_requests_add?mode=Update&iSRId=' . $iSRId . '" target="_blank">click here</a>';
            $url2 = '<a href="' . $site_url . 'map/technician?selected=1&iSRId=' . $iSRId . '" target="_blank">click here</a>';

            $arr_search = array('#NAME#', '#SR#', '#URL1#', '#URL2#', '#MAIL_FOOTER#');
            $arr_replace = array($Name, $iSRId, $url1, $url2, nl2br($MAIL_FOOTER));
        }
        else if($vType == 'SRCreated')
        {
            global $class_path;
            include_once($class_path."Application/service_request.inc.php");
            include_once($class_path."Application/user.inc.php");

            $UserObj = new User();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();

            $where_arr[] = 'user_mas."iAGroupId" = 2';
            $where_arr[] = "user_mas.\"vEmail\" != ''";
            $join_fieds_arr[] = 'concat(user_mas."vFirstName", \' \', user_mas."vLastName") as "vName"';
            $join_fieds_arr[] = 'user_mas."vEmail"';
            $UserObj->join_field = $join_fieds_arr;
            $UserObj->join = $join_arr;
            $UserObj->where = $where_arr;
            $UserObj->param['limit'] = 0;
            $UserObj->param['order_by'] = "\"vName\"";
            $UserObj->setClause();
            $rs_user = $UserObj->recordset_list();
            //echo "<pre>";print_r($rs_user);exit;
            $cnt_user = count($rs_user);
            $user_email_arr = array();
            $user_pref_email_arr = array();
            $user_email_arr11 = array();
            $user_email_arr1 = array();

            if($cnt_user > 0){
                for ($i=0; $i < $cnt_user; $i++){ 
                    $user_email_arr[] = $rs_user[$i]['vEmail'];
                }
            }
            //echo "<pre>";print_r($user_email_arr);
            ## New Special Event Service Request Created
            $sql_user_pref = "SELECT u.\"vEmail\" from user_preference up INNER JOIN user_mas u ON  up.\"iUserId\" = u.\"iUserId\" WHERE up.\"iMailNewSpeacialSR\" = 1 AND u.\"iStatus\" = 1 AND u.\"vEmail\" NOTNULL";
            $rs_user_pref = $sqlObj->GetCol($sql_user_pref);
            $cnt_user_pref = count($rs_user_pref);
            //echo "<pre>";print_r($rs_user_pref);exit;
            if($cnt_user_pref > 0){
				for ($i=0; $i < $cnt_user_pref; $i++){ 
					$user_pref_email_arr[] = $rs_user_pref[$i]; 
				}
            }
			$user_email_arr11 = array_merge($user_email_arr,$user_pref_email_arr);
            $user_email_arr1 = array_unique($user_email_arr11);

            //echo "<pre>";print_r($user_email_arr);exit;
            //file_put_contents($site_path."a.txt", print_r($user_email_arr,true));
            $user_emails = '';
            if(count($user_email_arr1) > 0)
                $user_emails = implode(',', $user_email_arr1);

           
            $to = $user_emails;
            //$to = 'rahul.jain@horizoncore.com';
            //echo $user_emails;exit;
            $url1 = '<a href="'.$site_url.'sr/service_requests_add?mode=Update&iSRId='.$iId.'" target="_blank">click here</a>';
            $url2 = '<a href="'.$site_url.'map/technician?selected=1&iSRId='.$iId.'" target="_blank">click here</a>';

            $arr_search = array('#SR#', '#URL1#', '#URL2#', '#MAIL_FOOTER#');
            $arr_replace = array($iId, $url1, $url2, nl2br($MAIL_FOOTER));
        }
    }

    $vSubject = str_replace($arr_search, $arr_replace, $vSubject);
    $vBody = str_replace($arr_search, $arr_replace, $vBody);

    if ($eMIME == 'html')
        $format = 'text/html';
    else
        $format = 'text/plain';
    if ($to != ''){
        return mailWithAttachment($to, $vSubject, $vBody, $vFrom, $format, $vCC, $bcc = "", $file_path);
    }
}
function sendHyacinthSRMailWithAttachment($email, $iId, $file_path) {

    global $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE;
 
    $vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE . "> ";
 
    $to = $email;
    //$to = 'pallavi.makadia@horizoncore.com';

    $vSubject = 'Hyacinth Service Request';
    $vBody = '';
    //echo $to."<hr/>".nl2br($vBody);exit;

    if ($eMIME == 'html')
        $format = 'text/html';
    else
        $format = 'text/plain';
    if ($to != '')
        return mailWithAttachment($to, $vSubject, $vBody, $vFrom, $format, $vCC, $bcc = "", $file_path);
}
function sendSRReportsMailWithAttachment($iId, $file_path_arr) {

    global $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE, $class_path;

    include_once($class_path."Application/user.inc.php");

    $UserObj = new User();

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    $where_arr[] = 'user_mas."iUserId" = '.$iId;

    $UserObj->join_field = $join_fieds_arr;
    $UserObj->join = $join_arr;
    $UserObj->where = $where_arr;
    $UserObj->param['limit'] = 0;
    $UserObj->setClause();
    $rs_user = $UserObj->recordset_list();
    //echo "<pre>";print_r($rs_user);exit;
    $vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE . "> ";
    $Name = $rs_user[0]['vName'];
    $vEmail = $rs_user[0]['vEmail'];
    $to = $vEmail;
    //$to = 'pallavi.makadia@horizoncore.com';

    $vSubject = 'Service Request Reports';

    if ($eMIME == 'html')
        $format = 'text/html';
    else
        $format = 'text/plain';
	
	$bcc = "";
	//$bcc = "zinal.patel@horizoncore.com";
    if ($to != '')
		return mailWithMultipleAttachment($to, $vSubject, $vBody, $vFrom, $format, $vCC, $bcc, $file_path_arr);
   
}

function sendSystemMaildirection($vSection, $vType, $url, $iSiteId) {

    global $class_path, $admin_panel_session_suffix;

    include_once($controller_path."service_request.inc.php");
    include_once($controller_path."user.inc.php");

    global $sqlObj, $site_url, $admin_url, $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE, $EMAIL_ADMIN, $MAIL_FOOTER, $SITE_NAME, $SITE_TITLE;
    $sql = "select * from email_format where \"vType\"='$vType' and \"vSection\"='$vSection' and \"iStatus\"='1'";
    $mail_db = $sqlObj->GetAll($sql);

    if (count($mail_db) == 0)
        return;
    $vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE . "> ";
    $vSubject = $mail_db[0]['vSubject'] . $iSiteId;
    $eMIME = $mail_db[0]['eMIME'];
    $vBody = $mail_db[0]['vBody'];
    $vCC = $mail_db[0]['vCC'];
    if ($vSection == 'User') {

        if ($vType == 'SITEAddressDirectionall' || $vType = "AllSITEAddressDirections" || $vType = "AllSRAddressDirections") {
            if ($_SESSION["sess_iUserId" . $admin_panel_session_suffix] != "") {
                $UserObj = new User();
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $UserObj->join_field = $join_fieds_arr;
                $UserObj->join = $join_arr;
                $where_arr[] = 'user_mas."iUserId" = ' . $_SESSION["sess_iUserId" . $admin_panel_session_suffix];

                $UserObj->where = $where_arr;
                $UserObj->param['limit'] = 'LIMIT 1';
                $UserObj->setClause();
                $rs_user = $UserObj->recordset_list();

                if ($rs_user[0]['vEmail']) {

                    $to = $rs_user[0]['vEmail'];

                    $Name = stripslashes($rs_user[0]['vFirstName']) . " " . stripslashes($rs_user[0]['vLastName']);

                    $address = '';
                    $url1 = '';
                    $url2 = '';

                    $arr_search = array('#Name#', '#MAIL_FOOTER#', '#NUMBER#', '#Link#', '#ADDRESS#', '#URL1#', '#URL2#');
                    $arr_replace = array($Name, nl2br($MAIL_FOOTER), $iSiteId, $url, $address, $url1, $url2);
                }
            }
        }
    }

    $vSubject = str_replace($arr_search, $arr_replace, $vSubject);
    //echo $vSubject;exit;
    $vBody = str_replace($arr_search, $arr_replace, $vBody);
//    echo nl2br($vBody);exit;

    if ($eMIME == 'html')
        $format = 'text/html';
    else
        $format = 'text/plain';
    /*if ($to != '')
        return mailme($to, $vSubject, $vBody, $vFrom, $format, $vCC, $bcc = "");*/ 
    if ($to != '')
        return sendSMTPMail($to, $vSubject, $vBody, $format ,$vCC , $bcc="");
}
/*-- Added by bhavik desai --*/
function sendMailReport($to,$attachment,$filename) {
	global $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE, $site_path;
	
	$vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE.  "> ";
	$vSubject = "Truck Trap Report";
	$eMIME = "html";
	$vBody = "Find the attch file to see your truck trap report.";
	$vCC="";
	$bcc="";
	
	if ($eMIME == 'html')
		$format = 'text/html';
	else
		$format = 'text/plain';
	
	$strSid = md5(uniqid(time()));
	$eol = "\r\n";
	$strHeader = "";
	$strHeader .= "From: " . $vFrom .$eol;
	$strHeader .= "Cc: " . $vCC . $eol;
	$strHeader .= "Bcc: ". $bcc.$eol;
	$strHeader .= "MIME-Version: 1.0".$eol;
	$strHeader .= "Content-Type: multipart/mixed; boundary=\"" . $strSid . "\"".$eol;
	$strHeader .= "Content-Transfer-Encoding: 7bit".$eol;
	$strHeader .= "This is a multi-part message in MIME format.".$eol;
	
	$nmessage="";
	$nmessage .= "--" . $strSid .$eol;
	$nmessage .= "Content-type: text/html; charset=\"iso-8859-1\"".$eol;
	$nmessage .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
	$nmessage .= $vBody .$eol;
	
	if($attachment != ""){
		$nmessage .= "--" . $strSid .$eol;
		$nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol;
		$nmessage .= "Content-Transfer-Encoding: base64".$eol;
		$nmessage .= "Content-Disposition: attachment".$eol.$eol;
		$nmessage .= $attachment .$eol.$eol;
		$nmessage .= "--" . $strSid .$eol;
	}

    $mail_log = "";
    $mail_log = "Headers : \n" . $strHeader . "\n";
    $mail_log .= "<br>Date Time :" . date("d M, Y  h:i:s") . "\r\n";
    $mail_log .= "To : " . $to . "\n";
    $mail_log .= "Subject : " . $vSubject . "\n";
    $mail_log .= "Body : \n" . $vBody . "\n";
    $mail_log .= "====================================================================\n\n";
	
    $filename = $site_path . "mail_log.html";
    if (!$fp = fopen($filename, 'a')) {
        print "Cannot open file ($filename)";
        exit;
    }
    if (!fwrite($fp, $mail_log)) {
        print "Cannot write to file ($filename)";
        exit;
    }
    fclose($fp);

	if ($_SERVER['HTTP_HOST'] == '192.168.32.125')
		$flgSend = 1;
	else
    	$flgSend = @mail($to, $vSubject, $nmessage, $strHeader);  // @ = No Show Error //

    //echo $flgSend;exit;
	return $flgSend;
}
function sendAttachedMailReport($to,$attachment,$filename,$vSubject) {
    global $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE, $site_path;
    
    $vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE.  "> ";
    $eMIME = "html";
    $vBody = "";
    $vCC="";
    $bcc="";
    
    if ($eMIME == 'html')
        $format = 'text/html';
    else
        $format = 'text/plain';
    
    $strSid = md5(uniqid(time()));
    $eol = "\r\n";
    $strHeader = "";
    $strHeader .= "From: " . $vFrom .$eol;
    $strHeader .= "Cc: " . $vCC . $eol;
    $strHeader .= "Bcc: ". $bcc.$eol;
    $strHeader .= "MIME-Version: 1.0".$eol;
    $strHeader .= "Content-Type: multipart/mixed; boundary=\"" . $strSid . "\"".$eol;
    $strHeader .= "Content-Transfer-Encoding: 7bit".$eol;
    $strHeader .= "This is a multi-part message in MIME format.".$eol;
    
    $nmessage="";
    $nmessage .= "--" . $strSid .$eol;
    $nmessage .= "Content-type: text/html; charset=\"iso-8859-1\"".$eol;
    $nmessage .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
    $nmessage .= $vBody .$eol;
    
    if($attachment != ""){
        $nmessage .= "--" . $strSid .$eol;
        $nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol;
        $nmessage .= "Content-Transfer-Encoding: base64".$eol;
        $nmessage .= "Content-Disposition: attachment".$eol.$eol;
        $nmessage .= $attachment .$eol.$eol;
        $nmessage .= "--" . $strSid .$eol;
    }

    $mail_log = "";
    $mail_log = "Headers : \n" . $strHeader . "\n";
    $mail_log .= "<br>Date Time :" . date("d M, Y  h:i:s") . "\r\n";
    $mail_log .= "To : " . $to . "\n";
    $mail_log .= "Subject : " . $vSubject . "\n";
    $mail_log .= "Body : \n" . $vBody . "\n";
    $mail_log .= "====================================================================\n\n";
    
    $filename = $site_path . "mail_log.html";
    if (!$fp = fopen($filename, 'a')) {
        print "Cannot open file ($filename)";
        exit;
    }
    if (!fwrite($fp, $mail_log)) {
        print "Cannot write to file ($filename)";
        exit;
    }
    fclose($fp);

    if ($_SERVER['HTTP_HOST'] == '192.168.32.125')
        $flgSend = 1;
    else
        $flgSend = @mail($to, $vSubject, $nmessage, $strHeader);  // @ = No Show Error //

    //echo $flgSend;exit;
    return $flgSend;
}
function sendMailBeeKeepers($to, $file_path_arr, $date) {
    global $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE;
    
    $vFrom = $EMAIL_FROM_NAME . " <" . $EMAIL_CUST_SERVICE.  "> ";
    $vSubject = "Lee county mosquito control treatment plan for ".$date;
    $eMIME = "html";
    $vBody = "";
    $vCC="";
    $bcc="";  

    //$to = 'pallavi.makadia@horizoncore.com';
    if ($eMIME == 'html')
        $format = 'text/html';
    else
        $format = 'text/plain';

    if ($to != '')
        return mailWithMultipleAttachment($to, $vSubject, $vBody, $vFrom, $format, $vCC, $bcc = "", $file_path_arr);
}

function sendMailReport_1($to,$attachment,$filename) {
	global $EMAIL_FROM_NAME, $EMAIL_CUST_SERVICE;
	$separator = md5(time());
    $eol = PHP_EOL;
    $vSubject = "Truck Trap Report";
    $emailbody="Find the attch file to see your truck trap report";
    
    $headers  = "From: ".$EMAIL_FROM_NAME." <".$EMAIL_CUST_SERVICE.">".$eol;
//     $headers .= "Bcc: email@domain.com".$eol;
    $headers .= "MIME-Version: 1.0".$eol; 
    $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol.$eol; 
    $headers .= "Content-Transfer-Encoding: 7bit".$eol;
    $headers .= "This is a MIME encoded message.".$eol.$eol;

    // message
    $message = "--".$separator.$eol;
    $message .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
    $message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
    $message .= "Find the attch file to see your truck trap report.".$eol.$eol;

    // attachment
    $message .= "--".$separator.$eol;
    $message .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
    $message .= "Content-Transfer-Encoding: base64".$eol;
    $message .= "Content-Disposition: attachment".$eol.$eol;
    $message .= $attachment.$eol.$eol;
    $message .= "--".$separator."--";


    //Email message
    $flgSend = @mail($to, $vSubject, $message, $headers);
    
    print_r(error_get_last());
	return $flgSend;
}





?>
