<?php
/* 
 * Author		= Hendar Permana
 * Create Date	= 14 Juni 2017
 * File Name	= v_entry_provit.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;
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
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
        <thead>
            <tr>
                <th width="4%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>No.</th>
                <th width="5%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>Code</th>
                <th width="5%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>Project Code</th>
                <th width="5%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>Date</th>
           	  	<th width="32%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>Progres Kontraktuil</th>
                <th width="14%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>Pekerjaan / MC Katrolan</th>
                
                <th width="14%" colspan="6" style="text-align:center; vertical-align:middle" nowrap>Proyek</th>
                <th width="14%" colspan="4" style="text-align:center; vertical-align:middle" nowrap>Pusat</th>
                <th width="14%" colspan="6" style="text-align:center; vertical-align:middle" nowrap>Stok</th>
                
                <th width="12%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>Over Booking</th>
                <th width="12%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap>Beban Alat</th>

            </tr>
            <tr>
            	
  	            <th style="text-align:center; vertical-align:middle" nowrap>WIP Proyek</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Material</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Upah</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Subkon WIP</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Alat</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Lain-Lain</th>
                
                <th style="text-align:center; vertical-align:middle" nowrap>Material</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Subkon</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Alat</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Lain-Lain</th>
                
                <th style="text-align:center; vertical-align:middle" nowrap>Stok</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Material</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Pelumas & BBM</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Suku Cadang</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Lain-Lain</th>
                <th style="text-align:center; vertical-align:middle" nowrap>Inventaris</th>
                
            </tr>
        </thead>
        <tbody>
		<?php
			
			$i = 0;
			$j = 0;
			
			if($recordcount >0)
			{ 
			foreach($vAssetGroup as $row) :
				$myNewNo 			= ++$i;
				$CODE_PROFLOSS 		= $row->CODE_PROFLOSS;
				$PRJCODE	 		= $row->PRJCODE;
				$DATE		 		= $row->DATE;
				$PROG_KONTRAKTUIL 	= $row->PROG_KONTRAKTUIL;
				$PEKERJAAN 			= $row->PEKERJAAN;
				$PROYEK_WIP 		= $row->PROYEK_WIP;
				$PROYEK_MATERIAL 	= $row->PROYEK_MATERIAL;
				$PROYEK_UPAH 		= $row->PROYEK_UPAH;
				$PROYEK_SUBKON 		= $row->PROYEK_SUBKON;
				$PROYEK_ALAT 		= $row->PROYEK_ALAT;
				$PROYEK_OVERHEAD	= $row->PROYEK_OVERHEAD;
				$PUSAT_MATERIAL		= $row->PUSAT_MATERIAL;
				$PUSAT_SUBKON 		= $row->PUSAT_SUBKON;
				$PUSAT_ALAT 		= $row->PUSAT_ALAT;
				$PUSAT_OVERHEAD 	= $row->PUSAT_OVERHEAD;
				$STOK 				= $row->STOK;
				$STOK_MATERIAL 		= $row->STOK_MATERIAL;
				$STOK_PELUMAS_BBM 	= $row->STOK_PELUMAS_BBM;
				$STOK_SUKU_CADANG 	= $row->STOK_SUKU_CADANG;
				$STOK_LAIN_LAIN 	= $row->STOK_LAIN_LAIN;
				$STOK_INV_BEKISTING = $row->STOK_INV_BEKISTING;
				$OVER_BOOKING 		= $row->OVER_BOOKING;
				$BEBAN_ALAT 		= $row->BEBAN_ALAT;
				
				$secUpd			= site_url('c_gl/c_entry_provit/update/?id='.$this->url_encryption_helper->encode_url($CODE_PROFLOSS));
				
				?> 
                <tr>
                            <td style="text-align:center"><?php echo $myNewNo; ?></td>
                            <td nowrap style="text-align:center"><?php echo anchor($secUpd,$CODE_PROFLOSS);?></td>
                            <td nowrap style="text-align:center"><?php echo $PRJCODE;?></td>
                            <td nowrap style="text-align:center"><?php echo $DATE;?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PROG_KONTRAKTUIL, $decFormat); ?> </td>
                            <td nowrap style="text-align:right"><?php echo number_format($PEKERJAAN, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PROYEK_WIP, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PROYEK_MATERIAL, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PROYEK_UPAH, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right"><?php echo number_format($PROYEK_SUBKON, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PROYEK_ALAT, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PROYEK_OVERHEAD, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PUSAT_MATERIAL, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PUSAT_SUBKON, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PUSAT_ALAT, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($PUSAT_OVERHEAD, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($STOK, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($STOK_MATERIAL, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($STOK_PELUMAS_BBM, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($STOK_SUKU_CADANG, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($STOK_LAIN_LAIN, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($STOK_INV_BEKISTING, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($OVER_BOOKING, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($BEBAN_ALAT, $decFormat); ?></td>
                            
                        </tr>
					<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="24" style="text-align:left">
				<?php echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />');?>            </td>
          </tr>
        </tfoot>
   	</table>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
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
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>