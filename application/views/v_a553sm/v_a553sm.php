<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Maret 2020
 * File Name	= v_a553sm.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody 	= $this->session->userdata['appBody'];

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

$empNameAct	= '';
$sqlEMP 	= "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
				FROM tbl_employee
				WHERE Emp_ID = '$DefEmp_ID'";
$resEMP 	= $this->db->query($sqlEMP)->result();
foreach($resEMP as $rowEMP) :
	$empNameAct	= $rowEMP->empName;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title>AdminLTE 2 | Dashboard</title>
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
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;

		endforeach;
	?>
	
	<body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
				    <?php echo $h2_title; ?>
				    <small><?php echo $empNameAct; ?></small>
				  </h1>
			</section>

			<style>
				.search-table, td, th {
					border-collapse: collapse;
				}
				.search-table-outter { overflow-x: scroll; }
			</style>
    		
    		<section class="content">
				<div class="box">
					<div class="box-body">
						<div class="search-table-outter">
							<table id="example1" width="100%" class="table table-bordered table-striped table-responsive search-table inner">
								<thead>
									<tr style="background:#CCCCCC">
									    <th style="vertical-align:middle; text-align:center" width="3%">NIK</th>
									    <th style="vertical-align:middle; text-align:center" width="7%">Nama Karyawan</th>
									    <th style="vertical-align:middle; text-align:center" width="5%">Tempat, Tgl. Lahir</th>
									    <th style="vertical-align:middle; text-align:center" width="5%">Departemen</th>
									    <th style="vertical-align:middle; text-align:center" width="50%">Catatan Penting</th>
									    <th style="vertical-align:middle; text-align:center; display: none;" width="10%">No. Kontak</th>
									    <th style="vertical-align:middle; text-align:center" width="2%">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$i = 0;
								$j = 0;
								if($ASMCount > 0)
								{
									foreach($ASMView as $row) :
										$myNewNo 	= ++$i;
										$ASSM_CODE	= $row->ASSM_CODE;
										$ASSM_DATE	= $row->ASSM_DATE;
										$EMP_ID		= $row->EMP_ID;
										$EMP_NAME	= $row->EMP_NAME;
										$EMP_BPLACE	= $row->EMP_BPLACE;
										$EMP_BDATE	= $row->EMP_BDATE;
										$EMP_BDATE	= date('d-m-Y', strtotime($EMP_BDATE));
										$EMP_GENDER	= $row->EMP_GENDER;
										$DIV_CODE	= $row->DIV_CODE;
										$SEC_CODE	= $row->SEC_CODE;
										$POS_NAME	= $row->POS_NAME;
										$Q_1		= $row->Q_1;
										$Q_1_1		= $row->Q_1_1;
										$Q_1_1DESC	= $row->Q_1_1DESC;
										$Q_2		= $row->Q_2;
										$Q_2_DESC	= $row->Q_2_DESC;
										$Q_3		= $row->Q_3;
										$Q_4		= $row->Q_4;
										$Q_5		= $row->Q_5;
										$Q_6		= $row->Q_6;
										$Q_6_DESC	= $row->Q_6_DESC;
										$Q_7		= $row->Q_7;
										$EMP_MAIL	= $row->EMP_MAIL;
										$EMP_NOHP	= $row->EMP_NOHP;
										$PROB_CONCL	= $row->PROB_CONCL;

										$DEPT_NAME	= '';
										$sqlDEPT	= "SELECT DEPT_NAME FROM tbl_dept WHERE DEPT_CODE = '$DIV_CODE'";
										$resDEPT	= $this->db->query($sqlDEPT)->result();
										foreach ($resDEPT as $key) :
											$DEPT_NAME	= $key->DEPT_NAME;
										endforeach;
										
										$EMP_DEPCOL	= $POS_NAME." ".$DEPT_NAME."<br>".$SEC_CODE;

										$Q1DESC 	= "";
										if($Q_1 == 1)
										{
											$Q1DESC = "<a class='text-red'>Sangat memungkinkan Kontak dengan Pihak Luar</a>.";
											$COLQ1	= "<label><input type='radio' class='flat-red' checked></label>";
										}
										elseif($Q_1 == 2)
										{
											$Q1DESC = "<a class='text-green'>Tidak akan ada Kontak dengan Pihak Luar</a>.";
											$COLQ1	= "<label><input type='radio' class='flat-green' checked></label>";
										}
										elseif($Q_1 == 3)
										{
											$Q1DESC = "<a class='text-yellow'>Belum Tentu ada Kontak dengan Pihak Luar</a>.";
											$COLQ1	= "<label><input type='radio' class='flat-yellow' checked></label>";
										}

										$Q2DESC 	= "";
										if($Q_2 == 1)
										{
											$Q2DESC = " <a class='text-green'>Bisa melakukan pekerjaan dari rumah</a>.";
											$COLQ2	= "<label><input type='radio' class='flat-green' checked></label>";
										}
										elseif($Q_2 == 2)
										{
											$Q2DESC = "<a class='text-red'> Tidak dapat melakukan pekerjaan dari rumah</a>.";
											$COLQ2	= "<label><input type='radio' class='flat-red' checked></label>";
										}
										elseif($Q_2 == 3)
										{
											$Q2DESC = "<a class='text-yellow'> Sebagian pekerjaan dapat dilakukan dari rumah</a>.";
											$COLQ2	= "<label><input type='radio' class='flat-yellow' checked></label>";
										}

										$Q3DESC 	= "";
										if($Q_3 == 1)
										{
											$Q3DESC = " <a class='text-green'>Pekerjaan dapat menggunakan Sistem NKE</a>.";
											$COLQ3	= "<label><input type='radio' class='flat-green' checked></label>";
										}
										elseif($Q_3 == 2)
										{
											$Q3DESC = "<a class='text-red'> Tidak dapat melakukan pekerjaan menggunakan Sistem NKE</a>.";
											$COLQ3	= "<label><input type='radio' class='flat-red' checked></label>";
										}
										elseif($Q_3 == 3)
										{
											$Q3DESC = "<a class='text-yellow'> Sebagian pekerjaan dapat dilakukan menggunakan Sistem NKE</a>.";
											$COLQ3	= "<label><input type='radio' class='flat-yellow' checked></label>";
										}

										$Q4DESC 	= "";
										if($Q_4 == 1)
										{
											$Q4DESC = " <a class='text-red'>Menggunakan Angkutan Umum menuju tempat kerja</a>.";
											$COLQ4	= "<label><input type='radio' class='flat-red' checked></label>";
										}
										elseif($Q_4 == 2)
										{
											$Q4DESC = "<a class='text-green'> Tidak menggunakan Angkutan Umum menuju tempat kerja</a>.";
											$COLQ4	= "<label><input type='radio' class='flat-green' checked></label>";
										}

										$Q7DESC 	= "";
										if($Q_7 == 1)
										{
											$Q4DESC = " <a class='text-red'>Cukup beresiko, karena jarak tempat duduk dengan rekan kerja dalam ruangan kurang 1,8 meter</a>.";
											$COLQ7	= "<label><input type='radio' class='flat-red' checked></label>";
										}
										elseif($Q_7 == 2)
										{
											$Q7DESC = "<a class='text-green'> Tidak beresiko, karena jarak tempat duduk dengan rekan kerja dalam ruangan lebih 1,8 meter</a>.";
											$COLQ7	= "<label><input type='radio' class='flat-green' checked></label>";
										}

										$NOTES		= "$Q1DESC$Q2DESC$Q3DESC$Q4DESC$Q7DESC";	
										$CONT_NO	= "<br>WA : $EMP_NOHP<br>e-Mail : $EMP_MAIL";
										
										$viewImage	= site_url('c_a553sm/c_a553sm/viewImage/?id='.$this->url_encryption_helper->encode_url($ASSM_CODE));

										if($PROB_CONCL <= 30)
										{
											$GRFCOL	= 'success';
											$TXTCOL	= 'green';
											$CONCD	= 'Risiko Rendah/Low';
										}
										elseif($PROB_CONCL <= 60)
										{
											$GRFCOL	= 'warning';
											$TXTCOL	= 'yellow';
											$CONCD	= 'Risiko sedang/Meium';
										}
										else
										{
											$GRFCOL	= 'danger';
											$TXTCOL	= 'red';
											$CONCD	= 'Risiko Tinggi/High';
										}
											
										if ($j==1) {
											echo "<tr class=zebra1>";
											$j++;
										} else {
											echo "<tr class=zebra2>";
											$j--;
										}
										?>
                                        		<input type="hidden" name="viewImage<?php echo $myNewNo; ?>" id="viewImage<?php echo $myNewNo; ?>" value="<?php echo $viewImage; ?>">
								                <td style="text-align:center">
								                    <?php
														$Upd= site_url('c_a553sm/c_a553sm/u_p4T/?id='.$this->url_encryption_helper->encode_url($ASSM_CODE));
								                   		echo anchor($Upd,$EMP_ID);
													?>
                                                </td>
								                <td nowrap>
								                	<?php echo $EMP_NAME; ?>
								                	<div class='progress-group'><div class='progress sm'><div class='progress-bar progress-bar-<?php echo $GRFCOL; ?>' style='width: <?php echo $PROB_CONCL; ?>%'></div></div></div>
								                </td>
								                <td nowrap><?php echo $EMP_BPLACE.", ".$EMP_BDATE; ?></td>
								                <td >
								                	<?php echo $EMP_DEPCOL; ?>
								                </td>
								                <td>
								                	<div><?php echo $COLQ1.$COLQ2.$COLQ3.$COLQ4.$COLQ7; ?> <a class='text-<?php echo $TXTCOL; ?>' style='font-weight: bold'>(<?php echo $CONCD; ?>)</a></div>
								                	<div><?php echo $NOTES."<br>Untuk tindak lanjut, hubungi : ".$CONT_NO; ?></div>
								                </td>
								                <td style="text-align:center; display: none;"><?php echo $CONT_NO; ?></td>
								                <td style="text-align:center">
													<a href="avascript:void(null);" class="btn btn-success btn-xs" title="Tampilkan" onClick="viewImage('<?php echo $myNewNo; ?>')">
                                                        <i class="glyphicon glyphicon-eye-open"></i>
                                                    </a>
                                                </td>
											</tr>
										<?php 
									endforeach; 
								}
								?>
								</tbody>
								<?php
									$sqlDEPTC	= "tbl_assesment WHERE EMP_ID = '$DefEmp_ID'";
									$resDEPTC	= $this->db->count_all($sqlDEPTC);
									
									if($resDEPTC == 0 || $EMP_ID == 'D15040004221') { ?>
									<tfoot>
										<tr>
											<td colspan="6" style="text-align:left">
												<?php 
													echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>');
												?>
											</td>
										</tr>
									</tfoot>
								<?php } ?>
							</table>
					    </div>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY hh:mm A' }})
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
	
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
	
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-red',
      radioClass   : 'iradio_flat-red'
    })
	
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-yellow, input[type="radio"].flat-yellow').iCheck({
      checkboxClass: 'icheckbox_flat-yellow',
      radioClass   : 'iradio_flat-yellow'
    })
	
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
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

	function viewImage(row)
	{
		var url	= document.getElementById('viewImage'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
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
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>