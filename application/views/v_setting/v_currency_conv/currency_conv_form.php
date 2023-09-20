<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= currency_conv_sd_form.php
 * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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
	$CC_CODE		= "";
	$CURR_ID		= "";
	$CURR_ID1		= "IDR";
	$CURR_ID2		= "IDR";
	$CC_DURATION	= 0;
	$CC_VALUE		= 1;
	
	$Yeras 			= date('Y');
	$Month 			= date('m');
	$Days			= date('d');
	
	$CC_STARTD		= "$Month/$Days/$Yeras";
	$CC_ENDD		= "$Month/$Days/$Yeras";
}
else
{
	$CC_CODE 		= $default['CC_CODE'];
	$CURR_ID1 		= $default['CURR_ID1'];
	$CURR_ID2		= $default['CURR_ID2'];
	$CC_STARTD 		= $default['CC_STARTD'];
	$CC_DURATION 	= $default['CC_DURATION'];
	$CC_ENDD		= $default['CC_ENDD'];
	$CC_VALUE 		= $default['CC_VALUE'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $appName; ?> | Data Tables</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
		
		endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small>setting</small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->

<script>
	function validateInCurr()
	{
		CURR_ID1	= document.getElementById('CURR_ID1').value;
		CURR_ID2	= document.getElementById('CURR_ID2').value;
		CC_VALUE	= parseFloat(document.getElementById('CC_VALUE').value);
		if(CURR_ID1 != CURR_ID2)
		{
			if(CC_VALUE == 1)
			{
				alert('Please check Value Convertion. Are you sure the value is 1 ... ?');
				document.getElementById('CC_VALUEX').focus();
				return false;
			}
		}
	}
	
	function changeFDate()
	{
		thisVal				= document.getElementById('datepicker').value;
		var date 			= new Date(thisVal);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
		var theM			= datey.getMonth();
		var dateDesc		= datey.getFullYear()+ "-" + theM + "-" + datey.getDate();
		var FDate			= document.getElementById('datepicker').value;
		changeDueDate(FDate)
	}
	
	function changeFDate1()
	{
		thisVal				= document.getElementById('datepicker').value;
		var date 			= new Date(thisVal);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
		var theM			= datey.getMonth();
		var dateDesc		= datey.getFullYear()+ "-" + theM + "-" + datey.getDate();
		var FDate			= document.getElementById('datepicker').value;
		changeDueDate(FDate)
	}
	
	function changeFDate2()
	{
		thisVal				= document.getElementById('datepicker').value;
		var date 			= new Date(thisVal);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
		var theM			= datey.getMonth();
		var dateDesc		= datey.getFullYear()+ "-" + theM + "-" + datey.getDate();
		var FDate			= document.getElementById('datepicker').value;
		changeDueDate(FDate)
	}
	
	function changeDueDate(thisVal)
	{
		var FDate			= document.getElementById('datepicker').value;
		var date 			= new Date(FDate);
		//alert(date)
		CC_TTODays			= parseFloat(document.getElementById('CC_DURATION').value);
		
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate() + CC_TTODays, 0, 0, 0);
		var theM			= datey.getMonth();
		if(theM == 0)
		{
			theMD	= 'January';
		}
		else if(theM == 1)
		{
			theMD	= 'February';
		}
		else if(theM == 2)
		{
			theMD	= 'March';
		}
		else if(theM == 3)
		{
			theMD	= 'April';
		}
		else if(theM == 4)
		{
			theMD	= 'May';
		}
		else if(theM == 5)
		{
			theMD	= 'June';
		}
		else if(theM == 6)
		{
			theMD	= 'July';
		}
		else if(theM == 7)
		{
			theMD	= 'August';
		}
		else if(theM == 8)
		{
			theMD	= 'September';
		}
		else if(theM == 9)
		{
			theMD	= 'October';
		}
		else if(theM == 10)
		{
			theMD	= 'November';
		}
		else if(theM == 11)
		{
			theMD	= 'December';
		}
		var dateDesc	=  datey.getDate()+ " " + theMD + " " + datey.getFullYear();
		
		document.getElementById('CC_ENDD').value 	= formatDate(datey);
		document.getElementById('CC_ENDDX').value	= dateDesc;
	}
	
	function formatDate(d) 
	{
		var dd = d.getDate()
		if ( dd < 10 ) dd = '0' + dd
		
		var mm = d.getMonth()+1
		if ( mm < 10 ) mm = '0' + mm
		
		var yy = d.getFullYear()
		
		return yy+'-'+mm+'-'+dd
	}
</script>
<section class="content">
    <div class="box box-primary"><br>
      <form name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInCurr();">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                <tr>
                    <td width="15%" align="left" class="style1">Currency ID 1</td>
                    <td width="1%" align="left" class="style1">:</td>
                    <td width="84%" align="left" class="style1" style="font-style:italic">
                        <input type="hidden" name="CC_CODE" id="CC_CODE" value="<?php echo $CC_CODE; ?>" >
                        <select name="CURR_ID1" id="CURR_ID1" onChange="checkCURID1(this.value)" class="form-control" style="max-width:75px">
                            <?php
                                $sqlGetCURRID		= "SELECT CURR_ID, CURR_CODE FROM tbl_currency WHERE CURR_STAT = 1
                                                        ORDER BY CURR_CODE ASC";
                                $resGetCURRID		= $this->db->query($sqlGetCURRID)->result();
            
                                foreach($resGetCURRID as $rowCURRID) :
                                    $CURR_ID1A		= $rowCURRID->CURR_ID;			
                                    $CURR_CODE1A	= $rowCURRID->CURR_CODE;
                                    ?>
                                        <option value="<?php echo $CURR_ID1A;?>" <?php if($CURR_ID1A == $CURR_ID1) { ?>selected<?php } ?>><?php echo "$CURR_ID1A";?></option>
                                    <?php
                                endforeach;
                            ?>
                        </select>
					</td>
                </tr>
                <tr>
                    <td width="15%" align="left" class="style1">Currency ID 2</td>
                    <td width="1%" align="left" class="style1">:</td>
                    <td width="84%" align="left" class="style1" style="font-style:italic">
                        <select name="CURR_ID2" id="CURR_ID2" onChange="checkCURID2(this.value)" class="form-control" style="max-width:75px">
                            <?php
                                $sqlGetCURRID		= "SELECT CURR_ID, CURR_CODE FROM tbl_currency WHERE CURR_STAT = 1
                                                        ORDER BY CURR_CODE ASC";
                                $resGetCURRID		= $this->db->query($sqlGetCURRID)->result();
            
                                foreach($resGetCURRID as $rowCURRID) :
                                    $CURR_ID1B		= $rowCURRID->CURR_ID;			
                                    $CURR_CODE1B	= $rowCURRID->CURR_CODE;
                                    ?>
                                        <option value="<?php echo $CURR_ID1B;?>" <?php if($CURR_ID1B == $CURR_ID2) { ?>selected<?php } ?>><?php echo "$CURR_CODE1B";?></option>
                                    <?php
                                endforeach;
                            ?>
                        </select>                    </td>
                </tr>
                <script>
                    function checkCURID1(thisVal)
                    {
                        var decFormat		= document.getElementById('decFormat').value;
                        CURRID1				= thisVal;
                        CURRID2				= document.getElementById('CURR_ID2').value;
                        if(CURRID1 == CURRID2)
                        {
                            theValue	= 1;
                            document.getElementById('CC_VALUE').value	= theValue;
                            document.getElementById('CC_VALUEX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(theValue)),decFormat));
                        }
                    }
                    
                    function checkCURID2(thisVal)
                    {
                        var decFormat		= document.getElementById('decFormat').value;
                        CURRID1				= document.getElementById('CURR_ID1').value;
                        CURRID2				= thisVal;
                        if(CURRID1 == CURRID2)
                        {
                            theValue	= 1;
                            document.getElementById('CC_VALUE').value	= theValue;
                            document.getElementById('CC_VALUEX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(theValue)),decFormat));
                        }
                    }
                </script>
                <tr>
                    <td align="left" class="style1">Start Date</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" style="font-style:italic">
                        <div class="input-group date">
                            <div class="input-group-addon">
                            	<i class="fa fa-calendar"></i></div>
                            <input type="text" name="CC_STARTD" class="form-control pull-left" id="datepicker" value="<?php echo $CC_STARTD; ?>" size="10" style="width:150px" onChange="changeFDate2()">
                        </div>					</td>
                </tr>
                <tr style="vertical-align:top">
                    <td align="left" class="style1">Duration (Days)</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" style="font-style:italic">
                        <input type="text" name="CC_DURATION" id="CC_DURATION" value="<?php echo $CC_DURATION; ?>" class="form-control" style="max-width:50px" onBlur="changeFDate(this.value);" >                    </td>
                </tr>
                <tr>
                    <td align="left" class="style1">End Date</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" style="font-style:italic">
                        <input type="hidden" name="CC_ENDD" id="CC_ENDD" value="<?php echo $CC_ENDD; ?>"  size="15" >
                        <input type="text" name="CC_ENDDX" id="CC_ENDDX" value="<?php echo $CC_ENDD; ?>" class="form-control" style="max-width:150px" disabled ></td>
                </tr>
                <tr>
                    <td align="left" class="style1">Value Convertion</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" style="font-style:italic">
                        <input type="hidden" name="CC_VALUE" id="CC_VALUE" size="15"  value="<?php echo $CC_VALUE; ?>" >
                        <input type="text" name="CC_VALUEX" id="CC_VALUEX"  class="form-control" style="max-width:150px; text-align:right;" value="<?php print number_format($CC_VALUE, $decFormat); ?>" onBlur="getCurrVal(this)" ></td>
                </tr>
                <script>
                    function getCurrVal(thisVal)
                    {
                        theValue			= thisVal.value.split(",").join("");
                        var decFormat		= document.getElementById('decFormat').value;
                        document.getElementById('CC_VALUEX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(theValue)),decFormat));
                        document.getElementById('CC_VALUE').value 		= theValue;
                    }
            
                    function doDecimalFormatxx(angka) {
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
                        //else return (c);  // untuk menghilangkan 2 angka di belakang koma
                    }
                </script>
                <tr>
                    <td align="left" class="style1">&nbsp;</td>
                    <td align="left" class="style1">&nbsp;</td>
                    <td align="left" class="style1">&nbsp;</td>
              </tr>
                <tr>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1">
                  <!--<input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php //if($task=='add')echo 'save'; else echo 'update';?>" align="left" />&nbsp;-->
                    <?php 
                            /*if ( ! empty($link))
                            {
                                foreach($link as $links)
                                {
                                    echo $links;
                                }
                            }*/
                        ?>
                  
                  <?php
					if($ISCREATE == 1)
					{
						if($task=='add')
						{
							?>
								<button class="btn btn-primary">
									<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
								</button>&nbsp;
							<?php
						}
						else
						{
							?>
								<button class="btn btn-primary" >
									<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
								</button>&nbsp;
							<?php
						}
					}
				
					echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
				?>
                        
                  </td>
                </tr>
                <tr>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1"><hr></td>
                </tr> 
            </table>
      </form>
    </div>
</section>
</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
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
    $('#datepicker').datepicker({
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
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>