<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= asset_maintenance.php
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
		if($TranslCode == 'AssetName')$AssetName = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'DocStatus')$DocStatus = $LangTransl;
		if($TranslCode == 'ProcessStat')$ProcessStat = $LangTransl;

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
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
              <th width="21%" style="text-align:center; vertical-align:middle" nowrap><?php echo $AssetName ?> </th>
              <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $StartDate ?> / <?php echo $EndDate?> </th>
              <th width="44%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?> </th>
              <th width="1%" style="text-align:center; vertical-align:middle" nowrap><?php echo $DocStatus ?> </th>
              <th width="2%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ProcessStat ?></th>
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
				$AS_NAME		= '';
					$sqlAS 		= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE  = '$AM_AS_CODE'";
					$resultAS 		= $this->db->query($sqlAS)->result();
					foreach($resultAS as $rowAS) :
						$AS_NAME 	= $rowAS->AS_NAME;
					endforeach;
				$AM_DATE		= $row->AM_DATE;
				$AM_PRJCODE		= $row->AM_PRJCODE;
				$AM_DESC		= $row->AM_DESC;
				$AM_STARTD		= $row->AM_STARTD;
					$AM_STARTDD	= date('m/d/Y',strtotime($AM_STARTD));
				$AM_STARTT		= $row->AM_STARTT;
					$AM_STARTTD	= date('H:i',strtotime($AM_STARTT));
				$AM_ENDD		= $row->AM_ENDD;
					$AM_ENDDD	= date('m/d/Y',strtotime($AM_ENDD));
				$AM_ENDT		= $row->AM_ENDT;
					$AM_ENDTD	= date('H:i',strtotime($AM_ENDT));
				$AM_STAT		= $row->AM_STAT;
				if($AM_STAT == 1)
				{
					$AM_STATD	= 'New';
					$STATCOL	= 'warning';
				}
				elseif($AM_STAT == 2)
				{
					$AM_STATD	= 'Confirm';
					$STATCOL	= 'primary';
				}
				elseif($AM_STAT == 3)
				{
					$AM_STATD	= 'Approve';
					$STATCOL	= 'success';
				}
				
				$AM_PROCS		= $row->AM_PROCS;
				if($AM_PROCS == 0)
				{
					$AM_PROCSD	= 'Open';
				}
				elseif($AM_PROCS == 1)
				{
					$AM_PROCSD	= 'Procesing';
				}
				elseif($AM_PROCS == 2)
				{
					$AM_PROCSD	= 'Finished';
				}
				elseif($AM_PROCS == 3)
				{
					$AM_PROCSD	= 'Canceled';
				}
				
				$secUpd			= site_url('c_asset/c_453tM41Nt/update/?id='.$this->url_encryption_helper->encode_url($AM_CODE));
					
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
                            <td nowrap><?php echo $AS_NAME; ?></td>
                            <td nowrap style="text-align:center"><?php echo "$AM_STARTDD - $AM_ENDDD<br>$AM_STARTTD - $AM_ENDTD"; ?></td>
                            <td nowrap><?php echo $AM_DESC; ?></td>
                            <td nowrap style="text-align:center">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
								<?php 
                                    echo $AM_STATD;
                                 ?>
                             </span>
							</td>
                            <td nowrap style="text-align:center">
                            <?php
								if($AM_PROCS == 0)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/draft_icon.png'; ?>" width="25" height="25" title="Draft">
                                	<?php
								}
								elseif($AM_PROCS == 1)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/process_icon2.png'; ?>" width="25" height="25" title="Processing">
                                	<?php
								}
								elseif($AM_PROCS == 2)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/finish_icon.png'; ?>" width="25" height="25" title="Finish">
                                	<?php
								}
								elseif($AM_PROCS == 3)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/canceled_icon.png'; ?>" width="25" height="25" title="Canceled">
                                	<?php
								}
								?>
                            </td>
                        </tr>
					<?php 
				endforeach; 
			}
			$secAddURL = site_url('c_asset/c_453tM41Nt/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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