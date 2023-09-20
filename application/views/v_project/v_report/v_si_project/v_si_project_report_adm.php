<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= r_usagereq_report_adm.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 27 Juli 2018
 * File Name	= v_si_project_report_adm.php
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

$PRJCOST	= 0;
$sqlPRJ		= "SELECT PRJDATE, PRJEDAT, PRJCOST, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJDATE	= $rowPRJ->PRJDATE;
	$PRJEDAT	= $rowPRJ->PRJEDAT;
	$PRJCOST	= $rowPRJ->PRJCOST;
	$PRJNAME	= $rowPRJ->PRJNAME;
endforeach;

$TOTSI		= 0;
$sqlSIT		= "SELECT SUM(SI_APPVAL) AS TOTSI FROM tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT IN (3,6)";
$resSIT		= $this->db->query($sqlSIT)->result();
foreach($resSIT as $rowSIT):
	$TOTSI	= $rowSIT->TOTSI;
endforeach;
if($TOTSI == '')
	$TOTSI	= 0;
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">LAPORAN DAFTAR SI / PEKERJAAN (+-)
        </td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:16px"><span class="style2" style="text-align:center; font-weight:bold; font-size:16px">&nbsp;s.d Periode : <?php 
				$CRDate	= date_create($End_Date);
				echo date_format($CRDate,"d-m-Y");
			?></span></td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
            	<!--<tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top">Type Dokumen</td>
        			<td width="1%">:</td>
        			<td width="91%"><?php// echo $DOCTYPE1; ?></td>
    			</tr>-->
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
                    <td width="1%">:</td>
                    <td width="91%"><?php echo "$PRJCODE"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">WAKTU PELAKSANAAN</td>
                  <td>:</td>
                  <td><?php echo date('d/m/Y', strtotime($PRJDATE))." s/d ".date('d/m/Y', strtotime($PRJEDAT)); ?></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NILAI PROYEK</td>
                  <td>:</td>
                  <td><?php echo number_format($PRJCOST, 2); ?></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NILAI SI / PEKERJAAN (+-)</td>
                  <td>:</td>
                  <td>Rp. <?php echo number_format($TOTSI, 2); ?></td>
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
                  <th width="2%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
                  <th colspan="6" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">SI / PEKERJAAN (+-)</th>
              </tr>
                <tr style="background:#CCCCCC">
                  <th width="11%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">NOMOR</th>
                  <th width="12%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TANGGAL</th>
                  <th width="32%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">DESKRIPSI</th>
                  <th width="16%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KODE PEKERJAAN</th>
                  <th width="13%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">NILAI</th>
                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">STATUS</th>
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
			   		$theRow		= 0;
				   	$sqlSIC		= "tbl_siheader WHERE PRJCODE = '$PRJCODE'";
					$resSIC		= $this->db->count_all($sqlSIC);
					if($resSIC > 0)
					{
						$sqlSI		= "SELECT SI_CODE, SI_MANNO, SI_DATE, SI_ENDDATE, SI_DESC, SI_APPVAL, SI_STAT
										FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
						$resSI		= $this->db->query($sqlSI)->result();
						foreach($resSI as $rowSI):
							$theRow		= $theRow + 1;
							$SI_CODE	= $rowSI->SI_CODE;
							$SI_MANNO	= $rowSI->SI_MANNO;
							$SI_DATE	= $rowSI->SI_DATE;
							$SI_ENDDATE	= $rowSI->SI_ENDDATE;
							$SI_DESC	= $rowSI->SI_DESC;
							$SI_APPVAL	= $rowSI->SI_APPVAL;
							$SI_STAT	= $rowSI->SI_STAT;
							
							if($SI_STAT == 0)
							{
								$SI_STATD 	= 'fake';
								$STATCOL	= 'danger';
							}
							elseif($SI_STAT == 1)
							{
								$SI_STATD 	= 'New';
								$STATCOL	= 'warning';
							}
							elseif($SI_STAT == 2)
							{
								$SI_STATD 	= 'Confirm';
								$STATCOL	= 'primary';
							}
							elseif($SI_STAT == 3)
							{
								$SI_STATD 	= 'Approved';
								$STATCOL	= 'success';
							}
							elseif($SI_STAT == 4)
							{
								$SI_STATD 	= 'Revise';
								$STATCOL	= 'danger';
							}
							elseif($SI_STAT == 5)
							{
								$SI_STATD 	= 'Rejected';
								$STATCOL	= 'danger';
							}
							elseif($SI_STAT == 6)
							{
								$SI_STATD 	= 'Close';
								$STATCOL	= 'info';
							}
							elseif($SI_STAT == 7)
							{
								$SI_STATD 	= 'Awaiting';
								$STATCOL	= 'warning';
							}
							elseif($SI_STAT == 9)
							{
								$SI_STATD 	= 'Void';
								$STATCOL	= 'danger';
							}
							else
							{
								$SI_STATD 	= 'Fake';
								$STATCOL	= 'warning';
							}
						?>
							<tr>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000"><?php echo $theRow; ?></td>
								<td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000;"><?php echo $SI_CODE; ?></td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;"><?php echo date('d/m/Y', strtotime($SI_DATE)); ?></td>
								<td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000;"><?php echo $SI_DESC; ?></td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;"><?php echo number_format($SI_APPVAL, 2); ?></td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">
                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
									<?php 
                                        echo "&nbsp;&nbsp;$SI_STATD&nbsp;&nbsp;";
                                     ?>
                                </span>
                                </td>
							</tr>
               			<?php
						endforeach;
					}
					else
					{
						?>
                        	<tr>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000">&nbsp;</td>
								<td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
							</tr>
                        <?php
					}
			   ?>
               <tr bgcolor="#666666" style="display:none">
                 <td colspan="5" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000"><b>TOTAL</b></td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
               </tr>
                <tr style="display:none">
                  <td colspan="7" nowrap style="text-align:center;">--- none ---</td>
                </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>