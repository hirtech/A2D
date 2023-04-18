<div class="loading">Loading&#8230;</div>
<div class="section-body">
    <div class="container-fluid">
        <div class="row  no-gutters w-100">
            <div class="card2">
                <div class="card-heading-inner">
                    <div id="map"></div>
                </div>
            </div>
            <div class="card3">
                <div class="main" id="main1">
                    <span style="cursor:pointer;" onclick="openNav1()"><img src="{$site_url}images/filter.png" alt="Filters" /><p class="area-text">&nbsp;Filters</p></span>
                </div>
                <div class="main" id="main2">
                    <span style="cursor:pointer;" onclick="openNav2()"><img src="{$site_url}images/layers.png" alt="Layers"/><p class="area-text">&nbsp;Layers</p></span>
                </div>
                <div class="main" id="main">
                    <span style="cursor:pointer;" onclick="openNav()"><img src="{$site_url}images/tool.png" alt="Tools"/><p class="area-text">&nbsp;Tools</p></span>
                </div>
                <div class="main" id="main3">
                    <span style="cursor:pointer;" onclick="openNav3()"><img src="{$site_url}images/search.png" alt="Search"/><p class="area-text">&nbsp;Search</p></span>
                </div>
            </div>
            <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="card-heading">
                    <p>Map Tools</p>
                </div>
                <div id="accordion" class="myaccordion">
                    <div class="card4">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="d-flex align-items-center justify-content-between btn btn-link collapsed tools-collapse" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Distance
                                    <span class="fa-stack fa-sm">
                                        <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                    </span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form method="POST" action="#">
                                    <div class="form-group">
                                        <input type="checkbox" class="map-tool-checkbox" id="showDistance" name="showDistance" /> Draw Polyline
                                        <input type="text" name="distanceinmiles" id="distanceinmiles" class="form-control" placeholder="Length in ft" readonly />
                                        <input type="text" name="distanceinft" id="distanceinft" class="form-control" placeholder="Length in mile" readonly />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card4">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="d-flex align-items-center justify-content-between btn btn-link collapsed tools-collapse" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Area
                                    <span class="fa-stack fa-sm">
                                        <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                    </span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form method="POST" action="#">
                                    <div class="form-group">
                                        <input type="checkbox" class="map-tool-checkbox" id="showArea" name="showArea" /> Draw Polygon
                                        <input type="text" name="areainft" id="areainft" class="form-control" placeholder="Area in sq. ft" readonly />
                                        <input type="text" name="areainmiles" id="areainmiles" class="form-control" placeholder="Area in sq. mile" readonly />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card4">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="d-flex align-items-center justify-content-between btn btn-link collapsed tools-collapse" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Circle
                                    <span class="fa-stack fa-sm">
                                        <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                    </span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form method="POST" action="#">
                                    <div class="form-group">
                                        <input type="checkbox" class="map-tool-checkbox" id="showCircle" name="showCircle" /> Draw Circle
                                        <input type="text" name="rCircle" id="rCircle" class="form-control" placeholder="Radius of circle" readonly />
                                        <input type="text" name="areaCircle" id="areaCircle" class="form-control" placeholder="Area of Circle" readonly />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card4">
                            <div class="card-header" >
                                <h2 class="mb-0">
                                    <button class="d-flex align-items-center justify-content-between btn btn-link" id="btn_map_addsite">
                                        Add Premise
                                         <span class="fa-stack fa-sm">
                                            <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </button>
                                </h2>
                                <div id="sitedivmsg" class="d-none sitedivmsg">
                                    <center>Click the spot on the map where you want this premise placed.</center>
                                </div>
                            </div>
                        </div>
                        <div class="card4">
                            <div class="card-header" >
                                <h2 class="mb-0">
                                    <button class="d-flex align-items-center justify-content-between  btn btn-link" id="btn_map_addbatchsite">Batch-create Premises <span class="fa-stack fa-sm"><i class="fa fa-plus fa-stack-1x fa-inverse"></i></span></button>
                                </h2>
                                <div id="batchsitedivmsg" class="d-none batchsitedivmsg">
                                    <center>Click the spot on the map where you want this premise placed.</center>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="mySidenav1" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav1()">&times;</a>
                <div class="card-heading">
                    <p>Filters</p>
                </div>
                <div class="card-heading-inner">
                    <ul id="myUL">
                        <li class="parent">
                            <input class="form-check-input selectAllNetwork" type="checkbox" name="sAllNetwork" id="selectAllNetwork" value="no" />
                            <span class="caret">Networks</span>
                            <ul class="nested">
                                {section name="n" loop=$networkArr}
                                {if $networkArr[n].iNetworkId|in_array:$user_networks}
                                    {assign var=networkSelected value=true}
                                {else}
                                    {assign var=networkSelected value=false}
                                {/if}
                                <li><input class="form-check-input selectAllNetwork" type="checkbox" name="sNetwork[]" id="sNetwork_{$networkArr[n].iNetworkId}" value="{$networkArr[n].iNetworkId}" {if $networkSelected} checked="checked" {/if} />{$networkArr[n].vName|gen_strip_slash}
                                </li>
                                {/section}
                            </ul>
                        </li>
                        <li class="parent">
                            <input class="form-check-input selectAllZone" type="checkbox" name="sAllZone" id="selectAllZone" value="no" />
                            <span class="caret">Fiber Zones</span>
                            <ul class="nested">
                                {foreach from=$skZones item=zone}
                                <li><input class="form-check-input selectAllZone" type="checkbox" name="skZones[]" id="skZones_{$zone['iZoneId']}" value="{$zone['iZoneId']}" /><label class="form-check-label" for="skZones_{$zone['iZoneId']}">{$zone['vZoneName']}</label></li>
                                {/foreach}
                            </ul>
                        </li>
                        <li class="parent">
                            <input class="form-check-input selectAllCity" type="checkbox" name="sAllCity" id="selectAllCity" value="no" />
                            <span class="caret">Cities</span>
                            <ul class="nested">
                                {foreach from=$cityArr item=city}
                                <li><input class="form-check-input selectAllCity" type="checkbox" name="city[]" value="{$city['iCityId']}" id="city_{$city['iCityId']}" /> <label class="form-check-label" for="city_{$city['iCityId']}">
                                        {$city['vCity']}</label></li>
                                {/foreach}
                            </ul>
                        </li>
                        <li class="parent">
                            <input class="form-check-input selectAllZipcode" type="checkbox" name="sAllZipcode" id="selectAllZipcode" value="no" />
                            <span class="caret">Zipcodes</span>
                            <ul class="nested">
                                {foreach from=$zipcodeArr item=zipcode}
                                <li><input class="form-check-input selectAllZipcode" type="checkbox" name="zipcode[]" value="{$zipcode['iZipcode']}" id="zipcode_{$zipcode['iZipcode']}" /> <label class="form-check-label" for="zipcode_{$zipcode['iZipcode']}">{$zipcode['vZipcode']}</label></li>
                                {/foreach}
                            </ul>
                        </li>
                    </ul>
                    <div class="distance">
                    </div>
                </div>
            </div>
            <div id="mySidenav2" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav2()">&times;</a>
                <div class="card-heading">
                    <p>Layers</p>
                </div>
                <div class="card-heading-inner">
                    <ul id="myUL">
                        {if $networkArr|@count gt 0}
                        <li class="parent"><input class="form-check-input" type="checkbox" name="allNetworkLayer" id="selectAllNetworkLayer" value="no" /><span class="caret">Networks</span>
                            <ul class="nested">
                                {section name="n" loop=$networkArr}
                                <li><input class="form-check-input selectAllNetworkLayer" type="checkbox" name="networkLayer[]" id="networkLayer_{$networkArr[n].iNetworkId}" value="{$networkArr[n].iNetworkId}" />
                                    <label class="form-check-label" for="networkLayer_{$networkArr[n].iNetworkId}">{$networkArr[n].vName|gen_strip_slash}</label>
                                </li>
                                {/section}
                            </ul>
                        </li>
                        {/if}
                        {if $zone_kml|@count gt 0}
                        <li class="parent"><input class="form-check-input" type="checkbox" name="allZoneLayer" id="selectAllZoneLayer" value="no" /><span class="caret">Zones</span>
                            <ul class="nested">
                                {foreach from=$zone_kml item=zone}
                                <li><input class="form-check-input selectAllZoneLayer" type="checkbox" name="zoneLayer[]" id="zoneLayer_{$zone['iZoneId']}" value="{$zone['iZoneId']}" />
                                    <label class="form-check-label" for="zoneLayer_{$zone['iZoneId']}">{$zone['vZoneName']}</label>
                                </li>
                                {/foreach}
                            </ul>
                        </li>
                        {/if}
                        {if $custLayers|@count gt 0}
                        <li class="parent"><input class="form-check-input" type="checkbox" name="allCustLayer" id="selectAllCustLayer" value="no" /><span class="caret">Custom KML</span>
                            <ul class="nested">
                                {foreach from=$custLayers item=custlayer}
                                <li><input class="form-check-input selectAllCustLayer" type="checkbox" name="custlayer[]" id="custlayer_{$custlayer['iCLId']}" value="{$custlayer['iCLId']}" />
                                    <label class="form-check-label" for="custlayer_{$custlayer['iCLId']}">&nbsp;&nbsp;{$custlayer['vName']}</label>
                                </li>
                                {/foreach}
                            </ul>
                        </li>
                        {/if}

                        <li class="parent"><input class="form-check-input" type="checkbox" name="allPremiseLayer" id="selectAllPremiseLayer" value="no" /><span class="caret">Premises</span>
                            <ul class="nested">
                                <li><input class="form-check-input selectAllPremiseLayer" type="checkbox" name="allPremiseStatus" id="selectAllpremiseStatusLayer" value="no" /><span class="caret">Premise Status</span>
                                    <ul class="nested">
                                        <li><input class="form-check-input selectAllpremiseStatusLayer" type="checkbox" name="premiseStatusLayer[]" id="premiseStatusLayer_0" value="0" /><label class="form-check-label" for="premiseStatusLayer_0">Off-Net</label></li>
                                        <li><input class="form-check-input selectAllpremiseStatusLayer" type="checkbox" name="premiseStatusLayer[]" id="premiseStatusLayer_1" value="1" /><label class="form-check-label" for="premiseStatusLayer_1">On-Net</label></li>
                                        <li><input class="form-check-input selectAllpremiseStatusLayer" type="checkbox" name="premiseStatusLayer[]" id="premiseStatusLayer_2" value="2" /><label class="form-check-label" for="premiseStatusLayer_2">Near-Net</label></li>
                                    </ul>    
                                </li>
                                <li><input class="form-check-input" type="checkbox" name="allSType" id="selectAllpremiseTypeLayer" value="no" /><span class="caret">Premise Types</span>
                                    <ul class="nested">
                                        {foreach from=$skSites item=site}
                                        <li><input class="form-check-input selectAllpremiseTypeLayer" type="checkbox" name="premiseTypeLayer[]" id="premiseTypeLayer_{$site['iSTypeId']}" value="{$site['iSTypeId']}" />
                                            {if $site['premise_sub_types']|count gt 0 }
                                            <span class="caret"><label class="form-check-label" for="site{$site['iSTypeId']}">{$site['vTypeName']}</label></span>
                                            <ul class="nested">
                                                {foreach from=$site['premise_sub_types'] item=sSType}
                                                <li><input class="form-check-input selectAllpremisesubTypeLayer premisesubTypeLayer_{$site['iSTypeId']}" type="checkbox" id="premisesubTypeLayer_{$sSType['iSSTypeId']}" name="premisesubTypeLayer[]" value="{$site['iSTypeId']}|||{$sSType['iSSTypeId']}" />
                                                    <label class="form-check-label" for="site{$sSType['iSSTypeId']}">{$sSType['vSubTypeName']}</label>
                                                </li>
                                                {/foreach}
                                            </ul>
                                            {else}
                                            <label class="form-check-label  selectAllpremiseTypeLayer" for="site{$site['iSTypeId']}">{$site['vTypeName']}</label>
                                            {/if}
                                        </li>
                                        {/foreach}
                                    </ul>
                                </li>
                                <li>
                                    <input class="form-check-input" type="checkbox" name="sAllsAttr" id="selectAllpremiseAttributeLayer" value="no" />
                                    <span class="caret">Premise Attributes</span>
                                    <ul class="nested">
                                        {foreach from=$sAttrubutes item=sAttr}
                                        <li><input class="form-check-input selectAllpremiseAttributeLayer" type="checkbox" name="premiseAttribute[]" id="premiseAttribute_{$sAttr['iSAttributeId']}" value="{$sAttr['iSAttributeId']}" /> <label class="form-check-label" for="premiseAttribute_{$sAttr['iSAttributeId']}">{$sAttr['vAttribute']}</label>
                                        </li>
                                        {/foreach}
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="parent"><input class="form-check-input" type="checkbox" name="allPremiseCircuitLayer" id="selectAllPremiseCircuitLayer" value="no" /><span class="caret">Premise Circuits</span>
                            <ul class="nested">
                                <li><input class="form-check-input selectAllPremiseCircuitLayer" type="checkbox" name="allPCircuitStatus" id="selectAllPCircuitStatusLayer" value="no" /><span class="caret">Status</span>
                                    <ul class="nested">
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_1" value="1" /><label class="form-check-label" for="pCircuitStatusLayer_1">Created</label></li>
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_2" value="2" /><label class="form-check-label" for="pCircuitStatusLayer_2">In Progress</label></li>
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_3" value="3" /><label class="form-check-label" for="pCircuitStatusLayer_3">Delayed</label></li>
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_4" value="4" /><label class="form-check-label" for="pCircuitStatusLayer_4">Connected</label></li>
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_5" value="5" /><label class="form-check-label" for="pCircuitStatusLayer_5">Active </label></li>
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_6" value="6" /><label class="form-check-label" for="pCircuitStatusLayer_6">Suspended </label></li>
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_7" value="7" /><label class="form-check-label" for="pCircuitStatusLayer_7">Trouble</label></li>
                                        <li><input class="form-check-input selectAllPCircuitStatusLayer" type="checkbox" name="pCircuitStatusLayer[]" id="pCircuitStatusLayer_8" value="8" /><label class="form-check-label" for="ppCircuitStatusLayer_8">Disconnected </label></li>
                                    </ul>    
                                </li>
                                <li><input class="form-check-input" type="checkbox" name="allPCircuitStatus" id="selectAllPCircuitCTLayer" value="no" /><span class="caret">Connection Type</span>
                                    <ul class="nested">
                                        {foreach from=$connection_types item=ctypes}
                                        <li><input class="form-check-input selectAllPCircuitCTLayer" type="checkbox" name="pCircuitcTypeLayer[]" id="pCircuitcTypeLayer_{$ctypes['iConnectionTypeId']}" value="{$ctypes['iConnectionTypeId']}" /> <label class="form-check-label" for="sAttr_{$ctypes['iConnectionTypeId']}">
                                             {$ctypes['vConnectionTypeName']}</label></li>
                                        {/foreach}
                                    </ul>    
                                </li>
                            </ul>
                        </li>

                        <li class="parent"><input class="form-check-input" type="checkbox" name="selectAllFiberInquiries" id="selectAllFiberInquiries" value="FiberInquiries" /><span for="selectAllFiberInquiries">Fiber Inquiries</span>
                        </li>
                        <li class="parent"><input class="form-check-input" type="checkbox" name="selectAllServiceOrders" id="selectAllServiceOrders" value="ServiceOrders" /><span for="selectAllServiceOrders">Service Orders</span>
                        </li>
                        <li class="parent"><input class="form-check-input" type="checkbox" name="selectAllWorkOrders" id="selectAllWorkOrders" value="selectAllWorkOrders" /><span for="selectAllWorkOrders">Work Orders</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="mySidenav3" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav3()">&times;</a>
                <div class="card-heading">
                    <p>Search</p>
                </div>
                <div class="card-heading-inner">
                    <div class="row">
                        <div class="col-12 search_menu">
                            <form id="form">
                                <input type="hidden" name="vLatitude" id="vLatitude" value="">
                                <input type="hidden" name="vLongitude" id="vLongitude" value="">
                                
                                <div class="form-group row">
                                    <label for="vName" class="col-sm-5 col-form-label">Premise ID</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="vName" class="form-control" id="vName" placeholder="Premise Name">
                                        <input type="hidden" id="serach_iPremiseId" name="serach_iPremiseId" value="">
                                        <img class="clear_site_address remove_site_name" id="clear_site_address_id" src="assets/images/icon-delete.png" onclick="return clear_site_address()" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="autofilladdress" class="col-sm-5 col-form-label">Premise Address</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="autofilladdress" class="form-control" id="autofilladdress" placeholder="Premise Address">
                                        <img class="clear_address" id="clear_address_id" src="assets/images/icon-delete.png" style="cursor:pointer;display:none;" onclick="return clear_address();" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="serach_fiber_inquiry" class="col-sm-5 col-form-label">Fiber Inquiry</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="serach_fiber_inquiry" class="form-control" id="serach_fiber_inquiry" placeholder="Fiber Inquiry Id OR address">
                                        <input type="hidden" id="serach_fiber_inquiry_id" name="serach_fiber_inquiry_id" value="">
                                        <img class="clear_fiberInquiry" id="clear_fiberInquiry_id" src="assets/images/icon-delete.png" onclick="return clear_fiberInquiry()" style="cursor:pointer;display:none;"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="search_iServiceOrderId" class="col-sm-5 col-form-label">Serviceorder Id</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="search_iServiceOrderId" class="form-control" id="search_iServiceOrderId" placeholder="Serviceorder Id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="search_iWorkOrderId" class="col-sm-5 col-form-label">Workorder Id</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="search_iWorkOrderId" class="form-control" id="search_iWorkOrderId" placeholder="Workorder Id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="search_iTroubleTicketId" class="col-sm-5 col-form-label">Trouble Ticket Id</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="search_iTroubleTicketId" class="form-control" id="search_iTroubleTicketId" placeholder="Trouble Ticket Id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="search_iMaintenanceTicketId" class="col-sm-5 col-form-label">Maintenance Ticket Id</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="search_iMaintenanceTicketId" class="form-control" id="search_iMaintenanceTicketId" placeholder="Maintenance Ticket Id">
                                    </div>
                                </div>                            
                                <div class="form-group row">
                                    <label for="search_iAwarenessId" class="col-sm-5 col-form-label">Awareness Task Id</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="search_iAwarenessId" class="form-control" id="search_iAwarenessId" placeholder="Awareness Task Id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="search_iEquipmentId" class="col-sm-5 col-form-label">Equipment Id</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="search_iEquipmentId" class="form-control" id="search_iEquipmentId" placeholder="Equipment Id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="search_iPremiseCircuitId" class="col-sm-5 col-form-label">Premise Circuit Id</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="search_iPremiseCircuitId" class="form-control" id="search_iPremiseCircuitId" placeholder="Premise Circuit Id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <input type="button" name="search" class="btn btn-primary" id="search_site_map" value="Search" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="button" name="reset" class="btn btn-danger" id="reset" value="Reset" onclick="return resetButton()" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{$site_url}assets/css/custom_map.css">
<script src="{$site_url}assets/js/mapjs/jquery_3_5_1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization&callback=Function.prototype"></script>
<script src="{$site_url}assets/js/mapjs/markerclustererplus/src/markerclusterer.js"></script>
<script src="{$site_url}assets/js/mapjs/oms.min.js"></script>
<script src="{$site_url}assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="{$site_url}assets/vendors/typeahead/typeahead.bundle.js"></script>

{include file="scripts/premise/multiple_premise_add.tpl"}
{include file="scripts/tasks/task_awareness_add.tpl"}

<script src="{$site_url}assets/js/app_js/multiple_premise_add.js"></script>
<script src="{$site_url}assets/js/app_js/task_awareness_add.js"></script>


<script type="text/javascript">
    var ajax_url = 'vmap/index?mode=List';
    var dDate = '{$dDate}';
    var dStartTime = '{$dStartTime}';
    var dEndTime = '{$dEndTime}';
</script>

<script>
var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
    toggler[i].addEventListener("click", function() {
        this.parentElement.querySelector(".nested").classList.toggle("active");
        this.classList.toggle("caret-down");
    });
}
function openNav() {
    closeNav2();
    closeNav1();
    closeNav3();
    if (document.getElementById("main").style.cssText == "margin-right: 350px;") {
        closeNav();
    } else {
        document.getElementById("mySidenav").style.width = "350px";
        document.getElementById("main").style.marginRight = "350px";
    }
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginRight = "0";
}

// For Navigation Filtter
function openNav1() {
    closeNav();
    closeNav2();
    closeNav3();
    if (document.getElementById("main1").style.cssText == "margin-right: 350px;") {
        closeNav1();
    } else {
        document.getElementById("mySidenav1").style.width = "350px";
        document.getElementById("main1").style.marginRight = "350px";
    }
}

function closeNav1() {
    document.getElementById("mySidenav1").style.width = "0";
    document.getElementById("main1").style.marginRight = "0";
}
// For Navigation Filtter
function openNav2() {
    closeNav();
    closeNav1();
    closeNav3();
    if (document.getElementById("main2").style.cssText == "margin-right: 350px;") {
        closeNav2();
    } else {
        document.getElementById("mySidenav2").style.width = "350px";
        document.getElementById("main2").style.marginRight = "350px";
    }
}

function closeNav2() {
    document.getElementById("mySidenav2").style.width = "0";
    document.getElementById("main2").style.marginRight = "0";
}

function openNav3() {
    closeNav();
    closeNav1();
    closeNav2();
    if (document.getElementById("main3").style.cssText == "margin-right: 350px;") {
        closeNav3();
    } else {
        document.getElementById("mySidenav3").style.width = "350px";
        document.getElementById("main3").style.marginRight = "350px";
    }
}

function closeNav3() {
    document.getElementById("mySidenav3").style.width = "0";
    document.getElementById("main3").style.marginRight = "0";
}

// For Navigation Accordion
$("#accordion").on("hide.bs.collapse show.bs.collapse", e => {
    $(e.target)
        .prev()
        .find("i:last-child")
        .toggleClass("fa-minus fa-plus");
});
let mode ="";
let currentlatitude="";
let currentlongitude="";
let stmeter = 804.672;
let tmppremiseId_Arr = [];
let tmpsrId_Arr = [];
let mathRandLat =(Math.random() / 10000);
let mathRandLng  =(Math.random() / 10000);
$(document).ready(function(){
    $.urlParam = function(name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        }
        return decodeURI(results[1]) || 0;
    }
     mode = $.urlParam('mode');
});
let MAP_LONG = '{$MAP_LONGITUDE}';
let MAP_LAT = '{$MAP_LATITUDE}';
var user_networks = {$user_networks|@json_encode};
</script>

<script>
{literal}
// This example creates a 2-pixel-wide red polyline showing the path of
// the first trans-Pacific flight between Oakland, CA, and Brisbane,
// Australia which was made by Charles Kingsford Smith.
var newPoly;
var map;
var m1;
var cm;
var cntrOfCircle, cityCircle;
var pl = 0;
var pCenter = 0;
var totalDistance = 0;
var cmCount = 0;
var pCount = 0;
var zCount = 0;
var zLayerCount = 0;
var siteMarker = [];
var polygonObj = [];
var pCenterMarker = [];
var circleMarker = [];
var zonePolygonObj = [];
var zonePolygonLayerObj = [];
//var polyLineMarker, polygonMarker;
var polylineMarker = [];
var polylineCount  = 0;
var polygonMarker = [];
var polygonCount  = 0;
var polyLineObj = [];
var pline = 0;
var skNetwork = [];
var skCity = [];
var skZones = [];
var skZipcode = [];
var clusterArr = [];
var siteInfoWindowTaskAwarenessArr = [];

/*var landinglayerMarker = [];
var larvallayerMarker = [];
var positivelayerMarker = [];*/
var sitesearchMarker = [];
var siteserachData = [];
var sscount = 0;
var markerClusterSiteSerach;
var siteMarkerCluster;
var fiberInquiryMarkerCluster;
var serviceOrderMarkerCluster;
var workOrderMarkerCluster;
var premiseCircuitMarkerCluster;

var infowindow;
var contentString
var gmarkers = [];
var positivesiteMarker = [];
var pov = 0;
var fiberInquirylayerMarker = [];
var fiberInquiryCount = 0;
var serviceOrderlayerMarker = [];
var serviceOrderCount = 0;
var workOrderlayerMarker = [];
var workOrderCount = 0;
var premiseCircuitlayerMarker = [];
var premiseCircuitCount = 0;
var landingMarker = [];
var landCount = 0;
var larvMarker = [];
var larvCount = 0;

const imagePath = "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m";

//Create LatLngBounds object.
var latlngbounds = new google.maps.LatLngBounds();

var networkFilterArr = [];
var infowindow_networkFilter;

var networkLayerArr = [];
var infowindow_networkLayer;

var zoneLayerArr = [];
var infowindow_zoneLayer;

var customeLayerArr = [];
var infowindow_customlayer;

var sitesrFilterMarker = [];

var siteNearDataMarker = [];
var sncount =0;
var defaultZoom = 9;
var markerSpiderfier = null;
{/literal}
</script>
<script src="{$site_url}assets/js/mapjs/events.js?ver=1.2"></script>
<script src="{$site_url}assets/js/mapjs/functions.js?ver=1.2"></script>
<script src="{$site_url}assets/js/app_js/premise_google_autocomplete.js"></script>
