<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Juni 2021
 * File Name	= v_partners.php
 * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

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

$SPLCODE		= $default['SPLCODE'];
$SPLDESC		= $default['SPLDESC'];
$SPLCAT			= $default['SPLCAT'];
if(isset($_POST['SPLCAT1']))
{
	$SPLCAT		= $_POST['SPLCAT1'];
}
$SPLADD1		= $default['SPLADD1'];
$SPLKOTA		= $default['SPLKOTA'];
$SPLNPWP		= $default['SPLNPWP'];
$SPLPERS		= $default['SPLPERS'];
$SPLTELP		= $default['SPLTELP'];
$SPLMAIL		= $default['SPLMAIL'];
$SPLNOREK		= $default['SPLNOREK'];
$SPLSCOPE		= $default['SPLSCOPE'];
$SPLNMREK		= $default['SPLNMREK'];
$SPLBANK		= $default['SPLBANK'];
$SPLOTHR		= $default['SPLOTHR'];
$SPLOTHR2		= $default['SPLOTHR2'];
$SPLTOP			= $default['SPLTOP'];
$SPLTOPD		= $default['SPLTOPD'];
$SPLSTAT		= $default['SPLSTAT'];
$Patt_Number	= $default['Patt_Number'];

$sqlApp 		= "SELECT PRJNAME, PRJLOCT, PRJADD, PRJTELP, PRJFAX, PRJMAIL, PRJ_IMGNAME FROM tbl_project WHERE isHO = 1";
$resApp 		= $this->db->query($sqlApp)->result();
foreach($resApp as $rowApp) :
    $PRJNAME  	= $rowApp->PRJNAME;
    $PRJLOCT  	= $rowApp->PRJLOCT;
    $PRJADD  	= $rowApp->PRJADD;
    $PRJTELP  	= $rowApp->PRJTELP;
    $PRJFAX  	= $rowApp->PRJFAX;
    $PRJMAIL  	= $rowApp->PRJMAIL;
    $PRJIMGNAME = $rowApp->PRJ_IMGNAME;
endforeach;
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

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
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
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'City')$City = $LangTransl;
			if($TranslCode == 'ContactPersonName')$ContactPersonName = $LangTransl;
			if($TranslCode == 'Email')$Email = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'FuncDesc')$FuncDesc = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'Scope')$Scope = $LangTransl;
			if($TranslCode == 'RekNo')$RekNo = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'BankName')$BankName = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PayTenor')$PayTenor = $LangTransl;
			if($TranslCode == 'Day')$Day = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'venCatEmpty')$venCatEmpty = $LangTransl;
			if($TranslCode == 'suplNmEmpty')$suplNmEmpty = $LangTransl;
			if($TranslCode == 'payInfonOth')$payInfonOth = $LangTransl;
			if($TranslCode == 'totPurch')$totPurch = $LangTransl;
			if($TranslCode == 'totPaid')$totPaid = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'NotPayment')$NotPayment = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title	= "Supplier";
			$h2_title	= "Pembelian";
		}
		else
		{
			$h1_title	= "Supplier";
			$h2_title	= "Purchase";
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header" style="display: none;">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/supplier_add.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $h2_title; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<?php
			$DEB_T 	= 0;
			$DEB_P 	= 0;
			$sTOT	= "SELECT SUM(INV_AMOUNT) AS DEB_T, SUM(INV_AMOUNT_PAID) AS DEB_P FROM tbl_pinv_header WHERE SPLCODE = '$SPLCODE'";
			$qTOT	= $this->db->query($sTOT)->result();
			foreach($qTOT as $rTOT) :
				$DEB_T	= $rTOT->DEB_T;
				$DEB_P	= $rTOT->DEB_P;
			endforeach;

			$DEB_R 		= $DEB_T - $DEB_P;

			$DEB_TP 	= $DEB_T;
			if($DEB_T == 0)
				$DEB_TP	= 1;

	        $DBT_PERC	= number_format($DEB_R / $DEB_TP * 100, 2);
	        $DBP_PERC	= number_format($DEB_P / $DEB_TP * 100, 2);

            // ----------- HUTANG
				if($DEB_T < 1000)									// Ratusan
	            {
	            	$DEB_TV = number_format($DEB_T / 1, 2);
	            	$DBTCOD	= "";
	            }
				elseif($DEB_T < 1000000)							// Juta per Seribu
	            {
	            	$DEB_TV = number_format($DEB_T / 1000, 2);
	            	$DBTCOD	= " RB";
	            }
				elseif($DEB_T < 1000000000)							// Miliar per Sejuta
	            {
	            	$DEB_TV = number_format($DEB_T / 1000000, 2);
	            	$DBTCOD	= " JT";
	            }
	            elseif($DEB_T < 1000000000000)						// Triliun per Semiliar
	            {
	            	$DEB_TV = number_format($DEB_T / 1000000000, 2);
	            	$DBTCOD	= " M";
	            }
	            else
	            {
	            	$DEB_TV = number_format($DEB_T / 1000000000000, 2);
	            	$DBTCOD	= " T";
	            }
	            $DBT_V 		= $DEB_TV.$DBTCOD;

            // ----------- DIBAYAR
				if($DEB_P < 1000)									// Ratusan
	            {
	            	$DEB_PV = number_format($DEB_P / 1, 2);
	            	$DBPCOD	= "";
	            }
				elseif($DEB_P < 1000000)							// Juta per Seribu
	            {
	            	$DEB_PV = number_format($DEB_P / 1000, 2);
	            	$DBPCOD	= " RB";
	            }
	            elseif($DEB_P < 1000000000)							// Miliar per Sejuta
	            {
	            	$DEB_PV = number_format($DEB_P / 1000000, 2);
	            	$DBPCOD	= " JT";
	            }
	            elseif($DEB_P < 1000000000000)						// Triliun per Semiliar
	            {
	            	$DEB_PV = number_format($DEB_P / 1000000000, 2);
	            	$DBPCOD	= " M";
	            }
	            else
	            {
	            	$DEB_PV = number_format($DEB_P / 1000000000000, 2);
	            	$DBPCOD	= " T";
	            }
	            $DBP_V 		= $DEB_PV.$DBPCOD;

            // ----------- SISA
	            $PM 		= "";
	            if($DEB_R < 0)
	            {
	            	$PM 	= "-";
	            	$DEB_R 	= abs($DEB_R);
	            }

				if($DEB_R < 1000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1, 2);
	            	$DBRCOD	= "";
	            }
				elseif($DEB_R < 1000000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1000, 2);
	            	$DBRCOD	= " RB";
	            }
	            elseif($DEB_R < 1000000000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1000000, 2);
	            	$DBRCOD	= " JT";
	            }
	            elseif($DEB_R < 1000000000000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1000000000, 2);
	            	$DBRCOD	= " M";
	            }
	            else
	            {
	            	$DEB_RV = number_format($DEB_R / 1000000000000, 2);
	            	$DBRCOD	= " T";
	            }
	            $DBR_V 		= $PM.$DEB_RV.$DBRCOD;
		?>
		<section class="content">
			<div class="row">
				<div class="col-md-6 col-sm-3 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-blue-gradient"><i class="glyphicon glyphicon-user"></i></span>
						<div class="info-box-content">
							<span class="info-box-text" style="font-weight: bold; font-size: 20px"><?php echo $PRJNAME; ?></span>
							<span class="info-box-text"><?php echo $PRJLOCT; ?></span>
							<span class="progress-description"><?php echo $PRJADD; ?></span>
							<span class="progress-description"><?php echo $SPLTELP; ?></span>
							<span class="progress-description"><?php echo $SPLMAIL; ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12" style="display: none;">
					<div class="info-box bg-red-gradient">
						<span class="info-box-icon"><i class="glyphicon glyphicon-th-list"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $totPurch; ?></span>
							<span class="info-box-number"><?php echo $DBT_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$DBP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								<?=$DBT_PERC?> % <?php echo $NotPayment; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-green-gradient">
						<span class="info-box-icon"><i class="glyphicon glyphicon-ok"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $totPaid; ?></span>
							<span class="info-box-number"><?php echo $DBP_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$DBP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								<?=$DBP_PERC?> % <?php echo $Paid; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-yellow-gradient">
						<span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $Remain; ?></span>
							<span class="info-box-number"><?php echo $DBR_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$DBP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								Total <?php echo $NotPayment; ?>
							</span>
						</div>
					</div>
				</div>
			</div>

		    <div class="row">
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                    <input type="text" name="SPLCAT1" id="SPLCAT1" value="<?php echo $SPLCAT; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
                <?php
                    $urlGetData	= base_url().'index.php/c_purchase/c_v3N/gLastCd/';
                ?>
		    </div>
        	<?php
        		$DefID 		= $this->session->userdata['Emp_ID'];
				$act_lnk 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
	function checkInp()
	{
		SPLCAT = document.getElementById('SPLCAT').value;
		if(SPLCAT == 0)
		{
			swal('<?php echo $venCatEmpty; ?>',
			{
				icon: "warning",
			});
			return false;			
		}

		SPLDESC = document.getElementById('SPLDESC').value;
		if(SPLDESC == '')
		{
			swal('<?php echo $suplNmEmpty; ?>',
			{
				icon: "warning",
			});
			return false;			
		}
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