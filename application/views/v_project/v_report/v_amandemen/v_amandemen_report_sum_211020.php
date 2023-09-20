<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2019
 * File Name	= v_amandemen_report_sum.php
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

$PRJNAME	= '';
$PRJCOST	= 0;
$PRJDATE	= date('Y/m/d');
$PRJDATE_CO	= date('Y/m/d');
$sqlPRJ		= "SELECT PRJDATE, PRJNAME, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJNAME	= $rowPRJ->PRJNAME;
	$PRJCOST	= $rowPRJ->PRJCOST;
	$PRJDATE	= date('Y/m/d', strtotime($rowPRJ->PRJDATE));
	$PRJDATE_CO	= date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $title; ?></title>
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px"><?php /*?>LAPORAN AMANDEMEN/PERUBAHAN BUDGET<?php */?>
        </td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:16px"><span class="style2" style="text-align:center; font-weight:bold; font-size:16px"><?php /*?>&nbsp;Periode : <?php */?>
        <?php 
				/*$CRDate	= date_create($End_Date);
				echo date_format($CRDate,"d-m-Y");*/
				$StartDate	= date('d M Y', strtotime($Start_Date));
				$EndDate	= date('d M Y', strtotime($End_Date));
				//echo "$StartDate s.d. $EndDate";
			?></span></td>
    </tr>
    <tr>
        <td colspan="2" class="style2" style="text-align:center; font-style:italic"><span class="style2" style="text-align:center; font-weight:bold; font-size:16px"><?php /*?>Summary<?php */?></span></td>
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
                  <th width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
                  <th width="8%" rowspan="2" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000; font-weight:bold; border-top-width:2px; border-top-color:#000;">KODE ITEM</th>
                  <th width="9%" rowspan="2" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000; font-weight:bold; border-top-width:2px; border-top-color:#000;">NAMA ITEM</th>
                  <th width="16%" rowspan="2" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000; font-weight:bold; border-top-width:2px; border-top-color:#000;">KETERANGAN</th>
                  <th width="8%" rowspan="2" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000; font-weight:bold; border-top-width:2px; border-top-color:#000;">SATUAN</th>
                  <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">BUDGET AWAL</th>
                  <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">AMANDEMEN</th>
                  <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">BUDGET AKHIR</th>
              </tr>
                <tr style="background:#CCCCCC">
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">VOL.</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">HARGA</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">VOL.</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">HARGA</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">VOL.</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">HARGA</th>
                  <th width="16%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-right-width:2px; border-right-color:#000">JUMLAH</th>
                </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td colspan="14" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
               </tr>
               <?php
			   		$theRow			= 0;
					$TAMD_AMOUNT	= 0;
				   	$sqlAMC			= "tbl_item A
											INNER JOIN tbl_amd_detail B ON B.ITM_CODE = A.ITM_CODE
												AND B.PRJCODE = '$PRJCODE'
											INNER JOIN tbl_amd_header C ON C.AMD_NUM = B.AMD_NUM
												AND C.PRJCODE = '$PRJCODE'
										WHERE A.PRJCODE = '$PRJCODE' AND C.AMD_STAT = 3
											AND (C.AMD_DATE BETWEEN '$Start_Date' AND '$End_Date')";
					$resAMC			= $this->db->count_all($sqlAMC);
					if($resAMC > 0)
					{
						$sqlAM		= "SELECT A.PRJCODE, A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, 
											A.ITM_LASTP, A.ITM_TOTALP,
											SUM(B.AMD_VOLM) AS ADDVOLM, SUM(B.AMD_TOTAL) AS ADDCOST, A.ADDMVOLM, A.ADDMCOST
										FROM tbl_item A
											INNER JOIN tbl_amd_detail B ON B.ITM_CODE = A.ITM_CODE
												AND B.PRJCODE = '$PRJCODE'
											INNER JOIN tbl_amd_header C ON C.AMD_NUM = B.AMD_NUM
												AND C.PRJCODE = '$PRJCODE'
										WHERE A.PRJCODE = '$PRJCODE' AND C.AMD_STAT = 3
											AND (C.AMD_DATE BETWEEN '$Start_Date' AND '$End_Date')
											GROUP BY A.ITM_CODE";
						$resAM		= $this->db->query($sqlAM)->result();
						foreach($resAM as $rowAM):
							$theRow		= $theRow + 1;
							$PRJCODE	= $rowAM->PRJCODE;
							$ITM_CODE	= $rowAM->ITM_CODE;
							$ITM_NAME	= $rowAM->ITM_NAME;
							$ITM_UNIT	= $rowAM->ITM_UNIT;
							$ITM_VOLMBG	= $rowAM->ITM_VOLMBG;
							$ITM_PRICE	= $rowAM->ITM_PRICE;
							$ITM_LASTP	= $rowAM->ITM_LASTP;
							$ITM_TOTALP	= $ITM_VOLMBG * $ITM_PRICE;
							$ADDVOLM	= $rowAM->ADDVOLM;
							$ADDCOST	= $rowAM->ADDCOST;
							$ADDVOLMA	= $ADDVOLM;
							if($ADDVOLM == '' OR $ADDVOLM == 0)
								$ADDVOLMA	= 1;
							$ADDPRICE	= $ADDCOST / $ADDVOLMA;
							$ADDMVOLM	= $rowAM->ADDMVOLM;
							$ADDMCOST	= $rowAM->ADDMCOST;
							$TADD_VOL	= $ITM_VOLMBG + $ADDVOLM - $ADDMVOLM;
							$TADD_AMN	= $ITM_TOTALP + $ADDCOST - $ADDMCOST;
							$TADD_VOLA	= $TADD_VOL;
							if($TADD_VOL == '' OR $TADD_VOL == 0)
								$TADD_VOLA	= 1;
							$TADDPRICE	= $TADD_AMN / $TADD_VOLA;
						?>
							<tr>
                                <td nowrap style="text-align:left;border-left-width:2px; border-left-color:#000"><?php echo $theRow; ?>.</td>
                                <td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
                              	<td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $ITM_UNIT; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ITM_TOTALP, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ADDPRICE, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ADDCOST, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($TADD_VOL, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($TADDPRICE, 2); ?></td>
                                <td nowrap style="text-align:right; border-right-width:2px; border-right-color:#000">
									<?php echo number_format($TADD_AMN, 2); ?>
                                </td>
                            </tr>
               			<?php
						endforeach;
					}
					else
					{
						?>
                            <tr>
                                <td colspan="14" nowrap style="text-align:center; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000">--- none ---</td>
                            </tr>
						<?php
					}
					?>
               <tr bgcolor="#CCCCCC" style="font-weight:bold">
                 <td colspan="14" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">&nbsp;</td>
               </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>