<a class="btn btn-primary d-none" id="nearsr_modalbox" data-toggle="modal" href="#nearsrmodal">launch model</a>
<div class="modal fade " id="nearsrmodal" tabindex="-1" role="dialog" aria-labelledby="nearsrmodal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<h5 class="modal-title" id="nearsrmodaltitle">SR History {if isset($iSRId)}
                    #{$iSRId}
                    {else}
                    #New
                    {/if}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="nearsrclosestbox">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<!-- <div class="card-header justify-content-between align-items-center">                               
							<h4 class="card-title">SR Details</h4>
						</div> -->
						<div class="card-body">
							<div class="table-responsive">
								<table class="table layout-primary bordered" >
									<thead>
										<tr>
											<th scope="col" class="text-center" width="12%">Date</th>
											<th scope="col"  width="44%">Name</th>
											<th scope="col"  width="44%">Description</th>
										</tr>
									</thead>
									<tbody id="nearsr_tbody2">

									</tbody>
								</table> 
							</div>
						</div>
					</div> 

				</div>

			</div>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/js/app_js/sr_history.js"></script>