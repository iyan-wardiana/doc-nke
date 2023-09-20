<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 5 Februari 2018
 * File Name	= v_reservation_mr_avail.php 
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
    <!-- iCheck for checkboxes and radio inputs -->
  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css';?>">
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
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Topic')$Topic = $LangTransl;
		if($TranslCode == 'MeetingRoom')$MeetingRoom = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Topic')$Topic = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title		= 'Kendaraan';
		$h2_title		= 'status';
		$MeetingRoom	= 'Pemesanan Ruang Rapat';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Pesan Sekarang';
		$Available		= 'Tersedia';
		$sureDelete		= 'Anda yakin akan menghapus data ini?';
		$Reschedule		= 'Jadwal Ulang';
	}
	else
	{
		$h1_title		= 'Vehicle';	
		$h2_title		= 'Status';	
		$MeetingRoom	= 'Meeting Room Reservation';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Book Now';
		$Available		= 'Available';
		$sureDelete		= 'Are your sure want to delete?';
		$Reschedule		= 'Reschedule';
	}
	
	$addReqMR			= site_url('howtouse/addMR/?id='.$this->url_encryption_helper->encode_url($appName));		// Meeting Room Request
	//$addReqVC			= site_url('howtouse/addVC/?id='.$this->url_encryption_helper->encode_url($appName));		// Vehicle Request
	$addReqRR			= site_url('howtouse/addRR/?id='.$this->url_encryption_helper->encode_url($appName));		// Rest Room Request
	
?>

<body class="hold-transition skin-blue sidebar-mini">
    <section class="content-header">    
    <h1>
        <?php echo $h1_title; ?>
        <small><?php echo $h2_title; ?></small>
      </h1><br>
    </section>
	<style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>    
    <div class="box">
        <div class="box-body">
            <div class="search-table-outter">
            	<div class="col-md-3">
                  <div class="box box-warning box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Jenis Kendaraan</h3>
        
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                      <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <!-- checkbox -->
                      <?php
					  	$CountCTG 	= $this->db->count_all('tbl_vehicle');
						$sqlCTG 	= "SELECT DISTINCT VH_TYPE FROM tbl_vehicle";
						$resCTG		= $this->db->query($sqlCTG)->result_array();
						
						$VH_TYPEX = "ALL";
						if(isset($_POST['submitTYP']))
						{
							$VH_TYPEX = $_POST['VH_TYPE'];	
							
						}
						//echo $VH_TYPEX;
					  ?>
                      <form name="frmTYP" id="frmTYP" method="post">
                      <label for="inputName" class="col-sm-12 control-label">Cari Berdasarkan Jenis Kendaraan :</label>
                      <div class="form-group">
                        <select name="VH_TYPE" id="VH_TYPE" class="form-control" style="max-width:250px" onChange="return searchTYP();">
                            <option value="ALL" <?php if($VH_TYPEX == "ALL"){ ?> selected <?php }?> >--- ALL ---</option>
                            <?php
							if($CountCTG > 0)
							{
								foreach($resCTG as $rowCTG)
								{
									$VH_TYPE	= $rowCTG['VH_TYPE'];
							?>
									<option value="<?=$VH_TYPE?>" <?php if($VH_TYPEX == $VH_TYPE){ ?> selected <?php }?>><?=$VH_TYPE?></option>
									<?php
								}
							}
							?>
                        </select>
                      </div>
                      <input type="submit" name="submitTYP" id="submitTYP" style="display:none">
                      </form>
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
                </div>
                <!-- /.col -->
                
                
                <div class="col-md-9">
                  <div class="box box-warning box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Daftar Kendaraan yang tersedia</h3>
        
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                      <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    
                    <div class="box-body">
                      <div class="form-group"> <!-- Group  -->
                      <?php
					  if($VH_TYPEX == "ALL")
					  {
						  if($VHCount > 0)
						  {
								foreach($vwVH as $rowVH)
								{
									$VH_CODE 		= $rowVH['VH_CODE'];
									$VH_TYPE		= $rowVH['VH_TYPE'];
									$VH_MEREK		= $rowVH['VH_MEREK'];
									$VH_NOPOL		= $rowVH['VH_NOPOL'];
									$VH_COLOR		= $rowVH['VH_COLOR'];
									$VH_CAPACITY	= $rowVH['VH_CAPACITY'];
									$VH_TAX_DATE	= $rowVH['VH_TAX_DATE'];
									$VH_IMAGE		= $rowVH['VH_IMAGE'];
									$VH_STAT		= $rowVH['VH_STAT'];
									
									if($VH_STAT == 1)
									{
										$VH_STAT	= "Booked";
										$color		= "red";
									}
									else
									{
										$VH_STAT 	= "Available";
										$color		= "green";
									}
									
									$addReqVC			= site_url('reservation/addVH/?id='.$this->url_encryption_helper->encode_url($VH_CODE));		// Vehicle Request
					  ?>
                      	<!-- Kendaraan 1 -->
                        <div class="col-xs-4">
                        <div class="box box-default">
                          <div class="box-header with-border">
                            <h3 class="box-title"><?=$VH_MEREK?></h3>
              
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="row">
                              <!-- /.col -->
                              <div class="col-sm-12">
                              <img src="<?php echo base_url().'assets/vehicle_img/'.$VH_IMAGE.'';?>" style="max-width:260px; width:100%; height:200px">
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                          </div>
                          <!-- /.box-body -->
                          <div class="box-footer no-padding">
                            <ul class="nav nav-pills nav-stacked">
                              <li><a href="#" style="cursor:default">Kapasitas
                                <span class="pull-right text-green"><?=$VH_CAPACITY?></span></a></li>
                              <li><a href="#" style="cursor:default">Plat Nomor <span class="pull-right text-green"><?=$VH_NOPOL?></span></a></li>
                              <li><a href="#" style="cursor:default">Status <span class="pull-right text-<?=$color?>"><?=$VH_STAT?></span></a></li>
                              <li>
                              <div class="col-sm-12">
                                <a class="btn btn-block btn-social btn-bitbucket bg-yellow-active" href="<?=$addReqVC?>" style="text-align:center">
                                <i class="fa fa-phone"></i><?=$BookingNow?></a>
                              </div>
                              </li>
                            </ul>
                          </div>
                          <!-- /.footer -->
                        </div>
                        <!-- /.box -->
                      </div>
                      <!-- End -->
                      <?php
							  }
							}
					  }
					  else
					  {
						  $cTYP		= "tbl_vehicle WHERE VH_TYPE = '$VH_TYPEX'";
						  $crTYP	= $this->db->count_all($cTYP);
						  $qTYP		= "SELECT * FROM tbl_vehicle WHERE VH_TYPE = '$VH_TYPEX'";
						  $resTYP	= $this->db->query($qTYP)->result_array();
						  if($crTYP > 0)
						  {
							  foreach($resTYP as $rowTYP)
							  {
								  $VH_CODE 		= $rowTYP['VH_CODE'];
								  $VH_TYPE		= $rowTYP['VH_TYPE'];
								  $VH_MEREK		= $rowTYP['VH_MEREK'];
								  $VH_NOPOL		= $rowTYP['VH_NOPOL'];
								  $VH_COLOR		= $rowTYP['VH_COLOR'];
								  $VH_CAPACITY	= $rowTYP['VH_CAPACITY'];
								  $VH_TAX_DATE	= $rowTYP['VH_TAX_DATE'];
								  $VH_IMAGE		= $rowTYP['VH_IMAGE'];
								  $VH_STAT		= $rowTYP['VH_STAT'];
								  
								  if($VH_STAT == 1)
								  {
									  $VH_STAT	= "Booked";
									  $color		= "red";
								  }
								  else
								  {
									  $VH_STAT 	= "Available";
									  $color		= "green";
								  }
								  
								  $addReqVC			= site_url('reservation/addVH/?id='.$this->url_encryption_helper->encode_url($VH_CODE));		// Vehicle Request
						?>
						  <!-- Kendaraan 1 -->
						  <div class="col-xs-4">
						  <div class="box box-default">
							<div class="box-header with-border">
							  <h3 class="box-title"><?=$VH_MEREK?></h3>
				
							  <div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							  </div>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
							  <div class="row">
								<!-- /.col -->
								<div class="col-sm-12">
								<img src="<?php echo base_url().'assets/vehicle_img/'.$VH_IMAGE.'';?>" style="max-width:260px; width:100%; height:200px">
								</div>
								<!-- /.col -->
							  </div>
							  <!-- /.row -->
							</div>
							<!-- /.box-body -->
							<div class="box-footer no-padding">
							  <ul class="nav nav-pills nav-stacked">
								<li><a href="#" style="cursor:default">Kapasitas
								  <span class="pull-right text-green"><?=$VH_CAPACITY?></span></a></li>
								<li><a href="#" style="cursor:default">Plat Nomor <span class="pull-right text-green"><?=$VH_NOPOL?></span></a></li>
								<li><a href="#" style="cursor:default">Status <span class="pull-right text-<?=$color?>"><?=$VH_STAT?></span></a></li>
								<li>
								<div class="col-sm-12">
								  <a class="btn btn-block btn-social btn-bitbucket bg-yellow-active" href="<?=$addReqVC?>" style="text-align:center">
								  <i class="fa fa-phone"></i><?=$BookingNow?></a>
								</div>
								</li>
							  </ul>
							</div>
							<!-- /.footer -->
						  </div>
						  <!-- /.box -->
						</div>
						<!-- End -->	
                        <?php
							}
						  }
					  }
					  ?>
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
                </div>
                <!-- /.col -->
                </div>
            </div>        
        </div>
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
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js';?>"></script>
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
  
  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
	checkboxClass: 'icheckbox_minimal-blue',
	radioClass   : 'iradio_minimal-blue'
  })
  //Red color scheme for iCheck
  $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
	checkboxClass: 'icheckbox_minimal-red',
	radioClass   : 'iradio_minimal-red'
  })
  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	checkboxClass: 'icheckbox_flat-green',
	radioClass   : 'iradio_flat-green'
  })
  
  function searchTYP(valTYP)
  {
	 	var VH_TYPEX = document.getElementById('VH_TYPE').value;
		document.frmTYP.submitTYP.click();
  }
	
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>