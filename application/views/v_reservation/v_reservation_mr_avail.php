<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 5 Februari 2018
 * File Name	= v_reservation_mr_avail.php 
 * Location		= -
*/ 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;
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

<?php
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Topic')$Topic = $LangTransl;
		if($TranslCode == 'MeetingRoom')$MeetingRoom = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Topic')$Topic = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title		= 'Ruang Meeting';
		$h2_title		= 'status';
		$MeetingRoom	= 'Pemesanan Ruang Rapat';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Pesan Sekarang';
		$Available		= 'Tersedia';
		$sureDelete		= 'Anda yakin akan menghapus data ini?';
		$Reschedule		= 'Jadwal Ulang';
	}
	else
	{
		$h1_title		= 'Meeting Room';	
		$h2_title		= 'status';	
		$MeetingRoom	= 'Meeting Room Reservation';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Book Now';
		$Available		= 'Available';
		$sureDelete		= 'Are your sure want to delete?';
		$Reschedule		= 'Reschedule';
	}
	
	$addReqMR			= site_url('howtouse/addMR/?id='.$this->url_encryption_helper->encode_url($appName));		// Meeting Room Request
	$addReqVC			= site_url('howtouse/addVC/?id='.$this->url_encryption_helper->encode_url($appName));		// Vehicle Request
	$addReqRR			= site_url('howtouse/addRR/?id='.$this->url_encryption_helper->encode_url($appName));		// Rest Room Request
?>

<body class="hold-transition skin-blue sidebar-mini">
    <section class="content-header">    
    <h1>
        <?php echo $h1_title; ?>
        <small><?php echo $h2_title; ?></small>
      </h1><br>
    </section>
	<style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>    
    <div class="box">
        <div class="box-body">
            <div class="search-table-outter">
            	<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                    <thead>
                        <tr>
                            <th width="2%" style="text-align:center;">No.</th>
                            <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code; ?></th>
                            <th width="14%" style="text-align:center; vertical-align:middle" nowrap><?php echo $MeetingRoom; ?> </th>
                            <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status; ?></th>
                            <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $StartDate; ?> </th>
                            <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $EndDate; ?></th>
                            <th width="46%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Topic; ?></th>
                            <th width="11%" style="text-align:center; vertical-align:middle" nowrap>ID</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        $j = 0;
                        if($MRCount >0)
                        { 
                        foreach($vwMR as $row) :
                            $myNewNo 		= ++$i;
                            $MR_CODE 		= $row->MR_CODE;
                            $MR_NAME 		= $row->MR_NAME;
                            $MR_STARTD	 	= $row->MR_STARTD;
                            $MR_ENDD	 	= $row->MR_ENDD;
                            $MR_STARTT	 	= $row->MR_STARTT;
                            $MR_ENDT	 	= $row->MR_ENDT;
                            $MR_NOTES	 	= $row->MR_NOTES;
                            $MR_STAT	 	= $row->MR_STAT;
                            $MR_CREATER		= $row->MR_CREATER;
                            $MR_CREATED		= $row->MR_CREATED;
                            $MR_SUBMITTER	= $row->MR_SUBMITTER;
                                
							if($MR_STAT == 0)
							{
								$MR_STATD 	= 'ready';
								$STATCOL	= 'success';
							}
							elseif($MR_STAT == 1)
							{
								$MR_STATD 	= 'booked';
								$STATCOL	= 'danger';
							} 
                            
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							if($MR_STARTD == '0000-00-00 00:00:00')
							{
								$date1V	= "-";
							}
							else
							{
								$date1 	= new DateTime($MR_STARTD);
								$date1V	= date_format($date1, "d/m/Y H:i:s");
							}
							
							if($MR_ENDD == '0000-00-00 00:00:00')
							{
								$date2V	= "-";
							}
							else
							{
								$date2 	= new DateTime($MR_ENDD);
								$date2V	= date_format($date2, "d/m/Y H:i:s");
							}
                            ?>
                                <td style="text-align:center"><?php echo $myNewNo; ?></td>
                                <td nowrap style="text-align:center"> <?php echo $MR_CODE;?></td>
                                <td nowrap style="text-align:left"><?php echo $MR_NAME; ?> </td>
                                <td nowrap style="text-align:center">
                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                        <?php 
                                            echo "&nbsp;&nbsp;$MR_STATD&nbsp;&nbsp;";
                                         ?>
                                    </span>
                                </td>
                                <td nowrap style="text-align:center">
                                    <?php 
                                         echo $date1V;
                                    ?>
                                </td>
                                <td nowrap style="text-align:center">
                                    <?php 
                                         echo $date2V;
                                    ?>
                                </td>
                                <td nowrap style="text-align:left"><?php echo $MR_NOTES; ?></td>
                                <td nowrap style="text-align:left"><?php echo $MR_SUBMITTER; ?></td>
                            </tr>
							<?php 
                        endforeach; 
                        }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="6" style="text-align:left">
                            <?php
                                echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                            ?>            </td>
                      </tr>
                    </tfoot>
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