<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= hrdocument.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_color = $this->session->userdata('comp_color');
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
    <title><?php echo $appName; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
</head>

<?php
	// $this->load->view('template/topbar');
	// $this->load->view('template/sidebar');

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
		if($TranslCode == 'DocumentTypeName')$DocumentTypeName = $LangTransl;
		if($TranslCode == 'FileQty')$FileQty = $LangTransl;

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
            <tr>
                <th width="2%">&nbsp;</th>
              <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
           	  <th width="89%" style="text-align:center; vertical-align:middle" nowrap><?php echo $DocumentTypeName ?> </th>
           	  <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $FileQty ?> </th>
          </tr>
        </thead>
        <tbody>
		<?php
			$sqlTypeC		= "tbl_document WHERE doc_code IN (SELECT doc_code FROM tbl_userdoctype WHERE emp_id = '$DefEmp_ID') 
								AND doc_parent = '$parentCode'
								AND isHRD = 1 
								AND doc_level = 4";
			$resTypeC		= $this->db->count_all($sqlTypeC);
	
			$sqlType		= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_code IN (SELECT doc_code FROM tbl_userdoctype WHERE emp_id = '$DefEmp_ID') 
								AND doc_parent = '$parentCode'
								AND isHRD = 1 
								AND doc_level = 4";
			$resType 		= $this->db->query($sqlType)->result();
			
			$i = 0;
			$j = 0;
			if($resTypeC >0)
			{
				foreach($resType as $rowType) :
					$myNewNo 		= ++$i;
					$doc_code	= $rowType->doc_code;
					$doc_name	= $rowType->doc_name;
					
					// Count file
					$sqlC		= "tbl_hrdoc_header WHERE DOCCODE = '$doc_code'";
					$ressqlC	= $this->db->count_all($sqlC);			
					if($ressqlC == 0)
						$theColor	= "danger";			
					else
						$theColor	= "success";
						
					$secGetDoc		= site_url('c_project/hrdocument/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($doc_code));
						
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
					?> 
								<td style="text-align:center"><?php echo $myNewNo; ?></td>
								<td><?php echo anchor($secGetDoc,$doc_code);?></td>
								<td nowrap> <?php echo $doc_name; ?> </td>
								<td style="text-align:center" nowrap>
                                    <a href="" data-skin="skin-green" class="btn btn-<?php echo $theColor; ?> btn-xs" >
                                    	<?php echo $ressqlC; ?>
                                    </a>
                                </td>
						    </tr>
						<?php 
				endforeach; 
			}
		?>
        </tbody>
			<tr>
				<td colspan="4" nowrap>
                <?php
					echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
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
<?php
	$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if($DefEmp_ID == 'D15040004221')
		echo "<font size='1'><i>$act_lnk</i></font>";
?>
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
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>