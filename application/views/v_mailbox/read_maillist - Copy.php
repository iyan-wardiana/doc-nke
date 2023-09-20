<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Juli 2017
 * File Name	= read_maillist.php
 * Location		= -
*/
date_default_timezone_set("Asia/Jakarta");
$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');
$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
$Emp_DeptCode		= $this->session->userdata['Emp_DeptCode'];
		
$MB_CLASS 		= $default['MB_CLASS'];
$MB_TYPE 		= $default['MB_TYPE'];
$MB_DEPT 		= $default['MB_DEPT'];
$MB_CODE 		= $default['MB_CODE'];
$MB_PARENTC 	= $default['MB_PARENTC'];
$MB_SUBJECT 	= $default['MB_SUBJECT'];
$MB_DATE 		= $default['MB_DATE'];
$MB_DATE1 		= $default['MB_DATE1'];
$MB_READD 		= $default['MB_READD'];
$MB_FROM_ID 	= $default['MB_FROM_ID'];
$MB_FROM		= $default['MB_FROM'];
$MB_TO_ID		= $default['MB_TO_ID'];
$MB_TO			= $default['MB_TO'];
$MB_MESSAGE 	= $default['MB_MESSAGE'];
$MB_STATUS 		= $default['MB_STATUS'];			
$MB_FN1			= $default['MB_FN1'];
$MB_D			= $default['MB_D'];
$MB_M			= $default['MB_M'];
$MB_Y			= $default['MB_Y'];
$MB_PATTNO		= $default['MB_PATTNO'];
$MAIL_NO		= $MB_CODE;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $appName; ?> | Compose Mail</title>
  <!-- Tell the browser to be responsive to screen width -->
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons1.min.css">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.min.css'; ?>">
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.print.css'; ?>" media="print">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css'; ?>">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'; ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
</head>
<body class="hold-transition skin-blue sidebar-mini">

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Mailbox
        <small><?php echo $countInbox; ?> messages</small>
    </h1>
    <?php /*?><ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
    </ol><?php */?>
</section>
<?php
	$secInbox_Mail	= site_url('c_mailbox/c_mailbox/inbox_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secSend_Mail	= site_url('c_mailbox/c_mailbox/sent_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secTrash_Mail	= site_url('c_mailbox/c_mailbox/read_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secWrite_Mail	= site_url('c_mailbox/c_mailbox/write_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
?>
<!-- Main content -->
<section class="content">
    <div class="row">
		<div class="col-md-3">
            <a href="<?php echo $secInbox_Mail; ?>" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Folders</h3>
                    <div class="box-tools">
            			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            			</button>
            		</div>
            	</div>
            	<div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                        	<a href="<?php echo $secInbox_Mail; ?>"><i class="fa fa-inbox"></i> Inbox
                        	<span class="label label-primary pull-right"><?php echo $countInbox; ?></span></a>
						</li>
                        <li>
                        	<a href="<?php echo $secSend_Mail; ?>"><i class="fa fa-envelope-o"></i> Sent
                        	<span class="label label-warning pull-right"><?php echo $countSent; ?></span></a>
                        </li>
                        <li>
                        	<a href="#"><i class="fa fa-file-text-o"></i> Drafts
                            <span class="label label-warning pull-right"><?php echo $countDraft; ?></span></a>
						</li>
                        <li>
                        	<a href="#"><i class="fa fa-filter"></i> Junk
                            <span class="label label-warning pull-right"><?php echo $countJunk; ?></span></a>
                        </li>
                        <li>
                        	<a href="<?php echo $secTrash_Mail; ?>"><i class="fa fa-trash-o"></i> Trash
                            <span class="label label-warning pull-right"><?php echo $countTrash; ?></span></a>
                        </li>
                    </ul>
            	</div>
			</div>
          	<div class="box box-solid">
                <div class="box-header with-border">
                	<h3 class="box-title">Chat Messanger</h3>
                	<div class="box-tools">
                		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
                	</div>
                </div>
            	<div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
                    </ul>
            	</div>
          	</div>
		</div>
        
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Write  New Message : <?php echo $MAIL_NO; ?></h3>
                    </div>
                    <form name="frm1" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="Emp_ID" id="Emp_ID" class="textbox" value="<?php echo $DefEmp_ID; ?>" />
                        <input type="hidden" name="MB_CLASS" id="MB_CLASS" class="textbox" value="<?php echo $MB_CLASS; ?>" />
                        <input type="hidden" name="MB_TYPE" id="MB_TYPE" class="textbox" value="<?php echo $MB_TYPE; ?>" />
                        <input type="hidden" name="MB_DEPT" id="MB_DEPT" class="textbox" value="<?php echo $MB_DEPT; ?>" />
                        <input type="hidden" name="MB_TO" id="MB_TO" class="textbox" value="<?php echo $MB_TO; ?>" />
                        
                        <input type="hidden" name="MB_STATUS" id="MB_STATUS" class="textbox" value="<?php echo $MB_STATUS; ?>" />
                        <input type="hidden" name="MB_PATTNO" id="MB_PATTNO" class="textbox" value="<?php echo $MB_PATTNO; ?>" />
                        <div class="box-body">
                            <div class="form-group">
                                <select name="MB_CLASS1" id="MB_CLASS1" class="form-control" disabled>
                                    <option value="M" <?php if($MB_CLASS == 'M') { ?> selected <?php } ?>> Memo </option>
                                    <option value="S" <?php if($MB_CLASS == 'S') { ?> selected <?php } ?>> Surat </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="MB_TYPE1" id="MB_TYPE1" class="form-control" placeholder="&nbsp;&nbsp;&nbsp;Mail Type" style="width: 100%;" disabled>
                                    <?php
                                        $sqlMTyp	= "SELECT MT_CODE, MT_DESC FROM tbl_mail_type ORDER BY MT_DESC";
                                        $sqlMTyp	= $this->db->query($sqlMTyp)->result();
                                        foreach($sqlMTyp as $row) :
                                            $MT_CODE1	= $row->MT_CODE;
                                            $MT_DESC1	= $row->MT_DESC;
                                            ?>
                                                <option value="<?php echo $MT_CODE1; ?>" <?php if($MT_CODE1 == $MB_TYPE) { ?> selected <?php } ?>>
                                                    <?php echo "$MT_DESC1"; ?>
                                                </option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
								<select name="MB_DEPT1" id="MB_DEPT1" class="form-control" disabled>
                                    <option value="J201" <?php if($MB_DEPT == 'J201') { ?> selected <?php } ?>> Human Resources / HR </option>
                                    <option value="J203" <?php if($MB_DEPT == 'J203') { ?> selected <?php } ?>> Information Technology / IT </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <?php
									echo "<strong>Sent To : </strong>$MB_TO";
								?>
                            </div>
                            <div class="form-group">
                                <?php
									echo "<strong>Subject : </strong>$MB_SUBJECT";
								?>
                            </div>
                            <div class="form-group">
                                <textarea name="MB_MESSAGE" id="compose-textarea" class="form-control" style="height: 300px" disabled>
                                    <?php
										echo $MB_MESSAGE;
									?>
                                </textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-success" onClick="MailStatus(3)">
                                	<i class="fa fa-mail-forward"></i> Forward
                                </button>&nbsp;
                           		<button type="button" class="btn btn-danger" onClick="MailStatus(6)">
                                	<i class="fa fa-times"></i> Delete
                                </button>&nbsp;
                            </div>
                            <button type="submit" class="btn btn-primary" name="submitSent" id="submitSent">
                           	 <i class="fa fa-mail-reply"></i> Replay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
	</div>
</section>

<script>
	function ShowDocSelect(thisVal)
	{
		document.getElementById('MB_CLASS_A').value	= document.getElementById('MB_CLASS').value;
		document.getElementById('MB_TYPE_A').value	= document.getElementById('MB_TYPE').value;
		document.getElementById('MB_DEPT_A').value	= document.getElementById('MB_DEPT').value;
		document.frm.submitSrch.click();
	}
	
	function MailStatus(thisVal)
	{
		document.getElementById('MB_STATUS').value	= thisVal;
		document.frm1.submitSent.click();
	}
</script>
                    
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/js/bootstrap.min.js'; ?>"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/js/app.min.js'; ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/js/demo.js'; ?>"></script>
<!-- iCheck -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'; ?>"></script>
<!-- Page Script -->
<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
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
</body>
</html>