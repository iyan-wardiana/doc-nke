<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Oktober 2018
	* File Name	= v_machine_form.php
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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
	$MCN_NUM		= '';
	$MCN_CODE		= '';
	$MCN_NAME 		= '';
	$MCN_DESC		= '';
	$MCN_ITMCAL		= 1;
	$MCN_STAT		= 1;
	
	foreach($viewDocPattern as $row) :
		$Pattern_Code 			= $row->Pattern_Code;
		$Pattern_Position 		= $row->Pattern_Position;
		$Pattern_YearAktive 	= $row->Pattern_YearAktive;
		$Pattern_MonthAktive 	= $row->Pattern_MonthAktive;
		$Pattern_DateAktive 	= $row->Pattern_DateAktive;
		$Pattern_Length 		= $row->Pattern_Length;
		$useYear 				= $row->useYear;
		$useMonth 				= $row->useMonth;
		$useDate 				= $row->useDate;
	endforeach;
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "XXX";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}

	$sql 		= "tbl_machine";
	$result 	= $this->db->count_all($sql);
	
	$myMax 		= $result+1;
		
	$lastPatt 	= $myMax;
	$lastPatt1 	= $myMax;
	$len = strlen($lastPatt);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";else $nol="";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";else $nol="";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";else $nol="";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";else $nol="";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";else $nol="";
	}
	
	$lastPatt = $nol.$lastPatt;

	$DocNumber		= "$Pattern_Code$lastPatt";
	$MCN_NUM		= date('ymdHis');
	$MCN_CODE		= $DocNumber;
	$MCN_ITMCODE	= '';
	$MCN_PSTEP		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_machine~$Pattern_Length";
	$dataTarget		= "MCN_CODE";
}
else
{
	$isSetDocNo 	= 1;
	$MCN_NUM 		= $default['MCN_NUM'];
	$MCN_CODE 		= $default['MCN_CODE'];
	$MCN_NAME 		= $default['MCN_NAME'];
	$MCN_DESC	 	= $default['MCN_DESC'];
	$MCN_ITMCODE 	= $default['MCN_ITMCODE'];
	$MCN_PSTEP 		= $default['MCN_PSTEP'];
	$MCN_ITMCAL 	= $default['MCN_ITMCAL'];
	$MCN_STAT 		= $default['MCN_STAT'];
}

$secGenCode	= base_url().'index.php/c_production/c_b0fm47/genCode/'; // Generate Code
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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'INVStep')$INVStep = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ToolsName')$ToolsName = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'machineNm')$machineNm = $LangTransl;
			if($TranslCode == 'MachineNumber')$MachineNumber = $LangTransl;
			if($TranslCode == 'calculation')$calculation = $LangTransl;
			if($TranslCode == 'setToItm')$setToItm = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'LastStep')$LastStep = $LangTransl;
			if($TranslCode == 'setToStep')$setToStep = $LangTransl;
			if($TranslCode == 'SectName')$SectName = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= 'Jumlah pemesanan tidak boleh kosong';
			$alert2		= 'Silahkan pilih nama supplier';
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert1		= 'Qty order can not be empty';
			$alert2		= 'Please select a supplier name';
			$isManual	= "Check to manual code.";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/production.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
		    <small><?php //echo $h2_title; ?></small>  </h1>
		  <?php /*?><ol class="breadcrumb">
		    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    <li><a href="#">Tables</a></li>
		    <li class="active">Data tables</li>
		  </ol><?php */?>
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
		                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
								<?php if($isSetDocNo == 0) { ?>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                            <div class="col-sm-10">
		                                <div class="alert alert-danger alert-dismissible">
		                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                    <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
		                                    <?php echo $docalert2; ?>
		                                </div>
		                            </div>
		                        </div>
		                        <?php } ?>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="MCN_NUM1" id="MCN_NUM1" value="<?php echo $MCN_NUM; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="MCN_NUM" id="MCN_NUM" size="30" value="<?php echo $MCN_NUM; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $MachineNumber; ?> </label>
		                          	<div class="col-sm-10">
		                            	<input type="text" class="form-control" name="MCN_CODE" id="MCN_CODE" value="<?php echo $MCN_CODE; ?>">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $machineNm; ?> </label>
		                          	<div class="col-sm-10">
		                            	<input type="text" class="form-control" name="MCN_NAME" id="MCN_NAME" value="<?php echo $MCN_NAME; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
		                          	<div class="col-sm-10">
		                                <textarea class="form-control" name="MCN_DESC"  id="MCN_DESC" style="height:70px"><?php echo $MCN_DESC; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $calculation ?> ?</label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:120px; text-align:right" name="MCN_ITMCAL1" id="MCN_ITMCAL1" value="<?php echo number_format($MCN_ITMCAL,2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cITMCAL(this);" >
		                                <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:120px; text-align:right" name="MCN_ITMCAL" id="MCN_ITMCAL" value="<?php echo $MCN_ITMCAL; ?>" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                        </div>
		                        <script type="text/javascript">
		                        	function cITMCAL(thisVal)
		                        	{
		                        		var ITMCAL 	= thisVal.value;
		                        		document.getElementById('MCN_ITMCAL').value 	= ITMCAL;
		                        		document.getElementById('MCN_ITMCAL1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(ITMCAL),2));
		                        	}
		                        </script>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $setToItm ?></label>
		                          	<div class="col-sm-10">
		                                <select name="MCN_ITMCODE[]" id="MCN_ITMCODE" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ItemName; ?>">
				                        	<option value="">--- None ---</option>
											<?php
				                                $Disabled_1	= 0;
				                                $sqlITM		= "SELECT DISTINCT PRJCODE, ITM_CODE, ITM_NAME 
				                                				FROM tbl_item WHERE PRJCODE = '$PRJCODE' ORDER BY ITM_NAME ASC";
				                                $resITM		= $this->db->query($sqlITM)->result();
				                                foreach($resITM as $row_1) :
				                                    $PRJCODE	= $row_1->PRJCODE;
				                                    $ITM_CODE	= $row_1->ITM_CODE;
				                                    $ITM_NAME	= $row_1->ITM_NAME;

				                                    $ITMExplode = explode('~', $MCN_ITMCODE);
													$ITM_CODE1	= '';
													$SELECTED	= 0;
													foreach($ITMExplode as $i => $key)
													{
														$ITM_CODE1	= $key;
														if($ITM_CODE == $ITM_CODE1)
														{
															$SELECTED	= 1;
														}
													}
				                                    ?>
				                                    <option value="<?php echo "$ITM_CODE"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?> title="<?php echo $ITM_NAME; ?>">
				                                        <?php echo "$ITM_CODE : $ITM_NAME"; ?>
				                                    </option>
				                                    <?php
				                                endforeach;
				                            ?>
				                        </select>
		                            </div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $setToStep ?></label>
		                          	<div class="col-sm-10">
		                                <select name="MCN_PSTEP[]" id="MCN_PSTEP" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SectName; ?>">
				                        	<option value="0"> --- </option>
											<?php
				                                $Disabled_1	= 0;
				                                $sqlPRD		= "SELECT DISTINCT PRODS_STEP, PRODS_NAME 
				                                				FROM tbl_prodstep ORDER BY PRODS_NAME ASC";
				                                $resPRD		= $this->db->query($sqlPRD)->result();
				                                foreach($resPRD as $row_1) :
				                                    $PRODS_STEP	= $row_1->PRODS_STEP;
				                                    $PRODS_NAME	= $row_1->PRODS_NAME;

				                                    $ITMExplode = explode('~', $MCN_PSTEP);
													$PRD_STEP1	= '';
													$SELECTED	= 0;
													foreach($ITMExplode as $i => $key)
													{
														$PRD_STEP1	= $key;
														if($PRODS_STEP == $PRD_STEP1)
														{
															$SELECTED	= 1;
														}
													}
				                                    ?>
				                                    <option value="<?php echo "$PRODS_STEP"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?> title="<?php echo $PRODS_NAME; ?>">
				                                        <?php echo "$PRODS_STEP : $PRODS_NAME"; ?>
				                                    </option>
				                                    <?php
				                                endforeach;
				                            ?>
				                        </select>
		                            </div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-10">
		                                <select name="MCN_STAT" id="MCN_STAT" class="form-control" style="max-width:120px">
		                                    <option value="1"<?php if($MCN_STAT == 1) { ?> selected <?php } ?>>Aktif</option>
		                                    <option value="0"<?php if($MCN_STAT == 0) { ?> selected <?php } ?>>Non Aktif</option>
		                                </select>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
										<button class="btn btn-primary">
										<i class="fa fa-save"></i></button>&nbsp;
										<?php 
											$backURL	= site_url('c_setting/c_m4ch1n');
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
	$(function ()
	{
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

	function checkInp()
	{
		MCN_CODE	= document.getElementById('MCN_CODE').value;
		MCN_NAME	= document.getElementById('MCN_NAME').value;
		MCN_ITMCAL	= document.getElementById('MCN_ITMCAL').value;
		MCN_PSTEP	= document.getElementById('MCN_PSTEP').value;
		
		if(MCN_CODE == "")
        {
            swal('<?php echo $MachineNumber; ?> can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#MCN_CODE').focus();
	        });
	        return false;
        }

		if(MCN_NAME == "")
        {
            swal('<?php echo $machineNm; ?> can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#MCN_NAME').focus();
	        });
	        return false;
        }

		if(MCN_ITMCAL == 0)
        {
            swal('<?php echo $calculation; ?> machine can not be zero',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#MCN_ITMCAL1').focus();
	        });
	        return false;
        }

		if(MCN_PSTEP == 0)
        {
            swal('<?php echo $setToStep; ?> can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#MCN_PSTEP').focus();
	        });
	        return false;
        }

		return false;
	}
	
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