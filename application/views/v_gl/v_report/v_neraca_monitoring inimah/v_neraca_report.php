<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= v_cashflow_report_adm.php
 * Location		= -
*/

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
if($TOTPROJ == 1)
{
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
					WHERE A.PRJCODE IN ($PRJCODECOL)
					ORDER BY A.PRJCODE";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJCODED 	= $row ->PRJCODE;
		$PRJNAMED 	= $row ->PRJNAME;
	endforeach;
	$PRJCODECOLL	= "'$PRJCODED'";
	$PRJNAMECOLL	= "$PRJNAMED";
}
else
{
	$PRJCODED	= 'Multi Project Code';
	$PRJNAMED 	= 'Multi Project Name';
	$myrow		= 0;
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
					WHERE A.PRJCODE IN ($PRJCODECOL)
					ORDER BY A.PRJCODE";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODED 	= $row ->PRJCODE;
		$PRJNAMED 	= $row ->PRJNAME;
		if($myrow == 1)
		{
			$PRJCODECOLL	= "$PRJCODED";
			$PRJCODECOL1	= "$PRJCODED";
			$PRJNAMECOLL	= "$PRJNAMED";
			$PRJNAMECOL1	= "$PRJNAMED";
		}
		if($myrow > 1)
		{
			$PRJCODECOL1	= "$PRJCODECOL1', '$PRJCODED";
			$PRJCODECOLL	= "'$PRJCODECOL1'";
			$PRJNAMECOL1	= "$PRJNAMECOL1, $PRJNAMED";
			$PRJNAMECOLL	= "$PRJNAMECOL1";
		}		
	endforeach;
	//echo $PRJCODECOLL;
	//return false;
}
$year	= date('Y');
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
$THISWEEK	= $WEEKTO-2;
$THISWEEKV	= $WEEKTO-1;
$dateSel	= getStartAndEndDate($THISWEEK, $year);
$startDate	= $dateSel[0];
$endDate	= $dateSel[1];

// NEXT WEEK + 1 - CURRENT WEEK
$NEXTWEEK1	= $WEEKTO-1;
$NEXTWEEK1V	= $WEEKTO;
$dateSel1	= getStartAndEndDate($NEXTWEEK1, $year);
$startDate1	= $dateSel1[0];
$endDate1	= $dateSel1[1];

// NEXT WEEK + 2
$NEXTWEEK2	= $WEEKTO;
$NEXTWEEK2V	= $WEEKTO+1;
$dateSel2	= getStartAndEndDate($NEXTWEEK2, $year);
$startDate2	= $dateSel2[0];
$endDate2	= $dateSel2[1];

// NEXT WEEK + 3
$NEXTWEEK3	= $WEEKTO+1;
$NEXTWEEK3V	= $WEEKTO+2;
$dateSel3	= getStartAndEndDate($NEXTWEEK3, $year);
$startDate3	= $dateSel3[0];
$endDate3	= $dateSel3[1];

// NEXT WEEK + 4
$NEXTWEEK4	= $WEEKTO+2;
$NEXTWEEK4V	= $WEEKTO+3;
$dateSel4	= getStartAndEndDate($NEXTWEEK4, $year);
$startDate4	= $dateSel4[0];
$endDate4	= $dateSel4[1];

// NEXT WEEK + 5
$NEXTWEEK5	= $WEEKTO+3;
$NEXTWEEK5V	= $WEEKTO+4;
$dateSel5	= getStartAndEndDate($NEXTWEEK5, $year);
$startDate5	= $dateSel5[0];
$endDate5	= $dateSel5[1];

// NEXT WEEK + 6
$NEXTWEEK6	= $WEEKTO+4;
$NEXTWEEK6V	= $WEEKTO+5;
$dateSel6	= getStartAndEndDate($NEXTWEEK6, $year);
$startDate6	= $dateSel6[0];
$endDate6	= $dateSel6[1];

// NEXT WEEK + 7
$NEXTWEEK7	= $WEEKTO+5;
$NEXTWEEK7V	= $WEEKTO+6;
$dateSel7	= getStartAndEndDate($NEXTWEEK7, $year);
$startDate7	= $dateSel7[0];
$endDate7	= $dateSel7[1];
/*echo "THISWEEK ($THISWEEK - $THISWEEKV) => Before = $startDate - $endDate<br>";				// (39 - 40)
echo "NEXTWEEK1 ($NEXTWEEK1 - $NEXTWEEK1V)  => Current = $startDate1 - $endDate1<br>";		// (40 - 41) // Current
echo "NEXTWEEK2 ($NEXTWEEK2 - $NEXTWEEK2V)  => Next + 1 = $startDate2 - $endDate2<br>";		// (41 - 42)
echo "NEXTWEEK3 ($NEXTWEEK3 - $NEXTWEEK3V)  => Next + 2 = $startDate3 - $endDate3<br>";		// (42 - 43)
echo "NEXTWEEK4 ($NEXTWEEK4 - $NEXTWEEK4V)  => Next + 3 = $startDate4 - $endDate4<br>";		// (43 - 44)*/
//return false;

$LangID 	= $this->session->userdata['LangID'];

$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl		= $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode	= $rowTransl->MLANG_CODE;
	$LangTransl	= $rowTransl->LangTransl;
	
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
<body>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="19%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/print.gif');?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/1stWebLogo.png') ?>" width="100" height="44"></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">PROJECT  cashflow summary</span></td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:14px">PT 1stWEB Media, Tbk,</td>
    </tr>
        <?php
            $StartDate1 = date('d/m/Y',strtotime($startDate1));
            $EndDate1 = date('d/m/Y',strtotime($endDate1));
            $End_Date = date('Y-m-d',strtotime($endDate));
            $startDatev = date('d/m/Y',strtotime($startDate));
            $endDatev 	= date('d/m/Y',strtotime($endDate));
			
			date_default_timezone_set("Asia/Jakarta");
			$currentD	= date('d/m/Y H:i:s')
        ?>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
        	<table width="100%">
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">Report Week to</td>
                    <td width="1%">:</td>
                    <td colspan="2"><span class="style2" style="text-align:left; font-style:italic"><?php echo $NEXTWEEK1; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">Report Date</td>
                  <td>:</td>
                  <td width="59%">
                    <span class="style2" style="text-align:left; font-style:italic">
                   		<?php echo "$startDatev to $endDatev"; ?>            		</span>                  </td>
                  <td width="32%" style="text-align:right">Generate : <?php echo $currentD; ?></td>
              </tr>
            </table>        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Project; ?></td>
                    <td colspan="2" nowrap style="text-align:center; font-weight:bold">Previous</td>
                    <td colspan="5" style="text-align:center; font-weight:bold">Week - <?php echo $NEXTWEEK1; ?> (Rp. dalam Juta)</td>
                    <td colspan="3" nowrap style="text-align:center; font-weight:bold">Total</td>
                    <td colspan="8" style="text-align:center; font-weight:bold">TTK Jatuh Tempo Minggu ke-</td>
                </tr>
                <?php
					$NEXTWEEK1_A	= $NEXTWEEK1 + 1;
						$REM_VAL_A	= $NEXTWEEK1_A - 52;
						if($REM_VAL_A > 0)
							$NEXTWEEK1_A	= $REM_VAL_A;
					$NEXTWEEK1_B	= $NEXTWEEK1 + 2;
						$REM_VAL_B	= $NEXTWEEK1_B - 52;
						if($REM_VAL_B > 0)
							$NEXTWEEK1_B	= $REM_VAL_B;
					$NEXTWEEK1_C	= $NEXTWEEK1 + 3;
						$REM_VAL_C	= $NEXTWEEK1_C - 52;
						if($REM_VAL_C > 0)
							$NEXTWEEK1_C	= $REM_VAL_C;
					$NEXTWEEK1_D	= $NEXTWEEK1 + 4;
						$REM_VAL_D	= $NEXTWEEK1_D - 52;
						if($REM_VAL_D > 0)
							$NEXTWEEK1_D	= $REM_VAL_D;
					$NEXTWEEK1_E	= $NEXTWEEK1 + 5;
						$REM_VAL_E	= $NEXTWEEK1_E - 52;
						if($REM_VAL_E > 0)
							$NEXTWEEK1_E	= $REM_VAL_E;
					$NEXTWEEK1_F	= $NEXTWEEK1 + 6;
						$REM_VAL_F	= $NEXTWEEK1_F - 52;
						if($REM_VAL_F > 0)
							$NEXTWEEK1_F	= $REM_VAL_F;
					$NEXTWEEK1_G	= $NEXTWEEK1 + 7;
						$REM_VAL_G	= $NEXTWEEK1_G - 52;
						if($REM_VAL_G > 0)
							$NEXTWEEK1_G	= $REM_VAL_G;
                ?>
                <tr style="background:#CCCCCC">
                    <td width="2%" nowrap style="text-align:center; font-weight:bold"><?php echo $Code; ?></td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Name; ?></td>
                	<td width="8%" nowrap style="text-align:center; font-weight:bold">Cashflow<br>Balance</td>
                    <td width="6%" nowrap style="text-align:center; font-weight:bold">Outst.<br>TTK</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">Cash In</td>
                    <td width="9%" style="text-align:center; font-weight:bold">TTK <br>Diterima</td>
                    <td width="4%" style="text-align:center; font-weight:bold">Pemb. TTK</td>
                    <td width="5%" style="text-align:center; font-weight:bold">PPh</td>
                    <td width="7%" style="text-align:center; font-weight:bold">OVH (3%)</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">Balance</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">Outstanding<br> TTK</td>
                    <td width="6%" nowrap style="text-align:center; font-weight:bold">OVH(3%)</td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">Prev.</td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold"><?php echo $NEXTWEEK1_A; ?></td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold"><?php echo $NEXTWEEK1_B; ?></td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold"><?php echo $NEXTWEEK1_C; ?></td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold"><?php echo $NEXTWEEK1_D; ?></td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold"><?php echo $NEXTWEEK1_E ?></td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold"><?php echo $NEXTWEEK1_F; ?></td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold"><?php echo $NEXTWEEK1_G; ?></td>
              </tr>
                <?php
                if($CFType == 1)	// SUMMARY
                {
					// PROJECT
						$PRJNAME	= 'none';
						$sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE IN ($PRJCODECOLL) ORDER BY PRJCODE";
						$resPRJ 	= $this->db->query($sqlPRJ)->result();						
						
						$GT_PREV_BALANCE	= 0;
						$GT_PREV_OUTPAY		= 0;
						$GT_CASHIN_W		= 0;
						$GT_TOT_OUTPAY_W	= 0;
						$GT_PAYM_W			= 0;
						$GT_PAYM_WPPh		= 0;
						$GT_OVH_WALLP		= 0;
						$GT_OVH_W			= 0;
						$GT_SALDO_W			= 0;
						$GT_NOW_BALANCE		= 0;
						$GT_TOT_OUTPAY_NOW	= 0;
						$GT_TOT_AKUM_OVH	= 0;
						$GT_TOT_OUTPAY_NW0	= 0;
						$GT_TOT_OUTPAY_NW1	= 0;
						$GT_TOT_OUTPAY_NW2	= 0;
						$GT_TOT_OUTPAY_NW3	= 0;
						$GT_TOT_OUTPAY_NW4	= 0;
						$GT_TOT_OUTPAY_NW5	= 0;
						$GT_TOT_OUTPAY_NW6	= 0;
						$GT_TOT_OUTPAY_NW7	= 0;
						foreach($resPRJ as $rowPRJ) :
							$PRJCODE	= $rowPRJ->PRJCODE;
							$PRJNAME	= $rowPRJ->PRJNAME;
						
							// A. BEGINNING BALANCE. CUT OFF TO 29/09/2017
								$sqlPRJC	= "tbl_balances WHERE BS_PRJCODE = '$PRJCODE'";
								$resPRJC	= $this->db->count_all($sqlPRJC);
								$BS_IN		= 0;
								$BS_OUT		= 0;
								$BS_SALARY	= 0;
								$BS_BALANCE	= 0;
								$BS_AP		= 0;
								$BS_GBALANCE= 0;
								if($resPRJC > 0)
								{
									$sqlPRJD 	= "SELECT BS_IN, BS_OUT, BS_SALARY, BS_BALANCE, BS_AP, BS_GBALANCE
													FROM tbl_balances WHERE BS_PRJCODE = '$PRJCODE'";
									$resPRJD 	= $this->db->query($sqlPRJD)->result();

									foreach($resPRJD as $rowPRJD):
										$BS_IN		= $rowPRJD->BS_IN;
										$BS_OUT		= $rowPRJD->BS_OUT;
										$BS_SALARY	= $rowPRJD->BS_SALARY;
										$BS_BALANCE	= $rowPRJD->BS_BALANCE;
										$BS_AP		= $rowPRJD->BS_AP;			// Due Date Outstanding Payment
										$BS_GBALANCE= $rowPRJD->BS_GBALANCE;
									endforeach;
								}
							
							// A. PREPARE
								// TOTAL CASH IN BEFORE DATE REPORT
									$CASHIN_P		= 0;
									$sqlCASHIN_P	= "SELECT SUM(TRBCOST) AS CASHIN_P
														FROM bs_trxbank_ci
														WHERE PRJCODE = '$PRJCODE'
															AND TRBDATE BETWEEN '2017-09-30' AND '$startDate'
															AND CFLCODE IN (11,12,13,14,15,16,17,18,19)";
									$resCASHIN_P 	= $this->db->query($sqlCASHIN_P)->result();							
									foreach($resCASHIN_P as $sqlCASHIN_P) :
										$CASHIN_P 	= $sqlCASHIN_P->CASHIN_P;
									endforeach;
									$CASHIN_P		= $CASHIN_P;					// CASH IN SEBELUM PERIODE LAPORAN
									
								// TOTAL OUTSTANDING PAYMENT BEFORE DATE REPORT
									$OUTPAY_P		= 0;
									$OUTPAYPPN_P	= 0;
									$sqlOUTPAY_P	= "SELECT SUM(B.TTKCOST) AS OUTPAY_P, SUM(B.PPN) AS OUTPAYPPN_P
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.TTKDATE BETWEEN '2017-09-30' AND '$startDate'
															AND A.TRXCANC = 0";
									$resOUTPAY_P	= $this->db->query($sqlOUTPAY_P)->result();						
									foreach($resOUTPAY_P as $sqlOUTPAY_P) :
										$OUTPAY_P	= $sqlOUTPAY_P->OUTPAY_P;
										$OUTPAYPPN_P= $sqlOUTPAY_P->OUTPAYPPN_P;
									endforeach;
									$TOT_OUTPAY_P	= $OUTPAY_P + $OUTPAYPPN_P;		// TAGIHAN SEBELUM PERIODE LAPORAN
									//echo "TOT_OUTPAY_P = $TOT_OUTPAY_P<br>";
									
								// TOTAL PAYMENT BEFORE DATE REPORT NON SALARY
									$PAIDTTK_P		= 0;
									$PAIDTTKPPN_P	= 0;
									$sqlPAYM_P 		= "SELECT SUM(A.TRBCOST) AS PAIDTTK_P
														FROM bs_trxbank_paid A
															INNER JOIN bs_ttkhd_paid B ON A.VOCCODE = B.VOCCODE
														WHERE A.PRJCODE = '$PRJCODE'
															AND A.TRBDATE BETWEEN '2017-09-30' AND '$startDate'
															AND A.CFLCODE IN (20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39)
															AND A.TRXCANC = 0"; // OK
									$resPAYM_P	= $this->db->query($sqlPAYM_P)->result();
									foreach($resPAYM_P as $sqlPAYM_P) :
										$PAIDTTK_P 		= $sqlPAYM_P->PAIDTTK_P;
										$PAIDTTKPPN_P	= 0;
									endforeach;
									$PAYM_P	= $PAIDTTK_P + $PAIDTTKPPN_P;			// PEMBAYARAN SEBELUM PERIODE LAPORAN
									//echo "PAYM_P = $PAYM_P";
									
								// TOTAL OVH 3% BEFORE DATE REPORT
									$OVH_WALLP		= 0.03 * $CASHIN_P;				// OVH SEBELUM PERIODE LAPORAM
									//echo "OVH_WALLP => $OVH_WALLP<br>";
								// TOTAL PREVIOUS BALANCE
									//$PREV_BALANCE	= round(($BS_BALANCE + ($CASHIN_P - $PAYM_P - $OVH_WALLP)) / 1000000);
									$PREV_BALANCE	= round(($BS_BALANCE + $CASHIN_P - $PAYM_P - $OVH_WALLP) / 1000000);
									//
									//echo "$PREV_BALANCE = $BS_BALANCE + $CASHIN_P - $PAYM_P - $OVH_WALLP<br>";
									$PREV_OUTPAY	= round(($TOT_OUTPAY_P + $PAYM_P) / 1000000);	// TOTAL TAGIHAN SEBELUM PERIODE LAPORAN
									//echo "$PREV_OUTPAY = $TOT_OUTPAY_P + $PAYM_P";
									
									//echo "$PREV_OUTPAY = $TOT_OUTPAY_P - $PAYM_P<br>";
									
							// B. CASH IN AND CASH OUT IN A WEEK
								// TOTAL CASH IN
									$CASHIN_W1		= 0;
									$sqlCASHIN_W	= "SELECT SUM(TRBCOST) AS CASHIN_W
														FROM bs_trxbank_ci
														WHERE PRJCODE = '$PRJCODE'
															AND TRBDATE BETWEEN '$startDate' AND '$endDate'
															AND CFLCODE IN (11,12,13,14,15,16,17,18,19)"; // OK
									$resCASHIN_W	= $this->db->query($sqlCASHIN_W)->result();							
									foreach($resCASHIN_W as $rowCASHIN_W) :
										$CASHIN_W1	= $rowCASHIN_W->CASHIN_W;
									endforeach;
									$CASHIN_WORIG	= $CASHIN_W1;
									$CASHIN_W		= round($CASHIN_W1 / 1000000);
							
								// TOTAL OUTSTANDING PAYMENT IN A WEEK
									$OUTPAY_W		= 0;
									$OUTPAYPPN_W	= 0;
									$sqlOUTPAY_W 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_W, SUM(B.PPN) AS OUTPAYPPN_W
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.TTKDATE BETWEEN '$startDate' AND '$endDate'
															AND A.TRXCANC = 0";
									$resOUTPAY_W	= $this->db->query($sqlOUTPAY_W)->result();							
									foreach($resOUTPAY_W as $rowOUTPAY_W) :
										$OUTPAY_W 	= $rowOUTPAY_W->OUTPAY_W;
										$OUTPAYPPN_W= $rowOUTPAY_W->OUTPAYPPN_W;
									endforeach;
									$TOT_OUTPAY_W	= round(($OUTPAY_W + $OUTPAYPPN_W) / 1000000);
									//echo "$TOT_OUTPAY_W	= round(($OUTPAY_W + $OUTPAYPPN_W)";
									
								// TOTAL PAYMENT
									$PAYM_W1	= 0;
									/*$sqlPAYM_W 	= "SELECT SUM(B.TTKCOST) AS PAYM_W1, SUM(B.PPN) AS PAIDTTKPPN_W1
														FROM bs_ttkhd_paid A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.TTKDATE BETWEEN '$startDate' AND '$endDate'"; // OK*/
									$sqlPAYM_W 	= "SELECT SUM(A.TRBCOST) AS PAYM_W1
													FROM bs_trxbank_paid A
														INNER JOIN bs_ttkhd_paid B ON A.VOCCODE = B.VOCCODE
													WHERE A.PRJCODE = '$PRJCODE'
														AND A.TRBDATE BETWEEN '$startDate' AND '$endDate'
														AND A.CFLCODE IN (20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39)
														AND A.TRXCANC = 0"; // OK
									$resPAYM_W 	= $this->db->query($sqlPAYM_W)->result();							
									foreach($resPAYM_W as $rowPAYM_W) :
										$PAYM_W1 		= $rowPAYM_W->PAYM_W1;
										$PAIDTTKPPN_W1 	= 0;
									endforeach;
									$PAYM_W2		= $PAYM_W1 + $PAIDTTKPPN_W1;
									
									$PAYM_W1A	= 0;
									$sqlPAYM_WA 	= "SELECT SUM(DISTINCT A.TRBCOST) AS PAYM_W1A
														FROM bs_trxbank_paid1 A
															INNER JOIN bs_votbhd C ON A.VOCCODE = C.VOCCODE
																AND C.REFFVOC != ''
														WHERE A.PRJCODE = '$PRJCODE'
															AND A.TRBDATE BETWEEN '$startDate' AND '$endDate'
															AND A.CFLCODE IN (20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39)
															AND A.TRXCANC = 0"; // OK
									$resPAYM_WA 	= $this->db->query($sqlPAYM_WA)->result();							
									foreach($resPAYM_WA as $rowPAYM_WA) :
										$PAYM_W1A 		= $rowPAYM_WA->PAYM_W1A;
									endforeach;
									$PAYM_W2A		= $PAYM_W1A;
									//$PAYM_W			= round($PAYM_W2 / 1000000);
									//echo "test $PAYM_W2";
									/*$sqlPAYM_WA	= "SELECT SUM(A.VOCCOST) AS PAYM_WA
													FROM bs_votbhd A
													WHERE A.PRJCODE = '$PRJCODE'
														AND A.TRXDATE BETWEEN '$startDate' AND '$endDate'
														AND A.TRXCANC = 0";*/ // OK
									//$resPAYM_WA	= $this->db->query($sqlPAYM_WA)->result();							
									//foreach($resPAYM_WA as $rowPAYM_WA) :
										//$PAYM_WA1 		= $rowPAYM_WA->PAYM_WA;
									//endforeach;
									//$PAYM_WA2		= $PAYM_WA1 + $PAIDTTKPPN_W1;
									$PAYM_WA2		= 0;
									//$PAYM_W			= round($PAYM_WA2 / 1000000);
									//echo "<br>$PAYM_W2 + $PAYM_WA2";
									$PAYTOT_W		= $PAYM_W2 + $PAYM_W2A;
									$PAYM_W			= round($PAYTOT_W / 1000000);
									
									
									
								// TOTAL PAYMENT - PPh
									$PAYM_WPPh1		= 0;
									$sqlPAYM_WPPh 	= "SELECT SUM(A.CSTCOST) AS PAYM_WPPh1
														FROM bs_vocdt_pph A
															INNER JOIN bs_trxbank_paid B ON A.VOCCODE = B.VOCCODE
														WHERE B.PRJCODE= '$PRJCODE'
															AND B.TRBDATE BETWEEN '$startDate' AND '$endDate'
															AND B.TRXCANC = 0"; // OK
									$resPAYM_WPPh 	= $this->db->query($sqlPAYM_WPPh)->result();							
									foreach($resPAYM_WPPh as $rowPAYM_WPPh) :
										$PAYM_WPPh1 	= abs($rowPAYM_WPPh->PAYM_WPPh1);
										$PAIDTTKPPN_W1 	= 0;
									endforeach;
									$PAYM_WPPh2		= $PAYM_WPPh1 + $PAIDTTKPPN_W1;
									$PAYM_WPPh		= round($PAYM_WPPh2 / 1000000);
							
								// TOTAL OVERHEADS
									$OVH_W		= round(0.03 * $PAYM_W2);
									$OVH_W2		= round($OVH_W / 1000000);
									//echo "OVH_W => $OVH_W<br>";
							
							// C. SALDO
								$SALDO_W		= $CASHIN_W - $PAYM_W - $PAYM_WPPh - $OVH_W2;
								
							// E. CURRENT BALANCE
								$NOW_BALANCE	= $PREV_BALANCE + $SALDO_W;
								$NOW_OUTPAY		= $PREV_OUTPAY + $TOT_OUTPAY_W - $PAYM_W - $PAYM_WPPh;
								$NOW_AKUM_OVH	= round(($OVH_WALLP + $OVH_W) / 1000000);
								//echo "$NOW_AKUM_OVH = $OVH_WALLP + $OVH_W";
							
							// F. NEXT WEEK + 0
									$OUTPAY_NW0		= 0;
									$OUTPAYPPN_NW0	= 0;
									$sqlOUTPAY_NW0 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW0, SUM(B.PPN) AS OUTPAYPPN_NW0
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE < '$startDate1'
															AND A.TRXCANC = 0"; // OK											
									$resOUTPAY_NW0	= $this->db->query($sqlOUTPAY_NW0)->result();							
									foreach($resOUTPAY_NW0 as $rowOUTPAY_NW0) :
										$OUTPAY_NW0 	= $rowOUTPAY_NW0->OUTPAY_NW0;
										$OUTPAYPPN_NW0	= $rowOUTPAY_NW0->OUTPAYPPN_NW0;
									endforeach;
									$TOT_OUTPAY_NW0	= round(($OUTPAY_NW0 + $OUTPAYPPN_NW0) / 1000000);
							
							// F. NEXT WEEK + 1
									$OUTPAY_NW1		= 0;
									$OUTPAYPPN_NW1	= 0;
									$sqlOUTPAY_NW1 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW1, SUM(B.PPN) AS OUTPAYPPN_NW1
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE BETWEEN '$startDate1' AND '$endDate1'
															AND A.TRXCANC = 0"; // OK												
									$resOUTPAY_NW1	= $this->db->query($sqlOUTPAY_NW1)->result();							
									foreach($resOUTPAY_NW1 as $rowOUTPAY_NW1) :
										$OUTPAY_NW1 	= $rowOUTPAY_NW1->OUTPAY_NW1;
										$OUTPAYPPN_NW1	= $rowOUTPAY_NW1->OUTPAYPPN_NW1;
									endforeach;
									$TOT_OUTPAY_NW1	= round(($OUTPAY_NW1 + $OUTPAYPPN_NW1) / 1000000);
									
							// F. NEXT WEEK + 2
									$OUTPAY_NW2		= 0;
									$OUTPAYPPN_NW2	= 0;
									$sqlOUTPAY_NW2 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW2, SUM(B.PPN) AS OUTPAYPPN_NW2
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE BETWEEN '$startDate2' AND '$endDate2'
															AND A.TRXCANC = 0"; // OK
									$resOUTPAY_NW2	= $this->db->query($sqlOUTPAY_NW2)->result();							
									foreach($resOUTPAY_NW2 as $rowOUTPAY_NW2) :
										$OUTPAY_NW2 	= $rowOUTPAY_NW2->OUTPAY_NW2;
										$OUTPAYPPN_NW2	= $rowOUTPAY_NW2->OUTPAYPPN_NW2;
									endforeach;
									$TOT_OUTPAY_NW2	= round(($OUTPAY_NW2 + $OUTPAYPPN_NW2) / 1000000);
									
							// F. NEXT WEEK + 3
									$OUTPAY_NW3		= 0;
									$OUTPAYPPN_NW3	= 0;
									$sqlOUTPAY_NW3 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW3, SUM(B.PPN) AS OUTPAYPPN_NW3
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE BETWEEN '$startDate3' AND '$endDate3'
															AND A.TRXCANC = 0";															
									$resOUTPAY_NW3	= $this->db->query($sqlOUTPAY_NW3)->result();							
									foreach($resOUTPAY_NW3 as $rowOUTPAY_NW3) :
										$OUTPAY_NW3 	= $rowOUTPAY_NW3->OUTPAY_NW3;
										$OUTPAYPPN_NW3	= $rowOUTPAY_NW3->OUTPAYPPN_NW3;
									endforeach;
									$TOT_OUTPAY_NW3	= round(($OUTPAY_NW3 + $OUTPAYPPN_NW3) / 1000000);
									
							// F. NEXT WEEK + 4
									$OUTPAY_NW4		= 0;
									$OUTPAYPPN_NW4	= 0;
									$sqlOUTPAY_NW4 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW4, SUM(B.PPN) AS OUTPAYPPN_NW4
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE BETWEEN '$startDate4' AND '$endDate4'
															AND A.TRXCANC = 0"; // OK															
									$resOUTPAY_NW4	= $this->db->query($sqlOUTPAY_NW4)->result();							
									foreach($resOUTPAY_NW4 as $rowOUTPAY_NW4) :
										$OUTPAY_NW4 	= $rowOUTPAY_NW4->OUTPAY_NW4;
										$OUTPAYPPN_NW4	= $rowOUTPAY_NW4->OUTPAYPPN_NW4;
									endforeach;
									$TOT_OUTPAY_NW4	= round(($OUTPAY_NW4 + $OUTPAYPPN_NW4) / 1000000);
									
							// F. NEXT WEEK + 5
									$OUTPAY_NW5		= 0;
									$OUTPAYPPN_NW5	= 0;
									$sqlOUTPAY_NW5 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW5, SUM(B.PPN) AS OUTPAYPPN_NW5
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE BETWEEN '$startDate5' AND '$endDate5'
															AND A.TRXCANC = 0"; // OK															
									$resOUTPAY_NW5	= $this->db->query($sqlOUTPAY_NW5)->result();							
									foreach($resOUTPAY_NW5 as $rowOUTPAY_NW5) :
										$OUTPAY_NW5 	= $rowOUTPAY_NW5->OUTPAY_NW5;
										$OUTPAYPPN_NW5	= $rowOUTPAY_NW5->OUTPAYPPN_NW5;
									endforeach;
									$TOT_OUTPAY_NW5	= round(($OUTPAY_NW5 + $OUTPAYPPN_NW5) / 1000000);
									
							// F. NEXT WEEK + 6
									$OUTPAY_NW6		= 0;
									$OUTPAYPPN_NW6	= 0;
									$sqlOUTPAY_NW6 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW6, SUM(B.PPN) AS OUTPAYPPN_NW6
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE BETWEEN '$startDate6' AND '$endDate6'
															AND A.TRXCANC = 0"; // OK															
									$resOUTPAY_NW6	= $this->db->query($sqlOUTPAY_NW6)->result();							
									foreach($resOUTPAY_NW6 as $rowOUTPAY_NW6) :
										$OUTPAY_NW6 	= $rowOUTPAY_NW6->OUTPAY_NW6;
										$OUTPAYPPN_NW6	= $rowOUTPAY_NW6->OUTPAYPPN_NW6;
									endforeach;
									$TOT_OUTPAY_NW6	= round(($OUTPAY_NW6 + $OUTPAYPPN_NW6) / 1000000);
									
							// F. NEXT WEEK + 7
									$OUTPAY_NW7		= 0;
									$OUTPAYPPN_NW7	= 0;
									$sqlOUTPAY_NW7 	= "SELECT SUM(B.TTKCOST) AS OUTPAY_NW7, SUM(B.PPN) AS OUTPAYPPN_NW7
														FROM bs_ttkhd_outp A
															INNER JOIN bs_ttkdt_outp B ON A.TTKCODE = B.TTKCODE
														WHERE B.PRJCODE = '$PRJCODE'
															AND A.DUEDATE BETWEEN '$startDate7' AND '$endDate7'
															AND A.TRXCANC = 0"; // OK															
									$resOUTPAY_NW7	= $this->db->query($sqlOUTPAY_NW7)->result();							
									foreach($resOUTPAY_NW7 as $rowOUTPAY_NW7) :
										$OUTPAY_NW7 	= $rowOUTPAY_NW7->OUTPAY_NW7;
										$OUTPAYPPN_NW7	= $rowOUTPAY_NW7->OUTPAYPPN_NW7;
									endforeach;
									$TOT_OUTPAY_NW7	= round(($OUTPAY_NW7 + $OUTPAYPPN_NW7) / 1000000);
									
									$GT_PREV_BALANCE	= $GT_PREV_BALANCE + $PREV_BALANCE;
									$GT_PREV_OUTPAY		= $GT_PREV_OUTPAY + $PREV_OUTPAY;
									$GT_CASHIN_W		= $GT_CASHIN_W + $CASHIN_W;
									$GT_TOT_OUTPAY_W	= $GT_TOT_OUTPAY_W + $TOT_OUTPAY_W;
									$GT_PAYM_W			= $GT_PAYM_W + $PAYM_W;
									$GT_PAYM_WPPh		= $GT_PAYM_WPPh + $PAYM_WPPh;
									$GT_OVH_W			= $GT_OVH_W + $OVH_W2;
									$GT_SALDO_W			= $GT_SALDO_W + $SALDO_W;
									$GT_NOW_BALANCE		= $GT_NOW_BALANCE + $NOW_BALANCE;
									$GT_TOT_OUTPAY_NOW	= $GT_TOT_OUTPAY_NOW + $NOW_OUTPAY;
									$GT_TOT_AKUM_OVH	= $GT_TOT_AKUM_OVH + $NOW_AKUM_OVH;
									$GT_TOT_OUTPAY_NW0	= $GT_TOT_OUTPAY_NW0 + $TOT_OUTPAY_NW0;
									$GT_TOT_OUTPAY_NW1	= $GT_TOT_OUTPAY_NW1 + $TOT_OUTPAY_NW1;
									$GT_TOT_OUTPAY_NW2	= $GT_TOT_OUTPAY_NW2 + $TOT_OUTPAY_NW2;
									$GT_TOT_OUTPAY_NW3	= $GT_TOT_OUTPAY_NW3 + $TOT_OUTPAY_NW3;
									$GT_TOT_OUTPAY_NW4	= $GT_TOT_OUTPAY_NW4 + $TOT_OUTPAY_NW4;
									$GT_TOT_OUTPAY_NW5	= $GT_TOT_OUTPAY_NW5 + $TOT_OUTPAY_NW5;
									$GT_TOT_OUTPAY_NW6	= $GT_TOT_OUTPAY_NW6 + $TOT_OUTPAY_NW6;
									$GT_TOT_OUTPAY_NW7	= $GT_TOT_OUTPAY_NW7 + $TOT_OUTPAY_NW7;
							?>
								<tr>
									<td nowrap style="text-align:left;"><?php echo $PRJCODE; ?></td>
									<td nowrap style="text-align:left;"><?php echo $PRJNAME; ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($PREV_BALANCE, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($PREV_OUTPAY, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($CASHIN_W, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_W, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($PAYM_W, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($PAYM_WPPh, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($OVH_W2, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($NOW_BALANCE, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($NOW_OUTPAY, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($NOW_AKUM_OVH, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW0, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW1, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW2, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW3, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW4, 0); ?></td>
								    <td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW5, 0); ?></td>
								    <td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW6, 0); ?></td>
								    <td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTPAY_NW7, 0); ?></td>
								</tr>
                        <?php
					endforeach;
                }
				?>
                
                <tr style="text-align:left; font-weight:bold; font-size:14px; background:#CCCCCC">
                    <td colspan="2" nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_PREV_BALANCE, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_PREV_OUTPAY, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_CASHIN_W, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_W, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_PAYM_W, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_PAYM_WPPh, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_OVH_W, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_NOW_BALANCE, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NOW, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_AKUM_OVH, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW0, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW1, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW2, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW3, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW4, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW5, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW6, 0); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GT_TOT_OUTPAY_NW7, 0); ?></td>
                </tr>
      </table>   	  </td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="0">
			<?php					
				/*echo "Before = $startDate - $endDate<br>";			// (39 - 40) // View on Report weekly
				echo "Current = $startDate1 - $endDate1<br>";		// (40 - 41) // Next + 1
				echo "Next + 1 = $startDate2 - $endDate2<br>";		// (41 - 42) // Next + 2
				echo "Next + 2 = $startDate3 - $endDate3<br>";		// (42 - 43) // Next + 3
				echo "Next + 3 = $startDate4 - $endDate4<br>";		// (43 - 44) // Next + 4
				echo "THISWEEK ($THISWEEK - $THISWEEKV) => Before = $startDate - $endDate<br>";				// (39 - 40)
				echo "NEXTWEEK1 ($NEXTWEEK1 - $NEXTWEEK1V)  => Current = $startDate1 - $endDate1<br>";		// (40 - 41) // Current
				echo "NEXTWEEK2 ($NEXTWEEK2 - $NEXTWEEK2V)  => Next + 1 = $startDate2 - $endDate2<br>";		// (41 - 42)
				echo "NEXTWEEK3 ($NEXTWEEK3 - $NEXTWEEK3V)  => Next + 2 = $startDate3 - $endDate3<br>";		// (42 - 43)
				echo "NEXTWEEK4 ($NEXTWEEK4 - $NEXTWEEK4V)  => Next + 3 = $startDate4 - $endDate4<br>";		// (43 - 44)*/
				
				$startDate1v = date('d/m/Y',strtotime($startDate1));
				$endDate1v 	= date('d/m/Y',strtotime($endDate1));
				$startDate2v = date('d/m/Y',strtotime($startDate2));
				$endDate2v 	= date('d/m/Y',strtotime($endDate2));
				$startDate3v = date('d/m/Y',strtotime($startDate3));
				$endDate3v 	= date('d/m/Y',strtotime($endDate3));
				$startDate4v = date('d/m/Y',strtotime($startDate4));
				$endDate4v 	= date('d/m/Y',strtotime($endDate4));
				$startDate5v = date('d/m/Y',strtotime($startDate5));
				$endDate5v 	= date('d/m/Y',strtotime($endDate5));
				$startDate6v = date('d/m/Y',strtotime($startDate6));
				$endDate6v 	= date('d/m/Y',strtotime($endDate6));
				$startDate7v = date('d/m/Y',strtotime($startDate7));
				$endDate7v 	= date('d/m/Y',strtotime($endDate7));
            ?>
                <tr>
                    <td width="9%" nowrap style="text-align:left;">Information:</td>
                    <td width="9%" nowrap style="text-align:left;">Minggu ke - <?php echo $THISWEEKV; ?></td>
                    <td width="82%" nowrap style="text-align:left;">: <?php echo "$startDatev - $endDatev"; ?></td>
                </tr>
                <tr>
                    <td nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:left;">Minggu ke - <?php echo $NEXTWEEK1V; ?></td>
                    <td nowrap style="text-align:left;">: <?php echo "$startDate1v - $endDate1v"; ?></td>
                </tr>
                <tr>
                    <td nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:left;">Minggu ke - <?php echo $NEXTWEEK2V; ?></td>
                    <td nowrap style="text-align:left;">: <?php echo "$startDate2v - $endDate2v"; ?></td>
                </tr>
                <tr>
                    <td nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:left;">Minggu ke - <?php echo $NEXTWEEK3V; ?></td>
                    <td nowrap style="text-align:left;">: <?php echo "$startDate3v - $endDate3v"; ?></td>
                </tr>
                <tr>
                    <td nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:left;">Minggu ke - <?php echo $NEXTWEEK4V; ?></td>
                    <td nowrap style="text-align:left;">: <?php echo "$startDate4v - $endDate4v"; ?></td>
                </tr>
                <tr>
                  <td nowrap style="text-align:left;">&nbsp;</td>
                  <td nowrap style="text-align:left;">Minggu ke - <?php echo $NEXTWEEK5V; ?></td>
                  <td nowrap style="text-align:left;">: <?php echo "$startDate5v - $endDate5v"; ?></td>
                </tr>
                <tr>
                  <td nowrap style="text-align:left;">&nbsp;</td>
                  <td nowrap style="text-align:left;">Minggu ke - <?php echo $NEXTWEEK6V; ?></td>
                  <td nowrap style="text-align:left;">: <?php echo "$startDate6v - $endDate6v"; ?></td>
                </tr>
                <tr>
                  <td nowrap style="text-align:left;">&nbsp;</td>
                  <td nowrap style="text-align:left;">Minggu ke - <?php echo $NEXTWEEK7V; ?></td>
                  <td nowrap style="text-align:left;">: <?php echo "$startDate7v - $endDate7v"; ?></td>
                </tr>
      </table>      	</td>
    </tr>
</table>
</section>
</body>
</html>