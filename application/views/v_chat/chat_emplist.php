<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 April 2017
 * File Name	= chat_emplist.php
 * Location		= -
*/

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
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
    <div class="callout callout-success">
        Please select a friend below.
    </div>
	<?php
        $i = 0;
        $j = 0;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $sqlSENDC 		= "tbl_employee_circle A
                                INNER JOIN tbl_employee B
                            WHERE A.Emp_ID  = '$DefEmp_ID'";
        $resSENDC 		= $this->db->count_all($sqlSENDC);
        
        if($resSENDC > 0)
        {
            $sqlSEND	= "SELECT A.Followings AS EMP_ID, B.First_Name, B.Last_Name
                            FROM tbl_employee_circle A
                                INNER JOIN tbl_employee B ON A.Followings = B.Emp_ID
                            WHERE A.Emp_ID  = '$DefEmp_ID'";
            $resSEND 	= $this->db->query($sqlSEND)->result();
            $theRow	= 0;
            foreach($resSEND as $rowSEND) :
                $EMP_ID 		= $rowSEND->EMP_ID;
                $First_Name1 	= $rowSEND->First_Name;
                $Last_Name1		= $rowSEND->Last_Name;
                $Last_Name1		= " $Last_Name1";
                $COMPLETE_NM1	= "$First_Name1$Last_Name1";
				$COMPLETE_NM2	= strtolower($COMPLETE_NM1);
				$COMPLETE_NM	= ucwords($COMPLETE_NM2);
                
				$imgemp_fnSend	= 'username';
                $imgemp_fnSendX	= '';
                $getIMGSend		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$EMP_ID'";
                $resIMGSend 		= $this->db->query($getIMGSend)->result();
                foreach($resIMGSend as $rowIMGSend) :
                    $imgemp_fnSend 	= $rowIMGSend ->imgemp_filename;
                    $imgemp_fnSendX = $rowIMGSend ->imgemp_filenameX;
                endforeach;
                $imgFriend		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$EMP_ID.'/'.$imgemp_fnSendX);
				if($imgemp_fnSend == 'username')
					$imgFriend	= base_url('assets/AdminLTE-2.0.5/emp_image/'.$imgemp_fnSendX);
                
				$sqlC			= "tbl_chat WHERE ((CHAT_EMP_FROM = '$DefEmp_ID' AND CHAT_EMP_TO = '$EMP_ID') 
									OR (CHAT_EMP_SENDER = '$DefEmp_ID' AND CHAT_EMP_SENDER = '$EMP_ID'))";
				$resC			= $this->db->count_all($sqlC);
				if($resC == 0)
				{
                	$theRow			= $theRow + 1;
					?>
						<div class="col-md-3">
							<div class="box box-warning box-solid">
								<div class="box-header with-border">
									<?php echo $COMPLETE_NM; ?>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="box-body" style="display:none;">
									<img class="direct-chat-img" src="<?php echo $imgFriend; ?>" alt="Message User Image">
									<div class="tab-pane" id="glyphicons">
										<ul class="bs-glyphicons">
											<li>
												<span class="glyphicon glyphicon-pencil" onClick="showChat(<?php echo $theRow; ?>)"></span>
												<span class="glyphicon-class">Chat</span>
											</li>
											<li>
												<span class="glyphicon glyphicon-envelope"></span>
												<span class="glyphicon-class">Mail</span>
											</li>
											<li>
												<span class="glyphicon glyphicon-new-window"></span>
												<span class="glyphicon-class">View</span>
											</li>
										</ul>
									</div>
									<div class="box-footer" style="display:none" id="chatNew_<?php echo $theRow; ?>">
										<form action="" method="post">
											<div class="input-group">
												<input type="hidden" name="chatRow_<?php echo $theRow; ?>" id="chatRow_<?php echo $theRow; ?>" value="<?php echo $theRow; ?>" >
												<input type="hidden" name="CHAT_EMP_FROM_<?php echo $theRow; ?>" id="CHAT_EMP_FROM_<?php echo $theRow; ?>" value="<?php echo $DefEmp_ID; ?>" >
												<input type="hidden" name="CHAT_EMP_TO_<?php echo $theRow; ?>" id="CHAT_EMP_TO_<?php echo $theRow; ?>" value="<?php echo $EMP_ID; ?>" >
												<input type="text" name="CHAT_MESSAGE_<?php echo $theRow; ?>" id="CHAT_MESSAGE_<?php echo $theRow; ?>" placeholder="Type Message ..." class="form-control">
												<span class="input-group-btn">
													<button type="button" name="submit_<?php echo $theRow; ?>" dir="submit_<?php echo $theRow; ?>" onClick="functionInput('<?php echo $theRow; ?>')" class="btn btn-primary btn-flat">Send</button>
												</span>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					<?php
				}
			endforeach;
		}
	?>
</section>
</body>
<script>
	function showChat(theRow)
	{
		document.getElementById('chatNew_'+theRow).style.display = '';
	}
</script>
<style>
.bs-glyphicons {
  padding-left: 0;
  padding-bottom: 1px;
  margin-bottom: 10px;
  list-style: none;
  overflow: hidden;
}

.bs-glyphicons li {
  float: left;
  width: 25%;
  font-size: 12px;
  text-align: center;
}

.bs-glyphicons .glyphicon {
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}

.bs-glyphicons .glyphicon-class {
  display: block;
  text-align: center;
  word-wrap: break-word; /* Help out IE10+ with class names */
}

.bs-glyphicons li:hover {
  background-color: rgba(86, 61, 124, .1);
}

@media (min-width: 768px) {
  .bs-glyphicons li {
	width: 12.5%;
  }
}
</style>
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

<script>
	function checkPref(theRow)
	{
		alert(theRow)
		document.getElementById('AS_PREFIXX').value = theRow;
		//document.frmsrch1.submitSrch1.click();
		
		document.frm_+theRow.submit_+theRow.click();
	}
							
	function functionInput(theRow)
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
				
				window.opener.refreshPage();
				window.close();
				if(recordcount > 0)
				{
					//document.getElementById('CheckThe_Code').value= recordcount;
					//document.getElementById("isHidden").innerHTML = ' Recomendation No already exist ... !';
					//document.getElementById("isHidden").style.color = "#ff0000";
				}
				else
				{
					//document.getElementById('CheckThe_Code').value= recordcount;
					//document.getElementById("isHidden").innerHTML = ' Recomendation No : OK .. !';
					//document.getElementById("isHidden").style.color = "green";
				}
			}
		}
		var chatRow_ 		= document.getElementById('chatRow_'+theRow).value;
		var CHAT_EMP_FROM_ 	= document.getElementById('CHAT_EMP_FROM_'+theRow).value;
		var CHAT_EMP_TO_ 	= document.getElementById('CHAT_EMP_TO_'+theRow).value;
		var CHAT_MESSAGE_a 	= document.getElementById('CHAT_MESSAGE_'+theRow).value;
		var CHAT_MESSAGE_b 	= CHAT_MESSAGE_a.replace(".","|");
		var CHAT_MESSAGE_c 	= CHAT_MESSAGE_b.replace(",","`");
		var CHAT_MESSAGE_ 	= CHAT_MESSAGE_c.replace("?","^");
		if(CHAT_MESSAGE_ == '')
		{
			alert('Please write a message.');
			document.getElementById('CHAT_MESSAGE_'+theRow).focus();
			return false;
		}
		var COLL_DATA		= ''+chatRow_+'~'+CHAT_EMP_FROM_+'~'+CHAT_EMP_TO_+'~'+CHAT_MESSAGE_+'';
		
		ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_chat/c_chat/getSentDat/';?>" + COLL_DATA, true);
		ajaxRequest.send(null);
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>