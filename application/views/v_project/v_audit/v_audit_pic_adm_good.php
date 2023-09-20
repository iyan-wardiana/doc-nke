<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Agustus 2017
 * File Name	= v_news_read.php
 * Location		= -
*/

$this->load->view('template/head');

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
<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="callout callout-success">
            <h4><?php echo "$h2_title"; ?></h4>
        </div>
    </section>
    
	<section class="content">
    
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <?php
                        $qry1C	= "tbl_audit_pic WHERE AUI_NUM = '$AUI_NUM'";
                        $res1C	= $this->db->count_all($qry1C);
                        
                        $qry1	= "SELECT DISTINCT DATE(UPL_DATET) AS UPL_DATET FROM tbl_audit_pic WHERE AUI_NUM = '$AUI_NUM'";
                        $res1	= $this->db->query($qry1)->result();
                    ?>
                    
            		<div class="box-header with-border">
            			<h3 class="box-title">&nbsp;</h3>
                        <div class="box-tools pull-right">
                            <span class="label label-primary"><?php echo $res1C; ?> Uploads</span>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    
					<?php
                        if($res1C > 0)
                        {
                            foreach($res1 as $row1):
                                $dateUpld      	= date('Y-m-d', strtotime($row1->UPL_DATET));
                                $dateUpldV		= date('d M Y', strtotime($row1->UPL_DATET));
                                ?>
                                <ul class="timeline">
                                    <li class="time-label">
                                        <i class="fa fa-clock-o"></i>
                                        <div class="timeline-item">
                                            <span class="time">&nbsp;</span>
                                            <h3 class="timeline-header"><?php echo $dateUpldV; ?></h3>
                                        </div>
                                    </li>
                                    <li class="time-label">
                                    	<div class="timeline-item">
                                    		<div class="box-body no-padding">
                                                <ul class="users-list clearfix">
                                                <?php
                                                    $query1	= "SELECT * FROM tbl_audit_pic WHERE AUI_NUM = '$AUI_NUM' AND DATE(UPL_DATET) = '$dateUpld'";
                                                    $result1= $this->db->query($query1)->result();
                                                    
                                                    foreach($result1 as $r):
                                                        $image      = $r->PICT_NAME;
                                                        $PICT_DESC	= $r->PICT_DESC;
                                                        $UPL_DATET	= date('d M Y', strtotime($r->UPL_DATET));
                                                        $imageURL   = base_url('NCR_Upload/'.$image);
                                                        ?>
                                                                <li>
                                                                      <img src="<?php echo base_url('NCR_Upload/'.$image); ?>" alt="User Image">
                                                                      <a class="users-list-name" href="#"><?php echo $PICT_DESC; ?></a>
                                                                </li>
                                                            <?php
                                                        endforeach;
                                                    ?>
                                                </ul>
                                    		</div>
                                        </div>
                                    </li>
                               	</ul>
                                <?php
                            endforeach;
                        }
                    ?>
                </div>
            </div>
        </div>
	</section>
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
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>