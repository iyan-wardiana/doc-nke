<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 21 Januari 2018
 * File Name	= print_matreq.php
 * Location		= -*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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
$LangID 		= $this->session->userdata['LangID'];
if($LangID == 'IND')
{
	$Transl_01	= "Halaman ini merupakan contoh untuk mencetak dokumen permintaan pembelian. Silahkan ajukan kepada kami untuk membuat halaman yang sebenarnya.";
}
else
{
	$Transl_01	= "This page is an example to print a purchase request document. Please feel free to ask us to create an actual page.";
}

$sql_01	= "SELECT * FROM tappname";
$res_01	= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
endforeach;

$AU_CODE         = $default['AU_CODE'];
$AUR_CODE        = $default['AUR_CODE'];
$AU_JOBCODE      = $default['AU_JOBCODE'];
$AU_AS_CODE      = $default['AU_AS_CODE'];
$AU_DATE         = $default['AU_DATE'];
$PRJCODE         = $default['PRJCODE'];
$AU_DESC         = $default['AU_DESC'];
$AU_STARTD       = $default['AU_STARTD'];
$AU_STARTD1      = date('d M Y H:s', strtotime($AU_STARTD));
$AU_ENDD         = $default['AU_ENDD'];
$AU_ENDD1        = date('d M Y H:s', strtotime($AU_ENDD));

$JOBTIME         = strtotime($AU_ENDD1) - strtotime($AU_STARTD1);
$JOBTIMEY        = floor($JOBTIME / (60 * 60 * 24 * 365)); 
$JOBTIMED        = floor($JOBTIME / (60 * 60 * 24)); 
$JOBTIMEH        = floor($JOBTIME / (60 * 60));
$minutes         = $JOBTIME - $JOBTIMEH * (60 * 60);
$JOBTIMES        = floor($minutes / 60 );
$AU_STARTT       = $default['AU_STARTT'];
$AU_ENDT         = $default['AU_ENDT'];
$AP_QTYOPR       = $default['AP_QTYOPR'];
$AP_QTYUNIT      = $default['AP_QTYUNIT'];
$AU_STAT         = $default['AU_STAT'];
$AU_NEEDITM      = $default['AU_NEEDITM'];
$AU_PROCS        = $default['AU_PROCS'];
$AU_CONFD        = $default['AU_CONFD'];
$AU_APPD         = $default['AU_APPD'];
$AU_PROCD        = $default['AU_PROCD'];
$AU_PROCT        = $default['AU_PROCT'];
$AU_REFNO        = $default['AU_REFNO'];

$WO_CODE  = '-';
$sqlWO    = "SELECT WO_NUM, WO_CODE FROM tbl_wo_header 
                WHERE WO_NUM = '$AU_REFNO' AND PRJCODE = '$PRJCODE'";
$resWO    = $this->db->query($sqlWO)->result();
foreach($resWO as $row) :
    $WO_NUM     = $row->WO_NUM;
    $WO_CODE    = $row->WO_CODE;
endforeach;

$AU_DATE   = date('d M Y', strtotime($AU_DATE));

$query = "SELECT PRJCODE, PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$res   = $this->db->query($query)->result();

foreach ($res as $r):
    $PRJCODE    = $r->PRJCODE;
    $PRJNAME    = $r->PRJNAME;
    $PRJLOCT    = $r->PRJLOCT;
endforeach;

//GET ASSET NAME
$qAsset = "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE = '$AU_AS_CODE' AND AS_STAT != 9";
$resAST = $this->db->query($qAsset)->result();
foreach ($resAST as $key) {
    $AS_NAME = $key->AS_NAME;
}

//GET JOB NAME
$qJOB   = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$AU_JOBCODE'";
$rJOB   = $this->db->query($qJOB)->result();
foreach ($rJOB as $keyJOB) {
    $JOBDESC    = $keyJOB->JOBDESC;
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
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>


<?php
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
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

<style type="text/css">
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
        padding-left:1.5cm;
        padding-right:1cm;
        padding-top:1cm;
        padding-bottom:1.5cm;
        /*padding: 0.01cm 0.2cm;*/
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        background-repeat: no-repeat;
        background-size: 550px 300px;
        background-position: center;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
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

	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

    <!-- <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php //echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div>
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
            <td><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" width="120" height="60" ></td>
            <td>&nbsp;</td>
            <td width="160">
                <table border="1" rules="all" width="100%" style="font-size: 10px;">
                    <tr>
                        <td>No. Form : &nbsp;</td>
                    </tr>
                    <tr>
                        <td>Revisi : &nbsp;</td>
                    </tr>
                    <tr>
                        <td>Tanggal Berlaku : &nbsp;   </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center;">
            &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center; font-size:18px; font-weight:bold">
            TIME SHEET HARIAN PEMAKAIAN ALAT BERAT
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center;">
            NO. : <?php echo $AU_CODE;?>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center;">
            &nbsp;
            </td>
        </tr>
    </table>
    <div class="col-md-6 col-sm-6" style="padding-left: 0px;">
        <table border="0" width="100%">
        <tr>
            <td style="vertical-align: top;">Nama Proyek</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;" width="230"><?php echo $PRJNAME; ?></td>
        </tr>
        <tr>
            <td>Lokasi Proyek</td>
            <td>:</td>
            <td width="230"><?php echo $PRJLOCT;?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td width="230"><?php echo $AU_DATE;?></td>
        </tr>
    </table>
    </div>
    <div class="col-md-6 col-sm-6">
        <table border="0" width="100%">
        <tr>
            <td width="150">Jenis Alat</td>
            <td>:</td>
            <td width="230"><?php echo $AS_NAME;?></td>
        </tr>
        <tr>
            <td width="150">No. SPK</td>
            <td>:</td>
            <td width="230"><?php echo $WO_CODE; ?></td>
        </tr>
        <tr>
            <td width="150">Operator</td>
            <td>:</td>
            <td width="230">&nbsp;</td>
        </tr>
        <tr>
            <td width="150">Penanggung Jawab</td>
            <td>:</td>
            <td width="230">&nbsp;</td>
        </tr>
    </table>
    </div>
    
    <table border="1" width="100%" rules="all">
        <tr>
            <td style="text-align: center; font-weight: bold;">NO.</td>
            <td width="200" colspan="2" style="text-align: center; font-weight: bold;">Kegiatan Alat</td>
            <td width="100" style="text-align: center; font-weight: bold;">Mulai Kerja</td>
            <td width="100" style="text-align: center; font-weight: bold;">Berhenti<br>Kerja</td>
            <td width="100" style="text-align: center; font-weight: bold;">Jumlah Jam<br>Kerja</td>
            <td width="200" style="text-align: center; font-weight: bold;">KETERANGAN</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.</td>
            <td width="150"><?php echo $JOBDESC;?></td>
            <td width="50" style="text-align: center;">Pagi</td>
            <td width="100"><?php echo $AU_STARTD1;?></td>
            <td width="100"><?php echo $AU_ENDD1;?></td>
            <td width="100" style="text-align: center;"><?php echo "$JOBTIMEH Jam";?></td>
            <td width="200"><?php echo $AU_DESC;?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td width="150">&nbsp;</td>
            <td width="50" style="text-align: center;">Siang</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td width="150">&nbsp;</td>
            <td width="50" style="text-align: center;">Malam</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td width="150">&nbsp;</td>
            <td width="50" style="text-align: center;">Total</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold;">NO.</td>
            <td width="200" colspan="2" style="text-align: center; font-weight: bold;">Kegiatan Operator</td>
            <td width="100" style="text-align: center; font-weight: bold;">Mulai Kerja</td>
            <td width="100" style="text-align: center; font-weight: bold;">Berhenti<br>Kerja</td>
            <td width="100" style="text-align: center; font-weight: bold;">Jumlah Jam<br>Kerja</td>
            <td width="200" style="text-align: center; font-weight: bold;">KETERANGAN</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td width="150">&nbsp;</td>
            <td width="50" style="text-align: center;">Pagi</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td width="150">&nbsp;</td>
            <td width="50" style="text-align: center;">Siang</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td width="150">&nbsp;</td>
            <td width="50" style="text-align: center;">Malam</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td width="150">&nbsp;</td>
            <td width="50" style="text-align: center;">Total</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="100">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                <b>Penyewa:</b><br><br><br><br><br><br><br>
                (..........................................)<br>
                <b>Kepala Proyek</b>
            </td>
            <td colspan="3" style="text-align: center;">
                <b>Pemilik:</b><br><br><br><br><br><br><br>
                (..........................................)<br>
                <b>Operator</b>
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