<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 September 2016
 * File Name	= project_invoice_sd_print1.php
 * Notes		= Sync With SDBP on 27 Mei 2016
*/
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
    if($angka<1||$angka>3999){
        $hsl = "Batas Angka 1 s/d 3999";
    }else{
         while($angka>=1000){
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
         while($angka>=100){
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

$PRJCODE 		= $this->session->userdata['dtProjSess']['myProjSession'];
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$own_Title 		= '';
$own_Code 		= '';
$own_Name 		= '';
$own_Add1 		= '';
	
$sqlOwner		= "SELECT own_Code, own_Title, own_Name, own_Add1 FROM sd_towner WHERE own_Code = '$PINV_OWNER'";
$ressqlOwner	= $this->db->query($sqlOwner)->result();
foreach($ressqlOwner as $rowqlOwner) :
	$own_Title 		= $rowqlOwner ->own_Title;
	$own_Code 		= $rowqlOwner ->own_Code;
	$own_Name 		= $rowqlOwner ->own_Name;
	$own_Add1 		= $rowqlOwner ->own_Add1;
endforeach;
if($PINV_CAT != 1)
{
	$sqlPINVH			= "SELECT * FROM sd_tprojinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CODE = '$PINV_CODE'";
	$ressqlPINVH		= $this->db->query($sqlPINVH)->result();
	foreach($ressqlPINVH as $rowPINVH) :		
		$PINV_CODE 		= $rowPINVH ->PINV_CODE;
		$PINV_MANNO 	= $rowPINVH ->PINV_MANNO;
		$PINV_STEP 		= $rowPINVH ->PINV_STEP;
		$PINV_CAT 		= $rowPINVH ->PINV_CAT;
		$PINV_MMC 		= $rowPINVH ->PINV_MMC;
		$MC_REF 		= $rowPINVH ->MC_REF;
		$PINV_SOURCE	= $rowPINVH ->PINV_SOURCE;
		$PRJCODE 		= $rowPINVH ->PRJCODE;
		$PINV_OWNER 	= $rowPINVH ->PINV_OWNER;
		$PINV_DATE 		= $rowPINVH ->PINV_DATE;
		$PINV_DATEx 	= date('Y-m-d', strtotime('-1 days', strtotime($PINV_DATE)));
		$PINV_ENDDATE	= $rowPINVH ->PINV_ENDDATE; 
		$PINV_CHECKD 	= $rowPINVH ->PINV_CHECKD; 
		$PINV_CREATED	= $rowPINVH ->PINV_CREATED;
		$PINV_RETVAL 	= $rowPINVH ->PINV_RETVAL;
		$PINV_RETCUT 	= $rowPINVH ->PINV_RETCUT;
		$PINV_RETCUTPPn = round(0.1 * $PINV_RETCUT);
		$PINV_DPPER 	= $rowPINVH ->PINV_DPPER;
		$PINV_DPVAL 	= $rowPINVH ->PINV_DPVAL;
		$PINV_DPVALPPn	= $rowPINVH ->PINV_DPVALPPn;
		$PINV_DPBACK 	= $rowPINVH ->PINV_DPBACK;
		$PINV_DPBACKPPn = $rowPINVH ->PINV_DPBACKPPn;
		$PINV_PROG 		= $rowPINVH ->PINV_PROG;
		$PINV_PROGVAL	= $rowPINVH ->PINV_PROGVAL;
		$PINV_PROGVALPPn= $rowPINVH ->PINV_PROGVALPPn;
		$PINV_PROGAPP	= $rowPINVH ->PINV_PROGAPP;
		$PINV_PROGAPPVAL= $rowPINVH ->PINV_PROGAPPVAL;
		$PINV_VALADD 	= $rowPINVH ->PINV_VALADD;
		$PINV_VALADDPPn = $rowPINVH ->PINV_VALADDPPn;
		$PINV_MATVAL 	= $rowPINVH ->PINV_MATVAL;
		$PINV_VALBEF	= $rowPINVH ->PINV_VALBEF;
		$PINV_VALBEFPPn	= $rowPINVH ->PINV_VALBEFPPn;
		$PINV_AKUMNEXT	= $rowPINVH ->PINV_AKUMNEXT;
		$PINV_TOTVAL 	= $rowPINVH ->PINV_TOTVAL;
		$PINV_TOTVALPPn = $rowPINVH ->PINV_TOTVALPPn;
		$PINV_NOTES 	= $rowPINVH ->PINV_NOTES;
	endforeach;
}
$PINV_DATEx 	= date('Y-m-d', strtotime('-1 days', strtotime($PINV_DATE)));
$PINV_STEPB		= $PINV_STEP - 1;
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'imagess/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_menu.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_table.css'; ?>");</style>
<script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">

<div id="mainPopUp">
<table width="750" border="0" cellpadding="0" cellspacing="0">
	<tr>
        <td>
            <table width="100%" border="0" style="size:auto">
                <tr>
                	<td colspan="3" class="style2">
                    <div id="Layer1">
                        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                        <img src="<?php echo base_url().'images/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                        <a href="#" onClick="window.close();" class="button"> close </a>    	</div>
                    </td>
                </tr>
                <tr>
                	<td colspan="3" class="style2" style="text-align:left"><img src="<?php echo base_url().'images/Logo1.jpg'; ?>" width="200" height="40" /></td>
                </tr>
                <tr>
                	<td colspan="3" class="style2" style="text-align:center"><font size="+1" style="font-weight:bold; text-decoration:underline">I N V O I C E1</font></td>
                </tr>
                <tr>
                  <td colspan="3" class="style2" style="text-align:center">No. : <?php echo $PINV_MANNO; ?></td>
                </tr>
            </table>
        </td>
    </tr>
	<tr>
    	<td>&nbsp;</td>
    </tr>
</table>
<form method="post" name="frmSearch" action="">
	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
    <table width="750" border="0" cellpadding="0" cellspacing="0">
        <tr>
<td>
                <table width="100%" border="1" rules="all">
                    <tr>
                        <td colspan="4" nowrap style="font-weight:bold;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
							<?php echo $own_Name; ?></td>
                        <td width="5%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4" nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                            <?php
								echo "$own_Add1";
							?></td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" nowrap style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <?php
						$sqlPRJNM 	= "SELECT PRJNAME FROM sd_tproject WHERE PRJCODE = '$PRJCODE'";
						$resultPRJNM= $this->db->query($sqlPRJNM)->result();
						foreach($resultPRJNM  as $rowPRJNM) :
							$PRJNAME = $rowPRJNM->PRJNAME;
						endforeach;
					?>
                    <tr>
                        <td colspan="6" nowrap style="text-align:center; border-bottom-color:#FFFFFF;">
                            <font size="+1" style="font-weight:bold;"><?php echo $PRJNAME; ?></font>
                    	</td>
                    </tr>
                    <tr>
                        <td colspan="6" nowrap style="text-align:center; border-bottom-color:#FFFFFF; border-top-color:#FFFFFF;">
                            <font size="+1" style="font-weight:bold;">THE MANSION @ DUKUH GOLF KEMAYORAN</font>                        </td>
                    </tr>
                    <tr>
                        <td width="1%" nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td width="8%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td width="14%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td width="43%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" nowrap style="text-align:center;border-top-color:#FFFFFF; border-bottom-color:#FFFFFF; font-weight:bold"><font size="2">
                        	<?php
								if($PINV_CAT != 2)
								{
									echo "No. $PINV_CODE";
								}
								else
								{
									echo "No. $PINV_CODE";
								}
							?></font>                    	</td>
                    </tr>
                    <?php
						$sqlPRJ = "SELECT PRJCOST AS proj_amountIDR FROM sd_tproject WHERE PRJCODE = '$PRJCODE'";
						$resultPRJ = $this->db->query($sqlPRJ)->result();
						foreach($resultPRJ  as $rowPRJ) :
							$proj_amountIDR = $rowPRJ->proj_amountIDR;
						endforeach;
						$proj_amountIDRDP	= $proj_amountIDR;
						$sqlPINVD		= "SELECT * FROM sd_tprojinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
						$ressqlPINVD	= $this->db->query($sqlPINVD)->result();
						foreach($ressqlPINVD as $rowPINVD) :
							$adend_step 	= $rowPINVD ->adend_step;
							$adend_Code 	= $rowPINVD ->adend_Code;
						?>
                            <tr>
                                <td colspan="6" nowrap style="text-align:center; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF; font-weight:bold"><font size="2">
                                   <?php echo "Addendum - $adend_step No. $adend_Code"; ?></font>                        	</td>
                            </tr>
                    	<?php
						endforeach;
						$proj_amountIDR 	= $proj_amountIDR;
					?>
                    <tr>
                        <td colspan="4" nowrap style="text-align:right;border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="text-align:right;border-left-color:#FFFFFF;border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4" nowrap style="text-align:right;border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Nilai Pekerjaan :</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                        <td style="text-align:right;border-left-color:#FFFFFF;border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;"><?php print number_format($proj_amountIDR, 0); ?>&nbsp;</td>
                    </tr>
                    <?php
						$sqlPINVDC			= "sd_tprojinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
						$ressqlPINVDC		= $this->db->count_all($sqlPINVDC);
						$rowNoa				= 0;
						
						$sqlPINVD			= "SELECT * FROM sd_tprojinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
						$ressqlPINVD		= $this->db->query($sqlPINVD)->result();
						foreach($ressqlPINVD as $rowPINVD) :
							$rowNoa			= $rowNoa + 1;
							$adend_step 	= $rowPINVD ->adend_step;
							$adend_Code 	= $rowPINVD ->adend_Code;
							$adend_Value1A 	= $rowPINVD ->adend_Value1A;
							
							$proj_amountIDR		= $proj_amountIDR + $adend_Value1A;
							?>
							<?php
						endforeach;
						$PPNValue	= $proj_amountIDR * 10 / 100;
						$TotPPNVal	= $PPNValue + $proj_amountIDR;
					?>
                    <tr style="font-weight:bold;">
                        <td colspan="4" nowrap style="text-align:right; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                            PPN 10 % :                        </td>
                        <td style="text-align:right; border-right-color:#FFFFFF; border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF; <?php if($rowNoa != $ressqlPINVDC) { ?> border-bottom-color:#FFFFFF; <?php } ?>">Rp.</td>
                        <td style="text-align:right; border-left-color:#FFFFFF; border-top-color:#FFFFFF; <?php if($rowNoa != $ressqlPINVDC) { ?> border-bottom-color:#FFFFFF; <?php } ?>"><?php print number_format($PPNValue, 0); ?>&nbsp;</td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td colspan="4" nowrap style="text-align:right; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                            NILAI KONTRAK (TERMASUK PPN 10 %) :                        </td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                        <td style="text-align:right;border-left-color:#FFFFFF;"><?php print number_format($TotPPNVal, 0); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" nowrap style="text-align:center; border-bottom-color:#FFFFFF;">
                            <font size="+1" style="font-weight:bold; text-decoration:underline">
                            	<?php	
									$StepTerm	= KonDecRomawi($PINV_STEP);				
									if($PINV_CAT == 1)
									{
										echo 'PEMBAYARAN UANG MUKA';
									}
									else
									{
										echo "PEMBAYARAN TERMIN $StepTerm";
									}
								?>
                            </font>
                    	</td>
                    </tr>
                    <tr>
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <?php
						$MC_TOTALT	= 0;
						$MC_REFB	= explode("|",$MC_REF);
						$NoUrut		= 0;
						for($i=0;$i< count($MC_REFB);$i++)
						{
							$MC_CODE 		= $MC_REFB[$i];
							$NoUrut			= $NoUrut + 1;
							$sqlGetMCVAL	= "SELECT MC_TOTVAL FROM sd_tmcheader WHERE PRJCODE = '$PRJCODE' AND MC_CODE = '$MC_CODE'";
							$resMCVAL		= $this->db->query($sqlGetMCVAL)->result();
							foreach($resMCVAL as $rowMCVAL) :
								$MC_TOTAL 	= $rowMCVAL ->MC_TOTVAL;
								$MC_TOTALT	= round($MC_TOTALT + $MC_TOTAL);
					?>
                                <tr>
                                    <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                    <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;"><?php echo $NoUrut; ?>. Valuation No. </td>
                                    <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                    <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                        <?php
                                            $MC_TOTALAV	= number_format($MC_TOTAL, 0);
                                            echo "$MC_TOTALAV"; 
                                        ?>                      	</td>
                                </tr>
                    <?php
							endforeach;
						}
					?>
                    <tr>
                      	<td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      	<td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;"> i.  Nilai prestasi saat ini  (g - h)</td>
                      	<td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
               	  		<td style="text-align:right; border-left-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                      		<?php
								$MC_TOTALTV		= number_format($MC_TOTALT, 0);
								echo "$MC_TOTALTV"; 
							?>                      	</td>
                    </tr>
                    <tr>
                      	<td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      	<td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">j.  Nilai PPN (10 % x i)</td>
                      	<td style="text-align:left; border-left-color:#FFFFFF; border-top-color:#FFFFFF;; border-right-color:#FFFFFF;">Rp.</td>
               	  		<td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF;">
                      		<?php 
								$MC_TOTALTA		= round($MC_TOTALT * 0.1);
								$MC_TOTALTAV	= number_format($MC_TOTALTA, 0);
								echo "$MC_TOTALTAV"; 
							?>                      	</td>
                    </tr>
                    <tr>
                      <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">k. Nilai yang kami ajukan (i + j)</td>
                      <td style="text-align:left; border-left-color:#FFFFFF; border-top-color:#FFFFFF;; border-right-color:#FFFFFF; font-weight:bold;">Rp.</td>
               	  <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; font-weight:bold;">
                      		<?php 
								$MC_TOTALTB		= round($MC_TOTALT + $MC_TOTALTA);
								$MC_TOTALTBV	= number_format($MC_TOTALTB, 0);
								echo "$MC_TOTALTBV"; 
							?>                      	</td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="3" style="text-align:right;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                        	Potongan PPh (3% x i) =                        </td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF;">Rp.</td>
                  <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF;">
                      		<?php 
								$MC_TOTALTC		= round($MC_TOTALT * 0.03);
								$MC_TOTALTCV	= number_format($MC_TOTALTC, 0);
								echo "($MC_TOTALTCV)"; 
							?>                        </td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="3" style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                        	NILAI YANG DITERIMA PT NKE Tbk. =                        </td>
                        <td style="border-right-color:#FFFFFF;">Rp.</td>
                  		<td style="text-align:right;">           
                      		<?php
								$MC_TOTALTD		= round($MC_TOTALTB - $MC_TOTALTC);
								$MC_TOTALTDV	= number_format($MC_TOTALTD, 0);
								echo "$MC_TOTALTDV"; 
							?>                    	</td>
                    </tr>
                    <tr>
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <?php						
						$terbilang = $moneyFormat->terbilang($MC_TOTALTD);						
					?>
                    <tr>
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="5" style="border-left-color:#FFFFFF; border-top-color:#FFFFFF; font-weight:bold">TERBILANG : "<?php echo $terbilang; ?> Rupiah"</td>
                    </tr>
                    <tr>
                        <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <?php
						$DateY 	= date('Y');
						$DateM 	= date('m');
						$DateD 	= date('d');
						//$Date 	= "$DateY-$DateM-$DateD";	
						$date 	= new DateTime($PINV_DATE);				
					?>
                    <tr>
                        <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      	<td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                        	Jakarta, <?php echo $date->format('d F Y'); ?>
                        </td>
                    </tr>
                    <tr style="font-weight:bold">
                      <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">PT NUSA KONSTRUKSI ENJINIRING, Tbk.</td>
                    </tr>
                    <tr>
                      <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="text-align:center;font-weight:bold;text-decoration:underline;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">DJOKO EKO SUPRASTOWO</td>
                    </tr>
                    <tr>
                      <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="text-align:center;font-weight:bold;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Ditektur Utama</td>
                    </tr>
                    <tr>
                      <td nowrap style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                    </tr>
              </table>
		  </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
    </table>
</form>
</div>
<script>
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
</script>
</body>
</html>