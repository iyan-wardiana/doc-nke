<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 September 2018
 * File Name	= spk_printdoc_salt.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$username 	= $this->session->userdata('username');
//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function 
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";

$WO_NUM 	= $default['WO_NUM'];
$DocNumber	= $default['WO_NUM'];
$WO_CODE 	= $default['WO_CODE'];
$WO_DATE 	= $default['WO_DATE'];
$WO_STARTD 	= $default['WO_STARTD'];
$WO_ENDD 	= $default['WO_ENDD'];
$PRJCODE	= $default['PRJCODE'];
$SPLCODE 	= $default['SPLCODE'];
$WO_DEPT 	= $default['WO_DEPT'];
$WO_CATEG 	= $default['WO_CATEG'];
$JOBCODEID 	= $default['JOBCODEID'];
$PR_REFNO	= $default['JOBCODEID'];
$JOBCODE1	= $PR_REFNO;
$WO_NOTE 	= $default['WO_NOTE'];
$WO_NOTE2 	= $default['WO_NOTE2'];
$WO_VALUE	= $default['WO_VALUE'];
$WO_STAT 	= $default['WO_STAT'];
$WO_MEMO 	= $default['WO_MEMO'];
$FPA_CODE 	= $default['FPA_CODE'];
$PRJNAME 	= $default['PRJNAME'];
$WO_QUOT 	= $default['WO_QUOT'];
$WO_NEGO 	= $default['WO_NEGO'];

$WO_PAYTYPE     = 1;
$WO_PAYTYPED    = "Fix Unit Rate (Harga Satuan Tetap)";
$sqlWOH         = "SELECT WO_PAYTYPE FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
$resWOH         = $this->db->query($sqlWOH)->result();
foreach($resWOH as $rowWOH) :
    $WO_PAYTYPE = $rowWOH->WO_PAYTYPE;
endforeach;
if($WO_PAYTYPE == 2)
{
    $WO_PAYTYPED = "Lump Sum Fix Price";
}

if($WO_QUOT == '')
	$WO_QUOT = "......";
if($WO_NEGO == '')
	$WO_NEGO = "......";

$JOBDESC	= '';
$sqlJDESC	= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
$resJDESC	= $this->db->query($sqlJDESC)->result();
foreach($resJDESC as $rowJDESC) :
	$JOBDESC	= $rowJDESC->JOBDESC;								
endforeach;

$SPLDESC	= '';
$SPLADD1	= '';
$SPLTELP	= '';
$SPLOTHR	= '';
$SPLOTHR2	= '';
$sqlSPLD	= "SELECT SPLDESC, SPLADD1, SPLTELP, SPLOTHR, SPLOTHR2 FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
$resSPLD	= $this->db->query($sqlSPLD)->result();
foreach($resSPLD as $rowSPLD) :
	$SPLDESC	= $rowSPLD->SPLDESC;
	$SPLADD1	= $rowSPLD->SPLADD1;
	$SPLTELP	= $rowSPLD->SPLTELP;
	$SPLOTHR	= $rowSPLD->SPLOTHR;
	$SPLOTHR2	= $rowSPLD->SPLOTHR2;
endforeach;

if(isset($_POST['submitSYNC']))
{
	$PASAL_3	= $_POST['PASAL_3'];
	$PASAL_4	= $_POST['PASAL_4'];
	
	$sqlPWON	= "tbl_printdoc_wo WHERE PWO_NUM = '$WO_NUM'";
	$resPWON	= $this->db->count_all($sqlPWON);
	if($resPWON == 0)
	{
		$sqlINS	= "INSERT INTO tbl_printdoc_wo (PWO_NUM, PASAL_3, PASAL_4) VALUES ('$WO_NUM', '$PASAL_3', '$PASAL_4')";
		$this->db->query($sqlINS);
	}
	else
	{
		$sqlUPD		= "UPDATE tbl_printdoc_wo SET PASAL_3 = '$PASAL_3', PASAL_4 = '$PASAL_4'
						WHERE PWO_NUM = '$WO_NUM'";
		$this->db->query($sqlUPD);
	}
}

$PASAL_1	= '';
$PASAL_2	= '';
$PASAL_3	= '';
$PASAL_4	= '';
$PASAL_5	= '';
$PASAL_6	= '';
$PASAL_7	= '';
$PASAL_8	= '';
$PASAL_9	= '';
$PASAL_10	= '';
$PASAL_11	= '';
$PASAL_12	= '';
$PASAL_13	= '';
$PASAL_14	= '';
$PASAL_15	= '';
$PASAL_16	= '';
$PASAL_17	= '';
$PASAL_18	= '';
$sqlPWOD	= "SELECT * FROM tbl_printdoc_wo WHERE PWO_NUM = '$WO_NUM'";
$resPWOD	= $this->db->query($sqlPWOD)->result();
foreach($resPWOD as $rowPWOD) :
	$PASAL_1	= $rowPWOD->PASAL_1;
	$PASAL_2	= $rowPWOD->PASAL_2;
	$PASAL_3	= $rowPWOD->PASAL_3;
	$PASAL_4	= $rowPWOD->PASAL_4;
	$PASAL_5	= $rowPWOD->PASAL_5;
	$PASAL_6	= $rowPWOD->PASAL_6;
	$PASAL_7	= $rowPWOD->PASAL_7;
	$PASAL_8	= $rowPWOD->PASAL_8;
	$PASAL_9	= $rowPWOD->PASAL_9;
	$PASAL_10	= $rowPWOD->PASAL_10;
	$PASAL_11	= $rowPWOD->PASAL_11;
	$PASAL_12	= $rowPWOD->PASAL_12;
	$PASAL_13	= $rowPWOD->PASAL_13;
	$PASAL_14	= $rowPWOD->PASAL_14;
	$PASAL_15	= $rowPWOD->PASAL_15;
	$PASAL_16	= $rowPWOD->PASAL_16;
	$PASAL_17	= $rowPWOD->PASAL_17;
	$PASAL_18	= $rowPWOD->PASAL_18;
endforeach;
/*if($SPLOTHR2 == 1)
	$SPLOTHR2	= "Direktur Utama";
elseif($SPLOTHR2 == 2)
	$SPLOTHR2	= "Direktur Operasional";
elseif($SPLOTHR2 == 3)
	$SPLOTHR2	= "Direktur";
elseif($SPLOTHR2 == 4)
	$SPLOTHR2	= "Pemilik Alat";
elseif($SPLOTHR2 == 5)
	$SPLOTHR2	= "Pemilik";
else
	$SPLOTHR2	= "Pribadi";*/

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
$textFormat = new textFormat();

function tanggal_indo($tanggal)
{
	$bulan = array (1 =>	'Januari',
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

$tanggal	= $WO_DATE;
$tanggalV	= date('d-m-Y', strtotime($WO_DATE));
$day 		= date('D', strtotime($tanggal));
$days 		= date('d', strtotime($tanggal));
$years 		= date('Y', strtotime($tanggal));
$dayV 		= $dayList[$day];
$dateV		= $textFormat->terbilang($days);
$monthV		= tanggal_indo($tanggal);
$yearV		= $textFormat->terbilang($years);
$WOValueV	= $textFormat->terbilang($WO_VALUE);

$days1 		= date('d', strtotime($WO_DATE));
$monthV1	= tanggal_indo($WO_DATE);
$yearsV1	= date('Y', strtotime($WO_DATE));

if($WO_CATEG == 'SALT')
	$DIR2	= "Pemilik Alat";
elseif($WO_CATEG == 'MDR')
	$DIR2	= "Mandor";
else
	$DIR2	= "Direktur Utama";

$DIR1		= "Direktur Operasional";

$showStamp	= 0;
$urlStamp	= '';
if($WO_STAT == 3)
{
	$showStamp	= 0;
	$urlStamp 	= '';
}
elseif($WO_STAT == 5)
{
	$showStamp	= 1;
	$urlStamp 	= base_url().'assets/AdminLTE-2.0.5/dist/img/failedstamp01.png';
}
else
{
	$showStamp	= 1;
	$urlStamp 	= base_url().'assets/AdminLTE-2.0.5/dist/img/inprogress01.jpg';
}
$comp_name 	= $this->session->userdata['comp_name'];
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
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

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
			font: 10px; Arial, Helvetica, sans-serif;
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
<script>
	function checkInpt()
	{
		TTKP_DENIED1	= document.getElementById('TTKP_DENIED1').checked;
		TTKP_DENIED2	= document.getElementById('TTKP_DENIED2').checked;
		if(TTKP_DENIED1 == false && TTKP_DENIED2 == false)
		{
			alert('<?php echo $alert1; ?>');
			return false;
		}
		
		TTKP_DOCTYPE1	= document.getElementById('TTKP_DOCTYPE1').checked;
		TTKP_DOCTYPE2	= document.getElementById('TTKP_DOCTYPE2').checked;
		TTKP_DOCTYPE3	= document.getElementById('TTKP_DOCTYPE3').checked;
		TTKP_DOCTYPE4	= document.getElementById('TTKP_DOCTYPE4').checked;
		if(TTKP_DOCTYPE1 == false && TTKP_DOCTYPE2 == false && TTKP_DOCTYPE3 == false && TTKP_DOCTYPE4 == false)
		{
			alert('<?php echo $alert2; ?>');
			return false;
		}
	}
</script>
<form name="frmsync" id="frmsync" action="" method=POST onSubmit="return checkInpt()">
    <div class="page">
        <section class="content">
            <div class="col-xs-12" style="background-image:url(<?php if($showStamp == 1) { echo $urlStamp; } ?>); background-repeat:no-repeat;">
                <div style="border: 7px double #999; text-align:center; width: 100%; min-height:28cm;">
                    <br>
                    <div class="col-xs-12">
                        <div style="border: 7px groove #999; text-align:center; width: 100%; font-size:16px; font-family:'Times New Roman', Times, serif">&nbsp;<br><b>SURAT PERINTAH KERJA ( SPK )</b><br>&nbsp;</div>
                    </div>
                    <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <b>&nbsp;</b><br>
                    </div>
                    <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <b>SURAT PERINTAH KERJA</b><br>
                        <b>PEKERJAAN <?php echo $WO_MEMO; ?></b><br>
                        antara<br>
                        <b><?php echo strtoupper($comp_name); ?></b><br>
                        dengan<br>
                        <font color="#FF0000" style="text-transform:uppercase"><b><?php echo $SPLDESC; ?></b></font><br>
                        <b>Nomor : <?php echo $WO_CODE; ?></b>
                        <div style="border:1px ridge #000;"></div>
                    </div>
                    <div class="col-xs-12"><br>
                        <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <p>
                            Pada hari ini <?php echo "<font color='#FF0000'><b>$dayV, tanggal $dateV, bulan $monthV, tahun $yearV ($tanggalV)</font></b>, kami yang bertandatangan di bawah ini :"; ?>
                            </p>
                        </div>
                        <div class="col-xs-3" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <p>
                                <b>1. Dedy Sutjipto</b>
                            </p>
                        </div>
                        <div class="col-xs-9" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <p>
                            Selaku <b><?php echo $DIR1; ?></b>, yang dalam hal ini bertindak untuk dan atas nama <b><?php echo $comp_name; ?></b>, yang berkedudukan di Jl. Ratnaniaga no. 1 Komplek Kota Baru Parahyangan, RT00/RW00 Cipeundeuy, Padalarang, Kabupaten Bandung Barat. Tlp./Fax : 022-86800620, untuk selanjutnya disebut <b>Pihak Pertama</b>.
                            </p>
                        </div>
                        <div class="col-xs-3" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <p>
                            <b>2. <font color="#FF0000"><?php echo $SPLOTHR; ?></font></b>
                            </p>
                        </div>
                        <div class="col-xs-9" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <p>
                            Selaku <b><?php echo $SPLOTHR2; ?></b>, yang dalam hal ini bertindak untuk dan atas nama <b><font color="#FF0000"><?php echo $SPLDESC; ?></font></b>, yang berkedudukan di <font color="#FF0000"><?php echo $SPLADD1; ?>, Tlp. <?php echo $SPLTELP; ?></font>, untuk selanjutnya disebut <b>Pihak Kedua</b>.
                            </p>
                        </div>
                    
                        <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <b>Pihak Pertama</b> dan <b>Pihak Kedua</b> secara bersama-sama disebut <b>Para Pihak</b>.<br><br>
                            Dasar dari <b>Surat Perintah Kerja</b> ini adalah :<br><br>
                            <ol start="1">
                                <li>Surat Penawaran Harga No <?php echo $WO_QUOT; ?></li>
                                <li>Berita Acara Klarifikasi dan Negosiasi No <?php echo $WO_NEGO; ?></li>
                                <li>No.  Permintaan <?php echo $FPA_CODE; ?></li>
                            </ol>
                        </div>
                        
                        <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <br>
                            <b>PASAL 1</b>
                            <br>
                            <b>LINGKUP PEKERJAAN</b>
                        </div>
                        
                        <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <b>Lingkup Pekerjaan Pihak Pertama :</b><br><br>
                            <ol start="1">
                                <li>Melakukan <b>Pembayaran</b> kepada <b>Pihak Kedua</b> sesuai <b>Pekerjaan</b> yang sudah dilaksanakan yang dibuktikan dengan <b>Berita Acara Prestasi Pekerjaan</b> yang disetujui oleh <b>Para Pihak</b></li>
                                <li>Mengkoordinir <b>Pelaksanaan Pekerjaan</b> di lapangan.</li>
                                <li>Memberikan <b>Lokasi Pekerjaan</b> sesuai dengan <b>Lingkup Pekerjaan</b> yang telah disepakati <b>Para Pihak</b>.</</li>
                            </ol>
                        </div>
                        
                        <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                            <b>Lingkup Pekerjaan Pihak Kedua :</b><br><br>
                            <ol start="1">
                                <li>Harus mengetahui dan menguasai sepenuhnya semua Spesifikasi Teknis yang dipersyaratkan dalam <b>Pelaksanaan Pekerjaan</b>.</li>
                                <li>Melaksanakan Pekerjaan sesuai dengan <b>Lingkup dan Waktu Pekerjaan</b> yang dipersyaratkan oleh <b>Pihak Pertama</b>.</li>
                                <li>Selalu berkoordinasi dengan <b>Pihak Pertama</b> mengenai situasi Pekerjaan sehingga membebaskan <b>Pihak Pertama</b> dari segala resiko kerusakan dan tuntutan oleh <b>Pihak Lain</b>.</li>
                                <li>Bertanggung jawab terhadap seluruh karyawan atau buruh yang dipekerjakan oleh <b>Pihak Kedua</b> di dalam <b>Lokasi Pekerjaan Pihak Pertama</b>.</li>
                                <li>Wajib dan bertanggung jawab untuk mengadakan dan menyelenggarakan <b>Program Keselamatan dan Kesehatan Kerja serta Lingkungan (K3L)</b> sesuai dengan undang-undang yang berlaku.</li>
                    
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <div class="page">
        <section class="content">
            <div class="col-xs-12" style="background-image:url(<?php if($showStamp == 1) { echo $urlStamp; } ?>); background-repeat:no-repeat;">
                <div style="border: 7px double #999; text-align:center; width: 100%; min-height:28cm;">
                <br>
                
                <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                    <b><center>PASAL 2<br>HARGA PEKERJAAN</center></b><br>
                    <ol start="1">
                        <li><b>Harga Pekerjaan</b> dalam <b>Surat Perjanjian</b> ini adalah <b><font color="#FF0000">Rp <?php echo number_format($WO_VALUE, 2); ?>,- ( <?php echo $WOValueV; ?> rupiah )</font></b> dengan  perincian sebagai berikut :</li>
                    </ol>
                </div>
                
                <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif; padding-left:40px">
                    <table width="100%" border="1" cellpadding="0" cellspacing="0" rules="all">
                        <tr>
                          <th style="text-align:center" width="54">No</th>
                          <th style="text-align:center" width="465">Deskripsi Pekerjaan</th>
                          <th style="text-align:center" width="174">Volume</th>
                          <th style="text-align:center" width="96">Sat</th>
                          <th style="text-align:center" width="196">Harga Satuan</th>
                          <th style="text-align:center" width="208">Total</th>
                        </tr>
                        <?php
                            $JOBDESC	= '';
                            $WO_DESC	= '';
                            $WO_VOLM	= 0;
                            $ITM_UNIT	= '';
                            $ITM_PRICE	= 0;
                            $TAXPRICE1	= 0;
                            $TAXPRICE2	= 0;
                            $TAXTOTAL	= 0;
                            $WO_TOTAL	= 0;
                            $WO_SUBTOTAL= 0;
                            $therow		= 0;
                            $sqlJLD	= "SELECT B.JOBDESC, A.WO_DESC, A.WO_VOLM, A.ITM_UNIT, A.ITM_PRICE, A.TAXPRICE1, A.TAXPRICE2, A.WO_TOTAL
                                            FROM tbl_wo_detail A
                                                INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
                                            WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
                            $resJLD	= $this->db->query($sqlJLD)->result();
                            foreach($resJLD as $rowJLD) :
                                $therow		= $therow + 1;
                                $JOBDESC	= $rowJLD->JOBDESC;
                                $WO_DESC	= $rowJLD->WO_DESC;
                                if($WO_DESC == '')
                                    $WO_DESC	= $JOBDESC;
                                    
                                $WO_VOLM	= $rowJLD->WO_VOLM;
                                $ITM_PRICE	= $rowJLD->ITM_PRICE;
                                $ITM_UNIT	= $rowJLD->ITM_UNIT;
                                
                                $ITM_PRICEV = $ITM_PRICE;
                                $WO_VOLMV	= $WO_VOLM;
                                if($ITM_UNIT == 'LS')
                                {
                                    $ITM_PRICEV = $WO_VOLM;
                                    $WO_VOLMV	= $ITM_PRICE;
                                }
                                
                                $TAXPRICE1	= $rowJLD->TAXPRICE1;
                                $TAXPRICE2	= $rowJLD->TAXPRICE2;
                                $WO_TOTAL	= $rowJLD->WO_TOTAL;
                                $TAXTOTAL	= $TAXTOTAL + $TAXPRICE1 - $TAXPRICE2;
                                $WO_SUBTOTAL= $WO_SUBTOTAL + $WO_TOTAL;
                                ?>
                                <tr>
                                    <td style="text-align:center"><?php echo $therow; ?>.</td>
                                    <td><font color="#FF0000"><?php echo $WO_DESC; ?></font></td>
                                    <td style="text-align:right"><?php echo number_format($WO_VOLMV, 2); ?></td>
                                    <td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                    <td style="text-align:right">Rp. <?php echo number_format($ITM_PRICEV, 2); ?></td>
                                    <td style="text-align:right">Rp. <?php echo number_format($WO_TOTAL, 2); ?></td>
                                </tr>
                                <?php
                            endforeach;
                            //$WO_PPN		= 0.1 * $WO_SUBTOTAL;
                            $WO_PPN		= $TAXTOTAL;
                            $WO_GTOTAL	= $WO_PPN + $WO_SUBTOTAL;
                        ?>
                        <tr>
                            <td colspan="5" style="text-align:left">
                                Sub Total<br>
                                PPN 10%<br>
                                <b>TOTAL</b>
                            </td>
                            <td style="vertical-align:top; text-align:right; font-size:13px"><b>Rp. <?php echo number_format($WO_SUBTOTAL, 2); ?></b><br>Rp. <?php echo number_format($WO_PPN, 2); ?><br><b>Rp. <?php echo number_format($WO_GTOTAL, 2); ?></b></td>
                        </tr>
                    </table>
                    <br>
                </div>
                <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                    <ol start="2">
                        <li>Dengan kondisi harga:</li>
                        <table border="0" width="100%">
                            <tr style="vertical-align:top">
                                <td>2.1</td>
                                <td>Harga sudah termasuk fee, keuntungan, upah dan segala resiko yang timbul saat pelaksanaan pekerjaan.</td>
                            </tr>
                            <tr style="vertical-align:top">
                              <td>2.2</td>
                              <td>Sifat dari harga adalah <b><?php echo $WO_PAYTYPED; ?></b> berlaku hingga <b>Surat Perintah Kerja</b> ini selesai.</td>
                            </tr>
                            <tr style="vertical-align:top">
                              <td>2.3</td>
                              <td>Harga dari <b>Surat Perintah Kerja</b> ini sudah termasuk <b>Pajak Penghasilan (PPh)</b> yang besarnya sesuai dengan Peraturan Perpajakan yang berlaku saat ini.</td>
                            </tr>
                            <tr style="vertical-align:top">
                              <td>2.4</td>
                              <td>Sudah termasuk biaya perawatan.</td>
                            </tr>
                        </table>
                    </ol>
                    </li>
                </div>
                <?php
                    $TOOLSNAME	= '';
                    $JOB_VOLM	= 0;
                    $JOB_VOLMH	= 0;
                    $sqlJLD2	= "SELECT B.JOBDESC, A.WO_VOLM, A.ITM_UNIT, A.ITM_PRICE, A.WO_TOTAL
                                    FROM tbl_wo_detail A
                                        INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
                                    WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE' AND B.GROUP_CATEG = 'T'";
                    $resJLD2	= $this->db->query($sqlJLD2)->result();
                    foreach($resJLD2 as $rowJLD2) :
                        $TOOLSNAME	= $rowJLD2->JOBDESC;
                        $JOB_VOLM	= $rowJLD2->WO_VOLM;
                        $JOB_VOLMH	= 0.5 * $JOB_VOLM;
                    endforeach;
                ?>
                
                <?php
                    $PT_DESC01 	= '';
                    $PT_DESC02 	= '';
                    $PT_DESC03 	= '';
                    $PT_DESC04 	= '';
                    $PT_DESC05 	= '';
                    $OTH_DESC01 = '';
                    $OTH_DESC02 = '';
                    $OTH_DESC03 = '';
                    $OTH_DESC04 = '';
                    $OTH_DESC05 = '';
                    $sqlPTO	= "SELECT * FROM tbl_payterm WHERE PTRM_TYPE = 'SUB'";
                    $resPTO	= $this->db->query($sqlPTO)->result();
                    foreach($resPTO as $rowPTO) :
                        $PT_DESC01 		= $rowPTO->PT_DESC01;
                        $PT_DESC02 		= $rowPTO->PT_DESC02;
                        $PT_DESC03 		= $rowPTO->PT_DESC03;
                        $PT_DESC04 		= $rowPTO->PT_DESC04;
                        $PT_DESC05 		= $rowPTO->PT_DESC05;
                        $PTRM_DESC01 	= $rowPTO->PTRM_DESC01;
                        $PTRM_DESC02 	= $rowPTO->PTRM_DESC02;
                        $PTRM_DESC03 	= $rowPTO->PTRM_DESC03;
                        $PTRM_DESC04 	= $rowPTO->PTRM_DESC04;
                        $PTRM_DESC05 	= $rowPTO->PTRM_DESC05;
                        $OTH_DESC01 	= $rowPTO->OTH_DESC01;
                        $OTH_DESC02 	= $rowPTO->OTH_DESC02;
                        $OTH_DESC03 	= $rowPTO->OTH_DESC03;
                        $OTH_DESC04 	= $rowPTO->OTH_DESC04;
                        $OTH_DESC05 	= $rowPTO->OTH_DESC05;
                    endforeach;
                ?>
                <br>
                <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                    <b><center>PASAL 3<br>JANGKA WAKTU PELAKSANAAN</center></b>
                    <div id="showPASAL_3TA" style="display:none"><textarea name="PASAL_3" id="compose-textarea1" class="form-control" style="height:100px"><?php echo $PASAL_3; ?></textarea></div>
                    <div id="showPASAL_3"><?php echo $PASAL_3; ?></div>
                <br>
                </div>
                <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                    <b><center>PASAL 4<br>CARA PEMBAYARAN</center></b>
                    <div id="showPASAL_4TA" style="display:none"><textarea name="PASAL_4" id="compose-textarea2" class="form-control" style="height:100px"><?php echo $PASAL_4; ?></textarea></div>
                    <div id="showPASAL_4"><?php echo $PASAL_4; ?></div>
                </div>
            </div>
        </section>
    </div>
    <div class="page">
        <section class="content">
            <div class="col-xs-12" style="background-image:url(<?php if($showStamp == 1) { echo $urlStamp; } ?>); background-repeat:no-repeat;">
                <div style="border: 7px double #999; text-align:center; width: 100%; min-height:28cm;">
                    <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <b><br><center>PASAL 5<br>KESEPAKATAN LAIN-LAIN</center></b><br>
                        <ol type="1">
                            <?php /*?><?php if($OTH_DESC01 != '') { ?>
                                <li><?php echo $OTH_DESC01; } ?></li>
                            <?php if($OTH_DESC02 != '') { ?>
                                <li><?php echo $OTH_DESC02; } ?></li>
                            <?php if($OTH_DESC03 != '') { ?>
                                <li><?php echo $OTH_DESC03; } ?></li>
                            <?php if($OTH_DESC04 != '') { ?>
                                <li><?php echo $OTH_DESC04; } ?></li>
                            <?php if($OTH_DESC05 != '') { ?>
                                <li><?php echo $OTH_DESC05; } ?></li><?php */?>
                            <li><b>Keadaan Memaksa (Force Majeure)</b> ialah suatu keadaan atau hal-hal yang terjadi diluar kekuasaan dan tidak dapat ditanggulangi oleh <b>Pihak Kedua</b> dan/atau <b>Pihak Pertama</b> ataupun oleh <b>Pihak Lain</b> yang profesional seperti: Banjir, gempa bumi, gunung meletus, angin topan, tanah longsor, kebakaran atau bencana alam lainnya, peperangan, huru-hara umum, wabah, penyakit menular lainnya yang bersifat memaksa dan mempunyai akibat langsung terhadap jangka waktu penyelesaian Pekerjaan</li>
                            <li>Apabila <b>Keadaan Memaksa (Force Majeure)</b> tersebut dalam ayat 1 Pasal ini terjadi, maka <b>Pihak Kedua</b> harus memberitahukan secara tertulis kepada <b>Pihak Pertama</b> tidak lebih dari 3x24 jam terhitung sejak terjadinya <b>Keadaan Memaksa (Force Majeure)</b> tersebut dengan disertai bukti-bukti yang sah sebagai pendukung.</li>
                            <li>Sehubungan dengan pelaksanaan pengakhiran <b>Surat Perintah Kerja</b> ini, <b>Para Pihak</b> sepakat dan setuju untuk mengabaikan atau mengesampingkan atau tidak mematuhi / menggunakan ketentuan yang diatur dalam Pasal 1266 dan Pasal 1267 Kitab Undang-Undang Hukum Perdata.</li>
                            <li>Apabila timbul sengketa atau perselisihan antara <b>Pihak Pertama</b> dengan <b>Pihak Kedua</b> sehubungan dengan pelaksanaan <b>Surat Perintah Kerja</b> ini, maka Para Pihak setuju untuk menyelesaikannya secara musyawarah dan hasil yang dicapai dari musyawarah tersebut akan dinyatakan dalam suatu pernyataan tertulis yang secara hukum bersifat mengikat dan harus disetujui oleh Para Pihak.</li>
                            <li>Apabila musyawarah sebagaimana tersebut dalam ayat 4 Pasal ini tidak tercapai penyelesaian, maka <b>Para Pihak</b> sepakat menyerahkan perselisihan tersebut kepada suatu <b>Badan Arbitrase</b> yaitu <b>Badan Arbitrase Nasional Indonesia (BANI)</b> yang berkedudukan di Bandung untuk diselesaikan.</li>
                        </ol>
                    </div>
                    <br>            
                    <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <b><center>PASAL 6<br>PENUTUP</center></b><br>
                        <ol type="1">
                            <li><b>Para Pihak</b> sepakat dan sependapat serta mengikuti <b>Surat Perintah Kerja</b> ini dan mengikat secara hukum.</li>
                            <li><b>Surat Perintah Kerja</b> ini berlaku sejak ditandatangani <b>Para Pihak</b> sampai dengan seluruh tanggungjawab dan kewajiban <b>Para Pihak</b> terpenuhi.</li>
                            <li><b>Surat Perintah Kerja</b> ini merupakan satu kesatuan yang tidak dapat dipisahkan dengan lampiran-lampirannya dan mempunyai <b>Kekuatan Hukum</b> yang sama.</li>
                        </ol>
                    </div>
                    <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <br><br>
                    </div>
                    
                    <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <br>
                        <b>Padalarang, <font color="#FF0000"><?php echo "$days1 $monthV1 $yearsV1"; ?></font></b><br><br>
                    </div>
                    
                    <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <b>PIHAK PERTAMA</b><br>
                        <b><?php echo strtoupper($comp_name); ?></b>
                        <br><br><br><br><br><br><br>
                        <b><u>DEDY SUTJIPTO</u></b><br>
                        <i><?php echo $DIR1; ?></i>
                    </div>
                    
                    <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <b>PIHAK KEDUA</b><br>
                        <b><font color="#FF0000">
                            <?php if($SPLOTHR2 != 'Pribadi') echo $SPLDESC; ?>
                        </font></b>
                        <br><br><br><br><br><br><br>
                        <font color="#FF0000"><u><?php echo $SPLOTHR; ?></u></font><br>
                        <font color="#FF0000"><i>
                            <?php if($SPLOTHR2 != 'Pribadi') echo $SPLOTHR2; ?>
                        </i></font>
                    </div>
                    <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                        <br>
                        <br>
                        <div id="Layer1">
                            <button class="btn btn-primary" name="submitSYNC">
                                <i class="fa fa-save"></i>&nbsp;&nbsp;Update
                            </button>
                            <button class="btn btn-warning" type="button" onClick="getEDIT()">
                                <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit
                            </button>
                            <button class="btn btn-success" type="button" onClick="getPRINT(); Layer1.style.visibility='hidden'; self.print(); self.close();" >
                                <i class="fa fa-print"></i>&nbsp;&nbsp;Print
                            </button>
                        </div>
                        <script>
							function getEDIT()
							{
								document.getElementById("showPASAL_3TA").style.display 	= '';
								document.getElementById("showPASAL_3").style.display 	= 'none';
								document.getElementById("showPASAL_4TA").style.display 	= '';
								document.getElementById("showPASAL_4").style.display 	= 'none';
							}
							
							function getPRINT()
							{
								document.getElementById("showPASAL_3TA").style.display 	= 'none';
								document.getElementById("showPASAL_3").style.display 	= '';
								document.getElementById("showPASAL_4TA").style.display 	= 'none';
								document.getElementById("showPASAL_4").style.display 	= '';
							}
						</script>
                    </div>
           		</div>
           	</div>
        </section>
    </div>
</form>
</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'; ?>"></script>

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
	//Add text editor
	$("#compose-textarea1").wysihtml5();
	$("#compose-textarea2").wysihtml5();
	$("#compose-textarea3").wysihtml5();
	});
	
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