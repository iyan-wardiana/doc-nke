<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 April 2017
 * File Name	= asset_usagereq.php
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
	$LangID 	= $this->session->userdata['LangID'];

$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl		= $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode	= $rowTransl->MLANG_CODE;
	$LangTransl	= $rowTransl->LangTransl;
		
	if($TranslCode == 'Add')$Add = $LangTransl;
	if($TranslCode == 'Edit')$Edit = $LangTransl;
	if($TranslCode == 'Back')$Back = $LangTransl;
	if($TranslCode == 'Code')$Code = $LangTransl;
	if($TranslCode == 'JobName')$JobName = $LangTransl;
	if($TranslCode == 'AssetName')$AssetName = $LangTransl;
	if($TranslCode == 'Date')$Date = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'Status')$Status = $LangTransl;

endforeach;
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
                <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?></th>
       	  	  <th width="24%" style="text-align:center; vertical-align:middle" nowrap><?php echo $JobName ?></th>
              <th width="19%" style="text-align:center; vertical-align:middle" nowrap><?php echo $AssetName ?></th>
              <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date ?></th>
              <th width="43%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?></th>
              <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?></th>
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
				$AUR_CODE 		= $row->AUR_CODE;
				$AUR_JOBCODE	= $row->AUR_JOBCODE;
				$AUR_AS_CODE	= $row->AUR_AS_CODE;
				$AUR_DATE		= $row->AUR_DATE;
				$AUR_PRJCODE	= $row->AUR_PRJCODE;
				$AUR_DESC		= $row->AUR_DESC;
				$AUR_STARTD		= $row->AUR_STARTD;
				$AUR_ENDD		= $row->AUR_ENDD;
				$AUR_STAT		= $row->AUR_STAT;
				if($AUR_STAT == 1)
				{
					$AUR_STATD	= 'New';
				}
				elseif($AUR_STAT == 2)
				{
					$AUR_STATD	= 'Confirm';
				}
				elseif($AUR_STAT == 3)
				{
					$AUR_STATD	= 'Approve';
				}
				
				$PRJNAME		= "None";
				$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$AUR_JOBCODE'";
				$resultPRJ 		= $this->db->query($sqlPRJ)->result();
					foreach($resultPRJ as $rowPRJ) :
						$PRJNAME 	= $rowPRJ->PRJNAME;
					endforeach;	
				
				$AS_NAME		= "None";
				$sqlAST 		= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE  = '$AUR_AS_CODE'";
				$resultAST 		= $this->db->query($sqlAST)->result();
					foreach($resultAST as $rowAST) :
						$AS_NAME 	= $rowAST->AS_NAME;
					endforeach;											
				
				$secUpd			= site_url('c_asset/c_asset_usagereq/update/?id='.$this->url_encryption_helper->encode_url($AUR_CODE));
					
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
				?> 
                            <td style="text-align:center"><?php echo $myNewNo; ?></td>
                            <td nowrap> <?php echo anchor($secUpd,$AUR_CODE);?></td>
                            <td nowrap><?php echo $AUR_AS_CODE; ?> </td>
                            <td nowrap><?php echo "$AUR_PRJCODE - $AS_NAME"; ?></td>
                            <td nowrap><?php echo $AUR_DATE; ?></td>
                            <td nowrap><?php echo $AUR_DESC; ?></td>
                            <td nowrap><?php echo $AUR_STATD; ?></td>
                        </tr>
					<?php 
				endforeach; 
			}
			$secAddURL = site_url('c_asset/c_asset_usagereq/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		?>
        </tbody>
        <tfoot>
          <tr>
            	<td colspan="7" style="text-align:left">
					<?php
						if($ISCREATE == 1)
						{
							echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>');
						}
					?>
					&nbsp;
					<?php 
							echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
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