<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 12 November 2017
 * File Name	= M_bank_payment.php
 * Location		= -
*/

class M_bank_payment extends CI_Model
{	
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $date_s, $date_e, $search) // GOOD
	{
		$sql = "tbl_bp_header A
					LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
					LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
					LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
						AND C.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE'
					AND A.CB_TYPE = 'BP'
					AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e') 
					AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
					OR C.Account_NameId LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $date_s, $date_e, $search, $length, $start, $order, $dir) // GOOD
	{
		if($order == "CB_DATE")
			$ORDBY 	= "ORDER BY A.CB_DATE $dir, A.CB_CODE ASC";
		else
			$ORDBY 	= "ORDER BY A.CB_CODE $dir, A.CB_DATE DESC";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP'
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e') 
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP'
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e') 
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP'
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e') 
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP'
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e') 
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $SPLCODE, $CB_STAT, $CB_SOURCE, $date_s, $date_e, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.CB_PAYFOR = '$SPLCODE'";
		if($CB_STAT != 0)
			$ADDQRY2 	= "AND A.CB_STAT = '$CB_STAT'";
		if($CB_SOURCE != '')
			$ADDQRY3 	= "AND A.CB_DOCTYPE = '$CB_SOURCE'";

		$sql = "tbl_bp_header A
					LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
					LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
					LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
						AND C.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE'
					AND A.CB_TYPE = 'BP' $ADDQRY1 $ADDQRY2 $ADDQRY3
					AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e')
					AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
					OR C.Account_NameId LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $SPLCODE, $CB_STAT, $CB_SOURCE, $date_s, $date_e, $search, $length, $start, $order, $dir) // GOOD
	{
		if($order == "CB_DATE")
			$ORDBY 	= "ORDER BY A.CB_DATE $dir, A.CB_CODE ASC";
		else
			$ORDBY 	= "ORDER BY A.CB_CODE $dir, A.CB_DATE DESC";
		
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";
		
		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.CB_PAYFOR = '$SPLCODE'";
		if($CB_STAT != 0)
			$ADDQRY2 	= "AND A.CB_STAT = '$CB_STAT'";
		if($CB_SOURCE != '')
			$ADDQRY3 	= "AND A.CB_DOCTYPE = '$CB_SOURCE'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e')
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY ";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e')
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY ";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e')
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY  
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.CB_DATE BETWEEN '$date_s' AND '$date_e')
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') $ORDBY  LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_BP($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP'";
		}
		else
		{
			$sql = "tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP'
						AND (A.CB_NUM LIKE '%$key%' ESCAPE '!' OR A.CB_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.CB_DATE LIKE '%$key%' ESCAPE '!' OR A.CB_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		/*$sql = "tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.CB_TYPE = 'BP'
					AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$EmpID')";*/
		return $this->db->count_all($sql);
	}
	
	function get_last_BP($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
						C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES
					FROM tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
						INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
						C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES
					FROM tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
						INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP'
						AND (A.CB_NUM LIKE '%$key%' ESCAPE '!' OR A.CB_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.CB_DATE LIKE '%$key%' ESCAPE '!' OR A.CB_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!' OR C.Account_NameId LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		/*$sql = "SELECT DISTINCT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
					C.Account_nameen AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES
				FROM tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.CB_TYPE = 'BP'
					AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$EmpID')
				Order By A.CB_DATE DESC, A.JournalH_Code DESC";*/
		return $this->db->query($sql);
	}
	
	function count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE) // G
	{
		$sql = "tbl_employee_acc A
					-- INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						-- AND B.Account_Class IN (2,3,4)
						-- AND B.Currency_id = '$proj_Currency'
						-- AND B.PRJCODE = '$PRJCODE'
				WHERE A.Emp_ID = '$DefEmp_ID' 
					-- AND B.isLast = '1'
				Order by A.Acc_Number";
		return $this->db->count_all($sql);
	}
	
	function view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE) // G
	{
		$sql = "SELECT DISTINCT
					A.Acc_Number,
					A.Acc_Name
					-- B.Acc_ID, 
					-- B.Account_Number, 
					-- B.Account_Nameen as Account_Name,
					-- B.Account_Category,
					-- B.Account_Class,			
					-- B.currency_ID,
                    -- B.Base_OpeningBalance,
                    -- B.Base_Debet,
                    -- B.Base_Kredit
				FROM tbl_employee_acc A
					-- INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						-- AND B.Account_Class IN (2,3,4)
						-- AND B.Currency_id = '$proj_Currency'
						-- AND B.PRJCODE = '$PRJCODE'
				WHERE A.Emp_ID = '$DefEmp_ID' 
					-- AND B.isLast = '1'
				Order by A.Acc_Number";
		return $this->db->query($sql);
	}

	//create by iyan date:190722
	function count_Acc_Name($proj_Currency, $DefEmp_ID, $PRJCODE, $Acc_ID)
	{
		$sql = "tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
							AND B.Account_Class IN (3,4)
							AND B.Currency_id = '$proj_Currency'
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.Emp_ID = '$DefEmp_ID'
						AND B.isLast = '1' AND B.Account_Number = '$Acc_ID'
					Order by B.Account_Category, B.Account_Number";
		return $this->db->count_all($sql);
	}

	function view_Acc_Name($proj_Currency, $DefEmp_ID, $PRJCODE, $Acc_ID)
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
							AND B.Account_Class IN (3,4)
							AND B.Currency_id = '$proj_Currency'
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.Emp_ID = '$DefEmp_ID'
						AND B.isLast = '1' AND B.Account_Number = '$Acc_ID'
					Order by B.Account_Category, B.Account_Number";
		return $this->db->query($sql);
	}

	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}

	function count_all_SPL($PRJCODE) // G
	{
		$sql	= "tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.INV_STAT = '3'
						AND A.PRJCODE = '$PRJCODE'
						AND A.selectedINV = '1'
						AND A.INV_PAYSTAT NOT IN ('FP')
						AND A.ISVOID = '0'";
		$sqlC	= $this->db->count_all($sql);
		$sql1	= "tbl_opn_inv A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.OPNI_STAT = '3'
						AND A.PRJCODE = '$PRJCODE'
						AND A.selectedINV = '1' 
						AND A.OPNI_PAYSTAT NOT IN ('FP') 
						AND A.ISVOID = '0'";
		$sqlC1	= $this->db->count_all($sql1);
		return $sqlAll	= $sqlC + $sqlC1;
	}
	
	function view_all_SPL($PRJCODE) // G
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
					FROM tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.INV_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0'
					UNION ALL
					SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
					FROM tbl_opn_inv A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.OPNI_STAT = '3' AND A.selectedINV = '1' AND A.OPNI_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0'";
		return $this->db->query($sql);
	}
	
	function count_all_INV($SPLCODE, $PRJCODE) // G
	{
		$sql	= "tbl_pinv_header A
					WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_all_INV($SPLCODE, $PRJCODE) // G
	{
		/*$sql	= "SELECT DISTINCT A.* FROM tbl_pinv_header A
					INNER JOIN tbl_pinv_detail B ON A.INV_NUM = B.INV_NUM
					WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1'";*/
		/*$sql	= "SELECT DISTINCT A.INV_NUM, A.INV_CODE, A.INV_DUEDATE, A.PO_NUM, A.PRJCODE, A.INV_AMOUNT AS ITM_AMOUNT, 
						A.INV_LISTTAXVAL AS TAX_AMOUNT_PPn1, A.IR_NUM, A.INV_NOTES, A.SPLCODE, A.INV_CATEG, A.INV_PPHVAL,
						A.INV_AMOUNT_RET
					FROM tbl_pinv_header A
						LEFT JOIN tbl_pinv_detail B ON A.INV_NUM = B.INV_NUM
					WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0'
					UNION ALL
					SELECT DISTINCT A.OPNI_NUM AS INV_NUM, A.OPNI_CODE AS INV_CODE, B.OPNI_DUEDATE AS INV_DUEDATE, '' AS PO_NUM, 
						A.PRJCODE, A.OPNI_ITMTOTAL AS ITM_AMOUNT, 0 AS TAX_AMOUNT_PPn1, WO_CODE AS IR_NUM,
						B.OPNI_NOTES AS INV_NOTES, B.SPLCODE, '' AS INV_CATEG, 0 AS INV_PPHVAL,
						0 AS INV_AMOUNT_RET
					FROM tbl_opn_invdet A
						LEFT JOIN tbl_opn_inv B ON A.OPNI_NUM = B.OPNI_NUM
					WHERE B.SPLCODE = '$SPLCODE' AND selectedINV = '1'";*/
		
		$sql	= "SELECT DISTINCT A.INV_NUM, A.INV_CODE, A.INV_DUEDATE, A.PO_NUM, A.PRJCODE, A.INV_AMOUNT AS ITM_AMOUNT, 
						A.INV_LISTTAXVAL AS TAX_AMOUNT_PPn1, A.IR_NUM, A.INV_NOTES, A.SPLCODE, A.INV_CATEG, A.INV_PPHVAL,
						A.INV_AMOUNT_RET, A.INV_AMOUNT_OTH
					FROM tbl_pinv_header A
					WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_DP($SPLCODE, $PRJCODE) // G
	{
		$sql	= "tbl_dp_header
					WHERE SPLCODE = '$SPLCODE' AND DP_STAT = '3' AND DP_PAID NOT IN (2) AND PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_all_DP($SPLCODE, $PRJCODE) // G
	{
		$sql	= "SELECT DISTINCT DP_NUM AS INV_NUM, DP_CODE AS INV_CODE, DP_DATE AS INV_DUEDATE, '' AS PO_NUM, PRJCODE,
						DP_AMOUNT AS ITM_AMOUNT, DP_AMOUNT_PPN AS TAX_AMOUNT_PPn1, '' AS IR_NUM, DP_NOTES AS INV_NOTES, SPLCODE,
						'DP' AS INV_CATEG, 0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_OTH
					FROM tbl_dp_header
					WHERE SPLCODE = '$SPLCODE' AND DP_STAT = '3' AND DP_PAID NOT IN (2) AND PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_SPL_up()
	{
		$sql	= "tbl_pinv_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.INV_STAT = '3'
					AND A.selectedINV = '1'
					AND A.ISVOID = '0'";
		return $this->db->count_all($sql);
	}
	
	function view_all_SPL_up()
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
					FROM tbl_pinv_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.INV_STAT = '3'
					AND A.selectedINV = '1'
					AND A.ISVOID = '0'";
		return $this->db->query($sql);
	}
	
	function count_all_BG($CB_CHEQNO)
	{
		$sql	= "tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
		return $this->db->count_all($sql);
	}
	
	function getAmmountBG($CB_CHEQNO)
	{
		$sql	= "SELECT BGAmmount FROM tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
		return $this->db->query($sql);
	}
	
	function count_all_UseBG($CB_CHEQNO)
	{
		$sql	= "tbl_bp_header WHERE CB_CHEQNO = '$CB_CHEQNO' AND CB_STAT NOT IN (3,5)";
		return $this->db->count_all($sql);
	}
	
	function getAmmountUseBG($CB_CHEQNO)
	{
		$sql	= "SELECT SUM(CB_TOTAM + CB_TOTAM_PPn) AS TotUsedAmmount FROM tbl_bp_header 
					WHERE CB_CHEQNO = '$CB_CHEQNO'
					AND CB_STAT NOT IN (3,5)";
		return $this->db->query($sql);
	}
	
	function count_all_GEJ() // OK
	{
		$sql	= "tbl_journalheader WHERE GEJ_STAT = '3'";
		return $this->db->count_all($sql);
	}
	
	function view_all_GEJ() // OK
	{
		$sql	= "SELECT DISTINCT * FROM tbl_journalheader WHERE GEJ_STAT = '3'";
		return $this->db->query($sql);
	}
	
	function count_all_GEJLOAN() // OK
	{
		$sql	= "tbl_journalheader WHERE GEJ_STAT = '3' AND Pattern_Type = 'LOAN'";
		return $this->db->count_all($sql);
	}
	
	function view_all_GEJLOAN() // OK
	{
		$sql	= "SELECT DISTINCT * FROM tbl_journalheader WHERE GEJ_STAT = '3' AND Pattern_Type = 'LOAN'";
		return $this->db->query($sql);
	}
	
	function add($inBankPay) // OK
	{
		$this->db->insert('tbl_bp_header', $inBankPay);
	}
	
	function get_CB_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_bp_header WHERE JournalH_Code = '$JournalH_Code'";
		return $this->db->query($sql);
	}

	function count_Doc_Inv($CB_SOURCE, $CB_NUM, $PRJCODE, $CB_PAYFOR)
	{
		if($CB_SOURCE == 'DP')
		{
			/*$sql 	= "tbl_bp_detail A
						INNER JOIN tbl_dp_header B ON B.DP_NUM = A.DocumentNo
						WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'";*/
			$sql 	= "tbl_bp_detail A
							INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
							INNER JOIN tbl_dp_header C ON C.DP_NUM = A.CBD_DOCNO
							WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'";
		}
		else if($CB_SOURCE == 'VCASH')
		{
			$sql = "tbl_bp_detail A
							INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
							INNER JOIN tbl_journalheader_vcash C ON C.JournalH_Code = A.CBD_DOCNO
							WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'";
		}
		else if($CB_SOURCE == 'OTH')
		{
			$sql = "tbl_bp_detail A
							INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
							WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_bp_detail A
			INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
			INNER JOIN tbl_pinv_header C ON C.INV_NUM = A.CBD_DOCNO
			WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'
			AND B.CB_PAYFOR = '$CB_PAYFOR'";
		}
		return $this->db->count_all($sql);

	}

	function view_Doc_Inv($CB_SOURCE, $CB_NUM, $PRJCODE, $CB_PAYFOR)
	{
		if($CB_SOURCE == 'DP')
		{
			$sql = "SELECT C.DP_CODE as docNum, C.DP_DATE as dateInv
							FROM tbl_bp_detail A
							INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
							INNER JOIN tbl_dp_header C ON C.DP_NUM = A.CBD_DOCNO
							WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'";
		}
		else if($CB_SOURCE == 'VCASH')
		{
			$sql = "SELECT C.Manual_No as docNum, C.JournalH_Date as dateInv
							FROM tbl_bp_detail A
							INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
							INNER JOIN tbl_journalheader_vcash C ON C.JournalH_Code = A.CBD_DOCNO
							WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'";
		}
		else if($CB_SOURCE == 'OTH')
		{
			$sql = "SELECT '' as docNum, '' as dateInv
							FROM tbl_bp_detail A
							INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
							WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "SELECT C.INV_CODE as docNum, C.INV_DATE as dateInv
							FROM tbl_bp_detail A
							INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
							INNER JOIN tbl_pinv_header C ON C.INV_NUM = A.CBD_DOCNO
							WHERE A.CB_NUM = '$CB_NUM' AND A.PRJCODE = '$PRJCODE'
							AND B.CB_PAYFOR = '$CB_PAYFOR'";
		}
		return $this->db->query($sql);

	}

	function update($JournalH_Code, $inBankPay) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_bp_header', $inBankPay);
	}
	
	function deleteDetail($JournalH_Code) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_bp_detail');
	}
	
	function updateCOA($JournalH_Code, $Acc_ID, $CB_TOTAM, $CB_TOTAM_PPn) // OK
	{
		$totKREDIT	= $CB_TOTAM + $CB_TOTAM_PPn;
		$sqlUPCOA 	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit + $totKREDIT WHERE Acc_ID = '$Acc_ID'";
		$this->db->query($sqlUPCOA);
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql 	= "tbl_bp_header A
						LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
						LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
						LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
						AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
						OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
						OR C.Account_NameId LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') ORDER BY A.CB_CODE ASC";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') ORDER BY A.CB_CODE ASC";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{

				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') ORDER BY A.CB_CODE ASC
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_SOURCE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.SPLDESC, CONCAT(BA.First_Name,' ', BA.Last_Name) AS complName
						FROM tbl_bp_header A
							LEFT JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							LEFT JOIN tbl_employee BA ON A.CB_PAYFOR = BA.Emp_Id
							LEFT JOIN tbl_chartaccount C ON A.CB_ACCID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR CONCAT(BA.First_Name,' ', BA.Last_Name) LIKE '%$search%' ESCAPE '!'
							OR C.Account_NameId LIKE '%$search%' ESCAPE '!') ORDER BY A.CB_CODE ASC LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2pRj($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_bp_header A 
					INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
					AND A.CB_RECTYPE = 'PRJ'
					AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2pRj($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bp_header A 
							INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND A.CB_RECTYPE = 'PRJ'
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR C.Account_NameId LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bp_header A 
							INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND A.CB_RECTYPE = 'PRJ'
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR C.Account_NameId LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bp_header A 
							INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND A.CB_RECTYPE = 'PRJ'
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR C.Account_NameId LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir 
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
							C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES,
							A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bp_header A 
							INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
							INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7)
							AND A.CB_RECTYPE = 'PRJ'
							AND (A.CB_NUM LIKE '%$search%' ESCAPE '!' OR A.CB_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CB_DATE LIKE '%$search%' ESCAPE '!' OR A.CB_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR C.Account_NameId LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_BP_inb($PRJCODE, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP'
						AND A.CB_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP'
						AND A.CB_STAT IN (2,7)
						AND (A.CB_NUM LIKE '%$key%' ESCAPE '!' OR A.CB_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.CB_DATE LIKE '%$key%' ESCAPE '!' OR A.CB_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_last_BP_inb($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
						C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES
					FROM tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
						INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP' AND A.CB_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.JournalH_Code, A.ISVOID, A.CB_CODE, A.CB_DATE, A.CB_TYPE, A.CB_CURRID, A.CB_TOTAM, A.CB_MEMO,
						C.Account_NameId AS Account_Name, A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT, A.CB_NOTES
					FROM tbl_bp_header A 
						INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
						INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.CB_TYPE = 'BP'
						AND A.CB_STAT IN (2,7)
						AND (A.CB_NUM LIKE '%$key%' ESCAPE '!' OR A.CB_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.CB_DATE LIKE '%$key%' ESCAPE '!' OR A.CB_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!' OR C.Account_NameId LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		/*$sql = "SELECT  DISTINCT
					A.JournalH_Code, 
					A.ISVOID,
					A.CB_CODE,
					A.CB_DATE,
					A.CB_TYPE,
					A.CB_CURRID,
					A.CB_TOTAM,
					A.CB_MEMO,
					C.Account_nameen AS Account_Name,
					A.CB_PAYFOR, A.CB_STAT, A.CB_APPSTAT,
					A.CB_NOTES
				FROM tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.CB_TYPE = 'BP'
					AND A.CB_STAT IN (2,7)
				Order By A.CB_DATE DESC, A.JournalH_Code DESC";*/
		return $this->db->query($sql);
	}
	
	function updatePINV($DocumentNo, $paramPINV, $JournalH_Code) // OK
	{
		// KELOMPOK NILAI PEMBAYARAN
		$Amount 		= $paramPINV['Amount'];			// Nilai Pembayaran setelah dipotong diskon (DiscAmount)
		$Amount_PPn 	= $paramPINV['Amount_PPn'];		// Tax Payment
		$PPhTax			= $paramPINV['PPhTax'];			// PPh Selected
		$PPhAmount		= $paramPINV['PPhAmount'];		// PPh Amount
		$DiscAmount		= $paramPINV['DiscAmount'];		// Potongan Pembayaran
		$DPAmount		= $paramPINV['DPAmount'];		// Potongan DP
		//echo "Kel. Pembayaran === $Amount - $Amount_PPn - $PPhTax - $PPhAmount - $DiscAmount - $DPAmount<br>";
		
		// KELOMPOK NILAI INVOICE
		$Inv_Amount		= $paramPINV['Inv_Amount'];		// Nilai yang harus Pembayaransebelum dipotong PPh Invoice
		$InvAmount_PPn	= $paramPINV['InvAmount_PPn'];	// Potongan PPh Invoice
		$InvAmount_PPh	= $paramPINV['InvAmount_PPh'];	// Potongan PPh Invoice
		$InvAmount_Ret	= $paramPINV['InvAmount_Ret'];	// Potongan Retensi Invoice
		$InvAmount_Disc	= $paramPINV['InvAmount_Disc'];	// Potongan Lainnya Invoice		
		$TOTINV_AMN		= $Inv_Amount + $InvAmount_PPn - $InvAmount_PPh - $InvAmount_Ret - $InvAmount_Disc;
		//echo "$TOTINV_AMN = $Inv_Amount + $InvAmount_PPn - $InvAmount_PPh - $InvAmount_Ret - $InvAmount_Disc<br>";
				
		//$TOTAMount	= $Amount + $Amount_PPn - $PPhAmount - $DiscAmount; 		// Total Nilai yang saat ini Dibayar
		$TOTAMountPay	= $Amount; 													// Total Nilai yang saat ini Dibayar
		$TOTAMountPay1	= $Amount + $DiscAmount + $DPAmount; 						// Total Nilai yang saat ini Dibayar
		$TOTAMountInv	= $TOTINV_AMN; 												// Total Nilai Inv yang harus Dibayar
				
		$AmountPA		= 0;
		$AmountP_PPnA	= 0;
		$sqlPAY			= "SELECT A.Amount, A.Amount_PPn 
							FROM tbl_bp_detail A
								INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM
							WHERE A.CBD_DOCNO = '$DocumentNo' 
								AND B.CB_STAT = 3
								AND A.JournalH_Code != '$JournalH_Code'";
		$resPAY			= $this->db->query($sqlPAY)->result();
		foreach($resPAY as $rowPAY) :
			$AmountP1		= $rowPAY->Amount;
			$AmountP_PPn1	= $rowPAY->Amount_PPn;
			$AmountPA		= $AmountPA + $AmountP1;
			$AmountP_PPnA	= $AmountP_PPnA + $AmountP_PPn1;
		endforeach;
		$TOTPaytoNow		= $AmountPA + $TOTAMountPay1; 							// Total Bayar sampai dengan saat ini
		
		if($TOTPaytoNow >= $TOTAMountInv)
		{
			$sql2		= "UPDATE tbl_pinv_header SET INV_AMOUNT_PAID = $TOTPaytoNow, INV_PAYSTAT = 'FP', INV_STAT = 6
							WHERE INV_NUM = '$DocumentNo'";
			$this->db->query($sql2);
		}
		else
		{
			$sql2		= "UPDATE tbl_pinv_header SET INV_AMOUNT_PAID = $TOTPaytoNow, INV_PAYSTAT = 'HP' 
							WHERE INV_NUM = '$DocumentNo'";
			$this->db->query($sql2);
		}
	}
	
	function updatePINV_NEW($CBD_DOCNO, $paramPINV, $JournalH_Code) // OK
	{
		// KELOMPOK NILAI PEMBAYARAN
		$INV_AMOUNT 	= $paramPINV['INV_AMOUNT'];
		$CBD_AMOUNT 	= $paramPINV['CBD_AMOUNT'];
		$CBD_AMOUNT_DISC= $paramPINV['CBD_AMOUNT_DISC'];
		$TOT_CUR_PAY 	= $CBD_AMOUNT + $CBD_AMOUNT_DISC;
				
		$T_CBD_AMN		= 0;
		$T_CBD_AMN_POT	= 0;
		$AmountP_PPnA	= 0;
		$sqlPAY			= "SELECT A.CBD_AMOUNT, A.CBD_AMOUNT_DISC FROM tbl_bp_detail A
								INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM
							WHERE A.CBD_DOCNO = '$CBD_DOCNO' 
								AND B.CB_STAT = 3
								AND A.JournalH_Code != '$JournalH_Code'";
		$resPAY			= $this->db->query($sqlPAY)->result();
		foreach($resPAY as $rowPAY) :
			$CBD_AMN		= $rowPAY->CBD_AMOUNT;				// NILAI PEMBAYARAN
			$CBD_AMN_DISC	= $rowPAY->CBD_AMOUNT_DISC;			// NILAI DISKON
			$T_CBD_AMN		= $T_CBD_AMN + $CBD_AMN;			// TOTAL NILAI PEMBAYARAN
			$T_CBD_AMN_POT	= $T_CBD_AMN_POT + $CBD_AMN_DISC;	// TOTAL NILAI DISKON
		endforeach;
		$TOT_BEF_PAY 		= $T_CBD_AMN + $T_CBD_AMN_POT;
		$TOTPaytoNow		= $TOT_CUR_PAY + $TOT_BEF_PAY; 							// Total Bayar sampai dengan saat ini
		
		if($TOTPaytoNow >= $INV_AMOUNT)
		{
			$sql2		= "UPDATE tbl_pinv_header SET INV_AMOUNT_PAID = $TOTPaytoNow, INV_PAYSTAT = 'FP', INV_STAT = 6
							WHERE INV_NUM = '$CBD_DOCNO'";
			$this->db->query($sql2);
		}
		else
		{
			$sql2		= "UPDATE tbl_pinv_header SET INV_AMOUNT_PAID = $TOTPaytoNow, INV_PAYSTAT = 'HP' 
							WHERE INV_NUM = '$CBD_DOCNO'";
			$this->db->query($sql2);
		}
	}
	
	function updateDP($DocumentNo, $updDP) // G
	{
		$this->db->where('DP_NUM', $DocumentNo);
		$this->db->update('tbl_dp_header', $updDP);
	}
	
	function updateDP1($DocumentNo, $Amount, $Amount_PPn) // G
	{
		$this->db->where('DP_NUM', $DocumentNo);
		$this->db->update('tbl_dp_header', $updDP);
	}
	
	function get_AllDataINVC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_pinv_header A
					WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0' AND A.PRJCODE = '$PRJCODE'
						AND (A.INV_CODE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataINVL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.INV_NUM, A.INV_CODE, A.INV_TYPE, A.INV_CATEG, A.PO_NUM, A.IR_NUM, A.PRJCODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE,
							A.INV_CURRENCY, A.INV_TAXCURR, A.DP_NUM, A.DP_AMOUNT, A.INV_AMOUNT, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, A.INV_AMOUNT_DPB,
							A.INV_AMOUNT_RET, A.INV_AMOUNT_POT, A.INV_AMOUNT_OTH, A.INV_AMOUNT_TOT, A.INV_AMOUNT_PAID, A.INV_ACC_OTH, A.INV_PPN, A.PPN_PERC,
							A.INV_PPH, A.PPH_PERC, A.INV_TERM, A.INV_PPNNUM, A.INV_PPHNUM, A.INV_LISTTAX, A.INV_LISTTAXVAL, A.INV_PPHVAL, A.INV_STAT,
							A.INV_PAYSTAT, A.COMPANY_ID, A.VENDINV_NUM, A.INV_NOTES, A.INV_NOTES1, A.REF_NOTES,
							A.INV_AMOUNT_POTOTH, A.INV_ACC_POTOTH
						FROM tbl_pinv_header A
						WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0' AND A.PRJCODE = '$PRJCODE'
							AND (A.INV_CODE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.INV_NUM, A.INV_CODE, A.INV_TYPE, A.INV_CATEG, A.PO_NUM, A.IR_NUM, A.PRJCODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE,
							A.INV_CURRENCY, A.INV_TAXCURR, A.DP_NUM, A.DP_AMOUNT, A.INV_AMOUNT, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, A.INV_AMOUNT_DPB,
							A.INV_AMOUNT_RET, A.INV_AMOUNT_POT, A.INV_AMOUNT_OTH, A.INV_AMOUNT_TOT, A.INV_AMOUNT_PAID, A.INV_ACC_OTH, A.INV_PPN, A.PPN_PERC,
							A.INV_PPH, A.PPH_PERC, A.INV_TERM, A.INV_PPNNUM, A.INV_PPHNUM, A.INV_LISTTAX, A.INV_LISTTAXVAL, A.INV_PPHVAL, A.INV_STAT,
							A.INV_PAYSTAT, A.COMPANY_ID, A.VENDINV_NUM, A.INV_NOTES, A.INV_NOTES1, A.REF_NOTES,
							A.INV_AMOUNT_POTOTH, A.INV_ACC_POTOTH
						FROM tbl_pinv_header A
						WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0' AND A.PRJCODE = '$PRJCODE'
							AND (A.INV_CODE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.INV_NUM, A.INV_CODE, A.INV_TYPE, A.INV_CATEG, A.PO_NUM, A.IR_NUM, A.PRJCODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE,
							A.INV_CURRENCY, A.INV_TAXCURR, A.DP_NUM, A.DP_AMOUNT, A.INV_AMOUNT, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, A.INV_AMOUNT_DPB,
							A.INV_AMOUNT_RET, A.INV_AMOUNT_POT, A.INV_AMOUNT_OTH, A.INV_AMOUNT_TOT, A.INV_AMOUNT_PAID, A.INV_ACC_OTH, A.INV_PPN, A.PPN_PERC,
							A.INV_PPH, A.PPH_PERC, A.INV_TERM, A.INV_PPNNUM, A.INV_PPHNUM, A.INV_LISTTAX, A.INV_LISTTAXVAL, A.INV_PPHVAL, A.INV_STAT,
							A.INV_PAYSTAT, A.COMPANY_ID, A.VENDINV_NUM, A.INV_NOTES, A.INV_NOTES1, A.REF_NOTES,
							A.INV_AMOUNT_POTOTH, A.INV_ACC_POTOTH
						FROM tbl_pinv_header A
						WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0' AND A.PRJCODE = '$PRJCODE'
							AND (A.INV_CODE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.INV_NUM, A.INV_CODE, A.INV_TYPE, A.INV_CATEG, A.PO_NUM, A.IR_NUM, A.PRJCODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE,
							A.INV_CURRENCY, A.INV_TAXCURR, A.DP_NUM, A.DP_AMOUNT, A.INV_AMOUNT, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, A.INV_AMOUNT_DPB,
							A.INV_AMOUNT_RET, A.INV_AMOUNT_POT, A.INV_AMOUNT_OTH, A.INV_AMOUNT_TOT, A.INV_AMOUNT_PAID, A.INV_ACC_OTH, A.INV_PPN, A.PPN_PERC,
							A.INV_PPH, A.PPH_PERC, A.INV_TERM, A.INV_PPNNUM, A.INV_PPHNUM, A.INV_LISTTAX, A.INV_LISTTAXVAL, A.INV_PPHVAL, A.INV_STAT,
							A.INV_PAYSTAT, A.COMPANY_ID, A.VENDINV_NUM, A.INV_NOTES, A.INV_NOTES1, A.REF_NOTES,
							A.INV_AMOUNT_POTOTH, A.INV_ACC_POTOTH
						FROM tbl_pinv_header A
						WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1' AND INV_PAYSTAT != 'FP' AND ISVOID = '0' AND A.PRJCODE = '$PRJCODE'
							AND (A.INV_CODE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataVCC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_journalheader_vcash A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_VCASH != 6
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!')";
		/*$sql 	= "tbl_journalheader_vcash A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND GEJ_STAT = '3' AND A.GEJ_STAT_VCASH = '1'
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!')";*/
		return $this->db->count_all($sql);
	}
	
	function get_AllDataVCL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				/*$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, 0 AS INV_AMOUNT_PPN, 0 AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH,
							A.Journal_Amount AS INV_AMOUNT_TOT, 0 AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_vcash A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND GEJ_STAT = 3 AND A.GEJ_STAT_VCASH = 1
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";*/
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_vcash A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_VCASH != 6
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_vcash A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_VCASH != 6
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_vcash A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_VCASH != 6
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_vcash A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_VCASH != 6
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataPPDC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_journalheader_pd A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_PPD = 2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPPDL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_PPD = 2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_PPD = 2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_PPD = 2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3 AND A.GEJ_STAT_PPD = 2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataPDC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_journalheader_pd A
						WHERE A.proj_Code = '$PRJCODE' AND A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPDL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.proj_Code = '$PRJCODE' AND A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.proj_Code = '$PRJCODE' AND A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.proj_Code = '$PRJCODE' AND A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DUEDATE,
							A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.PPNH_Amount AS INV_AMOUNT_PPN, A.PPHH_Amount AS INV_AMOUNT_PPH,
							0 AS INV_AMOUNT_DPB,0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_POT, 0 AS INV_AMOUNT_OTH, A.Journal_AmountReal AS INV_AMOUNT_PAID, 0 AS INV_ACC_OTH, 0 AS INV_PPN, 0 AS PPN_PERC,
							0 AS INV_PPH, 0 AS PPH_PERC, A.JournalH_Desc AS INV_NOTES
						FROM tbl_journalheader_pd A
						WHERE A.proj_Code = '$PRJCODE' AND A.PERSL_EMPID = '$SPLCODE' AND A.GEJ_STAT = 3
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataDPC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_dp_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.SPLCODE = '$SPLCODE' AND DP_STAT = '3' AND A.DP_PAID NOT IN (2) AND A.PRJCODE = '$PRJCODE'
						AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_REFCODE LIKE '%$search%' ESCAPE '!'
						OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataDPL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.DP_NUM AS INV_NUM, A.DP_CODE AS INV_CODE, A.DP_DATE AS INV_DUEDATE, '' AS PO_NUM, A.PRJCODE,
							A.DP_AMOUNT AS ITM_AMOUNT, DP_AMOUNT_PPN AS TAX_AMOUNT_PPN1, DP_AMOUNT_PPH AS TAX_AMOUNT_PPH1, '' AS IR_NUM, A.DP_NOTES AS INV_NOTES, A.SPLCODE,
							'DP' AS INV_CATEG, 0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_OTH
						FROM tbl_dp_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.SPLCODE = '$SPLCODE' AND DP_STAT = '3' AND A.DP_PAID NOT IN (2) AND A.PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_REFCODE LIKE '%$search%' ESCAPE '!'
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.DP_NUM AS INV_NUM, A.DP_CODE AS INV_CODE, A.DP_DATE AS INV_DUEDATE, '' AS PO_NUM, A.PRJCODE,
							A.DP_AMOUNT AS ITM_AMOUNT, DP_AMOUNT_PPN AS TAX_AMOUNT_PPN1, DP_AMOUNT_PPH AS TAX_AMOUNT_PPH1, '' AS IR_NUM, A.DP_NOTES AS INV_NOTES, A.SPLCODE,
							'DP' AS INV_CATEG, 0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_OTH
						FROM tbl_dp_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.SPLCODE = '$SPLCODE' AND DP_STAT = '3' AND A.DP_PAID NOT IN (2) AND A.PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_REFCODE LIKE '%$search%' ESCAPE '!'
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.DP_NUM AS INV_NUM, A.DP_CODE AS INV_CODE, A.DP_DATE AS INV_DUEDATE, '' AS PO_NUM, A.PRJCODE,
							A.DP_AMOUNT AS ITM_AMOUNT, DP_AMOUNT_PPN AS TAX_AMOUNT_PPN1, DP_AMOUNT_PPH AS TAX_AMOUNT_PPH1, '' AS IR_NUM, A.DP_NOTES AS INV_NOTES, A.SPLCODE,
							'DP' AS INV_CATEG, 0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_OTH
						FROM tbl_dp_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.SPLCODE = '$SPLCODE' AND DP_STAT = '3' AND A.DP_PAID NOT IN (2) AND A.PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_REFCODE LIKE '%$search%' ESCAPE '!'
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.DP_NUM AS INV_NUM, A.DP_CODE AS INV_CODE, A.DP_DATE AS INV_DUEDATE, '' AS PO_NUM, A.PRJCODE,
							A.DP_AMOUNT AS ITM_AMOUNT, DP_AMOUNT_PPN AS TAX_AMOUNT_PPN1, DP_AMOUNT_PPH AS TAX_AMOUNT_PPH1, '' AS IR_NUM, A.DP_NOTES AS INV_NOTES, A.SPLCODE,
							'DP' AS INV_CATEG, 0 AS INV_AMOUNT_RET, 0 AS INV_AMOUNT_OTH
						FROM tbl_dp_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.SPLCODE = '$SPLCODE' AND DP_STAT = '3' AND A.DP_PAID NOT IN (2) AND A.PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_REFCODE LIKE '%$search%' ESCAPE '!'
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>