<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Sept. 2017
 * File Name	= r_product_report.php
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

$LangID 	= $this->session->userdata['LangID'];
$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl	= $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode	= $rowTransl->MLANG_CODE;
	$LangTransl	= $rowTransl->LangTransl;
	
	if($TranslCode == 'AstCostReport')$AstCostReport = $LangTransl;
	if($TranslCode == 'Project')$Project = $LangTransl;
	if($TranslCode == 'AstName')$AstName = $LangTransl;
	if($TranslCode == 'Date')$Date = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
	if($TranslCode == 'Amount')$Amount = $LangTransl;
	if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
	if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
	if($TranslCode == 'Notes')$Notes = $LangTransl;
	if($TranslCode == 'Start')$Start = $LangTransl;
	if($TranslCode == 'End')$End = $LangTransl;
	if($TranslCode == 'Volume')$Volume = $LangTransl;
	if($TranslCode == 'Unit')$Unit = $LangTransl;
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $AstCostReport; ?> (<?php echo $CFTyped; ?>) </td>
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
                    <td width="8%" nowrap valign="top">Periode</td>
                    <td width="1%">:</td>
                    <td width="91%">
                    <span class="style2" style="text-align:left; font-style:italic">
						<?php echo "$StartDateV to $EndDateV"; ?>
                    </span>
                    </td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $Project; ?></td>
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
                    <td width="4%" nowrap style="text-align:center; font-weight:bold" height="35">No.</td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Project; ?></td>
                    <td width="15%" nowrap style="text-align:center; font-weight:bold"><?php echo $AstName; ?></td>
                    <td width="30%" nowrap style="text-align:center; font-weight:bold"><?php echo $Description; ?></td>
                    <td width="25%" nowrap style="text-align:center; font-weight:bold"><?php echo $JobDescription ?></td>
                    <td width="13%" nowrap style="text-align:center; font-weight:bold"><?php echo $Amount; ?> (Rp)</td>
                    <td width="5%" style="text-align:center; font-weight:bold;" nowrap>Status</td>
                </tr>
                <?php
					$therow		= 0;
					if($viewProj == 0)	// PER PROJECT
					{
						$sqlqC 			= "tbl_asset_expd
											WHERE 
												PRJCODE IN ($PRJCODECOL)
												AND ASEXP_DATE >= '$StartDate' AND ASEXP_DATE <= '$EndDate'";
						$sqlq0 			= "SELECT
													A.ASEXP_CODE, A.ASEXP_DATE, A.PRJCODE, A.JOBCODEID, A.ASEXP_AMOUNT,
													B.ASEXP_NOTE, B.ASEXP_STAT,
													C.AS_NAME
												FROM
													tbl_asset_expd A
												INNER JOIN tbl_asset_exph B ON A.ASEXP_CODE = B.ASEXP_CODE
												INNER JOIN tbl_asset_list C ON A.AS_CODE = C.AS_CODE
											WHERE 
												A.PRJCODE IN ($PRJCODECOL)
												AND A.ASEXP_DATE >= '$StartDate' AND A.ASEXP_DATE <= '$EndDate'
											ORDER BY ASEXP_DATE";
					}
					else // ALL PROJECT
					{
						$sqlqC 			= "tbl_asset_rcost
											WHERE
												RASTC_ASTCODE IN ($ASTCODECOL)
												AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'";
						$sqlq0 			= "SELECT * FROM tbl_asset_rcost
											WHERE
												RASTC_ASTCODE IN ($ASTCODECOL)
												AND RASTC_DATE >= '$StartDate' AND RASTC_DATE <= '$EndDate'
											ORDER BY RASTC_DATE";
					}
					$resqC 			= $this->db->count_all($sqlqC);
					$resq0 			= $this->db->query($sqlq0)->result();
					
					$ASEXP_AMOUNTT	= 0;
					if($resqC > 0)
					{
						?>
                            <tr style="font-weight:bold">
                                <td colspan="7" nowrap style="text-align:center;">&nbsp;</td>
                            </tr>
                        <?php
						foreach($resq0 as $rowsqlq0) :
							$therow			= $therow + 1;
							$ASEXP_CODE		= $rowsqlq0->ASEXP_CODE;
							$ASEXP_DATE 	= $rowsqlq0->ASEXP_DATE;
							$PRJCODE		= $rowsqlq0->PRJCODE;
							$JOBCODEID		= $rowsqlq0->JOBCODEID;
							$ASEXP_NOTE		= $rowsqlq0->ASEXP_NOTE;
							$ASEXP_STAT		= $rowsqlq0->ASEXP_STAT;
							$AS_NAME		= $rowsqlq0->AS_NAME;
							$ASEXP_DATE 	= date('d/m/Y',strtotime($ASEXP_DATE));								
							$ASEXP_AMOUNT	= $rowsqlq0->ASEXP_AMOUNT;
							$ASEXP_AMOUNTT	= $ASEXP_AMOUNTT + $ASEXP_AMOUNT;
							
							$JOBDESC		= "-";
							$getJOBD 		= "SELECT JOBDESC FROM tbl_joblist
												WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
							$resJOBD 		= $this->db->query($getJOBD)->result();
							foreach($resJOBD as $rowJD):
								$JOBDESC	= $rowJD->JOBDESC;
							endforeach;
							
							if($ASEXP_STAT == 0)
							{
								$STATDESC 	= 'fake';
								$STATCOL		= 'danger';
							}
							elseif($ASEXP_STAT == 1)
							{
								$STATDESC 	= 'New';
								$STATCOL	= 'warning';
							}
							elseif($ASEXP_STAT == 2)
							{
								$STATDESC 	= 'Confirm';
								$STATCOL	= 'primary';
							}
							elseif($ASEXP_STAT == 3)
							{
								$STATDESC 	= 'Approved';
								$STATCOL	= 'success';
							}
							elseif($ASEXP_STAT == 4)
							{
								$STATDESC 	= 'Revise';
								$STATCOL	= 'danger';
							}
							elseif($ASEXP_STAT == 5)
							{
								$STATDESC 	= 'Rejected';
								$STATCOL	= 'danger';
							}
							elseif($ASEXP_STAT == 6)
							{
								$STATDESC 	= 'Close';
								$STATCOL	= 'danger';
							}
							else
							{
								$STATDESC 	= 'Awaiting';
								$STATCOL	= 'warning';
							}
							?>
								<!-- HEADER -->
								<tr>
									<td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
									<td nowrap style="text-align:left;"><?php echo $PRJCODE; ?></td>
									<td nowrap style="text-align:left;"><?php echo $AS_NAME; ?></td>
									<td nowrap style="text-align:left;"><?php echo $ASEXP_NOTE; ?></td>
									<td nowrap style="text-align:left;"><?php echo $JOBDESC; ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($ASEXP_AMOUNT, 2); ?></td>
									<td nowrap style="text-align:left;">
                                    	<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                            <?php 
                                                echo "&nbsp;&nbsp;$STATDESC&nbsp;&nbsp;";
                                             ?>
                                        </span>
                                    </td>
								</tr>
							<?php
						endforeach;
						?>
							<tr>
								<td colspan="5" nowrap style="text-align:center; font-style:italic">&nbsp;</td>
								<td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($ASEXP_AMOUNTT, 2); ?></td>
								<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
							</tr>
                    	<?php
					}
					else
					{
						?>
							<tr style="font-size:14px;">
								<td colspan="7" nowrap style="text-align:center; font-style:italic">--- no data found ---</td>
							</tr>
						<?php
					}
                ?>
            </table>
	  </td>
    </tr>
</table>
</section>
</body>
</html>