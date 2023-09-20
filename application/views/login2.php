<?php
    $this->load->library('session');
    
    $appName            = $this->session->userdata('appName');
    $appBody            = $this->session->userdata('appBody');
    $username           = $this->session->userdata('username');
    $completeName       = $this->session->userdata('completeName');
    $Emp_ID             = $this->session->userdata('Emp_ID');
    $TSDESC             = $this->session->userdata('TSDESC');
    $LangID             = $this->session->userdata('LangID');
    
    if($LangID == '')
        $LangID         = 'ENG';

    $imgemp_filename    = ''; 
    $sqlGetIMG          = "SELECT imgemp_filename FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
    $resGetIMG          = $this->db->query($sqlGetIMG)->result();
    foreach($resGetIMG as $rowGIMG) :
        $imgemp_filename    = $rowGIMG ->imgemp_filename;
    endforeach;
    
    $sqlApp         = "SELECT * FROM tappname";
    $resultaApp     = $this->db->query($sqlApp)->result();
    foreach($resultaApp as $therow) :
        $appName = $therow->app_name;       
    endforeach;
    
    $srvURL     = $_SERVER['SERVER_ADDR'];
    $srvURLHN   = gethostbyaddr($_SERVER['SERVER_ADDR']);
    //echo "&nbsp;&nbsp;Your Host : $srvURLHN<br>&nbsp;&nbsp;Your IPS : $srvURL";
    $urlLogOut      = site_url('Auth/logout');
    $urlOpenLock    = site_url('Auth/openLock/?id='.$this->url_encryption_helper->encode_url($appName));

    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    $resTransl      = $this->db->query($sqlTransl)->result();
    foreach($resTransl as $rowTransl) :
        $TranslCode = $rowTransl->MLANG_CODE;
        $LangTransl = $rowTransl->LangTransl;

        if($TranslCode == 'CompanyCode')$CompanyCode = $LangTransl;
        if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
        if($TranslCode == 'actKey')$actKey = $LangTransl;
        if($TranslCode == 'license')$license = $LangTransl;
        if($TranslCode == 'regSys')$regSys = $LangTransl;
    endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $regSys; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/secure-01.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata('vers');

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
        //$this->load->view('template/mna');
        //______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');
        $LangID     = $this->session->userdata('LangID');

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
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'CompanyCode')$CompanyCode = $LangTransl;
            if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
            if($TranslCode == 'actKey')$actKey = $LangTransl;
            if($TranslCode == 'license')$license = $LangTransl;
        endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    
    <style type="text/css">
        body {
            overflow: hidden; /* Hide scrollbars */
        }
    </style>

    <body class="hold-transition skin-blue sidebar-mini fixed" >
        <div class="login-box">
            <div class="login-box-body">
                <div class="center">
                    <div class="login-logo" style="text-align:center"><strong>
                        <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/certification_00.png'; ?>" style="max-width:100px; max-height:120px" ></strong>
                    </div>
                    <p class="login-box-msg" style="display:none">&nbsp;</p>
                    <p class="login-box-msg" style="text-align:center">Please verify your software activation code</p>
                    <form action="<?php echo site_url('__l1y') ?>" method="post">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Your IP" name="ipadd" id="ipadd" value="<?php echo $srvURL; ?>" disabled />
                            <span class="glyphicon glyphicon-map-marker form-control-feedback"> </span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Private Key" name="tspk" id="tspk" value="<?php echo $TSDESC; ?>" disabled/>
                            <span class="glyphicon glyphicon-pencil form-control-feedback"> </span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="<?php echo $CompanyName; ?>" name="appNm" id="appNm" value="<?php //echo $appName; ?>"/>
                            <span class="glyphicon glyphicon-home form-control-feedback"> </span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="email" class="form-control" name="crkMail" id="crkMail" placeholder="Email" value="">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"> </span>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <button type="button" class="btn btn-primary btn-block btn-flat" onClick="checkValue();" id="btnReg">Verify</button>
                                <button type="submit" class="btn btn-success btn-block btn-flat" id="btnLogin" style="display: none;">Log In</button>
                            </div>
                        </div>
                    </form>
                    <div class="social-auth-links text-center">
                        <?php /*?><p>- OR -</p>
                        <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
                        <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a><?php */?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
    $imgLoc     = base_url('assets/AdminLTE-2.0.5/dist/img/icon/certification_01.png');
?>
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

    function checkValue()
    {
        crkMail     = document.getElementById('crkMail').value;
        ips         = document.getElementById('ipadd').value;
        appNm       = document.getElementById('appNm').value;
        tspk        = document.getElementById('tspk').value;

        if(appNm == '')
        {
            swal('Please input your Company Name',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#appNm').focus();
            });
            return false;           
        }
        else if(crkMail == '')
        {
            swal('Please input your email address',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#crkMail').focus();
            });
            return false;           
        }
        else
        {
            var imgLoc  = "<?php echo $imgLoc; ?>";
            swal(
            {
                icon: imgLoc,
                text: 'Please input your Software Activation Code ... !',
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Software Activation Code",
                        type: "text",
                    },
                },
                button: 
                {
                    text: "Sign In",
                    closeModal: false,
                }
            })
            .then(actKey => {
                var urlPass = "<?php echo site_url('__l1y/chkAct') ?>";
                collData    = ips+'~'+appNm+'~'+actKey+'~'+tspk;
                $.ajax({
                    type: 'POST',
                    url: urlPass,
                    data: "collData="+collData,
                    success: function(isRespon)
                    {
                        var myarr       = isRespon.split("~");
                        var actStat     = myarr[0];
                        var actAlert    = myarr[1];
                        if(actStat == 0)
                        {
                            swal(actAlert, 
                            {
                                icon: "warning",
                            });
                        }
                        else
                        {
                            document.getElementById('appNm').disabled           = true;
                            document.getElementById('crkMail').disabled         = true;
                            document.getElementById('btnReg').style.display     = 'none';
                            document.getElementById('btnLogin').style.display   = '';
                            swal(actAlert, 
                            {
                                icon: "success",
                            });
                        }
                    }
                });
            });
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