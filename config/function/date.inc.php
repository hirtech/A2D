<?php
function date_getSystemDateTime(){
	return date("Y-m-d H:i:s");
}
function date_getSystemDate(){
	return date("Y-m-d");
}
function date_getDateTime($text)
{
	if($text =="" || $text=="0000-00-00 00:00:00")
		return "---";
	else
		return date('M j, Y',strtotime($text));
}

function date_getDate($text)
{
	if($text =="" || $text=="0000-00-00 00:00:00")
		return "---";
	else
		return date('M j, y',strtotime($text));
}


function date_csv_getDateTime($text)
{
	if($text =="" || $text=="0000-00-00 00:00:00")
		return "---";
	else
		return date('M j Y',strtotime($text));
}

function date_timeBetween($start_date,$end_date)   
{
	$diff = $end_date-$start_date;   
	$seconds = 0;   
	$hours   = 0;   
	$minutes = 0;   
	if($diff % 86400 <= 0){$days = $diff / 86400;}  // 86,400 seconds in a day   

	if($diff % 86400 > 0)   
	{   
		$rest = ($diff % 86400);   
		$days = ($diff - $rest) / 86400;   
		if($rest % 3600 > 0)   
		{   
			$rest1 = ($rest % 3600);   
			$hours = ($rest - $rest1) / 3600;   
			if($rest1 % 60 > 0)   
			{   
				$rest2 = ($rest1 % 60);   
				$minutes = ($rest1 - $rest2) / 60;   
				$seconds = $rest2;   
			}   
			else{$minutes = $rest1 / 60;}   
		}   
		else{$hours = $rest / 3600;}   
	}   

	if($days > 0){$days = $days.' days, ';}   
	else{$days = false;}   
	if($hours > 0){$hours = $hours.' hours, ';}   
	else{$hours = false;}   
	if($minutes > 0){$minutes = $minutes.' minutes, ';}   
	else{$minutes = false;}   
	$seconds = $seconds.' seconds';

	return $days.''.$hours.''.$minutes.''.$seconds;   
}
function date_display_report_date($str){
	if($str != ""){
		return date("m/j/Y", strtotime($str));
	}
}

function date_display_report_time($str){
	if($str != ""){
		return date("h:i A", strtotime($str));
	}
}

function date_getDateTimeAll($str)
{
	if($str =="" || $str=="0000-00-00 00:00:00")
		return "---";
	else
		return date('M j, Y (H : i : s)',strtotime($str));
}
/*-- Added By Bhavik Desai --*/
function date_getDateTimeHourMinute($str)
{
	if($str =="" || $str=="0000-00-00 00:00:00")
		return "---";
	else
		return date('M j, Y H : i',strtotime($str));
}
/*-- Ended By Bhavik Desai --*/
function date_csv_getDateTimeAll($str)
{
	if($str =="" || $str=="0000-00-00 00:00:00")
		return "---";
	else
		return date('M j Y (H : i : s)',strtotime($str));
}
/*function date_getDateTimeDDMMYYYY($str)
{
	if($str !="" && $str!="0000-00-00 00:00:00"){
		return date('d/m/Y',strtotime($str));
	}
	else{
		return "";
	}
}*/
function date_getDateTimeDDMMYYYY($str)
{
	if($str !="" && $str!="0000-00-00 00:00:00"){
		return date('m/d/Y',strtotime($str));
	}
	else{
		return "";
	}
}

function date_getDateTimeDDMMYYYYALL($str)
{
	if($str !="" && $str!="0000-00-00 00:00:00"){
		return date('M j, y H:i:s',strtotime($str));
	}
	else{
		return "";
	}
}

function date_getDateTimeDDMMYYYYHHMMSS($str)
{
	if($str !="" && $str!="0000-00-00 00:00:00")
		return date('m/d/Y h:i:s A',strtotime($str));
	else
		return "";
		
}
function date_getDateTimeMMDDYYYY($str)
{
	if($str !="" && $str!="0000-00-00 00:00:00"){
		return date('m/d/Y',strtotime($str));
	}
	else{
		return "";
	}
}
function date_getDateTimeMMDDYY($str)
{
	if($str !="" && $str!="0000-00-00 00:00:00"){
		return date('m/d/y',strtotime($str));
	}
	else{
		return "";
	}
}
function date_getDateFMY($str)
{
	if($str =="" || $str=="0000-00-00")
		return "---";
	else
		return date('F j, Y',strtotime($str));
}
function date_addDate($text, $da=0, $ma=0, $ya=0, $ha=0)
{
	$h=date('H',strtotime($text));
	$d=date('d',strtotime($text));
	$m=date('m',strtotime($text));
	$y=date('Y',strtotime($text));
	$fromTime =date("Y-m-d H:i:s", mktime($h+$ha, 0, 0, $m+$ma, $d+$da, $y+$ya));
	return $fromTime;
}

function date_addDateTime($text, $da=0, $ma=0, $ya=0, $ha=0, $ia=0, $sa=0)
{
	$h=date('H',strtotime($text));
	$i=date('i',strtotime($text));
	$s=date('s',strtotime($text));
	$d=date('d',strtotime($text));
	$m=date('m',strtotime($text));
	$y=date('Y',strtotime($text));
	$fromTime =date("Y-m-d H:i:s", mktime($h+$ha, $i+$ia, $s+$sa, $m+$ma, $d+$da, $y+$ya));
	return $fromTime;
}
function date_addDateWithoutTime($text, $da=0, $ma=0, $ya=0, $ha=0)
{
	$h=date('H',strtotime($text));
	$d=date('d',strtotime($text));
	$m=date('m',strtotime($text));
	$y=date('Y',strtotime($text));
	$fromTime =date("Y-m-d", mktime($h+$ha, 0, 0, $m+$ma, $d+$da, $y+$ya));
	return $fromTime;
}
function date_getTimeFromDate($text) {
	if($text =="" || $str=="0000-00-00 00:00:00")
		return "---";
	else
		return date("h:i a", strtotime($text));
}

$vhourvalue = array("00", "01","02", "03","04","05","06","07","08","09","10","11","12","13","14","15","16","17", "18","19","20","21","22","23");

$vminute = array("00", "01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17", "18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","58","59");

function date_getHourDropDown($selhour="",$fieldId="",$first_options="", $other_parameter='') {
	
	global $vhourvalue;

	$hourcombo = "";
	$hourcombo .= "<select class=\"INPUT\" name=\"$fieldId\" id=\"$fieldId\" $other_parameter>";
	if($first_options!="")
		$hourcombo .= "<option value=''>".$first_options."</option>";
	for($i=0;$i<count($vhourvalue);$i++) {
		
		if (trim($selhour)==trim($vhourvalue[$i])) {
			$hourcombo .= "<option value=".$vhourvalue[$i]." selected>".$vhourvalue[$i];
		} else {
			$hourcombo .= "<option value=".$vhourvalue[$i].">".$vhourvalue[$i];
		}
	}
	$hourcombo .= "</select>";
	return $hourcombo;
}

//Hour Dropodown 12 Hour Format
$vhourvalue1 = array("01","02","03","04","05","06","07","08","09","10","11","12");

function date_getHourDropDownNew($selhour="",$fieldId="",$first_options="", $other_parameter='') {
	//echo $selhour;exit;
	global $vhourvalue1;

	$hourcombo = "";
	$hourcombo .= "<select class=\"INPUT\" name=\"$fieldId\" id=\"$fieldId\" $other_parameter>";
	if($first_options!="")
		$hourcombo .= "<option value=''>".$first_options."</option>";
	for($i=0;$i<count($vhourvalue1);$i++) {
		
		if (trim($selhour)==trim($vhourvalue1[$i])) {
			$hourcombo .= "<option value=".$vhourvalue1[$i]." selected>".$vhourvalue1[$i];
		} else {
			$hourcombo .= "<option value=".$vhourvalue1[$i].">".$vhourvalue1[$i];
		}
	}
	$hourcombo .= "</select>";
	//echo $hourcombo;exit;
	return $hourcombo;
}

function date_getMinuteDropDown($selday="",$fieldId="",$first_options="", $other_parameter='') {
	
	global $vminute;
	
	$minutecombo = "";
	$minutecombo .= "<select class=\"INPUT\" name=\"$fieldId\" id=\"$fieldId\" $other_parameter>";
	if($first_options!="")
		$minutecombo .= "<option value=''>".$first_options."</option>";
	for($i=0;$i<count($vminute);$i++) {
		
		if (trim($selday)==trim($vminute[$i])) {
			$minutecombo .= "<option value=".$vminute[$i]." selected>".$vminute[$i];
		} else {
			$minutecombo .= "<option value=".$vminute[$i].">".$vminute[$i];
		}
		
	}
	$minutecombo .= "</select>";
	return $minutecombo;
}

#-------------------------------------------------------------------------------------
# MONTH Combo: (Usage : echo DisplayMonth('5',Iid)
#-------------------------------------------------------------------------------------
$vmonth = array("January","February","March","April ","May","June","July","August","September","October","November","December");

$vmonthvalue = array("01","02", "03","04","05","06","07","08","09","10","11","12");

function date_getMonthDigitWithMonthDropDown($selmonth="",$fieldId="",$first_options="", $other_parameter='') {
	
	global $vmonth, $vmonthvalue;

	$monthcombo = "";
	$monthcombo .= "<select class=\"form-control select-my\" name=\"$fieldId\" $other_parameter>";
	if($first_options!="")
		$monthcombo .= "<option value=''>".$first_options."</option>";
	for($i=0;$i<count($vmonthvalue);$i++) {
		if($i < 9)
			$cnt = "0".($i+1);
		else
			$cnt = ($i+1);
		if (trim($selmonth)==trim($vmonthvalue[$i])) {
			$monthcombo .= "<option value=".$vmonthvalue[$i]." selected>".$vmonth[$i];
		} else {
			$monthcombo .= "<option value=".$vmonthvalue[$i].">".$vmonth[$i];
		}
		
	}
	$monthcombo .= "</select>";
	return $monthcombo;
}

#-------------------------------------------------------------------------------------
# YEAR Combo: (Usage : echo DisplayYear('2010',sIid,2010);
#-------------------------------------------------------------------------------------
//echo DisplayYear(date("Y"),$dDate,'2004','2015');
function date_getYearDropDown($selday="",$fieldId="",$limitStart="",$limitEnd="",$first_options="", $other_parameter='') {
    $daycombo = "";
	$drop_down_id = str_replace("[]", "", $fieldId);
  	$daycombo .= "<select class=\"form-control select-my\" id=\"$drop_down_id\" name=\"$fieldId\" $other_parameter>";
  	if($first_options!="")
		$daycombo .= "<option value=''>".$first_options."</option>";

	$arr_selected = array();
	if($selday!='' && !is_array($selday))
		$arr_selected[] = $selday;
	else if(is_array($selday))
		$arr_selected = $selday;
	//print_r($arr_selected);
    for($i=$limitStart;$i<=$limitEnd;$i++) {
    
            if (in_array($i, $arr_selected))
             {
                    $daycombo .= "<option value=".$i." selected>".$i;
            } else {
                    $daycombo .= "<option value=".$i.">".$i;
            }
            
    }
    $daycombo .= "</select>";
    return $daycombo;
}

/* -- Added by bhavik desai -- */
function getDateTimeEST($date){
	$time = new DateTime($date);
	$time->setTimezone(new DateTimeZone('America/New_York'));
	return $time->format('Y-m-d H:i:s');
}
/* -- ended by bhavik desai -- */

#===================================================
?>