<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 April 2017
 * File Name	= r_assetlist_report_adm.php
 * Location		= -
*/
if($viewType == 1)
{
	$repDate	= date('YmdHis');
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=ASTL_$repDate.xls");
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
			if($TranslCode == 'AssetListReport')$AssetListReport = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'AssetGroup')$AssetGroup = $LangTransl;
			if($TranslCode == 'AssetName')$AssetName = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Brand')$Brand = $LangTransl;
			if($TranslCode == 'Year')$Year = $LangTransl;
			if($TranslCode == 'HourMeter')$HourMeter = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
	
		endforeach;
	
	?>

<body style="overflow:auto">
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><?php echo $AssetListReport ?></td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php $comp_name 	= $this->session->userdata['comp_name']; echo $comp_name; ?></span></td>
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
                    <td width="8%" height="27" nowrap style="text-align:center; font-weight:bold">No.</td>
                    <td width="6%" nowrap style="text-align:center; font-weight:bold"><?php echo $Code ?></td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $AssetGroup ?></td>
                    <td width="13%" nowrap style="text-align:center; font-weight:bold"><?php echo $AssetName ?></td>
                    <td width="24%" style="text-align:center; font-weight:bold"><?php echo $Description ?> </td>
                    <td width="9%" nowrap style="text-align:center; font-weight:bold"><?php echo $Type ?></td>
                    <td width="12%" nowrap style="text-align:center; font-weight:bold"><?php echo $Brand ?></td>
                    <td width="6%" nowrap style="text-align:center; font-weight:bold"><?php echo $Year ?></td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold"><?php echo $HourMeter ?></td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold"><?php echo $Status ?></td>
              </tr>
                <?php					
                    $therow			= 0;
                    $GTOTAL			= 0;
					if($sortBy == "AS_STAT")
					{
						$sortBy		= "AS_STAT, AS_NAME";
					}
					
					$sqlq0 			= "SELECT * FROM tbl_asset_list WHERE AS_STAT != 9 ORDER BY $sortBy $sortType";												
					$resq0 			= $this->db->query($sqlq0)->result();
					
                    foreach($resq0 as $row0) :
                        $therow			= $therow + 1;
                        $AS_CODE 		= $row0->AS_CODE;
                        $AS_CODE_M 		= $row0->AS_CODE_M;
                        $AG_CODE 		= $row0->AG_CODE;
                        
							$AG_NAME	= '';
							$sqlq1 		= "SELECT AG_NAME FROM tbl_asset_group WHERE AG_CODE = '$AG_CODE' LIMIT 1";
							$resq1 		= $this->db->query($sqlq1)->result();
							foreach($resq1 as $row1) :
								$AG_NAME	= $row1->AG_NAME;
							endforeach;
							
                        $AS_NAME 		= $row0->AS_NAME;
                        $AS_DESC		= $row0->AS_DESC;
                        $AS_TYPECODE	= $row0->AS_TYPECODE;
                        $AS_BRAND		= $row0->AS_BRAND;
                        $AS_CAPACITY	= $row0->AS_CAPACITY;
                        $AS_MACHINE		= $row0->AS_MACHINE;
                        $AS_PRICE		= $row0->AS_PRICE;
                        $AS_YEAR		= $row0->AS_YEAR;
                        $AS_HM			= $row0->AS_HM;
                        $AS_STAT		= $row0->AS_STAT;
						
						$AS_STATD		= '';
						if($AS_STAT == 1)
						{
							$AS_STATD	= "Ready";
							$bgColor	= "";	
						}
						elseif($AS_STAT == 2)
						{
							$AS_STATD	= "In Active";
							$bgColor	= "background:#FF8080";
						}
						elseif($AS_STAT == 3)
						{
							$AS_STATD	= "Used";
							$bgColor	= "background:#9CD68B";
						}
						elseif($AS_STAT == 4)
						{
							$AS_STATD	= "Maintenance";
							$bgColor	= "background:#E2C5E1";	
						}
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$therow"; ?>.</td>
                                <td nowrap style="text-align:left;"><?php echo $AS_CODE_M; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $AG_NAME; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $AS_NAME; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $AS_DESC; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $AS_TYPECODE; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $AS_BRAND; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $AS_YEAR; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($AS_HM, $decFormat); ?></td>
                                <td nowrap style="text-align:center;"><?php echo $AS_STATD; ?></td>
                            </tr>
                        <?php
                    endforeach;
                ?>
      </table>      </td>
    </tr>
</table>
</section>
</body>
</html>