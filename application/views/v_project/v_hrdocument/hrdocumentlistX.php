<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Februari 2017
 * File Name	= hrdocumentlist.php
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$selGROUPDOC1	= $selGROUPDOC1;
$selCLASSDOC1	= $selCLASSDOC1;
$selTYPEDOC1	= $selTYPEDOC1;

$sqlAC 			= "tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
$ressqlAC		= $this->db->count_all($sqlAC);
$sqlA 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
$resultA 		= $this->db->query($sqlA)->result();
foreach($resultA as $rowA) :
	$doc_codeA 	= $rowA->doc_code;
endforeach;

$sqlBC 			= "tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
$ressqlBC		= $this->db->count_all($sqlBC);
$sqlB 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
$resultB 		= $this->db->query($sqlB)->result();
foreach($resultB as $rowB) :
	$doc_codeB	= $rowB->doc_code;
endforeach;

$selKeyDOC		= 1; // 1. GROUP, 2. CLASS, 3. TYPE

	
if(isset($_POST['subChgGROUPDOC']))
{
	$selGROUPDOC1 	= $_POST['selGROUPDOC'];
	
	$sqlAC 			= "tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
	$ressqlAC		= $this->db->count_all($sqlAC);
	
	$sqlBC 			= "tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
	$ressqlBC		= $this->db->count_all($sqlBC);
	
	$sqlA 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
	$resultA 		= $this->db->query($sqlA)->result();
	foreach($resultA as $rowA) :
		$doc_codeA 	= $rowA->doc_code;
	endforeach;
	$selCLASSDOC1 	= $doc_codeA;
	$selTYPEDOC1 	= '';
	
	$sqlB 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
	$resultB 		= $this->db->query($sqlB)->result();
	foreach($resultB as $rowB) :
		$doc_codeB	= $rowB->doc_code;
	endforeach;	
	
	$selKeyDOC		= 1;
}

if(isset($_POST['subChgCLASSDOC']))
{
	$selGROUPDOC1 	= $_POST['selGROUPDOCA'];
	$selCLASSDOC1 	= $_POST['selCLASSDOCA'];
	$selTYPEDOC1 	= '';
	
	$sqlAC 			= "tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
	$ressqlAC		= $this->db->count_all($sqlAC);
	
	$sqlBC 			= "tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
	$ressqlBC		= $this->db->count_all($sqlBC);
	
	$sqlA 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
	$resultA 		= $this->db->query($sqlA)->result();
	foreach($resultA as $rowA) :
		$doc_codeA 	= $rowA->doc_code;
	endforeach;
	
	$sqlB 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
	$resultB 		= $this->db->query($sqlB)->result();
	foreach($resultB as $rowB) :
		$doc_codeB	= $rowB->doc_code;
	endforeach;
	$selTYPEDOC1 	= $doc_codeB;
	
	$selKeyDOC		= 2;
}

if(isset($_POST['subChgTYPEDOC']))
{
	$selGROUPDOC1 	= $_POST['selGROUPDOCB'];
	$selCLASSDOC1 	= $_POST['selCLASSDOCB'];
	$selTYPEDOC1 	= $_POST['selTYPEDOCB'];
	
	$sqlAC 			= "tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
	$ressqlAC		= $this->db->count_all($sqlAC);
	
	$sqlBC 			= "tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
	$ressqlBC		= $this->db->count_all($sqlBC);
	
	$sqlA 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selGROUPDOC1' AND isHRD = 1";
	$resultA 		= $this->db->query($sqlA)->result();
	
	$sqlB 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selCLASSDOC1' AND isHRD = 1";
	$resultB 		= $this->db->query($sqlB)->result();	
	
	$selKeyDOC		= 3;
}

$txtSearch1        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
?>
<script>
	function chooseProject(thisVal)
	{
		proj_Code	= thisVal.value;
		document.frmsrch.submitSrch.click();
	}
</script>
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
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small><?php echo "$PRJCODE - $PRJNAME"; ?></small>
  </h1>
  <br>
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
	<table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr style="background:#CCCCCC">
                <th style="vertical-align:middle; text-align:center" width="13%" nowrap> Doc. Number</th>
                <th style="vertical-align:middle; text-align:center" width="13%" nowrap>Doc. Code</th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap> Date</th>
                <th style="vertical-align:middle; text-align:center" width="32%" nowrap>Project Name</th>
                <th style="vertical-align:middle; text-align:center" width="9%" nowrap>Doc. Location</th>
                <th style="vertical-align:middle; text-align:center" width="7%" nowrap>Type</th>
                <th style="vertical-align:middle; text-align:center" width="5%" nowrap>Action</th>
            </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			if($recordcount >0)
			{	
				foreach($viewdocument as $row) :
					$myNewNo = ++$i;
					$empID			= '';
					$HRDOCNO		= $row->HRDOCNO;
					$HRDOCCODE		= $row->HRDOCCODE;
					$HRDOCTYPE		= $row->HRDOCTYPE;
					$TRXDATE		= $row->TRXDATE;
					$PRJCODE		= $row->PRJCODE;
					$PRJNAME		= $row->PRJNAME;
					$HRDOCCOST		= 0;
					$HRDOCJNS		= $row->HRDOCJNS;
					if($HRDOCJNS == 1)
					{
						$HRDOCJNS	= "LEMBAR";
					}
					elseif($HRDOCJNS == 2)
					{
						$HRDOCJNS	= "BUKU";
					}
					elseif($HRDOCJNS == 3)
					{
						$HRDOCJNS	= "BUKU TIPIS";
					}
					else
					{
						$HRDOCJNS	= $HRDOCJNS;
					}
					$HRDOCLOK		= $row->HRDOCLOK;
					$HRDOC_NAME		= $row->HRDOC_NAME;
					$TRXDATEa		= $row->HRDOC_CREATED;
					$TRXDATE		= date('Y-m-d',strtotime($TRXDATEa));
					
					$secURLPI		= site_url('c_project/hrdocument/update/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
                	?>   	
						<tr>
                            <td nowrap> <?php echo anchor("$secURLPI",$HRDOCNO,array('class' => 'update')).' '; ?> </td>
                            <td nowrap> <?php echo $HRDOCCODE; ?> </td>
                            <td style="text-align:center" nowrap> <?php echo $TRXDATE; ?> </td>
                            <td><?php echo $PRJNAME; ?></td>
                            <td style="text-align:center; text-transform:uppercase"> <?php echo $HRDOCLOK; ?> </td>
                            <td style="text-align:center"><?php echo $HRDOCJNS; ?></td>
                            <td style="text-align:center" nowrap>
                                <?php
                                    if($HRDOC_NAME == '')
                                    {
										$secUplURL		= site_url('c_project/hrdocument/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
                                        ?>
                                            <input type="hidden" name="secUplURL_<?php echo $myNewNo; ?>" id="secUplURL_<?php echo $myNewNo; ?>" value="<?php echo $secUplURL; ?>"/>
                                            <a href="javascript:void(null);" onClick="selectPICT(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-success btn-xs" title="Upload Document">
                                            	<i class="fa fa-upload"></i>
                                            </a>
                                        <?php
                                    }
                                    else
                                    {
                                        $FileUpName = $HRDOC_NAME;
										$secUplURL	= site_url('c_project/hrdocument/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
                                        ?>
                                            <input type="hidden" name="FileUpName<?php echo $myNewNo; ?>" id="FileUpName<?php echo $myNewNo; ?>" value="<?php echo $FileUpName; ?>" />
                                            <a href="javascript:void(null);" onClick="typeOpenNewTab(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-success btn-xs" title="Download Document">
                                            	<i class="fa fa-eye"></i>
                                            </a>
                                        <?php
                                    }
                                ?>
							</td>
						</tr>
					<?php 
				endforeach; 
			}
			?>
        </tbody>
			<tr>
				<td colspan="7" nowrap>
                <?php
					$secAddURL	= site_url('c_project/hrdocument/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					echo anchor("$secAddURL",'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add Document" />');
				?>
				<?php 
					if ( ! empty($link))
					{
						foreach($link as $links)
						{
							echo $links;
						}
					}
				?>
				</td>
			</tr>
			<?php
		?>
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
	$secOpen 	= base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName=';
?>
<script>
	function chgGROUPDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOC').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOC').value = '';
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOC').value = '';
		
		document.getElementById('subChgGROUPDOC').click();
	}
	
	function chgCLASSDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOCA').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOCA').value = selCLASSDOC;
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOCA').value = '';
		
		document.getElementById('subChgCLASSDOC').click();
	}
	
	function chgTYPEDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOCB').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOCB').value = selCLASSDOC;
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOCB').value = selTYPEDOC;
		
		document.getElementById('subChgTYPEDOC').click();
	}
	
	function selectPICT(thisVal)
	{
		var urlUplPICT = document.getElementById('secUplURL_'+thisVal).value;
		title = 'Select Item';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlUplPICT, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	var urlOpen = "<?php echo $secOpen;?>";
	var urlOpenx = "<?php echo base_url();?>";
	function typeOpenNewTab(thisVal)
	{
		var myFileName	= document.getElementById('FileUpName'+thisVal).value;
		var FileUpName	= ''+myFileName+'&base_url='+urlOpenx;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlOpen+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	/*function typeOpenNewTab(thisVal)
	{
		var myFileName	= document.getElementById('FileUpName'+thisVal).value;
		var FileUpName	= ''+myFileName;
		alert(FileUpName)
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlOpen+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}*/
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>