<?php
	$appName 			= $this->session->userdata('appName');
	$username 			= $this->session->userdata('username');
	$completeName		= $this->session->userdata('completeName');
	$Emp_ID 			= $this->session->userdata('Emp_ID');
	$imgemp_filename 	= '';
	$sqlGetIMG			= "SELECT imgemp_filename FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
	$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
	foreach($resGetIMG as $rowGIMG) :
		$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	endforeach;
	
	$urlLogIn		= site_url('Auth/index1/?id='.$this->url_encryption_helper->encode_url($appName));
	$urlLogCA		= site_url('Auth/contact_adm');
	$urlLogOut		= site_url('Auth/logout');
	$urlOpenLock	= site_url('Auth/openLock/?id='.$this->url_encryption_helper->encode_url($appName));
?>
<!DOCTYPE html>
<html class="lockscreen">
    <head>
        <meta charset="UTF-8">
        <title>NSS | Reset Failed</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url('assets/AdminLTE/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url('assets/AdminLTE/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url('assets/AdminLTE/css/AdminLTE.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo base_url('assets/ionicons-2.0.1/css/ionicons.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo base_url('assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url('assets/font-awesome-4.3.0/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
    </head>
    <style type="text/css">
		html, body {
			width: 100%;
			height: 100%;
		}
		
		body{
			margin: 0;
			display: table
		}
		
		body>div {
			display: table-cell; 
			vertical-align: middle; /* vertical */
		}
	</style>
    <body>
    <div class="wrapper">
        <div id="login-overlay" class="modal-dialog">
            <div class="modal-content" style="vertical-align:middle">
                  <div class="modal-header" style="text-align:center">
                      <button type="button" class="close" data-dismiss="modal" style="display:none">
                      	<span aria-hidden="true">×</span><span class="sr-only">Close</span>
                      </button>
                      <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/nkesmartimg/NKELogoTransp.png'; ?>" style="max-width:260px" >
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-xs-12">
                              <p class="lead">Sorry, <span class="text-danger">Reset Failed ... !!!</span></p>
                              The system could not reset your password. We did not find your data in our system.<br>
                              Please, contact an Administrator System.<br><br>Regards,<br><strong>NKE Smart Dev. Team.</strong>
                              <br><br>
                              <ul class="list-unstyled" style="line-height: 2">
                                  <li><span class="fa fa-check text-success"></span>
                                  	<a href="<?php echo $urlLogIn; ?>" style="color:#000">Back to Log In</a>
                                  </li>
                                  <li><span class="fa fa-check text-success"></span>
                                  	<a href="<?php echo $urlLogCA; ?>" style="color:#000">Contact Administrator</a>
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
    </div>
    </body>
    <!-- jQuery 2.0.2 -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url('assets/AdminLTE/js/bootstrap.min.js') ?>" type="text/javascript"></script>

    <!-- page script -->
    <script type="text/javascript">
        $(function() {
            startTime();
            $(".center").center();
            $(window).resize(function() {
                $(".center").center();
            });
        });

        /*  */
        function startTime()
        {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();

            // add a zero in front of numbers<10
            m = checkTime(m);
            s = checkTime(s);

            //Check for PM and AM
            var day_or_night = (h > 11) ? "PM" : "AM";

            //Convert to 12 hours system
            if (h > 12)
                h -= 12;

            //Add time to the headline and update every 500 milliseconds
            $('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
            setTimeout(function() {
                startTime()
            }, 500);
        }

        function checkTime(i)
        {
            if (i < 10)
            {
                i = "0" + i;
            }
            return i;
        }

        /* CENTER ELEMENTS IN THE SCREEN */
        jQuery.fn.center = function() {
            this.css("position", "absolute");
            this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                    $(window).scrollTop()) - 30 + "px");
            this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                    $(window).scrollLeft()) + "px");
            return this;
        }
    </script>
</html>