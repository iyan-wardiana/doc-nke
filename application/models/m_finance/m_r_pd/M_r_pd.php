<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 2 Desember 2021
	* File Name		= M_r_pd.php
	* Location		= -
*/

class M_r_pd extends CI_Model
{
	public function __construct() 												// 1
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) 									// 2
	{
		$sql = "tbl_journalheader_pd A 
				WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE'
					AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Reference_Number LIKE '%$search%' ESCAPE '!'
					OR Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) 	// 3
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR Reference_Number LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created DESC";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR Reference_Number LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR Reference_Number LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created DESC
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE'
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!' OR Reference_Number LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search) 									// 2
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.PERSL_EMPID = '$SPLCODE'";
		if($GEJ_STAT != 0)
			$ADDQRY2 	= "AND A.GEJ_STAT = '$GEJ_STAT'";
		if($GEJ_CATEG != '')
			$ADDQRY3 	= "AND A.GEJ_CATEG = '$GEJ_CATEG'";

		$sql = "tbl_journalheader_pd A 
				WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
					AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!' OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search, $length, $start, $order, $dir) 	// 3
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.PERSL_EMPID = '$SPLCODE'";
		if($GEJ_STAT != 0)
			$ADDQRY2 	= "AND A.GEJ_STAT = '$GEJ_STAT'";
		if($GEJ_CATEG != '')
			$ADDQRY3 	= "AND A.GEJ_CATEG = '$GEJ_CATEG'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created DESC";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
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
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created DESC
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_journalheader_pd A
						WHERE A.ISPERSL = 1 AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (JournalH_Code LIKE '%$search%' ESCAPE '!' OR Manual_No LIKE '%$search%' ESCAPE '!'
							OR JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_CHO_by_number($JournalH_Code) 									// 4
	{
		$sql = "SELECT * FROM tbl_journalheader_pd WHERE JournalH_Code = '$JournalH_Code'";
		return $this->db->query($sql);
	}
	
	function count_all_COA($PRJCODE, $Emp_ID) 									// 5
	{
		$sql	= "tbl_employee_acc A
					INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
					WHERE A.Emp_ID = '$Emp_ID'";
		return $this->db->count_all($sql);
	}
	
	function view_all_COA($PRJCODE, $Emp_ID, $acc_number)						// 6
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
	
	function delPDRDet($JournalH_Code, $nextStep) 									// 7
	{
		$sql = "DELETE FROM tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code'
				AND ISPERSL = 1 AND ISPERSL_REALIZ = 1 AND ISPERSL_STEP = $nextStep";
		$this->db->query($sql);

		$sql = "DELETE FROM tbl_journaldetail_pd WHERE JournalH_Code = '$JournalH_Code'
				AND ISPERSL = 1 AND ISPERSL_REALIZ = 1 AND ISPERSL_STEP = $nextStep";
		$this->db->query($sql);
	}
	
	function updateCHO($JournalH_Code, $projGEJH)								// 8
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_journalheader', $projGEJH);

		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_journalheader_pd', $projGEJH);
	}
	
	function get_AllDataITMC($PRJCODE, $search) 								// 9
	{
		$sql = "tbl_joblist_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
				WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // 10
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
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
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
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
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
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
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
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>