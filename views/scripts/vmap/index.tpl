<div class="loading">Loading&#8230;</div>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
/* Absolute Center Spinner */
.container-fluid {
    padding-left: 0px !important;
    padding-right: 0px !important;
}

.loading {
    display: none;
    position: fixed;
    z-index: 99999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

/* Transparent Overlay */
.loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

    background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}

.loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 150ms infinite linear;
    -moz-animation: spinner 150ms infinite linear;
    -ms-animation: spinner 150ms infinite linear;
    -o-animation: spinner 150ms infinite linear;
    animation: spinner 150ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
    box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-moz-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-o-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

.section-body {
    padding: 5px;
}

.card2 {
    float: left;
    width: 94%;
    margin: 0 5px;
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
}

.card3 {
    float: left;
    width: 5%;
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
}

.card4 {
    width: 100%;
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
}

.card5 {
    width: 100%;
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
}

ul,
#myUL {
    list-style-type: none;
}

#myUL {
    margin: 0;
    padding: 0;
}

.caret1 {
    margin-left: 25px;
}

.caret {
    cursor: pointer;
    -webkit-user-select: none;
    /* Safari 3.1+ */
    -moz-user-select: none;
    /* Firefox 2+ */
    -ms-user-select: none;
    /* IE 10+ */
    user-select: none;
}

.caret::before {
    content: "\25B6";
    color: black;
    display: inline-block;
    margin-right: 6px;
}

.caret-down::before {
    -ms-transform: rotate(90deg);
    /* IE 9 */
    -webkit-transform: rotate(90deg);
    /* Safari */
    '
    transform: rotate(90deg);
}

.nested {
    display: none;
}

.active {
    display: block;
}

.col-lg-3.col-md-6,
.col-lg-8.col-md-6,
.col-lg-1.col-md-6 {
    padding: 0 5px;
}

.sidenav {
    height: 635px;
    width: 0;
    position: fixed;
    z-index: 100;

    right: 79px;
    background-color: #fff;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 0px;
}

.sidenav a {
    padding: 0;
    text-decoration: none;
    font-size: 25px;
    color: #818181;
    display: block;
    transition: 0.3s;
}

.sidenav a:hover {
    color: #00865
}

.sidenav .closebtn {
    position: absolute;
    top: 0px;
    right: 20px;
    /* font-size: 36px; */
}

.main {
    transition: margin-left .5s;
    padding: 16px;
    text-align: center;
    position: relative;
    margin-bottom: 15px;
}

/*#main1 {
        transition: margin-left .5s;
        padding: 16px;
        margin-top: 50px;
        text-align: center;position: relative;
    }*/
.parent {
    margin-left: 20px;
    padding: 5px;
}

@media screen and (max-height: 450px) {
    .sidenav {
        padding-top: 15px;
    }

    .sidenav a {
        font-size: 18px;
    }
}

.myaccordion {
    margin: 10px auto;
    box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);
}

.myaccordion .card,
.myaccordion .card:last-child .card-header {
    border: none;
}

.myaccordion .card-header {
    border-bottom-color: #f0f2f3;
    background: #f0f2f3;
    margin: 5px;
    padding: 6px 10px !important;
}

.myaccordion .fa-stack {
    font-size: 15px;
}

.myaccordion .btn {
    width: 100%;
    font-weight: bold;
    color: #fff;
    padding: 0;
    border: none;
}

.myaccordion .btn-link:hover,
.myaccordion .btn-link:focus {
    text-decoration: none;
}

.myaccordion li+li {
    margin-top: 10px;
}

.card-heading {
    background: #eee;
    padding: 5px 10px;
    border-bottom: 1px solid #008651;
}

.card-heading p {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.8px;
}

.card-heading-inner {
    padding: 10px;
    /*overflow-x: auto;*/
    overflow-x: inherit;
    display: block;
    width: 100%;
}

.fa-inverse {
    color: #161616 !important;
}

p.area-text {

    transform: rotate(90deg);
    font-size: 24px;
    text-transform: uppercase;
    white-space: pre;
}

p.area-text1 {
    position: absolute;
    top: 207px;
    -webkit-right: -50px;
    right: -12px;
    transform: rotate(90deg);
    font-size: 24px;
    text-transform: uppercase;
    text-align: right;
}

p.area-text2 {
    position: absolute;
    top: 400px;
    -webkit-right: -50px;
    right: -24px;
    transform: rotate(90deg);
    font-size: 24px;
    text-transform: uppercase;
    text-align: right;
}

#map {
    height: 700px;
    width: 100%;
}

.remove_site_name {
    cursor: pointer;
    display: none;
    position: absolute;
    right: 15px;
    top: 13px;
    width: 12px;
}

.clear_address {
    right: 15px !important;
}
.no-gutters .main img{
    height:20px;
}


div.sitedivmsg{
    color: rgb(255,255,255);
}

@media screen and (max-width: 1024px) {
    .card2 {
        width: 92%;
    }

    element.style {}

    .card3 {
        float: right;
        width: 6%;
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
    }


    #main {
        transition: margin-left .5s;
        padding: 10px;
        text-align: center;
    }

    #main1 {
        transition: margin-left .5s;
        padding: 10px;
        margin-top: 60px;
        text-align: center;
    }

    #main2 {
        transition: margin-left .5s;
        padding: 10px;
        margin-top: 60px;
        text-align: center;
    }
    #main3{
        margin-top:55px;
    }

    p.area-text {
        position: absolute;
        top: 75px;
        right: -17px;
        transform: rotate(90deg);
        font-size: 24px;
        text-transform: uppercase;
        text-align: right;
    }

    p.area-text1 {
        right: -20px;
    }

    p.area-text2 {
        right: -20px;
    }
}
@media screen and (max-width: 767px) {
    .card2 {
        width: 75%;
    }

    element.style {}

    .card3 {
        float: right;
        width: 21%;
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
    }


    #main {
        transition: margin-left .5s;
        padding: 10px;
        text-align: center;
    }

    #main1 {
        transition: margin-left .5s;
        padding: 10px;
        margin-top: 60px;
        text-align: center;
    }

    #main2 {
        transition: margin-left .5s;
        padding: 10px;
        margin-top: 60px;
        text-align: center;
    }

    p.area-text {
        position: absolute;
        top: 75px;
        right: -5px;
        transform: rotate(90deg);
        font-size: 24px;
        text-transform: uppercase;
        text-align: right;
    }

    p.area-text1 {
        right: -5px;
    }

    p.area-text2 {
        right: -5px;
    }
    div#main3{
        margin-top:48px;
    }
    .no-gutters .main p{
        display:none;
    }
    .main{
        margin-top:20px !important;
    }
    .no-gutters .main img {
        height: 50px;
        
    }
}
</style>

<div class="section-body">
    <div class="container-fluid">
        <div class="row  no-gutters w-100">
            <div class="card2">
                <!-- <div class="card-heading">
                  <p>Locations</p>
              </div> -->
                <div class="card-heading-inner">
                    <div id="map"></div>
                </div>
            </div>
            <div class="card3">
                <div class="main" id="main">
                    <span style="font-size:30px;cursor:pointer; color: #000;" onclick="openNav()"><img src="/images/tool.png" /> <p class="area-text">Tools</p></span>
                </div>
                <div class="main" id="main1">
                    <span style="font-size:30px;cursor:pointer; color: #000;" onclick="openNav1()"><img src="/images/filter.png" /> <p class="area-text">Filters</p></span>
                </div>
                <div class="main" id="main2">
                    <span style="font-size:30px;cursor:pointer; color: #000;" onclick="openNav2()"><img src="/images/layers.png" /> <p class="area-text">Layers</p></span>
                </div>
                <div class="main" id="main3">
                    <span style="font-size:30px;cursor:pointer; color: #000;" onclick="openNav3()"><img src="/images/search.png" /> <p class="area-text">Search</p></span>
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
                                    <button class="d-flex align-items-center justify-content-between  btn btn-link" id="btn_map_addsite">
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
                        <li class="parent"><input class="form-check-input selectAllsType" type="checkbox" name="allSType" id="selectAllsType" value="no" /><span class="caret">Premise Types</span>
                            <ul class="nested">

                                {foreach from=$skSites item=site}
                                <li><input class="form-check-input selectSiteData selectAllsType" type="checkbox" name="sType[]" id="sType_{$site['iSTypeId']}" value="{$site['iSTypeId']}" />
                                    {if $site['site_sub_types']|count gt 0 }
                                    <span class="caret"><label class="form-check-label" for="site{$site['iSTypeId']}">{$site['vTypeName']}</label></span>
                                    <ul class="nested">
                                        {foreach from=$site['site_sub_types'] item=sSType}
                                        <li><input class="form-check-input selectSiteData" type="checkbox" id="sSType_{$sSType['iSSTypeId']}" name="sSType[]" value="{$site['iSTypeId']}|||{$sSType['iSSTypeId']}" />
                                            <label class="form-check-label" for="site{$sSType['iSSTypeId']}">{$sSType['vSubTypeName']}</label>
                                        </li>
                                        {/foreach}
                                    </ul>
                                    {else}
                                    <label class="form-check-label" for="site{$site['iSTypeId']}">{$site['vTypeName']}</label>
                                    {/if}
                                </li>
                                {/foreach}

                            </ul>
                        </li>
                        <li class="parent">
                            <input class="form-check-input selectAllsAttr" type="checkbox" name="sAllsAttr" id="selectAllsAttr" value="no" />
                            <span class="caret">Premise Attribute</span>
                            <ul class="nested">

                                {foreach from=$sAttrubutes item=sAttr}
                                <li><input class="form-check-input selectSiteData selectAllsAttr" type="checkbox" name="sAttr[]" id="sAttr_{$sAttr['iSAttributeId']}" value="{$sAttr['iSAttributeId']}" /> <label class="form-check-label" for="sAttr_{$sAttr['iSAttributeId']}">
                                        {$sAttr['vAttribute']}</label></li>
                                {/foreach}
                            </ul>
                        </li>
                        <li class="parent">
                            <input class="form-check-input selectAllCity" type="checkbox" name="sAllCity" id="selectAllCity" value="no" />
                            <span class="caret">Cities</span>
                            <ul class="nested">

                                {foreach from=$cityArr item=city}
                                <li><input class="form-check-input selectSiteData selectAllCity" type="checkbox" name="city[]" value="{$city['iCityId']}" id="city_{$city['iCityId']}" /> <label class="form-check-label" for="city_{$city['iCityId']}">
                                        {$city['vCity']}</label></li>
                                {/foreach}
                            </ul>
                        </li>
                        <li class="parent">
                            <input class="form-check-input selectAllZone" type="checkbox" name="sAllZone" id="selectAllZone" value="no" />
                            <span class="caret">Zones</span>
                            <ul class="nested">

                                {foreach from=$skZones item=zone}
                                <li><input class="form-check-input selectAllZone" type="checkbox" name="skZones[]" id="skZones_{$zone['iZoneId']}" value="{$zone['iZoneId']}" /><label class="form-check-label" for="skZones_{$zone['iZoneId']}">{$zone['vZoneName']}</label></li>
                                {/foreach}
                            </ul>
                        </li>
                        <li class="parrent ml-4">
                            <input class="form-check-input selectAllsType" type="checkbox" name="nearbysite" id="nearbysite" value="no" /><span >Nearby Sites</span>
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
                        <li class="parent"><input style="margin-left: -0.25rem;" class="form-check-input selectAllsServices" type="checkbox" name="selectAllsServices" id="selectAllsServices" value="services" /><label class="caret1" for="selectAllsServices">&nbsp;&nbsp;&nbsp;&nbsp;Service Request</label>
                        </li>
                        <li class="parent"><input style="margin-left: -0.25rem;" class="form-check-input selectAllslandingrate" type="checkbox" name="selectAllslandingrate" id="selectAllslandingrate" value="landing_rate" /><label class="caret1" for="selectAllslandingrate">&nbsp;&nbsp;&nbsp;&nbsp;Landing Rate</label>
                        </li>
                        <li class="parent"><input style="margin-left: -0.25rem;" class="form-check-input selectAllslarval" type="checkbox" name="selectAllslarval" id="selectAllslarval" value="larval" /><label class="caret1" for="selectAllslarval">&nbsp;&nbsp;&nbsp;&nbsp;Larval Data</label>
                        </li>
                        <li class="parent"><input style="margin-left: -0.25rem;" class="form-check-input selectAllspositive" type="checkbox" name="selectAllspositive" id="selectAllspositive" value="positive" /><label class="caret1" for="selectAllspositive">&nbsp;&nbsp;&nbsp;&nbsp;Positive</label>
                        </li>
                        {if $custLayers|@count gt 0}
                        <li class="parent"><input style="margin-left: -0.25rem;" class="form-check-input" type="checkbox" name="allCustLayer" id="selectAllCustLayer" value="no" />&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left: -0.25rem;" class="caret">Custom Layers</span>
                            <ul class="nested">
                                {foreach from=$custLayers item=custlayer}
                                <li><input class="form-check-input selectAllCustLayer" type="checkbox" name="custlayer[]" id="custlayer_{$custlayer['iCLId']}" value="{$custlayer['iCLId']}" />
                                    <label class="form-check-label" for="custlayer_{$custlayer['iCLId']}">&nbsp;&nbsp;{$custlayer['vName']}</label>
                                </li>
                                {/foreach}
                            </ul>
                        </li>
                        {/if}
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
                        <div class="col-12">
                            <form id="form">
                                <input type="hidden" name="vLatitude" id="vLatitude" value="">
                                <!-- <input type="hidden" name="vLongitude" id="vLongitude" value="-81.819111"> -->
                                <input type="hidden" name="vLongitude" id="vLongitude" value="">
                                <div class="form-group row">
                                    <label for="iSiteId" class="col-sm-4 col-form-label">Premise Id</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="iSiteId" class="form-control" id="iSiteId" placeholder="Premise Id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="vName" class="col-sm-4 col-form-label">Premise Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vName" class="form-control" id="vName" placeholder="Premise Name">
                                        <input type="hidden" id="serach_iSiteId" name="serach_iSiteId" value="">
                                        <img class="clear_site_address remove_site_name" id="clear_site_address_id" src="assets/images/icon-delete.png" onclick="return clear_site_address()" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="autofilladdress" class="col-sm-4 col-form-label">Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="autofilladdress" class="form-control" id="autofilladdress" placeholder="Address">
                                        <img class="clear_address" id="clear_address_id" src="assets/images/icon-delete.png" style="cursor:pointer;display:none" onclick="return clear_address()" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="iSRId" class="col-sm-4 col-form-label">SR Id</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="iSRId" class="form-control" id="iSRId" placeholder="SR Id">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

{include file="scripts/tasks/task_larval_surveillance_add.tpl"}
{include file="scripts/tasks/task_landing_rate_add.tpl"}
{include file="scripts/tasks/task_trap_add.tpl"}
{include file="scripts/tasks/task_other_add.tpl"}
{include file="scripts/tasks/task_treatment_add.tpl"}
{include file="scripts/premise/multiple_premise_add.tpl"}
<script type="text/javascript">
    var ajax_url = 'vmap/index?mode=List';
    var access_group_var_add = '{$access_group_var_add}';
    var access_group_var_CSV = '{$access_group_var_CSV}';
    var dDate = '{$dDate}';
    var dStartTime = '{$dStartTime}';
    var dEndTime = '{$dEndTime}';
    var tmpmode = '{$tmpmode}';
</script>
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<script src="assets/js/app_js/task_larval_surveillance_add.js"></script>
<script src="assets/js/app_js/task_landing_rate_add.js"></script>
<script src="assets/js/app_js/task_trap_add.js"></script>
<script src="assets/js/app_js/task_other_add.js"></script>
<script src="assets/js/app_js/task_treatment_add.js"></script>
<script src="assets/js/app_js/multiple_premise_add.js"></script>

<script>
    var toggler = document.getElementsByClassName("caret");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }
</script>

<script>
function openNav() {
    closeNav2();
    closeNav1();
    closeNav3();
    if (document.getElementById("main").style.cssText == "margin-right: 250px;") {
        closeNav();
    } else {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginRight = "250px";
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
    if (document.getElementById("main1").style.cssText == "margin-right: 250px;") {
        closeNav1();
    } else {
        document.getElementById("mySidenav1").style.width = "250px";
        document.getElementById("main1").style.marginRight = "250px";
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
    if (document.getElementById("main2").style.cssText == "margin-right: 250px;") {
        closeNav2();
    } else {
        document.getElementById("mySidenav2").style.width = "250px";
        document.getElementById("main2").style.marginRight = "250px";
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
    if (document.getElementById("main3").style.cssText == "margin-right: 250px;") {
        closeNav3();
    } else {
        document.getElementById("mySidenav3").style.width = "250px";
        document.getElementById("main3").style.marginRight = "250px";
    }
}

function closeNav3() {
    document.getElementById("mySidenav3").style.width = "0";
    document.getElementById("main3").style.marginRight = "0";
}
</script>
<script type="text/javascript">
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
let tmpsiteId_Arr = [];
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
let ENABLE_INSTA_TREATMENT = '{$ENABLE_INSTA_TREATMENT}';
let MAP_LONG = '{$MAP_LONGITUDE}';
let MAP_LAT = '{$MAP_LATITUDE}';
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization"></script>
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
var siteMarker = [];
var polygonObj = [];
var pCenterMarker = [];
var circleMarker = [];
var zonePolygonObj = [];
//var polyLineMarker, polygonMarker;
var polylineMarker = [];
var polylineCount  = 0;
var polygonMarker = [];
var polygonCount  = 0;
var polyLineObj = [];
var pline = 0;
var siteTypes = [];
var siteSubTypes = [];
var sAttr = [];
var skCity = [];
var skZones = [];
var clusterArr = [];
var siteInfoWindowTaskOtherArr = [];
var siteInfoWindowTaskLandingArr = [];
var siteInfoWindowTaskTrapArr = [];
var siteInfoWindowTaskLarvalArr = [];
var siteInfoWindowTaskTreatmentArr = [];

/*var landinglayerMarker = [];
var larvallayerMarker = [];
var positivelayerMarker = [];*/
var sitesearchMarker = [];
var siteserachData = [];
var sscount = 0;
var markerClusterSiteSerach;

var infowindow;
var contentString
var gmarkers = [];
var positivesiteMarker = [];
var pov = 0;
var srlayerMarker = [];
var srCount = 0;
var landingMarker = [];
var landCount = 0;
var larvMarker = [];
var larvCount = 0;

const imagePath = "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m";

//Create LatLngBounds object.
var latlngbounds = new google.maps.LatLngBounds();

var customeLayerArr = [];
var infowindow_customlayer;

var sitesrFilterMarker = [];

var siteNearDataMarker = [];
var sncount =0;

{/literal}
</script>

<script src="assets/js/mapjs/events.js"></script>
<script src="assets/js/mapjs/functions.js"></script>
<script src="assets/js/app_js/premise_google_autocomplete.js"></script>