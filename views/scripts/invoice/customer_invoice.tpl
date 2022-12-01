<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name} Detail</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}invoice/invoice_list">{$module_name} List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table border="0" cellpadding="2" cellspacing="0" width="100%">
                            <tr>
                                <td colspan="3">
                                    <table width="100%" cellpadding="2" cellspacing="0" border="0">
                                        <tr>
                                            <td width="80%"><img src="{$site_logo}" width="270" height="130"></td>
                                            <td width="20%">
                                                <h4 class="card-title">Invoice</h4>
                                                <div>A2D Inc<br>55 Marietta Street, Suite 1800<br>pH. 239-694-2174</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>  
                                <td width="70%"><strong>Bill To: </strong></td>
                                <td width="15%"><strong>Invoice #: </strong></td>
                                <td width="15%" class="text-right">{$rs_invoice[0]['iInvoiceId']}</td>
                            </tr>
                            <tr>  
                                <td>Signal Point</td>
                                <td><strong>Invoice Date: </strong></td>
                                <td class="text-right">{$rs_invoice[0]['dInvoiceDate']|date_display_report_date}</td>
                            </tr>
                            <tr>  
                                <td>&nbsp;</td>
                                <td><strong>Payment Due: </strong></td>
                                <td class="text-right">{$rs_invoice[0]['dPaymentDate']|date_display_report_date}</td>
                            </tr>
                            <tr>  
                                <td>&nbsp;</td>
                                <td><strong>Billing Month: </strong></td>
                                <td class="text-right">{$vBillingMonth}</td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>  
                                <td colspan="3">
                                    <table class="table layout-primary bordered">
                                        <thead>
                                            <tr>
                                                <th width="10%" class="text-center">Premise</th>
                                                <th width="17%">Type</th>
                                                <th width="17%">Sub Type</th>
                                                <th width="16%">Network</th>
                                                <th width="10%">Service</th>
                                                <th width="10%" class="text-right">Service Started</th>
                                                <th width="10%" class="text-right">NRC</th>
                                                <th width="10%" class="text-right">MRC</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {if $cnt_invoice_lines gt 0}
                                            {section name="i" loop=$invoice_lines_arr}
                                            <tr>
                                                <td class="text-center">{$invoice_lines_arr[i]['iPremiseId']}</td>
                                                <td>{$invoice_lines_arr[i]['vPremiseType']}</td>
                                                <td>{$invoice_lines_arr[i]['vPremiseSubType']}</td>
                                                <td>{$invoice_lines_arr[i]['vNetwork']}</td>
                                                <td>{$invoice_lines_arr[i]['vServiceType']}</td>
                                                <td class="text-right">{$invoice_lines_arr[i]['dStartDate']|date_display_report_date}</td>
                                                <td class="text-right">{$invoice_lines_arr[i]['iNRCVariable']}</td>
                                                <td class="text-right">{$invoice_lines_arr[i]['iMRCFixed']}</td>
                                            </tr>
                                            {/section}
                                        {else}
                                            <tr>
                                                <td colspan="8">No Premises found!</td>
                                            </tr>
                                        {/if}
                                        </tbody>
                                        
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table width="100%">
                                        <tr>
                                            <td width="80%" class="text-right"><strong>Sub Total: </strong></td>
                                            <td width="10%" class="text-right">{$sTotalNRCVariable}</td>
                                            <td width="10%" class="text-right">{$sTotalMRCFixed}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong>Amount Due: </strong></td>
                                            <td class="text-right">{$iSGrandTotal}</td>
                                            <td class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Notes / Terms:</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Credit card payments are subjected to an additional 4% processing fee   </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



