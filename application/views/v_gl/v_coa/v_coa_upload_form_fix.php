<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Mei 2018
 * File Name	= v_item_upload_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
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
		$this->load->view('template/topbar');
		$this->load->view('template/sidebar');
		
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
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;	
			if($TranslCode == 'Back')$Back = $LangTransl;	
			if($TranslCode == 'Upload')$Upload = $LangTransl;
			
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'User')$User = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'View')$View = $LangTransl;
			if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
		    $alert1 = "Silahkan pilih file excel yang akan Anda upload.";
		    $alert2 = "Anda hanya dapat mengupload file xls atau xlsx.";
			$alert3	= "Anda Yakin?";
			$alert4	= "Proses ini akan me-reset COA sebelumnya.";
			$alert5	= "Baik! Proses akan dilanjutkan. Mohon tunggu beberapa saat.";
			$alert6	= "Baik! Proses reset master COA akan dibatalkan.";
		}
		else
		{
		    $alert1 = "Please select an excel file that will you uploaded.";
		    $alert2 = "You can upload xls or xlsx File Type only.";
			$alert3	= "Are you sure?";
			$alert4	= "This process will reset the previous COA.";
			$alert5	= "Well! The process will be processed. Please wait a few moments.";
			$alert6	= "Well! The COA master reset process will be canceled.";
		}
		$isLoadDone_1	= 1;
	?>
	<body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
				    <?php echo $h2_title; ?>
				    <small><?php echo $h3_title; ?></small>
				  </h1>
			</section>
			<!-- Main content -->
			<style>
				.search-table, td, th {
					border-collapse: collapse;
				}
				.search-table-outter { overflow-x: scroll; }
				
			    a[disabled="disabled"] {
			        pointer-events: none;
			    }
			</style>

			<section class="content">
				<div class="row">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">Upload File</h3>
							</div>
							<div class="box-body">
								<form name="myformupload" id="myformupload" class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo $form_action; ?>" onSubmit="return chcekFile();" >
									<div class="box-body">
						                <div class="form-group">
				                          	<label for="inputName" class="col-sm-2 control-label">Upload File</label>
				                          	<div class="col-sm-10">
				                            <input type="text" style="display:none" name="isUploaded" id="isUploaded" value="<?php echo $isUploaded; ?>" />
				                       		<input type="text" style="display:none" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
				                            <input type="file" name="userfile" id="userfile1" class="filestyle" data-buttonName="btn-primary"/>
				                          	</div>
				                        </div>
				                        <div class="form-group">
				                            <label for="inputName" class="col-sm-2 control-label">Description</label>
				                            <div class="col-sm-10">
				                                <textarea class="form-control" name="COAH_DESC" id="COAH_DESC" style="height:70px"><?php echo $COAH_DESC; ?></textarea>
				                            </div>
				                        </div>
			                        	<div class="pull-right">
			                                <?php
			                                	echo "<button class='btn btn-primary'><i class='glyphicon glyphicon-upload'></i></button>&nbsp&nbsp;";
			                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
			                                ?>
		                                </div>
						            </div>
			                    </form>
			                    <section id="hand">
									<h4 class="page-header"></h4>
									<div class="row fontawesome-icon-list">
										<div class="col-sm-12">
											<div class="alert alert-warning alert-dismissible">
							                	<h4><i class="icon fa fa-check"></i> Alert!</h4>
							                	Success alert preview. This alert is dismissable.
							              	</div>
										</div>
									</div>
								</section>
							</div>
							<div id="loading_1" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
					            <i class="fa fa-refresh fa-spin"></i>
					        </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6" id="idprogbar">
							<div class="cssProgress">
						      	<div class="cssProgress">
								    <div class="progress3">
										<div id="progressbarXX" style="text-align: center;">0%</div>
									</div>
									<span class="cssProgress-label" id="information" ></span>
								</div>
						    </div>
						</div>
						<div class="col-md-6">
							<div class="box box-warning">
								<div class="box-header with-border">
									<i class="fa fa-list"></i>
									<h3 class="box-title">Daftar File</h3>
								</div>
								<div class="box-body">
					                <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					                    <thead>
					                        <tr>
					                        	<th width="2%">No.</th>
					                            <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
					                            <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date ?> </th>
					                            <th width="61%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?> </th>
					                            <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
					                            <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $View ?> </th>
					                      </tr>
					                    </thead>
					                    <tbody>
										<?php
					                        $sqlITMH	= "tbl_coa_uphist WHERE COAH_PRJCODE = '$PRJCODE'";
					                        $resITMH	= $this->db->count_all($sqlITMH);
					                        $i = 0;
					                        $j = 0;
					                        if($resITMH >0)
					                        {
					                            $sqlITMHV	= "SELECT * FROM tbl_coa_uphist WHERE COAH_PRJCODE = '$PRJCODE'";
					                            $resITMHV	= $this->db->query($sqlITMHV)->result();
					                            foreach($resITMHV as $row) :
					                                $myNewNo 		= ++$i;
					                                $COAH_CODE 		= $row->COAH_CODE;
					                                $COAH_PRJCODE 	= $row->COAH_PRJCODE;
					                                $COAH_DATE 		= $row->COAH_DATE;
					                                $COAH_DESC	 	= $row->COAH_DESC;
					                                $COAH_FN	 	= $row->COAH_FN;
					                                $COAH_STAT	 	= $row->COAH_STAT;
					                                $COAH_USER	 	= $row->COAH_USER;
					                                
					                                if($COAH_STAT == 0)
					                                {
					                                    $COAH_STATD = 'fake';
					                                    $STATCOL	= 'danger';
					                                    $disabled1	= 1;	// Icon Process
					                                    $disabled2	= 1;	// Icon Flag
					                                }
					                                elseif($COAH_STAT == 1)
					                                {
					                                    $COAH_STATD = 'New';
					                                    $STATCOL	= 'warning';
					                                    $disabled1	= 0;	// Icon Process
					                                    $disabled2	= 1;	// Icon Flag
					                                }
					                                elseif($COAH_STAT == 2)
					                                {
					                                    $COAH_STATD = 'success';
					                                    $STATCOL	= 'success';
					                                    $disabled1	= 1;	// Icon Process
					                                    $disabled2	= 0;	// Icon Flag
					                                }
					                                elseif($COAH_STAT == 3)
					                                {
					                                    $COAH_STATD = 'changed';
					                                    $STATCOL	= 'primary';
					                                    $disabled1	= 1;	// Icon Process
					                                    $disabled2	= 1;	// Icon Flag
					                                }
					                                ?>
					                                	<tr>
					                                		<td> <?php echo $myNewNo; ?>.</td>
					                                        <td nowrap> <?php echo $COAH_CODE;?></td>
					                                        <td nowrap> <?php echo $COAH_DATE; ?> </td>
					                                        <td> <?php echo $COAH_DESC; ?> </td>
					                                        <td nowrap style="text-align:center"> 
					                                        	<span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
																	<?php
					                                                    echo $COAH_STATD;
					                                                 ?>
					                                             </span>
					                                        </td>
					                                        <td style="text-align:center" nowrap>
					                                            <?php
					                                                $FileName 	= $COAH_FN;
					                                                $fileAttach	= base_url('import_excel/import_coa/'.urldecode($FileName));
					                                                $collLink	= "$fileAttach~$FileName";
					                                                $linkDL1 	= site_url('c_gl/c_ch1h0fbeart/downloadFile/?id='.$this->url_encryption_helper->encode_url($collLink));
					                                                $secVWlURL	= site_url('c_gl/c_ch1h0fbeart/view_coaup/?id='.$this->url_encryption_helper->encode_url($COAH_CODE));

					                                                $secvwPRJ	= site_url('c_comprof/c_bUd93tL15t/c_project_progress/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					                                            ?>
					                                            <input type="hidden" name="secVWlURL_<?php echo $myNewNo; ?>" id="secVWlURL_<?php echo $myNewNo; ?>" value="<?php echo $secVWlURL; ?>"/>
					                                            <input type="hidden" name="COAH_CODE<?php echo $myNewNo; ?>" id="COAH_CODE<?php echo $myNewNo; ?>" value="<?php echo $COAH_CODE; ?>"/>
					                                            <a href="javascript:void(null);" onClick="procIMP(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-warning btn-xs" title="Process" <?php if($disabled1 == 1) { ?> disabled="disabled" <?php } ?>>
					                                                <i class="glyphicon glyphicon-refresh"></i>
					                                            </a>
					                                            <a href="javascript:void(null);" onClick="viewcoa(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-success btn-xs" title="Processed" <?php if($disabled2 == 1) { ?> disabled="disabled" <?php } ?>>
					                                                <i class="glyphicon glyphicon-flag"></i>
					                                            </a>
					                                            <a href="<?php echo $fileAttach; ?>" class="btn btn-primary btn-xs" title="Download">
					                                                <i class="glyphicon glyphicon-download-alt"></i>
					                                            </a>
					                                            <a href="javascript:void(null);" onClick="viewcoa(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-info btn-xs" title="View Document">
					                                                <i class="fa fa-eye"></i>
					                                            </a>
					                                        </td>
					                                    </tr>
					                                <?php 
					                            endforeach;
				                                /*$remP = 9 - $myNewNo;
				                                if($myNewNo <= 9)
				                                {
				                                	for($i=1;$i<=$remP;$i++)
				                                	{
				                                		?>
					                                	<tr>
					                                        <td>&nbsp;</td>
					                                        <td nowrap style="text-align:center">&nbsp;</td>
					                                        <td style="text-align:center" nowrap>&nbsp;</td>
					                                    </tr>
					                                    <?php
					                                }
				                                }*/
					                        }
					                    ?>
					                    </tbody>
					                </table>
								</div>
								<div id="loading_2" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
						            <i class="fa fa-refresh fa-spin"></i>
						        </div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<iframe id="myiFrame" src="<?php echo base_url('c_gl/c_ch1h0fbeart/impCOA' ) ?>" style='display: none;'></iframe>
		</div>
	</body>
</html>
<?php
	$secDelIcut = base_url().'index.php/c_gl/c_ch1h0fbeart/getPercent/?id=';
	$percLink 	= "$secDelIcut~$PRJCODE";

	$securlImp 	= base_url().'index.php/c_gl/c_ch1h0fbeart/impCOA/?id=';
	$impLink 	= "$securlImp~$PRJCODE";
?>
<script>
	$(function () 
	{
		$("#example1").DataTable();
		$('#example2').DataTable({
			"order": [[ 1, "asc" ]],
		  	"paging": true,
		  	"lengthChange": false,
		  	"searching": false,
		  	"ordering": true,
		  	"info": true,
		  	"autoWidth": false
		});
	});
	
	function chcekFile()
	{
		var userfile1 = document.getElementById('userfile1').value;
		
		if(userfile1 == '')
		{
			swal("", "<?php echo $alert1; ?>", "warning");
			return false;
		}
		
		var myExt	= getFileExtension(userfile1);
		
		if(myExt != 'xls' && myExt != 'xlsx')
		{
			swal("", "<?php echo $alert2; ?>", "error");
			return false;
		}
	}
	
	function getFileExtension(filename)
	{
	  var ext = /^.+\.([^.]+)$/.exec(filename);
	  return ext == null ? "" : ext[1];
	}

	function procIMP(row)
	{
        swal({
            title: "<?php echo $alert3; ?>",
            text: "<?php echo $alert4 ?>",
            icon: "warning",
            buttons: ["Tidak", "Ya"],
		})
		.then((willDelete) => {
			if (willDelete) 
			{
				swal("<?php echo $alert5; ?>", {icon: "success"})
				.then((value) => {
					document.getElementById('loading_1').style.display = '';
					document.getElementById('loading_2').style.display = '';
					var PRJCODE = '<?php echo $PRJCODE; ?>';
					var collID1	= '<?php echo $impLink; ?>';
					var IMPCODE = document.getElementById('COAH_CODE'+row).value;
					var collID	= collID1;
				    var myarr 	= collID.split("~");
				    var url 	= myarr[0];
					var perc = 0;

					var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
					$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
					$("#myiFrame")[0].contentWindow.COAH_CODEX.value 	= IMPCODE;
					butSubm.submit();
				});
			} 
			else 
			{
				//swal("<?php echo $alert6; ?>", {icon: "error"})
			}
        });
	}
	
	function viewcoa(thisVal)
	{
		var urlVWDoc = document.getElementById('secVWlURL_'+thisVal).value;
		title = 'View Bill of Quantity';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlVWDoc, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>