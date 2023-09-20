<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 17 Oktober 2021
	* File Name		= v_render.php
	* Location		= -
*/
 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

    $this->db->select('Display_Rows,decFormat');
    $resGlobal = $this->db->get('tglobalsetting')->result();
    foreach($resGlobal as $row) :
    	$Display_Rows = $row->Display_Rows;
    	$decFormat = $row->decFormat;
    endforeach;
    $decFormat		= 2;

    $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
?>
<!DOCTYPE html>
<html>
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers 	= $this->session->userdata['vers'];

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
    		
    		if($TranslCode == 'Group')$Group = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Periode')$Periode = $LangTransl;
    		if($TranslCode == 'Type')$Type = $LangTransl;
    		if($TranslCode == 'Summary')$Summary = $LangTransl;
    		if($TranslCode == 'Detail')$Detail = $LangTransl;
    		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
    		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
    		if($TranslCode == 'Excel')$Excel = $LangTransl;
    	endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Pilih tipe render data";
			$alert2		= "Pilih proyek";
			$alert3		= "Masukan tahun render data Chart Account";
			$alert4		= "Masukan tahun render data journal";
			$sureResOrd	= "Sistem akan melacak serta menyusun data ke dalam NKE System. Lanjutkan ...?";
		}
		else
		{
			$alert1		= "Please select a render type.";
			$alert2		= "Please select a project.";
			$alert3		= "Please input the year of rendering Chart Account data ";
			$alert4		= "Please input the year of rendering journal data";
			$sureResOrd	= "The system will track and compile data into the NKE System. Continue ...?";
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h1_title; ?>
                <small><?php echo $h2_title; ?></small>
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

			<div class="row">
				<div class="col-md-12" id="idprogbar2" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX2" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
            <div class="box box-primary">
            	<div class="box-body chart-responsive">
            		<form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return target_popup(this)">
                	<div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Group</label>
                        <div class="col-sm-10">
                        	<div class="search-table-outter">
	                        	<select name="IMP_CODEX" id="IMP_CODEX" class="form-control select2">
	                            	<option value="0"> --- </option>
						        	<option value="COA"> Master COA</option>
						        	<option value="BOQ"> Master BoQ</option>
						        	<option value="ITM"> Master Item</option>
						        	<option value="JRN"> Journal</option>
						        	<option value="PR"> Purchase Request (PR)</option>
						        	<option value="PO"> Purchase Order (PO)</option>
						        	<option value="AMD"> Amandemen</option>
						        	<option value="SI"> Site Instruction (SI)</option>
						        	<option value="FPRJ"> Project Invoice</option>
	                            </select>
	                        </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                    	/*function selType(thisVal)
                    	{
                    		if(thisVal == 'COA')
                    			$('#DESCRIPT').attr('placeholder','Masukan Tahun');
                    		else if(thisVal == 'BOQ')
                    			$('#DESCRIPT').attr('placeholder','Kosongkan');
                    		else if(thisVal == 'ITM')
                    			$('#DESCRIPT').attr('placeholder','Kosongkan');
                    		else if(thisVal == 'JRN')
                    			$('#DESCRIPT').attr('placeholder','Masukan Tahun');
                    		else if(thisVal == 'PR')
                    			$('#DESCRIPT').attr('placeholder','Kosongkan');
                    		else if(thisVal == 'PO')
                    			$('#DESCRIPT').attr('placeholder','Kosongkan');
                    		else if(thisVal == 'AMD')
                    			$('#DESCRIPT').attr('placeholder','Kosongkan');
                    		else if(thisVal == 'SI')
                    			$('#DESCRIPT').attr('placeholder','Kosongkan');
                    	}*/
                    </script>
                	<div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Project</label>
                        <div class="col-sm-10">
                        	<div class="search-table-outter">
						        <select name="PRJCODE" id="PRJCODE" class="form-control select2">
						        	<option value="0"> --- </option>
									<?php
					                    $getData	= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
				                                        FROM tbl_employee_proj A
				                                        INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
				                                        WHERE A.Emp_ID = '$DefEmp_ID'";
					                    $resGetData = $this->db->query($getData)->result();
					                    foreach($resGetData as $rowData) :
					                        $Emp_ID 	= $rowData->Emp_ID;
					                        $proj_Code 	= $rowData->proj_Code;
					                        $proj_Name 	= $rowData->PRJNAME;
					                        ?>
					                        <option value="<?php echo $proj_Code; ?>"><?php echo "$proj_Code - $proj_Name"; ?></option>
					                        <?php
					                    endforeach;
						            ?>
						        </select>
	                        </div>
                        </div>
                    </div>
                	<div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Year</label>
                        <div class="col-sm-10">
					        <select name="DESCRIPT" id="DESCRIPT" class="form-control select2">
					        	<option value="0"> --- </option>
								<?php
									$YA 	= 2015;
									$YE 	= date('Y');

				                    for($i=$YA; $i<=$YE;$i++)
				                    {
				                        ?>
				                        	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				                        <?php
				                    }
					            ?>
					        </select>
			        		<br>
			        		<br>
		                	<?php
		                		echo '<button type="button" class="btn btn-warning" onClick="renData()" title="Reset Order"><i class="fa fa-sliders"></i>&nbsp;&nbsp;Render</button>';
		                	?>
                        </div>
                    </div>
                </form>
            	</div>
			    <div id="loading_1" class="overlay" style="display:none">
			        <i class="fa fa-refresh fa-spin"></i>
			    </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>

			<div class="row">
				<div class="col-md-12" id="idprogbar3" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX2" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" width="100%" height="40%"></iframe>
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
	    $('#datepicker').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker2').datepicker({
	      autoclose: true
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
		document.getElementById('idprogbar2').style.display = 'none';
	}

	function renData()
	{
		var IMP_CODEX 	= $("#IMP_CODEX").val();		// Render Type
		var PRJCODE 	= $("#PRJCODE").val();
		var DESCRIPT 	= $("#DESCRIPT").val();

		if(IMP_CODEX == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
			});
			return false;
		}

		if(PRJCODE == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
			});
			return false;
		}

		if(IMP_CODEX == 'COA' && DESCRIPT == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
			});
			return false;
		}
		else if(IMP_CODEX == 'JRN' && DESCRIPT == 0)
		{
			swal('<?php echo $alert4; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
			});
			return false;
		}

	    swal({
            text: "<?php echo $sureResOrd; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display 		= '';
				if(IMP_CODEX == 'BOQ')
					document.getElementById('idprogbar2').style.display = '';

			    document.getElementById("progressbarXX").innerHTML	="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
			    document.getElementById("progressbarXX2").innerHTML	="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display 	= '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RENDERDATA';
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= IMP_CODEX;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.DESCRIPT.value 		= DESCRIPT;
				butSubm.submit();
            } 
            else 
            {
                //
            }
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
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>