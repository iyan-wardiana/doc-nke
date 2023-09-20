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
//$WO_REFNO 	= $default['WO_REFNO'];
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
{
	$DIR2	= "Pemilik Alat";
	$WO_CATEG_DESC = "Alat";
}
elseif($WO_CATEG == 'MDR')
{
	$DIR2	= "Mandor Borong";
	$WO_CATEG_DESC = "Mandor";
}
else
{
	$DIR2	= "Direktur Utama";
	$WO_CATEG_DESC = "Subkon";
}



$DIR1		= "Kepala Proyek";



if($WO_STAT == 2)
{
	$DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafCONFIRM.png) no-repeat center !important";
}
elseif($WO_STAT == 9)
{
	$DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafVOID.png) no-repeat center !important";
}
else
{
    $DrafTTD1   = "white";
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
    <style>
    	body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 12px Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
        width: 21cm !important;
        max-height: 29.7cm !important;
		padding-left: 1.5cm;
		padding-right: 1cm;
		padding-top: 1cm;
		padding-bottom: 1cm;
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
		/*@page {size: potrait}*/
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
    img.stamp {
        float: right;
        margin-left:5px;
        margin-top:-60px;
        width: 10px;
        position:absolute;
    }
    </style>
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->

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
            	<td width="100"><span class="style2" style="text-align:left; padding-left:6px;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" alt="" style="width:80px; height:30;"></span></td>
              <td style="vertical-align:top;">
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
                  <td width="800" style="border-top-width:2px; border-left-width:2px; text-align:center">
                   	<span style="font-size:16px; font-weight:bold">SURAT PERINTAH KERJA (SPK) - <?php echo strtoupper($WO_CATEG_DESC); ?></span>
                  </td>
                    <td width="200" style="border-top-width:2px; border-right-width:2px;"><span style="font-size:12px; font-weight:bold">No. : <?php echo $WO_CODE;?></span></td>
                </tr>
          </table>
			<table border="1" width="100%" rules="all" cellpadding="0" style="border-color:black;">
				<tr>
					<td width="50" style="border-left-width:2px; border-right:hidden; border-bottom:hidden;">Proyek</td>
					<td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
					<td width="250" style="border-bottom:hidden;"><?php echo $PRJNAME;?></td>
					<td width="50" style="border-right:hidden; border-bottom:hidden;">Supplier</td>
					<td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
					<td style="border-right-width:2px; border-bottom:hidden;"><?php echo ucwords($SPLDESC);?></td>
				</tr>
				<tr>
					<td width="50" style="border-left-width:2px; border-right:hidden; border-bottom:hidden;">Alamat</td>
					<td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
					<td width="250" style="border-bottom:hidden;"><?php echo $PRJLOCT;?></td>
					<td width="50" style="border-right:hidden; border-bottom:hidden;">Alamat</td>
					<td style="border-left:hidden;border-bottom:hidden;border-right:hidden;">:</td>
					<td style="border-right-width:2px; border-bottom:hidden;"><?php echo $SPLADD1;?></td>
				</tr>
				<tr>
					<td width="50" style="border-left-width:2px; border-right:hidden;">Pelaksana</td>
					<td style="border-left:hidden;border-right:hidden;">:</td>
					<td width="250"><?php //echo $PRJLOCT;?></td>
					<td width="50" style="border-right:hidden;">Telpon</td>
					<td style="border-left:hidden;border-right:hidden;">:</td>
					<td style="border-right-width:2px;"><?php echo $SPLTELP;?></td>
				</tr>
                <?php
                    $startD = date('d', strtotime($WO_STARTD));
                    $startM = tanggal_indo($WO_STARTD);
                    $startY = date('Y', strtotime($WO_STARTD));
                    $endD   = date('d', strtotime($WO_ENDD));
                    $endM   = tanggal_indo($WO_ENDD);
                    $endY   = date('Y', strtotime($WO_ENDD));
                    $day1   = strtotime($WO_ENDD) - strtotime($WO_STARTD);
                    $day    = floor($day1/(60 * 60 * 24));
                ?>
                <tr>
                    <td colspan="6" style="border-left-width: 2px; border-right-width: 2px; border-top-width: 2px; line-height: 20px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="150" style="padding-left: 0px;">WAKTU PELAKSANAAN</td>
                                <td>:</td>
                                <td>
                                    <?php echo "Tanggal $startD $startM $startY s/d $endD $endM $endY  ($day Hari)";?>
                                </td>
                            </tr>
                            <tr>
                                <td width="150" style="padding-left: 0px;">DESKRIPSI</td>
                                <td>:</td>
                                <td>
                                    <?php echo $WO_NOTE;?>
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
                    <td rowspan="2" style="text-align:center"><span style="font-size:10px;">URAIAN PEKERJAAN</span></td>
                    <td colspan="4" style="text-align:center; border-right-width:2px;"><span style="font-size:10px;">NILAI SPK</span></td>
                </tr>
            	<tr>
            	  <td style="text-align:center"><span style="font-size:10px;">VOL</span></td>
            	  <td style="text-align:center"><span style="font-size:10px;">SAT</span></td>
            	  <td style="text-align:center"><span style="font-size:10px;">HARGA</span></td>
            	  <td style="text-align:center; border-right-width:2px;"><span style="font-size:10px;">JUMLAH HARGA</span></td>
           	  </tr>
							<?php
							if($countwodet > 0):
								$no = 0;
								$WO_GTOTAL = 0;
								$WO_TOTDISC = 0;
								$TOT_TAXPPN = 0;
                                $TOT_TAXPPH = 0;
								foreach($vwodet->result() as $r):
									$no = $no + 1;
									$ITM_CODE		= $r->ITM_CODE;
									$JOBCODEID		= $r->JOBCODEID;
									$JOBDESC		= $r->JOBDESC;
                                    $WO_DESC        = $r->WO_DESC;
									$WO_VOLM		= $r->WO_VOLM;
									$ITM_UNIT		= $r->ITM_UNIT;
									$ITM_PRICE		= $r->ITM_PRICE;
									$WO_DISCP		= $r->WO_DISCP;
									$WO_DISC		= $r->WO_DISC;
									$WO_TOTDISC		= $WO_TOTDISC + $WO_DISC;
                                    $TAXCODE1       = $r->TAXCODE1;
                                    $TAXCODE2       = $r->TAXCODE2;
                                    if($TAXCODE1 == 'TAX01')
                                    {
                                        $TAXPRICE1      = $r->TAXPRICE1;
                                        $TOT_TAXPPN     = $TOT_TAXPPN + $TAXPRICE1;
                                    }

                                    if($TAXCODE2 == 'TAX02'){
                                        $TAXPRICE2      = $r->TAXPRICE2;
                                        $TOT_TAXPPH     = $TOT_TAXPPH + $TAXPRICE2;
                                    }elseif($TAXCODE2 == 'TAX03'){
                                        $TAXPRICE2      = $r->TAXPRICE2;
                                        $TOT_TAXPPH     = $TOT_TAXPPH + $TAXPRICE2;
                                    }
                                    elseif($TAXCODE2 == 'TAX03'){
                                        $TAXPRICE2      = $r->TAXPRICE2;
                                        $TOT_TAXPPH     = $TOT_TAXPPH + $TAXPRICE2;
                                    }
                                    elseif($TAXCODE2 == 'TAX04'){
                                        $TAXPRICE2      = $r->TAXPRICE2;
                                        $TOT_TAXPPH     = $TOT_TAXPPH + $TAXPRICE2;
                                    }

									$WO_TOTAL		= $r->WO_TOTAL;
									$WO_GTOTAL		= $WO_GTOTAL + $WO_TOTAL;
							?>
							<tr>
								<td style="border-left-width:2px; text-align:center;"><?=$no?></td>
								<td style="text-align:center;"><?=$ITM_CODE?></td>
								<td style="text-align:center;"><?=$JOBCODEID?></td>
								<td><!-- <?=$JOBDESC?> --> <?php echo $WO_DESC;?></td>
								<td style="text-align:center;"><?php echo number_format($WO_VOLM,2);?></td>
								<td style="text-align:center;"><?=$ITM_UNIT?></td>
								<td style="text-align:right;"><?php echo number_format($ITM_PRICE,2);?></td>
								<td style="border-right-width:2px;text-align:right;"><?php echo number_format($WO_TOTAL,2);?></td>
							</tr>
							<?php
							endforeach;
                            //row blank
                            $row = $no - 1;
                            if($row <= 25){
                                $lnrow = 25 - $row;
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

							//$TOT_TAXPPH     = $TOT_TAXP02 + $TOT_TAXP03 + $TOT_TAXP04;
                            $WO_GTOTAL1     = $WO_GTOTAL - $WO_TOTDISC;
							$GTOT_WO        = $WO_GTOTAL1 + $TOT_TAXPPN - $TOT_TAXPPH;
								?>
								<tr>
									<td colspan="4" rowspan="4" style="border-left-width:2px;text-align:left;">
										<b>Terbilang :</b> <br>
										<i><?php echo $textFormat->terbilang(round($GTOT_WO))." Rupiah"; ?></i>
									</td>
									<td colspan="3" style="text-align:right;">Total Harga</td>
									<td style="border-right-width:2px; text-align:right;"><?php echo number_format($WO_GTOTAL,2);?></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align:right;">PPN 10%</td>
									<td style="border-right-width:2px;text-align:right;"><?php echo number_format($TOT_TAXPPN,2);?></td>
								</tr>
                                <tr>
                                    <td colspan="3" style="text-align:right;">PPH</td>
                                    <td style="border-right-width:2px;text-align:right;"><?php echo number_format($TOT_TAXPPH,2);?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right; font-weight:bold;">Grand Total</td>
                                    <td style="border-right-width:2px;text-align:right;">
                                        <?php
                                            echo number_format($GTOT_WO,2);
                                        ?>
                                    </td>
                                </tr>
								<?php
							endif;
							?>
            </table>
            <table border="1" width="100%" rules="all" cellpadding="0" style="border-color:black;">
            	<tr>
                	<td width="73%" colspan="4" style="font-weight:bold"><span style="font-size:10px;">DIKELUARKAN DI __JAKARTA__<br>
               	  YANG MEMBERIKAN PEKERJAAN : PT SASMITO</span></td>
                  <td width="27%" style="text-align:center; font-weight:bold"><span style="font-size:10px;">MENYATAKAN SETUJU MENERIMA <br>
                  PEKERJAAN</span></td>
              </tr>
            	<tr>
            	  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">ENGINEERING (QS)</span></td>
            	  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">SITE MANAGER (SM) / <br>KOORD. MEP PROYEK</span></td>
            	  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">PROJECT MANAGER</span></td>
            	  <td style="font-weight:bold; text-align:center; border-bottom:dashed 2px"><span style="font-size:10px;">Mgr. Subkon/ Mgr. ME/ Mgr. Opr/<br>Ka. Cabang (untuk nilai &ge; 100jt)</span></td>
            	  <td style="text-align:center; font-weight:bold; border-bottom:dashed 2px"><span style="font-size:10px;">MANDOR / SUB / PEMASOK  *)<br>JAB.:</span></td>
          	  </tr>
                <?php
                    if($WO_STAT == 2){
                        $DrafTTD   = "DrafCONFIRM.png";
                        $showImg   = 1;
                    }elseif($WO_STAT == 3){
                        $DrafTTD   = "DrafApproved.png";
                        $showImg  = 0;
                    }elseif ($WO_STAT == 4) {
                        $DrafTTD   = "DrafRevised.png";
                        $showImg  = 0;
                    }elseif ($WO_STAT == 5) {
                        $DrafTTD   = "DrafRejected.png";
                        $showImg  = 0;
                    }elseif ($WO_STAT == 6) {
                        $DrafTTD   = "DrafClosed.png";
                        $showImg  = 0;
                    }elseif ($WO_STAT == 7) {
                        $DrafTTD   = "DrafAWAITING.png";
                        $showImg  = 0;
                    }elseif ($WO_STAT == 9) {
                        $DrafTTD   = "DrafVOID.png";
                        $showImg   = 1;
                    }
                ?>
            	<tr>
            	  <td height="79" style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 100px; position: absolute; margin-top: -60px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                    <span style="font-size:10px; font-style:italic; font-weight:bold">
                        NAMA:
                    </span>
                  </td>
            	  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 100px; position: absolute; margin-top: -60px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                    <span style="font-size:10px; font-style:italic; font-weight:bold">
                        
                        NAMA:
                    </span>
                  </td>
            	  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 100px; position: absolute; margin-top: -60px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                    <span style="font-size:10px; font-style:italic; font-weight:bold">
                        
                        NAMA:
                    </span>
                  </td>
            	  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 100px; position: absolute; margin-top: -60px; margin-left: 30px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                    <span style="font-size:10px; font-style:italic; font-weight:bold">
                        
                        NAMA:
                    </span>
                  </td>
            	  <td style="font-weight:bold; text-align:left; vertical-align:bottom">
                    <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 100px; position: absolute; margin-top: -60px; margin-left: 30px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                    <span style="font-size:10px; font-style:italic; font-weight:bold">
                        
                        NAMA:
                    </span>
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
            <span style="font-size:10px; font-style:italic; font-weight:bold">©Hakcipta, PT. SASMITO</span>
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