<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Sept. 2017
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

$StartDate 	= date('Y-m-d',strtotime($Start_Date));
$EndDate 	= date('Y-m-d',strtotime($End_Date));
$StartDateV	= date('d/m/Y',strtotime($Start_Date));
$EndDateV 	= date('d/m/Y',strtotime($End_Date));
$TSRPRJ		= strlen($PRJCODECOL);
if($TSRPRJ == 2)
{
	$PRJCODECOLV	= "All Project";
}
else
{
	$PRJCODECOLV	= str_replace("','",", ",$PRJCODECOL);
	$PRJCODECOLV	= str_replace("'","",$PRJCODECOLV);
}
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
			if($TranslCode == 'AssetProductionReport')$AssetProductionReport = $LangTransl;
			if($TranslCode == 'Periode')$Periode = $LangTransl;
			if($TranslCode == 'ProjectCode')$ProjectCode = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'AssetName')$AssetName = $LangTransl;
			if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Start')$Start = $LangTransl;
			if($TranslCode == 'End')$End = $LangTransl;
			if($TranslCode == 'Hour')$Hour = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'TotalExpenses')$TotalExpenses = $LangTransl;
			if($TranslCode == 'RAP')$RAP = $LangTransl;
	
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><?php echo $AssetProductionReport ?> (<?php echo $CFTyped; ?>) ADMIN</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php $comp_name 	= $this->session->userdata['comp_name']; echo $comp_name; ?></span></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
        	<table width="100%">
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top"><?php echo $Periode ?></td>
                    <td width="1%">:</td>
                    <td width="91%">
                    <span class="style2" style="text-align:left; font-style:italic">
						<?php echo "$StartDateV to $EndDateV"; ?>
                    </span>
                    </td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $ProjectCode ?></td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo "$PRJCODECOLV"; ?></span></td>
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
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
                    <td width="4%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Project ?></td>
                    <td width="14%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $AssetName ?></td>
                    <td width="31%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $JobDescription ?></td>
                    <td colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Date ?></td>
                    <td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Hour ?>(s)</td>
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Volume ?></td>
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Unit ?></td>
                    <td width="9%" rowspan="2" style="text-align:center; font-weight:bold" nowrap><?php echo $Notes ?></td>
                    <td width="9%" rowspan="2" style="text-align:center; font-weight:bold; display:none" nowrap><?php echo $TotalExpenses ?> <br>(IDR)</td>
                    <td colspan="2" style="text-align:center; font-weight:bold; display:none"><?php echo $RAP ?></td>
              	</tr>
                <tr style="background:#CCCCCC">
                    <td width="6%" nowrap style="text-align:center; font-weight:bold"><?php echo $Start ?></td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold"><?php echo $End ?></td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold; display:none">/ <?php echo $Hour ?>(s)</td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold; display:none">/ <?php echo $Volume ?></td>
                </tr>
                <?php
					//$CFType	= 1;
					if($CFType == 1) // 1. Detail, 2. Sumamry
					{
						$therow		= 0;
						
						if($viewAsset == 0)	// PER ASSET
						{
							if($viewProj == 0)	// PER ASSET AND PER PROJECT
							{
								$sqlqC 			= "tbl_asset_rjob
													WHERE 
														RASTC_PRJCODE IN ($PRJCODECOL)
														AND RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'";
								$sqlq0 			= "SELECT * FROM tbl_asset_rjob
													WHERE 
														RASTC_PRJCODE IN ($PRJCODECOL)
														AND RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
													ORDER BY RASTC_DATE";
							}
							else // PER ASSET AND ALL PROJECT
							{
								$sqlqC 			= "tbl_asset_rjob
													WHERE
														RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'";
								$sqlq0 			= "SELECT * FROM tbl_asset_rjob
													WHERE
														RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
													ORDER BY RASTC_DATE";
							}
							$resqC 			= $this->db->count_all($sqlqC);
							$resq0 			= $this->db->query($sqlq0)->result();
						}
						else // ALL ASSET
						{
							if($viewProj == 0) // ALL ASSET AND PER PROJECT
							{
								$sqlqC 			= "tbl_asset_rjob
													WHERE 
														RASTC_PRJCODE IN ($PRJCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'";
								$sqlq0 			= "SELECT * FROM tbl_asset_rjob
													WHERE 
														RASTC_PRJCODE IN ($PRJCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
													ORDER BY RASTC_DATE";
							}
							else // ALL ASSET AND ALL PROJECT
							{
								$sqlqC 			= "tbl_asset_rjob
													WHERE RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'";
								$sqlq0 			= "SELECT * FROM tbl_asset_rjob
													WHERE RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
													ORDER BY RASTC_DATE";
							}
							$resqC 			= $this->db->count_all($sqlqC);
							$resq0 			= $this->db->query($sqlq0)->result();
						}
						$RASTC_PRJCODEB		= '';
						$RASTC_ASTCODEB		= '';
						$RASTC_GTTIME		= 0;
						$RASTC_GTVOL		= 0;
						$RASTC_UNIT			= '';
						if($resqC > 0)
						{
							foreach($resq0 as $rowsqlq0) :
								$therow			= $therow + 1;
								$RASTC_DATE 	= $rowsqlq0->RASTC_DATE;
								$RASTC_PRJCODE	= $rowsqlq0->RASTC_PRJCODE;
								$RASTC_PRJNAME	= $rowsqlq0->RASTC_PRJNAME;
								$RASTC_ASTCODE	= $rowsqlq0->RASTC_ASTCODE;
								$RASTC_ASTDESC	= $rowsqlq0->RASTC_ASTDESC;
								
								$RASTC_STARTDx	= $rowsqlq0->RASTC_STARTD;
								$RASTC_STARTD 	= date('d/m/Y',strtotime($RASTC_STARTDx));
								$RASTC_ENDDx	= $rowsqlq0->RASTC_ENDD;
								$RASTC_ENDD 	= date('d/m/Y',strtotime($RASTC_ENDDx));							
								$RASTC_STARTDT	= date('H:i',strtotime($RASTC_STARTDx));
								$RASTC_ENDDT	= date('H:i',strtotime($RASTC_ENDDx));
								
								$RASTC_QTYTIME	= $rowsqlq0->RASTC_QTYTIME;
								$RASTC_TYPE		= $rowsqlq0->RASTC_TYPE;
								$RASTC_JOBC		= $rowsqlq0->RASTC_JOBC;
								$RASTC_JOBD		= $rowsqlq0->RASTC_JOBD;
								$RASTC_VOL		= $rowsqlq0->RASTC_VOL;
								
								$RASTC_COSTTOT	= $rowsqlq0->RASTC_COSTTOT;
								$RASTC_COSTAVGH	= $rowsqlq0->RASTC_COSTAVGH;
								$RASTC_COSTAVGV	= $rowsqlq0->RASTC_COSTAVGV;
								$RASTC_NOTE		= $rowsqlq0->RASTC_NOTE;
								
								
								if(($RASTC_ASTCODE != $RASTC_ASTCODEB) && $therow > 1)
								{
									$RASTC_GTTIME	= number_format($RASTC_GTTIME, $decFormat);
									$RASTC_GTVOL	= number_format($RASTC_GTVOL, $decFormat);
									?>
										<tr style="font-weight:bold; font-size:14px; background:#CCC">
											<td colspan="6" nowrap style="text-align:left;">&nbsp;</td>
											<td nowrap style="text-align:right;"><?php echo $RASTC_GTTIME; ?></td>
											<td nowrap style="text-align:right;"><?php echo $RASTC_GTVOL; ?></td>
											<td nowrap style="text-align:center;"><?php echo $RASTC_UNIT; ?></td>
											<td colspan="4" nowrap style="text-align:right;">&nbsp;</td>
										</tr>
									<?php
									$RASTC_GTTIME	= 0;
									$RASTC_GTVOL	= 0;
								}
								$RASTC_UNIT		= $rowsqlq0->RASTC_UNIT;
								$RASTC_GTVOL	= $RASTC_GTVOL + $RASTC_VOL;
								$RASTC_GTTIME	= $RASTC_GTTIME + $RASTC_QTYTIME;
								$RASTC_QTYTIME	= number_format($RASTC_QTYTIME, $decFormat);
								$RASTC_VOL		= number_format($RASTC_VOL, $decFormat);
								$RASTC_COSTTOT	= number_format($RASTC_COSTTOT, $decFormat);
								$RASTC_COSTAVGH	= number_format($RASTC_COSTAVGH, $decFormat);
								$RASTC_COSTAVGV	= number_format($RASTC_COSTAVGV, $decFormat);
								?>
									<tr>
										<td nowrap style="text-align:left;"><?php echo "$therow"; ?>.</td>
										<td nowrap style="text-align:left;">
											<?php
												if($RASTC_PRJCODE != $RASTC_PRJCODEB)
													echo $RASTC_PRJCODE;
												else
													echo "";
											?>
										</td>
										<td nowrap style="text-align:left;">
											<?php
												if($RASTC_ASTCODE != $RASTC_ASTCODEB)
													echo $RASTC_ASTDESC;
												else
													echo "";
											?>
										</td>
										<td nowrap style="text-align:left;"><?php echo $RASTC_JOBD; ?></td>
										<td nowrap style="text-align:center;"><?php echo "$RASTC_STARTD<br>$RASTC_STARTDT"; ?></td>
										<td nowrap style="text-align:center;"><?php echo "$RASTC_ENDD<br>$RASTC_ENDDT"; ?></td>
										<td nowrap style="text-align:right;"><?php echo "$RASTC_QTYTIME"; ?></td>
										<td nowrap style="text-align:right;"><?php echo $RASTC_VOL; ?></td>
										<td nowrap style="text-align:center;"><?php echo $RASTC_UNIT; ?></td>
										<td nowrap style="text-align:left;"><?php echo $RASTC_NOTE; ?></td>
										<td nowrap style="text-align:right; display:none"><?php echo $RASTC_COSTTOT; ?></td>
										<td nowrap style="text-align:right; display:none"><?php echo $RASTC_COSTAVGH; ?></td>
										<td nowrap style="text-align:right; display:none"><?php echo $RASTC_COSTAVGV; ?></td>
									</tr>
								<?php
								$RASTC_PRJCODEB	= $RASTC_PRJCODE;
								$RASTC_ASTCODEB	= $RASTC_ASTCODE;
							endforeach;
							$RASTC_GTTIME	= number_format($RASTC_GTTIME, $decFormat);
							$RASTC_GTVOL	= number_format($RASTC_GTVOL, $decFormat);
							?>
								<tr style="font-weight:bold; font-size:14px; background:#CCC">
									<td colspan="6" nowrap style="text-align:left;">&nbsp;</td>
									<td nowrap style="text-align:right;"><?php echo $RASTC_GTTIME; ?></td>
									<td nowrap style="text-align:right;"><?php echo $RASTC_GTVOL; ?></td>
									<td nowrap style="text-align:center;"><?php echo $RASTC_UNIT; ?></td>
									<td colspan="4" nowrap style="text-align:right;">&nbsp;</td>
								</tr>
							<?php
						}
						else
						{
							?>
								<tr style="font-size:14px;">
									<td colspan="13" nowrap style="text-align:center; font-style:italic">--- no data found ---</td>
								</tr>
							<?php
						}
					}
					else
					{
						$therow			= 0;
						$AU_PRJCODE		= "-";
						$AU_DESC		= "-";
						
						if($viewAsset == 0)	// PER ASSET
						{
							if($viewProj == 0)	// PER ASSET AND PER PROJECT
							{
								$sqlqC 		= "tbl_asset_rjob
												WHERE
													RASTC_PRJCODE IN ($PRJCODECOL)
														AND RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE";
								$sqlq0 		= "SELECT RASTC_DATE, RASTC_PRJCODE, RASTC_ASTCODE, RASTC_ASTDESC, RASTC_ASTCODE, RASTC_PRJCODE,
												SUM(RASTC_QTYTIME) AS TOTTIME, SUM(RASTC_VOL) AS TOTVOL, RASTC_UNIT
												FROM tbl_asset_rjob
												WHERE
													RASTC_PRJCODE IN ($PRJCODECOL)
														AND RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE ORDER BY RASTC_PRJCODE";
							}
							else // PER ASSET AND ALL PROJECT
							{
								$sqlqC 		= "tbl_asset_rjob
												WHERE
													RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE";
								$sqlq0 		= "SELECT RASTC_DATE, RASTC_PRJCODE, RASTC_ASTCODE, RASTC_ASTDESC, RASTC_ASTCODE, RASTC_PRJCODE,
												SUM(RASTC_QTYTIME) AS TOTTIME, SUM(RASTC_VOL) AS TOTVOL, RASTC_UNIT
												FROM tbl_asset_rjob
												WHERE
													RASTC_ASTCODE IN ($ASTCODECOL)
														AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE ORDER BY RASTC_PRJCODE";
							}
							$resqC 			= $this->db->count_all($sqlqC);
							$resq0 			= $this->db->query($sqlq0)->result();
						}
						else // ALL ASSET
						{
							if($viewProj == 0) // ALL ASSET AND PER PROJECT
							{
								$sqlqC 		= "tbl_asset_rjob
												WHERE
													RASTC_PRJCODE IN ($PRJCODECOL)
													AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE";
								$sqlq0 		= "SELECT RASTC_DATE, RASTC_PRJCODE, RASTC_ASTCODE, RASTC_ASTDESC, RASTC_ASTCODE, RASTC_PRJCODE,
												SUM(RASTC_QTYTIME) AS TOTTIME, SUM(RASTC_VOL) AS TOTVOL, RASTC_UNIT
												FROM tbl_asset_rjob
												WHERE
													RASTC_PRJCODE IN ($PRJCODECOL)
													AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE ORDER BY RASTC_PRJCODE";
							}
							else // ALL ASSET AND ALL PROJECT
							{
								$sqlqC 		= "tbl_asset_rjob
												WHERE RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE";
								$sqlq0 		= "SELECT RASTC_DATE, RASTC_PRJCODE, RASTC_ASTCODE, RASTC_ASTDESC, RASTC_PRJCODE,
												SUM(RASTC_QTYTIME) AS TOTTIME, SUM(RASTC_VOL) AS TOTVOL, RASTC_UNIT
												FROM tbl_asset_rjob
												WHERE RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
												GROUP BY RASTC_ASTCODE ORDER BY RASTC_PRJCODE";
							}
							$resqC 			= $this->db->count_all($sqlqC);
							$resq0 			= $this->db->query($sqlq0)->result();
						}
						$RASTC_PRJCODEB	= '';
						$RASTC_GTTIME	= 0;
						$RASTC_GTVOL	= 0;
						if($resqC > 0)
						{
							foreach($resq0 as $rowsqlq0) :
								$therow			= $therow + 1;
								$RASTC_DATE		= $rowsqlq0->RASTC_DATE;
								$RASTC_PRJCODE	= $rowsqlq0->RASTC_PRJCODE;
								$RASTC_ASTCODE	= $rowsqlq0->RASTC_ASTCODE;
								$RASTC_ASTDESC	= $rowsqlq0->RASTC_ASTDESC;
								$RASTC_PRJCODE	= $rowsqlq0->RASTC_PRJCODE;					
								$TOTTIME		= $rowsqlq0->TOTTIME;
								$TOTVOL			= $rowsqlq0->TOTVOL;
								$RASTC_UNIT		= $rowsqlq0->RASTC_UNIT;
								
								if(($RASTC_PRJCODE != $RASTC_PRJCODEB) && $therow > 1)
								{
									$RASTC_GTTIME	= number_format($RASTC_GTTIME, $decFormat);
									$RASTC_GTVOL	= number_format($RASTC_GTVOL, $decFormat);
									?>
										<tr style="font-weight:bold; font-size:14px; background:#CCC">
											<td colspan="6" nowrap style="text-align:left;">&nbsp;</td>
											<td nowrap style="text-align:right;"><?php echo $RASTC_GTTIME; ?></td>
											<td nowrap style="text-align:right;"><?php echo $RASTC_GTVOL; ?></td>
											<td nowrap style="text-align:center;"><?php echo $RASTC_UNIT; ?></td>
											<td colspan="4" nowrap style="text-align:right;">&nbsp;</td>
										</tr>
									<?php
									$RASTC_GTTIME	= 0;
									$RASTC_GTVOL	= 0;
								}
								$RASTC_GTVOL	= $RASTC_GTVOL + $TOTVOL;
								$RASTC_GTTIME	= $RASTC_GTTIME + $TOTTIME;
								
								$TOTTIME		= number_format($TOTTIME, $decFormat);
								$TOTVOL			= number_format($TOTVOL, $decFormat);
										
								?>
									<tr>
										<td nowrap style="text-align:left;"><?php echo "$therow"; ?>.</td>
										<td nowrap style="text-align:left;">
											<?php
												if($RASTC_PRJCODE != $RASTC_PRJCODEB)
													echo $RASTC_PRJCODE;
												else
													echo "";
											?>
										</td>
										<td nowrap style="text-align:left;">
											<?php
												echo "$RASTC_ASTCODE - $RASTC_ASTDESC";
											?>
										</td>
										<td nowrap style="text-align:left;"><?php //echo $RASTC_JOBD; ?></td>
										<td nowrap style="text-align:center;"><?php //echo "$RASTC_STARTD<br>$RASTC_STARTDT"; ?></td>
										<td nowrap style="text-align:center;"><?php //echo "$RASTC_ENDD<br>$RASTC_ENDDT"; ?></td>
										<td nowrap style="text-align:right;"><?php echo $TOTTIME; ?></td>
										<td nowrap style="text-align:right;"><?php echo $TOTVOL; ?></td>
										<td nowrap style="text-align:center;"><?php echo $RASTC_UNIT; ?></td>
										<td nowrap style="text-align:left;"><?php //echo $RASTC_NOTE; ?></td>
										<td nowrap style="text-align:right; display:none"><?php echo $RASTC_COSTTOT; ?></td>
										<td nowrap style="text-align:right; display:none"><?php echo $RASTC_COSTAVGH; ?></td>
										<td nowrap style="text-align:right; display:none"><?php echo $RASTC_COSTAVGV; ?></td>
									</tr>
									<?php
									$RASTC_PRJCODEB	= $RASTC_PRJCODE;
							endforeach;
							$RASTC_GTTIME	= number_format($RASTC_GTTIME, $decFormat);
							$RASTC_GTVOL	= number_format($RASTC_GTVOL, $decFormat);
							?>
								<tr style="font-weight:bold; font-size:14px; background:#CCC">
									<td colspan="6" nowrap style="text-align:left;">&nbsp;</td>
									<td nowrap style="text-align:right;"><?php echo $RASTC_GTTIME; ?></td>
									<td nowrap style="text-align:right;"><?php echo $RASTC_GTVOL; ?></td>
									<td nowrap style="text-align:center;"><?php echo $RASTC_UNIT; ?></td>
									<td colspan="4" nowrap style="text-align:right;">&nbsp;</td>
								</tr>
							<?php
						}
						else
						{
							?>
								<tr style="font-size:14px;">
									<td colspan="13" nowrap style="text-align:center; font-style:italic">--- no data found ---</td>
								</tr>
							<?php
						}
					}
                ?>
            </table>
	  </td>
    </tr>
</table>
</section>
</body>
</html>