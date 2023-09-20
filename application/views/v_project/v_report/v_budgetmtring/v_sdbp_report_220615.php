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

    function cut_text($var, $len = 200, $txt_titik = "-") 
    {
        $var1   = explode("</p>",$var);
        $var    = $var1[0];
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
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Laporan Pekerjaan Tambah/Kurang</title>
	    <style>
	        body { 
	            /*margin: 0;*/
	            padding: 0;
	            background-color: #FAFAFA;
	            font: 12pt Arial, Helvetica, sans-serif;
	        }

	        * {
	            box-sizing: border-box;
	            -moz-box-sizing: border-box;
	        }

	        .page {
	            width: 600mm;
	            /*min-height: 296mm;*/
	            padding-left: 1cm;
	            padding-right: 1cm;
	            padding-top: 1cm;
	            padding-bottom: 1cm;
	            margin: 0.5cm auto;
	            border: 1px #D3D3D3 solid;
	            border-radius: 5px;
	            background: white;
	           /* background-size: 400px 200px !important;*/
	            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	        }

	        @page {
	            /*size: auto;*/
    			/*size: A3;*/
	            margin: 0;
	        }

	        @media print {

	            @page{size: landscape;}
	            .page {
	                margin: 0;
	                border: initial;
	                border-radius: initial;
	                width: initial;
	                min-height: initial;
	                box-shadow: initial;
	                background: initial;
	                page-break-after: always;
	            }
	        }

	        .print_content table, thead, th, table, tbody, td {
	        	padding: 3px;
	        }
	    </style>
	</head>

	<div class="page">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
            <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>
        </div>
        <div class="print_title">
            <table width="100%" border="0" style="size:auto">
                <tr>
                    <td><img src="<?= base_url('assets/AdminLTE-2.0.5/dist/img/compLog/NKES/compLog.png') ?>" alt="" width="181" height="44"></td>
                    <td>
                        <h3><?php echo $h1_title; ?></h3>
                    </td>
                </tr>
            </table>
        </div>

        <div class="print_body" style="padding-top: 10px; font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr style="display: none;">
                    <td width="100">Nama Laporan</td>
                    <td width="10">:</td>
                    <td><?php echo "$h1_title"; ?></td>
                </tr>
                <tr>
                    <td width="100">Kode Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJCODE"; ?></td>
                </tr>
                <tr>
                    <td width="100">Nama Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr style="display: none;">
                    <td width="100">Kategori</td>
                    <td width="10">:</td>
                    <td><?php echo $ITMGRP_NM; ?></td>
                </tr>
                <tr>
                    <td width="100">Tgl. Cetak</td>
                    <td width="10">:</td>
                    <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px;">
        	<table width="100%" border="1" rules="all" style="border-color: black;">
        		<thead>
        			<tr style="background:#CCCCCC">
						<th width="400" rowspan="3" style="text-align:center; font-weight:bold; border-bottom-color:#000">DESKRIPSI</th>
						<th rowspan="3"  style="text-align:center; font-weight:bold; border-bottom-color:#000">SAT.</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">BUDGET AWAL</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PERUBAHAN</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
						<th rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PROGRES<br>KUMULATIF</th>
						<th colspan="4"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REQUEST</th>
						<th colspan="4"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REALISASI</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">PREDISKSI 100%</th>
	              	</tr>
	                <tr style="background:#CCCCCC">
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SAAT INI</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REQUEST</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SAAT INI</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REALISASI</th>
	                </tr>
	                <tr style="background:#CCCCCC">
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000">VOL.</th>
						<!-- <th width="5%"  style="text-align:center; font-weight:bold; border-bottom-color:#000;">HARGA</th> -->
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">%</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">TOTAL NILAI</th>
						<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">DEVIASI</th>
	                </tr>
	              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
						<td style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
	               	</tr>
        		</thead>
        		<tbody>
        			<?php
						if($JOBPARENT == 'All')
						{
							$addQJOB_ID 	= "";
							$addQJOB_P 		= "";
						} 
						else 
						{
							$addQJOB_ID 	= "AND JOBCODEID = '$JOBPARENT'";
							$addQJOB_P 	= "AND JOBCODEID LIKE '$JOBPARENT%'";
						}

						$ckISLASTH 		= "SELECT ISLASTH FROM tbl_joblist 
											WHERE PRJCODE = '$PRJCODE' $addQJOB_ID AND ISLAST = 0";
						$resckISLASTH	= $this->db->query($ckISLASTH);
						if($resckISLASTH->num_rows() > 0)
						{
							foreach($resckISLASTH->result() as $rCK):
								$CK_ISLASTH 	= $rCK->ISLASTH;
								if($CK_ISLASTH == 0)
								{
									$getJOB_P 	= "SELECT JOBCODEID, JOBDESC, JOBUNIT, JOBVOLM, JOBLEV, ISLASTH, ISLAST FROM tbl_joblist 
													WHERE PRJCODE = '$PRJCODE' $addQJOB_P AND ISLAST = 0";
									$resJOB_P 	= $this->db->query($getJOB_P);
									if($resJOB_P->num_rows() > 0)
									{
										$GTITM_BUDG 		= 0;
										$GTADD_TOTAL 		= 0;
										$GTITM_BUDG2 		= 0;
										$GTREQ_AMOUNT 		= 0;
										$GTITM_USED_AM 		= 0;
										$GTREMREQ_AMOUNT 	= 0;
										$GTREMREALZ_AMOUNT 	= 0;
										foreach($resJOB_P->result() as $rJP):
											$JOBCODEID_P 	= $rJP->JOBCODEID;
											$JOBDESC_P 		= $rJP->JOBDESC;
											$JOBUNIT_P 		= $rJP->JOBUNIT;
											$JOBVOLM_P 		= $rJP->JOBVOLM;
											$JOBLEV_P 		= $rJP->JOBLEV;
											$ISLASTH_P 		= $rJP->ISLASTH;
											$ISLAST_P 		= $rJP->ISLAST;
			
											if($JOBUNIT_P == '')
												$JOBUNIT_P = 'LS';
			
											if($ISLASTH_P == 1)
											{
												$CELL_COL	= "style='white-space:nowrap'";
											}
											else
											{
												$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";	
											}
			
											// SPACE
												$spaceLev 		= "";
												if($JOBLEV_P == 1)
													$spaceLev 	= 0;
												elseif($JOBLEV_P == 2)
													$spaceLev 	= 15;
												elseif($JOBLEV_P == 3)
													$spaceLev 	= 30;
												elseif($JOBLEV_P == 4)
													$spaceLev 	= 45;
												elseif($JOBLEV_P == 5)
													$spaceLev 	= 60;
												elseif($JOBLEV_P == 6)
													$spaceLev 	= 75;
												elseif($JOBLEV_P == 7)
													$spaceLev 	= 90;
												elseif($JOBLEV_P == 8)
													$spaceLev 	= 105;
												elseif($JOBLEV_P == 9)
													$spaceLev 	= 120;
												elseif($JOBLEV_P == 10)
													$spaceLev 	= 135;
												elseif($JOBLEV_P == 11)
													$spaceLev 	= 150;
												elseif($JOBLEV_P == 12)
													$spaceLev 	= 165;
			
											$JobView1		= "$JOBCODEID_P - $JOBDESC_P";
											$JobView		= wordwrap($JobView1, 60, "<br>", true);
			
											// get TOTAL PEKERJAAN
												$getJOB_TOT 	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG,
																	SUM(ADD_VOLM - ADDM_VOLM) AS ADDVOLM, SUM(ADD_JOBCOST - ADDM_JOBCOST) AS ADD_TOTAL,
																	SUM(PO_VOLM + WO_QTY) AS REQ_VOLM, SUM(PO_AMOUNT + WO_AMOUNT) AS REQ_AMOUNT,
																	-- SUM(REQ_VOLM) AS REQ_VOLM, SUM(REQ_AMOUNT) AS REQ_AMOUNT,
																	SUM(ITM_USED) AS ITM_USED, SUM(ITM_USED_AM) AS ITM_USED_AM
																	FROM tbl_joblist_detail 
																	WHERE PRJCODE = '$PRJCODE' AND JOBPARENT LIKE '$JOBCODEID_P%' AND ISLAST = 1";
												$resJOB_TOT 	= $this->db->query($getJOB_TOT);
												
												$REMREQ_VOLM 		= 0;
												$REMREQ_AMOUNT 		= 0;
												$REMREALZ_VOLM 		= 0;
												$REMREALZ_AMOUNT 	= 0;
												foreach($resJOB_TOT->result() as $rJOBT):
												// Budget awal
													$ITM_VOLMBG 	= $JOBVOLM_P; // Volume budget awal diambil dari BOQ
													$ITM_BUDG 		= $rJOBT->ITM_BUDG;
													
													// Addendum
													$ADDVOLM 		= $rJOBT->ADDVOLM;
													$ADD_TOTAL 		= $rJOBT->ADD_TOTAL;
													
													// after addendum
													$ITM_VOLM2 		= $ITM_VOLMBG + $ADDVOLM;
													$ITM_BUDG2 		= $ITM_BUDG + $ADD_TOTAL;
													
													// REQUEST :
													$REQ_VOLM 		= $rJOBT->REQ_VOLM;
													$REQ_AMOUNT 	= $rJOBT->REQ_AMOUNT;
													
													// REALISASI
													$ITM_USED 		= $rJOBT->ITM_USED;
													$ITM_USED_AM 	= $rJOBT->ITM_USED_AM;
												endforeach;
												
												// SISA BUDG THD REQ
												$REMREQ_VOLM 	= $ITM_VOLM2 - $REQ_VOLM;
												$REMREQ_AMOUNT 	= $ITM_BUDG2 - $REQ_AMOUNT;
												
												// SISA BUDG THD REALISASI
												$REMREALZ_VOLM 	= $ITM_VOLM2 - $ITM_USED;
												$REMREALZ_AMOUNT= $ITM_BUDG2 - $ITM_USED_AM;
												
												if($ISLASTH_P == 1)
												{
													$GTITM_BUDG 		= $GTITM_BUDG + $ITM_BUDG;
													$GTADD_TOTAL 		= $GTADD_TOTAL + $ADD_TOTAL;
													$GTITM_BUDG2 		= $GTITM_BUDG2 + $ITM_BUDG2;
													$GTREQ_AMOUNT 		= $GTREQ_AMOUNT + $REQ_AMOUNT;
													$GTITM_USED_AM 		= $GTITM_USED_AM + $ITM_USED_AM;
													$GTREMREQ_AMOUNT	= $GTREMREQ_AMOUNT + $REMREQ_AMOUNT;
													$GTREMREALZ_AMOUNT 	= $GTREMREALZ_AMOUNT + $REMREALZ_AMOUNT;
												}
											?>
												<tr>
													<td style="text-align:left; border-bottom-color:#000;">
														<span <?=$CELL_COL?>><div style='margin-left: <?=$spaceLev?>px;'><?=$JobView?></div></span>
													</td>
													<td  style="text-align:center; border-bottom-color:#000;"><?php echo $JOBUNIT_P; ?></td>
												<!-- BUDGET AWAL --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($JOBVOLM_P, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ITM_BUDG, 2); ?></td>
			
												<!-- PERUBAHAN --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($ADDVOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($ITM_VOLM2, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format(0, 2); // HOLD ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREQ_VOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREQ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($ITM_USED, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ITM_USED_AM, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREALZ_VOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREALZ_AMOUNT, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php // echo number_format($PRED_VAL, 2); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;">
														<?php echo number_format(0, 2); // HOLD ?></td>
												</tr>
											<?php
										endforeach;
			
										// // get TOTAL PEKERJAAN
										// $getJOB_GTOT 	= "SELECT SUM(ITM_BUDG) AS GTITM_BUDG,
										// 					SUM(ADD_JOBCOST - ADDM_JOBCOST) AS GTADD_TOTAL,
										// 					SUM(REQ_AMOUNT) AS GTREQ_AMOUNT,
										// 					SUM(ITM_USED_AM) AS GTITM_USED_AM
										// 					FROM tbl_joblist_detail 
										// 					WHERE PRJCODE = '$PRJCODE' AND JOBPARENT LIKE '$JOBCODEID_P%' AND ISLAST = 1";
										// $resJOB_GTOT 	= $this->db->query($getJOB_GTOT);
										// foreach($resJOB_GTOT->result() as $rJOBGT):
										// 	// Budget awal
										// 		$GTITM_BUDG 	= $GTITM_BUDG + $ITM_BUDG;
			
										// 	// Addendum
										// 		$GTADD_TOTAL 	= $GTADD_TOTAL + $ADD_TOTAL;
			
										// 	// after addendum
										// 		$GTITM_BUDG2 	= $GTITM_BUDG2 + $ITM_BUDG2;
			
										// 	// REQUEST :
										// 		$GTREQ_AMOUNT 	= $GTREQ_AMOUNT + $REQ_AMOUNT;
			
										// 	// REALISASI
										// 		$GTITM_USED_AM 	= $GTITM_USED_AM + $ITM_USED_AM;
			
										// 	// SISA BUDG THD REQ
										// 		$GTREMREQ_AMOUNT= $GTREMREQ_AMOUNT + $REMREQ_AMOUNT;
			
										// 	// SISA BUDG THD REALISASI
										// 		$GTREMREALZ_AMOUNT = $GTREMREALZ_AMOUNT + $REMREALZ_AMOUNT;
										// endforeach;
										?>
											<tr style="border: 2px solid;">
												<td colspan="3" style="text-align: right; font-weight:bold;">TOTAL</td>
												<td style="text-align: right; font-weight:bold;"><?php echo number_format($GTITM_BUDG, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTADD_TOTAL, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTITM_BUDG2, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="3"><?php echo number_format($GTREQ_AMOUNT, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTREMREQ_AMOUNT, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTITM_USED_AM, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTREMREALZ_AMOUNT, 2); ?></td>
											</tr>
										<?php
									}
								}
								else
								{
									$getJOB_P 	= "SELECT JOBCODEID, JOBDESC, JOBUNIT, JOBVOLM, JOBLEV, ISLASTH, ISLAST FROM tbl_joblist 
													WHERE PRJCODE = '$PRJCODE' $addQJOB_P AND ISLAST = 0";
									$resJOB_P 	= $this->db->query($getJOB_P);
									if($resJOB_P->num_rows() > 0)
									{
										$GTITM_BUDG 		= 0;
										$GTADD_TOTAL 		= 0;
										$GTITM_BUDG2 		= 0;
										$GTREQ_AMOUNT 		= 0;
										$GTITM_USED_AM 		= 0;
										$GTREMREQ_AMOUNT 	= 0;
										$GTREMREALZ_AMOUNT 	= 0;
										foreach($resJOB_P->result() as $rJP):
											$JOBCODEID_P 	= $rJP->JOBCODEID;
											$JOBDESC_P 		= $rJP->JOBDESC;
											$JOBUNIT_P 		= $rJP->JOBUNIT;
											$JOBVOLM_P 		= $rJP->JOBVOLM;
											$JOBLEV_P 		= $rJP->JOBLEV;
											$ISLASTH_P 		= $rJP->ISLASTH;
											$ISLAST_P 		= $rJP->ISLAST;
			
											if($JOBUNIT_P == '')
												$JOBUNIT_P = 'LS';
			
											if($ISLASTH_P == 1)
											{
												$CELL_COL	= "style='white-space:nowrap'";
											}
											else
											{
												$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";	
											}
			
											// SPACE
												$spaceLev 		= "";
												if($JOBLEV_P == 1)
													$spaceLev 	= 0;
												elseif($JOBLEV_P == 2)
													$spaceLev 	= 15;
												elseif($JOBLEV_P == 3)
													$spaceLev 	= 30;
												elseif($JOBLEV_P == 4)
													$spaceLev 	= 45;
												elseif($JOBLEV_P == 5)
													$spaceLev 	= 60;
												elseif($JOBLEV_P == 6)
													$spaceLev 	= 75;
												elseif($JOBLEV_P == 7)
													$spaceLev 	= 90;
												elseif($JOBLEV_P == 8)
													$spaceLev 	= 105;
												elseif($JOBLEV_P == 9)
													$spaceLev 	= 120;
												elseif($JOBLEV_P == 10)
													$spaceLev 	= 135;
												elseif($JOBLEV_P == 11)
													$spaceLev 	= 150;
												elseif($JOBLEV_P == 12)
													$spaceLev 	= 165;
			
											$JobView1		= "$JOBCODEID_P - $JOBDESC_P";
											$JobView		= wordwrap($JobView1, 60, "<br>", true);
			
											// get TOTAL PEKERJAAN
												$getJOB_TOT 	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG,
																	SUM(ADD_VOLM - ADDM_VOLM) AS ADDVOLM, SUM(ADD_JOBCOST - ADDM_JOBCOST) AS ADD_TOTAL,
																	SUM(REQ_VOLM) AS REQ_VOLM, SUM(REQ_AMOUNT) AS REQ_AMOUNT,
																	SUM(ITM_USED) AS ITM_USED, SUM(ITM_USED_AM) AS ITM_USED_AM
																	FROM tbl_joblist_detail 
																	WHERE PRJCODE = '$PRJCODE' AND JOBPARENT LIKE '$JOBCODEID_P%' AND ISLAST = 1";
												$resJOB_TOT 	= $this->db->query($getJOB_TOT);
												
												$REMREQ_VOLM 		= 0;
												$REMREQ_AMOUNT 		= 0;
												$REMREALZ_VOLM 		= 0;
												$REMREALZ_AMOUNT 	= 0;
												foreach($resJOB_TOT->result() as $rJOBT):
												// Budget awal
													$ITM_VOLMBG 	= $JOBVOLM_P; // Volume budget awal diambil dari BOQ
													$ITM_BUDG 		= $rJOBT->ITM_BUDG;
													
													// Addendum
													$ADDVOLM 		= $rJOBT->ADDVOLM;
													$ADD_TOTAL 		= $rJOBT->ADD_TOTAL;
													
													// after addendum
													$ITM_VOLM2 		= $ITM_VOLMBG + $ADDVOLM;
													$ITM_BUDG2 		= $ITM_BUDG + $ADD_TOTAL;
													
													// REQUEST :
													$REQ_VOLM 		= $rJOBT->REQ_VOLM;
													$REQ_AMOUNT 	= $rJOBT->REQ_AMOUNT;
													
													// REALISASI
													$ITM_USED 		= $rJOBT->ITM_USED;
													$ITM_USED_AM 	= $rJOBT->ITM_USED_AM;
												endforeach;
												
												// SISA BUDG THD REQ
												$REMREQ_VOLM 	= $ITM_VOLM2 - $REQ_VOLM;
												$REMREQ_AMOUNT 	= $ITM_BUDG2 - $REQ_AMOUNT;
												
												// SISA BUDG THD REALISASI
												$REMREALZ_VOLM 	= $ITM_VOLM2 - $ITM_USED;
												$REMREALZ_AMOUNT= $ITM_BUDG2 - $ITM_USED_AM;
												
												if($ISLASTH_P == 1)
												{
													$GTITM_BUDG 		= $GTITM_BUDG + $ITM_BUDG;
													$GTADD_TOTAL 		= $GTADD_TOTAL + $ADD_TOTAL;
													$GTITM_BUDG2 		= $GTITM_BUDG2 + $ITM_BUDG2;
													$GTREQ_AMOUNT 		= $GTREQ_AMOUNT + $REQ_AMOUNT;
													$GTITM_USED_AM 		= $GTITM_USED_AM + $ITM_USED_AM;
													$GTREMREQ_AMOUNT	= $GTREMREQ_AMOUNT + $REMREQ_AMOUNT;
													$GTREMREALZ_AMOUNT 	= $GTREMREALZ_AMOUNT + $REMREALZ_AMOUNT;
												}
											?>
												<tr>
													<td style="text-align:left; border-bottom-color:#000;">
														<span <?=$CELL_COL?>><div style='margin-left: <?=$spaceLev?>px;'><?=$JobView?></div></span>
													</td>
													<td  style="text-align:center; border-bottom-color:#000;"><?php echo $JOBUNIT_P; ?></td>
												<!-- BUDGET AWAL --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($JOBVOLM_P, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ITM_BUDG, 2); ?></td>
			
												<!-- PERUBAHAN --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($ADDVOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($ITM_VOLM2, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format(0, 2); // HOLD ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREQ_VOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREQ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($ITM_USED, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($ITM_USED_AM, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREALZ_VOLM, 3); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREALZ_AMOUNT, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td  style="text-align:right; border-bottom-color:#000;"><?php // echo number_format($PRED_VAL, 2); ?></td>
													<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;">
														<?php echo number_format(0, 2); // HOLD ?></td>
												</tr>
											<?php
										endforeach;
			
										// // get TOTAL PEKERJAAN
										// $getJOB_GTOT 	= "SELECT SUM(ITM_BUDG) AS GTITM_BUDG,
										// 					SUM(ADD_JOBCOST - ADDM_JOBCOST) AS GTADD_TOTAL,
										// 					SUM(REQ_AMOUNT) AS GTREQ_AMOUNT,
										// 					SUM(ITM_USED_AM) AS GTITM_USED_AM
										// 					FROM tbl_joblist_detail 
										// 					WHERE PRJCODE = '$PRJCODE' AND JOBPARENT LIKE '$JOBCODEID_P%' AND ISLAST = 1";
										// $resJOB_GTOT 	= $this->db->query($getJOB_GTOT);
										// foreach($resJOB_GTOT->result() as $rJOBGT):
										// 	// Budget awal
										// 		$GTITM_BUDG 	= $GTITM_BUDG + $ITM_BUDG;
			
										// 	// Addendum
										// 		$GTADD_TOTAL 	= $GTADD_TOTAL + $ADD_TOTAL;
			
										// 	// after addendum
										// 		$GTITM_BUDG2 	= $GTITM_BUDG2 + $ITM_BUDG2;
			
										// 	// REQUEST :
										// 		$GTREQ_AMOUNT 	= $GTREQ_AMOUNT + $REQ_AMOUNT;
			
										// 	// REALISASI
										// 		$GTITM_USED_AM 	= $GTITM_USED_AM + $ITM_USED_AM;
			
										// 	// SISA BUDG THD REQ
										// 		$GTREMREQ_AMOUNT= $GTREMREQ_AMOUNT + $REMREQ_AMOUNT;
			
										// 	// SISA BUDG THD REALISASI
										// 		$GTREMREALZ_AMOUNT = $GTREMREALZ_AMOUNT + $REMREALZ_AMOUNT;
										// endforeach;
										?>
											<tr style="border: 2px solid;">
												<td colspan="3" style="text-align: right; font-weight:bold;">TOTAL</td>
												<td style="text-align: right; font-weight:bold;"><?php echo number_format($GTITM_BUDG, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTADD_TOTAL, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTITM_BUDG2, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="3"><?php echo number_format($GTREQ_AMOUNT, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTREMREQ_AMOUNT, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTITM_USED_AM, 2); ?></td>
												<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTREMREALZ_AMOUNT, 2); ?></td>
											</tr>
										<?php
									}
								}
							endforeach;
						}

        			?>
        		</tbody>
        	</table>
        </div>
        <?php
			// hold ---------------------
			$qc_ITMGO  	= "tbl_joblist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBPARENT' AND ISLASTH = 1";
			$countITMGO = $this->db->count_all($qc_ITMGO);
			if($countITMGO > 0)   
			{
				if($ITM_GROUP == 'All')
				{
					$ADDQUERY	= "";
					$sql		= "SELECT IG_Num FROM  tbl_itemgroup";
					$viewGrp	= $this->db->query($sql)->result();
					foreach($viewGrp as $row) :
				      	$ITM_GROUP	= $row->IG_Num;
				      	$ADDQUERY	= "AND A.ITM_GROUP = '$ITM_GROUP'";

				      	// get JOB_DET from ISLASTH = 1
	               		$getJOB1_H 	= "SELECT A.JOBCODEID FROM tbl_joblist A
										WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBPARENT' AND ISLASTH = 1";
	               		$resJOB1_H	= $this->db->query($getJOB1_H);
	               		if($resJOB1_H->num_rows() > 0)
	               		{
	               			$arrJOBCODEID1_H = [];
	               			foreach($resJOB1_H->result() as $rJOB1_H):
	               				$JOBCODEID1_H[] = $rJOB1_H->JOBCODEID;
	               			endforeach;

	               			$arrJOBCODEID1_H = implode("','", $JOBCODEID1_H);
	               		}

	               		$rw_JOBH 	= "tbl_joblist_detail A
       									INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE AND B.ITM_GROUP = A.ITM_GROUP
       									WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT IN ('$arrJOBCODEID1_H') $ADDQUERY";
       					$rw01_JOBH 	= $this->db->count_all($rw_JOBH);
       					if($rw01_JOBH > 0)
       					{
       						?>
								<div class="print_content" style="padding-top: 5px; font-size: 12px; display:none;">
						        	<table width="100%" border="1" rules="all" style="border-color: black;">
						        		<thead>
							                <tr style="background:#CCCCCC">
												<th rowspan="3"  style="text-align:center; font-weight:bold; border-top-color:#000; border-bottom-color:#000; border-left-color:#000">NO</th>
												<th width="300" rowspan="3" style="text-align:center; font-weight:bold; border-bottom-color:#000">DESKRIPSI</th>
												<th rowspan="3"  style="text-align:center; font-weight:bold; border-bottom-color:#000">SAT.</th>
												<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">BUDGET AWAL</th>
												<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PERUBAHAN</th>
												<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
												<th rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PROGRES<br>KUMULATIF</th>
												<th colspan="4"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REQUEST</th>
												<th colspan="4"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REALISASI</th>
												<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">PREDISKSI 100%</th>
							              	</tr>
							                <tr style="background:#CCCCCC">
												<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SAAT INI</th>
												<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REQUEST</th>
												<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SAAT INI</th>
												<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REALISASI</th>
							                </tr>
							                <tr style="background:#CCCCCC">
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000">VOL.</th>
												<!-- <th width="5%"  style="text-align:center; font-weight:bold; border-bottom-color:#000;">HARGA</th> -->
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">%</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">TOTAL NILAI</th>
												<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">DEVIASI</th>
							                </tr>
							              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
												<td  style="text-align:center; border-right: none; border-bottom-color:#000;">&nbsp;</td>
												<td style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
												<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							               	</tr>
							            </thead>
							            <tbody>
						               	<?php
						               		// get JOB_DET from ISLASTH = 1
						               		$getJOB_H 	= "SELECT A.JOBCODEID, A.JOBDESC, A.JOBUNIT FROM tbl_joblist A
															WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBPARENT' AND ISLASTH = 1";
						               		$resJOB_H	= $this->db->query($getJOB_H);
						               		if($resJOB_H->num_rows() > 0)
						               		{
						               			$no = 0;
						               			$GTOT_ITM2VOL 		= 0;
						               			$GTOT_ITM2_AM 		= 0;
						               			$GTOT_ADDVOLM 		= 0;
						               			$GTOT_ADDAM  		= 0;
						               			$GITM_VOLM2 		= 0;
						               			$GITM_BUDG2 		= 0;
						               			$GTOT_REQVOLM 		= 0;
						               			$GTOT_REQAM 		= 0;
						               			$GTOT_REALZVOLM 	= 0;
						               			$GTOT_REALZAM 		= 0;
						               			$GTOTREM_REQVOLM	= 0;
						               			$GTOTREM_REQAM 		= 0;
						               			$GTOT_REALZVOLM 	= 0;
							               		$GTOT_REALZAM 		= 0;
						               			$GTOTREM_REALZVOLM	= 0;
						               			$GTOTREM_REALZAM 	= 0;
						               			foreach($resJOB_H->result() as $rJOB_H):
						               				$no 			= $no + 1;
						               				$JOBCODEID_H 	= $rJOB_H->JOBCODEID;
						               				$JOBDESC_H 		= $rJOB_H->JOBDESC;
						               				$ITM_UNIT_H 	= $rJOB_H->JOBUNIT;

						               				// get JOBCODEID & ITM_CODE from ITM_GROUP = 'O'
						               				$getJOB_ITMGO 	= "SELECT SUM(A.ITM_VOLM) AS TITM_VOLM, SUM(A.ITM_BUDG) AS TITM_BUDG,
						               									SUM(A.ADD_VOLM) AS TADD_VOLM, SUM(A.ADD_JOBCOST) AS TADD_JOBCOST,
						               									SUM(A.ADDM_VOLM) AS TADDM_VOLM, SUM(A.ADDM_JOBCOST) AS TADDM_JOBCOST,
						               									SUM(A.PO_VOLM) AS TPO_VOLM, SUM(A.PO_AMOUNT) AS TPO_AMOUNT,
						               									SUM(A.WO_QTY) AS TWO_VOLM, SUM(A.WO_AMOUNT) AS TWO_AMOUNT,
						               									SUM(A.ITM_USED) AS TVOC_VOLM, SUM(A.ITM_USED_AM) AS TVOC_AMOUNT,
						               									SUM(A.OPN_QTY) AS TOPN_VOLM, SUM(A.OPN_AMOUNT) AS TOPN_AMOUNT,
						               									SUM(A.ITM_RET) AS TRET_VOLM, SUM(A.ITM_RET_AM) AS TRET_AMOUNT,
						               									SUM(A.IR_VOLM) AS TIR_VOLM, SUM(A.IR_AMOUNT) AS TIR_AMOUNT
						               									FROM tbl_joblist_detail A
						               									INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE AND B.ITM_GROUP = A.ITM_GROUP
						               									WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODEID_H' $ADDQUERY 
						               									GROUP BY A.ITM_GROUP ORDER BY ORD_ID DESC";
						               				$resJOB_ITMGO 	= $this->db->query($getJOB_ITMGO);
						               				if($resJOB_ITMGO->num_rows() > 0)
						               				{
						               					$TREQ_VOLM = 0;
						               					foreach($resJOB_ITMGO->result() as $rJITMGO):
						               						// BUDGET AWAL
						               							$TITM_VOLM 	= $rJITMGO->TITM_VOLM;
						               							$TITM_BUDG 	= $rJITMGO->TITM_BUDG;
						               							if($TITM_VOLM == 0)
						               								$TITM_PRICE = 0;
						               							else
						               								$TITM_PRICE = $TITM_BUDG / $TITM_VOLM;

						               						// ADDENDUM
						               							$TADD_VOLM 		= $rJITMGO->TADD_VOLM;
						               							$TADD_JOBCOST 	= $rJITMGO->TADD_JOBCOST;
						               							$TADDM_VOLM 	= $rJITMGO->TADDM_VOLM;
						               							$TADDM_JOBCOST 	= $rJITMGO->TADDM_JOBCOST;

						               						// KOMULATIF PROGRESS
						               							$KUM_PROG	= 0;

						               						// AFTER ADDENDUM
						               							$TITM_VOLM2 	= $TITM_VOLM + $TADD_VOLM - $TADDM_VOLM;
						               							$TITM_BUDG2 	= $TITM_BUDG + $TADD_JOBCOST - $TADDM_JOBCOST;

						               						// REQUEST => PO, SPK, VOUCHER(VC, VLK/CPRJ, PD)
							               						$TPO_VOLM 		= $rJITMGO->TPO_VOLM;
							               						$TPO_AMOUNT 	= $rJITMGO->TPO_AMOUNT;
							               						$TWO_VOLM 		= $rJITMGO->TWO_VOLM;
							               						$TWO_AMOUNT 	= $rJITMGO->TWO_AMOUNT;
							               						$TVOC_VOLM 		= $rJITMGO->TVOC_VOLM;
							               						$TVOC_AMOUNT 	= $rJITMGO->TVOC_AMOUNT;

						               						// REQUEST
							               						$TREQ_VOLM 		= $TPO_VOLM + $TWO_VOLM + $TVOC_VOLM;
							               						$TREQ_AMOUNT 	= $TPO_AMOUNT + $TWO_AMOUNT + $TVOC_AMOUNT;
							               						if($TREQ_VOLM == 0)
							               							$TREQ_PRICE = 0;
							               						else
							               							$TREQ_PRICE = $TREQ_AMOUNT / $TREQ_VOLM;

							               					// SISA BUDGET THD REQUEST
							               						$REMREQ_VOLM 	= $TITM_VOLM2 - $TREQ_VOLM;
							               						$REMREQ_AMOUNT 	= $TITM_BUDG2 - $TREQ_AMOUNT;
							               						if($REMREQ_VOLM == 0)
							               							$REMREQ_PRICE = 0;
							               						else
							               							$REMREQ_PRICE = $REMREQ_AMOUNT / $REMREQ_VOLM;

							               					// REALISASI => OPN, OPN-RET, IR, Voucher (VC, VLK/CPRJ, PD)
							               						$TOPN_VOLM 		= $rJITMGO->TOPN_VOLM;
							               						$TOPN_AMOUNT 	= $rJITMGO->TOPN_AMOUNT;
							               						$TRET_VOLM 		= $rJITMGO->TRET_VOLM;
							               						$TRET_AMOUNT 	= $rJITMGO->TRET_AMOUNT;
							               						$TIR_VOLM 		= $rJITMGO->TIR_VOLM;
							               						$TIR_AMOUNT 	= $rJITMGO->TIR_AMOUNT;

							               						$REALZ_VOLM 	= $TOPN_VOLM + $TRET_VOLM + $TIR_VOLM + $TVOC_VOLM;
							               						$REALZ_AMOUNT 	= $TOPN_AMOUNT + $TRET_AMOUNT + $TIR_AMOUNT + $TVOC_AMOUNT;
							               						if($REALZ_VOLM == 0)
							               							$REALZ_PRICE = 0;
							               						else
							               							$REALZ_PRICE = $REALZ_AMOUNT / $REALZ_VOLM;


							               					// SISA BUDGET THD REALISASI
							               						$REMREALZ_VOLM 		= $TITM_VOLM2 - $REALZ_VOLM;
							               						$REMREALZ_AMOUNT	= $TITM_BUDG2 - $REALZ_AMOUNT;
							               						if($REMREALZ_VOLM == 0)
							               							$REMREALZ_PRICE = 0;
							               						else
							               							$REMREALZ_PRICE = $REMREALZ_AMOUNT / $REMREALZ_VOLM;

							               					// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
																// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
							               						// $ITM_LASTP 	= $rJITMGO->ITM_LASTP;
																// $PRED_VAL	= $REALZ_AMOUNT + ($REMREALZ_VOLM * $ITM_LASTP);
																// $PRED_DEV	= round($PRED_VAL,2) - round($TITM_BUDG2,2);

							               					// TOTAL BUDGET AWAL
							               						$GTOT_ITM2VOL 	= $GTOT_ITM2VOL + $TITM_VOLM2;
							               						$GTOT_ITM2_AM 	= $GTOT_ITM2_AM + $TITM_BUDG2;

							               					// TOTAL ADDENDUM
							               						$GTOT_ADDVOLM 	= $GTOT_ADDVOLM + $TADD_VOLM;
							               						$GTOT_ADDAM 	= $GTOT_ADDAM + $TADD_JOBCOST;

							               					// TOTAL AFTER ADDENDUM
							               						$GITM_VOLM2 	= $GITM_VOLM2 + $TITM_VOLM2;
							               						$GITM_BUDG2 	= $GITM_BUDG2 + $TITM_BUDG2;

							               					// TOTAL BUDGET THD REQUEST
							               						$GTOT_REQVOLM 	= $GTOT_REQVOLM + $TREQ_VOLM;
							               						$GTOT_REQAM 	= $GTOT_REQAM + $TREQ_AMOUNT;

							               					// TOTAL SISA BUDGET THD REQUEST
							               						$GTOTREM_REQVOLM	= $GTOTREM_REQVOLM + $REMREQ_VOLM;
						               							$GTOTREM_REQAM 		= $GTOTREM_REQAM + $REMREQ_AMOUNT;

						               						// TOTAL BUDGET THD REALISASI
							               						$GTOT_REALZVOLM = $GTOT_REALZVOLM + $REALZ_VOLM;
							               						$GTOT_REALZAM 	= $GTOT_REALZAM + $REALZ_AMOUNT;

						               						// TOTAL SISA BUDGET THD REALISASI
							               						$GTOTREM_REALZVOLM	= $GTOTREM_REALZVOLM + $REMREALZ_VOLM;
						               							$GTOTREM_REALZAM 	= $GTOTREM_REALZAM + $REMREALZ_AMOUNT;

						               						?>
						               							<tr>
									                                <td  style="text-align:center;border-left-color:#000; border-right-color:#000; border-bottom-color:#000;"><?php echo $no; ?></td>
									                                <td style="text-align:left; border-bottom-color:#000;"><?php echo "$JOBCODEID_H - $JOBDESC_H"; ?></td>
									                                <td  style="text-align:center; border-bottom-color:#000;"><?php echo $ITM_UNIT_H; ?></td>
								                                <!-- BUDGET AWAL --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TITM_VOLM, 3); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TITM_BUDG, 2); ?></td>

								                                <!-- PERUBAHAN --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TADD_VOLM, 3); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TADD_JOBCOST, 2); ?></td>
									                                
								                                <!-- SETELAH PERUBAHAN --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TITM_VOLM2, 3); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TITM_BUDG2, 2); ?></td>
									                                
								                                <!-- PROGRES KUMULATIF --->
								                                	<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($KUM_PROG, 2); ?></td>
									                                
								                                <!-- REQUEST : SAAT INI --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TREQ_VOLM, 3); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TREQ_AMOUNT, 2); ?></td>
									                                
								                                <!-- REQUEST : SISA BUDGET THD REQUEST --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREQ_VOLM, 3); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREQ_AMOUNT, 2); ?></td>
									                                
								                                <!-- REALISASI : SAAT INI --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
									                                
								                                <!-- REALISASI : SISA BUDGET THD REALISASI --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREALZ_VOLM, 3); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREALZ_AMOUNT, 2); ?></td>
									                                
								                                <!-- PREDISKSI 100% --->
									                                <td  style="text-align:right; border-bottom-color:#000;"><?php // echo number_format($PRED_VAL, 2); ?></td>
									                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;">
									                                	<?php // echo number_format($PRED_DEV, 2); ?></td>
									                            </tr>
						               						<?php
						               					endforeach;
						               				}
						               			endforeach;
						               			?>
						       						<tr style="background:#CCCCCC; font-weight: bold;">
						                                <td  colspan="3" style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-right-color:#000; border-top-color:#000;">
						                                	TOTAL ITEM 
						                                	<?php 
						                                		if($ITM_GROUP == 'M') echo "MATERIAL";
						                                		elseif($ITM_GROUP == 'U') echo "UPAH";
						                                		elseif($ITM_GROUP == 'S') echo "SUB-KONT";
						                                		elseif($ITM_GROUP == 'T') echo "ALAT";
						                                		elseif($ITM_GROUP == 'I') echo "LAIN-LAIN";
						                                		elseif($ITM_GROUP == 'R') echo "OVERHEAD LAPANGAN";
						                                		elseif($ITM_GROUP == 'O') echo "OVERHEAD";
						                                	?>
						                                </td>
						                                <!-- BUDGET AWAL --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ITM2VOL, 3); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ITM2_AM, 2); ?></td>

						                                <!-- PERUBAHAN --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ADDVOLM, 3); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ADDAM, 2); ?></td>
							                                
						                                <!-- SETELAH PERUBAHAN --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GITM_VOLM2, 3); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GITM_BUDG2, 2); ?></td>
							                                
						                                <!-- PROGRES KUMULATIF --->
						                                	<td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-right-color:#000; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
							                                
						                                <!-- REQUEST : SAAT INI --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REQVOLM, 3); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REQAM, 2); ?></td>
							                                
						                                <!-- REQUEST : SISA BUDGET THD REQUEST --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REQVOLM, 3); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REQAM, 2); ?></td>
							                                
						                                <!-- REALISASI : SAAT INI --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REALZVOLM, 3); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REALZAM, 2); ?></td>
							                                
						                                <!-- REALISASI : SISA BUDGET THD REALISASI --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REALZVOLM, 3); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REALZAM, 2); ?></td>
							                                
						                                <!-- PREDISKSI 100% --->
							                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
							                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;">
							                                	<?php //echo number_format($PRED_DEV, 2); ?></td>
						                            </tr>
						       					<?php
						               		}
										?>
										</tbody>
						            </table>
						        </div>
							<?php
       					}
				    endforeach;
				}
				else
				{
					$ADDQUERY	= "AND A.ITM_GROUP = '$ITM_GROUP'";
					?>
						<div class="print_content" style="padding-top: 5px; font-size: 12px;">
				        	<table width="100%" border="1" rules="all" style="border-color: black;">
				        		<thead>
					                <tr style="background:#CCCCCC">
										<th rowspan="3"  style="text-align:center; font-weight:bold; border-top-color:#000; border-bottom-color:#000; border-left-color:#000">NO</th>
										<th width="300" rowspan="3" style="text-align:center; font-weight:bold; border-bottom-color:#000">DESKRIPSI</th>
										<th rowspan="3"  style="text-align:center; font-weight:bold; border-bottom-color:#000">SAT.</th>
										<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">BUDGET AWAL</th>
										<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PERUBAHAN</th>
										<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
										<th rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PROGRES<br>KUMULATIF</th>
										<th colspan="4"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REQUEST</th>
										<th colspan="4"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REALISASI</th>
										<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">PREDISKSI 100%</th>
					              	</tr>
					                <tr style="background:#CCCCCC">
										<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SAAT INI</th>
										<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REQUEST</th>
										<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SAAT INI</th>
										<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REALISASI</th>
					                </tr>
					                <tr style="background:#CCCCCC">
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000">VOL.</th>
										<!-- <th width="5%"  style="text-align:center; font-weight:bold; border-bottom-color:#000;">HARGA</th> -->
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">%</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000;">TOTAL NILAI</th>
										<th  style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">DEVIASI</th>
					                </tr>
					              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
										<td  style="text-align:center; border-right: none; border-bottom-color:#000;">&nbsp;</td>
										<td style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
										<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
					               	</tr>
					            </thead>
					            <tbody>
				               	<?php
				               		// get JOB_DET from ISLASTH = 1
				               		$getJOB_H 	= "SELECT A.JOBCODEID, A.JOBDESC, A.JOBUNIT FROM tbl_joblist A
													WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBPARENT' AND ISLASTH = 1";
				               		$resJOB_H	= $this->db->query($getJOB_H);
				               		if($resJOB_H->num_rows() > 0)
				               		{
				               			$no = 0;
				               			$GTOT_ITM2VOL 		= 0;
				               			$GTOT_ITM2_AM 		= 0;
				               			$GTOT_ADDVOLM 		= 0;
				               			$GTOT_ADDAM  		= 0;
				               			$GITM_VOLM2 		= 0;
				               			$GITM_BUDG2 		= 0;
				               			$GTOT_REQVOLM 		= 0;
				               			$GTOT_REQAM 		= 0;
				               			$GTOT_REALZVOLM 	= 0;
				               			$GTOT_REALZAM 		= 0;
				               			$GTOTREM_REQVOLM	= 0;
				               			$GTOTREM_REQAM 		= 0;
				               			$GTOT_REALZVOLM 	= 0;
					               		$GTOT_REALZAM 		= 0;
				               			$GTOTREM_REALZVOLM	= 0;
				               			$GTOTREM_REALZAM 	= 0;
				               			foreach($resJOB_H->result() as $rJOB_H):
				               				$no 			= $no + 1;
				               				$JOBCODEID_H 	= $rJOB_H->JOBCODEID;
				               				$JOBDESC_H 		= $rJOB_H->JOBDESC;
				               				$ITM_UNIT_H 	= $rJOB_H->JOBUNIT;

				               				// get JOBCODEID & ITM_CODE from ITM_GROUP = 'O'
				               				$getJOB_ITMGO 	= "SELECT SUM(A.ITM_VOLM) AS TITM_VOLM, SUM(A.ITM_BUDG) AS TITM_BUDG,
				               									SUM(A.ADD_VOLM) AS TADD_VOLM, SUM(A.ADD_JOBCOST) AS TADD_JOBCOST,
				               									SUM(A.ADDM_VOLM) AS TADDM_VOLM, SUM(A.ADDM_JOBCOST) AS TADDM_JOBCOST,
				               									SUM(A.PO_VOLM) AS TPO_VOLM, SUM(A.PO_AMOUNT) AS TPO_AMOUNT,
				               									SUM(A.WO_QTY) AS TWO_VOLM, SUM(A.WO_AMOUNT) AS TWO_AMOUNT,
				               									SUM(A.ITM_USED) AS TVOC_VOLM, SUM(A.ITM_USED_AM) AS TVOC_AMOUNT,
				               									SUM(A.OPN_QTY) AS TOPN_VOLM, SUM(A.OPN_AMOUNT) AS TOPN_AMOUNT,
				               									SUM(A.ITM_RET) AS TRET_VOLM, SUM(A.ITM_RET_AM) AS TRET_AMOUNT,
				               									SUM(A.IR_VOLM) AS TIR_VOLM, SUM(A.IR_AMOUNT) AS TIR_AMOUNT
				               									-- MAX(A.ITM_LASTP) AS ITM_LASTP
				               									FROM tbl_joblist_detail A
				               									INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE AND B.ITM_GROUP = A.ITM_GROUP
				               									WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODEID_H' $ADDQUERY 
				               									GROUP BY A.ITM_GROUP ORDER BY ORD_ID ASC";
				               				$resJOB_ITMGO 	= $this->db->query($getJOB_ITMGO);
				               				if($resJOB_ITMGO->num_rows() > 0)
				               				{
				               					$TREQ_VOLM = 0;
				               					foreach($resJOB_ITMGO->result() as $rJITMGO):
				               						// BUDGET AWAL
				               							$TITM_VOLM 	= $rJITMGO->TITM_VOLM;
				               							$TITM_BUDG 	= $rJITMGO->TITM_BUDG;
				               							if($TITM_VOLM == 0)
				               								$TITM_PRICE = 0;
				               							else
				               								$TITM_PRICE = $TITM_BUDG / $TITM_VOLM;

				               						// ADDENDUM
				               							$TADD_VOLM 		= $rJITMGO->TADD_VOLM;
				               							$TADD_JOBCOST 	= $rJITMGO->TADD_JOBCOST;
				               							$TADDM_VOLM 	= $rJITMGO->TADDM_VOLM;
				               							$TADDM_JOBCOST 	= $rJITMGO->TADDM_JOBCOST;

				               						// AFTER ADDENDUM
				               							$TITM_VOLM2 	= $TITM_VOLM + $TADD_VOLM - $TADDM_VOLM;
				               							$TITM_BUDG2 	= $TITM_BUDG + $TADD_JOBCOST - $TADDM_JOBCOST;

				               						// KOMULATIF PROGRESS
				               							$KUM_PROG	= 0;

				               						// REQUEST => PO, SPK, VOUCHER(VC, VLK/CPRJ, PD)
					               						$TPO_VOLM 		= $rJITMGO->TPO_VOLM;
					               						$TPO_AMOUNT 	= $rJITMGO->TPO_AMOUNT;
					               						$TWO_VOLM 		= $rJITMGO->TWO_VOLM;
					               						$TWO_AMOUNT 	= $rJITMGO->TWO_AMOUNT;
					               						$TVOC_VOLM 		= $rJITMGO->TVOC_VOLM;
					               						$TVOC_AMOUNT 	= $rJITMGO->TVOC_AMOUNT;

				               						// REQUEST
					               						$TREQ_VOLM 		= $TPO_VOLM + $TWO_VOLM + $TVOC_VOLM;
					               						$TREQ_AMOUNT 	= $TPO_AMOUNT + $TWO_AMOUNT + $TVOC_AMOUNT;
					               						if($TREQ_VOLM == 0)
					               							$TREQ_PRICE = 0;
					               						else
					               							$TREQ_PRICE = $TREQ_AMOUNT / $TREQ_VOLM;

					               					// SISA BUDGET THD REQUEST
					               						$REMREQ_VOLM 	= $TITM_VOLM2 - $TREQ_VOLM;
					               						$REMREQ_AMOUNT 	= $TITM_BUDG2 - $TREQ_AMOUNT;
					               						if($REMREQ_VOLM == 0)
					               							$REMREQ_PRICE = 0;
					               						else
					               							$REMREQ_PRICE = $REMREQ_AMOUNT / $REMREQ_VOLM;

					               					// REALISASI => OPN, OPN-RET, IR, Voucher (VC, VLK/CPRJ, PD)
					               						$TOPN_VOLM 		= $rJITMGO->TOPN_VOLM;
					               						$TOPN_AMOUNT 	= $rJITMGO->TOPN_AMOUNT;
					               						$TRET_VOLM 		= $rJITMGO->TRET_VOLM;
					               						$TRET_AMOUNT 	= $rJITMGO->TRET_AMOUNT;
					               						$TIR_VOLM 		= $rJITMGO->TIR_VOLM;
					               						$TIR_AMOUNT 	= $rJITMGO->TIR_AMOUNT;

					               						$REALZ_VOLM 	= $TOPN_VOLM + $TRET_VOLM + $TIR_VOLM + $TVOC_VOLM;
					               						$REALZ_AMOUNT 	= $TOPN_AMOUNT + $TRET_AMOUNT + $TIR_AMOUNT + $TVOC_AMOUNT;
					               						if($REALZ_VOLM == 0)
					               							$REALZ_PRICE = 0;
					               						else
					               							$REALZ_PRICE = $REALZ_AMOUNT / $REALZ_VOLM;


					               					// SISA BUDGET THD REALISASI
					               						$REMREALZ_VOLM 		= $TITM_VOLM2 - $REALZ_VOLM;
					               						$REMREALZ_AMOUNT	= $TITM_BUDG2 - $REALZ_AMOUNT;
					               						if($REMREALZ_VOLM == 0)
					               							$REMREALZ_PRICE = 0;
					               						else
					               							$REMREALZ_PRICE = $REMREALZ_AMOUNT / $REMREALZ_VOLM;

					               					// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
														// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
					               						// $ITM_LASTP 	= $rJITMGO->ITM_LASTP;
														// $PRED_VAL	= $REALZ_AMOUNT + ($REMREALZ_VOLM * $ITM_LASTP);
														// $PRED_DEV	= round($PRED_VAL,2) - round($TITM_BUDG2,2);

					               					// TOTAL BUDGET AWAL
					               						$GTOT_ITM2VOL 	= $GTOT_ITM2VOL + $TITM_VOLM2;
					               						$GTOT_ITM2_AM 	= $GTOT_ITM2_AM + $TITM_BUDG2;

					               					// TOTAL ADDENDUM
					               						$GTOT_ADDVOLM 	= $GTOT_ADDVOLM + $TADD_VOLM;
					               						$GTOT_ADDAM 	= $GTOT_ADDAM + $TADD_JOBCOST;

					               					// TOTAL AFTER ADDENDUM
					               						$GITM_VOLM2 	= $GITM_VOLM2 + $TITM_VOLM2;
					               						$GITM_BUDG2 	= $GITM_BUDG2 + $TITM_BUDG2;

					               					// TOTAL BUDGET THD REQUEST
					               						$GTOT_REQVOLM 	= $GTOT_REQVOLM + $TREQ_VOLM;
					               						$GTOT_REQAM 	= $GTOT_REQAM + $TREQ_AMOUNT;

					               					// TOTAL SISA BUDGET THD REQUEST
					               						$GTOTREM_REQVOLM	= $GTOTREM_REQVOLM + $REMREQ_VOLM;
				               							$GTOTREM_REQAM 		= $GTOTREM_REQAM + $REMREQ_AMOUNT;

				               						// TOTAL BUDGET THD REALISASI
					               						$GTOT_REALZVOLM = $GTOT_REALZVOLM + $REALZ_VOLM;
					               						$GTOT_REALZAM 	= $GTOT_REALZAM + $REALZ_AMOUNT;

				               						// TOTAL SISA BUDGET THD REALISASI
					               						$GTOTREM_REALZVOLM	= $GTOTREM_REALZVOLM + $REMREALZ_VOLM;
				               							$GTOTREM_REALZAM 	= $GTOTREM_REALZAM + $REMREALZ_AMOUNT;

				               						?>
				               							<tr>
							                                <td  style="text-align:center;border-left-color:#000; border-right-color:#000; border-bottom-color:#000;"><?php echo $no; ?></td>
							                                <td style="text-align:left; border-bottom-color:#000;"><?php echo "$JOBCODEID_H - $JOBDESC_H"; ?></td>
							                                <td  style="text-align:center; border-bottom-color:#000;"><?php echo $ITM_UNIT_H; ?></td>
						                                <!-- BUDGET AWAL --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TITM_VOLM, 3); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TITM_BUDG, 2); ?></td>

						                                <!-- PERUBAHAN --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TADD_VOLM, 3); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TADD_JOBCOST, 2); ?></td>
							                                
						                                <!-- SETELAH PERUBAHAN --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TITM_VOLM2, 3); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TITM_BUDG2, 2); ?></td>
							                                
						                                <!-- PROGRES KUMULATIF --->
						                                	<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($KUM_PROG, 2); ?></td>
							                                
						                                <!-- REQUEST : SAAT INI --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($TREQ_VOLM, 3); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($TREQ_AMOUNT, 2); ?></td>
							                                
						                                <!-- REQUEST : SISA BUDGET THD REQUEST --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREQ_VOLM, 3); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREQ_AMOUNT, 2); ?></td>
							                                
						                                <!-- REALISASI : SAAT INI --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
							                                
						                                <!-- REALISASI : SISA BUDGET THD REALISASI --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php echo number_format($REMREALZ_VOLM, 3); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;"><?php echo number_format($REMREALZ_AMOUNT, 2); ?></td>
							                                
						                                <!-- PREDISKSI 100% --->
							                                <td  style="text-align:right; border-bottom-color:#000;"><?php // echo number_format($PRED_VAL, 2); ?></td>
							                                <td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;">
							                                	<?php // echo number_format($PRED_DEV, 2); ?></td>
							                            </tr>
				               						<?php
				               					endforeach;
				               				}
				               			endforeach;
				               			?>
				       						<tr style="background:#CCCCCC; font-weight: bold;">
				                                <td  colspan="3" style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-right-color:#000; border-top-color:#000;">
				                                	TOTAL ITEM 
				                                	<?php 
				                                		if($ITM_GROUP == 'M') echo "MATERIAL";
				                                		elseif($ITM_GROUP == 'U') echo "UPAH";
				                                		elseif($ITM_GROUP == 'S') echo "SUB-KONT";
				                                		elseif($ITM_GROUP == 'T') echo "ALAT";
				                                		elseif($ITM_GROUP == 'I') echo "LAIN-LAIN";
				                                		elseif($ITM_GROUP == 'R') echo "OVERHEAD LAPANGAN";
				                                		elseif($ITM_GROUP == 'O') echo "OVERHEAD";
				                                	?>
				                                </td>
				                                <!-- BUDGET AWAL --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ITM2VOL, 3); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ITM2_AM, 2); ?></td>

				                                <!-- PERUBAHAN --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ADDVOLM, 3); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_ADDAM, 2); ?></td>
					                                
				                                <!-- SETELAH PERUBAHAN --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GITM_VOLM2, 3); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GITM_BUDG2, 2); ?></td>
					                                
				                                <!-- PROGRES KUMULATIF --->
				                                	<td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-right-color:#000; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
					                                
				                                <!-- REQUEST : SAAT INI --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REQVOLM, 3); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REQAM, 2); ?></td>
					                                
				                                <!-- REQUEST : SISA BUDGET THD REQUEST --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REQVOLM, 3); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REQAM, 2); ?></td>
					                                
				                                <!-- REALISASI : SAAT INI --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REALZVOLM, 3); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOT_REALZAM, 2); ?></td>
					                                
				                                <!-- REALISASI : SISA BUDGET THD REALISASI --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REALZVOLM, 3); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;"><?php echo number_format($GTOTREM_REALZAM, 2); ?></td>
					                                
				                                <!-- PREDISKSI 100% --->
					                                <td  style="text-align:right;border-bottom-color:#000; border-left-color:#000; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
					                                <td  style="text-align:right;border-bottom-color:#000; border-right-color:#000; border-top-color:#000;">
					                                	<?php //echo number_format($PRED_DEV, 2); ?></td>
				                            </tr>
				       					<?php
				               		}
								?>
								</tbody>
				            </table>
				        </div>
					<?php
				}
			}
        ?>
	</body>
</html>