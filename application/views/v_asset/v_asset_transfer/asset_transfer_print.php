<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Agustus 2018
 * File Name	= asset_transfer_print.php
 * Location		= -
*/

//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$ASTSF_DATE = date('Y-m-d',strtotime($ASTSF_DATE));

$PRJNAME 	= '';
$PRJLOCT 	= '';
$PRJ_MNG_NM	= '';
$sqlPRJ 	= "SELECT A.PRJNAME, A.PRJLOCT, A.PRJ_MNG, B.First_Name, B.Last_Name
				FROM tbl_project A
					LEFT JOIN tbl_employee B ON A.PRJ_MNG = B.Emp_ID
				WHERE PRJCODE = '$PRJCODE'";
$resPRJ = $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
  $PRJNAME 		= $rowPRJ->PRJNAME;
  $PRJLOCT 		= $rowPRJ->PRJLOCT;
  $First_Name	= $rowPRJ->First_Name;
  $Last_Name	= $rowPRJ->Last_Name;
  $PRJ_MNG_NM 	= "$First_Name $Last_Name";
endforeach;

$ASTSF_SENDER	= "-";
$ASTSF_RECEIVER	= "-";
$sqlTSFH 		= "SELECT A.ASTSF_SENDER, A.ASTSF_RECEIVER
					FROM tbl_asset_tsfh A
					WHERE ASTSF_NUM = '$ASTSF_NUM'";
$resTSFH = $this->db->query($sqlTSFH)->result();
foreach($resTSFH as $rowTSFH) :
  $ASTSF_SENDER		= $rowTSFH->ASTSF_SENDER;
  $ASTSF_RECEIVER 	= $rowTSFH->ASTSF_RECEIVER;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <title><?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="overflow:auto">
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="10%" rowspan="2" class="style2" style="text-align:left; font-weight:bold; vertical-align:top"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td width="77%" class="style2">&nbsp;</td>
        <td width="13%" class="style2" style="text-align:left; font-weight:bold"><input type="checkbox"> MOBILISASI</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:center; font-weight:bold; font-size:18px"><u><?php echo $h1_title; ?></u><br></td>
        <td class="style2" style="text-align:left; font-weight:bold;" nowrap><input type="checkbox"> 
          DEMOBILISASI</td>
      </tr>
    <tr>
        <td class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
        <td class="style2" style="text-align:center; font-style:italic">No. : <?php echo $ASTSF_CODE; ?></td>
        <td class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="3" class="style2" style="text-align:left">
      	<table width="100%" border="0" style="size:auto">
        	<tr>
            	<td width="100%">
                	<table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="17%">Nomor &amp; Nama Proyek </td>
                            <td width="1%">:</td>
                            <td width="38%"><?php echo "$PRJCODE - $PRJNAME";?></td>
                            <td width="18%">Tanggal Kirim</td>
                            <td width="0%">:</td>
                            <td width="26%"><?php echo date("d M Y", strtotime($ASTSF_SENDD)); ?></td>
                        </tr>
                        <tr>
                          <td>Lokasi</td>
                          <td>:</td>
                          <td><?php echo $PRJLOCT; ?></td>
                          <td>Tanggal Terima</td>
                          <td>:</td>
                          <td><?php echo date("d M Y", strtotime($ASTSF_RECD)); ?></td>
                      </tr>
                        <tr>
                          <td>Kepala Proyek</td>
                          <td>:</td>
                          <td><?php echo $PRJ_MNG_NM; ?></td>
                          <td>Penerima</td>
                          <td>:</td>
                          <td><?php echo $ASTSF_RECEIVER; ?></td>
                      </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>Asal Pengiriman</td>
                          <td>:</td>
                          <td><?=$PRJLOCT?></td>
                      </tr>
                    </table>
                </td>
            </tr>
        </table>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="4%" height="37" nowrap style="text-align:center; font-weight:bold">No.</td>
                    <td width="23%" nowrap style="text-align:center; font-weight:bold">Deskripsi</td>
                    <td width="10%" nowrap style="text-align:center; font-weight:bold">No. PO</td>
                    <td width="9%" nowrap style="text-align:center; font-weight:bold">Volume</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">Satuan</td>
                    <td width="9%" nowrap style="text-align:center; font-weight:bold">Berat</td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold">Kondisi</td>
                    <td width="13%" nowrap style="text-align:center; font-weight:bold">No. Inventaris</td>
                    <td width="17%" nowrap style="text-align:center; font-weight:bold">Keterangan</td>
                </tr>
                <?php
					$maxRow	= 32;
					$theRow	= 0;
					$sqlq1 	= "SELECT A.ASTSF_NUM, A.ASTSF_CODE, A.ASTSF_DATE, A.AS_CODE, A.ASTSF_DESC,
										C.AS_NAME, C.AS_DESC,
										1 AS AST_QTY, 'UNIT' AS AST_UNIT, 1 AS AST_WEIGHT, 1 AS AST_COND
									FROM tbl_asset_tsfd A
										INNER JOIN tbl_asset_tsfh B ON A.ASTSF_NUM = B.ASTSF_NUM
										LEFT JOIN tbl_asset_list C ON A.AS_CODE = C.AS_CODE
									WHERE A.ASTSF_NUM = '$ASTSF_NUM'";
					$resq1 	= $this->db->query($sqlq1)->result();
					foreach($resq1 as $rowsqlq1) :
						$theRow			= $theRow + 1;
						$ASTSF_NUM 		= $rowsqlq1->ASTSF_NUM;
						$ASTSF_CODE 	= $rowsqlq1->ASTSF_CODE;
						$ASTSF_DATE 	= $rowsqlq1->ASTSF_DATE;
						$AS_CODE 		= $rowsqlq1->AS_CODE;
						$AS_NAME		= $rowsqlq1->AS_NAME;
						$AS_DESC		= $rowsqlq1->AS_DESC;
						$ASTSF_DESC		= $rowsqlq1->ASTSF_DESC;
						$AST_QTY 		= $rowsqlq1->AST_QTY;
						$AST_UNIT 		= $rowsqlq1->AST_UNIT;
						$AST_WEIGHT		= $rowsqlq1->AST_WEIGHT;
						$AST_COND		= $rowsqlq1->AST_COND;
						?>
						<tr>
							<td nowrap style="text-align:center;">
								<?php echo "$theRow."; ?>
							</td>
							<td nowrap style="text-align:left;">
								<?php echo "$AS_NAME"; ?>
							</td>
							<td nowrap style="text-align:left;">
								<?php echo "-"; ?>
							</td>
							<td nowrap style="text-align:right;">
								<?php echo "$AST_QTY"; ?>
							</td>
							<td nowrap style="text-align:center;">
								<?php echo "$AST_UNIT"; ?>
							</td>
							<td nowrap style="text-align:right;">
								<?php echo "$AST_WEIGHT"; ?>
							</td>
							<td nowrap style="text-align:center;">
								<?php echo "$AST_COND"; ?>
							</td>
							<td style="text-align:left;" nowrap>
								<?php
									echo "-";
								?>
							</td>
							<td style="text-align:left;" nowrap>
								<?php
									echo "$ASTSF_DESC";
								?>
							</td>
						</tr>
						<?php
                    endforeach;
					for($i = $theRow; $i < $maxRow; $i++)
					{
						$theRow	= $theRow + 1;
						?>
						<tr>
							<td nowrap style="text-align:center;">
								<?php echo "$theRow."; ?>
							</td>
							<td nowrap style="text-align:left;">
								<?php echo ""; ?>
							</td>
							<td nowrap style="text-align:left;">
								<?php echo ""; ?>
							</td>
							<td nowrap style="text-align:right;">
								<?php echo ""; ?>
							</td>
							<td nowrap style="text-align:center;">
								<?php echo ""; ?>
							</td>
							<td nowrap style="text-align:right;">
								<?php echo ""; ?>
							</td>
							<td nowrap style="text-align:center;">
								<?php echo ""; ?>
							</td>
							<td style="text-align:left;" nowrap>
								<?php
									echo "";
								?>
							</td>
							<td style="text-align:left;" nowrap>
								<?php
									echo "";
								?>
							</td>
						</tr>
						<?php
					}
                ?>
                <tr>
                    <td nowrap style="text-align:center;">&nbsp;</td>
                    <td colspan="4" nowrap style="text-align:center; font-weight:bold">Berat Total</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:center;">
                        <?php echo ""; ?>
                    </td>
                    <td style="text-align:left;" nowrap>&nbsp;</td>
                    <td style="text-align:left;" nowrap>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="9" nowrap style="text-align:center;">&nbsp;</td>
                </tr>
                <tr>
                  <td nowrap style="text-align:center;">&nbsp;</td>
                  <td colspan="8" nowrap style="text-align:center; font-weight:bold">
                  	Alat dan Barang yang sudah diterima menjadi tanggung jawab sepenuhnya oleh Penerima
                  </td>
                </tr>
                <tr height="40px">
                  <td nowrap style="text-align:center;">&nbsp;</td>
                  <td colspan="8" nowrap style="text-align:left; font-weight:bold; vertical-align:top">Catatan : </td>
                </tr>
			</table>
          	<table width="100%" border="1" style="size:auto; border-top:hidden" rules="all">
                <tr>
                    <td width="30%">
                    	<div class="col-xs-12" style="text-align:center">Pihak Pertama;</div>
                    	<div class="col-xs-12" style="text-align:center; font-weight:bold">PT. Mega Sukma</div>
                        <br><br><br>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	_____________________
                        </div>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	Yang Memberi
                        </div>
                    </td>
                    <td width="30%" valign="top">
                    	<div class="col-xs-12" style="text-align:center">Logistik & Alat;</div>
                    	<div class="col-xs-12" style="text-align:center; font-weight:bold">PT. Mega Sukma</div>
                        <br><br><br>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	_____________________
                        </div>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	Proyek
                        </div>
                    </td>
                    <td width="30%" valign="top">
                    	<div class="col-xs-12" style="text-align:center">Pihak Kedua;</div>
                    	<div class="col-xs-12" style="text-align:center; font-weight:bold">PT. ............</div>
                        <br><br><br>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	_____________________
                        </div>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	Yang Menerima
                        </div>
                    </td>
                    <td width="10%" nowrap>
                    	<div class="col-xs-12" style="text-align:left">No. Form : 06.R0/LOG/17</div>
                    	<div class="col-xs-12" style="text-align:center; font-weight:bold">&nbsp;</div>
                        <br>Revisi : 0<br><br>
                        <div class="col-xs-12" style="text-align:left">
                        Tanggal : <?php echo $ASTSF_DATE; ?>
                      	</div>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	&nbsp;
                        </div>
                    </td>
              </tr>
                <tr>
                  <td colspan="2" style="text-align:center; font-weight:bold">Seluruh Dokumen Daftar Lalu Lintas Alat & Barang Yang Telah Terkirim Ke<br>Proyek Harus Diketahui oleh Project Manager</td>
                  <td colspan="2" valign="top">
                    	<div class="col-xs-12" style="text-align:center">Mengetahui,</div>
                    	<div class="col-xs-12" style="text-align:center; font-weight:bold">&nbsp;</div>
                        <br><br>
                        <div class="col-xs-12" style="padding-left:15px; text-align:center">
                        	_____________________
                        </div>
                        <div class="col-xs-12" style="padding-left:15px;text-align:center; font-weight:bold">Project Manager</div>
                    </td>
                </tr>
                <tr>
                  <td colspan="4" style="text-align:left; font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Keterangan : 1. Stock&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Beli Baru&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Beli Bekas</td>
                </tr>
          </table>
	  </td>
    </tr>
</table>
</section>
</body>
</html>