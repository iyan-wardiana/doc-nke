<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2019
 * File Name	= v_amandemen_report.php
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

/*$CRDate	= date_create($End_Date);
echo date_format($CRDate,"d-m-Y");*/
$StartDate	= date('d M Y', strtotime($Start_Date));
$EndDate	= date('d M Y', strtotime($End_Date));
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">&nbsp;</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:16px">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
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
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">No.</th>
                  <th width="11%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-top-width:2px; border-top-color:#000;">Kode</th>
                  <th width="7%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-top-width:2px; border-top-color:#000;">Tanggal</th>
                  <th width="45%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-top-width:2px; border-top-color:#000;">Deskripsi</th>
                  <th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-top-width:2px; border-top-color:#000;">Jenis</th>
                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-top-width:2px; border-top-color:#000;">Kategori</th>
                  <th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-top-width:2px; border-top-color:#000;">Nilai</th>
                </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
               </tr>
               <?php
			   		$theRow			= 0;
				   	$sqlAMC			= "tbl_amd_header A
										WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT = $CFStat
											AND (A.AMD_DATE BETWEEN '$Start_Date' AND '$End_Date')";
					$resAMC			= $this->db->count_all($sqlAMC);
					if($resAMC > 0)
					{
						$sqlAM		= "SELECT A.AMD_NUM, A.AMD_CODE, A.AMD_JOBID, A.AMD_DESC, A.AMD_UNIT,
											A.AMD_TYPE, A.AMD_CATEG, A.AMD_REFNO, A.AMD_REFNOAM, 
											A.AMD_NOTES, A.AMD_DATE, A.AMD_AMOUNT
										FROM tbl_amd_header A
										WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT = $CFStat
											AND (A.AMD_DATE BETWEEN '$Start_Date' AND '$End_Date')";
						$resAM		= $this->db->query($sqlAM)->result();
						foreach($resAM as $rowAM):
							$theRow		= $theRow + 1;
							$AMD_NUM	= $rowAM->AMD_NUM;
							$AMD_CODE	= $rowAM->AMD_CODE;
							$AMD_DATE	= $rowAM->AMD_DATE;
							$AMD_JOBID	= $rowAM->AMD_JOBID;
							$AMD_DESC	= $rowAM->AMD_DESC;
							$AMD_TYPE	= $rowAM->AMD_TYPE;
							$AMD_CATEG	= $rowAM->AMD_CATEG;
							$AMD_UNIT	= $rowAM->AMD_UNIT;
							$AMD_REFNO	= $rowAM->AMD_REFNO;
							$AMD_REFNOAM= $rowAM->AMD_REFNOAM;
							$AMD_NOTES	= $rowAM->AMD_NOTES;
							$AMD_AMOUNT	= $rowAM->AMD_AMOUNT;
							
							if($AMD_CATEG == 'OB')
								$AMD_CATEGD	= 'Over Budget';
							elseif($AMD_CATEG == 'SI')
								$AMD_CATEGD	= 'Site Instruction';
							elseif($AMD_CATEG == 'SINJ')
								$AMD_CATEGD	= 'New Site Instruction';
							elseif($AMD_CATEG == 'NB')
								$AMD_CATEGD	= 'Not Budgeting';
							elseif($AMD_CATEG == 'OTH')
								$AMD_CATEGD	= 'Lainnya';
						?>
							<tr>
                                <td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000; border-left-width:2px; border-left-color:#000;  border-bottom-width:2px; border-bottom-color:#000"><?php echo $theRow; ?>.</td>
                                <td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000"><?php echo $AMD_CODE; ?></td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000"><?php echo $AMD_DATE; ?></td>
                              <td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000;  border-bottom-width:2px; border-bottom-color:#000"><?php echo "$AMD_DESC - $AMD_NOTES"; ?></td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000"><?php echo $AMD_UNIT; ?></td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000"><?php echo $AMD_CATEGD; ?></td>
                              <td nowrap style="text-align:right;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000"><?php echo number_format($AMD_AMOUNT, 2); ?></td>
                            </tr>
               			<?php
						endforeach;
					}
					else
					{
						?>
                            <tr>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">&nbsp;</td>
                            </tr>
						<?php
					}
					?>
               <tr bgcolor="#CCCCCC" style="display:none">
                 <td colspan="7" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000"><b>TOTAL</b></td>
               </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>