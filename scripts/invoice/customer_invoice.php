<?php
include_once($site_path . "scripts/session_valid.php");
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$iInvoiceId = $_REQUEST['iInvoiceId'];
//echo $iInvoiceId;exit;
if($iInvoiceId  > 0){
    $arr_param = array();
    $arr_param['iInvoiceId'] = $iInvoiceId;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."get_invoice_data_from_id.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));
    $response = curl_exec($ch);
    curl_close($ch);  
    $res = json_decode($response, true);
    $rs_invoice = $res['result']['data'];
    $invoice_lines_arr = $rs_invoice[0]['invoice_lines'];
    $cnt_invoice_lines = count($invoice_lines_arr);
    // echo "<pre>"; print_r($invoice_lines_arr);exit();

    if($rs_invoice) {
        $data = '
        <style>
        table.border{border:1px solid #c2c2c2;}
        table.border th{border:1px solid #c2c2c2;}
        table.border td{border:1px solid #c2c2c2;}
        </style>
        <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <tr>
                    <td colspan="3">
                        <table width="100%" cellpadding="2" cellspacing="0" border="0">
                        <tr>
                            <td width="80%"><img src="'.$site_logo.'" width="270" height="130"></td>
                            <td width="20%">
                                <div style="font-size:30px;font-weight:bold;">Invoice</div>
                                <div>A2D Inc<br/>55 Marietta Street, Suite 1800<br/>pH. 239-694-2174</div>
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>';
        $data .= '<tr>  
                    <td width="70%"><strong>Bill To: </strong></td>
                    <td width="15%"><strong>Invoice #: </strong></td>
                    <td width="15%" style="text-align: right;">'.$rs_invoice[0]['iInvoiceId'].'</td>
                </tr>';
        $data .= '<tr>  
                    <td>'.$rs_invoice[0]['vCompanyName'].'</td>
                    <td><strong>Invoice Date: </strong></td>
                    <td style="text-align: right;">'.date_display_report_date($rs_invoice[0]['dInvoiceDate']).'</td>
                </tr>';
        $data .= '<tr>  
                    <td>&nbsp;</td>
                    <td><strong>Payment Due: </strong></td>
                    <td style="text-align: right;">'.date_display_report_date($rs_invoice[0]['dPaymentDate']).'</td>
                </tr>'; 
                
        $data .= '<tr>  
                    <td>&nbsp;</td>
                    <td><strong>Billing Month: </strong></td>
                    <td style="text-align: right;">'.date("M", mktime(0, 0, 0, $rs_invoice[0]['iBillingMonth'], 10)).'-'.date("y", strtotime($rs_invoice[0]['iBillingYear'])).'</td>
                </tr>'; 
        $data .= '<tr><td colspan="3">&nbsp;</td></tr>';

        $totalNRCVariable = $totalMRCFixed = $iGrandTotal = 0;       
        $data .= '<tr>  
                    <td colspan="3">';
                    $data .= '
                        <table width="100%" style="border: 1px solid #c2c2c2;border-collapse: collapse;">
                            <tr>
                                <th width="10%" style="text-align: center;border: 1px solid #c2c2c2;">Premise</th>
                                <th width="17%" style="text-align: left;border: 1px solid #c2c2c2;">Type</th>
                                <th width="17%" style="text-align: left;border: 1px solid #c2c2c2;">Sub Type</th>
                                <th width="16%" style="text-align: left;border: 1px solid #c2c2c2;">Network</th>
                                <th width="10%" style="text-align: left;border: 1px solid #c2c2c2;">Service</th>
                                <th width="10%" style="text-align: right;border: 1px solid #c2c2c2;">Service Started</th>
                                <th width="10%" style="text-align: right;border: 1px solid #c2c2c2;">NRC</th>
                                <th width="10%" style="text-align: right;border: 1px solid #c2c2c2;">MRC</th>
                            </tr>';
                              
                    if($cnt_invoice_lines > 0){
                        for ($i=0; $i < $cnt_invoice_lines; $i++) { 
                                $totalNRCVariable += $invoice_lines_arr[$i]['iNRCVariable'];
                                $totalMRCFixed += $invoice_lines_arr[$i]['iMRCFixed'];
                            $data .= '<tr>
                                <td style="text-align: center;border: 1px solid #c2c2c2;">'.$invoice_lines_arr[$i]['iPremiseId'].'</td>
                                <td style="text-align: left;border: 1px solid #c2c2c2;">'.$invoice_lines_arr[$i]['vPremiseType'].'</td>
                                <td style="text-align: left;border: 1px solid #c2c2c2;">'.$invoice_lines_arr[$i]['vPremiseSubType'].'</td>
                                <td style="text-align: left;border: 1px solid #c2c2c2;">'.$invoice_lines_arr[$i]['vNetwork'].'</td>
                                <td style="text-align: left;border: 1px solid #c2c2c2;">'.$invoice_lines_arr[$i]['vServiceType'].'</td>
                                <td style="text-align: right;border: 1px solid #c2c2c2;">'.date_display_report_date($invoice_lines_arr[$i]['dStartDate']).'</td>
                                <td style="text-align: right;border: 1px solid #c2c2c2;">'.$invoice_lines_arr[$i]['iNRCVariable'].'</td>
                                <td style="text-align: right;border: 1px solid #c2c2c2;">'.$invoice_lines_arr[$i]['iMRCFixed'].'</td>
                            </tr>';
                        }
                    }else{
                        $data .= '<tr>
                                <td colspan="8">No Premises found!</td>
                            </tr>';
                    }
                 $data .= '</table>';
            $data .= '</td>
                </tr>';
            $iGrandTotal = $totalNRCVariable+$totalMRCFixed;
            $data .= '<tr><td colspan="3">&nbsp;</td></tr>';
            $data .= '<tr><td colspan="3">
            <table width="100%">';
                $data .= '<tr>
                    <td width="80%" style="text-align:right;"><strong>Sub Total: </strong></td>
                    <td width="10%" style="text-align:right;">'.$totalNRCVariable.'</td>
                    <td width="10%" style="text-align:right;">'.$totalMRCFixed.'</td>
                </tr>';
                $data .= '<tr><td colspan="3">&nbsp;</td></tr>';
                $data .= '<tr>
                        <td style="text-align:right;"><strong>Amount Due: </strong></td>
                        <td style="text-align:right;">'.$iGrandTotal.'</td>
                        <td style="text-align:right;"></td>
                    </tr>';
            $data .= '</td>
            </tr>';

            
        $data .= '<tr><td colspan="3">&nbsp;</td></tr>';           
        $data .= '<tr>
                <td colspan="3"><strong>Notes / Terms:</strong></td>
            </tr>';
        $data .= '<tr>
                <td colspan="3">'.$rs_invoice[0]['tNotes'].'</td>
            </tr>';
        $data .= '</table>';



        //echo $data;exit;
        $pdf_name = "Invoice-".$rs_invoice[0]['vPONumber'].".pdf";
        /*========== PDF Library ===============*/
        ob_clean();
        //echo $pdf_file_path.'tcpdf.php';exit;
        require_once($pdf_file_path.'config/lang/eng.php');
        require_once($pdf_file_path.'tcpdf.php');

        // Extend the TCPDF class to create custom Header and Footer
        class MYPDF extends TCPDF {
            
            // Page footer
            public function Footer() {
                /*$image_file = 'images/LeadingEdge_logo_160x60.png';
                $this->Image($image_file, 10, 10, 15, '', 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);*/
                // Position at 15 mm from bottom
                $this->SetY(-15);
                // Set font
                $this->SetFont('helvetica', 'I', 8);
                // Page number
                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }
        }

        // create new PDF document
        //$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);
        $pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);

        $pdf->setPrintHeader(false);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('helvetica', '', 10, '', true);


        $pdf->AddPage();

        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        //echo $html;exit;
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $data, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output($pdf_name, 'D');
    }
}