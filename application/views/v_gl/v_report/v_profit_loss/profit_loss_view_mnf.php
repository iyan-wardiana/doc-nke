<?php
/* 
 	* Author   = Dian Hermanto
 	* Create Date  = 21 Januari 2018
 	* File Name  = print_po.php
 	* Location   = -
*/

setlocale(LC_ALL, 'id-ID', 'id_ID');

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$LangID 	= $this->session->userdata['LangID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat	= 2;
$DrafTTD1   = "white";

$sql_01		= "SELECT * FROM tappname";
$res_01		= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
endforeach;

$PeriodeD 	= date('Y-m-d',strtotime($End_Date));

$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODECOL'";
$resPRJ	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJCOST 	= $rowPRJ->PRJCOST;
endforeach;
$PRJCODECOLL	= $PRJCODE;
$PRJNAMECOLL	= $PRJNAME;

$PERIODEV		= strftime('%B %Y', strtotime($End_Date));

$End_Date 		= date('Y-m-d',strtotime($End_Date));
$End_DateBef1	= date('Y-m-d', strtotime('-1 month', strtotime($End_Date)));
$End_DateBef	= date('Y-m-t', strtotime('-1 day', strtotime($End_DateBef1)));
$PERIODEM_BEF	= date('m', strtotime($End_DateBef));
$PERIODEY_BEF	= date('Y', strtotime($End_DateBef));

$PERIODM		= date('m', strtotime($End_Date));
$PERIODY		= date('Y', strtotime($End_Date));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt Arial, Helvetica, sans-serif;
        }
        * {
          box-sizing: border-box;
          -moz-box-sizing: border-box;
        }
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding-left: 2cm;
            padding-right: 2cm;
            padding-top: 2cm;
            padding-bottom: 2cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: <?php echo $DrafTTD1;?>;
            background-size: 400px 200px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        @page {
           /* size: A4;*/
            margin: 0;
        }
        @media print {
            /*@page{size: portrait;}*/
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
            .hcol1{
                background-color: #F7DC6F !important;
            }
        }
    </style>
    
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

<?php
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
        if($TranslCode == 'Date')$Date = $LangTransl;
        if($TranslCode == 'CustName')$CustName = $LangTransl;
        if($TranslCode == 'Color')$Color = $LangTransl;
        if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        if($TranslCode == 'Nominal')$Nominal = $LangTransl;
        if($TranslCode == 'salesPrcCust')$salesPrcCust = $LangTransl;
        if($TranslCode == 'Created')$Created = $LangTransl;
        if($TranslCode == 'Approved')$Approved = $LangTransl;
        if($TranslCode == 'Approved')$Approved = $LangTransl;
	endforeach;

    if($LangID == 'IND')
    {
        $header     = "LAPORAN LABA RUGI";
        $alert1     = "Pengaturan Departemen Pengguna";
        $alert2     = "Status pengguna belum ditentukan pada departemen manapun, sehingga tidak dapat membuat dokumen ini. Silahkan hubungi admin untuk meminta bantuan.";
    }
    else
    {
        $header     = "PROFIT AND LOSS REPORT";
        $alert1     = "User department setting";
        $alert2     = "User not yet set department, so can not create this document. Please call administrator to get help.";
    }

    $stlLine1 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine2 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden;";
    $stlLine3 		= "border-left-style: hidden; border-right-style: hidden;";
    $stlLine4 		= "border-top:double; border-bottom:double; border-right-style: hidden;";
    $stlLine5 		= "border-bottom:groove; border-right-style: hidden; border-top-style: hidden;";
    $stlLine6 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";

    /*$stlLine1 		= "";
    $stlLine2 		= "";
    $stlLine3 		= "";
    $stlLine4 		= "";
    $stlLine5 		= "";
    $stlLine6 		= "";*/
?>

<body class="hold-transition skin-blue sidebar-mini">

<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
    <!-- <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php //echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div>
    <div class="pad margin no-print" style="display:none">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php //echo $Transl_01; ?>
        </div>
    </div> -->
    <!-- Main content -->
    <div class="page">
        <table border="1" width="100%">
            <tr>
                <td style="<?=$stlLine1?> text-align: left; font-weight: bold; font-size: 18px"><?=$header?></td>
            </tr>
            <tr>
                <td style="<?=$stlLine1?>text-align: left; font-weight: bold; font-size: 18px"><?=$comp_name?></td>
            </tr>
            <tr>
                <td style="<?=$stlLine1?> text-align: left; font-weight: bold; font-size: 14px"><?=$PERIODEV?></td>
            </tr>
        	<tr>
				<td style="<?=$stlLine5.$stlLine3?> line-height: 1px">&nbsp;</td>
	      	</tr>
            <tr>
                <td style="<?=$stlLine3?> line-height: 5px">&nbsp;</td>
            </tr>
            <tr>
            	<td style="<?=$stlLine1?>">
            		<table border="1" width="100%">
	                    <script>
							function showDetJD(LinkD, cat)
							{
								w = 800;
								h = 550;
								var left = (screen.width/2)-(w/2);
								var top = (screen.height/2)-(h/2);
								window.open(LinkD+cat,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
							}
	                    </script>
			            <?php
			            	$LinkvwDet	= "$PRJCODECOL~$PeriodeD";
							$secvwDet	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC_mnf/?id='.$this->url_encryption_helper->encode_url($LinkvwDet).'&c4t=');

			            	$qryINC 	= "SELECT SUM(Base_Debet) AS TOT_INCOME
			            					FROM tbl_journaldetail
			            					WHERE JournalType = 'BR' AND GEJ_STAT = 3
												AND MONTH(JournalH_Date) = '$PERIODM' AND YEAR(JournalH_Date) = $PERIODY
			            						AND proj_Code IN (SELECT PRJCODE FROM tbl_project WHERE PRJCODE_HO = '$PRJCODECOL')";
			            	$resINC 	= $this->db->query($qryINC)->result();
			            	foreach($resINC as $rowINC) :
			            		$T_INC	= $rowINC->TOT_INCOME;
			            	endforeach;
			            	$PENP 	= $T_INC;

		            		$PENA	= 0;		// Penjualan Aset
		            		$PENL	= 0;		// Penjualan Lain2
		            		$BPP	= 0;
		            		$BLL	= 0;
		            		$BGP	= 0;
		            		$BLAT	= 0;
		            		$BAU	= 0;
		            		$BOL	= 0;
		            		$BPB	= 0;
		            		$BPM	= 0;
		            		$BPK	= 0;
		            		$BNOL	= 0;
			            	$qryEXP 	= "SELECT SUM(PPA) AS PENA, SUM(PLL) AS PENL, SUM(BPP) AS BPP, SUM(BLL) AS BLL, SUM(BGP) AS BGP,
			            						SUM(BLAT) AS BLAT, SUM(BAU) AS BAU, SUM(BOL) AS BOL, SUM(BPB) AS BPB, SUM(BPM) AS BPM, SUM(BPK) AS BPK,
			            						SUM(BNOL) AS BNOL
			            					FROM tbl_profitloss
			            					WHERE PRJCODE IN (SELECT PRJCODE FROM tbl_project WHERE PRJCODE_HO = '$PRJCODECOL')
			            					AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
			            	$resEXP 	= $this->db->query($qryEXP)->result();
			            	foreach($resEXP as $rowEXP) :
			            		$PENA	= $rowEXP->PENA;
			            		$PENL	= $rowEXP->PENL;
			            		$BPP	= $rowEXP->BPP;
			            		$BLL	= $rowEXP->BLL;
			            		$BGP	= $rowEXP->BGP;
			            		$BLAT	= $rowEXP->BLAT;
			            		$BAU	= $rowEXP->BAU;
			            		$BOL	= $rowEXP->BOL;
			            		$BPB	= $rowEXP->BPB;
			            		$BPM	= $rowEXP->BPM;
			            		$BPK	= $rowEXP->BPK;
			            		$BNOL	= $rowEXP->BNOL;
			            	endforeach;

			            	// TOTAL PENJUALAN
			            	$TPEN 		= $PENP + $PENA + $PENL;

			            	$TBP 		= $BPP + $BLL;
			            	$LRBRUTO 	= $TPEN - $TBP;

			            	$TBO 		= $BGP + $BLAT + $BAU + $BOL;
			            	$TBNO 		= $BPB + $BPM + $BPK + $BNOL;
			            	$LRNETT		= $LRBRUTO - $TBO - $TBNO;
			            ?>
            			<!-- START : PENDAPATAN -->
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td colspan="5" style="<?=$stlLine1?>" nowrap>PENDAPATAN</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Penjualan Pokok</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','PENP')">
			                        	<?php echo number_format($PENP, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?>" style="text-align: center;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Penjualan Aset</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','PENA')">
			                        	<?php echo number_format($PENA, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?>" style="text-align: center;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Penjualan Lain-lain</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','PENL')">
			                        	<?php echo number_format($PENL, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?>" style="text-align: center;">&nbsp;</td>
				            </tr>
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">TOTAL PENDAPATAN</td>
				                <td width="20%" style="<?=$stlLine3?>" style="text-align: center;">&nbsp;</td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine5?> text-align: right;"><?php echo number_format($TPEN, 2); ?></td>
				            </tr>
	            		<!-- END : PENDAPATAN -->

            			<tr>
			                <td colspan="4" style="<?=$stlLine1?> line-height: 2px" nowrap>&nbsp;</td>
			                <td width="20%" style="text-align: right; border-right-style: hidden; line-height: 2px">&nbsp;</td>
			            </tr>

			            <?php
			            ?>

	            		<!-- START : BEBAN PRODUKSI -->
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td colspan="5" style="<?=$stlLine1?>" nowrap>BEBAN PENJUALAN</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Pokok Produksi</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BPP')">
			                        	<?php echo number_format($BPP, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?>" style="text-align: center;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Lain-lain</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BLL')">
			                        	<?php echo number_format($BLL, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?>" style="text-align: center;">&nbsp;</td>
				            </tr>
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">TOTAL BEBAN PENJUALAN</td>
				                <td width="20%" style="<?=$stlLine3?>" style="text-align: center;">&nbsp;</td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine5?> text-align: right;"><?php echo number_format($TBP, 2); ?></td>
				            </tr>
	            		<!-- END : BEBAN PRODUKSI -->

            			<tr>
			                <td colspan="4" style="<?=$stlLine1?> line-height: 2px" nowrap>&nbsp;</td>
			                <td width="20%" style="text-align: right; border-right-style: hidden; line-height: 2px">&nbsp;</td>
			            </tr>
            			<tr>
			                <td colspan="5" style="<?=$stlLine2?> line-height: 2px" nowrap>&nbsp;</td>
			            </tr>

	            		<!-- START : LABA KOTOR -->
	            			<tr style="font-weight: bold; color: #A93226">
				                <td colspan="4" style="<?=$stlLine1?>" nowrap>LABA / RUGI KOTOR</td>
				                <td width="20%" style="<?=$stlLine4?> text-align: right;"><?php echo number_format($LRBRUTO, 2); ?></td>
				            </tr>
	            			<tr>
				                <td colspan="5" style="<?=$stlLine3?> line-height: 2px" nowrap>&nbsp;</td>
				            </tr>
	            			<tr>
				                <td colspan="5" style="<?=$stlLine2?> line-height: 2px" nowrap>&nbsp;</td>
				            </tr>
	            		<!-- END : LABA KOTOR -->

	            		<!-- START : BEBAN OPERASIONAL -->
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td colspan="5" style="<?=$stlLine1?>" nowrap>BEBAN OPERASIONAL</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Gaji Pegawai</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BGP')">
			                        	<?php echo number_format($BGP, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Listrik, Air dan Telepon</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BLAT')">
			                        	<?php echo number_format($BLAT, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Administrasi dan Umum</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BAU')">
			                        	<?php echo number_format($BAU, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Operasional Lainnya</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BOL')">
			                        	<?php echo number_format($BOL, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">TOTAL BEBAN OPERASIONAL</td>
				                <td width="20%" style="<?=$stlLine3?> text-align: right;">&nbsp;</td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine5?> text-align: right;"><?php echo number_format($TBO, 2); ?></td>
				            </tr>
	            		<!-- END : BEBAN OPERASIONAL -->

            			<tr>
			                <td colspan="4" style="<?=$stlLine1?> line-height: 2px" nowrap>&nbsp;</td>
			                <td width="20%" style="text-align: right; border-right-style: hidden; line-height: 2px">&nbsp;</td>
			            </tr>

	            		<!-- START : BEBAN NON OPERASIONAL -->
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td colspan="5" style="<?=$stlLine1?>" nowrap>BEBAN NON OPERASIONAL</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Penyusutan Bangunan</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BPB')">
			                        	<?php echo number_format($BPB, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Penyusutan Mesin</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BPM')">
			                        	<?php echo number_format($BPM, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Penyusutan Kendaraan</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BPK')">
			                        	<?php echo number_format($BPK, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr>
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">Beban Non Operasional Lainnya</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">
				                	<a onclick="showDetJD('<?php echo $secvwDet; ?>','BNOL')">
			                        	<?php echo number_format($BNOL, 2); ?>
			                        </a>
				                </td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine2?> text-align: right;">&nbsp;</td>
				            </tr>
	            			<tr style="font-weight: bold; color: #2E86C1">
				                <td width="5%" style="<?=$stlLine1?>" nowrap>&nbsp;</td>
				                <td width="50%" style="<?=$stlLine1?>">TOTAL BEBAN NON OPERASIONAL</td>
				                <td width="20%" style="<?=$stlLine3?> text-align: right;">&nbsp;</td>
				                <td width="5%" style="<?=$stlLine1?> text-align: right;">&nbsp;</td>
				                <td width="20%" style="<?=$stlLine5?> text-align: right;"><?php echo number_format($TBNO, 2); ?></td>
				            </tr>
	            		<!-- END : BEBAN NON OPERASIONAL -->

            			<tr>
			                <td colspan="4" style="<?=$stlLine1?> line-height: 2px" nowrap>&nbsp;</td>
			                <td width="20%" style="text-align: right; border-right-style: hidden; line-height: 2px">&nbsp;</td>
			            </tr>
            			<tr>
			                <td colspan="5" style="<?=$stlLine2?> line-height: 2px" nowrap>&nbsp;</td>
			            </tr>

	            		<!-- START : LABA KOTOR -->
	            			<tr style="font-weight: bold; color: #145A32">
				                <td colspan="4" style="<?=$stlLine1?>" nowrap>LABA / RUGI BERSIH</td>
				                <td width="20%" style="<?=$stlLine4?> text-align: right;"><?php echo number_format($LRNETT, 2); ?></td>
				            </tr>
	            			<tr>
				                <td colspan="5" style="<?=$stlLine3?> line-height: 2px" nowrap>&nbsp;</td>
				            </tr>
	            			<tr>
				                <td colspan="5" style="<?=$stlLine2?> line-height: 2px" nowrap>&nbsp;</td>
				            </tr>
	            			<tr>
				                <td colspan="5" style="<?=$stlLine6?> line-height: 2px" nowrap>&nbsp;</td>
				            </tr>
	            		<!-- END : LABA KOTOR -->
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
    </div>
</body>

</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>