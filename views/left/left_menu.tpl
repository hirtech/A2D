<!-- START: Main Menu-->
<div class="sidebar">
	<a href="#" class="sidebarCollapse float-right h6 dropdown-menu-right mr-2 mt-2 position-absolute d-block d-lg-none">
		<i class="icon-close"></i>
	</a>
	<!-- START: Logo-->
	<a href="{$site_url}" class="sidebar-logo d-flex">
		<img src="assets/images/logo.png" alt="logo" class="img-fluid mr-2"/>
		<!-- <span class="h5 align-self-center mb-0">Vector ERP</span>    -->     
	</a>
	<!-- END: Logo-->

	<!-- START: Menu-->

	<ul id="side-menu" class="sidebar-menu">
		{foreach from=$menu_arr key=k item=menu}
		{*	{if $menu.vName|@trim eq "Import" && $sess_ShowImport eq 0 }
				{assign var="showimport" value="d-none"}
			{else}
				{assign var="showimport" value=""}
			{/if} *}
		<li class="dropdown {$menu.vActiveClass}  {$showimport}"> <a href="{$menu.vURL}"><i class="{$menu.vIcon}"></i>{$menu.vName}</a>
			{if $menu.submenu|@count gt 0}
			<div>
				<ul class="sub-sidebar-menu">
					{foreach from=$menu.submenu item=val key=key}
					
					<li class="{$val.vActiveClass}">
						<a href="{$val.vURL}"><i class="{$val.vIcon}"></i> {$val.vName}</a>
					</li>
					{/foreach}
				</ul>
			</div>
			{/if}
		</li>
		{/foreach}
	</ul>
	<!-- END: Menu-->
</div>
<!-- END: Main Menu-->

<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('.sub-sidebar-menu li').each(function () {
    	//$(this).closest(".dropdown").removeClass("active");
        if ($(this).hasClass("active")) {
        	$(this).closest('div').addClass("active");
            $(this).closest('div').parent("li").addClass("active");
        } 
    });
});
{/literal}
</script>