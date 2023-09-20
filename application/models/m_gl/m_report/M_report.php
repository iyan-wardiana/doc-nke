<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Maret 2017
 * File Name	= M_report.php
 * Location		= 
*/
?>
<?php
class M_report extends CI_Model
{
	
	function get_proj_detail()
	{
		$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
		return $this->db->query($sql);
	}
}
?>