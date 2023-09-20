<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 06 November 2018
 * File Name	= spk_printdoc_mdr.php
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
$WO_REFNO 	= $default['WO_REFNO'];
$WO_MEMO 	= $default['WO_MEMO'];
$FPA_CODE 	= $default['FPA_CODE'];
$PRJNAME 	= $default['PRJNAME'];
$WO_QUOT 	= $default['WO_QUOT'];
$WO_NEGO 	= $default['WO_NEGO'];

$PRJLOCT	= ' ... ';
$PROJ_MNG	= '-';
$sqlPRJMNG	= "SELECT A.PRJLOCT, B.First_Name, B.Last_Name 
				FROM tbl_project A 
					INNER JOIN tbl_employee B ON A.PRJ_MNG = B.Emp_ID
				WHERE A.PRJCODE = '$PRJCODE'";
$resPRJMNG	= $this->db->query($sqlPRJMNG)->result();
foreach($resPRJMNG as $rowPRJMNG) :
	$PRJLOCT	= $rowPRJMNG->PRJLOCT;
	$First_Name	= $rowPRJMNG->First_Name;
	$Last_Name	= $rowPRJMNG->Last_Name;
	if($Last_Name == '')
		$PROJ_MNG	= "$First_Name";
	else
		$PROJ_MNG	= "$First_Name $Last_Name";
endforeach;
if($PROJ_MNG == '')
	$PROJ_MNG	= "-";

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
	$DIR2	= "Mandor Borong";
else
	$DIR2	= "Direktur Utama";

$DIR1		= "Kepala Proyek";

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
                <br>
                <b><?php echo $WO_CODE; ?></b><br>
                <b><?php echo $WO_DATE; ?></b><br>
                <b><font color="#FF0000" style="text-transform:uppercase"><?php echo $WO_NOTE; ?></font></b>
            </div>
    		<div class="col-xs-12"><br></div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                Pada hari ini <?php echo "<font color='#FF0000'><b>$dayV, tanggal $dateV, bulan $monthV, tahun $yearV ($tanggal)</font></b>, kami yang bertandatangan di bawah ini :"; ?>
                </p>
            </div>
            <div class="col-xs-3" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                    <b>1. <?php echo $PROJ_MNG; ?></b>
                </p>
            </div>
            <?php
				if($PRJLOCT == '')
					$PRJLOCT	= " ...... ";
				if($WO_REFNO == '')
					$WO_REFNO	= " ...... ";
			?>
            <div class="col-xs-9" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                Selaku <b>Kepala Proyek</b>, sesuai dengan Surat Keputusan No. <?php echo $WO_REFNO; ?> yang dalam hal ini bertindak untuk dan atas nama <b><?php echo $comp_name; ?></b>, pada <b><?php echo $PRJNAME; ?></b> yang berkedudukan di <?php echo $PRJLOCT; ?>, untuk selanjutnya disebut <b>Pihak Pertama</b>.
                </p>
            </div>
            <div class="col-xs-3" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                <b>2. <font color="#FF0000"><?php echo $SPLOTHR; ?></font></b>
                </p>
            </div>
            <div class="col-xs-9" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <p>
                Selaku <b>Mandor Borongan</b>, yang dalam hal ini bertindak untuk dan atas nama <b><font color="#FF0000">Pribadi</font></b>, yang berkedudukan di <font color="#FF0000"><?php echo $SPLADD1; ?>, Tlp. <?php echo $SPLTELP; ?></font>, untuk selanjutnya disebut <b>Pihak Kedua</b>.
                </p>
            </div>
    
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>Pihak Pertama</b> dan <b>Pihak Kedua</b> secara bersama-sama disebut <b>Para Pihak</b>.<br><br>
                Dasar dari <b>Surat Perintah Kerja</b> ini adalah :<br><br>
                <ol start="1">
                    <li>Surat Penawaran Harga No <?php echo $WO_QUOT; ?></li>
                    <li>Berita Acara Klarifikasi dan Negosiasi No <?php echo $WO_NEGO; ?></li>
                </ol>
                Maka dengan ini <b>Pihak Pertama</b>, memberikan <b>Perintah Kerja</b> kepada <b>Pihak Kedua</b> dan <b>Pihak Kedua</b> setuju serta sepakat dengan <b>Surat Perjanjian Kerja</b> ini untuk <?php echo $JOBDESC; ?>,  <?php echo $PRJNAME; ?>.
            </div>
    
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>PASAL 1</b>
                <br>
                <b>Lingkup Pekerjaan</b>
            </div>
    
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>Lingkup Pekerjaan Pihak Pertama :<br><br>
                <ol>
                    <li>Penyediaan tenaga kerja untuk pelaksanaan pekerjaan.</li>
                    <li>Penyediaan alat-alat kerja (small tools) yang diperlukan pada saat pelaksanaan pekerjaan.</li>
                    <li>Lingkup pekerjaan dan kondisi-kondisi pekerjaan dalam berita acara klarifikasi dan negosiasi, surat menyurat dan risalah rapat saling melengkapi satu dengan lainnya.</li>
                </ol>
            </div>
    
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>PASAL 2</b>
                <br>
                <b>Kewajiban dan Tanggung Jawab</b>
            </div>
    
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>Pihak Kedua wajib dan dianggap telah memahami ketentuan-ketentuan/persyaratan yang ditentukan, seperti :<br><br>
                <ol>
                    <li>Pihak Kedua wajib dan bertanggung jawab atas hasil kerja sesuai target waktu yang ditetapkan.</li>
                    <li>Bertanggung jawab sepenuhnya terhadap kesempurnaan hasil pekerjaan secara keseluruhan sampai dengan diterima oleh Pihak Pertama yang dibuktikan dengan Berita Acara Serah Terima Pertama.</li>
                    <li>Bertanggung jawab atas segala sesuatu yang berhubungan dengan karyawan/buruhnya, kerusakan pekerjaan lain yang diakibatkan oleh kelalaian Pihak Kedua, dengan segala biaya yang timbul menjadi tanggung jawab Pihak Kedua.</li>
                </ol>
            </div>  		</div>
	</div>
</section>

<section class="content">
	<div class="col-xs-12">
    	<div style="border: 7px double #999; text-align:center; width: 100%; height:950px">
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>PASAL 3</b>
                <br>
                <b>Kewajiban dan Tanggung Jawab</b>
            </div>
          	<div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <p><b>Harga Pekerjaan</b> dalam <b>Surat Perjanjian</b> ini adalah <b><font color="#FF0000">Rp <?php echo number_format($WO_VALUE, 2); ?>,- ( <?php echo $WOValueV; ?> rupiah )</font></b> dengan  perincian sebagai berikut :
                </p>
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
						$WO_GTOTAL	= 0;
                        $therow		= 0;
                        $sqlJLD	= "SELECT B.JOBDESC, A.WO_DESC, A.WO_VOLM, A.ITM_UNIT, A.ITM_PRICE, A.TAXPRICE1, 
										A.TAXPRICE2, A.WO_TOTAL
									FROM tbl_wo_detail A
										INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
											AND A.ITM_CODE = B.ITM_CODE
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
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><b>PASAL 4</b>
                <br>
                <b>Kondisi Harga</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol>
                    <li>Harga sudah termasuk fee, keuntungan, upah dan segala jenis resiko yang timbul pada saat pelaksanaan.</li>
                    <li>Kondisi lapangan harus sudah diperhitungkan pada saat penerimaan Surat Perintah Kerja ini.</li>
                    <li>Harga pekerjaan ini bersifat fix unit price (harga satuan tetap) berlaku sampai dengan pekerjaan diterima Pihak Pertama, yang dibuktikan dengan Berita Acara Serah Terima Pertama beserta lampirannya.</li>
                    <li>Harga satuan tersebut sudah termasuk Pajak Pertambahan Nilai(Pph) sesuai peraturan Perpajakan yang berlaku saat ini.</li>
                </ol>
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
                $sqlPTO	= "SELECT * FROM tbl_payterm";
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
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><b>PASAL 5</b>
                <br>
                <b>Jangka Waktu Pelaksanaan</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><ol type="1">
					<?php if($PTRM_DESC01 != '') { ?>
                        <li><?php echo $PTRM_DESC01; } ?></li>
                    <?php if($PTRM_DESC02 != '') { ?>
                        <li><?php echo $PTRM_DESC02; } ?></li>
                    <?php if($PTRM_DESC03 != '') { ?>
                        <li><?php echo $PTRM_DESC03; } ?></li>
                    <?php if($PTRM_DESC04 != '') { ?>
                        <li><?php echo $PTRM_DESC04; } ?></li>
                    <?php if($PTRM_DESC05 != '') { ?>
                        <li><?php echo $PTRM_DESC05; } ?></li>
                </ol>
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><b>PASAL 6</b>
                <br>
                <b>Cara Pembayaran</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol type="1">
					<?php if($PT_DESC01 != '') { ?>
                        <li><?php echo $PT_DESC01; } ?></li>
                    <?php if($PT_DESC02 != '') { ?>
                        <li><?php echo $PT_DESC02; } ?></li>
                    <?php if($PT_DESC03 != '') { ?>
                        <li><?php echo $PT_DESC03; } ?></li>
                    <?php if($PT_DESC04 != '') { ?>
                        <li><?php echo $PT_DESC04; } ?></li>
                    <?php if($PT_DESC05 != '') { ?>
                        <li><?php echo $PT_DESC05; } ?></li>
                </ol>
            </div>
  		</div>
	</div>
</section>

<section class="content">
	<div class="col-xs-12">
    	<div style="border: 7px double #999; text-align:center; width: 100%; height:950px">
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>PASAL 7</b>
                <br>
                <b>Asuransi</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol>
                    <li>Pihak Pertama harus mengasuransikan semua peralatan termasuk alat-alat yang digunakan untuk pelaksanaan pekerjaan.</li>
                    <li>Pihak Pertama harus mengasuransikan tenaga kerja Pihak Kedua dengan Program Jaminan Kecelakaan Kerja dan Program Jaminan Kematian dari PT. Jaminan Sosial Tenaga Kerja (Jamsostek).</li>
                    <li>Pihak Kedua paling lambat 1 x 24 jam harus memberitahukannya kepada Pihak Pertama apabila terjadi kecelakaan atau peristiwa yang dapat mengakibatkan klaim berdasarkan asuransi.</li>
                    <li>Klaim kecelakaan dapat direalisasikan dan atau diganti apabila disaat terjadi kecelakaan kerja, pekerja menggunakan Alat Pengaman Diri (APD).</li>
                </ol>
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><br><b>PASAL 8</b>
                <br>
                <b>Masa Pemeliharaan Pekerjaan</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol>
                    <li>Pada intinya jangka waktu Masa Pemeliharaan Pekerjaan dalam Surat Perintah Kerja ini adalah tidak ada,namun sebagaimana dimaksudkan dalam Surat Perintah Kerja ini, Pihak Kedua tetap bertanggung jawab secara moral kepada Pihak Pertama akan hasil kerjanya.</li>
                </ol>
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><br><b>PASAL 9</b>
                <br>
                <b>Berita Acara Serah Terima Kedua</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol>
                    <li>Karena Masa Pemeliharaan Pekrjaan tidak ada, maka Serah Terima Kedua pun tidak ada.</li>
                </ol>
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><br><b>PASAL 10</b>
                <br>
                <b>Pemutusan Perjanjiana</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>Pihak Pertama berhak membatalkan Surat Perintah Kerja ini secara sepihak dengan mengesampingkan ketentuan dalam Pasal 1266 dan Pasal 1267 Kitab Undang-undang Hukum Perdata Indonesia dengan memberitahukan secara tertulis 7 (hari) hari sebelumnya setelah melakukan peringatan tertulis kepada Pihak Kedua, apabila :<br><br>
                <ol>
                    <li>Dalam waktu yang sudah disepakati bersama tidak melakukan mobilisasi tenaga kerja beserta peralatan.</li>
                    <li>Tenaga kerja tidak dapat bekerja lebih dari 4 (empat) hari kalender.</li>
                    <li>Dinilai kurang mampu dan cakap dalam melaksanakan pekerjaan, yang dibuktikan dengan telah mendapat teguran secara tertulis sebanyak 3 (tiga) kali.</li>
                </ol>
            </div>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><br><b>PASAL 11</b>
                <br>
                <b>Pengalihan Pekerjaan</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>Pihak Kedua tidak dapat mengalihkan sebagian atau seluruh pekerjaan yang telah disetujui dan ditetapkan oleh Pihak Pertama kepada Pihak Lain tanpa ijin tertulis dari Pihak Pertama, namun apabila ijin dimaksud diberikan, maka Pihak Kedua tidak akan terbebas dari semua tanggung jawab dan kewajibannya berdasarkan Surat Perintah Kerja ini.<br><br>
            </div>
  		</div>
	</div>
</section>

<section class="content">
	<div class="col-xs-12">
    	<div style="border: 7px double #999; text-align:center; width: 100%; height:950px">
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>PASAL 12</b>
                <br>
                <b>Keadaan Memaksa (Force Majeure)</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>Keadaan Memaksa/Force Majeure ialah suatu keadaan atau hal-hal yang terjadi di luar kekuasaan dan tidak dapat ditanggulangi oleh Pihak Kedua dan atau Pihak Pertama ataupun Pihak Lain yang professional, seperti:<br><br>
                <ol>
                    <li>Banjir, gempa bumi, gunung meletus, angin topan, tanah longsor, kebakaran atau bencana alam lainnya.</li>
                    <li>Peperangan, pemberontakan, huru-hara umum, bom meledak.</li>
                    <li>Wabah, epidemic, penyakit menular.</li>
                    <li>Klaim kecelakaan dapat direalisasikan dan atau diganti apabila disaat terjadi kecelakaan kerja, pekerja menggunakan Alat Pengaman Diri (APD).</li>
                </ol>
                yang bersifat memaksa dan mempunyai akibat langsung terhadap jangka waktu penyelesaian pekerjaan.
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><br>
                <b>PASAL 13</b>
                <br>
                <b>Ganti Rugi</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol>
                    <li>Pihak Kedua berhak menuntut Ganti Rugi apabila Pihak Pertama terlambat memberikan lahan pekerjaan yang dibutuhkan, sehingga lingkup pekerjaan Pihak Kedua tertunda.</li>
                    <li>Ganti Rugi pada Pasal ini ayat 1 berupa kompensasi pembayaran upah buruh sebesar  tanpa kompensasi lembur.</li>
                    <li>Ganti Rugi pada Pasal ini ayat 1 harus dibuat tertulis dan disetujui oleh Pihak Pertama baik jumlah hari maupun jumlah buruh yang akan bekerja.
</li>
                </ol>
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><br><b>PASAL 14</b>
                <br>
                <b>Perhitungan Volume Pekerjaan</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol>
                    <li>Perhitungan harga satuan (unit rate) berdasarkan Pasal 3 Surat Perintah Kerja ini.</li>
                    <li>Bila ada penambahan volume pekerjaan atau perubahan harga pada saat pelaksanaan, maka Para Pihak setuju untuk mengadakan perhitungan kembali yang dituangkan dalam Berita Acara Perubahan Pekerjaan (Variation Order Work) yang dilampirkan Laporan Perubahan Pekerjaan.</li>
                </ol>
            </div>
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br><br><b>PASAL 15</b>
                <br>
                <b>Penyelesaian Perselisihan</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <ol>
                    <li>Apabila timbul sengketa atau perselisihan antara Pihak Pertama dengan Pihak Kedua sehubungan dengan pelaksanaan pekerjaan ini, maka Para Pihak setuju untuk menyelesaikan secara musyawarah dan hasil yang dicapai dari musyawarah tersebut akan dinyatakan dalam suatu pernyataan tertulis yang bersifat mengikat secara hukum.</li>
                    <li>Apabila musyawarah tidak tercapai penyelesaian, maka Para Pihak sepakat menyerahkan perselisihan tersebut kepada suatu Badan Arbitrase (BANI) untuk diselesaikan.</li>
                </ol>
            </div>
  		</div>
	</div>
</section>

<section class="content">
	<div class="col-xs-12">
    	<div style="border: 7px double #999; text-align:center; width: 100%; height:950px">
    		<br>
            <div class="col-xs-12" align="center" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <b>PASAL 16</b>
                <br>
                <b>Penutup</b>
            </div>
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
                <br>
                <ol>
                    <li>Para Pihak sepakat dan sependapat serta mengakui Surat Perintah Kerja ini berikut dokumen dan lampirannya merupakan satu kesatuan yang tidak terpisahkan dan mengikat secara hukum bagi Para Pihak.</li>
                    <li>Surat Perintah Kerja ini dibuat dalam rangkap 3 (tiga), mempunyai kekuatan hukum yang sama, ditandatangani oleh Para Pihak, yang dibuat di atas kertas dengan dibubuhi cap/stempel dan 2 (dua) diantaranya bermeterai secukupnya sesuai peraturan/hukum yang berlaku.</li>
                    <li>Surat Perintah Kerja ini berlaku sejak ditandatangani Para Pihak sampai dengan seluruh tanggung jawab dan kewajiban Para Pihak terpenuhi.</li>
                </ol>
            </div>
    
            <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>Bandung, <font color="#FF0000"><?php echo "$days1 $monthV1 $yearsV1"; ?></font></b><br><br>
            </div>
            
            <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <b>PIHAK PERTAMA</b><br>
                <b><?php echo strtoupper($comp_name); ?></b>
                <br><br><br><br>
                <b><u><?php echo $PROJ_MNG; ?></u></b><br>
                <i><?php echo $DIR1; ?></i>
            </div>
            
            <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <b>PIHAK KEDUA</b><br>
                <b><font color="#FF0000"><?php echo $SPLDESC; ?></font></b>
                <br><br><br><br>
                <font color="#FF0000"><u><?php echo $SPLOTHR; ?></u></font><br>
                <font color="#FF0000"><i><?php echo $DIR2; ?></i></font>
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