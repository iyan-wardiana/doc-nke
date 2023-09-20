<?php
	date_default_timezone_set("Asia/Jakarta");
	setlocale(LC_ALL, 'id-ID', 'id_ID');
	/* 
		 * Author		= Dian Hermanto
		 * Create Date	= 13 Oktober 2022
		 * File Name	= v_lkh_report_pd.php
		 * Location		= -
	*/
	//$this->load->view('template/head');
	$Periode1 = date('YmdHis');
	if($viewType == 1)
	{
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=LapLabaRugi_$Periode1.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	    
	$sqlApp         = "SELECT * FROM tappname";
	$resultaApp     = $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
	    $appName    = $therow->app_name;
	    $comp_init  = strtolower($therow->comp_init);
	    $comp_name  = $therow->comp_name;
	endforeach;

	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
	$comp_name 	= $this->session->userdata['comp_name'];

	$PeriodeD 		= date('Y-m-d',strtotime($DWR_DATE));

	$End_Date 		= date('Y-m-d',strtotime($DWR_DATE));
	$End_DateBef1	= date('Y-m-d', strtotime('-1 month', strtotime($DWR_DATE)));
	$End_DateBef	= date('Y-m-t', strtotime('-1 day', strtotime($End_DateBef1)));
	$PERIODEM_BEF	= date('m', strtotime($End_DateBef));
	$PERIODEY_BEF	= date('Y', strtotime($End_DateBef));

	$comp_init 		= $this->session->userdata('comp_init');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
	<title><?=$h1_title?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
  	<style>
    	body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
	        width: 21cm;
	        min-height: 29.7cm;
	        padding: 0.1cm;
	        margin: 0.1cm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    }
	    .subpage {
	        padding: 0.5cm;
	        height: 256mm;
	    }
    
	    @page {
	        size: A4;
	        margin: 0;
	    }
	    @media print {
	        .page {
	            margin: 0;
	            border: initial;
	            border-radius: initial;
	            width: initial;
	            min-height: initial;
	            box-shadow: initial;
	            background: initial;
	            page-break-after: always;
	        }
	    }
			
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>
</head>
<body style="overflow:auto">
	<div class="page">
		<section class="content">
	        <table width="100%" border="0" style="size:auto">
	            <tr>
	                <td width="19%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
	                <td width="42%" class="style2">&nbsp;</td>
	                <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
	            </tr>
	            <tr>
	                <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/'. $comp_init . '/compLog.png') ?>" width="250" height="60"></td>
	                <td colspan="2" class="style2" style="font-weight:bold; text-transform: uppercase; font-size:18px">LAPORAN</td>
	          	</tr>
	            <tr>
	                <td colspan="2" valign="top" class="style2" style="font-weight:bold; font-size:12px;"><span class="style2" style="font-weight:bold; font-size:12px"><?php //echo strtoupper($PRJNAMEV); ?></span></td>
	            </tr>
	            <tr>
	                <td colspan="2" valign="top" class="style2" style="font-size:12px; border-bottom:groove;"><span class="style2" style="font-weight:bold; font-size:12px"><?php //echo strtoupper($comp_name); ?></span><span class="pull-right" style="font-style: italic; font-size: 10px">Tgl. Cetak : <?php //echo date("d/m/Y H:i:s"); ?></span></td>
	            </tr>
	            <tr>
	                <td colspan="3" class="style2" style="text-align:left;">&nbsp;</td>
	            </tr>
	          	<tr>
	                <td colspan="3" class="style2" style="text-align:left;">
	                    <table width="100%" style="size:auto; font-size:12px;" cellspacing="-1" cellpadding="0">
	                        <tr style="text-align:left;">
	                         	<td width="15%" style="font-weight: bold; font-size: 13px;" nowrap>NILAI KONTRAK</td>
	                          	<td width="1%">:</td>
	                          	<td width="15%" style="font-weight: bold; font-size: 13px; text-align: right;" nowrap>
	                          		<?php //echo number_format($PRJCOSTV, $decFormat); ?>
	                          	</td>
	                          	<td width="55%">&nbsp;</td>
	                          	<td width="4%">&nbsp;</td>
	                          	<td width="10%">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td style="font-weight: bold; font-size: 13px;" nowrap>NILAI ADD. KONTRAK</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px; text-align: right;" nowrap>
	                                <?php //echo number_format($ADD_TOT, $decFormat); ?>
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td title="Rencana Progress Mingguan" nowrap>RENCANA PROGRESS</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php //echo number_format($PRG_PLANC, 4); ?> %
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td title="Realisasi Progres Mingguan" nowrap>REALISASI PROGRESS (Int.)</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php //echo number_format($PRG_RINTC, 4); ?> %
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td title="Realisasi Progres Mingguan" nowrap>REALISASI PROGRESS (Ext.)</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php //echo number_format($PRG_REKSC, 4); ?> %
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>REALISASI MC / TAGIHAN</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php //echo number_format($A1SPER, 4); ?> %
	                            </td>
	                          	<td style="text-align:right;" colspan="3">
	                          		<span class="pull-right" style="font-style: italic;">PERIODE : <?php //echo $PERIODEV; ?></span>
	                          	</td>
	                      	</tr>
	                    </table>
			    	</td>
	            </tr>
	            <tr>
					<td colspan="3" class="style2" style="text-align:left; font-size:12px">
	              	<table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
	                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:13px;">
	                    	<td style="<?=$stlLine6?> text-align: center;" width="3%">&nbsp;NO.&nbsp;</td>
	                      	<td style="<?=$stlLine6?> text-align: center;" colspan="2">URAIAN</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%">RENCANA AWAL</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%" nowrap>PERIODE<br>SEBELUMNYA</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="5%" nowrap>%</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%" nowrap>BULAN INI</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="5%"> (%)</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%" nowrap>PROYEKSI</td>
	                	</tr>
	                	<tr style="size:auto; font-size:4px;">
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	              	  	</tr>

	              	  	<?php //--- START 	: A. PENDAPATAN ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
		                        <td style="<?=$stlLine5?>; font-size: 13px;" align="center">A.</td>
		                        <td style="<?=$stlLine5?>; font-size: 13px; text-decoration: underline;" colspan="2">PENDAPATAN</td>
		                        <td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		                        <td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		                        <td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		                        <td width="12%" style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Progress MC</td>
		                        <td width="26%" style="<?=$stlLine5?> text-align:right; border-left: 0"><?php //echo number_format($A1SPER, 2); ?> %&nbsp;</td>
		                        <td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($PRJCOST, 0); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($A1B, 0); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right" nowrap><?php //echo number_format($A1BPER, 2); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($A1C, 0); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right" nowrap><?php //echo number_format($A1CPER, 2); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right" nowrap><?php //echo number_format($A1P, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Progress Kontraktuil</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0"><?php //echo number_format($PRG_KONTRTUIL, 2); ?> %&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Progress Internal</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0"><?php //echo number_format($PRG_RINTC, 2); ?> %&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Deviasi Progress</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0"><?php //echo number_format($PRG_RDEV, 2); ?> %&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Pendapatan Lain-lain</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($A5B, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($A5C, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($A5D, 0); ?></td>
		              	  	</tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2" align="center">&nbsp;Sub Total (A)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_AA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_AB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_AC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_AD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr style="display:none">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: A. PENDAPATAN ---// ?>


	              	  	<?php //--- START 	: C -> B. BIAYA - BIAYA ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine5?>" align="center">B.</td>
								<td style="<?=$stlLine5?>; font-size: 13px; text-decoration: underline;" colspan="2">BIAYA - BIAYA</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
		              	  	</tr>
		                    <?php
								$LinkBef_M	= "$PRJCODECOL~$End_DateBef~M";
								$LinkBef_U	= "$PRJCODECOL~$End_DateBef~U";
								$LinkBef_T	= "$PRJCODECOL~$End_DateBef~T";
								$LinkBef_SC	= "$PRJCODECOL~$End_DateBef~SC";
								$LinkBef_GE	= "$PRJCODECOL~$End_DateBef~GE";
								$LinkBef_I	= "$PRJCODECOL~$End_DateBef~I";
								$secPrintMB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_M));
								$secPrintUB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_U));
								$secPrintTB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_T));
								$secPrintSB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_SC));
								$secPrintGB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_GE));
								$secPrintIB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_I));
								
								$LinkCur_M	= "$PRJCODECOL~$PeriodeD~M";
								$LinkCur_U	= "$PRJCODECOL~$PeriodeD~U";
								$LinkCur_T	= "$PRJCODECOL~$PeriodeD~T";
								$LinkCur_SC	= "$PRJCODECOL~$PeriodeD~SC";
								$LinkCur_GE	= "$PRJCODECOL~$PeriodeD~GE";
								$LinkCur_I	= "$PRJCODECOL~$PeriodeD~I";
								$secPrintMC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_M));
								$secPrintUC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_U));
								$secPrintTC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_T));
								$secPrintSC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_SC));
								$secPrintGC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_GE));
								$secPrintIC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_I));
							?>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Material</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C1A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php //echo $secPrintMB; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C1B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C1BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetC('<?php //echo $secPrintMC; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C1C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C1CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C1AP, 0); ?></td>
		              	  	</tr>
		                    <script>
								function showDetB(LinkD)
								{
									w = 1000;
									h = 550;
									var left = (screen.width/2)-(w/2);
									var top = (screen.height/2)-(h/2);
									window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
								}
								
								function showDetC(LinkD)
								{
									w = 1000;
									h = 550;
									var left = (screen.width/2)-(w/2);
									var top = (screen.height/2)-(h/2);
									window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
								}
		                    </script>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Upah</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C2A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php //echo $secPrintUB; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C2B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C2BP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetC('<?php //echo $secPrintUC; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C2C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C2CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C2AP, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Alat</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C3A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php //echo $secPrintTB; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C3B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C3BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetC('<?php //echo $secPrintTC; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C3C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C3CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C3AP, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Subkontraktor</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C4A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php //echo $secPrintSB; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C4B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C4BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php //echo $secPrintSC; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C4C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C4CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C4AP, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Biaya Overhead</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C5AP, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php //echo $secPrintGB; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C5B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C5BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php //echo $secPrintGC; ?>')" style="cursor: pointer;">
										<?php //echo number_format($C5C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C5CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($C5CP, 0); ?></td>
		              	  	</tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (B)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_CA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_CB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_CC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php ////echo number_format($SUBTOT_CD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: C -> B. BIAYA HUTANG ---// ?>


	              	  	<?php //--- START 	: D -> C. HUTANG DALAM PROSES ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">C.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">HUTANG DALAM PROSES</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Kasbon</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Hutang Belum Dibukukan</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (C)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_DA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_DB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_DC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_DD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: D -> C. HUTANG DALAM PROSES ---// ?>


	              	  	<?php //--- START 	: E -> D. BIAYA PROYEK DI PUSAT ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">D.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">BIAYA PROYEK DI PUSAT</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;BUASO</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Contingency</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (D)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_EA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_EB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_EC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_ED, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: E -> D. BIAYA PROYEK DI PUSAT ---// ?>


	              	  	<?php //--- START 	: F -> E. STOCK / PERSEDIAAN ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">E.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">STOCK / PERSEDIAAN</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Material</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Lainnya</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (E)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_FA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_FB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_FC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_FD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: F -> E. STOCK / PERSEDIAAN ---// ?>


	              	  	<?php //--- START 	: G -> F. BEBAN-BEBAN ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">F.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">BEBAN-BEBAN</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Beban Alat</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Overhead Pusat (<?=number_format($PRJ_LROVH,2);?> %)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BAU_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BAUB_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BAUB_PLANP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BAU_REAL, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BAU_REALP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BAU_PROYEKSI, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Bunga Bank (<?=number_format($PRJ_LRBNK,2);?>%)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BNG_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BNGB_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BNGB_PLANP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BNG_REAL, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BNG_REALP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_BNG_PROYEKSI, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;PPh (<?=number_format($PRJ_LRPPH,2);?>%)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_PPH_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_PPHB_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_PPHB_PLANP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_PPH_REAL, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_PPH_REALP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($BB_PPH_PROYEKSI, 0); ?></td>
		               	    </tr>
		                    <?php
								$SUBTOT_GA	= $BB_BAU_PLAN + $BB_BNG_PLAN + $BB_PPH_PLAN;
								$SUBTOT_GB	= $BB_BAUB_PLAN + $BB_BNGB_PLAN + $BB_PPHB_PLAN;
								$SUBTOT_GC	= $BB_BAU_REAL + $BB_BNG_REAL + $BB_PPH_REAL;
								$SUBTOT_GD	= $BB_BAU_PROYEKSI + $BB_BNG_PROYEKSI + $BB_PPH_PROYEKSI;
							?>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (F)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_GA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_GB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_GC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_GD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: E -> F. BEBAN-BEBAN ---// ?>


	              	  	<?php //--- START 	: H -> G. LABA / RUGI ---// ?>
		                    <?php
		                    	// TOTAL 	= SUB TOTAL (A) - SUB TOTAL (B) - SUB TOTAL (C)
								$SUBTOT_HA	= $SUBTOT_AA - $SUBTOT_BA - $SUBTOT_CA - $SUBTOT_DA - $SUBTOT_EA + $SUBTOT_FA - $SUBTOT_GA;
								$SUBTOT_HB	= $SUBTOT_AB - $SUBTOT_BB - $SUBTOT_CB - $SUBTOT_DB - $SUBTOT_EA + $SUBTOT_FB - $SUBTOT_GA;
								$SUBTOT_HC	= $SUBTOT_AC - $SUBTOT_BC - $SUBTOT_CC - $SUBTOT_DC - $SUBTOT_EC + $SUBTOT_FC - $SUBTOT_GC;
								$SUBTOT_HD	= $SUBTOT_AD - $SUBTOT_BD - $SUBTOT_CD - $SUBTOT_DD - $SUBTOT_ED + $SUBTOT_FD - $SUBTOT_GD;
							?>
		                	<tr style="font-weight:bold; background:#CCCCCC; font-size:12px">
								<td style="<?=$stlLine5?>" align="center">G.</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;LABA / RUGI : (A-B-C-D+E-F)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_HA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_HB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_HC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_HD, 0); ?></td>
		              	  	</tr>
		                	<tr style="font-weight:bold;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
		           	      	</tr>
	              	  	<?php //--- END 	: H -> G. LABA / RUGI ---// ?>
	                </table>
	           	  </td>
	            </tr>
	        </table>
	        <br>
	        <div class="row no-print">
	            <div class="col-xs-12">
	                <div id="Layer1">
	                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
	                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
	                    <i class="fa fa-download"></i> Generate PDF
	                    </button>
	                </div>
	            </div>
	        </div>
		</section>
	</div>
</body>
</html>