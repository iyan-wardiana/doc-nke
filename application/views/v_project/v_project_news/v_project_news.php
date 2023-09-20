<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 23 Maret 2017
	* File Name	= vendor_category.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

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

$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/'.$PRJ_IMGNAME);
if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
{
	$imgLoc	= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
}
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
            if($TranslCode == 'Name')$Name = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'latestnews')$latestnews = $LangTransl;
			if($TranslCode == 'News')$News = $LangTransl;
			if($TranslCode == 'Picture')$Picture = $LangTransl;
			if($TranslCode == 'Sender')$Sender = $LangTransl;
			if($TranslCode == 'News')$News = $LangTransl;
        endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
      	<section class="content-header">
          	<h1>
              	<?php echo $mnName; ?> 
            </h1>
      	</section>

      	<style>
          	.search-table, td, th {
          	border-collapse: collapse;
          	}
          	.search-table-outter { overflow-x: scroll; }
      	</style>
    	
    	<?php
			$s_1 	= "tbl_project_liveinfo WHERE prjcode = '$PRJCODE'";
			$r_1 	= $this->db->count_all($s_1);

			$s_2 	= "tbl_project_liveinfopic WHERE prjcode = '$PRJCODE'";
			$r_2 	= $this->db->count_all($s_2);

			$TOTEMP = 0;
			$s_3 	= "SELECT COUNT(DISTINCT emp_id) AS TOT_EMP FROM tbl_project_liveinfo WHERE prjcode = '$PRJCODE' GROUP BY emp_id";
			$r_3 	= $this->db->query($s_3)->result();
			foreach($r_3 as $rw_3):
				$TOTEMP 	= $rw_3->TOT_EMP;
			endforeach;
    	?>
    	<section class="content">
			<div class="row">
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-body box-profile">
							<img class="profile-user-img img-responsive img-circle" src="<?=$imgLoc?>" alt="User profile picture">

							<h3 class="profile-username text-center"><?=$PRJNAME?></h3>

							<p class="text-muted text-center"><?=$PRJLOCT?></p>

							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<b><?=$News?></b> <a class="pull-right"><?php echo number_format($r_1,0); ?></a>
								</li>
								<li class="list-group-item">
									<b><?=$Picture?></b> <a class="pull-right"><?php echo number_format($r_2,0); ?></a>
								</li>
								<li class="list-group-item">
									<b><?=$Sender?></b> <a class="pull-right"><?php echo number_format($TOTEMP,0); ?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="col-md-9">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#activity" data-toggle="tab"><?=$latestnews?></a></li>
						</ul>
						<div class="tab-content">
							<div id="row">
								<form method="post" id="image-form" enctype="multipart/form-data" onSubmit="return false;">
									<div class="form-group margin-bottom-none">
										<div class="col-sm-7">
											<input type="hidden" name="prjcode" id="prjcode" class="form-control input-sm" value="<?=$PRJCODE?>">
											<input type="hidden" name="empid" id="empid" class="form-control input-sm" value="<?=$DefEmp_ID?>">
											<input type="text" name="emp_msg" id="emp_msg" class="form-control input-sm" placeholder="<?=$latestnews?>">
										</div>
										<div class="col-sm-3">
											<input type="file" name="file_input[]" id="file_input[]" multiple="multiple"/>
										</div>
										<div class="col-sm-2">
											<button type="submit" class="btn btn-danger pull-right btn-block btn-sm">Send</button>
										</div>
									</div>
								</form>
								<script type="text/javascript">
									/*$(function(){
										$("input[type='submit']").click(function(){
											var $fileUpload = $("input[type='file']");
											if (parseInt($fileUpload.get(0).files.length)>2){
											alert("You can only upload a maximum of 2 files");
											}
										});    
									});​*/

									$(document).ready(function(e) {
										$("#image-form").on("submit", function() {
											var $fileUpload = $("input[type='file']");
											if (parseInt($fileUpload.get(0).files.length)>5)
											{
												swal('Maksimal gambar yang dapat diupload : 5 file(s)',
												{
													icon: "warning",
												})
												.then(function()
												{
													//document.getElementById('CB_DPAMOUNTX').focus();
													//document.getElementById('CB_DPAMOUNTX').value = '0.00';
												});
												return false;
											}

											$.ajax({
												type: "POST",
												url: "<?php echo site_url('c_project/c_projnews/svForm')?>",
												data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
												contentType: false, // The content type used when sending data to the server.
												cache: false, // To unable request pages to be cached
												processData: false, // To send DOMDocument or non processed data file it is set to false
												success: function(data) {
													document.getElementById('emp_msg').value = '';
													document.getElementById('divH').innerHTML = data;
												}
											});
										});
									});
								</script>
							</div>
							<br>
							<div class="post">
							</div>
							<div class="post" id="divH">
							</div>
							<?php
								$s_pinfo		= "SELECT A.*, B.imgemp_filenameX AS file_nm FROM tbl_project_liveinfo A
														LEFT JOIN tbl_employee_img B ON A.emp_id = B.imgemp_empid
													WHERE A.prjcode = '$PRJCODE' ORDER BY A.created DESC";
								$r_pinfo		= $this->db->query($s_pinfo)->result();
								foreach($r_pinfo as $rw_pinfo) :
									$info_code	= $rw_pinfo->info_code;
									$emp_id		= $rw_pinfo->emp_id;
									$emp_name	= $rw_pinfo->emp_name;
									$emp_msg	= $rw_pinfo->emp_msg;
									$islast		= $rw_pinfo->islast;
									$created	= $rw_pinfo->created;
									$file_nm	= $rw_pinfo->file_nm;

									$dateIND1 	= new DateTime($created);
									$dateIND 	= strftime('%A', $dateIND1->getTimestamp());

									$crtDV		= strftime('%d %B %y', strtotime($created));
									$crtTV		= date('H:i', strtotime($created));

									$s_img		= "SELECT Pos_Code FROM tbl_employee WHERE Emp_ID = '$emp_id'";
									$r_img		= $this->db->query($s_img)->result();
									foreach($r_img as $rw_img) :
										$Pos_Code 	= $rw_img->Pos_Code;
									endforeach;

									$POSNM  	= "-";
									$sqlDEP 	= "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$Pos_Code'";
									$resDEP 	= $this->db->query($sqlDEP)->result();
									foreach($resDEP as $rowDEP) :
										$POSNM 	= $rowDEP->POSS_NAME;
									endforeach;

									$imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$emp_id.'/'.$file_nm);
									if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$emp_id))
									{
										$imgLoc	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
									}
									?>
									<div class="post">
										<div class="user-block">
											<img class="img-circle img-bordered-sm" src="<?=$imgLoc?>" alt="user image">
											<span class="username">
												<a href="#"><?=$emp_name?></a>
												<!-- <a href="#" class="pull-right btn-box-tool"><i class="fa fa-clock-o"></i></a> -->
											</span>
											<span class="description"><?=$POSNM?> - <?=$crtTV?><div class="pull-right"><?=$dateIND.", ".$crtDV?></div></span>
										</div>
										<p>
											<?=$emp_msg?>
										</p>
										<div class="row margin-bottom">
											<?php
												$s_imgPC	= "tbl_project_liveinfopic WHERE info_code = '$info_code' AND prjcode = '$PRJCODE'";
												$r_imgPC	= $this->db->count_all($s_imgPC);

												if($r_imgPC > 0)
												{
													$i 			= 0;
													$s_imgP		= "SELECT picture_name FROM tbl_project_liveinfopic
																	WHERE info_code = '$info_code' AND prjcode = '$PRJCODE'";
													$r_imgP		= $this->db->query($s_imgP)->result();
													foreach($r_imgP as $rw_imgP) :
														$i 		= $i+1;

														$picInf = $rw_imgP->picture_name;
														$imgP	= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/prjlivinfo/'.$picInf);
														if($i==1) { ?>
															<div class="col-sm-6">
										                      	<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
										                    </div>
														<?php } ?>
														<?php if($i == 2) { ?>
										                    <div class="col-sm-6">
																<div class="row">
																	<div class="col-sm-6">
																		<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																		<?php } if($i == 3) { ?>
																		<br>
																		<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																	</div>
																	<?php } if($i == 4) { ?>
																		<div class="col-sm-6">
																			<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																			<?php } if($i == 5) { ?>
																				<br>
																				<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																		</div>
																</div>
															</div>
														<?php }
													endforeach;
												}
											?>
										</div>
									</div>
									<?php
								endforeach;
							?>
						</div>
					</div>
				</div>
			</div>

        	<?php
        		$DefID 		= $this->session->userdata['Emp_ID'];
				$act_lnk 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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