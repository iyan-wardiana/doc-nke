<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 2 Februari 2018
 * File Name	= v_reservation_mr.php
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
		if($TranslCode == 'Destination')$Destination = $LangTransl;
		if($TranslCode == 'NOPOL')$NOPOL = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Information')$Information = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'View')$View = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title		= 'Pemesanan';
		$h2_title		= 'daftar pesanan';
		
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Kendaraan';
		$Driver			= 'Supir';
		$Revised		= 'Revisi';
		$BookingNow		= 'Pesan Sekarang';
		$Available		= 'Tersedia';
		$sureDelete		= 'Anda yakin akan menghapus data ini?';		
		$Reschedule		= 'Penjadwalan<br> Ulang';
	}
	else
	{
		$h1_title		= 'Reservation';	
		$h2_title		= 'book list';
		$Vehicle		= 'Vehicle';
		$Driver			= 'Driver';
		$Revised		= 'Revised';
		$Destination	= 'Tujuan';
		$NOPOL			= 'Plat Number';
		$BookingNow		= 'Book Now';
		$Available		= 'Available';
		$sureDelete		= 'Are your sure want to delete?';
		$Reschedule		= 'Reschedule';
	}
	
	$addReqMR			= site_url('howtouse/addMR/?id='.$this->url_encryption_helper->encode_url($appName));		// Meeting Room Request
	$addReqVC			= site_url('howtouse/addVH/?id='.$this->url_encryption_helper->encode_url($appName));		// Vehicle Request
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
                            <th width="5%" style="text-align:center; vertical-align:middle"><?php echo $Code; ?></th>
                            <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date; ?></th>
                            <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Vehicle; ?></th>
                            <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Driver; ?></th>
                            <th width="43%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Destination; ?> </th>
                            <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $NOPOL; ?></th>
                            <th width="12%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Reschedule; ?></th>
                            <th width="12%" style="text-align:center; vertical-align:middle" nowrap><?php echo "$Vehicle<br>($Revised)"; ?></th>
                            <th width="12%" style="text-align:center; vertical-align:middle" nowrap><?php echo "$Driver<br>($Revised)"; ?></th>
                            <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status; ?> </th>
                            <th width="7%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        $j = 0;
                        
                        if($BLVH_C > 0)
                        { 
                            foreach($BLVH_V as $row) :
                                $myNewNo 		= ++$i;
                                $RSV_CODE 		= $row->RSV_CODE;
                                $RSV_CATEG 		= $row->RSV_CATEG;
                                $CATEG_CODE	 	= $row->CATEG_CODE;
								$VH_MEREK		= $row->VH_MEREK;
								$DRIVER_CODE	= $row->DRIVER_CODE;
								$Driver1		= $row->DRIVER;
								$VH_NOPOL		= $row->VH_NOPOL;
									
                                $RSV_STARTD	 	= $row->RSV_STARTD;
                                $RSV_ENDD		= $row->RSV_ENDD;
                                $RSV_STARTT		= $row->RSV_STARTT;
                                $RSV_ENDT		= $row->RSV_ENDT;
                                $CATEG_CODE2 	= $row->CATEG_CODE2;
								$DRIVER_CODE2	= $row->DRIVER_CODE2;
									
                                $RSV_STARTD2 	= $row->RSV_STARTD2;
                                $RSV_ENDD2		= $row->RSV_ENDD2;
                                $RSV_STARTT2	= $row->RSV_STARTT2;
                                $RSV_ENDT2		= $row->RSV_ENDT2;
                                $RSV_TITLE		= $row->RSV_TITLE;
                                $RSV_DESC		= $row->RSV_DESC;
                                $RSV_QTY		= $row->RSV_QTY;
                                $RSV_MEMO		= $row->RSV_MEMO;
                                $RSV_EMPID		= $row->RSV_EMPID;
                                $RSV_STAT		= $row->RSV_STAT;
								
                                $date 	= new DateTime($RSV_STARTD);
							
								if($RSV_STARTT == '00:00:00')
								{
									$datetSV	= "-";
								}
								else
								{
									$datetS 	= new DateTime($RSV_STARTT);
									$datetSV	= date_format($datetS, "H:i");
								}
							
								if($RSV_ENDT == '00:00:00')
								{
									$datetEV	= "-";
								}
								else
								{
									$datetE 	= new DateTime($RSV_ENDT);
									$datetEV	= date_format($datetE, "H:i");
								}
								
								if($RSV_STAT == 4)
								{
                               		$bgSetting	= "vertical-align:middle; background-color:#00BEEE; color:#900; font-size:14px; font-weight:bold";
									$date2 		= new DateTime($RSV_STARTD2);
									
									//get merek mobil
									$Csql_rch 	= "tbl_vehicle WHERE VH_CODE = '$CATEG_CODE2'";
									$Rsql_rch	= "SELECT VH_MEREK FROM tbl_vehicle WHERE VH_CODE = '$CATEG_CODE2'";
									$Count_rch	= $this->db->count_all($Csql_rch);
									if($Count_rch > 0)
									{
										$res_rch	= $this->db->query($Rsql_rch)->result();
										foreach($res_rch as $row_rch)
										{
											$rChMerek	= $row_rch->VH_MEREK;
											//echo $rChMerek;		
										}
									}
									
									//get driver
									$Csql_rch1 	= "tbl_driver WHERE DRIVER_CODE = '$DRIVER_CODE2'";
									$Rsql_rch1	= "SELECT DRIVER FROM tbl_driver WHERE DRIVER_CODE = '$DRIVER_CODE2'";
									$Count_rch1	= $this->db->count_all($Csql_rch1);
									if($Count_rch1 > 0)
									{
										$res_rch1	= $this->db->query($Rsql_rch1)->result();
										foreach($res_rch1 as $row_rch1)
										{
											$rChDriver	= $row_rch1->DRIVER;
											//echo $DRIVER;		
										}
									}
									
									if($RSV_STARTT == '00:00:00')
									{
										$datetSV2	= "";
									}
									else
									{
										$datetS2 	= new DateTime($RSV_STARTT2);
										$datetSV2	= date_format($datetS2, "H:i");
									}
								
									if($RSV_ENDT == '00:00:00')
									{
										$datetEV2	= "";
									}
									else
									{
										$datetE2 	= new DateTime($RSV_ENDT2);
										$datetEV2	= date_format($datetE2, "H:i");
									}
									$dateV2			= date_format($date2, "d/m/Y");
									$resChedzV	= "$dateV2<br>$datetSV2 - $datetEV2";
								}
								else
								{
                               		$bgSetting	= "";
									$resChedzV	= "-";
									$rChMerek	= "-";
									$rChDriver	= "-";
								}
                                
                                if($RSV_STAT == 0)
                                {
                                    $RSV_STATD 	= 'fake';
                                    $STATCOL	= 'danger';
                                }
                                elseif($RSV_STAT == 1)
                                {
                                    $RSV_STATD 	= 'New';
                                    $STATCOL	= 'warning';
                                }
                                elseif($RSV_STAT == 2)
                                {
                                    $RSV_STATD 	= 'Awaiting';
                                    $STATCOL	= 'warning';
                                }
                                elseif($RSV_STAT == 3)
                                {
                                    $RSV_STATD 	= 'Approved';
                                    $STATCOL	= 'success';
                                }
                                elseif($RSV_STAT == 4)
                                {
                                    $RSV_STATD 	= 'Reschedule';
                                    $STATCOL	= 'info';
                                }
                                elseif($RSV_STAT == 5)
                                {
                                    $RSV_STATD 	= 'Rejected';
                                    $STATCOL	= 'danger';
                                }
                                elseif($RSV_STAT == 6)
                                {
                                    $RSV_STATD 	= 'Close';
                                    $STATCOL	= 'danger';
                                }
                                elseif($RSV_STAT == 8)
                                {
                                    $RSV_STATD 	= 'in used';
                                    $STATCOL	= 'primary';
                                }
                                else
                                {
                                    $RSV_STATD 	= 'Awaiting';
                                    $STATCOL	= 'warning';
                                }
								
								$enabled		= 0;
								$enabledDel		= 0;
								if($RSV_STAT != 3 || $RSV_STAT != 6)
								{
									$enabled	= 1;
								}
								if($RSV_STAT == 1)
								{
									$enabledDel	= 1;
								}
                                
                                $secUpd			= site_url('reservation/update/?id='.$this->url_encryption_helper->encode_url($RSV_CODE));
								$secBookList	= site_url('reservation/view_booklist/?id='.$this->url_encryption_helper->encode_url($appName));
								$secUpdAdm		= site_url('reservation/update_adm/?id='.$this->url_encryption_helper->encode_url($RSV_CODE));
                            
                                if ($j==1) {
                                    echo "<tr class=zebra1>";
                                    $j++;
                                } else {
                                    echo "<tr class=zebra2>";
                                    $j--;
                                }
                                ?>
                                    <td nowrap style="vertical-align:middle"><?php echo $RSV_CODE; ?></td>
                                    <td nowrap style="text-align:center; vertical-align:middle"><?php echo date_format($date, "d/m/Y"); echo "<br>$datetSV - $datetEV"; ?></td>
                                    <td nowrap style="text-align:left; vertical-align:middle"><?php echo $VH_MEREK; ?> </td>
                                    <td nowrap style="text-align:center; vertical-align:middle"><?php echo $Driver1; ?> </td>
                                    <td nowrap style="text-align:left; vertical-align:middle"><?php echo $RSV_DESC; ?></td>
                                    <td nowrap style="text-align:left; vertical-align:middle"><?php echo $VH_NOPOL; ?></td>
                                    <td nowrap style="text-align:center; <?php echo $bgSetting; ?>"><?php echo $resChedzV; ?></td>
                                    <td nowrap style="text-align:center; <?php echo $bgSetting; ?>"><?=$rChMerek?></td>
                                    <td nowrap style="text-align:center; <?php echo $bgSetting; ?>"><?=$rChDriver?></td>
                                    <td nowrap style="text-align:center; vertical-align:middle">
                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                        <?php 
                                            echo "&nbsp;&nbsp;$RSV_STATD&nbsp;&nbsp;";
                                         ?>
                                    </span>
                                    </td>
                                    <input type="hidden" name="urlViewList<?php echo $myNewNo; ?>" id="urlViewList<?php echo $myNewNo; ?>" value="<?php echo $secBookList; ?>">
                                    <td nowrap style="text-align:center; vertical-align:middle">
                                        <a href="<?php echo $secUpdAdm; ?>" class="btn btn-warning btn-xs" title="Update" <?php if($enabled == 0) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="<?php echo $secUpdAdm; ?>" class="btn btn-success btn-xs" title="<?php echo $Approve; ?>" <?php if($enabled == 0) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-ok"></i>
                                        </a>
                                        <a href="javascript:void(null);" class="btn btn-primary btn-xs" onClick="viewBookList('<?php echo $myNewNo; ?>')" title="<?php echo $View; ?>" <?php if($enabled == 0) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-zoom-in"></i>
                                        </a>
                                        <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($enabledDel == 0) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                            endforeach; 
                        }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="8" style="text-align:left">
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