<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Oktober 2018
	* File Name	= v_prodstep_form.php
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
	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
	
	$PRODS_NUM			= '';
	$PRODS_CODE			= '';
	$PRODS_STEP 		= '';
	$PRODS_NAME 		= '';
	$PRODS_DESC			= '';
	$PRODS_STAT			= 0;
	$PRODS_LAST 		= 0;
	$PRODS_ORDER 		= 0;
	
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

	$sql 		= "tbl_prodstep";
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
	$PRODS_NUM		= date('ymdHis');
	$PRODS_CODE		= $DocNumber;
	$PRODS_ORDER	= $lastPatt;
	if($PRODS_ORDER == 1)
		$PRODS_STEP	= 'ONE';
	elseif($PRODS_ORDER == 2)
		$PRODS_STEP	= 'TWO';
	elseif($PRODS_ORDER == 3)
		$PRODS_STEP	= 'THR';
	elseif($PRODS_ORDER == 4)
		$PRODS_STEP	= 'FOU';
	elseif($PRODS_ORDER == 5)
		$PRODS_STEP	= 'FIV';
	elseif($PRODS_ORDER == 6)
		$PRODS_STEP	= 'SIX';
	elseif($PRODS_ORDER == 7)
		$PRODS_STEP	= 'SEV';
	elseif($PRODS_ORDER == 8)
		$PRODS_STEP	= 'EIG';
	elseif($PRODS_ORDER == 9)
		$PRODS_STEP	= 'NIN';
	elseif($PRODS_ORDER == 10)
		$PRODS_STEP	= 'TEN';
	elseif($PRODS_ORDER == 11)
		$PRODS_STEP	= 'ELV';
	elseif($PRODS_ORDER == 12)
		$PRODS_STEP	= 'TWL';
	elseif($PRODS_ORDER == 13)
		$PRODS_STEP	= 'THD';
	elseif($PRODS_ORDER == 14)
		$PRODS_STEP	= 'FOD';
	elseif($PRODS_ORDER == 15)
		$PRODS_STEP	= 'FID';
	else
		$PRODS_STEP	= 'MAX';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_prodstep~$Pattern_Length";
	$dataTarget		= "PRODS_CODE";
}
else
{
	$isSetDocNo 	= 1;
	$PRODS_NUM 		= $default['PRODS_NUM'];
	$PRODS_CODE 	= $default['PRODS_CODE'];
	$PRODS_STEP 	= $default['PRODS_STEP'];
	$PRODS_NAME 	= $default['PRODS_NAME'];
	$PRODS_DESC	 	= $default['PRODS_DESC'];
	$PRODS_STAT 	= $default['PRODS_STAT'];
	$PRODS_LAST 	= $default['PRODS_LAST'];
	$PRODS_ORDER 	= $default['PRODS_ORDER'];
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
			if($TranslCode == 'SectName')$SectName = $LangTransl;
			if($TranslCode == 'Time')$Time = $LangTransl;
			if($TranslCode == 'StdCost')$StdCost = $LangTransl;
			if($TranslCode == 'Tools')$Tools = $LangTransl;
			if($TranslCode == 'Cost')$Cost = $LangTransl;

			if($TranslCode == 'LastStep')$LastStep = $LangTransl;
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
		</section>

		<section class="content">
		    <div class="row">
		        <div class="col-md-12">
		            <div class="box box-primary">
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
		                                <input type="text" class="form-control" name="PRODS_NUM1" id="PRODS_NUM1" value="<?php echo $PRODS_NUM; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="PRODS_NUM" id="PRODS_NUM" size="30" value="<?php echo $PRODS_NUM; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="PRODS_CODE" id="PRODS_CODE" value="<?php echo $PRODS_CODE; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SectName; ?> </label>
		                          	<div class="col-sm-10">
		                            	<input type="text" class="form-control" name="PRODS_NAME" id="PRODS_NAME" value="<?php echo $PRODS_NAME; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $INVStep ?> </label>
		                          	<div class="col-sm-10">
		                                <select name="PRODS_ORDER" id="PRODS_ORDER" class="form-control select2">
		                                	<?php
		                                		for($i=1;$i<=15;$i++)
		                                		{
		                                			$sqlCSTP	= "tbl_prodstep WHERE PRODS_ORDER = $i AND PRODS_STAT = '1' AND PRODS_ORDER != $PRODS_ORDER";
													$resCSTP 	= $this->db->count_all($sqlCSTP);
		                                			?>
		                                			<option value="<?php echo $i; ?>"<?php if($PRODS_ORDER == 1) { ?> selected <?php } if($resCSTP > 0) { ?> disabled <?php } ?>><?php echo $i; ?></option>
		                                			<?php
		                                		}
		                                	?>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
		                          	<div class="col-sm-10">
		                                <textarea class="form-control" name="PRODS_DESC"  id="PRODS_DESC" style="height:70px"><?php echo $PRODS_DESC; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $LastStep ?> ?</label>
		                          	<div class="col-sm-10">
		                                <select name="PRODS_LAST" id="PRODS_LAST" class="form-control select2">
		                                	<option value="0">---</option>
		                                    <option value="0"<?php if($PRODS_LAST == 0) { ?> selected <?php } ?>>No</option>
		                                    <option value="1"<?php if($PRODS_LAST == 1) { ?> selected <?php } ?>>Yes</option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-10">
		                                <select name="PRODS_STAT" id="PRODS_STAT" class="form-control select2">
		                                    <option value="1"<?php if($PRODS_STAT == 1) { ?> selected <?php } ?>>Aktif</option>
		                                    <option value="0"<?php if($PRODS_STAT == 0) { ?> selected <?php } ?>>Non Aktif</option>
		                                </select>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
										<button class="btn btn-primary">
										<i class="fa fa-save"></i></button>&nbsp;
										<?php 
											$backURL	= site_url('c_setting/c_pr0ds73p');
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
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      autoclose: true
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
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

	function checkInp(thisval)
	{
		PRODS_CODE	= document.getElementById('PRODS_CODE').value;
		PRODS_NAME	= document.getElementById('PRODS_NAME').value;

		if(PRODS_CODE == "")
        {
            swal('<?php echo $Code; ?> can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#PRODS_CODE').focus();
	        });
	        return false;
        }

		if(PRODS_NAME == "")
        {
            swal('<?php echo $SectName; ?> can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#PRODS_NAME').focus();
	        });
	        return false;
        }
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