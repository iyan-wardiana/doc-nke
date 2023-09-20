<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Junli 2018
 * File Name	= profit_loss_view.php
 * Location		= -
*/
$viewType	= 1;
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];

$PeriodeD = date('Y-m-d',strtotime($End_Date));

if($TOTPROJ == 1)
{
	$PeriodeD = date('Y-m-d',strtotime($End_Date));
	
	$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODECOL'";
	$resPRJ	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE 	= $rowPRJ->PRJCODE;
		$PRJNAME 	= $rowPRJ->PRJNAME;
		$PRJCOST 	= $rowPRJ->PRJCOST;
	endforeach;
	$PRJCODECOLL	= $PRJCODE;
	$PRJNAMECOLL	= $PRJNAME;
}
else
{
	$PROG_MC_A	= 0;
	$PRJCODED	= 'Multi Project Code';
	$PRJNAMED 	= 'Multi Project Name';
	$myrow		= 0;
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME, A.PRJCOST FROM tbl_project A 
					WHERE A.PRJCODE = '$PRJCODECOL'
					ORDER BY A.PRJCODE";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODED 	= $row ->PRJCODE;
		$PRJNAMED 	= $row ->PRJNAME;
		$PRJCOST 	= $row ->PRJCOST;
		$PROG_MC_A	= $PROG_MC_A + $PRJCOST;
		if($myrow == 1)
		{
			$PRJCODECOLL	= "$PRJCODED";
			$PRJCODECOL1	= "$PRJCODED";
			$PRJNAMECOLL	= "$PRJNAMED";
			$PRJNAMECOL1	= "$PRJNAMED";
		}
		if($myrow > 1)
		{
			$PRJCODECOL1	= "$PRJCODECOL1, $PRJCODED";
			$PRJCODECOLL	= "$PRJCODECOL1";
			$PRJNAMECOL1	= "$PRJNAMECOL1, $PRJNAMED";
			$PRJNAMECOLL	= "$PRJNAMECOL1";
		}
	endforeach;
}
$DEFAULTVAL	= 0;
$PERIODE 		= 0;
$PRJCODE 		= 0;
$PRJNAME 		= 0;
$PRJCOST 		= 0;
$PRJADD 		= 0;
$SIAPPVAL 		= 0;
$PROG_PLAN 		= 0;
$PROG_REAL 		= 0;
$INV_REAL 		= 0;
$PROGMC 		= 0;
$PROGMC_PLAN 	= 0;
$PROGMC_REAL	= 0;
$SI_PLAN 		= 0;
$SI_REAL 		= 0;
$SI_PROYEKSI 	= 0;
$MC_CAT_PLAN 	= 0;
$MC_CAT_REAL 	= 0;
$MC_CAT_PROYEKSI = 0;
$MC_OTH_PLAN	= 0;
$MC_OTH_REAL 	= 0;
$MC_OTH_PROYEKSI= 0;
$KURS_DEV_PLAN 	= 0;
$KURS_DEV_REAL 	= 0;
$KURS_DEV_PROYEKSI = 0;
$ASSURAN_PLAN 	= 0;
$ASSURAN_REAL 	= 0;
$ASSURAN_PROYEKSI = 0;
$CASH_EXPENSE 	= 0;
$BPP_MTR_PLAN 	= 0;
$BPP_MTR_REAL 	= 0;
$BPP_MTR_PROYEKSI = 0;
$BPP_UPH_PLAN 	= 0;
$BPP_UPH_REAL 	= 0;
$BPP_UPH_PROYEKSI = 0;
$BPP_ALAT_PLAN	= 0;
$BPP_ALAT_REAL 	= 0;
$BPP_ALAT_PROYEKSI = 0;
$BPP_SUBK_PLAN 	= 0;
$BPP_SUBK_REAL	= 0;
$BPP_SUBK_PROYEKSI = 0;
$BPP_BAU_PLAN 	= 0;
$BPP_BAU_REAL 	= 0;
$BPP_BAU_PROYEKSI = 0;
$BPP_OTH_PLAN 	= 0;
$BPP_OTH_REAL 	= 0;
$BPP_OTH_PROYEKSI = 0;
$STOCK 			= 0;
$STOCK_MTR 		= 0;
$STOCK_BBM 		= 0;
$STOCK_TOOLS 	= 0;
$EXP_TOOLS 		= 0;
$EXP_BAU_HOCAB 	= 0;
$EXP_BUNGA 		= 0;
$EXP_PPH 		= 0;
$GRAND_PROFLOS 	= 0;
$LR_CREATER 	= 0;
$LR_CREATED 	= 0;

$PERIODEM	= date('m', strtotime($PeriodeD));
$sqlGetLR	= "SELECT * FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' AND MONTH(PERIODE) = '$PERIODEM'";
$resGetLR	= $this->db->query($sqlGetLR)->result();
foreach($resGetLR as $rowLR):
	$PERIODE 		= $rowLR->PERIODE;
	$PRJCODE 		= $rowLR->PRJCODE;
	$PRJNAME 		= $rowLR->PRJNAME;
	$PRJCOST 		= $rowLR->PRJCOST;
	$PRJADD 		= $rowLR->PRJADD;
	$SIAPPVAL 		= $rowLR->SIAPPVAL;
	$PROG_PLAN 		= $rowLR->PROG_PLAN;
	$PROG_REAL 		= $rowLR->PROG_REAL;
	$INV_REAL 		= $rowLR->INV_REAL;
	$PROGMC 		= $rowLR->PROGMC;
	$PROGMC_PLAN 	= $rowLR->PROGMC_PLAN;
	$PROGMC_REAL	= $rowLR->PROGMC_REAL;
	$SI_PLAN 		= $rowLR->SI_PLAN;
	$SI_REAL 		= $rowLR->SI_REAL;
	$SI_PROYEKSI 	= $rowLR->SI_PROYEKSI;
	$MC_CAT_PLAN 	= $rowLR->MC_CAT_PLAN;
	$MC_CAT_REAL 	= $rowLR->MC_CAT_REAL;
	$MC_CAT_PROYEKSI = $rowLR->MC_CAT_PROYEKSI;
	$MC_OTH_PLAN	= $rowLR->MC_OTH_PLAN;
	$MC_OTH_REAL 	= $rowLR->MC_OTH_REAL;
	$MC_OTH_PROYEKSI= $rowLR->MC_OTH_PROYEKSI;
	$KURS_DEV_PLAN 	= $rowLR->KURS_DEV_PLAN;
	$KURS_DEV_REAL 	= $rowLR->KURS_DEV_REAL;
	$KURS_DEV_PROYEKSI = $rowLR->KURS_DEV_PROYEKSI;
	$ASSURAN_PLAN 	= $rowLR->ASSURAN_PLAN;
	$ASSURAN_REAL 	= $rowLR->ASSURAN_REAL;
	$ASSURAN_PROYEKSI = $rowLR->ASSURAN_REAL;
	$CASH_EXPENSE 	= $rowLR->CASH_EXPENSE;
	$BPP_MTR_PLAN 	= $rowLR->BPP_MTR_PLAN;
	$BPP_MTR_REAL 	= $rowLR->BPP_MTR_REAL;
	$BPP_MTR_PROYEKSI = $rowLR->BPP_MTR_PROYEKSI;
	$BPP_UPH_PLAN 	= $rowLR->BPP_UPH_PLAN;
	$BPP_UPH_REAL 	= $rowLR->BPP_UPH_REAL;
	$BPP_UPH_PROYEKSI = $rowLR->BPP_UPH_PROYEKSI;
	$BPP_ALAT_PLAN	= $rowLR->BPP_ALAT_PLAN;
	$BPP_ALAT_REAL 	= $rowLR->BPP_ALAT_REAL;
	$BPP_ALAT_PROYEKSI = $rowLR->BPP_ALAT_PROYEKSI;
	$BPP_SUBK_PLAN 	= $rowLR->BPP_SUBK_PLAN;
	$BPP_SUBK_REAL	= $rowLR->BPP_SUBK_REAL;
	$BPP_SUBK_PROYEKSI = $rowLR->BPP_SUBK_PROYEKSI;
	$BPP_BAU_PLAN 	= $rowLR->BPP_BAU_PLAN;
	$BPP_BAU_REAL 	= $rowLR->BPP_BAU_REAL;
	$BPP_BAU_PROYEKSI = $rowLR->BPP_BAU_PROYEKSI;
	$BPP_OTH_PLAN 	= $rowLR->BPP_OTH_PLAN;
	$BPP_OTH_REAL 	= $rowLR->BPP_OTH_REAL;
	$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
	$STOCK 			= $rowLR->STOCK;
	$STOCK_MTR 		= $rowLR->STOCK_MTR;
	$STOCK_BBM 		= $rowLR->STOCK_BBM;
	$STOCK_TOOLS 	= $rowLR->STOCK_TOOLS;
	$EXP_TOOLS 		= $rowLR->EXP_TOOLS;
	$EXP_BAU_HOCAB 	= $rowLR->EXP_BAU_HOCAB;
	$EXP_BUNGA 		= $rowLR->EXP_BUNGA;
	$EXP_PPH 		= $rowLR->EXP_PPH;
	$GRAND_PROFLOS 	= $rowLR->GRAND_PROFLOS;
	$LR_CREATER 	= $rowLR->LR_CREATER;
	$LR_CREATED 	= $rowLR->LR_CREATED;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profit and Loss Report</title>
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    .color-palette {
      height: 35px;
      line-height: 35px;
      text-align: center;
    }
    
    .color-palette-set {
      margin-bottom: 15px;
    }
    
    .color-palette span {
      display: none;
      font-size: 12px;
    }
    
    .color-palette:hover span {
      display: block;
    }
    
    .color-palette-box h4 {
      position: absolute;
      top: 100%;
      left: 25px;
      margin-top: -40px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
      display: block;
      z-index: 7;
    }
    </style>
</head>
<body>
<style>
.inplabel {border:none;background-color:white;}
.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
.inpdim {border:none;background-color:white;}
</style>
<section class="content">
        <table width="100%" border="0" style="size:auto">
            <tr>
                <td width="19%">
                    <div id="Layer1">
                        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                        <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
                <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
                <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
            </tr>
            <tr>
                <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
                <td class="style2">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
            </tr>
            <tr>
                <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="100" height="44"></td>
                <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:18px">PROJECT PROFIT AND LOSS</td>
          	</tr>
            <tr>
                <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo strtoupper($comp_name); ?></span></td>
            </tr>
                <?php
                    $StartDate1 = date('Y/m/d',strtotime($PERIODE));
                   	$EndDate1 = date('Y/m/d',strtotime($PERIODE));
                   	$End_Date = date('Y-m-d',strtotime($PERIODE));
                   	$EndDateV = date('F Y',strtotime($PERIODE));
                ?>
            <tr>
                <td colspan="3" class="style2" style="text-align:left;"><hr></td>
            </tr>
          <tr>
                <td colspan="3" class="style2" style="text-align:left;">
                    <table width="100%" style="size:auto; font-size:12px;">
                        <tr style="text-align:left;">
                            <td width="23%" nowrap>PROYEK</td>
                          	<td width="1%">:</td>
                            <td colspan="3" style="text-align:left; font-weight:bold">&nbsp;<?php echo strtoupper($PRJNAMECOLL); ?></td>
                      	</tr>
                        <?php
							// RENCANA PROGRES
								/*$MC_PROG		= 0;
								$MC_PROGAPP		= 0;
								$sqlGET_MC		= "SELECT MC_PROG, MC_PROGAPP FROM tbl_mcheader 
													WHERE PRJCODE = '$PRJCODECOL' AND MC_STAT = 3
														AND MC_DATE < '$End_Date' ORDER BY MC_DATE DESC LIMIT 1";
								$resGET_MC		= $this->db->query($sqlGET_MC)->result();
								foreach($resGET_MC as $rowGET_MC):
									$MC_PROG	= $rowGET_MC->MC_PROG;
									$MC_PROGAPP	= $rowGET_MC->MC_PROGAPP;
								endforeach;*/
								
							// DEVIASI PROGRES
								$DEVIASI_PROG	= $PROGMC_PLAN - $PROGMC_REAL;
						?>
                        <tr style="text-align:left;">
                         	<td nowrap>PERIODE</td>
                          	<td>:</td>
                          	<td width="34%" style="text-align:left;">&nbsp;<?php echo $EndDateV;?></td>
                          	<td width="13%" style="text-align:left;" nowrap>RENCANA PROGRES</td>
                          	<td width="29%" style="text-align:left;">: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($PROGMC_PLAN, 4); ?>" style="max-width:60px; text-align:right" class="inplabel"> 
                          	%
							</td>
                      	</tr>
                        <tr style="text-align:left;">
                         	<td nowrap>NILAI KONTRAK</td>
                          	<td>:</td>
                          	<td>
                            	<input type="text" name="theCode" id="theCode" value="<?php echo number_format($PRJCOST, $decFormat); ?>" style="max-width:120px; text-align:right" class="inplabel">
                            </td>
                          	<td nowrap>REALISASI PROGRES</td>
                          	<td>: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($PROGMC_REAL, 4); ?>" style="max-width:60px; text-align:right" class="inplabel"> 
                          	%
                            </td>
                      	</tr>
                        <?php
							// NILAI PEKERJAAN TAMBAH KURANG
								$TOTAL_SI		= 0;
								$sqlTOTAL_SI	= "SELECT SUM(SI_APPVAL) AS TOTAL_SI FROM tbl_siheader 
													WHERE PRJCODE = '$PRJCODECOL' AND SI_STAT = 3
														AND SI_DATE < '$End_Date'";
								$resTOTAL_SI	= $this->db->query($sqlTOTAL_SI)->result();
								foreach($resTOTAL_SI as $rowTOTAL_SI):
									$TOTAL_SI	= $rowTOTAL_SI->TOTAL_SI;
								endforeach;
								
							// REALISASI TAGIHAN
								$TOTAL_INCOME	= 0;
								$sqlTOTAL_INC	= "SELECT SUM(PINV_PAIDAM) AS TOTAL_INCOME FROM tbl_projinv_header 
													WHERE PRJCODE = '$PRJCODECOL' AND PINV_STAT = 3
														AND PINV_DATE < '$End_Date'";
								$resTOTAL_INC	= $this->db->query($sqlTOTAL_INC)->result();
								foreach($resTOTAL_INC as $rowTOTAL_INC):
									$TOTAL_INCOME	= $rowTOTAL_INC->TOTAL_INCOME;
								endforeach;
								
							// NILAI KONTRAK TOTAL
								$TOTAL_PRJ		= $PRJCOST + $TOTAL_SI;
						?>
                        <tr style="text-align:left;">
                         	<td nowrap>NILAI PEKERJAAN TAMBAH KURANG&nbsp;</td>
                          	<td>:</td>
                          	<td style="text-align:left;">
                                <input type="text" name="theCode" id="theCode" value="<?php echo number_format($SI_PLAN, $decFormat); ?>" style="max-width:120px; text-align:right" class="inplabel">
                            </td>
                          	<td style="text-align:left;" nowrap>REALISASI TAGIHAN</td>
                          	<td style="text-align:left;">: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($DEVIASI_PROG, 4); ?>" style="max-width:60px; text-align:right" class="inplabel">
                          	%</td>
                      	</tr>
                        <tr style="text-align:left;">
                         	<td nowrap>NILAI KONTRAK TOTAL</td>
                          	<td>:</td>
                          	<td style="text-align:left;">
                            	<input type="text" name="theCode" id="theCode" value="<?php echo number_format($TOTAL_PRJ, $decFormat); ?>" style="max-width:120px; text-align:right" class="inplabel">
                            </td>
                          	<td style="text-align:left;">DEVIASI PROGRES</td>
                          	<td style="text-align:left;">: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($DEVIASI_PROG, 4); ?>" style="max-width:60px; text-align:right" class="inplabel"> 
                          	%</td>
                      	</tr>
                        <tr style="text-align:left;">
                          <td nowrap valign="top">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td colspan="3">&nbsp;</td>
                        </tr>
                    </table>
		    </td>
            </tr>
            <tr>
				<td colspan="3" class="style2" style="text-align:left; font-size:12px">
              	<table width="100%" border="1" style="size:auto; font-size:12px;">
                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:14px;">
                    	<td width="3%">&nbsp;NO.&nbsp;</td>
                      	<td colspan="2">URAIAN</td>
                        <td width="20%">RENCANA AWAL</td>
                        <td width="15%">REALISASI<br ></td>
                        <td width="16%">PROYEKSI (%)</td>
                	</tr>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">A.</td>
                	  <td colspan="2">&nbsp;PENDAPATAN</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                    <?php
						$PROGMC_PLAN_AM	= $PROGMC_PLAN * $PRJCOST / 100;
						$PROGMC_REAL_AM	= $PROGMC_REAL * $PRJCOST / 100;
					?>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td width="9%" style="border-right: 0">&nbsp;Progress MC</td>
                	  <td width="37%" style="text-align:right; border-left: 0"><?php echo number_format($PROGMC_PLAN, 2); ?> %&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($PROGMC_PLAN_AM, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($PROGMC_REAL_AM, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="border-right: 0">&nbsp;Pekerjaan Tambah Kurang</td>
                	  <td style="text-align:right"><?php echo number_format($SI_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td style="border-right: 0" nowrap>&nbsp;Pekerjaan Katrolan</td>
                	  <td style="text-align:right; border-left: 0">&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($MC_CAT_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr style="display:none">
                	  <td>&nbsp;</td>
                	  <td style="border-right: 0" nowrap>&nbsp;Pekerjaan Lain-lain</td>
                	  <td style="text-align:right; border-left: 0">&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($MC_OTH_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td style="border-right: 0">&nbsp;</td>
                	  <td style="text-align:right; border-left: 0">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td style="border-right: 0">&nbsp;Selisih Kurs</td>
                	  <td style="text-align:right; border-left: 0">&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($KURS_DEV_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td style="border-right: 0">&nbsp;Asuransi</td>
                	  <td style="text-align:right; border-left: 0">&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($ASSURAN_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                    <?php
						$SUBTOT_AA	= $PROGMC_PLAN_AM + $SI_PLAN + $MC_CAT_PLAN + $MC_OTH_PLAN + $KURS_DEV_PLAN + $ASSURAN_PLAN;
					?>
                	<tr style="font-weight:bold; background:#CCCCCC;font-size:14px">
                	  <td>&nbsp;</td>
                	  <td colspan="2" align="center">&nbsp;Sub Total (A)</td>
                	  <td style="text-align:right">&nbsp;<?php echo number_format($SUBTOT_AA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;<?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;<?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">B.</td>
                	  <td colspan="2" style="border-right: 0">&nbsp;BIAYA  CASH : </td>
                	  <td style="text-align:right"><?php echo number_format($CASH_EXPENSE, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2" nowrap style="border-right: 0">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                    <?php
						$SUBTOT_BA	= $CASH_EXPENSE;
						$SUBTOT_BB	= $DEFAULTVAL;
					?>
                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="font-weight:bold; text-align:center">&nbsp;Sub Total (B)</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_BA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_BB, 0); ?>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td style="border-right: 0">&nbsp;</td>
                	  <td style="border-left: 0">&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">C.</td>
                	  <td colspan="2">&nbsp;BIAYA  PROYEK DI PUSAT :</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                    <?php
						// BIAYA PROYEK DI PUSAT : BAHAN --- DARI UM BERTIPE M
							/*$TOTAL_BHN		= 0;
							$sqlTOTAL_BHN	= "SELECT SUM(A.ITM_TOTAL) AS TOTAL_BHN FROM tbl_um_detail A
												INNER JOIN tbl_um_header X ON A.UM_NUM = X.UM_NUM
													AND X.PRJCODE = '$PRJCODECOL'
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
													AND B.PRJCODE = '$PRJCODECOL'
													AND B.ITM_GROUP = 'M'
												WHERE A.PRJCODE = '$PRJCODECOL' AND X.UM_STAT = 3
													AND UM_DATE < '$End_Date'";
							$resTOTAL_BHN	= $this->db->query($sqlTOTAL_BHN)->result();
							foreach($resTOTAL_BHN as $rowTOTAL_BHN):
								$TOTAL_BHN	= $rowTOTAL_BHN->TOTAL_BHN;
							endforeach;*/
							
						// BIAYA PROYEK DI PUSAT : UPAH --- DARI UM BERTIPE U
							/*$TOTAL_UPH		= 0;	// enjadi BPP_UPH_PLAN
							$sqlTOTAL_UPH	= "SELECT SUM(A.ITM_TOTAL) AS TOTAL_UPH FROM tbl_um_detail A
												INNER JOIN tbl_um_header X ON A.UM_NUM = X.UM_NUM
													AND X.PRJCODE = '$PRJCODECOL'
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
													AND B.PRJCODE = '$PRJCODECOL'
													AND B.ITM_GROUP = 'U'
												WHERE A.PRJCODE = '$PRJCODECOL' AND X.UM_STAT = 3
													AND UM_DATE < '$End_Date'";
							$resTOTAL_UPH	= $this->db->query($sqlTOTAL_UPH)->result();
							foreach($resTOTAL_UPH as $rowTOTAL_UPH):
								$TOTAL_UPH	= $rowTOTAL_UPH->TOTAL_UPH;
							endforeach;*/
							
						// BIAYA PROYEK DI PUSAT : ALAT --- DARI UM BERTIPE T
							/*$TOTAL_ALT		= 0; // menjadi BPP_ALAT_PLAN
							$sqlTOTAL_ALT	= "SELECT SUM(A.ITM_TOTAL) AS TOTAL_ALT FROM tbl_um_detail A
												INNER JOIN tbl_um_header X ON A.UM_NUM = X.UM_NUM
													AND X.PRJCODE = '$PRJCODECOL'
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
													AND B.PRJCODE = '$PRJCODECOL'
													AND B.ITM_GROUP = 'T'
												WHERE A.PRJCODE = '$PRJCODECOL' AND X.UM_STAT = 3
													AND UM_DATE < '$End_Date'";
							$resTOTAL_ALT	= $this->db->query($sqlTOTAL_ALT)->result();
							foreach($resTOTAL_ALT as $rowTOTAL_ALT):
								$TOTAL_ALT	= $rowTOTAL_ALT->TOTAL_ALT;
							endforeach;*/
							
						// BIAYA PROYEK DI PUSAT : ALAT --- DARI UM BERTIPE SUB
							/*$TOTAL_SUB		= 0;
							$sqlTOTAL_SUB	= "SELECT SUM(A.ITM_TOTAL) AS TOTAL_SUB FROM tbl_um_detail A
												INNER JOIN tbl_um_header X ON A.UM_NUM = X.UM_NUM
													AND X.PRJCODE = '$PRJCODECOL'
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
													AND B.PRJCODE = '$PRJCODECOL'
													AND B.ITM_GROUP = 'T'
												WHERE A.PRJCODE = '$PRJCODECOL' AND X.UM_STAT = 3
													AND UM_DATE < '$End_Date'";
							$resTOTAL_SUB	= $this->db->query($sqlTOTAL_SUB)->result();
							foreach($resTOTAL_SUB as $rowTOTAL_SUB):
								$TOTAL_SUB	= $rowTOTAL_SUB->TOTAL_SUB;
							endforeach;*/
					?>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Material</td>
                	  <td style="text-align:right"><?php echo number_format($BPP_MTR_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Upah</td>
                	  <td style="text-align:right"><?php echo number_format($BPP_UPH_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Alat</td>
                	  <td style="text-align:right"><?php echo number_format($BPP_ALAT_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Subkontraktor</td>
                	  <td style="text-align:right"><?php echo number_format($BPP_SUBK_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Biaya Administrasi Umum (BAU)</td>
                	  <td style="text-align:right"><?php echo number_format($BPP_BAU_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Rupa-rupa</td>
                	  <td style="text-align:right"><?php echo number_format($BPP_OTH_PLAN, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                    <?php
						$SUBTOT_CA	= $BPP_MTR_PLAN + $BPP_UPH_PLAN + $BPP_ALAT_PLAN + $BPP_SUBK_PLAN + $BPP_BAU_PLAN + $BPP_OTH_PLAN;
					?>
                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="font-weight:bold; text-align:center">Sub Total (C)</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
              	  	</tr>
                    <?php
						// Stock BAHAN
							/*$TOTAL_SBHN		= 0;
							$sqlTOTAL_SBHN	= "SELECT SUM(A.ITM_TOTAL) AS TOTAL_SBHN
												FROM tbl_ir_detail A
													INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
														AND B.PRJCODE = '$PRJCODECOL'
													INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
														AND C.PRJCODE = '$PRJCODECOL'
														-- AND C.ISMTRL = 1
												WHERE
													A.PRJCODE = '$PRJCODECOL'
													AND B.IR_DATE < '$End_Date'
													AND B.IR_STAT IN (3,6)";
							$resTOTAL_SBHN	= $this->db->query($sqlTOTAL_SBHN)->result();
							foreach($resTOTAL_SBHN as $rowTOTAL_SBHN):
								$TOTAL_SBHN	= $rowTOTAL_SBHN->TOTAL_SBHN;
							endforeach;*/
						
						// Stock Pelumas & BBM
							/*$TOTAL_SFUEL	= 0;
							$sqlTOTAL_SFUEL	= "SELECT SUM(A.ITM_TOTAL) AS TOTAL_SFUEL
												FROM tbl_ir_detail A
													INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
														AND B.PRJCODE = '$PRJCODECOL'
													INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
														AND C.PRJCODE = '$PRJCODECOL'
														AND C.ISFUEL = 1 OR C.ISLUBRIC = 1
												WHERE
													A.PRJCODE = '$PRJCODECOL'
													AND B.IR_DATE < '$End_Date'
													AND B.IR_STAT IN (3,6)";
							$resTOTAL_SFUEL	= $this->db->query($sqlTOTAL_SFUEL)->result();
							foreach($resTOTAL_SFUEL as $rowTOTAL_SFUEL):
								$TOTAL_SFUEL	= $rowTOTAL_SFUEL->TOTAL_SFUEL;
							endforeach;*/
						
						// Stock Suku Cadang / Tools
							/*$TOTAL_STLS	= 0;
							$sqlTOTAL_STLS	= "SELECT SUM(A.ITM_TOTAL) AS TOTAL_STLS
												FROM tbl_ir_detail A
													INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
														AND B.PRJCODE = '$PRJCODECOL'
													INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
														AND C.PRJCODE = '$PRJCODECOL'
														AND C.ISPART = 1
												WHERE
													A.PRJCODE = '$PRJCODECOL'
													AND B.IR_DATE < '$End_Date'
													AND B.IR_STAT IN (3,6)";
							$resTOTAL_STLS	= $this->db->query($sqlTOTAL_STLS)->result();
							foreach($resTOTAL_STLS as $rowTOTAL_STLS):
								$TOTAL_STLS	= $rowTOTAL_STLS->TOTAL_STLS;
							endforeach;*/
					?>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">D.</td>
                	  <td colspan="2">&nbsp;STOCK / PERSEDIAAN :</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr style="display:none">
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Stock</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Bahan
                      </td>
                	  <td style="text-align:right"><?php echo number_format($STOCK_MTR, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Pelumas &amp; BBM</td>
                	  <td style="text-align:right"><?php echo number_format($STOCK_BBM, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;Suku Cadang / Tools
                      </td>
                	  <td style="text-align:right"><?php echo number_format($STOCK_TOOLS, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                    <?php
						$SUBTOT_DA	= $STOCK_MTR + $STOCK_BBM + $STOCK_TOOLS;
					?>
                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="font-weight:bold; text-align:center">Sub Total (D)</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_DA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                    <?php
						// BEBAN ALAT
							$TOTAL_ASTEXP		= 0;
							$sqlTOTAL_ASTEXP	= "SELECT SUM(A.ASEXP_AMOUNT) AS TOTAL_ASTEXP
													FROM tbl_asset_expd A
														INNER JOIN tbl_asset_exph B ON A.ASEXP_NUM = B.ASEXP_NUM
															AND B.PRJCODE = '$PRJCODECOL'
													WHERE
														B.ASEXP_DATE < '$End_Date'
														AND B.ASEXP_STAT IN (3,6)";
							$resTOTAL_ASTEXP	= $this->db->query($sqlTOTAL_ASTEXP)->result();
							foreach($resTOTAL_ASTEXP as $rowTOTAL_ASTEXP):
								$TOTAL_ASTEXP	= $rowTOTAL_ASTEXP->TOTAL_ASTEXP;
							endforeach;
							
						$SUBTOT_EA	= $TOTAL_ASTEXP;
						$SUBTOT_FA	= $SUBTOT_BA + $SUBTOT_CA + $SUBTOT_DA + $SUBTOT_EA;
					?>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">E.</td>
                	  <td colspan="2">&nbsp;BEBAN ALAT
                      </td>
                	  <td style="text-align:right"><?php echo number_format($EXP_TOOLS, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">F.</td>
                	  <td colspan="2">&nbsp;BIAYA OPERASI PROYEK (B+C+D+E)</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_FA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                    <?php
						$SUBTOT_GA	= $SUBTOT_AA - $SUBTOT_FA;
					?>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">G.</td>
                	  <td colspan="2">&nbsp;KONTRIBUSI PROYEK (A-F)</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_GA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
              	  	</tr>
                	<tr style="font-weight:bold; font-size:14px">
                	  <td align="center">H.</td>
                	  <td colspan="2">&nbsp;BEBAN-BEBAN</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="border-right: 0">&nbsp;BAU Pusat &amp; Cabang              	    </td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
               	    </tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="border-right: 0">&nbsp;Bunga              	    </td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
               	    </tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="border-right: 0">&nbsp;PPh              	    </td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
                	  <td style="text-align:right">&nbsp;</td>
               	    </tr>
                    <?php
						$SUBTOT_HA	= $DEFAULTVAL;
					?>
                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
                	  <td>&nbsp;</td>
                	  <td colspan="2" style="font-weight:bold; text-align:center">Sub Total (H)</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_HA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
              	  	</tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  	</tr>
                    <?php
						$SUBTOT_IA	= $SUBTOT_GA - $SUBTOT_HA;
					?>
                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
                	  <td align="center">I.</td>
                	  <td colspan="2">&nbsp;LABA / RUGI : (G-H)</td>
                	  <td style="text-align:right"><?php echo number_format($SUBTOT_IA, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
                	  <td style="text-align:right"><?php echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
              	  	</tr>
                	<tr style="font-weight:bold;">
                	  <td>&nbsp;</td>
                	  <td colspan="2">&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  </tr>
                	<tr style="font-weight:bold; background:#CCCCCC;">
                	  <td>&nbsp;</td>
                	  <td colspan="2">POST NERACA :</td>
                	  <td style="text-align:right"><?php // echo number_format($DEFAULTVAL, 0); ?>&nbsp;</td>
                	  <td>&nbsp;</td>
                	  <td>&nbsp;</td>
              	  </tr>
                </table>
           	  </td>
            </tr>
        </table>
</section>
</body>
</html>