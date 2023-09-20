<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= M_so.php
 * Location		= -
*/

class M_so extends CI_Model
{
	function count_all_SO($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.SO_NUM LIKE '%$key%' ESCAPE '!' OR A.SO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.SO_DATE LIKE '%$key%' ESCAPE '!' OR A.SO_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_SO($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
						A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST,
						A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, A.SO_INVSTAT, A.ISDIRECT,
						A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.CUST_DESC
					FROM tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
						A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST,
						A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, A.SO_INVSTAT, A.ISDIRECT,
						A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.CUST_DESC
					FROM tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.SO_NUM LIKE '%$key%' ESCAPE '!' OR A.SO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.SO_DATE LIKE '%$key%' ESCAPE '!' OR A.SO_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_so_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
					OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
					OR B.CUST_DESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') ";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_so_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)
					AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
					OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
					OR B.CUST_DESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') ";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
							A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST, A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, 
							A.SO_INVSTAT, A.ISDIRECT, A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.STATDESC, A.STATCOL, A.CREATERNM,
							B.CUST_DESC
						FROM tbl_so_header A
							INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)
							AND (A.SO_NUM LIKE '%$search%' ESCAPE '!' OR A.SO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.SO_DATE LIKE '%$search%' ESCAPE '!' OR A.SO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR B.CUST_DESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_CUST($PRJCODE, $CUST_CODE) // GOOD
	{
		$sql 	= "tbl_offering_h A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT = 3 AND A.ISCLOSE = 0";
		//$sql 	= "tbl_customer";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUST($PRJCODE, $CUST_CODE) // GOOD
	{
		$sql = "SELECT DISTINCT B.CUST_CODE, B.CUST_DESC, B.CUST_ADD1
				FROM tbl_offering_h A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT = 3 AND A.ISCLOSE = 0";
		//$sql = "SELECT * FROM tbl_customer";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_itmOFF($PRJCODE) // GOOD
	{
		$sql	= "tbl_offering_d A 
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function get_all_itmOFF($PRJCODE) // GOOD
	{
		$sql	= "SELECT A.OFF_NUM, A.OFF_CODE, A.ITM_CODE, A.ITM_UNIT, B.ITM_NAME, A.OFF_VOLM, A.OFF_PRICE, A.OFF_COST, 
						A.OFF_DISC, A.OFF_DISCP, A.TAXCODE1, A.TAXPRICE1, A.OFF_TOTCOST, B.ITM_VOLM, A.PRJCODE
					FROM tbl_offering_d A 
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND A.OFF_STAT = 3";
		return $this->db->query($sql);
	}
	
	function count_all_itm($PRJCODE) // GOOD
	{
		$sql	= "tbl_item B
					WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND A.STATUS = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_all_itm($PRJCODE) // GOOD
	{
		$sql	= "SELECT A.* FROM tbl_item B
					WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND A.STATUS = '1'";
		return $this->db->query($sql);
	}
	
	function get_so_by_number($SO_NUM) // GOOD
	{			
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_so_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.SO_NUM = '$SO_NUM'";
		return $this->db->query($sql);
	}
	
	function get_Rate($SO_CURR) // GOOD
	{
		$RATE		= 1;
		$sqlRate 	= "SELECT RATE FROM tbl_currate WHERE CURR1 = '$SO_CURR' AND CURR2 = 'IDR'";
		$resRate	= $this->db->query($sqlRate)->result();
		foreach($resRate as $rowRate) :
			$RATE = $rowRate->RATE;		
		endforeach;
		return $RATE;
	}
	
	function updateSO($SO_NUM, $updSO) // GOOD
	{
		$this->db->where('SO_NUM', $SO_NUM);
		$this->db->update('tbl_so_header', $updSO);
	}
	
	function updVOID($SO_NUM, $PRJCODE, $ITM_CODE) // GOOD
	{
		$SO_VOLM 	= 0;
		$SO_PRICE 	= 0;
		$sqlGetPO	= "SELECT SO_VOLM, SO_PRICE, PROD_VOLM, PROD_PRICE
						FROM tbl_so_detail
						WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$SO_VOLM 		= $rowPO->SO_VOLM;
			$SO_PRICE 		= $rowPO->SO_PRICE;
			$SO_AMOUNT		= $SO_VOLM * $SO_PRICE;
			$PROD_VOLM 		= $rowPO->PROD_VOLM;
			$PROD_PRICE	 	= $rowPO->PROD_PRICE;
			$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
		endforeach;
			
		// Kembalikan di tabel Item
			$sqlSO	= "UPDATE tbl_item SET SO_VOLM = SO_VOLM - $SO_VOLM, SO_AMOUNT = SO_AMOUNT - $SO_AMOUNT
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlSO);
	}
	
	function deleteSODetail($SO_NUM) // GOOD
	{
		$this->db->where('SO_NUM', $SO_NUM);
		$this->db->delete('tbl_so_detail');
	}
	
	function updateSOH($SO_NUM, $updSOH) // GOOD
	{
		$this->db->where('SO_NUM', $SO_NUM);
		$this->db->update('tbl_so_header', $updSOH);
	}
	
	function updateDet($SO_NUM, $PRJCODE, $SO_DATE) // GOOD
	{
		$sql = "UPDATE tbl_so_detail SET PRJCODE = '$PRJCODE', SO_DATE = '$SO_DATE' WHERE SO_NUM = '$SO_NUM'";
		return $this->db->query($sql);
	}
	
	function count_all_soinb($PRJCODE, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)
						AND (A.SO_NUM LIKE '%$key%' ESCAPE '!' OR A.SO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.SO_DATE LIKE '%$key%' ESCAPE '!' OR A.SO_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_soinb($PRJCODE, $start, $end, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
						A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST,
						A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, A.SO_INVSTAT, A.ISDIRECT,
						A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.CUST_DESC
					FROM tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.SO_NUM, A.SO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_PRODD, A.PRJCODE, A.CUST_CODE,
						A.OFF_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_TOTCOST,
						A.SO_CREATER, A.SO_TERM, A.SO_PAYTYPE, A.SO_STAT, A.SO_INVSTAT, A.ISDIRECT,
						A.SO_NOTES, A.SO_MEMO, A.JOBCODE, A.SO_PRODD, A.SO_REFRENS,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.CUST_DESC
					FROM tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT IN (2,7)
						AND (A.SO_NUM LIKE '%$key%' ESCAPE '!' OR A.SO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.SO_DATE LIKE '%$key%' ESCAPE '!' OR A.SO_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.CUST_DESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function count_all_item($PRJCODE) // GOOD
	{
		$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE) // GOOD
	{
		$sql	= "SELECT * FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1";
		return $this->db->query($sql);
	}
	
	function add($AddSO) // GOOD
	{
		$this->db->insert('tbl_so_header', $AddSO);
	}
	
	function updateVolBud($SO_NUM, $PRJCODE, $ITM_CODE) // HOLD
	{
		$SO_VOLM 	= 0;
		$SO_PRICE 	= 0;
		$sqlGetPO	= "SELECT SO_VOLM, SO_PRICE, PROD_VOLM, PROD_PRICE
						FROM tbl_so_detail
						WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$SO_VOLM 		= $rowPO->SO_VOLM;
			$SO_PRICE 		= $rowPO->SO_PRICE;
			$SO_AMOUNT		= $SO_VOLM * $SO_PRICE;
			$PROD_VOLM 		= $rowPO->PROD_VOLM;
			$PROD_PRICE	 	= $rowPO->PROD_PRICE;
			$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
		endforeach;
		
		$REM_SOVOLM		= $SO_VOLM - $PROD_VOLM;
		$REM_SOAMOUNT	= $SO_AMOUNT - $PROD_AMOUNT;
	}
	
	function updateSOInb($SO_NUM, $updSO) // GOOD
	{
		$this->db->where('SO_NUM', $SO_NUM);
		$this->db->update('tbl_so_header', $updSO);
	}
	
	function updateSODet($SO_NUM, $PRJCODE) // GOOD
	{				
		$sqlGetSO	= "SELECT SO_NUM, ITM_CODE, SO_VOLM, SO_PRICE, SO_COST
						FROM tbl_so_detail
						WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetSO	= $this->db->query($sqlGetSO)->result();
		foreach($resGetSO as $rowSO) :
			$SO_NUM 		= $rowSO->SO_NUM;
			$ITM_CODE		= $rowSO->ITM_CODE;
			$SO_VOLM_NOW	= $rowSO->SO_VOLM;
			$SO_PRICE_NOW	= $rowSO->SO_PRICE;
			$SO_COST_NOW	= $rowSO->SO_COST;
			
			$sqlUpd2		= "UPDATE tbl_item SET SO_VOLM = SO_VOLM + $SO_VOLM_NOW, SO_AMOUNT = SO_AMOUNT + $SO_COST_NOW
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
	}
	
	function updateOFFDet($OFF_NUM, $PRJCODE) // GOOD
	{				
		$sqlGetSO	= "UPDATE tbl_offering_h SET ISCLOSE = 1 WHERE OFF_NUM = '$OFF_NUM' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlGetSO);
	}
	
	function get_AllDataITMC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A
				WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";

		// DIAMBIL SECARA LANGSUNG DARI TABEL MATERIAL -- DIBATALKAN. KEMBALI KE AWAL
		/*$sql 	= 	"tbl_item A
						INNER JOIN tbl_joblist_detail B ON A.ITM_CODE = B.ITM_CODE AND B.PRJCODE = A.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND ITM_TYPE = 'PRM'
						AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_DESC LIKE '%$search%' ESCAPE '!' 
						OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!')";*/
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A 
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	/*function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.PRJCODE, A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, A.ITM_CATEG, A.ITM_NAME, A.ITM_DESC, A.ITM_UNIT,
							A.ITM_VOLMBG, A.ITM_VOLM, A.ITM_IN, A.ITM_OUT, A.ACC_ID, A.ACC_ID_UM
						FROM tbl_item A
							INNER JOIN tbl_joblist_detail B ON A.ITM_CODE = B.ITM_CODE AND B.PRJCODE = A.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND ITM_TYPE = 'PRM'
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir";
			}
			else
			{
				$sql = "SELECT A.PRJCODE, A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, A.ITM_CATEG, A.ITM_NAME, A.ITM_DESC, A.ITM_UNIT,
							A.ITM_VOLMBG, A.ITM_VOLM, A.ITM_IN, A.ITM_OUT, A.ACC_ID, A.ACC_ID_UM
						FROM tbl_item A
							INNER JOIN tbl_joblist_detail B ON A.ITM_CODE = B.ITM_CODE AND B.PRJCODE = A.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND ITM_TYPE = 'PRM'
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.PRJCODE, A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, A.ITM_CATEG, A.ITM_NAME, A.ITM_DESC, A.ITM_UNIT,
							A.ITM_VOLMBG, A.ITM_VOLM, A.ITM_IN, A.ITM_OUT, A.ACC_ID, A.ACC_ID_UM
						FROM tbl_item A
							INNER JOIN tbl_joblist_detail B ON A.ITM_CODE = B.ITM_CODE AND B.PRJCODE = A.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND ITM_TYPE = 'PRM'
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.PRJCODE, A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, A.ITM_CATEG, A.ITM_NAME, A.ITM_DESC, A.ITM_UNIT,
							A.ITM_VOLMBG, A.ITM_VOLM, A.ITM_IN, A.ITM_OUT, A.ACC_ID, A.ACC_ID_UM
						FROM tbl_item A
							INNER JOIN tbl_joblist_detail B ON A.ITM_CODE = B.ITM_CODE AND B.PRJCODE = A.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND ITM_TYPE = 'PRM'
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_DESC LIKE '%$search%' ESCAPE '!' 
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}*/
	
	function get_AllDataITMSC($PRJCODE, $search) // G
	{
		$sql = "tbl_item A
				WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M','T') AND A.STATUS = 1
					AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
					OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir) // G
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT '' AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
							A.ITM_TYPE, A.ITM_CODE_H
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M','T') AND A.STATUS = 1
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT '' AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
							A.ITM_TYPE, A.ITM_CODE_H
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M','T') AND A.STATUS = 1
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT '' AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
							A.ITM_TYPE, A.ITM_CODE_H
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M','T') AND A.STATUS = 1
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT '' AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
							A.ITM_TYPE, A.ITM_CODE_H
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M','T') AND A.STATUS = 1
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>