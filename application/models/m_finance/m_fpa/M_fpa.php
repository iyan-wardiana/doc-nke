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
				WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
					AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_fpa_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_fpa_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function count_all_FPA($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_fpa_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'";
		}
		else
		{
			$sql = "tbl_fpa_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (FPA_NUM LIKE '%$key%' ESCAPE '!' OR FPA_CODE LIKE '%$key%' ESCAPE '!' 
						OR FPA_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_FPA($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID,
						A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_fpa_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_DATE, A.FPA_TSFD, A.PRJCODE, A.JOBCODEID,
						A.FPA_NOTE, A.FPA_STAT, A.FPA_MEMO, A.FPA_CREATER,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_fpa_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (FPA_NUM LIKE '%$key%' ESCAPE '!' OR FPA_CODE LIKE '%$key%' ESCAPE '!' 
						OR FPA_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
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
		// MS.201990400006 HANYA O, GE, I dari M, U, SC, T, I, GE, O
		$sql		= "tbl_joblist_detail A
							LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' 
							-- AND A.ITM_GROUP NOT IN ('M','T') 
							AND A.ITM_GROUP IN ('I','GE','O')
							AND A.JOBPARENT IN ($JOBCODEID)
							AND ISLAST = 1";		
		return $this->db->count_all($sql);
		// 2018/11/12 -- AND ITM_GROUP NOT IN ('M','T') ditutup kembali atas permintaan Pak Dikka setelah diskusi dengan pak dedy
		// agar tidak bisa memilih material
	}
	
	function viewAllItemServ($PRJCODE, $JOBCODEID) // G
	{
		// MS.201990400006 HANYA O, GE, I dari M, U, SC, T, I, GE, O
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, 
							A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY, 
							A.OPN_AMOUNT,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.JOBDESC, A.ITM_GROUP,
							B.ITM_NAME
						FROM tbl_joblist_detail A
							LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							-- AND A.ITM_GROUP NOT IN ('M','T')
							AND A.ITM_GROUP IN ('I','GE','O')
							AND A.JOBPARENT IN ($JOBCODEID)
							AND ISLAST = 1";
		return $this->db->query($sql);
		// 2018/11/12 -- AND ITM_GROUP NOT IN ('M','T') ditutup kembali atas permintaan Pak Dikka setelah diskusi dengan pak dedy
		// agar tidak bisa memilih material
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
				WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_STAT IN (2,7) AND A.FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
					AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_fpa_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_STAT IN (2,7) AND A.FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_fpa_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.FPA_STAT IN (2,7) AND A.FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (A.FPA_CODE LIKE '%$search%' ESCAPE '!' OR A.FPA_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function count_all_FPAInx($PRJCODE, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'";	// Only Confirm Stat (2)
		}
		else
		{
			$sql = "tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (FPA_NUM LIKE '%$key%' ESCAPE '!' OR FPA_CODE LIKE '%$key%' ESCAPE '!' 
						OR FPA_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_FPAInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_DATE, A.PRJCODE, A.JOBCODEID,
						A.FPA_NOTE, A.FPA_STAT, FPA_MEMO, A.FPA_CREATER, A.TSF_STAT,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM' LIMIT $start, $end";	// Only Confirm Stat (2)
		}
		else
		{
			$sql = "SELECT A.FPA_NUM, A.FPA_CODE, A.FPA_DATE, A.PRJCODE, A.JOBCODEID,
						A.FPA_NOTE, A.FPA_STAT, FPA_MEMO, A.FPA_CREATER, A.TSF_STAT,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_fpa_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND FPA_STAT IN (2,7) AND FPA_CATEG IN ('OHP','RPA','OTH','SUB') AND A.FPA_TYPE = 'RM'
						AND (FPA_NUM LIKE '%$key%' ESCAPE '!' OR FPA_CODE LIKE '%$key%' ESCAPE '!' 
						OR FPA_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
}
?>