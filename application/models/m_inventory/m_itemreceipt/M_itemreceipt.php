<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= M_itemreceipt.php
 * Location		= -
*/

class M_itemreceipt extends CI_Model
{	
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_ir_header
					WHERE PRJCODE = '$PRJCODE'
						AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
						OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE'
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE'
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE'
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE'
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $SPLCODE, $PO_NUM, $IR_STAT, $IR_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND SPLCODE = '$SPLCODE'";
		if($IR_STAT != 0)
			$ADDQRY2 	= "AND IR_STAT = '$IR_STAT'";
		if($PO_NUM != '')
			$ADDQRY3 	= "AND PO_NUM = '$PO_NUM'";

		$sql = "tbl_ir_header
					WHERE PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
						AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
						OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $SPLCODE, $PO_NUM, $IR_STAT, $IR_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND SPLCODE = '$SPLCODE'";
		if($IR_STAT != 0)
			$ADDQRY2 	= "AND IR_STAT = '$IR_STAT'";
		if($PO_NUM != '')
			$ADDQRY3 	= "AND PO_NUM = '$PO_NUM'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_ir_header
					WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7)
						AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
						OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		/*if($length == -1)
		{
			$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
						REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
					FROM tbl_ir_header
					WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7)
						AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
						OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
						REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
					FROM tbl_ir_header
					WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7)
						AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
						OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			return $this->db->query($sql);
		}*/

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7)
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7)
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7)
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7)
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2GRP($PRJCODE, $SPLCODE, $PO_NUM, $IR_STAT, $IR_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND SPLCODE = '$SPLCODE'";
		if($IR_STAT != 0)
			$ADDQRY2 	= "AND IR_STAT = '$IR_STAT'";
		if($PO_NUM != '')
			$ADDQRY3 	= "AND PO_NUM = '$PO_NUM'";

		$sql = "tbl_ir_header
					WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7) $ADDQRY1 $ADDQRY3
						AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
						OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2GRP($PRJCODE, $SPLCODE, $PO_NUM, $IR_STAT, $IR_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND SPLCODE = '$SPLCODE'";
		if($IR_STAT != 0)
			$ADDQRY2 	= "AND IR_STAT = '$IR_STAT'";
		if($PO_NUM != '')
			$ADDQRY3 	= "AND PO_NUM = '$PO_NUM'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7) $ADDQRY1 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7) $ADDQRY1 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7) $ADDQRY1 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
							REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
						FROM tbl_ir_header
						WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (2,7) $ADDQRY1 $ADDQRY3
							AND (IR_CODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR IR_NOTE LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_project() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
							A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
							A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
						FROM tbl_project A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows($PRJCODE, $key) // U
	{
		if($key == '')
		{
			$sql = "tbl_ir_header WHERE PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_ir_header WHERE PRJCODE = '$PRJCODE'
						AND (IR_CODE LIKE '%$key%' ESCAPE '!' OR PO_CODE LIKE '%$key%' ESCAPE '!' 
						OR IR_DATE LIKE '%$key%' ESCAPE '!' OR IR_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_IR($PRJCODE, $start, $end, $key) // U
	{
		if($key == '')
		{
			$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
						REVMEMO, STATDESC, STATCOL, CREATERNM, IR_LOC
					FROM tbl_ir_header
					WHERE PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
						REVMEMO, STATDESC, STATCOL, CREATERNM, IR_LOC
					FROM tbl_ir_header
					WHERE PRJCODE = '$PRJCODE'
						AND (IR_CODE LIKE '%$key%' ESCAPE '!' OR PO_CODE LIKE '%$key%' ESCAPE '!' 
						OR SPLDESC LIKE '%$key%' ESCAPE '!' OR IR_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsVend() // OK
	{
		$sql = "tbl_supplier WHERE SPLSTAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewvendor() // OK
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier WHERE SPLSTAT = '1'
				ORDER BY SPLDESC ASC";
		return $this->db->query($sql);
	}
	
	function count_all_Cust() // OK
	{
		$sql = "tbl_so_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE A.SO_STAT IN(3,6)";
		return $this->db->count_all($sql);
	}
	
	function viewcustomer() // OK
	{
		$sql = "SELECT A.CUST_CODE, B.CUST_DESC, B.CUST_ADD1
				FROM tbl_so_header A
					INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
				WHERE A.SO_STAT IN(3,6)
					ORDER BY B.CUST_DESC ASC";
		return $this->db->query($sql);
	}
	
	function count_all_Item($PRJCODE) // U
	{
		/*$sql		= "tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.UMCODE
						WHERE Z.PRJCODE = '$PRJCODE'
							AND Z.ISPART = 1 OR Z.ISFUEL = 1 OR Z.ISLUBRIC = 1 OR Z.ISFASTM = 1 OR Z.ISMTRL = 1";*/
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function count_allItem($PRJCODE) // U
	{
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')";
		return $this->db->count_all($sql);
	}
	
	function viewAllItem($PRJCODE) // U
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE,
							A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_STOCK, 
							A.ITM_BUDG, B.ACC_ID, B.ITM_NAME
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')";
		return $this->db->query($sql);
	}
	
	function count_allItemSubs($PRJCODE) // U
	{
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.JOBCODEID = B.JOBCODEID
							AND B.ITM_CODE_H = A.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_TYPE = 'SUBS'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemSubs($PRJCODE) // U
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, B.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE,
							A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_STOCK, 
							A.ITM_BUDG, B.ACC_ID, B.ITM_NAME, A.JOBDESC
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.JOBCODEID = B.JOBCODEID
							AND B.ITM_CODE_H = A.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_TYPE = 'SUBS'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')";
		return $this->db->query($sql);
	}
	
	function count_allItemOth($PRJCODE) // U
	{
		/*$sql		= "tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBCODEID = A.JOBCODEID
								OR B.ITM_CODE = A.ITM_CODE)";*/
		$sql		= "tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.ITM_CODE = A.ITM_CODE)";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemOth($PRJCODE) // U
	{
		/*$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
							A.ITM_VOLM, A.ITM_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, 
							A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_VOLM AS ITM_STOCK, 
							A.ITM_VOLMBG AS ITM_BUDG, A.ACC_ID, A.ITM_NAME, '' AS JOBDESC
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBCODEID = A.JOBCODEID
								OR B.ITM_CODE = A.ITM_CODE)";*/
		$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
							A.ITM_VOLM, A.ITM_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, 
							A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_VOLM AS ITM_STOCK, 
							A.ITM_VOLMBG AS ITM_BUDG, A.ACC_ID, A.ITM_NAME, '' AS JOBDESC
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.ITM_CODE = A.ITM_CODE)";
		return $this->db->query($sql);
	}
	
	function add($insRR) // U
	{
		$this->db->insert('tbl_ir_header', $insRR);
	}
	
	function addSN($insSN) // U
	{
		$this->db->insert('tbl_qrc_detail', $insSN);
	}
	
	function updateITM($parameters) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		
    	$PRJCODE 	= $parameters['PRJCODE'];
    	$WH_CODE 	= $parameters['WH_CODE'];
    	$JOBCODEID 	= $parameters['JOBCODEID'];
		$IR_NUM 	= $parameters['IR_NUM'];
    	$IR_CODE 	= $parameters['IR_CODE'];
    	$ITM_CODE 	= $parameters['ITM_CODE'];
    	$ITM_NAME 	= $parameters['ITM_NAME'];
    	$ITM_UNIT 	= $parameters['ITM_UNIT'];
    	$ITM_GROUP 	= $parameters['ITM_GROUP'];
		$ITM_QTY 	= $parameters['ITM_QTY'];
		$ITM_PRICE 	= $parameters['ITM_PRICE'];
		$ITM_LASTP 	= $parameters['ITM_PRICE'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
		
		// Mendapatkan Qty Awal
		$ITM_VOLMBG		= 0;
		$StartVOL		= 0;
		$StartPRC		= 0;
		$LastPRC 		= 0;
		$ITM_REMQTY		= 0;
		$StartTPRC		= 0;
		$StartIN		= 0;
		$StartINP		= 0;
		$IR_VOLM		= 0;
		$IR_AMOUNT		= 0;
		$sqlStartITM	= "SELECT ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_LASTP, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_IN, ITM_INP,
								IR_VOLM, IR_AMOUNT
							FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$ITM_VOLMBG		= $rowSITM->ITM_VOLMBG;		// Budget on RAP
			$ITM_VOLMBGR	= $rowSITM->ITM_VOLMBGR;	// Remain of Budget
			$StartVOL 		= $rowSITM->ITM_VOLM; 		// like as Last Volume
			$StartPRC 		= $rowSITM->ITM_PRICE;		// like as MAPP Price
			$LastPRC 		= $rowSITM->ITM_LASTP;		// like as Last Price Total
			$ITM_REMQTY		= $rowSITM->ITM_REMQTY;		// like as Remain Qty
			$StartTPRC 		= $rowSITM->ITM_TOTALP;		// like as Last Total Price
			$StartIN 		= $rowSITM->ITM_IN;			// like as Last Total IN
			$StartINP 		= $rowSITM->ITM_INP;		// like as Last Total IN Price
			$IR_VOLM 		= $rowSITM->IR_VOLM;
			$IR_AMOUNT 		= $rowSITM->IR_AMOUNT;
		endforeach;
		
		$StartTPRC		= $StartVOL * $LastPRC;			// Nilai Total Material sebelum ditambah (ITM_VOL * ITM_LASTP)
		$EndTPRC		= $ITM_TOTALP;					// Nilai Total Material tambahan (ITM_QTY * ITM_PRICE)
		$EndVOL			= $StartVOL + $ITM_QTY;			// New End Volume = Last Stock
		if($EndVOL == '' || $EndVOL == 0)
			$EndVOL		= 1;
		$EndTPRC		= $StartTPRC + $ITM_TOTALP;		// New End Amount
		$PRCAVG			= $EndTPRC / $EndVOL;			// Last Price Average - HOLD AVG
		$EndPRC			= $ITM_PRICE;					// Last Price from Last Price Order - USE THIS
		
		$EndIN			= $StartIN + $ITM_QTY;
		$EndINP			= $StartINP + $ITM_TOTALP;
		$ITMVOLMBGR		= $ITM_VOLMBGR - $EndIN;		// Remain of Budget
		$ITMREMQTY		= $ITM_REMQTY + $ITM_QTY;
		
		$IR_VOLM		= $IR_VOLM + $ITM_QTY;
		$IR_AMOUNT		= $IR_AMOUNT + $ITM_TOTALP;
		
		//$ITMREMQTY	= $ITM_VOLMBG - $ITM_VOLMBGR - $ITM_QTY;
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITMVOLMBGR, ITM_VOLM = ITM_VOLM + $ITM_QTY,
							ITM_REMQTY = ITM_REMQTY + $ITM_QTY, ITM_TOTALP = $EndTPRC, ITM_LASTP = $EndPRC,
							IR_VOLM = IR_VOLM + $ITM_QTY, IR_AMOUNT = IR_AMOUNT + $ITM_TOTALP,
							ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP, LAST_TRXNO = '$IR_NUM',
							ITM_AVGP = $PRCAVG
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
		$this->db->query($sqlUpDet);
		
		// UPDATE WH_QTY
			$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
			$resWHC	= $this->db->count_all($sqlWHC);
			
			if($resWHC > 0)
			{
				$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $ITM_PRICE,
									ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
				$this->db->query($sqlUpWH);
			}
			else
			{
				$sqlInsWH	= "INSERT INTO tbl_item_whqty (PRJCODE, WH_CODE, ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT,
									ITM_VOLM, ITM_PRICE, ITM_IN, ITM_INP)
									VALUES ('$PRJCODE', '$WH_CODE', '$ITM_CODE', '$ITM_GROUP', '$ITM_NAME', '$ITM_UNIT',
									$ITM_QTY, $ITM_PRICE, $ITM_QTY, $ITM_TOTALP)";
				$this->db->query($sqlInsWH);
			}
		
		// UPDATE JOBD DETAIL
		$StartSTOCK		= 0;
		$StartSTOCK_AM	= 0;
		$sqlStartJITM	= "SELECT ITM_STOCK, ITM_STOCK_AM FROM tbl_joblist_detail 
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resStartJITM	= $this->db->query($sqlStartJITM)->result();
		foreach($resStartJITM as $rowSJITM) :
			$StartSTOCK		= $rowSJITM->ITM_STOCK;
			$StartSTOCK_AM	= $rowSJITM->ITM_STOCK_AM;
		endforeach;
		
		$EndSTOCK			= $StartSTOCK + $ITM_QTY;
		$EndSTOCK_AM		= $StartSTOCK_AM + $ITM_TOTALP;
		
		$sqlUpDetJ			= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK + $ITM_QTY,
									ITM_STOCK_AM = ITM_STOCK_AM + $ITM_TOTALP,
									IR_VOLM = IR_VOLM + $ITM_QTY, IR_AMOUNT = IR_AMOUNT + $ITM_TOTALP,
									ITM_AVGP = $PRCAVG
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
		$this->db->query($sqlUpDetJ);
	}
	
	function updateITM_NEW($parameters) // OK
	{
		date_default_timezone_set("Asia/Jakarta");

    	$PRJCODE 	= $parameters['PRJCODE'];
    	$WH_CODE 	= $parameters['WH_CODE'];
    	$JOBCODEID 	= $parameters['JOBCODEID'];
		$IR_NUM 	= $parameters['IR_NUM'];
    	$IR_CODE 	= $parameters['IR_CODE'];
    	$ITM_CODE 	= $parameters['ITM_CODE'];
    	$ITM_NAME 	= $parameters['ITM_NAME'];
    	$ITM_UNIT 	= $parameters['ITM_UNIT'];
    	$ITM_GROUP 	= $parameters['ITM_GROUP'];
		$ITM_QTY 	= $parameters['ITM_QTY'];
		$ITM_PRICE 	= $parameters['ITM_PRICE'];
		$ITM_LASTP 	= $parameters['ITM_PRICE'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;

		// MENDAPATKAN KATEGORI
			$ITM_CATEG 	= "";
			$sqlITMCAT	= "SELECT ITM_CATEG FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
			$resITMCAT	= $this->db->query($sqlITMCAT)->result();
			foreach($resITMCAT as $rowITMCAT) :
				$ITM_CATEG	= $rowITMCAT->ITM_CATEG;
			endforeach;

		// UPDATE ITEM
			$sqlUpItm	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_REMQTY = ITM_REMQTY + $ITM_QTY, ITM_TOTALP = ITM_TOTALP + $ITM_TOTALP,
								ITM_LASTP = $ITM_LASTP, IR_VOLM = IR_VOLM + $ITM_QTY, IR_AMOUNT = IR_AMOUNT + $ITM_TOTALP,
								ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP, LAST_TRXNO = '$IR_NUM'
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpItm);
		
		// UPDATE WH_QTY
			$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
			$resWHC	= $this->db->count_all($sqlWHC);
			if($resWHC > 0)
			{
				$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $ITM_PRICE,
									ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
				$this->db->query($sqlUpWH);
			}
			else
			{
				$sqlInsWH	= "INSERT INTO tbl_item_whqty (PRJCODE, WH_CODE, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT,
									ITM_VOLM, ITM_PRICE, ITM_IN, ITM_INP)
									VALUES ('$PRJCODE', '$WH_CODE', '$ITM_CODE', '$ITM_GROUP', '$ITM_CATEG', '$ITM_NAME', '$ITM_UNIT',
									$ITM_QTY, $ITM_PRICE, $ITM_QTY, $ITM_TOTALP)";
				$this->db->query($sqlInsWH);
			}

		// GET LAST POSITION
			$ITM_SA			= 0;
			$ITM_SAAM		= 0;
			$sqlSITM		= "SELECT ITM_VOLM, ITM_TOTALP FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$resSITM		= $this->db->query($sqlSITM)->result();
			foreach($resSITM as $rowSITM) :
				$ITM_SA		= $rowSITM->ITM_VOLM;
				$ITM_SAAM	= $rowSITM->ITM_TOTALP;
			endforeach;
			
			$EndSITM		= $ITM_SA;
			if($EndSITM == 0)
				$EndSITM 	= 1;

			$EndSITM_AM		= $ITM_SAAM;
			$ITMAVGP 		= $EndSITM_AM / $EndSITM;

		// UPDATE ITEM AVG
			$sqlUpItm	= "UPDATE tbl_item SET ITM_AVGP = $ITMAVGP WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpItm);

		// UPDATE JOBLIST DETAIL PER JOBCODEID
			$STOCK_AW		= 0;
			$STOCK_AWAM		= 0;
			$sqlSTOCKJD		= "SELECT ITM_STOCK, ITM_STOCK_AM FROM tbl_joblist_detail 
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
			$resSTOCKJD		= $this->db->query($sqlSTOCKJD)->result();
			foreach($resSTOCKJD as $rowSTOCKJD) :
				$STOCK_AW	= $rowSTOCKJD->ITM_STOCK;
				$STOCK_AWAM	= $rowSTOCKJD->ITM_STOCK_AM;
			endforeach;
			
			$EndSTOCK		= $STOCK_AW + $ITM_QTY;
			$EndSTOCK_AM	= $STOCK_AWAM + $ITM_TOTALP;
			$ITM_AVGP 		= $EndSTOCK_AM / $EndSTOCK;

			$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET IR_VOLM = IR_VOLM + $ITM_QTY, IR_AMOUNT = IR_AMOUNT + $ITM_TOTALP,
								ITM_STOCK = ITM_STOCK + $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM + $ITM_TOTALP, ITM_AVGP = $ITM_AVGP
							WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpDetJ);
	}
	
	function updateITMSO($parameters) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		
    	$PRJCODE 	= $parameters['PRJCODE'];
    	$WH_CODE 	= $parameters['WH_CODE'];
    	$JOBCODEID 	= $parameters['JOBCODEID'];
		$IR_NUM 	= $parameters['IR_NUM'];
    	$IR_CODE 	= $parameters['IR_CODE'];
    	$ITM_CODE 	= $parameters['ITM_CODE'];
    	$ITM_NAME 	= $parameters['ITM_NAME'];
    	$ITM_UNIT 	= $parameters['ITM_UNIT'];
    	$ITM_GROUP 	= $parameters['ITM_GROUP'];
		$ITM_QTY 	= $parameters['ITM_QTY'];
		$ITM_PRICE 	= $parameters['ITM_PRICE'];
		$ITM_LASTP 	= $parameters['ITM_PRICE'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
		
		// Mendapatkan Qty Awal
		$ITM_VOLMBG		= 0;
		$StartVOL		= 0;
		$StartPRC		= 0;
		$ITM_REMQTY		= 0;
		$StartTPRC		= 0;
		$StartIN		= 0;
		$StartINP		= 0;
		$IR_VOLM		= 0;
		$IR_AMOUNT		= 0;
		$sqlStartITM	= "SELECT ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_LASTP, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_IN, ITM_INP,
								IR_VOLM, IR_AMOUNT
							FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$ITM_VOLMBG		= $rowSITM->ITM_VOLMBG;		// Budget on RAP
			$ITM_VOLMBGR	= $rowSITM->ITM_VOLMBGR;	// Remain of Budget
			$StartVOL 		= $rowSITM->ITM_VOLM; 		// like as Last Volume
			$StartPRC 		= $rowSITM->ITM_PRICE;		// like as MAPP Price
			$LastPRC 		= $rowSITM->ITM_LASTP;		// like as Last Price Total
			$ITM_REMQTY		= $rowSITM->ITM_REMQTY;		// like as Remain Qty
			$StartTPRC 		= $rowSITM->ITM_TOTALP;		// like as Last Total Price
			$StartTPRC 		= $rowSITM->ITM_TOTALP;		// like as Last Total Price
			$StartIN 		= $rowSITM->ITM_IN;			// like as Last Total IN
			$StartINP 		= $rowSITM->ITM_INP;		// like as Last Total IN Price
			$IR_VOLM 		= $rowSITM->IR_VOLM;
			$IR_AMOUNT 		= $rowSITM->IR_AMOUNT;
		endforeach;
		
		$StartTPRC		= $StartVOL * $LastPRC;			// Nilai Total Material sebelum ditambah (ITM_VOL * ITM_LASTP)
		$EndTPRC		= $ITM_TOTALP;					// Nilai Total Material tambahan (ITM_QTY * ITM_PRICE)
		$EndVOL			= $StartVOL + $ITM_QTY;			// New End Volume = Last Stock
		$EndTPRC		= $StartTPRC + $ITM_TOTALP;		// New End Amount
		$EndPRC			= $EndTPRC / $EndVOL;			// Last Price Average
		//$EndPRC			= $ITM_PRICE;				// Last Price from Last Price Order
		
		$EndIN			= $StartIN + $ITM_QTY;
		$EndINP			= $StartINP + $ITM_TOTALP;
		$ITMVOLMBGR		= $ITM_VOLMBGR - $EndIN;		// Remain of Budget
		$ITMREMQTY		= $ITM_REMQTY + $ITM_QTY;
		
		$IR_VOLM		= $IR_VOLM + $ITM_QTY;
		$IR_AMOUNT		= $IR_AMOUNT + $ITM_TOTALP;
		
		//$ITMREMQTY		= $ITM_VOLMBG - $ITM_VOLMBGR - $ITM_QTY;
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITM_QTY,
							ITM_REMQTY = ITM_REMQTY + $ITM_QTY, ITM_TOTALP = $EndTPRC, ITM_LASTP = $EndPRC,
							IR_VOLM = IR_VOLM + $ITM_QTY, IR_AMOUNT = IR_AMOUNT + $ITM_TOTALP,
							ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP, LAST_TRXNO = '$IR_NUM'
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
		$this->db->query($sqlUpDet);
		
		// UPDATE WH_QTY
			$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
			$resWHC	= $this->db->count_all($sqlWHC);
			
			if($resWHC > 0)
			{
				$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $ITM_PRICE,
									ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
				$this->db->query($sqlUpWH);
			}
			else
			{
				$sqlInsWH	= "INSERT INTO tbl_item_whqty (PRJCODE, WH_CODE, ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT,
									ITM_VOLM, ITM_PRICE, ITM_IN, ITM_INP)
									VALUES ('$PRJCODE', '$WH_CODE', '$ITM_CODE', '$ITM_GROUP', '$ITM_NAME', '$ITM_UNIT',
									$ITM_QTY, $ITM_PRICE, $ITM_QTY, $ITM_TOTALP)";
				$this->db->query($sqlInsWH);
			}
		
		// UPDATE JOBD DETAIL
		$StartSTOCK		= 0;
		$StartSTOCK_AM	= 0;
		$sqlStartJITM	= "SELECT ITM_STOCK, ITM_STOCK_AM FROM tbl_joblist_detail 
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resStartJITM	= $this->db->query($sqlStartJITM)->result();
		foreach($resStartJITM as $rowSJITM) :
			$StartSTOCK		= $rowSJITM->ITM_STOCK;
			$StartSTOCK_AM	= $rowSJITM->ITM_STOCK_AM;
		endforeach;
		
		$EndSTOCK			= $StartSTOCK + $ITM_QTY;
		$EndSTOCK_AM		= $StartSTOCK_AM + $ITM_TOTALP;
		
		$sqlUpDetJ			= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK + $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM + $ITM_TOTALP,
									IR_VOLM = IR_VOLM + $ITM_QTY, IR_AMOUNT = IR_AMOUNT + $ITM_TOTALP
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
		//$this->db->query($sqlUpDetJ);
	}
	
	function updateITM_Min($parameters) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		
    	$PRJCODE 	= $parameters['PRJCODE'];
    	$JOBCODEID 	= $parameters['JOBCODEID'];
		$IR_NUM 	= $parameters['IR_NUM'];
    	$IR_CODE 	= $parameters['IR_CODE'];
    	$ITM_CODE 	= $parameters['ITM_CODE'];
		$ITM_QTY 	= $parameters['ITM_QTY'];
		$ITM_PRICE 	= $parameters['ITM_PRICE'];
		$ITM_LASTP	= $parameters['ITM_PRICE'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
		
		// Mendapatkan Qty Awal
		$StartVOL		= 0;
		$StartPRC		= 0;
		$StartTPRC		= 0;
		$StartIN		= 0;
		$StartINP		= 0;
		$sqlStartITM	= "SELECT ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_IN, ITM_INP FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$StartVOL 	= $rowSITM->ITM_VOLM; 	// like as Last Volume
			$StartPRC 	= $rowSITM->ITM_PRICE;	// like as Last Price Average
			$StartTPRC 	= $rowSITM->ITM_TOTALP;	// like as Last Total Price
			$StartTPRC 	= $rowSITM->ITM_TOTALP;	// like as Last Total Price
			$StartIN 	= $rowSITM->ITM_IN;		// like as Last Total IN
			$StartINP 	= $rowSITM->ITM_INP;	// like as Last Total IN Price
		endforeach;
		
		$EndVOL			= $StartVOL - $ITM_QTY;
		$EndTPRC		= $StartTPRC - $ITM_TOTALP;
		$EndPRC			= $EndTPRC / $EndVOL;
		
		$EndIN			= $StartIN - $ITM_QTY;
		$EndINP			= $StartINP - $ITM_TOTALP;
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = $EndVOL, ITM_PRICE = $EndPRC, ITM_TOTALP = $EndTPRC, ITM_LASTP = $EndPRC, 
							ITM_IN = $EndIN, ITM_INP = $EndINP
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";	
		//$this->db->query($sqlUpDet);
		
		// UPDATE JOBD ETAIL
		$sqlStartJITM	= "SELECT ITM_STOCK, ITM_STOCK_AM FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resStartJITM	= $this->db->query($sqlStartJITM)->result();
		foreach($resStartJITM as $rowSJITM) :
			$StartSTOCK		= $rowSJITM->ITM_STOCK;
			$StartSTOCK_AM	= $rowSJITM->ITM_STOCK_AM;
		endforeach;
		
		$EndSTOCK			= $StartSTOCK - $ITM_QTY;
		$EndSTOCK_AM		= $StartSTOCK_AM - $ITM_TOTALP;
		
		$sqlUpDetJ			= "UPDATE tbl_joblist_detail SET ITM_STOCK = $EndSTOCK, ITM_STOCK_AM = $EndSTOCK_AM
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
		$this->db->query($sqlUpDetJ);
	}
	
	function get_IR_by_number($IR_NUM) // U
	{		
		$sql = "SELECT A.*,
					B.SPLDESC, B.SPLADD1
				FROM tbl_ir_header A
					LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE 
					A.IR_NUM = '$IR_NUM'";
		return $this->db->query($sql);
	}
	
	function updateIR($IR_NUM, $updIR) // U
	{
		$this->db->where('IR_NUM', $IR_NUM);
		return $this->db->update('tbl_ir_header', $updIR);
	}
	
	function deleteIRDetail($IR_NUM) // U
	{
		$this->db->where('IR_NUM', $IR_NUM);
		$this->db->delete('tbl_ir_detail');
	}
	
	function updatePO($IR_NUM, $PRJCODE, $PO_NUM) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$sql	= "UPDATE tbl_po_header SET IR_CREATED = 1 WHERE PO_NUM = '$PO_NUM'";
		$this->db->query($sql);
		
		$sqlGetPO	= "SELECT IR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, ACC_ID, ITM_QTY, ITM_PRICE, POD_ID
						FROM tbl_ir_detail
						WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$IR_NUM 		= $rowPO->IR_NUM;
			$JOBCODEDET		= $rowPO->JOBCODEDET;
			$JOBCODEID		= $rowPO->JOBCODEID;
			$POD_ID			= $rowPO->POD_ID;
			$ITM_CODE		= $rowPO->ITM_CODE;
			$ACC_ID			= $rowPO->ACC_ID;
			$IR_VOLM_NOW	= $rowPO->ITM_QTY;
			$IR_PRICE_NOW	= $rowPO->ITM_PRICE;
			$IR_COST_NOW	= $IR_VOLM_NOW * $IR_PRICE_NOW;
			
			$ITM_CODE_H		= $ITM_CODE;
			$ITM_TYPE		= 'PRM';
			$sqlITYPE		= "SELECT ITM_CODE, ITM_CODE_H, ITM_TYPE FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITYPE		= $this->db->query($sqlITYPE)->result();
			foreach($resITYPE as $rowITYPE) :
				$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
			endforeach;
			if($ITM_TYPE == 'SUBS')
			{
				$ITM_CODE_H	= $rowITYPE->ITM_CODE_H;
				$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
			}
			
			// UPDATE PO DETAIL
				$sqlUpd		= "UPDATE tbl_po_detail SET IR_VOLM = IR_VOLM + $IR_VOLM_NOW, IR_AMOUNT = IR_AMOUNT + $IR_COST_NOW
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
									AND PO_NUM = '$PO_NUM' AND PO_ID = $POD_ID";
				$this->db->query($sqlUpd);

			// UPDATE PR DETAIL
				$sqlPRD		= "SELECT PR_NUM, PRD_ID FROM tbl_po_detail WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE' AND PO_ID = $POD_ID";
				$resPRD		= $this->db->query($sqlPRD)->result();
				foreach($resPRD as $rowPRD) :
					$PR_NUM = $rowPRD->PR_NUM;
					$PRD_ID	= $rowPRD->PRD_ID;

					$updPRD	= "UPDATE tbl_pr_detail SET IR_VOLM = IR_VOLM + $IR_VOLM_NOW, IR_AMOUNT = IR_AMOUNT + $IR_COST_NOW
								WHERE PR_NUM = '$PR_NUM' AND PR_ID = $PRD_ID AND PRJCODE = '$PRJCODE'";
					$this->db->query($updPRD);
				endforeach;
			
			// UPDATE LAST PRICE IN ITEM TABLE
				$sqlUpd1		= "UPDATE tbl_item SET ITM_LASTP = $IR_PRICE_NOW WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				//$this->db->query($sqlUpd1); // DITENTUKAN PADA function updateITM
				
				if($ITM_TYPE == 'SUBS')
				{
					// UPDATE LAST PRICE iIN ITEM TABLE
						$sqlUpd1		= "UPDATE tbl_item SET ITM_LASTP = $IR_PRICE_NOW WHERE PRJCODE = '$PRJCODE' 
												AND ITM_CODE = '$ITM_CODE_H'";
						//$this->db->query($sqlUpd1); // DITENTUKAN PADA function updateITM
				}
		endforeach;
		
		// CEK QTY PO AND IR
			$selTOTROW 	= "tbl_po_detail WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
			$resTOTROW	= $this->db->count_all($selTOTROW);
			
			$sqlGetPOCV	= "SELECT PR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, PO_VOLM, (PO_VOLM * PO_PRICE) AS TOT_POAMOUNT, PO_DISC,
								IR_VOLM, IR_AMOUNT AS TOT_IRAMOUNT, PRD_ID
							FROM tbl_po_detail
							WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPOCV	= $this->db->query($sqlGetPOCV)->result();
			$TOTCLOSE1	= 0;
			$TOTCLOSE2	= 0;
			foreach($resGetPOCV as $rowPOCV) :
				$PR_NUM 		= $rowPOCV->PR_NUM;
				$JOBCODEDET		= $rowPOCV->JOBCODEDET;
				$JOBCODEID		= $rowPOCV->JOBCODEID;
				$ITM_CODE		= $rowPOCV->ITM_CODE;
				$PO_VOLM 		= $rowPOCV->PO_VOLM;
				$TOTPOAMOUNT	= $rowPOCV->TOT_POAMOUNT;
				$PO_DISC		= $rowPOCV->PO_DISC;
				$TOT_POAMOUNT	= $TOTPOAMOUNT - $PO_DISC;
				$IR_VOLM 		= $rowPOCV->IR_VOLM;
				$TOT_IRAMOUNT 	= $rowPOCV->TOT_IRAMOUNT;
				$PRD_ID 		= $rowPOCV->PRD_ID;
				
				if($PO_VOLM == $IR_VOLM)
				{
					$TOTCLOSE1	= $TOTCLOSE1 + 1;
					$sqlUpdPO	= "UPDATE tbl_po_header SET PO_STAT = '6', PO_ISCLOSE = '1', STATDESC = 'Close', STATCOL = 'info'
									WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
					//$this->db->query($sqlUpdPO);
				}
				if($TOT_POAMOUNT == $TOT_IRAMOUNT)
				{
					$TOTCLOSE2	= $TOTCLOSE2 + 1;
					$sqlUpdPO	= "UPDATE tbl_po_header SET PO_STAT = '6', PO_ISCLOSE = '1', STATDESC = 'Close', STATCOL = 'info'
									WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
					//$this->db->query($sqlUpdPO);
				}
			endforeach;
			
			//if($TOTCLOSE1 == $resTOTROW || $TOTCLOSE2 == $resTOTROW)
			if($TOTCLOSE1 == $resTOTROW)
			{
				$sqlUpdPO	= "UPDATE tbl_po_header SET PO_STAT = '6', PO_ISCLOSE = '1', STATDESC = 'Close', STATCOL = 'info'
								WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpdPO);
			}
	}
	
	function updateJOBDET($IR_NUM, $PRJCODE) // OK - DIRECT
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$sqlGetPO	= "SELECT IR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, ACC_ID, ITM_QTY, ITM_PRICE
						FROM tbl_ir_detail
						WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$IR_NUM 		= $rowPO->IR_NUM;
			$JOBCODEDET		= $rowPO->JOBCODEDET;
			$JOBCODEID		= $rowPO->JOBCODEID;
			$ITM_CODE		= $rowPO->ITM_CODE;
			$ACC_ID			= $rowPO->ACC_ID;
			$IR_VOLM_NOW	= $rowPO->ITM_QTY;
			$IR_PRICE_NOW	= $rowPO->ITM_PRICE;
			$IR_COST_NOW	= $IR_VOLM_NOW * $IR_PRICE_NOW;
			
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
			
			// UPDATE JOB HEADER
				$IR_VOLM	= 0;
				$IR_AMOUNT	= 0;				
				$sqlGetPOD	= "SELECT IR_VOLM, IR_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";									
				$resGetPOD	= $this->db->query($sqlGetPOD)->result();
				foreach($resGetPOD as $rowPOD) :
					$IR_VOLM 	= $rowPOD->IR_VOLM;
					$IR_AMOUNT 	= $rowPOD->IR_AMOUNT;
				endforeach;
				if($IR_VOLM == '')
					$IR_VOLM = 0;
				if($IR_AMOUNT == '')
					$IR_AMOUNT = 0;
				
				$totIRQty		= $IR_VOLM + $IR_VOLM_NOW;
				$totIRAmount	= $IR_AMOUNT + $IR_COST_NOW;
				$sqlUpd			= "UPDATE tbl_joblist_detail SET IR_VOLM = $totIRQty, IR_AMOUNT = $totIRAmount, 
										ITM_LASTP = $IR_PRICE_NOW
									WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($sqlUpd); HOLDED ON 10 JAN 2019
			
			// UPDATE JOB DETAIL
				$IR_VOLM	= 0;
				$IR_AMOUNT	= 0;				
				$sqlGetPOD	= "SELECT IR_VOLM, IR_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
									AND ITM_CODE = '$ITM_CODE_H'";									
				$resGetPOD	= $this->db->query($sqlGetPOD)->result();
				foreach($resGetPOD as $rowPOD) :
					$IR_VOLM 	= $rowPOD->IR_VOLM;
					$IR_AMOUNT 	= $rowPOD->IR_AMOUNT;
				endforeach;
				if($IR_VOLM == '')
					$IR_VOLM = 0;
				if($IR_AMOUNT == '')
					$IR_AMOUNT = 0;
				
				$totIRQty		= $IR_VOLM + $IR_VOLM_NOW;
				$totIRAmount	= $IR_AMOUNT + $IR_COST_NOW;
				$sqlUpd			= "UPDATE tbl_joblist_detail SET IR_VOLM = $totIRQty, IR_AMOUNT = $totIRAmount, 
										ITM_LASTP = $IR_PRICE_NOW
									WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
										AND ITM_CODE = '$ITM_CODE_H'";
				//$this->db->query($sqlUpd); HOLDED ON 10 JAN 2019
				
			// UPDATE LAST PRICE iIN ITEM TABLE
				$sqlUpd1		= "UPDATE tbl_item SET ITM_LASTP = $IR_PRICE_NOW WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpd1);
				
				if($ITM_TYPE == 'SUBS')
				{
					// UPDATE LAST PRICE iIN ITEM TABLE
						$sqlUpd1		= "UPDATE tbl_item SET ITM_LASTP = $IR_PRICE_NOW WHERE PRJCODE = '$PRJCODE' 
												AND ITM_CODE = '$ITM_CODE_H'";
						$this->db->query($sqlUpd1);
				}
		endforeach;
	}
	
	function updatePOQTY($parameters) // H
	{
    	$PO_NUM 	= $parameters['PO_NUM'];
    	$PRJCODE 	= $parameters['PRJCODE'];
		$IR_NUM 	= $parameters['IR_NUM'];
    	$IR_CODE 	= $parameters['IR_CODE'];
    	$ITM_CODE 	= $parameters['ITM_CODE'];
		$ITM_QTY 	= $parameters['ITM_QTY'];
		$ITM_PRICE 	= $parameters['ITM_PRICE'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
		
		// Mendapatkan Qty Awal PO
		$StartVOL		= 0;
		$StartPRC		= 0;
		$sqlStartITM	= "SELECT IRVOLM, IR_PRICE FROM tbl_po_detail WHERE PO_NUM = '$PO_NUM' AND CSTCODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$StartVOL 	= $rowSITM->IRVOLM; 	// like as Last Volume
			$StartPRC 	= $rowSITM->IR_PRICE;	// like as Last Price Average
		endforeach;
		
		$EndVOL			= $StartVOL + $ITM_QTY;
		$EndTPRC		= $StartTPRC + $ITM_TOTALP;
		$IRPAVG			= $EndTPRC / $EndVOL;
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_po_detail SET IRVOLM = $EndVOL, IR_PRICE = $EndPRC, IRPAVG = $IRPAVG 
						WHERE PO_NUM = '$PO_NUM' AND CSTCODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUpDet);
	}
	
	function count_all_IR_OUT($PRJCODE, $key, $DefEmp_ID) // U
	{
		if($key == '')
		{
			$sql = "tbl_ir_header WHERE PRJCODE = '$PRJCODE'
					AND IR_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_ir_header WHERE PRJCODE = '$PRJCODE'
					AND IR_STAT IN (2,7)
						AND (IR_CODE LIKE '%$key%' ESCAPE '!' OR PO_CODE LIKE '%$key%' ESCAPE '!' 
						OR IR_DATE LIKE '%$key%' ESCAPE '!' OR IR_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_IR_OUT($PRJCODE, $start, $end, $key, $DefEmp_ID) // U
	{
		if($key == '')
		{
			$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
						REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
					FROM tbl_ir_header
					WHERE PRJCODE = '$PRJCODE'
						AND IR_STAT IN (2,7)";
		}
		else
		{
			$sql = "SELECT IR_NUM, IR_CODE, IR_DATE, PRJCODE, SPLCODE, SPLDESC, PO_NUM, IR_REFER, IR_AMOUNT, IR_NOTE, IR_STAT, INVSTAT,
						REVMEMO, IR_SOURCE, STATDESC, STATCOL, CREATERNM, IR_LOC
					FROM tbl_ir_header
					WHERE PRJCODE = '$PRJCODE'
						AND IR_STAT IN (2,7)
						AND (IR_CODE LIKE '%$key%' ESCAPE '!' OR PO_CODE LIKE '%$key%' ESCAPE '!' 
						OR SPLDESC LIKE '%$key%' ESCAPE '!' OR IR_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->query($sql);
	}	
	
	function count_all_num_rowsProj($DefEmp_ID) // U
	{
		$sql	= "tbl_project A 
					INNER JOIN tbl_op_header B ON A.PRJCODE = B.PRJCODE 
					WHERE PRJSTAT = 1
					AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projecta($limit, $offset, $DefEmp_ID) // U
	{
		$sql = "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.TRXUSER, A.PRJSTAT,
				B.First_Name, B.Middle_Name, B.Last_Name
				FROM tbl_project A
				LEFT JOIN  tbl_employee B ON A.TRXUSER = B.Emp_ID
				INNER JOIN tbl_op_header C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJSTAT = 1 AND C.APPROVE = 3
				AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProj_PNo($txtSearch, $DefEmp_ID) // U
	{
		$sql	= "tbl_project WHERE PRJCODE LIKE '%$txtSearch%' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rowsProj_PNm($txtSearch) // U
	{
		$sql	= "tbl_project WHERE PRJNAME LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // U
	{
		$sql = "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.TRXUSER, A.PRJSTAT,
				B.First_Name, B.Middle_Name, B.Last_Name
				FROM tbl_project A
				LEFT JOIN  tbl_employee B ON A.TRXUSER = B.Emp_ID
				INNER JOIN tbl_op_header C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJSTAT = 1 AND C.APPROVE = 3
				AND A.PRJCODE LIKE '%$txtSearch%'
				AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE
				LIMIT $offset, $limit";	
		return $this->db->query($sql);
	}
	
	function get_last_ten_project_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // U
	{
		$sql = "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.TRXUSER, A.PRJSTAT,
				B.First_Name, B.Middle_Name, B.Last_Name
				FROM tbl_project A
				LEFT JOIN  tbl_employee B ON A.TRXUSER = B.Emp_ID
				INNER JOIN tbl_op_header C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJSTAT = 1 AND C.APPROVE = 3
				AND A.PRJNAME LIKE '%$txtSearch%'
				AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_IRN($txtSearch) // U
	{
		$sql	= "tbl_ir_header WHERE IR_NUM LIKE '%$txtSearch%' ";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_VendN($txtSearch) // U
	{
		$sql	= "tbl_ir_header A
					LEFT JOIN 	tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE B.SPLDESC LIKE '%$txtSearch%'";				
		return $this->db->count_all($sql);
	}
	
	function viewitemreceiptlist_PNo($limit, $offset, $txtSearch, $proj_Code) // U
	{
		$sql = "SELECT A.IR_NUM, A.IR_DATE, A.APPROVE, A.IR_STAT, A.SPLCODE, A.IR_NOTE, A.TRXUSER, A.REVMEMO, A.INVSTAT,
				B.First_Name, B.Middle_Name, B.Last_Name, C.SPLDESC
				FROM tbl_ir_header A
				LEFT JOIN	tbl_employee B ON A.TRXUSER = B.Emp_ID
				LEFT JOIN	tbl_supplier C ON A.SPLCODE = C.SPLCODE
				WHERE A.PRJCODE = '$proj_Code'
				AND A.IR_NUM LIKE '%$txtSearch%'
				ORDER BY A.IR_NUM
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function viewitemreceiptlist_PNm($limit, $offset, $txtSearch, $proj_Code) // U
	{
		$sql = "SELECT A.IR_NUM, A.IR_DATE, A.APPROVE, A.IR_STAT, A.SPLCODE, A.IR_NOTE, A.TRXUSER, A.REVMEMO, A.INVSTAT,
				B.First_Name, B.Middle_Name, B.Last_Name, C.SPLDESC
				FROM tbl_ir_header A
				LEFT JOIN	tbl_employee B ON A.TRXUSER = B.Emp_ID
				LEFT JOIN	tbl_supplier C ON A.SPLCODE = C.SPLCODE
				WHERE A.PRJCODE = '$proj_Code'
				AND C.SPLDESC LIKE '%$txtSearch%'
				ORDER BY A.IR_NUM
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function getProjName($myLove_the_an) // U
	{
		$sql	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$myLove_the_an'";
		$ressql = $this->db->query($sql)->result();
		foreach($ressql as $row) :
			$proj_Name = $row->proj_Name;
		endforeach;
		return $proj_Name;
	}
	
	function count_all_num_rowsWHLoc() // U
	{
		return $this->db->count_all('twhlocation');
	}
	
	function viewwhloc() // U
	{
		$this->db->select('WH_ID, WH_Code, WH_Name');
		$this->db->from('twhlocation');
		$this->db->order_by('WH_Name', 'asc');
		return $this->db->get();
	}
	
	// Update Material Request
	function updatePRA($SPPCODE, $parameters) // U -- Seharusnya saat diapprove dari inbox fungsi ini dijalankan
	{
		/*$SPPCODE 		= $SPPCODE;
		$RRSource 		= $parameters['RRSource'];
    	$CSTCODE 		= $parameters['CSTCODE'];
		$PRJCODE 		= $parameters['PRJCODE'];
    	$LPMVOLM 		= $parameters['LPMVOLM'];
    	$LPMVOLM2 		= $parameters['LPMVOLM2'];
		
		// Mengupdate data di project planning
		$sql1			= "SELECT IRQty1, IRQty2 FROM tbl_projplan_material WHERE MR_Number = '$MRNumber' AND Item_code = '$Item_code'";
		$resPRQty	= $this->db->query($sql1)->result();
		foreach($resPRQty as $rowPR) :
			$PR_Qtya = $rowPR->IRQty1;
			$PR_Qty2a = $rowPR->IRQty2;
		endforeach;
		$totPRQty1	= $Qty_RR + $PR_Qtya;
		$totPRQty2	= $Qty_RR2 + $PR_Qty2a;			
		
		$sqlUpDet	= "UPDATE tproject_mrdetail SET IRQty1 = $totPRQty1, IRQty2 = $totPRQty2
						WHERE MR_Number = '$MRNumber' AND Item_code = '$Item_code'";	
		$this->db->query($sqlUpDet);*/	
	}
	
	function updateLPMDetail($IR_NUM, $IR_CODE, $PRJCODE, $parameters) // U
	{
		$IR_NUM 	= $IR_NUM;
		$IR_CODE 	= $IR_CODE;
		$PRJCODE 	= $PRJCODE;
    	$CSTCODE 	= $parameters['CSTCODE'];
		$CSTUNIT 	= $parameters['CSTUNIT'];
    	$LPMVOLM 	= $parameters['LPMVOLM'];
    	$CSTPUNT 	= $parameters['CSTPUNT'];
		$CSTDISP 	= $parameters['CSTDISP'];
		$CSTDISC 	= $parameters['CSTDISC'];
		$CSTCOST 	= $parameters['CSTCOST'];
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_ir_detail SET CSTUNIT = '$CSTUNIT', LPMVOLM = '$LPMVOLM', CSTPUNT = '$CSTPUNT', CSTDISP = '$CSTDISP', CSTDISC = '$CSTDISC', CSTCOST = '$CSTCOST'
						WHERE IR_NUM = '$IR_NUM' AND IR_CODE = '$IR_CODE' AND CSTCODE = '$CSTCODE'";	
		$this->db->query($sqlUpDet);
	}
	
	function count_all_num_rows_inbox($DefEmp_ID) // U
	{
		$sql	= "tbl_ir_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE
						A.IR_STAT IN (2,7)
						AND A.APPROVE IN (1,2)
						AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";				
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_RR_inbox($limit, $offset, $DefEmp_ID) // U
	{
		$sql = "SELECT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.PRJCODE, A.SPLCODE, A.IR_REFER, A.PO_NUM AS OP_CODE, A.IR_AMOUNT, A.TRXUSER, A.APPROVE, A.APPRUSR, A.IR_STAT, A.INVSTAT, A.IR_NOTE,
					A.REVMEMO, A.WHCODE, A.Patt_Date, A.Patt_Month, A.Patt_Year, A.Patt_Number,
					B.SPLDESC, B.SPLADD1
				FROM tbl_ir_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE 
					A.IR_STAT IN (2,7)
					AND A.APPROVE IN (1,2)
					AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.IR_NUM
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_inbox_IRN($txtSearch, $DefProj_Code, $DefEmp_ID) // U
	{
		$sql	= "tbl_ir_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE 
						A.IR_NUM LIKE '%$txtSearch%' 
						AND A.IR_STAT IN (2,7)
						AND A.APPROVE IN (1,2)
						AND PRJCODE = '$DefProj_Code'
						AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_inbox_VendN($txtSearch, $DefProj_Code, $DefEmp_ID) // U
	{
		$sql	= "tbl_ir_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE 
						B.SPLDESC LIKE '%$txtSearch%' 
						AND A.IR_STAT IN (2,7)
						AND A.APPROVE IN (1,2)
						AND PRJCODE = '$DefProj_Code'
						AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_RR_inbox_PNo($limit, $offset, $txtSearch, $DefProj_Code, $DefEmp_ID) // U
	{
		$sql = "SELECT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.PRJCODE, A.SPLCODE, A.IR_REFER, A.PO_NUM AS OP_CODE, A.IR_AMOUNT, A.TRXUSER, A.APPROVE, A.APPRUSR, A.IR_STAT, A.INVSTAT, A.IR_NOTE,
					A.REVMEMO, A.WHCODE, A.Patt_Date, A.Patt_Month, A.Patt_Year, A.Patt_Number,
					B.SPLDESC, B.SPLADD1
				FROM tbl_ir_header A
					LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE 
					A.IR_NUM LIKE '%$txtSearch%' 
					AND A.IR_STAT IN (2,7)
					AND A.APPROVE IN (1,2)
					AND PRJCODE = '$DefProj_Code'
					AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.IR_NUM
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_RR_inbox_PNm($limit, $offset, $txtSearch, $DefProj_Code, $DefEmp_ID) // U
	{
		$sql = "SELECT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.PRJCODE, A.SPLCODE, A.IR_REFER, A.PO_NUM AS OP_CODE, A.IR_AMOUNT, A.TRXUSER, A.APPROVE, A.APPRUSR, A.IR_STAT, A.INVSTAT, A.IR_NOTE,
					A.REVMEMO, A.WHCODE, A.Patt_Date, A.Patt_Month, A.Patt_Year, A.Patt_Number,
					B.SPLDESC, B.SPLADD1
				FROM tbl_ir_header A
					LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE 
					B.SPLDESC LIKE '%$txtSearch%' 
					AND A.IR_STAT IN (2,7)
					AND A.APPROVE IN (1,2)
					AND PRJCODE = '$DefProj_Code'
					AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.IR_NUM
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function viewpurreq()
	{
		$query = $this->db->get('tbl_ir_header');
		return $query->result(); 
	}
	
	var $table = 'tbl_ir_header';
	
	function delete($IR_NUM)
	{
		$this->db->where('IR_NUM', $IR_NUM);
		$this->db->delete($this->table);
	}
	
	function get_last_ten_projRR($proj_Code, $limit, $txtSearch)
	{
		$sql = "SELECT A.MRH_ID, A.MR_Number, A.MR_Date, A.req_date, A.latest_date, A.MR_Class, A.MR_Type, A.proj_ID, A.proj_Code, A.MR_DepID, A.MR_EmpID, A.MR_Notes, A.MR_Status, 
				A.Approval_Status, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tproject_mrheader A
				LEFT JOIN  	tbl_employee B ON A.MR_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.proj_Code = $proj_Code AND C.isActive = 1
				ORDER BY A.MR_Number ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function viewitemreceiptlist($limit, $offset)
	{
		$sql = "SELECT A.IR_NUM, A.IR_DATE, A.Approval_Status, A.IR_STATus, A.Vend_Code, A.IR_NOTEs, A.RR_EmpID, A.Invoice_Status, A.isVoid,
				B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name
				FROM tbl_ir_header A
				LEFT JOIN  tbl_employee B ON A.RR_EmpID = B.Emp_ID
				LEFT JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				ORDER BY A.IR_NUM
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsDept()
	{
		return $this->db->count_all('tdepartment');
	}
	
	function viewDepartment()
	{
		$this->db->select('Dept_ID, Dept_Name');
		$this->db->from('tdepartment');
		$this->db->order_by('Dept_Name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsEmpDept()
	{
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_Itemxx()
	{
		return $this->db->count_all('titem');
	}
	
	// remarks by DH on March, 6 2014
	/*function viewAllItem()
	{
		$this->db->select('Item_Code, Item_Name, Item_Qty, Unit_Type_ID');
		$this->db->from('titem');
		$this->db->order_by('Item_Code', 'asc');
		return $this->db->get();
	}*/
	// add by DH on March, 6 2014
	/*function viewAllItem()
	{
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Unit_Type_ID1, B.Unit_Type_Name
				FROM titem A
				INNER JOIN tunittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}*/
	
	function getNumRowDocPat($MenuCode, $docPatternPosition)
	{
		$this->db->where('menu_code', $MenuCode);
		$this->db->where('Pattern_Position', $docPatternPosition);
		return $this->db->count_all('tbl_docpattern');
	}
	
	//function get_last_ten_RR_inbox($limit, $offset, $proj_Code)
	
	// Update Project Plan Material
	function updatePP($IR_NUM, $parameters)
	{
		$IR_NUM = $parameters['IR_NUM'];
    	$Item_code = $parameters['Item_code'];
		$proj_ID = $parameters['proj_ID'];
		$proj_Code = $parameters['proj_Code'];
    	$Qty_RR = $parameters['Qty_RR'];
    	$Qty_RR2 = $parameters['Qty_RR2'];
    	$Ref_Number = $parameters['Ref_Number'];
    	$UnitPrice = $parameters['UnitPrice'];
		
    	$IR_DATE = $parameters['IR_DATE'];
    	$accYr	= date('Y', strtotime($IR_DATE));
		$Qty_Plus = $parameters['Qty_Plus'];
    	$Trans_Type = $parameters['Transaction_Type'];
    	$Trans_Value = $parameters['Transaction_Value'];
    	$Curr_ID = $parameters['Currency_ID'];
    	$WH_ID = $parameters['WH_ID'];

		$RRSource = $parameters['RRSource'];
		
		// Mendapatkan nilai awal RR Qty
		$sql1		= "SELECT receipt_qty, receipt_qty2 FROM tprojplan_material WHERE proj_Code = '$proj_Code' AND Item_code = '$Item_code'";
		$resPPQty	= $this->db->query($sql1)->result();
		foreach($resPPQty as $row) :
			$RR_Qtya = $row->receipt_qty;
			$RR_Qty2a = $row->receipt_qty2;
		endforeach;
		// Menambahkan nilai awal RR Qty dengan RR Qty yang diapprove
		$totRRQty1	= $Qty_RR + $RR_Qtya;
		$totRRQty2	= $Qty_RR2 + $RR_Qty2a;
		
		// Update RR Qty di Material Project Plan
		$sql2		= "UPDATE tprojplan_material SET receipt_qty = $totRRQty1, receipt_qty2 = $totRRQty1
						WHERE proj_Code = '$proj_Code' AND Item_code = '$Item_code'";
		$this->db->query($sql2);
		
		if($RRSource == 'MR')
		{
			// Mendapatkan nilai awal RR Qty
			$sql3		= "SELECT IRQty1, IRQty2, IRQtyApproved1, IRQtyApproved2 FROM tproject_mrdetail WHERE MR_Number = '$Ref_Number' AND Item_code = '$Item_code'";
			$resPRQty	= $this->db->query($sql3)->result();
			foreach($resPRQty as $rowPR) :
				$RRQtyApp1 	= $rowPR->IRQtyApproved1;
				$RRQtyApp2 	= $rowPR->IRQtyApproved2;
				$IRQty1 	= $rowPR->IRQty1;
				$IRQty2 	= $rowPR->IRQty2;
			endforeach;
			// 1. Menambahkan nilai RR Qty dengan RR Qty yang diapprove pada Material Project dan
			// 2. Kurangi nilai IRQty1 dan IRQty2 dengan nilai yang sudah diapprove agar POQty1 = IRQty1 + POQtyApproved1
			$totRRQtyApp1	= $Qty_RR + $RRQtyApp1;
			$totRRQtyApp2	= $Qty_RR2 + $RRQtyApp2;
			$IRQty1x		= $IRQty1 - $Qty_RR;
			$IRQty2x		= $IRQty2 - $Qty_RR2;
			// Update RR Qty di Material Project
			$sql4		= "UPDATE tproject_mrdetail SET IRQty1 = $IRQty1x, IRQty2 = $IRQty2x, IRQtyApproved1 = $totRRQtyApp1, IRQtyApproved2 = $totRRQtyApp2
							WHERE MR_Number = '$Ref_Number' AND Item_code = '$Item_code'";	
			$this->db->query($sql4);
			
		}
		else
		{
			// Apabila PO, berarti harus mencari MR Numbernya terlebih dahulu
			$getMRN		= "SELECT PR_Number FROM tbl_op_header WHERE PO_NUM = '$Ref_Number'";
			$resgetMRN	= $this->db->query($getMRN)->result();
			foreach($resgetMRN as $rowPR) :
				$PRNumber = $rowPR->PR_Number;
			endforeach;
			// Mendapatkan nilai awal RR Qty
			$sql3		= "SELECT IRQty1, IRQty2, IRQtyApproved1, IRQtyApproved2 FROM tproject_mrdetail WHERE MR_Number = '$PRNumber' AND Item_code = '$Item_code'";
			$resPRQty	= $this->db->query($sql3)->result();
			foreach($resPRQty as $rowPR) :
				$RRQtyApp1 	= $rowPR->IRQtyApproved1;
				$RRQtyApp2 	= $rowPR->IRQtyApproved2;
				$IRQty1 	= $rowPR->IRQty1;
				$IRQty2 	= $rowPR->IRQty2;
			endforeach;
			// 1. Menambahkan nilai RR Qty dengan RR Qty yang diapprove pada Material Project
			// 2. Kurangi nilai IRQty1 dan IRQty2 dengan nilai yang sudah diapprove agar POQty1 = IRQty1 + POQtyApproved1
			$totRRQtyApp1	= $Qty_RR + $RRQtyApp1;
			$totRRQtyApp2	= $Qty_RR2 + $RRQtyApp2;
			$IRQty1x		= $IRQty1 - $Qty_RR;
			$IRQty2x		= $IRQty2 - $Qty_RR2;
			// Update RR Qty di Material Project
			$sql4		= "UPDATE tproject_mrdetail SET IRQty1 = $IRQty1x, IRQty2 = $IRQty2x, IRQtyApproved1 = $totRRQtyApp1, IRQtyApproved2 = $totRRQtyApp2
							WHERE MR_Number = '$PRNumber' AND Item_code = '$Item_code'";	
			$this->db->query($sql4);
		}
		
		// SEHARUSNYA SAAT PENERIMAAN BARANG TIDAK ADA PENENTUAN HARGA DAN PAJAK, ADANYA DI INVOICE.
		// Akan tetapi, di dalam proses nya akan dimasukan sebagai bahan default saat invoice

		// Apabila sourcenya bukan dari PO, maka ambil Price dari Material Budget karena di MR tidak ada penentuan harga
		if($RRSource == 'MR')
		{
			// Tidak ada pajak di dalam MR
			$qGetPrice	= "SELECT Unit_Price FROM tprojplan_material WHERE proj_Code = '$proj_Code' AND Item_code = '$Item_code'";
			$resqGetPrice	= $this->db->query($qGetPrice)->result();
			foreach($resqGetPrice as $rowPrice) :
				$Unit_Price = $rowPrice->Unit_Price; // Harga Per Item
			endforeach;
		}
		else
		{
			// Apabila dari PO, maka ambil unit price nya dari PO
			$qGetPrice		= "SELECT UnitPrice, Base_UnitPrice,
								Tax_Code1, Tax_Percentage1, Tax_Operator1, Tax_Amount1, BTax_Amount1, Tax_AmountPPh1, BTax_AmountPPh1, 
								Tax_Code2, Tax_Percentage2, Tax_Operator2, Tax_Amount2, BTax_Amount2, Tax_AmountPPh2, BTax_AmountPPh2, totAkumRow
								FROM tpo_detail A
								INNER JOIN TItem B ON A.Item_code = B.Item_code
								INNER JOIN tbl_op_header C ON C.PO_NUM = A.PO_NUM
								WHERE
								A.PO_NUM = '$Ref_Number'
								AND A.Item_Code = '$Item_code'
								AND C.proj_Code = '$proj_Code'";
			$resqGetPrice	= $this->db->query($qGetPrice)->result();
			foreach($resqGetPrice as $rowPrice) :
				$Tax_Code1 = $rowPrice->Tax_Code1;
				$Tax_Amount1 = $rowPrice->Tax_Amount1;
				$BTax_Amount1 = $rowPrice->BTax_Amount1;
				$Tax_AmountPPh1 = $rowPrice->Tax_AmountPPh1;
				$BTax_AmountPPh1 = $rowPrice->BTax_AmountPPh1;
				$Tax_Code2 = $rowPrice->Tax_Code2;
				$Tax_Amount2 = $rowPrice->Tax_Amount2;
				$BTax_Amount2 = $rowPrice->BTax_Amount2;
				$Tax_AmountPPh2 = $rowPrice->Tax_AmountPPh2;
				$BTax_AmountPPh2 = $rowPrice->BTax_AmountPPh2;
				$totAkumRow = $rowPrice->totAkumRow;
			endforeach;
		}
		
		$Unit_Price = $UnitPrice;
		
		$transacValue = $Unit_Price * $Qty_Plus;
		
		$sqlHist = "INSERT INTO titemhistory (JournalH_Code, proj_ID, proj_Code, Transaction_Date, Item_Code, Qty_Plus, QtyRR_Plus, Qty_Min, QtyRR_Min, 
					Transaction_Type, Transaction_Value, Currency_ID, WH_ID)
					VALUES ('$IR_NUM', $proj_ID, '$proj_Code', '$IR_DATE', '$Item_code', $Qty_Plus, $Qty_Plus, 0, 0, '$Trans_Type', $transacValue, '$Curr_ID', $WH_ID)";
		$this->db->query($sqlHist);
		
		// Check apakah Jumlah PO dengan Penerimaan sudah Sama
		if($RRSource == 'MR')
		{
			// Get Total RR Qty Per MR tanpa ada penghitungan per item, tapi total Qty Item dalam setiap MR
			$sqlGetTotRRQty		= "SELECT SUM(Qty_RR) AS Qty_RR, SUM(Qty_RR2) AS Qty_RR2
									FROM trr_detail A
									INNER JOIN TItem B ON A.Item_code = B.Item_code
									INNER JOIN tbl_ir_header C ON C.IR_NUM = A.IR_NUM
									WHERE
									C.Ref_Number = '$Ref_Number'
									AND C.proj_Code = '$proj_Code'";
			$resTotRRQty	= $this->db->query($sqlGetTotRRQty)->result();
			foreach($resTotRRQty as $row) :
				$QtyTotMRRR = $row->Qty_RR;
				$QtyTotMRRR2 = $row->Qty_RR2;
			endforeach;
			
			// Get MR Qty Per MR tanpa ada penghitungan per item, tapi total Qty Item dalam setiap MR
			$sqlGetMRQty		= "SELECT SUM(A.request_qty1) AS request_qty1, SUM(A.request_qty2) AS request_qty2
									FROM tproject_mrdetail A
									INNER JOIN TItem B ON A.Item_code = B.Item_code
									INNER JOIN tunittype C ON C.unit_type_id = A.unit_type_id1
									INNER JOIN tproject_mrheader D ON D.MR_Number = A.MR_Number
									WHERE 
									A.MR_Number = '$Ref_Number'
									AND D.proj_Code = '$proj_Code'";
			$resMRQty	= $this->db->query($sqlGetMRQty)->result();
			foreach($resMRQty as $row) :
				$request_qty1 = $row->request_qty1;
				$request_qty2 = $row->request_qty2;
			endforeach;
			
			if($QtyTotMRRR == $request_qty1)
			{
				$sqlUpdMR		= "UPDATE tproject_mrheader SET MR_Status = 4
									WHERE MR_Number = '$Ref_Number' AND proj_Code = '$proj_Code'";
				$this->db->query($sqlUpdMR);
			}
		}
		else
		{
			// Get Total RR Qty Per PO tanpa ada penghitungan per item, tapi total Qty Item dalam setiap PO
			$sqlGetTotRRQty		= "SELECT SUM(Qty_RR) AS Qty_RR, SUM(Qty_RR2) AS Qty_RR2
									FROM trr_detail A
									INNER JOIN TItem B ON A.Item_code = B.Item_code
									INNER JOIN tbl_ir_header C ON C.IR_NUM = A.IR_NUM
									WHERE
									C.Ref_Number = '$Ref_Number'
									AND C.proj_Code = '$proj_Code'";
			$resTotRRQty	= $this->db->query($sqlGetTotRRQty)->result();
			foreach($resTotRRQty as $row) :
				$QtyTotPORR = $row->Qty_RR;
				$QtyTotPORR2 = $row->Qty_RR2;
			endforeach;
			
			// Get PO Qty Per PO per Item tanpa ada penghitungan per item, tapi total Qty Item dalam setiap PO
			$sqlGetPOQty		= "SELECT SUM(A.Qty_PO) AS Qty_PO, SUM(A.Qty_PO2) AS Qty_PO2
									FROM tpo_detail A
									INNER JOIN TItem B ON A.Item_code = B.Item_code
									INNER JOIN tbl_op_header C ON C.PO_NUM = A.PO_NUM
									WHERE
									A.PO_NUM = '$Ref_Number'
									AND C.proj_Code = '$proj_Code'";
			$resPOQty	= $this->db->query($sqlGetPOQty)->result();
			foreach($resPOQty as $row) :
				$QtyPO = $row->Qty_PO;
				$QtyPO2 = $row->Qty_PO2;
			endforeach;
			
			if($QtyTotPORR == $QtyPO)
			{
				$sqlUpdPO		= "UPDATE tbl_op_header SET PO_Status = 4
									WHERE PO_NUM = '$Ref_Number' AND proj_Code = '$proj_Code'";
				$this->db->query($sqlUpdPO);
			}
		}
		
		// ---------------- START : Pembuatan Journal Detail ----------------
		
			// Create on 11 Mei 2015. by. : Dian Hermanto
			// Persediaan Material / Inventory		xxxx
			//		AP - Hutang Usaha						xxxx
			
			$curr_rate = 1; // Default IDR ke IDR
			
			// Pemanggilan Data Account
			// Pemanggila Acc_ID dilakukan secara manual terlebih dahulu, karena belum dibuatkan menu setting Link Account.
			// Acc_ID = 20033 => PERSEDIAAN BARANG JADI 	: untuk di sisi Debit
			// Acc_ID = 20180 => HUTANG USAHA LOKAL - IDR 	: untuk di sisi Kredit
			// Save Journal Header - Debit
			$sqlGEJDD = "INSERT INTO tjournaldetail (JournalH_Code, Acc_Id, proj_ID, proj_Code, Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect)
						VALUES ('$IR_NUM', '20033', $proj_ID, '$proj_Code', '$Curr_ID', $transacValue, $transacValue, $transacValue, 'Default', $curr_rate, 0)";
			if(!$this->db->query($sqlGEJDD))
			{
				echo "Error broh";
			}
			// Update to COA - Debit
			$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue, Base_Debet2 = Base_Debet2+$transacValue,
									BaseD_$accYr = BaseD_$accYr-$transacValue
								WHERE Acc_ID = '20033'";
			$this->db->query($sqlUpdCOAD);
			
			// Save Journal Header - Kredit
			$sqlGEJDK = "INSERT INTO tjournaldetail (JournalH_Code, Acc_Id, proj_ID, proj_Code, Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect)
						VALUES ('$IR_NUM', '20180', $proj_ID, '$proj_Code', '$Curr_ID', $transacValue, $transacValue, $transacValue, 'Default', $curr_rate, 0)";
			$this->db->query($sqlGEJDK);
			// Update to COA - Kredit
			$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, Base_Kredit2 = Base_Kredit2+$transacValue,
									BaseK_$accYr = BaseK_$accYr-$transacValue
								WHERE Acc_ID = '20180'";
			$this->db->query($sqlUpdCOAD);
		
		// ---------------- END : Pembuatan Journal Detail ----------------
		
		// Update Jumlah Item di Titem dan harga Rata-Rata Item
		$qGetProjCode		= "SELECT proj_Code FROM tbl_project WHERE proj_Code = '$proj_Code'";
		$resqGetProjCode	= $this->db->query($qGetProjCode)->result();
		foreach($resqGetProjCode as $rowPC) :
			$proj_Code = $rowPC->proj_Code;
		endforeach;
		
		$qGetAVGVal	= "SELECT Item_QTY, Item_Qty2, Avg_Value, itemConvertion FROM titem WHERE ItemCodeProj = '$proj_Code' AND Item_Code = '$Item_code'";
		$resqGetAVGVal	= $this->db->query($qGetAVGVal)->result();
		foreach($resqGetAVGVal as $rowPrice) :
			$Item_QTY = $rowPrice->Item_QTY;
			$Item_Qty2 = $rowPrice->Item_Qty2;
			$Avg_Value = $rowPrice->Avg_Value;
			$itemConvertion = $rowPrice->itemConvertion;
		endforeach;
		
		$totQTY = $Item_QTY + $Qty_RR;
		$totQTY2 = $Item_Qty2 + $Qty_RR2;
		$totValAVG = ($Item_QTY * $Avg_Value) + $transacValue;
		$AvgValue = $totValAVG / $totQTY;
		
		$sqlUpdTItem		= "UPDATE titem SET Item_QTY = $totQTY, Item_Qty2 = $totQTY2, Avg_Value = $AvgValue
								WHERE ItemCodeProj = '$proj_Code' AND Item_Code = '$Item_code'";
		$this->db->query($sqlUpdTItem);		
	}
	
	// Update Material Request 2
	function updatePR2($MRNumber, $parameters)
	{
		$MRNumber 	= $MRNumber;
    	$Item_code 	= $parameters['Item_code'];
		$proj_Code 	= $parameters['proj_Code'];
    	$Qty_RRC 	= $parameters['Qty_RR'];
    	$Qty_RR2C 	= $parameters['Qty_RR2'];
		$RRSource 	= $parameters['RRSource'];
		$IR_NUM 	= $parameters['IR_NUM'];
		
		// Dapatkan jumlah IR di MR sebelum update, dikhawatirkan akan ada perubahan Qty Penerimaan
		$sql0		= "SELECT IRQty1, IRQty2 FROM tproject_mrdetail WHERE MR_Number = '$MRNumber' AND Item_code = '$Item_code'";
		$resMRQty	= $this->db->query($sql0)->result();
		foreach($resMRQty as $rowMR) :
			$IRQty1A 	= $rowMR->IRQty1; // 60
			$IRQty2A 	= $rowMR->IRQty2;
		endforeach;
		
		// Dapatkan jumlah IR sebelum update, dikhawatirkan akan ada perubahan Qty Penerimaan
		$sql1		= "SELECT Qty_RR, Qty_RR2 FROM trr_detail WHERE IR_NUM = '$IR_NUM' AND Item_code = '$Item_code'";
		$resRRQty	= $this->db->query($sql1)->result();
		foreach($resRRQty as $rowRR) :
			$Qty_RRB 	= $rowRR->Qty_RR; // 35
			$Qty_RR2B 	= $rowRR->Qty_RR2;
		endforeach;
		//echo "IRQTY_MR = $IRQty1A and RR_QTY = $Qty_RRB and New RR = $Qty_RRC";
		// Cari selisi antara inputan saat Add dengan Update
		$endRRQty1	= $IRQty1A + $Qty_RRC - $Qty_RRB;
		$endRRQty2	= $IRQty2A + $Qty_RR2C - $Qty_RR2B;			
		
		$sqlUpDet	= "UPDATE tproject_mrdetail SET IRQty1 = $endRRQty1, IRQty2 = $endRRQty2
						WHERE MR_Number = '$MRNumber' AND Item_code = '$Item_code'";	
		$this->db->query($sqlUpDet);	
	}
	
	function updatePO_VOID($IR_NUM, $PRJCODE, $PO_NUM, $ISDIRECT) // OK
	{
		// CEK APAKAH ADA IR BERDASARKAN PO
		$sql_1	= "tbl_ir_header WHERE PO_NUM = '$PO_NUM' AND ISVOID = '0'";
		$res_1	= $this->db->count_all($sql_1);
		if($res_1 == 0)
		{
			$sql_2	= "UPDATE tbl_po_header SET IR_CREATED = 0 WHERE PO_NUM = '$PO_NUM'";
			$this->db->query($sql_2);
		}
		
		$sqlGetPO	= "SELECT IR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, ACC_ID, ITM_QTY, ITM_PRICE
						FROM tbl_ir_detail
						WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$IR_NUM 		= $rowPO->IR_NUM;
			$JOBCODEDET		= $rowPO->JOBCODEDET;
			$JOBCODEID		= $rowPO->JOBCODEID;
			$ITM_CODE		= $rowPO->ITM_CODE;
			$ACC_ID			= $rowPO->ACC_ID;
			$IR_VOLM_NOW	= $rowPO->ITM_QTY;
			$IR_PRICE_NOW	= $rowPO->ITM_PRICE;
			$IR_COST_NOW	= $IR_VOLM_NOW * $IR_PRICE_NOW;
			
			// UPDATE PO DETAIL
				$IR_VOLM	= 0;
				$IR_AMOUNT	= 0;				
				$sqlGetPOD	= "SELECT IR_VOLM, IR_AMOUNT FROM tbl_po_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM'";									
				$resGetPOD	= $this->db->query($sqlGetPOD)->result();
				foreach($resGetPOD as $rowPOD) :
					$IR_VOLM 	= $rowPOD->IR_VOLM;
					$IR_AMOUNT 	= $rowPOD->IR_AMOUNT;
				endforeach;
				if($IR_VOLM == '')
					$IR_VOLM = 0;
				if($IR_AMOUNT == '')
					$IR_AMOUNT = 0;
				
				$totIRQty		= $IR_VOLM - $IR_VOLM_NOW;
				$totIRAmount	= $IR_AMOUNT - $IR_COST_NOW;
				$sqlUpd			= "UPDATE tbl_po_detail SET IR_VOLM = $totIRQty, IR_AMOUNT = $totIRAmount
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM'";
				$this->db->query($sqlUpd);
				
			// IF PO IS CREATED BY PR
				$IR_VOLM	= 0;
				$IR_AMOUNT	= 0;				
				$sqlGetPOD	= "SELECT IR_VOLM, IR_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";									
				$resGetPOD	= $this->db->query($sqlGetPOD)->result();
				foreach($resGetPOD as $rowPOD) :
					$IR_VOLM 	= $rowPOD->IR_VOLM;
					$IR_AMOUNT 	= $rowPOD->IR_AMOUNT;
				endforeach;
				if($IR_VOLM == '')
					$IR_VOLM = 0;
				if($IR_AMOUNT == '')
					$IR_AMOUNT = 0;
				
				$totIRQty		= $IR_VOLM - $IR_VOLM_NOW;
				$totIRAmount	= $IR_AMOUNT - $IR_COST_NOW;
				$sqlUpd			= "UPDATE tbl_joblist_detail SET IR_VOLM = $totIRQty, IR_AMOUNT = $totIRAmount, ITM_LASTP = $IR_PRICE_NOW
									WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpd);
				
			// UPDATE LAST PRICE iIN ITEM TABLE
				$sqlUpd1		= "UPDATE tbl_item SET ITM_LASTP = $IR_PRICE_NOW WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpd1);

			// UPDATE PR
				$sqlUpdPR	= "UPDATE tbl_pr_detail SET IR_VOLM = IR_VOLM - $IR_VOLM_NOW, IR_AMOUNT = IR_AMOUNT - $IR_COST_NOW
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpdPR);
		endforeach;
		
		// CEK QTY PO AND IR
			$ISCLOSE 	= 0;
			$sqlGetPOCV	= "SELECT SUM(PO_VOLM) AS PO_VOLM, SUM(PO_VOLM * PO_PRICE) AS TOT_POAMOUNT, SUM(PO_DISC) AS PO_DISC, SUM(IR_VOLM) AS IR_VOLM, 
								SUM(IR_AMOUNT) AS TOT_IRAMOUNT
							FROM tbl_po_detail
							WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPOCV	= $this->db->query($sqlGetPOCV)->result();
			foreach($resGetPOCV as $rowPOCV) :
				$PO_VOLM 		= $rowPOCV->PO_VOLM;
				$TOTPOAMOUNT	= $rowPOCV->TOT_POAMOUNT;
				$PO_DISC		= $rowPOCV->PO_DISC;
				$TOT_POAMOUNT	= $TOTPOAMOUNT - $PO_DISC;
				$IR_VOLM 		= $rowPOCV->IR_VOLM;
				$TOT_IRAMOUNT 	= $rowPOCV->TOT_IRAMOUNT;
				if($PO_VOLM == $IR_VOLM)
				{
					$ISCLOSE 	= 1;
					$sqlUpdPO	= "UPDATE tbl_po_header SET PO_STAT = '6', PO_ISCLOSE = '1', STATDESC = 'Close', STATCOL = 'info'
									WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUpdPO);
				}
				if($TOT_POAMOUNT == $TOT_IRAMOUNT)
				{
					$ISCLOSE 	= 1;
					$sqlUpdPO	= "UPDATE tbl_po_header SET PO_STAT = '6', PO_ISCLOSE = '1', STATDESC = 'Close', STATCOL = 'info'
									WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUpdPO);
				}			
			endforeach;
			if($ISCLOSE == 0)
			{
				$sqlUpdPO	= "UPDATE tbl_po_header SET PO_STAT = '3', PO_ISCLOSE = '0', STATDESC = 'Approve', STATCOL = 'success'
								WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpdPO);
			}
			
			// SETING
			/*$TBL_NAME		= "tbl_po_header";
			$STAT_NAME		= "PO_STAT";
			$FIELD_NM_A		= "TOT_PO_A";
			$FIELD_NM_R		= "TOT_PO_R";
			$FIELD_NM_RJ	= "TOT_PO_RJ";
			$FIELD_NM_CL	= "TOT_PO_CL";
			
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
			$this->db->query($sqlUpd1Dash);*/
		
		// UPDATE PR IF PO IS FROM PR
			/*$sqlGetPR	= "SELECT PR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, PO_VOLM, SUM(PO_VOLM * PO_PRICE) AS TOT_POAMOUNT, PO_DISC, IR_VOLM, SUM(IR_AMOUNT) AS TOT_IRAMOUNT
							FROM tbl_po_detail
							WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPR	= $this->db->query($sqlGetPR)->result();
			foreach($resGetPR as $rowPR) :
				$PR_NUM 		= $rowPR->PR_NUM;
				$JOBCODEDET		= $rowPR->JOBCODEDET;
				$JOBCODEID 		= $rowPR->JOBCODEID;
				$ITM_CODE 		= $rowPR->ITM_CODE;
				$PO_VOLM 		= $rowPR->PO_VOLM;
				$TOTPOAMOUNT	= $rowPR->TOT_POAMOUNT;
				$PO_DISC		= $rowPR->PO_DISC;
				$TOT_POAMOUNT	= $TOTPOAMOUNT - $PO_DISC;
				$IR_VOLM 		= $rowPR->IR_VOLM;
				if($IR_VOLM == '')
					$IR_VOLM = 0;
				$TOT_IRAMOUNT 	= $rowPR->TOT_IRAMOUNT;
				if($TOT_IRAMOUNT == '')
					$TOT_IRAMOUNT = 0;
				// CEK PR
				if($PR_NUM != '')
				{
					$sqlUpdPR	= "UPDATE tbl_pr_detail SET IR_VOLM = IR_VOLM - $IR_VOLM, IR_AMOUNT = IR_AMOUNT + $TOT_IRAMOUNT
									WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPR);
				}
			endforeach;*/
	}
	
	function updateIRD($IR_NUM, $PRJCODE, $ITM_CODE, $ITM_QTY_BONUS) // OK
	{
    	$sqlUpdIR	= "UPDATE tbl_ir_detail SET ITM_QTY_BONUS = ITM_QTY_BONUS + $ITM_QTY_BONUS
						WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpdIR);
		
    	$sqlUpdITM	= "UPDATE tbl_item SET ITM_VOLMBON = ITM_VOLMBON + $ITM_QTY_BONUS
						WHERE  PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpdITM);
	}
	
	function updateIR_PO($JournalH_Code, $param2) // OK
	{
		$comp_init 		= $this->session->userdata('comp_init');

		$IR_NUM 		= $param2['JournalH_Code'];
    	$IR_DATE 		= $param2['IR_DATE'];
    	$PRJCODE 		= $param2['PRJCODE'];
    	$JOBCODEID 		= $param2['JOBCODEID'];
    	$PO_NUM 		= $param2['PO_NUM'];
    	$POD_ID 		= $param2['POD_ID'];
    	$PR_NUM 		= $param2['PR_NUM'];
    	$PRD_ID 		= $param2['PRD_ID'];
    	$ITM_CODE 		= $param2['ITM_CODE'];
    	$ACC_ID 		= $param2['ACC_ID'];
    	$ITM_GROUP 		= $param2['ITM_GROUP'];
		$ITM_QTY 		= $param2['ITM_QTY'];
		$ITM_QTY_BONUS 	= $param2['ITM_QTY_BONUS'];
		$ITM_PRICE 		= $param2['ITM_PRICE'];
		$WH_CODE 		= $param2['WH_CODE'];
		$IR_NOTE2 		= $param2['IR_NOTE2'];

		$TOTQTY			= $ITM_QTY + $ITM_QTY_BONUS;
		$ITM_TOTALP		= $ITM_QTY * $ITM_PRICE;

		// 1. UPDATE PO
			$sqlUpdPOD	= "UPDATE tbl_po_detail SET IR_VOLM = IR_VOLM - $ITM_QTY, IR_AMOUNT = IR_AMOUNT - $ITM_TOTALP, ISCLOSE = 0
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
									AND PO_NUM = '$PO_NUM' AND PO_ID = $POD_ID";
			$this->db->query($sqlUpdPOD);
			
			// UBAH STATUS PO DARI CLOSE (JIKA CLOSE) MENJADI APPROVE
			// CEK ADA BERAPA IR ATAS PO INI
				$sqlPOC	= "tbl_ir_header WHERE PO_NUM = '$PO_NUM'";
				$resPOC	= $this->db->count_all($sqlPOC);
				
				// JIKA HANYA SATU, UBAH DARI PO STATUS CLOSE MANJADI APPROVE
				$sqlUpdPOH	= "UPDATE tbl_po_header SET PO_STAT = 3, PO_INVSTAT = 0, IR_CREATED = 0, PO_ISCLOSE = 0,
									STATDESC = 'Approved', STATCOL = 'success'
								WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM'";
				$this->db->query($sqlUpdPOH);
						
		// 2. MENGURANGI STOCK PADA TBL_ITEM
			// STOCK BERKURANG TAPI BUDGET BERTAMBAH
			$sqlUpITM	= "UPDATE tbl_item SET ITM_VOLMBGR = ITM_VOLMBGR + $ITM_QTY, ITM_VOLM = ITM_VOLM - $ITM_QTY,
								ITM_REMQTY = ITM_REMQTY - $ITM_QTY, ITM_TOTALP = ITM_TOTALP - $ITM_TOTALP,
								IR_VOLM = IR_VOLM - $ITM_QTY, IR_AMOUNT = IR_AMOUNT - $ITM_TOTALP,
								ITM_IN = ITM_IN - $ITM_QTY, ITM_INP = ITM_INP - $ITM_TOTALP
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpITM);
		
		// 3. MENGURANGI STOCK PADA TBL_ITEM
			// STOCK BERKURANG TAPI BUDGET BERTAMBAH
			$sqlUpJLD	= "UPDATE tbl_joblist_detail SET IR_VOLM = IR_VOLM - $ITM_QTY, IR_AMOUNT = IR_AMOUNT - $ITM_TOTALP,
								ITM_STOCK = ITM_STOCK - $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM - $ITM_TOTALP
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpJLD);
			
		// 4. MENGURANGI STOCK BONUS
			$sqlUpdIR	= "UPDATE tbl_ir_detail SET ITM_QTY_BONUS = ITM_QTY_BONUS - $ITM_QTY_BONUS
							WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpdIR);
			
			$sqlUpdITM	= "UPDATE tbl_item SET ITM_VOLMBON = ITM_VOLMBON - $ITM_QTY_BONUS
							WHERE  PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpdITM);
		
		// 5. MENGURANGI STOCK PADA WAREHOUSE
			$updITM	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITM_QTY, ITM_IN = ITM_IN - $ITM_QTY, ITM_INP = ITM_INP - $ITM_TOTALP
						WHERE PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);

		// 6. UPDATE PR
			$sqlUpdPRD	= "UPDATE tbl_pr_detail SET IR_VOLM = IR_VOLM - $ITM_QTY, IR_AMOUNT = IR_AMOUNT - $ITM_TOTALP
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
									AND PR_NUM = '$PR_NUM' AND PR_ID = $PRD_ID";
			$this->db->query($sqlUpdPRD);

		// 7. CRETAE HISTORY
			$ITM_CATEG		= '';
	    	$ITM_GROUP		= '';
			$sqlL_ICAT		= "SELECT ITM_CATEG, ITM_GROUP FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resL_ICAT 		= $this->db->query($sqlL_ICAT)->result();				
			foreach($resL_ICAT as $rowL_ICAT):
				$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				$ITM_GROUP	= $rowL_ICAT->ITM_GROUP;
			endforeach;

			$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, JournalD_Id, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$IR_NUM', '$PRJCODE', '$IR_DATE', '$ITM_CODE', 0, $ITM_QTY, 
									0, $ITM_QTY, 'V-IR', $ITM_TOTALP, '$comp_init', 'IDR', 
									'$JOBCODEID', 9, 0, '$ITM_PRICE', '$ITM_CATEG', 'Void Note. $IR_NOTE2')";
			$this->db->query($sqlHist);
	}
	
	function barang_list(){
		$hasil=$this->db->query("SELECT * FROM tbl_ir_header");
		return $hasil->result();
	}
	
	function count_allItemG($PRJCODE) // U
	{
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'M' AND B.NEEDQRC = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemG($PRJCODE) // U
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE,
							A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_STOCK, 
							A.ITM_BUDG, B.ACC_ID, B.ITM_NAME
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'M' AND B.NEEDQRC = '1'";
		return $this->db->query($sql);
	}
	
	function count_allIGreige($PRJCODE) // U
	{
		$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP = 'M' AND NEEDQRC = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewAllIGreige($PRJCODE) // U
	{
		$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBCODE, PRJCODE, ITM_CODE, ITM_UNIT,
							ITM_VOLM, ITM_PRICE, PR_VOLM, PR_AMOUNT, PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT,
							UM_VOLM AS ITM_USED, (IR_VOLM - UM_VOLM) AS ITM_STOCK, 0 AS ITM_BUDG, ACC_ID, ITM_NAME
						FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP = 'M' AND NEEDQRC = '1'";
		return $this->db->query($sql);
	}
	
	function count_allItemSubsG($PRJCODE) // U
	{
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.JOBCODEID = B.JOBCODEID
							AND B.ITM_CODE_H = A.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_TYPE = 'SUBS'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'M' AND B.NEEDQRC = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemSubsG($PRJCODE) // U
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, B.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE,
							A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_STOCK, 
							A.ITM_BUDG, B.ACC_ID, B.ITM_NAME, A.JOBDESC
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.JOBCODEID = B.JOBCODEID
							AND B.ITM_CODE_H = A.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_TYPE = 'SUBS'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'M' AND B.NEEDQRC = '1'";
		return $this->db->query($sql);
	}
	
	function count_allItemOthG($PRJCODE) // U
	{
		/*$sql		= "tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBCODEID = A.JOBCODEID
								OR B.ITM_CODE = A.ITM_CODE)";*/
		$sql		= "tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'M' AND A.NEEDQRC = '1'
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.ITM_CODE = A.ITM_CODE)";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemOthG($PRJCODE) // U
	{
		/*$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
							A.ITM_VOLM, A.ITM_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, 
							A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_VOLM AS ITM_STOCK, 
							A.ITM_VOLMBG AS ITM_BUDG, A.ACC_ID, A.ITM_NAME, '' AS JOBDESC
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T')
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBCODEID = A.JOBCODEID
								OR B.ITM_CODE = A.ITM_CODE)";*/
		$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
							A.ITM_VOLM, A.ITM_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, 
							A.IR_VOLM, A.IR_AMOUNT, A.ITM_OUT AS ITM_USED, A.ITM_VOLM AS ITM_STOCK, 
							A.ITM_VOLMBG AS ITM_BUDG, A.ACC_ID, A.ITM_NAME, '' AS JOBDESC
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'M' AND A.NEEDQRC = '1'
							AND NOT EXISTS ( SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.ITM_CODE = A.ITM_CODE)";
		return $this->db->query($sql);
	}
	
	function count_all_CustG() // OK
	{
		$sql = "tbl_customer WHERE CUST_STAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewcustomerG() // OK
	{
		$sql = "SELECT CUST_CODE, CUST_DESC, CUST_ADD1 FROM tbl_customer WHERE CUST_STAT = '1' ORDER BY CUST_DESC ASC";
		return $this->db->query($sql);
	}

	function updatePR($IR_NUM, $IR_ID, $PO_NUM, $POD_ID, $ITM_CODE, $PRJCODE, $ITM_QTY, $TOT_PRICE)
	{
		$ITM_NAME 	= "";
		$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resITM		= $this->db->query($sqlITM)->result();
		foreach($resITM as $rowITM) :
			$ITM_NAME 	= $rowITM->ITM_NAME;
		endforeach;

		$sqlPRD		= "SELECT PR_NUM, PO_CODE, PRD_ID, PR_VOLM, PR_AMOUNT, PO_VOLM, PO_COST
						FROM tbl_po_detail WHERE PO_NUM = '$PO_NUM' AND PO_ID = $POD_ID AND PRJCODE = '$PRJCODE'";
		$resPRD		= $this->db->query($sqlPRD)->result();
		foreach($resPRD as $rowPRD) :
			$PR_NUM 	= $rowPRD->PR_NUM;
			$PR_CODE 	= $rowPRD->PR_CODE;
			$PRD_ID 	= $rowPRD->PRD_ID;
			$PR_VOLM 	= $rowPRD->PR_VOLM;
			$PR_AMOUNT 	= $rowPRD->PR_AMOUNT;
			$PO_VOLM 	= $rowPRD->PO_VOLM;
			$PO_COST 	= $rowPRD->PO_COST;
		endforeach;

		$sqlUpdPR	= "UPDATE tbl_pr_detail SET IR_VOLM = IR_VOLM + $ITM_QTY, IR_AMOUNT = IR_AMOUNT + $TOT_PRICE
						WHERE PR_NUM = '$PR_NUM' AND PR_ID = $PRD_ID AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUpdPR);


		$sqlUpdIR	= "UPDATE tbl_ir_detail SET PR_NUM = '$PR_NUM', PR_CODE = '$PR_CODE', PRD_ID = $PRD_ID, ITM_NAME = '$ITM_NAME',
							PR_VOLM = $PR_VOLM, PR_AMOUNT = $PR_AMOUNT, PO_VOLM = $PO_VOLM, PO_AMOUNT = $PO_COST
						WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUpdIR);
	}

	function updateIRDET($IR_NUM, $IR_ID, $PO_NUM, $POD_ID, $ITM_CODE, $PRJCODE, $ITM_QTY, $TOT_PRICE)
	{
		$ITM_NAME 	= "";
		$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resITM		= $this->db->query($sqlITM)->result();
		foreach($resITM as $rowITM) :
			$ITM_NAME 	= $rowITM->ITM_NAME;
		endforeach;

		$sqlPRD		= "SELECT A.PR_NUM, B.PR_CODE, A.PO_CODE, A.PRD_ID, A.PR_VOLM, A.PR_AMOUNT, A.PO_VOLM, A.PO_COST
						FROM tbl_po_detail A
							INNER JOIN tbl_po_header B ON B.PO_NUM = A.PO_NUM
						WHERE A.PO_NUM = '$PO_NUM' AND A.PO_ID = $POD_ID AND A.PRJCODE = '$PRJCODE'";
		$resPRD		= $this->db->query($sqlPRD)->result();
		foreach($resPRD as $rowPRD) :
			$PR_NUM 	= $rowPRD->PR_NUM;
			$PR_CODE 	= $rowPRD->PR_CODE;
			$PRD_ID 	= $rowPRD->PRD_ID;
			$PR_VOLM 	= $rowPRD->PR_VOLM;
			$PR_AMOUNT 	= $rowPRD->PR_AMOUNT;
			$PO_VOLM 	= $rowPRD->PO_VOLM;
			$PO_COST 	= $rowPRD->PO_COST;
			
			$sqlUpdIR	= "UPDATE tbl_ir_detail SET PR_NUM = '$PR_NUM', PR_CODE = '$PR_CODE', PRD_ID = $PRD_ID, ITM_NAME = '$ITM_NAME',
								PR_VOLM = $PR_VOLM, PR_AMOUNT = $PR_AMOUNT, PO_VOLM = $PO_VOLM, PO_AMOUNT = $PO_COST
							WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpdIR);
		endforeach;
	}
	
	function createSPP($paramSPP) // OK
	{
		$IR_NUM 		= $paramSPP['IR_NUM'];
		$PR_NUM 		= $paramSPP['PR_NUM'];
		$PR_CODE 		= $paramSPP['PR_CODE'];
		$PO_NUM 		= $paramSPP['PO_NUM'];
		$PO_CODE 		= $paramSPP['PO_CODE'];
		$PRJCODE 		= $paramSPP['PRJCODE'];

		// START : CREATE SPP HEADER
			$PR_DATE 	= "0000-00-00";
			$DEPCODE 	= "";
			$s_01		= "SELECT PR_NUM, PR_CODE, PR_DATE, PR_RECEIPTD, PRJCODE, DEPCODE, PR_NOTE, PR_PLAN_IR, PR_STAT,
								PR_ISCLOSE, STATDESC, STATCOL, DOC_APPD
							FROM tbl_pr_header
							WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' LIMIT 1";
			$r_01		= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01) :
				$PR_NUM 		= $rw_01->PR_NUM;
				$PR_NUM_NEW 	= $PR_NUM."-1";
				$PR_CODE 		= $rw_01->PR_CODE;
				$PR_CODE_NEW 	= $PR_CODE."-1";
				$PR_DATE 		= $rw_01->PR_DATE;
				$PR_RECEIPTD 	= $rw_01->PR_RECEIPTD;
				$DEPCODE 		= $rw_01->DEPCODE;
				$PR_NOTE 		= $rw_01->PR_NOTE." (Penambahan dari kelebihan penerimaan).";
				$PR_PLAN_IR 	= $rw_01->PR_PLAN_IR;
				$PR_STAT 		= $rw_01->PR_STAT;
				$PR_CREATER 	= $this->session->userdata['Emp_ID'];
				$PR_CREATED 	= date('Y-m-d H:i:s');
				$PR_ISCLOSE 	= $rw_01->PR_ISCLOSE;
				$STATDESC 		= $rw_01->STATDESC;
				$STATCOL 		= $rw_01->STATCOL;
				$DOC_APPD 		= date('Y-m-d H:i:s');


				$s_ins01		= "INSERT INTO tbl_pr_header (PR_NUM, PR_CODE, PR_DATE, PR_RECEIPTD, PRJCODE, DEPCODE, PR_NOTE, PR_PLAN_IR,
										PR_STAT, PR_CREATER, PR_CREATED, PR_ISCLOSE, STATDESC, STATCOL, DOC_APPD)
									VALUES ('$PR_NUM_NEW', '$PR_CODE_NEW', '$PR_DATE', '$PR_RECEIPTD', '$PRJCODE', '$DEPCODE', '$PR_NOTE', '$PR_PLAN_IR',
										'$PR_STAT', '$PR_CREATER', '$PR_CREATED', '$PR_ISCLOSE', '$STATDESC', '$STATCOL', '$DOC_APPD')";
				$this->db->query($s_ins01);
			endforeach;
		// END : CREATE SPP HEADER

		// START : CREATE SPP DETAIL
			$noU 		= 0;
			$s_02		= "SELECT JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC, ITM_CODE, ITM_NAME, ITM_UNIT,
								ITM_QTY_REM, ITM_QTY, ADD_PRVOLM, ITM_PRICE, ITM_TOTAL
							FROM tbl_ir_detail
							WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE' AND ISPRCREATE = 1";
			$r_02		= $this->db->query($s_02)->result();
			foreach($r_02 as $rw_02) :
				$noU 			= $noU+1;
				$JOBCODEDET 	= $rw_02->JOBCODEDET;
				$JOBCODEID 		= $rw_02->JOBCODEID;
				$JOBPARENT 		= $rw_02->JOBPARENT;
				$JOBPARDESC 	= $rw_02->JOBPARDESC;
				$ITM_CODE 		= $rw_02->ITM_CODE;
				$ITM_NAME 		= $rw_02->ITM_NAME;
				$ITM_UNIT 		= $rw_02->ITM_UNIT;
				$ITM_QTY_REM 	= $rw_02->ITM_QTY_REM;
				$ITM_QTY 		= $rw_02->ITM_QTY;
				$ADD_PRVOLM 	= $rw_02->ADD_PRVOLM;
				$ITM_PRICE 		= $rw_02->ITM_PRICE;
				$ITM_TOTAL 		= $rw_02->ITM_TOTAL;

				$s_ins02		= "INSERT INTO tbl_pr_detail (PR_ID, PR_NUM, PR_CODE, PR_DATE, PRJCODE, DEPCODE,
										JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC, ITM_CODE, ITM_UNIT, 
										PR_VOLM, PR_VOLM2, PR_PRICE, PR_TOTAL, PR_DESC)
									VALUES ($noU, '$PR_NUM_NEW', '$PR_CODE_NEW', '$PR_DATE', '$PRJCODE', '$DEPCODE',
										'$JOBCODEDET', '$JOBCODEID', '$JOBPARENT', '$JOBPARDESC', '$ITM_CODE', '$ITM_UNIT', 
										'$ADD_PRVOLM', '$ADD_PRVOLM', '$ITM_PRICE', '$ITM_TOTAL', 'Penambahan kelebihan LPM')";
				$this->db->query($s_ins02);

				$s_up01 = "UPDATE tbl_po_detail SET PR_VOLM = PR_VOLM + $ADD_PRVOLM, PO_VOLM = PO_VOLM + $ADD_PRVOLM,
								PO_COST = PO_PRICE * (PO_VOLM + $ADD_PRVOLM), IS_ADDVOLPR = 1, ADD_PRVOLM = $ADD_PRVOLM
							WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_up01);
			endforeach;
		// END : CREATE SPP DETAIL
	}
	
	function get_AllDataPOC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_po_header A
                	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
					AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataPODC($PRJCODE, $PO_NUM, $IR_NUM, $search) // GOOD
	{
		$sql = "tbl_po_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
				WHERE PO_NUM = '$PO_NUM' 
					AND B.PRJCODE = '$PRJCODE' AND (A.PO_VOLM - A.IR_VOLM) > 0";
		/*$sql = "tbl_ir_detail_tmp A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
						INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
							AND C.PRJCODE = '$PRJCODE'
					WHERE 
						A.IR_NUM = '$IR_NUM' 
						AND A.PRJCODE = '$PRJCODE'";*/
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPODL($PRJCODE, $PO_NUM, $IR_NUM, $search, $length, $start, $order, $dir) // GOOD
	{
		//$sql_01 		= "INSERT INTO tbl_ir_detail_tmp SELECT * FROM tbl_ir_detail WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
		//$this->db->query($sql_01);

		$sql = "SELECT A.DEPCODE, A.PO_NUM, A.PO_CODE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, 
					A.ITM_UNIT, A.PO_VOLM AS ITM_QTY, A.PO_VOLM AS ITM_QTY_REM,
					A.PO_VOLM AS PO_VOLM, 0 AS ITM_QTY_BONUS, A.PO_PRICE AS ITM_PRICE, 
					A.PO_DISP AS ITM_DISP, A.PO_DISC AS ITM_DISC, A.JOBPARENT, A.JOBPARDESC,
					A.PO_COST AS ITM_TOTAL, A.PO_DESC AS NOTES, A.IR_VOLM, A.IR_AMOUNT,
					A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2, A.PO_ID AS POD_ID,
					B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
					B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
					B.ISFASTM, B.ISWAGE, 0 AS ISPRCREATE, 0 AS ADD_PRVOLM
				FROM tbl_po_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
				WHERE PO_NUM = '$PO_NUM' 
					AND B.PRJCODE = '$PRJCODE' AND (A.PO_VOLM - A.IR_VOLM) > 0 ORDER BY B.ITM_NAME";

		/*$sql		= "SELECT A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
						A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT,
						A.ITM_QTY_REM, A.ITM_QTY, 0 AS PO_VOLM, A.POD_ID,
						A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, A.JOBPARENT, A.JOBPARDESC,
						A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
						A.ISPRCREATE, A.ADD_PRVOLM,
						B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
						B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
						B.ISFASTM, B.ISWAGE,
						C.PR_NUM, C.PO_NUM
					FROM tbl_ir_detail_tmp A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
						INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
							AND C.PRJCODE = '$PRJCODE'
					WHERE 
						A.IR_NUM = '$IR_NUM' 
						AND A.PRJCODE = '$PRJCODE' ORDER BY B.ITM_NAME";*/

		return $this->db->query($sql);
	}
	
	function get_AllDatatTmpIRC($PRJCODE, $PO_NUM, $IR_NUM, $search) // GOOD
	{
		/*$sql = "tbl_po_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
				WHERE PO_NUM = '$PO_NUM' 
					AND B.PRJCODE = '$PRJCODE' AND (A.PO_VOLM - A.IR_VOLM) > 0";*/
		$sql = "tbl_ir_detail_tmp A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
						-- INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
							-- AND C.PRJCODE = '$PRJCODE'
					WHERE 
						A.IR_NUM = '$IR_NUM' 
						AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDatatTmpIRL($PRJCODE, $PO_NUM, $IR_NUM, $search, $length, $start, $order, $dir) // GOOD
	{
		//$sql_01 		= "INSERT INTO tbl_ir_detail_tmp SELECT * FROM tbl_ir_detail WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
		//$this->db->query($sql_01);

		/*$sql = "SELECT A.DEPCODE, A.PO_NUM, A.PO_CODE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, 
					A.ITM_UNIT, A.PO_VOLM AS ITM_QTY, A.PO_VOLM AS ITM_QTY_REM,
					A.PO_VOLM AS PO_VOLM, 0 AS ITM_QTY_BONUS, A.PO_PRICE AS ITM_PRICE, 
					A.PO_DISP AS ITM_DISP, A.PO_DISC AS ITM_DISC, A.JOBPARENT, A.JOBPARDESC,
					A.PO_COST AS ITM_TOTAL, A.PO_DESC AS NOTES, A.IR_VOLM, A.IR_AMOUNT,
					A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2, A.PO_ID AS POD_ID,
					B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
					B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
					B.ISFASTM, B.ISWAGE, 0 AS ISPRCREATE, 0 AS ADD_PRVOLM
				FROM tbl_po_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
				WHERE PO_NUM = '$PO_NUM' 
					AND B.PRJCODE = '$PRJCODE' AND (A.PO_VOLM - A.IR_VOLM) > 0 ORDER BY B.ITM_NAME";*/

		$sql		= "SELECT A.PRJCODE, A.IR_ID, A.DEPCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
						A.ACC_ID, A.PR_NUM, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT,
						A.ITM_QTY_REM, A.ITM_QTY, A.ITM_QTY_PO AS PO_VOLM, A.POD_ID,
						A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, A.JOBPARENT, A.JOBPARDESC,
						A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
						A.ISPRCREATE, A.ADD_PRVOLM, A.SJ_NUM,
						B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
						B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
						B.ISFASTM, B.ISWAGE
					FROM tbl_ir_detail_tmp A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
						/*INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
							AND C.PRJCODE = '$PRJCODE'*/
					WHERE 
						A.IR_NUM = '$IR_NUM' 
						AND A.PRJCODE = '$PRJCODE' ORDER BY B.ITM_NAME";

		return $this->db->query($sql);
	}
	
	function get_AllDataPODIREDC($PRJCODE, $PO_NUM, $IR_NUM, $search) // GOOD
	{
		$sql 	= "tbl_ir_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
						INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
							AND C.PRJCODE = '$PRJCODE'
					WHERE 
						A.IR_NUM = '$IR_NUM' 
						AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPODIREDL($PRJCODE, $PO_NUM, $IR_NUM, $search, $length, $start, $order, $dir) // GOOD
	{
		

		$sql	= "SELECT A.IR_ID, A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
						A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT,
						A.ITM_QTY_REM, A.ITM_QTY, A.ITM_QTY_PO AS PO_VOLM, A.POD_ID,
						A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, A.JOBPARENT, A.JOBPARDESC,
						A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
						A.ISPRCREATE, A.ADD_PRVOLM, A.SJ_NUM,
						B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
						B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
						B.ISFASTM, B.ISWAGE,
						C.PR_NUM, C.PO_NUM
					FROM tbl_ir_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
						INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
							AND C.PRJCODE = '$PRJCODE'
					WHERE 
						A.IR_NUM = '$IR_NUM' 
						AND A.PRJCODE = '$PRJCODE' ORDER BY B.ITM_NAME";

		return $this->db->query($sql);
	}
}
?>