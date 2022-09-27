<?php

class Report { 
	function header()
	{
		global $site_url, $LOGO;
		$data = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><img src="'.$site_url.'images/lcmcd_logo.png" width="90"></td>
				<td></td>
			</tr>	
		</table>';
		return $data;
	}
	function footer()
	{
		global $COMPANY_COPYRIGHTS, $site_url;
		//<td align="left"><img src="'.$site_url.'images/LeadingEdge_logo_160x60.png"></td>
		$data = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left">&nbsp;</td>
				<td align="right">'.$COMPANY_COPYRIGHTS.'</td>
			</tr>	
		</table>';
		return $data;
	}
}
?>