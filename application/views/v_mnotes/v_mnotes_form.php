<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2020
 * File Name	= v_tsemp_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

if($task == 'add')
{
	$NTLN_CODE		= date('YmdHis');
	$NTLN_DATE		= date('m/d/Y');
	$NTLN_START    	= '06:00';
	$NTLN_END      	= '16:30';
    $NTLN_LOC       = '';
    $NTLN_TOPIC     = '';
	$NTLN_DESC		= '';
	$NTLN_USER	    = '';
    $NTLN_THEORY    = '';
}
else
{
	$NTLN_CODE      = $default['NTLN_CODE'];
    $NTLN_DATE      = date('m/d/Y', strtotime($default['NTLN_DATE']));
    $NTLN_START     = date('H:i', strtotime($default['NTLN_START']));
    $NTLN_END       = date('H:i', strtotime($default['NTLN_END']));
    $NTLN_LOC       = $default['NTLN_LOC'];
    $NTLN_TOPIC     = $default['NTLN_TOPIC'];
    $NTLN_DESC      = $default['NTLN_DESC'];
    $NTLN_USER      = $default['NTLN_USER'];
    $NTLN_THEOR     = $default['NTLN_THEORY'];
}

$empNameAct = '';
$sqlEMP     = "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
                FROM tbl_employee
                WHERE Emp_ID = '$DefEmp_ID'";
$resEMP     = $this->db->query($sqlEMP)->result();
foreach($resEMP as $rowEMP) :
    $empNameAct = $rowEMP->empName;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk  = $rowcss->cssjs_lnk;
              ?>
                  <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              <?php
          endforeach;

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk1  = $rowcss->cssjs_lnk;
              ?>
                  <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              <?php
          endforeach;
        ?>

        <!-- Google Font -->
        <!-- summernote -->
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/summernote/summernote.css';?>">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

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
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Save')$Save = $LangTransl;
    		if($TranslCode == 'Update')$Update = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'Address')$Address = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'ContactPerson')$ContactPerson = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;

    	endforeach;
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">
            <h1>
                <?php echo $h2_title; ?>
                <small><?php echo $empNameAct; ?></small>
              </h1>
            </section>

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
                            <?php
                                $message = $this->session->flashdata('msg');
                                if(isset($message)) {
                                    echo '<div class="alert alert-warning alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            '.$message.'
                                        </div>';
                                    $this->session->unset_userdata('msg');
                                }
                            ?>
                            	<form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()" enctype="multipart/form-data">
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?></label>
                                      	<div class="col-sm-10">
                                            <input type="text" class="form-control" name="NTLN_CODE" id="NTLN_CODE" value="<?php echo $NTLN_CODE; ?>" style="display:none"/>
                                            <input type="text" class="form-control" name="NTLN_CODE1" id="NTLN_CODE1" value="<?php echo $NTLN_CODE; ?>" disabled />
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Tanggal</label>
                                        <div class="col-sm-10">
                                          <div class="input-group date">
                                              <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                              <input type="text" name="NTLN_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $NTLN_DATE; ?>" style="width:105px">
                                            <input type="hidden" class="form-control" name="NTLN_DATE" id="NTLN_DATE" value="<?php echo $NTLN_DATE; ?>" />
                                          </div> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">Jam Mulai</label>
                                      	<div class="col-sm-10">
                                            <input type="text" class="form-control" style="max-width:80px" name="NTLN_START" id="NTLN_START" value="<?php echo $NTLN_START; ?>" onKeyUp="toTimeString1(this.value)" >
                                      	</div>
                                    </div>
                                    <script>
            							function toTimeString1(NTLN_START)
            							{
            								var totTxt 	= NTLN_START.length;
            								var noHour	= /^[0-2]+$/;
            								var noMinut	= /^[0-5]+$/;
            								if(totTxt == 1)
            								{
            									isHour	= document.getElementById('NTLN_START').value;
            									if(!isHour.match(noHour))
            									{
            										alert('Range no [0 - 2]');
            										document.getElementById('NTLN_START').value = 0;
            										document.getElementById('NTLN_START').focus();
            										return false;
            									}
            								}
            								if(totTxt == 2)
            								{
            									isHour	= document.getElementById('NTLN_START').value;
            									if(isHour > 24)
            									{
            										alert('Hour must be less then 24');
            										document.getElementById('NTLN_START').value = '';
            										document.getElementById('NTLN_START').focus();
            										return false;
            									}
            									else
            									{
            										document.getElementById('NTLN_START').value = isHour+':';
            										document.getElementById('NTLN_START').focus();
            									}
            								}
            								
            								if(totTxt == 4)
            								{
            									isHour		= document.getElementById('NTLN_START').value;
            									isMinutes	= isHour.substr(3,4);
            									if(!isMinutes.match(noMinut))
            									{
            										alert('Range no [0 - 5]');
            										isHour	= isHour.substr(0,3);
            										document.getElementById('NTLN_START').value = isHour;
            										document.getElementById('NTLN_START').focus();
            										return false;
            									}									
            								}
            							}
            						</script>
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">Jam Selesai</label>
                                      	<div class="col-sm-10">
                                            <input type="text" class="form-control" style="max-width:80px" name="NTLN_END" id="NTLN_END" value="<?php echo $NTLN_END; ?>" onKeyUp="toTimeString2(this.value)" >
                                      	</div>
                                    </div>
                                    <script>
            							function toTimeString2(NTLN_END)
            							{
            								var totTxt 	= NTLN_END.length;
            								var noHour	= /^[0-2]+$/;
            								var noMinut	= /^[0-5]+$/;
            								if(totTxt == 1)
            								{
            									isHour	= document.getElementById('NTLN_END').value;
            									if(!isHour.match(noHour))
            									{
            										alert('Range no [0 - 2]');
            										document.getElementById('NTLN_END').value = 0;
            										document.getElementById('NTLN_END').focus();
            										return false;
            									}
            								}
            								if(totTxt == 2)
            								{
            									isHour	= document.getElementById('NTLN_END').value;
            									if(isHour > 24)
            									{
            										alert('Hour must be less then 24');
            										document.getElementById('NTLN_END').value = '';
            										document.getElementById('NTLN_END').focus();
            										return false;
            									}
            									else
            									{
            										document.getElementById('NTLN_END').value = isHour+':';
            										document.getElementById('NTLN_END').focus();
            									}
            								}
            								
            								if(totTxt == 4)
            								{
            									isHour		= document.getElementById('NTLN_END').value;
            									isMinutes	= isHour.substr(3,4);
            									if(!isMinutes.match(noMinut))
            									{
            										alert('Range no [0 - 5]');
            										isHour	= isHour.substr(0,3);
            										document.getElementById('NTLN_END').value = isHour;
            										document.getElementById('NTLN_END').focus();
            										return false;
            									}									
            								}
            							}
            						</script>
                                    
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Topik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="NTLN_TOPIC" id="NTLN_TOPIC" value="<?php echo $NTLN_TOPIC; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Lokasi Rapat</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="NTLN_LOC" id="NTLN_LOC" value="<?php echo $NTLN_LOC; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Isi Notulen</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control textarea" name="NTLN_DESC" id="NTLN_DESC" style="height:70px" ><?php echo $NTLN_DESC; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Bertemu dengan</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="NTLN_USER" id="NTLN_USER" value="<?php echo $NTLN_USER; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Upload Materi *)<br><span style="font-size: 8pt; font-style: italic;">*File PDF Only</span></label>
                                        <div class="col-sm-10">
                                            <input type="file" name="NTLN_THEORY" id="NTLN_THEORY" size="20" />
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button class="btn btn-primary">
                                            <i class="fa fa-save"></i></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                            </button>&nbsp;
            								<?php	
                                                echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                                            ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
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
<!-- Summernote -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/summernote/summernote.min.js';?>"></script>
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

    $('.textarea').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']]
        ]
    });
    $('.dropdown-toggle').dropdown()
  });
</script>

<script>
	function checkInp()
	{
		EMPTS_STIME	= document.getElementById("EMPTS_STIME").value;
		if(EMPTS_STIME == '00:00')
		{
			alert("Masukan waktu mulai kegiatan.");
			document.getElementById("EMPTS_STIME").value = '00:00';
			document.getElementById("EMPTS_STIME").focus();
			return false;
		}
		
		EMPTS_ETIME		= document.getElementById("EMPTS_ETIME").value;
		if(EMPTS_ETIME == '00:00')
		{
			alert("Masukan waktu selesai kegiatan.");
			document.getElementById("EMPTS_ETIME").value = '23:59';
			document.getElementById("EMPTS_ETIME").focus();
			return false;
		}
		
		EMPTS_DESK	= document.getElementById("EMPTS_DESK").value;
		if(EMPTS_DESK == '')
		{
			alert("Silahkan isi catatan kegiatan yang Anda lakukan hari ini.");
			document.getElementById("EMPTS_DESK").focus();
			return false;
		}
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->

<?php
$this->load->view('template/foot');
?>