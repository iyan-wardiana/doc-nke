<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Januari 2018
 * File Name	= employee_up.php
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

// temporary
//$sqlEMP		= "SELECT NOPEG FROM employee_temp";
//$sresEMP	= $this->db->query($sqlEMP)->result();
//foreach($sresEMP as $rowEMP) :
	//$NOPEG	= $rowEMP->NOPEG;
	//$ins1	= "INSERT INTO tusermenu1 (isChkDetail, emp_id, menu_code, ISCREATE) VALUES (350, '$NOPEG', 'MN357', 1)";
	//$this->db->query($ins1);
	//$ins2	= "INSERT INTO tusermenu1 (isChkDetail, emp_id, menu_code, ISCREATE) VALUES (351, '$NOPEG', 'MN358', 1)";
	//$this->db->query($ins2);
//endforeach;



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
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Translate')$Translate = $LangTransl;
		if($TranslCode == 'Setting')$Setting = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Zone')$Zone = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'ZonePerc')$ZonePerc = $LangTransl;
		if($TranslCode == 'Criteria')$Criteria = $LangTransl;
		if($TranslCode == 'FileUpload')$FileUpload = $LangTransl;
		if($TranslCode == 'Employee')$Employee = $LangTransl;
		if($TranslCode == 'MarketingPerfom')$MarketingPerfom = $LangTransl;
		if($TranslCode == 'PerformanceOpr')$PerformanceOpr = $LangTransl;
		if($TranslCode == 'PerformanceFin')$PerformanceFin = $LangTransl;
		if($TranslCode == 'SpecialAllowance')$SpecialAllowance = $LangTransl;
		if($TranslCode == 'Process')$Process = $LangTransl;
		if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
		if($TranslCode == 'SyncEmployee')$SyncEmployee = $LangTransl;
		if($TranslCode == 'Synchronization')$Synchronization = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$alert_1		= "Silahkan sinkronisasi data karyawan dengan mengklik tombol di bawah.";
		$alert_2		= "Upload Terakhir";
		$alert_3		= "Sinkronisasi Terakhir";
		$alert_4		= "Sinkronisasi Selesai.";
		$alert_5		= "Upload Gagal. File harus berekstensi *.csv";
		$alert_6		= "Upload Berhasil.";
		$alert_7		= "Perhatian";
	}
	else
	{
		$alert_1		= "Please synchronize employee data by clicking the button below.";
		$alert_2		= "Last Upload";
		$alert_3		= "Last Synchronization";
		$alert_4		= "Synchronization finished.";
		$alert_5		= "Upload Failed. File must be of *.csv extention.";
		$alert_6		= "Upload Successed.";
		$alert_7		= "Alert";
	}
	
	date_default_timezone_set("Asia/Jakarta");
	$genCode		= date('Ymd-His');
	
	$AMOUNT_1		= 0;
	$AMOUNT_2		= 0;
	$AMOUNT_3		= 0;
	$AMOUNT_4		= 0;
	
	$isProcDone_1	= 1;	
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $FileUpload; ?>
    <small><?php echo $Employee; ?></small>
  </h1>
  <br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->	
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border" style="display:none">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                	<form class="form-horizontal" name="form_1" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return aktLoad()">
                    	<div class="col-md-4">
                            <div class="box box-success box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $FileUpload; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table width="100%" border="0">
                                        <tr>
                                            <td width="34%" nowrap style="text-align:left"><?php echo $ChooseFile; ?></td>
                                            <td width="66%" style="text-align:right">
                                            	<input type="file" name="userfile" id="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right">&nbsp;<br><br></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style="text-align:left">
                                                <button class="btn btn-success">
                                                	<i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload Excel
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="loading_1" class="overlay" <?php if($isUpdDone_1 == 1) { ?> style="display:none" <?php } ?>>
                                	<i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
					</form>
                    <?php
						$UEMP_DATE 	= "-";	
						$UEMP_DATEP = "-";							
						$sqlLastUp 		= "SELECT UEMP_DATE, UEMP_DATEP FROM tbl_upload_emp WHERE UEMP_STAT = '1'";
						$sresLastUp 	= $this->db->query($sqlLastUp)->result();
						foreach($sresLastUp as $therow) :
							$UEMP_DATE 	= $therow->UEMP_DATE;	
							$UEMP_DATEP = $therow->UEMP_DATEP;
							if($UEMP_DATE == '')
							{
								$UEMP_DATE	= "-";
							}
							if($UEMP_DATEP == '')
							{
								$UEMP_DATEP	= "<em>not yet sync</em>";
							}
						endforeach;
					?>
                    <form class="form-horizontal" name="form_1" method="post" action="<?php echo $form_actSync; ?>" onSubmit="return aktLoad_Syn()">
                        <div class="col-md-4">
                            <div class="box box-warning box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $SyncEmployee; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table width="100%" border="0">
                                        <tr>
                                            <td style="text-align:center; color:#06C">
												<?php echo "$alert_2 : $UEMP_DATE"; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center; color:#F00">
												<?php echo "$alert_3 : $UEMP_DATEP"; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">
                                            	<input type="hidden" class="form-control" name="UEMP_FNAME" id="UEMP_FNAME" value="<?php echo $UEMP_FNAME; ?>" />
												<?php echo $alert_1; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center">
                                                <button class="btn btn-warning">
                                                    <i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;&nbsp;<?php echo $Synchronization; ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="loading_2" class="overlay" <?php if($isProcDone_1 == 1) { ?> style="display:none" <?php } ?>>
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </form>
                    <script>
						function aktLoad()
						{
							document.getElementById('loading_1').style.display = '';
						}
						
						function aktLoad_Syn()
						{
							document.getElementById('loading_2').style.display = '';
						}
					</script>
                    <?php
					if($up_stat == "0")
					{
						?>
                        <div class="col-md-4">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> <?php echo $alert_7; ?>!</h4>
                                <?php echo $alert_5; ?>
                            </div>
                        </div>
						<?php
                    }
                    else if($up_stat == "1")
                    {
                        ?>
                            <div class="col-md-4">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> <?php echo $alert_7; ?>!</h4>
                                     <?php echo $alert_6; ?>
                                </div>
                            </div>
                        <?php
                    }
                    if($up_type == "SYNC")
                    {
                        ?>
                            <div class="col-md-4">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> <?php echo $alert_7; ?>!</h4>
                                     <?php echo $alert_4; ?>
                                </div>
                            </div>
                        <?php
                    }
                    ?>
                    
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
  
	var decFormat		= 2;
	
	function doDecimalFormat(angka) 
	{
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}
	
	function chekData_1()
	{
		AMOUNT_1	= document.getElementById('AMOUNT_1').value;
		if(AMOUNT_1 == 0)
		{
			alert('<?php echo $alert_1; ?>');
			document.getElementById('AMOUNT_1x').focus();
			return false;			
		}
		document.getElementById('loading_1').style.display = '';
	}
	
	function chekData_2()
	{
		AMOUNT_2	= document.getElementById('AMOUNT_2').value;
		if(AMOUNT_2 == 0)
		{
			alert('<?php echo $alert_2; ?>');
			document.getElementById('AMOUNT_2x').focus();
			return false;			
		}
		document.getElementById('loading_2').style.display = '';
	}
	
	function chekData_3()
	{
		AMOUNT_3	= document.getElementById('AMOUNT_3').value;
		if(AMOUNT_3 == 0)
		{
			alert('<?php echo $alert_3; ?>');
			document.getElementById('AMOUNT_3x').focus();
			return false;			
		}
		document.getElementById('loading_3').style.display = '';
	}
	
	function chekData_4()
	{
		document.getElementById('loading_4').style.display = '';
		AMOUNT_4	= document.getElementById('AMOUNT_4').value;
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>