<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 22 Maret 2019
 * File Name  = v_itembudet_report.php
 * Location   = -
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
  $decFormat = $row->decFormat;
endforeach;
$DefEmp_ID  = $this->session->userdata['Emp_ID'];

$PRJCOST  = 0;
$PRJNAME  = '';
$PRJDATE  = date('Y/m/d');
$PRJDATE_CO = date('Y/m/d');
$sqlPRJ   = "SELECT PRJDATE, PRJNAME, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
$resPRJ   = $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
  $PRJCOST  = $rowPRJ->PRJCOST;
  $PRJNAME  = $rowPRJ->PRJNAME;
  $PRJDATE  = date('Y/m/d', strtotime($rowPRJ->PRJDATE));
  $PRJDATE_CO = date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
endforeach;

$ITM_NAME = '';
// $sqlITM   = "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' LIMIT 1";
// $resITM   = $this->db->query($sqlITM)->result();
// foreach($resITM as $rowITM):
//   $ITM_NAME = $rowITM->ITM_NAME;
// endforeach;

function cut_text($var, $len = 200, $txt_titik = "-") 
{
  $var1 = explode("</p>",$var);
  $var  = $var1[0];
  if (strlen ($var) < $len) 
  { 
    return $var; 
  }
  if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
  {
    return $match [1] . $txt_titik;
  }
  else
  {
    return substr ($var, 0, $len) . $txt_titik;
  }
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
    <style type="text/css">
        body { 
            /*margin: 0;*/
            padding: 0;
            background-color: #FAFAFA;
            font: 10pt Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 297mm;
            /*min-height: 296mm;*/
            padding-left: 0.5cm;
            padding-right: 0.5cm;
            padding-top: 0.1cm;
            padding-bottom: 0.5cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            /* background-size: 400px 200px !important;*/
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        @page {
            /*size: auto;*/
            size: lanscape;
            /* margin: 0; */
        }

        @media print {

            @page{size: lanscape;}
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

        .jobH table thead th {
            border-top: 1px solid black !important;
            border-bottom: 1px solid black !important;
            border-right: 1px dashed black !important;
            /*font-weight: bold;*/
        }
        .jobH table tr td {
            border-top: 1px solid lightgray !important;
            border-bottom: 1px solid lightgray !important;
            border-right: 1px dashed lightgray !important;
            /*font-weight: bold;*/
        }

        #Layer1 {
            position: absolute;
            top: 35px;
            left: 1100px;
        }
    </style>
</head>
<body style="overflow:auto">
    <div class="page">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
            <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>                
        </div>            
        <section class="content">
            <table width="100%" border="0" style="size:auto">
                <tr>
                    <td width="50" rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" width="180"></td>
                    <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">
                        LAPORAN DETIL BUDGET <span style="font-size: 10pt; display:block; padding-top:5px;">PERIODE: <?php echo date('d-m-Y', strtotime($Start_Date));?>  S/D <?php echo date('d-m-Y', strtotime($End_Date));?></span>
                    </td>
            </tr>
                <tr>
                    <td colspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:16px"><?php /*?>&nbsp;Periode : <?php */?>
                    <?php 
                    /*$CRDate = date_create($End_Date);
                    echo date_format($CRDate,"d-m-Y");*/
                    $StartDate  = date('d-m-Y');
                    //echo "$StartDate";
                ?></td>
                </tr>
            </table>
            <?php
            // get BUDGET
                $arrITM_CODE    = implode("','", $ITM_CODE);
                $getBUD_ITM     = "SELECT JOBCODEID, JOBPARENT, JOBDESC, ITM_CODE, ITM_UNIT, ITM_VOLM, ITM_BUDG, ADD_VOLM, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail 
                                    WHERE PRJCODE = '$PRJCODE' AND JOBCODEID IN ('$arrITM_CODE') AND JOBPARENT = '$JOBPARENT'";
                $resBUD_ITM     = $this->db->query($getBUD_ITM);
                $ITM_VOLM2      = 0;
                $ITM_BUDG2      = 0;
                if($resBUD_ITM->num_rows() > 0)
                {
                    foreach($resBUD_ITM->result() as $rB):
                        $JOBCODEID      = $rB->JOBCODEID;
                        $JOBPARENT      = $rB->JOBPARENT;
                        $JOBDESC        = $rB->JOBDESC;
                        $ITM_CODE       = $rB->ITM_CODE;
                        $ITM_UNIT       = $rB->ITM_UNIT;
                        $ITM_VOLMBG     = $rB->ITM_VOLM;
                        $ITM_BUDG       = $rB->ITM_BUDG;
                        $ADD_VOLM       = $rB->ADD_VOLM;
                        $ADD_JOBCOST    = $rB->ADD_JOBCOST;
                        $ADDM_VOLM      = $rB->ADDM_VOLM;
                        $ADDM_JOBCOST   = $rB->ADDM_JOBCOST;

                    // Addendum
                        $ADDVOLM        = $ADD_VOLM - $ADDM_VOLM;
                        $ADD_TOTAL      = $ADD_JOBCOST - $ADDM_JOBCOST;

                    // after addendum
                        $ITM_VOLMBG2    = $ITM_VOLMBG + $ADDVOLM;
                        $ITM_BUDG2      = $ITM_BUDG + $ADD_TOTAL;

                        // get Item name
                        $resITM        = $this->db->select("ITM_CODE, ITM_NAME, ITM_UNIT")->get_where("tbl_item", ["PRJCODE" => $PRJCODE, "ITM_CODE" => $ITM_CODE]);
                        if($resITM->num_rows() > 0)
                        {
                            foreach($resITM->result() as $r):
                                $ITM_CODE    = $r->ITM_CODE;
                                $ITM_NAME    = $r->ITM_NAME;
                                $ITM_UNIT    = $r->ITM_UNIT;
                            endforeach;
                        }
                        
                    // get Induk pekerjaan
                        $resJOBH        = $this->db->select("JOBCODEID, JOBDESC")->get_where("tbl_joblist", ["PRJCODE" => $PRJCODE, "JOBCODEID" => $JOBPARENT]);
                        if($resJOBH->num_rows() > 0)
                        {
                            foreach($resJOBH->result() as $rJH):
                                $JOBCODEID_H    = $rJH->JOBCODEID;
                                $JOBDESC_H      = $rJH->JOBDESC;
                            endforeach;
                        }
                        
                    $REM_VOLMBG     = $ITM_VOLMBG2;
                    $REM_BUDG       = $ITM_BUDG2;

                    // get Data VC
                        $getDataVC     = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, A.JournalD_Debet, A.JournalD_Kredit,
                                            A.PPN_Code, A.PPN_Perc, A.PPN_Amount, A.PPH_Code, A.PPH_Perc, A.PPH_Amount, A.JOBCODEID, A.ITM_CODE, 
                                            A.ITM_GROUP, A.ITM_CATEG, A.ITM_VOLM, A.ITM_PRICE, A.ITM_UNIT, A.Other_Desc, 
                                            B.GEJ_STAT, B.STATDESC, B.Manual_No, B.JournalH_Desc
                                            FROM tbl_journaldetail_vcash A 
                                            INNER JOIN tbl_journalheader_vcash B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                            WHERE A.proj_Code = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.GEJ_STAT IN (3,6,9)
                                            AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')";
                        $resDataVC     = $this->db->query($getDataVC);
                        if($resDataVC->num_rows() > 0)
                        {
                            ?>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td class="style2" style="text-align:left; font-style:italic">
                                        <table width="100%">
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$h1_title"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">PERIODE LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$datePeriod"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Proyek</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Proyek</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Pekerjaan</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID_H"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Pekerjaan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC_H;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Item</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Item</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Satuan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $ITM_UNIT;?></span></td>
                                        </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">TANGGAL CETAK</td>
                                            <td>:</td>
                                            <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                                    <td class="style2" style="text-align:left; font-style:italic; vertical-align: bottom;">
                                        <table width="100%" border="0" rules="all" style="border-bottom: 1px solid;">
                                            <tr style="text-align:left; font-style:italic;  border: 2px double;">
                                                <td width="50" nowrap valign="top" style="font-weight: bold;"><?php //echo "$SelJOBCODEID_H - $SelJOBDESC_H"; ?></td>
                                                <td width="50" style="font-weight: bold; text-align: center;">VOL.</td>
                                                <td width="150" style="font-weight: bold; text-align: center;">JML HARGA</td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">BUDGET AWAL</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ITM_BUDG, 2);?></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">AMANDEMENT BUDGET</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ADD_TOTAL, 2); ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">BUDGET AKHIR</td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_VOLMBG2, 2); ?></td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center"><hr /></td>
                                </tr>
                            </table>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3">
                                        <div class="jobH">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                                <thead style="background-color: lightgray;">
                                                    <tr>
                                                        <th colspan="10" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                                        <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">JENIS TX</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                        <th style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no             = 0;
                                                        $TOT_ITMUSEDAM  = 0;
                                                        $REM_VOLMBG     = $ITM_VOLMBG2;
                                                        $REM_BUDG       = $ITM_BUDG2;
                                                        foreach($resDataVC->result() as $rVC):
                                                            $no                 = $no + 1;
                                                            $JournalH_Code      = $rVC->JournalH_Code; 
                                                            $JournalH_Date      = $rVC->JournalH_Date; 
                                                            $JournalType        = $rVC->JournalType; 
                                                            $JournalH_Desc      = $rVC->JournalH_Desc; 
                                                            $Acc_Id             = $rVC->Acc_Id; 
                                                            $JournalD_Debet     = $rVC->JournalD_Debet; 
                                                            $JournalD_Kredit    = $rVC->JournalD_Kredit; 
                                                            $PPN_Code           = $rVC->PPN_Code; 
                                                            $PPN_Perc           = $rVC->PPN_Perc; 
                                                            $PPN_Amount         = $rVC->PPN_Amount; 
                                                            $PPH_Code           = $rVC->PPH_Code; 
                                                            $PPH_Perc           = $rVC->PPH_Perc; 
                                                            $PPH_Amount         = $rVC->PPH_Amount; 
                                                            $JOBCODEID          = $rVC->JOBCODEID; 
                                                            $ITM_CODE           = $rVC->ITM_CODE; 
                                                            $ITM_GROUP          = $rVC->ITM_GROUP; 
                                                            $ITM_CATEG          = $rVC->ITM_CATEG; 
                                                            $ITM_VOLM           = $rVC->ITM_VOLM; 
                                                            $ITM_PRICE          = $rVC->ITM_PRICE; 
                                                            $ITM_UNIT           = $rVC->ITM_UNIT; 
                                                            $Other_Desc         = $rVC->Other_Desc; 
                                                            $Manual_No          = $rVC->Manual_No; 
                                                            $GEJ_STAT           = $rVC->GEJ_STAT;
                                                            $STATDESC           = $rVC->STATDESC;
                                                            $Trans_Type         = "Voucher Cash";

                                                            $s_00   = "SELECT Other_Desc FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$JournalH_Code'
                                                                        AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
                                                            $r_00   = $this->db->query($s_00)->result();
                                                            foreach($r_00 as $rw_00):
                                                                $JournalH_Desc    = $rw_00->Other_Desc;
                                                            endforeach;

                                                            $TOT_ITMUSEDAM      = $TOT_ITMUSEDAM + $JournalD_Debet;

                                                            // sisa budget
                                                                if($GEJ_STAT != 9)
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                                    $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }
                                                                else
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG;
                                                                    $REM_BUDG       = $REM_BUDG;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }

                                                            ?>
                                                                <tr>
                                                                    <td style="text-align: center;"><?php echo $no; ?></td>
                                                                    <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
                                                                    <td style="text-align: center;"><?php echo $Trans_Type; ?></td>
                                                                    <td nowrap style="text-align: center;"><?php echo $Manual_No; ?></td>
                                                                    <td><?php echo "$JournalH_Desc"; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_VOLM; ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                                    <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                                    <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, 3); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($REM_BUDG, 2); ?></td>
                                                                </tr>
                                                            <?php
                                                        endforeach;
                                                            ?>
                                                                <tr>
                                                                    <td colspan="8" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                                    <td style="text-align: right;"><?php echo number_format($TOT_ITMUSEDAM, 2); ?></td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            <?php
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }

                    // get Data VLK
                        $getDataVLK     = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, A.JournalD_Debet, 
                                            A.JournalD_Kredit,
                                            A.PPN_Code, A.PPN_Perc, A.PPN_Amount, A.PPH_Code, A.PPH_Perc, A.PPH_Amount, A.JOBCODEID, A.ITM_CODE, 
                                            A.ITM_GROUP, A.ITM_CATEG, A.ITM_VOLM, A.ITM_PRICE, A.ITM_UNIT, A.Other_Desc, 
                                            B.GEJ_STAT, B.STATDESC, B.Manual_No, B.JournalH_Desc
                                            FROM tbl_journaldetail_cprj A 
                                            INNER JOIN tbl_journalheader_cprj B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                            WHERE A.proj_Code = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.GEJ_STAT IN (3,9)
                                            AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')";
                        $resDataVLK     = $this->db->query($getDataVLK);
                        if($resDataVLK->num_rows() > 0)
                        {
                            ?>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td class="style2" style="text-align:left; font-style:italic">
                                        <table width="100%">
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$h1_title"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">PERIODE LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$datePeriod"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Proyek</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Proyek</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Pekerjaan</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID_H"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Pekerjaan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC_H;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Item</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Item</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Satuan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $ITM_UNIT;?></span></td>
                                        </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">TANGGAL CETAK</td>
                                            <td>:</td>
                                            <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                                    <td class="style2" style="text-align:left; font-style:italic; vertical-align: bottom;">
                                        <table width="100%" border="0" rules="all" style="border-bottom: 1px solid;">
                                            <tr style="text-align:left; font-style:italic;  border: 2px double;">
                                                <td width="50" nowrap valign="top" style="font-weight: bold;"><?php //echo "$SelJOBCODEID_H - $SelJOBDESC_H"; ?></td>
                                                <td width="50" style="font-weight: bold; text-align: center;">VOL.</td>
                                                <td width="150" style="font-weight: bold; text-align: center;">JML HARGA</td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">BUDGET AWAL</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ITM_BUDG, 2);?></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">AMANDEMENT BUDGET</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ADD_TOTAL, 2); ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">BUDGET AKHIR</td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_VOLMBG2, 2); ?></td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center"><hr /></td>
                                </tr>
                            </table>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3">
                                        <div class="jobH">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                                <thead style="background-color: lightgray;">
                                                    <tr>
                                                        <th colspan="10" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                                        <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">JENIS TX</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                        <th style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no             = 0;
                                                        $TOT_ITMUSEDAM  = 0;
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG;
                                                        foreach($resDataVLK->result() as $rVLK):
                                                            $no                 = $no + 1;
                                                            $JournalH_Code      = $rVLK->JournalH_Code; 
                                                            $JournalH_Date      = $rVLK->JournalH_Date; 
                                                            $JournalH_Desc      = $rVLK->JournalH_Desc;
                                                            $JournalType        = $rVLK->JournalType; 
                                                            $Acc_Id             = $rVLK->Acc_Id; 
                                                            $JournalD_Debet     = $rVLK->JournalD_Debet; 
                                                            $JournalD_Kredit    = $rVLK->JournalD_Kredit; 
                                                            $PPN_Code           = $rVLK->PPN_Code; 
                                                            $PPN_Perc           = $rVLK->PPN_Perc; 
                                                            $PPN_Amount         = $rVLK->PPN_Amount; 
                                                            $PPH_Code           = $rVLK->PPH_Code; 
                                                            $PPH_Perc           = $rVLK->PPH_Perc; 
                                                            $PPH_Amount         = $rVLK->PPH_Amount; 
                                                            $JOBCODEID          = $rVLK->JOBCODEID; 
                                                            $ITM_CODE           = $rVLK->ITM_CODE; 
                                                            $ITM_GROUP          = $rVLK->ITM_GROUP; 
                                                            $ITM_CATEG          = $rVLK->ITM_CATEG; 
                                                            $ITM_VOLM           = $rVLK->ITM_VOLM; 
                                                            $ITM_PRICE          = $rVLK->ITM_PRICE; 
                                                            $ITM_UNIT           = $rVLK->ITM_UNIT; 
                                                            $Other_Desc         = $rVLK->Other_Desc; 
                                                            $Manual_No          = $rVLK->Manual_No; 
                                                            $GEJ_STAT           = $rVLK->GEJ_STAT;
                                                            $STATDESC           = $rVLK->STATDESC;
                                                            $Trans_Type         = "Voucher LK";

                                                            $s_00   = "SELECT Other_Desc FROM tbl_journaldetail_cprj WHERE JournalH_Code = '$JournalH_Code'
                                                                        AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
                                                            $r_00   = $this->db->query($s_00)->result();
                                                            foreach($r_00 as $rw_00):
                                                                $JournalH_Desc    = $rw_00->Other_Desc;
                                                            endforeach;

                                                            $TOT_ITMUSEDAM      = $TOT_ITMUSEDAM + $JournalD_Debet;

                                                            // sisa budget
                                                                if($GEJ_STAT != 9)
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                                    $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }
                                                                else
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG;
                                                                    $REM_BUDG       = $REM_BUDG;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }

                                                            ?>
                                                                <tr>
                                                                    <td style="text-align: center;"><?php echo $no; ?></td>
                                                                    <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
                                                                    <td style="text-align: center;"><?php echo $Trans_Type; ?></td>
                                                                    <td nowrap style="text-align: center;"><?php echo $Manual_No; ?></td>
                                                                    <td><?php echo "$JournalH_Desc"; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_VOLM; ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                                    <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                                    <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, 3); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($REM_BUDG, 2); ?></td>
                                                                </tr>
                                                            <?php
                                                        endforeach;
                                                            ?>
                                                                <tr>
                                                                    <td colspan="8" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                                    <td style="text-align: right;"><?php echo number_format($TOT_ITMUSEDAM, 2); ?></td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            <?php
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }

                    // get Data PD
                        $getDataPD     = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, A.JournalD_Debet, 
                                            A.JournalD_Kredit,
                                            A.PPN_Code, A.PPN_Perc, A.PPN_Amount, A.PPH_Code, A.PPH_Perc, A.PPH_Amount, A.JOBCODEID, A.ITM_CODE, 
                                            A.ITM_GROUP, A.ITM_CATEG, A.ITM_VOLM, A.ITM_PRICE, A.ITM_UNIT, A.Other_Desc, 
                                            B.GEJ_STAT, B.STATDESC, B.Manual_No, B.JournalH_Desc
                                            FROM tbl_journaldetail_pd A 
                                            INNER JOIN tbl_journalheader_pd B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                            WHERE A.proj_Code = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.GEJ_STAT IN (3,6,9)
                                            AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')";
                        $resDataPD     = $this->db->query($getDataPD);
                        if($resDataPD->num_rows() > 0)
                        {
                            ?>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td class="style2" style="text-align:left; font-style:italic">
                                        <table width="100%">
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$h1_title"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">PERIODE LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$datePeriod"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Proyek</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Proyek</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Pekerjaan</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID_H"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Pekerjaan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC_H;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Item</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Item</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Satuan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $ITM_UNIT;?></span></td>
                                        </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">TANGGAL CETAK</td>
                                            <td>:</td>
                                            <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                                    <td class="style2" style="text-align:left; font-style:italic; vertical-align: bottom;">
                                        <table width="100%" border="0" rules="all" style="border-bottom: 1px solid;">
                                            <tr style="text-align:left; font-style:italic;  border: 2px double;">
                                                <td width="50" nowrap valign="top" style="font-weight: bold;"><?php //echo "$SelJOBCODEID_H - $SelJOBDESC_H"; ?></td>
                                                <td width="50" style="font-weight: bold; text-align: center;">VOL.</td>
                                                <td width="150" style="font-weight: bold; text-align: center;">JML HARGA</td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">BUDGET AWAL</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ITM_BUDG, 2);?></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">AMANDEMENT BUDGET</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ADD_TOTAL, 2); ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">BUDGET AKHIR</td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_VOLMBG2, 2); ?></td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center"><hr /></td>
                                </tr>
                            </table>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3">
                                        <div class="jobH">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                                <thead style="background-color: lightgray;">
                                                    <tr>
                                                        <th colspan="10" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                                        <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">JENIS TX</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                        <th style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no             = 0;
                                                        $TOT_ITMUSEDAM  = 0;
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG;
                                                        foreach($resDataPD->result() as $rPD):
                                                            $no                 = $no + 1;
                                                            $JournalH_Code      = $rPD->JournalH_Code; 
                                                            $JournalH_Date      = $rPD->JournalH_Date; 
                                                            $JournalH_Desc      = $rPD->JournalH_Desc;
                                                            $JournalType        = $rPD->JournalType; 
                                                            $Acc_Id             = $rPD->Acc_Id; 
                                                            $JournalD_Debet     = $rPD->JournalD_Debet; 
                                                            $JournalD_Kredit    = $rPD->JournalD_Kredit; 
                                                            $PPN_Code           = $rPD->PPN_Code; 
                                                            $PPN_Perc           = $rPD->PPN_Perc; 
                                                            $PPN_Amount         = $rPD->PPN_Amount; 
                                                            $PPH_Code           = $rPD->PPH_Code; 
                                                            $PPH_Perc           = $rPD->PPH_Perc; 
                                                            $PPH_Amount         = $rPD->PPH_Amount; 
                                                            $JOBCODEID          = $rPD->JOBCODEID; 
                                                            $ITM_CODE           = $rPD->ITM_CODE; 
                                                            $ITM_GROUP          = $rPD->ITM_GROUP; 
                                                            $ITM_CATEG          = $rPD->ITM_CATEG; 
                                                            $ITM_VOLM           = $rPD->ITM_VOLM; 
                                                            $ITM_PRICE          = $rPD->ITM_PRICE; 
                                                            $ITM_UNIT           = $rPD->ITM_UNIT; 
                                                            $Other_Desc         = $rPD->Other_Desc; 
                                                            $Manual_No          = $rPD->Manual_No; 
                                                            $GEJ_STAT           = $rPD->GEJ_STAT;
                                                            $STATDESC           = $rPD->STATDESC;
                                                            $Trans_Type         = "Voucher PD";

                                                            $s_00   = "SELECT Other_Desc FROM tbl_journaldetail_pd WHERE JournalH_Code = '$JournalH_Code'
                                                                        AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
                                                            $r_00   = $this->db->query($s_00)->result();
                                                            foreach($r_00 as $rw_00):
                                                                $JournalH_Desc    = $rw_00->Other_Desc;
                                                            endforeach;
                                                
                                                            $TOT_ITMUSEDAM      = $TOT_ITMUSEDAM + $JournalD_Debet;
                                                
                                                            // sisa budget
                                                                if($GEJ_STAT != 9)
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                                    $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }
                                                                else
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG;
                                                                    $REM_BUDG       = $REM_BUDG;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }

                                                            ?>
                                                                <tr>
                                                                    <td style="text-align: center;"><?php echo $no; ?></td>
                                                                    <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
                                                                    <td style="text-align: center;"><?php echo $Trans_Type; ?></td>
                                                                    <td nowrap style="text-align: center;"><?php echo $Manual_No; ?></td>
                                                                    <td><?php echo "$JournalH_Desc"; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_VOLM; ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                                    <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                                    <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, 3); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($REM_BUDG, 2); ?></td>
                                                                </tr>
                                                            <?php
                                                        endforeach;
                                                            ?>
                                                                <tr>
                                                                    <td colspan="8" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                                    <td style="text-align: right;"><?php echo number_format($TOT_ITMUSEDAM, 2); ?></td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            <?php
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }

                    // get Data IR
                        $getDataIR     = "SELECT A.*, 
                                            B.IR_STAT, B.STATDESC, B.IR_CODE, B.IR_NOTE
                                            FROM tbl_ir_detail A 
                                            INNER JOIN tbl_ir_header B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
                                            WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.IR_STAT IN (3,9)
                                            AND A.IR_DATE BETWEEN '$Start_Date' AND '$End_Date'";
                        $resDataIR     = $this->db->query($getDataIR);
                        if($resDataIR->num_rows() > 0)
                        {
                            ?>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td class="style2" style="text-align:left; font-style:italic">
                                        <table width="100%">
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$h1_title"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">PERIODE LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$datePeriod"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Proyek</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Proyek</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Pekerjaan</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID_H"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Pekerjaan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC_H;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Item</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Item</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Satuan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $ITM_UNIT;?></span></td>
                                        </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">TANGGAL CETAK</td>
                                            <td>:</td>
                                            <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                                    <td class="style2" style="text-align:left; font-style:italic; vertical-align: bottom;">
                                        <table width="100%" border="0" rules="all" style="border-bottom: 1px solid;">
                                            <tr style="text-align:left; font-style:italic;  border: 2px double;">
                                                <td width="50" nowrap valign="top" style="font-weight: bold;"><?php //echo "$SelJOBCODEID_H - $SelJOBDESC_H"; ?></td>
                                                <td width="50" style="font-weight: bold; text-align: center;">VOL.</td>
                                                <td width="150" style="font-weight: bold; text-align: center;">JML HARGA</td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">BUDGET AWAL</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ITM_BUDG, 2);?></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">AMANDEMENT BUDGET</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ADD_TOTAL, 2); ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">BUDGET AKHIR</td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_VOLMBG2, 2); ?></td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center"><hr /></td>
                                </tr>
                            </table>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3">
                                        <div class="jobH">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                                <thead style="background-color: lightgray;">
                                                    <tr>
                                                        <th colspan="10" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                                        <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">JENIS TX</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                        <th style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no             = 0;
                                                        $TOT_ITMUSEDAM  = 0;
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG;
                                                        foreach($resDataIR->result() as $rIR):
                                                            $no                 = $no + 1;
                                                            $IR_NUM             = $rIR->IR_NUM; 
                                                            $IR_DATE            = $rIR->IR_DATE; 
                                                            $IR_NOTE            = $rIR->IR_NOTE; 
                                                            $ITM_TOTAL          = $rIR->ITM_TOTAL;
                                                            $JOBCODEID          = $rIR->JOBCODEID; 
                                                            $ITM_CODE           = $rIR->ITM_CODE; 
                                                            $ITM_GROUP          = $rIR->ITM_GROUP; 
                                                            $ITM_CATEG          = $rIR->ITM_GROUP; 
                                                            $ITM_QTY            = $rIR->ITM_QTY; 
                                                            $ITM_PRICE          = $rIR->ITM_PRICE; 
                                                            $ITM_UNIT           = $rIR->ITM_UNIT; 
                                                            $NOTES              = $rIR->NOTES; 
                                                            $IR_CODE            = $rIR->IR_CODE; 
                                                            $IR_STAT            = $rIR->IR_STAT;
                                                            $STATDESC           = $rIR->STATDESC;
                                                            $Trans_Type         = "LPM";

                                                            $TOT_ITMUSEDAM      = $TOT_ITMUSEDAM + $ITM_TOTAL;

                                                            // sisa budget
                                                                if($IR_STAT != 9)
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG - $ITM_QTY;
                                                                    $REM_BUDG       = $REM_BUDG - $ITM_TOTAL;
                                                                    // echo "$REM_BUDG       = $REM_BUDG - $ITM_TOTAL";
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }
                                                                else
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG;
                                                                    $REM_BUDG       = $REM_BUDG;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }

                                                            ?>
                                                                <tr>
                                                                    <td style="text-align: center;"><?php echo $no; ?></td>
                                                                    <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($IR_DATE)); ?></td>
                                                                    <td style="text-align: center;"><?php echo $Trans_Type; ?></td>
                                                                    <td nowrap style="text-align: center;"><?php echo $IR_CODE; ?></td>
                                                                    <td><?php echo "$NOTES"; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_QTY; ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($ITM_TOTAL, 2); ?></td>
                                                                    <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                                    <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, 3); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($REM_BUDG, 2); ?></td>
                                                                </tr>
                                                            <?php
                                                        endforeach;
                                                            ?>
                                                                <tr>
                                                                    <td colspan="8" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                                    <td style="text-align: right;"><?php echo number_format($TOT_ITMUSEDAM, 2); ?></td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            <?php
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }

                    // get Data OPN
                        $getDataOPN     = "SELECT A.*, 
                                            B.OPNH_STAT, B.STATDESC, B.OPNH_CODE, B.OPNH_NOTE
                                            FROM tbl_opn_detail A 
                                            INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM AND B.PRJCODE = A.PRJCODE
                                            WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.OPNH_STAT IN (3,9)
                                            AND A.OPNH_DATE BETWEEN '$Start_Date' AND '$End_Date'";
                        $resDataOPN     = $this->db->query($getDataOPN);
                        if($resDataOPN->num_rows() > 0)
                        {
                            ?>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td class="style2" style="text-align:left; font-style:italic">
                                        <table width="100%">
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$h1_title"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <td width="8%" nowrap valign="top">PERIODE LAPORAN</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$datePeriod"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Proyek</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Proyek</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Pekerjaan</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID_H"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Pekerjaan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC_H;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                                <td width="8%" nowrap valign="top">Kode Item</td>
                                                <td width="0%">:</td>
                                                <td width="92%"><?php echo "$JOBCODEID"; ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Nama Item</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $JOBDESC;?></span></td>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">Satuan</td>
                                            <td>:</td>
                                            <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $ITM_UNIT;?></span></td>
                                        </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">TANGGAL CETAK</td>
                                            <td>:</td>
                                            <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                                    <td class="style2" style="text-align:left; font-style:italic; vertical-align: bottom;">
                                        <table width="100%" border="0" rules="all" style="border-bottom: 1px solid;">
                                            <tr style="text-align:left; font-style:italic;  border: 2px double;">
                                                <td width="50" nowrap valign="top" style="font-weight: bold;"><?php //echo "$SelJOBCODEID_H - $SelJOBDESC_H"; ?></td>
                                                <td width="50" style="font-weight: bold; text-align: center;">VOL.</td>
                                                <td width="150" style="font-weight: bold; text-align: center;">JML HARGA</td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">BUDGET AWAL</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ITM_BUDG, 2);?></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <td width="50" nowrap valign="top">AMANDEMENT BUDGET</td>
                                                <td width="50" style="text-align: right;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                                <td width="150" style="text-align: right;"><?php echo number_format($ADD_TOTAL, 2); ?></span></td>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                            <td nowrap valign="top">BUDGET AKHIR</td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_VOLMBG2, 2); ?></td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="style2" style="text-align:center"><hr /></td>
                                </tr>
                            </table>
                            <table width="100%" border="0" style="size:auto">
                                <tr>
                                    <td colspan="3">
                                        <div class="jobH">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                                <thead style="background-color: lightgray;">
                                                    <tr>
                                                        <th colspan="10" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                                        <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">JENIS TX</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                        <th style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                        <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                        <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                        <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no             = 0;
                                                        $TOT_ITMUSEDAM  = 0;
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG;
                                                        foreach($resDataOPN->result() as $rOPN):
                                                            $no                 = $no + 1;
                                                            $OPNH_NUM           = $rOPN->OPNH_NUM; 
                                                            $OPNH_DATE          = $rOPN->OPNH_DATE; 
                                                            $OPND_DESC          = $rOPN->OPND_DESC; 
                                                            $OPND_ITMTOTAL      = $rOPN->OPND_ITMTOTAL;
                                                            $JOBCODEID          = $rOPN->JOBCODEID; 
                                                            $ITM_CODE           = $rOPN->ITM_CODE; 
                                                            $ITM_GROUP          = $rOPN->ITM_GROUP; 
                                                            $ITM_CATEG          = $rOPN->ITM_GROUP; 
                                                            $OPND_VOLM          = $rOPN->OPND_VOLM; 
                                                            $ITM_PRICE          = $rOPN->OPND_ITMPRICE; 
                                                            $ITM_UNIT           = $rOPN->ITM_UNIT; 
                                                            $OPNH_NOTE          = $rOPN->OPNH_NOTE; 
                                                            $OPNH_CODE          = $rOPN->OPNH_CODE; 
                                                            $OPNH_STAT          = $rOPN->OPNH_STAT;
                                                            $STATDESC           = $rOPN->STATDESC;
                                                            $Trans_Type         = "OPN";

                                                            $TOT_ITMUSEDAM      = $TOT_ITMUSEDAM + $OPND_ITMTOTAL;

                                                            // sisa budget
                                                                if($OPNH_STAT != 9)
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG - $OPND_VOLM;
                                                                    $REM_BUDG       = $REM_BUDG - $OPND_ITMTOTAL;
                                                                    // echo "$REM_BUDG       = $REM_BUDG - $ITM_TOTAL";
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }
                                                                else
                                                                {
                                                                    $REM_VOLMBG     = $REM_VOLMBG;
                                                                    $REM_BUDG       = $REM_BUDG;
                                                                    if($REM_VOLMBG == 0)
                                                                        $REM_PRICEBG = 0;
                                                                    else
                                                                        $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                                }

                                                            ?>
                                                                <tr>
                                                                    <td style="text-align: center;"><?php echo $no; ?></td>
                                                                    <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($OPNH_DATE)); ?></td>
                                                                    <td style="text-align: center;"><?php echo $Trans_Type; ?></td>
                                                                    <td nowrap style="text-align: center;"><?php echo $OPNH_CODE; ?></td>
                                                                    <td><?php echo "$OPND_DESC"; ?></td>
                                                                    <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                                    <td style="text-align: center;"><?php echo $OPND_VOLM; ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($OPND_ITMTOTAL, 2); ?></td>
                                                                    <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                                    <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, 3); ?></td>
                                                                    <td style="text-align: right;"><?php echo number_format($REM_BUDG, 2); ?></td>
                                                                </tr>
                                                            <?php
                                                        endforeach;
                                                            ?>
                                                                <tr>
                                                                    <td colspan="8" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                                    <td style="text-align: right;"><?php echo number_format($TOT_ITMUSEDAM, 2); ?></td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            <?php
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }

                endforeach;
            }
        ?>
        </section>
    </div>
</body>
</html>
<?php
    if(isset($this->session->userdata['vers']))
        $vers  = $this->session->userdata['vers'];
    else
        $vers  = '2.0.5';

    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk1  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
        <?php
    endforeach;
?>
<script>
	$(function(){
		document.onkeydown = (event) => {
			console.log(event);
		    if (event.ctrlKey) {
		        event.preventDefault();
		        // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
		    }   
		};
	});
</script>