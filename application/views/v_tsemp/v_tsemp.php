<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2020
 * File Name	= v_tsemp.php
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
							<table id="example1" class="table table-bordered table-striped table-responsive search-table inner">
								<thead>
									<tr style="background:#CCCCCC">
									    <th style="vertical-align:middle; text-align:center" width="3%">&nbsp;</th>
									    <th style="vertical-align:middle; text-align:center" width="7%">Tanggal</th>
									    <th style="vertical-align:middle; text-align:center" width="5%">Mulai</th>
									    <th style="vertical-align:middle; text-align:center" width="5%">Selesai</th>
									    <th style="vertical-align:middle; text-align:center" width="50%">Catatan Pekerjaan</th>
									    <th style="vertical-align:middle; text-align:center" width="10%">User</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$i = 0;
								$j = 0;
								if($TSCount > 0)
								{
									foreach($TSView as $row) :
										$myNewNo 	= ++$i;
										$EMPTS_CODE	= $row->EMPTS_CODE;
										$EMP_ID		= $row->EMP_ID;
											$empName	= '';
											$sqlEMP 	= "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
															FROM tbl_employee
															WHERE Emp_ID = '$EMP_ID'";
											$resEMP 	= $this->db->query($sqlEMP)->result();
											foreach($resEMP as $rowEMP) :
												$empName	= $rowEMP->empName;
											endforeach;
										$empName1	= strtolower($empName);
										$empName2	= ucwords($empName1);
										
										$EMPTS_DATE		= date('d-m-Y', strtotime($row->EMPTS_DATE));
										$EMPTS_STIME	= date('h:i', strtotime($row->EMPTS_STIME));
										$EMPTS_ETIME	= date('h:i', strtotime($row->EMPTS_ETIME));
										$EMPTS_DESK		= '';
										$EMPTS_DESK		= $row->EMPTS_DESK;
										$EMPTS_PERSON 	= strtoupper($row->EMPTS_PERSON);
										if($EMPTS_PERSON != '')
										{
											$EMPTS_DESK	= $EMPTS_DESK.".<br>Serta melakukan pertemuan dengan : ". $EMPTS_PERSON;
										}
											
										if ($j==1) {
											echo "<tr class=zebra1>";
											$j++;
										} else {
											echo "<tr class=zebra2>";
											$j--;
										}
										?> 
								                <td style="text-align:center"> <?php echo $myNewNo; ?>.</td>
								                <td style="text-align:center"><?php echo $EMPTS_DATE; ?></td>
								                <td style="text-align:center"><?php echo $EMPTS_STIME; ?></td>
								                <td style="text-align:center"><?php echo $EMPTS_ETIME; ?></td>
								                <td nowrap>
								                    <?php
														$secUpd	= site_url('c_tsemp/c_tsemp/u_p4T/?id='.$this->url_encryption_helper->encode_url($EMPTS_CODE));
								                   		echo anchor($secUpd,$EMPTS_DESK);
													?>
								                </td>
								                <td style="text-align:center"><?php echo $empName2; ?></td>
											</tr>
										<?php 
									endforeach; 
								}
								?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="6" style="text-align:left">
										<?php
											echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>');
										?>
										</td>
									</tr>
								</tfoot>
							</table>
					    </div>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>

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