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

$PRJCODE 	= $PRJCODECOL;
$PRJNAME 	= '';
$PRJCNUM 	= '';
$PRJDATE_CO	= date('Y-m-d');
$sql 		= "SELECT PRJCODE, PRJNAME, PRJCNUM, PRJDATE, PRJCOST, PRJCOST2
					FROM tbl_project 
				WHERE PRJCODE = $PRJCODECOL";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJCODE 	= $row->PRJCODE;
	$PRJNAME 	= $row->PRJNAME;
	$PRJCNUM 	= $row->PRJCNUM;
	$PRJDATE_CO	= date('d M Y', strtotime($row->PRJDATE));
	$PRJCOST 	= $row->PRJCOST;
	$PRJCOST2 	= $row->PRJCOST2;
endforeach;
	
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

//$dateSel	= getStartAndEndDate($WEEKTO, $year);
//$startDate	= $dateSel[0];
//$endDate	= $dateSel[1];

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
<body style="overflow:auto">
<section class="content">
    <table width="100%" border="1px solid" style="size:auto">
    <tr>
        <td><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
      </tr>
        <?php			
			date_default_timezone_set("Asia/Jakarta");
			$currentD	= date('d/m/Y H:i:s')
        ?>
    <tr>
        <td class="style2" style="text-align:left;">
        	<table width="100%">
                <tr style="text-align:left;">
                  <td colspan="6" style="text-align:center; font-size:18px; font-weight:bold">REKAPITULASI MONTHLY CERTIFICATE</td>
                </tr>
                <tr style="text-align:left; font-weight:bold">
                    <td width="21%" valign="top" nowrap>
                        PAKET KONTRAK<br>
                        NOMOR KONTRAK<br>
                        NOMOR KONTRAK ADD...<br>
                        TANGGAL KONTRAK<br>
                        TANGGAL KONTRAK ADD...<br>
                        NILAI KONTRAK<br>
                        NILAI KONTRAK ADD...<br>
                        SUMBER DANA<br>
                        KONTRKTOR PELAKSANA<br>
                        KONSULTAN SUPERVISI
                    </td>
                    <td width="1%">
                    	:<br>
                    	:<br>
                    	:<br>
                    	:<br>
                    	:<br>
                    	:<br>
                    	:<br>
                    	:<br>
                    	:<br>
                    	:<br>
                    </td>
                    <?php $DEFAULTVIEW = ''; ?>
                    <td width="46%">
                    	<?php echo "$PRJCODE - $PRJNAME"; ?> <br>
                    	<?php echo $PRJCNUM; ?><br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    	<?php echo $PRJDATE_CO; ?><br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    	<?php echo number_format($PRJCOST, 2); ?><br>
                    	<?php echo number_format($PRJCOST2, 2); ?><br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    </td>
                    <td width="16%" style="text-align:left" nowrap>
                    	<br>
                    	<br>
                    	<br>
                    	NILAI KONTRAK<br>
                    	PPN 10%<br>
                    	TOTAL CONTRACT AMOUNT<br>
                    	<br>
                    	NILAI KONTRAK ADD...<br>
                    	PPN 10%<br>
                        TOTAL CONTRACT AMOUNT<br>
                    </td>
                    <td width="1%" style="text-align:right" nowrap>
                    	<br>
                    	<br>
                    	<br>
                    	: Rp<br>
                    	: Rp<br>
                    	: Rp<br>
                    	<br>
                    	: Rp<br>
                    	: Rp<br>
                    	: Rp<br>
                    </td>
                    <?php
						$PRJCOSTPPN	= $PRJCOST * 0.1;
						$PRJCOSTTOT	= $PRJCOST + $PRJCOSTPPN;
					?>
                    <td width="15%" style="text-align:right">
                    	<br>
                    	<br>
                    	<br>
                    	<?php echo number_format($PRJCOST, 2); ?><br>
                    	<?php echo number_format($PRJCOSTPPN, 2); ?><br>
                    	<?php echo number_format($PRJCOSTTOT, 2); ?><br>
                    	<br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    	<?php echo $DEFAULTVIEW; ?><br>
                    </td>
                </tr>
            </table>        </td>
    </tr>
    <tr>
        <td class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td rowspan="2" nowrap style="text-align:center; font-weight:bold">MC NO.</td>
                    <td colspan="2" nowrap style="text-align:center; font-weight:bold">REALISASI BULAN INI</td>
                    <td colspan="3" nowrap style="text-align:center; font-weight:bold">STATUS RETENSI</td>
                    <td colspan="3" style="text-align:center; font-weight:bold">STATUS UANG MUKA (%)</td>
                    <td rowspan="2" style="text-align:center; font-weight:bold">TOTAL POT.</td>
                    <td colspan="3" style="text-align:center; font-weight:bold">TOTAL PEMBAYARAN</td>
                </tr>
                <tr style="background:#CCCCCC">
                  <td nowrap style="text-align:center; font-weight:bold">%</td>
                  <td nowrap style="text-align:center; font-weight:bold">(RP)</td>
                  <td nowrap style="text-align:center; font-weight:bold">NILAI RETENSI</td>
                  <td nowrap style="text-align:center; font-weight:bold">POT RETENSI BLN INI</td>
                  <td nowrap style="text-align:center; font-weight:bold">SALDO</td>
                  <td nowrap style="text-align:center; font-weight:bold">NILAI UANG MUKA</td>
                  <td nowrap style="text-align:center; font-weight:bold">POT UM BULAN INI</td>
                  <td nowrap style="text-align:center; font-weight:bold">SALDO</td>
                  <td nowrap style="text-align:center; font-weight:bold">BULAN INI</td>
                  <td nowrap style="text-align:center; font-weight:bold">PPN 10%</td>
                  <td nowrap style="text-align:center; font-weight:bold">TERMASUK PPN</td>
                </tr>

                <tr style="background:#CCCCCC">
                    <td width="12%" nowrap style="text-align:center; font-weight:bold">A</td>
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">B</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">C</td>
                	<td width="8%" nowrap style="text-align:center; font-weight:bold">D</td>
                    <td width="12%" nowrap style="text-align:center; font-weight:bold">E</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">F</td>
                    <td width="10%" nowrap style="text-align:center; font-weight:bold">G</td>
                    <td width="10%" style="text-align:center; font-weight:bold">H</td>
                    <td width="4%" style="text-align:center; font-weight:bold">I</td>
                    <td width="4%" style="text-align:center; font-weight:bold">J</td>
                    <td width="6%" style="text-align:center; font-weight:bold">K</td>
                    <td width="4%" style="text-align:center; font-weight:bold">L</td>
                    <td width="9%" nowrap style="text-align:center; font-weight:bold">M</td>
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
					
					$maxRow	= 20;
					$CFType = 1;
					$noU	= 0;
					if($CFType == 1)	// SUMMARY
					{
						
						$MC_MANNO		= '';
						$MC_PROG		= 0;
						$MC_PROGVAL		= 0;
						$MC_RETVAL		= 0;
						$MC_DPBACK		= 0;
						$TOT_POT		= 0;
						$PINV_PAIDAM	= 0;
						$PINV_PAIDPPN	= 0;
						$PINV_PAIDTOT	= 0;
                                
						$sqlMC 		= "SELECT * FROM tbl_mcheader WHERE PRJCODE = $PRJCODECOL AND MC_STAT = 2";
						$resMC 		= $this->db->query($sqlMC)->result();
						foreach($resMC as $rowMC) :
							$MC_CODE 		= $rowMC->MC_CODE;
							$MC_MANNO 		= $rowMC->MC_MANNO;
							$MC_STEP 		= $rowMC->MC_STEP;
							$PRJCODE 		= $rowMC->PRJCODE;
							$MC_OWNER 		= $rowMC->MC_OWNER;
							$MC_DATE 		= $rowMC->MC_DATE;
							$MC_ENDDATE		= $rowMC->MC_ENDDATE;
							$MC_RETVAL 		= $rowMC->MC_RETVAL;
							$MC_PROG 		= $rowMC->MC_PROG;
							$MC_PROGVAL 	= $rowMC->MC_PROGVAL;
							$MC_VALADD 		= $rowMC->MC_VALADD;
							$MC_DPPER 		= $rowMC->MC_DPPER;
							$MC_DPVAL 		= $rowMC->MC_DPVAL;
							$MC_DPBACK 		= $rowMC->MC_DPBACK;
							$MC_RETCUT 		= $rowMC->MC_RETCUT;
							$MC_PROGAPP		= $rowMC->MC_PROGAPP;
							$MC_PROGAPPVAL 	= $rowMC->MC_PROGAPPVAL;
							$MC_AKUMNEXT 	= $rowMC->MC_AKUMNEXT;
							$MC_VALBEF 		= $rowMC->MC_VALBEF;
							$MC_TOTVAL 		= $rowMC->MC_TOTVAL;
							$MC_TOTVAL_PPn 	= $rowMC->MC_TOTVAL_PPn;
							$MC_TOTVAL_PPh 	= $rowMC->MC_TOTVAL_PPh;
							$MC_AKUMNEXT 	= $rowMC->MC_AKUMNEXT;
							$PRJDATE_CO		= date('d M Y', strtotime($row->PRJDATE));
							
							$TOT_POT		= $MC_DPBACK + $MC_RETCUT;
							$PINV_PAIDAM 	= 0;
							
							$GPINV_TOTVAL	= 0;
							$sqlPRJINV 		= "SELECT GPINV_TOTVAL FROM tbl_projinv_header WHERE PINV_SOURCE = 'MC_CODE' AND PINV_STAT = 3";
							$resPRJINV 		= $this->db->query($sqlPRJINV)->result();
							foreach($resPRJINV as $rowPRJINV) :
								$PINV_PAIDAM= $rowPRJINV->GPINV_TOTVAL;
							endforeach;
							
							$PINV_PAIDPPN 	= 0;
							$PINV_PAIDTOT 	= $PINV_PAIDAM + $PINV_PAIDPPN;
						endforeach;
						?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo $MC_MANNO; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_PROG,2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_PROGVAL,2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_RETVAL, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_RETVAL, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_RETVAL, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_DPBACK,2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_DPBACK,2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($MC_DPBACK, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($TOT_POT, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($PINV_PAIDAM,2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($PINV_PAIDPPN,2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($PINV_PAIDTOT,2); ?></td>
                            </tr>
                        <?php
						$remRow = $maxRow - $noU;
					}
					for($i=0;$i<=$maxRow;$i++)
					{
						?>
                            <tr>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                            </tr>
                        <?php
					}
					?>
                
                <tr style="text-align:left; font-weight:bold;">
                    <td colspan="13" nowrap style="text-align:left;">TOTAL S/D BULAN INI</td>
                </tr>
      </table>
      </td>
    </tr>
    <tr>
    	<td>
        	<table border="0" width="100%">
                    	<tr>
                        	<td style="text-align:center">MENGETAHUI:<br><br><br><br><br>........................................</td>
                        	<td style="text-align:center">DISETUJUI:<br><br><br><br><br>........................................</td>
                        	<td style="text-align:center">DIAJUKAN OLEH:<br><br><br><br><br>........................................</td>
                        </tr>
                    </table>
        </td>
    </tr>
</table>
</section>
</body>
</html>