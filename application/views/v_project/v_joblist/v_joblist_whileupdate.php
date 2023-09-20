<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 7 November 2020
	* File Name		= v_joblist.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$Emp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata['appBody'];

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
        
	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
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
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
		if($ISDELETE == 1)
		{
			$ISREAD 	= 1;
			$ISCREATE 	= 1;
			$ISAPPROVE 	= 1;
		}
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Class')$Class = $LangTransl;
			if($TranslCode == 'Group')$Group = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Qty')$Qty = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Upload')$Upload = $LangTransl;
			if($TranslCode == 'sureReset')$sureReset = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$sureDelete	= "Anda yakin akan menghapus data ini?";
			$sureDelJob	= "Anda yakin akan menghapus pekerjaan ini?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'Sistem akan mengatur ulang urutan BoQ secara otomatis. Yakin?';
		}
		else
		{
			$sureDelete	= "Are your sure want to delete?";
			$sureDelJob	= "Are your sure want to delete this Job?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'System will reset BoQ Order ID automatically. Are you sure?';
		}

		$PRJCODE		= $PRJCODE;
		$sql 			= "SELECT PRJNAME FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJNAME 	= $row ->PRJNAME;
		endforeach;
	?>
			
	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
    
    <?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
				<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wbs.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
				<?php echo $mnName; ?>
				<small><?php echo "$PRJNAME"; ?> </small>
			</h1>
		</section>

        <section class="content">
			<div class="row">
				<?php
					$s_00 	= "SELECT SUM(JOBCOST) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_BOQ FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";
					$r_00 	= $this->db->query($s_00)->result();
					foreach($r_00 as $rw_00):
						$TOT_RAP 	= $rw_00->TOT_RAP;
						$TOT_BOQ 	= $rw_00->TOT_BOQ;
					endforeach;
					$TOT_RAPP 		= $TOT_RAP;
					if($TOT_RAP == '' || $TOT_RAP == 0)
					{
						$TOT_RAP 	= 0;
						$TOT_RAPP 	= 0;
					}

					$TOT_BOQP 	= $TOT_BOQ;
					if($TOT_BOQ == '' || $TOT_BOQ == 0)
					{
						$TOT_BOQ 	= 0;
						$TOT_BOQP 	= 1;
					}

					$DEV_RAPBOQ 	= $TOT_BOQ - $TOT_RAP;
					$DEV_RAPBOQPERC = $DEV_RAPBOQ / $TOT_BOQP * 100;

					if($DEV_RAPBOQ > 0)
						$DEVDESC 	= "< ".number_format(abs($DEV_RAPBOQPERC), 2)."% dari BoQ";
					else
						$DEVDESC 	= "> ".number_format(abs($DEV_RAPBOQPERC), 2)."% dari BoQ";

					$s_01 	= "SELECT SUM(REQ_AMOUNT) AS TOT_REQAMN, SUM(ITM_USED_AM) AS TOT_USEAMN FROM tbl_joblist_detail
								WHERE PRJCODE = '$PRJCODE' AND ISLAST = 1";
					$r_01 	= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$TOT_REQAMN = $rw_01->TOT_REQAMN;
						$TOT_USEAMN = $rw_01->TOT_USEAMN;
					endforeach;
					if($TOT_REQAMN == '')
						$TOT_REQAMN = 0;
					if($TOT_USEAMN == '')
						$TOT_USEAMN = 0;

					$TOT_RAPB		= $TOT_RAP;
					if($TOT_RAP == 0)
						$TOT_RAPB 	= 1;

					$TOT_REQAMNPERC = $TOT_REQAMN / $TOT_RAPB * 100;

					$TOT_RAPPB		= $TOT_RAPP;
					if($TOT_RAPP == 0)
						$TOT_RAPPB 	= 1;

					$REM_BUD 		= $TOT_RAP - $TOT_REQAMN;
					$REM_BUDPERC 	= $REM_BUD / $TOT_RAPPB * 100;
				?>
		        <div class="col-md-3">
		          	<div class="info-box bg-blue">
		            	<span class="info-box-icon"><i class="ion ion-clipboard"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">Bill of Quantity (BoQ)</span>
			              	<span class="info-box-number"><?=number_format($TOT_BOQ,2)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: 100%"></div>
			              	</div>
			                <span class="progress-description">
			               		<?=number_format($DEV_RAPBOQ,2)?> dari RAP
			                </span>
			            </div>
			        </div>
			    </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-green">
			            <span class="info-box-icon"><i class="ion ion-calculator"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">RAP</span>
			              	<span class="info-box-number"><?=number_format($TOT_RAP,2)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: 100%"></div>
			              	</div>
			                <span class="progress-description">
			               		<?=$DEVDESC?>
			                </span>
			            </div>
		         	</div>
		        </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-yellow">
			            <span class="info-box-icon"><i class="ion ion-connection-bars"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">Digunakan</span>
			              	<span class="info-box-number"><?=number_format($TOT_REQAMN,2)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$TOT_REQAMNPERC?>%"></div>
			              	</div>
			                <span class="progress-description">
			                	<?=number_format($TOT_REQAMNPERC,2)?> % dari total RAP
			                </span>
			            </div>
		          	</div>
		        </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-aqua">
		            	<span class="info-box-icon"><i class="ion-information-circled"></i></span>

		            	<div class="info-box-content">
		              		<span class="info-box-text">Sisa Anggaran</span>
		              		<span class="info-box-number"><?=number_format($REM_BUD,2)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$REM_BUDPERC?>%"></div>
			              	</div>
			              	<span class="progress-description">
			                	<?=number_format($REM_BUDPERC,2)?>% dari RAP
			              	</span>
			           	</div>
		            </div>
		      	</div>
		   	</div>

			<div class="row">
				<div class="col-md-12" id="idprogbar" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
		  	<div class="box">
				<div class="box-body">
					<div class="search-table-outter">
						<!-- <table id="tree-table" class="table table-hover table-bordered"> -->
						<table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					        <thead>
					            <tr>
					                <th width="2%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					                <th width="36%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description; ?></th>
					                <th width="2%" style="text-align:center; vertical-align:middle" nowrap>Lev.</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Sat.</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Volume; ?></th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?> (BoQ)</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?> (RAP)</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>Deviasi<br>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>Add. (+)</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Used; ?></th>
					          </tr>
					        </thead>
							<tbody>
							</tbody>
							<tfoot>
								<?php
									$TOT_VOLBG	= 0;
									$TOT_BOQBG 	= 0;
									$TOT_RAPBG 	= 0;
									$TOT_ADDBG	= 0;
									$TOT_USEBG	= 0;
									$sqlTBUDG	= "SELECT SUM(ITM_VOLM * ITM_PRICE) AS TOT_VOLBG, SUM(BOQ_JOBCOST) AS TOT_BOQBUBUD,
														SUM(ITM_BUDG) AS TOT_RAPBUBUD,
														SUM(ADD_VOLM * ADD_PRICE) AS TOT_ADDBG,
														SUM(ITM_USED_AM) AS TOT_USEBG
													FROM tbl_joblist_detail WHERE IS_LEVEL = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_VOLBG	= $rowTBUDG->TOT_VOLBG;
										$TOT_BOQBG 	= $rowTBUDG->TOT_BOQBUBUD;
									endforeach;
									$TOT_DEVBG	= $TOT_BOQBG - $TOT_VOLBG;

									$sqlTBUDG	= "SELECT SUM(ADD_VOLM * ADD_PRICE) AS TOT_ADDBG,
														SUM(ITM_USED_AM) AS TOT_USEBG
													FROM tbl_joblist_detail WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_ADDBG 	= $rowTBUDG->TOT_ADDBG;
										$TOT_USEBG 	= $rowTBUDG->TOT_USEBG;
									endforeach;
								?>
					            <tr>
					                <th colspan="5" style="text-align:center; vertical-align:middle" nowrap>T O T A L</th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_BOQBG, 2)?></th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_BOQBG, 2)?></th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_DEVBG, 2)?></th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_ADDBG, 2)?></th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_USEBG, 2)?></th>
					          	</tr>
			                </tfoot>
						</table>
					</div>
					<br>
                    <?php
                        echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>&nbsp;&nbsp;');
                        if($DefEmp_ID == 'D15040004221' || $DefEmp_ID == 'E92090000071')
                        	echo '<button class="btn btn-info" onClick="reCount()" title="Recount BoQ"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;';
                    ?>
				</div>
				<div id="loading_1" class="overlay" style="display:none">
		            <i class="fa fa-refresh fa-spin"></i>
		        </div>
			</div>

			<div class="row">
				<div class="col-md-12" id="idprogbarXY" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXY" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="display: none;"></iframe>
		
		<!-- <script src="http://code.jquery.com/jquery-1.12.4.min.js"></script> 
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/js_tree/javascript.js'; ?>"></script>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-36251023-1']);
			_gaq.push(['_setDomainName', 'jqueryscript.net']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script> -->
	</body> 
</html>

<script>
    $(document).ready(function()
    {
	    $('#example').DataTable( {
	    	"bDestroy": true,
	    	"bSort" : false,
	        "processing": true,
	        "serverSide": true,
	        //"scrollX": false,
	        "autoWidth": true,
	        "filter": true,
	        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataJL/?id='.$PRJCODE)?>",
	        "type": "POST",
	        "lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
	        //"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
	        "columnDefs": [ { targets: [5], className: 'dt-body-center' }
	                      ],
	        //"order": [[ 2, "desc" ]],
	        "language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
	    } );
    });
	
	function reCount()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";

		document.getElementById('idprogbar').style.display 		= '';
	    document.getElementById("progressbarXX").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('idprogbarXY').style.display 	= '';
	    document.getElementById("progressbarXY").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('loading_1').style.display = '';
    	var collID	= PRJCODE;

		var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
		$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
		$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RECOUNTBOQ';
		$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RECOUNTBOQ';
		butSubm.submit();

        /*$.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	document.getElementById('loading_1').style.display = 'none';
            	swal(response, 
				{
					icon: "success",
				});
            }
        });*/
	}
	
	function syncJLD()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";
	    swal({
            text: "<?php echo $sureReset; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETWBD';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETWBD';
				butSubm.submit();

		        /*$.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	document.getElementById('loading_1').style.display = 'none';
		            	swal(response, 
						{
							icon: "success",
						});
		            }
		        });*/
            } 
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	function updJob(row)
	{
		var url	= document.getElementById('urlUpdate'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function showDetC(LinkD)
	{
		w = 700;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}
	
	function resOrder()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";
	    swal({
            text: "<?php echo $sureResOrd; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'REODERBOQ';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'REODERBOQ';
				butSubm.submit();

		        /*$.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	document.getElementById('loading_1').style.display = 'none';
		            	swal(response, 
						{
							icon: "success",
						});
		            }
		        });*/
            } 
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	function deleteJL(row)
	{
	    swal({
            text: "<?php echo $sureDelJob; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlDelJob'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	/*swal(response, 
						{
							icon: "success",
						});*/
		                $('#example').DataTable().ajax.reload();
		            }
		        });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
    function sleep(milliseconds) { 
        let timeStart = new Date().getTime(); 
        while (true) { 
            let elapsedTime = new Date().getTime() - timeStart; 
            if (elapsedTime > milliseconds) { 
                break; 
            } 
        } 
    }

	function updStat()
	{
		var timer = setInterval(function()
		{
	       	clsBar();
      	}, 4000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display = 'none';
		document.getElementById('idprogbarXY').style.display = 'none';
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
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>