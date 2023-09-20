<?php
$sqlMC		= "SELECT MC_STEP, MC_DATE FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE' AND MC_CODE = '$MC_CODE' AND MC_STAT IN (3,6)";
$resMC 		= $this->db->query($sqlMC)->result();
foreach($resMC as $rowMC):
	$MC_STEP = $rowMC->MC_STEP;
	$MC_DATE = $rowMC->MC_DATE;
	$MC_DAY  = date('d', strtotime($MC_DATE));
	$MC_MNTH = date('m', strtotime($MC_DATE));
	$MC_YEAR = date('Y', strtotime($MC_DATE));
endforeach;
$lenMCS = strlen($MC_STEP);
if($lenMCS==1) $nolMCS="0";elseif($lenMCS==2) $nolMCS="";
$pattMCS = $nolMCS.$MC_STEP;

if($MC_MNTH == '01')
	$MC_MNTHD = "JANUARI";
elseif($MC_MNTH == '02')
	$MC_MNTHD = "FEBRUARI";
elseif($MC_MNTH == '03')
	$MC_MNTHD = "MARET";
elseif($MC_MNTH == '04')
	$MC_MNTHD = "APRIL";
elseif($MC_MNTH == '05')
	$MC_MNTHD = "MEI";
elseif($MC_MNTH == '06')
	$MC_MNTHD = "JUNI";
elseif($MC_MNTH == '07')
	$MC_MNTHD = "JULI";
elseif($MC_MNTH == '08')
	$MC_MNTHD = "AGUSTUS";
elseif($MC_MNTH == '09')
	$MC_MNTHD = "SEPTEMBER";
elseif($MC_MNTH == '10')
	$MC_MNTHD = "OKTOBER";
elseif($MC_MNTH == '11')
	$MC_MNTHD = "NOPEMBER";
elseif($MC_MNTH == '12')
	$MC_MNTHD = "DESEMBER";

$PINV_DPPER = 0;
$sqlDP		= "SELECT PINV_DPPER FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT IN (3,6)";
$resDP 		= $this->db->query($sqlDP)->result();
foreach($resDP as $rowDP):
	$PINV_DPPER = $rowDP->PINV_DPPER;
endforeach;
if($PINV_DPPER == '')
	$PINV_DPPER = 0;

class moneyFormat
{
	public function rupiah ($angka)
	{
		$rupiah = number_format($angka ,2, ',' , '.' );
		return $rupiah;
	}

	public function terbilang ($angka)
	{
        $angka = (float)$angka;
        $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
        if ($angka < 12) {
            return $bilangan[$angka];
        } else if ($angka < 20) {
            return $bilangan[$angka - 10] . ' Belas';
        } else if ($angka < 100) {
            $hasil_bagi = (int)($angka / 10);
            $hasil_mod = $angka % 10;
            return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
        } else if ($angka < 200) {
            return sprintf('Seratus %s', $this->terbilang($angka - 100));
        } else if ($angka < 1000) {
            $hasil_bagi = (int)($angka / 100);
            $hasil_mod = $angka % 100;
            return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
        } else if ($angka < 2000) {
            return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
        } else if ($angka < 1000000) {
            $hasil_bagi = (int)($angka / 1000);
            $hasil_mod = $angka % 1000;
            return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
        } else if ($angka < 1000000000) {
            $hasil_bagi = (int)($angka / 1000000);
            $hasil_mod = $angka % 1000000;
            return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000) {
            $hasil_bagi = (int)($angka / 1000000000);
            $hasil_mod = fmod($angka, 1000000000);
            return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000000) {
            $hasil_bagi = $angka / 1000000000000;
            $hasil_mod = fmod($angka, 1000000000000);
            return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else {
            return 'Data Salah';
        }
    }
}

$moneyFormat = new moneyFormat();

function KonDecRomawi($angka)
{
    $hsl = "";
    if($angka<1||$angka>3999)
    {
        $hsl = "Batas Angka 1 s/d 3999";
    }
    else
    {
     	while($angka>=1000)
     	{
         	$hsl .= "M";
         	$angka -= 1000;
    	}
        if($angka>=500){
         	if($angka>500){
				if($angka>=900){
				 	$hsl .= "CM";
				 	$angka-=900;
				}else{
				 	$hsl .= "D";
				 	$angka-=500;
				}
         	}
        }
    	while($angka>=100)
    	{
         	if($angka>=400){
             	$hsl .= "CD";
             	$angka-=400;
        	}else{
             	$angka-=100;
         	}
     	}
    	if($angka>=50){
         	if($angka>=90){
             	$hsl .= "XC";
              	$angka-=90;
         	}else{
            	$hsl .= "L";
            	$angka-=50;
         	}
     	}
     	while($angka>=10){
         	if($angka>=40){
            	$hsl .= "XL";
            	$angka-=40;
         	}else{
            	$hsl .= "X";
            	$angka-=10;
         	}
     	}
		if($angka>=5){
			if($angka==9){
			 	$hsl .= "IX";
			 	$angka-=9;
			}else{
				$hsl .= "V";
				$angka-=5;
			}
		}
        while($angka>=1){
            if($angka==4){
                $hsl .= "IV";
                $angka-=4;
            }else{
                $hsl .= "I";
                $angka-=1;
            }
        }
    }
    return ($hsl);
}

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$comp_name 		= $this->session->userdata['comp_name'];
?>
<!DOCTYPE html>
<html>
  	<head>
	    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
	    <title></title>
	    <style>
	    	body {
				margin: 0;
				padding: 0;
				background-color: #FAFAFA;
				font: 10px; Arial, Helvetica, sans-serif;
			}
			* {
				box-sizing: border-box;
				-moz-box-sizing: border-box;
			}
			.page {
	        width: 22cm;
	        min-height: 29.7cm;
	        padding: 0.5cm;
	        margin: 0.5cm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    	}
	    	.subpage {
	        padding: 1cm;
	        height: 256mm;
	    	}
	    
		    @page {
		        size: A4;
		        margin: 0;
		    }
		    @media print {
		        .page {
		            margin: 0;
		            border: initial;
		            border-radius: initial;
		            width: initial;
		            min-height: initial;
		            box-shadow: initial;
		            background: initial;
		            page-break-after: always;
		        }
		    }
	    </style>
 	</head>
  	<body>
	  	<div style="padding-left:100px; padding-top:15px;">
		  	<table border="0">
		        <tr>
		          	<td colspan="3" class="style2">
			            <div id="Layer1">
			                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
			                <a href="#" onClick="window.close();" class="button"> close </a>
		                </div>
		        	</td>
		        </tr>
		    </table>
	  	</div>
	  	<div class="page">
		  	<table border="1" rules="all" width="100%">
		      	<tr>
		       		<td style="border-width:2px; border-color:#000000">
		        		<table border="1" rules="all" width="100%"  style="border-width:2px; border-color:#000000">
				            <tr>
				                <td height="20" align="center" style="font-size:16px; font-style: italic;"><b>REKAPITULASI SERTIFIKAT BULANAN / THERMIN</b></td>
				            </tr>
		        		</table>
				        <div style="padding-bottom:1px; padding-top:1px;">
					        <table border="1" rules="all" width="100%" style="height: 76px;">
					            <tr>
					              <td height="100" style="width: 131.2px; vertical-align:middle; border-width:2px; border-color:#000000">
					                <span class="style2" style="text-align:left; padding-left:10px; padding-right:10px;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="100" height="70"></span>          </td>
					            </tr>
					        </table>
		        		</div>
				        <table border="1" rules="all" width="100%"  style="border-width:2px; border-color:#000000">
				            <tr>
				                <td>
				                    <table style="width: 100%;" border="0">
				                    	<?php 
											if($countPRJ > 0): 
												foreach($viewProject as $r):
													$PRJCNUM = $r->PRJCNUM;
													$PRJNAME = $r->PRJNAME;
													$PRJDATE = date('Y-m-d', strtotime($r->PRJDATE));
													$PRJCOST = number_format($r->PRJCOST,$decFormat);
												endforeach;
											endif;
											?>
				                        <tr>
				                            <td width="20%" style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td width="2%" style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">:</td>
				                            <td width="35%" style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td width="15%" style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td width="2%" style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td width="26%" style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">PAKET KONTRAK</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;"><?=$PRJNAME?></td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">NOMOR KONTRAK</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;"><?=$PRJCNUM?></td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">TANGGAL KONTRAK</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;"><?=$PRJDATE?></td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">&nbsp;</td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">NILAI KONTRAK</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;"><?=$PRJCOST?></td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">SERTIFIKAT</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;"><?=$pattMCS?></td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">SUMBER DANA</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">SBSN</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">BULAN</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;"><?=$MC_MNTHD?></td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">KONTRAKTOR PELAKSANA</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">GALIH PT. SASMITO</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">TANGGAL</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">
				                            	<?php echo "$MC_DAY $MC_MNTHD $MC_YEAR"; ?></td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">KONSULTAN SUPERVISI</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">PT. MOJOPAHIT AGUNG KONSULTAN & GPS</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">HALAMAN</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">:</td>
				                            <td style="line-height: 10px; font-family:Arial, Helvetica, sans-serif; font-size:10px;">1</td>
				                      	</tr>
				                        <tr>
				                            <td style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                            <td style="line-height: 5px; font-family:Arial, Helvetica, sans-serif; font-size:5px;">&nbsp;</td>
				                      	</tr>
				                    </table>
				                </td>
				            </tr>
						</table>
						<div style="padding-bottom:1px; padding-top:1px; font-family:Arial, Helvetica, sans-serif; font-size:10px">
							<table border="1" rules="all" width="100%"  style="border-width:2px; border-color:#000000; text-align:center">
					            <tr>
					                <td width="5%" rowspan="3" style="border-width:2px; border-color:#000000">NO</td>
					                <td width="5%" rowspan="3" style="border-width:2px; border-color:#000000">DIV</td>
					                <td width="24%" rowspan="3" style="border-width:2px; border-color:#000000">URAIAN</td>
					                <td colspan="2" style="border-width:2px; border-color:#000000">KONTRAK</td>
					                <td colspan="2" style="border-width:2px; border-color:#000000">REALISASI S/D<br>BULAN LALU</td>
					                <td colspan="2" style="border-width:2px; border-color:#000000">BULAN INI</td>
					                <td style="border-width:2px; border-color:#000000">REALISASI S/D<br>BULAN INI</td>
					            </tr>
					            <tr>
									<td width="16%" style="border-width:2px; border-color:#000000">NILAI</td>
									<td width="12%" style="border-width:2px; border-color:#000000">BOBOT</td>
									<td width="16%" style="border-width:2px; border-color:#000000">NILAI</td>
									<td width="12%" style="border-width:2px; border-color:#000000">BOBOT</td>
									<td width="16%" style="border-width:2px; border-color:#000000">NILAI</td>
									<td width="12%" style="border-width:2px; border-color:#000000">BOBOT</td>
									<td width="16%" style="border-width:2px; border-color:#000000">NILAI</td>
					            </tr>
					            <tr>
									<td style="border-width:2px; border-color:#000000">(Rp)</td>
									<td style="border-width:2px; border-color:#000000">(%)</td>
									<td style="border-width:2px; border-color:#000000">(Rp)</td>
									<td style="border-width:2px; border-color:#000000">(%)</td>
									<td style="border-width:2px; border-color:#000000">(Rp)</td>
									<td style="border-width:2px; border-color:#000000">(%)</td>
									<td style="border-width:2px; border-color:#000000">(Rp)</td>
								</tr>
					          	<tr style="line-height:2px;">
					            	<td width="5%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="5%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="24%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="16%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="16%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					            </tr>
					            <?php
									$THIS_M 	= date('m', strtotime($MC_DATE));
									$THIS_Y 	= date('Y', strtotime($MC_DATE));
					            	$TOT_KOLA	= 0;
					            	$TOT_KOLB	= 0;
					            	$TOT_KOLC	= 0;
					            	$TOT_KOLD	= 0;
					            	$TOT_KOLE	= 0;
					            	$TOT_KOLF	= 0;
					            	$TOT_KOLG	= 0;
									$joblist	= "SELECT * FROM tbl_joblist WHERE JOBLEV = 1 AND ISBOBOT = 1 AND PRJCODE = '$PRJCODE'";
									$resBoQ 	= $this->db->query($joblist)->result();
									$no 		= 0;
									foreach($resBoQ as $row):
										$no				= $no + 1;
										$JOBCODEID 		= $row->JOBCODEID;
										$JOBCODEIDV		= $row->JOBCODEIDV;
										$JOBPARENT		= $row->JOBPARENT;
										$PRJCODE		= $row->PRJCODE;
										$ITM_CODE		= $row->ITM_CODE;
										$JOBDESC		= $row->JOBDESC;
										$JOBVOLM		= $row->JOBVOLM;
										$PRICE			= $row->PRICE;
										$JOBCOST		= $row->JOBCOST;
										$BOQ_VOLM		= $row->BOQ_VOLM;
										$BOQ_PRICE		= $row->BOQ_PRICE;
										$BOQ_JOBCOST	= $row->BOQ_JOBCOST;
										$BOQ_BOBOT		= $row->BOQ_BOBOT;
										$JOBD1 			= strtolower($JOBDESC);
										$JOBD 			= ucfirst($JOBD1);

										$TOT_KOLA		= $TOT_KOLA + $BOQ_JOBCOST;
										$TOT_KOLB		= $TOT_KOLB + $BOQ_BOBOT;

										// REALISASI S.D. BULAN LALU
											$PROG_V_MB 	= 0;
											$PROG_P_MB 	= 0;
											$sqlPROG	= "SELECT SUM(A.PROG_VAL) AS TOT_VAL_MB, SUM(A.PROG_PERC) AS TOT_PERC_MB
															 FROM tbl_project_progress_det A
																INNER JOIN tbl_project_progress B ON A.PRJP_NUM = B.PRJP_NUM AND A.PRJCODE = B.PRJCODE
															WHERE A.JOBCODEID = '$JOBCODEID'
																AND MONTH(B.PRJP_DATE_E) < '$THIS_M' AND YEAR(B.PRJP_DATE_E) = '$THIS_Y'";
											$resPROG 	= $this->db->query($sqlPROG)->result();
											foreach($resPROG as $rowPROG):
												$PROG_V_MB 	= $rowPROG->TOT_VAL_MB;
												$PROG_P_MB 	= $rowPROG->TOT_PERC_MB;
											endforeach;
											$TOT_KOLC	= $TOT_KOLC + $PROG_V_MB;
											$TOT_KOLD	= $TOT_KOLD + $PROG_P_MB;

										// REALISASI BULAN INI
											$PROG_V_CM 	= 0;
											$PROG_P_CM 	= 0;
											$sqlPROG	= "SELECT SUM(A.PROG_VAL) AS TOT_VAL_CM, SUM(A.PROG_PERC) AS TOT_PERC_CM
															 FROM tbl_project_progress_det A
																INNER JOIN tbl_project_progress B ON A.PRJP_NUM = B.PRJP_NUM AND A.PRJCODE = B.PRJCODE
															WHERE A.JOBCODEID = '$JOBCODEID'
																AND MONTH(B.PRJP_DATE_E) = '$THIS_M' AND YEAR(B.PRJP_DATE_E) = '$THIS_Y'";
											$resPROG 	= $this->db->query($sqlPROG)->result();
											foreach($resPROG as $rowPROG):
												$PROG_V_CM 	= $rowPROG->TOT_VAL_CM;
												$PROG_P_CM 	= $rowPROG->TOT_PERC_CM;
											endforeach;
											$TOT_KOLE	= $TOT_KOLE + $PROG_V_CM;
											$TOT_KOLF	= $TOT_KOLF + $PROG_P_CM;

										// REALISASI AKUMULASI S.D. BULAN INI
											$PROG_V_CUR = $PROG_V_MB + $PROG_V_CM;
											$TOT_KOLG	= $TOT_KOLG + $PROG_V_CUR;
										?>
								            <tr style="font-family:Arial, Helvetica, sans-serif; font-size:10px; line-height: 15px">
												<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">
													<?=KonDecRomawi($no)?>
												</td>
												<td width="5%" style="border-right-width:2px; border-color:#000000">
													<?=$no?>
												</td>
												<td width="24%" style="border-right-width:2px; border-color:#000000; text-align:justify;" nowrap>
													&nbsp;<?=$JOBD?>
												</td>
												<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;" nowrap>
													<?=number_format($BOQ_JOBCOST,2)?>&nbsp;
												</td>
												<td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;" nowrap>
													<?=number_format($BOQ_BOBOT,2)?>%&nbsp;
												</td>
												<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;" nowrap>
													<?=number_format($PROG_V_MB,2)?>&nbsp;
												</td>
												<td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;" nowrap>
													<?=number_format($PROG_P_MB,2)?>%&nbsp;
												</td>
												<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;" nowrap>
													<?=number_format($PROG_V_CM,2)?>&nbsp;
												</td>
												<td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;" nowrap>
													<?=number_format($PROG_P_CM,2)?>%&nbsp;
												</td>
												<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;" nowrap>
													<?=number_format($PROG_V_CUR,2)?>&nbsp;
												</td>
								            </tr>
						            	<?php
									endforeach;
								?>
					          	<tr style="line-height:2px;">
					            	<td width="5%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="5%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="24%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="16%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="12%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="12%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="12%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="16%" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					            </tr>
					          	<tr style="font-weight: bold;">
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center">A</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align: left;">JUMLAH (1 S/D 10)</td>
					                <td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;"><?=number_format($TOT_KOLA,2)?></td>
					              	<td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;"><?=number_format($TOT_KOLB,2)?></td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;"><?=number_format($TOT_KOLC,2)?></td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;"><?=number_format($TOT_KOLD,2)?></td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;"><?=number_format($TOT_KOLE,2)?></td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;"><?=number_format($TOT_KOLF,2)?></td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;"><?=number_format($TOT_KOLG,2)?></td>
					            </tr>
					            <?php
						            function pembulatan($nomi)
									{
										$rnd1 		= round($nomi);
										$ratusan 	= substr($rnd1, -3);
										if($ratusan<500)
											$akhir = $rnd1 - $ratusan;
										else
											$akhir = $rnd1 + (1000-$ratusan);
										return $akhir;
									}

					            	$PPN_KOLA 	= 0.1 * $TOT_KOLA;
					            	$PPN_KOLB 	= 0.1 * $TOT_KOLB;
					            	$PPN_KOLC 	= 0.1 * $TOT_KOLC;
					            	$PPN_KOLD 	= 0.1 * $TOT_KOLD;
					            	$PPN_KOLE 	= 0.1 * $TOT_KOLE;
					            	$PPN_KOLF 	= 0.1 * $TOT_KOLF;
					            	$PPN_KOLG 	= 0.1 * $TOT_KOLG;

					            	$GTOT_KOLA 	= $PPN_KOLA + $TOT_KOLA;
					            	$GTOT_KOLB 	= $PPN_KOLB + $TOT_KOLB;
					            	$GTOT_KOLC 	= $PPN_KOLC + $TOT_KOLC;
					            	$GTOT_KOLD 	= $PPN_KOLD + $TOT_KOLD;
					            	$GTOT_KOLE 	= $PPN_KOLE + $TOT_KOLE;
					            	$GTOT_KOLF 	= $PPN_KOLF + $TOT_KOLF;
					            	$GTOT_KOLG 	= $PPN_KOLG + $TOT_KOLG;

					            	// PEMBULATAN
					            	$RND_KOLA 	= pembulatan($GTOT_KOLA);
					            	$RND_KOLB 	= pembulatan($GTOT_KOLB);
					            	$RND_KOLC 	= pembulatan($GTOT_KOLC);
					            	$RND_KOLD 	= pembulatan($GTOT_KOLD);
					            	$RND_KOLE 	= pembulatan($GTOT_KOLE);
					            	$RND_KOLF 	= pembulatan($GTOT_KOLF);
					            	$RND_KOLG 	= pembulatan($GTOT_KOLG);

					            ?>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">B</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align: left;">PPN (10% x A)</td>
					                <td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($PPN_KOLA,2)?></td>
					              	<td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					              		<?=number_format($PPN_KOLC,2)?></td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					              		<?=number_format($PPN_KOLE,2)?></td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($PPN_KOLG,2)?></td>
					            </tr>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">C</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align:left;">TOTAL (A+B)</td>
					                <td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($GTOT_KOLA,2)?></td>
					              	<td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					              		<?=number_format($GTOT_KOLC,2)?></td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					              		<?=number_format($GTOT_KOLE,2)?></td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($GTOT_KOLG,2)?></td>
					            </tr>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">D</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align:left;">DIBULATKAN</td>
					                <td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($RND_KOLA,2)?></td>
					              	<td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					              		<?=number_format($RND_KOLC,2)?></td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					              		<?=number_format($RND_KOLE,2)?></td>
					              	<td width="16%" style="border-right-width:2px; border-color:#000000">&nbsp;</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($RND_KOLG,2)?></td>
					            </tr>
					            <?php
					            	$RETVAL	 	= $RND_KOLG * 0.05;
					            	$DPBACK 	= $PINV_DPPER * $RND_KOLG / 100;
					            	$OTHCUT 	= 0;
					            	$TOTCUT 	= $TOT_KOLC + $RETVAL + $DPBACK + $OTHCUT;
					            	$GTOT_MC 	= $RND_KOLG - $TOTCUT;

					            	// TOTAL PROGRESS %
					            	$GTOT_MCP 	= $TOT_KOLD + $TOT_KOLF;
					            ?>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">E</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align:left;">POTONGAN</td>
					                <td colspan="6" style="border-right-width:2px; border-top-width:2px; border-color:#000000; text-align: left;">
					                	1 MC s/d BULAN LALU</td>
					                <td width="12%" style="border-right-width:2px; border-top-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($TOT_KOLC,2)?></td>
					            </tr>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">&nbsp;</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align:left;">&nbsp;</td>
					                <td colspan="6" style="border-right-width:2px; border-color:#000000; text-align: left;">
					                	2 RETENSI (5% x D)</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($RETVAL,2)?></td>
					            </tr>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">&nbsp;</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align:left;">&nbsp;</td>
					                <td colspan="6" style="border-right-width:2px; border-color:#000000; text-align: left;">
					                	3 PENGEMBALIAN UANG MUKA (15% x D)</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($DPBACK,2)?></td>
					            </tr>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">&nbsp;</td>
					                <td colspan="2" style="border-right-width:2px; border-color:#000000; text-align:left;">&nbsp;</td>
					                <td colspan="6" style="border-right-width:2px; border-color:#000000; text-align: left;">
					                	4 POTONGAN LAIN - LAIN</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($OTHCUT,2)?></td>
					            </tr>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">F</td>
					                <td colspan="8" style="border-right-width:2px; border-color:#000000; text-align:left;">JUMLAH POTONGAN (1+2+3+4)</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($TOTCUT,2)?></td>
					            </tr>
					            <tr>
					            	<td width="5%" style="border-right-width:2px; border-color:#000000; text-align:center;">G</td>
					                <td colspan="8" style="border-right-width:2px; border-color:#000000; text-align:left;">MC BULAN INI (D-F) INCLUDE PPN (10 %)</td>
					                <td width="12%" style="border-right-width:2px; border-color:#000000; text-align: right;">
					                	<?=number_format($GTOT_MC,2)?></td>
					            </tr>
					          	<tr style="line-height:2px;">
					            	<td colspan="10" style="border-right-width:2px; border-top-width:2px; border-color:#000000">&nbsp;</td>
					            </tr>
					          	<tr style="line-height:2px; font-weight: bold;">
					            	<td colspan="10" height="44" style="border-right-width:2px; border-top-width:2px; border-color:#000000">
					            		<table width="100%">
					            			<tr>
					            				<td width="20%" style="text-align: right;">Terbilang : </td>
					            				<td width="80%" style="text-align: left;"><?=strtoupper($moneyFormat->terbilang($GTOT_MC));?></td>
											</tr>
										</table>
					            	</td>
					            </tr>
					            <tr>
									<td colspan="3" rowspan="3" style="border-right-width:2px; border-color:#000000; border-top-width:2px; text-align:left; text-align:center">PERSENTASE PEKERJAAN % :</td>
									<td colspan="6" style="border-right-width:2px; border-color:#000000; border-top-width:2px; text-align:left;">
										S/D BULAN INI</td>
									<td style="border-right-width:2px; border-color:#000000; border-top-width:2px; text-align:right;">
										<?=number_format($GTOT_MCP,2)?>%</td>
					            </tr>
					            <tr>
									<td colspan="6" style="border-right-width:2px; border-color:#000000; text-align:left;">
										S/D BULAN LALU</td>
									<td style="border-right-width:2px; border-color:#000000; text-align:right;">
										<?=number_format($TOT_KOLD,2)?>%</td>
					            </tr>
					            <tr>
									<td colspan="6" style="border-right-width:2px; border-color:#000000; text-align:left;">
										BULAN INI</td>
									<td style="border-right-width:2px; border-color:#000000; text-align:right;">
										<?=number_format($TOT_KOLF,2)?>%</td>
					            </tr>
							</table>
						</div>
						<table border="1" rules="all" width="100%"  style="border-width:2px; border-color:#000000;">
				            <tr>
				                <td height="165">
				                	<table border="0" width="100%" style="line-height:2px;" cellpadding="0" cellspacing="0">
				                    	<tr>
				                        	<td width="36%" height="10" align="center" style="border-right-style:hidden">
				                            	<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">MENGETAHUI:</span></td>
				                            <td width="28%" style="border-right-style:hidden">&nbsp;</td>
				                          	<td width="36%" align="center">
				                       	    <span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">DIAJUKAN OLEH:</span>                          </td>
				                      	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">
											<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">MANAGER OPERASIONAL</span></td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">
											<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">PROJECT MANAGER</span>
											</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">
											<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">PT. SASMITO</span>                          </td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">
											<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">PT. SASMITO</span>
											</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">&nbsp;</td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">&nbsp;</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">&nbsp;</td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">&nbsp;</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">&nbsp;                          </td>
											<td style="border-right-style:hidden">&nbsp;</td>
				                    	  <td align="center">&nbsp;</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">&nbsp;</td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">&nbsp;</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">&nbsp;</td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">&nbsp;</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">&nbsp;</td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">&nbsp;</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">&nbsp;</td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">&nbsp;</td>
				                  	  	</tr>
				                    	<tr>
											<td height="10" align="center" style="border-right-style:hidden">
											<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;"><b><u>MAHENDRA HIMAWAN GIRI</u></b></span>
											</td>
											<td style="border-right-style:hidden">&nbsp;</td>
											<td align="center">
											<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;"><b><u>---------------------------------------------</u></b></span>
											</td>
				                  	  	</tr>
				                    </table>
				              	</td>
				            </tr>
				        </table>
		         	</td>
		        </tr>
		    </table>
	    </div> 
  	</body>
</html>
