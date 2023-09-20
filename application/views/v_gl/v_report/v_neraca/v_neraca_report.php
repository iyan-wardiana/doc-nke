<?php
/* 
 	* Author   = Dian Hermanto
 	* Create Date  = 15 Maret 2017
 	* File Name  = v_cashflow_report_adm.php
 	* Location   = -
*/
$Periode1 = date('YmdHis');
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LapNeraca_$Periode1.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
    
$sqlApp         = "SELECT * FROM tappname";
$resultaApp     = $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
    $appName    = $therow->app_name;
    $comp_init  = $therow->comp_init;
    $comp_name  = $therow->comp_name;
endforeach;

$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;

$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$comp_name  = $this->session->userdata['comp_name'];
if($TOTPROJ == 1)
{
	$sql    = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
		      WHERE A.PRJCODE IN ($PRJCODECOL)
		      ORDER BY A.PRJCODE";
	$result   = $this->db->query($sql)->result();
	foreach($result as $row) :
	$PRJCODED   = $row ->PRJCODE;
	$PRJNAMED   = $row ->PRJNAME;
	endforeach;
	$PRJCODECOLL  = "'$PRJCODED'";
	$PRJNAMECOLL  = "$PRJNAMED";
}
else
{
	$PRJCODED 	= 'Multi Project Code';
	$PRJNAMED   = 'Multi Project Name';
	$myrow   	= 0;
	$sql    	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
		  			WHERE A.PRJCODE IN ($PRJCODECOL)
		  			ORDER BY A.PRJCODE";
	$result   	= $this->db->query($sql)->result();
	foreach($result as $row) :
	$myrow    	= $myrow + 1;
	$PRJCODED   = $row ->PRJCODE;
	$PRJNAMED   = $row ->PRJNAME;
	if($myrow == 1)
	{
		$PRJCODECOLL  = "$PRJCODED";
		$PRJCODECOL1  = "$PRJCODED";
		$PRJNAMECOLL  = "$PRJNAMED";
		$PRJNAMECOL1  = "$PRJNAMED";
	}
	if($myrow > 1)
	{
		$PRJCODECOL1  = "$PRJCODECOL1', '$PRJCODED";
		$PRJCODECOLL  = "'$PRJCODECOL1'";
		$PRJNAMECOL1  = "$PRJNAMECOL1, $PRJNAMED";
		$PRJNAMECOLL  = "$PRJNAMECOL1";
	}   
	endforeach;
}
$year = date('Y');
function getStartAndEndDate($week, $year)
{
    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    //$time += ((7*$week)+1-$day)*24*3600;
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-n-j', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-n-j', $time);
    return $return;
}
//function indonesian_date ($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') {
function indonesian_date ($timestamp = '', $date_format = 'l, j F Y | H:i') {
    if (trim ($timestamp) == '')
    {
            $timestamp = time ();
    }
    elseif (!ctype_digit ($timestamp))
    {
        $timestamp = strtotime ($timestamp);
    }
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace ("/S/", "", $date_format);
    $pattern = array (
        '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
        '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
        '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
        '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
        '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
        '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
        '/April/','/June/','/July/','/August/','/September/','/October/',
        '/November/','/December/',
    );
    $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
        'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
        'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
        'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
        'Oktober','November','Desember',
    );
    $date = date ($date_format, $timestamp);
    $date = preg_replace ($pattern, $replace, $date);
    $date = "{$date}";
    return $date;
}

$THISWEEK = $WEEKTO-2;
$THISWEEKV  = $WEEKTO-1;
$dateSel  = getStartAndEndDate($THISWEEK, $year);
$startDate  = $dateSel[0];
$endDate  = $dateSel[1];

// NEXT WEEK + 1 - CURRENT WEEK
$NEXTWEEK1  = $WEEKTO-1;
$NEXTWEEK1V = $WEEKTO;
$dateSel1 = getStartAndEndDate($NEXTWEEK1, $year);
$startDate1 = $dateSel1[0];
$endDate1 = $dateSel1[1];

// NEXT WEEK + 2
$NEXTWEEK2  = $WEEKTO;
$NEXTWEEK2V = $WEEKTO+1;
$dateSel2 = getStartAndEndDate($NEXTWEEK2, $year);
$startDate2 = $dateSel2[0];
$endDate2 = $dateSel2[1];

// NEXT WEEK + 3
$NEXTWEEK3  = $WEEKTO+1;
$NEXTWEEK3V = $WEEKTO+2;
$dateSel3 = getStartAndEndDate($NEXTWEEK3, $year);
$startDate3 = $dateSel3[0];
$endDate3 = $dateSel3[1];

// NEXT WEEK + 4
$NEXTWEEK4  = $WEEKTO+2;
$NEXTWEEK4V = $WEEKTO+3;
$dateSel4 = getStartAndEndDate($NEXTWEEK4, $year);
$startDate4 = $dateSel4[0];
$endDate4 = $dateSel4[1];

// NEXT WEEK + 5
$NEXTWEEK5  = $WEEKTO+3;
$NEXTWEEK5V = $WEEKTO+4;
$dateSel5 = getStartAndEndDate($NEXTWEEK5, $year);
$startDate5 = $dateSel5[0];
$endDate5 = $dateSel5[1];

// NEXT WEEK + 6
$NEXTWEEK6  = $WEEKTO+4;
$NEXTWEEK6V = $WEEKTO+5;
$dateSel6 = getStartAndEndDate($NEXTWEEK6, $year);
$startDate6 = $dateSel6[0];
$endDate6 = $dateSel6[1];

// NEXT WEEK + 7
$NEXTWEEK7  = $WEEKTO+5;
$NEXTWEEK7V = $WEEKTO+6;
$dateSel7 = getStartAndEndDate($NEXTWEEK7, $year);
$startDate7 = $dateSel7[0];
$endDate7 = $dateSel7[1];
/*echo "THISWEEK ($THISWEEK - $THISWEEKV) => Before = $startDate - $endDate<br>";       // (39 - 40)
echo "NEXTWEEK1 ($NEXTWEEK1 - $NEXTWEEK1V)  => Current = $startDate1 - $endDate1<br>";    // (40 - 41) // Current
echo "NEXTWEEK2 ($NEXTWEEK2 - $NEXTWEEK2V)  => Next + 1 = $startDate2 - $endDate2<br>";   // (41 - 42)
echo "NEXTWEEK3 ($NEXTWEEK3 - $NEXTWEEK3V)  => Next + 2 = $startDate3 - $endDate3<br>";   // (42 - 43)
echo "NEXTWEEK4 ($NEXTWEEK4 - $NEXTWEEK4V)  => Next + 3 = $startDate4 - $endDate4<br>";   // (43 - 44)*/
//return false;

$LangID   = $this->session->userdata['LangID'];

$sqlTransl    = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl    = $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode = $rowTransl->MLANG_CODE;
	$LangTransl = $rowTransl->LangTransl;

	if($TranslCode == 'Project')$Project = $LangTransl;
	if($TranslCode == 'Code')$Code = $LangTransl;
	if($TranslCode == 'Name')$Name = $LangTransl;
	if($TranslCode == 'AccountPayable')$AccountPayable = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'ChargeFiled')$ChargeFiled = $LangTransl;
	if($TranslCode == 'ChargeApproved')$ChargeApproved = $LangTransl;
	if($TranslCode == 'Date')$Date = $LangTransl;
	if($TranslCode == 'EndDate')$EndDate = $LangTransl;
	if($TranslCode == 'Status')$Status = $LangTransl;
	if($TranslCode == 'PlayedStatus')$PlayedStatus = $LangTransl;
	if($TranslCode == 'CheckTheList')$CheckTheList = $LangTransl;
endforeach;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
		<title><?php echo $h2_title; ?></title>
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
	</head>
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
	<body>
		<div class="page">
			<section class="content">
			    <table width="100%" border="0" style="size:auto">
				    <tr>
				        <td width="10%">
				            <div id="Layer1">
				                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
				                <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/print.gif');?>" border="0" alt="" align="absmiddle">Print</a>
				                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
				        <td width="51%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
				        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
				    </tr>
				    <tr>
				        <td rowspan="2" class="style2" style="text-align:left; font-weight:bold; font-size:14px"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/'. $comp_init . '/compLog.png') ?>" width="100" height="44"></td>
				        <td colspan="2" valign="bottom" class="style2" style="font-weight:bold; font-size:14px"><span class="style2" style="text-align:center; font-weight:bold; font-size:14px">
				          Neraca per 
				            <?php
				        //$date=date_create($End_Date);
				        echo indonesian_date ($End_Date, 'd F Y');
				      ?>
				        </span></td>
				      </tr>
				    <tr>
				        <td colspan="2" valign="top" class="style2" style="font-weight:bold; font-size:14px"><?php echo strtoupper($comp_name); ?></td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2" style="text-align:center"><hr noshade /></td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2">
				          	<table width="100%" border="0" style="size:auto">
				              	<tr>
				                	<td width="25%">&nbsp;</td>
				                    <td width="20%" style="border-top:double; border-bottom:groove; text-align:right">
				                    	<?php
					              			//$date=date_create($End_Date);
						             		echo indonesian_date ($End_Date, 'd F Y');
											?>
											    </td>
											    <td width="10%">&nbsp;</td>
											    <td width="25%">&nbsp;</td>
											    <td width="20%" style="border-top:double; border-bottom:groove; text-align:right">
											      <?php
											//$date=date_create($End_Date);
											echo indonesian_date ($End_Date, 'd F Y');
										?>
				                    </td>
				                </tr>
				                <?php
				        			// ------------------------ A K T I V A ------------------------
					            		/*
											-- CASH-HO      - KAS KANTOR
											-- CASH-PRJ     - KAS PROYEK
											-- BANK-HO      - BANK KANTOR
											-- BANK-PRJ     - BANK PROYEK
											-- AR       - PIUTANG USAHA
											-- AR-DP      - UANG MUKA PEMBELIAN KE SUPPLIER
											-- STOCK      - PERSEDIAAN
											-- TAX-DP     - PAJAK DIBAYAR DIMUKA
											-- INVENT     - INVENTARIS
											-- AST-LAND     - ASET TETAP : TANAH
											-- AST-BUILD    - ASET TETAP : BANGUNAN
											-- AST-MACH     - ASET TETAP : MESIN
											-- AST-VEHICL   - ASET TETAP : KENDARAAN
											-- AST-TOOLPRY    - ASET TETAP : PERALATAN PROYEK
											-- AST-TOOL     - ASET TETAP : ALAT/PERALATAN KANTOR
											-- AST-EQPRY    - ASET TETAP : PERLENGKAPAN PROYEK
											-- AST-EQOFC    - ASET TETAP : PERLENGKAPAN KANTOR
											-- AST-DEPR     - ASET TETAP : PENYUSUTAN
					            		*/

										// KAS KANTOR           -- CASH-HO
											$TOT_KASKTR = 0;
											$sqlKASKTR  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_KASKTR
															FROM tbl_chartaccount
															WHERE COGSReportID = 'CASH-HO' 
																-- AND Account_Class = 4 
																AND isLast = 1 
																AND PRJCODE = $PRJCODECOL";
											$resKASKTR    = $this->db->query($sqlKASKTR)->result();
											foreach($resKASKTR as $rowKASKTR):
												$TOT_KASKTR = $rowKASKTR->TOT_KASKTR;
											endforeach;
							              
							            // KAS PROYEK           -- CASH-PRJ
											$TOT_KASPRJ = 0;
											$sqlKASPRJ  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_KASPRJ
											        		FROM tbl_chartaccount
											      			WHERE COGSReportID = 'CASH-PRJ'
											        			-- AND Account_Class = 4 
											        			AND isLast = 1 
											        			AND PRJCODE = $PRJCODECOL";
											$resKASPRJ  = $this->db->query($sqlKASPRJ)->result();
											foreach($resKASPRJ as $rowKASPRJ):
												$TOT_KASPRJ 	= $rowKASPRJ->TOT_KASPRJ;
											endforeach;

										// BANK KANTOR            -- BANK-HO
											$TOT_BKTR = 0;
											$sqlBKTR  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_BKTR
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'BANK-HO'
											        -- Account_Class = 3
											        AND isLast = 1 
											        AND PRJCODE = $PRJCODECOL";
											$resBKTR    = $this->db->query($sqlBKTR)->result();
											foreach($resBKTR as $rowBKTR):
											$TOT_BKTR = $rowBKTR->TOT_BKTR;
											endforeach;

										// BANK PROYEK            -- BANK-PRJ
											$TOT_BPRJ = 0;
											$sqlBPRJ  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_BPRJ
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'BANK-PRJ' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resBPRJ    = $this->db->query($sqlBPRJ)->result();
											foreach($resBPRJ as $rowBPRJ):
											$TOT_BPRJ = $rowBPRJ->TOT_BPRJ;
											endforeach;

										// PIUTANG USAHA          -- AR
											$TOT_AR   = 0;
											$sqlAR    = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_AR
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AR' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resAR    = $this->db->query($sqlAR)->result();
											foreach($resAR as $rowAR):
											$TOT_AR = $rowAR->TOT_AR;
											endforeach;

										// PIUTANG LAIN-LAIN        -- AR-OTH
											$TOT_AROTH  = 0;
											$sqlAROTH = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_AROTH
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AR-OTH' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resAROTH = $this->db->query($sqlAROTH)->result();
											foreach($resAROTH as $rowAROTH):
											$TOT_AROTH  = $rowAROTH->TOT_AROTH;
											endforeach;

										// UANG MUKA PEMBELIAN        -- AR-DP
											$TOT_ARDP = 0;
											$sqlARDP  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ARDP
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AR-DP' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resARDP  = $this->db->query($sqlARDP)->result();
											foreach($resARDP as $rowARDP):
											$TOT_ARDP = $rowARDP->TOT_ARDP;
											endforeach;

										// PERSEDIAAN           -- STOCK
											$TOT_STOCK  = 0;
											$sqlSTOCK = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_STOCK
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'STOCK' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resSTOCK = $this->db->query($sqlSTOCK)->result();
											foreach($resSTOCK as $rowSTOCK):
											$TOT_STOCK  = $rowSTOCK->TOT_STOCK;
											endforeach;

										// BIAYA DIBAYAR DIMUKA       -- EXP-DP
											$TOT_EXPDP  = 0;
											$sqlEXPDP = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EXPDP
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'EXP-DP' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resEXPDP = $this->db->query($sqlEXPDP)->result();
											foreach($resEXPDP as $rowEXPDP):
											$TOT_EXPDP  = $rowEXPDP->TOT_EXPDP;
											endforeach;

										// PAJAK DIBAYAR DIMUKA       -- TAX-DP
											$TOT_TAXDP  = 0;
											$sqlTAXDP = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_TAXDP
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'TAX-DP' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resTAXDP = $this->db->query($sqlTAXDP)->result();
											foreach($resTAXDP as $rowTAXDP):
											$TOT_TAXDP  = $rowTAXDP->TOT_TAXDP;
											endforeach;

										// INVENTARIS             -- INVENT
											$TOT_INVT = 0;
											$sqlINVT  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_INVT
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'INVENT' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resINVT  = $this->db->query($sqlINVT)->result();
											foreach($resINVT as $rowINVT):
											$TOT_INVT = $rowINVT->TOT_INVT;
											endforeach;

										// ASET TETAP : TANAH         -- AST-LAND
											$TOT_ASTL = 0;
											$sqlASTL  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ASTL
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-LAND' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resASTL  = $this->db->query($sqlASTL)->result();
											foreach($resASTL as $rowASTL):
											$TOT_ASTL = $rowASTL->TOT_ASTL;
											endforeach;

										// ASET TETAP : BANGUNAN        -- AST-BUILD
											$TOT_ASTB = 0;
											$sqlASTB  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ASTB
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-BUILD' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resASTB  = $this->db->query($sqlASTB)->result();
											foreach($resASTB as $rowASTB):
											$TOT_ASTB = $rowASTB->TOT_ASTB;
											endforeach;

										// ASET TETAP : MESIN         -- AST-MACH
											$TOT_ASTMSN = 0;
											$sqlASTMSN  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ASTMSN
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-BUILD' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resASTMSN  = $this->db->query($sqlASTMSN)->result();
											foreach($resASTMSN as $rowASTMSN):
											$TOT_ASTMSN = $rowASTMSN->TOT_ASTMSN;
											endforeach;

										// ASET TETAP : KENDARAAN       -- AST-VEHICL
											$TOT_ASTV = 0;
											$sqlASTV  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ASTV
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-VEHICL' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resASTV  = $this->db->query($sqlASTV)->result();
											foreach($resASTV as $rowASTV):
											$TOT_ASTV = $rowASTV->TOT_ASTV;
											endforeach;

										// ASET TETAP : PERALATAN PROYEK    -- AST-TOOLPRY
											$TOT_ASTTP  = 0;
											$sqlASTTP = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ASTTP
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-TOOLPRY' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resASTTP = $this->db->query($sqlASTTP)->result();
											foreach($resASTTP as $rowASTTP):
											$TOT_ASTTP  = $rowASTTP->TOT_ASTTP;
											endforeach;

										// ASET TETAP : ALAT/PERALATAN KANTOR -- AST-TOOL
											$TOT_ASTT = 0;
											$sqlASTT  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ASTT
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-TOOL' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resASTT  = $this->db->query($sqlASTT)->result();
											foreach($resASTT as $rowASTT):
											$TOT_ASTT = $rowASTT->TOT_ASTT;
											endforeach;

										// ASET TETAP : PERLENGKAPAN PROYEK   -- AST-EQPRY
											$TOT_EQPRY  = 0;
											$sqlEQPRY = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EQPRY
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-EQPRY' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resEQPRY = $this->db->query($sqlEQPRY)->result();
											foreach($resEQPRY as $rowEQPRY):
											$TOT_EQPRY  = $rowEQPRY->TOT_EQPRY;
											endforeach;

										// ASET TETAP : PERLENGKAPAN KANTOR   -- AST-EQOFC
											$TOT_EQOFC  = 0;
											$sqlEQOFC = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EQOFC
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-EQOFC' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resEQOFC = $this->db->query($sqlEQOFC)->result();
											foreach($resEQOFC as $rowEQOFC):
											$TOT_EQOFC  = $rowEQOFC->TOT_EQOFC;
											endforeach;

										// ASET TETAP : PENYUSUTAN        -- AST-DEPR
											$TOT_ASTD = 0;
											$sqlASTD  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_ASTD
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AST-DEPR' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resASTD  = $this->db->query($sqlASTD)->result();
											foreach($resASTD as $rowASTD):
											$TOT_ASTD = $rowASTD->TOT_ASTD;
											endforeach;

											// TOTAL
											$TOT_KASB   = $TOT_KASKTR + $TOT_KASPRJ + $TOT_BKTR + $TOT_BPRJ;                  // KAS DAN SETARA KAS
											$TOT_BANK   = $TOT_BKTR + $TOT_BPRJ;
											$GTOT_ASTL    = $TOT_KASB + $TOT_AR + $TOT_AROTH + $TOT_STOCK + $TOT_TAXDP + $TOT_EXPDP + $TOT_ARDP;  // TOTAL ASET LANCAR

										// TOTAL ASET TETAP
											$GTOT_ASTTP   = $TOT_ASTL + $TOT_ASTB + $TOT_ASTMSN + $TOT_ASTV + $TOT_ASTTP + $TOT_ASTT + $TOT_EQPRY + $TOT_EQOFC + $TOT_ASTD + $TOT_INVT;
											$GTOT_ASTTL   = $GTOT_ASTTP;                        // TOTAL ASET TIDAK LANCAR
											$GTOT_AKTIVA  = $GTOT_ASTL + $GTOT_ASTTL;                 // GRAND TOTAL AKTIVA


											// ------------------------ P A S I V A ------------------------
											/*
											-- AP       - KEWAJIBAN : HUTANG USAHA
											-- AP-BANK      - KEWAJIBAN : HUTANG BANK
											-- AP-OTH     - KEWAJIBAN : HUTANG LAIN-LAIN
											-- EKUIT      - EKUITAS : MODAL DISETOR
											-- EKUIT-LRC    - EKUITAS : LABA RUGI BERJALAN
											*/
											        
										// KEWAJIBAN : HUTANG USAHA         -- AP
											$TOT_AP   = 0;
											$TOT_APV  = 0;
											/* karena AP, seharusnya Kredit - Debits
											$sqlAP  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_AP
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AP' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resAP  = $this->db->query($sqlAP)->result();*/
											$sqlAP  = "SELECT SUM(Base_OpeningBalance + Base_Kredit - Base_Debet) AS TOT_AP
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AP' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resAP  = $this->db->query($sqlAP)->result();
											foreach($resAP as $rowAP):
											$TOT_AP   = $rowAP->TOT_AP;
											//***$TOT_APV = abs($rowAP->TOT_AP);
											$TOT_APV  = ($rowAP->TOT_AP);
											endforeach;

										// KEWAJIBAN : HUTANG BANK          -- AP-BANK
											$TOT_APBNK  = 0;
											$TOT_APBNKV = 0;
											/*$sqlAPBNK = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_APBNK
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AP-BANK' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resAPBNK = $this->db->query($sqlAPBNK)->result();*/
											$sqlAPBNK = "SELECT SUM(Base_OpeningBalance + Base_Kredit - Base_Debet) AS TOT_APBNK
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AP-BANK' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resAPBNK = $this->db->query($sqlAPBNK)->result();
											foreach($resAPBNK as $rowAPBNK):
											$TOT_APBNK  = $rowAPBNK->TOT_APBNK;
											//$TOT_APBNKV = abs($rowAPBNK->TOT_APBNK);
											$TOT_APBNKV = ($rowAPBNK->TOT_APBNK);
											endforeach;

										// KEWAJIBAN : HUTANG LAIN-LAIN       -- AP-OTH
											$TOT_APOTH  = 0;
											$TOT_APOTHV = 0;
											$sqlAPOTH = "SELECT SUM(Base_OpeningBalance + Base_Kredit - Base_Debet) AS TOT_APOTH
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'AP-OTH' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resAPOTH = $this->db->query($sqlAPOTH)->result();
											foreach($resAPOTH as $rowAPOTH):
											$TOT_APOTH  = $rowAPOTH->TOT_APOTH;
											$TOT_APOTHV = ($rowAPOTH->TOT_APOTH);
											endforeach;

										// TOTAL KEWAJIBAN
											$GTOT_AP  = $TOT_APV + $TOT_APBNKV + $TOT_APOTHV;             // TOTAL HUTANG LANCAR
											//$GTOT_APV = abs($TOT_AP + $TOT_APBNK + $TOT_APOTH);           // TOTAL HUTANG LANCAR
											$GTOT_APV = ($TOT_AP + $TOT_APBNK + $TOT_APOTH);              // TOTAL HUTANG LANCAR

										// EKUITAS : MODAL DISETOR
											$TOT_EK   = 0;
											$TOT_EKV  = 0;
											$sqlEK  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EK
											        FROM tbl_chartaccount
											      WHERE COGSReportID = 'EKUIT' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resEK  = $this->db->query($sqlEK)->result();
											foreach($resEK as $rowEK):
											$TOT_EK   = $rowEK->TOT_EK;
											$TOT_EKV  = abs($rowEK->TOT_EK);
											endforeach;
							              
							            // EKUITAS : LABA RUGI DITAHAN
											$TOT_EKLRD  = 0;
											$TOT_EKLRDV = 0;
											$sqlEKLRD 	= "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EKLRD
												        	FROM tbl_chartaccount
												      		WHERE COGSReportID = 'EKUIT-LRD' AND isLast = 1 AND PRJCODE = $PRJCODECOL";
											$resEKLRD 	= $this->db->query($sqlEKLRD)->result();
											foreach($resEKLRD as $rowEKLRD):
												$TOT_EKLRD  = $rowEKLRD->TOT_EKLRD;
												$TOT_EKLRDV = abs($rowEKLRD->TOT_EKLRD);
											endforeach;
							              
							            // EKUITAS : LABA RUGI BERJALAN
							              	$TOT_EKLRBP = 0;  // Plus   : COGS & BEBAN
							              	$TOT_EKLRBM = 0;  // Minus  : Pendapatan
							              	$TOT_EKLRBV = 0;
							              
							            // PENDAPATAN : SEMUA ACOUNT KATEGORI 4
							                $sqlEKLRBP  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EKLRBP
							                          FROM tbl_chartaccount
							                        WHERE PRJCODE = $PRJCODECOL
							                          AND isLast = 1 
							                          -- AND COGSReportID = 'EKUIT-LRC' 
							                          AND Account_Category IN (4)";
							                $resEKLRBP  = $this->db->query($sqlEKLRBP)->result();
							                foreach($resEKLRBP as $rowEKLRBP):
							                  $TOT_EKLRBP = $rowEKLRBP->TOT_EKLRBP;
							                  $TOT_EKLRBPV= abs($rowEKLRBP->TOT_EKLRBP);
							                endforeach;
							              
							            // BEBAN : SEMUA ACOUNT KATEGORI 5 DAN 8
							                $sqlEKLRBM  = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EKLRBM
							                          		FROM tbl_chartaccount
							                        		WHERE 
							                          			PRJCODE = $PRJCODECOL
							                          			-- AND COGSReportID = 'EKUIT-LRC' 
							                          			AND isLast = 1
							                          			AND Account_Category IN (5)";
							                $resEKLRBM  = $this->db->query($sqlEKLRBM)->result();
							                foreach($resEKLRBM as $rowEKLRBM):
							                  $TOT_EKLRBM1  = abs($rowEKLRBM->TOT_EKLRBM);
							                  $TOT_EKLRBM = abs($rowEKLRBM->TOT_EKLRBM);
							                  //echo "TOT_EKLRBM1 = $TOT_EKLRBM1<br>";
							                endforeach;
							                //echo "TOT_EKLRBM = $TOT_EKLRBM<br>";
							                $sqlEKLRBM8 = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EKLRBM8
							                          		FROM tbl_chartaccount
							                    			WHERE 
							                          			PRJCODE = $PRJCODECOL
							                          			-- AND COGSReportID = 'EKUIT-LRC' 
							                          			AND isLast = 1
							                          			AND Account_Category IN (8)";
							                $resEKLRBM  = $this->db->query($sqlEKLRBM8)->result();
							                foreach($resEKLRBM as $rowEKLRBM):
							                  	$TOT_EKLRBM8  = abs($rowEKLRBM->TOT_EKLRBM8);
							                endforeach;
							              
							            // BEBAN : SEMUA ACOUNT KATEGORI 9
							                $sqlEKLRBM9 = "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOT_EKLRBM9
															FROM tbl_chartaccount
															WHERE PRJCODE = $PRJCODECOL
																AND isLast = 1 
																-- AND COGSReportID = 'EKUIT-LRC'
																AND Account_Category IN (9,10)";
							                $resEKLRBM9 = $this->db->query($sqlEKLRBM9)->result();
							                foreach($resEKLRBM9 as $rowEKLRBM9):
							                  	$TOT_EKLRBM9  = abs($rowEKLRBM9->TOT_EKLRBM9);
							                endforeach;
							              
							             // LABA RUGI (BISA PLUS BISA MIN)
								              // L/R      = PENDAPATAN (4) - BEBAN (4,8,9)
								              //echo "L/R = $TOT_EKLRBPV - $TOT_EKLRBM - $TOT_EKLRBM8 - $TOT_EKLRBM9<br>";
								              $TOT_EKLRB    = $TOT_EKLRBPV + $TOT_EKLRDV - $TOT_EKLRBM - $TOT_EKLRBM8 - $TOT_EKLRBM9;
								              $TOT_EKLRBV   = $TOT_EKLRB;
								              
								              $GTOT_EK    = $TOT_EKV + $TOT_EKLRB;                    // TOTAL EKUITAS / MODAL
								              //echo "$GTOT_EK    = $TOT_EKLRBPV + $TOT_EKLRDV - $TOT_EKLRBM - $TOT_EKLRBM8 - $TOT_EKLRBM9";
								              //$GTOT_EKV   = abs($TOT_EK + $TOT_EKLRB);                  // TOTAL EKUITAS / MODAL
								              $GTOT_EKV   = $GTOT_EK;                           // TOTAL EKUITAS / MODAL
								              
								              //$GTOT_PASIVA  = $GTOT_APV + $GTOT_EKV;                    // GRAND TOTAL PASIVA
								              $GTOT_PASIVA  = $GTOT_AP + $GTOT_EK;                      // GRAND TOTAL PASIVA
								              //$GTOT_PASIVAV = abs($GTOT_AP + $GTOT_EK);                   // GRAND TOTAL PASIVA
								              $GTOT_PASIVAV = $GTOT_PASIVA;                         // GRAND TOTAL PASIVA
				       			?>
				                <tr style="font-weight:bold">
				                  	<td> ASET</td>
				                    <td style="text-align:right"><?php echo number_format($GTOT_AKTIVA, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>KEWAJIBAN</td>
				                    <td style="text-align:right"><?php echo number_format($GTOT_AP, 2); ?></td>
				                </tr>
				                <tr>
				                  	<td style="font-weight:bold">&nbsp;&nbsp;Aset Lancar</td>
				                    <td style="text-align:right; font-weight:bold"><?php echo number_format($GTOT_ASTL, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;&nbsp;Hutang Usaha</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_APV, 2); ?></td>
				                </tr>
				                <tr>
				                  	<td style="font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kas Dan Setara Kas</td>
				                    <td style="text-align:right; font-weight:bold"><?php echo number_format($TOT_KASB, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;&nbsp;Hutang Bank</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_APBNKV, 2); ?></td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kas Kantor</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_KASKTR, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;&nbsp;Hutang Lain-lain</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_APOTHV, 2); ?></td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_BANK, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td style="text-align:right">&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kas Proyek</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_KASPRJ, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td style="font-weight:bold">EKUITAS</td>
				                    <td style="text-align:right; font-weight:bold"><?php echo number_format($GTOT_EKV, 2); ?></td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piutang Usaha</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_AR, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;&nbsp;Modal Disetor</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_EKV, 2); ?></td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piutang Lain-Lain</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_AROTH, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;&nbsp;Laba Rugi Ditahan</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_EKLRDV, 2); ?></td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Uang Muka Pembelian</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ARDP, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;&nbsp;Laba Rugi Bulan Berjalan</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_EKLRBV, 2); ?></td>
				                </tr>
				              	<tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persediaan</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_STOCK, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td style="text-align:right">&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Biaya dibayar dimuka</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_EXPDP, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pajak dibayar dimuka</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_TAXDP, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;</td>
				                    <td style="text-align:right">&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                    <td style="font-weight:bold">&nbsp;&nbsp;Aset Tidak Lancar</td>
				                    <td style="text-align:right; font-weight:bold"><?php echo number_format($GTOT_ASTTL, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td style="font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aset Tetap</td>
				                    <td style="text-align:right; font-weight:bold"><?php echo number_format($GTOT_ASTTP, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanah</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ASTL, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bangunan</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ASTB, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mesin</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ASTMSN, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kendaraan</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ASTV, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Proyek</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ASTTP, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Kantor</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ASTT, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perlengkapan Proyek</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_EQPRY, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perlengkapan Kantor</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_EQOFC, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inventaris</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_INVT, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Akumulasi Penyusutan</td>
				                    <td style="text-align:right"><?php echo number_format($TOT_ASTD, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;</td>
				                    <td style="border-top:double; border-bottom:groove; text-align:right"><?php echo number_format($GTOT_AKTIVA, 2); ?></td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td style="border-top:double; border-bottom:groove; text-align:right"><?php echo number_format($GTOT_PASIVAV, 2);?></td>
				                </tr>
				                <tr>
				                  <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td style="text-align:right">&nbsp;</td>
				                </tr>
				                <tr>
				                  	<td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td>&nbsp;</td>
				                    <td style="text-align:right" nowrap>Jakarta, 
							            <?php
							              	//$date=date_create($End_Date);
							              	echo indonesian_date ($End_Date, 'd F Y');
							            ?>
				                    </td>
				                </tr>
				            </table>
				        </td>
				    </tr>
			</table>
		</section>
	</div>
</body>
</html>