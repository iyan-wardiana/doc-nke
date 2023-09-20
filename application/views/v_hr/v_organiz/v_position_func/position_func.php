<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= position_func.php
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
		if($TranslCode == 'PositionName')$PositionName = $LangTransl;
		if($TranslCode == 'SectionName')$SectionName = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'FunctionalPosition')$FunctionalPosition = $LangTransl;
		if($TranslCode == 'Organization')$Organization = $LangTransl;
		if($TranslCode == 'Level')$Level = $LangTransl;
		if($TranslCode == 'Nomor')$Nomor = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $FunctionalPosition; ?>
        <small><?php echo $Organization; ?></small>
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
                    <th width="2%"><?php echo $Nomor; ?></th>
                    <th width="7%" style="text-align:center"><?php echo $Code; ?>  </th>
                  	<th width="19%" style="text-align:center"><?php echo $PositionName; ?>  </th>
                  	<th width="17%" style="text-align:center"><?php echo $Description; ?>  </th>
                  	<th width="25%" style="text-align:center"><?php echo $SectionName; ?>  </th>
                  	<th width="18%" style="text-align:center"><?php echo $Description; ?></th>
                  	<th width="6%" style="text-align:center"><?php echo $Level; ?></th>
                  	<th width="6%" style="text-align:center"><?php echo $Status; ?> </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $j = 0;
				foreach($vwPosStr as $row) :
					$i				= $i + 1;
					$POSF_CODE 		= $row->POSF_CODE;
					$POSF_NAME 		= $row->POSF_NAME;
					$POSF_PARENT	= $row->POSF_PARENT;
						if($POSF_PARENT == "")
						{
							$POSS_NAME 	= "none";
							$POSS_LEVEL = "none";
						}
						else
						{		
							$sqlGetParent	= "SELECT POSS_NAME, POSS_LEVEL FROM tbl_position_str WHERE POSS_CODE = '$POSF_PARENT'";
							$resGetParent	= $this->db->query($sqlGetParent)->result();
							foreach($resGetParent as $newrow) :
								$POSS_NAME 	= $newrow->POSS_NAME;
								$POSS_LEVEL = $newrow->POSS_LEVEL;
							endforeach;
						}
					$POSF_PARENT	= $row->POSF_PARENT;
					if($POSS_LEVEL == "BOD")
					{
						$POSS_LEVELD = "Director";
					}
					elseif($POSS_LEVEL == "DEPT")
					{
						$POSS_LEVELD = "Department";
					}
					elseif($POSS_LEVEL == "DIV")
					{
						$POSS_LEVELD = "Divisi";
					}
					elseif($POSS_LEVEL == "BIRO")
					{
						$POSS_LEVELD = "Biro";
					}
					elseif($POSS_LEVEL == "UNIT")
					{
						$POSS_LEVELD = "Unit";
					}
					elseif($POSS_LEVEL == "URS")
					{
						$POSS_LEVELD = "Urusan";
					}
					elseif($POSS_LEVEL == "STAF")
					{
						$POSS_LEVELD = "Staff";
					}
					else
					{
						$POSS_LEVELD = "none";
					}
					
					$POSF_DESC		= $row->POSF_DESC;
					
					$POSF_STAT		= $row->POSF_STAT;
					if($POSF_STAT == 1)
					{
						$POSF_STATD = "Active";
                        $STATCOL	= 'success';
					}
					else
					{
						$POSF_STATD = "In Active";
                        $STATCOL	= 'danger';
					}
					
					$secUpd		= site_url('c_hr/c_organiz/c_position_func/update/?id='.$this->url_encryption_helper->encode_url($POSF_CODE));
					
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
					?>
                        <td> <?php print $i; ?>.</td>
                        <td nowrap> <?php print anchor("$secUpd",$POSF_CODE,array('class' => 'update')).' '; ?> </td>
                        <td> <?php print $POSF_NAME; ?> </td>
                        <td> <?php print $POSF_DESC; ?> </td>
                        <td> <?php print $POSS_NAME; ?> </td>
                        <td><?php print $POSF_DESC; ?></td>
                        <td><?php print $POSS_LEVELD; ?></td>
                        <td style="text-align:center">
                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
							<?php 
                                echo "&nbsp;&nbsp;$POSF_STATD&nbsp;&nbsp;";
                             ?>
                        </span>
                        </td>
                    </tr>
                    <?php
				endforeach; 
				$secAddURL = site_url('c_hr/c_organiz/c_position_func/add/?id='.$this->url_encryption_helper->encode_url($appName));
                ?>
                </tbody> 
                <tr>
                    <td colspan="8">
                        <?php
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