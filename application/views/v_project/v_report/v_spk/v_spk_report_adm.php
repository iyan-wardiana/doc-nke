<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= r_usagereq_report_adm.php
 * Location		= -
*/

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

date_default_timezone_set("Asia/Jakarta");
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJCOST	= 0;
$sqlPRJ		= "SELECT PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJCOST	= $rowPRJ->PRJCOST;
endforeach;

$StartDate	= date('d M Y', strtotime($Start_Date));
$EndDate	= date('d M Y', strtotime($End_Date));
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php //echo $title; ?></title>
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
<section class="content" over>
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="16%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="45%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">LAPORAN SURAT PERINTAH KERJA (SPK) SUMMARY
        </td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:16px">
        <span class="style2" style="text-align:center; font-weight:bold; font-size:16px; display:none">&nbsp;s.d Periode : 
        <?php
				$CRDate		= date_create($End_Date);
				echo date_format($CRDate,"d-m-Y");
				$StartDate	= date('Y-m-d', strtotime($Start_Date));
				$EndDate	= date('Y-m-d', strtotime($End_Date));
			?></span></td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%" style="font-weight:bold">
            	<!--<tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top">Type Dokumen</td>
        			<td width="1%">:</td>
        			<td width="91%"><?php// echo $DOCTYPE1; ?></td>
    			</tr>-->
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$h1_title"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">PERIODE</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$StartDate s.d. $EndDate"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">TANGGAL CETAK</td>
                  <td>:</td>
                  <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                  <th width="2%" height="25" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
                  <th width="11%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000;">Nomor</th>
                  <th width="12%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000;">TGL. MULAI</th>
                  <th width="12%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000;">TGL. SELESAI</th>
                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000;">KODE PEKERJAAN</th>
                  <th width="25%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000;">DESKRIPSI</th>
                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000; display:none">NILAI</th>
                  <th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000;">NILAI TAGIHAN</th>
                  <th width="12%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000;">NILAI DIBAYAR</th>
                  <th width="12%" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">SISA TAGIHAN</th>
              </tr>
                <tr style="line-height:1px; border-left:hidden; border-right:hidden">
               	  <td colspan="9" style="border:none; line-height:2px">&nbsp;</td>
                </tr>
               <?php
			   		$theRow		= 0;
					$TOT_VAL	= 0;
					$TOT_PAID	= 0;
					$TOT_REM	= 0;
				   	$sqlWOC		= "tbl_wo_header A
											INNER JOIN tbl_joblist B ON A.JOBCODEID = B.JOBCODEID
										WHERE A.PRJCODE = '$PRJCODE' AND A.WO_DATE >= '$StartDate' AND A.WO_DATE <= '$EndDate'";
					$resWOC		= $this->db->count_all($sqlWOC);
					if($resWOC > 0)
					{
						$sqlWO		= "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_ENDD, A.WO_NOTE, A.WO_VALUE, B.JOBDESC 
										FROM tbl_wo_header A
											INNER JOIN tbl_joblist B ON A.JOBCODEID = B.JOBCODEID
										WHERE A.PRJCODE = '$PRJCODE' AND A.WO_DATE >= '$StartDate' AND A.WO_DATE <= '$EndDate'";
						$resWO		= $this->db->query($sqlWO)->result();
						foreach($resWO as $rowWO):
							$theRow		= $theRow + 1;
							$WO_NUM		= $rowWO->WO_NUM;
							$WO_CODE	= $rowWO->WO_CODE;
							$WO_DATE1	= $rowWO->WO_DATE;
							$WO_DATE	= date('d M Y', strtotime($WO_DATE1));
							$WO_ENDD1	= $rowWO->WO_ENDD;
							$WO_ENDD	= date('d M Y', strtotime($WO_ENDD1));
							$WO_NOTE	= $rowWO->WO_NOTE;
							$JOBDESC	= $rowWO->JOBDESC;
							$WO_VALUE	= $rowWO->WO_VALUE;
							
							// GET PAID VALUE
							$PAID_WOAMN		= 0;
							$PAID_WOAMN_PPN	= 0;
							$sqlWOPAID	= "SELECT SUM(Amount) AS PAID_WOAMN, SUM(Amount_PPn) AS PAID_WOAMN_PPN 
											FROM tbl_bp_detail 
											WHERE DocumentRef2 = '$WO_NUM'";
							$resWOPAID 	= $this->db->query($sqlWOPAID)->result();
							foreach($resWOPAID as $rowWOPAID) :
								$PAID_WOAMN		= $rowWOPAID->PAID_WOAMN;
								$PAID_WOAMN_PPN	= $rowWOPAID->PAID_WOAMN_PPN;
							endforeach;
							if($PAID_WOAMN == '')
								$PAID_WOAMN = 0;
							if($PAID_WOAMN_PPN == '')
								$PAID_WOAMN_PPN = 0;
							
							$WO_PAID	= $PAID_WOAMN;
							
							$WO_REMVAL	= $WO_VALUE - $WO_PAID;
							
							$TOT_VAL	= $TOT_VAL + $WO_VALUE;
							$TOT_PAID	= $TOT_PAID + $WO_PAID;
						?>
							<tr>
                              	<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000;<?php if($theRow == 1){ ?>border-top-width:2px; border-top-color:#000;<?php }?>"><?php echo $theRow; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $WO_CODE; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $WO_DATE; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $WO_ENDD; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $JOBDESC; ?></td>
                              	<td nowrap style="text-align:left;"><?php echo $WO_NOTE; ?></td>
                                <td nowrap style="text-align:right; display:none"><?php echo number_format($WO_VALUE, 2); ?></td>
                             	<td nowrap style="text-align:right;"><?php echo number_format($WO_VALUE, 2); ?></td>
                              	<td nowrap style="text-align:right;"><?php echo number_format($WO_PAID, 2); ?></td>
                              	<td nowrap style="text-align:right;border-right-width:2px; border-right-color:#000"><?php echo number_format($WO_REMVAL, 2); ?></td>                              
                            </tr>
               			<?php
						endforeach;
						$TOT_REM	= $TOT_VAL - $TOT_PAID;
					}
					else
					{
						?>
                            <tr>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                              	<td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:right; display:none">&nbsp;</td>
                             	<td nowrap style="text-align:right;">&nbsp;</td>
                              	<td nowrap style="text-align:right;">&nbsp;</td>
                              	<td nowrap style="text-align:right;border-right-width:2px; border-right-color:#000">&nbsp;</td>
                            </tr>
						<?php
					}
					?>
               <tr bgcolor="#666666" style="color:#FFF">
                 <td colspan="5" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000"><b>TOTAL</b></td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000">&nbsp;</td>
                 <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000"><b><?php echo number_format($TOT_VAL, 2); ?></b></td>
                 <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-top-width:2px; border-top-color:#000"><b><?php echo number_format($TOT_PAID, 2); ?></b></td>
                 <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-right-width:2px; border-right-color:#000;border-top-width:2px; border-top-color:#000"><b><?php echo number_format($TOT_REM, 2); ?></b></td>
               </tr>
                <tr style="display:none">
                  <td colspan="9" nowrap style="text-align:center;">--- none ---</td>
                </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>