<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Januari 2018
 * File Name	= v_zona_form.php
 * Location		= -
*/
?>
<?php 
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
	
if($task == 'add')
{
	$ZN_ID		= "";
	$ZN_CODE	= "";
	$ZN_DESC	= "";
	$ZN_PERC	= 0;
	$CRITA_CODE	= "";
	$CRITA_PERC	= 0;
}
else
{
	$ZN_ID 		= $default['ZN_ID'];
	$ZN_CODE 	= $default['ZN_CODE'];
	$ZN_DESC 	= $default['ZN_DESC'];
	$ZN_PERC 	= $default['ZN_PERC'];
	$CRITA_CODE	= $default['CRITA_CODE'];
	$CRITA_PERC	= $default['CRITA_PERC'];
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
		if($TranslCode == 'Percentation')$Percentation = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$alert1		= "Kode zona tidak boleh kosong.";
		$alert2		= "Deskripsi zona tidak boleh kosong.";
		$alert3		= "Persentasi zona tidak boleh kosong.";
		$alert4		= "Silahkan pilih salah satu kriteria...!";
	}
	else
	{
		$alert1		= "Criteria Code can not be empty.";
		$alert2		= "Description can not be empty.";
		$alert3		= "Percentation can not be empty.";
		$alert4		= "Please select a criteria...!";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $Add; ?>
    <small><?php echo $Zone; ?> Master</small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->        
<script>
	function chekData()
	{
		ZN_CODE		= document.getElementById('ZN_CODE').value;
		ZN_DESC		= document.getElementById('ZN_DESC').value;
		ZN_PERC		= document.getElementById('ZN_PERC').value;
		CRITA_CODE1	= document.getElementById('CRITA_CODE1').value;
		
		if(ZN_CODE == "")
		{
			alert('<?php echo $alert1; ?>');
			document.getElementById('ZN_CODE').focus();
			return false;
		}
		if(ZN_DESC == "")
		{
			alert('<?php echo $alert2; ?>');
			document.getElementById('ZN_DESC').focus();
			return false;
		}
		if(ZN_PERC == 0)
		{
			alert('<?php echo $alert3; ?>');
			document.getElementById('ZN_PERC1').focus();
			return false;
		}
		if(CRITA_CODE1 == "")
		{
			alert('<?php echo $alert4; ?>');
			document.getElementById('CRITA_CODE1').focus();
			return false;
		}
	}
</script>
<section class="content">	
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
                	<form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return chekData()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?></label>
                          	<div class="col-sm-10">
                                <label>
                                	<?php
										if($task == "add")
										{
									?>
                                    		<input type="text" class="form-control" style="max-width:100px;"  name="ZN_CODE" id="ZN_CODE" value="<?php echo $ZN_CODE; ?>" onChange="functioncheck(this.value)">
                                	<?php
										}
										else
										{
									?>
                                    		<input type="text" class="form-control" style="max-width:100px;"  name="ZN_CODE1" id="ZN_CODE1" value="<?php echo $ZN_CODE; ?>" disabled>
                                    		<input type="hidden" class="form-control" name="ZN_CODE" id="ZN_CODE" value="<?php echo $ZN_CODE; ?>" onChange="functioncheck(this.value)">
                                	<?php
										}
									?>
                                </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>
                                <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
                          	</div>
                        </div>
                        <script>
							function functioncheck(myValue)
							{
								var ajaxRequest;
								try
								{
									ajaxRequest = new XMLHttpRequest();
								}
								catch (e)
								{
									alert("Something is wrong");
									return false;
								}
								ajaxRequest.onreadystatechange = function()
								{
									if(ajaxRequest.readyState == 4)
									{
										recordcount = ajaxRequest.responseText;
										if(recordcount > 0)
										{
											document.getElementById('CheckThe_Code').value= recordcount;
											document.getElementById("isHidden").innerHTML = ' Code already exist ... !';
											document.getElementById("isHidden").style.color = "#ff0000";
										}
										else
										{
											document.getElementById('CheckThe_Code').value= recordcount;
											document.getElementById("isHidden").innerHTML = ' Code : OK .. !';
											document.getElementById("isHidden").style.color = "green";
										}
									}
								}
								var ZN_CODE = document.getElementById('ZN_CODE').value;
								
								ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_setting/c_zona/getTheCode/';?>" + ZN_CODE, true);
								ajaxRequest.send(null);
							}
						</script>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Description; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="ZN_DESC" id="ZN_DESC" style="max-width:350px" value="<?php echo $ZN_DESC; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ZonePerc; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="ZN_PERC1" id="ZN_PERC1" style="max-width:80px; text-align:right" value="<?php echo number_format($ZN_PERC, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getPERC()" maxlength="5" />
                                <input type="hidden" class="form-control" name="ZN_PERC" id="ZN_PERC" style="max-width:80px; text-align:right" value="<?php echo $ZN_PERC; ?>" />
                            </div>
                        </div>
                        <script>
							function getPERC()
							{
                                var decFormat	= document.getElementById('decFormat').value;
                                ZN_PERC 		= eval(document.getElementById('ZN_PERC1')).value.split(",").join("");
                                document.getElementById('ZN_PERC').value 	= ZN_PERC;
                                document.getElementById('ZN_PERC1').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ZN_PERC)),decFormat));
							}
						</script>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Criteria; ?></label>
                            <div class="col-sm-10">
                                <select name="CRITA_CODE1" id="CRITA_CODE1" class="form-control" style="max-width:250px">
                                    <option value="">--- None ---</option>
									<?php
                                        $sqlCA 	= "SELECT CRITA_CODE, CRITA_DESC, CRITA_PERC FROM tbl_criteria_allow";
                                        $resCA 	= $this->db->query($sqlCA)->result();
										foreach($resCA as $rowCA) :
											$CRITA_CODE1 = $rowCA->CRITA_CODE;
											$CRITA_DESC1 = $rowCA->CRITA_DESC;
											$CRITA_PERC1 = $rowCA->CRITA_PERC;
											?>
											<option value="<?php echo "$CRITA_CODE1~$CRITA_PERC1"; ?>" <?php if($CRITA_CODE1 == $CRITA_CODE) { ?>selected <?php } ?>> <?php echo $CRITA_DESC1; ?> </option>
											<?php
										 endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <?php
								if($ISCREATE == 1)
								{
									if($task=='add')
									{
										?>
											<button class="btn btn-primary">
											<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
											</button>&nbsp;
										<?php
									}
									else
									{
										?>
											<button class="btn btn-primary" >
											<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
											</button>&nbsp;
										<?php
									}
								}
							
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
							?>
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

<script>
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
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>