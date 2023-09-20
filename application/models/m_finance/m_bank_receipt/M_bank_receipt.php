<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 20 Desember 2017
 * File Name	= M_bank_receipt.php
 * Location		= -
*/

class M_bank_receipt extends CI_Model
{
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_br_header A
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
						AND C.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE'
					AND A.BR_TYPE = 'BR'
					AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
					OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR'
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR'
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR'
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR'
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	function get_AllDataGRPC($PRJCODE, $CUSTCODE, $CB_STAT, $CB_SOURCE, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($CUSTCODE != '')
			$ADDQRY1 	= "AND A.BR_PAYFROM = '$CUSTCODE'";
		if($CB_STAT != 0)
			$ADDQRY2 	= "AND A.BR_STAT = '$CB_STAT'";
		if($CB_SOURCE != '')
			$ADDQRY3 	= "AND A.BR_RECTYPE = '$CB_SOURCE'";

		$sql = "tbl_br_header A
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
						AND C.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE'
					AND A.BR_TYPE = 'BR' $ADDQRY1 $ADDQRY2 $ADDQRY3
					AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
					OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $CUSTCODE, $CB_STAT, $CB_SOURCE, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($CUSTCODE != '')
			$ADDQRY1 	= "AND A.BR_PAYFROM = '$CUSTCODE'";
		if($CB_STAT != 0)
			$ADDQRY2 	= "AND A.BR_STAT = '$CB_STAT'";
		if($CB_SOURCE != '')
			$ADDQRY3 	= "AND A.BR_RECTYPE = '$CB_SOURCE'";
		
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.BR_DATE, A.BR_RECTYPE, A.BR_TYPE, C.Account_NameEn, A.BR_PAYFROM, A.BR_TOTAM,
								A.BR_NOTES, A.BR_STAT, A.ISVOID, A.PRJCODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_br_header A
							LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
								AND C.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.BR_TYPE = 'BR' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.BR_CODE LIKE '%$search%' ESCAPE '!' OR C.Account_nameen LIKE '%$search%' ESCAPE '!' 
							OR A.BR_NOTES LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.BR_DATE DESC, A.BR_CODE DESC LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_BR() // OK
	{
		$sql = "tbl_br_header A 
					INNER JOIN tbl_br_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.BR_TYPE = 'BR'";
		return $this->db->count_all($sql);
	}
	
	function get_last_BR() // OK
	{
		$sql = "SELECT  DISTINCT
					A.JournalH_Code,
					A.BR_CODE,
					A.BR_RECTYPE,
					A.ISVOID,
					A.BR_DATE, 
					A.BR_TYPE,
					A.BR_CURRID,
					A.BR_TOTAM,
					A.BR_MEMO,
					C.Account_nameen AS Account_Name,
					A.BR_PAYFROM, BR_STAT, BR_APPSTAT,
					A.BR_NOTES
				FROM tbl_br_header A 
					INNER JOIN tbl_br_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.BR_TYPE = 'BR'
				Order By A.BR_DATE DESC, A.JournalH_Code DESC";
		return $this->db->query($sql);
	}
	
	function count_all_Acc($proj_Currency, $DefEmp_ID) // OK
	{
		/*$sql = "tbl_chartaccount A
				INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
				WHERE A.Account_Class IN (3,4)
				AND A.Currency_id = '$proj_Currency'
				Order by A.Account_Category, A.Account_Number";*/
		$sql = "tbl_employee_acc A
					INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						AND B.Account_Class IN (3,4)
						AND B.Currency_id = '$proj_Currency'
						AND B.PRJCODE = (SELECT PRJCODE FROM tbl_project WHERE isHO = 1)
				WHERE A.Emp_ID = '$DefEmp_ID' 
					AND B.isLast = '1'
					AND B.PRJCODE = (SELECT PRJCODE FROM tbl_project WHERE isHO = 1)
				Order by B.Account_Category, B.Account_Number";
		return $this->db->count_all($sql);
	}
	
	function view_all_Acc($proj_Currency, $DefEmp_ID) // OK
	{
		/*$sql = "SELECT DISTINCT
					A.Acc_ID, 
					A.Account_Number, 
					A.Account_Nameen as Account_Name,
					A.Account_Category,
					A.Account_Class,			
					A.currency_ID
				FROM tbl_chartaccount A
				INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
				WHERE A.Account_Class IN (3,4)
				AND A.Currency_id = '$proj_Currency'
				Order by A.Account_Category, A.Account_Number";*/
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
						AND B.PRJCODE = (SELECT PRJCODE FROM tbl_project WHERE isHO = 1)
				WHERE A.Emp_ID = '$DefEmp_ID' 
					AND B.isLast = '1'
					AND B.PRJCODE = (SELECT PRJCODE FROM tbl_project WHERE isHO = 1)
				Order by B.Account_Category, B.Account_Number";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // ok
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_INVR_OWN($BR_PAYFROM) // OK
	{
		$sql	= "tbl_projinv_header A
					INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code 
					WHERE A.PINV_OWNER = '$BR_PAYFROM' AND A.PINV_STAT = '3' AND A.PINV_CAT != '1'";
		return $this->db->count_all($sql);
	}
	
	function view_all_INVR_OWN($BR_PAYFROM) // OK
	{
		$sql	= "SELECT DISTINCT A.PINV_CODE, A.PINV_MANNO, A.PINV_DATE, A.PINV_ENDDATE,
						A.PINV_TOTVAL, A.PINV_TOTVALPPn, A.PINV_TOTVALPPh,
						A.PINV_DPVAL, A.PINV_DPVALPPn, A.PINV_PAIDAM,
						A.PINV_NOTES, A.PINV_CAT, A.PINV_STEP, A.GPINV_TOTVAL,
						B.own_Name AS OWN_NAME
					FROM tbl_projinv_header A
						INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
					WHERE A.PINV_OWNER = '$BR_PAYFROM' AND A.PINV_STAT = '3' AND A.PINV_CAT != '1'";
		return $this->db->query($sql);
	}
	
	function count_all_INVR_CUST($BR_PAYFROM) // OK
	{
		$sql	= "tbl_sinv_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.SINV_STAT = '3' AND A.SINV_PAYSTAT != 'FR'";
		return $this->db->count_all($sql);
	}
	
	function view_all_INVR_CUST($BR_PAYFROM) // OK
	{
		$sql	= "SELECT DISTINCT A.SINV_NUM AS PINV_CODE, A.SINV_CODE AS PINV_MANNO, A.SINV_DATE AS PINV_DATE,
						A.SINV_DUEDATE AS PINV_ENDDATE, A.SINV_AMOUNT AS PINV_TOTVAL, 
						A.SINV_AMOUNT_PPN AS PINV_TOTVALPPn, A.PINV_TOTVALPPh,
						A.DP_AMOUNT AS PINV_DPVAL, 0 AS PINV_DPVALPPn, A.SINV_AMOUNT_PAID AS PINV_PAIDAM,
						A.SINV_NOTES AS PINV_NOTES, '9' AS PINV_CAT, '0' AS PINV_STEP, A.SINV_TOTAM AS GPINV_TOTVAL,
						B.CUST_DESC AS OWN_NAME
					FROM tbl_sinv_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.SINV_STAT = '3' AND A.SINV_PAYSTAT != 'FR'";
		return $this->db->query($sql);
	}
	
	function count_all_INVR_OWNDP($BR_PAYFROM) // OK
	{
		$sql	= "tbl_projinv_header A
					INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code 
					WHERE A.PINV_OWNER = '$BR_PAYFROM' AND A.PINV_STAT = '3' AND A.PINV_CAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function view_all_INVR_OWNDP($BR_PAYFROM) // OK
	{
		$sql	= "SELECT DISTINCT A.PINV_CODE, A.PINV_MANNO, A.PINV_DATE, A.PINV_ENDDATE,
						A.PINV_TOTVAL, A.PINV_TOTVALPPn, A.PINV_TOTVALPPh,
						A.PINV_DPVAL, A.PINV_DPVALPPn, A.PINV_PAIDAM,
						A.PINV_NOTES, A.PINV_CAT, A.PINV_STEP, A.GPINV_TOTVAL,
						B.own_Name AS OWN_NAME
					FROM tbl_projinv_header A
						INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
					WHERE A.PINV_OWNER = '$BR_PAYFROM' AND A.PINV_STAT = '3' AND A.PINV_CAT = '1'";
		return $this->db->query($sql);
	}
	
	function count_all_INVR_OWNPD($BR_PAYFROM, $PRJCODE) // OK
	{
		$sql	= "tbl_journalheader_pd A
						INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID
					WHERE A.PERSL_EMPID = '$BR_PAYFROM' AND A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3
					AND (A.GEJ_STAT = 3 AND A.Journal_Amount > (A.Journal_AmountReal + A.PPNH_Amount - A.PPHH_Amount + A.PPD_RemAmount) AND (A.Journal_AmountReal + A.PPNH_Amount - A.PPHH_Amount) > 0 OR IF((A.Journal_AmountReal + A.PPNH_Amount - A.PPHH_Amount) = 0, A.isManualClose = 1, A.isManualClose = 0) AND A.GEJ_STAT_PD = 3)";
		return $this->db->count_all($sql);
	}
	
	function view_all_INVR_OWNPD($BR_PAYFROM, $PRJCODE) // OK
	{
		$sql	= "SELECT DISTINCT A.JournalH_Code AS PINV_CODE, A.Manual_No AS PINV_MANNO, A.JournalH_Date AS PINV_DATE,
						A.JournalH_Date AS PINV_ENDDATE, A.Journal_Amount AS PINV_TOTVAL,
						A.PPNH_Amount AS PINV_TOTVALPPn, A.PPHH_Amount AS PINV_TOTVALPPh, 0 AS PINV_DPVAL,
						0 AS PINV_DPVALPPn, A.Journal_AmountReal AS PINV_PAIDAM,
						A.JournalH_Desc AS PINV_NOTES, A.JournalType AS PINV_CAT, 0 AS PINV_STEP,
						(A.Journal_Amount-A.Journal_AmountReal) AS GPINV_TOTVAL,
						CONCAT(B.First_Name, ' ', B.Last_Name) AS OWN_NAME
					FROM tbl_journalheader_pd A
						INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID
					WHERE A.PERSL_EMPID = '$BR_PAYFROM' AND A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3
					AND (A.GEJ_STAT = 3 AND A.Journal_Amount > (A.Journal_AmountReal + A.PPNH_Amount - A.PPHH_Amount + A.PPD_RemAmount) AND (A.Journal_AmountReal + A.PPNH_Amount - A.PPHH_Amount) > 0 OR IF((A.Journal_AmountReal + A.PPNH_Amount - A.PPHH_Amount) = 0, A.isManualClose = 1, A.isManualClose = 0) AND A.GEJ_STAT_PD = 3)";
		return $this->db->query($sql);
	}
	
	function add($inBankRec) // OK
	{
		$this->db->insert('tbl_br_header', $inBankRec);
	}
	
	function get_BR_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_br_header WHERE JournalH_Code = '$JournalH_Code'";
		return $this->db->query($sql);
	}
	
	function update($JournalH_Code, $inBankRec) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_br_header', $inBankRec);
	}
	
	function deleteDetail($JournalH_Code) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_br_detail');
	}
	
	function updateCOA($JournalH_Code, $Acc_ID, $BR_TOTAM, $BR_TOTAM_PPn) // OK
	{
		$totKREDIT	= $BR_TOTAM + $BR_TOTAM_PPn;
		$sqlUPCOA 	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit + $totKREDIT WHERE Acc_ID = '$Acc_ID'";
		$this->db->query($sqlUPCOA);
	}
	
	function count_all_BR_inb($PRJCODE) // OK
	{
		$sql = "tbl_br_header A 
					LEFT JOIN tbl_br_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE
					A.BR_TYPE = 'BR' AND BR_STAT = '2' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_BR_inb($PRJCODE) // OK
	{
		$sql = "SELECT  DISTINCT
					A.JournalH_Code,
					A.BR_CODE,
					A.BR_RECTYPE,
					A.ISVOID,
					A.BR_DATE,
					A.BR_TYPE,
					A.BR_CURRID,
					A.BR_TOTAM,
					A.BR_MEMO,
					C.Account_nameen AS Account_Name,
					A.BR_PAYFROM, BR_STAT, BR_APPSTAT,
					A.BR_NOTES
				FROM tbl_br_header A 
					LEFT JOIN tbl_br_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Account_Number
				WHERE
					A.BR_TYPE = 'BR' AND BR_STAT = '2' AND A.PRJCODE = '$PRJCODE'
				ORDER By A.BR_DATE DESC, A.JournalH_Code DESC";
		return $this->db->query($sql);
	}
	
	function updatePRINV($PRJCODE, $DocumentNo, $DocumentRef, $Inv_Amount, $Inv_Amount_PPn, $Amount, $Amount_PPn, $GAmount, $BR_NUM) // OK
	{
		$sql0		= "UPDATE tbl_projinv_header SET PINV_PAIDAM = PINV_PAIDAM + $GAmount WHERE PINV_CODE = '$DocumentRef'";
		$this->db->query($sql0);

		// UPDATE HEADER N DETAIL
			$sqlHD1	= "UPDATE tbl_br_header SET PRJCODE = '$PRJCODE' WHERE BR_NUM = '$BR_NUM'";
			$this->db->query($sqlHD1);

			$sqlHD2	= "UPDATE tbl_br_detail SET PRJCODE = '$PRJCODE' WHERE BR_NUM = '$BR_NUM'";
			$this->db->query($sqlHD2);
			
		// TOTAL REALIZATION BY PROJECT INVOICE
		/*$sql1		= "SELECT SUM(PRINV_Amount) as TotRealAmount,
							SUM(PRINV_AmountPPn) as TotRealAmountPPn,
							SUM(PRINV_AmountPPh) as TotRealAmountPPh
						FROM tbl_projinv_realh WHERE PINV_Number = '$DocumentRef'";
		$res1		= $this->db->query($sql1)->result();
		foreach($res1 as $row1):
			$TotRealAmount		= $row1->TotRealAmount;
			$TotRealAmountPPn	= $row1->TotRealAmountPPn;
			$TotRealAmountPPh	= $row1->TotRealAmountPPh;
		endforeach;*/
		$sql1		= "SELECT GPINV_TOTVAL, PINV_PAIDAM FROM tbl_projinv_header WHERE PINV_CODE = '$DocumentRef'";
		$res1		= $this->db->query($sql1)->result();
		foreach($res1 as $row1):
			$GPINV_TOTVAL	= $row1->GPINV_TOTVAL;
			$PINV_PAIDAM	= $row1->PINV_PAIDAM;
		endforeach;
		
		//if($TotRealAmount >= $Inv_Amount && $TotRealAmountPPn >= $Inv_Amount_PPn)
		if($PINV_PAIDAM >= $GPINV_TOTVAL)
		{
			$sql2		= "UPDATE tbl_projinv_header SET PINV_STAT = 6, PINV_STATD = 'FR' WHERE PINV_CODE = '$DocumentRef'";
			$this->db->query($sql2);
		}
		else
		{
			$sql2		= "UPDATE tbl_projinv_header SET PINV_STAT = 3, PINV_STATD = 'HR' WHERE PINV_CODE = '$DocumentRef'";
			$this->db->query($sql2);
		}
	}

	function updateSRINV($PRJCODE, $DocumentNo, $DocumentRef, $Inv_Amount, $Inv_Amount_PPn, $Amount, $Amount_PPn, $GAmount, $BR_NUM) // OK
	{
		$sql1		= "SELECT SINV_TOTAM, SINV_AMOUNT_PAID FROM tbl_sinv_header WHERE SINV_NUM = '$DocumentRef'";
		$res1		= $this->db->query($sql1)->result();
		foreach($res1 as $row1):
			$SINV_TOTAM1	= $row1->SINV_TOTAM;
			$SINV_PAIDAM1	= $row1->SINV_AMOUNT_PAID;
		endforeach;
		$SINV_PAIDAM2		= $SINV_PAIDAM1 + $GAmount;

		$sql0		= "UPDATE tbl_sinv_header SET SINV_AMOUNT_PAID = $SINV_PAIDAM2 WHERE SINV_NUM = '$DocumentRef'";
		$this->db->query($sql0);

		// UPDATE HEADER N DETAIL
			$sqlHD1	= "UPDATE tbl_br_header SET PRJCODE = '$PRJCODE' WHERE BR_NUM = '$BR_NUM'";
			$this->db->query($sqlHD1);

			$sqlHD2	= "UPDATE tbl_br_detail SET PRJCODE = '$PRJCODE' WHERE BR_NUM = '$BR_NUM'";
			$this->db->query($sqlHD2);
		
		//if($TotRealAmount >= $Inv_Amount && $TotRealAmountPPn >= $Inv_Amount_PPn)
		if($SINV_PAIDAM2 >= $SINV_TOTAM1)
		{
			$sql2		= "UPDATE tbl_sinv_header SET SINV_STAT = 6, STATDESC = 'Close', STATCOL = 'info', SINV_PAYSTAT = 'FR' WHERE SINV_NUM = '$DocumentRef'";
			$this->db->query($sql2);
		}
		else
		{
			$sql2		= "UPDATE tbl_sinv_header SET SINV_STAT = 3, SINV_PAYSTAT = 'HR' WHERE SINV_NUM = '$DocumentRef'";
			$this->db->query($sql2);
		}
	}
}
?>