<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 1 Maret 2017 
 * File Name	= V_howtouse.php 
 * Location		= -
*/ 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');

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

	<?php
	
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Title')$Title = $LangTransl;
			if($TranslCode == 'Procedure')$Procedure = $LangTransl;
	
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
                <th width="5%">No.</th>
                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
           	  	<th width="22%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Title ?> </th>
                <th width="63%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Procedure ?> </th>
  	            <!--<th width="11%" style="text-align:center; vertical-align:middle" nowrap>Type</th>
                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>Color</th>
                <th width="11%" style="text-align:center; vertical-align:middle" nowrap>Status</th>
                <th width="12%" style="text-align:center; vertical-align:middle" nowrap>Note</th>
                <th width="14%" style="text-align:center; vertical-align:middle" nowrap>Quantity</th>-->

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
				$HTU_CODE 		= $row->HTU_CODE;
				$HTU_TITLE 		= $row->HTU_TITLE;
				$HTU_PROCEDURE	= $row->HTU_PROCEDURE;
//				$HTU_TYPE 		= $row->HTU_TYPE;
//				$HTU_COLOR 		= $row->HTU_COLOR;
//				$HTU_STAT 		= $row->HTU_STAT;
//				$HTU_NOTE		= $row->HTU_NOTE;
				
				$secUpd			= site_url('howtouse/update/?id='.$this->url_encryption_helper->encode_url($HTU_CODE));
				
				?> 
                
                            <td style="text-align:center"><?php echo $myNewNo; ?></td>
                            <td style="text-align:center" nowrap> <?php echo anchor($secUpd,$HTU_CODE);?></td>
                            <td nowrap><?php echo $HTU_TITLE; ?> </td>
                            <td nowrap><?php echo cut_text ($HTU_PROCEDURE,25); ?></td>
                            <!--<td nowrap><?php // echo $HTU_TYPE; ?></td>
                            <td nowrap><?php //echo $HTU_COLOR; ?></td>
                            <td nowrap><?php //echo $HTU_STAT; ?></td>
                            <td nowrap><?php //echo $HTU_NOTE; ?></td>-->
                            
                                <?php
								//$sqlq2 			= "SELECT SUM(HTU_QTY) as T_QTY FROM tbl_office_room_detail where HTU_CODE='$HTU_CODE'";
//													
//                    			$resq2 			= $this->db->query($sqlq2)->result();	
//                    
//                   				foreach($resq2 as $rowsqlq2) :
//									$T_QTY		= $rowsqlq2->T_QTY;
//								endforeach;
//								
//								if ($T_QTY=="")
//									$T_QTYx=0;
//								else
//									$T_QTYx=$T_QTY;
									
								?>
                                	<!--<td nowrap style="text-align:center;"><?php //echo $T_QTYx; ?></td>-->
                        </tr>
					<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" style="text-align:left">
				<?php
					/*echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />');*/
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