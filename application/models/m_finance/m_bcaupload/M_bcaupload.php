<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Maret 2017
 * File Name	= M_bcaupload.php
 * Location		= -
*/
?>
<?php
class M_bcaupload extends CI_Model
{
	function getAllFile($empID)
	{
		$sqlx	= "tbl_uploadbca WHERE empID = '$empID'";
		return $this->db->count_all($sqlx);
	}
	
	function viewAllData()
	{
		$sql = "SELECT *
				FROM tbl_uploadbca_data";
		return $this->db->query($sql);
	}	
}
?>