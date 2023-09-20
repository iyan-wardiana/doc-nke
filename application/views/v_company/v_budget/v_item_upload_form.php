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
			if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
		    $alert1 = "Silahkan pilih file excel yang akan Anda upload.";
		    $alert2 = "Anda hanya dapat mengupload file xlsx.";
			$alert3	= "Anda Yakin?";
			$alert4	= "Proses ini akan me-reset anggaran sebelumnya.";
			$alert5	= "Baik! Proses akan dilanjutkan. Mohon tunggu beberapa saat.";
			$alert6	= "Baik! Proses reset master budget akan dibatalkan.";
			$alert7	= "Deskripsi tidak boleh kosong.";
		}
		else
		{
		    $alert1 = "Please select an excel file that will you uploaded.";
		    $alert2 = "You can upload xlsx File Type only.";
			$alert3	= "Are you sure?";
			$alert4	= "This process will reset the previous budget.";
			$alert5	= "Well! The process will be processed. Please wait a few moments.";
			$alert6	= "Well! The budget master reset process will be canceled.";
			$alert7	= "The description cannot be empty.";
		}
		$isLoadDone_1	= 1;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    Upload
			    <small><?php echo $h3_title; ?></small>
			  </h1>
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
				<div class="col-md-6">
					<div class="box box-primary">
						<div class="box-header with-border">
							<i class="fa fa-cloud-upload"></i>
							<h3 class="box-title">Upload File</h3>
						</div>
						<div class="box-body">
							<form name="myformupload" id="myformupload" class="form-horizontal" enctype="multipart/form-data" method="post" >
								<div class="box-body">
					                <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label">Pilih File</label>
			                          	<div class="col-sm-10">
			                            <input type="text" style="display:none" name="isUploaded" id="isUploaded" value="<?php echo $isUploaded; ?>" />
			                       		<input type="text" style="display:none" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		                       			<input type="text" style="display:none" name="PRJPERIOD" id="PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" />
			                            <input type="file" name="userfile" id="userfile" class="filestyle" data-buttonName="btn-primary"/>
			                          	</div>
			                        </div>
			                        <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="ITMH_DESC"  id="ITMH_DESC" style="height:70px"></textarea>
                                        </div>
                                    </div>
		                        	<div class="pull-right">
		                        		<button type='submit' class='btn btn-primary'><i class='glyphicon glyphicon-upload'></i></button>
		                                <?php
		                                	echo "&nbsp&nbsp;";
		                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                                ?>
	                                </div>
					            </div>
		                    </form>
		                    <section id="hand">
								<h4 class="page-header"></h4>
								<div class="row fontawesome-icon-list">
									<div class="col-sm-12">
		                                <div class="alert alert-danger alert-dismissible">
		                                    <h4><i class="icon fa fa-ban"></i> Peringatan ... !!!</h4>
		                                    1. Hindari penggunaan SPASI pada nama file excel (*.xls) yang akan diupload.<br>
		                                    2. Setelah upload file, tidak otomatis merubah data yang ada.<br>
		                                    3. Perubahan data akan terjadi setelah file ini diproses.
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
				<div class="col-md-6">
					<div class="box box-warning">
						<div class="box-header with-border">
							<i class="fa fa-list"></i>
							<h3 class="box-title">Daftar File</h3>
						</div>
						<div class="box-body">
			                <table id="example" class="table table-bordered table-striped" width="100%">
			                    <thead>
			                        <tr>
			                            <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date ?> </th>
			                            <th width="61%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?> </th>
			                            <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
			                            <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $View ?> </th>
			                      </tr>
			                    </thead>
			                    <tbody>
			                    </tbody>
			                </table>
						</div>
						<div id="loading_2" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
				            <i class="fa fa-refresh fa-spin"></i>
				        </div>
					</div>
				</div>
			</div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="display:none"></iframe>
	</body>
</html>
<?php
	$secDelIcut = base_url().'index.php/c_gl/c_ch1h0fbeart/getPercent/?id=';
	$percLink 	= "$secDelIcut~$PRJCODE";

	$securlImp 	= base_url().'index.php/__l1y/impCOA/?id=';
	$impLink 	= "$securlImp~$PRJCODE";

	$frmAct 	= base_url().'index.php/c_comprof/c_bUd93tL15t/do_upload/?id=';
?>
<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_comprof/c_bUd93tL15t/get_AllDataITM/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [2], className: 'dt-body-center' },
						{ "width": "100px", "targets": [1] }
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );

	$(document).ready(function() {
		$('#myformupload').submit(function(e){
   			e.preventDefault();
			var userfile1 = document.getElementById('userfile').value;
			var ITMH_DESC = document.getElementById('ITMH_DESC').value;

			if(userfile1 == '')
			{
				swal("", "<?php echo $alert1; ?>", "warning");
				return false;
			}
			
			var myExt	= getFileExtension(userfile1);
			
			if(myExt != 'xlsx')
			{
				swal("", "<?php echo $alert2; ?>", "error");
				return false;
			}

			if(ITMH_DESC == '')
			{
				swal("", "<?php echo $alert7; ?>", "warning");
				return false;
			}

		   	var form = $(this);
		    var url = '<?php echo $frmAct; ?>';
		    var data = $('form').serialize();
		    var formData = new FormData($('#myformupload')[0]);
		    formData.append('userfile', $('input[type=file]')[0].files[0]);

			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: url,
				data: formData,
				contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
    			processData: false, // NEEDED, DON'T OMIT THIS
				success: function(data)
				{
					//alert(data)
					//$('#example2').data.reload();
					document.getElementById('userfile').value 	= '';
					document.getElementById('ITMH_DESC').value 	= '';
					$('#example').DataTable().ajax.reload();
				}
			});
		});	
	});	
	
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
		$('#example').DataTable().ajax.reload();
		var timer = setInterval(function()
		{
	       	clsBar();
      	}, 3000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display = 'none';
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
					document.getElementById('loading_2').style.display = '';
					var PRJCODE = '<?php echo $PRJCODE; ?>';
					var collID1	= '<?php echo $impLink; ?>';
					var IMPCODE = document.getElementById('ITMH_CODE'+row).value;
					var collID	= collID1;
				    var myarr 	= collID.split("~");
				    var url 	= myarr[0];
					var perc = 0;

					var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
					$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
					$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= IMPCODE;
					$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'ITM';
					butSubm.submit();
				});
			} 
			else 
			{
				//swal("<?php echo $alert6; ?>", {icon: "error"})
			}
        });
	}
	
	function viewItm(thisVal)
	{
		var urlVWDoc = document.getElementById('secVWlURL_'+thisVal).value;
		title = 'View Bill of Quantity';
		w = 780;
		h = 550;
		swal('Sorry, this page is under construction.',
		{
			icon:"warning",
		});
		return false;
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
	//______$this->load->view('template/aside');

	//______$this->load->view('template/js_data');

	//______$this->load->view('template/foot');
?>