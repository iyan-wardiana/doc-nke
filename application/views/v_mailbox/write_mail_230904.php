<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2017
 * File Name	= write_mail.php
 * Location		= -
*/
date_default_timezone_set("Asia/Jakarta");
$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$Emp_DeptCode	= $this->session->userdata['Emp_DeptCode'];

$MDEPT_CODE1	= 'JXXX';
$sqlMDC	= "SELECT MDEPT_CODE FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
$sqlMDC	= $this->db->query($sqlMDC)->result();
foreach($sqlMDC as $rowMDC) :
	$MDEPT_CODE1= $rowMDC->MDEPT_CODE;
endforeach;
if($MDEPT_CODE1 == '')
	$MDEPT_CODE1 = 'JXXX';

$MDEPT_CODE1	= 'JXXX';
$sqlMDC	= "SELECT A.DEMP_EMPID, A.DEMP_DEPCODE
			FROM tbl_mail_dept_emp A
			WHERE DEMP_EMPID = '$DefEmp_ID'";
$sqlMDC	= $this->db->query($sqlMDC)->result();
foreach($sqlMDC as $rowMDC) :
	$MDEPT_CODE1= $rowMDC->DEMP_DEPCODE;
endforeach;
if($MDEPT_CODE1 == '')
	$MDEPT_CODE1 = 'JXXX';
										
$MB_M		= date('m');
$MB_M1		= (int)$MB_M;
$MB_Y		= date('Y');
$sqlMBC		= "tbl_mailbox WHERE MB_M = '$MB_M1' AND MB_Y = '$MB_Y' AND MB_ISRUNNO = 'Y'";
$resMBC		= $this->db->count_all($sqlMBC);

$resMBCN	= $resMBC + 1;
$len = strlen($resMBCN);
$nol		= '';	
$PattLength	= 4;
if($PattLength==2)
{
	if($len==1) $nol="0";
}
elseif($PattLength==3)
{if($len==1) $nol="00";else if($len==2) $nol="0";
}
elseif($PattLength==4)
{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
}
elseif($PattLength==5)
{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
}
elseif($PattLength==6)
{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
}
elseif($PattLength==7)
{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
}
$MAIL_STEP	= $nol.$resMBCN;

$MB_TYPE_X	= '';
if($task == 'add')
{
	if (isset($_POST['submitSrch']))
	{
		$MB_CLASS1	= $_POST['MB_CLASS_A'];
		$MB_TYPE1	= $_POST['MB_TYPE_A'];
		$MB_DEPT1	= $_POST['MB_DEPT_A'];
	}
	else
	{
		$MB_CLASS1	= "S";
		$MB_TYPE1	= "";
		$MB_DEPT1	= $MDEPT_CODE1;
	}
	
	$MB_CLASS		= $MB_CLASS1;
	$MB_TYPE		= $MB_TYPE1;
	$MB_TYPE_X		= '';
	$MB_DEPT		= $MB_DEPT1;
	$MB_STATUS		= 1;							// 1. New/Unread, 2. Read, 3. Draft, 4. Junk, 5. Delete
}
else
{
	$MB_CLASS		= $default['MB_CLASS'];
	$MB_TYPE		= $default['MB_TYPE'];
	$MB_TYPE_X		= $default['MB_TYPE_X'];
	$MB_DEPT		= $default['MB_DEPT'];
	$MB_STATUS		= $default['MB_STATUS'];
	
	if (isset($_POST['submitSrch']))
	{
		$MB_CLASS	= $_POST['MB_CLASS_A'];
		$MB_TYPE	= $_POST['MB_TYPE_A'];
		$MB_DEPT	= $_POST['MB_DEPT_A'];
	}
}

$NO_01		= "J";			// HOLD
$NO_02		= $MB_DEPT;		// DEPARTMENT
$NO_03		= $MB_CLASS;
$NO_04		= $MAIL_STEP;
$NO_05		= "NKE";
$NO_06		= date('m');
$NO_07		= date('y');
$MAIL_NO	= "$NO_02-$NO_03$NO_04/$NO_05/$NO_06-$NO_07";
?>
<!DOCTYPE html>
<html>
<head>
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
                    <h3 class="box-title">Write  New Message : <?php echo $MAIL_NO; ?></h3>
                </div>
                <form name="frm" method="post" action="">
                    <input type="hidden" name="MB_CLASS_A" id="MB_CLASS_A" class="textbox" value="<?php echo $MB_CLASS; ?>" />
                    <input type="hidden" name="MB_TYPE_A" id="MB_TYPE_A" class="textbox" value="<?php echo $MB_TYPE; ?>" />
                    <input type="hidden" name="MB_DEPT_A" id="MB_DEPT_A" class="textbox" value="<?php echo $MDEPT_CODE1; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " style="display:none" />
                </form>
                <form name="frm1" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkData();">
                    <input type="hidden" name="Emp_ID" id="Emp_ID" class="textbox" value="<?php echo $DefEmp_ID; ?>" />
                    <input type="hidden" name="MB_DEPT" id="MB_DEPT" class="textbox" value="<?php echo $MDEPT_CODE1; ?>" />
                    <input type="hidden" name="MB_STATUS" id="MB_STATUS" class="textbox" value="<?php echo $MB_STATUS; ?>" />
                    <input type="hidden" name="MB_PATTNO" id="MB_PATTNO" class="textbox" value="<?php echo $resMBCN; ?>" />
                    <div class="box-body">
                        <div class="form-group">
                            <select name="MB_CLASS" id="MB_CLASS" class="form-control select2" onChange="ShowDocSelect(1);">
                                <option value="M" <?php if($MB_CLASS == 'M') { ?> selected <?php } ?>> Memo </option>
                                <option value="S" <?php if($MB_CLASS == 'S') { ?> selected <?php } ?>> Surat </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="MB_TYPE" id="MB_TYPE" class="form-control select2" placeholder="&nbsp;&nbsp;&nbsp;Mail Type" style="width: 100%;">
                            	 <option value="">--- Mail Title --- </option>
                                <?php
                                    $sqlMTyp	= "SELECT MT_CODE, MT_DESC FROM tbl_mail_type ORDER BY MT_ID";
                                    $resMTyp	= $this->db->query($sqlMTyp)->result();
                                    foreach($resMTyp as $row) :
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
                        <div class="form-group" id="div_mail_type_x" <?php if($MB_TYPE != "OTHER") { ?> style="display:none" <?php } ?>>
                            <textarea class="form-control" name="MB_TYPE_X"  id="MB_TYPE_X" data-placeholder="Mail Title"><?php echo $MB_TYPE_X; ?></textarea>
                        </div>
                        <div class="form-group">
                            <select name="MB_DEPT1" id="MB_DEPT1" class="form-control select2">
                                <?php
									if($MDEPT_CODE1 != 'JXXX')
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
												<option value="<?php echo "$MDEPT_CODE"; ?>" <?php if($MDEPT_CODE == $MDEPT_CODE1) { ?> selected <?php } ?>>
													<?php //echo "$MDEPT_CODE - $MDEPT_POSIT / $MDEPT_NAME"; ?>
													<?php echo "$MDEPT_CODE - $MDEPT_DESC / $MDEPT_NAME"; ?>
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
                            <select name="MB_TO[]" id="MB_TO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Mail To" style="width: 100%;">
                                <?php
                                    $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
												FROM tbl_employee WHERE Emp_ID != '$DefEmp_ID' ORDER BY First_Name";
                                    $sqlEmp	= $this->db->query($sqlEmp)->result();
                                    foreach($sqlEmp as $row) :
                                        $Emp_ID		= $row->Emp_ID;
                                        $First_Name	= $row->First_Name;
                                        $Last_Name	= $row->Last_Name;
                                        $Email		= $row->Email;
                                        ?>
                                            <option value="<?php echo "$Emp_ID|$Email"; ?>">
                                                <?php echo "$First_Name $Last_Name - $Email"; ?>
                                            </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="MB_TOG[]" id="MB_TOG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Mail To Group" style="width: 100%;">
                                <?php
                                    $sqlMG	= "SELECT MG_CODE, MG_NAME
												FROM tbl_mailgroup_header ORDER BY MG_NAME ASC";
                                    $sqlMG	= $this->db->query($sqlMG)->result();
                                    foreach($sqlMG as $rowMG) :
                                        $MG_CODE	= $rowMG->MG_CODE;
                                        $MG_NAME	= $rowMG->MG_NAME;
                                        ?>
                                            <option value="<?php echo "$MG_CODE"; ?>">
                                                <?php echo "$MG_NAME"; ?>
                                            </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="MB_SUBJECT" id="MB_SUBJECT" class="form-control" placeholder="&nbsp;Subject:" value="">
                        </div>
                        <div class="form-group">
                            <textarea name="MB_MESSAGE" id="compose-textarea" class="form-control" style="height: 300px">
                                &nbsp;
                            </textarea>
                        </div>
                        <div class="form-group">
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 1
                                <input type="file" name="attachment1" id="attachment1">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 2
                                <input type="file" name="attachment2" id="attachment2">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 3
                                <input type="file" name="attachment3" id="attachment3">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 4
                                <input type="file" name="attachment4" id="attachment4">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 5
                                <input type="file" name="attachment5" id="attachment5">
                            </div>
                            <p class="help-block">Max. 32MB</p>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success" onClick="MailStatus(3)">
                                <i class="fa fa-pencil"></i> Draft
                            </button>&nbsp;
                            <button type="reset" class="btn btn-danger">
                                <i class="fa fa-times"></i> Reset
                            </button>&nbsp;
                        </div>
                        <button type="submit" class="btn btn-primary" name="submitSent" id="submitSent">
                         <i class="fa fa-envelope-o"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
	</div>
</section>
<script>
	function ShowDocSelect_xx(thisvalue)
	{
		if(thisvalue == 'OTHER')
		{
			document.getElementById('div_mail_type_x').style.display = '';
		}
		else
		{
			document.getElementById('div_mail_type_x').style.display = 'none';
		}
	}
	
	function checkData()
	{
		var MB_TYPE	= document.getElementById('MB_TYPE').value;
		if(MB_TYPE == '')
		{
			alert('Please select mail type.');
			document.getElementById('MB_TYPE').focus();
			return false;
		}
		else if(MB_TYPE == 'OTHER')
		{
			var MB_TYPE_X	= document.getElementById('MB_TYPE_X').value;
			if(MB_TYPE_X == '')
			{
				alert('Mail Type can not be empty.');
				document.getElementById('MB_TYPE_X').focus();
				return false;
			}
		}
			
		var ZeroRec	= 0;
		var MB_TO	= document.getElementById('MB_TO').value;
			if(MB_TO != '')
				var ZeroRec	= 1;
		var MB_TOG	= document.getElementById('MB_TOG').value;
			if(MB_TOG != '')
				var ZeroRec	= 1;
		if(ZeroRec == 0)
		{
			alert('Please input email recipient.');
			document.getElementById('MB_TO').focus();
			return false;
		}
		
		var MB_SUBJECT	= document.getElementById('MB_SUBJECT').value;
		if(MB_SUBJECT == '')
		{
			alert('The Mail Subject can not be empty.');
			document.getElementById('MB_SUBJECT').focus();
			return false;
		}
	}
	
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