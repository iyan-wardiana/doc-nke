<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Februari 2018
 * File Name	= v_reservation_mr_list.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
</head>

<?php
	$LangID 		= $this->session->userdata['LangID'];

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
		if($TranslCode == 'Information')$Information = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'View')$View = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title		= 'Pemesanan';
		$h2_title		= 'daftar pesanan (admin)';		
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Pesan Sekarang';
		$Available		= 'Tersedia';
		$sureDelete		= 'Anda yakin akan menghapus data ini?';		
		$Reschedule		= 'Penjadwalan<br>Ulang';
		$Submitter		= 'Pengaju<br>/ CC';
	}
	else
	{
		$h1_title		= 'Reservation';	
		$h2_title		= 'book list (admin)';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Book Now';
		$Available		= 'Available';
		$sureDelete		= 'Are your sure want to delete?';
		$Reschedule		= 'Reschedule';
		$Submitter		= 'Submitter<br>/ CC';
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
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
    <div class="callout callout-success">
        <?php echo "$h1_title : $h2_title"; ?>
    </div>
	<div class="search-table-outter">
        <form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return validateInData()">
              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                    <thead>
                        <tr>
                            <th width="5%" style="text-align:center; vertical-align:middle"><?php echo $Code; ?></th>
                            <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date; ?></th>
                            <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $MeetingRoom; ?></th>
                            <th width="46%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Topic; ?> </th>
                            <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Submitter; ?></th>
                            <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $MeetingRoom; ?></th>
                            <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Reschedule; ?></th>
                            <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status; ?> </th>
                            <th width="7%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        $j = 0;
                        
                        if($BLCount2 >0)
                        { 
                            foreach($vwBL2 as $row) :
                                $myNewNo 		= ++$i;
                                $RSV_CODE 		= $row->RSV_CODE;
                                $RSV_CATEG 		= $row->RSV_CATEG;
                                $CATEG_CODE	 	= $row->CATEG_CODE;
								// GET MEETING ROOM DETAIL
									$MR_NAME	= '';
									$sqlMR		= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE'";
									$resMR		= $this->db->query($sqlMR)->result();
									foreach($resMR as $rowMR):
										$MR_NAME	= $rowMR->MR_NAME;
									endforeach;
									
                                $RSV_STARTD	 	= $row->RSV_STARTD;
                                $RSV_ENDD		= $row->RSV_ENDD;
                                $RSV_STARTT		= $row->RSV_STARTT;
                                $RSV_ENDT		= $row->RSV_ENDT;
                                $CATEG_CODE2 	= $row->CATEG_CODE2;
								// GET MEETING ROOM DETAIL
									$MR_NAME2	= '';
									$sqlMR2		= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE2'";
									$resMR2		= $this->db->query($sqlMR2)->result();
									foreach($resMR2 as $rowMR2):
										$MR_NAME2	= $rowMR2->MR_NAME;
									endforeach;
									
                                $RSV_STARTD2 	= $row->RSV_STARTD2;
                                $RSV_ENDD2		= $row->RSV_ENDD2;
                                $RSV_STARTT2	= $row->RSV_STARTT2;
                                $RSV_ENDT2		= $row->RSV_ENDT2;
                                $RSV_TITLE		= $row->RSV_TITLE;
                                $RSV_DESC		= $row->RSV_DESC;
                                $RSV_QTY		= $row->RSV_QTY;
                                $RSV_MEMO		= $row->RSV_MEMO;
                                $RSV_EMPID		= $row->RSV_EMPID;
                                $RSV_SUBMITTER	= $row->RSV_SUBMITTER;
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
                                
                                $secUpdAdm		= site_url('reservation/update_adm/?id='.$this->url_encryption_helper->encode_url($RSV_CODE));
                                $secBookList	= site_url('reservation/view_booklist/?id='.$this->url_encryption_helper->encode_url($appName));
                            
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
                                    <td nowrap style="text-align:left; vertical-align:middle"><?php echo $MR_NAME; ?> </td>
                                    <td nowrap style="text-align:left; vertical-align:middle"><?php echo $RSV_DESC; ?></td>
                                    <td nowrap style="text-align:left; vertical-align:middle"><?php echo $RSV_SUBMITTER; ?></td>
                                    <td nowrap style="text-align:left; <?php echo $bgSetting; ?>"><?php echo $MR_NAME2; ?></td>
                                    <td nowrap style="text-align:center; <?php echo $bgSetting; ?>"><?php echo $resChedzV; ?></td>
                                    <td nowrap style="text-align:center; vertical-align:middle">
                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                        <?php 
                                            echo "&nbsp;&nbsp;$RSV_STATD&nbsp;&nbsp;";
                                         ?>
                                    </span>
                                    </td>
                                    <td nowrap style="text-align:center; vertical-align:middle">
                                    	<input type="hidden" name="urlViewList<?php echo $myNewNo; ?>" id="urlViewList<?php echo $myNewNo; ?>" value="<?php echo $secBookList; ?>">
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
                            <td colspan="9" style="text-align:left">
                                <button class="btn btn-danger" type="button" onClick="window.close()">
                                	<i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
			</form>
    	</div>
    <!-- /.box-body -->
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

<script>
var selectedRows = 0
function check_all(chk) {
	if(chk.checked) {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = true;
			}
		} else {
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	} else {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = false;
			}
		} else {
			document.frmSearch.chk.checked = false;
		}
		selectedRows = 0;
	}
}

function pickThis(thisobj) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_item() 
	{ 
		//alert(document.frmSearch.chk.length) 
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.add_item(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_item(document.frmSearch.chk.value);
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					alert(B)
				}*/
			}
		}
		window.close();		
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>