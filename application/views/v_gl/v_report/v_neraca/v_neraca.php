<?php
/* 
 	* Author   = Dian Hermanto
 	* Create Date  = 08 Maret 2017
 	* File Name  = v_cashflow.php
 	* Location   = -
*/

$this->load->view('template/head');

$appName  	= $this->session->userdata('appName');
$appBody  	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
  	$Display_Rows = $row->Display_Rows;
  	$decFormat = $row->decFormat;
endforeach;
$decFormat    = 2;

$DefEmp_ID    = $this->session->userdata['Emp_ID'];

$Start_DateY  = date('Y');
$Start_DateM  = date('m');
$Start_DateD  = date('d');
$Start_Date   = "$Start_DateM/$Start_DateD/$Start_DateY"; 
$End_Date     = "$Start_DateM/$Start_DateD/$Start_DateY"; 

$DefEmp_ID    = $this->session->userdata['Emp_ID'];

$getproject   = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
		          WHERE 
		          	-- A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
		            -- AND 
		            A.isHO = 1 ORDER BY A.PRJCODE";
$qProject     = $this->db->query($getproject)->result();

function weekNumberOfMonth($theDate)
{
	$tanggal  = (int)date('d',strtotime($theDate));
	$bulan    = (int)date('m',strtotime($theDate));
	$tahun    = (int)date('Y',strtotime($theDate));

	//tanggal 1 tiap bulan
	$firstDate  = mktime(0, 0, 0, $bulan, 1, $tahun);
	$firstWeek  = (int) date('W', $firstDate);

	//tanggal sekarang
	$dateSel  = mktime(0, 0, 0, $bulan, $tanggal, $tahun);
	$weekSel1   = (int) date('W', $dateSel);
	//$weekSel  = $weekSel1 - $firstWeek + 1; // MINGGE KE TIAP BULAN
	$weekSel  = $weekSel1;          // MINGGE KE TIAP TAHUN
	return $weekSel;
}

$theDate  = date('Y-m-d');
$WEEKTO   = weekNumberOfMonth($theDate);
//echo $WEEKTO;
// FIRST AND LAST WEEK REPORT DATE : 30-09-2017
$startDate  = '2018-01-01';
$startWeek  = weekNumberOfMonth($startDate);
$startWeek  = $startWeek + 1;
$lastDate = '2018-12-25';
$lastWeek = weekNumberOfMonth($lastDate);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Dashboard</title>
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<?php
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');

		$ISREAD   = $this->session->userdata['ISREAD'];
		$ISCREATE   = $this->session->userdata['ISCREATE'];
		$ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
		$ISDWONL  = $this->session->userdata['ISDWONL'];
		$LangID   = $this->session->userdata['LangID'];

		$sqlTransl    = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl    = $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode = $rowTransl->MLANG_CODE;
			$LangTransl = $rowTransl->LangTransl;

			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'DateUntilto')$DateUntilto = $LangTransl;
			if($TranslCode == 'Weekto')$Weekto = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Summary')$Summary = $LangTransl;
			if($TranslCode == 'Detail')$Detail = $LangTransl;
			if($TranslCode == 'ViewType')$ViewType = $LangTransl;
			if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
			if($TranslCode == 'Excel')$Excel = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$alert1   = "Anda belum memilih salah satu anggaran / proyek.";
		}
		else
		{
			$alert1   = "You not yet select a budget / project.";
		}
		$form_action  = site_url('c_gl/c_r3p0r77l/r3pN3r4c4av13w/?id='.$this->url_encryption_helper->encode_url($appName));

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
		  	<h1>
		      <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/report_neraca.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
		      <small><?php echo $h3_title; ?></small>
		    </h1>
		    <?php /*?><ol class="breadcrumb">
		      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		      <li><a href="#">Tables</a></li>
		      <li class="active">Data tables</li>
		    </ol><?php */?>
		</section>
		<!-- Main content --> 
		<?php
		  $form_action  = site_url('c_gl/c_r3p0r77l/r3pN3r4c4av13w/?id='.$this->url_encryption_helper->encode_url($appName));
		?>

	    <section class="content">
	        <div class="box box-primary">
	          <div class="box-body chart-responsive">
	              	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return target_popup(this)">
	                  	<div class="form-group">
	                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Budget ?></label>
	                        <div class="col-sm-10">
	                          	<select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $Budget; ?>">
	                              	<option value="0"> --- </option>
	                  				<?php
	                                   foreach($qProject as $rowPRJ) :
					                      	$PRJCODE  = $rowPRJ->PRJCODE;
					                      	$PRJNAME  = $rowPRJ->PRJNAME;
	                                        ?>
	                                        <option value="<?php echo $PRJCODE; ?>">
	                                            <?php echo $PRJNAME; ?>
	                                        </option>
	                                        <?php
	                                    endforeach;
	                                ?>
	                            </select>
	                        </div>
	                    </div>
	                  	<div class="form-group" style="display: none;">
	                        <label for="inputName" class="col-sm-2 control-label"><?php echo $DateUntilto ?></label>
	                        <div class="col-sm-10">
	                          	<div class="input-group date">
	                                <div class="input-group-addon">
	                                <i class="fa fa-calendar"></i>&nbsp;</div>
	                                <input type="text" name="End_Date" class="form-control pull-left" id="datepicker" value="<?php echo $End_Date; ?>" size="10" style="width:120px" >
	                          	</div>
	                        </div>
	                    </div>
	                  	<div class="form-group" style="display:none">
	                    	<label for="inputName" class="col-sm-2 control-label"><?php echo $Weekto ?></label>
	                    	<div class="col-sm-10">
	                      		<select name="WEEKTO" id="WEEKTO" class="form-control" style="max-width:70px" onChange="changeStep(this.value)">
	              				<?php
	                                for($STEP=$startWeek;$STEP<=$lastWeek;$STEP++)
	                                {
	                                ?>
	                                    <option value="<?php echo $STEP; ?>" <?php if($STEP == $WEEKTO) { ?> selected <?php } ?>><?php echo $STEP; ?></option>
	                                    <?php
	                                }
	                            ?>
	                        	</select>
	                    	</div>
	                    </div>
	                  	<div class="form-group" style="display:none">
	                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?></label>
	                        <div class="col-sm-10">
	                          	<label>
	                            <input type="radio" name="CFType" id="CFType1" value="1" checked /> 
	                            <?php echo $Summary ?> <br />
	                            <input type="radio" name="CFType" id="CFType2" value="2" /> 
	                          	<?php echo $Detail ?></label>
	                        </div>
	                    </div>
	                  	<div class="form-group">
	                        <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType ?></label>
	                        <div class="col-sm-10">
	                          	<label>
	                            <input type="radio" name="viewType" value="0" class="flat-red" checked>
	                            <?php echo $WebViewer ?> &nbsp;&nbsp;&nbsp;
	                            <input type="radio" name="viewType" value="1" class="flat-red">
	                            <?php echo $Excel ?>         </label>
	                        </div>
	                    </div>
	                  	<div class="form-group">
	                        <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
	                        <div class="col-sm-10">
	                          	<button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>
	                        </div>
	                    </div>
	                </form>
	          </div>
	        </div>
	    </section>
    </body>
</html>
<script>
  	$(function ()
  	{
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

  	function MoveOption(objSourceElement, objTargetElement) 
  	{ 
	    var aryTempSourceOptions = new Array(); 
	    var aryTempTargetOptions = new Array(); 
	    var x = 0; 
	    
	    //looping through source element to find selected options 
	    for (var i = 0; i < objSourceElement.length; i++)
	    { 
	        if (objSourceElement.options[i].selected)
	      	{ 
	         	//need to move this option to target element 
	         	var intTargetLen = objTargetElement.length++; 
	         	objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
	         	objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
	        } 
	        else
	      	{ 
				//storing options that stay to recreate select element 
				var objTempValues = new Object(); 
				objTempValues.text = objSourceElement.options[i].text; 
				objTempValues.value = objSourceElement.options[i].value; 
				aryTempSourceOptions[x] = objTempValues; 
				x++; 
	      	} 
	    }
    
      	//sorting and refilling target list 
	    for (var i = 0; i < objTargetElement.length; i++)
	    { 
			var objTempValues = new Object(); 
			objTempValues.text = objTargetElement.options[i].text; 
			objTempValues.value = objTargetElement.options[i].value; 
			aryTempTargetOptions[i] = objTempValues; 
	    } 

    	aryTempTargetOptions.sort(sortByText); 

		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
			objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
			objTargetElement.options[i].selected = false; 
		}
    
      	//resetting length of source 
		objSourceElement.length = aryTempSourceOptions.length; 
		//looping through temp array to recreate source select element 
		for (var i = 0; i < aryTempSourceOptions.length; i++) 
		{ 
			objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
			objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
			objSourceElement.options[i].selected = false; 
		}
	}

	function sortByText(a, b) 
	{ 
		if (a.text < b.text) {return -1} 
		if (a.text > b.text) {return 1} 
		return 0; 
	}  
  
  	var url = "<?php echo $form_action; ?>";
  	function target_popup(form)
  	{
		packageelements = document.getElementById('PRJCODE').value;
		if(packageelements == '0')
		{
			swal('<?php echo $alert1; ?>',
			{
				icon:"warning"
			});
			return false;
		}
		title = 'Select Item';
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
  
  	var url = "<?php echo base_url().'index.php/c_itmng/uploadtxt/export_txt';?>";
  	function exporttoexcel()
  	{
    	window.open(url,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
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