<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Januari 2016
 * File Name	= project_invoice_print.php
 * Location		= -
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

function KonDecRomawi($angka){
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

$proj_Code		 		= $this->session->userdata['dtProjSess']['myProjSession'];
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$sqlOwner		= "SELECT own_Code, own_Title, own_Name, own_Add1 FROM sd_towner WHERE own_Code = '$Owner_Code'";
$ressqlOwner	= $this->db->query($sqlOwner)->result();
foreach($ressqlOwner as $rowqlOwner) :
	$own_Title 		= $rowqlOwner ->own_Title;
	$own_Code 		= $rowqlOwner ->own_Code;
	$own_Name 		= $rowqlOwner ->own_Name;
	$own_Add1 	= $rowqlOwner ->own_Add1;
endforeach;

$sqlPINVH			= "SELECT * FROM sd_tprojinv_header WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
$ressqlPINVH		= $this->db->query($sqlPINVH)->result();
foreach($ressqlPINVH as $rowPINVH) :
	$PINV_Step 		= $rowPINVH ->PINV_Step;
	$PINV_Date 		= $rowPINVH ->PINV_Date;
	$PINV_Datex 	= date('Y-m-d', strtotime('-1 days', strtotime($PINV_Date)));
	//$PINV_Datex 	= $PINV_Date;
	$PINV_SPKNo 	= $rowPINVH ->PINV_SPKNo;
	$PINV_SPLNo 	= $rowPINVH ->PINV_SPLNo;
	$PINV_StepM 	= $rowPINVH ->PINV_StepM;
	$PINV_Category 	= $rowPINVH ->PINV_Category;
	$AchievPercent 	= $rowPINVH ->AchievPercent;
	$AchievAmount 	= $rowPINVH ->AchievAmount;
	$AchievAmountPPn 	= $rowPINVH ->AchievAmountPPn;
	$RetentionAm 	= $rowPINVH ->RetentionAm;
	$CuttingDPAm 	= $rowPINVH ->CuttingDPAm;
endforeach;
$PINV_StepB			= $PINV_Step - 1;
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
                	<td colspan="3" class="style2" style="text-align:center"><font size="+1" style="font-weight:bold; text-decoration:underline">I N V O I C E</font></td>
                </tr>
                <tr>
                  <td colspan="3" class="style2" style="text-align:center">No. : <?php echo $PINV_Number; ?></td>
                </tr>
            </table>
        </td>
    </tr>
	<tr>
    	<td>
        	
        </td>
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
                    <tr>
                        <td colspan="6" nowrap style="text-align:center; border-bottom-color:#FFFFFF;">
                            <font size="+1" style="font-weight:bold;">PEKERJAAN STRUKTUR &amp; ARSITEKTUR</font>                    	</td>
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
								if($PINV_Category != 2)
								{
									echo "Surat Perintah Kerja (SPK) No. $PINV_SPKNo";
								}
								else
								{
									echo "Surat Pemenang Lelang <br>No. $PINV_SPLNo";
								}
							?></font>                    	</td>
                    </tr>
                    <?php
					{
						$sqlPRJ = "SELECT PRJCOST AS proj_amountIDR FROM sd_tproject WHERE PRJCODE = '$proj_Code'";
						$resultPRJ = $this->db->query($sqlPRJ)->result();
						foreach($resultPRJ  as $rowPRJ) :
							$proj_amountIDR = $rowPRJ->proj_amountIDR;
						endforeach;
						$proj_amountIDRDP	= $proj_amountIDR;
						$sqlPINVD		= "SELECT * FROM sd_tprojinv_detail WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
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
					}
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
						if($PINV_Category == 1)
						{
							$sqlPINVDC			= "sd_tprojinv_detail WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
							$ressqlPINVDC		= $this->db->count_all($sqlPINVDC);
							$rowNoa				= 0;
							
							$sqlPINVD			= "SELECT * FROM sd_tprojinv_detail WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
							$ressqlPINVD		= $this->db->query($sqlPINVD)->result();
							foreach($ressqlPINVD as $rowPINVD) :
								$rowNoa			= $rowNoa + 1;
								$adend_step 	= $rowPINVD ->adend_step;
								$adend_Code 	= $rowPINVD ->adend_Code;
								$adend_Value1A 	= $rowPINVD ->adend_Value1A;
								
								$proj_amountIDR		= $proj_amountIDR + $adend_Value1A;
								?>
                                <tr>
                                    <td colspan="4" nowrap style="text-align:right;border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                        Nilai Addendum - <?php echo "$adend_step"; ?> (Struktur) :                                    </td>
                                    <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                    <td style="text-align:right; border-left-color:#FFFFFF; border-top-color:#FFFFFF; <?php if($rowNoa != $ressqlPINVDC) { ?> border-bottom-color:#FFFFFF; <?php } ?>">
                                        <?php print number_format($adend_Value1A, 0); ?>&nbsp;                                    </td>
                                </tr>
								<?php
							endforeach;
							$PPNValue	= $proj_amountIDR * 10 / 100;
							$TotPPNVal	= $PPNValue + $proj_amountIDR;
							?>
                            <tr style="font-weight:bold;">
                                <td colspan="4" nowrap style="text-align:right; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                    Nilai Pekerjaan berikut Addendum  :                        </td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                <td style="text-align:right;border-left-color:#FFFFFF; border-bottom-color:#FFFFFF;"><?php print number_format($proj_amountIDR, 0); ?>&nbsp;</td>
                            </tr>
                            <tr style="font-weight:bold;">
                                <td colspan="4" nowrap style="text-align:right; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                    PPN 10 % :                        </td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                <td style="text-align:right;border-left-color:#FFFFFF; border-bottom-color:#FFFFFF;"><?php print number_format($PPNValue, 0); ?>&nbsp;</td>
                            </tr>
                            <tr style="font-weight:bold;">
                                <td colspan="4" nowrap style="text-align:right; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                    Nilai Akhir berikut PPN 10 % :                        </td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                <td style="text-align:right;border-left-color:#FFFFFF; border-bottom-color:#FFFFFF;"><?php print number_format($TotPPNVal, 0); ?>&nbsp;</td>
                            </tr>
                    	<?php
						}
						else
						{
							$sqlPINVDC			= "sd_tprojinv_detail WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
							$ressqlPINVDC		= $this->db->count_all($sqlPINVDC);
							$rowNoa				= 0;
							
							$sqlPINVD			= "SELECT * FROM sd_tprojinv_detail WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
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
                    	<?php
						}						
					?>
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
									$StepTerm	= KonDecRomawi($PINV_StepM);				
									if($PINV_Category == 2)
									{
										echo 'PEMBAYARAN UANG MUKA';
									}
									else
									{
										echo "PEMBAYARAN TERMIN $StepTerm";
									}
								?>
                            </font>                    	</td>
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
						$AchievPercent	= $AchievPercent;
						$AchievAmount	= $AchievAmount;
					?>
                    <tr>
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="2" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">a. Nilai Prestasi</td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                        <?php	
							$AchievPercentAll	= $AchievPercent;					
							if($PINV_Category == 2)
							{
								$AchievPercentx = '';
								echo "$AchievPercentx";
							}
							else
							{
								$AchievPercentx = number_format($AchievPercent, 4);
								echo "$AchievPercentx %";
							}
						?>                        </td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                        <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                        <?php						
							if($PINV_Category == 2)
							{
								echo '-';
							}
							else
							{
								echo number_format($AchievAmount, 0);
							}
						?>                    	</td>
                    </tr>
					<?php						
                        /*if($PINV_Category == 2)
                        {
                            echo '-';
                        }
                        else
                        {
                            echo number_format($AchievAmount, 0);
                        }*/
                    ?>
                    <?php				
                        if($PINV_Category == 2)
                        {
							$adend_Value2A	= 0;
							?>
                            <tr>
                                <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                	b. Nilai Pekerjaan Tambahan                                </td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                <td style="text-align:right; border-left-color:#FFFFFF; border-top-color:#FFFFFF;"> - </td>
                            </tr>
                            <?php
							$totPekTambah	= 0;
                        }				
                        elseif($PINV_Category == 3)
                        {
							$adend_Value2A	= 0;
							?>
                            <tr>
                                <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                	b. Nilai Pekerjaan Tambahan                                </td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                <td style="text-align:right; border-left-color:#FFFFFF; border-top-color:#FFFFFF;"> - </td>
                            </tr>
                            <?php
							$totPekTambah	= $AchievAmount;
                        }
						else
						{
							$totAdden2A		= 0;
							$sqlPINVD		= "SELECT * FROM sd_tprojinv_detail WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
							$ressqlPINVD	= $this->db->query($sqlPINVD)->result();
							$rowNo			= 0;
							foreach($ressqlPINVD as $rowPINVD) :
								$rowNo			= $rowNo + 1;
								$adend_step 	= $rowPINVD ->adend_step;
								$adend_Value1A 	= $rowPINVD ->adend_Value1A;
								$adend_Percent 	= $rowPINVD ->adend_Percent;
								$adend_Value2A 	= $rowPINVD ->adend_Value2A;
								
								$proj_amountIDR		= $proj_amountIDR + $adend_Value1A;
								$totAdden2A		= $totAdden2A + $adend_Value2A; // Nilai termasuk Pekerjaan Tambah
								
								$adendPercent	= number_format($adend_Percent, 0);
								?>
								<tr>
									<td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
									<td colspan="2" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
										<?php
											if($rowNo == 1)
											{
												echo "b. Pek. Tambah";
											}
										?>									</td>
									<td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
										- Addendum - <?php echo "$adend_step -- $adendPercent %"; ?> :                        </td>
									<td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
									<td style="text-align:right; border-left-color:#FFFFFF; border-top-color:#FFFFFF; <?php if($rowNo != $ressqlPINVDC) { ?> border-bottom-color:#FFFFFF; <?php } ?>">
										<?php echo number_format($adend_Value2A, 0); ?>									</td>
								</tr>
								<?php
							endforeach;
							$totPekTambah	= $AchievAmount + $totAdden2A;										// C. Nilai termasuk Pekerjaan Tambahan
						}
					?>
                    <tr>
                      	<td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      	<td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">c. Nilai termasuk Pekerjaan Tambah</td>
                      	<td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      	<td style="text-align:right;border-left-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                      		<?php 
								if($PINV_Category == 2)
								{
									echo "-";
								}
								else
								{
									echo number_format($totPekTambah, 0); 
								}
							?> 
                        	<input type="hidden" name="totPekTambah" id="totPekTambah" value="<?php echo $totPekTambah; ?>" />                            </td>
                    </tr>
                    <tr>
                      <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">d. Nilai Retensi  (5% x c)</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                      	<td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                      		<?php 
								if($PINV_Category == 2)
								{
									$retValue	= 0;
									$retValuexx	= '-';
									$retValuexy	= 0;
								}
								else
								{
									$retValue	= $RetentionAm * (-1);											// D. Nilai Retensi
									$retValuexy	= $RetentionAm;
									$retValuexx	= number_format($retValuexy, 0);
								}
								//echo "$retValuexx";
							?>
                            <input type="text" name="RetentionAm1" id="RetentionAm1" value="<?php echo $retValuexx; ?>" class="textbox" style="text-align:right" size="15" onchange="getRetentionAm(this.value)" />
                            <input type="hidden" name="RetentionAm" id="RetentionAm" value="<?php echo $retValuexy; ?>" />                    	</td>
                    </tr>
                    <script>						
						function getRetentionAm(thisValx)
						{
							var decFormat		= document.getElementById('decFormat').value;
							
							thisVal				= thisValx.split(",").join("");					
							document.getElementById('RetentionAm1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
							document.getElementById('RetentionAm').value 	= thisVal;
							changeCalculate();
						}
					</script>
					<?php
                        $sqlDPC = "sd_tprojinv_header WHERE proj_Code = '$proj_Code' AND PINV_Category = 2 AND PINV_Date <= '$PINV_Datex'";
                        $resultProjDPC = $this->db->count_all($sqlDPC);
						
                        if($resultProjDPC > 0)
                        {
                            $AchievAmountDP	= 0;
                            if($PINV_Category != 2)
                            {
                                $sqlDP 			= "SELECT AchievPercent, AchievAmount FROM sd_tprojinv_header 
                                                    WHERE proj_Code = '$proj_Code' AND PINV_Category = 2 AND PINV_Date <= '$PINV_Datex'";
                                $resultProjDP 	= $this->db->query($sqlDP)->result();
                            }
                            else
                            {
                                $sqlDP 			= "SELECT AchievPercent, AchievAmount FROM sd_tprojinv_header 
                                                    WHERE proj_Code = '$proj_Code' AND PINV_Category = 2 AND PINV_StepM = $PINV_StepM";
                                $resultProjDP 	= $this->db->query($sqlDP)->result();
                            }	
                        }
                        else
                        {
                            if($PINV_Category == 2)
                            {
                                $sqlDP 			= "SELECT AchievPercent, AchievAmount FROM sd_tprojinv_header 
                                                    WHERE proj_Code = '$proj_Code' AND PINV_Category = 2 AND PINV_StepM = $PINV_StepM";
                                $resultProjDP 	= $this->db->query($sqlDP)->result();
                            }
                            else
                            {
                                $AchievAmountAllDP2			= 0;
                            }
                        }
						$thisRow 		= 0;
						$totValueThisDP	= 0;
						$DPTotal		= 0;
						$CuttingDPTotal	= 0;
						
						foreach($resultProjDP as $rowDP) :
							$thisRow				= $thisRow +1;
							$AchievPercent			= $rowDP->AchievPercent;
							$AchievPercentx			= number_format($AchievPercent, 4);							
							$AchievAmountAllDP		= $rowDP->AchievAmount;
							$AchievAmountAllDP2		= $AchievAmountAllDP;
							$DPTotal				= $DPTotal + $AchievAmountAllDP2;
							
                        	$AchievAmountAllDPx		= number_format($AchievAmountAllDP2, 0);
							$DPValue				= $AchievAmountAllDP;
							
							$CuttingDPAm			= $AchievPercentAll * $AchievAmountAllDP / 100;
							
							if($PINV_Category == 2)
							{
								$PotDPValue		= 0;
								$PotDPValuex	= 0;
								$PotDPValueX	= '-';
								$PotDPValueXx	= 0;
								$totValueThisDP	= $totValueThisDP + $AchievAmountAllDP;
							}
							else
							{
								$totValueThisDP	= $totValueThisDP + $AchievAmountAllDP - $CuttingDPAm;                      
								$PotDPValue		= $CuttingDPAm * (-1);
								$PotDPValuex	= $CuttingDPAm;
								$PotDPValueXx	= number_format($PotDPValuex, 0);
								$PotDPValueX	= "($PotDPValueXx)";
							}
							$CuttingDPTotal		= $CuttingDPTotal + $PotDPValuex;
                    ?>
                            <tr>
                                <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                	<?php if($thisRow == 1) { ?>e.<?php } else { echo "&nbsp&nbsp;"; } ?> Nilai Uang Muka <?php echo $AchievPercentx; ?>%                                </td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;"><?php echo $AchievAmountAllDPx;?></td>
                            </tr>
                            <tr>
                                <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                	<?php if($thisRow == 1) { ?>f.<?php } else { echo "&nbsp&nbsp;"; } ?> Nilai Pemotongan Uang Muka <?php if($thisRow == 1) { ?>(E x A)<?php } ?>                                </td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
                                <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF;<?php if($thisRow < $resultProjDPC) { ?> border-bottom-color:#FFFFFF; <?php } ?>">
                                    <?php 
										//echo "$PotDPValueX";
                                    ?>
                                    <input type="text" name="CuttingDPAm1_<?php echo $thisRow; ?>" id="CuttingDPAm1_<?php echo $thisRow; ?>" value="<?php echo $PotDPValueXx; ?>" class="textbox" style="text-align:right" size="15" onchange="getCuttingDPAm(this.value, '<?php echo $thisRow; ?>')" />
                                    <input type="hidden" name="CuttingDPAm_<?php echo $thisRow; ?>" id="CuttingDPAm_<?php echo $thisRow; ?>" value="<?php echo $PotDPValuex; ?>" />                                    </td>
                            </tr>
                    <?php
						endforeach;
					?>
                    <script>						
						function getCuttingDPAm(thisValx, myDPRow)
						{
							var decFormat		= document.getElementById('decFormat').value;
							currCuttingDPAm1	= document.getElementById('CuttingDPAm_'+myDPRow).value;
							
							thisVal				= thisValx.split(",").join("");
							document.getElementById('CuttingDPAm1_'+myDPRow).value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
							document.getElementById('CuttingDPAm_'+myDPRow).value 	= thisVal;
							CuttingDPTotal		= document.getElementById('CuttingDPTotal').value;
							newCuttingDPTotal	= parseFloat(CuttingDPTotal) - parseFloat(currCuttingDPAm1) + parseFloat(thisVal);
							document.getElementById('CuttingDPTotal').value 		= newCuttingDPTotal;
							
							changeCalculate();
						}
					</script>
                    <input type="hidden" name="DPTotal" id="DPTotal" value="<?php echo $DPTotal; ?>" />
                    <input type="hidden" name="CuttingDPTotal" id="CuttingDPTotal" value="<?php echo $CuttingDPTotal; ?>" />
                    <tr>
                      <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">g. Nilai prestasi setelah dikurangi Retensi &amp; Uang Muka </td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
               	  <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                      		<?php
								$PresValueafterDP	= $totPekTambah + $retValue + $totValueThisDP;
								$PresValueafterDPx	= number_format($PresValueafterDP, 0);
							?>
                            <input type="text" name="PresValueafterDP1" id="PresValueafterDP1" value="<?php echo $PresValueafterDPx; ?>" class="textbox" style="text-align:right" size="15" disabled />
                            <input type="hidden" name="PresValueafterDP" id="PresValueafterDP" value="<?php echo $PresValueafterDP; ?>" />                            </td>
                    </tr>
                    <tr>
                      	<td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      	<td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">h. Nilai Pembayaran sebelumnya</td>
                      	<td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
               	  		<td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF;">
							<?php
								$sqlPayBefC 	= "sd_tprojinv_header WHERE proj_Code = '$proj_Code' AND PINV_Step = $PINV_StepB";
								$resultPayBefC 	= $this->db->count_all($sqlPayBefC);
								if($resultPayBefC > 0)
								{
									$sqlDP = "SELECT PINV_Category, thisPayment FROM sd_tprojinv_header WHERE proj_Code = '$proj_Code' AND PINV_Step = $PINV_StepB";
									$resultProjDP = $this->db->query($sqlDP)->result();
									foreach($resultProjDP as $rowDP) :
										$PINV_CategoryB	= $rowDP->PINV_Category;	
										$thisPayment	= $rowDP->thisPayment;
									endforeach;
									//$thisPayment	= 119558756350;
									if($PINV_CategoryB == 2)
									{
										$sqlSB = "SELECT PINV_Category, thisPayment FROM sd_tprojinv_header WHERE proj_Code = '$proj_Code' AND PINV_Step < $PINV_Step 
													AND PINV_Date < '$PINV_Date'";
										$resultSB = $this->db->query($sqlSB)->result();
										foreach($resultSB as $rowSB) :
											$thisPayment	= $rowSB->thisPayment;
										endforeach;
										
										/*if($resultProjDPC > 1)
										{
											$sqlSB 		= "SELECT thisPayment FROM sd_tprojinv_header
															WHERE proj_Code = '$proj_Code' AND PINV_Category = 2 ORDER BY PINV_Step desc GH"; // Step Before
											$resultSB 	= $this->db->query($sqlSB)->result();
											foreach($resultSB as $rowSB) :	
												$thisPayment	= $rowSB->thisPayment;
											endforeach;
										}*/
										//$thisPayment	= 119558756350;
									}
									
									$thisPaymentxy	= number_format($thisPayment, 0);
									$thisPaymentx	= "($thisPaymentxy)";
								}
								else
								{
									$thisPayment	= 0;
									$thisPaymentx	= '-';
								}	
								if($PINV_Category == 2)
								{
									$thisPayment	= 0;
									$thisPaymentx	= '-';
								}
								echo "$thisPaymentx"; 
							?>
                            <input type="hidden" name="thisPayment" id="thisPayment" value="<?php echo $thisPayment; ?>" />                            </td>
                  	</tr>
                    <tr>
                      <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;"> i.  Nilai prestasi saat ini  (g - h)</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
               	  <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                      		<?php
								$currPrestValue		= $PresValueafterDP - $thisPayment;
								$currPrestValuex	= number_format($currPrestValue, 0);
							?>
                            <input type="text" name="currPrestValue1" id="currPrestValue1" value="<?php echo $currPrestValuex; ?>" class="textbox" style="text-align:right" size="15" disabled />
                            <input type="hidden" name="currPrestValue" id="currPrestValue" value="<?php echo $currPrestValue; ?>" />                            </td>
                    </tr>
                    <tr>
                      <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">j.  Nilai PPN (10 % x i)</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
               	  <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF;">
                      		<?php 
								$PPNValB		= $currPrestValue * 0.1;
								$PPNValBX	= number_format($PPNValB, 0);
							?>
                            <input type="text" name="PPNValB1" id="PPNValB1" value="<?php echo $PPNValBX; ?>" class="textbox" style="text-align:right" size="15" disabled />
                            <input type="hidden" name="PPNValB" id="PPNValB" value="<?php echo $PPNValB; ?>" />                            </td>
                    </tr>
                    <tr>
                      <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                      <td colspan="3" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">k. Nilai yang kami ajukan (i + j)</td>
                      <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">Rp.</td>
               	  <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF;">
                      		<?php 
								$PengajValue	= $currPrestValue + $PPNValB;
								$PengajValuex	= number_format($PengajValue, 0);
							?>
                            <input type="text" name="PengajValue1" id="PengajValue1" value="<?php echo $PengajValuex; ?>" class="textbox" style="text-align:right" size="15" disabled />
                            <input type="hidden" name="PengajValue" id="PengajValue" value="<?php echo $PengajValue; ?>" />                            </td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="3" style="text-align:right;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                        	Potongan PPh (3% x i) =                        </td>
                        <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF;">Rp.</td>
                  <td style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF;">
                      		<?php 
								$PPhValue		= $currPrestValue * 0.03;
								$PPhValuex		= number_format($PPhValue, 0);
							?>
                            <input type="text" name="PPhValue1" id="PPhValue1" value="<?php echo $PPhValuex; ?>" class="textbox" style="text-align:right" size="15" disabled />
                            <input type="hidden" name="PPhValue" id="PPhValue" value="<?php echo $PPhValue; ?>" />                            </td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="3" style="text-align:right;border-left-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                        	NILAI YANG DITERIMA PT NKE Tbk. =                        </td>
                        <td style="border-right-color:#FFFFFF;">Rp.</td>
                  		<td style="text-align:right;">           
                      		<?php 
								$incomeValue	= $PengajValue - $PPhValue;
								$incomeValuex	= number_format($incomeValue, 0);
							?>
                            <input type="text" name="incomeValue1" id="incomeValue1" value="<?php echo $incomeValuex; ?>" class="textbox" style="text-align:right" size="15" disabled />
                            <input type="hidden" name="incomeValue" id="incomeValue" value="<?php echo $incomeValue; ?>" />                            </td>
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
						$terbilang = $moneyFormat->terbilang($incomeValue);						
					?>
                    <tr>
                        <td nowrap style="border-right-color:#FFFFFF; border-top-color:#FFFFFF;">&nbsp;</td>
                        <td colspan="4" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; font-weight:bold">TERBILANG : &quot; updating ...&quot;</td>
                        <td style="border-left-color:#FFFFFF; border-top-color:#FFFFFF; font-weight:bold; text-align:right">
                          <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" update invoice" />
                        </td>
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
						$date 	= new DateTime($PINV_Date);				
					?>
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
<?php
	if (isset($_POST['submitSrch']))
	{
		$RetentionAm	= $_POST['RetentionAm'];
		//$CuttingDPAm	= $_POST['CuttingDPAm'];
		$CuttingDPTotal	= $_POST['CuttingDPTotal'];
		$PINV_KwitAm	= $_POST['currPrestValue'];
		$PINV_KwitAmPPn	= $_POST['PPNValB'];
		$thisPayment	= $_POST['PresValueafterDP'];
		
		$sqlUPH 	= "UPDATE sd_tprojinv_header SET RetentionAm = $RetentionAm, CuttingDPAm = $CuttingDPTotal,
						PINV_KwitAm =  $PINV_KwitAm, PINV_KwitAmPPn =  $PINV_KwitAmPPn, thisPayment =  $thisPayment
						WHERE proj_Code = '$proj_Code' AND PINV_Number = '$PINV_Number'";
		$resulUPH 	= $this->db->query($sqlUPH);
		
		echo "<script type=\"text/javascript\" charset=\"utf-8\">window.self.close()</script>";
	}
?>
<script>
	function changeCalculate()
	{						
		var decFormat		= document.getElementById('decFormat').value;
		
		var totPekTambah	= document.getElementById('totPekTambah').value; 	// C
		var RetentionAm		= document.getElementById('RetentionAm').value;  	// D
		var DPTotal			= document.getElementById('DPTotal').value;  		// E
		var CuttingDPTotal	= document.getElementById('CuttingDPTotal').value; 	// F
		
		if(CuttingDPTotal > 0)
		{
			var CuttingDPTotal	= (-1) * parseFloat(CuttingDPTotal);
		}
		
		var PresValueafterDP	= parseFloat(totPekTambah) - parseFloat(RetentionAm) + parseFloat(DPTotal) + parseFloat(CuttingDPTotal);		// G		
		document.getElementById('PresValueafterDP1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PresValueafterDP)),decFormat));
		document.getElementById('PresValueafterDP').value 	= PresValueafterDP;
		
		var thisPayment		= document.getElementById('thisPayment').value; 																	// H
		
		var currPrestValue	= parseFloat(PresValueafterDP) - parseFloat(thisPayment);															// I
		document.getElementById('currPrestValue1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(currPrestValue)),decFormat));
		document.getElementById('currPrestValue').value 	= currPrestValue;
		
		var PPNValB	= parseFloat(currPrestValue) * 0.1;
		document.getElementById('PPNValB1').value			= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PPNValB)),decFormat));
		document.getElementById('PPNValB').value			= PPNValB; 																			// J
		
		var PengajValue		= parseFloat(currPrestValue) + parseFloat(PPNValB);	// K
		document.getElementById('PengajValue1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PengajValue)),decFormat));
		document.getElementById('PengajValue').value		= PengajValue;
		
		var PPhValue		= parseFloat(currPrestValue) * 0.03;
		document.getElementById('PPhValue1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PPhValue)),decFormat));
		document.getElementById('PPhValue').value	= PPhValue;
		
		var incomeValue		= parseFloat(PengajValue) - parseFloat(PPhValue);
		document.getElementById('incomeValue1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(incomeValue)),decFormat));
		document.getElementById('incomeValue').value	= incomeValue;
	}
	
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
	
	function doDecimalFormatxx(angka) {
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
		//else return (c + '.' + dec);
		else return (c);  // untuk menghilangkan 2 angka di belakang koma
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
</script>
</body>
</html>