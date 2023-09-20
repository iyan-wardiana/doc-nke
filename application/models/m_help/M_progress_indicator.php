<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2017
 * File Name	= M_progress_indicator.php
 * Location		= -
*/
?>
<?php
class M_progress_indicator extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_indikator ');
	}
	
	function get_last_ten_indic() // OK
	{		
		$sql = "SELECT * FROM tbl_indikator 
				ORDER BY IK_DESC";
		return $this->db->query($sql);
	}
	
	function add($indic) // OK
	{
		$this->db->insert('tbl_indikator', $indic);
	}
	
	function get_indic_by_code($IK_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_indikator  WHERE IK_CODE = '$IK_CODE'";
		return $this->db->query($sql);
	}
	
	function update($IK_CODE, $indic) // OK
	{
		$this->db->where('IK_CODE', $IK_CODE);
		$this->db->update('tbl_indikator ', $indic);
	}
}
?>