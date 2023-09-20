<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 September 2018
 * File Name	= payterm_form.php
 * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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

$IDPT 		= $default['ID'];
$PT_DESC01 	= $default['PT_DESC01'];
$PT_DESC02 	= $default['PT_DESC02'];
$PT_DESC03 	= $default['PT_DESC03'];
$PT_DESC04 	= $default['PT_DESC04'];
$PT_DESC05 	= $default['PT_DESC05'];
$PTRM_DESC01 = $default['PTRM_DESC01'];
$PTRM_DESC02 = $default['PTRM_DESC02'];
$PTRM_DESC03 = $default['PTRM_DESC03'];
$PTRM_DESC04 = $default['PTRM_DESC04'];
$PTRM_DESC05 = $default['PTRM_DESC05'];
$OTH_DESC01 = $default['OTH_DESC01'];
$OTH_DESC02 = $default['OTH_DESC02'];
$OTH_DESC03 = $default['OTH_DESC03'];
$OTH_DESC04 = $default['OTH_DESC04'];
$OTH_DESC05 = $default['OTH_DESC05'];
$PT_JOBAREA1  = $default['PT_JOBAREA1'];
$PT_JOBAREA2  = $default['PT_JOBAREA1'];
$PTRM_TYPE	= $default['PTRM_TYPE'];

if(isset($_POST['PTRM_TYPEX']))
{
	$PTRM_TYPE		= $_POST['PTRM_TYPEX'];
}

if($PTRM_TYPE == 'MDR')
{
	$sqlPTO	= "SELECT * FROM tbl_payterm WHERE PTRM_TYPE = 'MDR'";
	$resPTO	= $this->db->query($sqlPTO)->result();
	foreach($resPTO as $rowPTO) :
		$IDPT 		    = $rowPTO->ID;
		$PT_DESC01 		= $rowPTO->PT_DESC01;
		$PT_DESC02 		= $rowPTO->PT_DESC02;
		$PT_DESC03 		= $rowPTO->PT_DESC03;
		$PT_DESC04 		= $rowPTO->PT_DESC04;
		$PT_DESC05 		= $rowPTO->PT_DESC05;
		$PTRM_DESC01 	= $rowPTO->PTRM_DESC01;
		$PTRM_DESC02 	= $rowPTO->PTRM_DESC02;
		$PTRM_DESC03 	= $rowPTO->PTRM_DESC03;
		$PTRM_DESC04 	= $rowPTO->PTRM_DESC04;
		$PTRM_DESC05 	= $rowPTO->PTRM_DESC05;
		$OTH_DESC01 	= $rowPTO->OTH_DESC01;
		$OTH_DESC02 	= $rowPTO->OTH_DESC02;
		$OTH_DESC03 	= $rowPTO->OTH_DESC03;
		$OTH_DESC04 	= $rowPTO->OTH_DESC04;
		$OTH_DESC05 	= $rowPTO->OTH_DESC05;
    $PT_JOBAREA1  = $rowPTO->PT_JOBAREA1;
    $PT_JOBAREA2  = $rowPTO->PT_JOBAREA2;
	endforeach;
}
elseif($PTRM_TYPE == 'SUB')
{
	$sqlPTO	= "SELECT * FROM tbl_payterm WHERE PTRM_TYPE = 'SUB'";
	$resPTO	= $this->db->query($sqlPTO)->result();
	foreach($resPTO as $rowPTO) :
		$IDPT 		    = $rowPTO->ID;
		$PT_DESC01 		= $rowPTO->PT_DESC01;
		$PT_DESC02 		= $rowPTO->PT_DESC02;
		$PT_DESC03 		= $rowPTO->PT_DESC03;
		$PT_DESC04 		= $rowPTO->PT_DESC04;
		$PT_DESC05 		= $rowPTO->PT_DESC05;
		$PTRM_DESC01 	= $rowPTO->PTRM_DESC01;
		$PTRM_DESC02 	= $rowPTO->PTRM_DESC02;
		$PTRM_DESC03 	= $rowPTO->PTRM_DESC03;
		$PTRM_DESC04 	= $rowPTO->PTRM_DESC04;
		$PTRM_DESC05 	= $rowPTO->PTRM_DESC05;
		$OTH_DESC01 	= $rowPTO->OTH_DESC01;
		$OTH_DESC02 	= $rowPTO->OTH_DESC02;
		$OTH_DESC03 	= $rowPTO->OTH_DESC03;
		$OTH_DESC04 	= $rowPTO->OTH_DESC04;
		$OTH_DESC05 	= $rowPTO->OTH_DESC05;
    $PT_JOBAREA1  = $rowPTO->PT_JOBAREA1;
    $PT_JOBAREA2  = $rowPTO->PT_JOBAREA2;
	endforeach;
}
elseif($PTRM_TYPE == 'TLS')
{
	$sqlPTO	= "SELECT * FROM tbl_payterm WHERE PTRM_TYPE = 'TLS'";
	$resPTO	= $this->db->query($sqlPTO)->result();
	foreach($resPTO as $rowPTO) :
		$IDPT 		    = $rowPTO->ID;
		$PT_DESC01 		= $rowPTO->PT_DESC01;
		$PT_DESC02 		= $rowPTO->PT_DESC02;
		$PT_DESC03 		= $rowPTO->PT_DESC03;
		$PT_DESC04 		= $rowPTO->PT_DESC04;
		$PT_DESC05 		= $rowPTO->PT_DESC05;
		$PTRM_DESC01 	= $rowPTO->PTRM_DESC01;
		$PTRM_DESC02 	= $rowPTO->PTRM_DESC02;
		$PTRM_DESC03 	= $rowPTO->PTRM_DESC03;
		$PTRM_DESC04 	= $rowPTO->PTRM_DESC04;
		$PTRM_DESC05 	= $rowPTO->PTRM_DESC05;
		$OTH_DESC01 	= $rowPTO->OTH_DESC01;
		$OTH_DESC02 	= $rowPTO->OTH_DESC02;
		$OTH_DESC03 	= $rowPTO->OTH_DESC03;
		$OTH_DESC04 	= $rowPTO->OTH_DESC04;
		$OTH_DESC05 	= $rowPTO->OTH_DESC05;
    $PT_JOBAREA1  = $rowPTO->PT_JOBAREA1;
    $PT_JOBAREA2  = $rowPTO->PT_JOBAREA2;
	endforeach;
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
<script type="text/javascript">
    function notifyMe(msg_title, msg_body, redirect_onclick) {
        var granted = 0;
 
        // Let's check if the browser supports notifications
        if (!("Notification" in window)) {
            alert("This browser does not support desktop notification");
        }
 
        // Let's check if the user is okay to get some notification
        else if (Notification.permission === "granted") {
            granted = 1;
        }
 
        // Otherwise, we need to ask the user for permission
        else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {
                // If the user is okay, let's create a notification
                if (permission === "granted") {
                    granted = 1;
                }
            });
        }
 
        if (granted == 1) {
            var notification = new Notification(msg_title, {
                body: msg_body,
                icon: 'notif-icon.png'
            });
 
            if (redirect_onclick) {
                notification.onclick = function() {
                    window.location.href = redirect_onclick;
                }
            }
        }
    }
</script>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	//______$this->load->view('template/topbar');
	//______$this->load->view('template/sidebar');
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
		if($TranslCode == 'Name')$Name = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Category')$Category = $LangTransl;
		if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
		if($TranslCode == 'AddInvoice')$AddInvoice = $LangTransl;
		if($TranslCode == 'DownPayment')$DownPayment = $LangTransl;
		if($TranslCode == 'Payment')$Payment = $LangTransl;
	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <?php echo $h1_title; ?>
            <small><?php echo $h2_title; ?></small>
          </h1>
          <?php /*?><ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Data tables</li>
          </ol><?php */?>
        </section>

        <section class="content">	
            <script>
            	function getDetail(strType) 
            	{
            		document.getElementById("PTRM_TYPEX").value = strType;
            		document.frmsrch.submitSrch.click();
            	}
            </script>
            <button onClick="notifyMe('Title Notif', 'Body Notif')" style="display:none">Notify me!</button>
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
                                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                                    <input type="text" name="PTRM_TYPEX" id="PTRM_TYPEX" class="textbox" value="<?php echo $PTRM_TYPE; ?>" />
                                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                                </form>
                            	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveCategory()">
                                	<input type="hidden" name="IDPT" id="IDPT" class="textbox" value="<?php echo $IDPT; ?>" />
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">Tipe</label>
                                      	<div class="col-sm-10">
                                            <select name="PTRM_TYPE" id="PTRM_TYPE" class="form-control select2" style="max-width:150px" onChange="getDetail(this.value)">
                                                <option value="MDR" <?php if($PTRM_TYPE == 'MDR') { ?> selected <?php } ?>>Mandor</option>
                                                <option value="SUB" <?php if($PTRM_TYPE == 'SUB') { ?> selected <?php } ?>>Subkon</option>
                                                <option value="TLS" <?php if($PTRM_TYPE == 'TLS') { ?> selected <?php } ?>>Alat</option>
                                            </select>
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                      	<div class="col-sm-10">
                                            <b>JANGKA WAKTU PELAKSANAAN</b>
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">1.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PTRM_DESC01" id="PTRM_DESC01" value="<?php echo $PTRM_DESC01; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">2.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PTRM_DESC02" id="PTRM_DESC02" value="<?php echo $PTRM_DESC02; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">3.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PTRM_DESC03" id="PTRM_DESC03" value="<?php echo $PTRM_DESC03; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">4.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PTRM_DESC04" id="PTRM_DESC04" value="<?php echo $PTRM_DESC04; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">5.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PTRM_DESC05" id="PTRM_DESC05" value="<?php echo $PTRM_DESC05; ?>" />
                                      	</div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                      	<div class="col-sm-10">
                                            <b>CARA PEMBAYARAN</b>
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">1.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PT_DESC01" id="PT_DESC01" value="<?php echo $PT_DESC01; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">2.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PT_DESC02" id="PT_DESC02" value="<?php echo $PT_DESC02; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">3.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PT_DESC03" id="PT_DESC03" value="<?php echo $PT_DESC03; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">4.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PT_DESC04" id="PT_DESC04" value="<?php echo $PT_DESC04; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">5.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="PT_DESC05" id="PT_DESC05" value="<?php echo $PT_DESC05; ?>" />
                                      	</div>
                                    </div>
                                    <br>
                                    <div class="form-group" style="display:none;">
                                      	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                      	<div class="col-sm-10">
                                            <b>KESEPAKATAN LAIN-LAIN</b>
                                      	</div>
                                    </div>
                                    <div class="form-group" style="display:none;">
                                      	<label for="inputName" class="col-sm-2 control-label">1.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="OTH_DESC01" id="OTH_DESC01" value="<?php echo $OTH_DESC01; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group" style="display:none;">
                                      	<label for="inputName" class="col-sm-2 control-label">2.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="OTH_DESC02" id="OTH_DESC02" value="<?php echo $OTH_DESC02; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group" style="display:none;">
                                      	<label for="inputName" class="col-sm-2 control-label">3.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="OTH_DESC03" id="OTH_DESC03" value="<?php echo $OTH_DESC03; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group" style="display:none;">
                                      	<label for="inputName" class="col-sm-2 control-label">4.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="OTH_DESC04" id="OTH_DESC04" value="<?php echo $OTH_DESC04; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group" style="display:none;">
                                      	<label for="inputName" class="col-sm-2 control-label">5.</label>
                                      	<div class="col-sm-10">
                                        	<input type="text" class="form-control" name="OTH_DESC05" id="OTH_DESC05" value="<?php echo $OTH_DESC05; ?>" />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-10">
                                            <b>LINGKUP PEKERJAAN PIHAK PERTAMA</b>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">1.</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="PT_JOBAREA1" id="PT_JOBAREA1" value="<?php echo $PT_JOBAREA1; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">2.</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="PT_JOBAREA2" id="PT_JOBAREA2" value="<?php echo $PT_JOBAREA2; ?>" />
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                          <?php
            							
            									if($task=='add')
            									{
            										?>
            											<button class="btn btn-primary">
            											<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
            											</button>&nbsp;
            										<?php
            									}
            									else
            									{
            										?>
            											<button class="btn btn-primary" >
            											<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
            											</button>&nbsp;
            										<?php
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
			alert('Vendor Category Code is already exist.');
			document.getElementById('VendCat_Code').value = '';
			document.getElementById('VendCat_Code').focus();
			VendCat_Code = document.getElementById('VendCat_Code').value;
			functioncheck()
			return false;
		}
		
		VendCat_Code = document.getElementById('VendCat_Code').value;
		if(VendCat_Code == '')
		{
			alert('Vendor Category Code can not empty.');
			document.getElementById('VendCat_Code1').focus();
			return false;			
		}
	}
</script>
<?php 
//______$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//______$this->load->view('template/foot');
?>