<?php
/*  
  	* Author		  = Dian Hermanto
  	* Create Date	= 26 Oktober 2020
  	* File Name	  = v_s34rchEn9.php
  	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

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
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar'); 
		
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
			if($TranslCode == 'Title')$Title = $LangTransl;
			if($TranslCode == 'Procedure')$Procedure = $LangTransl;
		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			<?php echo $mnName; ?>
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
						            <div class="col-md-9" style="display: none;">
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
	                <?php
	                	$notif	= "";
	                	$notif1 = "";
	                	$numR 	= "";
						if(isset($_POST['kata_kunci']))
						{
							$kata_kunci = $this->db->escape_str(htmlentities(trim($_POST['kata_kunci'])));

							if(strlen($kata_kunci) == 0)
							{
								$notif 	= "<p class='text-red'>Masukan kata kunci</p>";
								$notif1	= "Masukan kata kunci";
								$numR 	= "";
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
								$results = "SELECT *, CONCAT(	IF(ISNULL(HOW_TITLE),' ', HOW_TITLE),
															IF(ISNULL(HOW_CONTENT),' ', HOW_CONTENT)) AS keyword
											FROM tbl_howtouse HAVING $where";

								$resSE 	= $this->db->query($results)->result();
								$num 	= 0;
								foreach($resSE as $therow) :
									$num 		= $num+1;
									$keyword 	= $therow->keyword;		
								endforeach;

								if($num == 0)
								{
									$notif 	= "<p class='text-red'>Pencarian dengan kata kunci <b> $kata_kunci</b> tidak ada hasil.</p></p>";
									$notif1	= "Pencarian dengan kata kunci <b> $kata_kunci</b> tidak ada hasil";
									$numR 	= "";
								}
								else
								{
									$notif 	= "<p class='text-red'>Hasil pencarian dengan kata kunci <b> $kata_kunci</b> sebanyak $num</p></p>";
									$notif1	= "Hasil pencarian dengan kata kunci <b> $kata_kunci</b> sebanyak $num";
									$numR 	= $num;
								}
							}
						}
					?>

				    <div class="row">
				        <div class="col-md-12">
				          	<ul class="timeline">
					            <li class="time-label">
					                  <span class="bg-green">
					                    <?=$notif1?>
					                  </span>
					            </li>
								<?php
									if(isset($_POST['kata_kunci']) && strlen($kata_kunci) != 0)
									{
										$num 				= 0;
										foreach($resSE as $therow) :
											$num 			= $num+1;
											$HOW_ID 		= $therow->HOW_ID;
											$HOW_TITLE 		= $therow->HOW_TITLE;
											$HOW_TITLE		= substr($HOW_TITLE, 0, 30)."";
											$HOW_CONTENT 	= $therow->HOW_CONTENT ?: "-";
											$HOW_OUTHOR		= $therow->HOW_OUTHOR ? "" : "-";
											$HOW_CREATED	= $therow->HOW_CREATED;
											$HOW_PART		= substr($HOW_CONTENT, 0, 255)."... ";
											$DESC 			= "Author : $HOW_OUTHOR. Register : $HOW_CREATED";
											/*echo '
											<p>
												<b><font size="4px">'.$HOW_TITLE.'</font></b><br>
												'.$HOW_PART.'...<br>
												'.$DESC.'...<br>
											</p>
											';*/
											$secUpd			= site_url('s34rchEn9/ids34rchEn9_det/?id='.$this->url_encryption_helper->encode_url($HOW_ID));
											?>
											<li>
								              	<i class="fa fa-num bg-blue"><?=$num?></i>

								              	<div class="timeline-item">
								                	<span class="time"><i class="fa fa-clock-o"></i> <?=$HOW_CREATED?></span>

								                	<h3 class="timeline-header"><a href="#"><?=$HOW_TITLE?></a></h3>

								                	<div class="timeline-body">
									                  	<?=$HOW_PART?>
								                	</div>
								               		<div class="timeline-footer">
								                  		<a href="<?=$secUpd?>" class="btn btn-primary btn-xs">Tampilkan Lebih</a>
								                	</div>
								              	</div>
								            </li>
											<?php
										endforeach;
									}
								?>
				          	</ul>
				        </div>
				    </div>
				</div>
	      	</div>
	    </section>
	</body>
</html>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	//______$this->load->view('template/aside');

	//______$this->load->view('template/js_data');

	//______$this->load->view('template/foot');
?>