<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2019
 * File Name	= v_projinv_report.php
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
				//$StartDate	= date('d M Y', strtotime($Start_Date));
				//$EndDate	= date('d M Y', strtotime($End_Date));
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
                <tr style="text-align:left; font-style:italic; display:none">
                    <td width="8%" nowrap valign="top">PERIODE</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php //echo "$StartDate s.d. $EndDate"; ?></span></td>
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
                  <th colspan="6" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">FAKTUR / INVOICE</th>
                  <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">FAKTUR PAJAK</th>
                  <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">STATUS INVOICE</th>
                  <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">OUTSTANDING</th>
              </tr>
                <tr style="background:#CCCCCC">
                  <th width="8%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">TANGGAL</th>
                  <th width="9%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">NOMOR</th>
                  <th width="16%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">NILAI (NON PPN)</th>
                  <th width="8%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">NILAI (PPN)</th>
                  <th width="4%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">PROGRESS</th>
                  <th width="8%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">PROGRESS (%)</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TANGGAL</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">NO. SERI</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">J. TEMPO</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TGL. BAYAR</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">J. TEMPO</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">HARI</th>
                  <th width="16%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-right-width:2px; border-right-color:#000">JUMLAH</th>
                </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td colspan="16" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
               </tr>
               <?php
			   		$theRow			= 0;
					$TAMD_AMOUNT	= 0;
				   	$sqlAMC			= "tbl_projinv_header A
											INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
										WHERE A.PRJCODE = '$PRJCODE' AND A.PINV_STAT = 3";
					$resAMC			= $this->db->count_all($sqlAMC);
					if($resAMC > 0)
					{
						$sqlAM		= "SELECT A.*, B.own_Title, B.own_Name
										FROM tbl_projinv_header A
											INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
										WHERE A.PRJCODE = '$PRJCODE' AND A.PINV_STAT = 3
											ORDER BY A.PINV_DATE ASC";
						$resAM		= $this->db->query($sqlAM)->result();
						foreach($resAM as $rowPRJINV):
							$theRow			= $theRow + 1;
							$PINV_CODE		= $rowPRJINV->PINV_CODE;
							$PINV_MANNO		= $rowPRJINV->PINV_MANNO;
							$PINV_STEP		= $rowPRJINV->PINV_STEP;
							$PINV_CAT		= $rowPRJINV->PINV_CAT;
							$PINV_SOURCE	= $rowPRJINV->PINV_SOURCE;
							$OWN_CODE		= $rowPRJINV->PINV_OWNER;
							$OWN_TITLE		= $rowPRJINV->own_Title;
							$OWN_NAME		= $rowPRJINV->own_Name;
							$PINV_DATE		= $rowPRJINV->PINV_DATE;
							$PINV_ENDDATE	= $rowPRJINV->PINV_ENDDATE;
							$PINV_RETVAL	= $rowPRJINV->PINV_RETVAL;
							$PINV_RETCUTP	= $rowPRJINV->PINV_RETCUTP;
							$PINV_RETCUT	= $rowPRJINV->PINV_RETCUT;
							$PINV_DPPER		= $rowPRJINV->PINV_DPPER;
							$PINV_DPVAL		= $rowPRJINV->PINV_DPVAL;
							$PINV_DPVALPPn	= $rowPRJINV->PINV_DPVALPPn;
							$PINV_DPBACK	= $rowPRJINV->PINV_DPBACK;
							$PINV_DPBACKPPn	= $rowPRJINV->PINV_DPBACKPPn;
							$PINV_PROG		= $rowPRJINV->PINV_PROG;
							$PINV_PROGVAL	= $rowPRJINV->PINV_PROGVAL;
							$PINV_PROGVALPPn= $rowPRJINV->PINV_PROGVALPPn;
							$PINV_PROGAPP	= $rowPRJINV->PINV_PROGAPP;
							$PINV_PROGAPPVAL= $rowPRJINV->PINV_PROGAPPVAL;
							$PINV_VALADD	= $rowPRJINV->PINV_VALADD;
							$PINV_VALADDPPn	= $rowPRJINV->PINV_VALADDPPn;
							$PINV_VALBEF	= $rowPRJINV->PINV_VALBEF;
							$PINV_VALBEFPPn	= $rowPRJINV->PINV_VALBEFPPn;
							$PINV_AKUMNEXT	= $rowPRJINV->PINV_AKUMNEXT;
							$PINV_TOTVAL	= $rowPRJINV->PINV_TOTVAL;
							$PINV_TOTVALPPn	= $rowPRJINV->PINV_TOTVALPPn;
							$PINV_TOTVALPPh	= $rowPRJINV->PINV_TOTVALPPh;
							$GPINV_TOTVAL	= $rowPRJINV->GPINV_TOTVAL;
							$PINV_NOTES		= $rowPRJINV->PINV_NOTES;
							$PINV_PAIDAM	= $rowPRJINV->PINV_PAIDAM;
							$REM_AMOUNT		= $GPINV_TOTVAL - $PINV_PAIDAM;
						?>
							<tr>
                                <td nowrap style="text-align:left;border-left-width:2px; border-left-color:#000"><?php echo $theRow; ?>.</td>
                                <td nowrap style="text-align:left;"><?php echo $PINV_DATE; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $PINV_MANNO; ?></td>
                              	<td nowrap style="text-align:right;"><?php echo number_format($PINV_TOTVAL, 2); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($GPINV_TOTVAL, 2); ?></td>
                              	<td nowrap style="text-align:right;"><?php echo number_format($PINV_PROGVAL, 2); ?></td>
                              	<td nowrap style="text-align:right;"><?php echo number_format($PINV_PROG, 2); ?></td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:center;"><?php echo $PINV_ENDDATE; ?></td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:right;"><?php echo number_format($PINV_PAIDAM, 2); ?></td>
                                <td nowrap style="text-align:center;"><?php echo $PINV_ENDDATE; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format(0, 2); ?></td>
                                <td nowrap style="text-align:right; border-right-width:2px; border-right-color:#000">
									<?php echo number_format($REM_AMOUNT, 2); ?>
                                </td>
                            </tr>
               			<?php
						endforeach;
					}
					else
					{
						?>
                            <tr>
                                <td colspan="16" nowrap style="text-align:center; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000">--- none ---</td>
                            </tr>
						<?php
					}
					?>
               <tr bgcolor="#CCCCCC" style="font-weight:bold">
                 <td colspan="16" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">&nbsp;</td>
               </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>