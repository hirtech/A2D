<?php
//error_reporting(E_ALL); ini_set('display_errors', 1);

include_once ($controller_path . "trouble_ticket.inc.php");
include_once ($controller_path . "maintenance_ticket.inc.php");
include_once ($controller_path . "service_order.inc.php");
include_once ($controller_path . "workorder.inc.php");
include_once ($controller_path . "fiber_inquiry.inc.php");
include_once ($controller_path . "event.inc.php");
include_once ($controller_path . "service_type.inc.php");

$TroubleTicketObj = new TroubleTicket();
$MaintenanceTicketObj = new MaintenanceTicket();
$ServiceOrderObj = new ServiceOrder();
$WorkOrderObj = new Workorder();
$FiberInquiryObj = new FiberInquiry();
$EventObj = new Event();
$ServiceTypeObj = new ServiceType();

if ($request_type == "dashboard_glance") {
    $currentdate = date("Y-m-d");
    $yesterdate = date("Y-m-d", strtotime("-1 days"));

    $currentweek_startdate = date("Y-m-d", strtotime("-6 days"));
    $lastweek_startdate = date("Y-m-d", strtotime("-7 days"));
    $lastweek_enddate = date("Y-m-d", strtotime("-13 days"));

    $day_glanc_arr = array();
    $week_glanc_arr = array();
    $month_glanc_arr = array();
    $week_glanc_arr = array();
    $year_glanc_arr = array();

    $result = array();

    /*==================== Day Glance ====================*/
    $day_where1 = '"dAddedDate"::date = \'' . $currentdate . '\''; //today
    $day_where2 = '"dAddedDate"::date = \'' . $yesterdate . '\''; //yester day

    // Trouble ticket Count Differnce of current day and yester day
    $rs_db_day = $TroubleTicketObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['ttcount1'] - $rs_db_day[0]['ttcount2']) / ($rs_db_day[0]['ttcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['ttcount1'] >= $rs_db_day[0]['ttcount2']){
        $tt_diff_rat = ' <span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $tt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['trouble_ticket']['today'] = (isset($rs_db_day[0]['ttcount1'])) ? $rs_db_day[0]['ttcount1'] : '0';
    $day_glanc_arr['trouble_ticket']['yesterday'] = (isset($rs_db_day[0]['ttcount2'])) ? $rs_db_day[0]['ttcount2'] : '0';
    $day_glanc_arr['trouble_ticket']['diff_ratio'] = $tt_diff_rat;

    // Maintenance ticket Count Differnce of current day and yester day
    $rs_db_day = $MaintenanceTicketObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['mtcount1'] - $rs_db_day[0]['mtcount2']) / ($rs_db_day[0]['mtcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['mtcount1'] >= $rs_db_day[0]['mtcount2']){
        $mt_diff_rat = ' <span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $mt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['maintenance_ticket']['today'] = (isset($rs_db_day[0]['mtcount1'])) ? $rs_db_day[0]['mtcount1'] : '0';
    $day_glanc_arr['maintenance_ticket']['yesterday'] = (isset($rs_db_day[0]['mtcount2'])) ? $rs_db_day[0]['mtcount2'] : '0';
    $day_glanc_arr['maintenance_ticket']['diff_ratio'] = $mt_diff_rat;

    // Service Order Count Differnce of current day and yester day
    $rs_db_day = $ServiceOrderObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['socount1'] - $rs_db_day[0]['socount2']) / ($rs_db_day[0]['socount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['socount1'] >= $rs_db_day[0]['socount2']){
        $so_diff_rat = ' <span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $so_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['service_order']['today'] = (isset($rs_db_day[0]['socount1'])) ? $rs_db_day[0]['socount1'] : '0';
    $day_glanc_arr['service_order']['yesterday'] = (isset($rs_db_day[0]['socount2'])) ? $rs_db_day[0]['socount2'] : '0';
    $day_glanc_arr['service_order']['diff_ratio'] = $so_diff_rat;

    // work Order Count Differnce of current day and yester day
    $rs_db_day = $WorkOrderObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['wocount1'] - $rs_db_day[0]['wocount2']) / ($rs_db_day[0]['wocount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['wocount1'] >= $rs_db_day[0]['wocount2']){
        $wo_diff_rat = ' <span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $wo_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['workorder']['today'] = (isset($rs_db_day[0]['wocount1'])) ? $rs_db_day[0]['wocount1'] : '0';
    $day_glanc_arr['workorder']['yesterday'] = (isset($rs_db_day[0]['wocount2'])) ? $rs_db_day[0]['wocount2'] : '0';
    $day_glanc_arr['workorder']['diff_ratio'] = $wo_diff_rat;

    //Fiber Inquiry Count Differnce of current day and yester day
    $rs_db_day = $FiberInquiryObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['ficount1'] - $rs_db_day[0]['ficount2']) / ($rs_db_day[0]['ficount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['ficount1'] >= $rs_db_day[0]['ficount2']){
        $fi_diff_rat = ' <span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $fi_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['fiber_inquiry']['today'] = (isset($rs_db_day[0]['ficount1'])) ? $rs_db_day[0]['ficount1'] : '0';
    $day_glanc_arr['fiber_inquiry']['yesterday'] = (isset($rs_db_day[0]['ficount2'])) ? $rs_db_day[0]['ficount2'] : '0';
    $day_glanc_arr['fiber_inquiry']['diff_ratio'] = $fi_diff_rat;

    //event Count Differnce of current day and yester day
    $rs_db_day = $EventObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['ecount1'] - $rs_db_day[0]['ecount2']) / ($rs_db_day[0]['ecount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['ecount1'] >= $rs_db_day[0]['ecount2']){
        $e_diff_rat = ' <span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $e_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['events']['today'] = (isset($rs_db_day[0]['ecount1'])) ? $rs_db_day[0]['ecount1'] : '0';
    $day_glanc_arr['events']['yesterday'] = (isset($rs_db_day[0]['ecount2'])) ? $rs_db_day[0]['ecount2'] : '0';
    $day_glanc_arr['events']['diff_ratio'] = $e_diff_rat;

    /*==================== Day Glance ====================*/

    /*==================== Month Glance ====================*/
    $month_where1 = 'date_trunc(\'month\', "dAddedDate") = date_trunc(\'month\',  (\'' . $currentdate . '\')::date)'; //current month
    $month_where2 = 'date_trunc(\'month\', "dAddedDate") = date_trunc(\'month\', (\'' . $currentdate . '\')::date - interval \'1 month\') '; //previous month (last month)

    //Trouble Tickets Count Differnce of current month and last month
    $rs_db_month = $TroubleTicketObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['ttcount1'] - $rs_db_month[0]['ttcount2']) / ($rs_db_month[0]['ttcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['ttcount1'] >= $rs_db_month[0]['ttcount2']){
        $tt_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $tt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['trouble_ticket']['curr_month'] = (isset($rs_db_month[0]['ttcount1'])) ? $rs_db_month[0]['ttcount1'] : '0';
    $month_glanc_arr['trouble_ticket']['last_month'] = (isset($rs_db_month[0]['ttcount2'])) ? $rs_db_month[0]['ttcount2'] : '0';
    $month_glanc_arr['trouble_ticket']['diff_ratio'] = $tt_diff_rat;

    //Maintenance Ticket Count Differnce of current month and last month
    $rs_db_month = $MaintenanceTicketObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['mtcount1'] - $rs_db_month[0]['mtcount2']) / ($rs_db_month[0]['mtcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['mtcount1'] >= $rs_db_month[0]['mtcount2']){
        $mt_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else{
        $mt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['maintenance_ticket']['curr_month'] = (isset($rs_db_month[0]['mtcount1'])) ? $rs_db_month[0]['mtcount1'] : '0';
    $month_glanc_arr['maintenance_ticket']['last_month'] = (isset($rs_db_month[0]['mtcount2'])) ? $rs_db_month[0]['mtcount2'] : '0';
    $month_glanc_arr['maintenance_ticket']['diff_ratio'] = $mt_diff_rat;

    //ServiceOrder Count Differnce of current month and last month
    $rs_db_month = $ServiceOrderObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['socount1'] - $rs_db_month[0]['socount2']) / ($rs_db_month[0]['socount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['socount1'] >= $rs_db_month[0]['socount2']){
        $so_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else{
        $so_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['service_order']['curr_month'] = (isset($rs_db_month[0]['socount1'])) ? $rs_db_month[0]['socount1'] : '0';
    $month_glanc_arr['service_order']['last_month'] = (isset($rs_db_month[0]['socount2'])) ? $rs_db_month[0]['socount2'] : '0';
    $month_glanc_arr['service_order']['diff_ratio'] = $so_diff_rat;

    //Workorder Count Differnce of current month and last month
    $rs_db_month = $WorkOrderObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['wocount1'] - $rs_db_month[0]['wocount2']) / ($rs_db_month[0]['wocount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['wocount1'] >= $rs_db_month[0]['wocount2']){
        $wo_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else {
        $wo_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['workorder']['curr_month'] = (isset($rs_db_month[0]['wocount1'])) ? $rs_db_month[0]['wocount1'] : '0';
    $month_glanc_arr['workorder']['last_month'] = (isset($rs_db_month[0]['wocount2'])) ? $rs_db_month[0]['wocount2'] : '0';
    $month_glanc_arr['workorder']['diff_ratio'] = $wo_diff_rat;

    //FiberInquiry Count Differnce of current month and last month
    $rs_db_month = $FiberInquiryObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['ficount1'] - $rs_db_month[0]['ficount2']) / ($rs_db_month[0]['ficount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['ficount1'] >= $rs_db_month[0]['ficount2']){
        $fi_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }else {
        $fi_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['fiber_inquiry']['curr_month'] = (isset($rs_db_month[0]['ficount1'])) ? $rs_db_month[0]['ficount1'] : '0';
    $month_glanc_arr['fiber_inquiry']['last_month'] = (isset($rs_db_month[0]['ficount2'])) ? $rs_db_month[0]['ficount2'] : '0';
    $month_glanc_arr['fiber_inquiry']['diff_ratio'] = $fi_diff_rat;

    //Event Count Differnce of current month and last month
    $rs_db_month = $EventObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['ecount1'] - $rs_db_month[0]['ecount2']) / ($rs_db_month[0]['ecount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['ecount1'] >= $rs_db_month[0]['ecount2']){
        $e_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $e_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['events']['curr_month'] = (isset($rs_db_month[0]['ecount1'])) ? $rs_db_month[0]['ecount1'] : '0';
    $month_glanc_arr['events']['last_month'] = (isset($rs_db_month[0]['ecount2'])) ? $rs_db_month[0]['ecount2'] : '0';
    $month_glanc_arr['events']['diff_ratio'] = $e_diff_rat;
    /*==================== Month Glance ====================*/

    /*==================== Week Glance ====================*/
    $week_where1 = ' ("dAddedDate"::date >= \'' . $currentweek_startdate . '\' and "dAddedDate"::date <= \'' . $currentdate . '\' )'; //current week
    $week_where2 = '( "dAddedDate"::date <= \'' . $lastweek_startdate . '\' and "dAddedDate"::date >= \'' . $lastweek_enddate . '\' )'; //last week

    //TroubleTicket Count Differnce of current week and last week
    $rs_db_week = $TroubleTicketObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['ttcount1'] - $rs_db_week[0]['ttcount2']) / ($rs_db_week[0]['ttcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['ttcount1'] >= $rs_db_week[0]['ttcount2']){
        $tt_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $tt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['trouble_ticket']['curr_week'] = (isset($rs_db_week[0]['ttcount1'])) ? $rs_db_week[0]['ttcount1'] : '0';
    $week_glanc_arr['trouble_ticket']['last_week'] = (isset($rs_db_week[0]['ttcount2'])) ? $rs_db_week[0]['ttcount2'] : '0';
    $week_glanc_arr['trouble_ticket']['diff_ratio'] = $tt_diff_rat;

    //MaintenanceTicket Count Differnce of current week and last week
    $rs_db_week = $MaintenanceTicketObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['mtcount1'] - $rs_db_week[0]['mtcount2']) / ($rs_db_week[0]['mtcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['mtcount1'] >= $rs_db_week[0]['mtcount2']){
        $mt_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $mt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['maintenance_ticket']['curr_week'] = (isset($rs_db_week[0]['mtcount1'])) ? $rs_db_week[0]['mtcount1'] : '0';
    $week_glanc_arr['maintenance_ticket']['last_week'] = (isset($rs_db_week[0]['mtcount2'])) ? $rs_db_week[0]['mtcount2'] : '0';
    $week_glanc_arr['maintenance_ticket']['diff_ratio'] = $mt_diff_rat;

    //ServiceOrder Count Differnce of current week and last week
    $rs_db_week = $ServiceOrderObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['socount1'] - $rs_db_week[0]['socount2']) / ($rs_db_week[0]['socount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['socount1'] >= $rs_db_week[0]['socount2']){
        $so_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $so_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['service_order']['curr_week'] = (isset($rs_db_week[0]['socount1'])) ? $rs_db_week[0]['socount1'] : '0';
    $week_glanc_arr['service_order']['last_week'] = (isset($rs_db_week[0]['socount2'])) ? $rs_db_week[0]['socount2'] : '0';
    $week_glanc_arr['service_order']['diff_ratio'] = $so_diff_rat;

    //Workorder Count Differnce of current week and last week
    $rs_db_week = $WorkOrderObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['wocount1'] - $rs_db_week[0]['wocount2']) / ($rs_db_week[0]['wocount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['wocount1'] >= $rs_db_week[0]['wocount2']){
        $wo_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $wo_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['workorder']['curr_week'] = (isset($rs_db_week[0]['wocount1'])) ? $rs_db_week[0]['wocount1'] : '0';
    $week_glanc_arr['workorder']['last_week'] = (isset($rs_db_week[0]['wocount2'])) ? $rs_db_week[0]['wocount2'] : '0';
    $week_glanc_arr['workorder']['diff_ratio'] = $wo_diff_rat;

    //FiberInquiry Count Differnce of current week and last week
    $rs_db_week = $FiberInquiryObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['ficount1'] - $rs_db_week[0]['ficount2']) / ($rs_db_week[0]['ficount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['ficount1'] >= $rs_db_week[0]['ficount2']){
        $fi_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $fi_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['fiber_inquiry']['curr_week'] = (isset($rs_db_week[0]['ficount1'])) ? $rs_db_week[0]['ficount1'] : '0';
    $week_glanc_arr['fiber_inquiry']['last_week'] = (isset($rs_db_week[0]['ficount2'])) ? $rs_db_week[0]['ficount2'] : '0';
    $week_glanc_arr['fiber_inquiry']['diff_ratio'] = $fi_diff_rat;

    //Event Count Differnce of current week and last week
    $rs_db_week = $EventObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['ecount1'] - $rs_db_week[0]['ecount2']) / ($rs_db_week[0]['ecount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['ecount1'] >= $rs_db_week[0]['ecount2']){
        $e_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $e_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['events']['curr_week'] = (isset($rs_db_week[0]['ecount1'])) ? $rs_db_week[0]['ecount1'] : '0';
    $week_glanc_arr['events']['last_week'] = (isset($rs_db_week[0]['ecount2'])) ? $rs_db_week[0]['ecount2'] : '0';
    $week_glanc_arr['events']['diff_ratio'] = $e_diff_rat;

    /*==================== Week Glance ====================*/

    /*==================== Year Glance ====================*/
    $year_where1 = 'date_trunc(\'year\', "dAddedDate")  = date_trunc(\'year\',  (\'' . $currentdate . '\')::date)'; //current year
    $year_where2 = 'date_trunc(\'year\', "dAddedDate") = date_trunc(\'year\', (\'' . $currentdate . '\')::date - interval \'1 year\') '; //previous year (last_year)

    //TroubleTicket Differnce of current year and previous year
    $rs_db_year = $TroubleTicketObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['ttcount1'] - $rs_db_year[0]['ttcount2']) / ($rs_db_year[0]['ttcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['ttcount1'] >= $rs_db_year[0]['ttcount2']){
        $tt_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $tt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['trouble_ticket']['curr_year'] = (isset($rs_db_year[0]['ttcount1'])) ? $rs_db_year[0]['ttcount1'] : '0';
    $year_glanc_arr['trouble_ticket']['last_year'] = (isset($rs_db_year[0]['ttcount2'])) ? $rs_db_year[0]['ttcount2'] : '0';
    $year_glanc_arr['trouble_ticket']['diff_ratio'] = $tt_diff_rat;

    //MaintenanceTicket Differnce of current year and previous year
    $rs_db_year = $MaintenanceTicketObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['mtcount1'] - $rs_db_year[0]['mtcount2']) / ($rs_db_year[0]['mtcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['mtcount1'] >= $rs_db_year[0]['mtcount2']){
        $mt_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $mt_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['maintenance_ticket']['curr_year'] = (isset($rs_db_year[0]['mtcount1'])) ? $rs_db_year[0]['mtcount1'] : '0';
    $year_glanc_arr['maintenance_ticket']['last_year'] = (isset($rs_db_year[0]['mtcount2'])) ? $rs_db_year[0]['mtcount2'] : '0';
    $year_glanc_arr['maintenance_ticket']['diff_ratio'] = $mt_diff_rat;

    //ServiceOrder Differnce of current year and previous year
    $rs_db_year = $ServiceOrderObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['socount1'] - $rs_db_year[0]['socount2']) / ($rs_db_year[0]['socount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['socount1'] >= $rs_db_year[0]['socount2']){
        $so_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $so_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['service_order']['curr_year'] = (isset($rs_db_year[0]['socount1'])) ? $rs_db_year[0]['socount1'] : '0';
    $year_glanc_arr['service_order']['last_year'] = (isset($rs_db_year[0]['socount2'])) ? $rs_db_year[0]['socount2'] : '0';
    $year_glanc_arr['service_order']['diff_ratio'] = $so_diff_rat;

    //Workorder Differnce of current year and previous year
    $rs_db_year = $WorkOrderObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['wocount1'] - $rs_db_year[0]['wocount2']) / ($rs_db_year[0]['wocount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['wocount1'] >= $rs_db_year[0]['wocount2']){
        $wo_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $wo_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['workorder']['curr_year'] = (isset($rs_db_year[0]['wocount1'])) ? $rs_db_year[0]['wocount1'] : '0';
    $year_glanc_arr['workorder']['last_year'] = (isset($rs_db_year[0]['wocount2'])) ? $rs_db_year[0]['wocount2'] : '0';
    $year_glanc_arr['workorder']['diff_ratio'] = $wo_diff_rat;

    //FiberInquiry Differnce of current year and previous year
    $rs_db_year = $FiberInquiryObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['ficount1'] - $rs_db_year[0]['ficount2']) / ($rs_db_year[0]['ficount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['ficount1'] >= $rs_db_year[0]['ficount2']){
        $fi_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $fi_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['fiber_inquiry']['curr_year'] = (isset($rs_db_year[0]['ficount1'])) ? $rs_db_year[0]['ficount1'] : '0';
    $year_glanc_arr['fiber_inquiry']['last_year'] = (isset($rs_db_year[0]['ficount2'])) ? $rs_db_year[0]['ficount2'] : '0';
    $year_glanc_arr['fiber_inquiry']['diff_ratio'] = $fi_diff_rat;

    //Event Differnce of current year and previous year
    $rs_db_year = $EventObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['ecount1'] - $rs_db_year[0]['ecount2']) / ($rs_db_year[0]['ecount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['ecount1'] >= $rs_db_year[0]['ecount2']){
        $e_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    } else {
        $e_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['events']['curr_year'] = (isset($rs_db_year[0]['ecount1'])) ? $rs_db_year[0]['ecount1'] : '0';
    $year_glanc_arr['events']['last_year'] = (isset($rs_db_year[0]['ecount2'])) ? $rs_db_year[0]['ecount2'] : '0';
    $year_glanc_arr['events']['diff_ratio'] = $e_diff_rat;
    /*==================== Year Glance ====================*/
    
    $result['day_galance'] = $day_glanc_arr;
    $result['month_glance'] = $month_glanc_arr;
    $result['week_glance'] = $week_glanc_arr;
    $result['year_glance'] = $year_glanc_arr;

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
} else if ($request_type == "dashboard_amchart") {
    $rs = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $ServiceTypeObj->join_field = $join_fieds_arr;
    $ServiceTypeObj->join = $join_arr;
    $ServiceTypeObj->where = $where_arr;
    $ServiceTypeObj->setClause();
    $rs = $ServiceTypeObj->servicetype_dashboard_amchart();
    // echo "<pre>"; print_r($rs);exit();
    $ni = count($rs);
    if ($ni > 0) {
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => $message, "result" => $rs);
    } else {
        $rh = HTTPStatus(400);
        $code = 2104;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 400, "Message" => $message, "result" => $rs);
    }
} else if ($request_type == "dashboard_accesgroup_map") {
    $site_arr = array();
    
    $userid = $RES_PARA['userId'];
    $iAGroupId = $RES_PARA['iAGroupId'];
    $iAccessType = $RES_PARA['iAccessType'];

    $today = date("Y-m-d");
    $LAST_15_DAYS =  date('Y-m-d', strtotime('-15 days', strtotime($today)));
    //echo $LAST_15_DAYS;exit;
    //echo $SALES_ACCESS_TYPE_ID." = ".$iAccessType;exit;
    if($SALES_ACCESS_TYPE_ID == $iAccessType) {
        // ************ Fiber Inquiry ************ //
        $FiberInquiryObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

        $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
        $join_fieds_arr[] = 'c."vCounty"';
        $join_fieds_arr[] = 'sm."vState"';
        $join_fieds_arr[] = 'cm."vCity"';
        $join_fieds_arr[] = 'z."vZoneName"';
        $join_fieds_arr[] = 'n."vName" as "vNetwork"';
        $join_fieds_arr[] = 'engagement_mas."vEngagement"';
        $join_fieds_arr[] = 'sst."vSubTypeName" as "vPremiseSubType"';
        $join_fieds_arr[] = 's."vName" as "vPremiseName"';

        $join_arr[] = 'LEFT JOIN contact_mas on fiberinquiry_details."iCId" = contact_mas."iCId"';
        $join_arr[] = 'LEFT JOIN county_mas c on fiberinquiry_details."iCountyId" = c."iCountyId"';
        $join_arr[] = 'LEFT JOIN state_mas sm on fiberinquiry_details."iStateId" = sm."iStateId"';
        $join_arr[] = 'LEFT JOIN city_mas cm on fiberinquiry_details."iCityId" = cm."iCityId"';
        $join_arr[] = 'LEFT JOIN zone z on fiberinquiry_details."iZoneId" = z."iZoneId"';
        $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
        $join_arr[] = 'LEFT JOIN engagement_mas on engagement_mas."iEngagementId" = fiberinquiry_details."iEngagementId"';
        $join_arr[] = 'LEFT JOIN site_sub_type_mas sst on sst."iSSTypeId" = "fiberinquiry_details"."iPremiseSubTypeId"';
        $join_arr[] = 'LEFT JOIN premise_mas s on s."iPremiseId" = "fiberinquiry_details"."iMatchingPremiseId"';

        $where_arr[] = "fiberinquiry_details.\"dAddedDate\" >= '".$LAST_15_DAYS."'"; 
        $where_arr[] = "fiberinquiry_details.\"iLoginUserId\" = '".$userid."'"; 
        $where_arr[] = "fiberinquiry_details.\"iStatus\" != 4"; //Completed
        $FiberInquiryObj->join_field = $join_fieds_arr;
        $FiberInquiryObj->join = $join_arr;
        $FiberInquiryObj->where = $where_arr;
        $FiberInquiryObj->param['order_by'] = "fiberinquiry_details.\"iFiberInquiryId\" DESC";
        $FiberInquiryObj->setClause();
        $FiberInquiryObj->debug_query = false;
        $rs_fInquiry = $FiberInquiryObj->recordset_list();
        //echo "<pre>";print_r($rs_fInquiry);exit;
        $fi = count($rs_fInquiry);
        if($fi > 0) {
            for($i=0; $i< $fi; $i++){
                $vFStatus = '';
                if($rs_fInquiry[$i]['iStatus'] == 1){
                    $vFStatus = 'Draft';
                }else if($rs_fInquiry[$i]['iStatus'] == 2){
                    $vFStatus = 'Assigned';
                }else if($rs_fInquiry[$i]['iStatus'] == 3){
                    $vFStatus = 'Review';
                }else if($rs_fInquiry[$i]['iStatus'] == 4){
                    $vFStatus = 'Complete';
                }
                $vIcon = $site_url."images/sr_green.png";
                $rs_fInquiry[$i]['vAddress'] = $rs_fInquiry[$i]['vAddress1'].' '.$rs_fInquiry[$i]['vStreet'].' '.$rs_fInquiry[$i]['vCity'].' '.$rs_fInquiry[$i]['vState'];
                $rs_fInquiry[$i]['vFStatus'] = $vFStatus;
                $rs_fInquiry[$i]['vIcon'] = $vIcon;
            }
            $site_arr['FiberInquiry'][] = $rs_fInquiry;
        }
        // ************ Fiber Inquiry ************ //

        // ************ Service Order ************ //
        $ServiceOrderObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

        $join_fieds_arr[] = 's."vName" as "vPremiseName"';
        $join_fieds_arr[] = 's."vAddress1"';
        $join_fieds_arr[] = 's."vLatitude"';
        $join_fieds_arr[] = 's."vLongitude"';
        $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
        $join_fieds_arr[] = 'cm."vCompanyName"';
        $join_fieds_arr[] = 'z."vZoneName"';
        $join_fieds_arr[] = 'n."vName" as "vNetwork"';
        $join_fieds_arr[] = 'ct."vConnectionTypeName"';
        $join_fieds_arr[] = 'st1."vServiceType" as "vServiceType1"';
        $join_fieds_arr[] = 'c."vCounty"';
        $join_fieds_arr[] = 'sm."vState"';
        $join_fieds_arr[] = 'cm1."vCity"';
        
        $join_arr[] = 'LEFT JOIN premise_mas s on service_order."iPremiseId" = s."iPremiseId"';
        $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
        $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
        $join_arr[] = 'LEFT JOIN city_mas cm1 on s."iCityId" = cm1."iCityId"';
        $join_arr[] = 'LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode"';
        $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
        $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
        $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
        $join_arr[] = 'LEFT JOIN company_mas cm on service_order."iCarrierID" = cm."iCompanyId"';
        $join_arr[] = 'LEFT JOIN connection_type_mas ct on service_order."iConnectionTypeId" = ct."iConnectionTypeId"';
        $join_arr[] = 'LEFT JOIN service_type_mas st1 on service_order."iService1" = st1."iServiceTypeId"';
        
        $where_arr[] = "service_order.\"iUserCreatedBy\" = '".$userid."'"; 
        $where_arr[] = "service_order.\"iSOStatus\" = '1'"; 

        $ServiceOrderObj->join_field = $join_fieds_arr;
        $ServiceOrderObj->join = $join_arr;
        $ServiceOrderObj->where = $where_arr;
        $ServiceOrderObj->param['order_by'] = "service_order.\"iServiceOrderId\"";
        $ServiceOrderObj->setClause();
        $ServiceOrderObj->debug_query = false;
        $rs_sorder = $ServiceOrderObj->recordset_list();
        $si = count($rs_sorder);
        if($si >0){
            for($i=0; $i<$si; $i++){
                $vIcon = $site_url."images/wrench-orange.png";
                $rs_sorder[$i]['vAddress'] = $rs_sorder[$i]['vAddress1'].' '.$rs_sorder[$i]['vStreet'].' '.$rs_sorder[$i]['vCity'].' '.$rs_sorder[$i]['vState'];
                $rs_sorder[$i]['vSOStatus'] = "Created";
                $rs_sorder[$i]['vIcon'] = $vIcon;
            }
            $site_arr['Serviceorder'][] = $rs_sorder;
        }
        // ************ Service Order ************ //
    } else if($TECHNICIAN_ACCESS_TYPE_ID == $iAccessType) {
        // ************ Workorder ************ //
        $WorkOrderObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

        $join_fieds_arr[] = 's."vName" as "vPremiseName"';
        $join_fieds_arr[] = 's."vAddress1"';
        $join_fieds_arr[] = 's."vStreet"';
        $join_fieds_arr[] = 's."vLatitude"';
        $join_fieds_arr[] = 's."vLongitude"';
        $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
        $join_fieds_arr[] = 'z."vZoneName"';
        $join_fieds_arr[] = 'n."vName" as "vNetwork"';
        $join_fieds_arr[] = 'so."vMasterMSA"';
        $join_fieds_arr[] = 'so."vServiceOrder"';
        $join_fieds_arr[] = 'ws."vStatus"';
        $join_fieds_arr[] = 'wt."vType"';
        $join_fieds_arr[] = 'c."vCity"';
        $join_fieds_arr[] = 'sm."vState"';
        $join_fieds_arr[] = "concat(u.\"vFirstName\", ' ', u.\"vLastName\") as \"vRequestor\"";
        $join_fieds_arr[] = "concat(u1.\"vFirstName\", ' ', u1.\"vLastName\") as \"vAssignedTo\"";
        $join_arr[] = 'LEFT JOIN premise_mas s on workorder."iPremiseId" = s."iPremiseId"';
        $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
        $join_arr[] = 'LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode"';
        $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
        $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
        $join_arr[] = 'LEFT JOIN city_mas c on s."iCityId" = c."iCityId"';
        $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
        $join_arr[] = 'LEFT JOIN service_order so on workorder."iServiceOrderId" = so."iServiceOrderId"';
        $join_arr[] = 'LEFT JOIN user_mas u on workorder."iRequestorId" = u."iUserId"';
        $join_arr[] = 'LEFT JOIN user_mas u1 on workorder."iAssignedToId" = u1."iUserId"';
        $join_arr[] = 'LEFT JOIN workorder_status_mas ws on workorder."iWOSId" = ws."iWOSId"';
        $join_arr[] = 'LEFT JOIN workorder_type_mas wt on workorder."iWOTId" = wt."iWOTId"';

        $where_arr[] = "workorder.\"iAssignedToId\" = '".$userid."'"; 
        $where_arr[] = "workorder.\"iWOSId\" != 2"; // Closed 

        $WorkOrderObj->join_field = $join_fieds_arr;
        $WorkOrderObj->join = $join_arr;
        $WorkOrderObj->where = $where_arr;
        $WorkOrderObj->param['order_by'] = "workorder.\"iWOId\" DESC";
        $WorkOrderObj->setClause();
        $WorkOrderObj->debug_query = false;
        $rs_worder = $WorkOrderObj->recordset_list();
        //echo "<pre>"; print_r($rs_worder);exit;
        $wi = count($rs_worder);
        if($wi > 0){
            for($i=0; $i<$wi; $i++){
                $vIcon = $site_url."images/user-wrench-red.png";
                $rs_worder[$i]['vAddress'] = $rs_worder[$i]['vAddress1'].' '.$rs_worder[$i]['vStreet'].' '.$rs_worder[$i]['vCity'].' '.$rs_worder[$i]['vState'];
                $rs_worder[$i]['vIcon'] = $vIcon;
            }
            $site_arr['Workorder'][] = $rs_worder;
        }
        // ************ Workorder ************ //

        // ************ Trouble Ticket ************ //
        $TroubleTicketObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

        $join_fieds_arr[] = 'so."vMasterMSA"';
        $join_fieds_arr[] = 'so."vServiceOrder"';
        
        $join_arr[] = 'LEFT JOIN service_order so on so."iServiceOrderId" = trouble_ticket."iServiceOrderId"';
        $join_arr[] = 'LEFT JOIN company_mas cm on cm."iCompanyId" = so."iCarrierID"';

        $where_arr[] = "trouble_ticket.\"iAssignedToId\" = '".$userid."'"; 
        $where_arr[] = "trouble_ticket.\"iStatus\" != 3"; // Completed 

        $TroubleTicketObj->join_field = $join_fieds_arr;
        $TroubleTicketObj->join = $join_arr;
        $TroubleTicketObj->where = $where_arr;
        $TroubleTicketObj->param['order_by'] = "trouble_ticket.\"iTroubleTicketId\" DESC";
        $TroubleTicketObj->param['limit'] = $limit;
        $TroubleTicketObj->setClause();
        $TroubleTicketObj->debug_query = false;
        $rs_trouble_ticket = $TroubleTicketObj->recordset_list();
        $ti = count($rs_trouble_ticket);
        //echo "<pre>";print_r($rs_trouble_ticket);exit;
        $trouble_ticket_arr = [];
        if($ti > 0) {
            for($i=0; $i<$ti; $i++){
                $iTroubleTicketId = $rs_trouble_ticket[$i]['iTroubleTicketId'];
                $iSeverity = '---';
                if($rs_trouble_ticket[$i]['iSeverity'] == 1){
                   $iSeverity = "Low"; 
                }else if($rs_trouble_ticket[$i]['iSeverity'] == 2){
                   $iSeverity = "Medium"; 
                }else if($rs_trouble_ticket[$i]['iSeverity'] == 3){
                   $iSeverity = "High"; 
                }else if($rs_trouble_ticket[$i]['iSeverity'] == 4){
                   $iSeverity = "Critical"; 
                }

                $iStatus = '---';
                if($rs_trouble_ticket[$i]['iStatus'] == 1){
                   $iStatus = "Not Started"; 
                }else if($rs_trouble_ticket[$i]['iStatus'] == 2){
                   $iStatus = "In Progress"; 
                }else if($rs_trouble_ticket[$i]['iStatus'] == 3){
                   $iStatus = "Completed"; 
                }

                $vServiceDetails = '';
                if($rs_trouble_ticket[$i]['iServiceOrderId'] != ""){
                    $vServiceDetails .= $rs_trouble_ticket[$i]['vMasterMSA']." | ".$rs_trouble_ticket[$i]['vServiceOrder'];
                }

                $TroubleTicketObj->clear_variable();
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $join_fieds_arr[] = 's."vName" as "vPremiseName"';
                $join_fieds_arr[] = 's."vAddress1"';
                $join_fieds_arr[] = 's."vStreet"';
                $join_fieds_arr[] = 's."vLatitude"';
                $join_fieds_arr[] = 's."vLongitude"';
                $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
                $join_fieds_arr[] = 'z."vZoneName"';
                $join_fieds_arr[] = 'n."vName" as "vNetwork"';
                $join_fieds_arr[] = 'c."vCity"';
                $join_fieds_arr[] = 'sm."vState"';
           

                $join_arr[] = 'LEFT JOIN premise_mas s on trouble_ticket_premise."iPremiseId" = s."iPremiseId"';
                $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
                $join_arr[] = 'LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode"';
                $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
                $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
                $join_arr[] = 'LEFT JOIN city_mas c on s."iCityId" = c."iCityId"';
                $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';

                $where_arr[] = "trouble_ticket_premise.\"iTroubleTicketId\" = '".$iTroubleTicketId."'"; 

                $TroubleTicketObj->join_field = $join_fieds_arr;
                $TroubleTicketObj->join = $join_arr;
                $TroubleTicketObj->where = $where_arr;
                $TroubleTicketObj->param['order_by'] = "trouble_ticket_premise.\"iPremiseId\" DESC";
                $TroubleTicketObj->param['limit'] = $limit;
                $TroubleTicketObj->setClause();
                $TroubleTicketObj->debug_query = false;
                $rs_tt_premise = $TroubleTicketObj->trouble_ticket_premise_recordset_list();
                //echo "<pre>";print_r($rs_tt_premise);exit;
                $tti = count($rs_tt_premise);
                if($tti > 0){
                    for($t=0; $t<$tti; $t++){
                        $vIcon = $site_url."images/blue_icon.png";
                        $trouble_ticket_arr[$t]['iTroubleTicketId'] = $iTroubleTicketId;
                        $trouble_ticket_arr[$t]['iSeverity'] = $iSeverity;
                        $trouble_ticket_arr[$t]['iStatus'] = $iStatus;
                        $trouble_ticket_arr[$t]['vServiceOrder'] = $vServiceDetails;
                        $trouble_ticket_arr[$t]['iPremiseId'] = $rs_tt_premise[$t]['iPremiseId'];
                        $trouble_ticket_arr[$t]['vPremiseName'] = $rs_tt_premise[$t]['vPremiseName'];
                        $trouble_ticket_arr[$t]['vPremiseType'] = $rs_tt_premise[$t]['vPremiseType'];
                        $trouble_ticket_arr[$t]['vLatitude'] = $rs_tt_premise[$t]['vLatitude'];
                        $trouble_ticket_arr[$t]['vLongitude'] = $rs_tt_premise[$t]['vLongitude'];
                        $trouble_ticket_arr[$t]['dTroubleStartDate'] = date_display_report_date($rs_tt_premise[$t]['dTroubleStartDate']);

                        $trouble_ticket_arr[$t]['vAddress'] = $rs_tt_premise[$t]['vAddress1'].' '.$rs_tt_premise[$t]['vStreet'].' '.$rs_tt_premise[$t]['vCity'].' '.$rs_tt_premise[$t]['vState'];
                        $trouble_ticket_arr[$t]['vIcon'] = $vIcon;
                    }
                }
            }
            $site_arr['TroubleTicket'][] = $trouble_ticket_arr;
        }
        // ************ Trouble Ticket ************ //

        // ************ Maintenance Ticket ************ //
        $MaintenanceTicketObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

        $join_fieds_arr[] = 'so."vMasterMSA"';
        $join_fieds_arr[] = 'so."vServiceOrder"';
        
        $join_arr[] = 'LEFT JOIN service_order so on so."iServiceOrderId" = maintenance_ticket."iServiceOrderId"';
        $join_arr[] = 'LEFT JOIN company_mas cm on cm."iCompanyId" = so."iCarrierID"';

        $where_arr[] = "maintenance_ticket.\"iAssignedToId\" = '".$userid."'"; 
        $where_arr[] = "maintenance_ticket.\"iStatus\" != 3"; // Completed 

        $MaintenanceTicketObj->join_field = $join_fieds_arr;
        $MaintenanceTicketObj->join = $join_arr;
        $MaintenanceTicketObj->where = $where_arr;
        $MaintenanceTicketObj->param['order_by'] = "maintenance_ticket.\"iMaintenanceTicketId\" DESC";
        $MaintenanceTicketObj->param['limit'] = $limit;
        $MaintenanceTicketObj->setClause();
        $MaintenanceTicketObj->debug_query = false;
        $rs_maintenance_ticket = $MaintenanceTicketObj->recordset_list();
        $ti = count($rs_maintenance_ticket);
        //echo "<pre>";print_r($rs_maintenance_ticket);exit;
        $maintenance_ticket_arr = [];
        if($ti > 0) {
            for($i=0; $i<$ti; $i++){
                $iMaintenanceTicketId = $rs_maintenance_ticket[$i]['iMaintenanceTicketId'];
                $iSeverity = '---';
                if($rs_maintenance_ticket[$i]['iSeverity'] == 1){
                   $iSeverity = "Low"; 
                }else if($rs_maintenance_ticket[$i]['iSeverity'] == 2){
                   $iSeverity = "Medium"; 
                }else if($rs_maintenance_ticket[$i]['iSeverity'] == 3){
                   $iSeverity = "High"; 
                }else if($rs_maintenance_ticket[$i]['iSeverity'] == 4){
                   $iSeverity = "Critical"; 
                }

                $iStatus = '---';
                if($rs_maintenance_ticket[$i]['iStatus'] == 1){
                   $iStatus = "Not Started"; 
                }else if($rs_maintenance_ticket[$i]['iStatus'] == 2){
                   $iStatus = "In Progress"; 
                }else if($rs_maintenance_ticket[$i]['iStatus'] == 3){
                   $iStatus = "Completed"; 
                }

                $vServiceDetails = '';
                if($rs_maintenance_ticket[$i]['iServiceOrderId'] != ""){
                    $vServiceDetails .= $rs_maintenance_ticket[$i]['vMasterMSA']." | ".$rs_maintenance_ticket[$i]['vServiceOrder'];
                }

                $MaintenanceTicketObj->clear_variable();
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $join_fieds_arr[] = 's."vName" as "vPremiseName"';
                $join_fieds_arr[] = 's."vAddress1"';
                $join_fieds_arr[] = 's."vStreet"';
                $join_fieds_arr[] = 's."vLatitude"';
                $join_fieds_arr[] = 's."vLongitude"';
                $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
                $join_fieds_arr[] = 'z."vZoneName"';
                $join_fieds_arr[] = 'n."vName" as "vNetwork"';
                $join_fieds_arr[] = 'c."vCity"';
                $join_fieds_arr[] = 'sm."vState"';
           

                $join_arr[] = 'LEFT JOIN premise_mas s on maintenance_ticket_premise."iPremiseId" = s."iPremiseId"';
                $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
                $join_arr[] = 'LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode"';
                $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
                $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
                $join_arr[] = 'LEFT JOIN city_mas c on s."iCityId" = c."iCityId"';
                $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';

                $where_arr[] = "maintenance_ticket_premise.\"iMaintenanceTicketId\" = '".$iMaintenanceTicketId."'"; 

                $MaintenanceTicketObj->join_field = $join_fieds_arr;
                $MaintenanceTicketObj->join = $join_arr;
                $MaintenanceTicketObj->where = $where_arr;
                $MaintenanceTicketObj->param['order_by'] = "maintenance_ticket_premise.\"iPremiseId\" DESC";
                $MaintenanceTicketObj->param['limit'] = $limit;
                $MaintenanceTicketObj->setClause();
                $MaintenanceTicketObj->debug_query = false;
                $rs_tt_premise = $MaintenanceTicketObj->maintenance_ticket_premise_recordset_list();
                //echo "<pre>";print_r($rs_tt_premise);exit;
                $tti = count($rs_tt_premise);
                if($tti > 0){
                    for($t=0; $t<$tti; $t++){
                        $vIcon = $site_url."images/gray_icon.png";
                        $maintenance_ticket_arr[$t]['iMaintenanceTicketId'] = $iMaintenanceTicketId;
                        $maintenance_ticket_arr[$t]['iSeverity'] = $iSeverity;
                        $maintenance_ticket_arr[$t]['iStatus'] = $iStatus;
                        $maintenance_ticket_arr[$t]['vServiceOrder'] = $vServiceDetails;
                        $maintenance_ticket_arr[$t]['iPremiseId'] = $rs_tt_premise[$t]['iPremiseId'];
                        $maintenance_ticket_arr[$t]['vPremiseName'] = $rs_tt_premise[$t]['vPremiseName'];
                        $maintenance_ticket_arr[$t]['vPremiseType'] = $rs_tt_premise[$t]['vPremiseType'];
                        $maintenance_ticket_arr[$t]['vLatitude'] = $rs_tt_premise[$t]['vLatitude'];
                        $maintenance_ticket_arr[$t]['vLongitude'] = $rs_tt_premise[$t]['vLongitude'];
                        $maintenance_ticket_arr[$t]['dMaintenanceStartDate'] = date_display_report_date($rs_tt_premise[$t]['dMaintenanceStartDate']);

                        $maintenance_ticket_arr[$t]['vAddress'] = $rs_tt_premise[$t]['vAddress1'].' '.$rs_tt_premise[$t]['vStreet'].' '.$rs_tt_premise[$t]['vCity'].' '.$rs_tt_premise[$t]['vState'];
                        $maintenance_ticket_arr[$t]['vIcon'] = $vIcon;
                    }
                }
            }
            $site_arr['MaintenanceTicket'][] = $maintenance_ticket_arr;
        }
        // ************ Maintenance Ticket ************ //
    } else if($CARRIER_ACCESS_TYPE_ID == $iAccessType) {
        // ************ Service Order ************ //
        $ServiceOrderObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

        $join_fieds_arr[] = 's."vName" as "vPremiseName"';
        $join_fieds_arr[] = 's."vAddress1"';
        $join_fieds_arr[] = 's."vLatitude"';
        $join_fieds_arr[] = 's."vLongitude"';
        $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
        $join_fieds_arr[] = 'cm."vCompanyName"';
        $join_fieds_arr[] = 'z."vZoneName"';
        $join_fieds_arr[] = 'n."vName" as "vNetwork"';
        $join_fieds_arr[] = 'ct."vConnectionTypeName"';
        $join_fieds_arr[] = 'st1."vServiceType" as "vServiceType1"';
        $join_fieds_arr[] = 'c."vCounty"';
        $join_fieds_arr[] = 'sm."vState"';
        $join_fieds_arr[] = 'cm1."vCity"';
        
        $join_arr[] = 'LEFT JOIN premise_mas s on service_order."iPremiseId" = s."iPremiseId"';
        $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
        $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
        $join_arr[] = 'LEFT JOIN city_mas cm1 on s."iCityId" = cm1."iCityId"';
        $join_arr[] = 'LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode"';
        $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
        $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
        $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
        $join_arr[] = 'LEFT JOIN company_mas cm on service_order."iCarrierID" = cm."iCompanyId"';
        $join_arr[] = 'LEFT JOIN connection_type_mas ct on service_order."iConnectionTypeId" = ct."iConnectionTypeId"';
        $join_arr[] = 'LEFT JOIN service_type_mas st1 on service_order."iService1" = st1."iServiceTypeId"';
        
        $where_arr[] = "service_order.\"iUserCreatedBy\" = '".$userid."'"; 
        $where_arr[] = "service_order.\"iSOStatus\" = '1'"; 

        $ServiceOrderObj->join_field = $join_fieds_arr;
        $ServiceOrderObj->join = $join_arr;
        $ServiceOrderObj->where = $where_arr;
        $ServiceOrderObj->param['order_by'] = "service_order.\"iServiceOrderId\"";
        $ServiceOrderObj->setClause();
        $ServiceOrderObj->debug_query = false;
        $rs_sorder = $ServiceOrderObj->recordset_list();
        $si = count($rs_sorder);
        if($si >0){
            for($i=0; $i<$si; $i++){
                $vIcon = $site_url."images/wrench-orange.png";
                $rs_sorder[$i]['vAddress'] = $rs_sorder[$i]['vAddress1'].' '.$rs_sorder[$i]['vStreet'].' '.$rs_sorder[$i]['vCity'].' '.$rs_sorder[$i]['vState'];
                $rs_sorder[$i]['vSOStatus'] = "Created";
                $rs_sorder[$i]['vIcon'] = $vIcon;
            }
            $site_arr['Serviceorder'][] = $rs_sorder;
        }
        // ************ Service Order ************ //
    }
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => array('site' => $site_arr));
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>