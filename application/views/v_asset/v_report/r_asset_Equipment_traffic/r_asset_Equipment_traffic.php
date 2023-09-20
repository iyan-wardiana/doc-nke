<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2017
 * File Name	= r_usage.php
 * Location		= -
*/
$this->load->view('template/head');
$appName 	= $this->session->userdata('appName');
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
$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Send_Date 		= "$Start_DateM/$Start_DateD/$Start_DateY";	
$Receipt_Date	= "$Start_DateM/$Start_DateD/$Start_DateY";
//$End_Date		= date('m/d/Y', strtotime('+1 days', strtotime($End_Date1))); //operasi penjumlahan tanggal sebanyak 1 hari
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject 		= $this->db->query($getproject)->result();

$getAset 		= "SELECT A.AS_CODE, A.AS_CODE_M, A.AS_NAME, A.AS_DESC FROM tbl_asset_list A ORDER BY A.AS_NAME";
$resAset 		= $this->db->query($getAset)->result();

$vPeriod		= "daily";
if(isset($_POST['vPeriodx']))
{
	$vPeriod 	= $_POST['vPeriodx'];
}
?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
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
		if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'All')$All = $LangTransl;
		if($TranslCode == 'PeriodType')$PeriodType = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Detail')$Detail = $LangTransl;
		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
		if($TranslCode == 'Excel')$Excel = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$SendDate		= "Tanggal Kirim";	
		$ReceiptDate	= "Tanggal Terima";
	}
	else
	{
		$SendDate		= "Send Date";	
		$ReceiptDate	= "Receipt Date";	
	}
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
        <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
            <input type="text" name="vPeriodx" id="vPeriodx" class="textbox" value="<?php echo $vPeriod; ?>" />
            <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
        </form>
   	  	<form method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
          <table width="100%">
            <tr>
                <td width="14%" nowrap><?php echo $ProjectName ?>   <font color="#FFFFFF">Project Name</font></td>
                <td width="1%" nowrap>:</td>
                <td colspan="2" nowrap>
                <input type="hidden" name="isVPAll" id="isVPAll" value="0" />
                <label>
                <input type="radio" name="viewProj" id="viewProj_0" value="0" onClick="changeVPType(0);" checked /> 
                <?php echo $Select ?> 
                <input type="radio" name="viewProj" id="viewProj_1" value="1" onClick="changeVPType(1)" /> 
                <?php echo $All ?>         </label>
                </td>
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
                    <select multiple="multiple" class="options" size="10" name="pavailable" onChange="MoveOption(this.form.pavailable, this.form.packageelements)" style="max-width:280px">
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
                  </select>
            	</td>
            </tr>
            <tr id="projAll04" style="display:none">
                <td nowrap valign="top">&nbsp;</td>
                <td nowrap valign="top">:</td>
                <td width="5%" id="projAll05" nowrap>
                    <select multiple="multiple" class="options" size="10" name="pavailable1" onChange="MoveOption1(this.form.pavailable1, this.form.packageelements1)" style="max-width:250px">
                    <?php 
                        foreach($resAset as $rowAset) :
                            $AS_CODE 	= $rowAset->AS_CODE;
                            $AS_CODE_M	= $rowAset->AS_CODE_M;
                            $AS_NAME	= $rowAset->AS_NAME;
                            $AS_DESC	= $rowAset->AS_DESC;
                            ?>
                                <option value="<?php echo $AS_CODE; ?>"><?php echo "$AS_CODE_M - $AS_DESC";?></option>
                            <?php
                        endforeach;
                    ?>
                    </select>                </td>
                <td width="80%" id="projAll06" nowrap>
                    <?php					
                        $getCount		= "tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID'";
                        $resGetCount	= $this->db->count_all($getCount);
                    ?>
                    <select multiple="multiple" name="packageelements1[]" id="packageelements1" size="10"  style="width: 300px;" ondblclick="MoveOption1(this.form.packageelements1, this.form.pavailable1)">
                  </select>
            	</td>
            </tr>
            <tr style="display:none">
                <td valign="top"><?php echo $PeriodType ?> </td>
                <td nowrap valign="top">:</td>
                <td colspan="2" nowrap>
                    <select name="vPeriod" id="vPeriod" class="form-control" style="max-width:100px" onChange="changeTypePeriod(this.value)" >
						<option value="daily" <?php if($vPeriod == "daily") { ?> selected <?php } ?>>Daily</option>
						<option value="weekly" <?php if($vPeriod == "weekly") { ?> selected <?php } ?>>Weekly</option>
						<option value="monthly" <?php if($vPeriod == "monthly") { ?> selected <?php } ?>>Monthly</option>
                  	</select>
				</td>
            </tr>
            <script>
				function changeTypePeriod(thisValue) 
				{					
					document.getElementById("vPeriodx").value = thisValue;
					document.frmsrch.submitSrch.click();
				}
            </script>
            <?php 
				if($vPeriod == "daily") {
			?>
            <tr>
              	<td><?php echo $SendDate ?>   </td>
           	  <td nowrap>:</td>
              	<td colspan="2" nowrap>
                  <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="SendDate" class="form-control pull-left" id="datepicker1" value="<?php echo $Send_Date; ?>" size="10" style="width:150px" >
                  </div>
				</td>
            </tr>
            <tr>
              	<td><?php echo $ReceiptDate ?>   </td>
              	<td nowrap>:</td>
              	<td colspan="2" nowrap>
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="ReceiptDate" class="form-control pull-left" id="datepicker2" value="<?php echo $Receipt_Date; ?>" size="10" style="width:150px" >
                    </div>
				</td>
            </tr>
            <?php 
				}
				if($vPeriod == "weekly") {
			?>
            <tr>
              	<td><?php echo $StartDate ?>   </td>
              	<td nowrap>:</td>
              	<td colspan="2" nowrap>
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="Start_Date" class="form-control pull-left" id="datepicker3" value="<?php echo $Start_Date; ?>" size="10" style="width:150px" >
                    </div>
				</td>
            </tr>
            <?php 
				}
				if($vPeriod == "monthly") {
			?>
            <tr>
              	<td><?php echo $EndDate ?>   </td>
              	<td nowrap>:</td>
              	<td colspan="2" nowrap>
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="End_Date" class="form-control pull-left" id="datepicker4" value="<?php echo $End_Date; ?>" size="10" style="width:150px" >
                    </div>
				</td>
            </tr>
            <?php 
				}
			?>
            <tr style="display:none">
                <td valign="top"><?php echo $Type ?> </td>
                <td nowrap valign="top">:</td>
                <td colspan="2" nowrap>
                <label>
                    <input type="radio" name="CFType" id="CFType1" value="1" checked /> 
                    <?php echo $Detail ?> <br />
                    <input type="radio" name="CFType" id="CFType2" value="2" style="display:none" />
                </label>    	</td>
            </tr>
            <tr>
                <td><?php echo $ViewType ?>  </td>
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
                    <!--<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Display Report" />-->
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
</body>
</html>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
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
    $('#datepicker1').datepicker({
      autoclose: true
    });
    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });
    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });
    //Date picker
    $('#datepicker4').datepicker({
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
	
	function MoveOption1(objSourceElement, objTargetElement) 
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
		w = 1000;
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
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>