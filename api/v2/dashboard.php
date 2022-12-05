<?php
include_once ($controller_path . "premise.inc.php");

include_once ($controller_path . "trouble_ticket.inc.php");
include_once ($controller_path . "maintenance_ticket.inc.php");
include_once ($controller_path . "service_order.inc.php");
include_once ($controller_path . "workorder.inc.php");
include_once ($controller_path . "fiber_inquiry.inc.php");
include_once ($controller_path . "event.inc.php");


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
} else if ($request_type == "dashboard_timelinechart") {
    $SiteObj = new Site();

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->setClause();
    $rs = $SiteObj->site_dashboard_history();

    $data = array();
    $ni = count($rs);
    if ($ni > 0) {
        $ind = 0;
        foreach ($rs as $key => $val) {

            if ($val['Type'] == "Treatment" || $val['Type'] == "Landing Rate" || $val['Type'] == "Laravel Surveillance" || $val['Type'] == "Other") {
                $Type = $val['Type'];

                $start_d = date('d', strtotime($val['dStartDate']));
                $start_m = date('m', strtotime($val['dStartDate']));
                $start_y = date('Y', strtotime($val['dStartDate']));

                $start_h = date('H', strtotime($val['dStartDate']));
                $start_i = date('i', strtotime($val['dStartDate']));
                $start_s = date('s', strtotime($val['dStartDate']));

                $end_d = date('d', strtotime($val['dEndDate']));
                $end_m = date('m', strtotime($val['dEndDate']));
                $end_y = date('Y', strtotime($val['dEndDate']));

                $end_h = date('H', strtotime($val['dEndDate']));
                $end_i = date('i', strtotime($val['dEndDate']));
                $end_s = date('s', strtotime($val['dEndDate']));

                //$start_h = ($start_h >=0 && $start_h <=9)?ltrim($start_h,0):$start_h;
                $start_h = ($start_h >= 0 && $start_h <= 9) ? substr($start_h, 1) : $start_h;
                $start_i = ($start_i >= 0 && $start_i <= 9) ? substr($start_i, 1) : $start_i;
                $start_s = ($start_s >= 0 && $start_s <= 9) ? substr($start_s, 1) : $start_s;

                $end_h = ($end_h >= 0 && $end_h <= 9) ? substr($end_h, 1) : $end_h;
                $end_i = ($end_i >= 0 && $end_i <= 9) ? substr($end_i, 1) : $end_i;
                $end_s = ($end_s >= 0 && $end_s <= 9) ? substr($end_s, 1) : $end_s;
            }
            else if ($val['Type'] == "Task Trap Placed")
            {
                $Type = "Trap Placed";

                $start_d = date('d', strtotime($val['dAddedDate']));
                $start_m = date('m', strtotime($val['dAddedDate']));
                $start_y = date('Y', strtotime($val['dAddedDate']));

                $start_h = date('H', strtotime($val['dAddedDate']));
                $start_i = date('i', strtotime($val['dAddedDate']));
                $start_s = date('s', strtotime($val['dAddedDate']));

                //end time 15 min to Adde Date Time
                $endTime = date_addDateTime($val['dAddedDate'], $da=0, $ma=0, $ya=0, $ha=0, $ia=15, $sa=0);

                $end_d = date('d', strtotime($endTime));
                $end_m = date('m', strtotime($endTime));
                $end_y = date('Y', strtotime($endTime));

                $end_h = date('H', strtotime($endTime));
                $end_i = date('i', strtotime($endTime));
                $end_s = date('s', strtotime($endTime));

                //$start_h = ($start_h >=0 && $start_h <=9)?ltrim($start_h,0):$start_h;
                $start_h = ($start_h >= 0 && $start_h <= 9) ? substr($start_h, 1) : $start_h;
                $start_i = ($start_i >= 0 && $start_i <= 9) ? substr($start_i, 1) : $start_i;
                $start_s = ($start_s >= 0 && $start_s <= 9) ? substr($start_s, 1) : $start_s;

                $end_h = ($end_h >= 0 && $end_h <= 9) ? substr($end_h, 1) : $end_h;
                $end_i = ($end_i >= 0 && $end_i <= 9) ? substr($end_i, 1) : $end_i;
                $end_s = ($end_s >= 0 && $end_s <= 9) ? substr($end_s, 1) : $end_s;

            }
            else if ($val['Type'] == "Task Trap Colected")
            {
                $Type = "Trap Collected";

                $start_d = date('d', strtotime($val['dModifiedDate']));
                $start_m = date('m', strtotime($val['dModifiedDate']));
                $start_y = date('Y', strtotime($val['dModifiedDate']));

                $start_h = date('H', strtotime($val['dModifiedDate']));
                $start_i = date('i', strtotime($val['dModifiedDate']));
                $start_s = date('s', strtotime($val['dModifiedDate']));

                //end time 15 min to Modified Date Time
                $endTime = date_addDateTime($val['dModifiedDate'], $da=0, $ma=0, $ya=0, $ha=0, $ia=15, $sa=0);

                $end_d = date('d', strtotime($endTime));
                $end_m = date('m', strtotime($endTime));
                $end_y = date('Y', strtotime($endTime));

                $end_h = date('H', strtotime($endTime));
                $end_i = date('i', strtotime($endTime));
                $end_s = date('s', strtotime($endTime));

                //$start_h = ($start_h >=0 && $start_h <=9)?ltrim($start_h,0):$start_h;
                $start_h = ($start_h >= 0 && $start_h <= 9) ? substr($start_h, 1) : $start_h;
                $start_i = ($start_i >= 0 && $start_i <= 9) ? substr($start_i, 1) : $start_i;
                $start_s = ($start_s >= 0 && $start_s <= 9) ? substr($start_s, 1) : $start_s;

                $end_h = ($end_h >= 0 && $end_h <= 9) ? substr($end_h, 1) : $end_h;
                $end_i = ($end_i >= 0 && $end_i <= 9) ? substr($end_i, 1) : $end_i;
                $end_s = ($end_s >= 0 && $end_s <= 9) ? substr($end_s, 1) : $end_s;
            }

            //echo $val['Type'];
            $site_details = 'Premise ' . $val['iSiteId'] . ($val['vSiteName'] ? ' (' . $val['vSiteName'] . ') ' : '');
            if ($val['iSRId'] > 0)
            {
                $site_details .= "<br/>SR " . $val['iSRId'] . ($val['vContactName'] ? " (" . $val['vContactName'] . ")" : '');
            }

            $data[] = array(
                'Technician' => $val['UserName'],
                'Operation' => $Type,
                'tooltip' => $site_details,
                'start_d' => $start_d,
                'start_m' => $start_m,
                'start_y' => $start_y,
                'statrh' => $start_h,
                'satrti' => $start_i,
                'starts' => $start_s,
                'end_d' => $end_d,
                'end_m' => $end_m,
                'end_y' => $end_y,
                'endh' => $end_h,
                'endi' => $end_i,
                'ends' => $end_s
            );
        }
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => $message, "result" => $data);
    }
    else
    {

        $rh = HTTPStatus(400);
        $code = 2104;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 400, "Message" => $message, "result" => $data);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>