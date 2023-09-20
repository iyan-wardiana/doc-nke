<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2017
 * File Name	= joblist_form.php
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

if($task == 'add')
{
	$JOBCODEID	= '';
	$PRJCODE	= $PRJCODE;
	$JOBCOD1 	= '';
	$JOBCOD2 	= '';
	$JOBDESC 	= '';
	$JOBTYPE 	= '';
	$JOBUNIT	= '';
	$JOBVOLM	= 0;
	$JOBCOST	= 0;
}
else
{
	$JOBCODEID 	= $default['JOBCODEID'];
	$PRJCODE 	= $default['PRJCODE'];
	$JOBCOD1 	= '';
	$JOBCOD2 	= $default['JOBCOD1'];
	$JOBDESC 	= $default['JOBDESC'];
	$JOBTYPE 	= $default['JOBTYPE'];
	$JOBUNIT 	= $default['JOBUNIT'];
	$JOBVOLM 	= $default['JOBVOLM'];
	$JOBCOST 	= $default['JOBCOST'];
}

$Pattern_Length		= 4;
$sqlJOBL	= "tbl_joblist WHERE PRJCODE = '$PRJCODE'";
$sqlJOBL	= $this->db->count_all($sqlJOBL);
$sqlJOBLN	= $sqlJOBL + 1;
$len = strlen($sqlJOBLN);
$nol		= '';	
if($Pattern_Length==2)
{
	if($len==1) $nol="0";
}
elseif($Pattern_Length==3)
{if($len==1) $nol="00";else if($len==2) $nol="0";
}
elseif($Pattern_Length==4)
{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
}
elseif($Pattern_Length==5)
{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
}
elseif($Pattern_Length==6)
{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
}
elseif($Pattern_Length==7)
{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
}
$JOB_CODE = $nol.$sqlJOBLN;
if($task == 'add')
{
	$JOBCODEID	= "$PRJCODE$JOB_CODE";
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
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
?>

<body class="hold-transition skin-blue sidebar-mini" <?php if($task == 'add') { ?> onLoad="functioncheck()" <?php } ?>>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $PRJNAME; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">	
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveCategory()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Job Code</label>
                          	<div class="col-sm-10">
                                <label>
                                <input type="text" name="JOB_CODE1" id="JOB_CODE1" class="form-control" style="max-width:150px" value="<?php echo $JOB_CODE; ?>" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?> disabled <?php } ?>/>
                                <input type="hidden" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>" />
                                <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                <input type="hidden" name="JOB_CODE" id="JOB_CODE" value="<?php echo $JOB_CODE; ?>" />
                            	</label><label>&nbsp;&nbsp;</label><label id="theCode"></label>&nbsp;&nbsp;&nbsp;
                            	<input type="hidden" name="CheckThe_Code" id="CheckThe_Code" size="20" maxlength="25" value="0" >
                          	</div>
                        </div>
						<script>
                            function functioncheck()
                            {
                                JOBCODEID	= document.getElementById('JOBCODEID').value;
                                JOB_CODE1	= document.getElementById('JOB_CODE1').value;
                                PRJCODE		= document.getElementById('PRJCODE').value;
                                document.getElementById('JOB_CODE').value	= JOB_CODE1;
								
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
                                            document.getElementById('CheckThe_Code').value	= recordcount;
                                            document.getElementById("theCode").innerHTML 	= ' The code already exist ... !';
                                            document.getElementById("theCode").style.color 	= "#ff0000";
											//document.getElementById("JOBCODEID").value		= '';
                                        }
                                        else
                                        {
                                            document.getElementById('CheckThe_Code').value	= recordcount;
                                            document.getElementById("theCode").innerHTML 	= ' The code : OK ... !';
                                            document.getElementById("theCode").style.color 	= "green";
											document.getElementById("JOBCODEID").value		= PRJCODE + JOB_CODE1;
                                        }
                                    }				
                                }
                                var JOB_CODE 	= document.getElementById('JOB_CODE').value;
                                ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_project/c_joblist/getJOBCODE/';?>" + JOBCODEID, true);
                                ajaxRequest.send(null);
                            }
                        </script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Job Description</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px" name="JOBDESC" id="JOBDESC" value="<?php echo $JOBDESC; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Job Type</label>
                          	<div class="col-sm-10">
                            <select name="JOBTYPE" id="JOBTYPE" class="form-control" style="max-width:150px">
                                <option value="S" <?php if($JOBTYPE == 'S') { ?>selected <?php } ?>> S - Selfdone </option>
                                <option value="C" <?php if($JOBTYPE == 'C') { ?>selected <?php } ?>> C - Subcontracted </option>
                                <option value="O" <?php if($JOBTYPE == 'O') { ?>selected <?php } ?>> O - Others </option>
                          	</select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Job Type</label>
                          	<div class="col-sm-10">
                            <select name="JOBUNIT" id="JOBUNIT" class="form-control" style="max-width:100px">
							<?php
                                $sqlUnit	= "SELECT Unit_Type_Code, Unit_Type_Name FROM tbl_unittype ORDER BY Unit_Type_Name";
                                $sqlUnit	= $this->db->query($sqlUnit)->result();
                                foreach($sqlUnit as $row) :
                                    $Type_Code		= $row->Unit_Type_Code;
                                    $Unit_Type_Name	= $row->Unit_Type_Name;
									?>
										<option value="<?php echo $Type_Code; ?>" <?php if($Type_Code == $JOBUNIT) { ?>selected <?php } ?>>
											<?php echo $Unit_Type_Name; ?>
										</option>
									<?php
                                endforeach;
                            ?>
                          	</select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Volume</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px; text-align:right;" name="JOBVOLM1" id="JOBVOLM1" value="<?php print number_format($JOBVOLM, $decFormat); ?>" onBlur="getJOBVOLM(this)">
                    			<input type="hidden" class="form-control" style="max-width:150px; text-align:right;" name="JOBVOLM" id="JOBVOLM" value="<?php echo $JOBVOLM; ?>">
                          	</div>
                        </div>
						<script>
                            function getJOBVOLM(thisVal)
                            {
                                var decFormat	= document.getElementById('decFormat').value;
                                var thisVal		= eval(thisVal).value.split(",").join("");
                                JOBVOLM			= thisVal;
                                document.getElementById('JOBVOLM').value 	= JOBVOLM;
                                document.getElementById('JOBVOLM1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.round(JOBVOLM)),decFormat));
                            }
                        </script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Cost (IDR)</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px; text-align:right;" name="JOBCOST1" id="JOBCOST1" value="<?php print number_format($JOBCOST, $decFormat); ?>" onBlur="getJOBCOST(this)">
                    			<input type="hidden" class="form-control" style="max-width:150px; text-align:right;" name="JOBCOST" id="JOBCOST" value="<?php echo $JOBCOST; ?>">
                          	</div>
                        </div>
						<script>
                            function getJOBCOST(thisVal)
                            {
                                var decFormat	= document.getElementById('decFormat').value;
                                var thisVal		= eval(thisVal).value.split(",").join("");
                                JOBCOST			= thisVal;
                                document.getElementById('JOBCOST').value 	= JOBCOST;
                                document.getElementById('JOBCOST1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.round(JOBCOST)),decFormat));
                            }
                        </script><br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" name="submitAdd" id="submitAdd" class="btn btn-primary" value="Save" onClick="return buttonShowPhoto(1)">&nbsp;
                                <?php 
                                    if ( ! empty($link))
                                    {
                                        foreach($link as $links)
                                        {
                                            echo $links;
                                        }
                                    }
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
</script>

<script>
	function saveCategory()
	{
		CheckThe_Code = document.getElementById('CheckThe_Code').value;
		if(CheckThe_Code > 0)
		{
			alert('Job Code is already exist.');
			document.getElementById('JOB_CODE').value = '';
			document.getElementById('JOB_CODE').focus();
			JOB_CODE = document.getElementById('JOB_CODE').value;
			functioncheck()
			return false;
		}
		
		JOB_CODE = document.getElementById('JOB_CODE').value;
		if(JOB_CODE == '')
		{
			alert('Job Code can not be empty.');
			document.getElementById('JOB_CODE1').focus();
			return false;			
		}
		
		JOBDESC = document.getElementById('JOBDESC').value;
		if(JOBDESC == '')
		{
			alert('Job Description can not be empty.');
			document.getElementById('JOBDESC').focus();
			return false;			
		}
	}
	
	var decFormat		= 2;
	
	function doDecimalFormat(angka) {
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

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>