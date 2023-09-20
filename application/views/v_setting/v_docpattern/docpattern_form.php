<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 2 Februari 2017
 * File Name    = docpattern_form.php
 * Location     = -
*/
?>
<?php
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
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
$decFormat      = 2;

if($task == 'add')
{
    $thisyear = date('Y');
    $default['Pattern_YearAktive'] = $thisyear;
    $default['useYear'] = 1;
    $default['useMonth'] = 1;
    $default['useDate'] = 1;
}

$LangID   = $this->session->userdata['LangID'];
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
        $this->load->view('template/mna');
        //______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');
        
        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];$LangID   = $this->session->userdata['LangID'];

        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;
            
            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Edit')$Edit = $LangTransl;
            if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Update')$Update = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'PatternCode')$PatternCode = $LangTransl;
            if($TranslCode == 'PatternPosition')$PatternPosition = $LangTransl;
            if($TranslCode == 'PatternforDocument')$PatternforDocument = $LangTransl;
            if($TranslCode == 'PatternName')$PatternName = $LangTransl;
            if($TranslCode == 'PatternYear')$PatternYear = $LangTransl;
            if($TranslCode == 'PatternMonth')$PatternMonth = $LangTransl;
            if($TranslCode == 'PatternDate')$PatternDate = $LangTransl;
            if($TranslCode == 'PatternDocumentLength')$PatternDocumentLength = $LangTransl;

        endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h2_title; ?>
                <small>setting</small>
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
                            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkData()">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternCode ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="Pattern_ID" id="Pattern_ID" class="form-control" style="max-width:350px" value="<?php echo set_value('Pattern_ID', isset($default['Pattern_ID']) ? $default['Pattern_ID'] : ''); ?>" /> 
                                        <input type="text" maxlength="5" name="Pattern_Code" id="Pattern_Code" class="form-control" value="<?php echo set_value('Pattern_Code', isset($default['Pattern_Code']) ? $default['Pattern_Code'] : ''); ?>" placeholder="Max. 5 char" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternPosition ?></label>
                                    <div class="col-sm-10">
                                        <select name="Pattern_Position" id="Pattern_Position" class="form-control select2">
                                            <?php
                                                $nowPosition = $default['Pattern_Position'];
                                                $Pattern_NameEdited = $default['Pattern_NameEdited'];
                                            ?>
                                            <option value="Normal" <?php if($nowPosition == 'Normal') { ?>selected<?php } ?> style="display:none">Normal</option>
                                            <option value="Especially" <?php if($nowPosition == 'Especially') { ?>selected<?php } ?>>Especially</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternforDocument ?></label>
                                    <div class="col-sm-10">
                                        <select name="menu_code" id="menu_code" class="form-control select2">
                                            <?php 
                                                $menu_codex = $default['menu_code'];
                                                $i = 0;
                                                foreach($viewMenuPattern as $row) :
                                                    $menu_codeA     = $row->menu_code;
                                                    if($LangID == 'IND')
                                                        $menu_nameA = $row->menu_name_IND;
                                                    else
                                                        $menu_nameA   = $row->menu_name_ENG;
                                                    ?>
                                                        <option value="<?php echo $row->menu_code;?>" <?php if($row->menu_code == $menu_codex) { ?>selected<?php } ?>><?php echo "$menu_codeA - $menu_nameA";?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternName ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="Pattern_Name" id="Pattern_Name" class="form-control" <?php if($Pattern_NameEdited==1) { ?> disabled <?php } ?> value="<?php echo set_value('Pattern_Name', isset($default['Pattern_Name']) ? $default['Pattern_Name'] : ''); ?>" /> 
                                        <?php if($Pattern_NameEdited==1) { ?>
                                        <input type="hidden" name="Pattern_Name" id="Pattern_Name" size="30" value="<?php echo set_value('Pattern_Name', isset($default['Pattern_Name']) ? $default['Pattern_Name'] : ''); ?>" />
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternYear ?></label>
                                    <div class="col-sm-10">
                                        <?php
                                            $Pattern_YearAktive = $default['Pattern_YearAktive'];
                                            $useYear = $default['useYear'];
                                            $useMonth = $default['useMonth'];
                                            $useDate = $default['useDate'];
                                            $nowYear = date('Y');
                                            $startYear = $nowYear - 5;
                                        ?>
                                        <select name="Pattern_YearAktive" id="Pattern_YearAktive" class="form-control select2">
                                            <?php
                                                for($idx=$startYear;$idx<=$nowYear;$idx++)
                                                {
                                                ?>
                                                    <option value="<?php echo $idx; ?>" <?php if($idx == $Pattern_YearAktive) { ?>selected<?php } ?>><?php echo $idx;?></option>
                                                <?php
                                                }
                                                ?>
                                        </select>                  
                                        <input type="checkbox" value="1" name="useYear" id="useYear" onClick="showLengYear();" <?php if($useYear==1){ ?> checked <?php } ?> style="display:none" >
                                        <input type="text" name="Pattern_LengthYear" id="Pattern_LengthYear" class="form-control" style="max-width:80px; display:none" value="<?php echo set_value('Pattern_LengthYear', isset($default['Pattern_LengthYear']) ? $default['Pattern_LengthYear'] : ''); ?>" />                      </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternMonth ?></label>
                                    <div class="col-sm-10">
                                        <select name="Pattern_MonthAktive" id="Pattern_MonthAktive" class="form-control select2">
                                            <?php
                                                $nowMonth = $default['Pattern_MonthAktive'];
                                                if($nowMonth==0) $nowMonth = date('m');
                                                for($idx=1;$idx<=12;$idx++)
                                                {
                                                    if($idx == 1) $month = "January";
                                                    elseif($idx == 2) $month = "February";
                                                    elseif($idx == 3) $month = "March";
                                                    elseif($idx == 4) $month = "April";
                                                    elseif($idx == 5) $month = "May";
                                                    elseif($idx == 6) $month = "June";
                                                    elseif($idx == 7) $month = "July";
                                                    elseif($idx == 8) $month = "August";
                                                    elseif($idx == 9) $month = "September";
                                                    elseif($idx == 10) $month = "October";
                                                    elseif($idx == 11) $month = "November";
                                                    elseif($idx == 12) $month = "Desember";
                                                ?>
                                                <option value="<?php echo $idx;?>" <?php if($idx == $nowMonth) { ?>selected<?php } ?>><?php echo $month;?></option>
                                                <?php
                                                }
                                                ?>
                                        </select>                    
                                        <input type="checkbox" value="1" name="useMonth" id="useMonth" <?php if($useMonth==1){ ?> checked <?php } ?> style="display:none">
                                        <input type="text" name="Pattern_LengthMonth" id="Pattern_LengthMonth" size="4" value="<?php echo set_value('Pattern_LengthMonth', isset($default['Pattern_LengthMonth']) ? $default['Pattern_LengthMonth'] : ''); ?>" style="display:none" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternDate ?></label>
                                    <div class="col-sm-10">
                                        <select name="Pattern_DateAktive" id="Pattern_DateAktive" class="form-control select2">
                                            <?php
                                                $nowDate = $default['Pattern_DateAktive'];
                                                if($nowDate==0) $nowDate = date('d');
                                                for($idx=1;$idx<=31;$idx++)
                                                {
                                                ?>
                                                <option value="<?php echo $idx;?>" <?php if($idx==$nowDate) { ?>selected<?php } ?>><?php echo $idx;?></option>
                                                <?php
                                                }
                                                ?>
                                        </select>
                                   
                                        <input type="checkbox" value="1" name="useDate" id="useDate" <?php if($useDate==1){ ?> checked <?php } ?> style="display:none">
                                        <input type="text" name="Pattern_LengthDate" id="Pattern_LengthDate" size="4" value="<?php echo set_value('Pattern_LengthDate', isset($default['Pattern_LengthDate']) ? $default['Pattern_LengthYear'] : ''); ?>" style="display:none" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PatternDocumentLength ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="Pattern_Length" id="Pattern_Length" class="form-control" value="<?php echo set_value('Pattern_Length', isset($default['Pattern_Length']) ? $default['Pattern_Length'] : ''); ?>" onKeyPress="return isIntOnlyNew(event);" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-info alert-dismissable">
                                            <i class="fa fa-warning"></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <b><span style="font-style:italic">Notes : <br>1. If position is Normal, document pattern will use current date. You can not edit the Pattern Name.<br>2. If Pattern Position is Especially, document pattern will use current date.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                    <div class="col-sm-10">
                                        <?php
                                            if($ISCREATE == 1)
                                            {
                                                if($task=='add')
                                                {
                                                    ?>
                                                        <button class="btn btn-primary">
                                                        <i class="fa fa-save"></i></button>&nbsp;
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                        <button class="btn btn-primary" >
                                                        <i class="fa fa-save"></i></button>&nbsp;
                                                    <?php
                                                }
                                            }
                                        
                                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

<script>
    $(function () 
    {
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

        //Date picker
        $('#datepicker4').datepicker({
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
    
    function checkData(value)
    {
        var pattCode    = document.getElementById('Pattern_Code').value;
        var pattName    = document.getElementById('Pattern_Name').value;
        var pattLength  = document.getElementById('Pattern_Length').value;
        
        if(pattCode == "")
        {
            swal('<?php echo $PatternCode; ?> can not be empty',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#Pattern_Code').focus();
            });
            return false;
        }
        
        if(pattName == "")
        {
            swal('<?php echo $PatternName; ?> can not be empty',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#Pattern_Name').focus();
            });
            return false;
        }
        
        if(pattLength == "")
        {
            swal('<?php echo $PatternDocumentLength; ?> can not be empty',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#Pattern_Length').focus();
            });
            return false;
        }
    }
    
    function isIntOnlyNew(evt)
    {
        if (evt.which){ var charCode = evt.which; }
        else if(document.all && event.keyCode){ var charCode = event.keyCode; }
        else { return true; }
        return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
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