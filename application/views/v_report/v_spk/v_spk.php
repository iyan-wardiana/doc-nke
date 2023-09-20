<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2017
 * File Name	= r_usagereq.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 7 Agustus 2017
 * File Name	= v_spk.php
 * Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_color = $this->session->userdata('comp_color');
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateM/$Start_DateD/$Start_DateY";	
$End_Date 		= "$Start_DateM/$Start_DateD/$Start_DateY";	

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject 		= $this->db->query($getproject)->result();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $appName; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
	// $this->load->view('template/topbar');
	// $this->load->view('template/sidebar');
	
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
		if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'All')$All = $LangTransl;
		if($TranslCode == 'DocumentType')$DocumentType = $LangTransl;
		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
		if($TranslCode == 'Excel')$Excel = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary"><br>
   	  <form method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
          <table width="100%">
            <tr>
              <td width="14%" nowrap> <?php echo $ProjectName ?>  <font color="#FFFFFF"><?php echo $ProjectName ?> </font></td>
              <td width="1%" nowrap>:</td>
              <td colspan="2" nowrap>
                <input type="hidden" name="isVPAll" id="isVPAll" value="0" />
                <label>
                    <input type="radio" name="viewProj" id="viewProj_0" value="0" onClick="changeVPType(0);" checked /> 
                    <?php echo $Select ?> 
                    <input type="radio" name="viewProj" id="viewProj_1" value="1" onClick="changeVPType(1)" /> 
                    <?php echo $All ?>         </label>                </td>
            </tr>
            <script>
                function changeVPType(thisVal)
                {
                    if(thisVal == 0)
                    {
                        document.getElementById('projAll01').style.display = '';
                        document.getElementById('isVPAll').value = 0;
                    }
                    else
                    {
                        document.getElementById('projAll01').style.display = 'none';
                        document.getElementById('isVPAll').value = 1;
                    }
                }
            </script>
            <tr id="projAll01">
                <td nowrap valign="top">&nbsp;</td>
                <td nowrap valign="top">:</td>
                <td width="5%" id="projAll02" nowrap>
                    <select multiple="multiple" class="options" size="10" name="pavailable" onChange="MoveOption(this.form.pavailable, this.form.packageelements)">
                    <?php 
                        foreach($qProject as $rowPRJ) :
                            $PRJCODE 	= $rowPRJ->PRJCODE;
                            $PRJNAME	= $rowPRJ->PRJNAME;
                            ?>
                                <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME";?></option>
                            <?php
                        endforeach;
                    ?>
                    </select>                </td>
                <td width="80%" id="projAll03" nowrap>
                    <?php					
                        $getCount		= "tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID'";
                        $resGetCount	= $this->db->count_all($getCount);
                    ?>
                    <select multiple="multiple" name="packageelements[]" id="packageelements" size="10"  style="width: 300px;" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)">
                  </select>                </td>
            </tr>
            <tr id="TransDate">
              <td>  <?php echo $DocumentType ?> </td>
              <td nowrap>:</td>
              <td colspan="2" nowrap>
               		  <!--<div class="input-group date">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input type="text" name="End_Date" class="form-control pull-left" id="datepicker" value="<?php// echo $End_Date; ?>" size="10" style="width:150px" >
                      </div>  -->            
              		<select name="TYPE" id="TYPE" class="form-control" style="max-width:80px" >
                          	<option value="0" >All</option>
                          	<option value="1" >Asli</option>
                          	<option value="2" >Copy</option>
                    </select>
              </td>
            </tr>
            <!--<tr id="TransDate">
              <td>  Date Until</td>
              <td nowrap>:</td>
              <td colspan="2" nowrap>
               		  <div class="input-group date">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input type="text" name="End_Date" class="form-control pull-left" id="datepicker" value="<?php// echo $End_Date; ?>" size="10" style="width:150px" >
                      </div>              </td>
            </tr>-->
            <!--<tr>
                <td valign="top">Type</td>
                <td nowrap valign="top">:</td>
                <td colspan="2" nowrap>
                <label>
                    <input type="radio" name="CFType" id="CFType1" value="1" checked /> 
                    Detail<br />
                    <input type="radio" name="CFType" id="CFType2" value="2" style="display:none" />
                </label>    	</td>
            </tr>-->
            <tr>
                <td> <?php echo $ViewType ?> </td>
                <td nowrap>:</td>
                <td colspan="2" nowrap>
                <label>
                    <input type="radio" name="viewType" id="viewType" value="0" checked /> 
                    <?php echo $WebViewer ?> 
                    <input type="radio" name="viewType" id="viewType" value="1" /> 
                    <?php echo $Excel ?>         </label>    	</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td colspan="2" nowrap>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td colspan="2" nowrap>
                    <!--<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Display Report" /> -->
                    
                    <button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>&nbsp;
                </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td colspan="2" nowrap><hr /></td>
            </tr>
        </table>
      </form>
    </div>
</section>
<?php
	$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if($DefEmp_ID == 'D15040004221')
		echo "<font size='1'><i>$act_lnk</i></font>";
?>
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
</script>
<script>
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
		isVPAll			= document.getElementById('isVPAll').value;
		if(isVPAll == 0)
		{
			packageelements	= document.getElementById('packageelements').value;
			if(packageelements == '')
			{
				alert('Please select one or all project');
				return false;
			}
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