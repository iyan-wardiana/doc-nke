<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= inb_asset_maintenance.php
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;
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
    <small><?php echo $PRJNAME; ?></small>
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
                <th width="2%">No.</th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap>Code</th>
           	  	<th width="18%" style="text-align:center; vertical-align:middle" nowrap>Job Name</th>
              <th width="21%" style="text-align:center; vertical-align:middle" nowrap>Asset Name</th>
              <th width="6%" style="text-align:center; vertical-align:middle" nowrap>Date</th>
              <th width="44%" style="text-align:center; vertical-align:middle" nowrap>Decsription</th>
              <th width="3%" style="text-align:center; vertical-align:middle" nowrap>Status</th>
          </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($recordcount >0)
			{ 
			foreach($vAssetUsage as $row) :
				$myNewNo 		= ++$i;
				$AM_CODE 		= $row->AM_CODE;
				$AM_AS_CODE		= $row->AM_AS_CODE;
				$AM_DATE		= $row->AM_DATE;
				$AM_PRJCODE		= $row->AM_PRJCODE;
				$AM_DESC		= $row->AM_DESC;
				$AM_STARTD		= $row->AM_STARTD;
				$AM_ENDD		= $row->AM_ENDD;
				$AM_STAT		= $row->AM_STAT;
				if($AM_STAT == 1)
				{
					$AM_STATD	= 'New';
				}
				elseif($AM_STAT == 2)
				{
					$AM_STATD	= 'Confirm';
				}
				elseif($AM_STAT == 3)
				{
					$AM_STATD	= 'Approve';
				}
				
				$secUpd			= site_url('c_asset/c_453tM41Nt/inbox_update/?id='.$this->url_encryption_helper->encode_url($AM_CODE));
					
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
				?> 
                            <td style="text-align:center"><?php echo $myNewNo; ?></td>
                            <td nowrap> <?php echo anchor($secUpd,$AM_CODE);?></td>
                            <td nowrap><?php echo $AM_AS_CODE; ?> </td>
                            <td nowrap><?php echo $AM_PRJCODE; ?></td>
                            <td nowrap><?php echo $AM_DATE; ?></td>
                            <td nowrap><?php echo $AM_DESC; ?></td>
                            <td nowrap><?php echo $AM_STATD; ?></td>
                        </tr>
					<?php 
				endforeach; 
			}
			$secAddURL = site_url('c_asset/c_asset_usage/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		?>
        </tbody>
        <tfoot>
          <tr style="display:none">
            <td colspan="7" style="text-align:left">
				<?php echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add Asset Usage" />');?>&nbsp;
				<?php 
					if ( ! empty($link))
					{
						foreach($link as $links)
						{
							echo $links;
						}
					}
				?>
				
			</td>
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