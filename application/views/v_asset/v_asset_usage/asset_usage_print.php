<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= r_usage_report.php
 * Location		= -
*/

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat	= 2;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
				WHERE A.PRJCODE = '$PRJCODE'
				ORDER BY A.PRJCODE";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJCODED 	= $row ->PRJCODE;
	$PRJNAMED 	= $row ->PRJNAME;
endforeach;
$PRJCODECOLLD	= $PRJCODECOLL;
$PRJNAMECOLLD	= $PRJNAMECOLL;
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

<?php

	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'RekapitulasiTimeSheet')$RekapitulasiTimeSheet = $LangTransl;
		if($TranslCode == 'HeavyEquipmentRent')$HeavyEquipmentRent = $LangTransl;
		if($TranslCode == 'WeeklyReport')$WeeklyReport = $LangTransl;
		if($TranslCode == 'Nomor')$Nomor = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'ReportUntil')$ReportUntil = $LangTransl;
		if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
		if($TranslCode == 'Periode')$Periode = $LangTransl;
		if($TranslCode == 'ReportDate')$ReportDate = $LangTransl;
		if($TranslCode == 'ProjectCode')$ProjectCode = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'AssetName')$AssetName = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Start')$Start = $LangTransl;
		if($TranslCode == 'End')$End = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Volume')$Volume = $LangTransl;
		if($TranslCode == 'Expenses')$Expenses = $LangTransl;
		if($TranslCode == 'Rent')$Rent = $LangTransl;
		if($TranslCode == 'SparePart')$SparePart = $LangTransl;
		if($TranslCode == 'Fuel')$Fuel = $LangTransl;
		if($TranslCode == 'Oil')$Oil = $LangTransl;
		if($TranslCode == 'FastMoving')$FastMoving = $LangTransl;
		if($TranslCode == 'RMFee')$RMFee = $LangTransl;
		if($TranslCode == 'Total')$Total = $LangTransl;
	endforeach;
?>

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
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" width="100" height="50"></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">&nbsp;</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">(</span></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center;">
        	<strong>TIME SHEET HARIAN PEMAKAIAN ALAT BERAT</strong><br>
            NO. :
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
                <tr style="text-align:left; font-style:italic">
                    <td width="4%" nowrap valign="top">Nama Proyek</td>
                    <td width="1%">:</td>
                    <td width="54%"><span class="style2" style="text-align:left; font-style:italic"><?php echo "$PRJCODECOLLD - $PRJNAMECOLLD"; ?></span></td>
                    <td width="15%">Jenis Alat</td>
                    <td width="1%">:</td>
                    <td width="25%" nowrap><?php echo "$AS_NAME"; ?></td>
                </tr>
                
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">Lokasi Proyek</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php //echo $PRJNAMECOLLD;?>&nbsp;</span></td>
                  <td>Operator</td>
                  <td>:</td>
                  <td><?php echo $AS_MACH_TYPE; ?></td>
              </tr>
              
              <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">Tangal</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php //echo $PRJNAMECOLLD;?>&nbsp;</span></td>
                  <td>Penanggung Jawab</td>
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
                    <td width="2%" nowrap style="text-align:center; font-weight:bold">No.</td>
                  <td width="7%" nowrap style="text-align:center; font-weight:bold">&nbsp;</td>
                  <td width="24%" nowrap style="text-align:center; font-weight:bold">Kegiatan Alat</td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php //echo $Date ?>Jam Mulai <br>Kerja</td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php //echo $Description ?>Kode</td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php //echo $Volume ?>Selesai <br>Jam</td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold; display:none"><?php //echo $Volume ?>Jam Lembur</td>
                  <td width="9%" nowrap style="text-align:center; font-weight:bold"><?php //echo $Volume ?>Total<br>Jam Kerja</td>
                  <td width="26%" nowrap style="text-align:center; font-weight:bold"><?php //echo $Volume ?>Keterangan</td>
              </tr>
                <?php					
                    $therow			= 0;
                    $GTOTAL			= 0;
                    $GTOTALA		= 0;
                    $GTOTALB		= 0;
                    $noU			= 0;
                    $noUa			= 0;
                    $noUb			= 0;
					
                    if($viewProj == 0)	// PER PROJECT
                    {
						$sqlq0 			= "SELECT A.* FROM tbl_asset_usage A
											WHERE A.PRJCODE IN ($PRJCODECOL) AND $KONDITT
												 AND A.AU_AS_CODE IN ($AS_CODE)
												 AND A.AU_STAT = 3 AND A.AU_PROCS = 3
											ORDER BY A.AU_DATE";
						$resq0 			= $this->db->query($sqlq0)->result();
                    }
                    else
                    {
						$sqlq0 			= "SELECT A.* FROM tbl_asset_usage A
											WHERE B.JL_PRJCODE IN ($PRJCODECOL) AND $KONDITT
												AND A.AU_AS_CODE IN ($AS_CODE)
												 AND A.AU_STAT = 3 AND A.AU_PROCS = 3
											ORDER BY A.AU_DATE";
						$resq0 			= $this->db->query($sqlq0)->result();
                    }
                    
					$AP_QTYOPR	= 0;
					$TOT_QTYOPR	= 0;
					$ISRENTP	= 0;
					$ISPARTP	= 0;
					$AP_HOPR_T	= 0;
                    foreach($resq0 as $rowsqlq0) :
                        $therow			= $therow + 1;
                        $AU_CODE 		= $rowsqlq0->AU_CODE;	
						$AU_CODED		= substr($AU_CODE, -4);					
                        $AU_JOBCODE 	= $rowsqlq0->AU_JOBCODE;
						
						//$JL_NAME		= $rowsqlq0->JL_NAME;
						//$JL_DESC		= $rowsqlq0->JL_DESC;
						
                        $AU_AS_CODE	= $rowsqlq0->AU_AS_CODE;
                        
							$AS_NAME	= '';
							$AS_DESC	= '';
							$sqlq3a 	= "SELECT AS_NAME, AS_DESC FROM tbl_asset_list WHERE AS_CODE = '$AU_AS_CODE' LIMIT 1";
							$resq3a 	= $this->db->query($sqlq3a)->result();
							foreach($resq3a as $rowq3a) :
								$AS_NAME	= $rowq3a->AS_NAME;
								$AS_DESC	= $rowq3a->AS_DESC;
							endforeach;
							
                        $AU_DATE 		= $rowsqlq0->AU_DATE;
						$AU_STARTD		= $rowsqlq0->AU_STARTD;
						$AU_STARTD1		= date('d/m/Y',strtotime($AU_STARTD));
						$AU_STARTD 		= date('H:i',strtotime($AU_STARTD));
						$AU_ENDD		= $rowsqlq0->AU_ENDD;
						$AU_ENDD 		= date('H:i',strtotime($AU_ENDD));
						$AU_PROCD		= $rowsqlq0->AU_PROCD;
						$AU_PROCT		= $rowsqlq0->AU_PROCT;
						$AU_PROCD		= "$AU_PROCD $AU_PROCT";
						$AU_PROCD 		= date('d/m/Y H:i',strtotime($AU_PROCD));
                        $PRJCODE		= $rowsqlq0->PRJCODE;
                        $AU_DESC		= $rowsqlq0->AU_DESC;
                        $AP_HOPR		= $rowsqlq0->AP_HOPR;
						$AP_HOPR_T		= $AP_HOPR_T + $AP_HOPR;
                        $AP_QTYOPR		= $rowsqlq0->AP_QTYOPR;
                        $AP_HEXP		= $rowsqlq0->AP_HEXP;
                        $AP_QTYEXP		= $rowsqlq0->AP_QTYEXP;
							
                        $ISRENT			= $rowsqlq0->ISRENT;
                        $ISPART			= $rowsqlq0->ISPART;
                        $ISFUEL			= $rowsqlq0->ISFUEL;
                        $ISLUBRIC		= $rowsqlq0->ISLUBRIC;
                        $ISFASTM		= $rowsqlq0->ISFASTM;
                        $ISWAGE			= $rowsqlq0->ISWAGE;
							
                        $ISRENTP		= $rowsqlq0->ISRENTP;
                        $ISPARTP		= $rowsqlq0->ISPARTP;
                        $ISFUELP		= $rowsqlq0->ISFUELP;
                        $ISLUBRICP		= $rowsqlq0->ISLUBRICP;
                        $ISFASTMP		= $rowsqlq0->ISFASTMP;
                        $ISWAGEP		= $rowsqlq0->ISWAGEP;
						
						$AU_TOTAL		= $ISRENTP + $ISPARTP + $ISFUELP + $ISLUBRICP + $ISFASTMP + $ISWAGEP;
						$TOT_QTYOPR		= $TOT_QTYOPR + $AP_QTYOPR;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$therow"; ?>&nbsp;.</td>
                                <td nowrap style="text-align:left;"><?php echo $AU_STARTD1; ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $AS_NAME; ?>&nbsp;</td>
                                <td nowrap style="text-align:center;"><?php echo $AU_STARTD; ?>&nbsp;</td>
                                <td nowrap style="text-align:center;"><?php echo $AU_CODED; ?>&nbsp;</td>
                                <td nowrap style="text-align:center;"><?php echo $AU_ENDD; ?>&nbsp;</td>
                                <td nowrap style="text-align:right; display:none"><?php echo number_format($ISRENTP, $decFormat); ?>&nbsp;</td>
                                <td nowrap style="text-align:right;"><?php echo number_format($AP_HOPR, $decFormat); ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $AU_DESC; ?>&nbsp;</td>
                            </tr>
                        <?php
                    endforeach;
                ?>
                <tr>
                    <td colspan="5" nowrap style="text-align:right;">Total :</td>
                    <td nowrap style="text-align:right; display:none"><?php echo number_format($AU_TOTAL, $decFormat); ?>&nbsp;</td>
                    <td nowrap style="text-align:right;"><?php echo number_format($AP_HOPR_T, $decFormat); ?>&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                </tr>     
                <tr>
                    <td colspan="9" nowrap style="text-align:left; vertical-align:text-top" height="70px"><b>CATATAN :</b></td>
                </tr>
            </table>
            <table width="100%" border="1" rules="all" style="border-top:hidden">
                <tr>
                    <td width="2%" nowrap style="text-align:left" height="70px">
                    Dibuat Oleh : <b>Operator Alat Berat</b><br><br>
                    Paraf : <br><br>
                    Tanggal :
                    </td>
                    <td width="2%" nowrap style="text-align:left" height="70px">
                    Diperiksa Oleh : <b>PIC Sewa Alat</b><br><br>
                    Paraf : <br><br>
                    Tanggal :
                    </td>
                    <td width="2%" nowrap style="text-align:left" height="70px">
                    Disetujui Oleh : <b>Penyewa</b><br><br>
                    Paraf : <br><br>
                    Tanggal :
                    </td>
                    <td width="2%" nowrap style="text-align:left" height="70px">
                    No. Form : 24.R0/LOG/17 <br><br>
                    Revisi : 0 <br><br>
                    Tanggal : 1 Juni 2017
                    </td>
              </tr>
          </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>