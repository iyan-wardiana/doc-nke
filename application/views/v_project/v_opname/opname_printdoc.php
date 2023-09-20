<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Maret 2017
 * File Name	= opname_printdoc.php
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

$OPNH_NUM 	= $default['OPNH_NUM'];
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

$OPNH_DATE	= '0000-00-00';
$sqlOPNH	= "SELECT OPNH_DATE FROM tbl_opn_header WHERE OPNH_NUM = '$OPNH_NUM'";
$resOPNH	= $this->db->query($sqlOPNH)->result();
foreach($resOPNH as $rowOPNH) :
	$OPNH_DATE	= $rowOPNH->OPNH_DATE;								
endforeach;

$WOSTARTD	= date('d M Y', strtotime($WO_STARTD));
$WOENDD		= date('d M Y', strtotime($WO_ENDD));

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
	$DIR2	= "Subkontraktor";
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
                <div style="border: 7px groove #999; text-align:center; width: 100%; font-size:16px; font-family:'Times New Roman', Times, serif">&nbsp;<br>
                <b>BERITA ACARA KEMAJUAN PEKERJAAN<br>
                <?php echo $JOBDESC; ?>
                </b><br><br></div>
            </div>
            <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>SPK : <?php echo $WO_CODE; ?></b><br>
                <b>Tanggal SPK : <?php echo $WO_DATE; ?></b><br>
                <b>Paket Pekerjaan : <font color="#FF0000" style="text-transform:uppercase"><?php echo $WO_NOTE; ?></font></b>
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
                Selaku <b>Kepala Proyek</b>, sesuai dengan Surat Keputusan No. <?php echo $WO_REFNO; ?> yang dalam hal ini bertindak untuk dan atas nama <b>PT. Mega Sukma</b>, pada <b><?php echo $PRJNAME; ?></b> yang berkedudukan di <?php echo $PRJLOCT; ?>, untuk selanjutnya disebut <b>Pihak Pertama</b>.
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
                Berdasarkan Surat Perintah Kerja SPK No. <?php echo $WO_CODE; ?>, tanggal : <?php echo $WO_DATE; ?> dan setelah mengadakan pemeriksaan bersama, maka Kedua Belah Pihak setuju dan sependapat bahwa Pihak Kedua sampai dengan tanggal Berita Acara Kemajuan Pekerjaan ini telah menyelesaikan pekerjaan <?php echo $WO_NOTE; ?>, sesuai yang disyaratkan dalam Surat Perintah Kerja tersebut di atas dengan Prestasi seperti tertera dalam Laporan Kemajuan Pekerjaan (Progress Work) Periode <?php echo "$WOSTARTD s/d $WOENDD"; ?>,  terlampir.
            </div>
            
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
           		<br>
                Demikian Berita Acara Kemajuan Pekerjaan ini dibuat dan ditanda tangani pada tanggal tersebut di atas oleh Kedua Belah Pihak untuk dipergunakan sebagaimana mestinya.
            </div>
    
            <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>&nbsp;</font></b><br><br>
            </div>
            
            <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <b>Pihak Pertama</b><br>
                <b><?php echo strtoupper($comp_name); ?></b>
                <br><br><br><br>
                <b><u><?php echo $PROJ_MNG; ?></u></b><br>
                <i><?php echo $DIR1; ?></i>
            </div>
            
            <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <b>Pihak Kedua</b><br>
                <b><font color="#FF0000"><?php echo $SPLDESC; ?></font></b>
                <br><br><br><br>
                <font color="#FF0000"><u><?php echo $SPLOTHR; ?></u></font><br>
                <font color="#FF0000"><i><?php echo $DIR2; ?></i></font>
            </div>
  		</div>
	</div>
    <br>&nbsp;
    <br>
  	<div class="col-xs-12">
    	<div style="border: 7px double #999; text-align:center; width: 100%; height:1200px">
    		<br>
            <div class="col-xs-12">
                <div style="border: 7px groove #999; text-align:center; width: 100%; font-size:16px; font-family:'Times New Roman', Times, serif">&nbsp;<br>
                <b>BERITA ACARA KEMAJUAN PEKERJAAN<br>
                <?php echo $JOBDESC; ?>
                </b><br><br></div>
            </div>
            <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>N. BAPP : <?php echo $WO_CODE; ?></b><br>
                <b>Tanggal SPK : <?php echo $WO_DATE; ?></b><br>
                <b>Paket Pekerjaan : <font color="#FF0000" style="text-transform:uppercase"><?php echo $WO_NOTE; ?></font></b>
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
                Selaku <b>Kepala Proyek</b>, sesuai dengan Surat Keputusan No. <?php echo $WO_REFNO; ?> yang dalam hal ini bertindak untuk dan atas nama <b>PT. Mega Sukma</b>, pada proyek<b><?php echo $PRJNAME; ?></b> yang berkedudukan di <?php echo $PRJLOCT; ?>, untuk selanjutnya disebut <b>Pihak Pertama</b>.
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
                Menerangkan bahwa Kedua Belah Pihak telah setuju dan sepakat melakukan pemeriksaan pekerjaan bersama dan menyetujui prestasi pekerjaan berdasarkan :
                <table width="100%" border="0">
                    <tr>
                        <td width="3%">&nbsp;</td>
                        <td width="18%" nowrap>1. No. Kontrak / SPK danTanggal</td>
                        <td width="1%">:</td>
                        <td width="78%"><?php echo $WO_CODE; ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>2. Harga Kontrak / SPK</td>
                        <td>:</td>
                        <td>Rp <?php echo number_format($WO_VALUE, 2); ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>3. Waktu Pelaksanaan</td>
                      <td>:</td>
                      <td><?php echo "$WOSTARTD s/d $WOENDD"; ?></td>
                    </tr>
                </table>
                <?php
					// TOTAL HARGA SPK
						$GWO_TOTAL	= 0;
						$GOPN_TOTAL	= 0;
						$sqlGTWO	= "SELECT SUM(WO_TOTAL) AS GWO_TOTAL, SUM(OPN_AMOUNT) AS GOPN_TOTAL
										FROM tbl_wo_detail WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
						$resGTWO	= $this->db->query($sqlGTWO)->result();
						foreach($resGTWO as $rowGTWO) :
							$GWO_TOTAL	= $rowGTWO->GWO_TOTAL;
							$GOPN_TOTAL	= $rowGTWO->GOPN_TOTAL;
						endforeach;
					
					// TOTAL PRESTASI SAAT INI
						$PROG_NOW	= 0;
						$sqlGOPN	= "SELECT SUM(OPND_ITMTOTAL) AS PROG_NOW
										FROM tbl_opn_detail WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
						$resGOPN	= $this->db->query($sqlGOPN)->result();
						foreach($resGOPN as $rowGOPN) :
							$PROG_NOW	= $rowGOPN->PROG_NOW;
						endforeach;
					
					// PROGRESS PRESTASI SAAT INI
						$PROG_NOWP	= $PROG_NOW / $GWO_TOTAL * 100;
					
					// PROGRESS PRESTASI SEBELUMNYA
						$PROG_BEF	= 0;
						$sqlGBEF	= "SELECT
											SUM(A.OPND_ITMTOTAL) AS PROG_BEF
										FROM
											tbl_opn_detail A
										INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
										WHERE A.OPNH_NUM = '$OPNH_NUM'
										AND B.OPNH_DATE < '$OPNH_DATE' AND A.PRJCODE = '$PRJCODE'";
						$resGBEF	= $this->db->query($sqlGBEF)->result();
						foreach($resGBEF as $rowGBEF) :
							$PROG_BEF	= $rowGBEF->PROG_BEF;
						endforeach;
					
					// PROGRESS PRESTASI SEBELUMNYA
						$PROG_BEFP	= $PROG_BEF / $GWO_TOTAL * 100;
					
					// PROGRESS PRESTASI SAMPAI SAAT INI
						$PROG_TONOWP= $PROG_NOWP + $PROG_BEFP;
						$PROG_TONOW	= $PROG_NOW + $PROG_BEF;
					
					// RETENSI
						$OPNH_DPPER	= 0;
						$OPNH_DPVAL	= 0;
						$PROG_RETP	= 0;
						$PROG_RET	= 0;
						$sqlRET		= "SELECT OPNH_DPPER, OPNH_DPVAL, OPNH_RETPERC, OPNH_RETAMN
										FROM tbl_opn_header
										WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
						$resRET		= $this->db->query($sqlRET)->result();
						foreach($resRET as $rowRET) :
							$OPNH_DPPER	= $rowRET->OPNH_DPPER;
							$OPNH_DPVAL	= $rowRET->OPNH_DPVAL;
							$PROG_RETP	= $rowRET->OPNH_RETPERC;
							$PROG_RET	= $rowRET->OPNH_RETAMN;
						endforeach;
					
					// PENGEMBALIAN UANG MUKA
						$DP_BACKP	= $OPNH_DPPER;
						$DP_BACK	= $OPNH_DPVAL;
					
					// PEMBAYARAN SEBELUMNYA
						$CB_PAID		= 0;
						$CB_PAID_PPn	= 0;
						$sqlPAID		= "SELECT A.CB_TOTAM, A.CB_TOTAM_PPn FROM tbl_bp_header A
											INNER JOIN tbl_bp_detail B ON A.CB_NUM = B.CB_NUM
											INNER JOIN tbl_pinv_detail C ON B.DocumentNo = C.INV_NUM
												AND C.IR_NUM = '$OPNH_NUM'
											WHERE B.CB_CATEG = 'OPN' 
												AND A.PRJCODE = '$PRJCODE'";
						$resPAID		= $this->db->query($sqlPAID)->result();
						foreach($resPAID as $rowPAID) :
							$CB_PAID	= $rowPAID->CB_TOTAM;
							$CB_PAID_PPn= $rowPAID->CB_TOTAM_PPn;
						endforeach;
						$PAID_TOTAL		= $CB_PAID + $CB_PAID_PPn;
					
					// PEMBAYARAN MENURUT PRESTASI SAAT INI
						$PAID_PRESN		= ($PROG_TONOW - $PAID_TOTAL - $DP_BACK - $PROG_RET);
					
					// PEMBAYARAN S.D. PRESTASI SAAT INI
						$PAID_TOPRESN	= $PAID_TOTAL;
					
					// PPn = 10% * PAID_PRESN
						//$PAID_TOPPNP	= 10;
						//$PAID_TOPPN		= $PAID_TOPPNP / 100 * $PAID_PRESN;
						
						$PAID_TOPPNP	= 0;
						$PAID_TOPPN		= 0;
						$sqlTPPN		= "SELECT WO_VALPPN AS PAID_TOPPN FROM tbl_wo_header
											WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
						$resTPPN		= $this->db->query($sqlTPPN)->result();
						foreach($resTPPN as $rowTPPN) :
							$PAID_TOPPN	= $rowTPPN->PAID_TOPPN;
						endforeach;
						$PAID_TOPPNP	= $PAID_TOPPN / $GWO_TOTAL * 100;
					
					// PEMBAYARAN BRUTO SAAT INI
						$PAID_BRUTO		= $PAID_PRESN + $PAID_TOPPN;
					
					// PPH
						$PAID_PPHP		= 0;
						$PAID_PPH		= $PAID_PPHP * $PAID_BRUTO;
					
					// POTONGAN LAIN-LAIN
						$POT_OTH		= 0;
						$sqlPOTH		= "SELECT OPNH_POT FROM tbl_opn_header
											WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
						$resPOTH		= $this->db->query($sqlPOTH)->result();
						foreach($resPOTH as $rowPOTH) :
							$POT_OTH	= $rowPOTH->OPNH_POT;
						endforeach;
					
					// PENERIMAAN NETTO
						$PAID_PPHP		= 0;
						$PAID_PPH		= $PAID_PPHP * $PAID_BRUTO;
					
					// PENERIMAAN NETTO SETELAH PPh
						$GRAND_TOTAL	= $PAID_BRUTO - $PAID_PPH - $POT_OTH;
						$GRAND_TOTALV	= $textFormat->terbilang($GRAND_TOTAL);
						
				?>
                Dengan perincian terlampir.<br>
                Maka Pihak Kedua berhak menerima pembayaran dengan perhitungan sebagai berikut :<br><br>
                <table width="100%" border="1">
                    <tr>
                        <td width="2%">A.</td>
                        <td width="59%" nowrap>Prestasi saat ini</td>
                        <td width="6%" style="text-align:center" nowrap><?php echo number_format($PROG_NOWP, 2); ?> %</td>
                        <td width="1%" style="text-align:center">x</td>
                        <td width="16%" style="text-align:right"><?php //echo number_format($GWO_TOTAL, 2); ?>&nbsp;</td>
                        <td width="16%" style="text-align:right"><?php echo number_format($PROG_NOW, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>B.</td>
                        <td>Prestasi s.d. yang lalu</td>
                        <td style="text-align:center" nowrap><?php echo number_format($PROG_BEFP, 2); ?> %</td>
                        <td style="text-align:center">x</td>
                        <td style="text-align:right"><?php //echo number_format($GWO_TOTAL, 2); ?>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($PROG_BEF, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>C.</td>
                        <td>Prestasi s.d. saat ini (A+B)</td>
                        <td style="text-align:center" nowrap><?php echo number_format($PROG_TONOWP, 2); ?> %</td>
                        <td style="text-align:center">x</td>
                        <td style="text-align:right"><?php //echo number_format($GWO_TOTAL, 2); ?>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($PROG_TONOW, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>D.</td>
                        <td>Potongan : (D1+D2+D3)</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                      	<td>&nbsp;</td>
                      	<td nowrap>D.1. Kum. Pengembalian Uang Muka (10%xKontrak)</td>
                        <td style="text-align:center" nowrap><?php echo number_format($DP_BACKP, 2); ?> %</td>
                        <td style="text-align:center">=</td>
                        <td style="text-align:right"><?php echo number_format($DP_BACK, 2); ?>&nbsp;</td>
                        <td style="text-align:right">&nbsp;</td>
                    </tr>
                    <tr>
                      	<td>&nbsp;</td>
                      	<td>D.2. Retensi (5%xC)</td>
                        <td style="text-align:center" nowrap><?php echo number_format($PROG_RETP, 2); ?> %</td>
                        <td style="text-align:center">=</td>
                        <td style="text-align:right"><?php echo number_format($PROG_RET, 2); ?>&nbsp;</td>
                        <td style="text-align:right">&nbsp;</td>
                    </tr>
                    <tr>
                      	<td>&nbsp;</td>
                      	<td>D.3. Pembayaran s.d. yang lalu</td>
                      	<td style="text-align:center">&nbsp;</td>
                      	<td style="text-align:center">=</td>
                      	<td style="text-align:right"><?php echo number_format($PAID_TOTAL, 2); ?>&nbsp;</td>
                      	<td>&nbsp;</td>
                    </tr>
                    <tr>
                      	<td>E.</td>
                      	<td>Prestasi Kerja Tambah / Kurang</td>
                      	<td style="text-align:center">&nbsp;</td>
                      	<td style="text-align:center">&nbsp;</td>
                      	<td>&nbsp;</td>
                      	<td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>F.</td>
                        <td>Pembayaran menurut prestasi saat ini ((C-D)+E)</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($PAID_PRESN, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>G.</td>
                        <td>Pembayaran s.d. prestasi saat ini (D.3+F)</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($PAID_TOPRESN, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>H.</td>
                        <td>PPN (0%xF)</td>
                        <td style="text-align:center"><?php echo number_format($PAID_TOPPNP, 2); ?> %</td>
                        <td style="text-align:center">x</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($PAID_TOPPN, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>I.</td>
                        <td>Pembayaran bruto saat ini ( F+H)</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($PAID_BRUTO, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                      	<td>J.</td>
                      	<td>PPH (2%)</td>
                      	<td style="text-align:center">&nbsp;</td>
                      	<td style="text-align:center">x</td>
                      	<td>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($PAID_PPH, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>K.</td>
                        <td>Potongan  (Bahan upah alat & BAU)</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($POT_OTH, 2); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>L.</td>
                        <td>Penerimaan Netto setelah PPh (I-J-K)</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"><?php echo number_format($GRAND_TOTAL, 2); ?>&nbsp;</td>
                    </tr>
                    <tr height="30px">
                      <td style="vertical-align:middle">M.</td>
                      <td style="vertical-align:bottom">Potongan lain-lain : __________________________________________________</td>
                      <td style="text-align:center">&nbsp;</td>
                      <td style="text-align:center">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td style="text-align:right">&nbsp;</td>
                    </tr>
                </table>
            </div>
            
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
           		<br>
                Terbilang:<br>
                <font style="font-style:italic"><b><?php echo "$GRAND_TOTALV Rupiah"; ?></b></font>
            </div>
            
            <div class="col-xs-12" align="justify" style="font-size:12px; font-family:'Times New Roman', Times, serif"">
           		<br>
                Demikian Berita Acara Prestasi Pekerjaan ini dibuat untuk dapa tdipergunakan sebagaimana mestinya.
            </div>
    
            <div class="col-xs-12" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <br>
                <b>&nbsp;</font></b><br>
            </div>
            
            <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <b>Pihak Pertama</b><br>
                <b><?php echo strtoupper($comp_name); ?></b>
                <br><br><br><br>
                <b><u><?php echo $PROJ_MNG; ?></u></b><br>
                <i><?php echo $DIR1; ?></i>
            </div>
            
            <div class="col-xs-6" align="center" style="font-size:14px; font-family:'Times New Roman', Times, serif"">
                <b>Pihak Kedua</b><br>
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