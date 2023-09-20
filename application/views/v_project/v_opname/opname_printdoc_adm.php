<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 11 Maret 2017
 * File Name    = opname_printdoc.php
 * Location     = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$username   = $this->session->userdata('username');
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

$OPNH_NUM   = $default['OPNH_NUM'];
$OPNH_CODE  = $default['OPNH_CODE'];
$OPNH_DATESP= $default['OPNH_DATESP'];
$OPNH_NOTE  = $default['OPNH_NOTE'];
$OPNH_STAT  = $default['OPNH_STAT'];
$WO_NUM     = $default['WO_NUM'];
$DocNumber  = $default['WO_NUM'];
$WO_CODE    = $default['WO_CODE'];
$WO_DATE    = $default['WO_DATE'];
$WO_STARTD  = $default['WO_STARTD'];
$WO_ENDD    = $default['WO_ENDD'];
$PRJCODE    = $default['PRJCODE'];
$SPLCODE    = $default['SPLCODE'];
$WO_DEPT    = $default['WO_DEPT'];
$WO_CATEG   = $default['WO_CATEG'];
$JOBCODEID  = $default['JOBCODEID'];
$PR_REFNO   = $default['JOBCODEID'];
$JOBCODE1   = $PR_REFNO;
$WO_NOTE    = $default['WO_NOTE'];
$WO_NOTE2   = $default['WO_NOTE2'];
$WO_VALUE   = $default['WO_VALUE'];
$WO_STAT    = $default['WO_STAT'];
$WO_REFNO   = $default['WO_REFNO'];
$WO_MEMO    = $default['WO_MEMO'];
$FPA_CODE   = $default['FPA_CODE'];
$PRJNAME    = $default['PRJNAME'];
$WO_QUOT    = $default['WO_QUOT'];
$WO_NEGO    = $default['WO_NEGO'];

$OPNH_DATE  = '0000-00-00';
$sqlOPNH    = "SELECT OPNH_DATE FROM tbl_opn_header WHERE OPNH_NUM = '$OPNH_NUM'";
$resOPNH    = $this->db->query($sqlOPNH)->result();
foreach($resOPNH as $rowOPNH) :
    $OPNH_DATE  = $rowOPNH->OPNH_DATE;                              
endforeach;

$WOSTARTD   = date('d M Y', strtotime($WO_STARTD));
$WOENDD     = date('d M Y', strtotime($WO_ENDD));

$PRJLOCT    = ' ... ';
$PROJ_MNG   = '-';
$sqlPRJMNG  = "SELECT A.PRJLOCT, B.First_Name, B.Last_Name 
                FROM tbl_project A 
                    INNER JOIN tbl_employee B ON A.PRJ_MNG = B.Emp_ID
                WHERE A.PRJCODE = '$PRJCODE'";
$resPRJMNG  = $this->db->query($sqlPRJMNG)->result();
foreach($resPRJMNG as $rowPRJMNG) :
    $PRJLOCT    = $rowPRJMNG->PRJLOCT;
    $First_Name = $rowPRJMNG->First_Name;
    $Last_Name  = $rowPRJMNG->Last_Name;
    if($Last_Name == '')
        $PROJ_MNG   = "$First_Name";
    else
        $PROJ_MNG   = "$First_Name $Last_Name";
endforeach;
if($PROJ_MNG == '')
    $PROJ_MNG   = "-";

$JOBDESC    = '';
$sqlJDESC   = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
$resJDESC   = $this->db->query($sqlJDESC)->result();
foreach($resJDESC as $rowJDESC) :
    $JOBDESC    = $rowJDESC->JOBDESC;                               
endforeach;

$JOBDESC    = '';
$sqlJDESC   = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
$resJDESC   = $this->db->query($sqlJDESC)->result();
foreach($resJDESC as $rowJDESC) :
    $JOBDESC    = $rowJDESC->JOBDESC;                               
endforeach;

$SPLDESC    = '';
$SPLADD1    = '';
$SPLTELP    = '';
$SPLOTHR    = '';
$sqlSPLD    = "SELECT SPLDESC, SPLADD1, SPLTELP, SPLOTHR FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
$resSPLD    = $this->db->query($sqlSPLD)->result();
foreach($resSPLD as $rowSPLD) :
    $SPLDESC    = $rowSPLD->SPLDESC;
    $SPLADD1    = $rowSPLD->SPLADD1;
    $SPLTELP    = $rowSPLD->SPLTELP;
    $SPLOTHR    = $rowSPLD->SPLOTHR;                            
endforeach;


/*$TOT_TAXPPN    = 0;
$TOT_TAXPPH    = 0;
$sqlWOD     = "SELECT SUM(A.TAXPRICE1) AS TOT_PPN, SUM(A.TAXPRICE2) AS TOT_PPH
                FROM tbl_wo_detail A
                INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
                WHERE B.WO_NUM = '$WO_NUM' AND B.PRJCODE = '$PRJCODE' AND B.WO_STAT = 3";
$resWOD     = $this->db->query($sqlWOD)->result();
foreach($resWOD as $rosWOD):
    $TOT_TAXPPN    = $rosWOD->TOT_PPN;
    $TOT_TAXPPH    = $rosWOD->TOT_PPH;
endforeach;*/

$TOT_TAXPPN    = 0;
$TOT_TAXPPH    = 0;
$TOT_RETAMN    = 0;
$sqlWOD     = "SELECT OPNH_AMOUNTPPN, OPNH_AMOUNTPPH, OPNH_RETAMN FROM tbl_opn_header
                WHERE OPNH_NUM = '$OPNH_NUM'";
$resWOD     = $this->db->query($sqlWOD)->result();
foreach($resWOD as $rosWOD):
    $TOT_TAXPPN    = $rosWOD->OPNH_AMOUNTPPN;
    $TOT_TAXPPH    = $rosWOD->OPNH_AMOUNTPPH;
    $TOT_RETAMN    = $rosWOD->OPNH_RETAMN;
endforeach;

$dayList = array(
    'Sun' => 'Minggu',
    'Mon' => 'Senin',
    'Tue' => 'Selasa',
    'Wed' => 'Rabu',
    'Thu' => 'Kamis',
    'Fri' => 'Jumat',
    'Sat' => 'Sabtu'
);

class textFormat
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
        } else if ($angka < 300) {
            return sprintf('Seratus %s', $this->terbilang($angka - 100));
        } else if ($angka < 1000) {
            $hasil_bagi = (int)($angka / 100);
            $hasil_mod = $angka % 100;
            return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
        } else if ($angka < 3000) {
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
$textFormat = new textFormat();

function tanggal_indo($tanggal)
{
    $bulan = array (1 =>    'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        );
    $split = explode('-', $tanggal);
    return $bulan[ (int)$split[1] ];
}


$tanggal    = $WO_DATE;
$day        = date('D', strtotime($tanggal));
$days       = date('d', strtotime($tanggal));
$years      = date('Y', strtotime($tanggal));
$dayV       = $dayList[$day];
$dateV      = $textFormat->terbilang($days);
$monthV     = tanggal_indo($tanggal);
$yearV      = $textFormat->terbilang($years);
//$WOValueV = $textFormat->terbilang($WO_VALUE);

$days1      = date('d', strtotime($WO_DATE));
$monthV1    = tanggal_indo($WO_DATE);
$yearsV1    = date('Y', strtotime($WO_DATE));

if($WO_CATEG == 'SALT')
    $DIR2   = "Pemilik Alat";
elseif($WO_CATEG == 'MDR')
    $DIR2   = "Subkontraktor";
else
    $DIR2   = "Direktur Utama";

$DIR1       = "Kepala Proyek";

$showStamp  = 0;
$urlStamp   = '';
if($WO_STAT == 3)
{
    $showStamp  = 0;
    $urlStamp   = '';
}
elseif($WO_STAT == 5)
{
    $showStamp  = 1;
    $urlStamp   = base_url().'assets/AdminLTE-2.0.5/dist/img/failedstamp01.png';
}
else
{
    $showStamp  = 1;
    $urlStamp   = base_url().'assets/AdminLTE-2.0.5/dist/img/inprogress01.jpg';
}
$comp_name  = $this->session->userdata['comp_name'];

//GET Opname Number
$queryOPN   = $this->db->order_by('OPNH_DATE','ASC')->get_where('tbl_opn_header', array('WO_CODE' => $WO_CODE, 'OPNH_STAT' => 3));
if($queryOPN->num_rows() > 0)
{
    $noOPN = 0;
    $OPNH_TO = "";
    foreach($queryOPN->result() as $rOPN):
        $noOPN       = $noOPN + 1;
        $OPNH_CODE_T  = $rOPN->OPNH_CODE;
        if($OPNH_CODE == $OPNH_CODE_T)
        {
            $OPNH_TO  = $noOPN;
        }
    endforeach;
}
else
{
    $OPNH_TO = "";
}

$startD = date('d', strtotime($OPNH_DATESP));
$startM = tanggal_indo($OPNH_DATESP);
$startY = date('Y', strtotime($OPNH_DATESP));
$endD   = date('d', strtotime($OPNH_DATE));
$endM   = tanggal_indo($OPNH_DATE);
$endY   = date('Y', strtotime($OPNH_DATE));
$day1   = strtotime($OPNH_DATE) - strtotime($OPNH_DATESP);
$day    = floor($day1/(60 * 60 * 24));

if($OPNH_STAT == 2)
{
    $DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafCONFIRM.png) no-repeat center !important";
}
elseif($OPNH_STAT == 9)
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
    <title><?php echo $appName; ?> | Data Tables</title>
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
        width: 21cm !important;
        min-height: 29.7cm !important;
        padding-left: 1.5cm;
        padding-right: 1cm;
        padding-top: 1cm;
        padding-bottom: 1cm;
        margin: 0.5cm auto;
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
        /*@page{size: portrait;}*/
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

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1 style="display:none">
    Sorry
    <small>Page Not Found</small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<div class="page">
    <!-- <table border="0">
        <tr>
          <td colspan="3" class="style2">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php //echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>                    </td>
        </tr>
    </table> -->
    <table border="0" width="100%">
        <tr>
            <td><span class="style2" style="text-align:left; padding-left:6px;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" alt="" style="width:80px; height:30;"></span></td>
          <td width="600" style="vertical-align:top;">
            <div style="font-weight:bold; font-size:16px"><?php echo strtoupper($appName); ?></div>
            <div style="font-size:12px">
                <?php echo $comp_add; ?>
            </div>
          </td>
      </tr>
    </table>
    <div style="padding-left:10px; padding-top:5px; padding-right:10px;">
        <table border="1" width="100%" rules="all" cellpadding="0" style="border-color:black;">
            <tr>
                <td style="border-top-width:2px; border-left-width:2px; text-align:center">
                <span style="font-size:16px; font-weight:bold">OPNAME PEKERJAAN</span>
                </td>
                <td width="150" style="border-top-width:2px; border-right-width:2px; text-align:center">
                <span>No. : <?=$OPNH_CODE?></span>
                </td>
            </tr>
        </table>
        <table border="1" width="100%" rules="all" cellpadding="0" style="border-color:black;">
            <tr>
                <td width="50" style="border-left-width:2px; border-right:hidden; border-bottom:hidden;">Proyek</td>
                <td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
                <td width="300" style="border-bottom:hidden;"><?php echo $PRJNAME;?></td>
                <td width="50" style="border-right:hidden; border-bottom:hidden;">Supplier</td>
                <td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
                <td width="300" style="border-right-width:2px; border-bottom:hidden;"><?php echo ucwords($SPLDESC);?></td>
            </tr>
            <tr>
                <td width="50" style="border-left-width:2px; border-right:hidden; border-bottom:hidden;">Alamat</td>
                <td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
                <td width="300" style="border-bottom:hidden;"><?php echo $PRJLOCT;?></td>
                <td width="50" style="border-right:hidden; border-bottom:hidden;">Alamat</td>
                <td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
                <td width="300" style="border-right-width:2px; border-bottom:hidden;"><?php echo $SPLADD1;?></td>
            </tr>
            <tr>
                <td width="50" style="border-left-width:2px; border-right:hidden;">Pelaksana</td>
                <td style="border-left:hidden;border-right:hidden;">:</td>
                <td width="300"><?php //echo $PRJLOCT;?></td>
                <td width="50" style="border-right:hidden;">Telpon</td>
                <td style="border-left:hidden;border-right:hidden;">:</td>
                <td width="300" style="border-right-width:2px;"><?php echo $SPLTELP;?></td>
            </tr>
            <tr>
                <td colspan="6" style="border-left-width: 2px; border-right-width: 2px; border-top-width: 2px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="100" style="padding-left: 0px;">No. SPK</td>
                            <td width="10">:</td>
                            <td>
                                <?php echo $WO_CODE;?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" style="padding-left: 0px;">Opname Ke</td>
                            <td width="10">:</td>
                            <td>
                                <?php echo $OPNH_TO;?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" style="padding-left: 0px;">Periode Opname</td>
                            <td width="10">:</td>
                            <td>
                                <?php echo "Tanggal $startD $startM $startY s/d $endD $endM $endY  ($day Hari)";?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" style="padding-left: 0px;">Deskripsi</td>
                            <td width="10">:</td>
                            <td>
                                <?php echo $OPNH_NOTE;?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table border="1" width="100%" rules="all" cellpadding="0" style="border-color:black;">
            <tr>
                <td rowspan="2" style="border-left-width:2px; text-align:center"><span style="font-size:10px;">NO</span></td>
                <td rowspan="2" style="text-align:center"><span style="font-size:10px;">KODE ITEM</span></td>
                <td rowspan="2" style="text-align:center"><span style="font-size:10px;">KODE POS PEK.</span></td>
                <td rowspan="2" style="text-align:center"><span style="font-size:10px;">NAMA ITEM</span></td>
                <td colspan="4" style="text-align:center; border-right-width:2px;"><span style="font-size:10px;">NILAI OPNAME</span></td>
            </tr>
            <tr>
              <td style="text-align:center"><span style="font-size:10px;">VOL</span></td>
              <td style="text-align:center"><span style="font-size:10px;">SAT</span></td>
              <td style="text-align:center"><span style="font-size:10px;">HARGA</span></td>
              <td style="text-align:center; border-right-width:2px;"><span style="font-size:10px;">JUMLAH HARGA</span></td>
            </tr>

            <?php
            if($countopndet > 0):
                $no = 0;
                $OPND_GTOTAL = 0;
                $WO_TOTDISC = 0;
                foreach($vopndet->result() as $r):
                    $no = $no + 1;
                    $ITM_CODE       = $r->ITM_CODE;
                    $JOBCODEID      = $r->JOBCODEID;
                    $JOBDESC        = $r->JOBDESC;
                    $OPND_DESC      = $r->OPND_DESC;
                    $OPND_VOLM      = $r->OPND_VOLM;
                    $ITM_UNIT       = $r->ITM_UNIT;
                    $OPND_ITMPRICE  = $r->OPND_ITMPRICE;
                    $OPND_ITMTOTAL  = $r->OPND_ITMTOTAL;
                    $OPNH_AMOUNT    = $r->OPNH_AMOUNT;
                    //$OPNH_RETAMN    = $r->OPNH_RETAMN;
                    $TAXCODE1       = $r->TAXCODE1;
                    $TAXCODE2       = $r->TAXCODE2;
                    $TAXPRICE1      = $r->TAXPRICE1;
                    $TAXPRICE2      = $r->TAXPRICE2;
                    $ITM_NAME       = '';

                    $sqlDETITM      = "SELECT ITM_NAME FROM tbl_item
                                        WHERE ITM_CODE = '$ITM_CODE'
                                            AND PRJCODE = '$PRJCODE'";
                    $resDETITM      = $this->db->query($sqlDETITM)->result();
                    foreach($resDETITM as $detITM) :
                        $ITM_NAME       = $detITM->ITM_NAME;
                    endforeach;

                    // if($TAXCODE1 == 'TAX01'){
                    //     $TOT_TAXPPN     = $TOT_TAXPPN + $TAXPRICE1;
                    // }else{
                    //     $TOT_TAXPPH     = $TOT_TAXPPH + $TAXPRICE1;
                    // }
                    $OPND_GTOTAL    = $OPND_GTOTAL + $OPND_ITMTOTAL;
            ?>
            
            <tr>
                <td style="border-left-width:2px; text-align:center;"><?=$no?></td>
                <td style="text-align:center;"><?=$ITM_CODE?></td>
                <td style="text-align:center;"><?=$JOBCODEID?></td>
                <td><!-- <?=$ITM_NAME?> --><?php echo $OPND_DESC;?></td>
                <td style="text-align:center;"><?php echo number_format($OPND_VOLM,2);?></td>
                <td style="text-align:center;"><?=$ITM_UNIT?></td>
                <td style="text-align:center;"><?php echo number_format($OPND_ITMPRICE,2);?></td>
                <td style="border-right-width:2px;text-align:right;"><?php echo number_format($OPND_ITMTOTAL, 2);?></td>
            </tr>
           <?php
            endforeach;

            //row blank
            $row = $no - 1;
            if($row <= 10){
                $lnrow = 10 - $row;
                for($i=0;$i<$lnrow;$i++):
                    ?>
                    <tr>
                        <td style="border-left-width:2px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="border-right-width:2px;">&nbsp;</td>
                    </tr>
                    <?php
                endfor;
            }

            $GTOT_OPN  = $OPND_GTOTAL + $TOT_TAXPPN - $TOT_TAXPPH - $TOT_RETAMN;
                ?>
                <tr>
                    <td colspan="4" rowspan="5" style="border-left-width:2px;text-align:center;">
                        <b>Terbilang :</b> <br>
                        <i><?php echo $textFormat->terbilang(round($GTOT_OPN))." Rupiah"; ?></i>
                    </td>
                    <td colspan="3" style="text-align:right;">Total Harga</td>
                    <td style="border-right-width:2px; text-align:right;"><?php echo number_format($OPND_GTOTAL,2);?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">PPN 11%</td>
                    <td style="border-right-width:2px;text-align:right;"><?php echo number_format($TOT_TAXPPN,2);?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">RETENSI</td>
                    <td style="border-right-width:2px;text-align:right;"><?php echo number_format($TOT_RETAMN,2);?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">PPH</td>
                    <td style="border-right-width:2px;text-align:right;"><?php echo number_format($TOT_TAXPPH,2);?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:bold;">Grand Total</td>
                    <td style="border-right-width:2px;text-align:right;">
                        <?php
                            echo number_format($GTOT_OPN,2);
                        ?>
                    </td>
                </tr>
                <?php
                endif;
            ?>
            </table>
            <table border="1" width="100%" rules="all" cellpadding="0" style="border-color:black;">
                <tr>
                    <td width="73%" colspan="4" style="font-weight:bold"><span style="font-size:10px;">DIKELUARKAN DI <?php echo strtoupper($PRJLOCT);?><br>
                  YANG MEMBERIKAN PEKERJAAN : <?=$appName?></span></td>
                  <td width="27%" style="text-align:center; font-weight:bold"><span style="font-size:10px;">MENYATAKAN SETUJU MENERIMA <br>
                  PEKERJAAN</span></td>
              </tr>
              <?php
                    if($OPNH_STAT == 2){
                        $DrafTTD   = "DrafCONFIRM.png";
                        $showImg   = 1;
                    }elseif($OPNH_STAT == 3){
                        $DrafTTD   = "DrafApproved.png";
                        $showImg  = 0;
                    }elseif ($OPNH_STAT == 4) {
                        $DrafTTD   = "DrafRevised.png";
                        $showImg  = 0;
                    }elseif ($OPNH_STAT == 5) {
                        $DrafTTD   = "DrafRejected.png";
                        $showImg  = 0;
                    }elseif ($OPNH_STAT == 6) {
                        $DrafTTD   = "DrafClosed.png";
                        $showImg  = 0;
                    }elseif ($OPNH_STAT == 7) {
                        $DrafTTD   = "DrafAWAITING.png";
                        $showImg  = 0;
                    }elseif ($OPNH_STAT == 9) {
                        $DrafTTD   = "DrafVOID.png";
                        $showImg   = 1;
                    }
                ?>
                <tr>
                  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">ENGINEERING (QS)</span></td>
                  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">SITE MANAGER (SM) / <br>KOORD. MEP PROYEK</span></td>
                  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">PROJECT MANAGER</span></td>
                  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">Mgr. Subkon/ Mgr. ME/ Mgr. Opr/<br>Ka. Cabang (untuk nilai &ge; 100jt)</span></td>
                  <td style="text-align:center; font-weight:bold; border-bottom:dashed 2px"><span style="font-size:10px;">MANDOR / SUB / PEMASOK  *)<br>JAB.:</span></td>
              </tr>
                <tr>
                  <td height="79" style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <span style="font-size:10px; font-style:italic; font-weight:bold">NAMA:</span>
                  </td>
                  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <span style="font-size:10px; font-style:italic; font-weight:bold">NAMA:</span>
                  </td>
                  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <span style="font-size:10px; font-style:italic; font-weight:bold">NAMA:</span>
                  </td>
                  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <span style="font-size:10px; font-style:italic; font-weight:bold">NAMA:</span>
                  </td>
                  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <span style="font-size:10px; font-style:italic; font-weight:bold">NAMA:</span>
                  </td>
              </tr>
                <tr>
                  <td style="font-weight:bold; text-align:left; border-top:dashed 2px;"><span style="font-size:10px; font-style:italic; font-weight:bold">TGL:</span></td>
                  <td style="font-weight:bold; text-align:left; border-top:dashed 2px;"><span style="font-size:10px; font-style:italic; font-weight:bold">TGL:</span></td>
                  <td style="font-weight:bold; text-align:left; border-top:dashed 2px;"><span style="font-size:10px; font-style:italic; font-weight:bold">TGL:</span></td>
                  <td style="font-weight:bold; text-align:left; border-top:dashed 2px;"><span style="font-size:10px; font-style:italic; font-weight:bold">TGL:</span></td>
                  <td style="font-weight:bold; text-align:left; border-top:dashed 2px;"><span style="font-size:10px; font-style:italic; font-weight:bold">TGL:</span></td>
              </tr>
            </table>
            <span style="font-size:10px; font-style:italic; font-weight:bold">©Hakcipta, <?=$appName?></span>
    </div>
</div>


</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>