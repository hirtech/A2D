<?php

## -----------------------------------------------------------------------------
## This function will write data in to temp file to check input / output value.
## Created by PN as on Friday, December 05, 2014
## -----------------------------------------------------------------------------

function gen_writeDataInTmpFile($data) {
    global $temp_gallery;
    if (is_array($data))
        $t_data = print_r($data, true);
    else
        $t_data = $data;
    //file_put_contents($temp_gallery . "a.txt", $t_data);
    $t_data = "\n--------------------------------------------------------------\n" . $t_data;
    $File = $temp_gallery . "a.txt";
    $Handle = fopen($File, 'a+');
    fwrite($Handle, $t_data);
    fclose($Handle);
}

# ------------------------------------------------------------------------
# Function close the connection and then redirect to passed url
# ------------------------------------------------------------------------

function hc_header($urldata) {
    global $sqlObj;
    if (isset($sqlObj))
        $sqlObj->Close();
    header("location:$urldata");
    exit;
}

function hc_exit() {
    global $sqlObj;
    if (isset($sqlObj))
        $sqlObj->Close();
    exit();
}

# ------------------------------------------------------------------------

function gen_status($data) {
    $status_arr = array("1" => "Active", "0" => "Inactive", "2" => "Deleted", "P" => "Pending", "C" => "Completed", "I" => "Inprocess");
    $short_status_arr = array("1", "0", "2", "P", "C", "I");
    if (in_array($data, $short_status_arr))
        return $status_arr[$data];
    else
        return "---";
}

//function to convert Abrreviation  in full name 
function gen_getAbbreviation($data) {
    $status_arr = array("L" => "Low", "M" => "Medium", "H" => "High", "U" => "Urgent", "E" => "Emergency", "C" => "Critical", "AN" => "Announcement");
    $short_status_arr = array("L", "M", "H", "U", "E", "C", "AN");
    if (in_array($data, $short_status_arr))
        return $status_arr[$data];
    else
        return "---";
}

###### Password Encrypt #############

function encrypt($data) {
    for ($i = 0, $key = 27, $c = 48; $i <= 255; $i++) {
        $c = 255 & ($key ^ ($c << 1));
        $table[$key] = $c;
        $key = 255 & ($key + 1);
    }
    $len = strlen($data);
    for ($i = 0; $i < $len; $i++) {
        $data[$i] = chr($table[ord($data[$i])]);
    }
    return base64_encode($data);
}

#########Password Decrypt ##########

function decrypt($data) {
    $data = base64_decode($data);
    for ($i = 0, $key = 27, $c = 48; $i <= 255; $i++) {
        $c = 255 & ($key ^ ($c << 1));
        $table[$c] = $key;
        $key = 255 & ($key + 1);
    }
    $len = strlen($data);
    for ($i = 0; $i < $len; $i++) {
        $data[$i] = chr($table[ord($data[$i])]);
    }
    return $data;
}

function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function gen_filter_text($str) {
    return htmlentities(stripslashes($str));
}

//This function is used in Admin side
//------------------------------------------------
function mailme($to, $subject, $vBody, $from, $format, $cc, $bcc = "") {
    global $site_path, $debug_mode, $logdir_path;
    if (strlen($format) == 0)
        $format = "text/html";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: " . $format . "; charset=iso-8859-1\r\n";

    /* additional headers */
    $headers .= "From: $from\r\n";
    if (strlen($cc) > 5)
        $headers .= "Cc: $cc\r\n";
    if (strlen($bcc) > 5)
        $headers .= "Bcc: $bcc\r\n";
    //echo "<pre>";
    $cnt = "";
    $cnt = "Headers : \n" . $headers . "\n";
    $cnt .= "<br>Date Time :" . date("d M, Y  h:i:s") . "\r\n";
    $cnt .= "To : " . $to . "\n";
    $cnt .= "Subject : " . $subject . "\n";
    $cnt .= "Body : \n" . $vBody . "\n";
    $cnt .= "====================================================================\n\n";
    //echo $cnt;exit;
    $debug_mode = "true";
    if ($debug_mode == "true") {
        $filename = $logdir_path . "mail_log.html";
        if (!$fp = fopen($filename, 'a')) {
            print "Cannot open file ($filename)";
            exit;
        }
        if (!fwrite($fp, $cnt)) {
            print "Cannot write to file ($filename)";
            exit;
        }
        fclose($fp);
        $status = true;
    }
    if ($_SERVER['HTTP_HOST'] != '192.168.32.132')
        $status = mail($to, $subject, $vBody, $headers);
    return $status;
}

function sendSMTPMail($to, $subject, $vBody, $format, $cc = "", $bcc = "") {

    global $site_path, $debug_mode, $logdir_path, $class_path;

    include_once($class_path .'smtp/PHPMailer.php');
	
	if (strlen($format) == 0)
        $format = "text/html";

    $mail = new PHPMailer;
    $mail->SMTPDebug = 1;                                   // Enable verbose debug output

    $mail->isSMTP();                                        // Set mailer to use SMTP
    $mail->Host = 'smtp.mail.us-east-1.awsapps.com';        // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                 // Enable SMTP authentication
    $mail->Username = 'noreply@vectorcontrolsystem.com';    // SMTP username
    $mail->Password = 'noreply123#';                        // SMTP password
    $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                      // TCP port to connect to
	$from = "noreply@vectorcontrolsystem.com";

    // Add a recipient
    $mail->setFrom($from);      // Add set from id
    $to = explode(';', $to);
            
    if (is_array($to)) {
        foreach ($to as $item) {
            if (trim($item) != ""){
                $mail->AddAddress(trim($item));
            }
        }
    }
    else if (trim($to) != ''){
        $mail->AddAddress(trim($to));
    }
    //$mail->AddAddress("zinal.patel@horizoncore.com");
    //echo "<pre>";print_r($mail);exit;
    //$mail->addReplyTo('info@example.com', 'Information');

    if($cc !='')
        $mail->addCC($cc);

    if($bcc !='')
        $mail->addBCC($bcc);

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	if($format == "text/html")
		$mail->isHTML(true);                                  // Set email format to HTML
	else
		$mail->isHTML(false);	

    $mail->Subject = $subject;
    $mail->Body    = $vBody;

	
	$cnt = "";
	$headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: " . $format . "; charset=iso-8859-1\r\n";

    /* additional headers */
    $headers .= "From: $from\r\n";
    if (strlen($cc) > 5)
        $headers .= "Cc: $cc\r\n";
    if (strlen($bcc) > 5)
        $headers .= "Bcc: $bcc\r\n";
    //echo "<pre>";
    $cnt = "Headers : \n" . $headers . "\n";
    $cnt .= "<br>Date Time :" . date("d M, Y  h:i:s") . "\r\n";
    $cnt .= "To : " . $to . "\n";
    $cnt .= "Subject : " . $subject . "\n";
    $cnt .= "Body : \n" . $vBody . "\n";
    $cnt .= "====================================================================\n\n";

    $debug_mode = "true";
    if ($debug_mode == "true") {
        $filename = $logdir_path . "mail_log.html";
        if (!$fp = fopen($filename, 'a')) {
            print "Cannot open file ($filename)";
            exit;
        }
        if (!fwrite($fp, $cnt)) {
            print "Cannot write to file ($filename)";
            exit;
        }
        fclose($fp);
        $status = true;
    }
   
    if ($_SERVER['HTTP_HOST'] != '192.168.32.132'){
       if($mail->send()){
	       	$status = 1;
	   }else {
		  $status = 0;
	   }
    }
	
    return $status;
}

function gen_random_string($length = 16) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //length:36
    $final_rand = '';
    for ($i = 0; $i < $length; $i++) {
        $final_rand .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $final_rand;
}

function gen_random_number_string($length = 16) {
    $chars = "123456789"; //length:36
    $final_rand = '';
    for ($i = 0; $i < $length; $i++) {
        $final_rand .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $final_rand;
}

function gen_add_slash($str) {
    if ($str) {
        return @addslashes($str);
    } else {
        return "";
    }
}

function gen_strip_slash($str) {
    if ($str) {
        return @stripslashes($str);
    } else {
        return "";
    }
}

function gen_make_currency_format($str) {
    if ($str) {
        $currency = "$" . number_format($str, 2);
    } else {
        $currency = "$0.00";
    }
    return $currency;
}

function gen_getRateInGallon($value, $unit) {
    if ($unit == "O") { // oz
        $gal_val = $value * 0.0078125;
    } else if ($unit == "P") { // pint
        $gal_val = $value * 0.125;
    } else if ($unit == "Q") { // quart
        $gal_val = $value * 0.25;
    } else if ($unit == "L") { // lb
        $gal_val = $value * 0.125;
    } else if ($unit == "G") {
        $gal_val = $value;
    }
    return number_format($gal_val, 4, '.', ''); // accuracy upto 4 digit
}

function gen_curr_amt($v, $dec = 2) {
    //return floor($v*pow(10, $dec))/pow(10, $dec);
    return round($v, $dec);
}

function gen_number_format($str, $pre = 2) {
    if ($str) {
        $number = number_format($str, $pre);
        return $number;
    }
}

function gen_clean_pdf_str($str) {
    if (trim($str) != "") {
        return str_replace("&gt; ", "", trim($str));
    } else {
        return "";
    }
}

function gen_read_csv_to_array($file_path, $file_name) {
    $csv_file = $file_path . $file_name;
    $csvarray = array();
    if (($handle = @fopen($csv_file, "r")) !== FALSE) {
        # Set the parent multidimensional array key to 0.

        $data = fgetcsv($handle, 0, ",");
        if (count($data) > 1) {
            $nn = 0;
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                # Count the total keys in the row.
                $c = count($data);
                # Populate the multidimensional array.
                for ($x = 0; $x < $c; $x++) {
                    $csvarray[$nn][$x] = $data[$x];
                }
                $nn++;
            }
        }

        $data1 = fgetcsv($handle, 0, "\t");
        if (count($data1) > 0) {
            $nn = 0;
            while (($data1 = fgetcsv($handle, 0, "\t")) !== FALSE) {

                # Count the total keys in the row.
                $c = count($data1);
                # Populate the multidimensional array.
                for ($x = 0; $x < $c; $x++) {
                    $csvarray[$nn][$x] = $data1[$x];
                }
                $nn++;
            }
        }
        # Close the File.
        fclose($handle);
    }
    //echo "<pre>";print_r($csvarray);exit;
    return $csvarray;
}

function gen_allow_null_int($val) {
    if (trim($val) != "")
        $ret = $val;
    else
        $ret = 0;

    return $ret;
}

function gen_allow_null_char($val) {
    if (trim($val) != "") {
        $search_arr = array("\\", "'");
        $replace_arr = array("", "''");
        //$ret = "'".str_replace("\\", "'", $val)."'";
        $ret = "'" . str_replace($search_arr, $replace_arr, $val) . "'";
    } else
        $ret = "NULL";

    return $ret;
}

function mailWithAttachment($to, $subject, $vBody, $from, $format, $cc, $bcc = "", $file_path = "") {
    global $site_path, $debug_mode;
    //*** Uniqid Session ***//
    $strSid = md5(uniqid(time()));

    $strHeader = "";
    $strHeader .= "From: " . $from . " \n";
    $strHeader .= "Cc: $cc\r\n";
    $strHeader .= "Bcc: $bcc\r\n";

    $strHeader .= "MIME-Version: 1.0\n";
    $strHeader .= "Content-Type: multipart/mixed; boundary=\"" . $strSid . "\"\n\n";
    $strHeader .= "This is a multi-part message in MIME format.\n";

    $strHeader .= "--" . $strSid . "\n";
    $strHeader .= "Content-type: " . $format . "; charset=utf-8\n";
    $strHeader .= "Content-Transfer-Encoding: 7bit\n\n";
    $strHeader .= $vBody . "\n\n";

    //*** Attachment ***//
    if ($file_path != "") {
        $FilesName = explode('/', $file_path);
        $strFilesName = end($FilesName);
        $strContent = chunk_split(base64_encode(file_get_contents($file_path)));
        $strHeader .= "--" . $strSid . "\n";
        $strHeader .= "Content-Type: application/octet-stream; name=\"" . $strFilesName . "\"\n";
        $strHeader .= "Content-Transfer-Encoding: base64\n";
        $strHeader .= "Content-Disposition: attachment; filename=\"" . $strFilesName . "\"\n\n";
        $strHeader .= $strContent . "\n\n";
    }

    $debug_mode = "true";
    if ($debug_mode == "true") {
        $filename = $site_path . "mail_log.html";
        if (!$fp = fopen($filename, 'a')) {
            print "Cannot open file ($filename)";
            exit;
        }
        if (!fwrite($fp, $strHeader)) {
            print "Cannot write to file ($filename)";
            exit;
        }
        fclose($fp);
        $status = true;
    }

    if ($_SERVER['HTTP_HOST'] == '192.168.32.125')
        $flgSend = 1;
    else
        $flgSend = @mail($to, $subject, null, $strHeader);  // @ = No Show Error //

    return $flgSend;
}

function mailWithMultipleAttachment($to, $subject, $vBody, $from, $format, $cc, $bcc = "", $file_path_arr = "") {

    global $site_path, $debug_mode;
    //*** Uniqid Session ***//
    $strSid = md5(uniqid(time()));

    $strHeader = "";
    $strHeader .= "From: " . $from . " \n";
    $strHeader .= "Cc: $cc\r\n";
    $strHeader .= "Bcc: $bcc\r\n";

    $strHeader .= "MIME-Version: 1.0\n";
    $strHeader .= "Content-Type: multipart/mixed; boundary=\"" . $strSid . "\"\n\n";
    $strHeader .= "This is a multi-part message in MIME format.\n";

    $strHeader .= "--" . $strSid . "\n";
    $strHeader .= "Content-type: " . $format . "; charset=utf-8\n";
    $strHeader .= "Content-Transfer-Encoding: 7bit\n\n";
    $strHeader .= $vBody . "\n\n";

    //*** Multiple Attachment ***//
    $cnt_file_path_arr = count($file_path_arr);
    if($cnt_file_path_arr > 0)
    {
        for ($i=0; $i < $cnt_file_path_arr; $i++)
        { 
            if ($file_path_arr[$i] != "")
            {
                $FilesName = explode('/', $file_path_arr[$i]);
                $strFilesName = end($FilesName);
                $strContent = chunk_split(base64_encode(file_get_contents($file_path_arr[$i])));
                $strHeader .= "--" . $strSid . "\n";
                $strHeader .= "Content-Type: application/octet-stream; name=\"" . $strFilesName . "\"\n";
                $strHeader .= "Content-Transfer-Encoding: base64\n";
                $strHeader .= "Content-Disposition: attachment; filename=\"" . $strFilesName . "\"\n\n";
                $strHeader .= $strContent . "\n\n";
            }
        }
    }
    
    $debug_mode = "true";
    if ($debug_mode == "true") {
        $filename = $site_path . "mail_log.html";
        if (!$fp = fopen($filename, 'a')) {
            print "Cannot open file ($filename)";
            exit;
        }
        if (!fwrite($fp, $strHeader)) {
            print "Cannot write to file ($filename)";
            exit;
        }
        fclose($fp);
        $status = true;
    }

    if ($_SERVER['HTTP_HOST'] == '192.168.32.125')
        $flgSend = 1;
    else
        $flgSend = @mail($to, $subject, null, $strHeader);  // @ = No Show Error //

    return $flgSend;
}

function gen_csv_remove_char($str) {
    if ($str) {
        $search_arr = array("'", ",", "\r\n");
        $replace_arr = array("", "", "");
        return str_replace($search_arr, $replace_arr, $str);
    }
}

function encrypt_password($password) {
    define('AES_256_CBC', 'aes-256-cbc');
    $sessionId = $password;
    //random number for encrtyption(salt)
    $salt = bin2hex(openssl_random_pseudo_bytes(8));
    $iv = $salt; //cipher length
    //ecrypted user sessionid
    $encryptedSession = openssl_encrypt($sessionId, AES_256_CBC, $salt, 0, $iv);
    return array('encryptedPassword' => $encryptedSession, 'salt' => $salt);
}

function decrypt_password($result) {
   // print_r($result);exit();    
    define('AES_256_CBC', 'aes-256-cbc');
    $vPassword = $result[0]['vPassword'];
    //random number for descrypt(salt)

    $salt = $result[0]['sSalt'];

    $iv = $salt; //cipher length.
    $decrypted = openssl_decrypt($vPassword, AES_256_CBC, $salt, 0, $iv);
    return $decrypted;
}

### Get XML string to Array
function xmlstring2array($string)
{
    $xml   = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_COMPACT | LIBXML_PARSEHUGE); 
    //$xml   = simplexml_load_string($string);
    $array = json_decode(json_encode($xml), TRUE);
    return $array;
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Trident/i',$u_agent))
    { // this condition is for IE11
        $bname = 'Internet Explorer';
        $ub = "rv";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Edge/i', $u_agent))
    {
        $bname = 'Microsoft Edge';
        $ub = "Edge";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    elseif(preg_match('/Windows NT 10/i',$u_agent) && preg_match('/Edge/i',$u_agent)){
        $bname = 'Microsoft Edge';
        $ub = "Edge";
    }
   
    // finally get the correct version number
    // Added "|:"
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
     ')[/|: ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

#--------------------------------------------
#   array2json
#---------------------------------------------
function array2json($arr) { 
    if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality. 
    $parts = array(); 
    $is_list = false; 

    //Find out if the given array is a numerical array 
    $keys = array_keys($arr); 
    $max_length = count($arr)-1; 
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1 
        $is_list = true; 
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position 
            if($i != $keys[$i]) { //A key fails at position check. 
                $is_list = false; //It is an associative array. 
                break; 
            } 
        } 
    } 

    foreach($arr as $key=>$value) { 
        if(is_array($value)) { //Custom handling for arrays 
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */ 
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */ 
        } else { 
            $str = ''; 
            if(!$is_list) $str = '"' . $key . '":'; 

            //Custom handling for multiple data types 
            if(is_numeric($value)) $str .= $value; //Numbers 
            elseif($value === false) $str .= 'false'; //The booleans 
            elseif($value === true) $str .= 'true'; 
            else $str .= '"' . addslashes($value) . '"'; //All other things 
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?) 

            $parts[] = $str; 
        } 
    } 
    $json = implode(',',$parts); 
    //echo "<pre>";print_r($json);exit;
    if($is_list) return '[' . $json . ']';//Return numerical JSON 
    return '{' . $json . '}';//Return associative JSON 
}

#--------------------------------------------
#  generate password
#---------------------------------------------
function generaterandomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1; //put the length -1 
    for ($i = 0; $i < 12; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //return array as string
}
?>