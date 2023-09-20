<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 April 2017
 * File Name	= risk_identif.php
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'CauseofRisk')$CauseofRisk = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Impact')$Impact = $LangTransl;
		if($TranslCode == 'RiskPolicy')$RiskPolicy = $LangTransl;
		if($TranslCode == 'Identifier')$Identifier = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small>project</small>
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
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
        <thead>
            <tr style="background:#CCCCCC">
                <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                <th style="vertical-align:middle; text-align:center" width="7%"><?php echo $Code ?> </th>
                <th style="vertical-align:middle; text-align:center" width="7%"><?php echo $Project ?></th>
                <th style="vertical-align:middle; text-align:center" width="7%">Div./Dep.</th>
              	<th style="vertical-align:middle; text-align:center" width="17%"><?php echo $CauseofRisk ?></th>
              	<th style="vertical-align:middle; text-align:center;" width="21%" ><?php echo $Description ?> </th>
                <th style="vertical-align:middle; text-align:center" width="33%"><?php echo $Impact ?></th>
                <th style="vertical-align:middle; text-align:center" width="9%"><?php echo $RiskPolicy ?></th>
                <th style="vertical-align:middle; text-align:center" width="6%"><?php echo $Identifier ?></th>
            </tr>
        </thead>
        <tbody>
		<?php
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$i = 0;
			$j = 0;
			if($recordcount > 0)
			{
				foreach($viewRiskIdentif as $row) :
					$myNewNo 	= ++$i;
					$RID_CODE	= $row->RID_CODE;
					$RID_PRJCODE= $row->RID_PRJCODE;
					$RID_DIVDEP	= $row->RID_DIVDEP;
					$RID_CAUSE	= $row->RID_CAUSE;
					$RID_RISK	= $row->RID_RISK;
					$RID_IMPACT	= $row->RID_IMPACT;
					$RID_POLICY	= $row->RID_POLICY;
					$EMP_ID		= $row->EMP_ID;
					
					// Risk List
						$RIDD_DESC1D	= "";
						$sqlDET1		= "SELECT RIDD_DESC1 FROM tbl_riskdescdet
											WHERE RIDD_CODE1 = '$RID_CODE'
											AND RIDD_PRJCODE1 = '$RID_PRJCODE'
											AND RIDD_GROUP1 = 1";
						$resDET1 		= $this->db->query($sqlDET1)->result();
						$i1				= 0;
						foreach($resDET1 as $row1) :
							$no1  			= ++$i1;
							$RIDD_DESC1 	= $row1->RIDD_DESC1;
							if($no1 == 1)
							{
								$RIDD_DESC1D	= "$no1. $RIDD_DESC1";
							}
							else
							{
								$RIDD_DESC1D	= "$RIDD_DESC1D<br>$no1. $RIDD_DESC1<br>";
							}
						endforeach;
					
					// Risk Impact List
						$RIDD_DESC2D	= "";
						$sqlDET2	= "SELECT RIDD_DESC2 FROM tbl_riskimpactdet
										WHERE RIDD_CODE2 = '$RID_CODE'
										AND RIDD_PRJCODE2 = '$RID_PRJCODE'
										AND RIDD_GROUP2 = 2";
						$resDET2 	= $this->db->query($sqlDET2)->result();
						$i2			= 0;
						foreach($resDET2 as $row2) :
							$no2		  	= ++$i2;
							$RIDD_DESC2 	= $row2->RIDD_DESC2;
							if($no2 == 1)
							{
								$RIDD_DESC2D	= "$no2. $RIDD_DESC2";
							}
							else
							{
								$RIDD_DESC2D	= "$RIDD_DESC2D<br>$no2. $RIDD_DESC2<br>";
							}
						endforeach;
					
					// Risk Policy List
						$RIDD_DESC3D	= "";
						$sqlDET3	= "SELECT RIDD_DESC3 FROM tbl_riskpolicydet
										WHERE RIDD_CODE3 = '$RID_CODE'
										AND RIDD_PRJCODE3 = '$RID_PRJCODE'
										AND RIDD_GROUP3 = 3";
						$resDET3 	= $this->db->query($sqlDET3)->result();
						$i3			= 0;
						foreach($resDET3 as $row3) :
							$no3		  	= ++$i3;
							$RIDD_DESC3 	= $row3->RIDD_DESC3;
							if($no3 == 1)
							{
								$RIDD_DESC3D	= "$no3. $RIDD_DESC3";
							}
							else
							{
								$RIDD_DESC3D	= "$RIDD_DESC3D<br>$no3. $RIDD_DESC3<br>";
							}
						endforeach;
					
					$secUpd			= site_url('c_riskidentif/c_riskidentif/update/?id='.$this->url_encryption_helper->encode_url($RID_CODE));
					
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
					?> 
                            <td style="text-align:center"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$RID_CODE.'" />'; ?> </td>
                            <td nowrap>
								<?php 
									if($DefEmp_ID == $EMP_ID)
										echo anchor($secUpd,$RID_CODE);
									else
										echo $RID_CODE;
								?>
                            </td>
                            <td><?php echo $RID_PRJCODE; ?> </td>
                            <td><?php echo $RID_DIVDEP; ?> </td>
                            <td><?php echo $RID_CAUSE; ?> </td>
                            <td><?php echo $RIDD_DESC1D; ?> </td>
                            <td><?php echo $RIDD_DESC2D; ?> </td>
                            <td><?php echo $RIDD_DESC3D; ?> </td>
                            <td nowrap><?php echo $EMP_ID; ?> </td>
						</tr>
					<?php 
				endforeach;
			}
			$url_DelRisk	= site_url('c_riskidentif/c_riskidentif/deleteRISK/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
		?>
        </tbody>
        <script>
			var url = "<?php echo $url_DelRisk;?>";
			function checkthis()
			{
				title = 'Select Item';
				w = 1000;
				h = 550;
				//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				var left = (screen.width/2)-(w/2);
				var top = (screen.height/2)-(h/2);
				return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			}
        </script>
        <tfoot>
          <tr>
            <td colspan="7" style="text-align:left">
				<?php 
					//echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />');
					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
					if($DefEmp_ID == 'D15040004221' or 'H17050004765')
					{
						?>
                        	&nbsp;<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-danger" value="Delete [ - ]" onClick="checkthis()" />
                        <?php
					}
				?>
			</td>
          </tr>
        </tfoot>
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

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>