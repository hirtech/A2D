<?php
//error_reporting(E_ALL); ini_set('display_errors', 1);

include_once ($controller_path . "trouble_ticket.inc.php");
include_once ($controller_path . "maintenance_ticket.inc.php");
include_once ($controller_path . "service_order.inc.php");
include_once ($controller_path . "workorder.inc.php");
include_once ($controller_path . "fiber_inquiry.inc.php");
include_once ($controller_path . "event.inc.php");
include_once ($controller_path . "service_type.inc.php");
// echo $request_type;exit();

if ($request_type == "dashboard_glance") {
    $currentdate = date("Y-m-d");
    $yesterdate = date("Y-m-d", strtotime("-1 days"));

    $currentweek_startdate = date("Y-m-d", strtotime("-6 days"));
    $lastweek_startdate = date("Y-m-d", strtotime("-7 days"));
    $lastweek_enddate = date("Y-m-d", strtotime("-13 days"));
    
    $TroubleTicketObj = new TroubleTicket();
    $MaintenanceTicketObj = new MaintenanceTicket();
    $ServiceOrderObj = new ServiceOrder();
    $WorkorderObj = new Workorder();
    $FiberInquiryObj = new FiberInquiry();
    $EventObj = new Event();

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
    $rs_db_day = $WorkorderObj->recordset_glance_data($day_where1, $day_where2);
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
    $rs_db_month = $WorkorderObj->recordset_glance_data($month_where1, $month_where2);
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
    $rs_db_week = $WorkorderObj->recordset_glance_data($week_where1, $week_where2);
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
    $rs_db_year = $WorkorderObj->recordset_glance_data($year_where1, $year_where2);
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
    $ServiceTypeObj = new ServiceType();
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
} 
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>