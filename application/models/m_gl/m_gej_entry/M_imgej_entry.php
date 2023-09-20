<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 09 April 2022
 * File Name	= M_imgej_entry.php
 * Location		= -
*/

class M_imgej_entry extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_journalheader_imp A 
				WHERE A.JournalType = 'IMP-TRX' AND proj_Code = '$PRJCODE'
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
						FROM tbl_journalheader_imp A
						WHERE A.JournalType = 'IMP-TRX' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!' OR JournalH_Date LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_imp A
						WHERE A.JournalType = 'IMP-TRX' AND proj_Code = '$PRJCODE'
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
						FROM tbl_journalheader_imp A
						WHERE A.JournalType = 'IMP-TRX' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_imp A
						WHERE A.JournalType = 'IMP-TRX' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC1($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_journalheader_imp A
				WHERE JournalType = 'IMP-TRX' AND proj_Code = '$PRJCODE'
					AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Code LIKE '%$search%' ESCAPE '!' 
					OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL1($PRJCODE, $search, $length, $start) // GOOD
	{
		$sql = "SELECT A.*
				FROM tbl_journalheader_imp A
				WHERE JournalType = 'IMP-TRX' AND proj_Code = '$PRJCODE'
					AND (Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Code LIKE '%$search%' ESCAPE '!' 
					OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		return $this->db->query($sql);
	}
	function count_all_GEJ($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_journalheader_imp WHERE JournalType = 'IMP-TRX' AND WH_ID = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_journalheader_imp WHERE JournalType = 'IMP-TRX' AND WH_ID = '$PRJCODE'
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
					FROM tbl_journalheader_imp WHERE JournalType = 'IMP-TRX' AND WH_ID = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, GEJ_STAT, Manual_No
					FROM tbl_journalheader_imp WHERE JournalType = 'IMP-TRX' AND WH_ID = '$PRJCODE'
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
		$this->db->insert('tbl_journalheader_imp', $projGEJH);
	}
	
	function updateDet($PR_NUM, $PRJCODE, $PR_DATE) // OK
	{
		$sql = "UPDATE tbl_pr_detail SET PRJCODE = '$PRJCODE', PR_DATE = '$PR_DATE' WHERE PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function get_GEJ_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_journalheader_imp WHERE JournalH_Code = '$JournalH_Code'";
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
		$this->db->update('tbl_journalheader_imp', $projGEJH);
	}
	
	function deleteGEJDetail($JournalH_Code) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_journaldetail_imp');
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
	
	function get_AllDataDetC($JournalH_Code, $search) // GOOD
	{
		$sql = "tbl_journaldetail_imp A
						WHERE A.JournalType = 'IMP-TRX' AND JournalH_Code = '$JournalH_Code'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR Other_Desc LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataDetL($JournalH_Code, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journaldetail_imp A
						WHERE A.JournalType = 'IMP-TRX' AND JournalH_Code = '$JournalH_Code'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR Other_Desc LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journaldetail_imp A
						WHERE A.JournalType = 'IMP-TRX' AND JournalH_Code = '$JournalH_Code'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR Other_Desc LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journaldetail_imp A
						WHERE A.JournalType = 'IMP-TRX' AND JournalH_Code = '$JournalH_Code'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR Other_Desc LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journaldetail_imp A
						WHERE A.JournalType = 'IMP-TRX' AND JournalH_Code = '$JournalH_Code'
							AND (Manual_No LIKE '%$search%' ESCAPE '!' OR Other_Desc LIKE '%$search%' ESCAPE '!'
							OR JournalH_Date LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>