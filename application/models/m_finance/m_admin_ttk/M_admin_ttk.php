<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 07 Maret 2017
 * File Name	= M_admin_ttk.php
 * Location		= -
*/
?>
<?php
class M_admin_ttk extends CI_Model
{
	function getAllFile($empID)
	{
		$sqlx	= "tbl_uploadreceipt WHERE empID = '$empID'";
		return $this->db->count_all($sqlx);
	}
	
	function getLastFile()
	{
		$sql = "SELECT FileUpName
				FROM tbl_uploadreceipt
				ORDER BY IDUpload DESC
				LIMIT 0 , 1";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsAllData()
	{
		return $this->db->count_all('mypermata');
	}
	
	function viewAllData()
	{
		$sql = "SELECT *
				FROM tbl_decreaseinvoice
				ORDER BY ttk_no";
		return $this->db->query($sql);
	}
}
?>