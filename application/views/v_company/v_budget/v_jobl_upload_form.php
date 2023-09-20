<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 5 Juli 2019
    * File Name	= v_boq_upload_form.php
    * Location		= -
*/

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
        	if($TranslCode == 'Save')$Save = $LangTransl;
        	if($TranslCode == 'Update')$Update = $LangTransl;	
        	if($TranslCode == 'Back')$Back = $LangTransl;	
        	if($TranslCode == 'Upload')$Upload = $LangTransl;

        endforeach;

        if($LangID == 'IND')
        {
            $alert1 = "Silahkan pilih file excel yang akan Anda upload.";
            $alert2 = "Anda hanya dapat mengupload file xls atau xlsx.";
        }
        else
        {
            $alert1 = "Please select an excel file that will you uploaded.";
            $alert2 = "You can upload xls or xlsx File Type only.";
        }
        $isLoadDone_1   = 1;
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                <?php echo $h2_title; ?>
                <small><?php echo $PRJNAME; ?></small>
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
                            <br>
                            <div class="box-body chart-responsive">
                                <form name="myformupload" id="myformupload" class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo $form_action; ?>" onSubmit="return chcekFile();" >
                                    <div class="form-group">
                                      	<label for="inputName" class="col-sm-2 control-label">Upload BoQ</label>
                                      	<div class="col-sm-10">
                                        <input type="text" style="display:none" name="isUploaded" id="isUploaded" value="<?php echo $isUploaded; ?>" />
                                   		<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                        <input type="file" name="userfile" id="userfile1" class="filestyle" data-buttonName="btn-primary"/>
                                      	</div>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label for="inputName" class="col-sm-2 control-label">Tipe</label>
                                        <div class="col-sm-10">
                                            <select name="BOQH_TYPE" id="BOQH_TYPE" class="form-control select2" style="max-width:120px" >
                                                <option value="1">Master</option>
                                                <option value="2" selected>Periode</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="BOQH_DESC"  id="BOQH_DESC" style="height:70px"><?php echo $BOQH_DESC; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-10">
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <h4><i class="icon fa fa-ban"></i> Peringatan ... !!!</h4>
                                                1. Hindari penggunaan SPASI pada nama file excel (*.xls) yang akan diupload.<br>
                                                2. Setelah upload file, tidak otomatis merubah data yang ada.<br>
                                                3. Perubahan data akan terjadi setelah file ini diproses.<br>
                                                4. Upload ini digunakan untuk mengimport data BoQ dan RAP proyek yang bersangkutan.
                                              </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <!--<input type="submit" value="Upload File" class="btn btn-warning" style="width:120px;" />&nbsp;&nbsp;-->
                                            
                                            <?php
                                                echo "<button class='btn btn-primary'><i class='glyphicon glyphicon-upload'></i></button>&nbsp;";
                                                echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
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
	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
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
  
	function chcekFile()
	{
		var userfile1 = document.getElementById('userfile1').value;
		
		if(userfile1 == '')
		{
			swal("", "<?php echo $alert1; ?>", "warning");
			return false;
		}
		
		var myExt	= getFileExtension(userfile1);
		
		if(myExt != 'xls' && myExt != 'xlsx')
		{
			swal("", "<?php echo $alert2; ?>", "error");
			return false;
		}
	}
	
	function getFileExtension(filename)
	{
	  var ext = /^.+\.([^.]+)$/.exec(filename);
	  return ext == null ? "" : ext[1];
	}
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>