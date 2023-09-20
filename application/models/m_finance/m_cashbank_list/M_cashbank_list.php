<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Desember 2017
 * File Name	= M_cashbank_list.php
 * Location		= -
*/

class M_cashbank_list extends CI_Model
{
	function count_all_cashbank() // OK
	{
		return $this->db->count_all('tbl_cashbank');
	}
	
	function get_all_cashbank() // OK
	{		
		$sql = "SELECT * FROM tbl_cashbank ORDER BY B_NAME";
		return $this->db->query($sql);
	}
	
	function add($InCashBank) // OK
	{
		$this->db->insert('tbl_cashbank', $InCashBank);
	}
	
	function get_CASHBANK_by_code($B_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_cashbank WHERE B_CODE = '$B_CODE'";
		return $this->db->query($sql);
	}
	
	function update($B_CODE, $UpCashBank) // OK
	{
		$this->db->where('B_CODE', $B_CODE);
		return $this->db->update('tbl_cashbank', $UpCashBank);
	}
	
	function count_all_Acc($proj_Currency)
	{
		$sql = "tbl_chartaccount A
				INNER JOIN tbl_chartcategory B ON A.AccChartCategory_ID = B.ChartCategory_ID
				WHERE A.AccountClass_ID IN (3,4)
				AND A.Currency_id = '$proj_Currency'
				Order by a.AccountClass_ID, A.AccChartCategory_ID, A.Account_Number";
		return $this->db->count_all($sql);
	}
	
	function view_all_Acc($proj_Currency)
	{
		$sql = "SELECT DISTINCT
					A.Acc_ID, 
					A.Account_Number, 
					A.Account_Nameen as Account_Name,
					A.AccChartCategory_ID,
					A.AccountClass_ID,			
					A.currency_ID
				FROM tbl_chartaccount A
				INNER JOIN tbl_chartcategory B ON A.AccChartCategory_ID = B.ChartCategory_ID
				WHERE A.AccountClass_ID IN (3,4)
				AND A.Currency_id = '$proj_Currency'
				Order by a.AccountClass_ID, A.AccChartCategory_ID, A.Account_Number";
		return $this->db->query($sql);
	}
}
?>