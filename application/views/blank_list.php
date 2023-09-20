<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= project_planning.php
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
  <title><?php echo $appName; ?> | Data Tables</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minx.css'; ?>">
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
    <small>setting</small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>

<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
<div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="3%">&nbsp;</th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap> Project<br>Code</th>
                <th width="18%" style="text-align:center; vertical-align:middle" nowrap>Project Name</th>
                <th width="21%" style="text-align:center; vertical-align:middle" nowrap>Owner</th>
                <th width="4%" style="text-align:center; vertical-align:middle" nowrap>Contract<br>Amount (IDR)</th>
                <th width="2%" style="text-align:center; vertical-align:middle" nowrap>Last Contract <br>Amount (IDR)</th>
                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>SI Total<br />(IDR)</th>
                <th width="8%" style="text-align:center; vertical-align:middle" nowrap>Start Date</th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap>End Date</th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap>Status</th>
            </tr>
        </thead>
        <tbody>
		<?php 
			$i = 0;
			if($recordcount >0)
			{
			foreach($vewproject as $row) :
				$myNewNo 		= ++$i;
				$PRJCODE 		= $row->PRJCODE;
				$PRJCNUM		= $row->PRJCNUM;
				$PRJNAME		= $row->PRJNAME;
				$PRJLOCT		= $row->PRJLOCT;
				$PRJCOST		= $row->PRJCOST;
				$PRJCOST2		= $row->PRJCOST2;
				$secUpd			= site_url('c_project/listproject_sd/update/?id='.$this->url_encryption_helper->encode_url($theProjCode));
					?>        	
						<tr>
							<td style="text-align:center">&nbsp;</td>
                            <td>&nbsp;</td>
						  <td nowrap>&nbsp;</td>
						  <td >&nbsp;</td>
						  <td nowrap style="text-align:right">&nbsp;</td>
						  <td nowrap style="text-align:right">&nbsp;</td>
						  <td nowrap style="text-align:right">&nbsp;</td>
						  <td nowrap>&nbsp;</td>
						  <td nowrap>&nbsp;</td>
						  <td nowrap>&nbsp;</td>
		  </tr>
					<?php 
				endforeach; 
			}
			else
			{
				?>
				<tr>
					<td style="text-align:center" colspan="10"> --- None ---</td>
				</tr>
				<?php
			}
		?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="10" style="text-align:left">
				<?php echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add Project" />');?>            </th>
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