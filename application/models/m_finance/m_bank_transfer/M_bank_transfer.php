<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 27 April 2018
 * File Name	= M_bank_transfer.php
 * Location		= -
*/

class M_bank_payment extends CI_Model
{
	function count_all_BP() // OK
	{
		/*$sql = "tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					INNER JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE 
					A.Acc_ID = B.Acc_ID
					AND A.proj_Code = '$DefProjCode'
					AND A.CB_TYPE = 'BP'";*/
		$sql = "tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.CB_TYPE = 'BP'";
		return $this->db->count_all($sql);
	}
	
	function get_last_BP() // OK
	{
		$sql = "SELECT  DISTINCT
					A.JournalH_Code, 
					A.ISVOID,
					A.CB_DATE,
					A.CB_TYPE,
					A.CB_CURRID,
					A.CB_TOTAM,
					A.CB_MEMO,
					C.Account_nameen AS Account_Name,
					A.CB_PAYFOR, CB_STAT, CB_APPSTAT,
					A.CB_NOTES
				FROM tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.CB_TYPE = 'BP'
				Order By A.CB_DATE DESC, A.JournalH_Code DESC";
		return $this->db->query($sql);
	}
	
	function count_all_Acc($proj_Currency)
	{
		$sql = "tbl_chartaccount A
				INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
				WHERE A.Account_Class IN (3,4)
				AND A.Currency_id = '$proj_Currency'
				Order by A.Account_Category, A.Account_Number";
		return $this->db->count_all($sql);
	}
	
	function view_all_Acc($proj_Currency)
	{
		$sql = "SELECT DISTINCT
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
				Order by A.Account_Category, A.Account_Number";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_SPL()
	{
		$sql	= "tbl_pinv_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.INV_STAT = '3'
					AND A.selectedINV = '1'
					AND A.INV_PAYSTAT NOT IN ('FP')
					AND A.ISVOID = '0'";
		return $this->db->count_all($sql);
	}
	
	function view_all_SPL()
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
					FROM tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.INV_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0'
					UNION ALL
					SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
					FROM tbl_opn_inv A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.OPNI_STAT = '3' AND A.selectedINV = '1' AND A.OPNI_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0'";
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
	
	function count_all_INV($SPLCODE) // OK
	{
		$sql	= "tbl_pinv_header A
					INNER JOIN tbl_pinv_detail B ON A.INV_NUM = B.INV_NUM
					WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1'";
		return $this->db->count_all($sql);
	}
	
	function view_all_INV($SPLCODE) // OK
	{
		/*$sql	= "SELECT DISTINCT A.* FROM tbl_pinv_header A
					INNER JOIN tbl_pinv_detail B ON A.INV_NUM = B.INV_NUM
					WHERE A.SPLCODE = '$SPLCODE' AND selectedINV = '1'";*/
		$sql	= "SELECT DISTINCT A.INV_NUM, A.INV_CODE, B.INV_DUEDATE, B.PO_NUM, A.PRJCODE, A.ITM_AMOUNT,
						A.TAX_AMOUNT_PPn1, A.IR_NUM, B.INV_NOTES, B.SPLCODE
					FROM tbl_pinv_detail A
						INNER JOIN tbl_pinv_header B ON A.INV_NUM = B.INV_NUM
					WHERE B.SPLCODE = '$SPLCODE' AND selectedINV = '1'
					UNION ALL
					SELECT DISTINCT A.OPNI_NUM AS INV_NUM, A.OPNI_CODE AS INV_CODE, B.OPNI_DUEDATE AS INV_DUEDATE, '' AS PO_NUM, 
						A.PRJCODE, A.OPNI_ITMTOTAL AS ITM_AMOUNT, 0 AS TAX_AMOUNT_PPn1, WO_CODE AS IR_NUM, B.OPNI_NOTES AS INV_NOTES,
						B.SPLCODE
					FROM tbl_opn_invdet A
						INNER JOIN tbl_opn_inv B ON A.OPNI_NUM = B.OPNI_NUM
					WHERE B.SPLCODE = '$SPLCODE' AND selectedINV = '1'";
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
	
	function add($inBankPay) // OK
	{
		$this->db->insert('tbl_bp_header', $inBankPay);
	}
	
	function get_CB_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_bp_header WHERE JournalH_Code = '$JournalH_Code'";
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
	
	function count_all_BP_inb() // OK
	{
		$sql = "tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.CB_TYPE = 'BP'
					AND A.CB_STAT = 2";
		return $this->db->count_all($sql);
	}
	
	function get_last_BP_inb() // OK
	{
		$sql = "SELECT  DISTINCT
					A.JournalH_Code, 
					A.ISVOID,
					A.CB_DATE,
					A.CB_TYPE,
					A.CB_CURRID,
					A.CB_TOTAM,
					A.CB_MEMO,
					C.Account_nameen AS Account_Name,
					A.CB_PAYFOR, CB_STAT, CB_APPSTAT,
					A.CB_NOTES
				FROM tbl_bp_header A 
					INNER JOIN tbl_bp_detail B ON B.journalH_code = A.journalH_code
					LEFT JOIN tbl_chartaccount C ON A.Acc_ID = C.Acc_ID
				WHERE 
					-- A.Acc_ID = B.Acc_ID AND
					A.CB_TYPE = 'BP'
					AND A.CB_STAT = 2
				Order By A.CB_DATE DESC, A.JournalH_Code DESC";
		return $this->db->query($sql);
	}
	
	function updatePINV($DocumentNo, $Amount, $Amount_PPn) // OK
	{
		$sql1		= "SELECT INV_AMOUNT FROM tbl_pinv_header WHERE INV_NUM = '$DocumentNo'";
		$res1		= $this->db->query($sql1)->result();
		foreach($res1 as $row1):
			$INV_AMOUNT		= $row1->INV_AMOUNT;
		endforeach;
		
		if($INV_AMOUNT >= $Amount)
		{
			$sql2		= "UPDATE tbl_pinv_header SET INV_AMOUNT_PAID = $Amount, INV_PAYSTAT = 'FP' WHERE INV_NUM = '$DocumentNo'";
			$this->db->query($sql2);
		}
		else
		{
			$sql2		= "UPDATE tbl_pinv_header SET INV_AMOUNT_PAID = $Amount, INV_PAYSTAT = 'HP' WHERE INV_NUM = '$DocumentNo'";
			$this->db->query($sql2)->result();
		}
	}
}
?>