<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 April 2018
	* File Name		= M_project_amd.php
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
					/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'*/
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
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
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
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
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
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
	
	function deleteAMDDetSUBS($AMD_NUM) // OK
	{
		$this->db->where('AMD_NUM', $AMD_NUM);
		$this->db->delete('tbl_amd_detail_subs');
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
	
	function updateWBSH($paramWBS) // OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$PRJCODE		= $paramWBS['PRJCODE'];

		// UPDATE JOBLIST
			$s_P1 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P1 		= $this->db->query($s_P1)->result();
			foreach($r_P1 as $rw_P1) :
				$JP1 	= $rw_P1->JOBPARENT;
				$s_u1a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP1' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u1a);

				$s_u1b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP1' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u1b);
				echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP1' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					echo "DONE 2<br>";
					$s_P3 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 		= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						echo "DONE 3<br>";
						$s_P4 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 		= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							echo "DONE 4<br>";
							$s_P5 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 		= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								echo "DONE 5<br>";
								$s_P6 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 		= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									echo "DONE 6<br>";
									$s_P7 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 		= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										echo "DONE 7<br>";
										$s_P8 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 		= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											echo "DONE 8<br>";
											$s_P9 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 		= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												echo "DONE 9<br>";
												$s_P10 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 		= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ADD_JOBCOST, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			// UPDATE ITEM
				$sql1		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
			
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
			
			$sql5	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
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
	
	function updateWBS($paramWBS) // OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_VOLM1 		= $paramWBS['ADD_VOLM'];
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
			$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);
					echo "DONE 3<br>";
					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
						echo "DONE 4<br>";
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
							echo "DONE 5<br>";
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
								echo "DONE 6<br>";
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);
									echo "DONE 7<br>";
									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);
										echo "DONE 8<br>";
										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);
											echo "DONE 9<br>";
											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			/*echo "DONE 11<br>";
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
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
			echo "DONE 12<br>";*/
		}
		elseif($AMD_CLASS == 1) // VOLUME IS DISABLED. PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			/* 	CATATAN HANYA AMANDEMEN HARGA
				1. Hanya melakukan perubahan harga
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 50			1,000		 50,000		Digunakan
					Sisa 		 50 		1,000 		 50,000		Sisa
					Amandemen 	 50 		1,200 		 60,000		Amandemen
					Total 		 50 		1,200 		110,000 	(Sisa + Amandemen)
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $REQ_AMOUNT;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $REQ_AMOUNT / $ITM_VOLM;

				// NILAI ADDENDUM
					$ADD_VOLM		= $BUD_REMV;				// DIANGGAP SEBAGAI AMANDEMEN VOLUME
					$ADD_JOBCOST	= $ADD_VOLM * $ADD_PRICE;	// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST
				$ITM_BUDGB 	= $BUD_USEAMN + $ADD_JOBCOST;		// BUDGET YANG SUDAH DIGUNAKAN + SISA BUDGET SETELAH DIKALI HARGA AMANDEMEN

				$s_u1		= "UPDATE tbl_joblist_detail SET 
									ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
										ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
										ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
											ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
											ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
												ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
												ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
													ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
													ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
														ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
														ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
															ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
															ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
																ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
																ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
																		ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
																	ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 	4. UPDATE PRICE IN MASTER ITEM
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
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 2) // PRICE IS DISABLED. QTY ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. TIDAK merubah TOTAL BUDGET
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 80			1,000		 80,000		Digunakan
					Sisa 		 20 		1,000 		 20,000		Sisa
					Amandemen 	  5 		0,000 		  0,000		Amandemen
					Total Sisa	 25 		  800 		 20,000 	(HRG. = Sisa Amount / (Sisa + Amandemen))
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $REQ_AMOUNT;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $REQ_AMOUNT / $ITM_VOLM;

				// NILAI ADDENDUM
					$ADD_VOLM		= $BUD_REMV + $ADD_VOLM1;	// SISA VOLUME + AMD_VOLM DIJADIKAN SEBAGAI AMANDEMEN VOLUME
					$ADD_PRICE 		= $BUD_REMAMN / $ADD_VOLM;
					$ADD_JOBCOST	= $BUD_REMAMN;				// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1		= "UPDATE tbl_joblist_detail SET 
									ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

			// UPDATE ITEM
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
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
	}
	
	function updateWBSM($paramWBS) 			// OK - SI MINUS
	{
		$ADDM_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADDM_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADDM_VOLM == '')
			$ADDM_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADDM_JOBCOST == '')
			$ADDM_JOBCOST	= 0;

		if($AMD_CLASS == 0) 					// IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
							ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);
					echo "DONE 3<br>";
					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
						echo "DONE 4<br>";
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
							echo "DONE 5<br>";
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
								echo "DONE 6<br>";
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);
									echo "DONE 7<br>";
									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);
										echo "DONE 8<br>";
										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);
											echo "DONE 9<br>";
											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDMVOLM = ADDMVOLM + $ADDM_VOLM, ADDMCOST = $ADDM_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
		}
		elseif($AMD_CLASS == 1) // VOLUME IS DISABLED. PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			// HANYA MERUBAH HARGA, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MENAMBAH VOLUME DAN NILAI ADDENDUM
				$ITM_VOLM	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ITM_VOLM * $ADD_PRICE;

			// CARI SELISIH TOTAL AWAL VS TOTAL AKHIR
				$DELTA_BUDG	= $ITM_BUDGB - $ITM_BUDGA;
			
			// UPDATE PRICE IN MASTER ITEM
				$s_01		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_01);

			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = $DELTA_BUDG, ITM_BUDG = $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
									ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ITM_BUDG = ITM_BUDG + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
										ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
										ITM_BUDG = ITM_BUDG + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
											ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
											ITM_BUDG = ITM_BUDG + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
												ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
												ITM_BUDG = ITM_BUDG + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
													ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
													ITM_BUDG = ITM_BUDG + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
														ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
														ITM_BUDG = ITM_BUDG + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
															ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
															ITM_BUDG = ITM_BUDG + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																ITM_BUDG = ITM_BUDG + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																	ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																	ITM_BUDG = ITM_BUDG + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
		}
		elseif($AMD_CLASS == 2) // PRICE IS DISABLED. QTY ONLY
		{
			// MERUBAH QTY, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MERUBAH HARGA
				$ITM_PRICE 	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_PRICE, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_PRICE	= $rowJL->ITM_PRICE;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ADDM_VOLM * $ITM_PRICE;
				echo "ITM_BUDGB = $ITM_BUDGB<br>";
			
			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADDM_JOBCOST = $ITM_BUDGB,
									ITM_BUDG = ITM_BUDG + $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					echo "DONE 2<br>";
					$s_P3 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 		= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						echo "DONE 3<br>";
						$s_P4 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 		= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							echo "DONE 4<br>";
							$s_P5 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 		= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								echo "DONE 5<br>";
								$s_P6 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 		= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									echo "DONE 6<br>";
									$s_P7 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 		= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										echo "DONE 7<br>";
										$s_P8 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 		= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											echo "DONE 8<br>";
											$s_P9 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 		= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												echo "DONE 9<br>";
												$s_P10 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 		= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADDM_VOLM - B.ADDM_VOLM) 
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
			
			$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = ADDMVOLM + $ADDM_VOLM,
							ADDMCOST = ADDMCOST + $ITM_BUDGB
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateWBSMIN($paramWBS) 			// PENGURANG ITEM YANG DIJADIKAN PENGGANTI UNTUK BUDGET LAIN
	{
		$ADDM_VOLM 		= $paramWBS['ADD_VOLM'];			// VOLUME DARI SISA YANG ADA
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADDM_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$AMD_TOTTSF		= $paramWBS['AMD_TOTTSF'];			// NILAI TRANSFER
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADDM_VOLM == '')
			$ADDM_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADDM_JOBCOST == '')
			$ADDM_JOBCOST	= 0;

		// PENGURANGAN INI SEBETULNYA HANYA MELAKUKAN PERUBAHAN HARGA, KARENA YANG DIKURANGI ADALAH NILAINYA, SEHINGGA VOLUME AKAN TETAP


		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
							ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);
					echo "DONE 3<br>";
					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
						echo "DONE 4<br>";
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
							echo "DONE 5<br>";
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
								echo "DONE 6<br>";
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);
									echo "DONE 7<br>";
									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);
										echo "DONE 8<br>";
										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);
											echo "DONE 9<br>";
											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDMVOLM = ADDMVOLM + $ADDM_VOLM, ADDMCOST = $ADDM_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
		}
		elseif($AMD_CLASS == 1) // VOLUME IS DISABLED. PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			// HANYA MERUBAH HARGA, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MENAMBAH VOLUME DAN NILAI ADDENDUM
				$ITM_VOLM	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ITM_VOLM * $ADD_PRICE;

			// CARI SELISIH TOTAL AWAL VS TOTAL AKHIR
				$DELTA_BUDG	= $ITM_BUDGB - $ITM_BUDGA;
			
			// UPDATE PRICE IN MASTER ITEM
				$s_01		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_01);

			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = $DELTA_BUDG, ITM_BUDG = $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
									ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ITM_BUDG = ITM_BUDG + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
										ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
										ITM_BUDG = ITM_BUDG + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
											ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
											ITM_BUDG = ITM_BUDG + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
												ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
												ITM_BUDG = ITM_BUDG + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
													ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
													ITM_BUDG = ITM_BUDG + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
														ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
														ITM_BUDG = ITM_BUDG + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
															ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
															ITM_BUDG = ITM_BUDG + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																ITM_BUDG = ITM_BUDG + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																	ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																	ITM_BUDG = ITM_BUDG + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
		}
		elseif($AMD_CLASS == 2) // PRICE IS DISABLED. QTY ONLY
		{
			// MERUBAH QTY, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MERUBAH HARGA
				$ITM_PRICE 	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_PRICE, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_PRICE	= $rowJL->ITM_PRICE;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ADDM_VOLM * $ITM_PRICE;
				echo "ITM_BUDGB = $ITM_BUDGB<br>";
			
			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADDM_JOBCOST = $ITM_BUDGB,
									ITM_BUDG = ITM_BUDG + $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					echo "DONE 2<br>";
					$s_P3 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 		= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						echo "DONE 3<br>";
						$s_P4 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 		= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							echo "DONE 4<br>";
							$s_P5 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 		= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								echo "DONE 5<br>";
								$s_P6 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 		= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									echo "DONE 6<br>";
									$s_P7 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 		= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										echo "DONE 7<br>";
										$s_P8 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 		= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											echo "DONE 8<br>";
											$s_P9 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 		= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												echo "DONE 9<br>";
												$s_P10 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 		= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADDM_VOLM - B.ADDM_VOLM) 
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
			
			$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = ADDMVOLM + $ADDM_VOLM,
							ADDMCOST = ADDMCOST + $ITM_BUDGB
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateWBSM_211229($paramWBS) 	// OK - SI MINUS
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
			$/*sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);*/
			
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

	function search_blog($title){
        //$this->db->like('JOBDESC', $title , 'both');
        $this->db->order_by('ORD_ID', 'ASC');
        return $this->db->get('tbl_joblist')->result();
    }
	
	function get_AllDataJLC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist A
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataJLDC($PRJCODE, $ITM_CODEH, $AMD_CATEG, $search) // GOOD
	{
		$s_01 	= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_01 	= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$PRJCODEVW 	= $rw_01->PRJCODEVW;
		endforeach;
		$tblName 	= "vw_joblist_detail_$PRJCODEVW";

		if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI' || $AMD_CATEG == 'OTH')
			$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH' AND REQ_VOLM > 0";
		else if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
			$ADDQRY1	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ITM_CODE != '$ITM_CODEH' AND ISLAST_BOQ = '1' AND REQ_VOLM > 0";

		if($AMD_CATEG == 'SINJ')
		{
			$sql = "tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql = "$tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		}

		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLDL($PRJCODE, $ITM_CODEH, $AMD_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$s_01 	= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_01 	= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$PRJCODEVW 	= $rw_01->PRJCODEVW;
		endforeach;
		$tblName 	= "vw_joblist_detail_$PRJCODEVW";

		if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI' || $AMD_CATEG == 'OTH')
			$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH'";
		else if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
			$ADDQRY1	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ITM_CODE != '$ITM_CODEH' AND ISLAST_BOQ = '1'";

		if($AMD_CATEG == 'SINJ')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir LIMIT 5";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT 5";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
								LIMIT $start, $length";*/
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
								LIMIT 5";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT 5";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataJLDETC($PRJCODE, $ITM_CODEH, $AMD_CATEG, $JOBID_SEL, $JOBPAR_SEL, $search) // GOOD
	{
		$s_01 	= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_01 	= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$PRJCODEVW 	= $rw_01->PRJCODEVW;
		endforeach;
		$tblName 	= "vw_joblist_detail_$PRJCODEVW";

		$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH' AND JOBCODEID != '$JOBID_SEL'";

		$sql 		= "$tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1) AND JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST_BOQ = '1'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLDETL($PRJCODE, $ITM_CODEH, $AMD_CATEG, $JOBID_SEL, $JOBPAR_SEL, $search, $length, $start, $order, $dir) // GOOD
	{
		$s_01 	= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_01 	= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$PRJCODEVW 	= $rw_01->PRJCODEVW;
		endforeach;
		$tblName 	= "vw_joblist_detail_$PRJCODEVW";

		$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH' AND JOBCODEID != '$JOBID_SEL'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1) AND JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST_BOQ = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir LIMIT 5";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1) AND JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST_BOQ = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT 5";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";*/
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1) AND JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST_BOQ = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT 5";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLAST_BOQ, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1) AND JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST_BOQ = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT 5";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMC($PRJCODE, $AMD_CATEG, $JPARENT, $search) // GOOD
	{
		if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
		{
			$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		}
		else if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI')
		{
			$sql	= "vw_tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $AMD_CATEG, $JPARENT, $search, $length, $start, $order, $dir) // GOOD
	{
		if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.STATUS = 1
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1 AND A.WBSD_STAT = 1
								AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR B.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY B.ITM_NAME $order $dir";*/


					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM vw_tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC $order $dir";
				}
				else
				{
					$sql = "SELECT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM vw_tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataITMHC($PRJCODE, $search) // GOOD
	{
		$sql	= 	"tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMHL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
							PR_VOLM, PR_AMOUNT, ADDVOLM, ADDCOST
						FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
							AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
							PR_VOLM, PR_AMOUNT, ADDVOLM, ADDCOST
						FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
							AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
							PR_VOLM, PR_AMOUNT, ADDVOLM, ADDCOST
						FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
							AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
							PR_VOLM, PR_AMOUNT, ADDVOLM, ADDCOST
						FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
							AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataSIC($PRJCODE, $search) // GOOD
	{
		$sql	= "tbl_siheader A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                    WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataSIL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            ORDER BY A.SI_CODE";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataJLHC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist A
				WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = '0'
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLHL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>