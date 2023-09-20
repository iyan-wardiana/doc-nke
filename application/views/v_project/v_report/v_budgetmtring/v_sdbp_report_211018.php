<?php
/* 
	* Author		   = Dian Hermanto
	* Create Date	= 20 April 2018
	* File Name	 = v_sdbp_report.php
	* Location		 = -
*/

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJCOST	= 0;
$PRJDATE	= date('Y/m/d');
$PRJDATE_CO	= date('Y/m/d');
$sqlPRJ		= "SELECT PRJDATE, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJCOST	= $rowPRJ->PRJCOST;
	$PRJDATE	= date('Y/m/d', strtotime($rowPRJ->PRJDATE));
	$PRJDATE_CO	= date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
endforeach;

function cut_text($var, $len = 200, $txt_titik = "-") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
	if (strlen ($var) < $len) 
	{ 
		return $var; 
	}
	if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
	{
		return $match [1] . $txt_titik;
	}
	else
	{
		return substr ($var, 0, $len) . $txt_titik;
	}
}

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
			        <td width="16%">
			            <div id="Layer1">
			                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
			                <a href="#" onClick="window.close();" class="button"> close </a>
			            </div>
			        </td>
			        <td width="45%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
			        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
			    </tr>
			    <tr>
			        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
			        <td class="style2">&nbsp;</td>
			        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
			    </tr>
			    <tr>
			        <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
			        <td colspan="2" class="style2" style="text-align:left; font-weight:bold; text-transform:uppercase; font-size:20px">&nbsp;</td>
			        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
			  	</tr>
			    <tr>
			        <td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:16px"><?php /*?>&nbsp;Periode : <?php */?>
			        <?php 
							/*$CRDate	= date_create($End_Date);
							echo date_format($CRDate,"d-m-Y");*/
							$StartDate	= date('d-m-Y');
							//echo "$StartDate";
						?></td>
			    </tr>
			    <tr>
			        <td colspan="2" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
			    </tr>
			    <tr>
			        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
			            <table width="100%">
			            	<!--<tr style="text-align:left; font-style:italic">
			        			<td width="8%" nowrap valign="top">Type Dokumen</td>
			        			<td width="1%">:</td>
			        			<td width="91%"><?php// echo $DOCTYPE1; ?></td>
			    			</tr>-->
			                <tr style="text-align:left; font-style:italic">
			                    <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
			                    <td width="0%">:</td>
			                    <td width="92%"><?php echo "$h1_title"; ?></span></td>
			                </tr>
			                <tr style="text-align:left; font-style:italic">
			                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
			                    <td width="0%">:</td>
			                    <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
			                </tr>
			                <tr style="text-align:left; font-style:italic">
			                  	<td nowrap valign="top">NAMA PROYEK</td>
			                  	<td>:</td>
			                  	<td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
			              	</tr>
			                <tr style="text-align:left; font-style:italic">
			                  	<td nowrap valign="top">KATEGORI LAPORAN</td>
			                  	<td>:</td>
			                  	<td><?php echo $ITMGRP_NM; ?></td>
			                </tr>
			                <tr style="text-align:left; font-style:italic">
			                  	<td nowrap valign="top">TANGGAL CETAK</td>
			                  	<td>:</td>
			                  	<td><?php echo date('Y-m-d:H:i:s'); ?></td>
			                </tr>
			            </table>
			        </td>
			    </tr>
			    <tr>
			        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
			    </tr>
			    <tr>
			    	<td colspan="3" class="style2" style="text-align:center">
			            <table width="100%" border="1" rules="all">
			                <tr style="background:#CCCCCC">
								<th width="5%" rowspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
								<th colspan="4" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">DAFTAR ITEM</th>
								<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">BUDGET AWAL</th>
								<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">PERUBAHAN</th>
								<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
								<th rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">PROGRES<br>KUMULATIF</th>
								<th colspan="4" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">PERMINTAAN</th>
								<th colspan="4" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">REALISASI</th>
								<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">PREDISKSI 100%</th>
			              	</tr>
			                <tr style="background:#CCCCCC">
								<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">SAAT INI</th>
								<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">SISA BUDGET THD REQUEST</th>
								<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">SAAT INI</th>
								<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">SISA BUDGET THD REALISASI</th>
			                </tr>
			                <tr style="background:#CCCCCC">
								<th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KODE</th>
								<th width="9%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">NAMA ITEM</th>
								<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">SATUAN</th>
								<th width="5%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">GROUP</th>
								<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">VOL.</th>
								<!-- <th width="5%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">HARGA</th> -->
								<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
								<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
								<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
								<th width="1%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
								<th width="1%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
								<th width="1%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">%</th>
								<th width="1%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
								<th width="1%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
								<th width="1%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
								<th width="1%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
								<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
								<th width="9%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
								<th width="18%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
								<th width="18%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
								<th width="18%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TOTAL NILAI</th>
								<th width="18%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">DEVIASI</th>
			                </tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
						   		if($ITM_GROUP == 'All')
								{
									$ADDQUERY	= "";
								}
								else
								{
									$ADDQUERY	= "AND A.ITM_GROUP = '$ITM_GROUP'";
								}
								
						   		$theRow			= 0;
						   		$GITM_VOLMBG	= 0;
						   		$GITM_BUDG		= 0;
								$GADD_VOLM 		= 0;
								$GADD_TOTAL		= 0;
								$GITM_VOLM2		= 0;
								$GITM_BUDG2		= 0;
								$GPO_VOLM		= 0;
								$GPO_AMOUNT		= 0;
								$GREM_VOLM		= 0;
								$GREM_AMOUNT	= 0;
								$GITM_OUT		= 0;
								$GUM_AMOUNT		= 0;
								$GREM_VOLM2		= 0;
								$GREM_AMOUNT2	= 0;
								$GPRED_DEV 		= 0;
							   	$sqlAMC			= "tbl_item_temp A WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' $ADDQUERY";
								$resAMC			= $this->db->count_all($sqlAMC);
								if($resAMC > 0)
								{
									$ITMGROUP 	= "";
									$sqlAM		= "SELECT A.*
													FROM tbl_item_temp A
													WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' $ADDQUERY";
									$resAM		= $this->db->query($sqlAM)->result();
									foreach($resAM as $rowAM):
										$theRow		= $theRow + 1;
										$ITM_GROUP	= $rowAM->ITM_GROUP;
										$ITM_CODE	= $rowAM->ITM_CODE;
										$ITM_NAME1	= $rowAM->ITM_NAME;
										$ITM_NAME	= cut_text ($ITM_NAME1, 70);
										$ITM_UNIT	= $rowAM->ITM_UNIT;
										$ITM_VOLM	= $rowAM->ITM_VOLM;				// STOCK
										$ITM_LASTP	= $rowAM->ITM_LASTP;
										$ITM_VOLMBG	= $rowAM->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rowAM->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT

										$sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
														FROM tbl_joblist_detail_temp WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										$resJBUD	= $this->db->query($sqlJBUD)->result();
										foreach($resJBUD as $rowJBUD):
											$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
											$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
											$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										endforeach;

										if(strtoupper($ITM_UNIT) == 'LS')
										{
											$ITM_BUDG 	= $rowAM->ITM_PRICE;
											if($ITM_VOLMBG == 0)
												$ITM_BUDG 	= 0;
										}
										
										// ADDENDUM
											$ADD_VOLM	= $rowAM->ADDVOLM;
											$ADD_TOTAL	= $rowAM->ADDCOST;
											$ADDM_VOLM	= $rowAM->ADDMVOLM;
											$ADDM_TOTAL	= $rowAM->ADDMCOST;
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;
										
										$KUM_PROG	= 0;
										
										$PO_VOLM	= $rowAM->PO_VOLM;
										$PO_AMOUNT	= $rowAM->PO_AMOUNT;
										if($PO_VOLM == 0)
											$PO_PRICE	= 0;
										else
											$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;
											
										$ITM_OUT	= $rowAM->ITM_OUT;
										$ITM_OUTP	= $rowAM->ITM_OUTP;
										$ITM_OUTP1	= $rowAM->UM_AMOUNT;	// UNTUK LS
										$UM_AMOUNT	= $ITM_OUTP;
										if(strtoupper($ITM_UNIT) == 'LS')
										{
											$UM_AMOUNT 		= $ITM_OUTP1;
										}

										if($ITM_OUT == 0)
											$OPN_PRICE	= 0;
										else
											$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $PO_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $PO_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REQUEST
											$REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											$REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											$PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_DEV	= round($PRED_VAL,2) - round($ITM_BUDG2,2);

										// SUB TOTAL PER KATEGORI

										if(strtoupper($ITM_UNIT) == 'LS')
										{
											$ITM_VOLMBG 	= 1;
											if($ITMVOLMBG == 0)
												$ITM_VOLMBG = 0;

											$ADD_VOLM 		= 1;
											if($ADD_TOTAL == 0)
												$ADD_VOLM 	= 0;
											$ITM_VOLM2 		= 1;
											$PO_VOLM		= 1;
											if($PO_AMOUNT == 0)
												$PO_VOLM	= 0;
											$REM_VOLM 		= 1;
											if($REM_AMOUNT2 == 0)
												$REM_VOLM	= 0;
											$ITM_OUT 		= 1;
											$REM_VOLM2 		= 1;
											$PRED_VAL 		= $ITM_BUDG;
											$PRED_DEV		= $UM_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

			               				if(($ITMGROUP != $ITM_GROUP) && $theRow > 1)
			               				{
										?>
				                            <tr style="background:#CCCCCC; font-weight: bold;">
				                                <td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
				                                	T O T A L</td>
				                                <!-- BUDGET AWAL --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
					                                <!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GITM_BUDG, 2); ?></td>

				                                <!-- PERUBAHAN --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GADD_TOTAL, 2); ?></td>
					                                
				                                <!-- SETELAH PERUBAHAN --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GITM_BUDG2, 2); ?></td>
					                                
				                                <!-- PROGRES KUMULATIF --->
				                                	<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($KUM_PROG, 2); ?></td>
					                                
				                                <!-- PERMINTAAN : SAAT INI --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GPO_AMOUNT, 2); ?></td>
					                                
				                                <!-- PERMINTAAN : SISA BUDGET THD REQUEST --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREM_AMOUNT, 2); ?></td>
					                                
				                                <!-- REALISASI : SAAT INI --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GUM_AMOUNT, 2); ?></td>
					                                
				                                <!-- REALISASI : SISA BUDGET THD REALISASI --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREM_AMOUNT2, 2); ?></td>
					                                
				                                <!-- PREDISKSI 100% --->
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GPRED_DEV, 2); ?></td>
					                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
					                                	<?php //echo number_format($PRED_DEV, 2); ?></td>
				                            </tr>
											<?php
										   		$GITM_VOLMBG	= 0;
										   		$GITM_BUDG		= 0;
												$GADD_VOLM 		= 0;
												$GADD_TOTAL		= 0;
												$GITM_VOLM2		= 0;
												$GITM_BUDG2		= 0;
												$GPO_VOLM		= 0;
												$GPO_AMOUNT		= 0;
												$GREM_VOLM		= 0;
												$GREM_AMOUNT	= 0;
												$GITM_OUT		= 0;
												$GUM_AMOUNT		= 0;
												$GREM_VOLM2		= 0;
												$GREM_AMOUNT2	= 0;
												$GPRED_DEV		= 0;
			               				}

										$GITM_VOLMBG	= $GITM_VOLMBG + $ITM_VOLMBG;
										$GITM_BUDG		= $GITM_BUDG + $ITM_BUDG;
										$GADD_VOLM 		= $GADD_VOLM + $ADD_VOLM;
										$GADD_TOTAL		= $GADD_TOTAL + $ADD_TOTAL;
										$GITM_VOLM2		= $GITM_VOLM2 + $ITM_VOLM2;
										$GITM_BUDG2		= $GITM_BUDG2 + $ITM_BUDG2;
										$GPO_VOLM		= $GPO_VOLM + $PO_VOLM;
										$GPO_AMOUNT		= $GPO_AMOUNT + $PO_AMOUNT;
										$GREM_VOLM		= $GREM_VOLM + $REM_VOLM;
										$GREM_AMOUNT	= $GREM_AMOUNT + $REM_AMOUNT;
										$GITM_OUT		= $GITM_OUT + $ITM_OUT;
										$GUM_AMOUNT		= $GUM_AMOUNT + $UM_AMOUNT;
										$GREM_VOLM2		= $GREM_VOLM2 + $REM_VOLM2;
										$GREM_AMOUNT2	= $GREM_AMOUNT2 + $REM_AMOUNT2;
										$GPRED_DEV 		= $GPRED_DEV + $PRED_VAL;
										?>
											<tr>
				                                <td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo $theRow; ?></td>
				                                <td nowrap style="text-align:left;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo $ITM_CODE; ?></td>
				                                <td nowrap style="text-align:left;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo $ITM_NAME; ?></td>
				                                <td nowrap style="text-align:center;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo $ITM_UNIT; ?></td>
				                                <td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo $ITM_GROUP; ?></td>
				                                <!-- BUDGET AWAL --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
					                                <!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($ITM_BUDG, 2); ?></td>

				                                <!-- PERUBAHAN --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($ADD_VOLM, 2); ?></td>
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($ADD_TOTAL, 2); ?></td>
					                                
				                                <!-- SETELAH PERUBAHAN --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($ITM_VOLM2, 2); ?></td>
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($ITM_BUDG2, 2); ?></td>
					                                
				                                <!-- PROGRES KUMULATIF --->
				                                	<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($KUM_PROG, 2); ?></td>
					                                
				                                <!-- PERMINTAAN : SAAT INI --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($PO_VOLM, 2); ?></td>
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($PO_AMOUNT, 2); ?></td>
					                                
				                                <!-- PERMINTAAN : SISA BUDGET THD REQUEST --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REM_VOLM, 2); ?></td>
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REM_AMOUNT, 2); ?></td>
					                                
				                                <!-- REALISASI : SAAT INI --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($ITM_OUT, 2); ?></td>
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($UM_AMOUNT, 2); ?></td>
					                                
				                                <!-- REALISASI : SISA BUDGET THD REALISASI --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REM_VOLM2, 2); ?></td>
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
					                                
				                                <!-- PREDISKSI 100% --->
					                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($PRED_VAL, 2); ?></td>
					                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>">
					                                	<?php echo number_format($PRED_DEV, 2); ?></td>
				                            </tr>
			               				<?php
			               				$ITMGROUP 	= $ITM_GROUP;
									endforeach;
									?>
			                            <tr style="background:#CCCCCC; font-weight: bold;">
			                                <td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
			                                	T O T A L</td>
			                                <!-- BUDGET AWAL --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
				                                <!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GITM_BUDG, 2); ?></td>

			                                <!-- PERUBAHAN --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GADD_TOTAL, 2); ?></td>
				                                
			                                <!-- SETELAH PERUBAHAN --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GITM_BUDG2, 2); ?></td>
				                                
			                                <!-- PROGRES KUMULATIF --->
			                                	<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($KUM_PROG, 2); ?></td>
				                                
			                                <!-- PERMINTAAN : SAAT INI --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GPO_AMOUNT, 2); ?></td>
				                                
			                                <!-- PERMINTAAN : SISA BUDGET THD REQUEST --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREM_AMOUNT, 2); ?></td>
				                                
			                                <!-- REALISASI : SAAT INI --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GUM_AMOUNT, 2); ?></td>
				                                
			                                <!-- REALISASI : SISA BUDGET THD REALISASI --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREM_AMOUNT2, 2); ?></td>
				                                
			                                <!-- PREDISKSI 100% --->
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GPRED_DEV, 2); ?></td>
				                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
				                                	<?php //echo number_format($PRED_DEV, 2); ?></td>
			                            </tr>
									<?php
								}
								else
								{
									?>
			                            <tr>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
			                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
			                            </tr>
									<?php
								}
							?>
			            </table>
		        	</td>
			    </tr>
			</table>
		</section>
	</body>
</html>