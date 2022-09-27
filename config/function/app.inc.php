<?php
## App Related Functions
function app_generate_login_device_tocken($length){
	$key = '';
    $keys = array_merge(range(0, 9), range(0, 9));
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $key;	
}

function app_login_token_verify($iEUserId,$vELoginToken, $vEFromDeviceIDNo){

	global $sqlObj;
	global $TOKEN_EXPIRY_HOURS;
	$curr_date  		= date_getSystemDateTime();
	$dLoginTokenExpiry 	= @strtotime($curr_date);
	$verificationArr 	= Array();
	$result['isError'] = 0;
	//echo $iUserId.'------'.$iUserType.'------'.$vToken.'------'.$vFromDeviceIDNo;exit();
	//if($iUserId > 0 && $vToken != '' && $vFromDeviceIDNo != '' && $iUserType != ''){

	if($iEUserId > 0 && $vELoginToken != ''){
		$sql = "SELECT iEUDTId, vEToken, dEExpireOn FROM exam_user_device_token WHERE iEUserId='".$iEUserId."' AND vEFromDeviceIDNo='".$vEFromDeviceIDNo."' ORDER BY iEUDTId DESC LIMIT 1";
		$rs  = $sqlObj->select($sql);
		if(count($rs) > 0){
			$iEUDTId		        = $rs[0]['iEUDTId'];
			$vEToken			    = $rs[0]['vEToken'];
			$dRSLoginTokenExpiry	= @strtotime($rs[0]['dEExpireOn']);
			//echo $dLoginTokenExpiry.'-----'.$dRSLoginTokenExpiry;exit;
			if($vELoginToken == $vEToken){
				if($dLoginTokenExpiry<=$dRSLoginTokenExpiry){
					$dEExpireOn = date_addDateTime($curr_date, $da=0, $ma=0, $ya=0, $ha=$TOKEN_EXPIRY_HOURS, $mi=0, $ss=0);
					
					 $sql = "UPDATE exam_user_device_token SET dEDate='".$curr_date."', dEExpireOn='".$dEExpireOn."' WHERE iEUDTId='".$iEUDTId."' LIMIT 1";
					$db  = $sqlObj->execute($sql);
					$result['isError'] = 0;
				}else{
					//echo "string";exit;
					$dEExpireOn = date_addDateTime($curr_date, $da=0, $ma=0, $ya=0, $ha=$TOKEN_EXPIRY_HOURS, $mi=0, $ss=0);
					 $sql = "UPDATE exam_user_device_token SET dEDate='".$curr_date."', dEExpireOn='".$dEExpireOn."' WHERE iEUDTId='".$iEUDTId."' LIMIT 1";
					$db  = $sqlObj->execute($sql);
					$result['isError'] = 0;
					//$result['isError'] = 7502; // Your login has expired. Please login for continue.
				}
			}else{
				$result['isError'] = 7501; // Invalid access. Please login again.
			}		
		}else{
			$result['isError'] = 2010; //Sorry no records found for %s.
		}
	}else{
		$result['isError'] = 1001;  //Invalid request or requested parameters are missing.
	}
	return $result;
}

?>

