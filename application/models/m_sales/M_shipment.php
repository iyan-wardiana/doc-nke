<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= M_shipment.php
 * Location		= -
*/

class M_shipment extends CI_Model
{
	function count_all_sn($PRJCODE) // G
	{
		$sql	= "tbl_sn_header A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_sn($PRJCODE) // G
	{
		$sql = "SELECT A.SN_NUM, A.SN_CODE, A.SN_TYPE, A.SN_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS,
				A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_TOTCOST, A.SN_TOTPPN, A.SN_RECEIVER, A.SN_NOTES,
				A.SN_CREATER, A.SN_STAT, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
				B.PRJCODE, B.PRJNAME
				FROM tbl_sn_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'
					ORDER BY A.SN_CODE ASC";		
		return $this->db->query($sql);
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_sn_header
					WHERE PRJCODE = '$PRJCODE'
						AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
						OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE'
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE'
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE'
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE'
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_sn_header
					WHERE PRJCODE = '$PRJCODE' AND SN_STAT IN (2,7)
						AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
						OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE' AND SN_STAT IN (2,7)
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE' AND SN_STAT IN (2,7)
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE' AND SN_STAT IN (2,7)
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_sn_header
						WHERE PRJCODE = '$PRJCODE' AND SN_STAT IN (2,7)
							AND (SN_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR SO_CODE LIKE '%$search%' ESCAPE '!' OR SN_NOTES LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_CUST() // G
	{
		$sql = "tbl_so_header A INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE WHERE A.SO_STAT = '3'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUST() // G
	{
		$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD, A.CUST_CODE, A.CUST_ADDRESS, A.SO_TOTCOST, 
					A.SO_TOTPPN, A.SO_NOTES,
					B.CUST_DESC
				FROM tbl_so_header A INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE WHERE A.SO_STAT = '3'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_item($PRJCODE) // G
	{
		$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1 AND ITM_VOLM > 0";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE) // G
	{
		$sql	= "SELECT * FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1 AND ITM_VOLM > 0";
		return $this->db->query($sql);
	}
	
	function add($AddSN) // G
	{
		$this->db->insert('tbl_sn_header', $AddSN);
	}
	
	function updateSNH($SN_NUM, $updSNH) // G
	{
		$this->db->where('SN_NUM', $SN_NUM);
		$this->db->update('tbl_sn_header', $updSNH);
	}
	
	function addSINVH($inSINVH) // G
	{
		$this->db->insert('tbl_sinv_header', $inSINVH);
	}
	
	function updateDet($SN_NUM, $SO_NUM, $PRJCODE, $SN_DATE) // G
	{
		$sqlSND			= "SELECT ITM_CODE, SN_PRICE FROM tbl_sn_detail WHERE SN_NUM = '$SN_NUM' AND PRJCODE = '$PRJCODE'";
		$resSND			= $this->db->query($sqlSND)->result();
		foreach($resSND as $rowSND) :
			$ITM_CODE 	= $rowSND->ITM_CODE;
			$SN_PRICE 	= $rowSND->SN_PRICE;

			// GET VOLUME TOTAL BY ITEM
				$sqlQRCD	= "SELECT SUM(QRC_VOLM) AS TOTSN_QRC FROM tbl_sn_detail_qrc
								WHERE SN_NUM = '$SN_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resQRCD	= $this->db->query($sqlQRCD)->result();
				foreach($resQRCD as $rowQRCD) :
					$TOTSN_QRC 	= $rowQRCD->TOTSN_QRC;
				endforeach;
				if($TOTSN_QRC == '')
					$TOTSN_QRC	= 0;

			// GET PRICE, DISC, AND TAX BY ITEM
				$SO_VOLM	= 0;
				$SO_PRICE	= 0;
				$SO_DISP	= 0;
				$AVG_DISC	= 0;
				$AVG_TAX	= 0;
				$TAXCODE1	= '';
				$sqlSOD		= "SELECT SO_VOLM, SO_PRICE, SO_DISP, SO_DISC, TAXCODE1, TAXPRICE1 FROM tbl_so_detail
								WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resSOD		= $this->db->query($sqlSOD)->result();
				foreach($resSOD as $rowSOD) :
					$SO_VOLM 	= $rowSOD->SO_VOLM;
					$SO_PRICE 	= $rowSOD->SO_PRICE;
					$SO_DISP 	= $rowSOD->SO_DISP;
					$SO_DISC 	= $rowSOD->SO_DISC;
					$TAXCODE1 	= $rowSOD->TAXCODE1;
					$TAXPRICE1 	= $rowSOD->TAXPRICE1;

					$AVG_DISC	= $SO_DISC / $SO_VOLM;
					$AVG_TAX	= $TAXPRICE1 / $SO_VOLM;
				endforeach;

			$SN_TOTAL	= $TOTSN_QRC * $SO_PRICE;
			$SN_DISC	= $TOTSN_QRC * $AVG_DISC;
			$SN_TAX		= $TOTSN_QRC * $AVG_TAX;

			// UPDATE SN VOL
				$sqlUSND = "UPDATE tbl_sn_detail SET SO_VOLM = $SO_VOLM, SN_VOLM = $TOTSN_QRC, SN_PRICE = $SO_PRICE, SO_PRICE = $SO_PRICE,
								SN_DISP = $SO_DISP, SN_DISC = $SN_DISC, SN_TOTAL = $SN_TOTAL, TAXCODE1 = '$TAXCODE1', TAXPRICE1 = $SN_TAX,
								SN_DATE = '$SN_DATE'
							WHERE SN_NUM = '$SN_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				//$this->db->query($sqlUSND);
		endforeach;
	}
	
	function get_sn_by_number($SN_NUM) // G 
	{
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_sn_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.SN_NUM = '$SN_NUM'";
		return $this->db->query($sql);
	}
	
	function updateSN($SN_NUM, $UpdSN) // G
	{
		$this->db->where('SN_NUM', $SN_NUM);
		$this->db->update('tbl_sn_header', $UpdSN);
	}
	
	function updateVolBud($SN_NUM, $PRJCODE, $ITM_CODE) // HOLD
	{
		$SN_VOLM 	= 0;
		$SN_PRICE 	= 0;
		$sqlGetPO	= "SELECT SN_VOLM, SN_PRICE, PROD_VOLM, PROD_PRICE
						FROM tbl_SN_detail
						WHERE SN_NUM = '$SN_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$SN_VOLM 		= $rowPO->SN_VOLM;
			$SN_PRICE 		= $rowPO->SN_PRICE;
			$SN_AMOUNT		= $SN_VOLM * $SN_PRICE;
			$PROD_VOLM 		= $rowPO->PROD_VOLM;
			$PROD_PRICE	 	= $rowPO->PROD_PRICE;
			$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
		endforeach;
		
		$REM_SOVOLM		= $SN_VOLM - $PROD_VOLM;
		$REM_SOAMOUNT	= $SN_AMOUNT - $PROD_AMOUNT;
	}
	
	function updREJECT($SN_NUM, $PRJCODE, $ITM_CODE) // HOLD
	{
		$SN_VOLM 	= 0;
		$SN_PRICE 	= 0;
		$sqlGetPO	= "SELECT SN_VOLM, SN_PRICE, PROD_PRICE
						FROM tbl_sn_detail
						WHERE SN_NUM = '$SN_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$SN_VOLM 		= $rowPO->SN_VOLM;
			$SN_PRICE 		= $rowPO->SN_PRICE;
			$SN_AMOUNT		= $SN_VOLM * $SN_PRICE;
			$PROD_VOLM 		= $rowPO->PROD_VOLM;
			$PROD_PRICE	 	= $rowPO->PROD_PRICE;
			$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
		endforeach;
			
		// Kembalikan di tabel Item
			$sqlSO	= "UPDATE tbl_item SET SN_VOLM = SN_VOLM - $SN_VOLM, SN_AMOUNT = SN_AMOUNT - $SN_AMOUNT
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlSO);
	}
	
	function deleteSNDetail($SN_NUM) // G
	{
		$this->db->where('SN_NUM', $SN_NUM);
		$this->db->delete('tbl_sn_detail');
	}
	
	function deleteSNDetailQRC($SN_NUM) // G
	{
		$this->db->where('SN_NUM', $SN_NUM);
		$this->db->delete('tbl_sn_detail_qrc');
	}
	
	function deleteSNQRCDetail($SN_NUM) // G
	{
		$this->db->where('SN_NUM', $SN_NUM);
		$this->db->delete('tbl_sn_detail_qrc');
	}
	
	function count_all_sn_inb($PRJCODE) // G
	{
		$sql	= "tbl_sn_header A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SN_STAT IN (2,7)";
		return $this->db->count_all($sql);
	}
	
	function get_all_sn_inb($PRJCODE) // G
	{
		$sql = "SELECT A.SN_NUM, A.SN_CODE, A.SN_TYPE, A.SN_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS,
				A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_TOTCOST, A.SN_TOTPPN, A.SN_RECEIVER, A.SN_NOTES,
				A.SN_CREATER, A.SN_STAT, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
				B.PRJCODE, B.PRJNAME
				FROM tbl_sn_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.SN_STAT IN (2,7)
					ORDER BY A.SN_CODE ASC";		
		return $this->db->query($sql);
	}
	
	function updateSNInb($SN_NUM, $updSN) // G
	{
		$this->db->where('SN_NUM', $SN_NUM);
		$this->db->update('tbl_sn_header', $updSN);
	}
	
	function updateSNDet($SN_NUM, $PRJCODE) // G
	{
		$SN_TOTVOLM	= 0;
		$SN_TOTCOST	= 0;
		$SN_TOTDISC	= 0;
		$SN_TOTPPN	= 0;
		$SN_TOTPPH	= 0;
		$sqlGetSN	= "SELECT A.SN_NUM, A.ITM_CODE, A.SN_VOLM, A.SN_PRICE, A.SN_DISC, A.SN_TOTAL, A.TAXPRICE1, A.TAXPRICE2,
							B.SO_NUM
						FROM tbl_sn_detail A
							INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
						WHERE A.SN_NUM = '$SN_NUM' AND A.PRJCODE = '$PRJCODE'";
		$resGetSN	= $this->db->query($sqlGetSN)->result();
		foreach($resGetSN as $rowSN) :
			$SN_NUM 		= $rowSN->SN_NUM;
			$SO_NUM 		= $rowSN->SO_NUM;
			$ITM_CODE 		= $rowSN->ITM_CODE;
			$SN_VOLM		= $rowSN->SN_VOLM;
			$SN_PRICE		= $rowSN->SN_PRICE;
			$SN_DISC		= $rowSN->SN_DISC;
			$SN_TOTAL		= $rowSN->SN_TOTAL;
			$TAXPRICE1		= $rowSN->TAXPRICE1;
			$TAXPRICE2		= $rowSN->TAXPRICE2;
			
			$SN_VOLM_NOW	= $rowSN->SN_VOLM;
			$SN_PRICE_NOW	= $rowSN->SN_PRICE;
			$SN_COST_NOW	= $SN_VOLM_NOW * $SN_PRICE_NOW;


			$SN_TOTVOLM		= $SN_TOTVOLM + $SN_VOLM_NOW;
			$SN_TOTCOST		= $SN_TOTCOST + $SN_COST_NOW;
			$SN_TOTDISC		= $SN_TOTDISC + $SN_DISC;
			$SN_TOTPPN		= $SN_TOTPPN + $TAXPRICE1;
			$SN_TOTPPH		= $SN_TOTPPH + $TAXPRICE2;
			
			// UPDATE SO DETAIL
				/*$SN_VOLMA	= 0;
				$SN_AMOUNTA	= 0;			
				$sqlGetSO	= "SELECT SN_VOLM, SN_PRICE, SN_AMOUNT FROM tbl_so_detail
								WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$resGetSO	= $this->db->query($sqlGetSO)->result();
				foreach($resGetSO as $rowSO) :
					$SN_VOLMA 	= $rowSO->SN_VOLM;
					$SN_AMOUNTA	= $rowSO->SN_AMOUNT;
				endforeach;
				if($SN_VOLMA == '')
					$SN_VOLMA = 0;
				if($SN_AMOUNTA == '')
					$SN_AMOUNTA = 0;
				
				$totSNQtyA		= $SN_VOLMA + $SN_VOLM_NOW;
				$totSNAmountA	= $SN_AMOUNTA + $SN_COST_NOW;*/

				$sqlUpd			= "UPDATE tbl_so_detail SET SN_VOLM = SN_VOLM + $SN_VOLM_NOW, SN_PRICE = $SN_PRICE,
										SN_AMOUNT = SN_AMOUNT + $SN_COST_NOW
									WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpd);
				
			// UPDATE TBL_ITEM
				/*$SN_VOLMB		= 0;
				$SN_AMOUNTB		= 0;
				$sqlGetITM		= "SELECT SN_VOLM, SN_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resGetITM		= $this->db->query($sqlGetITM)->result();
				foreach($resGetITM as $rowITM) :
					$SN_VOLMB 	= $rowITM->SN_VOLM;
					$SN_AMOUNTB	= $rowITM->SN_AMOUNT;
				endforeach;
				if($SN_VOLMB == '')
					$SN_VOLMB = 0;
				if($SN_AMOUNTB == '')
					$SN_AMOUNTB = 0;
				
				$totSNQtyB		= $SN_VOLMB + $SN_VOLM_NOW;
				$totSNAmountB	= $SN_AMOUNTB + $SN_COST_NOW;*/

				$sqlUpd2		= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $SN_VOLM_NOW, SN_VOLM = SN_VOLM + $SN_VOLM_NOW,
										SN_AMOUNT = SN_AMOUNT + $SN_COST_NOW,
										ITM_OUT = ITM_OUT + $SN_VOLM_NOW, ITM_OUTP = ITM_OUTP + $SN_COST_NOW
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpd2);
		endforeach;

		// UPDATE SN HEADER
			$sqlUpdSNH			= "UPDATE tbl_sn_header SET SN_TOTVOLM = $SN_TOTVOLM, SN_TOTCOST = $SN_TOTCOST,
										SN_TOTDISC = $SN_TOTDISC, SN_TOTPPN = $SN_TOTPPN, SN_TOTPPH = $SN_TOTPPH
									WHERE PRJCODE = '$PRJCODE' AND SN_NUM = '$SN_NUM'";
			$this->db->query($sqlUpdSNH);
	}
	
	function updQRCSTAT($SN_NUM) // G
	{
		$sqlQRCSN 	= "SELECT QRC_NUM, SN_NUM, SN_CODE FROM tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM'";
		$resQRCSN	= $this->db->query($sqlQRCSN)->result();
		foreach($resQRCSN as $rowQRCSN) :
			$QRC_NUM 	= $rowQRCSN->QRC_NUM;
			$SN_NUM 	= $rowQRCSN->SN_NUM;
			$SN_CODE 	= $rowQRCSN->SN_CODE;
			// UPDATE
				$updQRC	= "UPDATE tbl_qrc_detail SET QRC_STATS = 1, SN_NUM = '$SN_NUM', SN_CODE = '$SN_CODE' WHERE QRC_NUM = '$QRC_NUM'";
				$this->db->query($updQRC);

				$updGRP	= "UPDATE tbl_item_colld SET QRC_STATS = 1, SN_NUM = '$SN_NUM', SN_CODE = '$SN_CODE' WHERE QRC_NUM = '$QRC_NUM'";
				$this->db->query($updGRP);		
		endforeach;
	}
	
	function updSOSTAT($SO_NUM) // G
	{
		$sqlSO 	= "SELECT SO_VOLM, SN_VOLM FROM tbl_so_detail WHERE SO_NUM = '$SO_NUM'";
		$resSO	= $this->db->query($sqlSO)->result();
		foreach($resSO as $rowSO) :
			$SO_VOLM 	= $rowSO->SO_VOLM;
			$SN_VOLM 	= $rowSO->SN_VOLM;

			if($SN_VOLM >= $SO_VOLM)
			{
				$upSO	= "UPDATE tbl_so_header SET SO_STAT = 6, ISCLOSE = 1, STATDESC = 'Close', STATCOL = 'info' WHERE SO_NUM = '$SO_NUM'";
				$this->db->query($upSO);
			}
		endforeach;
	}
	
	function count_all_CUSTSN($PRJCODE) // G
	{
		//$sql = "tbl_so_header A INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE WHERE A.SO_STAT IN (3,6) AND SN_STAT IN ('NS','HS')";
		$sql = "tbl_so_detail X
					INNER JOIN tbl_so_header A ON A.SO_NUM = X.SO_NUM
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE
					X.SN_VOLM <= X.PROD_VOLM AND X.PROD_VOLM > '0' AND X.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUSTSN($PRJCODE) // G
	{
		$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD, A.CUST_CODE, A.CUST_ADDRESS, A.SO_TOTCOST, 
					A.SO_TOTPPN, A.SO_NOTES,
					B.CUST_DESC
				FROM tbl_so_detail X
					INNER JOIN tbl_so_header A ON A.SO_NUM = X.SO_NUM
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE
					X.SN_VOLM <= X.PROD_VOLM AND X.PROD_VOLM > 0 AND X.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_CUSTSNDIR($PRJCODE) // G
	{
		//$sql = "tbl_so_header A INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE WHERE A.SO_STAT IN (3,6) AND SN_STAT IN ('NS','HS')";
		$sql = "tbl_so_detail X
					INNER JOIN tbl_so_header A ON A.SO_NUM = X.SO_NUM
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE
					 X.PRJCODE = '$PRJCODE' AND SO_STAT = '3' AND A.SO_CAT = '2'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUSTSNDIR($PRJCODE) // G
	{
		$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD, A.CUST_CODE, A.CUST_ADDRESS, A.SO_TOTCOST, 
					A.SO_TOTPPN, A.SO_NOTES,
					B.CUST_DESC
				FROM tbl_so_detail X
					INNER JOIN tbl_so_header A ON A.SO_NUM = X.SO_NUM
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE
					X.PRJCODE = '$PRJCODE' AND SO_STAT = 3 AND A.SO_CAT = '2'";
		return $this->db->query($sql);
	}
}
?>