<?php
	/* 
		* Author		   = Dian Hermanto
		* Create Date	= 20 April 2018
		* File Name	 = v_sdbp_report_item.php
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
	            margin: 0;
	            padding: 0;
	            background-color: #FAFAFA;
	            font: 12pt Arial, Helvetica, sans-serif;
	        }

	        * {
	            box-sizing: border-box;
	            -moz-box-sizing: border-box;
	        }

	        .page {
	            width: 50cm;
	            min-height: 21cm;
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
	            /*size: auto;
    			size: A3;*/
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
                        <h3><?php //echo $h1_title; ?></h3>
                    </td>
                </tr>
            </table>
        </div>

        <div class="print_body" style="padding-top: 10px; font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
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
                <tr>
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
						<th width="5%" rowspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
						<th colspan="4" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">DAFTAR ITEM</th>
						<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">BUDGET AWAL</th>
						<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">PERUBAHAN</th>
						<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
						<th rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">PROGRES<br>KUMULATIF</th>
						<th colspan="4" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">REQUEST</th>
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
						<th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-left-width:2px; border-left-color:#000;">KODE</th>
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
						<td nowrap style="text-align:center; border-right: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
	               	</tr>
	            </thead>
	            <tbody>
               	<?php
			   		if($ITM_GROUP == 'All')
					{
						$ADDQUERY	= "";
					}
					else
					{
						$ADDQUERY	= "AND A.ITM_GROUP = '$ITM_GROUP'";
					}
					
			   		$newRow			= 0;
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
					// $GUM_AMOUNT		= 0;
					$GREALZ_AMOUNT	= 0;
					$GREM_VOLM2		= 0;
					$GREM_AMOUNT2	= 0;
					$GPRED_DEV 		= 0;
				   	$sqlAMC			= "tbl_joblist_detail A 
				   						INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
				   						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' $ADDQUERY";
					$resAMC			= $this->db->count_all($sqlAMC);
					if($resAMC > 0)
					{
						$ITMGROUP 	= "";
						$sqlAM		= "SELECT DISTINCT A.ITM_GROUP FROM tbl_joblist_detail A
										INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
										WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' $ADDQUERY
										ORDER BY A.ITM_GROUP ASC";
						$resAM		= $this->db->query($sqlAM)->result();
						//  AND A.ITM_CODE IN ('G026001001001001','G026001001002 001')
						$GTOT_BUDG 		= 0;
						$GTOT_ADD		= 0;
						$GTOT_BUDG2		= 0;
						$GTOT_REQ		= 0;
						$GTOT_REMREQ	= 0;
						$GTOT_REALZ 	= 0;
						$GTOT_REMREALZ 	= 0;
						foreach($resAM as $rowAM):
							$ITM_GROUP	= $rowAM->ITM_GROUP;							

						// echo "ITM_GROUP: $ITM_GROUP<br>";
						// SUB TOTAL MATERIAL
							
							$TOT_BUDGM 		= 0;
							$TOT_ADDM		= 0;
							$TOT_BUDGM2		= 0;
							$TOT_REQM		= 0;
							$TOT_REMREQM	= 0;
							$TOT_REALZM 	= 0;
							$TOT_REMREALZM 	= 0;
							if($ITM_GROUP == 'M')
							{
								$get_ITMM 	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, 
												SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
												SUM(A.ADD_VOLM) AS ADD_VOLM, A.ADD_PRICE, SUM(A.ADD_JOBCOST) AS ADD_JOBCOST, 
												SUM(A.ADDM_VOLM) AS ADDM_VOLM, SUM(A.ADDM_JOBCOST) AS ADDM_JOBCOST, 
												SUM(A.REQ_VOLM) AS REQ_VOLM, SUM(A.REQ_AMOUNT) AS REQ_AMOUNT, 
												SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.PO_AMOUNT) AS PO_AMOUNT, 
												SUM(A.IR_VOLM) AS IR_VOLM, SUM(A.IR_AMOUNT) AS IR_AMOUNT,
												SUM(A.WO_QTY) AS WO_QTY, SUM(A.WO_AMOUNT) AS WO_AMOUNT,	
												SUM(A.OPN_QTY) AS OPN_QTY, SUM(A.OPN_AMOUNT) AS OPN_AMOUNT,	
												SUM(A.ITM_USED) AS ITM_USED, SUM(A.ITM_USED_AM) AS ITM_USED_AM,
												SUM(A.ITM_RET) AS ITM_RET, SUM(A.ITM_RET_AM) AS ITM_RET_AM,	
												SUM(A.ITM_STOCK) AS ITM_STOCK, SUM(ITM_STOCK_AM) AS ITM_STOCK_AM, 
												B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
												FROM tbl_joblist_detail A
												INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' AND A.ITM_GROUP = 'M'
												GROUP BY A.ITM_CODE";
								$res_ITMM 	= $this->db->query($get_ITMM);
								if($res_ITMM->num_rows() > 0)
								{
									$newRow 		= $newRow;
									$TOT_BUDGM 		= 0;
									$TOT_ADDM 		= 0;
									$TOT_BUDGM2		= 0;
									$TOT_REQM 		= 0;
									$TOT_REMREQM 	= 0;
									$TOT_REALZM 	= 0;
									$TOT_REMREALZM 	= 0;
									foreach($res_ITMM->result() as $rM):
										$newRow 	= $newRow + 1;
										$ITM_CODE	= $rM->ITM_CODE;
										$ITM_NAME1	= $rM->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT	= $rM->ITM_UNIT;
										$ITM_GROUP	= $rM->ITM_GROUP;
										$ITM_VOLM	= $rM->ITM_VOLMBG;				// STOCK
										$ITM_LASTP	= $rM->ITM_LASTP;
										$ITM_VOLMBG	= $rM->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rM->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $rM->ITM_BUDG;		// BUDGET AMOUNTs

										// TOTAL BUDG AWAL MATERIAL
											$TOT_BUDGM 	= $TOT_BUDGM + $ITM_BUDG;

										// $sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
										// 				FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										// $resJBUD	= $this->db->query($sqlJBUD)->result();
										// foreach($resJBUD as $rowJBUD):
										// 	$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										// endforeach;

										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$ITM_BUDG 	= $rM->ITM_PRICE;
										// 	if($ITM_VOLMBG == 0)
										// 		$ITM_BUDG 	= 0;
										// }
										
										// ADDENDUM
											$ADD_VOLM	= $rM->ADD_VOLM;
											$ADD_TOTAL	= $rM->ADD_JOBCOST;
											$ADDM_VOLM	= $rM->ADDM_VOLM;
											$ADDM_TOTAL	= $rM->ADDM_JOBCOST;

										// TOTAL ADENDUM MATERIAL
											$TOT_ADDM 		= $TOT_ADDM + ($ADD_TOTAL - $ADDM_TOTAL);
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

											$TOT_BUDGM2		= $TOT_BUDGM + $TOT_ADDM;
										
										$KUM_PROG	= 0;
										
										// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
											// PO
											$PO_VOLM	= $rM->PO_VOLM;
											$PO_AMOUNT	= $rM->PO_AMOUNT;
											if($PO_VOLM == 0)
												$PO_PRICE	= 0;
											else
												$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

											// WO
											$WO_QTY		= $rM->WO_QTY;
											$WO_AMOUNT	= $rM->WO_AMOUNT;
											if($WO_QTY == 0)
												$WO_PRICE	= 0;
											else
												$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rM->ITM_USED;
											$ITM_USED_AM	= $rM->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REQ_VOLM 		= $PO_VOLM + $WO_QTY;
											$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT;
											if($REQ_VOLM == 0)
												$REQ_PRICE 	= 0;
											else
												$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;
											
											
											$TOT_REQM 		= $TOT_REQM + $REQ_AMOUNT;

										// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
											// OPN
											$OPN_QTY	= $rM->OPN_QTY;
											$OPN_AMOUNT	= $rM->OPN_AMOUNT;
											if($OPN_QTY == 0)
												$OPN_PRICE	= 0;
											else
												$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
											
											// OPN-RET
											$ITM_RET	= $rM->ITM_RET;
											$ITM_RET_AM	= $rM->ITM_RET_AM;
											if($ITM_RET == 0)
												$ITMRET_PRICE	= 0;
											else
												$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

											// IR(LPM)
											$IR_VOLM	= $rM->IR_VOLM;
											$IR_AMOUNT	= $rM->IR_AMOUNT;
											if($IR_VOLM == 0)
												$IR_PRICE	= 0;
											else
												$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rM->ITM_USED;
											$ITM_USED_AM	= $rM->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
											$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;

											$TOT_REALZM 	= $TOT_REALZM + $REALZ_AMOUNT;


										// $ITM_OUT	= $rM->ITM_OUT;
										// $ITM_OUTP	= $rM->ITM_OUTP;
										// $ITM_OUTP1	= $rM->UM_AMOUNT;	// UNTUK LS
										// $UM_AMOUNT	= $ITM_OUTP;
										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$UM_AMOUNT 		= $ITM_OUTP1;
										// }

										// if($ITM_OUT == 0)
										// 	$OPN_PRICE	= 0;
										// else
										// 	$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $REQ_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $REQ_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
											
											$TOT_REMREQM 	= $TOT_REMREQM + $REM_AMOUNT;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
											// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
											$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;

											$TOT_REMREALZM 	= $TOT_REMREALZM + $REM_AMOUNT2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_VAL	= $REALZ_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
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
											// $PRED_DEV		= $UM_AMOUNT;
											$PRED_DEV		= $REALZ_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $newRow; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
												<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
												<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG, 2); ?></td>

												<!-- PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($KUM_PROG, 2); ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRED_VAL, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
														<?php echo number_format($PRED_DEV, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr style="background:#CCCCCC; font-weight: bold;">
											<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL MATERIAL</td>
											<!-- BUDGET AWAL --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM, 2); ?></td>

											<!-- PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ADDM, 2); ?></td>
												
											<!-- SETELAH PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
												
											<!-- PROGRES KUMULATIF --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
												
											<!-- REQUEST : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>
												
											<!-- REQUEST : SISA BUDGET THD REQUEST --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQM, 2); ?></td>
												
											<!-- REALISASI : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>
												
											<!-- REALISASI : SISA BUDGET THD REALISASI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												
											<!-- PREDISKSI 100% --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
													<?php //echo number_format($PRED_DEV, 2); ?></td>
										</tr>
									<?php
								}
							}

							$TOT_BUDGU 		= 0;
							$TOT_ADDU		= 0;
							$TOT_BUDGU2		= 0;
							$TOT_REQU		= 0;
							$TOT_REMREQU	= 0;
							$TOT_REALZU 	= 0;
							$TOT_REMREALZU 	= 0;
							if($ITM_GROUP == 'U')
							{
								$get_ITMU 	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, 
												SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
												SUM(A.ADD_VOLM) AS ADD_VOLM, A.ADD_PRICE, SUM(A.ADD_JOBCOST) AS ADD_JOBCOST, 
												SUM(A.ADDM_VOLM) AS ADDM_VOLM, SUM(A.ADDM_JOBCOST) AS ADDM_JOBCOST, 
												SUM(A.REQ_VOLM) AS REQ_VOLM, SUM(A.REQ_AMOUNT) AS REQ_AMOUNT, 
												SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.PO_AMOUNT) AS PO_AMOUNT, 
												SUM(A.IR_VOLM) AS IR_VOLM, SUM(A.IR_AMOUNT) AS IR_AMOUNT,
												SUM(A.WO_QTY) AS WO_QTY, SUM(A.WO_AMOUNT) AS WO_AMOUNT,	
												SUM(A.OPN_QTY) AS OPN_QTY, SUM(A.OPN_AMOUNT) AS OPN_AMOUNT,	
												SUM(A.ITM_USED) AS ITM_USED, SUM(A.ITM_USED_AM) AS ITM_USED_AM,
												SUM(A.ITM_RET) AS ITM_RET, SUM(A.ITM_RET_AM) AS ITM_RET_AM,	
												SUM(A.ITM_STOCK) AS ITM_STOCK, SUM(ITM_STOCK_AM) AS ITM_STOCK_AM, 
												B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
												FROM tbl_joblist_detail A
												INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' AND A.ITM_GROUP = 'U'
												GROUP BY A.ITM_CODE";
								$res_ITMU 	= $this->db->query($get_ITMU);
								if($res_ITMU->num_rows() > 0)
								{
									$newRow 		= $newRow;
									$TOT_BUDGU 		= 0;
									$TOT_ADDU 		= 0;
									$TOT_BUDGU2		= 0;
									$TOT_REQU 		= 0;
									$TOT_REMREQU 	= 0;
									$TOT_REALZU 	= 0;
									$TOT_REMREALZU 	= 0;
									foreach($res_ITMU->result() as $rU):
										$newRow 	= $newRow + 1;
										$ITM_CODE	= $rU->ITM_CODE;
										$ITM_NAME1	= $rU->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT	= $rU->ITM_UNIT;
										$ITM_GROUP	= $rU->ITM_GROUP;
										$ITM_VOLM	= $rU->ITM_VOLMBG;				// STOCK
										$ITM_LASTP	= $rU->ITM_LASTP;
										$ITM_VOLMBG	= $rU->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rU->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $rU->ITM_BUDG;		// BUDGET AMOUNTs

										// TOTAL BUDG AWAL MATERIAL
											$TOT_BUDGU 	= $TOT_BUDGU + $ITM_BUDG;

										// $sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
										// 				FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										// $resJBUD	= $this->db->query($sqlJBUD)->result();
										// foreach($resJBUD as $rowJBUD):
										// 	$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										// endforeach;

										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$ITM_BUDG 	= $rM->ITM_PRICE;
										// 	if($ITM_VOLMBG == 0)
										// 		$ITM_BUDG 	= 0;
										// }
										
										// ADDENDUM
											$ADD_VOLM	= $rU->ADD_VOLM;
											$ADD_TOTAL	= $rU->ADD_JOBCOST;
											$ADDM_VOLM	= $rU->ADDM_VOLM;
											$ADDM_TOTAL	= $rU->ADDM_JOBCOST;

										// TOTAL ADENDUM MATERIAL
											$TOT_ADDU 		= $TOT_ADDU + ($ADD_TOTAL - $ADDM_TOTAL);
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

											$TOT_BUDGU2		= $TOT_BUDGU + $TOT_ADDU;
										
										$KUM_PROG	= 0;
										
										// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
											// PO
											$PO_VOLM	= $rU->PO_VOLM;
											$PO_AMOUNT	= $rU->PO_AMOUNT;
											if($PO_VOLM == 0)
												$PO_PRICE	= 0;
											else
												$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

											// WO
											$WO_QTY		= $rU->WO_QTY;
											$WO_AMOUNT	= $rU->WO_AMOUNT;
											if($WO_QTY == 0)
												$WO_PRICE	= 0;
											else
												$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rU->ITM_USED;
											$ITM_USED_AM	= $rU->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REQ_VOLM 		= $PO_VOLM + $WO_QTY + $ITM_USED;
											$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT;
											if($REQ_VOLM == 0)
												$REQ_PRICE 	= 0;
											else
												$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;
											
											
											$TOT_REQU 		= $TOT_REQU + $REQ_AMOUNT;

										// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
											// OPN
											$OPN_QTY	= $rU->OPN_QTY;
											$OPN_AMOUNT	= $rU->OPN_AMOUNT;
											if($OPN_QTY == 0)
												$OPN_PRICE	= 0;
											else
												$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
											
											// OPN-RET
											$ITM_RET	= $rU->ITM_RET;
											$ITM_RET_AM	= $rU->ITM_RET_AM;
											if($ITM_RET == 0)
												$ITMRET_PRICE	= 0;
											else
												$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

											// IR(LPM)
											$IR_VOLM	= $rU->IR_VOLM;
											$IR_AMOUNT	= $rU->IR_AMOUNT;
											if($IR_VOLM == 0)
												$IR_PRICE	= 0;
											else
												$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rU->ITM_USED;
											$ITM_USED_AM	= $rU->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
											$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;

											$TOT_REALZU 	= $TOT_REALZU + $REALZ_AMOUNT;


										// $ITM_OUT	= $rM->ITM_OUT;
										// $ITM_OUTP	= $rM->ITM_OUTP;
										// $ITM_OUTP1	= $rM->UM_AMOUNT;	// UNTUK LS
										// $UM_AMOUNT	= $ITM_OUTP;
										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$UM_AMOUNT 		= $ITM_OUTP1;
										// }

										// if($ITM_OUT == 0)
										// 	$OPN_PRICE	= 0;
										// else
										// 	$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $REQ_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $REQ_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
											
											$TOT_REMREQU 	= $TOT_REMREQU + $REM_AMOUNT;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
											// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
											$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;

											$TOT_REMREALZU 	= $TOT_REMREALZU + $REM_AMOUNT2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_VAL	= $REALZ_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
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
											// $PRED_DEV		= $UM_AMOUNT;
											$PRED_DEV		= $REALZ_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $newRow; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
												<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
												<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG, 2); ?></td>

												<!-- PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($KUM_PROG, 2); ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRED_VAL, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
														<?php echo number_format($PRED_DEV, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr style="background:#CCCCCC; font-weight: bold;">
											<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL UPAH</td>
											<!-- BUDGET AWAL --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGU, 2); ?></td>

											<!-- PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ADDU, 2); ?></td>
												
											<!-- SETELAH PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGU2, 2); ?></td>
												
											<!-- PROGRES KUMULATIF --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
												
											<!-- REQUEST : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQU, 2); ?></td>
												
											<!-- REQUEST : SISA BUDGET THD REQUEST --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQU, 2); ?></td>
												
											<!-- REALISASI : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZU, 2); ?></td>
												
											<!-- REALISASI : SISA BUDGET THD REALISASI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZU, 2); ?></td>
												
											<!-- PREDISKSI 100% --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
													<?php //echo number_format($PRED_DEV, 2); ?></td>
										</tr>
									<?php
								}
							}

							$TOT_BUDGS 		= 0;
							$TOT_ADDS		= 0;
							$TOT_BUDGS2		= 0;
							$TOT_REQS		= 0;
							$TOT_REMREQS	= 0;
							$TOT_REALZS 	= 0;
							$TOT_REMREALZS 	= 0;
							if($ITM_GROUP == 'S')
							{
								$get_ITMS	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, 
												SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
												SUM(A.ADD_VOLM) AS ADD_VOLM, A.ADD_PRICE, SUM(A.ADD_JOBCOST) AS ADD_JOBCOST, 
												SUM(A.ADDM_VOLM) AS ADDM_VOLM, SUM(A.ADDM_JOBCOST) AS ADDM_JOBCOST, 
												SUM(A.REQ_VOLM) AS REQ_VOLM, SUM(A.REQ_AMOUNT) AS REQ_AMOUNT, 
												SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.PO_AMOUNT) AS PO_AMOUNT, 
												SUM(A.IR_VOLM) AS IR_VOLM, SUM(A.IR_AMOUNT) AS IR_AMOUNT,
												SUM(A.WO_QTY) AS WO_QTY, SUM(A.WO_AMOUNT) AS WO_AMOUNT,	
												SUM(A.OPN_QTY) AS OPN_QTY, SUM(A.OPN_AMOUNT) AS OPN_AMOUNT,	
												SUM(A.ITM_USED) AS ITM_USED, SUM(A.ITM_USED_AM) AS ITM_USED_AM,
												SUM(A.ITM_RET) AS ITM_RET, SUM(A.ITM_RET_AM) AS ITM_RET_AM,	
												SUM(A.ITM_STOCK) AS ITM_STOCK, SUM(ITM_STOCK_AM) AS ITM_STOCK_AM, 
												B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
												FROM tbl_joblist_detail A
												INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' AND A.ITM_GROUP = 'S'
												GROUP BY A.ITM_CODE";
								$res_ITMS 	= $this->db->query($get_ITMS);
								if($res_ITMS->num_rows() > 0)
								{
									$newRow 		= $newRow;
									$TOT_BUDGS 		= 0;
									$TOT_ADDS 		= 0;
									$TOT_BUDGS2		= 0;
									$TOT_REQS 		= 0;
									$TOT_REMREQS 	= 0;
									$TOT_REALZS 	= 0;
									$TOT_REMREALZS 	= 0;
									foreach($res_ITMS->result() as $rS):
										$newRow 	= $newRow + 1;
										$ITM_CODE	= $rS->ITM_CODE;
										$ITM_NAME1	= $rS->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT	= $rS->ITM_UNIT;
										$ITM_GROUP	= $rS->ITM_GROUP;
										$ITM_VOLM	= $rS->ITM_VOLMBG;				// STOCK
										$ITM_LASTP	= $rS->ITM_LASTP;
										$ITM_VOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $rS->ITM_BUDG;		// BUDGET AMOUNTs

										// TOTAL BUDG AWAL MATERIAL
											$TOT_BUDGS 	= $TOT_BUDGS + $ITM_BUDG;

										// $sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
										// 				FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										// $resJBUD	= $this->db->query($sqlJBUD)->result();
										// foreach($resJBUD as $rowJBUD):
										// 	$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										// endforeach;

										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$ITM_BUDG 	= $rM->ITM_PRICE;
										// 	if($ITM_VOLMBG == 0)
										// 		$ITM_BUDG 	= 0;
										// }
										
										// ADDENDUM
											$ADD_VOLM	= $rS->ADD_VOLM;
											$ADD_TOTAL	= $rS->ADD_JOBCOST;
											$ADDM_VOLM	= $rS->ADDM_VOLM;
											$ADDM_TOTAL	= $rS->ADDM_JOBCOST;

										// TOTAL ADENDUM MATERIAL
											$TOT_ADDS 		= $TOT_ADDS + ($ADD_TOTAL - $ADDM_TOTAL);
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

											$TOT_BUDGS2		= $TOT_BUDGS + $TOT_ADDS;
										
										$KUM_PROG	= 0;
										
										// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
											// PO
											$PO_VOLM	= $rS->PO_VOLM;
											$PO_AMOUNT	= $rS->PO_AMOUNT;
											if($PO_VOLM == 0)
												$PO_PRICE	= 0;
											else
												$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

											// WO
											$WO_QTY		= $rS->WO_QTY;
											$WO_AMOUNT	= $rS->WO_AMOUNT;
											if($WO_QTY == 0)
												$WO_PRICE	= 0;
											else
												$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REQ_VOLM 		= $PO_VOLM + $WO_QTY + $ITM_USED;
											$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT;
											if($REQ_VOLM == 0)
												$REQ_PRICE 	= 0;
											else
												$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;
											
											
											$TOT_REQS 		= $TOT_REQS + $REQ_AMOUNT;

										// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
											// OPN
											$OPN_QTY	= $rS->OPN_QTY;
											$OPN_AMOUNT	= $rS->OPN_AMOUNT;
											if($OPN_QTY == 0)
												$OPN_PRICE	= 0;
											else
												$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
											
											// OPN-RET
											$ITM_RET	= $rS->ITM_RET;
											$ITM_RET_AM	= $rS->ITM_RET_AM;
											if($ITM_RET == 0)
												$ITMRET_PRICE	= 0;
											else
												$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

											// IR(LPM)
											$IR_VOLM	= $rS->IR_VOLM;
											$IR_AMOUNT	= $rS->IR_AMOUNT;
											if($IR_VOLM == 0)
												$IR_PRICE	= 0;
											else
												$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
											$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;

											$TOT_REALZS 	= $TOT_REALZS + $REALZ_AMOUNT;


										// $ITM_OUT	= $rM->ITM_OUT;
										// $ITM_OUTP	= $rM->ITM_OUTP;
										// $ITM_OUTP1	= $rM->UM_AMOUNT;	// UNTUK LS
										// $UM_AMOUNT	= $ITM_OUTP;
										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$UM_AMOUNT 		= $ITM_OUTP1;
										// }

										// if($ITM_OUT == 0)
										// 	$OPN_PRICE	= 0;
										// else
										// 	$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $REQ_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $REQ_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
											
											$TOT_REMREQS 	= $TOT_REMREQS + $REM_AMOUNT;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
											// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
											$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;

											$TOT_REMREALZS 	= $TOT_REMREALZS + $REM_AMOUNT2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_VAL	= $REALZ_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
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
											// $PRED_DEV		= $UM_AMOUNT;
											$PRED_DEV		= $REALZ_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $newRow; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
												<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
												<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG, 2); ?></td>

												<!-- PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($KUM_PROG, 2); ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRED_VAL, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
														<?php echo number_format($PRED_DEV, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr style="background:#CCCCCC; font-weight: bold;">
											<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL SUB-KONT</td>
											<!-- BUDGET AWAL --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGS, 2); ?></td>

											<!-- PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ADDS, 2); ?></td>
												
											<!-- SETELAH PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGS2, 2); ?></td>
												
											<!-- PROGRES KUMULATIF --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
												
											<!-- REQUEST : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQS, 2); ?></td>
												
											<!-- REQUEST : SISA BUDGET THD REQUEST --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQS, 2); ?></td>
												
											<!-- REALISASI : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZS, 2); ?></td>
												
											<!-- REALISASI : SISA BUDGET THD REALISASI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZS, 2); ?></td>
												
											<!-- PREDISKSI 100% --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
													<?php //echo number_format($PRED_DEV, 2); ?></td>
										</tr>
									<?php
								}
							}

							$TOT_BUDGT 		= 0;
							$TOT_ADDT		= 0;
							$TOT_BUDGT2		= 0;
							$TOT_REQT		= 0;
							$TOT_REMREQT	= 0;
							$TOT_REALZT 	= 0;
							$TOT_REMREALZT 	= 0;
							if($ITM_GROUP == 'T')
							{
								$get_ITMT	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, 
												SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
												SUM(A.ADD_VOLM) AS ADD_VOLM, A.ADD_PRICE, SUM(A.ADD_JOBCOST) AS ADD_JOBCOST, 
												SUM(A.ADDM_VOLM) AS ADDM_VOLM, SUM(A.ADDM_JOBCOST) AS ADDM_JOBCOST, 
												SUM(A.REQ_VOLM) AS REQ_VOLM, SUM(A.REQ_AMOUNT) AS REQ_AMOUNT, 
												SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.PO_AMOUNT) AS PO_AMOUNT, 
												SUM(A.IR_VOLM) AS IR_VOLM, SUM(A.IR_AMOUNT) AS IR_AMOUNT,
												SUM(A.WO_QTY) AS WO_QTY, SUM(A.WO_AMOUNT) AS WO_AMOUNT,	
												SUM(A.OPN_QTY) AS OPN_QTY, SUM(A.OPN_AMOUNT) AS OPN_AMOUNT,	
												SUM(A.ITM_USED) AS ITM_USED, SUM(A.ITM_USED_AM) AS ITM_USED_AM,
												SUM(A.ITM_RET) AS ITM_RET, SUM(A.ITM_RET_AM) AS ITM_RET_AM,	
												SUM(A.ITM_STOCK) AS ITM_STOCK, SUM(ITM_STOCK_AM) AS ITM_STOCK_AM, 
												B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
												FROM tbl_joblist_detail A
												INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' AND A.ITM_GROUP = 'T'
												GROUP BY A.ITM_CODE";
								$res_ITMT 	= $this->db->query($get_ITMT);
								if($res_ITMT->num_rows() > 0)
								{
									$newRow 		= $newRow;
									$TOT_BUDGT 		= 0;
									$TOT_ADDT 		= 0;
									$TOT_BUDGT2		= 0;
									$TOT_REQT 		= 0;
									$TOT_REMREQT 	= 0;
									$TOT_REALZT 	= 0;
									$TOT_REMREALZT 	= 0;
									foreach($res_ITMT->result() as $rS):
										$newRow 	= $newRow + 1;
										$ITM_CODE	= $rS->ITM_CODE;
										$ITM_NAME1	= $rS->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT	= $rS->ITM_UNIT;
										$ITM_GROUP	= $rS->ITM_GROUP;
										$ITM_VOLM	= $rS->ITM_VOLMBG;				// STOCK
										$ITM_LASTP	= $rS->ITM_LASTP;
										$ITM_VOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $rS->ITM_BUDG;		// BUDGET AMOUNTs

										// TOTAL BUDG AWAL MATERIAL
											$TOT_BUDGT 	= $TOT_BUDGT + $ITM_BUDG;

										// $sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
										// 				FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										// $resJBUD	= $this->db->query($sqlJBUD)->result();
										// foreach($resJBUD as $rowJBUD):
										// 	$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										// endforeach;

										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$ITM_BUDG 	= $rM->ITM_PRICE;
										// 	if($ITM_VOLMBG == 0)
										// 		$ITM_BUDG 	= 0;
										// }
										
										// ADDENDUM
											$ADD_VOLM	= $rS->ADD_VOLM;
											$ADD_TOTAL	= $rS->ADD_JOBCOST;
											$ADDM_VOLM	= $rS->ADDM_VOLM;
											$ADDM_TOTAL	= $rS->ADDM_JOBCOST;

										// TOTAL ADENDUM MATERIAL
											$TOT_ADDT 		= $TOT_ADDT + ($ADD_TOTAL - $ADDM_TOTAL);
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

											$TOT_BUDGT2		= $TOT_BUDGT + $TOT_ADDT;
										
										$KUM_PROG	= 0;
										
										// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
											// PO
											$PO_VOLM	= $rS->PO_VOLM;
											$PO_AMOUNT	= $rS->PO_AMOUNT;
											if($PO_VOLM == 0)
												$PO_PRICE	= 0;
											else
												$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

											// WO
											$WO_QTY		= $rS->WO_QTY;
											$WO_AMOUNT	= $rS->WO_AMOUNT;
											if($WO_QTY == 0)
												$WO_PRICE	= 0;
											else
												$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REQ_VOLM 		= $PO_VOLM + $WO_QTY + $ITM_USED;
											$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT;
											if($REQ_VOLM == 0)
												$REQ_PRICE 	= 0;
											else
												$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;
											
											
											$TOT_REQT 		= $TOT_REQT + $REQ_AMOUNT;

										// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
											// OPN
											$OPN_QTY	= $rS->OPN_QTY;
											$OPN_AMOUNT	= $rS->OPN_AMOUNT;
											if($OPN_QTY == 0)
												$OPN_PRICE	= 0;
											else
												$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
											
											// OPN-RET
											$ITM_RET	= $rS->ITM_RET;
											$ITM_RET_AM	= $rS->ITM_RET_AM;
											if($ITM_RET == 0)
												$ITMRET_PRICE	= 0;
											else
												$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

											// IR(LPM)
											$IR_VOLM	= $rS->IR_VOLM;
											$IR_AMOUNT	= $rS->IR_AMOUNT;
											if($IR_VOLM == 0)
												$IR_PRICE	= 0;
											else
												$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
											$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;

											$TOT_REALZT 	= $TOT_REALZT + $REALZ_AMOUNT;


										// $ITM_OUT	= $rM->ITM_OUT;
										// $ITM_OUTP	= $rM->ITM_OUTP;
										// $ITM_OUTP1	= $rM->UM_AMOUNT;	// UNTUK LS
										// $UM_AMOUNT	= $ITM_OUTP;
										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$UM_AMOUNT 		= $ITM_OUTP1;
										// }

										// if($ITM_OUT == 0)
										// 	$OPN_PRICE	= 0;
										// else
										// 	$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $REQ_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $REQ_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
											
											$TOT_REMREQT 	= $TOT_REMREQT + $REM_AMOUNT;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
											// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
											$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;

											$TOT_REMREALZT 	= $TOT_REMREALZT + $REM_AMOUNT2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_VAL	= $REALZ_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
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
											// $PRED_DEV		= $UM_AMOUNT;
											$PRED_DEV		= $REALZ_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $newRow; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
												<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
												<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG, 2); ?></td>

												<!-- PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($KUM_PROG, 2); ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRED_VAL, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
														<?php echo number_format($PRED_DEV, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr style="background:#CCCCCC; font-weight: bold;">
											<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL ALAT</td>
											<!-- BUDGET AWAL --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGT, 2); ?></td>

											<!-- PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ADDT, 2); ?></td>
												
											<!-- SETELAH PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGT2, 2); ?></td>
												
											<!-- PROGRES KUMULATIF --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
												
											<!-- REQUEST : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQT, 2); ?></td>
												
											<!-- REQUEST : SISA BUDGET THD REQUEST --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQT, 2); ?></td>
												
											<!-- REALISASI : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZT, 2); ?></td>
												
											<!-- REALISASI : SISA BUDGET THD REALISASI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZT, 2); ?></td>
												
											<!-- PREDISKSI 100% --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
													<?php //echo number_format($PRED_DEV, 2); ?></td>
										</tr>
									<?php
								}
							}

							$TOT_BUDGI 		= 0;
							$TOT_ADDI		= 0;
							$TOT_BUDGI2		= 0;
							$TOT_REQI		= 0;
							$TOT_REMREQI	= 0;
							$TOT_REALZI 	= 0;
							$TOT_REMREALZI 	= 0;
							if($ITM_GROUP == 'I')
							{
								$get_ITMI	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, 
												SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
												SUM(A.ADD_VOLM) AS ADD_VOLM, A.ADD_PRICE, SUM(A.ADD_JOBCOST) AS ADD_JOBCOST, 
												SUM(A.ADDM_VOLM) AS ADDM_VOLM, SUM(A.ADDM_JOBCOST) AS ADDM_JOBCOST, 
												SUM(A.REQ_VOLM) AS REQ_VOLM, SUM(A.REQ_AMOUNT) AS REQ_AMOUNT, 
												SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.PO_AMOUNT) AS PO_AMOUNT, 
												SUM(A.IR_VOLM) AS IR_VOLM, SUM(A.IR_AMOUNT) AS IR_AMOUNT,
												SUM(A.WO_QTY) AS WO_QTY, SUM(A.WO_AMOUNT) AS WO_AMOUNT,	
												SUM(A.OPN_QTY) AS OPN_QTY, SUM(A.OPN_AMOUNT) AS OPN_AMOUNT,	
												SUM(A.ITM_USED) AS ITM_USED, SUM(A.ITM_USED_AM) AS ITM_USED_AM,
												SUM(A.ITM_RET) AS ITM_RET, SUM(A.ITM_RET_AM) AS ITM_RET_AM,	
												SUM(A.ITM_STOCK) AS ITM_STOCK, SUM(ITM_STOCK_AM) AS ITM_STOCK_AM, 
												B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
												FROM tbl_joblist_detail A
												INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' AND A.ITM_GROUP = 'I'
												GROUP BY A.ITM_CODE";
								$res_ITMI 	= $this->db->query($get_ITMI);
								if($res_ITMI->num_rows() > 0)
								{
									$newRow 		= $newRow;
									$TOT_BUDGI 		= 0;
									$TOT_ADDI 		= 0;
									$TOT_BUDGI2		= 0;
									$TOT_REQI 		= 0;
									$TOT_REMREQI 	= 0;
									$TOT_REALZI 	= 0;
									$TOT_REMREALZI 	= 0;
									foreach($res_ITMI->result() as $rS):
										$newRow 	= $newRow + 1;
										$ITM_CODE	= $rS->ITM_CODE;
										$ITM_NAME1	= $rS->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT	= $rS->ITM_UNIT;
										$ITM_GROUP	= $rS->ITM_GROUP;
										$ITM_VOLM	= $rS->ITM_VOLMBG;				// STOCK
										$ITM_LASTP	= $rS->ITM_LASTP;
										$ITM_VOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $rS->ITM_BUDG;		// BUDGET AMOUNTs

										// TOTAL BUDG AWAL MATERIAL
											$TOT_BUDGI 	= $TOT_BUDGI + $ITM_BUDG;

										// $sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
										// 				FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										// $resJBUD	= $this->db->query($sqlJBUD)->result();
										// foreach($resJBUD as $rowJBUD):
										// 	$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										// endforeach;

										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$ITM_BUDG 	= $rM->ITM_PRICE;
										// 	if($ITM_VOLMBG == 0)
										// 		$ITM_BUDG 	= 0;
										// }
										
										// ADDENDUM
											$ADD_VOLM	= $rS->ADD_VOLM;
											$ADD_TOTAL	= $rS->ADD_JOBCOST;
											$ADDM_VOLM	= $rS->ADDM_VOLM;
											$ADDM_TOTAL	= $rS->ADDM_JOBCOST;

										// TOTAL ADENDUM MATERIAL
											$TOT_ADDI 		= $TOT_ADDI + ($ADD_TOTAL - $ADDM_TOTAL);
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

											$TOT_BUDGI2		= $TOT_BUDGI + $TOT_ADDI;
										
										$KUM_PROG	= 0;
										
										// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
											// PO
											$PO_VOLM	= $rS->PO_VOLM;
											$PO_AMOUNT	= $rS->PO_AMOUNT;
											if($PO_VOLM == 0)
												$PO_PRICE	= 0;
											else
												$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

											// WO
											$WO_QTY		= $rS->WO_QTY;
											$WO_AMOUNT	= $rS->WO_AMOUNT;
											if($WO_QTY == 0)
												$WO_PRICE	= 0;
											else
												$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REQ_VOLM 		= $PO_VOLM + $WO_QTY + $ITM_USED;
											$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT;
											if($REQ_VOLM == 0)
												$REQ_PRICE 	= 0;
											else
												$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;
											
											
											$TOT_REQI 		= $TOT_REQI + $REQ_AMOUNT;

										// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
											// OPN
											$OPN_QTY	= $rS->OPN_QTY;
											$OPN_AMOUNT	= $rS->OPN_AMOUNT;
											if($OPN_QTY == 0)
												$OPN_PRICE	= 0;
											else
												$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
											
											// OPN-RET
											$ITM_RET	= $rS->ITM_RET;
											$ITM_RET_AM	= $rS->ITM_RET_AM;
											if($ITM_RET == 0)
												$ITMRET_PRICE	= 0;
											else
												$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

											// IR(LPM)
											$IR_VOLM	= $rS->IR_VOLM;
											$IR_AMOUNT	= $rS->IR_AMOUNT;
											if($IR_VOLM == 0)
												$IR_PRICE	= 0;
											else
												$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
											$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;

											$TOT_REALZI 	= $TOT_REALZI + $REALZ_AMOUNT;


										// $ITM_OUT	= $rM->ITM_OUT;
										// $ITM_OUTP	= $rM->ITM_OUTP;
										// $ITM_OUTP1	= $rM->UM_AMOUNT;	// UNTUK LS
										// $UM_AMOUNT	= $ITM_OUTP;
										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$UM_AMOUNT 		= $ITM_OUTP1;
										// }

										// if($ITM_OUT == 0)
										// 	$OPN_PRICE	= 0;
										// else
										// 	$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $REQ_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $REQ_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
											
											$TOT_REMREQI 	= $TOT_REMREQI + $REM_AMOUNT;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
											// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
											$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;

											$TOT_REMREALZI 	= $TOT_REMREALZI + $REM_AMOUNT2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_VAL	= $REALZ_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
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
											// $PRED_DEV		= $UM_AMOUNT;
											$PRED_DEV		= $REALZ_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $newRow; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
												<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
												<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG, 2); ?></td>

												<!-- PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($KUM_PROG, 2); ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRED_VAL, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
														<?php echo number_format($PRED_DEV, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr style="background:#CCCCCC; font-weight: bold;">
											<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL LAIN-LAIN</td>
											<!-- BUDGET AWAL --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGI, 2); ?></td>

											<!-- PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ADDI, 2); ?></td>
												
											<!-- SETELAH PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGI2, 2); ?></td>
												
											<!-- PROGRES KUMULATIF --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
												
											<!-- REQUEST : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQI, 2); ?></td>
												
											<!-- REQUEST : SISA BUDGET THD REQUEST --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQI, 2); ?></td>
												
											<!-- REALISASI : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZI, 2); ?></td>
												
											<!-- REALISASI : SISA BUDGET THD REALISASI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZI, 2); ?></td>
												
											<!-- PREDISKSI 100% --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
													<?php //echo number_format($PRED_DEV, 2); ?></td>
										</tr>
									<?php
								}
							}

							$TOT_BUDGR 		= 0;
							$TOT_ADDR		= 0;
							$TOT_BUDGR2		= 0;
							$TOT_REQR		= 0;
							$TOT_REMREQR	= 0;
							$TOT_REALZR 	= 0;
							$TOT_REMREALZR 	= 0;
							if($ITM_GROUP == 'R')
							{
								$get_ITMR	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, 
												SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
												SUM(A.ADD_VOLM) AS ADD_VOLM, A.ADD_PRICE, SUM(A.ADD_JOBCOST) AS ADD_JOBCOST, 
												SUM(A.ADDM_VOLM) AS ADDM_VOLM, SUM(A.ADDM_JOBCOST) AS ADDM_JOBCOST, 
												SUM(A.REQ_VOLM) AS REQ_VOLM, SUM(A.REQ_AMOUNT) AS REQ_AMOUNT, 
												SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.PO_AMOUNT) AS PO_AMOUNT, 
												SUM(A.IR_VOLM) AS IR_VOLM, SUM(A.IR_AMOUNT) AS IR_AMOUNT,
												SUM(A.WO_QTY) AS WO_QTY, SUM(A.WO_AMOUNT) AS WO_AMOUNT,	
												SUM(A.OPN_QTY) AS OPN_QTY, SUM(A.OPN_AMOUNT) AS OPN_AMOUNT,	
												SUM(A.ITM_USED) AS ITM_USED, SUM(A.ITM_USED_AM) AS ITM_USED_AM,
												SUM(A.ITM_RET) AS ITM_RET, SUM(A.ITM_RET_AM) AS ITM_RET_AM,	
												SUM(A.ITM_STOCK) AS ITM_STOCK, SUM(ITM_STOCK_AM) AS ITM_STOCK_AM, 
												B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
												FROM tbl_joblist_detail A
												INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' AND A.ITM_GROUP = 'R'
												GROUP BY A.ITM_CODE";
								$res_ITMR 	= $this->db->query($get_ITMR);
								if($res_ITMR->num_rows() > 0)
								{
									$newRow 		= $newRow;
									$TOT_BUDGR 		= 0;
									$TOT_ADDR 		= 0;
									$TOT_BUDGR2		= 0;
									$TOT_REQR 		= 0;
									$TOT_REMREQR 	= 0;
									$TOT_REALZR 	= 0;
									$TOT_REMREALZR 	= 0;
									foreach($res_ITMR->result() as $rS):
										$newRow 	= $newRow + 1;
										$ITM_CODE	= $rS->ITM_CODE;
										$ITM_NAME1	= $rS->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT	= $rS->ITM_UNIT;
										$ITM_GROUP	= $rS->ITM_GROUP;
										$ITM_VOLM	= $rS->ITM_VOLMBG;				// STOCK
										$ITM_LASTP	= $rS->ITM_LASTP;
										$ITM_VOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $rS->ITM_BUDG;		// BUDGET AMOUNTs

										// TOTAL BUDG AWAL MATERIAL
											$TOT_BUDGR 	= $TOT_BUDGR + $ITM_BUDG;

										// $sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
										// 				FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										// $resJBUD	= $this->db->query($sqlJBUD)->result();
										// foreach($resJBUD as $rowJBUD):
										// 	$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										// endforeach;

										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$ITM_BUDG 	= $rM->ITM_PRICE;
										// 	if($ITM_VOLMBG == 0)
										// 		$ITM_BUDG 	= 0;
										// }
										
										// ADDENDUM
											$ADD_VOLM	= $rS->ADD_VOLM;
											$ADD_TOTAL	= $rS->ADD_JOBCOST;
											$ADDM_VOLM	= $rS->ADDM_VOLM;
											$ADDM_TOTAL	= $rS->ADDM_JOBCOST;

										// TOTAL ADENDUM MATERIAL
											$TOT_ADDR 		= $TOT_ADDR + ($ADD_TOTAL - $ADDM_TOTAL);
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

											$TOT_BUDGR2		= $TOT_BUDGR + $TOT_ADDR;
										
										$KUM_PROG	= 0;
										
										// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
											// PO
											$PO_VOLM	= $rS->PO_VOLM;
											$PO_AMOUNT	= $rS->PO_AMOUNT;
											if($PO_VOLM == 0)
												$PO_PRICE	= 0;
											else
												$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

											// WO
											$WO_QTY		= $rS->WO_QTY;
											$WO_AMOUNT	= $rS->WO_AMOUNT;
											if($WO_QTY == 0)
												$WO_PRICE	= 0;
											else
												$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REQ_VOLM 		= $PO_VOLM + $WO_QTY + $ITM_USED;
											$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT;
											if($REQ_VOLM == 0)
												$REQ_PRICE 	= 0;
											else
												$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;
											
											
											$TOT_REQR 		= $TOT_REQR + $REQ_AMOUNT;

										// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
											// OPN
											$OPN_QTY	= $rS->OPN_QTY;
											$OPN_AMOUNT	= $rS->OPN_AMOUNT;
											if($OPN_QTY == 0)
												$OPN_PRICE	= 0;
											else
												$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
											
											// OPN-RET
											$ITM_RET	= $rS->ITM_RET;
											$ITM_RET_AM	= $rS->ITM_RET_AM;
											if($ITM_RET == 0)
												$ITMRET_PRICE	= 0;
											else
												$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

											// IR(LPM)
											$IR_VOLM	= $rS->IR_VOLM;
											$IR_AMOUNT	= $rS->IR_AMOUNT;
											if($IR_VOLM == 0)
												$IR_PRICE	= 0;
											else
												$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
											$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;

											$TOT_REALZR 	= $TOT_REALZR + $REALZ_AMOUNT;


										// $ITM_OUT	= $rM->ITM_OUT;
										// $ITM_OUTP	= $rM->ITM_OUTP;
										// $ITM_OUTP1	= $rM->UM_AMOUNT;	// UNTUK LS
										// $UM_AMOUNT	= $ITM_OUTP;
										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$UM_AMOUNT 		= $ITM_OUTP1;
										// }

										// if($ITM_OUT == 0)
										// 	$OPN_PRICE	= 0;
										// else
										// 	$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $REQ_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $REQ_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
											
											$TOT_REMREQR 	= $TOT_REMREQR + $REM_AMOUNT;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
											// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
											$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;

											$TOT_REMREALZR 	= $TOT_REMREALZR + $REM_AMOUNT2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_VAL	= $REALZ_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
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
											// $PRED_DEV		= $UM_AMOUNT;
											$PRED_DEV		= $REALZ_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $newRow; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
												<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
												<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG, 2); ?></td>

												<!-- PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($KUM_PROG, 2); ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRED_VAL, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
														<?php echo number_format($PRED_DEV, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr style="background:#CCCCCC; font-weight: bold;">
											<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL OVERHEAD LAPANGAN</td>
											<!-- BUDGET AWAL --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGR, 2); ?></td>

											<!-- PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ADDR, 2); ?></td>
												
											<!-- SETELAH PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGR2, 2); ?></td>
												
											<!-- PROGRES KUMULATIF --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
												
											<!-- REQUEST : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQR, 2); ?></td>
												
											<!-- REQUEST : SISA BUDGET THD REQUEST --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQR, 2); ?></td>
												
											<!-- REALISASI : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZR, 2); ?></td>
												
											<!-- REALISASI : SISA BUDGET THD REALISASI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZR, 2); ?></td>
												
											<!-- PREDISKSI 100% --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
													<?php //echo number_format($PRED_DEV, 2); ?></td>
										</tr>
									<?php
								}
							}

							$TOT_BUDGO 		= 0;
							$TOT_ADDO		= 0;
							$TOT_BUDGO2		= 0;
							$TOT_REQO		= 0;
							$TOT_REMREQO	= 0;
							$TOT_REALZO 	= 0;
							$TOT_REMREALZO 	= 0;
							if($ITM_GROUP == 'O')
							{
								$get_ITMO	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, 
												SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
												SUM(A.ADD_VOLM) AS ADD_VOLM, A.ADD_PRICE, SUM(A.ADD_JOBCOST) AS ADD_JOBCOST, 
												SUM(A.ADDM_VOLM) AS ADDM_VOLM, SUM(A.ADDM_JOBCOST) AS ADDM_JOBCOST, 
												SUM(A.REQ_VOLM) AS REQ_VOLM, SUM(A.REQ_AMOUNT) AS REQ_AMOUNT, 
												SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.PO_AMOUNT) AS PO_AMOUNT, 
												SUM(A.IR_VOLM) AS IR_VOLM, SUM(A.IR_AMOUNT) AS IR_AMOUNT,
												SUM(A.WO_QTY) AS WO_QTY, SUM(A.WO_AMOUNT) AS WO_AMOUNT,	
												SUM(A.OPN_QTY) AS OPN_QTY, SUM(A.OPN_AMOUNT) AS OPN_AMOUNT,	
												SUM(A.ITM_USED) AS ITM_USED, SUM(A.ITM_USED_AM) AS ITM_USED_AM,
												SUM(A.ITM_RET) AS ITM_RET, SUM(A.ITM_RET_AM) AS ITM_RET_AM,	
												SUM(A.ITM_STOCK) AS ITM_STOCK, SUM(ITM_STOCK_AM) AS ITM_STOCK_AM, 
												B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
												FROM tbl_joblist_detail A
												INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' AND A.ITM_GROUP = 'O'
												GROUP BY A.ITM_CODE";
								$res_ITMO 	= $this->db->query($get_ITMO);
								if($res_ITMO->num_rows() > 0)
								{
									$newRow 		= $newRow;
									$TOT_BUDGO 		= 0;
									$TOT_ADDO 		= 0;
									$TOT_BUDGO2		= 0;
									$TOT_REQO 		= 0;
									$TOT_REMREQO 	= 0;
									$TOT_REALZO 	= 0;
									$TOT_REMREALZO 	= 0;
									foreach($res_ITMO->result() as $rS):
										$newRow 	= $newRow + 1;
										$ITM_CODE	= $rS->ITM_CODE;
										$ITM_NAME1	= $rS->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT	= $rS->ITM_UNIT;
										$ITM_GROUP	= $rS->ITM_GROUP;
										$ITM_VOLM	= $rS->ITM_VOLMBG;				// STOCK
										$ITM_LASTP	= $rS->ITM_LASTP;
										$ITM_VOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										$ITMVOLMBG	= $rS->ITM_VOLMBG;			// BUDGET VOLUME
										//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
										$ITM_BUDG	= $rS->ITM_BUDG;		// BUDGET AMOUNTs

										// TOTAL BUDG AWAL MATERIAL
											$TOT_BUDGO 	= $TOT_BUDGO + $ITM_BUDG;

										// $sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
										// 				FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										// $resJBUD	= $this->db->query($sqlJBUD)->result();
										// foreach($resJBUD as $rowJBUD):
										// 	$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
										// 	$ITM_BUDG	= $rowJBUD->ITM_BUDG;
										// endforeach;

										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$ITM_BUDG 	= $rM->ITM_PRICE;
										// 	if($ITM_VOLMBG == 0)
										// 		$ITM_BUDG 	= 0;
										// }
										
										// ADDENDUM
											$ADD_VOLM	= $rS->ADD_VOLM;
											$ADD_TOTAL	= $rS->ADD_JOBCOST;
											$ADDM_VOLM	= $rS->ADDM_VOLM;
											$ADDM_TOTAL	= $rS->ADDM_JOBCOST;

										// TOTAL ADENDUM MATERIAL
											$TOT_ADDO 		= $TOT_ADDO + ($ADD_TOTAL - $ADDM_TOTAL);
										
										// AFTER ADDENDUM
											$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
											$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

											$TOT_BUDGO2		= $TOT_BUDGO + $TOT_ADDO;
										
										$KUM_PROG	= 0;
										
										// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
											// PO
											$PO_VOLM	= $rS->PO_VOLM;
											$PO_AMOUNT	= $rS->PO_AMOUNT;
											if($PO_VOLM == 0)
												$PO_PRICE	= 0;
											else
												$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

											// WO
											$WO_QTY		= $rS->WO_QTY;
											$WO_AMOUNT	= $rS->WO_AMOUNT;
											if($WO_QTY == 0)
												$WO_PRICE	= 0;
											else
												$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REQ_VOLM 		= $PO_VOLM + $WO_QTY + $ITM_USED;
											$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT;
											if($REQ_VOLM == 0)
												$REQ_PRICE 	= 0;
											else
												$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;
											
											
											$TOT_REQO 		= $TOT_REQO + $REQ_AMOUNT;

										// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
											// OPN
											$OPN_QTY	= $rS->OPN_QTY;
											$OPN_AMOUNT	= $rS->OPN_AMOUNT;
											if($OPN_QTY == 0)
												$OPN_PRICE	= 0;
											else
												$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
											
											// OPN-RET
											$ITM_RET	= $rS->ITM_RET;
											$ITM_RET_AM	= $rS->ITM_RET_AM;
											if($ITM_RET == 0)
												$ITMRET_PRICE	= 0;
											else
												$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

											// IR(LPM)
											$IR_VOLM	= $rS->IR_VOLM;
											$IR_AMOUNT	= $rS->IR_AMOUNT;
											if($IR_VOLM == 0)
												$IR_PRICE	= 0;
											else
												$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

											// VC(Voucher Cash), VLK(CPRJ), PD
											$ITM_USED		= $rS->ITM_USED;
											$ITM_USED_AM	= $rS->ITM_USED_AM;
											if($ITM_USED == 0)
												$ITM_USEDP	= 0;
											else
												$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

											$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
											$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;

											$TOT_REALZO 	= $TOT_REALZO + $REALZ_AMOUNT;


										// $ITM_OUT	= $rM->ITM_OUT;
										// $ITM_OUTP	= $rM->ITM_OUTP;
										// $ITM_OUTP1	= $rM->UM_AMOUNT;	// UNTUK LS
										// $UM_AMOUNT	= $ITM_OUTP;
										// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
										// {
										// 	$UM_AMOUNT 		= $ITM_OUTP1;
										// }

										// if($ITM_OUT == 0)
										// 	$OPN_PRICE	= 0;
										// else
										// 	$OPN_PRICE	= $UM_AMOUNT / $ITM_OUT;
										
										// KUMULATIF REQUEST
											// HOLD
										
										// REMAIN BUDGET TO REQUEST => BUDGET - REQUEST
											$REM_VOLM	= $ITM_VOLMBG - $REQ_VOLM;
											$REM_AMOUNT	= $ITM_BUDG - $REQ_AMOUNT;
											if($REM_VOLM == 0)
												$REM_PRICE	= 0;
											else
												$REM_PRICE	= $REM_AMOUNT / $REM_VOLM;
											
											$TOT_REMREQO 	= $TOT_REMREQO + $REM_AMOUNT;
										
										// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
											// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
											// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
											$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
											$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
											if($REM_VOLM2 == 0)
												$REM_PRICE2	= 0;
											else
												$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;

											$TOT_REMREALZO 	= $TOT_REMREALZO + $REM_AMOUNT2;
												
										// PREDICTION => REALISASI + (REM_BUDG * LASTPRICE)
											// $PRED_VAL	= $UM_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
											$PRED_VAL	= $REALZ_AMOUNT + ($REM_VOLM2 * $ITM_LASTP);
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
											// $PRED_DEV		= $UM_AMOUNT;
											$PRED_DEV		= $REALZ_AMOUNT;
											if($PRED_DEV == 0)
												$PRED_VAL	= 0;
										}

										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $newRow; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
												<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
												<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
												<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG, 2); ?></td>

												<!-- PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ADD_TOTAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
													
												<!-- PROGRES KUMULATIF --->
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($KUM_PROG, 2); ?></td>
													
												<!-- REQUEST : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SAAT INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REALZ_VOLM, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($REM_VOLM2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REM_AMOUNT2, 2); ?></td>
													
												<!-- PREDISKSI 100% --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRED_VAL, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
														<?php echo number_format($PRED_DEV, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr style="background:#CCCCCC; font-weight: bold;">
											<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL OVERHEAD</td>
											<!-- BUDGET AWAL --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGO, 2); ?></td>

											<!-- PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ADDO, 2); ?></td>
												
											<!-- SETELAH PERUBAHAN --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGO2, 2); ?></td>
												
											<!-- PROGRES KUMULATIF --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
												
											<!-- REQUEST : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQO, 2); ?></td>
												
											<!-- REQUEST : SISA BUDGET THD REQUEST --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQO, 2); ?></td>
												
											<!-- REALISASI : SAAT INI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZO, 2); ?></td>
												
											<!-- REALISASI : SISA BUDGET THD REALISASI --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZO, 2); ?></td>
												
											<!-- PREDISKSI 100% --->
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
												<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">
													<?php //echo number_format($PRED_DEV, 2); ?></td>
										</tr>
									<?php
								}
							}
							
							// TOTAL KESELURUHAN
								// BUDG AWAL
								$GTOT_BUDG 	= $GTOT_BUDG + $TOT_BUDGM + $TOT_BUDGU + $TOT_BUDGS + $TOT_BUDGT + $TOT_BUDGI + $TOT_BUDGR + $TOT_BUDGO;

								// AMANDEMEN
								$GTOT_ADD 	= $GTOT_ADD + $TOT_ADDM + $TOT_ADDU + $TOT_ADDS + $TOT_ADDT + $TOT_ADDI + $TOT_ADDR + $TOT_ADDO;

								// AFTER AMANDEMEN
								$GTOT_BUDG2 	= $GTOT_BUDG2 + $TOT_BUDGM2 + $TOT_BUDGU2 + $TOT_BUDGS2 + $TOT_BUDGT2 + $TOT_BUDGI2 + $TOT_BUDGR2 + $TOT_BUDGO2;

								// REQUEST
								$GTOT_REQ 	= $GTOT_REQ + $TOT_REQM + $TOT_REQU + $TOT_REQS + $TOT_REQT + $TOT_REQI + $TOT_REQR + $TOT_REQO;

								// SISA BUDG THD REQUEST
								$GTOT_REMREQ 	= $GTOT_REMREQ + $TOT_REMREQM + $TOT_REMREQU + $TOT_REMREQS + $TOT_REMREQT + $TOT_REMREQI + $TOT_REMREQR + $TOT_REMREQO;

								// REALISASI
								$GTOT_REALZ 	= $GTOT_REALZ + $TOT_REALZM + $TOT_REALZU + $TOT_REALZS + $TOT_REALZT + $TOT_REALZI + $TOT_REALZR + $TOT_REALZO;

								// SISA BUDG THD REALISASI
								$GTOT_REMREALZ 	= $GTOT_REMREALZ + $TOT_REMREALZM + $TOT_REMREALZU + $TOT_REMREALZS + $TOT_REMREALZT + $TOT_REMREALZI + $TOT_REMREALZR + $TOT_REMREALZO;
						endforeach;

						?>
							<tr style="background:#CCCCCC; font-weight: bold;">
								<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL KESELURUHAN</td>
								<!-- BUDGET AWAL --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
									<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_BUDG, 2); ?></td>

								<!-- PERUBAHAN --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_ADD, 2); ?></td>
									
								<!-- SETELAH PERUBAHAN --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_BUDG2, 2); ?></td>
									
								<!-- PROGRES KUMULATIF --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($KUM_PROG, 2); ?></td>
									
								<!-- REQUEST : SAAT INI --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REQ, 2); ?></td>
									
								<!-- REQUEST : SISA BUDGET THD REQUEST --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REMREQ, 2); ?></td>
									
								<!-- REALISASI : SAAT INI --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REALZ, 2); ?></td>
									
								<!-- REALISASI : SISA BUDGET THD REALISASI --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REMREALZ, 2); ?></td>
									
								<!-- PREDISKSI 100% --->
									<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($GPRED_DEV, 2); ?></td>
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
				</tbody>
            </table>
        </div>
	</body>
</html>