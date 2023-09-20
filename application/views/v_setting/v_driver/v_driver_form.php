<?php
/*  
	* Author		= Hendar Permana 
	* Create Date	= 9 Mei 2017
	* File Name	= v_office_inventory_form.php
	* Location		= -
*/

/*  
	* Author		= Hendar Permana 
	* Create Date	= 02 Maret 2018
	* File Name	= v_driver_form.php
	* Location		= -
*/

?>
<?php
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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

if($task == 'add')
{
	if($countDocPattern > 0)
	{
		foreach($viewDocPattern as $row) :
			$Pattern_Code = $row->Pattern_Code;
			$Pattern_Position = $row->Pattern_Position;
			$Pattern_YearAktive = $row->Pattern_YearAktive;
			$Pattern_MonthAktive = $row->Pattern_MonthAktive;
			$Pattern_DateAktive = $row->Pattern_DateAktive;
			//$Pattern_Length = $row->Pattern_Length;
			$Pattern_Length = 4;
			$useYear = $row->useYear;
			$useMonth = $row->useMonth;
			$useDate = $row->useDate;
		endforeach;
	}
	else
	{
		$Pattern_Code 			= "XXX";
		$Pattern_Position 		= "Especially";
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		//$Pattern_Length = $row->Pattern_Length;
		$Pattern_Length 		= 4;
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
	}
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$year = (int)$Pattern_YearAktive;
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;
	
	
	$sql = "tbl_driver";
	$result = $this->db->count_all($sql);
	
	$myMax = $result;
	$myMax = $myMax+1;			
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	$lastPatternNumb = $nol.$lastPatternNumb;	
	
	$Y_HEADER		= substr(date('Y'), 2,4);
	
	$DocNumber 		= "DR$lastPatternNumb";

	$DRIV_CODE 		= $DocNumber;
	$DRIV_NAME 		= '';
	$DRIV_CONTACT 	= '';
	$DRIV_STAT		= '';
}
else
{
	$DRIV_CODE 		= $default['DRIV_CODE'];
	$DRIV_NAME 		= $default['DRIV_NAME'];
	$DRIV_CONTACT	= $default['DRIV_CONTACT'];
	$DRIV_STAT		= $default['DRIV_STAT'];
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
            $vers 	= $this->session->userdata['vers'];

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
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Contact')$Contact = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;

		endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
	            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnTitl; ?>
	            <small><?php echo $mnName; ?></small>
            </h1>
		</section>

		<section class="content">
		    <div class="row">
		        <div class="col-md-12">
		            <div class="box box-primary">
		                <div class="box-body chart-responsive">
		                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		           				<input type="Hidden" name="rowCount" id="rowCount" value="0">                        

		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?></label>
		                          	<div class="col-sm-10">
		                            	<input type="text" class="form-control" name="DRIV_CODE" id="DRIV_CODE" value="<?php echo $DRIV_CODE; ?>" readonly >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Name ?></label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="DRIV_NAME" id="DRIV_NAME"value="<?php echo $DRIV_NAME; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Contact ?></label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="DRIV_CONTACT" id="DRIV_CONTACT"value="<?php echo $DRIV_CONTACT; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?></label>
		                          	<div class="col-sm-10">
		                                <select name="DRIV_STAT" id="DRIV_STAT" class="form-control" >
		                                    <option value="0" <?php if($DRIV_STAT == "0") { ?> selected <?php } ?>>Ready</option>
		                                    <option value="1" <?php if($DRIV_STAT == "1") { ?> selected <?php } ?>>Used</option>
		                                </select>
		                            </div>
		                        </div>

		                        <br>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
		                            	<?php
		                                if($ISCREATE == 1 )
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
	function validateInData()
	{
		nextornot = document.getElementById('CheckThe_Code').value;
		if(nextornot > 0)
		{
			alert('Project Code Already Exist. Please Change.');
			document.getElementById('PRJCODE').value = '';
			document.getElementById('PRJCODE').focus();
			return false;
		}
		
		PRJNAME = document.getElementById('PRJNAME').value;
		if(PRJNAME == '')
		{
			alert('Project Name can not be empty');
			document.getElementById('PRJNAME').focus();
			return false;
		}
		
		PRJOWN = document.getElementById('PRJOWN').value;
		if(PRJOWN == 'none')
		{
			//alert('Please chose one of Owner Project.');
			//document.getElementById('PRJOWN').focus();
			//return false;
		}
		
		var PRJDATE = new Date(document.frm.PRJDATE.value);
		
		var PRJEDAT = new Date(document.frm.PRJEDAT.value);
		
		if(PRJEDAT < PRJDATE)
		{
			alert('End Date Project must be Greater than Start Date Project.');
			return false;
		}
		
		var ISCHANGE	= document.getElementById('ISCHANGEX').value;
		if(ISCHANGE == 1)
		{
			var REFCHGNO	= document.getElementById('REFCHGNO').value;
			if(REFCHGNO == '')
			{
				alert('Please input reference number.');
				document.getElementById('REFCHGNO').focus();
				return false;
			}
			
			var PRJCOST2 = eval(document.getElementById('PRJCOST22a')).value.split(",").join("");
			if(PRJCOST2 == 0)
			{
				alert('Please input new of Contract Value.');
				document.getElementById('PRJCOST22a').focus();
				return false;
			}
		}
		else
		{
			document.getElementById('REFCHGNO').value 	= '';
			document.getElementById('PRJCOST22a').value = '0.00';
			document.getElementById('PRJCOST22').value 	= '0';
		}
	}
	
	var decFormat		= 2;
	
	function doDecimalFormat(angka) {
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

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
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