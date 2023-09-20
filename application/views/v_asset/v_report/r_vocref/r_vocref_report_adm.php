<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 April 2017
 * File Name	= r_product_report_adm.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";
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
<body>
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
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="181" height="44"></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:12px">VOUCHER REFERENCE REPORT (<?php echo $CFTyped; ?>)</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">PT NUSA KONSTRUKSI ENJINIRING, Tbk,</span></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">No.</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">VOUCHER</td>
                  <td width="24%" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>
                  <td width="33%" nowrap style="text-align:center; font-weight:bold">ASSET</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">VOLUME</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">JUMLAH<br>(Rp.)</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">PROJECT</td>
              </tr>
                <?php
					if($CFType == 1) // 1. Detail, 2. Sumamry
					{
						$therow			= 0;
						
						if($viewAsset == 0)	// PER ASSET
						{
							if($viewProj == 0) // PER ASSET AND PER PROJECT
							{
								$sqlq0 			= "SELECT A.NOTES, A.ITM_CODE, B.AM_AS_CODE, A.AM_CODE, A.ITM_QTY, A.ITM_TOTAL, 
														A.AM_PRJCODE
													FROM tbl_asset_maintendet A
														INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
														WHERE B.AM_PRJCODE IN ($PRJCODECOL)
															AND B.AM_AS_CODE IN ($ASTCODECOL)
													UNION ALL 
													SELECT C.NOTES, C.ITM_CODE, D.AU_AS_CODE, C.AU_CODE, C.ITM_QTY, C.ITM_TOTAL, 
														C.AU_PRJCODE
													 FROM tbl_asset_usagedet C
														INNER JOIN tbl_asset_usage D ON C.AU_CODE = D.AU_CODE
														WHERE D.AU_PRJCODE IN ($PRJCODECOL)
															AND D.AU_AS_CODE IN ($ASTCODECOL)";
							}
							else // PER ASSET AND ALL PROJECT
							{
								$sqlq0 			= "SELECT A.NOTES, A.ITM_CODE, B.AM_AS_CODE, A.AM_CODE, A.ITM_QTY, A.ITM_TOTAL, 
														A.AM_PRJCODE

													FROM tbl_asset_maintendet A
														INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
														WHERE B.AM_AS_CODE IN ($ASTCODECOL)
													UNION ALL 
													SELECT C.NOTES, C.ITM_CODE, D.AU_AS_CODE, C.AU_CODE, C.ITM_QTY, C.ITM_TOTAL, 
														C.AU_PRJCODE
													 FROM tbl_asset_usagedet C
														INNER JOIN tbl_asset_usage D ON C.AU_CODE = D.AU_CODE
														WHERE D.AU_AS_CODE IN ($ASTCODECOL)";
							}
							$resq0 			= $this->db->query($sqlq0)->result();
						}
						else	// ALL ASSET
						{
							if($viewProj == 0) // ALL ASSET AND PER PROJECT
							{
								$sqlq0 			= "SELECT A.NOTES, A.ITM_CODE, B.AM_AS_CODE, A.AM_CODE, A.ITM_QTY, A.ITM_TOTAL, 
														A.AM_PRJCODE
													FROM tbl_asset_maintendet A
														INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
														WHERE B.AM_PRJCODE IN ($PRJCODECOL)
													UNION ALL 
													SELECT C.NOTES, C.ITM_CODE, D.AU_AS_CODE, C.AU_CODE, C.ITM_QTY, C.ITM_TOTAL, 
														C.AU_PRJCODE
													 FROM tbl_asset_usagedet C
														INNER JOIN tbl_asset_usage D ON C.AU_CODE = D.AU_CODE
														WHERE D.AU_PRJCODE IN ($PRJCODECOL)";
							}
							else // ALL ASSET AND ALL PROJECT
							{
								$sqlq0 			= "SELECT A.NOTES, A.ITM_CODE, B.AM_AS_CODE, A.AM_CODE, A.ITM_QTY, A.ITM_TOTAL, 
														A.AM_PRJCODE
													FROM tbl_asset_maintendet A
														INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
													UNION ALL 
													SELECT C.NOTES, C.ITM_CODE, D.AU_AS_CODE, C.AU_CODE, C.ITM_QTY, C.ITM_TOTAL, 
														C.AU_PRJCODE
													 FROM tbl_asset_usagedet C
														INNER JOIN tbl_asset_usage D ON C.AU_CODE = D.AU_CODE";
							}
							$resq0 			= $this->db->query($sqlq0)->result();
						}
						
						foreach($resq0 as $rowsqlq0) :
							$therow			= $therow + 1;
							$NOTES 			= $rowsqlq0->NOTES;						
							$ITM_CODE 		= $rowsqlq0->ITM_CODE;
							
							$AS_CODE		= $rowsqlq0->AM_AS_CODE;
							$AM_CODE		= $rowsqlq0->AM_CODE;
							$ITM_QTY		= number_format($rowsqlq0->ITM_QTY, $decFormat);
							$ITM_TOTAL		= number_format($rowsqlq0->ITM_TOTAL, $decFormat);;
							$AM_PRJCODE		= $rowsqlq0->AM_PRJCODE;
							
							$AS_NAME	= '';
							$AS_DESC	= '';
							$sqlq3a 	= "SELECT AS_NAME, AS_DESC FROM tbl_asset_list WHERE AS_CODE = '$AS_CODE' LIMIT 1";
							$resq3a 	= $this->db->query($sqlq3a)->result();
							foreach($resq3a as $rowq3a) :
								$AS_NAME	= $rowq3a->AS_NAME;
								$AS_DESC	= $rowq3a->AS_DESC;
							endforeach;
							
							$ITM_NAME	= '';
							$sqlq3b 	= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' LIMIT 1";
							$resq3b 	= $this->db->query($sqlq3b)->result();
							foreach($resq3b as $rowq3b) :
								$ITM_NAME	= $rowq3b->ITM_NAME;
							endforeach;
							?>
								<tr>
									<td nowrap style="text-align:left;"><?php echo "$therow"; ?>.</td>
									<td nowrap style="text-align:left;"><?php echo $NOTES; ?></td>
									<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
									<td nowrap style="text-align:left;"><?php echo $AS_NAME; ?></td>
									<td nowrap style="text-align:right;"><?php echo $ITM_QTY; ?></td>
									<td nowrap style="text-align:right;"><?php echo $ITM_TOTAL; ?></td>
									<td nowrap style="text-align:center;"><?php echo $AM_PRJCODE; ?></td>
								</tr>
							<?php
						endforeach;
						?>
							<tr style="font-style:italic">
								<td colspan="2" nowrap style="text-align:left;">&nbsp;</td>
								<td nowrap style="text-align:left;">&nbsp;</td>
								<td nowrap style="text-align:left;">&nbsp;</td>
								<td nowrap style="text-align:left;">&nbsp;</td>
								<td nowrap style="text-align:right;">&nbsp;</td>
								<td nowrap style="text-align:center;">&nbsp;</td>
							</tr>
						<?php
					}
					else
					{
						$therow			= 0;
						$AU_PRJCODE		= "-";
						$AU_DESC		= "-";
						

						if($viewAsset == 0)	// PER PROJECT
						{
							$sqlq0 		= "SELECT A.AS_CODE, A.AS_NAME FROM tbl_asset_list A
											WHERE A.AS_CODE IN ($ASTCODECOL) ORDER BY A.AS_NAME";
							$resq0 		= $this->db->query($sqlq0)->result();
						}
						else
						{
							$sqlq0 		= "SELECT A.AS_CODE, A.AS_NAME FROM tbl_asset_list A ORDER BY A.AS_NAME";
							$resq0 		= $this->db->query($sqlq0)->result();
						}
						
						foreach($resq0 as $rowsqlq0) :
							$therow		= $therow + 1;
							$AS_CODE 	= $rowsqlq0->AS_CODE;
								
								$AU_STARTD	= "";
								$sqlq1 		= "SELECT AU_STARTD, AU_STARTT FROM tbl_asset_usage WHERE AU_AS_CODE = '$AS_CODE' ORDER BY AU_STARTD ASC LIMIT 1";
								$resq1 		= $this->db->query($sqlq1)->result();
								foreach($resq1 as $rowsqlq1) :
									$AU_STARTD		= $rowsqlq1->AU_STARTD;
									$AU_STARTD 		= date('d/m/Y',strtotime($AU_STARTD));
									$AU_STARTT		= $rowsqlq1->AU_STARTT;
									$AU_STARTT 		= date('H:i',strtotime($AU_STARTT));
								endforeach;
								$AU_STARTD	= "$AU_STARTD<br>$AU_STARTT";
								
								$AU_ENDD	= "";
								$sqlq2 		= "SELECT AU_ENDD, AU_ENDT FROM tbl_asset_usage WHERE AU_AS_CODE = '$AS_CODE' ORDER BY AU_ENDD DESC LIMIT 1";
								$resq2 		= $this->db->query($sqlq2)->result();
								foreach($resq2 as $rowsqlq2) :
									$AU_ENDD		= $rowsqlq2->AU_ENDD;
									$AU_ENDD 		= date('d/m/Y',strtotime($AU_ENDD));
									$AU_ENDT		= $rowsqlq2->AU_ENDT;
									$AU_ENDT 		= date('H:i',strtotime($AU_ENDT));
								endforeach;
								$AU_ENDD	= "$AU_ENDD<br>$AU_ENDT";
												
								$AS_NAME 	= $rowsqlq0->AS_NAME;							
							
								$sqlq1 	= "SELECT SUM(AP_HOPR) AS TOTAP_HOPR, SUM(AP_QTYOPR) AS TOTAP_QTYOPR, SUM(AP_HEXP) AS TOTAP_HEXP, SUM(AP_QTYEXP) AS TOTAP_QTYEXP,
												SUM(ISRENTP) AS TOTISRENTP, SUM(ISPARTP) AS TOTISPARTP, SUM(ISFUELP) AS TOTISFUELP, SUM(ISLUBRICP) AS TOTISLUBRICP,
												SUM(ISFASTMP) AS TOTISFASTMP, SUM(ISWAGEP) AS TOTISWAGEP
											FROM tbl_asset_usage A
											WHERE AU_AS_CODE = '$AS_CODE' AND AU_PROCS = 2";
								$resq1 	= $this->db->query($sqlq1)->result();
								foreach($resq1 as $rowsqlq1) :
									$AP_HOPR		= $rowsqlq1->TOTAP_HOPR;
									$AP_QTYOPR		= $rowsqlq1->TOTAP_QTYOPR;
									$AP_HEXP		= $rowsqlq1->TOTAP_HEXP;
									$AP_QTYEXP		= $rowsqlq1->TOTAP_QTYEXP;
								
									$ISRENTP		= $rowsqlq1->TOTISRENTP;
									$ISPARTP		= $rowsqlq1->TOTISPARTP;
									$ISFUELP		= $rowsqlq1->TOTISFUELP;
									$ISLUBRICP		= $rowsqlq1->TOTISLUBRICP;
									$ISFASTMP		= $rowsqlq1->TOTISFASTMP;
									$ISWAGEP		= $rowsqlq1->TOTISWAGEP;
									
									$AU_TOTAL		= $ISRENTP + $ISPARTP + $ISFUELP + $ISLUBRICP + $ISFASTMP + $ISWAGEP;
								endforeach;
								
								$AP_HEXP			= $AU_TOTAL / $AP_HOPR;
								$AP_QTYEXP			= $AU_TOTAL / $AP_QTYOPR;
							
								if($viewType == 0)
								{
									$AP_HOPR	= number_format($AP_HOPR, $decFormat);
									$AP_QTYOPR	= number_format($AP_QTYOPR, $decFormat);
									$AU_TOTAL	= number_format($AU_TOTAL, $decFormat);
									$AP_HEXP	= number_format($AP_HEXP, $decFormat);
									$AP_QTYEXP	= number_format($AP_QTYEXP, $decFormat);
								}
								else
								{
									$AP_HOPR	= round($AP_HOPR);
									$AP_QTYOPR	= round($AP_QTYOPR);
									$AU_TOTAL	= round($AU_TOTAL);
									$AP_HEXP	= round($AP_HEXP);
									$AP_QTYEXP	= round($AP_QTYEXP);
								}
								?>
									<tr>
										<td nowrap style="text-align:left;"><?php echo "$therow"; ?>.</td>
										<td nowrap style="text-align:left;"><?php echo $AU_PRJCODE; ?></td>
										<td nowrap style="text-align:left;"><?php echo $AS_NAME; ?></td>
										<td nowrap style="text-align:left;"><?php echo $AU_DESC; ?></td>
										<td nowrap style="text-align:right;"><?php echo $AP_HOPR; ?></td>
										<td nowrap style="text-align:right;"><?php echo $AP_QTYOPR; ?></td>
										<td nowrap style="text-align:center;">-</td>
									</tr>
								<?php
						endforeach;
					}
                ?>
            </table>
	  </td>
    </tr>
</table>
</section>
</body>
</html>