<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 08 Maret 2017
	* File Name		= v_ledger.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody  	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateM/$Start_DateD/$Start_DateY";	
$End_Date 		= "$Start_DateM/$Start_DateD/$Start_DateY";	

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject 		= $this->db->query($getproject)->result();

$ISHO_PRJ	= '';
$sqlPRJ 	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$ISHO_PRJ	= $rowPRJ->PRJCODE;
endforeach;

$getSupplier 	= "SELECT A.SPLCODE, A.SPLDESC FROM tbl_supplier A WHERE SPLSTAT = 1 ORDER BY A.SPLDESC";
$qSupplier 		= $this->db->query($getSupplier)->result();
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
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    </head>

	<?php
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
			if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'ProjectName')$Prjnm = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
			if($TranslCode == 'SupplierName')$Splnm = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'ViewType')$ViewType = $LangTransl;
			if($TranslCode == 'Summary')$Summary = $LangTransl;
			if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
			if($TranslCode == 'Excel')$Excel = $LangTransl;
			if($TranslCode == 'genledg')$genledg = $LangTransl;
			if($TranslCode == 'trialbal')$trialbal = $LangTransl;
			if($TranslCode == 'detledg')$detledg = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1	= "Silahkan pilih salah satu anggaran.";
			$alert2	= "Silahkan pilih salah satu akun.";
			$alert3	= "Silahkan pilih kategori laporan.";
			$alert4 = "Silahkan pilih satu atau lebih supplier.";
			$AllAcc = "Semua Akun";
			$AccName= "Nama Akun";
			$AllSpl = "Semua Pemasok";
			$AllPrj = "Semua Proyek";
		}
		else
		{
			$alert1	= "Please select a budget.";
			$alert2	= "Please select an account.";
			$alert3	= "Please select a report category.";
			$alert4 = "Please select one or all supplier.";
			$AllAcc = "All Account";
			$AccName= "Account Name";
			$AllSpl = "All Supplier";
			$AllPrj = "All Project";
		}
		
		$form_action	= site_url('c_gl/c_r3p0r77l/l3dg3r_r3pp0r7vw/?id='.$this->url_encryption_helper->encode_url($appName));
		
		$PRJCODE		= $ISHO_PRJ;
		
		if(isset($_POST['PRJCODEX']))
		{
			$PRJCODE	= $_POST['PRJCODEX'];
		}
		
		$sqlDataACC 	= "SELECT DISTINCT
								A.ID,
								A.Acc_ID, 
								A.Account_Number, 
								A.Account_Nameen as Account_Name,
								A.Account_Category,
								A.Account_Class,			
								A.currency_ID,
								A.isLast
							FROM tbl_chartaccount A
								INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
							WHERE A.Currency_id = 'IDR' AND A.PRJCODE = '$PRJCODE' AND A.isLast = 1
								Order by A.ID";
		$resDataACC 	= $this->db->query($sqlDataACC)->result();

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/report_bukubesar.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
			    <small><?php echo $h3_title; ?></small>
			</h1>
		</section>      
		<script>
			function validateInCurr()
			{
				CURR_ID		= document.getElementById('CURR_ID').value;
				CURR_CODE	= document.getElementById('CURR_CODE').value;
				if(CURR_ID == "")
				{
					swal('Currency ID can not be empty.');
					document.getElementById('CURR_ID').focus();
					return false;
				}
				if(CURR_CODE == "")
				{
					swal('Currency Code can not be empty.');
					document.getElementById('CURR_CODE').focus();
					return false;
				}
			}
		</script>
		<section class="content">
		    <div class="box box-primary">
		    	<div class="box-body chart-responsive">
		        	<form name="frmsrch" id="frmsrch" action="" method=POST style="display: none;">
		                <input type="text" name="PRJCODEX" id="PRJCODEX" class="textbox" value="<?php echo $PRJCODE; ?>" />
		                <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
		            </form>
		        	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return target_popup(this)">
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Budget ?></label>
		                    <div class="col-sm-10">
		                    	<select name="PRJCODE[]" id="PRJCODE" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $Prjnm; ?>" style="width: 100%;">
		                            <!-- <option value="0"> --- </option> -->
		                            <option value="1" > <?=$AllPrj?> </option>
		                            <?php echo $i = 0;
		                            foreach($qProject as $row) :
		                                $PRJCODE1 	= $row->PRJCODE;
		                                $PRJNAME 	= $row->PRJNAME;
		                                ?>
		                                    <option value="<?php echo $PRJCODE1; ?>" <?php if ($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
		                              <?php
		                            endforeach;
		                            ?>
		                        </select>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $AccountName ?></label>
		                    <div class="col-sm-4">
                                <select name="sellAccount" id="sellAccount" class="form-control select2" data-placeholder="&nbsp;<?php echo $AccName; ?>" style="width: 100%;">
                                    <option value="0" > --- </option>
                                    <option value="1" > <?=$AllAcc?> </option>
                                    <?php
                                        /*foreach($resDataACC as $rowDACC) :
                                                $Acc_ID1 		= $rowDACC->Acc_ID;
                                                $Account_Number = $rowDACC->Account_Number;
                                                $Account_Name	= $rowDACC->Account_Name;
                                                $isLast			= $rowDACC->isLast;*/
                                            ?>
                                                <!-- <option value="<?php echo $Account_Number; ?>" <?php if($isLast == 0) { ?> disabled <?php } ?>><?php echo "$Account_Number $Account_Name";?></option> -->
                                            <?php
                                        //endforeach;
                                    ?>
                                </select>
                            </div>
		                    <label for="inputName" class="col-sm-1 control-label"> s.d.</label>
		                    <div class="col-sm-5">
                                <select name="sellAccount2" id="sellAccount2" class="form-control select2" data-placeholder="&nbsp;<?php echo $AccName; ?>" style="width: 100%;">
                                    <option value="0" > --- </option>
                                    <?php
                                        /*foreach($resDataACC as $rowDACC) :
                                                $Acc_ID1 		= $rowDACC->Acc_ID;
                                                $Account_Number = $rowDACC->Account_Number;
                                                $Account_Name	= $rowDACC->Account_Name;
                                                $isLast			= $rowDACC->isLast;*/
                                            ?>
                                                <!-- <option value="<?php echo $Account_Number; ?>" <?php if($isLast == 0) { ?> disabled <?php } ?>><?php echo "$Account_Number $Account_Name";?></option> -->
                                            <?php
                                        //endforeach;
                                    ?>
                                </select>
                            </div>
		                </div>
		                <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Splnm ?></label>
                            <div class="col-sm-10">
                                <select name="SPLCODE[]" id="SPLCODE" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $Splnm; ?>" style="width: 100%;">
                                    <!-- <option value="0" > --- </option> -->
                                    <option value="1" > <?=$AllSpl?> </option>
                                    <?php
                                        foreach($qSupplier as $rowSPL) :
                                            $SPLCODE  = $rowSPL->SPLCODE;
                                            $SPLDESC  = $rowSPL->SPLDESC;
                                            ?>
                                                <option value="<?php echo $SPLCODE; ?>" ><?php echo "$SPLCODE - $SPLDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Category ?></label>
		                    <div class="col-sm-10">
		                    	<select name="CATEGREP" id="CATEGREP" class="form-control select2">
		                            <option value="0"> --- </option>
		                            <option hidden value="TB"><?php echo $trialbal; ?></option>
		                            <option value="GL"><?php echo $genledg; ?></option>
		                            <option value="DL"><?php echo $detledg; ?></option>
		                        </select>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate ?></label>
		                    <div class="col-sm-10">
		                    	<div class="input-group date">
		                        	<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		                        	<input type="text" name="Start_Date" class="form-control pull-left" id="datepicker" value="<?php echo $Start_Date; ?>" size="10" style="width:150px" >
		                        </div>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate ?></label>
		                    <div class="col-sm-10">
		                    	<div class="input-group date">
		                        	<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		                        	<input type="text" name="End_Date" class="form-control pull-left" id="datepicker1" value="<?php echo $Start_Date; ?>" size="10" style="width:150px" >
		                        </div>
		                    </div>
		                </div>
		            	<div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?></label>
		                    <div class="col-sm-10">
		                    	<label>
		                        <input type="radio" name="CFType" id="CFType1" value="1" checked /> 
		                        <?php echo $Summary ?> <br />
		                        <input type="radio" name="CFType" id="CFType2" value="2" /> 
		                  		<?php echo $Detail ?>                     </label>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType ?></label>
		                    <div class="col-sm-10">
		                    	<label>
		                        <input type="radio" name="viewType" value="0" class="flat-red" checked>
		                        <?php echo $WebViewer ?> &nbsp;&nbsp;&nbsp;
		                        <input type="radio" name="viewType" value="1" class="flat-red">
		                      	<?php echo $Excel ?>         </label>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                    	<button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>
		                    </div>
		                </div>
		            </form>
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
	    $('#datepicker').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker1').datepicker({
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

	    getAccNumber = function() {
	    	let PRJCODE     = $('#PRJCODE').val();

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo base_url("c_gl/c_r3p0r77l/getAccNumber") ?>",
	            dataType: "JSON",
	            data: {PRJCODE:PRJCODE},
	            success: function(data) {
	                let lnData  	= data.length;
	                let dataOpt 	= '';
	                let isDisable 	= '';

	                if(lnData != 0) dataOpt = '<option value="0"> --- </option><option value="1"><?php echo $AllAcc; ?></option>';

	                for(let i=0; i<lnData; i++) {
	                	if(data[i].isLast == 0) isDisable = 'disabled';
	                    //dataOpt += '<option value="'+data[i].Account_Number+'" '+isDisable+'>'+data[i].Account_Number+' '+data[i].Account_Name+'</option>';
	                    dataOpt += '<option value="'+data[i].ID+'" '+isDisable+'>'+data[i].Account_Number+' '+data[i].Account_Name+'</option>';
	                }

	                $('#sellAccount').html(dataOpt);
	            }
	        });
	    }

	    getAccNumber2 = function() {
	    	let PRJCODE     = $('#PRJCODE').val();
	    	let ACCID1  	= $('#sellAccount').val();
	    	var collID 		= PRJCODE+'~'+ACCID1;

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo base_url("c_gl/c_r3p0r77l/getAccNumber2") ?>",
	            dataType: "JSON",
	            data: {collID:collID},
	            success: function(data) {
	                let lnData  	= data.length;
	                let dataOpt 	= '';
	                let isDisable 	= '';

	                if(lnData != 0) dataOpt = '<option value="0"> --- </option></option>';

	                for(let i=0; i<lnData; i++) {
	                	if(data[i].isLast == 0) isDisable = 'disabled';
	                    dataOpt += '<option value="'+data[i].ID+'" '+isDisable+'>'+data[i].Account_Number+' '+data[i].Account_Name+'</option>';
	                }

	                $('#sellAccount2').html(dataOpt);
	            }
	        });
	    }

	    $("#PRJCODE").on('change', function(){
	        let select = $(this).select2();
	        console.log(select);
	        let val = $(this).val();
	        if(val !== null)
	        {
	            if(val == 1)
	            {
	                $(this).select2({
	                    maximumSelectionLength: 1
	                });

	                $("#PRJCODE option:nth-child(1)").prop("disabled", false);
	            }
	            else
	            {
	                $(this).select2({
	                    maximumSelectionLength: 0
	                });

	                $("#PRJCODE option:nth-child(1)").prop("disabled", true);
	            }
	        }
	        else
	        {
	            $(this).select2({
	                maximumSelectionLength: 0
	            });

	            $("#PRJCODE option:nth-child(1)").prop("disabled", false);
	        }

	        getAccNumber();
	    });

	    $('#CATEGREP').select2({templateResult: hideSelect2Option});

	    $("#sellAccount").on('change', function(){
	        let select = $(this).select2();
	        console.log(select);
	        let val = $(this).val();
	        if(val !== null)
	        {
	            if(val == 1)
	            {
	                $(this).select2({
	                    maximumSelectionLength: 1
	                });

	                $("#sellAccount option:nth-child(1)").prop("disabled", false);
	            }
	            else
	            {
	                $(this).select2({
	                    maximumSelectionLength: 0
	                });

	                $("#sellAccount option:nth-child(1)").prop("disabled", true);
	            }
	        }
	        else
	        {
	            $(this).select2({
	                maximumSelectionLength: 0
	            });

	            $("#sellAccount option:nth-child(1)").prop("disabled", false);
	        }
	        getAccNumber2();
	    });

	    $("#SPLCODE").on('change', function(){
	        let select = $(this).select2();
	        console.log(select);
	        let val = $(this).val();
	        if(val !== null)
	        {
	            if(val == 1)
	            {
	                $(this).select2({
	                    maximumSelectionLength: 1
	                });

	                $("#SPLCODE option:nth-child(1)").prop("disabled", false);
	            }
	            else
	            {
	                $(this).select2({
	                    maximumSelectionLength: 0
	                });

	                $("#SPLCODE option:nth-child(1)").prop("disabled", true);
	            }
	        }
	        else
	        {
	            $(this).select2({
	                maximumSelectionLength: 0
	            });

	            $("#SPLCODE option:nth-child(1)").prop("disabled", false);
	        }
	    });

  	});

	/*$(document).ready(function() {
	  	$('#example').select2();

	  	$(".select2-search__field").on("keydown", function(e) {
	  		selAcc	= document.getElementById('sellAccount').value;
	    	console.log('selAcc = '+selAcc)
	  	});
	});*/

  	function hideSelect2Option(data, container) 
  	{
	    if(data.element) 
	    {
	        $(container).addClass($(data.element).attr("class"));
	        $(container).attr('hidden',$(data.element).attr("hidden"));
	    }
	    return data.text;
	}
  
  	function selPRJ(thisVal)
	{
		document.getElementById('PRJCODEX').value = thisVal;
		document.frmsrch.submitSrch.click();	
	}
	
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

	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		PRJCODE	= document.getElementById('PRJCODE').value;
		if(PRJCODE == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon:"warning",
			});
			return false;
		}

		sellAccount	= document.getElementById('sellAccount').value;
		if(sellAccount == 0 || sellAccount == '')
		{
			swal('<?php echo $alert2; ?>',
			{
				icon:"warning",
			});
			return false;
		}

		SPLCODE = document.getElementById('SPLCODE').value;
		if(SPLCODE == 0 || SPLCODE == '')
        {
            swal ( "" ,  "<?php echo $alert4; ?>" ,  "warning" )
            return false;
        }

		// packageelements	= document.getElementById('packageelementsCB').value;
		// if(packageelements == '')
		// {
		// 	swal('<?php //echo $alert2; ?>',
		// 	{
		// 		icon:"warning",
		// 	});
		// 	return false;
		// }

		CATEGREP	= document.getElementById('CATEGREP').value;
		if(CATEGREP == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon:"warning",
			});
			return false;
		}
		title = 'Select Item';
		w = 1200;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	var url = "<?php echo base_url().'index.php/c_itmng/uploadtxt/export_txt';?>";
	function exporttoexcel()
	{
		window.open(url,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
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
	