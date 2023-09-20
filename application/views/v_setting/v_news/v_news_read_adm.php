<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Agustus 2017
 * File Name	= v_news_read.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$Emp_ID 	= $this->session->userdata['Emp_ID'];
$sqlMG		= "SELECT A.NEWSD_CODE, A.NEWSD_DATE, A.NEWSD_RECEIVER, A.NEWSD_TITLE, A.NEWSD_CONTENT, A.NEWSD_CREATER, A.NEWSD_CREATED, 
					A.NEWSD_IMG, A.NEWSD_IMG1, A.NEWSD_IMG2, A.NEWSD_IMG3, A.NEWSD_IMG4, B.First_Name, B.Last_Name
				FROM tbl_news_detail A
					INNER JOIN tbl_employee B ON A.NEWSD_CREATER = B.Emp_ID
				WHERE A.NEWSD_ID = '$NEWSD_ID'";
$sqlMG		= $this->db->query($sqlMG)->result();
foreach($sqlMG as $rowMG) :
	$NEWSD_CODE		= $rowMG->NEWSD_CODE;
	$NEWSD_CODE		= $rowMG->NEWSD_CODE;
	$NEWSD_DATE		= $rowMG->NEWSD_DATE;
	$NEWSD_RECEIVER	= $rowMG->NEWSD_RECEIVER;
	$NEWSD_TITLE	= $rowMG->NEWSD_TITLE;
	$NEWSD_CONTENT	= $rowMG->NEWSD_CONTENT;
	$NEWSD_CREATER	= $rowMG->NEWSD_CREATER;
	$NEWSD_CREATED	= $rowMG->NEWSD_CREATED;
	$DATED			= date('F j, Y', strtotime($NEWSD_CREATED));
	$DATEDT			= date('g:i a', strtotime($NEWSD_CREATED));
	$NEWSD_IMG		= $rowMG->NEWSD_IMG;
	$NEWSD_IMG1		= $rowMG->NEWSD_IMG1;
	$NEWSD_IMG2		= $rowMG->NEWSD_IMG2;
	$NEWSD_IMG3		= $rowMG->NEWSD_IMG3;
	$NEWSD_IMG4		= $rowMG->NEWSD_IMG4;
	$First_Name		= ucfirst($rowMG->First_Name);
	$Last_Name		= ucfirst($rowMG->Last_Name);
	$compName1		= "$First_Name $Last_Name";
	$compName		= ucfirst($compName1);
endforeach;
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
<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="callout callout-success">
            <h4><?php echo "$h2_title : $NEWSD_TITLE"; ?></h4>
        </div>
    </section>
    
	<section class="content">
    
    <div class="row">
        <div class="col-md-12">
            <ul class="timeline">
            <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-red">
                    	<?php echo $DATED; ?>
                    </span>
                </li>
                <li>
                    <i class="fa fa-envelope bg-blue"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo ucfirst($DATEDT); ?></span>
                        <h3 class="timeline-header"><a href="#"><?php echo $NEWSD_TITLE; ?></a></h3>
                        <div class="timeline-body">
                           <?php echo $NEWSD_CONTENT; ?>
                        </div>
                        <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs" style="display:none">Read more</a>
                        <a class="btn btn-danger btn-xs" style="display:none">Delete</a>
                        </div>
                	</div>
                </li>
                <?php
					$imgNewsLoc1	= base_url('assets/AdminLTE-2.0.5/dist/img/news/'.$NEWSD_IMG1);
					$imgNewsLoc2	= base_url('assets/AdminLTE-2.0.5/dist/img/news/'.$NEWSD_IMG2);
					$imgNewsLoc3	= base_url('assets/AdminLTE-2.0.5/dist/img/news/'.$NEWSD_IMG3);
					$imgNewsLoc4	= base_url('assets/AdminLTE-2.0.5/dist/img/news/'.$NEWSD_IMG4);
				?>
                <li>
                  <i class="fa fa-camera bg-purple"></i>
                  <div class="timeline-item">
                    <span class="time">&nbsp;</span>
                    <h3 class="timeline-header"><a href="#">uploaded new photos</a></h3>
                        <div class="timeline-body">
                          <img src="<?php echo $imgNewsLoc1; ?>" alt="..." class="margin" style="max-width:200px; max-height:200px">
                          <img src="<?php echo $imgNewsLoc2; ?>" alt="..." class="margin" style="max-width:200px; max-height:200px">
                          <img src="<?php echo $imgNewsLoc3; ?>" alt="..." class="margin" style="max-width:200px; max-height:200px">
                          <img src="<?php echo $imgNewsLoc4; ?>" alt="..." class="margin" style="max-width:200px; max-height:200px">
                        </div>
                  </div>
                </li>
                <li>
                    <i class="fa fa-user bg-aqua"></i>
                    <div class="timeline-item">
                    	<span class="time">&nbsp;</span>
                    	<h3 class="timeline-header no-border"><a href="#"><?php echo $compName; ?></a> - Peacher</h3>
                    </div>
                </li>
                <li>
                    <i class="fa fa-comments bg-green"></i>
                    <div class="timeline-item">
                    	<span class="time">&nbsp;</span>
                    	<h3 class="timeline-header no-border"><a href="#">Comments</a></h3>
                    </div>
                </li>
                <li class="time-label">
                    	<i class="fa fa-clock-o"></i>
                </li>
            </ul>
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