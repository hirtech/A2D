<?php
//echo "<pre>";print_R($_REQUEST);exit;
include_once ($site_path . "scripts/session_valid.php");

per_hasModuleAccess("Import File", 'Add');

# ----------- Access Rule Condition -----------
$access_group_var_list = per_hasModuleAccess("Import File", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Import File", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Import File", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Import File", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Import File", 'Edit', 'N');

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "");

include_once ($function_path . "image.inc.php");
include_once ($controller_path . "import_file.inc.php");
include_once ($controller_path . "premise_type.inc.php");
include_once ($controller_path . "premise_sub_type.inc.php");
include_once ($controller_path . "premise.inc.php");
include_once ($controller_path . "zone.inc.php");
include_once ($controller_path . "user.inc.php");
include_once ($controller_path . "treatment_product.inc.php");
include_once ($controller_path . "task_type.inc.php");
include_once ($controller_path . "task_weather.inc.php");

include_once ($function_path . "mail.inc.php");

$mail_to = "hemangp@hirtechnology.com";
//$mail_to = "ritu.maurya@horizoncore.com";
$mail_cc = "zinal.patel@horizoncore.com";

//$mail_format = 'text/plain';
$mail_format = 'text/html';
$mail_subject = 'Import Invalid Record';
$mail_body = "";
$mail_from = "hirtechnology@gmail.com";

$ImportFileObj = new ImportFile();
$Site_TypeObj = new SiteType();
$SiteSubTypeObj = new SiteSubType();
$ZoneObj = new Zone();
$SiteObj = new Site();
$UserObj = new User();
$TProdObj = new TreatmentProduct();
$TaskTypeObj = new TaskType();
$TaskWeatherObj = new TaskWeather();

if ($mode == "import_file")
{
    /*echo "<pre>";print_r($_FILES);
     print_r($_REQUEST);exit();*/
    $jsonData = array();
    $cType = $_POST['impOptions'];
    $file = $_FILES['file']['name'];
    if ($_FILES['file']['name'] != "")
    {
        $file_arr = img_fileUpload("file", $import_file_path, '', $valid_ext = array(
            'xls',
            'xlsx'
        ));

        $file_name = $file_arr[0];
        $file_msg = $file_arr[1];

        if ($file_name != "")
        {
            $insert_array = array(
                "vFile" => $file_name,
                "vOption" => $cType,
                "dImportDate" => date_getSystemDateTime() ,
            );

            $ImportFileObj->insert_arr = $insert_array;
            $ImportFileObj->setClause();
            $iId = $ImportFileObj->add_records();
            //$file_name  = "1600423805_2019_larval_treatments.xls";
            include_once ($class_path . 'PHPExcel/PHPExcel.php');

            try
            {
                $excelReader = PHPExcel_IOFactory::createReaderForFile($import_file_path . $file_name);

                $excelObj = $excelReader->load($import_file_path . $file_name);

                $worksheet = $excelObj->getSheet(0);
                //$lastRow = $worksheet->getHighestRow();
                $lastRow = $worksheet->getHighestDataRow();
                //echo $lastRow;exit();
                $access_group_id = 4; //Technician
                $department_id_arr = array(
                    5,
                    6
                );
                //26=>Field Ops Management,27=>Field Ops Technician
                $password = 'Temp123!';
                $encryptedPassword = encrypt_password($password);
                $vFromIP = getIP();
                $user_status = 1;
                if ($cType == "larval")
                {

                    $treatment_product_arr = array();
                    $site_type_arr = array();
                    $r = 2;
                    $flag = 0;
                    $invalid_treat_prod = array();
                    $values = array();
                    $site_subtype_mas = array(
                        'Catch Basin',
                        'Basin',
                        'Mosquito Source',
                        'Residential'
                    );

                    $access_group_id = 4; //Technician
                    $department_id_arr = array(
                        5,
                        6
                    );
                    //26=>Field Ops Management,27=>Field Ops Technician
                    $password = 'Temp123!';
                    $encryptedPassword = encrypt_password($password);
                    $vFromIP = getIP();
                    $user_status = 1;
                    $count_larval_import = 0;

                    //print_r($worksheet);
                    if ($lastRow > 1)
                    {
                        $message = "";

                        //Validate the template is correct (check headers)
                        foreach ($import_larval_file_headers as $column => $headers)
                        {
                            if (trim($worksheet->getCell($column . '1')->getValue()) != trim($headers))
                            {
                                $flag = 1;
                                break;
                            }
                        }

                        if ($flag != 1)
                        {

                            $treatment_prod_error = 0;
                            //Check AE Treatment product name are not blank
                            $treatment_product_col = $excelObj->setActiveSheetIndex(0)
                                ->rangeToArray('AE2:AE' . $lastRow);
                            //echo "<pre>";print_r($treatment_product_col);exit();
                            $treatment_product = array_filter(array_column($treatment_product_col, '0'));
                            //Check AE column is not blank

                            if (count($treatment_product) > 0 && count($treatment_product) == count($treatment_product_col))
                            {

                                $treatment_product_arr = array_unique($treatment_product);
                                //Get Treatment product id
                                $TProdObj->clear_variable();
                                $where_arr = array();
                                $join_fieds_arr = array();
                                $join_arr = array();
                                $where_arr[] = 'treatment_product."vName" IN (\'' . implode("','", $treatment_product_arr) . '\') ';;
                                $TProdObj->join_field = $join_fieds_arr;
                                $TProdObj->join = $join_arr;
                                $TProdObj->where = $where_arr;
                                $TProdObj->setClause();
                                $rs_tprod_arr = $TProdObj->recordset_list();
                                //$rs_tprod_data = $TProdObj->recordset_total();
                                

                                //Check if all values in column AE are present in treatment_product
                                if (count($rs_tprod_arr) != count($treatment_product_arr))
                                {
                                    $treatment_prod_error = 1;
                                }
                                $user_col = $excelObj->setActiveSheetIndex(0)
                                    ->rangeToArray('Z2:Z' . $lastRow);

                                if ($treatment_prod_error != 1)
                                {
                                    //Import all unique values in Column C to site_subtype_mas, except values "Catch Basin", "Basin", "Mosquito Source" or "Residential"
                                    //Get Premise Sub type
                                    $join_fieds_arr = array();
                                    $join_arr = array();
                                    $where_arr = array();
                                    $SiteSubTypeObj->join_field = $join_fieds_arr;
                                    $SiteSubTypeObj->join = $join_arr;
                                    $SiteSubTypeObj->where = $where_arr;
                                    $SiteSubTypeObj->setClause();
                                    $rs_subtype = $SiteSubTypeObj->recordset_list();
                                    $rssubtype = array_column($rs_subtype, 'vSubTypeName');

                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $col_val = trim($worksheet->getCell('C' . $row)->getValue());
                                        if ($col_val != "" && !in_array($col_val, $site_subtype_mas) && !in_array($col_val, $site_subtype))
                                        {
                                            $site_subtype[] = trim($worksheet->getCell('C' . $row)->getValue());
                                        }

                                    }

                                    $count = 0;
                                    $site_count = 0;
                                    $user_cnt = 0;

                                    //Site sub data import
                                    if (count($site_subtype) > 0)
                                    {
                                        $Site_TypeObj->clear_variable();
                                        $where_arr = array();
                                        $join_fieds_arr = array();
                                        $join_arr = array();
                                        $where_arr[] = "site_type_mas.\"vTypeName\" ILIKE 'Mosquito Source' ";
                                        $Site_TypeObj->join_field = $join_fieds_arr;
                                        $Site_TypeObj->join = $join_arr;
                                        $Site_TypeObj->where = $where_arr;
                                        $Site_TypeObj->param['order_by'] = '';
                                        $Site_TypeObj->param['limit'] = '';
                                        $Site_TypeObj->setClause();
                                        $Site_TypeObj->debug_query = false;
                                        $rs_sitetype = $Site_TypeObj->recordset_list();

                                        for ($i = 0;$i < count($site_subtype);$i++)
                                        {
                                            if (!in_array($site_subtype[$i], $rssubtype))
                                            {

                                                $insert_arr = array();
                                                $insert_arr = array(
                                                    'iSTypeId' => $rs_sitetype[0]['iSTypeId'],
                                                    'vSubTypeName' => trim($site_subtype[$i]) ,
                                                    'iStatus' => '1'
                                                );
                                                $SiteSubTypeObj->insert_arr = $insert_arr;
                                                $SiteSubTypeObj->setClause();
                                                $SiteSubTypeObj->add_records();
                                                $count++;
                                            }
                                        }
                                        if ($count > 0)
                                        {
                                            $message .= $count . " records should be imported to site_sub_type_mas";
                                        }

                                    }

                                    $site_count = 0;
                                    $site_type_asoc_arr = array();

                                    //Get premise type
                                    $Site_TypeObj->clear_variable();
                                    $where_arr = array();
                                    $join_fieds_arr = array();
                                    $join_arr = array();
                                    $Site_TypeObj->join_field = $join_fieds_arr;
                                    $Site_TypeObj->join = $join_arr;
                                    $Site_TypeObj->where = $where_arr;
                                    $Site_TypeObj->param['order_by'] = '';
                                    $Site_TypeObj->param['limit'] = '';
                                    $Site_TypeObj->setClause();
                                    $Site_TypeObj->debug_query = false;
                                    $rssitetype = $Site_TypeObj->recordset_list();

                                    for ($s = 0;$s < count($rssitetype);$s++)
                                    {
                                        $typename = strtolower(trim($rssitetype[$s]['vTypeName']));
                                        $site_type_asoc_arr[$typename] = $rssitetype[$s]['iSTypeId'];
                                    }

                                    //import site data
                                    //echo "<pre>";print_r($site_type_asoc_arr);
                                    $site_data_arrr = array();

                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());

                                        $sitetype = trim($worksheet->getCell('C' . $row)->getValue());
                                        $cityname = trim($worksheet->getCell('AW' . $row)->getValue());
                                        $address1 = trim($worksheet->getCell('AU' . $row)->getValue());
                                        $address2 = trim($worksheet->getCell('AV' . $row)->getValue());
                                        $statename = trim($worksheet->getCell('AX' . $row)->getValue());
                                        $zipcode = trim($worksheet->getCell('AY' . $row)->getValue());
                                        $longtitude = trim($worksheet->getCell('AZ' . $row)->getValue());
                                        $latitude = trim($worksheet->getCell('BA' . $row)->getValue());

                                        $lat = number_format($latitude, 6, '.', '');
                                        $long = number_format($longtitude, 6, '.', '');

                                        if ($sitename != "" && ($lat != '0' && $long != '0'))
                                        {
                                            $sitetypeid = 0;
                                            $cityid = 0;
                                            $stateid = 0;
                                            $zipcodeid = 0;
                                            $zoneId = 0;
                                            $countyId = 0;

                                            $sitetypeid = $sitesubtypeid = "";
                                            $tmp_sitetype = strtolower($sitetype);

                                            if (isset($site_type_asoc_arr[$tmp_sitetype]))
                                            {
                                                $sitetypeid = $site_type_asoc_arr[$tmp_sitetype];

                                            }
                                            else
                                            {
                                                $sql = "SELECT \"iSSTypeId\",\"iSTypeId\"  from site_sub_type_mas where \"vSubTypeName\" ILIKE '" . $sitetype . "' limit 1";

                                                $rssitesub = $sqlObj->GetAll($sql);
                                                $sitetypeid = $rssitesub[0]['iSTypeId'];
                                                $sitesubtypeid = $rssitesub[0]['iSSTypeId'];
                                            }

                                          

                                           $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' and \"iSTypeId\" = '" . $sitetypeid . "' AND \"vPointLatLong\" = ST_GEOMFROMTEXT('POINT(" . $long . " " . $lat . ")', 4326)   LIMIT 1";
                                            
                                            $rs_site = $sqlObj->GetAll($sql_site);
                                            
                                            if (count($rs_site) == 0)
                                            {
                                               
                                                $site_data_arrr[] = $sitename;
                                                if ($cityname != "")
                                                {
                                                    $sql = "SELECT \"iCityId\"  from city_mas where \"vCity\" ILIKE '" . $cityname . "' limit 1";
                                                    $rs_city = $sqlObj->GetAll($sql);
                                                    if (!empty($rs_city))
                                                    {
                                                        $cityid = $rs_city[0]['iCityId'];
                                                    }
                                                    else
                                                    {
                                                        $sql = " INSERT into city_mas (\"vCity\") values (" . gen_allow_null_char($cityname) . ")";
                                                        $sqlObj->Execute($sql);
                                                        $cityid = $sqlObj->Insert_ID();
                                                    }
                                                }

                                                if ($statename != "")
                                                {
                                                    $sql = "SELECT \"iStateId\"  from state_mas where \"vState\" ILIKE '" . $statename . "' limit 1";
                                                    $rs_state = $sqlObj->GetAll($sql);

                                                    $stateid = $rs_state[0]['iStateId'];
                                                }

                                                if ($zipcode != "")
                                                {
                                                    $zipcode = (strlen($zipcode) < 5) ? str_pad($zipcode, 5, '0', STR_PAD_LEFT) : $zipcode;

                                                    $sql = "SELECT \"iZipcode\"  from zipcode_mas where \"vZipcode\" ILIKE '" . $zipcode . "' limit 1";
                                                    $rs_zipcode = $sqlObj->GetAll($sql);
                                                    if (!empty($rs_zipcode))
                                                    {
                                                        $zipcodeid = $rs_zipcode[0]['iZipcode'];
                                                        //echo "111";exit();
                                                        
                                                    }
                                                    else
                                                    {
                                                        $sql = " INSERT into zipcode_mas (\"vZipcode\") values (" . gen_allow_null_char($zipcode) . ")";
                                                        $sqlObj->Execute($sql);
                                                        $zipcodeid = $sqlObj->Insert_ID();

                                                    }
                                                }

                                                $vPointLatLong = gen_allow_null_char('');
                                                $iGeometryType = 1; //Point
                                                $status = 1; //Active
                                                if ($long != '' && $lat != '')
                                                {
                                                    $vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT(' . $long . ' ' . $lat . ')\', 4326)';

                                                    $sql_zone = "SELECT zone.\"iZoneId\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(" . $long . " " . $lat . ")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'";
                                                    $rs = $sqlObj->GetAll($sql_zone);

                                                    if ($rs)
                                                    {
                                                        $zoneId = $rs[0]['iZoneId'];
                                                    }

                                                }

                                                $sql_ins = 'INSERT INTO site_mas ("vName",  "iSTypeId", "iSSTypeId" ,"vAddress1", "vAddress2", "iZipcode", "iGeometryType", "iZoneId", "vLatitude", "vLongitude", "vNewLatitude", "vNewLongitude", "vPointLatLong","dAddedDate", "iStatus", "vLoginUserName", "iStateId", "iCountyId", "iCityId") VALUES (' . gen_allow_null_char($sitename) . ', ' . gen_allow_null_int($sitetypeid) . ', ' . gen_allow_null_int($sitesubtypeid) . ', ' . gen_allow_null_char($address1) . ', ' . gen_allow_null_char($address2) . ', ' . gen_allow_null_int($zipcodeid) . ', ' . gen_allow_null_int($iGeometryType) . ', ' . gen_allow_null_int($zoneId) . ', ' . gen_allow_null_char($lat) . ', ' . gen_allow_null_char($long) . ', ' . gen_allow_null_char($lat) . ', ' . gen_allow_null_char($long) . ', ' . $vPointLatLong . ', ' . gen_allow_null_char(date_getSystemDateTime()) . ', ' . gen_allow_null_int($status) . ', ' . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]) . ', ' . gen_allow_null_char($stateid) . ', ' . gen_allow_null_char($countyId) . ', ' . gen_allow_null_char($cityid) . ')';
                                                //echo "<br>".$sql_ins;exit();
                                                $sqlObj->Execute($sql_ins);
                                                $site_count++;
                                            }
                                            
                                            
                                        }
                                    }
                                    /*  echo "<pre>";print_r($site_data_arrr);
                                     echo "=>",$site_count++;exit();*/
                                    if ($site_count > 0)
                                    {
                                        $message .= $site_count . " records imported to site mas";
                                    }

                                    //echo "Site_count".$site_count."<br>";exit();
                                    //get user data
                                    $user_col = $excelObj->setActiveSheetIndex(0)
                                        ->rangeToArray('Z2:Z' . $lastRow);

                                    $user_name = array_column($user_col, '0');
                                    $user_arr = array_unique(array_filter($user_name));

                                    //echo "<pre>";print_r($user_arr);exit();
                                    //Add user data
                                    if ($user_arr > 0)
                                    {
                                        foreach ($user_arr as $ukey => $uname)
                                        {

                                            if (trim($uname) != "")
                                            {

                                                $name = explode(" ", $uname, 2);
                                                //Import into user table
                                                $username = str_replace(' ', '', $uname);
                                                //echo $username;exit();
                                                $iUserId = "";
                                                $UserObj->user_clear_variable();
                                                //check user name duplication
                                                $where_arr = array();
                                                $where_arr[] = "user_mas.\"vUsername\" = '" . $username . "'";
                                                $where_arr[] = "user_mas.\"iAGroupId\" = '" . $access_group_id . "'";
                                                $UserObj->where = $where_arr;
                                                $UserObj->param['limit'] = " LIMIT 1";
                                                $UserObj->setClause();
                                                $rs_user = $UserObj->recordset_list();
                                                //print_r($rs_user);exit();
                                                if (count($rs_user) == 0)
                                                {
                                                    $insert_array = array(
                                                        "iAGroupId" => $access_group_id,
                                                        "iDepartmentId" => $department_id_arr,
                                                        "vFirstName" => addslashes($name[0]) ,
                                                        "vLastName" => addslashes($name[1]) ,
                                                        "vUsername" => addslashes($username) ,
                                                        "vPassword" => $encryptedPassword['encryptedPassword'],
                                                        "vFromIP" => getIP() ,
                                                        "iStatus" => '1',
                                                        "dDate" => date_getSystemDateTime() ,
                                                        "sSalt" => addslashes($encryptedPassword['salt']) ,
                                                    );

                                                    $UserObj->insert_arr = $insert_array;
                                                    $UserObj->setClause();
                                                    $iUserId = $UserObj->add_records();

                                                    $user_cnt++;
                                                }
                                            }

                                        }
                                    }

                                    if ($user_cnt > 0)
                                    {
                                        $message .= $user_cnt . " records should be imported to user_mas";
                                    }

                                    $tt = 0;
                                    //Import into task_treatment
                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());

                                        $longtitude = trim($worksheet->getCell('AZ' . $row)->getValue());
                                        $latitude = trim($worksheet->getCell('BA' . $row)->getValue());

                                        $lat = number_format($latitude, 6, '.', '');
                                        $long = number_format($longtitude, 6, '.', '');

                                       
                                        if ($sitename != "" && ($lat != '0' && $long != '0'))
                                        {
                                            //Get site id

                                             //$sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "'  AND \"vPointLatLong\" = ST_SetSRID(ST_MakePoint(".$lat.", ".$long ."), 4326)::geography LIMIT 1";
                                            $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' AND \"vPointLatLong\" = ST_GEOMFROMTEXT('POINT(" . $long . " " . $lat . ")', 4326)   LIMIT 1";
                                            //echo "<br>".$sql_site;
                                            $rs_site = $sqlObj->GetAll($sql_site);
                                            //echo "<pre>";print_r($rs_site);exit();
                                            $siteid = $rs_site[0]['iSiteId'];

                                            if ($siteid != "")
                                            {
                                                $treat_date = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));
                                                //$treat_date = date('Y-m-d', strtotime($worksheet->getCell('E' . $row)->getValue()));
                                                $treat_type = trim($worksheet->getCell('V' . $row)->getValue());
                                                $treat_prod = trim($worksheet->getCell('AE' . $row)->getValue());
                                                $treat_area = trim($worksheet->getCell('G' . $row)->getValue());
                                                $treat_areatreated = (trim($worksheet->getCell('H' . $row)->getValue()) == 'Sq. Ft.') ? 'sqft' : 'acre';
                                                $treat_amountapplied = trim($worksheet->getCell('I' . $row)->getValue());
                                                $unit_name = trim($worksheet->getCell('J' . $row)->getValue());

                                                $treat_startdate = date('Y-m-d H:i:s',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));
                                                $treat_enddate = date("Y-m-d H:i:s", strtotime($treat_startdate . " +10 minutes"));
                                                $addedDate = date('Y-m-d H:i:s',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));
                                                $modifiedDate = date('Y-m-d H:i:s',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));

                                                /*$treat_startdate = date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()));
                                                $treat_enddate = date("Y-m-d H:i:s", strtotime($treat_startdate . " +10 minutes"));
                                                $addedDate = date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()));
                                                $modifiedDate = date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()));*/

                                                $user_name = trim($worksheet->getCell('Z' . $row)->getValue());

                                                $bJustification = (trim($worksheet->getCell('F' . $row)->getValue()) == 'Presence of Larvae') ? '1' : '0';
                                                $iEquipmentId = trim($worksheet->getCell('k' . $row)->getValue());

                                                //Get Treatment product id
                                                $TProdObj->clear_variable();
                                                $where_arr = array();
                                                $join_fieds_arr = array();
                                                $join_arr = array();
                                                $where_arr[] = 'treatment_product."vName" ILIKE \'' . $treat_prod . '\' ';;
                                                $TProdObj->join_field = $join_fieds_arr;
                                                $TProdObj->join = $join_arr;
                                                $TProdObj->where = $where_arr;
                                                $TProdObj->param['limit'] = " LIMIT 1";
                                                $TProdObj->setClause();
                                                $rs_tprod_data = $TProdObj->recordset_list();

                                                $tprod_id = $rs_tprod_data[0]['iTPId'];

                                                if (strtolower($unit_name) == "gallons")
                                                {
                                                    $unit_name = "Gallon";
                                                }
                                                else if (strtolower($unit_name) == "briquets" || strtolower($unit_name) == "briquet" || strtolower($unit_name) == "others" || strtolower($unit_name) == "other" || strtolower($unit_name) == "pouches" || strtolower($unit_name) == "pouch")
                                                {
                                                    $unit_name = "each";
                                                }
                                                else if (strtolower($unit_name) == "ounces" || strtolower($unit_name) == "ounce")
                                                {
                                                    $tprod_iUId = $rs_tprod_data[0]['iUId'];
                                                    if ($tprod_iUId > 0)
                                                    {
                                                        $TProdObj->clear_variable();
                                                        $where_arr = array();
                                                        $join_fieds_arr = array();
                                                        $join_arr = array();
                                                        $where_arr[] = " unit_mas.\"iUId\" = '" . $tprod_iUId . "'";
                                                        $TProdObj->join_field = $join_fieds_arr;
                                                        $TProdObj->join = $join_arr;
                                                        $TProdObj->where = $where_arr;
                                                        $TProdObj->param['order_by'] = "unit_mas.\"iUId\" DESC";
                                                        $TProdObj->param['limit'] = "LIMIT 1";
                                                        $TProdObj->setClause();
                                                        $TProdObj->debug_query = false;
                                                        $rs_unit1 = $TProdObj->unit_data();
                                                        if ($rs_unit1 > 0)
                                                        {
                                                            $unit_parent_id = $rs_unit1[0]['iParentId'];
                                                            if ($unit_parent_id > 0)
                                                            {
                                                                $sql_u = 'SELECT "vUnit" FROM unit_mas WHERE "iUId" = ' . $unit_parent_id . ' LIMIT 1';
                                                                $rs_u = $sqlObj->GetAll($sql_u);
                                                                if (!empty($rs_u))
                                                                {
                                                                    if ($rs_u[0]['vUnit'] == 'MASS')
                                                                    {
                                                                        $unit_name = "ounce";
                                                                    }
                                                                    else if ($rs_u[0]['vUnit'] == 'VOLUME')
                                                                    {
                                                                        $unit_name = "fluid ounce";
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                //Get Unit id
                                                $TProdObj->clear_variable();
                                                $where_arr = array();
                                                $join_fieds_arr = array();
                                                $join_arr = array();
                                                $where_arr[] = " unit_mas.\"vDescription\" ILIKE '" . $unit_name . "'";
                                                $TProdObj->join_field = $join_fieds_arr;
                                                $TProdObj->join = $join_arr;
                                                $TProdObj->where = $where_arr;
                                                $TProdObj->param['order_by'] = "";
                                                $TProdObj->param['limit'] = "";
                                                $TProdObj->setClause();
                                                $TProdObj->debug_query = false;
                                                $rs_unit = $TProdObj->unit_data();

                                                $unit_id = $rs_unit[0]['iUId'];

                                                $username = str_replace(' ', '', $user_name);
                                                //Get User id
                                                $UserObj->user_clear_variable();
                                                $where_arr = array();
                                                $where_arr[] = "user_mas.\"vUsername\" = '" . $username . "'";
                                                $where_arr[] = "user_mas.\"iAGroupId\" = '" . $access_group_id . "'";
                                                $join_fieds_arr = array();
                                                $join_arr = array();
                                                $UserObj->join_field = $join_fieds_arr;
                                                $UserObj->join = $join_arr;
                                                $UserObj->where = $where_arr;
                                                $UserObj->param['limit'] = " LIMIT 1";
                                                $UserObj->setClause();
                                                $rs_user = $UserObj->recordset_list();

                                                $user_id = $rs_user[0]['iUserId'];

                                                $sql = "INSERT INTO task_treatment (\"iSiteId\", \"iSRId\", \"dDate\", \"vType\", \"dStartDate\",\"dEndDate\", \"iTPId\", \"vArea\", \"vAreaTreated\",\"vAmountApplied\",\"iUId\", \"dAddedDate\",\"dModifiedDate\",\"iUserId\") VALUES (" . gen_allow_null_char($siteid) . ", " . gen_allow_null_char('') . ", " . gen_allow_null_char($treat_date) . ", " . gen_allow_null_char($treat_type) . ", " . gen_allow_null_char($treat_startdate) . ", " . gen_allow_null_char($treat_enddate) . ", " . gen_allow_null_char($tprod_id) . ", " . gen_allow_null_char($treat_area) . ", " . gen_allow_null_char($treat_areatreated) . ", " . gen_allow_null_char($treat_amountapplied) . "," . gen_allow_null_char($unit_id) . "," . gen_allow_null_char($addedDate) . "," . gen_allow_null_char($modifiedDate) . "," . gen_allow_null_int($user_id) . ")";

                                                $sqlObj->Execute($sql);
                                                $iTreatmentId = $sqlObj->Insert_ID();

                                                $tt++;
                                            }
                                        }
                                    }

                                    // import the unique values in task_type_mas
                                    $TaskTypeObj->clear_variable();
                                    $where_arr = array();
                                    $join_fieds_arr = array();
                                    $join_arr = array();
                                    $TaskTypeObj->join_field = $join_fieds_arr;
                                    $TaskTypeObj->join = $join_arr;
                                    $TaskTypeObj->where = $where_arr;
                                    $TaskTypeObj->setClause();
                                    $rs_type = $TaskTypeObj->recordset_list();
                                    $type_arr = array_column($rs_type, "vTypeName");

                                    $task_type_arr = array();

                                    $task_type_col = $excelObj->setActiveSheetIndex(0)
                                        ->rangeToArray('D2:D' . $lastRow);
                                    $task_type_name = array_column($task_type_col, '0');
                                    $task_type_arr = array_unique(array_filter($task_type_name));
                                    $ttype = 0;
                                    $tasktype = array();
                                    foreach ($task_type_arr as $key => $task_type)
                                    {
                                        $typename = "Site Status - " . $task_type;

                                        if ($typename != "" && !in_array($typename, $type_arr) && !in_array($typename, $tasktype))
                                        {

                                            $sql = "INSERT INTO task_type_mas (\"vTypeName\",\"iStatus\") values (" . gen_allow_null_char($typename) . ",'1')";
                                            $sqlObj->Execute($sql);
                                            $tasktype[] = $typename;
                                            $ttype++;
                                        }
                                    }

                                    //Import into task_other && task_larval_surveillance data
                                    $tother = 0;
                                    $tlar = 0;
                                    $twind = 0;

                                    $WindDirection_arr = array(
                                        'NE',
                                        'West',
                                        'ESE',
                                        'ENE',
                                        'NW',
                                        'NNE',
                                        'ESE',
                                        'SW',
                                        'WNW',
                                        'NNW',
                                        'SSW',
                                        'WSW'
                                    );
                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());

                                        $longtitude = trim($worksheet->getCell('AZ' . $row)->getValue());
                                        $latitude = trim($worksheet->getCell('BA' . $row)->getValue());

                                        $lat = number_format($latitude, 6, '.', '');
                                         $long = number_format($longtitude, 6, '.', '');

                                        if ($sitename != "" && ($lat != '0' && $long != '0'))
                                        {
                                            //echo $sitename;exit();
                                            //Get site id
                                            //$sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' AND \"vPointLatLong\" = ST_SetSRID(ST_MakePoint(".$lat.", ".$long ."), 4326)::geography LIMIT 1";
                                             $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' AND \"vPointLatLong\" = ST_GEOMFROMTEXT('POINT(" . $long . " " . $lat . ")', 4326)   LIMIT 1";
                                            // echo "<br>".$sql_site;
                                            $rs_site = $sqlObj->GetAll($sql_site);

                                            $siteid = $rs_site[0]['iSiteId'];

                                            if ($siteid != "")
                                            {
                                                $task_date = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));
                                                $task_startdate = date('Y-m-d H:i:s',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));
                                                $task_enddate = date("Y-m-d H:i:s", strtotime($task_startdate . " +10 minutes"));
                                                $addedDate = date('Y-m-d H:i:s',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));
                                                $modifiedDate = date('Y-m-d H:i:s',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));

                                                /*$task_date = date('Y-m-d', strtotime($worksheet->getCell('E' . $row)->getValue()));
                                                $task_startdate = date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()));
                                                $task_enddate = date("Y-m-d H:i:s", strtotime($task_startdate . " +10 minutes"));
                                                $addedDate = date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()));
                                                $modifiedDate = date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()));*/

                                                $user_name = trim($worksheet->getCell('Z' . $row)->getValue());

                                                $username = str_replace(' ', '', $user_name);
                                                //Get User id
                                                $UserObj->user_clear_variable();
                                                $where_arr = array();
                                                $where_arr[] = "user_mas.\"vUsername\" = '" . $username . "'";
                                                $where_arr[] = "user_mas.\"iAGroupId\" = '" . $access_group_id . "'";
                                                $join_fieds_arr = array();
                                                $join_arr = array();
                                                $UserObj->join_field = $join_fieds_arr;
                                                $UserObj->join = $join_arr;
                                                $UserObj->where = $where_arr;
                                                $UserObj->param['limit'] = " LIMIT 1";
                                                $UserObj->setClause();
                                                $rs_user = $UserObj->recordset_list();

                                                $user_id = $rs_user[0]['iUserId'];

                                                //site status
                                                $task_type_name = trim($worksheet->getCell('D' . $row)->getValue());

                                                // echo "<br>".date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()))."<br>";
                                                /***********import  task_other*************/
                                                $typeid = '';
                                                if ($task_type_name != "")
                                                {
                                                    $typename = "Site Status - " . $task_type_name;
                                                    // type id
                                                    $TaskTypeObj->clear_variable();
                                                    $where_arr = array();
                                                    $join_fieds_arr = array();
                                                    $join_arr = array();
                                                    $where_arr[] = "task_type_mas.\"vTypeName\" = '" . $typename . "'";
                                                    $TaskTypeObj->join_field = $join_fieds_arr;
                                                    $TaskTypeObj->join = $join_arr;
                                                    $TaskTypeObj->where = $where_arr;
                                                    $TaskTypeObj->param['limit'] = " LIMIT 1";
                                                    $TaskTypeObj->setClause();
                                                    $rs_type = $TaskTypeObj->recordset_list();

                                                    $typeid = $rs_type[0]['iTaskTypeId'];
                                                }

                                                if ($typeid != "")
                                                {

                                                    //Add task other
                                                    $sql = "INSERT INTO task_other(\"iSiteId\", \"iSRId\", \"dDate\", \"dStartDate\",\"dEndDate\", \"iTaskTypeId\", \"tNotes\", \"dAddedDate\",\"dModifiedDate\",\"iUserId\") VALUES (" . gen_allow_null_char($siteid) . ", " . gen_allow_null_char('') . ", " . gen_allow_null_char($task_date) . ", " . gen_allow_null_char($task_startdate) . ", " . gen_allow_null_char($task_enddate) . ", " . gen_allow_null_char($typeid) . ", " . gen_allow_null_char('') . "," . gen_allow_null_char($addedDate) . "," . gen_allow_null_char($modifiedDate) . "," . gen_allow_null_int($user_id) . ")";

                                                    $sqlObj->Execute($sql);
                                                    $iTOId = $sqlObj->Insert_ID();
                                                    $tother++;
                                                }

                                                /*************Import Task_larval****************/

                                                $count = trim($worksheet->getCell('P' . $row)->getValue());
                                                $stages = trim($worksheet->getCell('U' . $row)->getValue());
                                                $bAdult = (strtolower($worksheet->getCell('U' . $row)->getValue()) == "yes") ? '1' : '';
                                                $iDips = '1';
                                                $bInstar2 = '';
                                                $bInstar3 = '';
                                                $bInstar4 = '';
                                                //$bAdult = '' ;
                                                

                                                if ($stages == "2nd" || $stages == "Multi")
                                                {
                                                    $bInstar2 = '1';
                                                }

                                                if ($stages == "3rd" || $stages == "Multi")
                                                {
                                                    $bInstar3 = '1';
                                                }

                                                if ($stages == "4th" || $stages == "Multi")
                                                {
                                                    $bInstar4 = '1';
                                                }

                                                

                                                $sql = 'INSERT INTO task_larval_surveillance ("iSiteId", "iSRId", "iDips", "dDate", "dStartDate", "dEndDate", "iGenus", "iCount", "bEggs", "bInstar1", "bInstar2", "bInstar3", "bInstar4", "bPupae", "bAdult", "iGenus2", "iCount2", "bEggs2", "bInstar12", "bInstar22", "bInstar32", "bInstar42", "bPupae2", "bAdult2", "rAvgLarvel", "tNotes", "dAddedDate","dModifiedDate","iUserId") VALUES (' . gen_allow_null_int($siteid) . ', ' . gen_allow_null_int('') . ', ' . gen_allow_null_char($iDips) . ', ' . gen_allow_null_char($task_date) . ', ' . gen_allow_null_char($task_startdate) . ', ' . gen_allow_null_char($task_enddate) . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char($count) . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char($bInstar2) . ', ' . gen_allow_null_char($bInstar3) . ', ' . gen_allow_null_char($bInstar4) . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char($bAdult) . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char($count) . ', ' . gen_allow_null_char('') . ', ' . gen_allow_null_char($addedDate) . ', ' . gen_allow_null_char($modifiedDate) . ',' . gen_allow_null_int($user_id) . ')';
                                                $sqlObj->Execute($sql);
                                                $iTLSId = $sqlObj->Insert_ID();
                                                $tlar++;
                                            }
                                        }
                                    }

                                    //import task_wether data
                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());
                                        $longtitude = trim($worksheet->getCell('AZ' . $row)->getValue());
                                        $latitude = trim($worksheet->getCell('BA' . $row)->getValue());

                                        $lat = number_format($latitude, 6, '.', '');
                                         $long = number_format($longtitude, 6, '.', '');

                                      

                                         //$sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' AND \"vPointLatLong\" = ST_SetSRID(ST_MakePoint(".$lat.", ".$long ."), 4326)::geography LIMIT 1";
                                         $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' AND \"vPointLatLong\" = ST_GEOMFROMTEXT('POINT(" . $long . " " . $lat . ")', 4326)   LIMIT 1";
                                        $rs_site = $sqlObj->GetAll($sql_site);
                                        $siteid = $rs_site[0]['iSiteId'];
                                        if ($siteid != "")
                                        {

                                            $vCondition = trim($worksheet->getCell('T' . $row)->getValue());
                                            $wind = trim($worksheet->getCell('AD' . $row)->getValue());
                                            $iWindSpeed = preg_replace("/[^0-9]/", '', $wind);

                                            if ($vCondition != "" || $iWindSpeed != "")
                                            {
                                                /*$task_date = date('Y-m-d', strtotime($worksheet->getCell('E' . $row)->getValue()));
                                                $task_startdate = date('Y-m-d H:i:s', strtotime($worksheet->getCell('E' . $row)->getValue()));*/

                                                $task_date = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));
                                                $task_startdate = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('E' . $row)->getValue()));

                                                $task_enddate = date("Y-m-d H:i:s", strtotime($task_startdate . " +10 minutes"));

                                                $vWindDirection = "";
                                                foreach ($WindDirection_arr as $key => $value)
                                                {
                                                    if (strpos(strtolower($wind) , strtolower($value)) !== false)
                                                    {
                                                        $vWindDirection = $value;
                                                    }
                                                }

                                                $insert_arr = array(
                                                    "iSiteId" => $siteid,
                                                    "dDate" => $task_date,
                                                    "dStartDate" => $task_startdate,
                                                    "dEndDate" => $task_enddate,
                                                    "vCondition" => $vCondition,
                                                    "iWindSpeed" => $iWindSpeed,
                                                    "vWindDirection" => $vWindDirection
                                                );
                                                $TaskWeatherObj->clear_variable();
                                                $TaskWeatherObj->insert_arr = $insert_arr;
                                                $TaskWeatherObj->setClause();
                                                $iTWId = $TaskWeatherObj->add_records();
                                                $twind++;
                                            }
                                        }
                                    }

                                    //echo $tlar."=>222dsd";exit();
                                    if ($tlar > 0)
                                    {
                                        $jsonData['error'] = 0;
                                        $jsonData['msg'] = $tlar . " larval data imported sucessfully.";
                                    }
                                    else
                                    {
                                        $jsonData['error'] = 1;
                                        $jsonData['msg'] = $tlar . " larval data imported .";
                                    }

                                }
                                else
                                {
                                    $trprodar = array_column($rs_tprod_arr, 'vName');
                                    $json_data = array();
                                    $html = "";
                                    $valid_row = "";
                                    $invalid_row = "";
                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());
                                        $zone = trim($worksheet->getCell('A' . $row)->getValue());
                                        $siteType = trim($worksheet->getCell('C' . $row)->getValue());
                                        $productname = trim($worksheet->getCell('AE' . $row)->getValue());
                                        $productcode = trim($worksheet->getCell('AF' . $row)->getValue());

                                        //$html =" <p> <table width='100%' >";
                                        if (in_array($productname, $trprodar))
                                        {
                                            $json_data['valid_data'][] = array(
                                                'zone' => $zone,
                                                'sitename' => $sitename,
                                                'sitetype' => $siteType,
                                                'productname' => $productname,
                                                'productcode' => $productcode
                                            );
                                            /*$valid_row .="<tr>";
                                            $valid_row .="<td>".$zone."</td>";
                                            $valid_row .="<td>".$sitename."</td>";
                                            $valid_row .="<td>".$siteType."</td>";
                                            $valid_row .="<td>".$productname."</td>";
                                            $valid_row .="<td>".$productcode."</td>";
                                            $valid_row .= "</tr>";*/
                                        }
                                        else
                                        {
                                            $json_data['invalid_data'][] = array(
                                                'zone' => $zone,
                                                'sitename' => $sitename,
                                                'sitetype' => $siteType,
                                                'productname' => $productname,
                                                'productcode' => $productcode
                                            );
                                            $invalid_row .= "<tr>";
                                            $invalid_row .= "<td>" . $zone . "</td>";
                                            $invalid_row .= "<td>" . $sitename . "</td>";
                                            $invalid_row .= "<td>" . $siteType . "</td>";
                                            $invalid_row .= "<td>" . $productname . "</td>";
                                            $invalid_row .= "<td>" . $productcode . "</td>";
                                            $invalid_row .= "</tr>";
                                        }
                                        ///$html .= "</table></p>";
                                        
                                    }

                                    //mail
                                    $mail_body = "<p>Hello,</p>";

                                    $mail_body .= "<p>User tried to import larval data. Please check below records and details which have treatment products are missing.";

                                    $mail_body .= "<p>	File URL: " . $import_file_url . $file_name . "</p>";

                                    //$mail_body = "<p>Try to import larval file but gets some invalid records in ".$import_file_url.$file_name." file. Please try again after 24 hrs.</p>";
                                    if ($invalid_row != "")
                                    {
                                        $mail_body .= "<p><strong>Invalid Records</strong></p>";
                                        $mail_body .= "<p><table width='100%' border='1'>";
                                        $mail_body .= "<tr>";
                                        $mail_body .= "<td><strong>Zone</strong></td>";
                                        $mail_body .= "<td><strong>Premise Name</strong></td>";
                                        $mail_body .= "<td><strong>Premise Type</strong></td>";
                                        $mail_body .= "<td><strong>Product Name</strong></td>";
                                        $mail_body .= "<td><strong>Product Code</strong></td>";
                                        $mail_body .= "</tr>";
                                        $mail_body .= $invalid_row;
                                        $mail_body .= "</table></p>";

                                    }

                                    if ($mail_to != '')
                                    {
                                        //mailme($mail_to, $mail_subject, $mail_body, $mail_from, $mail_format, $mail_cc, $bcc = "");
                                        $send_mail = sendSMTPMail($mail_to, $mail_subject, $mail_body, $mail_format, $mail_cc, $bcc = "");

                                    }

                                    $jsonData['error'] = 1;
                                    $jsonData['msg'] = 'There are some new products which need to be added in System. Admin will add the data soon. Please try to add data after 24 hrs.';
                                    $jsonData['data'] = $json_data;
                                    $jsonData['error_flag'] = 2;
                                }
                            }
                            else
                            {
                                $jsonData['error'] = 1;
                                $jsonData['msg'] = MSG_IMPORT_ERROR . '-Missing Data';
                            }
                        }
                        else
                        {

                            $jsonData['error'] = 1;
                            $jsonData['msg'] = "Error - while uploading larval file due to headers are not matached with sample file";
                        }
                    }
                    else
                    {
                        $jsonData['error'] = 1;
                        $jsonData['msg'] = "Error - while uploading larval file has no records";
                    }
                }
                else if ($cType == "sr")
                {	
					//echo $lastRow;exit;
                    if ($lastRow > 1)
                    {
                        $message = "";
                        $flag = 0;
                        //Validate the template is correct (check headers)
                        foreach ($import_sr_file_headers as $column => $headers)
                        {
                            if (trim($worksheet->getCell($column . '1')->getValue()) != trim($headers))
                            {
                                //echo $column."==>".$worksheet->getCell($column.'1')->getValue()."==>".$headers;
                                $flag = 1;
                                break;
                            }
                        }
						//echo $flag;exit;
                        if ($flag != 1)
                        {
                            $site_data_arrr = array();

                            $Site_TypeObj->clear_variable();
                            $where_arr = array();
                            $join_fieds_arr = array();
                            $join_arr = array();
                            $where_arr[] = "site_type_mas.\"vTypeName\" ILIKE 'Residential' ";
                            $Site_TypeObj->join_field = $join_fieds_arr;
                            $Site_TypeObj->join = $join_arr;
                            $Site_TypeObj->where = $where_arr;
                            $Site_TypeObj->param['order_by'] = '';
                            $Site_TypeObj->param['limit'] = ' LIMIT 1 ';
                            $Site_TypeObj->setClause();
                            $Site_TypeObj->debug_query = false;
                            $rs_sitetype = $Site_TypeObj->recordset_list();
                            $sitetypeid = (isset($rs_sitetype[0]['iSTypeId'])) ? $rs_sitetype[0]['iSTypeId'] : "0";

                            for ($row = 2;$row <= $lastRow;$row++)
                            {
                                $sitename = trim($worksheet->getCell('B' . $row)->getValue());
								//echo $sitename;exit;
                                if ($sitename != "")
                                {
									
                                    $sitetype = trim($worksheet->getCell('C' . $row)->getValue());
                                    $cityname = trim($worksheet->getCell('P' . $row)->getValue());
                                    $address1 = trim($worksheet->getCell('N' . $row)->getValue());
                                    $address2 = trim($worksheet->getCell('O' . $row)->getValue());
                                    $statename = trim($worksheet->getCell('Q' . $row)->getValue());
                                    $zipcode = trim($worksheet->getCell('R' . $row)->getValue());
                                    $longtitude = trim($worksheet->getCell('S' . $row)->getValue());
                                    $latitude = trim($worksheet->getCell('T' . $row)->getValue());

                                    //check site already exist or not
                                    //echo round($longtitude,6);exit();
                                    $lat = number_format($latitude, 6, '.', '');
                                    $long = number_format($longtitude, 6, '.', '');

									$sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' and \"iSTypeId\" = '" . $sitetypeid . "' AND \"vPointLatLong\" = ST_GEOMFROMTEXT('POINT(" . $long . " " . $lat . ")', 4326)   LIMIT 1";
                                    $rs_site = $sqlObj->GetAll($sql_site);
									/*echo $sql_site;
                                    echo "<pre>";print_r($rs_site);exit;*/
                                    if (count($rs_site) == 0)
                                    {

                                        //  echo "1111";exit();
                                        //if (!in_array(strtolower($sitename), $site_data_arrr)){
                                        $site_data_arrr[] = strtolower($sitename);

                                        $cityid = '';
                                        $stateid = '';
                                        $zipcodeid = '';
                                        $zoneId = '';
                                        $countyId = '';

                                        if ($cityname != "")
                                        {
                                            $sql = "SELECT \"iCityId\"  from city_mas where \"vCity\" ILIKE '" . $cityname . "' limit 1";
                                            $rs_city = $sqlObj->GetAll($sql);
                                            if (!empty($rs_city))
                                            {
                                                $cityid = $rs_city[0]['iCityId'];
                                            }
                                            else
                                            {
                                                $sql = " INSERT into city_mas (\"vCity\") values (" . gen_allow_null_char($cityname) . ")";
                                                $sqlObj->Execute($sql);
                                                $cityid = $sqlObj->Insert_ID();
                                            }
                                        }

                                        if ($statename != "")
                                        {
                                            $sql = "SELECT \"iStateId\"  from state_mas where \"vState\" ILIKE '" . $statename . "' limit 1";
                                            $rs_state = $sqlObj->GetAll($sql);

                                            $stateid = $rs_state[0]['iStateId'];
                                        }

                                        if ($zipcode != "")
                                        {
                                            $zipcode = (strlen($zipcode) < 5) ? str_pad($zipcode, 5, '0', STR_PAD_LEFT) : $zipcode;

                                            $sql = "SELECT \"iZipcode\"  from zipcode_mas where \"vZipcode\" ILIKE '" . $zipcode . "' limit 1";
                                            $rs_zipcode = $sqlObj->GetAll($sql);
                                            if (!empty($rs_zipcode))
                                            {
                                                $zipcodeid = $rs_zipcode[0]['iZipcode'];
                                            }
                                            else
                                            {
                                                $sql = " INSERT into zipcode_mas (\"vZipcode\") values (" . gen_allow_null_char($zipcode) . ")";
                                                $sqlObj->Execute($sql);
                                                $zipcodeid = $sqlObj->Insert_ID();

                                            }
                                        }

                                        $vPointLatLong = gen_allow_null_char('');
                                        $iGeometryType = 1; //Point
                                        $status = 1; //Active
                                        if ($long != '' && $lat != '')
                                        {
                                            $vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT(' . $long . ' ' . $lat . ')\', 4326)';

                                            $sql_zone = "SELECT zone.\"iZoneId\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(" . $long . " " . $lat . ")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'";

                                            $rs = $sqlObj->GetAll($sql_zone);

                                            if ($rs)
                                            {
                                                $zoneId = $rs[0]['iZoneId'];
                                            }

                                        }

                                        $sql_ins = 'INSERT INTO site_mas ("vName",  "iSTypeId",  "vAddress1", "vAddress2", "iZipcode", "iGeometryType", "iZoneId", "vLatitude", "vLongitude", "vNewLatitude", "vNewLongitude", "vPointLatLong","dAddedDate", "iStatus", "vLoginUserName", "iStateId", "iCountyId", "iCityId") VALUES (' . gen_allow_null_char($sitename) . ', ' . gen_allow_null_int($sitetypeid) . ', ' . gen_allow_null_char($address1) . ', ' . gen_allow_null_char($address2) . ', ' . gen_allow_null_int($zipcodeid) . ', ' . gen_allow_null_int($iGeometryType) . ', ' . gen_allow_null_int($zoneId) . ', ' . gen_allow_null_char($lat) . ', ' . gen_allow_null_char($long) . ', ' . gen_allow_null_int($lat) . ', ' . gen_allow_null_int($long) . ', ' . $vPointLatLong . ', ' . gen_allow_null_char(date_getSystemDateTime()) . ', ' . gen_allow_null_int($status) . ', ' . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]) . ', ' . gen_allow_null_char($stateid) . ', ' . gen_allow_null_char($countyId) . ', ' . gen_allow_null_char($cityid) . ')';
                                        //echo $sql_ins;exit();
                                        $sqlObj->Execute($sql_ins);
                                        $site_count++;
                                        //}
                                        
                                    }
                                }

                                //contact
                                $contactname = trim($worksheet->getCell('H' . $row)->getValue());

                                if ($contactname != "")
                                {
                                    //check contact already exist or not
                                    $sql_cnt = "SELECT \"iCId\" from contact_mas where (concat(trim(contact_mas.\"vFirstName\"), ' ', trim(contact_mas.\"vLastName\"))) ILIKE '" . pg_escape_string($contactname) . "' LIMIT 1";
                                    $rs_contact = $sqlObj->GetAll($sql_cnt);
                                    // echo $contactname."<pre>";print_r($rs_contact);
                                    if (count($rs_contact) == 0)
                                    {
                                        $conatct_name = explode(" ", $contactname, 2);
                                        $firstname = addslashes($conatct_name[0]);
                                        $lastname = addslashes($conatct_name[1]);

                                        //$sql = 'INSERT INTO contact_mas ("vFirstName","vLastName","iStatus","dAddedDate") values (' . gen_allow_null_char($conatct_name[0]) . ',' . gen_allow_null_char($conatct_name[1]) . ','.'1,'. gen_allow_null_char(date_getSystemDateTime()) . ')';
                                        $sql = 'INSERT INTO contact_mas ("vFirstName","vLastName","iStatus","dAddedDate") values (' . gen_allow_null_char($firstname) . ',' . gen_allow_null_char($lastname) . ',' . '1,' . gen_allow_null_char(date_getSystemDateTime()) . ')';

                                        $sqlObj->Execute($sql);
                                        $contact_count++;
                                    }

                                }
                            }
                            // }
                            //import sr details
                            $sr_import_count = 0;
                            for ($row = 2;$row <= $lastRow;$row++)
                            {
                                $contact_name = trim($worksheet->getCell('H' . $row)->getValue());
                                $address1 = trim($worksheet->getCell('N' . $row)->getValue());
                                $col_o = trim($worksheet->getCell('N' . $row)->getValue());
                                $col_data = explode("@", $col_o);
                                $address2 = $col_data[0];
                                $crossstreet = $col_data[1];
                                $tInternalNotes = trim($worksheet->getCell('J' . $row)->getValue());
                                $tRequestorNotes = trim($worksheet->getCell('I' . $row)->getValue());

                                $cityname = trim($worksheet->getCell('P' . $row)->getValue());
                                $statename = trim($worksheet->getCell('Q' . $row)->getValue());
                                $zipcode = trim($worksheet->getCell('R' . $row)->getValue());
                                $longtitude = trim($worksheet->getCell('S' . $row)->getValue());
                                $latitude = trim($worksheet->getCell('T' . $row)->getValue());

                                $added_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCell('D' . $row)->getValue())));
                                $added_time = date('H:i:s', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCell('E' . $row)->getValue())));

                                $close_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCell('F' . $row)->getValue())));
								$close_time = date('H:i:s', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCell('G' . $row)->getValue())));

                                $iLoginUserId = $_SESSION["sess_iUserId" . $admin_panel_session_suffix];

                                /* $lat = (strlen(substr($latitude,strpos($latitude,".")+1))>6)?round($latitude,6):$latitude;
                                        
                                $long =(strlen(substr($longtitude,strpos($longtitude,".")+1))>6)?round($longtitude,6):$longtitude;*/
                                $lat = number_format($latitude, 6, '.', '');
                                $long = number_format($longtitude, 6, '.', '');
                                


                                $cid = '';
                                $cityid = '';
                                $stateid = '';
                                $zipcodeid = '';
                                $zoneId = 1;
                                $countyId = "";

                                if ($contact_name != "")
                                {
                                    //$sql = "SELECT \"iCId\" from contact_mas where (concat(trim(contact_mas.\"vFirstName\"), ' ', trim(contact_mas.\"vLastName\"))) ILIKE '%" . addslashes($contact_name) . "%'  order by \"iCId\" desc LIMIT 1";
                                    $sql = "SELECT \"iCId\" from contact_mas where (concat(trim(contact_mas.\"vFirstName\"), ' ', trim(contact_mas.\"vLastName\"))) ILIKE '" . pg_escape_string($contact_name) . "'  order by \"iCId\" desc LIMIT 1";
                                    $rs_contact = $sqlObj->GetAll($sql);
                                    $cid = $rs_contact[0]['iCId'];
                                }

                                if ($cityname != "")
                                {
                                    $sql = "SELECT \"iCityId\"  from city_mas where \"vCity\" ILIKE '" . $cityname . "' limit 1";

                                    $rs_city = $sqlObj->GetAll($sql);
                                    if (!empty($rs_city))
                                    {
                                        $cityid = $rs_city[0]['iCityId'];
                                    }
                                    else
                                    {
                                        $sql = " INSERT into city_mas (\"vCity\") values (" . gen_allow_null_char($cityname) . ")";
                                        $sqlObj->Execute($sql);
                                        $cityid = $sqlObj->Insert_ID();
                                    }
                                }

                                if ($statename != "")
                                {
                                    $sql = "SELECT \"iStateId\"  from state_mas where \"vState\" ILIKE '" . $statename . "' limit 1";
                                    $rs_state = $sqlObj->GetAll($sql);

                                    $stateid = $rs_state[0]['iStateId'];
                                }

                                if ($zipcode != "")
                                {
                                    $zipcode = (strlen($zipcode) < 5) ? str_pad($zipcode, 5, '0', STR_PAD_LEFT) : $zipcode;

                                    $sql = "SELECT \"iZipcode\"  from zipcode_mas where \"vZipcode\" ILIKE '" . $zipcode . "' limit 1";
                                    $rs_zipcode = $sqlObj->GetAll($sql);
                                    if (!empty($rs_zipcode))
                                    {
                                        $zipcodeid = $rs_zipcode[0]['iZipcode'];
                                    }
                                    else
                                    {
                                        $sql = " INSERT into zipcode_mas (\"vZipcode\") values (" . gen_allow_null_char($zipcode) . ")";
                                        $sqlObj->Execute($sql);
                                        $zipcodeid = $sqlObj->Insert_ID();
                                    }
                                }

                                $vPointLatLong = gen_allow_null_char('');

                                if ($lat != '' && $long != '')
                                {
                                    $vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT(' . $long . ' ' . $lat . ')\', 4326)';

                                    $sql_zone = "SELECT zone.\"iZoneId\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(" . $long . " " . $lat . ")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'";
                                    $rs = $sqlObj->GetAll($sql_zone);

                                    if ($rs)
                                    {
                                        $zoneId = $rs[0]['iZoneId'];
                                    }

                                }
	
                                $AddedDate = date("Y-m-d H:i:s", strtotime($added_date . " " . $added_time));
								$modifiedDate = '';
								if ($close_date != "" && $close_time != "") {
									$modifiedDate = date("Y-m-d H:i:s", strtotime($close_date . " " . $close_time));
								}
								
                                $draft = '1';
                                $closed = '4';

                                if ($added_date != "" && $added_time != "" && $close_date != "" && $close_time != ""){
                                    $status = $closed;
                                }
                                else{
                                    $status = $draft;
                                }

                                $sql = "INSERT INTO sr_details (\"iCId\", \"vAddress1\", \"vAddress2\",\"vCrossStreet\", \"iZipcode\", \"iStateId\", \"iCountyId\", \"iCityId\", \"iZoneId\", \"vLatitude\", \"vLongitude\", \"tInternalNotes\", \"tRequestorNotes\", \"iStatus\", \"dAddedDate\" ,\"dModifiedDate\",\"iUserId\",\"bMosquitoService\") VALUES (" . gen_allow_null_char($cid) . ", " . gen_allow_null_char($address1) . ", " . gen_allow_null_char($address2) . ", " . gen_allow_null_char($crossstreet) . ", " . gen_allow_null_char($zipcodeid) . ", " . gen_allow_null_char($stateid) . ", " . gen_allow_null_char($countyId) . ", " . gen_allow_null_char($cityid) . ", " . gen_allow_null_char($zoneId) . ", " . gen_allow_null_char($lat) . ", " . gen_allow_null_char($long) . ", " . gen_allow_null_char($tInternalNotes) . ", " . gen_allow_null_char($tRequestorNotes) . ", " . gen_allow_null_char($closed) . ", " . gen_allow_null_char($AddedDate) . "," . gen_allow_null_char($modifiedDate) . "," . gen_allow_null_int($iLoginUserId) . ",TRUE" . ")";

                                $sqlObj->Execute($sql);

                                $iSRId = $sqlObj->Insert_ID();
                                $sr_import_count++;
                                if ($iSRId)
                                {
                                    $sr_sql = array();
                                    $iLoginUserId = $_SESSION["sess_iUserId" . $admin_panel_session_suffix];
                                    //draft
                                    $sr_sql[] = "(" . gen_allow_null_int($iSRId) . "," . $draft . ",  " . gen_allow_null_char($AddedDate) . ", " . gen_allow_null_int($iLoginUserId) . ")";

                                    if ($close_date != "" && $close_time != "")
                                    {
                                        $closetime = date("G:i:s", strtotime($close_time));
                                        $ClosedDate = date("Y-m-d H:i:s", strtotime($close_date . " " . $closetime));
                                    }
                                    else
                                    {
                                        $ClosedDate = $AddedDate;
                                    }
                                    //closed
                                    $sr_sql[] = "(" . gen_allow_null_int($iSRId) . "," . $closed . ",  " . gen_allow_null_char($ClosedDate) . ", " . gen_allow_null_int($iLoginUserId) . ")";

                                    $sqlsr = "INSERT INTO sr_status_history(\"iSRId\", \"iStatus\", \"dAddedDate\", \"iLoginUserId\") VALUES " . implode(",", $sr_sql);
                                    $sqlObj->Execute($sqlsr);

                                }
                                //echo $iSRId;exit();
                                
                            }

                            if ($sr_import_count > 0)
                            {
                                $jsonData['error'] = 0;
                                $jsonData['msg'] = $sr_import_count . " service request record imported successfully.";
                            }
                            else
                            {
                                $jsonData['error'] = 1;
                                $jsonData['msg'] = $sr_import_count . " service request record imported.";
                            }

                        }
                        else
                        {
                            $jsonData['error'] = 1;
                            $jsonData['msg'] = "Error - while uploading service request file due to headers are not matached with sample file";
                        }
                    }
                }
                else if ($cType == "adult")
                {

                    if ($lastRow > 1)
                    {
                        $flag = 0;
                        //Validate the template is correct (check headers)
                        foreach ($import_adult_file_headers as $column => $headers)
                        {
                            if (trim($worksheet->getCell($column . '1')->getValue()) != trim($headers))
                            {
                                $flag = 1;
                                break;
                            }
                        }

                        if ($flag != 1)
                        {
                            $treatment_prod_error = 0;
                            //Check AE Treatment product name are not blank
                            $treatment_product_col = $excelObj->setActiveSheetIndex(0)
                                ->rangeToArray('AL2:AL' . $lastRow);
                            //echo "<pre>";print_r($treatment_product_col);exit();
                            $treatment_product = array_filter(array_column($treatment_product_col, '0'));

                            if (count($treatment_product) > 0 && count($treatment_product) == count($treatment_product_col))
                            {
                                $treatment_product_arr = array_unique(array_filter($treatment_product));
                                //Get Treatment product id
                                $TProdObj->clear_variable();
                                $where_arr = array();
                                $join_fieds_arr = array();
                                $join_arr = array();
                                $where_arr[] = 'treatment_product."vName" IN (\'' . implode("','", $treatment_product_arr) . '\') ';;
                                $TProdObj->join_field = $join_fieds_arr;
                                $TProdObj->join = $join_arr;
                                $TProdObj->where = $where_arr;
                                $TProdObj->setClause();
                                $rs_tprod_arr = $TProdObj->recordset_list();
                                //$rs_tprod_data = $TProdObj->recordset_total();
                                //echo "<pre>";print_r($treatment_product_arr);exit();
                                //Check treatment product are present in treatment_product tables
                                if (count($rs_tprod_arr) != count($treatment_product_arr))
                                {
                                    $treatment_prod_error = 1;
                                }

                                if ($treatment_prod_error != 1)
                                {
                                    $rst = 0;
                                    $rut = 0;

                                    $treatment_count = 0;
                                    //Import all unique site
                                    $site_data_arrr = array();

                                    $Site_TypeObj->clear_variable();
                                    $where_arr = array();
                                    $join_fieds_arr = array();
                                    $join_arr = array();
                                    $where_arr[] = "site_type_mas.\"vTypeName\" ILIKE 'Mosquito Source' ";
                                    $Site_TypeObj->join_field = $join_fieds_arr;
                                    $Site_TypeObj->join = $join_arr;
                                    $Site_TypeObj->where = $where_arr;
                                    $Site_TypeObj->param['order_by'] = '';
                                    $Site_TypeObj->param['limit'] = '';
                                    $Site_TypeObj->setClause();
                                    $Site_TypeObj->debug_query = false;
                                    $rs_sitetype = $Site_TypeObj->recordset_list();

                                    $sitetype_id = $rs_sitetype[0]['iSTypeId'];

                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());

                                        $address1 = trim($worksheet->getCell('W' . $row)->getValue());
                                        $address2 = trim($worksheet->getCell('X' . $row)->getValue());
                                        $cityname = trim($worksheet->getCell('Y' . $row)->getValue());
                                        $statename = trim($worksheet->getCell('Z' . $row)->getValue());
                                        $zipcode = trim($worksheet->getCell('AA' . $row)->getValue());
                                        $longtitude = trim($worksheet->getCell('AB' . $row)->getValue());
                                        $latitude = trim($worksheet->getCell('AC' . $row)->getValue());

                                        /* $lat = (strlen(substr($latitude,strpos($latitude,".")+1))>6)?round($latitude,6):$latitude;
                                        
                                         $long =(strlen(substr($longtitude,strpos($longtitude,".")+1))>6)?round($longtitude,6):$longtitude;*/

                                        $lat = number_format($latitude, 6, '.', '');
                                        $long = number_format($longtitude, 6, '.', '');
                                   
                                        if ($sitename != "")
                                        {

                                            /* $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' and \"iSTypeId\" = '" . $sitetype_id . "' AND \"vPointLatLong\" = ST_SetSRID(ST_MakePoint(".$lat.", ".$long ."), 4326)::geography LIMIT 1";*/
                                             $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' and \"iSTypeId\" = '" . $sitetype_id . "' AND \"vPointLatLong\" = ST_GEOMFROMTEXT('POINT(" . $long . " " . $lat . ")', 4326)   LIMIT 1";
                                            $rs_site = $sqlObj->GetAll($sql_site);

                                            if (count($rs_site) == 0)
                                            {
                                                if (!in_array($sitename, $site_data_arrr))
                                                {
                                                    $site_data_arrr[] = $sitename;

                                                    $cityid = 0;
                                                    $stateid = 0;
                                                    $zipcodeid = 0;
                                                    $zoneId = 0;
                                                    $countyId = 0;

                                                    if ($cityname != "")
                                                    {
                                                        $sql = "SELECT \"iCityId\"  from city_mas where \"vCity\" ILIKE '" . $cityname . "' limit 1";
                                                        $rs_city = $sqlObj->GetAll($sql);
                                                        if (!empty($rs_city))
                                                        {
                                                            $cityid = $rs_city[0]['iCityId'];
                                                        }
                                                        else
                                                        {
                                                            $sql = " INSERT into city_mas (\"vCity\") values (" . gen_allow_null_char($cityname) . ")";
                                                            $sqlObj->Execute($sql);
                                                            $cityid = $sqlObj->Insert_ID();
                                                        }
                                                    }

                                                    if ($statename != "")
                                                    {
                                                        $sql = "SELECT \"iStateId\"  from state_mas where \"vState\" ILIKE '" . $statename . "' limit 1";
                                                        $rs_state = $sqlObj->GetAll($sql);

                                                        $stateid = $rs_state[0]['iStateId'];
                                                    }

                                                    if ($zipcode != "")
                                                    {
                                                        $zipcode = (strlen($zipcode) < 5) ? str_pad($zipcode, 5, '0', STR_PAD_LEFT) : $zipcode;

                                                        $sql = "SELECT \"iZipcode\"  from zipcode_mas where \"vZipcode\" ILIKE '" . $zipcode . "' limit 1";
                                                        $rs_zipcode = $sqlObj->GetAll($sql);
                                                        if (!empty($rs_zipcode))
                                                        {
                                                            $zipcodeid = $rs_zipcode[0]['iZipcode'];
                                                            //echo "111";exit();
                                                            
                                                        }
                                                        else
                                                        {
                                                            $sql = " INSERT into zipcode_mas (\"vZipcode\") values (" . gen_allow_null_char($zipcode) . ")";
                                                            $sqlObj->Execute($sql);
                                                            $zipcodeid = $sqlObj->Insert_ID();
                                                        }
                                                    }

                                                    $vPointLatLong = gen_allow_null_char('');
                                                    $iGeometryType = 1; //Point
                                                    $status = 1; //Active
                                                    if ($long != '' && $lat != '')
                                                    {
                                                        $vPointLatLong = 'ST_GEOMFROMTEXT(\'POINT(' . $long . ' ' . $lat . ')\', 4326)';

                                                        $sql_zone = "SELECT zone.\"iZoneId\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(" . $long . " " . $lat . ")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'";
                                                        $rs = $sqlObj->GetAll($sql_zone);

                                                        if ($rs)
                                                        {
                                                            $zoneId = $rs[0]['iZoneId'];
                                                        }

                                                    }

                                                    $sql_ins = 'INSERT INTO site_mas ("vName",  "iSTypeId",  "vAddress1", "vAddress2", "iZipcode", "iGeometryType", "iZoneId", "vLatitude", "vLongitude", "vNewLatitude", "vNewLongitude", "vPointLatLong","dAddedDate", "iStatus", "vLoginUserName", "iStateId", "iCountyId", "iCityId") VALUES (' . gen_allow_null_char($sitename) . ', ' . gen_allow_null_int($sitetype_id) . ', ' . gen_allow_null_char($address1) . ', ' . gen_allow_null_char($address2) . ', ' . gen_allow_null_int($zipcodeid) . ', ' . gen_allow_null_int($iGeometryType) . ', ' . gen_allow_null_int($zoneId) . ', ' . gen_allow_null_char($lat) . ', ' . gen_allow_null_char($long) . ', ' . gen_allow_null_char($lat) . ', ' . gen_allow_null_char($long) . ', ' . $vPointLatLong . ', ' . gen_allow_null_char(date_getSystemDateTime()) . ', ' . gen_allow_null_int($status) . ', ' . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]) . ', ' . gen_allow_null_char($stateid) . ', ' . gen_allow_null_char($countyId) . ', ' . gen_allow_null_char($cityid) . ')';
                                                    //echo "<br>".$sql_ins;exit();
                                                    $sqlObj->Execute($sql_ins);
                                                    $rst++;
                                                }
                                            }
                                        }
                                    }
                                    //get user data
                                    $user_col = $excelObj->setActiveSheetIndex(0)
                                        ->rangeToArray('AJ2:AJ' . $lastRow);

                                    $user_name = array_column($user_col, '0');
                                    $user_arr = array_unique(array_filter($user_name));

                                    //echo "<pre>";print_r($user_arr);exit();
                                    //Import user data
                                    if ($user_arr > 0)
                                    {
                                        foreach ($user_arr as $ukey => $uname)
                                        {
                                            if (trim($uname) != "")
                                            {

                                                $name = explode(" ", $uname, 2);

                                                $username = str_replace(' ', '', $uname);
                                                //echo $username;exit();
                                                $iUserId = "";
                                                $UserObj->user_clear_variable();
                                                //check user name duplication
                                                $where_arr = array();
                                                $where_arr[] = "user_mas.\"vUsername\" = '" . $username . "'";
                                                $where_arr[] = "user_mas.\"iAGroupId\" = '" . $access_group_id . "'";
                                                $UserObj->where = $where_arr;
                                                $UserObj->param['limit'] = " LIMIT 1";
                                                $UserObj->setClause();
                                                $rs_user = $UserObj->recordset_list();
                                                //print_r($rs_user);exit();
                                                if (count($rs_user) == 0)
                                                {
                                                    $insert_array = array(
                                                        "iAGroupId" => $access_group_id,
                                                        "iDepartmentId" => $department_id_arr,
                                                        "vFirstName" => addslashes($name[0]) ,
                                                        "vLastName" => addslashes($name[1]) ,
                                                        "vUsername" => addslashes($username) ,
                                                        "vPassword" => $encryptedPassword['encryptedPassword'],
                                                        "vFromIP" => getIP() ,
                                                        "iStatus" => '1',
                                                        "dDate" => date_getSystemDateTime() ,
                                                        "sSalt" => addslashes($encryptedPassword['salt']) ,
                                                    );

                                                    $UserObj->insert_arr = $insert_array;
                                                    $UserObj->setClause();
                                                    $iUserId = $UserObj->add_records();
                                                    $rut++;
                                                }
                                            }
                                        }
                                    }

                                    //Import task treatment
                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());
                                        $longtitude = trim($worksheet->getCell('AB' . $row)->getValue());
                                        $latitude = trim($worksheet->getCell('AC' . $row)->getValue());

                                        /* $lat = (strlen(substr($latitude,strpos($latitude,".")+1))>6)?round($latitude,6):$latitude;
                                        
                                         $long =(strlen(substr($longtitude,strpos($longtitude,".")+1))>6)?round($longtitude,6):$longtitude;*/

                                     

                                        $lat = number_format($latitude, 6, '.', '');
                                        $long = number_format($longtitude, 6, '.', '');

                                        //Get site id
                                       

                                       /* $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' and \"iSTypeId\" = '" . $sitetype_id . "' AND \"vPointLatLong\" = ST_SetSRID(ST_MakePoint(".$lat.", ".$long ."), 4326)::geography LIMIT 1";*/
                                        $sql_site = "SELECT \"iSiteId\" FROM site_mas WHERE \"vName\" ILIKE '" . $sitename . "' and \"iSTypeId\" = '" . $sitetype_id . "' AND \"vPointLatLong\" = ST_GEOMFROMTEXT('POINT(" . $long . " " . $lat . ")', 4326)   LIMIT 1";
                                        $rs_site = $sqlObj->GetAll($sql_site);

                                        $siteid = $rs_site[0]['iSiteId'];

                                        if ($siteid != "")
                                        {
                                            $treat_date = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('C' . $row)->getValue()));
                                            $treat_type = trim($worksheet->getCell('AE' . $row)->getValue());
                                            $treat_startdate = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('F' . $row)->getValue()));
                                            $treat_enddate = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('G' . $row)->getValue()));
                                            $treat_prod = trim($worksheet->getCell('AL' . $row)->getValue());
                                            $treat_area = trim($worksheet->getCell('I' . $row)->getValue());
                                            $treat_areatreated = (trim($worksheet->getCell('J' . $row)->getValue()) == 'Acres') ? 'acre' : 'sqft';
                                            $treat_amountapplied = trim($worksheet->getCell('M' . $row)->getValue());
                                            $unit_name = trim($worksheet->getCell('N' . $row)->getValue());

                                            $addedDate = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('C' . $row)->getValue()));
                                            $modifiedDate = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('C' . $row)->getValue()));

                                            $bJustification = (trim($worksheet->getCell('AF' . $row)->getValue()) == "Presence of Larvae") ? '1' : '0';

                                            $tComments = trim($worksheet->getCell('U' . $row)->getValue()) . " " . trim($worksheet->getCell('L' . $row)->getValue()) . " " . trim($worksheet->getCell('AW' . $row)->getValue()) . " " . trim($worksheet->getCell('AY' . $row)->getValue()) . " " . trim($worksheet->getCell('AZ' . $row)->getValue());

                                            $user_name = trim($worksheet->getCell('AJ' . $row)->getValue());

                                            //Get Treatment product id
                                            $TProdObj->clear_variable();
                                            $where_arr = array();
                                            $join_fieds_arr = array();
                                            $join_arr = array();
                                            $where_arr[] = 'treatment_product."vName" ILIKE \'' . $treat_prod . '\' ';;
                                            $TProdObj->join_field = $join_fieds_arr;
                                            $TProdObj->join = $join_arr;
                                            $TProdObj->where = $where_arr;
                                            $TProdObj->param['limit'] = " LIMIT 1";
                                            $TProdObj->setClause();
                                            $rs_tprod_data = $TProdObj->recordset_list();

                                            $tprod_id = $rs_tprod_data[0]['iTPId'];

                                            if (strtolower($unit_name) == "gallons")
                                            {
                                                $unit_name = "Gallon";
                                            }
                                            else if (strtolower($unit_name) == "briquets" || strtolower($unit_name) == "briquet" || strtolower($unit_name) == "others" || strtolower($unit_name) == "other" || strtolower($unit_name) == "pouches" || strtolower($unit_name) == "pouch")
                                            {
                                                $unit_name = "each";
                                            }
                                            else if (strtolower($unit_name) == "ounces" || strtolower($unit_name) == "ounce")
                                            {
                                                $tprod_iUId = $rs_tprod_data[0]['iUId'];
                                                if ($tprod_iUId > 0)
                                                {
                                                    $TProdObj->clear_variable();
                                                    $where_arr = array();
                                                    $join_fieds_arr = array();
                                                    $join_arr = array();
                                                    $where_arr[] = " unit_mas.\"iUId\" = '" . $tprod_iUId . "'";
                                                    $TProdObj->join_field = $join_fieds_arr;
                                                    $TProdObj->join = $join_arr;
                                                    $TProdObj->where = $where_arr;
                                                    $TProdObj->param['order_by'] = "unit_mas.\"iUId\" DESC";
                                                    $TProdObj->param['limit'] = "LIMIT 1";
                                                    $TProdObj->setClause();
                                                    $TProdObj->debug_query = false;
                                                    $rs_unit1 = $TProdObj->unit_data();
                                                    if ($rs_unit1 > 0)
                                                    {
                                                        $unit_parent_id = $rs_unit1[0]['iParentId'];
                                                        if ($unit_parent_id > 0)
                                                        {
                                                            $sql_u = 'SELECT "vUnit" FROM unit_mas WHERE "iUId" = ' . $unit_parent_id . ' LIMIT 1';
                                                            $rs_u = $sqlObj->GetAll($sql_u);
                                                            if (!empty($rs_u))
                                                            {
                                                                if ($rs_u[0]['vUnit'] == 'MASS')
                                                                {
                                                                    $unit_name = "ounce";
                                                                }
                                                                else if ($rs_u[0]['vUnit'] == 'VOLUME')
                                                                {
                                                                    $unit_name = "fluid ounce";
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            //echo $unit_name;exit;
                                            //Get Unit id
                                            $TProdObj->clear_variable();
                                            $where_arr = array();
                                            $join_fieds_arr = array();
                                            $join_arr = array();
                                            $where_arr[] = " unit_mas.\"vDescription\" ILIKE '" . $unit_name . "'";
                                            $TProdObj->join_field = $join_fieds_arr;
                                            $TProdObj->join = $join_arr;
                                            $TProdObj->where = $where_arr;
                                            $TProdObj->param['order_by'] = "unit_mas.\"iUId\" DESC";
                                            $TProdObj->param['limit'] = "LIMIT 1";
                                            $TProdObj->setClause();
                                            $TProdObj->debug_query = false;
                                            $rs_unit = $TProdObj->unit_data();

                                            $unit_id = $rs_unit[0]['iUId'];
                                            $username = str_replace(' ', '', $user_name);
                                            //Get User id
                                            $UserObj->user_clear_variable();
                                            $where_arr = array();
                                            $where_arr[] = "user_mas.\"vUsername\" = '" . $username . "'";
                                            $where_arr[] = "user_mas.\"iAGroupId\" = '" . $access_group_id . "'";
                                            $join_fieds_arr = array();
                                            $join_arr = array();
                                            $UserObj->join_field = $join_fieds_arr;
                                            $UserObj->join = $join_arr;
                                            $UserObj->where = $where_arr;
                                            $UserObj->param['limit'] = " LIMIT 1";
                                            $UserObj->setClause();
                                            $rs_user = $UserObj->recordset_list();

                                            $user_id = $rs_user[0]['iUserId'];

                                            $sql = "INSERT INTO task_treatment (\"iSiteId\", \"iSRId\", \"dDate\", \"vType\", \"dStartDate\",\"dEndDate\", \"iTPId\", \"vArea\", \"vAreaTreated\",\"vAmountApplied\",\"iUId\", \"dAddedDate\",\"dModifiedDate\",\"iUserId\",\"bJustification\",\"tComments\") VALUES (" . gen_allow_null_char($siteid) . ", " . gen_allow_null_char('') . ", " . gen_allow_null_char($treat_date) . ", " . gen_allow_null_char($treat_type) . ", " . gen_allow_null_char($treat_startdate) . ", " . gen_allow_null_char($treat_enddate) . ", " . gen_allow_null_char($tprod_id) . ", " . gen_allow_null_char($treat_area) . ", " . gen_allow_null_char($treat_areatreated) . ", " . gen_allow_null_char($treat_amountapplied) . "," . gen_allow_null_char($unit_id) . "," . gen_allow_null_char($addedDate) . "," . gen_allow_null_char($modifiedDate) . "," . gen_allow_null_int($user_id) . "," . gen_allow_null_char($bJustification) . "," . gen_allow_null_char($tComments) . ")";

                                            $sqlObj->Execute($sql);
                                            $iTreatmentId = $sqlObj->Insert_ID();
                                            $treatment_count++;
                                        }
                                    }

                                    if ($treatment_count > 0)
                                    {
                                        $jsonData['error'] = 0;
                                        $jsonData['msg'] = $treatment_count . " adult treatment record imported successfully.";
                                    }
                                    else
                                    {
                                        $jsonData['error'] = 1;
                                        $jsonData['msg'] = $treatment_count . " adult treatment record imported .";
                                    }

                                }
                                else
                                {
                                    $trprodar = array_column($rs_tprod_arr, 'vName');
                                    $json_data = array();
                                    $html = "";
                                    //$valid_row = "";
                                    $invalid_row = "";
                                    for ($row = 2;$row <= $lastRow;$row++)
                                    {
                                        $sitename = trim($worksheet->getCell('B' . $row)->getValue());
                                        $zone = trim($worksheet->getCell('A' . $row)->getValue());
                                        $productname = trim($worksheet->getCell('AL' . $row)->getValue());
                                        $productcode = trim($worksheet->getCell('AM' . $row)->getValue());

                                        //$html =" <p> <table width='100%' >";
                                        if (in_array($productname, $trprodar))
                                        {
                                            $json_data['valid_data'][] = array(
                                                'zone' => $zone,
                                                'sitename' => $sitename,
                                                'productname' => $productname,
                                                'productcode' => $productcode
                                            );

                                        }
                                        else
                                        {
                                            $json_data['invalid_data'][] = array(
                                                'zone' => $zone,
                                                'sitename' => $sitename,
                                                'productname' => $productname,
                                                'productcode' => $productcode
                                            );
                                            $invalid_row .= "<tr>";
                                            $invalid_row .= "<td>" . $zone . "</td>";
                                            $invalid_row .= "<td>" . $sitename . "</td>";
                                            $invalid_row .= "<td>" . $productname . "</td>";
                                            $invalid_row .= "<td>" . $productcode . "</td>";
                                            $invalid_row .= "</tr>";
                                        }
                                        ///$html .= "</table></p>";
                                        
                                    }

                                    //mail
                                    $mail_body = "<p>Hello,</p>";

                                    $mail_body .= "<p>User tried to import adult treatment data. Please check below records and details which have treatment products are missing.";

                                    $mail_body .= "<p>	File URL: " . $import_file_url . $file_name . "</p>";

                                    //$mail_body = "<p>Try to import larval file but gets some invalid records in ".$import_file_url.$file_name." file. Please try again after 24 hrs.</p>";
                                    if ($invalid_row != "")
                                    {
                                        $mail_body .= "<p><strong>Invalid Records</strong></p>";
                                        $mail_body .= "<p><table width='100%' border='1'>";

                                        $mail_body .= "<tr>";
                                        $mail_body .= "<td><strong>Zone</strong></td>";
                                        $mail_body .= "<td><strong>Premise Name</strong></td>";
                                        $mail_body .= "<td><strong>Product Name</strong></td>";
                                        $mail_body .= "<td><strong>Product Code</strong></td>";
                                        $mail_body .= "</tr>";
                                        $mail_body .= $invalid_row;
                                        $mail_body .= "</table></p>";

                                    }

                                    if ($mail_to != '')
                                    {
                                        //mailme($mail_to, $mail_subject, $mail_body, $mail_from, $mail_format, $mail_cc, $bcc = "");
                                        $send_mail = sendSMTPMail($mail_to, $mail_subject, $mail_body, $mail_format, $mail_cc, $bcc = "");
                                    }

                                    $jsonData['error'] = 1;
                                    $jsonData['msg'] = 'There are some new products which need to be added in System. Admin will add the data soon. Please try to add data after 24 hrs.';
                                    $jsonData['data'] = $json_data;
                                    $jsonData['error_flag'] = 2;
                                }
                            }
                            else
                            {

                                $jsonData['error'] = 1;
                                $jsonData['msg'] = MSG_IMPORT_ERROR . '-Missing Data';
                            }

                        }
                        else
                        {
                            $jsonData['error'] = 1;
                            $jsonData['msg'] = "Error - while uploading adult treatments file due to headers are not matached with sample file";
                        }
                    }
                    else
                    {
                        $jsonData['error'] = 1;
                        $jsonData['msg'] = "Error - while uploading adult treatments file has no records";
                    }
                }
                else
                {
                    $jsonData['error'] = 1;
                    $jsonData['msg'] = MSG_IMPORT_ERROR;
                }
            }
            catch(Exception $e)
            {
                echo 'Message: ' . $e->getMessage();
            }
        }
        else
        {
            $jsonData['error'] = 1;
            $jsonData['msg'] = $file_msg;
        }

    }
    else
    {
        $jsonData['error'] = 1;
        $jsonData['msg'] = MSG_IMPORT_ERROR . $file_msg;
    }

    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
    
}

// General Variables
$module_name = "Import File";

$smarty->assign("mode", $mode);

$smarty->assign("module_name", $module_name);
$smarty->assign("samplefiles_path", $samplefiles_path);
$smarty->assign("samplefiles_url", $samplefiles_url);

?>
