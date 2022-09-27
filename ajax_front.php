<?php

include_once("server.php");

$mode = $_REQUEST['mode'];

if($mode == "contact_us"){
	//$AdminEMail =  "hirtechnology@gmail.com";
	$AdminEMail =  "vishalp@hirtechnology.com";
	//$AdminEMail =  "vishalp@hirtechnology.com;zinal.patel@horizoncore.com";

	if($_POST['contact_email'] != ""){
		$format =  "text/plain";

		$user_Subject ="Vectorcontrolsystem Contact Us / Enquiry Received";


		$username = $_POST['contact_name'];
		$useremail  =  $_POST['contact_email'];
		$userphone =  $_POST['contact_phone'];
		$usersubject =  $_POST['contact_subject'];
		$usermessage =  $_POST['contact_message'];


$user_body = "Thank you for submitting an Enquiry for information. The request has been sent to the appropriate person, who will be in touch with you shortly.
Below are the details of your Enquiry / Request for information:

Name: $username
Email: $useremail
Phone: $userphone
Subject: $usersubject
Message: $usermessage

If you have more enquiries, do not hesitate to contact us.

Yours Sincerely,
Vectorcontrolsystem";

	//$send_mail = mailme($useremail, $user_Subject, $user_body, $vFrom, $format, $vCC ="", $bcc="");
	$send_mail = sendSMTPMail($useremail, $user_Subject, $user_body, $format ,$vCC ="", $bcc="");
	
$admin_Subject ="Vectorcontrolsystem Contact Us / Enquiry Received";


$admin_body = "Dear Admin,

New Enquiry  is received for Vectorcontrolsystem

Below are the details of your Enquiry / Request for information:

Name: $username
Email: $useremail
Phone: $userphone
Subject: $usersubject
Message: $usermessage

Yours Sincerely,
Vectorcontrolsystem";
	//$send_mail = mailme($AdminEMail, $admin_Subject, $admin_body, $vFrom, $format, $vCC ="", $bcc="");
	$send_mail = sendSMTPMail($AdminEMail, $admin_Subject, $admin_body, $format, $vCC ="", $bcc="");
	
		if($send_mail){
			return 1;
		}else{
			return 0;
		}
	
	}else{
		return 0;
	}
}

?>