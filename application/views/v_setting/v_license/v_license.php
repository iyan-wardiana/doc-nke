<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 01 Maret 2021
    * File Name	    = v_license.php
    * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_init  = $this->session->userdata('comp_init');
$app_name   = $this->session->userdata('app_name');

$srvURL     = $_SERVER['SERVER_ADDR'];

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows  = $row->Display_Rows;
	$decFormat     = $row->decFormat;
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
            $vers   = $this->session->userdata['vers'];

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
    	$this->load->view('template/mna');
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
            if($TranslCode == 'CompanyCode')$CompanyCode = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'actKey')$actKey = $LangTransl;
    		if($TranslCode == 'license')$license = $LangTransl;
    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/certification_00.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
                <small><?php echo $license; ?></small>
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
                        	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveCategory()">
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $CompanyCode ?></label>
                                  	<div class="col-sm-10">
                                        <input type="text" name="VendCat_Code1" id="VendCat_Code1" class="form-control" value="<?php echo $comp_init; ?>" disabled/>
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $CompanyName ?></label>
                                  	<div class="col-sm-10">
                                    	<input type="text" class="form-control" name="VendCat_Name" id="VendCat_Name" value="<?php echo $appName; ?>" disabled />
                                  	</div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Private Key</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="VendCat_Name" id="VendCat_Name" value="<?php //echo $VendCat_Name; ?>" disabled />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $actKey ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="VendCat_Name" id="VendCat_Name" value="<?php //echo $VendCat_Name; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
										<button class="btn btn-primary">
										<i class="fa fa-key"></i></button>&nbsp;
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
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
		/*CheckThe_Code = document.getElementById('CheckThe_Code').value;
		if(CheckThe_Code > 0)
		{
			swal('Vendor Category Code is already exist.');
			document.getElementById('VendCat_Code').value = '';
			document.getElementById('VendCat_Code').focus();
			VendCat_Code = document.getElementById('VendCat_Code').value;
			functioncheck()
			return false;
		}*/
        
        VendCat_Code    = document.getElementById('VendCat_Code').value;
        VendCat_Name    = document.getElementById('VendCat_Name').value;
        Acc_DirParentA  = document.getElementById('Acc_DirParentA').value;
        Acc_DirParentB  = document.getElementById('Acc_DirParentB').value;
        if(VendCat_Code == '')
        {
            swal('<?php echo $venCatEmpty; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#VendCat_Code1').focus();
            });
            return false;           
        }
        
        if(VendCat_Name == '')
        {
            swal('<?php echo $catNmEmpt; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#VendCat_Name').focus();
            });
            return false;           
        }
        
        if(Acc_DirParentA == '')
        {
            swal('<?php echo $selInvAcc; ?>',
            {
                icon: "warning",
            });
            return false;           
        }
        
        if(Acc_DirParentB == '')
        {
            swal('<?php echo $selDPAcc; ?>',
            {
                icon: "warning",
            });
            return false;           
        }
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
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>