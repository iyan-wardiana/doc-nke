<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= department_form.php
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

if($task == "add")
{
	$DEPCODE 	= '';		
	$DEPDESC 	= '';
	$DEPINIT	= 1;
}	
else
{
	$DEPCODE 	= $default['DEPCODE'];		
	$DEPDESC 	= $default['DEPDESC'];
	$DEPINIT	= $default['DEPINIT'];
}
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

    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <?php
        $this->load->view('template/mna');
        //______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');
	
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
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;
    		if($TranslCode == 'Save')$Save = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'Update')$Update = $LangTransl;
    		if($TranslCode == 'Department')$Department = $LangTransl;
    		if($TranslCode == 'Organization')$Organization = $LangTransl;
    		
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;

    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo "$Add $Department"; ?>
                <small><?php echo $Organization; ?></small>
            </h1>
        </section>

        <section class="content">	
            <div class="box box-primary">
                <div class="box-header with-border" style="display: none;">
                    <h3 class="box-title">&nbsp;</h3>                
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkData()">
                      	<div class="form-group">
                        	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?>  </label>
                        	<div class="col-sm-10">
                            	<?php
        							if($task == "add")
        							{
        								?>
                            				<input type="text" name="DEPCODE" id="DEPCODE" value="" class="form-control" style="max-width:250px">
        								<?php
        							}
        							else
        							{
        								?>
                            				<input type="text" name="DEPCODEX" id="DEPCODEX" value="<?php echo $DEPCODE; ?>" class="form-control" style="max-width:250px" disabled>
                            				<input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>" class="form-control" style="max-width:250px">
        								<?php
        							}
        						?>
                            </div>
                      	</div>
                        <div class="form-group">
                          	<label class="col-sm-2 control-label"><?php echo $Name ?>  </label>
                          	<div class="col-sm-10">
                            	<input type="text" name="DEPDESC" id="DEPDESC" value="<?php echo $DEPDESC; ?>" class="form-control" style="max-width:250px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Status ?> </label>
                            <div class="col-sm-10">
                            	<select name="DEPINIT" id="DEPINIT" class="form-control" style="max-width:100px" >
                                    <option value="1" <?php if($DEPINIT == 1) { ?> selected <?php } ?>><?php echo $Active ?> </option>
                                    <option value="2" <?php if($DEPINIT == 2) { ?> selected <?php } ?>><?php echo $Inactive ?> </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?php
                                    if($ISCREATE == 1)
                                    {
                                        if($task=='add')
                                        {
                                            ?>
                                                <button class="btn btn-primary" id="btnSave">
                                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                                </button>&nbsp;
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <button class="btn btn-primary" id="btnSave">
                                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                                </button>&nbsp;
                                            <?php
                                        }
                                    }
                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                                ?>
                            </div>
                        </div>
                    </form>
                    <?php
                        $DefID      = $this->session->userdata['Emp_ID'];
                        $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
                    <script>
        				function checkData()
        				{
        					DEPCODE = document.getElementById('DEPCODE').value;
        					DEPDESC = document.getElementById('DEPDESC').value;
        					if(DEPCODE == '')
        					{
        						alert('Please input Department Code.');
        						document.getElementById("DEPCODE").focus();
        						return false;
        					}
        					if(DEPDESC == '')
        					{
        						alert('Please input Department Name.');
        						document.getElementById("DEPDESC").focus();
        						return false;
        					}		
        				}
        			</script>
                </div>
            </div>
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