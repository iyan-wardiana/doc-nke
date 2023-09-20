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

$LOCK_DOCDATE 	= date('d/m/Y');

$imgemp_filename 	= '';
$imgemp_filenameX	= '';
$sqlGetIMG			= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLocEmp				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
$imgLocEmp1				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
{
	$imgLocEmp			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
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
    <style type="text/css">
        .swal-icon img{
            width: 120px;
            height: 120px;
            border: 4px solid gray;
            -webkit-border-radius: 40px;
            border-radius: 40px;
            border-radius: 50%;
            margin: 20px auto;
            padding: 0;
            position: relative;
            box-sizing: content-box;
        }
    </style>

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
			if($TranslCode == 'lockRAP')$lockRAP = $LangTransl;
			if($TranslCode == 'budgSrc')$budgSrc = $LangTransl;
			if($TranslCode == 'ProjectInformation')$ProjectInformation = $LangTransl;
			if($TranslCode == 'lockInfo')$lockInfo = $LangTransl;
			if($TranslCode == 'Success')$Success = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$alert1		= "Anda Yakin?";
			$alert2		= "Setelah dikunci, TIDAK BISA DIBUKA KEMBALI. Segala bentuk perubahan budget harus melalui AMANDEMEN BUDGET (BA).";
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
			$alert2		= "Once locked, CANNOT BE RE-OPENED. Budget changes must go through the BUDGET AMENDMENT (BA).";
			$alert3		= "Well! The process will be processed. Please wait a few moments.";
			$alert4		= "Well! The budget master reset process will be canceled.";
			$alert5		= "You have not selected a copy destination budget.";
			$sureDelete	= "Are your sure want to delete?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'System will reset BoQ Order ID automatically. Are you sure?';
		}

		$PRJCOST 		= 0;
		$RAP_STAT 		= 0;
		$PRJCODE		= $PRJCODE;
		$PRJ_IMGNAME 	= "building.jpg";
		$sql 			= "SELECT PRJNAME, PRJPERIOD, PRJ_IMGNAME, PRJCOST, RAP_STAT, PRJADD FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJNAME 	= $row->PRJNAME;
			$PRJPERIOD 	= $row->PRJPERIOD;
			$PRJ_IMGNAME= $row->PRJ_IMGNAME;
			$PRJCOST 	= $row->PRJCOST;
			$RAP_STAT 	= $row->RAP_STAT;
			$PRJADD 	= $row->PRJADD;
		endforeach;
		if($PRJADD == '')
			$PRJADD = "-";

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
					<div id="divUnLock" <?php if($RAP_STAT == 1) { ?> style="display: none;" <?php } ?>>
						<span class="label label-danger" data-toggle="modal" data-target="#mdl_addPR" style="cursor: pointer;" style="display: none;"><i class="fa fa-unlock-alt"></i></span>
					</div>
					<div id="divLock" <?php if($RAP_STAT == 0) { ?> style="display: none;" <?php } ?>>
						<span class="label label-success" onClick="showLOCKER('<?=$PRJCODE?>')" style="cursor: pointer;"><i class="fa fa-lock"></i>
						</span>
					</div>
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

					$s_01 	= "SELECT SUM(PO_AMOUNT + WO_AMOUNT) AS TOT_REQAMN, SUM(ITM_USED_AM) AS TOT_USEAMN FROM tbl_joblist_detail
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
						$VDEV_RAPBOQ 	= $TOT_BOQ - $TOT_RAP;

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
							$DEVDESC_1 	= "<span class='pull-right text-green'><i class='fa fa-chevron-circle-up'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_2 	= "<span class='pull-right text-green'><i class='fa fa-chevron-circle-up'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." </span>";
							$DEVDESC_3 	= "<i class='fa fa-chevron-circle-up'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-up'></i> $DEV_RAPBOQPERC % </span>";
						}
						else
						{
							$DEVDESC_1 	= "<span class='pull-right text-red'><i class='fa fa-chevron-circle-down'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_2 	= "<span class='pull-right text-red'><i class='fa fa-chevron-circle-down'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." </span>";
							$DEVDESC_3 	= "<i class='fa fa-chevron-circle-down'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $DEV_RAPBOQPERC % </span>";
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
			               		<?=$DEVDESC_3?>
			                </span>
			            </div>
		         	</div>
		        </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-yellow ">
			            <span class="info-box-icon"><i class="ion ion-connection-bars"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text" title="OP, SPK, VCASH, PPD, VLK">Diminta</span>
			              	<span class="info-box-number" title="OP, SPK, VCASH, PPD, VLK"><?=$VTOT_REQAMN?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$VTOT_REQAMNPERC?>%"></div>
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

		        <div class="modal fade" id="mdl_addPR" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		        	<div class="modal-dialog" role="document">
					    <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
									<form class="form-horizontal" name="frm" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
								    	<div class="col-md-12">
								            <div class="box-header">
								              	<ul class="nav nav-tabs">
								                    <li id="li1" <?php echo $Active1Cls; ?>>
								                    	<a href="#" data-toggle="tab"><?=$lockRAP?></a>
								                    </li>
								                </ul>
								            </div>
								            <div class="box-body">
	                                        	<div class="col-md-6">
													<div class="box box-warning">
														<div class="box-header with-border">
															<i class="glyphicon glyphicon-home"></i>
															<h3 class="box-title"><?=$ProjectInformation?></h3>
														</div>
														<div class="box-body">
															<div class="col-md-12">
																<ul class="list-group list-group-unbordered">
																	<li class="list-group-item">
																		<b><?php echo "$PRJCODE - $PRJNAME"; ?></b>
																	</li>
																	<li class="list-group-item">
																		<span class="description"><?=$PRJADD?></span>
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
																		<b>RAB</b> <a class="pull-right"><?=number_format($TOT_BOQP)?></a>
																	</li>
																	<li class="list-group-item">
																		<b>RAP</b> <a class="pull-right"><?=number_format($TOT_RAPP)?></a>
																	</li>
																	<li class="list-group-item">
																		<b>DEV.</b>
																		<a class="pull-right"><?=$DEVDESC_1?></a>
																	</li>
																	<li class="list-group-item">
																		<b>&nbsp;</b>
																		<a class="pull-right"><?=$DEVDESC_2?></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
	                                      		</div>

	                                        	<div class="col-md-6">
													<div class="box box-success">
														<div class="box-header with-border">
															<i class="glyphicon glyphicon-info-sign"></i>
															<h3 class="box-title"><?=$lockInfo?></h3>
														</div>
														<div class="box-body">
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<label for="inputName">No. SK / Tgl. Dokumen</label>
											                    </div>
											                    <div class="col-sm-7">
											                    	<input type="text" class="form-control" style="text-align:left" name="LOCK_DOCREF" id="LOCK_DOCREF" value="" placeholder="No. SK Penguncian" />
											                    </div>
											                    <div class="col-sm-5">
											                    	<div class="input-group date">
									                            		<div class="input-group-addon">
									                            		<i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="LOCK_DOCDATE" class="form-control pull-left" id="datepicker" value="<?php echo $LOCK_DOCDATE; ?>" style="width:100%">
									                            	</div>
											                    </div>
											                </div>
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<label for="inputName">Catatan</label>
											                    </div>
											                    <div class="col-sm-12">
											                    	<input type="text" class="form-control" style="text-align:left" name="LOCK_DESC" id="LOCK_DESC" value="" placeholder="Catatan Penguncian RAP" />
											                    </div>
											                </div>
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<input type="file" name="userfile" id="userfile" class="filestyle" data-buttonName="btn-primary"/>
											                    </div>
											                </div>
															<div class="form-group">
											                    <div class="col-sm-6">
											                    	<label for="inputName">Penanggung Jawab 1</label>
											                    </div>
											                    <div class="col-sm-6">
											                    	<label for="inputName">Penanggung Jawab 2</label>
											                    </div>
											                </div>
															<div class="form-group">
											                    <div class="col-sm-6">
											                    	<select name="LOCK_EMP1" id="LOCK_EMP1" class="form-control select2" style="width: 100%;">
											                    		<option value=""> --- </option>
									                                    <?php
									                                        $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
									                                                    FROM tbl_employee ORDER BY First_Name";
									                                        $sqlEmp	= $this->db->query($sqlEmp)->result();
									                                        foreach($sqlEmp as $row) :
									                                            $Emp_ID		= $row->Emp_ID;
									                                            $First_Name	= $row->First_Name;
									                                            $Last_Name	= $row->Last_Name;
									                                            $Email		= $row->Email;
									                                            ?>
									                                                <option value="<?php echo "$Emp_ID"; ?>">
									                                                    <?php echo "$First_Name $Last_Name"; ?>
									                                                </option>
									                                            <?php
									                                        endforeach;
									                                    ?>
									                                </select>
											                    </div>
											                    <div class="col-sm-6">
											                    	<select name="LOCK_EMP2" id="LOCK_EMP2" class="form-control select2" style="width: 100%;">
											                    		<option value=""> --- </option>
									                                    <?php
									                                        $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
									                                                    FROM tbl_employee ORDER BY First_Name";
									                                        $sqlEmp	= $this->db->query($sqlEmp)->result();
									                                        foreach($sqlEmp as $row) :
									                                            $Emp_ID		= $row->Emp_ID;
									                                            $First_Name	= $row->First_Name;
									                                            $Last_Name	= $row->Last_Name;
									                                            $Email		= $row->Email;
									                                            ?>
									                                                <option value="<?php echo "$Emp_ID"; ?>">
									                                                    <?php echo "$First_Name $Last_Name"; ?>
									                                                </option>
									                                            <?php
									                                        endforeach;
									                                    ?>
									                                </select>
											                    </div>
											                </div>
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<div class="text-center">
													                	<button class="btn btn-warning btn-block" type="button" id="btnDetail1" name="btnDetail1">
				                                                    		<b><i class="fa fa-lock"></i>&nbsp;&nbsp;<?=$lockRAP?></b>
				                                                    	</button>
				                                                    	<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal" style="display: none;">
				                                                    		<i class="glyphicon glyphicon-remove"></i>
				                                                    	</button>
													              	</a>
											                    </div>
											                </div>
														</div>
													</div>
	                                      		</div>
			                                </div>
			                            </div>
			                        </form>
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
					   	$("#btnDetail1").click(function()
					    {
					    	PRJCODE 	= "<?php echo $PRJCODE; ?>";
					    	DOC_REF		= document.getElementById('LOCK_DOCREF').value;
					    	DOC_DATE	= document.getElementById('datepicker').value;
					    	DOC_DESC	= document.getElementById('LOCK_DESC').value;
					    	DOC_FILE	= document.getElementById('userfile').value;
					    	DOC_EMP1	= document.getElementById('LOCK_EMP1').value;
					    	DOC_EMP2	= document.getElementById('LOCK_EMP1').value;
					    	
					    	if(DOC_REF == '')
					    	{
					    		swal('No. SK Direksi tidak boleh kosong',
					    		{
					    			icon:"warning"
					    		})
					    		.then(function ()
					    		{
					    			swal.close();
					    			document.getElementById('LOCK_DOCREF').focus();
					    		})
					    		return false;
					    	}
					    	
					    	if(DOC_EMP1 == '')
					    	{
					    		swal('Anda belum memilih 1 pun Penanggung Jawab untuk mengunci RAP ini.',
					    		{
					    			icon:"warning"
					    		});
					    		return false;
					    	}

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
							    	var frmdata = { PRJCODE 	: PRJCODE,
							    					DOC_REF		: DOC_REF,
							    					DOC_DATE	: DOC_DATE,
							    					DOC_DESC	: DOC_DESC,
							    					DOC_FILE	: DOC_FILE,
							    					DOC_EMP1	: DOC_EMP1,
							    					DOC_EMP2	: DOC_EMP2,
							    				}
		                            $.ajax({
		                                type: 'POST',
		                                url: "<?php echo site_url('__l1y/lockRAP')?>",
		                                data: frmdata,
		                                success: function(isRespon)
		                                {
		                                	swal({
											  	title: "Berhasil!",
											  	text: "Untuk perubahan budget di RAP, hanya dapat dilakukan melalui Amandemen / BA",
											  	icon: "success",
											})
											.then(function()
											{
												swal.close();
												document.getElementById('idClose1').click();
												document.getElementById('divUnLock').style.display 	= 'none';
												document.getElementById('divLock').style.display 	= '';
		                                		$('#example').DataTable().ajax.reload();
											})
		                                }
		                            });
							    };
							});
						});
					});
				</script>
	    	<!-- ============ END MODAL =============== -->
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
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

	function checkLOCK()
	{
		var DOCNUM		= document.getElementById('PR_NUM').value;
	}

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