<?php
/*  
  	* Author		  = Dian Hermanto
  	* Create Date	= 26 Oktober 2020
  	* File Name	  = v_s34rchEn9.php
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
		<section class="content-header">
			<h1>
			<?php echo $h2_title; ?>
			<small><?php echo $h3_title; ?></small>
			</h1>
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>

  		<section class="content">
	      	<div class="row">
                <form action="" method="post">
		      		<div class="col-md-12">
		      			<div class="box box-primary">
				            <div class="box-header with-border">
				              	<h3 class="box-title">Pencarian Cepat</h3>
				            </div>
				            <div class="box-body">
					            <div class="row">
						            <div class="col-md-3">
						                <div class="input-group text-center">
						                  	<input type="text" name="kata_kunci" id="kata_kunci" placeholder="Masukkan kata kunci" class="form-control" />
						                    <span class="input-group-btn">
						                        <button type="submit" class="btn btn-primary btn-flat">Cari</button>
						                    </span>
						                </div>
						            </div>
						            <div class="col-md-9">
						                <div class="input-group text-right">
						                    <span class="input-group-btn">
						                        	<button type="button" class="btn bg-olive btn-flat">input</button>
						                        &nbsp;&nbsp;&nbsp;&nbsp;
						                        	<button type="button" class="btn bg-navy btn-flat">input</button>
						                        </p>
						                    </span>
						                </div>
						            </div>
					            </div>
				            </div>
				        	<br>
				        </div>
  					</div>
	            </form>
	      		<div class="col-md-12">
	      			<div class="box box-warning">
			            <div class="box-header with-border">
			              	<h3 class="box-title">Hasil Pencarian Cepat</h3>
			            </div>
			            <div class="box-body">
				            <div class="row">
					            <div class="col-md-3">
					                <?php
										if(isset($_POST['kata_kunci']))
										{
											$kata_kunci = $this->db->escape_str(htmlentities(trim($_POST['kata_kunci'])));

											if(strlen($kata_kunci) == 0)
											{
												echo '<p class="text-red">Masukan kata kunci</p>';
											}
											else
											{
												$where = "";
												
												//membuat variabel $kata_kunci_split untuk memecah kata kunci setiap ada spasi
												$kata_kunci_split = preg_split('/[\s]+/', $kata_kunci);
												$total_kata_kunci = count($kata_kunci_split);
												
												foreach($kata_kunci_split as $key=>$kunci){
													$where .= "keyword LIKE '%$kunci%'";
													if($key != ($total_kata_kunci - 1)){
														$where .= " OR ";
													}
												}

												//$results = $koneksi->query("SELECT judul, LEFT(deskripsi, 60) as deskripsi, url FROM artikel WHERE $where");
												$results = "SELECT *, CONCAT(	IF(ISNULL(COMP_CODE),' ', COMP_CODE),
																			IF(ISNULL(COMP_NAME),' ', COMP_NAME),
																			IF(ISNULL(COMP_ADD),' ', COMP_ADD),
																			IF(ISNULL(COMP_STATE),' ', COMP_STATE),
																			IF(ISNULL(COMP_CITY),' ', COMP_CITY),
																			IF(ISNULL(COMP_MAIL),' ', COMP_MAIL),
																			IF(ISNULL(COMP_CP),' ', COMP_CP),
																			IF(ISNULL(COMP_PHONE),' ', COMP_PHONE),
																			IF(ISNULL(COMP_NEWS1),' ', COMP_NEWS1),
																			IF(ISNULL(COMP_NEWS2),' ', COMP_NEWS2),
																			IF(ISNULL(COMP_NEWS3),' ', COMP_NEWS3)) AS keyword
															FROM tbl_company HAVING $where";

												$resSE 	= $this->db->query($results)->result();
												$num 	= 0;
												foreach($resSE as $therow) :
													$num 	= $num+1;
													$keyword = $therow->keyword;		
												endforeach;
												echo "keyword = $keyword";

												if($num == 0)
												{
													echo '<p class="text-red">Pencarian dengan kata kunci <b>'.$kata_kunci.'</b> tidak ada hasil.</p></p>';
												}
												else
												{
													echo '<p>Pencarian dari kata kunci <b>'.$kata_kunci.'</b> mendapatkan '.$num.' hasil:</p>';
													foreach($resSE as $therow) :
														$num 	= $num+1;
														$keyword = $therow->keyword;
														echo '
														<p>
															<b>'.$therow->COMP_NAME.'</b><br>
															'.$therow->COMP_NAME.'...<br>
															<a href="'.$therow->COMP_NAME.'">'.$therow->COMP_NAME.'</a>
														</p>
														';	
													endforeach;
												}
											}
										}
									?>
					            </div>
				            </div>
			            </div>
			            
			        	<br>
			        </div>
					</div>
	      	</div>
	    </section>
	</body>
</html>
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