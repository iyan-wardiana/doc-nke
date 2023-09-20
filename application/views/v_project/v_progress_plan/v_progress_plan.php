<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= item_list.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Create Date	= 23 November 2017
 * File Name	= c_progress_plan.php
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
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'PlanDate')$PlanDate = $LangTransl;
		if($TranslCode == 'Progress')$Progress = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'RealisationsDate')$RealisationsDate = $LangTransl;

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

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped" width="100%">
            	<thead>
                <tr>
                    <!--<th width="4%"  style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>-->
                    <th width="13%"  style="vertical-align:middle; text-align:center"><?php echo $Code ?></th>
                    <th width="24%"  style="vertical-align:middle; text-align:center"><?php echo $Project ?> </th>
                    <th width="18%" style="vertical-align:middle; text-align:center"><?php echo $PlanDate ?> </th>
                    <th width="17%" style="vertical-align:middle; text-align:center"><?php echo $Progress ?> </th>
                    <th width="15%" style="vertical-align:middle; text-align:center"><?php echo $Amount ?> </th>
                    <th width="13%"  style="vertical-align:middle; text-align:center" nowrap><?php echo $RealisationsDate ?> </th>
                </tr>
  
                </thead>
                <tbody> 
                    <?php 
                    $i = 0;
					$j = 0;
                    if($recordcount > 0)
                    {
                        //$Unit_Type_Name2	= '';
						foreach($viewitemlist as $row) :
							$myNewNo 		= ++$i;
							$MCP_CODE 		= $row->MCP_CODE;
							$MCP_PRJCODE 	= $row->MCP_PRJCODE;
							$MCP_DATE		= $row->MCP_DATE;
							$MCP_PROG 		= $row->MCP_PROG;
							$MCP_AMOUNT 	= $row->MCP_AMOUNT;			
							$MCR_DATE		= $row->MCR_DATE;
							
											
							$secUpd		= site_url('c_project/c_progress_plan/update/?id='.$this->url_encryption_helper->encode_url($MCP_CODE));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <!--<td style="text-align:center"> <?php //print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$MCP_CODE.'" />'; ?> </td>-->
                                    <!--<td nowrap> <?php// print anchor("$secUpd",$MCP_CODE,array('class' => 'update')).' '; ?> </td>-->
                                    <td> <?php print anchor("$secUpd",$MCP_CODE,array('class' => 'update')).' '; ?> </td>
                                   
                                    <?php
									
										// Project List
										$sqlPLC	= "tbl_project";
										$resPLC	= $this->db->count_all($sqlPLC);
										
										$sqlPL 	= "SELECT PRJCODE, PRJNAME
													FROM tbl_project WHERE PRJCODE = '$MCP_PRJCODE'
													ORDER BY PRJNAME";
										$resPL	= $this->db->query($sqlPL)->result();
										
										if($resPLC > 0)
                                        {
                                            foreach($resPL as $rowPL) :
                                                $PRJCODE1 = $rowPL->PRJCODE;
                                                $PRJNAME1 = $rowPL->PRJNAME;
                                            endforeach;
									?>
                                    
									<td style="text-align:center"><?php print "$PRJCODE1 - $PRJNAME1"; ?>&nbsp;</td>
                                    <?php
                                        }                                    	
                                    ?>
                                    <td style="text-align:center"><?php print $MCP_DATE ?>&nbsp;</td>
                                    <td style="text-align:right"><?php print number_format($MCP_PROG, $decFormat); ?>&nbsp;</td>
                                    <td style="text-align:right"><?php print number_format($MCP_AMOUNT, $decFormat); ?>&nbsp;</td>
                                    <td style="text-align:center"><?php print $MCR_DATE; ?>&nbsp;</td>

                                </tr>
                                <?php 
                        endforeach;
                    }
					$secAddURL = site_url('c_project/c_progress_plan/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					
					$backURL = site_url('c_project/c_progress_plan/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                    ?> 
                </tbody>
                <tr>
                    <td colspan="6">
                    	<?php
							if($ISCREATE == 1)
							{
								echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button> &nbsp;' );
							}
							
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
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