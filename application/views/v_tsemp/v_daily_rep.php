<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 20 Maret 2020
 * File Name	= v_daily_rep.php
 * Location		= -
*/ 
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
    $decFormat      = 2;

$empNameAct = '';
$sqlEMP     = "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
                FROM tbl_employee
                WHERE Emp_ID = '$DefEmp_ID'";
$resEMP     = $this->db->query($sqlEMP)->result();
foreach($resEMP as $rowEMP) :
    $empNameAct = $rowEMP->empName;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Dashboard</title>
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <?php
        $this->load->view('template/topbar');
        $this->load->view('template/sidebar');

        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];
        $LangID     = $this->session->userdata['LangID'];

    	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl		= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Title')$Title = $LangTransl;
    		if($TranslCode == 'Procedure')$Procedure = $LangTransl;
    		if($TranslCode == 'Requested')$Requested = $LangTransl;
    		if($TranslCode == 'Approved')$Approved = $LangTransl;
    		if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
    		if($TranslCode == 'MyBooking')$MyBooking = $LangTransl;
    	endforeach;
	
    	if($LangID == 'IND')
    	{
    		$h1_title		= 'Pemesanan';
    		$h2_title		= 'daftar kategori';
    		$MeetingRoom	= 'Pemesanan Ruang Rapat';
    		$RestRoom		= 'Pemesanan Kamar';
    		$Vehicle		= 'Pemesanan Kendaraan';
    		$BookingNow		= 'Pesan Sekarang';
    		$Available		= 'Tersedia';
    	}
    	else
    	{
    		$h1_title		= 'Reservation';	
    		$h2_title		= 'category list';	
    		$MeetingRoom	= 'Meeting Room Reservation';
    		$RestRoom		= 'Pemesanan Kamar';
    		$Vehicle		= 'Pemesanan Kendaraan';
    		$BookingNow		= 'Book Now';
    		$Available		= 'Available';
    	}
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">    
                <h1>
                Daftar Laporan Harian
                <small>WF Home NKE</small>
                </h1>
            </section>
            
            <?php
                $dateNow    = date('Y-m-d');
                $TOTDAY     = 0;
                $sqlAbsC    = "SELECT COUNT(DISTINCT EMP_ID) AS TOTDAY FROM tbl_absensi WHERE ABS_DATE = '$dateNow'";
                $resAbsC    = $this->db->query($sqlAbsC)->result();
                foreach ($resAbsC as $key) :
                    $TOTDAY = $key->TOTDAY;
                endforeach;

                $sqlAsmC    = "tbl_assesment";
                $resAsmC    = $this->db->count_all($sqlAsmC);
            ?>
            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3><?php echo $TOTDAY; ?></h3>
                                        <p>Kehadiran</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-clock"></i>
                                    </div>
                                    <a href="<?php echo $secAbsDR; ?>" class="small-box-footer">
                                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3><?php echo $resAsmC; ?></h3>
                                        <p>Penilaian Risiko</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-medkit"></i>
                                    </div>
                                    <a href="<?php echo $secAbsAR; ?>" class="small-box-footer">
                                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xs-6" style="display: none;">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>0</h3>
                                        <p>Catatan Harian Karyawan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-compose"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div> 
                            <div class="col-lg-3 col-xs-6" style="display: none;">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>0</h3>
                                        <p>Notulensi Rapat</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-document"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div> 
                        </div>     
                    </div>
                </div>
            </section>
        </div>
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>