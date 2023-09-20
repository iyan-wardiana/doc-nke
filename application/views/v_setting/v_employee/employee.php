<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Februari 2017
 * File Name	= employee.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
$Emp_DeptCode		= $this->session->userdata['Emp_DeptCode'];
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
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
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
		if($TranslCode == 'EmployeeCode')$EmployeeCode = $LangTransl;
		if($TranslCode == 'EmployeeName')$EmployeeName = $LangTransl;
		if($TranslCode == 'PlaceBirthDay')$PlaceBirthDay = $LangTransl;
		if($TranslCode == 'Department')$Department = $LangTransl;
		if($TranslCode == 'Dashboard')$Dashboard = $LangTransl;
		if($TranslCode == 'DocAuthorize')$DocAuthorize = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Authorization')$Authorization = $LangTransl;
		if($TranslCode == 'EmployeeList')$EmployeeList = $LangTransl;
		if($TranslCode == 'Employee')$Employee = $LangTransl;
		if($TranslCode == 'Group')$Group = $LangTransl;
		if($TranslCode == 'Department')$Department = $LangTransl;
		if($TranslCode == 'Position')$Position = $LangTransl;
	endforeach;
	$secAddURL = site_url('c_setting/c_employee/add/?id='.$this->url_encryption_helper->encode_url($appName));
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $EmployeeList; ?>
        <small><?php echo $Employee; ?></small>
        </h1><br>
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
            <table width="100%">
                <tr>
                 	<td width="80%">
                    <?php
                        echo anchor($secAddURL,'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;<?php echo $Add; ?></button>');
                    ?>
                    </td>
                 	<td width="20%">
                    	<form class="form-horizontal" name="frmSrc" method="post" action="<?php echo $search_action; ?>">
                            <div class="input-group">
                                <input type="text" name="key" class="form-control" style="max-width:100%" value="<?php echo $key; ?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">cari</button>
                                </span>
                            </div><!-- /input-group -->
                        </form>
                    </td>
                </tr>
            </table>
            <table id="example11" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                <thead>
                <tr>
                    <th width="2%" style="text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                    <th width="14%"><?php echo $EmployeeCode; ?> </th>
                    <th width="20%"><?php echo $EmployeeName; ?> </th>
                    <th width="23%"><?php echo $PlaceBirthDay; ?> </th>
                    <th width="25%"><?php echo $Department; ?> </th>
                    <th width="5%"><?php echo $Dashboard; ?> </th>
                    <th width="5%"><?php echo $DocAuthorize; ?> </th>
                    <th width="5%"><?php echo $Project; ?> </th>
                    <th width="8%"><?php echo $Authorization; ?> </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $dt1 		= strtotime("2017/04/01");
                $dt2 		= strtotime(date('Y/m/d'));
                $diff 		= abs($dt2-$dt1);
                $DayTot 	= $diff/86400;
                
                $i = 0;
                $j = 0;
                if(!$jlhpage)
				{         //ini untuk menangani penomoran agar otomatis menyesuaikan dengan paging
				  	$no	= 0;
				}
				else
				{
					$no = $jlhpage;
				}
		 
		 		if($viewdata != null)
				{
					foreach($viewdata as $row) :
					{
                        $myNewNo 		= ++$i;
                        $ID				= $row->ID;
                        $Emp_ID			= $row->Emp_ID;
                        
                        /*$getLogC		= "tbl_login_hist WHERE LOG_EMP = '$Emp_ID'";
                        $resLogC		= $this->db->count_all($getLogC);
                        $resLogCT		= $resLogC;
                        $sqlUpdLogC		= "UPDATE tbl_employee SET Log_Count = $resLogCT WHERE Emp_ID = '$Emp_ID'";
                        $this->db->query($sqlUpdLogC);
                        
                        $Log_AVG		= $resLogCT / $DayTot * 100;
                        
                        $UpdLogAVG	= "UPDATE tbl_employee SET Log_AVG = $Log_AVG WHERE Emp_ID = '$Emp_ID'";
                        $this->db->query($UpdLogAVG);*/
            
                        $First_Name		= $row->First_Name;
                        $Middle_Name	= $row->Middle_Name;
                        $Last_Name		= $row->Last_Name;
                        $Birth_Place	= $row->Birth_Place;
                        $Date_Of_Birth	= $row->Date_Of_Birth;
                        $empUpdate		= site_url('c_setting/c_employee/update/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
                        $empProject		= site_url('c_setting/c_employee/employee_project/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
                        $empAuthorize	= site_url('c_setting/c_employee/employee_authorization/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
                        $secDashURL		= site_url('c_setting/c_employee/employee_dashboard/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
                        $secDocURL		= site_url('c_setting/c_employee/employee_auth_doc/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
                        
                        $POS_CODE		= $row->Pos_Code;
                        $POS_NAME 		= '';
                        $POS_DESC 		= '';
                        $sqlPos			= "SELECT POS_CODE, POS_NAME, POS_CODE, POS_DESC
                                            FROM tbl_position WHERE POS_CODE = '$POS_CODE'";
                        $resPos			= $this->db->query($sqlPos)->result();
                        foreach($resPos as $rowPos) :
                            $POS_NAME 	= $rowPos->POS_NAME;
                            $POS_DESC 	= $rowPos->POS_DESC;
                        endforeach;
            
                        if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }
                        ?>
                            <td style="text-align:center"><?php echo $ID; ?>.
                                <input name="chkDetail" id="chkDetail" type="checkbox" value="<?php print $Emp_ID; ?>" style="display:none" /></td>
                            <td> <?php print anchor("$empUpdate",$Emp_ID,array('class' => 'update')).' '; ?> </td>
                            <td style="text-transform:uppercase"> <?php print "$First_Name&nbsp;$Middle_Name&nbsp;$Last_Name"; ?> </td>
                            <td> <?php print $Birth_Place; print ', '; print $Date_Of_Birth; ?> </td>
                            <?php /*?><td> <?php print $row->Mobile_Phone; ?> </td><?php */?>
                            <td> <?php print $POS_NAME; ?> </td>
                            <td style="text-align:center">
                                <a href="<?php echo $secDashURL; ?>" data-skin="skin-green" class="btn btn-info btn-xs" title="Dashboard Setting">
                                    <i class="fa fa-bar-chart-o"></i>
                                </a>
                            </td>
                            <td style="text-align:center">
                                <a href="<?php echo $secDocURL; ?>" data-skin="skin-green" class="btn btn-warning btn-xs" title="Document Authorization Setting">
                                    <i class="fa fa-book"></i>
                                </a>
                            </td>
                            <td style="text-align:center">
                                <a href="<?php echo $empProject; ?>" data-skin="skin-green" class="btn btn-danger btn-xs" title="Project Authorization">
                                    <i class="fa fa-building-o"></i>
                                </a>
                            <?php 
                                /*if($Emp_DeptCode == 1 || $Emp_DeptCode == 99 || $Emp_DeptCode == 3)
                                {
                                   print anchor("$empProject",'<img src="'.base_url().'assets/AdminLTE-2.0.5/dist/img/checklist3.png" width="25" height="25">',array('class' => 'update')).' '; 
                                }*/
                            ?>
                            </td>
                            <td style="text-align:center">
                                <a href="<?php echo $empAuthorize; ?>" data-skin="skin-green" class="btn btn-success btn-xs" title="Menu Authorization">
                                    <i class="fa fa-check"></i>
                                </a>
                            <?php 
                                /*if($Emp_DeptCode == 1 || $Emp_DeptCode == 99 || $Emp_DeptCode == 3)
                                {
                                    print anchor("$empAuthorize",'<img src="'.base_url().'assets/AdminLTE-2.0.5/dist/img/authorization_icon6.png" width="25" height="25">',array('class' => 'update')).' ';
                                }*/
                            ?>
                            </td>
                        </tr>
                        <?php
					}
					endforeach;
					?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align:left" class="panel-footer">
								<?php echo $paging; ?>
                            </td>
                        </tr>
                    </tfoot>
                    <?php
				}
				else
				{
					?>
                    <tr>
                    	<td colspan="9">No data available in table</td>
                  	</tr>
                    <?php
				}
				?>
            </table>
        </div>
	</div>
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