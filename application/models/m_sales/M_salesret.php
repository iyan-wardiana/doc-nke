<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 22 Oktober 2018
 * File Name	= M_salesret.php
 * Location		= -
*/

class M_salesret extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_sr_header A 
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
					OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_CUST($PRJCODE) // GOOD
	{
		$sql = "tbl_customer A 
					INNER JOIN tbl_sn_header B ON A.CUST_CODE = B.CUST_CODE
						AND B.SN_STAT IN (3,6) AND B.PRJCODE = '$PRJCODE'
				WHERE A.CUST_STAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUST($PRJCODE) // GOOD
	{
		$sql = "SELECT DISTINCT A.CUST_CODE, A.CUST_DESC, A.CUST_ADD1
				FROM tbl_customer A 
					INNER JOIN tbl_sn_header B ON A.CUST_CODE = B.CUST_CODE
						AND B.SN_STAT IN (3,6) AND B.PRJCODE = '$PRJCODE'
				WHERE A.CUST_STAT = '1'";
		return $this->db->query($sql);
	}
	
	function count_all_item($PRJCODE, $CUST_CODE, $SN_NUM) // GOOD
	{
		$sql	= "tbl_sn_detail A 
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SN_NUM = '$SN_NUM'";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE, $CUST_CODE, $SN_NUM) // GOOD
	{
		$sql	= "SELECT A.ID, A.ITM_CODE, B.ITM_NAME, B.ITM_DESC, A.ITM_UNIT, SUM(A.SN_VOLM) AS SN_VOLM, A.SN_PRICE, A.SN_DISC,
						SUM(A.SN_TOTAL) AS SN_TOTAL, SUM(A.TAXCODE1) AS TAXCODE1 
					FROM tbl_sn_detail A 
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SN_NUM = '$SN_NUM'
					GROUP BY A.ITM_CODE, A.SN_PRICE";
		return $this->db->query($sql);
	}
	
	function add($AddSR) // GOOD
	{
		$this->db->insert('tbl_sr_header', $AddSR);
	}
	
	function updateSRH($SR_NUM, $updSRH) // GOOD
	{
		$this->db->where('SR_NUM', $SR_NUM);
		$this->db->update('tbl_sr_header', $updSRH);
	}
	
	function get_sr_by_number($SR_NUM) // GOOD
	{
		$sql = "SELECT A.*
				FROM tbl_sr_header A
				WHERE A.SR_NUM = '$SR_NUM'";
		return $this->db->query($sql);
	}
	
	function updateDet($SR_NUM, $SR_CODE, $SR_DATE, $PRJCODE) // GOOD
	{
		$sql = "UPDATE tbl_sr_detail SET SR_CODE = '$SR_CODE', SR_DATE = '$SR_DATE', PRJCODE = '$PRJCODE' WHERE SR_NUM = '$SR_NUM'";
		return $this->db->query($sql);
	}
	
	function deleteSRDetail($SR_NUM) // GOOD
	{
		$this->db->where('SR_NUM', $SR_NUM);
		$this->db->delete('tbl_sr_detail');
		
		$this->db->where('SR_NUM', $SR_NUM);
		$this->db->delete('tbl_sr_detail_qrc');
	}
	
	function chkSN($SR_NUM, $SN_NUM, $PRJCODE) // GOOD
	{
		// CEK SN IN RETURN DOKUMEN
			$sqlSN	= "tbl_sr_header A WHERE A.PRJCODE = '$PRJCODE' AND A.SN_NUM = '$SN_NUM' AND A.SR_STAT IN (3,6)";
			$resSN	= $this->db->count_all($sqlSN);
			
			if($resSN == 0)
			{
				$sqlSN	= "UPDATE tbl_sn_header SET ISRETURN = 0 WHERE PRJCODE = '$PRJCODE' AND SN_NUM = '$SN_NUM'";
				$this->db->query($sqlSN);
			}
			else
			{
				$sqlSN	= "UPDATE tbl_sn_header SET ISRETURN = 1 WHERE PRJCODE = '$PRJCODE' AND SN_NUM = '$SN_NUM'";
				$this->db->query($sqlSN);
			}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_sr_header A 
				WHERE A.PRJCODE = '$PRJCODE' AND A.SR_STAT IN (2,7)
					AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
					OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.SR_STAT IN (2,7)
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.SR_STAT IN (2,7)
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.SR_STAT IN (2,7)
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.SR_NUM, A.SR_CODE, A.SR_TYPE, A.SR_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_DESC, A.CUST_ADD,
							A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.SR_TOTCOST, A.SR_TOTPPN, A.SR_TOTDISC,
							A.SR_NOTES, A.SR_STAT, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_sr_header A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.SR_STAT IN (2,7)
							AND (A.SR_CODE LIKE '%$search%' ESCAPE '!' OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.SO_CODE LIKE '%$search%' ESCAPE '!' OR A.SN_CODE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function updateSNDET($PRJCODE, $SN_NUM, $ITM_CODE, $SR_VOLM, $SR_PRICE, $SR_TOTAL) // GOOD
	{
		$sql = "UPDATE tbl_sn_detail SET SR_VOLM = SR_VOLM + $SR_VOLM, SR_PRICE = $SR_PRICE, SR_TOTAL = SR_TOTAL + $SR_TOTAL
				WHERE PRJCODE = '$PRJCODE' AND SN_NUM = '$SN_NUM' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);

		$SO_NUM 	= "";
		$sqlGetSN	= "SELECT B.SO_NUM FROM tbl_sn_detail A
							INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
						WHERE A.SN_NUM = '$SN_NUM' AND A.PRJCODE = '$PRJCODE'";
		$resGetSN	= $this->db->query($sqlGetSN)->result();
		foreach($resGetSN as $rowSN) :
			$SO_NUM = $rowSN->SO_NUM;
		endforeach;

		$sqlUpd	= "UPDATE tbl_so_detail SET SN_VOLM = SN_VOLM - $SR_VOLM, SN_PRICE = $SR_PRICE, SN_AMOUNT = SN_AMOUNT - $SR_TOTAL
					WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUpd);

		$sqlUpd2	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $SR_VOLM, SN_VOLM = SN_VOLM - $SR_VOLM, SN_AMOUNT = SN_AMOUNT - $SR_TOTAL,
							ITM_OUT = ITM_OUT - $SR_VOLM, ITM_OUTP = ITM_OUTP + $SR_TOTAL
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpd2);
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
}
?>