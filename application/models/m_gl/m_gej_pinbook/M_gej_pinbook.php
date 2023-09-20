<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Januari 2018
 * File Name	= M_gej_entry.php
 * Location		= -
*/

class M_gej_pinBook extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_journalheader_pb A 
				WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
					AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!' OR JournalH_Date LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pb A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!' OR JournalH_Date LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pb A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!' 
							OR JournalH_Date LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pb A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pb A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC1($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_journalheader A
				WHERE JournalType = 'GEJ' AND proj_Code = '$PRJCODE'
					AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Code LIKE '%$search%' ESCAPE '!' 
					OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL1($PRJCODE, $search, $length, $start) // GOOD
	{
		$sql = "SELECT A.*
				FROM tbl_journalheader A
				WHERE JournalType = 'GEJ' AND proj_Code = '$PRJCODE'
					AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Code LIKE '%$search%' ESCAPE '!' 
					OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		return $this->db->query($sql);
	}
	function count_all_GEJ($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_journalheader WHERE JournalType = 'GEJ' AND WH_ID = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_journalheader WHERE JournalType = 'GEJ' AND WH_ID = '$PRJCODE'
						AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
						OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_GEJ($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, GEJ_STAT, Manual_No
					FROM tbl_journalheader WHERE JournalType = 'GEJ' AND WH_ID = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, GEJ_STAT, Manual_No
					FROM tbl_journalheader WHERE JournalType = 'GEJ' AND WH_ID = '$PRJCODE'
						AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
						OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
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
	
	function count_all_COA($PRJCODE, $Emp_ID) // OK
	{
		$sql		= "tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.Emp_ID = '$Emp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_all_COA($PRJCODE, $Emp_ID) // OK
	{
		/*$sql		= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							-- B.PRJCODE, 
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
							INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE A.Emp_ID = '$Emp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'
						UNION ALL
						SELECT DISTINCT A.Account_Number, A.Account_NameEn, A.Account_NameId, A.Account_Class,
							-- B.PRJCODE, 
							A.Base_OpeningBalance, A.Base_Debet, A.Base_Kredit
						FROM
							tbl_chartaccount A
						WHERE A.Account_Class NOT IN (3,4) AND A.isLast = '1' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);*/
		$sql		= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							-- B.PRJCODE, 
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
							INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.Emp_ID = '$Emp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_Account($PRJCODE, $PRJPERIOD) // OK
	{
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
							AND A.WBSD_STAT = 1
						 	-- AND B.ACC_ID != ''
						 ";
		return $this->db->count_all($sql);
	}
	
	function view_all_Account($PRJCODE, $PRJPERIOD) // OK
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE_HO, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
						A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
						A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
						B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
							AND A.WBSD_STAT = 1
						 	-- AND B.ACC_ID != ''
						 ";
							
		return $this->db->query($sql);
	}
	
	function add($projGEJH) // OK
	{
		$this->db->insert('tbl_journalheader_pb', $projGEJH);
	}
	
	function addJRN($projGEJH) // OK
	{
		$this->db->insert('tbl_journalheader', $projGEJH);
	}
	
	function updateDet($PR_NUM, $PRJCODE, $PR_DATE) // OK
	{
		$sql = "UPDATE tbl_pr_detail SET PRJCODE = '$PRJCODE', PR_DATE = '$PR_DATE' WHERE PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function get_GEJ_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_journalheader_pb WHERE JournalH_Code = '$JournalH_Code'";
		return $this->db->query($sql);
	}
	
	function update($PR_NUM, $projMatReqH) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update('tbl_pr_header', $projMatReqH);
	}
	
	function deleteDetail($PR_NUM) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->delete('tbl_pr_detail');
	}
	
	function updateGEJ($JournalH_Code, $projGEJH) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_journalheader_pb', $projGEJH);
	}
	
	function deleteGEJDetail($JournalH_Code) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_journaldetail_pb');
	}
	
	function get_AllDataITMSC($PRJCODE, $search) // G
	{
		$sql = "tbl_joblist_detail A
				INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
					AND B.PRJCODE = '$PRJCODE'
					AND B.ITM_GROUP != 'M'
					AND B.ACC_ID_UM != ''
				WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
					AND A.ISLAST = 1
					AND A.ITM_UNIT = 'LS'
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
					OR A.JOBDESC LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir) // G
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE_HO, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
							A.JOBDESC, B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
							AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
							AND A.ITM_UNIT = 'LS'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE_HO, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
							A.JOBDESC, B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
							AND A.ITM_UNIT = 'LS'
							AND B.ACC_ID_UM != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE_HO, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
							A.JOBDESC, B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
							AND A.ITM_UNIT = 'LS'
							AND B.ACC_ID_UM != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE_HO, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
							A.JOBDESC, B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
							AND A.ITM_UNIT = 'LS'
							AND B.ACC_ID_UM != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataCOAC($PRJCODE, $DefEmp_ID, $search) // G
	{
		$sql = "tbl_employee_acc A
					INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.Emp_ID = '$DefEmp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'
					AND (B.Account_Number LIKE '%$search%' ESCAPE '!' OR B.Account_NameId LIKE '%$search%' ESCAPE '!'
					OR B.Account_NameEn LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataCOAL($PRJCODE, $DefEmp_ID, $search, $length, $start, $order, $dir) // G
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
							INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.Emp_ID = '$DefEmp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'
							AND (B.Account_Number LIKE '%$search%' ESCAPE '!' OR B.Account_NameId LIKE '%$search%' ESCAPE '!'
							OR B.Account_NameEn LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
							INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.Emp_ID = '$DefEmp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'
							AND (B.Account_Number LIKE '%$search%' ESCAPE '!' OR B.Account_NameId LIKE '%$search%' ESCAPE '!'
							OR B.Account_NameEn LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
							INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.Emp_ID = '$DefEmp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'
							AND (B.Account_Number LIKE '%$search%' ESCAPE '!' OR B.Account_NameId LIKE '%$search%' ESCAPE '!'
							OR B.Account_NameEn LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
							INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.Emp_ID = '$DefEmp_ID' AND B.isLast = '1' AND A.PRJCODE = '$PRJCODE'
							AND (B.Account_Number LIKE '%$search%' ESCAPE '!' OR B.Account_NameId LIKE '%$search%' ESCAPE '!'
							OR B.Account_NameEn LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllData_VTLKC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_tsflk
				WHERE PRJCODE = '$PRJCODE' AND TLK_STATUS = 3 AND TLK_USTATUS != 2
					AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!' OR TLK_DESC LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllData_VTLKL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE' AND TLK_STATUS = 3 AND TLK_USTATUS != 2
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE' AND TLK_STATUS = 3 AND TLK_USTATUS != 2
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE' AND TLK_STATUS = 3 AND TLK_USTATUS != 2
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE' AND TLK_STATUS = 3 AND TLK_USTATUS != 2
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataB_tFC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_journalheader A 
				WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
					AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!' OR JournalH_Date LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataB_tFL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!' OR JournalH_Date LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!' 
							OR JournalH_Date LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader A
						WHERE A.JournalType = 'PINBUK' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE) // G
	{
		$sql = "tbl_employee_acc A
					INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						AND B.Account_Class IN (2,3,4)
						AND B.Currency_id = '$proj_Currency'
						-- AND B.PRJCODE = '$PRJCODE'
				WHERE A.Emp_ID = '$DefEmp_ID' 
					AND B.isLast = '1'
				Order by B.Account_Category, B.Account_Number";
		return $this->db->count_all($sql);
	}
	
	function view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE) // G
	{
		$sql = "SELECT DISTINCT
					B.Acc_ID, 
					B.Account_Number, 
					B.Account_Nameen as Account_Name,
					B.Account_Category,
					B.Account_Class,			
					B.currency_ID,
                    B.Base_OpeningBalance,
                    B.Base_Debet,
                    B.Base_Kredit
				FROM tbl_employee_acc A
					INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						AND B.Account_Class IN (2,3,4)
						AND B.Currency_id = '$proj_Currency'
						-- AND B.PRJCODE = '$PRJCODE'
				WHERE A.Emp_ID = '$DefEmp_ID' 
					AND B.isLast = '1'
				Order by B.Account_Category, B.Account_Number";
		return $this->db->query($sql);
	}
	
	function get_AllDataPDSC($PRJCODE, $search) // GOOD
	{
		$sql 	= "tbl_bprop_header A
							LEFT JOIN tbl_employee B ON A.EMP_ID = B.Emp_ID
						WHERE A.PROP_STAT = 3 AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!' OR A.EMP_ID LIKE '%$search%' ESCAPE '!'
							 OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!' OR A.PROP_VALUE LIKE '%$search%' ESCAPE '!'
							 OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPDSL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.EMP_ID, A.PROP_NOTE, A.PROP_VALUE, A.PROP_TRANSFERED,
							CONCAT(B.First_Name,' ',B.Last_Name) AS EMP_NAME
						FROM tbl_bprop_header A
							LEFT JOIN tbl_employee B ON A.EMP_ID = B.Emp_ID
						WHERE A.PROP_STAT = 3 AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!' OR A.EMP_ID LIKE '%$search%' ESCAPE '!'
							 OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!' OR A.PROP_VALUE LIKE '%$search%' ESCAPE '!'
							 OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.EMP_ID, A.PROP_NOTE, A.PROP_VALUE, A.PROP_TRANSFERED,
							CONCAT(B.First_Name,' ',B.Last_Name) AS EMP_NAME
						FROM tbl_bprop_header A
							LEFT JOIN tbl_employee B ON A.EMP_ID = B.Emp_ID
						WHERE A.PROP_STAT = 3 AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!' OR A.EMP_ID LIKE '%$search%' ESCAPE '!'
							 OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!' OR A.PROP_VALUE LIKE '%$search%' ESCAPE '!'
							 OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.EMP_ID, A.PROP_NOTE, A.PROP_VALUE, A.PROP_TRANSFERED,
							CONCAT(B.First_Name,' ',B.Last_Name) AS EMP_NAME
						FROM tbl_bprop_header A
							LEFT JOIN tbl_employee B ON A.EMP_ID = B.Emp_ID
						WHERE A.PROP_STAT = 3 AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!' OR A.EMP_ID LIKE '%$search%' ESCAPE '!'
							 OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!' OR A.PROP_VALUE LIKE '%$search%' ESCAPE '!'
							 OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.EMP_ID, A.PROP_NOTE, A.PROP_VALUE, A.PROP_TRANSFERED,
							CONCAT(B.First_Name,' ',B.Last_Name) AS EMP_NAME
						FROM tbl_bprop_header A
							LEFT JOIN tbl_employee B ON A.EMP_ID = B.Emp_ID
						WHERE A.PROP_STAT = 3 AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!' OR A.EMP_ID LIKE '%$search%' ESCAPE '!'
							 OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!' OR A.PROP_VALUE LIKE '%$search%' ESCAPE '!'
							 OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>