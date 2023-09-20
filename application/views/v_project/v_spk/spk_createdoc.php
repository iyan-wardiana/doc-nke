<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Novemver 2018
 * File Name	= spk_createdoc.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat	= 2;

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_init	= $this->session->userdata('comp_init');

$PRJCODE 	= $def['PRJCODE'];
$WOP_NUM 	= $def['WOP_NUM'];
$WOP_TITLE 	= $def['WOP_TITLE'];
$WOP_CODE 	= $def['WOP_CODE'];
$WOP_PAGE1 	= $def['WOP_PAGE1'];
$WOP_PAGE2	= $def['WOP_PAGE2'];
$WOP_PAGE3 	= $def['WOP_PAGE3'];
$WOP_PAGE4 	= $def['WOP_PAGE4'];
$WOP_PAGE5 	= $def['WOP_PAGE5'];

$PROJ_MNG	= '-';
$sqlPRJMNG	= "SELECT A.PRJLOCT, B.First_Name, B.Last_Name 
				FROM tbl_project A 
					INNER JOIN tbl_employee B ON A.PRJ_MNG = B.Emp_ID
				WHERE A.PRJCODE = '$PRJCODE'";
$resPRJMNG	= $this->db->query($sqlPRJMNG)->result();
foreach($resPRJMNG as $rowPRJMNG) :
	$PRJLOCT	= $rowPRJMNG->PRJLOCT;
	$First_Name	= $rowPRJMNG->First_Name;
	$Last_Name	= $rowPRJMNG->Last_Name;
	if($Last_Name == '')
		$PROJ_MNG	= "$First_Name";
	else
		$PROJ_MNG	= "$First_Name $Last_Name";
endforeach;
if($PROJ_MNG == '')
	$PROJ_MNG	= "-";

$selPRJSYNC	= '';
if(isset($_POST['PRJCODE']))
{
	$WOP_TITLE 	= $_POST['WOP_TITLE'];
	$WOP_PAGE1 	= $_POST['WOP_PAGE1'];
	$WOP_PAGE2 	= $_POST['WOP_PAGE2'];
	$WOP_PAGE3 	= $_POST['WOP_PAGE3'];
	$WOP_PAGE4 	= $_POST['WOP_PAGE4'];
	//$WOP_PAGE5 = $_POST['WOP_PAGE5'];
	$WOP_CREATER= $DefEmp_ID;
	$WOP_CREATED= date('Y-m-d H:i:s');
	
	$sql1		= "tbl_wo_print A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.WOP_NUM = '$WOP_NUM'";
	$res1 		= $this->db->count_all($sql1);
	if($res1 == 0)
	{
		$sql2	= "INSERT INTO tbl_wo_print (PRJCODE, WOP_NUM, WOP_CODE, WOP_TITLE, WOP_PAGE1, WOP_PAGE2, WOP_PAGE3, WOP_PAGE4)
					VALUES ('$PRJCODE', '$WOP_NUM', '$WOP_CODE', '$WOP_TITLE', '$WOP_PAGE1', '$WOP_PAGE2', '$WOP_PAGE3', 
						'$WOP_PAGE4')";
		$res2 	= $this->db->query($sql2);
		
		$sql3	= "UPDATE tbl_wo_header SET WO_CDOC = 1 WHERE PRJCODE = '$PRJCODE' AND WO_NUM = '$WOP_NUM'";
		$this->db->query($sql3);		
	}
	else
	{
		$sql4	= "UPDATE tbl_wo_print SET WOP_TITLE = '$WOP_TITLE', WOP_PAGE1 = '$WOP_PAGE1', WOP_PAGE2 = '$WOP_PAGE2', 
						WOP_PAGE3 = '$WOP_PAGE3'
					WHERE PRJCODE = '$PRJCODE' AND WOP_NUM = '$WOP_NUM'";
		$this->db->query($sql4);
		
		$sql5	= "UPDATE tbl_wo_header SET WO_CDOC = 1 WHERE PRJCODE = '$PRJCODE' AND WO_NUM = '$WOP_NUM'";
		$this->db->query($sql5);
	}
}
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
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Consultant')$Consultant = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Title')$Title = $LangTransl;
		if($TranslCode == 'Content')$Content = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;

	endforeach;
?>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<section class="content">	
    <div class="row">

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">               
              		<div class="callout callout-success">
                        <h4><?php echo $h1_title; ?></h4> 
                        <p>Please choose a document to uplaod (PDF Only).</p>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" name="news_form" method="post" action="" enctype="multipart/form-data" onSubmit="return validateData()">
                    	<input type="hidden" class="form-control" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                    	<input type="hidden" class="form-control" name="WOP_CODE" id="WOP_CODE" value="<?php echo $WOP_CODE; ?>" />
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> SPK</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="WOP1" id="WOP1" value="<?php echo $WOP_CODE; ?>" disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Title ?> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="WOP_TITLE" id="WOP_TITLE" value="<?php echo $WOP_TITLE; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $page ?> 1</label>
                            <div class="col-sm-10">
                                <textarea id="editor1" name="WOP_PAGE1" rows="10" cols="80">
                                	<?php echo $WOP_PAGE1; ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $page ?> 2</label>
                            <div class="col-sm-10">
                                <textarea name="WOP_PAGE2" id="compose-textarea1" class="form-control" style="height: 150px">
									<?php echo $WOP_PAGE2; ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $page ?> 3</label>
                            <div class="col-sm-10">
                                <textarea name="WOP_PAGE3" id="compose-textarea2" class="form-control" style="height: 150px">
									<?php echo $WOP_PAGE3; ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $page ?> 4</label>
                            <div class="col-sm-10">
                                <textarea name="WOP_PAGE4" id="compose-textarea3" class="form-control" style="height: 150px">
									<?php echo $WOP_PAGE4; ?>
                                </textarea>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary">
                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                </button>&nbsp;
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'; ?>"></script>

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
	//Add text editor
	$("#compose-textarea").wysihtml5();
	$("#compose-textarea1").wysihtml5();
	$("#compose-textarea2").wysihtml5();
	$("#compose-textarea3").wysihtml5();
	$("#compose-textarea4").wysihtml5();
	});
	
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

    //Date picker
    $('#datepicker3').datepicker({
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
  
	function validateData()
	{
		var TASK_AUTHOR	= document.getElementById('TASK_AUTHOR').value;
		var TASK_TITLE	= document.getElementById('TASK_TITLE').value;
		var TASK_CONTENT= document.getElementById('compose-textarea').value;
		
		if(TASK_AUTHOR == '')
		{
			alert('Concultant can not be empty.');
			document.getElementById('TASK_AUTHOR').focus();
			return false;
		}
		
		if(TASK_TITLE == '')
		{
			alert('The title can not be empty.');
			document.getElementById('TASK_TITLE').focus();
			return false;
		}
		
		if(TASK_CONTENT == '')
		{
			alert('The content of task can not be empty.');
			document.getElementById('compose-textarea').focus();
			return false;
		}
	}
</script>

<!-- CK Editor -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
  });
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>