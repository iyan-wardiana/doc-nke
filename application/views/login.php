<?php
    $PRJSCAT    = "";
    $sqlprj     = "SELECT PRJSCATEG FROM tbl_project WHERE isHo = 1";
    $resprj     = $this->db->query($sqlprj)->result();
    foreach($resprj as $rwprj) :
        $PRJSCAT= $rwprj->PRJSCATEG;       
    endforeach;
    
    $sqlApp         = "SELECT * FROM tappname";
    $resultaApp     = $this->db->query($sqlApp)->result();
    foreach($resultaApp as $therow) :
        $appName    = $therow->app_name;
        $comp_init  = $therow->comp_init;
        $comp_name  = $therow->comp_name;
        $top_name   = $therow->top_name;
    endforeach;

    $appName            = $this->session->userdata('appName');
    $username           = $this->session->userdata('username');
    $completeName       = $this->session->userdata('completeName');
    $Emp_ID             = $this->session->userdata('Emp_ID');
    $vers               = $this->session->userdata('vers');
    if($vers == '')
        $vers           = "2.0.5";

    if($PRJSCAT == 1)
        $css_login      = base_url() . 'assets/css/login/login_konst.css';
    else
        $css_login      = base_url() . 'assets/css/login/login_man.css';

    $imgemp_filename    = '';
    $sqlGetIMG          = "SELECT imgemp_filename FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
    $resGetIMG          = $this->db->query($sqlGetIMG)->result();
    foreach($resGetIMG as $rowGIMG) :
        $imgemp_filename    = $rowGIMG ->imgemp_filename;
    endforeach;
    
    $urlLogOut      = site_url('__l1y/logout');
    $urlOpenLock    = site_url('__l1y/openLock/?id='.$this->url_encryption_helper->encode_url($appName));
    $srvURL         = $_SERVER['SERVER_ADDR'];

    $LangID         = "IND";

    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    $resTransl      = $this->db->query($sqlTransl)->result();
    foreach($resTransl as $rowTransl) :
        $TranslCode = $rowTransl->MLANG_CODE;
        $LangTransl = $rowTransl->LangTransl;
        
        if($TranslCode == 'usernEmpty')$usernEmpty = $LangTransl;
        if($TranslCode == 'userpEmpty')$userpEmpty = $LangTransl;
        if($TranslCode == 'inpEmpty')$inpEmpty = $LangTransl;
        if($TranslCode == 'nikNotReg')$nikNotReg = $LangTransl;
        if($TranslCode == 'emailNotReg')$emailNotReg = $LangTransl;
        if($TranslCode == 'resetSuccess')$resetSuccess = $LangTransl;
        if($TranslCode == 'urNIK')$urNIK = $LangTransl;
    endforeach;
?>
<!DOCTYPE html>
<html class="lockscreen">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $top_name; ?> | Log In</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 99 AND cssjs_vers IN ('$vers', 'All')";
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
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   
        <!--Bootsrap 4 CDN-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        
        <!--Fontawesome CDN-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

        <!--Custom styles-->
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="stylesheet" href="<?php echo $css_login; ?>">
    </head>
    <style type="text/css">
        .swal-icon img{
            width: 120px;
            height: 120px;
            border: 4px solid gray;
            -webkit-border-radius: 40px;
            border-radius: 40px;
            border-radius: 50%;
            margin: 20px auto;
            padding: 0;
            position: relative;
            box-sizing: content-box;
        }
    </style>
    <script type="text/javascript">
        window.onload = maxWindow;

        function maxWindow()
        {
            window.moveTo(0, 0);

            if (document.all) {
                top.window.resizeTo(screen.availWidth, screen.availHeight);
            }

            else if (document.layers || document.getElementById) 
            {
                if (top.window.outerHeight < screen.availHeight || top.window.outerWidth < screen.availWidth)
                {
                    top.window.outerHeight = screen.availHeight;
                    top.window.outerWidth = screen.availWidth;
                }
            }
        }
    </script>
    <!-- <body class="hold-transition skin-blue sidebar-mini fixed" onLoad="App.handleFullScreen()"> -->
    <body class="hold-transition skin-blue sidebar-mini fixed" >
        <div class="container">
            <div class="d-flex justify-content-center h-100">
                <div class="card">
                    <div style="background-image: url(<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/'. strtolower($comp_init) . '/compLog_Login.png'; ?>);">
                        <h3 style="text-align: center;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/'. strtolower($comp_init) . '/compLog_Login.png'; ?>" style="max-width:100%; max-height:100%" ></h3>
                            <!-- <div class="d-flex justify-content-end social_icon">
                            <span><i class="fab fa-facebook-square"></i></span>
                            <span><i class="fab fa-google-plus-square"></i></span>
                            <span><i class="fab fa-twitter-square"></i></span>
                        </div> -->
                        <?php //echo $comp_name; ?>
                    </div>
                    <div class="card-body">
                        <form name="frmlogin" id="frmlogin" method="post" action="<?php echo site_url('__l1y/login_process') ?>">
                            <input type="hidden" name="urlLIN" id="urlLIN" value="<?php echo site_url('__l1y/loginProc') ?>">
                            <input type="hidden" name="urlNIK" id="urlNIK" value="<?php echo site_url('__l1y/chkNIK') ?>">
                            <input type="hidden" name="urlHint" id="urlHint" value="<?php echo site_url('__l1y/chkHint') ?>">
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="username" name="username" id="username"/>
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" class="form-control" placeholder="password" name="password" id="password"/>
                            </div>
                            <div class="row align-items-center remember" style="display: none;">
                                <input type="checkbox">Remember Me
                            </div>
                            <div class="form-group">
                                <input type="button" value="Login" class="btn float-right login_btn" onclick="checkLogin()">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <!-- <div class="d-flex justify-content-center links">
                            Don't have an account?<a href="#">Sign Up</a>
                        </div> -->
                        <div class="d-flex justify-content-center">
                            <a href="#" onclick="forgpassw()">I Forgot My Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/js/app_iframe.js'; ?>"></script>

<script type="text/javascript">
    function checkLogin()
    {
        var usern   = $('#username').val();
        var userp   = $('#password').val();
        var collUP  = usern+'~'+userp;

        if(usern == '')
        {
            swal('<?php echo $usernEmpty; ?>',
            {
                icon: "warning",
            });
            return false;
        }

        if(userp == '')
        {
            swal('<?php echo $userpEmpty; ?>',
            {
                icon: "warning",
            });
            return false;
        }

        var succLog = 0;
        var urlLIN  = document.getElementById('urlLIN').value;
        $.ajax({
            type: 'POST',
            url: urlLIN,
            data: "collUP="+collUP,
            success: function(response)
            {
                var myarr   = response.split("~");
                var succLog = myarr[0];
                var succInf = myarr[1];

                if(succLog == 1)
                {
                    document.getElementById("frmlogin").submit();
                }
                else
                {
                    swal(succInf, {icon: "error"})
                    .then((value) => {
                        return false;
                    });
                    return false;
                }
            }
        });
    }

    function forgpassw()
    {
        swal(
        {
            icon: "warning",
            text: 'Masukan NIK Anda:',
            content: "input",
            button: 
            {
                text: "OK",
                closeModal: false,
            },
        })
        .then(nikemp => {
            if (!nikemp)
            {
                swal('<?php echo $inpEmpty; ?>',
                {
                    icon: "error",
                });
                return false;
            }
            else
            {
                //return fetch(`https://itunes.apple.com/search?term=${hintemp}&entity=movie`);

                var urlNIK  = document.getElementById('urlNIK').value;
                $.ajax({
                    type: 'POST',
                    url: urlNIK,
                    data: "nikemp="+nikemp,
                    success: function(isResNIK)
                    {
                        var myarr       = isResNIK.split("~");
                        var isRegNIK    = myarr[0];
                        var imgLoc      = myarr[1];
                        if(isRegNIK == 1)
                        {
                            swal(
                            {
                                icon: imgLoc,
                                text: 'Masukan petunjuk / hint pengguna Anda:',
                                content: "input",
                                button: 
                                {
                                    text: "Reset",
                                    closeModal: false,
                                },
                            })
                            .then(hintemp => {
                                if (!hintemp)
                                {
                                    swal('<?php echo $inpEmpty; ?>',
                                    {
                                        icon: "error",
                                    });
                                    return false;
                                }
                                else
                                {
                                    var urlHint = document.getElementById('urlHint').value;
                                    collData    = nikemp+'~'+hintemp;
                                    $.ajax({
                                        type: 'POST',
                                        url: urlHint,
                                        data: "collData="+collData,
                                        success: function(isRespon)
                                        {
                                            var myarr   = isRespon.split("~");
                                            var resStat = myarr[0];
                                            var resInfo = myarr[1];
                                            if(resStat == 0)
                                            {
                                                swal(resInfo,
                                                {
                                                    icon: "error",
                                                });
                                                return false;
                                            }
                                            else
                                            {
                                                swal('<?php echo $resetSuccess; ?> !', 'Username : <?php echo $urNIK; ?>, Password : '+resInfo, 'success');
                                            }
                                        }
                                    });
                                }
                            });
                        }
                        else
                        {
                            swal('<?php echo $nikNotReg; ?>',
                            {
                                icon: "error",
                            });
                            return false;
                        }
                    }
                });
            }
        });
    }
</script>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 99 AND cssjs_lnk LIKE '%sweetalert%'";
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