<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 April 2017
 * File Name	= chatlist.php
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

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";
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
		<?php
			$url_AddChat	= site_url('c_chat/c_chat/addchat/?id='.$this->url_encryption_helper->encode_url($appName));
		?>
		<script>
            var url = "<?php echo $url_AddChat;?>";
            function createChat()
            {
                title = 'Select Item';
                w = 500;
                h = 550;
                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                var left = (screen.width/2)-(w/2);
                var top = (screen.height/2)-(h/2);
                return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
            }
        </script>
        <?php
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			$sqlC		= "tbl_chat WHERE CHAT_EMP_FROM = '$DefEmp_ID' OR CHAT_EMP_TO = '$DefEmp_ID' AND CHAT_READ = 0";
			$resC		= $this->db->count_all($sqlC);
		?>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-wechat"></i></span>
                    
                	<div class="info-box-content">
                		<span class="info-box-text"><a href="javascript:void(null);" onClick="createChat();">Create Chat</a></span>
                		<span class="info-box-number"><?php echo $resC; ?></span>
                	</div>
                </div>
            </div>
        </div>
        <div class="row">
        	<?php				
				// SAVE MESSAGE
				$THEROWACT	= 0;
				if(isset($_POST['THEROWACT']))
				{
					$THEROWACT	= $_POST['THEROWACT'];
				}
			?>
                	<form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                        <input type="text" name="THEROWACT" id="THEROWACT" class="textbox" value="<?php echo $THEROWACT; ?>" />
                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                    </form>
        	<?php
				// START : GET USER AKTIF PHOTO
					$imgemp_fnSendX	= '';
					$getIMGSend		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$DefEmp_ID'";
					$resIMGSend 		= $this->db->query($getIMGSend)->result();
					foreach($resIMGSend as $rowIMGSend) :
						$imgemp_fnSend 	= $rowIMGSend ->imgemp_filename;
						$imgemp_fnSendX = $rowIMGSend ->imgemp_filenameX;
					endforeach;
					$imgSender		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID.'/'.$imgemp_fnSendX);
				// END : GET SENDER PHOTO
				
				$CHAT_RECID		= '';
				$sqlCHATH 		= "SELECT CHAT_EMP_FROM, CHAT_EMP_TO, CHAT_EMP_SENDER
									FROM tbl_chat 
									WHERE CHAT_EMP_FROM = '$DefEmp_ID' OR CHAT_EMP_TO = '$DefEmp_ID'
									GROUP BY CHAT_EMP_TO
									ORDER BY CHAT_TIME DESC";
				$resCHATH 		= $this->db->query($sqlCHATH)->result();
				$theRow	= 0;
				foreach($resCHATH as $rowCHATH) :
					$theRow			= $theRow + 1;
					// RECEIVER IS NON ACTIVE USER, WHO SENDED BY USER ACTIVE
					$CHAT_EMP_FROM 		= $rowCHATH->CHAT_EMP_FROM;
					$CHAT_EMP_TO		= $rowCHATH->CHAT_EMP_TO;
					$CHAT_EMP_SENDER	= $rowCHATH->CHAT_EMP_SENDER;
					
					if($DefEmp_ID == $CHAT_EMP_FROM)
					{
						$CHAT_EMP_TOT    = $CHAT_EMP_TO;
					}
					else
					{
						$CHAT_EMP_TOT   = $CHAT_EMP_FROM;
					}
					
					$CompName	= '';
					$First_Name	= '';
					$Last_Name	= '';
					$sqlEmp 	= "SELECT First_Name, Last_Name
									FROM tbl_employee WHERE Emp_ID = '$CHAT_EMP_TOT'";
					$resEmp		= $this->db->query($sqlEmp)->result();
					foreach($resEmp as $rowEmp) :
						$First_Name = $rowEmp->First_Name;
						$Last_Name	= $rowEmp->Last_Name;
					endforeach;
					//$CompName	= "$First_Name $Last_Name";					
					$Last_Name1		= " $Last_Name";
					$CHAT_SENDNAME1	= "$First_Name$Last_Name1";
					$CHAT_SENDNAME2	= strtolower($CHAT_SENDNAME1);
					$CHAT_SENDNAMEA	= ucwords($CHAT_SENDNAME2);
					
					// GET RECEIVE DATA
					$imgemp_fnRec 	= '';
					$imgemp_fnRecX 	= '';
					$getIMGRec		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$CHAT_EMP_TOT'";
					$resIMGRec 		= $this->db->query($getIMGRec)->result();
					foreach($resIMGRec as $rowIMGRec) :
						$imgemp_fnRec 	= $rowIMGRec ->imgemp_filename;
						$imgemp_fnRecX 	= $rowIMGRec ->imgemp_filenameX;
					endforeach;
					$imgToEmp		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$CHAT_EMP_TOT.'/'.$imgemp_fnRecX);
					if($imgemp_fnRec == 'username')
						$imgToEmp	= base_url('assets/AdminLTE-2.0.5/emp_image/'.$imgemp_fnRecX);
					
					/*$sqlC			= "tbl_chat WHERE ((CHAT_EMP_FROM = '$CHAT_EMP_FROM' AND CHAT_EMP_TO = '$CHAT_EMP_TO') 
										OR (CHAT_EMP_SENDER = '$CHAT_EMP_FROM' AND CHAT_EMP_SENDER = '$CHAT_EMP_TO'))
										AND CHAT_READ = 0 ORDER BY CHAT_TIME ASC";
					$resC			= $this->db->count_all($sqlC);*/
					
					$sqlC			= "tbl_chat WHERE ((CHAT_EMP_FROM = '$CHAT_EMP_FROM' AND CHAT_EMP_TO = '$CHAT_EMP_TO') 
										OR (CHAT_EMP_SENDER = '$CHAT_EMP_FROM' AND CHAT_EMP_SENDER = '$CHAT_EMP_TO'))
										AND CHAT_READ = 0 ORDER BY CHAT_TIME ASC";
					$resC			= $this->db->count_all($sqlC);
					?>
                        <div class="col-md-3">
                            <!-- DIRECT CHAT PRIMARY -->
                            <div class="box box-primary direct-chat direct-chat-primary">
                                <!-- START : HEADER -->
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $CHAT_SENDNAMEA; ?></h3>            
                                        <div class="box-tools pull-right">
                                            <span data-toggle="tooltip" title="<?php echo $resC; ?> New Chat" class="badge bg-light-blue" <?php if($resC == 0) { ?> style="display:none" <?php } ?>><?php echo $resC; ?></span>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus" title="Double Click" <?php if($resC > 0) { ?> onClick="readAll('<?php echo $theRow; ?>')" <?php } ?>></i></button>
                                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle" style="display:none">
                                                <i class="fa fa-comments"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="chatRowR_<?php echo $theRow; ?>" id="chatRowR_<?php echo $theRow; ?>" value="<?php echo $theRow; ?>" >
                                    <input type="hidden" name="CHAT_EMP_FROMR_<?php echo $theRow; ?>" id="CHAT_EMP_FROMR_<?php echo $theRow; ?>" value="<?php echo $CHAT_EMP_FROM; ?>" >
                                    <input type="hidden" name="CHAT_EMP_TOR_<?php echo $theRow; ?>" id="CHAT_EMP_TOR_<?php echo $theRow; ?>" value="<?php echo $CHAT_EMP_TO; ?>" >
                                <!-- END : HEADER -->
                                <!-- START : CONTENT -->
                                    <div class="box-body" <?php if($THEROWACT != $theRow) { ?> style="display:none" <?php } ?>>
                                        <div class="direct-chat-messages">
                                        <?php
											$sqlCHATD		= "SELECT CHAT_CODE, CHAT_EMP_FROM, CHAT_EMP_TO, CHAT_MESSAGE, 
																CHAT_TIME, CHAT_EMP_SENDER, CHAT_READ
																FROM tbl_chat 
																WHERE (CHAT_EMP_FROM = '$CHAT_EMP_FROM' AND CHAT_EMP_TO = '$CHAT_EMP_TO') 
																OR (CHAT_EMP_SENDER = '$CHAT_EMP_FROM' AND CHAT_EMP_SENDER = '$CHAT_EMP_TO') ORDER BY CHAT_TIME ASC";
											$resCHATD 		= $this->db->query($sqlCHATD)->result();
											foreach($resCHATD as $rowCHATD) :
												$CHAT_EMP_FROM 		= $rowCHATD->CHAT_EMP_FROM;
												$CHAT_EMP_TO		= $rowCHATD->CHAT_EMP_TO;
												$CHAT_MESSAGE		= $rowCHATD->CHAT_MESSAGE;
												$CHAT_TIME			= $rowCHATD->CHAT_TIME;
												$CHAT_EMP_SENDER	= $rowCHATD->CHAT_EMP_SENDER;
												
												$CHAT_EMP_TOT		= $CHAT_EMP_TOT; // Tujuan												
												if($CHAT_EMP_TOT == $CHAT_EMP_FROM)
												{
													$CHAT_EMP_FROMX	= $CHAT_EMP_TO;
												}
												else
												{
													$CHAT_EMP_FROMX  = $CHAT_EMP_FROM;
												}
												
												$First_Name		= '';
												$Last_Name		= '';
												$sqlEmp 		= "SELECT First_Name, Last_Name
																	FROM tbl_employee WHERE Emp_ID = '$CHAT_EMP_FROMX'";
												$resEmp			= $this->db->query($sqlEmp)->result();
												foreach($resEmp as $rowEmp) :
													$First_Name = $rowEmp->First_Name;
													$Last_Name	= $rowEmp->Last_Name;
												endforeach;
												//$CompName	= "$First_Name $Last_Name";					
												$Last_Name1		= " $Last_Name";
												$CHAT_SENDNAME1	= "$First_Name$Last_Name1";
												$CHAT_SENDNAME2	= strtolower($CHAT_SENDNAME1);
												$CHAT_SENDNAMEB	= ucwords($CHAT_SENDNAME2);
						
												/*$CHAT_SENDDATE 	= $rowCHATD->CHAT_SENDDATE;
												$CHAT_CONTSEND 	= $rowCHATD->CHAT_CONTSEND;
												$CHAT_RECDATE	= $rowCHATD->CHAT_RECDATE;
												$CHAT_CONTREC	= $rowCHATD->CHAT_CONTREC;
												$CHAT_SENDSTAT	= $rowCHATD->CHAT_SENDSTAT;
												$CHAT_RECSTAT	= $rowCHATD->CHAT_RECSTAT;*/
												
												if($CHAT_EMP_SENDER != $DefEmp_ID) 
												{
													?>
                                                    <!-- START : RECEIVER AREA -->
                                                        <div class="direct-chat-msg">
                                                            <div class="direct-chat-info clearfix">
                                                                <span class="direct-chat-name pull-left"><?php echo $CHAT_SENDNAMEA; ?></span>
                                                                <span class="direct-chat-timestamp pull-right"><?php echo $CHAT_TIME; ?></span>
                                                            </div>
                                                            <img class="direct-chat-img" src="<?php echo $imgToEmp; ?>" alt="Message User Image">
                                                            <div class="direct-chat-text">
                                                                <?php echo $CHAT_MESSAGE; ?>
                                                            </div>
                                                        </div>
                                                    <!-- END : RECEIVER AREA -->
                                                    <?php
												}
												else
												{
													?>
                                                    <!-- START : SENDER AREA / USER AKTIF -->
                                                        <div class="direct-chat-msg right">
                                                            <div class="direct-chat-info clearfix">
                                                                <span class="direct-chat-name pull-right"><?php echo $CHAT_SENDNAMEB; ?></span>
                                                                <span class="direct-chat-timestamp pull-left"><?php echo $CHAT_TIME; ?></span>
                                                            </div>
                                                            <img class="direct-chat-img" src="<?php echo $imgSender; ?>" alt="Message User Image">
                                                            <div class="direct-chat-text">
                                                                <?php echo $CHAT_MESSAGE; ?>
                                                            </div>
                                                        </div>
                                                    <!-- END : SENDER AREA / USER AKTIF -->
                                            		<?php
												}
											endforeach;
										?>
                                        </div>        
                                        <!-- Contacts are loaded here HOLDED -->
                                        <div class="direct-chat-contacts">
                                            <ul class="contacts-list">
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="" alt="User Image">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Count Dracula
                                                                <small class="contacts-list-date pull-right">2/28/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">How have you been? I was...</span>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <!-- END : CONTENT --> 
                                <!-- START : MESSAGES --> 
                                    <div class="box-footer" <?php if($THEROWACT != $theRow) { ?> style="display:none" <?php } ?>>
                                        <form action="" method="post" >
                                            <div class="input-group">
                                                <input type="hidden" name="chatRow_<?php echo $theRow; ?>" id="chatRow_<?php echo $theRow; ?>" value="<?php echo $theRow; ?>" >
                                                <input type="hidden" name="CHAT_EMP_FROM_<?php echo $theRow; ?>" id="CHAT_EMP_FROM_<?php echo $theRow; ?>" value="<?php echo $CHAT_EMP_FROM; ?>" >
                                                <input type="hidden" name="CHAT_EMP_TO_<?php echo $theRow; ?>" id="CHAT_EMP_TO_<?php echo $theRow; ?>" value="<?php echo $CHAT_EMP_TO; ?>" >
                                                <input type="text" name="CHAT_MESSAGE_<?php echo $theRow; ?>" id="CHAT_MESSAGE_<?php echo $theRow; ?>" placeholder="Type Message ..." class="form-control">
                                                <span class="input-group-btn">
                                                    <button type="button" name="submit_<?php echo $theRow; ?>" dir="submit_<?php echo $theRow; ?>" onClick="functionInput('<?php echo $theRow; ?>')" class="btn btn-primary btn-flat">Send</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                <!-- END : MESSAGES --> 
                            </div>
                        </div>
            		<?php
				endforeach;
			?>
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
	function readAll(theRow)
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
				document.getElementById("THEROWACT").value = recordcount;
				document.frmsrch1.submitSrch1.click();
				if(recordcount > 0)
				{
					//document.getElementById('CheckThe_Code').value= recordcount;
					//document.getElementById("isHidden").innerHTML = ' Recomendation No already exist ... !';
					//document.getElementById("isHidden").style.color = "#ff0000";
				}
				else
				{
					//document.getElementById('CheckThe_Code').value= recordcount;
					//document.getElementById("isHidden").innerHTML = ' Recomendation No : OK .. !';
					//document.getElementById("isHidden").style.color = "green";
				}
			}
		}
		var chatRowR_ 		= document.getElementById('chatRowR_'+theRow).value;
		var CHAT_EMP_FROMR_	= document.getElementById('CHAT_EMP_FROMR_'+theRow).value;
		var CHAT_EMP_TOR_ 	= document.getElementById('CHAT_EMP_TOR_'+theRow).value;
		var COLL_DATA		= ''+chatRowR_+'~'+CHAT_EMP_FROMR_+'~'+CHAT_EMP_TOR_+'';
		
		ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_chat/c_chat/getReadAll/';?>" + COLL_DATA, true);
		ajaxRequest.send(null);
	}
	
	function checkPref(theRow)
	{
		/*document.getElementById('AS_PREFIXX').value = theRow;
		//document.frmsrch1.submitSrch1.click();
		
		document.frm_+theRow.submit_+theRow.click();*/
	}
	
	function refreshPage()
	{
		window.location.reload(true);
	}
							
	function functionInput(theRow)
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
				document.getElementById("THEROWACT").value = recordcount;
				document.frmsrch1.submitSrch1.click();
				if(recordcount > 0)
				{
					//document.getElementById('CheckThe_Code').value= recordcount;
					//document.getElementById("isHidden").innerHTML = ' Recomendation No already exist ... !';
					//document.getElementById("isHidden").style.color = "#ff0000";
				}
				else
				{
					//document.getElementById('CheckThe_Code').value= recordcount;
					//document.getElementById("isHidden").innerHTML = ' Recomendation No : OK .. !';
					//document.getElementById("isHidden").style.color = "green";
				}
			}
		}
		var chatRow_ 		= document.getElementById('chatRow_'+theRow).value;
		var CHAT_EMP_FROM_ 	= document.getElementById('CHAT_EMP_FROM_'+theRow).value;
		var CHAT_EMP_TO_ 	= document.getElementById('CHAT_EMP_TO_'+theRow).value;
		var CHAT_MESSAGE_a 	= document.getElementById('CHAT_MESSAGE_'+theRow).value;
		var CHAT_MESSAGE_b 	= CHAT_MESSAGE_a.replace(".","|");
		var CHAT_MESSAGE_c 	= CHAT_MESSAGE_b.replace(",","`");
		var CHAT_MESSAGE_ 	= CHAT_MESSAGE_c.replace("?","^");
		if(CHAT_MESSAGE_ == '')
		{
			alert('Please write a message.');
			document.getElementById('CHAT_MESSAGE_'+theRow).focus();
			return false;
		}
		
		var COLL_DATA		= ''+chatRow_+'~'+CHAT_EMP_FROM_+'~'+CHAT_EMP_TO_+'~'+CHAT_MESSAGE_+'';
		
		ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_chat/c_chat/getSentDat/';?>" + COLL_DATA, true);
		ajaxRequest.send(null);
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>