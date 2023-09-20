<?php
/*
 * Author       = Dian Hermanto
 * Create Date  = 3 April 2019
 * File Name    = v_amd_print.php
 * Location     = -*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;
$Start_DateY    = date('Y');
$Start_DateM    = date('m');
$Start_DateD    = date('d');
$Start_Date     = "$Start_DateY-$Start_DateM-$Start_DateD";
$LangID         = $this->session->userdata['LangID'];
if($LangID == 'IND')
{
    $Transl_01  = "Halaman ini merupakan contoh untuk mencetak dokumen permintaan pembelian. Silahkan ajukan kepada kami untuk membuat halaman yang sebenarnya.";
}
else
{
    $Transl_01  = "This page is an example to print a purchase request document. Please feel free to ask us to create an actual page.";
}

$sql_01 = "SELECT * FROM tappname";
$res_01 = $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
    $comp_name  = $row_01->comp_name;
    $comp_add   = $row_01->comp_add;
    $comp_phone = $row_01->comp_phone;
    $comp_mail  = $row_01->comp_mail;
endforeach;

$AMD_NUM        = $default['AMD_NUM'];
$AMD_CODE       = $default['AMD_CODE'];
$AMD_DATE       = $default['AMD_DATE'];
$PRJCODE        = $default['PRJCODE'];
$JOBCODEID      = $default['JOBCODEID'];
$AMD_NOTES      = $default['AMD_NOTES'];
$AMD_STAT       = $default['AMD_STAT'];

$AMD_DATEV  = date('d M Y', strtotime($AMD_DATE));
$sqlPRJ     = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ     = $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
    $PRJNAME= $rowPRJ->PRJNAME;
endforeach;

if($AMD_STAT == 2)
{
    $DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafCONFIRM.png) no-repeat center !important";
}
elseif($AMD_STAT == 9)
{
    $DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafVOID.png) no-repeat center !important";
}
else
{
    $DrafTTD1   = "white";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">

    <style type="text/css">
        body {
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
            }
            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }
            .page {
            width: 29.7cm;
            min-height: 21cm;
            padding-left:0.5cm;
            padding-right:0.5cm;
            padding-top:1cm;
            padding-bottom:1cm;
            /*padding: 0.01cm 0.2cm;*/
            margin: 1cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: <?php echo $DrafTTD1;?>;
            background-size: 550px 300px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        @page {
            /*size: A4;*/
            margin: 0;
        }
        @media print {
            /*@page{size: landscape}*/
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

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>


<?php
    //$this->load->view('template/topbar');
    //$this->load->view('template/sidebar');
    
    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    $resTransl      = $this->db->query($sqlTransl)->result();
    foreach($resTransl as $rowTransl) :
        $TranslCode = $rowTransl->MLANG_CODE;
        $LangTransl = $rowTransl->LangTransl;
        
        if($TranslCode == 'From')$From = $LangTransl;
        if($TranslCode == 'To_x')$To_x = $LangTransl;
        if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
        if($TranslCode == 'OrderID')$OrderID = $LangTransl;
        if($TranslCode == 'DueDate')$DueDate = $LangTransl;
        if($TranslCode == 'To_x')$To_x = $LangTransl;
        if($TranslCode == 'To_x')$To_x = $LangTransl;
    endforeach
?>

<body class="hold-transition skin-blue sidebar-mini">

    <!-- <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php //echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>
    <div class="pad margin no-print" style="display:none">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php //echo $Transl_01; ?>
        </div>
    </div> -->
    <!-- Main content -->
    <div class="page">
        <table border="0" width="100%">
            <tr>
                <td width="120"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" width="120" height="80" ></td>
                <td style="text-align:center; font-size:18px; font-weight:bold">FORM AMANDEMEN</td>
                <td width="160" style="font-size: 11px;">
                    No. Ref. : <?php echo $AMD_CODE; ?>
                </td>
            </tr>
        </table>
        <div class="col-md-6 col-sm-6" style="padding-left: 0px; padding-top: 10px; padding-bottom: 10px;">
            <table border="0" width="100%">
                <tr>
                    <td width="150" style="vertical-align: top; font-size: 12px;">Tanggal Permintaan</td>
                    <td style="vertical-align: top; font-size: 12px;">:</td>
                    <td style="vertical-align: top; font-size: 12px;" width="350"><?php echo $AMD_DATEV; ?></td>
                </tr>
                <tr>
                    <td width="150" style="font-size: 12px;">Deskripsi</td>
                    <td style="font-size: 12px;">:</td>
                    <td width="350" style="font-size: 12px;"><?php echo $AMD_NOTES; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6 col-sm-6" style="padding-top: 10px; padding-bottom: 10px;">
            <table border="0" width="100%">
                <tr>
                    <td width="150" style="font-size: 12px;">Nama Proyek</td>
                    <td style="font-size: 12px;">:</td>
                    <td width="350" style="font-size: 12px;"><?php echo $PRJNAME; ?></td>
                </tr>
                <tr>
                    <td width="150" style="font-size: 12px;">Nomor Proyek</td>
                    <td style="font-size: 12px;">:</td>
                    <td width="350" style="font-size: 12px;"><?php echo $PRJCODE; ?></td>
                </tr>
            </table>
        </div>
        <table width="100%" border="1" rules="all">
            <tr>
                <td rowspan="2" style="background-color:#999; vertical-align:middle"><span style="font-size: 11px; font-weight: bold;"> NO.</span></td>
                <td rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">KODE</span></td>
                <td rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">DESKRIPSI</span></td>
                <td rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">SAT</span></td>
                <td colspan="3" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">RENCANA</span></td>
                <td colspan="3" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">SEBELUMNYA</span></td>
                <td colspan="3" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">PERUBAHAN</span></td>
                <td colspan="3" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">S.D SAAT INI</span></td>
                <td rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">KETERANGAN</span></td>
            </tr>
            <tr>
                <td style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">VOL.</span></td>
                <td style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">HARGA</span></td>
                <td style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">JUMLAH</span></td>
                <td style="background-color:#999; text-align:center"><span style="font-size: 11px; font-weight: bold;">VOL.</span></td>
                <td style="background-color:#999; text-align:center"><span style="font-size: 11px; font-weight: bold;">HARGA</span></td>
                <td style="background-color:#999; text-align:center"><span style="font-size: 11px; font-weight: bold;">JUMLAH</span></td>
                <td style="background-color:#999; text-align:center"><span style="font-size: 11px; font-weight: bold;">VOL.</span></td>
                <td style="background-color:#999; text-align:center"><span style="font-size: 11px; font-weight: bold;">HARGA</span></td>
                <td style="background-color:#999; text-align:center"><span style="font-size: 11px; font-weight: bold;">JUMLAH</span></td>
                <td style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">VOL.</span></td>
                <td style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">HARGA</span></td>
                <td style="background-color:#999; vertical-align:middle; text-align:center"><span style="font-size: 11px; font-weight: bold;">JUMLAH</span></td>
            </tr>
            <?php
                $maxRow     = 10;
                $rowNo      = 0;
                $sqlAMDC    = "tbl_amd_detail A
                                INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
                                LEFT JOIN tbl_joblist_detail C ON C.JOBCODEID = A.JOBCODEID
                                    AND A.PRJCODE = C.PRJCODE
                                LEFT JOIN tbl_item D ON D.ITM_CODE = C.ITM_CODE 
                                    AND A.PRJCODE = D.PRJCODE
                                    AND C.ITM_CODE = A.ITM_CODE
                                -- AND B.AMD_STAT IN (3, 6)
                                WHERE
                                    A.AMD_NUM = '$AMD_NUM'";
                $resAMDC    = $this->db->count_all($sqlAMDC);
                    
                if($resAMDC > 0)
                {
                    $sqlAMDD    = "SELECT A.*,
                                        B.AMD_DATE, B.AMD_CREATED, D.ITM_NAME, B.AMD_CATEG
                                    FROM
                                        tbl_amd_detail A
                                    INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
                                    LEFT JOIN tbl_joblist_detail C ON C.JOBCODEID = A.JOBCODEID
                                        AND A.PRJCODE = C.PRJCODE
                                    LEFT JOIN tbl_item D ON D.ITM_CODE = C.ITM_CODE 
                                        AND A.PRJCODE = D.PRJCODE
                                        AND C.ITM_CODE = A.ITM_CODE
                                    -- AND B.AMD_STAT IN (3, 6)
                                    WHERE
                                        A.AMD_NUM = '$AMD_NUM'";
                    $resAMDD    = $this->db->query($sqlAMDD)->result();
                    foreach($resAMDD as $rowAMD) :
                        $rowNo      = $rowNo + 1;
                        $AMD_NUM    = $rowAMD->AMD_NUM;
                        $JOBCODEID  = $rowAMD->JOBCODEID;
                        $JOBDESC    = $rowAMD->JOBDESC;
                        $ITM_GROUP  = $rowAMD->ITM_GROUP;
                        $ITM_CODE   = $rowAMD->ITM_CODE;
                        $AMD_DATE   = $rowAMD->AMD_DATE;
                        $AMD_CREATED= $rowAMD->AMD_CREATED;
                        $ITM_NAME   = $rowAMD->ITM_NAME;
                        $ITM_UNIT   = $rowAMD->ITM_UNIT;
                        $AMD_VOLM   = $rowAMD->AMD_VOLM;
                        $AMD_PRICE  = $rowAMD->AMD_PRICE;
                        $AMD_TOTAL  = $rowAMD->AMD_TOTAL;
                        $AMD_DESC   = $rowAMD->AMD_DESC;
                        $AMD_CATEG  = $rowAMD->AMD_CATEG;

                        // RENCANA AWAL : INGAT HARUS SEBELUM DITAMBAH AMANDEMEN
                            $ITM_VOLMBA     = 0;
                            $ITM_PRICEBA    = 0;
                            $ITM_BUDGA      = 0;
                            $sqlITM         = "SELECT ITM_VOLM, ITM_PRICE, ITM_BUDG, ITM_USED_AM FROM tbl_joblist_detail
                                                WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
                            $resITM         = $this->db->query($sqlITM)->result();
                            foreach($resITM as $rowITM) :
                                $ITM_VOLMBA  = $rowITM->ITM_VOLM;
                                $ITM_PRICEBA = $rowITM->ITM_PRICE;
                                $ITM_BUDGA   = $rowITM->ITM_BUDG;  
                                $ITM_USED_AM = $rowITM->ITM_USED_AM;           
                            endforeach;
                        
                        $ITM_VOLMB  = $ITM_VOLMBA;
                        $ITM_PRICEB = $ITM_PRICEBA;
                        $ITM_BUDG   = $ITM_BUDGA;
                        if($AMD_CATEG == 'NB')
                        {
                            $AMD_VOLMT  = 0;
                            $AMD_PRICET = 0;
                            $AMD_TOTALT = 0;
                            $sqlITMT    = "SELECT SUM(A.AMD_VOLM) AS AMD_VOLMBF, AMD_PRICE AS AMD_PRICEBF, SUM(A.AMD_TOTAL) AS AMD_TOTALBF FROM tbl_amd_detail A
                                                INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
                                                    AND B.AMD_STAT IN (3,6)
                                            WHERE JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE'";
                            $resITMT    = $this->db->query($sqlITMT)->result();
                            foreach($resITMT as $rowITMT) :
                                $AMD_VOLMT  = $rowITMT->AMD_VOLMBF;
                                $AMD_PRICET = $rowITMT->AMD_PRICEBF;
                                $AMD_TOTALT = $rowITMT->AMD_TOTALBF;                           
                            endforeach;
                            $ITM_VOLMB  = $ITM_VOLMBA - abs($AMD_VOLMT);
                            $ITM_PRICEB = $ITM_PRICEBA - abs($AMD_PRICET);
                            $ITM_BUDG   = $ITM_BUDGA - abs($AMD_TOTALT);
                        }
                                
                        // AMD BEFORE
                            $AMD_VOLMBF = 0;
                            $AMD_PRICEBF= 0;
                            $AMD_TOTALBF= 0;
                            $sqlITM2    = "SELECT SUM(A.AMD_VOLM) AS AMD_VOLMBF, AMD_PRICE AS AMD_PRICEBF, SUM(A.AMD_TOTAL) AS AMD_TOTALBF FROM tbl_amd_detail A
                                                INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
                                                    AND B.AMD_STAT IN (3,6)
                                                    AND B.AMD_CREATED < '$AMD_CREATED'
                                            WHERE JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE'";
                            $resITM2    = $this->db->query($sqlITM2)->result();
                            foreach($resITM2 as $rowITM2) :
                                $AMD_VOLMBF = $rowITM2->AMD_VOLMBF;
                                $AMD_PRICEBF= $rowITM2->AMD_PRICEBF;
                                $AMD_TOTALBF= $rowITM2->AMD_TOTALBF;                           
                            endforeach;

                        // AMD CHANGE
                            $ITM_VOLMB2 = 0;
                            $ITM_PRICEB2= 0;
                            $ITM_BUDG2  = 0;
                            $sqlITM1    = "SELECT SUM(A.AMD_VOLM) AS AMD_VOLM1, AMD_PRICE AS AMD_PRICE1, SUM(A.AMD_TOTAL) AS AMD_TOTAL1 FROM tbl_amd_detail A
                                                INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
                                                    -- AND B.AMD_STAT IN (3,6)
                                                    AND B.AMD_CREATED = '$AMD_CREATED'
                                            WHERE JOBCODEID = '$JOBCODEID'";
                            $resITM1    = $this->db->query($sqlITM1)->result();
                                foreach($resITM1 as $rowITM1) :
                                    $AMD_VOLM1 = $rowITM1->AMD_VOLM1;
                                    $AMD_PRICE1= $rowITM1->AMD_PRICE1;
                                    $AMD_TOTAL1= $rowITM1->AMD_TOTAL1;                           
                                endforeach;

                        
                        // $ITM_VOLMBBF    = $ITM_VOLMB + $AMD_VOLMBF; //AMD_VOLM BEFORE
                        // $ITM_TOTALBF    = $ITM_VOLMBBF * $AMD_PRICE; //AMD_TOTAL BEFORE

                        $AMD_VOLM       = $ITM_VOLMB + $AMD_VOLMBF + $AMD_VOLM1;                            
                        $AMD_TOTAL      = $ITM_BUDG + $AMD_TOTALBF + $AMD_TOTAL1;
                        //$AMD_REMTOT     = $AMD_TOTAL - $ITM_USED_AM; 
                        ?>
                        <tr>
                            <td style="text-align:center; font-size: 11px;"><?php echo $rowNo; ?>.</td>
                            <td style="font-size: 11px;" width="50"><?php echo $ITM_CODE; ?></td>
                            <td style="font-size: 11px;"><?php echo $ITM_NAME; ?></td>
                            <td style="text-align:center; font-size: 11px;"><?php echo $ITM_UNIT; ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($ITM_VOLMB, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($ITM_PRICEB, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($ITM_BUDG, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_VOLMBF, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_PRICEBF, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_TOTALBF, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_VOLM1, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_PRICE1, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_TOTAL1, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_VOLM, $decFormat); ?></td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($ITM_PRICEB, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-size: 11px;"><?php echo number_format($AMD_TOTAL, $decFormat); ?>&nbsp;</td>
                            <td width="100" style="font-size: 11px;"><?php echo $AMD_DESC; ?></td>
                        </tr>
                        <?php
                    endforeach;

                    $rowRem = $maxRow - $rowNo;
                    for($i=0; $i < $rowRem; $i++)
                    {
                    ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align:center">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php
                    }
                }
            ?>
            <tr height="80px">
                <td colspan="17">&nbsp;</td>
            </tr>
        </table>
        <table width="100%" border="1" rules="all">
            <?php
                if($AMD_STAT == 2){
                    $DrafTTD   = "DrafCONFIRM.png";
                    $showImg   = 1;
                }elseif($AMD_STAT == 3){
                    $DrafTTD   = "DrafApproved.png";
                    $showImg  = 0;
                }elseif ($AMD_STAT == 4) {
                    $DrafTTD   = "DrafRevised.png";
                    $showImg  = 0;
                }elseif ($AMD_STAT == 5) {
                    $DrafTTD   = "DrafRejected.png";
                    $showImg  = 0;
                }elseif ($AMD_STAT == 6) {
                    $DrafTTD   = "DrafClosed.png";
                    $showImg  = 0;
                }elseif ($AMD_STAT == 7) {
                    $DrafTTD   = "DrafAWAITING.png";
                    $showImg  = 0;
                }elseif ($AMD_STAT == 9) {
                    $DrafTTD   = "DrafVOID.png";
                    $showImg   = 1;
                }
            ?>
            <tr>
                <td width="25%">Dibuat Oleh : <br><br>Paraf : <br><br>Tanggal :</td>
                <td width="25%">
                    Diperiksa Oleh : <br><br>Paraf : <br><br>Tanggal :
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 130px; position: absolute; margin-top: -60px; margin-left: 30px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                </td>
                <td width="25%">
                    Diperiksa Oleh : <br><br>Paraf : <br><br>Tanggal :
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 130px; position: absolute; margin-top: -60px; margin-left: 30px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                </td>
                <td width="25%">
                    Disetujui Oleh : <br><br>Paraf : <br><br>Tanggal :
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 130px; position: absolute; margin-top: -60px; margin-left: 30px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                </td>
            </tr>
        </table>
    </div>
</body>

</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>