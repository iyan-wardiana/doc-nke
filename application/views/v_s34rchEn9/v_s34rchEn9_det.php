<?php
/*  
  	* Author		= Dian Hermanto
  	* Create Date	= 06 Maret 2022
  	* File Name	  	= v_s34rchEn9_det.php
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

$results = "SELECT *, CONCAT(	IF(ISNULL(HOW_TITLE),' ', HOW_TITLE), IF(ISNULL(HOW_CONTENT),' ', HOW_CONTENT)) AS keyword
			FROM tbl_howtouse WHERE HOW_ID = $HOW_ID";
$resSE 	= $this->db->query($results)->result();

foreach($resSE as $therow) :
	$HOW_ID 		= $therow->HOW_ID;
	$HOW_TITLE 		= $therow->HOW_TITLE;
	$HOW_CONTENT 	= $therow->HOW_CONTENT ?: "-";
	$HOW_OUTHOR		= $therow->HOW_OUTHOR ? "" : "-";
	$HOW_IMG1 		= $therow->HOW_IMG1;
	if($HOW_IMG1 == '')
		$HOW_IMG1 	= "https://via.placeholder.com/150x100";
	$HOW_IMG2 		= $therow->HOW_IMG2;
	if($HOW_IMG2 == '')
		$HOW_IMG2 	= "https://via.placeholder.com/150x100";
	$HOW_IMG3 		= $therow->HOW_IMG3;
	if($HOW_IMG3 == '')
		$HOW_IMG3 	= "https://via.placeholder.com/150x100";
	$HOW_IMG4 		= $therow->HOW_IMG4;
	$HOW_IMG5 		= $therow->HOW_IMG5;
	$HOW_IMG6 		= $therow->HOW_IMG6;
	$HOW_IMG7 		= $therow->HOW_IMG7;
	$HOW_IMG8 		= $therow->HOW_IMG8;
	$HOW_IMG9 		= $therow->HOW_IMG9;
	$HOW_IMG10 		= $therow->HOW_IMG10;
	$HOW_VIDEO 		= $therow->HOW_VIDEO;
	$HOW_CREATED	= $therow->HOW_CREATED;
endforeach;
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
		        <div class="col-md-12">
		          	<ul class="timeline">
			            <li class="time-label">
			                  <span class="bg-green">
			                    <?=$HOW_TITLE?>
			                  </span>
			            </li>
						<li>
			              	<i class="fa fa-comments bg-yellow"></i>

			              	<div class="timeline-item">
			                	<span class="time"><i class="fa fa-clock-o"></i> <?=$HOW_CREATED?></span>

			                	<h3 class="timeline-header"><a href="#"><?=$HOW_TITLE?></a></h3>

			                	<div class="timeline-body">
				                  	<?=$HOW_CONTENT?>
			                	</div>
			              	</div>
			            </li>
			            <li>
			              	<i class="fa fa-camera bg-purple"></i>

			              	<div class="timeline-item">
			                	<div class="timeline-body">
			                  		<img src="<?=$HOW_IMG1?>" alt="..." class="margin">
			                  		<img src="<?=$HOW_IMG2?>" alt="..." class="margin">
			                  		<img src="<?=$HOW_IMG3?>" alt="..." class="margin">
			                	</div>
			              	</div>
			            </li>
			            <?php
			            	if($HOW_VIDEO == '')
			            		$secVid		= base_url() . 'assets/AdminLTE-2.0.5/dist/img/no_video.jpg';
			            	else
			            		$secVid		= base_url() . ''.$HOW_VIDEO.'';
			            ?>
			            <li>
			              	<i class="fa fa-video-camera bg-maroon"></i>

			              	<div class="timeline-item">
			                	<div class="timeline-body">
			                  		<div class="embed-responsive embed-responsive-16by9">
			                    	<iframe class="embed-responsive-item" src="<?php echo $secVid; ?>"
			                            frameborder="0" allowfullscreen></iframe>
			                  		</div>
			                	</div>
			              	</div>
			            </li>
			            <li>
			              	<i class="fa fa-clock-o bg-gray"></i>
			            </li>
		          	</ul>
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