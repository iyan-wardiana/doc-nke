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
$OPNH_DATESP= '0000-00-00';
$sqlOPNH	= "SELECT OPNH_DATE, OPNH_DATESP FROM tbl_opn_header WHERE OPNH_NUM = '$OPNH_NUM'";
$resOPNH	= $this->db->query($sqlOPNH)->result();
foreach($resOPNH as $rowOPNH) :
	$OPNH_DATE	= $rowOPNH->OPNH_DATE;
	$OPNH_DATESP= $rowOPNH->OPNH_DATESP;								
endforeach;
$OPNH_DATEA = date('Y-m-d', strtotime('-6 days', strtotime($OPNH_DATE)));
$OPNH_DATEA = $OPNH_DATESP;

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

function tgl_indo($tanggal)
{
	$bulan = array 
	(
		1 =>   'Januari',
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
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

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

$tanggal	= $OPNH_DATE;

function tanggal_indo($tglIndo)
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
	$split = explode('-', $tglIndo);
	return $bulan[ (int)$split[1] ];
}

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

if($PRJLOCT == '')
	$PRJLOCT	= " ...... ";
if($WO_REFNO == '')
	$WO_REFNO	= " ...... ";

$SPLCATEG	= '';
$sqlSPLC	= "SELECT A.VendCat_Desc AS SPLCATEG FROM tbl_vendcat A
				INNER JOIN tbl_supplier B ON A.VendCat_Code = B.SPLCAT
				AND B.SPLCODE = '$SPLCODE'";
$resSPLC	= $this->db->query($sqlSPLC)->result();
foreach($resSPLC as $rowSPLC) :
	$SPLCATEG	= $rowSPLC->SPLCATEG;							
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

<body style="overflow:auto">
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
            <table width="100%" border="0" style="size:auto">
                <tr>
                    <td width="16%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
                    <td width="45%" class="style2">&nbsp;</td>
                    <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="150" height="100"></td>
                    <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">LAPORAN KEMAJUAN PEKERJAAN</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px">
                        (PROGRESS WORK)<br><br>
                        Paket Pekerjaan : <?php echo $WO_NOTE; ?>
                    </td>
                </tr>
				<?php
                    $OWNNAME	= '';
                    $COMPNAME	= '';
                    $sqlSPLC	= "SELECT A.PRJOWN, A.PRJ_MNG, CONCAT(B.own_Title, ' ', B.own_Name) AS OWNNAME,
                                        CONCAT(C.First_Name, ' ', C.Last_Name) AS COMPNAME
                                    FROM tbl_project A
                                        INNER JOIN tbl_owner B ON A.PRJOWN = B.own_Code
                                        LEFT JOIN tbl_employee C ON A.PRJ_MNG = C.Emp_ID
                                    WHERE A.PRJCODE = '$PRJCODE'";
                    $resSPLC	= $this->db->query($sqlSPLC)->result();
                    foreach($resSPLC as $rowSPLC) :
                        $OWNNAME	= $rowSPLC->OWNNAME;
                        $COMPNAME	= $rowSPLC->COMPNAME;
                    endforeach;		
                ?>
                <tr>
                    <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" class="style2" style="text-align:left; font-style:italic">
                        <table width="100%">
                            <!--<tr style="text-align:left; font-style:italic">
                                <td width="8%" nowrap valign="top">Type Dokumen</td>
                                <td width="1%">:</td>
                                <td width="91%"><?php// echo $DOCTYPE1; ?></td>
                            </tr>-->
                            <tr style="text-align:left; font-style:italic">
                                <td width="19%" nowrap valign="top">No. Kontrak / SPK dan Tanggal</td>
                                <td width="1%">:</td>
                                <td width="36%"><?php echo $WO_CODE; ?></span></td>
                                <td width="13%">Pemilik</td>
                                <td width="1%">:</td>
                                <td width="30%"><?php echo $OWNNAME; ?></td>
                            </tr>
                            <tr style="text-align:left; font-style:italic">
                              <td nowrap valign="top">Harga Kontrak / SPK</td>
                              <td>:</td>
                              <td><span class="style2" style="text-align:left; font-style:italic">Rp <?php echo number_format($WO_VALUE, 2); ?></span></td>
                              <td>Konsultan</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                          </tr>
                            <tr style="text-align:left; font-style:italic">
                              <td nowrap valign="top">Waktu Pelaksanaan</td>
                              <td>:</td>
                              <td><?php echo date('d-m-Y', strtotime($WOSTARTD))." s/d ".date('d-m-Y', strtotime($WOENDD)); ?></td>
                              <td>Kepala Proyek </td>
                              <td>:</td>
                              <td><?php echo $COMPNAME; ?></td>
                            </tr>
                            <tr style="text-align:left; font-style:italic">
                              <td nowrap valign="top">Periode</td>
                              <td>:</td>
                              <td><?php echo date('d-m-Y', strtotime($OPNH_DATESP))." s/d ".date('d-m-Y', strtotime($OPNH_DATE)); ?></td>
                              <td><?php echo $SPLCATEG; ?></td>
                              <td>:</td>
                              <td><?php echo $SPLDESC; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="style2" style="text-align:center"><hr /></td>
                </tr>
                <tr>
                    <td colspan="3" class="style2">
                        <table width="100%" border="1" rules="all">
                            <tr style="background:#CCCCCC">
                                <td rowspan="3" width="2%" nowrap style="text-align:center; font-weight:bold">No</td>
                                <td rowspan="3" width="26%" nowrap style="text-align:center; font-weight:bold">Uraian Pekerjaan</td>
                                <td colspan="5" nowrap style="text-align:center; font-weight:bold">Kontrak</td>
                                <td colspan="6" nowrap style="text-align:center; font-weight:bold">Kemajuan Pekerjaan</td>
                                <td width="16%" rowspan="3" nowrap style="text-align:center; font-weight:bold">Keterangan</td>
                            </tr>
                            <tr style="background:#CCCCCC">              
                                <td rowspan="2" width="4%" nowrap style="text-align:center; font-weight:bold">Volume</td>
                                <td rowspan="2" width="5%" nowrap style="text-align:center; font-weight:bold">Satuan</td>
                                <td rowspan="2" width="4%" nowrap style="text-align:center; font-weight:bold">Harga<br>Satuan</td>
                                <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Harga Total</td>
                                <td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Bobot %</td>              
                                <td colspan="2" nowrap style="text-align:center; font-weight:bold">Yang Lalu</td>
                                <td colspan="2" nowrap style="text-align:center; font-weight:bold">Saat Ini</td>
                                <td colspan="2" nowrap style="text-align:center; font-weight:bold">s.d. Saat Ini</td>              
                            </tr>
                            <tr style="background:#CCCCCC">
                                <td width="4%" nowrap style="text-align:center; font-weight:bold">Volume</td>
                                <td width="5%" nowrap style="text-align:center; font-weight:bold">Bobot %</td>
                                <td width="4%" nowrap style="text-align:center; font-weight:bold">Volume</td>
                                <td width="6%" nowrap style="text-align:center; font-weight:bold">Bobot %</td>
                                <td width="5%" nowrap style="text-align:center; font-weight:bold">Volume</td>
                                <td width="7%" nowrap style="text-align:center; font-weight:bold">Bobot %</td>
                            </tr>
                            <tr>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">2</td>
                                <td nowrap style="text-align:center;">4</td>
                                <td nowrap style="text-align:center;">5</td>
                                <td nowrap style="text-align:center;">6</td>
                                <td nowrap style="text-align:center;">7</td>
                                <td nowrap style="text-align:center;">8</td>
                                <td nowrap style="text-align:center;">9</td>
                                <td nowrap style="text-align:center;">10</td>
                                <td nowrap style="text-align:center;">11</td>
                                <td nowrap style="text-align:center;">12</td>
                                <td nowrap style="text-align:center;">13=9+11</td>
                                <td nowrap style="text-align:center;">14=10+12</td>
                                <td nowrap style="text-align:center;">18</td>
                            </tr>
                            <?php
                                $therow			= 0;
                                $GTOTAL			= 0;
                                $GTOTALA		= 0;
                                $GTOTALB		= 0;
                                $noU			= 0;
                                $noUa			= 0;
                                $noUb			= 0;
								
								$OPND_VOLMB		= 0;
								$BOQ_BOBOT_GT	= 0;
								$BOQ_BOBOT_GTB	= 0;
								$BOQ_BOBOT_GTC	= 0;
								
								$WO_GTOTAL1		= 0;
								$WO_GPPN		= 0;
								$sqlWOH			= "SELECT WO_VALUE, WO_VALPPN AS WO_GPPN FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";													
								$resWOH 		= $this->db->query($sqlWOH)->result();
								foreach($resWOH as $rowWOH) :
									$WO_GTOTAL1	= $rowWOH->WO_VALUE;
									$WO_GPPN 	= $rowWOH->WO_GPPN;
								endforeach;
								// HARUS NON PPN : MS.201991600043
								$WO_GTOTAL		= $WO_GTOTAL1 - $WO_GPPN;
														
                                $sqlq1 			= "SELECT A.OPNH_NUM, A.OPNH_CODE, A.JOBCODEID, C.JOBDESC, C.BOQ_BOBOT, A.ITM_CODE, A.ITM_UNIT, 
														A.OPND_VOLM, A.OPND_ITMPRICE, A.OPND_ITMTOTAL, A.OPND_DESC, B.WO_NUM, B.OPNH_DATE
													FROM tbl_opn_detail A
														INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
														INNER JOIN tbl_joblist C ON A.JOBCODEID = C.JOBCODEID
													WHERE A.OPNH_NUM = '$OPNH_NUM'";													
                                $resq1 			= $this->db->query($sqlq1)->result();
                                foreach($resq1 as $row1) :
                                    $therow			= $therow + 1;
                                    $OPNH_NUM 		= $row1->OPNH_NUM;
                                    $OPNH_CODE		= $row1->OPNH_CODE;
                                    $JOBCODEID	 	= $row1->JOBCODEID;
                                    $JOBDESC	 	= $row1->JOBDESC;
                                    $BOQ_BOBOT	 	= $row1->BOQ_BOBOT;
                                    $ITM_CODE	 	= $row1->ITM_CODE;
                                    $ITM_UNIT		= $row1->ITM_UNIT;
                                    $OPND_VOLM		= $row1->OPND_VOLM;
                                    $OPND_ITMPRICE	= $row1->OPND_ITMPRICE;
                                    $OPND_ITMTOTAL	= $row1->OPND_ITMTOTAL;
                                    $OPND_DESC		= $row1->OPND_DESC;
                                    $WO_NUM			= $row1->WO_NUM;
                                    $OPNH_DATE		= $row1->OPNH_DATE;
									
									// GET SPK DETAIL
										$WO_UNIT 		= $ITM_UNIT;
										$WO_VOLM		= 0;
										$WO_PRICE		= 0;
										$WO_TOTAL		= 0;
										$WO_TAXPPN		= 0;
										$sqlq2 			= "SELECT ITM_UNIT, WO_VOLM, ITM_PRICE, WO_TOTAL, TAXPRICE1 AS WO_TAXPPN
															FROM tbl_wo_detail WHERE WO_NUM = '$WO_NUM' AND JOBCODEID = '$JOBCODEID'";													
										$resq2 			= $this->db->query($sqlq2)->result();
										foreach($resq2 as $row2) :
											$WO_UNIT 	= $row2->ITM_UNIT;
											$WO_VOLM	= $row2->WO_VOLM;
											$WO_VOLMV	= $WO_VOLM;
											$WO_PRICE	= $row2->ITM_PRICE;
											$WO_TOTAL	= $row2->WO_TOTAL;
											$WO_TAXPPN	= $row2->WO_TAXPPN;
										endforeach;
										if($WO_VOLM == 0)
											$WO_VOLMV	= 1;
											
										$PROG_NOW_QTY	= $OPND_VOLMB + $OPND_VOLM;
									
									// GET OPN BEFORE
										$OPND_VOLMB		= 0;
										$sqlq3 			= "SELECT SUM(A.OPND_VOLM) AS OPND_VOLM
															FROM tbl_opn_detail A
																INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																	 AND B.OPNH_STAT IN (3,6) AND B.WO_NUM = '$WO_NUM'
															WHERE A.OPNH_NUM != '$OPNH_NUM' AND A.OPNH_DATE < '$OPNH_DATE'
																AND A.JOBCODEID = '$JOBCODEID'";													
										$resq3 			= $this->db->query($sqlq3)->result();
										foreach($resq3 as $row3) :
											$OPND_VOLMB	= $row3->OPND_VOLM;
										endforeach;
									
									$PROG_TOT_QTY		= $OPND_VOLMB + $OPND_VOLM;
									
									// GET BOBOT
										/*$BOQ_BOBOT		= 0;
										$sqlq4 			= "SELECT BOQ_BOBOT FROM tbl_joblist WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist 
															WHERE JOBCODEID = '$JOBCODEID')";													
										$resq4 			= $this->db->query($sqlq4)->result();
										foreach($resq4 as $row4) :
											$BOQ_BOBOT 	= $row4->BOQ_BOBOT;
										endforeach;*/
										// MS.201951000002
										// BOBOT AWAL DIDAPAT DARI harga total item : nilai kontrak subkon) x 100
										$BOQ_BOBOT		= $WO_TOTAL / $WO_GTOTAL * 100;
										
										$BOQ_BOBOT_GT	= $BOQ_BOBOT_GT + $BOQ_BOBOT;
										
										$BOQ_BOBOTBF	= $OPND_VOLMB / $WO_VOLMV * $BOQ_BOBOT;		// BOBOT BEFORE
										$BOQ_BOBOTCN	= $OPND_VOLM / $WO_VOLMV * $BOQ_BOBOT; 		// BOBOT CURRENT NOW
										$BOQ_BOBOTGT	= $BOQ_BOBOTBF + $BOQ_BOBOTCN; 				// BOBOT GRAND TOTAL
										
										$BOQ_BOBOT_GTB	= $BOQ_BOBOT_GTB + $BOQ_BOBOTBF;
										$BOQ_BOBOT_GTC	= $BOQ_BOBOT_GTC + $BOQ_BOBOTCN;
                                    ?>
                                        <tr>
                                            <td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
                                            <td style="text-align:left;"><?php echo $JOBDESC; ?></td>
                                            <td width="4%" nowrap style="text-align:right;"><?php echo number_format($WO_VOLM, $decFormat); ?></td>
                                            <td width="5%" nowrap style="text-align:center;"><?php echo $WO_UNIT; ?></td>
                                            <td width="4%" nowrap style="text-align:right;"><?php echo number_format($WO_PRICE, $decFormat); ?></td>
                                            <td width="7%" nowrap style="text-align:right;"><?php echo number_format($WO_TOTAL, $decFormat); ?></td>
                                            <td width="5%" nowrap style="text-align:right;"><?php echo number_format($BOQ_BOBOT, 4); ?></td>
                                            <td width="4%" nowrap style="text-align:right;"><?php echo number_format($OPND_VOLMB, $decFormat); ?></td>
                                            <td width="5%" nowrap style="text-align:right;"><?php echo number_format($BOQ_BOBOTBF, 4); ?></td>
                                            <td width="4%" nowrap style="text-align:right;"><?php echo number_format($OPND_VOLM, $decFormat); ?></td>
                                            <td width="6%" nowrap style="text-align:right;"><?php echo number_format($BOQ_BOBOTCN, 4); ?></td>
                                            <td width="5%" nowrap style="text-align:right;"><?php echo number_format($PROG_TOT_QTY, $decFormat); ?></td>
                                            <td width="7%" nowrap style="text-align:right;"><?php echo number_format($BOQ_BOBOTGT, 4); ?></td>
                                            <td width="16%" nowrap style="text-align:left;"><?php echo $OPND_DESC; ?></td>
                                        </tr>
                                    <?php
                                endforeach;
								$BOQ_BOBOT_GTG	= $BOQ_BOBOT_GTB + $BOQ_BOBOT_GTC;
                            ?>
                            <tr>
                                <td colspan="6" nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:right; font-weight:bold;"><?php echo number_format($BOQ_BOBOT_GT, 4); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right; font-weight:bold;"><?php echo number_format($BOQ_BOBOT_GTB, 4); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right; font-weight:bold;"><?php echo number_format($BOQ_BOBOT_GTC, 4); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right; font-weight:bold;"><?php echo number_format($BOQ_BOBOT_GTG, 4); ?></td>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="style2">
                        <table width="100%" border="0">
                            <tr>
                                <td width="30%" nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                                <td width="20%" nowrap style="text-align:right;">&nbsp;</td>
                                <td width="17%" nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                                <td width="33%" nowrap style="text-align:left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:right;">&nbsp;</td>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:left;"><?php echo $PRJLOCT; ?>, <?php echo tgl_indo(date('Y-m-d', strtotime($OPNH_DATE))); ?></td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:right;">&nbsp;</td>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:right;">&nbsp;</td>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:right;">&nbsp;</td>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:right; font-weight:bold; text-decoration:underline"><?php echo strtoupper($PROJ_MNG); ?></td>
                              <td nowrap style="text-align:right;">&nbsp;</td>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:left; font-weight:bold; text-decoration:underline"><?php echo strtoupper($SPLDESC); ?></td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:right; font-weight:bold;">Kepala Proyek</td>
                              <td nowrap style="text-align:right;">&nbsp;</td>
                              <td nowrap style="text-align:right; font-weight:bold;">&nbsp;</td>
                              <td nowrap style="text-align:left; font-weight:bold;"><?php echo "$SPLCATEG"; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
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