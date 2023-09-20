<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 Februari 2017
	* File Name		= employee_setdash_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$PRJSCATEG	= $this->session->userdata['PRJSCATEG'];
if($PRJSCATEG == 1)
	$ADDQTY = "AND DS_CATEG IN (1,3)";
else
	$ADDQTY = "AND DS_CATEG IN (1,2)";

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

$getdashboard 	= "SELECT DS_ROW, DS_TYPE, DS_NOTES 
					FROM tbl_dash_sett 
						WHERE DS_TYPE NOT IN (SELECT A.DS_TYPE FROM tbl_dash_sett_emp A INNER JOIN tbl_dash_sett B ON B.DS_TYPE = A.DS_TYPE WHERE A.EMP_ID = '$Emp_ID')
            				AND DS_STAT = 1 $ADDQTY
					ORDER BY DS_NOTES ASC";
$qDashboard 	= $this->db->query($getdashboard)->result();
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
    ?>
                      
    <script>
      	function validateInCurr()
      	{
			CURR_ID   = document.getElementById('CURR_ID').value;
			CURR_CODE = document.getElementById('CURR_CODE').value;
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
      	$urlUpdAuthDash   = site_url('c_hr/c_employee/c_employee/employee_dashboard_process/?id='.$this->url_encryption_helper->encode_url($appName));
      	$urlUpdAuthCreate = site_url('c_hr/c_employee/c_employee/employee_create_process/?id='.$this->url_encryption_helper->encode_url($appName));

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
            <div class="box box-primary"><br>
        	  	<form action="<?php echo $urlUpdAuthDash; ?>" method=POST>
                  	<table width="100%">
                        <tr>
                            <td width="16%"><input type="hidden" id="Emp_ID1" name="Emp_ID1" value="<?php print $Emp_ID; ?>" width="10" size="10" class="textbox">
                              &nbsp;&nbsp;&nbsp;Function Name        </td>
                            <td width="1%">:</td>
                            <td width="83%">Authorization Employee's Project</td>
                        </tr>
                      	<tr>
                            <td width="16%">
                            	&nbsp;&nbsp;&nbsp;Username Name        </td>
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
                          	<td colspan="3">&nbsp;</td>
                     	</tr>
                      	<tr>
                          	<td colspan="3">
                                <div class="box box-primary">           
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Dashboard Authorization</h3>
                                    </div>
                                </div>
                        	</td>
        				</tr>
                      	<tr>
                       	  	<td colspan="3">
                             	<table width="200" border="0">
	                                <tr>
	                                  	<td width="12">&nbsp;</td>
	                                  	<td width="106">
	                                  		<select multiple="multiple" class="options" size="10" name="pavailable" style="height: 300px" onclick="MoveOption(this.form.pavailable, this.form.packageelements)">
	                                        	<?php
		                                            foreach($qDashboard as $rowDSB) :
		                                                $DS_ROW 	= $rowDSB->DS_ROW;
		                                                $DS_TYPE 	= $rowDSB->DS_TYPE;
		                                                $DS_NOTES	= $rowDSB->DS_NOTES;
		                                                
		                                                ?>
		                                              <option value="<?php echo $DS_TYPE; ?>"><?php echo "$DS_NOTES";?></option>
		                                              <?php
		                                            endforeach;
		                                           	// endforeach; 
	                                        	?>
	                                     	</select>
	                                  	</td>
	                                  	<td width="60" bor>
	                                      	<?php					
	                                            $getCount		= "tbl_dash_sett_emp WHERE EMP_ID = '$Emp_ID'";
	                                            $resGetCount	= $this->db->count_all($getCount);
	                                        ?>
	                                      	<select multiple="multiple" name="packageelements[]" id="packageelements" size="10" style="width: 300px; height: 300px" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)">
	                                        	<?php
	                                            if($resGetCount > 0)
	                                            {
	                                                $getData		= "SELECT A.EMP_ID, A.DS_TYPE, B.DS_NOTES 
	                                                                    FROM tbl_dash_sett_emp A
	                                                                    	INNER JOIN tbl_dash_sett B ON B.DS_TYPE = A.DS_TYPE AND B.DS_STAT = '1'
	                                                                    WHERE A.EMP_ID = '$Emp_ID' $ADDQTY ORDER BY B.DS_NOTES ASC";
	                                                $resGetData 	= $this->db->query($getData)->result();
	                                                foreach($resGetData as $rowData) : 
	                                                {
	                                                    $EMP_ID 	= $rowData->EMP_ID;
	                                                    $DS_TYPE 	= $rowData->DS_TYPE;
	                                                    $DS_NOTES 	= $rowData->DS_NOTES;
	                                                    ?>
	                                                  <option value="<?php echo $DS_TYPE; ?>"><?php echo "$DS_NOTES";?></option>
	                                                  <?php
	                                                } 
	                                                endforeach; 
	                                            }
	                                        ?>
	                                      	</select>
	                                    </td>
	                                </tr>
                              	</table>
                          	</td>
        				</tr>
                  	</table>
        			<table width="100%" border="0">
                        <tr height="20">
                            <td colspan="3">&nbsp;</td>
                		</tr>
                        <tr height="20">
                            <td colspan="3">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- <input type="submit" name="btnDelete" id="btnDelete" class="btn btn-primary" value="Update Dashboard" />&nbsp; -->
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
                            </td>
                        </tr>
                        <tr height="20">
                            <td colspan="3"><hr /></td>
                        </tr>
                	</table>
		            <?php
		                $DefID      = $this->session->userdata['Emp_ID'];
		                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		                if($DefID == 'D15040004221')
		                    echo "<font size='1'><i>$act_lnk</i></font>";
		            ?>
                </form>
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
		for (var i = 0; i < objTargetElement.length; i++) { 
			objTargetElement.options[i].selected = true; 
		} 
	   	return false; 
  	} 
  	//--> 
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