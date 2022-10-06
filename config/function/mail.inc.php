<?php
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
        } else if ($vType == "FiberInquiryContact") {

            global $class_path;
            include_once($controller_path."fiber_inquiry.inc.php");
            $vSubject = "Your Fiber Inquiry #<".$iId.">";

            $FiberInquiryObj = new FiberInquiry();
            $FiberInquiryObj->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            //echo $iId;exit;
            $where_arr[] = 'fiberinquiry_details."iFiberInquiryId" = ' . $iId;

            $join_fieds_arr[] = 'concat(contact_mas."vFirstName", \' \', contact_mas."vLastName") as "vName"';
            $join_fieds_arr[] = 'contact_mas."vEmail"';
            $join_fieds_arr[] = 'concat(fiberinquiry_details."vAddress1", \' \', fiberinquiry_details."vStreet", \', \', city_mas."vCity", \', \', county_mas."vCounty", \' County, \', zipcode_mas."vZipcode") as "vAddress"';
            $join_arr[] = 'LEFT JOIN zipcode_mas ON fiberinquiry_details."iZipcode" = zipcode_mas."iZipcode"';
            $join_arr[] = 'LEFT JOIN city_mas ON fiberinquiry_details."iCityId" = city_mas."iCityId"';
            $join_arr[] = 'LEFT JOIN county_mas ON fiberinquiry_details."iCountyId" = county_mas."iCountyId"';
            $join_arr[] = 'LEFT JOIN contact_mas ON fiberinquiry_details."iCId" = contact_mas."iCId"';
            $FiberInquiryObj->join_field = $join_fieds_arr;
            $FiberInquiryObj->join = $join_arr;
            $FiberInquiryObj->where = $where_arr;
            $FiberInquiryObj->param['limit'] = 'LIMIT 1';
            $FiberInquiryObj->setClause();
            $rs_sr = $FiberInquiryObj->recordset_list();
            //echo "<pre>";print_r($rs_sr);exit;
            if ($rs_sr) {
                $si = count($rs_sr);
                if ($si > 0) {
                    $Name = $rs_sr[0]['vName'];
                    $vEmail = $rs_sr[0]['vEmail'];
                    $vAddress = $rs_sr[0]['vAddress'];
                    $to = $vEmail;
                    $arr_search = array('#FiberInquiry#', '#ADDRESS#', '#DATE#', '#NAME#', '#EMAIL_CUST_SERVICE#', '#MAIL_FOOTER#', '#SUPPORT_PHONE#');
                    $arr_replace = array($rs_sr[0]['iFiberInquiryId'], $vAddress, date_display_report_date(date_getSystemDate()), $Name, $EMAIL_CUST_SERVICE, nl2br($MAIL_FOOTER), $SUPPORT_PHONE);
                }
            }
        } else if ($vType == "FiberInquiryComplete") {
            global $class_path;
            include_once($controller_path."fiber_inquiry.inc.php");
            $FiberInquiryObj = new FiberInquiry();
            $FiberInquiryObj->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr[] = 'fiberinquiry_details."iFiberInquiryId" = ' . $iId;
            $join_fieds_arr[] = 'concat(contact_mas."vFirstName", \' \', contact_mas."vLastName") as "vName"';
            $join_fieds_arr[] = 'contact_mas."vEmail"';
            $join_fieds_arr[] = 'concat(fiberinquiry_details."vAddress1", \' \', fiberinquiry_details."vStreet", \', \', city_mas."vCity", \', \', county_mas."vCounty", \' County, \', zipcode_mas."vZipcode") as "vAddress"';

            $join_arr[] = 'LEFT JOIN zipcode_mas ON fiberinquiry_details."iZipcode" = zipcode_mas."iZipcode"';
            $join_arr[] = 'LEFT JOIN city_mas ON fiberinquiry_details."iCityId" = city_mas."iCityId"';
            $join_arr[] = 'LEFT JOIN county_mas ON fiberinquiry_details."iCountyId" = county_mas."iCountyId"';
            $join_arr[] = 'LEFT JOIN contact_mas ON fiberinquiry_details."iCId" = contact_mas."iCId"';

            $FiberInquiryObj->join_field = $join_fieds_arr;
            $FiberInquiryObj->join = $join_arr;
            $FiberInquiryObj->where = $where_arr;
            $FiberInquiryObj->param['limit'] = 'LIMIT 1';
            $FiberInquiryObj->setClause();
            $rs_sr = $FiberInquiryObj->recordset_list();
            //echo "<pre>";print_r($rs_sr);exit;
            if ($rs_sr) {
                $si = count($rs_sr);
                if ($si > 0) {
                    $Name = $rs_sr[0]['vName'];
                    $vEmail = $rs_sr[0]['vEmail'];
                    $vAddress = $rs_sr[0]['vAddress'];
                    $to = $vEmail;
                    $arr_search = array('#FiberInquiry#', '#ADDRESS#', '#DATE#', '#NAME#', '#EMAIL_CUST_SERVICE#', '#MAIL_FOOTER#', '#SUPPORT_PHONE#');
                    $arr_replace = array($rs_sr[0]['iFiberInquiryId'], $vAddress, date_display_report_date(date_getSystemDate()), $Name, $EMAIL_CUST_SERVICE, nl2br($MAIL_FOOTER), $SUPPORT_PHONE);
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
?>