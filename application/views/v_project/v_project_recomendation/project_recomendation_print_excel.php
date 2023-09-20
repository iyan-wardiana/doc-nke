<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 03 April 2017
 * File Name	= project_recomendation_view.php
 * Location		= -
*/

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=exceldata.xls");
header("Pragma: no-cache");
header("Expires: 0");
	
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'imagess/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">
@import url("<?php echo base_url() . 'css/style_table.css'; ?>");
</style>
<?php /*?><script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script><?php */?>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">
<style>
	.inplabel {border:none;background-color:white;}
	.inpdim {border:none;background-color:white;}
</style>
<table width="100%" border="0" style="size:auto">
    <tr style=" height:22px">
    	<td width="24%">
     		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>    	</div>       </td>
        <td width="56%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="20%" class="style2" style="text-align:center; font-size:24px">&nbsp;</td>
  	</tr>
    <tr>
        <td width="14%" style="vertical-align:middle">
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="160" height="40" />                            </td>
        <td width="60%" align="center" style="font-weight:bold"><span style="font-size:18px;">REKOMENDASI TENDER PROYEK</span><br>
    Rekom.No: <?php echo $REC_NO; ?><br></td>
        <td width="9%">
			<table width="100%" style="font-size:12px">
                <tr>
                    <td nowrap>DoK. No.</td>
                    <td>:</td>
                    <td>&nbsp;IQ210</td>
                </tr>
                <tr>
                    <td nowrap>Revisi</td>
                    <td>:</td>
                    <td nowrap>&nbsp;0(23/02/17)</td>
                </tr>
                <tr>
                    <td nowrap>Amand.</td>
                    <td>:</td>
                    <td>&nbsp;-</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
      <td style="vertical-align:middle">&nbsp;</td>
      <td nowrap>
        <span style="font-size:12px; font-style:italic">
        Note : - Form ini Wajib dipersiapkan dan diarsipkan (Asli) oleh Departemen Marketing<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- *) coret/ hilangkan  yang tidak perlu</span></label>;</td>
      <td>&nbsp;</td>
    </tr>
    <tr style="height:22px">
    	<td colspan="3" valign="middle" class="style2" style="text-align:left; font-weight:bold;"><hr /></td>
    </tr>
    
    <tr style=" height:22px">
		<td colspan="3" valign="top" class="style2">
            <table class="table" border="1" width="100%" style="font-size:12px; font-family:Arial, Helvetica, sans-serif" rules="all">
                        <tr style="font-weight:bold; height:22px">
                            <td width="13%" style="border-right-color:#FFFFFF;">&nbsp;PROYEK</td>
                            <td width="1%" style="border-right-color:#FFFFFF;">:</td>
                            <td colspan="2" style="border-right-color:#FFFFFF;"><?php echo $REC_PRJNAME; ?></td>
                            <td width="31%">&nbsp;</td>
                          <td width="22%">&nbsp;Nilai = + / -&nbsp;&nbsp;&nbsp;<?php echo $REC_CURRENCY; ?>&nbsp;<?php print number_format($REC_VALUE, $decFormat); ?></td>
                        </tr>
                        <tr style="font-weight:bold; height:22px">
                            <td style="border-right-color:#FFFFFF;"&nbsp;>OWNER</td>
                            <td style="border-right-color:#FFFFFF;">:</td>
                            <td colspan="4"><?php echo $ownerName; ?></td>
                        </tr>
                        <tr style="vertical-align:middle; font-weight:bold">
                            <td rowspan="2" style="vertical-align:middle">K O N S U T A N</td>
                            <td rowspan="2" style="vertical-align:middle; border-right-color:#FFFFFF;">:</td>
                            <td colspan="4">ARS : <?php echo $REC_CONSULT_ARS; ?></td>
                        </tr>
                        <tr style="font-weight:bold">
                          <td colspan="4">QS : <?php echo $REC_CONSULT_QS; ?></td>
                        </tr>
                        <tr style="font-weight:bold; height:22px">
                            <td style="border-right-color:#FFFFFF;">&nbsp;LOKASI</td>
                            <td style="border-right-color:#FFFFFF;">:</td>
                            <td colspan="4"><?php echo $REC_LOCATION; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="6">&nbsp;PERKIRAAN WAKTU TENDER TANGGAL : <?php echo date('Y-m-d',strtotime($REC_TEND_DATE)); ?></td>
                        </tr>
                        <tr style="height:25px; vertical-align:middle">
                            <th colspan="3">KETERANGAN</th>
                            <th colspan="2">REKOMENDASI</th>
                            <th>CATATAN</th>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3">&nbsp;1. &nbsp;&nbsp;Sumber Dana</td>
                            <td colspan="2">
                                <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC1" value="1" class="minimal" <?php if($REC_FUNDSRC == 1) { ?> checked <?php } ?> disabled>
                                &nbsp;&nbsp;Tersedia (aman)&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC2" value="2" class="minimal" <?php if($REC_FUNDSRC == 2) { ?> checked <?php } ?> disabled>
                                &nbsp;&nbsp;Bertahap&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC3" value="3" class="minimal" <?php if($REC_FUNDSRC == 3) { ?> checked <?php } ?> disabled>
                                &nbsp;&nbsp;Meragukan                        	</td>
                            <td>&nbsp;<?php echo $REC_FUNDSRC_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td style="border-right-color:#FFFFFF;">&nbsp;2. &nbsp;&nbsp;Sistem Pembayaran</td>
                            <td style="border-right-color:#FFFFFF;">&nbsp;</td>
                            <td width="6%">&nbsp;</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS1" value="1" class="minimal" <?php if($REC_PAY_SYS == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Progres Bulanan&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS2" value="2" class="minimal" <?php if($REC_PAY_SYS == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Termin&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS3" value="3" class="minimal" <?php if($REC_PAY_SYS == 3) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Turnkey                      	</td>
                            <td>&nbsp;<?php echo $REC_PAY_SYS_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td style="border-right-color:#FFFFFF;">&nbsp;3. &nbsp;&nbsp;Uang Muka</td>
                            <td style="border-right-color:#FFFFFF;">&nbsp;</td>
                            <td width="6%">&nbsp;</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_DP" id="REC_DP1" value="1" class="minimal" <?php if($REC_DP == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="REC_DP" id="REC_DP2" value="2" class="minimal" <?php if($REC_DP == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Tidak Ada                      	</td>
                            <td>&nbsp;<?php echo $REC_DP_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;4. &nbsp;&nbsp;Persyaratan Utama PQ</td>
                            <td colspan="2">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) Turn Over</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_TURNOVER" id="REC_TURNOVER1" value="1" class="minimal" <?php if($REC_TURNOVER == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;
                            <input type="checkbox" name="REC_TURNOVER" id="REC_TURNOVER2" value="2" class="minimal" <?php if($REC_TURNOVER == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;                      	</td>
                            <td>&nbsp;<?php echo $REC_TURNOVER_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) Pengalaman sejenis</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_EXP" id="REC_EXP1" value="1" class="minimal" <?php if($REC_EXP == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;
                            <input type="checkbox" name="REC_EXP" id="REC_EXP2" value="2" class="minimal" <?php if($REC_EXP == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;                      	</td>
                            <td>&nbsp;<?php echo $REC_EXP_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c) Kemampuan dasar</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_BASCAPAB" id="REC_BASCAPAB1" value="1" class="minimal" <?php if($REC_BASCAPAB == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;
                            <input type="checkbox" name="REC_BASCAPAB" id="REC_BASCAPAB2" value="2" class="minimal" <?php if($REC_BASCAPAB == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;                        </td>
                            <td>&nbsp;<?php echo $REC_BASCAPAB_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d) Kemampuan keuangan</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_FINCAPAB" id="REC_FINCAPAB1" value="1" class="minimal" <?php if($REC_FINCAPAB == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;
                            <input type="checkbox" name="REC_FINCAPAB" id="REC_FINCAPAB2" value="2" class="minimal" <?php if($REC_FINCAPAB == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;                        </td>
                            <td>&nbsp;<?php echo $REC_FINCAPAB_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;5. &nbsp;&nbsp;Waktu Pelaksanaan</td>
                            <td colspan="2">&nbsp;<?php echo $REC_DATEXECD; ?> hari</td>
                            <td>&nbsp;<?php echo $REC_TIMEXEC_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;6. &nbsp;&nbsp;Perkiraan waktu PQ</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME1" value="1" class="minimal" <?php if($REC_PQ_ESTIME == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;
                            <input type="checkbox" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME2" value="2" class="minimal" <?php if($REC_PQ_ESTIME == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;
                            <input type="checkbox" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME3" value="3" class="minimal" <?php if($REC_PQ_ESTIME == 3) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Mendesak                        </td>
                            <td>&nbsp;<?php echo $REC_PQ_ESTIME_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;7. &nbsp;&nbsp;Perkiraan waktu Tender</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME1" value="1" class="minimal" <?php if($REC_TEND_ESTIME == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;
                            <input type="checkbox" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME2" value="2" class="minimal" <?php if($REC_TEND_ESTIME == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;
                            <input type="checkbox" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME3" value="3" class="minimal" <?php if($REC_TEND_ESTIME == 3) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Mendesak                        </td>
                            <td>&nbsp;<?php echo $REC_TEND_ESTIME_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;8. &nbsp;&nbsp;Kompetitor/Bidders</td>
                            <td colspan="2"><?php echo $REC_BIDDER; ?></td>
                            <td rowspan="2">&nbsp;<?php echo $REC_BIDDER_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;</td>
                            <td colspan="2">&nbsp;
                            Jumlah = +/- <strong><?php echo $REC_BIDDER_QTY; ?></strong> Kontraktor                      	</td>
                        </tr>
                        <tr style=" height:22px">
                            <td colspan="3" nowrap>&nbsp;9. Perkiraan Eskalasi</td>
                            <td colspan="2">
                            <input type="checkbox" name="REC_ESKAL_EST" id="REC_ESKAL_EST1" value="1" class="minimal" <?php if($REC_ESKAL_EST == 1) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Ada&nbsp;&nbsp;
                            <input type="checkbox" name="REC_ESKAL_EST" id="REC_ESKAL_EST2" value="2" class="minimal" <?php if($REC_ESKAL_EST == 2) { ?> checked <?php } ?> disabled>
                            &nbsp;&nbsp;Tidak ada                        </td>
                            <td>&nbsp;<?php echo $REC_ESKAL_EST_NOTE; ?></td>
                        </tr>
                        <tr style=" height:22px">
                          <td colspan="3" rowspan="2" nowrap>&nbsp;KEPUTUSAN : beri tanda : <i class="fa fa-chevron-down"></i></td>
                          <td colspan="3">&nbsp;(<?php if($REC_CONCLUTION == 1) { ?> V <?php } else { ?> &nbsp;&nbsp; <?php } ?>) Ikut Tender/ PQ, *)&nbsp;Target :&nbsp;<?php echo $REC_CONC_TARGET; ?></td>
                        </tr>
                        <tr style=" height:22px">
                          <td colspan="3">&nbsp;(<?php if($REC_CONCLUTION == 0) { ?> V <?php } else { ?> &nbsp;&nbsp; <?php } ?>) Tidak ikut Tender/ PQ*) :&nbsp;<?php echo $REC_CONC_NOTES; ?></td>
                        </tr>
                        <tr style=" height:22px">
                          <td colspan="6" nowrap>&nbsp;<font style="text-decoration:underline">CATATAN HASIL KEPUTUSAN:</font><br /><br />&nbsp;
                      	<?php
							echo $REC_NOTES;
						?></td>
                        </tr>
                        <tr style=" height:22px">
                          <td colspan="6" nowrap>&nbsp;</td>
                        </tr>
                        <tr style=" height:22px">
                          <td colspan="6" nowrap>
                          <?php
						  	// GET MARKETING USER
							$compName1	= "";
							$sqlUSR1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$REC_USR_MRK'";
							$resUSR1	= $this->db->query($sqlUSR1)->result();
							foreach($resUSR1 as $rowUSR1):
								$Firt_Name1	= $rowUSR1->First_Name;
								$Last_Name1	= $rowUSR1->Last_Name;
								$compName1	= "$Firt_Name1 $Last_Name1";
							endforeach;
						  	// GET MARKETING MANAGER
							$compName2	= "";
							$sqlUSR2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$REC_MNG_MRK'";
							$resUSR2	= $this->db->query($sqlUSR2)->result();
							foreach($resUSR2 as $rowUSR2):
								$Firt_Name2	= $rowUSR2->First_Name;
								$Last_Name2	= $rowUSR2->Last_Name;
								$compName2	= "$Firt_Name2 $Last_Name2";
							endforeach;
						  	// GET ESTIMATE MANAGER
							$compName3	= "";
							$sqlUSR3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$REC_MNG_EST'";
							$resUSR3	= $this->db->query($sqlUSR3)->result();
							foreach($resUSR3 as $rowUSR3):
								$Firt_Name3	= $rowUSR3->First_Name;
								$Last_Name3	= $rowUSR3->Last_Name;
								$compName3	= "$Firt_Name3 $Last_Name3";
							endforeach;
						  ?>
                          	<table width="100%" border="0">
                            	<tr>
                                	<td width="33%"> DIREKOMENDASI :</td>
                                	<td width="34%">&nbsp;</td>
                                	<td width="33%">&nbsp;</td>
                                </tr>
                            	<tr>
                            	  <td>MARKETING</td>
                            	  <td>KEPALA DIVISI ESTIMATE</td>
                            	  <td>KEPALA DEPARTEMEN MARKETING</td>
                          	  </tr>
                            	<tr>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                          	  </tr>
                            	<tr>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                          	  </tr>
                            	<tr>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                          	  </tr>
                            	<tr>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                          	  </tr>
                            	<tr style="font-weight:bold; font-style:italic">
                            	  <td>Nama : <?php echo $compName1; ?></td>
                            	  <td>Nama : <?php echo $compName2; ?></td>
                            	  <td>Nama : <?php echo $compName3; ?></td>
                          	  </tr>
                            </table>                          </td>
                        </tr>
            </table>
      </td>
  	</tr> 
</table>
</body>
</html>
