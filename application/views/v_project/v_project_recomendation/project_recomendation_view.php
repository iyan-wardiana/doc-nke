<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 03 April 2017
 * File Name	= project_recomendation_view.php
 * Location		= -
*/
?>
<?php
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
				else
				{
					$ownerName	= "$own_Name";
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
	$REC_FUNDSRC_APBN	= $default['REC_FUNDSRC_APBN'];
	$REC_FUNDSRC_APBD	= $default['REC_FUNDSRC_APBD'];
	$REC_FUNDSRC_PRIV	= $default['REC_FUNDSRC_PRIV'];
	$REC_FUNDSRC_LOAN	= $default['REC_FUNDSRC_LOAN'];
	$REC_FUNDSRC_OTH	= $default['REC_FUNDSRC_OTH'];
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
	if($REC_CONCLUTION == '')
	{
		$REC_CONCLUTION	= 2;
	}
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
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Invoice</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'asset/css/AdminLTE.min.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<div class="wrapper">        
        <section class="invoice">
            <!-- this row will not appear when printing -->
          	<div class="row">
            <div class="col-xs-12 table-responsive">
            	<table border="0" width="100%">
                    <tbody>
                        <tr>
                            <td width="14%" style="vertical-align:middle">
                            	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="160" height="40" />                            </td>
                            <td width="60%" align="center" style="font-weight:bold"><span style="font-size:18px;">REKOMENDASI TENDER PROYEK</span><br>
                        Rekom.No: <?php echo $REC_NO; ?><br></td>
                            <td width="9%">
               	  <table width="100%">
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
                    </tbody>
				</table>
              <table class="table" border="0" width="100%" style="font-size:12px">
                <tbody>
                    <tr>
                        <th width="13%">&nbsp;</th>
               	      <th width="1%">&nbsp;</th>
               	      <th colspan="2">&nbsp;</th>
               	      <th width="31%">&nbsp;</th>
               	      <th width="22%">&nbsp;</th>
                  	</tr>
                    <tr>
                        <th>P R O Y E K</th>
                        <th>:</th>
                     	<th colspan="2"><?php echo $REC_PRJNAME; ?></th>
                  	    <th>&nbsp;</th>
                  	    <th nowrap>Nilai = + / -&nbsp;&nbsp;&nbsp;<?php echo $REC_CURRENCY; ?>&nbsp;<?php print number_format($REC_VALUE, $decFormat); ?></th>
                    </tr>
                    <tr>
                        <th>O W N E R</th>
                        <th>:</th>
                     	<th colspan="4"><?php echo $ownerName; ?></th>
                  	</tr>
                    <tr style="vertical-align:middle">
                        <th rowspan="2" style="vertical-align:middle">K O N S U T A N</th>
                        <th rowspan="2" style="vertical-align:middle">:</th>
                        <th colspan="4">ARS : <?php echo $REC_CONSULT_ARS; ?></th>
                    </tr>
                    <tr>
                      <th colspan="4">QS : <?php echo $REC_CONSULT_QS; ?></th>
                    </tr>
                    <tr>
                        <th>L O K A S I</th>
                        <th>:</th>
                        <th colspan="4"><?php echo $REC_LOCATION; ?></th>
                    </tr>
                    <tr>
                      	<th colspan="6"> PERKIRAAN WAKTU TENDER TANGGAL : <?php echo date('Y-m-d',strtotime($REC_TEND_DATE)); ?></th>
               	    </tr>
                    <tr>
                      	<th>KETERANGAN</th>
                      	<th>&nbsp;</th>
                      	<th width="11%">&nbsp;</th>
                      	<th colspan="2">REKOMENDASI</th>
                      	<th>CATATAN</th>
                    </tr>
                    <tr>
                      	<td colspan="3">1. &nbsp;&nbsp;Sumber Dana</td>
                      	<td colspan="2">
                      	<?php /*?><input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC1" value="1" class="minimal" <?php if($REC_FUNDSRC == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tersedia (aman)&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC2" value="2" class="minimal" <?php if($REC_FUNDSRC == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Bertahap&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_FUNDSRC" id="REC_FUNDSRC3" value="3" class="minimal" <?php if($REC_FUNDSRC == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Meragukan<?php */?>
                           
                        <input type="checkbox" name="REC_FUNDSRC_APBN" id="REC_FUNDSRC_APBN" value="1" class="minimal" <?php if($REC_FUNDSRC_APBN==1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;APBN&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_FUNDSRC_APBD" id="REC_FUNDSRC_APBD" value="1" class="minimal" <?php if($REC_FUNDSRC_APBD==1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;APBD&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_FUNDSRC_PRIV" id="REC_FUNDSRC_PRIV" value="1" class="minimal" <?php if($REC_FUNDSRC_PRIV==1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Private&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_FUNDSRC_LOAN" id="REC_FUNDSRC_LOAN" value="1" class="minimal" <?php if($REC_FUNDSRC_LOAN==1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;LOAN&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_FUNDSRC_OTH" id="REC_FUNDSRC_OTH" value="1" class="minimal" <?php if($REC_FUNDSRC_OTH==1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Lainnya
                        </td>
                      	<td><?php echo $REC_FUNDSRC_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3">2. &nbsp;&nbsp;Sistem Pembayaran</td>
                      	<td colspan="2">
                            <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS1" value="1" class="minimal" <?php if($REC_PAY_SYS == 1) { ?> checked <?php } ?>>
                            &nbsp;&nbsp;Progres Bulanan&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS2" value="2" class="minimal" <?php if($REC_PAY_SYS == 2) { ?> checked <?php } ?>>
                            &nbsp;&nbsp;Termijn&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="REC_PAY_SYS" id="REC_PAY_SYS3" value="3" class="minimal" <?php if($REC_PAY_SYS == 3) { ?> checked <?php } ?>>
                            &nbsp;&nbsp;Turnkey                        </td>
                      	<td><?php echo $REC_PAY_SYS_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3">3. &nbsp;&nbsp;Uang Muka</td>
                      	<td colspan="2">
                            <input type="checkbox" name="REC_DP" id="REC_DP1" value="1" class="minimal" <?php if($REC_DP == 1) { ?> checked <?php } ?>>
                            &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="REC_DP" id="REC_DP2" value="2" class="minimal" <?php if($REC_DP == 2) { ?> checked <?php } ?>>
                            &nbsp;&nbsp;Tidak Ada                        </td>
                      	<td><?php echo $REC_DP_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>4. &nbsp;&nbsp;Persyaratan Utama PQ</td>
                      	<td colspan="2">&nbsp;</td>
                      	<td>&nbsp;</td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) Turn Over</td>
                      	<td colspan="2">
                      	<input type="checkbox" name="REC_TURNOVER" id="REC_TURNOVER1" value="1" class="minimal" <?php if($REC_TURNOVER == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_TURNOVER" id="REC_TURNOVER2" value="2" class="minimal" <?php if($REC_TURNOVER == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;                      	</td>
                      	<td><?php echo $REC_TURNOVER_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) Pengalaman sejenis</td>
                      	<td colspan="2">
                      	<input type="checkbox" name="REC_EXP" id="REC_EXP1" value="1" class="minimal" <?php if($REC_EXP == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_EXP" id="REC_EXP2" value="2" class="minimal" <?php if($REC_EXP == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;                      	</td>
                      	<td><?php echo $REC_EXP_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c) Kemampuan dasar</td>
                      	<td colspan="2">
                        <input type="checkbox" name="REC_BASCAPAB" id="REC_BASCAPAB1" value="1" class="minimal" <?php if($REC_BASCAPAB == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_BASCAPAB" id="REC_BASCAPAB2" value="2" class="minimal" <?php if($REC_BASCAPAB == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;                        </td>
                      	<td><?php echo $REC_BASCAPAB_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d) Kemampuan keuangan</td>
                      	<td colspan="2">
                        <input type="checkbox" name="REC_FINCAPAB" id="REC_FINCAPAB1" value="1" class="minimal" <?php if($REC_FINCAPAB == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_FINCAPAB" id="REC_FINCAPAB2" value="2" class="minimal" <?php if($REC_FINCAPAB == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;                        </td>
                      	<td><?php echo $REC_FINCAPAB_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>5. &nbsp;&nbsp;Waktu Pelaksanaan</td>
                      	<td colspan="2"><?php echo $REC_DATEXECD; ?> hari</td>
                      	<td><?php echo $REC_TIMEXEC_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>6. &nbsp;&nbsp;Perkiraan waktu Tender</td>
                      	<td colspan="2">
                        <input type="checkbox" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME1" value="1" class="minimal" <?php if($REC_TEND_ESTIME == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME2" value="2" class="minimal" <?php if($REC_TEND_ESTIME == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME3" value="3" class="minimal" <?php if($REC_TEND_ESTIME == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Mendesak                        </td>
                      	<td><?php echo $REC_TEND_ESTIME_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>7. &nbsp;&nbsp;Perkiraan waktu PQ</td>
                      	<td colspan="2">
                        <input type="checkbox" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME1" value="1" class="minimal" <?php if($REC_PQ_ESTIME == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME2" value="2" class="minimal" <?php if($REC_PQ_ESTIME == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME3" value="3" class="minimal" <?php if($REC_PQ_ESTIME == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Mendesak                        </td>
                      	<td><?php echo $REC_PQ_ESTIME_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>8. &nbsp;&nbsp;Kompetitor/Bidders</td>
                      	<td colspan="2"><?php echo $REC_BIDDER; ?></td>
                      	<td rowspan="2"><?php echo $REC_BIDDER_NOTE; ?></td>
                    </tr>
                    <tr>
                      	<td colspan="3" nowrap>&nbsp;</td>
                   	  	<td colspan="2">
                        Jumlah = +/- <strong><?php echo $REC_BIDDER_QTY; ?></strong> Kontraktor                      	</td>
               	    </tr>
                    <tr>
                      	<td colspan="3" nowrap>9. Perkiraan Eskalasi</td>
                      	<td colspan="2">
                        <input type="checkbox" name="REC_ESKAL_EST" id="REC_ESKAL_EST1" value="1" class="minimal" <?php if($REC_ESKAL_EST == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="REC_ESKAL_EST" id="REC_ESKAL_EST2" value="2" class="minimal" <?php if($REC_ESKAL_EST == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak ada                        </td>
                      	<td><?php echo $REC_ESKAL_EST_NOTE; ?></td>
                    </tr>
                    <tr>
                      <td colspan="3" rowspan="2" nowrap> Keputusan : beri tanda : <i class="fa fa-check"></i></label></td>
                      <td colspan="3">( <?php if($REC_CONCLUTION == 1) { ?><font style="font-weight:bold">V</font><?php } else { ?> &nbsp;&nbsp; <?php } ?> ) Ikut Tender/ PQ, *)&nbsp;Target :&nbsp;<?php echo $REC_CONC_TARGET; ?></td>
                    </tr>
                    <tr>
                      <td colspan="3">( <?php if($REC_CONCLUTION == 0) { ?><font style="font-weight:bold">V</font><?php } else { ?> &nbsp;&nbsp; <?php } ?> ) Tidak ikut Tender/ PQ*) :&nbsp;<?php echo $REC_CONC_NOTES; ?></td>
                    </tr>
                    <tr>
                      <td colspan="6" nowrap><font style="text-decoration:underline">CATATAN HASIL KEPUTUSAN:</font><BR>
                      	<?php
							echo $REC_NOTES;
						?>                      </td>
                    </tr>
                    <tr>
                      <td colspan="5" nowrap>DIREKOMENDASIKAN TANGGAL : <?php echo date('Y-m-d',strtotime($REC_SIGN_USR_MRK)); ?></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <th colspan="6" style="text-align:center" nowrap>CATATAN PERSETUJUAN</th>
                    </tr>
                    <tr>
                      <td colspan="6">    
                      <?php
					  		$SIGN_BG_USR_MRK	= "yellow";
							$REC_USR_MRK_STATD 	= "DRAFT";
							$REC_FA_USR_MRK		= "flag-o";
					  		if($REC_USR_MRK_STAT == 2)
							{
								$SIGN_BG_USR_MRK	= "green";
								$REC_USR_MRK_STATD 	= "CONFIRMED";
								$REC_SIGN_USR_MRKD	= date('Y-m-d',strtotime($REC_SIGN_USR_MRK));
								$REC_FA_USR_MRK		= "thumbs-o-up";
							}
							else
							{
								$REC_SIGN_USR_MRKD	= '';
							}
							
							$SIGN_BG_MNG_EST	= "yellow";
							$REC_MNG_EST_STATD	= "DRAFT";
							$REC_FA_MNG_EST		= "flag-o";
					  		if($REC_MNG_EST_STAT == 2)
							{
								$SIGN_BG_MNG_EST	= "green";
								$REC_MNG_EST_STATD	= "CONFIRMED";
								$REC_SIGN_MNG_ESTD	= date('Y-m-d',strtotime($REC_SIGN_MNG_EST));
								$REC_FA_MNG_EST		= "thumbs-o-up";
							}
							else
							{
								$REC_SIGN_MNG_ESTD	= '';
							}
							
					  		$SIGN_BG_MNG_MRK	= "yellow";
							$REC_MNG_MRK_STATD	= "DRAFT";
							$REC_FA_MNG_MRK		= "flag-o";
					  		if($REC_MNG_MRK_STAT == 3)
							{
								$SIGN_BG_MNG_MRK	= "green";
								$REC_MNG_MRK_STATD 	= "APPROVED";
								$REC_SIGN_MNG_MRKD	= date('Y-m-d',strtotime($REC_SIGN_MNG_MRK));
								$REC_FA_MNG_MRK		= "thumbs-o-up";
							}
							else
							{
								$REC_SIGN_MNG_MRKD	= '';
							}
					  ?>                  
                      <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-<?php echo $SIGN_BG_USR_MRK; ?>"><i class="fa fa-<?php echo $REC_FA_USR_MRK; ?>"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">MARKETING</span>
                              <span class="info-box-number" style="font-size:14px"><?php echo $REC_USR_MRK_STATD; ?></span>
                              <span class="info-box-number" style="font-size:12px"><?php echo $REC_SIGN_USR_MRKD; ?></span>                          	</div><!-- /.info-box-content -->
                          </div><!-- /.info-box -->
                        </div><!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-<?php echo $SIGN_BG_MNG_EST; ?>"><i class="fa fa-<?php echo $REC_FA_MNG_EST; ?>"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">KA.ESTIMATE</span>
                              <span class="info-box-number" style="font-size:14px"><?php echo $REC_MNG_EST_STATD; ?></span>
                              <span class="info-box-number" style="font-size:14px"><?php echo $REC_SIGN_MNG_ESTD; ?></span>							</div><!-- /.info-box-content -->
                          </div><!-- /.info-box -->
                        </div><!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-<?php echo $SIGN_BG_MNG_MRK; ?>"><i class="fa fa-<?php echo $REC_FA_MNG_MRK; ?>"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">KEPALA</span>
                              <span class="info-box-text">MARKETING</span>
                              <span class="info-box-number" style="font-size:14px"><?php echo $REC_MNG_MRK_STATD; ?></span>
                              <span class="info-box-number" style="font-size:14px"><?php echo $REC_SIGN_MNG_MRKD; ?></span>							</div><!-- /.info-box-content -->
                          </div><!-- /.info-box -->
                        </div><!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12" style="display:none">
                          <div class="info-box">
                            <span class="info-box-icon bg-<?php echo $SIGN_BG_MNG_EST; ?>"><i class="fa fa-star-o"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">PRESIDEN</span>
                              <span class="info-box-text" style="font-size:14px">DIREKTUR</span>
                              <span class="info-box-number" style="font-size:14px">APPROVED</span>                            </div><!-- /.info-box-content -->
                          </div><!-- /.info-box -->
                        </div><!-- /.col -->
                      </div><!-- /.row -->                      </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <!-- /.col -->
          </div>
		</section>
        <?php
			$urlPrintDoc	= site_url('c_project/project_recomendation/printDocRecomend/?id='.$this->url_encryption_helper->encode_url($REC_CODE));
			$urlPrintDocEXCL= site_url('c_project/project_recomendation/printDocRecomendEXCEL/?id='.$this->url_encryption_helper->encode_url($REC_CODE));
		?>
        <section class="invoice">
            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-xs-12">
               	  <a href="<?php echo $urlPrintDoc; ?>" target="_self" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
               	  <a href="<?php echo $urlPrintDocEXCL; ?>" target="_self" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate PDF</a>
                </div>
            </div>
        </section>
	</div>
</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="checkbox"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="checkbox"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>
