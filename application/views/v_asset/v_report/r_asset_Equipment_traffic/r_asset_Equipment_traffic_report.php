<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= r_usage_report.php
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
$decFormat	= 2;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];

if($viewProj == 0) // SELECTED PROJECT
{
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
				$PRJCODECOL1	= "$PRJCODECOL1, $PRJCODED";
				$PRJCODECOLL	= "$PRJCODECOL1";
				$PRJNAMECOL1	= "$PRJNAMECOL1, $PRJNAMED";
				$PRJNAMECOLL	= "$PRJNAMECOL1";
			}		
		endforeach;
	}	
	$PRJCODECOLLD	= $PRJCODECOLL;
	$PRJNAMECOLLD	= $PRJNAMECOLL;
}
else
{
	$myrow			= 0;
	$sql 			= "SELECT DISTINCT PRJCODE FROM tbl_project WHERE PRJCOST > 1000000";
	$result 		= $this->db->query($sql)->result();	
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODE	= $row->PRJCODE;
		if($myrow == 1)
		{
			$NPRJCODE = $PRJCODE;
		}
		else if($myrow == 2)
		{
			$NPRJCODE = "'$NPRJCODE', '$PRJCODE'";
		}
		else if($myrow > 2)
		{
			$NPRJCODE = "$NPRJCODE, '$PRJCODE'";
		}
	endforeach;
	$PRJCODECOL		= $NPRJCODE;
	//echo "$NPRJCODE";
	//return false;
	$PRJCODECOLLD	= "All";
	$PRJNAMECOLLD	= "All";
}

/*if($vPeriod == "daily")
{
	$StartDate1 = date('Y-m-d',strtotime($Start_Date));
	$EndDate1 	= date('Y-m-d',strtotime($End_Date));
	
	$KONDITT	= "A.AU_STARTD >= '$StartDate1' AND A.AU_ENDD <= '$EndDate1'";
}
elseif($vPeriod == "weekly")
{
	$StartDate1 = date('Y-m-d',strtotime($Start_Date));
	$EndDate1	= date('Y-m-d', strtotime('+7 days', strtotime($StartDate1))); //operasi penjumlahan tanggal sebanyak 7 hari
	
	$KONDITT	= "A.AU_STARTD >= '$StartDate1' AND A.AU_ENDD <= '$EndDate1'";
}
else
{
	$EndDate1 	= date('Y-m-d',strtotime($End_Date));
	
	$KONDITT	= "A.AU_ENDD <= '$EndDate1'";
}*/

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
<style>
	#tex_hz{
		position:absolute;
		-moz-transform:rotate(-90deg);
		-webkit-transform:rotate(-90deg);
		-o-transform:rotate(-90deg);
		-ms-transform:rotate(-90deg);
		transform:rotate(-90deg);
	}
</style>
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
	
	if($LangID == 'IND')
	{
		$daftar_Trafic	= "Daftar Lalu Lintas Alat Dan Barang";	
	}
	else
	{
		$daftar_Trafic	= "Equipment And Goods Traffic";	
	}
?>

<body>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="20%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="60%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td colspan="3" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td colspan="3" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" width="100" height="50"></td>
        <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">&nbsp;</td>
        <td width="14%" class="style2" style="text-align:right; font-weight:bold; text-transform:uppercase; font-size:12px">
        <input type="checkbox" name="MOBILISASI"> <span style="vertical-align:top">MOBILISASI</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td width="6%" class="style2" style="text-align:right; font-weight:bold; text-transform:uppercase; font-size:12px">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="style2" style="text-align:center; font-size:12px">&nbsp;</td>
        <td valign="top" class="style2" style="text-align:right; font-size:12px;font-weight:bold;">
        <input type="checkbox" name="DEMOBILISASI"> <span style="vertical-align:top">DEMOBILISASI</span>
        </td>
        <td valign="top" class="style2" style="text-align:right; font-size:12px;font-weight:bold;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="5" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><?php echo $daftar_Trafic; ?></td>
    </tr>
    <tr>
        <td colspan="5" class="style2" style="text-align:center; font-size:12px">
        No............/DLLAB/................../....../.......
        </td>
    </tr>
    <tr>
      <td colspan="5" class="style2" style="vertical-align:central" align="center">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="5" class="style2" style="text-align:left;">
            <table width="100%">
                <tr style="text-align:left;">
                  <td nowrap valign="top">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td width="14%" rowspan="5" nowrap style="border-top-style:solid; border-top-color:#000; border-top-width:2px;border-left-style:solid; border-left-color:#000; border-left-width:2px;border-bottom-style:solid; border-bottom-color:#000; border-bottom-width:2px;border-right-style:solid; border-right-color:#000; border-right-width:2px">
                  <table width="100%">
                  	<tr>
                    	<td><span class="style2" style="text-align:left; font-weight:bold; font-size:14px">Legenda PIC</span></td>
                    </tr>
                    <tr>
                    	<td>
                        <span class="style2" style="text-align:left; font-size:12px">
                        V = ________________
                        </span>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        <span class="style2" style="text-align:left; font-size:12px">
                        O =________________
                        </span>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        <span class="style2" style="text-align:left; font-size:12px">
                        Ø =________________
                        </span>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        <span class="style2" style="text-align:left; font-size:12px">
                        # = ________________
                        </span>
                        </td>
                    </tr>
                  </table>
                  </td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="12%" nowrap valign="top"><?php echo "$ProjectName"; ?></td>
                    <td width="1%">:</td>
                    <td width="28%"><span class="style2" style="text-align:left; font-style:italic"><?php echo "$PRJNAMECOLLD"; ?></span></td>
                    <td width="13%">Tanggal Kirim</td>
                    <td width="1%">:</td>
                    <td width="31%" nowrap><?=$SendDate?></td>
                </tr>
                
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">Lokasi</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php //echo $PRJNAMECOLLD;?>&nbsp;</span></td>
                  <td>Tanggal Terima</td>
                  <td>:</td>
                  <td><?=$ReceiptDate?></td>
              </tr>
              
              <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">Kepala Proyek</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php //echo $PRJNAMECOLLD;?>&nbsp;</span></td>
                  <td>Penerima</td>
                  <td>:</td>
                  <td>&nbsp;</td>
              </tr>
                <tr style="text-align:left; font-style:italic">
                  <td height="21" valign="top" nowrap>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td><span class="style2" style="text-align:left; font-style:italic">&nbsp;</span></td>
                  <td>Asal Pengirim</td>
                  <td>:</td>
                  <td><?php echo $PRJNAMECOLLD; ?>;</td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="5" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="5" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="2%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
                  <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Deskripsi</td>
                  <td width="24%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.PO</td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Volume</td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Satuan</td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Berat<br>(Kg)</td>
                  <td width="8%" colspan="3" nowrap style="text-align:center; font-weight:bold">Kondisi</td>
                  <td width="9%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.Inventaris</td>
                  <td width="26%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Legenda PIC</td>
              </tr>
                <tr style="background:#CCCCCC">
                  <td nowrap style="text-align:center; font-weight:bold; -moz-transform:rotate(-90deg); -webkit-transform:rotate(-90deg);-o-transform:rotate(-90deg);-ms-transform:rotate(-90deg); transform:rotate(-90deg); padding:0px;" height="50px">Bagus</td>
                  <td nowrap style="text-align:center; font-weight:bold; -moz-transform:rotate(-90deg); -webkit-transform:rotate(-90deg);-o-transform:rotate(-90deg);-ms-transform:rotate(-90deg); transform:rotate(-90deg); padding:0px;" height="50px">Cacat</td>
                  <td nowrap style="text-align:center; font-weight:bold; -moz-transform:rotate(-90deg); -webkit-transform:rotate(-90deg);-o-transform:rotate(-90deg);-ms-transform:rotate(-90deg); transform:rotate(-90deg); padding:0px;" height="50px">Rusak</td>
                </tr>
                <tr>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                </tr>
                <tr>
                  <td nowrap>&nbsp;</td>
                  <td colspan="4" nowrap style="font-weight:bold">Berat Total</td>
                  <td nowrap>&nbsp;</td>
                  <td colspan="5" nowrap>&nbsp;</td>
                </tr>
                <tr>
                  <td height="50" nowrap>&nbsp;</td>
                  <td colspan="10" nowrap style="font-weight:bold; text-align:center">Alat dan Barang yang sudah diterima menjadi tanggung jawab sepenuhnya oleh Penerima</td>
                </tr>
                <tr>
                  <td height="50" colspan="11" nowrap style="vertical-align:text-top">
                  Catatan : .............................................................................................
                  </td>
              </tr>
            </table>
            <table width="100%" border="1" rules="all">
            	<tr style="border-top:hidden">
                	<td width="25%" height="100" style="text-align:center">
                    Pihak Pertama ;<br>
                    <span style="font-weight:bold; font-size:14px"><?php echo $comp_name; ?></span><br><br><br>
                    <u>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </u>
                    <br>
                    <span style="font-weight:bold; font-size:12px">Yang Memberi</span>
                    </td>
                    <td width="29%" height="100" style="text-align:center">
                    Logistik &amp; Alat<br>
                    <span style="font-weight:bold; font-size:14px"><?php echo $comp_name; ?></span><br><br><br>
                    <u>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </u>
                    <br>
                    <span style="font-weight:bold; font-size:12px"> Proyek</span>
                    </td>
                    <td width="29%" height="100" style="text-align:center">
                    Pihak Kedua ;<br>
                    <span style="font-weight:bold; font-size:14px">PT. ..............................</span><br>
                    <br><br>
                    <u>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </u>
                    <br>
                    <span style="font-weight:bold; font-size:12px">Yang Menerima</span>
                  </td>
                    <td width="17%" height="100" style="text-align:left">
                    No. Form : &nbsp;06.R0/LOG/17<br><br>
                    Revisi      : &nbsp;0<br><br>
                    Tanggal   : 1 Juni 2017
                  </td>
                </tr>
            	<tr>
            	  <td height="100" colspan="2" style="text-align:center; vertical-align:central; font-weight:bold">Seluruh Dokumen Daftar Lalu Lintas Alat &amp; Barang Yang Telah Terkirim Ke Proyek Harus Diketahui oleh Project Manager</td>
            	  <td colspan="2" style="text-align:center">
                  Mengetahui,<br><br><br><br>
                  <u>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </u><br>
                    <span style="font-weight:bold; font-size:12px">Project Manager</span>
                  </td>
           	  </tr>
            	<tr>
            	  <td colspan="4">&nbsp;&nbsp;<b>
                  Keterangan :&nbsp;
                  1. Stock  &nbsp;&nbsp;
                  2. Beli Baru &nbsp;&nbsp;
                  3. Beli Bekas
                  </b>
                  </td>
           	  </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>