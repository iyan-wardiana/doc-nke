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
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'copyProg')$copyProg = $LangTransl;
			if($TranslCode == 'copyBudg')$copyBudg = $LangTransl;
			if($TranslCode == 'addJob')$addJob = $LangTransl;
			if($TranslCode == 'budgSrc')$budgSrc = $LangTransl;
			if($TranslCode == 'budgDest')$budgDest = $LangTransl;
			if($TranslCode == 'Success')$Success = $LangTransl;
			if($TranslCode == 'JobParent')$JobParent = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'LastLevel')$LastLevel = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= "Anda Yakin?";
			$alert2		= "Proses ini akan me-reset anggaran sebelumnya.";
			$alert3		= "Baik! Proses akan dilanjutkan. Mohon tunggu beberapa saat.";
			$alert4		= "Baik! Proses reset master budget akan dibatalkan.";
			$alert5		= "Anda belum memilih Anggaran tujuan penyalinan.";

			$alert6		= "Anda belum menentukan induk pekerjaan.";
            $alert7     = "Kode pekerjaan tidak boleh kosong.";
            $alert8     = "Nama pekerjaan tidak boleh kosong.";
            $alert9     = "Anda belum menentukan unit pekerjaan.";
            $alert10    = "Silahkan tentukan level pekerjaan.";

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

    		$alert6		= "You have not defined a parent job yet.";
    		$alert7		= "Job code can not be empty.";
            $alert8     = "Job description can not be empty.";
            $alert9     = "You have not defined a job unit yet.";
            $alert10    = "Please set level of job.";

			$sureDelete	= "Are your sure want to delete?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'System will reset BoQ Order ID automatically. Are you sure?';
		}

		$PRJCODE		= $PRJCODE;
		$PRJ_IMGNAME 	= "building.jpg";
		$sql 			= "SELECT PRJNAME, PRJPERIOD, PRJ_IMGNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJNAME 	= $row->PRJNAME;
			$PRJPERIOD 	= $row->PRJPERIOD;
			$PRJ_IMGNAME= $row->PRJ_IMGNAME;
			$PRJCOST 	= $row->PRJCOST;
		endforeach;

		$sIMP	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
		$rJL	= $this->db->count_all($sIMP);

		$sIMP	= "tbl_item WHERE PRJCODE = '$PRJCODE'";
		$rITM	= $this->db->count_all($sIMP);

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
			</h1>
		</section>

        <section class="content">
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
					                <th width="38%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Unit; ?></th>
					                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Volume; ?></th>
					                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Price; ?></th>
					                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Add. (+)</th>
					                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Used; ?></th>
					                <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Remain; ?></th>
					                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>Projection<br>
					                Complete</th>
					                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					          </tr>
					        </thead>
							<tbody>
							</tbody>
							<tfoot>
								<?php
									$TOT_VOLBG	= 0;
									$TOT_ADDBG	= 0;
									$TOT_USEBG	= 0;
									$sqlTBUDG	= "SELECT SUM(ITM_VOLM * ITM_PRICE) AS TOT_VOLBG, SUM(ADD_VOLM * ADD_PRICE) AS TOT_ADDBG, SUM(ITM_USED_AM) AS TOT_USEBG
													FROM tbl_joblist_detail WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_VOLBG	= $rowTBUDG->TOT_VOLBG;
										$TOT_ADDBG 	= $rowTBUDG->TOT_ADDBG;
										$TOT_USEBG 	= $rowTBUDG->TOT_USEBG;
									endforeach;
									$TOT_REMBG	= $TOT_VOLBG + $TOT_ADDBG - $TOT_USEBG;
								?>
					            <tr>
					                <th colspan="5" style="text-align:center; vertical-align:middle" nowrap>T O T A L</th>
					                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?=number_format($TOT_VOLBG, 2)?></th>
					                <th width="5%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_ADDBG, 2)?></th>
					                <th width="6%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_USEBG, 2)?></th>
					                <th width="3%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_REMBG, 2)?></th>
					                <th width="3%" style="text-align:right; vertical-align:middle" nowrap><?=number_format(0, 2)?></th>
					                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					          	</tr>
			                </tfoot>
						</table>
					</div>
					<br>
	                <?php
	                    if($DefEmp_ID == 'D15040004221')
	                    {
	                    	echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
	                        echo anchor("$secUpl",'<button class="btn btn-warning" title="'.$Upload.'"><i class="glyphicon glyphicon-import"></i></button>&nbsp;');
	                        echo '<button class="btn btn-info" onClick="resOrder()" title="Reset Order"><i class="glyphicon glyphicon-th-list"></i></button>&nbsp;';
	                		echo '<button class="btn btn-danger" onClick="syncJLD()" title="Recount"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;';
							//echo '<button class="btn btn-warning" title="Tambahkan Pekerjaan" data-toggle="modal" data-target="#mdl_addJH"><i class="fa fa-copy" id="mdlAddJH"></i></button>&nbsp;';
	                    }
	                    else if($ISCREATE == 1)
	                    {
	                    	echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
	                        echo anchor("$secUpl",'<button class="btn btn-warning" title="'.$Upload.'"><i class="glyphicon glyphicon-import"></i></button>&nbsp;');
	                    }
	                    if($pgFrom == 'HO')
							echo '<button class="btn btn-warning" title="Copy Budget" data-toggle="modal" data-target="#mdl_addPR"><i class="fa fa-copy" id="mdlAddPR"></i></button>&nbsp;';
						
	                    echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>&nbsp;');
	                ?>
				</div>
				<div id="loading_1" class="overlay" style="display:none">
		            <i class="fa fa-refresh fa-spin"></i>
		        </div>
			</div>

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
																	<b><?=$Budget?></b> <a class="pull-right"><?php echo number_format($PRJCOST, 2); ?></a>
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
																	<b><?=$JobNm?></b> <a class="pull-right"><?php echo number_format($rJL, 0); ?></a>
																</li>
																<li class="list-group-item">
																	<b>Item</b> <a class="pull-right"><?php echo number_format($rITM, 0); ?></a>
																</li>
															</ul>
							                                <button class="btn btn-warning btn-block" type="button" id="btnDetail1" name="btnDetail1" <?php if($rJL == 0) { ?> disabled <?php } ?>>
	                                                    		<b><?=$copyBudg?></b>
	                                                    	</button>
														</div>
														<div class="col-md-12">
		                                                    <?php if($rJL == 0) { ?>
								                                <div class="alert alert-warning alert-dismissible">
													                Tidak ada data yang dapat disalin dari RAP ini.
												              	</div>
		                                                    <?php } ?>
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
												                            $sql 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJLEV = 2 
												                            			AND PRJCODE != '$PRJCODE'";
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

	    	<!-- ============ START MODAL : ADD JOB =============== -->
		        <div class="modal fade" id="mdl_addJH" name='mdl_addJH' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
						            <div class="box-body">
										<div class="box box-warning">
											<div class="box-header with-border">
												<i class="glyphicon glyphicon-play-circle"></i>
												<h3 class="box-title"><?=$addJob?></h3>
											</div>
											<div class="box-body">
												<form class="form-horizontal" name="frmAddJH" id="frmAddJH" method="post" action="" enctype="multipart/form-data" onSubmit="return submitForm()">
							                        <input type="hidden" name="selROW" id="selROW" value="" />
							                        <input type="hidden" name="pgFrom" id="pgFrom" value="<?php echo $pgFrom; ?>" />
							                        <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
							                        <input type="Hidden" name="rowCount" id="rowCount" value="0">
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label"><?php echo $JobParent; ?></label>
							                            <div class="col-sm-10">
							                            	<input type="hidden" class="form-control" name="JOBPARENT" id="JOBPARENT" value="" />
							                              	<select name="JOBPARENTX" id="JOBPARENTX" class="form-control select2" style="width: 100%" disabled>
							                                    <option value="0"> --- </option>
							                                    <?php
							                                        $s_BOQ    = "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
							                                        $r_BOQ    = $this->db->query($s_BOQ)->result();
							                                        foreach($r_BOQ as $rw_BOQ) :
							                                            $JOBID      = $rw_BOQ->JOBCODEID;
							                                            $JOBDESC    = $rw_BOQ->JOBDESC;
							                                            ?>
							                                            <option value="<?php echo $JOBID; ?>"><?php echo "$JOBID : $JOBDESC"; ?></option>
							                                            <?php
							                                        endforeach;
							                                    ?>
							                                </select>
							                            </div>
							                        </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label"><?php echo $JobCode; ?></label>
							                            <div class="col-sm-5">
							                                <input type="text" class="form-control" style="text-align:left" name="JOBCODEID" id="JOBCODEID" value="" />
							                            </div>
							                            <label for="inputName" class="col-sm-2 control-label" title="Induk RAP"><?php echo $LastLevel; ?></label>
							                            <div class="col-sm-3">
							                                <select name="ISLASTH" id="ISLASTH" class="form-control select2" style="width: 100%">
							                                    <option value=""> --- </option>
							                                    <option value="1"> Ya </option>
							                                    <option value="0"> Bukan </option>
							                                </select>
							                            </div>
							                        </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label" style="vertical-align:top"><?php echo $JobName; ?></label>
							                            <div class="col-sm-5">
							                                <input type="text" class="form-control" style="text-align:left" name="JOBDESC" id="JOBDESC" value="" />
							                            </div>
							                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Unit; ?></label>
							                            <div class="col-sm-3">
							                                <select name="JOBUNIT" id="JOBUNIT" class="form-control select2" style="width: 100%">
							                                    <option value="0"> --- </option>
							                                    <?php
							                                        $sqlUnit 	= "SELECT * FROM tbl_unittype";
							                                        $resUnit 	= $this->db->query($sqlUnit)->result();
							                                        foreach($resUnit as $rowUM) :
							                                            $Unit_Type_Code = $rowUM->Unit_Type_Code;
							                                            $UMCODE 		= $rowUM->UMCODE;
							                                            $Unit_Type_Name	= $rowUM->Unit_Type_Name;
							                                            ?>
							                                            <option value="<?php echo $Unit_Type_Code; ?>"><?php echo $Unit_Type_Name; ?></option>
							                                            <?php
							                                        endforeach;
							                                    ?>
							                                </select>
							                            </div>
							                        </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">BoQ</label>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label">Volume</label>
							                            </div>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label"><?=$Price?></label>
							                            </div>
							                       	</div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="BOQ_VOLM1" id="BOQ_VOLM1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolmBQ(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="BOQ_VOLM" id="BOQ_VOLM" value="0.00" />
							                            </div>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="BOQ_PRICE1" id="BOQ_PRICE1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrcBQ(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="BOQ_PRICE" id="BOQ_PRICE" value="0.00" />
							                            </div>
							                       </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">RAP</label>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label">Volume</label>
							                            </div>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label"><?=$Price?></label>
							                            </div>  
							                       	</div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="ITM_VOLM1" id="ITM_VOLM1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolmRAP(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="ITM_VOLM" id="ITM_VOLM" value="0.00" />
							                            </div>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE1" id="ITM_PRICE1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrcRAP(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE" id="ITM_PRICE" value="0.00" />
							                            </div>
							                       	</div>
							                        <div class="form-group">
							                            <div class="col-sm-offset-2 col-sm-10">
			                                            	<button type="button" class="btn btn-primary" onClick="proc_AddJH()">
			                                            		<i class="glyphicon glyphicon-ok"></i>
			                                            	</button>
			                              					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
			                                            		<i class="glyphicon glyphicon-remove"></i>
			                                            	</button>
							                            </div>
							                        </div>
							                    </form>
											</div>
										</div>
	                                </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$("#idClose2").click(function(){
			            $("#mdl_addJH").modal('hide');
			        });

					function proc_AddJH(row)
					{
						var JOBPARENT   = document.getElementById('JOBPARENT').value;
				        var JOBCODEID   = document.getElementById('JOBCODEID').value;
				        var ISLASTH     = document.getElementById('ISLASTH').value;
				        var JOBDESC     = document.getElementById('JOBDESC').value;
				        var JOBUNIT     = document.getElementById('JOBUNIT').value;

				        if(JOBPARENT == 0)
				        {
				            swal('<?php echo $alert6; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBPARENT').focus();
				            });
				            return false;
				        }

				        if(JOBCODEID == '')
				        {
				            swal('<?php echo $alert7; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBCODEID').focus();
				            });
				            return false;
				        }

				        if(ISLASTH == '')
				        {
				            swal('<?php echo $alert10; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('ISLASTH').focus();
				            });
				            return false;
				        }

				        if(JOBDESC == '')
				        {
				            swal('<?php echo $alert8; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBDESC').focus();
				            });
				            return false;
				        }

				        if(JOBUNIT == 0)
				        {
				            swal('<?php echo $alert9; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBUNIT').focus();
				            });
				            return false;
				        }

						var frmData = 	{
											PRJCODE		: $("#PRJCODE").val(),
											JOBPARENT	: $("#JOBPARENT").val(),
											JOBCODEID	: $("#JOBCODEID").val(),
											ISLASTH		: $("#ISLASTH").val(),
											JOBDESC		: $("#JOBDESC").val(),
											JOBUNIT		: $("#JOBUNIT").val(),
											BOQ_VOLM	: $("#BOQ_VOLM").val(),
											BOQ_PRICE	: $("#BOQ_PRICE").val(),
											ITM_VOLM	: $("#ITM_VOLM").val(),
											ITM_PRICE	: $("#ITM_PRICE").val(),
										};

						$.ajax({
							type 	: 'POST',
							url 	: "<?php echo site_url('c_comprof/c_bUd93tL15t/svJHD')?>",
							data 	: frmData,
							success : function(response)
							{
								$('#frmAddJH').trigger("reset");
								document.getElementById("idClose2").click();
								//swal(response)
								$('#example').DataTable().ajax.reload();
							}
						});
				    }
				</script>
	    	<!-- ============ END MODAL : ADD JOB =============== -->

            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
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
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        "ajax": "<?php echo site_url('c_comprof/c_bUd93tL15t/get_AllDataJL/?id='.$PRJCODE)?>",
        "type": "POST",
        "lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
        //"lengthMenu": [[50, 100, 200], [50, 100, 200]],
        "columnDefs": [ { targets: [5], className: 'dt-body-center' }
                      ],
        "order": [[ 2, "desc" ]],
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
      	}, 3000);
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
      	}, 3000);
	}

	function clsBarMDL()
	{
		document.getElementById('perProgInf').style.display 	= 'none';
		document.getElementById('idprogbarMDL').style.display 	= 'none';
		document.getElementById('SuccessInf').style.display 	= '';
		document.getElementById('btnDetail1').disabled 			= false;
	}

	function addHd(row)
	{
		document.getElementById("selROW").value	= row;
		collD 	= document.getElementById('urlAddJH'+row).value;
		arrVAR 	= collD.split("~");
		JPARENT = arrVAR[2];
		document.getElementById("JOBPARENT").value	= JPARENT;
		$('#JOBPARENTX').val(JPARENT).trigger('change');
		var PRJCODE		= document.getElementById("PRJCODE").value;
		var deptid		= JPARENT+'~'+PRJCODE;
		
		$.ajax({
			url: '<?php echo site_url('c_project/c_joblistdet/getCODEJOBLIST/?id=')?>',
			type: 'post',
			data: {depart:deptid},
			dataType: 'json',
			success:function(response)
			{
				document.getElementById("JOBCODEID").value	= response;
			}
		});
		document.getElementById('mdlAddJH').click();
		//$("#mdl_addJH").modal('show');
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