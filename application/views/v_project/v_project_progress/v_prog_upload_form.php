<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 September 2019
 * File Name	= v_prog_upload_form.php
 * Location		= -
*/
error_reporting(0);

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

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
$PRJNAME		= '';
$PRJNAME1		= '';
$sqlPRJ 		= "SELECT A.PRJNAME, B.PRJNAME AS PRJNAME1 FROM tbl_project A
					LEFT JOIN tbl_project_budg B ON A.PRJCODE = B.PRJCODE_HO
					WHERE B.PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJNAME1 	= $rowPRJ->PRJNAME1;
	$PRJNAME	= "$PRJNAME1 ($PRJNAME)";
endforeach;
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
		endforeach;

		if($LangID == 'IND')
		{
			$alert1	= "Silahkan pilih file excel yang akan Anda import.";
			$alert2	= "Proses ini akan me-reset data material sebelumnya. Anda Yakin?";
			$alert3	= "Anda Yakin?";
			$alert4	= "Proses ini akan me-reset anggaran sebelumnya.";
			$alert5	= "Baik! Proses akan dilanjutkan. Mohon tunggu beberapa saat.";
			$alert6	= "Baik! Proses reset master budget akan dibatalkan.";
			$alert7	= "Deskripsi tidak boleh kosong.";
		}
		else
		{
			$alert1	= "Please select an excel file that will you uploaded.";
			$alert2	= "This process will reset the previous material data. Are You Sure?";
			$alert3	= "Are you sure?";
			$alert4	= "This process will reset the previous budget.";
			$alert5	= "Well! The process will be processed. Please wait a few moments.";
			$alert6	= "Well! The budget master reset process will be canceled.";
			$alert7	= "The description cannot be empty.";
		}
			
		$isLoadDone_1	= 1;
		$PROGG_CODEX	= '';
		$sqlPRGGC		= "tbl_projprogres WHERE proj_Code = '$PRJCODE'";
		$resPRGGC		= $this->db->count_all($sqlPRGGC);

		// CHECK STATUS
		if(isset($_POST['PROGG_CODEX']))
		{
			$PROGG_CODEY	= $_POST['PROGG_CODEX'];
			
			$sqlCountPRGG	= "tbl_progg_uphist WHERE PROGG_STAT = 2 AND PROGG_CODE = '$PROGG_CODEY'";
			$resCountPRGG	= $this->db->count_all($sqlCountPRGG);	
		}
		else
		{
			$resCountPRGG	= 1;
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $mnName; ?>
			    <small><?php echo $PRJNAME1; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

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
		        <div class="col-md-12">
		            <div class="box box-primary">
		                <div class="box-header with-border" style="display:none">               
		              		<div class="box-tools pull-right">
		                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		                    </div>
		                </div>
		                <br>
		                <div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                        <div class="callout callout-info">
		                            <?php echo $alert1; ?>
		                        </div>           
		                    </div>
		                </div>
		                <div class="box-body chart-responsive">
		                    <form name="myformupload" id="myformupload" class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo $form_action; ?>" onSubmit="return chcekFile();" >
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
		                                <textarea class="form-control" name="PROGG_DESC"  id="PROGG_DESC"><?php echo $PROGG_DESC; ?></textarea>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                            <div class="col-sm-10">
		                                <div class="alert alert-danger alert-dismissible">
		                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                    <h4><i class="icon fa fa-ban"></i> Peringatan ... !!!</h4>
		                                    1. Hindari penggunaan SPASI pada nama file excel (*.xls) yang akan diupload.<br>
		                                    2. Setelah upload file, tidak otomatis merubah data yang ada.<br>
		                                    3. Perubahan data akan terjadi setelah file ini diproses.<br>
		                                    4. Upload ini digunakan untuk mengimport data item proyek yang bersangkutan.
		                                  </div>
		                            </div>
		                        </div>
		                        <?php
									if($isUploaded == 1)
									{
										$boxCol	= "success";
									}
									else
									{
										$boxCol	= "danger";
									}
									
									if($isUploaded == 1)
									{
									?>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                                    <div class="col-sm-10">
		                                        <div class="callout callout-<?php echo $boxCol; ?>">
		                                            <?php echo $message; ?>
		                                        </div>           
		                                    </div>
		                                </div>
		                        	<?php
									}
								?>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
		                                <!--<input type="submit" value="Upload File" class="btn btn-warning" style="width:120px;" />&nbsp;&nbsp;-->
		                                <button class="btn btn-primary"><i class="glyphicon glyphicon-upload"></i></button>
		                                <?php 
		                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                                ?>
		                            </div>
		                        </div>
		                    </form>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div class="box">
		        <div class="box-body">
		            <div class="search-table-outter">
		                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
		                    <input type="text" name="PROGG_CODEX" id="PROGG_CODEX" class="textbox" value="<?php echo $PROGG_CODEX; ?>" />
		                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
		                </form>
		                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                    <thead>
		                        <tr>
		                            <th width="2%">No.</th>
		                            <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
		                            <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date ?> </th>
		                            <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Project ?> </th>
		                            <th width="61%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?> </th>
		                            <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $User ?> </th>
		                            <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
		                            <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $View ?> </th>
		                      </tr>
		                    </thead>
		                    <tbody>
							<?php
		                        $sqlPRGGH	= "tbl_progg_uphist WHERE PROGG_PRJCODE = '$PRJCODE'";
		                        $resPRGGH	= $this->db->count_all($sqlPRGGH);
		                        $i = 0;
		                        $j = 0;
		                        if($resPRGGH >0)
		                        {
		                            $sqlPRGGHV	= "SELECT * FROM tbl_progg_uphist WHERE PROGG_PRJCODE = '$PRJCODE'";
		                            $resPRGGHV	= $this->db->query($sqlPRGGHV)->result();
		                            foreach($resPRGGHV as $row) :
		                                $myNewNo 		= ++$i;
		                                $PROGG_CODE 	= $row->PROGG_CODE;
		                                $PROGG_PRJCODE 	= $row->PROGG_PRJCODE;
		                                $PROGG_DATE 	= $row->PROGG_DATE;
		                                $PROGG_DESC	 	= $row->PROGG_DESC;
		                                $PROGG_FN	 	= $row->PROGG_FN;
		                                $PROGG_STAT	 	= $row->PROGG_STAT;
		                                $PROGG_USER	 	= $row->PROGG_USER;
		                                
		                                if($PROGG_STAT == 0)
		                                {
		                                    $PROGG_STATD = 'fake';
		                                    $STATCOL	= 'danger';
		                                    $disabled1	= 1;	// Icon Process
		                                    $disabled2	= 1;	// Icon Flag
		                                }
		                                elseif($PROGG_STAT == 1)
		                                {
		                                    $PROGG_STATD = 'New';
		                                    $STATCOL	= 'warning';
		                                    $disabled1	= 0;	// Icon Process
		                                    $disabled2	= 1;	// Icon Flag
		                                }
		                                elseif($PROGG_STAT == 2)
		                                {
		                                    $PROGG_STATD = 'success';
		                                    $STATCOL	= 'success';
		                                    $disabled1	= 1;	// Icon Process
		                                    $disabled2	= 0;	// Icon Flag
		                                }
		                                elseif($PROGG_STAT == 3)
		                                {
		                                    $PROGG_STATD = 'changed';
		                                    $STATCOL	= 'primary';
		                                    $disabled1	= 1;	// Icon Process
		                                    $disabled2	= 1;	// Icon Flag
		                                }
		                                    
		                                if ($j==1) {
		                                    echo "<tr class=zebra1>";
		                                    $j++;
		                                } else {
		                                    echo "<tr class=zebra2>";
		                                    $j--;
		                                }
		                                ?> 
		                                        <td> <?php echo $myNewNo; ?>.</td>
		                                        <td nowrap> <?php echo $PROGG_CODE;?></td>
		                                        <td nowrap> <?php echo $PROGG_DATE; ?> </td>
		                                        <td> <?php echo $PROGG_PRJCODE; ?> </td>
		                                        <td> <?php echo $PROGG_DESC; ?> </td>
		                                        <td> <?php echo $PROGG_USER; ?> </td>
		                                        <td nowrap style="text-align:center"> 
		                                        	<span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
														<?php
		                                                    echo $PROGG_STATD;
		                                                 ?>
		                                             </span>
		                                        </td>
		                                        <td style="text-align:center" nowrap>
		                                            <?php
		                                                $FileName 	= $PROGG_FN;
		                                                $fileAttach	= base_url('import_excel/import_proggress/'.urldecode($FileName));
		                                                $collLink	= "$fileAttach~$FileName";
		                                                $linkDL1 	= site_url('c_project/c_uPpR09r355/downloadFile/?id='.$this->url_encryption_helper->encode_url($collLink));
		                                                $secVWlURL	= site_url('c_project/c_uPpR09r355/view_itemup/?id='.$this->url_encryption_helper->encode_url($PROGG_CODE));
		                                            ?>
		                                            <input type="hidden" name="secVWlURL_<?php echo $myNewNo; ?>" id="secVWlURL_<?php echo $myNewNo; ?>" value="<?php echo $secVWlURL; ?>"/>
		                                            <input type="hidden" name="PROGG_CODE<?php echo $myNewNo; ?>" id="PROGG_CODE<?php echo $myNewNo; ?>" value="<?php echo $PROGG_CODE; ?>"/>
		                                            <!-- <a href="javascript:void(null);" onClick="getPROGG_CODE(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-warning btn-xs" title="Process" <?php if($disabled1 == 1) { ?> disabled="disabled" <?php } ?>>
		                                                <i class="glyphicon glyphicon-refresh"></i>
		                                            </a> -->
						                            <a href="javascript:void(null);" onClick="procIMP(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-warning btn-xs" title="Process"<?php if($disabled1 == 1) { ?> disabled="disabled" <?php } ?>>
				                                        <i class="glyphicon glyphicon-refresh"></i>
				                                    </a>
		                                            <a href="javascript:void(null);" onClick="viewbillofqty(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-success btn-xs" <?php if($disabled2 == 1) { ?> disabled="disabled" <?php } ?>>
		                                                <i class="glyphicon glyphicon-flag"></i>
		                                            </a>
		                                            <a href="<?php echo $fileAttach; ?>" data-skin="skin-green" class="btn btn-primary btn-xs" title="Download">
		                                                <i class="glyphicon glyphicon-download-alt"></i>
		                                            </a>
		                                            <a href="javascript:void(null);" onClick="viewbillofqty(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-info btn-xs" title="View Document">
		                                                <i class="fa fa-eye"></i>
		                                            </a>
		                                        </td>
		                                    </tr>
		                                <?php 
		                            endforeach; 
		                        }
		                    ?>
		                    </tbody>
		                </table>
		            </div>
		        </div>
		        <div id="loading_1" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
		            <i class="fa fa-refresh fa-spin"></i>
		        </div>
			</div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="width: 100%; display: none;"></iframe>
	</body>
</html>
<?php
	$secDelIcut = base_url().'index.php/c_gl/c_ch1h0fbeart/getPercent/?id=';
	$percLink 	= "$secDelIcut~$PRJCODE";

	$securlImp 	= base_url().'index.php/__l1y/impCOA/?id=';
	$impLink 	= "$securlImp~$PRJCODE";

	$frmAct 	= base_url().'index.php/c_comprof/c_bUd93tL15t/do_uploadJL/?id=';
?>
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
	
	function chcekFile()
	{
		var userfile1 = document.getElementById('userfile1').value;
		
		if(userfile1 == '')
		{
			alert('Please insert an excel file which will you uploaded.');
			return false;
		}
		
		var myExt	= getFileExtension(userfile1);
		
		if(myExt != 'xls' && myExt != 'xlsx')
		{
			alert('You can upload xls or xlsx File Type only.');
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
					document.getElementById('idprogbar').style.display = '';
				    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
					document.getElementById('loading_1').style.display = '';
					//document.getElementById('loading_2').style.display = '';
					var PRJCODE = '<?php echo $PRJCODE; ?>';
					var collID1	= '<?php echo $impLink; ?>';
					var IMPCODE = document.getElementById('PROGG_CODE'+row).value;
					var collID	= collID1;
				    var myarr 	= collID.split("~");
				    var url 	= myarr[0];
					var perc = 0;

					var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
					$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
					$("#myiFrame")[0].contentWindow.PRJPERIOD.value 	= "<?php echo $PRJPERIOD; ?>";
					$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= IMPCODE;
					$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'SCURVE';
					butSubm.submit();
				});
			} 
			else 
			{
				//swal("<?php echo $alert6; ?>", {icon: "error"})
			}
        });
	}
	
	function getPROGG_CODE(row)
	{
		var result = confirm("<?php echo $alert2; ?>");
		if (result)
		{
			PROGG_CODE = document.getElementById('PROGG_CODE'+row).value;
			document.getElementById('PROGG_CODEX').value = PROGG_CODE;
			document.getElementById('loading_1').style.display = '';
			document.frmsrch.submitSrch.click();
		}
	}
	
	function viewbillofqty(thisVal)
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