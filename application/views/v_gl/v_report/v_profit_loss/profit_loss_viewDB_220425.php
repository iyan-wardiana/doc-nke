<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 Junli 2018
	* File Name	= profit_loss_view.php
	* Location		= -
*/
$Periode1 = date('YmdHis');
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];

$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJCOST 	= $rowPRJ->PRJCOST;
endforeach;

$PeriodeD 		= date('Y-m-d',strtotime($END_DATE));

if($CATEGREP == 'M')
	$CATEGREPD = "M / Material";
elseif($CATEGREP == 'U')
	$CATEGREPD = "U / Upah";
elseif($CATEGREP == 'T')
	$CATEGREPD = "T / Alat";
elseif($CATEGREP == 'SC')
	$CATEGREPD = "SUB / Subkon";
elseif($CATEGREP == 'GE')
	$CATEGREPD = "GE - O / Biaya Adm. Umum dan Overhead";
elseif($CATEGREP == 'I')
	$CATEGREPD = "I / Rupa-Rupa";
else
	$CATEGREPD = "Lainnya";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
		<title><?php echo $h1_title; ?></title>
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
		<div class="page">
			<section class="content">
		        <table width="100%" border="0" style="size:auto">
		            <tr>
		                <td width="100%" class="style2" style="text-align:left;">&nbsp;</td>
		            </tr>
		          	<tr>
		                <td class="style2" style="text-align:left;">
		                    <table width="100%" style="size:auto; font-size:12px;">
		                        <tr style="text-align:left;">
		                            <td width="23%" nowrap>PROYEK</td>
		                          	<td width="1%">:</td>
		                            <td style="text-align:left; font-weight:bold"><?php echo strtoupper($PRJNAME); ?></td>
		                      	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>PERIODE</td>
		                          	<td>:</td>
		                          	<td style="text-align:left;">s.d. <?php echo $PeriodeD;?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>KATEGORI</td>
		                          	<td>:</td>
		                          	<td><?php echo $CATEGREPD;?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                          <td nowrap valign="top">&nbsp;</td>
		                          <td>&nbsp;</td>
		                          <td>&nbsp;</td>
		                        </tr>
		                    </table>
				    	</td>
		            </tr>
		            <tr>
						<td class="style2" style="text-align:left; font-size:12px">
		              	<table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
		                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:14px;">
		                    	<td width="5%" style="text-align:center">&nbsp;No.&nbsp;</td>
		                      	<td width="11%" style="text-align:center">Kode</td>
		                      	<td width="12%" style="text-align:center">Tanggal</td>
		                        <td width="40%" style="text-align:center">Deskripsi</td>
		                        <td width="10%" style="text-align:center" nowrap>Jumlah</td>
		                        <td width="10%" style="text-align:center" nowrap>Total</td>
		                    </tr>
		                    <?php
								if($CATEGREP == 'GE')
									$CATEGREP = "GE','O";
									
								$no		= 0;
								$totAm	= 0;
								$sqlJD	= "SELECT DISTINCT
												A.JournalH_Code,
												B.JournalType,
												B.Manual_No,
												A.JournalH_Date,
												A.Base_Debet,
												A.Base_Kredit,
												B.JournalH_Desc
											FROM
												tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                                				AND B.JournalType != 'IR' 
											WHERE
												A.ITM_CATEG IN ('$CATEGREP')
												AND B.GEJ_STAT = 3
												AND A.Base_Debet > 0
												AND A.proj_Code = '$PRJCODE'
												AND A.JournalH_Date <= '$END_DATE'";
								$resJD	= $this->db->query($sqlJD)->result();
								foreach($resJD as $rowJD) :
									$no				= $no+1;
									$JournalH_Code 	= $rowJD->JournalH_Code;
									$Manual_No 		= $rowJD->Manual_No;
									if($Manual_No == '')
										$Manual_No	= $JournalH_Code;
									$JournalH_Date 	= $rowJD->JournalH_Date;
									$JournalType 	= $rowJD->JournalType;
									$Base_Debet 	= $rowJD->Base_Debet;							
									$totAm			= $totAm + $Base_Debet;
									$JournalDesc 	= $rowJD->JournalH_Desc;							
									if($JournalDesc == '')
									{
										if($JournalType == 'UM')
											$JournalDesc	= "Penggunaan material";
										elseif($JournalType == 'OPN')
											$JournalDesc	= "Opname";
									}
									?>
		                                <tr>
		                                    <td style="text-align:center"><?php echo $no; ?></td>
		                                    <td style="text-align:center" nowrap><?php echo $Manual_No; ?></td>
		                                    <td style="text-align:center" nowrap><?php echo $JournalH_Date; ?></td>
		                                    <td style="text-align:left"><?php echo $JournalDesc; ?></td>
		                                    <td style="text-align:right"><?php echo number_format($Base_Debet,2); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($totAm,2); ?></td>
		                                </tr>
		                    		<?php
								endforeach;
							?>
		                </table>
		           	  </td>
		            </tr>
		        </table>
			</section>
		</div>
	</body>
</html>