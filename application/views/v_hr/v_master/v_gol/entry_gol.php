<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Oktober 2017
 * File Name	= entry_gol.php
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
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
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
		
		if($TranslCode == 'Nomor')$Nomor = $LangTransl;
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Parent')$Parent = $LangTransl;
		if($TranslCode == 'ChildCode')$ChildCode = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'BasicSalary')$BasicSalary = $LangTransl;
		if($TranslCode == 'PositionAllowance')$PositionAllowance = $LangTransl;
		if($TranslCode == 'HealthAllowance')$HealthAllowance = $LangTransl;
		if($TranslCode == 'CommunicAllow')$CommunicAllow = $LangTransl;
		if($TranslCode == 'AcommAllow')$AcommAllow = $LangTransl;
		if($TranslCode == 'MealAllow')$MealAllow = $LangTransl;
		if($TranslCode == 'OtherAllow')$OtherAllow = $LangTransl;
		if($TranslCode == 'ListGol')$ListGol = $LangTransl;
		if($TranslCode == 'Group')$Group = $LangTransl;
		if($TranslCode == 'Koef')$Koef = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Rank')$Rank = $LangTransl;
	endforeach;
?>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $ListGol; ?>
        <small><?php echo $Group; ?></small>
        </h1><br>
    </section>

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
        	<div class="search-table-outter">
            <table id="example1" class="table table-bordered table-striped" width="100%">
            	<thead>
                <tr>
                    <th width="31" style="vertical-align:middle; text-align:center" nowrap><?php echo $Nomor; ?></th>
                    <th width="146" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?></th>
                    <th width="76" style="vertical-align:middle; text-align:center" nowrap><?php echo $Parent; ?></th>
                    <th width="108" style="vertical-align:middle; text-align:center" nowrap><?php echo $ChildCode; ?></th>
                    <th width="76" style="vertical-align:middle; text-align:center" nowrap><?php echo $Rank; ?></th>
                    <th width="135" style="vertical-align:middle; text-align:center" nowrap><?php echo $BasicSalary; ?></th>
                    <th width="146" style="vertical-align:middle; text-align:center" nowrap><?php echo $PositionAllowance; ?></th>
                    <th width="112" style="vertical-align:middle; text-align:center" nowrap><?php echo $HealthAllowance; ?></th>
                    <th width="94" style="vertical-align:middle; text-align:center" nowrap><?php echo $CommunicAllow; ?></th>
                    <th width="90" style="vertical-align:middle; text-align:center" nowrap><?php echo $AcommAllow; ?></th>
                    <th width="63" style="vertical-align:middle; text-align:center" nowrap><?php echo $MealAllow; ?></th>
                    <th width="18" style="vertical-align:middle; text-align:center" nowrap><?php echo $OtherAllow; ?></th>
                    <th width="19" style="vertical-align:middle; text-align:center" nowrap><?php echo $Koef; ?></th>
                    <th width="40" style="vertical-align:middle; text-align:center" nowrap><?php echo $Status; ?></th>
                </tr>

                </thead>
                <tbody> 
                    <?php 
                    $i = 0;
					$j = 0;
                    if($countGol > 0)
                    {
                        $Unit_Type_Name2	= '';
						foreach($vwGol as $row) :
							$myNewNo 		= ++$i;
							$EMPG_CODE 		= $row->EMPG_CODE;
							$EMPG_NAME		= $row->EMPG_NAME;
							$EMPG_RANK		= $row->EMPG_RANK;
							$EMPG_PARENT	= $row->EMPG_PARENT;
							$EMPG_CHILD		= $row->EMPG_CHILD;
							$EMPG_BASAL 	= $row->EMPG_BASAL;
							$EMPG_P_ALLOW 	= $row->EMPG_P_ALLOW;
							$EMPG_H_ALLOW 	= $row->EMPG_H_ALLOW;
							$EMPG_C_ALLOW 	= $row->EMPG_C_ALLOW;
							$EMPG_A_ALLOW1 	= $row->EMPG_A_ALLOW1;
							$EMPG_A_ALLOW2 	= $row->EMPG_A_ALLOW2;
							$EMPG_AC_ALLOW	= $EMPG_A_ALLOW1 + $EMPG_A_ALLOW2;
							$EMPG_M_ALLOW 	= $row->EMPG_M_ALLOW;
							$EMPG_PF_ALLOW 	= $row->EMPG_PF_ALLOW;
							$EMPG_MK_ALLOW 	= $row->EMPG_MK_ALLOW;
							$EMPG_I_ALLOW 	= $row->EMPG_I_ALLOW;
							$EMPG_K_ALLOW 	= $row->EMPG_K_ALLOW;
							$EMPG_OTHERS	= $EMPG_PF_ALLOW + $EMPG_MK_ALLOW + $EMPG_I_ALLOW;
							$EMPG_K_ALLOW	= $row->EMPG_K_ALLOW;
							$EMPG_STAT 		= $row->EMPG_STAT;
							
							if($EMPG_STAT == 0)
							{
								$EMPG_STATD = 'fake';
								$STATCOL	= 'danger';
							}
							elseif($EMPG_STAT == 1)
							{
								$EMPG_STATD = 'New';
								$STATCOL	= 'warning';
							}
							elseif($EMPG_STAT == 2)
							{
								$EMPG_STATD = 'Confirm';
								$STATCOL	= 'primary';
							}
							elseif($EMPG_STAT == 3)
							{
								$EMPG_STATD = 'Approved';
								$STATCOL	= 'success';
							}
							if($EMPG_STAT == 5)
							{
								$EMPG_STATD = 'Rejected';
								$STATCOL	= 'danger';
							}
							$EMPG_NOTES 	= $row->EMPG_NOTES;
							
							$secUpd		= site_url('c_hr/c_master/c_gol/update/?id='.$this->url_encryption_helper->encode_url($EMPG_CODE));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td style="text-align:center"><?php print $myNewNo; ?>. </td>
                                    <td nowrap style="text-align:left">
                                    	<?php print anchor("$secUpd",$EMPG_CODE,array('class' => 'update')).' '; ?>
                                    </td>
                                    <td nowrap style="text-align:center"><?php print $EMPG_PARENT; ?> </td>
                                    <td nowrap style="text-align:center"> <?php print $EMPG_CHILD; ?> </td>
                                    <td nowrap style="text-align:left"><?php print $EMPG_RANK; ?></td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_BASAL, $decFormat); ?>&nbsp;</td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_P_ALLOW, $decFormat); ?>&nbsp;</td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_H_ALLOW, $decFormat); ?>&nbsp;</td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_C_ALLOW, $decFormat); ?>&nbsp;</td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_AC_ALLOW, $decFormat); ?>&nbsp;</td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_M_ALLOW, $decFormat); ?>&nbsp;</td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_OTHERS, $decFormat); ?>&nbsp;</td>
                                    <td nowrap style="text-align:right"><?php print number_format($EMPG_K_ALLOW, 2); ?>&nbsp;</td>
                                    <td nowrap style="text-align:center">
                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
										<?php 
                                            echo "&nbsp;&nbsp;$EMPG_STATD&nbsp;&nbsp;";
                                         ?>
                                    </span>
                                    </td>
                                </tr>
                                <?php 
                        endforeach;
                    }
                    ?> 
                </tbody>
                <tr>
                    <td colspan="14">
                    <?php
						if($ISCREATE == 1)
						{
							echo anchor("$addURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
                        }
						
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
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>