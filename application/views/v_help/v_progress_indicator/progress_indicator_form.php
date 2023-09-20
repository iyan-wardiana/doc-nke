<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2017
 * File Name	= progress_indicator_form.php
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
	$IK_CODE			= '';
	$IK_PARENT			= '';
	$IK_DESC			= '';
	$IK_TARGET			= 0;
	$IK_PROCESSED		= 0;
	$IK_ISHEADER		= 0;
	$IK_LEVEL			= 0;
}
else
{
	$IK_CODE 		= $default['IK_CODE'];
	$IK_PARENT		= $default['IK_PARENT'];
	$IK_DESC 		= $default['IK_DESC'];		
	$IK_TARGET 		= $default['IK_TARGET'];
	$IK_PROCESSED 	= $default['IK_PROCESSED'];
	$IK_ISHEADER 	= $default['IK_ISHEADER'];
	$IK_LEVEL 		= $default['IK_LEVEL'];
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
	$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

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
		if($TranslCode == 'IndicatorCode')$IndicatorCode = $LangTransl;
		if($TranslCode == 'ParentIndicator')$ParentIndicator = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Target')$Target = $LangTransl;
		if($TranslCode == 'Processed')$Processed = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Estimate')$Estimate = $LangTransl;
		if($TranslCode == 'Yes')$Yes = $LangTransl;
		if($TranslCode == 'No')$No = $LangTransl;

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
                	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveRecomend()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            			<input type="Hidden" name="rowCount" id="rowCount" value="0">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $IndicatorCode ?> </label>
                          	<div class="col-sm-10">
                            	<?php
									if($task == 'add')
									{
									?>
                                		<input type="text" name="IK_CODE" id="IK_CODE" class="form-control" style="max-width:150px" value="<?php echo $IK_CODE; ?>" >
                                    <?php
									}
									else
									{
									?>
                                		<input type="text" name="IK_CODE1" id="IK_CODE1" class="form-control" style="max-width:150px" value="<?php echo $IK_CODE; ?>" disabled >
                                		<input type="hidden" name="IK_CODE" id="IK_CODE" class="form-control" style="max-width:150px" value="<?php echo $IK_CODE; ?>" >
                                    <?php
									}
								?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ParentIndicator ?> </label>
                          	<div class="col-sm-10">
                            	<select name="IK_PARENT" id="IK_PARENT" class="form-control" style="max-width:200px">
                                    <?php
                                        $sqlINDIC1 	= "SELECT IK_CODE, IK_PARENT, IK_DESC
														FROM tbl_indikator WHERE IK_LEVEL = 1";
										$resINDIC1 	= $this->db->query($sqlINDIC1)->result();
										foreach($resINDIC1 as $rowINDIC1) :
											$IK_CODE1	= $rowINDIC1->IK_CODE;
											$IK_PARENT1	= $rowINDIC1->IK_PARENT;
											$IK_DESC1	= $rowINDIC1->IK_DESC;
											$sqlPRNC1 	= "tbl_indikator WHERE IK_PARENT = '$IK_CODE1'";
											$resPRNC1 	= $this->db->count_all($sqlPRNC1);
                                                ?>
                                                <option value="<?php echo $IK_CODE1; ?>"<?php if($IK_CODE1 == $IK_PARENT) { ?>selected <?php } if($resPRNC1 > 0) {?> style="font-weight:bold" <?php } ?>><?php echo $IK_DESC1; ?></option>
                                                <?php
												if($resPRNC1 > 0)
												{
													$sqlINDIC2 	= "SELECT IK_CODE, IK_PARENT, IK_DESC
																	FROM tbl_indikator WHERE IK_PARENT = '$IK_CODE1' AND IK_LEVEL = 2";
													$resINDIC2 	= $this->db->query($sqlINDIC2)->result();
													foreach($resINDIC2 as $rowINDIC2) :
														$IK_CODE2	= $rowINDIC2->IK_CODE;
														$IK_PARENT2	= $rowINDIC2->IK_PARENT;
														$IK_DESC2	= $rowINDIC2->IK_DESC;
														$sqlPRNC2 	= "tbl_indikator WHERE IK_PARENT = '$IK_CODE2'";
														$resPRNC2 	= $this->db->count_all($sqlPRNC2);
														?>
                                                        <option value="<?php echo $IK_CODE2; ?>" <?php if($IK_CODE2 == $IK_PARENT) { ?>selected <?php } if($resPRNC2 > 0) {?> style="font-weight:bold" <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $IK_DESC2; ?></option>
                                                        <?php
														if($resPRNC2 > 0)
														{
															$sqlINDIC3 	= "SELECT IK_CODE, IK_PARENT, IK_DESC
																			FROM tbl_indikator WHERE IK_PARENT = '$IK_CODE2' AND IK_LEVEL = 3";
															$resINDIC3 	= $this->db->query($sqlINDIC3)->result();
															foreach($resINDIC3 as $rowINDIC3) :
																$IK_CODE3	= $rowINDIC3->IK_CODE;
																$IK_PARENT3	= $rowINDIC3->IK_PARENT;
																$IK_DESC3	= $rowINDIC3->IK_DESC;
																?>
                                                                <option value="<?php echo $IK_CODE3; ?>" <?php if($IK_CODE3 == $IK_PARENT) { ?>selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $IK_DESC3; ?></option>
                                                                <?php
															endforeach;
														}
													endforeach;
												}
										endforeach;
                                    ?>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:350px" name="IK_DESC" id="IK_DESC" size="50" value="<?php echo $IK_DESC; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Target</label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:100px; text-align:right" name="IK_TARGET1" id="IK_TARGET1" value="<?php print number_format($IK_TARGET, $decFormat); ?>" maxlength="15" onBlur="getValueTarget(this)" onKeyPress="return isIntOnlyNew(event);" >
                                <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="IK_TARGET" id="IK_TARGET" value="<?php echo $IK_TARGET; ?>" maxlength="15" onBlur="getValue(this.value)" onKeyPress="return isIntOnlyNew(event);" >
                          	</div>
                        </div>
                        <script>
							function getValueTarget(thisVal)
							{
								var decFormat		= document.getElementById('decFormat').value;
								var IK_TARGETX		= eval(thisVal).value.split(",").join("");
								document.getElementById('IK_TARGET1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(IK_TARGETX)), decFormat));
								document.getElementById('IK_TARGET').value 		= IK_TARGETX;
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Processed ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:100px; text-align:right" name="IK_PROCESSED1" id="IK_PROCESSED1" value="<?php print number_format($IK_PROCESSED, $decFormat); ?>" maxlength="15" onBlur="getValueProcess(this)" onKeyPress="return isIntOnlyNew(event);" >
                                <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="IK_PROCESSED" id="IK_PROCESSED" value="<?php echo $IK_PROCESSED; ?>" maxlength="15" onBlur="getValue(this.value)" onKeyPress="return isIntOnlyNew(event);" >
                          	</div>
                        </div>
                        <script>
							function getValueProcess(thisVal)
							{
								var decFormat		= document.getElementById('decFormat').value;
								var IK_PROCESSEDX		= eval(thisVal).value.split(",").join("");
								document.getElementById('IK_PROCESSED1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(IK_PROCESSEDX)), decFormat));
								document.getElementById('IK_PROCESSED').value 	= IK_PROCESSEDX;
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?>  (<?php echo $Estimate ?> )</label>
                          	<div class="col-sm-10">
                                <input type="radio" name="IK_ISHEADER" id="IK_ISHEADER1" value="1" class="minimal" <?php if($IK_ISHEADER == 1) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;<?php echo $Yes ?> &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="IK_ISHEADER" id="IK_ISHEADER2" value="2" class="minimal" <?php if($IK_ISHEADER == 0) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;<?php echo $No ?> &nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                               <!-- <input type="submit" name="submitAdd" id="submitAdd" class="btn btn-primary" value="Save" onClick="return buttonShowPhoto(1)">&nbsp;-->
                                <?php 
                                    /*if ( ! empty($link))
                                    {
                                        foreach($link as $links)
                                        {
                                            echo $links;
                                        }
                                    }*/
                                ?>
                                
                                <?php
									//if($ISCREATE == 1)
									//{
										if($task=='add')
										{
											?>
												<button class="btn btn-primary" onClick="return buttonShowPhoto(1)">
												<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
												</button>&nbsp;
											<?php
										}
										else
										{
											?>
												<button class="btn btn-primary" onClick="return buttonShowPhoto(1)">
												<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									//}
								
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
	function saveRecomend()
	{		
		IK_CODE = document.getElementById('IK_CODE').value;
		if(IK_CODE == '')
		{
			alert('Indicator code can not be empty');
			document.getElementById('IK_CODE').focus();
			return false;
		}
		
		IK_DESC = document.getElementById('IK_DESC').value;
		if(IK_DESC == '')
		{
			alert('Description can not be empty');
			document.getElementById('IK_DESC').focus();
			return false;
		}
		
		IK_TARGET = document.getElementById('IK_TARGET').value;
		if(IK_TARGET == 0)
		{
			alert('Please input value of the Target.');
			document.getElementById('IK_TARGET1').focus();
			return false;
		}
		
		/*IK_PROCESSED = document.getElementById('IK_PROCESSED').value;
		if(IK_PROCESSED == 0)
		{
			alert('Please input value of the Processed.');
			document.getElementById('IK_PROCESSED1').focus();
			return false;
		}*/
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