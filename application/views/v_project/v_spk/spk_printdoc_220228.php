<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Maret 2017
 * File Name	= blank.php
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
$PRJNAME 	= $default['PRJNAME'];

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
$sqlSPLD	= "SELECT SPLDESC, SPLADD1, SPLTELP, SPLOTHR FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
$resSPLD	= $this->db->query($sqlSPLD)->result();
foreach($resSPLD as $rowSPLD) :
	$SPLDESC	= $rowSPLD->SPLDESC;
	$SPLADD1	= $rowSPLD->SPLADD1;
	$SPLTELP	= $rowSPLD->SPLTELP;
	$SPLOTHR	= $rowSPLD->SPLOTHR;							
endforeach;


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
<section class="content">
    <div class="box box-primary" style="display:none">
		<div class="box-header with-border">
            <h3 class="box-title">Dear <?php echo $username; ?>,</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            The page you requested is under construction, please contact your Administrator.
        </div><!-- /.box-body -->
        <div class="box-footer">
            <br><br>
            Developed by.<br>
            Developer Team of IT Department<br />
            PT Nusa Konstruksi Enjiniring, Tbk.
        </div><!-- /.box-footer-->
    </div>
  	<div class="col-xs-12">
    	<div style="border: 7px double #999; text-align:center; width: 100%; height:950px">
    		<br>
            <div class="col-xs-12">
                <div style="border: 7px groove #999; text-align:center; width: 100%; font-size:16px; font-family:'Times New Roman', Times, serif">&nbsp;<br><b>SURAT PERINTAH KERJA ( SPK )</b><br>&nbsp;</div>
            </div>
            <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <br><b>SURAT PERINTAH KERJA</b><br>
                <b>PEKERJAAN <font color="#FF0000" style="text-transform:uppercase"><?php echo $JOBDESC; ?></font></b>
            </div>
            <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                antara<br>
                <b>PT. MEGA SUKMA</b><br>
                dengan<br>
                <font color="#FF0000" style="text-transform:uppercase"><b><?php echo $SPLDESC; ?></b></font><br>
                <b>Nomor : <?php echo $WO_CODE; ?></b>
                <div style="border:1px ridge #000;"></div>
            </div>
    		<div class="col-xs-12"><br></div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                Pada hari ini <b><font color="#FF0000">Senin, tanggal dua belas , bulan Maret , tahun Dua Ribu Delapan Belas (12-03-2018)</font></b>, kami yang bertandatangan di bawah ini :
                </p>
            </div>
            <div class="col-xs-3" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                    <b>1. Dedy Sutjipto</b>
                </p>
            </div>
            <div class="col-xs-9" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                Selaku <b>Direktur Operasional</b>, yang dalam hal ini bertindak untuk dan atas nama <b>PT. Mega Sukma</b>, yang berkedudukan di Jl. Ratnaniaga no. 1 Komplek Kota Baru Parahyangan, RT00/RW00 Cipeundeuy, Padalarang, Kabupaten Bandung Barat. Tlp./Fax : 022-86800620, untuk selanjutnya disebut <b>Pihak Pertama</b>.
                </p>
            </div>
            <div class="col-xs-3" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                <b>2. <font color="#FF0000"><?php echo $SPLOTHR; ?></font></b>
                </p>
            </div>
            <div class="col-xs-9" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                Selaku <b>Direktur Utama</b>, yang dalam hal ini bertindak untuk dan atas nama <b><font color="#FF0000"><?php echo $SPLDESC; ?></font></b>, yang berkedudukan di <font color="#FF0000"><?php echo $SPLADD1; ?>, Tlp. <?php echo $SPLTELP; ?></font>, untuk selanjutnya disebut <b>Pihak Kedua</b>.
                </p>
            </div>
    
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>Pihak Pertama</b> dan <b>Pihak Kedua</b> secara bersama-sama disebut <b>Para Pihak</b>.<br><br>
                Dasar dari <b>Surat Perintah Kerja</b> ini adalah :<br><br>
                <ol start="1">
                    <li>Surat Penawaran Harga No........</li>
                    <li>Berita Acara Klarifikasi dan Negosiasi No...........</li>
                </ol>
            </div>
    
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>PASAL 1</b>
                <br>
                <b>LINGKUP PEKERJAAN</b>
            </div>
    
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>Lingkup Pekerjaan Pihak Pertama :<br><br>
                <ol start="1">
                    <li>Melakukan pembayaran sesuai pekerjaan yang sudah dilaksanakan</li>
                    <li>Mengkoordinir pelaksanaan pekerjaan di lapangan</li>
                    <li>Memberikan lokasi pekerjaan sesuai dengan lingkup pekerjaan</li>
                </ol>
                </b>
            </div>
    
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>Lingkup Pekerjaan Pihak Kedua :<br><br>
                <ol start="1">
                    <li>&nbsp;</li>
                    <li>&nbsp;</li>
                    <li>&nbsp;</li>
                </ol>
                </b>
            </div>
  		</div>
	</div>
</section>

<section class="content">
    
  <div class="col-xs-12">
    	<div style="border: 7px double #999; text-align:center; width: 100%; height:950px">
    <br>
    
    <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
    	<b><center>PASAL 2<br>HARGA PEKERJAAN</center></b>
        <ol start="1">
        	<li><b>Harga Pekerjaan</b> dalam <b>Surat Perjanjian</b> ini adalah <b><font color="#FF0000">Rp 1.818.824.155.50,- ( Satu milyar delapan ratus delapan belas juta delapan ratus dua puluh empat ribu seratus lima puluh lima koma lima puluh rupiah )</font></b> dengan  perincian sebagai berikut :</li>
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
            <tr>
            	<td style="text-align:center">1</td>
                <td><font color="#FF0000">Pekerjaan Bored Pile &Oslash; 650 mm ( Bentonite ) lokasi secant pile (Primary)</font></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td style="text-align:center">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td style="text-align:center">2</td>
                <td>
                	<font color="#FF0000">
                		<ol type="a">
                        	<li>Pekerjaan Bored Pile ø 650 mm (secondary) lokasi secant pile</li>
                            <li>Upah Cor</li>
                            <li>Perakitan dan penurunan besi</li>
                        </ol>
                    </font>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td style="text-align:center">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td colspan="5" style="text-align:left">
                	Sub Total<br>
                    PPN 10%<br>
                    <b>TOTAL</b>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
    
    <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
    	<br><b><center>PASAL 32<br>JANGKA WAKTU PELAKSANAAN</center></b><br>
        <ol type="1">
        	<li>Terhitung mulai tanggal <b>Surat Perintah Kerja</b> ini ditandatangani oleh <b>Para Pihak</b>, hingga <b>hari…………, tanggal………, bulan…………, tahun…………… (………/………/………)</b> yang dibuktikan dengan <b>Berita Acara Serah Terima Pertama</b>.</li>
            <li>Jangka waktu <b>Masa Pemeliharaan</b> dalam surat perjanjian ini adalah selama ………………..hari kalender terhitung sejak tanggal disetujuinya <b>Berita Acara Serah Terima Pertama</b> oleh <b>Para Pihak</b> termasuk oleh <b>Pemilik Proyek</b>.</li>
        </ol>
    </div>
    
    <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
    	<b><center>CARA PEMBAYARAN</center></b><br>
        <ol type="1">
        	<li>Pembayaran <b>Uang Muka</b> sebesar 10% (sepuluh persen) dari <b>Harga Pekerjaan</b>, yang akan dibayarkan setelah ………… hari kalender.</li>
            <li>Selanjutnya pembayaran berikutnya berdasarkan Progress Bulanan dengan minimal <b>Progress Pekerjaan</b> sebesar 10% (sepuluh persen) yang akan dibayarkan …….hari kalender dan dipotong Uang Muka &amp; Retensi.</li>
            <li>Retensi sebesar 5%, dari nilai kontrak yang akan dibayarkan ……………hari kalender.</li>
        </ol>
    </div>
    
    <div class="col-xs-12" align="justify" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
    	<b>Surat Perintah Kerja</b> ini diikat dengan <b>Surat Perjanjian Kontrak Kerja ( KK )</b> No……………………………..dengan Pasal – pasal didalamnya yang saling mengikat.<br>
    </div>
    
    <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
    	<br><b>Padalarang, <font color="#FF0000">12 Maret 2018</font></b>
    </div>
    
    <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
    	<b>PIHAK PERTAMA</b><br>
        <b>PT. MEGA SUKMA</b>
        <br><br><br><br>
        <b><u>DEDY SUTJIPTO</u></b><br>
        <i>Direktur Operasional</i>
    </div>
    
    <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
    	<b>PIHAK KEDUA</b><br>
        <b><font color="#FF0000">PT. SOLEFOUND SAKTI</font></b>
        <br><br><br><br>
        <font color="#FF0000"><u>TJAHYA HUSADA</u></font><br>
        <font color="#FF0000"><i>Direktur Utama</i></font>
    </div>
    
   </div>
   </div>
</section>

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