<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 November 2017
 * File Name	= v_claim_monitoring_report_adm.php
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
	$PRJCODECOLL	= "$PRJCODED";
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

$dateSel	= getStartAndEndDate($WEEKTO, $year);
$startDate	= $dateSel[0];
$endDate	= $dateSel[1];
//echo "startDate = $startDate";


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

function endCycle($d1, $months)
{
	$date = new DateTime($d1);

	// call second function to add the months
	$newDate = $date->add(add_months($months, $date));

	// goes back 1 day from date, remove if you want same day of month
	$newDate->sub(new DateInterval('P1D')); 

	//formats final date to Y-m-d form
	$dateReturned = $newDate->format('Y-m-d'); 

	return $dateReturned;
}
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
<body style="overflow:auto">
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="19%">&nbsp;</td>
        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:18px">MONITORING TAGIHAN PROYEK<br>
          <span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php $comp_name 	= $this->session->userdata['comp_name']; echo $comp_name; ?></span></td>
  </tr>
    <tr>
      <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px">&nbsp;</td>
    </tr>
        <?php			
			date_default_timezone_set("Asia/Jakarta");
			$currentD	= date('d/m/Y H:i:s')
        ?>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
        	<table width="100%">
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">Report Week to</td>
                    <td width="1%">:</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">Report Date</td>
                  <td>:</td>
                  <td width="59%">&nbsp;</td>
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
                    <td colspan="4" style="text-align:center; font-weight:bold">Cut Off Date (Selected Month)</td>
                    <td colspan="4" style="text-align:center; font-weight:bold">Invoicing</td>
                    <td colspan="4" nowrap style="text-align:center; font-weight:bold">Realization</td>
                </tr>

                <tr style="background:#CCCCCC">
                    <td width="2%" nowrap style="text-align:center; font-weight:bold"><?php echo $Code; ?></td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Name; ?></td>
                	<td width="5%" nowrap style="text-align:center; font-weight:bold">Progress<br>
                	  (%)</td>
                    <td width="5%" nowrap style="text-align:center; font-weight:bold">Amount<br>Progress</td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">Plan</td>
                    <td width="6%" style="text-align:center; font-weight:bold">Realization</td>
                    <td width="5%" style="text-align:center; font-weight:bold">Deviation</td>
                    <td width="4%" style="text-align:center; font-weight:bold">Amount</td>
                    <td width="3%" style="text-align:center; font-weight:bold">Plan</td>
                    <td width="4%" style="text-align:center; font-weight:bold">Date</td>
                    <td width="5%" nowrap style="text-align:center; font-weight:bold">Deviation</td>
                    <td width="4%" nowrap style="text-align:center; font-weight:bold">Amount</td>
                    <td width="4%" nowrap style="text-align:center; font-weight:bold">Plan</td>
                    <td width="5%" nowrap style="text-align:center; font-weight:bold"> Date</td>
                    <td width="5%" nowrap style="text-align:center; font-weight:bold">Deviation</td>
                    <td width="5%" nowrap style="text-align:center; font-weight:bold">Amount</td>
              </tr>
                <?php
					function selisih_tanggal($dateline, $kembali)
					{
						$start = strtotime('2017-07-12 10:05:25');
						$end   = strtotime('2017-07-13 11:07:33');
						$diff  = $end - $start;
						
						$hours = floor($diff / (60 * 60));
						$minutes = $diff - $hours * (60 * 60);
						echo 'Selisih Waktu: ' . $hours .  ' Jam, ' . floor( $minutes / 60 ) . ' Menit';
					}
						
                if($CFType == 1)	// SUMMARY
                {
					// PROJECT
						$PRJNAME	= 'none';
						$sqlPRJ 	= "SELECT PRJCODE, PRJNAME, PRJDATE_CO, PRJDATE_IC, PRJDATE_HC 
										FROM tbl_project WHERE PRJCODE IN ('$PRJCODECOLL') ORDER BY PRJCODE";
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
							$PRJDATE_CO	= $rowPRJ->PRJDATE_CO;	// BATAS PEMBUATAN MC
							$PRJDATE_IC	= $rowPRJ->PRJDATE_IC;	// LAMA DARI MC PENGAJUAN KE MC APPROVE
							$PRJDATE_HC	= $rowPRJ->PRJDATE_HC;	// BATAS PENCAIRAN DARI INV
						
							// A. BEGINNING BALANCE
								$MCB_LASTEP		= 0;
								$MCB_PERCENT	= 0;
								$MCB_AMOUNT		= 0;
								$MCB_AMOUNT2	= 0;
								$MCB_DATE		= '';
								$sqlMCBPREV 	= "SELECT MCB_PRJCODE, MCB_CONTRACT, MCB_LASTEP, MCB_DATE, MCB_YEAR, MCB_PERCENT, 
														MCB_AMOUNT, MCB_AMOUNT2
													FROM tbl_mc_balance WHERE MCB_PRJCODE = '$PRJCODE'";
								$resMCBPREV 	= $this->db->query($sqlMCBPREV)->result();
								foreach($resMCBPREV as $rowMCBPREV):
									$MCB_PRJCODE	= $rowMCBPREV->MCB_PRJCODE;
									$MCB_CONTRACT	= $rowMCBPREV->MCB_CONTRACT;
									$MCB_LASTEP		= $rowMCBPREV->MCB_LASTEP;
									$MCB_DATE		= $rowMCBPREV->MCB_DATE;		// LAST MC
									$MCB_PERCENT	= $rowMCBPREV->MCB_PERCENT;
									$MCB_AMOUNT		= $rowMCBPREV->MCB_AMOUNT;
									$MCB_AMOUNT2	= $rowMCBPREV->MCB_AMOUNT2;
								endforeach;
								$MCB_NEXTSTEP		= $MCB_LASTEP + 1;
								$LAST_MCDATE		= '';
								
								
								// SETTING MUST MC CREATED
								$MUST_MCCREATED1	= strtotime ('+1 month' , strtotime ($MCB_DATE));
								//$MUST_MCCREATED1 	= endCycle($startDate, $nMonths);
								$MUST_MCCREATED		= date('Y-m-d', $MUST_MCCREATED1);
								$CURR_DATE			= date('Y-m-d');
								//echo "MUST_MCCREATED= $MUST_MCCREATED1";
								
							// B. CARI MC UNTUK BULAN SELANJUTNYA
								$sqlMCNEXTC	= "tbl_mc_plan WHERE MCP_PRJCODE = '$PRJCODE' AND MCR_DATE > '$LAST_MCDATE'";
								$resMCNEXTC	= $this->db->count_all($sqlMCNEXTC);
								if($resMCNEXTC == 0)
								{
									// KETERLAMBATAN MC DARI $MUST_MCCREATED
									$MCP_DATE			= $CURR_DATE;
									$start 				= strtotime($MUST_MCCREATED);
									$end   				= strtotime($CURR_DATE);
									$diff  				= $end - $start;
										$COUNT_LATEMC	= $diff;
										$hours 			= floor($COUNT_LATEMC / (60 * 60));
									$daysMC				= $hours / 24;
									$MCP_AMOUNT			= 0;
									$MCR_MCCODE			= '';
									$MCR_DATE			= date('Y-m-d');
									$MCR_AMOUNT			= 0;
									
									// SETTING MUST INV CREATED
									$MUST_INVCREATED1	= strtotime ('+14 days' , strtotime ($MCR_DATE));
									$MUST_INVCREATED	= date('Y-m-d', $MUST_INVCREATED1);
								}
								else
								{
									$MCP_DATE			= date('Y-m-d');
									$MCP_AMOUNT			= 0;
									$MCR_MCCODE			= '';
									$MCR_DATE			= date('Y-m-d');
									$MCR_AMOUNT			= 0;
									$sqlMCNEXT 	= "SELECT MCP_DATE, MCP_AMOUNT,
													MCR_MCCODE, MCR_DATE, MCR_AMOUNT FROM tbl_mc_plan 
													WHERE MCP_PRJCODE = '$PRJCODE' AND MCR_DATE > '$LAST_MCDATE'";
									$resMCNEXT 	= $this->db->query($sqlMCNEXT)->result();
									foreach($resMCNEXT as $rowMCNEXT):
										$MCP_DATE		= $rowMCNEXT->MCP_DATE;
										$MCP_AMOUNT		= $rowMCNEXT->MCP_AMOUNT;
										$MCR_MCCODE		= $rowMCNEXT->MCR_MCCODE;
										$MCR_DATE		= $rowMCNEXT->MCR_DATE;
										$MCR_AMOUNT		= $rowMCNEXT->MCR_AMOUNT;
									endforeach;
									// KETERLAMBATAN MC DARI $MUST_MCCREATED
									$start 				= strtotime($MUST_MCCREATED);
									$end   				= strtotime($MCR_DATE);
									
									$diff  				= $end - $start;
										$COUNT_LATEMC	= $diff;
										$hours 			= floor($COUNT_LATEMC / (60 * 60));
									$daysMC				= $hours / 24;
									
									// SETTING MUST INV CREATED
									$MUST_INVCREATED1	= strtotime ('+14 days' , strtotime ($MCR_DATE));
									$MUST_INVCREATED	= date('Y-m-d', $MUST_INVCREATED1);
								}
								
							// C. CARI INVOICE UNTUK MC TERSEBUT
								$sqlMCINVC		= "tbl_projinv_header WHERE PINV_SOURCE = '$MCR_MCCODE' AND PRJCODE = '$PRJCODE'";
								$resMCINVC		= $this->db->count_all($sqlMCINVC);								
								if($resMCINVC == 0)
								{
									// KETERLAMBATAN MC DARI $MUST_MCCREATED
									$PINV_CODE			= '';
									$PINV_DATE			= $CURR_DATE;
									$PINV_TOTVAL		= 0;
									
									$start 				= strtotime($MUST_INVCREATED);
									$end  				= strtotime($CURR_DATE);
									$diff  				= $end - $start;
										$COUNT_LATEMC	= $diff;
										$hours 			= floor($COUNT_LATEMC / (60 * 60));
									$daysINV			= $hours / 24;
									
									// SETTING MUST INV REALIZATION CREATED
									$MUST_RINVCREATED1	= strtotime ('+14 days' , strtotime ($MUST_INVCREATED));
									$MUST_RINVCREATED	= date('Y-m-d', $MUST_RINVCREATED1);
								}
								else
								{
									$PINV_CODE		= '';
									$PINV_DATE		= date('Y-m-d');
									$PINV_TOTVAL	= 0;
									$sqlMCINV 		= "SELECT PINV_CODE, PINV_DATE, PINV_TOTVAL 
														FROM tbl_projinv_header WHERE PINV_SOURCE = '$MCR_MCCODE' AND PRJCODE = '$PRJCODE'";
									$resMCINV 		= $this->db->query($sqlMCINV)->result();
									foreach($resMCINV as $rowMCINV):
										$PINV_CODE		= $rowMCINV->PINV_CODE;
										$PINV_DATE		= $rowMCINV->PINV_DATE;
										$PINV_TOTVAL	= $rowMCINV->PINV_TOTVAL;
									endforeach;
									
									// KETERLAMBATAN MC DARI $MUST_MCCREATED
									$start 				= strtotime($MUST_INVCREATED);
									$end  				= strtotime($CURR_DATE);
									$diff  				= $end - $start;
										$COUNT_LATEMC	= $diff;
										$hours 			= floor($COUNT_LATEMC / (60 * 60));
									$daysINV			= $hours / 24;
									
									// SETTING MUST INV REALIZATION CREATED
									$MUST_RINVCREATED1	= strtotime ('+14 days' , strtotime ($MUST_INVCREATED));
									$MUST_RINVCREATED	= date('Y-m-d', $MUST_RINVCREATED1);
								}
								
							// D. CARI REALISASI INVOICE UNTUK INV TERSEBUT
								$sqlMCRINVC		= "tbl_projinv_realh WHERE PINV_Number = '$PINV_CODE' AND PRJCODE = '$PRJCODE'";
								$resMCRINVC		= $this->db->count_all($sqlMCRINVC);
								if($resMCRINVC == 0)
								{
									// KETERLAMBATAN REAL INV DARI $MUST_INVCREATED
									$PRINV_CODE			= '';
									$PRINV_DATE			= $CURR_DATE;
									$PRINV_TOTVAL		= 0;
									
									$start 				= strtotime($MUST_RINVCREATED);
									$end   				= strtotime($CURR_DATE);
									$diff  				= $end - $start;
										$COUNT_LATEMC	= $diff;
										$hours 			= floor($COUNT_LATEMC / (60 * 60));
									$daysRINV			= $hours / 24;
								}
								else
								{
									$PRINV_CODE			= '';
									$PRINV_DATE			= $CURR_DATE;
									$PRINV_TOTVAL		= 0;
									
									$sqlMCRINV 			= "SELECT PRINV_Number, PRINV_Date, RealINVAmount 
															FROM tbl_projinv_realh WHERE PINV_SOURCE = '$MC_CODE' AND PRJCODE = '$MC_CODE'";
									$resMCRINV 			= $this->db->query($sqlMCRINV)->result();
									foreach($resMCRINV as $rowMCRINV):
										$PRINV_CODE		= $rowMCRINV->PRINV_Number;
										$PRINV_DATE		= $rowMCRINV->PRINV_Date;
										$PRINV_TOTVAL	= $rowMCRINV->RealINVAmount;
									endforeach;
									
									// KETERLAMBATAN RINV DARI $MUST_MCCREATED
									$start 				= strtotime($MUST_RINVCREATED);
									$end   				= strtotime($PRINV_DATE);
									$diff  				= $end - $start;
										$COUNT_LATEMC	= $diff;
										$hours 			= floor($COUNT_LATEMC / (60 * 60));
									$daysRINV	= $hours / 24;
								}
								
								if($MCR_AMOUNT == 0)
								{
									$MC_DATE	= '-';
								}
								
								if($PINV_TOTVAL == 0)
								{
									$PINV_DATE	= '-';
								}
								
								if($PRINV_TOTVAL == 0)
								{
									$PRINV_DATE	= '-';
								}
							?>
								<tr>
									<td nowrap style="text-align:left;"><?php echo $PRJCODE; ?></td>
									<td nowrap style="text-align:left;"><?php echo $PRJNAME; ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($MCB_PERCENT, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($MCB_AMOUNT2, 0); ?></td>
									<td nowrap style="text-align:center;"><?php echo $MUST_MCCREATED; ?></td>
									<td nowrap style="text-align:center;"><?php echo $MCR_DATE; ?></td>
									<td nowrap style="text-align:center;"><?php echo number_format($daysMC, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($MCR_AMOUNT, 0); ?></td>
									<td nowrap style="text-align:center;"><?php echo $MUST_INVCREATED; ?></td>
									<td nowrap style="text-align:center;"><?php echo $PINV_DATE; ?></td>
									<td nowrap style="text-align:center;"><?php echo number_format($daysINV, 0); ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($PINV_TOTVAL, 0); ?></td>
									<td nowrap style="text-align:center;"><?php echo $MUST_RINVCREATED; ?></td>
									<td nowrap style="text-align:center;"><?php echo $PRINV_DATE; ?></td>
									<td nowrap style="text-align:center;"><?php echo number_format($daysRINV, 0); ?></td> 
									<td nowrap style="text-align:right;"><?php echo number_format($PRINV_TOTVAL, 0); ?></td>
								</tr>
                        <?php
					endforeach;
                }
				?>
                
                <tr style="text-align:left; font-weight:bold; font-size:14px; background:#CCCCCC">
                    <td colspan="2" nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td colspan="2" nowrap style="text-align:right;">&nbsp;</td>
                    <td colspan="4" nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                </tr>
      </table>   	  </td>
    </tr>
</table>
</section>
</body>
</html>