<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Agustus 2017
 * File Name	= stock_opn_list.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;


$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Action')$Action = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $PRJNAME; ?></small>
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
        <?php /*?><div class="box-header with-border">
            <h3 class="box-title"><?php echo $PRJNAME; ?></h3>
        </div><?php */?>
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		<thead>
            <tr>
                <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                <th style="vertical-align:middle; text-align:center" width="14%" nowrap><?php echo $Code ?>  </th>
                <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date ?>  </th>
                <th style="vertical-align:middle; text-align:center" width="9%" nowrap><?php echo $Project ?> </th>
                <th width="48%" style="vertical-align:middle; text-align:center"><?php echo $Notes ?> </th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $Status ?>  </th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $Action ?> </th>
        </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			$PRJCODE1 = "$PRJCODE";
						
			if($recCount >0)
			{
			foreach($vwStockOpn as $row) :				
				$myNewNo1 		= ++$i;
				$SOPNH_CODE		= $row->SOPNH_CODE;
				$SOPNH_DATE		= $row->SOPNH_DATE;
				$SOPNH_PRJCODE	= $row->SOPNH_PRJCODE;
				$SOPNH_PERIODE	= $row->SOPNH_PERIODE;
				$SOPNH_WH		= $row->SOPNH_WH;
				$SOPNH_NOTES	= $row->SOPNH_NOTES;
				$SOPNH_STAT		= $row->SOPNH_STAT;
				
				if($SOPNH_STAT == 0) $SOPNH_STATDesc = "fake";
				elseif($SOPNH_STAT == 1)$SOPNH_STATDesc = "New";
				elseif($SOPNH_STAT == 2) $SOPNH_STATDesc = "Confirm";
				elseif($SOPNH_STAT == 3) $SOPNH_STATDesc = "Approved";
				elseif($SOPNH_STAT == 4) $SOPNH_STATDesc = "Close";
				elseif($SOPNH_STAT == 5) $SOPNH_STATDesc = "Reject";
				
				if($SOPNH_STAT == 0)
				{
					$SOPNH_STATD = 'fake';
					$STATCOL	= 'danger';
				}
				elseif($SOPNH_STAT == 1)
				{
					$SOPNH_STATD = 'New';
					$STATCOL	= 'warning';
				}
				elseif($SOPNH_STAT == 2)
				{
					$SOPNH_STATD = 'Confirm';
					$STATCOL	= 'primary';
				}
				elseif($SOPNH_STAT == 3)
				{
					$SOPNH_STATD 	= 'Approve';
					$STATCOL	= 'success';
					
					// SAVE TO PROFLOSS
					// Check untuk bulan yang sama
						$YEARP	= date('Y',strtotime($SOPNH_DATE));
						$MONTHP	= (int)date('m',strtotime($SOPNH_DATE));
					// BUAT TANGGAL AKHIR BULAN PER SI
						$LASTDATE	= date('Y-m-t', strtotime($SOPNH_DATE));
						
					$sqlPL	= "tbl_profitloss 
								WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
					$resPL	= $this->db->count_all($sqlPL);
					if($resPL == 0)
					{
						// GET PRJECT DETAIL			
							$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$SOPNH_PERIODE'";
							$resPRJ	= $this->db->query($sqlPRJ)->result();
							foreach($resPRJ as $rowPRJ) :
								$PRJNAME 	= $rowPRJ->PRJNAME;
								$PRJCOST 	= $rowPRJ->PRJCOST;
							endforeach;
							
						// GET WIP TOTAL PER MONTH
							$RSOPN_REMP_M = 0;	// Material
							$sql_15		= "SELECT RSOPN_REMP
											FROM tbl_sopn_concl
											WHERE RSOPN_PRJCODE = '$PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
												AND RSOPN_YEAR = $YEARP AND RSOPN_MONTH = $MONTHP";
							$res_15 	= $this->db->query($sql_15)->result();
							foreach($res_15 as $row_15) :
								$RSOPN_REMP_M = $row_15->RSOPN_REMP;
							endforeach;
						
						// SAVE TO PROFITLOSS
							$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, STOCK_OPN)
										VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$RSOPN_REMP_M')";
							$this->db->query($insPL);
					}
					else
					{
						// GET WIP TOTAL PER MONTH
							$RSOPN_REMP_M = 0;	// Material
							$sql_15		= "SELECT RSOPN_REMP
											FROM tbl_sopn_concl
											WHERE RSOPN_PRJCODE = '$PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
												AND RSOPN_YEAR = $YEARP AND RSOPN_MONTH = $MONTHP";
							$res_15 	= $this->db->query($sql_15)->result();
							foreach($res_15 as $row_15) :
								$RSOPN_REMP_M = $row_15->RSOPN_REMP;
							endforeach;
							
						// SAVE TO PROFITLOSS
							$updPL	= "UPDATE tbl_profitloss SET STOCK_OPN = '$RSOPN_REMP_M'
										WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
							$this->db->query($updPL);
					}
				}
				elseif($SOPNH_STAT == 4)
				{
					$SOPNH_STATD 	= 'Close';
					$STATCOL	= 'success';
				}
				else
				{
					$SOPNH_STATD = 'Not Detected';
					$STATCOL	= 'danger';
				}
				
				$secUpd			= site_url('c_gl/profit_loss/update/?id='.$this->url_encryption_helper->encode_url($SOPNH_CODE));
						
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
					?> 
                            <td style="text-align:center">
                            <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $SOPNH_CODE;?>" onClick="getValueNo(this);" /></td>
                            <td nowrap><?php echo anchor($secUpd,$SOPNH_CODE);?></td>
                            <td style="text-align:center">
								<?php
                                    $date = new DateTime($SOPNH_DATE);
                                   // echo $date->format('d F Y');
								   echo $SOPNH_DATE;
                                ?>                            </td>
                            <td> <?php echo $SOPNH_PRJCODE; ?></td>
                            <td><?php echo $SOPNH_NOTES; ?> </td>
                            <td style="text-align:center">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
								<?php 
                                    echo "&nbsp;&nbsp;$SOPNH_STATDesc&nbsp;&nbsp;";
                                 ?>
                            </span>
                            </td>
                            <td style="text-align:center"> <?php //echo $SOPNH_STATDesc; ?></td>
                        </tr>
					<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tr>
          <td colspan="7">
            <?php
				$theProjCode = $PRJCODE;
				if($ISCREATE == 1)
				{
					$secAddURL	= site_url('c_gl/profit_loss/add/?id='.$this->url_encryption_helper->encode_url($theProjCode));
					//echo anchor("$secAddURL",'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />&nbsp;');
					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>');
				}
				?>
                <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />&nbsp;
            <?php
    
			echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
            ?>
            </td>
        </tr>
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
	$urlProjInDet	= site_url('c_project/listproject/vInpProjDet/?id='.$this->url_encryption_helper->encode_url($theProjCode));
	$urlProjInDet	= site_url('c_project/listproject/vProjPerform/?id='.$this->url_encryption_helper->encode_url($theProjCode));
?>   
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	function printDocument()
	{
		myVal = document.getElementById('myMR_Number').value;
		if(myVal == '')
		{
			alert('Please select one MR Number.')
			return false;
		}
		var url = '<?php echo base_url().'index.php/c_project/material_request/printdocument/';?>'+myVal;
		title = 'Select Item';
		w = 1000;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>