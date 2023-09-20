<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 03 April 2017
 * File Name	= project_recomendation_view.php
 * Location		= -
*/
	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
							
	date_default_timezone_set("Asia/Jakarta"); 
	$myYear 		= date('y');
	$myMonth 		= date('m');
	$myDays 		= date('d');
	$myHours 		= date('H');
	$myMinutes 		= date('i');
	$mySeconds 		= date('s');
	$CreaterNm 		= getenv("username");
	$CreaterNm2		= str_replace('$', '', $CreaterNm);
	$localIP 		= getHostByName(php_uname('n'));
	$localIP2 		= str_replace('.', '', $localIP);
	$Creater_Code	= "TR$myMonth$myYear$myDays$myHours$myMinutes$mySeconds";

	$REC_CODE 			= $default['REC_CODE'];
	$REC_NO 			= $default['REC_NO'];
	$DocNumber			= $REC_CODE;
	$REC_PAGE_NO 		= $default['REC_PAGE_NO'];
	$REC_PRJNAME 		= $default['REC_PRJNAME'];
	$REC_VALUE 			= $default['REC_VALUE'];
	$REC_OWNER 			= $default['REC_OWNER'];
		$ownerName		= "";
			$sqlX 		= "SELECT own_Title, own_Name
							FROM tbl_owner WHERE own_Code = '$REC_OWNER'";
			$result 	= $this->db->query($sqlX)->result();
			foreach($result as $rowx) :
				$own_Title		= $rowx->own_Title;
				$own_Name		= $rowx->own_Name;
				if($own_Title != '')
				{
					$ownerName	= "$own_Title $own_Name";
				}
			endforeach;
	$REC_CURRENCY		= $default['REC_CURRENCY'];
	$REC_CONSULT_ARS 	= $default['REC_CONSULT_ARS'];
	$REC_CONSULT_QS 	= $default['REC_CONSULT_QS'];
	$REC_LOCATION 		= $default['REC_LOCATION'];			
	$REC_DATE 			= $default['REC_DATE'];			
	$REC_TEND_DATED		= $default['REC_TEND_DATE'];
	$REC_TEND_DATE 		= date('m/d/Y',strtotime($REC_TEND_DATED));
	$REC_FUNDSRC		= $default['REC_FUNDSRC'];
	$REC_PAY_SYS 		= $default['REC_PAY_SYS'];
	$REC_DP 			= $default['REC_DP'];
	$REC_TURNOVER 		= $default['REC_TURNOVER'];			
	$REC_EXP			= $default['REC_EXP'];
	$REC_BASCAPAB		= $default['REC_BASCAPAB'];
	$REC_FINCAPAB		= $default['REC_FINCAPAB'];
	$REC_DATEXECD 		= $default['REC_DATEXEC'];
	//$REC_DATEXEC 		= date('m/d/Y',strtotime($REC_DATEXECD));
	$REC_PQ_ESTIME 		= $default['REC_PQ_ESTIME'];
	$REC_TEND_ESTIME 	= $default['REC_TEND_ESTIME'];
	$REC_BIDDER 		= $default['REC_BIDDER'];
	$REC_BIDDER_QTY 	= $default['REC_BIDDER_QTY'];
	$REC_ESKAL_EST 		= $default['REC_ESKAL_EST'];
	$REC_CONCLUTION		= $default['REC_CONCLUTION'];
	$REC_CONC_TARGET 	= $default['REC_CONC_TARGET'];
	$REC_CONC_NOTES 	= $default['REC_CONC_NOTES'];
	$REC_NOTES 			= $default['REC_NOTES'];
	
	$REC_SIGN_USR_MRK	= $default['REC_SIGN_USR_MRK'];
	$REC_SIGN_USR_MRKD	=  date('Y-m-d',strtotime($REC_SIGN_USR_MRK));
	$REC_USR_MRK 		= $default['REC_USR_MRK'];
	$REC_USR_MRK_STAT	= $default['REC_USR_MRK_STAT'];
	
	$REC_SIGN_MNG_MRK	= $default['REC_SIGN_MNG_MRK'];
	$REC_SIGN_MNG_MRKD	=  date('Y-m-d',strtotime($REC_SIGN_MNG_MRK));
	$REC_MNG_MRK 		= $default['REC_MNG_MRK'];
	$REC_MNG_MRK_STAT	= $default['REC_MNG_MRK_STAT'];	
	
	$REC_SIGN_MNG_EST	= $default['REC_SIGN_MNG_EST'];
	$REC_SIGN_MNG_ESTD	=  date('Y-m-d',strtotime($REC_SIGN_MNG_EST));
	$REC_MNG_EST 		= $default['REC_MNG_EST'];
	$REC_MNG_EST_STAT	= $default['REC_MNG_EST_STAT'];	
	
	$REC_DIR_MRK 		= $default['REC_DIR_MRK'];
	$REC_PRESDIR 		= $default['REC_PRESDIR'];
	$REC_NOTES 			= $default['REC_NOTES'];
	$REC_STAT 			= $default['REC_STAT'];
	
	$REC_FUNDSRC_NOTE	= $default['REC_FUNDSRC_NOTE'];
	$REC_PAY_SYS_NOTE	= $default['REC_PAY_SYS_NOTE'];
	$REC_DP_NOTE		= $default['REC_DP_NOTE'];
	$REC_TURNOVER_NOTE	= $default['REC_TURNOVER_NOTE'];
	$REC_EXP_NOTE		= $default['REC_EXP_NOTE'];
	$REC_BASCAPAB_NOTE	= $default['REC_BASCAPAB_NOTE'];
	$REC_FINCAPAB_NOTE	= $default['REC_FINCAPAB_NOTE'];
	$REC_TIMEXEC_NOTE	= $default['REC_TIMEXEC_NOTE'];
	$REC_PQ_ESTIME_NOTE	= $default['REC_PQ_ESTIME_NOTE'];
	$REC_TEND_ESTIME_NOTE	= $default['REC_TEND_ESTIME_NOTE'];
	$REC_BIDDER_NOTE	= $default['REC_BIDDER_NOTE'];
	$REC_ESKAL_EST_NOTE	= $default['REC_ESKAL_EST_NOTE'];
	
	$DOK_NO 			= $default['DOK_NO'];
	$REVISI 			= $default['REVISI'];
	$AMAND 				= $default['AMAND'];
	$REC_CREATER 		= $default['REC_CREATER'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;
	
$this->load->helper('pdf_helper');
TCPDF();

/*$obj_pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$obj_pdf->SetTitle('Pdf Example');
$obj_pdf->SetHeaderMargin(30);
$obj_pdf->SetTopMargin(20);
$obj_pdf->setFooterMargin(20);
$obj_pdf->SetAutoPageBreak(true);
$obj_pdf->SetAuthor('Author');
$obj_pdf->SetDisplayMode('real', 'default');*/

$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$obj_pdf->SetCreator(PDF_CREATOR);
//$title = "Project Recomendation";
//$obj_pdf->SetTitle($title);
//$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING);
//$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$obj_pdf->SetDefaultMonospacedFont('helvetica');
//$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->setFontSubsetting(true);
$obj_pdf->AddPage();

ob_start();
    // we can have any view part here like HTML, PHP etc
	?>
    <table border="1" width="100%" style="font-size:8px; font-family:Arial, Helvetica, sans-serif" rules="all">
        <tr style="height:25px; vertical-align:middle">
            <th width="20%">KETERANGAN</th>
            <th width="56%">REKOMENDASI</th>
            <th width="24%">CATATAN</th>
        </tr>
        <tr valign="baseline">
          	<td style="height:25px; vertical-align:baseline" valign="baseline">&nbsp;1. &nbsp;&nbsp;Sumber Dana</td>
          	<td style="height:25px; vertical-align:middle">
                <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC1" value="1" <?php if($REC_FUNDSRC == 1) { ?> checked="checked" <?php } ?>>
                &nbsp;&nbsp;Tersedia (aman)&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC2" value="2" <?php if($REC_FUNDSRC == 2) { ?> checked="checked" <?php } ?>>
                &nbsp;&nbsp;Bertahap&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC3" value="3" <?php if($REC_FUNDSRC == 3) { ?> checked="checked" <?php } ?>>
                &nbsp;&nbsp;Meragukan
            </td>
          	<td>&nbsp;<?php echo "$REC_FUNDSRC = $REC_FUNDSRC_NOTE"; ?></td>
        </tr>
        <tr style="height:25px; vertical-align:middle">
          	<td>&nbsp;2. &nbsp;Sistem Pembayaran</td>
          	<td>
                <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS1" value="1" class="minimal" <?php if($REC_PAY_SYS == 1) { ?> checked <?php } ?> disabled>
                &nbsp;&nbsp;Progres Bulanan&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS2" value="2" class="minimal" <?php if($REC_PAY_SYS == 2) { ?> checked <?php } ?> disabled>
                &nbsp;&nbsp;Termin&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS3" value="3" class="minimal" <?php if($REC_PAY_SYS == 3) { ?> checked <?php } ?> disabled>
                &nbsp;&nbsp;Turnkey
            </td>
          	<td>&nbsp;<?php echo "$REC_FUNDSRC = $REC_FUNDSRC_NOTE"; ?></td>
        </tr>
    </table>
	<?php
    $content = ob_get_contents();
ob_end_clean();
$obj_pdf->writeHTML($content, true, true, true, true, '');
$obj_pdf->Output('output.pdf', 'I');
?>