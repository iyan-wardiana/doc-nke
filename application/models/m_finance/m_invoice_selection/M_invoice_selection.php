<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= M_invoice_selection.php
 * Location		= -
*/

class M_invoice_selection extends CI_Model
{	
	function count_all_INV($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'";
		}
		else
		{
			$sql = "tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'
						AND (A.INV_NUM LIKE '%$key%' ESCAPE '!' OR A.INV_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.INV_DATE LIKE '%$key%' ESCAPE '!' OR A.INV_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_last_INV($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.INV_NUM, A.INV_CODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE, A.INV_AMOUNT, A.INV_AMOUNT_PAID, A.INV_NOTES,
						A.INV_STAT, A.INV_PAYSTAT, A.DP_AMOUNT, A.INV_LISTTAXVAL
					FROM tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.INV_NUM, A.INV_CODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE, A.INV_AMOUNT, A.INV_AMOUNT_PAID, A.INV_NOTES,
						A.INV_STAT, A.INV_PAYSTAT, A.DP_AMOUNT, A.INV_LISTTAXVAL
					FROM tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'
						AND (A.INV_NUM LIKE '%$key%' ESCAPE '!' OR A.INV_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.INV_DATE LIKE '%$key%' ESCAPE '!' OR A.INV_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		/*$sql	= "SELECT INV_NUM, INV_CODE, INV_DATE, INV_DUEDATE, SPLCODE, INV_AMOUNT, INV_AMOUNT_PAID, INV_NOTES, INV_STAT,
						INV_PAYSTAT, DP_AMOUNT, INV_LISTTAXVAL
					FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE' AND INV_STAT = '3' AND INV_PAYSTAT = 'NP' AND selectedINV = '0'
					UNION ALL
					SELECT OPNI_NUM AS INV_NUM, OPNI_CODE AS INV_CODE, OPNI_DATE AS INV_DATE, OPNI_DUEDATE AS INV_DUEDATE,
						SPLCODE, OPNI_AMOUNT AS INV_AMOUNT, OPNI_AMOUNT_PAID AS INV_AMOUNT_PAID, OPNI_NOTES AS INV_NOTES,
						OPNI_STAT AS INV_STAT, OPNI_PAYSTAT AS INV_PAYSTAT, 0 AS DP_AMOUNT, 0 AS INV_LISTTAXVAL
					FROM tbl_opn_inv WHERE PRJCODE = '$PRJCODE' AND OPNI_STAT = 3 AND OPNI_PAYSTAT = 'NP' AND selectedINV = '0'";*/
		return $this->db->query($sql);
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql 	= 	"tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'
						AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
						OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.INV_NUM, A.INV_CODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE, A.INV_AMOUNT, A.INV_AMOUNT_PAID, A.INV_NOTES,
							A.INV_STAT, A.INV_PAYSTAT, A.DP_AMOUNT, A.INV_LISTTAXVAL, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, 1 AS PINTYPE
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DATE, A.JournalH_Date AS INV_DUEDATE, A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.Journal_AmountReal AS INV_AMOUNT_PAID, A.JournalH_Desc AS INV_NOTES,
							A.GEJ_STAT AS INV_STAT, 0 AS INV_PAYSTAT, 0 AS DP_AMOUNT, 0 AS INV_LISTTAXVAL, 0 AS INV_AMOUNT_PPN, 0 AS INV_AMOUNT_PPH,
							2 AS PINTYPE
						FROM tbl_journalheader_vcash A
							INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID
						WHERE A.proj_Code = '531080' AND GEJ_STAT_VCASH = '0' AND A.GEJ_STAT = '3'
							AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR A.Manual_No LIKE '%$search%' ESCAPE '!' 
							OR A.JournalH_Date LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!' 
							OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.INV_NUM, A.INV_CODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE, A.INV_AMOUNT, A.INV_AMOUNT_PAID, A.INV_NOTES,
							A.INV_STAT, A.INV_PAYSTAT, A.DP_AMOUNT, A.INV_LISTTAXVAL, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, 1 AS PINTYPE
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DATE, A.JournalH_Date AS INV_DUEDATE, A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.Journal_AmountReal AS INV_AMOUNT_PAID, A.JournalH_Desc AS INV_NOTES,
							A.GEJ_STAT AS INV_STAT, 0 AS INV_PAYSTAT, 0 AS DP_AMOUNT, 0 AS INV_LISTTAXVAL, 0 AS INV_AMOUNT_PPN, 0 AS INV_AMOUNT_PPH,
							2 AS PINTYPE
						FROM tbl_journalheader_vcash A
							INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID
						WHERE A.proj_Code = '531080' AND GEJ_STAT_VCASH = '0' AND A.GEJ_STAT = '3'
							AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR A.Manual_No LIKE '%$search%' ESCAPE '!' 
							OR A.JournalH_Date LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!' 
							OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.INV_NUM, A.INV_CODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE, A.INV_AMOUNT, A.INV_AMOUNT_PAID, A.INV_NOTES,
							A.INV_STAT, A.INV_PAYSTAT, A.DP_AMOUNT, A.INV_LISTTAXVAL, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, 1 AS PINTYPE
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DATE, A.JournalH_Date AS INV_DUEDATE, A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.Journal_AmountReal AS INV_AMOUNT_PAID, A.JournalH_Desc AS INV_NOTES,
							A.GEJ_STAT AS INV_STAT, 0 AS INV_PAYSTAT, 0 AS DP_AMOUNT, 0 AS INV_LISTTAXVAL, 0 AS INV_AMOUNT_PPN, 0 AS INV_AMOUNT_PPH,
							2 AS PINTYPE
						FROM tbl_journalheader_vcash A
							INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID
						WHERE A.proj_Code = '531080' AND GEJ_STAT_VCASH = '0' AND A.GEJ_STAT = '3'
							AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR A.Manual_No LIKE '%$search%' ESCAPE '!' 
							OR A.JournalH_Date LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!' 
							OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.INV_NUM, A.INV_CODE, A.INV_DATE, A.INV_DUEDATE, A.SPLCODE, A.INV_AMOUNT, A.INV_AMOUNT_PAID, A.INV_NOTES,
							A.INV_STAT, A.INV_PAYSTAT, A.DP_AMOUNT, A.INV_LISTTAXVAL, A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, 1 AS PINTYPE
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND selectedINV = '0' AND INV_STAT = '3'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.JournalH_Date AS INV_DATE, A.JournalH_Date AS INV_DUEDATE, A.PERSL_EMPID AS SPLCODE, A.Journal_Amount AS INV_AMOUNT, A.Journal_AmountReal AS INV_AMOUNT_PAID, A.JournalH_Desc AS INV_NOTES,
							A.GEJ_STAT AS INV_STAT, 0 AS INV_PAYSTAT, 0 AS DP_AMOUNT, 0 AS INV_LISTTAXVAL, 0 AS INV_AMOUNT_PPN, 0 AS INV_AMOUNT_PPH,
							2 AS PINTYPE
						FROM tbl_journalheader_vcash A
							INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID
						WHERE A.proj_Code = '531080' AND GEJ_STAT_VCASH = '0' AND A.GEJ_STAT = '3'
							AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR A.Manual_No LIKE '%$search%' ESCAPE '!' 
							OR A.JournalH_Date LIKE '%$search%' ESCAPE '!' OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!' 
							OR CONCAT(B.First_Name,' ', B.Last_Name) LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
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
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier WHERE SPLSTAT = '1'
				ORDER BY SPLDESC LIMIT 400";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_rowsAllMR($PRJCODE) // U
	{
		$sql = "tbl_spp_header WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function viewAllMR($PRJCODE) // U
	{
		$sql = "SELECT A.SPPNUM, A.SPPCODE, A.TRXDATE, A.PRJCODE, A.TRXOPEN, A.TRXUSER, A.APPROVE, A.APPRUSR, A.JOBCODE, A.SPPNOTE, A.SPPSTAT, REVMEMO,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
					B.First_Name, B.Middle_Name, B.Last_Name,
					C.proj_Number, C.PRJCODE, C.PRJNAME,
					D.SPLCODE, D.SPLDESC, D.SPLADD1
				FROM tbl_spp_header A
				INNER JOIN  tbl_employee B ON A.TRXUSER = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				LEFT JOIN	tbl_supplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.APPROVE = 3 
				AND A.SPPSTAT NOT IN (1,4,5)
				ORDER BY A.SPPNUM ASC";
		return $this->db->query($sql);
	}
}
?>