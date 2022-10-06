<?php
include_once ($controller_path . "premise.inc.php");
include_once ($controller_path . "task_treatment.inc.php");
include_once ($controller_path . "task_landing_rate.inc.php");
include_once ($controller_path . "task_trap.inc.php");
include_once ($controller_path . "task_larval_surveillance.inc.php");
include_once ($controller_path . "task_other.inc.php");
include_once ($controller_path . "fiber_inquiry.inc.php");
include_once ($controller_path . "treatment_product.inc.php");

# ------------------------------------------------------------
$SiteObj = new Site();
$TaskTreatmentObj = new TaskTreatment();
$TaskLandingRate = new TaskLandingRate();
$TaskTrap = new TaskTrap();
$TaskLarvalSurveillance = new TaskLarvalSurveillance();
$TaskOther = new TaskOther();
$FiberInquiryObj = new FiberInquiry();
$TProdObj = new TreatmentProduct();

##Search Arary
$where_arr = array();

if ($request_type == "get_premise_history")
{
    $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"";
    $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"";
    $iSiteId = isset($RES_PARA['iSiteId'])?trim($RES_PARA['iSiteId']):"";
    $page_type = isset($RES_PARA['page_type'])?trim($RES_PARA['page_type']):"";
    $sortname = 'dDate';
    $sortdir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"desc";

    $where_arr = $join_fieds_arr = $join_arr = $site_history_arr = array();


    if ($iSiteId != "")
    {
        $where_arr[] = '"iSiteId"=' . $iSiteId;
    }

    if($start != "" && $page_length != ""){
        $SiteObj->param['limit'] = " LIMIT $page_length OFFSET $start";
    }else if($page_length != ""){
        $SiteObj->param['limit'] = " LIMIT $page_length";
    }

    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->param['order_by'] = '"' . $sortname . '" ' . $dir;
    $SiteObj->setClause();
    $rs = $SiteObj->site_history_list();
    
    $total = 0;
    $entry = array();
    $ni = count($rs);
    $total_record = $ni;
    if ($ni > 0)
    {
        $arr = array();
        $sr_arr = array();
        $ind = 0;
        foreach ($rs as $key => $val)
        {

            if ($val['Type'] == "Treatment")
            {

                $iTreatmentId = $val['iTreatmentId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $where_arr[] = 'task_treatment."iTreatmentId"=' . $iTreatmentId;
                $join_fieds_arr[] = " s.\"vName\" as  \"vSiteName\" ";
                $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
                $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
                $join_fieds_arr[] = " sr_details.\"iSRId\"";
                $join_fieds_arr[] = " sr_details.\"iCId\"";
                $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
                //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                $join_fieds_arr[] = " unit_mas.\"vUnit\"";
                $join_fieds_arr[] = "unit_mas.\"iParentId\"";
                $join_fieds_arr[] = " treatment_product.\"vName\"";
                $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_treatment."iSiteId"';
                $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
                //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iSiteId" = s."iSiteId"';
                //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';

                $join_arr[] = 'LEFT JOIN sr_details on sr_details."iSRId" = task_treatment."iSRId"';
                $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = sr_details."iCId"';
                $join_arr[] = 'LEFT JOIN unit_mas on unit_mas."iUId" = task_treatment."iUId"';
                $join_arr[] = 'LEFT JOIN treatment_product on treatment_product."iTPId" = task_treatment."iTPId"';

                $TaskTreatmentObj->join_field = $join_fieds_arr;
                $TaskTreatmentObj->join = $join_arr;
                $TaskTreatmentObj->where = $where_arr;
                $TaskTreatmentObj->param['order_by'] = "task_treatment.\"dDate\" DESC";
                $TaskTreatmentObj->setClause();
                $TaskTreatmentObj->debug_query = false;
                $treatment_arr = $TaskTreatmentObj->recordset_list();
                if (!empty($treatment_arr))
                {
                    $ti = count($treatment_arr);
                    for ($t = 0;$t < $ti;$t++)
                    {

                        $SiteObj->clear_variable();
                        $where_arr = array();
                        $join_fieds_arr = array();
                        $join_arr = array();
                        $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                        $where_arr[] = "site_attribute.\"iSiteId\"='" . $treatment_arr[$t]['iSiteId'] . "'";
                        $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                        $SiteObj->join_field = $join_fieds_arr;
                        $SiteObj->join = $join_arr;
                        $SiteObj->where = $where_arr;
                        $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                        $SiteObj->setClause();
                        $rs_site_attr = $SiteObj->site_attribute_list();
                        //echo "<pre>";print_r($rs_site_attr);exit();
                        $vAttributeArr = array();
                        if (!empty($rs_site_attr))
                        {
                            $sai = count($rs_site_attr);
                            for ($sa = 0;$sa < $sai;$sa++)
                            {
                                $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                            }
                        }

                        $TProdObj->clear_variable();
                        $rs_trtproduct = array();
                        $where_arr = array();
                        $join_fieds_arr = array();
                        $join_arr = array();
                        $join_fieds_arr[] = 'unit_mas."vUnit"';
                        $where_arr[] = 'treatment_product."iTPId" = ' . $treatment_arr[$t]['iTPId'] . '';
                        $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = treatment_product."iUId"';
                        $TProdObj->join_field = $join_fieds_arr;
                        $TProdObj->join = $join_arr;
                        $TProdObj->where = $where_arr;
                        $TProdObj->param['limit'] = "LIMIT 1";
                        $TProdObj->param['order_by'] = 'treatment_product."iTPId" DESC';
                        $TProdObj->setClause();
                        $rs_trtproduct = $TProdObj->recordset_list();

                        $appRate = (isset($rs_trtproduct[0]['vAppRate'])) ? $rs_trtproduct[0]['vAppRate'] : "";
                        $minRate = (isset($rs_trtproduct[0]['vMinAppRate'])) ? "min " . $rs_trtproduct[0]['vMinAppRate'] : "";
                        $maxRate = (isset($rs_trtproduct[0]['vMaxAppRate'])) ? "- max " . $rs_trtproduct[0]['vMaxAppRate'] : "";
                        $tragetappRate = (isset($rs_trtproduct[0]['vTragetAppRate'])) ? $rs_trtproduct[0]['vTragetAppRate'] : "";
                        $unitName = (isset($rs_trtproduct[0]['vUnit'])) ? $rs_trtproduct[0]['vUnit'] : "";

                        $vAppRate = $appRate . "(" . $minRate . $maxRate . ")" . $unitName . "/" . $tragetappRate;

                        $site_details = '';
                        //$arr[$ind]['vAttribute'][] = $treatment_arr[$t]['vAttribute'];
                        $site_details .= 'Premise ' . $treatment_arr[$t]['iSiteId'] . ($treatment_arr[$t]['vSiteName'] ? ' (' . $treatment_arr[$t]['vSiteName'] . ') ' : '') . ($treatment_arr[$t]['vTypeName'] ? $treatment_arr[$t]['vTypeName'] : '') . ($treatment_arr[$t]['vSubTypeName'] ? ' (' . $treatment_arr[$t]['vSubTypeName'] . ')' : '') . (!empty($vAttributeArr) ? ' (' . implode(' | ', $vAttributeArr) . ')' : '');

                        if ($treatment_arr[$t]['iSRId'] > 0)
                        {
                            $site_details .= "<br/>SR " . $treatment_arr[$t]['iSRId'] . ($treatment_arr[$t]['vContactName'] ? " (" . $treatment_arr[$t]['vContactName'] . ")" : '');
                            $sr_arr[] = $treatment_arr[$t]['iSRId'];

                        }

                        $arr[$ind]['dDate'] = ($treatment_arr[$t]['dDate'] ? $treatment_arr[$t]['dDate'] : '');
                        $arr[$ind]['site_details'] = $site_details;
                        $vSummary = '';
                        $vSummary = 'Treated' . ' ' . ($treatment_arr[$t]['vArea'] ? $treatment_arr[$t]['vArea'] : '') . ' ' . ($treatment_arr[$t]['vAreaTreated'] ? $treatment_arr[$t]['vAreaTreated'] : '') . ' With ' . ($treatment_arr[$t]['vAmountApplied'] ? number_format($treatment_arr[$t]['vAmountApplied'], 2, '.', '') : '') . ' ' . ($treatment_arr[$t]['vUnit'] ? $treatment_arr[$t]['vUnit'] : '') . ' ' . ($treatment_arr[$t]['vName'] ? $treatment_arr[$t]['vName'] : '');

                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iTreatmentId'];
                        $vSiteName = $treatment_arr[$t]['iSiteId'] . " (" . $treatment_arr[$t]['vSiteName'] . "; " . $treatment_arr[$t]['vTypeName'] . ")";

                        $srdisplay = ($treatment_arr[$t]['iSRId'] != "") ? $treatment_arr[$t]['iSRId'] . " (" . $treatment_arr[$t]['vContactName'] . ")" : "";

                        if ($treatment_arr[$t]['dStartDate'] != '')
                        {
                            $treatment_arr[$t]['dStartTime'] = date("H:i", strtotime($treatment_arr[$t]['dStartDate']));
                        }
                        else
                        {
                            $treatment_arr[$t]['dStartTime'] = date("H:i", time());
                        }

                        if ($treatment_arr[$t]['dEndDate'] != '')
                        {
                            $treatment_arr[$t]['dEndTime'] = date("H:i", strtotime($treatment_arr[$t]['dEndDate']));
                        }
                        else
                        {
                            $treatment_arr[$t]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s') . " +10 minutes"));
                        }

                        
                        $arr[$ind]['operation_type_data'] = array(
                            "iTreatmentId" => $treatment_arr[$t]['iTreatmentId'],
                            "vSiteName" => $vSiteName,
                            "iSiteId" => $treatment_arr[$t]['iSiteId'],
                            "dDate" => $treatment_arr[$t]['dDate'],
                            "dStartDate" => $treatment_arr[$t]['dStartDate'],
                            "dStartTime" => $treatment_arr[$t]['dStartTime'],
                            "dEndDate" => $treatment_arr[$t]['dEndDate'],
                            "dEndTime" => $treatment_arr[$t]['dEndTime'],
                            "vType" => $treatment_arr[$t]['vType'],
                            "iTPId" => $treatment_arr[$t]['iTPId'],
                            "vName" => $treatment_arr[$t]['vName'],
                            "vAppRate" => $vAppRate,
                            "vArea" => $treatment_arr[$t]['vArea'],
                            "vAreaTreated" => $treatment_arr[$t]['vAreaTreated'],
                            "vAmountApplied" => $treatment_arr[$t]['vAmountApplied'],
                            "iUId" => $treatment_arr[$t]['iUId'],
                            "iParentId" => $treatment_arr[$t]['iParentId'],
                            "srdisplay" => $srdisplay,
                            "iSRId" => $treatment_arr[$t]['iSRId'],
                            "iTechnicianId"=>$treatment_arr[$t]['iTechnicianId'],
                        );
                    }
                    $ind++;
                }
            }
            else if ($val['Type'] == "Landing Rate")
            {
                //echo $val['Type'];exit;
                $iTLRId = $val['iTLRId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $where_arr[] = 'task_landing_rate."iTLRId"=' . $iTLRId;
                $join_fieds_arr[] = " s.\"vName\"";
                $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
                $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
                $join_fieds_arr[] = " sr_details.\"iSRId\"";
                $join_fieds_arr[] = " sr_details.\"iCId\"";
                $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
                //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                //$join_fieds_arr[] = " mosquito_species_mas.\"tDescription\"";
                $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_landing_rate."iSiteId"';
                $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
                $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';
                $join_arr[] = 'LEFT JOIN sr_details on sr_details."iSRId" = task_landing_rate."iSRId"';
                $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = sr_details."iCId"';
                //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iSiteId" = s."iSiteId"';
                //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                $TaskLandingRate->join_field = $join_fieds_arr;
                $TaskLandingRate->join = $join_arr;
                $TaskLandingRate->where = $where_arr;
                $TaskLandingRate->param['order_by'] = "task_landing_rate.\"dDate\" DESC";
                $TaskLandingRate->setClause();
                $TaskLandingRate->debug_query = false;
                $landingrate_arr = $TaskLandingRate->recordset_list();
                if (!empty($landingrate_arr))
                {
                    $ti = count($landingrate_arr);
                    $iMspeciesIds = array();
                    for ($t = 0;$t < $ti;$t++)
                    {

                        $SiteObj->clear_variable();
                        $where_arr = array();
                        $join_fieds_arr = array();
                        $join_arr = array();
                        $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                        $where_arr[] = "site_attribute.\"iSiteId\"='" . $landingrate_arr[$t]['iSiteId'] . "'";
                        $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                        $SiteObj->join_field = $join_fieds_arr;
                        $SiteObj->join = $join_arr;
                        $SiteObj->where = $where_arr;
                        $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                        $SiteObj->setClause();
                        $rs_site_attr = $SiteObj->site_attribute_list();
                        //echo "<pre>";print_r($rs_site_attr);exit();
                        $vAttributeArr = array();
                        if (!empty($rs_site_attr))
                        {
                            $sai = count($rs_site_attr);
                            for ($sa = 0;$sa < $sai;$sa++)
                            {
                                $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                            }
                        }

                        $where_arr = array();
                        $join_fieds_arr = array();
                        $join_arr = array();
                        $TaskLandingRate->clear_variable();
                        $where_arr[] = 'task_landing_rate_species."iTLRId"=' . $iTLRId;
                        $join_fieds_arr[] = " mosquito_species_mas.\"tDescription\"";
                        $join_arr[] = 'LEFT JOIN mosquito_species_mas on mosquito_species_mas."iMSpeciesId" = task_landing_rate_species."iMSpeciesId"';

                        $TaskLandingRate->join_field = $join_fieds_arr;
                        $TaskLandingRate->join = $join_arr;
                        $TaskLandingRate->where = $where_arr;
                        $TaskLandingRate->setClause();
                        $TaskLandingRate->debug_query = false;
                        $landingrate_species_arr = $TaskLandingRate->task_landing_rate_species_list();
                        $species_arr = [];
                        if (count($landingrate_species_arr) > 0)
                        {
                            for ($s = 0;$s < count($landingrate_species_arr);$s++)
                            {
                                $species_arr[] = $landingrate_species_arr[$s]['tDescription'];
                                $iMspeciesIds[$s] = $landingrate_species_arr[$s]['iMSpeciesId'];
                            }
                        }
                        $iMSpeciesId = '';
                        if (!empty($iMspeciesIds))
                        {
                            $iMSpeciesId = implode("|||", $iMspeciesIds);
                        }

                        $site_details = '';
                        //$arr[$ind]['vAttribute'][] = $landingrate_arr[$t]['vAttribute'];
                        $site_details .= 'Premise ' . $landingrate_arr[$t]['iSiteId'] . ($landingrate_arr[$t]['vName'] ? ' (' . $landingrate_arr[$t]['vName'] . ') ' : '') . ($landingrate_arr[$t]['vTypeName'] ? $landingrate_arr[$t]['vTypeName'] : ' ') . ($landingrate_arr[$t]['vSubTypeName'] ? ' (' . $landingrate_arr[$t]['vSubTypeName'] . ')' : '') . (!empty($vAttributeArr) ? '(' . implode(' | ', $vAttributeArr) . ')' : '');

                        if ($landingrate_arr[$t]['iSRId'] > 0)
                        {
                            $site_details .= "<br/>SR " . $landingrate_arr[$t]['iSRId'] . ($landingrate_arr[$t]['vContactName'] ? " (" . $landingrate_arr[$t]['vContactName'] . ")" : '');
                            $sr_arr[] = $landingrate_arr[$t]['iSRId'];

                        }

                        if ($landingrate_arr[$t]['dStartDate'] != '')
                        {
                            $landingrate_arr[$t]['dStartTime'] = date("H:i", strtotime($landingrate_arr[$t]['dStartDate']));
                        }
                        else
                        {
                            $landingrate_arr[$t]['dStartTime'] = date("H:i", time());
                        }

                        if ($landingrate_arr[$t]['dEndDate'] != '')
                        {
                            $landingrate_arr[$t]['dEndTime'] = date("H:i", strtotime($landingrate_arr[$t]['dEndDate']));
                        }
                        else
                        {
                            $landingrate_arr[$t]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s') . " +10 minutes"));
                        }

                        $arr[$ind]['dDate'] = $landingrate_arr[$t]['dDate'];
                        $arr[$ind]['site_details'] = $site_details;
                        $arr[$ind]['vMaxLandingRate'] = $landingrate_arr[$t]['vMaxLandingRate'];
                        $vSummary = '';
                        $vSummary = 'Landing Rate ' . $arr[$ind]['vMaxLandingRate'] . ' ' . implode(' | ', $species_arr);
                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iTLRId'];
                        $vSiteName = $landingrate_arr[$t]['iSiteId'] . " (" . $landingrate_arr[$t]['vName'] . "; " . $landingrate_arr[$t]['vTypeName'] . ")";

                        $srdisplay = ($landingrate_arr[$t]['iSRId'] != "") ? $landingrate_arr[$t]['iSRId'] . " (" . $landingrate_arr[$t]['vContactName'] . ")" : "";

                        
                        $arr[$ind]['operation_type_data'] = array(
                            "iTLRId" => $landingrate_arr[$t]['iTLRId'],
                            "vSiteName" => $vSiteName,
                            "iSiteId" => $landingrate_arr[$t]['iSiteId'],
                            "dDate" => $landingrate_arr[$t]['dDate'],
                            "dStartDate" => $landingrate_arr[$t]['dStartDate'],
                            "dStartTime" => $landingrate_arr[$t]['dStartTime'],
                            "dEndDate" => $landingrate_arr[$t]['dEndDate'],
                            "dEndTime" => $landingrate_arr[$t]['dEndTime'],
                            "vMaxLandingRate" => $landingrate_arr[$t]['vMaxLandingRate'],
                            "iMSpeciesId" => $iMSpeciesId,
                            "tNotes" => $landingrate_arr[$t]['tNotes'],
                            "srdisplay" => $srdisplay,
                            "iSRId" => $landingrate_arr[$t]['iSRId'],
                            "iTechnicianId" =>$landingrate_arr[$t]['iTechnicianId'],
                        );
                    }
                    $ind++;
                }
            }
            else if ($val['Type'] == "Task Trap")
            {

                $iTTId = $val['iTTId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $where_arr[] = 'task_trap."iTTId"=' . $iTTId;
                $join_fieds_arr[] = " s.\"vName\"";
                $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
                $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
                $join_fieds_arr[] = " sr_details.\"iSRId\"";
                $join_fieds_arr[] = " sr_details.\"iCId\"";
                $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
                //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_trap."iSiteId"';
                $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
                $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';
                $join_arr[] = 'LEFT JOIN sr_details on sr_details."iSRId" = task_trap."iSRId"';
                //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iSiteId" = s."iSiteId"';
                //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = sr_details."iCId"';
                $join_fieds_arr[] = " trap_type_mas.\"vTrapName\",task_trap.\"dTrapPlaced\" as dDate";
                $join_arr[] = 'LEFT JOIN trap_type_mas on trap_type_mas."iTrapTypeId" = task_trap."iTrapTypeId"';

                $TaskTrap->join_field = $join_fieds_arr;
                $TaskTrap->join = $join_arr;
                $TaskTrap->where = $where_arr;
                $TaskTrap->param['order_by'] = "task_trap.\"dTrapPlaced\" DESC";
                $TaskTrap->setClause();
                $TaskTrap->debug_query = false;
                $tasktrap_arr = $TaskTrap->recordset_list();
                if (!empty($tasktrap_arr))
                {
                    $ti = count($tasktrap_arr);
                    for ($t = 0;$t < $ti;$t++)
                    {
                        $site_details = '';
                        $SiteObj->clear_variable();
                        $where_arr = array();
                        $join_fieds_arr = array();
                        $join_arr = array();
                        $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                        $where_arr[] = "site_attribute.\"iSiteId\"='" . $tasktrap_arr[$t]['iSiteId'] . "'";
                        $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                        $SiteObj->join_field = $join_fieds_arr;
                        $SiteObj->join = $join_arr;
                        $SiteObj->where = $where_arr;
                        $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                        $SiteObj->setClause();
                        $rs_site_attr = $SiteObj->site_attribute_list();
                        //echo "<pre>";print_r($rs_site_attr);exit();
                        $vAttributeArr = array();
                        if (!empty($rs_site_attr))
                        {
                            $sai = count($rs_site_attr);
                            for ($sa = 0;$sa < $sai;$sa++)
                            {
                                $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                            }
                        }

                        //$arr[$ind]['vAttribute'][] = $tasktrap_arr[$t]['vAttribute'];
                        $site_details .= 'Premise ' . $tasktrap_arr[$t]['iSiteId'] . ($tasktrap_arr[$t]['vName'] ? ' (' . $tasktrap_arr[$t]['vName'] . ') ' : ' ') . ($tasktrap_arr[$t]['vTypeName'] ? $tasktrap_arr[$t]['vTypeName'] : ' ') . ($tasktrap_arr[$t]['vSubTypeName'] ? ' (' . $tasktrap_arr[$t]['vSubTypeName'] . ')' : ' ') . (!empty($vAttributeArr) ? ' (' . implode(' | ', $vAttributeArr) . ')' : ' ');

                        if ($tasktrap_arr[$t]['iSRId'] > 0)
                        {
                            $site_details .= "<br/>SR " . $tasktrap_arr[$t]['iSRId'] . ($tasktrap_arr[$t]['vContactName'] ? " (" . $tasktrap_arr[$t]['vContactName'] . ")" : '');
                            $sr_arr[] = $tasktrap_arr[$t]['iSRId'];

                        }
                        $arr[$ind]['site_details'] = $site_details;
                        $arr[$ind]['dDate'] = ($tasktrap_arr[$t]['dTrapPlaced'] ? $tasktrap_arr[$t]['dTrapPlaced'] : '');
                        $arr[$ind]['vSummary'] = 'Trap ' .($tasktrap_arr[$t]['vTrapName'] ? $tasktrap_arr[$t]['vTrapName'] . ' Placed' : '');
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iTTId'];

                        $vSiteName = $tasktrap_arr[$t]['iSiteId'] . " (" . $tasktrap_arr[$t]['vName'] . "; " . $tasktrap_arr[$t]['vTypeName'] . ")";

                        $srdisplay = ($tasktrap_arr[$t]['iSRId'] != "") ? $tasktrap_arr[$t]['iSRId'] . " (" . $tasktrap_arr[$t]['vContactName'] . ")" : "";

                        
                        $arr[$ind]['operation_type_data'] = array(
                            "iTTId" => $tasktrap_arr[$t]['iTTId'],
                            "vSiteName" => $vSiteName,
                            "iSiteId" => $tasktrap_arr[$t]['iSiteId'],
                            "dTrapPlaced" => $tasktrap_arr[$t]['dTrapPlaced'],
                            "dTrapCollected" => $tasktrap_arr[$t]['dTrapCollected'],
                            "iTrapTypeId" => $tasktrap_arr[$t]['iTrapTypeId'],
                            "bMalfunction" => $tasktrap_arr[$t]['bMalfunction'],
                            "tNotes" => $tasktrap_arr[$t]['tNotes'],
                            "srdisplay" => $srdisplay,
                            "iSRId" => $tasktrap_arr[$t]['iSRId'],
                            "iTechnicianId" => $tasktrap_arr[$t]['iTechnicianId'],
                        );
                    }
                    $ind++;
                }

            }
            else if ($val['Type'] == "Laravel Surveillance")
            {

                $iTLSId = $val['iTLSId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $where_arr[] = 'task_larval_surveillance."iTLSId"=' . $iTLSId;
                $join_fieds_arr[] = " s.\"vName\" as  \"vSiteName\" ";
                $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
                $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
                $join_fieds_arr[] = " sr_details.\"iSRId\"";
                $join_fieds_arr[] = " sr_details.\"iCId\"";
                $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
                //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_larval_surveillance."iSiteId"';
                $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
                $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';

                //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iSiteId" = s."iSiteId"';
                //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                $join_arr[] = 'LEFT JOIN sr_details on sr_details."iSRId" = task_larval_surveillance."iSRId"';
                $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = sr_details."iCId"';

                $TaskLarvalSurveillance->join_field = $join_fieds_arr;
                $TaskLarvalSurveillance->join = $join_arr;
                $TaskLarvalSurveillance->where = $where_arr;
                $TaskLarvalSurveillance->param['order_by'] = "task_larval_surveillance.\"dDate\" DESC";
                $TaskLarvalSurveillance->setClause();
                $TaskLarvalSurveillance->debug_query = false;
                $larval_surveillance_arr = $TaskLarvalSurveillance->recordset_list();
                if (!empty($larval_surveillance_arr))
                {
                    $ti = count($larval_surveillance_arr);
                    for ($t = 0;$t < $ti;$t++)
                    {
                        $SiteObj->clear_variable();
                        $where_arr = array();
                        $join_fieds_arr = array();
                        $join_arr = array();
                        $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                        $where_arr[] = "site_attribute.\"iSiteId\"='" . $larval_surveillance_arr[$t]['iSiteId'] . "'";
                        $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                        $SiteObj->join_field = $join_fieds_arr;
                        $SiteObj->join = $join_arr;
                        $SiteObj->where = $where_arr;
                        $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                        $SiteObj->setClause();
                        $rs_site_attr = $SiteObj->site_attribute_list();
                        //echo "<pre>";print_r($rs_site_attr);exit();
                        $vAttributeArr = array();
                        if (!empty($rs_site_attr))
                        {
                            $sai = count($rs_site_attr);
                            for ($sa = 0;$sa < $sai;$sa++)
                            {
                                $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                            }
                        }

                        $site_details = '';
                        //$arr[$ind]['vAttribute'][] = $larval_surveillance_arr[$t]['vAttribute'];
                        $site_details .= 'Premise ' . $larval_surveillance_arr[$t]['iSiteId'] . ($larval_surveillance_arr[$t]['vSiteName'] ? ' (' . $larval_surveillance_arr[$t]['vSiteName'] . ') ' : ' ') . ($larval_surveillance_arr[$t]['vTypeName'] ? $larval_surveillance_arr[$t]['vTypeName'] : ' ') . ($larval_surveillance_arr[$t]['vSubTypeName'] ? ' (' . $larval_surveillance_arr[$t]['vSubTypeName'] . ')' : ' ') . (!empty($vAttributeArr) ? ' (' . implode(' | ', $vAttributeArr) . ')' : '');

                        if ($larval_surveillance_arr[$t]['iSRId'] > 0)
                        {
                            $site_details .= "<br/>SR " . $larval_surveillance_arr[$t]['iSRId'] . ($larval_surveillance_arr[$t]['vContactName'] ? " (" . $larval_surveillance_arr[$t]['vContactName'] . ")" : '');
                            $sr_arr[] = $larval_surveillance_arr[$t]['iSRId'];

                        }

                        $arr[$ind]['dDate'] = ($larval_surveillance_arr[$t]['dDate'] ? $larval_surveillance_arr[$t]['dDate'] : '');
                        $arr[$ind]['site_details'] = $site_details;

                        $iGenus_data = ($larval_surveillance_arr[$t]['iGenus'] ? $larval_surveillance_arr[$t]['iGenus'] : '');

                        switch ($iGenus_data)
                        {
                            case 1:
                                $iGenus = 'Ae.';
                            break;
                            case '2':
                                $iGenus = 'An.';
                            break;
                            case '3':
                                $iGenus = 'Cs.';
                            break;
                            case '4':
                                $iGenus = 'Cx.';
                            break;
                            default:
                                $iGenus = 'N/A';
                        }

                        $bEggs = ($larval_surveillance_arr[$t]['bEggs'] == 't') ? ', E' : '';
                        $bInstar1 = ($larval_surveillance_arr[$t]['bInstar1'] == 't') ? ', I1' : '';
                        $bInstar2 = ($larval_surveillance_arr[$t]['bInstar2'] == 't') ? ', I2' : '';
                        $bInstar3 = ($larval_surveillance_arr[$t]['bInstar3'] == 't') ? ', I3' : '';
                        $bInstar4 = ($larval_surveillance_arr[$t]['bInstar4'] == 't') ? ', I4' : '';
                        $bPupae = ($larval_surveillance_arr[$t]['bPupae'] == 't') ? ', P' : '';
                        $bAdult = ($larval_surveillance_arr[$t]['bAdult'] == 't') ? ', A' : '';

                        $iGenus_data2 = ($larval_surveillance_arr[$t]['iGenus2'] ? $larval_surveillance_arr[$t]['iGenus2'] : '');

                        switch ($iGenus_data2)
                        {
                            case 1:
                                $iGenus2 = 'Ae.';
                            break;
                            case '2':
                                $iGenus2 = 'An.';
                            break;
                            case '3':
                                $iGenus2 = 'Cs.';
                            break;
                            case '4':
                                $iGenus2 = 'Cx.';
                            break;
                            default:
                                $iGenus = 'N/A';
                        }
                        $bEggs2 = ($larval_surveillance_arr[$t]['bEggs2'] == 't') ? ', E' : '';
                        $bInstar12 = ($larval_surveillance_arr[$t]['bInstar12'] == 't') ? ', I1' : '';
                        $bInstar22 = ($larval_surveillance_arr[$t]['bInstar22'] == 't') ? ', I2,' : '';
                        $bInstar32 = ($larval_surveillance_arr[$t]['bInstar32'] == 't') ? ', I3' : '';
                        $bInstar42 = ($larval_surveillance_arr[$t]['bInstar42'] == 't') ? ', I4' : '';
                        $bPupae2 = ($larval_surveillance_arr[$t]['bPupae2'] == 't') ? ', P' : '';
                        $bAdult2 = ($larval_surveillance_arr[$t]['bAdult2'] == 't') ? ', A' : '';
                        $iTechnicianId = $larval_surveillance_arr[$t]['iTechnicianId'] ;

                        $vSummary = '';
                        $vSummary = 'Larval' . ($larval_surveillance_arr[$t]['iDips'] ? ' Dips' . ' ' . $larval_surveillance_arr[$t]['iDips'] : '') . ($larval_surveillance_arr[$t]['rAvgLarvel'] ? ' , Avg Larvae : ' . $larval_surveillance_arr[$t]['rAvgLarvel'] : '') . ' <font color=red>|</font> Species 1 : ' . $iGenus . ' ' . $larval_surveillance_arr[$t]['iCount'] . $bEggs . '' . $bInstar1 . '' . $bInstar2 . '' . $bInstar3 . '' . $bInstar4 . '' . $bPupae . '' . $bAdult . '<font color=red> | </font>Species 2 : ' . $iGenus2 . ' ' . $larval_surveillance_arr[$t]['iCount2'] . $bEggs2 . '' . $bInstar12 . '' . $bInstar22 . '' . $bInstar32 . '' . $bInstar42 . '' . $bPupae2 . '' . $bAdult2;

                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $val['iTLSId'];

                        if ($larval_surveillance_arr[$t]['dStartDate'] != '')
                        {
                            $larval_surveillance_arr[$t]['dStartTime'] = date("H:i", strtotime($larval_surveillance_arr[$t]['dStartDate']));
                        }
                        else
                        {
                            $larval_surveillance_arr[$t]['dStartTime'] = date("H:i", time());
                        }

                        if ($larval_surveillance_arr[$t]['dEndDate'] != '')
                        {
                            $larval_surveillance_arr[$t]['dEndTime'] = date("H:i", strtotime($larval_surveillance_arr[$t]['dEndDate']));
                        }
                        else
                        {
                            $larval_surveillance_arr[$t]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s') . " +10 minutes"));
                        }

                        $vSiteName = $larval_surveillance_arr[$t]['iSiteId'] . " (" . $larval_surveillance_arr[$t]['vName'] . "; " . $larval_surveillance_arr[$t]['vTypeName'] . ")";

                        $srdisplay = ($larval_surveillance_arr[$t]['iSRId'] != "0" && $larval_surveillance_arr[$t]['iSRId'] != "") ? $larval_surveillance_arr[$t]['iSRId'] . " (" . $larval_surveillance_arr[$t]['vContactName'] . ")" : "";

                        
                        $arr[$ind]['operation_type_data'] = array(
                            "iTLSId" => $larval_surveillance_arr[$t]['iTLSId'],
                            "vSiteName" => $vSiteName,
                            "iSiteId" => $larval_surveillance_arr[$t]['iSiteId'],
                            "iDips" => $larval_surveillance_arr[$t]['iDips'],
                            "dDate" => $larval_surveillance_arr[$t]['dDate'],
                            "dStartDate" => $larval_surveillance_arr[$t]['dStartDate'],
                            "dStartTime" => $larval_surveillance_arr[$t]['dStartTime'],
                            "dEndDate" => $larval_surveillance_arr[$t]['dEndDate'],
                            "dEndTime" => $larval_surveillance_arr[$t]['dEndTime'],
                            "iGenus" => $larval_surveillance_arr[$t]['iGenus'],
                            "iCount" => $larval_surveillance_arr[$t]['iCount'],
                            "bEggs" => $larval_surveillance_arr[$t]['bEggs'],
                            "bInstar1" => $larval_surveillance_arr[$t]['bInstar1'],
                            "bInstar2" => $larval_surveillance_arr[$t]['bInstar2'],
                            "bInstar3" => $larval_surveillance_arr[$t]['bInstar3'],
                            "bInstar4" => $larval_surveillance_arr[$t]['bInstar4'],
                            "bPupae" => $larval_surveillance_arr[$t]['bPupae'],
                            "bAdult" => $larval_surveillance_arr[$t]['bAdult'],
                            "iGenus2" => $larval_surveillance_arr[$t]['iGenus2'],
                            "iCount2" => $larval_surveillance_arr[$t]['iCount2'],
                            "bEggs2" => $larval_surveillance_arr[$t]['bEggs2'],
                            "bInstar12" => $larval_surveillance_arr[$t]['bInstar12'],
                            "bInstar22" => $larval_surveillance_arr[$t]['bInstar22'],
                            "bInstar32" => $larval_surveillance_arr[$t]['bInstar32'],
                            "bInstar42" => $larval_surveillance_arr[$t]['bInstar42'],
                            "bPupae2" => $larval_surveillance_arr[$t]['bPupae2'],
                            "bAdult2" => $larval_surveillance_arr[$t]['bAdult2'],
                            "rAvgLarvel" => $larval_surveillance_arr[$t]['rAvgLarvel'],
                            "tNotes" => $larval_surveillance_arr[$t]['tNotes'],
                            "dAddedDate" => $larval_surveillance_arr[$t]['dAddedDate'],
                            "srdisplay" => $srdisplay,
                            "iSRId" => $larval_surveillance_arr[$t]['iSRId'],
                            "iTechnicianId" => $larval_surveillance_arr[$t]['iTechnicianId'],
                        );
                    }
                    $ind++;
                }
            }
            else if ($val['Type'] == "Other")
            {
                $iTOId = $val['iTOId'];
                $where_arr = array();
                $join_fieds_arr = array();
                $join_arr = array();

                $where_arr[] = 'task_other."iTOId"=' . $iTOId;
                $join_fieds_arr[] = " s.\"vName\" as  \"vSiteName\" ";
                $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
                $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
                $join_fieds_arr[] = " sr_details.\"iSRId\"";
                $join_fieds_arr[] = " sr_details.\"iCId\"";
                $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
                //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_other."iSiteId"';
                $join_fieds_arr[] = " task_type_mas.\"vTypeName\" as \"task_name\" ";
                $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
                $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';

                //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iSiteId" = s."iSiteId"';
                //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                $join_arr[] = 'LEFT JOIN task_type_mas on task_type_mas."iTaskTypeId" = task_other."iTaskTypeId"';
                $join_arr[] = 'LEFT JOIN sr_details on sr_details."iSRId" = task_other."iSRId"';
                $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = sr_details."iCId"';

                $TaskOther->join_field = $join_fieds_arr;
                $TaskOther->join = $join_arr;
                $TaskOther->where = $where_arr;
                $TaskOther->param['order_by'] = "task_other.\"dDate\" DESC";
                $TaskOther->setClause();
                $TaskOther->debug_query = false;
                $task_other_arr = $TaskOther->recordset_list();
                if (!empty($task_other_arr))
                {
                    $ti = count($task_other_arr);
                    for ($t = 0;$t < $ti;$t++)
                    {
                        $SiteObj->clear_variable();
                        $where_arr = array();
                        $join_fieds_arr = array();
                        $join_arr = array();
                        $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                        $where_arr[] = "site_attribute.\"iSiteId\"='" . $task_other_arr[$t]['iSiteId'] . "'";
                        $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                        $SiteObj->join_field = $join_fieds_arr;
                        $SiteObj->join = $join_arr;
                        $SiteObj->where = $where_arr;
                        $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                        $SiteObj->setClause();
                        $rs_site_attr = $SiteObj->site_attribute_list();
                        //echo "<pre>";print_r($rs_site_attr);exit();
                        $vAttributeArr = array();
                        if (!empty($rs_site_attr))
                        {
                            $sai = count($rs_site_attr);
                            for ($sa = 0;$sa < $sai;$sa++)
                            {
                                $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                            }
                        }

                        $site_details = '';
                        //$arr[$ind]['vAttribute'][] = $task_other_arr[$t]['vAttribute'];
                        $site_details .= 'Premise ' . $task_other_arr[$t]['iSiteId'] . ($task_other_arr[$t]['vSiteName'] ? ' (' . $task_other_arr[$t]['vSiteName'] . ') ' : '') . ($task_other_arr[$t]['vTypeName'] ? $task_other_arr[$t]['vTypeName'] : '') . ($task_other_arr[$t]['vSubTypeName'] ? ' (' . $task_other_arr[$t]['vSubTypeName'] . ')' : '') . (empty($vAttributeArr) ? ' (' . implode(' | ', $vAttributeArr) . ')' : '');

                        if ($task_other_arr[$t]['iSRId'] > 0)
                        {
                            $site_details .= "<br/>SR " . $task_other_arr[$t]['iSRId'] . " (" . $task_other_arr[$t]['vContactName'] . ")";
                            $sr_arr[] = $task_other_arr[$t]['iSRId'];
                        }

                        $arr[$ind]['dDate'] = $task_other_arr[$t]['dDate'];
                        $arr[$ind]['site_details'] = $site_details;

                        $vSummary = $task_other_arr[$t]['task_name'];;
                        $arr[$ind]['vSummary'] = $vSummary;
                        $arr[$ind]['Type'] = $val['Type'];
                        $arr[$ind]['id'] = $task_other_arr[$t]['iTOId'];

                        $vSiteName = $task_other_arr[$t]['iSiteId'] . " (" . $task_other_arr[$t]['vName'] . "; " . $task_other_arr[$t]['vTypeName'] . ")";

                        $srdisplay = ($task_other_arr[$t]['iSRId'] != "") ? $task_other_arr[$t]['iSRId'] . " (" . $task_other_arr[$t]['vContactName'] . ")" : "";

                        if ($task_other_arr[$t]['dStartDate'] != '')
                        {
                            $task_other_arr[$t]['dStartTime'] = date("H:i", strtotime($task_other_arr[$t]['dStartDate']));
                        }
                        else
                        {
                            $task_other_arr[$t]['dStartTime'] = date("H:i", time());
                        }

                        if ($task_other_arr[$t]['dEndDate'] != '')
                        {
                            $task_other_arr[$t]['dEndTime'] = date("H:i", strtotime($task_other_arr[$t]['dEndDate']));
                        }
                        else
                        {
                            $task_other_arr[$t]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s') . " +10 minutes"));
                        }

                        
                        $arr[$ind]['operation_type_data'] = array(
                            "iTOId" => $task_other_arr[$t]['iTOId'],
                            "vSiteName" => $vSiteName,
                            "iSiteId" => $task_other_arr[$t]['iSiteId'],
                            "dDate" => $task_other_arr[$t]['dDate'],
                            "dStartDate" => $task_other_arr[$t]['dStartDate'],
                            "dStartTime" => $task_other_arr[$t]['dStartTime'],
                            "dEndDate" => $task_other_arr[$t]['dEndDate'],
                            "dEndTime" => $task_other_arr[$t]['dEndTime'],
                            "iTaskTypeId" => $task_other_arr[$t]['iTaskTypeId'],
                            "tNotes" => $task_other_arr[$t]['tNotes'],
                            "srdisplay" => $srdisplay,
                            "iSRId" => $task_other_arr[$t]['iSRId'],
                            "iTechnicianId" => $task_other_arr[$t]['iTechnicianId'],
                        );
                    }
                    $ind++;
                }
            }

        }
        $sr_arr = array_unique($sr_arr);
        $sr_list = array();
        $sr_count = count($sr_arr);

        if ($sr_count > 0)
        {
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $FiberInquiryObj->clear_variable();

            $where_arr[] = "sr_details.\"iSRId\" IN (" . implode(", ", $sr_arr) . ")";
            $where_arr[] = "sr_details.\"iStatus\" = 4 "; // SR Status = Complete;
            $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";

            $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = sr_details."iCId"';

            $FiberInquiryObj->join_field = $join_fieds_arr;
            $FiberInquiryObj->join = $join_arr;
            $FiberInquiryObj->where = $where_arr;
            $FiberInquiryObj->param['order_by'] = "sr_details.\"dAddedDate\" DESC";
            $FiberInquiryObj->setClause();
            $FiberInquiryObj->debug_query = false;
            $sr_details_arr = $FiberInquiryObj->recordset_list();
            $si = count($sr_details_arr);
            for ($s = 0;$s < $si;$s++)
            {
                $arr[$ind]['dDate'] = ($sr_details_arr[$s]['dAddedDate'] ? date("Y-m-d", strtotime($sr_details_arr[$s]['dAddedDate'])) : '');
                $arr[$ind]['site_details'] = 'SR ' . $sr_details_arr[$s]['iSRId'] . ($sr_details_arr[$s]['vContactName'] ? " (" . $sr_details_arr[$s]['vContactName'] . ")" : '');
                $vSummary = 'Closed SR : ' . ($sr_details_arr[$s]['tRequestorNotes'] ? $sr_details_arr[$s]['tRequestorNotes'] : '');
                $arr[$ind]['vSummary'] = $vSummary;
                $arr[$ind]['Type'] = "SR";
                $arr[$ind]['id'] = $sr_details_arr[$s]['iSRId'];

            }
            $ind++;
        }

        $ni = count($arr);

        //echo "<pre>";print_r($arr);exit();
        if ($page_type == "site_info_window")
        {
            $ni = count($arr);
            if ($ni >= 5)
            {
                $ni = 5;
            }
            $start1 = 0;
            $end1 = $ni;
            $total_record = $ni;
        }
        else
        {
            $ni = count($arr);
            if ($ni > $page_length)
            {
                if ($start != 0)
                {
                    if ($page_length != $start)
                    {
                        $start1 = ($start < $page_length) ? ($ni - $page_length - $start) : ($ni - $page_length);
                    }
                    else
                    {
                        $start1 = $page_length;
                    }
                }
                else
                {
                    $start1 = ($start < $page_length) ? 0 : ($ni - $page_length);
                }

                $end1 = ($ni - $start >= $page_length) ? ($start + $page_length) : $ni;
            }
            else
            {
                $start1 = 0;
                $end1 = $ni;
            }            
            
            $total_record = $ni;
        }
        if ($ni > 0){
            for ($i = $start1;$i < $end1;$i++)
            {
                $site_history_arr[] = array(
                    "Date" => $arr[$i]['dDate'],
                    "id" => $arr[$i]['id'],
                    "Name" => $arr[$i]['site_details'],
                    "Description" => $arr[$i]['vSummary'],
                    "Type" => $arr[$i]['Type'],
                    "operation_type_data" => $arr[$i]['operation_type_data'],
                );
            }
        }
    }
    $result = array('data' =>$site_history_arr , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}

?>
