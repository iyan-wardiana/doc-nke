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
						$sqlAM		= "SELECT A.*, B.ITM_NAME, B.ITM_OUT, B.ITM_OUTP, B.UM_AMOUNT
										FROM tbl_joblist_detail A
										INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
										WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != '' $ADDQUERY";
						$resAM		= $this->db->query($sqlAM)->result();
						//  AND A.ITM_CODE IN ('G026001001001001','G026001001002 001')
						foreach($resAM as $rowAM):
							$theRow		= $theRow + 1;
							$ITM_GROUP	= $rowAM->ITM_GROUP;
							$ITM_CODE	= $rowAM->ITM_CODE;
							$ITM_NAME1	= $rowAM->ITM_NAME;
							$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
							$ITM_UNIT	= $rowAM->ITM_UNIT;
							$ITM_VOLM	= $rowAM->ITM_VOLM;				// STOCK
							$ITM_LASTP	= $rowAM->ITM_LASTP;
							$ITM_VOLMBG	= $rowAM->ITM_VOLM;			// BUDGET VOLUME
							$ITMVOLMBG	= $rowAM->ITM_VOLM;			// BUDGET VOLUME
							//$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT
							$ITM_BUDG	= $ITM_VOLMBG * $ITM_LASTP;		// BUDGET AMOUNT

							$sqlJBUD	= "SELECT SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG
											FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
							$resJBUD	= $this->db->query($sqlJBUD)->result();
							foreach($resJBUD as $rowJBUD):
								$ITM_VOLMBG	= $rowJBUD->ITM_VOLMBG;
								$ITMVOLMBG	= $rowJBUD->ITM_VOLMBG;
								$ITM_BUDG	= $rowJBUD->ITM_BUDG;
							endforeach;

							// if(strtoupper($ITM_UNIT) == 'LS' || strtoupper($ITM_UNIT) == 'BLN')
							// {
							// 	$ITM_BUDG 	= $rowAM->ITM_PRICE;
							// 	if($ITM_VOLMBG == 0)
							// 		$ITM_BUDG 	= 0;
							// }
							
							// ADDENDUM
								$ADD_VOLM	= $rowAM->ADD_VOLM;
								$ADD_TOTAL	= $rowAM->ADD_JOBCOST;
								$ADDM_VOLM	= $rowAM->ADDM_VOLM;
								$ADDM_TOTAL	= $rowAM->ADDM_JOBCOST;
							
							// AFTER ADDENDUM
								$ITM_VOLM2	= $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
								$ITM_BUDG2	= $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;
							
							$KUM_PROG	= 0;
							
							// REQ => PO, SPK(WO), VC(Voucher Cash), VLK(CPRJ):
								// PO
								$PO_VOLM	= $rowAM->PO_VOLM;
								$PO_AMOUNT	= $rowAM->PO_AMOUNT;
								if($PO_VOLM == 0)
									$PO_PRICE	= 0;
								else
									$PO_PRICE	= $PO_AMOUNT / $PO_VOLM;

								// WO
								$WO_QTY		= $rowAM->WO_QTY;
								$WO_AMOUNT	= $rowAM->WO_AMOUNT;
								if($WO_QTY == 0)
									$WO_PRICE	= 0;
								else
									$WO_PRICE	= $WO_AMOUNT / $WO_QTY;

								// VC(Voucher Cash), VLK(CPRJ), PD
								$ITM_USED		= $rowAM->ITM_USED;
								$ITM_USED_AM	= $rowAM->ITM_USED_AM;
								if($ITM_USED == 0)
									$ITM_USEDP	= 0;
								else
									$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

								$REQ_VOLM 		= $PO_VOLM + $WO_QTY + $ITM_USED;
								$REQ_AMOUNT 	= $PO_AMOUNT + $WO_AMOUNT + $ITM_USED_AM;
								if($REQ_VOLM == 0)
									$REQ_PRICE 	= 0;
								else
									$REQ_PRICE 	= $REQ_AMOUNT / $REQ_VOLM;

							// REALISASI => OPN, OPN-RET, IR(LPM), VC(Voucher Cash), VLK(CPRJ), PD
								// OPN
								$OPN_QTY	= $rowAM->OPN_QTY;
								$OPN_AMOUNT	= $rowAM->OPN_AMOUNT;
								if($OPN_QTY == 0)
									$OPN_PRICE	= 0;
								else
									$OPN_PRICE	= $OPN_AMOUNT / $OPN_QTY;
								
								// OPN-RET
								$ITM_RET	= $rowAM->ITM_RET;
								$ITM_RET_AM	= $rowAM->ITM_RET_AM;
								if($ITM_RET == 0)
									$ITMRET_PRICE	= 0;
								else
									$ITMRET_PRICE	= $ITM_RET_AM / $ITM_RET;

								// IR(LPM)
								$IR_VOLM	= $rowAM->IR_VOLM;
								$IR_AMOUNT	= $rowAM->IR_AMOUNT;
								if($IR_VOLM == 0)
									$IR_PRICE	= 0;
								else
									$IR_PRICE	= $IR_AMOUNT / $IR_VOLM;

								// VC(Voucher Cash), VLK(CPRJ), PD
								$ITM_USED		= $rowAM->ITM_USED;
								$ITM_USED_AM	= $rowAM->ITM_USED_AM;
								if($ITM_USED == 0)
									$ITM_USEDP	= 0;
								else
									$ITM_USEDP	= $ITM_USED_AM / $ITM_USED;

								$REALZ_VOLM 	= $OPN_QTY + $ITM_RET + $IR_VOLM + $ITM_USED;
								$REALZ_AMOUNT 	= $OPN_AMOUNT + $ITM_RET_AM + $IR_AMOUNT + $ITM_USED_AM;



							// $ITM_OUT	= $rowAM->ITM_OUT;
							// $ITM_OUTP	= $rowAM->ITM_OUTP;
							// $ITM_OUTP1	= $rowAM->UM_AMOUNT;	// UNTUK LS
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
							
							// REMAIN BUDGET TO REALISASI => BUDGET - REALISASI
								// $REM_VOLM2	= $ITM_VOLMBG - $ITM_OUT;
								// $REM_AMOUNT2= $ITM_BUDG - $UM_AMOUNT;
								$REM_VOLM2	= $ITM_VOLMBG - $REALZ_VOLM;
								$REM_AMOUNT2= $ITM_BUDG - $REALZ_AMOUNT;
								if($REM_VOLM2 == 0)
									$REM_PRICE2	= 0;
								else
									$REM_PRICE2	= $REM_AMOUNT2 / $REM_VOLM2;
									
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
		                                
	                                <!-- REQUEST : SAAT INI --->
		                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
		                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GPO_AMOUNT, 2); ?></td>
		                                
	                                <!-- REQUEST : SISA BUDGET THD REQUEST --->
		                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
		                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREM_AMOUNT, 2); ?></td>
		                                
	                                <!-- REALISASI : SAAT INI --->
		                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
		                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREALZ_AMOUNT, 2); ?></td>
		                                
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
									// $GUM_AMOUNT		= 0;
									$GREALZ_AMOUNT 	= 0;
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
							// $GITM_OUT		= $GITM_OUT + $ITM_OUT;
							// $GUM_AMOUNT		= $GUM_AMOUNT + $UM_AMOUNT;
							$GREALZ_AMOUNT 	= $GREALZ_AMOUNT + $REALZ_AMOUNT;
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
		                                
	                                <!-- REQUEST : SAAT INI --->
		                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REQ_VOLM, 3); ?></td>
		                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
		                                
	                                <!-- REQUEST : SISA BUDGET THD REQUEST --->
		                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REM_VOLM, 3); ?></td>
		                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REM_AMOUNT, 2); ?></td>
		                                
	                                <!-- REALISASI : SAAT INI --->
		                                <td nowrap style="text-align:right;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REALZ_VOLM, 3); ?></td>
		                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;<?php if($resAMC == $theRow) { ?> border-bottom-width:2px; border-bottom-color:#000;<?php } ?>"><?php echo number_format($REALZ_AMOUNT, 2); ?></td>
		                                
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
	                                
                                <!-- REQUEST : SAAT INI --->
	                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
	                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GPO_AMOUNT, 2); ?></td>
	                                
                                <!-- REQUEST : SISA BUDGET THD REQUEST --->
	                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
	                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREM_AMOUNT, 2); ?></td>
	                                
                                <!-- REALISASI : SAAT INI --->
	                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
	                                <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GREALZ_AMOUNT, 2); ?></td>
	                                
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
				</tbody>
            </table>
        </div>
	</body>
</html>