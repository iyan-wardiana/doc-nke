<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 1 Februari 2018
	* File Name		= M_opname.php
	* Location		= -
*/

class M_opname extends CI_Model
{	
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_opn_header A
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $SPLCODE, $OPNH_STAT, $OPNH_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($OPNH_STAT != 0)
			$ADDQRY2 	= "AND A.OPNH_STAT = '$OPNH_STAT'";
		if($OPNH_CATEG != '')
			$ADDQRY3 	= "AND A.OPNH_CATEG = '$OPNH_CATEG'";

		$sql = "tbl_opn_header A
				WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
					AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $SPLCODE, $OPNH_STAT, $OPNH_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($OPNH_STAT != 0)
			$ADDQRY2 	= "AND A.OPNH_STAT = '$OPNH_STAT'";
		if($OPNH_CATEG != '')
			$ADDQRY3 	= "AND A.OPNH_CATEG = '$OPNH_CATEG'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataSHC($PRJCODE, $ISCLS, $search) // GOOD
	{
		if($ISCLS == 0)		// SHOW ALL
		{
			$sql = "tbl_opn_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
						OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
		}
		else				// HIDE DOKUMEN YANG REJECT, VOID, CLOSE
		{
			$sql = "tbl_opn_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (1,2,3)
						AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
						OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
		}
		
		return $this->db->count_all($sql);
	}
	
	function get_AllDataSHL($PRJCODE, $ISCLS, $search, $length, $start, $order, $dir) // GOOD
	{
		if($ISCLS == 0)		// SHOW ALL
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else				// HIDE DOKUMEN YANG REJECT, VOID, CLOSE
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (1,2,3)
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (1,2,3)
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (1,2,3)
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
								A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
								A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
								A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
							FROM tbl_opn_header A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (1,2,3)
								AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
								OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.WO_CODE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataWOC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_wo_header A
                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                WHERE A.WO_STAT = '3' AND A.PRJCODE = '$PRJCODE'
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataWOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3' AND A.PRJCODE = '$PRJCODE'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3' AND A.PRJCODE = '$PRJCODE'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3' AND A.PRJCODE = '$PRJCODE'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3' AND A.PRJCODE = '$PRJCODE'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_OPN($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			/*$sql = "tbl_opn_header A
						INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";*/
			$sql = "tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.OPNH_NUM LIKE '%$key%' ESCAPE '!' OR A.OPNH_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OPNH_DATE LIKE '%$key%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$key%' ESCAPE '!'
						OR B.JOBDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_OPN($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
						A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.OPNH_CREATER, A.OPNH_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.JOBDESC
					FROM tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
						A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.OPNH_CREATER, A.OPNH_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.JOBDESC
					FROM tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.OPNH_NUM LIKE '%$key%' ESCAPE '!' OR A.OPNH_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OPNH_DATE LIKE '%$key%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$key%' ESCAPE '!'
						OR B.JOBDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllItem($PRJCODE) // OK
	{
		//return $this->db->count_all('tbl_cost');
		if($PRJCODE == 'KTR')
		{
			//$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE'";
			$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP = 'S' AND ISLAST = 1";
		}		
		return $this->db->count_all($sql);
		
	}
	
	function viewAllItemMatBudget($PRJCODE) // OK
	{
		/*$sql		= "SELECT DISTINCT Z.PRJCODE, Z.CSTCODE, Z.Item_code, Z.unit_type_code, Z.unit_type_code2, Z.desc1,
						Z.PPMat_Qty, Z.PPMat_Qty2, Z.request_qty, Z.request_qty2, Z.PO_Qty, Z.PO_Qty2, Z.receipt_qty, Z.receipt_qty2,
						Z.used_qty, Z.used_qty2, Z.Unit_Price, Z.Amount, Z.Price, Z.TotPrice, B.Unit_Type_Name
						FROM tbl_projplan_material Z
						INNER JOIN tbl_unittype B ON B.unit_type_code = Z.unit_type_code
						WHERE Z.PRJCODE = '$PRJCODE' AND budgetCategory = 'MTRL'
						ORDER BY Z.Item_code";*/
		if($PRJCODE == 'KTR')
		{
			/*$sql		= "SELECT DISTINCT Z.PRJCODE, Z.ITM_CODE, Z.ITM_CATEG, Z.ITM_NAME, Z.ITM_DESC, Z.ITM_UNIT,
							Z.ITM_VOLM, Z.ITM_PRICE, Z.ITM_REMQTY, Z.ITM_LASTP, B.Unit_Type_Name
							FROM tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.unit_type_code
							WHERE Z.PRJCODE = '$PRJCODE'
							ORDER BY Z.ITM_NAME";*/
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, 
							A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT,
							B.ITM_NAME
							FROM tbl_joblist_detail A
							LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'S' AND ISLAST = 1";
		}
		else
		{
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, 
							A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT,
							B.ITM_NAME
							FROM tbl_joblist_detail A
							LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'S' AND ISLAST = 1";
		}
		return $this->db->query($sql);
	}
	
	function add($projOPNH) // OK
	{
		$this->db->insert('tbl_opn_header', $projOPNH);
	}
	
	function updateDet($OPNH_NUM, $PRJCODE, $OPNH_DATE) // OK
	{
		$sql = "UPDATE tbl_opn_detail SET PRJCODE = '$PRJCODE', OPNH_DATE = '$OPNH_DATE' WHERE OPNH_NUM = '$OPNH_NUM'";
		return $this->db->query($sql);
	}
	
	function get_opn_by_number($OPNH_NUM) // OK
	{
		$sql = "SELECT A.*,
					B.PRJCODE, B.PRJNAME
				FROM tbl_opn_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.OPNH_NUM = '$OPNH_NUM'";
		return $this->db->query($sql);
	}
	
	function get_opnprint_by_number($OPNH_NUM) // OK
	{
		$sql = "SELECT A.*, C.OPNH_CODE, C.OPNH_DATESP, C.OPNH_NOTE, C.OPNH_STAT, B.PRJCODE, B.PRJNAME
				FROM tbl_wo_header A
					INNER JOIN 	tbl_opn_header C ON A.WO_NUM = C.WO_NUM
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE C.OPNH_NUM = '$OPNH_NUM'";
		return $this->db->query($sql);
	}
	
	function update($OPNH_NUM, $projOPNH) // OK
	{
		$this->db->where('OPNH_NUM', $OPNH_NUM);
		$this->db->update('tbl_opn_header', $projOPNH);

		$sqlJDOPNH	= "UPDATE tbl_journalheader SET GEJ_STAT = 9 WHERE JournalH_Code = '$OPNH_NUM'";
        $this->db->query($sqlJDOPNH);

		$sqlJDOPND	= "UPDATE tbl_journaldetail SET GEJ_STAT = 9 WHERE JournalH_Code = '$OPNH_NUM'";
        $this->db->query($sqlJDOPND);
	}
	
	function updateREJECT($OPNH_NUM, $projOPNH) // OK
	{
		$this->db->where('OPNH_NUM', $OPNH_NUM);
		$this->db->update('tbl_opn_header', $projOPNH);
	}
	
	function deleteDetail($OPNH_NUM) // OK
	{
		$this->db->where('OPNH_NUM', $OPNH_NUM);
		$this->db->delete('tbl_opn_detail');
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_opn_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (2,7)
					AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (2,7)
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (2,7)
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (2,7)
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
							A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.WO_CODE, A.OPNH_CREATER, A.OPNH_ISCLOSE,
							A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.WO_CATEG
						FROM tbl_opn_header A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT IN (2,7)
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.OPNH_DATE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_CODE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_OPNInx($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND OPNH_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND OPNH_STAT IN (2,7)
						AND (A.OPNH_NUM LIKE '%$key%' ESCAPE '!' OR A.OPNH_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OPNH_DATE LIKE '%$key%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$key%' ESCAPE '!'
						OR B.JOBDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_OPNInb($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
						A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.OPNH_CREATER, A.OPNH_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.JOBDESC
					FROM tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND OPNH_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.PRJCODE, A.SPLCODE, A.OPNH_DEPT, A.JOBCODEID,
						A.OPNH_NOTE, A.OPNH_STAT, OPNH_MEMO, A.WO_NUM, A.OPNH_CREATER, A.OPNH_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.JOBDESC
					FROM tbl_opn_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND OPNH_STAT IN (2,7)
						AND (A.OPNH_NUM LIKE '%$key%' ESCAPE '!' OR A.OPNH_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.OPNH_DATE LIKE '%$key%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$key%' ESCAPE '!'
						OR B.JOBDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function updateWODet($OPNH_NUM, $WO_NUM, $PRJCODE) // OK
	{				
		$sqlGetWO	= "SELECT WO_ID, OPNH_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, OPND_VOLM, OPND_ITMPRICE
						FROM tbl_opn_detail
						WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetWO	= $this->db->query($sqlGetWO)->result();
		foreach($resGetWO as $rowWO) :
			$WO_ID 		= $rowWO->WO_ID;
			$OPNH_NUM 	= $rowWO->OPNH_NUM;
			$JOBCODEDET	= $rowWO->JOBCODEDET;
			$JOBCODEID	= $rowWO->JOBCODEID;
			$ITM_CODE	= $rowWO->ITM_CODE;
			$OPND_VOLM	= $rowWO->OPND_VOLM;
			$ITM_PRICE	= $rowWO->OPND_ITMPRICE;
			$OPND_TOTAMN= $OPND_VOLM * $ITM_PRICE;
			
			// UPDATE JOBDETAIL
			/*$REQ_VOLM	= 0;
			$REQ_AMOUNT	= 0;
			$sqlGetJD		= "SELECT WO_QTY, WO_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resGetJD		= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$WO_QTY 	= $rowJD->WO_QTY;
				$WO_AMOUNT	= $rowJD->WO_AMOUNT;
			endforeach;
			if($WO_QTY == '')
				$WO_QTY = 0;
			if($WO_AMOUNT == '')
				$WO_AMOUNT = 0;
				
			$totWOQty	= $WO_QTY + $WO_VOLM;
			$totWOAmn	= $WO_AMOUNT + $OPND_TOTAMN;*/
			
			if($OPND_VOLM == '')
				$OPND_VOLM = 0;
			if($OPND_TOTAMN == '')
				$OPND_TOTAMN = 0;
				
			/*$sqlGetWO1	= "SELECT OPN_VOLM, OPN_AMOUNT
							FROM tbl_wo_detail
							WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND WO_ID = '$WO_ID'";*/

			// LANGSUNG HITUNG TOTAL YANG SUDAH DIOPNAME PER WO_ID
			$sqlGetWO1	= "SELECT SUM(A.OPND_VOLM) AS OPN_VOLM, SUM(A.OPND_ITMTOTAL) AS OPN_AMOUNT
							FROM tbl_opn_detail A
								INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM AND B.OPNH_STAT IN (3,6)
							WHERE B.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND A.WO_ID = '$WO_ID'";
			$resGetWO1	= $this->db->query($sqlGetWO1)->result();
			foreach($resGetWO1 as $rowWO1) :
				$OPN_VOLM 	= $rowWO1->OPN_VOLM;
				if($OPN_VOLM == '')
					$OPN_VOLM	= 0;
				$OPN_AMOUNT	= $rowWO1->OPN_AMOUNT;
				if($OPN_AMOUNT == '')
					$OPN_AMOUNT	= 0;
			endforeach;
				
			/*$sqlUpdWO	= "UPDATE tbl_wo_detail SET OPN_VOLM = $OPN_VOLM+$OPND_VOLM, OPN_AMOUNT = $OPN_AMOUNT+$OPND_TOTAMN
							WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND WO_ID = '$WO_ID'";*/
			$sqlUpdWO	= "UPDATE tbl_wo_detail SET OPN_VOLM = $OPN_VOLM, OPN_AMOUNT = $OPN_AMOUNT
							WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND WO_ID = '$WO_ID'";
			$this->db->query($sqlUpdWO);
			
			$PO_VOLM 	= 0;
			$PO_AMOUNT 	= 0;
			$sqlGetJD	= "SELECT PO_VOLM, PO_AMOUNT
							FROM tbl_joblist_detail
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
								AND ITM_CODE = '$ITM_CODE'";
			$resGetJD	= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$PO_VOLM 	= $rowJD->PO_VOLM;
				if($PO_VOLM == '')
					$PO_VOLM	= 0;
				$PO_AMOUNT	= $rowJD->PO_AMOUNT;
				if($PO_AMOUNT == '')
					$PO_AMOUNT	= 0;
			endforeach;
			
			$sqlUpd		= "UPDATE tbl_joblist_detail SET PO_VOLM = $PO_VOLM+$OPND_VOLM, PO_AMOUNT = $PO_AMOUNT+$OPND_TOTAMN,
								OPN_QTY = OPN_QTY+$OPND_VOLM, OPN_AMOUNT = OPN_AMOUNT+$OPND_TOTAMN,
								ITM_USED = ITM_USED+$OPND_VOLM, ITM_USED_AM = ITM_USED_AM+$OPND_TOTAMN
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
								AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd);
			
			// UPDATE TBL_ITEM
			/*$WO_VOLM1		= 0;
			$WO_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT WO_VOLM, WO_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$WO_VOLM1 	= $rowJD1->WO_VOLM;
				$WO_AMOUNT1	= $rowJD1->WO_AMOUNT;
			endforeach;
			if($WO_VOLM1 == '')
				$WO_VOLM1 = 0;
			if($WO_AMOUNT1 == '')
				$WO_AMOUNT1 = 0;
				
			$totWORQty	= $WO_VOLM1 + $WO_VOLM;
			$totWORAmn	= $WO_AMOUNT1 + $OPND_TOTAMN;*/
				
			$sqlGetITM	= "SELECT PO_VOLM, PO_AMOUNT
							FROM tbl_item
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetITM	= $this->db->query($sqlGetITM)->result();
			foreach($resGetITM as $rowITM) :
				$PO_VOLM 	= $rowITM->PO_VOLM;
				if($PO_VOLM == '')
					$PO_VOLM	= 0;
				$PO_AMOUNT	= $rowITM->PO_AMOUNT;
				if($PO_AMOUNT == '')
					$PO_AMOUNT	= 0;
			endforeach;
				
			$sqlUpd2	= "UPDATE tbl_item SET PO_VOLM = PO_VOLM+$OPND_VOLM, PO_AMOUNT = PO_AMOUNT+$OPND_TOTAMN,
								ITM_OUT = ITM_OUT+$OPND_VOLM, ITM_OUTP = $ITM_PRICE
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
	}
	
	function updateVWODet($OPNH_NUM, $WO_NUM, $PRJCODE) // OK
	{				
		$sqlGetWO	= "SELECT OPNH_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, OPND_VOLM, OPND_ITMPRICE
						FROM tbl_opn_detail
						WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE' ";
		$resGetWO	= $this->db->query($sqlGetWO)->result();
		foreach($resGetWO as $rowWO) :
			$OPNH_NUM 	= $rowWO->OPNH_NUM;
			$JOBCODEDET	= $rowWO->JOBCODEDET;
			$JOBCODEID	= $rowWO->JOBCODEID;
			$ITM_CODE	= $rowWO->ITM_CODE;
			$OPND_VOLM	= $rowWO->OPND_VOLM;
			$ITM_PRICE	= $rowWO->OPND_ITMPRICE;
			$OPND_TOTAMN= $OPND_VOLM * $ITM_PRICE;
			
			// UPDATE JOBDETAIL
			/*$REQ_VOLM	= 0;
			$REQ_AMOUNT	= 0;
			$sqlGetJD		= "SELECT WO_QTY, WO_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resGetJD		= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$WO_QTY 	= $rowJD->WO_QTY;
				$WO_AMOUNT	= $rowJD->WO_AMOUNT;
			endforeach;
			if($WO_QTY == '')
				$WO_QTY = 0;
			if($WO_AMOUNT == '')
				$WO_AMOUNT = 0;
				
			$totWOQty	= $WO_QTY + $WO_VOLM;
			$totWOAmn	= $WO_AMOUNT + $OPND_TOTAMN;*/
			
			if($OPND_VOLM == '')
				$OPND_VOLM = 0;
			if($OPND_TOTAMN == '')
				$OPND_TOTAMN = 0;
				
			$sqlGetWO1	= "SELECT OPN_VOLM, OPN_AMOUNT
							FROM tbl_wo_detail
							WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetWO1	= $this->db->query($sqlGetWO1)->result();
			foreach($resGetWO1 as $rowWO1) :
				$OPN_VOLM 	= $rowWO1->OPN_VOLM;
				if($OPN_VOLM == '')
					$OPN_VOLM	= 0;
				$OPN_AMOUNT	= $rowWO1->OPN_AMOUNT;
				if($OPN_AMOUNT == '')
					$OPN_AMOUNT	= 0;
			endforeach;
				
			$sqlUpdWO	= "UPDATE tbl_wo_detail SET OPN_VOLM = OPN_VOLM-$OPND_VOLM, OPN_AMOUNT = OPN_AMOUNT-$OPND_TOTAMN
							WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpdWO);
				
			$sqlGetJD	= "SELECT PO_VOLM, PO_AMOUNT
							FROM tbl_joblist_detail
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
								AND ITM_CODE = '$ITM_CODE'";
			$resGetJD	= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$PO_VOLM 	= $rowJD->PO_VOLM;
				if($PO_VOLM == '')
					$PO_VOLM	= 0;
				$PO_AMOUNT	= $rowJD->PO_AMOUNT;
				if($PO_AMOUNT == '')
					$PO_AMOUNT	= 0;
			endforeach;
			
			$sqlUpd		= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM-$OPND_VOLM, PO_AMOUNT = PO_AMOUNT-$OPND_TOTAMN,
								OPN_QTY = OPN_QTY-$OPND_VOLM, OPN_AMOUNT = OPN_AMOUNT-$OPND_TOTAMN
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
								AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd);
			
			// UPDATE TBL_ITEM
			/*$WO_VOLM1		= 0;
			$WO_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT WO_VOLM, WO_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$WO_VOLM1 	= $rowJD1->WO_VOLM;
				$WO_AMOUNT1	= $rowJD1->WO_AMOUNT;
			endforeach;
			if($WO_VOLM1 == '')
				$WO_VOLM1 = 0;
			if($WO_AMOUNT1 == '')
				$WO_AMOUNT1 = 0;
				
			$totWORQty	= $WO_VOLM1 + $WO_VOLM;
			$totWORAmn	= $WO_AMOUNT1 + $OPND_TOTAMN;*/
				
			$sqlGetITM	= "SELECT PO_VOLM, PO_AMOUNT
							FROM tbl_item
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetITM	= $this->db->query($sqlGetITM)->result();
			foreach($resGetITM as $rowITM) :
				$PO_VOLM 	= $rowITM->PO_VOLM;
				if($PO_VOLM == '')
					$PO_VOLM	= 0;
				$PO_AMOUNT	= $rowITM->PO_AMOUNT;
				if($PO_AMOUNT == '')
					$PO_AMOUNT	= 0;
			endforeach;
				
			$sqlUpd2	= "UPDATE tbl_item SET PO_VOLM = PO_VOLM-$OPND_VOLM, PO_AMOUNT = PO_AMOUNT-$OPND_TOTAMN 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
		
		// Update Status OPN - to Open
			$STATDESC 	= 'Approved';
			$STATCOL	= 'success';

			$sqlUpdWO	= "UPDATE tbl_wo_header SET WO_STAT = 3, STATDESC = '$STATDESC', STATCOL = '$STATCOL'
							WHERE PRJCODE = '$PRJCODE' AND WO_NUM = '$WO_NUM'";
			$this->db->query($sqlUpdWO);
	}
	
	function updateWOH($WO_NUM, $PRJCODE) // OK
	{
		// Count All Row
		$sqlWOC	= "tbl_wo_detail WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
		$resWOC	= $this->db->count_all($sqlWOC);
		
		$totCLOSE	= 0;
		$sqlWO	= "SELECT WO_VOLM, WO_TOTAL, OPN_VOLM, OPN_AMOUNT
					FROM tbl_wo_detail
					WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
		$resWO	= $this->db->query($sqlWO)->result();
		foreach($resWO as $rowWO) :
			$WO_VOLM	= $rowWO->WO_VOLM;
			$WO_TOTAL	= $rowWO->WO_TOTAL;
			$OPN_VOLM	= $rowWO->OPN_VOLM;
			$OPN_AMOUNT	= $rowWO->OPN_AMOUNT;
			if(($OPN_VOLM >= $WO_VOLM) || ($OPN_AMOUNT == $WO_TOTAL))
				$totCLOSE	= $totCLOSE + 1;
		endforeach;
		if($totCLOSE == $resWOC)
		{
			$STATDESC 	= 'Close';
			$STATCOL	= 'info';
			$sqlUpdWO	= "UPDATE tbl_wo_header SET WO_STAT = 6, STATDESC = '$STATDESC', STATCOL = '$STATCOL'
							WHERE PRJCODE = '$PRJCODE' AND WO_NUM = '$WO_NUM'";
			$this->db->query($sqlUpdWO);
		}
	}

	function count_OPNDET_by_number($OPNH_NUM) // OK
	{
		$sql = "tbl_opn_detail A
					INNER JOIN tbl_joblist_detail B ON B.JOBCODEID = A.JOBCODEID
					INNER JOIN tbl_opn_header C ON C.OPNH_NUM = A.OPNH_NUM 
						-- AND C.JOBCODEID = B.JOBPARENT
				WHERE A.OPNH_NUM = '$OPNH_NUM'";
		return $this->db->count_all($sql);
	}

	function get_OPNDET_by_number($OPNH_NUM) // OK
	{
		$sql = "SELECT A.*, C.OPNH_AMOUNT, C.OPNH_RETAMN, B.JOBCODEID, B.JOBDESC, B.ITM_VOLM
				FROM tbl_opn_detail A
					INNER JOIN tbl_joblist_detail B ON B.JOBCODEID = A.JOBCODEID
						AND B.PRJCODE = A.PRJCODE
					INNER JOIN tbl_opn_header C ON C.OPNH_NUM = A.OPNH_NUM
						AND C.PRJCODE = A.PRJCODE
						-- AND C.JOBCODEID = B.JOBPARENT
				WHERE A.OPNH_NUM = '$OPNH_NUM'";
		return $this->db->query($sql);
	}
}
?>