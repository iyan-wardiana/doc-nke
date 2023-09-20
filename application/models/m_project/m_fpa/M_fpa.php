<?php
/* 
 * Author		= Wardiana
 * Create Date	= 28 Agustus 2018
 * File Name	= M_fpa.php
 * Location		= -
*/

class M_fpa extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_fpa_header A
					INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('MDR','SUB') AND A.FPA_TYPE = 'FPA'
					AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
					OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('MDR','SUB') AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('MDR','SUB') AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('MDR','SUB') AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('MDR','SUB') AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_FPA($PRJCODE) // G
	{
		$sql	= "tbl_fpa_header A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('MDR','SUB')";
		return $this->db->count_all($sql);
	}
	
	function get_all_FPA($PRJCODE) // G
	{
		$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID,
					A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_fpa_header A
				INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('MDR','SUB') AND A.FPA_TYPE = 'FPA'
				ORDER BY A.FPA_CODE ASC";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_ItemServ($PRJCODE, $JOBCODEID) // G
	{
		$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' 
						AND ITM_GROUP NOT IN ('M')
						AND JOBPARENT IN ('$JOBCODEID') 
						AND ISLAST = 1";		
		return $this->db->count_all($sql);
		
	}
	
	function viewAllItemServ($PRJCODE, $JOBCODEID) // G
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, 
							A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY, 
							A.OPN_AMOUNT,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.JOBDESC, A.ITM_GROUP
						FROM tbl_joblist_detail A
						WHERE A.PRJCODE = '$PRJCODE' 
							AND A.ITM_GROUP NOT IN ('M') 
							AND A.JOBPARENT IN ('$JOBCODEID')
							AND ISLAST = 1";
		return $this->db->query($sql);
	}
	
	function add($projWOH) // G
	{
		$this->db->insert('tbl_fpa_header', $projWOH);
	}
	
	function get_FPA_by_number($FPA_NUM) // G
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_fpa_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.FPA_NUM = '$FPA_NUM'";
		return $this->db->query($sql);
	}
	
	function update($FPA_NUM, $projWOH) // G
	{
		$this->db->where('FPA_NUM', $FPA_NUM);
		$this->db->update('tbl_fpa_header', $projWOH);
	}
	
	function deleteDetail($FPA_NUM) // G
	{
		$this->db->where('FPA_NUM', $FPA_NUM);
		$this->db->delete('tbl_fpa_detail');
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_fpa_header A
					INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('MDR') AND A.FPA_STAT IN (2,7)
					AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
					OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('MDR') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('MDR') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('MDR') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('MDR') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_FPAInx($DefEmp_ID) // G
	{
		$sql	= "tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('MDR') AND A.FPA_TYPE = 'FPA'";
		return $this->db->count_all($sql);
	}
	
	function get_all_FPAInb($DefEmp_ID) // G
	{
		$sql 	= "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.PRJCODE, A.JOBCODEID,
						A.FPA_NOTE, A.FPA_STAT, FPA_MEMO, A.FPA_CREATER, A.TSF_STAT,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('MDR') AND A.FPA_TYPE = 'FPA'
					ORDER BY A.FPA_CODE ASC";
		return $this->db->query($sql);
	}
	
	function get_AllDataC_1n25uB($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_fpa_header A
					INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('SUB') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
					AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
					OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n25uB($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('SUB') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('SUB') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('SUB') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID, B.JOBDESC,
							A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER, A.STATDESC, A.STATCOL, A.CREATERNM,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
						FROM tbl_fpa_header A
							INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('SUB') AND A.FPA_STAT IN (2,7) AND A.FPA_TYPE = 'FPA'
							AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!' 
							OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_FPAInxS($DefEmp_ID) // G
	{
		$sql	= "tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('SUB') AND A.FPA_TYPE = 'FPA'";
		return $this->db->count_all($sql);
	}
	
	function get_all_FPAInbS($DefEmp_ID) // G
	{
		$sql 	= "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_CATEG, A.FPA_DATE, A.PRJCODE, A.JOBCODEID,
						A.FPA_NOTE, A.FPA_STAT, FPA_MEMO, A.FPA_CREATER, A.TSF_STAT,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('SUB') AND A.FPA_TYPE = 'FPA'
					ORDER BY A.FPA_CODE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_ItemServX($PRJCODE, $JOBCODEID, $FPA_CATEG) // G
	{
		if($FPA_CATEG == 'SUB')
		{
			$ADDQRY	= "IN ('SC')";
		}
		elseif($FPA_CATEG == 'MDR')
		{
			$ADDQRY	= "IN ('U')";
		}
		
		$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' 
						AND ITM_GROUP $ADDQRY
						AND JOBPARENT IN ('$JOBCODEID') 
						AND ISLAST = 1";		
		return $this->db->count_all($sql);
		
	}
	
	function viewAllItemServX($PRJCODE, $JOBCODEID, $FPA_CATEG) // G
	{
		if($FPA_CATEG == 'SUB')
		{
			$ADDQRY	= "IN ('SC')";
		}
		elseif($FPA_CATEG == 'MDR')
		{
			$ADDQRY	= "IN ('U')";
		}
		
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, 
							A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY, 
							A.OPN_AMOUNT,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.JOBDESC, A.ITM_GROUP
						FROM tbl_joblist_detail A
						WHERE A.PRJCODE = '$PRJCODE' 
							AND A.ITM_GROUP $ADDQRY
							AND A.JOBPARENT IN ('$JOBCODEID')
							AND ISLAST = 1";
		return $this->db->query($sql);
	}
}
?>