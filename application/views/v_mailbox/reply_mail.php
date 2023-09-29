<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2017
 * File Name	= maillist.php
 * Location		= -
*/
date_default_timezone_set("Asia/Jakarta");
$appName 	= $this->session->userdata('appName');

$this->load->view('template/head');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$Emp_DeptCode	= $this->session->userdata['Emp_DeptCode'];

$MDEPT_CODE1	= '';
$sqlMDC	= "SELECT MDEPT_CODE FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
$sqlMDC	= $this->db->query($sqlMDC)->result();
foreach($sqlMDC as $rowMDC) :
	$MDEPT_CODE1= $rowMDC->MDEPT_CODE;
endforeach;
if($MDEPT_CODE1 == '')
	$MDEPT_CODE1	= 'JXXX';

$MB_ID 			= $default['MB_ID'];
$MB_NO 			= $default['MB_NO'];
$MB_CLASS 		= $default['MB_CLASS'];
$MB_TYPE 		= $default['MB_TYPE'];
$MB_TYPE_X 		= $default['MB_TYPE_X'];
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
//$MB_STATUS 	= $default['MB_STATUS'];
$MB_STATUS 		= 1;						// 1. Unread/New	
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
  <title><?php echo $appName; ?> | Compose Mail</title>
  <!-- Tell the browser to be responsive to screen width -->
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
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

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
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
	$secProc_Mail	= site_url('c_mailbox/c_mailbox/proc_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
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
                        	<a href="<?php echo $secProc_Mail; ?>"><i class="fa fa-clock-o"></i> Process
                        	<span class="label label-info pull-right"><?php echo $countProc; ?></span></a>
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
                    <h3 class="box-title">Reply Mail : <?php echo $MAIL_NO; ?></h3>
                </div>
                <form name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
                    <input type="hidden" name="Emp_ID" id="Emp_ID" class="textbox" value="<?php echo $DefEmp_ID; ?>" />
                    <input type="hidden" name="MBR_NO" id="MBR_NO" class="textbox" value="<?php echo $MB_NO; ?>" />
                    <input type="hidden" name="MBR_CODE" id="MBR_CODE" class="textbox" value="<?php echo $MB_CODE; ?>" />
                    <input type="hidden" name="MBR_CLASS" id="MBR_CLASS" class="textbox" value="<?php echo $MB_CLASS; ?>" />
                    <input type="hidden" name="MBR_TYPE" id="MBR_TYPE" class="textbox" value="<?php echo $MB_TYPE; ?>" />
                    <input type="hidden" name="MBR_TYPE_X" id="MBR_TYPE_X" class="textbox" value="<?php echo $MB_TYPE_X; ?>" />
                    <input type="hidden" name="MBR_DEPT" id="MBR_DEPT" class="textbox" value="<?php echo $MDEPT_CODE1; ?>" />
                    <input type="hidden" name="MBR_STATUS" id="MBR_STATUS" class="textbox" value="<?php echo $MB_STATUS; ?>" />
                    <input type="hidden" name="MBR_D" id="MBR_D" class="textbox" value="<?php echo $MB_D; ?>" />
                    <input type="hidden" name="MBR_M" id="MBR_M" class="textbox" value="<?php echo $MB_M; ?>" />
                    <input type="hidden" name="MBR_Y" id="MBR_Y" class="textbox" value="<?php echo $MB_Y; ?>" />
                    <input type="hidden" name="MBR_PATTNO" id="MBR_PATTNO" class="textbox" value="<?php echo $MB_PATTNO; ?>" />
                    <div class="box-body">
                        <div class="form-group">
                            <select name="MB_CLASS1" id="MB_CLASS1" class="form-control" disabled>
                                <option value="M" <?php if($MB_CLASS == 'M') { ?> selected <?php } ?>> Memo </option>
                                <option value="S" <?php if($MB_CLASS == 'S') { ?> selected <?php } ?>> Surat </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="MB_TYPE1" id="MB_TYPE1" class="form-control" style="width: 100%;" disabled>
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
                            <?php /*?><select name="MB_DEPT1" id="MB_DEPT1" class="form-control" onChange="ShowDocSelect(3);"><?php */?>
                            <select name="MB_DEPT1" id="MB_DEPT1" class="form-control" disabled>
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
                            <select name="MBR_TO[]" id="MBR_TO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Mail To" style="width: 100%;">
                                <?php
                                    $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email FROM tbl_employee ORDER BY First_Name";
                                    $sqlEmp	= $this->db->query($sqlEmp)->result();
                                    foreach($sqlEmp as $row) :
                                        $Emp_ID		= $row->Emp_ID;
                                        $First_Name	= $row->First_Name;
                                        $Last_Name	= $row->Last_Name;
                                        $Email		= $row->Email;
                                        ?>
                                            <option value="<?php echo "$Emp_ID|$Email"; ?>" <?php if($Emp_ID == $MB_FROM_ID) { ?> selected <?php } ?>>
                                                <?php echo "$First_Name $Last_Name - $Email"; ?>
                                            </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="MBR_TOG[]" id="MBR_TOG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Mail To Group" style="width: 100%;">
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
                            <input name="MBR_SUBJECT" id="MBR_SUBJECT" class="form-control" placeholder="&nbsp;Subject:" value="<?php echo $MB_SUBJECT; ?>">
                        </div>
                        <div class="form-group">
                            <textarea name="MBR_MESSAGE" id="compose-textarea" class="form-control" style="height: 300px">
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
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success" onClick="MailStatus(3)" style="display:none">
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
                    <div class="col-md-12">
						<?php
                            $DOC_NUM	= $MB_NO;
                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
							$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
											AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV') AND MDEPT_CODE = '$MDEPT_CODE'";
							$resAPP		= $this->db->query($sqlAPP)->result();
							foreach($resAPP as $rowAPP) :
								$MAX_STEP		= $rowAPP->MAX_STEP;
								$MDEPT_CODE		= $rowAPP->MDEPT_CODE;
								$APPROVER_1		= $rowAPP->APPROVER_1;
								$APPROVER_2		= $rowAPP->APPROVER_2;
								$APPROVER_3		= $rowAPP->APPROVER_3;
								$APPROVER_4		= $rowAPP->APPROVER_4;
								$APPROVER_5		= $rowAPP->APPROVER_5;
							endforeach;
							
                        	if($resCAPP == 0)
                        	{
                        		if($LangID == 'IND')
								{
									$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
								}
								else
								{
									$zerSetApp	= "There are no arrangements for the approval of this document.";
								}
                        		?>
                        			<div class="alert alert-warning alert-dismissible">
					                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                <?php echo $zerSetApp; ?>
					              	</div>
                        		<?php
                        	}
                        ?>
		                <div class="row">
		                    <div class="col-md-12">
		                        <div class="box box-danger collapsed-box">
		                            <div class="box-header with-border">
		                                <h3 class="box-title"><?php echo $Approval; ?></h3>
		                                <div class="box-tools pull-right">
		                                    <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
		                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                                    </button>
		                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                                    </button>
		                                </div>
		                            </div>
		                            <div class="box-body">
							            <div class="box-body no-padding">
			                        		<div class="search-table-outter">
								              	<table id="tbl" class="table table-striped" width="100%" border="0">
													<?php
														$s_STEP		= "SELECT DISTINCT APP_STEP, MDEPT_CODE FROM tbl_docstepapp_det
																		WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND MDEPT_CODE = '$MDEPT_CODE' ORDER BY APP_STEP";
														$r_STEP		= $this->db->query($s_STEP)->result();
														foreach($r_STEP as $rw_STEP) :
															$STEP	    = $rw_STEP->APP_STEP;
															$MDEPT_CODE	= $rw_STEP->MDEPT_CODE;
															$HIDE 	= 0;
															?>
												                <tr>
												                  	<td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
																	<?php
																		$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP'";
									                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
									                                    if($r_APPH_1 > 0)
									                                    {
																			$s_00	= "SELECT DISTINCT A.AH_APPROVER, A.AH_APPROVED,
																							CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																						FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																						WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = $STEP";
																			$r_00	= $this->db->query($s_00)->result();
																			foreach($r_00 as $rw_00) :
																				$APP_EMP_1	= $rw_00->AH_APPROVER;
																				$APP_NME_1	= $rw_00->complName;
																				$APP_DAT_1	= $rw_00->AH_APPROVED;

										                                    	$APPCOL 	= "success";
										                                    	$APPIC 		= "check";
																				?>
																					<td style="width: 2%;">
																						<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																							<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																						</div>
																					</td>
																					<td>
																						<?=$APP_NME_1?><br>
																						<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APP_DAT_1?></span>
																					</td>
																				<?php
																			endforeach;
																		}
																		else
																		{
																			$s_00	= "SELECT DISTINCT A.APPROVER_1, A.MDEPT_CODE,
																							CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																						FROM tbl_docstepapp_det A INNER JOIN tbl_employee B ON A.APPROVER_1 = B.Emp_ID
																						WHERE A.MENU_CODE = '$MenuApp' AND A.PRJCODE = '$PRJCODE' AND A.MDEPT_CODE = '$MDEPT_CODE' AND A.APP_STEP = $STEP";
																			$r_00	= $this->db->query($s_00)->result();
																			foreach($r_00 as $rw_00) :
																				$APP_EMP_1	= $rw_00->APPROVER_1;
																				$MDEPT_CODE	= $rw_00->MDEPT_CODE;
																				$APP_NME_1	= $rw_00->complName;
																				$OTHAPP 	= 0;
																				$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
											                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
											                                    if($r_APPH_1 > 0)
											                                    {
											                                    	$HIDE 	= 1;
											                                    	$s_01	= "SELECT AH_APPROVED FROM tbl_approve_hist
											                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
											                                        $r_01	= $this->db->query($s_01)->result();
											                                        foreach($r_01 as $rw_01):
											                                            $APPDT	= $rw_01->AH_APPROVED;
											                                        endforeach;

											                                    	$APPCOL 	= "success";
											                                    	$APPIC 		= "check";
																					?>
																						<td style="width: 2%;">
																							<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																								<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																							</div>
																						</td>
																						<td>
																							<?=$APP_NME_1?><br>
																							<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																						</td>
																					<?php
											                                    }
											                                    else
											                                    {
											                                    	$APPCOL 	= "danger";
											                                    	$APPIC 		= "close";
											                                    	$APPDT 		= "-";
											                                    	$s_APPH_O	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND MDEPT_CODE = '$MDEPT_CODE')";
												                                    $r_APPH_O	= $this->db->count_all($s_APPH_O);
												                                    if($r_APPH_O > 0)
												                                    	$OTHAPP = 1;
											                                    }
											                                    if($HIDE == 0)
											                                    {
																					?>
																						<td style="width: 2%;">
																							<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																								<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																							</div>
																						</td>
																						<td>
																							<?=$APP_NME_1?><br>
																							<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																						</td>
																					<?php
																				}

																				if($OTHAPP > 0)
																				{
																					$APPDT_OTH 	= "-";
																					$APPNM_OTH 	= "-";
											                                    	$s_01	= "SELECT A.AH_APPROVED, A.AH_APPLEV,
											                                    					CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME
											                                    				FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
											                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND MDEPT_CODE = '$MDEPT_CODE')";
											                                        $r_01	= $this->db->query($s_01)->result();
											                                        foreach($r_01 as $rw_01):
											                                            $APPDT_LEV	= $rw_01->AH_APPLEV;
											                                            $APPDT_OTH	= $rw_01->AH_APPROVED;
											                                            $APPNM_OTH	= $rw_01->COMPLNAME;

												                                    	$APPCOL 	= "success";
												                                    	$APPIC 		= "check";
																						?>
																			                <tr>
																			                  	<td style="width: 10%" nowrap>&nbsp;</td>
																								<td style="width: 2%;">
																									<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																										<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																									</div>
																								</td>
																								<td>
																									<?=$APPNM_OTH?><br>
																									<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT_OTH?></span>
																								</td>
																							</tr>
																						<?php
											                                        endforeach;
											                                    }
																			endforeach;
																		}
																	?>
																</tr>
															<?php
														endforeach;
													?>
								              	</table>
							              	</div>
							            </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
			        </div>
                </form>
            </div>
        </div>
	</div>
    <?php
		$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// if($DefEmp_ID == 'D15040004221')
			echo "<font size='1'><i>$act_lnk</i></font>";
	?>
</section>
                    
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