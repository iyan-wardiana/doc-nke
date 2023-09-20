<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= inb_listproject.php
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

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}
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
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'Owner')$Owner = $LangTransl;
		if($TranslCode == 'ContractAmount')$ContractAmount = $LangTransl;
		if($TranslCode == 'LastContractAmount')$LastContractAmount = $LangTransl;
		if($TranslCode == 'SITotal')$SITotal = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
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
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner">
        <thead>
            <tr>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
              	<th width="18%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ProjectName ?> </th>
                <th width="21%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Owner ?> </th>
                <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ContractAmount ?>  <br />(IDR)</th>
                <th width="2%" style="text-align:center; vertical-align:middle" nowrap><?php echo $LastContractAmount ?> <br />(IDR)</th>
                <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $SITotal ?> <br />(IDR)</th>
                <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $StartDate ?> </th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $EndDate ?> </th>
                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
             </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($recordcount >0)
			{
			foreach($vewproject as $row) :
				$myNewNo 		= ++$i;
				$PRJCODE 		= $row->PRJCODE;
				$PRJCNUM		= $row->PRJCNUM;
				$PRJNAME		= $row->PRJNAME;
				$PRJLOCT		= $row->PRJLOCT;
				$PRJCOST		= $row->PRJCOST;
				$PRJCOST2		= $row->PRJCOST2;
				
				if($PRJCOST2 < 1)
				{
					$PRJCOST2	= $PRJCOST;
				}
				$PRJDATE		= $row->PRJDATE;
				$PRJEDAT		= $row->PRJEDAT;
				
				$sqlAC 			= "tbl_projhistory WHERE PRJCODE = '$PRJCODE'";
				$ressqlAC		= $this->db->count_all($sqlAC);
				
				$sqlsp0			= "SELECT ENDDATE FROM tbl_projhistory WHERE PRJCODE = '$PRJCODE'";
				$sqlsp0R		= $this->db->query($sqlsp0)->result();
				foreach($sqlsp0R as $rowsp0) :
					$ENDDATE		= $rowsp0->ENDDATE;
				endforeach;
				if($ressqlAC > 0)
				{
					$PRJEDAT		= $ENDDATE;
				}
				
				if($PRJEDAT == '')
				{
					$PRJEDAT	= date('Y-m-d');
				}
				$myDateProj 	= $row->PRJDATE;		
				$PRJSTAT		= $row->PRJSTAT;
					if($PRJSTAT == 0) $PRJSTATDesc = "New";
					elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE
								FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE		= $rowx->PRJDATE;
						endforeach;
					}
							
				$isActif = $row->PRJSTAT;
				if($isActif == 1)
				{
					$isActDesc = 'Active';
				}
				else
				{
					$isActDesc = 'In Active';
				}
				
				$PRJOWN			= $row->PRJOWN;
				$ownerName		= "";
					$sqlX 		= "SELECT own_Title, own_Name
									FROM tbl_owner WHERE own_Code = '$PRJOWN'";
					$result 	= $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$own_Title		= $rowx->own_Title;
						$own_Name		= $rowx->own_Name;
						if($own_Title != '')
						{
							$ownerName	= "$own_Title $own_Name";
						}
					endforeach;
				
				$TOTSIPRJ	= 0;
				$sqlGetSI	= "SELECT SUM(SI_APPVAL) AS TOTSIPROJECT FROM tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 2";
				$resGetSI	= $this->db->query($sqlGetSI)->result();
				foreach($resGetSI as $rowSIP) :
					$TOTSIPRJ	= $rowSIP->TOTSIPROJECT;
				endforeach;
				
				$PRJCODE = $row->PRJCODE;
				$secUpd			= site_url('c_asset/c_asset_usage/inbox_usagelist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
				?> 
                            <td><?php echo anchor($secUpd,$PRJCODE);?></td>
                            <td nowrap> <?php print $PRJNAME; ?> </td>
                            <td title="<?php echo $ownerName; ?>" > <?php print $PRJOWN; ?> </td>
                            <td nowrap style="text-align:right"> - <?php //print number_format($PRJCOST, 0); ?></td>
                            <td nowrap style="text-align:right"> - <?php //print number_format($PRJCOST2, 0); ?></td>
                            <td nowrap style="text-align:right"> - <?php //print number_format($TOTSIPRJ, 0); ?></td>
                            <td nowrap> <?php print date('d M Y', strtotime($PRJDATE)); ?> </td>
                            <td nowrap> <?php print date('d M Y', strtotime($PRJEDAT)); ?> </td>
                            <td nowrap> <?php print $isActDesc; ?> </td>
                        </tr>
					<?php 
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

<?php
	//$urlProjInDet	= site_url('c_project/listproject/vInpProjDet/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	//$urlProjInDet	= site_url('c_project/listproject/vProjPerform/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
?>
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal;
		document.getElementById('myProjCode').value = myValue;
		document.getElementById('selProject').value = myValue;
		chooseProject(thisVal);
	}
	
	function chooseProject(thisVal)
	{
		document.frmselect.submit.click();
	}
		
	function vProjPerform()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjPerF; ?>';
		title = 'Select Item';		
		
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+screen.width+', height='+screen.height);
	}
		
	function vInpProjDet()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjInDet; ?>';
		title = 'Select Item';		
		w = 900;
		h = 550;
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