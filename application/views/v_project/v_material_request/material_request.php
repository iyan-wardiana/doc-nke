<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Februari 2017
 * File Name	= material_request.php
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

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
	$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
		if($TranslCode == 'SPPCode')$SPPCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'RequestedBy')$RequestedBy = $LangTransl;
		if($TranslCode == 'RequestStatus')$RequestStatus = $LangTransl;
		if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;

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
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner">
		<thead>
            <tr>
                <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                <th style="vertical-align:middle; text-align:center" width="14%" nowrap><?php echo $RequestCode ?> </th>
                <th style="vertical-align:middle; text-align:center" width="9%" nowrap><?php echo $SPPCode ?>  </th>
                <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date ?>  </th>
                <th width="48%" style="vertical-align:middle; text-align:center"><?php echo $RequestedBy ?> </th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $RequestStatus ?> </th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $ApprovalStatus ?> </th>
        </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			$PRJCODE1 = "$PRJCODE";
			
			$dataSessSrc = array(
					'selSearchPRJCODE' => $PRJCODE1,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
						
			if($recordcount >0)
			{
			foreach($viewprojmatreq as $row) :				
				$myNewNo1 			= ++$i;
				$SPPNUM				= $row->SPPNUM;
				$SPPCODE			= $row->SPPCODE;
				$TRXDATE			= $row->TRXDATE;
				$PRJCODE			= $row->PRJCODE;
				$TRXOPEN			= $row->TRXOPEN;
				$TRXUSER			= $row->TRXUSER;
				$APPROVE			= $row->APPROVE;
				$APPRUSR			= $row->APPRUSR;
				$JOBCODE			= $row->JOBCODE;
				$proj_Number		= $row->proj_Number;
				$PRJNAME			= $row->PRJNAME;
				$SPPNOTE			= $row->SPPNOTE;
				$SPPSTAT			= $row->SPPSTAT;
				
				if($SPPSTAT == 0) $SPPSTATDesc = "fake";
				elseif($SPPSTAT == 1) $SPPSTATDesc = "New";
				elseif($SPPSTAT == 2) $SPPSTATDesc = "Confirm";
				elseif($SPPSTAT == 3) $SPPSTATDesc = "Approved";
				elseif($SPPSTAT == 4) $SPPSTATDesc = "Close";
				elseif($SPPSTAT == 5) $SPPSTATDesc = "Reject";
				
				// 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
				if($APPROVE == 0) $APPROVEDes = "fake";
				elseif($APPROVE == 1) $APPROVEDes = "New";
				elseif($APPROVE == 2) $APPROVEDes = "Awaiting";
				elseif($APPROVE == 3) $APPROVEDes = "Approve";
				elseif($APPROVE == 4) $APPROVEDes = "Revise";
				elseif($APPROVE == 5) $APPROVEDes = "Reject";	
				
				$secUpd			= site_url('c_project/material_request/update/?id='.$this->url_encryption_helper->encode_url($SPPNUM));
						
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
					?> 
                            <td style="text-align:center">
                            <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $SPPNUM;?>" onClick="getValueNo(this);" /></td>
                            <td nowrap><?php echo anchor($secUpd,$SPPNUM);?></td>
                            <td> <?php print $SPPCODE; ?></td>
                            <td style="text-align:center"><?php
                                    $date = new DateTime($TRXDATE);
                                   // echo $date->format('d F Y');
								   echo $TRXDATE;
                                ?>                            </td>
                            <td><?php print ""; ?> </td>
                            <td style="text-align:center"> <?php print $SPPSTATDesc; ?> </td>
                            <td style="text-align:center"> <?php print $APPROVEDes; ?></td>
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
				$secAddURL	= site_url('c_project/material_request/add/?id='.$this->url_encryption_helper->encode_url($theProjCode));
                /*echo anchor("$secAddURL",'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add New" />'); ?>&nbsp;*/
                
                echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>');
				?>
                &nbsp;
                <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
                <!--<input type="button" name="btnDelete" id="btnDelete" class="btn btn-warning" value="Print Document" onClick="printDocument();" />&nbsp;-->
                <button type="button" onClick="printDocument();" class="btn btn-primary"><i class="cus-print-16x16"></i>&nbsp;&nbsp;<?php echo $Print; ?></button>&nbsp;
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