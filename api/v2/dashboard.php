<?php
include_once ($controller_path . "premise.inc.php");
include_once ($controller_path . "sr.inc.php");
include_once ($controller_path . "task_larval_surveillance.inc.php");
include_once ($controller_path . "task_treatment.inc.php");
include_once ($controller_path . "task_trap.inc.php");
include_once ($controller_path . "task_mosquito_pool.inc.php");
include_once ($controller_path . "task_mosquito_pool_result.inc.php");

if ($request_type == "dashboard_glance")
{
    $currentdate = date("Y-m-d");
    $yesterdate = date("Y-m-d", strtotime("-1 days"));

    $currentweek_startdate = date("Y-m-d", strtotime("-6 days"));
    $lastweek_startdate = date("Y-m-d", strtotime("-7 days"));
    $lastweek_enddate = date("Y-m-d", strtotime("-13 days"));
    
    $SRObj = new SR();
    $LarvalSurObj = new TaskLarvalSurveillance();
    $TreatmentObj = new TaskTreatment();
    $TrapObj = new TaskTrap();
    $MosquitoPoolObj = new TaskMosquitoPool();
    $MosquitoPoolResultObj = new TaskMosquitoPoolResult();

    $day_glanc_arr = array();
    $week_glanc_arr = array();
    $month_glanc_arr = array();
    $week_glanc_arr = array();
    $year_glanc_arr = array();

    $result = array();

    /*======DAy Glance====================*/
    //today
    $day_where1 = '"dAddedDate"::date = \'' . $currentdate . '\'';
    //yester day
    $day_where2 = '"dAddedDate"::date = \'' . $yesterdate . '\'';

    //SR Count Differnce of current day and yester day
    $rs_db_day = $SRObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['srcount1'] - $rs_db_day[0]['srcount2']) / ($rs_db_day[0]['srcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['srcount1'] >= $rs_db_day[0]['srcount2'])
    {
        $sr_diff_rat = ' <span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $sr_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['public_request']['today'] = (isset($rs_db_day[0]['srcount1'])) ? $rs_db_day[0]['srcount1'] : '0';
    $day_glanc_arr['public_request']['yesterday'] = (isset($rs_db_day[0]['srcount2'])) ? $rs_db_day[0]['srcount2'] : '0';
    $day_glanc_arr['public_request']['diff_ratio'] = $sr_diff_rat;

    //Larval Count  Differnce of current day and yester day
    $rs_db_day = $LarvalSurObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['larcount1'] - $rs_db_day[0]['larcount2']) / ($rs_db_day[0]['larcount2'] *100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['larcount1'] >= $rs_db_day[0]['larcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['larval_samples']['today'] = (isset($rs_db_day[0]['larcount1'])) ? $rs_db_day[0]['larcount1'] : '0';
    $day_glanc_arr['larval_samples']['yesterday'] = (isset($rs_db_day[0]['larcount2'])) ? $rs_db_day[0]['larcount2'] : '0';
    $day_glanc_arr['larval_samples']['diff_ratio'] = $diff_rat;

    //Treatment Count Differnce of current day and yester day
    $rs_db_day = $TreatmentObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['treatmentcount1'] - $rs_db_day[0]['treatmentcount2']) / ($rs_db_day[0]['treatmentcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['treatmentcount1'] >= $rs_db_day[0]['treatmentcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['treatment']['today'] = (isset($rs_db_day[0]['treatmentcount1'])) ? $rs_db_day[0]['treatmentcount1'] : '0';
    $day_glanc_arr['treatment']['yesterday'] = (isset($rs_db_day[0]['treatmentcount2'])) ? $rs_db_day[0]['treatmentcount2'] : '0';
    $day_glanc_arr['treatment']['diff_ratio'] = $diff_rat;

    //Trap Count Differnce of current day and yester day
    $rs_db_day = $TrapObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['trapcount1'] - $rs_db_day[0]['trapcount2']) / ($rs_db_day[0]['trapcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['trapcount1'] >= $rs_db_day[0]['trapcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['trap_collect']['today'] = (isset($rs_db_day[0]['trapcount1'])) ? $rs_db_day[0]['trapcount1'] : '0';
    $day_glanc_arr['trap_collect']['yesterday'] = (isset($rs_db_day[0]['trapcount2'])) ? $rs_db_day[0]['trapcount2'] : '0';
    $day_glanc_arr['trap_collect']['diff_ratio'] = $diff_rat;

    //MosquitoPool Count  Differnce of current day and yester day
    $rs_db_day = $MosquitoPoolObj->recordset_glance_data($day_where1, $day_where2);

    $diff_ratio = ($rs_db_day[0]['mospoolcount1'] - $rs_db_day[0]['mospoolcount2']) / ($rs_db_day[0]['mospoolcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['mospoolcount1'] >= $rs_db_day[0]['mospoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['mosq_pool']['today'] = (isset($rs_db_day[0]['mospoolcount1'])) ? $rs_db_day[0]['mospoolcount1'] : '0';
    $day_glanc_arr['mosq_pool']['yesterday'] = (isset($rs_db_day[0]['mospoolcount2'])) ? $rs_db_day[0]['mospoolcount2'] : '0';
    $day_glanc_arr['mosq_pool']['diff_ratio'] = $diff_rat;

    //Positive Pool Result Count Differnce of current day and yester day
    $rs_db_day = $MosquitoPoolResultObj->recordset_glance_data($day_where1, $day_where2);
    $diff_ratio = ($rs_db_day[0]['postivepoolcount1'] - $rs_db_day[0]['postivepoolcount2']) / ($rs_db_day[0]['postivepoolcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check today >= yesterday
    if ($rs_db_day[0]['postivepoolcount1'] >= $rs_db_day[0]['postivepoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $day_glanc_arr['postive_pool']['today'] = (isset($rs_db_day[0]['postivepoolcount1'])) ? $rs_db_day[0]['postivepoolcount1'] : '0';
    $day_glanc_arr['postive_pool']['yesterday'] = (isset($rs_db_day[0]['postivepoolcount2'])) ? $rs_db_day[0]['postivepoolcount2'] : '0';
    $day_glanc_arr['postive_pool']['diff_ratio'] = $diff_rat;

    /*========================================Month Glance======================================*/
    //current month
    $month_where1 = 'date_trunc(\'month\', "dAddedDate")  = date_trunc(\'month\',  (\'' . $currentdate . '\')::date)';
    //previous month (last month)
    $month_where2 = 'date_trunc(\'month\', "dAddedDate") = date_trunc(\'month\', (\'' . $currentdate . '\')::date - interval \'1 month\') ';

    //SR Count Differnce of current month and last month
    $rs_db_month = $SRObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['srcount1'] - $rs_db_month[0]['srcount2']) / ($rs_db_month[0]['srcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['srcount1'] >= $rs_db_month[0]['srcount2'])
    {
        $sr_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $sr_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['public_request']['curr_month'] = (isset($rs_db_month[0]['srcount1'])) ? $rs_db_month[0]['srcount1'] : '0';
    $month_glanc_arr['public_request']['last_month'] = (isset($rs_db_month[0]['srcount2'])) ? $rs_db_month[0]['srcount2'] : '0';
    $month_glanc_arr['public_request']['diff_ratio'] = $sr_diff_rat;

    //Larval Count Differnce of current month and previous month
    $rs_db_month = $LarvalSurObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['larcount1'] - $rs_db_month[0]['larcount2']) / ($rs_db_month[0]['larcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['larcount1'] >= $rs_db_month[0]['larcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['larval_samples']['curr_month'] = (isset($rs_db_month[0]['larcount1'])) ? $rs_db_month[0]['larcount1'] : '0';
    $month_glanc_arr['larval_samples']['last_month'] = (isset($rs_db_month[0]['larcount2'])) ? $rs_db_month[0]['larcount2'] : '0';
    $month_glanc_arr['larval_samples']['diff_ratio'] = $diff_rat;

    //Treatment Count Differnceof current month and previous month
    $rs_db_month = $TreatmentObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['treatmentcount1'] - $rs_db_month[0]['treatmentcount2']) / ($rs_db_month[0]['treatmentcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['treatmentcount1'] >= $rs_db_month[0]['treatmentcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['treatment']['curr_month'] = (isset($rs_db_month[0]['treatmentcount1'])) ? $rs_db_month[0]['treatmentcount1'] : '0';
    $month_glanc_arr['treatment']['last_month'] = (isset($rs_db_month[0]['treatmentcount2'])) ? $rs_db_month[0]['treatmentcount2'] : '0';
    $month_glanc_arr['treatment']['diff_ratio'] = $diff_rat;

    //Trap Count  Differnceof current month and previous month
    $rs_db_month = $TrapObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['trapcount1'] - $rs_db_month[0]['trapcount2']) / ($rs_db_month[0]['trapcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['trapcount1'] >= $rs_db_month[0]['trapcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['trap_collect']['curr_month'] = (isset($rs_db_month[0]['trapcount1'])) ? $rs_db_month[0]['trapcount1'] : '0';
    $month_glanc_arr['trap_collect']['last_month'] = (isset($rs_db_month[0]['trapcount2'])) ? $rs_db_month[0]['trapcount2'] : '0';
    $month_glanc_arr['trap_collect']['diff_ratio'] = $diff_rat;

    //MosquitoPool Count  Differnceof current month and previous month
    $rs_db_month = $MosquitoPoolObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['mospoolcount1'] - $rs_db_month[0]['mospoolcount2']) / ($rs_db_month[0]['mospoolcount2'] *  100);
  
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;

    //check curr_month >= last_month
    if ($rs_db_month[0]['mospoolcount1'] >= $rs_db_month[0]['mospoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['mosq_pool']['curr_month'] = (isset($rs_db_month[0]['mospoolcount1'])) ? $rs_db_month[0]['mospoolcount1'] : '0';
    $month_glanc_arr['mosq_pool']['last_month'] = (isset($rs_db_month[0]['mospoolcount2'])) ? $rs_db_month[0]['mospoolcount2'] : '0';
    $month_glanc_arr['mosq_pool']['diff_ratio'] = $diff_rat;

    //Psitive Pool Result Count Differnceof current month and previous month
    $rs_db_month = $MosquitoPoolResultObj->recordset_glance_data($month_where1, $month_where2);
    $diff_ratio = ($rs_db_month[0]['postivepoolcount1'] - $rs_db_month[0]['postivepoolcount2']) / ($rs_db_month[0]['postivepoolcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_month >= last_month
    if ($rs_db_month[0]['postivepoolcount1'] >= $rs_db_month[0]['postivepoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $month_glanc_arr['postive_pool']['curr_month'] = (isset($rs_db_month[0]['postivepoolcount1'])) ? $rs_db_month[0]['postivepoolcount1'] : '0';
    $month_glanc_arr['postive_pool']['last_month'] = (isset($rs_db_month[0]['postivepoolcount2'])) ? $rs_db_month[0]['postivepoolcount2'] : '0';
    $month_glanc_arr['postive_pool']['diff_ratio'] = $diff_rat;

    /*========================================Week Glance======================================*/
    //current week
    $week_where1 = ' ("dAddedDate"::date >= \'' . $currentweek_startdate . '\' and "dAddedDate"::date <= \'' . $currentdate . '\' )';
    //last week
    $week_where2 = '( "dAddedDate"::date <= \'' . $lastweek_startdate . '\' and "dAddedDate"::date >= \'' . $lastweek_enddate . '\' )';

    //SR Count Differnce of current week and last week
    $rs_db_week = $SRObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['srcount1'] - $rs_db_week[0]['srcount2']) / ($rs_db_week[0]['srcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['srcount1'] >= $rs_db_week[0]['srcount2'])
    {
        $sr_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $sr_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['public_request']['curr_week'] = (isset($rs_db_week[0]['srcount1'])) ? $rs_db_week[0]['srcount1'] : '0';
    $week_glanc_arr['public_request']['last_week'] = (isset($rs_db_week[0]['srcount2'])) ? $rs_db_week[0]['srcount2'] : '0';
    $week_glanc_arr['public_request']['diff_ratio'] = $sr_diff_rat;

    //Larval Count Differnce of current week and previous week
    $rs_db_week = $LarvalSurObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['larcount1'] - $rs_db_week[0]['larcount2']) / ($rs_db_week[0]['larcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['larcount1'] >= $rs_db_week[0]['larcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['larval_samples']['curr_week'] = (isset($rs_db_week[0]['larcount1'])) ? $rs_db_week[0]['larcount1'] : '0';
    $week_glanc_arr['larval_samples']['last_week'] = (isset($rs_db_week[0]['larcount2'])) ? $rs_db_week[0]['larcount2'] : '0';
    $week_glanc_arr['larval_samples']['diff_ratio'] = $diff_rat;

    //Treatment Count Differnceof current week and previous week
    $rs_db_week = $TreatmentObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['treatmentcount1'] - $rs_db_week[0]['treatmentcount2']) / ($rs_db_week[0]['treatmentcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['treatmentcount1'] >= $rs_db_week[0]['treatmentcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['treatment']['curr_week'] = (isset($rs_db_week[0]['treatmentcount1'])) ? $rs_db_week[0]['treatmentcount1'] : '0';
    $week_glanc_arr['treatment']['last_week'] = (isset($rs_db_week[0]['treatmentcount2'])) ? $rs_db_week[0]['treatmentcount2'] : '0';
    $week_glanc_arr['treatment']['diff_ratio'] = $diff_rat;

    //Trap Count  Differnceof current week and previous week
    $rs_db_week = $TrapObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['trapcount1'] - $rs_db_week[0]['trapcount2']) / ($rs_db_week[0]['trapcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['trapcount1'] >= $rs_db_week[0]['trapcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['trap_collect']['curr_week'] = (isset($rs_db_week[0]['trapcount1'])) ? $rs_db_week[0]['trapcount1'] : '0';
    $week_glanc_arr['trap_collect']['last_week'] = (isset($rs_db_week[0]['trapcount2'])) ? $rs_db_week[0]['trapcount2'] : '0';
    $week_glanc_arr['trap_collect']['diff_ratio'] = $diff_rat;

    //MosquitoPool Count  Differnceof current week and previous week
    $rs_db_week = $MosquitoPoolObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['mospoolcount1'] - $rs_db_week[0]['mospoolcount2']) / ($rs_db_week[0]['mospoolcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['mospoolcount1'] >= $rs_db_week[0]['mospoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['mosq_pool']['curr_week'] = (isset($rs_db_week[0]['mospoolcount1'])) ? $rs_db_week[0]['mospoolcount1'] : '0';
    $week_glanc_arr['mosq_pool']['last_week'] = (isset($rs_db_week[0]['mospoolcount2'])) ? $rs_db_week[0]['mospoolcount2'] : '0';
    $week_glanc_arr['mosq_pool']['diff_ratio'] = $diff_rat;

    //Psitive Pool Result Count Differnceof current week and previous week
    $rs_db_week = $MosquitoPoolResultObj->recordset_glance_data($week_where1, $week_where2);
    $diff_ratio = ($rs_db_week[0]['postivepoolcount1'] - $rs_db_week[0]['postivepoolcount2']) / ($rs_db_week[0]['mospoolcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_week >= last_week
    if ($rs_db_week[0]['postivepoolcount1'] >= $rs_db_week[0]['postivepoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $week_glanc_arr['postive_pool']['curr_week'] = (isset($rs_db_week[0]['postivepoolcount1'])) ? $rs_db_week[0]['postivepoolcount1'] : '0';
    $week_glanc_arr['postive_pool']['last_week'] = (isset($rs_db_week[0]['postivepoolcount2'])) ? $rs_db_week[0]['postivepoolcount2'] : '0';
    $week_glanc_arr['postive_pool']['diff_ratio'] = $diff_rat;

    /*=========================================Year Glance====================================*/
    //current year
    $year_where1 = 'date_trunc(\'year\', "dAddedDate")  = date_trunc(\'year\',  (\'' . $currentdate . '\')::date)';
    //previous year (last_year)
    $year_where2 = 'date_trunc(\'year\', "dAddedDate") = date_trunc(\'year\', (\'' . $currentdate . '\')::date - interval \'1 year\') ';

    //SR Differnce of current year and previous year
    $rs_db_year = $SRObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['srcount1'] - $rs_db_year[0]['srcount2']) / ($rs_db_year[0]['srcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['srcount1'] >= $rs_db_year[0]['srcount2'])
    {
        $sr_diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $sr_diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['public_request']['curr_year'] = (isset($rs_db_year[0]['srcount1'])) ? $rs_db_year[0]['srcount1'] : '0';
    $year_glanc_arr['public_request']['last_year'] = (isset($rs_db_year[0]['srcount2'])) ? $rs_db_year[0]['srcount2'] : '0';
    $year_glanc_arr['public_request']['diff_ratio'] = $sr_diff_rat;

    //Larval Count Differnce  of current year and previous year
    $rs_db_year = $LarvalSurObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['larcount1'] - $rs_db_year[0]['larcount2']) / ($rs_db_year[0]['larcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['larcount1'] >= $rs_db_year[0]['larcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['larval_samples']['curr_year'] = (isset($rs_db_year[0]['larcount1'])) ? $rs_db_year[0]['larcount1'] : '0';
    $year_glanc_arr['larval_samples']['last_year'] = (isset($rs_db_year[0]['larcount2'])) ? $rs_db_year[0]['larcount2'] : '0';
    $year_glanc_arr['larval_samples']['diff_ratio'] = $diff_rat;

    //Treatment Count Differnce of current year and previous year
    $rs_db_year = $TreatmentObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['treatmentcount1'] - $rs_db_year[0]['treatmentcount2']) / ($rs_db_year[0]['treatmentcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['treatmentcount1'] >= $rs_db_year[0]['treatmentcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['treatment']['curr_year'] = (isset($rs_db_year[0]['treatmentcount1'])) ? $rs_db_year[0]['treatmentcount1'] : '0';
    $year_glanc_arr['treatment']['last_year'] = (isset($rs_db_year[0]['treatmentcount2'])) ? $rs_db_year[0]['treatmentcount2'] : '0';
    $year_glanc_arr['treatment']['diff_ratio'] = $diff_rat;

    //Trap Count  Differnce of current year and previous year
    $rs_db_year = $TrapObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['trapcount1'] - $rs_db_year[0]['trapcount2']) / ($rs_db_year[0]['trapcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['trapcount1'] >= $rs_db_year[0]['trapcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['trap_collect']['curr_year'] = (isset($rs_db_year[0]['trapcount1'])) ? $rs_db_year[0]['trapcount1'] : '0';
    $year_glanc_arr['trap_collect']['last_year'] = (isset($rs_db_year[0]['trapcount2'])) ? $rs_db_year[0]['trapcount2'] : '0';
    $year_glanc_arr['trap_collect']['diff_ratio'] = $diff_rat;

    //MosquitoPool Count  Differnce of current year and previous year
    $rs_db_year = $MosquitoPoolObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['mospoolcount1'] - $rs_db_year[0]['mospoolcount2']) / ($rs_db_year[0]['mospoolcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['mospoolcount1'] >= $rs_db_year[0]['mospoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['mosq_pool']['curr_year'] = (isset($rs_db_year[0]['mospoolcount1'])) ? $rs_db_year[0]['mospoolcount1'] : '0';
    $year_glanc_arr['mosq_pool']['last_year'] = (isset($rs_db_year[0]['mospoolcount2'])) ? $rs_db_year[0]['mospoolcount2'] : '0';
    $year_glanc_arr['mosq_pool']['diff_ratio'] = $diff_rat;

    //Psitive Pool Result Count Differnce of current year and previous year
    $rs_db_year = $MosquitoPoolResultObj->recordset_glance_data($year_where1, $year_where2);
    $diff_ratio = ($rs_db_year[0]['postivepoolcount1'] - $rs_db_year[0]['postivepoolcount2']) / ($rs_db_year[0]['postivepoolcount2'] * 100);
    $diff_ratio = (is_nan($diff_ratio) == 1 || is_infinite($diff_ratio) == 1)?"0" : $diff_ratio;
    //check curr_year >= last_year
    if ($rs_db_year[0]['postivepoolcount1'] >= $rs_db_year[0]['postivepoolcount2'])
    {
        $diff_rat = '<span class="text-success"><i class="fa fa-caret-up"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    else
    {
        $diff_rat = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' . round(abs($diff_ratio),2) . '%</span>';
    }
    $year_glanc_arr['postive_pool']['curr_year'] = (isset($rs_db_year[0]['postivepoolcount1'])) ? $rs_db_year[0]['postivepoolcount1'] : '0';
    $year_glanc_arr['postive_pool']['last_year'] = (isset($rs_db_year[0]['postivepoolcount2'])) ? $rs_db_year[0]['postivepoolcount2'] : '0';
    $year_glanc_arr['postive_pool']['diff_ratio'] = $diff_rat;

    $result['day_galance'] = $day_glanc_arr;
    $result['month_glance'] = $month_glanc_arr;
    $result['week_glance'] = $week_glanc_arr;
    $result['year_glance'] = $year_glanc_arr;


    $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result); 
} else if ($request_type == "dashboard_timelinechart")
{
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
    if ($ni > 0)
    {
        $ind = 0;
        foreach ($rs as $key => $val)
        {

            if ($val['Type'] == "Treatment" || $val['Type'] == "Landing Rate" || $val['Type'] == "Laravel Surveillance" || $val['Type'] == "Other")
            {
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