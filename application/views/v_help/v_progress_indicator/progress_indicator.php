<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2017
 * File Name	= progress_indicator.php
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
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'IndicatorCode')$IndicatorCode = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Parent')$Parent = $LangTransl;
		if($TranslCode == 'Target')$Target = $LangTransl;
		if($TranslCode == 'Processed')$Processed = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small>project</small>
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
            <tr style="background:#CCCCCC">
                <th style="vertical-align:middle; text-align:center" width="7%"><?php echo $IndicatorCode ?> </th>
                <th style="vertical-align:middle; text-align:center" width="17%"><?php echo $Description ?> </th>
              <th style="vertical-align:middle; text-align:center;" width="21%" ><?php echo $Parent ?>  1</th>
                <th style="vertical-align:middle; text-align:center" width="33%"><?php echo $Parent ?>  2</th>
                <th style="vertical-align:middle; text-align:center" width="9%"><?php echo $Target ?> </th>
                <th style="vertical-align:middle; text-align:center" width="6%"><?php echo $Processed ?> </th>
            </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($recordcount > 0)
			{
				foreach($viewIndic as $row) :
					$myNewNo 		= ++$i;
					$IK_CODE		= $row->IK_CODE;
					$IK_PARENT		= $row->IK_PARENT;
					$IK_DESC		= $row->IK_DESC;
					$IK_DESC1		= "-";
					$IK_DESC2		= "-";
					$IK_TARGET		= $row->IK_TARGET;
					$IK_PROCESSED	= $row->IK_PROCESSED;
					$IK_ISHEADER	= $row->IK_ISHEADER;
					$IK_ISHEADER	= $row->IK_ISHEADER;
					
					// GET CHILD LEV 1
					/*$sqlPRNC 	= "tbl_indikator WHERE IK_PARENT = '$IK_CODE'";
					$resPRNC 	= $this->db->count_all($sqlPRNC);
					if($resPRNC > 0)
					{*/
						$sqlINDIC1 	= "SELECT IK_CODE, IK_PARENT, IK_DESC
										FROM tbl_indikator 
										WHERE IK_CODE = '$IK_PARENT'";
						$resINDIC1 	= $this->db->query($sqlINDIC1)->result();
						foreach($resINDIC1 as $rowINDIC1) :
							$IK_CODE1	= $rowINDIC1->IK_CODE;
							$IK_PARENT1	= $rowINDIC1->IK_PARENT;
							$IK_DESC1	= $rowINDIC1->IK_DESC;
							
							$sqlINDIC2 	= "SELECT IK_CODE, IK_PARENT, IK_DESC
										FROM tbl_indikator 
										WHERE IK_CODE = '$IK_PARENT1'";
							$resINDIC2 	= $this->db->query($sqlINDIC2)->result();
							foreach($resINDIC2 as $rowINDIC2) :
								$IK_DESC2	= $rowINDIC2->IK_DESC;
							endforeach;
						endforeach;
					//}
						
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
					?> 
                            <td nowrap>
                                <?php
									$secUpd			= site_url('c_help/progress_indicator/update/?id='.$this->url_encryption_helper->encode_url($IK_CODE));
                                echo anchor($secUpd,$IK_CODE);?></td>
                            <td nowrap> <?php print $IK_DESC; ?> </td>
                            <td nowrap> <?php print $IK_DESC1; ?> </td>
                            <td> <?php print $IK_DESC2; ?> </td>
                            <td style="text-align:center" nowrap> <?php print number_format($IK_TARGET, $decFormat); ?> </td>
                            <td style="text-align:center" nowrap> <?php print number_format($IK_PROCESSED, $decFormat); ?> </td>
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
					//echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add Indicator" />');
					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
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