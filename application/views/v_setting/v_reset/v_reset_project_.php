<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 29 September 2018
	* File Name	= v_reset_project.php
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
	
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

/*$sqlPRJC	= "tbl_project A 
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";*/
$sqlPRJC	= "tbl_project A";
$resPRJC	= $this->db->count_all($sqlPRJC);

/*$sqlPRJ		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, 
					A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";*/
$sqlPRJ		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, 
					A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A
				ORDER BY A.PRJCODE";
$resPRJ		= $this->db->query($sqlPRJ)->result();

$sqlPRJHO	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
$resPRJHO	= $this->db->query($sqlPRJHO)->result();
foreach ($resPRJHO as $key => $value) {
	$PRJCODEHO	= $value->PRJCODE;
}

$time 	= microtime();
$time 	= explode(' ', $time);
$time 	= $time[1] + $time[0];
$start 	= $time;

$RESFULL	= 0;
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

	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
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
				
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Process')$Process = $LangTransl;
			if($TranslCode == 'sureReset')$sureReset = $LangTransl;
			if($TranslCode == 'resType')$resType = $LangTransl;
			if($TranslCode == 'resTrx')$resTrx = $LangTransl;
			if($TranslCode == 'resAll')$resAll = $LangTransl;
			if($TranslCode == 'yesDel')$yesDel = $LangTransl;
			if($TranslCode == 'canReset')$canReset = $LangTransl;
		endforeach;
		
		$urllogout = site_url('__l1y/logout');
		$isLoadDone_1	= 1;

		if($LangID == 'IND')
		{
			$alert1		= "Silahkan pilih file excel yang akan Anda import.";
			$alert2		= "Proses ini akan me-reset data material sebelumnya. Anda Yakin?";
			$alert3		= "Silahan pilih proyek yang akan direset.";
			$alert4		= "Proses ini akan me-reset seluruh data transaksi pada proyek yang Anda pilih. Anda Yakin?";
			$AllTRX 	= "Semua Transaksi";
			$ExcTRX 	= "Kecuali BoQ dan WBS";
			$Reset 		= "Atur Ulang";
			$AMD 		= "amandemen / amd ...";
			$ASTEXP		= "pembiayaan alat / tls_exp ...";
			$ASTMNT		= "perawatan alat / tls_mnt ...";
			$ASTPRD		= "produksi alat / tls_product ...";
			$ASTRCOST	= "pembiayaan alat / tls_cost ...";
			$ASTRJOB	= "pekerjaan alat / tls_jobl ...";
			$ASTUSG		= "penggunaan alat / tls_usg ...";
			$ASTCONC	= "rangkuman alat / tls_concl ...";
			$ASTEXP		= "pembebanan alat / tls_exp ...";
			$BP			= "pembayaran keuangan / fin_paymnt ...";
			$BR			= "peneriman keuangan / fin_income ...";
			$DASH		= "data dashboard / dt_dash ...";
			$DTRX		= "transaksi dashboard / trx_dash ...";
			$DPH		= "uang muka / dp ...";
			$FMON		= "monitoring keuangan / fin_monit ...";
			$FTRACK		= "pelacakan keuangan / fin_track ...";
			$IR			= "penerimaan material / ir ...";
			$ITMHIST	= "jejak material / itm_hist ...";
			$MCCONC		= "rangkuman monthly certficate / rmc ...";
			$MC			= "monthly certficate / mc ...";
			$OPN		= "opname / opn ...";
			$PINV		= "faktur pembelian / pinv ...";
			$PO 		= "pemesanan pembelian / po ...";
			$PR 		= "permintaan pemesanan / pr ...";
			$LNR		= "laba rugi / lnr ...";
			$PRJINV		= "faktur klaim / prjinv ...";
			$PRJPROG	= "progres proyek / prj_prog ...";
			$SIC		= "sertifikat si / sic ...";
			$SI			= "site instruction / si ...";
			$TRXC		= "penghitungan transaksi / trx_count ...";
			$TTK		= "tanda terima kwitansi / ttk ...";
			$UM			= "penggunaan material / um ...";
			$WO			= "surat perintah kerja / spk ...";
			$WOREQ		= "permintaan surat perintah kerja alat / spkreq_tls ...";
			$SPEC		= "khusus / spc ...";
			$FPA		= "permintaan alat / tls_req ...";
			$FUELUSG	= "penggunaan bbm / fuel_usg ...";
			$TSF		= "transfer tahapan / section transfer ...";
			$AUR		= "perm. penggunaan alat / asset usage req. ...";
			$MCG		= "mc group / mc group ...";
			$RET		= "pengembalian / return ...";
			$PRJCONC	= "kesimpulan proyek / project conclusion ...";
			$PRJREAL	= "grafik progres / chart progress ...";
			$TRAILT		= "trail tracker / trail tracker ...";
			$BOM		= "bill off material / billof_material ...";
			$CCAL		= "kalkulasi biaya / cost_calculation ...";
			$ITMADJ		= "penyesuaian item / adjust_item ...";
			$ITMTSF		= "pemindahan material / item_transf ...";
			$ITMWH		= "stok item gudang / item_wh ...";
			$JO			= "perintah kerja / job_order ...";
			$MR			= "permint. mtr prod / material_req ...";
			$OFFO		= "penawaran / offer_letter ...";
			$SIN		= "faktur penjualan / sales_inv ...";
			$SN			= "pengiriman / shipm_notes ...";
			$SO			= "dok. penjualan / sales_ord ...";
			$STF		= "proses produksi / section_tsf ...";
			$JOCON		= "rekapan jo / jo._concl ...";
			$JOSTF		= "tsf detil jo / jo._stfdet ...";
			$SRRET		= "pengemb. penjualan / sr._ret ...";
			$QRCDET		= "pengkodean qr / qrc._ret ...";
			$Others		= "lainnya / oth._ret ...";
		}
		else
		{
			$alert1		= "Please select an excel file that will you uploaded.";
			$alert2		= "This process will reset the previous material data. Are You Sure?";
			$alert3		= "Please select a Project that will you reset.";
			$alert4		= "This process will reset all transaction data on the project you choose. Are you sure?";
			$AllTRX 	= "All Transaction";
			$ExcTRX 	= "Exc. BoQ and WBS";
			$Reset 		= "Reset";
			$AMD 		= "amandement / amd ...";
			$ASTEXP		= "tool expenses / tls_exp ...";
			$ASTMNT		= "tool maintenance / tls_mnt ...";
			$ASTPRD		= "tool production / tls_product ...";
			$ASTRCOST	= "tool cost / tls_cost ...";
			$ASTRJOB	= "tool joblist / tls_jobl ...";
			$ASTUSG		= "tool usage / tls_usg ...";
			$ASTCONC	= "tool conclusion / tls_concl ...";
			$ASTEXP		= "tool expenses / tls_exp ...";
			$BP			= "bank payment / fin_paymnt ...";
			$BR			= "bank receipt / fin_income ...";
			$DASH		= "data dashboard / dt_dash ...";
			$DTRX		= "dashboard transaction / trx_dash ...";
			$DPH		= "down payment / dp ...";
			$FMON		= "finance monitoring / fin_monit ...";
			$FTRACK		= "finance tracking / fin_track ...";
			$IR			= "item receipt / ir ...";
			$ITMHIST	= "item history / itm_hist ...";
			$MCCONC		= "mc conclusion / rmc ...";
			$MC			= "monthly certficate / mc ...";
			$OPN		= "opname / opn ...";
			$PINV		= "purchase invoice / pinv ...";
			$PO 		= "purchase order / po ...";
			$PR 		= "purchase request / pr ...";
			$LNR		= "profit and loss / lnr ...";
			$PRJINV		= "invoice claim / prjinv ...";
			$PRJPROG	= "project progress / prj_prog ...";
			$SIC		= "si sertificate / sic ...";
			$SI			= "site instruction / si ...";
			$TRXC		= "transaction count / trx_count ...";
			$TTK		= "document receipt / ttk ...";
			$UM			= "use material / um ...";
			$WO			= "work order / spk ...";
			$WOREQ		= "work order req tool / spkreq_tls ...";
			$SPEC		= "special / spc ...";
			$FPA		= "tool request / tls_req ...";
			$FUELUSG	= "fuel usage / fuel_usg ...";
			$TSF		= "section transfer / section transfer ...";
			$AUR		= "asset usage req. / asset usage req. ...";
			$MCG		= "mc group / mc group ...";
			$RET		= "return / return ...";
			$PRJCONC	= "project conclusion / project conclusion ...";
			$PRJREAL	= "chart progress / chart progress ...";
			$TRAILT		= "trail tracker / trail tracker ...";
			$BOM		= "bill off material / billof_material ...";
			$CCAL		= "cost_calculation / cost_cal ...";
			$ITMADJ		= "adjust_item / adj_item ...";
			$ITMTSF		= "item_transf / item_tsf ...";
			$ITMWH		= "item_wh / item_wh ...";
			$JO			= "job_order / job_order ...";
			$MR			= "material_req / matr_req ...";
			$OFFO		= "offer_letter / off_lett ...";
			$SIN		= "sales_inv / sls_inv ...";
			$SN			= "shipm_notes / shp_nts ...";
			$SO			= "sales_ord / sls_ord ...";
			$STF		= "section_tsf / sec._tsf ...";
			$JOCON		= "jo_concl / jo._concl ...";
			$JOSTF		= "jo_stfdet / jo._stfdet ...";
			$SRRET		= "sr_ret / sr._ret ...";
			$QRCDET		= "qrc_det / qrc._ret ...";
			$Others		= "others data / oth._ret ...";
		}
		
		$PR_STAT	= 0;
		if(isset($_POST['PRJCODE']))	// MARKETING
		{
			$PRJCODE	= $_POST['PRJCODE'];
			$RES_TYPE	= $_POST['RES_TYPE'];
			$RESFULL	= $_POST['RESFULL'];
		}
		else
		{
			$PRJCODE	= "";
			$RES_TYPE	= "0";
			$RESFULL	= 0;
		}
	?>
			
	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <?php echo $h1_title; ?>
		    <small><?php echo $h2_title; ?></small>
		  </h1>
		</section>

		<section class="content">
		    <div class="row">
				<div class="col-md-12" id="idprogbar" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
		        <div class="col-md-12">
		            <div class="box box-primary">
		                <div class="box-header with-border" style="display:none">               
		              		<div class="box-tools pull-right">
		                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		                    </div>
		                </div>
		                <br>
		                <div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                        <div class="callout callout-info">
		                            <?php echo $alert1; ?>
		                        </div>           
		                    </div>
		                </div>
		                <div class="box-body chart-responsive">
		                    <form name="myformupload" id="myformupload" class="form-horizontal" enctype="multipart/form-data" method="post">
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
		                          	<div class="col-sm-10">
		                          		<?php
		                          			$secRes		= base_url().'index.php/__l1y/resSyst/?id=';
											$urlLock	= site_url('__l1y/logout/?id=');
		                          		?>
		                          		<input type="hidden" name="secRes" id="secRes" value="<?php echo $secRes; ?>" />
		                                <select name="PRJCODE" id="PRJCODE" class="form-control select2">
		                                    <option value="none"> --- </option>
		                                    <?php
												if($resPRJC > 0)
												{
													foreach($resPRJ as $row) :
														$PRJCODE1 	= $row->PRJCODE;
														$PRJNAME 	= $row->PRJNAME;
														?>
															<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>>
																<?php echo "$PRJCODE1 - $PRJNAME"; ?>
		                                                    </option>
														<?php
													endforeach;
												}
												else
												{
													?>
														<option value="none">--- No Unit Found ---</option>
													<?php
												}
		                                    ?>
										</select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label">Tipe Reset</label>
		                          	<div class="col-sm-10">
		                                <select name="RESFULL" id="RESFULL" class="form-control select2">
		                                    <option value="none"> --- </option>
		                                    <option value="0"> Hanya Data Transaksi </option>
		                                    <option value="1"> Bersihkan Semua Data </option>
										</select>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
		                            <div class="col-sm-10">
		                                <select name="RES_TYPE" id="RES_TYPE" class="form-control" style="max-width:200px" >
		                                    <option value="0"> --- </option>
		                                    <option value="All" <?php if($RES_TYPE == "All") { ?> selected <?php } ?>><?php echo $AllTRX; ?></option>
		                                    <option value="Exc" <?php if($RES_TYPE == "Exc") { ?> selected <?php } ?>><?php echo $ExcTRX; ?></option>
										</select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                            <div class="col-sm-10">
		                                <div class="alert alert-danger alert-dismissible">
		                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                    <h4><i class="icon fa fa-ban"></i> Peringatan ... !!!</h4>
		                                    1. Hati-hati dalam menentukan proyek.<br>
		                                    2. <?php echo $AllTRX; ?> : Mereset semua data proyek tersebut tanpa kecuali.<br>
		                                    3. <?php echo $ExcTRX; ?> : Mereset semua data proyek tersebut selain BoQ dan WBS.<br>
		                                    4. Reset ini akan memakan beberapa waktu Anda.
		                                  </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
		                                <!--<input type="submit" value="Upload File" class="btn btn-warning" style="width:120px;" />&nbsp;&nbsp;-->
		                                <button class="btn btn-primary" type="button" onclick="chcekFile()"><i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;<?php echo "$Reset"; ?></button>
		                            </div>
		                        </div>
		                    </form>
		                </div>
		                <div id="loading_1" class="overlay" style="display:none">
		                    <i class="fa fa-refresh fa-spin"></i>
		                </div>
		            </div>
		        </div>
		    </div>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="display: none;"></iframe>
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
</script>

<script>
	$(function () 
	{
	$("#example1").DataTable();
	$('#example2').DataTable({
	  "paging": true,
	  "lengthChange": false,
	  "searching": false,
	  "ordering": true,
	  "info": true,
	  "autoWidth": false
	});
	});
	
	function chcekFile(e)
	{
		

		var wasPressed 		= false;

		e = e || window.event;
		window.event.keyCode = 116;
		
		if (e.keyCode == 116)
		{
			document.onkeydown 	= 116;
			document.onkeypress = 116
			document.onkeyup 	= 116;

			console.log("f5 pressed");
			alert("f5 pressed");
			wasPressed = true;
		}
		/*document.onkeydown 	= fkey;
		document.onkeypress = fkey
		document.onkeyup 	= fkey;

		var wasPressed 		= false;

		function fkey(e)
		{
			e = e || window.event;
			if( wasPressed ) return; 

			if (e.keyCode == 116)
			{
				console.log("f5 pressed");
				alert("f5 pressed");
				wasPressed = true;
			}
		}*/
		return false;
		PRJCODE 	= document.getElementById('PRJCODE').value;
		RESFULL 	= document.getElementById('RESFULL').value;
		if(PRJCODE == 'none')
		{
			swal('<?php echo $alert3; ?>',
			{
				icon: "warning",
			});
			document.getElementById('PRJCODE').focus();
			return false;
		}

		if(RESFULL == 'none')
		{
			swal('<?php echo $resType; ?>',
			{
				icon: "warning",
			});
			document.getElementById('RESFULL').focus();
			return false;
		}

	    swal({
            text: "<?php echo $sureReset; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
            	if(RESFULL == 0)
            	{
            		TypRes = "<?php echo $resTrx; ?>";
            	}
            	else
            	{
            		TypRes = "<?php echo $resAll; ?>";
            	}
			    swal({
		            text: TypRes,
		            icon: "warning",
		            buttons: ["No", "Yes"],
		        })
		        .then((willDelete1) => 
		        {
		            if (willDelete1) 
		            {
		            	document.getElementById('idprogbar').style.display = '';
		            	document.getElementById('loading_1').style.display = '';
		            	var collID	= PRJCODE+'~'+RESFULL;
				        var myarr 	= collID.split("~");

				        var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
						$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
						$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= RESFULL;
						$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESET';
						butSubm.submit();

				        /*var url 	= document.getElementById('secRes').value;

				        $.ajax({
				            type: 'POST',
				            url: url,
				            data: {collID: collID},
				            success: function(response)
				            {
				            	document.getElementById('loading_1').style.display = 'none';
				            	swal(response, 
								{
									icon: "success",
								})
								.then((willDelete) => {
									var logoutUrl 	= '<?php echo $urlLock; ?>';
									window.location = logoutUrl
								});
				            }
				        });*/
		            } 
		            else 
		            {
		                swal("<?php echo $canReset; ?>", 
						{
							icon: "error",
						});
		            }
		        });
            } 
            else 
            {
                swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});
            }
        });

		/*if(userfile1 == '')
		{
			alert('Please insert an excel file which will you uploaded.');
			return false;
		}
		
		var myExt	= getFileExtension(userfile1);
		
		if(myExt != 'xls' && myExt != 'xlsx')
		{
			alert('You can upload xls or xlsx File Type only.');
			return false;
		}*/
	}
	
	function getFileExtension(filename)
	{
	  var ext = /^.+\.([^.]+)$/.exec(filename);
	  return ext == null ? "" : ext[1];
	}
	
	function getITMH_CODE(row)
	{
		var result = confirm("<?php echo $alert2; ?>");
		if (result)
		{
			ITMH_CODE = document.getElementById('ITMH_CODE'+row).value;
			document.getElementById('ITMH_CODEX').value = ITMH_CODE;
			document.getElementById('loading_1').style.display = '';
			document.frmsrch.submitSrch.click();
		}
	}
	

	function viewbillofqty(thisVal)
	{
		var urlVWDoc = document.getElementById('secVWlURL_'+thisVal).value;
		title = 'View Bill of Quantity';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlVWDoc, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}	
	
	function updStat(response)
	{
		document.getElementById('loading_1').style.display = 'none';
		swal(response, 
		{
			icon: "success",
		})
		.then((willDelete) => {
			var logoutUrl 	= '<?php echo $urlLock; ?>';
			window.location = logoutUrl
		});
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