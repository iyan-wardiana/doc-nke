<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 28 November 2018
 * File Name	= M_offering.php
 * Location		= -
*/

class M_offering extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_offering_h A 
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
					OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS, A.CCAL_CODE, A.BOM_CODE,
							A.OFF_TOTCOST, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_offering_h A 
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS, A.CCAL_CODE, A.BOM_CODE,
							A.OFF_TOTCOST, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_offering_h A 
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS, A.CCAL_CODE, A.BOM_CODE,
							A.OFF_TOTCOST, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_offering_h A 
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS, A.CCAL_CODE, A.BOM_CODE,
							A.OFF_TOTCOST, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_offering_h A 
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_offering_h A  
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT IN (2,7)
					AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
					OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS, A.CCAL_CODE, A.BOM_CODE,
							A.OFF_TOTCOST, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
					FROM tbl_offering_h A   
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT IN (2,7)
						AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
						OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.PRJCODE, A.CUST_CODE, A.CUST_ADDRESS, A.CCAL_CODE, A.BOM_CODE,
							A.OFF_TOTCOST, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
					FROM tbl_offering_h A   
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT IN (2,7)
						AND (A.OFF_CODE LIKE '%$search%' ESCAPE '!' OR B.CUST_DESC LIKE '%$search%' ESCAPE '!' 
						OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function count_all_off($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.OFF_NUM LIKE '%$key%' ESCAPE '!' OR A.OFF_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OFF_DATE LIKE '%$key%' ESCAPE '!' OR A.OFF_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_off($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.CUST_CODE, A.BOM_CODE, A.SO_NUM, A.OFF_TOTCOST, A.OFF_TOTPPN, 
						A.OFF_SOSTAT, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_MEMO, A.OFF_STAT, A.OFF_CREATER, 
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PRJCODE,
						B.CUST_DESC
					FROM tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.CUST_CODE, A.BOM_CODE, A.SO_NUM, A.OFF_TOTCOST, A.OFF_TOTPPN, 
						A.OFF_SOSTAT, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_MEMO, A.OFF_STAT, A.OFF_CREATER, 
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PRJCODE,
						B.CUST_DESC
					FROM tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.OFF_NUM LIKE '%$key%' ESCAPE '!' OR A.OFF_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OFF_DATE LIKE '%$key%' ESCAPE '!' OR A.OFF_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function count_all_CUST() // GOOD
	{
		$sql = "tbl_ccal_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						AND B.CUST_STAT = '1'
				WHERE A.CCAL_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUST() // GOOD
	{
		$sql = "SELECT DISTINCT A.CUST_CODE, B.CUST_DESC
				FROM tbl_ccal_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						AND B.CUST_STAT = '1'
				WHERE A.CCAL_STAT = 3";
		return $this->db->query($sql);
	}
	
	function count_all_CUSTUPD($OFF_NUM) // GOOD
	{
		$sql = "tbl_ccal_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						AND B.CUST_STAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUSTUPD($OFF_NUM) // GOOD
	{
		$sql = "SELECT DISTINCT A.CUST_CODE, B.CUST_DESC
				FROM tbl_ccal_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						AND B.CUST_STAT = '1'
				WHERE A.CCAL_STAT = 3
				UNION ALL
				SELECT DISTINCT A.CUST_CODE, B.CUST_DESC
				FROM tbl_offering_h A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						AND B.CUST_STAT = '1'
				WHERE A.OFF_NUM = '$OFF_NUM'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($AddOFF) // GOOD
	{
		$this->db->insert('tbl_offering_h', $AddOFF);
	}
	
	function updateOFFH($OFF_NUM, $updOFFH) // GOOD
	{
		$this->db->where('OFF_NUM', $OFF_NUM);
		$this->db->update('tbl_offering_h', $updOFFH);
	}
	
	function updateOFF($OFF_NUM, $UppOFF) // GOOD
	{
		$this->db->where('OFF_NUM', $OFF_NUM);
		$this->db->update('tbl_offering_h', $UppOFF);
	}
	
	function get_off_by_number($OFF_NUM) // GOOD
	{			
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_offering_h A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.OFF_NUM = '$OFF_NUM'";
		return $this->db->query($sql);
	}
	
	function updateOFFDET($OFF_NUM, $UppOFFD) // GOOD
	{
		$this->db->where('OFF_NUM', $OFF_NUM);
		$this->db->update('tbl_offering_d', $UppOFFD);
	}
	
	function deleteOFFDetail($OFF_NUM) // GOOD
	{
		$this->db->where('OFF_NUM', $OFF_NUM);
		$this->db->delete('tbl_offering_d');
	}
	
	function updateDet($OFF_NUM, $PRJCODE, $OFF_DATE) // GOOD
	{
		$sql = "UPDATE tbl_offering_d SET PRJCODE = '$PRJCODE', OFF_DATE = '$OFF_DATE' WHERE OFF_NUM = '$OFF_NUM'";
		return $this->db->query($sql);
	}
	
	function count_all_offinb($PRJCODE, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT IN (2,7)
						AND (A.OFF_NUM LIKE '%$key%' ESCAPE '!' OR A.OFF_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OFF_DATE LIKE '%$key%' ESCAPE '!' OR A.OFF_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_offinb($PRJCODE, $start, $end, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.CUST_CODE, A.BOM_CODE, A.SO_NUM, A.OFF_TOTCOST, A.OFF_TOTPPN, 
						A.OFF_SOSTAT, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_MEMO, A.OFF_STAT, A.OFF_CREATER, 
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PRJCODE,
						B.CUST_DESC
					FROM tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT IN (2,7)";
		}
		else
		{
			$sql = "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.CUST_CODE, A.BOM_CODE, A.SO_NUM, A.OFF_TOTCOST, A.OFF_TOTPPN, 
						A.OFF_SOSTAT, A.OFF_TOTDISC, A.OFF_NOTES, A.OFF_MEMO, A.OFF_STAT, A.OFF_CREATER, 
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PRJCODE,
						B.CUST_DESC
					FROM tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT IN (2,7)
						AND (A.OFF_NUM LIKE '%$key%' ESCAPE '!' OR A.OFF_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OFF_DATE LIKE '%$key%' ESCAPE '!' OR A.OFF_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}	
		return $this->db->query($sql);
	}
	
	function updateOFFInb($OFF_NUM, $UppOFF) // GOOD
	{
		$this->db->where('OFF_NUM', $OFF_NUM);
		$this->db->update('tbl_offering_h', $UppOFF);
	}
	
	function updateVolBud($OFF_NUM, $PRJCODE, $ITM_CODE) // HOLD
	{
		$OFF_VOLM 	= 0;
		$OFF_PRICE 	= 0;
		$sqlGetPO	= "SELECT OFF_VOLM, OFF_PRICE, PROD_VOLM, PROD_PRICE
						FROM tbl_offering_d
						WHERE OFF_NUM = '$OFF_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$OFF_VOLM 		= $rowPO->OFF_VOLM;
			$OFF_PRICE 		= $rowPO->OFF_PRICE;
			$OFF_AMOUNT		= $OFF_VOLM * $OFF_PRICE;
			$PROD_VOLM 		= $rowPO->PROD_VOLM;
			$PROD_PRICE	 	= $rowPO->PROD_PRICE;
			$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
		endforeach;
		
		$REM_SOVOLM		= $OFF_VOLM - $PROD_VOLM;
		$REM_SOAMOUNT	= $OFF_AMOUNT - $PROD_AMOUNT;
	}
	
	function updREJECT($OFF_NUM, $PRJCODE, $ITM_CODE) // HOLD
	{
		$OFF_VOLM 	= 0;
		$OFF_PRICE 	= 0;
		$sqlGetPO	= "SELECT OFF_VOLM, OFF_PRICE, PROD_VOLM, PROD_PRICE
						FROM tbl_offering_d
						WHERE OFF_NUM = '$OFF_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$OFF_VOLM 		= $rowPO->OFF_VOLM;
			$OFF_PRICE 		= $rowPO->OFF_PRICE;
			$OFF_AMOUNT		= $OFF_VOLM * $OFF_PRICE;
			$PROD_VOLM 		= $rowPO->PROD_VOLM;
			$PROD_PRICE	 	= $rowPO->PROD_PRICE;
			$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
		endforeach;
			
		// Kembalikan di tabel Item
			$sqlSO	= "UPDATE tbl_item SET OFF_VOLM = OFF_VOLM - $OFF_VOLM, OFF_AMOUNT = OFF_AMOUNT - $OFF_AMOUNT
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlSO);
	}
}
?>