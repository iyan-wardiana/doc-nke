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
	
	$urlLogOut		= site_url('__l1y/logout');
	$urlOpenLock	= site_url('__l1y/openLock/?id='.$this->url_encryption_helper->encode_url($appName));
?>
<!DOCTYPE html>
<html class="lockscreen">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $appName; ?> | Log In</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url() . 'assets/AdminLTE/css/bootstrap.min.css'; ?>" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url() . 'assets/AdminLTE/css/font-awesome.min.css'; ?>" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url() . 'assets/AdminLTE/css/AdminLTE.css'; ?>" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo base_url() . 'assets/ionicons-2.0.1/css/ionicons.min.css'; ?>" rel="stylesheet" type="text/css" />
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url() . 'assets/font-awesome-4.3.0/css/font-awesome.min.css'; ?>" rel="stylesheet" type="text/css" />      
    </head>
    <body>
        <div class="login-box">
            <div class="login-box-body">
                <div class="center">
                    <div class="login-logo"><strong>
                        <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:250px; max-height:250px" ></strong>
                    </div>
                    <p class="login-box-msg" style="display:none">Sign in to start NKE Smart System</p><br>
                    <form action="<?php echo site_url('__l1y/reset_process') ?>" method="post" onSubmit="return checkValue();">
                        <div class="form-group has-feedback">
                        	<input type="text" class="form-control" placeholder="Employee ID" name="EMP_ID" id="EMP_ID"/>
                            <span class="glyphicon glyphicon-user form-control-feedback"> </span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="email" class="form-control" placeholder="Email" name="EMAIL" id="EMAIL">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"> </span>
                        </div>
                        <div class="row">
                            <div class="col-xs-8">    
                                <div class="checkbox icheck">
                                        <a href="<?php echo site_url('__l1y/') ?>">Back to Log In</a>
                                </div>                        
                            </div>
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Reset</button>
                            </div>
                        </div>
                    </form>
                    <script>
						function checkValue()
						{
							EMP_ID	= document.getElementById('EMP_ID').value;
							EMAIL	= document.getElementById('EMAIL').value;
							if(EMP_ID == '')
							{
								alert('Please input Employee ID');
								document.getElementById('EMP_ID').focus();
								return false;
							}
							if(EMAIL == '')
							{
								alert('Please input email');
								document.getElementById('EMAIL').focus();
								return false;
							}
						}
					</script>
                </div>
            </div>
        </div>
    </body>
    <!-- jQuery 2.0.2 -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url() . 'assets/AdminLTE/js/bootstrap.min.js'; ?>" type="text/javascript"></script>

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