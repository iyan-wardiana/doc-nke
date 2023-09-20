<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Junli 2018
 * File Name	= profit_loss_view.php
 * Location		= -
*/
$Periode1 = date('YmdHis');
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LapLabaRugi_$Periode1.xls");
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

$PeriodeD 	= date('Y-m-d',strtotime($End_Date));
$isHO		= 0;

if($TOTPROJ == 1)
{
	$PeriodeD = date('Y-m-d',strtotime($End_Date));
	
	$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST, isHO FROM tbl_project WHERE PRJCODE IN ('$PRJCODECOL')";
	$resPRJ	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE 	= $rowPRJ->PRJCODE;
		$PRJNAME 	= $rowPRJ->PRJNAME;
		$PRJCOST 	= $rowPRJ->PRJCOST;
		$isHO 		= $rowPRJ->isHO;
	endforeach;
	$PRJCODECOLL	= $PRJCODE;
	$PRJNAMECOLL	= $PRJNAME;
}
else
{
	$PROG_MC_A	= 0;
	$PRJCODED	= 'Multi Project Code';
	$PRJNAMED 	= 'Multi Project Name';
	$PRJNAME 	= 'Multi Project Name';
	$myrow		= 0;
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME, A.PRJCOST FROM tbl_project A 
					WHERE A.PRJCODE IN ('$PRJCODECOL')
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
$DEFAULTVAL		= 0;
$PERIODE 		= 0;
$PRJADD 		= 0;
$SIAPPVAL 		= 0;
$PROG_PLAN 		= 0;
$PROG_REAL 		= 0;
$INV_REAL 		= 0;
$PROGMC 		= 0;
$PROGMC_PLAN_X 	= 0;
$PROGMC_REAL	= 0;
$SI_PLAN 		= 0;
$SI_REAL 		= 0;
$SI_PROYEKSI 	= 0;
$MC_CAT_PLAN 	= 0;
$MC_CAT_REAL 	= 0;
$PROGMC_REALA	= 0;
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
$BPP_MTR_PLAN_X 	= 0;
$BPP_MTR_REAL_X 	= 0;
$BPP_MTR_PROYEKSI = 0;
$BPP_UPH_PLAN_X 	= 0;
$BPP_UPH_REAL_X 	= 0;
$BPP_UPH_PROYEKSI = 0;
$BPP_ALAT_PLAN_X	= 0;
$BPP_ALAT_REAL_X 	= 0;
$BPP_ALAT_PROYEKSI = 0;
$BPP_SUBK_PLAN_X 	= 0;
$BPP_SUBK_REAL_X	= 0;
$BPP_SUBK_PROYEKSI = 0;
$BPP_BAU_PLAN_X 	= 0;
$BPP_BAU_REAL_X 	= 0;
$BPP_BAU_PROYEKSI = 0;
$BPP_OTH_PLAN_X 	= 0;
$BPP_OTH_REAL_X 	= 0;
$BPP_OTH_PROYEKSI = 0;
$BPP_I_PLAN_X 	= 0;
$BPP_I_REAL_X 	= 0;
$BPP_I_PROYEKSI = 0;
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
$PERIODEY	= date('Y', strtotime($PeriodeD));

$sqlLRC		= "tbl_profitloss WHERE PRJCODE IN ('$PRJCODECOL')
				AND MONTH(PERIODE) = '$PERIODEM' AND YEAR(PERIODE) = '$PERIODEY'";
$resLRC		= $this->db->count_all($sqlLRC);

/*if($resLRC > 0)
{*/
	$sqlGetLR	= "SELECT * FROM tbl_profitloss WHERE PRJCODE IN ('$PRJCODECOL')
					AND MONTH(PERIODE) = '$PERIODEM' AND YEAR(PERIODE) = '$PERIODEY' LIMIT 1";
	$resGetLR	= $this->db->query($sqlGetLR)->result();
/*}
else
{
	$PERIODE1	='0000-00-00';
	$sqlGetLR1	= "SELECT PERIODE FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' ORDER BY PERIODE DESC LIMIT 1";
	$resGetLR1	= $this->db->query($sqlGetLR1)->result();
	foreach($resGetLR1 as $rowLR1):
		$PERIODE1	= $rowLR1->PERIODE;
	endforeach;
	$PERIODEM	= date('m', strtotime($PERIODE1));
	$PERIODEY	= date('Y', strtotime($PERIODE1));
	
	$sqlGetLR	= "SELECT * FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' 
					AND MONTH(PERIODE) = '$PERIODEM' AND YEAR(PERIODE) = '$PERIODEY' LIMIT 1";
	$resGetLR	= $this->db->query($sqlGetLR)->result();
}*/
foreach($resGetLR as $rowLR):
	$PERIODE 		= $rowLR->PERIODE;
	//$PRJCODE 		= $rowLR->PRJCODE;
	//$PRJNAME 		= $rowLR->PRJNAME;
	//$PRJCOST 		= $rowLR->PRJCOST;
	$PRJADD 		= $rowLR->PRJADD;
	$SIAPPVAL 		= $rowLR->SIAPPVAL;
	$PROG_PLAN 		= $rowLR->PROG_PLAN;
	$PROG_REAL 		= $rowLR->PROG_REAL;
	$INV_REAL 		= $rowLR->INV_REAL;
	$PROGMC 		= $rowLR->PROGMC;
	$PROGMC_PLAN_X 	= $rowLR->PROGMC_PLAN;
	$PROGMC_REAL	= $rowLR->PROGMC_REAL;
	$PROGMC_REALA	= $rowLR->PROGMC_REALA;
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
	$BPP_MTR_PLAN_X 	= $rowLR->BPP_MTR_PLAN;
	$BPP_MTR_REAL_X 	= $rowLR->BPP_MTR_REAL;
	$BPP_MTR_PROYEKSI = $rowLR->BPP_MTR_PROYEKSI;
	$BPP_UPH_PLAN_X		= $rowLR->BPP_UPH_PLAN;
	$BPP_UPH_REAL_X 	= $rowLR->BPP_UPH_REAL;
	$BPP_UPH_PROYEKSI = $rowLR->BPP_UPH_PROYEKSI;
	$BPP_ALAT_PLAN_X	= $rowLR->BPP_ALAT_PLAN;
	$BPP_ALAT_REAL_X 	= $rowLR->BPP_ALAT_REAL;
	$BPP_ALAT_PROYEKSI = $rowLR->BPP_ALAT_PROYEKSI;
	$BPP_SUBK_PLAN_X	= $rowLR->BPP_SUBK_PLAN;
	$BPP_SUBK_REAL_X	= $rowLR->BPP_SUBK_REAL;
	$BPP_SUBK_PROYEKSI = $rowLR->BPP_SUBK_PROYEKSI;
	$BPP_BAU_PLAN_X 	= $rowLR->BPP_BAU_PLAN;
	$BPP_BAU_REAL_X 	= $rowLR->BPP_BAU_REAL;
	$BPP_BAU_PROYEKSI = $rowLR->BPP_BAU_PROYEKSI;
	$BPP_OTH_PLAN_X 	= $rowLR->BPP_OTH_PLAN;
	$BPP_OTH_REAL_X 	= $rowLR->BPP_OTH_REAL;
	$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
	$BPP_I_PLAN_X 	= $rowLR->BPP_I_PLAN;
	$BPP_I_REAL_X 	= $rowLR->BPP_I_REAL;
	$BPP_I_PROYEKSI = $rowLR->BPP_I_PROYEKSI;
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

$End_Date 		= date('Y-m-d',strtotime($End_Date));
$End_DateBef1	= date('Y-m-d', strtotime('-1 month', strtotime($End_Date)));
$End_DateBef	= date('Y-m-t', strtotime('-1 day', strtotime($End_DateBef1)));
$PERIODEM_BEF	= date('m', strtotime($End_DateBef));
$PERIODEY_BEF	= date('Y', strtotime($End_DateBef));
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
	<title>Report</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	   folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
	    <!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
	<!-- daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
	<!-- Bootstrap Color Picker -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
	<!-- Bootstrap time Picker -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
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
        width: 29.7cm;
        min-height: 21cm;
        padding: 0.5cm;
        margin: 0.5cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .subpage {
        padding: 1cm;
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
	                <td width="9%">
	                    <div id="Layer1">
	                        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
	                        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
	                        <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
	                <td width="52%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
	                <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
	            </tr>
	            <tr>
	                <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="100" height="44"></td>
	                <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:18px">PROJECT PROFIT AND LOSS</td>
	          	</tr>
	            <tr>
	                <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo strtoupper($comp_name); ?></span></td>
	            </tr>
	            <tr>
	                <td colspan="3" class="style2" style="text-align:left;"><hr></td>
	            </tr>
				<?php
	                // ------------------------------------ H E A D E R ------------------------------------
	                $PRJNAMEV	= $PRJNAME;
	                $PERIODEV	= date('F Y',strtotime($End_Date));
	                $PRJCOSTV	= $PRJCOST;
	                
	                    // NILAI PEKERJAAN TAMBAH KURANG - BEFORE
	                        /*$TOTAL_SIB		= 0;
	                        $sqlTOTAL_SI	= "SELECT SUM(SI_APPVAL) AS TOTAL_SI FROM tbl_siheader 
	                                            WHERE PRJCODE = '$PRJCODECOL' AND SI_STAT = 3
	                                                AND SI_DATE < '$End_Date'";
	                        $resTOTAL_SI	= $this->db->query($sqlTOTAL_SI)->result();
	                        foreach($resTOTAL_SI as $rowTOTAL_SI):
	                            $TOTAL_SIB	= $rowTOTAL_SI->TOTAL_SI;
	                        endforeach;*/
							
							$TOTAL_SIT 	= 0;	// SI TOTAL
							$sqlSITOT 	= "SELECT SUM(SI_APPVAL) AS TOTAL_SIT FROM tbl_siheader WHERE PRJCODE IN ('$PRJCODECOL')
											AND SI_INCCON = 1 AND SI_STAT  IN (3,6) AND SI_DATE <= '$End_DateBef'";
							$reSITOT	= $this->db->query($sqlSITOT)->result();
							foreach($reSITOT as $rowSITOT):
								$TOTAL_SIT 	= $rowSITOT->TOTAL_SIT;
							endforeach;
							
							$TOTAL_SIP 	= 0;
							/*$sqlLRBEF	= "SELECT SUM(SI_PLAN) AS SI_PLAN
											FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' 
												AND PERIODE <= '$End_DateBef'";
							$reslLRBEF	= $this->db->query($sqlLRBEF)->result();
							foreach($reslLRBEF as $rowLRB):
								$TOTAL_SIP 	= $rowLRB->SI_PLAN;
							endforeach;*/
							$TOTAL_SIP 	= $TOTAL_SIT;
							
							$TOTAL_SIB 	= 0;
							$sqlLRBEF	= "SELECT SUM(SI_REAL) AS SI_REAL
											FROM tbl_profitloss WHERE PRJCODE IN ('$PRJCODECOL')
												AND PERIODE <= '$End_DateBef'";
							$reslLRBEF	= $this->db->query($sqlLRBEF)->result();
							foreach($reslLRBEF as $rowLRB):
								$TOTAL_SIB 	= $rowLRB->SI_REAL;
							endforeach;
							$TOTAL_SIB 	= $TOTAL_SIT;
	                    
	                    // NILAI PEKERJAAN TAMBAH KURANG - CURRENT
	                        /*$TOTAL_SIC		= 0;
	                        $sqlTOTAL_SIC	= "SELECT SUM(SI_APPVAL) AS TOTAL_SI FROM tbl_siheader 
	                                            WHERE PRJCODE = '$PRJCODECOL' AND SI_STAT = 3
	                                                AND MONTH(SI_DATE) = '$PERIODEM'";
	                        $resTOTAL_SIC	= $this->db->query($sqlTOTAL_SIC)->result();
	                        foreach($resTOTAL_SIC as $rowTOTAL_SIC):
	                            $TOTAL_SIC	= $rowTOTAL_SIC->TOTAL_SI;
	                        endforeach;*/
							
							$TOTAL_SIC 	= 0;
							$sqlLRBEF	= "SELECT SUM(SI_REAL) AS SI_REAL
											FROM tbl_profitloss WHERE PRJCODE IN ('$PRJCODECOL')
												AND MONTH(PERIODE) = '$PERIODEM' AND YEAR(PERIODE) = '$PERIODEY'";
							$reslLRBEF	= $this->db->query($sqlLRBEF)->result();
							foreach($reslLRBEF as $rowLRB):
								$TOTAL_SIC 	= $rowLRB->SI_REAL;
							endforeach;
							
							$sqlSITOC 	= "SELECT SUM(SI_APPVAL) AS TOTAL_SITC FROM tbl_siheader WHERE PRJCODE IN ('$PRJCODECOL')
											AND SI_INCCON = 1 AND SI_STAT IN (3,6)
											AND MONTH(SI_DATE) = '$PERIODEM' AND YEAR(SI_DATE) = '$PERIODEY'";
							$reSITOC	= $this->db->query($sqlSITOC)->result();
							foreach($reSITOC as $rowSITOC):
								$TOTAL_SITC 	= $rowSITOC->TOTAL_SITC;
							endforeach;
							$TOTAL_SIC 	= $TOTAL_SITC;
	                        
	                //$TOTAL_SIV	= $TOTAL_SIB + $TOTAL_SIC;
	                $TOTAL_SIV	= $TOTAL_SIP;
	                $TOTAL_PRJ	= $PRJCOST + $TOTAL_SIV;
	                    
	                    // REALISASI TAGIHAN
	                        $TOTAL_INCOME	= 0;
	                        /*$sqlTOTAL_INC	= "SELECT SUM(A.GAmount) AS TOTAL_INCOME FROM tbl_br_detail A 
	                                            INNER JOIN tbl_br_header B ON A.BR_NUM = A.BR_NUM
	                                                AND B.BR_DOCTYPE = 'PINV' AND B.BR_STAT = 3
	                                            INNER JOIN tbl_projinv_header C ON C.PINV_CODE = A.DocumentNo
	                                                AND C.PRJCODE = '$PRJCODE' AND PINV_DATE < '$End_Date'";*/
	                        $sqlTOTAL_INC	= "SELECT DISTINCT A.BR_NUM, A.GAmount
													FROM tbl_br_detail A 
	                                            INNER JOIN tbl_br_header B ON A.BR_NUM = A.BR_NUM
	                                            	AND B.PRJCODE = A.PRJCODE
	                                                AND B.BR_DOCTYPE = 'PINV' AND B.BR_STAT = 3
	                                            INNER JOIN tbl_projinv_header C ON C.PINV_CODE = A.DocumentNo
	                                            	AND C.PINV_CAT = 2
	                                            WHERE A.PRJCODE IN ('$PRJCODECOL')";
	                        $resTOTAL_INC	= $this->db->query($sqlTOTAL_INC)->result();
	                        foreach($resTOTAL_INC as $rowTOTAL_INC):
	                            $GAmount	= $rowTOTAL_INC->GAmount;
	                            //$TOTAL_INCOME	= $rowTOTAL_INC->TOTAL_INCOME;
								$TOTAL_INCOME	= $TOTAL_INCOME + $GAmount;
	                        endforeach;
	                        
	                $TOTAL_INCOMEV	= $TOTAL_INCOME;
	                $TOTAL_INCOMEVP	= $TOTAL_INCOME / $TOTAL_PRJ * 100;
	                
	                // RENCANA PROGRES BEFORE
	                    $MC_PROG		= 0;
	                    $MC_PROGAPP		= 0;
	                    $MC_PROGVAL		= 0;
	                    $MC_PROGAPPVAL	= 0;
	                    $sqlGET_MC		= "SELECT SUM(MC_PROGCUR) AS MC_PROGCUR, SUM(MC_PROG) AS MC_PROG, SUM(MC_PROGAPP) AS MC_PROGAPP, 
												SUM(MC_PROGVAL) AS MC_PROGVAL, SUM(MC_PROGAPPVAL) AS MC_PROGAPPVAL FROM tbl_mcheader 
	                                        WHERE PRJCODE IN ('$PRJCODECOL') AND MC_STAT = 3
	                                            AND MC_DATE < '$End_Date'";
	                    $resGET_MC		= $this->db->query($sqlGET_MC)->result();
	                    foreach($resGET_MC as $rowGET_MC):
	                        $MC_PROGCUR		= $rowGET_MC->MC_PROGCUR;
	                        $MC_PROG		= $rowGET_MC->MC_PROG;
	                        $MC_PROGAPP		= $rowGET_MC->MC_PROGAPP;
	                        $MC_PROGVAL		= $rowGET_MC->MC_PROGVAL;
	                        $MC_PROGAPPVAL	= $rowGET_MC->MC_PROGAPPVAL;
	                    endforeach;
	                    // MS.191119021340-00275 : nilai prosentase laporan
	                    // Khusus PROGMC_PLAN diganti dari S-CURVE pada tiap akhir bulan periode laporan
	                    $PROGPLAN 		= $MC_PROGCUR;
	                    $sqlGET_PLN		= "SELECT Prg_PlanAkum AS PROGMC_PLAN, Prg_RealAkum AS PROGMC_REAL FROM tbl_projprogres
	                    					WHERE proj_Code IN ('$PRJCODECOL') AND MONTH(Prg_Date1) = '$PERIODEM' AND YEAR(Prg_Date1) = '$PERIODEY' ORDER BY Prg_Step DESC LIMIT 1";
	                    $resGET_PLN		= $this->db->query($sqlGET_PLN)->result();
	                    foreach($resGET_PLN as $rowGET_PLN):
	                        $PROGPLAN	= $rowGET_PLN->PROGMC_PLAN;
	                        $PROGREAL	= $rowGET_PLN->PROGMC_REAL;
	                    endforeach;

	                    $sqlGET_REAL	= "SELECT SUM(Prg_Real) AS PROGMC_REAL FROM tbl_projprogres
	                    					WHERE proj_Code IN ('$PRJCODECOL') AND MONTH(Prg_Date1) <= '$PERIODEM' AND YEAR(Prg_Date1) <= '$PERIODEY'";
	                    $resGET_REAL		= $this->db->query($sqlGET_REAL)->result();
	                    foreach($resGET_REAL as $rowGET_REAL):
	                        $PROGREAL	= $rowGET_REAL->PROGMC_REAL;
	                    endforeach;

	                    if($PROGPLAN == '')
	                    	$PROGPLAN 	= $MC_PROGCUR;
	                    if($PROGREAL == '')
	                    	$PROGREAL 	= $MC_PROGCUR;

	                    $PROGMC_PLAN	= $PROGPLAN;
	                    $PROGMC_PLANC	= $MC_PROGCUR;
	                    $PROGMC_REAL	= $PROGREAL;
	                    $PROGMC_PLAN_AM	= $MC_PROGVAL;
	                    $PROGMC_REAL_AM	= $MC_PROGAPPVAL;
						                    
	                // DEVIASI PROGRES
	                    //$DEVIASI_PROG	= $PROGMC_PLAN - $PROGMC_REAL;
	                    //$DEVIASI_PROG	= $MC_PROG - $MC_PROGAPP;
	                    //$DEVIASI_PROG	= $PROGMC_REAL - $TOTAL_INCOMEVP;
	                    // MS.191119021340-00275 : nilai prosentase laporan
	                    $DEVIASI_PROG	= $PROGMC_REAL - $PROGMC_PLAN;
						
					
	                // ------------------------------------ A. P E N D A P A T A N ------------------------------------
						$TOTAL_PRJ1			= $TOTAL_PRJ;
						//echo "TOTAL_PRJ1 = $TOTAL_PRJ1<br>";
						if($TOTAL_PRJ1 == 0)
							$TOTAL_PRJ1 = 1;
							
						// A.1 PROGRESS MC
							$PROGMC_REAL_AMB 	= 0;
							$sqlLRBEF	= "SELECT SUM(PROGMC_REALA) AS PROGMC_REALA
											FROM tbl_profitloss WHERE PRJCODE IN ('$PRJCODECOL')
												AND PERIODE <= '$End_DateBef'";
							$reslLRBEF	= $this->db->query($sqlLRBEF)->result();
							foreach($reslLRBEF as $rowLRB):
								$PROGMC_REAL_AMB 	= $rowLRB->PROGMC_REALA;
							endforeach;
							
							$PROGMC_REAL_AMB	= $PROGMC_REAL_AMB;
							$PROGMC_REAL_AMBP	= $PROGMC_REAL_AMB / $TOTAL_PRJ1 * 100;
							//echo "$PROGMC_REAL_AMBP	= $PROGMC_REAL_AMB / $TOTAL_PRJ1 * 100";
							$PROGMC_REAL_AMC	= $PROGMC_REALA;
							$PROGMC_REAL_AMCP	= $PROGMC_REAL_AMC / $TOTAL_PRJ1 * 100;
							
						// A.2 PEKERJAAN TAMBAH KURANG
							$TOTAL_SIB			= $TOTAL_SIB;									// SI SEBELUMNYA
							$TOTAL_SIBP			= $TOTAL_SIB / $TOTAL_PRJ1 * 100;
							$TOTAL_SIC			= $TOTAL_SIC;
							$TOTAL_SICP			= $TOTAL_SIC / $TOTAL_PRJ1 * 100;
							
						// A.3 PEKERJAAN KATROLAN
							$MC_PROGC		= 0;
							$MC_PROGAPPC	= 0;
							$MC_PROGVALC	= 0;
							$MC_PROGAPPVALC	= 0;
							$sqlGET_MCC		= "SELECT PINV_PROG, PINV_PROGVAL, PINV_PROGVALPPn, GPINV_TOTVAL
												FROM tbl_projinv_header 
												WHERE PRJCODE IN ('$PRJCODECOL') AND PINV_STAT IN (3,6)
													AND PINV_CAT = 2
													AND MONTH(PINV_DATE) = '$PERIODEM' ORDER BY PINV_DATE DESC";
							$resGET_MCC		= $this->db->query($sqlGET_MCC)->result();
							foreach($resGET_MCC as $rowGET_MCC):
								$MC_PROGC		= $rowGET_MCC->PINV_PROG;
								$MC_PROGAPPC	= $rowGET_MCC->PINV_PROG;
								$MC_PROGVALC	= $rowGET_MCC->PINV_PROGVAL;
								$MC_PROGAPPVALC	= $rowGET_MCC->PINV_PROGVAL;
							endforeach;
							$TOTAL_SIKATROL		= $PROGMC_PLAN - $PROGMC_REAL;
							$TOTAL_SIKATROLP	= $TOTAL_SIKATROL / $TOTAL_PRJ1 * 100;
							$TOTAL_SIKATROL_AMC	= $MC_PROGVALC - $MC_PROGAPPVALC;
							$TOTAL_SIKATROL_AMCP= $TOTAL_SIKATROL_AMC / $TOTAL_PRJ1 * 100;
							
						// A.4 PENDAPATAN LAIN-LAIN
							// A. NILAI INCOME - BEFORE
								$TOTAL_INBD		= 0;
								$TOTAL_INBK		= 0;
								$TOTAL_INB		= 0;
								$sqlTOTAL_INB	= "SELECT SUM(A.Base_Debet) AS TOTAL_IND, SUM(A.Base_Kredit) AS TOTAL_INK
													FROM
														tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType != 'PRJINV'
													INNER JOIN tbl_chartaccount D ON A.Acc_Id = D.Account_Number
														AND D.Account_Category IN (4)
														AND D.PRJCODE = A.proj_Code
													WHERE A.proj_Code IN ('$PRJCODECOL')
														AND B.GEJ_STAT = 3
														AND B.JournalH_Date <= '$End_DateBef'";
								$resTOTAL_INB	= $this->db->query($sqlTOTAL_INB)->result();
								foreach($resTOTAL_INB as $rowTOTAL_INB):
									$TOTAL_INBD	= $rowTOTAL_INB->TOTAL_IND;
									$TOTAL_INBK	= $rowTOTAL_INB->TOTAL_INK;
								endforeach;
								$TOTAL_INB		= $TOTAL_INBK - $TOTAL_INBD;
								
							// B. NILAI INCOME - CURRENT
								$TOTAL_INCD		= 0;
								$TOTAL_INCK		= 0;
								$TOTAL_INC		= 0;
								$sqlTOTAL_INC	= "SELECT SUM(A.Base_Debet) AS TOTAL_IND, SUM(A.Base_Kredit) AS TOTAL_INK
													FROM
														tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType != 'PRJINV'
													INNER JOIN tbl_chartaccount D ON A.Acc_Id = D.Account_Number
														AND D.Account_Category IN (4)
														AND D.PRJCODE = A.proj_Code
													WHERE A.proj_Code IN ('$PRJCODECOL')
														AND B.GEJ_STAT = 3
														AND MONTH(B.JournalH_Date) = '$PERIODEM'";
								$resTOTAL_INC	= $this->db->query($sqlTOTAL_INC)->result();
								foreach($resTOTAL_INC as $rowTOTAL_INC):
									$TOTAL_INCD	= $rowTOTAL_INC->TOTAL_IND;
									$TOTAL_INCK	= $rowTOTAL_INC->TOTAL_INK;
								endforeach;
								$TOTAL_INC	= $TOTAL_INCK - $TOTAL_INCD;
							
						// SUB TOTAL A
							//$SUBTOT_AA	= $PRJCOST;
							$SUBTOT_AA	= $PRJCOST + $TOTAL_SIP;
							$SUBTOT_AA2	= $SUBTOT_AA;
								if($SUBTOT_AA == 0) $SUBTOT_AA2 = 1;
							//$SUBTOT_AB	= $PROGMC_REAL_AMB + $PROGMC_REAL_AMC + $TOTAL_INB;
							$SUBTOT_AB	= $PROGMC_REAL_AMB + $TOTAL_SIB + $TOTAL_INB;
							$SUBTOT_ABP	= $SUBTOT_AB / $SUBTOT_AA2 * 100;
							$SUBTOT_AC	= $PROGMC_REAL_AMC + $TOTAL_SIC + $TOTAL_INC;
							$SUBTOT_ACP	= $SUBTOT_AC / $SUBTOT_AA2 * 100;
							$SUBTOT_AD	= $SUBTOT_AB + $SUBTOT_AC;
							$SUBTOT_ADP	= $SUBTOT_AD / $SUBTOT_AA2 * 100;
							
							
	                // ------------------------------------ B. H U T A N G ------------------------------------	
						$SUBTOT_BA	= $CASH_EXPENSE;
						$SUBTOT_BB	= $DEFAULTVAL;
						$SUBTOT_BD	= 0;
							
	                // ------------------------------------ B / C. B I A Y A ------------------------------------
						// MENGHITUNG BIAYA / BEBAN SEBELUMNYA UNTUK SEMUA TIPE
							$BPP_MTR_BEF	= 0;
							$BPP_UPH_BEF	= 0;
							$BPP_ALT_BEF	= 0;
							$BPP_EXPT_BEF	= 0;
							$BPP_SUBK_BEF	= 0;
							$BPP_BAU_BEF	= 0;
							$BPP_OTH_BEF	= 0;
							$BPP_I_BEF		= 0;
							$sqlBEFLR		= "SELECT SUM(BPP_MTR_REAL) AS TOT_BEF_MTR, SUM(BPP_UPH_REAL) AS TOT_BEF_UPH,
													SUM(BPP_ALAT_REAL) AS TOT_BEF_ALT, SUM(EXP_TOOLS) AS TOT_BEF_EXPT, 
													SUM(BPP_SUBK_REAL) AS TOT_BEF_SUBK, SUM(BPP_BAU_REAL) AS TOT_BEF_BAU,
													SUM(BPP_OTH_REAL) AS TOT_BEF_OTH, SUM(BPP_I_REAL) AS TOT_I_OTH
												FROM tbl_profitloss WHERE PRJCODE IN ('$PRJCODECOL')
													AND PERIODE <= '$End_DateBef'";
							$resBEFLR		= $this->db->query($sqlBEFLR)->result();
							foreach($resBEFLR as $rowBEFLR):
								$BPP_MTR_BEF	= $rowBEFLR->TOT_BEF_MTR;
								$BPP_UPH_BEF	= $rowBEFLR->TOT_BEF_UPH;
								$BPP_ALT_BEF	= $rowBEFLR->TOT_BEF_ALT;
								$BPP_EXPT_BEF	= $rowBEFLR->TOT_BEF_EXPT;
								$BPP_SUBK_BEF	= $rowBEFLR->TOT_BEF_SUBK;
								$BPP_BAU_BEF 	= $rowBEFLR->TOT_BEF_BAU;
								$BPP_OTH_BEF 	= $rowBEFLR->TOT_BEF_OTH;
								$BPP_I_BEF 		= $rowBEFLR->TOT_I_OTH;
							endforeach;
							
						// B.1 BIAYA PROYEK DI PUSAT : BAHAN 		--- BERTIPE M
							$BPP_MTR_PLAN	= 0;
							$sql_MTR_PLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(ADD_JOBCOST) AS TOT_ADDPLUS,
													SUM(ADDM_JOBCOST) AS TOT_ADDMIN
												FROM
													tbl_joblist_detail WHERE PRJCODE IN ('$PRJCODECOL')
												AND ITM_GROUP = 'M' AND ISLAST = 1";
							$res_MTR_PLAN	= $this->db->query($sql_MTR_PLAN)->result();
							foreach($res_MTR_PLAN as $row_MTR_PLAN):
								$TOT_BUDG		= $row_MTR_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_MTR_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= $row_MTR_PLAN->TOT_ADDMIN;
								$TOTAL_MTR		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
								$BPP_MTR_PLAN	= $TOTAL_MTR;
								$BPP_MTR_PLAN1	= $TOTAL_MTR;
								if($BPP_MTR_PLAN == '')
								{
									$BPP_MTR_PLAN	= 0;
									$BPP_MTR_PLAN1	= 1;
								}
							
							// BEFORE PERCENTATION
								$BPP_MTR_BEFP	= $BPP_MTR_BEF / $BPP_MTR_PLAN1 * 100;
							
							// CURRENT PERCENTATION
								$BPP_MTR_REALC	= $BPP_MTR_REAL_X;
								$BPP_MTR_REALP	= $BPP_MTR_REALC / $BPP_MTR_PLAN1 * 100;
							
						// B.2 BIAYA PROYEK DI PUSAT : UPAH			--- BERTIPE U
							$BPP_UPH_PLAN	= 0;
							$sql_UPH_PLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(ADD_JOBCOST) AS TOT_ADDPLUS,
													SUM(ADDM_JOBCOST) AS TOT_ADDMIN
												FROM
													tbl_joblist_detail WHERE PRJCODE IN ('$PRJCODECOL')
												AND ITM_GROUP = 'U' AND ISLAST = 1";
							$res_UPH_PLAN	= $this->db->query($sql_UPH_PLAN)->result();
							foreach($res_UPH_PLAN as $row_UPH_PLAN):
								$TOT_BUDG		= $row_UPH_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_UPH_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= $row_UPH_PLAN->TOT_ADDMIN;
								$TOTAL_UPH		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$BPP_UPH_PLAN	= $TOTAL_UPH;
							$BPP_UPH_PLAN1	= $TOTAL_UPH;
							if($BPP_UPH_PLAN == '')
							{
								$BPP_UPH_PLAN	= 0;
								$BPP_UPH_PLAN1	= 1;
							}
							
							// BEFORE PERCENTATION
								$BPP_UPH_BEFP	= $BPP_UPH_BEF / $BPP_UPH_PLAN1 * 100;
							
							// CURRENT PERCENTATION
								$BPP_UPH_REALC	= $BPP_UPH_REAL_X;
								$BPP_UPH_REALP	= $BPP_UPH_REALC / $BPP_UPH_PLAN1 * 100;
							
						// B.3 BIAYA PROYEK DI PUSAT : ALAT 		--- BERTIPE T
							$BPP_ALAT_PLAN	= 0;
							$sql_ALAT_PLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(ADD_JOBCOST) AS TOT_ADDPLUS,
													SUM(ADDM_JOBCOST) AS TOT_ADDMIN
												FROM
													tbl_joblist_detail WHERE PRJCODE IN ('$PRJCODECOL')
												AND ITM_GROUP = 'T' AND ISLAST = 1";
							$res_ALAT_PLAN	= $this->db->query($sql_ALAT_PLAN)->result();
							foreach($res_ALAT_PLAN as $row_ALAT_PLAN):
								$TOT_BUDG		= $row_ALAT_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_ALAT_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= $row_ALAT_PLAN->TOT_ADDMIN;
								$TOTAL_ALT		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$BPP_ALT_PLAN	= $TOTAL_ALT;
							$BPP_ALT_PLAN1	= $TOTAL_ALT;
							if($BPP_ALT_PLAN == '')
							{
								$BPP_ALT_PLAN	= 0;
								$BPP_ALT_PLAN1	= 1;
							}
							
							// BEFORE PERCENTATION
							// ADA PENAMBAHAN BEBAN ALAT -> EXP_TOOLS
								$BPP_ALT_BEF1	= $BPP_ALT_BEF + $BPP_EXPT_BEF;
								$BPP_ALT_BEF	= $BPP_ALT_BEF1;
								$BPP_ALT_BEFP	= $BPP_ALT_BEF / $BPP_ALT_PLAN1 * 100;
							
							// CURRENT PERCENTATION
							// ADA PENAMBAHAN BEBAN ALAT -> EXP_TOOLS
								$BPP_ALT_REALC	= $BPP_ALAT_REAL_X + $EXP_TOOLS;
								$BPP_ALT_REALP	= $BPP_ALT_REALC / $BPP_ALT_PLAN1 * 100;
							
						// B.4 BIAYA PROYEK DI PUSAT : SUBKON 		--- BERTIPE SC
							$BPP_SUBK_PLAN	= 0;
							$sql_SUBK_PLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(ADD_JOBCOST) AS TOT_ADDPLUS,
													SUM(ADDM_JOBCOST) AS TOT_ADDMIN
												FROM
													tbl_joblist_detail WHERE PRJCODE IN ('$PRJCODECOL')
												AND ITM_GROUP IN ('S', 'SC') AND ISLAST = 1";
							$res_SUBK_PLAN	= $this->db->query($sql_SUBK_PLAN)->result();
							foreach($res_SUBK_PLAN as $row_SUBK_PLAN):
								$TOT_BUDG		= $row_SUBK_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_SUBK_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= $row_SUBK_PLAN->TOT_ADDMIN;
								$TOTAL_SUBK		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$BPP_SUBK_PLAN	= $TOTAL_SUBK;
							$BPP_SUBK_PLAN1	= $TOTAL_SUBK;
							if($BPP_SUBK_PLAN == '')
							{
								$BPP_SUBK_PLAN	= 0;
								$BPP_SUBK_PLAN1	= 1;
							}
							
							// BEFORE PERCENTATION
								$BPP_SUBK_BEFP	= $BPP_SUBK_BEF / $BPP_SUBK_PLAN1 * 100;
							
							// CURRENT PERCENTATION
								$BPP_SUBK_REALC	= $BPP_SUBK_REAL_X;
								$BPP_SUBK_REALP	= $BPP_SUBK_REALC / $BPP_SUBK_PLAN1 * 100;
							
						// B.5 BIAYA PROYEK DI PUSAT : SUBKON 		--- BERTIPE SC
							$BPP_BAU_PLAN	= 0;
							$sql_BAU_PLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(ADD_JOBCOST) AS TOT_ADDPLUS,
													SUM(ADDM_JOBCOST) AS TOT_ADDMIN
												FROM
													tbl_joblist_detail WHERE PRJCODE IN ('$PRJCODECOL')
												AND ITM_GROUP = 'GE' AND ISLAST = 1";
							$res_BAU_PLAN	= $this->db->query($sql_BAU_PLAN)->result();
							foreach($res_BAU_PLAN as $row_BAU_PLAN):
								$TOT_BUDG		= $row_BAU_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_BAU_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= $row_BAU_PLAN->TOT_ADDMIN;
								$TOTAL_BAU		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$BPP_BAU_PLAN	= $TOTAL_BAU;
							$BPP_BAU_PLAN1	= $TOTAL_BAU;
							if($BPP_BAU_PLAN == '')
							{
								$BPP_BAU_PLAN	= 0;
								$BPP_BAU_PLAN1	= 1;
							}
							
							// BEFORE PERCENTATION
								$BPP_BAU_BEFP	= $BPP_BAU_BEF / $BPP_BAU_PLAN1 * 100;
							
							// CURRENT PERCENTATION
								$BPP_BAU_REALC	= $BPP_BAU_REAL_X;
								$BPP_BAU_REALP	= $BPP_BAU_REALC / $BPP_BAU_PLAN1 * 100;
							
						// B.6 BIAYA PROYEK DI PUSAT : RUPA-RUPA 	--- BERTIPE O
							$BPP_OTH_PLAN	= 0;
							$sql_OTH_PLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(ADD_JOBCOST) AS TOT_ADDPLUS,
													SUM(ADDM_JOBCOST) AS TOT_ADDMIN
												FROM
													tbl_joblist_detail WHERE PRJCODE IN ('$PRJCODECOL')
												AND ITM_GROUP IN ('O') AND ISLAST = 1";
							$res_OTH_PLAN	= $this->db->query($sql_OTH_PLAN)->result();
							foreach($res_OTH_PLAN as $row_OTH_PLAN):
								$TOT_BUDG		= $row_OTH_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_OTH_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= $row_OTH_PLAN->TOT_ADDMIN;
								$TOTAL_OTH		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$BPP_OTH_PLAN	= $TOTAL_OTH;
							$BPP_OTH_PLAN1	= $TOTAL_OTH;
							if($BPP_OTH_PLAN == '')
							{
								$BPP_OTH_PLAN	= 0;
								$BPP_OTH_PLAN1	= 1;
							}
							
							// BEFORE PERCENTATION
								$BPP_OTH_BEFP	= $BPP_OTH_BEF / $BPP_OTH_PLAN1 * 100;
							
							// CURRENT PERCENTATION
								$BPP_OTH_REALC	= $BPP_OTH_REAL_X;
								$BPP_OTH_REALP	= $BPP_OTH_REALC / $BPP_OTH_PLAN1 * 100;
							
						// B.7 BIAYA PROYEK DI PUSAT : RUPA-RUPA 	--- BERTIPE I AND O
							$BPP_I_PLAN	= 0;
							$sql_I_PLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(ADD_JOBCOST) AS TOT_ADDPLUS,
													SUM(ADDM_JOBCOST) AS TOT_ADDMIN
												FROM
													tbl_joblist_detail WHERE PRJCODE IN ('$PRJCODECOL')
												AND ITM_GROUP IN ('I') AND ISLAST = 1";
							$res_I_PLAN	= $this->db->query($sql_I_PLAN)->result();
							foreach($res_I_PLAN as $row_I_PLAN):
								$TOT_BUDG		= $row_I_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_I_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= $row_I_PLAN->TOT_ADDMIN;
								$TOTAL_I		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$BPP_I_PLAN	= $TOTAL_I;
							$BPP_I_PLAN1= $TOTAL_I;
							if($BPP_I_PLAN == '')
							{
								$BPP_I_PLAN	= 0;
								$BPP_I_PLAN1= 1;
							}
							
							// BEFORE PERCENTATION
								$BPP_I_BEFP	= $BPP_I_BEF / $BPP_I_PLAN1 * 100;
							
							// CURRENT PERCENTATION
								$BPP_I_REALC	= $BPP_I_REAL_X;
								$BPP_I_REALP	= $BPP_I_REALC / $BPP_I_PLAN1 * 100;
							
						// B.7.1 BIAYA PROYEK DI PUSAT : UNDEFINE 	--- BERTIPE "-" - BEFORE
							$BPP_UNDEF_PLAN		= 0;
							$BPP_UNDEFB_REAL	= 0;
							$TOTAL_UNDEFD		= 0;
							$TOTAL_UNDEFK		= 0;
							/*$sql_UNDEFB_REAL	= "SELECT SUM(A.Base_Debet) AS TOTAL_UNDEFD, SUM(A.Base_Kredit) AS TOTAL_UNDEFK
													FROM
														tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
													INNER JOIN tbl_chartaccount D ON A.Acc_Id = D.Account_Number
														AND D.Account_Category IN (5,8,9)
														AND D.PRJCODE = '$PRJCODECOL'
													WHERE A.ITM_CATEG = '-'
														AND A.proj_Code = '$PRJCODECOL'
														AND B.GEJ_STAT IN (3,9)
														AND B.JournalType IN ('GEJ','VGEJ','CPRJ','VCPRJ','O-EXP','VO-EXP', 
														'OPN','VOPN','AU')
														AND B.JournalH_Date < '$End_DateBef'";
							$res_UNDEFB_REAL	= $this->db->query($sql_UNDEFB_REAL)->result();
							foreach($res_UNDEFB_REAL as $row_UNDEFB_REAL):
								$TOTAL_UNDEFD	= $row_UNDEFB_REAL->TOTAL_UNDEFD;
								$TOTAL_UNDEFK	= $row_UNDEFB_REAL->TOTAL_UNDEFK;
							endforeach;
							$TOTAL_UNDEFB_REAL1	= $TOTAL_UNDEFD - $TOTAL_UNDEFK;
							$TOTAL_UNDEFB_REAL	= $TOTAL_UNDEFB_REAL1 + $BPP_OTH1B_REAL;
							//echo "$TOTAL_UNDEFB_REAL = $TOTAL_UNDEFB_REAL1 + $BPP_OTH1B_REAL";
							//$BPP_UNDEFB_REAL	= $TOTAL_UNDEFB_REAL;
							$BPP_UNDEFB_REAL	= $TOTAL_UNDEFB_REAL;
							$BPP_UNDEFB_PLAN1	= $BPP_UNDEF_PLAN;*/
							
							$TOTAL_UNDEFB_REAL1	= 0;
							$TOTAL_UNDEFB_REAL	= 0;
							$BPP_UNDEFB_REAL	= 0;
							$BPP_UNDEFB_PLAN1	= 0;
							if($BPP_UNDEFB_PLAN1 == 0)
								$BPP_UNDEFB_PLAN1 = 1;
								
							$BPP_UNDEFB_REALP	= 100;
							if($BPP_UNDEFB_REAL == '')
								$BPP_UNDEFB_REAL	= 0;
							
						// B.7.2 BIAYA PROYEK DI PUSAT : UNDEFINE 	--- BERTIPE "-" - CURRENT
							$BPP_UNDEF_REAL	= 0;
							$TOTAL_UNDEFCD	= 0;
							$TOTAL_UNDEFCK	= 0;
							/*$sql_UNDEF_REAL	= "SELECT SUM(A.Base_Debet) AS TOTAL_UNDEFCD, SUM(A.Base_Kredit) AS TOTAL_UNDEFCK
													FROM
														tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
													INNER JOIN tbl_chartaccount D ON A.Acc_Id = D.Account_Number
														AND D.Account_Category IN (5,8,9)
														AND D.PRJCODE = '$PRJCODECOL'
													WHERE A.ITM_CATEG = '-'
														AND A.proj_Code = '$PRJCODECOL'
														AND B.GEJ_STAT IN (3,9)
														AND B.JournalType IN ('GEJ','VGEJ','CPRJ','VCPRJ','O-EXP','VO-EXP', 
														'OPN','VOPN','AU')
													AND MONTH(B.JournalH_Date) = '$PERIODEM'";
							$res_UNDEF_REAL	= $this->db->query($sql_UNDEF_REAL)->result();

							foreach($res_UNDEF_REAL as $row_UNDEF_REAL):
								$TOTAL_UNDEFCD	= $row_UNDEF_REAL->TOTAL_UNDEFCD;
								$TOTAL_UNDEFCK	= $row_UNDEF_REAL->TOTAL_UNDEFCK;
							endforeach;
							$TOTAL_UNDEF_REAL	= $TOTAL_UNDEFCD - $TOTAL_UNDEFCK + $BPP_OTH1_REAL;
							$BPP_UNDEF_REAL		= $TOTAL_UNDEF_REAL;
							$BPP_UNDEF_PLAN1	= $BPP_UNDEF_PLAN;*/
							$TOTAL_UNDEF_REAL	= 0;
							$BPP_UNDEF_REAL		= 0;
							$BPP_UNDEF_PLAN1	= 0;
							if($BPP_UNDEF_PLAN1 == 0)
								$BPP_UNDEF_PLAN1 = 1;
								
							$BPP_UNDEF_REALP	= 100;
							if($BPP_UNDEF_REAL == '')
								$BPP_UNDEF_REAL	= 0;
							
						$SUBTOT_CA= $BPP_MTR_PLAN+$BPP_UPH_PLAN+$BPP_ALT_PLAN+$BPP_SUBK_PLAN+$BPP_BAU_PLAN+$BPP_OTH_PLAN+$BPP_I_PLAN;
						$SUBTOT_CB= $BPP_MTR_BEF+$BPP_UPH_BEF+$BPP_ALT_BEF+$BPP_SUBK_BEF+$BPP_BAU_BEF+$BPP_OTH_BEF+$BPP_I_BEF+$BPP_UNDEFB_REAL;
						$SUBTOT_CC= $BPP_MTR_REALC+$BPP_UPH_REALC+$BPP_ALT_REALC+$BPP_SUBK_REALC+$BPP_BAU_REALC+$BPP_OTH_REALC+$BPP_I_REALC+$BPP_UNDEF_REAL;
						
						$SUBTOT_CA2 = $SUBTOT_CA;
						if($SUBTOT_CA2 == 0) $SUBTOT_CA2 = 1;
							
	                // ------------------------------------ C / D. B I A Y A ------------------------------------
						$SUBTOT_DA	= $SUBTOT_BA + $SUBTOT_CA;
						$SUBTOT_DA2	= $SUBTOT_DA;
							if($SUBTOT_DA2 == 0) $SUBTOT_DA2 = 1;
							
						$SUBTOT_DB	= $SUBTOT_BB + $SUBTOT_CB;
						$SUBTOT_DD	= $SUBTOT_BD + $SUBTOT_CC;
						$SUBTOT_DDP	= $SUBTOT_DD / $SUBTOT_DA2 * 100;
						
							
	                // ------------------------------------ D / E. B E B A N ------------------------------------
						$BB_BAU_PLAN	= 7 * $SUBTOT_AA / 100;
						$BB_BNG_PLAN	= 2 * $SUBTOT_AA / 100;
						// MS.191202033427-00309 : bunga pada laba rugi
						$BB_BNG_PLAN	= 0;
						// MS.191218020552-00341 : LABA RUGI KANTOR PUSAT
						if($isHO == 1)
							$BB_BAU_PLAN 	= 0;

						$BB_PPH_PLAN	= 0 * ($SUBTOT_AA - $SUBTOT_DA - $BB_BAU_PLAN - $BB_BNG_PLAN);
						
						// BEFORE
						$BB_BAU_PLAN1	= $BB_BAU_PLAN;
						if($BB_BAU_PLAN1 == 0)
							$BB_BAU_PLAN1 = 1;
						$BB_BNG_PLAN1	= $BB_BNG_PLAN;
						if($BB_BNG_PLAN1 == 0)
							$BB_BNG_PLAN1 = 1;
						$BB_PPH_PLAN1	= $BB_PPH_PLAN;
						if($BB_PPH_PLAN1 == 0)
							$BB_PPH_PLAN1 = 1;
							
						$BB_BAUB_PLAN	= 7 * $SUBTOT_AB / 100;
						// MS.191218020552-00341 : LABA RUGI KANTOR PUSAT
						if($isHO == 1)
							$BB_BAUB_PLAN 	= 0;

						$BB_BAUB_PLANP	= $BB_BAUB_PLAN / $BB_BAU_PLAN1 * 100;
						$BB_BNGB_PLAN	= 2 * $SUBTOT_AB / 100;
						// MS.191202033427-00309 : bunga pada laba rugi
						$BB_BNGB_PLAN	= 0;
						$sqlTOTAL_BNGB	= "SELECT SUM(A.Base_Debet - A.Base_Kredit) AS BB_BNGB_PLAN
											FROM
												tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											INNER JOIN tbl_chartaccount C ON A.Acc_Id = C.Account_Number
												AND C.PRJCODE = A.proj_Code
											WHERE A.proj_Code IN ('$PRJCODECOL')
												AND B.GEJ_STAT = 3
												AND A.Acc_Id = 84470
												AND B.JournalH_Date <= '$End_DateBef'";
						$resTOTAL_BNGB	= $this->db->query($sqlTOTAL_BNGB)->result();
						foreach($resTOTAL_BNGB as $rowTOTAL_BNGB):
							$BB_BNGB_PLAN	= $rowTOTAL_BNGB->BB_BNGB_PLAN;
						endforeach;

						$BB_BNGB_PLANP	= $BB_BNGB_PLAN / $BB_BNG_PLAN1 * 100;
					
						// BEBAN PPH BEFORE. INFO MBA SHEILA : MS.201991700052 : BEBAN-BEBAN PADA LABA RUGI
							$BB_PPHB_PLAN		= 0;
							/*$sqlTOT_PPHB		= "SELECT SUM(A.INV_PPHVAL) AS TOTAL_PPHB
													FROM tbl_pinv_header A
													WHERE A.INV_STAT IN (3,6) AND PRJCODE = '$PRJCODECOL'
														AND INV_DATE <= '$End_DateBef'";*/
							$sqlTOT_PPHB		= "SELECT SUM(A.PINV_TOTVALPPh) AS TOTAL_PPHB
													FROM tbl_projinv_header A
													WHERE A.PINV_STAT IN (3,6) AND A.PRJCODE IN ('$PRJCODECOL')
														AND A.PINV_DATE <= '$End_DateBef'";
							$resTOT_PPHB		= $this->db->query($sqlTOT_PPHB)->result();
							foreach($resTOT_PPHB as $rowTOT_PPHB):
								$BB_PPHB_PLAN	= $rowTOT_PPHB->TOTAL_PPHB;
							endforeach;
						
						//$BB_PPHB_PLAN	= 0 * ($SUBTOT_AB - $SUBTOT_DB - $BB_BAUB_PLAN - $BB_BNGB_PLAN);
						$BB_PPHB_PLANP	= $BB_PPHB_PLAN / $BB_PPH_PLAN1 * 100;
						
						// CURRENT
						$BB_BAU_REAL	= 7 * $SUBTOT_AC / 100;
						// MS.191218020552-00341 : LABA RUGI KANTOR PUSAT
						if($isHO == 1)
							$BB_BAU_REAL 	= 0;

						$BB_BAU_REALP	= $BB_BAU_REAL / $BB_BAU_PLAN1 * 100;
						$BB_BNG_REAL	= 2 * $SUBTOT_AC / 100;
						// MS.191202033427-00309 : bunga pada laba rugi
						$BB_BNG_REAL	= 0;
						$sqlTOTAL_BNGR	= "SELECT SUM(A.Base_Debet - A.Base_Kredit) AS BB_BNG_REAL
											FROM
												tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											INNER JOIN tbl_chartaccount C ON A.Acc_Id = C.Account_Number
												AND C.PRJCODE = A.proj_Code
											WHERE A.proj_Code IN ('$PRJCODECOL')
												AND B.GEJ_STAT = 3
												AND A.Acc_Id = 84470
												AND MONTH(A.JournalH_Date) = '$PERIODEM' AND YEAR(A.JournalH_Date) = '$PERIODEY'";
						$resTOTAL_BNGR	= $this->db->query($sqlTOTAL_BNGR)->result();
						foreach($resTOTAL_BNGR as $rowTOTAL_BNGR):
							$BB_BNG_REAL	= $rowTOTAL_BNGR->BB_BNG_REAL;
						endforeach;

						$BB_BNG_REALP	= $BB_BNG_REAL / $BB_BNG_PLAN1 * 100;
					
						// BEBAN PPH CURRENT. INFO MBA SHEILA : MS.201991700052 : BEBAN-BEBAN PADA LABA RUGI
							$BB_PPHC_PLAN		= 0;
							$sqlTOT_PPHC		= "SELECT SUM(A.PINV_TOTVALPPh) AS TOTAL_PPHB
													FROM tbl_projinv_header A
													WHERE A.PINV_STAT IN (3,6) AND A.PRJCODE IN ('$PRJCODECOL')
														AND MONTH(A.PINV_DATE) = '$PERIODEM' AND YEAR(A.PINV_DATE) = '$PERIODEY'";
							$resTOT_PPHC		= $this->db->query($sqlTOT_PPHC)->result();
							foreach($resTOT_PPHC as $rowTOT_PPHC):
								$BB_PPHC_PLAN	= $rowTOT_PPHC->TOTAL_PPHB;
							endforeach;					
						
						//$BB_PPH_REAL	= 0 * ($SUBTOT_AC - $SUBTOT_DD - $BB_BAU_REAL - $BB_BNG_REAL);
						$BB_PPH_REAL	= $BB_PPHC_PLAN;
						$BB_PPH_REALP	= $BB_PPH_REAL / $BB_PPH_PLAN1 * 100;

						// START MS.191202073249-00312 : tampilan laba rugi
							// ----------------------------------------1
								$PROGMC_REAL_AMCN	= $PROGMC_REAL_AMB + $PROGMC_REAL_AMC;
								$TOTAL_SICN			= $TOTAL_SIB + $TOTAL_SIC;
								$TOTAL_INCN			= $TOTAL_INB + $TOTAL_INC;
								
								// PRESENTASE
								$PROGMC_REAL_AMCNP	= $PROGMC_REAL_AMCN / $TOTAL_PRJ1 * 100;
								$TOTAL_SICNP		= $TOTAL_SICN / $TOTAL_PRJ1 * 100;
								$TOTAL_INCNP		= $TOTAL_INCN / $TOTAL_PRJ1 * 100;


							// ----------------------------------------2
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
								
		                    	// BAU DAN OVH disatukan ke dalam BAU saja berdasarkan Task Request MS.201961900017						
								// BEFORE
									$BPP_BAU_PLANG	= $BPP_BAU_PLAN + $BPP_OTH_PLAN;
									$BPP_BAU_PLANG2	= $BPP_BAU_PLANG;
										if($BPP_BAU_PLANG2 == 0) $BPP_BAU_PLANG2 = 1;
										
									$BPP_BAU_BEFG	= $BPP_BAU_BEF + $BPP_OTH_BEF;
									
								// CURRENT
									$BPP_BAU_REALCG	= $BPP_BAU_REALC + $BPP_OTH_REALC;
									
									// BEFORE PERCENTATION
										$BPP_BAU_BEFPG	= $BPP_BAU_BEFG / $BPP_BAU_PLANG2 * 100;
										
									// CURRENT PERCENTATION
										$BPP_BAU_REALPG	= $BPP_BAU_REALCG / $BPP_BAU_PLANG2 * 100;
										
								// SAMPAI SAAT INI
								$BPP_MTR_REALCN		= $BPP_MTR_BEF + $BPP_MTR_REALC;
								$BPP_UPH_REALCN		= $BPP_UPH_BEF + $BPP_UPH_REALC;
								$BPP_ALT_REALCN		= $BPP_ALT_BEF + $BPP_ALT_REALC;
								$BPP_SUBK_REALCN	= $BPP_SUBK_BEF + $BPP_SUBK_REALC;
								$BPP_BAU_REALCGN	= $BPP_BAU_BEFG + $BPP_BAU_REALCG;
								$BPP_I_REALCN		= $BPP_I_BEF + $BPP_I_REALC;
								
								// PRESENTASE
								$BPP_MTR_REALCNP	= $BPP_MTR_REALCN / $BPP_MTR_PLAN1 * 100;
								$BPP_UPH_REALCNP	= $BPP_UPH_REALCN / $BPP_UPH_PLAN1 * 100;
								$BPP_ALT_REALCNP	= $BPP_ALT_REALCN / $BPP_ALT_PLAN1 * 100;
								$BPP_SUBK_REALCNP	= $BPP_SUBK_REALCN / $BPP_SUBK_PLAN1 * 100;
								$BPP_BAU_REALCGNP	= $BPP_BAU_REALCGN / $BPP_BAU_PLAN1 * 100;
								$BPP_I_REALCNP		= $BPP_I_REALCN / $BPP_I_PLAN1 * 100;
								
								$SUBTOT_CBP			= $SUBTOT_CB / $SUBTOT_CA2 * 100;
								$SUBTOT_CCP			= $SUBTOT_CC / $SUBTOT_CA2 * 100;
								//$SUBTOT_CD		= $BPP_MTR_REALCN + $BPP_UPH_REALCN + $BPP_ALT_REALCN + $BPP_SUBK_REALCN + $BPP_BAU_REALCGN + $BPP_I_REALCN;
								$SUBTOT_CD			= $SUBTOT_CB + $SUBTOT_CC;
								$SUBTOT_CDP			= $SUBTOT_CD / $SUBTOT_CA2 * 100;


							// ----------------------------------------3
								// SAMPAI SAAT INI
								$BB_BAU_REALN		= $BB_BAUB_PLAN + $BB_BAU_REAL;
								$BB_BNG_REALN		= $BB_BNGB_PLAN + $BB_BNG_REAL;
								$BB_PPH_REALN		= $BB_PPHB_PLAN + $BB_PPH_REAL;
								
								// PRESENTASE
								$BB_BAU_REALNP		= $BB_BAU_REALN / $BB_BAU_PLAN1 * 100;
								$BB_BNG_REALNP		= $BB_BNG_REALN / $BB_BNG_PLAN1 * 100;
								$BB_PPH_REALNP		= $BB_PPH_REALN / $BB_PPH_PLAN1 * 100;


		                    // ----------------------------------------4
								$SUBTOT_EA	= $BB_BAU_PLAN + $BB_BNG_PLAN + $BB_PPH_PLAN;
								$SUBTOT_EA2	= $SUBTOT_EA;
									if($SUBTOT_EA2 == 0) $SUBTOT_EA2 = 1;
								
								$SUBTOT_EB	= $BB_BAUB_PLAN + $BB_BNGB_PLAN + $BB_PPHB_PLAN;
								$SUBTOT_EBP	= $SUBTOT_EB / $SUBTOT_EA2 * 100;
								$SUBTOT_EC	= $BB_BAU_REAL + $BB_BNG_REAL + $BB_PPH_REAL;
								$SUBTOT_ECP	= $SUBTOT_EC / $SUBTOT_EA2 * 100;
								$SUBTOT_ECN	= $BB_BAU_REALN + $BB_BNG_REALN + $BB_PPH_REALN;
								$SUBTOT_ECNP= $SUBTOT_ECN / $SUBTOT_EA2 * 100;


							// REALISASI BIAYA = (SUBTOTAL B + SUBTOTAL C) / (REALISASI_PROGRESS * NILAI_KONTRAK_TOTAL)
								$PROGMCREAL 	= $PROGMC_REAL * $PRJCOSTV / 100;
								if($PROGMCREAL == 0)
									$PROGMCREAL = 1;
								$TOTEXP_REAL	= ($SUBTOT_CD + $SUBTOT_ECN) / ($PROGMCREAL) * 100;
								//echo "$TOTEXP_REAL	= ($SUBTOT_CD + $SUBTOT_ECN) / ($PROGMC_REAL * $PRJCOSTV / 100) * 100;";


						// END MS.191202073249-00312 : tampilan laba rugi


						// MS.191218071401-00343 : laba rugi dp
							$TOT_RECDP		= 0;
							$sqlTOTAL_RECDP	= "SELECT SUM(A.GAmount) AS TOT_RECDP, SUM(A.Inv_Amount) AS TOT_INVDP
												FROM tbl_br_detail A
													INNER JOIN tbl_br_header B ON A.BR_NUM = B.BR_NUM
													INNER JOIN tbl_projinv_header C ON A.DocumentNo = C.PINV_CODE
														AND C.PINV_CAT = 1 AND B.PRJCODE = A.PRJCODE
												WHERE B.BR_STAT IN (3,6) AND A.PRJCODE IN ('$PRJCODECOL')
													AND B.BR_DATE <= '$End_DateBef'";
							$resTOTAL_RECDP	= $this->db->query($sqlTOTAL_RECDP)->result();
							foreach($resTOTAL_RECDP as $rowTOTAL_RECDP):
								$TOT_RECDP	= $rowTOTAL_RECDP->TOT_RECDP;
								$TOT_INVDP	= $rowTOTAL_RECDP->TOT_INVDP;
							endforeach;
	            ?>
	          	<tr>
	                <td colspan="3" class="style2" style="text-align:left;">
	                    <table width="100%" style="size:auto; font-size:12px;">
	                        <tr style="text-align:left;">
	                            <td width="23%" nowrap>PROYEK</td>
	                          	<td width="1%">:</td>
	                            <td colspan="3" style="text-align:left; font-weight:bold">&nbsp;<?php echo strtoupper($PRJNAMEV); ?></td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>PERIODE</td>
	                          	<td>:</td>
	                          	<td width="34%" style="text-align:left;">&nbsp;<?php echo $PERIODEV;?></td>
	                          	<td width="13%" style="text-align:left;" nowrap>RENCANA PROGRES (BCWS)</td>
	                          	<td width="29%" style="text-align:left;">: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($PROGMC_PLAN, 4); ?>" style="max-width:150px; text-align:right" class="inplabel"> 
	                          	%
								</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>NILAI KONTRAK</td>
	                          
	                          	<td>:</td>
	                          	<td>
	                            	<input type="text" name="theCode" id="theCode" value="<?php echo number_format($PRJCOSTV, $decFormat); ?>" style="max-width:120px; text-align:right" class="inplabel">
	                            </td>
	                          	<td nowrap>REALISASI PROGRES (BCWP)</td>
	                          	<td>: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($PROGMC_REAL, 4); ?>" style="max-width:150px; text-align:right" class="inplabel"> 
	                          	%
	                            </td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>NILAI PEKERJAAN TAMBAH KURANG</td>
	                          	<td>:</td>
	                          	<td>
	                            	<input type="text" name="theCode" id="theCode" value="<?php echo number_format($TOTAL_SIT, $decFormat); ?>" style="max-width:120px; text-align:right" class="inplabel">
	                            </td>
	                          	<td nowrap>REALISASI BIAYA (ACWP)</td>
	                          	<td>: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($TOTEXP_REAL, 4); ?>" style="max-width:150px; text-align:right" class="inplabel"> 
	                          	%
	                            </td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>NILAI KONTRAK TOTAL</td>
	                          	<td>:</td>
	                          	<td style="text-align:left;">
	                                <input type="text" name="theCode" id="theCode" value="<?php echo number_format($TOTAL_PRJ, $decFormat); ?>" style="max-width:120px; text-align:right" class="inplabel">
	                            </td>
	                          	<td style="text-align:left;" nowrap>REALISASI TAGIHAN</td>
	                          	<td style="text-align:left;">: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($TOTAL_INCOMEVP, 4); ?>" style="max-width:150px; text-align:right" class="inplabel">
	                          	%</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>UANG MUKA DITERIMA</td>
	                          	<td>:</td>
	                          	<td style="text-align:left;">
	                            	<input type="text" name="theCode" id="theCode" value="<?php echo number_format($TOT_INVDP, $decFormat); ?>" style="max-width:120px; text-align:right" class="inplabel">
	                            </td>
	                          	<td style="text-align:left;">DEVIASI PROGRES</td>
	                          	<td style="text-align:left;">: <input type="text" name="theCode" id="theCode" value="<?php echo number_format($DEVIASI_PROG, 4); ?>" style="max-width:150px; text-align:right" class="inplabel"> 
	                          	%</td>
	                      	</tr>
	                    </table>
			    	</td>
	            </tr>
	            <tr>
					<td colspan="3" class="style2" style="text-align:left; font-size:12px">
	              	<table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
	                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:14px;">
	                    	<td width="3%">&nbsp;NO.&nbsp;</td>
	                      	<td colspan="2" style="text-align:center">URAIAN</td>
	                        <td width="13%" style="text-align:center" nowrap>RENCANA AWAL<br>MAPP</td>
	                        <td width="10%" style="text-align:center" nowrap>PROGRESS<br>SEBELUMNYA</td>
	                        <td width="2%" style="text-align:center" nowrap>%</td>
	                        <td width="12%" style="text-align:center" nowrap>PROGRESS<br>SAAT INI</td>
	                        <td width="2%" style="text-align:center">%</td>
	                        <td width="11%" style="text-align:center" nowrap>PROGRESS<br>SAMPAI SAAT INI</td>
	                        <td width="2%" style="text-align:center" nowrap>%</td>
	                        <td width="10%" nowrap style="text-align:center">PROYEKSI (%)</td>
	                	</tr>
	                	<tr style="font-weight:bold; font-size:14px">
	                        <td align="center">A.</td>
	                        <td colspan="2">&nbsp;PENDAPATAN</td>
	                        <td style="text-align:right">&nbsp;</td>
	                        <td style="text-align:right">&nbsp;</td>
	                        <td style="text-align:right">&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	              	  	</tr>
	                    <?php
	                    // ----------------------------------1
						?>
	                	<tr>
	                        <td>&nbsp;</td>
	                        <td width="10%" style="border-right: 0">&nbsp;Progress MC</td>
	                        <td width="25%" style="text-align:right; border-left: 0"><?php echo number_format($PROGMC_PLANC, 2); ?> %&nbsp;</td>
	                        <td style="text-align:right"><?php echo number_format($PRJCOST, 0); ?></td>
	                        <td style="text-align:right"><?php echo number_format($PROGMC_REAL_AMB, 0); ?></td>
	                        <td style="text-align:right" nowrap><?php echo number_format($PROGMC_REAL_AMBP, 2); ?></td>
	                        <td style="text-align:right"><?php echo number_format($PROGMC_REAL_AMC, 0); ?></td>
	                        <td nowrap style="text-align:right"><?php echo number_format($PROGMC_REAL_AMCP, 2); ?></td>
	                        <td nowrap style="text-align:right"><?php echo number_format($PROGMC_REAL_AMCN, 0); ?></td>
	                        <td nowrap style="text-align:right"><?php echo number_format($PROGMC_REAL_AMCNP, 2); ?></td>
	                        <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr style="display:none">
	                	  <td>&nbsp;</td>
	                	  <td style="border-right: 0" nowrap>&nbsp;Progress Kontraktuil</td>
	                	  <td style="text-align:right; border-left: 0"><?php echo number_format($PROGMC_REAL, 2); ?> %&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php // echo number_format($PROGMC_REAL_AMC, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($PROGMC_REAL_AMCP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2" style="border-right: 0">&nbsp;Pekerjaan Tambah Kurang</td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SIP, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SIB, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SIBP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SIC, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SICP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SICN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SICNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td style="border-right: 0" nowrap>&nbsp;Pekerjaan Katrolan</td>
	                	  <td style="text-align:right; border-left: 0"><?php echo number_format($TOTAL_SIKATROLP, 2); ?> %&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php //echo number_format($TOTAL_SIKATROL_AMC, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_SIKATROL_AMCP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td style="border-right: 0" nowrap>&nbsp;Lain-lain</td>
	                	  <td style="text-align:right; border-left: 0">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_INB, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_INC, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_INCN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($TOTAL_INCNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr style="font-weight:bold; background:#CCCCCC;font-size:14px">
	                	  <td>&nbsp;</td>
	                	  <td colspan="2" align="center">&nbsp;Sub Total (A)</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_AA, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_AB, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_ABP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_AC, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_ACP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_AD, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_ADP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr style="display:none">
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	              	  	</tr>
	                	<tr style="font-weight:bold; font-size:14px">
	                	  <td align="center">B.</td>
	                	  <td colspan="2">&nbsp;BIAYA  - BIAYA :</td>
	                	  <td>&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	              	  	</tr>
	                    <?php
	                    // ----------------------------------------2
						?>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Material</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_MTR_PLAN, 0); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintMB; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_MTR_BEF, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_MTR_BEFP, 2); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetC('<?php echo $secPrintMC; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_MTR_REALC, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_MTR_REALP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_MTR_REALCN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_MTR_REALCNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                    <script>
							function showDetB(LinkD)
							{
								w = 600;
								h = 550;
								var left = (screen.width/2)-(w/2);
								var top = (screen.height/2)-(h/2);
								window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
							}
							
							function showDetC(LinkD)
							{
								w = 600;
								h = 550;
								var left = (screen.width/2)-(w/2);
								var top = (screen.height/2)-(h/2);
								window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
							}
	                    </script>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Upah</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UPH_PLAN, 0); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintUB; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_UPH_BEF, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UPH_BEFP, 2); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetC('<?php echo $secPrintUC; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_UPH_REALC, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UPH_REALP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UPH_REALCN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UPH_REALCNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Alat</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_ALT_PLAN, 0); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintTB; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_ALT_BEF, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_ALT_BEFP, 2); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetC('<?php echo $secPrintTC; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_ALT_REALC, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_ALT_REALP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_ALT_REALCN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_ALT_REALCNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Subkontraktor</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_SUBK_PLAN, 0); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintSB; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_SUBK_BEF, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_SUBK_BEFP, 2); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintSC; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_SUBK_REALC, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_SUBK_REALP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_SUBK_REALCN, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_SUBK_REALCNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Biaya Administrasi Umum (BAU)</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_BAU_PLANG, 0); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintGB; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_BAU_BEFG, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_BAU_BEFPG, 2); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintGC; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_BAU_REALCG, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_BAU_REALPG, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_BAU_REALCGN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_BAU_REALCGNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr style="display:none">
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Overhead</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_OTH_PLAN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_OTH_BEF, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_OTH_BEFP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_OTH_REALC, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_OTH_REALP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Rupa-rupa</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_I_PLAN, 0); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintIB; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_I_BEF, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_I_BEFP, 2); ?></td>
	                	  <td style="text-align:right">
	                          <a onclick="showDetB('<?php echo $secPrintIC; ?>')" style="cursor: pointer;">
	                            <?php echo number_format($BPP_I_REALC, 0); ?>
	                          </a>
	                      </td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_I_REALP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_I_REALCN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_I_REALCNP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr style="display:none">
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;Lainnya</td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UNDEF_PLAN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UNDEFB_REAL, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UNDEFB_REALP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UNDEF_REAL, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BPP_UNDEF_REALP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
	                	  <td>&nbsp;</td>
	                	  <td colspan="2" style="font-weight:bold; text-align:center">Sub Total (B)</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CA, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CB, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CBP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CC, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CCP, 2); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CD, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_CDP, 2); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	              	  	</tr>
	                    <?php
	                    // ----------------------------------------3
						?>
	                	<tr style="font-weight:bold; font-size:14px">
	                	  <td align="center">C.</td>
	                	  <td colspan="2">&nbsp;BEBAN-BEBAN</td>
	                	  <td>&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2" style="border-right: 0">&nbsp;Overhead Pusat (7%)</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BAU_PLAN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BAUB_PLAN, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BAU_REAL, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BAU_REALN, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	               	    </tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2" style="border-right: 0">&nbsp;Bunga Bank             	    </td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BNG_PLAN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BNGB_PLAN, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BNG_REAL, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_BNG_REALN, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	               	    </tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2" style="border-right: 0">&nbsp;PPh</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_PPH_PLAN, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($BB_PPHB_PLAN, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_PPH_REAL, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($BB_PPH_REALN, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	               	    </tr>
	                    <?php
	                    // ----------------------------------------4
						?>
	                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
	                	  <td>&nbsp;</td>
	                	  <td colspan="2" style="font-weight:bold; text-align:center">Sub Total (C)</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_EA, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_EB, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_EC, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_ECN, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr>
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	              	  	</tr>
	                    <?php
							$SUBTOT_IA	= $SUBTOT_AA - $SUBTOT_DA - $SUBTOT_EA;
							$SUBTOT_IB	= $SUBTOT_AB - $SUBTOT_DB - $SUBTOT_EB;
							$SUBTOT_IC	= $SUBTOT_AC - $SUBTOT_DD - $SUBTOT_EC;
							//$SUBTOT_ID	= $SUBTOT_IA + $SUBTOT_IB + $SUBTOT_IC;
							$SUBTOT_ID	= $SUBTOT_AD - $SUBTOT_CD - $SUBTOT_ECN;
						?>
	                	<tr style="font-weight:bold; background:#CCCCCC; font-size:14px">
	                	  <td align="center">D.</td>
	                	  <td colspan="2">&nbsp;LABA / RUGI : (A-B-C)</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_IA, 0); ?></td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_IB, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_IC, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right"><?php echo number_format($SUBTOT_ID, 0); ?></td>
	                	  <td style="text-align:right">&nbsp;</td>
	                	  <td style="text-align:right">&nbsp;</td>
	              	  	</tr>
	                	<tr style="font-weight:bold;">
	                	  <td>&nbsp;</td>
	                	  <td colspan="2">&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	                	  <td>&nbsp;</td>
	           	      </tr>
	                </table>
	           	  </td>
	            </tr>
	        </table>
		</section>
	</div>
</body>
</html>