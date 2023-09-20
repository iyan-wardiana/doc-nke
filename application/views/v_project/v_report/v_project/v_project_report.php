<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 14 Februari 2018
    * File Name	= r_supplier_report.php
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
$comp_name 	= $this->session->userdata['comp_name'];
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
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
		endforeach;
	?>
	<body style="overflow:auto">
		<section class="content">
		    <table width="100%" border="0" style="size:auto">
				<tr>
					<td colspan="3" class="style2" style="text-align:left; font-weight:bold;">
						<div id="Layer1">
					        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
					        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
					        <a href="#" onClick="window.close();" class="button"> close </a>
					    </div>
					</td>
				</tr>
				<tr>
					<td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
					<td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?><br><span class="style2" style="text-align:center; font-weight:bold; font-size:12px; display:none"><?php echo $comp_name; ?></span></td>
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
			                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NO.</td>
			                    <td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">KODE</td>
			                    <td width="16%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NAMA PROYEK</td>
			                    <td width="11%" rowspan="2" style="text-align:center; font-weight:bold">NO. KONTRAK</td>
			                    <td width="9%" rowspan="2" style="text-align:center; font-weight:bold">LOKASI</td>
			                    <td width="22%" rowspan="2" style="text-align:center; font-weight:bold">PEMILIK</td>
			                    <td colspan="2" style="text-align:center; font-weight:bold">TANGGAL </td>
			                    <td width="14%" rowspan="2" style="text-align:center; font-weight:bold">NILAI PROYEK</td>
			                    <td width="6%" rowspan="2" style="text-align:center; font-weight:bold">STATUS</td>
			              </tr>
			                <tr style="background:#CCCCCC">
			                  <td width="7%" style="text-align:center; font-weight:bold">MULAI</td>
			                  <td width="7%" style="text-align:center; font-weight:bold">SELESAI</td>
			                </tr>
			                <?php
								if($SuppStat == 2)
								{
									$FILTER_1	= "";
								}
								else
								{
									$FILTER_1	= "WHERE PRJSTAT = $SuppStat";
								}
								
								if($SortBy == 'NM')
								{
									$FILTER_2	= "ORDER BY PRJNAME ASC";
								}
								else
								{
									$FILTER_2	= "ORDER BY PRJCODE ASC";
								}
								
								$sql1 		= "SELECT PRJCODE, PRJCNUM, PRJNAME, PRJLOCT, PRJOWN, PRJDATE, PRJEDAT, PRJCOST, PRJSTAT
												FROM tbl_project
												$FILTER_1
												$FILTER_2";
								$res1 		= $this->db->query($sql1)->result();
								
								$therow		= 0;
								foreach($res1 as $row1) :
									$therow		= $therow + 1;
									$PRJCODE 	= $row1->PRJCODE;
									$PRJCNUM 	= $row1->PRJCNUM;
									$PRJNAME 	= $row1->PRJNAME;
									$PRJLOCT 	= $row1->PRJLOCT;
									$PRJOWN 	= $row1->PRJOWN;
									$PRJDATE 	= $row1->PRJDATE;
									$PRJEDAT 	= $row1->PRJEDAT;
									$PRJCOST 	= $row1->PRJCOST;
									$PRJSTAT 	= $row1->PRJSTAT;
									if($PRJSTAT == 1)
										$PRJSTATD	= "Aktif";
									else
										$PRJSTATD	= "In Aktif";
									
									$OWN_NAMECOMP	= '';	
									$sql2			= "SELECT own_Title, own_Name 
														FROM tbl_owner WHERE own_Code = '$PRJOWN'";
									$res2 			= $this->db->query($sql2)->result();
									foreach($res2 as $row2) :
										$own_Title 	= $row2->own_Title;
										$own_Name 	= $row2->own_Name;
										if($own_Title == '')
											$OWN_NAMECOMP	= "$own_Name";
										if($own_Title != '')
											$OWN_NAMECOMP	= "$own_Title.$own_Name";								
									endforeach;
									?>
									<tr>
										<td nowrap style="text-align:left;"><?php echo "$therow."; ?></td>
										<td nowrap style="text-align:left;"><?php echo $PRJCODE; ?>&nbsp;</td>
										<td nowrap style="text-align:left;"><?php echo $PRJNAME; ?></td>
										<td nowrap style="text-align:left;"><?php echo $PRJCNUM; ?></td>
										<td nowrap style="text-align:left;"><?php echo $PRJLOCT; ?></td>
										<td nowrap style="text-align:left;"><?php echo $OWN_NAMECOMP; ?></td>
										<td nowrap style="text-align:center;"><?php echo $PRJDATE; ?></td>
										<td nowrap style="text-align:center;"><?php echo $PRJEDAT; ?>&nbsp;</td>
										<td nowrap style="text-align:right;"><?php echo number_format($PRJCOST, 2); ?>&nbsp;</td>
										<td nowrap style="text-align:center;"><?php echo $PRJSTATD; ?>&nbsp;</td>
									</tr>
									<?php
								endforeach;
			                ?>
			            </table>
				  	</td>
			    </tr>
			</table>
		</section>
	</body>
</html>