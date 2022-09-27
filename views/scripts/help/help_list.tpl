<div class="row  no-gutters w-100">
	<div class="col-12  align-self-center">
		<div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
			<div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
    </div>
  </div>
</div>
<div class="row help-list">
  {section name="result" loop=$result_arr}
  <div class="col-12 col-md-6 mt-3">
    <div class="card">
     <div class="card-header  justify-content-between align-items-center">
       <i class="{$result_arr[result].vIcon} text-primary"></i>
       <div class="wrap">
         <h4 class="card-title">{$result_arr[result].vHelpHeader}</h4> 
         <p>{$result_arr[result].vHelpDescription}</p>
       </div>
     </div>
     {section name="result2" loop=$result_arr2}
     {if $result_arr[result].iHHId eq $result_arr2[result2].iHHId}
     <div class="card-body">
       <div id="accordion{$result_arr2[result2].iHLId}" class="help-slide-list" role="tablist" {if $result_arr2[result2].totSlides  gt 0} data-target="#myModal" data-toggle="modal" {/if} data-id="accordion{$result_arr2[result2].iHLId}" data-title="{$result_arr2[result2].vHelpDetails}">
        <div class="mb-2">
          <h6 class="mb-0">
            <a class="text-uppercase d-block border-bottom" data-toggle="collapse" href="#collapse{$result_arr2[result2].iHLId}" aria-expanded="true" aria-controls="collapse">
              {$result_arr2[result2].vHelpDetails}
              {if $result_arr2[result2].totSlides  gt 0}
              <i class="fas fa-arrow-circle-right text-primary float-right"></i>
              {/if}
            </a>
          </h6>
        </div>
      </div>
    </div>
    {/if}   
    {/section}
  </div>
</div>
{/section}
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body p-0" id="accordion_details">
        Modal body text goes here.
      </div>
    </div>
  </div>
</div>
<script src="assets/js/app_js/help.js"></script>
