<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Februari 2017
 * File Name	= employee_auth_form.php
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
		CURR_ID		= document.getElementById('CURR_ID').value;
		CURR_CODE	= document.getElementById('CURR_CODE').value;
		if(CURR_ID == "")
		{
			alert('Currency ID can not be empty.');
			document.getElementById('CURR_ID').focus();
			return false;
		}
		if(CURR_CODE == "")
		{
			alert('Currency Code can not be empty.');
			document.getElementById('CURR_CODE').focus();
			return false;
		}
	}
</script>
<?php
	$urlUpdAuth			= site_url('c_setting/c_employee/employee_docauth_process/?id='.$this->url_encryption_helper->encode_url($appName));
?>
<section class="content">
    <div class="box box-primary"><br>
		<form action="<?php echo $urlUpdAuth; ?>" onSubmit="confirmDelete();" method=POST>
            <table width="100%">
                <tr>
                    <td width="16%">
                    &nbsp;&nbsp;&nbsp;<input type="hidden" id="Emp_ID1" name="Emp_ID1" value="<?php print $Emp_ID; ?>" width="10" size="10" class="textbox">
                        &nbsp;Function Name        </td>
                    <td width="1%">:</td>
                    <td width="83%">Function Authorization</td>
                </tr>
                <tr>
                    <td width="16%">
                        &nbsp;&nbsp;&nbsp;&nbsp;Username Name        </td>
                    <td width="1%">:</td>
                <?php
                        $sqlgEmp = "SELECT A.Position_ID, A.First_Name, A.Middle_Name, A.Last_Name, B.POS_NAME
                                    FROM tbl_employee A
                                    LEFT JOIN tbl_position B ON A.POS_CODE = B.POS_CODE
                                    WHERE A.emp_id = '$Emp_ID'";
                        $ressqlgEmp = $this->db->query($sqlgEmp)->result();
                        foreach($ressqlgEmp as $rowEmp) :
                            $First_Name = $rowEmp->First_Name;
                            $Middle_Name = $rowEmp->Middle_Name;
                            $Last_Name = $rowEmp->Last_Name;
                            $POS_NAME = $rowEmp->POS_NAME;
                        endforeach;
                    ?>
                    <td width="83%"><?php echo "$First_Name $Middle_Name $Last_Name";; ?></td>
                </tr>
                <tr>
                    <td width="16%">
                        &nbsp;&nbsp;&nbsp;&nbsp;Position Name        </td>
                    <td width="1%">:</td>
                  	<td width="83%" style="font-weight:bold"><?php echo $POS_NAME; ?></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">
                    <div class="box box-primary">           
                        <div class="box-header with-border">
                            <h3 class="box-title">Document Access</h3>
                        </div>
                    </div>
                    </td>
                </tr>
                <?php 
                $i = 0;
                $i1 = 0;
                $i2 = 0;
                $i3 = 0;
                foreach($viewalltype as $row) : 
                {
                    $i = $i + 1;
                    $doc_code1 		= $row->doc_code;
                    // To Get Checked
                    $doc_IDg1a 		= $row->doc_ID;
                    $doc_IDg1 		= 0;
                    $doc_codeg1 	= '';
                    $sqlg1 			= "SELECT isChkDetail, emp_id, doc_code
										FROM tbl_userdoctype
										WHERE emp_id = '$Emp_ID' AND doc_code = '$doc_code1'";
                    $resultg1 = $this->db->query($sqlg1)->result();
                    foreach($resultg1 as $rowg1) :
                        $doc_IDg1 	= $rowg1->isChkDetail;
                        $doc_codeg1 = $rowg1->doc_code;
                    endforeach;
                    ?>
                    <tr>
                        <td colspan="3" style="text-align:left; font-weight:bold">
                        &nbsp;&nbsp;&nbsp;<input name="chkDetail<?php echo $i; ?>" id="chkDetail<?php echo $i; ?>" type="checkbox" value="<?php echo $row->doc_ID; ?>" onClick="checkVal(<?php echo $i; ?>)" <?php if($doc_IDg1 == $doc_IDg1a) { ?> checked <?php } ?>/>
                        &nbsp;&nbsp;&nbsp;<?php print ""; ?>
                        <input type="hidden" name="data[<?php echo $i; ?>][Emp_ID]" id="data<?php echo $i; ?>Emp_ID" size="6" value="<?php print $Emp_ID; ?>" />
                        <input type="hidden" name="data[<?php echo $i; ?>][isChkDetail]" id="data<?php echo $i; ?>isChkDetail" size="6" value="<?php print $doc_IDg1; ?>" />
                        <input type="hidden" name="data[<?php echo $i; ?>][doc_code]" id="data<?php echo $i; ?>doc_code" size="6" value="<?php print $doc_code1; ?>" />
                        <?php print $row->doc_name; ?> </td>
                    </tr>
                    <?php
                        // Menu level 2
                        $sql2 		= "SELECT doc_ID, doc_code, doc_level, doc_parent, doc_name
										FROM tbl_document
										WHERE doc_parent = '$doc_code1'
											AND isHRD = 1
											AND doc_level = 3
										ORDER BY doc_code";
                        $result2 	= $this->db->query($sql2)->result();
                        // count data
							$resultCount2	= "tbl_document WHERE doc_parent = '$doc_code1' AND isHRD = 1 AND doc_level = 3";
							$resultCount2	= $this->db->count_all($resultCount2);
                        // End count data
                        if($resultCount2 > 0)
                        {
                            foreach($result2 as $row2) :
                            {
                                $i2 = $i + 1;
                                $i = $i2;
                                $doc_ID2 	= $row2->doc_ID;
                                $doc_code2 	= $row2->doc_code;
                                $doc_name2 	= $row2->doc_name;
                                // To Get Checked
                                $doc_IDg2a 	= $doc_ID2;
                                $doc_IDg2 	= 0;
                                $doc_codeg2 = '';
                                $sqlg2 		= "SELECT isChkDetail, emp_id, doc_code
												FROM tbl_userdoctype
												WHERE emp_id = '$Emp_ID' AND doc_code = '$doc_code2'";
                                $resultg2 = $this->db->query($sqlg2)->result();
                                foreach($resultg2 as $rowg2) :
                                    $doc_IDg2 = $rowg2->isChkDetail;
                                    $doc_codeg2 = $rowg2->doc_code;
                                endforeach;
                        ?>
                            <tr>
                                <td colspan="3" style="text-align:left">
                                &nbsp;&nbsp;&nbsp;<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?>
                                 <input name="chkDetail<?php echo $i2; ?>" id="chkDetail<?php echo $i2; ?>" type="checkbox" value="<?php echo $doc_ID2; ?>" onClick="checkVal(<?php echo $i2; ?>)" <?php if($doc_IDg2 == $doc_IDg2a) { ?> checked <?php } ?> />
                                &nbsp;&nbsp;&nbsp;<?php print ""; ?>
                                <input type="hidden" name="data[<?php echo $i2; ?>][Emp_ID]" id="data<?php echo $i2; ?>Emp_ID" size="6" value="<?php print $Emp_ID; ?>" />
                                <input type="hidden" name="data[<?php echo $i2; ?>][isChkDetail]" id="data<?php echo $i2; ?>isChkDetail" size="6" value="<?php print $doc_IDg2; ?>" />
                                <input type="hidden" name="data[<?php echo $i2; ?>][doc_code]" id="data<?php echo $i2; ?>doc_code" size="6" value="<?php print $doc_code2; ?>" />
                                <?php print $doc_name2; ?> </td>        
                            </tr>
                            <?php
                                // Menu level 3
								$sql3 		= "SELECT doc_ID, doc_code, doc_level, doc_parent, doc_name
												FROM tbl_document
												WHERE doc_parent = '$doc_code2'
													AND isHRD = 1
													AND doc_level = 4
												ORDER BY doc_code";
                                $result3 	= $this->db->query($sql3)->result();
                                // count data
									$resultCount3	= "tbl_document WHERE doc_parent = '$doc_code2' AND isHRD = 1 AND doc_level = 4";
									$resultCount3	= $this->db->count_all($resultCount3);
                                // End count data
                                if($resultCount3 > 0)
                                {
                                    foreach($result3 as $row3) :
                                    {
                                        $i3 = $i2 + 1;
                                        $i2 = $i3;
                                        $i = $i3;
                                        $doc_ID3 	= $row3->doc_ID;
                                        $doc_code3 	= $row3->doc_code;
                                        $doc_name3 	= $row3->doc_name;
                                        // To Get Checked
                                        $doc_IDg3a = $doc_ID3;
                                        $doc_IDg3 = 0;
                                        $doc_codeg3 = '';
                                        $sqlg3 		= "SELECT isChkDetail, emp_id, doc_code
														FROM tbl_userdoctype
														WHERE emp_id = '$Emp_ID' AND doc_code = '$doc_code3'";
                                        $resultg3 	= $this->db->query($sqlg3)->result();
                                        foreach($resultg3 as $rowg3) :
                                            $doc_IDg3 = $rowg3->isChkDetail;
                                            $doc_codeg3 = $rowg3->doc_code;
                                        endforeach;
                                ?>
                                    <tr>
                                        <td colspan="3" style="text-align:left">
                                        &nbsp;&nbsp;&nbsp;<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?>
                                        <input name="chkDetail<?php echo $i3; ?>" id="chkDetail<?php echo $i3; ?>" type="checkbox" value="<?php echo $doc_ID3; ?>" onClick="checkVal(<?php echo $i3; ?>)" <?php if($doc_IDg3 == $doc_IDg3a) { ?> checked <?php } ?> />
                                        &nbsp;&nbsp;&nbsp;<?php print ""; ?>
                                        <input type="hidden" name="data[<?php echo $i3; ?>][Emp_ID]" id="data<?php echo $i3; ?>Emp_ID" size="6" value="<?php print $Emp_ID; ?>" />
                                        <input type="hidden" name="data[<?php echo $i3; ?>][isChkDetail]" id="data<?php echo $i3; ?>isChkDetail" size="6" value="<?php print $doc_IDg3; ?>" />
                                        <input type="hidden" name="data[<?php echo $i3; ?>][doc_code]" id="data<?php echo $i3; ?>doc_code" size="6" value="<?php print $doc_code3; ?>" />
                                        <?php print $doc_name3; ?> </td>        
                                    </tr>                                    
                                <?php
                                    }
                                    endforeach;
                                }
                            ?>
                        <?php
                            }
                            endforeach;
                        }
                    ?>
                <?php
                } 
                endforeach; 
                ?>
              <tr height="20">
                    <td colspan="3">&nbsp;</td>
              </tr>
                <tr>
                    <td colspan="3">
                    <div class="box box-primary">           
                        <div class="box-header with-border">
                            <h3 class="box-title">Document Authorization</h3>
                        </div>
                    </div>
                    </td>
                </tr>
                <?php
					$DAU_WRITE 	= 0;
					$DAU_READ 	= 0;
					$DAU_DL 	= 0;
					$sqlDAU 		= "SELECT DAU_WRITE, DAU_READ, DAU_DL
										FROM tbl_employee_docauth
										WHERE DAU_EMPID = '$Emp_ID'";
					$resultDAU 		= $this->db->query($sqlDAU)->result();
					foreach($resultDAU as $rowDAU) :
						$DAU_WRITE 	= $rowDAU->DAU_WRITE;
						$DAU_READ 	= $rowDAU->DAU_READ;
						$DAU_DL 	= $rowDAU->DAU_DL;
					endforeach;
				?>
                <tr>
                    <td width="16%">
                        &nbsp;&nbsp;&nbsp;&nbsp;Document Authorization        </td>
                    <td width="1%">:</td>
                  	<td width="83%" style="font-weight:bold">
                        <input type="checkbox" name="DAU_WRITE" id="DAU_WRITE" value="1" <?php if($DAU_WRITE == 1) { ?> checked <?php } ?> onClick="checkthisW();">
                        Write (Add and View) &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="DAU_READ" id="DAU_READ" value="1" <?php if($DAU_READ == 1) { ?> checked <?php } ?> onClick="checkthisR();">
                        Read (View Only) &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="DAU_DL" id="DAU_DL" value="1" <?php if($DAU_DL == 1) { ?> checked <?php } ?> onClick="checkthisD();">
                        Download (Download Only)
					</td>
              	</tr>
            </table>
        
            <table width="100%" border="0">
                <tr height="20">
                    <td colspan="3"><hr /></td>
                </tr>
                <tr height="20">
                    <td colspan="3">
                        <!--<input type="submit" name="btnDelete" id="btnDelete" class="btn btn-primary" value="Update Authorization" />&nbsp;-->
                        <button class="btn btn-primary" >
                            <i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                        </button>&nbsp;
                        <?php 
                            /*if ( ! empty($link))
                            {
                                foreach($link as $links)
                                {
                                    echo $links;
                                }
                            }*/
							
							echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
                        ?>
                    </td>
                </tr>
                <tr height="20">
                    <td colspan="3"><hr /></td>
                </tr>
            </table>
        </form>
        </div>
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
	function checkVal(thisVal)
	{
		var isCheck = document.getElementById('chkDetail'+thisVal).checked;
		var isCheckVal = document.getElementById('chkDetail'+thisVal).value;
		if(isCheck == true)
		{
			document.getElementById('data'+thisVal+'isChkDetail').value = isCheckVal;
		}
		else
		{
			document.getElementById('data'+thisVal+'isChkDetail').value = 0;
		}
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>