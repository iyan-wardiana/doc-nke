<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 28 April 2018
 * File Name	= M_cproj_payment.php
 * Location		= -
 * Notes		= CPRJ -> Cash Project
*/

class M_cproj_payment extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_journalheader A 
				WHERE A.JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
					AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_i($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_journalheader A 
				WHERE A.JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
					AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_i($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_GEJ($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_journalheader WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_journalheader 
					WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
						AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
						OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_GEJ($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
						LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader 
						WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
								OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')
						LIMIT $start, $end";
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
	
	function count_all_COA($PRJCODE, $Emp_ID) // G
	{
		$sql		= "tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE A.Emp_ID = '$Emp_ID'";
		return $this->db->count_all($sql);
	}
	
	function view_all_COA($PRJCODE, $Emp_ID, $acc_number) // G
	{
		if($acc_number == '')
		{
			$sql	= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE B.PRJCODE = '$PRJCODE' AND A.Emp_ID = '$Emp_ID' ORDER BY B.Account_Number";
		}
		else
		{
			$sql	= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE B.PRJCODE = '$PRJCODE' AND (A.Emp_ID = '$Emp_ID' OR A.Acc_Number = '$acc_number') ORDER BY B.Account_Number";
		}
		return $this->db->query($sql);
	}
	
	function count_all_Account($PRJCODE) // G
	{
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
						 	-- AND B.ACC_ID != ''
						 ";
		return $this->db->count_all($sql);
	}
	
	function view_all_Account($PRJCODE) // G
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.JOBDESC, A.PRJCODE, 
						A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, 
						A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
						A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
						B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, B.ITM_GROUP, B.ITM_CATEG
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
						 	-- AND B.ACC_ID != ''
						";
							
		return $this->db->query($sql);
	}
	
	function add($projGEJH) // G
	{
		$this->db->insert('tbl_journalheader', $projGEJH);
	}
	
	function get_CPRJ_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_journalheader WHERE JournalH_Code = '$JournalH_Code'";
		return $this->db->query($sql);
	}
	
	function update($PR_NUM, $projMatReqH) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update('tbl_pr_header', $projMatReqH);
	}
	
	function updateCPRJ($JournalH_Code, $projGEJH) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_journalheader', $projGEJH);
	}
	
	function deleteCPRJDetail($JournalH_Code) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_journaldetail');
	}
	
	function count_all_GEJOTH($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_journalheader WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_journalheader 
					WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
						AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
						OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_GEJOTH($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
						LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader 
						WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
								OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')
						LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function count_all_AccountyXP($PRJCODE) // OK
	{
		$sql		= "tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP != 'M'";
		return $this->db->count_all($sql);
	}
	
	function view_all_AccountyXP($PRJCODE) // OK
	{
		$sql		= "SELECT DISTINCT A.JOBCODEID, A.PRJCODE, A.JOBDESC,
							B.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_JOBCOST, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.WO_QTY AS PR_VOLM, A.WO_AMOUNT AS PR_AMOUNT, A.OPN_QTY AS PO_VOLM, A.OPN_AMOUNT AS PO_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_LASTP, 
							B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, 'JOBL' AS FRM
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP != 'M'
						UNION ALL
						SELECT DISTINCT JOBCODEID, A.PRJCODE, '' AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, A.ITM_TOTALP AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST, A.ADDMVOLM AS ADDM_VOLM, A.ADDMCOST AS ADDM_JOBCOST,
							A.PR_VOLM, A.PR_AMOUNT, A.PO_VOLM, A.PO_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_LASTP, 
							A.ITM_NAME, A.ACC_ID_UM AS ACC_ID, 'ITML' AS FRM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' 
							AND A.ITM_GROUP != 'M' AND A.JOBCODEID != ''";
		return $this->db->query($sql);
	}
	
	function count_all_AccountyXI($PRJCODE) // OK
	{
		$sql		= "tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE' AND B.ITM_GROUP = 'I'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'I'";
		return $this->db->count_all($sql);
	}
	
	function view_all_AccountyXI($PRJCODE) // OK
	{
		$sql		= "SELECT DISTINCT A.JOBCODEID, A.PRJCODE, A.JOBDESC,
							B.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_JOBCOST, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.WO_QTY AS PR_VOLM, A.WO_AMOUNT AS PR_AMOUNT, A.OPN_QTY AS PO_VOLM, A.OPN_AMOUNT AS PO_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_LASTP, 
							B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, 'JOBL' AS FRM
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE' AND B.ITM_GROUP = 'I'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'I'
						UNION ALL
						SELECT DISTINCT JOBCODEID, A.PRJCODE, '' AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, A.ITM_TOTALP AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST, A.ADDMVOLM AS ADDM_VOLM, A.ADDMCOST AS ADDM_JOBCOST,
							A.PR_VOLM, A.PR_AMOUNT, A.PO_VOLM, A.PO_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_LASTP, 
							A.ITM_NAME, A.ACC_ID_UM AS ACC_ID, 'ITML' AS FRM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' 
							AND A.ITM_GROUP = 'I' AND A.JOBCODEID != ''";
		return $this->db->query($sql);
	}
	
	function get_AllDataITMC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A
				WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
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
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK_AM, 
							A.IS_LEVEL, A.ISLAST,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, B.ITM_TYPE
						FROM tbl_joblist_detail A 
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK_AM, 
							A.IS_LEVEL, A.ISLAST,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, B.ITM_TYPE
						FROM tbl_joblist_detail A 
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK_AM, 
							A.IS_LEVEL, A.ISLAST,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, B.ITM_TYPE
						FROM tbl_joblist_detail A 
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK_AM, 
							A.IS_LEVEL, A.ISLAST,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, B.ITM_TYPE
						FROM tbl_joblist_detail A 
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
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
}
?>