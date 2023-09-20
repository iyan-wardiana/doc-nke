<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Maret 2020
 * File Name	= v_absen_rep.php
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
				    <small>WF Home NKE</small>
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
							<table id="example3" width="100%" class="table table-bordered table-striped table-responsive search-table inner">
								<thead>
									<tr>
										<th style="vertical-align:middle; text-align:center" width="2%">&nbsp;</th>
										<th style="vertical-align:middle; text-align:center" width="5%">NIK</th>
	                                    <th style="vertical-align:middle; text-align:center" width="9%">Nama Karyawan</th>
	                                    <th style="vertical-align:middle; text-align:center" width="11%">Tempat, Tgl. Lahir</th>
	                                    <th style="vertical-align:middle; text-align:center" width="20%">Departemen</th>
	                                    <th style="vertical-align:middle; text-align:center" width="13%">Masuk</th>
	                                    <th style="vertical-align:middle; text-align:center;" width="12%">Keluar</th>
	                                    <th style="vertical-align:middle; text-align:center" width="20%">Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$DN 	= date('Y-m-d');
	                                    $i 		= 0;
	                                    $j 		= 0;
	                                    
	                                    $sqlLOGC	= "tbl_absensi WHERE ABS_DATE = '$DN'";
	                                    $resLOGC	= $this->db->count_all($sqlLOGC);
	                                    
	                                    if($resLOGC > 0)
	                                    {											
	                                        $sqlLOG	= "SELECT A.*, B.Birth_Place, B.Date_Of_Birth,
	                                                        CONCAT(B.First_Name, ' ', B.Last_Name) AS compName
	                                                    FROM tbl_absensi A
	                                                    	INNER JOIN tbl_employee B ON A.EMP_ID = B.Emp_ID
	                                                    WHERE ABS_DATE = '$DN' ORDER BY ABS_CREATED ASC";
	                                        $resLOG	= $this->db->query($sqlLOG)->result();
	                                        foreach ($resLOG as $rowLOG) :
	                                        	$i 				= $i + 1;
	                                            $EMP_ID			= $rowLOG->EMP_ID;
	                                            $ABS_DATEI		= date('d-m-Y H:i:s', strtotime($rowLOG->ABS_DATEI));
	                                            
	                                            $ABS_DATEI		= $rowLOG->ABS_DATEI;
	                                            if($ABS_DATEI != '')
	                                            	$ABS_DATEI	= date('d-m-Y H:i:s', strtotime($rowLOG->ABS_DATEI));
	                                            else
	                                            	$ABS_DATEI	= "-";
	                                            
	                                            $ABS_DATEO		= $rowLOG->ABS_DATEO;
	                                            if($ABS_DATEO != '')
	                                            	$ABS_DATEO	= date('d-m-Y H:i:s', strtotime($rowLOG->ABS_DATEO));
	                                            else
	                                            	$ABS_DATEO	= "-";

	                                            $compName		= $rowLOG->compName;
	                                            $Birth_Place	= $rowLOG->Birth_Place;
	                                            $Date_Of_Birth	= $rowLOG->Date_Of_Birth;
												
												$DEPT_NAME		= '';
												$SEC_CODE		= '';
												$POS_NAME		= '';
										
			                                    $sqlASMC		= "tbl_assesment WHERE EMP_ID = '$EMP_ID'";
			                                    $resASMC		= $this->db->count_all($sqlASMC);
												if($resASMC > 0)
												{
													$sqlDEPT	= "SELECT B.DEPT_NAME, A.SEC_CODE, A.POS_NAME FROM tbl_assesment A 
																	INNER JOIN tbl_dept B ON A.DIV_CODE = B.DEPT_CODE
																	WHERE A.EMP_ID = '$EMP_ID'";
													$resDEPT	= $this->db->query($sqlDEPT)->result();
													foreach ($resDEPT as $key) :
														$DEPT_NAME	= $key->DEPT_NAME;
														$SEC_CODE	= $key->SEC_CODE;
														$POS_NAME	= $key->POS_NAME;
													endforeach;
												}
												$EMP_DEPCOL	= $POS_NAME." ".$DEPT_NAME."<br>".$SEC_CODE;
												if($resASMC == 0)
													$EMP_DEPCOL		= "<a class='text-red' style='font-style: italic;'>Belum mengisi Risk Assesment</a>";
	                                                
	                                            if ($j==1) {
	                                                echo "<tr class=zebra1>";
	                                                $j++;
	                                            } else {
	                                                echo "<tr class=zebra2>";
	                                                $j--;
	                                            }
	                                            ?>
	                                                    <td style="text-align:center"><?php echo $i; ?>.</td>
	                                                    <td style="text-align:center"><?php echo $EMP_ID; ?></td>
	                                                    <td nowrap><?php echo $compName; ?></td>
	                                                    <td nowrap><?php echo $Birth_Place.", ".$Date_Of_Birth; ?></td>
	                                                    <td><?php echo $EMP_DEPCOL; ?></td>
	                                                    <td style="text-align:center"><?php echo $ABS_DATEI; ?></td>
	                                                    <td style="text-align:center"><?php echo $ABS_DATEO; ?></td>
	                                                    <td style="text-align:center">&nbsp;</td>
	                                                </tr>
	                                            <?php 
	                                        endforeach; 
	                                    }
	                                    ?>
								</tbody>
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