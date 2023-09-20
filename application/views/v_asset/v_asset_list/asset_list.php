<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Maret 2017
 * File Name	= asset_list.php
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Name')$Name = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Brand')$Brand = $LangTransl;
		if($TranslCode == 'Capacity')$Capacity = $LangTransl;
		if($TranslCode == 'Year')$Year = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
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
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?>  </th>
           	  	<th width="34%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Name ?>  </th>
                <th width="13%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Type ?> </th>
              <th width="20%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Brand ?> </th>
              <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Capacity ?> </th>
                <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Year ?> </th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Price ?> </th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
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
				$AS_CODE 		= $row->AS_CODE;
				$AS_CODE_M 		= $row->AS_CODE_M;
				$AG_CODE		= $row->AG_CODE;
				$AS_NAME		= $row->AS_NAME;
				$AS_DESC		= $row->AS_DESC;
				$AS_TYPECODE	= $row->AS_TYPECODE;
				$AS_BRAND		= $row->AS_BRAND;
				$AS_SN			= $row->AS_SN;
				$AS_CAPACITY	= $row->AS_CAPACITY;
				$AS_MACHINE		= $row->AS_MACHINE;
				$AS_MACH_TYPE	= $row->AS_MACH_TYPE;
				$AS_MACH_SN		= $row->AS_MACH_SN;
				$AS_PRICE		= $row->AS_PRICE;
				$AS_YEAR		= $row->AS_YEAR;
				if($AS_YEAR == 0)
				{
					$AS_YEAR	= "-";
				}
				
				$AS_STAT		= $row->AS_STAT;
				
				if($AS_STAT == 1)
				{
					$AS_STATD	= "Ready";
					$bgColor	= "";	
				}
				elseif($AS_STAT == 2)
				{
					$AS_STATD	= "In Active";
					$bgColor	= "background:#FF8080";
				}
				elseif($AS_STAT == 3)
				{
					$AS_STATD	= "Used";
					$bgColor	= "background:#9CD68B";
				}
				elseif($AS_STAT == 4)
				{
					$AS_STATD	= "Maintenance";
					$bgColor	= "background:#E2C5E1";	
				}
				
				$secUpd			= site_url('c_asset/c_asset_list/update/?id='.$this->url_encryption_helper->encode_url($AS_CODE));
						
				if ($j==1) 
				{
					echo "<tr class=zebra1>";
					$j++;
				} 
				else 
				{
					echo "<tr class=zebra2>";						
					$j--;
				}
				?> 
                            <td style="text-align:center"><?php echo $myNewNo; ?></td>
                            <td nowrap> <?php echo anchor($secUpd,$AS_CODE_M);?></td>
                            <td nowrap><?php echo $AS_NAME; ?> </td>
                            <td nowrap><?php echo $AS_TYPECODE; ?></td>
                            <td nowrap><?php echo $AS_BRAND; ?></td>
                            <td nowrap style="text-align:right"><?php echo $AS_CAPACITY; ?>&nbsp;</td>
                            <td nowrap style="text-align:center"><?php echo $AS_YEAR; ?></td>
                            <td nowrap style="text-align:right"><?php print number_format($AS_PRICE, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:center;<?php //echo $bgColor; ?>">
                            <?php
								if($AS_STAT == 1)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/ready_icon2.png'; ?>" width="25" height="25" title="Stanby">
                                	<?php
								}
								elseif($AS_STAT == 2)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/inactive_red.png'; ?>" width="25" height="25" title="Damage">
                                	<?php
								}
								elseif($AS_STAT == 3)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/lock_icon1.png'; ?>" width="25" height="25" title="Used">
                                	<?php
								}
								elseif($AS_STAT == 4)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/repair_yellow1.png'; ?>" width="25" height="25" title="Repair">
                                	<?php
								}
								?>
                            </td>
                        </tr>
					<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="9" style="text-align:left">
				<?php
					if($ISCREATE == 1)
					{
						echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>');
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