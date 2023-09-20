<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2017
 * File Name	= joblist.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
	<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
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
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'JobCode')$JobCode = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Class')$Class = $LangTransl;
		if($TranslCode == 'Group')$Group = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Volume')$Volume = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

<h1>
    <?php echo $h2_title; ?> 
    <small><?php echo $PRJNAME; ?> </small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
        <thead>
            <tr>
                <th><?php echo $Description; ?></th>
                <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Group; ?></th>
                <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Class; ?></th>
                <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Type; ?></th>
                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Volume; ?></th>
                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Unit; ?></th>
                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?></th>
          </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($countjobl > 0)
			{
				foreach($vwjoblist as $row) :
					$myNewNo 		= ++$i;
					$JOBCODEID 	= $row->JOBCODEID;
					$JOBCODEIDV	= $row->JOBCODEIDV;
					$JOBCOD1 	= $row->JOBCOD1;
					$JOBCOD2 	= $row->JOBCOD2;
					$JOBDESC 	= $row->JOBDESC;
					$JOBCLASS 	= $row->JOBCLASS;
					$JOBGRP 	= $row->JOBGRP;
					$JOBTYPE 	= $row->JOBTYPE;
					$JOBUNIT 	= $row->JOBUNIT;
					$JOBLEV 	= $row->JOBLEV;
					$JOBVOLM 	= $row->JOBVOLM;
					$JOBCOST 	= $row->JOBCOST;
					$ISHEADER 	= $row->ISHEADER;
					
					if($JOBGRP == 'M')
					{
						$JOBGRPD = "Material";
					}
					elseif($JOBGRP == 'U')
					{
						$JOBGRPD = "Upah";
					}
					elseif($JOBGRP == 'S')
					{
						$JOBGRPD = "Service";
					}
					elseif($JOBGRP == 'T')
					{
						$JOBGRPD = "Tools";
					}
					elseif($JOBGRP == 'I')
					{
						$JOBGRPD = "Indirect";
					}
					elseif($JOBGRP == 'R')
					{
						$JOBGRPD = "Reimburstment";
					}
					else
					{
						$JOBGRPD = "-";
					}
					
					if($JOBCLASS == 'CLS01')
					{
						$JOBCLASSD = "An. STR";
					}
					elseif($JOBCLASS == 'CLS02')
					{
						$JOBCLASSD = "Dinding";
					}
					elseif($JOBCLASS == 'CLS03')
					{
						$JOBCLASSD = "Lantai";
					}
					elseif($JOBCLASS == 'CLS04')
					{
						$JOBCLASSD = "Sanitair";
					}
					elseif($JOBCLASS == 'CLS05')
					{
						$JOBCLASSD = "Sundries";
					}
					elseif($JOBCLASS == 'CLS06')
					{
						$JOBCLASSD = "Others";
					}
					else
					{
						$JOBCLASSD = "-";
					}
					
					if($JOBTYPE == 'S')
					{
						$JOBTYPED	= "Selfdone";
					}
					elseif($JOBTYPE == 'C')
					{
						$JOBTYPED	= "Subcontracted";
					}
					elseif($JOBTYPE == 'O')
					{
						$JOBTYPED	= "Others";
					}
					else
					{
						$JOBTYPED	= "-";
					}
					
					$secUpd			= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
					$space_level1 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
					$JobView		= "$JOBCODEIDV - $JOBDESC";
					$secUpd			= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
						
					if($ISHEADER == 1)
					{
						if ($j==1) {
							echo "<tr class=zebra1 style=font-weight:bold>";
							$j++;
						} else {
							echo "<tr class=zebra2 style=font-weight:bold>";
							$j--;
						}
					}
					else
					{
						if ($j==1) {
							echo "<tr class=zebra1>";
							$j++;
						} else {
							echo "<tr class=zebra2>";
							$j--;
						}
					}
					?>
						<td width="51%" nowrap><?php print $JobView; ?> </td>
						<td nowrap><?php print $JOBCLASSD; ?> </td>
						<td nowrap><?php print $JOBCLASSD; ?></td>
						<td nowrap><?php print $JOBTYPED; ?></td>
						<td nowrap style="text-align:right"><?php print number_format($JOBVOLM, $decFormat); ?> </td>
						<td nowrap style="text-align:center"><?php echo $JOBUNIT; ?></td>
						<td nowrap style="text-align:right"><?php print number_format($JOBCOST, $decFormat); ?></td>
					</tr>
					<?php
					$sqlC2		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$ressqlC2 	= $this->db->count_all($sqlC2);
					if($ressqlC2 > 0)
					{
						$sqlJOB2	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, ISHEADER
										FROM tbl_joblist
										WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
						$resJOB2 	= $this->db->query($sqlJOB2)->result();
						foreach($resJOB2 as $rowJOB2) :
							$JOBCODEID2 	= $rowJOB2->JOBCODEID;
							$JOBCODEID2V	= $rowJOB2->JOBCODEIDV;
							$JOBPARENT2		= $rowJOB2->JOBPARENT;
							$JOBDESC2 		= $rowJOB2->JOBDESC;
							$JOBGRP2 		= $rowJOB2->JOBGRP;
							$JOBCLASS2 		= $rowJOB2->JOBCLASS;
							$JOBUNIT2 		= $rowJOB2->JOBUNIT;
							$JOBVOLM2 		= $rowJOB2->JOBVOLM;
							$JOBCOST2 		= $rowJOB2->JOBCOST;
							$ISHEADER2 		= $rowJOB2->ISHEADER;
							
							$JobView2		= "$JOBCODEID2V - $JOBDESC2";
							$space_level2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							$secUpd2		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID2));
							
							if($ISHEADER2 == 1)
							{
								if ($j==1) {
									echo "<tr class=zebra1 style=font-weight:bold>";
									$j++;
								} else {
									echo "<tr class=zebra2 style=font-weight:bold>";
									$j--;
								}
							}
							else
							{
								if ($j==1) {
									echo "<tr class=zebra1>";
									$j++;
								} else {
									echo "<tr class=zebra2>";
									$j--;
								}
							}
							?> 
								<td>
									<?php 
										print $space_level2;
										print anchor("$secUpd2",$JobView2,array('class' => 'update')).' ';
									?>								</td>
								<td nowrap><?php print $JOBCLASSD; ?></td>
								<td nowrap><?php print $JOBCLASSD; ?></td>
								<td nowrap><?php print $JOBTYPED; ?></td>
								<td nowrap style="text-align:right"><?php print number_format($JOBVOLM2, $decFormat); ?></td>
								<td nowrap style="text-align:center"><?php echo $JOBUNIT2; ?></td>
								<td nowrap style="text-align:right"><?php print number_format($JOBCOST2, $decFormat); ?></td>
							</tr>
							<?php
							$sqlC3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE'";
							$ressqlC3 	= $this->db->count_all($sqlC3);
							if($ressqlC3 > 0)
							{
								$sqlJOB3	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, ISHEADER
												FROM tbl_joblist
												WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE'";
								$resJOB3 	= $this->db->query($sqlJOB3)->result();
								foreach($resJOB3 as $rowJOB3) :
									$JOBCODEID3 	= $rowJOB3->JOBCODEID;
									$JOBCODEID3V	= $rowJOB3->JOBCODEIDV;
									$JOBPARENT3		= $rowJOB3->JOBPARENT;
									$JOBDESC3 		= $rowJOB3->JOBDESC;
									$JOBGRP3 		= $rowJOB3->JOBGRP;
									$JOBCLASS3 		= $rowJOB3->JOBCLASS;
									$JOBUNIT3 		= $rowJOB3->JOBUNIT;
									$JOBVOLM3 		= $rowJOB3->JOBVOLM;
									$JOBCOST3 		= $rowJOB3->JOBCOST;
									$ISHEADER3 		= $rowJOB3->ISHEADER;
									
									$JobView3		= "$JOBCODEID3V - $JOBDESC3";
									$space_level3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									$secUpd3		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID3));
									
									if($ISHEADER3 == 1)
									{
										if ($j==1) {
											echo "<tr class=zebra1 style=font-weight:bold>";
											$j++;
										} else {
											echo "<tr class=zebra2 style=font-weight:bold>";
											$j--;
										}
									}
									else
									{
										if ($j==1) {
											echo "<tr class=zebra1>";
											$j++;
										} else {
											echo "<tr class=zebra2>";
											$j--;
										}
									}
										?> 
										<td>
											<?php 
												print $space_level3;
												print anchor("$secUpd3",$JobView3,array('class' => 'update')).' ';
											?>										</td>
										<td nowrap><?php print $JOBCLASSD; ?></td>
										<td nowrap><?php print $JOBCLASSD; ?></td>
										<td nowrap><?php print $JOBTYPED; ?></td>
										<td nowrap style="text-align:right"><?php print number_format($JOBVOLM3, $decFormat); ?></td>
										<td nowrap style="text-align:center"><?php echo $JOBUNIT3; ?></td>
										<td nowrap style="text-align:right"><?php print number_format($JOBCOST3, $decFormat); ?></td>
									</tr>
									<?php
									$sqlC4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE'";
									$ressqlC4 	= $this->db->count_all($sqlC4);
									if($ressqlC4 > 0)
									{
										$sqlJOB4	= "SELECT JOBCODEID, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, ISHEADER
														FROM tbl_joblist
														WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE'";
										$resJOB4 	= $this->db->query($sqlJOB4)->result();
										foreach($resJOB4 as $rowJOB4) :
											$JOBCODEID4 	= $rowJOB4->JOBCODEID;
											$JOBCODEID4V	= $rowJOB4->JOBCODEIDV;
											$JOBPARENT4		= $rowJOB4->JOBPARENT;
											$JOBDESC4 		= $rowJOB4->JOBDESC;
											$JOBGRP4 		= $rowJOB4->JOBGRP;
											$JOBCLASS4 		= $rowJOB4->JOBCLASS;
											$JOBUNIT4 		= $rowJOB4->JOBUNIT;
											$JOBVOLM4 		= $rowJOB4->JOBVOLM;
											$JOBCOST4 		= $rowJOB4->JOBCOST;
											$ISHEADER4 		= $rowJOB4->ISHEADER;
											
											$JobView4		= "$JOBCODEID4V - $JOBDESC4";
											$space_level4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											$secUpd4		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID4));
											
											if($ISHEADER4 == 1)
											{
												if ($j==1) {
													echo "<tr class=zebra1 style=font-weight:bold>";
													$j++;
												} else {
													echo "<tr class=zebra2 style=font-weight:bold>";
													$j--;
												}
											}
											else
											{
												if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}
											}
												?> 
												<td>
													<?php 
														print $space_level4;
														print anchor("$secUpd4",$JobView4,array('class' => 'update')).' ';
													?>												</td>
												<td nowrap><?php print $JOBCLASSD; ?></td>
												<td nowrap><?php print $JOBCLASSD; ?></td>
												<td nowrap><?php print $JOBTYPED; ?></td>
												<td nowrap style="text-align:right"><?php print number_format($JOBVOLM4, $decFormat); ?></td>
												<td nowrap style="text-align:center"><?php echo $JOBUNIT4; ?></td>
												<td nowrap style="text-align:right"><?php print number_format($JOBCOST4, $decFormat); ?></td>
											</tr>
											<?php
											$sqlC5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";
											$ressqlC5 	= $this->db->count_all($sqlC5);	
											if($ressqlC5 > 0)
											{
												$sqlJOB5	= "SELECT JOBCODEID, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, ISHEADER
																FROM tbl_joblist
																WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";
												$resJOB5 	= $this->db->query($sqlJOB5)->result();
												foreach($resJOB5 as $rowJOB5) :
													$JOBCODEID5 	= $rowJOB5->JOBCODEID;
													$JOBCODEID5V	= $rowJOB5->JOBCODEIDV;
													$JOBPARENT5		= $rowJOB5->JOBPARENT;
													$JOBDESC5 		= $rowJOB5->JOBDESC;
													$JOBGRP5 		= $rowJOB5->JOBGRP;
													$JOBCLASS5 		= $rowJOB5->JOBCLASS;
													$JOBUNIT5 		= $rowJOB5->JOBUNIT;
													$JOBVOLM5 		= $rowJOB5->JOBVOLM;
													$JOBCOST5 		= $rowJOB5->JOBCOST;
													$ISHEADER5 		= $rowJOB5->ISHEADER;
													
													$JobView5		= "$JOBCODEID5V - $JOBDESC5";
													$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													$secUpd5		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID5));
													
													if($ISHEADER5 == 1)
													{
														if ($j==1) {
															echo "<tr class=zebra1 style=font-weight:bold>";
															$j++;
														} else {
															echo "<tr class=zebra2 style=font-weight:bold>";
															$j--;
														}
													}
													else
													{
														if ($j==1) {
															echo "<tr class=zebra1>";
															$j++;
														} else {
															echo "<tr class=zebra2>";
															$j--;
														}
													}
														?> 
														<td>
															<?php 
																print $space_level5;
																print anchor("$secUpd5",$JobView5,array('class' => 'update')).' ';
															?>														</td>
														<td nowrap><?php print $JOBCLASSD; ?></td>
														<td nowrap><?php print $JOBCLASSD; ?></td>
														<td nowrap><?php print $JOBTYPED; ?></td>
														<td nowrap style="text-align:right"><?php print number_format($JOBVOLM5, $decFormat); ?></td>
														<td nowrap style="text-align:center"><?php echo $JOBUNIT5; ?></td>
														<td nowrap style="text-align:right"><?php print number_format($JOBCOST5, $decFormat); ?></td>
													</tr>
													<?php
												endforeach;
											}
										endforeach;
									}
								endforeach;
							}
						endforeach;
					}
				endforeach; 
			}
		?>
        </tbody>
   	</table>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
</body>

</html>
<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
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
<script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>