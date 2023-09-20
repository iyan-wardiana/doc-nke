<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Maret 2017
 * File Name	= M_ttkestpembayaran.php
 * Location		= -
*/
?>
<?php
class M_ttkestpembayaran extends CI_Model
{	
	function getAllFile($empID)
	{
		$sqlx	= "tbl_uploadttkest WHERE empID = '$empID'";
		return $this->db->count_all($sqlx);
	}
	
	function getLastFile()
	{
		$sql = "SELECT FileUpName
				FROM uploadttkest
				ORDER BY IDUpload DESC
				LIMIT 0 , 1";
		return $this->db->query($sql);
	}
	
	function viewAllData()
	{
		$sql = "SELECT *
				FROM tbl_ttkestinvoice
				ORDER BY VOCCODE";
		return $this->db->query($sql);
	}	
}
?>