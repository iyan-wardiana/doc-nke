<!-- Left side column. contains the sidebar -->
<?php
	$maxLimDf	= 50;
	$maxLimit	= 50;
	$sqlApp 	= "SELECT * FROM tappname";
	$resultaApp = $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
		$appName 	= $therow->app_name;
		$maxLimDf 	= $therow->maxLimDf;
		$maxLimit 	= $therow->maxLimit;		
	endforeach;

	//$this->load->model('menu_model', '', TRUE);
	$appName 		= $this->session->userdata('appName');
	$vers     		= $this->session->userdata['vers'];
	$username 		= $this->session->userdata('username');
	$Emp_ID 		= $this->session->userdata('Emp_ID');
	$LangID			= $this->session->userdata['LangID'];
	$sysMode		= $this->session->userdata['sysMode'];
	$LastModeD		= $this->session->userdata['LastModeD'];
	$sysMnt			= $this->session->userdata['sysMnt'];
	$LastMntD		= $this->session->userdata['LastMntD'];
	$PRJSCATEG		= $this->session->userdata['PRJSCATEG'];

	$tgl1 			= new DateTime($LastMntD);
	$tgl2 			= new DateTime();
	$dif1 			= $tgl1->diff($tgl2);
	$dif2 			= $dif1->days;

	$cLogV			= "tbl_emp_vers WHERE EMP_ID = '$Emp_ID' AND VERS = '$vers'";
	$vLogV			= $this->db->count_all($cLogV);

	$isEnd 			= 0;
	if($sysMnt == 1 && $tgl2 >= $tgl1)
	{
		$isEnd 		= 2;
	}
	elseif($sysMnt == 1 && $dif2 < 6) 
	{
		$isEnd 		= 1;
	}

	$lasTDMnt		= date('d M Y', strtotime($LastMntD));
	$mntWarn1		= "";
	$mntWarn2		= "";
	$sqlTransl			= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl			= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode		= $rowTransl->MLANG_CODE;
		$LangTransl		= $rowTransl->LangTransl;	
		if($TranslCode == 'systemMode')$systemMode = $LangTransl;
		if($TranslCode == 'descMode')$descMode = $LangTransl;
		if($TranslCode == 'Expire')$Expire = $LangTransl;
		if($TranslCode == 'Warning')$Warning = $LangTransl;
	endforeach;
	$tgl1 = new DateTime($LastMntD);
	$tgl2 = new DateTime();
 
	$dif1 = $tgl1->diff($tgl2);
	$dif2 = $dif1->days;

	$imgemp_filename 	= '';
	$imgemp_filenameX	= '';
	$sqlGetIMG			= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
	$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
	foreach($resGetIMG as $rowGIMG) :
		$imgemp_filename 	= $rowGIMG ->imgemp_filename;
		$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
	endforeach;

	$completeName		= $this->session->userdata('completeName');

	$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
	if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
	{
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
	}
	if (!isset($url_search))
	{
		$url_search	= '';
	}
	$imgWarn			= base_url('assets/AdminLTE-2.0.5/dist/img/1stweb/1stweb.png');
	$secMsgNtf			= site_url('c_msg_ntf/c_msg_ntf/?id='.$this->url_encryption_helper->encode_url($appName));

	if (!isset($MenuCode))
	{
		$MenuCode	= '';
	}

	$getparent    = $this->db->get_where('tbl_menu', array('menu_code' => $MenuCode));


	if($getparent->num_rows()>0)
	{
		foreach($getparent->result() as $r):
			$parent_code  = $r->parent_code;
			$level_menu   = $r->level_menu;
		endforeach;

		if($level_menu == 3){
		  //get Level 3
		  $getparent1   = $this->db->get_where('tbl_menu', array('menu_code' => $parent_code));
		  if($getparent1->num_rows()>0)
		  {
		  		foreach($getparent1->result() as $r1):
					$parent1_code  = $r1->parent_code;
				endforeach;	
		  }
		  else
		  {
		  		$parent1_code = '';	
		  }
		  
		  //Menu Active
		  $Lev1Menu = $parent1_code;
		  $Lev2Menu = $parent_code;
		  $Lev3Menu = $MenuCode;
		}elseif($level_menu == 2){
		  //Menu Active
		  $Lev1Menu = $parent_code;
		  $Lev2Menu = $MenuCode;
		  $Lev3Menu = '';
		}elseif($level_menu == 1){
		  //Menu Active
		  $Lev1Menu = $MenuCode;
		  $Lev2Menu = '';
		  $Lev3Menu = '';
		}	
	}
	else
	{
		$Lev1Menu = '';
		$Lev2Menu = '';
		$Lev3Menu = '';
	}
		$appBody    = $this->session->userdata['appBody'];
?>

<body class="hold-transition skin-blue sidebar-mini fixed" onLoad="App.handleFullScreen()">
	<div class="wrapper">
		<aside class="main-sidebar">
		    <section class="sidebar">
				<!-- Sidebar user panel -->
		        <?php if($sysMode == 1) { ?>
		        <div class="alert alert-warning alert-dismissible">
		            <h4><i class="icon fa fa-warning"></i> <?php echo $systemMode; ?></h4>
		            <?php echo "$descMode $Expire : ".date('d-M-Y', strtotime($LastModeD)); ?>
		        </div>
		        <?php } ?>
		        <div class="user-panel">
		            <div class="pull-left image">
		                <img src="<?php echo $imgLoc; ?>" class="img-circle" style="max-height:55px" alt="User Image" />
		            </div>
		            <div class="pull-left info">
		                <p><?php echo $this->session->userdata('completeName'); ?></p>

		                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
		            </div>
		        </div>
		        <?php
					$appName 	= $this->session->userdata('appName');
					$collData	= "$Emp_ID~$username~$completeName~$appName";
				?>

		        <!-- search form -->
		        <form action="<?php echo $url_search; ?>" method="post" class="sidebar-form" onsubmit="return checkData();" style="display: none;">
		            <div class="input-group">
		                <input type="text" name="gEn5rcH" class="form-control" placeholder="Search..." />
		                <input type="hidden" name="maxLimDf" class="form-control" placeholder="Search..." value="<?php echo $maxLimDf; ?>" />
		                <input type="hidden" name="maxLimit" class="form-control" placeholder="Search..." value="<?php echo $maxLimit; ?>" />
		                <span class="input-group-btn">
		                    <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
		                </span>
		            </div>
		        </form>
		        <!-- /.search form -->
		        <!-- sidebar menu: : style can be found in sidebar.less -->
		        <?php
					$urlDash1	= site_url('__180c2f/?id='.$this->url_encryption_helper->encode_url($appName));
					//$urlLock	= site_url('__l1y/lockSystem/?id='.$collData);
					$urlLock	= site_url('__l1y/lockSystem/?id='.$this->url_encryption_helper->encode_url($collData));
					$howtouse	= site_url('howtouse/?id='.$this->url_encryption_helper->encode_url($appName));
					$reservation= site_url('reservation/?id='.$this->url_encryption_helper->encode_url($appName));

					// NEW MENU
					$urlAbs		= site_url('c_tsemp/c_tsemp/?id='.$this->url_encryption_helper->encode_url($appName));
					$urlAbsDR	= site_url('c_tsemp/c_tsemp/d41lYr3p?id='.$this->url_encryption_helper->encode_url($appName));
					$urlAssesm	= site_url('c_a553sm/c_a553sm/?id='.$this->url_encryption_helper->encode_url($appName));
					$urlmnotes	= site_url('c_mnotes/c_mnotes/?id='.$this->url_encryption_helper->encode_url($appName));
					$urlreport	= site_url('c_report/c_report_emp/?id='.$this->url_encryption_helper->encode_url($appName));


					if($LangID == 'IND')
					{
						$Text1		= "NAVIGASI UTAMA";
						$dashboardT	= "Beranda";
						$LockScr	= "Kunci Layar";
						$HowToUse	= "Cara Penggunaan";
						$Reservat	= "Pemesanan";
						$mntWarn1	= "Layanan '1stWeb Assistance' akan segera berakhir pada tanggal : ";
						$mntWarn2	= "Silahkan hubungi kami agar tetap mendapatkan layanan '1stWeb Assistance'.";
						$mntWarn3	= "Layanan '1stWeb Assistance' sudah berakhir per ";
						$mntWarn4	= "Mengapa saya melihat ini?";

						if($isEnd == 1)
						{
							$mntWarna	= "Perawatan Sistem";
							$mntWarnb	= "Akan berakhir pada $lasTDMnt.";
						}
						else if($isEnd == 2)
						{
							$mntWarna	= "Perawatan Sistem";
							$mntWarnb	= "sudah berkahir pada $lasTDMnt.";
						}
					}
					else
					{
						$Text1		= "MAIN NAVIGATION";
						$dashboardT	= "Dashboard";
						$HowToUse	= "How To Use";
						$LockScr	= "Lock Screen";
						$Reservat	= "Reservation";
						$mntWarn1	= "Sorry, '1stWeb Assistance' services will be finished on : ";
						$mntWarn2	= "Please contact us to get '1stWeb Assistance' services.";
						$mntWarn3	= "Sorry, we have finished '1stWeb Assistance' services per ";
						$mntWarn4	= "Why did I see this message?";

						if($isEnd == 1)
						{
							$mntWarna	= "System Maintenance";
							$mntWarnb	= "will expire on $lasTDMnt.";
						}
						else if($isEnd == 2)
						{
							$mntWarna	= "System Maintenance";
							$mntWarnb	= "has expired on $lasTDMnt.";
						}
					}

					if($MenuCode == '')
						$MenuCode == 'MN500';

				?>

				<input type="hidden" name="isEnd" id="isEnd" value="<?php echo $isEnd; ?>">

				<ul class="sidebar-menu" data-widget="tree">
		        </ul>
		    </section>
		</aside>

	    <div class="content-wrapper" id="content-wrapper" style="min-height: 421px;">
	        <div class="content-tabs">
	            <button class="roll-nav roll-left fullscreen" onclick="App.handleFullScreen()"><i class="fa fa-arrows-alt"></i></button>
	            <nav class="page-tabs menuTabs tab-ui-menu" id="tab-menu">
	                <div class="page-tabs-content" style="margin-left: 0px;"></div>
	            </nav>
	            <button class="roll-nav roll-right fullscreen" onclick="App.handleFullScreen()"><i class="fa fa-arrows-alt"></i></button>
	        </div>
	        <div class="content-iframe " style="background-color: #ffffff; ">
	            <div class="tab-content " id="tab-content"></div>
	        </div>
	    </div>
	</div>
</body>
<?php
	$baseURL	= base_url();
	$basePath	= site_url();
	$globImg	= site_url('assets/AdminLTE-2.0.5/dist/img/');

	$urlBlank	= site_url('__180c2f/dahsBoard/?id='.$this->url_encryption_helper->encode_url($appName));
	$urlGL		= site_url('c_gl/c_ch1h0fbeart/id1h0fbx1');
?>
<script type="text/javascript">
	$(function () {
		// console.log(window.location);

        App.setbasePath("<?php echo $baseURL; ?>");
        App.setGlobalImgPath("<?php echo $globImg; ?>");

        addTabs({
            id: '10000',
            title: 'Beranda',
            close: false,
            url: "",
            targetType: "iframe-tab",
            icon: "fa fa-circle-o",
            urlType: 'abosulte'
        });

        App.fixIframeCotent();

        var menus = [
            {
                id: "9000",
                text: "NAVIGASI UTAMA",
                icon: "",
                isHeader: true
            },
			{
	            id: '9001',
	            text: 'Beranda 1',
	            url: "",
	            targetType: "iframe-tab",
	            icon: "fa fa-circle-o",
	            urlType: 'abosulte',
                children:
                [
                	{
			            id: '9001',
			            text: 'Beranda 1',
			            url: "",
			            targetType: "iframe-tab",
			            icon: "fa fa-circle-o",
			            urlType: 'abosulte',
		            },
                ]
            },
       	];
        $('.sidebar-menu').sidebarMenu({data: menus});
    });
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".preloader").fadeOut();
		var isEnd = $('#isEnd').val();

		var isEnd = $('#isEnd').val();
		if(isEnd == 1)
		{
			mntAlert1();
		}
		else if(isEnd == 2)
		{
			mntAlert1();
		}
	});

	function mntAlert1()
	{
		title 	= "<?php echo $mntWarna; ?>";
		message = "<?php echo $mntWarnb; ?>";

		window.createNotification
		({
			closeOnClick: false,
			displayCloseButton: false,
			positionClass: 'nfc-bottom-right',
			showDuration: 6000000, // 1K = 1s
			theme: 'error' // success, warning, info, error, None
		})
		({
			title: title,
			message: message
		});
	}
</script>
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
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>