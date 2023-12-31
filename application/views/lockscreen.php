<?php
	$data				= explode("~", $collData);
	$empID				= $data[0];
	$username			= $data[1];
	$completeName		= $data[2];
	$appName			= $data[3];
	
	$this->session->sess_destroy();	
	
	$updLog	= "UPDATE tbl_employee SET OLStat = 0 WHERE Emp_ID = '$empID'";
	$this->db->query($updLog);
	
	$imgemp_filename 	= '';
	$sqlGetIMG			= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$empID'";
	$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
	$imgemp_filenameX	= "username.jpg";
	foreach($resGetIMG as $rowGIMG) :
		$imgemp_filename 	= $rowGIMG ->imgemp_filename;
		$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
	endforeach;
	$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$empID.'/'.$imgemp_filenameX);
	if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$empID))
	{
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
	}
	
	$urlLogOut		= site_url('__l1y/logout');
	$urlOpenLock	= site_url('__l1y/openLock/?id='.$this->url_encryption_helper->encode_url($appName));
	
	function cut_text($var, $len = 200, $txt_titik = "...") 
	{
		$var1	= explode("</p>",$var);
		$var	= $var1[0];
		if (strlen ($var) < $len) 
		{ 
			return $var; 
		}
		if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
		{
			return $match [1] . $txt_titik;
		}
		else
		{
			return substr ($var, 0, $len) . $txt_titik;
		}
	}
?>
<!DOCTYPE html>
<html class="lockscreen">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $appName; ?> | Lockscreen</title>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-screen-02.png'; ?>" sizes="32x32">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <script>
		function validateInData()
		{
			mySessionEmp_ID = document.getElementById('mySessionEmp_ID').value;
			if(mySessionEmp_ID == "")
			{
				alert('You are soo long time left the system. System will be log out');
				var url = "<?php echo $urlLogOut; ?>";
				window.location = url;
				return false;
			}
		}
	</script>
    <body>
        <!-- Automatic element centering using js -->
        <form name="frm" method="post" action="<?php echo $urlOpenLock; ?>" onSubmit="return validateInData();">
        	<input type="hidden" name="mySessionEmp_ID" id="mySessionEmp_ID" value="<?php echo $empID; ?>">
            <div class="center">            
                <div class="headline text-center" id="time">
                    <!-- Time auto generated by js -->
                </div><!-- /.headline -->
                <?php
                    $coutnName	= strlen($completeName);
                    if($coutnName > 15)
                    {
                        $completeName	= cut_text ("$completeName", 15);
                    }
                ?>
                <!-- User name -->
                <div class="lockscreen-name" style="text-align:center"><?php echo "$completeName"; ?></div>
                
                <!-- START LOCK SCREEN ITEM -->
                <div class="lockscreen-item">
                    <!-- lockscreen image -->
                    <div class="lockscreen-image">
                        <img src="<?php echo $imgLoc; ?>" class="img-circle" alt="User Image" />
                    </div>
                    <!-- /.lockscreen-image -->
                    
                    <!-- lockscreen credentials (contains the form) -->
                    <div class="lockscreen-credentials">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="password" name="log_password" />
                            <div class="input-group-btn">
                                <button class="btn btn-flat"><i class="fa fa-arrow-right text-muted"></i></button>
                            </div>
                        </div>
                    </div><!-- /.lockscreen credentials -->
                </div>
    
                <div class="lockscreen-link">
                    <i class="fa fa-lock"></i>&nbsp;&nbsp;<a href="<?php echo $urlLogOut; ?>">Or sign in as a different user<br><?php echo $appName; ?></a>
                </div>
            </div><!-- /.center -->
		</form>
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