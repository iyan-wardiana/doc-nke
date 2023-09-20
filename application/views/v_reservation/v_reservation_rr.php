<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 27 Februari 2018
 * File Name	= v_reservation_rr.php
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
	$decFormat	= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
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
		if($TranslCode == 'Information')$Information = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Process')$Process = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title		= 'Pemesanan';
		$h2_title		= 'daftar pesanan';
		if($REQ_STAT == 1)
		{
			$h2_title	= 'daftar pesanan';
		}
		elseif($REQ_STAT == 2)
		{
			$h2_title	= 'daftar pesanan menuggu';
		}
		elseif($REQ_STAT == 3)
		{
			$h2_title	= 'daftar pesanan disetujui';
		}
		elseif($REQ_STAT == 99)
		{
			$h2_title	= 'daftar pesanan anda';
		}
		
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Pesan Sekarang';
		$Available		= 'Tersedia';
		$sureDelete		= 'Anda yakin akan menghapus data ini?';		
		$Reschedule		= 'Penjadwalan<br> Ulang';
		$noFoundResv	= "tidak ditemukan pemesanan.";
	}
	else
	{
		$h1_title		= 'Reservation';	
		$h2_title		= 'book list';
		if($REQ_STAT == 1)
		{
			$h2_title	= 'book list';
		}
		elseif($REQ_STAT == 2)
		{
			$h2_title	= 'pending book list';
		}
		elseif($REQ_STAT == 3)
		{
			$h2_title	= 'approved book list';
		}
		elseif($REQ_STAT == 99)
		{
			$h2_title	= 'your book list';
		}
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Book Now';
		$Available		= 'Available';
		$sureDelete		= 'Are your sure want to delete?';
		$Reschedule		= 'Reschedule';
		$noFoundResv	= "no reservation found.";
	}
	
	if(isset($_POST['RSV_CODE']))	// MARKETING
	{
		$RSV_CODE	= $_POST['RSV_CODE'];
		$CUR_STAT	= $_POST['CUR_STAT'];
		
		if($CUR_STAT == 3)			// START USING MR
		{
			$CUR_STATN	= 8;
			$sqlRSVSTAT	= "UPDATE tbl_reservation SET RSV_STAT = $CUR_STATN WHERE RSV_CODE = '$RSV_CODE'";
		}
		elseif($CUR_STAT == 8)		// FINISH
		{
			$CUR_STATN	= 6;
			$FinishDate	= date('Y-m-d H:i:s');
			$sqlRSVSTAT	= "UPDATE tbl_reservation SET RSV_STAT = $CUR_STATN, RSV_ENDD2 = '$FinishDate' WHERE RSV_CODE = '$RSV_CODE'";
			
			// SENT MAIL TO SUBMITTER
				$RSV_MAIL	= "dianhermanto@nusakonstruksi.com";
				$sqlRsv		= "SELECT RSV_CODE, CATEG_CODE2, RSV_TITLE, RSV_DESC, RSV_STARTD2, RSV_ENDD2, RSV_MAIL
								FROM tbl_reservation WHERE RSV_CODE = '$RSV_CODE'";
				$resRsv		= $this->db->query($sqlRsv)->result();
				foreach($resRsv as $rowRsv):
					$RSV_CODE1		= $rowRsv->RSV_CODE;
					$CATEG_CODE1	= $rowRsv->CATEG_CODE2;
					$AR_NAME1		= '';
					$AR_ADDRESS1	= '';
					$sqlAR1			= "SELECT AR_NAME, AR_ADDRESS FROM tbl_apartement WHERE AR_CODE = '$CATEG_CODE1'";
					$resAR1			= $this->db->query($sqlAR1)->result();
					foreach($resAR1 as $rowAR1):
						$AR_NAME1	= $rowAR1->AR_NAME;
						$AR_ADDRESS1= $rowAR1->AR_ADDRESS;
					endforeach;
					$RSV_TITLE		= $rowRsv->RSV_TITLE;
					$RSV_DESC		= $rowRsv->RSV_DESC;
					$RSV_STARTD1	= date('Y-m-d', strtotime($rowRsv->RSV_STARTD2));
					$RSV_ENDD1		= date('Y-m-d', strtotime($rowRsv->RSV_ENDD2));
					$RSV_STARTT1	= date('H:i', strtotime($rowRsv->RSV_STARTD2));
					$RSV_ENDT1		= date('H:i', strtotime($rowRsv->RSV_ENDD2));
					$RSV_MAIL		= $rowRsv->RSV_MAIL;
				endforeach;
		
				$toMail		= ''.$RSV_MAIL.'';
				$headers 	= 'MIME-Version: 1.0' . "\r\n";
				$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
				$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
				$subject 	= "Konfirmasi Penyelesaian Pemesanan";
				$output		= '';
				$output		.= '<table width="100%" border="0">
									<tr>
										<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3">Assalamu \'alaikum wr.wb.</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Terimakasih sudah menggunakan pelayanan NKE Smart System. Pesanan Anda:</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">ID</td>
										<td width="89%">: '.$RSV_CODE1.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%" nowrap>Kamar</td>
										<td width="89%">: '.$AR_NAME1.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%" nowrap>Alamat</td>
										<td width="89%">: '.$AR_ADDRESS1.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Tanggal</td>
										<td width="89%">: '.$RSV_STARTD1.' s.d. '.$RSV_ENDD1.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Waktu</td>
										<td width="89%">: Pukul '.$RSV_STARTT1.' s.d. '.$RSV_ENDT1.' WIB</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">STATUS</td>
										<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: SELESAI</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">&nbsp;</td>
										<td width="89%">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Hormat kami,</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">ttd</td>
									</tr>
									<tr>
									<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>';
				$output		.= '</table>';
				//send email
				@mail($toMail, $subject, $output, $headers);
		}		
		$this->db->query($sqlRSVSTAT);
		
	}
	
	// CARI BOOKINGAN YANG SUDAH MASUK WAKTU NAMUN BELUM TEKAN TOMBOL START
	$dateNow	= date('Y-m-d H:i:s');
	$sqlCAg		= "tbl_reservation WHERE RSV_CATEG = 'RR' AND RSV_STARTD2 <= '$dateNow' AND RSV_ENDD2 >= '$dateNow' AND RSV_EMPID = '$DefEmp_ID' AND RSV_STAT IN (3,8)";
	$resCAg		= $this->db->count_all($sqlCAg);
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
            <div class="row">
            	<?php
					if($resCAg > 0)
					{
						$sqlAg		= "SELECT RSV_CODE, CATEG_CODE2, RSV_TITLE, RSV_DESC, RSV_STARTD2, RSV_ENDD2, RSV_STAT
										FROM tbl_reservation 
										WHERE RSV_CATEG = 'RR' AND RSV_STARTD2 <= '$dateNow' AND RSV_ENDD2 >= '$dateNow' AND RSV_EMPID = '$DefEmp_ID'
											AND RSV_STAT IN (3,8)";
						$resAg		= $this->db->query($sqlAg)->result();
						$theRow		= 0;
						foreach($resAg as $rowAg):
							$theRow			= $theRow + 1;
							$RSV_CODE		= $rowAg->RSV_CODE;
							$CATEG_CODE2	= $rowAg->CATEG_CODE2;
							$AR_NAME		= '';
							$AR_ADDRESS		= '';
							$sqlAR 			= "SELECT AR_NAME, AR_ADDRESS FROM tbl_apartement WHERE AR_CODE = '$CATEG_CODE2'";
							$resAR 			= $this->db->query($sqlAR)->result();
							foreach($resAR as $rowAR) :
								$AR_NAME 	= $rowAR->AR_NAME;	
								$AR_ADDRESS	= $rowAR->AR_ADDRESS;		
							endforeach;
							$RSV_TITLE		= $rowAg->RSV_TITLE;
							$RSV_DESC		= $rowAg->RSV_DESC;
							$RSV_STARTD2	= $rowAg->RSV_STARTD2;
							$RSV_ENDD2		= $rowAg->RSV_ENDD2;
							$RSV_STAT		= $rowAg->RSV_STAT;
							
							$STARTDD1		= date('d', strtotime($RSV_STARTD2));
							$STARTDD2		= date('d', strtotime($RSV_ENDD2));
							
							$STARTMD1		= date('F', strtotime($RSV_STARTD2));
							$STARTMD2		= date('F', strtotime($RSV_ENDD2));
							
							if(($STARTDD1 == $STARTDD2) AND ($STARTMD1 == $STARTMD2))
								$STARTDDV	= "$STARTDD1 $STARTMD1";
							elseif(($STARTDD1 != $STARTDD2) AND ($STARTMD1 == $STARTMD2))
								$STARTDDV	= "$STARTDD1 - $STARTDD2 $STARTMD1";
							else
								$STARTDDV	= "$STARTDD1 $STARTMD1 - $STARTDD2 $STARTMD2";
							
							$STARTDM		= date('F', strtotime($RSV_ENDD2));
							$STARTDY		= date('Y', strtotime($RSV_ENDD2));
							
							$HOURS1			= date('H:i', strtotime($RSV_STARTD2));
							$HOURS2			= date('H:i', strtotime($RSV_ENDD2));
							
							$HOURSCOMP		= "$HOURS1 - $HOURS2";
							$DATECOMP		= "$STARTDDV $STARTDY";
							
							if($RSV_STAT == 3)
							{
								$CLASSTAT	= "glyphicon glyphicon-play";
								$CUR_STAT	= 3;
								$BUTTV		= "START";
							}
							elseif($RSV_STAT == 8)
							{
								$CLASSTAT	= "glyphicon glyphicon-stop";
								$CUR_STAT	= 8;
								$BUTTV		= "FINISH";
							}
								
							?>
                            <form class="form-horizontal" name="form_1" method="post" action="" onSubmit="return chekData_1('<?php echo $theRow; ?>')">
                                <div class="col-md-4">
                                    <div class="box box-warning box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo $AR_NAME; ?></h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td colspan="2" style="text-align:center; font-size:18px">
														<?php echo $RSV_TITLE; ?>
                                                        <input type="hidden" name="RSV_CODE_<?php echo $theRow; ?>" id="RSV_CODE_<?php echo $theRow; ?>" value="<?php echo $RSV_CODE; ?>">
                                                        <input type="hidden" name="CUR_STAT<?php echo $theRow; ?>" id="CUR_STAT<?php echo $theRow; ?>" value="<?php echo $CUR_STAT; ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center; font-size:36px; color:#060"><?php echo "$HOURSCOMP"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center; font-size:18px"><?php echo "$DATECOMP"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center; font-style:italic"><?php echo $RSV_DESC; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" nowrap style="text-align:left">&nbsp;</td>
                                                    <td width="50%" nowrap style="text-align:left">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center;">
                                                      <button type="button" class="btn btn-warning" style="height:50px" onClick="chekData_1('<?php echo $theRow; ?>')">
                                                        <i class="<?php echo $CLASSTAT; ?>"></i><font size="+1">&nbsp;&nbsp;<?php echo $BUTTV; ?></font>
                                                      </button>
                                                      </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div id="loading_1" class="overlay" style="display:none">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
						endforeach;
					}
					else
					{
						?>
                            <form class="form-horizontal" name="form_1" method="post" action="">
                                <div class="col-md-4">
                                    <div class="box box-warning box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">&nbsp;</h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <table width="100%" border="0">
                                                <tr>
                                                  <td colspan="2" style="text-align:center; font-size:18px">
														<?php echo $noFoundResv; ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center; font-size:22px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center; font-size:20px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" nowrap style="text-align:left">&nbsp;</td>
                                                    <td width="50%" nowrap style="text-align:left">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div id="loading_1" class="overlay">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </div>
									</div>
                                </div>
                            </form>
                    	<?php
					}
				?>
                <form class="form-horizontal" name="form_x" method="post" action="" style="display:none">
                	<input type="text" name="RSV_CODE" id="RSV_CODE" value="">
                	<input type="text" name="CUR_STAT" id="CUR_STAT" value="">
                    <button class="btn btn-warning">
                        <i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;OK
                    </button>
                </form>
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
  
	function chekData_1(theRow)
	{
		RSV_CODE		= document.getElementById('RSV_CODE_'+theRow).value;
		CUR_STAT		= document.getElementById('CUR_STAT'+theRow).value;
		
		document.getElementById('RSV_CODE').value = RSV_CODE;
		document.getElementById('CUR_STAT').value = CUR_STAT;
		document.forms['form_x'].submit();		
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>