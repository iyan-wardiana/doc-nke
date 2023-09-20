<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 20 Februari 2022
	* File Name		= v_lock.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 		= $this->session->userdata('appName');
$appBody 		= $this->session->userdata['appBody'];
$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$s_app 	= "SELECT app_stat FROM tappname";
$r_app 	= $this->db->query($s_app)->result();
foreach($r_app as $rw_app) :
	$app_stat = $rw_app->app_stat;		
endforeach;

$img_filenameX 	= "";
$sqlGetIMG		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$DefEmp_ID'";
$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$img_filenameX 		= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID.'/'.$img_filenameX);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
}
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
			if($TranslCode == 'Title')$Title = $LangTransl;
			if($TranslCode == 'Procedure')$Procedure = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$alert1	= "Anda yakin akan mengunci transaksi penjurnalan?";
			$alert2	= "Mengunci jurnal akan membuat jurnal yang berstatus approve/disetujui tidak bisa dibatalkan. Anda Yakin?";
		}
		else
		{
			$alert1	= "Are you sure want to lock Journal Transaction?";
			$alert2	= "After locking the journal, the journal document with the status of approved cannot be canceled. Are you sure?";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			<?php echo $mnName; ?> &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-red" id="StatApp" <?php if($app_stat == 0) { ?> style="display: none;" <?php } ?>> TERKUNCI! </span>
			</h1>
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>

  		<section class="content">
	      	<div class="row">
	      		<div class="col-md-12">
	                <div class="alert alert-warning alert-dismissible">
		                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="display: none;">&times;</button>
		                <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
		                Fitur ini digunakan untuk mengunci program agar pengguna lain tidak dapat melakukan penjurnalan di semua transaksi yang secara langsung membentuk jurnal. Fitur Penguncian ini akan tetap membuka kesempatan kepada pengguna lain untuk bisa menginput transaksi-transaksi yang tidak membentuk jurnal, seperti SPP, PO, dan SPK.
		            </div>
		        </div>
		    </div>
	        <form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return validateInData()">
                <div class="row">
                    <div class="col-md-4">
                        <div class="box box-success">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title">Data Pengunci Transaksi</h3>
                            </div>
                            <div class="box-body">
				                <div class="row">
				                	<?php
			                    		$compName 	= "-";
			                    		$s_Empl		= "SELECT CONCAT(First_Name,' ', Last_Name) AS compName, Birth_Place, Date_Of_Birth, Pos_Code
			                    						FROM tbl_employee
			                    						WHERE Emp_ID = '$DefEmp_ID' LIMIT 1";
										$r_Empl 	= $this->db->query($s_Empl)->result();
										foreach($r_Empl as $rw_Empl) :
											$compName 	= $rw_Empl->compName;
											$bPlace 	= $rw_Empl->Birth_Place;
											$bDate 		= strftime('%d %b %Y', strtotime($rw_Empl->Date_Of_Birth));
											$PosCode 	= $rw_Empl->Pos_Code;

											$POSS_NAME 	= "-";			
											$s_possC	= "SELECT POSS_NAME, POSS_DESC FROM tbl_position_str WHERE POSS_CODE = '$PosCode' LIMIT 1";
											$r_possC 	= $this->db->query($s_possC)->result();
											foreach($r_possC as $rw_possC) :
												$POSS_NAME 	= $rw_possC->POSS_NAME;
												$POSS_DESC 	= $rw_possC->POSS_DESC;
											endforeach;
										endforeach;
				                	?>
					                <div class="col-md-5" style="text-align: center;">
					                  	<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
					                </div>

					                <div class="col-md-7">
					                  	<div class="row">
					                    	<div class="col-md-12">
					                    		<?php echo "<strong>Nama Lengkap</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$compName"; ?>
					                    	</div>
					                    	<div class="col-md-12">
					                    		<?php echo "<strong>TTL</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$bPlace, $bDate"; ?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php echo "<strong>Posisi</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$POSS_NAME"; ?>
					                    	</div>
				                    	</div>
				                    </div>
					            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box box-primary">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title">Eksekusi Penguncian</h3>
                            </div>
                            <div class="box-body">
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label">Tipe</label>
				                    <div class="col-sm-10">
				                    	<select name="LOCKTYPE" id="LOCKTYPE" class="form-control select2">
				                          	<option value="0"> Hanya Jurnal </option>
				                          	<option value="1"> Seluruh Transaksi </option>
				                       	</select>
				                    </div>
				                </div>
				                <div class="form-group" id="groupLOCKJMONTH">
				                    <label for="inputName" class="col-sm-2 control-label">Bulan</label>
				                    <div class="col-sm-6">
				                    	<select name="LOCKJMONTH" id="LOCKJMONTH" class="form-control select2">
				                    		<option value="">-- Pilih Bulan --</option>
				                    		<?php
				                    			$arrMonth = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
				                    			$no = 0;
				                    			foreach($arrMonth as $m):
				                    				$no = $no + 1;
				                    				?>
				                    					<option value="<?=$no?>"><?=$m?></option>
				                    				<?php
				                    			endforeach;
				                    		?>
				                       	</select>
				                    </div>
				                    <div class="col-sm-4">
				                    	<select name="LOCKJYEAR" id="LOCKJYEAR" class="form-control select2">
				                    		<?php
				                    			$nowD = date('Y-m-d');
				                    			$nowY = date('Y');
				                    			$befY = date('Y', strtotime("-1 year", strtotime($nowD))); // Before this year
				                    			for ($i=$befY; $i <= $nowY; $i++) { 
				                    				?>
				                    					<option value="<?=$i?>" <?php if($i == $nowY) echo "selected"; ?>><?=$i?></option>
				                    				<?php
				                    			}
				                    		?>
				                       	</select>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label">Pesan</label>
				                    <div class="col-sm-10">
				                    	<textarea name="JournalH_Desc" class="form-control" id="JournalH_Desc" cols="30" placeholder="Pesan (WA) yang akan dikirim kepada setiap pengguna" style="height: 60px"></textarea>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>
                    <div class="col-md-2">
                        <div class="box box-warning" style="text-align: center;">
                            <div class="box-header with-border">
                                <h3 class="box-title">Kunci Transaksi</h3>
                            </div>
                            <div class="box-body">
                            	<a href="#" id="lockID" onClick="lock(0);" <?php if($app_stat == 0) { ?> style="display: none;" <?php } ?>>
									<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/locked.jpg'; ?>" style="max-width:90px; max-height:90px" >
								</a>

				            	<a href="#" id="unlockID" onClick="lock(1);" <?php if($app_stat == 1) { ?> style="display: none;" <?php } ?>>
									<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/unlocked.jpg'; ?>" style="max-width:90px; max-height:80px" >
								</a>
				            </div>
				        </div>
				    </div>
                    <div class="col-md-2">
                        <div class="box box-warning" style="text-align: center;">
                            <div class="box-header with-border">
                                <h3 class="box-title">Kunci Jurnal</h3>
                            </div>
                            <div class="box-body">
                            	<a href="#" id="lockID" onClick="lockJRN();">
									<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/lock_journal.png'; ?>" style="max-width:90px; max-height:90px" >
								</a>
				            </div>
				        </div>
				    </div>
                </div>
	        </form>
	    </section>
	</body>
</html>
<script type="text/javascript">
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
	      	autoclose: true,
			/*startDate: '-3d',
			endDate: '+0d'*/
	    });

	    //Date picker
	    $('#datepicker2').datepicker({
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
	    
	    $('#LOCKTYPE').on('change', function(){
	        let thisVal = $(this).val();
	        if(thisVal == 0)
	        {
	            $('#groupLOCKJMONTH').show();
	            $('#groupLOCKJMONTH').show();
	        }
	        else
	        {
	            $('#groupLOCKJMONTH').hide();
	            $('#groupLOCKJMONTH').hide();
	        }
	    });
  	});

  	function lock(statVal)
  	{
        swal({
            text: "<?php echo $alert1; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
		        var url 		= "<?php echo site_url('lck/lTrx')?>";
		        
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {statApp: statVal},
                    success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        })
                        .then(function()
                        {
                        	swal.close();
                        	if(statVal == 0)
                        	{
                        		document.getElementById('lockID').style.display 	= 'none';
                        		document.getElementById('unlockID').style.display 	= '';
                        		document.getElementById('StatApp').style.display 	= 'none';
                        	}
                        	else if(statVal == 1)
                        	{
                        		document.getElementById('lockID').style.display 	= '';
                        		document.getElementById('unlockID').style.display 	= 'none';
                        		document.getElementById('StatApp').style.display 	= '';
                        	}
                        })
                    }
                });
            } 
            else 
            {
                //...
            }
        });
	}

  	function lockJRN()
  	{
  	    let LOCKJMONTH = $('#LOCKJMONTH').val();
  	    let LOCKJYEAR = $('#LOCKJMONTH').val();
  	    
  	    if(LOCKJMONTH == '')
  	    {
  	        swal("Silahkan pilih bulan yang akan dikunci jurnalnya");
  	        return false;
  	    }
  	        
  	    if(LOCKJYEAR == '')
  	    {
  	        swal("Silahkan pilih tahun yang akan dikunci jurnalnya");
  	        return false;
  	    }
  	    
        swal({
            text: "<?php echo $alert2; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
		        var url 		= "<?php echo site_url('lck/lJRN')?>";
		        
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {LOCKJMONTH:$('#LOCKJMONTH').val(), LOCKJYEAR:$('#LOCKJYEAR').val()},
                    success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        })
                        .then(function()
                        {
                        	swal.close();
                        })
                    }
                });
            } 
            else 
            {
                //...
            }
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