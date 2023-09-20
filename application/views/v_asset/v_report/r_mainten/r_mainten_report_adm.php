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
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
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

if($vPeriod == "daily")
{
	$StartDate1 = date('Y-m-d',strtotime($Start_Date));
	$EndDate1 	= date('Y-m-d',strtotime($End_Date));
	
	$KONDITT	= "A.AM_STARTD >= '$StartDate1' AND A.AM_ENDD <= '$EndDate1'";
}
elseif($vPeriod == "weekly")
{
	$StartDate1 = date('Y-m-d',strtotime($Start_Date));
	$EndDate1	= date('Y-m-d', strtotime('+7 days', strtotime($StartDate1))); //operasi penjumlahan tanggal sebanyak 7 hari
	
	$KONDITT	= "A.AM_STARTD >= '$StartDate1' AND A.AM_ENDD <= '$EndDate1'";
}
else
{
	$EndDate1 	= date('Y-m-d',strtotime($End_Date));
	
	$KONDITT	= "A.AM_ENDD <= '$EndDate1'";
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
		if($TranslCode == 'AssetMaintenReport')$AssetMaintenReport = $LangTransl;
		if($TranslCode == 'ReportUntil')$ReportUntil = $LangTransl;
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
		if($TranslCode == 'Detail')$Detail = $LangTransl;
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><?php echo $AssetMaintenReport ?> (Admin)</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php $comp_name 	= $this->session->userdata['comp_name']; echo $comp_name; ?></span></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top"><?php echo $ReportUntil ?></td>
                    <td width="1%">:</td>
                    <td width="91%"><span class="style2" style="text-align:left; font-style:italic"><?php echo "$EndDate1"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $ProjectCode ?></td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo "$PRJCODECOLLD"; ?></span></td>
              </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $ProjectName ?></td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAMECOLLD;?></span></td>
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
                    <td width="2%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
                  <td width="4%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Project ?></td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $AssetName ?></td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Date ?></td>
                  <td width="19%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Description ?></td>
                  <td colspan="5" style="text-align:center; font-weight:bold">
				  	<?php 
						if($LangID == 'IND')
							echo "$Detail $Expenses";
						else
							echo "$Expenses $Detail";
					?>
                  </td>
              </tr>
                <tr style="background:#CCCCCC">
                  <td width="4%" nowrap style="text-align:center; font-weight:bold"><?php echo $Start ?></td>
                  <td width="4%" nowrap style="text-align:center; font-weight:bold"><?php echo $End ?></td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $SparePart ?></td>
                  <td width="6%" style="text-align:center; font-weight:bold"><?php echo $Fuel ?></td>
                  <td width="6%" nowrap style="text-align:center; font-weight:bold"><?php echo $Oil ?></td>
                  <td width="7%" nowrap style="text-align:center; font-weight:bold"><?php echo $FastMoving ?></td>
                  <td width="7%" nowrap style="text-align:center; font-weight:bold"><?php echo $Total ?></td>
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
						$sqlq0 			= "SELECT A.AM_CODE, A.AM_AS_CODE, A.AM_DATE, A.AM_PRJCODE, A.AM_DESC, A.AM_STARTD,
										  A.AM_ENDD, A.AM_STARTT, A.AM_ENDT, A.AM_STAT, A.AM_PROCS, A.AM_PROCD, A.AM_PROCT, 
										  C.ISFASTM, C.ISFUEL, C.ISLUBRIC, C.ISPART,
										  B.ITM_CODE, B.ITM_KIND, B.ITM_PRICE, B.ITM_QTY, B.ITM_QTY_P, B.ITM_TOTAL, B.ITM_UNIT, 
										  B.NOTES, C.ITM_DESC,
										  D.Unit_Type_Code, D.UMCODE, D.Unit_Type_Name
										  FROM tbl_asset_mainten A
										  INNER JOIN tbl_asset_maintendet B ON B.AM_CODE = A.AM_CODE
										  AND B.AM_PRJCODE = A.AM_PRJCODE
										  INNER JOIN tbl_item C ON C.ITM_CODE = B.ITM_CODE
										  INNER JOIN tbl_unittype D ON D.UMCODE = B.ITM_UNIT
										  WHERE B.AM_PRJCODE IN ($PRJCODECOL) AND $KONDITT ORDER BY A.AM_DATE";
						$resq0 			= $this->db->query($sqlq0)->result();
                    }
                    else
                    {
						$sqlq0 			= "SELECT A.AM_CODE, A.AM_AS_CODE, A.AM_DATE, A.AM_PRJCODE, A.AM_DESC, A.AM_STARTD,
										  A.AM_ENDD, A.AM_STARTT, A.AM_ENDT, A.AM_STAT, A.AM_PROCS, A.AM_PROCD, A.AM_PROCT,
										  C.ISFASTM, C.ISFUEL, C.ISLUBRIC, C.ISPART, 
										  B.ITM_CODE, B.ITM_KIND, B.ITM_PRICE, B.ITM_QTY, B.ITM_QTY_P, B.ITM_TOTAL, B.ITM_UNIT, 
										  B.NOTES, C.ITM_DESC,
										  D.Unit_Type_Code, D.UMCODE, D.Unit_Type_Name
										  FROM tbl_asset_mainten A
										  INNER JOIN tbl_asset_maintendet B ON B.AM_CODE = A.AM_CODE
										  AND B.AM_PRJCODE = A.AM_PRJCODE
										  INNER JOIN tbl_item C ON C.ITM_CODE = B.ITM_CODE
										  INNER JOIN tbl_unittype D ON D.UMCODE = B.ITM_UNIT
										  WHERE $KONDITT ORDER BY A.AM_DATE";
						$resq0 			= $this->db->query($sqlq0)->result();
                    }
                    
                    foreach($resq0 as $rowsqlq0) :
                        $therow			= $therow + 1;
                        $AM_CODE 		= $rowsqlq0->AM_CODE;	
						
						$AM_AS_CODE	= $rowsqlq0->AM_AS_CODE;
                        
							$AS_NAME	= '';
							$AS_DESC	= '';
							$sqlq3a 	= "SELECT AS_NAME, AS_DESC FROM tbl_asset_list WHERE AS_CODE = '$AM_AS_CODE' LIMIT 1";
							$resq3a 	= $this->db->query($sqlq3a)->result();
							foreach($resq3a as $rowq3a) :
								$AS_NAME	= $rowq3a->AS_NAME;
								$AS_DESC	= $rowq3a->AS_DESC;
							endforeach;
							
                        $AM_DATE 		= $rowsqlq0->AM_DATE;
						$AM_PRJCODE 	= $rowsqlq0->AM_PRJCODE;
						$AM_DESC		= $rowsqlq0->AM_DESC;
						$AM_STARTD		= $rowsqlq0->AM_STARTD;
						$AM_STARTD 		= date('d/m/Y H:i',strtotime($AM_STARTD));
						$AM_ENDD		= $rowsqlq0->AM_ENDD;
						$AM_ENDD 		= date('d/m/Y H:i',strtotime($AM_ENDD));
						$AM_PROCD		= $rowsqlq0->AM_PROCD;
						$AM_PROCT		= $rowsqlq0->AM_PROCT;
						$AM_PROCS		= $rowsqlq0->AM_PROCS;
						$AM_PROCD		= "$AM_PROCD $AM_PROCT";
						$AM_PROCD 		= date('d/m/Y H:i',strtotime($AM_PROCD));
						$ITM_TOTAL		= $rowsqlq0->ITM_TOTAL;
							
                        $ISFUEL			= $rowsqlq0->ISFUEL;
						$ISLUBRIC		= $rowsqlq0->ISLUBRIC;
                        $ISFASTM		= $rowsqlq0->ISFASTM;
						$ISPART			= $rowsqlq0->ISPART;
						
						if($ISFUEL == 1)
							$ISFUELP	= $ITM_TOTAL;
						else
							$ISFUELP	= 0;
							
						if($ISLUBRIC == 1)
							$ISLUBRICP	= $ITM_TOTAL;
						else
							$ISLUBRICP	= 0;
							
						if($ISFASTM == 1)
							$ISFASTMP	= $ITM_TOTAL;
						else
							$ISFASTMP	= 0;
							
						if($ISPART == 1)
							$ISPARTP	= $ITM_TOTAL;
						else
							$ISPARTP	= 0;
							
						$AM_TOTAL		= $ISPARTP + $ISFUELP + $ISLUBRICP + $ISFASTMP;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$therow"; ?>.</td>
                                <td nowrap style="text-align:left;"><?php echo $AM_PRJCODE; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $AS_NAME; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $AM_STARTD; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $AM_ENDD; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $AM_DESC; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ISPARTP, $decFormat); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ISFUELP, $decFormat); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ISLUBRICP, $decFormat); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ISFASTMP, $decFormat); ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($AM_TOTAL, $decFormat); ?></td>
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