<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Januari 2018
 * File Name	= v_zona.php
 * Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

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
	if($TranslCode == 'Save')$Save = $LangTransl;
	if($TranslCode == 'Update')$Update = $LangTransl;	
	if($TranslCode == 'Back')$Back = $LangTransl;
	if($TranslCode == 'Code')$Code = $LangTransl;
	if($TranslCode == 'Zone')$Zone = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'ZonePerc')$ZonePerc = $LangTransl;
	if($TranslCode == 'Criteria')$Criteria = $LangTransl;
	if($TranslCode == 'Percentation')$Percentation = $LangTransl;
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
        <?php echo $Zone; ?>
        <small>Master</small>
        </h1><br>
        <?php /*?><ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
        </ol><?php */?>
    </section>

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                    <th width="2%" nowrap>No</th>
                    <th width="3%"><?php echo $Code; ?></th>
                    <th width="38%"><?php echo $Description; ?></th>
                    <th width="10%" nowrap><?php echo $ZonePerc; ?></th>
                    <th width="31%"><?php echo $Criteria; ?></th>
                    <th width="11%"><?php echo $Percentation; ?></th>
                    <th width="5%">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $noUrut = 0;
                $j = 0;
                if($recordcount >0)
                {
                    foreach($viewZone as $row) : 
                        $noUrut			= $noUrut + 1;
                        $ZN_ID 			= $row->ZN_ID;
                        $ZN_CODE 		= $row->ZN_CODE;
                        $ZN_DESC 		= $row->ZN_DESC;
                        $ZN_PERC 		= $row->ZN_PERC;
                        $CRITA_CODE		= $row->CRITA_CODE;
                        $CRITA_PERC		= $row->CRITA_PERC;
						$CRITA_DESC		= '';
						$sqlCA	= "SELECT CRITA_DESC FROM tbl_criteria_allow WHERE CRITA_CODE = '$CRITA_CODE'";
						$resCA	= $this->db->query($sqlCA)->result();
						foreach($resCA as $rowCA):
							$CRITA_DESC	= $rowCA->CRITA_DESC;
						endforeach;
                        $secUpd		= site_url('c_setting/c_zona/update/?id='.$this->url_encryption_helper->encode_url($ZN_ID));
                            
                        if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }
                        ?>
                            <td style="text-align:center" nowrap> <?php echo $noUrut; ?>.</td>
                            <td><?php echo anchor($secUpd,$ZN_CODE);?></td>
                            <td><?php echo "$ZN_DESC"; ?></td>
                            <td style="text-align:right"><?php echo number_format($ZN_PERC,2); ?></td>
                            <td><?php echo "$CRITA_CODE - $CRITA_DESC"; ?></td>
                            <td style="text-align:right"><?php echo number_format($CRITA_PERC, 2); ?></td>
                            <td nowrap>
                                <a href="<?php echo $secUpd; ?>" class="btn btn-warning btn-xs" title="Update">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </a>
                                <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                        	</td>
                        </tr>
                        <?php
                    endforeach; 
                }
                $secAddURL = site_url('c_setting/c_zona/add/?id='.$this->url_encryption_helper->encode_url($appName));
                ?>
                </tbody>
                <tr>
                  <td style="text-align:left" colspan="7">
                    <?php 
						//echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />');
						
						echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
					?>
                  </td>
                </tr>
            </table>
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