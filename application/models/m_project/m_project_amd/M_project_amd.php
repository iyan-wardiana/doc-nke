<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 April 2018
 * File Name	= M_project_amd.php
 * Location		= -
*/

class M_project_amd extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_amd_header A
					LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_amd($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_amd($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
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
	
	function count_all_num_rowsAllItem($PRJCODE, $AMD_CATEG, $JOBCODE) // OK
	{
		if($PRJCODE == 'KTR')
		{
			if($AMD_CATEG == 'NB')
			{
				$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE'";
			}
			else
			{
				$sql		= "tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
		}
		else
		{
			if($AMD_CATEG == 'OB')
			{
				$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SI')
			{
				$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SINJ')
			{
				/*$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE'";*/
				$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
			elseif($AMD_CATEG == 'NB')
			{
				/*$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";*/
				$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE'";
			}
			elseif($AMD_CATEG == 'OTH')
			{
				$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
			else
			{
				$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}			
		}		
		return $this->db->count_all($sql);		
	}
	
	function viewAllItemMatBudget($PRJCODE, $AMD_CATEG, $JOBCODE) // OK
	{
		if($PRJCODE == 'KTR')
		{
			if($AMD_CATEG == 'NB')
			{
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE'";
			}
			else
			{
				$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
								LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
		}
		else
		{
			if($AMD_CATEG == 'OB')
			{
				$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SI')
			{
				$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SINJ')
			{
				/*$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP, 
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'";*/
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
			elseif($AMD_CATEG == 'NB')
			{
				/*$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE, 
								A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, 
								A.ADD_PRICE, A.ITM_GROUP, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, 
								A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, 
								A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_TYPE, A.JOBDESC AS ITM_NAME
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'
							UNION ALL
							SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND ISOUTB = 1";*/
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE'";
			}
			elseif($AMD_CATEG == 'OTH')
			{
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
		}
		return $this->db->query($sql);
	}
	
	function addAMD($insertAMD) // OK
	{
		$this->db->insert('tbl_amd_header', $insertAMD);
	}
	
	function get_amd_by_number($AMD_NUM) // OK
	{
		$sql		= "SELECT * FROM tbl_amd_header WHERE AMD_NUM = '$AMD_NUM' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function updateAMD($AMD_NUM, $updateAMD) // OK
	{
		$this->db->where('AMD_NUM', $AMD_NUM);
		$this->db->update('tbl_amd_header', $updateAMD);
	}
	
	function deleteAMDDet($AMD_NUM) // OK
	{
		$this->db->where('AMD_NUM', $AMD_NUM);
		$this->db->delete('tbl_amd_detail');
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_amd_header A
					LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7)
					AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7)
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7)
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7)
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7)
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_AMDInb($PRJCODE, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7)
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_AMDInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7)
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function get_amd_by_numberLC($DefEmp_ID) // OK
	{
		$sql 		= "tbl_amd_header A
							INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
							AND A.AMD_STAT IN (2,7)";
		$countAMD 	= $this->db->count_all($sql);
		return $countAMD;
	}
	
	function get_amd_by_numberL($DefEmp_ID) // OK
	{
		$sql 	= "SELECT A.PRJCODE
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND A.AMD_STAT IN (2,7) LIMIT 1";
		return $this->db->query($sql);
	}
	
	function updateWBS($paramWBS) // OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);*/
		
		// SELECT ITM_VOLMBG, ITM_VOLMBGR, ITM_PRICE, ITM_LASTP, ADDVOLM, ADDCOST FROM tbl_item; 
		// SELECT JOBVOLM, PRICE, JOBCOST, ADD_VOLM, ADD_PRICE, ADD_JOBCOST FROM tbl_joblist; 
		// SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST  FROM tbl_joblist_detail;
		
		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$sql1	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			/*$sql3	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql3);*/
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql5		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			
			$sql6		= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7		= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
		elseif($AMD_CLASS == 1) // IF PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			// HANYA MERUBAH HARGA, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MENAMBAH VOLUME DAN NILAI ADDENDUM
			$ITM_VOLM	= 1;
			$sqlJL		= "SELECT ITM_VOLM FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$ITM_VOLM	= $rowJL->ITM_VOLM;
			endforeach;
			$ITM_BUDG	= $ITM_VOLM * $ADD_PRICE;
			
			$sql1		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = $ITM_BUDG
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
								ITM_BUDG = $ITM_BUDG
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql3);
		}
		elseif($AMD_CLASS == 2) // IF QTY ONLY
		{
			// MERUBAH QTY, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MERUBAH HARGA
			
			$sql1	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql5	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			
			$sql6	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
	}
	
	function updateWBSD($paramWBS) // HOLD
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		$sql	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
						ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);
	}
	
	function updateWBSM($paramWBS) // OK - SI MINUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' c";
		$this->db->query($sql);*/

		
		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
							ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = $ADDVOLM2
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql6	= "UPDATE tbl_joblist SET ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7	= "UPDATE tbl_joblist_detail SET ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
		elseif($AMD_CLASS == 1) // IF PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			$sql1		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql3);
		}
		elseif($AMD_CLASS == 2) // IF QTY ONLY
		{
			$sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDMVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDMVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDMVOLM	= $rowITM->ADDMVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDMVOLM2		= $ADDMVOLM + $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateWBSDM($paramWBS) // OK
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		$sql	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
						ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);
	}
	
	function updateSIH($paramSIH) // G
	{
		$AMD_NUM 		= $paramSIH['SI_AMANDNO'];
		$SI_AMANDVAL 	= $paramSIH['SI_AMANDVAL'];
		$SI_AMANDSTAT	= $paramSIH['SI_AMANDSTAT'];
		$SI_CODE		= $paramSIH['SI_CODE'];
		$PRJCODE		= $paramSIH['PRJCODE'];
		
		if($SI_AMANDVAL == '')
			$SI_AMANDVAL	= 0;
			
		$sqlUPDSI	= "UPDATE tbl_siheader SET SI_AMAND = 1, SI_AMDCREATED = 1, SI_AMANDNO = '$AMD_NUM', 
							SI_AMANDVAL = '$SI_AMANDVAL', SI_AMANDSTAT = $SI_AMANDSTAT
						WHERE SI_CODE = '$SI_CODE' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUPDSI);
			
		// UPDATE BERDASARKAN TOTAL AMD KE TOTAL SI
			$TOT_AMD		= 0;
			$SI_APPVAL		= 0;
			$sqlTOTAMD		= "SELECT SUM(A.AMD_AMOUNT) TOT_AMD, B.SI_APPVAL FROM tbl_amd_header A, tbl_siheader B
								WHERE A.AMD_REFNO = B.SI_CODE AND AMD_REFNO = '$SI_CODE' AND AMD_STAT IN (3,6)";
			$resTOTAMD		= $this->db->query($sqlTOTAMD)->result();
			foreach($resTOTAMD as $rowTOTAMD):
				$TOT_AMD	= $rowTOTAMD->TOT_AMD;
				$SI_APPVAL	= $rowTOTAMD->SI_APPVAL;
				if($TOT_AMD >= $SI_APPVAL)
				{
					$sqlUPDSI	= "UPDATE tbl_siheader SET SI_STAT = 6
									WHERE SI_CODE = '$SI_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUPDSI);
				}
			endforeach;		
	}
	
	function updateWBSMin($paramWBS) // G - NOT BUDGETING - MINUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$sql1	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM - $ADD_VOLM;
			
			$sql5		= "UPDATE tbl_item SET ADDVOLM = $ADDVOLM2
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			
			$sql6	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
		elseif($AMD_CLASS == 1) // IF PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			/*$JOBVOLM	= 1;
			$sqlJL		= "SELECT JOBVOLM FROM tbl_joblist 
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$JOBVOLM	= $rowJL->JOBVOLM;
			endforeach;*/
			$ITM_VOLM	= 1;
			$sqlJL		= "SELECT ITM_VOLM FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$ITM_VOLM	= $rowJL->ITM_VOLM;
			endforeach;
			$ITM_BUDG	= $ITM_VOLM * $ADD_PRICE;
			
			$sql1		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql1);
			
			//$JOBCOST	= $JOBVOLM * $ADD_PRICE;
			/*$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, JOBCOST = $JOBCOST, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
			$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql2);
			
			/*$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ITM_BUDG = $JOBCOST, 
								ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
			$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
								ITM_BUDG = $ITM_BUDG
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql3);
		}
		elseif($AMD_CLASS == 2) // IF QTY ONLY
		{
			
			$sql1	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM - $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = $ADDVOLM2
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateWBSMPlus($paramWBS) // G - PEMBALIKAN SI MINUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' c";
		$this->db->query($sql);*/
		
		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM, ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM, ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM - $ADD_VOLM;
			
			$sql6	= "UPDATE tbl_joblist SET ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7	= "UPDATE tbl_joblist_detail SET ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
		elseif($AMD_CLASS == 1) // IF PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			$sql1		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql1);
			
			$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql2);
			
			$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql3);
		}
		elseif($AMD_CLASS == 2) // IF QTY ONLY
		{
			$sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDMVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDMVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDMVOLM	= $rowITM->ADDMVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDMVOLM2		= $ADDMVOLM - $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateSIHCanc($paramSIH) // G
	{
		$AMD_NUM 		= $paramSIH['SI_AMANDNO'];
		$SI_AMANDVAL 	= $paramSIH['SI_AMANDVAL'];
		$SI_AMANDSTAT	= $paramSIH['SI_AMANDSTAT'];
		$SI_CODE		= $paramSIH['SI_CODE'];
		$PRJCODE		= $paramSIH['PRJCODE'];
		
		if($SI_AMANDVAL == '')
			$SI_AMANDVAL	= 0;
			
		$sqlUPDSI	= "UPDATE tbl_siheader SET SI_AMAND = 0, SI_AMDCREATED = 0, SI_AMANDNO = '', 
							SI_AMANDVAL = 0, SI_AMANDSTAT = 0
						WHERE SI_CODE = '$SI_CODE' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUPDSI);
	}
	
	function deleteBOQ($AMD_NUM) // G
	{
		$this->db->where('JOBCOD2', $AMD_NUM);
		$this->db->delete('tbl_boqlist');
	}
	
	function deleteJL($AMD_NUM) // G
	{
		$this->db->where('JOBCOD2', $AMD_NUM);
		$this->db->delete('tbl_joblist');
	}
	
	function deleteJLD($AMD_NUM) // G
	{
		$this->db->where('JOBCOD2', $AMD_NUM);
		$this->db->delete('tbl_joblist_detail');
	}
}
?>