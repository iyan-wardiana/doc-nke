<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 21 Februari 2017
    * File Name	= employee_setproj_form.php
    * Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$PRJSCATEG  = $this->session->userdata['PRJSCATEG'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
    $APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

if($PRJSCATEG == 1)
    $PRJTYPE    = "2,3";
else
    $PRJTYPE    = "1";

$getproject     = "SELECT PRJCODE, PRJNAME FROM tbl_project
                    WHERE PRJCODE NOT IN (SELECT A.PRJCODE FROM tbl_ir_sett A INNER JOIN tbl_project B ON B.PRJCODE = A.PRJCODE WHERE A.EMP_ID = '$Emp_ID')
                        AND PRJTYPE IN ($PRJTYPE)
                    ORDER BY PRJCODE";
$qProject 		= $this->db->query($getproject)->result();
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
    	
    	if($LangID == 'IND')
    	{
    		$subTitleH	= "Tambah";
    		$subTitleD	= "penggunaan kas proyek";
    		$alert0		= "Silahkan pilih proyek pada kolom sebelah kanan.";
    		$alert1		= "Silahkan pilih akun pada kolom sebelah kanan.";
    		$alert2		= "Anda belum/tidak menentukan employee ID untuk karyawan ini.";
    	}
    	else
    	{
    		$subTitleH	= "Add";
    		$subTitleD	= "project cash payment";
    		$alert0		= "Please select the project(s) in the right side.";
    		$alert1		= "Please select the account(s) in the right side.";
    		$alert2		= "You do not select an Account(s) for this employee(s)";
    	}
    ?>
                      
    <script>
      function validateInCurr()
      {
        CURR_ID   = document.getElementById('CURR_ID').value;
        CURR_CODE = document.getElementById('CURR_CODE').value;
        if(CURR_ID == "")
        {
            swal('<?php echo $currEmpty; ?>',
            {
                icon: "warning",
            });
            document.getElementById('CURR_ID').focus();
            return false;
        }
        if(CURR_CODE == "")
        {
            swal('<?php echo $currEmpty; ?>',
            {
                icon: "warning",
            });
            document.getElementById('CURR_CODE').focus();
            return false;
        }
      }
    </script>
    <?php
      $urlUpdAuth     = site_url('c_hr/c_employee/c_employee/employee_auth_items_process/?id='.$this->url_encryption_helper->encode_url($appName));

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $mnName; ?>
                <!-- <small>setting</small> -->
            </h1>
        </section>

        <section class="content">   
                <form class="form-horizontal" name="absen_form" method="post" action="<?php echo $urlUpdAuth; ?>" enctype="multipart/form-data" onSubmit="return confirmDelete()">
                    <td width="16%"><input type="hidden" id="Emp_ID1" name="Emp_ID1" value="<?php print $Emp_ID; ?>" width="10" size="10" class="textbox">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border" style="display: none;">
                                    <i class="fa fa-cloud-upload"></i>
                                    <h3 class="box-title">&nbsp;</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Function Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="" value="<?=$h2_title?>" disabled />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Username</label>
                                        <div class="col-sm-10">
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
                                            <input type="text" class="form-control" name="" id="" value="<?php echo "$First_Name $Middle_Name $Last_Name"; ?>" disabled />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Position Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="" value="<?php echo $POS_NAME; ?>" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <i class="fa fa-users"></i>
                                    <h3 class="box-title">Project Authorization Item Category Material Curah</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <select multiple class="form-control" name="pavailable" onclick="MoveOption(this.form.pavailable, this.form.packageelements)" style="height: 150px">
                                                <?php
                                                    foreach($qProject as $rowPRJ) :
                                                        $PRJCODE    = $rowPRJ->PRJCODE;
                                                        $PRJNAME    = $rowPRJ->PRJNAME;
                                                        ?>
                                                            <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME";?></option>
                                                        <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php
                                                $getCount       = "tbl_project
                                                                    WHERE PRJCODE IN (SELECT A.PRJCODE FROM tbl_ir_sett A INNER JOIN tbl_project B ON B.PRJCODE = A.PRJCODE WHERE A.EMP_ID = '$Emp_ID')
                                                                        AND PRJTYPE IN ($PRJTYPE)
                                                                    ORDER BY PRJCODE";
                                                $resGetCount    = $this->db->count_all($getCount);
                                            ?>
                                            <select multiple class="form-control" name="packageelements[]" id="packageelements" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)" style="height: 150px">
                                                <?php
                                                    if($resGetCount > 0)
                                                    {
                                                        $getData        = "SELECT PRJCODE, PRJNAME FROM tbl_project
                                                                            WHERE PRJCODE IN (SELECT A.PRJCODE FROM tbl_ir_sett A INNER JOIN tbl_project B ON B.PRJCODE = A.PRJCODE WHERE A.EMP_ID = '$Emp_ID')
                                                                                AND PRJTYPE IN ($PRJTYPE)
                                                                            ORDER BY PRJCODE";
                                                        $resGetData     = $this->db->query($getData)->result();
                                                        foreach($resGetData as $rowData) :
                                                            $proj_Code  = $rowData->PRJCODE;
                                                            $proj_Name  = $rowData->PRJNAME;
                                                            ?>
                                                                <option value="<?php echo $proj_Code; ?>"><?php echo "$proj_Code - $proj_Name";?></option>
                                                            <?php
                                                        endforeach; 
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-sm-9">
                                    <!-- <input type="submit" name="btnDelete" id="btnDelete" class="btn btn-primary" value="Update Project" />&nbsp; -->
                                    <button class="btn btn-primary">
                                    <i class="fa fa-save"></i></button>&nbsp;
                                    <?php
                                        $backURL        = site_url('c_hr/c_employee/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));
                                        echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>&nbsp;');
                                        /*if ( ! empty($link))
                                        {
                                            foreach($link as $links)
                                            {
                                                echo $links;
                                            }
                                        }*/
                                    ?>
                                </div>
                            </div>
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
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"> 
	function MoveOption(objSourceElement, objTargetElement) 
	{ 
		var aryTempSourceOptions = new Array(); 
		var aryTempTargetOptions = new Array(); 
		var x = 0; 
	
		//looping through source element to find selected options 
		for (var i = 0; i < objSourceElement.length; i++)
		{ 
			if (objSourceElement.options[i].selected)
			{ 
				 //need to move this option to target element 
				 var intTargetLen = objTargetElement.length++; 
				 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
				 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
			} 
			else
			{ 
				 //storing options that stay to recreate select element 
				 var objTempValues = new Object(); 
				 objTempValues.text = objSourceElement.options[i].text; 
				 objTempValues.value = objSourceElement.options[i].value; 
				 aryTempSourceOptions[x] = objTempValues; 
				 x++; 
			} 
		} 
	
		//sorting and refilling target list 
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			var objTempValues = new Object(); 
			objTempValues.text = objTargetElement.options[i].text; 
			objTempValues.value = objTargetElement.options[i].value; 
			aryTempTargetOptions[i] = objTempValues; 
		} 
	
		aryTempTargetOptions.sort(sortByText); 
	
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
			objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
			objTargetElement.options[i].selected = false; 
		} 
	
		//resetting length of source 
		objSourceElement.length = aryTempSourceOptions.length; 
		//looping through temp array to recreate source select element 
		for (var i = 0; i < aryTempSourceOptions.length; i++) 
		{ 
			objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
			objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
			objSourceElement.options[i].selected = false; 
		} 
	} 
	
	function sortByText(a, b) 
	{ 
		if (a.text < b.text) {return -1} 
		if (a.text > b.text) {return 1} 
		return 0;
	} 
	
	function selectAll(objTargetElement) 
	{ 
		for (var i = 0; i < objTargetElement.length; i++) 
		{ 
			objTargetElement.options[i].selected = true; 
		} 
		//return false;
  	}
	
	function confirmDelete()
	{
		column1	= document.getElementById('packageelements').value;
		if(column1 == '')
		{
            swal('<?php echo $alert0; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                document.getElementById('packageelements').focus();
            });
			return false;
		}
	}
  //--> 
 </SCRIPT> 
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