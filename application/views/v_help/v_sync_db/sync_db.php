<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 29 Mei 2017
 * File Name	= sync_db.php
 * Location		= -
*/

function hitungHari($awal,$akhir)
{
	$tglAwal = strtotime($awal);
	$tglAkhir = strtotime($akhir);
	$jeda = $tglAkhir - $tglAwal;
	return floor($jeda/(60*60*24));
}

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$TBL_NAME		= '';
if(isset($_POST['submitSync']))
{
	$TBL_NAME 	= $_POST['TBL_NAME'];
	
	if($TBL_NAME == 'DPHD' || $TBL_NAME == 'ALL')
	{
		// DP_HD
		// CEK NEW STATUS
			$DP_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM dp_hd WHERE DAPPROVE = '0' GROUP BY PRJCODE";
			$ResDP_N	= $this->db->query($DP_N)->result();
			foreach($ResDP_N as $rowDP_N) :
				$PRJCODE_N	= $rowDP_N->PRJCODE;
				$TOTDP_N	= $rowDP_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_DP_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_DP_N, TOT_DP_C) VALUES ('$PRJCODE_N', $TOTDP_N, $TOTDP_N)B";
					$this->db->query($INS_DP_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_DP_A	= "UPDATE tbl_dash_transac SET TOT_DP_N = $TOTDP_N, TOT_DP_C = $TOTDP_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_DP_A);	
				}
			endforeach;
		// CEK APPROVE STATUS
			$DP_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM dp_hd WHERE DAPPROVE = '1' GROUP BY PRJCODE";
			$ResDP_A	= $this->db->query($DP_A)->result();
			foreach($ResDP_A as $rowDP_A) :
				$PRJCODE_A	= $rowDP_A->PRJCODE;
				$TOTDP_A	= $rowDP_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_DP_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_DP_A, TOT_DP_C) VALUES ('$PRJCODE_A', $TOTDP_A, $TOTDP_A)";
					$this->db->query($INS_DP_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_DP_A	= "UPDATE tbl_dash_transac SET TOT_DP_A = $TOTDP_A, TOT_DP_C = $TOTDP_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_DP_A);	
				}
			endforeach;
	}
	elseif($TBL_NAME == 'LPMHD' || $TBL_NAME == 'ALL')
	{
		// LPMHD
		// CEK NEW STATUS
			$IR_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM lpmhd WHERE APPROVE = '0' GROUP BY PRJCODE";
			$ResIR_N	= $this->db->query($IR_N)->result();
			foreach($ResIR_N as $rowIR_N) :
				$PRJCODE_N	= $rowIR_N->PRJCODE;
				$TOTIR_N	= $rowIR_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_IR_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_IR_N, TOT_IR_C) VALUES ('$PRJCODE_N', $TOTIR_N, $TOTIR_N)B";
					$this->db->query($INS_IR_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_IR_A	= "UPDATE tbl_dash_transac SET TOT_IR_N = $TOTIR_N, TOT_IR_C = $TOTIR_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_IR_A);	
				}
			endforeach;
		// CEK APPROVE STATUS
			$IR_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM lpmhd WHERE APPROVE = '1' GROUP BY PRJCODE";
			$ResIR_A	= $this->db->query($IR_A)->result();
			foreach($ResIR_A as $rowIR_A) :
				$PRJCODE_A	= $rowIR_A->PRJCODE;
				$TOTIR_A	= $rowIR_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_IR_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_IR_A, TOT_IR_C) VALUES ('$PRJCODE_A', $TOTIR_A, $TOTIR_A)";
					$this->db->query($INS_IR_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_IR_A	= "UPDATE tbl_dash_transac SET TOT_IR_A = $TOTIR_A, TOT_IR_C = $TOTIR_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_IR_A);	
				}
			endforeach;
	}
	elseif($TBL_NAME == 'OPHD' || $TBL_NAME == 'ALL')
	{
		// OP_HD
		// CEK NEW STATUS
			$PO_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM op_hd WHERE APPROVE = '0' GROUP BY PRJCODE";
			$ResPO_N	= $this->db->query($PO_N)->result();
			foreach($ResPO_N as $rowPO_N) :
				$PRJCODE_N	= $rowPO_N->PRJCODE;
				$TOTPO_N	= $rowPO_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_PO_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_PO_N, TOT_PO_C) VALUES ('$PRJCODE_N', $TOTPO_N, $TOTPO_N)";
					$this->db->query($INS_PO_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_PO_A	= "UPDATE tbl_dash_transac SET TOT_PO_N = $TOTPO_N, TOT_PO_C = $TOTPO_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_PO_A);	
				}
			endforeach;
		// CEK APPROVE STATUS
			$PO_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM op_hd WHERE APPROVE = '1' GROUP BY PRJCODE";
			$ResPO_A	= $this->db->query($PO_A)->result();
			foreach($ResPO_A as $rowPO_A) :
				$PRJCODE_A	= $rowPO_A->PRJCODE;
				$TOTPO_A	= $rowPO_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_PO_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_PO_A, TOT_PO_C) VALUES ('$PRJCODE_A', $TOTPO_A, $TOTPO_A)";
					$this->db->query($INS_PO_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_PO_A	= "UPDATE tbl_dash_transac SET TOT_PO_A = $TOTPO_A, TOT_PO_C = $TOTPO_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_PO_A);	
				}
			endforeach;
	}
	elseif($TBL_NAME == 'SPKHD' || $TBL_NAME == 'ALL')
	{
		// SPKHD
		// CEK NEW STATUS
			$SPK_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM spkhd WHERE APPROVE = '0' GROUP BY PRJCODE";
			$ResSPK_N	= $this->db->query($SPK_N)->result();
			foreach($ResSPK_N as $rowSPK_N) :
				$PRJCODE_N	= $rowSPK_N->PRJCODE;
				$TOTSPK_N	= $rowSPK_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_SPK_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_SPK_N, TOT_SPK_C) VALUES ('$PRJCODE_N', $TOTSPK_N, $TOTSPK_N)";
					$this->db->query($INS_SPK_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_SPK_A	= "UPDATE tbl_dash_transac SET TOT_SPK_N = $TOTSPK_N, TOT_SPK_C = $TOTSPK_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_SPK_A);	
				}
			endforeach;
		// CEK APPROVE STATUS
			$SPK_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM spkhd WHERE APPROVE = '1' GROUP BY PRJCODE";
			$ResSPK_A	= $this->db->query($SPK_A)->result();
			foreach($ResSPK_A as $rowSPK_A) :
				$PRJCODE_A	= $rowSPK_A->PRJCODE;
				$TOTSPK_A	= $rowSPK_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_SPK_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_SPK_A, TOT_SPK_C) VALUES ('$PRJCODE_A', $TOTSPK_A, $TOTSPK_A)";
					$this->db->query($INS_SPK_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_SPK_A	= "UPDATE tbl_dash_transac SET TOT_SPK_A = $TOTSPK_A, TOT_SPK_C = $TOTSPK_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_SPK_A);	
				}
			endforeach;
	}
	elseif($TBL_NAME == 'SPPHD' || $TBL_NAME == 'ALL')
	{
		// SPPHD
		// CEK NEW STATUS
			$SPP_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM spphd WHERE APPROVE = 'N' GROUP BY PRJCODE";
			$ResSPP_N	= $this->db->query($SPP_N)->result();
			foreach($ResSPP_N as $rowSPP_N) :
				$PRJCODE_N	= $rowSPP_N->PRJCODE;
				$TOTSPP_N	= $rowSPP_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_SPP_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_REQ_N, TOT_REQ_C) VALUES ('$PRJCODE_N', $TOTSPP_N, $TOTSPP_N)";
					$this->db->query($INS_SPP_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_SPP_A	= "UPDATE tbl_dash_transac SET TOT_REQ_N = $TOTSPP_N, TOT_REQ_C = $TOTSPP_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_SPP_A);	
				}
			endforeach;
		// CEK APPROVE STATUS
			$SPP_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM spphd WHERE APPROVE = 'Y' GROUP BY PRJCODE";
			$ResSPP_A	= $this->db->query($SPP_A)->result();
			foreach($ResSPP_A as $rowSPP_A) :
				$PRJCODE_A	= $rowSPP_A->PRJCODE;
				$TOTSPP_A	= $rowSPP_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_SPP_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_REQ_A, TOT_REQ_C) VALUES ('$PRJCODE_A', $TOTSPP_A, $TOTSPP_A)";
					$this->db->query($INS_SPP_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_SPP_A	= "UPDATE tbl_dash_transac SET TOT_REQ_A = $TOTSPP_A, TOT_REQ_C = $TOTSPP_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_SPP_A);	
				}
			endforeach;
	}
	elseif($TBL_NAME == 'VOCHD' || $TBL_NAME == 'ALL')
	{
		// VOCHD
		// CEK NEW STATUS
			$VOC_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM vochd WHERE APPROVE = '0' GROUP BY PRJCODE";
			$ResVOC_N	= $this->db->query($VOC_N)->result();
			foreach($ResVOC_N as $rowVOC_N) :
				$PRJCODE_N	= $rowVOC_N->PRJCODE;
				$TOTVOC_N	= $rowVOC_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_VOC_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE,TOT_VOC_N,TOT_VOC_C) VALUES ('$PRJCODE_N',$TOTVOC_N,$TOTVOC_N)";
					$this->db->query($INS_VOC_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_VOC_A	= "UPDATE tbl_dash_transac SET TOT_VOC_N = $TOTVOC_N, TOT_VOC_C = $TOTVOC_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_VOC_A);	
				}
			endforeach;	
		// CEK APPROVE STATUS
			$VOC_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM vochd WHERE APPROVE = 'Y' GROUP BY PRJCODE";
			$ResVOC_A	= $this->db->query($VOC_A)->result();
			foreach($ResVOC_A as $rowVOC_A) :
				$PRJCODE_A	= $rowVOC_A->PRJCODE;
				$TOTVOC_A	= $rowVOC_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_VOC_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_VOC_A)
									VALUES ('$PRJCODE_A', $TOTVOC_A)";
					$this->db->query($INS_VOC_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_VOC_A	= "UPDATE tbl_dash_transac SET TOT_VOC_A = $TOTVOC_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_VOC_A);	
				}
			endforeach;
	}
	elseif($TBL_NAME == 'VLKHD' || $TBL_NAME == 'ALL')
	{
		// VLKHD
		// CEK NEW STATUS
			$VLK_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM vochd WHERE APPROVE = '0' GROUP BY PRJCODE";
			$ResVLK_N	= $this->db->query($VLK_N)->result();
			foreach($ResVLK_N as $rowVLK_N) :
				$PRJCODE_N	= $rowVLK_N->PRJCODE;
				$TOTVLK_N	= $rowVLK_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_VLK_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_VLK_N, TOT_VLK_C) VALUES ('$PRJCODE_N', $TOTVLK_N, $TOTVLK_N)";
					$this->db->query($INS_VLK_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_VLK_A	= "UPDATE tbl_dash_transac SET TOT_VLK_N = $TOTVLK_N, TOT_VLK_C = $TOTVLK_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_VLK_A);	
				}
			endforeach;	
		// CEK APPROVE STATUS
			$VLK_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM vochd WHERE APPROVE = 'Y' GROUP BY PRJCODE";
			$ResVLK_A	= $this->db->query($VLK_A)->result();
			foreach($ResVLK_A as $rowVLK_A) :
				$PRJCODE_A	= $rowVLK_A->PRJCODE;
				$TOTVLK_A	= $rowVLK_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_VLK_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_VLK_A, TOT_VLK_C) VALUES ('$PRJCODE_A', $TOTVLK_A, $TOTVLK_A)";
					$this->db->query($INS_VLK_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_VLK_A	= "UPDATE tbl_dash_transac SET TOT_VLK_A = $TOTVLK_A, TOT_VLK_C = $TOTVLK_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_VLK_A);	
				}
			endforeach;	
	}
	elseif($TBL_NAME == 'SIHD' || $TBL_NAME == 'ALL')
	{
		// tbl_siheader
		// CEK NEW STATUS
			$SI_N		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM tbl_siheader WHERE SI_STAT = '1' GROUP BY PRJCODE";
			$ResSI_N	= $this->db->query($SI_N)->result();
			foreach($ResSI_N as $rowSI_N) :
				$PRJCODE_N	= $rowSI_N->PRJCODE;
				$TOTSI_N	= $rowSI_N->TOTNOTAPP;
				$PRJC_N		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_N'";
				$ResPRJC_N	= $this->db->count_all($PRJC_N);
				if($ResPRJC_N == 0)
				{
					// INSERT NEW
					$INS_SI_N	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_SI_N, TOT_SI_C) VALUES ('$PRJCODE_N', $TOTSI_N, $TOTSI_N)";
					$this->db->query($INS_SI_N);				
				}
				else
				{
					// UPDATE NEW
					$UPD_SI_A	= "UPDATE tbl_dash_transac SET TOT_SI_N = $TOTSI_N, TOT_SI_C = $TOTSI_N WHERE PRJ_CODE = '$PRJCODE_N'";
					$this->db->query($UPD_SI_A);	
				}
			endforeach;	
		// CEK APPROVE STATUS
			$SI_A		= "SELECT PRJCODE, COUNT(PRJCODE) AS TOTNOTAPP FROM vochd WHERE APPROVE = 'Y' GROUP BY PRJCODE";
			$ResSI_A	= $this->db->query($SI_A)->result();
			foreach($ResSI_A as $rowSI_A) :
				$PRJCODE_A	= $rowSI_A->PRJCODE;
				$TOTSI_A	= $rowSI_A->TOTNOTAPP;
				$PRJC_A		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE_A'";
				$ResPRJC_A	= $this->db->count_all($PRJC_A);
				if($ResPRJC_A == 0)
				{
					// INSERT NEW
					$INS_SI_A	= "INSERT INTO tbl_dash_transac (PRJ_CODE, TOT_SI_A, TOT_SI_C) VALUES ('$PRJCODE_A', $TOTSI_A, $TOTSI_A)";
					$this->db->query($INS_SI_A);				
				}
				else
				{
					// UPDATE NEW
					$UPD_SI_A	= "UPDATE tbl_dash_transac SET TOT_SI_A = $TOTSI_A, TOT_SI_C = $TOTSI_A WHERE PRJ_CODE = '$PRJCODE_A'";
					$this->db->query($UPD_SI_A);	
				}
			endforeach;
	}
	elseif($TBL_NAME == 'SPPHD_R')
	{
		$sqlqa0		= "TRUNCATE TABLE tbl_purch_report";
		$this->db->query($sqlqa0);
		
		// QRY_A
			$noUa		= 0;
			$QRY_A		= "SELECT DISTINCT SPPCODE FROM spphd_log2017 WHERE YEAR(TRXDATE) = 2017";
			$ResQRY_A	= $this->db->query($QRY_A)->result();
			foreach($ResQRY_A as $rowQRY_A) :
				$noUa		= $noUa + 1;
				$SPPCODE 	= $rowQRY_A->SPPCODE;
				if($noUa == 1)
				{
					$NSPPCODE = $SPPCODE;
				}
				else if($noUa == 2)
				{
					$NSPPCODE = "'$NSPPCODE', '$SPPCODE'";
				}
				else if($noUa > 2)
				{
					$NSPPCODE = "$NSPPCODE, '$SPPCODE'";
				}
			endforeach;
			// DELETE
			$QRY_A1		= "DELETE FROM sppdt_log2017 WHERE SPPCODE NOT IN ($NSPPCODE)";
			$this->db->query($QRY_A1);
			//echo "SPP HAS BEEN DELETED.<BR>";
		
		// QRY_B
			$noUb		= 0;
			$QRY_B		= "SELECT DISTINCT OP_CODE FROM op_hd_log2017 WHERE YEAR(TRXDATE) = 2017";
			$ResQRY_B	= $this->db->query($QRY_B)->result();
			foreach($ResQRY_B as $rowQRY_B) :
				$noUb		= $noUb + 1;
				$OP_CODE 	= $rowQRY_B->OP_CODE;
				if($noUb == 1)
				{
					$NOP_CODE = $OP_CODE;
				}
				else if($noUb == 2)
				{
					$NOP_CODE = "'$NOP_CODE', '$OP_CODE'";
				}
				else if($noUb > 2)
				{
					$NOP_CODE = "$NOP_CODE, '$OP_CODE'";
				}
			endforeach;
			// DELETE
			$QRY_B1		= "DELETE FROM op_dt_log2017 WHERE OP_CODE NOT IN ($NOP_CODE)";
			$this->db->query($QRY_B1);
			//echo "OP HAS BEEN DELETED.<BR>";
		
		// QRY_C
			$noUc		= 0;
			$QRY_C		= "SELECT DISTINCT LPMCODE FROM lpmhd_log WHERE YEAR(TRXDATE) = 2017";
			$ResQRY_C	= $this->db->query($QRY_C)->result();
			foreach($ResQRY_C as $rowQRY_C) :
				$noUc		= $noUc + 1;
				$LPMCODE 	= $rowQRY_C->LPMCODE;
				if($noUc == 1)
				{
					$NLPMCODE = $LPMCODE;
				}
				else if($noUc == 2)
				{
					$NLPMCODE = "'$NLPMCODE', '$LPMCODE'";
				}
				else if($noUc > 2)
				{
					$NLPMCODE = "$NLPMCODE, '$LPMCODE'";
				}
			endforeach;
			// DELETE
			$QRY_C1		= "DELETE FROM lpmdt_log2017 WHERE LPMCODE NOT IN ($NLPMCODE)";
			$this->db->query($QRY_C1);
			//echo "LPM HAS BEEN DELETED.<BR>";
		
		// QRY_D
			$noUd		= 0;
			$QRY_D		= "SELECT DISTINCT SPLCODE FROM op_hd_log2017";
			$ResQRY_D	= $this->db->query($QRY_D)->result();
			foreach($ResQRY_D as $rowQRY_D) :
				$noUd		= $noUd + 1;
				$SPLCODE 	= $rowQRY_D->SPLCODE;
				if($noUd == 1)
				{
					$NSPLCODE = $SPLCODE;
				}
				else if($noUd == 2)
				{
					$NSPLCODE = "'$NSPLCODE', '$SPLCODE'";
				}
				else if($noUd > 2)
				{
					$NSPLCODE = "$NSPLCODE, '$SPLCODE'";
				}
			endforeach;
			// DELETE
			$QRY_D1		= "DELETE FROM supplier_op WHERE SPLCODE NOT IN ($NSPLCODE)";
			$this->db->query($QRY_D1);
		
		$TBL_A		= "SELECT A1.SPPCODE, A1.CSTCODE, A1.SPPDESC, A1.CSTUNIT, A1.SPPVOLM,
							A2.TRXDATE AS SPPDATE, A2.PRJCODE, A2.TRXPDAT, A2.APPRUSR, A2.APVDATE
						FROM sppdt_log2017 A1
							INNER JOIN spphd_log2017 A2 ON A1.SPPCODE = A2.SPPCODE
						WHERE YEAR(A2.TRXDATE) = 2017";
		$ResTBL_A	= $this->db->query($TBL_A)->result();
		$theRow		= 0;
		$KOLOM3		= 0;
		$KOLOM4		= 0;
		$KOLOM5		= 0;
		$therow		= 0;
		foreach($ResTBL_A as $rowsqlq0) :
			$therow			= $therow + 1;
			$PRJCODE 		= $rowsqlq0->PRJCODE;						// Kolom 1
			$SPP_CODE 		= $rowsqlq0->SPPCODE;						// Kolom SPP CODE
			$SPP_DATE		= $rowsqlq0->SPPDATE;						// -
			$SPP_DATED 		= date('d/m/Y',strtotime($SPP_DATE));		// Kolom SPP DATE
			$SPP_DATED1		= date('Y/m/d',strtotime($SPP_DATE));		// -
			$SPP_MONTH		= date('m',strtotime($SPP_DATE));			// Kolom SPP MONTH
			$SPP_YEAR		= date('Y',strtotime($SPP_DATE));			// Kolom SPP YEAR
			$SPP_CSTCODE 	= $rowsqlq0->CSTCODE;						// Kolom SPP ITEM
			$SPP_VOLM 		= $rowsqlq0->SPPVOLM;						// Kolom SPP VOLUME
			$SPP_CSTUNIT 	= $rowsqlq0->CSTUNIT;						// Kolom SPP UNIT
			$SPP_TRXPDAT 	= $rowsqlq0->TRXPDAT;						// -
			$SPP_TRXPDATD 	= date('d/m/Y',strtotime($SPP_TRXPDAT));	// Kolom SPP MOS PLAN
			$SPP_CSTDESC 	= $rowsqlq0->SPPDESC;						// -
			$PREP_NOTES		= '';			
			$MOS_TARGET		= 7;
			
			// CHECK IS CANCELED OR NO
			$SPPCNC			= 0;
			$sqlCNCSPP		= "cnchd WHERE TRXCODE = '$SPP_CODE'";
			$resCNCSPP 		= $this->db->count_all($sqlCNCSPP);
			if($resCNCSPP > 0)
				$SPPCNC		= 1;
			
			// GET OP DETAIL PER DETAIL
			$SPP_CSTDESC= str_replace('\'', '', $SPP_CSTDESC);
			$sqlq1 		= "op_dt_log2017 A 
								LEFT JOIN op_hd_log2017 A1 ON A.OP_CODE = A1.OP_CODE
							WHERE
								YEAR(A1.TRXDATE) 	= 2017 
								AND A1.SPPCODE 		= '$SPP_CODE'
								AND A.CSTCODE 		= '$SPP_CSTCODE'
								AND A.OP_DESC		= '$SPP_CSTDESC'";
			$resq1 		= $this->db->count_all($sqlq1);
												
			$OP_CODE		= '';
			$OP_CSTCODE		= '';
			$OP_CSTUNIT		= '';
			$OP_VOLM		= 0;
			$OP_TRXDATE		= '';
			$SPLCODE		= '';
			$OP_TRXUSER		= '';
			$OP_DELIVDT		= '';
			$OPCNC			= 0;
			$LPM_CODE		= '';
			$LPM_CSTCODE	= '';
			$LPM_CSTUNIT	= '';
			$LPM_VOLM		= '';
			$LPM_TRXDATE	= '';
			$DURATION_DAY	= '-';
			$DEVIATION_DAY	= '-';
			//$resq1 		= 0;
			if($resq1 > 0)	// IF OP > 0
			{
				$sqlq2 		= "SELECT A.OP_CODE, A.CSTCODE, A.CSTUNIT, A.OP_VOLM, A.OP_DESC,
									A1.TRXDATE, A1.SPLCODE, A1.TRXUSER, A1.DELIVDT
								FROM op_dt_log2017 A
									LEFT JOIN op_hd_log2017 A1 ON A.OP_CODE = A1.OP_CODE
								WHERE
									YEAR(A1.TRXDATE) 	= 2017
									AND A1.SPPCODE 		= '$SPP_CODE'
									AND A.CSTCODE 		= '$SPP_CSTCODE'
									AND A.OP_DESC		= '$SPP_CSTDESC'";
				$resq2 		= $this->db->query($sqlq2)->result();
				$SPP_CODEB		= '';
				$SPP_CSTDESCB	= '';
				$SPLDESC		= '';
				$OPCNC			= 0;
				foreach($resq2 as $rowsqlq2) :
					$OP_CODE	= $rowsqlq2->OP_CODE;					// Kolom OP CODE
					$OP_CSTCODE	= $rowsqlq2->CSTCODE;					// -
					$OP_CSTUNIT	= $rowsqlq2->CSTUNIT;
					$OP_VOLM	= $rowsqlq2->OP_VOLM;
					$OP_DESC	= $rowsqlq2->OP_DESC;
					$SPLCODE	= $rowsqlq2->SPLCODE;
					$OP_TRXDATEX= $rowsqlq2->TRXDATE;					// -
					
					// CHECK IS CANCELED OR NO
					$OPCNC			= 0;
					$sqlCNCOP		= "cnchd WHERE TRXCODE = '$OP_CODE'";
					$resCNCOP		= $this->db->count_all($sqlCNCOP);
					if($resCNCOP > 0)
						$OPCNC		= 1;
					
					$OPDURAT_DAY	= 0;
					$SPP_CODEA		= $SPP_CODE;
					
					if($OP_TRXDATEX != '')
					{
						$OP_TRXDATE		= date('d/m/Y',strtotime($OP_TRXDATEX));	// Kolom OP DATE
						$OP_TRXDATE1	= date('Y/m/d',strtotime($OP_TRXDATEX));	// -
					}
					else
					{
						$OP_TRXDATE		= '';
						$OP_CURRDATE	= date('Y/m/d');
						$OP_TRXDATE1	= date('Y/m/d',strtotime($OP_CURRDATE));	// Kolom OP DATE
					}
						
					$OPDURAT_DAY1	= hitungHari($SPP_DATED1, $OP_TRXDATE1);
					$OPDURAT_DAY	= (int)$OPDURAT_DAY1;							// Kolom OP DURATION
					
					$DELIVDT		= $rowsqlq2->DELIVDT;
					if($DELIVDT != '')
						$OP_DELIVDT	= date('d/m/Y',strtotime($DELIVDT));
					else
						$OP_DELIVDT	= '';
					
					$sqlSPL 		= "SELECT SPLDESC FROM supplier_op WHERE SPLCODE = '$SPLCODE' LIMIT 1";
					$resSPL 		= $this->db->query($sqlSPL)->result();
					foreach($resSPL as $rowSPL) :
						$SPLDESC	= $rowSPL->SPLDESC;
					endforeach;
					
					if($SPLDESC == '')
						$SPLDESC	= 'no name';
					
					// GET THE FIRST LPM DETAIL PER ITEM
					$sqlq4 		= "lpmdt_log2017 A 
										LEFT JOIN lpmhd_log A1 ON A.LPMCODE = A1.LPMCODE
									WHERE
										YEAR(A1.TRXDATE) 	= 2017 
										AND A1.OP_CODE 		= '$OP_CODE'
										AND A.CSTCODE 		= '$OP_CSTCODE'";
					$resq4 		= $this->db->count_all($sqlq4);
					//$resq4		= 0;
					if($resq4 > 0)	// IF LPM > 0
					{
						$OP_DESC	= str_replace('\'', '', $OP_DESC);
						$LPM_VOLM	= 0;
						$sqlq5 		= "SELECT A.LPMCODE, A.CSTCODE, A.CSTUNIT, SUM(A.LPMVOLM) AS LPMVOLM,
											A1.TRXDATE, A1.TRXGDAT AS TRXGDATE 
										FROM lpmdt_log2017 A 
											INNER JOIN lpmhd_log A1 ON A.LPMCODE = A1.LPMCODE
										WHERE
											YEAR(A1.TRXDATE) 	= 2017 
											AND A1.OP_CODE 		= '$OP_CODE'
											AND A.CSTCODE 		= '$OP_CSTCODE'
											AND A.LPMDESC 		= '$OP_DESC'
											LIMIT 1";
						$resq5 		= $this->db->query($sqlq5)->result();
						foreach($resq5 as $rowsqlq5) :
							$LPM_CODE		= $rowsqlq5->LPMCODE;							// Kolom LPM CODE
							$LPM_CSTCODE	= $rowsqlq5->CSTCODE;
							$LPM_CSTUNIT	= $rowsqlq5->CSTUNIT;
							$LPM_VOLM		= $rowsqlq5->LPMVOLM;
							if($LPM_VOLM == '')
								$LPM_VOLM	= 0;
							$LPM_TRXDATE	= $rowsqlq5->TRXDATE;
							$LPM_TRXGDATEx	= $rowsqlq5->TRXGDATE;
							if($LPM_TRXGDATEx != '')
							{
								$LPM_TRXGDATE	= date('d/m/Y',strtotime($LPM_TRXGDATEx));	// Kolom LPM DATE
								$LPM_TRXGDATE1	= date('Y/m/d',strtotime($LPM_TRXGDATEx));
								$DURATION_DAY1	= hitungHari($SPP_DATED1, $LPM_TRXGDATE1);
								$DURATION_DAY	= (int)$DURATION_DAY1;						// Kolom DURATION
								$DEVIATION_DAY	= $MOS_TARGET - $DURATION_DAY;				// Kolom DEVIATION
							}
							else
							{
								$LPM_TRXGDATE	= '';
								$DURATION_DAY	= '-';
								$DEVIATION_DAY	= '-';
							}
							//echo "$SPP_CODE : $OP_CODE : $LPM_CODE X<br>";
							$INS_R1	= "INSERT INTO tbl_purch_report
								(PREP_PROJECT, PREP_SPPCODE, PREP_SPPDATE, PREP_SPPITM, PREP_ITMDESC,
									PREP_SPPVOLM, PREP_SPPUNIT, PREP_SPPMP, PREP_SPPSTAT,
									PREP_OPCODE, PREP_OPDATE, PREP_OPSPL, PREP_OPVOLM, PREP_OPDURAT, PREP_OPSTAT,
									PREP_LPMCODE, PREP_LPMDATE, PREP_LPMGDATE, PREP_LPMVOLM,
									PREP_MOSTRG, PREP_MOSDUR, PREP_MOSDEV, PREP_NOTES, PREP_MONTH, PREP_YEAR) 
								VALUES 
								('$PRJCODE', '$SPP_CODE', '$SPP_DATED', '$SPP_CSTCODE', '$SPP_CSTDESC',
								'$SPP_VOLM', '$SPP_CSTUNIT', '$SPP_TRXPDATD', $SPPCNC,
								'$OP_CODE', '$OP_TRXDATE', '$SPLDESC', $OP_VOLM, '$OPDURAT_DAY', $OPCNC,
								'$LPM_CODE', '$LPM_TRXDATE', '$LPM_TRXGDATE', $LPM_VOLM,
								'$MOS_TARGET', '$DURATION_DAY', '$DEVIATION_DAY', '$PREP_NOTES', $SPP_MONTH, $SPP_YEAR)";
							$this->db->query($INS_R1);
						endforeach;
					}
					else			// IF LPM == 0
					{
						$LPM_CODE		= '';
						$LPM_CSTCODE	= '';
						$LPM_CSTUNIT	= '';
						$LPM_VOLM		= 0;
						$LPM_TRXDATE	= '';
						$LPM_TRXGDATE	= '';
						$DURATION_DAY	= '-';
						$DEVIATION_DAY	= '-';
						//echo "$SPP_CODE : $OP_CODE : $LPM_CODE Y<br>";
						$INS_R2	= "INSERT INTO tbl_purch_report
								(PREP_PROJECT, PREP_SPPCODE, PREP_SPPDATE, PREP_SPPITM, PREP_ITMDESC, 									
									PREP_SPPVOLM, PREP_SPPUNIT, PREP_SPPMP, PREP_SPPSTAT,
									PREP_OPCODE, PREP_OPDATE, PREP_OPSPL, PREP_OPVOLM, PREP_OPDURAT, PREP_OPSTAT,
									PREP_LPMCODE, PREP_LPMDATE, PREP_LPMGDATE, PREP_LPMVOLM,
									PREP_MOSTRG, PREP_MOSDUR, PREP_MOSDEV, PREP_NOTES, PREP_MONTH, PREP_YEAR) 
								VALUES 
								('$PRJCODE', '$SPP_CODE', '$SPP_DATED', '$SPP_CSTCODE', '$SPP_CSTDESC',
								'$SPP_VOLM', '$SPP_CSTUNIT', '$SPP_TRXPDATD', $SPPCNC,
								'$OP_CODE', '$OP_TRXDATE', '$SPLDESC', $OP_VOLM, '$OPDURAT_DAY', $OPCNC,
								'$LPM_CODE', '$LPM_TRXDATE', '$LPM_TRXGDATE', $LPM_VOLM, 
								'$MOS_TARGET', '$DURATION_DAY', '$DEVIATION_DAY', '$PREP_NOTES', $SPP_MONTH, $SPP_YEAR)";
						$this->db->query($INS_R2);
					}
				endforeach;
			}
			else			// IF OP = 0
			{
				//echo " (OP = 0) : LPM = 0<br>";
				$OP_CODE	= '';					// Kolom OP CODE
				$OP_CSTCODE	= '';					// -
				$OP_DESC	= '';
				$OP_TRXDATEX= '';					// -
				
				$SPLDESC		= '';
				$OP_VOLM		= 0;
				$OPDURAT_DAY	= 0;
				
				$LPM_CODE		= '';
				$LPM_CSTCODE	= '';
				$LPM_CSTUNIT	= '';
				$LPM_VOLM		= 0;
				$SPLDESC		= 'no name';
				$LPM_TRXDATE	= '';
				$LPM_TRXGDATE	= '';
				$DURATION_DAY	= '-';
				$DEVIATION_DAY	= '-';
				
				$OP_CURRDATE	= date('Y/m/d');
				$OP_TRXDATE1	= date('Y/m/d',strtotime($OP_CURRDATE));
						
				$OPDURAT_DAY1	= hitungHari($SPP_DATED1, $OP_TRXDATE1);
				$OPDURAT_DAY	= (int)$OPDURAT_DAY1;
				//echo "$therow. $SPP_CODE  ($SPP_MONTH) - $OP_CODE<br>";
				//echo "$SPP_CODE : $OP_CODE : $LPM_CODE Z<br>";
				$INS_R3	= "INSERT INTO tbl_purch_report
						(PREP_PROJECT, PREP_SPPCODE, PREP_SPPDATE, PREP_SPPITM, PREP_ITMDESC,
							PREP_SPPVOLM, PREP_SPPUNIT, PREP_SPPMP, PREP_SPPSTAT,
							PREP_OPCODE, PREP_OPDATE, PREP_OPSPL, PREP_OPVOLM, PREP_OPDURAT, PREP_OPSTAT,
							PREP_LPMCODE, PREP_LPMDATE, PREP_LPMGDATE, PREP_LPMVOLM,
							PREP_MOSTRG,PREP_MOSDUR, PREP_MOSDEV, PREP_NOTES, PREP_MONTH, PREP_YEAR) 
						VALUES 
						('$PRJCODE', '$SPP_CODE', '$SPP_DATED', '$SPP_CSTCODE', '$SPP_CSTDESC',
						'$SPP_VOLM', '$SPP_CSTUNIT', '$SPP_TRXPDATD', $SPPCNC,
						'$OP_CODE', '$OP_TRXDATE', '$SPLDESC', $OP_VOLM, '$OPDURAT_DAY', $OPCNC,
						'$LPM_CODE', '$LPM_TRXDATE', '$LPM_TRXGDATE', $LPM_VOLM, 
						'$MOS_TARGET', '$DURATION_DAY', '$DEVIATION_DAY', '$PREP_NOTES', $SPP_MONTH, $SPP_YEAR)";
				$this->db->query($INS_R3);
			}
		endforeach;	
	}
	elseif($TBL_NAME == 'CSFL')
	{
		// ------------------------------- CASH IN -------------------------------  //
		// IN.1 GET CASH IN FROM votbdt UNTUK DP (CFLCODE = 18) DAN NON-DP (CFLCODE != 18)
			$sqlqa0		= "TRUNCATE TABLE tbl_cf_report_in";
			$this->db->query($sqlqa0);
			
			// DELETE DATA cf_votbdt
			$sqlqa1	= "DELETE FROM cf_votbdt WHERE ACCCODE != '11410'";
			$this->db->query($sqlqa1);
				
			// DELETE DATA cf_votbhd
			$sqlqa2		= "DELETE FROM cf_votbhd WHERE VOCCODE NOT IN (SELECT VOCCODE FROM cf_votbdt)";			
			$this->db->query($sqlqa2);
					
			$sqlqa 		= "SELECT A.VOCCODE, B.TRXDATE, A.ACCCODE, A.PJTCODE, A.LPODESC, A.CSTCOST, A.CFLCODE
								FROM cf_votbdt A
								LEFT JOIN cf_votbhd B ON A.VOCCODE = B.VOCCODE
							WHERE A.ACCCODE = '11410'";
			$resqa 		= $this->db->query($sqlqa)->result();
			foreach($resqa as $rowa) :
				$VOCCODEa1 	= $rowa->VOCCODE;
				$TRXDATEa1 	= $rowa->TRXDATE;
				$ACCCODEa1 	= $rowa->ACCCODE;
				$PJTCODEa1 	= $rowa->PJTCODE;
				$LPODESCa1 	= $rowa->LPODESC;
				$CSTCOSTa1 	= $rowa->CSTCOST;	
				$CFLCODEa1 	= $rowa->CFLCODE;	
				
				$CFLDESCa1 	= '';
				$sqlqa1 	= "SELECT CFLDESC FROM cashflow WHERE CFLCODE = '$CFLCODEa1' LIMIT 1";
				$resqa1 	= $this->db->query($sqlqa1)->result();
				foreach($resqa1 as $rowa1) :
					$CFLDESCa1 	= $rowa1->CFLDESC;
				 endforeach;	
				
				$ACCDESCa2	= '';
				$sqlqa2		= "SELECT ACCDESC FROM chart WHERE ACCCODE = '$ACCCODEa1' LIMIT 1";
				$resqa2		= $this->db->query($sqlqa2)->result();
				foreach($resqa2 as $rowa2) :
					$ACCDESCa2	= $rowa2->ACCDESC;
				endforeach;	
				
				$PRJNAMEa3 	= '';
				$sqlqa3		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PJTCODEa1' LIMIT 1";
				$resqa3		= $this->db->query($sqlqa3)->result();
				foreach($resqa3 as $rowa3) :
					$PRJNAMEa3 	= $rowa3->PRJNAME;
				endforeach;
				
				$LPODESCa1	= str_replace('\'','', $LPODESCa1);
				
				$INS_R1	= "INSERT INTO tbl_cf_report_in
								(DPREP_TYPE, DPREP_TBL, CFLCODE, CFLDESC, VOCCODE, TRXDATE, ACCCODE, ACCDESC, PJTCODE, PRJNAME, 
								LPODESC, CSTCOST) 
							VALUES 
								(1, 'VOT','$CFLCODEa1','$CFLDESCa1', '$VOCCODEa1', '$TRXDATEa1', '$ACCCODEa1', '$ACCDESCa2', '$PJTCODEa1', 
								'$PRJNAMEa3', '$LPODESCa1', '$CSTCOSTa1')";
				$this->db->query($INS_R1);
			endforeach;
		
		// IN.2 GET CASH IN FROM TRXBANK
			// DELETE DUPLICATE DATA IN cf_trxbank_in
				$sqlqa3	= "DELETE FROM cf_trxbank_in WHERE CFLCODE NOT IN ('12','13','14','15','16','17','18','19') AND TRXCANC = 1";
				$this->db->query($sqlqa3);
		
			$sqlqb 		= "SELECT JRNCODE, TRBDATE, CFLCODE, PRJCODE, TRBCOST, VOCCODE
							FROM cf_trxbank_in 
							WHERE CFLCODE IN ('12','13','14','15','16','17','18','19')";
			$resqb 		= $this->db->query($sqlqb)->result();
			foreach($resqb as $rowb) :
				$JRNCODEb1 	= $rowb->JRNCODE;
				$TRBDATEb1 	= $rowb->TRBDATE;
				$CFLCODEb1 	= $rowb->CFLCODE;
				$PRJCODEb1 	= $rowb->PRJCODE;
				$PJTCODEb1 	= $rowb->PRJCODE;
				$TRBCOSTb1 	= $rowb->TRBCOST;
				$VOCCODEb1 	= $rowb->VOCCODE;
				
				$CFLDESCb2 	= '';
				$sqlqb2		= "SELECT CFLDESC FROM cashflow WHERE CFLCODE = '$CFLCODEb1' LIMIT 1";
				$resqb2		= $this->db->query($sqlqb2)->result();
				foreach($resqb2 as $rowb2) :
					$CFLDESCb2 	= $rowb2->CFLDESC;
				 endforeach;
				
				$PRJNAMEb3 	= '';
				$sqlqb3		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PJTCODEb1' LIMIT 1";
				$resqb3		= $this->db->query($sqlqb3)->result();
				foreach($resqb3 as $rowb3) :
					$PRJNAMEb3 	= $rowb3->PRJNAME;
				endforeach;
				
				$INS_R2	= "INSERT INTO tbl_cf_report_in
								(DPREP_TYPE, DPREP_TBL, CFLCODE, CFLDESC, VOCCODE, TRXDATE, PJTCODE, PRJNAME, JRNCODE, TRBDATE, PRJCODE, 
								TRBCOST) 
							VALUES 
								(1, 'TRXBNK', '$CFLCODEb1', '$CFLDESCb2', '$VOCCODEb1','$TRBDATEb1', '$PJTCODEb1', '$PRJNAMEb3', 
								'$JRNCODEb1', '$TRBDATEb1', '$PRJCODEb1', '$TRBCOSTb1')";
				$this->db->query($INS_R2);
			endforeach;
		
		// ------------------------------- CASH OUT -------------------------------  //
		// OUT.1 GET CASH OUT PENGELUARAN GAJI
			$sqlqc0		= "TRUNCATE TABLE tbl_cf_report_out";
			$this->db->query($sqlqc0);
			
			// DELETE DUPLICATE DATA IN cf_vocdt
				$sqlqa4	= "DELETE FROM cf_vocdt WHERE ACCCODE NOT IN ('42411','42413')";
				$this->db->query($sqlqa4);
			
			$sqlqc 	= "SELECT A.VOCCODE, A.ACCCODE, A.PJTCODE, A.LPODESC, A.CSTUNIT, A.LPOVOLM, A.CSTCOST 
						FROM cf_vocdt A
						WHERE A.ACCCODE IN ('42411','42413')";
			$resqc 	= $this->db->query($sqlqc)->result();
			foreach($resqc as $rowc) :
				$VOCCODEc1	= $rowc->VOCCODE;
				$ACCCODEc1	= $rowc->ACCCODE;
				$PJTCODEc1	= $rowc->PJTCODE;
				$LPODESCc1	= $rowc->LPODESC;
				$CSTUNITc1	= $rowc->CSTUNIT;
				$LPOVOLMc1	= $rowc->LPOVOLM;
				$CSTCOSTc1	= $rowc->CSTCOST;
				$CFLCODEc1	= 0;
				
				$PRJNAMEc2 	= '';
				$sqlqc2 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PJTCODEc1' LIMIT 1";
				$resqc2 		= $this->db->query($sqlqc2)->result();
				foreach($resqc2 as $rowc2) :
					$PRJNAMEc2 	= $rowc2->PRJNAME;
				endforeach;
				
				$ACCDESCc3	= '';
				$sqlqc3		= "SELECT ACCDESC FROM chart WHERE ACCCODE = '$ACCCODEc1' LIMIT 1";
				$resqc3		= $this->db->query($sqlqc3)->result();
				foreach($resqc3 as $rowc3) :
					$ACCDESCc3 	= $rowc3->ACCDESC;
				endforeach;
				
				$INS_R3	= "INSERT INTO tbl_cf_report_out
								(DPREP_TYPE, DPREP_TBL, VOCCODE, ACCCODE, ACCDESC, PJTCODE, PRJNAME, LPODESC, CSTCOST) 
							VALUES 
								(2,'VOC','$VOCCODEc1','$ACCCODEc1', '$ACCDESCc3', '$PJTCODEc1', '$PRJNAMEc2', '$LPODESCc1', '$CSTCOSTc1')";
				$this->db->query($INS_R3);
			endforeach;
		
		// OUT.2 GET CASH OUT FROM TRXBANK -- DI HIDDEN KARENA LANGSUNG IMPORT DR ODBC TO TRXBANK_CF
			/*$sqlqd 	= "SELECT JRNCODE, TRBDATE, CFLCODE, PRJCODE, TRBCOST, VOCCODE
						FROM TRXBANK1
						WHERE CFLCODE IN ('20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39')";
			$resqd 	= $this->db->query($sqlqd)->result();
			foreach($resqd as $rowd) :
				$JRNCODEd1 	= $rowd->JRNCODE;
				$TRBDATEd1 	= $rowd->TRBDATE;
				$CFLCODEd1 	= $rowd->CFLCODE;
				$PRJCODEd1 	= $rowd->PRJCODE;
				$TRBCOSTd1 	= $rowd->TRBCOST;
				$VOCCODEd1 	= $rowd->VOCCODE;
				
				$CFLDESCd2 	= '';
				$sqlqd2		= "SELECT CFLDESC FROM CASHFLOW WHERE CFLCODE = '$CFLCODEd1'";
				$resqd2		= $this->db->query($sqlqd2)->result();
				foreach($resqd2 as $rowd2) :
					$CFLDESCd2 	= $rowd2->CFLDESC;
				endforeach;
				
				
				$PRJNAMEd3 	= '';
				$sqlqd3		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODEd1'";
				$resqd3		= $this->db->query($sqlqd3)->result();
				foreach($resqd3 as $rowd3) :
					$PRJNAMEd3 	= $rowd3->PRJNAME;
				endforeach;
				
				$INS_R4	= "INSERT INTO tbl_cf_report_out
								(DPREP_TYPE, DPREP_TBL, CFLCODE, CFLDESC, VOCCODE, TRXDATE, PJTCODE, PRJNAME, JRNCODE, TRBDATE, 
								PRJCODE, TRBCOST) 
							VALUES 
								(2, 'TRXBNK', '$CFLCODEd1', '$CFLDESCd2','$VOCCODEd1', '$TRBDATEd1', '$PRJCODEd1', '$PRJNAMEd3', 
								'$JRNCODEd1', '$TRBDATEd1', '$PRJCODEd1', '$TRBCOSTd1')";
				$this->db->query($INS_R4);
			endforeach;*/
	}
	elseif($TBL_NAME == 'DPHD_R')
	{
		$sqlqa0		= "TRUNCATE TABLE tbl_dp_report";			
		$this->db->query($sqlqa0);
		
		$sqlqa 		= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCOST
						FROM dp_vocdt
						WHERE ACCCODE = '11790' AND CSTCOST != 0";
		$resqa 		= $this->db->query($sqlqa)->result();
		
		foreach($resqa as $rowa) :
			$VOCCODE 	= $rowa->VOCCODE;
				
			$TRXDATE 	= '';
			$SPLCODE	= '';
			$sqlqa0 	= "SELECT TRXDATE, SPLCODE FROM dp_vochd WHERE VOCCODE = '$VOCCODE' LIMIT 1";
			$resqa0 	= $this->db->query($sqlqa0)->result();
			foreach($resqa0 as $rowa0) :
				$TRXDATE 	= $rowa0->TRXDATE;
				$SPLCODE 	= $rowa0->SPLCODE;
			 endforeach;
				 
			$ACCCODE 	= $rowa->ACCCODE;
			$PJTCODE 	= $rowa->PJTCODE;
			$LPODESC 	= $rowa->LPODESC;
			$CSTCOST 	= $rowa->CSTCOST;
			
			$SPLDESCD 	= '';
			$sqlqa1 	= "SELECT SPLDESC FROM supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resqa1 	= $this->db->query($sqlqa1)->result();
			foreach($resqa1 as $rowa1) :
				$SPLDESC 	= $rowa1->SPLDESC;
				$SPLDESCD	= "$SPLCODE : $SPLDESC";
			 endforeach;
			
			$LPODESC	= str_replace('\'','', $LPODESC);
			
			$INS_R1	= "INSERT INTO tbl_dp_report
							(DPR_PRJCODE, DPR_VOCCODE, DPR_ACCCODE, DPR_TRXDATE, DPR_SPLCODE, DPR_SPLDESC, DPR_LPODESC, DPR_CSTCOST) 
						VALUES 
							('$PJTCODE','$VOCCODE', '$ACCCODE', '$TRXDATE', '$SPLCODE', '$SPLDESCD', '$LPODESC', $CSTCOST)";
			$this->db->query($INS_R1);
		endforeach;
	}
	elseif($TBL_NAME == 'VOC_OUT')
	{
		//$sqlqa0	= "TRUNCATE TABLE tbl_outpay_report";
		// PROCEDURE :
		// 1. Mendata semua TTKVOC
		
		// DELETE DUPLICATE DATA IN rpaid_trxbank
			//$sqlqa1	= "DELETE FROM routpay_trxbank WHERE YEAR(TRBDATE)< 2013 AND TRXCANC = 1";
			//$this->db->query($sqlqa1);
			
		// DELETE IN rpaid_ttkhd
			$sqlqa2	= "DELETE FROM routpay_ttkhd WHERE TTKPROC = 0";
			$this->db->query($sqlqa2);
			
		// DELETE IN rpaid_ttkdt
			//$sqlqa3	= "DELETE FROM routpay_ttkdt WHERE YEAR(KWTDATE) < 2012";
			//$this->db->query($sqlqa3);
			
		// DELETE SEMUA VOUCHER YANG SUDAH DIPROSES DI TRXBANK
			$sqlqa4	= "DELETE FROM routpay_vochd WHERE TRXCANC = 1 || TRBPROC = 1 || VOCCOST < 1000";
			$this->db->query($sqlqa4);	
		
		// MENCARI SEMUA VOCCODE YANG ADA DI routpay_vochd
			$noUb			= 0;
			$NVOCCODE		= '';
			$sqlqb 			= "SELECT DISTINCT VOCCODE FROM routpay_vochd";
			$resqb 			= $this->db->query($sqlqb)->result();
			foreach($resqb as $rowqb) :
				$noUb		= $noUb + 1;
				$VOCCODE	= $rowqb->VOCCODE;
				if($noUb == 1)
				{
					$NVOCCODE = $VOCCODE;
				}
				else if($noUb == 2)
				{
					$NVOCCODE = "'$NVOCCODE', '$VOCCODE'";
				}
				else if($noUb > 2)
				{
					$NVOCCODE = "$NVOCCODE, '$VOCCODE'";
				}
			endforeach;
		
		// DELETE SEMUA VOUCHER DI routpay_ttkhd TIDAK ADA DI routpay_vochd
			$DEL_VOCHD	= "DELETE FROM routpay_ttkhd WHERE VOCCODE NOT IN ($NVOCCODE)";
			$this->db->query($DEL_VOCHD);	
		
		// MENCARI SEMUA TTKCODE YANG ADA DI routpay_ttkhd
			$noUc		= 0;
			$NTTKCODE	= '';
			$sqlqc 		= "SELECT DISTINCT TTKCODE FROM routpay_ttkhd";
			$resqc 		= $this->db->query($sqlqc)->result();			
			foreach($resqc as $rowc) :
				$noUc		= $noUc + 1;
				$TTKCODE 	= $rowc->TTKCODE;
				if($noUc == 1)
				{
					$NTTKCODE = $TTKCODE;
				}
				else if($noUc == 2)
				{
					$NTTKCODE = "'$NTTKCODE', '$TTKCODE'";
				}
				else if($noUc > 2)
				{
					$NTTKCODE = "$NTTKCODE, '$TTKCODE'";
				}
			endforeach;
		
		// DELETE SEMUA TTKCODE DI routpay_ttkdt YANG TIDAK ADA DI routpay_ttkhd
			$DEL_TTKDT	= "DELETE FROM routpay_ttkdt WHERE TTKCODE NOT IN ($NTTKCODE)";
			$this->db->query($DEL_TTKDT);
		
		// DELETE SEMUA DATA BER-TYPE 1 (OUTSTANDING)
			$sqlqa0		= "DELETE FROM tbl_outpay_report WHERE ROP_TYPE = 1";
			$this->db->query($sqlqa0);
		
		// MENCARI SEMUA VOCCODE YANG ADA DI routpay_trxbank
			/*$noUb			= 0;
			$NVOCCODE		= '';
			$sqlqb 			= "SELECT DISTINCT VOCCODE FROM routpay_trxbank";
			$resqb 			= $this->db->query($sqlqb)->result();
			foreach($resqb as $rowqb) :
				$noUb		= $noUb + 1;
				$VOCCODE	= $rowqb->VOCCODE;
				if($noUb == 1)
				{
					$NVOCCODE = $VOCCODE;
				}
				else if($noUb == 2)
				{
					$NVOCCODE = "'$NVOCCODE', '$VOCCODE'";
				}
				else if($noUb > 2)
				{
					$NVOCCODE = "$NVOCCODE, '$VOCCODE'";
				}
			endforeach;*/
		
		// DELETE SEMUA VOUCHER DI routpay_vochd YANG SUDAH ADA DI routpay_trxbank
			/*$DEL_TRXBNK	= "DELETE FROM routpay_vochd WHERE VOCCODE IN ($NVOCCODE)";
			$this->db->query($DEL_TRXBNK);*/
		
		// DELETE SEMUA VOUCHER DI routpay_ttkhd YANG SUDAH ADA DI routpay_trxbank
			/*$DEL_TTKHD	= "DELETE FROM routpay_ttkhd WHERE VOCCODE IN ($NVOCCODE)";
			$this->db->query($DEL_TTKHD);*/
		
		$sqlqc 			= "SELECT TTKCODE, TTKDATE, SPLCODE, TTKDESC, VOCCODE, VOCCOST
								FROM routpay_ttkhd ORDER BY TTKDATE";
		$resqc 			= $this->db->query($sqlqc)->result();							
		foreach($resqc as $rowsqlqc) :
			$TTKCODE 	= $rowsqlqc->TTKCODE;		
			$TTKDATE 	= $rowsqlqc->TTKDATE;
			$SPLCODE 	= $rowsqlqc->SPLCODE;
			$TTKDESC 	= $rowsqlqc->TTKDESC;
			$VOCCODE 	= $rowsqlqc->VOCCODE;
			$VOCCOST 	= $rowsqlqc->VOCCOST;
			
			$PRJCODE		= '';
			$sqlqca 		= "SELECT PRJCODE FROM routpay_ttkdt WHERE TTKCODE = '$TTKCODE' LIMIT 1";
			$resqca 		= $this->db->query($sqlqca)->result();
			foreach($resqca as $rowqca) :
				$PRJCODE	= $rowqca->PRJCODE;
			endforeach;
			
			$SPLDESC	= '';
			$sqlqcb 	= "SELECT SPLDESC FROM supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resqcb 	= $this->db->query($sqlqcb)->result();
			foreach($resqcb as $rowqcb) :
				$SPLDESC	= $rowqcb->SPLDESC;
			endforeach;
			
			$DUEDATE	= '';
			$sqlqcc 	= "SELECT DUEDATE FROM routpay_vochd WHERE VOCCODE = '$VOCCODE' LIMIT 1";
			$resqcc 	= $this->db->query($sqlqcc)->result();
			foreach($resqcc as $rowqcc) :
				$DUEDATE	= $rowqcc->DUEDATE;
			endforeach;
			$SPLDESC 	= preg_replace('/[^A-Za-z0-9\  ]/', '', $SPLDESC);
			$TTKDESC 	= preg_replace('/[^A-Za-z0-9\  ]/', '', $TTKDESC);
			$INS_R1	= "INSERT INTO tbl_outpay_report
							(ROP_PRJCODE, ROP_TTKCODE, ROP_TTKDATE, ROP_SPLCODE, ROP_SPLDESC, ROP_TTKDESC, 
								ROP_VOCCODE, ROP_VOCDUED, ROP_VOCCOST, ROP_TYPE) 
						VALUES 
							('$PRJCODE','$TTKCODE', '$TTKDATE', '$SPLCODE', '$SPLDESC', '$TTKDESC', 
								'$VOCCODE', '$DUEDATE', $VOCCOST, 1)";
			$this->db->query($INS_R1);
		endforeach;
	}
	elseif($TBL_NAME == 'VOC_PAID')
	{
		$type =1;
		if($type == 1)
		{
			//$sqlqa0	= "DELETE FROM tbl_outpay_report WHERE ROP_TYPE = 2";
			//$this->db->query($sqlqa0);
			
			// DELETE CANCELED DATA IN rpaid_trxbank - OK
				$sql1	= "DELETE FROM rpaid_trxbank WHERE TRXCANC = 1";
				$this->db->query($sql1);
				
			// DELETE DATA CANCELED IN rpaid_ttkhd - OK
				$sql2	= "DELETE FROM rpaid_ttkhd WHERE TRXCANC = 1";
				$this->db->query($sql2);
				
			// DELETE CANCELED DATA IN rpaid_vochd - OK
				$sql3	= "DELETE FROM rpaid_vochd WHERE TRXCANC = 1";
				$this->db->query($sql3);
			
			// CARI SEMUA TTKCODE YANG SUDAH TERINPUT DI tbl_outpay_report - OK
				$noUa		= 0;
				$NTTKCODE	= '';
				$sql4 		= "SELECT DISTINCT ROP_TTKCODE FROM tbl_outpay_report WHERE ROP_TYPE = 2";
				$res4 		= $this->db->query($sql4)->result();			
				foreach($res4 as $row4) :
					$noUa		= $noUa + 1;
					$TTKCODE 	= $row4->ROP_TTKCODE;
					if($noUa == 1)
					{
						$NTTKCODE = $TTKCODE;
					}
					else if($noUa == 2)
					{
						$NTTKCODE = "'$NTTKCODE', '$TTKCODE'";
					}
					else if($noUa > 2)
					{
						$NTTKCODE = "$NTTKCODE, '$TTKCODE'";
					}
				endforeach;
			
			// DELETE SEMUA TTKCODE DI rpaid_ttkhd YANG ADA DI tbl_outpay_report AGAR TIDAK TERINPUT ULANG - OK
				$sql5		= "DELETE FROM rpaid_ttkhd WHERE TTKCODE IN ($NTTKCODE)";
				$this->db->query($sql5);
			
			// DELETE SEMUA TTKCODE DI RPAID_TTKDT YANG TIDAK ADA DI rpaid_ttkhd - OK
				$noUb		= 0;
				$NTTKCODE2	= '';
				$sql6 		= "SELECT DISTINCT TTKCODE FROM rpaid_ttkhd";
				$res6 		= $this->db->query($sql6)->result();			
				foreach($res6 as $row6) :
					$noUb		= $noUb + 1;
					$TTKCODE 	= $row6->TTKCODE;
					if($noUb == 1)
					{
						$NTTKCODE2 = $TTKCODE;
					}
					else if($noUb == 2)
					{
						$NTTKCODE2 = "'$NTTKCODE2', '$TTKCODE'";
					}
					else if($noUb > 2)
					{
						$NTTKCODE2 = "$NTTKCODE2, '$TTKCODE'";
					}
				endforeach;
				$sql7		= "DELETE FROM rpaid_ttkdt WHERE TTKCODE NOT IN ($NTTKCODE2)";
				$this->db->query($sql7);
			
			// COPY DATA IN rpaid_ttkdt	 - OK
				$sqlTRN	= "TRUNCATE TABLE rpaid_ttkdt2";
				$this->db->query($sqlTRN);
				
				$sql7a	= "INSERT INTO rpaid_ttkdt2 SELECT * FROM rpaid_ttkdt GROUP BY TTKCODE, PRJCODE";
				$this->db->query($sql7a);
			
			// CARI SEMUA VOCCODE DI rpaid_ttkhd - OK
				$noUc		= 0;
				$NVOCCODE	= '';
				$sql8 		= "SELECT DISTINCT VOCCODE FROM rpaid_ttkhd where VOCCODE != ''";
				$res8 		= $this->db->query($sql8)->result();			
				foreach($res8 as $row8) :
					$noUc		= $noUc + 1;
					$VOCCODEd 	= $row8->VOCCODE;
					if($noUc == 1)
					{
						$NVOCCODE = $VOCCODEd;
					}
					else if($noUc == 2)
					{
						$NVOCCODE = "'$NVOCCODE', '$VOCCODEd'";
					}
					else if($noUc > 2)
					{
						$NVOCCODE = "$NVOCCODE, '$VOCCODEd'";
					}
				endforeach;
			
			// DELETE SEMUA DATA VOCCODE DI rpaid_trxbank YANG TIDAK ADA DI rpaid_ttkhd - OK
				$sql9		= "DELETE FROM rpaid_trxbank WHERE VOCCODE NOT IN ($NVOCCODE)";
				$this->db->query($sql9);
				
			// DELETE SEMUA DATA VOUCHER DI rpaid_vochd YANG TIDAK ADA DI rpaid_ttkhd - OK
				$sql10	= "DELETE FROM rpaid_vochd WHERE VOCCODE NOT IN ($NVOCCODE)";
				$this->db->query($sql10);
			
			// CARI SEMUA SPLCODE YANG ADA DI rpaid_ttkhd - OK
				$noUd		= 0;
				$NSPLCODE	= '';
				$sql11 		= "SELECT DISTINCT SPLCODE FROM rpaid_ttkhd";
				$res11 		= $this->db->query($sql11)->result();			
				foreach($res11 as $row11) :
					$noUd		= $noUd + 1;
					$SPLCODE 	= $row11->SPLCODE;
					if($noUd == 1)
					{
						$NSPLCODE = $SPLCODE;
					}
					else if($noUd == 2)
					{
						$NSPLCODE = "'$NSPLCODE', '$SPLCODE'";
					}
					else if($noUd > 2)
					{
						$NSPLCODE = "$NSPLCODE, '$SPLCODE'";
					}
				endforeach;
			
			// DELETE SEMUA SPLCODE DI supplier2 YANG TIDAK ADA DI rpaid_ttkhd - OK
				$DEL_SPL	= "DELETE FROM supplier2 WHERE SPLCODE NOT IN ($NSPLCODE)";
				$this->db->query($DEL_SPL);
				
			$sqlqf 			= "SELECT TTKCODE, TTKDATE, SPLCODE, TTKDESC, VOCCODE, VOCCOST FROM rpaid_ttkhd ORDER BY TTKDATE";
			$resqf 			= $this->db->query($sqlqf)->result();							
			foreach($resqf as $rowsqlqf) :
				$TTKCODE 	= $rowsqlqf->TTKCODE;
				$TTKDATE 	= $rowsqlqf->TTKDATE;
				$SPLCODE 	= $rowsqlqf->SPLCODE;
				$TTKDESC 	= $rowsqlqf->TTKDESC;
				$VOCCODE 	= $rowsqlqf->VOCCODE;
				$VOCCOST 	= $rowsqlqf->VOCCOST;
				
				$PRJCODE		= '';
				$sqlqca 		= "SELECT PRJCODE FROM rpaid_ttkdt2 WHERE TTKCODE = '$TTKCODE' LIMIT 1";
				$resqca 		= $this->db->query($sqlqca)->result();
				foreach($resqca as $rowqca) :
					$PRJCODE	= $rowqca->PRJCODE;
				endforeach;
				
				$JRNCODE	= '';
				$DUEDATE	= '';
				if($VOCCODE != '')
				{
					$sqlqcg 	= "SELECT JRNCODE FROM rpaid_trxbank WHERE VOCCODE = '$VOCCODE' LIMIT 1";
					$resqcg 	= $this->db->query($sqlqcg)->result();
					foreach($resqcg as $rowqcg) :
						$JRNCODE	= $rowqcg->JRNCODE;
					endforeach;
				
					$sqlqc 		= "SELECT DUEDATE FROM rpaid_vochd WHERE VOCCODE = '$VOCCODE' LIMIT 1";
					$resqc 		= $this->db->query($sqlqc)->result();			
					foreach($resqc as $rowc) :
						$DUEDATE 	= $rowc->DUEDATE;
					endforeach;
				}
				
				$SPLDESC	= '';
				$sqlqcb 	= "SELECT SPLDESC FROM supplier2 WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resqcb 	= $this->db->query($sqlqcb)->result();
				foreach($resqcb as $rowqcb) :
					$SPLDESC	= $rowqcb->SPLDESC;
				endforeach;
					
				$SPLDESC 	= preg_replace('/[^A-Za-z0-9\  ]/', '', $SPLDESC);
				$TTKDESC 	= preg_replace('/[^A-Za-z0-9\  ]/', '', $TTKDESC);
				$INS_R1	= "INSERT INTO tbl_outpay_report
								(ROP_PRJCODE, ROP_TTKCODE, ROP_TTKDATE, ROP_SPLCODE, ROP_SPLDESC, ROP_TTKDESC, 
									ROP_VOCCODE, ROP_VOCDUED, ROP_VOCCOST, ROP_TYPE, ROP_REF) 
							VALUES 
								('$PRJCODE','$TTKCODE', '$TTKDATE', '$SPLCODE', '$SPLDESC', '$TTKDESC', 
									'$VOCCODE', '$DUEDATE', $VOCCOST, 2, '$JRNCODE')";
				$this->db->query($INS_R1);
			endforeach;
		}
		elseif($type == 2)
		{
			// MENCARI DUEDATE VOCHD
			/*$noUc		= 0;
			$NVOCCODE	= '';
			$sqlqc 		= "SELECT DISTINCT ROP_VOCCODE FROM tbl_outpay_report WHERE ROP_TYPE = 2";
			$resqc 		= $this->db->query($sqlqc)->result();			
			foreach($resqc as $rowc) :
				$noUc		= $noUc + 1;
				$VOCCODE 	= $rowc->ROP_VOCCODE;
				if($noUc == 1)
				{
					$NVOCCODE = $VOCCODE;
				}
				else if($noUc == 2)
				{
					$NVOCCODE = "'$NVOCCODE', '$VOCCODE'";
				}
				else if($noUc > 2)
				{
					$NVOCCODE = "$NVOCCODE, '$VOCCODE'";
				}
			endforeach;*/
			// DELETE SEMUA TTKCODE DI routpay_ttkdt YANG TIDAK ADA DI routpay_ttkhd
			//$DEL_TTKDT	= "DELETE FROM rpaid_vochd_copy WHERE VOCCODE NOT IN ($NVOCCODE)";
			//$this->db->query($DEL_TTKDT);
			
			$sqlqc 		= "SELECT VOCCODE, DUEDATE FROM rpaid_vochd_copy";
			$resqc 		= $this->db->query($sqlqc)->result();			
			foreach($resqc as $rowc) :
				$VOCCODE 	= $rowc->VOCCODE;
				$DUEDATE 	= $rowc->DUEDATE;
				echo "$VOCCODE - $DUEDATE<br>";
					$UPDATED	= "UPDATE tbl_outpay_report SET ROP_VOCDUED = '$DUEDATE' WHERE ROP_VOCCODE = '$VOCCODE' AND ROP_TYPE = 2";
					$this->db->query($UPDATED);
			endforeach;
		}
	}
	elseif($TBL_NAME == 'PLOSS')
	{
		// BIAYA PROYEK				
		// Check untuk bulan yang sama
			$csTable	= "cs201710";
			$theDate	= "2017-10-10";
			$PRJCODE	= "351";
			$YEARP		= date('Y',strtotime($theDate));
			$MONTHP		= (int)date('m',strtotime($theDate));
			$LASTDATE	= date('Y-m-t', strtotime($theDate));
			
			$sqlPRJ1	= "SELECT DISTINCT PRJCODE FROM $csTable WHERE PRJCODE IN ('$PRJCODE')";
			$resPRJ1 	= $this->db->query($sqlPRJ1)->result();
			foreach($resPRJ1 as $rowPRJ1) :
				$PRJCODE		= $rowPRJ1->PRJCODE;
					
				// PERHITUNGAN OVH PUSAT
					$OHC_PUSAT	= 0;
					$sql_OHCP	= "SELECT SUM(CSTVOLM * CSTPUNT) AS OHC_PUSAT
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND CSTDESC LIKE '%Beban Pusat%'";
					$res_OHCP 	= $this->db->query($sql_OHCP)->result();
					foreach($res_OHCP as $row_OHCP) :
						$OHC_PUSAT 	= $row_OHCP->OHC_PUSAT;
					endforeach;
						
					$sqlPL	= "tbl_profitloss 
								WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
					$resPL	= $this->db->count_all($sqlPL);
					if($resPL == 0)
					{
						// GET PRJECT DETAIL			
							$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
							$resPRJ	= $this->db->query($sqlPRJ)->result();
							foreach($resPRJ as $rowPRJ) :
								$PRJNAME 	= $rowPRJ->PRJNAME;
								$PRJCOST 	= $rowPRJ->PRJCOST;
							endforeach;
						
						// SAVE TO PROFITLOSS
							$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PUSAT)
										VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PUSAT')";
							$this->db->query($insPL);
					}
					else
					{
						// SAVE TO PROFITLOSS
							$updPL	= "UPDATE tbl_profitloss SET OHC_PUSAT = '$OHC_PUSAT'
										WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
							$this->db->query($updPL);
					}
					
				// MATERIAL
					$TOT_EMA	= 0;
					$TOT_EMB	= 0;
					$TOT_EMC	= 0;
					$LPMAMTR 	= 0;
					$LPMAMTC 	= 0;
					$VOCAMTR_EM	= 0;
					$VOCAMTC_EM	= 0;
					$sql_EB		= "SELECT SUM(CSTVOLM * CSTPUNT) AS TOT_EMA, SUM(LPMAMTR) AS LPMAMTR, SUM(LPMAMTC) AS LPMAMTC, 
										SUM(VOCAMTR) AS VOCAMTR, SUM(VOCAMTC) AS VOCAMTC,
										SUM(APTCOST + (CSTVOLM * CSTPUNT)) AS TOT_EMC
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND CSTTYPE = 'M'";
					$res_EB 	= $this->db->query($sql_EB)->result();
					foreach($res_EB as $row_EB) :
						$TOT_EMA 	= $row_EB->TOT_EMA;
						$TOT_EMC 	= $row_EB->TOT_EMC;
						$LPMAMTR 	= $row_EB->LPMAMTR;
						$LPMAMTC 	= $row_EB->LPMAMTC;
						$VOCAMTR_EM	= $row_EB->VOCAMTR;
						$VOCAMTC_EM	= $row_EB->VOCAMTC;
					endforeach;
					$TOT_EMB		= $LPMAMTR + $VOCAMTR_EM - $LPMAMTC - $VOCAMTC_EM;
						
					$sqlPL	= "tbl_profitloss 
								WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
					$resPL	= $this->db->count_all($sqlPL);
					if($resPL == 0)
					{
						// GET PRJECT DETAIL			
							$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
							$resPRJ	= $this->db->query($sqlPRJ)->result();
							foreach($resPRJ as $rowPRJ) :
								$PRJNAME 	= $rowPRJ->PRJNAME;
								$PRJCOST 	= $rowPRJ->PRJCOST;
							endforeach;
						
						// SAVE TO PROFITLOSS
							$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, BPP_MTR_A, BPP_MTR_B, BPP_MTR_C)
										VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$TOT_EMA', '$TOT_EMB', '$TOT_EMC')";
							$this->db->query($insPL);
					}
					else
					{
						// SAVE TO PROFITLOSS
							$updPL	= "UPDATE tbl_profitloss SET BPP_MTR_A = '$TOT_EMA', BPP_MTR_B = '$TOT_EMB', BPP_MTR_C = '$TOT_EMC'
										WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
							$this->db->query($updPL);
					}
				
				// UPAH
					$TOT_EUA	= 0;
					$TOT_EUB	= 0;
					$TOT_EUC	= 0;
					$VOCAMTR_EU	= 0;
					$VOCAMTC_EU	= 0;
					$sql_EU		= "SELECT SUM(CSTVOLM * CSTPUNT) AS TOT_EUA, SUM(VOCAMTR) AS VOCAMTR, SUM(VOCAMTC) AS VOCAMTC,
										SUM(APTCOST + (CSTVOLM * CSTPUNT)) AS TOT_EUC
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND CSTTYPE = 'W'";
					$res_EU 	= $this->db->query($sql_EU)->result();
					foreach($res_EU as $row_EU) :
						$TOT_EUA	= $row_EU->TOT_EUA;
						$TOT_EUC	= $row_EU->TOT_EUC;
						$VOCAMTR_EU	= $row_EU->VOCAMTR;
						$VOCAMTC_EU	= $row_EU->VOCAMTC;
					endforeach;
					$TOT_EUB		= $VOCAMTR_EU - $VOCAMTC_EU;
					
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET BPP_UPH_A = '$TOT_EUA', BPP_UPH_B = '$TOT_EUB', BPP_UPH_C = '$TOT_EUC'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
				
				// SUBKONT
					$TOT_ESA	= 0;
					$TOT_ESB	= 0;
					$TOT_ESC	= 0;
					$VOCAMTR_ES	= 0;
					$VOCAMTC_ES	= 0;
					$sql_ES		= "SELECT SUM(CSTVOLM * CSTPUNT) AS TOT_ESA, SUM(VOCAMTR) AS VOCAMTR, SUM(VOCAMTC) AS VOCAMTC,
										SUM(APTCOST + (CSTVOLM * CSTPUNT)) AS TOT_ESC
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND CSTTYPE LIKE '%C%'";
					$res_ES 	= $this->db->query($sql_ES)->result();
					foreach($res_ES as $row_ES) :
						$TOT_ESA	= $row_ES->TOT_ESA;
						$TOT_ESC	= $row_ES->TOT_ESC;
						$VOCAMTR_ES	= $row_ES->VOCAMTR;
						$VOCAMTC_ES	= $row_ES->VOCAMTC;
					endforeach;
					$TOT_ESB		= $VOCAMTR_ES - $VOCAMTC_ES;
					
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET BPP_SUBK_A = '$TOT_ESA', BPP_SUBK_B = '$TOT_ESB', BPP_SUBK_C = '$TOT_ESC'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
				
				// TOOLS / ALAT
					$TOT_ETA	= 0;
					$TOT_ETB	= 0;
					$TOT_ETC	= 0;
					$VOCAMTR_ET	= 0;
					$VOCAMTC_ET	= 0;
					$sql_ET		= "SELECT SUM(CSTVOLM * CSTPUNT) AS TOT_ETA, SUM(VOCAMTR) AS VOCAMTR, SUM(VOCAMTC) AS VOCAMTC,
										SUM(APTCOST + (CSTVOLM * CSTPUNT)) AS TOT_ETC
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND CSTTYPE = 'T'";
					$res_ET 	= $this->db->query($sql_ET)->result();
					foreach($res_ET as $row_ET) :
						$TOT_ETA	= $row_ET->TOT_ETA;
						$TOT_ETC	= $row_ET->TOT_ETC;
						$VOCAMTR_ET	= $row_ET->VOCAMTR;
						$VOCAMTC_ET	= $row_ET->VOCAMTC;
					endforeach;
					$TOT_ETB		= $VOCAMTR_ET - $VOCAMTC_ET;
					
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET BPP_ALAT_A = '$TOT_ETA', BPP_ALAT_B = '$TOT_ETB', BPP_ALAT_C = '$TOT_ETC'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
						
				// INDIRECT / LAIN LAIN
					$TOT_EOA	= 0;
					$TOT_EOB	= 0;
					$TOT_EOC	= 0;
					$VOCAMTR_EO	= 0;
					$VOCAMTC_EO	= 0;
					$sql_EO		= "SELECT SUM(CSTVOLM * CSTPUNT) AS TOT_EOA, SUM(VOCAMTR) AS VOCAMTR, SUM(VOCAMTC) AS VOCAMTC,
										SUM(APTCOST + (CSTVOLM * CSTPUNT)) AS TOT_EOC
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND CSTTYPE = 'O'";
					$res_EO 	= $this->db->query($sql_EO)->result();
					foreach($res_EO as $row_EO) :
						$TOT_EOA	= $row_EO->TOT_EOA;
						$TOT_EOC	= $row_EO->TOT_EOC;
						$VOCAMTR_EO	= $row_EO->VOCAMTR;
						$VOCAMTC_EO	= $row_EO->VOCAMTC;
					endforeach;
					$TOT_EOB		= $VOCAMTR_EO - $VOCAMTC_EO;
					
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET BPP_OTH_A = '$TOT_EOA', BPP_OTH_B = '$TOT_EOB', BPP_OTH_C = '$TOT_EOC'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
				
				// REIMBURSMENT / OVERHEAD
					$TOT_EIA	= 0;
					$TOT_EIB	= 0;
					$TOT_EIC	= 0;
					$VOCAMTR_EI	= 0;
					$VOCAMTC_EI	= 0;
					$sql_EI		= "SELECT SUM(CSTVOLM * CSTPUNT) AS TOT_EIA, SUM(VOCAMTR) AS VOCAMTR, SUM(VOCAMTC) AS VOCAMTC,
										SUM(APTCOST + (CSTVOLM * CSTPUNT)) AS TOT_EIC
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND CSTTYPE LIKE '%I%'";
					$rEI_EI 	= $this->db->query($sql_EI)->result();
					foreach($rEI_EI as $row_EI) :
						$TOT_EIA	= $row_EI->TOT_EIA;
						$TOT_EIC	= $row_EI->TOT_EIC;
						$VOCAMTR_EI	= $row_EI->VOCAMTR;
						$VOCAMTC_EI	= $row_EI->VOCAMTC;
					endforeach;
					$TOT_EIB		= $VOCAMTR_EI - $VOCAMTC_EI;
					
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET BPP_OVH_A = '$TOT_EIA', BPP_OVH_B = '$TOT_EIB', BPP_OVH_C = '$TOT_EIC'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
					
				// GAJI
					$TOT_EWA	= 0;
					$TOT_EWB	= 0;
					$TOT_EWC	= 0;
					$VOCAMTR_EW = 0;
					$VOCAMTC_EW	= 0;
					$sql_EW		= "SELECT SUM(CSTVOLM * CSTPUNT) AS TOT_EWA, SUM(VOCAMTR) AS VOCAMTR, SUM(VOCAMTC) AS VOCAMTC,
										SUM(APTCOST + (CSTVOLM * CSTPUNT)) AS TOT_EWC
									FROM $csTable
									WHERE PRJCODE = '$PRJCODE' AND ACCCODE IN (42411, 42611)";
					$res_EW 	= $this->db->query($sql_EW)->result();
					foreach($res_EW as $row_EW) :
						$TOT_EWA 	= $row_EW->TOT_EWA;
						$TOT_EWC 	= $row_EW->TOT_EWC;
						$VOCAMTR_EW = $row_EW->VOCAMTR;
						$VOCAMTC_EW	= $row_EW->VOCAMTC;
					endforeach;
					$TOT_EWB		= $VOCAMTR_EW - $VOCAMTC_EW;
					
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET BPP_GAJI_A = '$TOT_EWA', BPP_GAJI_B = '$TOT_EWB', BPP_GAJI_C = '$TOT_EWC'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
			endforeach;
	}
	elseif($TBL_NAME == 'BSHEET')
	{
		// PREPARE
			// IMPORT TTKHD ke bs_ttkhd_outp, bs_ttkhd_forpaid
			// IMPORT TTKDT ke bs_ttkdt_outp
			// IMPORT TRXBANK ke bs_trxbank, bs_trxbank_outp
			
		// PENGKOLEKSIAN DATA YANG BELUM DIBAYAR
			// A. KOLEKSI SEMUA VOCCODE DI bs_ttkhd_paid - SUDAH DIBAYAR SEBELUMNYA
				$noUa		= 0;
				$NVOCCODE	= '';
				$sqlqa 		= "SELECT VOCCODE AS ROP_VOCCODE FROM bs_ttkhd_paid";
				$resqa 		= $this->db->query($sqlqa)->result();			
				foreach($resqa as $rowa) :
					$noUa		= $noUa + 1;
					$VOCCODE 	= $rowa->ROP_VOCCODE;
					if($noUa == 1)
					{
						$NVOCCODE = $VOCCODE;
					}
					else if($noUa == 2)
					{
						$NVOCCODE = "'$NVOCCODE', '$VOCCODE'";
					}
					else if($noUa > 2)
					{
						$NVOCCODE = "$NVOCCODE, '$VOCCODE'";
					}
				endforeach;
		
			// B. DELETE SEMUA VOCCODE DI bs_ttkhd_outp YANG ADA DI bs_ttkhd_paid
				$sqlqb 		= "DELETE FROM bs_ttkhd_outp WHERE VOCCODE IN ($NVOCCODE)";
				$this->db->query($sqlqb);
		
			// C. DELETE SEMUA VOCCODE DI bs_ttkhd_outp YANG ADA DI bs_trxbank_outp - SUDAH TERBAYAR
				$sqlqc 		= "DELETE FROM bs_trxbank_outp WHERE VOCCODE IN ($NVOCCODE)";
				$this->db->query($sqlqc);
				
				$sqlqc1		= "DELETE FROM bs_ttkhd_outp WHERE VOCCODE IN (SELECT B.VOCCODE FROM bs_trxbank_outp B)";
				$this->db->query($sqlqc1);
				
				$sqlqc2		= "DELETE FROM bs_ttkhd_outp WHERE TRXCANC = 1";
				$this->db->query($sqlqc2);
		
			// D. FINALIZING - KOLEKSI SEMUA TTKCODE YANG MASIH OUTSTANDING
				$noUd		= 0;
				$NTTKCODE	= '';
				$sqlqd 		= "SELECT TTKCODE FROM bs_ttkhd_outp";
				$resqd		= $this->db->query($sqlqd)->result();			
				foreach($resqd as $rowd) :
					$noUd		= $noUd + 1;
					$TTKCODE 	= $rowd->TTKCODE;
					if($noUd == 1)
					{
						$NTTKCODE = $TTKCODE;
					}
					else if($noUd == 2)
					{
						$NTTKCODE = "'$NTTKCODE', '$TTKCODE'";
					}
					else if($noUd > 2)
					{
						$NTTKCODE = "$NTTKCODE, '$TTKCODE'";
					}
				endforeach;
		
			// E. DELETE SEMUA TTKCODE DI bs_ttkdt_outp YANG TIDAK ADA DI bs_ttkhd_outp
				$sqlqe 		= "DELETE FROM bs_ttkdt_outp WHERE TTKCODE NOT IN($NTTKCODE)";
				$this->db->query($sqlqe);
		
		// PENGKOLEKSIAN DATA YANG SUDAH DIBAYAR
			// F. DELETE SEMUA TTKCODE DI bs_ttkhd_forpaid YANG ADA DI bs_ttkhd_outp
				$sqlqf 		= "DELETE FROM bs_ttkhd_forpaid WHERE TTKCODE IN ($NTTKCODE)";
				$this->db->query($sqlqf);
				
				$sqlqf1		= "DELETE FROM bs_ttkhd_forpaid WHERE TRXCANC = 1";
				$this->db->query($sqlqf1);
				
			// G. FINALIZING - KOLEKSI SISA SEMUA TTKCODE SETELAH DIKURANGI DENGAN TTK OUTSTANDING		
				$sqlqg		= "TRUNCATE TABLE bs_ttkhd_paid";
				$this->db->query($sqlqg);
				
				$sqlqg1		= "INSERT INTO bs_ttkhd_paid SELECT * FROM bs_ttkhd_forpaid";
				$this->db->query($sqlqg1);
	}
	elseif($TBL_NAME == 'BSHEET1')
	{
		// PREPARE
			// 1. IMPORT BS_VOCHD_PAID, CONDITION => TRBPROC = 1, TRXCANC = 0
			// 2. IMPORT BS_TTKHD_REC, BS_TTKDT_REC
			// 3. IMPORT BS_TTKHD_OUTP DAN BS_TTKDT_OUTP
			// 3. IMPORT BS_TRXBANK_PAID
			
		// PENGKOLEKSIAN DATA YANG BELUM DIBAYAR
			// A. KOLEKSI SEMUA VOCCODE DI bs_ttkhd_paid - SUDAH DIBAYAR SEBELUMNYA
			//return false;
				$noUa		= 0;
				$NVOCCODE	= '';
				$sqlqa 		= "SELECT VOCCODE AS ROP_VOCCODE FROM bs_vochd_paid";
				$resqa 		= $this->db->query($sqlqa)->result();			
				foreach($resqa as $rowa) :
					$noUa		= $noUa + 1;
					$VOCCODE 	= $rowa->ROP_VOCCODE;
					if($noUa == 1)
					{
						$NVOCCODE = $VOCCODE;
					}
					else if($noUa == 2)
					{
						$NVOCCODE = "'$NVOCCODE', '$VOCCODE'";
					}
					else if($noUa > 2)
					{
						$NVOCCODE = "$NVOCCODE, '$VOCCODE'";
					}
				endforeach;
		
			// B. DELETE SEMUA VOCCODE DI bs_ttkhd_outp YANG ADA DI bs_ttkhd_paid
				$sqlqb 		= "DELETE FROM bs_ttkhd_outp WHERE VOCCODE IN ($NVOCCODE)";
				$this->db->query($sqlqb);
				
				$sqlqc2		= "DELETE FROM bs_ttkhd_outp WHERE TRXCANC = 1";
				$this->db->query($sqlqc2);
				
				// DELETE SEMUA DATA DI TRXBANK YANG TIDAK ADA HUBUNGAN DENGAN BS_VOCHD_PAID
				$sqlqc 		= "DELETE FROM bs_trxbank_paid WHERE VOCCODE NOT IN ($NVOCCODE)";
				$this->db->query($sqlqc);			
				
		
			// C. DELETE SEMUA VOCCODE DI bs_ttkhd_outp YANG ADA DI bs_trxbank_outp - SUDAH TERBAYAR
				//$sqlqc 		= "DELETE FROM bs_trxbank_outp WHERE VOCCODE IN ($NVOCCODE)";
				//$this->db->query($sqlqc);
				
				/*$noUx		= 0;
				$NVOCCODEx	= '';
				$sqlqx 		= "SELECT VOCCODE AS ROP_VOCCODE FROM bs_vochd_paid";
				$resqx 		= $this->db->query($sqlqx)->result();			
				foreach($resqx as $rowx) :
					$noUx		= $noUx + 1;
					$VOCCODEx 	= $rowx->ROP_VOCCODE;
					if($noUx == 1)
					{
						$NVOCCODEx = $VOCCODEx;
					}
					else if($noUx == 2)
					{
						$NVOCCODEx = "'$NVOCCODEx', '$VOCCODEx'";
					}
					else if($noUx > 2)
					{
						$NVOCCODEx = "$NVOCCODEx, '$VOCCODEx'";
					}
				endforeach;
				
				$sqlqc1		= "DELETE FROM bs_ttkhd_outp WHERE VOCCODE IN ($NVOCCODEx)";
				$this->db->query($sqlqc1);*/
		
			// D. FINALIZING - KOLEKSI SEMUA TTKCODE YANG MASIH OUTSTANDING
				$noUd		= 0;
				$NTTKCODE	= '';
				$sqlqd 		= "SELECT TTKCODE FROM bs_ttkhd_outp";
				$resqd		= $this->db->query($sqlqd)->result();			
				foreach($resqd as $rowd) :
					$noUd		= $noUd + 1;
					$TTKCODE 	= $rowd->TTKCODE;
					if($noUd == 1)
					{
						$NTTKCODE = $TTKCODE;
					}
					else if($noUd == 2)
					{
						$NTTKCODE = "'$NTTKCODE', '$TTKCODE'";
					}
					else if($noUd > 2)
					{
						$NTTKCODE = "$NTTKCODE, '$TTKCODE'";
					}
				endforeach;
		
			// E. DELETE SEMUA TTKCODE DI bs_ttkdt_outp YANG TIDAK ADA DI bs_ttkhd_outp
				$sqlqe 		= "DELETE FROM bs_ttkdt_outp WHERE TTKCODE NOT IN($NTTKCODE)";
				$this->db->query($sqlqe);
		
		// PENGKOLEKSIAN DATA YANG SUDAH DIBAYAR
			// F. DELETE SEMUA TTKCODE DI bs_ttkhd_forpaid YANG ADA DI bs_ttkhd_outp
				$sqlqf 		= "DELETE FROM bs_ttkhd_forpaid WHERE TTKCODE IN ($NTTKCODE)";
				$this->db->query($sqlqf);
				
				$sqlqf1		= "DELETE FROM bs_ttkhd_forpaid WHERE TRXCANC = 1";
				$this->db->query($sqlqf1);
				
			// G. FINALIZING - KOLEKSI SISA SEMUA TTKCODE SETELAH DIKURANGI DENGAN TTK OUTSTANDING		
				$sqlqg		= "TRUNCATE TABLE bs_ttkhd_paid";
				$this->db->query($sqlqg);
				
				$sqlqg1		= "INSERT INTO bs_ttkhd_paid SELECT * FROM bs_ttkhd_forpaid";
				$this->db->query($sqlqg1);
	}
	elseif($TBL_NAME == 'EMPLOYEE')
	{
		$sql1	= "SELECT DISTINCT EMPID FROM tbl_employee_new";
		$res1 	= $this->db->query($sql1)->result();
		foreach($res1 as $row1) :
			$EMPID	= $row1->EMPID;			
			$sqlUPDATE	= "UPDATE tbl_employee SET Employee_status = 1, Emp_Status = 1 WHERE Emp_ID = '$EMPID'";
			$this->db->query($sqlUPDATE);
		endforeach;	
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
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
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">	
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">

                	<form class="form-horizontal" name="frm" id="frm" method="post" action="">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Syncas for Table</label>
                          	<div class="col-sm-10">
                            <select name="TBL_NAME" id="TBL_NAME" class="form-control" style="max-width:120px" >
                                <option value="CSFL" <?php if($TBL_NAME == "CSFL") { ?> selected <?php } ?>>CASH FLOW REPORT</option>
                                <option value="DPHD" <?php if($TBL_NAME == "DPHD") { ?> selected <?php } ?>>DP</option>
                                <option value="DPHD_R" <?php if($TBL_NAME == "DPHD_R") { ?> selected <?php } ?>>DP REPORT</option>
                                <option value="LPMHD" <?php if($TBL_NAME == "LPMHD") { ?> selected <?php } ?>>LPM</option>
                                <option value="OPHD" <?php if($TBL_NAME == "OPHD") { ?> selected <?php } ?>>PO</option>
                                <option value="SPKHD" <?php if($TBL_NAME == "SPKHD") { ?> selected <?php } ?>>SPK</option>
                                <option value="SPPHD" <?php if($TBL_NAME == "SPPHD") { ?> selected <?php } ?>>SPP</option>
                                <option value="SPPHD_R" <?php if($TBL_NAME == "SPPHD_R") { ?> selected <?php } ?>>SPP REPORT</option>
                                <option value="VOCHD" <?php if($TBL_NAME == "VOCHD") { ?> selected <?php } ?>>VOUCHER</option>
                                <option value="VLKHD" <?php if($TBL_NAME == "VLKHD") { ?> selected <?php } ?>>VOUCHER LK</option>
                                <option value="VOC_PAID" <?php if($TBL_NAME == "VOC_PAID") { ?> selected <?php } ?>>VOUCHER PAYMENT</option>
                                <option value="VOC_OUT" <?php if($TBL_NAME == "VOC_OUT") { ?> selected <?php } ?>>VOUCHER OUTSTANDING</option>
                                <option value="PLOSS" <?php if($TBL_NAME == "PLOSS") { ?> selected <?php } ?>>PROFIT LOSS</option>
                                <option value="BSHEET1" <?php if($TBL_NAME == "BSHEET1") { ?> selected <?php } ?>>BALANCE SHEET</option>
                                <option value="SIHD" <?php if($TBL_NAME == "SIHD") { ?> selected <?php } ?>>SI</option>
                                <option value="EMPLOYEE" <?php if($TBL_NAME == "EMPLOYEE") { ?> selected <?php } ?>>EMPLOYEE</option>
                                <option value="ALL" <?php if($TBL_NAME == "ALL") { ?> selected <?php } ?>>ALL</option>
                            </select>
                          	</div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<input type="submit" name="submitSync" id="submitSync" class="btn btn-primary" value="Sync Table" onClick="return buttonApp1(1)" >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
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