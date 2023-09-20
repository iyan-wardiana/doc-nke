<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Oktober 2017
 * File Name	= v_project_bill_report_adm.php
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
	$decFormat 	= $row->decFormat;
	$decFormat2 = $row->decFormat;
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
        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:18px">LAPORAN TAGIHAN PROYEK<br>
          <span class="style2" style="text-align:center; font-weight:bold; font-size:12px">PT SASMITO INFRA</span></td>
  </tr>
    <?php
            //n$StartDate1 = date('Y/m/d',strtotime($Start_Date));
            //n$EndDate1 = date('Y/m/d',strtotime($End_Date));
			//n$DOCTYPE1 = $TYPE;
			//if($TYPE==1)
			//	$DOCTYPE1 = "Asli";
			//elseif($TYPE==2)
			//	$DOCTYPE1 = "Copy";
			//else
			//	$DOCTYPE1 = "All";			
        ?>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
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
                    <td width="91%"><?php echo "$PRJCODECOLLD"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
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
        	<table width="100%" border="1" style="font-size:10px" rules="all">
          <tr style="background:#CCCCCC">
                    <td width="2%" rowspan="2" style="text-align:center; font-weight:bold"> NO.</td>
                    <td width="3%" rowspan="2" style="text-align:center; font-weight:bold">URAIAN</td>
                    <td height="20" colspan="4" style="text-align:center; font-weight:bold">MC</td>
                    <td colspan="5" style="text-align:center; font-weight:bold">KWITANSI</td>
                    <td width="6%" rowspan="2" style="text-align:center; font-weight:bold">PROG.</td>
                  <td width="7%" rowspan="2" style="text-align:center; font-weight:bold" nowrap>NOMOR FAKTUR</td>
                  <td width="5%" rowspan="2" style="text-align:center; font-weight:bold">TT<br />
                  OWNER</td>
<td width="5%" rowspan="2" style="text-align:center; font-weight:bold">Tgl. Jatuh <br />
                      Tempo</td>
            <td colspan="4" style="text-align:center; font-weight:bold">REALISASI</td>
                    <td width="4%" rowspan="2" style="text-align:center; font-weight:bold">DEVISA</td>
            <td width="6%" rowspan="2" style="text-align:center; font-weight:bold">KET</td>
              </tr>
                <tr style="background:#CCCCCC">
                  <td width="1%" style="text-align:center; font-weight:bold">NO</td>
                  <td width="2%" style="text-align:center; font-weight:bold">BULAN</td>
                  <td width="3%" style="text-align:center; font-weight:bold">PROG</td>
                  <td width="3%" style="text-align:center; font-weight:bold">NILAI</td>
                  <td width="4%" style="text-align:center; font-weight:bold">NO.</td>
                  <td width="5%" style="text-align:center; font-weight:bold">TGL.</td>
                  <td width="7%" style="text-align:center; font-weight:bold">DPP</td>
                  <td width="7%" style="text-align:center; font-weight:bold">PPN</td>
                  <td width="9%" style="text-align:center; font-weight:bold">NILAI</td>
                  <td width="5%" style="text-align:center; font-weight:bold">TGL.</td>
                  <td width="5%" style="text-align:center; font-weight:bold">NILAI</td>
                  <td width="5%" style="text-align:center; font-weight:bold">PPH</td>
                  <td width="6%" style="text-align:center; font-weight:bold" nowrap>POT. LAIN</td>
              </tr>
                <?php			
                    $sqlPRJ		= "SELECT A.PINV_STEP, A.PINV_SOURCE, A.PINV_CAT, A.PINV_CODE, A.PINV_MANNO, A.PINV_DATE,
										A.PINV_ENDDATE AS PINV_TTODate, A.PINV_ENDDATE AS PINV_TTODDate, 
										A.PINV_PROG, A.PINV_PROGVAL, A.PINV_PROGVALPPn,
										A.PINV_DPPER, A.PINV_DPVAL,
										MONTH(A.PINV_DATE) AS MC_DATEM,
										A.PINV_MANNO AS PINV_PPnNoM, A.PINV_STEP,
										A.PINV_PROGVAL AS PINV_KwitAm, A.PINV_PROGVALPPn AS PINV_KwitAmPPn,
										B.PRINV_Date, 0 as RealINVAmount, 0 as RealINVAmountPPh, 0 as RealINVOtherAm, 0 as PRINV_Deviation, 0 as PRINV_Notes
									FROM tbl_projinv_header A
										LEFT JOIN tbl_projinv_realh B ON A.PINV_CODE = B.PINV_Number
									WHERE A.PRJCODE = '$PRJCODECOLL' ORDER BY isDP, PINV_STEP ASC";
                    // count data
                        $resultCount = $this->db->where('PRJCODE', $PRJCODECOLL);
                        $resultCount = $this->db->count_all_results('tbl_projinv_header');
                    // End count data
                    $resultPRJ = $this->db->query($sqlPRJ)->result();
                    if($resultCount > 0)
                    {
                        $i = 0;
                        $MRNumber2 	= '';
						$uraian2	= '';
						$PINV_CODE2	= '';
						$PINV_MANNO2= '';
						$PINV_DATE	= '';
                        foreach($resultPRJ as $rowPRJ) :
                            $PINV_STEP 		= $rowPRJ->PINV_STEP;
                            $PINV_SOURCE	= $rowPRJ->PINV_SOURCE;
                            $PINV_CAT 		= $rowPRJ->PINV_CAT;
                            $PINV_STEP 		= $rowPRJ->PINV_STEP;
                            $MC_DATEM 		= $rowPRJ->MC_DATEM;
                            $PINV_DPPER		= $rowPRJ->PINV_DPPER;
                            $PINV_DPVAL		= $rowPRJ->PINV_DPVAL;
							if($PINV_CAT == 1)
							{
								$uraian			= "DP $PINV_STEP";
								$PINV_PROGx		= '';
								$PINV_SOURCE	= "DP";
								$MC_DATEM		= $MC_DATEM;
								$MC_PROGAPP		= $PINV_DPPER;
								$MC_PROGAPPVAL	= $PINV_DPVAL;
								$MC_PROGAPPVAL2	= $MC_PROGAPPVAL * 0.1;
								$TOTPINV_PROGVAL= $MC_PROGAPPVAL + $MC_PROGAPPVAL2;
							}
							else
							{
								$uraian		= "TM $PINV_STEP";
								$PINV_PROG 	= $rowPRJ->PINV_PROG;
								$PINV_PROGy	= number_format($PINV_PROG, $decFormat2);
								$PINV_PROGx	= "$PINV_PROGy";
								$MC_DATEM		= '';
								$MC_PROGAPP		= 0;
								$MC_PROGAPPVAL	= 0;
								$sqlMC			= "SELECT MONTH(MC_DATE) AS MC_DATEM, MC_PROGAPP, MC_PROGAPPVAL
													FROM tbl_mcheader WHERE MC_CODE = '$PINV_SOURCE' LIMIT 1";
								$resMC			= $this->db->query($sqlMC)->result();
								foreach($resMC as $rowMC) :
									$MC_DATEM 		= $rowMC->MC_DATEM;
									$MC_PROGAPP 	= $rowMC->MC_PROGAPP;
									$MC_PROGAPPVAL 	= $rowMC->MC_PROGAPPVAL;
									$MC_PROGAPPVAL2	= $MC_PROGAPPVAL * 0.1;
									$TOTPINV_PROGVAL= $MC_PROGAPPVAL + $MC_PROGAPPVAL2;
								endforeach;
							}
								
                            $PINV_CODE		= $rowPRJ->PINV_CODE;
                            $PINV_MANNO		= $rowPRJ->PINV_MANNO;
                            $PINV_DATEx			= $rowPRJ->PINV_DATE;
							$PINV_DATE			= date('d-M-y',strtotime($PINV_DATEx));
                            $PINV_TTODatex		= $rowPRJ->PINV_TTODate;
							$PINV_TTODate		= date('d-M-y',strtotime($PINV_TTODatex));
                            $PINV_TTODDatex		= $rowPRJ->PINV_TTODDate;
							$PINV_TTODDate		= date('d-M-y',strtotime($PINV_TTODDatex));
                            $PINV_PROGVAL		= $rowPRJ->PINV_PROGVAL;
                            $PINV_PROGVALPPn	= $rowPRJ->PINV_PROGVALPPn;
                            $totAcchievAmount	= $PINV_PROGVAL + $PINV_PROGVALPPn;
                            $PINV_PPnNoM		= $rowPRJ->PINV_PPnNoM;
                            $PRINV_Datex		= $rowPRJ->PRINV_Date;
							if($PRINV_Datex == '')
							{
								$PRINV_Date			= '';
							}
							else
							{
								$PRINV_Date			= date('d-M-y',strtotime($PRINV_Datex));
							}
                            $RealINVAmount		= $rowPRJ->RealINVAmount;
                            $RealINVAmountPPh	= $rowPRJ->RealINVAmountPPh;
                            $RealINVOtherAm		= $rowPRJ->RealINVOtherAm;
                            $PINV_KwitAm		= $rowPRJ->PINV_KwitAm;
                            $PINV_KwitAmPPn		= $rowPRJ->PINV_KwitAmPPn;
                            //$totPINV_KwitAm		= $PINV_KwitAm + $PINV_KwitAmPPn;
                           // $TOTPINV_PROGVAL	= $PINV_KwitAm + $PINV_KwitAmPPn;
                            $PRINV_Deviation	= $rowPRJ->PRINV_Deviation;
                            $PRINV_Notes		= $rowPRJ->PRINV_Notes;
							
                            ?>
                        <tr>
                            <td width="2%" nowrap>&nbsp;
								<?php
									if($PINV_MANNO != $PINV_MANNO2)
									{
                            			$currentRow  = ++$i;
										echo "$currentRow."; 
									}
								?>                            </td>
                            <td width="3%" nowrap>&nbsp;
								<?php 
									if($uraian != $uraian2)
									{
										echo $uraian; 
									}
								?>                            </td>
                            <td style="text-align:left"><?php echo $PINV_SOURCE; ?></td>
                            <td style="text-align:center"><?php echo $MC_DATEM; ?></td>
                            <td style="text-align:right"><?php echo number_format($MC_PROGAPP, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right"><?php echo number_format($MC_PROGAPPVAL, $decFormat); ?>&nbsp;</td>
                            <td nowrap>&nbsp;
								<?php
									if($PINV_CODE != $PINV_CODE2)
									{
										echo $PINV_CODE; 
									}
								?>                            </td>
                            <td nowrap>&nbsp;
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										echo $PINV_DATE; 
									}
								?>                            </td>
                            <td style="text-align:right" nowrap>&nbsp;
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										//echo number_format($PINV_KwitAm, $decFormat);
										echo number_format($MC_PROGAPPVAL, $decFormat);
									}
								?>                            </td>
                            <td style="text-align:right" nowrap>&nbsp;
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										//echo number_format($PINV_KwitAmPPn, $decFormat);
										echo number_format($MC_PROGAPPVAL2, $decFormat);
									}
								?>                            </td>
                            <td style="text-align:right" nowrap>&nbsp;
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										echo number_format($TOTPINV_PROGVAL, $decFormat);
									}
								?>                            </td>
                            <td style="text-align:right" nowrap>
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										if($PINV_PROGx > 0)
										{
											echo "$PINV_PROGx %";
										}
									}
								?>&nbsp;                            </td>
                            <td nowrap>&nbsp;
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										echo $PINV_PPnNoM;
									}
								?>                            </td>
                            <td nowrap>&nbsp;
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										echo $PINV_TTODate;
									}
								?>                            </td>
                            <td nowrap>&nbsp;
								<?php 
									if(($uraian != $uraian2) && ($PINV_CODE != $PINV_CODE2))
									{
										echo $PINV_TTODDate;
									}
								?>                            </td>
                            <td nowrap><?php echo $PRINV_Date; ?></td>
                            <td style="text-align:right" nowrap>&nbsp;<?php if($RealINVAmount > 0) { echo number_format($RealINVAmount, $decFormat); } ?></td>
                            <td style="text-align:right" nowrap>&nbsp;<?php if($RealINVAmountPPh > 0) { echo number_format($RealINVAmountPPh, $decFormat); } ?></td>
                            <td style="text-align:right" nowrap>&nbsp;<?php if($RealINVOtherAm > 0) { echo number_format($RealINVOtherAm, $decFormat); } ?></td>
                          	<td nowrap style="text-align:right">
								<?php 
									if($PRINV_Deviation < 0)
									{
										$PRINV_DeviationDA	= abs($PRINV_Deviation);
										$PRINV_DeviationD	= "($PRINV_DeviationDA)";
									}
									else
									{
										$PRINV_DeviationD	= $PRINV_Deviation;
									}
									echo $PRINV_DeviationD; 
								?>
                            </td>
                            <td><?php echo $PRINV_Notes;; ?></td>
                        </tr>
                    <?php
							$uraian2 		= $uraian;
							$PINV_MANNO2 	= $PINV_MANNO;
							$PINV_CODE2		= $PINV_CODE;
                        endforeach;
                    }
                ?>
                <tr>
                  <td colspan="21" style="text-align:center" nowrap>--- none ---</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</section>
</body>
</html>