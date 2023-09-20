<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 23 April 2018
 * File Name	= v_inb_amd_list.php
 * Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJCODE		= $PRJCODE;
?>

<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
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
		if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'PONo')$PONo = $LangTransl;
		if($TranslCode == 'ReceiveNo')$ReceiveNo = $LangTransl;
		if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
		if($TranslCode == 'Invoiced')$Invoiced = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Direct')$Direct = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$sureDelete	= "Anda yakin akan menghapus data ini?";
	}
	else
	{
		$sureDelete	= "Are your sure want to delete?";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
        <small><?php echo $h3_title; ?></small>
        </h1><br>
    </section>

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
		<div class="box-body">
            <div class="search-table-outter">
                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                    <thead>
                    <tr>
                        <th width="5%" style="display:none"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                        <th width="6%" style="display:none">Nomor</th>
                        <th width="12%" nowrap><?php echo $Code; ?>  </th>
                        <th width="4%"><?php echo $Date; ?> </th>
                        <th width="38%" nowrap><?php echo $JobDescription; ?> </th>
                        <th width="26%"><?php echo $Notes; ?> </th>
                        <th width="3%" nowrap><?php echo $Status; ?> </th>
                        <th width="6%" nowrap>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody> 
                        <?php 
                        $i = 0;
                        $j = 0;
                        if($cData > 0)
                        {
                            foreach($vData as $row) :
                                $myNewNo 		= ++$i;
                                $AMD_ID 	= $row->AMD_ID;
                                $AMD_NUM 	= $row->AMD_NUM;
                                $AMD_CODE 	= $row->AMD_CODE;
                                $AMD_DATE 	= $row->AMD_DATE;
                                $PRJCODE	= $row->PRJCODE;
                                $AMD_DESC	= $row->AMD_DESC;
                                $AMD_NOTES 	= $row->AMD_NOTES;
                                $AMD_MEMO 	= $row->AMD_MEMO;
                                $AMD_AMOUNT = $row->AMD_AMOUNT;
                                $AMD_STAT	= $row->AMD_STAT;
                                $JOBDESC	= $row->JOBDESC;							
								
                                $secUpd			= site_url('c_project/c_am1h0db2/update_inb/?id='.$this->url_encryption_helper->encode_url($AMD_NUM));
								if($AMD_STAT == 1)
								{
									$AMD_STATD 		= 'New';
									$STATCOL		= 'warning';
								}
								elseif($AMD_STAT == 2)
								{
									$AMD_STATD 		= 'Confirm';
									$STATCOL		= 'primary';
								}
								elseif($AMD_STAT == 3)
								{
									$AMD_STATD 		= 'Approved';
									$STATCOL		= 'success';
								}
								elseif($AMD_STAT == 4)
								{
									$AMD_STATD 		= 'Revised';
									$STATCOL		= 'warning';
								}
								elseif($AMD_STAT == 5)
								{
									$AMD_STATD 		= 'Reject';
									$STATCOL		= 'danger';
								}
								elseif($AMD_STAT == 6)
								{
									$AMD_STATD 		= 'Closed';
									$STATCOL		= 'info';
								}
								elseif($AMD_STAT == 7)
								{
									$AMD_STATD 		= 'Awaiting';
									$STATCOL		= 'warning';
								}
								else
								{
									$AMD_STATD 		= 'fake';
									$STATCOL		= 'danger';
								}
                                
                                if ($j==1) {
                                    echo "<tr class=zebra1>";
                                    $j++;
                                } else {
                                    echo "<tr class=zebra2>";
                                    $j--;
                                }
                                
                        		?>
                                    <td style="text-align:center; display:none" nowrap> <?php print '<input name="chkDetail" id="chkDetail" type="radio" value="'.$AMD_NUM.'" />'; ?> </td>
                                    <td nowrap style="display:none"> <?php print $AMD_NUM; ?> </td>
                                    <td nowrap> <?php print $AMD_CODE; ?> </td>
                                    <td style="text-align:center" nowrap> <?php print date('d M Y', strtotime($AMD_DATE)); ?> </td>
                                    <td nowrap style="text-align:left">  <?php print $JOBDESC; ?> </td>
                                    <td nowrap> <?php print $AMD_NOTES; ?> </td>
                                    <td nowrap style="text-align:center">
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                            <?php
                                                echo $AMD_STATD;
                                             ?>
                                         </span>
                                    </td>
                                    <td nowrap style="text-align:center">
                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                        <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($AMD_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                           	    </tr>
                            	<?php 
                   			endforeach;
                		}
                        $url_add 	= site_url('c_project/c_am1h0db2/i180dahdd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?> 
                    </tbody>
                </table>
            </div>
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
	
	function printDocument(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>