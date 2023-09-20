<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Juli 2017
 * File Name	= read_maillist.php
 * Location		= -
*/
date_default_timezone_set("Asia/Jakarta");
$appName 	= $this->session->userdata('appName');

// $this->load->view('template/head');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$Emp_DeptCode	= $this->session->userdata['Emp_DeptCode'];

$MB_ID 			= $default['MB_ID'];
$MB_NO 			= $default['MB_NO'];
$MB_CLASS 		= $default['MB_CLASS'];
$MB_TYPE 		= $default['MB_TYPE'];
$MB_TYPE_X 		= $default['MB_TYPE_X'];
	$MB_TYPE_X1 = str_replace('<br>', '', $MB_TYPE_X);
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
$MB_TO_IDG		= $default['MB_TO_IDG'];
$MB_TOG			= $default['MB_TOG'];
$MB_MESSAGE 	= $default['MB_MESSAGE'];	
$MB_STATUS 		= $default['MB_STATUS'];
$MB_FN1			= $default['MB_FN1'];
$MB_FN2			= $default['MB_FN2'];
$MB_FN3			= $default['MB_FN3'];
$MB_FN4			= $default['MB_FN4'];
$MB_FN5			= $default['MB_FN5'];
$MB_ISRUNNO		= $default['MB_ISRUNNO'];
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
    <title><?php echo $appName; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <?php
        $vers     = $this->session->userdata['vers'];

        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
        $rescss = $this->db->query($sqlcss)->result();
        foreach($rescss as $rowcss) :
            $cssjs_lnk  = $rowcss->cssjs_lnk;
            ?>
                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
            <?php
        endforeach;

        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	// $this->load->view('template/topbar');
	// $this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
?>

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
	$secDraft_Mail	= site_url('c_mailbox/c_mailbox/draft_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secTrash_Mail	= site_url('c_mailbox/c_mailbox/trash_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secWrite_Mail	= site_url('c_mailbox/c_mailbox/write_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	
	function clean_textarea_of_br($data) 
	{
		return str_replace(array("<br>", "<br/>", "<br />"), "", $data[0]);
	}
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
                        	<a href="<?php echo $secDraft_Mail; ?>"><i class="fa fa-file-text-o"></i> Drafts
                            <span class="label label-warning pull-right"><?php echo $countDraft; ?></span></a>
						</li>
                        <li style="display:none">
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
          	<div class="box box-solid" style="display:none">
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
                    <h3 class="box-title">Read Message : <?php echo $MAIL_NO; ?></h3>
                </div>
                <form name="frm_reply" method="post" action="<?php echo $action_reply; ?>">
                    <input type="hidden" name="Emp_ID" id="Emp_ID" class="textbox" value="<?php echo $DefEmp_ID; ?>" />
                    <input type="hidden" name="MB_ID" id="MB_ID" class="textbox" value="<?php echo $MB_ID; ?>" />
                    <input type="hidden" name="MB_CODE" id="MB_CODE" class="textbox" value="<?php echo $MB_CODE; ?>" />
                    <input type="submit" class="button_css" name="submitReply" id="submitReply" value=" search " style="display:none" />
                </form>
                <form name="frm1" method="post" action="<?php echo $action_trash; ?>">
                    <input type="hidden" name="Emp_ID" id="Emp_ID" class="textbox" value="<?php echo $DefEmp_ID; ?>" />
                    <input type="hidden" name="MB_ID" id="MB_ID" class="textbox" value="<?php echo $MB_ID; ?>" />
                    <input type="hidden" name="MB_NO" id="MB_NO" class="textbox" value="<?php echo $MB_NO; ?>" />
                    <input type="hidden" name="MB_CODE" id="MB_CODE" class="textbox" value="<?php echo $MB_CODE; ?>" />
                    <input type="hidden" name="MB_CLASS" id="MB_CLASS" class="textbox" value="<?php echo $MB_CLASS; ?>" />
                    <input type="hidden" name="MB_TYPE" id="MB_TYPE" class="textbox" value="<?php echo $MB_TYPE; ?>" />
                    <input type="hidden" name="MB_TYPE_X" id="MB_TYPE_X" class="textbox" value="<?php echo $MB_TYPE_X; ?>" />
                    <input type="hidden" name="MB_DEPT" id="MB_DEPT" class="textbox" value="<?php echo $MB_DEPT; ?>" />
                    <input type="hidden" name="MB_PARENTC" id="MB_PARENTC" class="textbox" value="<?php echo $MB_PARENTC; ?>" />
                    <input type="hidden" name="MB_SUBJECT" id="MB_SUBJECT" class="textbox" value="<?php echo $MB_SUBJECT; ?>" />
                    <input type="hidden" name="MB_DATE" id="MB_DATE" class="textbox" value="<?php echo $MB_DATE; ?>" />
                    <input type="hidden" name="MB_DATE1" id="MB_DATE1" class="textbox" value="<?php echo $MB_DATE1; ?>" />
                    <input type="hidden" name="MB_READD" id="MB_READD" class="textbox" value="<?php echo $MB_READD; ?>" />
                    <input type="hidden" name="MB_FROM_ID" id="MB_FROM_ID" class="textbox" value="<?php echo $MB_FROM_ID; ?>" />
                    <input type="hidden" name="MB_FROM" id="MB_FROM" class="textbox" value="<?php echo $MB_FROM; ?>" />
                    <input type="hidden" name="MB_TO_ID" id="MB_TO_ID" class="textbox" value="<?php echo $MB_TO_ID; ?>" />
                    <input type="hidden" name="MB_TO" id="MB_TO" class="textbox" value="<?php echo $MB_TO; ?>" />
                    <input type="hidden" name="MB_TO_IDG" id="MB_TO_IDG" class="textbox" value="<?php echo $MB_TO_IDG; ?>" />
                    <input type="hidden" name="MB_TOG" id="MB_TOG" class="textbox" value="<?php echo $MB_TOG; ?>" />
                    <input type="hidden" name="MB_STATUS" id="MB_STATUS" class="textbox" value="<?php echo $MB_STATUS; ?>" />
                    <input type="hidden" name="MB_FN1" id="MB_FN1" class="textbox" value="<?php echo $MB_FN1; ?>" />
                    <input type="hidden" name="MB_FN2" id="MB_FN2" class="textbox" value="<?php echo $MB_FN2; ?>" />
                    <input type="hidden" name="MB_FN3" id="MB_FN3" class="textbox" value="<?php echo $MB_FN3; ?>" />
                    <input type="hidden" name="MB_FN4" id="MB_FN4" class="textbox" value="<?php echo $MB_FN4; ?>" />
                    <input type="hidden" name="MB_FN5" id="MB_FN5" class="textbox" value="<?php echo $MB_FN5; ?>" />
                    <input type="hidden" name="MB_ISRUNNO" id="MB_ISRUNNO" class="textbox" value="<?php echo $MB_ISRUNNO; ?>" />
                    <input type="hidden" name="MB_D" id="MB_D" class="textbox" value="<?php echo $MB_D; ?>" />
                    <input type="hidden" name="MB_M" id="MB_M" class="textbox" value="<?php echo $MB_M; ?>" />
                    <input type="hidden" name="MB_Y" id="MB_Y" class="textbox" value="<?php echo $MB_Y; ?>" />
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
                        <?php if($MB_TYPE == "OTHER") { ?>
                            <div class="form-group" id="div_mail_type_x" >
                                <textarea class="form-control" name="MB_TYPE_X"  id="MB_TYPE_X" placeholder="Mail Title" disabled><?php echo $MB_TYPE_X1; ?></textarea>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <select name="MB_DEPT1" id="MB_DEPT1" class="form-control" disabled>
                                <?php									
									if($MB_DEPT != 'JXXX')
									{
										$sqlDept	= "SELECT A.DEMP_DEPCODE,
															B.MDEPT_CODE, B.MDEPT_DESC, B.MDEPT_POSIT, B.MDEPT_NAME
														FROM tbl_mail_dept_emp A
															INNER JOIN tbl_mail_dept B ON A.DEMP_DEPCODE = B.MDEPT_CODE
														WHERE A.DEMP_EMPID = '$DefEmp_ID'
										 				ORDER BY A.DEMP_DEPCODE";
										$sqlDept	= $this->db->query($sqlDept)->result();
										foreach($sqlDept as $rowDept) :
											$DEMP_DEPCODE	= $rowDept->DEMP_DEPCODE;
											$MDEPT_CODE		= $rowDept->MDEPT_CODE;
											$MDEPT_DESC		= $rowDept->MDEPT_DESC;
											$MDEPT_POSIT	= $rowDept->MDEPT_POSIT;
											$MDEPT_NAME		= $rowDept->MDEPT_NAME;
											?>
												<option value="<?php echo "$MDEPT_CODE"; ?>" <?php if($MDEPT_CODE == $MB_DEPT) { ?> selected <?php } ?>>
													<?php echo "$MDEPT_CODE - $MDEPT_POSIT / $MDEPT_NAME"; ?>
												</option>
											<?php
										endforeach;
									}
									else
									{
										?>
                                            <option value="JXXX" selected>
                                                NON-MANAGEMENT
                                            </option>
                                        <?php
									}
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <?php
                                echo "<strong>From : </strong>$MB_FROM";
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                                echo "<strong>Subject : </strong>$MB_SUBJECT";
                            ?>
                        </div>
                        <div class="form-group">
                            <textarea name="MB_MESSAGE1" id="compose-textarea" class="form-control" style="height: 300px" disabled>
                                <?php
                                    echo $MB_MESSAGE;
                                ?>
                            </textarea>
                            <textarea name="MB_MESSAGE" id="compose-textarea" class="form-control" style="height: 300px; display:none">
                                <?php
                                    echo $MB_MESSAGE;
                                ?>
                            </textarea>
                        </div>
                        <div class="form-group">
						<?php
                            $linkDL1 = site_url('c_mailbox/c_mailbox/downloadFile/?id='.$this->url_encryption_helper->encode_url($MB_FN1));
                            $linkDL2 = site_url('c_mailbox/c_mailbox/downloadFile/?id='.$this->url_encryption_helper->encode_url($MB_FN2));
                            $linkDL3 = site_url('c_mailbox/c_mailbox/downloadFile/?id='.$this->url_encryption_helper->encode_url($MB_FN3));
                            $linkDL4 = site_url('c_mailbox/c_mailbox/downloadFile/?id='.$this->url_encryption_helper->encode_url($MB_FN4));
                            $linkDL5 = site_url('c_mailbox/c_mailbox/downloadFile/?id='.$this->url_encryption_helper->encode_url($MB_FN5));
                            if($MB_FN1 != '')
							{
								?>
                                	1. <a href="<?php echo $linkDL1; ?>"><?php echo $MB_FN1; ?>&nbsp;&nbsp;</a>
                                <?php
							}
                            if($MB_FN2 != '')
							{
								?>
                                	2. <a href="<?php echo $linkDL2; ?>"><?php echo $MB_FN2; ?>&nbsp;&nbsp;</a>
                                <?php
							}
                            if($MB_FN3 != '')
							{
								?>
                                	3. <a href="<?php echo $linkDL3; ?>"><?php echo $MB_FN3; ?>&nbsp;&nbsp;</a>
                                <?php
							}
                            if($MB_FN4 != '')
							{
								?>
                                	4. <a href="<?php echo $linkDL4; ?>"><?php echo $MB_FN4; ?>&nbsp;&nbsp;</a>
                                <?php
							}
                            if($MB_FN5 != '')
							{
								?>
                                	5. <a href="<?php echo $linkDL5; ?>"><?php echo $MB_FN5; ?>&nbsp;&nbsp;</a>
                                <?php
							}
							
							$secDL_Mail		= base_url().'index.php/c_mailbox/c_mailbox/DL_mail_I/?id='.$MB_ID;
							$secPrint_Mail 	= base_url().'index.php/c_mailbox/c_mailbox/print_mail_I/?id='.$MB_ID;
                        ?>                        
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-warning" onClick="forwardMail()">
                                <i class="fa fa-mail-forward"></i> Forward
                            </button>&nbsp;
                            <button type="button" class="btn btn-success" onClick="DLMail('<?php echo $secDL_Mail; ?>')">
                                <i class="fa fa-download"></i> Download
                            </button>&nbsp;
                            <button type="button" class="btn btn-success" onClick="printMail('<?php echo $secPrint_Mail; ?>')">
                                <i class="fa fa-print"></i> Print
                            </button>&nbsp;
                            <button type="submit" class="btn btn-danger" name="submitTrash" id="submitTrash">
                                <i class="fa fa-times"></i> Delete
                            </button>&nbsp;
                        </div>
                        <button type="button" class="btn btn-primary" onClick="replayMail()">
                         <i class="fa fa-mail-reply"></i> Replay
                        </button>
                    </div>
                </form>
            </div>
        </div>
	</div>
</section>
<script>
	function printMail(printURL)
	{
		var url = printURL;
		title = 'Select Item';
		w = 1000;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	function DLMail(printURL)
	{
		var url = printURL;
		title = 'Select Item';
		w = 1000;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	function replayMail()
	{
		document.frm_reply.submitReply.click();
	}
	
	function forwardMail()
	{
		alert('Sorry this button is under construction.');
		return false;
		//document.frm_reply.submitReply.click();
	}
</script>
<script>
    $(function () {
    //Add text editor
       $("#compose-textarea").wysihtml5();
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
        $.fn.datepicker.defaults.format = "dd/mm/yyyy";
        $('#datepicker').datepicker({
            autoclose: true,
            startDate: '-3d',
            endDate: '+0d'
        });

        //Date picker
        $('#datepicker1').datepicker({
          autoclose: true,
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
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js';?>"></script>
</body>
</html>