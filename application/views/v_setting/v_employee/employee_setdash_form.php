<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2017
 * File Name	= employee_setdash_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

//$getdashboard 	= "SELECT DS_ROW, DS_TYPE, DS_NOTES FROM tbl_dash_sett ORDER BY DS_NOTES ASC";
//$qDashboard 	= $this->db->query($getdashboard)->result();

$getdocAuth 	= "SELECT DS_ROW, DS_TYPE, DS_NOTES FROM tbl_dash_sett 
						WHERE DS_TYPE NOT IN (SELECT A.DS_TYPE FROM tbl_dash_sett_emp A INNER JOIN tbl_dash_sett B ON B.DS_TYPE = A.DS_TYPE WHERE A.EMP_ID = '$Emp_ID')
					ORDER BY DS_NOTES ASC";
$qDashboard 	= $this->db->query($getdocAuth)->result();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
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
	if($TranslCode == 'Back')$Back = $LangTransl;
	if($TranslCode == 'Update')$Update = $LangTransl;

endforeach;

?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small>setting</small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->        
<script>
	function validateInCurr()
	{
		CURR_ID		= document.getElementById('CURR_ID').value;
		CURR_CODE	= document.getElementById('CURR_CODE').value;
		if(CURR_ID == "")
		{
			alert('Currency ID can not be empty.');
			document.getElementById('CURR_ID').focus();
			return false;
		}
		if(CURR_CODE == "")
		{
			alert('Currency Code can not be empty.');
			document.getElementById('CURR_CODE').focus();
			return false;
		}
	}
</script>
<?php
	$urlUpdAuthDash		= site_url('c_setting/c_employee/employee_dashboard_process/?id='.$this->url_encryption_helper->encode_url($appName));
	$urlUpdAuthCreate	= site_url('c_setting/c_employee/employee_create_process/?id='.$this->url_encryption_helper->encode_url($appName));
?>
<section class="content">
    <div class="box box-primary"><br>
	  	<form action="<?php echo $urlUpdAuthDash; ?>" method=POST>
          	<table width="100%">
                <tr>
                    <td width="16%"><input type="hidden" id="Emp_ID1" name="Emp_ID1" value="<?php print $Emp_ID; ?>" width="10" size="10" class="textbox">
                      &nbsp;&nbsp;&nbsp;Function Name        </td>
                    <td width="1%">:</td>
                    <td width="83%">Authorization Employee's Project</td>
                </tr>
              	<tr>
                    <td width="16%">
                    	&nbsp;&nbsp;&nbsp;Username Name        </td>
                    <td width="1%">:</td>
                	<?php
                        $sqlgEmp = "SELECT A.Position_ID, A.First_Name, A.Middle_Name, A.Last_Name, B.POS_NAME
                                    FROM tbl_employee A
                                    LEFT JOIN tbl_position B ON A.POS_CODE = B.POS_CODE
                                    WHERE A.emp_id = '$Emp_ID'";
                        $ressqlgEmp = $this->db->query($sqlgEmp)->result();
                        foreach($ressqlgEmp as $rowEmp) :
                            $First_Name = $rowEmp->First_Name;
                            $Middle_Name = $rowEmp->Middle_Name;
                            $Last_Name = $rowEmp->Last_Name;
                            $POS_NAME = $rowEmp->POS_NAME;
                        endforeach;
                    ?>
                  	<td width="83%"><?php echo "$First_Name $Middle_Name $Last_Name";; ?></td>
              	</tr>
              	<tr>
                  	<td colspan="3">&nbsp;</td>
             	</tr>
              	<tr>
                  	<td colspan="3">
                    <div class="box box-primary">           
                        <div class="box-header with-border">
                            <h3 class="box-title">Dashboard Authorization</h3>
                        </div>
                    </div>                    </td>
				</tr>
              	<tr>
               	  <td colspan="3">
                      <table width="200" border="0">
                        <tr>
                          <td width="12">&nbsp;</td>
                          <td width="106">
                            <select multiple="multiple" class="options" size="10" name="pavailable" onclick="MoveOption(this.form.pavailable, this.form.packageelements)">
                                <?php
                                    foreach($qDashboard as $rowDSB) :
                                        $DS_ROW 	= $rowDSB->DS_ROW;
                                        $DS_TYPE 	= $rowDSB->DS_TYPE;
                                        $DS_NOTES	= $rowDSB->DS_NOTES;
                                        
                                        ?>
                                      <option value="<?php echo $DS_TYPE; ?>"><?php echo "$DS_NOTES";?></option>
                                      <?php
                                    endforeach;
                                   // endforeach; 
                                ?>
                              </select>                      </td>
                          <td width="60" bor>
                            
                              <?php					
                                    $getCount		= "tbl_dash_sett_emp WHERE EMP_ID = '$Emp_ID'";
                                    $resGetCount	= $this->db->count_all($getCount);
                                ?>
                              <select multiple="multiple" name="packageelements[]" id="packageelements" size="10" style="width: 300px;" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)">
                                <?php
                                    if($resGetCount > 0)
                                    {
                                        $getData		= "SELECT A.EMP_ID, A.DS_TYPE, B.DS_NOTES 
                                                            FROM tbl_dash_sett_emp A
                                                            INNER JOIN tbl_dash_sett B ON B.DS_TYPE = A.DS_TYPE
                                                            WHERE A.EMP_ID = '$Emp_ID'";
                                        $resGetData 	= $this->db->query($getData)->result();
                                        foreach($resGetData as $rowData) : 
                                        {
                                            $EMP_ID 	= $rowData->EMP_ID;
                                            $DS_TYPE 	= $rowData->DS_TYPE;
                                            $DS_NOTES 	= $rowData->DS_NOTES;
                                            ?>
                                          <option value="<?php echo $DS_TYPE; ?>"><?php echo "$DS_NOTES";?></option>
                                          <?php
                                        } 
                                        endforeach; 
                                    }
                                ?>
                              </select>                      </td>
                        </tr>
                      </table>
                  </td>
				</tr>
              	<tr>
                  	<td colspan="3">&nbsp;</td>
				</tr>
              	<tr>
                  	<td colspan="3">
                    <div class="box box-primary">           
                        <div class="box-header with-border">
                            <h3 class="box-title">Dashboard Authorization - HR</h3>
                        </div>
                    </div>                    </td>
				</tr>
                <?php
					$getdashboard 	= "SELECT DS_ROW, DS_TYPE, DS_NOTES FROM tbl_dash_sett_hr ORDER BY DS_NOTES ASC";
					$qDashboard 	= $this->db->query($getdashboard)->result();
					
					$getdocAuth 	= "SELECT DS_ROW, DS_TYPE, DS_NOTES FROM tbl_dash_sett_hr ORDER BY DS_NOTES ASC";
					$qDashboard 	= $this->db->query($getdocAuth)->result();
				?>
              	<tr>
              	  <td colspan="3">
                      <table width="200" border="0">
                        <tr>
                          <td width="12">&nbsp;</td>
                          <td width="106">
                            <select multiple="multiple" class="options" size="10" name="pavailable1" onclick="MoveOption(this.form.pavailable1, this.form.packageelements1)">
                                <?php
                                    foreach($qDashboard as $rowDSB) :
                                        $DS_ROW 	= $rowDSB->DS_ROW;
                                        $DS_TYPE 	= $rowDSB->DS_TYPE;
                                        $DS_NOTES	= $rowDSB->DS_NOTES;
                                        
                                        ?>
                                      <option value="<?php echo $DS_TYPE; ?>"><?php echo "$DS_NOTES";?></option>
                                      <?php
                                    endforeach;
                                   // endforeach; 
                                ?>
                              </select>                      </td>
                          <td width="60" bor>
                            
                              <?php					
                                    $getCount		= "tbl_dash_sett_hr_emp WHERE EMP_ID = '$Emp_ID'";
                                    $resGetCount	= $this->db->count_all($getCount);
                                ?>
                              <select multiple="multiple" name="packageelements1[]" id="packageelements1" size="10" style="width: 300px;" ondblclick="MoveOption(this.form.packageelements1, this.form.pavailable1)">
                                <?php
                                    if($resGetCount > 0)
                                    {
                                        $getData		= "SELECT A.EMP_ID, A.DS_TYPE, B.DS_NOTES 
                                                            FROM tbl_dash_sett_hr_emp A
                                                            INNER JOIN tbl_dash_sett_hr B ON B.DS_TYPE = A.DS_TYPE
                                                            WHERE A.EMP_ID = '$Emp_ID'";
                                        $resGetData 	= $this->db->query($getData)->result();
                                        foreach($resGetData as $rowData) : 
                                        {
                                            $EMP_ID 	= $rowData->EMP_ID;
                                            $DS_TYPE 	= $rowData->DS_TYPE;
                                            $DS_NOTES 	= $rowData->DS_NOTES;
                                            ?>
                                          <option value="<?php echo $DS_TYPE; ?>"><?php echo "$DS_NOTES";?></option>
                                          <?php
                                        } 
                                        endforeach; 
                                    }
                                ?>
                              </select>                      </td>
                        </tr>
                      </table>
                  </td>
           	  </tr>
          	</table>        
			<table width="100%" border="0">
                <tr height="20">
                    <td colspan="3">&nbsp;</td>
        		</tr>
                <tr height="20">
                    <td colspan="3">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--<input type="submit" name="btnDelete" id="btnDelete" class="btn btn-primary" value="Update Dashboard" />-->&nbsp;
                        <button class="btn btn-primary" >
                        	<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                        </button>&nbsp;
                        <?php 
                            /*if ( ! empty($link))
                            {
                                foreach($link as $links)
                                {
                                    echo $links;
                                }
                            }*/
							
							echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
                        ?>
                        
                        
                    </td>
                </tr>
                <tr height="20">
                    <td colspan="3"><hr /></td>
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
    $('#datepicker1').datepicker({
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
</script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"> 
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

     function selectAll(objTargetElement) 
     { 
		for (var i = 0; i < objTargetElement.length; i++) { 
		objTargetElement.options[i].selected = true; 
   } 
   return false; 
  } 
  //--> 
 </SCRIPT> 
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>