<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Juli 2019
 * File Name	= joblistdet.php
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
	        $vers     = $this->session->userdata['vers'];

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
		<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
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
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'copyProg')$copyProg = $LangTransl;
			if($TranslCode == 'copyBudg')$copyBudg = $LangTransl;
			if($TranslCode == 'budgSrc')$budgSrc = $LangTransl;
			if($TranslCode == 'budgDest')$budgDest = $LangTransl;
			if($TranslCode == 'Success')$Success = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$alert1		= "Anda Yakin?";
			$alert2		= "Proses ini akan me-reset anggaran sebelumnya.";
			$alert3		= "Baik! Proses akan dilanjutkan. Mohon tunggu beberapa saat.";
			$alert4		= "Baik! Proses reset master budget akan dibatalkan.";
			$alert5		= "Anda belum memilih Anggaran tujuan penyalinan.";
			$sureDelete	= "Anda yakin akan menghapus data ini?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'Sistem akan mengatur ulang urutan BoQ secara otomatis. Yakin?';
		}
		else
		{
			$alert1		= "Are you sure?";
			$alert2		= "This process will reset the previous budget.";
			$alert3		= "Well! The process will be processed. Please wait a few moments.";
			$alert4		= "Well! The budget master reset process will be canceled.";
			$alert5		= "You have not selected a copy destination budget.";
			$sureDelete	= "Are your sure want to delete?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'System will reset BoQ Order ID automatically. Are you sure?';
		}

		$RAP_STAT 		= 0;
		$PRJCODE		= $PRJCODE;
		$PRJ_IMGNAME 	= "building.jpg";
		$sql 			= "SELECT PRJNAME, PRJPERIOD, PRJ_IMGNAME, RAP_STAT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJNAME 	= $row->PRJNAME;
			$PRJPERIOD 	= $row->PRJPERIOD;
			$PRJ_IMGNAME= $row->PRJ_IMGNAME;
			$RAP_STAT 	= $row->RAP_STAT;
		endforeach;

		$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/'.$PRJ_IMGNAME);
		if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
		{
			$imgLoc	= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
		}
		$isLoadDone_1	= 1;
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
				<div class="pull-right">
					<?php if($RAP_STAT == 0) { ?>
						<span class="label label-danger" data-toggle="modal" data-target="#mdl_addPR" style="cursor: pointer;"><i class="fa fa-unlock-alt"></i></span>
					<?php } else { ?>
						<span class="label label-success" onClick="showLOCKER('<?=$PRJCODE?>')" style="cursor: pointer;"><i class="fa fa-lock"></i></span>
					<?php } ?>
				</div>
			</h1>
		</section>

        <section class="content">
			<div class="row">
				<?php
					//$s_00 = "SELECT SUM(JOBCOST) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_BOQ FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";
					$s_00 	= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_BOQ FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";
					$r_00 	= $this->db->query($s_00)->result();
					foreach($r_00 as $rw_00):
						$TOT_RAP 	= $rw_00->TOT_RAP;
						$TOT_BOQ 	= $rw_00->TOT_BOQ;
					endforeach;
					$TOT_RAPP 		= $TOT_RAP;
					if($TOT_RAP == '' || $TOT_RAP == 0)
					{
						$TOT_RAP 	= 0;
						$TOT_RAPP 	= 1;
					}

					$TOT_BOQP 	= $TOT_BOQ;
					if($TOT_BOQ == '' || $TOT_BOQ == 0)
					{
						$TOT_BOQ 	= 0;
						$TOT_BOQP 	= 1;
					}

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

					// KOLOM BOQ
						$VTOT_BOQ 		= $TOT_BOQ;
						$VDEV_RAPBOQ 	= abs($TOT_BOQ - $TOT_RAP);

						$TOT_PROGIN 	= 0;
						$TOT_PROGEKS 	= 0;
						$DEV_RAPBOQPERC = number_format($VDEV_RAPBOQ / $TOT_BOQP * 100, 2);

						$s_02 		= "SELECT IFNULL(SUM(BOQ_BOBOT_PI),0) AS TOT_PROGIN, IFNULL(SUM(BOQ_BOBOT_PIEKS),0) AS TOT_PROGEKS
										FROM tbl_joblist WHERE PRJCODE = '$PRJCODE'";
						$r_02 		= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02):
							$TOT_PROGIN 	= $rw_02->TOT_PROGIN;
							$TOT_PROGEKS 	= $rw_02->TOT_PROGEKS;
						endforeach;

					// KOLOM RAP
						if($VDEV_RAPBOQ > 0)
						{
							$DEVDESC_1 	= "<span class='pull-right'><i class='fa fa-chevron-circle-up'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_2 	= "<i class='fa fa-chevron-circle-up'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-up'></i> $DEV_RAPBOQPERC % </span>";
						}
						else
						{
							$DEVDESC_1 	= "<span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_2 	= "<i class='fa fa-chevron-circle-down'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $DEV_RAPBOQPERC % </span>";
						}
						$VTOT_RAP 		= $TOT_RAP;
					
					// KOLOM REQUEST AND USED
						$VTOT_REQAMN 	= number_format($TOT_REQAMN,0);
						$VTOT_USEAMN 	= number_format($TOT_USEAMN,0);
						$VTOT_REQAMNPERC= $TOT_REQAMN / $TOT_RAPP * 100;
						$VTOT_USEAMNPERC= $TOT_USEAMN / $TOT_RAPP * 100;
					
					// KOLOM REMAIN
						$REM_RAP 		= $TOT_RAP - $TOT_REQAMN;
						$REM_RAPPERC 	= $REM_RAP / $TOT_RAPP * 100;

				?>
		        <div class="col-md-3">
		          	<div class="info-box bg-blue">
		            	<span class="info-box-icon"><i class="ion ion-clipboard"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">Bill of Quantity (BoQ)</span>
			              	<span class="info-box-number"><?=number_format($VTOT_BOQ, 0)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: 100%"></div>
			              	</div>
			                <span class="progress-description">
		                		<i class="fa fa-line-chart" title="Internal"></i> 
			                	<?=number_format($TOT_PROGIN, 4)?>
			                	<span class='pull-right' title="Eksternal">
			                		<i class="fa fa-bar-chart"></i>
				                	<?=number_format($TOT_PROGEKS, 4)?> %
				                </span>
			                </span>
			            </div>
			        </div>
			    </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-green">
			            <span class="info-box-icon"><i class="ion ion-calculator"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">RAP</span>
			              	<span class="info-box-number"><?=number_format($VTOT_RAP, 0)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: 100%"></div>
			              	</div>
			                <span class="progress-description">
			               		<?=$DEVDESC_2?>
			                </span>
			            </div>
		         	</div>
		        </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-yellow ">
			            <span class="info-box-icon"><i class="ion ion-connection-bars"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">Digunakan</span>
			              	<span class="info-box-number"><?=$VTOT_USEAMN?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$VTOT_USEAMNPERC?>%"></div>
			              	</div>
			                <span class="progress-description">
		                		<i class="fa fa-cog" title="Total Permintaan <?php echo $VTOT_REQAMN; ?>"></i> 
			                	<?=number_format($VTOT_REQAMNPERC,2)?> %
			                	<span class='pull-right' title="Total Penggunaan <?php echo $VTOT_USEAMN; ?>">
			                		<i class="fa fa-cogs"></i>
				                	<?=number_format($VTOT_USEAMNPERC,2)?> %
				                </span>
			                </span>
			            </div>
		          	</div>
		        </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-aqua">
		            	<span class="info-box-icon"><i class="ion-information-circled"></i></span>

		            	<div class="info-box-content">
		              		<span class="info-box-text">Sisa Anggaran</span>
		              		<span class="info-box-number"><?=number_format($REM_RAP,2)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$REM_RAPPERC?>%"></div>
			              	</div>
			              	<span class="progress-description">
			              		<i class="fa fa-cubes"></i>
			                	<?=number_format($REM_RAPPERC,2)?> %
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
					                <th width="55%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Sat.</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Volume; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Price; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>BoQ</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Add. (+)</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Used; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Remain; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Projection<br>
					                Complete</th>
					          </tr>
					        </thead>
							<tbody>
							</tbody>
							<tfoot>
								<?php
									$TOT_RAP	= 0;
									$TOT_RAB 	= 0;
									$TOT_ADD	= 0;
									$TOT_USED	= 0;
									/*$sqlTBUDG	= "SELECT SUM(ITM_VOLM * ITM_PRICE) AS TOT_RAP,
														SUM(BOQ_JOBCOST) AS TOT_RAB,
														SUM(ADD_VOLM * ADD_PRICE) AS TOT_ADD,
														SUM(ITM_USED_AM) AS TOT_USED
													FROM tbl_joblist_detail WHERE IS_LEVEL = 1 AND PRJCODE = '$PRJCODE'";*/
									$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_RAB
													FROM tbl_joblist_detail WHERE ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_RAP	= $rowTBUDG->TOT_RAP;
										$TOT_RAB 	= $rowTBUDG->TOT_RAB;
									endforeach;
									//$TOT_DEV	= $TOT_RAB - $TOT_RAP;

									$sqlTBUDG	= "SELECT SUM(ADD_JOBCOST) AS TOT_ADD, SUM(ITM_USED_AM) AS TOT_USED
													FROM tbl_joblist_detail WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_ADD 	= $rowTBUDG->TOT_ADD;
										$TOT_USED 	= $rowTBUDG->TOT_USED;
									endforeach;
									$TOT_DEV	= $TOT_RAB - $TOT_RAP - $TOT_ADD;
									$TOT_REM 	= $TOT_RAP + $TOT_ADD - $TOT_USED;
								?>
					            <tr>
					                <th colspan="4" style="text-align:center; vertical-align:middle" nowrap>T O T A L</th>
					                <th width="6%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_RAP, 0)?></th>
					                <th width="6%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_RAB, 0)?></th>
					                <th width="5%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_ADD, 0)?></th>
					                <th width="6%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_USED, 0)?></th>
					                <th width="3%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_REM, 0)?></th>
					                <th width="3%" style="text-align:right; vertical-align:middle" nowrap><?=number_format(0, 2)?></th>
					          	</tr>
			                </tfoot>
						</table>
					</div>
					<br>
                    <?php
						if($ISCREATE == 1)
						{
							//echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
							echo '<button class="btn btn-warning" title="Copy Budget" data-toggle="modal" data-target="#mdl_addPR"><i class="fa fa-copy"></i></button>&nbsp;&nbsp;';
						}
                        echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>&nbsp;&nbsp;');
                    ?>
				</div>
				<div id="loading_1" class="overlay" style="display:none">
		            <i class="fa fa-refresh fa-spin"></i>
		        </div>
			</div>

		    <a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addItm" id="btnModal" style="display: none;">
        		<i class="glyphicon glyphicon-search"></i>
        	</a>
			<input type="hidden" name="PRJCODE" id="PRJCODE">
			<input type="hidden" name="JOBCODEID" id="JOBCODEID">
			<input type="hidden" name="JOBDESC" id="JOBDESC">
			<input type="hidden" name="urlSv" id="urlSv">

	    	<!-- ============ START MODAL =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    max-width: 900px;   
					}

					.datatable td span{
					    width: 80%;
					    display: block;
					    overflow: hidden;
					}

					.modal {
						display: 'flex';
						justify-content: 'center';
						align-items: 'center';
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addPR" name='mdl_addPR' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
							            <div class="box-header">
							              	<ul class="nav nav-tabs">
							                    <li id="li1" <?php echo $Active1Cls; ?>>
							                    	<a href="#" data-toggle="tab"><?=$copyBudg?></a>
							                    </li>
							                </ul>
							            </div>
							            <div class="box-body">
                                        	<div class="col-md-6">
												<div class="box box-warning">
													<div class="box-header with-border">
														<i class="glyphicon glyphicon-play-circle"></i>
														<h3 class="box-title"><?=$budgSrc?></h3>
													</div>
													<div class="box-body">
														<div class="col-md-12">
															<ul class="list-group list-group-unbordered">
																<li class="list-group-item">
																	<b><?php echo "$PRJCODE - $PRJNAME"; ?></b>
																</li>
																<li class="list-group-item">
																	<b><?=$Budget?></b> <a class="pull-right">235,000,000,000</a>
																</li>
															</ul>
														</div>
														<div class="col-md-6">
															<div class="box-body box-profile">
																<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:140px; width:150px" alt="User profile picture">
															</div>
														</div>
														<div class="col-md-6">
															<ul class="list-group list-group-unbordered">
																<li class="list-group-item">
																	<b><?=$JobNm?></b> <a class="pull-right">543</a>
																</li>
																<li class="list-group-item">
																	<b>Item</b> <a class="pull-right">13,287</a>
																</li>
															</ul>
							                                <button class="btn btn-warning btn-block" type="button" id="btnDetail1" name="btnDetail1">
	                                                    		<b><?=$copyBudg?></b>
	                                                    	</button>
														</div>
													</div>
												</div>
                                      		</div>

                                        	<div class="col-md-6">
												<div class="box box-success">
													<div class="box-header with-border">
														<i class="glyphicon glyphicon-download"></i>
														<h3 class="box-title"><?=$budgDest?></h3>
													</div>
													<div class="box-body">
														<div class="alert alert-info alert-dismissible">
											                <!-- <h4><i class="icon fa fa-warning"></i> Perhatian</h4> -->
											                Proses penyalinan data RAP memerlukan beberapa menit, silahkan menunggu.
										              	</div>
														<div class="col-md-12">
															<ul class="list-group list-group-unbordered">
																<li class="list-group-item">
																	<select name="PRJCODE_DEST" id="PRJCODE_DEST" class="form-control select2" style="width: 100%">
											                          <option value=""> --- </option>
												                        <?php
												                            $sql 	= "SELECT PRJCODE, PRJNAME FROM tbl_project";
																			$result = $this->db->query($sql)->result();
																			foreach($result as $row) :
											                                    $PRJCODE1 	= $row->PRJCODE;
											                                    $PRJNAME1 	= $row->PRJNAME;
											                                    ?>
											                                		<option value="<?php echo $PRJCODE1; ?>">
											                                			<?php echo "$PRJCODE1 - $PRJNAME1"; ?>
											                                		</option>
											                                	<?php
											                                endforeach;
											                            ?>
											                        </select>
																</li>
																<li class="list-group-item" id="perProgInf">
																	<b><?php echo "$copyProg - $Budget"; ?> (%)</b>
																</li>
																<div class="cssProgress" id="idprogbarMDL">
															      	<div class="cssProgress">
																	    <div class="progress3">
																			<div id="progressbarXXMDL" style="text-align: center;">0%</div>
																		</div>
																		<span class="cssProgress-label" id="information" ></span>
																	</div>
															    </div>
															    <div class="alert alert-success alert-dismissible" id="SuccessInf" style="display: none;">
													                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
													                <h4><i class="icon fa fa-check"></i><?=$Success?></h4> Proses Penyalinan Selesai.
												              	</div>
															</ul>
														</div>
													</div>
												</div>
                                      		</div>
                                      		<div id="loading_1" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
									            <i class="fa fa-refresh fa-spin"></i>
									        </div>
		                                </div>
		                            </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

				<?php
					$securlImp 	= base_url().'index.php/__l1y/impCOA/?id=';
					$impLink 	= "$securlImp~$PRJCODE";
				?>
				<script type="text/javascript">
					$(document).ready(function()
					{
						$("#idRefresh1").click(function()
						{
							$('#example1').DataTable().ajax.reload();
						});

					   	$("#btnDetail1").click(function()
					    {
							PRJCODE 	= "<?php echo $PRJCODE; ?>";
						    swal({
					            title: "<?php echo $alert1; ?>",
					            text: "<?php echo $alert2 ?>",
					            icon: "warning",
					            buttons: ["Tidak", "Ya"],
							})
					        .then((willDelete) => 
					        {
					            if (willDelete) 
					            {
									//document.getElementById('idprogbar').style.display = '';
									PRJCODE_D 	= document.getElementById('PRJCODE_DEST').value;
									var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
									if(PRJCODE_D == '')
									{
										swal('<?php echo $alert5; ?>',
										{
											icon: "warning",
										});
	                                    return false;
									}
									else
									{
									    document.getElementById("progressbarXXMDL").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
										//document.getElementById('loading_1').style.display = '';
										document.getElementById('btnDetail1').disabled 		= true;
										$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
										$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= PRJCODE_D;
										$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'COPY_BUDGET';
										butSubm.submit();
									}
					            }
	        				});
					    });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

	    	<!-- ============ START MODAL ITEM =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
			
					$LangID 	= $this->session->userdata['LangID'];
					if($LangID == 'IND')
					{
						$setAcc 	= "Pengaturan Item Pekerjaan";
					}
					else
					{
						$setAcc 	= "Job Item Setting";
					}
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><?php echo $setAcc; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
	                                    	<form method="post" name="frmSearch1" id="frmSearch1" action="">
	                                        	<div class="row">
							                        <div class="box box-success">
							                            <div class="box-body">
										                    <div class="col-sm-5">
										                    	<label for="inputName" class="control-label"><?php echo "$JobCode"; ?></label>
										                    	<div id="JOBCODEIDD"></div>
										                    </div>
										                    <div class="col-sm-7">
										                    	<label for="inputName" class="control-label"><?php echo "$JobName"; ?></label>
										                    	<div id="JOBCODEDESC"></div>
										                    </div>
										                    <div class="col-sm-12">
										                    	<label for="inputName" class="control-label"><?php echo "$ItemName"; ?></label>
										                    </div>
										                    <div class="col-sm-12">
										                    	<select name="ITM_CODE" id="ITM_CODE" class="form-control select2" style="width: 100%">
								                        			<option value="" > --- </option>
								                                    <?php
											                            $s_bv	= "SELECT ITM_CODE, ITM_NAME FROM tbl_item
											                            			WHERE PRJCODE IN (SELECT PRJCODE FROM tbl_project WHERE isHO = 1)
											                            				AND ITM_CODE != ''
										                                            	ORDER BY ITM_NAME";
											                            $r_bv 	= $this->db->query($s_bv)->result();

																		foreach($r_bv as $rw_av) :
																			$ITM_CODE	= $rw_av->ITM_CODE;
																			$ITM_NAME 	= $rw_av->ITM_NAME;
																			?>
																				<option value="<?php echo $ITM_CODE; ?>"><?php echo "$ITM_NAME - $ITM_CODE"; ?></option>
																			<?php
																		endforeach;
																	?>
								                                </select>
										                    </div>
							                            </div>
							                        </div>
							                    </div>

	                                        	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
	                                        		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;OK
	                                        	</button>&nbsp;
	                          					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
	                                        		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
	                                        	</button>
	                                        </form>
                                      	</div>
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$(document).ready(function()
					{
					   	$("#btnDetail2").click(function()
					    {
							var urlSv 		= $("#urlSv").val(); 
							var PRJCODE 	= $("#PRJCODE").val();
							var ITM_CODE 	= $("#ITM_CODE").val();
							var JOBCODEID	= $("#JOBCODEID").val();
							var JOBDESC		= $("#JOBDESC").val();
							var dataString 	= 'PRJCODE='+PRJCODE+'&ITM_CODE='+ITM_CODE+'&JOBCODEID='+JOBCODEID+'&JOBDESC='+JOBDESC;
							//alert(dataString)
							$.ajax({
			                    type: 'POST',
			                    url: urlSv,
			                    data: dataString,
			                    success: function(response)
			                    {
			                        swal(response, 
			                        {
			                            icon: "success",
			                        });
			                        $('#example').DataTable().ajax.reload();
			                    }
			                });

                        	document.getElementById("idClose").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL ITEM =============== -->

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
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
		
		//Datemask dd/mm/yyyy
		$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
		//Datemask2 mm/dd/yyyy
		$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
		//Money Euro
		$("[data-mask]").inputmask();
		
		//Date range picker
		$('#reservation').daterangepicker();
		//Date range picker with time picker
		$('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
		//Date range as a button
		$('#daterange-btn').daterangepicker(
			{
			  ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			  },
			  startDate: moment().subtract(29, 'days'),
			  endDate: moment()
			},
			function (start, end) {
			  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}
		);
		
		//Date picker
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      autoclose: true,
		  endDate: '+1d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
		});
		
		//iCheck for checkbox and radio inputs
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass: 'iradio_minimal-blue'
		});
		//Red color scheme for iCheck
		$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
		  checkboxClass: 'icheckbox_minimal-red',
		  radioClass: 'iradio_minimal-red'
		});
		//Flat red color scheme for iCheck
		$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		  checkboxClass: 'icheckbox_flat-green',
		  radioClass: 'iradio_flat-green'
		});
		
		//Colorpicker
		$(".my-colorpicker1").colorpicker();
		//color picker with addon
		$(".my-colorpicker2").colorpicker();
		
		//Timepicker
		$(".timepicker").timepicker({
		  showInputs: false
		});
	});

    $(document).ready(function() {
    $('#example').DataTable( {
    	"bSort" : false,
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        "ajax": "<?php echo site_url('c_project/c_joblistdet/get_AllDataJL/?id='.$PRJCODE)?>",
        "type": "POST",
        "lengthMenu": [[100, 200, -1], [100, 200, "All"]],
        //"lengthMenu": [[50, 100, 200], [50, 100, 200]],
        "columnDefs": [ { targets: [4,5,6,7,8,9], className: 'dt-body-right' },
        				//{ targets: [0,1,2,3,4,5,6,7,8], orderable: false }
                      ],
        //"order": [[ 0, "desc" ]],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );
	
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
	
	function updITM(collDtItm)
	{
		collDtItm 	= collDtItm.split('~');
		urlSv 		= collDtItm[0];
		PRJCODE 	= collDtItm[1];
		JOBCODEID 	= collDtItm[2];
		JOBDESC 	= collDtItm[3];

		document.getElementById('PRJCODE').value 		= PRJCODE;
		document.getElementById('JOBCODEID').value 		= JOBCODEID;
		document.getElementById('JOBDESC').value 		= JOBDESC;
		document.getElementById('JOBCODEIDD').innerHTML = JOBCODEID;
		document.getElementById('JOBCODEDESC').innerHTML= JOBDESC;
		document.getElementById('urlSv').value 			= urlSv;
		document.getElementById('btnModal').click();
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
      	}, 8000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display = 'none';
	}

	function updStatMDL()
	{
		var timer = setInterval(function()
		{
	       	clsBarMDL();
      	}, 8000);
	}

	function clsBarMDL()
	{
		document.getElementById('perProgInf').style.display 	= 'none';
		document.getElementById('idprogbarMDL').style.display 	= 'none';
		document.getElementById('SuccessInf').style.display 	= '';
		document.getElementById('btnDetail1').disabled 			= false;
	}
</script>
<script language="javascript" src="<?php echo base_url() . 'assets/css/pbar/js/jquery/jquery-2.1.4.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/css/pbar/js/functions.js'; ?>"></script>
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