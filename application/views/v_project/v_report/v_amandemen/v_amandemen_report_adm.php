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

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJCOST	= 0;
$PRJDATE	= date('Y/m/d');
$PRJDATE_CO	= date('Y/m/d');
$sqlPRJ		= "SELECT PRJDATE, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">LAPORAN AMANDEMEN/PERUBAHAN BUDGET (ADMIN)
        </td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:16px"><span class="style2" style="text-align:center; font-weight:bold; font-size:16px">&nbsp;s.d Periode : 
        <?php 
				$CRDate	= date_create($End_Date);
				echo date_format($CRDate,"d-m-Y");
			?></span></td>
    </tr>
    <tr>
        <td colspan="2" class="style2" style="text-align:center; font-style:italic"><span class="style2" style="text-align:center; font-weight:bold; font-size:16px">Detail</span></td>
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
                  <td><?php echo "$PRJDATE s.d. $PRJDATE_CO"; ?></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NILAI PROYEK</td>
                  <td>:</td>
                  <td><?php echo number_format($PRJCOST, 2); ?></td>
                </tr>
                <tr style="text-align:left; font-style:italic; display:none">
                  <td nowrap valign="top">NILAI RAP (AWAL)</td>
                  <td>:</td>
                  <td>&nbsp;</td>
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
                  <th colspan="7" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">AMANDEMEN (PERUBAHAN BUDGET)</th>
                  <th width="12%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;border-bottom-width:2px; border-bottom-color:#000;">Budget Awal</th>
                  <th width="12%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;border-bottom-width:2px; border-bottom-color:#000;">Budget <br>setelah <br>Amandemen</th>
              </tr>
                <tr style="background:#CCCCCC">
                  <th width="11%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Nomor</th>
                  <th width="12%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Tanggal</th>
                  <th width="25%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Deskripsi</th>
                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Jenis</th>
                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Kategori Item</th>
                  <th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">Item Budget</th>
                  <th width="12%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">Nilai</th>
                </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td colspan="4" nowrap style="text-align:center;border:none">&nbsp;</td>
               </tr>
               <?php
			   		$theRow		= 0;
				   	$sqlAMC		= "tbl_amd_detail A
											INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
										WHERE B.PRJCODE = '$PRJCODE'";
					$resAMC		= $this->db->count_all($sqlAMC);
					if($resAMC > 0)
					{
						$sqlAM		= "SELECT A.AMD_NUM, A.JOBCODEID, A.JOBPARENT, A.ITM_CODE, A.ITM_UNIT, A.JOBDESC,
											A.AMD_VOLM, A.AMD_PRICE, A.AMD_TOTAL, A.AMD_DESC,
											B.AMD_CODE, B.AMD_TYPE, B.AMD_CATEG, B.AMD_REFNO, B.AMD_REFNOAM, 
											B.AMD_JOBID, B.AMD_NOTES, B.AMD_DATE, B.AMD_AMOUNT
										FROM tbl_amd_detail A
											INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
										WHERE B.PRJCODE = '$PRJCODE'";
						$resAM		= $this->db->query($sqlAM)->result();
						foreach($resAM as $rowAM):
							$theRow		= $theRow + 1;
							$AMD_NUM	= $rowAM->AMD_NUM;
							$AMD_CODE	= $rowAM->AMD_CODE;
							$JOBCODEID	= $rowAM->JOBCODEID;
							$JOBPARENT	= $rowAM->JOBPARENT;
							$ITM_CODE	= $rowAM->ITM_CODE;
							$ITM_UNIT	= $rowAM->ITM_UNIT;
							$JOBDESC	= $rowAM->JOBDESC;
							$AMD_VOLM	= $rowAM->AMD_VOLM;
							$AMD_PRICE	= $rowAM->AMD_PRICE;
							$AMD_TOTAL	= $rowAM->AMD_TOTAL;
							$AMD_DESC	= $rowAM->AMD_DESC;
							$AMD_TYPE	= $rowAM->AMD_TYPE;
							$AMD_CATEG	= $rowAM->AMD_CATEG;
							$AMD_REFNO	= $rowAM->AMD_REFNO;
							$AMD_JOBID	= $rowAM->AMD_JOBID;
							$AMD_NOTES	= $rowAM->AMD_NOTES;
							$AMD_DATE	= $rowAM->AMD_DATE;
							$AMD_AMOUNT	= $rowAM->AMD_AMOUNT;
							
							// GET VOLM BEFORE
							$sqlJOBDC	= "tbl_joblist_detail
											WHERE JOBCODEID = '$AMD_JOBID' AND PRJCODE = '$PRJCODE'";
							$sqlJOBDC	= $this->db->count_all($sqlJOBDC);
							$ITM_VOLM	= 0;
							$ITM_PRICE	= 0;
							$ITM_BUDG	= 0;
							if($sqlJOBDC > 0)
							{
								$sqlJOBD	= "SELECT ITM_VOLM, ITM_PRICE, ITM_BUDG FROM tbl_joblist_detail
												WHERE JOBCODEID = '$AMD_JOBID' AND PRJCODE = '$PRJCODE' cv";
								$sqlJOBD	= $this->db->query($sqlJOBD)->result();
								foreach($sqlJOBD as $rowJOBD):
									$ITM_VOLM	= $rowJOBD->ITM_VOLM;
									$ITM_PRICE	= $rowJOBD->ITM_PRICE;
									$ITM_BUDG	= $rowJOBD->ITM_BUDG;
								endforeach;
							}
							$GAMD_TOT	= $AMD_VOLM + $ITM_VOLM;
							$GAMD_TOTAM	= $AMD_TOTAL + $ITM_BUDG;
							
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
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000"><?php echo $theRow; ?></td>
                                <td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000;"><?php echo $AMD_CODE; ?></td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;"><?php echo $AMD_DATE; ?></td>
                              <td nowrap style="text-align:left;border-top-width:2px; border-top-color:#000;"><?php echo $JOBDESC; ?></td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;"><?php echo $ITM_UNIT; ?></td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;"><?php echo $AMD_CATEGD; ?></td>
                              <td nowrap style="text-align:right;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000"><?php echo number_format($AMD_VOLM, 2); ?></td>
                              <td nowrap style="text-align:right;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000"><?php echo number_format($AMD_TOTAL, 2); ?></td>
                              <td nowrap style="text-align:right;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000"><?php echo number_format($ITM_BUDG, 2); ?></td>
                              <td nowrap style="text-align:right;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000"><?php echo number_format($GAMD_TOTAM, 2); ?></td>
                            </tr>
               			<?php
						endforeach;
					}
					else
					{
						?>
                            <tr>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
                            </tr>
						<?php
					}
					?>
               <tr bgcolor="#666666">
                 <td colspan="7" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000"><b>TOTAL</b></td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
               </tr>
                <tr style="display:none">
                  <td colspan="10" nowrap style="text-align:center;">--- none ---</td>
                </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>