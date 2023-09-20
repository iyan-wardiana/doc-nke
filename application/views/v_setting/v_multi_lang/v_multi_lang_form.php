<?php
	/* 
		* Author		= Dian Hermanto
		* Create Date	= 7 Februari 2017
		* File Name		= docpattern_form.php
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

	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
	if($task == 'add')
	{
		$MLANG_ID		= "";
		$MLANG_CODE		= "";
		$MLANG_IND		= "";
		$MLANG_ENG		= "";
	}
	else
	{
		$MLANG_ID 		= $default['MLANG_ID'];
		$MLANG_CODE 	= $default['MLANG_CODE'];
		$MLANG_IND 		= $default['MLANG_IND'];
		$MLANG_ENG 		= $default['MLANG_ENG'];
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
      		if($TranslCode == 'Save')$Save = $LangTransl;
      		if($TranslCode == 'Update')$Update = $LangTransl;
      		if($TranslCode == 'Back')$Back = $LangTransl;
      		if($TranslCode == 'Translate')$Translate = $LangTransl;
      		if($TranslCode == 'Setting')$Setting = $LangTransl;
      	endforeach;
    ?>  

    <script>
		function chekData()
		{
			MLANG_CODE  = document.getElementById('MLANG_CODE').value;
			MLANG_IND = document.getElementById('MLANG_IND').value;
			MLANG_ENG = document.getElementById('MLANG_ENG').value;

			if(MLANG_CODE == "")
			{
			 	alert('Translate Code can not be empty.');
			  	document.getElementById('MLANG_CODE').focus();
			  	return false;
			}
			if(MLANG_IND == "")
			{
			  	alert('IND language can not be empty.');
			  	document.getElementById('MLANG_IND').focus();
			  	return false;
			}
			if(MLANG_ENG == "")
			{
			  	alert('ENG language can not be empty.');
			  	document.getElementById('MLANG_ENG').focus();
			  	return false;
			}
		}
    </script>
    <?php
        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
        <h1>
            <?php echo "$Translate"; ?>
            <small><?php echo "$Setting"; ?></small>
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
                        	<form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return chekData()">
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label">Doc. Code</label>
                                  	<div class="col-sm-10">
                                        <label>
                                        	<?php
        										if($task == "add")
        										{
        											?>
	                                            		<input type="text" class="form-control" name="MLANG_CODE" id="MLANG_CODE" value="<?php echo $MLANG_CODE; ?>" onChange="functioncheck(this.value)">
		                                                </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>
		                                                <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
		                                        	<?php
	        										}
	        										else
	        										{
	        										?>
	                                            		<input type="text" class="form-control" name="MLANG_CODE1" id="MLANG_CODE1" value="<?php echo $MLANG_CODE; ?>" disabled>
	                                            		<input type="hidden" class="form-control" name="MLANG_CODE" id="MLANG_CODE" value="<?php echo $MLANG_CODE; ?>" onChange="functioncheck(this.value)">
	                                        		<?php
        										}
        									?>
                                  	</div>
                                </div>
                                <script>
        							function functioncheck(myValue)
        							{
        								var ajaxRequest;
        								try
        								{
        									ajaxRequest = new XMLHttpRequest();
        								}
        								catch (e)
        								{
        									alert("Something is wrong");
        									return false;
        								}
        								ajaxRequest.onreadystatechange = function()
        								{
        									if(ajaxRequest.readyState == 4)
        									{
        										recordcount = ajaxRequest.responseText;
        										if(recordcount > 0)
        										{
        											document.getElementById('CheckThe_Code').value= recordcount;
        											document.getElementById("isHidden").innerHTML = ' Translate code already exist ... !';
        											document.getElementById("isHidden").style.color = "#ff0000";
        										}
        										else
        										{
        											document.getElementById('CheckThe_Code').value= recordcount;
        											document.getElementById("isHidden").innerHTML = ' Translate Code : OK .. !';
        											document.getElementById("isHidden").style.color = "green";
        										}
        									}
        								}
        								var MLANG_CODE = document.getElementById('MLANG_CODE').value;
        								
        								ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_setting/c_multi_lang/getTheCode/';?>" + MLANG_CODE, true);
        								ajaxRequest.send(null);
        							}
        						</script>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Indonesia</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="MLANG_IND" id="MLANG_IND" value="<?php echo $MLANG_IND; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">English</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="MLANG_ENG" id="MLANG_ENG" value="<?php echo $MLANG_ENG; ?>" />
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
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
	var decFormat		= 2;
	
	function doDecimalFormat(angka) 
	{
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
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