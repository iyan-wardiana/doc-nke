<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 April 2017
 * File Name	= asset_joblist.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

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
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'JobCode')$JobCode = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;

	endforeach;
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
                <th width="2%">No.</th>
              <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $JobCode ?> </th>
              <th width="15%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Project ?> </th>
           	  <th width="32%" style="text-align:center; vertical-align:middle" nowrap><?php echo $JobName ?> </th>
              <th width="40%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Notes ?> </th>
              <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
          </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($recordcount >0)
			{ 
			foreach($vAssetGroup as $row) :
				$myNewNo 		= ++$i;
				$JL_CODE 		= $row->JL_CODE;
				$JL_MANCODE 	= $row->JL_MANCODE;
				$JL_NAME		= $row->JL_NAME;
				$JL_PRJCODE		= $row->JL_PRJCODE;
				$JL_STAT		= $row->JL_STAT;
				
				if($JL_STAT == 1)
				{
					$JL_STATD	= "Process";
					$STATCOL	= 'warning';
				}
				elseif($JL_STAT == 2)
				{
					$JL_STATD	= "Done";
					$STATCOL	= 'success';
				}
				else
				{
					$JL_STATD	= "In Active";
					$STATCOL	= 'danger';
				}
				
				$PRJCODE 		= '';
				$PRJNAME		= '';
				$sqlPL 			= "SELECT PRJCODE, PRJNAME
									FROM tbl_project WHERE PRJCODE = '$JL_PRJCODE'
									ORDER BY PRJNAME";
				$resPL			= $this->db->query($sqlPL)->result();
				foreach($resPL as $rowPL) :
					$PRJCODE = $rowPL->PRJCODE;
					$PRJNAME = $rowPL->PRJNAME;
				endforeach;

				$JL_DESC		= $row->JL_DESC;
				
				$secUpd			= site_url('c_asset/c_asset_joblist/update/?id='.$this->url_encryption_helper->encode_url($JL_CODE));
					
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
				?> 
                            <td style="text-align:center"><?php echo $myNewNo; ?></td>
                            <td nowrap> <?php echo anchor($secUpd, $JL_MANCODE);?></td>
                            <td nowrap> <?php echo "$PRJCODE - $PRJNAME"; ?> </td>
                            <td> <?php echo $JL_NAME; ?> </td>
                            <td> <?php echo $JL_DESC; ?></td>
                            <td style="text-align:center;">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
								<?php 
                                    echo $JL_STATD;
                                 ?>
                             </span>
                            </td>
                            </tr>
					<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" style="text-align:left">
				<?php
					if($ISCREATE == 1)
					{
						echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>');
					}
				?></td>
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