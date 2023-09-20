<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 April 2015
 * File Name	= r_matrequest_report.php
*/
?>
<?php
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat	= 0;
// Start : Query ini wajib dipasang disetiap halaman View
$sql = "SELECT * FROM tbl_language WHERE Language_Status = 1";
$reslang = $this->db->query($sql)->result();
foreach($reslang as $row) :
	$Language_ID	= $row->Language_ID;
endforeach;

$PRJNAME	= '';
$sqlPRJ 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME	= $rowPRJ->PRJNAME;
endforeach;

$sql = "SELECT code_translate, transalate_$Language_ID as myTransalte FROM tbl_translate";
$restrans = $this->db->query($sql)->result();
foreach($restrans as $row) :
	$code_translate	= $row->code_translate;
	if($code_translate == 'Nomor')$Nomor = $row->myTransalte;
	if($code_translate == 'VendCode')$VendCode = $row->myTransalte;
	if($code_translate == 'VendName')$VendName = $row->myTransalte;
	if($code_translate == 'Phone')$Phone = $row->myTransalte;
	if($code_translate == 'VendAddress')$VendAddress = $row->myTransalte;
	if($code_translate == 'materialbudgetreport')$materialbudgetreport = $row->myTransalte;
	if($code_translate == 'nodata')$nodata = $row->myTransalte;
endforeach;
// End : Query ini wajib dipasang disetiap halaman View
/*if($DisplayType == 'excel')
{
	$dateNow = date('Ymd');
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=BankGuarantee_$dateNow.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}*/

// CREATE FOR TRACER ID						
date_default_timezone_set("Asia/Jakarta"); 
$myYear 		= date('y');
$myMonth 		= date('m');
$myDays 		= date('d');
$myHours 		= date('H');
$myMinutes 		= date('i');
$mySeconds 		= date('s');
$CreaterNm 		= getenv("username");
$CreaterNm2		= str_replace('$', '', $CreaterNm);
$localIP 		= getHostByName(php_uname('n'));
$localIP2 		= str_replace('.', '', $localIP);
$Creater_Code	= "TR$myMonth$myYear$myDays$myHours$myMinutes$mySeconds";

$SICODEX 	= explode("|",$selDocNumbColl);
$totSelect 	= count($SICODEX);
if($totSelect == 1)
{
	$SICODEX 	= $selDocNumbColl;
}
else
{
	$SICODEX 	= explode("|",$selDocNumbColl);
	for($i=0; $i<$totSelect; $i++)
	{
		$SICODER	= $SICODEX[$i];
	}
}

$MC_DateY	= date('Y');
$MC_DateM 	= date('m');
$MC_DateD 	= date('d');
$MC_DATE	= "$MC_DateD - $MC_DateM - $MC_DateY";
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'imagess/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">
@import url("<?php echo base_url() . 'css/style_table.css'; ?>");
</style>
<?php /*?><script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script><?php */?>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">
<style>
	.inplabel {border:none;background-color:white;}
	.inpdim {border:none;background-color:white;}
</style>
<table width="100%" border="0" style="size:auto">
    <tr>
    	<td width="24%">
     		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
        <td width="56%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="20%" class="style2" style="text-align:center; font-size:24px">&nbsp;</td>
  	</tr>
    <tr>
    	<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
    	<td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><span class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img//Logo2.jpg'; ?>" width="300" height="70" /></span></td>
      <td colspan="2" nowrap class="style2" style="text-align:left; font-weight:bold; text-transform:uppercase; font-size:24px"> REKAPITULASI PERSETUJUAN SI</td>
    </tr>
    <tr>
      	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">Nomor : </td>
  </tr>
    <tr>
      	<td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
    </tr>
    <tr>
      	<td class="style2" style="text-align:left;font-style:italic" valign="top">&nbsp;</td>
      	<td class="style2" style="text-align:left;">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3" class="style2" style="text-align:left">
        	<table width="100%" border="0" style="font-size:13px">
                <tr>
                    <td width="13%" style="text-align:left">Project Name</td>
                    <td width="1%">:</td>
                    <td width="58%" ><?php echo $PRJNAME; ?></td>
                    <td width="28%" style="text-align:right" nowrap>Tanggal : <?php echo $MC_DATE; ?></td>
                </tr>
                <tr>
                    <td style="text-align:left">Project Code</td>
                    <td>:</td>
                    <td colspan="2" ><?php echo $PRJCODE; ?></td>
                </tr>
                <tr>
                  <td style="text-align:left">Periode</td>
                  <td>:</td>
                  <td colspan="2" > .............................</td>
                </tr>
  	 	 	</table>
      	</td>
  	</tr>
  <tr>
        <td colspan="3" class="style2">
        	<table width="100%" border="1" rules="all" style="font-family:Arial, Helvetica, sans-serif; font-size:14px">
                <tr style="background:#CCCCCC">
                    <td width="4%" style="text-align:center; font-weight:bold">No</td>
                    <td width="10%" style="text-align:center; font-weight:bold">Code / Numbe</td>
                    <td width="9%" style="text-align:center; font-weight:bold">Manual Number</td>
                    <td width="36%" style="text-align:center; font-weight:bold">Description</td>
                    <td width="6%" style="text-align:center; font-weight:bold">Charge Filed</td>
                    <td width="6%" style="text-align:center; font-weight:bold">App.<br />Owner</td>
                    <td width="6%" style="text-align:center; font-weight:bold">SI<br />
                    Doc.</td>
                    <td width="6%" style="text-align:center; font-weight:bold">BQ<br />
                    Doc.</td>
                    <td width="17%" style="text-align:center; font-weight:bold">Remarks</td>
                </tr>
                <?php
					$NoU			= 0;
					$totCSTCOST2	= 0;
					$sqlDT1R 		= 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
					if($totSelect == 1)
					{
						$SI_VALUE2	= 0;
						$SICODEX 	= $selDocNumbColl;
						echo "testing $SICODEX";
						$sql01 		= "SELECT SI_CODE, SI_MANNO, SI_DATE, SI_DATE, PRJCODE, SI_DESC, SI_VALUE, SI_APPVAL, SI_NOTES
										FROM tbl_siheader WHERE SI_CODE = '$SICODEX'";
						$ressql01 	= $this->db->query($sql01)->result();
						foreach($ressql01 as $row01) :
							$NoU			= $NoU + 1;
							$SI_CODE		= $row01->SI_CODE;
							$SI_MANNO		= $row01->SI_MANNO;
							$SI_MANNOD		= $SI_MANNO;
							if($SI_MANNO == '')
							{
								$SI_MANNOD	= 'Not Set';
							}
							$SI_DATE		= $row01->SI_DATE;
							$PRJCODE		= $row01->PRJCODE;
							$SI_DESC		= $row01->SI_DESC;
							$SI_VALUE		= $row01->SI_VALUE;
							$SI_APPVAL		= $row01->SI_APPVAL;
							$SI_NOTES		= $row01->SI_NOTES;
							$SI_VALUE2		= $SI_VALUE2 + $SI_VALUE;
							
							// Setting SI_MANNO
							$jml_SI_MANNO	= strlen($SI_MANNO);							
							if($jml_SI_MANNO < 35) // seratus pertama
							{
								$SI_MANNOD	= $SI_MANNOD;
							}
							else
							{
								$SI_MANNO1		= substr($SI_MANNO,0,35); 	// Baris 1
								$SI_MANNOa		= substr($SI_MANNO,36);		// Baris ke-2
								$jml_SI_MANNOa	= strlen($SI_MANNOa);		// Cek jumlah abris ke-2
								if($jml_SI_MANNOa < 35)
								{
									$SI_MANNOD		= "$SI_MANNO1<br>$SI_MANNOa";
								}
								else
								{
									$SI_MANNO2		= substr($SI_MANNOa,0,35); 	// Baris ke-2
									$SI_MANNOb		= substr($SI_MANNO2,36);	// Baris ke-3
									$jml_SI_MANNOb	= strlen($SI_MANNOb);		// Cek jumlah abris ke-3
									if($jml_SI_MANNOb < 35)
									{
										$SI_MANNOD		= "$SI_MANNO1<br>$SI_MANNO2<br>$SI_MANNOb";
									}
									else
									{
										$SI_MANNO3		= substr($SI_MANNOb,0,35); 	// Baris ke-3
										$SI_MANNOc		= substr($SI_MANNO3,36);	// Baris ke-4
										$SI_MANNOD		= "$SI_MANNO1<br>$SI_MANNO2<br>$SI_MANNO3<br>$SI_MANNOc";
									}
								}
							}
							
							// Setting SI_DESC			
							$STR01 		= $SI_DESC;
							$STR02 		= count(explode(' ', $STR01));	// Count Words in a sentence
							$STR03 		= explode(' ', $STR01);	// Count Words in a sentence
							$STR_ROW	= '';
							$STR_ROW1	= '';
							$STR_ROW2a	= '';
							$STR_ROW2	= '';
							$STR_ROW3a	= '';
							$STR_ROW3	= '';
							
							for($trow = 0; $trow < $STR02; $trow++)
							{
								$STR04 		= $STR03[$trow];
								$STR_ROW	= $STR_ROW.' '.$STR04;
								$C_STR_ROW	= strlen($STR_ROW);
								if($C_STR_ROW < 61)
								{
									$STR_ROW1	= $STR_ROW1.' '.$STR04;				// Baris ke-1
									$C_STR_ROW1	= strlen($STR_ROW1);
									$STR_DESC1	= "$STR_ROW1";
									$STR_DESC	= $STR_DESC1;
								}
								else
								{
									$STR_ROW1		= $STR_ROW1;
									$STR_ROW2a		= $STR_ROW2a.' '.$STR04;		// Baris ke-2
									$C_STR_ROW2a	= strlen($STR_ROW2a);
									if($C_STR_ROW2a < 61)
									{
										$STR_ROW2	= $STR_ROW2.' '.$STR04;
										$C_STR_ROW2	= strlen($STR_ROW2);
										$STR_DESC2	= "$STR_ROW2";
										$STR_DESC	= "$STR_DESC1<br>&nbsp;$STR_DESC2";
									}
									else
									{
										$STR_ROW1		= $STR_ROW1;
										$STR_ROW2		= $STR_ROW2;
										$STR_ROW3a		= $STR_ROW3a.' '.$STR04;	// Baris ke-3
										$C_STR_ROW3a	= strlen($STR_ROW3a);
										if($C_STR_ROW3a < 61)
										{
											$STR_ROW3	= $STR_ROW3.' '.$STR04;
											$C_STR_ROW3	= strlen($STR_ROW3);
											$STR_DESC3	= "$STR_ROW3";
											$STR_DESC	= "$STR_DESC1 <br>&nbsp;$STR_DESC2 <br>&nbsp;$STR_DESC3";
										}
									}
								}
							}
						endforeach;
						?>
                            <tr>
                                <td width="4%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                <td width="10%" nowrap>&nbsp;<?php echo "$SI_CODE"; ?></td>
                                <td width="9%" nowrap >&nbsp;<?php echo "$SI_MANNOD"; ?></td>
                                <td width="36%" nowrap >&nbsp;<?php echo "$STR_DESC"; ?></td>
                                <td width="6%" nowrap style="text-align:right">&nbsp;<?php print number_format($SI_VALUE, $decFormat); ?>&nbsp;</td>
                                <td width="6%" nowrap style="text-align:center">
                                	<input type="checkbox" name="ownAppa1" id="ownAppa1" />
                                </td>
                                <td width="6%" nowrap style="text-align:center">
                                	<input type="checkbox" name="ownAppa2" id="ownAppa2" />
                                </td>
                                <td width="6%" nowrap style="text-align:center">
                                	<input type="checkbox" name="ownAppa3" id="ownAppa3" />
                                </td>
                                <td width="17%" style="text-align:right" nowrap>&nbsp;</td>
                            </tr>
						<?php
                    }
					else
					{
						$SICODEX 	= explode("|",$selDocNumbColl);
						$SI_VALUE2	= 0;
						for($i=0; $i<$totSelect; $i++)
						{
							$SICODER	= $SICODEX[$i];							
							$sql02 		= "SELECT SI_CODE, SI_MANNO, SI_DATE, SI_DATE, PRJCODE, SI_DESC, SI_VALUE, SI_APPVAL, SI_NOTES
											FROM tbl_siheader WHERE SI_CODE = '$SICODER'";
							$ressql02 	= $this->db->query($sql02)->result();
							foreach($ressql02 as $row02) :
								$NoU			= $NoU + 1;
								$SI_CODE		= $row02->SI_CODE;
								$SI_MANNO		= $row02->SI_MANNO;
								$SI_MANNOD		= $SI_MANNO;
								if($SI_MANNO == '')
								{
									$SI_MANNOD	= 'Not Set';
								}
								$SI_DATE		= $row02->SI_DATE;
								$PRJCODE		= $row02->PRJCODE;
								$SI_DESC		= $row02->SI_DESC;
								$SI_DESCD		= $row02->SI_DESC;
								$SI_VALUE		= $row02->SI_VALUE;
								$SI_APPVAL		= $row02->SI_APPVAL;
								$SI_NOTES		= $row02->SI_NOTES;
								$SI_VALUE2		= $SI_VALUE2 + $SI_VALUE;
							endforeach;
							
							// Setting SI_MANNO
							$jml_SI_MANNO	= strlen($SI_MANNO);							
							if($jml_SI_MANNO < 35) // seratus pertama
							{
								$SI_MANNOD	= $SI_MANNOD;
							}
							else
							{
								$SI_MANNO1		= substr($SI_MANNO,0,35); 	// Baris 1
								$SI_MANNOa		= substr($SI_MANNO,36);		// Baris ke-2
								$jml_SI_MANNOa	= strlen($SI_MANNOa);		// Cek jumlah abris ke-2
								if($jml_SI_MANNOa < 35)
								{
									$SI_MANNOD		= "$SI_MANNO1<br>$SI_MANNOa";
								}
								else
								{
									$SI_MANNO2		= substr($SI_MANNOa,0,35); 	// Baris ke-2
									$SI_MANNOb		= substr($SI_MANNO2,35);	// Baris ke-3
									$jml_SI_MANNOb	= strlen($SI_MANNOb);		// Cek jumlah abris ke-3
									if($jml_SI_MANNOb < 35)
									{
										$SI_MANNOD		= "$SI_MANNO1<br>$SI_MANNO2<br>$SI_MANNOb";
									}
									else
									{
										$SI_MANNO3		= substr($SI_MANNOb,0,35); 	// Baris ke-3
										$SI_MANNOc		= substr($SI_MANNO3,36);	// Baris ke-4
										$SI_MANNOD		= "$SI_MANNO1<br>$SI_MANNO2<br>$SI_MANNO3<br>$SI_MANNOc";
									}
								}
							}
							
							$STR01 		= $SI_DESC;
							$STR02 		= count(explode(' ', $STR01));	// Count Words in a sentence
							$STR03 		= explode(' ', $STR01);	// Count Words in a sentence
							$STR_ROW	= '';
							$STR_ROW1	= '';
							$STR_ROW2a	= '';
							$STR_ROW2	= '';
							$STR_ROW3a	= '';
							$STR_ROW3	= '';
							
							for($trow = 0; $trow < $STR02; $trow++)
							{
								$STR04 		= $STR03[$trow];
								$STR_ROW	= $STR_ROW.' '.$STR04;
								$C_STR_ROW	= strlen($STR_ROW);
								if($C_STR_ROW < 61)
								{
									$STR_ROW1	= $STR_ROW1.' '.$STR04;				// Baris ke-1
									$C_STR_ROW1	= strlen($STR_ROW1);
									$STR_DESC1	= "$STR_ROW1";
									$STR_DESC	= $STR_DESC1;
								}
								else
								{
									$STR_ROW1		= $STR_ROW1;
									$STR_ROW2a		= $STR_ROW2a.' '.$STR04;		// Baris ke-2
									$C_STR_ROW2a	= strlen($STR_ROW2a);
									if($C_STR_ROW2a < 61)
									{
										$STR_ROW2	= $STR_ROW2.' '.$STR04;
										$C_STR_ROW2	= strlen($STR_ROW2);
										$STR_DESC2	= "$STR_ROW2";
										$STR_DESC	= "$STR_DESC1<br>&nbsp;$STR_DESC2";
									}
									else
									{
										$STR_ROW1		= $STR_ROW1;
										$STR_ROW2		= $STR_ROW2;
										$STR_ROW3a		= $STR_ROW3a.' '.$STR04;	// Baris ke-3
										$C_STR_ROW3a	= strlen($STR_ROW3a);
										if($C_STR_ROW3a < 61)
										{
											$STR_ROW3	= $STR_ROW3.' '.$STR04;
											$C_STR_ROW3	= strlen($STR_ROW3);
											$STR_DESC3	= "$STR_ROW3";
											$STR_DESC	= "$STR_DESC1 <br>&nbsp;$STR_DESC2 <br>&nbsp;$STR_DESC3";
										}
									}

								}
							}
							
							?>
                                <tr>
                                    <td width="4%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                    <td width="10%" nowrap>&nbsp;<?php echo "$SI_CODE"; ?></td>
                                    <td width="9%" nowrap style="text-align:left">&nbsp;<?php echo "$SI_MANNOD"; ?></td>
                                    <td width="36%" nowrap style="text-align:left">&nbsp;<?php echo "$STR_DESC"; ?></td>
                                    <td width="6%" nowrap style="text-align:right">&nbsp;<?php print number_format($SI_VALUE, $decFormat); ?>&nbsp;</td>
                                    <td width="6%" nowrap style="text-align:center">
                                        <input type="checkbox" name="ownAppb1" id="ownAppb1" />
                                    </td>
                                    <td width="6%" nowrap style="text-align:center">
                                        <input type="checkbox" name="ownAppb2" id="ownAppb2" />
                                    </td>
                                    <td width="6%" nowrap style="text-align:center">
                                        <input type="checkbox" name="ownAppb3" id="ownAppb3" />
                                    </td>
                                    <td width="17%" style="text-align:right" nowrap>&nbsp;</td>
                                </tr>
                            <?php
						}
					}
					?>
                    <tr>
                      <td colspan="4" nowrap style="text-align:right; font-weight:bold">J U M L A H : </td>
                      <td nowrap style="text-align:right; font-weight:bold">&nbsp;<?php print number_format($SI_VALUE2, $decFormat); ?>&nbsp;</td>
                      <td colspan="4" nowrap style="text-align:center">&nbsp;</td>
                    </tr>
      			</table>
          </td>
  	</tr>
    <tr>
		<td colspan="3" valign="top" class="style2"><table width="100%" border="0">
          <tr style="font-size:10px">
            <td width="20%" style="text-align:center">&nbsp;</td>
            <td width="60%" style="text-align:center">&nbsp;</td>
            <td width="20%" style="text-align:center" nowrap>&nbsp;</td>
          </tr>
          <tr style="font-size:13px">
            <td width="20%" style="text-align:center">Diajukan Oleh,</td>
            <td width="60%" style="text-align:center">&nbsp;</td>
            <td width="20%" style="text-align:center" nowrap>Disetujui Oleh,</td>
          </tr>
          <tr style="font-size:10px;">
            <td style="text-align:center">
           	  <table border="1" align="center" rules="all">
                	<tr height="60">
                    	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td>&nbsp;</td>
            <td style="text-align:center">
            	<table border="1" align="center" rules="all">
                	<tr height="60">
                    	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
            </td>
          </tr>
        </table></td>
  	</tr>    
    <tr>
      <td colspan="3" class="style2" style="font-size:8px; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
</table>
</body>
</html>