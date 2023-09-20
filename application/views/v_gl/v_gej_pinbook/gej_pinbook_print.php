<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 13 April 2022
 * File Name    = gej_pinbook_print.php
 * Location     = -
*/

//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;

$sqlApp         = "SELECT * FROM tappname";
$resultaApp     = $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
    $appName    = $therow->app_name;
    $comp_init  = $therow->comp_init;
    $comp_name  = $therow->comp_name;
endforeach;

$DefEmp_ID  = $this->session->userdata['Emp_ID'];

$LangID     = $this->session->userdata['LangID'];
if($LangID == 'IND')
{
    $h1_title   = 'JURNAL UMUM';
}
else
{
    $h1_title   = 'GENERAL JOURNAL';
}
//$CB_DATE = date('Y-m-d',strtotime($CB_DATE));

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

$JH_CODE    = '';
$JH_CODEY   = '';
$JH_DATEY   = '';
$JH_DATEM   = '';
$JH_DATED   = '';
$BNK_DESC   = '';
$JH_DESC    = '';
$CREATER_ID = '';
$sqlJH      = "SELECT DISTINCT A.JournalH_Code, B.Manual_No, B.JournalH_Date, B.JournalH_Desc, A.Acc_Id, C.Account_NameId,
                    B.Emp_ID AS CREATER_ID
                FROM
                    tbl_journaldetail A
                INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                INNER JOIN tbl_chartaccount C ON A.Acc_Id = C.Account_Number
                WHERE
                    A.JournalH_Code = '$CB_NUM'";
$resJH  = $this->db->query($sqlJH)->result();
foreach($resJH as $rowJH) :
    $JH_CODE    = $rowJH->JournalH_Code;
    $JH_CODEM   = $rowJH->Manual_No;
    $JH_DATEY   = date('Y', strtotime($rowJH->JournalH_Date));
    $JH_DATEM   = date('m', strtotime($rowJH->JournalH_Date));
    $JH_DATED   = date('d', strtotime($rowJH->JournalH_Date));
    $JH_DESC    = $rowJH->JournalH_Desc;
    $BNK_DESC   = $rowJH->Account_NameId;
    $CREATER_ID  = $rowJH->CREATER_ID;
endforeach;

$KD_BANK    = substr($JH_CODEM, 0, 3);
if($KD_BANK != 'GEJ')
{
    $NO_CODE    = substr($JH_CODEM, -3, 3);
    $DATE_CODE  = substr($JH_CODEM, 3, -3);
    $DATE_CODEY = substr($DATE_CODE, 0, 4);
    $DATE_CODEM = substr($DATE_CODE, 4, -2);
    $DATE_CODED = substr($DATE_CODE, -2, 2);
}
else
{
    $NO_CODE    = substr($JH_CODEM, -3, 3);
    $DATE_CODE  = substr($JH_CODEM, 3, -3);
    $DATE_CODEY = $JH_DATEY;
    $DATE_CODEM = $JH_DATEM;
    $DATE_CODED = $JH_DATED;
}
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
            font: 12pt Arial, Helvetica, sans-serif;
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .page {
        width: 21cm;
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
<div class="page">
    <table width="100%" border="0" style="size:auto; font-family:Arial, Helvetica, sans-serif; font-size:12px">
        <tr>
            <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
            <td colspan="7" class="style2">&nbsp;</td>
            <td colspan="3" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="11" valign="top" nowrap class="style2" style="text-align:center; font-weight:bold;"><?php echo $h1_title; ?><br></td>
          </tr>
        <tr>
          <td rowspan="3" valign="top" nowrap class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/'. $comp_init . '/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
          <td width="9%" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
          <td colspan="4" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
          <td width="15%" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
          <td width="4%" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
          <td width="17%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
          <td width="1%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
          <td width="16%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
        </tr>
        <tr>
          <td rowspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
          <td colspan="3" rowspan="2"  nowrap class="style2" style="text-align:left;">
            <?php /*?>PUTIH &nbsp;<br>
            MERAH&nbsp;&nbsp;<br>
            KUNING&nbsp;&nbsp;<?php */?> </td>
          <td width="29%" rowspan="2"  nowrap class="style2" style="text-align:left;">
          <?php /*?><span class="style2" style="text-align:left;">
          : &nbsp;GENERAL ACCOUNTING<br>
          : &nbsp;COST/BUDGET CONTROL<br>
          : &nbsp;ARSIP  
          </span><?php */?>
          </td>
          <td class="style2" style="text-align:left;">&nbsp;</td>
          <td colspan="4" rowspan="2" class="style2" style="text-align:left;">
            <table border="1" rules="all" width="100%">
              <tr>
                <td width="25%" nowrap style="text-align:center">Kode Bank</td>
                <td width="25%" style="text-align:center">Tahun</td>
                <td width="25%" style="text-align:center">Bulan</td>
                <td width="25%" style="text-align:center">Tanggal</td>
                <td width="25%" nowrap style="text-align:center">No. Urut</td>
                </tr>
              <tr>
                <td style="text-align:center"><?php echo $KD_BANK; ?></td>
                <td style="text-align:center"><?php echo $DATE_CODEY; ?></td>
                <td style="text-align:center"><?php echo $DATE_CODEM; ?></td>
                <td style="text-align:center"><?php echo $DATE_CODED; ?></td>
                <td style="text-align:center" nowrap><?php echo $NO_CODE; ?></td>
                </tr>
              </table>      </td>
          </tr>
        <tr>
          <td class="style2" style="text-align:right;" nowrap>NO. URUT JURNAL : </td>
        </tr>
        <tr>
          <td colspan="6" nowrap class="style2" style="text-align:left;">&nbsp;</td>
          <td class="style2" style="text-align:right;" nowrap>&nbsp;</td>
          <td colspan="4" nowrap class="style2" style="text-align:left;">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="11" class="style2" height="4" style="text-align:left">DESKRIPSI : <?php echo $JH_DESC; ?></td>
        </tr>
        <tr>
          <td colspan="11" class="style2" height="4" style="text-align:left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="11" class="style2">
              <table width="100%" border="1" rules="all">
                    <tr style="background:#CCCCCC">
                        <td width="4%" height="37" nowrap style="text-align:center; font-weight:bold">No.</td>
                        <td width="13%" nowrap style="text-align:center; font-weight:bold">No. Akun</td>
                        <td width="44%" nowrap style="text-align:center; font-weight:bold">Deskripsi</td>
                        <td width="11%" nowrap style="text-align:center; font-weight:bold">Debit</td>
                        <td width="9%" nowrap style="text-align:center; font-weight:bold">Kredit</td>
                        <td width="19%" nowrap style="text-align:center; font-weight:bold">Keterangan</td>
                    </tr>
                    <?php
                        $maxROW     = 10;
                        $noU        = 0;
                        $i          = 0;
                        $totAmount  = 0;
                        $sqlq2      = "SELECT DISTINCT A.Acc_Id, A.proj_Code, A.Base_Kredit, A.JournalD_Debet, A.JournalD_Debet_tax,
                                            A.JournalD_Kredit, A.JournalD_Kredit_tax, A.isDirect, A.Notes, A.ITM_CODE,
                                            A.Ref_Number, A.Other_Desc, A.Journal_DK, A.isTax, B.Account_NameEn, C.JournalH_Desc
                                        FROM tbl_journaldetail A
                                            INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number
                                                AND B.PRJCODE = '$PRJCODE'
                                            INNER JOIN tbl_journalheader C ON A.JournalH_Code = C.JournalH_Code
                                        WHERE A.JournalH_Code = '$CB_NUM'";
                        $resq2  = $this->db->query($sqlq2)->result();
                        foreach($resq2 as $rowsqlq2) :
                            $noU                = $noU + 1;
                            $Acc_Id             = $rowsqlq2->Acc_Id;
                            $PRJCODE            = $rowsqlq2->proj_Code;
                            $Base_Kredit        = $rowsqlq2->Base_Kredit;
                            $Description        = $rowsqlq2->Account_NameEn;
                            $BaseDebet          = $rowsqlq2->JournalD_Debet;
                            $JournalD_Debet_tax = $rowsqlq2->JournalD_Debet_tax;
                            $BaseKredit         = $rowsqlq2->JournalD_Kredit;
                            $JournalD_Kredit_tax= $rowsqlq2->JournalD_Kredit_tax;
                            $ITM_CODE           = $rowsqlq2->ITM_CODE;
                            $isDirect           = $rowsqlq2->isDirect;
                            $Ref_Number         = $rowsqlq2->Ref_Number;
                            $Other_Desc         = $rowsqlq2->Other_Desc;
                            $Journal_DK         = $rowsqlq2->Journal_DK;
                            $isTax              = $rowsqlq2->isTax;
                            $JournalHDesc       = $rowsqlq2->JournalH_Desc;
                            
                            if($ITM_CODE != '')
                            {
                                $sqlITM     = "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
                                $resITM     = $this->db->query($sqlITM)->result();
                                foreach($resITM as $rowITM) :
                                    $ITM_NAME   = $rowITM->ITM_NAME;
                                endforeach;
                                $Description    = $ITM_NAME;
                            }
                            else
                            {
                                $sqlITM     = "SELECT Account_NameId FROM tbl_chartaccount 
                                                WHERE Account_Number = '$Acc_Id'";
                                $resITM     = $this->db->query($sqlITM)->result();
                                foreach($resITM as $rowITM) :
                                    $ITM_NAME   = $rowITM->Account_NameId;
                                endforeach;
                                $Description    = $ITM_NAME;
                            }
                            
                            if($Other_Desc == '')
                            {
                                $JournalHDesc       = $rowsqlq2->JournalH_Desc;
                            }
                            else
                            {
                                $JournalHDesc       = $Other_Desc;
                            }
                            
                            if($BaseDebet == 0)
                            {
                                $BaseDebetV     = "";
                            }
                            else
                            {
                                $BaseDebetV     = number_format($BaseDebet,2);
                            }
                            
                            if($BaseKredit == 0)
                            {
                                $BaseKreditV        = "";
                            }
                            else
                            {
                                $BaseKreditV        = number_format($BaseKredit,2);
                            }
                            ?>
                                <tr>
                                    <td nowrap style="text-align:center;">
                                        <?php echo "$noU."; ?>&nbsp;
                                    </td>
                                    <td nowrap style="text-align:left;">
                                        <?php echo "$Acc_Id"; ?>&nbsp;
                                    </td>
                                    <td nowrap>
                                        <?php echo strtoupper($Description); ?>
                                    </td>
                                    <td nowrap style="text-align:right;">
                                        <?php echo $BaseDebetV; ?>&nbsp;
                                    </td>
                                    <td nowrap style="text-align:right;">
                                        <?php echo $BaseKreditV; ?>&nbsp;
                                    </td>
                                    <td nowrap style="text-align:left;">
                                        <?php echo strtoupper($JournalHDesc); ?>
                                    </td>
                                </tr>
                            <?php
                            $remROW = $maxROW - $noU;
                        endforeach;
                        
                        for($i=0;$i<=$remROW;$i++)
                        {
                        ?>
                            <tr>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                            </tr>
                        <?php
                        }
                        ?>
                            <tr>
                                <td colspan="6">Terbilang : <?php echo $moneyFormat->terbilang($BaseDebet); ?> Rupiah</td>
                            </tr>
                        <?php
                    ?>
            </table>
                <br>
                <br>
                <br>
                <table width="100%" border="0">
                    <tr>
                        <td colspan="2" style="text-align:center">Dibuat Oleh:</td>
                        <td colspan="2" style="text-align:center">Diperiksa Oleh</td>
                        <td colspan="2" style="text-align:center">Disetujui Oleh:</td>
                    </tr>
                    <tr height="40px">
                      <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                      <td width="8%" style="text-align:center" valign="top">&nbsp;</td>
                      <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                      <td width="8%" style="text-align:center" valign="top">&nbsp;</td>
                      <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                      <td width="8%" style="text-align:center" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" style="text-align:center;"><font style="text-decoration:underline">Nama : ..........................</font><br>Tanggal : ......................</td>
                      <td colspan="2" style="text-align:center"><font style="text-decoration:underline">Nama : ..........................</font><br>Tanggal : ......................</td>
                      <td colspan="2" style="text-align:center"><font style="text-decoration:underline">Nama : ..........................</font><br>Tanggal : ......................</td>
                    </tr>
                    <tr>
                      <td colspan="2" style="text-align:left">&nbsp;</td>
                      <td colspan="2" style="text-align:left">&nbsp;</td>
                      <td colspan="2" style="text-align:center">&nbsp;</td>
                    </tr>
                </table></td>
        </tr>
    </table>
</div>
</body>
</html>