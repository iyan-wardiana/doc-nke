<?php
/*  
 * Author		= Hendar Permana
 * Create Date	= 26 Mei 2017
 * Updated		= Dian Hermanto - 11 November 2017
 * File Name	= M_purchase_po.php
 * Location		= -
*/

class M_purchase_po extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_po_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1'
					AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
					OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
				 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($PO_STAT != 0)
			$ADDQRY2 	= "AND A.PO_STAT = '$PO_STAT'";
		if($PR_NUM != '')
			$ADDQRY3 	= "AND A.PR_NUM = '$PR_NUM'";

		$sql = "tbl_po_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1' $ADDQRY1 $ADDQRY2 $ADDQRY3
					AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
					OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
				 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($PO_STAT != 0)
			$ADDQRY2 	= "AND A.PO_STAT = '$PO_STAT'";
		if($PR_NUM != '')
			$ADDQRY3 	= "AND A.PR_NUM = '$PR_NUM'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '1' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataSHC($PRJCODE, $ISCLS, $search) // GOOD
	{
		if($ISCLS == 0)		// SHOW ALL
		{
			$sql = "tbl_po_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
					 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
		}
		else				// HIDE DOKUMEN YANG REJECT, VOID, CLOSE
		{
			$sql = "tbl_po_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (1,2,3)
						AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
					 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
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
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE'
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE'
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE'
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE'
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
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
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (1,2,3)
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (1,2,3)
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (1,2,3)
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.*, B.SPLDESC
							FROM tbl_po_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (1,2,3)
								AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
								OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							 	OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_po_header A 
				WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (2,7)
					AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_po_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (2,7)
						AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_po_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (2,7)
						AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2GRP($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($PO_STAT != 0)
			$ADDQRY2 	= "AND A.PO_STAT = '$PO_STAT'";
		if($PR_NUM != '')
			$ADDQRY3 	= "AND A.PR_NUM = '$PR_NUM'";

		$sql = "tbl_po_header A 
				WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (2,7) $ADDQRY1 $ADDQRY2 $ADDQRY3
					AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2GRP($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($PO_STAT != 0)
			$ADDQRY2 	= "AND A.PO_STAT = '$PO_STAT'";
		if($PR_NUM != '')
			$ADDQRY3 	= "AND A.PR_NUM = '$PR_NUM'";

		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_po_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (2,7) $ADDQRY1 $ADDQRY2 $ADDQRY3
						AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_po_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (2,7) $ADDQRY1 $ADDQRY2 $ADDQRY3
						AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rowsPO($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_po_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_po_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PO_CODE LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PO_DATE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_PO($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_TYPE, A.PO_CAT, A.PO_DATE, A.PRJCODE, A.SPLCODE,
						A.PR_NUM, A.PR_CODE, A.PO_CURR, A.PO_CURRATE, A.PO_TAXCURR, A.PO_TAXRATE, A.PO_TOTCOST,
						A.PO_CREATER, A.PO_PLANIR, A.PO_TERM, A.PO_PAYTYPE, A.PO_STAT, A.PO_INVSTAT, A.ISDIRECT,
						A.PO_NOTES, A.PO_MEMO, A.JOBCODE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PO_RECEIVLOC, A.PO_RECEIVCP,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_po_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_TYPE, A.PO_CAT, A.PO_DATE, A.PRJCODE, A.SPLCODE,
						A.PR_NUM, A.PR_CODE, A.PO_CURR, A.PO_CURRATE, A.PO_TAXCURR, A.PO_TAXRATE, A.PO_TOTCOST,
						A.PO_CREATER, A.PO_PLANIR, A.PO_TERM, A.PO_PAYTYPE, A.PO_STAT, A.PO_INVSTAT, A.ISDIRECT,
						A.PO_NOTES, A.PO_MEMO, A.JOBCODE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PO_RECEIVLOC, A.PO_RECEIVCP,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_po_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PO_CODE LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PO_DATE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function get_AllDataITMUC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A
				WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U') AND GROUP_CATEG IN ('UA') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMUL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U') AND GROUP_CATEG IN ('UA') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U') AND GROUP_CATEG IN ('UA') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U') AND GROUP_CATEG IN ('UA') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U') AND GROUP_CATEG IN ('UA') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataITMSC($PRJCODE, $search) // G
	{
		$sql = "tbl_item A
				WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
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
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item A LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item A LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item A LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item A LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rowsVend() // OK
	{
		$sql = "tbl_supplier WHERE SPLSTAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewvendor() // OK
	{
		/*$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier WHERE SPLSTAT = '1'
				ORDER BY SPLDESC LIMIT 400";*/
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier WHERE SPLSTAT = '1' ORDER BY SPLDESC ASC";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function get_Rate($PO_CURR) // OK
	{
		$RATE		= 1;
		$sqlRate 	= "SELECT RATE FROM tbl_currate WHERE CURR1 = '$PO_CURR' AND CURR2 = 'IDR'";
		$resRate	= $this->db->query($sqlRate)->result();
		foreach($resRate as $rowRate) :
			$RATE = $rowRate->RATE;		
		endforeach;
		return $RATE;
	}
	
	function get_IR($PO_NUM) // G
	{
		$sqlIR 	= "tbl_ir_header WHERE PO_NUM = '$PO_NUM' AND IR_STAT != 5";
		$resIR	= $this->db->count_all($sqlIR);
		return $resIR;
	}
	
	function add($AddPO) // OK
	{
		$this->db->insert('tbl_po_header', $AddPO);
	}
	
	function updateDet($PO_NUM, $PRJCODE, $PO_DATE) // OK
	{
		$sql = "UPDATE tbl_po_detail SET PRJCODE = '$PRJCODE', PO_DATE = '$PO_DATE' WHERE PO_NUM = '$PO_NUM'";
		return $this->db->query($sql);
	}
	
	function get_PO_by_number($PO_NUM) // OK
	{			
		$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_TYPE, A.PO_CAT, A.PO_DATE, A.PO_DUED, A.PRJCODE, A.SPLCODE, 
					A.PR_NUM, A.PO_CURR, A.PO_CURRATE, A.PO_PAYTYPE, A.PO_TENOR, A.ISDIRECT, A.PO_TAXAMN,
					A.PO_PLANIR, A.PO_NOTES, A.PO_NOTES1, A.PO_MEMO, A.PO_STAT, A.PO_TOTCOST, A.PO_TERM,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PO_RECEIVLOC, A.PO_RECEIVCP, A.PO_SENTROLES, A.PO_PAYNOTES, A.isPrint,
					A.PO_REFRENS, A.PO_CONTRNO, C.PR_CODE, A.PO_PAYNOTES, A.DEPCODE, A.PO_DPPER, A.PO_DPVAL,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_po_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					LEFT JOIN tbl_pr_header C ON A.PR_NUM = C.PR_NUM
				WHERE A.PO_NUM = '$PO_NUM'";
		return $this->db->query($sql);
	}
	
	function get_ro_by_number($PO_NUM) // OK
	{			
		$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_TYPE, A.PO_CAT, A.PO_DATE, A.PO_DUED, A.PRJCODE, A.SPLCODE, 
					A.PR_NUM, A.PO_CURR, A.PO_CURRATE, A.PO_PAYTYPE, A.PO_TENOR, A.ISDIRECT, A.PO_TAXAMN,
					A.PO_PLANIR, A.PO_NOTES, A.PO_NOTES1, A.PO_MEMO, A.PO_STAT, A.PO_TOTCOST, A.PO_TERM,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PO_RECEIVLOC, A.PO_RECEIVCP, A.PO_SENTROLES, 
					A.PO_REFRENS, A.PO_CONTRNO, C.WO_CODE,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_po_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					LEFT JOIN tbl_wo_header C ON A.PR_NUM = C.WO_NUM
				WHERE A.PO_NUM = '$PO_NUM'";
		return $this->db->query($sql);
	}
	
	function updatePO($PO_NUM, $updPO) // OK
	{
		$this->db->where('PO_NUM', $PO_NUM);
		$this->db->update('tbl_po_header', $updPO);
	}
	
	function updatePOH($PO_NUM, $updPOH) // OK
	{
		$this->db->where('PO_NUM', $PO_NUM);
		$this->db->update('tbl_po_header', $updPOH);
	}
	
	function deletePODetail($PO_NUM) // OK
	{
		$this->db->where('PO_NUM', $PO_NUM);
		$this->db->delete('tbl_po_detail');
	}
	
	function updatePOInb($PO_NUM, $updPO) // OK
	{
		$this->db->where('PO_NUM', $PO_NUM);
		$this->db->update('tbl_po_header', $updPO);
	}
	
	function updatePODet($PO_NUM, $PRJCODE, $PR_NUM, $ISDIRECT) // OK
	{				
		$sqlGetPO	= "SELECT PO_NUM, PR_NUM, JOBCODEDET, JOBCODEID, PRD_ID, ITM_CODE, PO_VOLM, PO_PRICE
						FROM tbl_po_detail
						WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$PO_NUM 		= $rowPO->PO_NUM;
			$PR_NUM 		= $rowPO->PR_NUM;
			$JOBCODEDET		= $rowPO->JOBCODEDET;
			$JOBCODEID		= $rowPO->JOBCODEID;
			$PRD_ID			= $rowPO->PRD_ID;
			$ITM_CODE		= $rowPO->ITM_CODE;
			$PO_VOLM_NOW	= $rowPO->PO_VOLM;
			$PO_PRICE_NOW	= $rowPO->PO_PRICE;
			$PO_COST_NOW	= $PO_VOLM_NOW * $PO_PRICE_NOW;
			
			$ITM_CODE_H	= $ITM_CODE;
			$ITM_TYPE	= 'PRM';
			$sqlITYPE	= "SELECT ITM_CODE_H, ITM_TYPE FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITYPE	= $this->db->query($sqlITYPE)->result();
			foreach($resITYPE as $rowITYPE) :
				$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
			endforeach;
			if($ITM_TYPE == 'SUBS')
			{
				$ITM_CODE_H	= $rowITYPE->ITM_CODE_H;
				$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
			}
			
			if($ISDIRECT == 0)
			{
				// UPDATE PR DETAIL
				$PO_VOLM	= 0;
				$PO_AMOUNT	= 0;			
				$sqlGetPRD	= "SELECT PO_VOLM, PO_AMOUNT FROM tbl_pr_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
									AND PR_NUM = '$PR_NUM' AND PR_ID = $PRD_ID";									
				$resGetPRD	= $this->db->query($sqlGetPRD)->result();
				foreach($resGetPRD as $rowPRD) :
					$PO_VOLM 	= $rowPRD->PO_VOLM;
					$PO_AMOUNT 	= $rowPRD->PO_AMOUNT;
				endforeach;
				if($PO_VOLM == '')
					$PO_VOLM = 0;
				if($PO_AMOUNT == '')
					$PO_AMOUNT = 0;
				
				$totPOQty		= $PO_VOLM + $PO_VOLM_NOW;
				$totPOAmount	= $PO_AMOUNT + $PO_COST_NOW;
				/*$sqlUpd			= "UPDATE tbl_pr_detail SET PO_VOLM = $totPOQty, PO_AMOUNT = $totPOAmount
									WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
										AND PR_NUM = '$PR_NUM'";*/
				$sqlUpd			= "UPDATE tbl_pr_detail SET PO_VOLM = $totPOQty, PO_AMOUNT = $totPOAmount
									WHERE PR_ID = $PRD_ID";
				$this->db->query($sqlUpd);
				
				// UPDATE JOB HEADER
				$PO_VOLM	= 0;
				$PO_AMOUNT	= 0;
				$sqlGetJD	= "SELECT PO_VOLM, PO_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				$resGetJD	= $this->db->query($sqlGetJD)->result();
				foreach($resGetJD as $rowJD) :
					$PO_VOLM 	= $rowJD->PO_VOLM;
					$PO_AMOUNT	= $rowJD->PO_AMOUNT;
				endforeach;
				if($PO_VOLM == '')
					$PO_VOLM = 0;
				if($PO_AMOUNT == '')
					$PO_AMOUNT = 0;
				
				$totJDQty		= $PO_VOLM + $PO_VOLM_NOW;
				$totJDAmount	= $PO_AMOUNT + $PO_COST_NOW;
				$sqlUpdJD		= "UPDATE tbl_joblist_detail SET PO_VOLM = $totJDQty, PO_AMOUNT = $totJDAmount
									WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($sqlUpdJD); HIDDEN BY DIAN 10 JULI 2018
				
				// UPDATE JOB DETAIL PER ITEM
				$PO_VOLM1	= 0;
				$PO_AMOUNT1	= 0;
				$sqlGetJD1	= "SELECT PO_VOLM, PO_AMOUNT FROM tbl_joblist_detail
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE_H' AND JOBCODEID = '$JOBCODEID'";
				$resGetJD1	= $this->db->query($sqlGetJD1)->result();
				foreach($resGetJD1 as $rowJD1) :
					$PO_VOLM1 	= $rowJD1->PO_VOLM;
					$PO_AMOUNT1	= $rowJD1->PO_AMOUNT;
				endforeach;
				if($PO_VOLM1 == '')
					$PO_VOLM1 = 0;
				if($PO_AMOUNT1 == '')
					$PO_AMOUNT1 = 0;
				
				$totJDQty1		= $PO_VOLM1 + $PO_VOLM_NOW;
				$totJDAmount1	= $PO_AMOUNT1 + $PO_COST_NOW;
				$sqlUpdJD1		= "UPDATE tbl_joblist_detail SET PO_VOLM = $totJDQty1, PO_AMOUNT = $totJDAmount1
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE_H' AND JOBCODEID = '$JOBCODEID'";
				$this->db->query($sqlUpdJD1);
			}
			
			// UPDATE TBL_ITEM
			$PO_VOLM1		= 0;
			$PO_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT PO_VOLM, PO_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$PO_VOLM1 	= $rowJD1->PO_VOLM;
				$PO_AMOUNT1	= $rowJD1->PO_AMOUNT;
			endforeach;
			if($PO_VOLM1 == '')
				$PO_VOLM1 = 0;
			if($PO_AMOUNT1 == '')
				$PO_AMOUNT1 = 0;
				
			$totPOQty1	= $PO_VOLM1 + $PO_VOLM_NOW;
			$totPOAmn1	= $PO_AMOUNT1 + $PO_COST_NOW;
			$sqlUpd2	= "UPDATE tbl_item SET PO_VOLM = $totPOQty1, PO_AMOUNT = $totPOAmn1 WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
		
		if($ISDIRECT == 0)
		{
			// CEK TOTAL PR AND PO			
			$TOT_PRQTY 		= 0;
			$TOT_PRAMOUNT	= 0;
			$TOT_POQTY 		= 0;
			$TOT_POAMOUNT 	= 0;	
			$sqlGetPRCV		= "SELECT SUM(PR_VOLM) AS TOT_PRQTY, SUM(PR_TOTAL) AS TOT_PRAMOUNT, SUM(PO_VOLM) AS TOT_POQTY,
									SUM(PO_AMOUNT) AS TOT_POAMOUNT
								FROM tbl_pr_detail
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPRCV	= $this->db->query($sqlGetPRCV)->result();
			foreach($resGetPRCV as $rowPRCV) :
				$TOT_PRQTY 		= $rowPRCV->TOT_PRQTY;
				$TOT_PRAMOUNT	= $rowPRCV->TOT_PRAMOUNT;
				$TOT_POQTY 		= $rowPRCV->TOT_POQTY;
				$TOT_POAMOUNT 	= $rowPRCV->TOT_POAMOUNT;			
			endforeach;
			/*//if(($TOT_PRQTY == $TOT_POQTY) && $TOT_PRQTY > 0)
			if($TOT_POQTY >= $TOT_PRQTY)
			{
				$sqlUpdPR	= "UPDATE tbl_pr_header SET PR_STAT = 6, PR_ISCLOSE = 1, STATDESC = 'Close', STATCOL = 'info'
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpdPR);
			}
			//if(($TOT_PRAMOUNT == $TOT_POAMOUNT) && $TOT_PRAMOUNT > 0)
			// NILAI TIDAK DIJADIKAN ACUAN UNTUK CLOSE PR, HANYA DARI QTY. ON 11 JAN 19
			if($TOT_POAMOUNT >= $TOT_PRAMOUNT)
			{
				$sqlUpdPR	= "UPDATE tbl_pr_header SET PR_STAT = 6, PR_ISCLOSE = 1, STATDESC = 'Close', STATCOL = 'info'
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($sqlUpdPR);
			}*/
			
			$PRISCLOSE 		= 1;
			$sqlGetPRDet	= "SELECT ITM_CODE, PR_VOLM, PR_VOLM * PR_PRICE AS TOT_PR_AMOUNT, PO_VOLM, PO_AMOUNT AS TOT_PO_AMOUNT 
								FROM tbl_pr_detail
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPRDet	= $this->db->query($sqlGetPRDet)->result();
			foreach($resGetPRDet as $rowPRDet) :
				$ITM_CODE 		= $rowPRDet->ITM_CODE;
				$PR_VOLM 		= $rowPRDet->PR_VOLM;
				$TOT_PRAMN		= $rowPRDet->TOT_PR_AMOUNT;
				$PO_VOLM 		= $rowPRDet->PO_VOLM;
				$TOT_POAMN		= $rowPRDet->TOT_PO_AMOUNT;

				if($PO_VOLM >= $PR_VOLM)
				{
					$sqlUpdPRD	= "UPDATE tbl_pr_detail SET ISCLOSE = 1 WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}
				if($TOT_POAMN >= $TOT_PRAMN)
				{
					$sqlUpdPRD	= "UPDATE tbl_pr_detail SET ISCLOSE = 1 WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}
				if($PR_VOLM > $PO_VOLM)
				{
					$PRISCLOSE 	= 0;
				}
			endforeach;			
			
			if($PRISCLOSE == 1)
			{
				$sqlUpdPR	= "UPDATE tbl_pr_header SET PR_STAT = 6, PR_ISCLOSE = 1, STATDESC = 'Close', STATCOL = 'info'
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpdPR);
			}

			// SETING
			$TBL_NAME		= "tbl_pr_header";
			$STAT_NAME		= "PR_STAT";
			$FIELD_NM_A		= "TOT_REQ_A";
			$FIELD_NM_R		= "TOT_REQ_R";
			$FIELD_NM_RJ	= "TOT_REQ_RJ";
			$FIELD_NM_CL	= "TOT_REQ_CL";
			
			// SUM TOTAL APPROVE = 3
			$TOTAL_APP	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 3";
			$TOTAL_APP	= $this->db->count_all($selTOTA);
			
			// SUM TOTAL REVISE = 4
			$TOTAL_REV	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 4";
			$TOTAL_REV	= $this->db->count_all($selTOTA);
			
			// SUM TOTAL REJECT = 5
			$TOTAL_REJ	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 5";
			$TOTAL_REJ	= $this->db->count_all($selTOTA);
			
			// SUM TOTAL CLOSE = 6
			$TOTAL_CLO	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 6";
			$TOTAL_CLO	= $this->db->count_all($selTOTA);
			
			// UPDATE DASHBOARD
			$sqlUpd1Dash		= "UPDATE tbl_dash_transac SET $FIELD_NM_A = $TOTAL_APP, $FIELD_NM_R = $TOTAL_REV,
										$FIELD_NM_RJ = $TOTAL_REJ, $FIELD_NM_CL = $TOTAL_CLO
									WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($sqlUpd1Dash);
		}
		else // IF DIRECT
		{
			$sqlGetPRDet	= "SELECT ITM_CODE, ITM_VOLM, ITM_VOLM * ITM_PRICE AS TOT_AMOUNT, PO_VOLM, PO_AMOUNT FROM tbl_joblist_detail
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetPRDet	= $this->db->query($sqlGetPRDet)->result();
			foreach($resGetPRDet as $rowPRDet) :
				$ITM_CODE 		= $rowPRDet->ITM_CODE;
				$ITM_VOLM 		= $rowPRDet->ITM_VOLM;
				$TOT_AMOUNT		= $rowPRDet->TOT_AMOUNT;
				$PO_VOLM 		= $rowPRDet->PO_VOLM;
				$PO_AMOUNT		= $rowPRDet->PO_AMOUNT;
				if(($ITM_VOLM == $PO_VOLM) && $ITM_VOLM > 0)
				{
					$sqlUpdPRD	= "UPDATE tbl_joblist_detail SET ISCLOSE = 1 WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}
				if(($TOT_AMOUNT == $PO_AMOUNT) && TOT_AMOUNT > 0)
				{
					$sqlUpdPRD	= "UPDATE tbl_joblist_detail SET ISCLOSE = 1 WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}
			endforeach;
		}
	}
	
	function count_all_num_rowsAllItem($PRJCODE) // OK
	{
		if($PRJCODE == 'KTR')
		{
			$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
		}		
		return $this->db->count_all($sql);
	}
	
	function viewAllItemMatBudget($PRJCODE) // OK
	{
		if($PRJCODE == 'KTR')
		{
			/*$sql		= "SELECT DISTINCT Z.PRJCODE, Z.ITM_CODE, Z.ITM_CATEG, Z.ITM_NAME, Z.ITM_DESC, Z.ITM_UNIT,
							Z.ITM_VOLM, Z.ITM_PRICE, Z.ITM_REMQTY, Z.ITM_LASTP, B.Unit_Type_Name
							FROM tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.unit_type_code
							WHERE Z.PRJCODE = '$PRJCODE'
							ORDER BY Z.ITM_NAME";*/
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE,
								A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, 
								A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM,
								A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, 
								A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_TYPE, B.ITM_NAME
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','I','T')
							UNION ALL
							SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLMBG AS ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, PO_AMOUNT, IR_VOLM, 
								IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, ITM_VOLM AS ITM_STOCK, 
								ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND ITM_TYPE = 'SUBS'";
		}
		else
		{
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE,
								A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, 
								A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM,
								A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, 
								A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_TYPE, B.ITM_NAME
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','I','T')
							UNION ALL
							SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLMBG AS ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, PO_AMOUNT, IR_VOLM, 
								IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, ITM_VOLM AS ITM_STOCK, 
								ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND ITM_TYPE = 'SUBS'";
		}
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsPOInb($PRJCODE, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "tbl_po_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PO_STAT IN (2,7)";	// Only Confirm Stat (2)
		}
		else
		{
			$sql = "tbl_po_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PO_STAT IN (2,7)
						AND (PO_CODE LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PO_DATE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_POInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PO_NUM, A.PR_NUM, A.PR_CODE, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.SPLCODE, A.JOBCODE,
						A.PO_STAT, PO_MEMO, A.PO_CREATER, A.PO_TOTCOST, A.PO_TERM, A.PO_PLANIR, A.PO_TERM, A.PO_TYPE, 
						A.PO_CAT, A.PO_CURR, A.PO_CURRATE, A.PO_PAYTYPE, A.PO_TENOR, A.PO_NOTES, A.ISDIRECT,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PO_RECEIVLOC, A.PO_RECEIVCP,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_po_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PO_STAT IN (2,7) LIMIT $start, $end";	// Only Confirm Stat (2)
		}
		else
		{
			$sql = "SELECT A.PO_NUM, A.PR_NUM, A.PR_CODE, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.SPLCODE, A.JOBCODE,
						A.PO_STAT, PO_MEMO, A.PO_CREATER, A.PO_TOTCOST, A.PO_TERM, A.PO_PLANIR, A.PO_TERM, A.PO_TYPE, 
						A.PO_CAT, A.PO_CURR, A.PO_CURRATE, A.PO_PAYTYPE, A.PO_TENOR, A.PO_NOTES, A.ISDIRECT,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PO_RECEIVLOC, A.PO_RECEIVCP,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_po_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND PO_STAT IN (2,7)
						AND (PO_CODE LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PO_DATE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function count_all_IR($PO_NUM) // OK
	{
		$sql	= "tbl_ir_header A
						INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
							AND B.PO_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PO_NUM = '$PO_NUM' AND A.IR_STAT IN (3,6)";
		return $this->db->count_all($sql);
	}
	
	function get_all_IR($PO_NUM) // OK
	{
		$sql 	= "SELECT A.IR_NUM, A.IR_DATE, A.IR_DUEDATE, A.SPLCODE, C.SPLDESC
					FROM tbl_ir_header A
						INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
							AND B.PO_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PO_NUM = '$PO_NUM' AND A.IR_STAT IN (3,6)";
		return $this->db->query($sql);
	}
	
	function getMyMaxLimit($MenuCode, $DefEmp_ID)
	{
		$APPLIMIT_1		= 0;
		$APPLIMIT_2		= 0;
		$APPLIMIT_3		= 0;
		$APPLIMIT_4		= 0;
		$APPLIMIT_5		= 0;
		$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'";
		$resAPP	= $this->db->query($sqlAPP)->result();
		foreach($resAPP as $rowAPP) :
			$MAX_STEP		= $rowAPP->MAX_STEP;			
			$APPROVER_1		= $rowAPP->APPROVER_1;
			$APPROVER_2		= $rowAPP->APPROVER_2;
			$APPROVER_3		= $rowAPP->APPROVER_3;
			$APPROVER_4		= $rowAPP->APPROVER_4;
			$APPROVER_5		= $rowAPP->APPROVER_5;
			
			$APPLIMIT_1		= $rowAPP->APPLIMIT_1;
			$APPLIMIT_2		= $rowAPP->APPLIMIT_2;
			$APPLIMIT_3		= $rowAPP->APPLIMIT_3;
			$APPLIMIT_4		= $rowAPP->APPLIMIT_4;
			$APPLIMIT_5		= $rowAPP->APPLIMIT_5;
		endforeach;
		
		$myMaxLIMIT	= 0;
		$sqlCAPP_1	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'";
		$resCAPP_1	= $this->db->count_all($sqlCAPP_1);
		if($resCAPP_1 > 0)
		{
			$sqlAPP_1	= "SELECT APPLIMIT_1 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'";
			$resAPP_1	= $this->db->query($sqlAPP_1)->result();
			foreach($resAPP_1 as $rowAPP_1) :
				$APPLIMIT_1	= $rowAPP_1->APPLIMIT_1;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_1;
			$RangeApp_a	= 0;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_2	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_2 = '$DefEmp_ID'";
		$resCAPP_2	= $this->db->count_all($sqlCAPP_2);
		if($resCAPP_2 > 0)
		{
			$sqlAPP_2	= "SELECT APPLIMIT_2 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_2 = '$DefEmp_ID'";
			$resAPP_2	= $this->db->query($sqlAPP_2)->result();
			foreach($resAPP_2 as $rowAPP_2) :
				$APPLIMIT_2	= $rowAPP_2->APPLIMIT_2;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_2;
			$RangeApp_a	= $APPLIMIT_1;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_3	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_3 = '$DefEmp_ID'";
		$resCAPP_3	= $this->db->count_all($sqlCAPP_3);
		if($resCAPP_3 > 0)
		{
			$sqlAPP_3	= "SELECT APPLIMIT_3 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_3 = '$DefEmp_ID'";
			$resAPP_3	= $this->db->query($sqlAPP_3)->result();
			foreach($resAPP_3 as $rowAPP_3) :
				$APPLIMIT_3	= $rowAPP_3->APPLIMIT_3;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_3;
			$RangeApp_a	= $APPLIMIT_2;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_4	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_4 = '$DefEmp_ID'";
		$resCAPP_4	= $this->db->count_all($sqlCAPP_4);
		if($resCAPP_4 > 0)
		{
			$sqlAPP_4	= "SELECT APPLIMIT_4 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_4 = '$DefEmp_ID'";
			$resAPP_4	= $this->db->query($sqlAPP_4)->result();
			foreach($resAPP_4 as $rowAPP_4) :
				$APPLIMIT_4	= $rowAPP_4->APPLIMIT_4;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_4;
			$RangeApp_a	= $APPLIMIT_3;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_5	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_5 = '$DefEmp_ID'";
		$resCAPP_5	= $this->db->count_all($sqlCAPP_5);
		if($resCAPP_5 > 0)
		{
			$sqlAPP_5	= "SELECT APPLIMIT_5 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_5 = '$DefEmp_ID'";
			$resAPP_5	= $this->db->query($sqlAPP_5)->result();
			foreach($resAPP_5 as $rowAPP_5) :
				$APPLIMIT_5	= $rowAPP_5->APPLIMIT_5;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_5;
			$RangeApp_a	= $APPLIMIT_4;
			$RangeApp_b	= $myMaxLIMIT;
		}
		$RangeApp		= "$RangeApp_a~$RangeApp_b";
		
		return $RangeApp;
	}
	
	function updateVolBud($PO_NUM, $PRJCODE, $ITM_CODE) // G
	{
		$PR_VOLM 	= 0;
		$PR_AMOUNT 	= 0;
		$IR_VOLM 	= 0;
		$IR_AMOUNT 	= 0;			
		$sqlGetPO	= "SELECT JOBCODEID, PR_VOLM, PR_AMOUNT, PO_VOLM, PO_COST, IR_VOLM, IR_AMOUNT
						FROM tbl_po_detail
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$JOBCODEID 	= $rowPO->JOBCODEID;
			$PR_VOLM 	= $rowPO->PR_VOLM;
			$PR_AMOUNT 	= $rowPO->PR_AMOUNT;
			$PO_VOLM 	= $rowPO->PO_VOLM;
			$PO_AMOUNT 	= $rowPO->PO_COST;
			$IR_VOLM 	= $rowPO->IR_VOLM;
			$IR_AMOUNT 	= $rowPO->IR_AMOUNT;
		endforeach;
		
		$REM_POVOLM		= $PO_VOLM - $IR_VOLM;
		$REM_POAMOUNT	= $PO_AMOUNT - $IR_AMOUNT;
		
		/*$sqlGetJDI		= "SELECT PO_VOLM, PO_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetJDI		= $this->db->query($sqlGetJDI)->result();
		foreach($resGetJDI as $rowJDI) :
			$PO_VOLMI 	= $rowJDI->PO_VOLM;
			$PO_AMOUNTI	= $rowJDI->PO_AMOUNT;
		endforeach;
		if($PO_VOLMI == '')
			$PO_VOLMI = 0;
		if($PO_AMOUNTI == '')
			$PO_AMOUNTI = 0;
		
		$totREMQty	= $PO_VOLMI - $REM_POVOLM;
		$totREMAmn	= $PO_AMOUNTI - $REM_POAMOUNT;*/
			
		
		// UPDATE TO TBL ITEM N JOB
		$sqlUpd		= "UPDATE tbl_item SET PO_VOLM = PO_VOLM - $REM_POVOLM, PO_AMOUNT = PO_AMOUNT - $REM_POAMOUNT
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpd);
		
		$sqlUpd		= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM - $REM_POVOLM, PO_AMOUNT = PO_AMOUNT - $REM_POAMOUNT
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'";
		$this->db->query($sqlUpd);
	}
	
	function updREJECT($PO_NUM, $PRJCODE) // G
	{
		$sqlPO	= "SELECT PR_NUM, JOBCODEID, ITM_CODE, PO_VOLM, PO_COST
					FROM tbl_po_detail WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
		$resPO	= $this->db->query($sqlPO)->result();
		foreach($resPO as $rowPO) :
			$PR_NUM		= $rowPO->PR_NUM;
			$JOBCODEID	= $rowPO->JOBCODEID;
			$ITM_CODE	= $rowPO->ITM_CODE;
			$PO_VOLM	= $rowPO->PO_VOLM;
			$PO_COST	= $rowPO->PO_COST;
			
			// Kembalikan di tabel PR
			$sqlPR	= "UPDATE tbl_pr_detail SET PO_VOLM = PO_VOLM - $PO_VOLM, PO_AMOUNT = PO_AMOUNT - $PO_COST
						WHERE PR_NUM = '$PR_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlPR);
			
			// Kembalikan di tabel JOBLIST
			$sqlJLD	= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM - $PO_VOLM, PO_AMOUNT = PO_AMOUNT - $PO_COST
						WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlJLD);
			
			// Kembalikan di tabel MASTER ITEM
			$sqlITM	= "UPDATE tbl_item SET PO_VOLM = PO_VOLM - $PO_VOLM, PO_AMOUNT = PO_AMOUNT - $PO_COST
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlITM);
		endforeach;
		
		// UPDATE REQUEST STATUS
			$sqlJLD	= "UPDATE tbl_pr_header SET PR_STAT = 3, PR_ISCLOSE = 0, STATDESC = 'Approved', STATCOL = 'success'
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlJLD);
	}
	
	function deletePOH($PO_NUM) // G
	{
		$this->db->where('PO_NUM', $PO_NUM);
		$this->db->delete('tbl_po_header');
	}
	
	function deletePOD($PO_NUM) // G
	{
		$this->db->where('PO_NUM', $PO_NUM);
		$this->db->delete('tbl_po_detail');
	}
	
	function get_AllDataPR($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_pr_header A
					LEFT JOIN  tbl_employee B ON A.PR_CREATER = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE A.PR_STAT = 3 AND A.PRJCODE = '$PRJCODE'
					AND (PR_CODE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!' 
					OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_RECEIPTD LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPRL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PR_CREATER, A.PR_APPROVER, 
							A.JOBCODE, A.PR_NOTE, A.PR_STAT, A.PR_MEMO, A.PR_PLAN_IR,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS reQName,
							C.proj_Number, C.PRJCODE, C.PRJNAME
						FROM tbl_pr_header A
							LEFT JOIN  tbl_employee B ON A.PR_CREATER = B.Emp_ID
							INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						WHERE A.PR_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (PR_CODE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_RECEIPTD LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PR_CREATER, A.PR_APPROVER, 
							A.JOBCODE, A.PR_NOTE, A.PR_STAT, A.PR_MEMO, A.PR_PLAN_IR,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS reQName,
							C.proj_Number, C.PRJCODE, C.PRJNAME
						FROM tbl_pr_header A
							LEFT JOIN  tbl_employee B ON A.PR_CREATER = B.Emp_ID
							INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						WHERE A.PR_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (PR_CODE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_RECEIPTD LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PR_CREATER, A.PR_APPROVER, 
							A.JOBCODE, A.PR_NOTE, A.PR_STAT, A.PR_MEMO, A.PR_PLAN_IR,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS reQName,
							C.proj_Number, C.PRJCODE, C.PRJNAME
						FROM tbl_pr_header A
							LEFT JOIN  tbl_employee B ON A.PR_CREATER = B.Emp_ID
							INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						WHERE A.PR_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (PR_CODE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_RECEIPTD LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PR_CREATER, A.PR_APPROVER, 
							A.JOBCODE, A.PR_NOTE, A.PR_STAT, A.PR_MEMO, A.PR_PLAN_IR,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS reQName,
							C.proj_Number, C.PRJCODE, C.PRJNAME
						FROM tbl_pr_header A
							LEFT JOIN  tbl_employee B ON A.PR_CREATER = B.Emp_ID
							INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						WHERE A.PR_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (PR_CODE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_RECEIPTD LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllData_ovhC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_po_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
					AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
					OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
				 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllData_ovhL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRP_ovhC($PRJCODE, $SPLCODE, $PO_STAT, $PO_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($PO_STAT != 0)
			$ADDQRY2 	= "AND A.PO_STAT = '$PO_STAT'";
		if($PO_CATEG != '')
			$ADDQRY3 	= "AND A.PO_CATEG = '$PO_CATEG'";

		$sql = "tbl_po_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
					AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
					OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
				 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRP_ovhL($PRJCODE, $SPLCODE, $PO_STAT, $PO_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($PO_STAT != 0)
			$ADDQRY2 	= "AND A.PO_STAT = '$PO_STAT'";
		if($PO_CATEG != '')
			$ADDQRY3 	= "AND A.PO_CATEG = '$PO_CATEG'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_CAT = '2'
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PO_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataITMC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A
				WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
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
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>